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
JHTML::_('behavior.modal');

	$document = JFactory::getDocument();


	$document->setTitle(trim(JText::_('GURU_MYCOURSES')));
	$document->setMetaData( 'viewport', 'width=device-width, initial-scale=1.0' );

	
	require_once(JPATH_SITE.DS."components".DS."com_guru".DS."helpers".DS."generate_display.php");
	require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");
	$guruModelguruOrder = new guruModelguruOrder();
	
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
		
	$document = JFactory::getDocument();
    $document->addStyleSheet("components/com_guru/css/guru_style.css");
	$document->addScript("components/com_guru/js/programs.js");
	$db = JFactory::getDBO();
	$my_courses = $this->my_courses;
	$Itemid = JRequest::getVar("Itemid", "0");
	$search = JRequest::getVar("search_course", "");
	$config = $this->getConfigSettings();
	
	
	
	$sql = "Select datetype FROM #__guru_config where id=1 ";
	$db->setQuery($sql);
	$format_date = $db->loadResult();
	

	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');	
	if($deviceType !='phone'){
		$cname= JText::_("GURU_COURSES_DETAILS");
		$class_title = 'class="guruml20"';

	}
	else{
		$cname= JText::_("GURU_DAYS_NAME");
		$class_title = 'class="guruml0"';

	}
	
	?>
<script language="javascript">
	function showContentVideo(href){
		jQuery('#myModal .modal-body iframe').attr('src', href);
	}
	
	jQuery('#myModal').on('hide', function () {
		 jQuery('#myModal .modal-body iframe').attr('src', '');
	});
