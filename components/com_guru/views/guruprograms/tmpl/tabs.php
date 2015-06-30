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
include(JPATH_SITE.DS.'components'.DS.'com_guru'.DS.'models'.DS.'gurutask.php');
$document = JFactory::getDocument();
$program= $this->program;
$itemid = JRequest::getVar("Itemid", "0");
$guruHelper = new guruHelper();
$document->addScriptDeclaration('
	document.onreadystatechange = function(){
		initPhoneTabs();
	}
');

?>
<style type="text/css">
  .accordionItem.hideTabs div { display: none; }
  div.guru-content .btn{
  	height:27px;!important; 
  }
</style>

<script>
var modalWindow = {
    parent:"body",
    windowId:null,
    content:null,
    width:null,
    height:null,
    close:function()
    {
        jQuery(".modal-window").remove();
        jQuery(".modal-overlay").remove();
    },
    open:function()
    {
        var modal = "";
        modal += "<div class=\"modal-overlay\"></div>";
        modal += "<div id=\"" + this.windowId + "\" class=\"modal-window modal g_modal\" style=\" display:block!important;\">";
        modal += this.content;
        modal += "</div>";  
        jQuery(this.parent).append(modal);
        jQuery(".modal-window").append("<a id=\"close-window\" class=\"close-window\"></a>");
        jQuery(".close-window").click(function(){modalWindow.close();});
        jQuery(".modal-overlay").click(function(){modalWindow.close();});
    }
};
  var openMyModal = function(lesson_h, lesson_w, source)  
{  
    modalWindow.windowId = "myModal";  
    modalWindow.height = lesson_h;  
    modalWindow.width = '100%';  
    modalWindow.content = "<iframe id='g_lesson_pop' class='g_leesson_popup' src='" + source + "'></iframe>";  
    modalWindow.open();  
};  
function changeGuruTab(tab){
	tabs = new Array("tab1", "tab2", "tab3", "tab4", "tab5", "tab6");
	for(i=0; i<tabs.length; i++){
		if(tab == tabs[i]){
			if(eval(document.getElementById(tabs[i]))){
				var temp = "li-"+tabs[i];
				document.getElementById(tabs[i]).style.display = "block";
				if(eval(document.getElementById(temp))){
					document.getElementById(temp).className="ui-tabs-active";
				}
			}
		}
		else{
			if(eval(document.getElementById(tabs[i]))){
				var temp = "li-"+tabs[i];
				document.getElementById(tabs[i]).style.display = "none";
				if(eval(document.getElementById(temp))){
					document.getElementById(temp).className="";
				}
			}
		}
	}
}
	var accordionItems = new Array();

    function initPhoneTabs() {
      // Grab the accordion items from the page
      var divs = document.getElementsByTagName( 'div' );
      for ( var i = 0; i < divs.length; i++ ) {
        if ( divs[i].className == 'accordionItem' ) accordionItems.push( divs[i] );
      }

      // Assign onclick events to the accordion item headings
      for ( var i = 0; i < accordionItems.length; i++ ) {
        var h3 = getFirstChildWithTagName( accordionItems[i], 'H3' );
        h3.onclick = toggleItem;
      }

      // Hide all accordion item bodies except the first
      for ( var i = 1; i < accordionItems.length; i++ ) {
        accordionItems[i].className = 'accordionItem hideTabs';
      }
    }

    function toggleItem() {
      var itemClass = this.parentNode.className;

      // Hide all items
      for ( var i = 0; i < accordionItems.length; i++ ) {
        accordionItems[i].className = 'accordionItem hideTabs';
      }

      // Show this item if it was previously hidden
      if ( itemClass == 'accordionItem hideTabs' ) {
        this.parentNode.className = 'accordionItem';
      }
    }

    function getFirstChildWithTagName( element, tagName ) {
      for ( var i = 0; i < element.childNodes.length; i++ ) {
        if ( element.childNodes[i].nodeName == tagName ) return element.childNodes[i];
      }
    }

</script>

<?php
if($program->metatitle =="" || $program->metadesc =="" ){
    $document = JFactory::getDocument();
    $document->setTitle($program->name);
    $document->setMetaData('keywords', $program->name);
    $document->setMetaData('description', strip_tags($program->description));
}


$document->setMetaData( 'viewport', 'width=device-width, initial-scale=1.0' );
?>
<?php
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
function isCustomer(){
    $db = JFactory::getDBO();
    $my = JFactory::getUser();
    $user_id = $my->id;
	$course_id = intval(JRequest::getVar("cid", 0));
    $sql = "select count(*) from #__guru_buy_courses bc, #__guru_order o, #__guru_customer c where bc.userid=".intval($user_id)." and o.id = bc.order_id and bc.course_id=".intval($course_id)." and o.userid=".intval($user_id)." and c.id=".intval($user_id)." and o.status='Paid'" ;
    $db->setQuery($sql);
    $db->query();
    $result = $db->loadColumn();
    $result = @$result["0"];
    if($result > 0){
        $sql = "select `block` from #__users where `id`=".intval($user_id);
        $db->setQuery($sql);
        $db->query();
        $result = $db->loadColumn();
        $result = @$result["0"];
        if($result == 0){
            return true;
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}
function inCustomerTable(){
    $db = JFactory::getDBO();
    $my = JFactory::getUser();
    $user_id = $my->id;
    $sql = "select count(*) from  #__guru_customer where id=".intval($user_id);
    $db->setQuery($sql);
    $db->query();
    $result = $db->loadColumn();
    $result = @$result["0"];
    
    if($result > 0){
        $sql = "select `block` from #__users where `id`=".intval($user_id);
        $db->setQuery($sql);
        $db->query();
        $result = $db->loadColumn();
        $result = @$result["0"];
        
        if($result == 0){
            return true;
        }
        else{
            return false;
        }
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
function createButton($buy_background, $course_id, $buy_class, $program, $program_content){
    $db = JFactory::getDBO();
    $my = JFactory::getUser();
    $user_id = $my->id;
    $itemid = JRequest::getVar("Itemid", "0");
    $expired = false;
    $sql = "select `expired_date` from #__guru_buy_courses where userid=".intval($user_id)." and course_id=".intval($course_id);
    $db->setQuery($sql);
    $db->query();
    $expired_date_string = $db->loadColumn();
    $expired_date_string = @$expired_date_string["0"];
    
    $not_show = false;
    $current_date_string = "";
    $sql = "select bc.id from #__guru_buy_courses bc, #__guru_order o where bc.userid=".intval($user_id)." and bc.course_id=".intval($course_id)." and (bc.expired_date >= now() or bc.expired_date = '0000-00-00 00:00:00') and bc.order_id = o.id and o.status <> 'Pending'";
    $db->setQuery($sql);
    $db->query();
    $result = $db->loadColumn();
    $result = @$result["0"];
    if(($expired_date_string != "0000-00-00 00:00:00") || (!isset($result) || intval($result) == 0)){
        $expired_date_int = strtotime($expired_date_string);
        $jnow = JFactory::getDate();
        $current_date_string = $jnow->toSQL();
        $current_date_int = strtotime($current_date_string);
        $renew = "false";
        if($current_date_int < $expired_date_int){
            $renew = "true";
        }
        $sql = "select bc.course_id from #__guru_buy_courses bc, #__guru_order o where o.id=bc.order_id and bc.userid=".intval($user_id)." and o.status='Paid'";
        $db->setQuery($sql);
        $db->query();
        $my_courses = $db->loadColumn();
        if(in_array($course_id, $my_courses) && $renew){ // I bought this course
            @$difference_int = get_time_difference($current_date_int, $expired_date_int);
            $difference = $difference_int["days"]." ".JText::_("GURU_REAL_DAYS");
            if($difference_int["days"] == 0){
                if($difference_int["hours"] == 0){
                    if($difference_int["minutes"] == 0){
                        $difference = "0";
                    }
                    else{
                        $difference = $difference_int["minutes"]." ".JText::_("GURU_REAL_MINUTES");
                    }
                }
                else{
                    $difference = $difference_int["hours"]." ".JText::_("GURU_REAL_HOURS");
                }
            }
            if($expired_date_string == "0000-00-00 00:00:00"){//unlimited
                $difference_int = "1"; //default for unlimited
            }
            
            if($difference_int !== FALSE){// is not expired
                $not_show = true;
            }
            else{
                $return .= '<input type="button" class="btn btn-warning" onclick="document.location.href=\''.str_replace("&amp;", "&", JRoute::_("index.php?option=com_guru&view=guruPrograms&task=buy_action&course_id=".$course_id."&Itemid=".$itemid)).'\';" value="'.JText::_("GURU_BUY_NOW").'" name="Buy" />';
                $expired = true;
            }
        }
        else{
            $return .= '<input type="button" class="btn btn-warning" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruPrograms&task=buy_action&course_id=".$course_id."&Itemid=".$itemid).'\';" value="'.JText::_("GURU_BUY_NOW").'" name="Buy" />';
        }
        $return .= '<div>
                    <div class="g_action_msg">'.JText::_("GURU_ACCESS_BUT_BUTTON")."&nbsp;&nbsp;";
        $return .= '</div>
                    </div>';
    }
    else{//not show the button
        $not_show = true;
    }
    $sql = "SELECT chb_free_courses, step_access_courses, selected_course  FROM `#__guru_program` where id = ".intval($course_id);
    $db->setQuery($sql);
    $db->query();
    $result= $db->loadAssocList();
    $chb_free_courses = $result["0"]["chb_free_courses"];
    $step_access_courses = $result["0"]["step_access_courses"];
    $selected_course = $result["0"]["selected_course"];
    if($chb_free_courses == 1){
        $sql = "SELECT  count(*) FROM `#__guru_buy_courses` where `order_id` >='0' and `userid`=".intval($user_id)." and course_id=".intval($course_id);
        $db->setQuery($sql);
        $db->query();
        $result = $db->loadColumn();
        $result = @$result["0"];
        
        if($result > 0 ){
            $sql = "select `block` from #__users where `id`=".intval($user_id);
            $db->setQuery($sql);
            $db->query();
            $result = $db->loadColumn();
            $result = @$result["0"];

            if($result != 0 ||  $result == NULL ){
                $not_show = false;
            }
            else{
                $not_show = true;
            }
        }
        else{
            $not_show = false;
        }
    }
    
    if($difference_int == FALSE && $expired_date_string != "0000-00-00 00:00:00"){
        $is_expired_true =true;
    }
    else{
        $is_expired_true = false;
    }
    if($not_show && ($chb_free_courses == 0 || ($chb_free_courses == 1 && $step_access_courses == 1) || ($chb_free_courses == 1 && $step_access_courses == 0  && $selected_course != -1 && isCustomer()) || ($chb_free_courses == 1 && $step_access_courses == 0  && $selected_course == -1 && hasAtLeastOneCourse()) || ($chb_free_courses == 1 && $step_access_courses == 0  && $selected_course != -1 && buySelectedCourse($selected_course)))){
        $return = array("0"=>"");
        if(isset($program_content) && count($program_content) > 0){
            $module_id = $program_content["0"]["id"];
            $lessons = guruModelguruProgram::getSubCategory($module_id);
            $lesson_name = "";
            if(isset($lessons) && count($lessons) > 0){
                $lesson_name = $lessons["0"]["name"];
            }
            if($is_expired_true == false){
                $return["0"] = '<div><div><p>'.JText::_("GURU_WELCOME_TO").' "'.$program->name.'" '.JText::_("GURU_COURSE_FROM_PHRASE").'! '.JText::_("GURU_PLEASE_GET_STARTED").' "'.$lesson_name.'" '.JText::_("GURU_BELOW").'</p></div></div>';
            }
            else{
                $return["0"] = '<div><div>'.JText::_("GURU_EXPIRED_TEXT1")." ".'<a href="'.JRoute::_('index.php?option=com_guru&controller=guruOrders&task=renew&course_id='.$course_id).'">'.JText::_("GURU_EXPIRED_TEXT2").'</a>'." ".JText::_("GURU_EXPIRED_TEXT3").'</div></div>';
            }
        }
    }
    else{
        if($chb_free_courses == 1){//checked
            if($step_access_courses == 0 && !$expired){// Students
                if($selected_course == '-1'){// any course
                    if($user_id == 0){//not logged
                        $return = ' <div class="g_cource_enrole_options">
                                        <div class="clearfix">
                                            <div >'.JText::_("GURU_FREE_ALL_STUDENTS").'&nbsp;&nbsp;
                                                <input type="button" class="btn btn-warning" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
                                            </div>
                                        </div>
                                        <div class="clearfix">  
                                            <div>'.JText::_("GURU_NOT_A_STUDENT").'&nbsp;&nbsp;
                                                <input type="button" class="btn btn-warning" onclick="document.location.href=\''.str_replace("&amp;", "&",JRoute::_("index.php?option=com_guru&view=guruPrograms&task=buy_action&course_id=".$course_id."&Itemid=".$itemid)).'\';" value="'.JText::_("GURU_BUY_NOW").'" name="Buy" />
                                            </div>
                                        </div>                                  
                                    </div>';
                    }
                    else{
                        if(hasAtLeastOneCourse()){
                            $return = ' <div>
                                        <div>
                                            <div class="'.$span10.' '.$buy_background.'">'.JText::_("GURU_FREE_ALL_STUDENTS_LOGGIN").'&nbsp;&nbsp;
                                                <input type="button" class="btn btn-warning" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
                                            </div>
                                        </div>                              
                                    </div>';
                        }
                    }
                }
                else{// selected courses
                    if($user_id == 0){// not logged
                        $selected_course_final = explode('|', $selected_course);
                        foreach($selected_course_final as $key=>$value){
                            if(trim($value) == ""){
                                unset($selected_course_final[$key]);
                            }
                        }
                        $db = JFactory::getDBO();
                        $sql = "select name, id from #__guru_program where id in (".implode(", ", $selected_course_final).")";
                        $db->setQuery($sql);
                        $db->query();
                        $result = $db->loadAssocList();
                        $all_title = array();
                        $itemid = JRequest::getVar("Itemid", "0");
                        if(isset($result) && count($result) > 0){
                            foreach($result as $key=>$course){
                                $all_title[] = '<a href="'.JRoute::_("index.php?option=com_guru&view=guruPrograms&layout=view&cid=".$course["id"]."&Itemid=".$itemid).'">'.$course["name"].'</a>';
                            }
                        }
                        $all_title = implode(", ", $all_title);
                        $not_show = false;
                        $return = '<div class="g_cource_enrole_options">
                                        <div>
                                            <div class=" '.$span10.' '.$buy_background.' list_courses">'.JText::_("GURU_FREE_STUDENTS_SOME_COURSES").'<br/>
                                                '.$all_title.'
                                            </div>
                                        </div>
                                        <div class="clearfix">  
                                            <div class=" '.$span10.' '.$buy_background.'">'.JText::_("GURU_STUDENT_ANY_OF_COURSE").'&nbsp;&nbsp;
                                                <input type="button" class="btn btn-warning" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
                                            </div>
                                        </div>
                                        <div class="clearfix">  
                                            <div>'.JText::_("GURU_NOT_A_STUDENT").'&nbsp;&nbsp;
                                                <input type="button" class="btn btn-warning" onclick="document.location.href=\''.str_replace("&amp;", "&",JRoute::_("index.php?option=com_guru&view=guruPrograms&task=buy_action&course_id=".$course_id."&Itemid=".$itemid)).'\';" value="'.JText::_("GURU_BUY_NOW").'" name="Buy" />
                                            </div>
                                        </div>
                                    </div>';
                    }
                    else{
                        if(buySelectedCourse($selected_course)){
                            $selected_course_final = explode('|', $selected_course);
                            foreach($selected_course_final as $key=>$value){
                                if(trim($value) == ""){
                                    unset($selected_course_final[$key]);
                                }
                            }
                            $db = JFactory::getDBO();
                            $sql = "select name, id from #__guru_program where id in (".implode(", ", $selected_course_final).")";
                            $db->setQuery($sql);
                            $db->query();
                            $result = $db->loadAssocList();
                            $all_title = array();
                            $itemid = JRequest::getVar("Itemid", "0");
                            if(isset($result) && count($result) > 0){
                                foreach($result as $key=>$course){
                                    $all_title[] = '<a href="'.JRoute::_("index.php?option=com_guru&view=guruPrograms&layout=view&cid=".$course["id"]."&Itemid=".$itemid).'">'.$course["name"].'</a>';
                                }
                            }
                            $all_title = implode(", ", $all_title);
                            $return = '<div >
                                            <div>
        
                                                <div class="list_courses">'.JText::_("GURU_STUDENT_SOME_COURSE").'
        
                                                </div>
        
                                            </div>
        
                                            <div>
                                                <div class="'.$buy_background.'">
        
                                                    '.$all_title.'&nbsp;&nbsp;&nbsp;
        
                                                    <input type="button" class="btn btn-warning" onclick=" document.adminForm.task.value=\'enroll\'; document.adminForm.submit();" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
        
                                                </div>
        
                                            </div>
        
                                        </div>';
                        }
                        //}
                    }
                }
            }
            elseif($step_access_courses == 1){// Members
                $return = '<div>
                                <div>
                                    <div>'.JText::_("GURU_FREE_MEMBERS").'&nbsp;&nbsp;
                                        <input type="button" class="btn btn-warning" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
                                    </div>
                                </div>
                            </div>';
            }
            elseif($step_access_courses == 2){// Guest
                $return = ' <div >
                                <div>
                                    <div>'.JText::_("GURU_FREE_GUEST").'</div>
                                </div>
                            </div>';
            }
        }
    }
    return $return;
}
function createButtonB($buy_background, $course_id, $buy_class, $program, $program_content){
    $db = JFactory::getDBO();
    $my = JFactory::getUser();
    $user_id = $my->id;
    $itemid = JRequest::getVar("Itemid", "0");
    $expired = false;
    $sql = "select expired_date from #__guru_buy_courses where userid=".intval($user_id)." and course_id=".intval($course_id);
    $db->setQuery($sql);
    $db->query();
    $expired_date_string = $db->loadResult();
    $not_show = false;
    $current_date_string = "";
    $sql = "select bc.id from #__guru_buy_courses bc, #__guru_order o where bc.userid=".intval($user_id)." and bc.course_id=".intval($course_id)." and (bc.expired_date >= '".$current_date_string."' or bc.expired_date = '0000-00-00 00:00:00') and bc.order_id = o.id and o.status <> 'Pending'";
    $db->setQuery($sql);
    $db->query();
    $result = $db->loadResult();
    if(($expired_date_string != "0000-00-00 00:00:00") || (!isset($result) || intval($result) == 0)){
        $expired_date_int = strtotime($expired_date_string);
        $jnow = JFactory::getDate();
        $current_date_string = $jnow->toSQL();
        $current_date_int = strtotime($current_date_string);
        $renew = "false";
        if($current_date_int < $expired_date_int){
            $renew = "true";
        }
        $sql = "select bc.course_id from #__guru_buy_courses bc, #__guru_order o where o.id=bc.order_id and bc.userid=".intval($user_id)." and o.status='Paid'";
        $db->setQuery($sql);
        $db->query();
        $my_courses = $db->loadColumn();
        $detect = new Mobile_Detect;
        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
        if($deviceType != "phone"){
            $span10 = "span10";
        }
        $return = '<div>
                            <div>'.JText::_("GURU_ACCESS_BUT_BUTTON")."&nbsp;&nbsp;";
        if(in_array($course_id, $my_courses) && $renew){ // I bought this course

            $difference_int = get_time_difference($current_date_int, $expired_date_int);
            $difference = $difference_int["days"]." ".JText::_("GURU_REAL_DAYS");
            if($difference_int["days"] == 0){
                if($difference_int["hours"] == 0){
                    if($difference_int["minutes"] == 0){
                        $difference = "0";
                    }
                    else{
                        $difference = $difference_int["minutes"]." ".JText::_("GURU_REAL_MINUTES");
                    }
                }
                else{
                    $difference = $difference_int["hours"]." ".JText::_("GURU_REAL_HOURS");
                }
            }
            if($expired_date_string == "0000-00-00 00:00:00"){//unlimited
                $difference_int = "1"; //default for unlimited
            }
            if($difference_int !== FALSE){// is not expired
                $not_show = true;
            }
            else{
                $return .= '<input type="button" class="'.trim($buy_class).' btn btn-warning" onclick="document.location.href=\''.str_replace("&amp;", "&",JRoute::_("index.php?option=com_guru&view=guruPrograms&task=buy_action&course_id=".$course_id."&Itemid=".$itemid)).'\';" value="'.JText::_("GURU_BUY_NOW").'" name="Buy" />';
                $expired = true;
            }
        }
        else{
            $return .= '<input type="button" class="'.trim($buy_class).' btn btn-warning" onclick="document.location.href=\''.str_replace("&amp;", "&",JRoute::_("index.php?option=com_guru&view=guruPrograms&task=buy_action&course_id=".$course_id."&Itemid=".$itemid)).'\';" value="'.JText::_("GURU_BUY_NOW").'" name="Buy" />';
        }
        $return .= '</div>
                    </div>';
    }
    else{//not show the button
        $not_show = true;
    }
    $sql = "SELECT chb_free_courses, step_access_courses, selected_course  FROM `#__guru_program` where id = ".intval($course_id);
    $db->setQuery($sql);
    $db->query();
    $result= $db->loadAssocList();
    $chb_free_courses = $result["0"]["chb_free_courses"];
    $step_access_courses = $result["0"]["step_access_courses"];
    $selected_course = $result["0"]["selected_course"];
    if($chb_free_courses == 1){
        $sql = "SELECT  count(*) FROM `#__guru_buy_courses` where `order_id` >='0' and `userid`=".intval($user_id)." and course_id=".intval($course_id);
        $db->setQuery($sql);
        $db->query();
        $result= $db->loadResult();
        if($result > 0 ){
            $not_show = true;
        }
        else{
            $not_show = false;
        }
    }
    if($difference_int == FALSE){
       // $is_expired_true =true;
    }
    else{
        //$is_expired_true = false;
    }
    if($not_show && ($chb_free_courses == 0 || ($chb_free_courses == 1 && $step_access_courses == 1) || ($chb_free_courses == 1 && $step_access_courses == 0  && $selected_course != -1 && isCustomer()) || ($chb_free_courses == 1 && $step_access_courses == 0  && $selected_course == -1 && hasAtLeastOneCourse()) || ($chb_free_courses == 1 && $step_access_courses == 0  && $selected_course != -1 && buySelectedCourse($selected_course)))){
        $return = array("0"=>"");
        if(isset($program_content) && count($program_content) > 0){
            $lesson_name = "";
            if(isset($lessons) && count($lessons) > 0){
                $lesson_name = $lessons["0"]["name"];
            }
            if($is_expired_true == false){
                $return["0"] = '<div><div><p>'.JText::_("GURU_WELCOME_TO").' "'.$program->name.'" '.JText::_("GURU_COURSE_FROM_PHRASE").'! '.JText::_("GURU_PLEASE_GET_STARTED").' "'.$lesson_name.'" '.JText::_("GURU_BELOW").'</p></div></div>';
            }
            else{
                $return["0"] = '<div><div><p>'.JText::_("GURU_EXPIRED_TEXT1")." ".'<a href="'.JRoute::_('index.php?option=com_guru&controller=guruOrders&task=renew&course_id='.$course_id).'">'.JText::_("GURU_EXPIRED_TEXT2").'</a>'." ".JText::_("GURU_EXPIRED_TEXT3").'</p></div></div>';
            }
        }
    }
    else{
        if($chb_free_courses == 1){//checked
            if($step_access_courses == 0 && !$expired){// Students
                if($selected_course == '-1'){// any course
                    if($user_id == 0){//not logged
                        $return = ' <div >
                                            <div class="clearfix">
                                                <div class="g_cource_enrole_options">'.JText::_("GURU_FREE_ALL_STUDENTS").'&nbsp;&nbsp;
    
                                                    <input type="button" class="'.trim($buy_class).' btn btn-warning" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
    
                                                </div>
                                            </div>
                                            <div class="clearfix">
                                                <div class="g_cource_enrole_options">'.JText::_("GURU_NOT_A_STUDENT").'&nbsp;&nbsp;
    
                                                    <input type="button" class="'.trim($buy_class).'btn btn-warning" onclick="document.location.href=\''.str_replace("&amp;", "&",JRoute::_("index.php?option=com_guru&view=guruPrograms&task=buy_action&course_id=".$course_id."&Itemid=".$itemid)).'\';" value="'.JText::_("GURU_BUY_NOW").'" name="Buy" />
    
                                                </div>
                                            </div>                                  
                                    </div>';
                    }
                    else{
                        if(hasAtLeastOneCourse()){
                            $return = ' <div >
                                            <div class="g_cource_enrole_options">'.JText::_("GURU_FREE_ALL_STUDENTS_LOGGIN").'&nbsp;&nbsp;
                                                <input type="button" class="'.trim($buy_class).' btn btn-warning" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
                                            </div>
                                    </div>';
                        }
                    }
                }
                else{// selected courses
                    if($user_id == 0){// not logged
                        $selected_course_final = explode('|', $selected_course);
                        foreach($selected_course_final as $key=>$value){
                            if(trim($value) == ""){
                                unset($selected_course_final[$key]);
                            }
                        }
                        $db = JFactory::getDBO();
                        $sql = "select name, id from #__guru_program where id in (".implode(", ", $selected_course_final).")";
                        $db->setQuery($sql);
                        $db->query();
                        $result = $db->loadAssocList();
                        $all_title = array();
                        $itemid = JRequest::getVar("Itemid", "0");
                        if(isset($result) && count($result) > 0){
                            foreach($result as $key=>$course){
                                $all_title[] = '<a href="'.JRoute::_("index.php?option=com_guru&view=guruPrograms&layout=view&cid=".$course["id"]."&Itemid=".$itemid).'">'.$course["name"].'</a>';
                            }
                        }
                        $all_title = implode(", ", $all_title);
                        $not_show = false;
                        $return = '<div >
                                            <div class="g_cource_enrole_options list_courses ">'.JText::_("GURU_FREE_STUDENTS_SOME_COURSES").'<br/>
                                                '.$all_title.'
                                            </div>
                                            <div class="clearfix">
                                                <div class="g_cource_enrole_options">'.JText::_("GURU_STUDENT_ANY_OF_COURSE").'&nbsp;&nbsp;
    
                                                    <input type="button"  class="'.trim($buy_class).' btn btn-warning" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
    
                                                </div>
                                            </div>
                                            <div class="clearfix">
                                                <div class="g_cource_enrole_options">'.JText::_("GURU_NOT_A_STUDENT").'&nbsp;&nbsp;
    
                                                    <input type="button" class="'.trim($buy_class).' btn btn-warning" onclick="document.location.href=\''.str_replace("&amp;", "&",JRoute::_("index.php?option=com_guru&view=guruPrograms&task=buy_action&course_id=".$course_id."&Itemid=".$itemid)).'\';" value="'.JText::_("GURU_BUY_NOW").'" name="Buy" />
    
                                                </div>
                                            </div>
                                    </div>';
                    }
                    else{
                        if(buySelectedCourse($selected_course)){
                            $selected_course_final = explode('|', $selected_course);
                            foreach($selected_course_final as $key=>$value){
                                if(trim($value) == ""){
                                    unset($selected_course_final[$key]);
                                }
                            }
                            $db = JFactory::getDBO();
                            $sql = "select name, id from #__guru_program where id in (".implode(", ", $selected_course_final).")";
                            $db->setQuery($sql);
                            $db->query();
                            $result = $db->loadAssocList();
                            $all_title = array();
                            $itemid = JRequest::getVar("Itemid", "0");
                            if(isset($result) && count($result) > 0){
                                foreach($result as $key=>$course){
                                    $all_title[] = '<a href="'.JRoute::_("index.php?option=com_guru&view=guruPrograms&layout=view&cid=".$course["id"]."&Itemid=".$itemid).'">'.$course["name"].'</a>';
                                }
                            }
                            $all_title = implode(", ", $all_title);
                            $return = '<div>
                                            <div class="list_courses">'.JText::_("GURU_STUDENT_SOME_COURSE").'
    
                                            </div>
                                            <div>
    
                                                '.$all_title.'&nbsp;&nbsp;&nbsp;
    
                                                <input type="button" class="'.trim($buy_class).' btn btn-warning" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
    
                                            </div>
                                            </div>';
                        }
                        //}
                    }
                }
            }
            elseif($step_access_courses == 1){// Members
                $return = '<div >
                                    <div class="g_cource_enrole_options">'.JText::_("GURU_FREE_MEMBERS").'&nbsp;&nbsp;
                                        <input type="button" class="'.trim($buy_class).' btn btn-warning" onclick="document.location.href=\''.JRoute::_("index.php?option=com_guru&view=guruprograms&task=enroll&action=enroll&cid=".$course_id).'\';" value="'.JText::_("GURU_ENROLL_NOW").'" name="Enroll" />
                                    </div>
                            </div>';
            }
            elseif($step_access_courses == 2){// Guest
                $return = ' <div>
                                    <div class="g_cource_enrole_options">'.JText::_("GURU_FREE_GUEST").'</div>
                            </div>';
            }
        }
    }
    return $return;
}
function buySelectedCourse($selected_course){
    $db = JFactory::getDBO();
    $user = JFactory::getUser();
    $user_id = $user->id;
    $sql = "SELECT distinct(`course_id`) FROM #__guru_buy_courses where `userid`=".intval($user_id);
    $db->setQuery($sql);
    $db->query();
    $all_courses = $db->loadColumn();
    $selected_course_final = explode('|', $selected_course);
    @$intersect = array_intersect($selected_course_final, @$all_courses);
    if(count($intersect)>0){
        return true;
    }
    else{
        return false;
    }
}
function chekIfNotLog($lesson){
    $db = JFactory::getDBO();
    $lesson_id = $lesson["id"];
    $user = JFactory::getUser();
    $user_id = $user->id;
    $course_id = intval(JRequest::getVar("cid", 0));
    $sql = "select step_access from #__guru_task where id=".intval($lesson_id);
    $db->setQuery($sql);
    $db->query();
    $lesson_acces = intval($db->loadResult());
    if($user_id == 0 && $lesson_acces == 2){
        return true;
    }
    elseif($user_id == 0 && $lesson_acces != 2){
        return false;
    }
    elseif($user_id == 0 && $lesson_acces == 0){
        return false;
    }
    elseif($user_id == 0 && $lesson_acces == 1){
        return false;
    }
    elseif($user_id != 0 && $lesson_acces == 1){
        return true;
    }
    elseif($user_id != 0 && $lesson_acces == 2){
        return true;
    }
    elseif($user_id != 0 && $lesson_acces == 0){
        $sql = "select count(*) from #__guru_buy_courses where `userid`=".intval($user_id)." and `course_id`=".intval($course_id);
        $db->setQuery($sql);
        $db->query();
        $result = $db->loadColumn();
        $result = @$result["0"];
        
        if($result > 0){
            $expired = false;
            $sql = "select expired_date from #__guru_buy_courses where userid=".intval($user_id)." and course_id=".intval($course_id);
            $db->setQuery($sql);
            $db->query();
            $expired_date_string = $db->loadResult();
            $current_date_string = "";
            $sql = "select bc.id from #__guru_buy_courses bc, #__guru_order o where bc.userid=".intval($user_id)." and bc.course_id=".intval($course_id)." and (bc.expired_date >= now() or bc.expired_date = '0000-00-00 00:00:00') and bc.order_id = o.id and o.status <> 'Pending'";
            $db->setQuery($sql);
            $db->query();
            $result = $db->loadColumn();
            $result = @$result["0"];
            
            if(($expired_date_string != "0000-00-00 00:00:00") || (isset($result) || intval($result) != 0)){
                $expired_date_int = strtotime($expired_date_string);
                $jnow = JFactory::getDate();
                $current_date_string = $jnow->toSQL();
                $current_date_int = strtotime($current_date_string);
                $renew = "false";
                if($current_date_int < $expired_date_int){
                    $renew = "true";
                }
                $sql = "select bc.course_id from #__guru_buy_courses bc, #__guru_order o where o.id=bc.order_id and bc.userid=".intval($user_id)." and o.status='Paid'";
                $db->setQuery($sql);
                $db->query();
                $my_courses = $db->loadResultArray();
                if(in_array($course_id, $my_courses)){ // I bought this course
                    $difference_int = get_time_difference($current_date_int, $expired_date_int);
                    $difference = $difference_int["days"]." ".JText::_("GURU_REAL_DAYS");
                    if($difference_int["days"] == 0){
                        if($difference_int["hours"] == 0){
                            if($difference_int["minutes"] == 0){
                                $difference = "0";
                            }
                            else{
                                $difference = $difference_int["minutes"]." ".JText::_("GURU_REAL_MINUTES");
                            }
                        }
                        else{
                            $difference = $difference_int["hours"]." ".JText::_("GURU_REAL_HOURS");
                        }
                    }
                    if($expired_date_string == "0000-00-00 00:00:00"){//unlimited
                        $difference_int = "1"; //default for unlimited
                    }
                    if($difference_int !== FALSE){// is not expired
                        return true;
                    }
                    else{
                        return false;
                    }
                }
                else{
                    $sql = "select count(*) from #__guru_buy_courses where `userid`=".intval($user_id)." and `course_id`=".intval($course_id)." and order_id=0";
                    $db->setQuery($sql);
                    $db->query();
                    $result = $db->loadResult();
                    if($result > 0){
                        return true;
                    }
                }
            }
        }
        else{
            return false;
        }
    }
    $jnow = JFactory::getDate();
    $current_date_string = $jnow->toSQL();
    $sql = "select bc.id from #__guru_buy_courses bc, #__guru_order o where bc.userid=".intval($user_id)." and bc.course_id=".intval($course_id)." and (bc.expired_date >= '".$current_date_string."' or bc.expired_date = '0000-00-00 00:00:00') and bc.order_id = o.id and o.status <> 'Pending'";
    $db->setQuery($sql);
    $db->query();
    $result = $db->loadResult();
    if(!isset($result) || intval($result) == 0){
        return false;
    }
    return true;
}
function accessToLesson($lesson){
    $lesson_id = $lesson["id"];
    $db = JFactory::getDBO();
    $course_id = intval(JRequest::getVar("cid", 0));
    if($lesson["chb_free_courses"] != 1){// not checked
        return chekIfNotLog($lesson);
    }
    else{
        $user = JFactory::getUser();
        if($user->id != 0){
            $sql = "select count(*) from #__guru_buy_courses where `userid`=".intval($user->id)." and `course_id`=".intval($course_id);
            $db->setQuery($sql);
            $db->query();
            $result = $db->loadResult();
            if($result == 0){// not bought course
                switch($lesson["step_access_courses"]){
                    case "0":{ //for students, if that user has at least one course
                    $user_id = $user->id;
                    $sql = "select distinct(`course_id`) from #__guru_buy_courses where `userid`=".intval($user_id)." and `course_id`=".intval($course_id);
                    $db->setQuery($sql);
                    $db->query();
                    $all_student_courses = $db->loadResultArray();
                    if(isset($all_student_courses) && count($all_student_courses) > 0){
                        $sql = "select `selected_course` from #__guru_program where id=".intval($course_id);
                        $db->setQuery($sql);
                        $db->query();
                        $selected_courses = $db->loadResult();
                        if(trim($selected_courses) != ""){
                            if(trim($selected_courses) == "-1"){// for any course bought
                                return true;
                            }
                            else{//only for selected courses
                                $ok = true;
                                $selected_courses = explode("|", trim($selected_courses));
                                foreach($selected_courses as $key => $select_course){
                                    if(trim($select_course) != "" && !in_array(trim($select_course), $all_student_courses)){
                                        $ok = false;
                                        break;
                                    }
                                }
                                return $ok;
                            }
                        }
                    }
                    else{
                        return false;
                    }
                    break;
                    }
                    case "1" : {// for members access
                    return true;
                    break;
                    }
                    case "2" : {// for guest access
                    return true;
                    break;
                    }
                }
            }
            else{// bought course
                return true;
            }
        }//log-in
        else{//log-out
            if($lesson["step_access_courses"] == 2){// guest access
                return true;
            }
            else{
                return chekIfNotLog($lesson);
            }
        }
    }
}
function getAction(){
    $db = JFactory::getDBO();
    $user = JFactory::getUser();
    $user_id = $user->id;
    $course_id = intval(JRequest::getVar("cid", 0));
    $jnow = JFactory::getDate();
    $current_date_string = $jnow->toSQL();
    $sql = "select count(*) from #__guru_buy_courses where userid=".intval($user_id)." and course_id=".intval($course_id);
    $db->setQuery($sql);
    $db->query();
    $result = $db->loadResult();
    if($result == 0){
        return false;
    }
    return true;
}
function boostrap_buttons($program, $course, $config, $course_config){
    $st_psgpage = json_decode($config->st_psgpage);
    $psgpage = json_decode($config->psgpage);
    $buy_class = $st_psgpage->course_other_button;
    $buy_background = $st_psgpage->course_other_background;
    $my = JFactory::getUser();
    $course_id = intval(JRequest::getVar("cid", 0));
    $show_buy_button =  $course_config->course_buy_button;
    $buy_button_location =  $course_config->course_buy_button_location;
    $program_content = "";
    if($show_buy_button == "0" && ($buy_button_location == "0" || $buy_button_location == "2")){
        ?>
    <div>
        <?php
        $button = createButtonB($buy_background, $course_id, $buy_class, $program, $program_content);
        if(is_array($button)){
            echo $button["0"];
        }
        else{
            echo $button;
        }
        ?>
    </div>
    <?php
    }
}
function tab1boost($program, $author, $program_content, $exercise, $requirements, $course, $config, $course_config){
    $st_psgpage = json_decode($config->st_psgpage);
    $psgpage = json_decode($config->psgpage);
    $course_level = $psgpage->course_level;
    $buy_class = $st_psgpage->course_other_button;
    $buy_background = $st_psgpage->course_other_background;
    $my = JFactory::getUser();
    $course_id = intval(JRequest::getVar("cid", 0));
    $show_buy_button =  $course_config->course_buy_button;
    $buy_button_location =  $course_config->course_buy_button_location;
    $user_id = $my->id;
    $user->id = $my->id;
    $db = JFactory::getDBO();
    $sql = "select name, alias from #__guru_program where id=".intval($course_id);
    $db->setQuery($sql);
    $db->query();
    $result = $db->loadAssocList();
    $alias = $result["0"]["alias"] == "" ? JFilterOutput::stringURLSafe($result["0"]["name"]) : $result["0"]["alias"];
    $sql = "SELECT  count(*) FROM `#__guru_buy_courses` where `order_id` >='0' and `userid`=".intval($user_id)." and course_id=".intval($course_id);
    $db->setQuery($sql);
    $db->query();
    $result= $db->loadResult();
    if($result > 0){
        $not_show = true;
    }
    else{
        $not_show = false;
    }
    $sql = "SELECT chb_free_courses, step_access_courses, selected_course  FROM `#__guru_program` where id = ".intval($course_id);
    $db->setQuery($sql);
    $db->query();
    $result= $db->loadAssocList();
    $chb_free_courses = $result["0"]["chb_free_courses"];
    $step_access_courses = $result["0"]["step_access_courses"];
    $selected_course = $result["0"]["selected_course"];
    if(buySelectedCourse($selected_course)){
        $hascourse = true;
    }
    $coursetype_details = guruModelguruProgram::getCourseTypeDetails($course_id);
    
    if($course_level==1){
        $display_levelimg = "none";
    }
    else{
        $display_levelimg = "inherit-inline";
    }
    ?>
    <?php
	
	// start calculation for one lesson per (option in admin)
	if($user_id > 0){
		$db = JFactory::getDBO();
		$sql = "select DATE_FORMAT(buy_date,'%Y-%m-%d %H:%i:%s') from #__guru_buy_courses where course_id=".intval($course_id)." and userid =".$user_id;
		$db->setQuery($sql);
		$db->query();
		$date_enrolled = $db->loadResult();
		$date_enrolled = strtotime($date_enrolled);
	}
	if(isset($date_enrolled)){
		$start_relaese_date1 = $coursetype_details[0]["start_release"];
		$start_relaese_date = strtotime($start_relaese_date1);
		$start_date =  $date_enrolled;
		
		$jnow = JFactory::getDate();
		$date9 = $jnow->toSQL();
		$date_9 = date("Y-m-d",strtotime($date9));
		$date9 = strtotime($date9);
		//$interval = $start_relaese_date->diff($date9);
		$interval = abs($date9 - $start_date);
		$dif_days = floor($interval/(60*60*24));
		$dif_week = floor($interval/(60*60*24*7));
		$dif_month = floor($interval/(60*60*24*30));
		if($coursetype_details[0]["course_type"] == 1){
			if($coursetype_details[0]["lesson_release"] == 1){
				$diff_start = $dif_days+1;
				$diff_date = $dif_days+1;
			}
			elseif($coursetype_details[0]["lesson_release"] == 2){
				$dif_days_enrolled = $dif_days_enrolled /7;
				$diff_start = $dif_week+1;
				$diff_date = $dif_week+1;
			}
			elseif($coursetype_details[0]["lesson_release"] == 3){
				$dif_days_enrolled = $dif_days_enrolled /30;
				$diff_start = $dif_month+1;
				$diff_date = $dif_month+1;
			}
		}
	}
	
	$step_less = $diff_start;
	// end calculation for one lesson per (option in admin)
	
    foreach($program_content as $key=>$array){
        $subcat = guruModelguruProgram::getSubCategory($array['id']);
    ?>
        <div class="chapter_wrap t_row">
            <div>
                <div>
                    <div class="chapter_title clearfix"><!-- start module name-->
            
                        <div>
            
                            <h4 class="g_module_name_mobile day clearfix span12">
                                <?php
            
                                echo $array['title'];
                                ?>
            
            
                            </h4>
            
                        </div>
            
                    </div><!-- end module name-->
                    
                   <div class="lessons_wrap"><!-- lesson start -->                      
                    <div id='tdb_<?php echo $array['id']; ?>'><!-- -->
                        <div id='tableb_<?php echo $array['id'];?>' class="subcat">
                            <ul>
                             <?php
                                foreach($subcat as $poz=>$sub_cat){
                                    switch($sub_cat['difficultylevel']){
                                        case "easy":
                                            $imgLevel="beginner_level.png";
                                            break;
                                        case "medium":
                                            $imgLevel="intermediate_level.png";
                                            break;
                                        case "hard":
                                            $imgLevel="advanced_level.png";
                                            break;
                                    }
                                    if($config->open_target == 0 ){
                                        $my = JFactory::getUser();
                                        $user_id = $my->id;
                                        $display = "none";
                                        if($user_id > 0){
                                            $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                                            if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                                                $display = "none";
                                            }
                                            else{
                                                if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                                    $display = "inline-block";
                                                }
                                            }
                                        }
                                    }
                                    else{
                                        $my = JFactory::getUser();
                                        $user_id = $my->id;
                                        $display = "none";
                                        if($user_id > 0){
                                            $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                                            if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start ){
                                                $display = "none";
                                            }
                                            else{
                                                if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                                    $display = "block";
                                                }
                                            }
                                        }
                                    }
									
                                    if($user_id == 0 && $sub_cat["chb_free_courses"] == 1 && $sub_cat["step_access_courses"] == 1){
									?>
                                        <li class="g_row">
                                            <div class="col_title g_cell lesson_name"><a href="<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component" onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component'); return false;"><?php echo $sub_cat['name']; ?></a></div>
                    
                                        <?php
                    
                                        $user_id = $my->id;
                    
                                        $display = "hidden";
                    
                                        if($user_id > 0){
                    
                                            $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                    
                                            if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                    
                                                $display = "hidden";
                    
                                            }
                    
                                            else{
                    
                                                if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                    
                                                    $display = "inherit";
                    
                                                }
                    
                                            }
                    
                    
                    
                                        }
                    
                                        ?>
                                        <div style="visibility:<?php echo $display; ?>;" class="g_cell viewed pull-left">
                    
                                            <i class="icon-eye-open"></i>
                    
                                        </div>
                                        <?php
                    
                    
                                            ?>
                    
                                            <div class="g_cell pull-left level">
                    
                                                <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                    
                                            </div>
                    
                    
                                    </li>
                                    <?php
                                    }
									elseif($sub_cat["chb_free_courses"] == 0 && $user_id == 0 && accessToLesson($sub_cat ) && $config->open_target==0){
										?>
									 <li class="g_row">
										 
										<div class="col_title g_cell span8 lesson_name">
											<a href="<?php echo JRoute::_("index.php?option=com_guru&view=gurutasks&catid=".$program->catid."&module=".$array['id']."-".$array['alias']."&cid=".$sub_cat['id']."-".$sub_cat['alias']); ?>"><span <?php echo $style;?> ><?php echo $sub_cat['name']; ?></span></a></div>
										<?php
										$user_id = $my->id;
										$display = "hidden";
										if($user_id > 0){
											$lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
											if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
												$display = "hidden";
											}
											else{
												if(isset($lesson_viewed) && $lesson_viewed === TRUE){
													$display = "inherit";
												}
											}
										}
										?>
										<div style="visibility:<?php echo $display; ?>;" class="g_cell pull-left span2 viewed">
												
																		<i class="icon-eye-open"></i>
												
														</div>
										<?php
										if($course_level==0){
											?>
											<div class="g_cell pull-left span1 level">
												<img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
										   </div>
											<?php } ?>
									</li>       
									<?php
									}
									elseif($user_id != 0 && isCustomer() && $config->open_target==0 &&(($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0)) ||($sub_cat["chb_free_courses"] == 1 && $sub_cat["step_access_courses"] == 2)){
										$diff_date--;
										?>
									 <li class="g_row">
										<?php
											$span = "span9";
											if($diff_start > 0){
												$span = "span7";
											}
											
											$preview_viewd = FALSE;
											if($prev_id == 0){
												$prev_id = $sub_cat["id"];
												$preview_viewd = TRUE;
											}
											else{
												$lesson_viewed = guruModelguruTask::getViewLesson($prev_id);
												$prev_id = $sub_cat["id"];
												if($lesson_viewed){
													$preview_viewd = TRUE;
												}
											}
											
											if($coursetype_details[0]["course_type"] == 0){// non sequential
												$preview_viewd = TRUE;
											}
										?>
										<div class="col_title g_cell <?php echo $span; ?> lesson_name">
											<?php
												if($preview_viewd){
													if($config->open_target == 0){
											?>
                                                        <a href="<?php echo JRoute::_("index.php?option=com_guru&view=gurutasks&catid=".$program->catid."&module=".$array['id']."-".$array['alias']."&cid=".$sub_cat['id']."-".$sub_cat['alias']); ?>">
                                                            <span <?php echo $style;?> ><?php echo $sub_cat['name']; ?></span>
                                                        </a>
											<?php
                                            		}
													else{
											?>
                                            			<a onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JUri::root(); ?>index.php?option=com_guru&view=gurutasks&catid=<?php echo $program->catid; ?>&module=<?php echo $array["id"];?>-<?php echo $array["alias"]; ?>&cid=<?php echo $sub_cat['id'];?>-<?php echo $sub_cat["alias"];?>&tmpl=component&Itemid=<?php echo $itemid;?>'); return false; javascript:setViewed('viewed-<?php echo $sub_cat['id']; ?>', '<?php echo JUri::root()."components/com_guru/images/icons/viewed.gif"; ?>')" href="<?php echo JUri::root(); ?>index.php?option=com_guru&view=gurutasks&catid=<?php echo $program->catid; ?>&module=<?php echo $array["id"];?>-<?php echo $array["alias"]; ?>&cid=<?php echo $sub_cat['id'];?>-<?php echo $sub_cat["alias"];?>&tmpl=component&Itemid=<?php echo $itemid;?>&format=raw">
															<?php echo $sub_cat['name']; ?>
                                                        </a>
                                            <?php
													}
												}
												else{
											?>
													<span <?php echo $style;?> ><?php echo $sub_cat['name']; ?></span>
											<?php
												}
											?>
										</div>
										<?php
										$user_id = $my->id;
										$display = "hidden";
										if($user_id > 0){
											$lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
											if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
												$display = "hidden";
											}
											else{
												if(isset($lesson_viewed) && $lesson_viewed === TRUE){
													$display = "inherit";
												}
											}
										}
										?>
										<div id="viewed-<?php echo $sub_cat['id']; ?>" style="visibility:<?php echo $display;?>" class="g_cell span2 viewed">
											<i class="fa fa-eye"></i>
										</div>
										<?php
										if($course_level==0){
											?>
											<div class="g_cell span1 level">
												<img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
										   </div>
											<?php
										} 
											
										if($diff_start > 0){
											?>
											<div class="available_lesson g_cell span2 available"><?php echo JText::_("GURU_AVAILABLE");?></div>
										<?php
										}
										?>
									</li>
									<?php
									}
                                    elseif($user_id != 0 && $sub_cat["chb_free_courses"] == 1 && $sub_cat["step_access_courses"] == 1 && $not_show === FALSE && (($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0))){
									$diff_date--;
                
                                    ?>
                
                                <li class="g_row">
                                     <div class="col_title g_cell lesson_name"><a href="<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component" onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component'); return false;"><?php echo $sub_cat['name']; ?></a></div>
                
                                    <?php
                
                                    $user_id = $my->id;
                
                                    $display = "hidden";
                
                                    if($user_id > 0){
                
                                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                
                                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                
                                            $display = "hidden";
                
                                        }
                
                                        else{
                
                                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                
                                                $display = "inherit";
                
                                            }
                
                                        }
                
                
                
                                    }
                
                                    ?>
                
                                     <div style="visibility:<?php echo $display; ?>;" class="g_cell pull-left viewed">
                    
                                            <i class="icon-eye-open"></i>
                    
                                     </div>
                
                                    <?php
                
                                    if($course_level==0){
                
                                        ?>
                
                                        <div class="g_cell pull-left level">
                
                                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                
                                       </div>
                
                                <?php } ?>
                
                                </li>
                                <?php
            
                                }
                                elseif(($user_id != 0 && inCustomerTable() && $sub_cat["chb_free_courses"] == 1 && $sub_cat["step_access_courses"] == 0 && $sub_cat["selected_course"] == -1 && $not_show === FALSE && hasAtLeastOneCourse() && (($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0)))){
								$diff_date--;
            
                                ?>
                                <li class="g_row">
                                 <div class="col_title g_cell span6 lesson_name"><a href="<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component" onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component'); return false;"><?php echo $sub_cat['name']; ?></a></div>
            
                                <?php
            
                                $user_id = $my->id;
            
                                $display = "hidden";
            
                                if($user_id > 0){
            
                                    $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
            
                                    if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
            
                                        $display = "hidden";
            
                                    }
            
                                    else{
            
                                        if(isset($lesson_viewed) && $lesson_viewed === TRUE){
            
                                            $display = "inherit";
            
                                        }
            
                                    }
            
            
            
                                }
            
                                ?>
            
                                <div style="visibility:<?php echo $display; ?>;" class="g_cell pull-left viewed">
                    
                                            <i class="icon-eye-open"></i>
                    
                                 </div>
            
                                <?php
            
                                if($course_level==0){
            
                                    ?>
            
                                    <div class="g_cell pull-left level">
            
                                        <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
            
                                   </div>
            
                            <?php } ?>
            
                            </li>
                            <?php
                            }
                            elseif(($user_id != 0 && isCustomer() && $sub_cat["chb_free_courses"] == 1 && $sub_cat["step_access_courses"] == 0 && $not_show === FALSE && $hascourse == TRUE &&((($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0))))){
                            $diff_date--;
        
                            ?>
                            <li class="g_row">
                             <div class="col_title g_cell lesson_name"><a href="<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component" onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component'); return false;"><?php echo $sub_cat['name']; ?></a></div>
        
                            <?php
        
                            $user_id = $my->id;
        
                            $display = "hidden";
        
                            if($user_id > 0){
        
                                $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
        
                                if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
        
                                    $display = "hidden";
        
                                }
        
                                else{
        
                                    if(isset($lesson_viewed) && $lesson_viewed === TRUE){
        
                                        $display = "inherit";
        
                                    }
        
                                }
        
        
        
                            }
        
                            ?>
        
                            <div style="visibility:<?php echo $display; ?>;" class="g_cell pull-left viewed">
                    
                                            <i class="icon-eye-open"></i>
                    
                            </div>
            
        
                            <?php
        
                            if($course_level==0){
        
                                ?>
        
                                <div class="g_cell pull-left level">
        
                                    <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
        
                               </div>
        
                                <?php } ?>
        
                        </li>
                        <?php
                        }
                        elseif(!accessToLesson($sub_cat)&&(($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0))){
                        $diff_date--;
                        //$lesson_height = ($lesson_height/2)+100;
                        //$lesson_width = ($lesson_width/2)+100;
    
                        ?>
                       <li class="g_row">
                         <div class="col_title g_cell lesson_name"><a href="<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruEditplans&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&tmpl=component" onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruEditplans&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&tmpl=component'); return false;"><?php echo $sub_cat['name']; ?></a></div>
    
                        <?php
    
                        $user_id = $my->id;
    
                        $display = "hidden";
    
    
                        if($user_id > 0){
    
                            $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
    
                            if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
    
                                $display = "hidden";
    
                            }
    
                            else{
    
                                if(isset($lesson_viewed) && $lesson_viewed === TRUE){
    
                                    $display = "inherit";
    
                                }
    
                            }
    
    
    
                        }
    
                        ?>
    
                        <div  style="visibility:<?php echo $display; ?>;" class="g_cell pull-left viewed">
                    
                                            <i class="icon-eye-open"></i>
                    
                        </div>
            
    
                        <?php
    
                        if($course_level==0){
    
                            ?>
    
                            <div class="g_cell pull-left level">
        
                                    <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
        
                             </div>
    
                            <?php } 
                            if($user_id >0 && $coursetype_details[0]["course_type"] != 0 && $coursetype_details[0]["lessons_show"] == 1 && $coursetype_details[0]["lesson_release"] >0 && $not_show === TRUE){
    
                if($coursetype_details[0]["course_type"] == 1){
                    if($coursetype_details[0]["lesson_release"] == 1){
    
                        $date_to_display = strtotime ( '+'.$step_less++.' day' , $start_date) ;
    
                    }
    
                    elseif($coursetype_details[0]["lesson_release"] == 2){
    
                        $date_to_display = strtotime ( '+'.$step_less++.' week' , $start_date) ;
    
                    }
    
                    elseif($coursetype_details[0]["lesson_release"] == 3){
    
                        $date_to_display = strtotime ( '+'.$step_less++.' month' , $start_date) ;
    
                    }
    
                }
    
                if($diff_start >0){
    
                    ?>
    
                    <div class="available_lesson g_cell available"><?php echo JText::_("GURU_AVAILABLE");?></div>
    
                    <?php
    
                }
    
                else{
    
                    ?>
    
                    <div class="g_cell  date_available"><?php echo date('m-d-Y', $date_to_display);?></div>
    
                    <?php
    
                }
    
            }
            ?>
        </li>
        <?php
        }
                elseif($config->open_target==0 &&(($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0))){
					$diff_date--;
                    ?>
                 <li class="g_row">
                     
                    <div class="col_title g_cell lesson_name">
                        <a href="<?php echo JRoute::_("index.php?option=com_guru&view=gurutasks&catid=".$program->catid."&module=".$array['id']."-".$array['alias']."&cid=".$sub_cat['id']."-".$sub_cat['alias']); ?>"><span <?php echo $style;?> ><?php echo $sub_cat['name']; ?></span></a></div>
                    <?php
                    $user_id = $my->id;

                    $display = "hidden";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "hidden";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "inherit";
                            }
                        }
                    }
                    ?>
                    <div style="visibility:<?php echo $display; ?>;" class="g_cell pull-left viewed">
                            
                                                    <i class="icon-eye-open"></i>
                            
                                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell pull-left level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                       </div>
                        <?php } ?>
                </li>       
                <?php
                }
                elseif($config->open_target==1 && (($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0))){
				    $diff_date--;
                    if($user_id ==0){
                        $span_lesson = 'span8';
                    }
                    else{
                        $span_lesson = 'span9';
                    }
                    
                    if($diff_start >0){
                        $span_lesson = 'span7';
                    }
                    $preview_viewd = FALSE;
                    if($prev_id == 0){
                        $prev_id = $sub_cat["id"];
                        $preview_viewd = TRUE;
                    }
                    else{
                        $lesson_viewed = guruModelguruTask::getViewLesson($prev_id);
                        $prev_id = $sub_cat["id"];
                        if($lesson_viewed){
                            $preview_viewd = TRUE;
                        }
                    }
                    
                    if($coursetype_details[0]["course_type"] == 0){// non sequential
                        $preview_viewd = TRUE;
                    }
                ?>
                <li class="g_row">
                    <div class="col_title g_cell <?php echo $span_lesson ;?> lesson_name">
                        <?php
                            if($preview_viewd){
                        ?>
                                <a onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JUri::root(); ?>index.php?option=com_guru&view=gurutasks&catid=<?php echo $program->catid; ?>&module=<?php echo $array["id"];?>-<?php echo $array["alias"]; ?>&cid=<?php echo $sub_cat['id'];?>-<?php echo $sub_cat["alias"];?>&tmpl=component&Itemid=<?php echo $itemid;?>'); return false; javascript:setViewed('viewed-<?php echo $sub_cat['id']; ?>', '<?php echo JUri::root()."components/com_guru/images/icons/viewed.gif"; ?>')" href="<?php echo JUri::root(); ?>index.php?option=com_guru&view=gurutasks&catid=<?php echo $program->catid; ?>&module=<?php echo $array["id"];?>-<?php echo $array["alias"]; ?>&cid=<?php echo $sub_cat['id'];?>-<?php echo $sub_cat["alias"];?>&tmpl=component&Itemid=<?php echo $itemid;?>&format=raw">
                                    <?php echo $sub_cat['name']; ?>
                                </a>
                        <?php
                            }
                            else{
                        ?>
                                <?php echo $sub_cat['name']; ?>
                        <?php
                            }
                        ?>
                    </div>
                <?php
                    $user_id = $my->id;
                    $display = "hidden";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "hidden";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "visible";
                            }
                        }
                    }
                    
                    $viewed_span = "span1";
                    if($diff_start > 0){
                        $viewed_span = "span2";
                    }
                    ?>
                        
                    <div id="viewed-<?php echo $sub_cat['id']; ?>" style="visibility:<?php echo $display;?>" class="g_cell <?php echo $viewed_span; ?> viewed">
                        <i class="fa fa-eye"></i>
                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell span1 level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                        </div>
                        <?php } ?>
                    <?php
                    if($user_id >0 && $coursetype_details[0]["course_type"] != 0 && $coursetype_details[0]["lessons_show"] == 1 && $coursetype_details[0]["lesson_release"] >0 && $not_show === TRUE){
                        
                        if($diff_start >0){
                            ?>
                            <div class="available_lesson g_cell span2 available"><?php echo JText::_("GURU_AVAILABLE");?></div>
                            <?php
                        }
                    }
                    ?>
                </li>
                    <?php
                }
                elseif($sub_cat["chb_free_courses"] == 0 && $user_id == 0 ){
					//$lesson_height = ($lesson_height/2)+100;
                    //$lesson_width = ($lesson_width/2)+100;
                    ?>
                <li class="g_row">
                   <div class="col_title g_cell lesson_name"><a href="<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruEditplans&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&tmpl=component" onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruEditplans&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&tmpl=component'); return false;"><?php echo $sub_cat['name']; ?></a></div>
                    <?php
                    $user_id = $my->id;
                    $display = "none";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "none";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "inherit";
                            }
                        }
                    }
                    ?>
                   <div  style="visibility:<?php echo $display; ?>;" class="g_cell pull-left viewed">
                            
                                                    <i class="icon-eye-open"></i>
                            
                                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell pull-left level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                        </div>
                        <?php } ?>
                </li>
                    <?php
                }
                 else{
					$style="style='color:#999999;'";
                    $gray_style=" class=\'s_no_underline\' ";
                    if($coursetype_details[0]["lessons_show"] == '1'){
                        ?>
                        <li <?php echo $style;?> class="g_row">
                            <div class="col_title g_cell lesson_name">
                                <?php echo $sub_cat['name']; ?>
                            </div>
                            
                            <div class="g_cell level pull-left">
                                <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                            </div>
                        </li>
                        <?php
                    }
                    else{
                        ?>
                        <li>&nbsp;</li>
                        <?php
                    }
                }
            }
            
            ?>
            </ul>   
        </div>
       </div> 
   </div>
   
   </div>
   </div>
   </div>
   <?php
   }
}
function tab1($program, $author, $program_content, $exercise, $requirements, $course, $config, $course_config){
    $prev_id = 0;
    $st_psgpage = json_decode($config->st_psgpage);
    $psgpage = json_decode($config->psgpage);
    $course_level = $psgpage->course_level;
    $buy_class = $st_psgpage->course_other_button;
    $buy_background = $st_psgpage->course_other_background;
    $my = JFactory::getUser();
    $course_id = intval(JRequest::getVar("cid", 0));
    $show_buy_button =  $course_config->course_buy_button;
    $buy_button_location =  $course_config->course_buy_button_location;
    $user_id = $my->id;
    $user->id = $my->id;
    $lesson_size = $config->lesson_window_size;
    $lesson_size = explode("x", $lesson_size);
    $lesson_height = $lesson_size["0"];
    $lesson_width = $lesson_size["1"];
    $style_grayout = "color:#999999;";
    $db = JFactory::getDBO();
    $sql = "select name, alias from #__guru_program where id=".intval($course_id);
    $db->setQuery($sql);
    $db->query();
    $result = $db->loadAssocList();
    $alias = $result["0"]["alias"] == "" ? JFilterOutput::stringURLSafe($result["0"]["name"]) : $result["0"]["alias"];
    $sql = "SELECT  count(*) FROM `#__guru_buy_courses` where `order_id` >='0' and `userid`=".intval($user_id)." and course_id=".intval($course_id);
    $db->setQuery($sql);
    $db->query();
    $result= $db->loadResult();
    if($result > 0){
        $not_show = true;
    }
    else{
        $not_show = false;
    }
    $sql = "SELECT chb_free_courses, step_access_courses, selected_course  FROM `#__guru_program` where id = ".intval($course_id);
    $db->setQuery($sql);
    $db->query();
    $result= $db->loadAssocList();
    $chb_free_courses = $result["0"]["chb_free_courses"];
    $step_access_courses = $result["0"]["step_access_courses"];
    $selected_course = $result["0"]["selected_course"];
    if(buySelectedCourse($selected_course)){
        $hascourse = true;
    }
    $coursetype_details = guruModelguruProgram::getCourseTypeDetails($course_id);
    if($course_level==1){
        $display_levelimg = "none";
    }
    else{
        $display_levelimg = "inherit-inline";
    }
    ?>
    
<div><!-- start main div-->
    <div class="tab_active_cont course_view_tablecontents">
            <?php
    if($deviceType !="phone"){// if computer /tablet
        if($show_buy_button == "0" && ($buy_button_location == "0" || $buy_button_location == "2")){
            ?>
        <div class="call_2_action buy_now">
            <div>
               <?php
                $button = createButton($buy_background, $course_id, $buy_class, $program, $program_content);// display message like "Get access to all the tutorials in the course now! and Buy Now button"
                if(is_array($button)){
                    echo $button["0"];
                }
                else{
                    echo $button;
                }
                ?>
            </div>
        </div>
            <?php
        }
        ?>
    <div class="col_titles t_row">
        <div><!-- start td for show/close all button-->
            <?php
            $show_all_cloase_all = isset($course_config->show_all_cloase_all) ? $course_config->show_all_cloase_all : "0";
             if($user_id > 0){
                $col_width=9;
            }else{
                $col_width=8;
            }
            
            if($user_id > 0 && $coursetype_details[0]["course_type"] != 0  && $coursetype_details[0]["lessons_show"] == 1 && $coursetype_details[0]["lesson_release"] >0 && $not_show === TRUE){
                $col_width = 7;
            }
            
            ?>
            <div class="col_title g_cell span<?php echo $col_width; ?>">
            <?php
            if($show_all_cloase_all != 1){
                ?>
                    <input type="button" class="btn btn-primary show_sub" value="+ <?php echo JText::_("GURU_SHOW_ALL_BUTTON"); ?>"/><!--show all button -->
                    <input type="button" class="btn btn-primary close_sub" value="- <?php echo JText::_("GURU_CLOSE_ALL_BUTTON"); ?>"/><!--close all button -->
                <?php
            }
            else{
                echo '&nbsp;';
            }
            ?>
            </div>
            
            <div class="col_title g_cell span2">
                <?php
                echo JText::_("GURU_VIEWED");
                ?>
            </div>
            <div class="col_title g_cell span1">
                    <?php
                    if($course_level==0){
                        echo JText::_("GURU_LEVEL");
                    }
                    ?><!--Level -->
            </div>
            <?php
            if($user_id > 0 && $coursetype_details[0]["course_type"] != 0  && $coursetype_details[0]["lessons_show"] == 1 && $coursetype_details[0]["lesson_release"] >0 && $not_show === TRUE){?>
                <div class="col_title g_cell span2">
                    <?php echo JText::_("GURU_AVAILABILITY"); ?>
                </div>
                <?php
            } ?>
        </div><!-- end td for show/close all button-->
    </div>
        <?php
        // start calculation for one lesson per (option in admin)
        if($user_id > 0){
            $db = JFactory::getDBO();
            $sql = "select DATE_FORMAT(buy_date,'%Y-%m-%d %H:%i:%s') from #__guru_buy_courses where course_id=".intval($course_id)." and userid =".$user_id;
            $db->setQuery($sql);
            $db->query();
            $date_enrolled = $db->loadResult();
            $date_enrolled = strtotime($date_enrolled);
        }
        if(isset($date_enrolled)){
            $start_relaese_date1 = $coursetype_details[0]["start_release"];
            $start_relaese_date = strtotime($start_relaese_date1);
            $start_date =  $date_enrolled;
            
            $jnow = JFactory::getDate();
            $date9 = $jnow->toSQL();
            $date_9 = date("Y-m-d",strtotime($date9));
            $date9 = strtotime($date9);
            //$interval = $start_relaese_date->diff($date9);
            $interval = abs($date9 - $start_date);
            $dif_days = floor($interval/(60*60*24));
            $dif_week = floor($interval/(60*60*24*7));
            $dif_month = floor($interval/(60*60*24*30));
            if($coursetype_details[0]["course_type"] == 1){
                if($coursetype_details[0]["lesson_release"] == 1){
                    $diff_start = $dif_days+1;
                    $diff_date = $dif_days+1;
                }
                elseif($coursetype_details[0]["lesson_release"] == 2){
                    $dif_days_enrolled = $dif_days_enrolled /7;
                    $diff_start = $dif_week+1;
                    $diff_date = $dif_week+1;
                }
                elseif($coursetype_details[0]["lesson_release"] == 3){
                    $dif_days_enrolled = $dif_days_enrolled /30;
                    $diff_start = $dif_month+1;
                    $diff_date = $dif_month+1;
                }
            }
        }
        
        $step_less = $diff_start;
        // end calculation for one lesson per (option in admin)
        foreach($program_content as $key=>$array){
            $subcat = guruModelguruProgram::getSubCategory($array['id']);
        ?>
        <div class="chapter_wrap t_row">
        <div>
            <div>
                <div class="chapter_title clearfix"><!-- start module name-->
        
                    <div>
        
                        <div class="day clearfix span12" onClick="javascript:show_hidde('<?php echo $array['id'];?>','<?php echo JUri::root()."components/com_guru/images/";?>')">
        
                            <img id='img_<?php echo $array['id']; ?>' src='<?php echo JUri::root()."components/com_guru/images/arrow-right.gif";?>' />
        
                            <?php
        
                            echo $array['title'];
                            ?>
        
        
                        </div>
        
                    </div>
        
                </div><!-- end module name-->
            <?php
            if(count($subcat)>0){?>
                            <div class="lessons_wrap">                          
                                <div id='td_<?php echo $array['id']; ?>'>
                                    <div id='table_<?php echo $array['id'];?>' class="subcat">
                                        <ul class="thumb">
                     <?php }
            foreach($subcat as $poz=>$sub_cat){
                switch($sub_cat['difficultylevel']){
                    case "easy":
                        $imgLevel="beginner_level.png";
                        break;
                    case "medium":
                        $imgLevel="intermediate_level.png";
                        break;
                    case "hard":
                        $imgLevel="advanced_level.png";
                        break;
                }
                if(($user->id>0 && $sub_cat['step_access']!=2) || $sub_cat['step_access']==2){
                    $style=" class='s_underline' ";
                    $gray_style=" class='s_underline' ";
                }
                else{
                    $style=" class='s_no_underline'";
                    $gray_style=" class= 's_no_underline' ";
                }
                if($sub_cat['chb_free_courses'] == 1){
                    if(isset ($sub_cat['step_access_courses']) && $sub_cat['step_access_courses']== 2){
                        $style=" class='s_underline' ";
                        $gray_style=" class='s_underline'";
                    }
                    if($user->id <= 0){
                        if(isset($sub_cat['step_access_courses']) && $sub_cat['step_access_courses']== 1){
                            $style=" class='s_no_underline'";
                            $gray_style=" class='s_no_underline'  ";
                        }
                        if(isset($sub_cat['step_access_courses']) && $sub_cat['step_access_courses']== 0 && $sub_cat['selected_course']== -1){
                            $style=" class='s_no_underline'";
 
                            $gray_style=" class='s_no_underline' ";
                        }
                        if(isset($sub_cat['step_access_courses']) && $sub_cat['step_access_courses']== 0 && $sub_cat['selected_course']!= -1){
                            $style=" class='s_no_underline'";
                            $gray_style=" class='s_no_underline' ";
                        }
                    }
                    else{
                        if(isset($sub_cat['step_access_courses']) && $sub_cat['step_access_courses']== 0 && $sub_cat['selected_course']== -1 && $not_show ==FALSE){
                            $style=" class='s_no_underline'";
                            $gray_style=" class='s_no_underline' ";
                        }
                        if(isset($sub_cat['step_access_courses']) && $sub_cat['step_access_courses']== 0 && $sub_cat['selected_course']!= -1 && $not_show == FALSE){
                            $style=" class='s_no_underline'";
                            $gray_style=" class='s_no_underline' ";
                        }
                        if(isset($sub_cat['step_access_courses']) && $sub_cat['step_access_courses']== 1 && $not_show ==FALSE){
                            $style=" class='s_no_underline'";
                            $gray_style=" class='s_no_underline' ";
                        }
                    }
                }
				
                if($user_id == 0 && $sub_cat["chb_free_courses"] == 1 && $sub_cat["step_access_courses"] == 1){
				?>
                    <li class="g_row">
                      <div class="col_title g_cell span9 lesson_name"><a href="<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component" onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component'); return false;"><?php echo $sub_cat['name']; ?></a></div>
                    <?php
                    $user_id = $my->id;
                    $display = "hidden";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "hidden";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "inherit";
                            }
                        }
                    }
                    ?>
                    <div id="viewed-<?php echo $sub_cat['id']; ?>" style="visibility:<?php echo $display;?>" class="g_cell span2 viewed">
                        <i class="fa fa-eye"></i>
                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell span1 level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                        </div>
                        <?php } ?>
                </li>
                <?php
                }
                elseif($sub_cat["chb_free_courses"] == 0 && $user_id == 0 && accessToLesson($sub_cat ) && $config->open_target==0){
					?>
                 <li class="g_row">
                     
                    <div class="col_title g_cell span8 lesson_name">
                        <a href="<?php echo JRoute::_("index.php?option=com_guru&view=gurutasks&catid=".$program->catid."&module=".$array['id']."-".$array['alias']."&cid=".$sub_cat['id']."-".$sub_cat['alias']); ?>"><span <?php echo $style;?> ><?php echo $sub_cat['name']; ?></span></a></div>
                    <?php
                    $user_id = $my->id;
                    $display = "hidden";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "hidden";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "inherit";
                            }
                        }
                    }
                    ?>
                    <div style="visibility:<?php echo $display; ?>;" class="g_cell pull-left span2 viewed">
                            
                                                    <i class="icon-eye-open"></i>
                            
                                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell pull-left span1 level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                       </div>
                        <?php } ?>
                </li>       
                <?php
                }
                elseif($user_id != 0 && isCustomer() && $config->open_target==0 &&(($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0)) ||($sub_cat["chb_free_courses"] == 1 && $sub_cat["step_access_courses"] == 2)){
					$diff_date--;

                    ?>
                 <li class="g_row">
                    <?php
                        $span = "span9";
                        if($diff_start > 0){
                            $span = "span7";
                        }
                        
                        $preview_viewd = FALSE;
                        if($prev_id == 0){
                            $prev_id = $sub_cat["id"];
                            $preview_viewd = TRUE;
                        }
                        else{
                            $lesson_viewed = guruModelguruTask::getViewLesson($prev_id);
                            $prev_id = $sub_cat["id"];
                            if($lesson_viewed){
                                $preview_viewd = TRUE;
                            }
                        }
                        
                        if($coursetype_details[0]["course_type"] == 0){// non sequential
                            $preview_viewd = TRUE;
                        }
                    ?>
                    <div class="col_title g_cell <?php echo $span; ?> lesson_name">
                        <?php
                            if($preview_viewd){
								if($config->open_target == 0){
                        ?>
                                    <a href="<?php echo JRoute::_("index.php?option=com_guru&view=gurutasks&catid=".$program->catid."&module=".$array['id']."-".$array['alias']."&cid=".$sub_cat['id']."-".$sub_cat['alias']); ?>">
                                        <span <?php echo $style;?> ><?php echo $sub_cat['name']; ?></span>
                                    </a>
						<?php
                        		}
								else{
						?>                                
                                    <a onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JUri::root(); ?>index.php?option=com_guru&view=gurutasks&catid=<?php echo $program->catid; ?>&module=<?php echo $array["id"];?>-<?php echo $array["alias"]; ?>&cid=<?php echo $sub_cat['id'];?>-<?php echo $sub_cat["alias"];?>&tmpl=component&Itemid=<?php echo $itemid;?>'); return false; javascript:setViewed('viewed-<?php echo $sub_cat['id']; ?>', '<?php echo JUri::root()."components/com_guru/images/icons/viewed.gif"; ?>')" href="<?php echo JUri::root(); ?>index.php?option=com_guru&view=gurutasks&catid=<?php echo $program->catid; ?>&module=<?php echo $array["id"];?>-<?php echo $array["alias"]; ?>&cid=<?php echo $sub_cat['id'];?>-<?php echo $sub_cat["alias"];?>&tmpl=component&Itemid=<?php echo $itemid;?>&format=raw">
                                        <?php echo $sub_cat['name']; ?>
                                    </a>
                        <?php
								}
                            }
                            else{
                        ?>
                                <span <?php echo $style;?> ><?php echo $sub_cat['name']; ?></span>
                        <?php
                            }
                        ?>
                    </div>
                    <?php
                    $user_id = $my->id;
                    $display = "hidden";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "hidden";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "inherit";
                            }
                        }
                    }
                    ?>
                    <div id="viewed-<?php echo $sub_cat['id']; ?>" style="visibility:<?php echo $display;?>" class="g_cell span2 viewed">
                        <i class="fa fa-eye"></i>
                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell span1 level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                       </div>
                        <?php
                    } 
                        
                    if($diff_start > 0){
                        ?>
                        <div class="available_lesson g_cell span2 available"><?php echo JText::_("GURU_AVAILABLE");?></div>
                    <?php
                    }
                    ?>
                </li>
                <?php
                }
                elseif($user_id != 0 && $sub_cat["chb_free_courses"] == 1 && $sub_cat["step_access_courses"] == 1 && $not_show === FALSE && (($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0))){
                    $diff_date--;
                    ?>
                <li class="g_row">
                     <div class="col_title g_cell span9 lesson_name"><a href="<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component" onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component'); return false;"><?php echo $sub_cat['name']; ?></a></div>
                    <?php
                    $user_id = $my->id;
                    $display = "hidden";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "hidden";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "inherit";
                            }
                        }
                    }
                    ?>
                    <div id="viewed-<?php echo $sub_cat['id']; ?>" style="visibility:<?php echo $display;?>" class="g_cell span2 viewed">
                        <i class="fa fa-eye"></i>
                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell span1 level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                       </div>
                        <?php } ?>
                </li>
                    <?php
                }
                elseif(($user_id != 0 && inCustomerTable() && $sub_cat["chb_free_courses"] == 1 && $sub_cat["step_access_courses"] == 0 && $sub_cat["selected_course"] == -1 && $not_show === FALSE && hasAtLeastOneCourse() && (($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0)))){
					$diff_date--;
                    ?>
                    <li class="g_row">
                     <div class="col_title g_cell span6 lesson_name"><a href="<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component" onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component'); return false;"><?php echo $sub_cat['name']; ?></a></div>
                    <?php
                    $user_id = $my->id;
                    $display = "hidden";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "hidden";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "inherit";
                            }
                        }
                    }
                    ?>
                    <div id="viewed-<?php echo $sub_cat['id']; ?>" style="visibility:<?php echo $display;?>" class="g_cell span2 viewed">
                        <i class="fa fa-eye"></i>
                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell span2 level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                       </div>
                        <?php } ?>
                </li>
                <?php
                }
                elseif(($user_id != 0 && isCustomer() && $sub_cat["chb_free_courses"] == 1 && $sub_cat["step_access_courses"] == 0 && $not_show === FALSE && $hascourse == TRUE &&((($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0))))){
					$diff_date--;
                    ?>
                    <li class="g_row">
                     <div class="col_title g_cell span6 lesson_name"><a href="<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component" onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruProfile&task=loginform&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&returnpage=guruprograms&graybox=true&tmpl=component'); return false;"><?php echo $sub_cat['name']; ?></a></div>
                    <?php
                    $user_id = $my->id;
                    $display = "hidden";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "hidden";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "inherit";
                            }
                        }
                    }
                    ?>
                    <div id="viewed-<?php echo $sub_cat['id']; ?>" style="visibility:<?php echo $display;?>" class="g_cell span2 viewed">
                        <i class="fa fa-eye"></i>
                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell span2 level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                       </div>
                        <?php } ?>
                </li>
                <?php
                }
                elseif(!accessToLesson($sub_cat)&&(($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0))){
					$diff_date--;
                    //$lesson_height = ($lesson_height/2)+100;
                    //$lesson_width = ($lesson_width/2)+100;
                    ?>
                   <li class="g_row">
                      <div class="col_title g_cell span9 lesson_name"><a style="<?php echo $style_grayout;?>" href="<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruEditplans&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&tmpl=component" onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruEditplans&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&tmpl=component'); return false;"><?php echo $sub_cat['name']; ?></a></div>
                    <?php
                    $user_id = $my->id;
                    $display = "hidden";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "hidden";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "inherit";
                            }
                        }
                    }
                    ?>
                    <div id="viewed-<?php echo $sub_cat['id']; ?>" style="visibility:<?php echo $display;?>" class="g_cell span2 viewed">
                        <i class="fa fa-eye"></i>
                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell span1 level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                       </div>
                        <?php } 
                        if($user_id >0 && $coursetype_details[0]["course_type"] != 0 && $coursetype_details[0]["lessons_show"] == 1 && $coursetype_details[0]["lesson_release"] >0 && $not_show === TRUE){
                        if($coursetype_details[0]["course_type"] == 1){
                            if($coursetype_details[0]["lesson_release"] == 1){
                                $date_to_display = strtotime ( '+'.$step_less++.' day' , $start_date) ;
                            }
                            elseif($coursetype_details[0]["lesson_release"] == 2){
                                $date_to_display = strtotime ( '+'.$step_less++.' week' , $start_date) ;
                            }
                            elseif($coursetype_details[0]["lesson_release"] == 3){
                                $date_to_display = strtotime ( '+'.$step_less++.' month' , $start_date) ;
                            }
                        }
                        if($diff_start >0){
                            ?>
                            <div class="available_lesson g_cell span2 available"><?php echo JText::_("GURU_AVAILABLE");?></div>
                            <?php
                        }
                        else{
                            ?>
                            <div class="g_cell span2 date_available"><?php echo date('m-d-Y', $date_to_display);?></div>
                            <?php
                        }
                    }
                    ?>
                </li>
                <?php
                }
				/*elseif($sub_cat["chb_free_courses"] == 0 && $user_id == 0 && accessToLesson($sub_cat ) && $config->open_target==1){*/
				elseif($config->open_target==0 && isCustomer() && (($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0))){
				    ?>
                 <li class="g_row">
                    <div class="col_title g_cell span9 lesson_name"><a onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JUri::root(); ?>index.php?option=com_guru&view=gurutasks&catid=<?php echo $program->catid; ?>&module=<?php echo $array["id"];?>-<?php echo $array["alias"]; ?>&cid=<?php echo $sub_cat['id'];?>-<?php echo $sub_cat["alias"];?>&tmpl=component&Itemid=<?php echo $itemid;?>'); return false; javascript:setViewed('viewed-<?php echo $sub_cat['id']; ?>', '<?php echo JUri::root()."components/com_guru/images/icons/viewed.gif"; ?>')" href="<?php echo JUri::root(); ?>index.php?option=com_guru&view=gurutasks&catid=<?php echo $program->catid; ?>&module=<?php echo $array["id"];?>-<?php echo $array["alias"]; ?>&cid=<?php echo $sub_cat['id'];?>-<?php echo $sub_cat["alias"];?>&tmpl=component&Itemid=<?php echo $itemid;?>&format=raw"><?php echo $sub_cat['name']; ?></a></div>
                    <?php
                    $user_id = $my->id;
                    $display = "hidden";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "hidden";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "inherit";
                            }
                        }
                    }
                    ?>
                    <div style="visibility:<?php echo $display; ?>;" class="g_cell pull-left span2 viewed">
                            
                                                    <i class="icon-eye-open"></i>
                            
                                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell span1 pull-left level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                       </div>
                        <?php } ?>
                </li>       
                <?php
                }
				elseif($config->open_target==0 && !isCustomer() && (($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0))){
				    ?>
                 <li class="g_row">
                    <div class="col_title g_cell span9 lesson_name"><a href="<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruEditplans&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&tmpl=component" onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruEditplans&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&tmpl=component'); return false;"><?php echo $sub_cat['name']; ?></a></div>
                    <?php
                    $user_id = $my->id;
                    $display = "hidden";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "hidden";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "inherit";
                            }
                        }
                    }
                    ?>
                    <div style="visibility:<?php echo $display; ?>;" class="g_cell pull-left span2 viewed">
                            
                                                    <i class="icon-eye-open"></i>
                            
                                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell span1 pull-left level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                       </div>
                        <?php } ?>
                </li>       
                <?php
                }
                elseif($config->open_target==1 && (($coursetype_details[0]["course_type"] == 1 && $diff_date >0) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0))){
					$diff_date--;
                    if($user_id ==0){
                        $span_lesson = 'span8';
                    }
                    else{
                        $span_lesson = 'span9';
                    }
                    
                    if($diff_start >0){
                        $span_lesson = 'span7';
                    }
                    $preview_viewd = FALSE;
                    if($prev_id == 0){
                        $prev_id = $sub_cat["id"];
                        $preview_viewd = TRUE;
                    }
                    else{
                        $lesson_viewed = guruModelguruTask::getViewLesson($prev_id);
                        $prev_id = $sub_cat["id"];
                        if($lesson_viewed){
                            $preview_viewd = TRUE;
                        }
                    }
                    
                    if($coursetype_details[0]["course_type"] == 0){// non sequential
                        $preview_viewd = TRUE;
                    }
                ?>
                <li class="g_row">
                   <div class="col_title g_cell <?php echo $span_lesson ;?> lesson_name">
                        <?php
                            if($preview_viewd){
                        ?>
                                <a onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JUri::root(); ?>index.php?option=com_guru&view=gurutasks&catid=<?php echo $program->catid; ?>&module=<?php echo $array["id"];?>-<?php echo $array["alias"]; ?>&cid=<?php echo $sub_cat['id'];?>-<?php echo $sub_cat["alias"];?>&tmpl=component&Itemid=<?php echo $itemid;?>'); return false; javascript:setViewed('viewed-<?php echo $sub_cat['id']; ?>', '<?php echo JUri::root()."components/com_guru/images/icons/viewed.gif"; ?>')" href="<?php echo JUri::root(); ?>index.php?option=com_guru&view=gurutasks&catid=<?php echo $program->catid; ?>&module=<?php echo $array["id"];?>-<?php echo $array["alias"]; ?>&cid=<?php echo $sub_cat['id'];?>-<?php echo $sub_cat["alias"];?>&tmpl=component&Itemid=<?php echo $itemid;?>&format=raw">
                                    <?php echo $sub_cat['name']; ?>
                                </a>
                        <?php
                            }
                            else{
                        ?>
                                <?php echo $sub_cat['name']; ?>
                        <?php
                            }
                        ?>
                    </div>
                <?php
                    $user_id = $my->id;
                    $display = "hidden";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "hidden";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "visible";
                            }
                        }
                    }
                    
                    $viewed_span = "span1";
                    if($diff_start > 0){
                        $viewed_span = "span2";
                    }
                    ?>
                        
                    <div id="viewed-<?php echo $sub_cat['id']; ?>" style="visibility:<?php echo $display;?>" class="g_cell <?php echo $viewed_span; ?> viewed">
                        <i class="fa fa-eye"></i>
                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell span1 level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                        </div>
                        <?php } ?>
                    <?php
                    if($user_id >0 && $coursetype_details[0]["course_type"] != 0 && $coursetype_details[0]["lessons_show"] == 1 && $coursetype_details[0]["lesson_release"] >0 && $not_show === TRUE){
                        /*if($coursetype_details[0]["course_type"] == 1){
                            if($coursetype_details[0]["lesson_release"] == 1){
                                $date_to_display = strtotime ( '+'.$step_less++.' day' , $start_date) ;
                            }
                            elseif($coursetype_details[0]["lesson_release"] == 2){
                                $date_to_display = strtotime ( '+'.$step_less++.' week' , $start_date) ;
                            }

                            elseif($coursetype_details[0]["lesson_release"] == 3){
                                $date_to_display = strtotime ( '+'.$step_less++.' month' , $start_date) ;
                            }
                        }*/
                        
                        if($diff_start >0){
                            ?>
                            <div class="available_lesson g_cell span2 available"><?php echo JText::_("GURU_AVAILABLE");?></div>
                            <?php
                        }
                    }
                    ?>
                </li>
                    <?php
                }
                elseif($sub_cat["chb_free_courses"] == 0 && $user_id == 0 ){
					//$lesson_height = ($lesson_height/2)+100;
                    //$lesson_width = ($lesson_width/2)+100;
                    ?>
                <li class="g_row">
                   <div class="col_title g_cell span9 lesson_name"><a href="<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruEditplans&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&tmpl=component" onclick="openMyModal('<?php echo $lesson_height;?>','<?php echo $lesson_width;?>','<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruEditplans&course_id=<?php echo intval($course_id);?>-<?php echo $alias.$action;?>&tmpl=component'); return false;"><?php echo $sub_cat['name']; ?></a></div>
                    <?php
                    $user_id = $my->id;
                    $display = "none";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start){
                            $display = "none";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "inherit";
                            }
                        }
                    }
                    ?>
                    <div id="viewed-<?php echo $sub_cat['id']; ?>" style="display:<?php echo $display;?>" class="g_cell span2 viewed">
                        <i class="fa fa-eye"></i>
                    </div>
                    <?php
                    if($course_level==0){
                        ?>
                        <div class="g_cell span1 level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                        </div>
                        <?php } ?>
                </li>
                    <?php
                }
                else{
					if($coursetype_details[0]["course_type"] == 1){
                        if($coursetype_details[0]["lesson_release"] == 1){
                            $date_to_display = strtotime ( '+'.$step_less++.' day' , $start_date);
                        }
                        elseif($coursetype_details[0]["lesson_release"] == 2){
                            $date_to_display = strtotime ( '+'.$step_less++.' week' , $start_date);
                        }
                        elseif($coursetype_details[0]["lesson_release"] == 3){
                            $date_to_display = strtotime ( '+'.$step_less++.' month' , $start_date);
                        }
                    }
                    $style="style='color:#999999;'";
                    $gray_style=" class=\'s_no_underline\' ";
                    if($coursetype_details[0]["lessons_show"] == '1'){
                    
                    $span_lesson = "span9";
                    $available_div = "";
                    if($diff_start >0){
                        $date_to_display = date($config->datetype, $date_to_display);
                        $span_lesson = 'span7';
                        $available_div = '<div class="available_lesson g_cell span2 available">'.$date_to_display.'</div>';
                    }       
                ?>
                    <li <?php echo $style;?> class="g_row" >
                        <div class="col_title g_cell <?php echo $span_lesson; ?> lesson_name">
                            <?php echo $sub_cat['name']; ?>
                        </div>
                        
                        <div class="g_cell span2 viewed" style="visibility:visible" id="viewed-726">
                            &nbsp;
                        </div>
                        
                        <div class="g_cell span1 level">
                            <img style="background-color:transparent;" src="<?php echo JURI::root()."components/com_guru/images/".$imgLevel; ?>" />
                        </div>
                        <?php
                            echo $available_div;
                        ?>
                    </li>
                    <?php
                    }
                    else{
                        ?>
                    <li>&nbsp;</li>
                        <?php
                    }
                }
                ?>
                <?php
                if($config->open_target == 0 ){
                    ?>
                    <?php
                }
                else{
                    $my =JFactory::getUser();
                    $user_id = $my->id;
                    $display = "none";
                    if($user_id > 0){
                        $lesson_viewed = guruModelguruTask::getViewLesson($sub_cat['id']);
                        if($coursetype_details[0]["lessons_show"] == '2' && $diff_date > $diff_start ){
                            $display = "none";
                        }
                        else{
                            if(isset($lesson_viewed) && $lesson_viewed === TRUE){
                                $display = "block";
                            }
                        }
                    }
                    ?>
                    <?php
                }
                if($coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1  && $coursetype_details[0]["lesson_release"] == 0)||($coursetype_details[0]["course_type"] == 1  && $user_id <= 0) || $coursetype_details[0]["lessons_show"] == '2' ||($user_id>0 && $not_show === FALSE) ){
                }
                else{
                }
                ?>
                <?php if($deviceType !="phone"){?>
                    <?php
                }
            }
            if(count($subcat)>0){
                ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
            }
            ?>
        </div>
        </div>
        </div>
            <?php
        }
      
        if($show_buy_button == "0" && ($buy_button_location == "1" || $buy_button_location == "2")){
            $button = createButton($buy_background, $course_id, $buy_class, $program, $program_content);
            if(!is_array($button)){
        ?>
            <div class="call_2_action buy_now">
                <div>
                    <?php
                    if(!is_array($button)){
                        echo $button;
                    }
                    ?>
                </div>
            </div>
                <?php
            }   
        }
        ?>
        </div>
