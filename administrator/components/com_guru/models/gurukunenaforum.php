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
jimport ("joomla.aplication.component.model");


class guruAdminModelguruKunenaForum extends JModelLegacy {
	var $_packages;
	var $_package;
	var $_tid = null;
	var $_total = 0;
	var $_pagination = null;

	
	function savekunenadetails() {
		$post_value = JRequest::get('post',JREQUEST_ALLOWRAW);
		$db = JFactory::getDBO();
		
		$sql = "select count(*) from #__extensions where element='com_kunena'";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadResult();
		if($count >0){
			$sql = "UPDATE #__guru_kunena_forum set `forumboardcourse`='".$post_value["autoforumk"]."', `forumboardlesson`= '".$post_value["autoforumk1"]."',	`forumboardteacher` ='". $post_value["autoforumk2"]."', `deleted_boards`='".$post_value["deleted_boards"]."', `allow_stud`='".$post_value["allow_stud"]."', `allow_edit`='".$post_value["allow_edit"]."',`allow_delete`='".$post_value["allow_delete"]."' ";
			$db->setQuery($sql);
			$db->query();
			return true;
		}
		else{
			return false;
		}
	}
	
	function getKunenaforumDetails(){
	  	$db = JFactory::getDBO();
		$sql = "SELECT * from #__guru_kunena_forum where id='1'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadObject();
		return $result;
	}

};
?>