<?php
/**
* @package RSMembership!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

if (file_exists(JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/rsmembership.php')) {
	require_once JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/rsmembership.php';
}

class plgSystemRSMembership2CO extends JPlugin
{
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$this->_loadLanguage();
		
		if ($this->canRun() && $this->params) {
			$name = $this->params->get('payment_name','2Checkout');
			RSMembership::addPlugin($this->getTranslation($name), 'rsmembership2co'); 
		}
	}
	
	protected function getTranslation($text) {
		$lang = JFactory::getLanguage();
		$key  = str_replace(' ', '_', $text);
		if ($lang->hasKey($key)) {
			return JText::_($key);
		} else {
			return $text;
		}
	}

	protected function canRun()  {
		return file_exists(JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/rsmembership.php');
	}

	protected function _getTax($price) {
		$tax_value = $this->params->get('tax_value');

		if (!empty($tax_value))
		{
			$tax_type = $this->params->get('tax_type');
			
			// percent ?
			if ($tax_type == 0)
				$tax_value = $price * ($tax_value / 100);
		}
		
		return $tax_value;
	}
	
	public function onMembershipPayment($plugin, $data, $extra, $membership, $transaction, $html) {
		if ( !$this->canRun() ) return;
		if ( $plugin != 'rsmembership2co' ) return false;

		// Set tax.
		$transaction->price += $this->_getTax($transaction->price);
		
		// Set the custom hash.
		$transaction->custom = md5($transaction->params.' '.uniqid('2checkout'));
		
		// Is it a demo purchase?
		$demo = !$this->params->get('mode');
		
		// Set the URL for the form
		$url = sprintf('https://%s.2checkout.com/checkout/purchase', ($demo ? 'sandbox' : 'www'));
		
		// Set our vars
		$vars = array(
			'sid'				 => $this->params->get('id'),
			'mode'				 => '2CO',
			'li_0_name' 		 => $this->params->get('message_type') ? $membership->name : JText::sprintf( 'PLG_SYSTEM_RSMEMBERSHIP2CO_MEMBERSHIP_PURCHASE_ON', RSMembershipHelper::showDate($transaction->date) ),
			'li_0_price' 		 => $this->_convertNumber($transaction->price),
			'x_receipt_link_url' => JUri::root().'index.php?option=com_rsmembership&oldtwocopayment=1',
			'currency_code'		 => RSMembershipHelper::getConfig('currency'),
			'custom'			 => $transaction->custom,
			'vendor_order_id'	 => $transaction->custom
		);
		
		if ($membership->recurring && $membership->period > 0) {
			$vars['li_0_recurrence'] = $this->getRecurrence($membership->period, $membership->period_type);
			
			// Get duration.
			if ($membership->recurring_times) {
				if (preg_match('#[0-9]+#', $vars['li_0_recurrence'], $match)) {
					$vars['li_0_duration'] = str_replace($match[0], $match[0] * $membership->recurring_times, $vars['li_0_recurrence']);
				}
			} else {
				$vars['li_0_duration'] = 'Forever';
			}
		}
		
		// Add demo mode.
		if ($demo) {
			$vars['demo'] = 'Y';
		}
		
		// Mark the transaction as complete.
		if ($membership->activation == 2) {
			$transaction->status = 'completed';
		}

		$html  = '';
		$html .= '<p>'.JText::_('PLG_SYSTEM_RSMEMBERSHIP2CO_PLEASE_WAIT_REDIRECT').'</p>';
		$html .= '<form method="post" action="'.$url.'" id="twocoForm">';

		foreach ($vars as $key => $value) {
			$html .= '<input type="hidden" name="'.$key.'" value="'.htmlentities($value, ENT_COMPAT, 'utf-8').'" />'."\n";
		}
		
		$html .= '</form>';
		
		$html .= '<script type="text/javascript">';
		$html .= 'function twocoFormSubmit() { document.getElementById(\'twocoForm\').submit() }';
		$html .= 'try { window.addEventListener ? window.addEventListener("load",twocoFormSubmit,false) : window.attachEvent("onload",twocoFormSubmit); }';
		$html .= 'catch (err) { twocoFormSubmit(); }';
		$html .= '</script>';
		
		return $html;
	}
	
	protected function getRecurrence($period, $type) {
		$default = "1 Week";
		
		switch ($type) {
			default:
			case 'h':
				return $default;
			break;
			
			case 'd':
				// No less than a week.
				if ($period < 7) {
					return $default;
				}
				
				// Correct weeks
				if ($period % 7 == 0) {
					$weeks = $period / 7;
				} else {
					// Try to round up.
					$weeks = round($period / 7);
				}
				
				return "$weeks Week";
			break;
			
			case 'm':
				return "$period Month";
			break;
			
			case 'y':
				return "$period Year";
			break;
		}
	}
	
	public function onAfterDispatch()
	{
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		if( $app->getName() != 'site' ) return;

		$oldtwocopayment = $jinput->get('oldtwocopayment', '', 'string');
		if ( !empty($oldtwocopayment) )
			$this->onOldPaymentNotification();

		$newtwocopayment = $jinput->get('twocopayment', '', 'string');
		if ( !empty($newtwocopayment) )
		{
			$cc_processed = $jinput->get('credit_card_processed', '', 'string');
			if ( $cc_processed ) 
				$this->onOldPaymentNotification();
			else 
				$this->onNewPaymentNotification();
		}
	}

	public function getLimitations() {
		return JText::_('PLG_SYSTEM_RSMEMBERSHIP2CO_LIMITATIONS');
	}

	// new modified method
	protected function onNewPaymentNotification()
	{
		if ( !$this->canRun() ) return;

		$log  	= array();
		$deny 	= false;
		$jinput	= JFactory::getApplication()->input;
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		$secret_word = $this->params->get('secret_word');
		$sid 		 = $this->params->get('id');

		$recurring	 		= $jinput->get('recurring', 0, 'int');
		$sale_id     		= $jinput->get('sale_id', '', 'string');
		$vendor_id   		= $jinput->get('vendor_id', '', 'string');
		$invoice_id  		= $jinput->get('invoice_id', '', 'string');
		$md5_hash 			= $jinput->get('md5_hash', '', 'string');
		$payment_amount 	= $jinput->get('item_list_amount_0', '', 'string');
		$timestamp 			= $jinput->get('timestamp', '', 'string');
		$vendor_order_id 	= $jinput->get('vendor_order_id', '', 'string');
		$custom 		 	= $jinput->get('custom', '', 'string');

		if (empty($custom)) 
			$custom = $vendor_order_id;

		$query->select('*')
			  ->from($db->qn('#__rsmembership_transactions'))
			  ->where($db->qn('custom').' = '.$db->q($custom));
		$db->setQuery($query);

		$transaction = $db->loadObject();

		// calculate the hash
		//UPPERCASE(MD5_ENCRYPTED(sale_id + vendor_id + invoice_id + Secret Word))
		$hash 		 = strtoupper(md5($sale_id.$vendor_id.$invoice_id.$secret_word));

		// hash received
		if ( $hash != $md5_hash ) 
		{
			$log[] = JText::sprintf("PLG_SYSTEM_RSMEMBERSHIP2CO_VERIFICATION_ERROR", $md5_hash, $hash);
			$deny  = true;
		}
		else 
		{
			if ($recurring) 
			{
				// recurring transaction
				$log[] = "Identified this payment as recurring.";

				$query->clear();
				$query
					->select($db->qn('id'))
					->select($db->qn('user_id'))
					->select($db->qn('membership_id'))
					->from($db->qn('#__rsmembership_membership_subscribers'))
					->where($db->qn('from_transaction_id').' = '.$db->q($transaction->id));
				$db->setQuery($query);
				$membership = $db->loadObject();
				if (!empty($membership))
				{
					$user = JFactory::getUser($membership->user_id);

					JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rsmembership/tables');
					$transaction = JTable::getInstance('Transaction','RSMembershipTable');
					$transaction->user_id 	 = $user->get('id');
					$transaction->user_email = $user->get('email');
					$transaction->type 		 = 'renew';
					$params = array();
					$params[] = 'id='.$membership->id;
					$params[] = 'membership_id='.$membership->membership_id;
					
					$transaction->params 	= implode(';', $params); // params, membership, extras etc
					$transaction->ip 		= $_SERVER['REMOTE_ADDR'];
					$transaction->date 		= $timestamp;
					$transaction->price 	= $payment_amount;

					$transaction->currency 	= RSMembershipHelper::getConfig('currency');
					$transaction->hash 		= '';
					$transaction->gateway 	= $this->getTranslation($this->params->get('payment_name', '2Checkout'));
					$transaction->status 	= 'completed';

					// store the transaction
					$transaction->store();

					RSMembership::finalize($transaction->id);
					$log[] = "Successfully added the recurring transaction to the database.";

				} else 
					$log[] = "Could not identify the original transaction for this recurring payment.";

			}
			else 
			{ // one time transaction

				// do nothing if transaction completed
				if ( empty($transaction) || $transaction->status == 'completed' ) return;

				// update order number
				$query->clear();
				$query
					->update($db->qn('#__rsmembership_transactions'))
					->set($db->qn('hash').' = '.$db->q($sale_id))
					->where($db->qn('id').' = '.$db->q($transaction->id));
				$db->setQuery($query);
				$db->execute();
			}

			// approve payment
			RSMembership::approve($transaction->id);
			$log[] = JText::sprintf('PLG_SYSTEM_RSMEMBERSHIP2CO_PAYMENT_SUCCESS', $sale_id);
		}

		RSMembership::saveTransactionLog($log, $transaction->id);

		if ( $deny ) 
			RSMembership::deny($transaction->id);
	}

	// old method
	protected function onOldPaymentNotification() 
	{
		if ( !$this->canRun() ) return;

		$log  	= array();
		$deny 	= false;
		$app 	= JFactory::getApplication();
		$jinput	= $app->input;
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		$recurring			= $jinput->get('recurring', 0, 'int');
		$custom 			= $jinput->get('custom', '', 'string');
		$ordernumber 		= $this->params->get('mode') ? $jinput->get('order_number', '', 'string') : 1;
		$total 		 		= $jinput->get('total', '', 'string');
		$key 		 		= $jinput->get('key', '', 'string');
		$processed 	 		= $jinput->get('credit_card_processed', '', 'string');
		$timestamp 	 		= $jinput->get('timestamp', '', 'string');
		$payment_amount	 	= $jinput->get('payment_amount', '', 'string');

		$query
			->select('*')
			->from($db->qn('#__rsmembership_transactions'))
			->where($db->qn('custom').' = '.$db->q($custom));
		$db->setQuery($query);
		$db->execute();
		$transaction = $db->loadObject();

		$secret_word = $this->params->get('secret_word');
		$sid 		 = $this->params->get('id');

		// calculate the hash
		$hash = strtoupper(md5($secret_word.$sid.$ordernumber.$total));

		if ( $hash != $key ) 
		{
			$log[] = JText::sprintf("PLG_SYSTEM_RSMEMBERSHIP2CO_VERIFICATION_ERROR", $key, $hash);
			$deny  = true;
		}
		else
		{
			if ($recurring) 
			{ // recurring payment
				$log[] = "Identified this payment as recurring.";

				$query->clear();
				$query
					->select($db->qn('id'))
					->select($db->qn('user_id'))
					->select($db->qn('membership_id'))
					->from($db->qn('#__rsmembership_membership_subscribers'))
					->where($db->qn('from_transaction_id').' = '.$db->q($transaction->id));
				$db->setQuery($query);
				$membership = $db->loadObject();
				if (!empty($membership))
				{
					$user 		= JFactory::getUser($membership->user_id);
					// get the serialized user_data from previous transaction
					$user_data	= $transaction->user_data;

					// load new transaction object
					JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rsmembership/tables');
					$transaction = JTable::getInstance('Transaction','RSMembershipTable');
					$transaction->user_id 	 = $user->get('id');
					$transaction->user_email = $user->get('email');
					$transaction->user_data  = $user_data;
					$transaction->type 		 = 'renew';
					$params = array();
					$params[] = 'id='.$membership->id;
					$params[] = 'membership_id='.$membership->membership_id;
					
					$transaction->params 	 = implode(';', $params); // params, membership, extras etc
					$transaction->ip 		 = $_SERVER['REMOTE_ADDR'];
					$transaction->date 		 = $timestamp;
					$transaction->price 	 = $payment_amount;

					$transaction->currency 	 = RSMembershipHelper::getConfig('currency');
					$transaction->hash 		 = '';
					$transaction->gateway 	 = $this->getTranslation($this->params->get('payment_name', '2Checkout'));
					$transaction->status 	 = 'completed';

					// store the transaction
					$transaction->store();

					RSMembership::finalize($transaction->id);
					$log[] = "Successfully added the recurring transaction to the database.";

				} else 
					$log[] = "Could not identify the original transaction for this recurring payment.";
				
			} 
			else 
			{
				// transaction exists
				if ( empty($transaction) || $transaction->status == 'completed' ) return;

				// check if the amount is correct
				$price = $this->_convertNumber($transaction->price);
				$currency = strtolower(trim(RSMembershipHelper::getConfig('currency')));
				if ( $price <= $total ) 
				{
					// process payment
					if ( $processed == 'Y' ) 
					{
						// update order number
						$query->clear();
						$query
							->update($db->qn('#__rsmembership_transactions'))
							->set($db->qn('hash').' = '.$db->q($ordernumber))
							->where($db->qn('id').' = '.$db->q($transaction->id));
						$db->setQuery($query);
						$db->execute();

						// approve
						RSMembership::approve($transaction->id);
						$log[] = JText::sprintf('PLG_SYSTEM_RSMEMBERSHIP2CO_PAYMENT_SUCCESS', $ordernumber);
					}
					else
					{
						$log[] = JText::_("PLG_SYSTEM_RSMEMBERSHIP2CO_CC_NOT_PROCESSED");
						$deny  = true;
					}
				}
				else
				{
					$log[] = JText::sprintf("PLG_SYSTEM_RSMEMBERSHIP2CO_EXPECTED_AMOUNT", $price, $currency, $total, $currency);
					$deny  = true;
				}
			}
		}

		RSMembership::saveTransactionLog($log, $transaction->id);
		
		if ($deny)
			RSMembership::deny($transaction->id);

		$app->redirect('index.php?option=com_rsmembership&task=thankyou');
	}

	protected function _convertNumber($number) {
		return number_format($number, 2, '.', '');
	}
	
	protected function _loadLanguage() {
		$this->loadLanguage('plg_system_rsmembership', JPATH_ADMINISTRATOR);
		$this->loadLanguage('plg_system_rsmembership2co', JPATH_ADMINISTRATOR);
	}
}