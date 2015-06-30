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
define('JPATH_BASE', substr(substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), "administra")),0,-1));

define( 'DS', DIRECTORY_SEPARATOR );
$parts = explode(DS, JPATH_BASE);
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once ( JPATH_BASE .DS.'configuration.php' );
include_once( JPATH_BASE .DS. "libraries" .DS. "joomla" .DS. "object" .DS. "object.php" );
include_once( JPATH_BASE .DS. "libraries" .DS. "joomla" .DS. "database" .DS. "database.php" );

$config = new JConfig();
$options = array ("host" => $config->host,"user" => $config->user,"password" => $config->password,"database" => $config->db,"prefix" => $config->dbprefix);
$db = JFactory::getDBO();


if(isset($_REQUEST['id']) && $_REQUEST['id']>0){
	$sql4="UPDATE `#__guru_task` t SET forum_kunena_generatedt = 2 WHERE t.id=".intval($_REQUEST['id']);
	$db->setQuery($sql4);
	$db->query($sql4);
	
	
	$sql1="select  catidkunena from #__guru_kunena_lessonslinkage where idlesson='".intval($_REQUEST['id'])."' order by id desc limit 0,1";
	$db->setQuery($sql1);
	$result = $db->loadResult();
	
	
	$sql2 = "delete from #__kunena_categories where id=".$result;
	$db->setQuery($sql2);
	$db->query($sql2);
	
	$sql3 = "delete from #__kunena_aliases where item=".$result;
	$db->setQuery($sql3);
	$db->query($sql3);
	

	$_SESSION["lesson_removed"]="yes";
	
}