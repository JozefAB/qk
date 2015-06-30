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
jimport('joomla.html.pagination');
include(JPATH_SITE.DS.'components'.DS.'com_guru'.DS.'models'.DS.'gurutask.php');
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_guru/css/guru_style.css");
$document->addScript("components/com_guru/js/programs.js");
$document->setMetaData( 'viewport', 'width=device-width, initial-scale=1.0' );
require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");

JHtml::_('bootstrap.tooltip');
//JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
//JHtml::_('formbehavior.chosen', 'select');

$db = JFactory::getDBO();
$user = JFactory::getUser();
$user_id = $user->id;
$Itemid = JRequest::getVar("Itemid", "0");
$search = JRequest::getVar("search_course", "");
$config = $this->getConfigSettings();
$cid = array();
$guruModelguruOrder = new guruModelguruOrder();
$guruModelguruTask = new guruModelguruTask();


$certcourseidlist = $guruModelguruOrder->getCourseidsList($user_id);
$certificates_general = $guruModelguruOrder->getCertificate(); 

$document->setTitle(trim(JText::_('GURU_QUIZZ_FINAL_EXAM')));

$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
if($deviceType =="phone"){
	$styledisplay = 'display:inline-block !important;';
	$class_title = 'class="guruml20"';

}
else{
	$styledisplay = '';
	$class_title = 'class="guruml20"';
}

$return_url = base64_encode("index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=".intval(@$itemid));

if($config->gurujomsocialprofilestudent == 1){
	$link = "index.php?option=com_community&view=profile&task=edit&Itemid=".$Itemid;
}
else{
	$link = "index.php?option=com_guru&view=guruProfile&task=edit&Itemid=".$Itemid;
}
	
