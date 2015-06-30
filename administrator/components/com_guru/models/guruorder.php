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

jimport('joomla.application.component.modellist');
jimport('joomla.utilities.date');

class guruAdminModelguruOrder extends JModelLegacy {
	var $_orders;
	var $_order;
	var $_id = null;
	var $_total = 0;
	var $total = 0;
	var $_pagination = null;
	protected $_context = 'com_guru.guruOrder';

	function __construct () {
		parent::__construct();
		$cids = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int)$cids[0]);
		$mainframe =JFactory::getApplication();

		global $option;
		// Get the pagination request variables
		$limit = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );
		
		if(JRequest::getVar("limitstart") == JRequest::getVar("old_limit")){
			JRequest::setVar("limitstart", "0");		
			$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
			$limitstart = $mainframe->getUserStateFromRequest($option.'limitstart', 'limitstart', 0, 'int');
		}
		
		$this->setState('limit', $limit); // Set the limit variable for query later on
		$this->setState('limitstart', $limitstart);	
	}
	
    function getPagination() {
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))	{
			jimport('joomla.html.pagination');
			if (!$this->_total) $this->getItems();
			$this->_pagination = new JPagination( $this->_total, $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}
	
	function getItems(){
		$config = new JConfig();	
		$app = JFactory::getApplication('administrator');
		$startdate = JRequest::getVar("startdate", "");
		$enddate = JRequest::getVar("enddate", "");
		$search = JRequest::getVar("search", "");
		$filter_status = JRequest::getVar("filter_status", "-");
		$filter_payement = JRequest::getVar("filter_payement", "-");
		$filter_course = JRequest::getVar("filter_course", "");
		$filter_teacher = JRequest::getVar("filter_teacher", "");
		$db = JFactory::getDBO();
		
		$and = "";
		
		if($startdate != "" && $enddate == ""){
			$and .= " and o.`order_date` >= '".$startdate."'";
		}
		elseif($startdate == "" && $enddate != ""){
			$and .= " and o.`order_date` <= '".$enddate."'";
		}
		elseif($startdate != "" && $enddate != ""){
			$and .= " and o.`order_date` >= '".$startdate."' and o.`order_date` <= '".$enddate."'";
		}
		
		if($filter_status != "-"){
			$and .= " and o.`status`='".$filter_status."'";
		}
		
		if($filter_payement != "-"){
			$and .= " and o.`processor` ='".$filter_payement."'";
		}
		if(intval($filter_teacher) != 0){
			$courses = array("0");
			if(intval($filter_course) == 0){
				$sql = "select `id` from #__guru_program where `author`=".intval($filter_teacher);
			}
			else{
				$sql = "select `id` from #__guru_program where `author`=".intval($filter_teacher)." and id =".intval($filter_course);
			}
			$db->setQuery($sql);
			$db->query();
			$courses_ids = $db->loadColumn();
			if(isset($courses_ids) && count($courses_ids) > 0){
				$courses = $courses_ids;
			}
			
			$or = array();
			foreach($courses as $key=>$id){
				$or[] = "o.`courses` like '".$id."-%' OR o.`courses` like '%|".$id."-%'";
			}
			$and .= " and (".implode(" OR ", $or).")";
		}
		
		if(trim($search) != ""){			
			$and .= " and (c.`firstname` like '%".addslashes(trim($search))."%' or c.`lastname` like '%".addslashes(trim($search))."%' or u.`username` like '".addslashes(trim($search))."')";
		}
		$_SESSION['search_order'] = trim($search);
		
		$sql = "select o.*, u.username, c.firstname, c.lastname from #__guru_order o, #__users u, #__guru_customer c where c.id=u.id and u.id=o.userid ".$and." order by o.order_date desc ";
		$export = JRequest::getVar("export", "");
		if($export != ""){
			$this->exportOrders($sql);
		}
		
		$limitstart=$this->getState('limitstart');
		$limit=$this->getState('limit');
			
		if($limit!=0){
			$limit_cond=" LIMIT ".$limitstart.",".$limit." ";
		} else {
			$limit_cond = NULL;
		}
		$result = $this->_getList($sql.$limit_cond);
		$this->_total = $this->_getListCount($sql);
		return $result;
	}
	
	function getTotalSum(){
		$config = new JConfig();	
		$app = JFactory::getApplication('administrator');
		$startdate = JRequest::getVar("startdate", "");
		$enddate = JRequest::getVar("enddate", "");
		$search = JRequest::getVar("search", "");
		$filter_status = JRequest::getVar("filter_status", "-");
		$filter_payement = JRequest::getVar("filter_payement", "-");
		$filter_course = JRequest::getVar("filter_course", "");
		$filter_teacher = JRequest::getVar("filter_teacher", "");
		$db = JFactory::getDBO();
		$courses = array();
		
		$and = "";
		
		if($startdate != "" && $enddate == ""){
			$and .= " and o.`order_date` >= '".$startdate."'";
		}
		elseif($startdate == "" && $enddate != ""){
			$and .= " and o.`order_date` <= '".$enddate."'";
		}
		elseif($startdate != "" && $enddate != ""){
			$and .= " and o.`order_date` >= '".$startdate."' and o.`order_date` <= '".$enddate."'";
		}
		
		if($filter_status != "-"){
			$and .= " and o.`status`='".$filter_status."'";
		}
		
		if($filter_payement != "-"){
			$and .= " and o.`processor` ='".$filter_payement."'";
		}
		
		if(intval($filter_teacher) != 0){
			$courses = array("0");
			$sql = "select `id` from #__guru_program where `author`=".intval($filter_teacher);
			$db->setQuery($sql);
			$db->query();
			$courses_ids = $db->loadColumn();
			if(isset($courses_ids) && count($courses_ids) > 0){
				$courses = $courses_ids;
			}
			
			$or = array();
			foreach($courses as $key=>$id){
				$or[] = "o.`courses` like '".$id."-%' OR o.`courses` like '%|".$id."-%'";
			}
			$and .= " and (".implode(" OR ", $or).")";
		}
		
		if(trim($search) != ""){			
			$and .= " and (c.`firstname` like '%".addslashes(trim($search))."%' or c.`lastname` like '%".addslashes(trim($search))."%' or u.`username` like '".addslashes(trim($search))."')";
		}
		$_SESSION['search_order'] = trim($search);
		
		$sql = "select o.*, u.username, c.firstname, c.lastname from #__guru_order o, #__users u, #__guru_customer c where c.id=u.id and u.id=o.userid ".$and." order by o.order_date desc ";
		
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		
		$return = 0.00;
		$currency = "USD";
		
		if(isset($result) && count($result) > 0){
			foreach($result as $key=>$value){
				if(isset($value["courses"]) && trim($value["courses"]) != ""){
					$list = explode("|", $value["courses"]);
					
					if(isset($list) && count($list) > 0){
						foreach($list as $nr=>$element){
							$temp = explode("-", $element);
							$course_id = intval($temp["0"]);
							if(isset($courses) && count($courses) > 0){// filter about teachers
								if(in_array($course_id, $courses)){ // if this course is from our filter
									if($value["amount_paid"] == -1){
										$currency = $value["currency"];
										$return += $temp["1"];
									}
									else{
										$currency = $value["currency"];
										$return += $value["amount_paid"];
									}
								}
							}
							else{// not filter about teachers
								if($value["amount_paid"] == -1){
									$currency = $value["currency"];
									$return += $temp["1"];
								}
								else{
									$currency = $value["currency"];
									$return += $value["amount_paid"];
								}
							}
						}
					}
				}
			}
		}
		$character = "GURU_CURRENCY_".$currency;
		return JText::_($character).$return;
	}

	function exportOrders($sql){
		$db = JFactory::getDBO();
		$export = JRequest::getVar("export", "");
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		
		if($export == "csv"){
			$users = $this->getUsers();
			$courses = $this->getAllCourses();
			
			$data = "";
			$header = array("Date", "Price", "Username", "Student", "Status", "Course(s)", "Payment Method");
			
			$data .= implode(",", $header);
			$data .= "\n";
			
			if(isset($result) && count($result) > 0){
				foreach($result as $key=>$value){
					$data .= $value["order_date"].",";
					if($value["amount_paid"] == "-1"){
						$data .= $value["amount"].",";
					}
					else{
						$data .= $value["amount_paid"].",";
					}
					
					$data .= $users[$value["userid"]]["username"].",";
					$data .= $users[$value["userid"]]["name"].",";
					$data .= $value["status"].",";
					
					if(isset($value["courses"]) && trim($value["courses"]) != ""){
						$list = explode("|", $value["courses"]);
						if(isset($list) && count($list) > 0){
							$list_courses_name = array();
							foreach($list as $nr=>$element){
								$temp = explode("-", $element);
								if(isset($temp["0"]) && trim($temp["0"]) != ""){
									$list_courses_name[] = $courses[$temp["0"]]["name"];
								}
							}
							$data .= implode("; ", $list_courses_name).",";
						}
						else{
							$data .= ",";
						}
					}
					else{
						$data .= ",";
					}
					
					$data .= $value["processor"]."\n";
				}
			}
			
			$csv_filename = "orders.csv";
			$size_in_bytes = strlen($data);
			header("Content-Type: application/x-msdownload");
			header("Content-Disposition: attachment; filename=".$csv_filename);
			header("Pragma: no-cache");
			header("Expires: 0");
			echo utf8_decode($data);
			exit();
		}
		elseif($export == "pdf"){
			$users = $this->getUsers();
			$courses = $this->getAllCourses();
			$file_content = "<style>
								td{
									border:1px solid #000000;
								}
								table{
									border-collapse: collapse
								}
							 </style>";
			
			require(JPATH_SITE.DS."components".DS."com_guru".DS."helpers".DS."MPDF".DS."mpdf.php");
			$pdf = new mPDF('utf-8','A4-L');
			
			$file_content .= '<table>';
			$file_content .= '	<tr>';
			$file_content .= '		<td>Date</td>';
			$file_content .= '		<td>Price</td>';
			$file_content .= '		<td>Username</td>';
			$file_content .= '		<td>Student</td>';
			$file_content .= '		<td>Status</td>';
			$file_content .= '		<td>Course(s)</td>';
			$file_content .= '		<td>Payment Method</td>';
			$file_content .= '	</tr>';
			
			if(isset($result) && count($result) > 0){
				foreach($result as $key=>$value){
					$file_content .= '<tr>';
					
					$file_content .= '<td>'.$value["order_date"].'</td>';
					
					if($value["amount_paid"] == "-1"){
						$file_content .= '<td>'.$value["amount"].'</td>';
					}
					else{
						$file_content .= '<td>'.$value["amount_paid"].'</td>';
					}
					
					$file_content .= '<td>'.$users[$value["userid"]]["username"].'</td>';
					$file_content .= '<td>'.$users[$value["userid"]]["name"].'</td>';
					$file_content .= '<td>'.$value["status"].'</td>';
					
					if(isset($value["courses"]) && trim($value["courses"]) != ""){
						$list = explode("|", $value["courses"]);
						if(isset($list) && count($list) > 0){
							$list_courses_name = array();
							foreach($list as $nr=>$element){
								$temp = explode("-", $element);
								if(isset($temp["0"]) && trim($temp["0"]) != ""){
									$list_courses_name[] = $courses[$temp["0"]]["name"];
								}
							}
							$file_content .= '<td>'.implode("</br>", $list_courses_name).'</td>';
						}
						else{
							$file_content .= '<td></td>';
						}
					}
					else{
						$file_content .= '<td></td>';
					}
					
					$file_content .= '<td>'.$value["processor"].'</td>';
					$file_content .= '</tr>';
				}
			}
			
			$file_content .= '</table>';
			
			//set up a page
			$pdf->AddPage('L');
			$pdf->SetXY(100,50);
			$pdf->SetDisplayMode('fullpage');  
			$pdf->WriteHTML($file_content);
			$pdf->Output('orders.pdf','D'); 
			exit;
		}
	}
	
	function getUsers(){
		$db = JFactory::getDBO();
		$sql = "select u.`id`, u.`name`, u.`username` from #__users u, #__guru_customer g where u.`id`=g.`id`";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	
	function getAllCourses(){
		$db = JFactory::getDBO();
		$sql = "select `id`, `name` from #__guru_program";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}

	function setId($id) {
		$this->_id = $id;

		$this->_order = null;
	}
	
	public static function getAllPromos(){
		$db = JFactory::getDBO();
		$jnow = JFactory::getDate();
		$date = $jnow->toSQL();
		$sql = "select title, code from #__guru_promos where published=1 and codestart <= '".$date."' and (codeend >= '".$date."' or codeend='0000-00-00 00:00:00')";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}

	public static function getJoomlaUser($id){
		$db = JFactory::getDBO();
		$sql = "select name, username, email from #__users where id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}

	function getDiscountDetails($promocode){
		$db = JFactory::getDBO();
		$sql = "select discount, typediscount from #__guru_promos where id = '".addslashes(trim($promocode))."'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function publish(){
		$db = JFactory::getDBO();		
		$cids = JRequest::getVar('cid', array(0), 'post', 'array'); 
		$task = JRequest::getVar('task', '', 'post');
		$item = $this->getTable('guruTasks');
		if ($task == 'publish'){
			$sql = "update #__guru_order set published='1' where oid in ('".implode("','", $cids)."')";
			$ret = 1;
		} else {
			$sql = "update #__guru_order set published='0' where oid in ('".implode("','", $cids)."')";
			$ret = -1;	
		}
		
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
	
		return $ret;
	}
	
	function getOrder() {
		
		if (empty ($this->_order)) {
			$this->_order = $this->getTable("guruOrder");
			$this->_order->load($this->_id);
		}
		return $this->_order;
	}
	
	function getSigns(){
		$db = JFactory::getDBO();
		$sql="SELECT currency_name AS name, sign FROM #__guru_currencies";
		$db->setQuery($sql);
		$result=$db->loadObjectList();
		return $result;
	}

	function saveCustomer(){
		$db = JFactory::getDBO();
		$user_id = $this->saveJoomlaUser();
		return $user_id;
	}	
	
	function saveJoomlaUser(){
		$db = JFactory::getDBO();
		$user_id = JRequest::getVar("userid", "");
		$password = JRequest::getVar("password", "");
		$password = $this->encriptPassword($password);
		$name = JRequest::getVar("firstname", "");
		$username = JRequest::getVar("username", "");
		$email = JRequest::getVar("email", "");
		$usertype = "Registered";
		$block = "0";
		$sendEmail = "0";
		$jnow = JFactory::getDate();
		$registerDate = $jnow->toSQL();
		$lastvisitDate = "0000-00-00 00:00:00";
				
		if($user_id == ""){
			$sql = "insert into #__users(`name`, `username`, `email`, `password`, `block`, `sendEmail`, `registerDate`, `lastvisitDate`, `activation`, `params`) values ('".addslashes(trim($name))."', '".addslashes(trim($username))."', '".addslashes(trim($email))."', '".$password."', 0, 0, '".$registerDate."', '".$lastvisitDate."', '', '')";
			$db->setQuery($sql);
			
			if($db->query()){
				$sql = "select id from #__users where name='".addslashes(trim($name))."' and username='".addslashes(trim($username))."' and email='".addslashes(trim($email))."'";
				$db->setQuery($sql);
				$db->query();
				$user_id = $db->loadResult();
			}			
			if($user_id != ""){
				$query = "select id from #__usergroups where title='Registered'";
				$db->setQuery($query);
				$group_id = $db->loadResult();
				
				$query = "insert into #__user_usergroup_map(`user_id`, `group_id`) values('".$user_id."', '".$group_id."')";
				$db->setQuery($query);
				$group_id = $db->loadResult();			
			}
		}		
		$company = JRequest::getVar("company", "");
		$firstname = JRequest::getVar("firstname", "");
		$lastname = JRequest::getVar("lastname", "");
		
		$sql = "insert into #__guru_customer(`id`, `company`, `firstname`, `lastname`) values (".$user_id.", '".addslashes(trim($company))."', '".addslashes(trim($firstname))."', '".addslashes(trim($lastname))."')";		
		$db->setquery($sql);
		$db->query();
		
		$return = $user_id;
		return $return;
	}
	
	function encriptPassword($password){
		$salt = "";
		for($i=0; $i<=32; $i++) {
			$d = rand(1,30)%2;
		  	$salt .= $d ? chr(rand(65,90)) : chr(rand(48,57));
	   	}
		$hashed = md5($password.$salt);
		$encrypted = $hashed.':'.$salt;
		return $encrypted;
	}

	public static function getPlanExpiration(){
		$db = JFactory::getDBO();
		$sql = "select * from #__guru_subplan";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}

	function courseIsAlreadyBuy($course_id, $user_id){
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

	function saveOrder(){		
		$db = JFactory::getDBO();
		$jnow = JFactory::getDate();
		$datetime = $jnow->toSQL();
		$user_id = JRequest::getVar("userid", "0");
		$course_id = JRequest::getVar("course_id", "");
		$all_price = JRequest::getVar("hidden_licenses", array());
		$subscr_type_select = JRequest::getVar("subscr_type_select", array());
		$amount_paid = JRequest::getVar("amount_paid", "") == "" ? "-1":JRequest::getVar("amount_paid", "");
		$courses = "";
		$all_plans = $this->getPlanExpiration();
		$courses_list_expiration = array();
		$courses_list_plans = array();
		$courses_list_price = array();
		
		$sql = "select currency from #__guru_config where id=1";
		$db->setQuery($sql);
		$db->query();
		$currency = $db->loadResult();
			
		if(isset($course_id) && is_array($course_id) && count($course_id) > 0){
			$course_price = array();
			foreach($course_id as $key=>$value){
				$price = trim($all_price[$key]);
				$sql = "";
				if($subscr_type_select[$key] == "renewal"){
					$sql = "select p.plan_id from #__guru_program_renewals p where p.product_id = ".$value." and price like '".trim($price)."'";
				}
				else{
					$sql = "select p.plan_id from #__guru_program_plans p where p.product_id = ".$value." and price like '".trim($price)."'";
				}
				$db->setQuery($sql);
				$db->query();				
				$plan_id = intval($db->loadResult());
				
				if(intval($plan_id) == "0"){
					$sql = "select p.plan_id from #__guru_program_plans p where p.product_id = ".$value." and price like '".trim($price)."'";
					$db->setQuery($sql);
					$db->query();
					$plan_id = intval($db->loadResult());
				}
				
				$course_price[] = $value."-".$price."-".$plan_id;
				$courses_list_plans[$value] = $plan_id;
				$courses_list_price[$value] = $price;
				//-------------set list with expiration date
				$order_expiration = "";
				$order_date_int = $this->getCurrentDate(strtotime($datetime), $value, $user_id);
				
				if($all_plans[$plan_id]["period"] == "hours" && $all_plans[$plan_id]["term"] != "0"){
					$order_expiration = strtotime("+".$all_plans[$plan_id]["term"]." hours", $order_date_int);
					$courses_list_expiration[$value] = date('Y-m-d H:i:s', $order_expiration);
				}
				elseif($all_plans[$plan_id]["period"] == "months" && $all_plans[$plan_id]["term"] != "0"){
					$order_expiration = strtotime("+".$all_plans[$plan_id]["term"]." month", $order_date_int);
					$courses_list_expiration[$value] = date('Y-m-d H:i:s', $order_expiration);
				}
				elseif($all_plans[$plan_id]["period"] == "years" && $all_plans[$plan_id]["term"] != "0"){
					$order_expiration = strtotime("+".$all_plans[$plan_id]["term"]." year", $order_date_int);
					$courses_list_expiration[$value] = date('Y-m-d H:i:s', $order_expiration);
				}
				elseif($all_plans[$plan_id]["period"] == "days" && $all_plans[$plan_id]["term"] != "0"){
					$order_expiration = strtotime("+".$all_plans[$plan_id]["term"]." days", $order_date_int);
					$courses_list_expiration[$value] = date('Y-m-d H:i:s', $order_expiration);
				}
				elseif($all_plans[$plan_id]["period"] == "weeks" && $all_plans[$plan_id]["term"] != "0"){
					$order_expiration = strtotime("+".$all_plans[$plan_id]["term"]." weeks", $order_date_int);
					$courses_list_expiration[$value] = date('Y-m-d H:i:s', $order_expiration);
				}
				else{
					$courses_list_expiration[$value] = "0000-00-00 00:00:00";	
				}
				//-------------set list with expiration date
			}
			$courses = implode("|", $course_price);
		}
		$amount = JRequest::getVar("total", "");
		$processor = JRequest::getVar("processor", "");
		$number_of_licenses = JRequest::getVar("nr_licenses", "");
		$promocode_code = JRequest::getVar("promocode", "");
		$promocodeid = "0";
		
		if($promocode_code != "none"){		
			$sql = "select id from #__guru_promos where code = '".addslashes(trim($promocode_code))."'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadResult();
			$promocodeid = intval($result);
		}		
		
		$sql = "insert into #__guru_order (`userid`, `order_date`, `courses`, `status`, `amount`, `amount_paid`, `processor`, `number_of_licenses`, `currency`, `promocodeid`, `published`) values (".$user_id.", '".$datetime."', '".$courses."', 'Paid', ".trim($amount).", ".$amount_paid.", '".addslashes(trim($processor))."', ".intval($number_of_licenses).", '".$currency."', '".addslashes(trim($promocodeid))."', 0)";
		$db->setQuery($sql);
		if($db->query()){
			$sql = "select max(id) from #__guru_order";
			$db->setQuery($sql);
			$db->query();
			$order_id = $db->loadResult();
			if(isset($courses_list_expiration) && count($courses_list_expiration) > 0){
				foreach($courses_list_expiration as $course_id=>$date_expiration){
					$sql = "";
					if($this->courseIsAlreadyBuy($course_id, $user_id)){
						$sql = "update #__guru_buy_courses set `expired_date` = '".$date_expiration."', `plan_id` = '".$courses_list_plans[$course_id]."', email_send=0, order_id=".intval($order_id)."  where userid=".intval($user_id)." and course_id=".intval($course_id);
					}
					else{
						$sql  = "insert into #__guru_buy_courses(`userid`, `order_id`, `course_id`, `price`, `buy_date`, `expired_date`, `plan_id`, `email_send`) values ";
						$sql .= "(".intval($user_id).", ".intval($order_id).", ".intval($course_id).", ".$courses_list_price[$course_id].", '".$datetime."', '".$date_expiration."', '".$courses_list_plans[$course_id]."', 0)";
 					}
					$db->setQuery($sql);
					$db->query();
					
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
					
					if($promocodeid !=0){
						$price = self::getPromoDiscountCourse($courses_list_price[$course_id],$promocodeid);
					}
					else{
						$price = $courses_list_price[$course_id];
					}	
														
					$amount_paid_author = ($teacher_earnings * $price)/100;  
					
					$sql = "INSERT INTO `#__guru_authors_commissions` (`author_id`, `course_id`, `plan_id`, `order_id`, `customer_id`, `commission_id`, `price`, `price_paid`, `amount_paid_author`, `promocode_id`, `status_payment`, `payment_method`, `data`, `currency`) VALUES('".intval($author)."', ".intval($course_id).", ".$courses_list_plans[$course_id].", '".intval($order_id)."', '".intval($user_id)."', '".$commission_id."', '".$courses_list_price[$course_id]."', '".$price."', '".$amount_paid_author."', '".$promocodeid."', 'pending', '".$processor."', '".$datetime."','".$currency."')";
					$db->setQuery($sql);
					$db->query();
				// end teacher commission calculation 
				}
			}
		}
		$sql = "select codeused from #__guru_promos where id=".intval($promocodeid);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if(isset($result)){
			$new_val = 	intval($result) + 1;
		}
		else{
			$new_val = 	intval($result) + 0;
		}
		$sql = "update #__guru_promos set `codeused`=".$new_val." where id=".intval($promocodeid);
		$db->setQuery($sql);
		$db->query();
		$this->sendEmailOnPurcaseAdmin($course_id, $order_id ,$order_expiration, $plan_id);
		$_SESSION["orderok"] = 1;
		return true;
	}
	
	function getEmailProgramReminders(){
		$course_id = JRequest::getVar("course_id", "");
		$db = JFactory::getDBO();
		$sql = "select 1 from #__guru_program_reminders re, #__guru_subremind se where re.product_id =".intval($course_id)." and re.emailreminder_id = se.id and se.term=11"; 
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if($result != "0"){
			return true;
		}
		else{
			return false;
		}
	}
	
	function sendEmailOnPurcaseAdmin($course_id, $order_id ,$order_expiration, $plan_id){
		if($this->getEmailProgramReminders()){
			require_once(JPATH_ROOT.DS.'components'.DS.'com_guru'.DS.'helpers'.DS.'cronjobs.php');
			$order_expiration = date("Y-m-d H:m:s", $order_expiration);
			sendEmailOnPurcase($course_id, $order_id, $order_expiration, $plan_id);
		}
	}
	
	function cycleStatus(){
		$id = JRequest::getVar("cid");
		if(isset($id) && is_array($id) && count($id) > 0){
			$id = $id["0"];
			$db = JFactory::getDBO();
			$config = JFactory::getConfig();
			
			$sql = "select status from #__guru_order where id=".intval($id);
			$db->setQuery($sql);
			$db->query();
			$actual = $db->loadResult();
			
			$jnow = JFactory::getDate();
			$order_date = $jnow->toSQL();
			
			$sql = "select currency from #__guru_config where id=1";
			$db->setQuery($sql);
			$db->query();
			$currency = $db->loadResult();
			
			if(isset($actual) && trim($actual) != "" && trim($actual) == "Paid"){				
				$sql = "update #__guru_order set `status` = 'Pending' where id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				
				$sql = "DELETE FROM `#__guru_authors_commissions` where `order_id` =".intval($id);
				$db->setQuery($sql);
				$db->query();
				
				return "1";
			}
			elseif(isset($actual) && trim($actual) != "" && trim($actual) == "Pending"){
				$sql = "update #__guru_order set `status` = 'Paid' where id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				
				$sql = "select count(id) from #__guru_authors_commissions where order_id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$count = $db->loadResult();
				
				$sql = "select processor from #__guru_order where id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$processor_payment = $db->loadResult();
				
				if($count <=0 ){
					$sql = "select courses from #__guru_order where id=".intval($id);
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
								
								$sql = "select userid from #__guru_order where id=".intval($id);
								$db->setQuery($sql);
								$db->query();
								$userid = $db->loadResult();
								
								$sql = "select promocodeid from #__guru_order where id=".intval($id);
								$db->setQuery($sql);
								$db->query();
								$promocodeid = $db->loadResult(); 
								if($promocodeid !=0){
									$price = self::getPromoDiscountCourse($allpricecourse,$promocodeid);
								}
								else{
									$price = $allpricecourse;
								}							
																	
								$amount_paid_author = ($teacher_earnings * $price)/100;    
					
								$sql = "INSERT INTO `#__guru_authors_commissions` (`author_id`, `course_id`, `plan_id`, `order_id`, `customer_id`, `commission_id`, `price`, `price_paid`, `amount_paid_author`, `promocode_id`, `status_payment`, `payment_method`, `data`, `currency`) VALUES('".intval($author)."', ".intval($course_id).", ".$plan_id.", '".intval($id)."', '".intval($userid)."', '".$commission_id."', '".$allpricecourse."', '".$price."', '".$amount_paid_author."', '".$promocodeid."', 'pending', '".$processor_payment."', '".$order_date."','".$currency."')";
								$db->setQuery($sql);
								$db->query();
							
								// end teacher commission calculation 
							}	
						}
					}
				}		
				
				$sql = "select userid, courses from #__guru_order where id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$order_detailss = $db->loadAssocList();
				
				$sql = "SELECT email from #__users where id=".intval($order_detailss["0"]["userid"]);
				$db->setQuery($sql);
				$db->query();
				$email = $db->loadColumn();
				$email = $email["0"];
				$email_list = array("0"=>$email);
				
				$sql = "SELECT firstname from #__guru_customer where id=".intval($order_detailss["0"]["userid"]);
				$db->setQuery($sql);
				$db->query();
				$firstname = $db->loadColumn();
				$firstname = $firstname["0"];
				
				$sql = "select `template_emails` from #__guru_config";
				$db->setQuery($sql);
				$db->query();
				$configs = $db->loadAssocList();
				$template_emails = $configs["0"]["template_emails"];
				$template_emails = json_decode($template_emails, true);
				
				$subject_procesed = $template_emails["approve_order_subject"];
				$body_procesed = $template_emails["approve_order_body"];
				$app = JFactory::getApplication();
				$site_name = $app->getCfg('sitename');
				$site_name = "<a target='_blank' href='".JURI::root()."'>".$site_name."</a>";
				$body_procesed = str_replace("[SITE_NAME]", $site_name, $body_procesed);
				$body_procesed = str_replace("[STUDENT_FIRST_NAME]", $firstname, $body_procesed);
				
				$sql= "Select courses from #__guru_order WHERE id=".intval($id)." and userid=".intval($order_detailss["0"]["userid"])."";
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
				$coursename = "<a target='_blank' href='".JURI::root()."index.php?option=com_guru&view=guruorders&layout=mycourses'>".$coursename."</a>";
				$body_procesed = str_replace("[COURSE_NAME]", "''".$coursename."''", $body_procesed);
				$from = $config->get("mailfrom");
				$fromname = $config->get("fromname");
				JFactory::getMailer()->sendMail($from, $fromname, $email_list, $subject_procesed, $body_procesed, 1);
				return "2";
			}
		}
	}
	function getPromoDiscountCourse($total, $promo_id){
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
	
	function approveOrder(){
		$cids = JRequest::getVar("cid", array(), "post", "array");
		if(isset($cids) && count($cids) > 0){
			$db = JFactory::getDBO();
			$config = JFactory::getConfig();
			$jnow = JFactory::getDate();
			$order_date = $jnow->toSQL();
			$sql = "select currency from #__guru_config where id=1";
			$db->setQuery($sql);
			$db->query();
			$currency = $db->loadResult();
			foreach($cids as $key=>$id){
				$sql = "select status from #__guru_order where id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$actual = $db->loadResult();
				
				$sql = "select promocodeid from #__guru_order where id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$promocodeid = $db->loadResult();
				
				$sql = "select processor from #__guru_order where id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$processor_payment = $db->loadResult();
				
				if(isset($actual) && trim($actual) != "" && trim($actual) == "Pending"){
					$sql = "update #__guru_order set `status` = 'Paid' where id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$sql = "select count(id) from #__guru_authors_commissions where order_id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$count = $db->loadResult();
					if($count <=0 ){
						$sql = "select courses from #__guru_order where id=".intval($id);
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
									
									$sql = "select userid from #__guru_order where id=".intval($id);
									$db->setQuery($sql);
									$db->query();
									$userid = $db->loadResult(); 
									
									$sql = "select promocodeid from #__guru_order where id=".intval($id);
									$db->setQuery($sql);
									$db->query();
									$promocodeid = $db->loadResult(); 
	
									if($promocodeid !=0){
										$price = self::getPromoDiscountCourse($allpricecourse,$promocodeid);
									}
									else{
										$price = $allpricecourse;
									}							
																		
									$amount_paid_author = ($teacher_earnings * $price)/100;  
						
									$sql = "INSERT INTO `#__guru_authors_commissions` (`author_id`, `course_id`, `plan_id`, `order_id`, `customer_id`, `commission_id`, `price`, `price_paid`, `amount_paid_author`, `promocode_id`, `status_payment`, `payment_method`, `data`, `currency`) VALUES('".intval($author)."', ".intval($course_id).", ".$plan_id.", '".intval($id)."', '".intval($userid)."', '".$commission_id."', '".$allpricecourse."', '".$price."', '".$amount_paid_author."', '".$promocodeid."', 'pending', '".$processor_payment."', '".$order_date."', '".$currency."' )";
									$db->setQuery($sql);
									$db->query();
								
									// end teacher commission calculation 
								}	
							}
						}	
					}
					$sql = "select userid, courses from #__guru_order where id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$order_detailss = $db->loadAssocList();
					
					$sql = "SELECT email from #__users where id=".intval($order_detailss["0"]["userid"]);
					$db->setQuery($sql);
					$db->query();
					$email = $db->loadColumn();
					$email = $email["0"];
					$email_list = array("0"=>$email);
					
					$sql = "SELECT firstname from #__guru_customer where id=".intval($order_detailss["0"]["userid"]);
					$db->setQuery($sql);
					$db->query();
					$firstname = $db->loadColumn();
					$firstname = $firstname["0"];
					
					$sql = "select `template_emails` from #__guru_config";
					$db->setQuery($sql);
					$db->query();
					$configs = $db->loadAssocList();
					$template_emails = $configs["0"]["template_emails"];
					$template_emails = json_decode($template_emails, true);
					
					$subject_procesed = $template_emails["approve_order_subject"];
					$body_procesed = $template_emails["approve_order_body"];
					$app = JFactory::getApplication();
					$site_name = $app->getCfg('sitename');

					$site_name = "<a target='_blank' href='".JURI::root()."'>".$site_name."</a>";
					$body_procesed = str_replace("[SITE_NAME]", $site_name, $body_procesed);
					$body_procesed = str_replace("[STUDENT_FIRST_NAME]", $firstname, $body_procesed);
					
					$sql= "Select courses from #__guru_order WHERE id=".intval($id)." and userid=".intval($order_detailss["0"]["userid"])."";
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
					$coursename = "<a target='_blank' href='".JURI::root()."index.php?option=com_guru&view=guruorders&layout=mycourses'>".$coursename."</a>";
					$body_procesed = str_replace("[COURSE_NAME]", "''".$coursename."''", $body_procesed);
					$from = $config->get("mailfrom");
					$fromname = $config->get("fromname");
					JFactory::getMailer()->sendMail($from, $fromname, $email_list, $subject_procesed, $body_procesed, 1);
				}
			}	
			return "2";
		}
	}
	function makePending(){
		$cids = JRequest::getVar("cid", array(), "post", "array");
		if(isset($cids) && count($cids) > 0){
			$db = JFactory::getDBO();
			$config = JFactory::getConfig();
			foreach($cids as $key=>$id){
				$sql = "select status from #__guru_order where id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$actual = $db->loadResult();
				
				if(isset($actual) && trim($actual) != "" && trim($actual) == "Paid"){				
					$sql = "update #__guru_order set `status` = 'Pending' where id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					
					$sql = "DELETE FROM `#__guru_authors_commissions` where `order_id` =".intval($id);
					$db->setQuery($sql);
					$db->query();
				}
			}
			return "1";
		}
	}
	
	function getUserId($username){
		$db = JFactory::getDBO();
		$sql = "select id from #__users where username='".addslashes(trim($username))."'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}
	
	function checkUsername(){
		$db = JFactory::getDBO();
		$username = JRequest::getVar("username", "");
		if(trim($username) != ""){
			$sql = "select count(*) from #__users where username='".addslashes(trim($username))."'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadResult();
			if($result != "0"){
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}

	function getOrderDetails(){
		$db = JFactory::getDBO();
		$user_id = JRequest::getVar("userid", "0");
		$sql = "select username from #__users where id=".intval($user_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}
	
	function getOrderFromOrders(){
		$db = JFactory::getDBO();
		$id = JRequest::getVar("cid", "0");
		$sql = "select * from #__guru_order where id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function getCoursesPromo($id){
		$db = JFactory::getDBO();
		$sql = "select courses_ids from #__guru_promos where id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result;
	}
	
	function getPromo($id){
		$db = JFactory::getDBO();
		$sql = "select * from #__guru_promos where id='".intval($id)."'";
		$db->setQuery($sql);
		$db->query();
		$promo = $db->loadObjectList();			
		$promo = $promo["0"];
		
		return $promo;
	}
	
	function getPromoDiscountCourses($total, $promo_id){
		$old_total = $total;
		$value_to_display = "";
		$promo_details = self:: getPromo($promo_id);
		
		if($promo_details->typediscount == '0') {//use absolute values					
			$difference = $total - (float)$promo_details->discount;
			if($difference < 0){
				$total = 0;
			}
			else{
				$total = $difference;
			}					
			$value_to_display = $promo_details->discount;
		}
		else{//use percentage
			$total = ($promo_details->discount / 100)*$total;
			$difference = $old_total - $total;	
			if($difference < 0){
				$value_to_display =  "0";
			}
			else{
				$discount = $old_total - $difference;
				$value_to_display =  (float)$discount;
			}
		}
		return $value_to_display;
	}

	function delete () {
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');		
		$db = JFactory::getDBO();
		$course_id = "";
		foreach($cids as $cid){
			$sql = "select * FROM #__guru_order WHERE id = ".$cid;
			$db->setQuery($sql);
			$db->query();
			$order_details = $db->loadAssocList();
			
			$student_id = @$order_details["0"]["userid"];
			$courses = @$order_details["0"]["courses"];
			$courses = explode("|", $courses);
			
			if(is_array($courses)){
				foreach($courses as $key=>$value){
					$value = explode("-", $value);
					$course_id = $value["0"];
					$plan_id = $value["2"];
					//check if we can delete for all that course and order OR just delete from valability date but not all course
					$sql = "select count(*) FROM #__guru_order where (courses like '".$course_id."-%' OR courses like '%|".$course_id."-%') and `userid`=".intval($student_id);
					$db->setQuery($sql);
					$db->query();
					$count = $db->loadResult();
					
					if($count == "1"){//delete all course
						$sql = "delete from #__guru_buy_courses where userid=".intval($student_id)." and course_id=".intval($course_id);
						$db->setQuery($sql);
						$db->query();
					}
					else{ //just delete from valability
						$sql = "select bc.expired_date, bc.buy_date, s.term, s.period FROM #__guru_buy_courses bc, #__guru_subplan s where bc.userid=".intval($student_id)." and bc.course_id=".intval($course_id)." and s.id=".intval($plan_id);
						$db->setQuery($sql);
						$db->query();
						$result = $db->loadAssocList();
						
						$expiration_string = $result["0"]["expired_date"];
						$buy_string =  $result["0"]["buy_date"];
						$term = $result["0"]["term"];
						$period = $result["0"]["period"];
						$expiration_int = strtotime($expiration_string);
						
						if($period == "hours" && $term != "0"){
							$expiration_string = strtotime("-".$term." hours", $expiration_int);
							$expiration_string = date('Y-m-d H:i:s', $expiration_string);
						}
						elseif($period == "months" && $term != "0"){
							$expiration_string = strtotime("-".$term." month", $expiration_int);
							$expiration_string = date('Y-m-d H:i:s', $expiration_string);
						}
						elseif($period == "years" && $term != "0"){
							$expiration_string = strtotime("-".$term." year", $expiration_int);
							$expiration_string = date('Y-m-d H:i:s', $expiration_string);
						}
						elseif($period == "days" && $term != "0"){
							$expiration_string = strtotime("-".$term." days", $expiration_int);
							$expiration_string = date('Y-m-d H:i:s', $expiration_string);
						}
						elseif($period == "weeks" && $term != "0"){
							$expiration_string = strtotime("-".$term." weeks", $expiration_int);
							$expiration_string = date('Y-m-d H:i:s', $expiration_string);
						}
						elseif($term == "0"){//if date is 0000-00-00 00:00:00 for unlimited
							$expiration_string = $this->getOldDate($course_id, $cid, $buy_string);
						}
					}
				}
				$sql = "select courses FROM #__guru_order where id = (select max(id) from  #__guru_order where (courses like '".$course_id."-%' OR courses like '%|".$course_id."-%') and id <> ".intval($cid).")";
				$db->setQuery($sql);
				$db->query();
				$find_courses = $db->loadAssocList();
				if(isset($find_courses) && count($find_courses) > 0){
					$find_courses = explode("|", $find_courses["0"]["courses"]);
					foreach($find_courses as $findkey=>$find_value){
						$find_value = explode("-", $find_value);
						$find_plan_id = $find_value["2"];
						$find_course_id = $find_value["0"];
						if($find_course_id == $course_id){
							$sql = "update #__guru_buy_courses set `expired_date`='".trim($expiration_string)."', `plan_id`='".$find_plan_id."' where `userid`=".intval($student_id)." and `course_id`=".intval($course_id);
							$db->setQuery($sql);
							$db->query();
							
							//set old order_id
							$sql = "select max(id) from  #__guru_order where (courses like '".$course_id."-%' OR courses like '%|".$course_id."-%') and id <> ".intval($cid)." and `userid`=".intval($student_id);
							$db->setQuery($sql);
							$db->query();
							$next_order = $db->loadResult();
							if(isset($next_order)){
								$sql = "update #__guru_buy_courses set `order_id`=".intval($next_order)." where `userid`=".intval($student_id)." and `course_id`=".intval($course_id);
								$db->setQuery($sql);
								$db->query();
							}
						}
					}
				}
			}
			$delsql = "DELETE FROM #__guru_order WHERE id = ".$cid;
			$db->setQuery($delsql);
			$db->query();
			
			$sql = "DELETE FROM `#__guru_authors_commissions` where `order_id` =".intval($cid);
			$db->setQuery($sql);
			$db->query();
		}
		return true;
	}
	
	function getOldDate($course_id, $order_id, $buy_string){
		$db = JFactory::getDBO();
		$sql = "select courses FROM #__guru_order where (courses like '".$course_id."-%' OR courses like '%|".$course_id."-%') and id <> ".intval($order_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		$return = "";
		
		$buy_int = strtotime($buy_string);
		
		if(isset($result) && count($result) > 0){
			foreach($result as $key => $course){
				$course = explode("|", $course["courses"]);
				foreach($course as $key=>$value){
					if(trim($value) != ""){
						$value = explode("-", $value);
						$plan_id = $value["2"];
						$courseid = $value["0"];
						if($courseid == $course_id){
							$sql = "select s.term, s.period FROM #__guru_subplan s where s.id=".intval($plan_id);
							$db->setQuery($sql);
							$db->query();
							$plans = $db->loadAssocList();
							$term = $plans["0"]["term"];
							$period = $plans["0"]["period"];
							if($period == "hours" && $term != "0"){
								$return = strtotime("+".$term." hours", $buy_int);
								$return = date('Y-m-d H:i:s', $return);
								$buy_int = strtotime($return);
							}
							elseif($period == "months" && $term != "0"){
								$return = strtotime("+".$term." month", $buy_int);
								$return = date('Y-m-d H:i:s', $return);
								$buy_int = strtotime($return);
							}
							elseif($period == "years" && $term != "0"){
								$return = strtotime("+".$term." year", $buy_int);
								$return = date('Y-m-d H:i:s', $return);
								$buy_int = strtotime($return);
							}
							elseif($period == "days" && $term != "0"){
								$return = strtotime("+".$term." days", $buy_int);
								$return = date('Y-m-d H:i:s', $return);
								$buy_int = strtotime($return);
							}
							elseif($period == "weeks" && $term != "0"){
								$return = strtotime("+".$term." weeks", $buy_int);
								$return = date('Y-m-d H:i:s', $return);
								$buy_int = strtotime($return);
							}
						}	
					}	
				}
			}	
		}
		return $return;
	}
	
	function remove () {
		$cids = JRequest::getVar('cid', array(0), 'get', 'array');
		$item = $this->getTable('adagencyOrder'); 
		foreach ($cids as $cid) {
			if (!$item->delete($cid)) {
				$this->setError($item->getErrorMsg());
				return false;

			}
		}

		return true;
	}
	function getProgramPrice($id){
		$db = JFactory::getDBO();
		$sql = "SELECT price FROM #__guru_program WHERE id = ".$id;
		$db->setQuery($sql);
		if (!$db->query()) {
			echo $db->stderr();
			return;
		}
		$price = $db->loadResult();
		return $price;
	}
	
	public static function getConfig(){
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__guru_config WHERE id = '1' ";
		$db->setQuery($sql);
		if (!$db->query()) {
			echo $db->stderr();
			return;
		}
		$price = $db->loadObject();
		return $price;
	}
	
	public static function getCourses($ids){
		$db = JFactory::getDBO();
		if(trim($ids) != ""){
			$sql = "SELECT * FROM #__guru_program WHERE id in (".$ids.")";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList();
			return $result;
		}
		else{
			return array();
		}
	}
	
	public static function getPlugins(){
		$db = JFactory::getDBO();
		$sql = "select * from #__extensions where folder='gurupayment'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	function getDateType(){
		$db = JFactory::getDBO();
		$sql = "select datetype from #__guru_config WHERE id = '1'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}
	
	function getAllTeachers(){
		$db = JFactory::getDBO();
		$sql = "select u.`id`, u.`name` from #__users u, #__guru_authors a where a.`userid`=u.`id`";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
};
?>