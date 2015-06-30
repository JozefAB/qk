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
jimport ("joomla.aplication.component.model");

class guruModelguruBuy extends JModelLegacy {
	var $_plugins;
	var $_plugin;
	var $plugin_instances = array();
	var $_id = null;
	var $allowed_types = array("payment", "encoding");
	var $req_methods = array("getFEData", "getBEData");	
	var $_installpath = ""; 
	var $plugins_loaded = 0;
	
	function __construct () {
		parent::__construct();
		$this->_installpath = JPATH_COMPONENT_ADMINISTRATOR . DS . "plugins" . DS;
		$this->loadPlugins();
	}
	
	function getUserCourses(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id = $user->id;
		$sql = "select course_id from #__guru_buy_courses where userid=".intval($user_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("course_id");
		return $result;
	}
	
	function getCourseDetails($course_id){
		$db = JFactory::getDBO();
		$sql = "select p.name from #__guru_program p where p.id=".intval($course_id);
		/*$sql = "select p.name, pp.price from #__guru_program p, #__guru_program_plans pp where p.id=".intval($course_id)." and p.id=pp.product_id and pp.default=1";*/
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		if($result == ""){
			unset($_SESSION["courses_from_cart"][$course_id]);
			unset($_SESSION["renew_courses_from_cart"][$course_id]);
		}
		return $result;
	}
	
	function getCoursePlans($course_id, $plan){
		$db = JFactory::getDBO();
		$sql = "";
		$action = JRequest::getVar("action", "");
		if(trim($plan) == "renew"){
			$action = "renew";
		}
		if($action == ""){
			$sql = "select sp.name, pp.default, pp.plan_id, pp.price from #__guru_program p, #__guru_program_plans pp, #__guru_subplan sp where p.id=".intval($course_id)." and p.id=pp.product_id and pp.plan_id=sp.id order by sp.ordering asc";
		}
		else{
			$sql = "select sp.name, pp.default, pp.plan_id, pp.price from #__guru_program p, #__guru_program_renewals pp, #__guru_subplan sp where p.id=".intval($course_id)." and p.id=pp.product_id and pp.plan_id=sp.id order by sp.ordering asc";
		}
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		if(isset($result) && count($result) == 0){
			$sql = "select sp.name, pp.default, pp.plan_id, pp.price from #__guru_program p, #__guru_program_plans pp, #__guru_subplan sp where p.id=".intval($course_id)." and p.id=pp.product_id and pp.plan_id=sp.id order by sp.ordering asc";			
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList();
		}		
		return $result;
	}
	
	function getPluginList(){
		if(!empty($this->plugins) && is_array($this->plugins)){
			return $this->plugins;
		}
		$plugins = JPluginHelper::getPlugin('gurupayment');
		return $plugins;
	}
	
	function getCustomer(){
		$user = JFactory::getUser();
		$user_id = $user->id;
		$db = JFactory::getDBO();
		$sql = "select * from #__guru_customer where id=".intval($user_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function getJoomlaUserF($id){
		$db = JFactory::getDBO();
		$sql = "select name, username, email from #__users where id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function checkProfileCompletion($customer){		
		if(empty($customer)){
			return -1;
		}
		
		$user_email = "";

		if(isset($customer["0"]["id"])){
			$user = JFactory::getUser($customer["0"]["id"]);
			$user_email = $user->email;
		}

		if(!isset($customer["0"]["id"]) || ((int)$customer["0"]["id"] <= 0) || strlen(trim($customer["0"]["firstname"])) < 1 || strlen(trim($customer["0"]["lastname"])) < 1 || strlen(trim($user_email)) < 1 ){
			return -1;
		}
		return 1;
	}
	
	function getConfigs(){
		$db = JFactory::getDBO();
		$sql = "select * from #__guru_config";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function getPromo(){
		$promocode = JRequest::getVar("promocode", "");	
		$promo = "";
		$db = JFactory::getDBO();
		if(trim($promocode) == ""){
			$promocode = $_SESSION["promo_code"];
		}
		
		if(trim($promocode) != ""){
			$sql = "select * from #__guru_promos where code='".trim($promocode)."'";
			$db->setQuery($sql);
			$db->query();
			$promo = $db->loadObjectList();			
			$promo = $promo["0"];
		}
		else{
			$promo =  $this->getTable("guruPromos");
		}
		return $promo;
	}
	
	function loadCustomer( $sid ){
		$db = JFactory::getDBO();
		$sql = "select transaction_details from #__guru_session where sid=".$sid;
		$db->setQuery($sql);
		$prof = $db->loadResult();
		return unserialize(base64_decode($prof));
	}
	
	function saveNewOrder($total_prices){
		$user = JFactory::getUser();
		$user_id = $user->id;	
		$all_plans = $this->getPlanExpiration();
	
		if(intval($user_id) != 0){
			$db = JFactory::getDBO();
			$jnow = JFactory::getDate();
			$date = $jnow->toSQL();
			$procesor = $_SESSION["processor"];
			$config = $this->getConfigs();			
			$courses = array();
			$plans = array();
			$all_courses = array();
			$action = JRequest::getVar("action", "");
			if(trim($action) == "renew"){
				$all_courses = $_SESSION["renew_courses_from_cart"];
			}
			else{
				$all_courses = $_SESSION["courses_from_cart"];
			}
			
			if(isset($all_courses) && count($all_courses) > 0){
				foreach($all_courses as $key=>$value){
					$price = trim($value["value"]);
					if($value["plan"] == "buy"){
						$sql = "select p.plan_id from #__guru_program_plans p where p.product_id = ".$value["course_id"]." and price like '".$price."'";
						$db->setQuery($sql);
						$db->query();				
						$plan_id = intval($db->loadResult());
					}
					else{
						$sql = "select p.plan_id from #__guru_program_renewals p where p.product_id = ".$value["course_id"]." and price like '".$price."'";
						$db->setQuery($sql);
						$db->query();				
						$plan_id = intval($db->loadResult());
						if(!isset($plan_id) || $plan_id == NULL || $plan_id == "0"){
							$sql = "select p.plan_id from #__guru_program_plans p where p.product_id = ".$value["course_id"]." and price like '".$price."'";
							$db->setQuery($sql);
							$db->query();				
							$plan_id = intval($db->loadResult());
						}
					}					
					$courses[] = $value["course_id"]."-".$price."-".$plan_id;
					$plans[$value["course_id"]] = $plan_id;
				}
			}			
			$promo_code_id = "0";
			$promo_code = $_SESSION["promo_code"];		
			if(isset($promo_code) && trim($promo_code) != ""){
				$sql = "select id from #__guru_promos where code='".addslashes(trim($promo_code))."'";
				$db->setQuery($sql);
				$db->query();
				$promo_code_id = $db->loadColumn();
				$promo_code_id = $promo_code_id["0"];
			}
			
			//$sql = "INSERT INTO `#__guru_order` (`userid`, `order_date`, `courses`, `status`, `amount`, `amount_paid`, `processor`, `number_of_licenses`, `currency`, `promocodeid`, `published`) VALUES (".$user_id.", '".$date."', '".implode("|", $courses)."', 'Pending', 0, 0, '".addslashes(trim($procesor))."', 0, '".$config["0"]["currency"]."', '".intval($promo_code_id)."' ,0)";
			$sql = "INSERT INTO `#__guru_order` (`userid`, `order_date`, `courses`, `status`, `amount`, `amount_paid`, `processor`, `number_of_licenses`, `currency`, `promocodeid`, `published`) VALUES (".$user_id.", '".$date."', '".implode("|", $courses)."', 'Pending', '".$total_prices."', '-1', '".addslashes(trim($procesor))."', 0, '".$config["0"]["currency"]."', '".intval($promo_code_id)."' ,0)";
			
			$db->setQuery($sql);
			if($db->query()){
				$sql = "select max(id) from #__guru_order where userid=".intval($user_id);
				$db->setQuery($sql);
				$order_id = $db->loadResult();
				if(isset($order_id)){
					foreach($all_courses as $key=>$value){
						if(!$this->wasBuy($key, $user_id)){
							//----------- set expiration courses
								$order_expiration = "";					
								$order_date_int = $this->getCurrentDate(strtotime($date), $key, $user_id);
								if($all_plans[$plans[$key]]["period"] == "hours" && $all_plans[$plans[$key]]["term"] != "0"){
									$order_expiration = strtotime("+".$all_plans[$plans[$key]]["term"]." hours", $order_date_int);
									$order_expiration = date('Y-m-d H:i:s', $order_expiration);
								}
								elseif($all_plans[$plans[$key]]["period"] == "months" && $all_plans[$plans[$key]]["term"] != "0"){
									$order_expiration = strtotime("+".$all_plans[$plans[$key]]["term"]." month", $order_date_int);
									$order_expiration = date('Y-m-d H:i:s', $order_expiration);
								}
								elseif($all_plans[$plans[$key]]["period"] == "years" && $all_plans[$plans[$key]]["term"] != "0"){
									$order_expiration = strtotime("+".$all_plans[$plans[$key]]["term"]." year", $order_date_int);
									$order_expiration = date('Y-m-d H:i:s', $order_expiration);
								}
								elseif($all_plans[$plans[$key]]["period"] == "days" && $all_plans[$plans[$key]]["term"] != "0"){
									$order_expiration = strtotime("+".$all_plans[$plans[$key]]["term"]." days", $order_date_int);
									$order_expiration = date('Y-m-d H:i:s', $order_expiration);
								}
								elseif($all_plans[$plans[$key]]["period"] == "weeks" && $all_plans[$plans[$key]]["term"] != "0"){
										$order_expiration = strtotime("+".$all_plans[$plans[$key]]["term"]." weeks", $order_date_int);
										$order_expiration = date('Y-m-d H:i:s', $order_expiration);
								}
								else{//for unlimited
									$order_expiration = "0000-00-00 00:00:00";
								}
							//---------------set expiration course	
							if($procesor == 'offline'){
								$sql  = "insert into #__guru_buy_courses (`userid`, `order_id`, `course_id`, `price`, `buy_date`, `expired_date`, `plan_id`, `email_send`) values ";
								$sql .= "(".$user_id.", ".$order_id.", ".$key.", '".$value["value"]."', '".$date."', '".$order_expiration."', '".$plans[$key]."', 0)";
								$db->setQuery($sql);
								$db->query();
							}
							else{
								$sql  = "insert into #__guru_buy_courses (`userid`, `order_id`, `course_id`, `price`, `buy_date`, `expired_date`, `plan_id`, `email_send`) values ";
								$sql .= "(".$user_id.", ".$order_id.", ".$key.", '".$value["value"]."', '".$date."', '', '".$plans[$key]."', 0)";
								$db->setQuery($sql);
								$db->query();
							}
						}
						else{
							$sql = 'update #__guru_buy_courses set plan_id=CONCAT(`plan_id`, "|", '.$plans[$key].') where userid='.$user_id." and course_id=".$key;
							$db->setQuery($sql);
							$db->query();



						}
					}
				}
				if($procesor == 'offline'){
					// start  sent email to admin to let him know that there are orders in pending
					$template_emails = $config["0"]["template_emails"];
					$template_emails = json_decode($template_emails, true);
					$subject_procesed = $template_emails["pending_order_subject"];
					$body_procesed = $template_emails["pending_order_body"];
					
					
					
					$firstnamelastname = "SELECT firstname, lastname FROM #__guru_customer WHERE id=".intval($user_id);
					$db->setQuery($firstnamelastname);
					$db->query();
					$firstnamelastname = $db->loadAssocList();
					
					
					
					$sql= "Select courses from #__guru_order WHERE id=".intval($order_id)." and userid=".intval($user_id)."";
					$db->setQuery($sql);
					$db->query();
					$courselist = $db->loadColumn();
					$idss = array();
					if(trim($courselist["0"]) != ""){
						$temp1 = explode("|", trim($courselist["0"]));
						if(is_array($temp1) && count($temp1) > 0){
							foreach($temp1 as $key=>$value){
								$temp2 = explode("-", $value);
								$idss[] = trim($temp2["0"]);
							}
						}
					}
			
					$list_of_coursesids = implode(",", $idss);	
								
					$sql = "Select name from #__guru_program where id in (".$list_of_coursesids.")";
					$db->setQuery($sql);
					$db->query();
					$coursename = $db->loadColumn();
					$coursename = implode(", ", $coursename);
					$configss = JFactory::getConfig();
					$from = $configss->get("mailfrom");
					$fromname = $configss->get("fromname");
						
					$order_url_list = '<a href="'.JURI::root().'administrator/index.php?option=com_guru&controller=guruOrders" target="_blank">'.$fromname.'</a>';
					
					$body_procesed = str_replace("[COURSE_NAME]", $coursename, $body_procesed);
					$body_procesed = str_replace("[STUDENT_LAST_NAME]", $firstnamelastname[0]["lastname"], $body_procesed);
					$body_procesed = str_replace("[STUDENT_FIRST_NAME]", $firstnamelastname[0]["firstname"], $body_procesed);
					$body_procesed = str_replace("[ORDER_NUMBER]", $order_id, $body_procesed);
					$body_procesed = str_replace("[GURU_ORDER_LIST_URL]", $order_url_list, $body_procesed);
					
					
					$sql = "select u.`email` from #__users u, #__user_usergroup_map ugm where u.`id`=ugm.`user_id` and ugm.`group_id`='8' and u.`id` IN (".$config["0"]["admin_email"].")";
					$db->setQuery($sql);
					$db->query();
					$email = $db->loadColumn();
					
					for($i=0; $i< count($email); $i++){
						JFactory::getMailer()->sendMail($from, $fromname, $email[$i], $subject_procesed, $body_procesed, 1);
					}
					// end  sent email to admin to let him know that there are orders in pending
				}
				return $order_id;
			}
			return "0";
		}
		return "0";
	}
	
	function wasBuy($course_id, $user_id){
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__guru_buy_courses where userid=".intval($user_id)." and course_id=".intval($course_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if($result == "0"){
			return false;
		}
		return true;
	}
	
	function getCourseId($course_name){
		$db = JFactory::getDBO();
		$sql = "select id from #__guru_program where name like '".addslashes(trim($course_name))."'";
		$db->setQuery($sql);
		$db->query();
		$id = $db->loadResult();
		return $id;
	}
	
	function getlistPlugins(){
		if(empty($this->_plugins)){
			$sql = "select * from #__guru_plugins";
			$this->_plugins = $this->_getList($sql);			
		}
		return $this->_plugins;
	}
	
	function registerPlugin($filename, $classname){
		$install_path = $this->_installpath; 
		if (!file_exists($install_path.$filename)) {
			return 0;//_NO_PLUGIN_FILE_EXISTS;	
		}
		require_once ($install_path.$filename);
		$plugin = new $classname;//new $this->plugins[$classname];	// 
		if(!is_object($plugin)){
			return 0;
		}
		foreach($this->req_methods as $method){
			if(!method_exists($plugin, $method)){
				return 0;
			}
		}
		if(isset($this->_plugins[$classname])){
			$this->_plugins[$classname]->instance =& $plugin;
		}
		else{
			$this->_plugins[$classname] = new stdClass;
			$this->_plugins[$classname]->instance =& $plugin;
		}
		return $plugin;
	}
	
	function loadPlugins(){
		if($this->plugins_loaded == 1){
			return;
		}
		$plugins = $this->getlistPlugins();

		foreach($plugins as $plugin){
			$this->registerPlugin($plugin->filename, $plugin->classname);
		    if($plugin->published == '1'){
	        	if($plugin->type == 'payment'){
					$this->payment_plugins[$plugin->name] = $plugin;
					if($plugin->def == 'default'){
						$this->default_payment = $plugin;
					}
				}
				if($plugin->type == 'encoding'){
					$this->encoding_plugins[$plugin->name] = $plugin;
				}
			}
		}
		$this->plugins_loaded = 1;
		return;
	}

	function getCartItems(){
		$db = JFactory::getDBO();
		$items = array();
		$action = JRequest::getVar("action", "");
		if(trim($action) == "renew"){
			$items = $_SESSION["renew_courses_from_cart"];
		}
		else{
			$items = $_SESSION["courses_from_cart"];
		}
		
		if(isset($items) && count($items) > 0){			
			foreach($items as  $key=>$value){
				$plan_id = "0";
				if($value["plan"] == "buy"){
					$sql = "select p.plan_id from #__guru_program_plans p where p.product_id = ".$value["course_id"]." and price like '".$value["value"]."'";
					$db->setQuery($sql);
					$db->query();				
					$plan_id = intval($db->loadResult());					
				}
				else{
					$sql = "select p.plan_id from #__guru_program_renewals p where p.product_id = ".$value["course_id"]." and price like '".$value["value"]."'";
					$db->setQuery($sql);
					$db->query();				
					$plan_id = intval($db->loadResult());
					if(!isset($plan_id) || $plan_id == NULL || $plan_id == "0"){
						$sql = "select p.plan_id from #__guru_program_plans p where p.product_id = ".$value["course_id"]." and price like '".$value["value"]."'";
						$db->setQuery($sql);
						$db->query();				
						$plan_id = intval($db->loadResult());
					}
				}
				$sql = "select s.name from #__guru_subplan s where id=".intval($plan_id);				
				$db->setQuery($sql);
				$db->query();
				$plan_name = $db->loadResult();
				$items[$key]["name"] .= " - ".$plan_name;
			}	
		}		
		return $items;
	}
	
	function proccessAuthorizeFail($controller, $result){
		$msg = "Fail payment";
		if(isset($result['msg']) && !empty($result['msg'])){
			$msg .= " :" . $result['msg'];
		}
		if($result["processor"] == "payauthorize"){
			$sid = $result["sid"];
			$return_url = "index.php?option=com_guru&view=guruBuy&action2=submit&order_id=".intval($sid);
		}
		$controller->setRedirect(JRoute::_($return_url), $msg);
	}
	
	function proccessFail($controller, $result){
		global $Itemid;
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id = $user->id;
		$order_id = $result["order_id"];
		$page_itemid = $result["Itemid"];
		if($page_itemid != '0'){
			$Itemid = "&Itemid=".$page_itemid;
		}
		else{
			$Itemid = NULL;
		}
		
		unset($_SESSION["courses_from_cart"]);
		unset($_SESSION["renew_courses_from_cart"]);
		unset($_SESSION["promo_code"]);
		unset($_SESSION["max_total"]);
		unset($_SESSION["order_id"]);
		unset($_SESSION["promocode"]);
		unset($_SESSION["processor"]);
		unset($_SESSION["discount_value"]);
		
		$sql = "select courses from #__guru_order where id=".intval($order_id);
		$db->setQuery($sql);
		$db->query();
		$courses_string = $db->loadResult();
		if(isset($courses_string) && trim($courses_string) != ""){
			$all_courses_string = explode("|", $courses_string);
			if(isset($all_courses_string) && count($all_courses_string) > 0){
				foreach($all_courses_string as $key=>$value){
					$temp = explode("-", $value);
					$course_id = $temp["0"];
					$sql = "select plan_id from #__guru_buy_courses where userid=".intval($user_id)." and course_id=".intval($course_id);
					$db->setQuery($sql);
					$db->query();
					$plan_id = $db->loadResult();
					if(strpos($plan_id, "|")){
						$temp = explode("|", $plan_id);
						$plan_id = $temp["0"];
						$sql = "update #__guru_buy_courses set plan_id='".$plan_id."'where userid=".intval($user_id)." and course_id=".intval($course_id);
						$db->setQuery($sql);
						$db->query();
					}
				}
			}
		}
		
		$sql = "delete from #__guru_order where id=".intval($order_id);
		$db->setQuery($sql);
		$db->query();
		
		$sql = "delete from #__guru_buy_courses where order_id=".intval($order_id);
		$db->setQuery($sql);
		$db->query();
		
        $failed_url = JURI::base()."index.php?option=com_guru&view=gurupcategs".$Itemid;
		$failed_url = str_replace ("https://", "http://", $failed_url);
		$app->redirect(JRoute::_($failed_url), JText::_('GURU_FAIL_PAY'));
	}
	
	function proccessIPN($controller, $result){
		$this->proccessSuccess($controller, $result, true);
	}
	
	function getCurrentDate($today_date, $course_id, $user_id){
		$db = JFactory::getDBO();
		$sql = "select `expired_date` from #__guru_buy_courses where course_id=".intval($course_id)." and userid=".intval($user_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if($result != NULL && trim($result) != ""){
			$date_int = strtotime(trim($result));
			if($today_date > $date_int){
				return $today_date;
			}
			elseif($today_date <= $date_int){
				return $date_int;
			}
		}
		else{
			return $today_date;
		}
	}
	
	function proccessSuccess($controller, $result, $stop = false){
		global $Itemid;
		$app = JFactory::getApplication("site");
		require_once(JPATH_COMPONENT.DS.'helpers'.DS.'cronjobs.php');
		$db = JFactory::getDBO();
		$customer_id = isset($result["customer_id"])? $result["customer_id"] : $result["user_id"];
		$order_id = isset($result["order_id"]) ? $result["order_id"] : $result["sid"];
		$price = $result["price"];
		
		$sql = "select status from #__guru_order where id=".intval($order_id);
		$db->setQuery($sql);
		$db->query();
		$orderstatus = $db->loadResult();
		
		$sql = "select processor from #__guru_order where id=".intval($order_id);
		$db->setQuery($sql);
		$db->query();
		$processor_payment = $db->loadResult();
		
		$sql = "select promocodeid from #__guru_order where id=".intval($order_id);
		$db->setQuery($sql);
		$db->query();
		$promocodeid = $db->loadResult();
		
		$sql = "select currency from #__guru_config where id=1";
		$db->setQuery($sql);
		$db->query();
		$currency = $db->loadResult();
		
		
		if(isset($orderstatus) && trim($orderstatus) == "Pending"){
			//----------- set expiration courses
			$all_plans = $this->getPlanExpiration();
			$jnow = JFactory::getDate();
			$order_date = $jnow->toSQL();
			$sql = "select courses from #__guru_order where id=".intval($order_id);
			$db->setQuery($sql);
			$db->query();
			$courses = $db->loadResult();
			if(isset($courses) && trim($courses) != ""){
				$all_courses = explode("|", $courses);
				if(isset($all_courses) && is_array($all_courses) && count($all_courses) > 0){
					foreach($all_courses as $key => $value){
						$temp1 = explode("-", $value);
						$course_id = $temp1["0"];
						$plan_id = $temp1["2"];
						$allpricecourse = $temp1["1"];
						
						$sql = "select plan_id from #__guru_buy_courses where userid=".intval($customer_id)." and course_id=".intval($course_id);
						$db->setQuery($sql);
						$db->query();
						$old_plan_id = $db->loadResult();
						if(strpos($old_plan_id, "|") !== FALSE){
							$temp = explode("|", $old_plan_id);
							$plan_id = $temp["1"];
						}
						$order_expiration = "";					
						$order_date_int = $this->getCurrentDate(strtotime($order_date), $course_id, $customer_id);
						if($all_plans[$plan_id]["period"] == "hours" && $all_plans[$plan_id]["term"] != "0"){
							$order_expiration = strtotime("+".$all_plans[$plan_id]["term"]." hours", $order_date_int);
							$order_expiration = date('Y-m-d H:i:s', $order_expiration);
						}
						elseif($all_plans[$plan_id]["period"] == "months" && $all_plans[$plan_id]["term"] != "0"){
							$order_expiration = strtotime("+".$all_plans[$plan_id]["term"]." month", $order_date_int);
							$order_expiration = date('Y-m-d H:i:s', $order_expiration);
						}
						elseif($all_plans[$plan_id]["period"] == "years" && $all_plans[$plan_id]["term"] != "0"){
							$order_expiration = strtotime("+".$all_plans[$plan_id]["term"]." year", $order_date_int);
							$order_expiration = date('Y-m-d H:i:s', $order_expiration);
						}
						elseif($all_plans[$plan_id]["period"] == "days" && $all_plans[$plan_id]["term"] != "0"){
							$order_expiration = strtotime("+".$all_plans[$plan_id]["term"]." days", $order_date_int);
							$order_expiration = date('Y-m-d H:i:s', $order_expiration);
						}
						elseif($all_plans[$plan_id]["period"] == "weeks" && $all_plans[$plan_id]["term"] != "0"){
								$order_expiration = strtotime("+".$all_plans[$plan_id]["term"]." weeks", $order_date_int);
								$order_expiration = date('Y-m-d H:i:s', $order_expiration);
						}
						else{//for unlimited
							$order_expiration = "0000-00-00 00:00:00";
						}
						$sql = "update #__guru_buy_courses set buy_date='".$order_date."', expired_date='".$order_expiration."', plan_id=".$plan_id.", email_send=0, order_id=".intval($order_id)." where userid=".intval($customer_id)." and course_id=".intval($course_id);
						$db->setQuery($sql);
						$db->query();
						//-----------------send email
						sendEmailOnPurcase($course_id, $order_id, $order_expiration, $plan_id);
						//-----------------send email
						
						// start teacher commission calculation 
						$sql = "select author from #__guru_program where id=".intval($course_id);
						$db->setQuery($sql);
						$db->query();
						$author = $db->loadResult();
						
						$sql = "select commission_id from #__guru_authors where userid=".intval($author);
						$db->setQuery($sql);
						$db->query();
						$commission_id = $db->loadResult();
						
						$sql = "select teacher_earnings from #__guru_commissions where id=".intval($commission_id);
						$db->setQuery($sql);
						$db->query();
						$teacher_earnings = $db->loadResult(); 
						
						$amount_paid_author = ($teacher_earnings * $price)/100;  
			
						$sql = "INSERT INTO `#__guru_authors_commissions` (`author_id`, `course_id`, `plan_id`, `order_id`, `customer_id`, `commission_id`, `price`, `price_paid`, `amount_paid_author`, `promocode_id`, `status_payment`, `payment_method`, `data`, `currency`) VALUES('".intval($author)."', ".intval($course_id).", ".$plan_id.", '".intval($order_id)."', '".intval($customer_id)."', '".$commission_id."', '".$allpricecourse."', '".$price."', '".$amount_paid_author."', '".$promocodeid."', 'pending', '".$processor_payment."', '".$order_date."', '".$currency."')";
						$db->setQuery($sql);
						$db->query();
					
					// end teacher commission calculation 
					}
				}
			}
			//-----------
			$sql = "update #__guru_order set `order_date`='".$order_date."', `status`='Paid', `amount`='".$price."', `amount_paid`='".$price."', form='' where id=".$order_id;
			$db->setQuery($sql);
			$db->query();
		//$app->redirect(JRoute::_("index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=".$item_id));
		}
		$app->redirect("index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=".$item_id, JText::_("GURU_PAYMENT_SUCCESSFULLY"));
		return true;
	}
	
	function proccessWait( $controller, $result ){
		global $Itemid;
		$var_processor = $_SESSION["processor"];
		$app = JFactory::getApplication("site");
		unset($_SESSION["courses_from_cart"]);
		unset($_SESSION["renew_courses_from_cart"]);
		unset($_SESSION["promo_code"]);
		unset($_SESSION["max_total"]);
		unset($_SESSION["order_id"]);
		unset($_SESSION["promocode"]);
		unset($_SESSION["processor"]);
		if($var_processor == 'offline'){
			$app->redirect(JRoute::_("index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=".$item_id));
		}
		else{
			$app->redirect(JRoute::_("index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=".$item_id));
		}
		return true;
	}
	
	function getPlanExpiration(){
		$db = JFactory::getDBO();
		$sql = "select * from #__guru_subplan";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
};

?>