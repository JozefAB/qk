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
define('JPATH_BASE', substr(substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), "components")),0,-1));

define( 'DS', DIRECTORY_SEPARATOR );
$parts = explode(DS, JPATH_BASE);
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once ( JPATH_BASE .DS.'configuration.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'database'.DS.'database.php');

$config = new JConfig();
$db = JFactory::getDBO();

$action = JRequest::getVar("action", "");

if($action == "publish"){
	$v = JRequest::getVar("v", "0");
	$table = "#__guru_questions";
	if($v == 1){
		$table = "#__guru_quiz";
	}
	
	$id = JRequest::getVar("id", "0");
	$sql = "update ".$table." set `published`='1' where `id`=".intval($_REQUEST['id']);
	$db->setQuery($sql);
	if(!$db->query()){
		return false;
	}
	return true;
	die();
}
elseif($action == "unpublish"){
	$v = JRequest::getVar("v", "0");
	$table = "#__guru_questions";
	if($v == 1){
		$table = "#__guru_quiz";
	}
	
	$id = JRequest::getVar("id", "0");
	$sql = "update ".$table." set `published`='0' where `id`=".intval($_REQUEST['id']);
	$db->setQuery($sql);
	if(!$db->query()){
		return false;
	}
	return true;
	die($sql);
}


$deleted = $_REQUEST['deleted'];
$f = $_REQUEST['f'];
if($f == 0){
	if(isset($_REQUEST['id']) && $_REQUEST['id']>0){ 
		$query = "DELETE FROM #__guru_questions WHERE id=".$deleted." and qid=".$_REQUEST['id']."";
		$db->setQuery($query);
		if($db->query()){
			echo "2";
		}
		else{
			echo $query;
		}
	}	
}
elseif($f == 1){
	$query = "select quizzes_ids from #__guru_quizzes_final where qid=".$_REQUEST['id'];
	$db->setQuery($query);
	$db->query();
	$result=$db->loadResult();
	$newvalues = str_replace(",".$deleted, "",$result );


	if(isset($_REQUEST['id']) && $_REQUEST['id']>0){ 
		$query = "update #__guru_quizzes_final set quizzes_ids='".$newvalues."' where qid=".$_REQUEST['id'];
		$db->setQuery($query);
		if($db->query()){
			echo "2";
		}
		else{
			echo $query;
		}
	}	
}
?>