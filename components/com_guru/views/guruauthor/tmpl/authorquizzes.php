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
JHtml::_('behavior.framework');

$db = JFactory::getDBO();
$div_menu = $this->authorGuruMenuBar();
$myquizzes = $this->myquizzes;
$user = JFactory::getUser();
$user_id = $user->id;
$v = "";

$config = $this->config;
$allow_teacher_action = json_decode($config->st_authorpage);//take all the allowed action from administator settings
$teacher_add_quizzesfe = @$allow_teacher_action->teacher_add_quizzesfe; //allow or not action Add quiz
$teacher_edit_quizzesfe = @$allow_teacher_action->teacher_edit_quizzesfe; //allow or not action Edit quiz

$selectcoursesd = JRequest::getVar("selectcoursesd", "0");
$doc = JFactory::getDocument();
$doc->addScript('components/com_guru/js/jquery-dropdown.js');

$doc->setTitle(trim(JText::_('GURU_AUTHOR'))." ".trim(JText::_('GURU_AUTHOR_MY_QUIZZES')));

?>
<script language="javascript" type="application/javascript">
	function deleteQuiz(){
		if (document.adminForm['boxchecked'].value == 0) {
			alert( "<?php echo JText::_("GURU_MAKE_SELECTION_FIRT");?>" );
		} 
		else{
			if(confirm("<?php echo JText::_("GURU_REMOVE_AUTHOR_COURSES"); ?>")){
				document.adminForm.task.value='removeQuiz';
				document.adminForm.submit();
			}
		}	
	}
	function duplicateQuiz(){
		if (document.adminForm['boxchecked'].value == 0) {
			alert( "<?php echo JText::_("GURU_MAKE_SELECTION_FIRT");?>" );
		} 
		else{
			document.adminForm.task.value='duplicateQuiz';
			document.adminForm.submit();
		}
	}
	
	function unpublishQuiz(){
		if (document.adminForm['boxchecked'].value == 0) {
			alert( "<?php echo JText::_("GURU_MAKE_SELECTION_FIRT");?>" );
		} 
		else{
			document.adminForm.task.value='unpublish_quiz';
			document.adminForm.submit();
		}
	}
	
	function publishQuiz(){
		if (document.adminForm['boxchecked'].value == 0) {
			alert( "<?php echo JText::_("GURU_MAKE_SELECTION_FIRT");?>" );
		} 
		else{
			document.adminForm.task.value='publish_quiz';
			document.adminForm.submit();
		}	
	}
	
	function editOptions(){
		display = document.getElementById("button-options").style.display;
		
		if(display == "none"){
			document.getElementById("button-options").style.display = "";
			document.getElementById("new-options").value = "<?php echo JText::_('GURU_NEW'); ?> \u2227";
		}
		else{
			document.getElementById("button-options").style.display = "none";
			document.getElementById("new-options").value = "<?php echo JText::_('GURU_NEW'); ?> \u2228";
		}
	}
	
	function editOptions2(){
		display = document.getElementById("button-options2").style.display;
		
		if(display == "none"){
			document.getElementById("button-options2").style.display = "";
			document.getElementById("new-options2").value = "<?php echo JText::_('GURU_NEW'); ?> \u2227";
		}
		else{
			document.getElementById("button-options2").style.display = "none";
			document.getElementById("new-options2").value = "<?php echo JText::_('GURU_NEW'); ?> \u2228";
		}
	}
</script>
<style>
	div.g_inline_child button.btn{
		height:26px !important;
	}
