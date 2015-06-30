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
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'database'.DS.'database.php');

$config = new JConfig();

$db = JFactory::getDBO();; 
$query="update #__guru_certificates set design_background ='' where id='1'";
$db->setQuery($query);
if($db->query()){
	$image_selected = JRequest::getVar("image_selected", "");
	if(trim($image_selected) != ""){
		$image_selected_array = explode("/", $image_selected);
		$image_selected = $image_selected_array[count($image_selected_array) - 1];
		
		unlink(JPATH_SITE.DS."images".DS."stories".DS."guru".DS."certificates".DS.$image_selected);
		unlink(JPATH_SITE.DS."images".DS."stories".DS."guru".DS."certificates".DS."thumbs".DS.$image_selected);
	}
	echo "2";
}
else{
	echo $query;
}
?>