//if(count($my_quizzes)){
?>
<div class="g_row">
	<div class="g_cell span12">
		<div>
			<div>
                <form name="adminForm" method="post" id="adminForm">
                <!--REMOVED BY JOSEPH 31/03/2015-->
                   <!--<div id="guru_menubar" class="clearfix g_toolbar guru_menubar">
                        <ul>
                            <li id="my_account"><a href="<?php //echo $link; ?>"><i class="icon-user"></i><?php //echo JText::_("GURU_MY_ACCOUNT"); ?></a></li>
                            <li id="my_courses"><a href="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php //echo $Itemid; ?>"><i class="icon-eye-open"></i><?php //echo JText::_("GURU_MYCOURSES"); ?></a></li>
                            <li id="my_orders"><a href="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php //echo $Itemid; ?>"><i class="icon-cart"></i><?php //echo JText::_("GURU_MYORDERS_MYORDERS"); ?></a></li>
                            <li id="my_quizzes"><a class="g_toolbar_active" href="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php //echo $Itemid; ?>"><i class="icon-question-sign"></i><?php //echo JText::_("GURU_QUIZZ_FINAL_EXAM"); ?></a></li>
                            <li id="my_certificates"><a href="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php //echo $Itemid; ?>"><i class="icon-star"></i><?php //echo JText::_("GURU_MYCERTIFICATES"); ?></a></li>
                            
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
                         <option value="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_MYCERTIFICATES");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_QUIZZ_FINAL_EXAM");?></option>
                          <option value="index.php?option=com_guru&view=guruBuy&Itemid=<?php //echo $Itemid; ?>"><?php //echo JText::_("GURU_CART");?></option>
                         <option value="index.php?option=com_users&task=user.logout&<?php //echo JSession::getFormToken(); ?>=1&Itemid=<?php //echo $Itemid; ?>"><?php //echo JText::_("GURU_LOGIN_OUT");?></option>
                      </select>
                  </div>-->
                <!--END-->
                    
                     <div id="myquizzes" class="clearfix com-cont-wrap">
                        <div class="clearfix">
                            <div class="mycourses_page page_title g_cell span7">
                                <h2><?php echo JText::_('GURU_QUIZZ_FINAL_EXAM');?></h2>
                            </div> 
                        <!--REMOVED BY JOSEPH 31/03/2015-->
                           <!--<div id="g_user_action" class="g_cell span5 g_hide_mobile">
                                <a class="btn btn-success" href="index.php?option=com_guru&view=guruBuy&Itemid=<?php echo $Itemid; ?>"><img src="components/com_guru/images/cart.gif" alt="<?php echo JText::_("GURU_MY_CART"); ?>"/><u><?php echo JText::_("GURU_CART"); ?></u></a>
                          </div>-->
                        <!--END-->
                        </div>  
                        
                        <!-- Start Search and filters-->
                        <div id="g_quizzes_filters" class="clearfix">
                            <div class="g_cell span8 g_hide_mobile">
                                <div class="input-group g_search">
                                  <input type="text" class="form-control inputbox" name="search_course" value="<?php if(isset($_POST['search_course'])) echo $_POST['search_course'];?>" >
                                  <span class="input-group-btn g_hide_mobile">
                                    <button class="btn btn-primary" type="submit"><?php echo JText::_("GURU_SEARCH"); ?></button>
                                  </span>
                                </div><!-- /input-group -->
                            </div>
                            <div class="g_cell span12 g_mobile">
                                <div class="input-group g_search">
                                  <input type="text" class="form-control inputbox" name="search_course1" value="<?php if(isset($_POST['search_course1'])) echo $_POST['search_course1'];?>" >
                                  <span class="input-group-btn g_hide_mobile">
                                    <button class="btn btn-primary" type="submit"><?php echo JText::_("GURU_SEARCH"); ?></button>
                                  </span>
                                </div><!-- /input-group -->
                            </div>
                            <div  id="g_myquizzes_filters" class="g_cell span12 g_hide_mobile pull-right">
                                <div class="">
                                  <div class="g_cell span4">
                                     <select class="g_myquizzes_select" name="selectcoursesd" id="selectcoursesd" onchange="document.adminForm.submit();" >
                                         <option value="0" <?php if(@$psd == "0"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_SELECT_COURSE");?></option>
                        
                                        <?php
                                         if(isset($_POST['search_course']) && $_POST['search_course'] !="" ){
                                            $search_id  ="SELECT id FROM #__guru_program where name like '%".$_POST['search_course']."%' LIMIT 0,1";
                                            $db->setQuery($search_id);
                                            $db->query();
                                            $search_id = $db->loadResult();
											
                                            
                                            $psd=search_id;
                                         }
                                         if(isset($_POST['selectcoursesd'])){
                                            $psd=$_POST['selectcoursesd'];
                                         }
                                         if(isset($_POST['selecttyped'])){
                                           $ptd=$_POST['selecttyped'];
                                         }
                                         if(isset($_POST['selectstatus'])){
                                           $pcd=$_POST['selectstatus'];
                                         }
                                         if(!isset($psd)) {$psd=NULL;}
                                         if(!isset($ptd)) {$ptd=NULL;}
                                         if(!isset($pcd)) {$pcd=NULL;}
                                        ?>
                    
                                 <?php
                                        $cidd = "SELECT distinct pid from #__guru_quiz_taken where user_id=".intval($user_id);
                                        $db->setQuery($cidd);
                                        $cidd= $db->loadAssocList();
                    
                                            foreach($cidd as $key => $values){						
                                                $course_id = $values["pid"];
                                                $sql = "SELECT name FROM #__guru_program WHERE id=".intval($course_id);		
                                                $db->setQuery($sql);
                                                $db->query();
                                                $result_name = $db->loadResult();
                                                if($psd == $course_id){
                                                    $selected = 'selected="selected"';
                                                }
                                                else {
                                                    $selected = '';
                                                }
                                                ?>			
                                        <option value="<?php echo $course_id;?>"<?php echo $selected ; ?>><?php echo $result_name; ?></option>
                                        <?php
                                            }
                                        ?>
                                      </select>
                                  </div>
                                 <div class="g_cell span4">
                                      <select class="g_myquizzes_select" name="selecttyped" id="selecttyped" onchange="document.adminForm.submit();" >
                                         <option value="0" <?php if($ptd == "0"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_SELECT_TYPE");?></option>
                                         <option value="1" <?php if($ptd == "1"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_QUIZ");?></option>
                                         <option value="2" <?php if($ptd == "2"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_FQUIZ");?></option>
                                      </select>
                                 </div>
                                 <div class="g_cell span4">    
                                      <select class="g_myquizzes_select" name="selectstatus" id="selectstatus" onchange="document.adminForm.submit();" >
                                         <option value="0" <?php if($pcd == "0"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_SELECT_STATUS");?></option>
                                         <option value="1" <?php if($pcd == "1"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_QUIZ_PASSED_STATUS");?></option>
                                         <option value="2" <?php if($pcd == "2"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_QUIZ_FAILED_STATUS");?></option>
                                      </select>
                                </div>
                             </div>                 
                            </div> 
                            
                            <div class="g_cell span12 g_mobile">
                                <div class="">
                                  <div class="g_cell span12 g_mobile">
                                     <select class="g_myquizzes_select" name="selectcourses" id="selectcourses" onchange="document.adminForm.submit();" >
                                         <option value="0" <?php if(@$ps == "0"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_SELECT_COURSE");?></option>
                        
                                        <?php
                                         if(isset($_POST['search_course1']) && $_POST['search_course1'] !="" ){
                                            $search_id  ="SELECT id FROM #__guru_program where name like '%".$_POST['search_course1']."%' LIMIT 0,1";
                                            $db->setQuery($search_id);
                                            $db->query();
                                            $search_id = $db->loadResult();
                                            
                                            $ps=search_id;
                                         }
                                         if(isset($_POST['selectcourses'])){
                                            $ps=$_POST['selectcourses'];
                                         }
                                         if(isset($_POST['selecttype'])){
                                           $pt=$_POST['selecttype'];
                                         }
                                         if(isset($_POST['selectstatus'])){
                                           $pc=$_POST['selectstatus'];
                                         }
                                         if(!isset($ps)) {$ps=NULL;}
                                         if(!isset($pt)) {$pt=NULL;}
                                         if(!isset($pc)) {$pc=NULL;}
                                        ?>
                    
                                 <?php
                                        $cidd = "SELECT distinct pid from #__guru_quiz_taken where user_id=".intval($user_id);
                                        $db->setQuery($cidd);
                                        $cidd= $db->loadAssocList();
                    
                                            foreach($cidd as $key => $values){						
                                                $course_id = $values["pid"];
                                                $sql = "SELECT name FROM #__guru_program WHERE id=".intval($course_id);		
                                                $db->setQuery($sql);
                                                $db->query();
                                                $result_name = $db->loadResult();
                                                if($ps == $course_id){
                                                    $selected = 'selected="selected"';
                                                }
                                                else {
                                                    $selected = '';
                                                }
                                                ?>			
                                        <option value="<?php echo $course_id;?>"<?php echo $selected ; ?>><?php echo $result_name; ?></option>
                                        <?php
                                            }
                                        ?>
                                      </select>
                                 </div>
                                 <div class="g_cell span12 g_mobile">
                                      <select class="g_myquizzes_select" name="selecttype" id="selecttype" onchange="document.adminForm.submit();" >
                                         <option value="0" <?php if($pt == "0"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_SELECT_TYPE");?></option>
                                         <option value="1" <?php if($pt == "1"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_QUIZ");?></option>
                                         <option value="2" <?php if($pt == "2"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_FQUIZ");?></option>
                                      </select>
                                 </div> 
                             </div>                 
                            </div>                 
                            
                            
                                            
                        </div>
                        <!-- End Search and filters-->
                    <div class="clearfix"> 
                        <div class="g_table_wrap g_hide_mobile">   
                            <table class="table table-striped">
                                <tr class="g_table_header">
                                    <th class="g_cell_1"><?php echo JText::_("GURU_DAYS_NAME"); ?></th>
                                    <th class="g_cell_2 g_hide_mobile"><?php echo JText::_("GURU_PROGRAM_PROGRAMS"); ?></th>
                                    <th class="g_cell_3 g_hide_mobile g_hide_small_size"><?php echo JText::_("GURU_TYPE"); ?></th>
                                    <th class="g_cell_4 g_hide_mobile"><?php echo JText::_("GURU_DATE_TAKEN"); ?></th>
                                    <th class="g_cell_5"><?php echo JText::_("GURU_ATTEPMT_Q"); ?></th>
                                    <th class="g_cell_6 g_hide_mobile"><?php echo JText::_("GURU_PASSING_SCORE"); ?></th>
                                    <th class="g_cell_7 "><?php echo JText::_("GURU_MYSCORE"); ?></th>
                                </tr>
                                
                                    <?php
                                        $k = 0;
                                        $hascertificate = false;
                                        $already_edited = array();
                                        $db	= JFactory::getDBO();
                                        $datetype = "SELECT datetype from #__guru_config WHERE id=1";
                                        $db->setQuery($datetype);
                                        $db->query();
                                        $datetype = $db->loadResult();
                                        
                                        $avg_quizzes_cert = "SELECT avg_cert from #__guru_certificates WHERE id=1";
                                        $db->setQuery($avg_quizzes_cert);
                                        $db->query();
                                        $avg_quizzes_cert = $db->loadResult();
                                        
                                        if(isset($ps) && $ps!=0 && $ps != NULL){
                                            $cidd = $ps;
                                        }
                                        else{
                                            $selectcoursesd = JRequest::getVar("selectcoursesd", "0");
											$and = "";
											if(intval($selectcoursesd) != 0){
												$and .= " and `pid`=".intval($selectcoursesd);
											}
											
											$sql = "SELECT pid from #__guru_quiz_taken where `user_id`=".intval($user_id).$and;
                                            $db->setQuery($sql);
											$db->query();
                                            $cid = $db->loadColumn();
                                        }
                                        
										$search = JRequest::getVar("search_course", "");
										$selectcoursesd = JRequest::getVar("selectcoursesd", "0");
										$and = "";
										if(trim($search) != ""){
											$and .= " and q.`name` like '%".addslashes(trim($search))."%'";
										}
										
										if(intval($selectcoursesd) != 0){
											$and .= " and qz.`pid`=".intval($selectcoursesd);
										}
										
                                        $sql =  "SELECT q.id, q.name, q.time_quiz_taken, q.is_final, qz.pid FROM  #__guru_quiz_taken qz INNER JOIN  #__guru_quiz q ON (qz.quiz_id = q.id) WHERE user_id = ".intval($user_id)." ".$and;
										
                                        if(isset($ps) && $ps != 0 && $ps != NULL ){
                                            $sql .= " and qz.pid=".$ps;
                                        }
                                        if(isset($pt) && $pt != 0 && $pt != NULL ){
                                            if($pt == 1){
                                                $sql .= " and q.is_final=0";
                                            }
                                            elseif($pt == 2){
                                                $sql .= " and q.is_final=1";
                                            }
                                        }
										 if(isset($psd) && $psd != 0 && $psd != NULL ){
                                            $sql .= " and qz.pid=".$psd;
                                        }
                                        if(isset($ptd) && $ptd != 0 && $ptd != NULL ){
                                            if($ptd == 1){
                                                $sql .= " and q.is_final=0";
                                            }
                                            elseif($ptd == 2){
                                                $sql .= " and q.is_final=1";
                                            }
                                        }
										
										$sql .= " group by q.id";
										
                                        $db->setQuery($sql);
                                        $db->query();
                                        $my_quizzes = $db->loadAssocList();	
                                        $total_rows = 0;
										
										$limit_request = JRequest::getVar("limit", "-1");
										if($limit_request == -1){
											$config = new JConfig();
											if(isset($_SESSION["quiz_limit"])){
												$limit = $_SESSION["quiz_limit"];
											}
											else{
												$limit = $config->list_limit;
												$_SESSION["quiz_limit"] = $limit;
											}
										}
										else{
											$_SESSION["quiz_limit"] = $limit_request;
											$limit = $limit_request;
										}
										
										$limitstart = JRequest::getVar("limitstart", 0);
										$row = 0;
										
                                        foreach($my_quizzes as $key=>$value){
                                            $class = "odd";
                                            if($k%2 != 0){
                                                $class = "even";
                                            }
											
                                            $id = $my_quizzes[$key]["id"];
											$scores_avg_quizzes = $guruModelguruTask->getAvgScoresQ($user_id,@$cid[0]);
											
											$val = $my_quizzes[$key]["pid"];
                        
                                            $sql = "SELECT id_final_exam FROM #__guru_program WHERE id=".intval($val);
                                            $db->setQuery($sql);
                                            $result = $db->loadResult();
                                            
                                            $sql = "SELECT hasquiz from #__guru_program WHERE id=".intval($val);
                                            $db->setQuery($sql);
                                            $resulthasq = $db->loadResult();
                                            
                                            $sqlm = "SELECT max_score FROM #__guru_quiz WHERE id=".intval($id);
                                            $db->setQuery($sqlm);
                                            $result_maxs = $db->loadResult();

                                            $sql = "SELECT `name`, `published` from #__guru_program WHERE id =".intval($val);
                                            $db->setQuery($sql);
											$db->query();
                                            $result = $db->loadAssocList();
											$coursename = $result["0"]["name"];
											$published = $result["0"]["published"];
                                            
                                            if($my_quizzes[$key]["is_final"] == 1){
                                                $type =  JText::_("GURU_FQUIZ");
                                            }
                                            else{
                                                $type = JText::_("GURU_QUIZ");
                                            }
                                            
                                            $sql = "SELECT time_quiz_taken FROM #__guru_quiz WHERE id=".intval($id);
                                            $db->setQuery($sql);
                                            $time_quiz_taken = $db->loadResult();
                                            
                                            $sql = "SELECT * from #__guru_quiz_taken where user_id=".intval($user_id)." and quiz_id=".intval($id)." and pid=".intval($val)." order by `date_taken_quiz` desc";
											
                                            $db->setQuery($sql);
                                            $quiz_taken_rows = $db->loadAssocList();
											
											if($my_quizzes[$key]["is_final"] == 0){
												$my_quizzes[$key]["is_final"] = "1";
											}
											elseif($my_quizzes[$key]["is_final"] == 1){
												$my_quizzes[$key]["is_final"] = "2";
											}
											
											if(count($quiz_taken_rows) > 0){
												foreach($quiz_taken_rows as $key_row=>$value_row){
													$selecttyped = JRequest::getVar("selecttyped", "0");
													
													if($selecttyped != 0 && $selecttyped != $my_quizzes[$key]["is_final"]){
														continue;
													}
													
													$date_taken =  date($datetype, strtotime($value_row["date_taken_quiz"]));
													
													$sql = "SELECT id, score_quiz, time_quiz_taken_per_user, date_taken_quiz FROM #__guru_quiz_taken WHERE `id`=".intval($value_row["id"]);
                                            		$db->setQuery($sql);
		                                            $result_q = $db->loadObject();
                                            		$first = explode("|", @$result_q->score_quiz);
                                            		
													$res = 0;
													if(intval($first["1"]) != 0){
														$res = intval(@($first["0"] / $first["1"])*100);
													}
											
													if($res >= $result_maxs){
														$selectstatus = JRequest::getVar("selectstatus", "0");
														if($selectstatus != 0 && $selectstatus == 2){
															continue;
														}
														
														$passfail = '<span  style="color:#66CC00;">'.JText::_("GURU_QUIZ_PASSED").'</span>'; 
														$pcolor='color:#66CC00;';
													}
													else{
														if(@$selectstatus != 0 && @$selectstatus == 1){
															continue;
														}
													
														$passfail = '<span  style="color:#FF0000;">'.JText::_("GURU_QUIZ_FAILED").'</span>'; 
														$pcolor='color:#FF0000;';
													}
													
													if( ($row >= $limitstart && $row < $limitstart + $limit) || $limit == 0){
                                            ?>
                                                    <tr>
                                                        <td class="g_cell_1"><?php echo $my_quizzes[$key]["name"];?></td>
                                                        <td class="g_cell_2 g_hide_mobile"> 
                                                        <?php
															if($published == 0){
                                                                echo $coursename;
                                                            }
                                                            else{
                                                        ?>
                                                                <a href="<?php echo JRoute::_("index.php?option=com_guru&view=guruPrograms&task=view&cid=".$cid[$key]."-".@$alias."&Itemid=".$Itemid); ?>">
                                                                    <?php echo $coursename; ?>
                                                                </a>
                                                        <?php
                                                            }
                                                            
                                                            if($time_quiz_taken == 11){
                                                                $time_quiz_taken = "Unlimited";
                                                            }
                                                        ?>
                                                        </td>
                                                        <td class="g_cell_3 g_hide_mobile g_hide_small_size"><?php echo $type; ?></td>
                                                        <td class="g_cell_4 g_hide_mobile"><?php echo $date_taken; ?></td>
                                                        <td class="g_cell_5"><?php echo $value_row["time_quiz_taken_per_user"]."/".$time_quiz_taken; ?></td>
                                                        <td class="g_cell_6 g_hide_mobile"><?php echo $result_maxs."%"; ?></td>
                                                        <td class="g_cell_7 "><?php echo $res."%".$passfail;?></td>
                                                    </tr>
                                    <?php
													}
													$total_rows ++;
													$row ++;
												}
											}
                                            $k++;
                                        }
                                    ?>
                            </table>
                            <table>
                            	<tr>
                                	<td>
                                    	<?php
											$config = new JConfig();
											
                                        	$pagination = new JPagination(NULL, 0, 5);
											//echo $pagination->getLimitBox();

											$limit = $_SESSION["quiz_limit"];
											$limitstart = JRequest::getVar("limitstart", 0);
											$total = $total_rows;
											$pagesStart = 0;
											$pagesStop = 0;
											$pagesCurrent = 0; 
											$pagesTotal = 0;
											
											if($total > $limitstart){
												$pagesTotal = @ceil($total / $limit);
												if($pagesTotal <= 10){
													$pagesStart = 1;
													$pagesStop = $pagesTotal;
													$pagesCurrent = @($limitstart / $limit) + 1;
												}
												else{
													$pagesCurrent = ($limitstart / $limit) + 1;
													if($pagesCurrent - 5 > 1){
														$pagesStart = $pagesCurrent - 5;
														if($pagesCurrent + 4 >= $pagesTotal){
															$pagesStop = $pagesTotal;
														}
														else{
															$pagesStop = $pagesCurrent + 4;
														}
													}
													else{
														$pagesStart = 1;
														if($pagesTotal <= 10){
															$pagesStop = $pagesTotal;
														}
														else{
															$pagesStop = 10;
														}
													}
												}
											}
											
											$pagination->set("limitstart", $limitstart);
											$pagination->set("limit", $limit);
											$pagination->set("total", $total);
											$pagination->set("pagesStart", $pagesStart);
											$pagination->set("pagesStop", $pagesStop);
											$pagination->set("pagesCurrent", $pagesCurrent);
											$pagination->set("pagesTotal", $pagesTotal);
											
											/*echo "<pre>";
											print_r($pagination);
											echo "</pre>";*/
											
											echo $pagination->getListFooter();
										?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                         <div class="g_table_wrap g_mobile">   
                            <table class="table table-striped">
                                <tr class="g_table_header">
                                    <th class="g_cell_1"><?php echo JText::_("GURU_DAYS_NAME"); ?></th>
                                    <th class="g_cell_2"><?php echo JText::_("GURU_ATTEPMT_Q"); ?></th>
                                    <th class="g_cell_3 "><?php echo JText::_("GURU_MYSCORE"); ?></th>
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
                                        
                                        $avg_quizzes_cert = "SELECT avg_cert from #__guru_certificates WHERE id=1";
                                        $db->setQuery($avg_quizzes_cert);
                                        $db->query();
                                        $avg_quizzes_cert = $db->loadResult();
                                        
                                        if(isset($ps) && $ps!=0 && $ps != NULL){
                                            $cidd = $ps;
                                        }
                                        else{
                        
                                            $cid = "SELECT pid from #__guru_quiz_taken where user_id=".intval($user_id);
                                            $db->setQuery($cid);
                                            $cid = $db->loadColumn();
                                        
                                        }
                                        
                                        $sql =  "SELECT q.id, q.name, q.time_quiz_taken, q.is_final, qz.pid FROM  #__guru_quiz_taken qz INNER JOIN  #__guru_quiz q ON (qz.quiz_id = q.id) WHERE user_id = ".intval($user_id);
                                        
                                        if(isset($ps) && $ps != 0 && $ps != NULL ){
                                            $sql .= " and qz.pid=".$ps;
                                        }
                                        if(isset($pt) && $pt != 0 && $pt != NULL ){
                                            if($pt == 1){
                                                $sql .= " and q.is_final=0";
                                            }
                                            elseif($pt == 2){
                                                $sql .= " and q.is_final=1";
                                            }
                                        }
                                        $db->setQuery($sql);
                                        $db->query();
                                        $my_quizzes = $db->loadAssocList();	
                                        
                                        $t = 1;
                                        foreach($my_quizzes as $key=>$value){
                                            $class = "odd";
                                            if($k%2 != 0){
                                                $class = "even";
                                            }
                        
                                            $id = $my_quizzes[$key]["id"];
                        
                                            
                                        $scores_avg_quizzes =  $guruModelguruTask->getAvgScoresQ($user_id,$cid[0]);
										
                                        if($ps!= 0 &&  $ps != NULL && $ps!=""){
                                                $val = $cidd;
                                        }
                                        else{
                                            $val = $cid[$key];
                                        }
                                        if($pt!= 0 &&  $pt != NULL){
                                            $val = $my_quizzes[$key]["pid"];
                                        }
                        
                                            $sql = "SELECT id_final_exam FROM #__guru_program WHERE id=".intval($val);
                                            $db->setQuery($sql);
                                            $result = $db->loadResult();
                                            
                                            $sql = "SELECT hasquiz from #__guru_program WHERE id=".intval($val);
                                            $db->setQuery($sql);
                                            $resulthasq = $db->loadResult();
                                            
                                            $sqlm = "SELECT max_score FROM #__guru_quiz WHERE id=".intval($id);
                                            $db->setQuery($sqlm);
                                            $result_maxs = $db->loadResult();
                                        
                                
                                            $sql = "SELECT id, score_quiz, time_quiz_taken_per_user, date_taken_quiz  FROM #__guru_quiz_taken WHERE user_id=".intval($user_id)." and quiz_id=".intval($id)." and pid=".intval($val)." ORDER BY id DESC LIMIT 0,1";
                                            $db->setQuery($sql);
                                            $result_q = $db->loadObject();
                                            
                                            $first= explode("|", @$result_q->score_quiz);
                                            
                                            @$res = intval(($first[0]/$first[1])*100);
                                            
                                            $coursename = "SELECT name from #__guru_program WHERE id =".intval($val)."
											
                        ";					
                                            $db->setQuery($coursename);
                                            $coursename = $db->loadResult();
                                            
                                            if($my_quizzes[$key]["is_final"] == 1){
                                                $type =  JText::_("GURU_FQUIZ");
                                            }
                                            else{
                                                $type = JText::_("GURU_QUIZ");
                                            }
                                            $date_taken =  date($datetype, strtotime($result_q->date_taken_quiz));
                                            
                                            
                                            $sql = "SELECT  time_quiz_taken FROM #__guru_quiz WHERE id=".intval($id);
                                            $db->setQuery($sql);
                                            $time_quiz_taken = $db->loadResult();
                                            
                                            $sql = "SELECT count(id) from #__guru_quiz_taken where user_id=".intval($user_id)." and quiz_id=".intval($id)." and pid=".intval($val);
                                            $db->setQuery($sql);
                                            $counttimeq = $db->loadResult();
                                            
                                            if($res >= $result_maxs){
                                                $passfail = '<span  style="color:#66CC00;">'.JText::_("GURU_QUIZ_PASSED").'</span>'; 
                                                $pcolor='#66CC00;';
                                            }
                                            else{
                                                $passfail = '<span  style="color:#FF0000;">'.JText::_("GURU_QUIZ_FAILED").'</span>'; 
                                                $pcolor='#FF0000;';
                        
                                            }
                                            
                                            if(isset($pc) && $pc != 0 && $pc != NULL ){
                                                if($pc == 1){
                                                    if($res < $result_maxs){
                                                        continue;
                                                    }
                                                }
                                                elseif($pc == 2){
                                                    if($res >= $result_maxs){
                                                        continue;
                                                    }
                                                }
                                        }
                                            
                                            if(!in_array($id, $already_edited)){
                                                $already_edited[] = $id;
                                            ?>
                                                    <td class="g_cell_1"><?php echo $my_quizzes[$key]["name"];?></td>
                                                     <td class="g_cell_2"><?php echo $t."/".$time_quiz_taken; ?></td>
                                                     <td class="g_cell_3 " style="color:<?php echo $pcolor;?>"><?php echo $res."%";?></td>
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
                    <input type="hidden" name="view" value="guruOrders" />
                    <input type="hidden" name="task" value="myquizandfexam" />
                </form>
              </div>
            </div>
          </div>
        </div>   

		<script type="text/javascript" language="javascript">
			window.onload=function(){
				document.getElementById("limit").value = <?php echo intval($limit); ?>;
			};
		</script>