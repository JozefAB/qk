<?php

/*------------------------------------------------------------------------
# com_guru
# ------------------------------------------------------------------------
# author    iJoomla
# copyright Copyright (C) 2013 ijoomla.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.ijoomla.com
# Technical Support:  Forum - http://www.ijoomla.com/forum/index/
-------------------------------------------------------------------------*/


define( '_JEXEC', 1 );
define('JPATH_BASE', substr(substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), "components")),0,-1));
define( 'DS', DIRECTORY_SEPARATOR );

$parts = explode(DS, JPATH_BASE);
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once ( JPATH_BASE .DS.'configuration.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'database'.DS.'database.php');

$db = JFactory::getDBO();

$course_id = JRequest::getVar("course_id", "0");
$sql = "update #__guru_program set `id_final_exam`=0 where `id`=".intval($course_id);
$db->setQuery($sql);
if($db->query()){
	return true;
}
else{
	return false;
}
?>