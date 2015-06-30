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


class guruModelguruTask extends JModelLegacy {
	var $_attributes;
	var $_attribute;
	var $_id = null;
	var $_module=null;

	function __construct () {
		parent::__construct();
		$cids = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int)$cids[0]);
		
		$module = JRequest::getVar('module', '0');
		$this->setModule($module);
	}

	function setId($id) {
		$this->_id = $id;
		$this->_attribute = null;
	}

	function setModule($module) {
		$this->_module = $module;
	}
	
	function view () {
		$my= JFactory::getUser();	
	}

	function getProgresBarSettings(){
		$db = JFactory::getDBO();
		$sql = "select progress_bar, st_donecolor, st_notdonecolor, st_txtcolor, st_width, st_height from #__guru_config";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function getLessonOrder($course_id){
		$db = JFactory::getDBO();
		$sql = "select id from #__guru_task WHERE id IN (SELECT media_id FROM #__guru_mediarel WHERE type = 'dtask' AND type_id in (SELECT id FROM #__guru_days WHERE pid =".intval($course_id).")) order by `ordering` asc";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		
		return $result;
	}
	
	function getLessonJumpOrder($course_id, $less_id){
	$db = JFactory::getDBO();
	$sql ="select row from (SELECT @row := @row + 1 as row, t.id as lesson_id FROM #__guru_task t, (SELECT @row := 0) r where t.id  IN (SELECT media_id FROM #__guru_mediarel WHERE type = 'dtask' AND type_id in (SELECT id FROM #__guru_days WHERE pid =".intval($course_id)."))  order by ordering) as table_T where lesson_id =".$less_id;
	$db->setQuery($sql);
	$db->query();
	$result = $db->loadResult();
	return $result;
	
	}
	
	function getAllSteps($module_id){
		$db = JFactory::getDBO();
		$jnow 	= JFactory::getDate();
		$date 	= $jnow->toSQL();
		
		$sql = "select t.id, t.name, t.step_access 
				from #__guru_task t, #__guru_mediarel m 
				where m.type_id = ".intval($module_id)." and m.type='dtask' and m.media_id=t.id
				AND t.startpublish <= '".$date."'
				AND (t.endpublish = '0000-00-00 00:00:00' OR t.endpublish >= '".$date."') 
				order by ordering asc";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}

	function getJumpStep($id){
		$db = JFactory::getDBO();
		$sql = "select jump_step, module_id1, type_selected from #__guru_jump where id = ".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}

	function getlistTask ($dayid, $dayord) {
			$my = JFactory::getUser();
			$database = JFactory::getDBO();
			$sql = "SELECT tasks FROM #__guru_programstatus WHERE userid=".$my->id." AND pid = (SELECT pid FROM #__guru_days WHERE id=".$dayid." )";
			$database->setQuery($sql);
			$task_object = $database->loadResult();
			
			$task_object = explode(';', $task_object);
			$task_array = $task_object[$dayord-1];
			
			$task_ids_in = '';
			$task_array = explode('-', $task_array);
			foreach($task_array as $task)
				{
					$the_task = explode(',', $task);
					if ($the_task[0])
						$task_ids_in = $task_ids_in.$the_task[0].',';
				}
				
			$task_ids_in = substr($task_ids_in, 0, strlen($task_ids_in)-1);
			$task_ids_in = explode(',', $task_ids_in);
			return $task_ids_in;
	}	
	
	function getMainMediaForTask($taskid){
		$database = JFactory::getDBO();
			
		$sql = "SELECT * FROM #__guru_media
					WHERE id = (SELECT media_id FROM #__guru_mediarel WHERE mainmedia='1' AND type_id = ".$taskid.") "; 
		$database->setQuery($sql);
		$media = $database->loadObject();
		return $media;	
	}
	
	function getAllModules($pid){
		$db = JFactory::getDBO();
		$sql = "select * from #__guru_days where pid=".intval($pid)." order by ordering asc";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function getNextModule($pid, $module_id){
		$db = JFactory::getDBO();
		$sql = "select id from #__guru_days where pid = ".intval($pid)." order by ordering asc";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		$return = "0";
		
		if(isset($result) && is_array($result) && count($result) > 0){
			foreach($result as $key=>$value){
				if(intval($value) == intval($module_id)){
					if(isset($result[$key + 1])){
						$return = $result[$key + 1];
					}
				}
			}
		}
		return $return;
	}	
	
	function getPrevModule($pid, $module_id){
		$db = JFactory::getDBO();
		$sql = "select id from #__guru_days where pid = ".intval($pid)." order by ordering asc";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		$return = "0";
		
		if(isset($result) && is_array($result) && count($result) > 0){
			foreach($result as $key=>$value){
				if(intval($value) == intval($module_id)){
					if(isset($result[$key - 1])){
						$return = $result[$key - 1];
					}
				}
			}
		}
		return $return;
	}	
	
	function editModuleOnFront(){
		$db = JFactory::getDBO();
		$attribs = array();
		$id = intval(JRequest::getVar("module", ""));
		$attribs["id"] = $id;
		$sql = "select * from #__guru_days where id=".$id;
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();

		$attribs["name"] = $result["0"]["title"];
		$attribs["alias"] = $result["0"]["alias"];
		$attribs["category"] = "";
		$attribs["difficultylevel"] = "";
		$attribs["points"] = "";
		$attribs["image"] = "";
		$attribs["published"] = $result["0"]["published"];
		$attribs["startpublish"] = $result["0"]["startpublish"];
		$attribs["endpublish"] = $result["0"]["endpublish"];
		$attribs["metatitle"] = $result["0"]["metatitle"];
		$attribs["metakwd"] = $result["0"]["metakwd"];
		$attribs["metadesc"] = $result["0"]["metadesc"];
		$attribs["time"] = 0;
		$attribs["ordering"] = $result["0"]["ordering"];
		$attribs["step_access"] = $result["0"]["access"];
		$attribs["layout"] = 6;
		$attribs["next_module"] = $id;
		$attribs["prev_module"] = $id;
		$attribs["layout_text"] = array();
		if(trim($result["0"]["media_id"]) != ""){			
			$attribs["layout_media"]["0"] = $this->parse_media(intval($result["0"]["media_id"]), 0);
		}
		else{
			$attribs["layout_media"]["0"] = array();
		}
		$attribs["layout_jump"] = array();
		
		//preview----------------
		$stop = false;
		while(!$stop){
			$prev_module = $this->getPrevModule($result["0"]["pid"], $id);
						
			if($prev_module == "0"){
				$stop = true;
			}
			else{
				$cid_array = $this->getAllSteps($prev_module);
				if(count($cid_array) > 0){
					$stop = true;
					$current_module = $prev_module;
					$attribs["prev_module"] = $prev_module;
					$attribs["prevs"] = $cid_array[count($cid_array)-1]["id"];
				}
				else{
					$current_module = $prev_module;
					$attribs["prev_module"] = $prev_module;
				}
			}
		}		
		//preview----------------
		
		//next-------------------
		$stop = false;
		$next_id = "0";
		$next_id_access = "0";
		while(!$stop){
			$cid_array = $this->getAllSteps($id);
			if(count($cid_array) > 0){
				$next_id = $cid_array["0"]["id"];
				$next_id_access = $cid_array["0"]["step_access"];
				$attribs["next_module"] = $id;
				$stop = true;
			}
			else{
				$current_module = $next_module;
			}
		}
		if(!isset($next_module) || $next_module === NULL){
		
		}
		//next-------------------
    	$attribs["nexts"] = $next_id;
    	$attribs["nextaccess"] = $next_id_access;
    	$attribs["pid"] = $result["0"]["pid"];


		
		return $attribs;
	}
	
	function getTask(){
		$action = JRequest::getVar("action", "");
		$jnow 	= JFactory::getDate();
		$date 	= $jnow->toSQL();
		
		if($action != ""){
			$attribs = $this->editModuleOnFront();
			return (object)$attribs;
		}
		
		
		$my = JFactory::getUser();
		$db = JFactory::getDBO();
		
		if(empty ($this->_attributes)){
			$sql = "SELECT lt.*, lm.media_id as layout
					FROM #__guru_task AS lt
					LEFT JOIN #__guru_mediarel as lm 
					ON lt.id=lm.type_id
					WHERE lt.id = ".intval($this->_id)." AND lt.published = 1 AND  lt.startpublish <='".$date."' AND (lt.endpublish >='".$date."' OR lt.endpublish = '0000-00-00 00:00:00' )
					 AND type='scr_l' ";
			$this->_attributes = $this->_getList($sql);		
			
			// if the course whom the lesson belong to is set as free for guests,
			// then the lesson is free for guests
			$sql = "SELECT p.chb_free_courses, p.step_access_courses, p.selected_course
				FROM #__guru_program p				
				LEFT JOIN #__guru_days d
				ON d.pid = p.id
				where d.id=".intval(JRequest::getVar("module", "0"));
			
			$this->_attributes[0]->course_details = $this->_getList($sql);
			$this->_attributes[0]->course_details = $this->_attributes[0]->course_details[0];
			
			$is_course_free_for_guests = false;
			if($this->_attributes[0]->course_details->chb_free_courses == 1 && $this->_attributes[0]->course_details->step_access_courses == 2) { 
				$is_course_free_for_guests = true;
				$this->_attributes[0]->step_access = 2;
			}
		}
		
		$attribs=$this->_attributes[0];
		$attribs->module=$this->_module;		
		$attribs->prev_module = $this->_module;
		
		//start get text, media 
		$attribs->layout_text=array();
		$attribs->layout_media=array();
		$attribs->layout_jump=array();
		
		
		
		if($attribs->layout != ""){
			$sql="SELECT type, lm.media_id as media_id, lm.layout
				 FROM #__guru_mediarel as lm
				 WHERE type_id=".intval($this->_id)." 
				 AND (type='scr_t' or type='scr_m') and layout=".$attribs->layout." order by mainmedia asc";
	
					
					
			$result=$this->_getList($sql);
			
			for($i=0;$i<count($result);$i++){
				if($result[$i]->type=="scr_t"){
					$attribs->layout_text[]=$this->parse_txt(intval($result[$i]->media_id));
				}
				else if($result[$i]->type=="scr_m" && $result[$i]->layout!="12"){
					$sql = "select * from #__guru_media where `id`=".intval($result[$i]->media_id);
					$db->setQuery($sql);
					$db->query();
					$media_details = $db->loadObject();
					
					if($media_details->type == "video" && $media_details->source == "url"){
						$configs = $this->getConfig();
						$video_size = $configs->default_video_size;
						
						if(trim($video_size) != ""){
							$temp = explode("x", trim($video_size));
							$media_details->width = $temp["1"];
							$media_details->height = $temp["0"];
						}
						
						if($media_details->width==0){
							$media_details->width=400;
						}
						
						require_once(JPATH_ROOT .'/components/com_guru/helpers/videos/helper.php');
						$parsedVideoLink = parse_url($media_details->url);
						preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $parsedVideoLink['host'], $matches);
						$domain	= $matches['domain'];
						
						if (!empty($domain)){
							$provider		= explode('.', $domain);
							$providerName	= JString::strtolower($provider[0]);
							$libraryPath	= JPATH_ROOT .'/components/com_guru/helpers/videos' .'/'. $providerName . '.php';
							require_once($libraryPath);
							$className		= 'PTableVideo' . JString::ucfirst($providerName);
							$videoObj		= new $className();
							$videoObj->init($media_details->url);
							$video_id		= $videoObj->getId();
							
							if($providerName == "youtube" || $providerName == "vimeo" || $providerName == "dailymotion"){
								$video_id = $video_id."?autoplay=".$media_details->auto_play;
							}
							
							$videoPlayer	= $videoObj->getViewHTML($video_id, $media_details->width, $media_details->height);
							$attribs->layout_media[] = $videoPlayer;
						}
					}
					else{
						$attribs->layout_media[] = $this->parse_media(intval($result[$i]->media_id), $attribs->layout);
					}
				}
				elseif($result[$i]->type=="scr_m" && $result[$i]->layout=="12"){
					$attribs->layout_media[] = $this->parse_media(intval($result[$i]->media_id), $attribs->layout);
				}
			}
		}
		if(!isset($attribs->layout_text[0]))
			$attribs->layout_text[0]="";
		if(!isset($attribs->layout_text[1]))
			$attribs->layout_text[1]="";
		if(!isset($attribs->layout_media[0]))
			$attribs->layout_media[0]="";
		if(!isset($attribs->layout_media[1]))
			$attribs->layout_media[1]="";
		
		$sql = "select `media_id` from #__guru_mediarel where type_id=".intval($this->_id)." and layout=99";
		$db->setQuery($sql);
		$db->query();
		$audio_id = $db->loadResult();
		if(isset($audio_id)){
			$attribs->audio = $this->parse_media($audio_id);
		}
		
		$sql="SELECT lm.media_id as jump, lj.text
			 FROM #__guru_mediarel as lm
			 LEFT JOIN #__guru_jump as lj
			 ON lm.media_id=lj.id
			 WHERE type_id=".intval($this->_id)." 
			 AND type='jump' order by lj.button asc ";
			 
		$attribs->layout_jump=$this->_getList($sql);

		if($attribs->step_access==2 || ($attribs->step_access<2 && $my->id>0)){		
			$sql="SELECT distinct(lt.id), ordering, step_access
				FROM #__guru_task lt
				LEFT JOIN #__guru_mediarel lm
				ON lt.id=lm.media_id
				WHERE lm.type_id=".intval($this->_module)."
				and type='dtask' 
				AND lt.startpublish <= '".$date."' 
				AND (lt.endpublish = '0000-00-00 00:00:00' OR lt.endpublish >= '".$date."')
				ORDER BY ordering";
				
			$db->setQuery($sql);
			$db->query();
			$result=$db->loadObjectList();
			
			
			for($i=0;$i<count($result);$i++){
				if($result[$i]->id == intval($this->_id) && $i==0){
					$attribs->prevs = 0;
					if(isset($result[$i+1])){
						$attribs->nexts	= $result[$i+1]->id;
						$attribs->nextaccess = $is_course_free_for_guests ? 2 : @$result[$i+1]->step_access;
					}
				}
				elseif($result[$i]->id==intval($this->_id) && $i==count($result)-1){
					$attribs->prevs	= @$result[$i-1]->id;
					$attribs->prevaccess = $is_course_free_for_guests ? 2 : @$result[$i-1]->step_access;
					$attribs->nexts	= 0;
				}
				elseif($result[$i]->id==intval($this->_id)){
					$attribs->prevs = @$result[$i-1]->id;
					$attribs->nexts	= @$result[$i+1]->id;
					$attribs->prevaccess = $is_course_free_for_guests ? 2 : @$result[$i-1]->step_access;
					$attribs->nextaccess = $is_course_free_for_guests ? 2 : @$result[$i+1]->step_access;
				}
			}		
			//start get the program/course
			$sql = "SELECT `pid`
					FROM #__guru_days
					WHERE `id`=".intval($attribs->module);

			$db->setQuery($sql);
			$db->query();
			$result=$db->loadObject();
			$attribs->pid = $result->pid;
			if(!isset($attribs->nexts) || $attribs->nexts == "0"){
				$next_module = $this->getNextModule($attribs->pid, $this->_module);				
				$attribs->next_module = $next_module;
			}
			else{
				$attribs->next_module = $this->_module;
			}	
			
			return $attribs;
		}
		else{
			return false;
		}
			
	}
	
	function parse_txt ($id){
		//return $id;
		$db = JFactory::getDBO();
		
		$q = "SELECT * FROM #__guru_config WHERE id = '1' ";
		$db->setQuery( $q );
		$configs = $db->loadObject();
				
		$q  = "SELECT * FROM #__guru_media WHERE id = ".$id;
		$db->setQuery( $q );
		$result = $db->loadObject();	
		
		$the_media = $result;
		
		if($the_media->type=='text')
			{
				//$media = $the_media->code;
				
				if($the_media->show_instruction ==2){
					$media .= $the_media->code; 
					//$media .= $the_media->name; 
				}
				elseif($the_media->show_instruction ==1){
					$media .= $the_media->code; 
					//$media .= ''.$the_media->instructions.'<br/>';
					//$media .= $the_media->name;
				} 
				elseif($the_media->show_instruction ==0){
					//$media .= ''.$the_media->instructions.'<br/>';
					$media .= $the_media->code; 
					//$media .= $the_media->name; 
				}
				
			}
		if($the_media->type=='docs')
			{
			
				$the_base_link = explode('administrator/', $_SERVER['HTTP_REFERER']);
				$the_base_link = $the_base_link[0];
				$media = 'The selected element is a text file that can\'t have a preview';
				//$media = JText::_("GURU_TASKS");
				
				if($the_media->source == 'local' && (substr($the_media->local,(strlen($the_media->local)-3),3) == 'txt' || substr($the_media->local,(strlen($the_media->local)-3),3) == 'pdf') && $the_media->width == 0)
				$media='<div class="contentpane">
								<iframe id="blockrandom"
									name="iframe"
									src="'.$the_base_link.'/'.$configs->docsin.'/'.$the_media->local.'"
									width="100%"
									height="600"
									scrolling="auto"
									align="top"
									frameborder="2"
									class="wrapper">
									This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
								</div>';
								
				if($the_media->source == 'local' && $the_media->width == 1)
				$media='<a href="'.$the_base_link.'/'.$configs->docsin.'/'.$the_media->local.'" target="_blank">'.$the_media->name.'</a>';
		
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
				//$media = 'The quiz is being generated';
				$media = '';
				$q  = "SELECT * FROM #__guru_quiz WHERE id = ".$the_media->source." and published=1 ";
				$db->setQuery( $q );
				$result_quiz = $db->loadObject();				
				
				$media = $media. '<strong>'.$result_quiz->name.'</strong><br /><br />';
				$media = $media. $result_quiz->description.'<br /><br />';
				
				$q  = "SELECT * FROM #__guru_questions WHERE qid = ".$the_media->source." and published=1";
				$db->setQuery( $q );
				$quiz_questions = $db->loadObjectList();			
				
				foreach( $quiz_questions as $one_question )
					{
						$media = $media.'<div align="left">'.$one_question->text.'<div>';
						
						$media = $media.'<div align="left" style="padding-left:30px;">';
						if($one_question->a1!=''){
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a1.'</input><br />';
						}	
						if($one_question->a2!=''){
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a2.'</input><br />';
						}	
						if($one_question->a3!=''){
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a3.'</input><br />';
						}	
						if($one_question->a4!=''){
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a4.'</input><br />';
						}
						if($one_question->a5!=''){
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a5.'</input><br />';
						}	
						if($one_question->a6!=''){
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a6.'</input><br />';
						}	
						if($one_question->a7!=''){
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a7.'</input><br />';
						}	
						if($one_question->a8!=''){
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a8.'</input><br />';
						}	
						if($one_question->a9!=''){
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a9.'</input><br />';		
						}	
						if($one_question->a10!=''){
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a10.'</input><br />';		
						}	
						$media = $media.'</div>';																																										
					}		
					
				$media = $media.'<br /><div align="left" style="padding-left:30px;"><input type="submit" value="'.JText::_("GURU_QUIZ_SUBMIT").'" /></div>';	
			}	
		
		if(!isset($media)){
			$media=NULL;
		}
		
		if($the_media->show_instruction == "0"){//show the instructions above
			$media = '<div  style="text-align:center"><i>'.$the_media->instructions.'</i></div>'.
					 $media;
					 //'<br /><br />';
					 //<div style="text-align:center"><i>'.$the_media->name.'</i></div>';
		}
		elseif($the_media->show_instruction == "1"){//show the instructions above
			$media = $media.
			   		 //'<br /><br />
					 //<div style="text-align:center"><i>'.$the_media->name.'</i></div>
					 '<br /><br />
					 <div  style="text-align:center"><i>'.$the_media->instructions.'</i></div>';
		}
		elseif($the_media->show_instruction == "2"){//don't show the instructions
			$media = $media;
			  		 //'<br /><br />
					 //<div style="text-align:center"><i>'.$the_media->name.'</i></div>';
		}
		if($the_media->type != 'quiz'){
			if(@$the_media->hide_name == 0){
				$media .= '<div class="clearfix"></div><div class="g_media_title text-centered">'.@$the_media->name.'</div>';
			}
		}
		
		return stripslashes($media);	
	}
	
	function parse_media ($id, $layout=0){
		$db = JFactory::getDBO();
		$jnow 	= JFactory::getDate();
		$date 	= $jnow->toSQL();
		$guruHelper = new guruHelper();
		$max_id = NULL;
		
		$configs = $this->getConfig();	
		$no_plugin_for_code = 0;
		$aheight			= 0; 
		$awidth				= 0; 
		$vheight			= 0; 
		$vwidth				= 0;
		
		if($layout!=12){
			$sql = "SELECT * FROM #__guru_media
					WHERE id = ".$id;
			$db->setQuery($sql);
			$media = $db->loadObject();	
			@$media->code = stripslashes(@$media->code);
		}
		else{
			$sql = "SELECT * FROM #__guru_quiz WHERE id = ".$id;
			$db->setQuery($sql);
			$db->query();
			$media = $db->loadObject();
			$media->type="quiz";
			$media->code="";
		}

		$default_video_size_string = $configs->default_video_size;
		$default_video_size_array = explode("x", $default_video_size_string);
		$default_video_height = $default_video_size_array ["0"];
		$default_video_width = $default_video_size_array ["1"];
		
		//start video
		
		if(isset($media->type) && $media->type=='video'){
			if ($media->source=='url' || $media->source=='local'){				
				if($media->width == 0 || $media->height == 0 || $media->option_video_size == "0"){
					$media->width = $default_video_width; //300; 
					$media->height = $default_video_height; //400;
				}
			}elseif($media->source=='code'){	
				if($media->option_video_size == "0"){
					$media->width = $default_video_width; //300; 
					$media->height = $default_video_height; //400;
					
					$replace_with = 'width="'.$media->width.'"';
					$media->code = preg_replace('#width="[0-9]+"#', $replace_with, $media->code);
					$replace_with = 'height="'.$media->height.'"';
					$media->code = preg_replace('#height="[0-9]+"#', $replace_with, $media->code);	
					
					$replace_with = 'name="width" value="'.$media->width.'"';
					$media->code = preg_replace('#name="width" value="[0-9]+"#', $replace_with, $media->code);
					$replace_with = 'name="height" value="'.$media->height.'"';
					$media->code = preg_replace('#name="height" value="[0-9]+"#', $replace_with, $media->code);	
				}
				elseif ($media->width == 0 || $media->height == 0){
					//parse the code to get the width and height if we have width=... and height=....
					$begin_tag = strpos($media->code, 'width="');
					if ($begin_tag!==false){
						$remaining_code = substr($media->code, $begin_tag+7, strlen($media->code));
						$end_tag = strpos($remaining_code, '"');
						$media->width = substr($remaining_code, 0, $end_tag);					
						$begin_tag = strpos($media->code, 'height="');
						if ($begin_tag!==false){
							$remaining_code = substr($media->code, $begin_tag+8, strlen($media->code));
							$end_tag = strpos($remaining_code, '"');
							$media->height = substr($remaining_code, 0, $end_tag);
							$no_plugin_for_code = 1;
						}
						else{
							$media->width = $default_video_width; //300; 
							$media->height = $default_video_height; //400;
						}	
					}else{
						$media->width = $default_video_width; //300; 
						$media->height = $default_video_height; //400;
					}						
				}
				else{
					if($media->option_video_size == "0"){
						$media->width = $default_video_width; //300; 
						$media->height = $default_video_height; //400;
					}
					$replace_with = 'width="'.$media->width.'"';
					$media->code = preg_replace('#width="[0-9]+"#', $replace_with, $media->code);
					$replace_with = 'height="'.$media->height.'"';
					$media->code = preg_replace('#height="[0-9]+"#', $replace_with, $media->code);	
					
					$replace_with = 'name="width" value="'.$media->width.'"';
					$media->code = preg_replace('#name="width" value="[0-9]+"#', $replace_with, $media->code);
					$replace_with = 'name="height" value="'.$media->height.'"';
					$media->code = preg_replace('#name="height" value="[0-9]+"#', $replace_with, $media->code);	
				}
			}
			$vwidth=$media->width;
			$vheight=$media->height;	
		}		
		//end video
		
		//start audio	
		elseif(isset($media->type) && $media->type=='audio'){
			if ($media->source=='url' || $media->source=='local'){	
				if ($media->width == 0 || $media->height == 0){
					$media->width=20; 
					$media->height=300;
				}
			}		
			elseif ($media->source=='code'){
				if ($media->width == 0 || $media->height == 0){
					$begin_tag = strpos($media->code, 'width="');
					if ($begin_tag!==false){
						$remaining_code = substr($media->code, $begin_tag+7, strlen($media->code));
						$end_tag = strpos($remaining_code, '"');
						$media->width = substr($remaining_code, 0, $end_tag);
						$begin_tag = strpos($media->code, 'height="');
						if ($begin_tag!==false){
							$remaining_code = substr($media->code, $begin_tag+8, strlen($media->code));
							$end_tag = strpos($remaining_code, '"');
							$media->height = substr($remaining_code, 0, $end_tag);
							$no_plugin_for_code = 1;
						}else{
							$media->height=20; 
							$media->width=300;
						}	
					}else{
						$media->height=20; 
						$media->width=300;
					}							
				}else{					
					$replace_with = 'width="'.$media->width.'"';
					$media->code = preg_replace('#width="[0-9]+"#', $replace_with, $media->code);
					$replace_with = 'height="'.$media->height.'"';
					$media->code = preg_replace('#height="[0-9]+"#', $replace_with, $media->code);
				}
			}	
			$awidth=$media->width;
			$aheight=$media->height;
		}	
		
		$parts=explode(".", @$media->local);
		$extension=$parts[count($parts)-1];
		
		if(isset($media->type) && ($media->type=='video' || $media->type=='audio')){
			$media->width = "100%";
			if($media->type=='video' && $extension=="avi"){
				$auto_play = "";
				if($media->auto_play == "1"){
					$auto_play = "&autoplay=1";
				}
				$media->code = '<object id="MediaPlayer1" CLASSID="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" type="application/x-oleobject" width="'.$media->width.'" height="'.$media->height.'">
<param name="fileName" value="'.JURI::root().$configs->videoin."/".$media->local.'">
<param name="animationatStart" value="true">
<param name="transparentatStart" value="true">
<param name="autoStart" value="true">
<param name="showControls" value="true">
<param name="Volume" value="10">
<param name="autoplay" value="false">
<embed width="'.$media->width.'" height="'.$media->height.'" name="plugin" src="'.JURI::root().$configs->videoin."/".$media->local.'" type="video/x-msvideo">
</object>';
			}
			elseif($no_plugin_for_code == 0){
				$vwidth = "100%";
				$awidth = "100%";
				$media->code = $guruHelper->create_media_using_plugin($media, $configs, $awidth, $aheight, $vwidth, $vheight);	
			}
		} 
		//end audio

		//start docs type
		if(isset($media->type) && $media->type=='docs'){
			$media->code = 'The selected element is a text file that can\'t have a preview';	
			
			if($media->source == 'local' && (substr($media->local,(strlen($media->local)-3),3) == 'txt' || substr($media->local,(strlen($media->local)-3),3) == 'pdf') && $media->width > 1 && $media->height > 0) {
				if($media->height == 0){
					$media->height = 600;
				}
				$media->code='<div class="contentpane">
							<iframe id="blockrandom" name="iframe" src="'.JURI::root().$configs->docsin.'/'.$media->local.'" width="'.$media->width.'" height="'.$media->height.'" scrolling="auto" align="top" frameborder="2" class="wrapper"> This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
						</div>';
				
				$media->name = '<div style="text-align:center"><i>'.$media->name.'</i></div>';
				$media->instructions = '<div style="text-align:center"><i>'.$media->instructions.'</i></div>';
				$media->code = '<div style="text-align:center"><i>'.$media->code.'</i></div>';
				
				$return = "";
				if($media->show_instruction ==2){
					$return .= $media->code;
				}
				elseif($media->show_instruction ==1){
					$return .= $media->code; 
					$return .= ''.$media->instructions.'<br/>';
				} 
				elseif($media->show_instruction ==0){
					$return .= ''.$media->instructions.'<br/>';
					$return .= $media->code;
				}
				
				if(isset($media->hide_name) && $media->hide_name == 0){
					$return .= $media->name;
				}
				
				return $return;
			}
			elseif($media->source == 'url' && (substr($media->url,(strlen($media->url)-3),3) == 'txt' || substr($media->url,(strlen($media->url)-3),3) == 'pdf') && $media->width > 1) {
				$media->code='<div class="contentpane">
							<iframe id="blockrandom" name="iframe" src="'.$media->url.'" width="'.$media->width.'" height="'.$media->height.'" scrolling="auto" align="top" frameborder="2" class="wrapper"> This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
						</div>';
				
				$media->name = '<div style="text-align:center"><i>'.$media->name.'</i></div>';
				$media->instructions = '<div style="text-align:center"><i>'.$media->instructions.'</i></div>';
				$media->code = '<div style="text-align:center"><i>'.$media->code.'</i></div>';
				
				$return = "";
				if($media->show_instruction ==2){
					$return .= $media->code;
				}
				elseif($media->show_instruction ==1){
					$return .= $media->code; 
					$return .= ''.$media->instructions.'<br/>';
				} 
				elseif($media->show_instruction ==0){
					$return .= ''.$media->instructions.'<br/>';
					$return .= $media->code;
				}
				
				if(isset($media->hide_name) && $media->hide_name == 0){
					$return .= $media->name;
				}
				
				return $return;
			}
			elseif($media->source == 'local' && $media->width == 1){
				$media->code='<br /><a href="'.JURI::root().$configs->docsin.'/'.$media->local.'" target="_blank">'.$media->local.'</a>';
				return stripslashes($media->code.'<p /><div  style="text-align:center"><i>'.$media->instructions.'</i></div>');
			}
			
			elseif($media->source == 'url'  && $media->width == 0){
				$media->code='<div class="contentpane">
							<iframe id="blockrandom" name="iframe" src="'.$media->url.'" width="100%" height="600" scrolling="auto" align="top" frameborder="2" class="wrapper"> This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe> </div>';		
			}				
			elseif($media->source == 'url'  && $media->width == 1){
				$media->code='<a href="'.$media->url.'" target="_blank">'.$media->local.'</a>';		
			}
			elseif($media->source == 'local'  && $media->height == 0){
				$media->code='<br /><a href="'.JURI::root().$configs->docsin.'/'.$media->local.'" target="_blank">'.$media->name.'</a>';
				return stripslashes($media->code.'<p /><div  style="text-align:center"><i>'.$media->instructions.'</i></div>');		
			}		
		}
		//end doc
	
		//start url
		if(isset($media->type) && $media->type=='url'){
			if($media->width == 1){
				$media->code = '<a href="'.$media->url.'" target="_blank">'.$media->url.'</a>';
			}
			else{
				$media->code = '<iframe id="blockrandom" name="iframe" src="'.$media->url.'" width="800px" height="600px" scrolling="auto" align="top" frameborder="2"></iframe>';
			}
		}
		//end url
		
		//start article
		if(isset($media->type) && $media->type=='Article'){
			$id = $media->code;
			include_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_guru'.DS.'models'.DS.'gurutask.php');
			$media->code = guruAdminModelguruTask::getArticleById($id);
		}
		//end article

		//start image				
		if(isset($media->type) && $media->type=='image'){
			require_once("components/com_guru/helpers/helper.php");
			$helper = new guruHelper();
			$width = $media->width;
			$height = $media->height;
			$new_size = "";
			$type = "";
			if(intval($width) != 0){
				$new_size = $width;
				$type = "w";
			}
			else{
				$new_size = $height;
				$type = "h";
			}
			
			$helper->createThumb($media->local, $configs->imagesin.'/media', $new_size, $type);
			$media->code = '<img src="'.JURI::root().$configs->imagesin.'/media/thumbs'.$media->local.'" />';	
		}
		//end image
		
		//start text
		if(isset($media->type) && $media->type=='text'){
			$media->code = $media->code;
		}
		//end text
		
		//start docs type
		if(isset($media->type) && $media->type=='file'){			
			$media->code = JText::_('GURU_NO_PREVIEW');	
			if($media->source == 'local' && (substr($media->local,(strlen($media->local)-3),3) == 'zip' || substr($media->local,(strlen($media->local)-3),3) == 'exe')){
				$media->code='<br /><a href="'.JURI::root().$configs->filesin.'/'.$media->local.'" target="_blank">'.$media->local.'</a>';
				//return stripslashes($media->code.'<p /><div  style="text-align:center"><i>'.$media->instructions.'</i></div>');
			}			
			else if($media->source == 'url'){
				$media->code='<a href="'.$media->url.'" target="_blank">'.$media->local.'</a>';		
			}	
		}
		//end doc
		if(isset($media->type) && $media->type=='quiz' && $media->published=='1' && strtotime($media->startpublish) <= strtotime($date) && ( strtotime($media->endpublish) >= strtotime($date) || $media->endpublish == "0000-00-00 00:00:00")){
			$document = JFactory::getDocument();
    		$document->addStyleSheet("components/com_guru/css/quiz.css");
			$document->addScript("components/com_guru/js/programs.js");

			$media->code = '';				
			$query  = "SELECT * FROM #__guru_quiz WHERE id = ".$media->id." and published=1";
			$db->setQuery($query);
			$result_quiz = $db->loadObject();
			
			
			$sql_quest = "SELECT count(id) from #__guru_questions where qid=".$media->id;
			$db->setQuery($query);
			$result_quest = $db->loadResult();
			
			$query  = "UPDATE #__guru_quiz set nb_quiz_select_up=".$result_quest." WHERE id = ".$media->id;
			$db->setQuery($query);
			
			$sql = "SELECT max_score, pbl_max_score, limit_time, show_limit_time, time_quiz_taken, show_nb_quiz_taken, nb_quiz_select_up, show_nb_quiz_select_up  FROM #__guru_quiz where id=".$result_quiz->id;
			$db->setQuery($sql);
			$result_settings_quiz = $db->loadObject();
			if($result_quiz->is_final == 0){
				$text_quiz_info_top = JText::_("GURU_MINIMUM_SCORE_QUIZ");
				$text_quiz_info_top1 = JText::_("GURU_QUIZ_TAKEN_UP_TO");
			}
			else{
				$text_quiz_info_top = JText::_("GURU_MINIMUM_SCORE_FINAL_QUIZ");
				$text_quiz_info_top1 = JText::_("GURU_QUIZ_CAN_BE_TAKEN");
			}
			$table_quiz = '<table><tr><td>'; 
			
			
			
			if($result_settings_quiz->show_limit_time ==0){
				$table_quiz.= JText::_("GURU_LIMIT_QUIZ").": "."<span style='color:#669900'>".$result_settings_quiz->limit_time."</span>"." ".JText::_("GURU_REAL_MINUTES");
			}
			$table_quiz.='</td><td style="padding-left:25px;">';
			if($result_settings_quiz->pbl_max_score ==0){
				$table_quiz.= $text_quiz_info_top.": "."<span style='color:#669900'>".$result_settings_quiz->max_score.JText::_("GURU_PERCENT")."</span>";
			}
			$table_quiz.='</td><tr><td>';
			if($result_settings_quiz->show_nb_quiz_select_up == 0){
				$table_quiz.= JText::_("GURU_QUESTIONS").": "."<span style='color:#669900'>".$result_settings_quiz->nb_quiz_select_up."</span>";
			}
			$table_quiz.='</td><td style="padding-left:25px;">';
				
			
						
			if($result_settings_quiz->show_nb_quiz_taken ==0){
				if($result_settings_quiz->time_quiz_taken < 0){
					$result_settings_quiz->time_quiz_taken = 0;
				}
				if($result_settings_quiz->time_quiz_taken == 11){
					$timestotake = "Unlimited";
				}
				else{
					$timestotake = $result_settings_quiz->time_quiz_taken;
				}
				$table_quiz.= $text_quiz_info_top1.": "."<span style='color:#669900'>".$timestotake."</span>"." ".JText::_("GURU_TIMES");
			}
			$table_quiz.='</td></tr></table>';
			if(isset($_SESSION["submit_disabled"])){
				$table_quiz.='<table><tr><td style="padding-top:15px;">'.$_SESSION["submit_disabled"].'</td></tr></table>';
			}
			
			$user = JFactory::getUser();
			$user_id = $user->id;
			$sql = "SELECT `time_quiz_taken_per_user` FROM #__guru_quiz_taken where quiz_id=".$result_quiz->id." AND user_id=".$user_id." ORDER BY id DESC LIMIT 0,1";
			$db->setQuery($sql);
			$result_time_user = $db->loadColumn();
			$result_time_user = @$result_time_user["0"];
			
			if($result_settings_quiz->time_quiz_taken == 11){
				$result_time_user = 11;
			}
			else{
				$result_time_user = $result_settings_quiz->time_quiz_taken - $result_time_user;
			}
			
			$media->code .= '<span class="guru_quiz_title">'.$result_quiz->name.'</span>';
			$media->code .=' <div class="g_quiz_info">'.$table_quiz.'</div>';
			if($result_quiz->description !=""){
				$media->code .= '<span class="guru_quiz_description">'.$result_quiz->description.'</span>';
			}
			if(isset($result_settings_quiz->nb_quiz_select_up) && $result_settings_quiz->nb_quiz_select_up !=0 && $result_settings_quiz->show_nb_quiz_select_up ==0){
				$order_by = " ORDER BY RAND() LIMIT  ".$result_settings_quiz->nb_quiz_select_up."";
			}
			else{
				$order_by = " ORDER BY reorder LIMIT  ".$result_settings_quiz->nb_quiz_select_up."";
			}

			if($result_quiz->is_final == 1){
				$sql = "SELECT 	quizzes_ids FROM #__guru_quizzes_final WHERE qid=".$media->id;
				$db->setQuery($sql);
				$db->query();
				$result=$db->loadResult();	
				$result_qids = explode(",",trim($result,","));
				
				if($result_qids["0"] == ""){
					$result_qids["0"] = 0;
				}
				
				if(isset($result_qids) && count($result_qids) > 0){
					foreach($result_qids as $key=>$value){
						$quiz_id = intval($value);
						$sql = "select `published` from #__guru_quiz where `id`=".intval($quiz_id);
						$db->setQuery($sql);
						$db->query();
						$published = $db->loadColumn();
						$published = @$published["0"];
						if(intval($published) == 0){
							unset($result_qids[$key]);
						}
					}
				}
				
				if(count($result_qids) == 0 || $result_qids["0"] == ""){
					$result_qids["0"] = 0;
				}
				
				$query  = "SELECT * FROM #__guru_questions WHERE qid IN (".implode(",", $result_qids).") and published=1".$order_by;
			}
			else{
				$query  = "SELECT * FROM #__guru_questions WHERE qid = ".$media->id." and published=1".$order_by;
			}		
				
			$db->setQuery($query);
			$quiz_questions = $db->loadObjectList();

				
			$media->code.='<div id="the_quiz">';
				
			$array_quest = array();
				
			$question_number = 1;
			if(!isset($quiz_questions) || count($quiz_questions) <= 0){
				return "";
			}
			
			$per_page = $result_quiz->questions_per_page;// questions per page
			if($per_page == 0){
				$per_page = count($quiz_questions);
			}
			$nr_pages = 1;// default one page
			
			if(count($quiz_questions) > 0 && count($quiz_questions) > $per_page){
				$nr_pages = ceil(count($quiz_questions) / $per_page);
			}

			for($pag = 1; $pag <= $nr_pages; $pag++){
				$k = ($pag - 1) * $per_page;
				$added = 0;

				$display = "";
				if($pag == 1){
					$display = "block";
				}
				else{
					$display = "none";
				}
				
				$media->code .= '<div id="quiz_page_'.$pag.'" style="display:'.$display.';">'; // start page

				while(isset($quiz_questions[$k]) && $added < $per_page){
					$one_question = $quiz_questions[$k];
					
					$array_quest[] = $one_question->id;
					$question_answers_number = 0;
					
					$media->code .= '<ul class="guru_list">';
					$media->code .= 	'<li class="question">'.$question_number.". ".$one_question->text.'</li>';
					if($one_question->a1!=''){
						$media->code .= '<li class="answer"><input type="checkbox" value="1a" name="q'.$one_question->id.'[]" />'." ".$one_question->a1.'</li>';
						$question_answers_number++;
					}	
					
					if($one_question->a2!=''){
						$media->code .= '<li class="answer"><input type="checkbox" value="2a" name="q'.$one_question->id.'[]">'." ".$one_question->a2.'</li>';
						$question_answers_number++;
					}	
					if($one_question->a3!=''){
						$media->code .= '<li class="answer"><input type="checkbox" value="3a" name="q'.$one_question->id.'[]">'." ".$one_question->a3.'</li>';
						$question_answers_number++;
					}	
					if($one_question->a4!=''){
						$media->code .= '<li class="answer"><input type="checkbox" value="4a" name="q'.$one_question->id.'[]">'." ".$one_question->a4.'</li>';
						$question_answers_number++;
					}	
					if($one_question->a5!=''){
						$media->code .= '<li class="answer"><input type="checkbox" value="5a" name="q'.$one_question->id.'[]">'." ".$one_question->a5.'</li>';
						$question_answers_number++;
					}	
					if($one_question->a6!=''){
						$media->code .= '<li class="answer"><input type="checkbox" value="6a" name="q'.$one_question->id.'[]">'." ".$one_question->a6.'</li>';
						$question_answers_number++;
					}	
					if($one_question->a7!=''){
						$media->code .= '<li class="answer"><input type="checkbox" value="7a" name="q'.$one_question->id.'[]">'." ".$one_question->a7.'</li>';
						$question_answers_number++;
					}	
					if($one_question->a8!=''){
						$media->code .= '<li class="answer"><input type="checkbox" value="8a" name="q'.$one_question->id.'[]">'." ".$one_question->a8.'</li>';
						$question_answers_number++;
					}
					if($one_question->a9!=''){
						$media->code .= '<li class="answer"><input type="checkbox" value="9a" name="q'.$one_question->id.'[]">'." ".$one_question->a9.'</li>';
						$question_answers_number++;
					}
					if($one_question->a10!=''){
						$media->code .= '<li class="answer"><input type="checkbox" value="10a" name="q'.$one_question->id.'[]">'." ".$one_question->a10.'</li>';
						$question_answers_number++;
					}		
							
					$correct_answers = explode('|||', $one_question->answers);
					foreach($correct_answers as $key=>$value){
						if(intval(intval($value)) != 0){
							$correct_answers[$key] = intval($value);
						}
						else{
							unset($correct_answers[$key]);
						}
					}
					
					$the_right_answer = array();
					foreach($correct_answers as $key=>$value){
						$column = "a".$value;
						$the_right_answer[] = $one_question->$column;
						
					}
					
					
					$the_right_answer = implode("|||", $the_right_answer);
					$all_answers = array();
					$all_answers_text = array();
					
					if(trim($one_question->a1) != ""){
						$all_answers[] = "1a"; 
						$all_answers_text[] = trim(str_replace("'", "&acute;", $one_question->a1));
					}
					if(trim($one_question->a2) != ""){
						$all_answers[] = "2a";
						$all_answers_text[] = trim(str_replace("'", "&acute;", $one_question->a2));
					}
					if(trim($one_question->a3) != ""){
						$all_answers[] = "3a"; 
						$all_answers_text[] = trim(str_replace("'", "&acute;", $one_question->a3));
					}
					if(trim($one_question->a4) != ""){
						$all_answers[] = "4a"; 
						$all_answers_text[] = trim(str_replace("'", "&acute;", $one_question->a4));
					}			
					if(trim($one_question->a5) != ""){
						$all_answers[] = "5a"; 
						$all_answers_text[] = trim(str_replace("'", "&acute;", $one_question->a5));
					}				
					if(trim($one_question->a6) != ""){
						$all_answers[] = "6a";
						$all_answers_text[] = trim(str_replace("'", "&acute;", $one_question->a6));
					}
					if(trim($one_question->a7) != ""){
						$all_answers[] = "7a";
						$all_answers_text[] = trim(str_replace("'", "&acute;", $one_question->a7));
					}				
					if(trim($one_question->a8) != ""){
						$all_answers[] = "8a";
						$all_answers_text[] = trim(str_replace("'", "&acute;", $one_question->a8));
					}				
					if(trim($one_question->a9) != ""){
						$all_answers[] = "9a"; 
						$all_answers_text[] = trim(str_replace("'", "&acute;", $one_question->a9));
					}				
					if(trim($one_question->a10) != ""){
						$all_answers[] = "10a";
						$all_answers_text[] = trim(str_replace("'", "&acute;", $one_question->a10));
					}
					
					$all_answers = implode("|||", $all_answers);
					$all_answers_text = implode("|||", $all_answers_text);
					$question_number++; 
					$media->code.='</ul>';
					
					$k++;
					$added++;
				}
				
				if($pag == $nr_pages){
					$catid_req = JRequest::getVar("catid","");
					$module_req = JRequest::getVar("module","");
					$cid_req = JRequest::getVar("cid","");
					
					$media->code.='<br />
						   <div align="left" style="padding-left:4px;">
								<input type="hidden" value="'.$media->name.'" id="quize_name" name="quize_name"/>
								<input type="hidden" value="'.$result_settings_quiz->nb_quiz_select_up.'" id="nb_of_questions" name="nb_of_questions"/>
								<input type="hidden" value="'.$media->id.'" id="quize_id" name="quize_id"/>
								<input type="hidden" value="1" name="submit_action" id="submit_action" />
								<input type="hidden" value="'.$catid_req.'" name="catid_req" id="catid_req">
								<input type="hidden" value="'.$module_req.'" name="module_req" id="module_req">
								<input type="hidden" value="'.$cid_req.'" name="cid_req" id="cid_req">
								<input type="hidden" value="'.$open_target.'" name="open_target" id="open_target">
								<input type="button"  name="submitbutton" class="btn btn-warning" id ="submitbutton" value="'.JText::_("GURU_QUIZ_SUBMIT").'" onclick="document.getElementById(\'quizz_exam\').submit();" />
						  </div>';
				}
				
				$media->code .= '</div>'; // end page
			}
			
			if($nr_pages > 1){
				$media->code .= '<div class="pagination pagination-centered"><ul class="uk-pagination">';
				$media->code .= 	'<li class="pagination-start" id="pagination-start"><span class="pagenav">'.JText::_("GURU_START").'</span></li>';
				$media->code .= 	'<li class="pagination-prev" id="pagination-prev"><span class="pagenav">'.JText::_("GURU_PREV").'</span></li>';
				for($p=1; $p<=$nr_pages; $p++){
					if($p == 1){
						$media->code .= '<li id="list_1"><span class="pagenav">1</span></li>';
					}
					else{
						$media->code .= '<li id="list_'.$p.'">
											<a onclick="changePage('.intval($p).', '.intval($nr_pages).'); return false;" href="#">'.$p.'</a>
										 </li>';
					}
				}
				$media->code .= 	'<li class="pagination-next" id="pagination-next">
										<a href="#" onclick="changePage(2, '.intval($nr_pages).'); return false;">'.JText::_("GURU_NEXT").'</a>
									 </li>';
				$media->code .= 	'<li class="pagination-end" id="pagination-end">
										<a href="#" onclick="changePage('.intval($nr_pages).', '.intval($nr_pages).'); return false;">'.JText::_("GURU_END").'</a>
									 </li>';
				$media->code .= '</ul></div>';
			}
			
			// create quiz taken and question
			$sql = "SELECT `open_target` FROM `#__guru_config` WHERE `id`=1";
			$db->setQuery($sql);
			$db->query();
			$open_target = $db->loadColumn();
			$open_target = $open_target["0"];
				
			$media->code.='<input type="hidden" value="'.($question_number-1).'" name="question_number" id="question_number" />';
			$media->code.='<input type="hidden" value="'.implode(",", $array_quest).'" name="list_questions_id" id="list_questions_id" />';
			$media->code.='<input type="hidden" value="'.$max_id.'" name="id_quiz_question" id="id_quiz_question" />';
			
			$_SESSION["questionsids"] = implode(",", $array_quest);
			$_SESSION["quiz_id"] = $media->id;

			if(isset($result_time_user) && $result_time_user <= 0){
				$disabled='disabled=disabled';
				$msg= JText::_("GURU_QUIZ_RES_MC");
				$_SESSION["submit_disabled"]=$msg;
			}
			else{
				$disabled='';
			}
			
			$media->code.='</div>';
		}
		$return = "";
		if(isset($media->show_instruction) && $media->show_instruction == "0"){//show the instructions above
			$return = '<div  style="text-align:center"><i>'.$media->instructions.'</i></div>'.
					 $media->code;
		}
		elseif(isset($media->show_instruction) && $media->show_instruction == "1"){//show the instructions above
			$return = $media->code.
			   		 '<br /><br />
					 <div  style="text-align:center"><i>'.$media->instructions.'</i></div>';
		}
		elseif(isset($media->show_instruction) && $media->show_instruction == "2"){//don't show the instructions
			$return = $media->code;
		}
		elseif(!isset($media->show_instruction) || $media->show_instruction == NULL){
			$return = $media->code;
		}
		
		if(@$media->type != 'quiz'){
			if(@$media->hide_name == 0){
				$return .= '<div class="clearfix"></div><div class="g_media_title text-centered">'.@$media->name.'</div>';
			}
		}
		
		return stripslashes($return);
	}	
	
	function getTask2($taskid){
			$database = JFactory::getDBO();
			
			$sql = "SELECT t.*, cat.name as cat_name FROM #__guru_task as t
					LEFT JOIN #__guru_taskcategory as cat on t.category = cat.id
					WHERE t.id = ".$taskid; 
			$database->setQuery($sql);
			$task = $database->loadObject();
			return $task;	
	
	}
	
	function getMediaForTask() {
				$sql = "SELECT * 
						FROM #__guru_media
						WHERE id in (SELECT media_id FROM #__guru_mediarel WHERE type_id=".intval(@$_GET['cid'])." AND type='task')";
			$this->_attributes = $this->_getList($sql);
		return $this->_attributes;
	}
	
	function getprogramname () {
			$id=JRequest::getVar("cid","0");
			$sql = "SELECT * FROM #__guru_program WHERE id= (SELECT pid FROM #__guru_days WHERE id= ".intval($id).") ";
			$programname = $this->_getList($sql);
			return $programname;
	}	

	function getday() {
			$sql = "SELECT * FROM #__guru_days WHERE id = ".intval(@$_GET['cid'])." ";
			$day = $this->_getList($sql);		
			return $day;
	}


	function find_task_status($progid, $day, $taskord) {
		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		$sql = "SELECT tasks FROM #__guru_programstatus WHERE userid=".$my->id." AND pid = (SELECT pid FROM #__guru_days WHERE id= ".$progid.")";
		$database->setQuery($sql);
		$result = $database->loadObjectList();
		
		$task_array = $result[0]->tasks;

		$task_array = explode(';', $task_array);
		$task_array = $task_array[$day-1];
		$task_array = explode('-', $task_array);
		
		if(isset($task_array[$taskord]))
			{
				$the_task_status = $task_array[$taskord];
				$task_value_array = explode(',', $the_task_status);		
				if(isset($task_value_array[1]))
					$status = $task_value_array[1];		
				else
					$status = 0;		
			}
		else $status = 0;		
	
		return $status;
	}	

	function getTaskforOrd($progid, $day, $taskord) {
		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		$sql = "SELECT tasks FROM #__guru_programstatus WHERE userid=".$my->id." AND pid = (SELECT pid FROM #__guru_days WHERE id= ".$progid.")";
		$database->setQuery($sql);
		$result = $database->loadResult();
		
		$task_array = $result;

		$task_array = explode(';', $task_array);
		$task_array = $task_array[$day-1];
		$task_array = explode('-', $task_array);
		
		if(isset($task_array[$taskord]))
			{
				$the_task_status = $task_array[$taskord];
				$task_value_array = explode(',', $the_task_status);		
				if(isset($task_value_array[0]))
					$taskid = $task_value_array[0];		
				else
					$taskid = 0;		
			}
		else $taskid = 0;		

		return $taskid;
	}	

	function find_no_of_tasks_done($progid, $day){
		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		$sql = "SELECT tasks FROM #__guru_programstatus WHERE userid=".$my->id." AND pid = (SELECT pid FROM #__guru_days WHERE id= ".$progid.")";
		$database->setQuery($sql);
		$result = $database->loadResult();
		$task_array = explode(';', $result);
		$task_list_of_day = $task_array[$day-1];
		$no = substr_count ($task_list_of_day, ',2');
		return $no;
	}

	function change_task_status_2($progid, $day, $taskord) {
		$jnow 	= JFactory::getDate();
		$date 	= $jnow->toSQL();
		
		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		if($my->id>0)
			$sql = "SELECT tasks, days FROM #__guru_programstatus WHERE userid = ".$my->id." AND pid = (SELECT pid FROM #__guru_days WHERE id= ".$progid.")";
		else 
			$sql = "SELECT tasks, days FROM #__guru_programstatus WHERE pid = (SELECT pid FROM #__guru_days WHERE id= ".$progid.")";
		$database->setQuery($sql);
		$result = $database->loadObject();
		
		$task_array = $result->tasks;
		$day_array = $result->days;
		
		//$copy_to_replace = $task_array;
		
		$task_array = explode(';', $task_array);

		$to_be_replaced = $task_array[$day-1];
		
		$to_be_replaced_array = explode('-', $to_be_replaced);
		
		$to_be_replaced_obj = $to_be_replaced_array[$taskord-1];
			$task_obj = explode(',', $to_be_replaced_obj);
		if($task_obj[1]==0)	
			$to_be_replaced_array[$taskord-1] = $task_obj[0].',1';
		
		$recreating_task_array = implode('-', $to_be_replaced_array);
		
		$task_array[$day-1] = $recreating_task_array;
		
		$new_task_array = implode(';', $task_array);
		
	
		$sql = "UPDATE #__guru_programstatus SET tasks='".$new_task_array."' WHERE pid = (SELECT pid FROM #__guru_days WHERE id= ".$progid.") AND userid =".$my->id;	
		$database->setQuery($sql);
		$database->query();		
		
				$day_array = explode(';', $day_array);
				
				$day_status_array = explode(',', $day_array[$day-1]);
				if($day_status_array[1]=='0')
					{
						$day_array[$day-1] = $day_status_array[0].',1';
						$day_for_database = implode(';', $day_array);
						$sql = "UPDATE #__guru_programstatus SET days='".$day_for_database."' WHERE pid =(SELECT pid FROM #__guru_days WHERE id= ".$progid.") AND userid =".$my->id;	
						$database->setQuery($sql);		
						$database->query();
					}	
					
				$sql = "SELECT status FROM #__guru_programstatus WHERE userid = ".$my->id." AND pid =(SELECT pid FROM #__guru_days WHERE id= ".$progid.")";
				$database->setQuery($sql);
				$result = $database->loadObjectList();
				$program_status = $result[0]->status;	
				
				if($program_status==0)
					{
						$sql = "UPDATE #__guru_programstatus SET status='1',startdate='".$date."' WHERE pid =(SELECT pid FROM #__guru_days WHERE id= ".$progid.") AND userid =".$my->id;	
						$database->setQuery($sql);		
						$database->query();
					}		
	}	

	function done_task_status_2($progid, $day, $taskord) {
		$jnow 	= JFactory::getDate();
		$date 	= $jnow->toSQL();
		
		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		$sql = "SELECT tasks, days FROM #__guru_programstatus WHERE userid = ".$my->id." AND pid = (SELECT pid FROM #__guru_days WHERE id= ".$progid.")";
		$database->setQuery($sql);
		$result = $database->loadObject();
		$task_array = $result->tasks;
		$day_array = $result->days;
		
		$task_array = explode(';', $task_array);

		$to_be_replaced = $task_array[$day-1];
		
		$to_be_replaced_array = explode('-', $to_be_replaced);
		
		$to_be_replaced_obj = $to_be_replaced_array[$taskord-1];
			$task_obj = explode(',', $to_be_replaced_obj);
		if($task_obj[1]!=2)	
			$to_be_replaced_array[$taskord-1] = $task_obj[0].',2';
		
		$recreating_task_array = implode('-', $to_be_replaced_array);
		
		$task_array[$day-1] = $recreating_task_array;
		
		$new_task_array = implode(';', $task_array);
	
		$sql = "UPDATE #__guru_programstatus SET tasks='".$new_task_array."' WHERE pid = (SELECT pid FROM #__guru_days WHERE id= ".$progid.") AND userid =".$my->id;	
		$database->setQuery($sql);
		$database->query();		
		
				// we seach again to see if all the tasks for the current day are done
				$day_done = 1;
				
				foreach($to_be_replaced_array as $to_be_replaced_array_value)
					{
						$to_be_replaced_array_value_array = explode(',', $to_be_replaced_array_value);
						if($to_be_replaced_array_value_array[0] && $to_be_replaced_array_value_array[1]!=2)
							{
								$day_done = 0;
								break;
							}
					}

				$day_array = explode(';', $day_array);
				
				$day_status_array = explode(',', $day_array[$day-1]);
				if($day_status_array[1]=='1' && $day_done == 1)
					{
						$day_array[$day-1] = $day_status_array[0].',2';
						$day_for_database = implode(';', $day_array);
						$sql = "UPDATE #__guru_programstatus SET days='".$day_for_database."' WHERE pid =(SELECT pid FROM #__guru_days WHERE id= ".$progid.") AND userid =".$my->id;	
						$database->setQuery($sql);		
						$database->query();

					}	
					
				$sql = "SELECT status FROM #__guru_programstatus WHERE userid = ".$my->id." AND pid = (SELECT pid FROM #__guru_days WHERE id= ".$progid.")";
				$database->setQuery($sql);
				$result = $database->loadObjectList();
				$program_status = $result[0]->status;	
				
				if($program_status==0)
					{
						$sql = "UPDATE #__guru_programstatus SET status='1',startdate='".$date."' WHERE pid =(SELECT pid FROM #__guru_days WHERE id= ".$progid.") AND userid =".$my->id;	
						$database->setQuery($sql);		
						$database->query();
					}
										
				// we seach again to see if all the tasks for the current day are done
				$program_done = 1;
				$to_be_replaced_array = $day_array;
				foreach($to_be_replaced_array as $to_be_replaced_array_value)
					{
						$to_be_replaced_array_value_array = explode(',', $to_be_replaced_array_value);
						if($to_be_replaced_array_value_array[0] && $to_be_replaced_array_value_array[1]!=2)
							{
								$program_done = 0;
								break;
							}
					}
					
				if($program_status==1 && $program_done==1)
					{
						$sql = "UPDATE #__guru_programstatus SET status='2',enddate='".$date."' WHERE pid =(SELECT pid FROM #__guru_days WHERE id= ".$progid.") AND userid =".$my->id;	
						$database->setQuery($sql);		
						$database->query();
						
						// if the program doesn't have a RE-DO free we LOCK it - status = -1 (begin)
						$sql = "SELECT redo FROM #__guru_program WHERE id = (SELECT pid FROM #__guru_days WHERE id= ".$progid.")";
						$database->setQuery($sql);
						$result = $database->loadResult();
						$program_redo = $result;
						if($program_redo == 'cost' || $program_redo == 'same')
							{
								$sql = "UPDATE #__guru_programstatus SET status='-1' WHERE pid =(SELECT pid FROM #__guru_days WHERE id= ".$progid.") AND userid =".$my->id;	
								$database->setQuery($sql);		
								$database->query();
							}
						// if the program doesn't have a RE-DO free we LOCK it - status = -1 (end)						
					}			

		$sql = "SELECT media_id FROM #__guru_mediarel WHERE type = 'email' AND type_id = (SELECT pid FROM #__guru_days WHERE id = '".$progid."') ";

		$database->setQuery($sql);
		$mail_array_ids = $database->loadResultArray();	
		$mail_array_ids = implode(',', $mail_array_ids);
						
		$task_array_to_string = implode(';', $task_array);	
		$how_many_tasks_are = substr_count($task_array_to_string , ',');	
		$how_many_tasks_are_done = substr_count($task_array_to_string , ',2');
		
		$done_raport =  $how_many_tasks_are_done /$how_many_tasks_are * 100;

		if ($done_raport >= 25 && $done_raport < 50)
			{
				$sql = "SELECT * FROM #__guru_emails WHERE `type` = 'trigger' AND `trigger` = 'quarter' AND `published` = '1' AND `id` in (".$mail_array_ids.") ";
			}
		elseif($done_raport >= 50 && $done_raport < 75)
			{
				$sql = "SELECT * FROM #__guru_emails WHERE `type` = 'trigger' AND `trigger` = 'half' AND `published` = '1' AND `id` in (".$mail_array_ids.") ";
			}
		elseif($done_raport >= 75 && $done_raport < 100)
			{
				$sql = "SELECT * FROM #__guru_emails WHERE `type` = 'trigger' AND `trigger` = 'uncompleted' AND `published` = '1' AND `id` in (".$mail_array_ids.") ";
			}
		elseif($done_raport == 100)
			{
				$sql = "SELECT * FROM #__guru_emails WHERE `type` = 'trigger' AND `trigger` = 'completed' AND `published` = '1' AND `id` in (".$mail_array_ids.") ";
			}	
		
		$database->setQuery($sql);
		$the_mail = $database->loadObjectList();
		
		if(isset($the_mail[0]))
			{
				$subject = $the_mail[0]->subject;
				$message = $the_mail[0]->body;
				// to do: parsing the {variables}
				$sqls = "SELECT * FROM #__guru_config WHERE id = 1 ";
				$database->setQuery($sqls);
				$configs = $database->loadObject();					

				if($the_mail[0]->sendtime == 0)
					{ // a real time mail with trigger - begin
						JFactory::getMailer()->sendMail( $configs->fromemail, $configs->fromname, $my->email, $subject, $message, 1 );
					} // a real time mail with trigger - end
				else
					{ // a delayed message with trigger - begin
						$mail_id = $the_mail[0]->id;
						$sql_pend_em = "SELECT count(id) FROM #__guru_emails_pending WHERE mail_id = ".$mail_id." AND user_id = ".$my->id." AND pid = (SELECT pid FROM #__guru_days WHERE id = '".$progid."') ";
						$database->setQuery($sql_pend_em);
						$pending_emails = $database->loadResult();			
						
						if($pending_emails==0)
							{
								$sql_pid = "SELECT pid FROM #__guru_days WHERE id= ".$progid;
								$database->setQuery($sql_pid);
								$prog_id = $database->loadResult();									
								
								if ($the_mail[0]->sendday == 1)
									$time_type = 3600*24;// day;
								elseif ($the_mail[0]->sendday == 2)
									$time_type = 3600; // hours;	
								elseif ($the_mail[0]->sendday == 3)
									$time_type = 3600*24*30;// months;
								elseif ($the_mail[0]->sendday == 3)
									$time_type = 3600*24*365;// years;
								
								$the_moment = time();
								
								$the_moment_final = $the_moment + $the_mail[0]->sendtime * $time_type;
								
								$sql = "INSERT INTO `#__guru_emails_pending` ( 
																						`sending_time` , 
																						`mail_id` , 
																						`mail_subj` , 
																						`mail_body`,
																						`user_id` ,
																						`pid`,
																						`type`
																			) VALUES ( 
																						'".$the_moment_final."', 
																						'".$mail_id."' , 
																						'".$subject."', 
																						'".$message."',
																						'".$my->id."',
																						'".$prog_id."',
																						'T'
																			)";
								$database->setQuery($sql);
								if (!$database->query() ){
									$this->setError($database->getErrorMsg());
									return false;
								}

							}				
					} // a delayed message with trigger - end
			}	
		
		return true;	
	}	

	function reset_program ($progid){
		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		$sql = "SELECT tasks, days FROM #__guru_programstatus WHERE userid = ".$my->id." AND pid = (SELECT pid FROM #__guru_days WHERE id= ".$progid.") ";
		$database->setQuery($sql);
		$result = $database->loadObjectList();
		
		$task_array = $result[0]->tasks;
		$day_array = $result[0]->days;
		
		$day_array_reset = str_replace(',2', ',0', $day_array);
		$task_array_reset = str_replace(',2', ',0', $task_array);

		$jnow 	= JFactory::getDate();
		$date 	= $jnow->toSQL();

		$sql = "UPDATE #__guru_programstatus SET 
													status='0',
													startdate='".$date."',
													enddate='0000-00-00 00:00:00',
													days = '".$day_array_reset."',
													tasks = '".$task_array_reset."',
													status=1
				WHERE pid =(SELECT pid FROM #__guru_days WHERE id= ".$progid.") AND userid =".$my->id;	
		$database->setQuery($sql);		
		$database->query();
	}
	
	
	function getlistPackages () { 
		$database = JFactory::getDBO();
		$sql = "select * from #__ad_agency_order_type";
		$database->setQuery($sql);
		$rows = $database->loadObjectList();
		return $rows;
	}	
	
	function find_day_status($dayid){
		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		$sql = "SELECT days FROM #__guru_programstatus WHERE userid = ".$my->id." AND pid = (SELECT pid FROM #__guru_days WHERE id= ".$dayid.") ";
		$database->setQuery($sql);
		$result = $database->loadResult();
		
		$status = 0;
		
		$day_array = explode(';', $result);
		foreach($day_array as $day_value)
			{
				$day_value = explode(',', $day_value);
				if($day_value[0]==$dayid)
					{
						$status = $day_value[1];
						break;
					}
			}
		return $status;	
	}

	function find_ids_for_skip_and_done_2($dayid, $dayord, $taskord) {
		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		$sql = "SELECT tasks FROM #__guru_programstatus WHERE userid = ".$my->id." AND pid = (SELECT pid FROM #__guru_days WHERE id = ".$dayid." ) ";
		$database->setQuery($sql);
		$result = $database->loadResult();
		
		$task_object = explode(';', $result);
		
		$task_array_object = $task_object[$dayord-1];
		
		$skip = 0;
		$done = 0;
		$the_order = 0;
		
		$task_array = explode('-', $task_array_object);
		
		if(isset($task_array[$taskord])){
				$skip = 1;}
		else {
				$skip = 0;}
		return $skip;
	}

	function getConfig(){
		$db =  JFactory::getDBO();
		$sql = "SELECT * FROM #__guru_config WHERE id = '1' ";
		$db->setQuery($sql);
		if (!$db->query()) {
			echo $db->stderr();
			return;
		}
		$config = $db->loadObject();
		return $config;
	}

	function parse_day_finnish_content($tasksarray) {
		$tasks_to_parse = '';
		$time_to_parse = 0;
		$points_to_parse = 0;

		$db =  JFactory::getDBO();
		foreach($tasksarray as $task)
		{
			$sql = "SELECT * FROM #__guru_task WHERE id = ".$task;
			$db->setQuery($sql);
			if (!$db->query()) {
				echo $db->stderr();
				return;
			}
			$returned_task = $db->loadObject();
			$tasks_to_parse = $tasks_to_parse.$returned_task->name.', ';
			$time_to_parse = $time_to_parse + $returned_task->time;
			$points_to_parse = $points_to_parse + $returned_task->points;
		} // foreach end	
		
		$tasks_to_parse = substr($tasks_to_parse, 0, strlen($tasks_to_parse)-2);
		$to_return = $tasks_to_parse.'$$$$$'.$time_to_parse.'$$$$$'.$points_to_parse;
		return $to_return;
	}


	function find_link_for_next_day($dayid, $dayord) {
		$my = JFactory::getUser();
		$database = JFactory::getDBO();
		$sql = "SELECT days, tasks FROM #__guru_programstatus WHERE userid = ".$my->id." AND pid = (SELECT pid FROM #__guru_days WHERE id = ".$dayid." ) ";
		$database->setQuery($sql);
		$result = $database->loadObject();
		
		$no_of_days = 0;
		$day_array = explode(';', $result->days);
		foreach($day_array as $day)
			if(isset($day) && $day>0)
				$no_of_days++;
		
		if($dayord == $no_of_days)	
			{
				// we are on the last day already - we go back to "My programs" - begin
				$link = 'index.php?option=com_guru&view=guruPrograms&task=myprograms';
				// we are on the last day already - we go back to "My programs" - end
			}
		else
			{
				// we find now the day and the task
				$day_ = explode(',',$day_array[$dayord]);
				$next_day_id = $day_[0];
				
				//$task_object = explode(';', $result->tasks);
				//$task_array_object = $task_object[$dayord];
				//$task_array = explode('-', $task_array_object);
				//$the_task = explode(',', $task_array[0]);
				//$next_task_id = $the_task[0];
				
				$link = 'index.php?option=com_guru&view=guruTasks&task=view&cid=1&pid='.$next_day_id;
				// we find now the day and the task
			}	
		return $link;
	}	

	function create_trial($userid, $progid) {
		$db = JFactory::getDBO();	
		
		$sql = "SELECT count(id) FROM #__guru_programstatus 
				WHERE pid = ".$progid." AND userid = ".$userid;
		$db->setQuery($sql);

		$result = $db->loadResult();
		
		if($result==0)
		// only if this program hasn't got a line in the status program - trial or paid - begin
		{	
		$sql = "SELECT id FROM #__guru_days 
				WHERE pid = ".$progid." ORDER BY ordering ASC";
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		$day_status = '';
		$task_status = '';
		foreach($result as $day)
			{$day_status = $day_status.$day->id.',0;'; 
						
			$sqltasks = "SELECT media_id FROM #__guru_mediarel 
						WHERE type_id = ".$day->id." AND type = 'dtask' ";
			$db->setQuery($sqltasks);
			$resultt = $db->loadObjectList();
			foreach($resultt as $task)
					{$task_status = $task_status.$task->media_id.',0-';}
								
					$task_status = $task_status.';';		
					}
				
			$sqlins = "INSERT INTO #__guru_programstatus 
						( 
						`userid` , 
						`pid` , 
						`days` , 
						`tasks` , 
						`status` )
				VALUES (
						'".$userid."', 
						'".$progid."', 
						'".$day_status."', 
						'".$task_status."', 
						'0'
					);";
			$db->setQuery($sqlins);		
			$db->Query();		
			
		$sqlorder = "SELECT max(oid) FROM #__guru_order";
		$db->setQuery($sqlorder);
		$maxoid = $db->loadResult();	
		
		if(!isset($maxoid))
			$maxoid = 999;
		
			$sqlins = "INSERT INTO #__guru_order 
						( 
						`oid` , 
						`userid` , 
						`programid` , 
						`date` , 
						`payment`,
						`published` )
				VALUES (
						'".($maxoid+1)."', 
						'".$userid."', 
						'".$progid."',
						now(), 
						'Trial', 
						'1'
					);";
			$db->setQuery($sqlins);		
			$db->Query();		
		} // only if this program hasn't got a line in the status program - trial or paid - end
			
	}	
	
	function find_if_program_was_bought($userid, $progid) {
		$db = JFactory::getDBO();	
		
		$sql = "SELECT payment FROM #__guru_order
				WHERE programid = ".$progid." AND userid = ".$userid;
		$db->setQuery($sql);
		$result = $db->loadResult();
		
		return strtolower($result);
		}	
		
	function generate_quiz($qid){
		$db = JFactory::getDBO();	
		
		$sql = "SELECT q.description, q.image, quest.* FROM #__guru_quiz as q
				LEFT JOIN #__guru_questions as quest on q.id = quest.qid
				WHERE q.id = ".$qid." AND q.published = 1";
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		
		$media = '';
		
		foreach($result as $rez)
			{
				$media = $media.'<b>'.$rez->text.'</b><br />';
				for($i=1; $i<=10; $i++)
					{
						$answer = 'a'.$i;
						if($rez->$answer != '')
							{
								$media = $media.'<input name="'.$answer.'" type="radio" value="'.$i.'a">'.$rez->$answer.'</input><br />';
							}
					}
				$media = $media.'<br>';	
			}	
		return $media;	
	}	
	
	function getExercise(){
		$my=JFactory::getUser();
		$db = JFactory::getDBO();
		$config= $this->getConfig();
		$exercise = JRequest::getVar("id","0");
		$course	  = JRequest::getVar("pid","0");
		$sql="SELECT lmr.access, lm.*
			  FROM #__guru_mediarel lmr
			  LEFT JOIN #__guru_media lm
			  ON lmr.media_id=lm.id
			  WHERE lmr.type_id=".$course." AND lmr.media_id=".$exercise." AND lmr.type='pmed'";
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadObject();
		
		if($result->access==2 || ($result->access<2 && $my->id > 0)){		
			return $result;
		}	
		else{
		 	return false;
		}	
	}	
	
	function getSteps($author_id){
		$db= JFactory::getDBO();		
		$jnow 	= JFactory::getDate();
		$date 	= $jnow->toSQL();
	
		$sql = "SELECT lt.*
			FROM `#__guru_task` as lt, `#__guru_program` as lp
			LEFT JOIN `#__guru_days` as ld on lt.id=ld.pid  
			LEFT JOIN `#__guru_mediarel` as lm on ld.id=lm.type_id 						
			WHERE lp.author=".$author_id."  
			AND lp.published=1 
			AND lp.startpublish<='".$date."' 
			AND (lp.endpublish>'".$date."' or lp.endpublish='0000-00-00 00:00:00')
			AND lt.id=lm.media_id
			AND lp.id=lt.id
			GROUP BY lp.id";
		$db->setQuery($sql);
		$db->query();
		$steps = $db->loadObjectList();
		
		return $steps;
	}
	
	function getSkipAction($course_id){
		$db = JFactory::getDBO();
		$sql = "select skip_module from #__guru_program where id=".intval($course_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}
	
	function getViewLesson($lesson_id){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id = $user->id;
		$sql = "select count(*) from #__guru_viewed_lesson where user_id=".intval($user_id)." and lesson_id like '%|".$lesson_id."|%'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if($result > 0){
			return true;	
		}
		return false;
	}
	//$credit_val = 0 ADDED BY JOSEPH 08/04/2015
	function saveLessonViewed($step_id,$pid,$credit_val = 0){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$id = $user->id;
		$config = JFactory::getConfig();
		$offset = JFactory::getApplication()->getCfg('offset');
		$jnow = JFactory::getDate('now', $offset);
		$date_last_visit = $jnow;
		$module_id = intval(JRequest::getVar('module','0'));
		if($id != 0 && $pid !=""){
			$sql = "select count(*) from #__guru_viewed_lesson where user_id=".intval($id)." and pid=".$pid;
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadResult();
			if($count == 0){
				$sql  = "insert into #__guru_viewed_lesson (`user_id`, `lesson_id`, module_id, date_last_visit, pid) values ";
				$sql .= "(".$id.", '|".$step_id."|', '|".$module_id."|', '".$date_last_visit."' ,'".$pid."')";			
				$db->setQuery($sql);
				$db->query();
				//ADDED BY JOSEPH 07/04/2015
						$sql = 'update #__guru_customer set `credit_total` = `credit_total` +'.intval($credit_val).' where `id`='.intval($id);
						$db->setQuery($sql);
						$db->query();
				//END
				//ADDED BY JOSEPH 09/04/2015
						$sql  = "insert into #__qikey_completed_lesson (user_id, course_id, module_id, lesson_id, completed_date) values ";
						$sql .= "(".$id.", ".$pid.", ".$module_id.", ".$step_id." ,'".$date_last_visit."')";			
						$db->setQuery($sql);
						$db->query();
				//END
				$sql = 'update #__guru_viewed_lesson set module_id = "|'.$module_id.'|" WHERE pid='.$pid;
			}
			else{
				$sql = "select count(*) from #__guru_viewed_lesson where user_id=".intval($id)." and lesson_id like '%|".$step_id."|%' and pid=".$pid;
				$db->setQuery($sql);
				$db->query();
				$count = $db->loadResult();
				if($count == 0){
					$sql = 'update #__guru_viewed_lesson set lesson_id = CONCAT(lesson_id, "|'.$step_id.'|"), module_id = "|'.$module_id.'|", date_last_visit = "'.$date_last_visit.'"  where user_id='.intval($id)." and pid=".$pid;
					$db->setQuery($sql);
					$db->query();
					//ADDED BY JOSEPH 07/04/2015
						$sql = 'update #__guru_customer set `credit_total` = `credit_total` +'.intval($credit_val).' where `id`='.intval($id);
						$db->setQuery($sql);
						$db->query();
					//END
					//ADDED BY JOSEPH 09/04/2015
						$sql  = "insert into #__qikey_completed_lesson (user_id, course_id, module_id, lesson_id, completed_date) values ";
						$sql .= "(".$id.", ".$pid.", ".$module_id.", ".$step_id." ,'".$date_last_visit."')";			
						$db->setQuery($sql);
						$db->query();
					//END
				}
				else{
					$sql = "SELECT `lesson_id` from #__guru_viewed_lesson WHERE `user_id` =".intval($id)." and lesson_id like '%|".$step_id."|%' and pid=".$pid;
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadResult();
					$result_lesson = explode('||', trim($result, "||"));
					foreach($result_lesson as $key=>$value){
						if($step_id == $value){
							unset($result_lesson[$key]);
							$result_lesson[] = $step_id;
							break;
						}
					}
					$result_lesson = implode("||", $result_lesson);
					$result_lesson = "|".$result_lesson."|";
					$sql = 'update #__guru_viewed_lesson set lesson_id ="'.$result_lesson.'", date_last_visit = "'.$date_last_visit.'" where user_id='.intval($id).' and pid='.intval($pid);
					$db->setQuery($sql);
					$db->query();
				}
				$sql = 'update #__guru_viewed_lesson set module_id = "|'.$module_id.'|" where user_id='.intval($id)." and pid=".$pid;
				$db->setQuery($sql);
				$db->query();
			}
			
				$sql = "SELECT `lesson_id` from #__guru_viewed_lesson WHERE `user_id` =".intval($id)." and pid=".$pid;
				$db->setQuery($sql);
				$db->query();
				$result1 = $db->loadResult();
				$result1 = explode('||', trim($result1, "||"));
				
				$sql = "SELECT `completed` from #__guru_viewed_lesson WHERE `user_id` =".intval($id)." and pid=".intval($pid);
				$db->setQuery($sql);
				$db->query();
				$completed = $db->loadResult();
				
				$sql ="SELECT id FROM #__guru_task WHERE id IN (SELECT media_id FROM #__guru_mediarel WHERE type = 'dtask' AND type_id in (SELECT id FROM #__guru_days WHERE pid =".$pid.") ) ";
				$db->setQuery($sql);
				$db->query();
				$result2 = $db->loadColumn();
				
				@$intersect = array_intersect($result1, $result2);
				
				//echo "<pre>"; print_r($result1); print_r($result2); die();
				
				if(count($intersect) == count($result2)){
					$date = date('Y-m-d');
					if($completed != 1){
						$sql = 'update #__guru_viewed_lesson set `completed` = "1", `date_completed` = "'.$date.'" where user_id='.intval($id)." and pid=".intval($pid);
						$db->setQuery($sql);
						$db->query();
					}
				}
				else{
					if($completed != 1){
						$sql = 'update #__guru_viewed_lesson set `completed` = "0" where user_id='.intval($id)." and pid=".intval($pid);
						$db->setQuery($sql);
						$db->query();
					}
				}
		}
	}
	
	function emailCertificate($pid){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$id = $user->id;
		$config = JFactory::getConfig();
		include(JPATH_SITE.DS.'components'.DS.'com_guru'.DS.'models'.DS.'gurubuy.php');
		@$guru_configs = guruModelguruBuy::getConfigs();
		$sql = "SELECT `name` from #__guru_program WHERE `id` =".$pid;
		$db->setQuery($sql);
		$db->query();
		$resultcn = $db->loadResult();



		$imagename = "SELECT * FROM #__guru_certificates WHERE id=1";
		$db->setQuery($imagename);
		$db->query();
		$imagename = $db->loadAssocList();



		$date_completed = "SELECT datecertificate FROM #__guru_mycertificates WHERE user_id=".intval($id)." and course_id=".$pid;
		$db->setQuery($date_completed);
		$db->query();
		$date_completed = $db->loadResult();

		$format = "SELECT datetype FROM #__guru_config WHERE id=1";
		$db->setQuery($format);
		$db->query();
		$format = $db->loadResult();
		
		$date_completed = date($format, strtotime($date_completed));

		$completiondate = $date_completed;
		$sitename = $config->get( 'config.sitename');
		$coursename = $resultcn;



		$firstname = "SELECT firstname, lastname FROM #__guru_customer WHERE id=".intval($id);
		$db->setQuery($firstname);
		$db->query();
		$firstname = $db->loadAssocList();
		
		$email = "SELECT email FROM #__users WHERE id=".intval($id);
		$db->setQuery($email);
		$db->query();
		$email = $db->loadResult();

		$imagename[0]["templates3"]  = str_replace("[SITENAME]", $sitename, $imagename[0]["templates3"]);
		$imagename[0]["templates3"]  = str_replace("[STUDENT_FIRST_NAME]", $firstname[0]["firstname"], $imagename[0]["templates3"]);
		$imagename[0]["templates3"]  = str_replace("[COMPLETION_DATE]", $completiondate, $imagename[0]["templates3"]);
		$imagename[0]["templates3"]  = str_replace("[COURSE_NAME]", $coursename, $imagename[0]["templates3"]);


		
		if(isset($guru_configs["0"]["fromname"]) && trim($guru_configs["0"]["fromname"]) != ""){
			$fromname = trim($guru_configs["0"]["fromname"]);
		}
		if(isset($guru_configs["0"]["fromemail"]) && trim($guru_configs["0"]["fromemail"]) != ""){
			$from = trim($guru_configs["0"]["fromemail"]);
		}
		
		
		$email_body	= $imagename[0]["templates3"];
		
		$recipient = $email;
		$mode = true;
		
		$imagename[0]["subjectt3"]  = str_replace("[SITENAME]", $sitename, $imagename[0]["subjectt3"]);
		$imagename[0]["subjectt3"]  = str_replace("[STUDENT_FIRST_NAME]", $firstname[0]["firstname"], $imagename[0]["subjectt3"]);
		$imagename[0]["subjectt3"]  = str_replace("[STUDENT_LAST_NAME]", $firstname[0]["lastname"], $imagename[0]["subjectt3"]);
		
		$subject_procesed = $imagename[0]["subjectt3"];
		$body_procesed = $email_body;
	
		
		$email_sent = "SELECT emailcert FROM #__guru_mycertificates WHERE user_id=".intval($id)." and course_id=".$pid;
		$db->setQuery($email_sent);
		$db->query();
		$email_sent = $db->loadResult();
		if($email_sent == 0){
			JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject_procesed, $body_procesed, $mode);
			
			$email_sentok = "UPDATE #__guru_mycertificates set emailcert=1 where user_id=".intval($id)." and course_id=".$pid;
			$db->setQuery($email_sentok);
			$db->query();
		}
	
	}
	
	function InsertMyCertificateDetails($pid){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$jnow = JFactory::getDate();
		$id = $user->id;
		$sql = "SELECT count(id) from #__guru_mycertificates WHERE `user_id` =".intval($id)." and course_id=".intval($pid);
		$db->setQuery($sql);
		$db->query();
		$count_cert = $db->loadResult();
		
		$current_date_cert = $jnow->toSQL();

		if($count_cert == 0){
			$author_id = "SELECT `author` from #__guru_program WHERE `id` =".intval($pid);
			$db->setQuery($author_id);
			$db->query();
			$resultauth = $db->loadResult();

			$sql = 'insert into  #__guru_mycertificates (`course_id`, `author_id`, `user_id`, `emailcert`, `datecertificate` ) values ("'.intval($pid).'", "'.intval($resultauth).'", "'.intval($id).'", 0, "'.$current_date_cert.'")';
			$db->setQuery($sql);
			$db->query();	
		}
	
	}
	
	function getStepAccessCourses(){
		$course	  = JRequest::getVar("catid","0");
		$program = $this->get('Program');
		$db = JFactory::getDBO();
		$sql = "SELECT step_access_courses  FROM `#__guru_program` where id = ".$course;
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadResult();
		return $result;	
	}
	function getChbAccessCourses(){
		$course	  = JRequest::getVar("catid","0");
		$program = $this->get('Program');
		$db = JFactory::getDBO();
		$sql = "SELECT chb_free_courses  FROM `#__guru_program` where id = ".$course;
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadResult();
		return $result;	
	}
	function getDataStepAccessCourses($course_id){
		$program = $this->get('Program');
		$db = JFactory::getDBO();
		$sql = "SELECT step_access_courses  FROM `#__guru_program` where id = ".$course_id;
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadResult();
		return $result;	
	
	
	}
	function getDataChbAccessCourses($course_id){
		$program = $this->get('Program');
		$db = JFactory::getDBO();
		$sql = "SELECT chb_free_courses  FROM `#__guru_program` where id = ".$course_id;
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadResult();
		return $result;	
	
	}
	function getCertificate(){
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__guru_certificates
				WHERE id = '1'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	function getCertificateTerm($id){
		$db = JFactory::getDBO();
		$sql = "SELECT certificate_term  FROM #__guru_program
				WHERE id =".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}
	function getAvgScoresQ($uid,$pid){
		$db = JFactory::getDBO();
		$s = 0;
		$sql = "SELECT id_final_exam  FROM #__guru_program
		WHERE id =".intval($pid);
		$db->setQuery($sql);
		$db->query();
		$id_final_exam = $db->loadColumn();
		
		$sql = "SELECT hasquiz  FROM #__guru_program
		WHERE id =".intval($pid);
		$db->setQuery($sql);
		$db->query();
		$nb_ofscores = $db->loadColumn();
		$nb_ofscores = @$nb_ofscores[0];
		
		$sql = "SELECT distinct quiz_id  FROM #__guru_quiz_taken
		WHERE user_id =".intval($uid)." and pid =".intval($pid)." and quiz_id <> ".intval(@$id_final_exam["0"]);
		$db->setQuery($sql);
		$db->query();
		$quiz_id = $db->loadAssocList();
		
		for($i=0; $i<count($quiz_id); $i++){
			if($id_final_exam !=0){
				$sql = "SELECT score_quiz  FROM #__guru_quiz_taken
					WHERE user_id =".intval($uid)." and pid =".intval($pid)." and quiz_id = ".$quiz_id[$i]["quiz_id"]." order by id desc limit 0,1";
			}
			else{
				$sql = "SELECT score_quiz  FROM #__guru_quiz_taken
					WHERE user_id =".intval($uid)." and pid =".intval($pid). " order by id desc limit 0,1";
			}
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadObjectList();
			
	
			
			foreach($result as $key=>$value){
				$score = $value->score_quiz;
				if($score !=""){
					$score = explode("|",$score );
					$how_many_right_answers = $score[0];
					$number_of_questions = $score[1];	
					if($how_many_right_answers == 0){
						$score = 0;
					}
					else{
						$score = intval(($how_many_right_answers/$number_of_questions)*100);
					}
					$s +=$score;
				}
	
			}
		}
		if($nb_ofscores != 0){
			$result_score = intval($s / $nb_ofscores);
		}
		return @$result_score;
	
	
	}
	function getIsQuizOrNot($lid){
		$db = JFactory::getDBO();
		$sql = "SELECT type  FROM #__guru_media
				WHERE id =(SELECT media_id from #__guru_mediarel where type_id=".$lid." LIMIT 1)";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return @$result[0];
	}
	function studFailedQuiz($lid){
		$db = JFactory::getDBO();
		$sql = "SELECT student_failed_quiz  FROM #__guru_quiz WHERE id =(SELECT media_id FROM #__guru_mediarel WHERE type='scr_m' and type_id=".intval($lid)." LIMIT 1)";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return @$result[0];
	}
	function getIsFinal($id){
		$db = JFactory::getDBO();
		$sql = "SELECT is_final  FROM #__guru_quiz WHERE id =(SELECT media_id FROM #__guru_mediarel WHERE type='scr_m' and type_id=".intval($id)." LIMIT 1)";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return @$result[0];
	}

	function createTimer($quiz_id){
		$db = JFactory::getDBO();
		$sql = "SELECT  qct_alignment, qct_border_color, qct_minsec, qct_title_color, qct_bg_color, qct_font , qct_width,  qct_height, qct_font_nb, qct_font_words  FROM  #__guru_config WHERE id=1";
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadObjectList();
	
		if($result[0]->qct_alignment ==1){
			$align = "left";
		}
		elseif($result[0]->qct_alignment==2){
			$align = "right";
		}
		elseif($result[0]->qct_alignment ==3){
			$align = "center";
		}
	
		$return = '<div align ='.$align.'><span>
		<div style="width:'.$result[0]->qct_width.'px; height:'.$result[0]->qct_height.'px; border: 1px solid; border-color:'.'#'.$result[0]->qct_border_color.'; font-family:'.$result[0]->qct_font.'; background-color:'.'#'.$result[0]->qct_bg_color.';">
                <div align="center" style="border-bottom:1px '.'#'.$result[0]->qct_border_color.'solid; font-size:'.$result[0]->qct_font_words.'px; color:'.'#'.$result[0]->qct_title_color.'; background-color:'.'#'.$result[0]->qct_border_color.';">'.JText::_("GURU_TIMEPROMO").'</div>
          <div id="totalbg" style="background-color:'.'#'.$result[0]->qct_bg_color.';">
		  	<div align="center" id="ijoomlaguru_time" style="font-size:'.$result[0]->qct_font_nb.'px; border-color:'.'#'.$result[0]->qct_border_color.'; color:'.'#'.$result[0]->qct_minsec.'; padding-top:10px;"></div>
            <div align="center" style="font-size:'.$result[0]->qct_font_words.'px;">'.JText::_("GURU_PROGRAM_DETAILS_MINUTES").'  '.JText::_("GURU_PROGRAM_DETAILS_SECONDS") .'</div>
       	 </div>
		</div> 
		</span></div>';
		

		return $return;
	}
	
	function get_time_difference($start, $end){
    $uts['start'] = $start;
    $uts['end'] = $end;
    if( $uts['start'] !== -1 && $uts['end'] !== -1){
        if($uts['end'] >= $uts['start']){
            $diff = $uts['end'] - $uts['start'];
            if($days=intval((floor($diff/86400)))){
                $diff = $diff % 86400;
			}
				
            if($hours=intval((floor($diff/3600)))){
                $diff = $diff % 3600;
			}	
            
			if($minutes=intval((floor($diff/60)))){
                $diff = $diff % 60;
			}	
            $diff = intval($diff);
            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff));
        }
		else{
			return false;
		}
    }
    return false;
}

	function isLastPassedQuiz($course_id){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id = $user->id;
		
		$sql = "SELECT `certificate_term` from #__guru_program WHERE `id`=".intval($course_id);
		$db->setQuery($sql);
		$db->query();
		$certificate_term = $db->loadColumn();
		$certificate_term = @$certificate_term["0"];
		
		if($certificate_term == 1){// no certificate
			return false;
		}
		
		if($certificate_term == 2){// Complete all the lessons
			$sql = "select `completed` from #__guru_viewed_lesson WHERE `user_id`=".intval($user_id)." and `pid`=".intval($course_id);
			$db->setQuery($sql);
			$db->query();
			$completed = $db->loadColumn();
			$completed = @$completed["0"];

			if($completed == 1){
				return true;
			}
			return false;
		}
		
		if($certificate_term == 3){// Pass the final exam
			$sql = "select `id_final_exam` from #__guru_program where `id`=".intval($course_id);
			$db->setQuery($sql);
			$db->query();
			$id_final_exam = $db->loadColumn();
			$id_final_exam = @$id_final_exam["0"];
			
			if(intval($id_final_exam) > 0){
				$sql = "select `score_quiz` from #__guru_quiz_taken where `user_id`=".intval($user_id)." and `pid`=".intval($course_id)." and `quiz_id`=".intval($id_final_exam);
				$db->setQuery($sql);
				$db->query();
				$score_quiz = $db->loadColumn();
				$score_quiz = @$score_quiz["0"];
				
				if(isset($score_quiz) && trim($score_quiz) != ""){
					$sql = "select `max_score` from #__guru_quiz where `id`=".intval($id_final_exam);
					$db->setQuery($sql);
					$db->query();
					$max_score = $db->loadColumn();
					$max_score = @$max_score["0"];
					
					$score_quiz_array = explode("|", $score_quiz);
					$correct = $score_quiz_array["0"];
					$total = $score_quiz_array["1"];
					
					$percent = @($correct * 100) / @$total;
					
					if($percent >= $max_score){
						return true;
					}
					return false;
				}
				return false;
			}
			return false;
		}
		
		if($certificate_term == 4){// Pass the quizzes in avg of...
			$sql = "SELECT * FROM `#__guru_quiz_taken` WHERE `id` in (select max(`id`) from #__guru_quiz_taken where `user_id`=".intval($user_id)." and `pid`=".intval($course_id)." group by `quiz_id`)";
			$db->setQuery($sql);
			$db->query();
			$all_quizes = $db->loadAssocList();
			
			$sql = "select `avg_certc` from #__guru_program where `id`=".intval($course_id);
			$db->setQuery($sql);
			$db->query();
			$avg_certc = $db->loadColumn();
			$avg_certc = @$avg_certc["0"];
			
			$sql = "select `hasquiz` from #__guru_program where `id`=".intval($course_id);
			$db->setQuery($sql);
			$db->query();
			$all_quizes_from_course = $db->loadColumn();
			$all_quizes_from_course = @$all_quizes_from_course["0"];
			
			if(isset($all_quizes) && count($all_quizes) > 0){
				$percent = 0;
				foreach($all_quizes as $key=>$value){
					$score_quiz = $value["score_quiz"];
					$score_quiz_array = explode("|", $score_quiz);
					$correct = $score_quiz_array["0"];
					$total = $score_quiz_array["1"];
					
					$percent += ($correct * 100) / $total;
				}

				$total_percent = $percent / $all_quizes_from_course;
				
				if($total_percent >= $avg_certc){
					return true;
				}
				return false;
			}
			return false;
		}
		
		if($certificate_term == 5){// Finish all lessons and pass final exam
			$sql = "select `completed` from #__guru_viewed_lesson WHERE `user_id`=".intval($user_id)." and `pid`=".intval($course_id);
			$db->setQuery($sql);
			$db->query();
			$completed = $db->loadColumn();
			$completed = @$completed["0"];
			
			if($completed == 0){
				return false;
			}
			
			$sql = "select `id_final_exam` from #__guru_program where `id`=".intval($course_id);
			$db->setQuery($sql);
			$db->query();
			$id_final_exam = $db->loadColumn();
			$id_final_exam = @$id_final_exam["0"];
			
			if(intval($id_final_exam) > 0){
				$sql = "select `score_quiz` from #__guru_quiz_taken where `user_id`=".intval($user_id)." and `pid`=".intval($course_id)." and `quiz_id`=".intval($id_final_exam);
				$db->setQuery($sql);
				$db->query();
				$score_quiz = $db->loadColumn();
				$score_quiz = @$score_quiz["0"];
				
				if(isset($score_quiz) && trim($score_quiz) != ""){
					$sql = "select `max_score` from #__guru_quiz where `id`=".intval($id_final_exam);
					$db->setQuery($sql);
					$db->query();
					$max_score = $db->loadColumn();
					$max_score = @$max_score["0"];
					
					$score_quiz_array = explode("|", $score_quiz);
					$correct = $score_quiz_array["0"];
					$total = $score_quiz_array["1"];
					
					$percent = ($correct * 100) / $total;
					
					if($percent >= $max_score){
						return true;
					}
					return false;
				}
				return false;
			}
			return false;
		}
		
		if($certificate_term == 6){// Finish all lessons and pass quizzes in avg of...
			$sql = "select `completed` from #__guru_viewed_lesson WHERE `user_id`=".intval($user_id)." and `pid`=".intval($course_id);
			$db->setQuery($sql);
			$db->query();

			$completed = $db->loadColumn();
			$completed = @$completed["0"];
			
			if($completed == 0){
				return false;
			}
			
			$sql = "SELECT * FROM `#__guru_quiz_taken` WHERE `id` in (select max(`id`) from #__guru_quiz_taken where `user_id`=".intval($user_id)." and `pid`=".intval($course_id)." group by `quiz_id`)";
			$db->setQuery($sql);
			$db->query();
			$all_quizes = $db->loadAssocList();
			
			$sql = "select `hasquiz` from #__guru_program where `id`=".intval($course_id);
			$db->setQuery($sql);
			$db->query();
			$all_quizes_from_course = $db->loadColumn();
			$all_quizes_from_course = @$all_quizes_from_course["0"];
			
			$sql = "select `avg_certc` from #__guru_program where `id`=".intval($course_id);
			$db->setQuery($sql);
			$db->query();
			$avg_certc = $db->loadColumn();
			$avg_certc = @$avg_certc["0"];
			
			if(isset($all_quizes) && count($all_quizes) > 0){
				$percent = 0;
				foreach($all_quizes as $key=>$value){
					$score_quiz = $value["score_quiz"];
					$score_quiz_array = explode("|", $score_quiz);
					$correct = $score_quiz_array["0"];
					$total = $score_quiz_array["1"];
					
					$percent += ($correct * 100) / $total;
				}
				$total_percent = $percent / $all_quizes_from_course;
				
				if($total_percent >= $avg_certc){
					return true;
				}
				return false;
			}
			return false;
		}
		
		return false;
	}
	
	function createPendingQuiz($quiz_id,$all_questions_ids){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id = $user->id;
		$date = date('Y-m-d h:i:s');
		$max_id = NULL;
		
		$sql = "SELECT `time_quiz_taken` FROM #__guru_quiz WHERE `id`=".intval($quiz_id);
		$db->setQuery($sql);
		$resultt = $db->loadColumn();
		$resultt = $resultt["0"];
		
		$sql = "SELECT count(`user_id`) FROM #__guru_quiz_taken WHERE `user_id`=".intval($user_id)." and `quiz_id`=".intval($quiz_id);
		$db->setQuery($sql);
		$resultu = $db->loadColumn();
		$iterator = 1;
		
		if($resultt < 11){
			if(intval($resultu["0"]) != 0){
				$iterator = intval($resultu["0"]) + 1;
			}
		}
		else{
			$iterator = 11;
		}
		
		$sql = "insert into #__guru_quiz_taken (`user_id`, `quiz_id`, `score_quiz`, `date_taken_quiz`, `pid`, `time_quiz_taken_per_user`) values (".intval($user_id).", ".intval($quiz_id).", '".$score_quiz."', '".$date."', '', ".intval($iterator).")";
		$db->setQuery($sql);
		if($db->query()){
			$sql = "select max(`id`) from #__guru_quiz_taken";
			$db->setQuery($sql);
			$db->query();
			$max_id = $db->loadColumn();
			$max_id = $max_id["0"];
			
			$all_questions_ids_array = explode(",", $all_questions_ids);
			$sql = "INSERT INTO #__guru_quiz_question_taken (`user_id`, `show_result_quiz_id`, `answers_gived`,`question_id`, `question_order_no`) VALUES";			
			foreach($all_questions_ids_array as $key=>$q_id){
				$q_id = intval($q_id);
				if($q_id != 0){
					 $sql .= "('".intval($user_id)."', '".$max_id."', '', '".intval($q_id)."', '".($key +1)."'),";
					
				}
			}	
			$db->setQuery(substr($sql, 0, strlen($sql)  - 1));
			$db->query();
		}
	
	}
	
	function getResultQuizzes($quiz_id, $course_id, $number_of_questions){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id = $user->id;
		$date = date('Y-m-d h:i:s');
		$quiz_form_content = "";
		$your_score_text = JText::_("GURU_YOUR_SCORE");
		
		$id_quiz_question_row = JRequest::getVar("id_quiz_question",""); 
		
		$q  = "SELECT max_score, is_final FROM #__guru_quiz WHERE id = ".intval($quiz_id);
		$db->setQuery($q);
		$quiz_details = $db->loadAssocList();
		
		$sql = "SELECT `time_quiz_taken` FROM #__guru_quiz WHERE `id`=".intval($quiz_id);
		$db->setQuery($sql);
		$resultt = $db->loadColumn();
		$resultt = @$resultt["0"];
		
		$quiz_form_content .= '<div id="the_quiz">';      
        $quiz_form_content .= '<ul class="guru_list">';
		
		
		$all_questions_ids = JRequest::getVar("list_questions_id","");
		if($all_questions_ids == ""){
			return true;
		}
		if(@$quiz_details["0"]["is_final"] == 0){
			//$sql = "SELECT a.`id`, a.`answers`, a.`text`, a.`a1`,a.`a2`, a.`a3`, a.`a4`, a.`a5`,a.`a6`, a.`a7`, a.`a8`, a.`a9`, a.`a10` FROM `#__guru_questions` a INNER JOIN `#__guru_quiz_question_taken` b  ON  (a.id = b.question_id ) WHERE a.qid=".intval($quiz_id)." and  b.show_result_quiz_id =".intval($id_quiz_question_row)." order by question_order_no";
			$sql = "SELECT `id`,`answers`, `text`, `a1`, `a2`, `a3`, `a4`, `a5`, `a6`, `a7`, `a8`, `a9`, `a10` FROM `#__guru_questions` WHERE `qid`=".intval($quiz_id)." and `id` IN (".$all_questions_ids.")";
		}
		else{
			//$sql = "SELECT a.`id`, a.`answers`, a.`text`, a.`a1`,a.`a2`, a.`a3`, a.`a4`, a.`a5`,a.`a6`, a.`a7`, a.`a8`, a.`a9`, a.`a10` FROM `#__guru_questions` a INNER JOIN `#__guru_quiz_question_taken` b  ON  (a.id = b.question_id ) WHERE b.show_result_quiz_id =".intval($id_quiz_question_row)." order by question_order_no"
			$sql = "SELECT `id`,`answers`, `text`, `a1`, `a2`, `a3`, `a4`, `a5`, `a6`, `a7`, `a8`, `a9`, `a10` FROM `#__guru_questions` WHERE  `id` IN (".$all_questions_ids.")";
		}
		$db->setQuery($sql);
		$db->query();
		$questions_answers = $db->loadAssocList("id");
		
		
		$total_questions = JRequest::getVar("nb_of_questions", "0");
		$total_correct_answers = 0;
		
		
		if($all_questions_ids != ""){
			$all_questions_ids_array = explode(",", $all_questions_ids);

			foreach($all_questions_ids_array as $key=>$q_id){
				$q_id = intval($q_id);
				$checked_answers = JRequest::getVar("q".$q_id, array(), "post", "array");
				if(count($checked_answers) == 0){
					$checked_answers = array("0"=>"");
				}
				if(is_array($checked_answers) && count($checked_answers) > 0){
					$correct_answers = $questions_answers[$q_id]["answers"];
					$correct_answers = array_filter(explode("|||", $correct_answers));
					
					$all_answers = array();
					$all_answers_text = array();
					
					if(trim($questions_answers[$q_id]["a1"]) != ""){
						$all_answers[] = "1a"; 
						$all_answers_text[] = trim(str_replace("'", "&acute;", $questions_answers[$q_id]["a1"]));
					}
					if(trim($questions_answers[$q_id]["a2"]) != ""){
						$all_answers[] = "2a";
						$all_answers_text[] = trim(str_replace("'", "&acute;", $questions_answers[$q_id]["a2"]));
					}
					if(trim($questions_answers[$q_id]["a3"]) != ""){
						$all_answers[] = "3a"; 
						$all_answers_text[] = trim(str_replace("'", "&acute;", $questions_answers[$q_id]["a3"]));
					}
					if(trim($questions_answers[$q_id]["a4"]) != ""){
						$all_answers[] = "4a"; 
						$all_answers_text[] = trim(str_replace("'", "&acute;", $questions_answers[$q_id]["a4"]));
					}			
					if(trim($questions_answers[$q_id]["a5"]) != ""){
						$all_answers[] = "5a"; 
						$all_answers_text[] = trim(str_replace("'", "&acute;", $questions_answers[$q_id]["a5"]));
					}				
					if(trim($questions_answers[$q_id]["a6"]) != ""){
						$all_answers[] = "6a";
						$all_answers_text[] = trim(str_replace("'", "&acute;", $questions_answers[$q_id]["a6"]));
					}
					if(trim($questions_answers[$q_id]["a7"]) != ""){
						$all_answers[] = "7a";
						$all_answers_text[] = trim(str_replace("'", "&acute;", $questions_answers[$q_id]["a7"]));
					}				
					if(trim($questions_answers[$q_id]["a8"]) != ""){
						$all_answers[] = "8a";
						$all_answers_text[] = trim(str_replace("'", "&acute;", $questions_answers[$q_id]["a8"]));
					}				
					if(trim($questions_answers[$q_id]["a9"]) != ""){
						$all_answers[] = "9a"; 
						$all_answers_text[] = trim(str_replace("'", "&acute;", $questions_answers[$q_id]["a9"]));
					}				
					if(trim($questions_answers[$q_id]["a10"]) != ""){
						$all_answers[] = "10a";
						$all_answers_text[] = trim(str_replace("'", "&acute;", $questions_answers[$q_id]["a10"]));
					}
					
					$all_answers = implode("|||", $all_answers);
					$all_answers_text = implode("|||", $all_answers_text);
					
					if(count(array_intersect($correct_answers, $checked_answers)) == count($correct_answers) && count($checked_answers) == count($correct_answers)) {
						$total_correct_answers ++;
						$quiz_form_content .= '<li class="question right">'.($key+1).'. '.$questions_answers[$q_id]["text"].'</li>';
					}
					else {
						$quiz_form_content .= '<li class="question wrong g_quize_q">'.($key+1).'. '.$questions_answers[$q_id]["text"].'</li>';
					}
					
					$all_answers_array = explode("|||", $all_answers);
					$all_answers_array_text = explode("|||", $all_answers_text);
					
					for($j=0; $j<count($all_answers_array); $j++){
						if(in_array($all_answers_array[$j], $correct_answers)){
							$quiz_form_content .= '<li class="correct">'.$all_answers_array_text[$j].'</li>';
						}
						else{
							$quiz_form_content .= '<li class="incorrect">'.$all_answers_array_text[$j].'</li>';
						}
					}
				}
			} // end foreach
			
			$score_quiz = $total_correct_answers."|".$number_of_questions;
			$score = intval(@($total_correct_answers/$number_of_questions)*100);

			
			
			
			$sql = "SELECT count(`user_id`) FROM #__guru_quiz_taken WHERE `user_id`=".intval($user_id)." and `quiz_id`=".intval($quiz_id)." and pid=".intval($course_id);
			$db->setQuery($sql);
			$resultu = $db->loadColumn();
			
			$sql = "SELECT time_quiz_taken_per_user FROM #__guru_quiz_taken WHERE `user_id`=".intval($user_id)." and `quiz_id`=".intval($quiz_id)." and pid=".intval($course_id)." ORDER BY id DESC LIMIT 0,1";
			$db->setQuery($sql);
			$last_time_quiz_taken_per_user = $db->loadColumn();
			$last_time_quiz_taken_per_user = $last_time_quiz_taken_per_user["0"];
			
			$iterator = 1;
			
			if($resultt < 11){
				if($last_time_quiz_taken_per_user < $resultt){
					if(intval($resultu["0"]) != 0 && $last_time_quiz_taken_per_user < 11){
						$iterator = intval($resultu["0"]) + 1;
					}
					else{
						$iterator = 1;
					}
				}
				else{
					$iterator = $resultt;
				}
			}
			else{
				$iterator = 11;
			}
			$sql = "insert into #__guru_quiz_taken (`user_id`, `quiz_id`, `score_quiz`, `date_taken_quiz`, `pid`, `time_quiz_taken_per_user`) values (".intval($user_id).", ".intval($quiz_id).", '".$score_quiz."', '".$date."', ".intval($course_id).", ".intval($iterator).")";
			$db->setQuery($sql);
			
			if($db->query()){
				$sql = "select max(`id`) from #__guru_quiz_taken";
				$db->setQuery($sql);
				$db->query();
				$max_id = $db->loadColumn();
				$max_id = $max_id["0"];
				
				$all_questions_ids_array = explode(",", $all_questions_ids);
				
				$sql = "INSERT INTO #__guru_quiz_question_taken (`user_id`, `show_result_quiz_id`, `answers_gived`,`question_id`, `question_order_no`) VALUES";			
				foreach($all_questions_ids_array as $key=>$q_id){
					$q_id = intval($q_id);
					if($q_id != 0){
						$checked_answers = JRequest::getVar("q".$q_id, array(), "post", "array");
						if(count($checked_answers) > 1){
							$checked_answers = implode(" ||", $checked_answers);
						}
						else{
							$checked_answers = $checked_answers["0"]." ||";
						}
						
						$sql .= "('".intval($user_id)."', '".$max_id."', '".$checked_answers."', '".intval($q_id)."', '".($key +1)."'),";
					}
				}
				$db->setQuery(substr($sql, 0, strlen($sql)  - 1));
				$db->query();		
			}
		}
		$quiz_form_content .= '</ul>';      
        $quiz_form_content .= '</div>';

		$quiz_form_header = "";
		if(@$quiz_details["0"]["is_final"] == 0){
			$lang_quizpassed = JText::_("GURU_QUIZ_PASSED_TEXT");
			$lang_quiz = JText::_("GURU_QUIZ_FAILED_TEXT");
			$next_button_text = JText::_("GURU_COURSE_CONTINUE_COURSE");
			$more_times = JText::_("GURU_MORE_TIMES");
		}
		else{
			$lang_quizpassed = JText::_("GURU_FEXAM_PASSED_TEXT");
			$lang_quiz = JText::_("GURU_FEXAM_FAILED_TEXT");
			$next_button_text = "";
			$more_times = JText::_("GURU_MOREFE_TIMES");
		}
		$passed_quiz = JText::_("GURU_QUIZ_PASSED");
		$percent =  JText::_("GURU_PERCENT");
		$min_to_pass = JText::_("GURU_MIN_TO_PASS");
		$congrat =  JText::_("GURU_CONGRAT");
		$failed =  JText::_("GURU_QUIZ_FAILED");
		$take_again = JText::_("GURU_TAKE_AGAIN_QUIZ");
		$time_remain_task_quiz = JText::_("GURU_TIMES_REMAIN_TAKE_QUIZ");
		$yes = JText::_("GURU_YES");
		$yes_again = JText::_("GURU_TAKE_AGAIN_QUIZ");
		$unlimited = JText::_("GURU_UNLIMITED");
		
		$catid_req = JRequest::getVar("catid_req","");
		$module_req = JRequest::getVar("module_req","");
		$cid_req = JRequest::getVar("cid_req","");
		$open_target = JRequest::getVar("open_target","");
		if($open_target == 1){
			$tmpl="&tmpl=component";
		}
		else{
			$tmpl="";
		}
		
		$link_quiz = JRoute::_('index.php?option=com_guru&view=gurutasks&catid='.$catid_req.'&module='.$module_req.'&cid='.$cid_req.$tmpl.'&Itemid=');
		
		$sql = "SELECT `time_quiz_taken_per_user`  FROM `#__guru_quiz_taken` WHERE user_id=".intval($user_id)." and quiz_id=".intval($quiz_id)." and pid=".intval($course_id)." ORDER BY id DESC LIMIT 0,1";                                               
		$db->setQuery($sql);
		$time_quiz_taken_per_user = $db->loadColumn();
		$time_quiz_taken_per_user = $time_quiz_taken_per_user["0"];
		$chances_remained = intval($resultt - $time_quiz_taken_per_user);
		
		if($resultt >= 0){
			if($score >= @$quiz_details["0"]["max_score"]){
				$quiz_form_header .= '<span class="guru_quiz_score">'.$your_score_text.':'.$score.'<span style="color:#292522;">'.$passed_quiz.'</span></span>';
				$quiz_form_header .='<div class ="guru-quiz-timer">';
				$quiz_form_header .='<span>'.$lang_quizpassed.'<span style="color:#669900;">'.$score.$percent.'</span>'.','." ".$min_to_pass." ".'<span style="color:#669900;">'.@$quiz_details["0"]["max_score"].$percent.'</span></span>';
				$quiz_form_header .='<br/><span>'.$congrat.'</span>';
				$quiz_form_header .='<br/></br><span>'.$next_button_text.'</span>';
				$quiz_form_header .='</div>';
			}
			else{
				$quiz_form_header .= '<span class="guru_quiz_score">'.$your_score_text.':'.$score.$percent.'<span style="color:#292522;">'.$failed.'</span></span>';
				$quiz_form_header .='<div class ="guru-quiz-timer">';
				$quiz_form_header .='<span>'.$lang_quiz.'<span style="color:#669900;">'.$score.$percent.'</span>'.','." "." ".$min_to_pass." ".'<span style="color:#669900;">'.@$quiz_details["0"]["max_score"].$percent.'</span></span>';
				if($resultt < 11){
					$quiz_form_header .='<br/><span>'.$time_remain_task_quiz.'<span style="color:#669900;">'." ".$chances_remained." ".'</span>'.$more_times.'</span>';
					$quiz_form_header .='<br/></br><span>'.$yes_again.'</span>';
                    $quiz_form_header .='<br/><br/><input type="button" class="guru-yes-no-quiz-button"  onClick="window.location=\''.$link_quiz.'\'" name="yesbutton" value="'.$yes.'"/>'.'&nbsp;&nbsp;';
				}
				else{
					$quiz_form_header .='<br/><span>'.$time_remain_task_quiz.'<span style="color:#669900;">'." ".$unlimited.'</span>'." ".$more_times.'</span>';
					$quiz_form_header .='<br/></br><span>'.$yes_again.'</span>';
                    $quiz_form_header .='<br/><br/><input type="button" class="guru-yes-no-quiz-button"  onClick="window.location=\''.$link_quiz.'\'" name="yesbutton" value="'.$yes.'"/>'.'&nbsp;&nbsp;';
				}
				$quiz_form_header .= '</div>';
			   
            }
		}


		
		return $quiz_form_header.$quiz_form_content;
	
	}
	function eliminateBlankAnswers($answers){
		$temp_array = array();
		if(isset($answers) && count($answers) > 0){
			foreach($answers as $key=>$value){
				if(trim($value) != ""){
					$temp_array[] = $value;
				}
			}
		}
		return $temp_array;
	}
	
	function generatePassed_Failed_quizzes($quiz_id, $course_id, $number_of_questions, $pass){
		$time_quiz_taken = "";
		$database = JFactory::getDBO();
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id = $user->id;
		$date = date('Y-m-d h:i:s');
		$quiz_form_content = "";
		$resultt ="";
		$your_score_text = JText::_("GURU_YOUR_SCORE");
		$guruModelguruOrder = new guruModelguruOrder();
		
		$id_quiz_question_row = JRequest::getVar("id_quiz_question",""); 
		$sql = "SELECT show_countdown, max_score, questions_per_page FROM #__guru_quiz WHERE id=".intval($quiz_id);
		$database->setQuery($sql);
		$result = $database->loadObject();

		$sql = "SELECT id, score_quiz, time_quiz_taken_per_user  FROM #__guru_quiz_taken WHERE user_id=".intval($user_id)." and quiz_id=".intval($quiz_id)." and pid=".intval($course_id)." ORDER BY id DESC LIMIT 0,1";                                               
		$database->setQuery($sql);
		$result_q = $database->loadObject();

		if($time_quiz_taken < 11){
			$time_user = $time_quiz_taken - $result_q->time_quiz_taken_per_user;
		}
		
		$first= explode("|", @$result_q->score_quiz);
		@$res = intval(($first[0]/$first[1])*100);
												
											
		$k = 0;
		$quiz_id =  intval($quiz_id);
		$id =  intval($result_q->id);
		$quiz_name = $guruModelguruOrder->getQuizNameF($quiz_id);
		$number_of_questions = $first[1];
	   
		   
		$score = $res." %";
		$ans_gived =  $guruModelguruOrder->getAnsGivedFr($user_id,$id,$quiz_id);
		$ans_right =  $guruModelguruOrder->getAnsRightFr($quiz_id, $id );
		$the_question =  $guruModelguruOrder->getQuestionNameF($id,$quiz_id);


		
		$all_answers_array = $guruModelguruOrder->getAllAnsF($quiz_id,$id);
		$all_answers_text_array = $guruModelguruOrder->getAllAnsTextF($quiz_id,$id);
		
		if($pass == 1){   
			@$quiz_result_content .='<div class ="guru-quiz-timer">';
			$quiz_result_content .='<span>'. JText::_("GURU_QUIZ_PASSED_TEXT").'<span style="color:#669900;">'.$score.'</span>'.','." ". JText::_("GURU_MIN_TO_PASS")." ".'<span style="color:#669900;">'.$result->max_score.JText::_("GURU_PERCENT").'</span></span>';
			$quiz_result_content .='<br/>'.'<span>'.JText::_("GURU_CONGRAT").'</span>';
			$quiz_result_content .='<br/></br>'.'<span>'.JText::_("GURU_COURSE_CONTINUE_COURSE").'</span>';
			$quiz_result_content .='</div>';   
		}
		else{
			@$quiz_result_content .='<div class ="guru-quiz-timer">';
			$quiz_result_content .='<span>'. JText::_("GURU_QUIZ_FAILED_TEXT").'<span style="color:#669900;">'.$score.'</span>'.','." ". JText::_("GURU_MIN_TO_PASS")." ".'<span style="color:#669900;">'.$result->max_score. JText::_("GURU_PERCENT").'</span></span>';
			$quiz_result_content .='</div><br/>';  
		}
		$quiz_result_content .= '<div id="the_quiz">';
		
		$per_page = $result->questions_per_page;// questions per page
		if($per_page == 0){
			$per_page = $number_of_questions;
		}
		$nr_pages = 1;// default one page
		
		if($number_of_questions > 0 && $number_of_questions > $per_page){
			$nr_pages = ceil($number_of_questions / $per_page);
		}
		
		for($pag = 1; $pag <= $nr_pages; $pag++){
			$k = ($pag - 1) * $per_page;
			$added = 0;

			$display = "";
			if($pag == 1){
				$display = "block";
			}
			else{
				$display = "none";
			}
			
			$quiz_result_content .= '<div id="quiz_page_'.$pag.'" style="display:'.$display.';">'; // start page
			
			for($i=$k; $i<intval($pag * $per_page); $i++){
				if(!isset($all_answers_array[$i])){
					continue;
				}
				
				$answer_count = 0;
				$all_answers_array_result = explode("|||",$all_answers_array[$i]);
				$all_answers_array_result = $this->eliminateBlankAnswers($all_answers_array_result);
				
				$all_answers_text_array_result = explode("|||",$all_answers_text_array[$i]);
				$ans_right_result = explode("|||", $ans_right[$i]->answers);
				$ans_gived_result = explode(" ||", $ans_gived[$i]->answers_gived);
				
				$all_answers_text_array_result = $this->eliminateBlankAnswers($all_answers_text_array_result);
				$ans_right_result = $this->eliminateBlankAnswers($ans_right_result);
				$ans_gived_result = $this->eliminateBlankAnswers($ans_gived_result);
				
				
				@$quiz_result_content .= '<ul class="guru_list">';
				$empty_elements = array("");
				$ans_gived_result = array_diff($ans_gived_result,$empty_elements);
				
				if(count(array_intersect($ans_right_result, $ans_gived_result)) == count($ans_right_result) && count($ans_gived_result) == count($ans_right_result)) {
					$answer_count ++;
					$quiz_result_content .= '<li class="question right">'. str_replace("\'", "&acute;",$the_question[$i]->text).'</li>';                               
				}
				else{   
					$quiz_result_content .= '<li class="question wrong g_quize_q">'. str_replace("\'", "&acute;",$the_question[$i]->text).'</li>';                   
				}
			   
				for($j=0; $j<count($all_answers_array_result); $j++){
					if($all_answers_array_result[$j] != "") {
						//--------------------------------------------
						$inArray = in_array($all_answers_array_result[$j], $ans_right_result);
						//--------------------------------------------
						if($inArray){
							$quiz_result_content .= '<li class="correct">'.$all_answers_text_array_result[$j].'</li>';
						}
						else{
							$quiz_result_content .= '<li class="incorrect">'.$all_answers_text_array_result[$j].'</li>';
						}
					}
				}   
			   
				$quiz_result_content .= '</ul>'; 
			}
			$quiz_result_content .= '</div>'; // end page
		}
		
		if($nr_pages > 1){
			$quiz_result_content .= '<div class="pagination pagination-centered"><ul class="uk-pagination">';
			$quiz_result_content .= 	'<li class="pagination-start" id="pagination-start"><span class="pagenav">'.JText::_("GURU_START").'</span></li>';
			$quiz_result_content .= 	'<li class="pagination-prev" id="pagination-prev"><span class="pagenav">'.JText::_("GURU_PREV").'</span></li>';
			for($p=1; $p<=$nr_pages; $p++){
				if($p == 1){
					$quiz_result_content .= '<li id="list_1"><span class="pagenav">1</span></li>';
				}
				else{
					$quiz_result_content .= '<li id="list_'.$p.'">
										<a onclick="changePage('.intval($p).', '.intval($nr_pages).'); return false;" href="#">'.$p.'</a>
									 </li>';
				}
			}
			$quiz_result_content .= 	'<li class="pagination-next" id="pagination-next">
									<a href="#" onclick="changePage(2, '.intval($nr_pages).'); return false;">'.JText::_("GURU_NEXT").'</a>
								 </li>';
			$quiz_result_content .= 	'<li class="pagination-end" id="pagination-end">
									<a href="#" onclick="changePage('.intval($nr_pages).', '.intval($nr_pages).'); return false;">'.JText::_("GURU_END").'</a>
								 </li>';
			$quiz_result_content .= '</ul></div>';
		}
		
		$quiz_result_content .= '</div>';

		$quiz_form_header = "";
		if(@$quiz_details["0"]["is_final"] == 0){
			$lang_quizpassed = JText::_("GURU_QUIZ_PASSED_TEXT");
			$lang_quiz = JText::_("GURU_QUIZ_FAILED_TEXT");
			$next_button_text = JText::_("GURU_COURSE_CONTINUE_COURSE");
			$more_times = JText::_("GURU_MORE_TIMES");
		}
		else{
			$lang_quizpassed = JText::_("GURU_FEXAM_PASSED_TEXT");
			$lang_quiz = JText::_("GURU_FEXAM_FAILED_TEXT");
			$next_button_text = "";
			$more_times = JText::_("GURU_MOREFE_TIMES");
		}
		$passed_quiz = JText::_("GURU_QUIZ_PASSED");
		$percent =  JText::_("GURU_PERCENT");
		$min_to_pass = JText::_("GURU_MIN_TO_PASS");
		$congrat =  JText::_("GURU_CONGRAT");
		$failed =  JText::_("GURU_QUIZ_FAILED");
		$take_again = JText::_("GURU_TAKE_AGAIN_QUIZ");
		$time_remain_task_quiz = JText::_("GURU_TIMES_REMAIN_TAKE_QUIZ");
		$yes = JText::_("GURU_YES");
		$unlimited = JText::_("GURU_UNLIMITED");
		
		$sql = "SELECT `time_quiz_taken_per_user`  FROM `#__guru_quiz_taken` WHERE user_id=".intval($user_id)." and quiz_id=".intval($quiz_id)." and pid=".intval($course_id)." ORDER BY id DESC LIMIT 0,1";                                               
		$db->setQuery($sql);
		$time_quiz_taken_per_user = $db->loadColumn();
		$time_quiz_taken_per_user = $time_quiz_taken_per_user["0"];
		$chances_remained = intval($resultt - $time_quiz_taken_per_user);
		
		if(@$resultt >= 0){
			if($score >= @$quiz_details["0"]["max_score"]){
				$quiz_form_header .= '<span class="guru_quiz_score">'.$your_score_text.':'.$score.'<span style="color:#292522;">'.$passed_quiz.'</span></span>';
				$quiz_form_header .='<div class ="guru-quiz-timer">';
				$quiz_form_header .='<span>'.$lang_quizpassed.'<span style="color:#669900;">'.$score." ".$percent.'</span>'.','." ".$min_to_pass." ".'<span style="color:#669900;">'.@$quiz_details["0"]["max_score"].$percent.'</span></span>';
				$quiz_form_header .='<br/><span>'.$congrat.'</span>';
				$quiz_form_header .='<br/></br><span>'.$next_button_text.'</span>';
				$quiz_form_header .='</div>';
			}
			else{
				$quiz_form_header .= '<span class="guru_quiz_score">'.$your_score_text.':'.$score.$percent.'<span style="color:#292522;">'.$failed.'</span>';
				$quiz_form_header .='<div class ="guru-quiz-timer">';
				$quiz_form_header .='<span>'.$lang_quiz.'<span style="color:#669900;">'.$score.$percent.'</span>'.','." "." ".$min_to_pass." ".'<span style="color:#669900;">'.@$quiz_details["0"]["max_score"].$percent.'</span></span>';
				if($time_quiz_taken < 11){
					$quiz_form_header .='<br/><span>'.$time_remain_task_quiz.'<span style="color:#669900;">'." ".$chances_remained." ".'</span>'.$more_times.'</span>';
				}
				else{
					$quiz_form_header .='<br/><span>'.$time_remain_task_quiz.'<span style="color:#669900;">'." ".$unlimited." ".'</span>Unlimited</span>';
				}
				
				if($time_quiz_taken >1){
					$quiz_form_header .='<br/></br><span>'.$take_again.'</span>';
					$quiz_form_header .='<br/><br/><input type="button" class="guru-yes-no-quiz-button"  onClick="window.location.reload()" name="yesbutton" value="'.$yes.'"/>'.'&nbsp;&nbsp;';

				}
				$quiz_form_header .= '</div>';
			   
            }
		}	
		return $quiz_result_content;
	}
	
};
?>