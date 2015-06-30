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

$document = JFactory::getDocument();
$document->setTitle( JText::_("GURU_MYCERTIFICATES"));

$db = JFactory::getDBO();
$config = JFactory::getConfig();
$course_id = JRequest::getVar("ci");
$user = JFactory::getUser();
$user_id = $user->id;
$guruModelguruTask = new guruModelguruTask();
$sql = "SELECT `name` from #__guru_program WHERE `id` =".intval($course_id);
$db->setQuery($sql);
$db->query();
$result = $db->loadResult();



$imagename = "SELECT * FROM #__guru_certificates WHERE id=1";
$db->setQuery($imagename);
$db->query();
$imagename = $db->loadAssocList();

$authorname = "SELECT name from #__users where id IN (SELECT author_id FROM #__guru_mycertificates WHERE user_id = ".intval($user_id)." AND course_id =".intval($course_id)." )";
$db->setQuery($authorname);
$db->query();
$authorname = $db->loadResult();
$background_color = "";

if($imagename[0]["design_background"] !=""){
	$image_theme = explode("/", $imagename[0]["design_background"]);
	if(trim($image_theme[4]) =='thumbs'){
		$image_theme = $image_theme[5];
	}
	else{
		$image_theme = $image_theme[4];
	}
	}	
else{
	$background_color= "background-color:"."#".$imagename[0]["design_background_color"];
}	

$site_url = JURI::root();
$coursename = $result;
$authorname = $authorname;

$date_completed = "SELECT datecertificate FROM #__guru_mycertificates WHERE user_id=".intval($user_id)." AND course_id=".intval($course_id);
$db->setQuery($date_completed);
$db->query();
$date_completed = $db->loadResult();

$format = "SELECT datetype FROM #__guru_config WHERE id=1";
$db->setQuery($format);
$db->query();
$format = $db->loadResult();

if(isset($date_completed) && $date_completed !='0000-00-00' ){
	$date_completed = date($format, strtotime($date_completed));
	$completiondate = $date_completed;

}
else{
	$completiondate = "";
}
$certificateid = "SELECT id FROM #__guru_mycertificates WHERE user_id = ".intval($user_id)." AND course_id=".intval($course_id);
$db->setQuery($certificateid);
$db->query();
$certificateid = $db->loadResult();

$sitename = $config->get( 'sitename');


$coursemsg = "SELECT certificate_course_msg FROM #__guru_program WHERE id=".intval($course_id);
$db->setQuery($coursemsg);
$db->query();
$coursemsg = $db->loadResult();

$firstnamelastname = "SELECT firstname, lastname FROM #__guru_customer WHERE id=".intval($user_id);
$db->setQuery($firstnamelastname);
$db->query();
$firstnamelastname = $db->loadAssocList();

$scores_avg_quizzes =  $guruModelguruTask->getAvgScoresQ($user_id,$course_id);

$avg_quizzes_cert = "SELECT avg_certc FROM #__guru_program WHERE id=".intval($course_id);
$db->setQuery($avg_quizzes_cert);
$db->query();
$avg_quizzes_cert = $db->loadResult();


$sql = "SELECT id_final_exam FROM #__guru_program WHERE id=".intval($course_id);
$db->setQuery($sql);
$result = $db->loadResult();

$sql = "SELECT hasquiz from #__guru_program WHERE id=".intval($course_id);
$db->setQuery($sql);
$resulthasq = $db->loadResult();

$sql = "SELECT max_score FROM #__guru_quiz WHERE id=".intval($result);
$db->setQuery($sql);
$result_maxs = $db->loadResult();

$sql = "SELECT id, score_quiz, time_quiz_taken_per_user  FROM #__guru_quiz_taken WHERE user_id=".intval($user_id)." and quiz_id=".intval($result)." and pid=".intval($course_id)." ORDER BY id DESC LIMIT 0,1";
$db->setQuery($sql);
$result_q = $db->loadObject();

$first= explode("|", @$result_q->score_quiz);

@$res = intval(($first[0]/$first[1])*100);

if($resulthasq == 0 && $scores_avg_quizzes == ""){
	$avg_certc1 = "N/A";
}
elseif($resulthasq != 0 && $scores_avg_quizzes == ""){
	$avg_certc1 = "N/A";
}
elseif($resulthasq != 0 && isset($scores_avg_quizzes)){
	if($scores_avg_quizzes >= intval($avg_quizzes_cert)){
		$avg_certc1 =  $scores_avg_quizzes.'%'; 
	}
	else{
		$avg_certc1 = $scores_avg_quizzes.'%';
	}
}

if($result !=0 && $res !="" ){
	if( $res >= $result_maxs){
		$avg_certc = $res.'%';
	}
	elseif($res < $result_maxs){
		$avg_certc = $res.'%';
	}
}
elseif(($result !=0 && $result !="")){
	$avg_certc = "N/A";
}
elseif($result ==0 || $result ==""){
	$avg_certc = "N/A";
}