</style>
<div class="g_row clearfix">
	<div class="g_cell span12">
		<div>
			<div>
                <div id="g_myquizzesauthor" class="clearfix com-cont-wrap">
                    <?php 	echo $div_menu; //MENU TOP OF AUTHORS?>
                    <!--BUTTONS -->
                    <div class="g_inline_child clearfix">
                    <?php
						if($teacher_add_quizzesfe == 0){
					?>
                    		<div class="btn-options-group pull-left">
                                <button onclick="editOptions();" id="new-options" class="btn btn-success g_toggle_button"><?php echo JText::_('GURU_NEW'); ?>&nbsp;<span class="fa fa-caret-down"></span></button>
                                <div class="button-options" id="button-options" style="display:none;">
                                    <ul>
                                        <li>
                                            <a href="#" onclick="window.parent.location.href = 'index.php?option=com_guru&controller=guruAuthor&task=editQuizFE&v=0';">
                                                <?php echo JText::_("GURU_REGULAR_QUIZ"); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" onclick="window.parent.location.href = 'index.php?option=com_guru&controller=guruAuthor&task=editQuizFE&v=1';">
                                                <?php echo JText::_("GURU_FINAL_EXAM_QUIZ"); ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
							</div>
                        
					<?php
						}
					?> 
                         <button class="btn btn-warning"  onclick="duplicateQuiz();"><?php echo JText::_('GURU_DUPLICATE'); ?></button>
                         <button class="btn btn-danger"   onclick="deleteQuiz();"><?php echo JText::_('GURU_DELETE'); ?></button>
                    </div> 
                    <!-- -->
                    
                    <div class="profile_page page_title">
                        <h2><?php echo JText::_('GURU_AUTHOR_MY_QUIZZES');?></h2>
                    </div>
                    <div id="g_myquizzesauthorcontent" class="g_sect clearfix">
                        <form class="form-horizontal" id="adminForm" method="post" name="adminForm" enctype="multipart/form-data" action="index.php" style="padding-top:10px;">
                        	<!-- Start Search -->
                            <div id="g_author_quizzes_filters" class="clearfix g_row_inner">
                                <div class="g_cell">
                                	<div>
                                    	<div>
                                    		<div>
                                                <select class="g_myquizzes_select" name="selectcoursesd" id="selectcoursesd" onchange="document.adminForm.submit();" >
                                                    <option value="0" <?php if($selectcoursesd == "0"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_SELECT_COURSE");?></option>
                                                    <?php
                                                        $my_courses = $this->getMyCourses();
                                                        if(isset($my_courses) && count($my_courses) > 0){
                                                            foreach($my_courses as $key=>$course){
                                                                $selected = "";
                                                                if($course["id"] == $selectcoursesd){
                                                                    $selected = 'selected="selected"';
                                                                }
                                                                echo '<option value="'.$course["id"].'" '.$selected.'>'.$course["name"].'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                              </div>
                                        </div>
                                   </div>       
                                </div>                              
                                
                                <div class="g_cell">
                                	<div>
                                    	<div>
                                    		<div>
                                            <select onchange="document.adminForm.submit()" name="quiz_select_type">
                                            <?php 
                                                if(isset($_SESSION['quiz_select_type'])){
                                                    $pb=$_SESSION['quiz_select_type'];
                                                }
                                                if(isset($_POST['quiz_select_type'])){
                                                    $pb=$_POST['quiz_select_type'];
                                                }
                                                if(!isset($pb)) {$pb=NULL;}
                                            ?>
                                            <option <?php if($pb=='0') { echo "selected='selected'";} ?> value="0"><?php echo JText::_("GURU_SELECT_TYPE2"); ?></option>
                                            <option <?php if($pb=='1') { echo "selected='selected'";} ?> value="1"><?php echo JText::_("GURU_QUIZZES_FILTER"); ?></option>
                                            <option <?php if($pb=='2') { echo "selected='selected'";} ?> value="2"><?php echo JText::_("GURU_FQUIZZES_FILTER"); ?></option>
                                            </select>	
                                             </div>
                                        </div>
                                   </div>       
                                </div>
                                
                                <div class="g_cell search-on-line">
                                	<div>
                                        <input type="text" class="form-control inputbox" name="search_quiz" value="<?php if(isset($_POST['search_quiz'])){echo $_POST['search_quiz'];} ?>" >
                                        <span class="input-group-btn g_hide_mobile">
                                            <button class="btn btn-primary" type="submit"><?php echo JText::_("GURU_SEARCH"); ?></button>
                                        </span>
                                     </div>
                                </div>
                                
							</div>
                              
                            <div class="clearfix">
                                    <div class="g_table_wrap g_margin_top">
                                        <table id="g_authorquiz" class="table table-striped">
                                            <tr class="g_table_header">
                                                <th class="g_cell_1"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);" /></th>
                                                <th class="g_cell_2 g_hide_mobile"><?php echo JText::_('GURU_ID'); ?></th>
                                                <th class="g_cell_3"> <?php echo JText::_('GURU_NAME'); ?></th>
                                                <th class="g_cell_4 g_hide_mobile"><?php echo JText::_('GURU_TYPE'); ?></th>
                                                <th class="g_cell_5 g_hide_mobile"><?php echo JText::_('GURU_STATS'); ?></th>
                                                <th class="g_cell_6 g_hide_mobile"><?php echo JText::_('GURU_TAB_REQUIREMENTS_COURSES'); ?></th>
                                                <th class="g_cell_7"><?php echo JText::_("GURU_PROGRAM_DETAILS_STATUS"); ?></th>
                                            </tr>
                                            <?php
                                            $n = count($myquizzes);
											if(isset($myquizzes) && count($myquizzes) > 0 && $myquizzes !== FALSE){
												for ($i = 0; $i < $n; $i++):
													$id = $myquizzes[$i]->id;
													$checked = JHTML::_('grid.id', $i, $id);
													$published = JHTML::_('grid.published', $myquizzes, $i );
													if($myquizzes[$i]->is_final == 0){
														$v = 0; 
													}
													else{
														$v = 1;
													}
                                            ?>
                                                <tr class="guru_row">
                                                    <td class="g_cell_1"><?php echo $checked;?></td>
                                                    <td class="g_cell_2 g_hide_mobile"><?php echo $id;?></td>
                                                    <td class="g_cell_3">
                                                    <?php 
                                                    if($teacher_edit_quizzesfe == 0){
													?>
                                                    	<a href="<?php echo JRoute::_("index.php?option=com_guru&view=guruauthor&task=editQuizFE&cid=".$id."&v=".$v."&e=1"); ?>"><?php echo $myquizzes[$i]->name; ?></a>
                                                    <?php
                                                    }
													else{
														echo $myquizzes[$i]->name;
													}
													?>
                                                    </td>
                                                    
                                                    <td class="g_cell_4 g_hide_mobile">
													 <?php
														 if($myquizzes[$i]->is_final == 0){
															echo JText::_('GURU_MEDIATYPEQUIZ'); 
															
														 }
														 else{
															echo JText::_('GURU_FQUIZ');
														 }
													 
													 ?>
                                                    </td>           
													<td class="g_cell_5 g_hide_mobile"><a href="index.php?option=com_guru&view=guruauthor&task=quizz_stats&id=<?php echo intval($myquizzes[$i]->id); ?>"><i class="fa fa-list"></i></a></td>			  													
                                                    <td class="g_cell_6 g_hide_mobile">
														 <?php
                                                            $itemid = JRequest::getVar("Itemid", "0");
                                                            $sql = "select `type_id` from #__guru_mediarel where `layout`='12' and `media_id`=".intval($id)." and type='scr_m'";
                                                            $db->setquery($sql);
                                                            $db->query();
                                                            $result_type_id = $db->loadColumn();
        
                                                            if(isset($result_type_id) && intval($result_type_id) != 0){
                                                            	
                                                                $sql = "select p.`id`, p.`name`, p.`alias` from #__guru_days d, #__guru_program p where d.`id` IN (SELECT type_id from #__guru_mediarel where media_id IN (".implode(",",$result_type_id).") and type='dtask') and d.`pid`=p.`id` and p.`author`=".intval($user_id);
                                                                $db->setquery($sql);
                                                                $db->query();
                                                                $result_pid = $db->loadAssocList();
																$comma = ", ";
																
																foreach($result_pid as $key=>$value){
																 	$alias = isset($value["alias"]) ? trim($value["alias"]) : JFilterOutput::stringURLSafe($value["name"]);
																	$link = JRoute::_("index.php?option=com_guru&view=guruPrograms&task=view&cid=".intval($value["id"])."-".$alias."&Itemid=".intval($itemid));
																	if(($key+1) == count($result_pid)){
																		$comma = "";
																	}
																	echo '<a href="'.$link.'">'. $value["name"]."</a>".$comma;
																}
																
                                                            }
                                                            else{
                                                                
                                                                echo "-";
                                                            }																
                                                            
                                                        ?>
                                                   </td>
                                                  <td class="g_cell_7">
														 <?php 
                                                            if($myquizzes[$i]->published == 0){
																echo '<img alt="Unpublished" src="components/com_guru/images/icons/publish_x.png">';
                                                            }
                                                            else{
                                                                echo '<img alt="Published" src="components/com_guru/images/icons/tick.png">';
                                                            }
                                                          ?>
                                                    </td>
                                                </tr>
                                            <?php 
                                                	endfor;
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
                           	
                            <input type="hidden" name="task" value="<?php echo JRequest::getVar("task", "authorquizzes"); ?>" />
                            <input type="hidden" name="option" value="com_guru" />
                            <input type="hidden" name="controller" value="guruAuthor" />
                            <input type="hidden" name="boxchecked" value="" />
                        </form>
                   </div> 
                 </div>      
         	</div>
		</div>
	</div>
 </div>               