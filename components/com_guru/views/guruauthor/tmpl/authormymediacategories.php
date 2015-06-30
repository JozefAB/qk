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
$my_media_cat = $this->mymediacat;
$config = $this->config;
$search = JRequest::getVar("filter_search", "");
$state = JRequest::getVar("filter_state", "-1");
$doc = JFactory::getDocument();
$doc->setTitle(trim(JText::_('GURU_AUTHOR'))." ".trim(JText::_('GURU_AUTHOR_MY_MEDIA_CAT')));
?>
<script language="javascript" type="application/javascript">
	function deleteAuthorMediaCat(){
		if(confirm("<?php echo JText::_("GURU_REMOVE_AUTHOR_COURSES"); ?>")){
			document.adminForm.task.value='removeMediaCat';
			document.adminForm.submit();
		}
	}
	function newAuthorMediaCategory(){
		document.adminForm.task.value='authoraddeditmediacat';
		document.adminForm.submit();	
	}
	function duplicateMediaCat(){
		document.adminForm.task.value='duplicateMediaCat';
		document.adminForm.submit();	
	}
	function newAuthorMedia(){
		document.adminForm.task.value='editMedia';
		document.adminForm.submit();
	}
	function unpublishMediaCat(){
		document.adminForm.task.value='unpublishMediaCat';
		document.adminForm.submit();
	}
	function publishMediaCat(){
		document.adminForm.task.value='publishMediaCat';
		document.adminForm.submit();
	}
</script>	
<div class="g_row clearfix">
	<div class="g_cell span12">
		<div>
			<div>
                <div id="g_mycoursesauthor" class="clearfix com-cont-wrap">
                    <?php 	echo $div_menu; //MENU TOP OF AUTHORS?>
                    <!--BUTTONS -->
                    <div class="g_inline_child clearfix">
                        <input type="button" class="btn btn-success" value="<?php echo JText::_('GURU_DAY_NEW_MEDIA'); ?>" onclick="newAuthorMedia();"/>
                        <input type="button" class="btn btn-success" value="<?php echo JText::_('GURU_NEWCATEGORY'); ?>" onclick="newAuthorMediaCategory();"/>
                        <input type="button" class="btn btn-warning" value="<?php echo JText::_('GURU_DUPLICATE'); ?>" onclick="duplicateMediaCat();"/>
                        <input type="button" class="btn btn-inverse" value="<?php echo JText::_('GURU_UNPUBLISH'); ?>" onclick="unpublishMediaCat();"/>
                        <input type="button" class="btn btn-primary" value="<?php echo JText::_('GURU_PUBLISH'); ?>" onclick="publishMediaCat();"/>
                        <input type="button" class="btn btn-danger" value="<?php echo JText::_('GURU_DELETE'); ?>" onclick="deleteAuthorMediaCat();"/>
                    </div> 
                    <!-- -->
                    
                    <div class="profile_page page_title">
                        <h2><?php echo JText::_('GURU_AUTHOR_MY_MEDIA_CAT');?></h2>
                    </div>
                    <div id="g_mymediacatauthorcontent" class="g_sect clearfix">
                        <form  action="index.php" class="form-horizontal" id="adminForm" method="post" name="adminForm" enctype="multipart/form-data">
                        	<!-- Start Search -->
                            <div class="clearfix">
                                <div class="g_cell span6">
                                 <div class="input-group g_search">
                                      <input type="text" class="form-control inputbox" name="filter_search" value="<?php if(isset($_POST['filter_search'])) echo $_POST['filter_search'];?>" >
                                      <span class="input-group-btn g_hide_mobile">
                                        <button class="btn btn-primary" type="submit"><?php echo JText::_("GURU_SEARCH"); ?></button>
                                      </span>
                                  </div><!-- /input-group -->
                                </div>
                                <div class="g_cell span6">
                                    <select name="filter_state" onchange="adminForm.submit();">
                                        <option value="-1" <?php if($state == "-1"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_SELECT_STATUS"); ?></option>
                                        <option value="1" <?php if($state == "1"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_PUBLISHED"); ?></option>
                                        <option value="0" <?php if($state == "0"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_UNPUBLISHED"); ?></option>
                                    </select>
                            	</div>
                            </div>
                            <!-- End Search -->
                            
                            <div class="clearfix">
                                <div class="g_table_wrap g_margin_top">
                                    <table id="g_authormediacat" class="table table-striped">
                                        <tr class="g_table_header">
                                            <th class="g_cell_1"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);" /></th>
                                            <th class="g_cell_2 g_hide_mobile"><?php echo JText::_('GURU_ID'); ?></th>
                                            <th class="g_cell_3"><?php echo JText::_('GURU_NAME'); ?></th>
                                            <th class="g_cell_4"><?php echo JText::_("GURU_PUBL"); ?></th>
                                        </tr>
                    
                           
										<?php 
                                        $n =  count($my_media_cat);
                                        for ($i = 0; $i < $n; $i++):
                                            $id = $my_media_cat[$i]["id"];
                                            $checked = JHTML::_('grid.id', $i, $id);
                                           
                                            $published = JHTML::_('grid.published', $my_media_cat, $i );
                                        ?>
                                             <tr class="guru_row">
                                             	<td class="g_cell_1"><?php echo $checked;?></td>
                                                <td class="g_cell_2 g_hide_mobile"><?php echo $id;?></td>
                                                <td class="guru_product_name g_cell_3">
													<?php
                                                        $line = "";
                                                        for($j=0; $j<$my_media_cat[$i]["level"]; $j++){
                                                            $line .= '&#151;';
                                                        }
                                                    ?>
                                                     <a href="index.php?option=com_guru&view=guruauthor&task=authoraddeditmediacat&id=<?php echo intval($my_media_cat[$i]["id"]); ?>"><?php echo $line."(".$my_media_cat[$i]["level"].") ".$my_media_cat[$i]["name"]; ?></a>
                                               </td>
                                                    <td class="g_cell_4">
														 <?php
                                                            if($my_media_cat[$i]["published"] == 0){
                                                                echo '<a title="Publish Item" onclick="return listItemTask(\'cb'.$i.'\', \'publishMediaCat\')" href="#">
                                                                        <img alt="Unpublished" src="components/com_guru/images/icons/publish_x.png">
                                                                      </a>';
                                                            }
                                                            else{
                                                                echo '<a title="Unpublish Item" onclick="return listItemTask(\'cb'.$i.'\',\'unpublishMediaCat\')" href="#">
                                                                        <img alt="Published" src="components/com_guru/images/icons/tick.png">
                                                                      </a>';
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
                           
                            <input type="hidden" name="task" value="<?php echo JRequest::getVar("task", ""); ?>" />
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