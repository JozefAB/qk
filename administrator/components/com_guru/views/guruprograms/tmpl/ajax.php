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

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

define( '_JEXEC', 1 );
define('JPATH_BASE', substr(substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), "administra")),0,-1));

define( 'DS', DIRECTORY_SEPARATOR );
$parts = explode(DS, JPATH_BASE);
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once ( JPATH_BASE .DS.'configuration.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'database'.DS.'database.php');

$config = new JConfig();
$db = JFactory::getDBO();

$action = JRequest::getVar("action", "");
if($action == "check_values"){
	$name = JRequest::getVar("name", "");
	$alias = JRequest::getVar("alias", "");
	$duration = JRequest::getVar("duration", "");
	$id = JRequest::getVar("id", "0");
	$avatar = JRequest::getVar("avatar", "");
	
	if(trim($alias) != ""){
		$sql = "select count(*) from #__guru_program where `alias`='".addslashes(trim($alias))."' and `id` <> ".intval($id);
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		$count = @$count["0"];
		if($count > 0){
			echo "exist";
		}
		else{
			echo "not exist";
		}
	}
	else{
		$sql = "select count(*) from #__guru_program where `name`='".addslashes(trim($name))."' and `id` <> ".intval($id);
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		$count = @$count["0"];
		if($count > 0){
			echo "exist";
		}
		else{
			echo "not exist";
		}
	}
	die();
}

// start for remove image action
if(isset($_REQUEST['id']) && $_REQUEST['id']>0){ 

		
	if($_REQUEST['avatar'] == 0){
		$query = "update #__guru_program set image='' where id='".intval($_REQUEST['id'])."'";
	}
	elseif($_REQUEST['avatar'] == 1){
		$query = "update #__guru_program set image_avatar='' where id='".intval($_REQUEST['id'])."'";
	}
	$db->setQuery($query);
	
	if($db->query()){
		echo "2";
	}
	else{
		echo $query;
	}
}
// end for remove image action
?>