</script>
    <?php
	$return_url = base64_encode("index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=".intval(@$itemid));
	
	if($config->gurujomsocialprofilestudent == 1){
		$link = "index.php?option=com_community&view=profile&task=edit&Itemid=".$Itemid;
	}
	else{
		$link = "index.php?option=com_guru&view=guruProfile&task=edit&Itemid=".$Itemid;
	}
	if(count($my_courses) > 0){
	
?>
<div id="myModal" class="modal hide g_modal" style="display:none;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    </div>
    <div class="modal-body">
    <iframe id="g_my_course_pop" style="width:100%; height:100%; border:none;"></iframe>
    </div>
</div>
<div class="g_row">
	<div class="g_cell span12">
		<div>
			<div>
                <form action="index.php" name="adminForm" method="post">
                <!--REMOVED BY JOSEPH 31/03/2015-->
                    <!--<div id="guru_menubar" class="clearfix g_toolbar guru_menubar">
                        <ul>
                            <li id="my_account"><a href="<?php //echo $link; ?>"><i class="icon-user"></i><?php //echo JText::_("GURU_MY_ACCOUNT"); ?></a></li>
                            <li id="my_courses"><a class="g_toolbar_active" href="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php //echo $Itemid; ?>"><i class="icon-eye-open"></i><?php //echo JText::_("GURU_MYCOURSES"); ?></a></li>
                            <li id="my_orders"><a href="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php //echo $Itemid; ?>"><i class="icon-cart"></i><?php //echo JText::_("GURU_MYORDERS_MYORDERS"); ?></a></li>
                            <li id="my_quizzes"><a href="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php //echo $Itemid; ?>"><i class="icon-question-sign"></i><?php //echo JText::_("GURU_QUIZZ_FINAL_EXAM"); ?></a></li>
                            <li id="my_certificates"><a href="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php //echo $Itemid; ?>"><i class="icon-star"></i><?php //echo JText::_("GURU_MYCERTIFICATES"); ?></a></li>
                            
                            <li class="g_hide_mobile logout-btn" id="g_logout">
                                <a href="index.php?option=com_users&task=user.logout&<?php //echo JSession::getFormToken(); ?>=1&Itemid=<?php //echo $Itemid; ?>&return=<?php //echo $return_url;?>">
                                    <i class="fa fa-sign-out"></i>
                                </a>
							</li>
                            
                        </ul>
                    </div>
                    <div id="guru_menubar_mobile" class="g_mobile guru_menubar">
                        <select name="menuboostrap" class="g_select" id="menuboostrap" onchange="window.open(this.value, '_self');" >
                         <option value="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_MYORDERS_MYORDERS");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_QUIZZ_FINAL_EXAM");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_MYCERTIFICATES");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_MYCOURSES");?></option>
                         <option value="index.php?option=com_guru&view=guruBuy&Itemid=<?php //echo $Itemid; ?>"><?php //echo JText::_("GURU_CART");?></option>
                         <option value="<?php //echo JURI::root()?>index.php?option=com_users&task=user.logout&<?php //echo JSession::getFormToken(); ?>=1&Itemid=<?php //echo $Itemid; ?>"><?php //echo JText::_("GURU_LOGIN_OUT");?></option>
                          
                      </select>
                  </div>-->
                <!--END-->
                    <div id="mycourses" class="clearfix com-cont-wrap">
                        <div class="clearfix">
                            <div class="mycourses_page page_title g_cell span7">
                                <h2><?php echo JText::_('GURU_MYCOURSES');?></h2>
                            </div> 
                        <!--REMOVED BY JOSEPH-->
                            <!--<div id="g_user_action" class="g_cell span5 g_hide_mobile">
                                <a class="btn btn-success" href="index.php?option=com_guru&view=guruBuy&Itemid=<?php echo $Itemid; ?>"><img src="components/com_guru/images/cart.gif" alt="<?php echo JText::_("GURU_MY_CART"); ?>"/><u><?php echo JText::_("GURU_CART"); ?></u></a>
                          </div>-->
                        <!--END-->
                        </div>  
                        <!-- Start Search -->
                        <div class="clearfix">
                            <div class="g_cell span4">
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
                                        <th class="g_cell_1"><?php echo $cname; ?></th>
                                        <th class="g_cell_2 g_hide_mobile"><?php echo JText::_("GURU_COURSE_PROGRESS"); ?></th>
                                        <th class="g_cell_3 g_hide_mobile"><?php echo JText::_("GURU_LAST_VISIT"); ?></th>
                                        <th class="g_cell_5"><?php echo JText::_("GURU_RENEW"); ?></th>
                                    </tr>
                                    <style>
										  div.guru-content .btn_renew{
											height:25px;!important; 
										  }
									</style>
                                    <?php
                                    $k = 0;
                                    $already_edited = array();
                                    
                                    foreach($my_courses as $key=>$course){
                                        $bool_expired = false;
                                        $jnow = JFactory::getDate();
                                        $date_current = $jnow->toSQL();									
                                        $int_current_date = strtotime($date_current);
                                        $no_renew = false;
                                        $course = (object)$course;
                                        
                                        $id = $course->course_id;
                                        $alias = isset($course->alias) ? trim($course->alias) : JFilterOutput::stringURLSafe($course->course_name);
                                        
                                        if(!in_array($id, $already_edited)){
                                            $already_edited[] = $id;
                                ?>
                                        <tr class="guru_row">	
                                                <td class="guru_product_name g_cell_1"><a href="<?php echo JRoute::_("index.php?option=com_guru&view=guruPrograms&task=view&cid=".$id."-".$alias."&Itemid=".$Itemid); ?>"><?php echo $course->course_name; ?></a>
                                                <?php								
                                                    $expire = JText::_("GURU_EXPIRES");
                                                    if($course->plan_name == "Unlimited" || $course->expired_date == "0000-00-00 00:00:00"){
                                                        $date = '<span class="guru_active">'.JText::_("GURU_UNLIMITED_PLAN").'</span>';
                                                        $no_renew = true;
                                                    }
                                                    else{
                                                        $jnow = JFactory::getDate();
                                                        $date_current = $jnow->toSQL();									
                                                        $int_current_date = strtotime($date_current);
                                                        $bool_expired = false;
                                                        $date_string = "";
                                                        if($int_current_date > strtotime($course->expired_date)){ //expired
                                                            $bool_expired = true;
                                                            $expire = JText::_("GURU_EXPIRED");
                                                            $date_int = strtotime($course->expired_date);
                                                            $date_string = "";
                                                            if($config->hour_format == 24){
                                                                $date_string = JHTML::_('date', $date_int, 'Y-m-d H:M:S');
                                                            }
                                                            elseif($config->hour_format == 12){
                                                                $date_string = JHTML::_('date', $date_int, 'Y-m-d l:M:S p');
                                                            }
                                                            //---------------------------
                                                            $difference_int = get_time_difference($date_int, $int_current_date);
                                                            $difference = $difference_int["days"]." ".JText::_("GURU_REAL_DAYS");
                                                            if($difference_int["days"] == 0){
                                                                if($difference_int["hours"] == 0){
                                                                    if($difference_int["minutes"] == 0){
                                                                        $difference = "0";
                                                                    }
                                                                    else{
                                                                        $difference = $difference_int["minutes"]." ".JText::_("GURU_REAL_MINUTES")." ".JText::_("GURU_AGO");
                                                                    }
                                                                }
                                                                else{
                                                                    $difference = $difference_int["hours"]." ".JText::_("GURU_REAL_HOURS").", ".
                                                                                  $difference_int["minutes"]." ".JText::_("GURU_REAL_MINUTES")." ".JText::_("GURU_AGO");
                                                                }
                                                            }
                                                            else{
                                                                $difference = $difference_int["days"]." ".JText::_("GURU_REAL_DAYS").", ".
                                                                              $difference = $difference_int["hours"]." ".JText::_("GURU_REAL_HOURS").", ".
                                                                              $difference_int["minutes"]." ".JText::_("GURU_REAL_MINUTES")." ".JText::_("GURU_AGO");
                                                            }
                                                            $date = '<span class="guru_expired">'.$difference." (".JHTML::_('date', $date_int, 'm-d-Y').")".'</span>';
                                                            //---------------------------
                                                        }
                                                        else{
                                                            $bool_expired = false;
                                                            $expire = JText::_("GURU_EXPIRES");
                                                            $date_int = strtotime($course->expired_date);
                                                            $date_string = "";
                                                            if($config->hour_format == 24){
                                                                $date_string = JHTML::_('date', $date_int, 'Y-m-d H:M:S');
                                                            }
                                                            elseif($config->hour_format == 12){
                                                                $date_string = JHTML::_('date', $date_int, 'Y-m-d l:M:S p');
                                                            }
                                                            //---------------------------
                                                            $difference_int = get_time_difference($int_current_date, $date_int);
                                                            $difference = $difference_int["days"]." ".JText::_("GURU_REAL_DAYS");
                                                            if($difference_int["days"] == 0){
                                                                if($difference_int["hours"] == 0){
                                                                    if($difference_int["minutes"] == 0){
                                                                        $difference = "0";
                                                                    }
                                                                    else{
                                                                        $difference = JText::_("GURU_IN")." ".$difference_int["minutes"]." ".JText::_("GURU_REAL_MINUTES");
                                                                    }
                                                                }
                                                                else{
                                                                    $difference = JText::_("GURU_IN")." ".$difference_int["hours"]." ".JText::_("GURU_REAL_HOURS").", ".
                                                                                  $difference_int["minutes"]." ".JText::_("GURU_REAL_MINUTES");
                                                                }
                                                            }
                                                            else{
                                                                $difference = JText::_("GURU_IN")." ".$difference_int["days"]." ".JText::_("GURU_REAL_DAYS").", ".
                                                                              $difference = $difference_int["hours"]." ".JText::_("GURU_REAL_HOURS").", ".
                                                                              $difference_int["minutes"]." ".JText::_("GURU_REAL_MINUTES");
                                                            }
                                                            $date = '<span class="guru_active">'.$difference.'</span>';
                                                            //---------------------------
                                                        }
                                                    }
                                                    $nr_orders = $this->countCourseOrders($id);
                                                ?>						
                                                <br/><?php echo $expire; ?>:<?php echo $date; ?>
                                                <?php if ($nr_orders > 0) {
                                                ?>
                                                <br /><a href="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php echo $Itemid; ?>&course=<?php echo $id; ?>"><?php echo JText::_("GURU_VIEW_ORDERS")." (".$nr_orders.")"; ?></a>
                                                <?php
                                                        } else {}?>
                                                </td>
                                                <td class="g_cell_2 g_hide_mobile"> 
                                                    <?php
                                                        $user = JFactory::getUser();
                                                        $user_id = $user->id;
                                                        $completed_progress = $guruModelguruOrder->courseCompleted($user_id,$id);
                                                        $date_completed = $guruModelguruOrder->dateCourseCompleted($user_id, $id);
                                                        $date_completed = date("".$format_date."", strtotime($date_completed));
                                                
                                                        $style_color = "";
                                                        if($completed_progress == true){
                                                            if($deviceType !="phone"){
                                                                $var_lang = JText::_('GURU_COMPLETED');
                                                                $lesson_module_progress = $var_lang." ". "(".$date_completed.")" ;	
                                                                $style_color = 'style="color:#669900"';
                                                            }
                                                            else{
                                                                $var_lang = JText::_('GURU_COMPLETED');
                                                                $lesson_module_progress = $var_lang;
                                                            }
                                                        }
                                                        else{
                                                            $lesson_module_progress = $guruModelguruOrder->getLastViewedLessandMod($user_id, $id);	
                                                        }
                                                        
                                                        if(isset($lesson_module_progress)){
                                                             echo $lesson_module_progress; 
                                                        } 
                                                        else{
                                                            echo "";
                                                        }	
                                                        
                                                    ?>								
                                                    </td>
                                                    <?php
                                                        $date_last_visit = $guruModelguruOrder->dateLastVisit($user_id, $id);
                                                        if($date_last_visit !="0000-00-00" && $date_last_visit !=NULL ){
                                                            $date_last_visit = date("".$format_date."", strtotime($date_last_visit));
                                                        }
                                                        else{
                                                            $date_last_visit = "";
                                                        }
                                                        $count_quizz_taken = $guruModelguruOrder->countQuizzTakenF($user_id, $id);
                                                        
                                                    ?>
                                                    <td class="g_cell_3 g_hide_mobile"><?php echo $date_last_visit;  ?></td>
                                                    <td class="g_cell_5">
                                                        <?php
                                                            if($bool_expired == 0){ // not expired
                                                                $date_int = strtotime($course->expired_date);
                                                                $difference_int = get_time_difference($int_current_date, $date_int);		
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
                                                            
                                                                $comfirm_text = JText::_("GURU_STILL_HAVE")." ".$difference." ".JText::_("GURU_AVAILABLE_COURSE")." ".JText::_("GURU_YES")." ".JText::_("GURU_ADD_TIME")." ".JText::_("GURU_CANCEL")." ".JText::_("GURU_GO_TO_COURSE_PAGE");
                                                                if(!$no_renew){
                                                        ?>
                                                                <input type="button" class=" btn btn-warning btn_renew" onclick="javascript:renewCourse(&quot;<?php echo $comfirm_text; ?>&quot;, <?php echo $id; ?> , &quot;<?php echo JURI::root(); ?>&quot;);" value="<?php echo JText::_("GURU_RENEW"); ?>">
                                                        <?php
                                                                }
                                                            }
                                                            else{// expired
                                                                if(!$no_renew){
                                                        ?>
                                                                <input type="button" class="btn btn-warning btn_renew" onclick="document.adminForm.task.value='renew'; document.adminForm.order_id.value=<?php echo $course->order_id; ?>; document.adminForm.course_id.value=<?php echo $id; ?>;  document.adminForm.submit();" value="<?php echo JText::_("GURU_RENEW"); ?>">
                                                        <?php
                                                                }
                                                            }
                                                        ?>		
                                                    </td>
                                                </tr>
                                            
                                <?php
                                        }
                                    }
                                ?>
                        </table>
                    </div>        
                </div>
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
    else{
?>	
	<div class="g_row">
		<div class="g_cell span12">
            <div>
                <div>
                    <form action="index.php" name="adminForm" method="post">
                        <div id="guru_menubar" class="clearfix g_toolbar guru_menubar">
                            <ul>
                                <li id="my_account"><a href="<?php echo $link; ?>"><i class="icon-user"></i><?php echo JText::_("GURU_MY_ACCOUNT"); ?></a></li>
                                <li id="my_courses"><a class="g_toolbar_active" href="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php echo $Itemid; ?>"><i class="icon-eye-open"></i><?php echo JText::_("GURU_MYCOURSES"); ?></a></li>
                                <li id="my_orders"><a href="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php echo $Itemid; ?>"><i class="icon-cart"></i><?php echo JText::_("GURU_MYORDERS_MYORDERS"); ?></a></li>
                                <li id="my_quizzes"><a href="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php echo $Itemid; ?>"><i class="icon-question-sign"></i><?php echo JText::_("GURU_QUIZZ_FINAL_EXAM"); ?></a></li>
                                <li id="my_certificates"><a href="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php echo $Itemid; ?>"><i class="icon-star"></i><?php echo JText::_("GURU_MYCERTIFICATES"); ?></a></li>
                                 <li class="g_hide_mobile logout-btn" id="g_logout">
                                    <a href="index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken(); ?>=1&Itemid=<?php echo $Itemid; ?>&return=<?php echo $return_url;?>">
                                        <i class="fa fa-sign-out"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div id="guru_menubar_mobile" class="g_mobile guru_menubar">
                            <select name="menuboostrap" class="g_select" id="menuboostrap" onchange="window.open(this.value, '_self');" >
                             <option value="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_MYORDERS_MYORDERS");?></option>
                             <option value="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_QUIZZ_FINAL_EXAM");?></option>
                             <option value="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_MYCERTIFICATES");?></option>
                             <option value="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_MYCOURSES");?></option>
                             <option value="index.php?option=com_guru&view=guruBuy&Itemid=<?php echo $Itemid; ?>"><?php echo JText::_("GURU_CART");?></option>
                             <option value="index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken(); ?>=1&Itemid=<?php echo $Itemid; ?>"><?php echo JText::_("GURU_LOGIN_OUT");?></option>
                              
                          </select>
                      </div>
                        <div id="mycourses" class="clearfix com-cont-wrap">
                            <div class="clearfix">
                                <div class="mycourses_page page_title g_cell span7">
                                    <h2><?php echo JText::_('GURU_MYCOURSES');?></h2>
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
                <?php echo JText::_("GURU_NO_COURSES"); ?>, <a href="<?php echo JRoute::_("index.php?option=com_guru&view=gurupcategs"); ?>"><?php echo JText::_("GURU_SEE_A_LIST"); ?></a>
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
