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


class guruAdminModelguruProgram extends  JModelLegacy  {
	var $_attributes;
	var $_attribute;
	var $_id = null;
	var $_total = 0;
    var $_pagination = null;
	protected $_context = 'com_guru.guruPrograms';

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
	
	function getPagination(){
			// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))	{
			jimport('joomla.html.pagination');
			if (!$this->_total) $this->getItems();
			$this->_pagination = new JPagination( $this->_total, $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}
	
	function orderFile(){
		$cids = JRequest::getVar('cid', array(), "array");
		$cid = $cids["0"];
		$task = JRequest::getVar("task", "");
		$order = JRequest::getVar("order", array(), "array");
		$id = JRequest::getVar("id", "0");
		$db = JFactory::getDBO();
		
		if($task == "orderupfile"){
			$old_order = $order[$cid];
			if($old_order == 0){
				$old_order = 0;
			}
			else{
				$old_order --;
			}
			$sql = "update #__guru_mediarel set `order`=".intval($old_order)." where `type`='pmed' and `type_id`=".intval($id)." and `media_id`=".intval($cid);
			$db->setQuery($sql);
			if($db->query()){
				return true;
			}
		}
		elseif($task == "orderdownfile"){
			$old_order = $order[$cid];
			$old_order ++;
			
			$sql = "update #__guru_mediarel set `order`=".intval($old_order)." where `type`='pmed' and `type_id`=".intval($id)." and `media_id`=".intval($cid);
			$db->setQuery($sql);
			if($db->query()){
				return true;
			}
		}
	}
	
	function saveorderFile(){
		$cids = JRequest::getVar('cid', array(), "array");
		$task = JRequest::getVar("task", "");
		$order = JRequest::getVar("order", array(), "array");
		$id = JRequest::getVar("id", "0");
		$db = JFactory::getDBO();
		if(isset($cids) && count($cids) > 0){
			foreach($cids as $key=>$value){
				$sql = "update #__guru_mediarel set `order`=".intval($order[$key])." where `type`='pmed' and `type_id`=".intval($id)." and `media_id`=".intval($value);
				$db->setQuery($sql);
				$db->query();
			}
		}
		return true;
	}

	function getAllReminds(){
        $db = JFactory::getDBO();
        $sql = "SELECT * FROM #__guru_subremind ORDER BY ordering ASC, id DESC ";
        $db->setQuery($sql);
        $res = $db->loadObjectList();
        return $res;
    }
	protected function getListQuery(){
		$task = JRequest::getVar("task", "");
		$pid = JRequest::getVar("pid", "");
		$search = JRequest::getVar("search", "");
		$and = "";
		if($task == "show"){
			if(trim($search) != ""){
				$and = " and (u.username like '%".addslashes(trim($search))."%' or c.firstname like '%".addslashes(trim($search))."%' or c.lastname like '%".addslashes(trim($search))."%')";
			}	
			$db = JFactory::getDBO();	
			$sql = "select distinct u.id, u.name, u.username from #__users u, #__guru_buy_courses bc, #__guru_customer c, #__guru_order o where c.id=u.id and u.id=bc.userid and bc.course_id=".$pid." ".$and." and o.`userid`=c.`id` and o.`userid`=bc.`userid` order by c.id desc";
			return $sql;
		}
		else{
			if(trim($search) != ""){
				$and = " and (u.username like '%".addslashes(trim($search))."%' or c.firstname like '%".addslashes(trim($search))."%' or c.lastname like '%".addslashes(trim($search))."%')";
			}		
			$db = JFactory::getDBO();
			$sql = "select u.id, u.username, c.firstname, c.lastname from #__users u, #__guru_customer c where c.id=u.id ".$and." order by c.id desc";
			return $sql;
		}
	}
	
	function orderUp(){	
		$db = JFactory::getDBO();
		$ids = JRequest::getVar("cid");
		$table = $this->getTable("guruPrograms");		
		$table->load($ids["0"]);		
		if(!$table->move(-1)){
			return false;
		}
		return true;
	}
	
	function orderDown(){
		$db = JFactory::getDBO();
		$ids = JRequest::getVar("cid");
		$table = $this->getTable("guruPrograms");		
		$table->load($ids["0"]);		
		if(!$table->move(1)){
			return false;
		}
		return true;
	}
	
	function saveOrder(){	
		$db = JFactory::getDBO();		
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');		
		$cid = array_values($cids);		
		$order = JRequest::getVar( 'order', array (0), 'post', 'array' );
		$order = array_values($order);		
		$total = count($cid);
		
		for($i=0; $i<$total; $i++){
			$sql = "update #__guru_program set `ordering`=".$order[$i]." where `id`=".$cid[$i];
			$db->setQuery($sql);
			if (!$db->query()){
				return false;
			}
		}
		return true;
	}
	
	function getFilters(){
		$app = JFactory::getApplication('administrator');
		$filter = (object)array();
		$filter_search = $app->getUserStateFromRequest('search','search','');
		$filter->search = $filter_search;
		
		return $filter;
	}
	
	function setId($id) {
		$this->_id = $id;
		$this->_attribute = null;
	}


	function getlistPrograms(){
		$tmpl = JRequest::getVar("tmpl", "");
		if(trim($tmpl) != ""){
			JRequest::setVar("tmpl", "component");
		}
			
		$db			= JFactory::getDBO();
		
		$app = JFactory::getApplication('administrator');
		
		$limitstart	= $app->getUserStateFromRequest('limitstart','limitstart','0');
		$limit		= $app->getUserStateFromRequest('limit','limit',$app->getCfg('list_limit'));
		$lock		= $app->getUserStateFromRequest('course_lock_status','course_lock_status','YN');
		$course_publ_status = $app->getUserStateFromRequest('course_publ_status','course_publ_status','YN');		
		$catid		= JRequest::getVar("catid", "-1");//$mainframe->getUserStateFromRequest('catid','catid','-1','int');
		$search		= JRequest::getVar("search_text", "");
		$condition	= array();
		
		if($limit!=0){
			$limit_cond=" LIMIT ".$limitstart.",".$limit." ";
		}
		else $limit_cond="";
		
		if($course_publ_status!="YN"){
			if($course_publ_status=="Y"){
				$condition[] = " published='1' ";
			}
			else if($course_publ_status=="N"){
				$condition[] = " published='0' ";
			}
		}
			
		if($catid != -1){
			$condition[]=" catid=".$catid." ";
		}

		if(trim($search) != ""){
			$condition[] = " (name LIKE '%".$search."%' OR description LIKE '%".$search."%')";
		}
		if(!empty($condition)){
			$condition=" AND ".implode(" AND ",$condition);
		}
		else{
			$condition="";
		}	
		$sql = "SELECT * FROM #__guru_program WHERE 1=1 ".$condition." ORDER BY `ordering` ASC";		
		$this->total = $this->_getListCount($sql);
		$sql = "SELECT * FROM #__guru_program WHERE 1=1 ".$condition." ORDER BY `ordering` ASC ";
		//$this->_attributes = $this->_getList($sql);
		return $sql;

	}	
	
	function getProgram() {
		$db = JFactory::getDBO();
		if (empty ($this->_attribute)) { 
			$this->_attribute =$this->getTable("guruPrograms");
			$this->_attribute->load($this->_id);
		}
			$catid = $this->_attribute->catid;
			$data = JRequest::get('post');
			
			
			if (!$this->_attribute->bind($data)){
				$this->setError($item->getErrorMsg());
				return false;
	
			}
	
			if (!$this->_attribute->check()) {
				$this->setError($item->getErrorMsg());
				return false;
			}
			$this->_attribute->catid = $catid;

		
		//start get author list
		$sql = "SELECT u.id, u.name FROM #__users u, #__guru_authors la where u.id=la.userid";	
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadObjectList();
		
		$author_list=array();
		$author_list[]=JHTML::_("select.option",JText::_('GURU_SELECT'),"0");
		for($i=0;$i<count($result);$i++)
			$author_list[]=JHTML::_("select.option",$result[$i]->name,$result[$i]->id);
		$this->_attribute->lists['author']=JHTML::_("select.genericlist",$author_list,"author","","text","value",$this->_attribute->author);
		if($this->_attribute->published == 1){ 
			$checkedd = 'checked="checked"';
		}
		else{
			$checkedd = '';
		}
		
		$this->_attribute->lists['published']  = '<input type="hidden" name="published" value="0">';
		
		
		if($this->_attribute->published == 1){ 
			$this->_attribute->lists['published'] .= '<input type="checkbox" checked="checked" value="1" class="ace-switch ace-switch-5" name="published"><span class="lbl"></span>';
		}
		else{
			$this->_attribute->lists['published'] .= '<input type="checkbox" value="1" class="ace-switch ace-switch-5" name="published"><span class="lbl"></span>';
		}		
		
		$level_list=array();
		$level_list[]=JHTML::_("select.option","0",JText::_("GURU_BEGINNERS"));
		$level_list[]=JHTML::_("select.option","1",JText::_("GURU_INTERMEDIATE"));
		$level_list[]=JHTML::_("select.option","2",JText::_("GURU_ADVANCED"));
		
		$this->_attribute->lists['level'] = JHTML::_('select.genericlist', $level_list, 'level','','value', 'text', $this->_attribute->level );
		
		return $this->_attribute;
	}
	
	public static function getProgramCategory($catid) {
		$database = JFactory::getDBO();
		$sql = "SELECT id,name FROM #__guru_category WHERE id='".$catid."' ";
		$database->setQuery($sql);
		$name = $database->loadObject();
		return $name;		
	}
	
	public static function getpdays ($pid) {
			// we find out how many Days has a given program
			$database = JFactory::getDBO();
			$sql = "SELECT count(id) as how_many FROM #__guru_days WHERE pid='".$pid."' ";
			$database->setQuery($sql);
			$rows = $database->loadObject();
			return $rows;
	}	
	
    function getProgramPlans($id = 0){
        $data = JRequest::get('get'); 
        $db =JFactory::getDBO();
		$id_post = JRequest::getVar('cid');
        if ( ($id == 0) && ( isset( $id_post) ) ) {
            $id = (int) $id_post[0];
        }
        $sql = "SELECT `plan_id`, `price`, `default` FROM #__guru_program_plans
                   WHERE product_id = " . $id;
        $db->setQuery($sql);
        $res = $db->loadObjectList();
        return $res;
    }
    
    function getProgramReminds($id = 0)
    {
        $data = JRequest::get('get');        
        $db =JFactory::getDBO();
        $id_post = JRequest::getVar('cid');
        if ( ($id == 0) && ( isset( $id_post) ) ) {
            $id = (int) $id_post[0];
        }
        $sql = "SELECT emailreminder_id FROM #__guru_program_reminders
                   WHERE product_id=" . $id;
		$db->setQuery( $sql );
		$res = $db->loadObjectList();
        return $res;
    }

    function getProgramRenewals($id = 0)
    {
        $data = JRequest::get('get');        
        $db =JFactory::getDBO();
        $id_post = JRequest::getVar('cid');
        if ( ($id == 0) && ( isset( $id_post) ) ) {
            $id = (int) $id_post[0];
        }
        $sql = "SELECT `plan_id`, `price`, `default` FROM #__guru_program_renewals
                   WHERE product_id=" . $id;
		$db->setQuery( $sql );
		$res = $db->loadObjectList();
        return $res;
    }
	public static function getStudentsNumber($pid){
		$database = JFactory::getDBO();
		$sql = "SELECT count(distinct bc.userid) FROM #__guru_buy_courses bc, #__users u , #__guru_customer c, #__guru_order o WHERE c.id=bc.userid and  bc.userid=u.id and bc.course_id=".$pid." and o.`userid`=c.`id` and o.`userid`=bc.`userid`";
		$database->setQuery($sql);
		$students_number = $database->loadResult();
		return $students_number;
	}
	function countQuizzTaken($id, $pid){
		$db = JFactory::getDBO();
		$sql = "SELECT count(*) from #__guru_quiz_taken WHERE `user_id` =".intval($id)." and pid=".$pid;
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();	
		return $result;	
	}
	
	function getCourseName($pid){
		$database = JFactory::getDBO();
		$sql = "SELECT `name` FROM #__guru_program WHERE `id`=".$pid;
		$database->setQuery($sql);
		$course_name = $database->loadResult();
		return $course_name;
	}

    
	function store () {
		$item = $this->getTable('guruPrograms');
		$data = JRequest::get('post', JREQUEST_ALLOWRAW);
		$database = JFactory::getDBO();
		$db = JFactory::getDBO();	
   		$data['startpublish'] = date('Y-m-d H:i:s', strtotime($data['startpublish']));
		if($data['endpublish'] != 'Never' && $data['endpublish'] != ''){
			$data['endpublish'] = date('Y-m-d H:i:s', strtotime($data['endpublish']));
		}
		$data['image_avatar'] = $data["image_avatar"];
			
   		 $jnow =JFactory::getDate();
		 $date2 = $jnow->toSQL();
		 if($data['id'] !=NULL){
			 $sql1 = "SELECT lesson_release FROM #__guru_program where id=".$data['id'];
			 $db->setQuery($sql1);
			 $less_release_db = $db->loadResult();
			  if($less_release_db != $data['lesson_release']){
				$sql = "UPDATE #__guru_program set start_release = '". $date2."' WHERE id = '" . $data['id']. "' ";
				$db->setQuery($sql);
				$db->query();
			  } 
		 }
		$data['description'] = JRequest::getVar("description","","post","string",JREQUEST_ALLOWRAW);
		$data['introtext']	 = JRequest::getVar("introtext","","post","string",JREQUEST_ALLOWRAW);
		
		if($data['alias']==''){
			$data['alias'] = JFilterOutput::stringURLSafe($data['name']);
		} else {
			$data['alias'] = JFilterOutput::stringURLSafe($data['alias']);
		}
		//ADDED BY JOSEPH 05/04/2015
		//$data['duration'] = JRequest::getVar("duration","","post","float",JREQUEST_ALLOWRAW);
		//END
		$final_quiz = "0";
		$sql = "select `id_final_exam` from #__guru_program where `id`=".intval($data['id']);
		$db->setQuery($sql);
		$db->query();
		$id_final_exam = $db->loadColumn();
		$id_final_exam = @$id_final_exam["0"];
		if(isset($id_final_exam) && intval($id_final_exam) != 0){
			$final_quiz = intval($id_final_exam);
		}
		
		if($final_quiz != "0" && $data["final_quizzes"] == 0){
			// delete final exam
			$progid = $data['id'];
			
			$sql = "SELECT id FROM `#__guru_days` WHERE pid='" . $progid . "' order by ordering desc limit 0,1";
			$db->setQuery($sql);
			$db->query();
			$moduleid = $db->loadColumn();
			$moduleid = @$moduleid["0"];
			
			$sql = "select mr.`media_id` from #__guru_mediarel mr, #__guru_task t where mr.`type`='dtask' and mr.`type_id`=".intval($moduleid)." and mr.`media_id`=t.`id` order by t.`ordering` desc limit 0,1";
			$db->setQuery($sql);
			$db->query();
			$lesson_id = $db->loadColumn();
			$lesson_id = @$lesson_id["0"];
			
			$sql = "delete FROM `#__guru_mediarel` WHERE `type` = 'dtask' AND `type_id` = ".intval($moduleid)." and `media_id` = ".intval($lesson_id);
			$db->setQuery($sql);
			$db->query();
			
			$sql = "delete from #__guru_task where `id`=".intval($lesson_id);
			$db->setQuery($sql);
			$db->query();
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
		
		if(isset($data['echbox'])){
			$email_chbox = $data['echbox'];
		}	
		else{
			$email_chbox = '';
		}	
			
		//delete old records

		if ($data['id']>0) {
			$db->setQuery("DELETE FROM `#__guru_mediarel` WHERE type='email' AND type_id='".$data['id']."'");
			$db->query();
			}
		//delete end
		$sql = "select count(*) from #__extensions where element='com_kunena' and name='com_kunena'";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		if($count[0] >0){
		 if(JComponentHelper::isEnabled( 'com_kunena', true) ){
		   $db->setQuery("SELECT forumboardcourse,forumboardlesson FROM `#__guru_kunena_forum` WHERE id=1 ");
		   $db->query();	
		   $ressult = $db->loadAssocList();

			if(($data['id']== 0 || $data['id'] == "") && $ressult[0]["forumboardcourse"] == 1 ){
				$nameofmainforum = JText::_('GURU_TREECOURSE');
				
				$sql = "SELECT name FROM #__kunena_categories WHERE parent_id='0' and name='".addslashes($nameofmainforum)."'";
				$db->setQuery($sql);
				$db->query($sql);
				$result = $db->loadColumn();
	
				if(count($result[0]) == 0){
					$sql = "INSERT INTO #__kunena_categories ( `parent_id`, `name`, `alias`, `icon_id`, `locked`, `accesstype`, `access`, `pub_access`, `pub_recurse`, `admin_access`, `admin_recurse`, `ordering`, `published`, `channels`, `checked_out`, `checked_out_time`, `review`, `allow_anonymous`, `post_anonymous`, `hits`, `description`, `headerdesc`, `class_sfx`, `allow_polls`, `topic_ordering`, `numTopics`, `numPosts`, `last_topic_id`, `last_post_id`, `last_post_time`, `params`) VALUES ( 0, '".$nameofmainforum."', 'course', 0, 0, 'joomla.level', 1, 1, 1, 8, 1, 2, 1, NULL, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, '', '', '', 0, 'lastpost', 0, 0, 0, 0, 0, '{}')";
					$db->setQuery($sql);
					$db->query($sql);
				}

				$sql = "SELECT id FROM #__kunena_categories WHERE parent_id='0' and name='".$nameofmainforum."'";
				$db->setQuery($sql);
				$db->query();
				$idmainforum= $db->loadResult();
				
				
		
					$sql = "INSERT INTO #__kunena_categories ( `parent_id`, `name`, `alias`, `icon_id`, `locked`, `accesstype`, `access`, `pub_access`, `pub_recurse`, `admin_access`, `admin_recurse`, `ordering`, `published`, `channels`, `checked_out`, `checked_out_time`, `review`, `allow_anonymous`, `post_anonymous`, `hits`, `description`, `headerdesc`, `class_sfx`, `allow_polls`, `topic_ordering`, `numTopics`, `numPosts`, `last_topic_id`, `last_post_id`, `last_post_time`, `params`) VALUES ( '".$idmainforum."', '".$data['name']."', '".$data['alias']."', 0, 0, 'joomla.group', 1, 1, 1, 8, 1, 2, 1, NULL, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, '', '', '', 0, 'lastpost', 0, 0, 0, 0, 0, '{}')";
					$db->setQuery($sql);
					$db->query($sql);
		}
		
		       $sql = "SELECT id FROM #__kunena_categories WHERE parent_id='".$idmainforum."' and name='".addslashes($data['name'])."'";
				$db->setQuery($sql);
				$db->query($sql);
				$resultid = $db->loadColumn();
				
				if($resultid[0] !=0){
					$sql = "INSERT INTO #__kunena_aliases (`alias`, `type`, `item`, `state`) VALUES (  '".$data['alias']."', 'catid', '".$resultid[0]."', 0)";
					$db->setQuery($sql);
					$db->query($sql);
				}
				
				if ($data['id']=="") $data['id'] = mysql_insert_id();
				if ($data['id']==0) {
					$ask = "SELECT `id` FROM `#__guru_program` ORDER BY `id` DESC LIMIT 1 ";
					$db->setQuery( $ask );
					$data['id'] = $db->loadResult();
				}
				$sql = "INSERT INTO #__guru_kunena_courseslinkage (`idcourse`, `coursename`, `catidkunena`) VALUES (  '".$data['id']."', '".addslashes($data['name'])."', '".$resultid[0]."')";
				$db->setQuery($sql);
				$db->query($sql);
	   }
		
		}
		
		if ($data['id']=="") $data['id'] = mysql_insert_id();
		if ($data['id']==0) {
			$ask = "SELECT `id` FROM `#__guru_program` ORDER BY `id` DESC LIMIT 1 ";
			$db->setQuery( $ask );
			$data['id'] = $db->loadResult();
		}
		$progid = $data['id'];
		if($email_chbox!='')
		foreach ($email_chbox as $email_chbox_val) {
			if (intval($email_chbox_val)>0) {
				$db->setQuery("INSERT INTO `#__guru_mediarel` (`id`,`type`,`type_id`,`media_id`,`mainmedia`) VALUES ('','email','".$progid."','".$email_chbox_val."','0')");
				$db->query();
			}
		}
		
		if (isset($data['mediafiles'])) {
			//delete old records
			if ($data['id']>0) {
				$db->setQuery("DELETE FROM `#__guru_mediarel` WHERE type='pmed' AND type_id='".$data['id']."'");
				$db->query();
			}
			//delete end
			if ($data['id']=="") $data['id'] = mysql_insert_id();
			if ($data['id']==0) {
				$ask = "SELECT `id` FROM `#__guru_program` ORDER BY `id` DESC LIMIT 1 ";
				$db->setQuery( $ask );
				$data['id'] = $db->loadResult();
			}
			$progid = $data['id'];			
			$thefiles = explode(',',$data['mediafiles']);

			$id_tmp_med_task_2_remove = array();
			if(isset($data['mediafiletodel']))
				$id_tmp_med_files_2_remove = explode(',', $data['mediafiletodel']);
				
			foreach ($thefiles as $files){
				if($files != ""){
					$array = $data["order"];
					if (intval($files)>0 && !in_array($files,$id_tmp_med_files_2_remove)) {
						$access = "access".$files;
						$order = "0";
						if($array[$files] != ""){
							$order = $array[$files];
						}		
						$sql = "INSERT INTO `#__guru_mediarel` (`id`,`type`,`type_id`,`media_id`,`mainmedia`, `access`, `order`) VALUES ('','pmed','".$progid."','".$files."','0', ".$data[$access].", ".$order.")";
						echo $sql."<br/><br/>"; 
						$db->setQuery($sql);
						$db->query();
					}
				}
			}
		} // end if
		
		
		if (isset($data['preqfiles'])) {
			//delete old records
			if ($data['id']>0) {
				//$db->setQuery("DELETE FROM `#__guru_mediarel` WHERE type='preq' AND type_id='".$data['id']."'");
				//$db->query();
			}
			//delete end
			if ($data['id']=="") $data['id'] = mysql_insert_id();
			if ($data['id']==0) {
				$ask = "SELECT `id` FROM `#__guru_program` ORDER BY `id` DESC LIMIT 1 ";
				$db->setQuery( $ask );
				$data['id'] = $db->loadResult();
			}
			$progid = $data['id'];
					
			
			$thefiles = explode(',',$data['preqfiles']);
			
			$id_tmp_med_task_2_remove = array();
			if(isset($data['preqfiletodel']))
				$id_tmp_med_files_2_remove = explode(',', $data['preqfiletodel']);
				
			foreach ($thefiles as $files) {	
				if (intval($files)>0 && !in_array($files,$id_tmp_med_files_2_remove)) {
					$db->setQuery("INSERT INTO `#__guru_mediarel` (`id`,`type`,`type_id`,`media_id`,`mainmedia`) VALUES ('','preq','".$progid."','".$files."','0')");
					$db->query();
				}
			}
		} // end if

        $sql = "DELETE FROM #__guru_program_plans WHERE product_id = '" . $progid . "' ";
        $db->setQuery($sql);
        $db->query();
		
        foreach( $data['subscriptions'] as $element ) {
			if($data['subscription_price'][$element] == 0 && !isset($data['chb_free_courses'])){
				$_SESSION["empltyprice"] = 1;
				return false;
			}
            $data['subscription_default'] == $element ? $default = '1' : $default = '0';
            $sql = "INSERT INTO `#__guru_program_plans` (`product_id` ,`plan_id` ,`price` ,`default`) 
                       VALUES ('{$progid}', '{$element}', '{$data['subscription_price'][$element]}', '{$default}')";
            $sqlz[] = $sql;
            $db->setQuery($sql);
            $db->query();
        }
        // Subscriptions - END
        
        // Renewals
        $sql = "DELETE FROM #__guru_program_renewals WHERE product_id = '" . $progid . "' ";
        $db->setQuery($sql);
        $db->query();
        foreach( $data['renewals'] as $element ) {
            $data['renewal_default'] == $element ? $default = '1' : $default = '0';
            $sql = "INSERT INTO `#__guru_program_renewals` (`product_id` ,`plan_id` ,`price` ,`default`) 
                       VALUES ('{$progid}', '{$element}', '{$data['renewal_price'][$element]}', '{$default}');";
            $sqlz[] = $sql;
            $db->setQuery($sql);
            $db->query();
        }
        // Renewals - END
		
		//SEQUESNITAL_NON-SEQUENTIAL Course START
		 		
		 if($data['lesson_release'] != '0'){
			$sql = "UPDATE #__guru_program set course_type =".$data['course_type']." , lesson_release=".$data['lesson_release']." , lessons_show =".$data['lessons_show']."  WHERE id = '" . $progid . "' ";
		  if($less_release_db != $data['lesson_release']){
			$sql = "UPDATE #__guru_program set start_release = '". $date2."' WHERE id = '" . $progid. "' ";
			$db->setQuery($sql);
			$db->query();
		  } 
		 }
		 elseif($data['lesson_release'] == '0'){
		 	$sql = "UPDATE #__guru_program set course_type =".$data['course_type']." , lesson_release=".$data['lesson_release']." , lessons_show =".$data['lessons_show']."  WHERE id = '" . $progid . "' ";
		 }
       	 $db->setQuery($sql);
       	 $db->query();
		
		 
		//SEQUESNITAL_NON-SEQUENTIAL Course END
		
		//FINAL EXAM QUIZ START
		
		$sql = "SELECT id FROM `#__guru_days` WHERE pid='" . $progid . "' order by ordering desc limit 0,1";
		$db->setQuery($sql);
		$db->query();
		$moduleid = $db->loadColumn();
		$moduleid = @$moduleid["0"];
		
		$sql = "SELECT name FROM `#__guru_quiz` WHERE id='" . $data['final_quizzes'] . "' ";
		$db->setQuery($sql);
		$db->query();
		$name_quiz=$db->loadResult();

		$db->setQuery("SELECT id_final_exam  FROM `#__guru_program` WHERE id=".$progid );
		$db->query();	
		$id_final_exam = $db->loadResult();
		
		$sql = "UPDATE #__guru_program set `id_final_exam` =".intval($data['final_quizzes'])." WHERE `id`=".$progid;
		$db->setQuery($sql);
		$db->query();
		
		$sql = "UPDATE #__guru_program set `certificate_term` = ".$data['certificate_setts']." WHERE `id`=".$progid;
		$db->setQuery($sql);
		$db->query();
		
		if($data['final_quizzes'] != '0' && $data['final_quizzes'] != $id_final_exam ){	
			$sql = "select mr.`media_id` from #__guru_mediarel mr, #__guru_task t where mr.`type`='dtask' and mr.`type_id`=".intval($moduleid)." and mr.`media_id`=t.`id` order by t.`ordering` desc limit 0,1";
			$db->setQuery($sql);
			$db->query();
			$lesson_id = $db->loadColumn();
			$lesson_id = @$lesson_id["0"];
			
			$sql = "SELECT `media_id` FROM `#__guru_mediarel` WHERE `type` = 'scr_m' AND `type_id` = ".intval($lesson_id)." and `layout` = 12";
			$db->setQuery($sql);
			$db->query();
			$media_id = $db->loadColumn();
			$media_id = @$media_id["0"];
			$type = "quiz";
			
			if(intval($media_id) == 0){
				$sql = "SELECT `media_id` FROM `#__guru_mediarel` WHERE `type` = 'scr_m' AND `type_id` = ".intval($lesson_id);
				$db->setQuery($sql);
				$db->query();
				$media_id = $db->loadColumn();
				$media_id = @$media_id["0"];
				$type = "";
			}
						
			$sql = "select `ordering` from #__guru_task where `id`=".intval($lesson_id);
			$db->setQuery($sql);
			$db->query();
			$max_ordering = $db->loadColumn();
			$max_ordering = @$max_ordering["0"];
			
			$name_exam = JText::_("GURU_FINAL_EXAM").$name_quiz;
			
			if($type == 'quiz'){
				// check if is final quiz
				$sql = "select `is_final` from #__guru_quiz where `id`=".intval($media_id);
				$db->setQuery($sql);
				$db->query();
				$is_final = $db->loadColumn();
				$is_final = @$is_final["0"];
				
				if($is_final == 0){// is not final quiz
					// add final quizl);
					$sql = "INSERT INTO `#__guru_task` (`name`, `alias`, `category`, `difficultylevel`, `points`, `image`, `published`, `startpublish`, `endpublish`, `metatitle`, `metakwd`, `metadesc`, `time`, `ordering`, `step_access`) VALUES ('".addslashes(trim($name_exam))."', 'final-exam', NULL, 'hard', NULL, NULL, 1, now(), '0000-00-00 00:00:00', '', '', '', 0,".($max_ordering + 1).", 0)";
					$db->setQuery($sql);
					$db->query();
					
					$db->setQuery("SELECT max(id) FROM `#__guru_task`");
					$db->query();	
					$max_id = $db->loadColumn();
					$max_id = @$max_id["0"];
						
					$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`) VALUES ('scr_l','".$max_id."','12','0')");
					$db->query();	
		
					$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$max_id."','".$data['final_quizzes']."','1',12)");
					$db->query();	
			
					$query = "INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`text_no`) VALUES ('dtask','".$moduleid."','".$max_id."','0','0')";	
					$db->setQuery($query);
					$db->query();
				}
				else{// already is final exam
					$sql = "select `name` from #__guru_quiz where `id`=".intval($data['final_quizzes']);
					$db->setQuery($sql);
					$db->query();
					$new_quiz_name = $db->loadColumn();
					
					$new_quiz_name = @$new_quiz_name["0"];
					$new_quiz_id = $data['final_quizzes'];
					
					$sql = "update #__guru_task set `name` = '".addslashes(trim($name_exam))."' where `id`=".intval($lesson_id);
					$db->setQuery($sql);
					$db->query();
					
					$sql = "update #__guru_mediarel set `media_id`=".intval($new_quiz_id)." where `type`='scr_m' and `type_id`=".intval($lesson_id);
					$db->setQuery($sql);
					$db->query();
				}
			}
			else{
				// add final quizl);
				$sql = "INSERT INTO `#__guru_task` (`name`, `alias`, `category`, `difficultylevel`, `points`, `image`, `published`, `startpublish`, `endpublish`, `metatitle`, `metakwd`, `metadesc`, `time`, `ordering`, `step_access`) VALUES ('".addslashes(trim($name_exam))."', 'final-exam', NULL, 'hard', NULL, NULL, 1, now(), '0000-00-00 00:00:00', '', '', '', 0,".($max_ordering + 1).", 0)";
				$db->setQuery($sql);
				$db->query();
				
				$db->setQuery("SELECT max(id) FROM `#__guru_task`");
				$db->query();	
				$max_id = $db->loadColumn();
				$max_id = @$max_id["0"];
					
				$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`) VALUES ('scr_l','".$max_id."','12','0')");
				$db->query();	
	
				$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$max_id."','".$data['final_quizzes']."','1',12)");
				$db->query();	
		
				$query = "INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`text_no`) VALUES ('dtask','".$moduleid."','".$max_id."','0','0')";	
				$db->setQuery($query);
				$db->query();
			}
		}
		
		//FINAL EXAM QUIZ END

        // Email reminders
        $sql = "DELETE FROM #__guru_program_reminders WHERE product_id = '" . $progid . "' ";
        $db->setQuery($sql);
        $db->query();
        foreach( $data['reminders'] as $element ) {
            $sql = "INSERT INTO `#__guru_program_reminders` (`product_id` ,`emailreminder_id` ,`send`)
                       VALUES ('{$progid}', '{$element}', '1');";
            $sqlz[] = $sql;
            $db->setQuery($sql);
            $db->query();
       }
	   //Free Courses
	   if(isset($data['chb_free_courses'])){
		   $sql = "UPDATE `#__guru_program` set chb_free_courses = '1' where id=".$data['id'];
		   $db->setQuery($sql);
		   $db->query();
	   }
	   else{
		   $sql = "UPDATE `#__guru_program` set chb_free_courses = '0' where id=".$data['id'];
		   $db->setQuery($sql);
		   $db->query();
	   }
	   if(isset($data['step_access_courses'])){
		   $sql = "UPDATE `#__guru_program` set step_access_courses = ".$data['step_access_courses']." where id=".$data['id'];
		   $db->setQuery($sql);
		   $db->query(); 		   
	   }
	   if(isset($data['selected_course'])){
		   $anyCourseSelected = false;
		   $course_value = "";
		   foreach($data['selected_course'] as $key=>$value) {
		   		if($value == "-1") {
					$anyCourseSelected = true;
					break;
				}
				else {
					$course_value.=$value."|";
				}
		   }
		   
		   if($anyCourseSelected){
			   $sql = "UPDATE `#__guru_program` set selected_course = '-1' where id=".$data['id'];
		   }
		   else{ 
		       $sql = "UPDATE `#__guru_program` set selected_course = '".$course_value."' where id=".$data['id'];
		   }
		   $db->setQuery($sql);
		   $db->query();
	   }
	   //Avg certificate
	   	if(isset($data['avg_cert'])){
		   $sql = "UPDATE `#__guru_program` set avg_certc = '".$data['avg_cert']."' where id=".$data['id'];
		   $db->setQuery($sql);
		   $db->query();
	   }
	   if(isset($data['coursemessage'])){
	   	 $sql = "UPDATE `#__guru_program` set certificate_course_msg = '".$data['coursemessage']."' where id=".$data['id'];
		 $db->setQuery($sql);
		 $db->query();
	   }
	    if ($data['id']>0){
	   	 $sql = "UPDATE `#__guru_kunena_courseslinkage` set coursename = '".addslashes($data['name'])."' where idcourse=".$data['id'];
		 $db->setQuery($sql);
		 $db->query();
		 
		 $sql = "SELECT catidkunena  FROM `#__guru_kunena_courseslinkage` where idcourse=".$data['id']." order by id desc limit 0,1";
		 $db->setQuery($sql);
		 $db->query();
		 $catidkunena = $db->loadResult();
		 
		 $sql = "SELECT coursename  FROM `#__guru_kunena_courseslinkage` where idcourse=".$data['id']." order by id desc limit 0,1";
		 $db->setQuery($sql);
		 $db->query();
		 $coursename = $db->loadResult();
		 
	   }
	   
		return $progid;
	}	
	
function parse_quiz ($id,$type){
	$database = JFactory::getDBO();
	$q = "SELECT * FROM #__guru_config WHERE id = '1' ";
	$database->setQuery($q);
	$configs = $database->loadObject();
		
	if($type=="quiz"){
		$q  = "SELECT * FROM #__guru_quiz WHERE id = ".$id;
		$database->setQuery( $q );
		$result = $database->loadObject();
		$the_media = $result;
		$the_media->type="quiz";
	}
	else{
		$q  = "SELECT * FROM #__guru_media WHERE id = ".$id;
		$database->setQuery( $q );
		$result = $database->loadObject();
		$the_media = $result;	
	}
	
	if($the_media->type=='text'){
		$media = $the_media->code;
	}
	if($the_media->type=='docs'){
		$the_base_link = explode('administrator/', $_SERVER['HTTP_REFERER']);
		$the_base_link = $the_base_link[0];				
			
		$media = 'The selected element is a text file that can\'t have a preview';
		if($the_media->source == 'local' && (substr($the_media->local,(strlen($the_media->local)-3),3) == 'txt' || substr($the_media->local,(strlen($the_media->local)-3),3) == 'pdf') && $the_media->width > 1) {
			$media='<div class="contentpane">
					<iframe id="blockrandom"
						name="iframe"
						src="'.$the_base_link.'/'.$configs->docsin.'/'.$the_media->local.'"
						width="'.$the_media->width.'"
						height="'.$the_media->height.'"
						scrolling="auto"
						align="top"
						frameborder="2"
						class="wrapper">
						This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
					</div>';
		}
							
		if($the_media->source == 'local' && $the_media->width == 1)
			$media='<br /><a href="'.$the_base_link.'/'.$configs->docsin.'/'.$the_media->local.'" target="_blank">'.$the_media->name.'</a>';
	
		if($the_media->source == 'url'  && $the_media->width == 0)
			$media='<div class="contentpane">
						<iframe id="blockrandom"
						name="iframe"
						src="'.$the_media->url.'"
						width="100%"
						height="600"
						scrolling="auto"
						align="top"
						frameborder="2"
						class="wrapper">
						This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
					</div>';		
							
		if($the_media->source == 'url'  && $the_media->width == 1)
			$media='<a href="'.$the_media->url.'" target="_blank">'.$the_media->name.'</a>';								
	}	
	
	if($the_media->type=='quiz'){		
		$the_media->source=$the_media->id;
		
		$media = '';
			
		$q  = "SELECT * FROM #__guru_quiz WHERE id = ".$the_media->source;
		$database->setQuery( $q );
		$result_quiz = $database->loadObject();				
		$media .= '<span class="guru_quiz_title">'.$result_quiz->name.'</span>';
		$media .= '<span class="guru_quiz_description">'.$result_quiz->description.'</span>';
				
		if($result_quiz->is_final == 1){
			$sql = "SELECT 	quizzes_ids FROM #__guru_quizzes_final WHERE qid=".$the_media->source;
			$database->setQuery($sql);
			$database->query();
			$result=$database->loadResult();	
			$result_qids = explode(",",trim($result,","));
		
		
			$q  = "SELECT * FROM #__guru_questions WHERE qid IN (".implode(",", $result_qids).") and published=1";
			
		}
		else{
			$q  = "SELECT * FROM #__guru_questions WHERE qid =".$the_media->source." and published=1";
		}	
		
		$database->setQuery( $q );
		$database->query();
		$quiz_questions = $database->loadObjectList();			
			
		$media = $media.'<div id="the_quiz">';
			
		$question_number = 1;
		
		for($i=0;$i<count($quiz_questions);$i++){
			$one_question=$quiz_questions[$i];
			$question_answers_number = 0;
			

			$media .= '<ul class="guru_list">';
			$media .= 	'<li class="question">'.$one_question->text.'</li>';
					
			//$media = $media.'<div align="left" style="padding-left:30px;">';
			if($one_question->a1!=''){
				$media .= '<li class="answer"><input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a1).'" \' type="checkbox" value="1a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a1.'</input></li>';
				
				$question_answers_number++;
			}	
			if($one_question->a2!=''){
				$media .= '<li class="answer"><input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a2).'" \' type="checkbox" value="2a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a2.'</input></li>';
				
				$question_answers_number++;
			}	
			if($one_question->a3!=''){
				$media .= '<li class="answer"><input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a3).'" \' type="checkbox" value="3a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a3.'</input></li>';
				
				$question_answers_number++;
			}	
			if($one_question->a4!=''){
				$media .= '<li class="answer"><input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a4).'" \' type="checkbox" value="4a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a4.'</input></li>';
				
				$question_answers_number++;
			}	
			if($one_question->a5!=''){
				$media .= '<li class="answer"><input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a5).'" \' type="checkbox" value="5a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a5.'</input></li>';
				
				$question_answers_number++;
			}	
			if($one_question->a6!=''){
				$media .= '<li class="answer"><input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a6).'" \' type="checkbox" value="6a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a6.'</input></li>';
				
				$question_answers_number++;
			}	
			if($one_question->a7!=''){
				$media .= '<li class="answer"><input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a7).'" \' type="checkbox" value="7a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a7.'</input></li>';
				
				$question_answers_number++;
			}	
			if($one_question->a8!=''){
				$question_answers_number++;
				
				$media .= '<li class="answer"><input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a8).'" \' type="checkbox" value="8a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a8.'</input></li>';
			}
			if($one_question->a9!=''){
				$question_answers_number++;
				
				$media .= '<li class="answer"><input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a9).'" \' type="checkbox" value="9a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a9.'</input></li>';
			}
			if($one_question->a10!=''){
				$question_answers_number++;
				
				$media .= '<li class="answer"><input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a10).'" \' type="checkbox" value="10a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a10.'</input></li>';
			}
			//$media = $media.'</div>';	
			$media = $media.'</ul>';	
					
			$the_first_answer = explode(',', $one_question->answers);
			$the_first_answer = $the_first_answer[0];
			$the_first_answer = str_replace('a', '', $the_first_answer);
					
			if(intval($the_first_answer) == 1)
				$the_right_answer = $one_question->a1;
				if(intval($the_first_answer) == 2)
					$the_right_answer = $one_question->a2;
				if(intval($the_first_answer) == 3)

					$the_right_answer = $one_question->a3;
				if(intval($the_first_answer) == 4)
					$the_right_answer = $one_question->a4;
				if(intval($the_first_answer) == 5)
					$the_right_answer = $one_question->a5;
				if(intval($the_first_answer) == 6)
					$the_right_answer = $one_question->a6;
				if(intval($the_first_answer) == 7)
					$the_right_answer = $one_question->a7;
				if(intval($the_first_answer) == 8)
					$the_right_answer = $one_question->a8;
				if(intval($the_first_answer) == 9)
					$the_right_answer = $one_question->a9;
				if(intval($the_first_answer) == 10)
					$the_right_answer = $one_question->a10;																		
																																										
				$media = $media.'<input type="hidden" value="" name="question_answergived'.$question_number.'" id="question_answergived'.$question_number.'" />';
				$media = $media.'<input type="hidden" value="'.str_replace("'","$$$$$" ,$the_right_answer).'" name="question_answerright'.$question_number.'" id="question_answerright'.$question_number.'" />';
				$media = $media.'<input type="hidden" value="'.str_replace("'","$$$$$" ,$one_question->text).'" name="the_question'.$question_number.'" id="the_question'.$question_number.'" />';		
				$question_number++;																																								
			}		
			$media = $media.'<input type="hidden" value="'.($question_number-1).'" name="question_number" id="question_number" />';
			$media = $media.'<br /><div align="left" style="padding-left:30px;"><input type="submit" value="Submit" onclick="get_quiz_result()" /></div>';	
			$media = $media.'</div>';
		}	
	return $media;	
}
		
	
	
	function more_media_files ($ids){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT *, id as media_id FROM `#__guru_media` WHERE id in (".$ids.") GROUP BY media_id");
		$db->query();
		$more_media_files = $db->loadObjectList();
		$this->assign("more_media_files", $more_media_files);
		return true;
	}
	
	public static function existing_ids ($ids){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT media_id FROM `#__guru_mediarel` WHERE type_id = ".$ids." AND type='pmed' ");
		$db->query();
		$existing_ids = $db->loadObjectList();
		return $existing_ids;
	}	
	
	public static function preq_existing_ids ($ids){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT media_id FROM `#__guru_mediarel` WHERE type_id = ".$ids." AND type='preq' ");
		$db->query();
		$existing_ids = $db->loadObjectList();
		return $existing_ids;
	}	

	function delFileMedia($id, $cid){
		$database = JFactory::getDBO();
		$query = "DELETE FROM #__guru_mediarel WHERE type_id=".$id." AND media_id=".$cid;
		$database->setQuery($query);
		if (!$database->query()){			
			return false;
		}
		return true;
	}

	function delete(){
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		
		$item = $this->getTable('guruPrograms');
		$database = JFactory::getDBO();
		
		$sql = "SELECT imagesin FROM #__guru_config WHERE id ='1' ";
		$database->setQuery($sql);
		if (!$database->query()) {
			echo $database->stderr();
			return;
		}
		$imagesin = $database->loadResult();		
		
		foreach ($cids as $cid) {
			$stud_nb = 	$this->getStudentsNumber($cid);
			if($stud_nb > 0){
				return "denined";
			}
			// we delete the image asociated to this program - begin
			$sql = "SELECT image FROM #__guru_program WHERE id =".$cid;
			$database->setQuery($sql);
			if (!$database->query()) {
				echo $database->stderr();
				return;
			}
			$image = $database->loadResult();	
			$targetPath = JPATH_SITE.'/'.$imagesin.'/';		
			unlink($targetPath.$image);
			$query = "DELETE FROM #__guru_mediarel WHERE type = 'pmed' AND type_id = '".$cid."'";
			$database->setQuery( $query );
			$database->query();				
			// we delete the media relation - end	
			
			// we delete the email relation - begin
			$query = "DELETE FROM #__guru_mediarel WHERE type = 'email' AND type_id = '".$cid."'";
			$database->setQuery( $query );
			$database->query();				
			// we delete the email relation - end	
			
			// we delete the DAYS along with the relations and images - begin
			$sql = "SELECT image, id FROM #__guru_days WHERE id =".$cid;
			$database->setQuery($sql);
			if (!$database->query()) {
				echo $database->stderr();
				return;
			}
			$day_array = $database->loadObjectList();			
			foreach($day_array as $one_day)
				{
					unlink($targetPath.$one_day->image);
					// we delete the relation with tasks
					$query = "DELETE FROM #__guru_mediarel WHERE type = 'dtask' AND type_id = '".$one_day->id."'";
					$database->setQuery( $query );
					$database->query();	
					// we delete the relation with media
					$query = "DELETE FROM #__guru_mediarel WHERE type = 'dmed' AND type_id = '".$one_day->id."'";
					$database->setQuery( $query );
					$database->query();	
				}
			

			$query = "DELETE FROM #__guru_days WHERE pid = '".$cid."'";
			$database->setQuery( $query );
			$database->query();				
			// we delete the DAYS along with the relations and images - end			
			
			// we delete the program_status - begin
			$query = "DELETE FROM #__guru_programstatus WHERE pid = '".$cid."'";
			$database->setQuery( $query );
			$database->query();				
			// we delete the program_status - end			
			
			
			$query = "SELECT `deleted_boards` FROM #__guru_kunena_forum WHERE id =1 ";
			$database->setQuery( $query );
			$database->query();	
			$deleted_boards = $database->loadResult();
			
			$sql = "select count(*) from #__extensions where element='com_kunena'";
			$database->setQuery($sql);
			$database->query();
			$count = $database->loadResult();
			
			if($count > 0){
				if($deleted_boards == 1){
					$sql = "SELECT alias FROM #__guru_program WHERE id =".$cid;	
					$database->setQuery( $sql );
					$database->query();	
					$alias = $database->loadResult();
					
					$query = "DELETE FROM #__kunena_categories WHERE alias = '".$alias."'";
					$database->setQuery( $query );
					$database->query();	
					
					$query = "DELETE FROM #__kunena_aliases WHERE alias = '".$alias."'";
					$database->setQuery( $query );
					$database->query();
				}
				elseif($deleted_boards == 2){
					$sql = "SELECT alias FROM #__guru_program WHERE id =".$cid;	
					$database->setQuery( $sql );
					$database->query();	
					$alias = $database->loadResult();
					
					$query = "UPDATE #__kunena_categories set published=0 WHERE alias = '".$alias."'";
					$database->setQuery( $query );
					$database->query();	
				}
			
			}											
		
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
		if ($task == 'publish'){
			$sql = "update #__guru_program set published='1' where id in ('".implode("','", $cids)."')";
			$ret = 1;
		} else {
			$ret = -1;
			$sql = "update #__guru_program set published='0' where id in ('".implode("','", $cids)."')";

		}
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
	
		return $ret;
	}	
	
	function publishEdit(){
		$db = JFactory::getDBO();		
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
					
		$task = JRequest::getVar('task', '', 'post');
		if ($task == 'publish'){
			$sql = "update #__guru_media set published='1' where id in ('".implode("','", $cids)."')";
			$ret = 1;
		} else {
			$ret = -1;
			$sql = "update #__guru_media set published='0' where id in ('".implode("','", $cids)."')";

		}
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
	
		return $ret;
	}	
	
	function delmedia($tid,$cid) {
		$db = JFactory::getDBO();
		
		$sql = "delete from #__guru_mediarel where type='pmed' and type_id=".$tid." and media_id=".$cid." and mainmedia='0'";
		
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
		return 1;
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
	
		function getLastViewedLessandMod($id, $pid){
			$db = JFactory::getDBO();
			$sql = "SELECT `lesson_id`, `module_id` from #__guru_viewed_lesson WHERE `user_id` =".intval($id)." and pid=".$pid;
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadObjectList();

			@$result_lesson = $result[0]->lesson_id;
			$result_lesson = explode('||', trim($result_lesson, "||"));
			$result_lesson1 = end($result_lesson);
			@$result_module = $result[0]->module_id;
			$result_module = explode('||', trim($result_module, "||"));
			$result_module1 = end($result_module);
			
			
			$sql = "SELECT name FROM #__guru_task WHERE `id`=". intval($result_lesson1);
			$db->setQuery($sql);
			$db->query();
			$result_lesson = $db->loadResult();
			
			
			$sql = "SELECT title FROM #__guru_days WHERE `id`=".intval($result_module1);
			$db->setQuery($sql);
			$db->query();
			$result_module = $db->loadResult();
			
			$sql = "SELECT id FROM #__guru_days WHERE `pid`=".$pid." ORDER BY ordering";
			$db->setQuery($sql);
			$db->query();
			$result_module_id = $db->loadColumn();
			
			if(isset($result_module1) && $result_module1 !=NULL){
				$sql = "SELECT id FROM #__guru_task t WHERE id IN (SELECT media_id FROM #__guru_mediarel WHERE type = 'dtask' AND type_id in (".$result_module1.")) ORDER BY t.ordering";
				$db->setQuery($sql);
				$db->query();
				$result_lesson_id = $db->loadColumn();
			}
			//echo"<pre>"; print_r($result_lesson_id);die();
			$module_nb = array_search ($result_module1 , $result_module_id );
			//if($module_nb ==0){
				$module_nb +=1;
			//}	
			@$lesson_nb = array_search ($result_lesson1 , $result_lesson_id );
			$lesson_nb +=1; 

			if($result_module!=""){
				$result = JText::_('GURU_PROMDAY')." ".$module_nb.":"." ".$result_module. "<br/>".JText::_('GURU_TASK_TASK')." ".$lesson_nb.":"." ".$result_lesson ;			
			}
			else{
				$result = "";
			}
			return $result;
	}
	
	function courseCompleted($id, $pid){
		$db = JFactory::getDBO();
		$sql = "SELECT `completed` from #__guru_viewed_lesson WHERE `user_id` =".intval($id)." and pid=".$pid;
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if($result == 1){
			return true;	
		}
		else{
			return false;
		}
	}
	
	function dateCourseCompleted($id, $pid){
		$db = JFactory::getDBO();
		$sql = "SELECT `date_completed` from #__guru_viewed_lesson WHERE `user_id` =".intval($id)." and pid=".$pid;
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}
	
	function dateLastVisit($id, $pid){
		$db = JFactory::getDBO();
		$sql = "SELECT `date_last_visit` from #__guru_viewed_lesson WHERE `user_id` =".intval($id)." and pid=".$pid;
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return @$result[0];	
	}	
	
	function checkbox_construct( $rowNum, $recId, $name='cid' )
	{
		$db = JFactory::getDBO();
		
		$sql = " SELECT id FROM #__guru_order GROUP BY id";
		
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}	
		$result = $db->loadColumn();	
		
		$sql = "SELECT influence FROM #__guru_config WHERE id = 1";
		$db->setQuery($sql);
		if (!$db->query()) {
			echo $db->stderr();
			return;
			}
		$influence = $db->loadResult(); // we have selected the INFLUENCE 
		
		if(($influence==0 && in_array($recId, $result)))
			{
				$not = 'not';
				$disabled = 'disabled="disabled"';	
			}	
		else 
			{
				$disabled = '';
				$not = '';
			}	
		
		return '<input type="checkbox" id="'.$not.'cb'.$rowNum.'" '.$disabled.'" name="'.$name.'[]" value="'.$recId.'" onclick="isChecked(this.checked);" />$$$$$'.$disabled;
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
		
		$task = JRequest::getVar("task","");
		if($task == "show"){
			$query = $this->getListQuery();
		}
		else{
			$query = $this->getlistPrograms();
		}
		$result = $this->_getList($query.$limit_cond);
		$this->_total = $this->_getListCount($query);
		return $result;
	}
	
	function duplicate () {		
		jimport('joomla.filesystem.folder');
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$n		= count( $cid );

		if ($n == 0) {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}
		foreach ($cid as $id)
		{
			$row 	= $this->getTable('guruPrograms');
			$db = JFactory::getDBO();
			// load the row from the db table
			$row->load( (int) $id );

			$old_prog_id = $row->id;
			
			$sql = "SELECT imagesin FROM `#__guru_config` WHERE id = 1";
			$db->setQuery($sql);
			$configs = $db->loadResult();				

				
			$row->name 	= JText::_( 'GURU_CS_COPY_TITLE' ).' '.$row->name;
			$row->id 			= 0;
			$time_now = time();
			$min= ($time_now/ 60 % 60);
			$sec = $time_now %60;
			
			$increment = $min.$sec;
			$row->alias = $row->alias.$increment;


			if (!$row->check()) {
				return JError::raiseWarning( 500, $row->getError() );
			}
			if (!$row->store()) {
				return JError::raiseWarning( 500, $row->getError() );
			}
			$row->checkin();
			unset($row);
			
			$sql = "SELECT max(id) FROM `#__guru_program` ";
			$db->setQuery( $sql );
			$new_prog_id = $db->loadColumn();	
			$new_prog_id = $new_prog_id[0];
			
			// we will duplicate now the days from the program - begin
			$sql = "SELECT id FROM `#__guru_days` WHERE pid = ".$old_prog_id;
			$db->setQuery($sql);
			$the_days_array = $db->loadColumn();		

			// duplicate exercise files for course ----------------------------------
			$sql = "select * from #__guru_mediarel where `type`='pmed' and `type_id`=".intval($old_prog_id);
			$db->setQuery($sql);
			$db->query();
			$old_exercises = $db->loadAssocList();
			if(isset($old_exercises) && count($old_exercises) > 0){
				foreach($old_exercises as $key=>$mediarel_value){
					$type = "pmed";
					$type_id = $new_prog_id;
					$media_id = $mediarel_value["media_id"];
					$sql = "insert into #__guru_mediarel (`type`, `type_id`, `media_id`) values ('".$type."', ".intval($type_id).", ".intval($media_id).")";
					$db->setQuery($sql);
					$db->query();
				}
			}
			// duplicate exercise files for course ----------------------------------
			
			foreach($the_days_array as $one_day) {
				guruAdminModelguruProgram::duplicate_day($one_day, $new_prog_id, $old_prog_id);
			}
			
			guruAdminModelguruProgram::duplicate_plans($new_prog_id, $old_prog_id);
			
			// we will duplicate now the days from the program - end
		}
	return 1;
				
	}		

	function duplicate_plans($prog_id, $old_prog_id){
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM `#__guru_program_plans` WHERE product_id = ".$old_prog_id;
		$db->setQuery($sql);
		$program_plans = $db->loadObjectList();
		
		foreach($program_plans as $value){
			$sql = "INSERT INTO `#__guru_program_plans` 
									( 
										`product_id` , 
										`plan_id` , 
										`price` , 
										`default`
							) VALUES (
										'".$prog_id."', 
										'".$value->plan_id."' , 
										'".$value->price."'	,
										'".$value->default."'											
									)";
			$db->setQuery($sql);
			if (!$db->query() ){
				$this->setError($db->getErrorMsg());
				return false;
			}
		}
	}
	
	function duplicate_day($old_day_id, $prog_id,$old_prog_id) {
		$db = JFactory::getDBO();
		
		$sql = "SELECT * FROM `#__guru_days` WHERE id = ".$old_day_id;
		$db->setQuery($sql);
		$the_day_object = $db->loadObject();

		$sql = "SELECT imagesin FROM `#__guru_config` WHERE id = 1";
		$db->setQuery($sql);
		$configs = $db->loadColumn();
		$configs = $configs[0];
		
		$new_image = $the_day_object->image;
		if($the_day_object->image!='')
			{
				$new_image = 'copy_'.$the_day_object->image;
				// do a copy of the image on the server
				copy(JPATH_SITE.'/'.$configs.'/'.$the_day_object->image, JPATH_SITE.'/'.$configs.'/'.$new_image);
			}

		$sql = "INSERT INTO `#__guru_days` 
											( 
												`pid` , 
												`title` , 
												`description` , 
												`image` , 
												`published` ,
												`startpublish`,
												`endpublish`,
												`metatitle`,
												`metakwd`,
												`metadesc`,
												`afterfinish`,
												`url`,
												`pagetitle`,
												`pagecontent`,
												`ordering`,
												`locked`
									) VALUES (
												'".$prog_id."', 
												'".mysql_escape_string($the_day_object->title)."', 
												'".mysql_escape_string($the_day_object->description)."' , 
												'".$new_image."', 
												'".$the_day_object->published."',
												'".$the_day_object->startpublish."',
												'".$the_day_object->endpublish."',
												'".mysql_escape_string($the_day_object->metatitle)."',
												'".mysql_escape_string($the_day_object->metakwd)."',
												'".mysql_escape_string($the_day_object->metadesc)."',
												'".$the_day_object->afterfinish."',
												'".$the_day_object->url."',
												'".mysql_escape_string($the_day_object->pagetitle)."',
												'".mysql_escape_string($the_day_object->pagecontent)."',
												'".mysql_escape_string($the_day_object->ordering)."',
												'".$the_day_object->locked."'												
											)";
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		$sql = "SELECT max(id) FROM `#__guru_days` ";
		$db->setQuery($sql);
		$the_day_copy_id = $db->loadColumn();
		$the_day_copy_id = $the_day_copy_id[0];
		
		// we duplicate now the tasks + media (inside mediarel table) - BEGIN
		$sql = "SELECT * FROM `#__guru_mediarel` WHERE type_id = ".$old_day_id;
		$db->setQuery($sql);
		$media_rel_object_list = $db->loadObjectList();
		
		$task_list = '';
		foreach($media_rel_object_list as $media_rel_object){
			$media_id = $media_rel_object->media_id;
			$mediaforvideo = $media_id ;

			if($media_rel_object->type == "dtask"){
				$sql = "select * from #__guru_task where `id`=".intval($media_rel_object->media_id);
				$db->setQuery($sql);
				$db->query();
				$old_lesson = $db->loadAssocList();
				if(isset($old_lesson) && count($old_lesson) > 0){
					$sql = "INSERT INTO `#__guru_task` (`name`, `alias`, `category`, `difficultylevel`, `points`, `image`, `published`, `startpublish`, `endpublish`, `metatitle`, `metakwd`, `metadesc`, `time`, `ordering`, `step_access`) VALUES ('".addslashes(trim($old_lesson["0"]["name"]))."', '".addslashes(trim($old_lesson["0"]["alias"]))."', ".intval($old_lesson["0"]["category"]).", '".trim($old_lesson["0"]["difficultylevel"])."', ".intval($old_lesson["0"]["points"]).", '".trim($old_lesson["0"]["image"])."', '".$old_lesson["0"]["published"]."', '".$old_lesson["0"]["startpublish"]."', '".$old_lesson["0"]["endpublish"]."', '".addslashes(trim($old_lesson["0"]["metatitle"]))."', '".addslashes(trim($old_lesson["0"]["metakwd"]))."', '".addslashes(trim($old_lesson["0"]["metadesc"]))."', ".$old_lesson["0"]["time"].", ".$old_lesson["0"]["ordering"].", ".$old_lesson["0"]["step_access"].")";
					$db->setQuery($sql);
					if($db->query()){
						$sql = "select max(id) from #__guru_task";
						$db->setQuery($sql);
						$db->query();
						$media_id = $db->loadResult();

					}

					$sql = "select * from `#__guru_mediarel` WHERE type_id = ".$mediaforvideo;
					$db->setQuery($sql);
		            $media_content = $db->loadObjectList();

		            foreach($media_content as $value){
		            	if($value->type =='scr_m' || $value->type =='scr_t'){
			            	$sql = "INSERT INTO `#__guru_mediarel` 
													( 
														`type` , 
														`type_id` , 
														`media_id` , 
														`mainmedia`,
														`layout`
											) VALUES (
														'".$value->type."', 
														'".$media_id."', 
														'".$value->media_id."' , 
														'".$value->mainmedia."'	,
														'".$value->layout."'											
													)";
							$db->setQuery($sql);
							if (!$db->query() ){
								$this->setError($db->getErrorMsg());
								return false;
							}
						}
						elseif ($value->type =='scr_l') {
							$sql = "INSERT INTO `#__guru_mediarel` 
													( 
														`type` , 
														`type_id` , 
														`media_id` 
											) VALUES (
														'".$value->type."', 
														'".$media_id."', 
														'".$value->media_id."' 											
													)";
							$db->setQuery($sql);
							if (!$db->query() ){
								$this->setError($db->getErrorMsg());
								return false;
							}
								
						}	
		            }

				}
			}
			
			$sql = "INSERT INTO `#__guru_mediarel` 
												( 
													`type` , 
													`type_id` , 
													`media_id` , 
													`mainmedia`
										) VALUES (
													'".$media_rel_object->type."', 
													'".$the_day_copy_id."', 
													'".$media_id."' , 
													'".$media_rel_object->mainmedia."'												
												)";
			$db->setQuery($sql);
			if (!$db->query() ){
				$this->setError($db->getErrorMsg());
				return false;
			}
		}
		// we duplicate now the tasks + media (inside mediarel table) - END
	}
	
	function exportFile(){
		$header = "First Name, Last Name, Email, Username"; 
		$data  = $header."\n";
		$pid = JRequest::getVar("pid", 0, 'post','int');
		$db = JFactory::getDBO();
		$sql = "select distinct u.username, u.email, c.firstname, c.lastname  from #__guru_customer c, #__users u, #__guru_buy_courses bc where c.id=bc.userid and bc.course_id =".intval($pid)." and bc.userid=u.id order by c.id desc";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadObjectList();
		$n = count($result);
		for ($i = 0; $i < $n; $i++){
			$firstname = $result[$i]->firstname;
			$lastname  = $result[$i]->lastname;
			$username = $result[$i]->username;
			$email = $result[$i]->email;
			
			$data .= ''.$firstname.' , '.$lastname.', '.$email.', '.$username.''."\n";
		}
		header("Content-Type: application/x-msdownload");
		header("Content-Disposition: attachment; filename=Students_Enrolled.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $data;
		exit();
	
	}
	
	function getDateFormat(){
		$db = JFactory::getDBO();
		$sql = "Select datetype FROM #__guru_config where id=1 ";
		$db->setQuery($sql);
		$format_date = $db->loadColumn();
		$format_date = $format_date[0];
		return $format_date;
	}
	
	function approve(){
		$cids = JRequest::getVar("cid", array(), "request", "array");
		$db = JFactory::getDBO();
		$sql = "update #__guru_program set `status`='1' where `id`=".intval($cids["0"]);
		$db->setQuery($sql);
		if(!$db->query()){
			return FALSE;
		}
		
		$this->sendEmailForApprove($cids["0"]);
		
		return TRUE;
	}
	
	function pending(){
		$cids = JRequest::getVar("cid", array(), "request", "array");
		$db = JFactory::getDBO();
		$sql = "update #__guru_program set `status`='0' where `id`=".intval($cids["0"]);
		$db->setQuery($sql);
		if(!$db->query()){
			return FALSE;
		}
		
		$this->sendEmailForPending($cids["0"]);
		
		return TRUE;
	}
	
	function sendEmailForApprove($course_id){
		$db = JFactory::getDBO();
		$sql = "select p.*, u.name as username from #__guru_program p, #__users u where u.`id`=p.`author` and p.`id`=".intval($course_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		
		$sql = "select `template_emails`, `fromname`, `fromemail` from #__guru_config";
		$db->setQuery($sql);
		$db->query();
		$confic = $db->loadAssocList();
		$template_emails = $confic["0"]["template_emails"];
		$template_emails = json_decode($template_emails, true);
		$fromname = $confic["0"]["fromname"];
		$fromemail = $confic["0"]["fromemail"];
		
		$sql = "select u.`email` from #__users u where u.`id`=".intval($result["0"]["author"]);
		$db->setQuery($sql);
		$db->query();
		$email = $db->loadColumn();
		$email = array(@$email["0"]);
		
		$app = JFactory::getApplication();
		$site_name = $app->getCfg('sitename'); 
		
		$subject = $template_emails["approve_subject"];
		$body = $template_emails["approve_body"];
		
		$approve_url = '<a href="'.JURI::root()."administrator/index.php?option=com_guru&controller=guruPrograms&cid[]=".intval($result["0"]["id"])."&task=approve".'" target="_blank">'.JURI::root()."administrator/index.php?option=com_guru&controller=guruPrograms&cid[]=".intval($result["0"]["id"])."&task=approve".'</a>';
		
		$subject = str_replace("[AUTHOR_NAME]", $result["0"]["username"], $subject);
		$subject = str_replace("[COURSE_NAME]", $result["0"]["name"], $subject);
		$subject = str_replace("[COURSE_APPROVE_URL]", $approve_url, $subject);
		$subject = str_replace("[SITE_NAME]", $site_name, $subject);
		
		$body = str_replace("[AUTHOR_NAME]", $result["0"]["username"], $body);
		$body = str_replace("[COURSE_NAME]", $result["0"]["name"], $body);
		$body = str_replace("[COURSE_APPROVE_URL]", $approve_url, $body);
		$body = str_replace("[SITE_NAME]", $site_name, $body);
		
		JFactory::getMailer()->sendMail($fromemail, $fromname, $email, $subject, $body, 1);
	}
	
	function sendEmailForPending($course_id){
		$db = JFActory::getDBO();
		$sql = "select p.*, u.name as username from #__guru_program p, #__users u where u.`id`=p.`author` and p.`id`=".intval($course_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		
		$sql = "select `template_emails`, `fromname`, `fromemail`, `admin_email` from #__guru_config";
		$db->setQuery($sql);
		$db->query();
		$confic = $db->loadAssocList();
		$template_emails = $confic["0"]["template_emails"];
		$template_emails = json_decode($template_emails, true);
		$fromname = $confic["0"]["fromname"];
		$fromemail = $confic["0"]["fromemail"];
		
		$sql = "select u.`email` from #__users u, #__user_usergroup_map ugm where u.`id`=ugm.`user_id` and ugm.`group_id`='8' and u.`id` IN (".$confic["0"]["admin_email"].")";
		$db->setQuery($sql);
		$db->query();
		$email = $db->loadColumn();
		
		$app = JFactory::getApplication();
		$site_name = $app->getCfg('sitename'); 
		
		$subject = $template_emails["unapprove_subject"];
		$body = $template_emails["unapprove_body"];
		
		$approve_url = '<a href="'.JURI::root()."administrator/index.php?option=com_guru&controller=guruPrograms&cid[]=".intval($result["0"]["id"])."&task=approve".'" target="_blank">'.JURI::root()."administrator/index.php?option=com_guru&controller=guruPrograms&cid[]=".intval($result["0"]["id"])."&task=approve".'</a>';
		
		$subject = str_replace("[AUTHOR_NAME]", $result["0"]["username"], $subject);
		$subject = str_replace("[COURSE_NAME]", $result["0"]["name"], $subject);
		$subject = str_replace("[COURSE_APPROVE_URL]", $approve_url, $subject);
		$subject = str_replace("[SITE_NAME]", $site_name, $subject);
		
		$body = str_replace("[AUTHOR_NAME]", $result["0"]["username"], $body);
		$body = str_replace("[COURSE_NAME]", $result["0"]["name"], $body);
		$body = str_replace("[COURSE_APPROVE_URL]", $approve_url, $body);
		$body = str_replace("[SITE_NAME]", $site_name, $body);
		
		for($i=0; $i< count($email); $i++){
			JFactory::getMailer()->sendMail($fromemail, $fromname, $email[$i], $subject, $body, 1);
		}
	}
};
?>