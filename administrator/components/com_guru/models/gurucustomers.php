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


class guruAdminModelguruCustomers extends JModelLegacy{
	var $_customers;
	var $_customer;
	var $_id = null;
	var $_total = 0;
	var $total = 0;
	var $_pagination = null;
	protected $_context = 'com_guru.guruCustomers';

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
   
	function setId($id) {
		$this->_id = $id;
		$this->_customer = null;
	}

	protected function getListQuery(){		
        $db = JFactory::getDBO();
		$search = JRequest::getVar("search", "");
		$and = "";
		if(trim($search) != ""){
			$and = " and (u.username like '%".addslashes(trim($search))."%' or c.firstname like '%".addslashes(trim($search))."%' or c.lastname like '%".addslashes(trim($search))."%')";
		}
		$sql = "select u.id, u.username, c.firstname, c.lastname from #__users u, #__guru_customer c where c.id=u.id ".$and." order by c.id desc ";
		return $sql;
	}
	
	function getItems(){
		$config = new JConfig();	
		$app = JFactory::getApplication('administrator');
		
		$db = JFactory::getDBO();
		$search = JRequest::getVar("search", "");
		$and = "";
		if(trim($search) != ""){
			$and = " and (u.username like '%".addslashes(trim($search))."%' or c.firstname like '%".addslashes(trim($search))."%' or c.lastname like '%".addslashes(trim($search))."%')";
		}
		$sql = "select u.id, u.username, c.firstname, c.lastname,  ug.title usertype, u.block publish, u.lastvisitDate, u.id user_id, u.email, group_concat(ug.title) usertype
				from #__users u, #__guru_customer c, #__user_usergroup_map uugm, #__usergroups ug 
				where c.id=u.id AND uugm.user_id=u.id AND uugm.group_id=ug.id ".$and." 
				GROUP BY u.id
				order by c.id desc ";
		
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


	function getlistCourses () {
		$db = JFactory::getDBO();
		$sql = "SELECT id,name FROM #__guru_program";
		$db->setQuery($sql);
		$courses=$db->loadObjectList();
		return $courses;
	}

	function getFilters(){
		$app = JFactory::getApplication('administrator');
		$filter_search = $app->getUserStateFromRequest('search','search','');
		@$filter->search = $filter_search;
		
		return $filter;
	}

	function getCustomer(){
		if(isset($_REQUEST['userid']) && $_REQUEST['userid'] > 0){
			$db = JFactory::getDBO();
			$q = "SELECT u.id as user_id,
						 u.name as name, 
						 u.email as email, 
						 u.username as username
						 FROM #__users u where id = '".$_REQUEST['userid']."'";
			$db->setQuery($q);
			$db->query();
			$result = $db->loadObjectList();
			$this->_customer = $result[0];
			$this->_customer->id = 0;
		}
		elseif(empty($this->_customer)){ 
			$this->_customer = $this->getTable("guruCustomer"); 
			if($this->_id > 0){
				$this->_customer->load($this->_id);
			}
		}
		return $this->_customer;
	}
	
	function updateUserActivation($id){
			$db = JFactory::getDBO();
			$sql = 'UPDATE #__users set block=0, activation="" where id ='.intval($id);
			$db->setQuery($sql);
			$db->query();
	}
	
	function encriptPassword($password){
		$salt = "";
		for($i=0; $i<=32; $i++){
			$d = rand(1,30)%2;
		  	$salt .= $d ? chr(rand(65,90)) : chr(rand(48,57));
	   	}
		$hashed = md5($password.$salt);
		$encrypted = $hashed.':'.$salt;
		return $encrypted;
	}
	
	function saveJoomlaUser(){
		$db = JFactory::getDBO();
		$user_id = "";
		$password = JRequest::getVar("password", "");
		$password = $this->encriptPassword($password);
		$name = JRequest::getVar("firstname", "");
		$username = JRequest::getVar("username", "");
		$email = JRequest::getVar("email", "");
		$block = "0";
		$sendEmail = "0";
		$jnow = JFactory::getDate();
		$registerDate = $jnow->toSQL(); 
		$lastvisitDate = "0000-00-00 00:00:00";
		
		$sql = "insert into #__users(`name`, `username`, `email`, `password`, `block`, `sendEmail`, `registerDate`, `lastvisitDate`, `activation`, `params`) values ('".addslashes(trim($name))."', '".addslashes(trim($username))."', '".addslashes(trim($email))."', '".$password."', 0, 0, '".$registerDate."', '".$lastvisitDate."', '', '')";
		$db->setQuery($sql);
		
		if($db->query()){
			$sql = "select id from #__users where name='".addslashes(trim($name))."' and username='".addslashes(trim($username))."' and email='".addslashes(trim($email))."'";
			$db->setQuery($sql);
			$db->query();
			$user_id = $db->loadResult();			
		}
		
		if($user_id != ""){
			
			$query = "select student_group  from #__guru_config where id='1'";
			$db->setQuery($query);
			$student_group = $db->loadResult();
			
			if(isset($student_group) && $student_group !=2){
				$group_id = $student_group; 
			}
			else{
				$query = "select id from #__usergroups where title='Registered'";
				$db->setQuery($query);
				$group_id = $db->loadResult();
			}
			
			
			
			$query = "insert into #__user_usergroup_map(`user_id`, `group_id`) values('".$user_id."', '".$group_id."')";
			$db->setQuery($query);
			$group_id = $db->loadResult();			
		}
		return $user_id;
	}

	function existCustomer($id){
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__guru_customer where id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if($result > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	function store(){ 
		$db = JFactory::getDBO();
		$id = JRequest::getVar("id", "");		
		$company = JRequest::getVar("company", "");
		$firstname = JRequest::getVar("firstname", "");
		$lastname = JRequest::getVar("lastname", "");	
		$return = array();
		$sql = "";
		if(!$this->existCustomer($id)){	
			$action = JRequest::getVar("action", "");
			if($action != "existing"){
				$id = $this->saveJoomlaUser();
			}
			$sql = "insert into #__guru_customer(`id`, `company`, `firstname`, `lastname`) values (".intval($id).", '".$company."', '".addslashes(trim($firstname))."', '".$lastname."')";
		}
		else{	
			$sql = "update #__guru_customer set company='".$company."', firstname='".addslashes(trim($firstname))."', lastname='".$lastname."' where id=".intval($id);
		}
		$db->setQuery($sql);
		if($db->query()){
			$return["error"] = TRUE;
			$return["id"] = $id;
		}
		else{
			$return["error"] = false;
			$return["id"] = 0;
		}
		$this->updateUserActivation($id);		
		return $return;	
	}

	function remove(){
		$db = JFactory::getDBO();
		$cids = JRequest::getVar('cid', array(), 'post', 'array');
		foreach($cids as $key=>$value){
			if(trim($value) != ""){
				$sql = "SELECT count(*) from #__guru_order where userid=".intval($value);
				$db->setQuery($sql);
				$db->query();
				$result_customer = $db->loadColumn();
				if(intval($result_customer[0]) <= 0){
					$sql = "delete from #__guru_customer where id=".intval($value);
					$db->setQuery($sql);
					if(!$db->query()){
						return false;
					}
				}
				else{
					$_SESSION["cust_is_enrolled"] = "1";
					return false;
				}
			} 
		}
		return true;
	}


	function block(){
		$db = JFactory::getDBO();
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$task = JRequest::getVar('task', '', 'post');
		$item = $this->getTable('guruCustomer');
		if ($task == 'block'){
			$sql = "update #__users set block='1' where id in ('".implode("','", $cids)."')";
			$ret = -1;
		}
		else {
			$ret = 1;
			$sql = "update #__users set block='0' where id in ('".implode("','", $cids)."')";

		}
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
		return true;
	}
	
	function getNoOrders($uid){
		$db = JFactory::getDBO();		
		$sql="SELECT COUNT(*) FROM jos_guru_order as o, 
		jos_guru_customer AS c, jos_guru_program AS p 
		WHERE o.userid=c.user_id AND
		o.programid=p.id AND c.user_id ='".$uid."'";		
		$db->setQuery($sql);
		$result = $db->loadResult();
		
		return $result;
	}	
	
	function getNoTests($uid){
		$db = JFactory::getDBO();
		$sql="SELECT COUNT(*) FROM jos_guru_order as o, 
		jos_guru_customer AS c, jos_guru_program AS p 
		WHERE o.userid=c.user_id AND
		o.programid=p.id AND c.user_id ='".$uid."'";		
		$db->setQuery($sql);
		$result = $db->loadResult();
		
		return $result;
	}		
	
	function existNewCustomer($username_value){
		$db = JFactory::getDBO();
		$sql = "select a.user_id as userid from #__guru_customer a where a.user_id=(select id from #__users u where u.username='".$username_value."')";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadObject();		
		if(isset($result->userid) && $result->userid==0){
			return false;
		} 
		return $result->userid;
	}
	
	function existUser($username_value){
		$db = JFactory::getDBO();
		$sql = "select count(*) as total from #__users where username='".$username_value."'";		
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadObject();		
		if($result->total==0){
			return false;
		} 
		return true;
	}
	
	function getUserId($username){
		$db = JFactory::getDBO();
		$sql = "select id from #__users where username='".addslashes(trim($username))."'";
	
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}
	
	function getCustomerDetails($id){
		$db = JFactory::getDBO();
		$sql = "select u.username, u.email, c.firstname, c.lastname, c.company from #__users u left join #__guru_customer c on u.id=c.id where u.id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	function getStudentCourses($id){
		$db = JFactory::getDBO();
		$sql = "SELECT id ,name FROM #__guru_program where id in(SELECT distinct(course_id) from #__guru_buy_courses where userid=".intval($id).")";
		$db->setQuery($sql);
		$courses=$db->loadAssocList();
		return $courses;
	}
	
	function resetCourses(){
		$user_id = JRequest::getVar("id", "0");
		$course_id = JRequest::getVar("course", "0");
		$db = JFactory::getDBO();
		
		$sql = "delete from #__guru_mycertificates where `course_id`=".intval($course_id)." and `user_id`=".intval($user_id);
		$db->setQuery($sql);
		if(!$db->query()){
			return "0";
		}
		
		$sql = "delete from #__guru_quiz_question_taken where `user_id`=".intval($user_id);
		$db->setQuery($sql);
		if(!$db->query()){
			return "0";
		}
		
		$sql = "delete from #__guru_quiz_taken where `pid`=".intval($course_id)." and `user_id`=".intval($user_id);
		$db->setQuery($sql);
		if(!$db->query()){
			return "0";
		}
		
		$sql = "delete from #__guru_viewed_lesson where `pid`=".intval($course_id)." and `user_id`=".intval($user_id);
		$db->setQuery($sql);
		if(!$db->query()){
			return "0";
		}
		
		return $course_id;
	}
};
?>