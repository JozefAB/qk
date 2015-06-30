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

class guruAdminControllerguruMediacategs extends guruAdminController {
	var $_model = null;
	
	function __construct () {
		parent::__construct();		
		$this->registerTask("add", "edit");
		$this->registerTask("", "listMediaCategs");
		$this->registerTask("edit", "edit");
		$this->registerTask("cancel", "cancel");
		$this->registerTask("save", "save");	
		$this->registerTask("apply", "apply");
		$this->_model = $this->getModel("guruMediacategs");
	}

	function listMediaCategs(){
		$view = $this->getView("guruMediacategs", "html");
		$view->setModel($this->_model, true);
		$view->display();
	}
	
	function edit(){
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("guruMediacategs", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);
		$model = $this->getModel("guruMediacategs");
		$view->setModel($model);
		$view->editForm();
	}
	
	function save(){
		if($id = $this->_model->store()){
			$msg = JText::_('CATEGORYSAVED');
		} 
		else{
			$msg = JText::_('CATEGORYSAVEFAILED');
		}		
		$link = "index.php?option=com_guru&controller=guruMediacategs";
		$this->setRedirect($link, $msg);
	}

	function apply(){		
		$id = $this->_model->store();
		if($id){
			$msg = JText::_('CATEGORYSAVED');	
		} 
		else{
			$msg = JText::_('CATEGORYSAVEFAILED');
		}
		$link = "index.php?option=com_guru&controller=guruMediacategs&task=edit&id=".$id['1'];
		$this->setRedirect($link, $msg);
	}

	function cancel(){
		$msg = JText::_('GURU_MEDIACANCEL');	
		$link = "index.php?option=com_guru&controller=guruMediacategs";
		$this->setRedirect($link,$msg);
	}
	
	function remove(){
		if(!$this->_model->remove()){
			$msg = JText::_('GURU_CATEGORIES_UNSUCCESSFULLY_DELETED');
		} 
		else{
		 	$msg = JText::_('GURU_CATEGORIES_SUCCESSFULLY_DELETED');
		}		
		$link = "index.php?option=com_guru&controller=guruMediacategs";
		$this->setRedirect($link, $msg);
	}	
	
	function unpublish(){
		if($this->_model->unpublish()){
			$msg = JText::_("GURU_CATEGORY_UNPUBLISHED");
			$this->setRedirect('index.php?option=com_guru&controller=guruMediacategs', $msg);
		}
		else{
			$msg = JText::_("GURU_CATEGORY_UNPUBLISHED_ERROR");
			$this->setRedirect('index.php?option=com_guru&controller=guruMediacategs', $msg, "notice");
		}
	}
	
	function publish(){
		if($this->_model->publish()){
			$msg = JText::_("GURU_CATEGORY_PUBLISHED");
			$this->setRedirect('index.php?option=com_guru&controller=guruMediacategs', $msg);
		}
		else{
			$msg = JText::_("GURU_CATEGORY_PUBLISHED_ERROR");
			$this->setRedirect('index.php?option=com_guru&controller=guruMediacategs', $msg, "notice");
		}
	}

};

?>