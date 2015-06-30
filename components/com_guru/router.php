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

function guruBuildRoute(&$query){
	$segments = array();
	$database = JFactory::getDBO();
	$task = @$query['task'];
	$active_menu_id = @$query['Itemid'];
	unset($query['task']);
	
	// get a menu item based on Itemid or currently active
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	// we need a menu item.  
	//Either the one specified in the query, or the current active one if none specified
	if (empty($query['Itemid'])) {
		$menuItem = $menu->getActive();
		$menuItemGiven = false;
		unset($query['Itemid']);
	}
	else {
		$menuItem = $menu->getItem($query['Itemid']);
		$menuItemGiven = true;
	}
	

	$view = "";
	if(isset($query['view'])) {
		$view = $query['view'];
	}
	else{
		// we need to have a view in the query or it is an invalid URL
		@$view = $menuItem->query["view"];
		$query['view'] = $view;
	}
	if(isset($query['view']) && ($query['view'] == "guruPcategs" || $query['view'] == "gurupcategs")){
		$segments[] = $query['view'];
		unset($query['view']);		
		unset($query['task']);
		if(isset($query['cid'])){
			$segments[] = $query['cid'];
		}
		unset($query['cid']);
	}
	
	if(isset($query['view']) && ($query['view'] == "guruPrograms" || $query['view'] == "guruprograms")){
		$segments[] = $query['view'];
		unset($query['view']);
		
		if(isset($query['cid'])){
			$course_id = intval($query['cid']);
		}
		else{
			$course_id = "0";
		}
		
		if($course_id == "0"){
			$course_id = intval(@$query['course_id']);
		}
		
		if($course_id == "0"){
			if(isset($menuItem->params)){
				$course_id = intval($menuItem->params->get("cid", 0));
			}
		}
		
		
		
		//------------------------------------------------------------------------------------		
		$sql = "select catid from #__guru_program where id=".intval($course_id);		
		$database->setQuery($sql);
		$database->query();
		$category_id = $database->loadResult();
		if(intval($category_id) != 0){
			$sql = "select name, alias from #__guru_category where id=".intval($category_id);
			$database->setQuery($sql);
			$database->query();
			$result = $database->loadAssocList();			
			$alias = "";
			if(isset($result["0"]["alias"])){
				if(trim($result["0"]["alias"]) == ""){
					$alias = JFilterOutput::stringURLSafe($result["0"]["name"]);
				}
				else{
					$alias = trim($result["0"]["alias"]);
				}
			}
			$segments[] = $category_id."-".$alias;
		}
		//------------------------------------------------------------------------------------
		
		$sql = "select name, alias from #__guru_program where id=".intval($course_id);
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadAssocList();			
		$alias = "";
		if(trim(@$result["0"]["alias"]) == ""){
			$alias = JFilterOutput::stringURLSafe(@$result["0"]["name"]);
		}
		else{
			$alias = trim($result["0"]["alias"]);
		}
		$segments[] = intval($course_id)."-".$alias;
		unset($query['cid']);
		unset($query['layout']);
		
		if(isset($query['action'])){
			$segments[] = $query['action'];
			unset($query['action']);
		}
		unset($query['catid']);
		unset($query['controller']);
		
		if(isset($query['registered_user'])){
			$segments[] = $query['registered_user'];
			unset($query['registered_user']);
		}
	}
	
	if(isset($query['view']) && ($query['view'] == "gurutasks" || $query['view'] == "guruTasks")){
		$segments[] = $query['view'];
		unset($query['view']);
		//------------------------------------------------------------------------------------
		if(isset($query['catid'])){
			$sql = "select name, alias from #__guru_category where id=".intval($query['catid']);
			$database->setQuery($sql);
			$database->query();
			$result = $database->loadAssocList();			
			$alias = "";
			if(trim(@$result["0"]["alias"]) == ""){
				$alias = JFilterOutput::stringURLSafe(@$result["0"]["name"]);
			}
			else{
				$alias = trim(@$result["0"]["alias"]);
			}
			$segments[] = intval($query['catid'])."-".$alias;
			unset($query['catid']);
		}
		//------------------------------------------------------------------------------------
		if(isset($query['module'])){
			$sql = "select title, alias from #__guru_days where id=".intval($query['module']);
			$database->setQuery($sql);
			$database->query();
			$result = $database->loadAssocList();			
			$alias = "";
			if(trim($result["0"]["alias"]) == ""){
				$alias = JFilterOutput::stringURLSafe($result["0"]["title"]);
			}
			else{
				$alias = trim($result["0"]["alias"]);
			}
			$segments[] = intval($query['module'])."-".$alias;
			unset($query['module']);
		}
		//------------------------------------------------------------------------------------		
		if(isset($query['cid'])){
			$sql = "select name, alias from #__guru_task where id=".intval($query['cid']);
			$database->setQuery($sql);
			$database->query();
			$result = $database->loadAssocList();		
			$alias = "";
			if(trim($result["0"]["alias"]) == ""){
				$alias = JFilterOutput::stringURLSafe($result["0"]["name"]);
			}
			else{
				$alias = trim($result["0"]["alias"]);
			}
			if(intval($query['cid']) != "0"){
				$segments[] = intval($query['cid'])."-".$alias;
				unset($query['cid']);
			}
		}
		//------------------------------------------------------------------------------------
		
		unset($query['task']);
		
		unset($query['action']);
		
		if(isset($query['certificate'])){
			$segments[] = $query['certificate'];
			unset($query['certificate']);
		}
		if(isset($query['pdf'])){
			$segments[] = $query['pdf'];
			unset($query['pdf']);
		}
		
		if(isset($query['dw'])){
			$segments[] = $query['dw'];
			unset($query['dw']);
		}
		
		if(isset($query['ci'])){
			$segments[] = $query['ci'];
			unset($query['ci']);
		}
		
		if(isset($query['prev_lesson_id'])){
			$segments[] = $query['prev_lesson_id'];
			unset($query['prev_lesson_id']);
		}
		
		if(isset($query['module_prev_lesson'])){
			$segments[] = $query['module_prev_lesson'];
			unset($query['module_prev_lesson']);
		}
		
		if(isset($query["course_id"])){
			$segments[] = $query["course_id"];
			unset($query['course_id']);
		}
		
		unset($query['view']);
		
	}

	if(isset($query['view']) && (($query['view'] == "guruorders") || ($query['view'] == "guruOrders"))){
		$segments[] = $query['view'];
		unset($query['view']);
		if(isset($query['layout'])){
			$segments[] = $query['layout'];
			unset($query['layout']);
		}
		
		if(isset($query['back'])){
			$segments[] = $query['back'];
			unset($query['back']);
		}

		if(isset($query['course'])){
			$sql = "select name, alias from #__guru_program where id=".intval($query['course']);
			$database->setQuery($sql);
			$database->query();
			$result = $database->loadAssocList();			
			$alias = "";
			if(trim($result["0"]["alias"]) == ""){
				$alias = JFilterOutput::stringURLSafe($result["0"]["name"]);
			}
			else{
				$alias = trim($result["0"]["alias"]);
			}
			if(intval($query['course']) != 0){
				$segments[] = intval($query['course'])."-".$alias;
				unset($query['course']);
			}
		}
	}
	
	if(isset($query['view']) && ($query['view'] == "guruauthor")){
		$segments[] = $query['view'];
		unset($query['view']);
		if(isset($query['controller'])){
			unset($query['controller']);
		}
		
		if(intval($active_menu_id) != 0){
			$menuItem = $menu->getItem($query['Itemid']);
			if(isset($menuItem)){
				$menu_vars = $menuItem->query;
				$id = @$query["id"];
				$cid = @$query["cid"];
				$pid = @$query["pid"];
				
				if(isset($menu_vars["layout"]) && !isset($query['layout']) && (intval($id) == 0 && intval($cid) == 0 && intval($pid) == 0)){
					$segments[] = $menu_vars["layout"];
				}
				else{
					if(isset($query['layout'])){
						$segments[] = $query['layout'];
						unset($query['layout']);
					}
				}
			}	
		}
		else{
			if(isset($query['layout'])){
				$segments[] = $query['layout'];
				unset($query['layout']);
			}
		}
		
		$segments[] = @$query['cid'];
		unset($query['cid']);
		
		if(isset($query['pid'])){
			$segments[] = $query['pid'];
			unset($query['pid']);
		}
		
		if(isset($query['id'])){
			$segments[] = $query['id'];
			unset($query['id']);
		}
		
		if(isset($query['userid'])){
			$segments[] = $query['userid'];
			unset($query['userid']);
		}
		
		if(isset($query['v'])){
			$segments[] = $query['v'];
			unset($query['v']);
		}
		
		if(isset($query['e'])){
			$segments[] = $query['e'];
			unset($query['e']);
		}
		if(isset($query['export'])){
			$segments[] = $query['export'];
			unset($query['export']);
		}
		
		if(isset($query['quiz'])){
			$db = JFactory::getDBO();
			$sql = "select `name` from #__guru_quiz where `id`=".intval($query['quiz']);
			$db->setQuery($sql);
			$db->query();
			$q_name = $db->loadColumn();
			$q_name = @$q_name["0"];
			$q_alias = JFilterOutput::stringURLSafe($q_name);
			
			$segments[] = $query['quiz']."-".$q_alias;
			unset($query['quiz']);
		}
		
		if($task == "treeCourse"){
			$segments[] = "tree";
		}
		elseif($task == "addCourse"){
			$segments[] = "info";
		}
		elseif($task == "editMedia"){
			$segments[] = "infom";
		}
		elseif($task == "editQuizFE"){
			$segments[] = "infoq";
		}
		elseif($task == "authoraddeditmediacat"){
			$segments[] = "infomc";
		}
		elseif($task == "newStudent"){
			$segments[] = "infos";
		}
		elseif($task == "course_stats"){
			$segments[] = "stats";
		}
		elseif($task == "quizz_stats"){
			$segments[] = "qstats";
		}
		elseif($task == "studentdetails"){
			$segments[] = "ststats";
		}
		elseif($task == "studentquizes"){
			$segments[] = "squizes";
		}
		elseif($task == "quizdetails"){
			$segments[] = "qdetails";
		}
		
		if(isset($query["tmpl"])){
			$segments[] = $query["tmpl"];
			unset($query['tmpl']);
		}
		if(isset($query["action"])){
			$segments[] = $query["action"];
			unset($query['action']);
		}
	}
	
	if(isset($query['view']) && ($query['view'] == "guruProfile")){
		$segments[] = $query['view'];
		unset($query['view']);
		
		if(isset($query["task"])){
			$segments[] = $query["task"];
			unset($query['task']);
		}
		
		if(isset($query["course_id"])){
			$segments[] = $query["course_id"];
			unset($query['course_id']);
		}
		
		if(isset($query["returnpage"])){
			$segments[] = $query["returnpage"];
			unset($query['returnpage']);
		}
		
		if(isset($query["graybox"])){
			$segments[] = $query["graybox"];
			unset($query['graybox']);
		}
		
		if(isset($query["tmpl"])){
			$segments[] = $query["tmpl"];
			unset($query['tmpl']);
		}
	}

	if(isset($query['view']) && ($query['view'] == "guruLogin")){

		$segments[] = $query['view'];
		unset($query['view']);
		
		$segments[] = $query['returnpage'];
		unset($query['returnpage']);
		
		if(isset($query['cid'])){
			$sql = "select name, alias from #__guru_program where id=".intval($query['cid']);
			$database->setQuery($sql);
			$database->query();
			$result = $database->loadAssocList();			
			$alias = "";
			if(trim($result["0"]["alias"]) == ""){
				$alias = JFilterOutput::stringURLSafe($result["0"]["name"]);
			}
			else{
				$alias = trim($result["0"]["alias"]);
			}
			if(intval($query['cid']) != 0){
				$segments[] = intval($query['cid'])."-".$alias;
				unset($query['cid']);
			}
		}
	}
	
	if(isset($query['view']) && ($query['view'] == "guruEditplans")){
		$segments[] = $query['view'];
		unset($query['view']);
		
		unset($query['tmpl']);
		
		$segments[] = $query['course_id'];
		unset($query['course_id']);
		
		if(isset($query['action'])){
			$segments[] = $query['action'];
			unset($query['action']);
		}
	}
	
	if(isset($query['view']) && ($query['view'] == "guruBuy")){
		$segments[] = $query['view'];
		unset($query['view']);
	}
	
	return $segments;
}


