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
$document = JFactory::getDocument();

class guruAdminController extends JControllerLegacy {

	function __construct() {
		parent::__construct();
		
		$ajax_req = JRequest::getVar("no_html", 0, "request");
		$squeeze = JRequest::getVar("sbox", 0, "request");
		$squeeze2 = JRequest::getVar("tmpl", 0, "request");
		$task = JRequest::getVar("task");
		$export = JRequest::getVar("export", "");
		$export1 = JRequest::getVar("export1", "");
		
		if($export != "" || $export1 != ""){
			// do nothing
		}
		elseif(!$ajax_req && $task != "savesbox"&& $task != "save2" && $task != "export_button" && $task != "export" && $task != "savequizzes" && $task !="savequestionedit" && $task != "savequestion"){
			$document = JFactory::getDocument();
			$document->addStyleSheet("components/com_guru/css/general.css");
			$document->addStyleSheet("components/com_guru/css/tmploverride.css");
			
			$document->addStyleSheet( 'components/com_guru/css/bootstrap.min.css' );
			$document->addStyleSheet( 'components/com_guru/css/font-awesome.min.css' );
			$document->addStyleSheet( 'components/com_guru/css/ace-fonts.css' );
			$document->addStyleSheet( 'components/com_guru/css/ace.min.css' );
			$document->addStyleSheet( 'components/com_guru/css/fullcalendar.css' );
			$document->addStyleSheet( 'components/com_guru/css/g_admin_modal.css' );
			
			require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'chtmlinput.php');

  			$view = $this->getView('guruDtree', 'html');
  			if (!$squeeze2 && !$squeeze){
	  		?>
	  		
			<?php
				$view->showDtree();
				?>
					
			<?php
  			}
		}

	}

	function display ($cachable = false, $urlparams = array()) {
		parent::display($cachable, $urlparams);	
	}

	function debugStop($msg = ''){
       	$app = JFactory::getApplication('administrator');
	  	echo $msg;
		$app->close();
	}
};

?>
