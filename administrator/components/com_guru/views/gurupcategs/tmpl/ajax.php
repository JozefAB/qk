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
define('JPATH_BASE', substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), "/administra")) );

if (!isset($_SERVER["HTTP_REFERER"])) exit("Direct access not allowed.");
include("../../../../../../configuration.php");

$mosConfig_absolute_path =substr(JPATH_BASE, 0, strpos(JPATH_BASE, "/administra")); 

define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
$app = JFactory::getApplication('administrator');

$app = JFactory::getApplication('site');

$app->initialise();

$config = new JConfig();
$dbhost = $config->host;
$dbname = $config->db; 
$dbuser = $config->user;
$dbpass = $config->password;
$dbprefix = $config->dbprefix;

$db = mysql_connect($dbhost, $dbuser, $dbpass) or die("Error: 1"); 
mysql_select_db($dbname, $db) or die("Error: 2"); 
if(isset($_REQUEST['id']) && $_REQUEST['id']>0){
	$query="update ".$dbprefix."guru_category set image='' where id='".intval($_REQUEST['id'])."'";
	if(mysql_query($query))
		echo true;
	else echo $query;
}




	
