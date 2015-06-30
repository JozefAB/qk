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
$my_media = $this->mymediath;
$config = $this->config;
$filters= $this->filters;
$all_categs =  $this->all_categs;
$allow_teacher_action = json_decode($config->st_authorpage);//take all the allowed action from administator settings
$teacher_add_media = @$allow_teacher_action->teacher_add_media; //allow or not action Add media
$teacher_edit_media = @$allow_teacher_action->teacher_edit_media; //allow or not action Edit media
$Itemid = JRequest::getVar("Itemid", "0");

$doc =JFactory::getDocument();
$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');
$doc->addScript('components/com_guru/js/jquery.noconflict.js');
$doc->addScript('components/com_guru/js/jquery.DOMWindow.js');
$doc->addScript('components/com_guru/js/guru_modal.js');
$doc->addScript('components/com_guru/js/jquery-dropdown.js');

$doc->addScript(JURI::root().'plugins/content/jw_allvideos/jw_allvideos/includes/js/mediaplayer/jwplayer.min.js');
$doc->addScript(JURI::root().'plugins/content/jw_allvideos/jw_allvideos/includes/js/wmvplayer/silverlight.js');
$doc->addScript(JURI::root().'plugins/content/jw_allvideos/jw_allvideos/includes/js/wmvplayer/wmvplayer.js');
$doc->addScript(JURI::root().'plugins/content/jw_allvideos/jw_allvideos/includes/js/quicktimeplayer/AC_QuickTime.js');
$doc->setTitle(trim(JText::_('GURU_AUTHOR'))." ".trim(JText::_('GURU_AUTHOR_MY_MEDIA')));
?>
<script language="javascript" type="application/javascript">
	function deleteAuthorMedia(){
		if (document.adminForm['boxchecked'].value == 0) {
			alert( "<?php echo JText::_("GURU_MAKE_SELECTION_FIRT");?>" );
		} 
		else{
			if(confirm("<?php echo JText::_("GURU_REMOVE_AUTHOR_MEDIA"); ?>")){
				document.adminForm.task.value='removeMedia';
				document.adminForm.submit();
			}
		}	
	}
	function unpublishMedia(){
		if (document.adminForm['boxchecked'].value == 0) {
			alert( "<?php echo JText::_("GURU_MAKE_SELECTION_FIRT");?>" );
		} 
		else{
			document.adminForm.task.value='unpublishMedia';
			document.adminForm.submit();
		}	
	}
	
	function publishMedia(){
		if (document.adminForm['boxchecked'].value == 0) {
			alert( "<?php echo JText::_("GURU_MAKE_SELECTION_FIRT");?>" );
		} 
		else{
			document.adminForm.task.value='publishMedia';
			document.adminForm.submit();
		}
	}
	
	function duplicateMedia(){
		if (document.adminForm['boxchecked'].value == 0) {
			alert( "<?php echo JText::_("GURU_MAKE_SELECTION_FIRT");?>" );
		} 
		else{
			document.adminForm.task.value='duplicateMedia';
			document.adminForm.submit();
		}
	}
	
	function newMedia(val){
		document.adminForm.task.value='editMedia';
		document.adminForm.selected_item_New.value=val;
		document.adminForm.submit();
	}
	
	function newAuthorMediaCategory(){
		document.adminForm.task.value='authoraddeditmediacat';
		document.adminForm.submit();
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
                <div id="g_mycoursesauthor" class="clearfix com-cont-wrap">
                    <?php 	echo $div_menu; //MENU TOP OF AUTHORS?>
                    <!--BUTTONS -->
                    <div class="g_inline_child  clearfix">
                       <?php if($teacher_add_media == 0){?>
                       			<div class="btn-options-group pull-left">
                                   <button onclick="editOptions();" id="new-options" class="btn btn-success g_toggle_button"><?php echo JText::_('GURU_DAY_NEW_MEDIA'); ?>&nbsp;<span class="fa fa-caret-down"></span></button>
                                    <div class="button-options" id="button-options" style="display:none;">
                                        <ul>
                                            <li>
                                                <a href="#" onclick="newMedia('video');">
                                                    <?php echo JText::_("GURU_VIDEO"); ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" onclick="newMedia('audio');">
                                                    <?php echo JText::_("GURU_AUDIO"); ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" onclick="newMedia('docs');">
                                                    <?php echo JText::_("GURU_DOCS"); ?>
                                                </a>
                                            </li>
                                             <li>
                                                <a href="#" onclick="newMedia('url');">
                                                    <?php echo JText::_("GURU_URL"); ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" onclick="newMedia('image');">
                                                    <?php echo JText::_("GURU_IMAGE"); ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" onclick="newMedia('text');">
                                                    <?php echo JText::_("GURU_text"); ?>
                                                </a>
                                            </li>
                                            
                                            <li>
                                                <a href="#" onclick="newMedia('file');">
                                                    <?php echo JText::_("GURU_MEDIATYPEFILE_"); ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                       <?php }?>
                       	 <button class="btn btn-warning"  onclick="duplicateMedia();"><?php echo JText::_('GURU_DUPLICATE'); ?></button>
                         <button class="btn btn-danger"   onclick="deleteAuthorMedia();"><?php echo JText::_('GURU_DELETE'); ?></button>
                         
                    </div> 
                    <!-- -->
                    <div class="profile_page page_title">
                        <h2><?php echo JText::_('GURU_AUTHOR_MY_MEDIA');?></h2>
                    </div>
                    <div id="g_mymediaauthorcontent" class="g_sect clearfix">
                        <form  action="index.php" class="form-horizontal" id="adminForm" method="post" name="adminForm" enctype="multipart/form-data">
                            <div id="filter-bar" class="btn-toolbar clearfix">
                                <div class="btn-group pull-left">
                                    <?php echo $filters->status; ?>
                                </div>
                                <div class="btn-group pull-left">
                                    <?php echo $filters->type; ?>
                                </div>
                                <div class="btn-group pull-left">
                                    <input type="text" class="form-control inputbox" name="search_media" style="height:34px;" value="<?php if(isset($_POST['search_media'])) echo $_POST['search_media'];?>" >
                                    <span class="input-group-btn g_hide_mobile">
										<button class="btn btn-primary" type="submit" style="height:28px;"><?php echo JText::_("GURU_SEARCH"); ?></button>
                                    </span>
                                </div>
                            </div>    
                           
                            <div class="clearfix">
                                <div class="g_table_wrap g_margin_top">
                                    <table id="g_authormedia" class="table table-striped">
                                        <tr class="g_table_header">
                                            <th class="g_cell_1"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this);" /></th>
                                            <th class="g_cell_2 g_hide_mobile"><?php echo JText::_('GURU_ID'); ?></th>
                                            <th class="g_cell_3"><?php echo JText::_('GURU_NAME'); ?></th>
                                            <th class="g_cell_4 g_hide_mobile"><?php echo JText::_("GURU_TYPE"); ?></th>
                                            <!--<th class="g_cell_5 g_hide_mobile"><?php echo JText::_("GURU_TASKS_CATEGORY"); ?></th>-->
                                            <th class="g_cell_6"><?php echo JText::_("GURU_PREVIEW"); ?></th>
                                            <th class="g_cell_7"><?php echo JText::_("GURU_PROGRAM_DETAILS_STATUS"); ?></th>
                                        </tr>
                                                            
                                                   
                                        <?php 
                                        $n =  count($my_media);
                                        for ($i = 0; $i < $n; $i++):
                                            $id = $my_media[$i]->id;
                                            $checked = JHTML::_('grid.id', $i, $id);
                                           
                                            $published = JHTML::_('grid.published', $my_media, $i );
                                            $alias = isset($my_media[$i]->alias) ? trim($my_media[$i]->alias) : JFilterOutput::stringURLSafe($my_media[$i]->name);
                                        ?>
                                            <tr class="guru_row">
                                            	 <td class="g_cell_1"><?php echo $checked;?></td>
                                                 <td class="g_cell_2 g_hide_mobile"><?php echo $id;?></td>
                                                 <td class="guru_product_name g_cell_3">
                                                 <?php 
												 if($teacher_edit_media == 0){
												 ?>
                                                 <a href="<?php echo JRoute::_("index.php?option=com_guru&view=guruauthor&task=editMedia&cid=".$id."-".$alias."&Itemid=".$Itemid); ?>"><?php echo $my_media[$i]->name; ?></a>
                                                 <?php
												 }
												 else{
													echo $my_media[$i]->name;
												 }
												 ?>
                                                 </td>
                                                
                                                <td class="g_cell_4 g_hide_mobile">
													<?php
                                                       switch ($my_media[$i]->type) {
                                                            case "video": $class = "fa fa-video-camera";	    						
                                                                break;
                                                            case "audio": $class = "fa fa-play-circle";	    						
                                                                break;
                                                            case "docs": $class = "fa fa-folder-open";	    						
                                                                break;
                                                            case "quiz": $class = "fa fa-question";	    						
                                                                break;
                                                            case "url": $class = "fa fa-link";	    						
                                                                break;
                                                            case "Article": $class = "fa fa-file-text";	    						
                                                                break;											
                                                            case "image": $class = "fa fa-picture-o";	    						
                                                                break;
                                                            case "text": $class = "fa fa-book";	    						
                                                            break;	
                                                            case "file": $class = "fa fa-file";	    						
                                                                break;										
                                                        }
                                                     ?>
                                                     <i class="<?php echo $class; ?>"></i>
                                                </td>
                                               <!-- <td class="g_cell_5 g_hide_mobile">    
													 <?php
                                                        if(isset($all_categs) && is_array($all_categs) && isset($all_categs[$my_media[$i]->category_id])){
															echo $all_categs[$my_media[$i]->category_id]["name"];
                                                        }
                                                    ?>
                                                </td>-->
                                                <td class="g_cell_6">
                                                	<?php
                                                    	$width = "700";
														$height = "530";
														
														if($my_media[$i]->type == "video"){
															$width = "850";
															$height = "615";
														}
														elseif($my_media[$i]->type == "audio"){
															$width = "400";
															$height = "200";
														}
														elseif($my_media[$i]->type == "docs"){
															$width = "700";
															$height = "530";
														}
														elseif($my_media[$i]->type == "url"){
															if($my_media[$i]->width == 1){ // link
																$width = "400";
																$height = "200";
															}
															else{ // wrap
																$width = "700";
																$height = "530";
															}
														}
														elseif($my_media[$i]->type == "image"){
															$width = "700";
															$height = "530";
														}
														elseif($my_media[$i]->type == "text"){
															$width = "700";
															$height = "530";
														}
														elseif($my_media[$i]->type == "file"){
															$width = "400";
															$height = "200";
														}
													?>
													<a class="g_hide_mobile" onclick="javascript:openMyModal(window.screen.availWidth - 120, window.screen.availHeight - 180, '<?php echo JURI::root(); ?>index.php?option=com_guru&controller=guruAuthor&task=preview&tmpl=component&id=<?php echo $my_media[$i]->id;?>'); return false;" href="#">
                                                    	<?php echo JText::_('GURU_MEDIA_PREVIEW_LOWER');?>
                                                    </a>
                                                    
                                                    
                                                    <a class="g_mobile" onclick="javascript:openMyModal(<?php echo $width; ?>, <?php echo $height; ?>, '<?php echo JURI::root(); ?>index.php?option=com_guru&controller=guruAuthor&task=preview&tmpl=component&id=<?php echo $my_media[$i]->id;?>'); return false;" href="#">
                                                    	<i class="fa fa-search-plus"></i>
                                                    </a>
                                                </td>
                                                <td class="g_cell_7">    
													<?php
                                                        if($my_media[$i]->published == 0){
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
                           	
                            <input type="hidden" name="task" value="authormymedia" />
                            <input type="hidden" name="option" value="com_guru" />
                            <input type="hidden" name="controller" value="guruAuthor" />
                            <input type="hidden" name="boxchecked" value="" />
                            <input type="hidden" name="selected_item_New" value="" />
                        </form>
                        
                   </div>  
              </div>
             </div> 
		</div>
	</div>
 </div>                   
 <script language="javascript">
	var first = false;
	function showContentVideo(href){
		first = true;
		jQuery( '#myModal .modal-body iframe').attr('src', href);
	}
	
	function closeModal(){
		jQuery('#myModal .modal-body iframe').attr('src', '');
	}
	
	jQuery('#myModal').on('hide', function () {
		jQuery('#myModal .modal-body iframe').attr('src', '');
	});
	
	if(!first){
		jQuery('#myModal .modal-body iframe').attr('src', '');
	}
	else{
		first = false;
	}
</script>