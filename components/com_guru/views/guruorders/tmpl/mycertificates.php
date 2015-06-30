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
defined ('_JEXEC') or die ("Go away.");
include(JPATH_SITE.DS.'components'.DS.'com_guru'.DS.'models'.DS.'gurutask.php');
JHTML::_('behavior.tooltip');
	
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_guru/css/guru_style.css");
$document->addScript("components/com_guru/js/programs.js");
$db = JFactory::getDBO();
$user = JFactory::getUser();
$user_id = $user->id;
//$my_certificates= $guruModelguruOrder->getMyCertificates();
$Itemid = JRequest::getVar("Itemid", "0");
$search = JRequest::getVar("search_course", "");
$config = $this->getConfigSettings();
$guruModelguruOrder = new guruModelguruOrder();
$guruModelguruTask = new guruModelguruTask();

$my_courses = $this->my_courses;
$certcourseidlist = $guruModelguruOrder->getCourseidsList($user_id);
$certificates_general = $guruModelguruOrder->getCertificate(); 

$document->setTitle(trim(JText::_('GURU_MYCERTIFICATES')));
$return_url = base64_encode("index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=".intval(@$itemid));
if($config->gurujomsocialprofilestudent == 1){
	$link = "index.php?option=com_community&view=profile&task=edit&Itemid=".$Itemid;
}
else{
	$link = "index.php?option=com_guru&view=guruProfile&task=edit&Itemid=".$Itemid;
}
	
