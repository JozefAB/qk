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

class guruAdminControllerguruTasks extends guruAdminController {
	var $model = null;
	
	function __construct () {
		parent::__construct();
		$this->registerTask ("add", "edit");
		$this->registerTask ("", "listTasks");
		$this->registerTask ("", "addQuiz");
		$this->registerTask ("unpublish", "publish");	
		$this->registerTask ("apply", "apply");	
		$this->_model = $this->getModel("guruTask");
	}

	function listTasks() {
		$view = $this->getView("guruTasks", "html"); 
		$view->setModel($this->_model, true);
		$view->display();
	}
	
	function edit(){
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);
		$view->editForm();
	}
	
	function editsbox(){
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("editformsbox");
		$view->setModel($this->_model, true);
		$view->editForm();
	}

	function addmedia(){
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("addmedia");
		$view->setModel($this->_model, true);
		$view->addmedia();
	}
	
	function addQuiz(){
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("addquiz");
		$view->setModel($this->_model, true);
		$view->addQuiz();
	}
	
	function jumpbts(){
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("jumpbts");
		$view->setModel($this->_model, true);
		$view->jumpbts();
	}
	
	function jumpbts_save(){
		JRequest::setVar ("hidemainmenu", 1);
		JRequest::setVar ("tmpl", "component");
		$pieces = $this->_model->jump_save();
		echo '<script type="text/javascript">window.onload=function(){
				window.parent.document.getElementById("close").click();
				window.parent.jump('.$pieces[1].','.$pieces[0].',"'.$pieces[2].'");
			}</script>';
		echo '<strong>Jump saved. Please wait...</strong>';
	}
	
	function addtext(){
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("addtext");
		$view->setModel($this->_model, true);
		$view->addmedia();
	}	
	
	function addmainmedia(){
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("addmainmedia");
		$view->setModel($this->_model, true);
		$view->addmedia();
	}
	
	function save(){
		$task = JRequest::getVar("task","","post","string");
		if($task=='save2'){
			$this->save2();
		} 
		else {
			$return = $this->_model->store();
			if($return["return"] === TRUE){
				$msg = JText::_('GURU_TASKS_SAVED');
			}
			else {
				$msg = JText::_('GURU_TASKS_NOTSAVED');
			}
			$link = "index.php?option=com_guru&controller=guruTasks";
			$this->setRedirect($link, $msg);
		}
	}
	
	function apply() {
		$task = JRequest::getVar("task","","post","string");
		$return = $this->_model->store();
		if($return["return"] === TRUE){
			$msg = JText::_('GURU_TASKS_SAVED');
		} else {
			$msg = JText::_('GURU_TASKS_NOTSAVED');
		}
		$id = JRequest::getVar("id","","","int");
		$module = JRequest::getVar("module","");
		if($id == ""){
			$id = $return["id"];
		}
		$progrid=JRequest::getVar("day","","","int");
		$link ="index.php?option=com_guru&controller=guruTasks&tmpl=component&task=editsbox&cid[]=".$id."&progrid=".$progrid."&module=".intval($module);	
		$this->setRedirect($link, $msg);
	}
	
	function save2(){
		JRequest::setVar ("hidemainmenu", 1);
		JRequest::setVar ("tmpl", "component");	
		$return = $this->_model->store();
		if($return["return"] === TRUE){
			$msg = JText::_('GURU_TASKS_SAVED');
		} 
		else{
			$msg = JText::_('GURU_TASKS_NOTSAVED');
		}

		echo "Step saved. Please wait...";
		echo "Step saved. Please wait...";
		echo '<script type="text/javascript">window.onload=function(){
			window.parent.location.reload(true);
			}</script>';
	}
	
	function remove(){
		if(!$this->_model->delete()) {
			$msg = JText::_('GURU_TASKS_DELFAILED');
		} 
		else {
		 	$msg = JText::_('GURU_TASKS_DEL');
		}
		$link = "index.php?option=com_guru&controller=guruTasks";
		$this->setRedirect($link, $msg);		
	}
	
	function del(){ 
		$tid = JRequest::getVar('tid','0','get','int');	
		$main = JRequest::getVar('main','0','get','int');	
		$cid = JRequest::getVar('cid',array(),'get','array');	
		$cid = intval($cid[0]);
		if(!$this->_model->delmedia($tid,$cid,$main)){
			$msg = JText::_('GURU_TASKS_DELFAILED2');
		}
		else{
		 	$msg = JText::_('GURU_TASKS_DEL2');
		}
		
		$link = "index.php?option=com_guru&controller=guruTasks&task=edit&cid[]=".$tid;
		$this->setRedirect($link, $msg);
	}

	function cancel(){
	 	$msg = JText::_('GURU_TASKS_PUBCANCEL');
		$link = "index.php?option=com_guru&controller=guruTasks";
		$this->setRedirect($link, $msg);
	}					
	
	function savemedia(){
		$insertit	= JRequest::getVar('idmedia','0','post','int');
		$taskid   	= JRequest::getVar('idtask','0','post','int');
		$mainmedia 	= JRequest::getVar('mainmedia','0','post','int');
		$this->_model->addmedia($insertit, $taskid, $mainmedia);
	}

	function upload() { 
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);
		$view->uploadimage();
		$newid = $this->_model->store();
		$link = "index.php?option=com_guru&controller=guruTasks&task=edit&cid[]=".$newid;
		$this->setRedirect($link, $msg);
	}	
};
?>