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

echo "OK";
exit;


if(isset($_GET['renameId']) && isset($_GET['newName']))	{	// variables are set

	$res = mysql_query("select * from category where ID='".$_GET['renameId']."'");
	if($inf = mysql_fetch_array($res)){
		mysql_query("update category set categoryName='".$_GET['newName']."' where ID='".$inf["ID"]."'") or die("NOT OK");
		echo "OK";	// OK when everything is ok
	}
	echo "NOT OK";	// Node didn't exist -> Message not ok


	
	exit;
}

if(isset($_GET['deleteIds'])){
	
	mysql_query("delete from category where ID IN(".$_GET['deleteIds']."')") or die("NOT OK");
	echo "OK";
}

echo "NOT OK";

