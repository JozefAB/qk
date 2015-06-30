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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
	JHtml::_('behavior.calendar');

	$program = $this->program;
	$lists = $this->lists;
	jimport('joomla.html.pane');
	$pane	= JPane::getInstance('tabs');
	$editorul  = JFactory::getEditor(); 
	$mmediam = $this->mmediam;
	$configuration = guruAdminModelguruProgram::getConfigs();
	$course_config = json_decode($configuration->ctgpage);		
	$full_image_size = $course_config->ctg_image_size;
	$full_image_proportional = $course_config->ctg_image_size_type == "0" ? "w" : "h";
	
	$db = JFactory::getDBO();
	$sql = "Select datetype FROM #__guru_config where id=1 ";
	$db->setQuery($sql);
	$format_date = $db->loadColumn();
	$dateformat = $format_date[0];

	
	$format = "%m-%d-%Y";
	switch($dateformat){
		case "d-m-Y H:i:s": $format = "%d-%m-%Y %H:%M:%S";
			  break;
		case "d/m/Y H:i:s": $format = "%d/%m/%Y %H:%M:%S"; 
			  break;
		case "m-d-Y H:i:s": $format = "%m-%d-%Y %H:%M:%S"; 
			  break;
		case "m/d/Y H:i:s": $format = "%m/%d/%Y %H:%M:%S"; 
			  break;
		case "Y-m-d H:i:s": $format = "%Y-%m-%d %H:%M:%S"; 
			  break;
		case "Y/m/d H:i:s": $format = "%Y/%m/%d %H:%M:%S"; 
			  break;
		case "d-m-Y": $format = "%d-%m-%Y"; 
			  break;
		case "d/m/Y": $format = "%d/%m/%Y"; 
			  break;
		case "m-d-Y": $format = "%m-%d-%Y"; 
			  break;
		case "m/d/Y": $format = "%m/%d/%Y"; 
			  break;
		case "Y-m-d": $format = "%Y-%m-%d"; 
			  break;
		case "Y/m/d": $format = "%Y/%m/%d";		
			  break;  	  	  	  	  	  	  	  	  	  
	}
				
?>


		<script language="javascript" type="text/javascript">
		<!--
		Joomla.submitbutton = function(pressbutton){
		//function submitbutton(pressbutton) {
			var form = document.adminForm;
			<?php echo $editorul->save( 'introtext' );?>
			if (pressbutton=='save' || pressbutton=='apply') {
				 if (form['name'].value == "") {
						alert( "<?php echo JText::_("GURU_CS_PLSINSNAME");?>" );
				 } /* else if(document.getElementById('introtext').value =='') {
				 	alert("<?php echo JText::_("GURU_PR_PLSINSINTRO");?>");
				 } */else if (form['price'].value<0.00001 || isNaN(form['price'].value)) {
				 	alert("<?php echo JText::_("GURU_PR_PLSINSPRICE");?>");
				 }else if (form['catid'].value==0) {
					alert("<?php echo JText::_("GURU_PR_PLSINSCATEG");?>");
				 } else if (form['freetrial'].value<0 || isNaN(form['freetrial'].value) || Math.ceil(form['freetrial'].value)>form['freetrial'].value ) {
				 		alert( "<?php echo JText::_("GURU_PR_PLSINSTRIAL");?>" );
				 } else { 
						submitform( pressbutton );
						window.top.setTimeout('window.parent.document.getElementById("sbox-window").close()', 200);
					}
			}
			else  
				{
					window.top.setTimeout('window.parent.document.getElementById("sbox-window").close()', 200);
					submitform( pressbutton ); 
				}	
		}
		-->
		</script>