$imagename[0]["templates1"]  = str_replace("[SITENAME]", $sitename, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[STUDENT_FIRST_NAME]", @$firstnamelastname[0]["firstname"], $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[STUDENT_LAST_NAME]", @$firstnamelastname[0]["lastname"], $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[SITEURL]", $site_url, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[CERTIFICATE_ID]", $certificateid, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[COMPLETION_DATE]", $completiondate, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[COURSE_NAME]", $coursename, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[AUTHOR_NAME]", $authorname, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[COURSE_MSG]", $coursemsg, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[COURSE_AVG_SCORE]", $avg_certc, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[COURSE_FINAL_SCORE]", $avg_certc, $imagename[0]["templates1"]);

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_guru/css/trainer_style.css");
?>
<script>
function openWinCertificate2(t1,t2,t3,t4,t5)
{
	myWindow=window.open('<?php echo JURI::root();?>index.php?option=com_guru&view=guruOrders&task=printcertificate&op=2&cn='+t1+'&an='+t2+'&id='+t3+'&cd='+t4+'&ci='+t5+'&tmpl=component','','width=850,height=600, resizable = 0');
	myWindow.focus();
}
function openWinCertificate3(t1,t2,t3,t4,t5)
{
	myWindow=window.open('<?php echo JURI::root();?>index.php?option=com_guru&view=guruOrders&task=printcertificate&op=3&cn='+t1+'&an='+t2+'&id='+t3+'&cd='+t4+'&ci='+t5+'&tmpl=component','','width=800,height=250, resizable = 0');
	myWindow.focus();
}
function openWinCertificate4(t1,t2,t3,t4,t5)
{
	myWindow=window.open('<?php echo JURI::root();?>index.php?option=com_guru&view=guruOrders&task=savepdfcertificate&op=9&cn='+t1+'&an='+t2+'&id='+t3+'&cd='+t4+'&ci='+t5+'&tmpl=component','','width=800,height=600, resizable = 0');
	myWindow.focus();
}
</script>


<?php

$cid = JRequest::getVar("prev_lesson_id");
$module_prev_lesson = JRequest::getVar("module_prev_lesson");
$catid = JRequest::getVar("catid");
$tmpl = JRequest::getVar("tmpl");
$pid = JRequest::getVar("course_id");

if($tmpl == ""){
	$tmpl = "";
	$position="position:relative;";
}
else{
	$tmpl = "&tmpl=component";
	$position="position:absolute;";
}

?>

<div id="g_lesson_navs" class="clearfix">
    <div class="g_lesson_nav_bar clearfix">
        <div class="pull-left">
            <a  onclick="javascript:closeBox();" href="<?php echo JRoute::_('index.php?option=com_guru&view=guruPrograms&task=view&cid='.$pid.'&Itemid='.@$Itemid); ?>">
            <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/home.png"; ?>" alt="<?php echo JText::_("GURU_COURSE_HOME_PAGE"); ?>" title="<?php echo JText::_("GURU_COURSE_HOME_PAGE"); ?>"/>
        </a>
        </div> 
        <div class="pull-right">  
            <a href="<?php echo JRoute::_("index.php?option=com_guru&view=guruTasks&catid=".intval($catid)."&task=view&module=".$module_prev_lesson."&cid=".$cid.$tmpl); ?>">
                            <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/back.png"; ?>" alt="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>" title="<?php echo JText::_("GURU_PREVIOUS_LESSON"); ?>"/>
             </a>
             <a onClick="window.location.reload();">
                    <img style="border:none;" src="<?php echo JUri::base()."components/com_guru/images/repeat.png"; ?>" alt="<?php echo JText::_("GURU_REFRESH_PAGE"); ?>" title="<?php echo JText::_("GURU_REFRESH_PAGE"); ?>"/></a>
        </div>
    </div>  
  </div>  
<div class="g_sect">
	<div class="g_row"> 
		<div class="g_cell span12">
        	<div>
                <div style="<?php echo $position;?>">
                	<div style="text-align:center">
                        <p style="font-size:36px;"><?php echo JText::_("GURU_CONGRAT"); ?></p>
                        <p style="font-size:26px;"><?php echo JText::_("GURU_EFFORTS_AWERDED"); ?></p>
                        <button type="submit" class="btn btn-primary" name="download_cert"  onclick="openWinCertificate4('<?php echo str_replace("'","&acute;",$coursename)?>','<?php echo $authorname ?>','<?php echo $certificateid ?>', '<?php echo $date_completed ?>', '<?php echo $course_id; ?>')" ><i class="icon-download"></i> <?php echo JText::_("GURU_DOWNLOAD_CERT"); ?></button>
                        <button type="submit" class="btn btn-primary" name="share_cert"  onclick="openWinCertificate3('<?php echo str_replace("'","&acute;",$coursename)?>','<?php echo $authorname ?>','<?php echo $certificateid ?>', '<?php echo $date_completed ?>', '<?php echo $course_id; ?>')" ><i class="icon-share"></i> <?php echo JText::_("GURU_SHARE_CERTIFICATE"); ?></button>
                        <button type="submit" class="btn btn-primary"  name="email_cert"  onclick="openWinCertificate2('<?php echo str_replace("'","&acute;",$coursename)?>','<?php echo $authorname ?>','<?php echo $certificateid ?>', '<?php echo $date_completed ?>', '<?php echo $course_id; ?>')" ><i class="icon-envelope"></i> <?php echo JText::_("GURU_EMAIL_CERTIFICATE"); ?></button>
                    </div>    
                    <div style="font-family:<?php echo $imagename[0]["font_certificate"]; ?>; margin-top:15px; min-height:600px; <?php echo $background_color;?>; background-size:100% 100%; background-repeat:no-repeat; background-image:url(<?php echo JUri::base()."images/stories/guru/certificates/".$image_theme; ?>); position:relative;">
                        <div class="g_certificate_view" style="color:<?php echo "#".$imagename[0]["design_text_color"]; ?>">
                        	<div>
                            	<?php echo $imagename[0]["templates1"]; ?>
                            </div>    
                        </div>
                   </div> 
       			</div> 
       		</div>  
		</div>
	</div>
</div>