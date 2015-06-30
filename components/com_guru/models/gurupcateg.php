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


class guruModelguruPcateg extends JModelLegacy {
	var $_promos;
	var $_promo;
	var $_id = null;

	function __construct () {
		parent::__construct();
		$cids = JRequest::getVar('cid', 0, '', 'array');
		$itemid = JRequest::getVar('Itemid', 0);
		if(($cids["0"] == 0) && (isset($itemid) && ($itemid != 0))){
			$db = JFactory::getDBO();	
			$sql = "SELECT params from #__menu where id=".$itemid;
			$db->setQuery($sql);
			$db->query();
			$params = $db->loadColumn();
			$params = $params["0"];
			$params = json_decode($params);
			if(isset($params->cid) && ($params->cid != "")){
				$cids[0] = $params->cid;
				JRequest::setVar("cid", $cids[0]);
			}
		}
		$this->setId((int)$cids[0]);

	}


	function setId($id) {
		$this->_id = $id;

		$this->_promo = null;
	}


	function getlistPcategs(){ 
		if(empty($this->_promos)){
			$query = "SELECT c. *, count( parent_id ) AS copii FROM `#__guru_category` c LEFT OUTER JOIN `#__guru_categoryrel` r ON c.id = r.child_id WHERE c.id IN (select `child_id` from `#__guru_categoryrel`) and r.parent_id = 0 and c.published=1 GROUP BY c.id order by c.ordering asc";		
			$this->_promos = $this->_getList($query);
		}
		
		for($i=0; $i<count($this->_promos); $i++){
			if(trim($this->_promos[$i]->image) != ""){
				$path=explode("/",$this->_promos[$i]->image);
				$this->_promos[$i]->imageName=$path[count($path)-1];
			}
		}
		return $this->_promos;
	}	
	
	function getchildren(){
		$cid = JRequest::getVar("cid", "0");
		
		if(intval($cid) != 0){
			$idu = intval($cid);
		}
		else{
			$db = JFactory::getDBO();
			$item = JRequest::getVar('Itemid', 0);
			
			if(isset($item) && $item != 0){
				$sql = "SELECT `params` FROM #__menu WHERE `id`=".intval($item);
				$db->setQuery($sql);
				$db->query();
				$params = $db->loadColumn();
				$params = json_decode($params["0"]);
				$idu = $params->cid;
			}
		}
		
		$query  = "SELECT c.*, count(catid) as copii FROM `#__guru_category` c LEFT JOIN `#__guru_program` t ON t.catid=c.id WHERE c.id in (select child_id from `#__guru_categoryrel` where parent_id=".intval($idu).") and c.published=1 GROUP BY c.id order by c.ordering asc";
		$this->_promos = $this->_getList($query);
		return $this->_promos;
	}
	
	function getchildren_of_subcategory ($id) {
		$db = JFactory::getDBO();
		$query  = "SELECT c.*, count(catid) as copii FROM `#__guru_category` c LEFT JOIN `#__guru_program` t ON t.catid=c.id WHERE c.id in (select child_id from `#__guru_categoryrel` where parent_id=".$id.") and c.published=1 GROUP BY c.id";
		$db->setQuery($query);
		$kkk = $db->loadObjectList();
		return $kkk;
	}	
	
	function getprograms(){
		$id = JRequest::getVar("cid", "0");
		$jnow 	= JFactory::getDate();
		$date 	= $jnow->toSQL();
		$query  = "SELECT * FROM `#__guru_program` WHERE published='1' AND status='1' AND  startpublish <='".$date."' AND (endpublish >='".$date."' OR endpublish = '0000-00-00 00:00:00' ) AND catid=".intval($id)." order by `ordering` asc";
		$this->_promos = $this->_getList($query);
		
		for($i=0;$i<count($this->_promos);$i++){
			if(trim($this->_promos[$i]->image)!=""){
				$path=explode("/",$this->_promos[$i]->image);
				$this->_promos[$i]->imageName=$path[count($path)-1];
			}
		}
		return $this->_promos;
	}
	
	function getnoprograms ($idu) {
		$db = JFactory::getDBO();
		$query  = "SELECT count(id) FROM `#__guru_program` WHERE published='1' AND catid=".$idu;
		$db->setQuery($query);
		$how_many = $db->loadResult();
		return $how_many;
	}	
	
