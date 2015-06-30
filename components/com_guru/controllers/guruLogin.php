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

class guruControllerguruLogin extends guruController {
	function __construct () {
		parent::__construct();
		$this->registerTask("","view");
		$this->registerTask("register", "edit");
		$this->registerTask("saveCustomer", "save");
		$this->registerTask("saveAuthor", "saveauthor");
		$this->registerTask("authorprofile", "saveAuthoredit");
		$this->registerTask("log_in_user", "logUser");
		$this->registerTask("terms", "terms");
	}
	
	function view(){
		$returnpage = JRequest::getVar("returnpage", "");
		$Itemid = JRequest::getVar("Itemid", "0");

		if($returnpage != "" && $returnpage == "enroll"){
			$user = JFactory::getUser();
			if($user->id != "0"){
				$app = JFactory::getAPPlication("site");
				$course_id = JRequest::getVar("course_id", "0");
				$link = "index.php?option=com_guru&view=guruPrograms&task=enroll&cid=".intval($course_id)."&Itemid=".intval($Itemid);
				$app->redirect($link);
			}
		}
		
		JRequest::setVar('view', 'guruLogin');	
		parent::display();
	}
	
	 function edit(){
        $view = $this->getView("guruLogin", "html");
        $view->setLayout("editForm");
        $view->editForm();
    }
	
	function terms(){
        $view = $this->getView("guruLogin", "html");
        $view->setLayout("terms");
        $view->terms();
    }
	