</div><!-- end main div-->
    <?php
}
}
function tab2($program){
?>
    <div class="span12">
        <div class="course_view_description">
            <?php  
                echo $program->description;
            ?>  
        </div>
    </div>    
<?php    
}
function tab3($program, $config){
	$k = 0;
    $prices = guruModelguruProgram::getPrices($program->id);
    
	$chb_free_courses = $program->chb_free_courses;
	$step_access_courses = $program->step_access_courses;
	$selected_course = $program->selected_course;
	
	if($chb_free_courses == 1){
		if($step_access_courses == "2"){
			echo JText::_("GURU_FREE_GUESTS");
		}
		elseif($step_access_courses == "1"){
			echo JText::_("GURU_FREE_MEMBERS");
		}
		elseif($step_access_courses == "0"){
			if(trim($selected_course) == "" || trim($selected_course) == "-1"){
				echo JText::_("GURU_FREE_ALL_STUDENTS");
			}
			else{
				$courses = explode("|", $selected_course);
				if(isset($courses) && count($courses) > 0){
					foreach($courses as $key=>$value){
						if(intval($value) == 0){
							unset($courses[$key]);
						}
					}
				}
				
				if(count($courses) == 0){
					$courses = array("0");
				}
				
				$db = JFactory::getDBO();
				$sql = "select `name` from #__guru_program where `id` in (".implode(", ", $courses).")";
				$db->setQuery($sql);
				$db->query();
				$names = $db->loadColumn();
				
				echo JText::_("GURU_FREE_STUDENTS_SOME_COURSES").' "'.implode('", "', $names).'"';
			}
		}
	}
	elseif(isset($prices) && $prices != NULL){
?>
   <div class="clearfix">
    <div class="g_table_wrap"> 
        <table class="table table-striped">
             <tr class="g_table_header">
                <th class="g_cell_1"><?php echo  JText::_("GURU_SUBS_PLAN_NAME");  ?></th>
                <th class="g_cell_2"><?php echo JText::_("GURU_PROGRAM_DETAILS_PRICE"); ?></th>
            </tr>
    <?php
				foreach($prices as $key=>$value){
					$class = "odd";
					if($k%2 != 0){
						$class = "even";
					}
					if(trim($value["name"]) != "" || trim($value["price"]) != ""){
            ?>
                    <tr class="<?php echo $class; ?>"> 
                        <td class="g_cell_1"><b><?php echo $value["name"]; ?>:</b></td>
                        <td  class="g_cell_2">
                            <?php 
                                $currency = $config->currency;
                                $currencypos = $config->currencypos;
                                if($currencypos == 0){
                                    echo JText::_("GURU_CURRENCY_".$currency)." ".$value["price"]; 
                                }
                                else{
                                    echo $value["price"]." ".JText::_("GURU_CURRENCY_".$currency); 
                                }
                            ?>
                        </td>
                    </tr>
            <?php
					}
					$k++;
				}
        ?>
        </table>
      </div>
    </div>    
    <?php       
    }
}
function tab4($exercise,$config){
?>
<div class="course_view_exercises span12">
    <ul>
<?php
    $db = JFactory::getDBO();
    $course_id = intval(JRequest::getVar("cid", 0));
    $my = JFactory::getUser();
    foreach($exercise as $element){
        ?>
    <li class="g_row">
        <div class="g_cell span12">
    <script type="text/javascript">
            <?php
            if($my->id >0 && $element->access != 2){
                $sql = "select count(*) from #__guru_buy_courses where `userid`=".intval($my->id)." and `course_id`=".intval($course_id);
                $db->setQuery($sql);
                $db->query();
                $result = $db->loadResult();
                if($result > 0){
                    $expired = false;
                    $sql = "select expired_date from #__guru_buy_courses where userid=".intval($my->id)." and course_id=".intval($course_id);
                    $db->setQuery($sql);
                    $db->query();
                    $expired_date_string = $db->loadResult();
                    $current_date_string = "";
                    $sql = "select bc.id from #__guru_buy_courses bc, #__guru_order o where bc.userid=".intval($my->id)." and bc.course_id=".intval($course_id)." and (bc.expired_date >= '".$current_date_string."' or bc.expired_date = '0000-00-00 00:00:00') and bc.order_id = o.id and o.status <> 'Pending'";
                    $db->setQuery($sql);
                    $db->query();
                    $result = $db->loadResult();
                    if(($expired_date_string != "0000-00-00 00:00:00") || (!isset($result) || intval($result) == 0)){
                        $expired_date_int = strtotime($expired_date_string);
                        $jnow = JFactory::getDate();
                        $current_date_string = $jnow->toSQL();
                        $current_date_int = strtotime($current_date_string);
                        if($current_date_int < $expired_date_int){
                            $expired = false;
                        }
                        $sql = "select bc.course_id from #__guru_buy_courses bc, #__guru_order o where o.id=bc.order_id and bc.userid=".intval($my->id)." and o.status='Paid'";
                        $db->setQuery($sql);
                        $db->query();
                        $my_courses = $db->loadColumn();
                        if(in_array($course_id, $my_courses)){ // I bought this course
                            $difference_int = get_time_difference($current_date_int, $expired_date_int);
                            $difference = $difference_int["days"];
                            if($difference_int["days"] == 0){
                                if($difference_int["hours"] == 0){
                                    if($difference_int["minutes"] == 0){
                                        $difference = "0";
                                    }
                                    else{
                                        $difference = $difference_int["minutes"];
                                    }
                                }
                                else{
                                    $difference = $difference_int["hours"];
                                }
                            }
                            if($expired_date_string == "0000-00-00 00:00:00"){//unlimited
                                $difference_int = "1"; //default for unlimited
                            }
                            if($difference_int !== FALSE){// is not expired
                                $expired = true;
                            }
                            else{
                                $expired = false;
                            }
                        }
                        else{
                            $sql = "select count(*) from #__guru_buy_courses where `userid`=".intval($my->id)." and `course_id`=".intval($course_id)." and order_id=0";
                            $db->setQuery($sql);
                            $db->query();
                            $result = $db->loadResult();
                            if($result > 0){
                                $expired = true;
                            }
                        }
                    }
                }
                else{
                    $expired = false;
					@$expired_date_int = -1;

                }
            }
            $access_exerc = 1;
            if($expired_date_string =='0000-00-00 00:00:00'){
                $access_exerc = 1;
            }
			elseif($expired_date_int == -1){
				$access_exerc = 0;
			}
            else{
                $expired_date_int = strtotime($expired_date_string);
                $jnow = JFactory::getDate();
                $current_date_string = $jnow->toSQL();
                $current_date_int = strtotime($current_date_string);
                if($current_date_int > $expired_date_int){
                    $access_exerc = 0;                  
                }
                else{
                    $access_exerc = 1;
                }
                if($expired_date_int ==0){
                    $access_exerc = 1;      
                }
            }
            ?>
            <?php
            if($element->access == 2 || ($element->access < 2 && $my->id > 0 && $access_exerc == 1 )){  
                if($element->type == "docs" && trim($element->local) != ""){
                    ?>
                document.write('<a target="_blank" href="<?php echo JURI::root().$config->docsin.'/'.$element->local; ?>"><img src="components/com_guru/images/<?php echo $element->type; ?>.gif" alt="<?php echo $element->type; ?>" align="absmiddle" />&nbsp;<?php echo $element->local; ?></a>');
                    <?php
                }
				elseif($element->type == "docs" && trim($element->url) != ""){
                    ?>
                	document.write('<a target="_blank" href="<?php echo JURI::root().$element->url; ?>"><img src="components/com_guru/images/<?php echo $element->type; ?>.gif" alt="<?php echo $element->type; ?>" align="absmiddle" />&nbsp;<?php echo $element->name; ?></a>');
                    <?php

                }
                elseif($element->type == "file" && trim($element->local) != ""){
                    ?>
                document.write('<a target="_blank" href="<?php echo JURI::root().$config->filesin.'/'.$element->local; ?>"><img src="components/com_guru/images/<?php echo $element->type; ?>.gif" alt="<?php echo $element->type; ?>" align="absmiddle" />&nbsp;<?php echo $element->local; ?></a>');
                    <?php

                }
            }
            else{
                if(trim($element->local) != ""){
                    ?>
                document.write('<a href="<?php echo JURI::root()."index.php?option=com_guru&view=guruEditplans&course_id=".$course_id."-".$alias."&tmpl=component".$action; ?>" onclick="openMyModal(\'\',\'\',\'<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruEditplans&course_id=<?php echo $course_id;?>-<?php echo $alias; ?>&tmpl=component<?php echo $action; ?>\'); return false;" ><img src="components/com_guru/images/<?php echo $element->type; ?>.gif" alt="<?php echo $element->type; ?>" align="absmiddle" />&nbsp;<?php echo $element->local; ?></a>');
                    <?php
                }
            }
            ?>
            
    </script>
        </div>
        </li>
        <?php
    }
?>
    </ul>
</div>
<?php
}
function tab5($author,$course, $config, $course_config){
    $authors_config = json_decode($config->st_authorspage);
    $detect = new Mobile_Detect;
    $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
    if($deviceType == "phone"){
        $class_th_links = "class='teacher_links2'";
    }
    else{
        $class_th_links = "class='well teacher_links'";
    }
    ?>
<div class="course_view_teacher">
    <div id="teacherdetail" class="clearfix com-cont-wrap">
    <!-- Author Name -->
    <div class="name_guru page_title">
        <h2><?php echo $author->name; ?></h2>
    </div>
     <div class="teacher_row_guru">
        <div class="teacher_cell_guru span12">
            <div class = 'weblinks'>
                <div>
                    <div class="well teacher_links g_toolbar">
                        <?php
                        if((trim($author->show_email)!="")&&($author->show_email==1)){ ?>
                            <span class="teacher_email_guru">
                                    <a href="mailto:<?php echo $author->email; ?>">
                                        <?php echo JText::_('GURU_EMAIL');?>
                                    </a>
                                </span>
                            <?php
                        }
                        if((trim($author->show_website)!="http://")&&($author->show_website==1)){ ?>
                            <span class="guru_teacher_site">
                                    <a href="<?php echo $author->website; ?>" target="_blank">
                                        <?php echo JText::_('GURU_SITE'); ?>
                                    </a>
                                </span>
                            <?php
                        }
                        if((trim($author->show_blog)!="http://")&&($author->show_blog==1)){ ?>
                            <span class="guru_teacher_blog">
                                    <a href="<?php echo $author->blog; ?>" target="_blank">
                                        <?php echo JText::_('GURU_BLOG'); ?>
                                    </a>
                                </span>
                            <?php
                        }
                        if((trim($author->show_twitter)!="")&&($author->show_twitter==1)){ ?>
                            <span class="guru_teacher_twitter">
                                    <a href="http://www.twitter.com/<?php echo $author->twitter; ?>" target="_blank">
                                        <?php echo JText::_('GURU_TWITTER'); ?>
                                    </a>
                                </span>
                            <?php
                        }
                        if((trim($author->show_facebook)!="http://")&&($author->show_facebook==1)){ ?>
                            <span class="guru_teacher_facebook">
                                    <a href="<?php echo $author->facebook; ?>" target="_blank">
                                        <?php echo JText::_('GURU_FACEBOOK'); ?>
                                    </a>
                                </span>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
             <div>
                <div>
                <div class="description_guru clearfix" style="text-align:left">
                	<div class='image_guru'>
                        <?php
                        $config_author = json_decode($config->authorpage);
                        $img_align = $config_author->author_image_alignment; //0-left, 1-right
                        if($img_align == 0){
                            $align = "left";
                        }
                        else{
                            $align = "right";
                        }
						$guruHelper = new guruHelper();

                        if(trim($author->images)!=""){
                            $type = $course_config->course_image_size_type == "0" ? "w" : "h";
                            $guruHelper->createThumb($author->imageName,$config->imagesin."/authors", $course_config->course_image_size, $type);
                            ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_guru&view=guruauthor&layout=view&task=author&cid='.$author->id."-".JFilterOutput::stringURLSafe($author->name)); ?>">
                                
                                <img src='<?php echo JURI::root().$author->images; ?>' alt='author image' align='<?php echo $align;?>' /></a>
                            <?php   } ?>
                    </div>
                	<?php echo $author->full_bio; ?>
                </div>
                    <div class="teacher_info">
                     <h2 class="teacher_courses_heading_guru"><?php echo JText::_("GURU_TAB_AUTHOR_COURSES"); ?></h2>
                       <div class="clearfix">
                        <div class="g_table_wrap"> 
                            <div class="table_container columns">
                                <table class="table table-striped">
                                    <tr class="courses_table_header g_table_header">
                                        <th class="g_cell_1" ><?php echo JText::_("GURU_TAB_AUTHOR_COURSES_NAME"); ?></th>
                                        <th class="g_cell_2"><?php echo JText::_("GURU_TAB_AUTHOR_COURSES_LEVEL"); ?></th>
                                        <th class="g_cell_3"><?php echo JText::_("GURU_TAB_AUTHOR_COURSES_RELEASE");?></th>
                                    </tr>
                                    <?php
                                    $k = 0;
                                    if(count($course)>0){           
                                        $itemid = JRequest::getVar("Itemid", "0");
                                        for($i=0; $i<count($course); $i++){
                                            $class = "odd";
                                            if($k%2 != 0){
                                                $class = "even";
                                            }
                                    ?>
                                        <tr class="<?php echo $class; ?>">          
                                            <td class="g_cell_1">
                                                <?php 
                                                    /***$alias = trim($course[$i]->alias) == "" ? JFilterOutput::stringURLSafe($course[$i]->name) : trim($course[$i]->alias);
                                                    $courseLink = JRoute::_('index.php?option=com_guru&view=guruPrograms&layout=view&cid='.$course[$i]->id."-".$alias."&Itemid=".$itemid);***/
                                                    if(isset($course[$i]->alias)){
                                                        if(trim($course[$i]->alias) == ""){
                                                            $alias = JFilterOutput::stringURLSafe($course[$i]->name);
                                                        }
                                                        else{
                                                            $alis = trim($course[$i]->alias);
                                                        }
                                                        $courseLink = JRoute::_('index.php?option=com_guru&view=guruPrograms&layout=view&cid='.$course[$i]->id."-".$alias."&Itemid=".$itemid1);
                                                    }
                                                    else{
                                                        $courseLink = JRoute::_('index.php?option=com_guru&view=guruPrograms&layout=view&cid='.$course[$i]->id."&Itemid=".$itemid1);
                                                    }
                                                ?>
                                                <a href='<?php echo $courseLink; ?>'>
                                                    <?php echo $course[$i]->name; ?>
                                                </a>
                                            </td>
                                            <td class="g_cell_2"><img src='<?php echo JURI::root()."components/com_guru/images/".$course[$i]->level.".png"; ?>'/></td>
                                            <?php
                                                $int_date    = strtotime($course[$i]->startpublish);
                                                $date        = date($config->datetype, $int_date);
                                            ?>
                                            <td class="g_cell_3"><?php echo $date; ?></td>
                                        </tr>
                                    <?php
                                    $k++;
                                        }
                                        
                                    }
                                    ?>
                            </table>
                            </div>
                        </div>
                      </div>  
                </div>
               </div>
             </div>   
</div>
</div>
</div>
</div>
<?php
}
function tab6($requirements, $program){
?>
<div>
    <div class="course_view_requirements">
    <?php
        if(!empty($requirements)){
    
            ?>
    
        <p><strong><?php echo JText::_("GURU_TAB_REQUIREMENTS_COURSES"); ?>:</strong></p>
    
        <ul>
    
            <li>
    
                <?php
    
                $requirements=implode("</li><li>",$requirements);
    
                echo $requirements;
    
                ?>
    
            </li>
    
        </ul>
    
            <?php } ?>
    
        <?php
    
        if(trim($program->pre_req) != ""){
    
            ?>
    
        <p><strong><?php echo JText::_("GURU_TAB_REQUIREMENTS_OTHERS");?>:</strong></p>
    
            <?php
    
            echo $program->pre_req;
    
        }
    
    
    
        if(trim($program->pre_req_books) != ""){
    
            ?>
    
        <p><strong><?php echo JText::_("GURU_TAB_REQUIREMENTS_BOOKS");?>:</strong></p>
    
            <?php
    
            echo $program->pre_req_books;
    
        }
    
    
    
        if(trim($program->reqmts) != ""){
    
            ?>
    
        <p><strong><?php echo JText::_("GURU_TAB_REQUIREMENTS_MISC");?>:</strong></p>
    
            <?php
    
            echo $program->reqmts;
    
        }
        ?>
     </div> 
 </div>      
    <?php
}
function createTabs($program, $author, $program_content, $exercise, $requirements, $course, $config, $course_config){
    jimport('joomla.html.pane');
    jimport('joomla.utilities.date');
    JHtml::_('behavior.framework');
    
    $document = JFactory::getDocument();
    $document->addStyleSheet("components/com_guru/css/tabs_css.css");
    $document->addStyleSheet("components/com_guru/css/tabs.css");
    
    $document->addScript("components/com_guru/js/programs.js");
    
    $itemid = JRequest::getVar("Itemid", "0");
    $user = JFactory::getUser();
    $user_id = $user->id;
    $lesson_size = $config->lesson_window_size;
    $lesson_size = explode("x", $lesson_size);
    $lesson_height = $lesson_size["0"];
    $lesson_width = $lesson_size["1"];
    
    if(trim($lesson_height) == "" || trim($lesson_height) == "0"){
        $lesson_height == 1000;
    }
    if(trim($lesson_width) == "" || trim($lesson_width) == "0"){
        $lesson_width == 600;
    }
    
    $action_bool = getAction();
    $action = "";
    if($action_bool === TRUE){
        $action = "&action=renew";
    }

    ?>
    <div>
 <!-- start mobile version -->
        <div class="g_mobile">
            <div class="container-fluid">
                <div class="call_2_action buy_now">
                    <div>
                        <?php boostrap_buttons($program, $course, $config, $course_config); ?>
                    </div>
                </div>
                
                <div id="accordion" class="accordion">
                    <?php
                    	if(!empty($program_content) && $course_config->course_table_contents == "0"){?>
                            <div class="accordionItem">
                                <h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_TAB_TABLE_CONTENT");?></h3>
                                <div class="clearfix tab-body  g_accordion-group g_content_active">
                                    <?php 
                                        tab1boost($program, $author, $program_content, $exercise, $requirements, $course, $config, $course_config);
                                    ?>	
                                </div>
                            </div>

					<?php
						}
							
						if($program->description != "" && $course_config->course_description_show == "0"){?>
                        	<div class="accordionItem">
								<h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_TAB_DESCRIPTION");?></h3>
								<div class="clearfix tab-body  g_accordion-group g_content_active">
									<?php
										tab2($program);
									?>
								</div>
							</div>
					<?php
                    	}
						
						if($course_config->course_tab_price == "0" && !is_array($button)){
					?>
                    		<div class="accordionItem">
                                <h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_BUY_PRICE");?></h3>
                                <div class="clearfix tab-body  g_accordion-group g_content_active">
                                	<?php
                                		if(!isset($button)){
                                			$button = "";
                                		}
                                		tab3($program, $config);
                                	?>
                                </div>
							</div>
					<?php
                    	}
						
                        if(!empty($exercise)){
                    ?>
                    		<div class="accordionItem">
                            	<h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_EXERCISE_FILES");?></h3>
                            	<div class="clearfix tab-body  g_accordion-group g_content_active">
                            		<?php
                                    	tab4($exercise,$config);
									?>
                            	</div>
							</div>
					<?php
						}
						
						if($course_config->course_author == "0"){
					?>
                    		<div class="accordionItem">
                    			<h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_TAB_AUTHOR");?></h3>
                    			<div class="clearfix tab-body  g_accordion-group g_content_active">
									<?php 
                                    	tab5($author,$course, $config, $course_config);
                                	?>
                            	</div>
							</div>
					<?php
                     	}
						
						if((!empty($requirements) || $program->pre_req!="" || $program->pre_req_books!="" || $program->reqmts!="") && ($course_config->course_requirements == "0") && !is_array($button)){
					?>
                    		<div class="accordionItem">
                                <h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_TAB_REQUIREMENTS");?></h3>
                                <div class="clearfix tab-body  g_accordion-group g_content_active">
                                	<?php 
                                		tab6($requirements, $program);
                                	?>
                                </div>
							</div>
					<?php
                    	}
					?>
                </div>
            </div>
        </div>
        <!-- end mobile version -->
        <!-- start computer/tablet version -->
        <div class="g_hide_mobile">
            <div class=" guru-course-view" id="guru_tabs">
                <div>
                    <ul id="guru_tabs_navs" class="nav nav-tabs clearfix">
                         <?php if(!empty($program_content) && $course_config->course_table_contents == "0"){?>
                                <li id="li-tab1" class="ui-tabs-active"><a href="#" onclick="javascript:changeGuruTab('tab1'); return false;"><?php echo JText::_("GURU_TAB_TABLE_CONTENT");?></a></li>
                         <?php }?>
                         <?php  if($program->description != "" && $course_config->course_description_show == "0"){?>
                                    <li id="li-tab2"><a href="#" onclick="javascript:changeGuruTab('tab2'); return false;"><?php echo JText::_("GURU_TAB_DESCRIPTION");?></a></li>
                         <?php }?>
                         <?php if($course_config->course_tab_price == "0" && !is_array($button)){?>
                                    <li id="li-tab3"><a href="#" onclick="javascript:changeGuruTab('tab3'); return false;"><?php echo JText::_("GURU_BUY_PRICE");?></a></li>
                         <?php }?>
                         <?php  if(!empty($exercise)){?>
                                    <li id="li-tab4"><a href="#" onclick="javascript:changeGuruTab('tab4'); return false;"><?php echo JText::_("GURU_EXERCISE_FILES");?></a></li>
                         <?php }?>
                         <?php if($course_config->course_author == "0"){?>
                                    <li id="li-tab5"><a href="#" onclick="javascript:changeGuruTab('tab5'); return false;"><?php echo JText::_("GURU_TAB_AUTHOR");?></a></li>
                         <?php } ?>
                         <?php if((!empty($requirements) || $program->pre_req!="" || $program->pre_req_books!="" || $program->reqmts!="") && ($course_config->course_requirements == "0") && !is_array($button)){?>
                                    <li id="li-tab6"><a href="#" onclick="javascript:changeGuruTab('tab6'); return false;"><?php echo JText::_("GURU_TAB_REQUIREMENTS");?></a></li>
                         <?php }?>
                </ul>
             </div>
             
             <div class="tab-content">
                 <div id="tab1" style="display:block;">
                     <?php if(!empty($program_content) && $course_config->course_table_contents == "0"){
                    
                        tab1($program, $author, $program_content, $exercise, $requirements, $course, $config, $course_config);
                        }
                     ?>
                 </div>
                 <div id="tab2" style="display:none;">
                     <?php
                     if($program->description != "" && $course_config->course_description_show == "0"){
                         tab2($program);
                     }
                     ?>
                 </div>
                 <div id="tab3" style="display:none;">
                     <?php
                     if(!isset($button)){
                         $button = "";
                     }
                     if($course_config->course_tab_price == "0" && !is_array($button)){
                         tab3($program, $config);
                     }
                     ?>
                 </div>
                 <div id="tab4" style="display:none;">
                     <?php
                     if(!empty($exercise)){
                         tab4($exercise,$config);
                     }
                     ?>
                 </div>
                 <div id="tab5" style="display:none;">
                     <?php
                     if($course_config->course_author == "0"){
                         tab5($author,$course, $config, $course_config);
                     }
                     ?>
                 </div>
                 <div id="tab6" style="display:none;">
                     <?php
                     if((!empty($requirements) || $program->pre_req!="" || $program->pre_req_books!="" || $program->reqmts!="") && ($course_config->course_requirements == "0") && !is_array($button)){
                         tab6($requirements, $program);
                     }
                     ?>
                 </div>
             </div>
         </div>
    </div>        
 </div>
 <?php
 }
 ?>