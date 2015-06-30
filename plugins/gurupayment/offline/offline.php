<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgGurupaymentOffline extends JPlugin{
	var $_db = null;

	function __construct(&$subject, $config){
		$this->_db = JFactory :: getDBO();
		parent :: __construct($subject, $config);
	}

	function onReceivePayment($post){
		if($post['processor'] != 'offline'){
			return 0;
		}
		
		$params = new JRegistry($post['params']);
		$default = $this->params;
        
		$out['sid'] = $post['sid'];
		$out['order_id'] = $post['order_id'];
		$out['processor'] = $post['processor'];
		if(isset($post['txn_id'])){
			$out['processor_id'] = JRequest::getVar('tx', $post['txn_id']);
		}
		else{
			$out['processor_id'] = "";
		}
		if(isset($post['custom'])){
			$out['customer_id'] = JRequest::getInt('cm', $post['custom']);
		}
		else{
			$out['customer_id'] = "";
		}
		if(isset($post['mc_gross'])){
			$out['price'] = JRequest::getVar('amount', JRequest::getVar('mc_amount3', JRequest::getVar('mc_amount1', $post['mc_gross'])));
		}
		else{
			$out['price'] = "";
		}
		$out['pay'] = $post['pay'];
		if(isset($post['email'])){
			$out['email'] = $post['email'];
		}
		else{
			$out['email'] = "";
		}
		$out["Itemid"] = $post["Itemid"];

		if($params->get('paypaypal_errorlog', $default->get('paypaypal_errorlog'))) error_log("\n\n[Start get post]: ".print_r($post,true),3,JPATH_ROOT.DS.'plugins'.DS.'gurupayment'.DS.'papaypal_log.txt');

		if($out['pay'] == 'ipn'){
			$s_info = jcsPPGetInfo($params, $post, $default);
			if ($params->get('paypaypal_errorlog', $default->get('paypaypal_errorlog'))){
				error_log("\n[Return Paypal]: ".print_r($s_info,true), 3, JPATH_ROOT.DS.'plugins'.DS.'gurupayment'.DS.'papaypal_log.txt');
			}
			$database = JFactory::getDBO();

			if(isset($s_info['txn_type'])){
				switch($s_info['txn_type']){
					case "subscr_signup":
						break;
					case "send_money":
					case "web_accept":
					case "subscr_payment":
						switch ($s_info['payment_status']){
							case 'Processed':
							case 'Completed':
								break;
							case 'Refunded':
								return;
								break;
							case 'In-Progress':
							case 'Pending':
								$out['pay'] = 'fail';
								break;
							default:
								return;
						}
						break;

					case 'recurring_payment':
						break;
								
					case "subscr_failed":
							break;
					case "subscr_eot":
					case "subscr_cancel":
						return;
						break;
					case "new_case":
						return;
						break;
					case "adjustment":
						default: 
						break;
				}
			}
		}
		return $out;
	}

	function onSendPayment($post){
		$db = JFactory::getDBO();
	
		if($post['processor'] != 'offline'){
			return false;
		}	

		if($post['params']){
			$params = $post['params'];
		}
		else{
			$params = $this->params;
		}
		
		$params = json_decode($params);
		
		$lang = JFactory::getLanguage();
        $lang->load('plg_gurupayment_offline', JPATH_ADMINISTRATOR);
		
		$cancel_return = JURI::root().'index.php?option=com_guru&controller=guruBuy&processor='.$post['processor'].'&task='.$post['task'].'&sid='.$post['sid'].'&order_id='.$post['order_id'].'&pay=fail';
		
		$ok_return = JURI::root().'index.php?option=com_guru&controller=guruBuy&processor='.$post['processor'].'&task='.$post['task'].'&sid='.$post['sid'].'&order_id='.$post['order_id'].'&pay=wait';
		
		$form  = '<form name="offlineform" action="index.php" method="post">';
		
		if(trim($params->instructions) != ""){
			$params->instructions = nl2br($params->instructions);
			$form .= '<div class="alert alert-info">'.$params->instructions.'</div>';
		}
		
		$form .= '<input type="button" class="btn btn-primary" onclick="window.location=\''.$cancel_return.'\';" value="'.JText::_("PLG_GURUPAYMENT_OFFLINE_CANCEL").'" />';
		$form .= '&nbsp;&nbsp;';
		$form .= '<input type="button" class="btn btn-warning" onclick="window.location=\''.$ok_return.'\';" value="'.JText::_("PLG_GURUPAYMENT_OFFLINE_OK").'" />';
		$form .= '</form>';
		
		return $form;
	}
}
?>