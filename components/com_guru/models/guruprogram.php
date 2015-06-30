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

class guruModelguruProgram extends JModelLegacy {
	var $_attributes;
	var $_attribute;
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
			$params = $db->loadResult();
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
		$this->_attribute = null;
	}

	function getlistPrograms () {
		$catId	= JRequest::getVar("cid","0","get","int");
		$jnow 	= JFactory::getDate();
		$date 	= $jnow->toSQL();
		$db		= JFactory::getDBO();
		$result	= array();
		
		if($catId>0){
			$sql = "SELECT name from #__guru_category where id = '".$catId."'";
			$db->setQuery($sql);
			$db->query();
			$res = $db->loadObject();
			$result['catName'] = $res->name;
		
			$sql = "SELECT * from #__guru_program where catid = '".$catId."'
					AND published = 1 and startpublish <= '".$date."' and (endpublish> '".$date."' or endpublish='0000-00-00')";
			$db->setQuery($sql);
			$db->query();
			$res = $db->loadObjectList();
			$result['courses'] = $res;
		}
		return $result;
	}	
	
	function getpdays () {
		if(isset($_REQUEST['cid'])){
			$sql = "SELECT * FROM #__guru_days WHERE pid='".intval($_REQUEST['cid'])."' ORDER BY ordering ASC";
			$pdays = $this->_getList($sql);
			return $pdays;
		}
		return NULL;
	}
	
	function get_a_day_by_id ($day_id) {
			$database = JFactory::getDBO();
			$sql = "SELECT * FROM #__guru_days WHERE id='".$day_id."' ";
			$database->setQuery($sql);
			$aday = $database->loadObject();

			return $aday;

	}

	function getProgram() {
		$database = Jfactory::getDBO();
		if (empty ($this->_attribute)) { 
			$this->_attribute =$this->getTable("guruPrograms");
			$this->_attribute->load($this->_id);
		}
		$data = JRequest::get('post'); 
		if (!$this->_attribute->bind($data)){
			$this->setError($item->getErrorMsg());
			return false;
	
		}
		if (!$this->_attribute->check()) {
			$this->setError($item->getErrorMsg());
			return false;
		}
		$sql="SELECT sum(lt.time) AS course_time 
			  FROM `#__guru_task` as lt 
			  LEFT JOIN `#__guru_mediarel` lm on lt.id=lm.media_id 
			  LEFT JOIN `#__guru_days` as ld on lm.type_id=ld.id
			  WHERE type='dtask' and ld.pid=".$this->_id;
		$database->setQuery($sql);
		$database->query();
		$result=$database->loadResult();
		$hours=intval($result/60);
		$minutes=$result%60;
		$this->_attribute->duration=$hours.":".$minutes;	
		
		switch ($this->_attribute->level){
			case "0":
				$this->_attribute->level=JText::_('GURU_LEVEL_BEGINER');
				break;
			case "1":
				$this->_attribute->level=JText::_('GURU_LEVEL_INTERMEDIATE');
				break;
			case "2":
				$this->_attribute->level=JText::_('GURU_LEVEL_ADVANCED');
				break;
		}
		return $this->_attribute;
	}
	
	function getConfigSettings(){
		$sql = "SELECT * FROM #__guru_config WHERE id=1";
		$ConfigSettings = $this->_getList($sql);
		return $ConfigSettings[0];
	}
	
	function getsum_points_and_time () {
			$db = JFactory::getDBO();
			$sql ="SELECT id FROM #__guru_days WHERE pid=".intval(JRequest::getVar("cid", ""));
			$db->setQuery($sql);
			$db->query();
			$temp = $db->loadColumn();
						
			$sql ="SELECT media_id FROM #__guru_mediarel WHERE type='dtask' AND type_id in (".implode(',',$temp).")";
			$db->setQuery($sql);
			$db->query();
			$temp = $db->loadColumn();
						
			$sql ="SELECT sum(points) as s_points, sum(time) as s_time FROM #__guru_task WHERE id in (".implode(',',$temp).")";
			$sum_points_time = $this->_getList($sql);
		
			return $sum_points_time;
	}	

	function getsum_points_and_time_for_program ($pid) {
			$database = JFactory::getDBO();
			$sql = "SELECT sum(points) as s_points, sum(time) as s_time FROM #__guru_task WHERE id in (SELECT media_id FROM #__guru_mediarel WHERE type='dtask' AND type_id in ( SELECT id FROM #__guru_days WHERE pid=".intval($pid)." )  ) ";  
			$database->setQuery($sql);
			$rows = $database->loadObjectList();
			return $rows;
	}	

	function getsum_points_and_time_for_a_day1 ($day_id) {
			$database = JFactory::getDBO();
			$sql = "SELECT sum(points) as s_points, sum(time) as s_time FROM #__guru_task WHERE id in (SELECT media_id FROM #__guru_mediarel WHERE type = 'dtask' AND type_id = '".intval($day_id)."')" ;  
			$database->setQuery($sql);
			$rows = $database->loadObjectList();
			return $rows;
	}	

	function getsum_points_and_time_for_a_day2 ($task_id_array) {
			$database = JFactory::getDBO();
			$sql = "SELECT sum(points) as s_points, sum(time) as s_time FROM #__guru_task WHERE id in (".$task_id_array.")";
			$database->setQuery($sql);
			$rows = $database->loadObjectList();
			return $rows;
	}		

	function getmyprograms () { 
			$my = JFactory::getUser();
			$sql = "SELECT ord.*,prog.*,stat.* FROM #__guru_programstatus as stat, #__guru_program as prog
			LEFT JOIN #__guru_order as ord on ord.programid = prog.id
			WHERE (prog.id = stat.pid AND stat.userid = ".$my->id.")
			AND ord.userid = ".$my->id."
			GROUP BY prog.id
			ORDER BY prog.id ASC
			";  
			$my_programs = $this->_getList($sql);
		
			return $my_programs;

	}
	
	function find_id_for_first_uncompleted_day($day_array){
		$search_id_for_first_day_uncompleted = -1;
		foreach($day_array as $day_array_key=>$day_array_value)
			{
				if (strpos($day_array_value, ',0')!==FALSE || strpos($day_array_value, ',1')!==FALSE)
					{
						$search_id_for_first_day_uncompleted = $day_array_key;
						break;
					}
			}
		
		$search_array_id_for_first_day_uncompleted = explode(',', $day_array[$search_id_for_first_day_uncompleted]);
		$id_for_first_day_uncompleted = $search_array_id_for_first_day_uncompleted[0];
	
		return $id_for_first_day_uncompleted.','.($search_id_for_first_day_uncompleted+1);
	}	


	function find_id_for_first_uncompleted_task($task_array){
		$search_id_for_first_task_uncompleted = 0;
		
		foreach($task_array as $task_array_key=>$task_array_value)
			{
				if (strpos($task_array_value, ',0')!==FALSE || strpos($task_array_value, ',1')!==FALSE)
					{
						$search_id_for_first_task_uncompleted = $task_array_key;
						break;
					}
			}

		$search_array_id_for_first_task_uncompleted = explode(',', $task_array[$search_id_for_first_task_uncompleted]);
		$id_for_first_task_uncompleted = $search_array_id_for_first_task_uncompleted[0];
		return $id_for_first_task_uncompleted.','.($search_id_for_first_task_uncompleted+1);
	}	
	
	function find_day_status($userid, $progid, $dayid) {
		$database = JFactory::getDBO();
		$sql = "SELECT days FROM #__guru_programstatus WHERE pid =".$progid." AND userid = ".$userid;
		$database->setQuery($sql);
		$result = $database->loadObjectList();
		//print_r($result[0]);
		
		if(isset($result[0]))
			{
				$day_array = $result[0]->days;
				$day_array = explode(';', $day_array);
			}	
		$status = 0;
		if(isset($result[0]))
		foreach($day_array as $day_value)
			{
				$day_value_array = explode(',', $day_value);		
				if($day_value_array[0]==$dayid)
					$status = $day_value_array[1];	
			}
		return $status;
	}	
	
	function find_program_tasks($progid) {
		$database = JFactory::getDBO();
		$sql = "SELECT * FROM #__guru_task WHERE id IN (SELECT media_id FROM #__guru_mediarel WHERE type = 'dtask' AND type_id in (SELECT id FROM #__guru_days WHERE pid =".$progid.") ) ";
		$database->setQuery($sql);
		$result = $database->loadObjectList();
		return $result;
	}		
	
	
	function find_program_days($progid){
		$database = JFactory::getDBO();
		$sql = "SELECT id, ordering,title FROM #__guru_days WHERE pid =".$progid." ORDER BY ordering ASC";
		$database->setQuery($sql);
		$result = $database->loadObjectList();
		return $result;	
	}
	
	function find_day_tasks($dayid){
		$database = JFactory::getDBO();
		$sql = "SELECT * FROM #__guru_task WHERE id IN (SELECT media_id FROM #__guru_mediarel WHERE type = 'dtask' AND type_id = ".intval($dayid).")";
		$database->setQuery($sql);
		$result = $database->loadObjectList();
		return $result;		
	}
	
	function find_intro_media($progid) {
		$database = JFactory::getDBO();
		$sql = "SELECT * FROM #__guru_media WHERE id = (SELECT media_id FROM #__guru_mediarel WHERE type = 'pmed' AND type_id = ".intval($progid)." LIMIT 1)";
		$database->setQuery($sql);
		$result = $database->loadObject();
		return $result;			
	}


	function find_if_rogram_was_bought($userid, $progid){
		// returns 1 if the program is already bought
		// return 0 if the program is TRIAL or wasn't bought
		$database = JFactory::getDBO();
		$sql = "SELECT payment FROM #__guru_order WHERE userid = '".$userid."' AND programid = ".$progid;
		$database->setQuery($sql);
		$result = $database->loadResult();
		if (strtolower($result) == 'trial' || !isset($result))	
			return 0;
		else return 1;	
	}
	
	function find_status_line_for_program($userid, $progid){
		$database = JFactory::getDBO();
		$sql = "SELECT days,tasks FROM #__guru_programstatus WHERE userid = '".$userid."' AND pid = ".$progid;
		$database->setQuery($sql);
		$result = $database->loadObject();
		return $result;
	}	
	
	function find_link_text_for_day_resume_button($day_array, $task_array, $status){
		//$day_array = $status_line->days;
		$day_array = explode(';', $day_array);
		$how_many_days = count($day_array)-1;
		$day_id_to_get_started_array = explode(',', $day_array[0]); 
		
		//$task_array = $status_line->tasks;
		$task_array = explode(';', $task_array);
		$task_id_array = explode('-', $task_array[0]);
		$task_id_to_get_started_array = explode(',', $task_id_array[0]); 

		// we find the id for the first day who isn't completed

		
		if($status=='1')
			{	
				$first_day_uncompleted = guruModelguruProgram::find_id_for_first_uncompleted_day($day_array);
				$first_day_uncompleted = explode(',', $first_day_uncompleted);
				$id_for_first_day_uncompleted = $first_day_uncompleted[0];
				$ordering_for_first_day_uncompleted = $first_day_uncompleted[1];

				$first_task_uncompleted = guruModelguruProgram::find_id_for_first_uncompleted_task(explode('-',$task_array[($ordering_for_first_day_uncompleted-1)]));
				$first_task_uncompleted = explode(',', $first_task_uncompleted);
				$id_for_first_task_uncompleted = $first_task_uncompleted[0];
				$ordering_for_first_task_uncompleted = $first_task_uncompleted[1];
			}

if ($status=='0' && isset($task_id_to_get_started_array[0]) && $task_id_to_get_started_array[0]>0 && isset($day_id_to_get_started_array[0]) && $day_id_to_get_started_array[0]>0)
	{
		$link_for_resume = 	'index.php?option=com_guru&view=guruTasks&task=view&cid=1&pid='.$day_id_to_get_started_array[0];
		$text_for_resume = JText::_('GURU_MYPROGRAMS_ACTION_GETSTARTED');
	}
	
if ($status=='1' && isset($id_for_first_task_uncompleted) && $id_for_first_task_uncompleted>0 && isset($id_for_first_day_uncompleted) && $id_for_first_day_uncompleted>0)
	{
		$link_for_resume = 	'index.php?option=com_guru&view=guruTasks&task=view&cid='.$ordering_for_first_task_uncompleted.'&pid='.$id_for_first_day_uncompleted;	
		$text_for_resume = JText::_('GURU_DAYS_RESUME_BUTTON');	
	}	
if ($status=='2' && isset($task_id_to_get_started_array[0]) && $task_id_to_get_started_array[0]>0 && isset($day_id_to_get_started_array[0]) && $day_id_to_get_started_array[0]>0) 
	{
		$link_for_resume = 'index.php?option=com_guru&view=guruTasks&task=view&cid=1&pid='.$day_id_to_get_started_array[0].'&s=0';	
		$text_for_resume = JText::_('GURU_MYPROGRAMS_ACTION_STARTAGAIN');	
	}
if ($status=='-1') 
	{
		$the_day_id = $day_id_to_get_started_array[0];
		
		$db = JFactory::getDBO();
		$sql = "SELECT pid FROM #__guru_days 
				WHERE id = ".$the_day_id;
		$db->setQuery($sql);
		$result = $db->loadResult();		
		
		$link_for_resume = 'index.php?option=com_guru&view=guruProfile&task=buy&cid='.$result;	
		$text_for_resume = JText::_('GURU_MYPROGRAMS_ACTION_BUYAGAIN');	
	}	
	
	return $link_for_resume.'$$$$$'.$text_for_resume;
	
	}	

	function program_status($userid, $progid){
		$database = JFactory::getDBO();
		$sql = "SELECT status FROM #__guru_programstatus WHERE userid = '".$userid."' AND pid = ".$progid;
		$database->setQuery($sql);
		$result = $database->loadResult();
		return $result;
	}	
	
	function getSubCategory($id){
		$database = JFactory::getDBO();
		$offset = JFactory::getApplication()->getCfg('offset');
		$jnow = JFactory::getDate('now', $offset);
		$date 	= $jnow;
		$sql = "SELECT t.name, t.alias, t.time, t.id, p.chb_free_courses, p.step_access_courses, p.lessons_show, p.selected_course, t.ordering, t.step_access, t.difficultylevel
				FROM #__guru_task t
				LEFT JOIN #__guru_mediarel m
				ON m.media_id=t.id
				LEFT JOIN #__guru_days d 
				ON m.type_id=d.id
				LEFT JOIN #__guru_program p
				ON d.pid = p.id
				WHERE d.id=".$id." and m.type='dtask'
				AND t.published=1 
				AND t.startpublish <= '".$date."'  
				AND (t.endpublish = '0000-00-00 00:00:00' OR t.endpublish >= '".$date."') 
				GROUP BY t.id 
				ORDER BY ordering ASC ";
				
		$database->setQuery($sql);
		$rows = $database->loadAssocList();
		
		return $rows;
	}
	
	function getProgramContent(){
		$database = JFactory::getDBO();
		$sql = "select id, title, alias from #__guru_days 
				WHERE pid=".intval($this->_attribute->id)." ORDER BY `ordering`";
				
		$database->setQuery($sql);
		$rows = $database->loadAssocList();
		return $rows;
	}



	function getReqCourses(){
		$result = array();
		$database = JFactory::getDBO();
		$sql = "SELECT lp.id, name from #__guru_program as lp
		  		LEFT JOIN #__guru_mediarel as lm 
		   		on lp.id=lm.media_id
		   		where type_id=".intval($this->_attribute->id)." and lm.type='preq'";
		$database->setQuery($sql);
		$rows = $database->loadAssocList();
		
		if(isset($rows) && count($rows) > 0){
			foreach($rows as $key=>$value){
				$result[] = '<a href="index.php?option=com_guru&view=guruPrograms&task=view&cid='.$value["id"].'">'.$value["name"]."</a>";
			}
		}
		return $result;
	}

	function getAuthor(){
		if(empty($this->_attribute)){ 
			$this->_attribute =$this->getTable("guruPrograms");
			$this->_attribute->load($this->_id);
		}
		$db = JFactory::getDBO();		
		$sql = "SELECT * FROM #__users u LEFT JOIN #__guru_authors a ON u.id=a.userid WHERE a.userid=".intval($this->_attribute->author);
		$db->setQuery($sql);
		$db->query();
		$author = $db->loadObject();
		if(isset ($author->images)&& trim($author->images)!=""){
			$path=explode("/",$author->images);
			$author->imageName=$path[count($path)-1];
		}				
		return $author;
	}
	
	function getExercise(){
		$db= JFactory::getDBO();
		$sql = "SELECT lm.*, lmr.access as access,type_id
				FROM `#__guru_media` as lm
				LEFT JOIN `#__guru_mediarel` as lmr
				ON lm.id=lmr.media_id
				WHERE `lmr`.`type_id` =".intval($this->_attribute->id)."
				AND lm.published = 1 and lmr.type='pmed' order by lmr.order ";
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		
		for($i=0;$i<count($result);$i++){
			switch($result[$i]->type){
				case "video":
					if($result[$i]->width==0 && $result[$i]->height==0){
						$result[$i]->width=400;
						$result[$i]->height=300;
					}
					else {
						$result[$i]->width+=50;
						$result[$i]->height+=50;
					}
					break;
				case "audio":
					if($result[$i]->width==0){
						$result[$i]->width=400;
					} 
					else $result[$i]->width+=50;
					$result[$i]->height=300;
					break;
				case "docs":
					if($result[$i]->width==0 && $result[$i]->height==0){
						$result[$i]->width=400;
						$result[$i]->height=300;
					}
					else {
						$result[$i]->width+=50;
						$result[$i]->height+=50;
					}
					break;
				case "image":
					$img_size = @getimagesize(JPATH_SITE.DS.$config->imagesin.'/'.$result[$i]->local);
					if($result[$i]->height>0){	
						$result[$i]->width = $img_size[0] +50;		
					}
					else{
						$result[$i]->height = $img_size[1] +50;		
					}
					break;
				default:
					$result[$i]->width=400;
					$result[$i]->height=300;
					break;
			}
		}
		
		return $result;
	}
	
	function getAuthorCourses(){
		$db= JFactory::getDBO();
		
		$jnow 	= JFactory::getDate();
		$date 	= $jnow->toSQL();
	
		$sql = "SELECT lp.id, lp.name, lp.startpublish, sum(lt.time) AS course_time,
			CASE lp.level WHEN 0 THEN 'beginner_level'
					   WHEN 1 THEN 'intermediate_level'
					   WHEN 2 THEN 'advanced_level'
			END 
			AS level
			FROM `#__guru_program` as lp
			LEFT JOIN `#__guru_days` as ld on lp.id=ld.pid  
			LEFT JOIN `#__guru_mediarel` as lm on ld.id=lm.type_id 
			LEFT JOIN `#__guru_task` as lt on lt.id=lm.media_id 
			WHERE lp.author=".intval($this->_attribute->author)."  
			AND lp.published=1 
			AND lp.startpublish<='".$date."' 
			AND (lp.endpublish>'".$date."' or lp.endpublish='0000-00-00 00:00:00')
			GROUP BY lp.id";
		$db->setQuery($sql);
		$courses = $db->loadObjectList();
		return $courses;
	}
	
	function getFreeForGroups($id){
		$db = JFactory::getDBO();
		$sql = "select name from #__core_acl_aro_groups where id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}
	
	function getPrices($id){
		$db = JFactory::getDBO();
		$sql = "select s.name, pp.price from #__guru_program p left join #__guru_program_plans pp on p.id=pp.product_id left join #__guru_subplan s on s.id=pp.plan_id where p.id=".intval($id)." order by s.ordering asc";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function getUserCourses(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id = $user->id;
		$sql = "select course_id from #__guru_buy_courses where userid=".intval($user_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("course_id");
		return $result;
	}
	
	function isCustomer(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id = $user->id;
			
		$sql = "select count(*) from #__guru_buy_courses bc, #__guru_customer c where bc.userid=".intval($user_id)." and c.id=".intval($user_id) ;
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if($result > 0){
			return true;
		}
		else{
			return false;
		}
	}
	function hasAtLeastOneCourse(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id = $user->id;
		$course_id = intval(JRequest::getVar("cid", 0));
		$sql = "SELECT count(*) FROM #__guru_buy_courses where `userid`=".intval($user_id)." and course_id <>".$course_id;
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if($result > 0){
			return true;
		}
		else{	
			return false;
		}
	}
	function getOnlyPrices($id){
		$db = JFactory::getDBO();
		$sql = "select pp.price from #__guru_program p, #__guru_program_plans pp WHERE p.id=pp.product_id and pp.default=1 and pp.product_id=".$id;
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return number_format($result,2);
	}
	function getOnlyPricesR($id){
		$db = JFactory::getDBO();
		$sql = "select min(pp.price) from #__guru_program p, #__guru_program_plans pp WHERE p.id=pp.product_id and pp.product_id=".$id;
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return number_format($result,2);
	}
	function getLessonReleaseType($id){
		$db = JFactory::getDBO();
		$sql = "select lesson_release, course_type  from #__guru_program where id=".$id;
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssoc();
		return $result;
	}	
	function getStudentAmount($id){
		$db = JFactory::getDBO();
		$sql = "SELECT count(distinct bc.userid) FROM #__guru_buy_courses bc, #__users u , #__guru_customer c, #__guru_order o WHERE c.id=bc.userid and  bc.userid=u.id and bc.course_id=".$id." and o.`userid`=c.`id` and o.`userid`=bc.`userid`";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result[0];
	}		
	public static function getCourseTypeDetails($id){
		$db = JFactory::getDBO();
		$sql = "select DATE_FORMAT(p.start_release, '%Y-%m-%d') as start_release, p.course_type, p.lesson_release,  p.lessons_show from #__guru_program p WHERE p.id=".$id;
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}			

	function enroll(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id = $user->id;
		$course_id = intval(JRequest::getVar("cid", "0"));
		if($course_id == 0){
			$course_id = intval(JRequest::getVar("course_id", "0"));
		}
		$courses = intval($course_id)."-0.0-1";
		$amount = 0;
		$buy_date = date("Y-m-d H:i:s");
		$plan_id = "1";
		$order_expiration = "0000-00-00 00:00:00";
		$jnow = JFactory::getDate();
		$current_date_string = $jnow->toSQL();
		
		$sql = "select count(*) from #__guru_customer  where id=".intval($user_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
			if($result > 0){
				$iscustomer =  true;
			}
			else{
				$iscustomer = false;
			}
		
		$sql = "SELECT chb_free_courses, step_access_courses, selected_course FROM `#__guru_program` where id = ".intval($course_id);
		$db->setQuery($sql);
		$db->query();
		$result= $db->loadAssocList();
		$chb_free_courses = $result["0"]["chb_free_courses"];
		$step_access_courses = $result["0"]["step_access_courses"];
		$selected_course = $result["0"]["selected_course"];
		if($chb_free_courses == 1){
			if($step_access_courses ==1){
				$iscustomer =  true;
			}
			elseif($step_access_courses ==0 && $selected_course == -1){
				if($this->hasAtLeastOneCourse() && $this->isCustomer()){
					$iscustomer =  true;
				}
				else{
					$iscustomer =  false;
				}
			}
		}	
		
		$temp = explode(" ", $user->name);
		if(isset($temp) && count($temp) > 1){		
			$last_name = $temp[count($temp) - 1];	
			unset($temp[count($temp) - 1]);
			$first_name = implode(" ", $temp); 
		}
		else{
			if(count($temp) == 1){
				$first_name = $user->name;
				$last_name  = $user->name;
			}
		}
		
		$sql = "SELECT count(id) FROM`#__guru_customer`where id=".intval($user_id);
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		$count = $count[0];
		
		if($count == 0) {
			$sql = "INSERT INTO `#__guru_customer`(`id`,`company`, `firstname`, `lastname`) VALUES ('".intval($user_id)."','','".addslashes(trim($first_name))."','".addslashes(trim($last_name))."')";
			$db->setQuery($sql);
			$db->query();
		}
		$sql = "select count(*) from #__guru_buy_courses where `order_id`=0 and `userid`=".intval($user_id)." and `course_id`=".intval($course_id)." and expired_date < '".$current_date_string."'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if(($result == 0) && $iscustomer == true){// add a new license
				
			$sql = "select currency from #__guru_config where id=1" ;
			$db->setQuery($sql);
			$db->query();
			$currency = $db->loadColumn();
			$currency = $currency[0];
			
			$sql = "insert into #__guru_order (`userid`, `order_date`, `courses`, `status`, `amount`, `amount_paid`, `processor`, `number_of_licenses`, `currency`, `promocodeid`, `published`, `form`) values (".intval($user_id).", '".$buy_date."', '".intval($course_id)."-0-1', 'Paid', '0', '-1','paypaypal','0','".$currency."','0','1', '')";
			$db->setQuery($sql);
			$db->query();	
			
			$sql = "select MAX(id) from #__guru_order";
			$db->setQuery($sql);
			$db->query();
			$max_id = $db->loadColumn();
			$max_id = $max_id[0];
			
			$sql = "insert into #__guru_buy_courses (`userid`, `order_id`, `course_id`, `price`, `buy_date`, `expired_date`, `plan_id`, `email_send`) values (".$user_id.", ".$max_id." , ".$course_id.", '".$amount."', '".$buy_date."', '".$order_expiration."', '".$plan_id."', 0)";
			$db->setQuery($sql);
			$db->query();
			
			return 'now';
		}
		else {
			if($iscustomer){
				$sql = "update #__guru_buy_courses set `expired_date` = '".$order_expiration."', `plan_id` = 1 where `userid`=".intval($user_id)." and `course_id`=".intval($course_id)." and `order_id`=0 ";
				$db->setQuery($sql);
				$db->query();
				return 'old';		
			}
		}
		
	}
	
	function changeCompleted(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$userid = $user->id;
		$id = JRequest::getVar("cid", "0");
		$id = intval($id);
		
		$sql = "SELECT `lesson_id` from #__guru_viewed_lesson WHERE `user_id` =".intval($userid)." and pid=".$id;
		$db->setQuery($sql);
		$db->query();
		$result1 = $db->loadResult();
		$viewed = explode('||', trim($result1, "||"));
		
		$sql ="SELECT id FROM #__guru_days WHERE pid =".$id;
		$db->setQuery($sql);
		$db->query();
		$temp = $db->loadColumn();
		
		if(count($temp)>0){
			$sql ="SELECT media_id FROM #__guru_mediarel WHERE type = 'dtask' AND type_id in (".implode(',',$temp).")";
			$db->setQuery($sql);
			$db->query();
			$temp = $db->loadColumn();
					
			$sql ="SELECT id FROM #__guru_task WHERE id IN (".implode(',',$temp).")";
			$db->setQuery($sql);
			$db->query();
			$all_lessons = $db->loadColumn();
			
			$diff = array_diff($all_lessons, $viewed);
			if(isset($diff) && count($diff) > 0){
				$sql = "update #__guru_viewed_lesson set `completed`=0 WHERE `user_id` =".intval($userid)." and pid=".intval($id);
				$db->setQuery($sql);
				$db->query();
			}
		}
	}
};
?>