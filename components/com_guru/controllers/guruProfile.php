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

class guruControllerguruProfile extends guruController {
	var $model = null;
	
	function __construct(){
		parent::__construct();
		$this->registerTask ("add", "edit");
		$this->registerTask ("", "edit");
		$this->registerTask ("edit", "edit");
		$this->registerTask ("register", "edit");
		$this->registerTask ("saveCustomer", "save");
		$this->registerTask ("loginform", "loginform");
		$this->_model = $this->getModel('guruCustomer');
	}

	function edit(){
		$view = $this->getView("guruProfile", "html");
		$view->setLayout("editForm");
		$view->editForm();
	}
	
	function buy () {
		$view = $this->getView("guruProfile", "html");
		$view->setLayout("buy");
		$view->buy();
	}	

	function login(){
		$view = $this->getView("guruProfile", "html");
		$view->setLayout("editform");
		$view->login();
	}
	
	function loginform(){
		$view = $this->getView("guruProfile", "html");
		$view->setLayout("loginform");
		$view->loginform();
	}

	function logCustomerIn(){
		//global $mainframe;
		$app = JFactory::getApplication();
		if($return = JRequest::getVar('return', '', 'request', 'base64')){
			$return = base64_decode($return);
		}
		$options = array();
		$options['remember'] = JRequest::getBool('remember', false);
		$options['return'] = $return;
		$username = JRequest::getVar("username", "", 'request');
		$password = JRequest::getVar("passwd", "", 'request');
		$credentials = array();
		$credentials['username'] = $username;
		$credentials['password'] = $password;
		$err = $app->login($credentials, $options);
		$graybox = JRequest::getVar("graybox", "");
		$course_id = intval(JRequest::getVar("course_id", ""));
		if($graybox == "true" || $graybox == "1"){
			if(isset($err) && $err === FALSE){
				$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruProfile&task=loginform&course_id=".intval($course_id)."-".$alias.$action."&returnpage=guruprograms&graybox=true&tmpl=component"), JText::_("GURU_LOGIN_FAILED"), "notice");
				return true;
			}
			else{
				$db = JFactory::getDBO();
				$user = JFactory::getUser();
				$user_id = $user->id;
				$courses = intval($course_id)."-0.0-1";
				$amount = 0;
				$buy_date = date("Y-m-d H:i:s");
				$plan_id = "1";
				$order_expiration = "0000-00-00 00:00:00";
				$jnow = JFactory::getDate();
				$current_date_string = $jnow->toSQL();
				
				$sql = "select count(*) from #__guru_buy_courses where `userid`=".intval($user_id)." and `course_id`=".intval($course_id)." and `order_id`='0' and expired_date < '".$current_date_string."'";
				$db->setQuery($sql);
				$db->query();
				$result = $db->loadResult();
				if($result == 0){// add a new license
					$sql = "insert into #__guru_buy_courses (`userid`, `order_id`, `course_id`, `price`, `buy_date`, `expired_date`, `plan_id`, `email_send`) values (".$user_id.", 0 , ".$course_id.", '".$amount."', '".$buy_date."', '".$order_expiration."', '".$plan_id."', 0)";
					$db->setQuery($sql);
					$db->query();	
					
					$sql = "select currency from #__guru_config where id=1" ;
					$db->setQuery($sql);
					$db->query();
					$currency = $db->loadResult();
		
					$sql = "insert into #__guru_order (`id`, `userid`, `order_date`, `courses`, `status`, `amount`, `amount_paid`, `processor`, `number_of_licenses`, `currency`, `promocodeid`, `published`, `form`) values (0, ".intval($user_id).", '".$buy_date."', '".intval($course_id)."-0-1', 'Paid', '0', '-1','paypaypal','0','".$currency."','0','1', '')";
					$db->setQuery($sql);
					$db->query();
					
					$msg = JText::_("GURU_ENROLL_SUCCESSFULLY");			
				}
				else{
					$msg = JText::_("GURU_ALREADY_ENROLLED");
				}
				$_SESSION["joomlamessage"] = $msg;
								
				echo '<script type="text/javascript">';
				echo 'window.parent.location.reload(true);';
				echo '</script>';	
				die();
				return true;
			}
		}
		$link = 'index.php?option=com_guru&view=guruPrograms&task=myprograms';
		$this->setRedirect(JRoute::_($link), $msg);
	}