	function isCustomer($user_id){
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__guru_customer where id=".intval($user_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if($result > 0){
			return true;
		}
		return false;
	}
	
	function buyCourses($user_id){
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__guru_order where userid=".intval($user_id);
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
	
	function logUser(){
		//global $mainframe;
		$app = JFactory::getApplication();
        //global $Itemid;
		$Itemid = JRequest::getVar("Itemid", "0");
		$return_page = JRequest::getVar("returnpage", "mycourses");
		$course_id = JRequest::getVar("cid","0");
		if ($return = JRequest::getVar('return', '', 'request', 'base64')) {
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
		
		$link = "";
		if($return_page == "checkout"){
			$user = JFactory::getUser();
			$user_id = $user->id;
			if(!$this->isCustomer($user_id)){
				$link = "index.php?option=com_guru&view=guruBuy&task=checkout&from=login&returnpage=checkout";				
			}
			else{
				$link = "index.php?option=com_guru&view=guruBuy";
			}
		}
		elseif($return_page == "myorders"){
			$link = "index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=".intval($Itemid);
		}
		elseif($return_page == "myquizandfexam"){
			$link = "index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=".intval($Itemid);
		}
		elseif($return_page == "mycourses"){
			$link = "index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=".intval($Itemid);
		}
		elseif($return_page == "mycertificates"){
			$link = "index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=".intval($Itemid);
		}
		elseif($return_page == "enroll"){
			$link = "index.php?option=com_guru&view=guruPrograms&task=enroll&cid=".$course_id."&Itemid=".intval($Itemid);
		}
		elseif($return_page == "authorprofile"){
			$link = "index.php?option=com_guru&view=guruauthor&task=authorprofile&Itemid=".intval($Itemid);
		}
		elseif($return_page == "authormycourses"){
			$link = "index.php?option=com_guru&view=guruauthor&task=authormycourses&Itemid=".intval($Itemid);
		}
		elseif($return_page == "authormymediacategories"){
			$link = "index.php?option=com_guru&view=guruauthor&task=authormymediacategories&Itemid=".intval($Itemid);
		}
		elseif($return_page == "authormymedia"){
			$link = "index.php?option=com_guru&view=guruauthor&task=authormymedia&Itemid=".intval($Itemid);
		}
		elseif($return_page == "authorquizzes"){
			$link = "index.php?option=com_guru&view=guruauthor&task=authorquizzes&Itemid=".intval($Itemid);
		}
		elseif($return_page == "authorcommissions"){
			$link = "index.php?option=com_guru&view=guruauthor&task=authorcommissions&Itemid=".intval($Itemid);
		}
		elseif($return_page == "mystudents" || $return_page == "studentquizes" ||  $return_page == "studentdetails" || $return_page == "quizdetails"){
			$link = "index.php?option=com_guru&view=guruauthor&task=mystudents&Itemid=".intval($Itemid);
		}
		elseif($return_page == "registerforlogout"){
			$view_get = JRequest::getVar("view");
			$email_r = JRequest::getVar("e");
			$catid = JRequest::getVar("catid");
			$module_lesson = JRequest::getVar("module");
			$lesson_id = JRequest::getVar("cid");
			if(($view_get == "guruTasks" || $view_get == "gurutasks") && $email_r == "1"){
				$link = "index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".$module_lesson."&cid=".$lesson_id;
			}
			else{
				$link = "index.php?option=com_guru";
			}
		}
		
		$err = $app->login($credentials, $options);
		$this->setRedirect(JRoute::_($link, false));
	}
	
	 function save(){
		$Itemid = JRequest::getVar('Itemid', 0);
		$Itemid = $_POST['Itemid'];
		$course_id = intval(JRequest::getVar("course_id", ""));
		$model = $this->getModel("guruLogin");
		$return_page = JRequest::getVar("returnpage", "");
		$validate = $model->isNewUser();
		if($validate === FALSE){
			$msg = JText::_('GURU_USERNAME_EMAIL_UNIQUE');
			$return = JRequest::getVar("returnpage", "", "request");
			$link = "index.php?option=com_guru&view=guruLogin&returnpage=".$return."&task=register&Itemid=".$Itemid."&cid=".$course_id;
			$this->setRedirect($link, $msg);
			return false;			
		}
		
		
		if($model->store()){
			global $app;
			$options = array();
			$options['remember'] = JRequest::getBool('remember', false);
			
			$username = JRequest::getVar("username", "");
			$password = JRequest::getVar("password", "");
	
			$credentials = array();
			$credentials['username'] = trim($username);
			$credentials['password'] = trim($password);
			$credentials['email'] = JRequest::getVar("email", '');
			
            unset($_SESSION["username"]);
			unset($_SESSION["email"]);
			unset($_SESSION["firstname"]);
			unset($_SESSION["lastname"]);
			unset($_SESSION["company"]);
			$registered_user = JRequest::getVar("registered_user","");
			$err = $app->login($credentials, $options);
			
			if(intval($return_page) != "0"){
				$return_page = "guruprograms";
			}
			
			if($return_page == "checkout"){
				$user = JFactory::getUser();
				$user_id = $user->id;
				//$user_id = $ress["1"];
			
				if(!$this->buyCourses($user_id)){
					$link = "index.php?option=com_guru&view=guruBuy&task=checkout&from=login&Itemid=".$Itemid;
				}
				else{
					$link = "index.php?option=com_guru&view=guruBuy&Itemid=".$Itemid;
				}
			}
			elseif($return_page == "mycourses"){
				$link = "index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=".$Itemid;
			}
			elseif($return_page == "myorders"){
				$link = "index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=".$Itemid;
			}
			elseif($return_page == "mycertificates"){
				$link = "index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=".$Itemid;
			}
			elseif($return_page == "enroll"){
				$link = "index.php?option=com_guru&view=guruPrograms&task=enroll&cid=".$course_id."&Itemid=".$Itemid."&registered_user=".$registered_user;
			}
			elseif($return_page == "authorprofile"){
				$link = "index.php?option=com_guru&view=guruauthor&task=authorprofile&Itemid=".intval($Itemid);
			}
			elseif($return_page == "registerforlogout"){
				$view_get = JRequest::getVar("view");
				$email_r = JRequest::getVar("e");
				$catid = JRequest::getVar("catid");
				$module_lesson = JRequest::getVar("module");
				$lesson_id = JRequest::getVar("cid");
				if(($view_get == "guruTasks" || $view_get == "gurutasks") && $email_r == "1"){
					$link = "index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".$module_lesson."&cid=".$lesson_id;
				}
				else{
					$link = "index.php?option=com_guru";
				}
			}
			elseif($return_page == "guruprograms"){
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
			}
			$msg = JText::_('DSCUSTOMERSAVED');
			$this->setRedirect(JRoute::_($link, false), $msg);
        } 
		else{
            $msg = JText::_('DSCUSTOMERSAVEERR');
            $return = JRequest::getVar("returnpage", "", "request");
            $link = "index.php?option=com_guru&view=guruLogin&returnpage=checkout&Itemid=".$Itemid;
			$this->setRedirect(JRoute::_($link), $msg, "notice");
        }        
    }
	
	function saveauthor(){
		$Itemid = JRequest::getVar('Itemid', 0);
		$Itemid = $_POST['Itemid'];
		$model = $this->getModel("guruLogin");
		$return_page = JRequest::getVar("returnpage", "");
		$db = JFactory::getDBO();
		$sql = "select * from #__guru_config";
		$db->setQuery($sql);
		$db->query();
		$configs = $db->loadAssocList();
		
		$allow_teacher_action = json_decode($configs["0"]["st_authorpage"]);//take all the allowed action from administator settings
		$teacher_aprove = @$allow_teacher_action->teacher_aprove; //allow or not aprove teacher
        
		$validate = $model->isNewUser();
		if($validate === FALSE){
			$msg = JText::_('GURU_USERNAME_EMAIL_UNIQUE');
			$return = JRequest::getVar("returnpage", "", "request");
			$link = "index.php?option=com_guru&view=guruLogin&returnpage=".$return."&Itemid=".$Itemid;
			$this->setRedirect(JRoute::_($link, false), $msg);
			return false;			
		}
		
		$return = $model->store();
		$msg = "";
		$link = "";
		$notice = "";
		
		if($return["0"]){
            unset($_SESSION["username"]);
			unset($_SESSION["email"]);
			$msg = "";
			if($teacher_aprove == 1){ // NO
				$msg = JText::_('GURU_TEACHER_SAVED_PENDING');
			}
			else{
				$msg = JText::_('GURU_TEACHER_SAVED_ACTIVATED');
			}
			$link = "index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses&Itemid=".$Itemid;
			$notice = "Success";
			//$this->setRedirect(JRoute::_($link, false), $msg, "Success");
        } 
		else{
            $msg = JText::_('DSCUSTOMERSAVEERR');
            $return = JRequest::getVar("returnpage", "", "request");
            $link = "index.php?option=com_guru&view=guruLogin&returnpage=".$return."&Itemid=".$Itemid;
			$notice = "notice";
			//$this->setRedirect(JRoute::_($link, false), $msg, "notice");
        }
		
		//we need to login, first we check if we already are logged in
		$userr = JFactory::getUser();
		$logged_userid = $userr->get('id');
		
		if(isset($logged_userid) && $logged_userid == 0){
			if($teacher_aprove == 1){ // NO
				$user_status = $this->sendJoomlaEmail($return["1"]);
				if(intval($user_status) != 0){
					global $app;
					$options = array();
					$options['remember'] = JRequest::getBool('remember', false);
					
					$username = JRequest::getVar("username", "");
					$password = JRequest::getVar("password", "");
			
					$credentials = array();
					$credentials['username'] = trim($username);
					$credentials['password'] = trim($password);
					$credentials['email'] = JRequest::getVar("email", '');
					
					if($return["1"]->enabled == 1){
						$err = $app->login($credentials, $options);
					}
				}
			}
		}
		
		if($teacher_aprove == 0){ // YES
			global $app;
			$options = array();
			$options['remember'] = JRequest::getBool('remember', false);
			
			$username = JRequest::getVar("username", "");
			$password = JRequest::getVar("password", "");
	
			$credentials = array();
			$credentials['username'] = trim($username);
			$credentials['password'] = trim($password);
			$credentials['email'] = JRequest::getVar("email", '');
			
			if($return["1"]->enabled == 1){
				$err = $app->login($credentials, $options);
			}
		}
		
		$this->setRedirect(JRoute::_($link), $msg, $notice);
    }
	
	function sendJoomlaEmail($data){
		$lang = JFactory::getLanguage();
		$extension = 'com_users';
		$base_dir = JPATH_SITE;
		$language_tag = 'en-GB';
		$lang->load($extension, $base_dir, $language_tag, true);
		
		$data = (array)$data;
		$user = $data;
		$params = JComponentHelper::getParams('com_users');
		// Prepare the data for the user object.
		//$data['email'] = JStringPunycode::emailToPunycode($data['email1']);
		$data['password'] = $data['password1'];
		$useractivation = $params->get('useractivation');
		$sendpassword = $params->get('sendpassword', 1);
		
		$config = JFactory::getConfig();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		// Compile the notification mail values.
		$data['fromname'] = $config->get('fromname');
		$data['mailfrom'] = $config->get('mailfrom');
		$data['sitename'] = $config->get('sitename');
		$data['siteurl'] = JUri::root();
		
		// Handle account activation/confirmation emails.
		if ($useractivation == 2)
		{
			// Set the link to confirm the user email.
			$uri = JUri::getInstance();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base . JRoute::_('index.php?option=com_users&task=registration.activate&token=' . $data['activation']."&g=1", false);

			$emailSubject = JText::sprintf(
				'COM_USERS_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);
			
			if ($sendpassword)
			{
				$emailBody = JText::sprintf(
					'COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY',
					$data['name'],
					$data['sitename'],
					$data['activate'],
					$data['siteurl'],
					$data['username'],
					$data['password_clear']
				);
			}
			else
			{
				$emailBody = JText::sprintf(
					'COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY_NOPW',
					$data['name'],
					$data['sitename'],
					$data['activate'],
					$data['siteurl'],
					$data['username']
				);
			}
			
			$emailBody = str_replace("\n\r", "<br/>", $emailBody);
			$emailBody = str_replace("\n", "<br/>", $emailBody);
			$emailBody = str_replace("\r", "<br/>", $emailBody);
		}
		elseif ($useractivation == 1)
		{
			// Set the link to activate the user account.
			$uri = JUri::getInstance();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base . JRoute::_('index.php?option=com_users&task=registration.activate&token=' . $data['activation']."&g=1", false);

			$emailSubject = JText::sprintf(
				'COM_USERS_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);

			if ($sendpassword)
			{
				$emailBody = JText::sprintf(
					'COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY',
					$data['name'],
					$data['sitename'],
					$data['activate'],
					$data['siteurl'],
					$data['username'],
					$data['password_clear']
				);
			}
			else
			{
				$emailBody = JText::sprintf(
					'COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY_NOPW',
					$data['name'],
					$data['sitename'],
					$data['activate'],
					$data['siteurl'],
					$data['username']
				);
			}
			
			$emailBody = str_replace("\n\r", "<br/>", $emailBody);
			$emailBody = str_replace("\n", "<br/>", $emailBody);
			$emailBody = str_replace("\r", "<br/>", $emailBody);

		}
		else{
			$db = JFactory::getDBO();
			$sql = "select `template_emails` from #__guru_config";
			$db->setQuery($sql);
			$db->query();
			$configs = $db->loadAssocList();
			$template_emails = $configs["0"]["template_emails"];
			$template_emails = json_decode($template_emails, true);
			
			$pending_teacher_subject = $template_emails["pending_teacher_subject"];
			$pending_teacher_body = $template_emails["pending_teacher_body"];
			
			$name = JRequest::getVar("name", "");
			$app = JFactory::getApplication();
			$site_name = $app->getCfg('sitename');
			$username = JRequest::getVar("username", "");
			$password = JRequest::getVar("password", "");
			
			$pending_teacher_subject = str_replace("[AUTHOR_NAME]", $name, $pending_teacher_subject);
			$pending_teacher_subject = str_replace("[SITE_NAME]", $site_name, $pending_teacher_subject);
			$pending_teacher_subject = str_replace("[USERNAME]", $username, $pending_teacher_subject);
			$pending_teacher_subject = str_replace("[PASSWORD]", $password, $pending_teacher_subject);
			
			$pending_teacher_body = str_replace("[AUTHOR_NAME]", $name, $pending_teacher_body);
			$pending_teacher_body = str_replace("[SITE_NAME]", $site_name, $pending_teacher_body);
			$pending_teacher_body = str_replace("[USERNAME]", $username, $pending_teacher_body);
			$pending_teacher_body = str_replace("[PASSWORD]", $password, $pending_teacher_body); 

			$emailSubject = $pending_teacher_subject;
			$emailBody = $pending_teacher_body;
		}

		// Send the registration email.
		$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody, true);

		// Send Notification mail to administrators
		if (($params->get('useractivation') < 2) && ($params->get('mail_to_admin') == 1))
		{
			$emailSubject = JText::sprintf(
				'COM_USERS_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);

			$emailBodyAdmin = JText::sprintf(
				'COM_USERS_EMAIL_REGISTERED_NOTIFICATION_TO_ADMIN_BODY',
				$data['name'],
				$data['username'],
				$data['siteurl']
			);

			// Get all admin users
			$query->clear()
				->select($db->quoteName(array('name', 'email', 'sendEmail')))
				->from($db->quoteName('#__users'))
				->where($db->quoteName('sendEmail') . ' = ' . 1);

			$db->setQuery($query);

			try
			{
				$rows = $db->loadObjectList();
			}
			catch (RuntimeException $e)
			{
				$this->setError(JText::sprintf('COM_USERS_DATABASE_ERROR', $e->getMessage()), 500);
				return false;
			}

			// Send mail to all superadministrators id
			foreach ($rows as $row)
			{
				$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $row->email, $emailSubject, $emailBodyAdmin, 1);

				// Check for an error.
				if ($return !== true)
				{
					$this->setError(JText::_('COM_USERS_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'));
					return false;
				}
			}
		}

		// Check for an error.
		if ($return !== true)
		{
			$this->setError(JText::_('COM_USERS_REGISTRATION_SEND_MAIL_FAILED'));

			// Send a system message to administrators receiving system mails
			$db = JFactory::getDbo();
			$query->clear()
				->select($db->quoteName(array('name', 'email', 'sendEmail', 'id')))
				->from($db->quoteName('#__users'))
				->where($db->quoteName('block') . ' = ' . (int) 0)
				->where($db->quoteName('sendEmail') . ' = ' . (int) 1);
			$db->setQuery($query);

			try
			{
				$sendEmail = $db->loadColumn();
			}
			catch (RuntimeException $e)
			{
				$this->setError(JText::sprintf('COM_USERS_DATABASE_ERROR', $e->getMessage()), 500);
				return false;
			}

			if (count($sendEmail) > 0)
			{
				$jdate = new JDate;

				// Build the query to add the messages
				foreach ($sendEmail as $userid)
				{
					$values = array($db->quote($userid), $db->quote($userid), $db->quote($jdate->toSql()), $db->quote(JText::_('COM_USERS_MAIL_SEND_FAILURE_SUBJECT')), $db->quote(JText::sprintf('COM_USERS_MAIL_SEND_FAILURE_BODY', $return, $data['username'])));
					$query->clear()
						->insert($db->quoteName('#__messages'))
						->columns($db->quoteName(array('user_id_from', 'user_id_to', 'date_time', 'subject', 'message')))
						->values(implode(',', $values));
					$db->setQuery($query);

					try
					{
						$db->execute();
					}
					catch (RuntimeException $e)
					{
						$this->setError(JText::sprintf('COM_USERS_DATABASE_ERROR', $e->getMessage()), 500);
						return false;
					}
				}
			}
			return false;
		}

		if ($useractivation == 1)
		{
			return "useractivate";
		}
		elseif ($useractivation == 2)
		{
			return "adminactivate";
		}
		else
		{
			return $user["id"];
		}
	}
	
	function saveAuthoredit(){
		$id = JRequest::getVar("auid", "0");
		$model = $this->getModel("guruLogin");
		$ress = $model->update($id);
		if($ress){
			$msg = JText::_('GURU_AU_AUTHOR_DETAILS_SAVED');
			$link = "index.php?option=com_guru&view=guruauthor&layout=authorprofile&Itemid=".$Itemid;
			$this->setRedirect(JRoute::_($link, false), $msg, "Success");		
		}
	}

};

?>