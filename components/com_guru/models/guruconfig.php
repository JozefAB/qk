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


class guruModelguruConfig extends JModelLegacy {
	var $_configs = null;
	var $_id = null;

	function __construct () {
		parent::__construct();
		$this->_id = 1;

	}

	function getConfig() {
		$db=  JFactory::getDBO();
		/*if (empty ($this->_configs)) {
			$this->_configs = $this->getTable("guruConfig");
			$this->_configs->load($this->_id);
		}*/
		$sql="SELECT * 
			  FROM #__guru_config
			  WHERE id=".$this->_id;
		$db->setQuery($sql);
		$db->query();
		$this->_configs= $db->loadObjectList();
		var_dump($this->_configs); die;
		return $this->_configs;

	}

	function store () {
		$item = $this->getTable('guruConfig');
		/*$imagepath = str_replace("/administrator","",JPATH_BASE);
		$imagepath = $imagepath."/images/stories/";
		$newimgfolder = $_POST['imgfolder'];
		
		if ( !is_dir ( $imagepath.$newimgfolder ) ) {
	       @mkdir ( $imagepath."/".$newimgfolder );
	       @chmod ( $imagepath."/".$newimgfolder, 0777 ); }
	     else {
	       @chmod ( $imagepath."/".$newimgfolder, 0777 ); 
	    }    
	    
		*/
		
		if (isset($_POST['btnback'])) $_POST['btnback']=1; else $_POST['btnback']=0;
		if (isset($_POST['btnhome'])) $_POST['btnhome']=1; else $_POST['btnhome']=0;
		if (isset($_POST['btnnext'])) $_POST['btnnext']=1; else $_POST['btnnext']=0;
		if (isset($_POST['dofirst'])) $_POST['dofirst']=1; else $_POST['dofirst']=0;
		$data = JRequest::get('post');	
		$database =  JFactory::getDBO();
		
		if (!$item->bind($data)){
			return JError::raiseError( 500, $database->getErrorMsg() );
			return false;

		} 
		if (!$item->check()) {
			return JError::raiseError( 500, $database->getErrorMsg() );
			return false;

		}
		
       $item->taskpage = $_POST['taskpage'];
       $item->daypage = $_POST['daypage'];
       $item->ctgpage = $_POST['ctgpage'];
       $item->pggpage = $_POST['pggpage'];
       $item->pgpage = $_POST['pgpage'];
       $item->taskpage = $_POST['taskpage'];
      
		if (!$item->store()) {
			return JError::raiseError( 500, $database->getErrorMsg() );
			return false;

		}
		
		return true;

	}	
};
?>