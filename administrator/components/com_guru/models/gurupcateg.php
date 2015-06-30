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
class guruAdminModelguruPcateg extends JModelLegacy {
	var $_promos;
	var $_promo;
	var $_id = null;
	var $_total = 0;
	var $_pagination = null;
	protected $_context = 'com_guru.guruPcategs';
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
	function getItems(){
		$app = JFactory::getApplication('administrator');
		
		$db = JFactory::getDBO();
		$query  = "SELECT id, description, name, child_id as cid, parent_id as pid, ordering, published FROM #__guru_category, #__guru_categoryrel WHERE ";
		$query .= "#__guru_category.id=#__guru_categoryrel.child_id ";
		$query .= "ORDER BY #__guru_categoryrel.parent_id ASC ";
		
		$limitstart=$this->getState('limitstart');
		$limit=$this->getState('limit');
			
		if($limit!=0){
			$limit_cond=" LIMIT ".$limitstart.",".$limit." ";
		} else {
			$limit_cond = NULL;
		}
		$result = $this->_getList($query.$limit_cond);
		$this->_total = $this->_getListCount($query);
		return $result;
	}
	function setId($id) {
		$this->_id = $id;
		$this->_promo = null;
	}
	function getCategoryCount(){
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__guru_category";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}
	function getParentId($id){
		$db = JFactory::getDBO();
		$sql = "select parent_id from #__guru_categoryrel where child_id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}	
	function getCateg() {
		if (empty ($this->_promo)) { 
			$this->_promo = $this->getTable("guruPcateg");
			
			if($this->_id > 0){
			} 
			else{
				$id=JRequest::getVar("cid","0","post","int");
				if ($id>0) $this->_id=intval($id);
			}
			$this->_promo->load($this->_id);
			
			$pid = $this->getParentId($this->_id);
			$this->_promo->pid = $pid;
			JRequest::setVar("pid",$pid);
		}
		return $this->_promo;
	}
	
	
	function store () {			
		$db = JFactory::getDBO();
		$item = $this->getTable('guruPcateg');
		
		$data = JRequest::get('post');
		$data['description'] = JRequest::getVar("description","","post","string",JREQUEST_ALLOWRAW);
		if($data['alias']==''){
			$data['alias'] = JFilterOutput::stringURLSafe($data['name']);
		} else {
			$data['alias'] = JFilterOutput::stringURLSafe($data['alias']);
		}
		
		$id = JRequest::getVar("id", "0");
		if(intval($id) == 0){
			$sql = "SELECT max(c.ordering) as maximum
					from #__guru_category c, #__guru_categoryrel cr 
					where c.id = cr.child_id and cr.parent_id = ".intval($data["parentcategory_id"])."
					group by cr.parent_id";
							
			$db->setQuery($sql);
			$db->query();
			$max_order = $db->loadResult();		
			$data["ordering"] = intval($max_order)+1;
		}
		
		if (!$item->bind($data)){
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		if (!$item->check()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		if (!$item->store()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		
		if (intval($data['id']) > 0) {
			//we need to delete the old parent and create new relationship
			$delid = intval($data['id']);
			$ask = "DELETE FROM `#__guru_categoryrel` WHERE `child_id` = ".$delid;
			$db->setQuery($ask);
			$db->query();
			//now we do the new insert
			$theparent = intval($data['parentcategory_id']);
			$ask = "INSERT INTO `#__guru_categoryrel` (`parent_id`, `child_id`) VALUES ({$theparent},{$delid})";
			$db->setQuery($ask);
			$db->query();
			$newid = intval($data['id']);
		} else {
			//inseram in tabela de relatii cu categoriile
			$newid = mysql_insert_id();
			if ($newid > 0) {
				//it'ok
			} else {
				//check for the latest category added
				$ask = "SELECT `id` FROM `#__guru_category` ORDER BY `id` DESC LIMIT 1 ";
				$db->setQuery( $ask );
				$newid = $db->loadResult();				
			}
			//let's do the insert into the relationship table
			$theparent = intval($data['parentcategory_id']);
			$sql = "INSERT INTO `#__guru_categoryrel` (`parent_id`, `child_id`) VALUES ({$theparent},{$newid})";
			$db->setQuery($sql);
			$db->query();
		}
		//return true;
		return $newid;
	}	
	function delete () {
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$database =  & JFactory::getDBO();
		$item = $this->getTable('guruPcateg');
		$sql = "SELECT imagesin FROM #__guru_config WHERE id ='1' ";
		$database->setQuery($sql);
		if (!$database->query()) {
			echo $database->stderr();
			return;
		}
		$imagesin = $database->loadResult();
		
		$not_deleted = '';
		
		foreach ($cids as $cid) {
		
			$q = "SELECT count(child_id) FROM #__guru_categoryrel WHERE parent_id = ".$cid;
			$database->setQuery($q);
			$how_many_subcats = $database->loadResult();	
			
			$q = "SELECT count(id) FROM #__guru_program WHERE catid = ".$cid;
			$database->setQuery($q);
			$how_many_programs = $database->loadResult();	
			
			
			if(($how_many_subcats==0 || !isset($how_many_subcats)) && ($how_many_programs==0 || !isset($how_many_programs))) 
			{// if the category doesn't have subcategories or programs - we delete it - begin
			
			// we delete the image asociated to this program - begin
			$sql = "SELECT image FROM #__guru_category WHERE id =".$cid;
			$database->setQuery($sql);
			if (!$database->query()) {
				echo $database->stderr();
				return;
			}
			$image = $database->loadResult();	
			$targetPath = JPATH_SITE.'/'.$imagesin.'/';		
			unlink($targetPath.$image);
			// we delete the image asociated to this program - end				
		
			$delrel = "DELETE FROM `#__guru_categoryrel` WHERE `child_id` = ".$cid;
			$database->setQuery($delrel);
			$database->query();
		
			if (!$item->delete($cid)) {
				$this->setError($item->getErrorMsg());
				return false;
			}
			}// if the category doesn't have subcategories or programs - we delete it - end
			else
			{// if the category cannot be deleted we pass the ID - for the message - begin
				$not_deleted = $not_deleted . $cid . ',';
			}// if the category cannot be deleted we pass the ID - for the message - end
			
		}		
		$not_deleted = substr($not_deleted, 0, strlen($not_deleted)-1);
		//return true;
		return '1$$$$$'.$not_deleted;
	}
	function publish () {
		$db = JFactory::getDBO();		
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$task = JRequest::getVar('task', '', 'post');
		if ($task == 'publish'){
			$sql = "update #__guru_category set published='1' where id in ('".implode("','", $cids)."')";
			$ret = 1;
			
		} else {
			$ret = -1;
			$sql = "update #__guru_category set published='0' where id in ('".implode("','", $cids)."')";
		}
		$db->setQuery($sql);
		$db->query();
		
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $ret;
	}
	
	public static function getConfigs() {
		$db = JFactory::getDBO();
		
		$sql = "SELECT * FROM #__guru_config LIMIT 1";
		
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}	
		$result = $db->loadObject();	
		return $result;
	}	
	function get_undeleted_categs($ids){
		$db = JFactory::getDBO();
		$cat_name = '';
		$sql = "SELECT name FROM #__guru_category WHERE id in (".$ids.") GROUP BY id";
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}	
		$result = $db->loadColumn();	
		foreach($result as $res)
			$cat_name = $cat_name.$res.', ';
		$cat_name = substr($cat_name,0,strlen($cat_name)-2);
		
		return $cat_name;	
	}
	
	function orderdown(){
		$db = JFactory::getDBO();
		$cid = JRequest::getVar("cid", array(), "post", "array");
		$id = $cid["0"];
		$ordering = JRequest::getVar("order", array(), "post", "array");
		$order_value = $ordering[$id];
		$sql = "SELECT c.id, c.ordering 
				FROM #__guru_category c, #__guru_categoryrel cr 
				WHERE c.id = cr.child_id and cr.parent_id = (select parent_id from #__guru_categoryrel where child_id=".intval($id).") and c.ordering >= ".intval($order_value)." and c.id <> ".intval($id)."
				GROUP BY cr.parent_id, c.ordering
				ORDER BY c.ordering asc";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		if(isset($result) && is_array($result) && count($result)>0){
			$new_id = $result["0"]["id"];
			$new_value = $result["0"]["ordering"];
			$sql = "update #__guru_category set ordering=".intval($new_value)." where id=".intval($id);
			$db->setQuery($sql);
			if($db->query()){			
				$sql = "update #__guru_category set ordering=".intval($order_value)." where id=".intval($new_id);
				$db->setQuery($sql);
				if($db->query()){
					return true;
				}
			}
		}
		return false;
	}
	
	function orderup(){
		$db = JFactory::getDBO();
		$cid = JRequest::getVar("cid", array(), "post", "array");
		$id = $cid["0"];
		$ordering = JRequest::getVar("order", array(), "post", "array");
		$order_value = $ordering[$id];
		$sql = "SELECT c.id, c.ordering 
				FROM #__guru_category c, #__guru_categoryrel cr 
				WHERE c.id = cr.child_id and cr.parent_id = (select parent_id from #__guru_categoryrel where child_id=".intval($id).") and c.ordering <= ".intval($order_value)." and c.id <> ".intval($id)."
				GROUP BY cr.parent_id, c.ordering
				ORDER BY c.ordering desc";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		if(isset($result) && is_array($result) && count($result)>0){
			$new_id = $result["0"]["id"];
			$new_value = $result["0"]["ordering"];
			$sql = "update #__guru_category set ordering=".intval($new_value)." where id=".intval($id);
			$db->setQuery($sql);
			if($db->query()){			
				$sql = "update #__guru_category set ordering=".intval($order_value)." where id=".intval($new_id);
				$db->setQuery($sql);
				if($db->query()){
					return true;
				}
			}
		}
		return false;
	}
	
	function saveorder($idArray = null, $lft_array = null){
		// Get an instance of the table object.
		$table = $this->getTable("guruPcateg");
		if(!$table->saveorder($idArray, $lft_array)){
			$this->setError($table->getError());
			return false;
		}
		// Clean the cache
		$this->cleanCache();
		return true;
	}
};
?>