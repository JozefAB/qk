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
$document->addStyleSheet("components/com_guru/css/guru-j30.css");
$document->addStyleSheet("components/com_guru/css/guru-responsive.css");
$document->addStyleSheet("components/com_guru/css/guru_pages.css");
$document->addStyleSheet("components/com_guru/css/guru_layout.css");
$document->addStyleSheet('media/jui/css/bootstrap.min.css');
// load FontAwesome -------------------------------------------
$document->addStyleSheet("components/com_guru/css/font-awesome.min.css");	
// >>>>>>>>>>>>>>>>>>>> *** <<<<<<<<<<<<<<<<<<<<<
$document->addStyleSheet('media/jui/css/bootstrap.min.css');
$db = JFactory::getDBO();
$sql = "SELECT guru_turnoffbootstrap  FROM  `#__guru_config` WHERE id=1";
$db->setQuery($sql);
$db->query();
$guru_turnoffbootstrap = $db->loadResult();

if($guru_turnoffbootstrap != 0){ 
	$document->addStyleSheet("components/com_guru/css/guru-bootstrap.css");	
}

$sql = "SELECT guru_turnoffjq  FROM  `#__guru_config` WHERE id=1";
$db->setQuery($sql);
$db->query();
$guru_turnoffjq = $db->loadResult();

if( $guru_turnoffjq != 0){ 
	$document->addScript('components/com_guru/js/jquery-1.9.1.min.js');	
}
$document->addScript('components/com_guru/js/jquery.height_equal.js');	


class guruController extends JControllerLegacy {
	var $_customer = null;
	function __construct() {
		parent::__construct();
	}

	function display ($cachable = false, $urlparams = Array()){
		parent::display(false, null);	
	}

	function setclick($msg = ''){
	}
};
?>

