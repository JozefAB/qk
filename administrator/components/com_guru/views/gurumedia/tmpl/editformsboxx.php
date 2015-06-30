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
	$mediaval1	= JRequest::getVar("med","","get","string");
	$mediaval2	= JRequest::getVar("txt","","get","string");
	$scr		= JRequest::getVar("scr","0","get","int");
	$txt		= JRequest::getVar("txt","0","get","int");
	
	$data 	= $this->data;
	$_row	= $this->media;
	$lists 	= $_row->lists;

	$nullDate = 0;
	$livesite = JURI::base();	
	$configuration = guruAdminModelguruMedia::getConfig();	
	$editorul  = JFactory::getEditor();
	
	$UPLOAD_MAX_SIZE = @ini_get('upload_max_filesize');
	$max_post 		= (int)(ini_get('post_max_size'));
	$memory_limit 	= (int)(ini_get('memory_limit'));
	$UPLOAD_MAX_SIZE = min($UPLOAD_MAX_SIZE, $max_post, $memory_limit);
	if($UPLOAD_MAX_SIZE == 0) {$UPLOAD_MAX_SIZE = 10;}
	
	$maxUpload = "<font color='#FF0000'>";
	$maxUpload .= JText::_('GURU_MEDIA_MAX_UPL_V_1')." ";
	$maxUpload .= $UPLOAD_MAX_SIZE.'M ';
	$maxUpload .= JText::_('GURU_MEDIA_MAX_UPL_V_2');
	$maxUpload .= "</font>";

	$doc =JFactory::getDocument();
	$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');	
	//$doc->addScript('components/com_guru/js/jquery.noconflict.js');
	//$doc->addScript('components/com_guru/js/jquery.DOMWindow.js');
	//$doc->addScript('components/com_guru/js/open_modal.js');
	//include(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_guru'.DS.'js'.DS.'modal2.js');
	
	include(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_guru'.DS.'helpers'.DS.'createUploader.php');
	JHtml::_('behavior.framework');	
	
?>
	<!--<script type="text/javascript" src="<?php //echo JURI::base().'components/com_guru/views/gurutasks/tmpl/prototype-1.6.0.2.js' ?>"></script>-->
	
	<script type="text/javascript" src="<?php echo JURI::base(); ?>components/com_guru/views/gurumedia/tmpl/js.js"></script>	
	<script language="javascript" type="text/javascript">
		function changefolder() {								
			submitbutton('changes');
		}
		
		//Joomla.submitbutton2 = function(pressbutton){
		function submitbutton2(pressbutton) {
			var form = document.adminForm;
			<?php echo $editorul->save( 'text' );?>
			if(pressbutton=='savesbox'){
				if(form['name'].value == ""){
					alert( "<?php echo JText::_("GURU_MEDIA_JS_NAME_ERR");?>" );
				} 
				else if(form['type'].value == 0){
					alert( "<?php echo JText::_("GURU_MEDIA_JS_TYPE_ERR");?>" );
				}
				else if(form['type'].value == 'image' && form['is_image'].value == 0){
					alert( "<?php echo JText::_("GURU_MEDIA_JS_IMAGE_ERR");?>" );
				}
				else if (form['type'].value == 'text' && document.getElementById('text').value == '' ){
					alert( "<?php echo JText::_("GURU_MEDIA_JS_TEXT_ERR");?>" );
				}
				else if(form['type'].value == 'video'){
					if(document.getElementById("source_code_v").checked == true){
						if(form['code_v'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else if(document.getElementById("source_url_v").checked == true){
						if(form['url_v'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else if(document.getElementById("source_local_v2").checked == true){
						if(form['localfile'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else{
						alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
						return false;
					}
					submitform( pressbutton );
				}
				else if(form['type'].value == 'audio'){
					if(document.getElementById("source_code_a").checked == true){
						if(form['code_a'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else if(document.getElementById("source_url_a").checked == true){
						if(form['url_a'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else if(document.getElementById("source_local_a2").checked == true){
						if(form['localfile_a'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else{
						alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
						return false;
					}
					submitform( pressbutton );
				}
				else if(form['type'].value == 'docs'){
					if(document.getElementById("source_url_d").checked == true){
						if(form['url_d'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else if(document.getElementById("source_local_d2").checked == true){
						if(form['localfile_d'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else{
						alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
						return false;
					}
					submitform( pressbutton );
				}
				else if(form['type'].value == 'file'){
					if(document.getElementById("source_url_f").checked == true){
						if(document.getElementById("url_f").value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else if(document.getElementById("source_local_f2").checked == true){
						if(document.getElementById("localfile_f").value == "" || document.getElementById("localfile_f").value == "root"){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else{
						alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
						return false;
					}
					submitform( pressbutton );
				}
				else{
					submitform( pressbutton );					
				}
			}
			else{	
				submitform( pressbutton );
			}	
		}
				
		function store_sessions(scr,ldb,dt1,dt2,dt3,dt4,dt5,dt6,dm1,dm2,dm3,dm4,dm5,dm6,dm7) {
			var url = 'components/com_guru/views/gurutasks/tmpl/store_sessions.php?ldb='+ldb+'&scr='+scr+'&dt='+dt1+','+dt2+','+dt3+','+dt4+','+dt5+','+dt6+'&dm='+dm1+','+dm2+','+dm3+','+dm4+','+dm5+','+dm6+','+dm7;
			new Ajax.Request(url, {
			  method: 'get',
			  asynchronous: 'true',
			  onSuccess: function(transport) {
			  },
			  onCreate: function(){
			  }
			});
			return true;
		}	
		
		function SelectArticleg(id, title, object){ 
			document.getElementById('articleid').value = id;
			document.getElementById('article_name').value = title;	
			document.getElementById('sbox-btn-close').click();
		}	
		
	</script>
<?php 
	$document = JFactory::getDocument();
	$document->addStyleSheet("components/com_guru/css/ytb.css"); 

	$action = JRequest::getVar("action", "");

?>	
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<table width="100%">
		<tr>
			<td width="20%">&nbsp;</td>
			<td>
				<input class="btn btn-success pull-right" border="0" type="button" name="savesbox" id="savesbox" value="<?php echo JText::_("GURU_SAVE_PROGRAM_BTN"); ?>" onClick="javascript:submitbutton2('savesbox');" />
			</td>
		</tr>
	</table>
	<fieldset class="adminform">
		<div class="well"><?php if(isset($_row->id)) {echo  JText::_('GURU_MEDIADET_EDIT');} else{echo  JText::_('GURU_MEDIADET_NEW');} ?></div>
	<table border="0" width="100%" class="adminform">
		<?php 
		if($txt==1){ ?>
			<input type="hidden" name="type" value="text" />
		<?php 
		}
		else{ 
			if($action == "addtext"){
				$_row->type='text';
				echo '<input type="hidden" name="type" value="text" />';
			}
			else{
			?>	
			<tr>
				<td width="20%"><?php echo JText::_('GURU_TYPE'); ?>:<font color="#ff0000">*</font> </td>
				<td width="80%">
					<?php
						echo $lists['type']; 
					?>
				</td>
			</tr>
		<?php }
		}
		?>
		<tr>
			<td width="20%" nowrap> <?php echo JText::_('Name');?>:<font color="#ff0000">*</font> </td>
			<td width="80%">
				<input class="formField" type="text" name="name" id="name" size="60" value="<?php echo str_replace('"', "&quot;", $_row->name); ?>">
                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIA_NAME"); ?>" >
					<img border="0" src="components/com_guru/images/icons/tooltip.png">
				</span>
                &nbsp;&nbsp;
                <input type="checkbox" value="1" name="hide_name" <?php if($_row->hide_name == 1){ echo 'checked="checked"'; } ?> />
                <span class="lbl"></span>
                <?php echo JText::_("LM_HIDE_NAME"); ?>
			</td>
		</tr>
		<tr>
			<td width="20%" nowrap> <?php echo JText::_('GURU_CATEGORY');?>:<font color="#ff0000">*</font> </td>
			<td width="80%">
				<?php echo $this->parentCategory($_row->category_id);?>
			</td>
		</tr>
		<tr>
			<td width="20%"><?php echo JText::_('GURU_INSTR');?>:</td>
			<td width="80%">
				<textarea class="formField" type="text" name="instructions" rows="2" cols="60" ><?php echo stripslashes($_row->instructions); ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="20%"><?php echo JText::_('GURU_SHOW_INSTRUCTION');?>:</td>
			<td width="80%">
				<select name="show_instruction" id="show_instruction">
					<option value="0" <?php if($_row->show_instruction == "0"){echo 'selected="selected"'; } ?> ><?php echo JText::_("GURU_SHOW_ABOVE"); ?></option>
					<option value="1" <?php if($_row->show_instruction == "1"){echo 'selected="selected"'; } ?> ><?php echo JText::_("GURU_SHOW_BELOW"); ?></option>
					<option value="2" <?php if($_row->show_instruction == "2"){echo 'selected="selected"'; } ?> ><?php echo JText::_("GURU_DONT_SHOW"); ?></option>
				</select>
			</td>
		</tr>
	
	
		<?php
			$display_list_of_dir = $lists['video_dir'];
			$display_list_of_files = $lists['video_url'];
			$folder_of_files = $configuration->videoin;
			$edit_video = 'style="display:none;"';	
			if($_row->type=='video'){
				$edit_video = 'style=""';
			}
		?>
	
		<tr id="videoblock" <?php echo $edit_video; ?>>
        	<td width="20%" valign="middle">
				<?php echo JText::_('GURU_MEDIATYPEVIDEO');?>:<font color="#ff0000">*</font> 
            </td>
			<td width="80%">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-left:-8px;">
					<tr>						
						<td>
							<?php echo JText::_('GURU_MEDIATYPEVIDEOS'); ?><br/>
							<table cellspacing="0" cellpadding="5" border="0" width="100%">
								<tbody>
									<tr bgcolor="#eeeeee" valign="top" id="code_of_file">
										<td width="5%">
											<input id="source_code_v" <?php if($_row->source=='code') echo 'checked="checked"';?> type="radio" value="code" name="source_v"/>
											<span class="lbl"></span>
										</td>
										<td width="28%"><?php echo JText::_('GURU_MEDIATYPEVIDEO').' '.JText::_('GURU_MEDIATYPECODE');?></td>
										<td width="67%"><textarea cols="35" name="code_v" onKeyPress="javascript:change_radio_code()" onPaste="javascript:change_radio_code()"><?php echo stripslashes($_row->code); ?></textarea></td>
									</tr>
									<tr bgcolor="#ffffff" valign="top">
										<td width="5%">
											<input id="source_url_v" type="radio" <?php if($_row->source=='url') echo 'checked="checked"';?> value="url" name="source_v" onChange="javascript:hide_hidden_row();"/>
											<span class="lbl"></span>
										</td>
										<td width="28%"><?php echo JText::_('GURU_MEDIATYPEVIDEO').' '.JText::_('GURU_MEDIATYPEURLURL');?></td>
										<td width="67%">
                                        	<input type="text" value="<?php echo $_row->url; ?>" name="url_v" id="url_v" size="40" onPaste="javascript:change_radio_url()" onblur="javascript:addVideoFromUrl('<?php echo JURI::root(); ?>');" />
                                            <br/>
                                            <div style="padding-top:5px">
                                                <input type="button" class="btn btn-success" value="<?php echo JText::_("COM_GURU_GET_VIDEO_NFO"); ?>" name="video-info" onclick="javascript:addVideoFromUrl('<?php echo JURI::root(); ?>');" />
                                            </div>
                                            
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
                                            <div class="control-group pull-left" id="progress-video-upload" style="display:none; clear: both;">
                                                <label class="control-label" for="inputEmail">
                                                </label>
                                                <div class="controls">
                                                    <div class="progress progress-success progress-striped active input-large">
                                                        <div class="bar" style="width: 100%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="control-group pull-left">
                                                <label class="control-label" for="inputEmail">
                                                </label>
                                                <div class="controls">
                                                    <div id="video_details">
                                                        
                                                    </div>
                                                </div>
                                            </div>
										</td>
									</tr>
									<tr bgcolor="#eeeeee" valign="top">
										<td>
											<input id="source_local_v2" type="radio" <?php if($_row->source=='local' && $_row->uploaded==0) echo 'checked="checked"';?> value="local" name="source_v"  onChange="javascript:hide_hidden_row();"/>
											<span class="lbl"></span>
										</td>
										<td colspan="2"><?php echo JText::_("GURU_LOCAL"); ?></td>
									</tr>
									<tr bgcolor="#eeeeee" valign="top">
										<td></td>
										<td colspan="2" valign="top">
											<table>
												<tr valign="top" id="uploadblock">
													<td width="9%"><?php echo JText::_('GURU_MEDIA_UPLOAD');?>:</td>
													<td width="91%">
														<div id="videoUploader"></div>
													</td>
												</tr>
                                                <tr valign="top">
                                                    <td width="9%"></td>
                                                    <td width="91%"><?php
                                                        echo $maxUpload;
                                                    ?></td>
                                                </tr>	
												<tr id="to_hide_row_v" style="display:none">
													<td>
														<?php echo JText::_('GURU_MEDIA_UPLOADED_FILE');?>:
													</td>
													<td>
														<?php echo $_row->local;  ?>
													</td>							
												</tr>
											</table>
										</td>
									</tr> 
									<tr bgcolor="#eeeeee" valign="top">
										<td></td>
										<td><?php echo JText::_('GURU_MEDIATYPECHOOSE').' '.JText::_('GURU_MEDIATYPEVIDEO_');?><br/>
											<font size="1"><?php echo JText::_('GURU_MEDIA_UPLOADTO_V_1').' '.$folder_of_files.JText::_('GURU_MEDIA_UPLOADTO_V_2'); ?></font></td>
										<td><?php echo $display_list_of_dir;?>
											<?php 
											if(isset($now_selected)&&($now_selected!='')) {echo str_replace($now_selected.'"',$now_selected.'" selected="selected"',$display_list_of_files); }//$lists['image_url'];
											else echo $display_list_of_files;
											?>
										</td>
									</tr>
									<tr bgcolor="#FFFFFF"  valign="top" id="player_size">
										<td></td>
										<td colspan="2">
											<table cellspacing="0" cellpadding="5" border="0" width="100%">
												<tbody>
													<tr>
														<td width="10%"><?php echo JText::_('GURU_MEDIA_SIZE'); ?></td>
														<td width="90%">
															<table>
																<tr>
																	<?php									
																		if($_row->option_video_size == NULL){
																			$_row->option_video_size = "0";
																		}
																	?>
																	<td>									
																		<input type="radio" name="option_video_size" value="0" <?php if($_row->option_video_size == "0"){echo 'checked="checked"';} ?>/>
																		<span class="lbl"></span>
																	</td>
																	<td>	
																		<?php 
																			$default_size = $configuration->default_video_size;
																			$default_zize_array = explode("x", $default_size);
																			$dafault_size_height = $default_zize_array["0"];
																			$dafault_size_width = $default_zize_array["1"];
																			echo JText::_("GURU_USE_GLOBAL")." (".$dafault_size_height." px x ".$dafault_size_width." px)"; 
																		?>
																	</td>
																</tr>
																<tr>	
																	<td>
																		<input type="radio" name="option_video_size" value="1" <?php if($_row->option_video_size == "1"){echo 'checked="checked"';} ?>/>
																		<span class="lbl"></span>
																	</td>
																	<td>
																		<?php
																			if($_row->id == "0"){
																				$_row->height = "";
																				$_row->width = "";
																			}
																		?>
                                                                        <div>
                                                                            <div style="float:left;">
                                                                                <input type="text" id="height_v" size="5" value="<?php echo $_row->height;?>" name="height_v"/>
                                                                            </div>
                                                                            <div style="float:left;">
                                                                             px x &nbsp;
                                                                            </div>
                                                                            <div style="float:left;">
                                                                                <input type="text" id="width_v" size="5" value="<?php echo $_row->width;?>" name="width_v"/>
                                                                            </div>
                                                                            <div style="float:left;">   
                                                                                 px &nbsp;
                                                                            </div>
                                                                            <div style="float:left;">
                                                                            <?php
                                                                                echo " (".JText::_("GURU_HEIGHT")." x ".JText::_("GURU_WIDTH").")";
                                                                            ?>
                                                                            </div>
                                                                        </div>
																		<input type="hidden" id="video_size" name="video_size" value="" />
																	</td>
																</tr>
															</table>		
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>	
				</table>
			</td>
		</tr>
		
		<?php
			$display_list_of_dir = $lists['audio_dir'];
			$display_list_of_files = $lists['audio_url'];
			$folder_of_files = $configuration->audioin;
			$edit_audio = 'style="display:none;"';
			if($_row->type=='audio'){
				$edit_audio = 'style=""';
			}	
		?>
		
		<tr id="audioblock" <?php echo $edit_audio; ?>>
        	<td width="20%">
				<?php echo JText::_('GURU_MEDIATYPEAUDIO');?>:<font color="#ff0000">*</font> 
            </td>
			<td width="80%">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-left:-8px;">
					<tr>
						<td>
							<?php echo JText::_('GURU_MEDIATYPEAUDIOS'); ?><br/>
							<table cellspacing="0" cellpadding="5" border="0" width="100%">
								<tbody>
									<tr bgcolor="#eeeeee" valign="top" id="code_of_file">
										<td width="5%"><input id="source_code_a" <?php if($_row->source=='code') echo 'checked="checked"';?> type="radio" value="code" name="source_a"/><span class="lbl"></span></td>
										<td width="28%"><?php echo JText::_('GURU_MEDIATYPEAUDIO').' '.JText::_('GURU_MEDIATYPECODE');?></td>
										<td width="67%"><textarea cols="35" name="code_a" onKeyPress="javascript:change_radio_code()" onPaste="javascript:change_radio_code()"><?php echo stripslashes($_row->code); ?></textarea></td>
									</tr>
									<tr bgcolor="#ffffff" valign="top">
										<td width="5%"><input id="source_url_a" type="radio" <?php if($_row->source=='url') echo 'checked="checked"';?> value="url" name="source_a" onChange="javascript:hide_hidden_row();"/><span class="lbl"></span></td>
										<td width="28%"><?php echo JText::_('GURU_MEDIATYPEAUDIO').' '.JText::_('GURU_MEDIATYPEURLURL');?></td>
										<td width="67%"><input type="text" onKeyPress="javascript:change_radio_url()" onPaste="javascript:change_radio_url()" size="40" value="<?php echo $_row->url;?>" name="url_a"  onChange="javascript:hide_hidden_row();"/></td>
									</tr>
									<tr bgcolor="#eeeeee" valign="top" >
										<td>
											<input id="source_local_a2" type="radio" <?php if($_row->source=='local' && $_row->uploaded==0) echo 'checked="checked"';?> value="local" name="source_a"  onChange="javascript:hide_hidden_row();"/>
											<span class="lbl"></span>
										</td>
										<td colspan="2" valign="top"><?php echo JText::_("GURU_LOCAL"); ?></td>
									</tr>
									<tr bgcolor="#eeeeee" valign="top">
										<td></td>
										<td colspan="2" valign="top">
											<table>
												<tr  valign="top" id="uploadblock">
													<td width="9%"><?php echo JText::_('GURU_MEDIA_UPLOAD');?>:</td>
													<td width="91%"><div id="audioUploader"></div></td>
												</tr>	
                                                <tr valign="top">
                                                    <td width="9%"></td>
                                                    <td width="91%">
                                                        <?php
                                                            echo $maxUpload;
                                                        ?>
                                                    </td>
                                                </tr>
												<tr id="to_hide_row_a" style="display:none">
													<td><?php echo JText::_('GURU_MEDIA_UPLOADED_FILE');?>:</td>
													<td>
														<?php 
															echo $_row->local;
														?>
													</td>							
												</tr>
											</table>
										</td>
									</tr>			 
									<tr bgcolor="#eeeeee" valign="top">
										<td></td>
										<td><?php echo JText::_('GURU_MEDIATYPECHOOSE').' '.JText::_('GURU_MEDIATYPEAUDIO_');?><br/>
											<font size="1"><?php echo JText::_('GURU_MEDIA_UPLOADTO_V_1').' '.$folder_of_files.JText::_('GURU_MEDIA_UPLOADTO_V_2'); ?></font></td>
										<td><?php echo $display_list_of_dir;?>
										<?php 
											$now_selected = guruAdminModelguruMedia::now_selected_media ($_row->id);
											if (isset($now_selected)) { echo JText::_('GURU_MEDIA_NOW_SELECTED').$now_selected; } ?><br><?php echo $display_list_of_files;?>
										</td>
									</tr>
									<tr bgcolor="#eeeeee" valign="top" id="player_size">
										<td></td>
										<td colspan="2">
											<table cellspacing="0" cellpadding="5" border="0" width="100%">
												<tbody>
													<tr>
														<td width="10%"><?php echo JText::_('GURU_MEDIA_SIZE'); ?></td>
														<td width="90%">
														  <?php
																$media_size_val = "";
																if( isset($this->audio_set) && (( $this->audio_set==1) || (isset($_row->id) && ($_row->id>0)))){
																	if($this->audio_set==0){
																		$media_size_val = $_row->width;
																	}
																	else{
																		$media_size_val = "250";
																	}
																}
																else{
																	$media_size_val = "250";
																}
														  ?>
														  <input type="text" size="10" value="<?php echo $media_size_val; ?>" name="width_a"/>
														  <input type="hidden" size="10" value="20" name="height_a"/>  
														<?php echo JText::_('GURU_MEDIA_WIDTH'); ?>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>	
								</tbody>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<?php
			if($_row->auto_play == NULL){
				$_row->auto_play = "1";
			}
			$edit_play = 'style="display:none;"';
			if($_row->type=='audio' || $_row->type=='video'){
				$edit_play = 'style=""';
			}
		?>
			
		<tr id="auto_play" <?php echo $edit_play; ?>>
			<td colspan="2">
				<table width="100%">
					<tr>
						<td width="85%">
							<?php echo JText::_("GURU_AUTO_PLAY"); ?>
						</td>
						<td align="left">
                        	<div>
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
						</td>
					</tr>
				</table>
			</td>
		</tr>	
		<?php
			$display_list_of_dir = $lists['docs_dir'];
			$display_list_of_files = $lists['docs_url'];
			$folder_of_files = $configuration->docsin;
			$edit_docs = 'style="display:none;"';	
			if($_row->type=='docs'){
				$edit_docs = 'style=""';
			}
		?>
		
		<tr id="docsblock" <?php echo $edit_docs; ?>>            
            	<td width="20%">
					<?php echo JText::_('GURU_MEDIATYPEDOCS');?>:<font color="#ff0000">*</font> 
                </td>
                <td width="80%">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-left:-8px;">
					<tr>
						<td>
							<?php echo JText::_('GURU_MEDIATYPEDOCSS'); ?><br/>
							<table cellspacing="0" cellpadding="5" border="0" width="100%">
								<tbody>
									<tr bgcolor="#ffffff" valign="top">
										<td width="5%"><input id="source_url_d" type="radio" <?php if($_row->source=='url') echo 'checked="checked"';?> value="url" name="source_d" onChange="javascript:hide_hidden_row();"/><span class="lbl"></span></td>
										<td width="28%"><?php echo JText::_('GURU_MEDIATYPEDOCS').' '.JText::_('GURU_MEDIATYPEURLURL');?></td>
										<td width="67%"><input type="text" onKeyPress="javascript:change_radio_url()" onPaste="javascript:change_radio_url()" size="40" value="<?php echo $_row->url;?>" name="url_d"  onChange="javascript:hide_hidden_row();"/></td>
									</tr>
									<tr bgcolor="#eeeeee" valign="top" >
										<td>
											<input id="source_local_d2" type="radio" <?php if($_row->source=='local') echo 'checked="checked"';?> value="local" name="source_d"  onChange="javascript:hide_hidden_row();"/>
											<span class="lbl"></span>
										</td>
										<td colspan="2" valign="top">
											<?php echo JText::_("GURU_LOCAL"); ?>
										</td>
									</tr>
									<tr bgcolor="#eeeeee" valign="top" >
										<td></td>
										<td colspan="2" valign="top">
											<table>
												<tr valign="top" id="uploadblock">
													<td width="9%"><?php echo JText::_('GURU_MEDIA_UPLOAD');?>:</td>
													<td width="91%">
														<div id="docUploader"></div>
													</td>
												</tr>
                                                <tr valign="top">
                                                    <td width="9%"></td>
                                                    <td width="91%">
                                                        <?php
                                                            echo $maxUpload;
                                                        ?>
                                                    </td>
                                                </tr>	
												<tr id="to_hide_row_d" style="display:none">
													<td>
													<?php echo JText::_('GURU_MEDIA_UPLOADED_FILE');?>:
													</td>
													<td>
														<?php 
															echo $_row->local;
														?>
													</td>							
												</tr>
											</table>
										</td>
									</tr>			 
									<tr bgcolor="#eeeeee" valign="top">
										<td></td>
										<td><?php echo JText::_('GURU_MEDIATYPECHOOSE').' '.JText::_('GURU_MEDIATYPEDOCS_');?><br/>
											<font size="1"><?php echo JText::_('GURU_MEDIA_UPLOADTO_V_1').' '.$folder_of_files.JText::_('GURU_MEDIA_UPLOADTO_V_2'); ?></font></td>
										<td><?php echo $display_list_of_dir;?>
										<?php 
											$now_selected = guruAdminModelguruMedia::now_selected_media ($_row->id);
											if (isset($now_selected)) { echo JText::_('GURU_MEDIA_NOW_SELECTED').$now_selected; } ?><br><?php echo $display_list_of_files; //$lists['image_url'];?></td>
									</tr>
									<tr bgcolor="#eeeeee" valign="top" id="player_size" <?php //echo $code_of_file;?>>
										<td></td>
										<td colspan="2">
											<table>
												<tr>
													<td width="10%">
														<?php echo JText::_('GURU_MEDIA_DISPL_DOC'); ?>
													</td>
													<td><script type="text/javascript">
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
													</td>
												</tr>
											</table>
											<table id="whdoc" cellspacing="0" cellpadding="5" border="0" width="100%" <?php if(isset($sel_link)){ echo 'style="display:none;"';}?>>
												<tbody>
													<tr>
														<td width="10%"><?php echo JText::_('GURU_MEDIA_SIZE'); ?></td>
														<td width="90%">
                                                        	<div>
                                                                <div style="float:left;">
                                                                    <input type="text" size="10" value="<?php if($_row->width>99){echo $_row->width;}else {echo "600";} ?>" name="width"/>
                                                                </div>
                                                                <div style="float:left;">
                                                                    &nbsp;X&nbsp;
                                                                </div>
                                                                <div style="float:left;">   
                                                                    <input type="text" size="10" value="<?php if($_row->height>99){echo $_row->height;}else {echo "800";}?>" name="height"/>
                                                                </div>
                                                                <div style="float:left;">
																	<?php echo JText::_('GURU_MEDIA_WIDTH_HEIGHT'); ?>
                                                                </div>
                                                            </div>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<?php
			$edit_url = 'style="display:none;"';
			if($_row->type=='url'){
				$edit_url = 'style=""';
			}
		?>
		
		<tr id="urlblock" <?php echo $edit_url; ?>>
			<td colspan="2">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="20%"><?php echo JText::_('GURU_MEDIATYPEURL_');?>:</td>
						<td width="80%">
                        <div>
                            <div style="float:left;">
                                <input type="text" size="80" value="<?php if (isset($_row->url) && $_row->url !="" ){echo $_row->url;}else{ echo "http://";}?>" name="url"/>
                            </div>
                            <div style="float:left;">
                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEURL_"); ?>" >
                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                </span>
                            </div>                                
                            <div>
                                <font color="#FF0000"><?php echo JText::_("GURU_ENTER_FULL_URL"); ?> http://ijoomla.com</font>
                            </div>
                        </div>
                    </td>
					</tr>
					<tr>
						<td width="10%">
							<?php echo JText::_('GURU_MEDIA_DISPL_DOC'); ?>
						</td>
						<td>	
							<select name="display_as2" >
								<option value="wrapper"><?php echo JText::_('GURU_MEDIA_DISPL_DOC_W'); ?></option>
								<option value="link" <?php if($_row->type=='url' && $_row->width==1) echo 'selected = "selected"'; ?>><?php echo JText::_('GURU_MEDIA_DISPL_DOC_L'); ?></option>
							</select>
						</td>
					</tr>		
				</table>
			</td>
		</tr>
   		<?php
			$edit_art = 'style="display:none;"';
			if($_row->type=='Article'){
				$edit_art = 'style=""';
			}
		?>
		
		<tr id="artblock" <?php echo $edit_art; ?>>
	<td colspan="2">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="20%"><?php echo JText::_('GURU_MEDIATYPEARTICLE_');?>:</td>
			<td width="80%">
		<?php
		    $headData = $doc->getHeadData();
		    $scripts = $headData['scripts'];
		    unset($scripts['/media/system/js/tabs.js']);
		    $headData['scripts'] = $scripts;
		    $doc->setHeadData($headData);
		   
		    unset($this->_scripts['/media/system/js/tabs.js']);
		 	if($_row->id !=""){
				$db = JFactory::getDBO();
				$sql = "SELECT code FROM `#__guru_media` WHERE type='Article' and id=".$_row->id;
				$db->setQuery($sql);
				$guru_articleid = $db->loadColumn();
			 } 
			if(@$code !=0){
			$sql = "SELECT title FROM `#__content` WHERE id=".$guru_articleid;
			$db->setQuery($sql);
			$guru_article_name = $db->loadColumn();
			}	
			?>
            	<?php echo $this->displayArticleguru(@$guru_articleid[0], @$guru_article_name[0]); ?>	
                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEARTICLE_"); ?>" >
                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                </span>
			</td>
		</tr>	
		</table>
	</td>
</tr>	
		<?php
			$edit_image = 'style="display:none;"';
			if($_row->type=='image'){
				$edit_image = 'style=""';
			}
		?>
		
		<tr id="imageblock" <?php echo $edit_image; ?>>
		<td colspan = "2">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="20%"><?php echo JText::_('GURU_MEDIATYPEIMAGE');?>:<font color="#ff0000">*</font></td>
					<td width="80%">
						 <div id="imageUploader"></div>
					 	<input type="hidden" name="images" id="images" value="<?php echo $_row->local; ?>" />			
					</td>
				</tr>
                <tr valign="top">
                    <td width="20%"></td>
                    <td width="80%"><?php
                        echo $maxUpload;
                    ?></td>
                </tr>			
				<tr>
					<td width="20%">				
						<?php  echo JText::_('GURU_GEN_IM_FIS');?>:
					</td>	
					<td width="80%">
						<?php 
						$media_fullpx = 200;
						$media_prop = 'w';
						$is_image = 0;
						if($_row->width>0 && $_row->height == 0)
							{
								$media_fullpx = $_row->width;
								$media_prop = 'w';
								$is_image = 1;
							}	
						if($_row->height >0 && $_row->width == 0)
							{
								$media_fullpx = $_row->height;					
								$media_prop = 'h';
								$is_image = 1;
							}	
						?>
                        <div>	
                        	<div style="float:left;">	
								<input type="text" size="8" id="media_fullpx" name="media_fullpx" value="<?php echo $media_fullpx;?>" /> 
                            </div>
                            <div style="float:left;">	
                            	px &nbsp;
                            </div>
                            <div style="float:left;">
								<select name="media_prop" id="media_prop">
                                    <option value="w" <?php if($media_prop=='w') echo 'selected="selected"'; ?>><?php  echo JText::_('GURU_PROPW');?></option>
                                    <option value="h" <?php if($media_prop=='h') echo 'selected="selected"'; ?>><?php  echo JText::_('GURU_PROPH');?></option>
								</select>	
                            </div>
                        </div>
						<input type="hidden" id="is_image" name="is_image" value="<?php echo $is_image;?>" />									
					</td>
				</tr>
				<tr>
					<td>
						<?php echo JText::_('GURU_PRODCIMG');?>:
					</td>
					<td>
						<?php 
							if(trim($_row->local)!=""){
								$media_image = '<img id="view_imagelist23" name="view_imagelist" style="margin:5px;" border="0" alt="" src="'.JURI::root().$configuration->imagesin."/media/thumbs".$_row->local.'" />';
							}
							else
								$media_image = '<img id="view_imagelist23" name="view_imagelist" style="margin:5px;" border="0" alt="" src="../images/M_images/blank.png" />';
							// generating thumb image - stop				
						?>
						<?php echo $media_image; ?>
						<input type="hidden" id="image" name="image" value="<?php echo $_row->local;?>" />
					</td>
				</tr>			
			</table>
		</td>
	</tr>
		
		<?php
			$edit_text = 'style="display:none;"';
			if($_row->type=='text'){
				$edit_text = 'style=""';
			}	
		?>
		<tr id="textblock" <?php echo $edit_text; ?>>
			<td colspan="2">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="20%">
							<?php echo JText::_('GURU_MEDIATYPETEXT');?>:<font color="#ff0000">*</font>
						</td>
						<td width="80%">
							<?php 
								echo $editorul->display( 'text', ''.$_row->code,'100%', '300px', '20', '60' );			
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<?php
			$display_list_of_dir = $lists['files_dir'];
			$display_list_of_files = $lists['files_url'];
			$edit_file = 'style="display:none;"';
			if($_row->type=='file'){
				$edit_file = 'style=""';
			}
		?>
		<tr id="fileblock" <?php echo $edit_file; ?>>
        	<td width="20%">
				<?php echo JText::_('GURU_MEDIATYPEDOCS');?>:
                <font color="#ff0000">*</font> 
            </td>
            <td width="80%">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-left:-7px;">
                    <tr>
                        <td>
                            <?php echo JText::_('GURU_MEDIATYPEFILES'); ?><br/>
                            <table cellspacing="0" cellpadding="5" border="0" width="100%">
                                <tbody>
                                    <tr bgcolor="#ffffff" valign="top">
                                        <td width="5%">
                                            <input id="source_url_f" type="radio" <?php if($_row->source=='url') echo 'checked="checked"';?> value="url" name="source_f" onChange="javascript:hide_hidden_row();"/>
											<span class="lbl"></span>
                                        </td>
                                        <td width="28%">
                                            <?php echo JText::_('GURU_MEDIATYPEDOCS').' '.JText::_('GURU_MEDIATYPEURLURL');?>
                                        </td>
                                        <td width="67%">
                                            <input type="text" onKeyPress="javascript:change_radio_url()" onPaste="javascript:change_radio_url()" size="40" value="<?php echo $_row->url;?>" id="url_f" name="url_f"  onChange="javascript:hide_hidden_row();" onmouseout="doPreview();" on/>
                                            <?php 
                                                if($_row->source=="url" && $_row->url!=""){
                                            ?>
                                                    <div id="filePreview">
                                                        <a class="a_guru" href="<?php echo $_row->url; ?>"><?php echo JText::_("GURU_PREVIEW"); ?></a>
                                                    </div>
                                            <?php	
                                                } 
                                            ?>
                                            <div id="filePreview"></div>
                                        </td>
                                    </tr>
                                    <tr bgcolor="#eeeeee" valign="top" >
                                        <td>
                                            
                                            <input id="source_local_f2" type="radio" <?php if($_row->source=='local') echo 'checked="checked"';?> value="local" name="source_f"  onChange="javascript:hide_hidden_row();"/>
											<span class="lbl"></span>
                                        </td>
                                        <td colspan="2" valign="top">
                                            <?php echo JText::_("GURU_LOCAL"); ?>
                                        </td>
                                    </tr>
                                    
                                    <tr bgcolor="#eeeeee" valign="top" >
                                        <td></td>
                                        <td colspan="2" valign="top">
                                            <table>
                                                <tr valign="top" id="uploadblock">
                                                    <td width="9%"><?php echo JText::_('GURU_MEDIA_UPLOAD');?>:</td>
                                                    <td width="91%">
                                                        <div id="fileUploader"></div>
                                                    </td>
                                                </tr>
                                                <tr valign="top">
                                                    <td width="9%"></td>
                                                    <td width="91%"><?php
                                                        echo "<font color='#FF0000'>";
                                                        echo JText::_('GURU_MEDIA_MAX_UPL_V_1')." ";
                                                        echo $UPLOAD_MAX_SIZE.'M ';
                                                        echo JText::_('GURU_MEDIA_MAX_UPL_V_2');
                                                        echo "</font>";?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>			 
                                    <tr bgcolor="#eeeeee" valign="top">
                                        <td></td>
                                        <td>
                                            <?php echo JText::_('GURU_MEDIATYPECHOOSE').' '.JText::_('GURU_MEDIATYPEDOCS_');?><br/>
                                            <font size="1">
                                                <?php echo JText::_('GURU_MEDIA_UPLOADTO_V_1').' '.$folder_of_files.JText::_('GURU_MEDIA_UPLOADTO_V_2'); ?>
                                            </font>
                                        </td>
                                        <td>
                                            <?php 
                                                echo $display_list_of_dir;
                                                $now_selected = guruAdminModelguruMedia::now_selected_media ($_row->id);
                                                if (isset($now_selected)) { 
                                                    echo JText::_('GURU_MEDIA_NOW_SELECTED').$now_selected; 
                                                } ?>
                                            <br>
                                            <?php echo $display_list_of_files; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <div id="filesFolder"><?php echo JURI::root().$configuration->filesin; ?></div>
                                            <?php 
                                            if($_row->source=="local" && $_row->local!=""){
                                            ?>
                                                <a class="a_guru" href="<?php echo JURI::root().$configuration->filesin."/".$_row->local; ?>" id="filePreviewList"><?php echo JText::_("GURU_PREVIEW"); ?></a>
                                            <?php 
                                            }else{ ?>
                                                <a class="a_guru" href="#" style="visibility:hidden" id="filePreviewList"><?php echo JText::_("GURU_PREVIEW"); ?></a>	
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>	
                        </td>
                    </tr>	
                </table>
            </td>
		</tr>	
	</table>
<?php
	$action = JRequest::getVar("action", "");
?>
	<input type="hidden" name="action" value="<?php echo $action; ?>" />
	<input type="hidden" name="option" value="com_guru" />
	<input type="hidden" name="task" value="edit" />
	<input type="hidden" name="id" value="<?php echo $_row->id;?>" />
	<input type="hidden" name="mediatext" value="<?php 
	
		if($mediaval1!=""){
			echo "med";
		}
		elseif($mediaval2!=""){
			echo "txt";
		}
	?>" id="mediatext" />
	<input type="hidden" name="mediatextvalue" value="<?php 
		if($mediaval1!=""){
			echo $mediaval1;
		}
		elseif($mediaval2!=""){
			echo $mediaval2;
		}
	?>" id="mediatextvalue" />
	<input type="hidden" name="controller" value="guruMedia" />
	<input type="hidden" name="screen" id="screen"  value="<?php echo $scr; ?>" />

	<script type="text/javascript">
		var currentURL = window.location;
		document.write('<in'+'put type="hidden" name="redirect_to" value="'+currentURL.href+'" />');
	</script>
</fieldset>
</form>