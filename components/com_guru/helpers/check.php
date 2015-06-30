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

$task = JRequest::getVar("task", "");
if($task == "user.login" || $task == "user.logout"){
	$username = JRequest::getVar("username", "");
    $password = JRequest::getVar("password", "");
	$return = JRequest::getVar("return", "");
	define(JPATH_COMPONENT, JPATH_SITE.DS."components".DS."com_users");
	
	require_once(JPATH_SITE.DS."components".DS."com_users".DS."controllers".DS."user.php");
	if($task == "user.login"){
		UsersControllerUser::login();
	}
	else{
		UsersControllerUser::logout();
	}
}
?>