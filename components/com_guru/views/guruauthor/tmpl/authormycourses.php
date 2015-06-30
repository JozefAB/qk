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
$my_courses = $this->mycoursesth;
$config = $this->config;
$allow_teacher_action = json_decode($config->st_authorpage);//take all the allowed action from administator settings
@$teacher_add_courses = $allow_teacher_action->teacher_add_courses; //allow or not action Add courses
@$teacher_edit_courses = $allow_teacher_action->teacher_edit_courses; //allow or not action Add courses
$Itemid = JRequest::getVar("Itemid", "0");
$doc = JFactory::getDocument();
$doc->setTitle(trim(JText::_('GURU_AUTHOR'))." ".trim(JText::_('GURU_AUTHOR_MY_COURSE')));

?>
<script language="javascript" type="application/javascript">
	function deleteAuthorCourse(){
		if (document.adminForm['boxchecked'].value == 0) {
			alert( "<?php echo JText::_("GURU_MAKE_SELECTION_FIRT");?>" );
		} 
		else{
			if(confirm("<?php echo JText::_("GURU_REMOVE_AUTHOR_COURSES"); ?>")){
				document.adminForm.task.value='removeCourse';
				document.adminForm.submit();
			}
		}	
	}
	
	function newAuthorCourse(){
		document.adminForm.task.value='addCourse';
		document.adminForm.submit();	
	}
	
	function duplicateCourse(){
		if (document.adminForm['boxchecked'].value == 0) {
			alert( "<?php echo JText::_("GURU_MAKE_SELECTION_FIRT");?>" );
		} 
		else{
			document.adminForm.task.value='duplicateCourse';
			document.adminForm.submit();
		}	
	}
	
	function unpublishCourse(){
		if (document.adminForm['boxchecked'].value == 0) {
			alert( "<?php echo JText::_("GURU_MAKE_SELECTION_FIRT");?>" );
		} 
		else{
			document.adminForm.task.value='unpublishCourse';
			document.adminForm.submit();
		}	
	}
	
	function publishCourse(){
		if (document.adminForm['boxchecked'].value == 0) {
			alert( "<?php echo JText::_("GURU_MAKE_SELECTION_FIRT");?>" );
		} 
		else{
			document.adminForm.task.value='publishCourse';
			document.adminForm.submit();
		}	
	}
