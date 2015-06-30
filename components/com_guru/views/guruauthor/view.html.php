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

jimport ("joomla.application.component.view");
class guruViewguruauthor extends JViewLegacy {

	function display ($tpl =  null ) {
		$db = JFactory::getDBO();
		$authors = $this->get('authorList');
		$this->assignRef('authors', $authors);
		
		$author = $this->get('Author');
		$this->assignRef('author', $author);
		
		$config =  $this->get('Config');
		$this->assignRef('config', $config);

		parent::display($tpl);
	}
	
	function view ($tpl =  null ) {
		$db = JFactory::getDBO();
		$author = $this->get('author');
		$this->assignRef('author', $author);
		
		$config = $this->get('Config');
		$this->assignRef('config', $config);
		parent::display($tpl);

	}
	
	function authorprofile($tpl =  null ) {
		$db = JFactory::getDBO();
		$author = $this->get('author');
		$this->assignRef('author', $author);
		
		$user =  $this->get('AuthorDetails');
		$this->assignRef("user",$user);
		
		$config =  $this->get('Config');
		$this->assignRef('config', $config);
		
		parent::display($tpl);

	}
	function authorcommissions_pending($tpl =  null ) {
		$details = $this->get('PendingDetails');
		$this->assignRef('details', $details);
		
		$total_pending = $this->get('AuthorcommissionsPending');
		$this->assignRef('total_pending', $total_pending);		
		
		$config = $this->get('Config');
		$this->assignRef('config', $config);
		
		parent::display($tpl);
	}
	function authorcommissions_paid($tpl =  null ) {
		$details = $this->get('PaidDetails');
		$this->assignRef('details', $details);
		
		$total_paid = $this->get('AuthorcommissionsPaid');
		$this->assignRef('total_paid', $total_paid);		
		
		$config = $this->get('Config');
		$this->assignRef('config', $config);
		
		parent::display($tpl);

	}	
	
	function mystudents($tpl =  null ) {
		$db = JFactory::getDBO();
		$author = $this->get('author');
		$this->assignRef('author', $author);
		$this->state = $this->get('State');
		
		$user =  $this->get('AuthorDetails');
		$this->assignRef("user",$user);
		
		$config =  $this->get('Config');
		$this->assignRef('config', $config);
		
		$students = $this->get('Items');
		$this->assignRef('students', $students);
		
		$this->pagination = $this->get('Pagination');
		
		parent::display($tpl);

	}
	
	function authormymedia($tpl =  null ) {
		$db = JFactory::getDBO();
		$author = $this->get('author');
		$this->assignRef('author', $author);
		$this->state = $this->get('State');
		
		$all_categs =  $this->get('AllMediaCategory');
		$this->assignRef("all_categs",$all_categs);
		
		$config =  $this->get('Config');
		$this->assignRef('config', $config);
		
		$mymediath = $this->get('Items');
		$this->assignRef('mymediath', $mymediath);
		
		$this->pagination = $this->get('Pagination');

		$filters = $this->get('filtersMedia');
		$this->assignRef('filters', $filters);
		
		parent::display($tpl);
	}
	
	function authortreecourse($tpl =  null){
		$app = JFactory::getApplication('site');
		$pid = $app->getUserStateFromRequest("pid", "pid", "0");
		$db = JFactory::getDBO();
		
		$filters = $this->get('filters');
		$this->assign("filters", $filters);

		$ads = $this->get('listDays');
		$this->assignRef('ads', $ads);
		
		$pagination = $this->get('Pagination');
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}
	
	function newstudent($tpl = null){
		$this->student = $this->get("Student");
		parent::display($tpl);
	}
	
	function authorquizzes($tpl =  null ) {
		$db = JFactory::getDBO();
		$author = $this->get('author');
		$this->assignRef('author', $author);
		
		$user =  $this->get('AuthorDetails');
		$this->assignRef("user",$user);
		
		$config =  $this->get('Config');
		$this->assignRef('config', $config);
		
		$myquizzes = $this->get('Items');
		$this->assignRef('myquizzes', $myquizzes);
		
		$this->pagination = $this->get('Pagination');
		
		parent::display($tpl);

	}
	
