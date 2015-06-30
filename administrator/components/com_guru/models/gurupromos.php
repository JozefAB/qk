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

jimport('joomla.application.component.modellist');
jimport('joomla.utilities.date');



class guruAdminModelguruPromos extends  JModelLegacy {
	var $_packages;
	var $_package;
	var $_tid = null;
	var $_total = 0;
	var $_pagination = null;
	protected $_context = 'com_guru.guruPromos';

	function __construct () {
		parent::__construct();
		$cids = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int)$cids[0]);
		$mainframe =JFactory::getApplication();

		global $option;
		// Get the pagination request variables
		$limit = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );
		
		if(JRequest::getVar("limitstart") == JRequest::getVar("old_limit")){
			JRequest::setVar("limitstart", "0");		
			$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
			$limitstart = $mainframe->getUserStateFromRequest($option.'limitstart', 'limitstart', 0, 'int');
		}
		
		$this->setState('limit', $limit); // Set the limit variable for query later on
		$this->setState('limitstart', $limitstart);	
		
	}
	
	
	
   	function getPagination() {
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))	{
			jimport('joomla.html.pagination');
			if (!$this->_total) $this->getItems();
			$this->_pagination = new JPagination( $this->_total, $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}

	function setId($id) {
		$this->_tid = $id;
		$this->_package = null;
	}
	
	function getcourses(){
		$db = JFactory::getDBO();
		$sql = "SELECT id, name FROM #__guru_program";	
		$db->setQuery($sql);
		$db->query();
		$courses = $db->loadObjectList();
		return $courses;
	}
	function getCoursesPromo(){
		$db = JFactory::getDBO();
		$promo_id = JRequest::getVar("promo_id", "");
		$sql = "SELECT courses_ids FROM #__guru_promos where id=".intval($promo_id);	
		$db->setQuery($sql);
		$db->query();
		$courses = $db->loadColumn();
		$courses_array = explode("|",$courses["0"]);
		$courses_array = array_values(array_filter($courses_array));
		if(count($courses_array) > 0){
			$courses_array = " where id IN(".implode(",",$courses_array ).")";
		}
		else{
			$courses_array = " where id IN(0)";
		}
		$sql = "SELECT id, name FROM #__guru_program".$courses_array;	
		$db->setQuery($sql);
		$db->query();
		$courses = $db->loadObjectList();
		return $courses;
		
	
	}
	
	protected function getListQuery(){
        $db = JFactory::getDBO();
		$condition=NULL;$publ=NULL;
			if(isset($_POST['search_promos'])){
				$cond= addslashes(($_POST['search_promos']));
				$_SESSION['search_promos']=$cond;
				if($cond!=''){
					$condition="AND c.title LIKE '%".$cond."%' OR c.code LIKE '%".$cond."%' ";
				}
			} elseif (isset($_SESSION['search_promos'])) {
				$cond=$_SESSION['search_promos'];
				if($cond!=''){
					$condition="AND c.title LIKE '%".$cond."%' OR c.code LIKE '%".$cond."%' ";
				}
			}
			
			if(isset($_SESSION['promos_publ_status'])){
				if($_SESSION['promos_publ_status']=='Y') {
					$publ=" AND c.published=1 ";
				} elseif ($_SESSION['promos_publ_status']=='N') {
					$publ=" AND c.published=0 ";
				} else {
					$publ=NULL;
				}
			}
			
			if(isset($_POST['promos_publ_status'])){
				if($_POST['promos_publ_status']=='Y') {
					$publ=" AND c.published=1 ";
				} elseif ($_POST['promos_publ_status']=='N') {
					$publ=" AND c.published=0 ";
				} else {
					$publ=NULL;				
				}
			}
			
			$sql = "SELECT * FROM #__guru_promos AS c WHERE 1=1 ".$condition.$publ." ORDER BY id DESC";

		return $sql;
	}
	
	function getItems(){
		$config = new JConfig();	
		$app = JFactory::getApplication('administrator');
		$db = JFactory::getDBO();
		$sql = $this->getListQuery();
		$limitstart=$this->getState('limitstart');
		$limit=$this->getState('limit');
			
		if($limit!=0){
			$limit_cond=" LIMIT ".$limitstart.",".$limit." ";
		} else {
			$limit_cond = NULL;
		}
		$result = $this->_getList($sql.$limit_cond);
		$this->_total = $this->_getListCount($sql);
		return $result;
	}

	function getPromo() {
		if (empty ($this->_package)) {
			$this->_package = $this->getTable("guruPromos");
			$this->_package->load($this->_tid);
			$data = JRequest::get('post');
			
			if (!$this->_package->bind($data)){
				JError::raiseWarning( 500, $db->getErrorMsg() );
				return false;
	
			}
	
			if (!$this->_package->check()) {
				JError::raiseWarning( 500, $db->getErrorMsg() );
				return false;
	
			}
		}
		return $this->_package;
		
	}
	
	
	function store () {
		$item = $this->getTable('guruPromos');
		$data = JRequest::get('post');
		$id = JRequest::getVar("id","0","post","int");
		$data["courses_ids"] = implode("||", $data["cid"]);
		$db = JFactory::getDBO();
		if($id !=0){
			$sql = "SELECT count(title)  FROM #__guru_promos
			WHERE title ='".$data['title']."' and id<> ".intval($id)."";
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadColumn();
			if($count[0] >0){
				$msg = JText::_('GURU_PROMO_TITLE_EXISTS');
				$app = JFactory::getApplication();
				$app->redirect('index.php?option=com_guru&controller=guruPromos', $msg,'warning');
			 
			}
			
			$sql = "SELECT count(title)  FROM #__guru_promos
			WHERE code ='".$data['code']."' and id<> ".intval($id)."";
			$db->setQuery($sql);
			$db->query();
			$countt = $db->loadColumn();
			
			if($countt[0] >0){
				$msg = JText::_('GURU_PROMO_CODE_EXISTS');
				$app = JFactory::getApplication();
				$app->redirect('index.php?option=com_guru&controller=guruPromos', $msg,'warning');
			 
			}
		}
		else{
			$sql = "SELECT count(title)  FROM #__guru_promos
			WHERE title ='".$data['title']."'";
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadColumn();
			if($count[0] >0){
				$msg = JText::_('GURU_PROMO_TITLE_EXISTS');
				$app = JFactory::getApplication();
				$app->redirect('index.php?option=com_guru&controller=guruPromos', $msg,'warning');
			 
			}
			
			$sql = "SELECT count(title)  FROM #__guru_promos
			WHERE code ='".$data['code']."'";
			$db->setQuery($sql);
			$db->query();
			$countt = $db->loadColumn();
			
			if($countt[0] >0){
				$msg = JText::_('GURU_PROMO_CODE_EXISTS');
				$app = JFactory::getApplication();
				$app->redirect('index.php?option=com_guru&controller=guruPromos', $msg,'warning');
			 
			}
		
		
		}
		$data['code'] = strtolower($data['code']);
		$db = JFactory::getDBO();
		if($data['codestart']==JText::_('GURU_TODAY') || $data['codestart'] == "" ){
			$data['codestart']=date('Y-m-d', time());
		}
		$data['codestart'] = date('Y-m-d H:i:s', strtotime($data['codestart']));
		
		if( $data['codeend'] !='Never' && $data['codeend'] != ''){
			$data['codeend'] = date('Y-m-d H:i:s', strtotime($data['codeend']));
		}
		if (!$item->bind($data)){
			JError::raiseWarning( 500, $db->getErrorMsg() );
			return false;

		}

		if (!$item->check()) {
			JError::raiseWarning( 500, $db->getErrorMsg() );
			return false;

		}
		
		if (!$item->store()) {
			JError::raiseWarning( 500, $db->getErrorMsg() );
			return false;
		}
		$sql = "SELECT id  FROM #__guru_promos
		WHERE code ='".$data['code']."'";
		$db->setQuery($sql);
		$db->query();
		$id = $db->loadColumn();
		if(isset($id[0]) && $id[0] !="" && $id[0] !=NULL){
					return $id[0];

		}
		else{
			return false;
		}
	}	

	function delete () {
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$item = $this->getTable('guruPromos');
		foreach ($cids as $cid) {
			if (!$item->delete($cid)) {
				$this->setError($item->getErrorMsg());
				return false;

			}
		}

		return true;
	}


	function publish () {
		$db = JFactory::getDBO();
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$task = JRequest::getVar('task', '', 'post');
		$item = $this->getTable('guruPromos');
		if ($task == 'publish')
			$sql = "update #__guru_promos set published='1' where id in ('".implode("','", $cids)."')";
		
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
		return 1;
	}
	
	function unpublish () {
		$db = JFactory::getDBO();
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$task = JRequest::getVar('task', '', 'post');
		$item = $this->getTable('guruPromos');
		if ($task == 'unpublish')
			$sql = "update #__guru_promos set published='0' where id in ('".implode("','", $cids)."')";		

		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
		return -1;
	}
	
	public static function getConfig(){
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__guru_config WHERE id = '1' ";
		$db->setQuery($sql);
		if (!$db->query()) {
			echo $db->stderr();
			return;
		}
		$res = $db->loadObject();
		return $res;
	}

};
?>