if(count($my_courses) > 0){
?>
<script>
function openWinCertificate1(t1,t2,t3,t4,t5)
{
	myWindow=window.open('<?php echo JURI::root();?>index.php?option=com_guru&view=guruOrders&task=printcertificate&op=1&cn='+t1+'&an='+t2+'&id='+t3+'&cd='+t4+'&ci='+t5+'&tmpl=component','','width=800,height=600, resizable = 0');
	myWindow.focus();
}
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
<div class="g_row">
	<div class="g_cell span12">
		<div>
			<div>
                <form action="index.php" name="adminForm" method="post">
                <!--CHANGED BY JOSEPH 31/03/2015-->
                    <!--<div id="guru_menubar" class="clearfix g_toolbar guru_menubar">
                        <ul>
                            <li id="my_account"><a href="<?php //echo $link; ?>"><i class="icon-user"></i><?php //echo JText::_("GURU_MY_ACCOUNT"); ?></a></li>
                            <li id="my_courses"><a href="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php //echo $Itemid; ?>"><i class="icon-eye-open"></i><?php //echo JText::_("GURU_MYCOURSES"); ?></a></li>
                            <li id="my_orders"><a href="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php //echo $Itemid; ?>"><i class="icon-cart"></i><?php //echo JText::_("GURU_MYORDERS_MYORDERS"); ?></a></li>
                            <li id="my_quizzes"><a href="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php //echo $Itemid; ?>"><i class="icon-question-sign"></i><?php //echo JText::_("GURU_QUIZZ_FINAL_EXAM"); ?></a></li>
                            <li id="my_certificates"><a class="g_toolbar_active" href="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php //echo $Itemid; ?>"><i class="icon-star"></i><?php //echo JText::_("GURU_MYCERTIFICATES"); ?></a></li>
                            
                            <li class="g_hide_mobile logout-btn" id="g_logout">
                                <a href="index.php?option=com_users&task=user.logout&<?php //echo JSession::getFormToken(); ?>=1&Itemid=<?php //echo $Itemid; ?>&return=<?php //echo $return_url;?>">
                                    <i class="fa fa-sign-out"></i>
                                </a>
							</li>
                        </ul>
                    </div>
                    <div id="guru_menubar_mobile" class="g_mobile guru_menubar g_select">
                        <select name="menuboostrap" class="g_select" id="menuboostrap" onchange="window.open(this.value, '_self');" >
                         <option value="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_MYCOURSES");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_MYORDERS_MYORDERS");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_QUIZZ_FINAL_EXAM");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_MYCERTIFICATES");?></option>
                         <option value="index.php?option=com_guru&view=guruBuy&Itemid=<?php //echo $Itemid; ?>"><?php //echo JText::_("GURU_CART");?></option>
                         <option value="index.php?option=com_users&task=user.logout&<?php //echo JSession::getFormToken(); ?>=1&Itemid=<?php //echo $Itemid; ?>"><?php //echo JText::_("GURU_LOGIN_OUT");?></option>
                      </select>
                  </div>-->
                <!--END-->    
                    <div id="mycertificates" class="clearfix com-cont-wrap">
                        <div class="clearfix">
                            <div class="mycourses_page page_title g_cell span7">
                                <h2><?php echo JText::_('GURU_MYCERTIFICATES');?></h2>
                            </div> 
                        <!--REMOVED BY JOSEPH 31/03/2015-->
                            <!--<div id="g_user_action" class="g_cell span5 g_hide_mobile">
                                <a class="btn btn-success" href="index.php?option=com_guru&view=guruBuy&Itemid=<?php echo $Itemid; ?>"><img src="components/com_guru/images/cart.gif" alt="<?php echo JText::_("GURU_MY_CART"); ?>"/><u><?php echo JText::_("GURU_CART"); ?></u></a>
                          </div>-->
                        <!--END-->
                       </div>  
                
                        
                        <!-- Start Search -->
                        <div class="clearfix">
                            <div class="g_cell span8">
                                <div class="input-group g_search">
                                  <input type="text" class="form-control inputbox" name="search_course" value="<?php if(isset($_POST['search_course'])) echo $_POST['search_course'];?>" >
                                  <span class="input-group-btn g_hide_mobile">
                                    <button class="btn btn-primary" type="submit"><?php echo JText::_("GURU_SEARCH"); ?></button>
                                  </span>
                                </div><!-- /input-group -->
                            </div>
                        </div>
                        <!-- End Search -->
                   <div class="clearfix">
                    <div class="g_table_wrap">     
                        <table class="table table-striped">
                            <tr class="g_table_header">
                                <th class="g_cell_1"><span class="g_hide_mobile"><?php echo JText::_("GURU_PROGRAM_PROGRAMS"); ?></span> <span><?php echo JText::_("GURU_NAME"); ?></span></th>
                                <th class="g_cell_2 g_hide_mobile"><?php echo JText::_("GURU_TERM"); ?></th>
                                <th class="g_cell_3 g_hide_mobile"><?php echo JText::_("GURU_LESSONS_COMPLETED"); ?></th>
                                <th class="g_cell_4 g_hide_mobile"><?php echo JText::_("GURU_QUIZES_AVG_SCORE"); ?></th>
                                <th class="g_cell_5 g_hide_mobile"><?php echo JText::_("GURU_FINAL_EXAM_SCORE"); ?></th>
                                <th class="g_cell_6"><?php echo JText::_("GURU_OPTIONS"); ?></th>
                            </tr>
                            <tr>
                                <?php
                                    $k = 0;
                                    $hascertificate = false;
                                    $already_edited = array();
                                    $db		= JFactory::getDBO();
                                    $datetype = "SELECT datetype from #__guru_config WHERE id=1";
                                    $db->setQuery($datetype);
                                    $db->query();
                                    $datetype = $db->loadResult();
                                    
                                    
                                    
                                    $n = count($my_courses);
                    
                                    foreach($my_courses as $key=>$course){
                                        $class = "odd";
                                        if($k%2 != 0){
                                            $class = "even";
                                        }
                    
                                        $id = $my_courses[$key]["course_id"];
                                        
                                        $scores_avg_quizzes =  $guruModelguruTask->getAvgScoresQ($user_id,$id);
                                        
                                        $avg_quizzes_cert = "SELECT avg_certc FROM #__guru_program WHERE id=".intval($id);
                                        $db->setQuery($avg_quizzes_cert);
                                        $db->query();
                                        $avg_quizzes_cert = $db->loadResult();
                                        
                    
                                        $sql = "SELECT id_final_exam FROM #__guru_program WHERE id=".intval($id);
                                        $db->setQuery($sql);
                                        $result = $db->loadResult();
                                        
                                        $sql = "SELECT hasquiz from #__guru_program WHERE id=".intval($id);
                                        $db->setQuery($sql);
                                        $resulthasq = $db->loadResult();
                                    
                                        $sql = "SELECT max_score FROM #__guru_quiz WHERE id=".intval($result);
                                        $db->setQuery($sql);
                                        $result_maxs = $db->loadResult();
                            
                                        $sql = "SELECT id, score_quiz, time_quiz_taken_per_user  FROM #__guru_quiz_taken WHERE user_id=".intval($user_id)." and quiz_id=".intval($result)." and pid=".intval($id)." ORDER BY id DESC LIMIT 0,1";
                                        $db->setQuery($sql);
                                        $result_q = $db->loadObject();
                                        
                                        $first= explode("|", @$result_q->score_quiz);
                                        
                                        @$res = intval(($first[0]/$first[1])*100);
                                    
                                        $certterm = $my_courses[$key]["certerm"];
                                        
                                        
                                        
                                        if(!in_array($id, $already_edited)){
                                            $already_edited[] = $id;
                                        ?>
                                                <td class="g_cell_1"> 
                                                    <?php
                                                        $course_details = $guruModelguruOrder->getCourses($id);
                                                        $certificateid =  $guruModelguruOrder->getCertificateId($user_id, $id);
                                                        $author_name = "SELECT name FROM #__users WHERE id IN (SELECT author_id from #__guru_mycertificates where course_id=".intval($id)." and user_id=".intval($user_id).") ";		
                                                        $db->setQuery($author_name);
                                                        $author_name = $db->loadResult();
                                                        
                                                        $date_completed = "SELECT datecertificate from #__guru_mycertificates where course_id=".intval($id)." and user_id=".intval($user_id);
                                                        $db->setQuery($date_completed);
                                                        $date_completed = $db->loadResult();
                                                        
                                                        $datetype = "SELECT datetype from #__guru_config WHERE id=1";
                                                        $db->setQuery($datetype);
                                                        $datetype = $db->loadResult();
                                                        $date_completed =  date($datetype, strtotime($date_completed));
                                                        
                                                        if($certterm == 0){
                                                            $details = JText::_("GURU_NO_CERT_GIVEN");
                                                        }
                                                        elseif($certterm == 1){
                                                            $details = JText::_("GURU_NO_CERT_GIVEN");
                                                        }
                                                        elseif($certterm == 2){
                                                            $details = JText::_("GURU_MUST_COLMP_ALL_LESS");
                                                        }
                                                        elseif($certterm == 3){
                                                            $details = JText::_("GURU_MUST_PASS_FE")." ".$result_maxs."%";
                                                        }
                                                        elseif($certterm == 4){
                                                            $details = JText::_("GURU_MUST_PASS_QAVG")." ".$avg_quizzes_cert."%";
                                                        }
                                                        elseif($certterm == 5){
                                                            $details = JText::_("GURU_CERT_TERM_FALFE");
                                                        }
                                                        elseif($certterm == 6){
                                                            $details = JText::_("GURU_CERT_TERM_FALPQAVG")." ".$avg_quizzes_cert."%";
                                                        }
                                                        $completed_course = $guruModelguruOrder->courseCompleted($user_id, $id);
                    
                    ?>
                                                        <a href="<?php echo JRoute::_("index.php?option=com_guru&view=guruPrograms&task=view&cid=".$id."-".@$alias."&Itemid=".$Itemid); ?>"><?php echo $my_courses[$key]["course_name"]; ?></a>
                                                    </td>
                                                    <td class="g_cell_2 g_hide_mobile"><?php echo $details ;  ?> </td>
                                                    <td class="g_cell_3 g_hide_mobile">
                                                        <?php
                                                            if($completed_course == true){
                                                                echo '<span  style="color:#66CC00;">'.JText::_("GURU_YES").'</span>'; 
                                                            }
                                                            else{
                                                                echo '<span  style="color:#FF0000;">'.JText::_("GURU_NO").'</span>'; 
                                                            }
                                                        
                                                         ?>
                                                    </td>
                                                    <td class="g_cell_4 g_hide_mobile">
                                                        <?php
                                                        if($resulthasq == 0 && $scores_avg_quizzes == ""){
                                                            echo JText::_("GURU_NO_QUIZZES");
                                                        }
                                                        elseif($resulthasq != 0 && $scores_avg_quizzes == ""){
                                                            echo JText::_("GURU_NOT_TAKEN");
                                                        }
                                                        elseif($resulthasq != 0 && isset($scores_avg_quizzes)){
                                                            if($scores_avg_quizzes >= intval($avg_quizzes_cert)){
                                                                echo $scores_avg_quizzes.'%'.'<span  style="color:#66CC00;">'.JText::_("GURU_QUIZ_PASSED").'</span>'; 
                                                            }
                                                            else{
                                                                echo $scores_avg_quizzes.'%'.'<span  style="color:#FF0000;">'.JText::_("GURU_QUIZ_FAILED").'</span>';
                                                            }
                                                        } 
                                                        ?>
                                                    </td>
                                                    <td class="g_cell_5 g_hide_mobile">
                                                        <?php
                                                            if($result !=0 && $res !="" ){
                                                                if( $res >= $result_maxs){
                                                                    echo $res.'%'.'<span  style="color:#66CC00;">'.JText::_("GURU_QUIZ_PASSED").'</span>';
                                                                }
                                                                elseif($res < $result_maxs){
                                                                    echo $res.'%'.'<span  style="color:#FF0000;">'.JText::_("GURU_QUIZ_FAILED").'</span>';
                                                                }
                                                            }
                                                            elseif(($result !=0 && $result !="")){
                                                                echo JText::_("GURU_NOT_TAKEN");
                                                            }
                                                            elseif($result ==0 || $result ==""){
                                                                echo JText::_("GURU_NO_FINAL_EXAM");
                                                            }
                                                            
                                                         ?>
                                                    </td>
                    
                                                 <td class="g_cell_6">   
                    
                                             <?php
                                                //--------------hascertificate calculation-------------------
                                                if($certterm == 1 || $certterm == 0){
                                                    $hascertficate = false;
                                                }
                                                if($certterm == 2){
                                                    if($completed_course == true){
                                                        $hascertficate = true;
                                                    }
                                                    else{
                                                        $hascertficate = false;
                                                    }
                                                }
                                                elseif($certterm == 3){
                                                    if( $res >= $result_maxs){
                                                        $hascertficate = true;
                                                    }
                                                    else{
                                                        $hascertficate = false;
                                                    }
                                                }
                                                elseif($certterm == 4){
                                                    if($scores_avg_quizzes >= intval($avg_quizzes_cert)){
                                                        $hascertficate = true;
                                                    }
                                                    else{
                                                        $hascertficate = false;
                                                    }
                                                }
                                                elseif($certterm == 5){
                                                    if($completed_course==true && isset($result_maxs) && $res >= intval($result_maxs)){
                                                        $hascertficate = true;
                                                    }
                                                    else{
                                                        $hascertficate = false;
                                                    }
                                                }
                                                elseif($certterm == 6){
                                                    if($completed_course==true && isset($scores_avg_quizzes) && ($scores_avg_quizzes >= intval($avg_quizzes_cert))){
                                                        $hascertficate = true;
                                                    }
                                                    else{
                                                        $hascertficate = false;
                                                    }
                                                }
                                                
                                                //-----------------------------------------------------------
                                             
                                                    if($hascertficate == false ){
                                                        if($certterm == 0){
                                                            $span = JText::_("GURU_NO_CERT_MYC");
                                                        }
                                                        elseif($certterm == 1){
                                                            $span = JText::_("GURU_NO_CERT_MYC");
                                                        }
                                                        elseif($certterm == 2){
                                                            $span = JText::_("GURU_ALLLESS_CERT_MYC");
                                                        }
                                                        elseif($certterm == 3){
                                                            if($res == ""){
                                                                $span =  JText::_("GURU_PASSF_CERT_MYC")." ".$result_maxs."%,".JText::_("GURU_YOUR_SCORE_IS2");
                                                            }
                                                            elseif(isset($result_maxs) && $res < intval($result_maxs)){
                                                                $span =  JText::_("GURU_PASSF_CERT_MYC")." ".$result_maxs."%,".JText::_("GURU_YOUR_SCORE_IS") ." ".$res."%";
                                                            }
                                                            else{
                                                                $span = JText::_("GURU_PASSF_CERT_MYC")." ".$result_maxs."%,".JText::_("GURU_YOUR_SCORE_IS2");
                                                            }
                                                        }
                                                        elseif($certterm == 4){
                                                            if(isset($scores_avg_quizzes) && ($scores_avg_quizzes < intval($avg_quizzes_cert))){
                                                                $span = JText::_("GURU_PASSAVG")." ".$avg_quizzes_cert."%,".JText::_("GURU_YOUR_SCORE_WAS")." ".$scores_avg_quizzes."%";
                                                            }
                                                            elseif($scores_avg_quizzes == null){
                                                                $span = JText::_("GURU_PASSAVG")." ".$avg_quizzes_cert."%,".JText::_("GURU_YOUR_SCORE_WAS2");
                                                            }
                                                            
                                                        }
                                                        elseif($certterm == 5){
                                                            if($completed_course==true && isset($result_maxs) && $res < intval($result_maxs)){
                                                                $span =  JText::_("GURU_FINISH_ALL_LESSONS_PASSFE1")." ".$result_maxs."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSFE2") ." ".$res."%";
                                                            }
                                                            elseif($completed_course==false && isset($result_maxs) && $result_maxs < intval($res)){
                                                                $span = JText::_("GURU_FINISH_ALL_LESSONS_PASSFE1")." ".$result_maxs."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSFE4");
                        
                                                            }
                                                            elseif($completed_course==false && isset($result_maxs) && $res < intval($result_maxs)){
                                                                $span = JText::_("GURU_FINISH_ALL_LESSONS_PASSFE1")." ".$result_maxs."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSFE3")." ".$res."%";
                                                            }
                                                            elseif($completed_course==false && $res == ""){
                                                                $span = JText::_("GURU_FINISH_ALL_LESSONS_PASSFE1")." ".$result_maxs."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSFE5")." ".$res."%";
                                                            }
                                                        }
                                                        elseif($certterm == 6){
                                                            if($completed_course==true && isset($scores_avg_quizzes) && ($scores_avg_quizzes < intval($avg_quizzes_cert))){
                                                                $span = JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG1")." ".$avg_quizzes_cert."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG2")." ".$scores_avg_quizzes."%";
                                                            }
                                                            elseif($completed_course==false && isset($scores_avg_quizzes) && ($avg_quizzes_cert < intval($scores_avg_quizzes))){
                                                                $span = JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG1")." ".$avg_quizzes_cert."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG4");
                        
                                                            }
                                                            elseif($completed_course==false && $scores_avg_quizzes == ""){
                                                                $span = JText::_("GURU_FINISH_ALL_LESSONS_PASSFE1")." ".$avg_quizzes_cert."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG5");
                                                            }								
                                                            else{
                                                                $span = JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG1")." ".$avg_quizzes_cert."%".JText::_("GURU_FINISH_ALL_LESSONS_PASSAVG3")." ".$scores_avg_quizzes."%";
                                                            }
                                                        }
                                                        ?>
                                                            <span style="color:#FF6600"><?php echo  JText::_("GURU_NOT_ELIGIBLE");?></span><br/>
                                                            <span class="editlinktip hasTip" title="<?php echo $span; ?>" style="color:#0099FF; font-size:12px;"><?php echo "( ".JText::_("GURU_WHY")." )";?>
                                                            </span>
                                                     
                                                        <?php
                                                    }
                                                        
                                                    elseif(in_array($id, $certcourseidlist) && ($hascertficate == true || $hascertficate == 1)){
                                                    
                                                  ?>
                                                        
                                                            <a href="#" onclick="openWinCertificate1('<?php echo str_replace("'","&acute;",$course_details[0]["name"])?>','<?php echo $author_name ?>','<?php echo $certificateid ?>', '<?php echo $date_completed ?>', '<?php echo $id; ?>')"><img title="<?php echo JText::_("GURU_VIEW_TOOLTIP"); ?>" src="<?php echo JUri::root()."/images/stories/guru/certificates/viewed.png"; ?>" align="viewed" /></a>
                                                            <a href="#" onclick="openWinCertificate4('<?php echo str_replace("'","&acute;",$course_details[0]["name"])?>','<?php echo $author_name ?>','<?php echo $certificateid ?>', '<?php echo $date_completed ?>', '<?php echo $id; ?>')"><img title="<?php echo JText::_("GURU_DLD_TOOLTIP"); ?>" src="<?php echo JUri::root()."/images/stories/guru/certificates/download.png"; ?>" align="viewed" /></a>
                                                            <a href="#" onclick="openWinCertificate3('<?php echo str_replace("'","&acute;",$course_details[0]["name"])?>','<?php echo $author_name ?>','<?php echo $certificateid ?>', '<?php echo $date_completed ?>', '<?php echo $id; ?>')"><img title="<?php echo JText::_("GURU_LINK_TOOLTIP"); ?>" src="<?php echo JUri::root()."/images/stories/guru/certificates/link.png"; ?>" align="viewed" /></a>	
                                                            <a href="#" onclick="openWinCertificate2('<?php echo str_replace("'","&acute;",$course_details[0]["name"])?>','<?php echo $author_name ?>','<?php echo $certificateid ?>', '<?php echo $date_completed ?>', '<?php echo $id; ?>')"><img title="<?php echo JText::_("GURU_EMAIL_TOOLTIP"); ?>" src="<?php echo JUri::root()."/images/stories/guru/certificates/email.png"; ?>" align="viewed" /></a>
                                                      <?php 
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
                 </div>  
                    <input type="hidden" name="option" value="com_guru" />
                    <input type="hidden" name="controller" value="guruOrders" />
                    <input type="hidden" name="task" value="mycertificates" />
                    <input type="hidden" name="order_id" value="" />
                    <input type="hidden" name="course_id" value="" />
                </form>
               </div>
             </div>
          </div>
         </div>    