function guruParseRoute($segments){
	$database = JFactory::getDBO();
	$user = JFactory::getUser();
	$vars = array();
	//Get the active menu item.
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$item	= $menu->getActive();
	//echo"<pre>"; var_dump($menuid);  var_dump($segments);echo"</pre>";
	// Count route segments
	$count = count($segments);
	
	if($segments["0"] == "guruPcategs" || $segments["0"] == "gurupcategs"){
		if($segments["0"] == "gurupcategs"){
			$vars['view'] = "gurupcategs";
			
			/*$app	= JFactory::getApplication();
			$menu	= $app->getMenu();
			$item	= $menu->getActive();
			
			if(isset($item->query["layout"]) && trim($item->query["layout"]) != ""){// click on menu item
				$vars['task'] = "view";
			}*/
		}
		else{
			$vars['view'] = "guruPcategs";
			$vars['task'] = "view";
			$vars['cid'] = intval($segments["1"]);
		}
	}
	
	if($segments["0"] == "guruPrograms" || $segments["0"] == "guruprograms"){
		if($segments["0"] == "guruPrograms"){ // when is comming from course link, from category page, or click on Buy Now
			$vars['view'] = "guruPrograms";
			$vars['catid'] = intval($segments["1"]);
			$vars['cid'] = intval($segments["2"]);
			
			$course_id = JRequest::getVar("course_id", "0");
			if(intval($course_id) == 0){// from course link
				$vars['task'] = 'view';
			}
			else{ //click on Buy Now
				$vars['task'] = 'buy_action';
			}
			
			if(isset($segments["3"]) && (intval($segments["3"]) == 0 || intval($segments["3"]) == 1)){
				$vars["registered_user"] = intval($segments["3"]);
				$vars['task'] = 'enroll';
			}
			
		}
		elseif($segments["0"] == "guruprograms"){ // when is comming from menu item - Course Layout
			$vars['view'] = "guruPrograms";
			$vars['catid'] = intval($segments["1"]);
			$vars['cid'] = intval($segments["2"]);
			if(isset($segments["3"]) && $segments["3"] == "enroll"){
				$vars['task'] = 'enroll';
			}
			else{
				$vars['layout'] = 'view';
			}
		}
	}
	
	if($segments["0"] == "gurutasks" || $segments["0"] == "guruTasks"){
		if(count($segments) == 5){
			$vars['view'] = $segments["0"];
			$vars['certificate'] = $segments["1"];
			$vars['pdf'] = $segments["2"];
			$vars['ci'] = $segments["3"];
			$vars['dw'] = $segments["4"];
			$vars['task'] = 'viewcertificate';
		}
		elseif(count($segments) == 9){
			$vars['view'] = $segments["0"];
			$vars['course_id'] = intval($segments["1"]);
			$vars['certificate'] = intval($segments["2"]);
			$vars['pdf'] = intval($segments["3"]);
			$vars['dw'] = intval($segments["4"]);
			$vars['ci'] = intval($segments["5"]);
			$vars['prev_lesson_id'] = intval($segments["6"]);
			$vars['module_prev_lesson'] = intval($segments["7"]);
			$vars['task'] = 'viewcertificate';
		}
		else{
			$vars['view'] = "gurutasks";
			$vars['catid'] = intval($segments["1"]);
			$vars['module'] = intval($segments["2"]);
			if(isset($segments["3"])){
				$vars['cid'] = intval($segments["3"]);
			}
			//$vars['task'] = 'view';
			if(!isset($segments["3"])){
				$vars['action'] = 'viewmodule';
			}
		}
	}
	
	if($segments["0"] == "guruorders" ||$segments["0"] == "guruOrders"){
		$vars['view'] = "guruorders";
		
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getActive();
		$task = JRequest::getVar("task", "");
		
		if(isset($item->query["layout"]) && trim($item->query["layout"]) != ""){
			$vars['layout'] = trim($item->query["layout"]);
		}
		
		if(isset($segments["1"]) && intval($segments["1"]) == 0){
			$vars['layout'] = $segments["1"];
		}
		
		if($task == "myorders"){
			$vars['layout'] = "myorders";
		}
		elseif($task == "mycourses"){
			$vars['layout'] = "mycourses";
		}
		
		if(intval(@$segments["1"]) == 1){
			$vars["back"] = 1;
			$vars["task"] = "printcertificate";
		}
		
		if(isset($segments["2"]) && intval($segments["2"]) != 0){
			$vars["course"] = intval($segments["2"]);
		}
	}
	
	if($segments["0"] == "guruauthor"){
		$vars['view'] = "guruauthor";
		$task = JRequest::getVar("task", "");
		
		$app	= JFactory::getApplication();
		$menu	= $app->getMenu();
		$item	= $menu->getActive();
		if(count($segments) == 2 && $segments["1"] == "authorprofile"){
			$vars['layout'] = 'authorprofile';
		}
		else{
			if(intval(@$segments["1"]) == 0){
				if(isset($segments["1"]) && $segments["1"] == "authormycourses"){
					$vars['layout'] = 'authormycourses';
					if(trim($task) == ""){
						$vars['task'] = 'authormycourses';
					}
					else{
						$vars['task'] = trim($task);
						unset($vars['layout']);
					}
					
					if(isset($segments["2"]) && intval($segments["2"]) != "0"){
						if(strpos($segments["2"], ":") === FALSE){
							$vars['userid'] = intval($segments["2"]);
						}
						else{
							$vars['quiz'] = intval($segments["2"]);
						}
					}
				}
				elseif(isset($segments["1"]) && $segments["1"] == "mystudents"){
					$vars['layout'] = 'mystudents';
					
					if(trim($task) != ""){
						$vars['task'] = trim($task);
						unset($vars['layout']);
					}
					
					if(isset($segments["2"]) && intval($segments["2"]) != 0){
						$vars['cid'] = intval($segments["2"]);
					}
					
					if(count($segments) == 4){
						$vars['action'] = $segments["3"];
					}
				}
				elseif(isset($segments["1"]) && $segments["1"] == "authorquizzes"){
					$vars['layout'] = 'authorquizzes';
					
					if(trim($task) != ""){
						$vars['task'] = trim($task);
						unset($vars['layout']);
					}
					
					if(isset($segments["3"]) && $segments["3"] == "component"){
						$vars['task'] = "addquizzes";
						unset($vars['layout']);
						$vars['tmpl'] = "component";
					}
				}
				elseif(isset($segments["1"]) && $segments["1"] == "authormymedia"){
					$vars['layout'] = 'authormymedia';
					
					if(trim($task) != ""){
						$vars['task'] = trim($task);
						unset($vars['layout']);
					}
				}
				elseif(isset($segments["1"]) && $segments["1"] == "authormymediacategories"){
					$vars['layout'] = 'authormymediacategories';
					
					if(trim($task) != ""){
						$vars['task'] = trim($task);
						unset($vars['layout']);
					}
				}
				elseif(isset($segments["1"]) && $segments["1"] == "authorcommissions"){
					$vars['layout'] = 'authorcommissions';
					
					if(trim($task) != ""){
						$vars['task'] = trim($task);
						unset($vars['layout']);
					}
				}
				elseif(isset($segments["1"]) && $segments["1"] == "newStudent"){
					$vars['task'] = "newStudent";
					$vars['id'] = intval($segments["2"]);
					
					if($task == "save"){
						$vars['layout'] = "mystudents";
					}
					else{
						$vars['layout'] = "newStudent";
					}
				}
				elseif(isset($segments["1"]) && $segments["1"] == "studentdetails"){
					$vars['layout'] = 'studentdetails';
					if(isset($segments["2"])){
						$vars["userid"] = intval($segments["2"]);
					}
					$vars["task"] = "studentdetails";
				}
				elseif(isset($segments["1"]) && $segments["1"] == "studentquizes"){
					$vars['layout'] = 'studentquizes';
					if(isset($segments["2"])){
						$vars["pid"] = intval($segments["2"]);
					}
					
					if(isset($segments["3"])){
						$vars["userid"] = intval($segments["3"]);
					}
					$vars["task"] = "studentquizes";
				}
				elseif(isset($segments["1"]) && $segments["1"] == "quizdetails"){
					$vars['layout'] = 'quizdetails';
					if(isset($segments["2"])){
						$vars["pid"] = intval($segments["2"]);
					}
					
					if(isset($segments["3"])){
						$vars["userid"] = intval($segments["3"]);
					}
					
					if(isset($segments["4"])){
						$vars["quiz"] = intval($segments["4"]);
					}
					$vars["task"] = "quizdetails";
				}
				elseif(isset($item->query["layout"]) && trim($item->query["layout"]) != ""){// click on menu item - teacher
					$vars['layout'] = 'view';
				}
				elseif(isset($segments["1"]) && $segments["1"] == "view"){
					$vars['layout'] = 'view';
					if(isset($segments["2"])){
						$vars["cid"] = intval($segments["2"]);
					}
				}
			}
			else{
				if(isset($segments["2"]) && $segments["2"] == "tree"){
					$vars['task'] = "treeCourse";
					$vars['pid'] = intval($segments["1"]);
				}
				elseif(isset($segments["2"]) && $segments["2"] == "info"){
					$vars['task'] = "addCourse";
					$vars['id'] = intval($segments["1"]);
				}
				elseif(isset($segments["2"]) && $segments["2"] == "infom"){
					$vars['task'] = "editMedia";
					$vars['cid'] = intval($segments["1"]);
				}
				elseif(isset($segments["2"]) && $segments["2"] == "infomc"){
					$vars['task'] = "authoraddeditmediacat";
					$vars['id'] = intval($segments["1"]);
				}
				elseif(isset($segments["2"]) && $segments["2"] == "stats"){
					$vars['task'] = "course_stats";
					$vars['id'] = intval($segments["1"]);
				}
				elseif(isset($segments["4"]) && $segments["4"] == "infoq"){
					$vars['task'] = "editQuizFE";
					$vars['cid'] = intval($segments["1"]);
					$vars['v'] = intval($segments["2"]);
					$vars['e'] = intval($segments["3"]);
				}
				elseif(isset($segments["2"]) && $segments["2"] == "infoq"){
					$vars['task'] = "editQuizFE";
					$vars['cid'] = intval($segments["1"]);
					$vars['e'] = "1";
				}
				elseif(isset($segments["3"]) && $segments["3"] == "infoq"){
					$vars['task'] = "editQuizFE";
					$vars['cid'] = intval($segments["1"]);
					$vars['e'] = "1";
				}
				elseif(isset($segments["2"]) && $segments["2"] == "qstats"){
					$vars['task'] = "quizz_stats";
					$vars['id'] = intval($segments["1"]);
				}
				elseif(isset($segments["2"]) && $segments["2"] == "ststats"){
					$vars['task'] = "studentdetails";
					$vars['id'] = intval($segments["1"]);
				}
			}
		}
		
	}
	
	if($segments["0"] == "guruProfile"){
		$vars['view'] = "guruProfile";
		
		if(count($segments) == 2){ // click on menu item My Quizes and redirect to profile page if not customer
			$vars['task'] = "edit";
			$vars['returnpage'] =@$segments["2"];
		}
	}
	
	if($segments["0"] == "guruLogin"){	
		$vars['view'] = "guruLogin";
		if(isset($segments["1"])){
			$vars['returnpage'] = @$segments["1"];
		}
		
		if(isset($segments["2"])){
			$vars['cid'] = intval(@$segments["2"]);
		}
	}
	
	if($segments["0"] == "guruEditplans"){
		$vars['view'] = "guruEditplans";
		$vars['course_id'] = $segments["1"];
		$vars['tmpl'] = "component";
		if(isset($segments["2"])){
			$vars['action'] = trim($segments["2"]);
		}
	}
	
	if($segments["0"] == "guruBuy"){
		$vars['view'] = "guruBuy";
	}
	
	if(isset($vars["view"])){
		$temp = str_replace("guru", "", $vars["view"]);
		$temp = ucfirst($temp);
		$vars["controller"] = "guru".$temp;
	}
	
	$task = JRequest::getVar("task", "");
	
	if(trim($task) != ""){
		$vars['task'] = trim($task);
	}
	
	if(@$segments["0"] != "guruorders" && @$segments["1"] != "mycertificates" && @$segments["1"] != "myorders" && @$_SESSION["CB"] == "1"){
		$itemid = $_SESSION["ITEMID"];
		if(intval($itemid) != 0){
			$vars['Itemid'] = $itemid;
		}
		unset($_SESSION["ITEMID"]);
		unset($_SESSION["CB"]);
	}
	return $vars;
}
?>