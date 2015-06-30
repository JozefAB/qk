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
define( '_JEXEC', 1 );
define('JPATH_BASE', substr(substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), "administra")),0,-1));

define( 'DS', DIRECTORY_SEPARATOR );
$parts = explode(DS, JPATH_BASE);
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once ( JPATH_BASE .DS.'configuration.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'base'.DS.'adapter.php');
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'database'.DS.'database.php');
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'filesystem'.DS.'folder.php');

$config = new JConfig();
$db = JFactory::getDBO();		
$id = $_REQUEST["id"];
$task = $_REQUEST["task"];
if($task == 'addcourse'){
	$sql = "select `published` from #__guru_program where id =".$id;
	$db->setQuery($sql);
	$db->query();
	$published = $db->loadColumn();
	$published = $published["0"];
	
	if($published){
		$sql = "update #__guru_program set published='0' where id =".$id;
		$ret = 'unpublish';
	}
	else{
		$ret = 'publish';
		$sql = "update #__guru_program set published='1' where id =".$id;
	}	
}
else{
	$sql = "select `published` from #__guru_media where id =".$id;
	$db->setQuery($sql);
	$db->query();
	$published = $db->loadColumn();
	$published = $published["0"];
	
	if($published){
		$sql = "update #__guru_media set published='0' where id =".$id;
		$ret = 'unpublish';
	}
	else{
		$ret = 'publish';
		$sql = "update #__guru_media set published='1' where id =".$id;
	}
}
$db->setQuery($sql);
if (!$db->query() ){
	$this->setError($db->getErrorMsg());
	return false;
}

echo $ret;
die();
?>	