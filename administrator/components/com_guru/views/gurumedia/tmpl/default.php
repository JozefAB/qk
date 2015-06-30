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

$files = $this->files;
$filters=$this->filters;
$n = count($files);
$k = 0;
$t=0;	
$doc =JFactory::getDocument();
$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');	
$doc->addScript('components/com_guru/js/jquery.noconflict.js');
$doc->addScript('components/com_guru/js/jquery.DOMWindow.js');
$doc->addScript('components/com_guru/plugins/jw_allvideos/jw_allvideos/includes/js/mediaplayer/jwplayer.min.js');
$doc->addScript('components/com_guru/plugins/jw_allvideos/jw_allvideos/includes/js/wmvplayer/silverlight.js');
$doc->addScript('components/com_guru/plugins/jw_allvideos/jw_allvideos/includes/js/wmvplayer/wmvplayer.js');
$doc->addScript('components/com_guru/plugins/jw_allvideos/jw_allvideos/includes/js/quicktimeplayer/AC_QuickTime.js');
	

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');

$all_categs = guruAdminModelguruMedia::getAllMediaCategory();
$configs = guruAdminModelguruMedia::getConfig();

$width = "1000";
$height = "600";

if($t == 0){
	$db = JFactory::getDBO();
	$sql = "UPDATE  `#__guru_media` set `type`='Article' where `type`='art'";
	$db->setQuery($sql);
	$db->query();
	$t =$t +1;
}
$lesson_window_size = $configs->lesson_window_size_back;
if(trim($lesson_window_size) != ""){
	$lesson_window_size = explode("x", $lesson_window_size);
	$width = $lesson_window_size["1"];
	$height = $lesson_window_size["0"];
}
?>	

<script type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton=='duplicate') {
			if (form['boxchecked'].value == 0) {
				alert( "<?php echo JText::_("GURU_MEDIA_MAKESEL_JAVAMSG");?>" );
			} else 	{
				submitform(pressbutton);
			}
		}
		else{
			submitform(pressbutton);
		}
	}		
</script>

<form name="topform1" id="topform1" action="index.php?option=com_guru&controller=guruMedia" method="post">	
	<table style="width: 100%;" cellpadding="5" cellspacing="5" bgcolor="#FFFFFF">
		<tr>
			<td>
				<input type="text" name="search_media" value="<?php if(isset($_POST['search_media'])) {
							echo $_POST['search_media'];
						} elseif(isset($_SESSION['search_media'])) {
							echo $_SESSION['search_media'];
						}?>" />
				<input class="btn btn-primary" type="submit" name="submit_search" value="<?php echo JText::_('GURU_SEARCHTXT');?>" />
			</td>
		
			<td>
				<?php 
					echo $filters->status;
				?>	
			</td>
		
			<td>
            	<?php
					echo $filters->type;
				?>
			</td>
			
			<td>
				<?php 
					echo $filters->media_category;
				?>
			</td>
		</tr>
	</table>
    
</form>
 <div id="myModal" class="modal hide">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:closeModal();">&times;</button>
    </div>
    <div class="modal-body">
    <iframe></iframe>
    </div>
</div>
 
