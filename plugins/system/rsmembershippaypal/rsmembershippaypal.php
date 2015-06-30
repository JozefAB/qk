<?php
/**
* @package RSMembership!
* @copyright (C) 2009-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

if (file_exists(JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/rsmembership.php')) {
	require_once JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/rsmembership.php';
}

class plgSystemRSMembershipPayPal extends JPlugin
{
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
		
		if ($this->canRun() && $this->params) {
			$this->_loadLanguage();
			$name = $this->params->get('payment_name', 'PayPal');
			RSMembership::addPlugin($this->getTranslation($name), 'rsmembershippaypal'); 
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

	protected function canRun()
	{
		return file_exists(JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/rsmembership.php');
	}

	public function onMembershipPayment($plugin, $data, $extra, $membership, $transaction, $html)
	{
		if (!$this->canRun()) return;
		if ($plugin != 'rsmembershippaypal') return false;

		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		$transaction->price += $this->_getTax($transaction->price);
		
		$extra_total = 0;
		foreach ($extra as $row)
			$extra_total += $row->price;

		$query->select('*')->from($db->qn('#__rsmembership_memberships'))->where($db->qn('id').' = '.$db->q((int) $membership->id));
		$db->setQuery($query);
		$db_membership = $db->loadObject();

		$transaction->custom = md5($transaction->params.' '.time());
		$url = $this->params->get('mode') ? 'https://www.paypal.com/cgi-bin/webscr' : 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		
		$html = '';
		$html .= '<p>'.JText::_('PLG_SYSTEM_RSMEMBERSHIPPAYPAL_PLEASE_WAIT_REDIRECT').'</p>';
		$html .= '<form method="post" action="'.$url.'" id="paypalForm">';
		$html .= '<input type="hidden" name="business" value="'.$this->escape($this->params->get('email')).'" />';
		$html .= '<input type="hidden" name="charset" value="utf-8" />';
		
		if ($this->params->get('message_type'))
			$html .= '<input type="hidden" name="item_name" value="'.$this->escape($membership->name).'" />';
		else
			$html .= '<input type="hidden" name="item_name" value="'.$this->escape(JText::sprintf( 'PLG_SYSTEM_RSMEMBERSHIPPAYPAL_MEMBERSHIP_PURCHASE_ON', RSMembershipHelper::showDate($transaction->date) )).'" />';
			
		$html .= '<input type="hidden" name="currency_code" value="'.$this->escape(RSMembershipHelper::getConfig('currency')).'" />';
		
		if ($membership->recurring && $membership->period > 0 && $transaction->type == 'new')
		{
			$html .= '<input type="hidden" name="cmd" value="_xclick-subscriptions" />';
			$html .= '<input type="hidden" name="no_shipping" value="1" />';
			$html .= '<input type="hidden" name="no_note" value="1" />';
			$html .= '<input type="hidden" name="src" value="1" />';
			$html .= '<input type="hidden" name="sra" value="1" />';
			if ($membership->recurring_times > 0) {
				$html .= '<input type="hidden" name="srt" value="'.($membership->recurring_times > 52 ? 52 : $membership->recurring_times).'" />';
			}
			
			// trial period
			if ($membership->use_trial_period)
			{
				// initial price
				$price = $this->_convertNumber($transaction->price);
				$html .= '<input type="hidden" name="a1" value="'.$price.'" />';
				list($p, $t) = $this->_convertPeriod($db_membership->trial_period, $db_membership->trial_period_type);
				$html .= '<input type="hidden" name="p1" value="'.$p.'" />';
				$html .= '<input type="hidden" name="t1" value="'.$t.'" />';
				
				// renewal price (+tax)				
				$price = $this->_convertNumber($membership->use_renewal_price ? $db_membership->renewal_price + $this->_getTax($db_membership->renewal_price) : $db_membership->price + $this->_getTax($db_membership->price));
				// add extras
				$price += $extra_total;
				$html .= '<input type="hidden" name="a3" value="'.$price.'" />';
				list($p, $t) = $this->_convertPeriod($db_membership->period, $db_membership->period_type);
				$html .= '<input type="hidden" name="p3" value="'.$p.'" />';
				$html .= '<input type="hidden" name="t3" value="'.$t.'" />';
			}
			// no trial period
			else
			{
				// different renewal price ?
				if ($membership->use_renewal_price)
				{
					// initial price
					$price = $this->_convertNumber($transaction->price);
					$html .= '<input type="hidden" name="a1" value="'.$price.'" />';
					list($p, $t) = $this->_convertPeriod($db_membership->period, $db_membership->period_type);
					$html .= '<input type="hidden" name="p1" value="'.$p.'" />';
					$html .= '<input type="hidden" name="t1" value="'.$t.'" />';
					
					// renewal price (+tax)
					$price = $this->_convertNumber($membership->renewal_price + $this->_getTax($membership->renewal_price));
					// add extras
					$price += $extra_total;
					$html .= '<input type="hidden" name="a3" value="'.$price.'" />';
					list($p, $t) = $this->_convertPeriod($db_membership->period, $db_membership->period_type);
					$html .= '<input type="hidden" name="p3" value="'.$p.'" />';
					$html .= '<input type="hidden" name="t3" value="'.$t.'" />';
				}
				// regular price
				else
				{
					// renewal price
					$price = $this->_convertNumber($transaction->price);
					$html .= '<input type="hidden" name="a3" value="'.$price.'" />';
					list($p, $t) = $this->_convertPeriod($membership->period, $membership->period_type);
					$html .= '<input type="hidden" name="p3" value="'.$p.'" />';
					$html .= '<input type="hidden" name="t3" value="'.$t.'" />';
				}
			}
		}
		else
		{
			$html .= '<input type="hidden" name="cmd" value="_xclick" />';
			$html .= '<input type="hidden" name="amount" value="'.$this->_convertNumber($transaction->price).'" />';
		}
		if ($db_membership->activation == 2) {
			$transaction->status = 'completed';
		}
		
		$uri 	= JUri::getInstance();
		$base 	= $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
		$return = $base.JRoute::_('index.php?option=com_rsmembership&task=thankyou', false);
		
		$html .= '<input type="hidden" name="notify_url" value="'.JRoute::_(JURI::root().'index.php?option=com_rsmembership&paypalpayment=1').'" />';
		$html .= '<input type="hidden" name="bn" value="RSJoomla_SP" />';
		$html .= '<input type="hidden" name="custom" value="'.$this->escape($transaction->custom).'" />';
		$html .= '<input type="hidden" name="return" value="'.$this->escape($return).'" />';
		$html .= '<input type="hidden" name="rm" value="1" />';
		
		if ($cancel = $this->params->get('cancel_return'))
		{
			$replace = array('{live_site}', '{membership_id}');
			$with = array(JURI::root(), $membership->id);
			$cancel = str_replace($replace, $with, $cancel);
			$html .= '<input type="hidden" name="cancel_return" value="'.$cancel.'" />';
		}
		
		$html .= '</form>';
		
		$html .= '<script type="text/javascript">';
		$html .= 'function paypalFormSubmit() { window.setTimeout(function() { document.getElementById(\'paypalForm\').submit() }, 5500); }';
		$html .= 'try { window.addEventListener ? window.addEventListener("load",paypalFormSubmit,false) : window.attachEvent("onload",paypalFormSubmit); }';
		$html .= 'catch (err) { paypalFormSubmit(); }';
		$html .= '</script>';
		
		return $html;
	}
	
	protected function _getTax($price)
	{
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
	
	public function onAfterRender()
	{
		$app = JFactory::getApplication();		
		if($app->getName() != 'site') return;

		$paypalpayment = $app->input->get('paypalpayment', '', 'string');

		if (!empty($paypalpayment))
			$this->onPaymentNotification();
	}
	
	public function getLimitations()
	{
		return JText::_('PLG_SYSTEM_RSMEMBERSHIPPAYPAL_PAYPAL_LIMITATIONS');
	}
	
	protected function _buildPostData() 
	{
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';

		//reading raw POST data from input stream. reading pot data from $_POST may cause serialization issues since POST data may contain arrays
		$raw_post_data = file_get_contents('php://input');
		if ($raw_post_data) 
		{
			$raw_post_array = explode('&', $raw_post_data);
			$myPost = array();
			foreach ($raw_post_array as $keyval) {
				$keyval = explode ('=', $keyval);
				if (count($keyval) == 2) {
					$myPost[$keyval[0]] = urldecode($keyval[1]);
				}
			}

			$get_magic_quotes_exists 	= function_exists('get_magic_quotes_gpc');
			$get_magic_quotes_gpc 		= $get_magic_quotes_exists && get_magic_quotes_gpc();
			
			foreach ($myPost as $key => $value) {
				if ($key == 'limit' || $key == 'limitstart' || $key == 'option') continue;
				
				if ($get_magic_quotes_exists && $get_magic_quotes_gpc) {
					$value = urlencode(stripslashes($value)); 
				} else {
					$value = urlencode($value);
				}
				$req .= "&$key=$value";
			}
		} else {
			// read the post from PayPal system
			$post = $_POST;
			foreach ($post as $key => $value)
			{
				if ($key == 'limit' || $key == 'limitstart' || $key == 'option') continue;
				
				$value = urlencode($value);
				$req .= "&$key=$value";
			}
		}

		return $req;
	}
	
	protected function addLog($entry) {
		// Add timestamp to the entry
		$entry = JFactory::getDate()->format('[Y-m-d H:i:s]').' - '.$entry."\n";
		
		// Compute the log file's path.
		static $path;
		if (!$path) {
			$config = new JConfig();
			$path 	= $config->log_path.'/rsmembership-paypal.php';
			
			if (!file_exists($path)) {
				file_put_contents($path, "<?php die('Forbidden.'); ?>\n\n");
			}
		}
		
		if ($this->params->get('enable_logging')) {
			file_put_contents($path, $entry, FILE_APPEND);
		}
		
		echo $entry;
	}

	protected function finish() {
		JFactory::getApplication()->close();
	}
	
	protected function onPaymentNotification()
	{
		if (!$this->canRun()) return;
		
		ob_end_clean();
		
		$name = $this->getTranslation($this->params->get('payment_name', 'PayPal'));

		require_once JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/adapters/input.php';
		
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$jinput = RSInput::create();
		$log = array();

		$req = $this->_buildPostData();
		
		$this->addLog("IPN received: $req");
		
		// post back to PayPal system to validate
		$url = $this->params->get('mode') ? 'https://www.paypal.com/cgi-bin/webscr' : 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		$only_completed = (int) $this->params->get('only_completed', 0);

		if (!extension_loaded('curl') || !function_exists('curl_exec') || !is_callable('curl_exec')) {
			$this->addLog('[err] cURL is not installed or executable, cannot connect back to PayPal for validation!');
			$this->finish();
		}
		
		$this->addLog("Connecting to $url to verify if PayPal response is valid.");
		
		require_once JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/version.php';
		$version = (string) new RSMembershipVersion;
		$website = JUri::root();
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.paypal.com'));
		curl_setopt($ch, CURLOPT_USERAGENT, "RSMembership!/$version ($website)");
		$res = curl_exec($ch);
		$errstr = curl_error($ch);
		curl_close($ch);
		
		if ($errstr) {
			$this->addLog('[err] cURL reported error: '.$errstr);
			$this->finish();
		}
		
		// assign posted variables to local variables
		$item_name 			= $jinput->get('item_name', '', 'none');
		$item_number 		= $jinput->get('item_number', '', 'none');
		$payment_status 	= $jinput->get('payment_status', '', 'none');
		$payment_amount 	= $jinput->get('mc_gross', '', 'none');
		$payment_currency 	= $jinput->get('mc_currency', '', 'none');
		$txn_id 			= $jinput->get('txn_id', '', 'none');
		$txn_type 			= $jinput->get('txn_type', '', 'none');
		$receiver_email 	= $jinput->get('receiver_email', '', 'none');
		$payer_email 		= $jinput->get('payer_email', '', 'none');
		$custom 			= $jinput->get('custom', 0, 'none');

		// try to get the transaction id based on the custom hash
		$transaction_id = $this->getTransactionId($custom);

		// Do not deny the transaction for now.
		$deny = false;
		
		$this->addLog("Transaction ID is '$transaction_id', based on '$custom'.");

		if ($res)
		{
			$this->addLog("Successfully connected to $url. Response is $res");
			
			if (strcmp ($res, "VERIFIED") == 0)
			{
				$this->addLog("Response is VERIFIED.");
				$log[] = "PayPal reported a valid transaction.";
				$log[] = "Payment status is ".(!empty($payment_status) ? $payment_status : 'empty').".";
				// check the payment_status is Completed
				if (!$only_completed || ($only_completed && $payment_status == 'Completed')) {
					// sign up - do nothing, we use our "custom" parameter to identify the transaction
					if ($txn_type == 'subscr_signup')
					{
						$log[] = "Subscription signup has been received.";
						
						// If this is a free trial, we'll need to make sure that the transaction is accepted since "subscr_payment" will be received after the trial ends
						$mc_amount1 = $jinput->get('mc_amount1', '', 'none');
						$subscr_id	= $jinput->get('subscr_id', '', 'none');
						if ((float) $mc_amount1 == (float) $transaction->price && $mc_amount1 == '0.00') {
							// Emulate the variables needed below to approve the transaction
							// No txn_id here, let's just use subscr_id so we can use something for PayPal identification.
							$txn_id 		= 'Subscription ID: '.$subscr_id;
							$payment_amount = $mc_amount1;
							
							// Load the transaction so that it can be processed below
							$transaction = $this->getTransaction($transaction_id, 'id');
						}
					}
					elseif ($txn_type == 'subscr_payment' || $txn_type == 'recurring_payment')
					{
						$log[] = "Adding new payment...";
						// check that txn_id has not been previously processed
						// check custom_hash from db -> if custom_hash == txn_id

						$query->clear();
						$query
							->select($db->qn('id'))
							->from($db->qn('#__rsmembership_transactions'))
							->where($db->qn('hash').' = '.$db->q($txn_id))
							->where($db->qn('gateway').' = '.$db->q($name));
						$db->setQuery($query);

						if (!$db->loadResult())
						{
							$transaction = $this->getTransaction($custom);

							// check if transaction exists
							if (!empty($transaction))
							{
								// this transaction has already been processed
								// we need to create a new "renewal" transaction
								if ($transaction->status == 'completed')
								{
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
										$transaction->user_id = $user->get('id');
										$transaction->user_email = $user->get('email');
										$transaction->type = 'renew';
										$params = array();
										$params[] = 'id='.$membership->id;
										$params[] = 'membership_id='.$membership->membership_id;
										
										$transaction->params = implode(';', $params); // params, membership, extras etc
										$date = JFactory::getDate();
										$transaction->date = $date->toSql();
										$transaction->ip = $_SERVER['REMOTE_ADDR'];
										$transaction->price = $payment_amount;
										$transaction->currency = RSMembershipHelper::getConfig('currency');
										$transaction->hash = '';
										$transaction->gateway = $name;
										$transaction->status = 'pending';

										// store the transaction
										$transaction->store();

										RSMembership::finalize($transaction->id);

										$log[] = "Successfully added the recurring transaction to the database.";
									}
									else
										$log[] = "Could not identify the original transaction for this recurring payment.";
								}
							}
							else
								$log[] = "Could not identify transaction with custom hash $custom. Stopping.";
						}
						else
							$log[] = "The transaction $txn_id has already been processed. Stopping.";
					}
					else
					{
						// check that txn_id has not been previously processed
						// check custom_hash from db -> if custom_hash == txn_id
						$query->clear();
						$query
							->select($db->qn('id'))
							->from($db->qn('#__rsmembership_transactions'))
							->where($db->qn('hash').' = '.$db->q($txn_id))
							->where($db->qn('gateway').' = '.$db->q($name));
						$db->setQuery($query);

						if (!$db->loadResult()) 
						{
							$query->clear();
							$query
								->select('*')
								->from($db->qn('#__rsmembership_transactions'))
								->where($db->qn('custom').' = '.$db->q($custom))
								->where($db->qn('status').' != '.$db->q('completed'));
							$db->setQuery($query);
							$transaction = $db->loadObject();
							
							// check if transaction exists
							if (empty($transaction))
								$log[] = "Could not identify transaction with custom hash $custom. Stopping.";
						}
						else
							$log[] = "The transaction $txn_id has already been processed. Stopping.";
					}
					
					if (!empty($transaction))
					{
						$plugin_email   = $this->normalize($this->params->get('email'));
						$primary_email  = $this->normalize($this->params->get('primary_email'));
						$receiver_email = $this->normalize($receiver_email);
						if (!$primary_email) {
							$primary_email = $plugin_email;
						}

						// check that receiver_email is your Primary PayPal email
						if ($receiver_email == $plugin_email || $receiver_email == $primary_email)
						{
							// check that payment_amount/payment_currency are correct
							// check $payment_amount == $price from $subscription_id && $payment_currency == $price from $subscription_id
							$price 				= $this->_convertNumber($transaction->price);
							$currency 			= $this->normalize(RSMembershipHelper::getConfig('currency'));
							$payment_currency 	= $this->normalize($payment_currency);
							
							if ((float) $payment_amount >= (float) $price)
							{
								if ($currency == $payment_currency)
								{
									// set the hash
									$this->setTransactionHash($transaction->id, $txn_id);

									// process payment unless manual activation selected
									$membership_id = $this->getMembershipId($transaction->params, $transaction->type);
									if ($membership_id) {
										$query->clear()
											->select('activation')
											->from($db->qn('#__rsmembership_memberships'))
											->where($db->qn('id').' = '.$db->q((int) $membership_id));
										$db->setQuery($query);
										$activation = $db->loadResult();
										
										if ($activation != MEMBERSHIP_ACTIVATION_MANUAL) {
											RSMembership::approve($transaction->id);
										}
										
										$activationText = 'missing';
										if ($activation == MEMBERSHIP_ACTIVATION_MANUAL) {
											$activationText = 'manual';
										} elseif ($activation == MEMBERSHIP_ACTIVATION_AUTO) {
											$activationText = 'auto';
										} elseif ($activation == MEMBERSHIP_ACTIVATION_INSTANT) {
											$activationText = 'instant';
										}
										
										$log[] = "Activation is $activationText.";
										$log[] = "Successfully added the payment to the database.";
									}
									else {
										$log[] = "The membership could not be found in the database.";
									}
									
								}
								else
								{
									$log[] = "Expected a currency of $currency. PayPal reports this payment is made in $payment_currency. Stopping.";
									$deny  = true;
								}
							}
							else
							{
								$log[] = "Expected an amount of $price $currency. PayPal reports this payment is $payment_amount $payment_currency. Stopping.";
								$deny  = true;
							}
						}
						else
						{
							$log[] = "Expected payment to be made to $plugin_email".($primary_email ? " or $primary_email" : "").". PayPal reports this payment is made for $receiver_email. Stopping.";
							$deny  = true;
						}
					}
				}
				else
				{
					$log[] = "Payment status is $payment_status. Stopping.";
				}
			}
			elseif (strcmp($res, "INVALID") == 0)
			{
				$this->addLog("[err] Response is INVALID.");
				
				$log[] = "Could not verify transaction authencity. PayPal said it's invalid.";
				$log[] = "String sent to PayPal is $req";
				$deny  = true;
				// log for manual investigation
			}
			else
			{
				$this->addLog("[err] PayPal response returned invalid data. Data is presented below:");
				$this->addLog($res);
				$this->addLog("End of data.");
				
				$log[] = 'PayPal response is not valid! Should be either VERIFIED or INVALID, received "'.strip_tags($res).'"';
			}
		}
		else
			$log[] = "Could not open $url in order to verify this transaction. Error reported is: $errstr";
		
		if ($transaction_id)
		{
			$log[] = "String sent by PayPal is $req";
			RSMembership::saveTransactionLog($log, $transaction_id);
			if ($deny) 
				RSMembership::deny($transaction_id);
		}
		
		$this->finish();
	}
	
	protected function getMembershipId($params, $type) {
		$params = RSMembershipHelper::parseParams($params);
		
		switch ($type)
		{
			case 'new':
			case 'renew':
				$membership_id = $params['membership_id'];
			break;
			case 'addextra':
				JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rsmembership/tables');
				// Verify if the subscription still exists
				$current = JTable::getInstance('Membership_Subscriber', 'RSMembershipTable');
				if (!$current->load($params['id'])) {
					return false;
				}	
				// Check if this membership still exists
				$membership = JTable::getInstance('Membership', 'RSMembershipTable');
				if (!$membership->load($current->membership_id)) {
					return false;
				}
				
				$membership_id = $current->membership_id;
			
			break;
			case 'upgrade':
				JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rsmembership/tables');
				$membership = JTable::getInstance('Membership', 'RSMembershipTable');
				if (!$membership->load($params['to_id'])) {
					return false;
				}
				$membership_id = $membership->membership_id;
			break;
		}
		
		return $membership_id;
	}
	
	protected function _convertNumber($number)
	{
		return number_format($number, 2, '.', '');
	}
	
	protected function _convertPeriod($period, $type)
	{
		$return = array();
		
		$return[0] = $period;
		$return[1] = strtoupper($type);
		
		return $return;
	}
	protected function _loadLanguage() {
		$this->loadLanguage('plg_system_rsmembership', JPATH_ADMINISTRATOR);
		$this->loadLanguage('plg_system_rsmembershippaypal', JPATH_ADMINISTRATOR);
	}
	
	protected function escape($string) {
		return htmlentities($string, ENT_COMPAT, 'utf-8');
	}
	
	// Get full transaction details.
	protected function getTransaction($value, $column = 'custom') {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select('*')
			  ->from($db->qn('#__rsmembership_transactions'))
			  ->where($db->qn($column).' = '.$db->q($value));
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	// Get transaction ID from custom column.
	protected function getTransactionId($value, $column = 'custom') {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select($db->qn('id'))
			  ->from($db->qn('#__rsmembership_transactions'))
			  ->where($db->qn($column).' = '.$db->q($value));
		$db->setQuery($query);
		return (int) $db->loadResult();
	}
	
	// Sets the order number (from the Payment Gateway) to the specified transaction
	protected function setTransactionHash($id, $hash) {
		$db 	= JFactory::getDbo();
		$query 	= $db->getQuery(true);
		
		$query->update($db->qn('#__rsmembership_transactions'))
			  ->set($db->qn('hash').' = '.$db->q($hash))
			  ->where($db->qn('id').' = '.$db->q($id));
		$db->setQuery($query);
		$db->execute();
	}
	
	protected function normalize($string) {
		return strtolower(trim($string));
	}
}