	function authormycourses($tpl =  null ) {
		$db = JFactory::getDBO();
		$author = $this->get('author');
		$this->assignRef('author', $author);
		$this->state = $this->get('State');
		
		$user =  $this->get('AuthorDetails');
		$this->assignRef("user",$user);
		
		$config =  $this->get('Config');
		$this->assignRef('config', $config);
		
		//$mycoursesth = $this->get('AuthorMyCourses');
		$mycoursesth = $this->get('Items');
		$this->assignRef('mycoursesth', $mycoursesth);
		
		$this->pagination = $this->get('Pagination');
		
		parent::display($tpl);
	}
	function authoraddeditmediacat($tpl =  null ){
		$this->categories = $this->get('Categories');
		$this->parent_id = $this->get('Parent');
		parent::display($tpl);
	}
	function parentMediaCategory($parent_id){
		$model = $this->getModel();
		$categ_db = $model->getAllRowsMediaCat(0, 0);
		
		$return = '';
		if(is_array($categ_db) && count($categ_db) == 0){
			$return .= '<select name="parent_id" class="input-large">';
			$return .= 		'<option value="">(0) '.JText::_("GURU_TOP").'</option>';		
			$return .= '</select>';
		}
		else{
			$return .= '<select name="parent_id" class="input-large">';
			$return .= 		'<option value="">(0) '.JText::_("GURU_TOP").'</option>';
			if(isset($categ_db) && count($categ_db) > 0){
				foreach($categ_db as $key=>$val){
					$val = (object)$val;
					$id = $val->id;
					$name = $val->name;
					$line = "";
					for($i=0; $i<$val->level; $i++){
						$line .= "&#151;";
					}
					$selected = "";
					if($parent_id == $id){
						$selected = 'selected="selected"';
					}
					$return .= '<option value="'.$id.'" '.$selected.'>'.$line."(".$val->level.") ".$name.'</option>';			
				}
			}	
			$return .= '</select>';		
		}			
		return $return;
	}
	function authoraddcourse($tpl =  null ){
		$lists = NULL;
		$positionA = "";
		$positionB = "";
		$db = JFactory::getDBO();
		$program = $this->get('Program');
		$isNew = ($program->id < 1);
		$text = $isNew?JText::_('New'):JText::_('Edit');
		$task = JRequest::getVar('task', '', 'get');
		
		
		$sql = "SELECT currency from #__guru_config where id=1";
		$db->setQuery($sql);
		$db->query();
		$currency = $db->loadColumn();
		$currency = $currency["0"];
		$currency = "GURU_CURRENCY_".$currency;
		$currency = JText::_(''.$currency.'');
		
		$sql = "SELECT currencypos from #__guru_config where id=1";
		$db->setQuery($sql);
		$db->query();
		$currencypos = $db->loadColumn();
		$currencypos = $currencypos["0"];
		if($currencypos == 0){
	    	$positionB = $currency."&nbsp;&nbsp;";
		}
		else{
			$positionA = "&nbsp;&nbsp;".$currency;
		}
		
		if($isNew){
			$mmediam = new stdClass();
			$mmediam_req = NULL;			
		} 
		else{
			$db->setQuery("SELECT a.*, b.* FROM `#__guru_mediarel` as a, `#__guru_media` as b WHERE a.type='pmed' AND a.media_id=b.id AND a.mainmedia=0 AND a.type_id=".$program->id." order by a.order asc");
			$mmediam = $db->loadObjectList();
			
			$db->setQuery("SELECT a.*, b.* FROM `#__guru_mediarel` as a, `#__guru_program` as b WHERE a.type='preq' AND a.media_id=b.id AND a.mainmedia=0 AND a.type_id=".$program->id);
			$mmediam_preq = $db->loadObjectList();
		}
       
        $price_formats = array(
			'1' => JText::_('GURU_PF1'),
			'2' => JText::_('GURU_PF2'),
			'3' => JText::_('GURU_PF3'),
			'4' => JText::_('GURU_PF4'),
			'5' => JText::_('GURU_PF5')
		);
		foreach ( $price_formats as $key => $pf ) {
			$pfs[] = JHTML::_( 'select.option', $key, $pf );
		}
		$lists['priceformat'] = '<label for="priceformat">' . JText::_( 'GURU_PRICE_FORMAT' ) . '</label>';
		$lists['priceformat'] .= JHTML::_( 'select.genericlist', $pfs, 'priceformat', ' id="priceformat" class="inputbox" size="1" ', 'value', 'text', $program->priceformat );
	
		$plans = $this->get('AllPlans');
    	
        $program_plans =  $this->get('ProgramPlans');
        
        $renewals = $plans;
        $program_renewals =  $this->get( 'ProgramRenewals' );
        
        $reminds = $this->get('AllReminds');
        $program_reminds = $this->get('ProgramReminds');
        $plains_html = "<table id='subscriptions'>";
		$plains_html .= "<tr style='background:#999'>
							<th width='1%' style='padding:0.5em;'>" . JText::_( 'GURU_DEFAULT' ) . "</th>
                            <th width='1%' style='padding:0.5em;'><input type='checkbox' id='splains' name='splains' value='' onclick='checkPlans(\"subscriptions\");'/><span class=\"lbl\"></span></th>
                            <th style='padding:0.5em;'>" . JText::_( 'GURU_NAME' ) . "</th>
                            <th style='padding:0.5em;'>" . JText::_( 'GURU_RTERMS' ) . "</th>
                            <th style='padding:0.5em;'>" . JText::_( 'GURU_PRICE' ) . "</th>
                        </tr>";
						
		if(is_array($plans))
		
		$k = 0;
		foreach($plans as $plain ) {
			$checked = false;
			$default = false;
			$price = "";
            if ( is_array($program_plans) ) {
                foreach ( $program_plans as $plain_value ) {
                    if ( $plain_value->plan_id == $plain->id ) {
                        $checked = true;
                        $price = $plain_value->price;
                        if ( $plain_value->default == 1 ) {
                            $default = true;
                        }
                    }
                }
            }
            
			$plains_html .= "<tr>";
			//$plains_html .= "<td style='padding:0.5em;'><input type='radio' name='subscription_default' value='" . $plain->id . "' " . (($default) ? "checked='checked'" : "") . "/><span class=\"lbl\"></span></td>";
			//ADDED BY JOSEPH BUCK 21/03/2015
			$plains_html .= "<td style='padding:0.5em;'><input type='radio' name='subscription_default' value='" . $plain->id . "' " . "checked='checked'" . "/><span class=\"lbl\"></span></td>";
			//END
			// $plains_html .= "<td style='padding:0.5em;'><input class='plain' type='checkbox' id='subscriptions_".$k."' name='subscriptions[]' value='" . $plain->id . "'" . (($checked) ? " checked='checked'" : "") . "/><span class=\"lbl\"></span></td>";
			//ADDED BY JOSEPH BUCK 24/03/2015
			$plains_html .= "<td style='padding:0.5em;'><input class='plain' type='checkbox' id='subscriptions_".$k."' name='subscriptions[]' value='" . $plain->id . "'" . " checked='checked'" . "/><span class=\"lbl\"></span></td>";
			//END
			$plains_html .= "<td style='padding:0.5em;'>" . $plain->name . "</td>";
            if ($plain->term != 0) {
                $zplain = $plain->term . ' ' . $plain->period;
            } else {
                $zplain = ucfirst(JText::_( 'GURU_UNLIMPROMO' ));
            }
            
			$plains_html .= "<td style='padding:0.5em;'>" . $zplain . "</td>";
			//$plains_html .= "<td style='padding:0.5em;'>".$positionB."<input type='text' id='subscription_price_".$k++."' name='subscription_price[" . $plain->id . "]' value='" . $price . "' />".$positionA."</td>";
			//ADDED BY JOSEPH BUCK 24/03/2015
			$plains_html .= "<td style='padding:0.5em;'>".$positionB."<input type='text' id='subscription_price_".$k++."' name='subscription_price[" . $plain->id . "]' value='15' />".$positionA."</td>";
			//END
			$plains_html .= "</tr>\n";
		}
		$plains_html .= "</table>";

        $plains_html2 = "<table id='renewals'>";
		$plains_html2 .= "<tr style='background:#999'>
							<th width='1%' style='padding:0.5em;'>" . JText::_( 'GURU_DEFAULT' ) . "</th>
                            <th width='1%' style='padding:0.5em;'><input type='checkbox' id='splains' name='splains' value='' onclick='checkPlans(\"renewals\");'/><span class=\"lbl\"></span></th>
                            <th style='padding:0.5em;'>" . JText::_( 'GURU_NAME' ) . "</th>
                            <th style='padding:0.5em;'>" . JText::_( 'GURU_RTERMS' ) . "</th>
                            <th style='padding:0.5em;'>" . JText::_( 'GURU_PRICE' ) . "</th>
                        </tr>";
		if(is_array($renewals))
		$k = 0;
		foreach($renewals as $plain ) {
			$checked = false;
			$default = false;
			$price = "";
            if ( is_array($program_renewals) ) {
                foreach ( $program_renewals as $plain_value ) {
                    if ( $plain_value->plan_id == $plain->id ) {
                        $checked = true;
                        $price = $plain_value->price;
                        if ( $plain_value->default == 1 ) {
                            $default = true;
                        }
                    }
                }
            }
            
			$plains_html2 .= "<tr>";
			$plains_html2 .= "<td style='padding:0.5em;'><input type='radio' name='renewal_default' value='" . $plain->id . "' " . (($default) ? "checked='checked'" : "") . "/><span class=\"lbl\"></span></td>";
			$plains_html2 .= "<td style='padding:0.5em;'><input class='plain' type='checkbox' id='renewals_".$k."' name='renewals[]' value='" . $plain->id . "'" . (($checked) ? " checked='checked'" : "") . "/><span class=\"lbl\"></span></td>";
			$plains_html2 .= "<td style='padding:0.5em;'>" . $plain->name . "</td>";
            if ($plain->term != 0) {
                $zplain = $plain->term . ' ' . $plain->period;
            } else {
                $zplain = ucfirst(JText::_( 'GURU_UNL
				MPROMO' ));
            }
            
			$plains_html2 .= "<td style='padding:0.5em;'>" . $zplain . "</td>";
			$plains_html2.= "<td style='padding:0.5em;'>".$positionB."<input type='text' id='renewal_price_".$k++."' name='renewal_price[" . $plain->id . "]' value='" . $price . "' />".$positionA."</td>";
			$plains_html2 .= "</tr>\n";
		}
		$plains_html2 .= "</table>";
        
        $plains_html3 = "<table id='emails'>";
		$plains_html3 .= "<tr style='background:#999'>
                            <th width='1%' style='padding:0.5em;'><input type='checkbox' id='semails' name='semails' value='' onclick='checkPlans(\"emails\")'/><span class=\"lbl\"></span></th>
                            <th style='padding:0.5em;'>" . JText::_( 'Name' ) . "</th>
                            <th style='padding:0.5em;'>" . JText::_( 'Terms' ) . "</th>
                        </tr>";
		if(is_array($reminds))
		foreach($reminds as $plain){
			$checked = false;
            if (is_array($program_reminds)) {
                foreach ( $program_reminds  as $plain_value ) {
                    if ( $plain_value->emailreminder_id == $plain->id )
                        $checked = true;
                }
            }

            $plain->term = JText::_('GURU_REM_EXP' . $plain->term);
            
			$plains_html3 .= "<tr>";
			$plains_html3.= "<td style='padding:0.5em;'><input class='plain' type='checkbox' name='reminders[]' value='" . $plain->id . "'" . (($checked) ? " checked='checked'" : "") . " onclick='javascript:guruCheckboxEmail(value)'/><span class=\"lbl\"></span></td>";
			$plains_html3 .= "<td style='padding:0.5em;'>" . $plain->name . "</td>";
			$plains_html3 .= "<td style='padding:0.5em;'>" . $plain->term . "</td>";
			$plains_html3 .= "</tr>\n";
		}
		$plains_html3 .= "</table>\n\n";
        
        $this->assign("plans", $plains_html);
        $this->assign("renewals", $plains_html2);
        $this->assign("emails", $plains_html3);

		$this->assign("program", $program);
		$this->assign("mmediam", $mmediam);
		if(!isset($mmediam_preq)) {$mmediam_preq=NULL;}
		$this->assign("mmediam_preq", $mmediam_preq);
		$this->assign("lists", $lists);	
		$gurudateformat = $this->get('DateFormat');
		$this->assignRef('gurudateformat', $gurudateformat);
		$config = $this->get('Configs'); 
		$this->assignRef('config', $config);
		parent::display($tpl);
	}
	function authoraddquizfe($tpl =  null){
		parent::display($tpl);
	}
	function listAuthors(){
		$db = JFactory::getDBO();
		$sql = "SELECT id, name FROM `#__users` where id in (select userid from #__guru_authors)";
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadAssocList();
		return $result;	
	}
	function list_all($search, $name, $category_id, $selected_categories=Array(), $size=1, $toplevel=true, $multiple=false) {

		$db = JFactory::getDBO();

		$q  = "SELECT parent_id FROM #__guru_categoryrel ";
		if( $category_id )
		$q .= "WHERE child_id ='$category_id'";
		$db->setQuery($q);   
		$db->query();
		$category_id=$db->loadResult();
		if (isset($_POST['catid'])) $category_id=intval($_POST['catid']);
		$multiple = $multiple ? "multiple=\"multiple\"" : "";
		
		$search_submit = '';
		if($search==1)
			$search_submit = " onchange=\"document.adminForm.submit()\" ";		

		
		echo "<select ".$search_submit." class=\"input-medium\" size=\"$size\" $multiple name=\"$name\">\n";
		if( $toplevel ) {
			echo "<option value=\"-1\">(0) Top</option>\n";
		}
		$this->list_tree($category_id, '0', '0', $selected_categories);
		echo "</select>\n";
	}
	
	function list_tree($category_id="", $cid='0', $level='0', $selected_categories=Array() ) {

		$db = JFactory::getDBO();
		$level++;

		$q = "SELECT id, child_id, ordering, name FROM #__guru_category,#__guru_categoryrel ";
		$q .= "WHERE #__guru_categoryrel.parent_id='$cid' ";
		$q .= "AND #__guru_category.id=#__guru_categoryrel.child_id ";
		$q .= "ORDER BY #__guru_category.ordering ASC";
		$db->setQuery($q);
		$allresults = $db->loadObjectList();

		foreach ($allresults as $child) {
			$selected = "";
			$child_id = $child->id;
			if ($child_id > 3) {
			if ($child_id != $cid) {
				if( $selected_categories==$child_id) {
					$selected = "selected=\"selected\"";
				}
				echo "<option $selected value=\"$child_id\">\n";
			}

		//ADDED BY JOSEPH 24/03/2015
		//ADDED IF CLAUSE
			
					for ($i=0;$i<$level;$i++) {
					echo "&#151;";
				}			
				echo "&nbsp;".$child->name."</option>";
			}
			
			$this->list_tree($category_id, $child_id, $level, $selected_categories);
		}
	}
	function getCourseListForStudents(){
		$program =$this->get('Program');
		$selected_course = $this->getSelectedCourse();
		$selected_course_final = explode ("|", $selected_course);
		$database =JFactory::getDBO();
		if($program->id < 1){
		$sql = "select id, name from #__guru_program ";
		}
		else{
		$sql = "select id, name from #__guru_program where id !=".$program->id;
		}
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadAssocList();
		$selected = "";
		if ($selected_course == "-1") {$selected = 'selected="selected"'; }
		$html = '<select name ="selected_course[]" id="selected_course[]" multiple="multiple">';
		if(isset($result) && is_array($result) && count($result) > 0){
			$html .= '<option value="-1" '.$selected.'>'.JText::_("GURU_ANY_COURSE").'</option>';
			foreach($result as $key=>$value){
				$selected = "";
				if(in_array($value["id"], $selected_course_final)){
					$selected = 'selected="selected"';
				}
				$html .= '<option value="'.$value["id"].'" '.$selected.' >'.$value["name"].'</option>';
			}
		}
		$html .= '</select>';
		echo $html;
	}
	
	function getSelectedCourse() {
		$program = $this->get('Program');
		$db = JFactory::getDBO();
		if($program->id !=NULL){
			$sql = "SELECT selected_course  FROM `#__guru_program` where id = ".$program->id;
			$db->setQuery($sql);
			$db->query();
			$result=$db->loadResult();
		}
		else{
			$result = "";
		}
		return $result;		
	
	}

	function authorGuruMenuBar(){
		$tmpl = JRequest::getVar("tmpl", "");
		if($tmpl == "component"){
			return "";
		}
		
		$g_my_profile_active = "";
		$g_my_courses_active = "";
		$g_my_students = "";
		$g_my_quizzes = "";
		$menu_g_my_profile_active = "";
		$menu_g_my_courses_active = "";
		$menu_g_my_students = "";
		$menu_g_my_quizzes = "";
		$menu_g_my_media = "";
		$menu_g_my_media_cat = "";
		$menu_g_my_commissions = "";
		
		$controller = JRequest::getVar("view", "");
		$layout = JRequest::getVar("layout", "");
		$task = JRequest::getVar("task", "");
		$config =  $this->get('Config');
		$Itemid = JRequest::getVar("Itemid", "0");
		
		//if($config->gurujomsocialprofilestudent == 1){
			//$link = "index.php?option=com_community&view=profile&task=edit&Itemid=".$Itemid;
		//}
		//else{
			$link = "index.php?option=com_guru&view=guruauthor&task=authorprofile&layout=authorprofile&Itemid=".$Itemid;
		//}
	
		if(trim($layout) == ""){
			$layout = $task;
		}
		if($controller == "guruauthor" && $layout == "authorprofile"){
			$g_my_profile_active = 'class="g_toolbar_active"';
			$menu_g_my_profile_active = 'selected="selected"';
		}
		elseif($controller == "guruauthor" && $layout == "authormycourses"){
			$g_my_courses_active = 'class="g_toolbar_active"';
			$menu_g_my_courses_active = 'selected="selected"';
		}
		elseif($controller == "guruauthor" && $layout == "authorquizzes"){
			$g_my_quizzes = 'class="g_toolbar_active"';
			$menu_g_my_quizzes = 'selected="selected"';
		}
		elseif($controller == "guruauthor" && $layout == "mystudents"){
			$g_my_students = 'class="g_toolbar_active"';
			$menu_g_my_students = 'selected="selected"';
		}
		elseif($controller == "guruauthor" && $layout == "authormymedia"){
			$g_my_media = 'class="g_toolbar_active"';
			$menu_g_my_media = 'selected="selected"';
		}
		elseif($controller == "guruauthor" && $layout == "authorcommissions"){
			$g_my_commissions = 'class="g_toolbar_active"';
			$menu_g_my_commissions = 'selected="selected"';
		}
		elseif($controller == "guruauthor" && $layout == "authormymediacategories"){
			$g_my_media_cat = 'class="g_toolbar_active"';
			$menu_g_my_media_cat = 'selected="selected"';
		}
		elseif($controller == "guruauthor" && $layout == "editMedia"){
			$g_my_media = 'class="g_toolbar_active"';
			$menu_g_my_media = 'selected="selected"';
		}
		elseif($controller == "guruauthor" && $layout == "editQuizFE"){
			$g_my_quizzes = 'class="g_toolbar_active"';
			$menu_g_my_quizzes = 'selected="selected"';
		}
		elseif($controller == "guruauthor" && $layout == "treeCourse"){
			$g_my_courses_active = 'class="g_toolbar_active"';
			$menu_g_my_courses_active = 'selected="selected"';
		}
		elseif($controller == "guruauthor" && $layout == "addCourse"){
			$g_my_courses_active = 'class="g_toolbar_active"';
			$menu_g_my_courses_active = 'selected="selected"';
		}
		
		
		
		
		
		$log_out = "";
		$user = JFactory::getUser();
		if($user->id > 0){
			$itemid = JRequest::getVar("Itemid", "0");
			$layout_come = JRequest::getVar("layout", "authormycourses");
			$return_url = base64_encode("index.php?option=com_guru&view=guruLogin&returnpage=".$layout_come."&Itemid=".intval($itemid));

		
			$log_out = '<li id="g_logout" class="g_hide_mobile logout-btn">
									<a href="index.php?option=com_users&task=user.logout&'.JSession::getFormToken().'=1&Itemid='.$Itemid.'&return='.$return_url.'"><i class="fa fa-sign-out"></i></a>
					   </li>';
		}
		//CHANGED BY JOSEPH 31/03/2015
		$return = '
			<!--<div class="g_toolbar guru_menubar g_hide_mobile" id="a_guru_menubar">
				<ul>
					<li id="g_my_profile">
						<a '.$g_my_profile_active.' href="'.JRoute::_("".$link."").'">
							<i class="fa fa-user"></i>&nbsp;'.JText::_('GURU_AUTHOR_PROFILE').'
						</a>
					</li>
					
					<li id="g_my_courses_active">
						<a '.$g_my_courses_active.' href="'.JRoute::_("index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses").'">
							<i class="fa fa-file-o"></i>&nbsp;'.JText::_('GURU_AUTHOR_MY_COURSE').'
						</a>
					</li>
					
					<li id="g_my_students">
						<a '.$g_my_students.' href="'.JRoute::_("index.php?option=com_guru&view=guruauthor&task=mystudents&layout=mystudents").'">
							<i class="fa fa-user"></i>&nbsp;'.JText::_('GURU_AUTHOR_MY_STUDENTS').'
						</a>
					</li>
					
					<li id="g_my_quizzes">
						<a '.$g_my_quizzes.' href="'.JRoute::_("index.php?option=com_guru&view=guruauthor&task=authorquizzes&layout=authorquizzes").'">
							<i class="fa fa-question-circle"></i>&nbsp;'.JText::_('GURU_AUTHOR_MY_QUIZZES').'
						</a>
					</li>';
					$return .= '
						<li id="g_my_media">
							<a '.@$g_my_media.' href="'.JRoute::_("index.php?option=com_guru&view=guruauthor&task=authormymedia&layout=authormymedia").'">
								<i class="fa fa-picture-o"></i>&nbsp;'.JText::_('GURU_AUTHOR_MY_MEDIA').'
							</a>
						</li>
					';
					$return .= '
						<li id="g_my_media">
							<a '.@$g_my_commissions.' href="'.JRoute::_("index.php?option=com_guru&view=guruauthor&task=authorcommissions&layout=authorcommissions").'">
								<i class="fa fa-money"></i>&nbsp;'.JText::_('GURU_COMMISSIONS').'
							</a>
						</li>
					';
					
					$return .= ''.$log_out.'
				</ul>
			</div>-->';
			$return .= '<!--<div id="guru_menubar_mobile" class="g_mobile g_select">
							<select name="menu_bar" onchange="document.location.href = this.value">
								<option '.$menu_g_my_profile_active.' value="'.$link.'">'.JText::_("GURU_AUTHOR_PROFILE").'</option>
								<option '.$menu_g_my_courses_active.' value="'.JRoute::_("index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses").'">'.JText::_("GURU_AUTHOR_MY_COURSE").'</option>
								<option '.$menu_g_my_students.' value="'.JRoute::_("index.php?option=com_guru&view=guruauthor&task=mystudents&layout=mystudents").'">'.JText::_("GURU_AUTHOR_MY_STUDENTS").'</option>
								<option '.$menu_g_my_quizzes.' value="'.JRoute::_("index.php?option=com_guru&view=guruauthor&task=authorquizzes&layout=authorquizzes").'">'.JText::_("GURU_AUTHOR_MY_QUIZZES").'</option>
								<option '.$menu_g_my_media.' value="'.JRoute::_("index.php?option=com_guru&view=guruauthor&task=authormymedia&layout=authormymedia").'">'.JText::_("GURU_AUTHOR_MY_MEDIA").'</option>
								<option '.$menu_g_my_commissions.' value="'.JRoute::_("index.php?option=com_guru&view=guruauthor&task=authorcommissions&layout=authorcommissions").'">'.JText::_("GURU_COMMISSIONS").'</option>
							</select>
						</div>-->';
		//END
		return $return;
	}
	
	function isTeacherOrNot(){
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$sql = "SELECT count(id) FROM `#__guru_authors` where userid = ".intval($user->id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result["0"];
	}
	
	function getCourseName($id){
		$db = JFactory::getDBO();
		$sql = "SELECT name FROM `#__guru_program` where id = ".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result["0"];
	}
	
	function selectedCoursesforFree(){
		$program = $this->get('Program');
		$db = JFactory::getDBO();
		$sql = "SELECT chb_free_courses  FROM `#__guru_program` where id = ".$program->id;
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}
	
	function getStepAccessCourses(){
		$program = $this->get('Program');
		$db = JFactory::getDBO();
		$sql = "SELECT step_access_courses  FROM `#__guru_program` where id = ".$program->id;
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadResult();
		return $result;	
	}
	
	function existing_ids ($ids){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT media_id FROM `#__guru_mediarel` WHERE type_id = ".$ids." AND type='pmed' ");
		$db->query();
		$existing_ids = $db->loadObjectList();
		return $existing_ids;
	}
	
	function preq_existing_ids ($ids){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT media_id FROM `#__guru_mediarel` WHERE type_id = ".$ids." AND type='preq' ");
		$db->query();
		$existing_ids = $db->loadObjectList();
		return $existing_ids;
	}
	
	function getConfigs(){
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__guru_config";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function getConfigsObject() {
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
	
	function getProgram($id){
		$database = JFactory::getDBO();
		$sql = "SELECT * FROM #__guru_program WHERE id = ".intval($id);
		$database->setQuery($sql);
		$result = $database->loadAssocList();
		return $result["0"];
	}
	
	function getProgramName ($dayid){
		$database = JFactory::getDBO();
		$sql = " SELECT name FROM #__guru_program WHERE id = (SELECT pid FROM #__guru_days WHERE id = ".$dayid." )"; 
		$database->setQuery($sql);
		$result = $database->loadResult();
		return $result;	
	}
	
	function getIDTasksForDay ($dayid){			
		$order_column = "ORDER BY `ordering` ASC";
		
		$database = JFactory::getDBO();
		$sql = "SELECT distinct media_id 
				FROM #__guru_mediarel lm
				LEFT JOIN #__guru_task lt
				ON lm.media_id=lt.id
				WHERE type='dtask' AND type_id = ".$dayid." ".$order_column;
		$database->setQuery($sql);
		$result = $database->loadColumn();
		return $result;
	}
	
	function getTask ($taskid){
		$database = JFactory::getDBO();
		$sql = " SELECT * FROM #__guru_task WHERE id = ".$taskid; 
		$database->setQuery($sql);
		$result = $database->loadObject();
		return $result;	
	}
	
	function select_layout ($pid){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT media_id FROM `#__guru_mediarel` WHERE type_id = ".intval($pid)." AND type='scr_l' ");
		$db->query();
		$layout_id = $db->loadResult();
		return $layout_id;
	}
	
	function getTaskType ($taskid) {
		$database = JFactory::getDBO();
		$sql = " SELECT type FROM #__guru_media WHERE id = (SELECT media_id FROM #__guru_mediarel WHERE type='task' AND type_id = ".intval($taskid)." )"; 
		$database->setQuery($sql);
		$result = $database->loadResult();
		return $result;		
	}
	
	function newModule($tpl = null){
		parent::display($tpl);
	}
	
	function editForm($tpl = null) {
		$app = JFactory::getApplication("site");

		$db = JFactory::getDBO();
		$program = $this->get('day'); 

		$this->assign("program", $program);		
		parent::display($tpl);
	}
	
	function getMediaType($id){
		$db = JFactory::getDBO();
		$sql = "select type from #__guru_media where id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}
	
	function getMediaName($id){
		$db = JFactory::getDBO();
		$sql = "SELECT name FROM #__guru_media WHERE id=".intval($id)." LIMIT 1";
		$db->setQuery($sql);
		$db->query();
		$existing_ids = $db->loadResult();
		return $existing_ids;
	}
	
	function vimeo($tpl = null) {
        $id = JRequest::getVar('id', '0');
        $this->assignRef('id', $id);
        parent::display($tpl);
    }
	
	function editLessonForm($tpl = null) {
		$_SESSION['addmed_tskmed_to_rep']=NULL;
		$db = JFactory::getDBO();
		$program = $this->get('Task');
		$jumps = $this->get('Jumps');
		$this->assign("jumps",$jumps);
		$isNew = ($program->id < 1);
		$text = $isNew?JText::_('GURU_NEW'):JText::_('GURU_EDIT');
		$advertiser_id = JRequest::getVar('advertiser_id', '', 'post');
		$task = JRequest::getVar('task', '', 'get');
		$mainmedia= '';
		$mainquiz= '';
		$the_layout = 1;
		$mmediam =  new stdClass();
		
		//JToolBarHelper::title(JText::_('GURU_TASK').":<small>[".$text."]</small>");
		//JToolBarHelper::save();
		if ($isNew) {
			//JToolBarHelper::cancel();
			$program->published=1;
		} else {
			//JToolBarHelper::apply();
			//JToolBarHelper::cancel ('cancel', JText::_('GURU_CLOSE_TASK_BTN'));
			$db->setQuery("SELECT a.*,b.* FROM `#__guru_mediarel` as a, `#__guru_media` as b WHERE a.media_id=b.id AND a.mainmedia=1 AND a.type_id=".$program->id);
			$mainmedia = $db->loadObjectList();
			
			$db->setQuery("SELECT a.*,b.* FROM `#__guru_mediarel` as a, `#__guru_media` as b WHERE a.type='task' AND a.media_id=b.id AND a.mainmedia=0 AND a.type_id=".$program->id);
			$mmediam = $db->loadObjectList();
			
			$db->setQuery("SELECT a.*,b.* FROM `#__guru_mediarel` as a, `#__guru_quiz` as b WHERE a.type='tquiz' AND a.media_id=b.id AND a.mainmedia=1 AND a.type_id=".$program->id);
			$mainquiz = $db->loadObjectList();	
			
			$db->setQuery("SELECT media_id FROM `#__guru_mediarel` WHERE type='scrnl' AND type_id=".$program->id);
			$the_layout = $db->loadResult();		
		}
		
		$lists="";
		
		$published = '<input type="hidden" name="published" value="0">';
		if($program->published == 1){
			$published .= '<input type="checkbox" checked="checked" value="1" class="ace-switch ace-switch-5" name="published">';
		}
		else{
			$published .= '<input type="checkbox" value="1" class="ace-switch ace-switch-5" name="published">';
		}
		$published .= '<span class="lbl"></span>';
		
		$lists['published'] = $published;
		
		//dificulty lists
		$dificulty[] = JHTML::_('select.option',  "none", JText::_('GURU_SELLEVEL'), 'value', 'option' );	
		$dificulty[] = JHTML::_('select.option',  "easy", JText::_('GURU_EASY'), 'value', 'option' );	
		$dificulty[] = JHTML::_('select.option',  "medium", JText::_('GURU_MEDIUM'), 'value', 'option' );	
		$dificulty[] = JHTML::_('select.option',  "hard", JText::_('GURU_HARD'), 'value', 'option' );		
		$javascript = '';
		
		if ($isNew) 
			$the_difficultylevel = 'easy';
		else
			$the_difficultylevel = $program->difficultylevel;
		
	  	$lists['difficulty']  =  JHTML::_( 'select.genericlist', $dificulty, 'difficultylevel', 'class="inputbox" size="1"'.$javascript,'value', 'option', $the_difficultylevel);
	  	//all media
	  	$db->setQuery("SELECT id,name FROM `#__guru_media` ORDER BY id DESC");
		$allmedia = $db->loadObjectList();
		//all quiz
	  	$db->setQuery("SELECT id,name FROM `#__guru_quiz` ORDER BY id DESC");
		$allquiz = $db->loadObjectList();
		/*---------------------------------------------*/
		$medias[] = JHTML::_('select.option',  "0", JText::_('Add Media Files'), 'id', 'name' );
		$medias 	= array_merge( $medias, $allmedia );
		$javascript = ' style="margin-top: 3px; margin-bottom: 3px;"';
	  	$lists['addmedias']  =  JHTML::_( 'select.genericlist', $medias, 'addmedia', 'multiple="multiple" class="inputbox" size="10"'.$javascript,'id', 'name', $program->difficultylevel);	
		/*---------------------------------------------*/
		$medias[] = JHTML::_('select.option',  "0", JText::_('Add Main Media'), 'id', 'name' );
		$medias 	= array_merge( $medias, $allmedia );
		$javascript = ' style="margin-top: 3px; margin-bottom: 3px;"';
	  	$lists['addmainmedias']  =  JHTML::_( 'select.genericlist', $medias, 'addmainmedia', 'class="inputbox" size="1"'.$javascript,'id', 'name', $program->difficultylevel);
	  	/*---------------------------------------------*/
		$quiz[] = JHTML::_('select.option',  "0", JText::_('Add Quiz'), 'id', 'name' );
		$quiz 	= array_merge( $quiz, $allquiz );
		$javascript = ' style="margin-top: 3px; margin-bottom: 3px;"';
	  	$lists['addquiz']  =  JHTML::_( 'select.genericlist', $medias, 'addquiz', 'class="inputbox" size="1"'.$javascript,'id', 'name', $program->difficultylevel);		
		//$javascript = ' style="display:none;"';
		
		if(isset($_SESSION['temp_lays'])&&($_SESSION['temp_lays']=='yes')){
			$db->setQuery("SELECT * FROM `#__guru_media_templay` WHERE ip='".ip2long($_SERVER['REMOTE_ADDR'])."'");
			$tem_lay = $db->loadObjectList();	
		} else { 
			$tem_lay=NULL; 
		}
		
	  	//all media
		$this->assign("tem_lay",$tem_lay);
	  	$this->assign("program", $program);
	  	$this->assign("mainmedia", $mainmedia);
		$this->assign("mainquiz", $mainquiz);
	  	$this->assign("mmediam", $mmediam);
		$this->assign("lists", $lists);
		$this->assign("the_layout", $the_layout);
		parent::display($tpl);
	}
	
	function addmedia ($tpl =  null ) { 
		$model = $this->getModel("guruAuthor");
		$medias = $this->get('listaddmedia');
		if(isset($_GET['type'])&&($_GET['type']=='audio')){
			foreach($medias as $element){
				$element->prevw = $model->parse_audio($element->id);
			}
		}
		$types = $this->get('distincttypes');
		$this->assignRef('types', $types);
		$this->assignRef('medias', $medias);
		
 		$pagination = $this->get( 'Pagination' );
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	}
	
	function addexercise($tpl =  null ) { 
		$db = JFactory::getDBO();
		$user = JFActory::getUser();
		$sql = "SELECT * FROM `#__guru_media` WHERE type in ('docs', 'file') AND author=".intval($user->id);
		if(isset($_POST['search_text']) && $_POST['search_text']!="")
			$sql = $sql." AND name LIKE '%".$_POST['search_text']."%' " ;
		if(isset($_POST['media_select']) && $_POST['media_select']!='all')
			$sql = $sql." AND type='".$_POST['media_select']."' ";	
		$db->setQuery($sql);
		$db->query();
		$medias = $db->loadObjectList();
		$this->assignRef('medias', $medias);

		parent::display($tpl);
	}
	
	function addQuiz ($tpl =  null ) { 
		$quiz = $this->get('listQuiz');
		$this->assignRef('quiz', $quiz);
		
 		$pagination = $this->get( 'Pagination' );
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	}
	
	function getAllMediaCategs(){
		$db = JFactory::getDBO();
		$sql = "select id, name from #__guru_media_categories";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function get_asoc_file_for_media($media_id)	{
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__guru_media WHERE id=".$media_id;
		$db->setQuery($sql);
		if (!$db->query()) {
			echo $db->stderr();
			return;
		}
		$the_media = $db->loadObject();			
		if(!empty($the_media )){
			if($the_media->source == 'local' || $the_media->type == 'image')
				$asoc_file = $the_media->local;
			else
				$asoc_file = '-';
		}
		else $asoc_file = '-';
		
		return 	$asoc_file;			
	}
	
	function getMediaCategoriesName(){
		$db = JFactory::getDBO();
		$sql = "select id, name from #__guru_media_categories";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	
	function real_quiz_id($media_id)	{
		$db = JFactory::getDBO();
		$sql = "SELECT source FROM #__guru_media WHERE id=".$media_id;
		$db->setQuery($sql);
		if (!$db->query()) {
			echo $db->stderr();
			return;
		}
		$the_media = $db->loadResult();		
		
		return 	$the_media;			
	}
	
	function select_media ($pid, $media_no, $layout=0){
		// m_type = scr_m for media
		$db = JFactory::getDBO();
		if($pid !=""){
			$db->setQuery("SELECT media_id FROM `#__guru_mediarel` WHERE type_id = ".$pid." AND type='scr_m' AND mainmedia='".$media_no."' AND layout=".$layout." ");
			$db->query();
			$media_id = $db->loadColumn();
		}
		return @$media_id[0];
	}
	
	function select_text ($pid, $text_no = NULL, $layout=0){
		$db = JFactory::getDBO();
		if(isset($text_no)) { $cond=" AND text_no = '".intval($text_no)."'";} else { $cond=NULL;}
		if($pid !=""){
			$db->setQuery("SELECT media_id,mainmedia FROM `#__guru_mediarel` WHERE type_id = ".$pid." AND type='scr_t' ".$cond." AND layout=".$layout);
		}
		$db->query();
		$media_obj = $db->loadObject();
		if(isset($media_obj))
			$media_id = @$media_obj->media_id.'$$$$$'.@$media_obj->mainmedia;
		else
			$media_id = 0;
		return $media_id;
	}
	
	function parse_media ($id, $layout_id){
		$model = $this->getModel("guruAuthor");
		$media = $model->parse_media($id, $layout_id);
		return $media;
	}
	
	function parse_txt($id){
		$model = $this->getModel("guruAuthor");
		$media = $model->parse_txt($id);
		return $media;
	}
	
	function parse_audio($id){
		$model = $this->getModel("guruAuthor");
		$media = $model->parse_audio($id);
		return $media;
	}
	
	function preview($tpl =  null){
		$media = $this->get("mainMedia");
		$this->assignRef('media',$media);
		
		$config =  $this->get('Config');
		$this->assignRef('config', $config);
		
		parent::display($tpl);
	}
	
	function editMediaForm($tpl =  null){
		$app = JFactory::getApplication('site');
		$db = JFactory::getDBO();
		
		$data = JRequest::get('post');
		$this->assign("data", $data);
		
		$media = $this->get('file');
		$this->assign("media", $media);
		
		$config = $this->get('config');
		$this->assign('config',$config);
		
		parent::display($tpl);
	}
	
	function authormymediacategories($tpl =  null){
		$mymediacat = $this->get('AuthorMyMediaCategories');
		$this->assign("mymediacat", $mymediacat);
		
		$config =  $this->get('Config');
		$this->assignRef('config', $config);
	
		$this->pagination = $this->get('Pagination');
	
		parent::display($tpl);
	}
	
	function authorcommissions($tpl =  null){
		$config =  $this->get('Config');
		$this->assignRef('config', $config);
		
		$authcomm =  $this->get('Authorcommissions');
		$this->assignRef('authcomm', $authcomm);
		
		$authcommpending =  $this->get('AuthorcommissionsPending');
		$this->assignRef('authcommpending', $authcommpending);
		
		$authcommpaid =  $this->get('AuthorcommissionsPaid');
		$this->assignRef('authcommpaid', $authcommpaid);
		
		$authpaymetoption =  $this->get('AuthorPaymetOption');
		$this->assignRef('authpaymetoption', $authpaymetoption);				
	
		$this->pagination = $this->get('Pagination');
	
		parent::display($tpl);
	}
	
	function now_selected_media ($mediaid){
		$db = JFactory::getDBO();
		if((isset($mediaid)) && ($mediaid != "")){
			$sql = "SELECT local FROM #__guru_media WHERE id = ".$mediaid;
			$db->setQuery($sql);
			if (!$db->query()) {
				echo $db->stderr();
				return;
			}
			$now_selected = $db->loadColumn();	
		}
		else{
			$now_selected = "";
		}	
		return @$now_selected["0"];
	}
	
	function parentCategory($categ_id){
		if($categ_id == NULL){
			if(isset($_SESSION["category_id"])){
				$categ_id = $_SESSION["category_id"];
			}
		}
		$model = $this->getModel();
		$categ_db = $model->getAllRows(0, 0);
				
		$return = '';
		if(is_array($categ_db) && count($categ_db) == 0){
			$return .= '<select name="category_id">';
			$return .= 		'<option value="0">'.JText::_("GURU_GENERAL").'</option>';		
			$return .= '</select>';
		}
		else{
			$return .= '<select name="category_id">';
			foreach($categ_db as $key=>$val){
				$val = (object)$val;
				$id = $val->id;
				$name = $val->name;
				$line = "";
				for($i=0; $i<$val->level; $i++){
					$line .= "&#151;";
				}
				$selected = "";
				if($categ_id == $id){
					$selected = 'selected="selected"';
				}
				$return .= '<option value="'.$id.'" '.$selected.'>'.$line."(".$val->level.") ".$name.'</option>';
			}
			$return .= '</select>';
		}
		return $return;
	}
	
	function displayArticleguru($id, $guruartname){
		$value = "";
		$document = JFactory::getDocument();
		$html = "";	
		$article = JTable::getInstance('content');															
		if($id != "0"){
			$value = $id;
		}	
		else{
			$value = NULL;
		}
		if($value){
			$article->load($value);
		}							
		else{
			if($id != 0){				
				$article->title = $guruartname;
			}
			else{ 
				$article->title = '';
			}	
		}

		$name = 'article';
		$link = JURI::root().'administrator/index.php?option=com_content&amp;task=element&amp;tmpl=component&amp;layout=modal&function=SelectArticleg"';
		JHTML::_('behavior.modal', 'a.modal');
		
		$html = "\n".'<div class="fltlft"><div id="updateTextAfterDelete"><input type="text" size="75" id="'.$name.'_name" value="'.htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('GURU_MEDIATYPEARTICLE2').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">'.JText::_('GURU_SELECT_CHANGE').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="'.$name.'id" name="'.$name.'id" value="'.(int)$value.'" />';
		
		echo $html;
	}
	
	function QuestionNo($id){
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
	
	function editQuiz($tpl = null) { 
		$app = JFactory::getApplication('site');		
		$db = JFactory::getDBO();		
		$program = $this->get('quiz'); 
		$value_option = JRequest::getVar("v");
		$media = $this->get('Media');
		$this->assign("max_reo", $media->max_reo);
		$this->assign("min_reo", $media->min_reo);
	   	$this->assign("mmediam", $media->mmediam);
		$this->assign("mainmedia", $media->mainmedia);
		$this->assign("program", $program);
		
		parent::display($tpl);
	}
	function editQuizFE($tpl = null) { 
		$app = JFactory::getApplication('site');		
		$db = JFactory::getDBO();		
		$program = $this->get('quiz'); 
		$value_option = JRequest::getVar("v");
		$media = $this->get('Media');
		
		$this->assign("media", $media);
		$this->assign("max_reo", $media->max_reo);
		$this->assign("min_reo", $media->min_reo);
	   	$this->assign("mmediam", $media->mmediam);
		$this->assign("mainmedia", $media->mainmedia);
		$this->assign("program", $program);
		$pagination = $this->get( 'Pagination' );
		$this->assignRef('pagination', $pagination);
		$gurudateformat = $this->get('DateFormat');
		$this->assignRef('gurudateformat', $gurudateformat);
		parent::display($tpl);
	}
	
	function addQuestion ($tpl =  null ) { 
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM `#__guru_questions`");
		$medias = $db->loadObjectList();
		$this->assignRef('medias', $medias);
		parent::display($tpl);
	}
	
	function id_for_last_question(){
		$db = JFactory::getDBO();
		$sql = "SELECT max(id) FROM #__guru_questions ";
		$db->setQuery($sql);
		if(!$db->query()){
			echo $db->stderr();
			return;
		}
		$id = $db->loadResult();
		return $id;	
	}
	
	function editQuestion ($tpl =  null ) { 
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM `#__guru_questions` WHERE id = ".$_GET['qid']." AND qid =".$_GET['cid']);
		$medias = $db->loadObject();
		$this->assignRef('medias', $medias);
		parent::display($tpl);
	}
	
	function jumpbts($tpl = null){
		$days = $this->get('listDaysJumps');
		$this->assignRef('days',$days);
		if(isset($_GET['id'])){
			$current = $this->get('CurrentJump');
		}
		else{
			$current = NULL;
		}
		$this->assign("current",$current);
		parent::display($tpl);
	}
	
	function getIDTasksForDayJump($dayid){
		$database = JFactory::getDBO();
		$sql = "SELECT media_id FROM #__guru_mediarel WHERE type='dtask' AND type_id = ".$dayid." ORDER BY `id` ASC ";
		$database->setQuery($sql);
		$result = $database->loadColumn();
		return $result;	
	}
	
	function getTask2($taskid){
		$database = JFactory::getDBO();
		$sql = " SELECT * FROM #__guru_task WHERE id = ".$taskid; 
		$database->setQuery($sql);
		$result = $database->loadObject();
		return $result;
	}
	
	function courseStats($tpl = null){
		$this->course_name = $this->get("CourseName");
		$this->total_students = $this->get("TotalStudentsByCourseId");
		$this->student_complete = $this->get("StudentCompleteByCourseId");
		$this->quizzes = $this->get("QuizzesByCourseId");
		$this->score = $this->get("ScoreByCourseId");
		$this->final_exam = $this->get("FinalExamByCourseId");
		$this->pass = $this->get("PassByCourseId");
		
		parent::display($tpl);
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
	
	function getMyCourses(){
		$db = JFactory::getDBO();
		$user = JFActory::getUser();
		$sql = "SELECT * FROM #__guru_program WHERE `author`=".intval($user->id);
		$db->setQuery($sql);
		$db->query();
		$courses = $db->loadAssocList();
		return $courses;
	}
	
	function quizzStats($tpl = null){
		$this->quizz_name = $this->get("QuizzName");
		$this->total_students = $this->get("TotalStudentsByQuizzId");
		$this->score_to_pass = $this->get("ScoreToPassByQuizzId");
		$this->avg_score = $this->get("AvgScoreByQuizzId");
		$this->students_pass = $this->get("StudentsPassByQuizzId");
		$this->students_failed = $this->get("StudentsFailedByQuizzId");
		
		parent::display($tpl);
	}
	function addQuizzes ($tpl =  null ) { 
		$db = JFactory::getDBO();		
		$user = JFActory::getUser();
		$search_text = JRequest::getVar('search_text', "");
		$sql = "SELECT id, name FROM `#__guru_quiz`";
		if($search_text!=""){
			$sql = $sql." where name LIKE '%".$search_text."%' and `is_final` <> 1 and `author`=".intval($user->id);
		}
		else{
			$sql = $sql." where `is_final` <> 1 and `author`=".intval($user->id);
		}
		$db->setQuery($sql);
		$list_quizzes=$db->loadAssocList();	
		$this->assignRef('list_quizzes',$list_quizzes);
		parent::display($tpl);
	}
	
	function studentdetails($tpl =  null){
		$this->details = $this->get("StudentDetails");
		parent::display($tpl);
	}
	
	function studentquizes($tpl =  null){
		$this->quizes = $this->get("StudentQuizes");
		parent::display($tpl);
	}
	
	function quizdetails($tpl =  null){
		$this->ads = $this->get("StudentQuizCompleted");
		parent::display($tpl);
	}
	
	function getQuizName($quiz_id){
		$db = JFactory::getDBO();
		$sql = "SELECT name FROM #__guru_quiz WHERE `id`=".intval($quiz_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}
	
	function getScoreQuiz($quiz_id, $user_id, $pid){
		$db = JFactory::getDBO();
		$sql = "SELECT `score_quiz` FROM #__guru_quiz_taken WHERE `user_id`=".intval($user_id)." and `quiz_id`=".intval($quiz_id)." and `pid`=".intval($pid)." order by id desc limit 0,1";
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadResult();		
		return $result;		
	}
	
	function getAnsGived($user_id, $quiz_id, $pid){
		$db = JFactory::getDBO();
		
		$sql = "SELECT is_final from #__guru_quiz where id=".$quiz_id;
		$db->setQuery($sql);
		$db->query();
		$isfinal=$db->loadResult();
		if($isfinal == 0){
			$sql = "SELECT q.`id`, `answers_gived` FROM #__guru_quiz_question_taken qq, #__guru_questions q  WHERE qq.question_id=q.id and user_id=".intval($user_id)." and show_result_quiz_id = (SELECT `id` FROM #__guru_quiz_taken WHERE `user_id`=".intval($user_id)." and `quiz_id`=".intval($quiz_id)." and `pid`=".intval($pid)." order by id desc limit 0,1) ORDER BY q.reorder";
		}
		else{
			$sql = "SELECT q.`id`, qq.`answers_gived` FROM #__guru_quiz_question_taken qq INNER JOIN #__guru_questions q ON( qq.question_id = q.id) WHERE qq.user_id=".intval($user_id)." and qq.show_result_quiz_id= (SELECT `id` FROM #__guru_quiz_taken WHERE `user_id`=".intval($user_id)." and `quiz_id`=".intval($quiz_id)." and `pid`=".intval($pid)." order by id desc limit 0,1) order by qq.question_order_no";
		}
		$db->setQuery($sql);
		$db->query();
		$result_ansgived = $db->loadObjectList("id");	
		return $result_ansgived;		
	}
	
	function getAnsRight($user_id, $quiz_id, $pid){
		$db = JFactory::getDBO();
		
		$sql = "SELECT is_final from #__guru_quiz where id=".$quiz_id;
		$db->setQuery($sql);
		$db->query();
		$isfinal=$db->loadResult();
		if($isfinal == 0){
			$sql = "SELECT `id`, `answers` FROM #__guru_questions WHERE qid=".intval($quiz_id). " ORDER BY `reorder` ";
		}
		else{
			$sql = "SELECT w1.`id`, w1.`answers` FROM #__guru_questions w1 INNER JOIN #__guru_quiz_question_taken w2 ON (w1.id= w2.question_id)  WHERE w2.show_result_quiz_id= (SELECT `id` FROM #__guru_quiz_taken WHERE `user_id`=".intval($user_id)." and `quiz_id`=".intval($quiz_id)." and `pid`=".intval($pid)." order by id desc limit 0,1) order by question_order_no";
		}
		$db->setQuery($sql);
		$db->query();
		$result_ansright = $db->loadObjectList("id");
		return $result_ansright;
	}
	
	function getQuestionName($user_id, $pid, $quiz_id){
		$db = JFactory::getDBO();
		$sql = "SELECT is_final from #__guru_quiz where id=".$quiz_id;
		$db->setQuery($sql);
		$db->query();
		$isfinal=$db->loadResult();
		
		$sql = "SELECT show_nb_quiz_select_up from #__guru_quiz where id=".$quiz_id;
		$db->setQuery($sql);
		$db->query();
		@$show_nb_quiz_select_up=$db->loadResult();
		
		if($isfinal == 0 && @$show_nb_quiz_select_up ==1){
			$sql = "SELECT `id`, `text` FROM #__guru_questions WHERE qid=".intval($quiz_id)." ORDER BY reorder";
		}
		elseif($isfinal == 0 && @$show_nb_quiz_select_up ==0){
			$sql = "SELECT  q1.`id`, q1.`text`, q2.`question_order_no`  FROM #__guru_questions q1 INNER JOIN #__guru_quiz_question_taken q2 ON (q1.id = q2.question_id)  WHERE q1.qid=".intval($quiz_id)." and q2.show_result_quiz_id=(SELECT `id` FROM #__guru_quiz_taken WHERE `user_id`=".intval($user_id)." and `quiz_id`=".intval($quiz_id)." and `pid`=".intval($pid)." order by id desc limit 0,1) order by q2.question_order_no";
		}
		else{
			$sql = "SELECT q1.`id`, q1.`text` FROM #__guru_questions q1 INNER JOIN #__guru_quiz_question_taken q2 ON (q1.id = q2.question_id)  WHERE  q2.show_result_quiz_id= (SELECT `id` FROM #__guru_quiz_taken WHERE `user_id`=".intval($user_id)." and `quiz_id`=".intval($quiz_id)." and `pid`=".intval($pid)." order by id desc limit 0,1) order by q2.question_order_no";
		}
		$db->setQuery($sql);
		$db->query();
		$result_question = $db->loadObjectList("id");
	    return $result_question; 
	}
	
	function getAllAns($quiz_id, $user_id, $pid){
		$db = JFactory::getDBO();
		$sql = "SELECT is_final from #__guru_quiz where id=".intval($quiz_id);
		$db->setQuery($sql);
		$db->query();
		$isfinal = $db->loadColumn();
		$isfinal = $isfinal[0];
		if($isfinal == 1){
			$sql = "SELECT question_id FROM #__guru_quiz_question_taken WHERE show_result_quiz_id=(SELECT `id` FROM #__guru_quiz_taken WHERE `user_id`=".intval($user_id)." and `quiz_id`=".intval($quiz_id)." and `pid`=".intval($pid)." order by id desc limit 0,1) order by question_order_no";
		}
		else{
			$sql = "SELECT question_id FROM #__guru_quiz_question_taken qq, #__guru_questions q  WHERE qq.question_id=q.id and show_result_quiz_id=(SELECT `id` FROM #__guru_quiz_taken WHERE `user_id`=".intval($user_id)." and `quiz_id`=".intval($quiz_id)." and `pid`=".intval($pid)." order by id desc limit 0,1) ORDER BY reorder"; 
		}	
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadObjectList();
		$i = 0;
		$result_allans = array();
		
		foreach($result as $key=>$value){
			if($isfinal == 0){
				$sql = "SELECT `a1`, `a2`,`a3`,`a4`,`a5`,`a6`,`a7`,`a8`,`a9`,`a10` FROM #__guru_questions WHERE qid=".intval($quiz_id)." and id=".$value->question_id;
			}
			else{
				$sql = "SELECT `a1`, `a2`,`a3`,`a4`,`a5`,`a6`,`a7`,`a8`,`a9`,`a10` FROM #__guru_questions WHERE id=".$value->question_id;
			}
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
	
	function getAllAnsText($quiz_id, $user_id, $pid){
		$db =JFactory::getDBO();
		$sql = "SELECT is_final from #__guru_quiz where id=".intval($quiz_id);
		$db->setQuery($sql);
		$db->query();
		$isfinal = $db->loadColumn();
		$isfinal = $isfinal[0];
		
		if($isfinal == 0){
			$sql = "SELECT question_id FROM #__guru_quiz_question_taken qq, #__guru_questions q  WHERE qq.question_id=q.id and show_result_quiz_id=(SELECT `id` FROM #__guru_quiz_taken WHERE `user_id`=".intval($user_id)." and `quiz_id`=".intval($quiz_id)." and `pid`=".intval($pid)." order by id desc limit 0,1) ORDER BY reorder";
		}
		else{
			$sql = "SELECT question_id FROM #__guru_quiz_question_taken WHERE show_result_quiz_id=(SELECT `id` FROM #__guru_quiz_taken WHERE `user_id`=".intval($user_id)." and `quiz_id`=".intval($quiz_id)." and `pid`=".intval($pid)." order by id desc limit 0,1) order by question_order_no";
		}
		$db->setQuery($sql);
		$db->query();
		$result=$db->loadObjectList();
		$i = 0;
		$result_allans = array();
		
		foreach($result as $key=>$value){
			if($isfinal == 0){
				$sql = "SELECT `a1`, `a2`,`a3`,`a4`,`a5`,`a6`,`a7`,`a8`,`a9`,`a10` FROM #__guru_questions WHERE qid=".intval($quiz_id)." and id=".$value->question_id;
			}
			else{
				$sql = "SELECT `a1`, `a2`,`a3`,`a4`,`a5`,`a6`,`a7`,`a8`,`a9`,`a10` FROM #__guru_questions WHERE id=".$value->question_id;
			
			}
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
	
	function terms($tpl = null) {
		$db = JFactory::getDBO();
		$sql = "select * from #__guru_config";
		$db->setQuery($sql);
		$db->query();
		$configs = $db->loadAssocList();
		$this->assignRef('configs', $configs);
		
		parent::display($tpl);
	}
	
	function userName($id){
		$name = "";
		$db = JFactory::getDBO();
		$sql = "select `firstname`, `lastname` from #__guru_customer where `id`=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		if(isset($result) && count($result) > 0){
			$name = $result["0"]["firstname"]." ".$result["0"]["lastname"];
		}
		return $name;
	}
	
	function userEmail($id){
		$name = "";
		$db = JFactory::getDBO();
		$sql = "select `email` from #__users where `id`=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadcolumn();
		$result = @$result["0"];
		return $result;
	}
	function getDetailsPagination(){
		$pagination = $this->get('Pagination');
		return $pagination;
	}
	
	function authorcommissions_details_paid($tpl =  null){
		$details_paid =  $this->get('PendingDetails');
		$this->assignRef('details_paid', $details_paid);
		
		$config = $this->get('Config');
		$this->assignRef('config', $config);
		
		parent::display($tpl);
	}
	
};
	
?>