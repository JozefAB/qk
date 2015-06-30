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

class guruControllerguruAuthor extends guruController {
	var $_model = null;
	
	function __construct () {
		parent::__construct();
		$this->registerTask ("", "getAuthorList");
		$this->registerTask ("author", "getAuthor");
		$this->registerTask("authorprofile", "authorProfile");
		$this->registerTask("authormycourses", "authorMycourses");
		$this->registerTask("authormymedia", "authorMymedia");
		$this->registerTask("authorcommissions", "authorCommissions");
		$this->registerTask("treeCourse", "authorTreeCourses");
		$this->registerTask("mystudents", "myStudents");
		$this->registerTask("authorregister", "authorRegister");
		$this->registerTask("authorquizzes", "authorquizzes");
		$this->registerTask("removeCourse", "removeCourses");
		$this->registerTask("removeMedia", "removeMedia");
		$this->registerTask("addCourse", "addCourse");
		$this->registerTask("newStudent", "newStudent");
		$this->registerTask("duplicateCourse", "duplicateCourse");
		$this->registerTask("unpublishCourse", "unpublishCourse");
		$this->registerTask("publishCourse", "publishCourse");
		$this->registerTask("unpublishMedia", "unpublishMedia");
		$this->registerTask("publishMedia", "publishMedia");
		$this->registerTask("newmodule", "newModule");
		$this->registerTask("save_new_module", "saveNewModule");
		$this->registerTask("save_module", "saveModule");
		$this->registerTask("edit", "edit");
		$this->registerTask("editsbox", "editsbox");
		$this->registerTask("preview", "preview");
		$this->registerTask("duplicateMedia", "duplicateMedia");
		$this->registerTask("editMedia", "editMedia");
		$this->registerTask("authormymediacategories", "authormymediacategories");
		$this->registerTask("authoraddeditmediacat", "authoraddeditmediacat");
		$this->registerTask("unpublishMediaCat", "unpublishMediaCat");
		$this->registerTask("publishMediaCat", "publishMediaCat");
		$this->registerTask("removeMediaCat", "removeMediaCat");
		$this->registerTask("apply_media", "applyMedia");
		$this->registerTask("save_media", "saveMedia");
		$this->registerTask("savesbox", "savesbox");
		$this->registerTask("applymediacat", "applyMediaCat");
		$this->registerTask("savemediacat", "saveMediaCat");
		$this->registerTask("duplicateMediaCat", "duplicateMediaCat");
		$this->registerTask("saveLesson", "saveLesson");
		$this->registerTask("applyLesson", "applyLesson");
		$this->registerTask("editQuiz", "editQuiz");
		$this->registerTask("save_quiz", "saveQuiz");
		$this->registerTask("addquestion", "addQuestion");
		$this->registerTask("savequestion", "saveQuestion");
		$this->registerTask("editquestion", "editQuestion");
		$this->registerTask("jumpbts_save", "saveJump");
		$this->registerTask("publish_quiz", "publishQuiz");
		$this->registerTask("unpublish_quiz", "unpublishQuiz");
		$this->registerTask("editsboxx", "editsboxx");
		$this->registerTask("removeQuiz", "removeQuiz");
		$this->registerTask("course_stats", "courseStats");
		$this->registerTask("quizz_stats", "quizzStats");
		$this->registerTask("duplicateQuiz", "duplicateQuiz");
		$this->registerTask("editQuizFE", "editQuizFE");
		$this->registerTask("apply_quizFE", "applyQuizFE");
		$this->registerTask("save_quizFE", "saveQuizFE");
		$this->registerTask("addquizzes", "addQuizzes");
		$this->registerTask("save", "save");
		$this->registerTask("addexercise", "addexercise");
		$this->registerTask ("saveOrderQuestions", "saveOrderQuestions");
		$this->registerTask ("saveOrderExercices", "saveOrderExercices");
		$this->registerTask ("studentdetails", "studentdetails");
		$this->registerTask ("studentquizes", "studentquizes");
		$this->registerTask ("quizdetails", "quizdetails");
		$this->registerTask("terms", "terms");
		$this->registerTask("action", "action");
		$this->registerTask("apply_commissions", "applyCommissions");
		$this->registerTask("paid_commission", "paidCommission");
		$this->registerTask("pending_commission", "pendingCommission");
		$this->registerTask("details_paid", "detailsPaid");
		
		
		$this->_model = $this->getModel("guruauthor");
	}
	
	function getAuthorList(){
		JRequest::setVar ("view", "guruauthor");
		$view = $this->getView("guruauthor", "html");
		$view->setModel($this->_model, true);
		parent::display();
	}
	
	function action(){
		$return = $this->_model->action();
		JRequest::setVar("action", "0");
		$pid = JRequest::getVar("pid", "0");
		$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruauthor&task=treeCourse&pid=".intval($pid)));
	}
	
