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

$config = JFactory::getConfig();
include(JPATH_SITE.DS.'components'.DS.'com_guru'.DS.'models'.DS.'gurutask.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_guru'.DS.'helpers'.DS.'Mobile_Detect.php');

$scores_avg_quizzes ="";
$background_color = "";
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');


$db = JFactory::getDBO();
$user = JFactory::getUser();

$document = JFactory::getDocument();

$imagename = "SELECT * FROM #__guru_certificates WHERE id=1";
$db->setQuery($imagename);
$db->query();
$imagename = $db->loadAssocList();

?>
<script language="javascript">
	function SelectAll(id)
	{
		document.getElementById(id).focus();
		document.getElementById(id).select();
	}
</script>
<?php

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
$coursename = JRequest::getVar('cn', '', 'get');
$authorname = JRequest::getVar('an', '', 'get');
$certificateid = JRequest::getVar('id', '', 'get');
$completiondate = JRequest::getVar('cd', '', 'get');
$option_selected = JRequest::getVar('op', '', 'get');
$course_id = JRequest::getVar('ci', '', 'get');
$url_copy_id = JRequest::getVar('opt', '', 'get');
$backend = JRequest::getVar('back', "0");
$sitename = $config->get( 'sitename');

///----------Preview button from backend------------////
if(isset($backend) && $backend ==1){
?>
<div style=" min-height:800px; font-family:<?php echo $imagename[0]["font_certificate"]; ?>; background-size:100%; <?php echo $background_color;?>; background-repeat:no-repeat; background-image:url(<?php echo JUri::root()."images/stories/guru/certificates/".$image_theme; ?>);">
		<div style="padding-top:150px; color:<?php echo "#".$imagename[0]["design_text_color"]; ?>">
				<?php echo $imagename[0]["templates1"]; ?>
		   </div>
</div>
<?php
}

///------------------------------------------------///


$user_id = $user->id;


$user_id_ct =JRequest::getVar('ct', '', 'get');
$user_id = $user->id;

if(intval($user_id_ct) != 0){
	$user_id = intval($user_id_ct);
}


$firstnamelastname = "SELECT firstname, lastname FROM #__guru_customer WHERE id=".intval($user_id);
$db->setQuery($firstnamelastname);
$db->query();
$firstnamelastname = $db->loadAssocList();

$coursemsg = "SELECT certificate_course_msg FROM #__guru_program WHERE id=".intval($course_id);
$db->setQuery($coursemsg);
$db->query();
$coursemsg = $db->loadResult();

if($user_id !="" && $backend !=1){
	//$scores_avg_quizzes =  guruModelguruTask::getAvgScoresQ($user_id,$course_id);
}

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


$certificate_url = JUri::base()."index.php?option=com_guru&view=guruOrders&task=printcertificate&opt=".$certificateid."&cn=".$coursename."&an=".$authorname."&cd=".$completiondate."&id=".$certificateid;
$certificate_url = str_replace(" ", "%20", $certificate_url);


$imagename[0]["templates1"]  = str_replace("[SITENAME]", $sitename, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[STUDENT_FIRST_NAME]", $firstnamelastname[0]["firstname"], $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[STUDENT_LAST_NAME]", $firstnamelastname[0]["lastname"], $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[SITEURL]", $site_url, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[CERTIFICATE_ID]", $certificateid, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[COMPLETION_DATE]", $completiondate, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[COURSE_NAME]", $coursename, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[AUTHOR_NAME]", $authorname, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[CERT_URL]", $certificate_url, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[COURSE_MSG]", $coursemsg, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[COURSE_AVG_SCORE]", $avg_certc1, $imagename[0]["templates1"]);
$imagename[0]["templates1"]  = str_replace("[COURSE_FINAL_SCORE]", $avg_certc, $imagename[0]["templates1"]);





$imagename[0]["templates2"]  = str_replace("[SITENAME]", $sitename, $imagename[0]["templates2"]);
$imagename[0]["templates2"]  = str_replace("[STUDENT_FIRST_NAME]", $firstnamelastname[0]["firstname"], $imagename[0]["templates2"]);
$imagename[0]["templates2"]  = str_replace("[STUDENT_LAST_NAME]", $firstnamelastname[0]["lastname"], $imagename[0]["templates2"]);
$imagename[0]["templates2"]  = str_replace("[SITEURL]", $site_url, $imagename[0]["templates2"]);
$imagename[0]["templates2"]  = str_replace("[CERTIFICATE_ID]", $certificateid, $imagename[0]["templates2"]);
$imagename[0]["templates2"]  = str_replace("[COMPLETION_DATE]", $completiondate, $imagename[0]["templates2"]);
$imagename[0]["templates2"]  = str_replace("[COURSE_NAME]", $coursename, $imagename[0]["templates2"]);
$imagename[0]["templates2"]  = str_replace("[AUTHOR_NAME]", $authorname, $imagename[0]["templates2"]);
$imagename[0]["templates2"]  = str_replace("[CERT_URL]", $certificate_url, $imagename[0]["templates2"]);
$imagename[0]["templates2"]  = str_replace("[COURSE_MSG]", $coursemsg, $imagename[0]["templates2"]);
$imagename[0]["templates2"]  = str_replace("[COURSE_AVG_SCORE]", $avg_certc1, $imagename[0]["templates2"]);
$imagename[0]["templates2"]  = str_replace("[COURSE_FINAL_SCORE]", $avg_certc, $imagename[0]["templates2"]);