</script>	
<div class="g_row clearfix">
	<div class="g_cell span12">
		<div>
			<div>
                <div id="g_mycoursesauthor" class="clearfix com-cont-wrap">
                    <?php 	echo $div_menu; //MENU TOP OF AUTHORS?>
                    <!--BUTTONS -->
                    <div class="g_inline_child  clearfix">
                       <?php if($teacher_add_courses == 0){?>
                <!--ADDED BY JOSEPH 31/03/2015-->
								<input type="button" class="btn btn-success" value="<?php echo JText::_('GURU_NEW'); ?>" onclick="newAuthorCourse();"/>
                <!--END-->
                                <input type="button" class="btn btn-warning" value="<?php echo JText::_('GURU_DUPLICATE'); ?>" onclick="duplicateCourse();"/>
                       <?php }?> 
                         <input type="button" class="btn btn-inverse" value="<?php echo JText::_('GURU_UNPUBLISH'); ?>" onclick="unpublishCourse();"/>
                         <input type="button" class="btn btn-primary" value="<?php echo JText::_('GURU_PUBLISH'); ?>" onclick="publishCourse();"/>
                         <input type="button" class="btn btn-danger" value="<?php echo JText::_('GURU_DELETE'); ?>" onclick="deleteAuthorCourse();"/>
                    </div> 
                    <!-- -->
                    
                    <div class="profile_page page_title">
                        <h2><?php echo JText::_('GURU_AUTHOR_MY_COURSE');?></h2>
                    </div>
                    <div id="g_mycoursesauthorcontent" class="g_sect clearfix">
                        <form  action="index.php" class="form-horizontal" id="adminForm" method="post" name="adminForm" enctype="multipart/form-data">
                        	<!-- Start Search -->
                            <div class="clearfix g_margin_bottom">
                                <div class="filter-search btn-group pull-left">
                                 <div class="input-group g_search">
                                      <input type="text" class="form-control inputbox" name="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" >
                                      <span class="input-group-btn g_hide_mobile">
                                        <button class="btn btn-primary" type="submit"><?php echo JText::_("GURU_SEARCH"); ?></button>
                                      </span>
                                  </div><!-- /input-group -->
                                </div>
                            </div>
                            <!-- End Search -->
                        	 <div class="clearfix">
                                <div class="g_table_wrap">
                                    <table id="g_authorcourse" class="table table-striped">
                                        <tr class="g_table_header">
                                            <th class="g_cell_1"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);" /></th>
                                            <th class="g_cell_2"><?php echo JText::_('GURU_TI_VIEW_COURSE'); ?></th>
                                            <th class="g_cell_3"><?php echo JText::_('GURU_COURSE_CURRICULUM'); ?></th>
                                            <th class="g_cell_4"><?php echo JText::_("GURU_COURSE_DETAILS"); ?></th>
                                            <th class="g_cell_5"><?php echo JText::_("GURU_STATS"); ?></th>
                                            <th class="g_cell_6">
                                            	<span class="g_hide_mobile"><?php echo JText::_("GURU_PUBL"); ?></span>
                                                <span class="g_mobile">&nbsp;&nbsp;&nbsp;&nbsp;</span>
											</th>
                                            <th class="g_cell_3 g_hide_mobile">
												<span><?php echo JText::_("GURU_STATUS"); ?></span>
											</th>
                                        </tr>
                        
                        	
										<?php 
                                        $n =  count($my_courses);
                                        for ($i = 0; $i < $n; $i++):
                                            $id = $my_courses[$i]->id;
                                            $checked = JHTML::_('grid.id', $i, $id);
                                            //$published = JHTML::_('grid.published', $my_courses[$i], $i );
                                            $alias = isset($my_courses[$i]->alias) ? trim($my_courses[$i]->alias) : JFilterOutput::stringURLSafe($my_courses[$i]->course_name);
                                        ?>
                                        	<tr class="guru_row">	
                                                <td class="g_cell_1"><?php echo $checked;?></td>
                                                <td class="g_cell_2"><a href="<?php echo JRoute::_("index.php?option=com_guru&view=guruPrograms&task=view&cid=".$id."-".$alias."&Itemid=".$Itemid); ?>"><i class="fa fa-eye"></i></a></td>
                                            	<td class="guru_product_name g_cell_3"><a href="index.php?option=com_guru&view=guruauthor&task=treeCourse&pid=<?php echo intval($my_courses[$i]->id); ?>"><?php echo $my_courses[$i]->name; ?></a></td>
                                                <td class="g_cell_4">
                                                    <?php if($teacher_edit_courses == 0){
													?>        
                                                    <a href="index.php?option=com_guru&view=guruauthor&task=addCourse&id=<?php echo intval($my_courses[$i]->id); ?>"><i class="fa fa-pencil-square-o"></i></a>
                                                    <?php 
													}
													else{
														echo JText::_("GURU_INFO");
													}
													?>
                                                </td>
                                                <td class="g_cell_5">          
                                                	<a href="index.php?option=com_guru&view=guruauthor&task=course_stats&id=<?php echo intval($my_courses[$i]->id); ?>"><i class="fa fa-list"></i></a>
                                                </td>
                                                <td class="g_cell_6">       
													<?php
                                                        if($my_courses[$i]->published == 0){
                                                            echo '<a title="Publish Item" onclick="return listItemTask(\'cb'.$i.'\', \'publishCourse\')" href="#">
                                                                    <img alt="Unpublished" src="components/com_guru/images/icons/publish_x.png">
                                                                  </a>';
                                                        }
                                                        else{
                                                            echo '<a title="Unpublish Item" onclick="return listItemTask(\'cb'.$i.'\',\'unpublishCourse\')" href="#">
                                                                    <img alt="Published" src="components/com_guru/images/icons/tick.png">
                                                                  </a>';
                                                        }
                                                    ?>
                                              </td>
                                              <td class="g_cell_3 g_hide_mobile">
													<?php
                                                    	if($my_courses[$i]->status == "0"){
															echo '<div class="pending">'.JText::_("GURU_PENDING").'</div>';
														}
														elseif($my_courses[$i]->status == "1"){
															echo '<div class="approved">'.JText::_("GURU_APPROVED").'</div>';
														}
													?>
                                              </td>
                                           </tr>             
                                        <?php
                                            endfor;
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
                       		
                            <input type="hidden" name="task" value="<?php echo JRequest::getVar("task", "authormycourses"); ?>" />
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