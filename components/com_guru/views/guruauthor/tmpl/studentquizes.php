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
$div_menu = $this->authorGuruMenuBar();

$quizes = $this->quizes;
$userid = JRequest::getVar("userid", "0");
$pid = JRequest::getVar("pid", "0");
$user_name = $this->userName($userid);
$user_email = $this->userEmail($userid);
$doc = JFactory::getDocument();
$doc->setTitle(trim(JText::_('GURU_AUTHOR'))." ".trim(JText::_('GURU_COU_STUDENTS'))." ".trim(JText::_('GURU_DETAILS')));
$doc = JFactory::getDocument();
$doc->addStyleSheet('media/jui/css/bootstrap.min.css');
?>

<style type="text/css">
	div.g_row{
		margin:0px !important;
	}
</style>

<div class="g_row">
	<div class="g_cell span12">
		<div>
			<div>
                <div id="g_myquizzesstats" class="clearfix com-cont-wrap">
                    <?php 	echo $div_menu; //MENU TOP OF AUTHORS?>
                    
                    <div id="g_mycoursesauthorcontent" class="g_sect clearfix">
                        <form  action="index.php" class="form-horizontal" id="adminForm" method="post" name="adminForm" enctype="multipart/form-data">
                        	<div class="clearfix">
                                    <div class="g_table_wrap">
                                        <h4>
											<?php
												$grav_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($user_email)))."?d=mm&s=40";
												echo '<img src="'.$grav_url.'" alt="'.$user_name.'" title="'.$user_name.'"/>&nbsp;';
												echo $user_name;
											?>
										</h4>
                                        <table class="table table-striped">
                                            <tr class="g_table_header">
                                                <th class="g_cell_1"><?php echo JText::_("GURU_QUIZ_NAME"); ?></th>
                                                <th class="g_cell_2 g_hide_mobile"><?php echo JText::_("GURU_AUTHOR_ATTEP"); ?></th>
                                                <th class="g_cell_3 g_hide_mobile"><?php echo JText::_('GURU_CORRECT_ANSWERS'); ?></th>
                                                <th class="g_cell_4 g_hide_mobile"><?php echo JText::_("GURU_WRONG_ANSWERS"); ?></th>
                                                <th class="g_cell_5"><?php echo JText::_("GURU_FINAL_SCORE"); ?></th>
                                                <th class="g_cell_6"><?php echo JText::_("GURU_DETAILS"); ?></th>
                                            </tr>
                                            <?php
                                            	if(isset($quizes) && count($quizes) > 0){
													foreach($quizes as $key=>$quiz){
											?>
                                            			<tr class="guru_row">
                                                        	<td class="g_cell_1">
																<?php echo $quiz["name"]; ?>
                                                			</td>
                                                            <td class="g_cell_2 g_hide_mobile">
																<?php echo intval($quiz["timequizuser"])."/".$quiz["time_quiz_taken"]; ?>
                                                			</td>
                                                            <td class="g_cell_3 g_hide_mobile">
																<?php echo $quiz["correct"]; ?>
                                                			</td>
                                                            <td class="g_cell_4 g_hide_mobile">
																<?php echo $quiz["wrong"]; ?>
                                                			</td>
                                                            <td class="g_cell_5">
																<?php
                                                                	if(trim($quiz["final_score"]) != "" && intval($quiz["final_score"]) != 0){
																		echo $quiz["final_score"]."% / (".$quiz["max_score"]."%)";
																	}
																?>
                                                			</td>
                                                            <td class="g_cell_6">
                                                            	<?php
                                                            		if(trim($quiz["final_score"]) != "" && intval($quiz["final_score"]) != 0){
																?>
																		<input type="button" class="btn btn-success g_hide_mobile" onclick="window.location='<?php echo JURI::root()."index.php?option=com_guru&view=guruauthor&task=quizdetails&layout=quizdetails&pid=".intval($pid)."&userid=".intval($userid)."&quiz=".intval($quiz["id"]); ?>&tmpl=component'" value="<?php echo JText::_("GURU_DETAILS"); ?>" />
                                                                        
                                                                        <input type="button" class="btn btn-success g_mobile" onclick="window.location='<?php echo JRoute::_("index.php?option=com_guru&view=guruauthor&task=quizdetails&layout=quizdetails&pid=".intval($pid)."&userid=".intval($userid)."&quiz=".intval($quiz["id"])); ?>'" value="<?php echo JText::_("GURU_DETAILS"); ?>" />
                                                                <?php
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
                                       
                            <input type="hidden" name="task" value="<?php echo JRequest::getVar("task", ""); ?>" />
                            <input type="hidden" name="option" value="com_guru" />
                            <input type="hidden" name="controller" value="guruAuthor" />
                        </form>
                   </div>  
              </div>
           </div>   
		</div>
	</div>
 </div>                   