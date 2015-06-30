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

class guruControllerguruQuiz extends guruController {
	var $_model = null;
	
	function __construct () {

		parent::__construct();

		$this->registerTask ("", "listQuiz");

		$this->_model = $this->getModel("guruQuiz");
	}

	function listQuiz() {

		$view = $this->getView("guruQuiz", "html");
		$view->setModel($this->_model, true);

		$view->display();


	}

	function edit () {
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("guruQuiz", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);
	
		$view->editForm();

	}
	
	function creat () { 
		$view = $this->getView("adagencyReports", "html");
		$view->setModel($this->_model, true);

		$view->display();

	}
	
	function emptyrep () { 
		$view = $this->getView("adagencyReports", "html");
		$view->setModel($this->_model, true);

		$view->emptyrep();

	}


	function save () {
		if ($this->_model->store() ) {

			$msg = JText::_('LANGSAVED');
		} else {
			$msg = JText::_('LANGSAVEFAILED');
		}
		$link = "index.php?option=com_guru&view=guruQuiz";
		$this->setRedirect($link, $msg);

	}

	function upload () {
		$msg = $this->_model->upload();

		$link = "index.php?option=com_guru&view=guruQuiz";
		$this->setRedirect($link, $msg);
		
	}

	function remove () {
		if (!$this->_model->delete()) {
			$msg = JText::_('LANGREMERROR');
		} else {
		 	$msg = JText::_('LALNGREMSUCC');
		}
		
		$link = "index.php?option=com_guru&view=guruQuiz";
		$this->setRedirect($link, $msg);
		
	}

	function cancel () {
	 	$msg = JText::_('LANGCANCELED');	
		$link = "index.php?option=com_guru&view=guruQuiz";
		$this->setRedirect($link, $msg);


	}

	function publish () {
		$res = $this->_model->publish();
		if (!$res) {
			$msg = JText::_('LANGPUBLICHERROR');
		} elseif ($res == -1) {
		 	$msg = JText::_('LANGUNPUBSUCC');
		} elseif ($res == 1) {
			$msg = JText::_('LANGPUBSUCC');
		} else {
                 	$msg = JText::_('LANGUNSPECERROR');
		}
		
		$link = "index.php?option=com_guru&view=guruQuiz";
		$this->setRedirect($link, $msg);


	}
	
	function addquestion () {
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruQuiz", "html");
		$view->setLayout("addquestion");
		$view->setModel($this->_model, true);
		$view->addquestion();

	}
	function savequestion () {
		$qtext = mysql_escape_string($_POST['text']);
		$quizid = intval($_POST['quizid']);
		$a1 = mysql_escape_string($_POST['a1']);
		$a2 = mysql_escape_string($_POST['a2']);
		$a3 = mysql_escape_string($_POST['a3']);
		$a4 = mysql_escape_string($_POST['a4']);
		$a5 = mysql_escape_string($_POST['a5']);
		$a6 = mysql_escape_string($_POST['a6']);
		$a7 = mysql_escape_string($_POST['a7']);
		$a8 = mysql_escape_string($_POST['a8']);
		$a9 = mysql_escape_string($_POST['a9']);
		$a0 = mysql_escape_string($_POST['a0']);
		$answers = $_POST['1a'].$_POST['2a'].$_POST['3a'].$_POST['4a'].$_POST['5a'].$_POST['6a'].$_POST['7a'].$_POST['8a'].$_POST['9a'].$_POST['0a'];
		$this->_model->addquestion($qtext,$quizid,$a1,$a2,$a3,$a4,$a5,$a6,$a7,$a8,$a9,$a0,$answers);
	}

};

?>