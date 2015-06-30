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
defined ('_JEXEC') or die ("Go away.");
JHTML::_('behavior.tooltip');

	$data = $this->data;
	$_row = $this->media;
	$lists = @$_row->lists;
	$doc =JFactory::getDocument();
	$doc->setTitle(trim(JText::_('GURU_AUTHOR'))." ".trim(JText::_('GURU_AUTHOR_MY_MEDIA')));
	$nullDate = 0;
	$livesite = JURI::base();	
	$configuration = $this->getConfigsObject();	
	$editorul  = JFactory::getEditor();
	
	$UPLOAD_MAX_SIZE = @ini_get('upload_max_filesize');
	$max_post 		= (int)(ini_get('post_max_size'));
	$memory_limit 	= (int)(ini_get('memory_limit'));
	$UPLOAD_MAX_SIZE = min($UPLOAD_MAX_SIZE, $max_post, $memory_limit);
	if($UPLOAD_MAX_SIZE == 0) {$UPLOAD_MAX_SIZE = 10;}
	
	$maxUpload = "<font color='#FF0000'>";
	$maxUpload .= JText::_('GURU_MEDIA_MAX_UPL_V_1')." ";
	$maxUpload .= $UPLOAD_MAX_SIZE.'B ';
	$maxUpload .= "</font>";
	$div_menu = $this->authorGuruMenuBar();
	
	include(JPATH_SITE.DS.DS.'components'.DS.'com_guru'.DS.'helpers'.DS.'createUploader.php');
	?>
    <style type="text/css">
		/* modal
		-------------------------*/
		.modal{
			left: 50px;
			margin: 0 !important;
			padding-top: 20px;
			right: 50px;
			top: 10%;
			width: auto;
			border:none !important;
			border-radius: 0px !important;
			box-shadow: none !important;
			position:inherit !important;
		}
		
	</style>
    
	<script type="text/javascript" src="<?php echo JURI::base(); ?>components/com_guru/views/guruauthor/tmpl/js/js.js"></script>	            
	<script language="javascript" type="text/javascript">
		function changefolder() {								
			submitbutton('changes');
		}
		
		function isFloat(nr){
			return parseFloat(nr.match(/^-?\d*(\.\d+)?$/)) > 0;
		}
		
		Joomla.submitbutton = function(pressbutton){
			var form = document.adminForm;
			if (pressbutton == 'save_media' || pressbutton == 'apply_media') { 
				if (form['name'].value == "") {
					alert( "<?php echo JText::_("GURU_MEDIA_JS_NAME_ERR");?>" );
				} 
				else if (form['type'].value == 0 || form['type'].value == "-" ) {
					alert( "<?php echo JText::_("GURU_MEDIA_JS_TYPE_ERR");?>" ); 
				}
				else if (form['type'].value == 'image' && form['is_image'].value == 0) {
					alert( "<?php echo JText::_("GURU_MEDIA_JS_IMAGE_ERR");?>" );
				}
				else if (form['type'].value == 'text' && document.getElementById('textblock').value =='') {
					alert( "<?php echo JText::_("GURU_MEDIA_JS_TEXT_ERR");?>" );
				}				
				else {
					type = document.getElementById("type").value;
					
					if(type == "video"){
						if(document.getElementById("source_v").value == 'code'){
							if(form['code_v'].value == ""){
								alert("<?php echo JText::_("GURU_ADD_VIDEO_CODE"); ?>");
								return false;
							}
						}
						else if(document.getElementById("source_v").value == 'url'){
							if(form['url_v'].value == ""){
								alert("<?php echo JText::_("GURU_ADD_VIDEO_URL"); ?>");
								return false;
							}
						}
						else if(document.getElementById("source_v").value == 'local'){
							if(form['localfile'].value == ""){
								alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
								return false;
							}
						}
						
					}
					else if(type == "audio"){
						width_a = document.getElementById("width_a").value;
						if(document.getElementById("source_a").value == 'local'){
							if(!isFloat(width_a) || width_a <= 0){
								alert("<?php echo JText::_("GURU_MEDIAAPPLYFAILED").JText::_("GURU_MEDIASAVEDSIZE"); ?>");
								return false;
							}
						}
						
						if(document.getElementById("source_a").value == 'code'){
							if(form['code_a'].value == ""){
								alert("<?php echo JText::_("GURU_ADD_AUDIO_CODE"); ?>");
								return false;
							}
						}
						else if(document.getElementById("source_a").value == 'url'){
							if(form['url_a'].value == ""){
								alert("<?php echo JText::_("GURU_ADD_AUDIO_URL"); ?>");
								return false;
							}
						}
						else if(document.getElementById("source_a").value == 'local'){
							if(form['localfile_a'].value == ""){
								alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
								return false;
							}
						}
					}
					else if(type == "docs"){
						width = document.getElementById("width").value;
						height = document.getElementById("height").value;
						link = document.getElementById("display_as").value;
						
						if(document.getElementById("source_d").value == 'local' && link == 'wrapper'){
							if(!isFloat(width) || width <= 0){
								alert("<?php echo JText::_("GURU_MEDIAAPPLYFAILED").JText::_("GURU_MEDIASAVEDSIZE"); ?>");
								return false;
							}
							else if(!isFloat(height) || height <= 0){
								alert("<?php echo JText::_("GURU_MEDIAAPPLYFAILED").JText::_("GURU_MEDIASAVEDSIZE"); ?>");
								return false;
							}
						}
						
						if(document.getElementById("source_d").value == 'url'){
							if(form['url_d'].value == ""){
								alert("<?php echo JText::_("GURU_ADD_DOC_URL"); ?>");
								return false;
							}
						}
						else if(document.getElementById("source_d").value == 'local'){
							if(form['localfile_d'].value == ""){
								alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
								return false;
							}
						}
					}
					else if(type == "image"){
						media_fullpx = document.getElementById("media_fullpx").value;
						if(!isFloat(media_fullpx) || media_fullpx <= 0){
							alert("<?php echo JText::_("GURU_MEDIAAPPLYFAILED").JText::_("GURU_MEDIASAVEDSIZE"); ?>");
							return false;
						}
					}
					else if(type == "file"){
						if(document.getElementById("source_f").value == 'url'){
							if(document.getElementById("url_f").value == ""){
								alert("<?php echo JText::_("GURU_ADD_FILE_URL"); ?>");
								return false;
							}
						}
						else if(document.getElementById("source_f").value == 'local'){
							if(document.getElementById("localfile_f").value == "" || document.getElementById("localfile_f").value == "root"){
								alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
								return false;
							}
						}
					}
					
					submitform( pressbutton );
				}
			}
			else{
				submitform( pressbutton );
			}
		}
		
		function SelectArticleg(id, title, object){ 
			document.getElementById('articleid').value = id;
			document.getElementById('article_name').value = title;	
			window.parent.SqueezeBox.close();
		}
		function makeActiveV(value){
			if(value == 'embeded'){
				document.getElementById("embeded").className ="tab-pane active";
				document.getElementById("addfromurl").className ="tab-pane";
				document.getElementById("upload").className ="tab-pane";
				document.getElementById("selectfile").className ="tab-pane";
				document.getElementById("li_embeded").className ="active";
				document.getElementById("li_addfromurl").className ="";
				document.getElementById("li_upload").className ="";
				document.getElementById("li_selectfile").className ="";
				document.getElementById("source_v").value = "code";
				document.getElementById("uploaded_tab").value = "-1";

			}
			if(value == 'addfromurl'){
				document.getElementById("embeded").className ="tab-pane";
				document.getElementById("addfromurl").className ="tab-pane active";
				document.getElementById("upload").className ="tab-pane";
				document.getElementById("selectfile").className ="tab-pane";
				document.getElementById("li_embeded").className ="";
				document.getElementById("li_addfromurl").className ="active";
				document.getElementById("li_upload").className ="";
				document.getElementById("li_selectfile").className ="";
				document.getElementById("source_v").value = "url";
				document.getElementById("uploaded_tab").value = "-1";
			}
			if(value == 'upload'){
				document.getElementById("embeded").className ="tab-pane";
				document.getElementById("addfromurl").className ="tab-pane";
				document.getElementById("upload").className ="tab-pane active";
				document.getElementById("selectfile").className ="tab-pane";
				document.getElementById("li_embeded").className ="";
				document.getElementById("li_addfromurl").className ="";
				document.getElementById("li_upload").className ="active";
				document.getElementById("li_selectfile").className ="";
				document.getElementById("source_v").value = "";
				document.getElementById("uploaded_tab").value = "0";
			}
			if(value == 'selectfile'){
				document.getElementById("embeded").className ="tab-pane";
				document.getElementById("addfromurl").className ="tab-pane";
				document.getElementById("upload").className ="tab-pane";
				document.getElementById("selectfile").className ="tab-pane active";
				document.getElementById("li_embeded").className ="";
				document.getElementById("li_addfromurl").className ="";
				document.getElementById("li_upload").className ="";
				document.getElementById("li_selectfile").className ="active";
				document.getElementById("source_v").value = "";
				document.getElementById("uploaded_tab").value = "1";
			}
		}
		function makeActiveA(value){
			if(value == 'embeded'){
				document.getElementById("embeded_a").className ="tab-pane active";
				document.getElementById("addfromurl_a").className ="tab-pane";
				document.getElementById("upload_a").className ="tab-pane";
				document.getElementById("selectfile_a").className ="tab-pane";
				document.getElementById("lia_embeded").className ="active";
				document.getElementById("lia_addfromurl").className ="";
				document.getElementById("lia_upload").className ="";
				document.getElementById("lia_selectfile").className ="";
				document.getElementById("source_a").value = "code";
				document.getElementById("uploaded_tab").value = "-1";

			}
			if(value == 'addfromurl'){
				document.getElementById("embeded_a").className ="tab-pane";
				document.getElementById("addfromurl_a").className ="tab-pane active";
				document.getElementById("upload_a").className ="tab-pane";
				document.getElementById("selectfile_a").className ="tab-pane";
				document.getElementById("lia_embeded").className ="";
				document.getElementById("lia_addfromurl").className ="active";
				document.getElementById("lia_upload").className ="";
				document.getElementById("lia_selectfile").className ="";
				document.getElementById("source_a").value = "url";
				document.getElementById("uploaded_tab").value = "-1";
			}
			if(value == 'upload'){
				document.getElementById("embeded_a").className ="tab-pane";
				document.getElementById("addfromurl_a").className ="tab-pane";
				document.getElementById("upload_a").className ="tab-pane active";
				document.getElementById("selectfile_a").className ="tab-pane";
				document.getElementById("lia_embeded").className ="";
				document.getElementById("lia_addfromurl").className ="";
				document.getElementById("lia_upload").className ="active";
				document.getElementById("lia_selectfile").className ="";
				document.getElementById("source_a").value = "";
				document.getElementById("uploaded_tab").value = "0";
			}
			if(value == 'selectfile'){
				document.getElementById("embeded_a").className ="tab-pane";
				document.getElementById("addfromurl_a").className ="tab-pane";
				document.getElementById("upload_a").className ="tab-pane";
				document.getElementById("selectfile_a").className ="tab-pane active";
				document.getElementById("lia_embeded").className ="";
				document.getElementById("lia_addfromurl").className ="";
				document.getElementById("lia_upload").className ="";
				document.getElementById("lia_selectfile").className ="active";
				document.getElementById("source_a").value = "";
				document.getElementById("uploaded_tab").value = "1";
			}
		}		
		function makeActiveD(value){
			if(value == 'addfromurl'){
				document.getElementById("addfromurl_d").className ="tab-pane active";
				document.getElementById("upload_d").className ="tab-pane";
				document.getElementById("selectfile_d").className ="tab-pane";
				document.getElementById("lid_addfromurl").className ="active";
				document.getElementById("lid_upload").className ="";
				document.getElementById("lid_selectfile").className ="";
				document.getElementById("source_d").value = "url";
				document.getElementById("uploaded_tab").value = "-1";
			}
			if(value == 'upload'){
				document.getElementById("addfromurl_d").className ="tab-pane";
				document.getElementById("upload_d").className ="tab-pane active";
				document.getElementById("selectfile_d").className ="tab-pane";
				document.getElementById("lid_addfromurl").className ="";
				document.getElementById("lid_upload").className ="active";
				document.getElementById("lid_selectfile").className ="";
				document.getElementById("source_d").value = "";
				document.getElementById("uploaded_tab").value = "0";
			}
			if(value == 'selectfile'){
				document.getElementById("addfromurl_d").className ="tab-pane";
				document.getElementById("upload_d").className ="tab-pane";
				document.getElementById("selectfile_d").className ="tab-pane active";
				document.getElementById("lid_addfromurl").className ="";
				document.getElementById("lid_upload").className ="";
				document.getElementById("lid_selectfile").className ="active";
				document.getElementById("source_d").value = "";
				document.getElementById("uploaded_tab").value = "1";
			}
		}
		function makeActiveF(value){
			if(value == 'addfromurl'){
				document.getElementById("addfromurl_f").className ="tab-pane active";
				document.getElementById("upload_f").className ="tab-pane";
				document.getElementById("selectfile_f").className ="tab-pane";
				document.getElementById("lif_addfromurl").className ="active";
				document.getElementById("lif_upload").className ="";
				document.getElementById("lif_selectfile").className ="";
				document.getElementById("source_f").value = "url";
				document.getElementById("uploaded_tab").value = "-1";
			}
			if(value == 'upload'){
				document.getElementById("addfromurl_f").className ="tab-pane";
				document.getElementById("upload_f").className ="tab-pane active";
				document.getElementById("selectfile_f").className ="tab-pane";
				document.getElementById("lif_addfromurl").className ="";
				document.getElementById("lif_upload").className ="active";
				document.getElementById("lif_selectfile").className ="";
				document.getElementById("source_f").value = "";
				document.getElementById("uploaded_tab").value = "0";
			}
			if(value == 'selectfile'){
				document.getElementById("addfromurl_f").className ="tab-pane";
				document.getElementById("upload_f").className ="tab-pane";
				document.getElementById("selectfile_f").className ="tab-pane active";
				document.getElementById("lif_addfromurl").className ="";
				document.getElementById("lif_upload").className ="";
				document.getElementById("lif_selectfile").className ="active";
				document.getElementById("source_f").value = "";
				document.getElementById("uploaded_tab").value = "1";
			}
		}