	function registerCustomer(){
		jimport("joomla.database.table.user");
		$db = JFactory::getDBO();
		$user = new JUser();
		$currentuser = new JUser();
		$res = true;
		$item = $this->getTable('guruCustomer');
		$data = JRequest::get('post');
		$iduser = intval($data['user_id']);
		
		
		$sql = "select student_group from #__guru_config where id=1" ;
		$db->setQuery($sql);
		$db->query();
		$student_group = $db->loadResult();
		
		$sql = "select title from #__usergroups where id='".$student_group."'" ;
		$db->setQuery($sql);
		$db->query();
		$title = $db->loadResult();
		
		
		//update user
		if ($iduser!=0)	$currentuser->load($iduser);
		$oldpass = $currentuser->password;
		$user->bind($data);
		if (isset($data['password']) && $data['password']!="") $currentuser->password=$user->password;
		//update user
		
		if (!isset($user->registerDate)) $user->registerDate = date( 'Y-m-d H:i:s' );
		if (!isset($user->block)) $user->block = 0;
		$user->usertype = ''.$title.'';
		$sqls = "SELECT `id` FROM #__core_acl_aro_groups WHERE name='".$title."'";
		$db->setQuery($sqls);
		$reggroup = $db->loadResult();		
		$user->gid = $reggroup;
		//var_dump($data);die();
		if ($currentuser->id>0) {
			$currentuser->bind($data); 
			if (strlen($_POST['password']) < 5) $currentuser->password=$oldpass;
			$currentuser->id = $iduser;
			$currentuser->name = $data['fullname'];
			if (!$currentuser->save()) {
				$error = $user->getError();
				echo $error;
				$res = false;
			}
		} else {
			if (!$user->save()) {
				$error = $user->getError();
				echo $error;
				$res = false;
			}
		}
		if ($res) {	
			$user->id = mysql_insert_id(); 
			if ($data['user_id']==0) {
				$ask = "SELECT `id` FROM `#__users` ORDER BY `id` DESC LIMIT 1 ";
				$db->setQuery( $ask );
				$where = $db->loadResult();
				$data['user_id'] = $where;
			}			
			if (!isset($data['fullname'])) $data['fullname'] = $_POST['fullname'];			
			
			if (!$item->bind($data)){
			 	$res = false;
			}
			if (!$item->check()) {
				$res = false;
			}
			if (!$item->store()) {
				$res = false;
			}
		}		
				
		return $res;

	}	

	function save(){
		$link = $this->getLink();
		$model = $this->getModel("guruProfile");
		if($model->store()){
			$msg = JText::_('DSCUSTOMERSAVED');
		}
		else{
			$msg = JText::_('DSCUSTOMERSAVEERR');
			$link = "index.php?option=com_guru&view=guruProfile&task=edit";
		}
		//REMOVED BY JOSEPH 01/04/2015
		//$this->setRedirect(JRoute::_($link), $msg);
		//ADDED BY JOSEPH 01/04/2015
		$this->setRedirect($link, $msg);
		//END

	}


	function getLink() {
		$return = JRequest::getVar("returnpage", "");
		$itemid = JRequest::getVar("Itemid", "0");

		switch ($return) {
			case "myorders":
			       	$link = "index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=".intval($itemid);
				break;
			case "checkout":
			       	$link = "index.php?option=com_guru&view=guruBuy&task=checkout&from=profile&Itemid=".intval($itemid);
				break;	
			default:
			       	$link = "index.php?option=com_guru&view=guruProfile&task=edit&Itemid=".intval($itemid);
				break;
		}
		return $link;
	}
	
};

?>