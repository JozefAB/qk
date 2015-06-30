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

class guruAdminControllerguruCustomers extends guruAdminController{
	var $model = null;
	function __construct () {
		parent::__construct();
		$this->registerTask ("", "listCustomers");
		$this->registerTask ("add", "addCustomer");
		$this->registerTask ("edit", "editCustomer");
		$this->registerTask ("next", "newStudent");
		$this->registerTask ("block", "block");
		$this->registerTask ("unblock", "block");
		$this->registerTask ("reset", "resetCourses");
		$this->_model = $this->getModel('guruCustomers');		
	}

	function newStudent(){
		$action = JRequest::getVar("action", "");
		$username = JRequest::getVar("username", "");
		if($action == "new_existing_student"){	
			$user_id = $this->_model->getUserId($username);
			if($user_id == NULL){
				$msg = JText::_("GURU_THIS_USERNAME").' "'.$username.'" '.JText::_("GURU_NOT_EXIST");
				$this->setRedirect("index.php?option=com_guru&controller=guruCustomers&task=add", $msg, 'notice');
			}
			else{
				$this->setRedirect("index.php?option=com_guru&controller=guruCustomers&task=edit&action=existing&cid[]=".$user_id);
			}
		}
		else{
			$this->setRedirect("index.php?option=com_guru&controller=guruCustomers&task=edit");
		}
	}
	
	function listCustomers() {
		$view = $this->getView("guruCustomers", "html");
		$view->setModel($this->_model, true);
		$view->display();
	}

	function editCustomer() {
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("guruCustomers", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);
		$view->editForm();
	}
	
	function addCustomer(){
		$view = $this->getView("guruCustomers", "html");
		$view->setLayout("addForm");
		$view->setModel($this->_model, true);
		$view->addForm();
	}

	function save(){		
		if($this->_model->store()){
			$msg = JText::_('GURU_CUST_SAVED');
		} 
		else{
			$msg = JText::_('GURU_CUST_SAVEFAIL');
		}
		$link = "index.php?option=com_guru&controller=guruCustomers";
		$this->setRedirect($link, $msg);
	}
	
	function apply(){				
		$result = $this->_model->store(); 
		$userId = $result["id"];
		if($result["error"] === TRUE){
			$msg = JText::_('GURU_CUST_APPLY');
		} 
		else{
			$msg = JText::_('GURU_CUST_APPLYFAIL');
		}
		$link = "index.php?option=com_guru&controller=guruCustomers&task=edit&cid[]=".$result["id"];
		$this->setRedirect($link, $msg);
	}	

	function remove(){		
		if($this->_model->remove()){
			$msg = JText::_('GURU_CUST_REMSUCC');
		} 
		else{
			if(isset($_SESSION["cust_is_enrolled"]) && $_SESSION["cust_is_enrolled"] == 1){
				$msg = JText::_('GURU_CUST_REMFAIL').JText::_('GURU_CUST_REMFAIL_IS_ENROLLED');
				unset($_SESSION["cust_is_enrolled"]);
			}
			else{
				$msg = JText::_('GURU_CUST_REMFAIL');
			}
		}		
		$link = "index.php?option=com_guru&controller=guruCustomers";
		$this->setRedirect($link, $msg);
	}

	function cancel(){
	 	$msg = JText::_('GURU_CUST_CANCEL');
		$link = "index.php?option=com_guru&controller=guruCustomers";
		$this->setRedirect($link, $msg);
	}
	function block(){
		$const = "";
		if(JRequest::getVar("task")=="block"){
			$const = "BLOCK";
		}
		else{
			$const = "UNBLOCK";
		}
		if ($this->_model->block() ){
			$msg = JText::_('GURU_STUD_'.$const);
		} 
		else{
			$msg = JText::_('GURU_STUD_'.$const);
		}
		$link = "index.php?option=com_guru&controller=guruCustomers";
		$this->setRedirect($link, $msg, "notice");
	}
	function resetCourses(){
		$return = $this->_model->resetCourses(); 
		$userId = JRequest::getVar("id", "0");
		$type = "";
		$db = JFactory::getDBO();
		$sql = "select `name` from #__guru_program where id=".intval($return);
		$db->setQuery($sql);
		$db->query();
		$cname = $db->loadColumn();
		
		if(intval($return) > 0){
			$msg = JText::_('GURU_RESET_SUCCESSFULLY1').$cname["0"].JText::_('GURU_RESET_SUCCESSFULLY2');
			$type = "message";
		} 
		else{
			$msg = JText::_('GURU_ORDFAILED');
			$type = "error";
		}
		
		$link = "index.php?option=com_guru&controller=guruCustomers&task=edit&cid[]=".$userId;
		$this->setRedirect($link, $msg, $type);
	}
};
?>