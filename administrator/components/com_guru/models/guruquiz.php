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


class guruAdminModelguruQuiz extends JModelLegacy {
	var $_languages;
	var $_id = null;
	var $_total = 0;
	var $_pagination = null;
	
	function __construct () {
		parent::__construct();
		global $option;
		$cids = JRequest::getVar('cid', 0, '', 'array');
		$mainframe = JFactory::getApplication("admin");
		// Get the pagination request variables
		$app = JFactory::getApplication('administrator');
		$limit = $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$limitstart = $app->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );

		$this->setState('limit', $limit); // Set the limit variable for query later on
		$this->setState('limitstart', $limitstart);
		$this->setId((int)$cids[0]);
		
		if(JRequest::getVar("limitstart") == JRequest::getVar("old_limit")){
			JRequest::setVar("limitstart", "0"); 
			$this->setState('limitstart', 0);
		}
	}


	function setId($id) {
		$this->_id = $id;
		$this->_installpath = JPATH_COMPONENT.DS."plugins".DS;
		$this->_plugin = null;
	}
	public static function StudentsQuizzNo($id){
		$db = JFactory::getDBO();
		$sql = "select count( distinct user_id) from #__guru_quiz_taken where quiz_id=".$id." and user_id <> 0";
		$db->setQuery($sql);
		$tmp = $db->loadResult();
		return $tmp;	
	}
	public static function NbOfTimesandStudents($quiz_id){
		$db = JFactory::getDBO();
		$sql = "SELECT `user_id`, GROUP_CONCAT(SUBSTRING_INDEX(`score_quiz` , '|', 1 ) / SUBSTRING_INDEX(`score_quiz` , '|', -1)) as score_by_user FROM `#__guru_quiz_taken` where `quiz_id` = ".$quiz_id." group by `user_id` having `user_id` <> 0";
		$db->setQuery($sql);
		return  $db->loadAssocList();
	}
	function getlistStudentsQuizTaken(){
		$quiz_id =  intval(JRequest::getVar("id", ""));
		$db = JFactory::getDBO();		
		$sql = "select u.id, u.username, u.email, c.firstname, c.lastname, tq.date_taken_quiz, tq.score_quiz, tq.`id` as tq_id  from #__guru_customer c, #__users u, #__guru_quiz_taken tq where c.id=u.id and c.id = tq.user_id and u.id IN (select  user_id from #__guru_quiz_taken where quiz_id=".$quiz_id.") and tq.quiz_id=".$quiz_id." order by c.id desc";
		$db->setQuery($sql);
		$tmp = $db->loadObjectList();
		return $tmp;
	}	
	
	function getScoreQuizTaken($quiz_id, $user_id, $tq_id){
		$db =JFactory::getDBO();
		$sql = "SELECT score_quiz FROM #__guru_quiz_taken WHERE user_id=".intval($user_id)." and quiz_id=".intval($quiz_id)." and id=".intval($tq_id);
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadResult();		
		return $result;		
	}
	
	function DataTaken($quiz_id,$user_id, $tq_id){
		$db =JFactory::getDBO();
		$sql = "SELECT 	date_taken_quiz FROM #__guru_quiz_taken WHERE user_id=".intval($user_id)." and quiz_id=".intval($quiz_id)." and id=".intval($tq_id);
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadResult();		
		return $result;	
	}	
	

	function getlistQuiz () {
		if(empty ($this->_plugins)) {
			$sql = "SELECT * FROM #__guru_quiz AS c WHERE 1=1 ";
			
			$search_cond=NULL;
			$limit_cond=NULL;
			
			if(isset($_SESSION['search_quiz'])) {
				$src=$_SESSION['search_quiz'];
				$search_cond=" AND (c.id LIKE '%".$src."%' OR c.name LIKE '%".$src."%' OR c.description LIKE '%".$src."%' or c.image LIKE '%".$src."%' )";
			}

			if(isset($_POST['search_quiz'])) {
				$src=$_POST['search_quiz'];
				$_SESSION['search_quiz']=$src;
				$search_cond=" AND (c.id LIKE '%".$src."%' OR c.name LIKE '%".$src."%' OR c.description LIKE '%".$src."%' or c.image LIKE '%".$src."%' )";
			}
			
			if(isset($_POST['quiz_select_type'])) {
				$src=$_POST['quiz_select_type'];
				$_SESSION['quiz_select_type']=$src;
				if($src == 1){
					$search_cond=" AND c.is_final= 0";
				}
				elseif($src == 2){
					$search_cond=" AND c.is_final= 1";
				}
			}
			
			$limitstart=$this->getState('limitstart');
			$limit=$this->getState('limit');
			
			if($limit!=0){
				$limit_cond=" LIMIT ".$limitstart.",".$limit." ";
			}
			
			$published=NULL;
			
			if(isset($_SESSION['quiz_publ_status'])) {
				$src=$_SESSION['quiz_publ_status'];
				if(($src=='Y')||($src=='N')){
					if($src=='Y'){$src=1;} else {$src=0;}
					$published=" AND c.published =".$src." ";
				}
			}

			if(isset($_POST['quiz_publ_status'])) {
				$src=$_POST['quiz_publ_status'];
				if(($src=='Y')||($src=='N')){
					if($src=='Y'){$src=1;} else {$src=0;}
					$published=" AND c.published =".$src." ";
				}
			}
			
			$orderby=' ORDER BY c.ordering';
			
			$sql.=$search_cond.$published;
			$this->_total=$this->_getListCount($sql);
			$this->_languages = $this->_getList($sql.$orderby.$limit_cond);			
		}
		
		return $this->_languages;
	}
		
	function getlistQuizTakenStud(){
		$db = JFactory::getDBO();
		$user_id = JRequest::getVar('cid',"");
		$pid = JRequest::getVar('pid',"");
		$sql = "SELECT * FROM #__guru_quiz_taken WHERE user_id=".$user_id." and pid=".$pid;
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadObjectList();
		
		$this->_total = count($result);
		
		$limitstart=$this->getState('limitstart');
		$limit=$this->getState('limit');
		
		if($limit!=0){
			$limit_cond=" LIMIT ".$limitstart.",".$limit." ";
		}
		
		$sql = "SELECT * FROM #__guru_quiz_taken WHERE user_id=".$user_id." and pid=".$pid;
		$db->setQuery($sql);
		$db->query();
		$result = $this->_getList($sql.$limit_cond);
		
		return $result;
	}
	function getshow_quizz_res(){
		$db = JFactory::getDBO();
		$quiz_id = JRequest::getVar('quiz_id',"");
		$sql = "SELECT * FROM #__guru_question WHERE qid=".$quiz_id;
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadObjectList();
		return $result;
	}
		
	function getQuizName($quiz_id){
		$db =JFactory::getDBO();
		$sql = "SELECT name FROM #__guru_quiz WHERE id=".$quiz_id;
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadResult();		
		return $result;	
	}
	function getStudName($user_id){
		$db =JFactory::getDBO();
		$sql = "SELECT name FROM #__users WHERE id=".$user_id;
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadResult();		
		return $result;	
	}
	function getCourseName($pid){
		$database = JFactory::getDBO();
		$sql = "SELECT `name` FROM #__guru_program WHERE `id`=".$pid;
		$database->setQuery($sql);
		$course_name = $database->loadResult();
		return $course_name;
	}
	function getScoreQuiz($quiz_id,$user_id,$id){
		$db =JFactory::getDBO();
		$sql = "SELECT 	score_quiz FROM #__guru_quiz_taken WHERE user_id=".intval($user_id)." and quiz_id=".intval($quiz_id)." and id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadResult();		
		return $result;		
	}
	function getAnsGived($user_id, $id){
		$db =JFactory::getDBO();
		$sql = "SELECT q.`id`, `answers_gived` FROM #__guru_quiz_question_taken qq, #__guru_questions q  WHERE qq.question_id=q.id and user_id=".intval($user_id)." and show_result_quiz_id=".intval($id)." ORDER BY q.reorder";
		$db->setQuery($sql);
		$db->query();
		$result_ansgived = $db->loadObjectList("id");	
		return $result_ansgived;		
	}
	function getAnsRight($quiz_id){
		$db =JFactory::getDBO();
		$sql = "SELECT `id`, `answers` FROM #__guru_questions WHERE qid=".intval($quiz_id). " ORDER BY `reorder` ";
		$db->setQuery($sql);
		$db->query();
		$result_ansright = $db->loadObjectList("id");	
		return $result_ansright;	
	
	}
	function getAllAns($quiz_id, $id){
		$db =JFactory::getDBO();
		$sql = "SELECT question_id FROM #__guru_quiz_question_taken qq, #__guru_questions q  WHERE qq.question_id=q.id and show_result_quiz_id=".$id." ORDER BY reorder"; 
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadObjectList();
		$i = 0;
		foreach($result as $key=>$value){
			$sql = "SELECT `a1`, `a2`,`a3`,`a4`,`a5`,`a6`,`a7`,`a8`,`a9`,`a10` FROM #__guru_questions WHERE qid=".intval($quiz_id)." and id=".$value->question_id;
			$db->setQuery($sql);
			$db->query();
			$choices = $db->loadAssocList();
			$correct_ans = "";
			
			if(!isset($choices["0"])){
				continue;
			}
			
			if($choices[0]['a1'] != "") 
			{
				$correct_ans .= "1a|||";
			}
			if($choices[0]['a2'] != "") 
			{
				$correct_ans .= "2a|||";
			}
			if($choices[0]['a3'] != "") 
			{
				$correct_ans .= "3a|||";
			}
			if($choices[0]['a4'] != "") 
			{
				$correct_ans .= "4a|||";
			}
			if($choices[0]['a5'] != "") 
			{
				$correct_ans .= "5a|||";
			}
			if($choices[0]['a6'] != "") 
			{
				$correct_ans .= "6a|||";
			}
			if($choices[0]['a7'] != "") 
			{
				$correct_ans .= "7a|||";
			}
			if($choices[0]['a8'] != "") 
			{
				$correct_ans .= "8a|||";
			}
			if($choices[0]['a9'] != "") 
			{
				$correct_ans .= "9a|||";
			}
			if($choices[0]['a10'] != "") 
			{
				$correct_ans .= "10a|||";
			}
			$result_allans[$value->question_id] = $correct_ans;
		}
		return $result_allans;	
	}
	function getAllAnsText($quiz_id, $id){
		$db =JFactory::getDBO();
		$sql = "SELECT question_id FROM #__guru_quiz_question_taken qq, #__guru_questions q  WHERE qq.question_id=q.id and show_result_quiz_id=".$id." ORDER BY reorder"; 
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadObjectList();
		$i = 0;
		foreach($result as $key=>$value){
			$sql = "SELECT `a1`, `a2`,`a3`,`a4`,`a5`,`a6`,`a7`,`a8`,`a9`,`a10` FROM #__guru_questions WHERE qid=".intval($quiz_id)." and id=".$value->question_id;
			$db->setQuery($sql);
			$db->query();
			$choices = $db->loadAssocList();
			$correct_ans = "";
			
			if(!isset($choices["0"])){
				continue;
			}
			
			if($choices[0]['a1'] != "") 
			{
				$correct_ans .= $choices[0]['a1']."|||";
			}
			if($choices[0]['a2'] != "") 
			{
				$correct_ans .= $choices[0]['a2']."|||";
			}
			if($choices[0]['a3'] != "") 
			{
				$correct_ans .= $choices[0]['a3']."|||";
			}
			if($choices[0]['a4'] != "") 
			{
				$correct_ans .= $choices[0]['a4']."|||";
			}
			if($choices[0]['a5'] != "") 
			{
				$correct_ans .= $choices[0]['a5']."|||";
			}
			if($choices[0]['a6'] != "") 
			{
				$correct_ans .= $choices[0]['a6']."|||";
			}
			if($choices[0]['a7'] != "") 
			{
				$correct_ans .= $choices[0]['a7']."|||";
			}
			if($choices[0]['a8'] != "") 
			{
				$correct_ans .= $choices[0]['a8']."|||";
			}
			if($choices[0]['a9'] != "") 
			{
				$correct_ans .= $choices[0]['a9']."|||";
			}
			if($choices[0]['a10'] != "") 
			{
				$correct_ans .= $choices[0]['a10']."|||";
			}
			$result_allans[$value->question_id] = $correct_ans;
		}
		return $result_allans;	
	}

	 function getQuestionName($id,$quiz_id){
		$db =JFactory::getDBO();
		$sql = "SELECT `id`, `text` FROM #__guru_questions WHERE qid=".intval($quiz_id)." ORDER BY reorder";
		$db->setQuery($sql);
		$db->query();
		$result_question=$db->loadObjectList("id");
	    return $result_question; 
}

	function saveorder(){
        $db =JFactory::getDBO();
        $data = JRequest::get('post');
        $ok = true;
        
        if ($data['task'] == 'saveorder') {
            // Combine the ids with their ordering numbers
            $order = array_combine($data['cid'], $data['order']);
            // Sort ascending the order array
            asort($order);
            // The new value for each item [will be auto-incremented below]
            $new_val = 0;
			           
            foreach($order as $key => $value) {
                $sql = "UPDATE #__guru_quiz SET ordering = '".$new_val."' WHERE id=".$key;
                $sqlz[] = $sql;
                $db->setQuery($sql);
                if ( !$db->query() ) {
                    $ok = false;
                }
                $new_val++;
            }
        } 
		elseif ( $data['task'] == 'orderup' || $data['task'] == 'orderdown' ) {
            $current_item['id'] = (int) $data['cid'][0];
            
            $sql = "SELECT ordering FROM #__guru_quiz WHERE id = {$current_item['id']}";
            $db->setQuery($sql);
            $current_item['ordering'] = $db->loadResult();
            
            $compare = ($data['task'] == 'orderup') ? '<' : '>';
            $desc_or_asc = ($data['task'] == 'orderup') ? 'DESC' : 'ASC';
            
            $sql = "SELECT id, ordering FROM #__guru_quiz WHERE ordering " . $compare . " {$current_item['ordering']} ORDER BY ordering " . $desc_or_asc . " LIMIT 1";
            $sqlz[] = $sql;
            $db->setQuery($sql);
            $previous_item = $db->loadAssoc();
           
            // If we have a previous/next item, interchange the 2
            if ( !empty($previous_item) ) {

                // Update ordering for the current item
                $sql = "UPDATE #__guru_quiz SET ordering = '{$previous_item['ordering']}' WHERE id = {$current_item['id']}";
                $sqlz[] = $sql;
                $db->setQuery($sql);
                if ( !$db->query() ) {
                    $ok = false;
                }

                // Update ordering for the current item
                $sql = "UPDATE #__guru_quiz SET ordering = '{$current_item['ordering']}' WHERE id = {$previous_item['id']}";
                $sqlz[] = $sql;
                $db->setQuery($sql);
                if ( !$db->query() ) {
                    $ok = false;
                }                
            }
        }        
        return $ok;        
    }
	
	function saveOrderQuest(){	
		$db = JFactory::getDBO();		
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');		
		$cid = array_values($cids);		
		$order = JRequest::getVar( 'order', array (0), 'post', 'array' );
		$order = array_values($order);		
		$total = count($cid);
		for($i=0; $i<$total; $i++){
			$sql = "update #__guru_questions set `reorder`=".$order[$i]." where `id`=".$cid[$i];
			$db->setQuery($sql);
			if (!$db->query()){
				return false;
			}
		}
		return true;
	}

	function getquiz () {
		$jnow = JFactory::getDate();

		if (empty ($this->_attribute)) { 
			$this->_attribute =$this->getTable("guruQuiz");
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
		
		if($this->_attribute->id<=0){
			$this->_attribute->text=JText::_('GURU_NEW_Q_BTN');
			$this->_attribute->published=1;
			$this->_attribute->startpublish =  $jnow->toSQL();
		}
		else $this->_attribute->text=JText::_('GURU_EDIT_Q_BTN');
		
		if(substr($this->_attribute->endpublish,0,4) =='0000' || $this->_attribute->id<1) 
			$this->_attribute->endpublish = JText::_('GURU_NEVER');  
				
		
		if(!isset($this->_attribute->published)){
			$this->_attribute->published = 1;
		}
		$this->_attribute->lists['published'] = '<input type="hidden" name="published" value="0">';
		if($this->_attribute->published == 0){ 
			$this->_attribute->lists['published'] .= '<input type="checkbox" value="0" class="ace-switch ace-switch-5" name="published">';
		}
		else{
			$this->_attribute->lists['published'] .= '<input type="checkbox" checked="checked" value="1" class="ace-switch ace-switch-5" name="published">';
		}
		$this->_attribute->lists['published'] .= '<span class="lbl"></span>'; 
		
		//start get author list
		$db = JFactory::getDBO();
		$sql = "SELECT u.id, u.name FROM #__users u, #__guru_authors la where u.id=la.userid";	
		$db->setQuery($sql);
		$db->query();
		$result_authors = $db->loadObjectList();
		
		$author_listl=array();
		$author_listl[]=JHTML::_("select.option",JText::_('GURU_SELECT'),"0");
		for($i=0;$i<count($result_authors);$i++){
			$author_listl[]=JHTML::_("select.option",$result_authors[$i]->name,$result_authors[$i]->id);
		}	
		$this->_attribute->lists['author']=JHTML::_("select.genericlist",$author_listl,"author","","text","value",$this->_attribute->author);
		
		
		return $this->_attribute;
	}
	
	function getQuizById(){
		$db =Jfactory::getDBO();
		$query="select * from #__guru_quiz where id=".intval($this->_id)." limit 1";
		$db->setQuery($query);
		$db->query();
		$result=$db->loadObject();
		return $result;
	}
	
	function getMedia(){
		$db =Jfactory::getDBO();
		$media= new StdClass;
	
		$app = JFactory::getApplication('admin');
		$limit = $app->getUserStateFromRequest( 'limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$limitstart = JRequest::getVar("limitstart", "0");
	
		if($this->_id==0){
			$db->setQuery("SELECT * FROM `#__guru_questions` WHERE qid='0' ");
			$media->mmediam = $db->loadObjectList();
			$media->max_reo=0;
			$media->min_reo=0;
			$media->mainmedia=0;
		}
		else{
			$db->setQuery("SELECT count(*) FROM `#__guru_questions` WHERE qid='".$this->_id."'");
			$db->query();
			$total = $db->loadColumn();
			$total = @$total["0"];
			$media->total = $total;
			
			$sql_limit = "LIMIT ".$limitstart.",".$limit;
			if(intval($limit) == 0){
				$sql_limit = "";
			}
			
			$db->setQuery("SELECT * FROM `#__guru_questions` WHERE qid='".$this->_id."' ORDER BY reorder ".$sql_limit);
			$media->mmediam = $db->loadObjectList();
			$db->setQuery("SELECT id FROM `#__guru_questions` WHERE qid = '".$this->_id."' ORDER BY reorder DESC LIMIT 1");
			$media->max_reo = $db->loadResult();
			$db->setQuery("SELECT id FROM `#__guru_questions` WHERE qid = '".$this->_id."' ORDER BY reorder ASC LIMIT 1");
			$media->min_reo = $db->loadResult();
			$db->setQuery("SELECT * FROM `#__guru_media` WHERE id in (SELECT media_id FROM #__guru_mediarel WHERE type='qmed' AND type_id = ".$this->_id.") ");
			$media->mainmedia = $db->loadObjectList();
		}
		return $media;
	}
	
	public static function QuestionNo($id){
		$db = JFactory::getDBO();
		
		$sql = "select `is_final` from #__guru_quiz where `id`=".intval($id);
		$db->setQuery($sql);
		$is_final = $db->loadResult();
		$is_final = @$is_final["0"];
		
		if($is_final == 1){ // final exam
			$sql = "select `nb_quiz_select_up` from #__guru_quiz where `id`=".intval($id);
			$db->setQuery($sql);
			$tmp = $db->loadResult();
			return $tmp;
		}
		else{
			$sql = "select count(id) from #__guru_questions where qid=".$id;
			$db->setQuery($sql);
			$tmp = $db->loadResult();
			return $tmp;
		}
	}	
	
	function store () {
		$app = JFactory::getApplication('administrator');
		$item = $this->getTable('guruQuiz');
		
		$data = JRequest::get('post');
		$db = JFactory::getDBO();
		$data['description'] = $_POST['description'];
		$data['startpublish'] = date('Y-m-d H:i:s', strtotime($data['startpublish']));
		
		if($data['endpublish'] != JText::_('GURU_NEVER') && trim($data['endpublish']) != ""){
			$data['endpublish'] = date('Y-m-d H:i:s', strtotime($data['endpublish']));
		}
		
		$res = true;
		
		if (!$item->bind($data)){
			$res = false;
		}
		
		if (!$item->check()) {
			$res = false;
		}

		if (!$item->store()) {
			$res = false;
		}else{
			$this->_id=$item->id;
		}
		
		$app->setUserState('new_quiz_id',$item->id);
		
		$new_quiz = 0;
		if ($data['id']=="") {
			$data['id'] = mysql_insert_id();
			$app->setUserState('new_quiz_id',$data['id']);
		}
			
		if ($data['id']==0) {
			$ask = "SELECT `id` FROM `#__guru_quiz` ORDER BY `id` DESC LIMIT 1 ";
			$db->setQuery( $ask );
			$data['id'] = $db->loadResult();
			$new_quiz = 1;
		}
		$quizid = $data['id'];
		
		$md = "SELECT `id` FROM `#__guru_media` WHERE source='".$quizid."' ORDER BY `id` DESC LIMIT 1";
		$db->setQuery($md);
		$md_id=$db->loadResult();
		
		if($data['valueop'] == 1){
		//Save settings for quiz timer 
			$sql = "UPDATE `#__guru_quiz` SET `max_score`='".$data['max_score_pass']."', `pbl_max_score`='".$data['show_max_score_pass']."', `time_quiz_taken`='".$data['nb_quiz_taken']."', `show_nb_quiz_taken`='".$data['show_nb_quiz_taken']."', `nb_quiz_select_up`='".$data['nb_quiz_select_up']."', `show_nb_quiz_select_up`='".$data['show_nb_quiz_select_up']."', `final_quiz`= '".$data['final_quiz']."', `limit_time`='".$data['limit_time_l']."', limit_time_f = '".$data['limit_time_f']."', show_finish_alert = '".$data['show_finish_alert']."', student_failed_quiz = '".$data['student_failed_quiz']."', is_final ='1' WHERE id='".$quizid."'";
			$db->setQuery($sql);
			$db->query();
		}
		else{
			$sql = "UPDATE `#__guru_quiz` SET `max_score`='".$data['max_score_pass']."', `pbl_max_score`='".$data['show_max_score_pass']."', `time_quiz_taken`='".$data['nb_quiz_taken']."', `show_nb_quiz_taken`='".$data['show_nb_quiz_taken']."', `nb_quiz_select_up`='".$data['nb_quiz_select_up']."', `show_nb_quiz_select_up`='".$data['show_nb_quiz_select_up']."', `final_quiz`= '".$data['final_quiz']."', `limit_time`='".$data['limit_time_l']."', limit_time_f = '".$data['limit_time_f']."', show_finish_alert = '".$data['show_finish_alert']."', student_failed_quiz = '".$data['student_failed_quiz']."',  is_final ='0'  WHERE id='".$quizid."'";
			$db->setQuery($sql);
			$db->query();
		
		}
		//END Save settings for quiz timer 
		
		if(!$md_id) {
			$sql = "INSERT INTO `#__guru_media` (`id` ,`name` ,`instructions` ,`type` ,`source` ,`uploaded` ,`code` ,`url` ,`local` ,`width` ,`height` ,`published`) VALUES (NULL , '".addslashes($data['name'])."', '".addslashes($data['description'])."', 'quiz', '".$quizid."', '0', NULL , NULL , NULL , '0', '0', '".$data['published']."');";	
		} else {
			$sql = "UPDATE `#__guru_media` SET `name` = '".addslashes($data['name'])."',`instructions` = '".addslashes($data['description'])."',`published` = '".$data['published']."' WHERE `source` = '".$quizid."' LIMIT 1 ;";
		}
		$db->setQuery($sql);
		$db->query();
		
		
		if($new_quiz && $data['valueop'] == 0){
				$sql = "UPDATE `#__guru_questions` SET 
						`qid` = '".$quizid."'
						WHERE `qid` ='0' ";
				$db->setQuery($sql);
				if (!$db->query() ){
					$this->setError($db->getErrorMsg());
					return false;
				}
		}
		elseif($data['valueop'] == 1){
			$sql = "UPDATE `#__guru_quizzes_final` SET 
						`qid` = '".$quizid."'
						WHERE `qid` ='0' ";
				$db->setQuery($sql);
				if (!$db->query() ){
					$this->setError($db->getErrorMsg());
					return false;
				}		
		}

		if (isset($_POST['mediafiles'])) {
			//delete old records
			if ($data['id']>0) {
				$db->setQuery("DELETE FROM `#__guru_mediarel` WHERE type='qmed' AND type_id='".$data['id']."'");
				$db->query();
			}
			//delete end
			if ($data['id']=="") $data['id'] = mysql_insert_id();
			if ($data['id']==0) {
				$ask = "SELECT `id` FROM `#__guru_quiz` ORDER BY `id` DESC LIMIT 1 ";
				$db->setQuery( $ask );
				$data['id'] = $db->loadResult();
			}
			$progid = $data['id'];
			
			$thefiles = explode(',',$_POST['mediafiles']);
			
			$id_tmp_med_task_2_remove = array();
			if(isset($_POST['mediafiletodel']))
				$id_tmp_med_files_2_remove = explode(',', $_POST['mediafiletodel']);
				
			foreach ($thefiles as $files) {
				if (intval($files)>0 && !in_array($files,$id_tmp_med_files_2_remove)) {
					$db->setQuery("INSERT INTO `#__guru_mediarel` (`id`,`type`,`type_id`,`media_id`,`mainmedia`) VALUES ('','qmed','".$progid."','".$files."','0')");
					$db->query();
				}
			}
		} // end if		
		
		if(isset($_POST['deleteq'])){
		$thefiles = explode(',',trim($_POST['deleteq'],","));
			foreach ($thefiles as $files) {
				if (intval($files)>0 && $data['valueop'] == 0) {
					$sql = "delete from #__guru_questions where id=".$files;
					$db->setQuery($sql);
					$db->query();
				}
				else{
					$sql = "select quizzes_ids from #__guru_quizzes_final where qid=".$quizid." order by id DESC LIMIT 0,1 " ;
					$db->setQuery($sql);
					$db->query();
					$result=$db->loadResult();	
					
					
					$newvalues = str_replace($_POST['deleteq'], "", $result);
					
					
					$sql = "update #__guru_quizzes_final set quizzes_ids='".$newvalues."' where qid=".$quizid;
				 	$db->setQuery($sql);
					$db->query();	
				
				} // end if
			} // end for
		} // end if
		
		//echo "<pre>";var_dump($_POST);die();
		if(isset($_POST['order_q'])){
			foreach($_POST['order_q'] as $key=>$value){
				if(isset($_POST['publish_q'][$key])){
					$published_cond=",`published` = '".$_POST['publish_q'][$key]."'";
				}
				$sql = "UPDATE `#__guru_questions` SET 
						`reorder` = '".intval($value)."'".$published_cond."
						WHERE `id` ='".$key."' ";
				$db->setQuery($sql);
				$db->query();
			}
		}
		return $res;

	}
	public static function getAmountQuestions($id){
		$db =Jfactory::getDBO();
		$query="select count(id) from #__guru_questions where qid=".intval($id);
		$db->setQuery($query);
		$db->query();
		$result=$db->loadResult();
		return $result;
	
	}
	
	
	public static function getAmountQuizzes($id){
		$db =Jfactory::getDBO();
		$sql = "SELECT 	quizzes_ids FROM #__guru_quizzes_final WHERE qid=".$id;
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadColumn();	
		@$result_qids = explode(",",trim($result["0"],","));
		if(isset($result_qids[0]) && $result_qids[0]!=""){
			$query="select count(id) from #__guru_questions where qid IN (".implode(",", $result_qids).")";
			$db->setQuery($query);
			$db->query();
			$result=$db->loadResult();
			return $result;
		}
	
	}		


	function getPagination(){
		// Lets load the content if it doesn't already exist
		if(empty($this->_pagination)){
			jimport('joomla.html.pagination');
			if(!$this->_total){
				$task = JRequest::getVar("task", "");
				if($task == "listQuizStud"){
					$this->getlistQuizTakenStud();
				}
				else{
					$this->getlistQuiz();
				}
			}
			$this->_pagination = new JPagination( $this->_total, $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}
	
	function more_media_files ($ids){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT *, id as media_id FROM `#__guru_media` WHERE id in (".$ids.") GROUP BY media_id");
		$db->query();
		$more_media_files = $db->loadObjectList();
		$this->assign("more_media_files", $more_media_files);
		return true;
	}
	
	function existing_ids ($ids){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT media_id FROM `#__guru_mediarel` WHERE type_id = ".$ids." AND type='qmed' ");
		$db->query();
		$existing_ids = $db->loadObjectList();
		return $existing_ids;
	}		

	function upload() { 
	//$absolutepath = JPATH_SITE;
	$database = JFactory::getDBO();
	$db = JFactory::getDBO();
 	//get the image folder 
	
		$sqla = "SELECT `imagesin` FROM #__guru_config LIMIT 1";
		$db->setQuery($sqla);
		$db->query();
		$imgfolder = $db->loadResult(); 	
	$targetPath = JPATH_SITE.'/'.$imgfolder.'/';
	$failed = '0';
	//var_dump($_FILES);die('nu merge uploaduuuu');
	if (isset($_FILES['image_file'])) {
			
		$filename = $_FILES['image_file']['name'];
		if ($filename) {
			
			$filenameParts = explode('.', $filename);
			$extension = '';
			if (count($filenameParts) > 1)
				$extension = array_pop($filenameParts);
			$extension = strtolower($extension);
			if (!in_array($extension, array('jpg', 'jpeg', 'gif', 'png'))) {
				//mosErrorAlert("The image must be gif, png, jpg, jpeg, swf");
				$text = strip_tags( addslashes( nl2br( "The image must be gif, png, jpg, jpeg." )));
				echo "<script>alert('$text'); </script>";
				$failed=1;
			}
			if ($failed != 1) {
			if (!move_uploaded_file ($_FILES['image_file']['tmp_name'],$targetPath.$filename)) {
				//mosErrorAlert("Upload of ".$filename." failed");
				$text = strip_tags( addslashes( nl2br( "Upload of ".$filename." failed." )));
				echo "<script>alert('$text'); </script>";
			} else {
				return $filename;
				}
			}
		  }	
		}
	}

	function delete () {
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$item = $this->getTable('guruQuiz');
		$database = JFactory::getDBO();
		
		$sql = "SELECT id_final_exam FROM #__guru_program";
		$database->setQuery($sql);
		$database->query();
		$existingfinalexam_ids = $database->loadColumn();
		
		$sql = "SELECT imagesin FROM #__guru_config WHERE id ='1' ";
		$database->setQuery($sql);
		if (!$database->query()) {
			echo $database->stderr();
			return;
		}
		$imagesin = $database->loadResult();
		
		foreach ($cids as $cid) {
			$sql = "select count(*) from #__guru_mediarel where `type`='scr_m' and `media_id`=".intval($cid)." and `layout`=12";
			$database->setQuery($sql);
			$database->query();
			$count = $database->loadColumn();
			$count = @$count["0"];
			
			if(intval($count) > 0){
				return "assigned";
			}
			
			if(in_array($cid, $existingfinalexam_ids)){
				$_SESSION["is_atribuited"] = 1;
				return false;
			}
			
			$sql = "SELECT image FROM #__guru_quiz WHERE id =".$cid;
			$database->setQuery($sql);
			if (!$database->query()) {
				echo $database->stderr();
				return;
			}
			$image = $database->loadResult();			
			
			if (!$item->delete($cid)) {
				$this->setError($item->getErrorMsg());
				return false;
			}
			
			$query = "DELETE FROM #__guru_questions WHERE qid = '".$cid."'";
			$database->setQuery( $query );
			$database->query();
			
			$query = "SELECT `id` FROM `#__guru_media` WHERE source = '".$cid."'";
			$database->setQuery( $query );
			$med_id = $database->loadResult();
			
			if($med_id){			
				$query = "DELETE FROM #__guru_mediarel WHERE media_id = '".$med_id."'";
				$database->setQuery( $query );
				$database->query();			
				
				$query = "DELETE FROM #__guru_media WHERE id = '".$med_id."'";
				$database->setQuery( $query );
				$database->query();			
			}
						
			$query = "DELETE FROM #__guru_mediarel WHERE type = 'qmed' AND type_id = '".$cid."'";
			$database->setQuery( $query );
			$database->query();
		
			$query = "DELETE FROM #__guru_mediarel WHERE type = 'tquiz' AND media_id = '".$cid."'";
			$database->setQuery( $query );
			$database->query();		
			
			$targetPath = JPATH_SITE.'/'.$imagesin.'/';		
			unlink($targetPath.$image);
		}
		return true;
	}
	
	function getreportsAdvertisers () {
		if (empty ($this->_package)) {
			$db = JFactory::getDBO();
			$sql = "SELECT a.aid, a.company, a.user_id FROM #__ad_agency_advertis as a, #__users as b WHERE a.user_id = b.id ORDER BY a.company ASC";
			$db->setQuery($sql);
			if (!$db->query()) {
				echo $db->stderr();
				return;
			}
			$this->_package = $db->loadObjectList();
			
		}
		return $this->_package;

	}
	
	function addquestion ($qtext,$quizid,$a1,$a2,$a3,$a4,$a5,$a6,$a7,$a8,$a9,$a10,$answers) {
		$db = JFactory::getDBO();
		$query='SELECT MAX( reorder ) FROM `#__guru_questions` WHERE `qid` ="'.$quizid.'" ';
		$db->setQuery($query);
		$reorder=$db->loadResult();
		$reorder=intval($reorder)+1;
		$sql = "INSERT INTO `#__guru_questions` ( `id` , `qid`, `text` , `a1` , `a2` , `a3` , `a4` , `a5` , `a6` , `a7` , `a8` , `a9` , `a10` , `answers`, reorder ) VALUES ('', '".$quizid."', '".addslashes($qtext)."' , '".addslashes($a1)."' , '".addslashes($a2)."', '".addslashes($a3)."', '".addslashes($a4)."', '".addslashes($a5)."', '".addslashes($a6)."', '".addslashes($a7)."', '".addslashes($a8)."', '".addslashes($a9)."', '".$a10."', '".$answers."', ".$reorder.");";
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
	return true;
	}

	function editquestion ($qtext,$qid,$a1,$a2,$a3,$a4,$a5,$a6,$a7,$a8,$a9,$a10,$answers) {
		$db = JFactory::getDBO();
		$sql = "UPDATE `#__guru_questions` SET 
				`text` = '".addslashes($qtext)."',
				`a1` = '".addslashes($a1)."',
				`a2` = '".addslashes($a2)."',
				`a3` = '".addslashes($a3)."',
				`a4` = '".addslashes($a4)."',
				`a5` = '".addslashes($a5)."',
				`a6` = '".addslashes($a6)."',
				`a7` = '".addslashes($a7)."',
				`a8` = '".addslashes($a8)."',
				`a9` = '".addslashes($a9)."',
				`a10` = '".addslashes($a10)."',
				`answers` = '".$answers."' 
				WHERE `id` =".$qid." LIMIT 1";				
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
	return true;
	}
	
	function delquestion($id,$qid) {
		$db = JFactory::getDBO();
		
		$sql = "delete from #__guru_questions where id=".$id." and qid=".$qid;
		
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
		return 1;
	}

	function addmedia ($toinsert, $taskid, $mainmedia) {
		$db = JFactory::getDBO();
		$sql = "INSERT INTO `#__guru_mediarel` ( `id` , `type` , `type_id` , `media_id` , `mainmedia` ) VALUES ('', 'qmed', '".$taskid."' , '".$toinsert."', '".$mainmedia."');";
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
	return true;
	}

	function delmedia($tid,$cid) {
		$db = JFactory::getDBO();
		
		$sql = "delete from #__guru_mediarel where type='qmed' and type_id=".$tid." and media_id=".$cid;
		
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
		return 1;
	}

	public static function id_for_last_question(){
		$db = JFactory::getDBO();
		$sql = "SELECT max(id) FROM #__guru_questions ";
			$db->setQuery($sql);
			if (!$db->query()) {
				echo $db->stderr();
				return;
			}
			$id = $db->loadResult();
		return $id;	
	}	
	
	function publish () { 
		$db = JFactory::getDBO();
		$cids = JRequest::getVar('cid', array(0), 'post', 'array'); 
		$task = JRequest::getVar('task', '', 'post');
		
		$sql = "SELECT is_final from #__guru_quiz where id='".$cids[0]."' ";
		$db->setQuery($sql);
		$id = $db->loadResult();
		
		if ($task == 'publish'){
			$sql = "update #__guru_quiz set published='1' where id in ('".implode("','", $cids)."')";
			if($id == 0){
				$ret = 1;
			}
			else{
				$ret = 2;
			}
			
		} else {
			$sql = "update #__guru_quiz set published='0' where id in ('".implode("','", $cids)."')";
			if($id == 0){
				$ret = -1;
			}
			else{
				$ret = -2;
			}
		}
		
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $ret;
	}	
	
	function cancel() {
		$db = JFactory::getDBO();
		$sql = "delete from #__guru_questions where qid='0' ";
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}

		if(isset($_POST['newquizq'])){
			$thefiles = $_POST['newquizq'];
			$thefiles = explode(',', $thefiles);
			foreach ($thefiles as $files) {
				if (intval($files)>0) {
					$db->setQuery("delete from #__guru_questions where id='".$files."'");
					$db->query();
				}
			}
		}
		
		return 1;
	}
	function removequizresults(){
		$quiz_id =  JRequest::getVar( 'cid', "", 'post', 'array');
		$db = JFactory::getDBO();
		$sql = "delete from #__guru_quiz_question_taken where show_result_quiz_id IN (SELECT id from #__guru_quiz_taken where quiz_id=".$quiz_id[0].")";
		$db->setQuery($sql);
		$db->query();
		
		$sql = "delete from #__guru_quiz_taken where quiz_id=".$quiz_id[0];
		$db->setQuery($sql);
		if($db->query()){
		return 1;
		}

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

	function checkbox_construct( $rowNum, $recId, $name='cid' ){
		$db = JFactory::getDBO();
		
		$sql = " SELECT media_id FROM #__guru_mediarel WHERE type_id in ( SELECT media_id FROM #__guru_mediarel WHERE type_id in (SELECT id FROM #__guru_days WHERE pid in (SELECT id FROM #__guru_order GROUP BY id)) AND type = 'dtask' GROUP BY media_id ) AND  type = 'tquiz' ";
		
		$db->setQuery($sql);
		if (!$db->query()){
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
	
	function duplicate () {
		
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$n		= count( $cid );
		if ($n == 0) {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}

		foreach ($cid as $id)
		{
			$row 	= $this->getTable('guruQuiz');
			$db = JFactory::getDBO();
			// load the row from the db table
			$row->load( (int) $id );
			
			$row->name = JText::_( 'GURU_Q_COPY_TITLE' ).' '.$row->name ;
			if($row->image!='')
				{
					$sql = "SELECT imagesin FROM `#__guru_config` WHERE id = 1";
					$db->setQuery($sql);
					$configs = $db->loadResult();						
					copy(JPATH_SITE.'/'.$configs.'/'.$row->image, JPATH_SITE.'/'.$configs.'/copy_'.$row->image);
					$row->image = 'copy_'.$row->image;
				}	
			$old_quiz_id = $row->id;
				
			$row->id = 0;

			if (!$row->check()) {
				return JError::raiseWarning( 500, $row->getError() );
			}
			if (!$row->store()) {
				return JError::raiseWarning( 500, $row->getError() );
			}
			$row->checkin();
			unset($row);
			
			$isfinal = "SELECT `is_final` FROM `#__guru_quiz` WHERE id= ".$old_quiz_id;
			$db->setQuery( $isfinal );
			$isfinal = $db->loadColumn();
			$isfinal = $isfinal[0];
			
			if($isfinal ==0 ){
				$ask = "SELECT `id` FROM `#__guru_questions` WHERE qid= ".$old_quiz_id;
				$db->setQuery( $ask );
				$question_array = $db->loadColumn();
			}	
			
			$sql = "SELECT max(id) FROM `#__guru_quiz` ";
			$db->setQuery($sql);
			$new_quiz_id = $db->loadColumn();
			$new_quiz_id = $new_quiz_id[0];
			
			$sql = "SELECT 	quizzes_ids FROM #__guru_quizzes_final WHERE qid=".$id;
			$db->setQuery($sql);
			$db->query();
			$result_fq=$db->loadColumn();	
			
			if($isfinal ==0 ){
				foreach ($question_array as $question)
					{
						$sql = "SELECT * FROM `#__guru_questions` WHERE id = ".$question;
						$db->setQuery($sql);
						$the_question_object = $db->loadObject();					
						
						$sql = "INSERT INTO `#__guru_questions` 
															( 
																`qid` , 
																`text` , 
																`a1` , 
																`a2` , 
																`a3` ,
																`a4`,
																`a5`,
																`a6`,
																`a7`,
																`a8`,
																`a9`,
																`a10`,
																`answers`
													) VALUES (
																'".$new_quiz_id."', 
																'".mysql_escape_string($the_question_object->text)."', 
																'".mysql_escape_string($the_question_object->a1)."' , 
																'".mysql_escape_string($the_question_object->a2)."', 
																'".mysql_escape_string($the_question_object->a3)."',
																'".mysql_escape_string($the_question_object->a4)."',
																'".mysql_escape_string($the_question_object->a5)."',
																'".mysql_escape_string($the_question_object->a6)."',
																'".mysql_escape_string($the_question_object->a7)."',
																'".mysql_escape_string($the_question_object->a8)."',
																'".mysql_escape_string($the_question_object->a9)."',
																'".mysql_escape_string($the_question_object->a10)."',
																'".$the_question_object->answers."'									
															)";
						$db->setQuery($sql);
						if (!$db->query() ){
							$this->setError($db->getErrorMsg());
							return false;
						}		
					}	
				}
			else{
				$sql = "INSERT INTO `#__guru_quizzes_final` (`quizzes_ids`, `qid`, `published`)VALUES('".$result_fq[0]."', '".$new_quiz_id."',1)";	
				$db->setQuery($sql);
				if (!$db->query() ){
					$this->setError($db->getErrorMsg());
					return false;
				}		
			}			
		}
	return 1;
				
	}		

};
?>