<?php
	}
	else{
?>
<div class="g_row">
	<div class="g_cell span12">
		<div>
			<div>
                <form action="index.php" name="adminForm" method="post">
                
                    <div id="guru_menubar" class="g_toolbar guru_menubar">
                        <ul>
                            <li id="my_account"><a href="<?php echo $link; ?>"><i class="icon-user"></i><?php echo JText::_("GURU_MY_ACCOUNT"); ?></a></li>
                            <li id="my_courses"><a href="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php echo $Itemid; ?>"><i class="icon-eye-open"></i><?php echo JText::_("GURU_MYCOURSES"); ?></a></li>
                            <li id="my_orders"><a href="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php echo $Itemid; ?>"><i class="icon-cart"></i><?php echo JText::_("GURU_MYORDERS_MYORDERS"); ?></a></li>
                            <li id="my_quizzes"><a href="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php echo $Itemid; ?>"><i class="icon-question-sign"></i><?php echo JText::_("GURU_QUIZZ_FINAL_EXAM"); ?></a></li>
                            <li id="my_certificates"><a class="g_toolbar_active" href="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php echo $Itemid; ?>"><i class="icon-star"></i><?php echo JText::_("GURU_MYCERTIFICATES"); ?></a></li>
                            <li class="g_hide_mobile logout-btn" id="g_logout">
                                <a href="index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken(); ?>=1&Itemid=<?php echo $Itemid; ?>&return=<?php echo $return_url;?>">
                                    <i class="fa fa-sign-out"></i>
                                </a>
							</li>
                        </ul>
                    </div>
                    <div id="guru_menubar_mobile" class="g_mobile guru_menubar g_select">
                        <select name="menuboostrap" class="g_select" id="menuboostrap" onchange="window.open(this.value, '_self');" >
                         <option value="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_MYCOURSES");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_MYORDERS_MYORDERS");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_QUIZZ_FINAL_EXAM");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_MYCERTIFICATES");?></option>
                         <option value="index.php?option=com_guru&view=guruBuy&Itemid=<?php echo $Itemid; ?>"><?php echo JText::_("GURU_CART");?></option>
                         <option value="index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken(); ?>=1&Itemid=<?php echo $Itemid; ?>"><?php echo JText::_("GURU_LOGIN_OUT");?></option>
                      </select>
                  </div>
                    
                    <div id="mycertificates" class="clearfix com-cont-wrap">
                        <div class="clearfix">
                            <div class="mycourses_page page_title g_cell span7">
                                <h2><?php echo JText::_('GURU_MYCERTIFICATES');?></h2>
                            </div> 
                            <div id="g_user_action" class="g_cell span5 g_hide_mobile">
                                <a class="btn btn-success" href="index.php?option=com_guru&view=guruBuy&Itemid=<?php echo $Itemid; ?>"><img src="components/com_guru/images/cart.gif" alt="<?php echo JText::_("GURU_MY_CART"); ?>"/><u><?php echo JText::_("GURU_CART"); ?></u></a>
                          </div>
                       </div>  
                
                        
                        <!-- Start Search -->
                        <div class="clearfix">
                            <div class="g_cell span8">
                                <div class="input-group g_search">
                                  <input type="text" class="form-control inputbox" name="search_course" value="<?php if(isset($_POST['search_course'])) echo $_POST['search_course'];?>" >
                                  <span class="input-group-btn g_hide_mobile">
                                    <button class="btn btn-primary" type="submit"><?php echo JText::_("GURU_SEARCH"); ?></button>
                                  </span>
                                </div><!-- /input-group -->
                            </div>
                        </div>
                        <!-- End Search -->
		<?php echo JText::_("GURU_NO_MYCERTIFICATES"); ?>, <a href="<?php echo JRoute::_("index.php?option=com_guru&view=gurupcategs&Itemid=".intval($Itemid)); ?>"><?php echo JText::_("GURU_SEE_A_LIST"); ?></a>
        	</div>
        	<input type="hidden" name="option" value="com_guru" />
            <input type="hidden" name="controller" value="guruOrders" />
            <input type="hidden" name="task" value="mycourses" />
            <input type="hidden" name="order_id" value="" />
            <input type="hidden" name="course_id" value="" />
        </form>
        	</div>
    	</div>    
	</div>
</div>
<?php	
	}
?>