	function removeCourses(){
		$Itemid = JRequest::getVar("Itemid", "0");
		if(!$this->_model->delete()){
			$msg = JText::_('GURU_COURSE_NOT_REMOVED');
		}
		else{
		 	$msg = JText::_('GURU_COURSE_REMOVED');
		}

		$link = "index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses&Itemid=".$Itemid;
		$this->setRedirect(JRoute::_($link, false), $msg);
	}
	function removeMedia(){
		$Itemid = JRequest::getVar("Itemid", "0");
		if(!$this->_model->deleteMedia()){
			$msg = JText::_('GURU_MEDIA_NOT_REMOVED');
		}
		else{
		 	$msg = JText::_('GURU_MEDIA_REMOVED');
		}

		$link = "index.php?option=com_guru&view=guruauthor&task=authormymedia&layout=authormymedia&Itemid=".$Itemid;
		$this->setRedirect(JRoute::_($link, false), $msg);
	}
	function removeMediaCat(){
		$Itemid = JRequest::getVar("Itemid", "0");
		if(!$this->_model->removeMediaCat()){
			$msg = JText::_('GURU_MEDIACAT_NOT_REMOVED');
		}
		else{
		 	$msg = JText::_('GURU_MEDIACAT_REMOVED');
		}

		$link = "index.php?option=com_guru&view=guruauthor&task=authormymediacategories&layout=authormymediacategories&Itemid=".$Itemid;
		$this->setRedirect(JRoute::_($link, false), $msg);
	}
	function removeQuiz(){
		$Itemid = JRequest::getVar("Itemid", "0");
		if(!$this->_model->removeQuiz()){
			$msg = JText::_('GURU_QUIZ_NOT_REMOVED');
		}
		else{
		 	$msg = JText::_('GURU_QUIZ_REMOVED');
		}
		$link = "index.php?option=com_guru&view=guruauthor&task=authorquizzes&layout=authorquizzes&Itemid=".$Itemid;
		$this->setRedirect(JRoute::_($link, false), $msg);
	}
	function getAuthor(){
		JRequest::setVar ("view", "guruauthor");
		$view = $this->getView("guruauthor", "html");
		$view->setLayout("view");
		$view->setModel($this->_model, true);
		$view->view();
	}
	function authorProfile(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$user = JFactory::getUser();
		$user_id = $user->id;
		
		$model = $this->getModel("guruAuthor");
        $res = $model->checkAuthorProfile($user_id);
		$res_enabled = $model->checkAuthorProfileEnabled($user_id);
		
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=authorprofile"."&Itemid=".$Itemid, false));
			return true;
		}
		if($res === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS").'</p>
					  </div>';
			return true;
        }
		elseif($res_enabled === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS_ENABLED").'</p>
					  </div>';
			return true;
		}
		else{
			$view = $this->getView("guruauthor", "html");
			$view->setLayout("authorprofile");
			$view->setModel($this->_model, true);
			$view->authorprofile();
		}
	}
	function myStudents(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$user = JFactory::getUser();
		$user_id = $user->id;
		
		$model = $this->getModel("guruAuthor");
        $res = $model->checkAuthorProfile($user_id);
		$res_enabled = $model->checkAuthorProfileEnabled($user_id);
		
		
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=mystudents"."&Itemid=".$Itemid, false));
			return true;
		}
		if($res === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS").'</p>
					  </div>';
			return true;
        }
		elseif($res_enabled === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS_ENABLED").'</p>
					  </div>';
			return true;
		}
		else{
			$view = $this->getView("guruauthor", "html");
			$view->setLayout("mystudents");
			$view->setModel($this->_model, true);
			$view->mystudents();
		}
	}
	
	function authorMymedia(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$user = JFactory::getUser();
		$user_id = $user->id;
		
		$model = $this->getModel("guruAuthor");
        $res = $model->checkAuthorProfile($user_id);
		$res_enabled = $model->checkAuthorProfileEnabled($user_id);
		        
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=authormymedia"."&Itemid=".$Itemid, false));
			return true;
		}
		
		if($res === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS").'</p>
					  </div>';
			return true;
        }
		elseif($res_enabled === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS_ENABLED").'</p>
					  </div>';
			return true;
		}
		else{
			$view = $this->getView("guruAuthor", "html");
			$view->setLayout("authormymedia");
			$view->setModel($this->_model, true);
			$view->authormymedia();
		}
	}
	
	function authorCommissions(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$user = JFactory::getUser();
		$user_id = $user->id;
		
		$model = $this->getModel("guruAuthor");
        $res = $model->checkAuthorProfile($user_id);
		$res_enabled = $model->checkAuthorProfileEnabled($user_id);
		        
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=authorcommissions"."&Itemid=".$Itemid, false));
			return true;
		}
		
		if($res === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS").'</p>
					  </div>';
			return true;
        }
		elseif($res_enabled === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS_ENABLED").'</p>
					  </div>';
			return true;
		}
		else{
			$view = $this->getView("guruAuthor", "html");
			$view->setLayout("authorcommissions");
			$view->setModel($this->_model, true);
			$view->authorcommissions();
		}
	}
	function paidCommission(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("authorcommissions_paid");
		$view->setModel($this->_model, true);
		$view->authorcommissions_paid();
	}
	function pendingCommission(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("authorcommissions_pending");
		$view->setModel($this->_model, true);
		$view->authorcommissions_pending();
	}	
	
	function detailsPaid(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("authorcommissions_details_paid");
		$view->setModel($this->_model, true);
		$view->authorcommissions_details_paid();
	}
	
	function authormymediacategories(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$user = JFactory::getUser();
		$user_id = $user->id;
		
		$model = $this->getModel("guruAuthor");
        $res = $model->checkAuthorProfile($user_id);
		$res_enabled = $model->checkAuthorProfileEnabled($user_id);
		
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=authormymediacategories"."&Itemid=".$Itemid, false));
			return true;
		}
		if($res === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS").'</p>
					  </div>';
			return true;
        }
		elseif($res_enabled === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS_ENABLED").'</p>
					  </div>';
			return true;
		}
		else{
			$view = $this->getView("guruAuthor", "html");
			$view->setLayout("authormymediacategories");
			$view->setModel($this->_model, true);
			$view->authormymediacategories();
		}
	}
	
	function authorTreeCourses(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$user = JFactory::getUser();
		$user_id = $user->id;
		
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=mystudents"."&Itemid=".$Itemid, false));
			return true;
		}
		else{
			$view = $this->getView("guruauthor", "html");
			$view->setLayout("authortreecourse");
			$view->setModel($this->_model, true);
			$view->authortreecourse();
		}
	}
	
	function newStudent(){
		$view = $this->getView("guruauthor", "html");
		$view->setLayout("newstudent");
		$view->setModel($this->_model, true);
		$view->newstudent();
	}
	
	function authorQuizzes(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$user = JFactory::getUser();
		$user_id = $user->id;
		
		$model = $this->getModel("guruAuthor");
        $res = $model->checkAuthorProfile($user_id);
		$res_enabled = $model->checkAuthorProfileEnabled($user_id);
		
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=authorquizzes"."&Itemid=".$Itemid, false));
			return true;
		}
		if($res === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS").'</p>
					  </div>';
			return true;
        }
		elseif($res_enabled === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS_ENABLED").'</p>
					  </div>';
			return true;
		}
		else{
			$view = $this->getView("guruauthor", "html");
			$view->setLayout("authorquizzes");
			$view->setModel($this->_model, true);
			$view->authorquizzes();
		}
	}
	function authorMycourses(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$user = JFactory::getUser();
		$user_id = $user->id;
		
		$model = $this->getModel("guruAuthor");
        $res = $model->checkAuthorProfile($user_id);
		$res_enabled = $model->checkAuthorProfileEnabled($user_id);
		
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=authormycourses"."&Itemid=".$Itemid, false));
			return true;
		}
		if($res === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS").'</p>
					  </div>';
			return true;
        }
		elseif($res_enabled === FALSE){
			echo '<div class="alert alert-error">
						<h4 class="alert-heading">Message</h4>
						<p>'.JText::_("GURU_ONLY_AUTHORS_ENABLED").'</p>
					  </div>';
			return true;
		}
		else{
			$view = $this->getView("guruauthor", "html");
			$view->setLayout("authormycourses");
			$view->setModel($this->_model, true);
			$view->authormycourses();
		}
	}
	function authorRegister(){
		$view = $this->getView("guruauthor", "html");
        $view->setLayout("authorprofile");
		$view->setModel($this->_model, true);
        $view->authorprofile();
	}
	function addCourse(){
		$view = $this->getView("guruauthor", "html");
        $view->setLayout("authoraddcourse");
		$view->setModel($this->_model, true);
        $view->authoraddcourse();
        //ADDED BY JOSEPH 31/03/2015
        //$link = "index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authoraddcourse";
        //$this->setRedirect(JRoute::_($link, false));
        //END
	}
	
	function authoraddeditmediacat(){
		$view = $this->getView("guruauthor", "html");
        $view->setLayout("authoraddeditmediacat");
		$view->setModel($this->_model, true);
        $view->authoraddeditmediacat();
	
	}	
	
	function save(){
		$come_from =  JRequest::getVar("g_page");
		$msg = "";
		$link = "";
		
		if($come_from == "courseadd"){
			$link = "index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses";
			if($this->_model->store()){
				$msg = JText::_('GURU_COURSE_SAVED_SUCCESSFULLY');
			} 
			else{
				$msg = JText::_('GURU_COURSE_SAVED_UNSUCCESSFULLY');
			}
		}
		elseif($come_from == "courseedit"){
			$link = "index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses";
			if($this->_model->store()){
				$msg = JText::_('GURU_COURSE_SAVED_SUCCESSFULLY');
			} 
			else{
				$msg = JText::_('GURU_COURSE_SAVED_UNSUCCESSFULLY');
			}
		}
		else{
			if($this->_model->store()){
				$msg = JText::_('GURU_CUST_SAVED');
			} 
			else{
				$msg = JText::_('GURU_CUST_SAVEFAIL');
			}
			$link = "index.php?option=com_guru&view=guruauthor&task=mystudents&layout=mystudents";
		}
		
		$this->setRedirect(JRoute::_($link, false), $msg);
	}
	
	function apply(){
		$come_from =  JRequest::getVar("g_page");
		$result = $this->_model->store();
		$userId = $result["id"];
		$msg = "";
		if($come_from == "courseadd"){
			$link = "index.php?option=com_guru&view=guruauthor&task=addCourse&id=".intval($result["id"]);
			if(intval($result["id"]) !=0){
				$msg = JText::_('GURU_COURSE_SAVED_SUCCESSFULLY');
			} 
			else{
				$msg = JText::_('GURU_COURSE_SAVED_UNSUCCESSFULLY');
			}
		}
		elseif($come_from == "courseedit" ){
			$link = "index.php?option=com_guru&view=guruauthor&task=addCourse&id=".intval($result["id"]);
			if(intval($result["id"]) !=0){
				$msg = JText::_('GURU_COURSE_SAVED_SUCCESSFULLY');
			} 
			else{
				$msg = JText::_('GURU_COURSE_SAVED_UNSUCCESSFULLY');
			}
		}
		else{
			$link = "index.php?option=com_guru&view=guruauthor&task=newStudent&layout=newStudent&id=".intval($result["id"]);
			if(isset($result["error"]) && $result["error"] === TRUE){
				$msg = JText::_('GURU_CUST_APPLY');
			} 
			elseif(isset($result["error"]) && $result["error"] === FALSE){
				$msg = JText::_('GURU_CUST_APPLYFAIL');
			}
		}
		
		$this->setRedirect(JRoute::_($link, false), $msg);
	}
	
	function duplicateCourse(){
		$result = $this->_model->duplicateCourse();
		$type = "message";
		if($result === TRUE){
			$link = "index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses";
			$msg = JText::_("GURU_DUPLICATE_COURSE_SUCCESSFULLY");
			$type = "message";
		}
		else{
			$link = "index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses";
			$msg = JText::_("GURU_DUPLICATE_COURSE_UNSUCCESSFULLY");
			$type = "error";
		}
		$this->setRedirect(JRoute::_($link, false), $msg, $type);
	}
	
	function unpublishCourse(){
		$result = $this->_model->unpublishCourse();
		$type = "message";
		if($result === TRUE){
			$link = "index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses";
			$msg = JText::_("GURU_UNPUBLISH_COURSE_SUCCESSFULLY");
			$type = "message";
		}
		else{
			$link = "index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses";
			$msg = JText::_("GURU_UNPUBLISH_COURSE_UNSUCCESSFULLY");
			$type = "error";
		}
		$this->setRedirect(JRoute::_($link, false), $msg, $type);
	}
	
	function publishCourse(){
		$result = $this->_model->publishCourse();
		$type = "message";
		if($result === TRUE){
			$link = "index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses";
			$msg = JText::_("GURU_PUBLISH_COURSE_SUCCESSFULLY");
			$type = "message";
		}
		else{
			$link = "index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses";
			$msg = JText::_("GURU_PUBLISH_COURSE_UNSUCCESSFULLY");
			$type = "error";
		}
		$this->setRedirect(JRoute::_($link, false), $msg, $type);
	}
	function unpublishMedia(){
		$result = $this->_model->unpublishMedia();
		$type = "message";
		if($result === TRUE){
			$link = "index.php?option=com_guru&view=guruauthor&task=authormymedia&layout=authormymedia";
			$msg = JText::_("GURU_MEDIAUNPUB");
			$type = "message";
		}
		else{
			$link = "index.php?option=com_guru&view=guruauthor&task=authormymedia&layout=authormymedia";
			$msg = JText::_("GURU_MEDIAPUB");
			$type = "error";
		}
		$this->setRedirect(JRoute::_($link, false), $msg, $type);
	}
	
	function publishMedia(){
		$result = $this->_model->publishMedia();
		$type = "message";
		if($result === TRUE){
			$link = "index.php?option=com_guru&view=guruauthor&task=authormymedia&layout=authormymedia";
			$msg = JText::_("GURU_MEDIAPUB");
			$type = "message";
		}
		else{
			$link = "index.php?option=com_guru&view=guruauthor&task=authormymedia&layout=authormymedia";
			$msg = JText::_("GURU_MEDIAUNPUB");
			$type = "error";
		}
		$this->setRedirect(JRoute::_($link, false), $msg, $type);
	}
	
	function unpublishMediaCat(){
		$result = $this->_model->unpublishMediaCat();
		$type = "message";
		if($result === TRUE){
			$link = "index.php?option=com_guru&view=guruauthor&task=authormymediacategories&layout=authormymediacategories";
			$msg = JText::_("GURU_MEDIAUNPUB");
			$type = "message";
		}
		else{
			$link = "index.php?option=com_guru&view=guruauthor&task=authormymediacategories&layout=authormymediacategories";
			$msg = JText::_("GURU_MEDIAPUB");
			$type = "error";
		}
		$this->setRedirect(JRoute::_($link, false), $msg, $type);
	}
	
	function publishMediaCat(){
		$result = $this->_model->publishMediaCat();
		$type = "message";
		if($result === TRUE){
			$link = "index.php?option=com_guru&view=guruauthor&task=authormymediacategories&layout=authormymediacategories";
			$msg = JText::_("GURU_MEDIAPUB");
			$type = "message";
		}
		else{
			$link = "index.php?option=com_guru&view=guruauthor&task=authormymediacategories&layout=authormymediacategories";
			$msg = JText::_("GURU_MEDIAUNPUB");
			$type = "error";
		}
		$this->setRedirect(JRoute::_($link, false), $msg, $type);
	}
	function unpublishQuiz(){
		$result = $this->_model->unpublishQuiz();
		$type = "message";
		if($result === TRUE){
			$link = "index.php?option=com_guru&view=guruauthor&task=authorquizzes&layout=authorquizzes";
			$msg = JText::_("GURU_QUIZUNPUB");
			$type = "message";
		}
		else{
			$link = "index.php?option=com_guru&view=guruauthor&task=authorquizzes&layout=authorquizzes";
			$msg = JText::_("GURU_QUIZPUB");
			$type = "error";
		}
		$this->setRedirect(JRoute::_($link, false), $msg, $type);
	}
	
	function publishQuiz(){
		$result = $this->_model->publishQuiz();
		$type = "message";
		if($result === TRUE){
			$link = "index.php?option=com_guru&view=guruauthor&task=authorquizzes&layout=authorquizzes";
			$msg = JText::_("GURU_QUIZPUB");
			$type = "message";
		}
		else{
			$link = "index.php?option=com_guru&view=guruauthor&task=authorquizzes&layout=authorquizzes";
			$msg = JText::_("GURU_QUIZUNPUB");
			$type = "error";
		}
		$this->setRedirect(JRoute::_($link, false), $msg, $type);
	}
	function newModule(){
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("newModule");
		$view->newModule();
	}
	
	function saveNewModule(){
		$pid = JRequest::getVar("pid", "0");
		if($this->_model->store_new_module()){
			$msg = JText::_('GURU_DAY_SAVE');
			$_SESSION["saved_new"] = 1;
		}
		else{
			$msg = JText::_('GURU_DAY_NOTSAVE');
		}
		echo "	<script> 
					window.parent.location.href=\"".JURI::base()."index.php?option=com_guru&view=guruauthor&task=treeCourse&pid=".$pid."\";
					window.parent.document.getElementById('close').click();
				</script>";
	}
	
	function saveModule(){
		$pid = JRequest::getVar("pid", "0");
		if($this->_model->store_module()){
			$msg = JText::_('GURU_DAY_SAVE');
			$_SESSION["saved_new"] = 1;
		}
		else{
			$msg = JText::_('GURU_DAY_NOTSAVE');
		}
		echo "	<script> 
					window.parent.location.href=\"".JURI::base()."index.php?option=com_guru&view=guruauthor&task=treeCourse&pid=".$pid."\";
					window.parent.document.getElementById('close').click();
				</script>";
	}
	
	function edit(){
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);
	
		$model = $this->getModel("guruAuthor");
		$view->setModel($model);
		$view->editForm();
	}
	
	function vimeo() {
   		JRequest::setVar('view', 'guruAuthor');
		JRequest::setVar('layout', 'vimeo');
        $view = $this->getView("guruAuthor", "html");
		$view->setLayout("vimeo");
        $view->vimeo();
        die();
    }
	function preview(){
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("preview");
		$view->setModel($this->_model, true);
		$view->preview();
	}
	
	function editsbox(){
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("editformsbox");
		$view->setModel($this->_model, true);
		$view->editLessonForm();
	}
	
	function addmedia(){
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("addmedia");
		$view->setModel($this->_model, true);
		$view->addmedia();
	}
	
	function addexercise(){
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("addexercise");
		$view->setModel($this->_model, true);
		$view->addexercise();
	}
	
	function addQuiz(){
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("addquiz");
		$view->setModel($this->_model, true);
		$view->addQuiz();
	}
	
	function addtext(){
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("addtext");
		$view->setModel($this->_model, true);
		$view->addmedia();
	}
	
	function duplicateMedia(){ 
		$res = $this->_model->duplicateMedia();
		if($res == 1){
			$msg = JText::_('GURU_MEDIA_DUPLICATE_SUCC');
		}
		else{
			$msg = JText::_('GURU_MEDIA_DUPLICATE_ERR');
		}
		
		$link = "index.php?option=com_guru&view=guruauthor&task=authormymedia&layout=authormymedia";
		$this->setRedirect(JRoute::_($link, false), $msg);
	}
	
	function editMedia(){
		$redirect_to= JRequest::getVar('redirect_to',NULL,"post","string");
		$type		= JRequest::getVar('type',"","post","string");
		
		if(isset($redirect_to)) {
			$_SESSION['temp_type']=$type;
			$msg=NULL;
			$this->setRedirect(JRoute::_($redirect_to, false), $msg);			
		}	
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("editmedia");
		$view->setModel($this->_model, true);
		$view->editMediaForm();
	}
	
	function saveMedia(){
		if($id = $this->_model->storeMedia()){
			$msg = JText::_('GURU_MEDIASAVED');
		} 
		else{
			if($_SESSION["isempty"] == 1){
				$msg = JText::_('GURU_MEDIASAVEDFAILED_TEXTEMPTY');
				unset($_SESSION["isempty"]);
			}
			else{
				$msg = JText::_('GURU_MEDIASAVEDFAILED');
				if($_SESSION["nosize"] == 0){
				$msg = $msg.JText::_('GURU_MEDIASAVEDSIZE');
				$n='warning';
				}
				unset($_SESSION["nosize"]);
			}	
		}
		$link = "index.php?option=com_guru&view=guruauthor&task=authormymedia&layout=authormymedia";
		$this->setRedirect(JRoute::_($link, false), $msg, $n);
	}
	
	function applyMedia(){
		$id = JRequest::getVar("id","0","post","int");
		if($this->_model->storeMedia()){
			$msg = JText::_('GURU_MEDIAAPPLY');
		} 
		else{
			if($_SESSION["isempty"] == 1){
				$msg = JText::_('GURU_MEDIASAVEDFAILED_TEXTEMPTY');
				unset($_SESSION["isempty"]);
			}
			else{
				$msg = JText::_('GURU_MEDIAAPPLYFAILED');
				if($_SESSION["nosize"] == 0){
				$msg = $msg.JText::_('GURU_MEDIASAVEDSIZE');
				$n='warning';
				}
				unset($_SESSION["nosize"]);
			}	
		}
		
		if($id != 0){
			$link = "index.php?option=com_guru&view=guruauthor&task=editMedia&cid=".intval($id);
		} 
		else{
			$last_media = $this->_model->last_media();
			$link = "index.php?option=com_guru&view=guruauthor&task=editMedia&cid=".$last_media;
		}
		$this->setRedirect(JRoute::_($link, false), $msg,$n);
	}
	
	function savesbox(){
		JRequest::setVar ("hidemainmenu", 1);
		JRequest::setVar ("tmpl", "component");
		$id				= JRequest::getVar("id","0","post","int");
		$mediatext		= JRequest::getVar('mediatext','','post','string');
		$mediatextvalue	= JRequest::getVar('mediatextvalue','','post','string');
		$screen			= JRequest::getVar('screen', '0');
		
		$action = JRequest::getVar("action", "");
		
		if($action == "addtext" || $action == "addmedia"){
			$screen = "1";
		}
		
		if($id==0){
			if((($mediatext!="") && ($mediatextvalue!="") && ($screen!="")) || ($screen=="0")){				
				if($id=$this->_model->storeMedia()){
					?>
					<script type="text/javascript" src="<?php echo JURI::root().'media/system/js/mootools.js' ?>"></script>
								
					<script type="text/javascript">
					
						function loadjscssfile(filename, filetype){
							if (filetype=="js"){ //if filename is a external JavaScript file
								var fileref=document.createElement('script')
							  	fileref.setAttribute("type","text/javascript")
							  	fileref.setAttribute("src", filename)
							}
							else if (filetype=="css"){ //if filename is an external CSS file
								var fileref=document.createElement("link")
								fileref.setAttribute("rel", "stylesheet")
								fileref.setAttribute("type", "text/css")
								fileref.setAttribute("href", filename)
							}
								if (typeof fileref!="undefined")
								document.getElementsByTagName("head")[0].appendChild(fileref)
						}
								
						function loadprototipe(){
							loadjscssfile("<?php echo JURI::base().'components/com_guru/views/guruauthor/tmpl/prototype-1.6.0.2.js' ?>","js");
						//alert('testing');
						}
								
						function addmedia (idu, name, description) {
						<?php if($screen != "0"){ ?>
							jQuery.ajax({
								url: 'components/com_guru/views/guruauthor/tmpl/ajaxAdd<?php if($mediatext=='med') { echo "Media";} else { echo "Text";}  ?>.php?id='+idu,
								cache: false
							})
							.done(function(transport) {
								replace_m = <?php echo $mediatextvalue;?>;
								to_be_replaced = parent.document.getElementById('<?php if($mediatext=='med') { echo "media";} else { echo "text";} ?>_'+replace_m);
								to_be_replaced.innerHTML = '&nbsp;';
								if ((transport.match(/(.*?).pdf(.*?)/))&&(!transport.match(/(.*?).iframe(.*?)/))) {
									to_be_replaced.innerHTML += transport+'<p /><div  style="text-align:center"><i>' + description + '</i></div>'; 
								} 
								else {
									to_be_replaced.innerHTML += transport+'<br /><div style="text-align:center"><i>'+ name +'</i></div><br /><div  style="text-align:center"><i>' + description + '</i></div>';
								}
								
								parent.document.getElementById('before_menu_<?php if($mediatext=='med') { echo "med";} else { echo "txt";} ?>_'+replace_m).style.display = 'none';
								parent.document.getElementById('after_menu_<?php if($mediatext=='med') { echo "med";} else { echo "txt";} ?>_'+replace_m).style.display = '';
								parent.document.getElementById('db_<?php if($mediatext=='med') { echo "media";} else { echo "text";} ?>_'+replace_m).value = idu;
								
								screen_id = <?php echo $screen; ?>;
								
								replace_edit_link = parent.document.getElementById('a_edit_<?php if($mediatext=='med') { echo "media";} else { echo "text";} ?>_'+replace_m);
								replace_edit_link.href = 'index.php?option=com_guru&controller=guruAuthor&tmpl=component&task=editsboxx&cid='+ idu +'&scr=' + screen_id;
								if ((transport.match(/(.*?).pdf(.*?)/))&&(!transport.match(/(.*?).iframe(.*?)/))) {
									var qwe='&nbsp;'+transport+'<p /><div  style="text-align:center"><i>' + description + '</i></div>';
								} 
								else {
									var qwe='&nbsp;'+transport+'<br /><div style="text-align:center"><i>'+ name +'</i></div><div  style="text-align:center"><i>' + description + '</i></div>';
								}
								window.parent.<?php if($mediatext=='med') { echo "";} else { echo "tx";} ?>test(replace_m, idu, qwe);
							});
						<?php } 
							else { 
						// for adding sound ?>
							jQuery.ajax({
								url: 'components/com_guru/views/guruauthor/tmpl/ajaxAddMedia.php?id='+idu,
								cache: false
							})
							.done(function(transport) {
								replace_m = "99";
								to_be_replaced = parent.document.getElementById('media_'+replace_m);
								to_be_replaced.innerHTML = '&nbsp;';
								
								if(replace_m!=99){
									if ((transport.match(/(.*?).pdf(.*?)/))&&(!transport.match(/(.*?).iframe(.*?)/))) {
										to_be_replaced.innerHTML += transport+'<p /><div  style="text-align:center"><i>' + description + '</i></div>'; 
									} 
									else {
										to_be_replaced.innerHTML += transport+'<br /><div style="text-align:center"><i>'+ name +'</i></div><br /><div  style="text-align:center"><i>' + description + '</i></div>';
									}
								} 
								else {
									to_be_replaced.innerHTML += transport;
									parent.document.getElementById("media_"+99).style.display="";
									parent.document.getElementById("description_med_99").innerHTML=''+name;
								}
								parent.document.getElementById('before_menu_med_'+replace_m).style.display = 'none';
								parent.document.getElementById('after_menu_med_'+replace_m).style.display = '';
								parent.document.getElementById('db_media_'+replace_m).value = idu;
								
								screen_id = document.getElementById('the_screen_id').value;
								replace_edit_link = parent.document.getElementById('a_edit_media_'+replace_m);
								replace_edit_link.href = 'index.php?option=com_guru&controller=guruAuthor&tmpl=component&task=editsboxx&cid='+ idu+"&scr="+replace_m;
								if ((transport.match(/(.*?).pdf(.*?)/))&&(!transport.match(/(.*?).iframe(.*?)/))) {
									var qwe='&nbsp;'+transport+'<p /><div  style="text-align:center"><i>' + description + '</i></div>';
								} 
								else {
									var qwe='&nbsp;'+transport+'<br /><div style="text-align:center"><i>'+ name +'</i></div><div  style="text-align:center"><i>' + description + '</i></div>';
								}
								window.parent.test(replace_m, idu, '<span class="success-add-media"><?php echo JText::_("GURU_SUCCESSFULLY_ADED_MEDIA"); ?></span>');
							});
						<?php }?>
							//window.parent.close_modal();
							setTimeout('window.parent.document.getElementById("close").click()',1000);
							//window.parent.SqueezeBox.close();						
							return true;						
						}				
					</script>
                    					
					<?php
					
					$current_media = $this->_model->getMediaInfo($id);
					
					if($screen=="0"){
						echo '<script type="text/javascript">window.onload=function(){
						loadprototipe();
						var t=setTimeout(\'addmedia('.$id.', "'.addslashes(trim($current_media->name)).'", "-", "");\',1000);						
						}</script>';
					}
					else{
						echo '<script type="text/javascript">window.onload=function(){
						loadprototipe();
						var t=setTimeout(\'addmedia('.$id.', "'.addslashes(trim($current_media->name)).'", "'.$current_media->instructions.'");\',1000);						
						}</script>'; 
					}
					
							
					echo '<strong>Media saved. Please wait...</strong>';
				}
			}		
		} 
		else{
			if($id = $this->_model->storeMedia()){
				$msg = JText::_('GURU_MEDIASAVED');
			} 
			else{
				$msg = JText::_('GURU_MEDIASAVEDFAILED');
			}

			echo '<script type="text/javascript">window.onload=function(){
				window.parent.page_refresh('.$screen.');
				var t=setTimeout(\'window.parent.SqueezeBox.close();\',0);
			}</script>';
			echo '<strong>Media saved. Please wait...</strong>';
		}
	}
	function saveMediaCat(){
		if($id = $this->_model->storeMediaCat()){
			$msg = JText::_('GURU_MEDIAAPPLY');
		} 
		else{
			if($_SESSION["isempty"] == 1){
				$msg = JText::_('GURU_MEDIASAVEDFAILED_TEXTEMPTY');
				unset($_SESSION["isempty"]);
			}
			else{
				$msg = JText::_('CUSTSAVEFAILED');
				if($_SESSION["nosize"] == 0){
				$n='warning';
				}
				unset($_SESSION["nosize"]);
			}	
		}
		$link = "index.php?option=com_guru&view=guruauthor&task=authormymediacategories&layout=authormymediacategories";
		$this->setRedirect(JRoute::_($link, false), $msg, $n);
	}
	
	function applyMediaCat(){
		$id = JRequest::getVar("id","0");
		$return = $this->_model->storeMediaCat();
		if($return["0"]){
			$msg = JText::_('GURU_MEDIAAPPLY');
		} 
		else{
			if($_SESSION["isempty"] == 1){
				$msg = JText::_('GURU_MEDIASAVEDFAILED_TEXTEMPTY');
				unset($_SESSION["isempty"]);
			}
			else{
				$msg = JText::_('GURU_MEDIAAPPLYFAILED');
				if($_SESSION["nosize"] == 0){
				$msg = $msg.JText::_('GURU_MEDIASAVEDSIZE');
				$n='warning';
				}
				unset($_SESSION["nosize"]);
			}	
		}
		
		if($id != 0){
			$link = "index.php?option=com_guru&view=guruauthor&task=authoraddeditmediacat&id=".intval($id);
		} 
		else{
			$last_media = $return["1"];
			$link = "index.php?option=com_guru&view=guruauthor&task=authoraddeditmediacat&id=".intval($last_media);
		}
		$this->setRedirect(JRoute::_($link, false), $msg,$n);
	}
	
	function duplicateMediaCat(){
		$result = $this->_model->duplicateMediaCat();
		
		$type = "message";
		if($result){
			$link = "index.php?option=com_guru&view=guruauthor&task=authormymediacategories&layout=authormymediacategories";
			$msg = JText::_("GURU_DUPLICATE_CATEG_SUCCESSFULLY");
			$type = "message";
		}
		else{
			$link = "index.php?option=com_guru&view=guruauthor&task=authormymediacategories&layout=authormymediacategories";
			$msg = JText::_("GURU_DUPLICATE_CATEG_UNSUCCESSFULLY");
			$type = "error";
		}
		$this->setRedirect(JRoute::_($link, false), $msg, $type);
	}

	function applyLesson(){
		$task = JRequest::getVar("task", "");
		$return = $this->_model->storeLesson();
		if($return["return"] === TRUE){
			$msg = JText::_('GURU_TASKS_SAVED');
		} else {
			$msg = JText::_('GURU_TASKS_NOTSAVED');
		}
		$id = JRequest::getVar("id", "");
		$module = JRequest::getVar("module","");
		if($id == ""){
			$id = $return["id"];
		}
		$progrid=JRequest::getVar("day", "");
		$link ="index.php?option=com_guru&view=guruauthor&tmpl=component&task=editsbox&cid=".$id."&progrid=".$progrid."&module=".intval($module);	
		$this->setRedirect(JURI::root().$link);
	}
	
	function saveLesson(){
		JRequest::setVar ("hidemainmenu", 1);
		JRequest::setVar ("tmpl", "component");	
		$return = $this->_model->storeLesson();
		if($return["return"] === TRUE){
			$msg = JText::_('GURU_TASKS_SAVED');
		} 
		else{
			$msg = JText::_('GURU_TASKS_NOTSAVED');
		}

		echo "Step saved. Please wait...";
		echo '<script type="text/javascript">window.onload=function(){
			window.parent.location.reload(true);
			}</script>';
	}
	
	function editQuiz(){
		$user = JFactory::getUser();
		$user_id = $user->id;
		
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=authorprofile"."&Itemid=".$Itemid, false));
			return true;
		}
		
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("editQuiz");
		$view->setModel($this->_model, true);
		$view->editQuiz();
	}
	
	function editQuizFE(){
		$user = JFactory::getUser();
		$user_id = $user->id;
		
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=authorprofile"."&Itemid=".$Itemid, false));
			return true;
		}
		
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("editquizfe");
		$view->setModel($this->_model, true);
		$view->editQuizFE();
	}
	
	function saveQuiz(){
		JRequest::setVar("hidemainmenu", 1);
		JRequest::setVar("tmpl", "component");
		$id	= JRequest::getVar("id", "0");
		$screen	= 12;
		
		?>
        <script language="javascript" type="text/javascript">	
			function loadjscssfile(filename, filetype){
				if (filetype=="js"){ //if filename is a external JavaScript file
					var fileref=document.createElement('script')
					fileref.setAttribute("type","text/javascript")
					fileref.setAttribute("src", filename)
				}
				else if (filetype=="css"){ //if filename is an external CSS file
					var fileref=document.createElement("link")
					fileref.setAttribute("rel", "stylesheet")
					fileref.setAttribute("type", "text/css")
					fileref.setAttribute("href", filename)
				}
				if (typeof fileref!="undefined"){
					document.getElementsByTagName("head")[0].appendChild(fileref);
				}
			}
		
			function loadprototipe(){
				loadjscssfile("<?php echo JURI::base().'components/com_guru/views/guruauthor/tmpl/prototype-1.6.0.2.js' ?>","js");
			}
		
			function addmedia (idu, name, asoc_file, description) {
				loadprototipe();
				
				jQuery.ajax({
					url: 'components/com_guru/views/guruauthor/tmpl/ajaxAddMedia.php?id='+idu+'&type=quiz',
					cache: false
				})
				.done(function(transport) {
					to_be_replaced=parent.document.getElementById('media_15');
					replace_m=15;
					to_be_replaced.innerHTML = '&nbsp;';
			
					to_be_replaced.innerHTML += transport;
					parent.document.getElementById("media_"+99).style.display="";
					parent.document.getElementById("description_med_99").innerHTML=''+name;
						
					parent.document.getElementById('before_menu_med_'+replace_m).style.display = 'none';
					parent.document.getElementById('after_menu_med_'+replace_m).style.display = '';
					parent.document.getElementById('db_media_'+replace_m).value = idu;
					
					replace_edit_link = parent.document.getElementById('a_edit_media_'+replace_m);
					replace_edit_link.href = 'index.php?option=com_guru&controller=guruAuthor&tmpl=component&task=editQuiz&cid='+ idu;
					var qwe='&nbsp;'+transport+'<br /><div style="text-align:center"><i>'+ name +'</i></div><div  style="text-align:center"><i>' + description + '</i></div>';
							
					window.parent.test(replace_m, idu,qwe);
				});
				
				setTimeout('window.parent.document.getElementById("close").click()',1000);
				return true;
			}
		</script>
        <?php
		
		if($id==0){
			if($id=$this->_model->storeQuiz()){
				$quiz=$this->_model->getQuizById();
				echo '<script type="text/javascript">window.onload=function(){
					var t=setTimeout(\'addmedia('.$quiz->id.', "'.$quiz->name.'","-", "'.$quiz->description.'");\',1000);						
			}</script>';
			}		
		}
		else{
			if($id=$this->_model->storeQuiz()){
				$msg = JText::_('GURU_MEDIASAVED');
				$quiz=$this->_model->getQuizById();
				echo '<script type="text/javascript">window.onload=function(){
					var t=setTimeout(\'addmedia('.$quiz->id.', "'.$quiz->name.'","-", "'.$quiz->description.'");\',1000);						
			}</script>';
			}
			else {
				$msg = JText::_('GURU_MEDIASAVEDFAILED');
			}
			echo '<script type="text/javascript">window.onload=function(){
				var t=setTimeout(\'window.parent.document.getElementById("sbox-window").close();\',0);
				window.parent.page_refresh('.$screen.');
			}</script>';
		}
		echo '<strong>'.JText::_('GURU_QUIZSAVED_PLSWAIT').'</strong>';
	}
	
	function addQuestion(){
		JRequest::setVar("hidemainmenu", 1); 
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("addquestion");
		$view->setModel($this->_model, true);
		$view->addQuestion();
	}
	
	function saveQuestion(){
		$qtext 	= JRequest::getVar('text', '');
		$qtext = addslashes(trim($qtext));
		$quizid = JRequest::getVar('quizid','0','post','int');
		$a1 = JRequest::getVar('a1',NULL,'post','string');
		$a2 = JRequest::getVar('a2',NULL,'post','string');
		$a3 = JRequest::getVar('a3',NULL,'post','string');
		$a4 = JRequest::getVar('a4',NULL,'post','string');
		$a5 = JRequest::getVar('a5',NULL,'post','string');
		$a6 = JRequest::getVar('a6',NULL,'post','string');
		$a7 = JRequest::getVar('a7',NULL,'post','string');
		$a8 = JRequest::getVar('a8',NULL,'post','string');
		$a9 = JRequest::getVar('a9',NULL,'post','string');
		$a10= JRequest::getVar('a10',NULL,'post','string');
		$answers = '';
		
		$is_from_modal_lesson = JRequest::getVar('is_from_modal');
		
		for($i=1;$i<=10;$i++){
			$on=JRequest::getVar($i."a","","post","string");
			if($on=='on'){
				$answers.=$i.'a|||';
			}	
		}
		
		$qid = JRequest::getVar("qid", "0");
		
		if(intval($qid) == 0){
			$this->_model->addquestion($qtext,$quizid,$a1,$a2,$a3,$a4,$a5,$a6,$a7,$a8,$a9,$a10,$answers);
		}
		else{
			$this->_model->updatequestion($qtext,$qid,$a1,$a2,$a3,$a4,$a5,$a6,$a7,$a8,$a9,$a10,$answers);
		}
		$_SESSION["added_questions_tab"] = "1";

		if($quizid != 0){
			if($is_from_modal_lesson != 1){
				echo "Saving question. Please wait...";
				echo '<script type="text/javascript">';
				
				echo 'if(window.parent.document.adminForm.name.value == ""){
						window.parent.location.reload();	
					  }
					  else{
						window.parent.document.adminForm.task.value = "apply_quizFE";
						window.parent.document.adminForm.submit();
					  }';
				echo '</script>';
			
			}
			else{
				echo "Saving question. Please wait...";
				echo '<script type="text/javascript">';
				
				echo 'if(window.parent.document.adminForm.name.value == ""){
						window.parent.location.reload();	
					  }
					  else{
						window.parent.document.adminForm.task.value = "apply_quizFE";
						window.parent.document.adminForm.submit();
					  }';
				echo '</script>';
			}
			die();
			
		}
		
		if($quizid == 0){
			if($is_from_modal_lesson != 1){
				echo "Saving question. Please wait...";
				echo '<script type="text/javascript">';
				
				echo 'if(window.parent.document.adminForm.name.value == ""){
							window.parent.location.reload();	
					  }
					  else{
							window.parent.document.adminForm.task.value = "apply_quizFE";
							window.parent.document.adminForm.submit();
					  }';
				echo '</script>';
			
			}
			else{
				echo "Saving question. Please wait...";
				echo '<script type="text/javascript">';
				
				echo 'if(window.parent.document.adminForm.name.value == ""){
						window.parent.location.reload();	
					  }
					  else{
						window.parent.document.adminForm.task.value = "apply_quizFE";
						window.parent.document.adminForm.submit();
					  }';
				echo '</script>';
			}
			die();
		}
	}
	
	function editQuestion(){
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("editquestion");
		$view->setModel($this->_model, true);
		$view->editQuestion();
	}
	
	function jumpbts(){
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("jumpbts");
		$view->setModel($this->_model, true);
		$view->jumpbts();
	}
	
	function saveJump(){
		JRequest::setVar ("hidemainmenu", 1);
		JRequest::setVar ("tmpl", "component");
		$pieces = $this->_model->saveJump();
		echo '<script type="text/javascript">window.onload=function(){
				window.parent.document.getElementById("close").click();
				window.parent.jump('.$pieces["1"].','.$pieces["0"].',"'.$pieces["2"].'");
			}</script>';
		echo '<strong>Jump saved. Please wait...</strong>';
	}
	
	function editsboxx(){
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("editformsboxx");
		$view->setModel($this->_model, true);
		$view->editMediaForm();
	}
	
	function duplicateQuiz(){
		$result = $this->_model->duplicateQuiz();
		$type = "message";
		if($result === TRUE){
			$link = "index.php?option=com_guru&view=guruauthor&task=authorquizzes&layout=authorquizzes";
			$msg = JText::_("GURU_DUPLICATE_QUIZ_SUCCESSFULLY");
			$type = "message";
		}
		else{
			$link = "index.php?option=com_guru&view=guruauthor&task=authorquizzes&layout=authorquizzes";
			$msg = JText::_("GURU_DUPLICATE_QUIZ_UNSUCCESSFULLY");
			$type = "error";
		}
		$this->setRedirect(JRoute::_($link, false), $msg, $type);
	}
	
	function courseStats(){
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("coursestats");
		$view->setModel($this->_model, true);
		$view->courseStats();
	}
	
	function quizzStats(){
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("quizzstats");
		$view->setModel($this->_model, true);
		$view->quizzStats();
	}
	
	function saveQuizFE(){
		if($id = $this->_model->storeQuiz()){
			$msg = JText::_('GURU_QUIZSAVED');
		} 
		else{
			$msg = JText::_('GURU_QUIZ_NOT_SAVED');
		}
		$link = "index.php?option=com_guru&view=guruauthor&task=authorquizzes&layout=authorquizzes";
		$this->setRedirect(JRoute::_($link), $msg);
	}

	function applyQuizFE(){
		$id = JRequest::getVar("id", "0");
		if($this->_model->storeQuiz()){
			$msg = JText::_('GURU_QUIZSAVED');
		}
		else{
			$msg = JText::_('GURU_QUIZ_NOT_SAVED');
		}
		
		$valueop = JRequest::get('post');
		if($id == 0){
			$db = JFactory::getDBO();
			$sql = "SELECT max(id) FROM `#__guru_quiz` ";
			$db->setQuery($sql);
			$new_quiz_id = $db->loadColumn();
			$id = $new_quiz_id["0"];
		}
		
		if($valueop['valueop'] == 1){
			$link = "index.php?option=com_guru&view=guruauthor&task=editQuizFE&cid=".intval($id)."&e=1";
		}
		else{
			$link = "index.php?option=com_guru&view=guruauthor&task=editQuizFE&cid=".intval($id);
		}
		$this->setRedirect(JRoute::_($link), $msg);
	}
	function addquizzes(){
		JRequest::setVar("hidemainmenu", 1); 
		$view = $this->getView("guruAuthor", "html");
		$view->setLayout("addQuizzes");
		$view->setModel($this->_model, true);
		$view->addquizzes();
	}	
	function savequizzes(){
		$quizid = JRequest::getVar('quizid','0');
		$quizzes_ids = JRequest::getVar('quizzes_ids','0');
		
		$db = JFactory::getDBO();
		$sql = "select quizzes_ids from #__guru_quizzes_final where qid=".intval($quizid);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		
		if(count($result) == 0){
			$sql = "INSERT INTO `#__guru_quizzes_final` (`quizzes_ids`,`qid`)VALUES('".$quizzes_ids."','".$quizid."' )"; 
			$db->setQuery($sql);
			$db->query();
		}
		else{
			$newvalues = @$result["0"].$quizzes_ids;
			$sql = "update #__guru_quizzes_final set quizzes_ids='".$newvalues."' where qid=".$quizid;
			$db->setQuery($sql);
			$db->query();
		}			
		
		$_SESSION["added_quiz"] = "1";
		
		if($quizid != 0){
			echo "Saving quizzes. Please wait...";
			echo '<script type="text/javascript">';
			echo 'if(window.parent.document.adminForm.name.value == ""){
						window.parent.location.reload();	
				  }
				  else{
						window.parent.document.adminForm.task.value = "apply_quizFE";
						window.parent.document.adminForm.submit();
				  }';
			echo '</script>';
			die();
		}
		
		if($quizid==0){
			echo "Saving quizzes. Please wait...";
			echo '<script type="text/javascript">';
			echo 'if(window.parent.document.adminForm.name.value == ""){
						window.parent.location.reload();	
				  }
				  else{
						window.parent.document.adminForm.task.value = "apply_quizFE";
						window.parent.document.adminForm.submit();
				  }';
			echo '</script>';
			die();
		}
	
	}
		
	public function saveOrderQuestions(){
		// Get the arrays from the Request
		$originalOrder = explode(',', $this->input->getString('original_order_values'));

		$model = $this->getModel("guruAuthor");
		// Save the ordering
		$return = $model->saveOrderQuest();
		if ($return){
			echo "1";
		}
		// Close the application
		JFactory::getApplication()->close();
	}

	public function saveOrderExercices(){
		// Get the arrays from the Request
		$originalOrder = explode(',', $this->input->getString('original_order_values'));

		$model = $this->getModel("guruAuthor");
		// Save the ordering
		$return = $model->saveorderFile();
		if ($return){
			echo "1";
		}
		// Close the application
		JFactory::getApplication()->close();
	}
	
	function studentdetails(){
		JRequest::setVar ("view", "guruauthor");
		$view = $this->getView("guruauthor", "html");
		$view->setLayout("studentdetails");
		$view->setModel($this->_model, true);
		$view->studentdetails();
	}
	
	function studentquizes(){
		JRequest::setVar ("view", "guruauthor");
		$view = $this->getView("guruauthor", "html");
		$view->setLayout("studentquizes");
		$view->setModel($this->_model, true);
		$view->studentquizes();
	}
	
	function quizdetails(){
		JRequest::setVar ("view", "guruauthor");
		$view = $this->getView("guruauthor", "html");
		$view->setLayout("quizdetails");
		$view->setModel($this->_model, true);
		$view->quizdetails();
	}
	
	function terms(){
        $view = $this->getView("guruauthor", "html");
        $view->setLayout("terms");
        $view->terms();
    }
	function applyCommissions(){
		$result = $this->_model->applyCommissions();
		$type = "message";
		if($result === TRUE){
			$link = "index.php?option=com_guru&view=guruauthor&task=authorcommissions&layout=authorcommissions";
			$msg = JText::_("GURU_MODIF_OK");
			$type = "message";
		}		
		$this->setRedirect(JRoute::_($link, false), $msg, $type);
	}
	
};

?>