<form id="adminForm" action="index.php" name="adminForm" method="post">	
	
    <table width="100%">
    	<tr>
        	<td>
            	<select name="teacher_id" id="teacher_id" onchange="document.adminForm.task.value='change_teacher'; document.adminForm.submit();">
                	<option value="0"><?php echo JText::_("GURU_CHANGE_MASS_TEACHER"); ?></option>
                    <?php
						$teachers = $this->getTeachers();
						if(isset($teachers) && count($teachers) > 0){
							foreach($teachers as $key=>$value){
								echo '<option value="'.intval($value["id"]).'">'.$value["name"].'</option>';
							}
						}
					?>
                </select>
            </td>
        </tr>
    </table>
    
    <div class="container-fluid">
          <a data-toggle="modal" data-target="#myModal" class="pull-right guru_video" onclick="showContentVideo('index.php?option=com_guru&controller=guruAbout&task=vimeo&id=27181343&tmpl=component')" class="pull-right guru_video" href="#">
                    <img src="<?php echo JURI::base(); ?>components/com_guru/images/icon_video.gif" class="video_img" />
                <?php echo JText::_("GURU_MEDIALIBRARY_VIDEO"); ?>                  
          </a>
	</div>	
	<div class="clearfix"></div>
    <div class="well well-minimized">
		<?php echo JText::_("GURU_MEDIALIBRARY_SETTINGS_DESCRIPTION"); ?>
	</div>
    
    <input type="hidden" name="page_width" value="0" />
    <input type="hidden" name="page_height" value="0" />
    <script type="text/javascript">
		<?php
		if($configs->back_size_type == "1"){ 
			echo 'document.adminForm.page_width.value="'.$width.'";';
			echo 'document.adminForm.page_height.value="'.$height.'";';
		}
		
		?>
	</script>
	<div id="editcell" >
		<table class="table table-striped table-bordered adminlist">
			<thead>
				<tr>
					<th width="5%">
						<input type="checkbox" onclick="Joomla.checkAll(this)" name="toggle" value="" />
                        <span class="lbl"></span>
					</th>
	   				<th width="5%">
						<?php echo JText::_('GURU_ID');?>
					</th>
					<th width="5%">	
					</th>
					<th width="40%">
						<?php echo JText::_('GURU_MEDIA_NAME');?>
					</th>
					<th width="15%">
						<?php echo JText::_('GURU_TYPE');?>
					</th>
					<th width="15%">
						<?php echo JText::_('GURU_CATEGORY');?>
					</th>
					<th width="10%">
						<?php echo JText::_('GURU_PREVIEW');?>
					</th>		
					
					<th width="5%">
						<?php echo JText::_('GURU_PUBLISHED');?>
					</th>				
				</tr>
			</thead>
		<tbody>
	<?php 

	for ($i = 0; $i < $n; $i++){
		$file = $this->files[$i];
		
		$id = $file->id;
		$checked = JHTML::_('grid.id', $i, $id);	
		$link = JRoute::_("index.php?option=com_guru&controller=guruMedia&task=edit&cid[]=".$id);
		$published = JHTML::_('grid.published', $file, $i );
	
	?>
	<tr class="camp<?php echo $k;?>">
		<td>
	    	<?php echo $checked;?>
            <span class="lbl"></span>
		</td>		
		<td>
	     	<?php echo $id;?>
		</td>		
	    <td>
	   		<?php 
	    		$srcimg = JURI::base()."/components/com_guru/images/";
	    		$image = "doc.gif";
	   			switch ($file->type) {
	    			case "video": $image = "video.gif";	    						
	    				break;
	    			case "audio": $image = "audio.gif";	    						
	    				break;
	    			case "docs": $image = "doc.gif";	    						
	    				break;
	    			case "quiz": $image = "quiz.gif";	    						
	    				break;
	    			case "url": $image = "url.gif";	    						
	    				break;
					case "Article": $image = "url.gif";	    						
	    				break;											
	    			case "image": $image = "image.jpg";	    						
	    				break;	
					case "file": $image = "file.gif";	    						
	    				break;										
	    		}
			?>
	    	<img src="<?php echo $srcimg.$image;?>" alt="" />
		</td>			
	    <td>
	     	<a class="a_guru" href="<?php echo $link;?>" ><?php echo $file->name;?></a>
		</td>	
		<td>
			<?php 
				$type = "";
	   			switch ($file->type) {
		    		case "video": $type = JText::_('GURU_MEDIATYPEVIDEO');	    						
		    			break;
		    		case "audio": $type = JText::_('GURU_MEDIATYPEAUDIO');	    						
		    			break;
		    		case "docs": $type = JText::_('GURU_MEDIATYPEDOCS');	    						
		    			break;
		    		case "url": $type = JText::_('GURU_MEDIATYPEURL');	    						
		    			break;
					case "Article": $type = JText::_('GURU_MEDIATYPEARTICLE');	    						
		    			break;
		    		case "image": $type = JText::_('GURU_MEDIATYPEIMAGE');	    						
		    			break;
		    		case "text": $type = JText::_('GURU_MEDIATYPETEXT');	    						
		    			break;
					case "file": $type = JText::_('GURU_MEDIATYPEFILE');	    						
		    			break;
		    		case "quiz": $type = JText::_('GURU_MEDIATYPEQUIZ');	    						
		    			break;																		
	    		}
	    		echo $type;?>
		</td>
		<td>
			<?php
				if(isset($all_categs) && is_array($all_categs) && isset($all_categs[$file->category_id])){
			?>
				<a class="a_guru" href="index.php?option=com_guru&controller=guruMediacategs&task=edit&id=<?php echo $file->category_id; ?>"><?php echo $all_categs[$file->category_id]["name"]; ?></a>
			<?php			
				}
			?>
		</td>
		<td>
			<span>
			<?php 
		if ($file->type == 'video' || $file->type == 'audio') {	
			if ($file->source=='code'){
				$file->code = stripslashes($file->code);
				if ($file->width == 0 || $file->height == 0){
					$begin_tag = strpos($file->code, 'width="');
					if ($begin_tag!==false){
						$remaining_code = substr($file->code, $begin_tag+7, strlen($file->code));
						$end_tag = strpos($remaining_code, '"');
						$height = substr($remaining_code, 0, $end_tag);
									
						$begin_tag = strpos($file->code, 'height="');
						if ($begin_tag!==false){
							$remaining_code = substr($file->code, $begin_tag+8, strlen($file->code));
							$end_tag = strpos($remaining_code, '"');
						}
					}	
				}
		}		
		?>
		
		<script type="text/javascript">	
			document.write('<a data-toggle="modal" data-target="#myModal" onclick="showContentVideo(\'index.php?option=com_guru&controller=guruMedia&task=preview&tmpl=component&id=<?php echo $file->id;?>\')" href="#"><?php echo JText::_('GURU_MEDIA_PREVIEW_LOWER');?></a>');
		</script>				
	   </span> 
				
	  <?php }
            elseif($file->type == 'docs' || $file->type == 'url'){ 
				if($file->width > 1){ // we display the link in a wrapper 
					if(($file->type == 'docs') && (substr($file->local,(strlen($file->local)-3),3) == 'pdf')){
						if(intval($file->width) > 100){
							$height = $file->height;
							$width = $file->width;
						}
					}?>           
					<script type="text/javascript">	
						document.write('<a data-toggle="modal" data-target="#myModal" onclick="showContentVideo(\'index.php?option=com_guru&controller=guruMedia&task=preview&tmpl=component&id=<?php echo $file->id;?>\')" href="#"><?php echo JText::_('GURU_MEDIA_PREVIEW_LOWER');?></a>');
					</script>				
				<?php
				}
				else{ // we display the link in a pop-up ?>
					<script type="text/javascript">
						document.write('<a data-toggle="modal" data-target="#myModal" onclick="showContentVideo(\'index.php?option=com_guru&controller=guruMedia&task=preview&tmpl=component&id=<?php echo $file->id;?>\')" href="#"><?php echo JText::_('GURU_MEDIA_PREVIEW_LOWER');?></a>');
					</script>
			</span> 
			<?php	
				}				
			}
			elseif ($file->type=='text'){ ?>
				<script type="text/javascript">
                    document.write('<a data-toggle="modal" data-target="#myModal" onclick="showContentVideo(\'index.php?option=com_guru&controller=guruMedia&task=preview&tmpl=component&id=<?php echo $file->id;?>\')" href="#"><?php echo JText::_('GURU_MEDIA_PREVIEW_LOWER');?></a>');
                </script>
                </span> 
			
			<?php 
			}
			elseif($file->type == 'image') { 
			?>
				<script type="text/javascript">	
                    document.write('<a data-toggle="modal" data-target="#myModal" onclick="showContentVideo(\'index.php?option=com_guru&controller=guruMedia&task=preview&tmpl=component&id=<?php echo $file->id;?>\')" href="#"><?php echo JText::_('GURU_MEDIA_PREVIEW_LOWER');?></a>');
                </script>
                </span> 
			<?php 
			}
			elseif($file->type == 'Article') { 
			?>
				<script type="text/javascript">	
                    document.write('<a data-toggle="modal" data-target="#myModal" onclick="showContentVideo(\'index.php?option=com_guru&controller=guruMedia&task=preview&tmpl=component&id=<?php echo $file->id;?>\')" href="#"><?php echo JText::_('GURU_MEDIA_PREVIEW_LOWER');?></a>');
                </script>
                </span> 
			<?php 
			}
			else if($file->type == 'file'){?>
				<script type="text/javascript">
                    document.write('<a data-toggle="modal" data-target="#myModal" onclick="showContentVideo(\'index.php?option=com_guru&controller=guruMedia&task=preview&tmpl=component&id=<?php echo $file->id;?>\')" href="#"><?php echo JText::_('GURU_MEDIA_PREVIEW_LOWER');?></a>');
                </script>
			<?php
			}
			else{
				echo JText::_('GURU_PREVIEW_NP'); 
			}?>
		</td>
		<td>
			<?php echo $published;?>
		</td>
	</tr>


<?php 
		$k = 1 - $k;
	}
?>
		 <tr>
                	<td colspan="10">
                    	<div class="pagination pagination-toolbar">
							<?php echo $this->pagination->getListFooter(); ?>
                        </div>
                        <div class="btn-group pull-left">
                            <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                            <?php echo $this->pagination->getLimitBox(); ?>
                       </div>
                    </td>
                </tr>
	</tbody>
</table>
</div>

<input type="hidden" name="option" value="com_guru" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="guruMedia" />
<input type="hidden" name="old_limit" value="<?php echo JRequest::getVar("limitstart"); ?>" />

</form>
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
	
	jQuery('body').click(function () {
		if(!first){
			jQuery('#myModal .modal-body iframe').attr('src', '');
		}
		else{
			first = false;
		}
	});
</script>