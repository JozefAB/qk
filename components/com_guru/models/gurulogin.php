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
class guruModelguruLogin extends JModelLegacy {
	
	function __construct(){
		parent::__construct();
	}
	
	function isNewUser(){
		$username = JRequest::getVar("username", "");
		$email = JRequest::getVar("email", "");
		$firstname = JRequest::getVar("firstname", "");
		$lastname = JRequest::getVar("lastname", "");
    	$company = JRequest::getVar("company", "");

		$_SESSION["username"] = $username;
		$_SESSION["email"] = $email;
		$_SESSION["firstname"] = $firstname;
		$_SESSION["lastname"] = $lastname;
		$_SESSION["company"] = $company;
		
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__users where username='".trim(addslashes($username))."'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		
		if($result != "0"){
			return false;
		}
		
		$sql = "select count(*) from #__users where email='".trim(addslashes($email))."'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if($result != "0"){
			return false;
		}
		
		return true;
	}
	
	function store(){
		jimport("joomla.database.table.user");
		$db = JFactory::getDBO();
		$my = JFactory::getUser();
		$course_id = JRequest::getVar("course_id","0");

		$sql = "select * from #__guru_config";
		$db->setQuery($sql);
		$db->query();
		$configs = $db->loadAssocList();
		
		$allow_teacher_action = json_decode($configs["0"]["st_authorpage"]);//take all the allowed action from administator settings
		
		$teacher_aprove = @$allow_teacher_action->teacher_aprove; //allow or not aprove teacher
		$params = JComponentHelper::getParams('com_users');
		
		$nowDate = JFactory::getDate();
		$nowDate = $nowDate->toSql();
		$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		$authKey = md5(uniqid(rand(), true));
		
		
		if(!$my->id){
			$new_user = 1;
		}
		else{
			$new_user = 0;
		}	
		
		$table = $this->getTable('guruCustomer');
		$data = JRequest::get('post');
		$data['password2'] = $data['password_confirm'];
		if($data['guru_teacher'] == 1){
			$data['name'] = $data['firstname']." ".$data['lastname'];
		}
		$data["enabled"] = 1;
		$res = true;
		$reg = JSession::getInstance("none", array());
		$user = new JUser();
		$useractivation = $params->get('useractivation');
		
		if(!$my->id){
			if($data['guru_teacher'] == 1){
				$user->bind($data);
				if(!$user->save()) {
					$reg->set("tmp_profile", $data);
					$error = $user->getError();
					$res = false;
				}
				if(isset($user->id)){
					$this->addToGroup($user->id);	
				}
			}
			if($data['guru_teacher'] == 2){
				$session = JFactory::getSession();
				$token = $session->getToken();
					
				if($teacher_aprove == 1){
					$data["enabled"] = 2;
					if($useractivation == 1 || $useractivation == 2){
						$data["block"] = 1;
						$data["activation"] = $token;
					}
					else{
						$data["block"] = 0;
						$data["activation"] = "";
					}
				}
				elseif($teacher_aprove == 0){
					$data["block"] = 0;
					$data["activation"] = "";
				}
				$user->bind($data);
				if(!$user->save()) {
					$reg->set("tmp_profile", $data);
					$error = $user->getError();
					$res = false;
				}
				
				if(isset($user->id)){
					$this->addToGroup($user->id);	
				}
			}
		}
		else{
			$user->id = $my->id;
		}
		
		if($data['guru_teacher'] == 1){
			if (!$user->id) {
				return false;
			}	
		}

		if($data['guru_teacher'] == 1){
			$data['id'] = $user->id;	
			if(!$this->existCustomer($data['id'])){
				$sql = "insert into #__guru_customer(`id`, `company`, `firstname`, `lastname`) values (".$data['id'].", '".addslashes(trim($data['company']))."', '".addslashes(trim($data['firstname']))."', '".addslashes(trim($data['lastname']))."')";
				$db->setQuery($sql);
				if(!$db->query()){
					$res = false;
				}
			}
		}
		
		if($data['guru_teacher'] == 2){
			$sql = "select `id` from #__guru_commissions where `default_commission`=1";
			$db->setQuery($sql);
			$db->query();
			$id_commission = $db->loadColumn();
			$id_commission = @$id_commission["0"];
		
			$data['id'] = $user->id;	
			$data["full_bio"] = JRequest::getVar("full_bio","","post","string",JREQUEST_ALLOWRAW);
			if(!$this->existAuthor($data['id'])){
				$sql = "INSERT INTO `#__guru_authors` (`userid`, `gid`, `full_bio`, `images`, `emaillink`, `website`, `blog`, `facebook`, `twitter`, `show_email`, `show_website`, `show_blog`, `show_facebook`, `show_twitter`, `author_title`, `ordering`, `forum_kunena_generated`,`enabled`, `commission_id`) VALUES('".intval($data['id'])."', 2, '".$data["full_bio"]."','".$data["images"]."', '".$data["emaillink"]."', '".$data["website"]."', '".$data["blog"]."', '".$data["facebook"]."', '".$data["twitter"]."', '".$data["show_email"]."', '".$data["show_website"]."', '".$data["show_blog"]."', '".$data["show_facebook"]."', '".$data["show_twitter"]."',  '".$data["author_title"]."', '".$data["ordering"]."', '', '".$data["enabled"]."', '".$id_commission."' )";
				$db->setQuery($sql);
				if(!$db->query()){
					$res = false;
				}
				
				if($teacher_aprove == 0){ // YES
					$sql = "select `template_emails`, `fromname`, `fromemail`, `admin_email` from #__guru_config";
					$db->setQuery($sql);
					$db->query();
					$confic = $db->loadAssocList();
					$template_emails = $confic["0"]["template_emails"];
					$template_emails = json_decode($template_emails, true);
					$fromname = $confic["0"]["fromname"];
					$fromemail = $confic["0"]["fromemail"];
					
					
					$sql = "select u.`email` from #__users u, #__user_usergroup_map ugm where u.`id`=ugm.`user_id` and ugm.`group_id`='8' and u.`id` IN (".$confic["0"]["admin_email"].")";
					$db->setQuery($sql);
					$db->query();
					$email = $db->loadColumn();
					
					$app = JFactory::getApplication();
					$site_name = $app->getCfg('sitename'); 
					
					$subject = $template_emails["new_teacher_subject"];
					$body = $template_emails["new_teacher_body"];
					
					$subject = str_replace("[AUTHOR_NAME]", $user->name, $subject);
					
					$body = str_replace("[AUTHOR_NAME]", $user->name, $body);
			
					for($i=0; $i< count($email); $i++){
						JFactory::getMailer()->sendMail($fromemail, $fromname, $email[$i], $subject, $body, 1);
					}
				}
			}
		}
		//global $mainframe;
		$app = JFactory::getApplication();
		
		if($return = JRequest::getVar('return', '', 'method', 'base64')) {
			$return = base64_decode($return);
		}

		if($res){
			$reg->clear("tmp_profile");
		}
		
		return array("0"=>$res, "1"=>$user);
	}
	
	function update($id){
		$db = JFactory::getDBO();
		$data = JRequest::get('post');
		$data["full_bio"] = JRequest::getVar("full_bio","","post","string",JREQUEST_ALLOWRAW);
		
		$sql1 = "UPDATE `#__users` set `name`= '".$data["name"]."' WHERE id=".intval($id);
		$db->setQuery($sql1);
		$db->query();
		
		$sql = "UPDATE `#__guru_authors` set `full_bio`= '".addslashes($data["full_bio"])."', `images`= '".$data["images"]."', `emaillink`='".$data["emaillink"]."', `website`='".$data["website"]."', `blog`='".$data["blog"]."', `facebook`='".$data["facebook"]."', `twitter`='".$data["twitter"]."', `show_email`= '".$data["show_email"]."', `show_website`='".$data["show_website"]."' , `show_blog`='".$data["show_blog"]."', `show_facebook`='".$data["show_facebook"]."', `show_twitter`='".$data["show_twitter"]."', `author_title`='".$data["author_title"]."', `ordering`= '".$data["ordering"]."' WHERE userid=".intval($id);
		$db->setQuery($sql);
		if(!$db->query()){
			$res = false;
		}
		else{
			$res = true;
		}
		return $res;
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
	function existAuthor($id){
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__guru_authors where userid=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		if($result[0] > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	function addToGroup($user_id){
		$db = JFactory::getDBO();
		$group_id = "";
		
		$studentpage = JRequest::getVar("studentpage", "");
		
		if($studentpage == "studentpage"){
			$sql = "select `student_group` from #__guru_config where id=1";
			$db->setQuery($sql);
			$db->query();
			$group_id = $db->loadResult();
		}
		else{
			$sql = "select `st_authorpage` from #__guru_config where id=1";
			$db->setQuery($sql);
			$db->query();
			$st_authorpage = $db->loadColumn();
			$st_authorpage = json_decode($st_authorpage["0"], true);
			$group_id = $st_authorpage["teacher_group"];
		}
		
		$sql = "insert into #__user_usergroup_map(`user_id`, `group_id`) values('".$user_id."', '".$group_id."')";
		$db->setQuery($sql);
		$db->query();
		
	}
	function getConfigs(){
		$db = JFactory::getDBO();
		$sql = "select * from #__guru_config";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
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
	
};
?>