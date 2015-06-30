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


$day_id = $_GET['day_id'];
$screen_id = $_GET['screen_id'];

define( '_JEXEC', 1 );
define('JPATH_BASE' , 1);
include_once("../../../../../configuration.php");
include_once("../../../../../libraries/joomla/base/object.php");
include_once("../../../../../libraries/joomla/database/database.php");
include_once("../../../../../libraries/joomla/database/database/mysql.php");

$config = new JConfig();
$options = array ("host" => $config->host,"user" => $config->user,"password" => $config->password,"database" => $config->db,"prefix" => $config->dbprefix);
$database = new JDatabaseMySQL($options);

$sql = " SELECT ordering FROM #__guru_days WHERE id = ".$day_id;
$database->setQuery($sql);
if (!$database->query()) {
	echo $database->stderr();
	return;
}
$ordering = $database->loadResult();

$sql = " SELECT id, days, tasks FROM #__guru_programstatus WHERE pid = (SELECT pid FROM #__guru_days WHERE id = '".$day_id."')";
$database->setQuery($sql);
if (!$database->query()) {
	echo $database->stderr();
	return;
}
$ids = $database->loadObjectList();

foreach($ids as $one_id)
	{
		$task_array = explode(';',$one_id->tasks);
		$old_task_array = $task_array[$ordering-1];
		if($old_task_array == '')
			$new_task_array = $old_task_array.$screen_id.'-0';
		else
			$new_task_array = $old_task_array.','.$screen_id.'-0';	
		$task_array[$ordering-1] = $new_task_array;
		$new_task_array = implode(';', $task_array);
		$sql = "update #__guru_programstatus set tasks='".$new_task_array."' where id =".$one_id->id;
		$database->setQuery($sql);
		$database->query();		
	}

$sql = "INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`) VALUES ('dtask','".$day_id."','".$screen_id."')";
$database->setQuery($sql);
$database->Query();	

?>