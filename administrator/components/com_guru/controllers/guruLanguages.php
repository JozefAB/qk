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

class guruAdminControllerguruLanguages extends guruAdminController {
	var $_model = null;
	
	function __construct () {
		parent::__construct();
		$this->registerTask ("add", "edit");
		$this->registerTask ("", "listLanguages"); 
		$this->_model = $this->getModel("guruLanguages");
	}

	function edit () { 
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("guruLanguages", "html");
		$view->setLayout("editForm");
		$view->editForm();
	}

	function save () {
		$admin= JPATH_SITE . "/administrator/language/en-GB/en-GB.com_guru.ini" ;
		@chmod($admin,0777);
		$fp=@fopen($admin,"w");	
		fwrite($fp,stripslashes(JArrayHelper::getValue($_POST,'admin_file')));
		
		fclose($fp);
		$front=JPATH_SITE . "/language/en-GB/en-GB.com_guru.ini" ;
		@chmod($front,0777);
		$fp=@fopen($front,"w");
		fwrite($fp,stripslashes(JArrayHelper::getValue($_POST,'front_file')));
		fclose($fp);
		$msg = JText::_('GURU_LANG_SAVED');
		$link = "index.php?option=com_guru&controller=guruLanguages";
		$this->setRedirect($link, $msg);
	}
	
	function apply () {
		$admin= JPATH_SITE . "/administrator/language/en-GB/en-GB.com_guru.ini" ;
		@chmod($admin,0777);
		$fp=@fopen($admin,"w");
		fwrite($fp,stripslashes(JArrayHelper::getValue($_POST,'admin_file')));
		fclose($fp);
		$front=JPATH_SITE . "/language/en-GB/en-GB.com_guru.ini" ;
		@chmod($front,0777);
		$fp=@fopen($front,"w");
		fwrite($fp,stripslashes(JArrayHelper::getValue($_POST,'front_file')));
		fclose($fp);
		$msg = JText::_('GURU_LANG_SAVED');
		$link = "index.php?option=com_guru&controller=guruLanguages&task=edit";
		$this->setRedirect($link, $msg);
	}	

	function upload () {
		$msg = $this->_model->upload();
		$link = "index.php?option=com_guru&controller=guruLanguages";
		$this->setRedirect($link, $msg);
	}
	
	function cancel () {
	 	$msg = JText::_('GURU_LANG_CANCEL');
		$link = "index.php?option=com_guru&controller=guruLanguages";
		$this->setRedirect($link, $msg);
	}
};

?>