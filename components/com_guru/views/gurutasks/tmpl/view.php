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
JHTML::_('behavior.modal', 'a.modal');
JHTML::_('behavior.framework');
include(JPATH_SITE.DS.'components'.DS.'com_guru'.DS.'models'.DS.'guruprogram.php');
include(JPATH_SITE.DS.'components'.DS.'com_guru'.DS.'models'.DS.'guruorder.php');
$document = JFactory::getDocument();

require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");

$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');	

$callback = JRequest::getVar("submit_action");

$Itemid = JRequest::getVar('Item', 0);   
$document = JFactory::getDocument();
$db = JFactory::getDBO();
$sql = "SELECT guru_turnoffjq  FROM  `#__guru_config` WHERE id=1";
$db->setQuery($sql);
$db->query();
$guru_turnoffjq = $db->loadResult();

if( $guru_turnoffjq != 0){ 
	$document->addScript('components/com_guru/js/jquery-1.9.1.min.js');	
}

$app = JFactory::getApplication();
$step=$this->task;

if(isset($step->id) && ($step != false)){
    $step_id = $step->id;
    $pid = $step->pid;
    //REMOVED BY JOSEPH 08/04/2015
    //$this->saveLesson($step_id, $pid);

    //ADDED BY JOSEPH 08/04/2015
    $credit_val = $step->credit;
    //echo $credit_val;
    //echo "<pre>";
    //var_dump($step);
    //echo "</pre>";
    //die();
    $this->saveLesson($step_id, $pid, $credit_val);
}

if(!isset($step->id)){
}

if ($step == false || $step==""){
     $view_get = JRequest::getVar("view");
    $email_r = JRequest::getVar("e");
    $catid = JRequest::getVar("catid");
    $module_lesson = JRequest::getVar("module");
    $lesson_id = JRequest::getVar("cid");
    if($view_get == "guruTasks" && $email_r ==1){
        $link = JRoute::_("index.php?option=com_guru&controller=guruLogin&task=&returnpage=registerforlogout&view=".$view_get."&e=".$email_r."&catid=".$catid."&module=".$module_lesson."&cid=".$lesson_id."");
    }
    else{
         $link = JRoute::_("index.php?option=com_guru&controller=guruLogin&task=&returnpage=registerforlogout");
    }
    $app->redirect($link);
}

$sql = "Select media_id from #__guru_mediarel where type_id=".$step->id." and type='scr_m' and layout=12";
$db->setQuery($sql);
$db->query();
$id= $db->loadResult();

//set meta data for each step
$document = JFactory::getDocument();
if(isset($step->metatitle) && trim($step->metatitle) != ""){
    $document->setTitle(trim($step->metatitle));
}
else{
    $document->setTitle($step->name);
}
if(isset($step->metakwd) && trim($step->metakwd) != ""){
    $document->setMetaData("keywords", trim($step->metakwd));
}
else{
    $document->setMetaData('keywords', $step->name);
}
if(isset($step->metadesc) && trim($step->metadesc) != ""){
    $document->setDescription(trim($step->metadesc));
}
else{
    $document->setMetaData('description', @$step->description );
}

$user = JFactory::getUser();

$db = JFactory::getDBO();
$sql = "select open_target, lesson_window_size from #__guru_config";
$db->setQuery($sql);
$db->query();
$result = $db->loadAssocList();


$target = intval($result["0"]["open_target"]);
$lesson_size = $result["0"]["lesson_window_size"];
$lesson_size = explode("x", $lesson_size);
$lesson_height = $lesson_size["0"];
$lesson_width = $lesson_size["1"];

$document = JFactory::getDocument();
$document->addScript("components/com_guru/js/programs.js");
$document->addStyleSheet("components/com_guru/css/guru_task.css");
$document->addStyleSheet("components/com_guru/css/guru_style.css");
$guruModelguruTask = new guruModelguruTask();
$guruModelguruOrder = new guruModelguruOrder();

$configs = $guruModelguruTask->getConfig();

if(isset($step) && ($step != false)){
	$skip_modules_course = $guruModelguruTask->getSkipAction($step->pid);
}
else{
}
$module_pozition = "0";
$certificates = $guruModelguruTask->getCertificate();

$is_final = $guruModelguruTask->getIsFinal($step->id);

if($is_final == ""){
    $is_final = 0;
}
$db = JFactory::getDBO();
$sql = "select avg_certc from #__guru_program where id=".$step->pid;
$db->setQuery($sql);
$db->query();
$avg_certif = $db->loadResult();


?>
<script>
function submitgurucomment(id){
    message = encodeURIComponent(document.getElementById('message').value);
     var req = new Request.HTML({
            url: "<?php echo JURI::base();?>index.php?option=com_guru&controller=guruTasks&task=lessonmessage&lessonid="+id+"&message="+message+'&format=raw',
            data: { 'do' : '1' },
            onComplete: function(response){   
                document.getElementById("gurucommentbox").empty().adopt(response);
                document.getElementById('message').value = "";
				document.getElementById('submitb').disabled = "disabled";
            }
        }).send();
}       

function deletegurucomment(id, uid, comid){
    var req = new Request.HTML({
            url: "<?php echo JURI::base();?>index.php?option=com_guru&controller=guruTasks&task=deletecom&lessonid="+id+"&uid="+uid+"&comid="+comid+'&format=raw',
            data: { 'do' : '1' },
            onComplete: function(response){   
                document.getElementById("gurucommentbox").empty().adopt(response);
            }
        }).send();       

}

function guruChangeText(){
    if (document.getElementById('message').value.length > 0){
        document.getElementById('submitb').disabled = "";
    }
	else{
		document.getElementById('submitb').disabled = "disabled";
	}
}
function editgurucomment(comid){
	var gurutext = document.getElementById('gurupostcomment'+comid).innerHTML;
	document.getElementById('gurupostcomment'+comid).style.display = "none";
	document.getElementById("message1"+comid).value = gurutext;
	document.getElementById('message1'+comid).style.display = "block";
	if(document.getElementById('delete'+comid)){
		document.getElementById('delete'+comid).style.display = "none";
	}
	if(document.getElementById('edit'+comid)){
		document.getElementById('edit'+comid).style.display = "none";
	}
	document.getElementById('save'+comid).style.display = "block";
}
function savegurucomment(lid, comid){
	 message = encodeURIComponent(document.getElementById('message1'+comid).value);
     var req = new Request.HTML({
            url: "<?php echo JURI::base();?>index.php?option=com_guru&controller=guruTasks&task=editformgurupost&lessonid="+lid+"&comid="+comid+"&message="+message+'&format=raw',
            data: { 'do' : '1' },
            onComplete: function(response){  
				document.getElementById("gurupostcomment"+comid).value =document.getElementById("gurupostcomment"+comid).empty().adopt(response); 
				document.getElementById('gurupostcomment'+comid).style.display = "block";
                document.getElementById('message1'+comid).style.display = "none";
				if(document.getElementById('delete'+comid)){
					document.getElementById('delete'+comid).style.display = "table-row";
				}
				if(document.getElementById('edit'+comid)){

					document.getElementById('edit'+comid).style.display = "block";
				}
				document.getElementById('save'+comid).style.display = "none";
            }
        }).send();
}

</script>
<?php
$user = JFactory::getUser();

$sql = "select count(*) from #__extensions where element='com_kunena'";
$db->setQuery($sql);
$db->query();
$count = $db->loadResult();

$sql = "select allow_stud from #__guru_kunena_forum where id=1";
$db->setQuery($sql);
$db->query();
$allow_stud = $db->loadResult();

$sql = "select allow_edit from #__guru_kunena_forum where id=1";
$db->setQuery($sql);
$db->query();
$allow_edit = $db->loadResult();

$sql = "select allow_delete  from #__guru_kunena_forum where id=1";
$db->setQuery($sql);
$db->query();
$allow_delete = $db->loadResult();
    
if($count >0){
	if($deviceType !='phone'){
		$rows_cols = 'rows="5" cols="95"';
	}
	else{
		$rows_cols = 'rows="3"';
	}
    $gurucommentbox = '
    <div id="'.$step->id.'" class="gurucommentform">
        <div class="guru row-fluid gurucommentform-title">'.JText::_('GURU_COMMENT_LESSON').'</div>
        <form method="post" name="postform">
            <table>
                <tr>
                    <td valign="top">
                        <table>
                        <tr>
                            <td><span class="guru row-fluid">
                            '.JText::_('GURU_MESSAGE').' </span></td>
                        </tr>
                        <tr>
                            <td colspan="2"><textarea onkeyup="javascript:guruChangeText();" style="width:100%" name="message" id="message" '.$rows_cols.' ></textarea></td>
                        </tr>
                        <tr>
                            <td><input class="btn btn-success" disabled="disabled" id="submitb" name="submitb" type="button" onclick="javascript:submitgurucomment('.$step->id.');" value="'.JText::_('GURU_QUIZ_SUBMIT') .'" /></td>
                        </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </div>';
}