</script>

<?php
	$tmpl = JRequest::getVar("tmpl", "");
	if($tmpl == "component"){
?>        
        <style type="text/css">
            .redactor_box {
                width:60%;
            }
        </style>
<?php
	}
?>
        
<div class="g_row clearfix">
	<div class="g_cell span12">
		<div>
			<div>
                <div id="g_mymedia_addedit" class="clearfix com-cont-wrap">        
					<?php 
                        $document = JFactory::getDocument();
                        //$document->addStyleSheet("components/com_guru/css/ytb.css"); 
                        $tmpl = JRequest::getVar("tmpl", "");
                        
                        if($tmpl != "component"){
                            echo $div_menu;
                        }
                    ?>
                    
                    <form class="form-horizontal" action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
                    		 <?php
								if($tmpl != "component"){
							?>
									<div class="g_inline_child g_margin_bottom">
											<input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE"); ?>" onclick="Joomla.submitbutton('apply_media');" />
											<input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE_AND_CLOSE"); ?>" onclick="Joomla.submitbutton('save_media');" />
											<input type="button" class="btn btn-inverse" value="<?php echo JText::_("GURU_CLOSE"); ?>" onclick="document.location.href='<?php echo JRoute::_("index.php?option=com_guru&controller=guruAuthor&task=authormymedia&layout=authormymedia"); ?>';" />
									</div>
							<?php
								}
								else{
							?>
									<div class="g_inline_child g_margin_bottom">
											<input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE_AND_CLOSE"); ?>" onclick="Joomla.submitbutton('savesbox');" />
									</div>
							<?php
								}
							?>
                        	<div class="page_title">
                                <h2>
                                	<?php if(isset($_row->id)) {echo  JText::_('GURU_DAY_EDIT_MEDIA');} else{echo  JText::_('GURU_DAY_NEW_MEDIA');} 
									?>
                                </h2>    
                            </div>
                            <div class="clearfix"></div>
                            <div class="control-group">
                                <label class="control-label" for="author_title"><?php echo JText::_('GURU_TYPE'); ?>:<font color="#ff0000">*</font></label>
                                <div class="controls">
                                    <?php 
                                        echo $lists['type'];
                                    ?>
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_TYPE"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>    
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label" for="author_title"><?php echo JText::_('GURU_NAME'); ?>:<font color="#ff0000">*</font></label>
                                <div class="controls">
                                    <?php
                                        if(isset($_row->name)){
                                            $_row->name = str_replace('"', '&quot;', $_row->name);
                                        }
                                    ?>
                                    <input class="formField pull-left" type="text" name="name" id="name" size="60" value="<?php echo $_row->name; ?>">
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIA_NAME"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                    <div class="pull-left g_margin_left">
                                        <input type="checkbox" value="1" name="hide_name" <?php if($_row->hide_name == 1){ echo 'checked="checked"'; } ?> />
                                        <span class="lbl"></span>
                                        <?php echo JText::_("LM_HIDE_NAME"); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="published" value="1"/>
                            
                            <!--
                            <div class="control-group">
                                <label class="control-label" for="author_title"><?php //echo JText::_('NEWADAPPROVED');?>:<font color="#ff0000">*</font></label> 
                                <div class="controls">
                                    <?php //echo $lists['approved'];?>
                                    <span class="editlinktip hasTip" title="<?php //echo JText::_("GURU_TIP_MEDIA_NEWADAPPROVED"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                </div>
                            </div>
                            -->
                            
                            <div class="control-group">
                                <label class="control-label" for="author_title"><?php echo JText::_('GURU_INSTR');?>: </label>
                                <div class="controls">
                                     <textarea class="formField" type="text" name="instructions" rows="2" cols="60" ><?php echo stripslashes($_row->instructions); ?></textarea>
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIA_INSTR"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                </div>
                            </div>
                          <div class="control-group clearfix">
                            <label class="control-label" for="author_title"><?php echo JText::_('GURU_SHOW_INSTRUCTION');?>: </label>
                            <div class="controls">
                                 <select name="show_instruction" id="show_instruction">
                                    <option value="0" <?php if($_row->show_instruction == "0"){echo 'selected="selected"'; } ?> ><?php echo JText::_("GURU_SHOW_ABOVE"); ?></option>
                                    <option value="1" <?php if($_row->show_instruction == "1"){echo 'selected="selected"'; } ?> ><?php echo JText::_("GURU_SHOW_BELOW"); ?></option>
                                    <option value="2" <?php if($_row->show_instruction == "2"){echo 'selected="selected"'; } ?> ><?php echo JText::_("GURU_DONT_SHOW"); ?></option>
                                </select>
                            </div>
                        </div>
                         <?php
							if(isset($_row->auto_play) && $_row->auto_play == NULL){
								$_row->auto_play = "1";
							}
							$auto_play_display = "none";
							if(isset($_row->type) && ($_row->type=='audio' || $_row->type=='video')){
								$auto_play_display = "table-row";
							}
						?>
                        <div class="control-group" id="auto_play" style="display:<?php echo $auto_play_display; ?>">
                            <label class="control-label" for="author_title">
								<?php echo JText::_('GURU_AUTO_PLAY');?>
							</label> 
                            <div class="controls">
                            	<input type="hidden" name="auto_play" value="0">
								<?php
                                    $checked = '';
                                    if($_row->auto_play == 1){
                                        $checked = 'checked="checked"';
                                    }
                                ?>
                                <input type="checkbox" <?php echo $checked; ?> value="1" class="ace-switch ace-switch-5" name="auto_play">
                                <span class="lbl"></span>
                            </div>
                        </div>
                        
                        <?php
							$display_docs = "none";
							if(isset($_row->type) && $_row->type=='docs'){
								$display_docs = "block";
							}
						?>
                        <div class="control-group" id="display_docs" style="display:<?php echo $display_docs; ?>">
                            <label class="control-label" for="author_title">
								<?php echo JText::_('GURU_MEDIA_DISPL_DOC');?>:
							</label> 
                            <div class="controls">
                            	<script type="text/javascript">
									function wh(y){
										if(y==1){
											document.getElementById('whdoc').style.display='';
										} 
										if (y==0) {
											document.getElementById('whdoc').style.display='none';
										}	
									}
								</script>								
								<select id="display_as" name="display_as">
									<option value="wrapper" onclick="javascript:wh(1)"><?php echo JText::_('GURU_MEDIA_DISPL_DOC_W'); ?></option>
									<option value="link" onclick="javascript:wh(0)" <?php if($_row->type=='docs' && $_row->width==1) {echo 'selected = "selected"'; $sel_link=1;}?>><?php echo JText::_('GURU_MEDIA_DISPL_DOC_L'); ?></option>
								</select>
								<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DISPL_DOC"); ?>" >
									<img border="0" src="components/com_guru/images/icons/tooltip.png">
								</span>
                            </div>
                        </div>
                        
                        <?php
                        	//-------- VIDEO - BEGIN 
                    		$display_list_of_dir = $lists['video_dir'];
		                    $display_list_of_files = $lists['video_url'];
        		            $folder_of_files = $configuration->videoin;
                		    if(isset($_row->type) && $_row->type=='video'){
                        		$stylev = 'style="table-row;"';
							}
                    		else{
                        		$stylev = 'style="display:none;"';
							}
						?>
                       
                        <div class="control-group" id="videoblock" <?php echo $stylev; ?>>
                            <label class="control-label">
								<?php echo JText::_('GURU_MEDIATYPEVIDEOS'); ?> <font color="#ff0000">*</font>
                            </label>
                            <div class="controls">
                            	<div class="pull-left clearfix">
									<?php 
                                        $tab_active1 = "active";
                                        $tab_active2 = "";
                                        $tab_active3 = "";
                                        $tab_active4 = "";
                                        
                                         if($_row->source=='code'){
                                            $tab_active1 = "active";
                                            $tab_active2 = "";
                                            $tab_active3 = "";
                                            $tab_active4 = "";
                                         }
                                         if($_row->source=='url'){
                                            $tab_active1 = "";
                                            $tab_active2 = "active";
                                            $tab_active3 = "";
                                            $tab_active4 = "";
                                         }
                                         if($_row->source=='local' && $_row->uploaded_tab==0){
                                            $tab_active1 = "";
                                            $tab_active2 = "";
                                            $tab_active3 = "active";
                                            $tab_active4 = "";
                                         }
                                         if($_row->source=='local' && $_row->uploaded_tab==1){
                                            $tab_active1 = "";
                                            $tab_active2 = "";
                                            $tab_active3 = "";
                                            $tab_active4 = "active";
                                         }
                                    ?>
                                    <ul class="nav nav-tabs">
                                        <li onclick="makeActiveV('embeded'); return false;" id="li_embeded" class="<?php echo $tab_active1; ?>"><a href="#embeded" data-toggle="tab"><?php echo JText::_('GURU_EMBEDED_CODE');?></a></li>
                                        <li onclick="makeActiveV('addfromurl'); return false;" id="li_addfromurl" class="<?php echo $tab_active2; ?>"><a href="#addfromurl" data-toggle="tab"><?php echo JText::_('GURU_ADD_FROM_URL_MEDIA');?></a></li>
                                        <li onclick="makeActiveV('upload'); return false;" id="li_upload" class="<?php echo $tab_active3; ?>"><a href="#upload" data-toggle="tab"><?php echo JText::_('GURU_UPLOAD_FILE');?></a></li>
                                        <li onclick="makeActiveV('selectfile'); return false;" id="li_selectfile"  class="<?php echo $tab_active4; ?>"><a href="#selectfile" data-toggle="tab"><?php echo JText::_('GURU_SELECT_EXISTING_FILE');?></a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane <?php echo $tab_active1; ?>" id="embeded">
                                            <div class="pull-left">
                                                <?php echo JText::_('GURU_MEDIATYPEVIDEO').' '.JText::_('GURU_MEDIATYPECODE'); ?>
                                                &nbsp;&nbsp;
                                                <textarea cols="23" name="code_v"><?php echo stripslashes($_row->code); ?></textarea>
                                                <div class="pull-right">
                                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEVIDEO"); ?>" >
                                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="tab-pane <?php echo $tab_active2; ?>" id="addfromurl">
                                               <div id="video_add_from_url" class="pull-left">
                                            <?php echo JText::_('GURU_MEDIATYPEVIDEO').' '.JText::_('GURU_MEDIATYPEURLURL'); ?>
                                                &nbsp;&nbsp;
                                                <input type="text" value="<?php echo $_row->url; ?>" name="url_v" id="url_v" class="input-xlarge" onPaste="javascript:change_radio_url()" onblur="javascript:addVideoFromUrl('<?php echo JURI::root(); ?>');" />
                                                <div class="clearfix"></div>
                                                
                                                <div class="form-helper pull-left">
                                                    <?php echo JText::_("COM_GURU_VIDEO_SUPPORTED"); ?>
                                                    <div style="margin-top: 5px">
                                                        <span><img alt="YouTube" title="YouTube" src="<?php echo JURI::root()."components/com_guru/images/video_support/"; ?>youtube.png"></span>
                                                        <span><img alt="Yahoo Video" title="Yahoo Video" src="<?php echo JURI::root()."components/com_guru/images/video_support/"; ?>yahoo.png"></span>
                                                        <span><img alt="MySpace Video" title="MySpace Video" src="<?php echo JURI::root()."components/com_guru/images/video_support/"; ?>myspace.png"></span>
                                                        <span><img alt="Flickr" title="Flickr" src="<?php echo JURI::root()."components/com_guru/images/video_support/"; ?>flickr.png"></span>
                                                        <span><img alt="Vimeo" title="Vimeo" src="<?php echo JURI::root()."components/com_guru/images/video_support/"; ?>vimeo.png"></span>
                                                        <span><img alt="Metacafe" title="Metacafe" src="<?php echo JURI::root()."components/com_guru/images/video_support/"; ?>metacafe.png"></span>
                                                        <span><img alt="Blip.tv" title="Blip.tv" src="<?php echo JURI::root()."components/com_guru/images/video_support/"; ?>bliptv.png"></span>
                                                        <span><img alt="Dailymotion" title="Dailymotion" src="<?php echo JURI::root()."components/com_guru/images/video_support/"; ?>dailymotion.png"></span>
                                                        <span><img alt="Break" title="Break" src="<?php echo JURI::root()."components/com_guru/images/video_support/"; ?>break.png"></span>
                                                        <span><img alt="Live Leak" title="Live Leak" src="<?php echo JURI::root()."components/com_guru/images/video_support/"; ?>liveleak.png"></span>
                                                        <span><img alt="Viddler" title="Viddler" src="<?php echo JURI::root()."components/com_guru/images/video_support/"; ?>viddler.png"></span>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div>
                                                    <input type="button" class="btn btn-success" value="<?php echo JText::_("COM_GURU_GET_VIDEO_NFO"); ?>" name="video-info" onclick="javascript:addVideoFromUrl('<?php echo JURI::root(); ?>');" />
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="pull-left" id="progress-video-upload" style="display:none; clear: both; margin-top:10px;">
                                                    <div class="progress progress-success progress-striped active input-large">
                                                        <div class="bar" style="width: 100%"></div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div id="video_details">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="tab-pane <?php echo $tab_active3; ?>" id="upload">
                                             <div id="video_add_from_upload" class="pull-left">
                                                <?php echo JText::_("GURU_LOCAL"); ?>
                                                &nbsp;&nbsp;
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_LOCAL"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                                <div class="alert error"><?php echo JText::_("GURU_ACCEPTED_FILE_TYPES").": flv, swf, mov, wmv, mp4 and divx"; ?></div>
                                                <div class="clearfix"></div>
                                                <div id="videoUploader"></div>
                                                <div class="clearfix"></div>
                                                <?php
                                                    echo '<strong>'.$_row->local.'</strong><br/>';
                                                ?>
                                                <?php echo $maxUpload; ?>
                                            </div>
                                        </div>
                                        
                                         <div class="tab-pane <?php echo $tab_active4; ?>" id="selectfile">
                                                 <div id="video_add_from_existings" class="pull-left">
                                                <?php echo JText::_('GURU_MEDIATYPECHOOSE').' '.JText::_('GURU_MEDIATYPEVIDEO_');?><br/>
                                                <?php
                                                    $now_selected = $this->now_selected_media ($_row->id);
            
                                                    if(isset($now_selected)&&($now_selected!='')){
                                                        echo str_replace($now_selected.'"',$now_selected.'" selected="selected"',$display_list_of_files); 
                                                    }
                                                    else{
                                                        echo $display_list_of_files;
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div>
                                            <?php									
                                                if($_row->option_video_size == NULL){
                                                    $_row->option_video_size = "0";
                                                }
                                                $default_size = $configuration->default_video_size;
                                                $default_zize_array = explode("x", $default_size);
                                                $dafault_size_height = $default_zize_array["0"];
                                                $dafault_size_width = $default_zize_array["1"];
                                            ?>
                                            <div class="clearfix"></div>
                                             <input type="hidden" id="width_v"  value="<?php echo $dafault_size_width;?>" name="width_v" />
                                             <input type="hidden" id="height_v"  value="<?php echo $dafault_size_height;?>" name="height_v"/>
                                            <input type="hidden" id="check" id="video_size" name="video_size" value="" />
                                            </div>
                                        </div>
                                        <input id="source_v" type="hidden" value="<?php if($_row->source==''){ echo 'code'; } else{ echo $_row->source;} ?>" name="source_v"/>
                                    </div>
                             	</div>
                           </div>     
                        <!--  VIDEO - END  ---->
                        
                        
                        <?php //-------- AUDIO - BEGIN 
						$display_list_of_dir = $lists['audio_dir'];
						$display_list_of_files = $lists['audio_url'];
						$folder_of_files = $configuration->audioin;
						if(isset($_row->type) && $_row->type=='audio')
							$stylea = 'style="table-row;"';
						else
							$stylea = 'style="display:none;"';	
						?>
                        <div class="control-group" id="audioblock" <?php echo $stylea; ?>>
                            <label class="control-label">
								<?php echo JText::_('GURU_MEDIATYPEAUDIOS'); ?> <font color="#ff0000">*</font>
                            </label>
                            <div class="controls">
                            	<div class="pull-left clearfix">
                                	<?php 
                                        $tab_active1a = "active";
                                        $tab_active2a = "";
                                        $tab_active3a = "";
                                        $tab_active4a = "";
                                        
                                         if($_row->source=='code'){
                                            $tab_active1a = "active";
                                            $tab_active2a = "";
                                            $tab_active3a = "";
                                            $tab_active4a = "";
                                         }
                                         if($_row->source=='url'){
                                            $tab_active1a = "";
                                            $tab_active2a = "active";
                                            $tab_active3a = "";
                                            $tab_active4a = "";
                                         }
                                         if($_row->source=='local' && $_row->uploaded_tab==0){
                                            $tab_active1a = "";
                                            $tab_active2a = "";
                                            $tab_active3a = "active";
                                            $tab_active4a = "";
                                         }
                                         if($_row->source=='local' && $_row->uploaded_tab==1){
                                            $tab_active1a = "";
                                            $tab_active2a = "";
                                            $tab_active3a = "";
                                            $tab_active4a = "active";
                                         }
                                    ?>
                                    <ul class="nav nav-tabs">
                                        <li onclick="makeActiveA('embeded'); return false;" id="lia_embeded" class="<?php echo $tab_active1a; ?>"><a href="#embeded_a" data-toggle="tab"><?php echo JText::_('GURU_EMBEDED_CODE');?></a></li>
                                        <li onclick="makeActiveA('addfromurl'); return false;" id="lia_addfromurl" class="<?php echo $tab_active2a; ?>"><a href="#addfromurl_a" data-toggle="tab"><?php echo JText::_('GURU_ADD_FROM_URL_MEDIA');?></a></li>
                                        <li onclick="makeActiveA('upload'); return false;" id="lia_upload" class="<?php echo $tab_active3a; ?>"><a href="#upload_a" data-toggle="tab"><?php echo JText::_('GURU_UPLOAD_FILE');?></a></li>
                                        <li onclick="makeActiveA('selectfile'); return false;" id="lia_selectfile"  class="<?php echo $tab_active4a; ?>"><a href="#selectfile_a" data-toggle="tab"><?php echo JText::_('GURU_SELECT_EXISTING_FILE');?></a></li>
                                    </ul>
                                    <div class="tab-content">
                                    	<div class="tab-pane <?php echo $tab_active1a; ?>" id="embeded_a">
                                            <div class="pull-left">
                                                 <?php echo JText::_('GURU_MEDIATYPEAUDIO').' '.JText::_('GURU_MEDIATYPECODE');?>
                                                &nbsp;&nbsp;
                                                <textarea cols="35" name="code_a"><?php echo stripslashes($_row->code); ?></textarea>
                                                <div class="pull-right">
                                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEAUDIOS"); ?>" >
                                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane <?php echo $tab_active2a; ?>" id="addfromurl_a">
                                        	 <div class="pull-left">
												<?php echo JText::_('GURU_MEDIATYPEAUDIO').' '.JText::_('GURU_MEDIATYPEURLURL');?>
                                                &nbsp;&nbsp;
                                                <input type="text" class="input-xlarge" value="<?php echo $_row->url;?>" name="url_a"/>
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEAUDIO"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="tab-pane <?php echo $tab_active3a; ?>" id="upload_a">
                                        	  <div class="pull-left">
												<?php echo JText::_("GURU_LOCAL"); ?>
                                                &nbsp;&nbsp;
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_AUDIO_LOCAL"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                                <div class="alert error"><?php echo JText::_("GURU_ACCEPTED_FILE_TYPES").": mp3 and wma"; ?></div>
                                                <div class="clearfix"></div>
                                                <div id="audioUploader"></div>
                                                <div class="clearfix"></div>
                                                <?php
                                                    echo $now_selected;
                                                ?>
                                                <br/>
                                                <?php echo $maxUpload; ?>
                                            </div>
                                        </div>
                                        <div class="tab-pane <?php echo $tab_active4a; ?>" id="selectfile_a">
                                        	<div class="pull-left">
												<?php echo JText::_('GURU_MEDIATYPECHOOSE').' '.JText::_('GURU_MEDIATYPEAUDIO_');?><br/>
                                                <div class="clearfix"></div>
                                                <?php echo $display_list_of_files; ?>
                                            
                                                <div class="clearfix"></div>
                                                
                                                <div class="pull-left">
                                                    <div style="float:left; padding-right:20px;">
                                                        <?php //echo JText::_('GURU_MEDIA_SIZE'); ?>
                                                    </div>
                                                    <div style="float:left">
                                                        <input type="hidden" value="<?php if(isset($_row->id)&&($_row->id>0)) {echo $_row->width;} else {echo "250";}?>" name="width_a" id="width_a"/>
                                                        <input type="hidden" value="20" name="height_a"/>
                                                        <?php //echo JText::_('GURU_MEDIA_WIDTH'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   <input id="source_a" value="<?php if($_row->source==''){echo 'code';}else{ echo $_row->source;}?>" type="hidden" name="source_a"/>
                                  </div>
                               </div>
                            </div>        
                        <!--  AUDIO - END  ---->
                        
                       
                        
                        <?php //-------- DOCUMENTS - BEGIN 
						$display_list_of_dir = $lists['docs_dir'];
						$display_list_of_files = $lists['docs_url'];
						$folder_of_files = $configuration->docsin;
						if(isset($_row->type) && $_row->type=='docs'){
							$styled = 'style="table-row;"';
						}
						else{
							$styled = 'style="display:none;"';
						}
						?>
                        <div class="control-group" id="docsblock" <?php echo $styled; ?>>
                            <label class="control-label">
								<?php echo JText::_('GURU_MEDIATYPEDOCSS'); ?> <font color="#ff0000">*</font>
                            </label>
                            <div class="controls">
                            	<div class="pull-left clearfix">
                                <?php 
                                        $tab_active2d = "active";
                                        $tab_active3d = "";
                                        $tab_active4d = "";
                                        
                                        
                                         if($_row->source=='url'){
                                            $tab_active2d = "active";
                                            $tab_active3d = "";
                                            $tab_active4d = "";
                                         }
                                         if($_row->source=='local' && $_row->uploaded_tab==0){
                                            $tab_active2d = "";
                                            $tab_active3d = "active";
                                            $tab_active4d = "";
                                         }
                                         if($_row->source=='local' && $_row->uploaded_tab==1){
                                            $tab_active2d = "";
                                            $tab_active3d = "";
                                            $tab_active4d = "active";
                                         }
                                    ?>
                                    <ul class="nav nav-tabs">
                                    	<li onclick="makeActiveD('addfromurl'); return false;" id="lid_addfromurl" class="<?php echo $tab_active2d; ?>"><a href="#addfromurl_d" data-toggle="tab"><?php echo JText::_('GURU_ADD_FROM_URL_MEDIA');?></a></li>
                                        <li onclick="makeActiveD('upload'); return false;" id="lid_upload" class="<?php echo $tab_active3d; ?>"><a href="#upload_d" data-toggle="tab"><?php echo JText::_('GURU_UPLOAD_FILE');?></a></li>
                                        <li onclick="makeActiveD('selectfile'); return false;" id="lid_selectfile"  class="<?php echo $tab_active4d; ?>"><a href="#selectfile_d" data-toggle="tab"><?php echo JText::_('GURU_SELECT_EXISTING_FILE');?></a></li>
                                    </ul>
                                    <div class="tab-content">
                                    	<div class="tab-pane <?php echo $tab_active2d; ?>" id="addfromurl_d">
                                            <div class="pull-left">
                                                  <?php echo JText::_('GURU_MEDIATYPEDOCS').' '.JText::_('GURU_MEDIATYPEURLURL');?>
                                                &nbsp;&nbsp;
                                                <input type="text" class="input-xlarge" value="<?php echo $_row->url;?>" name="url_d"/>
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEDOCS"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="tab-pane <?php echo $tab_active3d; ?>" id="upload_d">
                                            <div class="pull-left">
                                            	<?php echo JText::_("GURU_LOCAL"); ?>
                                                &nbsp;&nbsp;
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_AUDIO_LOCAL"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                                <div class="alert error"><?php echo JText::_("GURU_ACCEPTED_FILE_TYPES").": txt and pdf"; ?></div>
                                                <div class="clearfix"></div>
                                                <div id="docUploader"></div>
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIA_UPLOAD"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                                <?php echo $now_selected; ?>
                                                <div class="clearfix"></div>
                                                <?php echo $maxUpload; ?>
                                            </div>
                                        </div>
                                        <div class="tab-pane <?php echo $tab_active4d; ?>" id="selectfile_d">
                                            <div class="pull-left">
                                            	<?php echo JText::_('GURU_MEDIATYPECHOOSE').' '.JText::_('GURU_MEDIATYPEDOCS_');?>
                                                <div class="clearfix"></div>
                                                <?php echo $display_list_of_files; ?>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                	<div class="pull-left" id="whdoc" <?php if(isset($sel_link)){ echo 'style="display:none;"';}?>>
                                        <div style="float:left; padding-right:20px;">
                                            <?php //echo JText::_('GURU_MEDIA_SIZE'); ?>
                                        </div>
                                        <div style="float:left">
                                            <div>
                                                <div style="float:left;">
                                                    <?php
                                                        if(intval($_row->width) == 0){
                                                            $_row->width = "600";
                                                        }
                                                        
                                                        if(intval($_row->height) == 0){
                                                            $_row->height = "800";
                                                        }
                                                    ?>
                                                
                                                    <input type="hidden" value="<?php echo $_row->width; ?>" name="width" id="width"/> 
                                                    <input type="hidden"  value="<?php echo $_row->height; ?>" name="height" id="height"/>
                                                    &nbsp;
                                                    <?php //echo JText::_('GURU_MEDIA_WIDTH_HEIGHT'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            	<input id="source_d" value="<?php if($_row->source==''){echo 'url';}else{ echo $_row->source;}?>" type="hidden" name="source_d"/>
							</div>
                        </div>
                    </div>

                        <!--  DOCUMENTS - END  ---->
                        
                        
                        <?php //-------- URL - BEGIN 
						if(isset($_row->type) && $_row->type=='url'){
							$styleu = 'style="table-row;"';
						}
						else{
							$styleu = 'style="display:none;"';
						}
						?>
                        
                        <div id="urlblock" <?php echo $styleu; ?>>
                        
                            <div class="control-group">
                                <label class="control-label">
                                    <?php echo JText::_('GURU_MEDIATYPEURL_'); ?>:
                                </label>
                                <div class="controls">
                                    <input type="text" class="input-xlarge" value="<?php if (isset($_row->url)){echo $_row->url;}else{ echo "http://";}?>" name="url"/>
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEURL_"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                               </div>
                            </div>
                               
                            <div class="control-group"> 
                                <label class="control-label">
                                    <?php echo JText::_('GURU_MEDIA_DISPL_DOC'); ?>:
                                </label>
								<div class="controls">
                                    <select name="display_as2" >
                                        <option value="wrapper"><?php echo JText::_('GURU_MEDIA_DISPL_DOC_W'); ?></option>
                                        <option value="link" <?php if($_row->type=='url' && $_row->width==1) echo 'selected = "selected"'; ?>><?php echo JText::_('GURU_MEDIA_DISPL_DOC_L'); ?></option>
                                    </select>
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DISPL_DOC"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
								</div>
                            </div>
                            
						</div>
                        <?php //-------- URL - END ?>
                        
                        
                        <?php //-------- Article - BEGIN 
						if(isset($_row->type) && $_row->type=='Article')
							$styleu = 'style="table-row;"';
						else
							$styleu = 'style="display:none;"';	
						?>
                        <div class="control-group" id="artblock" <?php echo $styleu; ?>>
                        	<label class="control-label">
								<?php echo JText::_('GURU_MEDIATYPEARTICLE_'); ?>:
                            </label>
                            <div class="controls">
                            	<?php
									if($_row->id !=""){
										$db = JFactory::getDBO();
										$sql = "SELECT code FROM `#__guru_media` WHERE type='Article' and id=".$_row->id;
										$db->setQuery($sql);
										$guru_articleid = $db->loadColumn();
									}  
									if(isset($code) && $code !=0){
										$sql = "SELECT title FROM `#__content` WHERE id=".$guru_articleid["0"];
										$db->setQuery($sql);
										$guru_article_name = $db->loadColumn();
									}	
                                ?>
                                <?php echo $this->displayArticleguru(@$guru_articleid["0"], @$guru_article_name["0"]); ?>	
                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEARTICLE_"); ?>" >
                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                </span>
                            </div>
                        </div>
                        <?php //-------- Article - END ?>
                        
                        
                        <?php //-------- IMAGE - BEGIN 
						if(isset($_row->type) && $_row->type=='image'){
							$stylei = 'style="table-row;"';
						}
						else{
							$stylei = 'style="display:none;"';
						}	
						?>
                        <div class="control-group" id="imageblock" <?php echo $stylei; ?>>
                        	<label class="control-label">
								<?php echo JText::_('GURU_MEDIATYPEIMAGE');?>:<font color="#ff0000">*</font>
                            </label>
                            <div class="controls">
                            	<div id="imageUploader" class="pull-left g_margin_bottom"></div>
								<span class="editlinktip hasTip g_margin_left" title="<?php echo JText::_("GURU_TIP_MEDIATYPEIMAGE"); ?>" >
									<img border="0" src="components/com_guru/images/icons/tooltip.png">
                                </span>
                                <div class="clearfix"></div>
                                <div class="alert alert-danger">
                                	<?php echo JText::_('GURU_ALLOWED_IMAGES_EXT');?>
                                </div>
                                <div class="clearfix"></div>
                                <?php
									echo $maxUpload;
                                ?>
                                <div class="clearfix"></div>
                                <div style="float:left; padding-right:20px;">
									<?php //echo JText::_('GURU_GEN_IM_FIS');?>
								</div>
                                <?php 
                                    $media_fullpx = 200;
                                    $media_prop = 'w';
                                    $is_image = 0;				
                                    if($_row->width>0 && $_row->height == 0){
                                        $media_fullpx = $_row->width;
                                        $media_prop = 'w';
                                        $is_image = 1;
                                    }	
                                    if($_row->height >0 && $_row->width == 0){
                                        $media_fullpx = $_row->height;					
                                        $media_prop = 'h';
                                        $is_image = 1;
                                    }
                                    if(trim($_row->local)!=""){
                                        $is_image = 1;
                                    }
								?>
                                <div style="float:left;">
                                    <input type="hidden" id="media_fullpx" name="media_fullpx" value="<?php echo $media_fullpx;?>" />
                                    <select name="media_prop" id="media_prop" class="input-small" style="display:none;">
                                        <option value="w" <?php if($media_prop=='w') echo 'selected="selected"'; ?>><?php  echo JText::_('GURU_PROPW');?></option>
                                        <option value="h" <?php if($media_prop=='h') echo 'selected="selected"'; ?>><?php  echo JText::_('GURU_PROPH');?></option>
                                    </select>
                                </div>
                                <div style="float:left;">
                                    <input type="hidden" id="is_image" name="is_image" value="<?php echo $is_image;?>" />	
                                </div>
                                <div class="clearfix"></div>
                                <?php echo JText::_('GURU_PRODCIMG');?>:
                                <?php
                                    if(isset($_row->local) && trim($_row->local) != ""){
                                        require_once(JPATH_SITE."/components/com_guru/helpers/helper.php");
                                        $helper = new guruHelper();
                                        $width = $_row->width;
                                        $height = $_row->height;
                                        $new_size = "";
                                        $type = "";
                                        if(intval($width) != 0){
                                            $new_size = $width;
                                            $type = "w";
                                        }
                                        else{
                                            $new_size = $height;
                                            $type = "h";
                                        }
                                        $q = "SELECT * FROM #__guru_config WHERE id = '1' ";
                                        $db->setQuery( $q );
                                        $configs = $db->loadObject();
                                        
                                        $helper->createThumb($_row->local, $configs->imagesin.'/media', $new_size, $type);
                                        $media_image = '<img id="view_imagelist23" name="view_imagelist" style="margin:5px;" border="0" alt="" src="'.JURI::root().$configuration->imagesin."/media/thumbs".$_row->local.'" />';
                                    }
                                    else{
										$media_image = '<img id="view_imagelist23" name="view_imagelist" style="margin:5px;" border="0" alt="" src="'.JURI::base().'components/com_guru/images/blank.png" />';
									}
                                    // generating thumb image - stop	
											
								?>
                          
								<?php echo $media_image; ?>
                                <input type="hidden" id="image" name="image" value="<?php echo $_row->local;?>" />
                            </div>
                        </div>
                        <?php //-------- IMAGE - END ?>
                        
                        <?php //-------- TEXT - BEGIN 
						if(isset($_row->type) && $_row->type=='text'){
							$stylet = 'style="block;"';
						}
						else{
							$stylet = 'style="display:none;"';
						}
						?>
                        <div class="control-group" id="textblock" <?php echo $stylet; ?>>
                        	<label class="control-label">
								<?php echo JText::_('GURU_MEDIATYPETEXT'); ?>:<font color="#ff0000">*</font>
                            </label>
                            <div class="controls">
                            	<?php
									$doc = JFactory::getDocument();
									$doc->addScript(JURI::root().'components/com_guru/js/redactor.min.js');
									$doc->addStyleSheet(JURI::root().'components/com_guru/css/redactor.css');
								?>
                                <textarea id="text" name="text" class="useredactor" style="width:70%; height:100px;"><?php echo $_row->code; ?></textarea>
                                <?php
									$upload_script = 'window.addEvent( "domready", function(){
														jQuery(".useredactor").redactor({
															 buttons: [\'bold\', \'italic\', \'underline\', \'link\', \'alignment\', \'unorderedlist\', \'orderedlist\']
														});
														jQuery(".redactor_useredactor").css("height","300px").css("width", "100%");
													  });';
									$doc->addScriptDeclaration($upload_script);
								?>
                            </div>
                        </div>
                        <?php //-------- TEXT - END ?>
                        
                        <?php
                        	//-------- FILE - BEGIN 
                    		$display_list_of_dir = $lists['files_dir'];
							$display_list_of_files = $lists['files_url'];
							
							if(isset($_row->type) && $_row->type=='file'){
								$stylef = 'style="table-row;"';
							}
							else{
								$stylef = 'style="display:none;"';
							}
						?>
                       
                        <div class="control-group" id="fileblock" <?php echo $stylef; ?>>
                            <label class="control-label">
								<?php echo JText::_('GURU_MEDIATYPEFILES'); ?> <font color="#ff0000">*</font>
                            </label>
                            <div class="controls">
                            	<div class="pull-left clearfix">
                                      <?php 
                                        $tab_active2f = "active";
                                        $tab_active3f = "";
                                        $tab_active4f = "";
                                        
                                        
                                         if($_row->source=='url'){
                                            $tab_active2f = "active";
                                            $tab_active3f = "";
                                            $tab_active4f = "";
                                         }
                                         if($_row->source=='local' && $_row->uploaded_tab==0){
                                            $tab_active2f = "";
                                            $tab_active3f = "active";
                                            $tab_active4f = "";
                                         }
                                         if($_row->source=='local' && $_row->uploaded_tab==1){
                                            $tab_active2f = "";
                                            $tab_active3f = "";
                                            $tab_active4f = "active";
                                         }
                                    ?>
                                    <ul class="nav nav-tabs">
                                    	<li onclick="makeActiveF('addfromurl'); return false;" id="lif_addfromurl" class="<?php echo $tab_active2f; ?>"><a href="#addfromurl_f" data-toggle="tab"><?php echo JText::_('GURU_ADD_FROM_URL_MEDIA');?></a></li>
                                        <li onclick="makeActiveF('upload'); return false;" id="lif_upload" class="<?php echo $tab_active3f; ?>"><a href="#upload_f" data-toggle="tab"><?php echo JText::_('GURU_UPLOAD_FILE');?></a></li>
                                        <li onclick="makeActiveF('selectfile'); return false;" id="lif_selectfile"  class="<?php echo $tab_active4f; ?>"><a href="#selectfile_f" data-toggle="tab"><?php echo JText::_('GURU_SELECT_EXISTING_FILE');?></a></li>
                                    </ul>
                                    <div class="tab-content">
                                    	<div class="tab-pane <?php echo $tab_active2f; ?>" id="addfromurl_f">
                                        	<?php echo JText::_('GURU_FILE').' '.JText::_('GURU_MEDIATYPEURLURL');?>
                                            &nbsp;&nbsp;
                                            <input type="text" onKeyPress="javascript:change_radio_url();" onPaste="javascript:change_radio_url()" class="input-xlarge" value="<?php echo $_row->url;?>" id="url_f" name="url_f"  onChange="javascript:hide_hidden_row();" onmouseout="doPreview();" on/>
                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_FILE_MEDIATYPEURLURL"); ?>" >
                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                            </span>
                                            <?php 
                                                if($_row->source=="url" && $_row->url!=""){
                                            ?>
                                                    <div id="filePreview">
                                                        <a class="a_guru" href="<?php echo $_row->url; ?>"><?php echo JText::_("GURU_DOWNLOAD"); ?></a>
                                                    </div>
                                            <?php	
                                                } 
                                            ?>
                                            <div id="filePreview"></div>
                                        </div>
                                        <div class="tab-pane <?php echo $tab_active3f; ?>" id="upload_f">
											<?php echo JText::_("GURU_LOCAL_FILE"); ?>
                                            <div class="alert error"><?php echo JText::_("GURU_ACCEPTED_FILE_TYPES").": zip and exe"; ?></div>
                                            <div id="uploadblock">
                                                <div id="fileUploader"></div>
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_FILE_MEDIA_UPLOAD"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                            <div class="clearfix"></div>
                                            <?php
                                                echo $now_selected."<br/>";
                                                echo $maxUpload;
                                            ?>
                                            <br/>
                                            <?php 
                                                if($_row->source=="local" && $_row->local!=""){
                                            ?>
                                                    <a class="a_guru" href="<?php echo JURI::root().trim($configuration->filesin)."/".$_row->local; ?>" id="filePreviewList">
                                                        <?php echo JText::_("GURU_DOWNLOAD"); ?>
                                                    </a>
                                            <?php 
                                                }
                                                else{
                                            ?>
                                                    <a class="a_guru" href="#" style="visibility:hidden" id="filePreviewList">
                                                        <?php echo JText::_("GURU_DOWNLOAD"); ?>
                                                    </a>
                                            <?php
                                                }
                                            ?>
                                    </div>
                                    <div class="tab-pane <?php echo $tab_active4f; ?>" id="selectfile_f">
										<?php echo JText::_('GURU_MEDIATYPECHOOSE').' '.JText::_('GURU_MEDIATYPEFILE_');?>
                                        <br/>
                                        <?php echo $display_list_of_files; ?>
                                        <div class="clearfix"></div>
                                        <div id="filesFolder"><?php echo JURI::root().$configuration->filesin; ?></div>
                                        
                                        <?php 
                                            if($_row->source=="local" && $_row->local!=""){
                                        ?>
                                                <a class="a_guru" href="<?php echo JURI::root().trim($configuration->filesin)."/".$_row->local; ?>" id="filePreviewList">
                                                    <?php echo JText::_("GURU_DOWNLOAD"); ?>
                                                </a>
                                        <?php 
                                            }
                                            else{
                                        ?>
                                                <a class="a_guru" href="#" style="visibility:hidden" id="filePreviewList">
                                                    <?php echo JText::_("GURU_DOWNLOAD"); ?>
                                                </a>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                  </div>  
                                  <input id="source_f" value="<?php if($_row->source==''){echo 'url';}else{ echo $_row->source;}?>" type="hidden" name="source_f"/>
                                </div>
                            </div>
                        </div>

                        <!--  FILE - END  ---->
                        	
                            <input type="hidden" name="option" value="com_guru" />
                            <input type="hidden" name="task" value="edit" />
                            <input type="hidden" name="id" value="<?php echo $_row->id;?>" />
                            <input type="hidden" name="controller" value="guruAuthor" />
                            <input type="hidden" name="was_uploaded" id="was_uploaded" value="1" />
                            <input type="hidden" name="uploaded_tab" id="uploaded_tab" value="-1" />
                            <input type="hidden" name="tmpl" id="tmpl" value="<?php echo $tmpl; ?>" />
                            <?php
                            	$user = JFactory::getUser();
								$user_id = $user->id;
							?>
                            <input type="hidden" name="author" id="author" value="<?php echo intval($user_id); ?>" />
                            
                            <?php
                                if($tmpl == "component"){
                                    $mediaval1	= JRequest::getVar("med", "");
                                    $mediaval2	= JRequest::getVar("txt", "");
                                    $scr		= JRequest::getVar("scr", "0");
                                    $txt		= JRequest::getVar("txt", "0");
                        
                                    $mediatext = "";
                                    if($mediaval1 != ""){
                                        $mediatext = "med";
                                    }
                                    elseif($mediaval2 != ""){
                                        $mediatext = "txt";
                                    }
                                    
                                    $mediatextvalue = "";
                                    if($mediaval1 != ""){
                                        $mediatextvalue = $mediaval1;
                                    }
                                    elseif($mediaval2 != ""){
                                        $mediatextvalue = $mediaval2;
                                    }
                                    
                                    $cid = JRequest::getVar("cid", "0");
                                    $progrid = JRequest::getVar("progrid", "0");
                                    $module = JRequest::getVar("module", "0");
                            ?>
                                    <input type="hidden" name="action" value="addmedia" id="action" />
                                    <input type="hidden" name="mediatext" value="<?php echo $mediatext; ?>" id="mediatext" />
                                    <input type="hidden" name="mediatextvalue" value="<?php echo $mediatextvalue; ?>" id="mediatextvalue" />
                                    <input type="hidden" name="screen" id="screen"  value="<?php echo intval($scr); ?>" />
                                    <input type="hidden" name="redirect_to" value="<?php echo JURI::root().'index.php?option=com_guru&controller=guruAuthor&tmpl=component&task=editsbox&cid='.intval($cid).'&progrid='.intval($progrid).'&module='.intval($module); ?>" />
                            <?php
                                }
                            ?>
                            <?php
								if($tmpl != "component"){
							?>
									<div class="g_inline_child g_margin_bottom">
											<input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE"); ?>" onclick="Joomla.submitbutton('apply_media');" />
											<input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE_AND_CLOSE"); ?>" onclick="Joomla.submitbutton('save_media');" />
											<input type="button" class="btn btn-inverse" value="<?php echo JText::_("GURU_CLOSE"); ?>" onclick="document.location.href='<?php echo JRoute::_("index.php?option=com_guru&controller=guruAuthor&task=authormymedia&layout=authormymedia"); ?>';" />
									</div>
							<?php
								}
								else{
							?>
									<div class="g_inline_child g_margin_bottom">
											<input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE_AND_CLOSE"); ?>" onclick="Joomla.submitbutton('savesbox');" />
									</div>
							<?php
								}
							?>
                            
                        </form>
                    
				</div>
			</div>
		</div>
	</div>
</div>