$imagename[0]["templates3"]  = str_replace("[SITENAME]", $sitename, $imagename[0]["templates3"]);
$imagename[0]["templates3"]  = str_replace("[STUDENT_FIRST_NAME]", $firstnamelastname[0]["firstname"], $imagename[0]["templates3"]);
$imagename[0]["templates3"]  = str_replace("[STUDENT_LAST_NAME]", $firstnamelastname[0]["lastname"], $imagename[0]["templates3"]);
$imagename[0]["templates3"]  = str_replace("[SITEURL]", $site_url, $imagename[0]["templates3"]);
$imagename[0]["templates3"]  = str_replace("[CERTIFICATE_ID]", $certificateid, $imagename[0]["templates3"]);
$imagename[0]["templates3"]  = str_replace("[COMPLETION_DATE]", $completiondate, $imagename[0]["templates3"]);
$imagename[0]["templates3"]  = str_replace("[COURSE_NAME]", $coursename, $imagename[0]["templates3"]);
$imagename[0]["templates3"]  = str_replace("[AUTHOR_NAME]", $authorname, $imagename[0]["templates3"]);
$imagename[0]["templates3"]  = str_replace("[CERT_URL]", $certificate_url, $imagename[0]["templates3"]);
$imagename[0]["templates3"]  = str_replace("[COURSE_MSG]", $coursemsg, $imagename[0]["templates3"]);
$imagename[0]["templates3"]  = str_replace("[COURSE_AVG_SCORE]", $avg_certc1, $imagename[0]["templates3"]);
$imagename[0]["templates3"]  = str_replace("[COURSE_FINAL_SCORE]", $avg_certc, $imagename[0]["templates3"]);


$imagename[0]["templates4"]  = str_replace("[SITENAME]", $sitename, $imagename[0]["templates4"]);
$imagename[0]["templates4"]  = str_replace("[STUDENT_FIRST_NAME]", $firstnamelastname[0]["firstname"], $imagename[0]["templates4"]);
$imagename[0]["templates4"]  = str_replace("[STUDENT_LAST_NAME]", $firstnamelastname[0]["lastname"], $imagename[0]["templates4"]);
$imagename[0]["templates4"]  = str_replace("[CERTIFICATE_ID]", $certificateid, $imagename[0]["templates4"]);
$imagename[0]["templates4"]  = str_replace("[COMPLETION_DATE]", $completiondate, $imagename[0]["templates4"]);
$imagename[0]["templates4"]  = str_replace("[COURSE_NAME]", $coursename, $imagename[0]["templates4"]);
$imagename[0]["templates4"]  = str_replace("[SITEURL]", $site_url, $imagename[0]["templates4"]);
$imagename[0]["templates4"]  = str_replace("[AUTHOR_NAME]", $authorname, $imagename[0]["templates4"]);
$imagename[0]["templates4"]  = str_replace("[CERT_URL]", $certificate_url, $imagename[0]["templates4"]);
$imagename[0]["templates4"]  = str_replace("[COURSE_MSG]", $coursemsg, $imagename[0]["templates4"]);
$imagename[0]["templates4"]  = str_replace("[COURSE_AVG_SCORE]", $avg_certc1, $imagename[0]["templates4"]);
$imagename[0]["templates4"]  = str_replace("[COURSE_FINAL_SCORE]", $avg_certc, $imagename[0]["templates4"]);




?>
<script language="javascript" type="text/javascript">
	function iJoomlaCertClose() {
		window.close();
	}
</script>
<?php
if(isset($url_copy_id) && $url_copy_id!=0){
?>
<div id="g_certificate" class="g_certificate clearfix">
	<div id="g_certificate_section" class="g_sect clearfix">
    	<div class="g_row">
            <div class="g_cell span12">
            	<div>
                	<div>
                    	<div>
                        	<div>
								<?php echo $imagename[0]["templates2"];?><br/>
                            </div>
                             <div style="height:600px; <?php echo $background_color;?>; background-size:100% 100%; background-repeat:no-repeat; background-image:url(<?php echo JUri::base()."images/stories/guru/certificates/".$image_theme; ?>); position:relative;">
                                <div class="g_certificate_view" style="font-family:<?php echo $imagename[0]["font_certificate"]; ?>; color:<?php echo "#".$imagename[0]["design_text_color"]; ?>">
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
    </div>       
</div>       
<?php
}

