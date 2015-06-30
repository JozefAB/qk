<?php

/*------------------------------------------------------------------------
# com_guru
# ------------------------------------------------------------------------
# author    iJoomla
# copyright Copyright (C) 2013 ijoomla.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.ijoomla.com
# Technical Support:  Forum - http://www.ijoomla.com/forum/index/
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_('behavior.tooltip');
$db = JFactory::getDBO();
$div_menu = $this->authorGuruMenuBar();
$students = $this->students;
$doc = JFactory::getDocument();
$doc->setTitle(trim(JText::_('GURU_AUTHOR'))." ".trim(JText::_('GURU_AUTHOR_MY_STUDENTS')));
$isteacher = $this->isTeacherOrNot();
$config = $this->config;
$allow_teacher_action = json_decode($config->st_authorpage);//take all the allowed action from administator settings
$teacher_add_students = @$allow_teacher_action->teacher_add_students; //allow or not action Add students
@$from = JRequest::getVar("from", "");

$doc->addScript('components/com_guru/js/guru_modal.js');

?>
<div class="g_row clearfix">
	<div class="g_cell span12">
		<div>
			<div>
                <div id="g_mystudentslist" class="clearfix com-cont-wrap">
                    <?php 	echo $div_menu; //MENU TOP OF AUTHORS?>
                    <!--BUTTONS -->
                    <?php if($teacher_add_students == 0){
					?>
                    <!--<div class="g_inline_child clearfix">
                         <input type="button" class="btn btn-success" value="<?php //echo JText::_('GURU_NEW'); ?>" onclick="document.adminForm.task.value='newStudent'; document.adminForm.submit();"/>
                    </div> -->
                    <!-- -->
                    <?php 
					}
					?>
                    <div class="profile_page page_title">
                        <h2><?php echo JText::_('GURU_AUTHOR_MY_STUDENTS');?></h2>
                    </div>
                    <?php 
					if($isteacher >0){
					?>
                        <div id="g_mystudentsauthor" class="g_sect clearfix">
                            <form class="form-horizontal" id="adminForm" method="post" name="adminForm" enctype="multipart/form-data" action="index.php">
                                
                                <!-- Start Search -->
                                <div class="clearfix">
                                    <div class="g_cell">
                                     <div class="input-group g_search">
                                          <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
                                          <span class="input-group-btn g_hide_mobile">
                                            <button class="btn btn-primary" type="submit"><?php echo JText::_("GURU_SEARCH"); ?></button>
                                          </span>
                                          
											<?php
												$filter_course = $this->escape($this->state->get('filter.course'));
												$my_courses = $this->getMyCourses();
											?>
										  	<select name="filter_course" id="filter_course" onchange="document.adminForm.submit();">
                                            	<option value="0"> <?php echo JText::_("GURU_SELECT_COURSE"); ?> </option>
                                                <?php
                                                	if(isset($my_courses) && count($my_courses) > 0){
														foreach($my_courses as $key=>$value){
															if($value["published"] == 0){
																continue;
															}
															$selected = "";
															if($value["id"] == $filter_course){
																$selected = 'selected="selected"';
															}
															echo '<option value="'.$value["id"].'" '.$selected.'>'.$value["name"].'</option>';
														}
													}
												?>
										  	</select>
                                          
                                      </div><!-- /input-group -->
                                    </div>
                                </div>
                                <!-- End Search -->
                                <div class="clearfix">
                                    <div class="g_table_wrap g_margin_top">
                                        <table id="g_authorstudent" class="table table-striped">
                                            <tr class="g_table_header">
                                                <th></th>
                                                <th class="g_cell_3"><?php echo JText::_('GURU_FULL_NAME'); ?></th>
                                                <th class="g_cell_4"><?php echo JText::_("GURU_STATS"); ?></th>
                                                <th class="g_cell_5 g_hide_mobile"><?php echo JText::_("GURU_EMAIL"); ?></th>
                                                <th class="g_cell_6 g_hide_mobile"><?php echo JText::_("GURU_PROGRAM_PROGRAMS"); ?></th>
                                            </tr>
                                            <?php 
                                            if(isset($students) && count($students) > 0 && $students !== FALSE){
                                                $i = 0;
                                                foreach($students as $key=>$student){
                                                    $id = $student->id;
                                                    $checked = JHTML::_('grid.id', $i, $id);
													$itemid = JRequest::getVar("Itemid", "0");
                                            ?>
                                                <tr class="guru_row">
                                                    <td>
                                                    	<?php
                                                        	$grav_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($student->email)))."?d=mm&s=40";
															echo '<img src="'.$grav_url.'" alt="'.$student->name.'" title="'.$student->name.'"/>';
														?>
                                                    </td>
                                                    <td class="g_cell_3"><?php echo $student->name; ?></td>
                                                    <td class="g_cell_4">
                                                    	<?php
                                                        	$link_modal = JURI::root()."index.php?option=com_guru&view=guruauthor&layout=studentdetails&userid=".intval($id)."&tmpl=component";
															$itemid = JRequest::getVar("Itemid", "0");
                                                            $link_phone = JRoute::_("index.php?option=com_guru&view=guruauthor&layout=studentdetails&userid=".intval($id)."&Itemid=".intval($itemid));
														?>
                                                    	<a class="g_hide_mobile" onclick="javascript:openMyModal((window.screen.availWidth - 120), (window.screen.availHeight - 180), '<?php echo $link_modal; ?>');" href="#">
                                                        	<i class="fa fa-list"></i>
														</a>
                                                        
                                                        <a class="g_mobile" href="<?php echo $link_phone; ?>">
                                                        	<i class="fa fa-list"></i>
                                                        </a>
                                                        
													</td>
                                                    <td class="g_cell_5 g_hide_mobile"><?php echo $student->email;?></td>
                                                    <td class="g_cell_6 g_hide_mobile">
                                                        <?php
                                                            $courses = $student->courses;
                                                            $courses = explode("-", $courses);
                                                            $courses = array_unique($courses);
                                                            $sum = count($courses);
                                                        
                                                            $itemid = JRequest::getVar("Itemid", "0");
														?>
                                                        	<a class="g_hide_mobile" onclick="javascript:openMyModal((window.screen.availWidth - 120), (window.screen.availHeight - 180), '<?php echo $link_modal; ?>');" href="#">
                                                            	<?php echo intval($sum); ?>
                                                            </a>
                                                            
                                                            <a class="g_mobile" href="<?php echo $link_phone; ?>">
                                                        		<?php echo intval($sum); ?>
                                                        	</a>
                                                   </td>
                                               </tr>
                                            <?php
                                                    $i ++;
                                                }
                                            }
                                            ?>	
                                           </table>
                                    </div>
                              </div>
                               
                               <?php
                                    echo $this->pagination->getLimitBox();
                                    $pages = $this->pagination->getPagesLinks();
                                    include_once(JPATH_SITE.DS."components".DS."com_guru".DS."helpers".DS."helper.php");
                                    $helper = new guruHelper();
                                    $pages = $helper->transformPagination($pages);
                                    echo $pages;
                                ?>
                               
                                <input type="hidden" name="option" value="com_guru" />
                                <input type="hidden" name="controller" value="guruAuthor" />
                                <input type="hidden" name="view" value="guruauthor" />
                                <input type="hidden" name="task" value="mystudents" />
                                <input type="hidden" name="action" value="<?php echo JRequest::getVar("action", ""); ?>" />
                                <input type="hidden" name="qid" value="<?php echo JRequest::getVar("qid", ""); ?>" />
                                <input type="hidden" name="cid" value="<?php echo JRequest::getVar("cid", ""); ?>" />
                            </form>
                       </div> 
                  <?php
				  }
				  else{
					?>
                    <div class="g_table_row">
                        <div class="g_cell span1 g_table_cell">
                            <div>
                                <div>
                                    <?php echo JText::_("GURU_ONLY_AUTHORS");?>
                                </div>
                            </div>
                        </div>
                   </div>     
                  <?php  
				  }
				  ?>   
			</div>
           </div> 
		</div>
	</div>
 </div>            