?>
<?php
function accessToLesson($lesson_id){   
    $db = JFactory::getDBO();
    $user = JFactory::getUser();
    $user_id = $user->id;
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
        $module_id = intval(JRequest::getVar("module"));
        $sql = "select pid from #__guru_days where id=".intval($module_id);
        $db->setQuery($sql);
        $db->query();
        $course_id = intval($db->loadResult());
        //$course_id = intval(JRequest::getVar("catid", 0));
        $jnow = JFactory::getDate();
        $current_date_string = $jnow->toSQL();
        $sql = "select count(*) from #__guru_buy_courses where userid=".intval($user_id)." and course_id=".intval($course_id)." and (expired_date >= '".$current_date_string."' or expired_date = '0000-00-00 00:00:00')";
        $db->setQuery($sql);
        $db->query();
        $result = $db->loadResult();   
        if($result == 0){
            return false;
        }
        return true;   
    }
    return false;
}
    $module_id = intval(JRequest::getVar("module"));
    $sql = "select pid from #__guru_days where id=".intval($module_id);
    $db->setQuery($sql);
    $db->query();
    $catid = intval($db->loadResult());
//$catid = JRequest::getVar("catid", "0");

    $layout_style_display=array();
    for($i=1;$i<=12;$i++){
        if ($i==$step->layout){
             $layout_style_display[$i]="style='display:block;'";
            //$layout_style_display[$i]="style='visibility:visible;'";
        }   
        else{
            $layout_style_display[$i]="style='display:none;'";
            //$layout_style_display[$i]="style='visibility:hidden;'";
        }
    }
    //print_r($layout_style_display); exit;
    $progres_bar = $guruModelguruTask->getProgresBarSettings();
    ?>
    <?php
        $author_id = JRequest::getVar("author", "");
         $Itemid = JRequest::getVar('Item', 0);   
         
         //---------------SECV_NON-SECV---------------------//
         $user = JFactory::getUser();
         $user_id = $user->id;
         if($user_id > 0){
            $db = JFactory::getDBO();
            $sql = "select DATE_FORMAT(buy_date,'%Y-%m-%d') as date_enrolled from #__guru_buy_courses where course_id=".intval($step->pid)." and userid =".$user_id;
            $db->setQuery($sql);
            $db->query();
            $date_enrolled = $db->loadResult();   
            $date_enrolled = strtotime($date_enrolled);   
           
           
        }
        $coursetype_details = guruModelguruProgram::getCourseTypeDetails($step->pid);
        $start_relaese_date = $coursetype_details[0]["start_release"];
        $start_relaese_date = strtotime($start_relaese_date);

       
        $jnow = JFactory::getDate();
        $date9 = $jnow->toSQL();
        $date_9 = date("Y-m-d",strtotime($date9));

        $date9 = strtotime($date9);
        //$interval = $start_relaese_date->diff($date9);
        $interval = abs($date9 - $start_relaese_date);

        //echo"<pre>";print_r(floor($interval/(60*60*24)));
        $dif_days = floor($interval/(60*60*24));
        $dif_week = floor($interval/(60*60*24*7));
        $dif_month = floor($interval/(60*60*24*30));
       
        $diff_enrolled = abs($date9 - @$date_enrolled);
        $dif_days_enrolled = floor($diff_enrolled/(60*60*24));
       
        if($coursetype_details[0]["course_type"] == 1){
            if($coursetype_details[0]["lesson_release"] == 1){
                $diff_date = 1+$dif_days_enrolled;
               
            }
            elseif($coursetype_details[0]["lesson_release"] == 2){
                $dif_days_enrolled = intval($dif_days_enrolled /7);
                $diff_date = $dif_days_enrolled;
            }
            elseif($coursetype_details[0]["lesson_release"] == 3){
                $dif_days_enrolled = intval( $dif_days_enrolled /30);
                $diff_date = $dif_days_enrolled;
            }
        }
       
	   	$sql = "SELECT media_id FROM #__guru_mediarel WHERE type='scr_m' and type_id=".intval($step->id);
		$db->setQuery($sql);
		$result = $db->loadResult();
		$quiz_id = $result;
	   
		$nb_of_questions = JRequest::getVar("question_number");
		$quizz_fe_content = $this->getQuizCalculation($quiz_id, $step->pid, $nb_of_questions);  
		
		$completed_course = $guruModelguruOrder->courseCompleted($user->id,$step->pid);
		$course_certificate_term = $guruModelguruTask->getCertificateTerm($step->pid);
		$scores_avg_quizzes =  $guruModelguruTask->getAvgScoresQ($user->id,$step->pid);	
		$certificates[0]["avg_cert"] = $avg_certif;
    ?>
    
    <div class="g_lesson_content clearfix">
    	<div id="g_lesson_navs" class="clearfix">
        	<div class="g_lesson_nav_bar clearfix">
        		<div class="pull-left">
                	<a href="<?php echo JRoute::_('index.php?option=com_guru&view=guruPrograms&task=view&cid='.$step->pid.'&Itemid='.$Itemid); ?>">
                    <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/home.png"; ?>" alt="<?php echo JText::_("GURU_COURSE_HOME_PAGE"); ?>" title="<?php echo JText::_("GURU_COURSE_HOME_PAGE"); ?>"/>
                </a>
            	</div>    
            <?php
                    $sql = "SELECT max_score FROM #__guru_quiz WHERE id=".intval($result);
                    $db->setQuery($sql);
                    $result_maxs = $db->loadResult();

                    $sql = "SELECT id, score_quiz, time_quiz_taken_per_user  FROM #__guru_quiz_taken WHERE user_id=".intval($user_id)." and quiz_id=".intval($result)." and pid=".$step->pid." ORDER BY id DESC LIMIT 0,1";
                    $db->setQuery($sql);
                    $result_q = $db->loadObject();
                   
                    $first= explode("|", @$result_q->score_quiz);
                   
                    @$res = intval(($first[0]/$first[1])*100);
					//var_dump($result_q); var_dump($res);die();
           
          			if($completed_course == 1){
						$completed_course = true;
					}
            //--------for my certificate--------------------
            if($course_certificate_term == 2 && $completed_course == true ){
                $this->InsertMyCertificateDetails1($step->pid);
                $this->emailCertificate1($step->pid);
            }   
            if($course_certificate_term == 3 && isset($result_maxs) && $res >= intval($result_maxs) ){
                $this->InsertMyCertificateDetails1($step->pid);
                $this->emailCertificate1($step->pid);
            }
            if($course_certificate_term == 4 && $scores_avg_quizzes >= intval($certificates[0]["avg_cert"])){
                $this->InsertMyCertificateDetails1($step->pid);
                $this->emailCertificate1($step->pid);
            }
            if($course_certificate_term == 5 && $completed_course==true && isset($result_maxs) && $res >= intval($result_maxs)){
                $this->InsertMyCertificateDetails1($step->pid);
                $this->emailCertificate1($step->pid);
            }
            if($course_certificate_term == 6 && $completed_course==true && isset($scores_avg_quizzes) && ($scores_avg_quizzes >= intval($certificates[0]["avg_cert"]))){
                $this->InsertMyCertificateDetails1($step->pid);
                $this->emailCertificate1($step->pid);
            }
            //----------------------------------------------
           
           
           
            //start previw--------------------------------------------------------------------------------
            ?>
            
            <div class="pull-right">
                <?php
                    $tmpl = "";
                    if($target == "1"){
                        $tmpl = "&tmpl=component";   
                    }
                ?>
           
                <?php
                if(@$step->prevs != 0 && $step->prevs != "-1"){
                    $chb_free_courses1 = $guruModelguruTask->getChbAccessCourses();
                    $step_access_courses1 = $guruModelguruTask->getStepAccessCourses();
                    if($chb_free_courses1 == 1 && $step_access_courses1 == 2){
                        $step->prevaccess = 2;
                    }
                   
                    if((($user->id <= 0) && (isset($step->prevaccess) && ($step->prevaccess != 2))) || ($user->id > 0 && accessToLesson($step->prevs) === FALSE && $step->prevaccess != 2)){
                        if($target == "0"){
                    ?>
                            <a rel="{handler: 'iframe', size: {x: 400, y: 400}, iframeOptions: {id: 'lesson_editplans'}}" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruEditplans&course_id=".intval($catid)."&tmpl=component"); ?>" class="modal">
                            <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/back.png"; ?>" alt="<?php echo JText::_("GURU_NEXT_LESSON"); ?>" title="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>"/>
                        </a>
                    <?php       
                        }
                        else{
                    ?>   
                            <a href="<?php echo JRoute::_("index.php?option=com_guru&view=guruEditplans&course_id=".intval($catid)."&tmpl=component"); ?>">
                                <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/back.png"; ?>" alt="<?php echo JText::_("GURU_NEXT_LESSON"); ?>" title="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>" />
                            </a>   
                <?php
                        }
                    }
                    else{
                ?>
                        <a href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".$step->prev_module."&cid=".$step->prevs.$tmpl); ?>">
                            <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/back.png"; ?>" alt="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>" title="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>"/>
                        </a>
                <?php
                    }
                }
                elseif(@$step->prevs == "0"){
                    $current_module = $step->prev_module;
                   
                    $chb_free_courses1 = $guruModelguruTask->getChbAccessCourses();
                    $step_access_courses1 = $guruModelguruTask->getStepAccessCourses();
                    if($chb_free_courses1 == 1 && $step_access_courses1 == 2){
                        $step->prevaccess = 2;
                    }
                                       
                    if($user->id<=0 && @$step->prevaccess!=2){
                        if($target == "0"){
                    ?>
                            <a href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".intval($step->prev_module)."&action=viewmodule"); ?>">
                                <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/back.png"; ?>" alt="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>" title="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>"/>
                            </a>
                    <?php       
                        }
                        else{
                           
                    ?>
                             <a href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".intval($step->prev_module)."&action=viewmodule&tmpl=component"); ?>">
                                <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/back.png"; ?>" alt="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>" title="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>"/>
                            </a>
                    <?php
                        }                   
                    }
                    else{
                        if($skip_modules_course == "0"){ //not skip module                       
                    ?>
                            <a href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".intval($step->prev_module)."&action=viewmodule".$tmpl); ?>">
                                <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/back.png"; ?>" alt="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>" title="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>"/>
                            </a>
                    <?php
                        }
                        else{
                            $prev_module = $guruModelguruTask->getPrevModule($step->pid, $step->prev_module);
                            $cid_array = $guruModelguruTask->getAllSteps($prev_module);
                            $cid = "0";
                            if(isset($cid_array) && is_array($cid_array) && count($cid_array) > 0){
                                $cid = $cid_array[count($cid_array)-1]["id"];
                            }
                    ?>
                            <a href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".$prev_module."&cid=".$cid.$tmpl); ?>">
                                <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/back.png"; ?>" alt="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>" title="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>"/>
                            </a>
                    <?php
                        }
                    }
                }
                elseif(@$step->prevs == "-1"){
                    //do nothing, no more preview
                }               
                //stop previw--------------------------------------------------------------------------------
				if($target == 0){
                ?>
                    <a onClick="window.location.reload();">
                        <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/repeat.png"; ?>" alt="<?php echo JText::_("GURU_REFRESH_PAGE"); ?>" title="<?php echo JText::_("GURU_REFRESH_PAGE"); ?>"/>
                    </a>
               
                <?php 
				} 
				else{
				?>
					 <a onClick="window.location.reload();">
                        <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/repeat.png"; ?>" alt="<?php echo JText::_("GURU_REFRESH_PAGE"); ?>" title="<?php echo JText::_("GURU_REFRESH_PAGE"); ?>"/>
                    </a>
				<?php
				}              
                //start next--------------------------------------------------------------------------------   
               
                $lessons_order = $guruModelguruTask->getLessonOrder($step->pid);
                $key_of_lesson = array_search($step->nexts, $lessons_order);
               
			   if(isset($step->id)){
				$isquizornot =  $guruModelguruTask->getIsQuizOrNot($step->id);
				$studfailedquiz = $guruModelguruTask->studFailedQuiz($step->id);
			   }	
			   
                if(isset($step->nexts) && ($step->nexts != 0)){
					$chb_free_courses1 = $guruModelguruTask->getChbAccessCourses();
                    $step_access_courses1 = $guruModelguruTask->getStepAccessCourses();
                    if($chb_free_courses1 == 1 && $step_access_courses1 == 2){
                        $step->nextaccess = 2;
                    }
                    if(($user->id <= 0 && $step->nextaccess != 2) || ($user->id > 0 && (accessToLesson($step->nexts) === FALSE && $step->nextaccess != 2))){
                ?>
                        <a rel="{handler: 'iframe', size: {x: 400, y: 400}, iframeOptions: {id: 'lesson_editplans'}}" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruEditplans&course_id=".intval($catid)."&tmpl=component"); ?>" class="modal">
                            <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/next.png"; ?>" alt="<?php echo JText::_("GURU_NEXT_LESSON"); ?>" title="<?php echo JText::_("GURU_NEXT_LESSON"); ?>"/>
                        </a>
                <?php
                       
                    }
                    else{
						if(($coursetype_details[0]["course_type"] == 1 && $diff_date > $key_of_lesson+1)  || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0)){
                    ?>
                           <a id="nextbut" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".$step->next_module."&cid=".$step->nexts.$tmpl); ?>">
                                <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/next.png"; ?>" alt="<?php echo JText::_("GURU_NEXT_LESSON"); ?>" title="<?php echo JText::_("GURU_NEXT_LESSON"); ?>"/>
                            </a>
                    <?php
   
						}   
                    }
                }
                else{
                    $next_module = "0";
                    $stop = false;
                    $current_module = $step->module;
                    while(!$stop){
                        $next_module = $guruModelguruTask->getNextModule($step->pid, $current_module);                       
                        if($next_module == "0"){
                            $stop = true;
                        }
                        else{
                            $cid_array = $guruModelguruTask->getAllSteps($next_module);
                            if(count($cid_array) > 0){
                                $stop = true;
                            }
                            else{
                                $current_module = $next_module;
                            }
                        }
                    }
                    if($next_module != "0"){
                        $guruModelguruTask->setModule($next_module);
                        $cid_array = $guruModelguruTask->getAllSteps($next_module);
                        $cid = "0";
                        if(isset($cid_array) && is_array($cid_array) && count($cid_array) > 0){
                            $cid = $cid_array["0"]["id"];
                            $step->nextaccess = $cid_array["0"]["step_access"];
                        }
                        if($cid != "0"){
                            $chb_free_courses1 = $guruModelguruTask->getChbAccessCourses();
                            $step_access_courses1 = $guruModelguruTask->getStepAccessCourses();
                            if($chb_free_courses1 ==1 && $step_access_courses1 == 2){
                                $step->nextaccess = 2;
                            }
							
                       
                            if($user->id <= 0 && $step->nextaccess != 2){
                    ?>
                                <a id="nextbut" rel="{handler: 'iframe', size: {x: <?php echo $lesson_width; ?>, y: <?php echo $lesson_height; ?>},iframeOptions: {id: 'lesson_editplans'}}" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".$next_module."&cid=".$cid."&tmpl=component"); ?>" class="modal">
                                    <img  style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/next.png"; ?>" alt="<?php echo JText::_("GURU_NEXT_LESSON"); ?>" title="<?php echo JText::_("GURU_NEXT_LESSON"); ?>"/>
                                </a>
                    <?php
                            }

                            else{
                           
                                if($skip_modules_course == "0" && (($coursetype_details[0]["course_type"] == 1 &&  $diff_date>= $key_of_lesson+2 ) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0))){
                               
                                 //not skip module
                    ?>
                                    <a id="nextbut" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".$step->next_module."&action=viewmodule".$tmpl); ?>">
                                        <img  style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/next.png"; ?>" alt="<?php echo JText::_("GURU_NEXT_LESSON"); ?>" title="<?php echo JText::_("GURU_NEXT_LESSON"); ?>"/>
                                    </a>
                    <?php
                                }
                                else{ //skip module
                                    if(($coursetype_details[0]["course_type"] == 1 && $diff_date>= $key_of_lesson+2) || $coursetype_details[0]["course_type"] == 0 || ($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] == 0)){
                    ?>
                                    <a id="nextbut" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".$step->next_module."&cid=".$cid.$tmpl); ?>">
                                        <img  style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/next.png"; ?>" alt="<?php echo JText::_("GURU_NEXT_LESSON"); ?>" title="<?php echo JText::_("GURU_NEXT_LESSON"); ?>"/>
                                    </a>
                    <?php
                                    }
                                }                               
                            }//log in
                        }
                    }//cid != "0"   
                   
                }
                //stop next--------------------------------------------------------------------------------
				?>
					<?php
                    if(@$next_module == "0"){
                        $tmpl = "&tmpl=component";     
                       
                    if($course_certificate_term != 1){
                        
                        $cid = $step->id;
                        $tmpl = "";
                        if($target == "1"){
                            $tmpl = "&tmpl=component";   
                        }
                                
                        if($course_certificate_term == 2 && $completed_course == true ){
                            ?>
                                <a id="nextbut2"  href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&task=viewcertificate".$tmpl."&certificate=1&pdf=1&dw=2&ci=".$step->pid."&prev_lesson_id=".$cid."&module_prev_lesson=".$step->prev_module."&catid=".$catid."&course_id=".$step->pid);?>">
                                        <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/certificate.png"; ?>" alt="<?php echo JText::_("GURU_MYCERTIFICATES"); ?>" title="<?php echo JText::_("GURU_MYCERTIFICATES"); ?>"/>
                                </a>
                                
                            <?php
                        }
                        if($course_certificate_term == 3 && isset($result_maxs) && $res >= intval($result_maxs) ){
                            ?>
                            <a id="nextbut3"  href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&task=viewcertificate".$tmpl."&certificate=1&pdf=1&dw=2&ci=".$step->pid."&prev_lesson_id=".$cid."&module_prev_lesson=".$step->prev_module."&catid=".$catid."&course_id=".$step->pid);?>">
                                        <img  style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/certificate.png"; ?>" alt="<?php echo JText::_("GURU_MYCERTIFICATES"); ?>" title="<?php echo JText::_("GURU_MYCERTIFICATES"); ?>"/>
                                </a>
                            <?php
                           
                       
                        }
                        if($course_certificate_term == 4 && $scores_avg_quizzes >= intval($certificates[0]["avg_cert"])){
                            ?>
                            <a id="nextbut4" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&task=viewcertificate".$tmpl."&certificate=1&pdf=1&dw=2&ci=".$step->pid."&prev_lesson_id=".$cid."&module_prev_lesson=".$step->prev_module."&catid=".$catid."&course_id=".$step->pid);?>">
                                        <img  style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/certificate.png"; ?>" alt="<?php echo JText::_("GURU_MYCERTIFICATES"); ?>" title="<?php echo JText::_("GURU_MYCERTIFICATES"); ?>"/>
                                </a>
                            <?php
                       
                        }
                        if($course_certificate_term == 5 && $completed_course==true && isset($result_maxs) && $res >= intval($result_maxs)){
                            ?>
                            <a id="nextbut5" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&task=viewcertificate".$tmpl."&certificate=1&pdf=1&dw=2&ci=".$step->pid."&prev_lesson_id=".$cid."&module_prev_lesson=".$step->prev_module."&catid=".$catid."&course_id=".$step->pid);?>">
                                        <img  style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/certificate.png"; ?>" alt="<?php echo JText::_("GURU_MYCERTIFICATES"); ?>" title="<?php echo JText::_("GURU_MYCERTIFICATES"); ?>"/>
                                </a>
                            <?php
                        }
                        if($course_certificate_term == 6 && $completed_course==true && isset($scores_avg_quizzes) && ($scores_avg_quizzes >= intval($certificates[0]["avg_cert"]))){
                            ?>
                            <a  id="nextbut6" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&task=viewcertificate".$tmpl."&certificate=1&pdf=1&dw=2&ci=".$step->pid."&prev_lesson_id=".$cid."&module_prev_lesson=".$step->prev_module."&catid=".$catid."&course_id=".$step->pid);?>">
                                        <img  style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/certificate.png"; ?>" alt="<?php echo JText::_("GURU_MYCERTIFICATES"); ?>" title="<?php echo JText::_("GURU_MYCERTIFICATES"); ?>"/>
                                </a>
                            <?php
                        }

                        ?>
                        <?php
                      }
                    }
                    ?>
                    	<input type="hidden" name="certificate_link" id="certificate_link" value="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&task=viewcertificate".$tmpl."&certificate=1&pdf=1&dw=2&ci=".$step->pid."&prev_lesson_id=".@$cid."&module_prev_lesson=".$step->prev_module."&catid=".$catid."&course_id=".$step->pid);?>">
				</div>
               </div><!--end of g_row div--> 
            
               <div id="g_progress" class="pull-right">
                <?php
				$action    = JRequest::getVar("action", "");
            	if($action == ""){//we are on a lesson page       
				if(isset($progres_bar) && $progres_bar["0"]["progress_bar"] == "0"){
					$all_steps = $guruModelguruTask->getAllSteps($step->module);
					$total = 0;
					$poz = 1;
					$line_width = 5; //black line separator
					$cid = JRequest::getVar("cid", "0");
					if(isset($all_steps) && count($all_steps) > 0){
						$total = count($all_steps);
						foreach($all_steps as $key=>$value){                       
							if($value["id"] == intval($cid)){
								$poz = $key+1;
								$module_pozition = $poz;
								break;
							}
						}
					}
			   
                    $all_modules = $guruModelguruTask->getAllModules($step->pid);
                    $total_modules = 0;
                    $poz_module = 1;
                    $current_module = JRequest::getVar("module", 0);
                    if(isset($all_modules) && count($all_modules) > 0){
                        $total_module = count($all_modules);
                        foreach($all_modules as $key=>$value){                       
                            if($value["id"] == intval($current_module)){
                                $poz_module = $key+1;
                                $module_pozition = $poz_module;
                                break;
                            }
                        }
                    }
					?>
                    <div>
                        <span style="font-style:italic;">
                        <?php
                            echo JText::_("GURU_PROGRES_MODULE")." ".$poz_module."/".$total_module.", ".JText::_("GURU_PROGRES_LESSON")." ".$poz."/".$total;
                        ?>
                        </span>
                        <br />
                        <div class="danger" id="blank" style="width:<?php echo $progres_bar["0"]["st_width"]; ?>px; height:<?php echo $progres_bar["0"]["st_height"]; ?>px; background-color:<?php echo $progres_bar["0"]["st_notdonecolor"]; ?>; border-radius: 4px;">
                            <div class="success" id="completed" style="float:left; height:<?php echo $progres_bar["0"]["st_height"]; ?>px; width:<?php echo (($progres_bar["0"]["st_width"]*$poz)/$total)-$line_width; ?>px; background-color:<?php echo $progres_bar["0"]["st_donecolor"]; ?>; border-radius:4px 0 0 4px;">
                                &nbsp;
                            </div>
                            <div class="warning" id="separator" style="float:left; width:<?php echo $line_width; ?>px; height:<?php echo $progres_bar["0"]["st_height"]; ?>px; background-color:<?php echo $progres_bar["0"]["st_txtcolor"]; ?>;"></div>
                    </div>
                </div>
                <?php 
					}
				}
				else{// we are one module page
                   
                if(isset($progres_bar) && $progres_bar["0"]["progress_bar"] == "0"){
                    $all_modules = $guruModelguruTask->getAllModules($step->pid);
                    $total = 0;
                    $poz = 1;
                    $line_width = 5; //black line separator
                    $current_module = JRequest::getVar("module", 0);
                    if(isset($all_modules) && count($all_modules) > 0){
                        $total = count($all_modules);
                        foreach($all_modules as $key=>$value){                       
                            if($value["id"] == intval($current_module)){
                                $poz = $key+1;
                                $module_pozition = $poz;
                                break;
                            }
                        }
                    }
        ?>
                    <div>
                        <span style="font-style:italic;"><?php echo JText::_("GURU_PROGRES_MODULE")." ".$poz."/".$total; ?></span>
                        <br />
                         <div class="danger" id="blank" style="width:<?php echo $progres_bar["0"]["st_width"]; ?>px; height:<?php echo $progres_bar["0"]["st_height"]; ?>px; background-color:<?php echo $progres_bar["0"]["st_notdonecolor"]; ?>;  border-radius: 4px;">
                            <div id="completed" class="success" style="float:left; height:<?php echo $progres_bar["0"]["st_height"]; ?>px; width:<?php echo (($progres_bar["0"]["st_width"]*$poz)/$total)-$line_width; ?>px; background-color:<?php echo $progres_bar["0"]["st_donecolor"]; ?>; border-radius:4px 0 0 4px;">
                                &nbsp;
                            </div>
                            <div id="separator" class="warning" style="float:left; width:<?php echo $line_width; ?>px; height:<?php echo $progres_bar["0"]["st_height"]; ?>px; background-color:<?php echo $progres_bar["0"]["st_txtcolor"]; ?>;"></div>
                    </div>
                    </div>
        <?php           
                }   
            }   
        ?>
          </div> 
        </div> 
        
        <div class="g_sect">
        <div class="g_row">
        	<?php if($target == 1){ $class_modal ="g_modal_cell";}else{$class_modal ="";}?>
        	<div class="g_cell span12 <?php echo  $class_modal;?>">
            	<div>
                	<div>
        				<div>
                            <!-- lesson Page title--> 
                            <div class="g_lesson_page page_title">
                                <?php               
                                    if($action == ""){//we are on a lesson page   
                                        echo "<h2>".$step->name."</h2>";       
                                    }
                                ?>

                            </div>
                            <!--end lesson page title -->
                            <?php
                            $all_media = $step->layout_media;
                            $all_text = $step->layout_text;
                            $show_media = true;
                            $stop_search = false;
                           
                            if(isset($all_media) && count($all_media) > 0){
                                foreach($all_media as $key_media=>$value_media){
                                    if(trim($value_media) != ""){
                                        $show_media = true;
                                        $stop_search = true;
                                        break;
                                    }
                                }
                                if(!$stop_search){
                                    $show_media = false;
                                }
                            }
                            else{
                                $show_media = false;
                            }
                           
                            if(!$stop_search){
                                if(isset($all_text) && count($all_text) > 0){
                                    foreach($all_text as $key_text=>$value_text){
                                        if(trim($value_text) != ""){
                                            $show_media = true;
                                            break;
                                        }
                                    }
                                    $show_media = false;
                                }
                                else{
                                    $show_media = false;
                                }
                            }
                            
                            if($action != "" && $show_media === false){
                               
                    ?>
                    				<span class="g_lesson_title">
                                    	<h2>
                                            <span><?php echo JText::_("GURU_PROGRES_MODULE")." ".$module_pozition.":"; ?></span>
                                        </h2>  
                                        <span><?php echo $step->name; ?></span>  
                                    </span>
                    <?php       
                            }
                            elseif($action != "" && $show_media === true){
							?>
                               	<span class="g_lesson_title" id="g_module_name">
                                    <h2>
                                        <span><?php echo JText::_("GURU_PROGRES_MODULE")." ".$module_pozition.":"; ?></span>
                                    </h2>  
                                    <span><?php echo $step->name; ?></span>
                                </span>
                            <?php        
                            }   
                        ?>
                    
                                <?php
									if($layout_style_display["1"] == "style='display:block;'"){
                                ?>
                                <div id="layout1" <?php echo $layout_style_display["1"]; ?>>
                                    <div class="clearfix">
                                    	<div class="g_cell span12">
                                            <div class="g_cell span6" id="media_1">
                                            <?php
                                                echo $step->layout_media["0"];
                                            ?>
                                        	</div> 
                                            <div class="g_cell span6" id="text_1">
                                            	<div>
													<?php $text = $step->layout_text[0];
                                                          $text = JHtml::_('content.prepare', $text);
                                                          echo $text;
                                                    ?>  
                                                </div>     
                                        	</div>
                                        </div>
                                    </div>                                  
                                </div>
                                <?php
                                    }
                                    elseif($layout_style_display["2"] == "style='display:block;'"){       
                                ?>
                                <div id="layout2" <?php echo $layout_style_display["2"]; ?>>
                                    <div class="clearfix">
                                        <div class="g_cell span6">
                                            <div class="span12" id="media_2">
                                                <?php echo $step->layout_media["0"];?>                           
                                            </div>
                                            <div class="span12" id="media_2">
                                                <?php echo $step->layout_media["1"];?>                           
                                            </div>
                                        </div>
                                       
                                        <div class="g_cell span6" id="text_2">
                                        	<div>
                                            <?php $text = $step->layout_text[0];
                                                  $text = JHtml::_('content.prepare', $text);
                                                  echo $text;
                                            ?> 
                                            </div>              
                                        </div>   
                                   </div> 
                                </div>
                                <?php
                                    }
                                    elseif($layout_style_display["3"] == "style='display:block;'"){
                                ?>
                                <div id="layout3" <?php echo $layout_style_display["3"]; ?>>
                                    <div>
                                        <div class="g_cell span12" id="text_3">
                                        	<div>
												<?php
                                                $coursetype_details = guruModelguruProgram::getCourseTypeDetails($step->pid);
                                                if($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] != 0){   
                                                    $lesson_jump_id = intval(JRequest::getVar("cid"));
                                                    $lesson_jump_order = $guruModelguruTask->getLessonJumpOrder($step->pid,$lesson_jump_id);
                                
                                                    $user = JFactory::getUser();
                                                    $user_id = $user->id;
                                                    if($user_id > 0){
                                                        $db = JFactory::getDBO();
                                                        $sql = "select DATE_FORMAT(buy_date,'%Y-%m-%d') from #__guru_buy_courses where course_id=".intval($step->pid)." and userid =".$user_id;
                                                        $db->setQuery($sql);
                                                        $db->query();
                                                        $date_enrolled = $db->loadResult();   
                                                        $date_enrolled = strtotime($date_enrolled);   
                                                    }
                                                   

                                                   
                                                   
                                                   
                                                        if($coursetype_details[0]["lesson_release"] == 1){
                                                            $date_to_display = strtotime ( '+'.($lesson_jump_order-1).' day' , $date_enrolled) ;           
                                                        }                           
                                                        elseif($coursetype_details[0]["lesson_release"] == 2){                               
                                                            $date_to_display = strtotime ( '+'.($lesson_jump_order-1).' week' , $date_enrolled) ;                           
                                                        }                               
                                                        elseif($coursetype_details[0]["lesson_release"] == 3){                               
                                                            $date_to_display = strtotime ( '+'.($lesson_jump_order-1).' month' , $date_enrolled) ;                           
                                                        }                       
                                                           
                                                   
                                                    //$date_to_display =  strtotime ( '+'.($lesson_jump_order-1).' day' , $date_enrolled) ;
                                                   
                                                   
                                                   
                                                    $date_to_display = date("m-d-Y",$date_to_display );   
                                                    if($lesson_jump_order <= $diff_date){
                                                        echo $step->layout_media["0"];
                                                    }
                                                    else{
                                                        $date_final = '<span style="color:#66CC00;">'.$date_to_display.'</span>';
                                                        ?>
                                                        <div style="border: 1px solid #FF0000; background-color:#FDF9E3; padding:10px;"><?php echo  JText::_("GURU_SECV_JUMP_BUT")." ".$date_final." ".JText::_("GURU_SECV_JUMP_BUT_CONT"); ?></div>
                                                       
                                                        <?php
                                                    }
                                                }
                                                else {
                                
                                                    echo $step->layout_media["0"];
                                                }
                                                ?>  
                                             </div>        
                                        </div>   
                                        <div class="g_cell span12" id="media_3">
                                            <?php
                                                  $text = $step->layout_text[0];
                                                  $text = JHtml::_('content.prepare', $text);
                                                  $lesson_jump_id = intval(JRequest::getVar("cid"));
                                                  $lesson_jump_order = $guruModelguruTask->getLessonJumpOrder($step->pid,$lesson_jump_id);
                                                  if($coursetype_details[0]["course_type"] == 1 && $coursetype_details[0]["lesson_release"] != 0){   
                                                      if($lesson_jump_order <= $diff_date){
                                                         echo $text;
                                                      }
                                                  }
                                                  else{
                                                      echo $text;
                                                  }
                                                  ?>                                       
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }
                                    elseif($layout_style_display["4"] == "style='display:block;'"){
                                ?>
                                <div id="layout4" <?php echo $layout_style_display["4"]; ?>>
                                    <div class="clearfix">
                                        <div class="g_cell span12">
                                            <div class="span6" id="media_5">
                                                <?php echo $step->layout_media["0"];?>                           
                                            </div>
                                            <div class="g_cell span6" id="media_6">
                                                <?php echo $step->layout_media["1"];?>                                       
                                            </div>
                                        </div>    
                                        <div class="g_cell span12" id="text_4">
                                        	<div>
												<?php $text = $step->layout_text[0];
                                                      $text = JHtml::_('content.prepare', $text);
                                                      echo $text;
                                                ?>  
                                            </div>     
                                        </div> 
                                   </div>       
                                </div>
                                <?php
                                    }
                                    elseif($layout_style_display["5"] == "style='display:block;'"){
                                ?>
                                <div id="layout5" <?php echo $layout_style_display["5"]; ?>>
                                    <div class="clearfix">
                                        <div class="g_cell span12" id="text_5">
                                        	<div>
                                            <?php $text = $step->layout_text[0];
                                                  $text = JHtml::_('content.prepare', $text);
                                                  echo $text;
                                            ?>  
                                            </div>     
                                        </div>
                                    </div>   
                                </div>
                                <?php
                                    }
                                    elseif($layout_style_display["6"] == "style='display:block;'"){
                                ?>
                                <div id="layout6" <?php echo $layout_style_display["6"];//$layout6_styledisplay; ?>>
                                    <div class="clearfix">
                                        <div class="g_cell span12 pagination-centered" id="media_7">
                                            <?php
												echo $step->layout_media["0"];
											?>
                                         </div>  
                                    </div>                                             
                                </div>
                                <?php
                                    }
                                    elseif($layout_style_display["7"] == "style='display:block;'"){
                                ?>
                                <div id="layout7" <?php echo $layout_style_display["7"];//$layout1_styledisplay; ?>>
                                    <div class="span6" id="text_6">
                                    	<div>
											<?php $text = $step->layout_text[0];
                                                  $text = JHtml::_('content.prepare', $text);
                                                  echo $text;
                                            ?> 
                                         </div>         
                                    </div>                                       
                                    <div class="span6" id="media_8">
                                        <?php echo $step->layout_media["0"];?>   
                                    </div>   
                                    
                                </div>
                                <?php
                                    }
                                    elseif($layout_style_display["8"] == "style='display:block;'"){
                                ?>
                                <div id="layout8" <?php echo $layout_style_display["8"];//$layout2_styledisplay; ?>>
                                    <div class="clearfix">
                                        <div class="g_cell span6" id="text_7">
                                        	<div>
												<?php $text = $step->layout_text[0];
                                                      $text = JHtml::_('content.prepare', $text);
                                                      echo $text;
                                                ?> 
                                             </div>             
                                        </div>                            
                                       <div class="g_cell span6">
                                            <div class="span12" id="media_9">
                                                <?php echo $step->layout_media["0"];?>           
                                            </div>
                                           
                                           
                                            <div class="g_cell span12" id="media_9">
                                                <?php echo $step->layout_media["1"];?>           
                                            </div>
                                       </div>     
                                    </div>                        
                                </div>
                                <?php
                                    }
                                    elseif($layout_style_display["9"] == "style='display:block;'"){
                                ?>       
                                <div id="layout9" <?php echo $layout_style_display["9"]; ?>>
                                    <div class="clearfix">
                                        <div class="g_cell span12" id="text_8">
                                        	<div>
												<?php $text = $step->layout_text[0];
                                                      $text = JHtml::_('content.prepare', $text);
                                                      echo $text;
                                                ?>   
                                            </div>        
                                        </div>   
                                       
                                        <div class="g_cell span12" id="media_11">
                                            <?php echo $step->layout_media["0"];?>                               
                                        </div>
                                     </div>                             
                                </div>       
                                <?php
                                    }
                                    elseif($layout_style_display["10"] == "style='display:block;'"){
                                ?>
                                <div id="layout10" <?php echo $layout_style_display["10"]; ?>>
                                    <div class="clearfix">
                                        <div class="g_cell span12" id="text_9">
                                        	<div>
                                            	<?php echo $step->layout_text["0"];?>   
                                            </div>
                                        </div> 
                                        <div class="clearfix">  
                                            <div class="g_cell span6" id="media_12">
                                                <?php echo $step->layout_media["0"];?>                           
                                            </div>
                                            <div class="g_cell span6" id="media_13">
                                                <?php echo $step->layout_media[1];?>                           
                                            </div>
                                        </div>     
                                    </div>      
                                </div>
                                <?php
                                    }
                                    elseif($layout_style_display["11"] == "style='display:block;'"){
                                ?>
                                  <div id="layout11" <?php echo $layout_style_display["11"];//$layout3_styledisplay; ?>>
                                    <div class="clearfix">
                                        <div class="g_cell span12" id="text_10">
                                        	<div>
												<?php $text = $step->layout_text[0];
                                                      $text = JHtml::_('content.prepare', $text);
                                                      echo $text;
                                                ?> 
                                            </div>              
                                        </div>   
                                        <div class="g_cell span12" id="media_14">
                                            <?php echo $step->layout_media["0"];?>                           
                                        </div>                               
                                        
                                        <div class="g_cell span12" id="text_11">
                                        	<div>
												<?php $text = $step->layout_text[1];
                                                      $text = JHtml::_('content.prepare', $text);
                                                      echo $text;
                                                ?> 
                                            </div>          
                                        </div> 
                                    </div>      
                                </div>       
                                <?php
                    
                                    }
                                    elseif($layout_style_display["12"] == "style='display:block;'"){                   
                                ?>
                                <div id="layout12" <?php if($layout_style_display["12"] != "style='display:block;'"){ echo $layout_style_display["12"];} ?>><!--start quizz/final exam layout -->
                                	<div id="media_15"><!--start quizz div with form -->
                                    	<div class=""><!--start g_row -->
                                        	<div class=""><!--start g_cell-->
                                            	<div id="the_quiz"><!-- start quizz/final exam content -->
                                                	<form method="post" action="" name="quizz_exam" id="quizz_exam">
                                                    	<?php
															$document->addStyleSheet("components/com_guru/css/guru_style.css");
                                                			$document->addStyleSheet("components/com_guru/css/quiz.css");
															$database = JFactory::getDBO();
															if(!isset($id)){
																$id = JRequest::getVar("quize_id");
																$id = intval($id);
															}
															$sql = "SELECT published  FROM #__guru_quiz WHERE id=".$id;
															$database->setQuery($sql);
															$result = $database->loadColumn();
															$result = $result["0"];
															
															if($result == 1){
																$sql = "SELECT show_countdown, max_score FROM #__guru_quiz WHERE id=".$id;
																$database->setQuery($sql);
																$result = $database->loadObject();
														   
																$user = JFactory::getUser();
																$user_id = $user->id;
															
																$sql = "select `time_quiz_taken` from #__guru_quiz where `id`=".intval($id);
																$database->setQuery($sql);
																$database->query();
																$time_quiz_taken = $database->loadColumn();
																$time_quiz_taken = @$time_quiz_taken["0"];
															
															
																$sql = "SELECT id, score_quiz, time_quiz_taken_per_user  FROM #__guru_quiz_taken WHERE user_id=".intval($user_id)." and quiz_id=".intval($id)." and pid=".intval($step->pid)." ORDER BY id DESC LIMIT 0,1";                                               
																$database->setQuery($sql);
																$result_q = $database->loadObject();
																if($time_quiz_taken < 11){
																	$time_user = $time_quiz_taken - $result_q->time_quiz_taken_per_user;
																}
																$first= explode("|", @$result_q->score_quiz);
																@$res = intval(($first[0]/$first[1])*100);
															
																
																if($res >= intval($result->max_score)){
																	$pass = 1;
																	$nb_of_questions = JRequest::getVar("question_number");
																	$quizz_fe_content_failed = $this->generatePassed_Failed_quizzes($id,$step->pid,$nb_of_questions, $pass);
																	echo $quizz_fe_content_failed;
																}
																elseif(isset($result_q->time_quiz_taken_per_user) && intval($result_q->time_quiz_taken_per_user) == $time_quiz_taken && $result_q->time_quiz_taken_per_user < 11 ){
																	$pass = 0;
																	$quizz_fe_content_pass = $this->generatePassed_Failed_quizzes($id,$step->pid,$nb_of_questions, $pass);
																	echo $quizz_fe_content_pass;
																}
																else{
																	if($callback == 0){
																		if($result->show_countdown == 0){
																			if(trim($step->layout_media[0]) != ""){
																				$timer = $guruModelguruTask->createTimer($step->id);
																				echo "<br/>".$timer."<br/>";
																				
																				$id = @$_SESSION["quiz_id"];
																				$database = JFactory::getDBO();
																			
																				if(isset($id)){
																					$sql = "SELECT limit_time, limit_time_f, show_finish_alert  from #__guru_quiz WHERE id=".$id;
																					$database->setQuery($sql);
																					$result = $database->loadObject();
																				}
																				
																				$minutes = 0;
																				$seconds = 0;
																				if(isset($_COOKIE["m1"])){
																					$minutes = $_COOKIE["m1"];
																				}
																				else{
																					$minutes = intval($result->limit_time);
																				}
																				
																				if(isset($_COOKIE["m2"])){
																					$seconds = $_COOKIE["m2"];
																				}
																				else{
																					$seconds = 0;
																				}
																				unset($_SESSION["quiz_id"]);
					
																				echo '<script language="javascript" type="text/javascript">
																						window.onload = iJoomlaTimer('.$minutes.', '.$seconds.', '.intval($id).', '.																	intval($result->limit_time_f).', '.intval($result->show_finish_alert).');
																					  </script>';
																			}
																		}
																		if((trim($step->layout_media["0"]) != "") && @$result_q->time_quiz_taken_per_user >1 && @$result_q->time_quiz_taken_per_user<11){
																			if($result_q->time_quiz_taken_per_user ==1){
																				echo'<span style="font-size:26px;" >';
																					echo JText::_("GURU_TIMES_REMAIN_TAKE_QUIZ")." <span style='color:red'>".$time_user."</span>"." ".JText::_("GURU_ATTEMPT_LEFT") ;
																				echo'</span>';   
																			}
																			else
																			{
																				echo'<span style="font-size:26px;" >';
																					echo JText::_("GURU_TIMES_REMAIN_TAKE_QUIZ")." <span style='color:red'>".$time_user."</span>"." " .JText::_("GURU_ATTEMPTS_LEFT");
																				echo'</span>';   
																			}
																		   
																		}
																		echo $step->layout_media[0];/* quiz***final exam content */
																	}
																	else{
																		$quiz_id = JRequest::getVar("quize_id");
																		$nb_of_questions = JRequest::getVar("question_number");
																		echo $quizz_fe_content;
																	}
																}
															}//end if($result == 1)
															else{
																echo '<span class="guru_quiz_title"> '.JText::_("GURU_UNPL_QUIZ").'</span>';
															}
														?>
                                                        <input type="hidden" name="controller" value="guruTasks" />
                                                    	<input type="hidden" name="option" value="com_guru" />
                                                        <input type="hidden" name="task" value="quizz_fe_submit" />
                                                    </form>                                              
                                            	</div><!-- end quizz/final exam content -->                                      
                                       		</div><!--end g_cell-->
                                        </div><!--end g_row-->
                                   </div><!-- end  quizz div with form  -->
                                </div><!-- end quizz/final exam layout -->
                                <?php
                                    }
                                    $coursetype_details = guruModelguruProgram::getCourseTypeDetails($step->pid);
									
	
									$sql = "SELECT step_access_courses  FROM `#__guru_program` where id = ".intval($step->pid);
									$db->setQuery($sql);
									$db->query();
									$step_access_coursesjump = $db->loadResult();
									
									$sql = "SELECT chb_free_courses  FROM `#__guru_program` where id = ".intval($step->pid);
									$db->setQuery($sql);
									$db->query();
									$chb_free_coursesjump = $db->loadResult();
									
									
									if($chb_free_coursesjump == 1 && $step_access_coursesjump == 2){
										$accessJumpB = 1;
									}
                    
                                ?>   
                                <?php if(count($step->layout_jump)>0){ ?>
                                            
                                                 <div id="g_jump_button_ref">
                                                   
                                                                <?php
                                                                    if (isset($step->layout_jump["0"])){
																	?>
                                                                     <?php
                                                                        $tmpl = "";
                                                                        if($target == 1){
                                                                            $tmpl = "&tmpl=component";
                                                                        }
                                                                       
                                                                        $jump_details = $guruModelguruTask->getJumpStep($step->layout_jump[0]->jump);                                                                               
                                                                        $module_link = @$jump_details["0"]["module_id1"];
                                                                        $jump_cid = @$jump_details["0"]["jump_step"];
                                                                        $jump_link = JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&module=".intval($module_link)."&task=view&cid=".$jump_cid.$tmpl);
                                                                        if($jump_details["0"]["type_selected"] == "module"){
                                                                            $jump_link = JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".$jump_cid."&action=viewmodule".$tmpl);
                                                                        }
                                                                       
                                                                        $db = JFactory::getDBO();
                                                                        $sql = "select step_access from #__guru_task where id=".intval($jump_cid);                                                   
                    
                                                                        $db->setQuery($sql);
                                                                        $db->query();
                                                                        $lesson_acces = intval($db->loadResult());
																		
                                                                     
                                                                        if(!accessToLesson($jump_cid) && $accessJumpB !=1){
                    
                                                                ?>
                                                                            <?php
                                                                                $style_jump = "";
                                                                                if($target == 0){
                                                                                }
                                                                            ?>
                                                                            <a rel="{handler: 'iframe', size: {x: 400, y: 400}, iframeOptions: {id: 'lesson_editplans'}}" <?php echo $style_jump; ?> class="btn btn-danger modal" id="amodal"  href="<?php echo JRoute::_("index.php?option=com_guru&view=guruEditplans&course_id=".intval($catid).'&tmpl=component'); ?>"><?php echo $step->layout_jump[0]->text; ?></a>
                                                                <?php
                                                                        }
																		elseif(!accessToLesson($jump_cid) && $accessJumpB ==1){
																			?>
                                                                            <input class="btn btn-danger" type="button" name="JumpButton" value="<?php echo $step->layout_jump[0]->text; ?>" onclick="document.location.href='<?php echo $jump_link; ?>'" />
                                                                <?php
																		}
                                                                        elseif(accessToLesson($jump_cid) && $coursetype_details[0]["course_type"] ==0){
                                                                        ?>
                                                                            <input class="btn btn-danger" type="button" name="JumpButton" value="<?php echo $step->layout_jump[0]->text; ?>" onclick="document.location.href='<?php echo $jump_link; ?>'" />
                                                                <?php
                                                                        }
                                                                        else{
                                                                        $lesson_jump_id = intval(JRequest::getVar("cid"));
                                                                        $lesson_jump_order = $guruModelguruTask->getLessonJumpOrder($step->pid,$lesson_jump_id);
                                                                        if($lesson_jump_order <= $diff_date){
                                                                           
                                                                ?>
                                                                            <input class="btn btn-danger" type="button" name="JumpButton" value="<?php echo $step->layout_jump[0]->text; ?>" onclick="document.location.href='<?php echo $jump_link; ?>'" />
                                                                <?php
                                                                        }
                                                                       
                                                                        }
																		?>
                                                                        <?php
                                                                    }
                                                                ?>
                                                           
                                                           
                                                                <?php
                                                                    if (isset($step->layout_jump["1"])){
																	?>
                                                                    <?php
                                                                        $jump_details = $guruModelguruTask->getJumpStep($step->layout_jump[1]->jump);                                                                               
                                                                        $module_link = @$jump_details["0"]["module_id1"];
                                                                        $jump_cid = @$jump_details["0"]["jump_step"];
                                                                        $jump_link = JRoute::_("index.php?option=com_guru&view=guruTasks&module=".intval($module_link)."&task=view&cid=".$jump_cid.$tmpl);                                           
                                                                        if($jump_details["0"]["type_selected"] == "module"){
                                                                            $jump_link = JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".$jump_cid."&action=viewmodule".$tmpl);
                                                                        }
                                                                       
                                                                        $db = JFactory::getDBO();
                                                                        $sql = "select step_access from #__guru_task where id=".intval($jump_cid);                                                   
                                                                        $db->setQuery($sql);
                                                                        $db->query();
                                                                        $lesson_acces = intval($db->loadResult());
                                                                       
                                                                        if(!accessToLesson($jump_cid)&& $accessJumpB !=1){
                                                                ?>
                                                                            <?php
                                                                                $style_jump = "";
                                                                                if($target == 0){
                                                                                   
                                                                                }
                                                                            ?>
                                                                            <a rel="{handler: 'iframe', size: {x: 400, y: 400},iframeOptions: {id: 'lesson_editplans'}}" <?php echo $style_jump; ?> class="btn btn-danger modal" id="amodal" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruEditplans&course_id=".intval($catid).'&tmpl=component'); ?>"><?php echo $step->layout_jump[1]->text; ?></a>
                                                                <?php
                                                                        }
                                                                        elseif(accessToLesson($jump_cid) && $coursetype_details[0]["course_type"] ==0){
                                                                        ?>
                                                                            <input class="btn btn-danger" type="button" name="JumpButton" value="<?php echo $step->layout_jump[1]->text; ?>" onclick="document.location.href='<?php echo $jump_link; ?>'" />
                                                                <?php
                                                                        }
                                                                        else{
                                                                            $lesson_jump_id = intval(JRequest::getVar("cid"));
                                                                            $lesson_jump_order = $guruModelguruTask->getLessonJumpOrder($step->pid,$lesson_jump_id);
                                                                            if($lesson_jump_order <= $diff_date){
                                                                ?>
                                                                                <input class="btn btn-danger" type="button" name="JumpButton" value="<?php echo $step->layout_jump[1]->text; ?>" onclick="document.location.href='<?php echo $jump_link; ?>'" />
                                                                <?php
                                                                            }
                                                                       
                                                                        }
																		?>
                                                                       <?php 
                                                                    }
                                                                ?>
                                                           </div>
                                                           <div id="g_jump_button_ref">
                                                           
                                                                <?php
                                                                    if (isset($step->layout_jump["2"])){
																	?>
                                                                    <?php
                                                                        $jump_details = $guruModelguruTask->getJumpStep($step->layout_jump[2]->jump);
                                                                        $module_link = @$jump_details["0"]["module_id1"];
                                                                        $jump_cid = @$jump_details["0"]["jump_step"];
                                                                        $jump_link = JRoute::_("index.php?option=com_guru&view=guruTasks&module=".intval($module_link)."&task=view&cid=".$jump_cid.$tmpl);
                                                                        if($jump_details["0"]["type_selected"] == "module"){
                                                                            $jump_link = JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".$jump_cid."&action=viewmodule".$tmpl);
                                                                        }
                                                                       
                                                                        $db = JFactory::getDBO();
                                                                        $sql = "select step_access from #__guru_task where id=".intval($jump_cid);                                                   
                                                                        $db->setQuery($sql);
                                                                        $db->query();
                                                                        $lesson_acces = intval($db->loadResult());
                                                                       
                                                                        if(!accessToLesson($jump_cid) && $accessJumpB !=1){
                                                                ?>
                                                                            <?php
                                                                                $style_jump = "";
                                                                                if($target == 0){
                                                                                   
                                                                                }
                                                                            ?>
                                                                            <a rel="{handler: 'iframe', size: {x: 400, y: 400},iframeOptions: {id: 'lesson_editplans'}}" <?php echo $style_jump; ?> class="btn btn-danger modal" id="amodal" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruEditplans&course_id=".intval($catid).'&tmpl=component'); ?>"><?php echo $step->layout_jump[2]->text; ?></a>
                                                                <?php
                                                                        }
                                                                        elseif(accessToLesson($jump_cid) && $coursetype_details[0]["course_type"] ==0){
                                                                        ?>
                                                                            <input class="btn btn-danger" type="button" name="JumpButton" value="<?php echo $step->layout_jump[2]->text; ?>" onclick="document.location.href='<?php echo $jump_link; ?>'" />
                                                                <?php
                                                                        }
                                                                        else{
                                                                            $lesson_jump_id = intval(JRequest::getVar("cid"));
                                                                            $lesson_jump_order = $guruModelguruTask->getLessonJumpOrder($step->pid,$lesson_jump_id);
                                                                            if($lesson_jump_order <= $diff_date){
                                                                ?>
                                                                                <input class="btn btn-danger" type="button" name="JumpButton" value="<?php echo $step->layout_jump[2]->text; ?>" onclick="document.location.href='<?php echo $jump_link; ?>'" />
                                                                <?php
                                                                            }
                                                                       
                                                                        }
																		?>
                                                                        <?php
                                                                    }
                                                                ?>
                                                           
                                                          
                                                                <?php
																
                                                                    if (isset($step->layout_jump["3"])){
																	?>
                                                                    <?php
                                                                        $jump_details = $guruModelguruTask->getJumpStep($step->layout_jump[3]->jump);
                                                                        $module_link = @$jump_details["0"]["module_id1"];
                                                                        $jump_cid = @$jump_details["0"]["jump_step"];
                                                                        $jump_link = JRoute::_("index.php?option=com_guru&view=guruTasks&module=".intval($module_link)."&task=view&cid=".$jump_cid.$tmpl);
                                                                        if($jump_details["0"]["type_selected"] == "module"){
                                                                            $jump_link = JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".$jump_cid."&action=viewmodule".$tmpl);
                                                                        }
                                                                       
                                                                        $db = JFactory::getDBO();
                                                                        $sql = "select step_access from #__guru_task where id=".intval($jump_cid);                                                   
                                                                        $db->setQuery($sql);
                                                                        $db->query();
                                                                        $lesson_acces = intval($db->loadResult());
                                                                       
                                                                        if(!accessToLesson($jump_cid)&& $accessJumpB !=1){
                                                                ?>
                                                                            <?php
                                                                                $style_jump = "";
                                                                                if($target == 0){
                                                                                   
                                                                                }
                                                                            ?>
                                                                            <a rel="{handler: 'iframe', size: {x: 400, y: 400},iframeOptions: {id: 'lesson_editplans'}}" <?php echo $style_jump; ?> class="btn btn-danger modal" id="amodal" href="<?php echo JRoute::_("index.php?option=com_guru&view=guruEditplans&course_id=".intval($catid).'&tmpl=component'); ?>"><?php echo $step->layout_jump[3]->text; ?></a>
                                                                <?php
                                                                        }
                                                                        elseif(accessToLesson($jump_cid) && $coursetype_details[0]["course_type"] ==0){
                                                                        ?>
                                                                            <input class="btn btn-danger" type="button" name="JumpButton" value="<?php echo $step->layout_jump[3]->text; ?>" onclick="document.location.href='<?php echo $jump_link; ?>'" />
                                                                <?php
                                                                        }
                                                                        else{
                                                                            $lesson_jump_id = intval(JRequest::getVar("cid"));
                                                                            $lesson_jump_order = $guruModelguruTask->getLessonJumpOrder($step->pid,$lesson_jump_id);
                                                                            if($lesson_jump_order <= $diff_date){
                                                                ?>
                                                                                <input class="btn btn-danger" type="button" name="JumpButton" value="<?php echo $step->layout_jump[3]->text; ?>" onclick="document.location.href='<?php echo $jump_link; ?>'" />
                                                                <?php
                                                                            }
                                                                       
                                                                        }
																		?>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            
                                                    </div>
                                            <?php } ?>
                                            </div>
                                           </div>
                                          </div>
                                          </div> 
                                          </div>
                                          </div> 

<?php 
 if(isset($step->audio)){
?>
    <div id="div_audio">
        <?php
            $step->audio = str_replace('style="', 'style="position:absolute; top:-100px;', $step->audio);
            echo $step->audio;
        ?>
    </div>
<?php       
}
?>
                                    
<?php
$verifie = JRequest::getVar("action", "");
if($verifie !="viewmodule"){//if you come from module page
	$sql = "select count(*) from #__extensions where element='com_kunena' and name='com_kunena'";
	$db->setQuery($sql);
	$db->query();
	$count = $db->loadResult();
	if($count >0){
	
		$user = JFactory::getUser();
		$user_id = $user->id;
	   
		$sql ="select enabled from #__extensions WHERE element='ijoomlagurudiscussbox'";
		$db->setQuery($sql);
		$db->query();
		$enabled = $db->loadResult();
		
		$sql ="select count(id) from #__kunena_categories WHERE alias='".$step->alias."'";
		$db->setQuery($sql);
		$db->query();
		$board_less= $db->loadResult();	
		
		if($enabled == 1){//if the plugin  is enabled
			if($board_less != 0){//if we have category created for the lesson
				$sql ="select numPosts from #__kunena_categories WHERE name='".$step->name."' order by id desc limit 0,1";
				$db->setQuery($sql);
				$db->query();
				$numposts = $db->loadResult();
				
				 if($user_id != 0 ){//if you are login
					if($allow_stud == 0){
						echo $gurucommentbox;
					}
					if($allow_stud == 0){
						if($numposts  !=0){
						?>
						<div class="gurucommentform-title"><?php echo JText::_ ( 'GURU_POST_IN_DISCUSSION' );?></div>
					  <?php
					   }?>
						<div id="gurucommentbox">
							<?php
							$sql ="select id, name, userid from #__kunena_messages WHERE subject='".$step->name."' order by id desc";
							$db->setQuery($sql);
							$db->query();
							$resultid = $db->loadAssocList();
						   
							$jnow = JFactory::getDate();
							$date_currentk = $jnow->toSQL();                                   
							$int_current_datek = strtotime($date_currentk);
						   
							$sql ="select id from #__kunena_categories WHERE name='".$step->name."' order by id desc limit 0,1";
							$db->setQuery($sql);
							$db->query();
							$catkunena = $db->loadResult();
						   
							$sql ="select id from #__kunena_topics WHERE subject='".$step->name."' order by id asc limit 0,1";
							$db->setQuery($sql);
							$db->query();
							$idmess = $db->loadResult();
	
								   
							for($i=0; $i < count($resultid); $i++){   
								$sql = "select message from #__kunena_messages_text WHERE mesid=".$resultid[$i]["id"];
								$db->setQuery($sql);
								$db->query();
								$result = $db->loadResult();
							   
								$sql = "select time from #__kunena_messages WHERE id=".$resultid[$i]["id"];
								$db->setQuery($sql);
								$db->query();
								$datestart = $db->loadResult();
							   
								$timepast = $guruModelguruTask->get_time_difference($datestart,$int_current_datek);
							   
								if($timepast["days"] == 0){
									if($timepast["hours"] == 0){
										if($timepast["minutes"] == 0){
											$difference = "a few seconds ago";
										}
										else{
											$difference = $timepast["minutes"]." ".JText::_("GURU_REAL_MINUTES")." ".JText::_("GURU_AGO");
										}
									}
									else{
										$difference = $timepast["hours"]." ".JText::_("GURU_REAL_HOURS").", ".
													  $timepast["minutes"]." ".JText::_("GURU_REAL_MINUTES")." ".JText::_("GURU_AGO");
									}
								}
								else{
									$difference = $timepast["days"]." ".JText::_("GURU_REAL_DAYS").", ".
												  $difference = $timepast["hours"]." ".JText::_("GURU_REAL_HOURS").", ".
												  $timepast["minutes"]." ".JText::_("GURU_REAL_MINUTES")." ".JText::_("GURU_AGO");
								}
									   
					
											
								if($deviceType !='phone'){
									$rows_cols = ' rows="2" cols="90"';
									$style = 'style="width:100%"';
								}
								else{
									$rows_cols = 'rows="3"';
									$style = 'style="width:50%"';
								}
								?>
								<div class="guru row-fluid guru-header">
									<span><img  src="<?php echo JUri::base()."components/com_guru/images/guru_comment.gif"; ?>"/></span>
									<span><?php echo JText::_ ( 'GURU_POSTED' );?>:<?php echo $difference; ?></span>
									<span style="float:right;"><a href=<?php echo JUri::base().'index.php?option=com_kunena&view=topic&catid='.$catkunena.'&id='.$idmess.'&Itemid=0#'.$resultid[$i]["id"];?>>#<?php echo $resultid[$i]["id"];?></a></span>
									<span><?php echo JText::_ ( 'GURU_COMMENTED_BY' ) . ' ' . $resultid[$i]["name"] ; ?>
									</span>
								</div>
								<div class="guru-reply-body clearfix">
									<div style="display:block;" id="gurupostcomment<?php echo $resultid[$i]["id"];?>" class="guru-text"><?php echo $result; ?></div>
									<textarea style="display:none;" <?php echo $style; ?> name="message1<?php echo $resultid[$i]["id"];?>" id="message1<?php echo $resultid[$i]["id"];?>" <?php echo $rows_cols; ?>></textarea>
									<input style="display:none;" id="save<?php echo $resultid[$i]["id"];?>" name="save" class="btn btn-success" type="button" onclick="javascript:savegurucomment('<?php echo $step->id;?>','<?php echo $resultid[$i]["id"];?>');" value="<?php echo JText::_('GURU_SAVE'); ?>" />
									<div>
									<?php if($user_id == $resultid[$i]["userid"]){
											if($allow_delete == 0){
												 echo '<span style="display:block; float:left;"><a id="delete'.$resultid[$i]["id"].'" href="#" onclick="javascript:deletegurucomment('.$step->id.', '.$resultid[$i]["userid"].', '.$resultid[$i]["id"].'); return false;">'.JText::_("GURU_DELETE").'</a></span>';
											 }
											if($allow_edit == 0){
												 echo '<span style="float:right;display:block "><a id="edit'.$resultid[$i]["id"].'" href="#" onclick="javascript:editgurucomment('.$resultid[$i]["id"].'); return false;">'.JText::_("GURU_EDIT").'</a></span>';
											}
									 
									 } else {echo "";}?>
									</div>   
								 </div>
					  <?php }//end for
					  ?>
					  </div><!--end div id gurucommentbox -->
					  <?php
					}//end allow_stud
					
				 }//end if you are login
				
				
			}//end if we have category created for the lesson
			
		}// end if the plugin  is enabled
		
	
	}// end if you come from module page 
}//end if count

?>

    </div>
    
<?php

if($target == "1"){
    if(isset($step) && isset($step->id)&& isset($id)){
        $script = 'parent.document.getElementById("viewed-'.$step->id.'").style.display = "block"';
        $document->addScriptDeclaration($script);
    }
}
?>