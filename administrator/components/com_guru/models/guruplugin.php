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


class guruAdminModelguruPlugin extends JModelLegacy {
	
	function __construct () {
		parent::__construct();
		$cids = JRequest::getVar('cid', 0, '', 'array');		
	}

	function getlistPlugins () {
		$db = JFactory::getDBO();
		$sql = "select * from #__extensions where folder='gurupayment'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}

	function publish(){
		$db = JFactory::getDBO();
		$cids = JRequest::getVar('id', array(0), 'post', 'int');
		$task = JRequest::getVar('task', '', 'post');  
		if($task == 'publish'){
			$sql = "update `#__extensions` set enabled='1' where extension_id in ('".$cids."')";
			$res = 1;
		}
		else{
			$sql = "update `#__extensions` set enabled='0' where extension_id in ('".$cids."')";
			$res = -1;
		}			
		$db->setQuery($sql);
		if(!$db->query()){
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $res;
	}
};
?>