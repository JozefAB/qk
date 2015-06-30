<?php
/*------------------------------------------------------------------------
# com_guru
# ------------------------------------------------------------------------
# author    iJoomla
# copyright Copyright (C) 2013 ijoomla.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.ijoomla.com
# Technical Support:  Forum - http://www.ijoomla.com.com/forum/index/
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport ('joomla.application.component.controller');

class guruControllerguruBuy extends guruController {
	
	function __construct(){
		parent::__construct();
		$this->registerTask("","view");
		$this->registerTask("checkout", "checkout");
		$this->registerTask("payment", "notify");
		$this->registerTask("return", "return_action");
		$this->registerTask("cancelreturn", "cancelreturn");
		$this->registerTask("updatecart", "updatecart");
		$this->registerTask("deletefromsession", "deleteFromSession");
		$this->registerTask("failPayment", "failPayment");
		$this->_model = $this->getModel("guruBuy");
	}
	
	function view(){
		JRequest::setVar('view', 'guruBuy');	
		parent::display();
	}
	
	function getPromoDiscountCoursee($total, $promo_id){
		$old_total = $total;
		$value_to_display = "";
		$db = JFactory::getDBO();
		$sql = "select * from #__guru_promos where id='".intval($promo_id)."'";
		$db->setQuery($sql);
		$db->query();
		$promo = $db->loadObjectList();			
		$promo_details = $promo["0"];
		
		if($promo_details->typediscount == '0') {//use absolute values					
			$difference = $total - (float)$promo_details->discount;
			if($difference < 0){
				$total = 0;
			}
			else{
				$total = $difference;
			}					
		}
		else{//use percentage
			$total = ($promo_details->discount / 100)*$total;
			$difference = $old_total - $total;	
			if($difference < 0){
				$total =  "0";
			}
			else{
				$total = $difference;
			}
		}
		
		return $total;
	}
	
	function updatecart(){
		$all_courses = array();
		$msg2 = JText::_("GURU_PROMO_FOR_STUD");
		$action = JRequest::getVar("action", "");
		$counter = 0;
		$db = JFactory::getDBO();
		if(trim($action) == ""){
			$all_courses = $_SESSION["courses_from_cart"];
		}
		else{
			$all_courses = $_SESSION["renew_courses_from_cart"];
		}
		$prices = JRequest::getVar("plan_id", array());
		$discount_code = JRequest::getVar("promocode", "");
		$total = 0.0;
		
		$sql = "select courses_ids from #__guru_promos where code='".$discount_code."'";
		$db->setQuery($sql);
		$db->query();
		$courses_ids_list = $db->loadColumn();
		$courses_ids_list2 = implode(",",$courses_ids_list);
		$courses_ids_list3 = explode("||",$courses_ids_list2);
		
		if(isset($all_courses) && is_array($all_courses) && count($all_courses) > 0){
			foreach($all_courses as $key=>$value){	
				$first_total += $prices[$key];
				if(in_array($value["course_id"],$courses_ids_list3 )){
					$counter +=1;
					$model = $this->getModel("guruBuy");
					$promo_details = $model->getPromo();
					
					if($promo_details->typediscount == '0') {//use absolute values		
						$difference = $prices[$key] - (float)$promo_details->discount;
						$total = $difference;
					}
					else{//use percentage
						$total = ($promo_details->discount / 100)*$prices[$key];
						$difference = $prices[$key] - $total;
						$discount = $prices[$key] - $difference;
						$total = (float)$prices[$key] - (float)$discount;
					}
					$all_courses[$key]["value"] = $prices[$key];
				}
				else{
					$all_courses[$key]["value"] = $prices[$key];
					$total += $prices[$key];
				}
			}			
			if(trim($action) == ""){
				$_SESSION["courses_from_cart"] = $all_courses;
			}
			else{
				$_SESSION["renew_courses_from_cart"] = $all_courses;
			}
		}
		$old_total = $first_total;
	
		$sql = "select published from #__guru_promos where code='".$discount_code."'";
		$db->setQuery($sql);
		$db->query();
		$pubpromo = $db->loadColumn();
		$pubpromo = $pubpromo["0"];
		
		$sql = "select id from #__guru_promos where code='".$discount_code."'";
		$db->setQuery($sql);
		$db->query();
		$promocodeid = $db->loadColumn();
		$promocodeid = $promocodeid["0"];
			
		$sql = "select codeused from #__guru_promos where id=".intval($promocodeid);
		$db->setQuery($sql);
		$db->query();
		$codeused = $db->loadColumn();
		$codeused = $codeused["0"];
		
		$sql = "select codelimit from #__guru_promos where id=".intval($promocodeid);
		$db->setQuery($sql);
		$db->query();
		$codelimit = $db->loadColumn();
		$codelimit = $codelimit["0"];
		
		$jnow = JFactory::getDate();
		$date = $jnow->toSQL();
		$date = strtotime($date);
		
		$sql = "select codeend from #__guru_promos where id=".intval($promocodeid);
		$db->setQuery($sql);
		$db->query();
		$codeend = $db->loadColumn();
		$codeend = $codeend["0"];
		$sql = "select forexisting from #__guru_promos where id=".intval($promocodeid);
		$db->setQuery($sql);
		$db->query();
		$forexisting = $db->loadColumn();
		$forexisting = $forexisting["0"];
		
		$user = JFactory::getUser();
		$user_id = $user->id;
		
		$sql = "select count(id) from #__guru_customer where id=".intval($user_id);
		$db->setQuery($sql);
		$db->query();
		$isstudent = $db->loadColumn();
		$isstudent = $isstudent["0"];
		
		if($codeend =='0000-00-00 00:00:00'){
			$never = 1;
		}
		else{
			$never = 0;
			$codeend = strtotime($codeend);
		}
		
		if($pubpromo == 0){
			$discount_code ="";
			$_SESSION["msg"] = $msg;	
		}
		
		if($codeused >= $codelimit && $never == 0 && $codelimit >0){
			$discount_code ="";
			$_SESSION["msg"] = $msg;	
		}
		
		if($date > $codeend && $never == 0){
			$discount_code ="";
			$_SESSION["msg"] = $msg;	
		}
		
		if($forexisting == 1 && $isstudent == 0){
			$discount_code ="";
			$_SESSION["msg"] = $msg2;	
		}
		if(trim($discount_code) != ""){
			$_SESSION["promo_code"] = $discount_code;
			$model = $this->getModel("guruBuy");
			$promo_details = $model->getPromo();
			if(!isset($promo_details)){// promo expired
				$_SESSION["promo_code"] = "";
				$_SESSION["discount_value"] = "";
			}
			else{
				$set_discount = false;			
				if(trim($promo_details->codelimit) != 0){
					if(trim($promo_details->codelimit) > trim($promo_details->codeused)){
						$set_discount = true;
					}
				}
				else{
					$set_discount = true;
				}
				
				if($set_discount === TRUE){
					$configs = $model->getConfigs();
					$currency = $configs["0"]["currency"];
					$currencypos = $configs["0"]["currencypos"];					
					$character = JText::_("GURU_CURRENCY_".$currency);
					
					if($promo_details->typediscount == '0') {//use absolute values		
						$difference = $total - (float)$promo_details->discount;
						if($difference < 0){
							$total = 0;
							$counter = $counter -1;
						}
						$model = $this->getModel('gurubuy');	
						if($currencypos == 0){
							$_SESSION["discount_value"] = $character." ".($promo_details->discount*$counter);
						}
						else{
							$_SESSION["discount_value"] = ($promo_details->discount*$counter)." ".$character;
						}

					}
					else{//use percentage
						$difference = $old_total - $total;
						if($difference < 0){
							if($currencypos == 0){
								$_SESSION["discount_value"] =  $character." "."0";
							}
							else{
								$_SESSION["discount_value"] =  "0"." ".$character;
							}								
						}
						else{
							$discount = $difference;
							if($currencypos == 0){
								$_SESSION["discount_value"] =  $character." ".(float)$discount;
							}
							else{
								$_SESSION["discount_value"] =  (float)$discount." ".$character;
							}	
							
							$total = (float)$old_total - (float)$discount;
						}
						
					}
					if($codelimit > 0 && $user_id !=0){
						$db =JFactory::getDBO();	
						$sql = "select codeused from #__guru_promos where id=".intval($promocodeid);
						$db->setQuery($sql);
						$db->query();
						$result = $db->loadColumn();
						if(isset($result["0"])){
							$new_val = 	intval($result["0"]) + 1;
						}
						$sql = "update #__guru_promos set `codeused`=".$new_val." where id=".intval($promocodeid);
						$db->setQuery($sql);
						$db->query();
					}
					unset($_SESSION["msg"]);		
				}
				else{
					$_SESSION["promo_code"] = "";
					$_SESSION["discount_value"] = "";
					$_SESSION["msg"] = $msg;
				}
			}// if promo is not expired
			
		}
		else{		
			$_SESSION["promo_code"] = "";
			$_SESSION["discount_value"] = "";
		}
		
		$point_poz = strpos($total, ".");		
		$total = substr($total, 0, $point_poz+3);
		$_SESSION["max_total"] = $total;	
		if(trim($action) != ""){
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruBuy&action=".trim($action), false));
		}
		else{
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruBuy",false),  $_SESSION["msg"] , 'warning');
		}
	}
	
	function checkout(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$db = JFactory::getDBO();
		$from = JRequest::getVar("from", "");
		if(trim($from) == ""){
			$promocode = JRequest::getVar("promocode", "");
			$procesor = JRequest::getVar("processor", "");
			$_SESSION["promocode"] = trim($promocode);
			$_SESSION["processor"] = trim($procesor);
			$this->updatecart();
		}
		$sql = "select courses_ids from #__guru_promos where code='".addslashes(trim($_SESSION["promocode"]))."'";
		$db->setQuery($sql);
		$db->query();
		$courses_for_promo = $db->loadColumn();
		
		$sql = "select id from #__guru_promos where code='".addslashes(trim($_SESSION["promocode"]))."'";
		$db->setQuery($sql);
		$db->query();
		$promo_id_f = $db->loadColumn();
		$promo_id_f = $promo_id_f["0"];
		
		$list_courses_promo = explode("||", $courses_for_promo["0"]);
		
		$order_id = "";
		$_Itemid = $Itemid;
		$cart = $this->getModel('gurubuy');		
		$plugins_enabled = $cart->getPluginList();		
		$user = JFactory::getUser();
		$user_id = $user->id;
		// Check Login
		if($user_id == "0"){
			// sincronize courses from session with courses from request;
			$courses_request = JRequest::getVar("plan_id", array());
			$courses_session = $_SESSION["courses_from_cart"];
			if(isset($courses_request) && count($courses_request) > 0){
				foreach($courses_request as $key=>$value){
					$courses_session[$key]["value"] = $value;
				}
				$_SESSION["courses_from_cart"] = $courses_session;
			}
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=checkout", false));
			return true;
		}
		
		$customer = $cart->getCustomer();
		
		// Check Payment Plugin installed
		if(empty($plugins_enabled)) {
			$msg = JText::_('Payment plugins not installed');
			$this->setRedirect(JRoute::_("index.php?option=com_guru&controller=guruBuy&Itemid=".$_Itemid, false), $msg);
			return;
		}

		$res = $cart->checkProfileCompletion($customer);
		$details_user =$cart->getJoomlaUserF($user_id);
		$username = $details_user["0"]["username"];
		$email = $details_user["0"]["email"];
		$name = $details_user["0"]["name"];
		
		$temp = explode(" ", $name);
		if(isset($temp) && count($temp) > 1){		
			$last_name = $temp[count($temp) - 1];	
			unset($temp[count($temp) - 1]);
			$first_name = implode(" ", $temp); 
		}
		else{
			if(count($temp) == 1){
				$first_name = $name;
				$last_name  = $name;
			}
		}
		
		if($res < 1){
			$db = JFactory::getDBO();
			$sql = "insert into #__guru_customer(`id`, `firstname`, `lastname`) values (".intval($user_id).", '".addslashes(trim($first_name))."', '".addslashes(trim($last_name))."')";
			$db->setQuery($sql);
			$db->query();
			
		}
		$total = 0;

		$configs = $cart->getConfigs();
		$items = $cart->getCartItems($customer, $configs);

		$dispatcher =  JDispatcher::getInstance();
		$params['user_id'] = $customer["0"]["id"];
		
		if ( isset($this->_customer) && isset($this->_customer->_customer) ) {
			$params['customer'] = ($this->_customer->_customer);
			// get email from user and set to customer
			$user = JFactory::getUser();
			$params['customer']->email = $user->get('email');
		}

		$params['products'] = $items; // array of products
		$params['config'] = $configs;
		$params['processor'] = $_SESSION["processor"];
		$gataways = JPluginHelper::getPlugin('gurupayment', $params['processor']);


		if(is_array($gataways)){
			foreach($gataways as $gw){
				if($gw->name == $prosessor){
					$params['params'] = $gw->params;
					break;
				}
			}
		}
		else{
			$params['params'] = $gataways->params;
		}

		$character = JText::_("GURU_CURRENCY_".$configs["0"]["currency"]);
		$discount_value = $_SESSION["discount_value"];
		$discount_value = str_replace($character, "", $discount_value);
		$discount_value = trim($discount_value);
		
		//check if the price is 0(zero), to not redirect to paypal
		$total_prices = 0;
		foreach($items as $key=>$value){
			if(in_array($value["course_id"], $list_courses_promo)){
				$value["value"] = self:: getPromoDiscountCoursee($value["value"], $promo_id_f);
			}
			$total_prices += $value["value"];
		}
		$order_id = $cart->saveNewOrder($total_prices);
		$model = $this->getModel("guruBuy");
		$promo_details = $model->getPromo();

		if($total_prices == "0" || $total_prices == "0.00" || (isset($discount_value) && trim($discount_value) != "" && ($total_prices - $discount_value) == 0 && $promo_details->typediscount == '0') || ($total_prices - $discount_value) < 0 && $promo_details->typediscount == '0'){
			$model = $this->getModel("guruBuy");
			$submit_array = array("customer_id"=>$customer["0"]["id"], "order_id"=>$order_id, "price"=>"0");
			
			unset($_SESSION["courses_from_cart"]);
			unset($_SESSION["renew_courses_from_cart"]);
			unset($_SESSION["promo_code"]);
			unset($_SESSION["max_total"]);
			unset($_SESSION["order_id"]);
			unset($_SESSION["promocode"]);
			unset($_SESSION["processor"]);
			unset($_SESSION["discount_value"]);

			$model->proccessSuccess("guruBuy", $submit_array, false);
		}
		
		$params['order_id'] = $order_id;
		$params['sid'] = $order_id;
		$params['option'] = 'com_guru';
		$params['controller'] = 'guruBuy';
		$params['task'] = 'payment';
		$params['order_amount'] = $discount_value;
		$params['order_currency'] = $configs["0"]['currency'];
		$params['Itemid'] = JRequest::getInt('Itemid');
		$params["customer_id"] = $customer["0"]["id"];
		JPluginHelper::importPlugin('gurupayment');
		$result = $dispatcher->trigger('onSendPayment', array(&$params));
		
		$form_created = $result["0"];
		if(trim($form_created) == "" && isset($result["1"])){
			$form_created = $result["1"];
		}
		
		$db = JFactory::getDBO();
		$sql = "update #__guru_order set form='".trim(addslashes($form_created))."' where id=".intval($order_id);
		$db->setQuery($sql);
		$db->query();
		
		//for https ---------------------------------------
		$processor = $_SESSION["processor"];
		if($processor == "payauthorize"){
			$page_url = $this->getPageURL();
			$reqhttps = "1";
			if(is_file(JPATH_SITE.DS."plugins".DS."gurupayment".DS."payauthorize".DS."install")){
				$content = JFile::read(JPATH_SITE.DS."plugins".DS."gurupayment".DS."payauthorize".DS."install");
				$reqhttps = $this->getReqhttps($content);
				if($reqhttps == "1"){//https
					if(strpos($page_url, "https") === FALSE){
						$site = JURI::root();
						$site = str_replace("http", "https", $site);
						$page_url = $site."index.php?option=com_guru&view=guruBuy&action2=submit&order_id=".$order_id."&Itemid=".intval($Itemid);
						$this->setRedirect(JRoute::_($page_url, false));
						return true;
					}
				}
			}
		}
		
		$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruBuy&action2=submit&order_id=".$order_id."&Itemid=".intval($Itemid), false));
		
		//for https ---------------------------------------
	}
	
	function getPageURL(){
		$pageURL = 'http';
		if($_SERVER["HTTPS"] == "on"){
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if($_SERVER["SERVER_PORT"] != "80"){
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}
		else{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	function getReqhttps($content){
		$reqhttps = "1";
		if(trim($content) != ""){
			$by_n = explode("\n", $content);
			if(isset($by_n)){
				foreach($by_n as $key=>$value){
					$by_equal = explode("=", $value);
					if(is_array($by_equal) && count($by_equal) > 0){
						if($by_equal["0"] == "reqhttps"){
							$reqhttps = trim($by_equal["1"]);
						}
					}
				}
			}
		}
		return $reqhttps;
	}
	
	function payment(){
		$model = $this->getModel("guruBuy");
		if(JRequest::getVar('processor', '') == ''){
			return false;
		}
		
		$_SESSION["creditCardNumber"] = JRequest::getVar("creditCardNumber", "");
		$_SESSION["expDateMonth"] = JRequest::getVar("expDateMonth", "");
		$_SESSION["expDateYear"] = JRequest::getVar("expDateYear", "");
		$_SESSION["cvv2Number"] = JRequest::getVar("cvv2Number", "");

		$dispatcher = & JDispatcher::getInstance();
		JPluginHelper::importPlugin('gurupayment');
		$params = JPluginHelper::getPlugin('gurupayment', JRequest::getVar('processor'))->params;
		
		$param = array_merge(JRequest::get('request'), array('params' => $params));
		$param['handle'] = &$this;

		$results_plugins = $dispatcher->trigger('onReceivePayment', array(&$param));
		
		$result = array();
		foreach($results_plugins as $result_plugin){
			if(!empty($result_plugin)){
				$result = $result_plugin;
			}
		}

		if(empty($result['sid'])){
			$result['sid'] = -1;
		}	

		if(empty($result['pay'])){
			$result['pay'] = 'fail';
		}	
		
		if(isset($result) && !empty($result)){
			// set sid if empty 
			if((!isset($result['sid']) || empty($result['sid'])) && !empty($result['order_id'])){
				$result['sid'] = $result['order_id'];
			}
			switch($result['pay']){
				case 'success':
					$model->proccessSuccess($this, $result);
					break;
				case 'ipn':
					$model->proccessIPN($this, $result);
					break;
				case 'wait':
					$model->proccessWait($this, $result);
					break;
				case 'fail':
					if($result["processor"] == "paypaypal" || $result["processor"] == "offline"){
						$model->proccessFail($this, $result);
					}
					else{
						$model->proccessAuthorizeFail($this, $result);
					}
					break;
				default:
					break;
			}
		}
	}
	
	function cancelreturn(){
		$msg = JText::_("GURU_OPERATION_CANCELED");
		$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruBuy", false), $msg);
	}
	
	function deleteFromSession(){
		$course_id = JRequest::getVar("course_id");
		$action = JRequest::getVar("action", "");
		if(trim($action) == "buy"){
			$all_courses = $_SESSION["courses_from_cart"];
			unset($all_courses[$course_id]);
			$_SESSION["courses_from_cart"] = $all_courses;
		}
		else{
			$all_courses = $_SESSION["renew_courses_from_cart"];
			unset($all_courses[$course_id]);
			$_SESSION["renew_courses_from_cart"] = $all_courses;
		}
	}

};

?>