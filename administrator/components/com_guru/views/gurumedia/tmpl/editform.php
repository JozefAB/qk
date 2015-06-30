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
	$data = $this->data;
	$_row = $this->media;
	$lists = $_row->lists;
	$nullDate = 0;
	$livesite = JURI::base();	
	$configuration = guruAdminModelguruMedia::getConfig();	
	$editorul  = JFactory::getEditor();
	
	$UPLOAD_MAX_SIZE = @ini_get('upload_max_filesize');
	$max_post 		= (int)(ini_get('post_max_size'));
	$memory_limit 	= (int)(ini_get('memory_limit'));
	$UPLOAD_MAX_SIZE = min($UPLOAD_MAX_SIZE, $max_post, $memory_limit);
	if($UPLOAD_MAX_SIZE == 0) {$UPLOAD_MAX_SIZE = 10;}
	//$UPLOAD_MAX_SIZE*=1048576; //transform in bytes
	
	$maxUpload = "<font color='#FF0000'>";
	$maxUpload .= JText::_('GURU_MEDIA_MAX_UPL_V_1')." ";
	$maxUpload .= $UPLOAD_MAX_SIZE.'M ';
	$maxUpload .= JText::_('GURU_MEDIA_MAX_UPL_V_2');
	$maxUpload .= "</font>";
	
	
	include(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_guru'.DS.'helpers'.DS.'createUploader.php');
	?>
	<script type="text/javascript" src="<?php echo JURI::base(); ?>components/com_guru/views/gurumedia/tmpl/js.js"></script>	            
	<script language="javascript" type="text/javascript">
		function changefolder() {								
			submitbutton('changes');
		}
		
		function isFloat(nr){
			return parseFloat(nr.match(/^-?\d*(\.\d+)?$/)) > 0;
		}
		
		Joomla.submitbutton = function(pressbutton){
			var form = document.adminForm;
			if (pressbutton == 'save' || pressbutton == 'apply') { 
				if (form['name'].value == "") {
					alert( "<?php echo JText::_("GURU_MEDIA_JS_NAME_ERR");?>" );
				} 
				else if (form['type'].value == 0 || form['type'].value == "-" ) {
					alert( "<?php echo JText::_("GURU_MEDIA_JS_TYPE_ERR");?>" ); 
				}
				else if (form['type'].value == 'image' && form['is_image'].value == 0) {
					alert( "<?php echo JText::_("GURU_MEDIA_JS_IMAGE_ERR");?>" );
				}
				else if(form['author'].value == '- select -'){
					alert( "<?php echo JText::_("GURU_CS_QAUTHOR");?>" );
				}
				else if (form['type'].value == 'text' && document.getElementById('textblock').value =='') {
					alert( "<?php echo JText::_("GURU_MEDIA_JS_TEXT_ERR");?>" );
				}				
				else {
					type = document.getElementById("type").value;
					
					if(type == "video"){
						width_v = document.getElementById("width_v").value;
						height_v = document.getElementById("height_v").value;
						
						if(document.getElementById("option_video_size").checked == true){
							if(!isFloat(width_v) || width_v <= 0){
								alert("<?php echo JText::_("GURU_MEDIAAPPLYFAILED").JText::_("GURU_MEDIASAVEDSIZE"); ?>");
								return false;
							}
							else if(!isFloat(height_v) || height_v <= 0){
								alert("<?php echo JText::_("GURU_MEDIAAPPLYFAILED").JText::_("GURU_MEDIASAVEDSIZE"); ?>");
								return false;
							}
						}
						
						if(document.getElementById("source_code_v").checked == true){
							if(form['code_v'].value == ""){
								alert("<?php echo JText::_("GURU_ADD_VIDEO_CODE"); ?>");
								return false;
							}
						}
						else if(document.getElementById("source_url_v").checked == true){
							if(form['url_v'].value == ""){
								alert("<?php echo JText::_("GURU_ADD_VIDEO_URL"); ?>");
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
					}
					else if(type == "audio"){
						width_a = document.getElementById("width_a").value;
						if(document.getElementById("source_local_a2").checked == true){
							if(!isFloat(width_a) || width_a <= 0){
								alert("<?php echo JText::_("GURU_MEDIAAPPLYFAILED").JText::_("GURU_MEDIASAVEDSIZE"); ?>");
								return false;
							}
						}
						
						if(document.getElementById("source_code_a").checked == true){
							if(form['code_a'].value == ""){
								alert("<?php echo JText::_("GURU_ADD_AUDIO_CODE"); ?>");
								return false;
							}
						}
						else if(document.getElementById("source_url_a").checked == true){
							if(form['url_a'].value == ""){
								alert("<?php echo JText::_("GURU_ADD_AUDIO_URL"); ?>");
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
					}
					else if(type == "docs"){
						width = document.getElementById("width").value;
						height = document.getElementById("height").value;
						link = document.getElementById("display_as").value;
						
						if(document.getElementById("source_local_d2").checked == true && link == 'wrapper'){
							if(!isFloat(width) || width <= 0){
								alert("<?php echo JText::_("GURU_MEDIAAPPLYFAILED").JText::_("GURU_MEDIASAVEDSIZE"); ?>");
								return false;
							}
							else if(!isFloat(height) || height <= 0){
								alert("<?php echo JText::_("GURU_MEDIAAPPLYFAILED").JText::_("GURU_MEDIASAVEDSIZE"); ?>");
								return false;
							}
						}
						
						if(document.getElementById("source_url_d").checked == true){
							if(form['url_d'].value == ""){
								alert("<?php echo JText::_("GURU_ADD_DOC_URL"); ?>");
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
					}
					else if(type == "image"){
						media_fullpx = document.getElementById("media_fullpx").value;
						if(!isFloat(media_fullpx) || media_fullpx <= 0){
							alert("<?php echo JText::_("GURU_MEDIAAPPLYFAILED").JText::_("GURU_MEDIASAVEDSIZE"); ?>");
							return false;
						}
					}
					else if(type == "file"){
						if(document.getElementById("source_url_f").checked == true){
							if(document.getElementById("url_f").value == ""){
								alert("<?php echo JText::_("GURU_ADD_FILE_URL"); ?>");
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
		</script>
<?php 
	$document = JFactory::getDocument();
	$document->addStyleSheet("components/com_guru/css/ytb.css"); 
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<fieldset class="adminform">
		<div class="well"><?php if(isset($_row->id)) {echo  JText::_('GURU_MEDIADET_EDIT');} else{echo  JText::_('GURU_MEDIADET_NEW');} ?></div>
		<table border="0" width="100%" class="adminform">
		<tr>
			<td width="20%">
				<?php echo JText::_('GURU_TYPE'); ?>:<font color="#ff0000">*</font> 
			</td>
			<td width="80%">
				<?php 
					echo $lists['type'];
				?>
				<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_TYPE"); ?>" >
					<img border="0" src="components/com_guru/images/icons/tooltip.png">
				</span>
			</td>
		</tr>		
		<tr>
			<td width="20%" nowrap> <?php echo JText::_('GURU_NAME');?>:<font color="#ff0000">*</font> </td>
			<td width="80%">
				<?php
					$_row->name = str_replace('"', '&quot;', $_row->name);					
				?>
				<input class="formField" type="text" name="name" id="name" size="60" value="<?php echo $_row->name; ?>">
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
				<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIA_CATEGORY"); ?>" >
					<img border="0" src="components/com_guru/images/icons/tooltip.png">
				</span>
			</td>
		</tr>
        <tr>
            <td>
                <?php echo JText::_('GURU_AUTHOR'); ?>:<font color="#ff0000">*</font></td>
            <td>
                <?php echo $lists['author']; ?>
                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_AUTHOR"); ?>" >
                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                </span>
            </td>
        </tr>
		<tr>
			<td width="20%"><?php echo JText::_('NEWADAPPROVED');?>:</td>
			<td width="80%">
				<?php echo $lists['approved'];?>
				<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIA_NEWADAPPROVED"); ?>" >
					<img border="0" src="components/com_guru/images/icons/tooltip.png">
				</span>
			</td>
		</tr>		
		<tr>
			<td width="20%"><?php echo JText::_('GURU_INSTR');?>:</td>
			<td width="80%">
				<textarea class="formField" type="text" name="instructions" rows="2" cols="60" ><?php echo stripslashes($_row->instructions); ?></textarea>
				<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIA_INSTR"); ?>" >
					<img border="0" src="components/com_guru/images/icons/tooltip.png">
				</span>
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
		
<?php //-------- VIDEO - BEGIN 
$display_list_of_dir = $lists['video_dir'];
$display_list_of_files = $lists['video_url'];
$folder_of_files = $configuration->videoin;

if(!isset($_row->type)){
	$type = JRequest::getVar("type", "");
	if($type != ""){
		$_row->type = $type;
	}
}

if($_row->type=='video')
	$stylev = 'style="table-row;"';
else
	$stylev = 'style="display:none;"';	
?>
<tr id="videoblock" <?php echo $stylev; ?> >
	<td colspan="2">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="20%">
			<?php echo JText::_('GURU_MEDIATYPEVIDEO');?>:<font color="#ff0000">*</font> 
			</td>
			<td>
			 <?php echo JText::_('GURU_MEDIATYPEVIDEOS'); ?><br/>
			 <table cellspacing="0" cellpadding="5" border="0" width="100%">
		      <tbody>
		      <tr bgcolor="#eeeeee" valign="top" id="code_of_file" <?php //echo $code_of_file;?>>              	
			    <td width="5%">
					<input id="source_code_v" <?php if($_row->source=='code') echo 'checked="checked"';?> type="radio" value="code" name="source_v"/>
					<span class="lbl"></span>
				</td>
		        <td width="28%">
					<?php echo JText::_('GURU_MEDIATYPEVIDEO').' '.JText::_('GURU_MEDIATYPECODE');?>
				</td>
		        <td width="67%">
                	<div>
                        <div style="float:left;">
                            <textarea cols="23" name="code_v" onKeyPress="javascript:change_radio_code()" onPaste="javascript:change_radio_code()"><?php echo stripslashes($_row->code); ?></textarea>
                        </div>
                        <div style="float:left;">
                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEVIDEO"); ?>" >
                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                            </span>
                        </div>
                    </div>
				</td>
		      </tr>
		      <tr bgcolor="#ffffff" valign="top">
		        <td width="5%">
					<input id="source_url_v" type="radio" <?php if($_row->source=='url') echo 'checked="checked"';?> value="url" name="source_v" onChange="javascript:hide_hidden_row();"/>
					<span class="lbl"></span>
				</td>
		        <td width="28%">
					<?php echo JText::_('GURU_MEDIATYPEVIDEO').' '.JText::_('GURU_MEDIATYPEURLURL');?>
				</td>
		        <td width="67%">
                	<div>
                    	<div style="float:left;">
                        	<input type="text" value="<?php echo $_row->url; ?>" name="url_v" id="url_v" size="40" onPaste="javascript:change_radio_url()" onblur="javascript:addVideoFromUrl('<?php echo JURI::root(); ?>');" />
                            <br/>
                            <div style="padding-top:5px">
                            	<input type="button" class="btn btn-success" value="<?php echo JText::_("COM_GURU_GET_VIDEO_NFO"); ?>" name="video-info" onclick="javascript:addVideoFromUrl('<?php echo JURI::root(); ?>');" />
                            </div>
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
                        
                    </div>					
				</td>
			</tr>		      
			<tr bgcolor="#eeeeee" valign="top" >
				<td>
					<input id="source_local_v2" type="radio" <?php if($_row->source=='local' && $_row->uploaded==0) echo 'checked="checked"';?> value="local" name="source_v"  onChange="javascript:hide_hidden_row();"/>
					<span class="lbl"></span>
				</td>
				<td colspan="2">
					<div>
                    	<div style="float:left;">
                        	<?php echo JText::_("GURU_LOCAL"); ?>&nbsp;
                        </div>
                        <div style="float:left;">
                        	<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_LOCAL"); ?>" >
                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                            </span>
                        </div>
                    </div>					
				</td>
			</tr>
			<tr bgcolor="#eeeeee" valign="top" >
			  	<td>
				</td>
				<td colspan="2" valign="top">
					<table>
						<tr valign="top" id="uploadblock" <?php //echo $styleupload; ?>>
							<td width="91%">
								<div id="videoUploader"></div>
							</td>
						</tr>	
						<tr valign="top">
							<td width="91%"><?php
								echo $maxUpload;
							?></td>
						</tr>
					</table>
				</td>
			  </tr>
			  
			  <tr bgcolor="#eeeeee" valign="top">
		      	<td>
					</td>
		        <td>
					<?php echo JText::_('GURU_MEDIATYPECHOOSE').' '.JText::_('GURU_MEDIATYPEVIDEO_');?><br/>
		            	<font size="1">
							<?php echo JText::_('GURU_MEDIA_UPLOADTO_V_1').' '.$folder_of_files.JText::_('GURU_MEDIA_UPLOADTO_V_2'); ?>
						</font>
				</td>
		        <td>
					<?php 
						//echo $display_list_of_dir; 
						$now_selected = guruAdminModelguruMedia::now_selected_media ($_row->id);
						if (isset($now_selected)) { 
							echo ' <strong>'.$_row->local.'</strong>'; } ?><br><?php 
							if(isset($now_selected)&&($now_selected!='')) {
								echo str_replace($now_selected.'"',$now_selected.'" selected="selected"',$display_list_of_files); 
							}
						else echo $display_list_of_files;
				?></td>
		    </tr>
			
			<tr bgcolor="#FFFFFF"  valign="top" id="player_size" <?php //echo $code_of_file;?>>
				<td></td>
		        <td colspan="2"><table cellspacing="0" cellpadding="5" border="0" width="100%">
		            <tbody>
					<tr id="g_mediaEdit_size">
						<td width="10%"><?php echo JText::_('GURU_MEDIA_SIZE'); ?></td>
						<td width="90%">
							<table>
								<tr id="g_defaultMedia_size">
									<?php									
										if($_row->option_video_size == NULL){
											$_row->option_video_size = "0";
										}
									?>
                                    <td>
                                    	<div>
                                        	<div style="float:left;">
                                            	<input type="radio" name="option_video_size" value="0" <?php if($_row->option_video_size == "0"){echo 'checked="checked"';} ?>/>
												<span class="lbl"></span>
                                            </div>
                                            <div style="float:left;">
                                            	<?php 
													$default_size = $configuration->default_video_size;
													$default_zize_array = explode("x", $default_size);
													$dafault_size_height = $default_zize_array["0"];
													$dafault_size_width = $default_zize_array["1"];
													echo JText::_("GURU_USE_GLOBAL")." (".$dafault_size_width." px x ".$dafault_size_height." px)"; 
												?>
                                            </div>
                                        </div>
                                    </td>									
								</tr>
								<tr id="g_customMedia_size">
                                	<td>
                                    	<div>
                                        	<div class="g_swich_option">
                                            	<input type="radio" name="option_video_size" id="option_video_size" value="1" <?php if($_row->option_video_size == "1"){echo 'checked="checked"';} ?>/><span class="lbl"></span>&nbsp;<span>Use custom width and height</span>
												
                                            </div>
                                            <div class="g_option_value">
                                            	<input type="text" id="width_v" size="5" value="<?php echo $_row->width;?>" name="width_v"/>&nbsp;<span>width in px</span>
                                            </div>
                                            <!-- <div style="float:left;">
                                            	px &nbsp;
                                            </div> -->
                                            <!-- <div style="float:left;">
                                            	x &nbsp;
                                            </div> -->
                                            <div class="g_option_value">
                                            	<input type="text" id="height_v" size="5" value="<?php echo $_row->height;?>" name="height_v"/>&nbsp;<span>height in px</span>
                                            </div>
                                            <!-- <div style="float:left;">
                                            	px 
                                            </div> -->
                                            <div style="float:left;">
                                            	<?php
													// echo " (".JText::_("GURU_WIDTH")." x ".JText::_("GURU_HEIGHT").")";
												?>						
												<input id="check" type="hidden" id="video_size" name="video_size" value="" />
                                            </div>
                                        </div>
                                    </td>		
                                	
								</tr>
							</table>		
						</td>
					</tr>
		        </tbody></table></td>
		      </tr>  
		    </tbody></table>
			</td>
		</table>
		
	</td>
</tr>
<?php //-------- VIDEO - END ?>
<?php //-------- AUDIO - BEGIN 
$display_list_of_dir = $lists['audio_dir'];
$display_list_of_files = $lists['audio_url'];
$folder_of_files = $configuration->audioin;
if($_row->type=='audio')
	$stylea = 'style="table-row;"';
else
	$stylea = 'style="display:none;"';	
?>
<tr id="audioblock" <?php echo $stylea;?>>
	<td colspan="2">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="20%">
				<?php echo JText::_('GURU_MEDIATYPEAUDIO');?>:
				<font color="#ff0000">*</font> 
			</td>
			<td>
				<?php echo JText::_('GURU_MEDIATYPEAUDIOS'); ?><br/>
			 	<table cellspacing="0" cellpadding="5" border="0" width="100%">
		     		<tbody>
		      			<tr bgcolor="#eeeeee" valign="top" id="code_of_file" <?php //echo $code_of_file;?>>
			    			<td width="5%">
								<input id="source_code_a" <?php if($_row->source=='code') echo 'checked="checked"';?> type="radio" value="code" name="source_a"/>
								<span class="lbl"></span>
							</td>
		        			<td width="28%">
								<?php echo JText::_('GURU_MEDIATYPEAUDIO').' '.JText::_('GURU_MEDIATYPECODE');?>
							</td>
		        			<td width="67%">
								<textarea cols="35" name="code_a" onKeyPress="javascript:change_radio_code()" onPaste="javascript:change_radio_code()"><?php echo stripslashes($_row->code); ?></textarea>
								<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEAUDIOS"); ?>" >
									<img border="0" src="components/com_guru/images/icons/tooltip.png">
								</span>
							</td>
		     			</tr>
		      			<tr bgcolor="#ffffff" valign="top">
		       				<td width="5%">
								<input id="source_url_a" type="radio" <?php if($_row->source=='url') echo 'checked="checked"';?> value="url" name="source_a" onChange="javascript:hide_hidden_row();"/>
								<span class="lbl"></span>
							</td>
		        			<td width="28%">
								<?php echo JText::_('GURU_MEDIATYPEAUDIO').' '.JText::_('GURU_MEDIATYPEURLURL');?>
							</td>
		        			<td width="67%">
                            	
								<input type="text" onKeyPress="javascript:change_radio_url()" onPaste="javascript:change_radio_url()" size="40" value="<?php echo $_row->url;?>" name="url_a"  onChange="javascript:hide_hidden_row();"/>
								<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEAUDIO"); ?>" >
									<img border="0" src="components/com_guru/images/icons/tooltip.png">
								</span>
							</td>
		      			</tr>
		     			
						<tr bgcolor="#eeeeee">
							<td>
								<input id="source_local_a2" type="radio" <?php if($_row->source=='local') echo 'checked="checked"';?> value="local" name="source_a"  onChange="javascript:hide_hidden_row();"/>
								<span class="lbl"></span>
							</td>
                            <td colspan="2">
                            	<div>
                                    <div style="float:left;">
                                        <?php echo JText::_('GURU_LOCAL');?>&nbsp;
                                    </div>
                                    <div style="float:left;">
                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_AUDIO_LOCAL"); ?>" >
                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                        </span>
                                    </div>
                                </div>
                            </td>                            
						</tr>
			 			<tr bgcolor="#eeeeee" valign="top" >
			  				<td>
							</td>
							<td colspan="2" valign="top">
								<table>
									<tr  valign="top" id="uploadblock">
										<td width="91%">
											<div id="audioUploader"></div>
										</td>
									</tr>
									<tr valign="top">
										<td width="91%">
											<?php
												echo $maxUpload;
											?>
										</td>
									</tr>
								</table>
							</td>
			  			</tr>			 
			 
			  			<tr bgcolor="#eeeeee" valign="top">
		        			<td>
							</td>
		       				<td>
								<?php echo JText::_('GURU_MEDIATYPECHOOSE').' '.JText::_('GURU_MEDIATYPEAUDIO_');?><br/>
		           				 <font size="1"><?php echo JText::_('GURU_MEDIA_UPLOADTO_V_1').' '.$folder_of_files.JText::_('GURU_MEDIA_UPLOADTO_V_2'); ?></font>
							</td>
		        			<td>
								<?php //echo $display_list_of_dir;?>
		        				<?php 
									$now_selected = guruAdminModelguruMedia::now_selected_media ($_row->id);
									if (isset($now_selected)) { 
										echo $now_selected; 
									} ?>
									<br>
									<?php echo $display_list_of_files;  ?>
							</td>
		      			</tr>
		     			<tr bgcolor="#eeeeee" valign="top" id="player_size" >
		        		<td> </td>
		        		<td colspan="2">
							<table cellspacing="0" cellpadding="5" border="0" width="100%">
		            			<tbody>
									<tr>
		             					<td width="10%">
											<?php echo JText::_('GURU_MEDIA_SIZE'); ?>
										</td>
		              					<td width="90%">
					  						<input type="text" size="10" value="<?php if(isset($_row->id)&&($_row->id>0)) {echo $_row->width;} else {echo "250";}?>" name="width_a" id="width_a"/>
											<input type="hidden" size="10" value="20" name="height_a"/>
		            						<?php echo JText::_('GURU_MEDIA_WIDTH'); ?>
										</td>
		            				</tr>
		        				</tbody>
							</table>
						</td>
		     		 </tr>
		   		 </tbody></table>
			</td>
		</table>
	</td>
</tr>
<?php //-------- AUDIO - END ?>
<?php
	if($_row->auto_play == NULL){
		$_row->auto_play = "1";
	}
	$auto_play_display = "none";
	if($_row->type=='audio' || $_row->type=='video'){
		$auto_play_display = "table-row";
	}
?>
<tr id="auto_play" style="display:<?php echo $auto_play_display; ?>">
	<td colspan="5">
		<table width="40%">
			<tr>
                <td>
                    <?php
                        echo JText::_("GURU_AUTO_PLAY");
                    ?>
                </td>
                <td align="left">
					<input type="hidden" name="auto_play" value="0">
					<?php
						$checked = '';
						if($_row->auto_play == 1){
							$checked = 'checked="checked"';
						}
					?>
					<input type="checkbox" <?php echo $checked; ?> value="1" class="ace-switch ace-switch-5" name="auto_play">
					<span class="lbl"></span>
                </td> 
                <td>
                </td>
			</tr>
		</table>
	</td>
</tr>			
<?php //-------- DOCUMENTS - BEGIN 
$display_list_of_dir = $lists['docs_dir'];
$display_list_of_files = $lists['docs_url'];
$folder_of_files = $configuration->docsin;
if($_row->type=='docs')
	$styled = 'style="table-row;"';
else
	$styled = 'style="display:none;"';	
/*$styled = 'style="display:none;"';	
	if($_row->type=='docs'){
		$styled = 'style=""';
	}*/
?>
<tr id="docsblock" <?php echo $styled; ?>>
	<td colspan="2">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="20%">
					<?php echo JText::_('GURU_MEDIATYPEDOCS');?>:<font color="#ff0000">*</font> 
				</td>
				<td>
					<?php echo JText::_('GURU_MEDIATYPEDOCSS'); ?><br/>
			 		<table cellspacing="0" cellpadding="5" border="0" width="100%">
		     			<tbody>
		      				<tr bgcolor="#ffffff" valign="top">
		        				<td width="5%">
									<input id="source_url_d" type="radio" <?php if($_row->source=='url') echo 'checked="checked"';?> value="url" name="source_d" onChange="javascript:hide_hidden_row();"/>
									<span class="lbl"></span>
								</td>
		        				<td width="28%">
									<?php echo JText::_('GURU_MEDIATYPEDOCS').' '.JText::_('GURU_MEDIATYPEURLURL');?>
								</td>
		        				<td width="67%">
									<input type="text" onKeyPress="javascript:change_radio_url()" onPaste="javascript:change_radio_url()" size="40" value="<?php echo $_row->url;?>" name="url_d"  onChange="javascript:hide_hidden_row();"/>
									<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEDOCS"); ?>" >
										<img border="0" src="components/com_guru/images/icons/tooltip.png">
									</span>
								</td>
		     				</tr>
		     
			 				<tr bgcolor="#eeeeee" valign="top" >
			  					<td>
			 						<input id="source_local_d2" type="radio" <?php if($_row->source=='local') echo 'checked="checked"'; ?> value="local" name="source_d"  onChange="javascript:hide_hidden_row();"/>
									<span class="lbl"></span>
								</td>
								<td colspan="2">
									<?php echo JText::_('GURU_LOCAL'); ?>
								</td>
			  				<tr bgcolor="#eeeeee" valign="top" >
			  					<td>
								</td>
								<td colspan="2" valign="top">
									<table>
										<tr  valign="top" id="uploadblock">
										<td width="91%">
											<div id="docUploader"></div>
											<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIA_UPLOAD"); ?>" >
												<img border="0" src="components/com_guru/images/icons/tooltip.png">
											</span>
									</td>
								</tr>
								
								<tr valign="top">
									<td width="91%"><?php
										echo $maxUpload;?>
									</td>
								</tr>
							</table>
						</td>
			 		 </tr>			 
			 
			  <tr bgcolor="#eeeeee" valign="top">
		        <td></td>
		        <td><?php echo JText::_('GURU_MEDIATYPECHOOSE').' '.JText::_('GURU_MEDIATYPEDOCS_');?><br/>
		            <font size="1"><?php echo JText::_('GURU_MEDIA_UPLOADTO_V_1').' '.$folder_of_files.JText::_('GURU_MEDIA_UPLOADTO_V_2'); ?></font></td>
		        <td><?php //echo $display_list_of_dir; //$lists['image_dir'];?>
		        <?php 
				$now_selected = guruAdminModelguruMedia::now_selected_media ($_row->id);
				if (isset($now_selected)) { echo $now_selected; } ?><br><?php echo $display_list_of_files; //$lists['image_url'];?></td>
		      </tr>
		      <tr bgcolor="#eeeeee" valign="top" id="player_size" <?php //echo $code_of_file;?>>
		        <td> </td>
		        <td colspan="2">
					<table width="100%"><!--table for the display-->
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
                                                                
								<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DISPL_DOC"); ?>" >
									<img border="0" src="components/com_guru/images/icons/tooltip.png">
								</span>
							</td>
						</tr>
					</table><!--end display setings-->
						
				
				<table id="whdoc" cellspacing="0" cellpadding="5" border="0" width="100%" <?php if(isset($sel_link)){ echo 'style="display:none;"';}?>>
		            <tbody><tr>
		              <td width="10%">
							<?php echo JText::_('GURU_MEDIA_SIZE'); ?>
                      </td>
		              <td width="90%">
                        <div>
                            <div style="float:left;">
                                <input type="text" size="10" value="<?php echo $_row->width; ?>" name="width" id="width"/>
                            </div>
                            <div style="float:left;">
                                 X &nbsp;
                            </div>
                            <div style="float:left;">
                                <input type="text" size="10" value="<?php echo $_row->height; ?>" name="height" id="height"/>
                            </div>
                            <div style="float:left;">
                                <?php echo JText::_('GURU_MEDIA_WIDTH_HEIGHT'); ?> &nbsp;
                            </div>
                            <div style="float:left;">
                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIA_SIZE"); ?>" >
                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                </span>
                            </div>
                        </div>
		            </tr>
		        	</tbody>
				</table>
				<?php ?>
				</td>
		      </tr>
		    </tbody></table>
			</td>
		</table>
		
	</td>
</tr>
<?php //-------- DOCUMENTS - END ?>
<?php //-------- URL - BEGIN 
if($_row->type=='url')
	$styleu = 'style="table-row;"';
else
	$styleu = 'style="display:none;"';	
?>
<tr id="urlblock" <?php echo $styleu; ?>>
	<td colspan="2">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="20%"><?php echo JText::_('GURU_MEDIATYPEURL_');?>:</td>
			<td width="80%">
            	<div>
                	<div style="float:left;">
                    	<input type="text" size="80" value="<?php if (isset($_row->url)){echo $_row->url;}else{ echo "http://";}?>" name="url"/>
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
				<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DISPL_DOC"); ?>" >
					<img border="0" src="components/com_guru/images/icons/tooltip.png">
				</span>
			</td>
		</tr>		
		</table>
	</td>
</tr>
<?php //-------- URL - END ?>
<?php //-------- Article - BEGIN 
if($_row->type=='Article')
	$styleu = 'style="table-row;"';
else
	$styleu = 'style="display:none;"';	
?>
<tr id="artblock" <?php echo $styleu; ?>>
	<td colspan="2">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="20%"><?php echo JText::_('GURU_MEDIATYPEARTICLE_');?>:</td>
			<td width="80%">
		<?php
			if($_row->id !=""){
				$db = JFactory::getDBO();
				$sql = "SELECT code FROM `#__guru_media` WHERE type='Article' and id=".$_row->id;
				$db->setQuery($sql);
				$guru_articleid = $db->loadColumn();
			}  
			if(@$code !=0){
			$sql = "SELECT title FROM `#__content` WHERE id=".$guru_articleid["0"];
			$db->setQuery($sql);
			$guru_article_name = $db->loadColumn();
			}	
			?>
            	<?php echo $this->displayArticleguru(@$guru_articleid["0"], @$guru_article_name["0"]); ?>	
                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEARTICLE_"); ?>" >
                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                </span>
			</td>
		</tr>	
		</table>
	</td>
</tr>
<?php //-------- Article - END ?>
<?php //-------- IMAGE - BEGIN 
if($_row->type=='image')
	$stylei = 'style="table-row;"';
else
	$stylei = 'style="display:none;"';	
	
?>
<tr id="imageblock" <?php echo $stylei; ?>>
	<td colspan = "2">
		
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		
		<tr>
			<td width="20%"><?php echo JText::_('GURU_MEDIATYPEIMAGE');?>:<font color="#ff0000">*</font></td>
			<td width="80%">
				 <div id="imageUploader"></div>
				 <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_MEDIATYPEIMAGE"); ?>" >
					<img border="0" src="components/com_guru/images/icons/tooltip.png">
				</span>
			</td>
		</tr>
	
		<tr valign="top">
			<td width="20%"></td>
			<td width="80%">
            <div class="alert alert-danger" style="width:50%;">
            	<?php echo JText::_('GURU_ALLOWED_IMAGES_EXT');?>
            </div>	
			<?php
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
                    <div style="float:left;">
                    	<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_GEN_IM_FIS"); ?>" >
                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                            </span>	
                        <input type="hidden" id="is_image" name="is_image" value="<?php echo $is_image;?>" />	
                    </div>
                </div>												
			</td>
		</tr>
		<tr>
			<td>
			<?php echo JText::_('GURU_PRODCIMG');?>:
			</td>
			<td>
			<?php 
				
				if(trim($_row->local)!=""){
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
			<?php //-------- IMAGE - END ?>
			<?php //-------- TEXT - BEGIN 
			if($_row->type=='text')
				$stylet = 'style="table-row;"';
			else
				$stylet = 'style="display:none;"';	
			?>
			<tr id="textblock" <?php echo $stylet; ?>>
				<td colspan="2">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="20%">
								<?php echo JText::_('GURU_MEDIATYPETEXT');?>:
								<font color="#ff0000">*</font>
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
			<?php //-------- TEXT - END 
			//-------- FILE - START 
			$display_list_of_dir = $lists['files_dir'];
			$display_list_of_files = $lists['files_url'];
			if($_row->type=='file'){
				$stylef = 'style="table-row;"';
			}
			else{
				$stylef = 'style="display:none;"';	
			}
		?>
			<tr id="fileblock" <?php echo $stylef; ?>>
				<td colspan="2">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="20%">
								<?php echo JText::_('GURU_MEDIATYPEFILE');?>:
								<font color="#ff0000">*</font> 
							</td>
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
												<?php echo JText::_('GURU_FILE').' '.JText::_('GURU_MEDIATYPEURLURL');?>
											</td>
		        							<td width="67%">
												<input type="text" onKeyPress="javascript:change_radio_url()" onPaste="javascript:change_radio_url()" size="40" value="<?php echo $_row->url;?>" id="url_f" name="url_f"  onChange="javascript:hide_hidden_row();" onmouseout="doPreview();" on/>
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
											</td>
		     							</tr>
										<tr bgcolor="#eeeeee" valign="top" >
			  								<td>
												<input id="source_local_f2" type="radio" <?php if($_row->source=='local') echo 'checked="checked"';?> value="local" name="source_f"  onChange="javascript:hide_hidden_row();"/>
												<span class="lbl"></span>
											</td>
											<td colspan="2">
												<?php echo JText::_("GURU_LOCAL_FILE"); ?>
											</td>
										</tr>
			  							<tr bgcolor="#eeeeee" valign="top" >
			  								<td>
											</td>
											<td colspan="2" valign="top">
												<table>
													<tr  valign="top" id="uploadblock">
														
													<td width="91%">
														<div id="fileUploader"></div>
														<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_FILE_MEDIA_UPLOAD"); ?>" >
															<img border="0" src="components/com_guru/images/icons/tooltip.png">
														</span>
													</td>
												</tr>
												<tr valign="top">
													<td width="91%"><?php
														echo $maxUpload;
														?>
													</td>
												</tr>
											</table>
										</td>
			 						</tr>			 
			  						<tr bgcolor="#eeeeee" valign="top">
		        						<td>
										</td>
		       							<td>
											<?php echo JText::_('GURU_MEDIATYPECHOOSE').' '.JText::_('GURU_MEDIATYPEFILE_');?><br/>		           							
											<font size="1">
												<?php echo JText::_('GURU_MEDIA_UPLOADTO_V_1').' '.$configuration->filesin.JText::_('GURU_MEDIA_UPLOADTO_V_2'); ?>
											</font>
										</td>
		        						<td id="">
											<?php 
												//echo $display_list_of_dir;
												$now_selected = guruAdminModelguruMedia::now_selected_media ($_row->id);
												if (isset($now_selected)) { 
													echo $now_selected; 
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
												<a class="a_guru" href="<?php echo JURI::root().$configuration->filesin."/".$_row->local; ?>" id="filePreviewList"><?php echo JText::_("GURU_DOWNLOAD"); ?></a>
											<?php 
											}else{ ?>
												<a class="a_guru" href="#" style="visibility:hidden" id="filePreviewList"><?php echo JText::_("GURU_DOWNLOAD"); ?></a>	
											<?php
											}
											?>
										</td>
									</tr>
		   				 		</tbody>
						 	</table>
						</td>
					</table>
				</td>
			</tr>
			<?php //- FILE - END ?>
		</table>	
		<input type="hidden" name="option" value="com_guru" />
		<input type="hidden" name="task" value="edit" />
		<input type="hidden" name="id" value="<?php echo $_row->id;?>" />
		<input type="hidden" name="controller" value="guruMedia" />
		<input type="hidden" name="was_uploaded" id="was_uploaded" value="1" />
	</form>
</fieldset>		