<style>
#rowsmedia {
	background-color:#eeeeee;
}
#rowsmedia tr{
	background-color:#eeeeee;
}
#rowsmainmedia {
	background-color:#eeeeee;
}
#rowsmainmedia tr{
	background-color:#eeeeee;
}
</style>
<script>
function delete_temp(i){
 document.getElementById('tr'+i).style.display = 'none';
 document.getElementById('mediafiletodel').value =  document.getElementById('mediafiletodel').value+','+i;
}
function delete_temp2(i){
 document.getElementById('1tr'+i).style.display = 'none';
 document.getElementById('mediafiletodel').value =  document.getElementById('mediafiletodel').value+','+i;
}
</script>
<div style="float:right">
<?php if (isset($_GET['cid'][0]) && intval($_GET['cid'][0])>0)
{?>
<div id="toolbar" class="btn-toolbar pull-right no-margin">
    <div id="toolbar-apply" class="btn-wrapper">
        <button class="btn btn-success" onclick="javascript:submitbutton2('save');">
            <span class="icon-apply icon-white"></span>
            Save
        </button>
    </div>
</div>
<?php }else{?>
<div id="toolbar" class="btn-toolbar pull-right no-margin">
    <div id="toolbar-apply" class="btn-wrapper">
        <button class="btn btn-success" onclick="javascript:submitbutton('save');">
            <span class="icon-apply icon-white"></span>
            Save
        </button>
    </div>
</div>
<?php }?>
</div> 
 
 <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
 	<?php
		echo $pane->startPane("Category-pane");
		echo $pane->startPanel( JText::_('GURU_GENERAL'), "general-settings" );

	?>
	<fieldset class="adminform">
	<legend><?php if ($program->id<1) echo JText::_('GURU_NEWCOURSE'); else echo JText::_('GURU_EDITCOURSE');?></legend>
	<table class="adminform">
		<tr>
			<td width="15%">
			<?php echo JText::_('GURU_PRODNAME'); ?>:<font color="#ff0000">*</font></td>
			<td>
			<input class="inputbox" type="text" name="name" size="40" maxlength="255" value="<?php echo $program->name; ?>" />
			</td>
		</tr>
		<tr>
			<td width="15%">
			<?php echo JText::_('GURU_ALIAS'); ?>:</td>
			<td>
			<input class="inputbox" type="text" name="alias" size="40" maxlength="255" value="<?php echo $program->alias; ?>" />
			</td>
		</tr>
		<tr>
			<td width="15%">
			<?php echo JText::_('GURU_CATEGPARENT'); ?>:<font color="#ff0000">*</font></td>
			<td>
			<?php $lists['treecateg']=$this->list_all(0, "catid", $program->catid, $program->catid); ?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo JText::_('GURU_PRODDESC');?>: 
			</td>
			<td>
		<?php echo $editorul->display( 'description', ''.stripslashes($program->description),'100%', '300px', '20', '60' );?>
			</td>
		</tr>	
		<tr>
			<td>
			<?php echo JText::_('GURU_PRODINTRO');?>:
			</td>
			<td>
		<?php echo $editorul->display( 'introtext', ''.stripslashes($program->introtext),'100%', '300px', '20', '60' );?>
			</td>
		</tr>		
		</table>
	</fieldset>	
	 <?php 
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_('GURU_PRICING'), "media-info" );
     ?>
     <fieldset class="adminform">
	<legend><?php if ($program->id<1) echo JText::_('GURU_NEWCOURSE'); else echo JText::_('GURU_EDITCOURSE');?></legend>
	<table class="adminform">
		<tr>
			<td>
			<?php echo JText::_('GURU_PRICE');?>:<font color="#ff0000">*</font> 
			</td>
			<td width="85%">
			<input class="inputbox" type="text" name="price" size="10" maxlength="255" value="<?php echo $program->price; ?>" />
			<br>
			</td>
		</tr>
		<tr>
			<td></td>
			<td nowrap>
			<?php echo JText::_('GURU_PRODFREEOR');?>           
            <?php //echo $lists['days'];?>
			<input class="inputbox" type="text" id="freetrial" name="freetrial" size="4" maxlength="255" value="<?php if($program->freetrial>=0) echo $program->freetrial; elseif($_POST['freetrial']) echo $_POST['freetrial']; else echo '0';?>" />
            <?php echo JText::_('GURU_TREEDAYS');?>
			</td>
		</tr>		
		<tr>
			<td>
			</td>
			<td>
			<?php echo JText::_('GURU_PRODFREFOR');?><br>
			<?php echo $lists['freegroups'];?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo JText::_('GURU_PRODREDO');?>
			</td>
			<td nowrap>
			<input type="radio" <?php if ($program->redo == "free") echo "checked"; ?> name="redo" value="free" />
    		<?php echo JText::_('GURU_PRODFREEE');?><input type="radio" <?php if ($program->redo == "same") echo "checked"; ?> value="same" name="redo"/>
    		<?php echo JText::_('GURU_PRODCSTSAME');?><input type="radio" value="cost" name="redo" <?php if ($program->redo == "cost") echo "checked"; ?> />
    		<?php echo JText::_('GURU_PRODCOST');?><input type="text" <?php if ($program->redo == "cost") echo 'value="'.$program->redocost.'"'; ?> size="10" name="redocost" /> 
		    <select name="discount">
		      <option value="percent" <?php if ($program->discount == 'percent') echo "selected"; ?>>%</option>
		      <option value="amount" <?php if ($program->discount == 'amount') echo "selected"; ?>>$</option>
		    </select>    
		    <?php echo JText::_('GURU_PRODLESS');?>
			</td>
		</tr>
		</table>
	</fieldset>	
	 <?php 
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_('GURU_IMAGE'), "media-info" );
     ?>
     <fieldset class="adminform">
	<legend><?php if ($program->id<1) echo JText::_('GURU_NEWCOURSE'); else echo JText::_('GURU_EDITCOURSE');?></legend>
	<table class="adminform">
		<tr>
		<td width="100" align="left">
		<?php echo JText::_('GURU_IMAGE');?>:
		</td>
		<td>
		<input class="inputbox" type="file" name="image_file" size="35" value="" /><input class="inputbox" type="submit" value="Upload" onclick="return UploadImage();">
		<script  language="javascript" type="text/javascript">
			function UploadImage() {								
			var fileControl = document.adminForm.image_file;
			var thisext = fileControl.value.substr(fileControl.value.lastIndexOf('.'));
			if (thisext != ".jpeg" && thisext != ".jpg" && thisext != ".gif" && thisext != ".png" && thisext != ".JPEG" && thisext != ".JPG" && thisext != ".GIF" && thisext != ".PNG")  
			{ alert('<?php echo JText::_('Invalid image type!');?>');
			  return false;
			}  
			if (fileControl.value) {
				document.adminForm.image.value = fileControl.value;
				document.adminForm.task.value = 'upload';
				return true;
			}
			return false;
			}
		</script>
		</td>
	</tr>
		<tr>
			<td>
			<?php echo JText::_('GURU_PRODCIMG');?> 
			</td>
			<td>
			<?php
			
				if(isset($_POST['image'])) $the_image = $_POST['image']; else $the_image = $program->image;
				// generating thumb image - start
				$img_size = @getimagesize(JPATH_SITE.DS.$configuration->imagesin.'/'.$the_image);
				$img_width = $img_size[0];
				$img_height = $img_size[1];
				if($img_width>0 && $img_height>0)
				{
					if($full_image_proportional=='w')
						{
							$thumb_width = $full_image_size;
							$thumb_height = $img_height / ($img_width/$full_image_size);
						}
					elseif($full_image_proportional=='h')	
						{
							$thumb_height = $full_image_size;
							$thumb_width = $img_width / ($img_height/$full_image_size);		
						}
					
					$image_to_thumb = JPATH_SITE.DS.$configuration->imagesin.'/'.$the_image;
					$image_full_thumb = guruAdminHelper::create_thumbnails($image_to_thumb, $thumb_width, $thumb_height,$img_width,$img_height, 'full_');
					//echo JPATH_SITE.DS.'images'.DS.'stories'.DS.$image_full_thumb;
					$program_image = '<img style="margin:5px;" border="0" alt="" src="'.JURI::root().$configuration->imagesin.DS.$image_full_thumb.'" />';
				}
				else
					$program_image = '';
				// generating thumb image - stop					
			
			?>
			<?php echo $program_image; /* <img src="<?php echo str_replace('/administrator','/',JURI::base()); echo $configuration->imagesin.'/' ;?><?php if(isset($_POST['image'])) echo $_POST['image']; else echo $program->image;?>" alt="" /> */?>
			</td>
		</tr>		
		</table>
	</fieldset>	
	 <?php 
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_('GURU_MEDIA'), "media-info" );
     ?>
     <fieldset class="adminform">
	<legend><?php if ($program->id<1) echo JText::_('GURU_NEWCOURSE'); else echo JText::_('GURU_EDITCOURSE');?></legend>
	<table class="adminform">
		<tr>
			<td width="15%">
			<?php echo JText::_('GURU_PRODAVMEDIA'); ?>:</td>
			<td>
			<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruPrograms&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>" class="modal"><span title="Parameters" class="icon-32-config"></span><?php echo JText::_("GURU_ADD_MAIN_MEDIA"); ?></a><br><br><table cellspacing="1" cellpadding="5" border="0" bgcolor="#cccccc" width="100%">
          <tbody id="rowsmedia"><tr>
            <td width="8%"> </td>
            <td width="39%"><strong><?php echo JText::_("GURU_FILE"); ?></strong></td>
            <td width="9%"><strong><?php echo JText::_("GURU_TYPE"); ?></strong></td>
            <td width="18%"><strong><?php echo JText::_("GURU_REORDER"); ?></strong></td>
            <td width="12%"><strong><?php echo JText::_("GURU_REMOVE"); ?></strong></td>
            <td width="14%"><strong><?php echo JText::_("GURU_PUBLISHED"); ?></strong></td>
          </tr>
          <?php 
        $oldids = "";
		$display_none = '';
		
		$existing_ids = guruAdminModelguruProgram::existing_ids($program->id);
		
		$tt = array();
		foreach($existing_ids as $ex_idz)
		{
			$tt[] = $ex_idz->media_id;
		}		
		
		$more_media_files=new stdClass();
		
		///////////////////////////////////////////////////////////
		if(isset($_POST['mediafiles']))
		{
		$temp_media_id = explode(',', $_POST['mediafiles']);
		//if(in_array($mmedial->media_id,$temp_media_id))
			//{
				//$link2_remove = '<font color="#FF0000"><span onClick="delete_temp2('.$mmedial->media_id.')">Remove</span></font>';
			//}
		$more_ids = '';
		foreach($temp_media_id as $temp_media_id_val)
			{
				if($temp_media_id_val!='')
				$more_ids = $more_ids.$temp_media_id_val.',';
			}
		$more_ids = substr($more_ids,0, strlen($more_ids)-1);	
		if(strlen($more_ids)>0)	
			{
				guruAdminModelguruProgram::more_media_files($more_ids);
				$more_media_files = $this->more_media_files;
			}	
		}		
        //////////////////////////////////////////////////////////////
		
	    foreach ($mmediam as $mmedial) { 
			
		$link2_remove = '<font color="#FF0000"><span onClick="delete_temp2('.$mmedial->media_id.')">Remove</span></font>';
		$display_none = '';
		if(isset($_POST['mediafiletodel']))
		{
			$temp_media_id_2_rem = explode(',', $_POST['mediafiletodel']);
			if(in_array($mmedial->media_id,$temp_media_id_2_rem))
				$display_none = ' style="display:none"';
		}
		
		?>
        <tr id="1tr<?php echo $mmedial->media_id;?>" <?php echo $display_none; ?>>
          <td width="8%"></td>
          <td width="39%"><a class="a_guru" href="index.php?option=com_guru&controller=guruMedia&task=edit&cid[]=<?php echo $mmedial->media_id;?>" target="_blank"><?php echo $mmedial->name;?></a></td>
          <td width="9%"><?php echo $mmedial->type;?></td>
          <td width="18%">--</td>
          <td width="12%"><?php echo $link2_remove; ?></td>
          <td width="14%"><?php echo $mmedial->published;?></td>
        </tr>
     <?php   
          $oldids = $oldids.$mmedial->media_id.',';
        }
		if(count($more_media_files)>0)
		foreach($more_media_files as $more_media_files_val) {
		$display_none = '';
		if(!in_array($more_media_files_val->media_id,$tt)) { // if start
		$link2_remove = '<font color="#FF0000"><span onClick="delete_temp2('.$more_media_files_val->media_id.')">Remove</span></font>';

		if(isset($_POST['mediafiletodel']))
		{
			$temp_media_id_2_rem = explode(',', $_POST['mediafiletodel']);
			if(in_array($more_media_files_val->media_id,$temp_media_id_2_rem))
				$display_none = ' style="display:none"';
		}
		
        ?>
        <tr id="1tr<?php echo $more_media_files_val->media_id;?>" <?php echo $display_none; ?>>
          <td width="8%"></td>
          <td width="39%"><a class="a_guru" href="index.php?option=com_guru&controller=guruMedia&task=edit&cid[]=<?php echo $mmedial->media_id;?>" target="_blank"><?php echo $more_media_files_val->name;?></a></td>
          <td width="9%"><?php echo $more_media_files_val->type;?></td>
          <td width="18%">--</td>
          <td width="12%"><?php echo $link2_remove; ?></td>
          <td width="14%"><?php echo $more_media_files_val->published;?></td>
        </tr>		
	<?php $oldids = $oldids.$more_media_files_val->media_id.',';
			} // if end
		} // foreach end ?>	
     </tbody></table>
      <input type="hidden" value="<?php echo $oldids;?>" name="mediafiles" id="mediafiles">
	  <input type="hidden" value="<?php if (isset($_POST['mediafiletodel'])) echo $_POST['mediafiletodel'];?>" name="mediafiletodel" id="mediafiletodel">
			</td>
		</tr>
		</table> 
	</fieldset>	
	 <?php 
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_('GURU_EMAILS'), "media-info" );
     ?>
     <fieldset class="adminform">
	<legend><?php if ($program->id<1) echo JText::_('GURU_NEWCOURSE'); else echo JText::_('GURU_EDITCOURSE');?></legend>
	<table class="adminform" align="center">
		<tr>
			<td width="90%">
			<table border="1">
			<tr>
				<td width="10%"><strong><?php echo JText::_('GURU_PRODLACT'); ?></strong></td>
				<td width="90%"><strong><?php echo JText::_('GURU_PRODLEMAIL'); ?></strong></td>
			</tr>
			<?php 
			$allemailList = guruAdminModelguruProgram::getallemails();
			$emailList = guruAdminModelguruProgram::getpemails($program->id);
			$email_id_array = "";
			foreach($emailList as $email)
			$email_id_array = $email_id_array.','.$email->media_id;

			$emailz = explode (',', $email_id_array);

			foreach($allemailList as $allemail)
			{ 
			?>
			<tr>
				<td width="10%"><input type="checkbox" name="echbox[]" <?php if(in_array($allemail->id,$emailz) || in_array($allemail->id,$_POST['echbox'])) echo "checked";  ?> value="<?php echo $allemail->id;?>" /></td>
				<td width="90%"><?php echo $allemail->description; ?></td>
			</tr>
			<?php } // end foreach ?>
			</table>
			</td>
		</tr>		
		</table>
	</fieldset>	
	 <?php 
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_('GURU_PUBLISHING'), "media-info" );
     ?>
      <fieldset class="adminform">
	<legend><?php if ($program->id<1) echo JText::_('GURU_NEWCOURSE'); else echo JText::_('GURU_EDITCOURSE');?></legend>
	<table class="adminform">
	<tr>
		<td width="15%">
        <?php echo JText::_('GURU_PRODLPBS'); ?>
		</td>
		<td width="85%">
		<?php echo $lists['published']; ?>
		</td>
		</tr>
     <tr>
		<td valign="top" align="right">
        <?php echo JText::_('GURU_PRODLSPUB'); ?>
		</td>
	    <td>
		<?php if ($program->id<1) $start_publish =  date("".$dateformat."", time()); else $start_publish =  date("".$dateformat."", strtotime($program->startpublish));
		echo JHTML::_('calendar', $start_publish, 'startpublish', 'startpublish', $format, array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
		</td>
	</tr>
	<tr>
		<td valign="top" align="right">
        <?php echo JText::_('GURU_PRODLEPUB'); ?>
		</td>
		<td>
		<?php if(substr($program->endpublish,0,4) =='0000' || $program->id<1) $end_publish = JText::_('GURU_NEVER'); else $end_publish = date("".$dateformat."", strtotime($program->end_publish));
		echo JHTML::_('calendar', $end_publish, 'endpublish', 'endpublish', $format, array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
		</td>
	</tr>
	</table>
	</fieldset>	
	 <?php 
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_('GURU_METATAGS'), "media-info" );
     ?>
      <fieldset class="adminform">
	<legend><?php if ($program->id<1) echo JText::_('GURU_NEWCOURSE'); else echo JText::_('GURU_EDITCOURSE');?></legend>
	<table class="adminform">
		<tr>
			<td width="15%">
			<?php echo JText::_('GURU_TITLE'); ?>:</td>
			<td>
			<input class="inputbox" type="text" name="metatitle" size="40" maxlength="255" value="<?php echo $program->metatitle; ?>" />
			</td>
		</tr>
		<tr>
			<td>
			<?php echo JText::_('GURU_KWDS');?>:
			</td>
			<td>
			<textarea cols="40" name="metakwd" class="inputbox"><?php echo $program->metakwd; ?></textarea>
			<br>
			</td>
		</tr>	
		<tr>
			<td>
			<?php echo JText::_('GURU_DSCS');?>:
			</td>
			<td>
			<textarea cols="40" name="metadesc" class="inputbox"><?php echo $program->metadesc; ?></textarea>
			<br>
			</td>
		</tr>	
		</table>
	</fieldset>	
	 <?php 
		echo $pane->endPanel();
		echo $pane->endPane();
     ?>
		<input type="hidden" name="id" value="<?php echo $program->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="image" value="<?php if(isset($_POST['image'])) echo $_POST['image']; else echo $program->image;?>" />
		<input type="hidden" name="option" value="com_guru" />
		<input type="hidden" name="is_sbox" value="<?php if(isset($_GET['tmpl']) && $_GET['tmpl'] == 'component') echo '1' ;else echo '0'; ?>" />
		<input type="hidden" name="controller" value="guruPrograms" />
</form>
