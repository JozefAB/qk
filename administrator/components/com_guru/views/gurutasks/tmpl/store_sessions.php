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
	define('JPATH_BASE', substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), "/administra")) );
	include("../../../../../../configuration.php");
	
	$mosConfig_absolute_path =substr(JPATH_BASE, 0, strpos(JPATH_BASE, "/administra")); 
	$config = new JConfig();
	$dbhost = $config->host;
	$dbname = $config->db; 
	$dbuser = $config->user;
	$dbpass = $config->password;
	$dbprefix = $config->dbprefix;
	
	$db = mysql_connect($dbhost, $dbuser, $dbpass) or die("Error: 1"); 
	mysql_select_db($dbname, $db) or die("Error: 2"); 
	
	define( 'DS', DIRECTORY_SEPARATOR );
	
	require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
	$app = JFactory::getApplication('administrator');
	
	$app = JFactory::getApplication('site');
	
	$app->initialise();
	$db = JFactory::getDBO();
	$db_text=JRequest::getVar('dt', NULL, 'get');
	$db_med=JRequest::getVar('dm', NULL, 'get');
	$lay=JRequest::getInt('ldb',0,'get');
	$scr=JRequest::getInt('scr',-1,'get');
	
	$ip=ip2long($_SERVER['REMOTE_ADDR']);	
	$sql="SELECT ip FROM #__guru_media_templay WHERE ip='".$ip."'";
	$db->setQuery($sql);
	$exists=$db->loadResult();
	//var_dump($exists);die();
	if($exists!=NULL) {
		$sql2="DELETE FROM #__guru_media_templay WHERE ip='".$ip."'";
		$db->setQuery($sql2);
		$db->query($sql2);
	}
	$query="INSERT INTO #__guru_media_templay (`ip` , `scr_id` ,`tmp_time` ,`db_lay` ,`db_med` ,`db_text`)
			VALUES ('".$ip."', '".$scr."', NOW(), '".$lay."', '".$db_med."', '".$db_text."');";
	//echo $query;die();
	$db->setQuery($query);
	$db->query();
?>