if($option_selected == 1){
	if($deviceType !='phone'){
?>
	<div class="visible-desktop visible-tablet hidden-phone">
  	<div style="position:absolute; left:-10px;">
 	 <div style="width:800px; height:600px; <?php echo $background_color;?>; background-size:100% 100%; background-repeat:no-repeat; background-image:url(<?php echo JUri::base()."images/stories/guru/certificates/".$image_theme; ?>);">
        <div class="g_certificate_view" style="font-family:<?php echo $imagename[0]["font_certificate"]; ?>; color:<?php echo "#".$imagename[0]["design_text_color"]; ?>">
                <?php echo $imagename[0]["templates1"]; ?>
        </div>
    </div>
   </div> 
   </div>
<?php }
	else{
?>

	<div class="hidden-desktop hidden-tablet visible-phone g_mobile_certificate_view">
 	 	 <div style="font-family:<?php echo $imagename[0]["font_certificate"]; ?>;<?php echo $background_color;?>; background-image:url(<?php echo JUri::base()."images/stories/guru/certificates/".$image_theme; ?>);">
        <div class="row-fluid g_certificate_view_mobile" style="color:<?php echo "#".$imagename[0]["design_text_color"];?>">
                <?php echo $imagename[0]["templates1"]; ?>
        </div>
    </div>

 	</div>
<?php	
	}
}
elseif($option_selected == 2){
?>
<div class="certificate_envelope modal-window">
    <div class="g_row">
        <div class="g_cell span6">
            <div class="g_certificate_detail clearfix" style=" <?php echo $background_color;?>; background-image:url(<?php echo JUri::base()."images/stories/guru/certificates/".$image_theme; ?>);">
                <h3 style="color:<?php echo "#".$imagename[0]["design_text_color"]; ?>"><?php echo JText::_("GURU_CERTIFICATE_OF_COMPLETION"); ?></h3>
            </div>    
        </div>
        <div class="g_cell span6">
            <div class="alert-info">
                <b><?php echo $firstnamelastname[0]["firstname"]." ".$firstnamelastname[0]["lastname"]; ?></b>
                <b><?php echo $coursename; ?></b>
                <p><?php echo JText::_("GURU_SHARE_CERTIFICATE"); ?></p>
            </div>    
        </div>
    </div>
    <div class="g_row">
    	<div class="g_cell span12">
            <form id="emailcertificate" name="emailcertificate" method="post" class="form-horizontal" role="form" action="index.php?option=com_guru&view=guruOrders&task=sendemailcertificate">
              <div class="form-group">
                    <label for="email"><?php echo JText::_("GURU_EMAIL_CERTIFICATE_FORM"); ?> </label>
                    <input class="guru_textbox g_std_input" type="text" name="emails" id="emails" value="">
              </div>
              <div class="form-group">
                    <label for="persmsg"><?php echo JText::_("GURU_EMAIL_PERSONAL_MESSAGE"); ?></label>
                    <textarea name="personalmessage" id="personalmessage" rows="3"></textarea>
              </div>
            
            <input type="hidden" name="color" value="<?php echo $imagename[0]["design_text_color"]; ?>" />
            <input type="hidden" name="image" value="<?php echo $image_theme; ?>" />
            <input type="hidden" name="bgcolor" value="<?php echo $background_color; ?>" />
            <input type="hidden" name="course_name" value="<?php echo $coursename; ?>" />
            <input type="hidden" name="studentfn" value="<?php echo $firstnamelastname[0]["firstname"]; ?>" />
            <input type="hidden" name="studentln" value="<?php echo $firstnamelastname[0]["lastname"]; ?>" />
            <input type="hidden" name="cn" value="<?php echo $coursename; ?>" />
            <input type="hidden" name="id" value="<?php echo $certificateid; ?>" />
            <input type="hidden" name="an" value="<?php echo $authorname; ?>" />
            <input type="hidden" name="cd" value="<?php echo $completiondate; ?>" />
            <input type="hidden" name="ci" value="<?php echo $course_id; ?>" />
            </form>
            
            <div id="certificateemailsbutton">
            		<span>
                    	<input class="btn btn-danger" type="button" onclick="javascript:iJoomlaCertClose();" id="cancel" name="cancel" value="<?php echo JText::_("GURU_CANCEL"); ?>">
                    </span>
                    <span>
                        <input class="btn btn-primary" type="button" onclick="emailcertificate.submit();" id="send" name="send" value="<?php echo JText::_("GURU_SEND"); ?>" />
                    </span>
                   
            </div>
        </div> 
     </div>     
</div>
<?php }
elseif($option_selected == 3){
?>
<div class="g_row clearfix modal-window">
    <div class="g_cell span12">
    <?php echo JText::_("GURU_URL_CERTIFICATE_TEXT"); ?>
    
    </div>
	<div class="g_cell span12">
    	<b><?php echo JText::_("GURU_URL_CERTIFICATE_TEXT2"); ?></b>
    </div>
	<div class="g_cell span12">
    	<input onclick="SelectAll('g_copy_link');" type="text" name ="g_copy_link" id="g_copy_link" value="<?php echo JUri::base()."index.php?option=com_guru&view=guruOrders&task=printcertificate&opt=".$certificateid."&cn=".$coursename."&an=".$authorname."&cd=".$completiondate."&id=".$certificateid."&ci=".$course_id."&ct=".$user_id; ?>">
    </div>
 </div>
<?php }?>