	function no_of_programs_for_category_children($id) {
			$no_of_cats_with_programs = 0;
			$db = JFactory::getDBO();
			$query  = "SELECT child_id FROM `#__guru_categoryrel` WHERE parent_id=".$id;
			//$this->_promos = $this->_getList($query);
			$db->setQuery($query);
			$child_id_object = $db->loadObject();
			
			if(isset($child_id_object))
			foreach($child_id_object as $child_id)
				{
					$db = JFactory::getDBO();
					$query  = "SELECT count(id) FROM `#__guru_program` WHERE published='1' AND status='1' AND catid=".$child_id;
					$db->setQuery($query);
					$how_many = $db->loadColumn();
					$how_many = $how_many["0"];
					$no_of_programs_for_cat = $how_many;
					if($no_of_programs_for_cat>0)
						$no_of_cats_with_programs++;
				}
		return $no_of_cats_with_programs;
	}	
	
	function no_of_programs_for_category_recursive($id) {
			//global $no_of_cats_with_programs;
			
			$no_of_cats_with_programs = 0;
			
			$db = JFactory::getDBO();
			$query  = "SELECT child_id FROM `#__guru_categoryrel` WHERE parent_id=".$id;
			//$this->_promos = $this->_getList($query);
			$db->setQuery($query);
			$child_id_object = $db->loadColumn();
			
			if(isset($child_id_object))
			foreach($child_id_object as $child_id)
				{
					$more = guruModelguruPcateg::no_of_programs_for_category_recursive($child_id);
					$no_of_cats_with_programs = $no_of_cats_with_programs + $more;
					
					$db = JFactory::getDBO();
					$query  = "SELECT count(id) FROM `#__guru_program` WHERE published='1' AND status='1' AND catid=".$child_id;
					$db->setQuery($query);
					$how_many = $db->loadColumn();
					$how_many = $how_many["0"];

					if($how_many>0)
						{
							$no_of_cats_with_programs = $no_of_cats_with_programs + $how_many;
						}	
				}
		//echo $no_of_cats_with_programs.' - '.$id.'<br />';
		return $no_of_cats_with_programs;
	}				
	
	function getpdays ($pid) {
			$database = JFactory::getDBO();
			$sql = "SELECT count(id) as how_many FROM #__guru_days WHERE pid='".$pid."' ";
			$database->setQuery($sql);
			$rows = $database->loadObjectList();
			return $rows;
	}	

	function getConfigSettings(){
		$sql = "SELECT * FROM #__guru_config WHERE id=1";
		$ConfigSettings = $this->_getList($sql);
		return $ConfigSettings[0];
	}

	function getsum_points_and_time ($pid) {
			$database = JFactory::getDBO();
			$sql = "SELECT sum(points) as s_points, sum(time) as s_time FROM #__guru_task WHERE id in (SELECT media_id FROM #__guru_mediarel WHERE type='dtask' AND type_id in ( SELECT id FROM #__guru_days WHERE pid=".$pid." )  ) ";  
			$database->setQuery($sql);
			$rows = $database->loadObjectList();
			return $rows;
	}

	function getCateg() {
		if (empty ($this->_promo)) { 
			$this->_promo = $this->getTable("guruPcateg");
			$this->_promo->load($this->_id);
		}		
		if(trim($this->_promo->image)!=""){
			$path=explode("/",$this->_promo->image);
			$this->_promo->imageName=$path[count($path)-1];
		}		
		return $this->_promo;
	}
		
	function store () {			
		$db = JFactory::getDBO();
		$item = $this->getTable('guruPcateg');
		
		$data = JRequest::get('post');
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
		} else {
			//inseram in tabela de relatii cu categoriile
			$newid = mysql_insert_id();
			if ($newid > 0) {
				//it'ok
			} else {
				//check for the latest category added
				$ask = "SELECT `id` FROM `#__guru_category` ORDER BY `id` DESC LIMIT 1 ";
				$db->setQuery( $ask );
				$newid = $db->loadColumn();	
				$newid = $newid["0"];			
			}
			//let's do the insert into the relationship table
			$theparent = intval($data['parentcategory_id']);
			$sql = "INSERT INTO `#__guru_categoryrel` (`parent_id`, `child_id`) VALUES ({$theparent},{$newid})";
			$db->setQuery($sql);
			$db->query();
		}
		return true;
	}	

	function find_if_rogram_was_bought($userid, $progid){
		$database = JFactory::getDBO();
		$sql = "SELECT payment FROM #__guru_order WHERE userid = '".$userid."' AND programid = ".$progid;
		$database->setQuery($sql);
		$result = $database->loadColumn();
		$result = $result["0"];
		if (strtolower($result) == 'trial' || !isset($result) || strtolower($result) == 'not_paid')	
			return 0;
		else return 1;	
	}
	
	function program_status($userid, $progid){
		$database = JFactory::getDBO();
		$sql = "SELECT status FROM #__guru_programstatus WHERE userid = '".$userid."' AND pid = ".$progid;
		$database->setQuery($sql);
		$result = $database->loadColumn();
		$result = $result["0"];
		return $result;
	}
};
?>