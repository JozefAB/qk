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
	JHtmlBehavior::framework();
	$program = $this->program;
	$mainmedia = $this->mainmedia;
	$mainquiz = $this->mainquiz;
	$mmediam = $this->mmediam;
	$lists = $this->lists;
	$the_layout = $this->the_layout;
	jimport('joomla.html.pane');
	$pane	= JPane::getInstance('tabs');
	//$editorul  = JFactory::getEditor();
	$configuration = guruAdminModelguruTask::getConfigs();
	/***$full_image_size = $configuration->tasks_fullpx;
	$full_image_proportional = $configuration->tasks_f_prop;***/
?>
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

<script language="javascript" type="text/javascript">	
function iFrameHeight() {
	var h = 0;
	if ( !document.all ) {
		h = document.getElementById('blockrandom').contentDocument.height;
		document.getElementById('blockrandom').style.height = h + 60 + 'px';
	} else if( document.all ) {
		h = document.frames('blockrandom').document.body.scrollHeight;
		document.all.blockrandom.style.height = h + 20 + 'px';
	}
}
</script>

<script type="text/javascript">
function dom_refresh() { 
		window.parent.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });
		window.parent.addEvent('domready', function() {

			SqueezeBox.initialize({});

			$$('a.modal').each(function(el) {
				el.addEvent('click', function(e) {
					new Event(e).stop();
					SqueezeBox.fromElement(el);
				});
			});
		});
}		

function page_refresh(sid) {
	submitform( 'apply' ); 
	//document.location = 'index.php?option=com_guru&controller=guruTasks&task=edit&cid[]='+sid;
}		
</script>
<?php  //JHTML::_('behavior.modal'); ?>
<script language="javascript" type="text/javascript">
<!--		
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton=='save' || pressbutton=='apply') {
		 if (form['name'].value == "") {
				alert( "<?php echo JText::_("GURU_TASKS_JS_NAME");?>" );
		 } else if (form['category'].value == "50000") {
		 		alert( "<?php echo JText::_("GURU_TASKS_JS_CAT");?>" );
		 } else if (form['difficultylevel'].value == "none") {
		 		alert( "<?php echo JText::_("GURU_TASKS_JS_DIFF");?>" );		
		 } else if (form['points'].value<0.00001 || isNaN(form['points'].value)) {
		 		alert( "<?php echo JText::_("GURU_TASKS_JS_POINTS");?>" );
		 }  else if (form['time'].value<0.00001 || isNaN(form['time'].value)) {
		 		alert( "<?php echo JText::_("GURU_TASKS_JS_TIME");?>" );
		 }  /*else if (form['type'].value == 0) {
		 		alert( "<?php echo JText::_("GURU_TASKS_JS_TYPE");?>" );
		 }*/
		 else
		  {
				submitform( pressbutton );
			}
	}
	else 
		submitform( pressbutton );
}

function delete_mm(i){
 document.getElementById('trmm'+i).style.display = 'none';
 document.getElementById('show_upload_link_m').style.display = '';
 document.getElementById('delete_mm').value = i;
}
function delete_mq(i){
 document.getElementById('trmq'+i).style.display = 'none';
 document.getElementById('show_upload_link_q').style.display = '';
 document.getElementById('delete_mq').value = i;
}
function delete_temp(i){
 document.getElementById('trm'+i).style.display = 'none';
 document.getElementById('delete_temp').value =  document.getElementById('delete_temp').value+','+i;
}

		function displayblock(type){
		if (type=='video' || type=='audio' || type=='url' || type=='docs' || type=='image' || type=='text' || type=='quiz')
			{
				document.getElementById('selectblock').style.display = '';
				if (type=='video')
					{
						document.getElementById('selectblockvid').style.display = '';
						document.getElementById('selectblockaud').style.display = 'none';
						document.getElementById('selectblockdoc').style.display = 'none';
						document.getElementById('selectblockurl').style.display = 'none';
						document.getElementById('selectblockimg').style.display = 'none';
						document.getElementById('selectblocktxt').style.display = 'none';
						document.getElementById('selectblockqiz').style.display = 'none';
						document.getElementById('mediatype').value = 'task';
					}
				if (type=='audio')
					{
						document.getElementById('selectblockvid').style.display = 'none';
						document.getElementById('selectblockaud').style.display = '';
						document.getElementById('selectblockdoc').style.display = 'none';
						document.getElementById('selectblockurl').style.display = 'none';
						document.getElementById('selectblockimg').style.display = 'none';
						document.getElementById('selectblocktxt').style.display = 'none';
						document.getElementById('selectblockqiz').style.display = 'none';
						document.getElementById('mediatype').value = 'task';
					}
				if (type=='docs')
					{
						document.getElementById('selectblockvid').style.display = 'none';
						document.getElementById('selectblockaud').style.display = 'none';
						document.getElementById('selectblockdoc').style.display = '';
						document.getElementById('selectblockurl').style.display = 'none';
						document.getElementById('selectblockimg').style.display = 'none';
						document.getElementById('selectblocktxt').style.display = 'none';
						document.getElementById('selectblockqiz').style.display = 'none';
						document.getElementById('mediatype').value = 'task';
					}				
				if (type=='url')
					{
						document.getElementById('selectblockvid').style.display = 'none';
						document.getElementById('selectblockaud').style.display = 'none';
						document.getElementById('selectblockdoc').style.display = 'none';
						document.getElementById('selectblockurl').style.display = '';
						document.getElementById('selectblockimg').style.display = 'none';
						document.getElementById('selectblocktxt').style.display = 'none';
						document.getElementById('selectblockqiz').style.display = 'none';
						document.getElementById('mediatype').value = 'task';
					}		
				if (type=='image')
					{
						document.getElementById('selectblockvid').style.display = 'none';
						document.getElementById('selectblockaud').style.display = 'none';
						document.getElementById('selectblockdoc').style.display = 'none';
						document.getElementById('selectblockurl').style.display = 'none';
						document.getElementById('selectblockimg').style.display = '';
						document.getElementById('selectblocktxt').style.display = 'none';
						document.getElementById('selectblockqiz').style.display = 'none';
						document.getElementById('mediatype').value = 'task';
					}	
				if (type=='text')
					{
						document.getElementById('selectblockvid').style.display = 'none';
						document.getElementById('selectblockaud').style.display = 'none';
						document.getElementById('selectblockdoc').style.display = 'none';
						document.getElementById('selectblockurl').style.display = 'none';
						document.getElementById('selectblockimg').style.display = 'none';
						document.getElementById('selectblocktxt').style.display = '';
						document.getElementById('selectblockqiz').style.display = 'none';
						document.getElementById('mediatype').value = 'task';
					}		
				if (type=='quiz')
					{
						document.getElementById('selectblockvid').style.display = 'none';
						document.getElementById('selectblockaud').style.display = 'none';
						document.getElementById('selectblockdoc').style.display = 'none';
						document.getElementById('selectblockurl').style.display = 'none';
						document.getElementById('selectblockimg').style.display = 'none';
						document.getElementById('selectblocktxt').style.display = 'none';
						document.getElementById('selectblockqiz').style.display = '';
						document.getElementById('mediatype').value = 'tquiz';
					}																									
			}	
		else	
			{
				document.getElementById('selectblock').style.display = 'none';
				document.getElementById('mediatype').value = 'none';		
			}	
		}
		
function ChangeLayout(number){
	for(i=1; i<=6; i++)
		if(i==number)
			{
				document.getElementById('layout_img_'+i).style.border = '3px solid #0000FF';
				document.getElementById('layout'+i).style.display = '';
			}	
		else
			{
				document.getElementById('layout_img_'+i).style.border = '';
				document.getElementById('layout'+i).style.display = 'none';			
			}
	document.getElementById('layout_db').value = number;		
}

function test(){
	window.parent.setTimeout('SqueezeBox.close()', 1000);
}

-->
</script>

 <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
 	<?php
		echo $pane->startPane("Category-pane");
		echo $pane->startPanel( JText::_('GURU_GENERAL'), "general-settings" );
	?>
	<fieldset class="adminform">
	<legend><?php if ($program->id<1) echo JText::_('GURU_NEW'); else echo JText::_('GURU_EDIT'); echo JText::_('GURU_TASK');?></legend>
	<table class="adminform">
		<tr>
			<td width="15%">
			<?php echo JText::_('GURU_PRODNAME'); ?>:<font color="#ff0000">*</font></td>
			<td colspan="3">
			<input class="inputbox" type="text" name="name" size="40" maxlength="255" value="<?php echo $program->name; ?>" />
			</td>
		</tr>
		<tr>
			<td width="15%">
				<?php //echo JText::_('GURU_ALIAS'); ?>:</td>
			<td colspan="3">
				<input class="inputbox" type="text" name="alias" size="40" maxlength="255" value="<?php echo $program->alias; ?>" />
			</td>
		</tr>
		<tr>
			<td width="15%">
			<?php echo JText::_('Category'); ?>:<font color="#ff0000">*</font></td>
			<td  colspan="3">
			<?php 
			if($program->id<1)
				{
					$selected_cat = 0;
					if(isset($_SESSION['category']) && intval($_SESSION['category'])!=50000)
						$selected_cat = intval($_SESSION['category']);
				}	
			else
				$selected_cat = $program->category;	
			$lists['treecateg']=$this->list_all(0, "category", $program->category, $selected_cat); ?>
		</td>
		</tr>		
		<tr>
			<td width="15%">
			<?php echo JText::_('Difficulty'); ?>:<font color="#ff0000">*</font></td>
			<td colspan="3">
			<?php echo $lists['difficulty'];?>
			</td>
		</tr>
		<tr>
			<td width="15%">
			<?php echo JText::_('GURU_POINTS'); ?>:<font color="#ff0000">*</font></td>
			<td colspan="3">
			<?php 
			if($program->id<1)
				{
					$points = 5;
					if(isset($_POST['points']) && intval($_POST['points'])>0)
						$points = intval($_POST['points']);
				}	
			else
				$points = $program->points;	?>			
			<input class="inputbox" type="text" name="points" size="8" maxlength="255" value="<?php echo $points; ?>" />
			</td>
		</tr>
		<tr>
		<tr>
			<td width="20%" nowrap> <?php echo JText::_('Time(minutes)');?>:<font color="#ff0000">*</font> </td>
			<td width="80%" colspan="3">
			<?php 
			if($program->id<1)
				{
					$time = 5;
					if(isset($_POST['time']) && intval($_POST['time'])>0)
						$time = intval($_POST['time']);
				}	
			else
				$time = $program->time;	?>				
				<input class="formField" type="text" name="time" size="10" value="<?php echo $time; ?>">
			</td>
		</tr>
		</table>
	</fieldset>	
	 <?php 
		echo $pane->endPanel();
	
		echo $pane->startPanel( JText::_('GURU_PUBLISHING'), "media-info" );
     ?>
    <fieldset class="adminform">
	<legend><?php if ($program->id<1) echo JText::_('GURU_NEW'); else echo JText::_('GURU_EDIT'); echo JText::_('GURU_TASK');?></legend>
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
		<?php 
		if ($program->id<1) $start_publish =  date('Y-m-d H:i:s', time()); else $start_publish =  $program->startpublish;
		echo JHTML::_('calendar', $start_publish, 'startpublish', 'startpublish', '%Y-%m-%d-%H-%M-%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
		</td>
	</tr>
	<tr>
		<td valign="top" align="right">
        <?php echo JText::_('GURU_PRODLEPUB'); ?>
		</td>
		<td>
		<?php 
		if(substr($program->endpublish,0,4) =='0000' || $program->id<1) $end_publish = JText::_('GURU_NEVER'); else $end_publish = $program->endpublish;  
		echo JHTML::_('calendar', $end_publish, 'endpublish', 'endpublish', '%Y-%m-%d-%H-%M-%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
		</td>
	</tr>
	</table>
	</fieldset>	
	 <?php 
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_('GURU_METATAGS'), "media-info" );
     ?>
      <fieldset class="adminform">
	<legend><?php if ($program->id<1) echo JText::_('GURU_NEW'); else echo JText::_('GURU_EDIT'); echo JText::_('GURU_TASK');?></legend>
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

		if ($program->id>0)
			$the_layout_is = guruAdminModelguruTask::select_layout($program->id);
		else
			$the_layout_is = 1;
			
			
		$layout_media1	= 0;
		$style_before_menu_med_1 = '';
		$style_after_menu_med_1 = 'style="display:none"';
		$layout_text1 = 0;
		$style_before_menu_txt_1 = '';
		$style_after_menu_txt_1 = 'style="display:none"';		
		if	($the_layout_is == 1 && $program->id>0)
			{
				$text_is_quiz = 0;
				$the_media_id = guruAdminModelguruTask::select_media($program->id, 1);
				if($the_media_id > 0)
					{
						$layout_media1	= $the_media_id;
						$layout_media1_content = guruAdminModelguruTask::parse_media ($layout_media1);
						$style_before_menu_med_1 = 'style="display:none"';
						$style_after_menu_med_1 = '';						
					}

				$the_text_obj = guruAdminModelguruTask::select_text($program->id);
				
				if($the_text_obj)
					{
						$the_text_obj = explode('$$$$$', $the_text_obj);
						$the_text_id = $the_text_obj[0];
						if($the_text_id > 0)
							{
								$layout_text1	= $the_text_id;
								$layout_text1_content = guruAdminModelguruTask::parse_txt ($layout_text1);
								$style_before_menu_txt_1 = 'style="display:none"';
								$style_after_menu_txt_1 = '';
								if($the_text_obj[1] == 2)
									{
										$text_is_quiz = 1;						
										$the_quiz_id = guruAdminModelguruTask::real_quiz_id($the_text_id);
									}	
							}
					}						
			}	
		$layout_media2	= 0;
		$layout_media3	= 0;
		$style_before_menu_med_2 = '';
		$style_after_menu_med_2 = 'style="display:none"';
		$style_before_menu_med_3 = '';
		$style_after_menu_med_3 = 'style="display:none"';	
		$layout_text2 = 0;
		$style_before_menu_txt_2 = '';
		$style_after_menu_txt_2 = 'style="display:none"';					
		if	($the_layout_is == 2)
			{
				$text_is_quiz = 0;
				
				$the_media_id = guruAdminModelguruTask::select_media($program->id, 1, 'scr_m');
				if($the_media_id > 0)
					{
						$layout_media2	= $the_media_id;
						$layout_media2_content = guruAdminModelguruTask::parse_media ($layout_media2);
						$style_before_menu_med_2 = 'style="display:none"';
						$style_after_menu_med_2 = '';						
					}
					
				$the_media_id = guruAdminModelguruTask::select_media($program->id, 2);
				if($the_media_id > 0)
					{
						$layout_media3	= $the_media_id;
						$layout_media3_content = guruAdminModelguruTask::parse_media ($layout_media3);
						$style_before_menu_med_3 = 'style="display:none"';
						$style_after_menu_med_3 = '';						
					}		
				
				$the_text_obj = guruAdminModelguruTask::select_text($program->id);
				
				if($the_text_obj)
					{				
						$the_text_obj = explode('$$$$$', $the_text_obj);
						$the_text_id = $the_text_obj[0];
						if($the_text_id > 0)
							{
								$layout_text2	= $the_text_id;
								$layout_text2_content = guruAdminModelguruTask::parse_txt ($layout_text2);
								$style_before_menu_txt_2 = 'style="display:none"';
								$style_after_menu_txt_2 = '';
								if($the_text_obj[1] == 2)
									{
										$text_is_quiz = 1;						
										$the_quiz_id = guruAdminModelguruTask::real_quiz_id($the_text_id);
									}						
							}
					}							
			}				

		$layout_media4	= 0;
		$style_before_menu_med_4 = '';
		$style_after_menu_med_4 = 'style="display:none"';
		$layout_text3 = 0;
		$style_before_menu_txt_3 = '';
		$style_after_menu_txt_3 = 'style="display:none"';		
		if	($the_layout_is == 3)
			{
				$text_is_quiz = 0;
				
				$the_media_id = guruAdminModelguruTask::select_media($program->id, 1);
				if($the_media_id > 0)
					{
						$layout_media4	= $the_media_id;
						$layout_media4_content = guruAdminModelguruTask::parse_media ($layout_media4);
						$style_before_menu_med_4 = 'style="display:none"';
						$style_after_menu_med_4 = '';						
					}
					
				$the_text_obj = guruAdminModelguruTask::select_text($program->id);
				
				if($the_text_obj)
					{				
						$the_text_obj = explode('$$$$$', $the_text_obj);
						$the_text_id = $the_text_obj[0];
						if($the_text_id > 0)
							{
								$layout_text3	= $the_text_id;
								$layout_text3_content = guruAdminModelguruTask::parse_txt ($layout_text3);
								$style_before_menu_txt_3 = 'style="display:none"';
								$style_after_menu_txt_3 = '';
								if($the_text_obj[1] == 2)
									{
										$text_is_quiz = 1;						
										$the_quiz_id = guruAdminModelguruTask::real_quiz_id($the_text_id);
									}						
							}
					}										
			}				
		$layout_media5	= 0;
		$layout_media6	= 0;
		$style_before_menu_med_5 = '';
		$style_after_menu_med_5 = 'style="display:none"';
		$style_before_menu_med_6 = '';
		$style_after_menu_med_6 = 'style="display:none"';		
		$layout_text4 = 0;
		$style_before_menu_txt_4 = '';
		$style_after_menu_txt_4 = 'style="display:none"';			
		if	($the_layout_is == 4)
			{
				$text_is_quiz = 0;
				
				$the_media_id = guruAdminModelguruTask::select_media($program->id, 1);
				if($the_media_id > 0)
					{
						$layout_media5	= $the_media_id;
						$layout_media5_content = guruAdminModelguruTask::parse_media ($layout_media5);
						$style_before_menu_med_5 = 'style="display:none"';
						$style_after_menu_med_5 = '';						
					}
					
				$the_media_id = guruAdminModelguruTask::select_media($program->id, 2);
				if($the_media_id > 0)
					{
						$layout_media6	= $the_media_id;
						$layout_media6_content = guruAdminModelguruTask::parse_media ($layout_media6);
						$style_before_menu_med_6 = 'style="display:none"';
						$style_after_menu_med_6 = '';						
					}		
				$the_text_obj = guruAdminModelguruTask::select_text($program->id);
				
				if($the_text_obj)
					{				
						$the_text_obj = explode('$$$$$', $the_text_obj);
						$the_text_id = $the_text_obj[0];
						if($the_text_id > 0)
							{
								$layout_text4	= $the_text_id;
								$layout_text4_content = guruAdminModelguruTask::parse_txt ($layout_text4);
								$style_before_menu_txt_4 = 'style="display:none"';
								$style_after_menu_txt_4 = '';
								if($the_text_obj[1] == 2)
									{
										$text_is_quiz = 1;						
										$the_quiz_id = guruAdminModelguruTask::real_quiz_id($the_text_id);
									}							
							}
					}											
			}	
		$layout_text5 = 0;
		$style_before_menu_txt_5 = '';
		$style_after_menu_txt_5 = 'style="display:none"';	
		if	($the_layout_is == 5)
			{
				$text_is_quiz = 0;
				
				$the_text_obj = guruAdminModelguruTask::select_text($program->id);
				
				if($the_text_obj)
					{				
						$the_text_obj = explode('$$$$$', $the_text_obj);
						$the_text_id = $the_text_obj[0];
						if($the_text_id > 0)
							{
								$layout_text5	= $the_text_id;
								$layout_text5_content = guruAdminModelguruTask::parse_txt ($layout_text5);
								$style_before_menu_txt_5 = 'style="display:none"';
								$style_after_menu_txt_5 = '';
								if($the_text_obj[1] == 2)
									{
										$text_is_quiz = 1;						
										$the_quiz_id = guruAdminModelguruTask::real_quiz_id($the_text_id);
									}						
							}		
					}
			}				
			
		$layout_media7	= 0;
		$style_before_menu_med_7 = '';
		$style_after_menu_med_7 = 'style="display:none"';
		if	($the_layout_is == 6)
			{
				$the_media_id = guruAdminModelguruTask::select_media($program->id, 1);
				if($the_media_id > 0)
					{
						$layout_media7	= $the_media_id;
						$layout_media7_content = guruAdminModelguruTask::parse_media ($layout_media7);
						$style_before_menu_med_7 = 'style="display:none"';
						$style_after_menu_med_7 = '';						
					}
			}						
		echo $pane->startPanel( JText::_('Layout'), "layout" );
		$srcimg = JURI::base()."/components/com_guru/images/";

		$layout_styledisplay = array(1 => 'style="display:none"',2 => 'style="display:none"',3 => 'style="display:none"',4 => 'style="display:none"',5 => 'style="display:none"',6 => 'style="display:none"');
		$layout_styledisplay[$the_layout_is] = 'style=""';

		
		$layout_imgstyle = array(1 => '',2 => '',3 => '',4 => '',5 => '',6 => '');
		$layout_imgstyle[$the_layout_is] = 'style="border:3px; border-style:solid; border-color:#0000FF;"';	

     ?>
    <fieldset class="adminform">
	<legend><?php if ($program->id<1) echo JText::_('GURU_NEW'); else echo JText::_('GURU_EDIT'); echo JText::_('GURU_TASK');?></legend>
	<table class="adminform" style="width:800px;">
	<tr>
		<td>
			<?php echo JText::_("GURU_SEL_LAY"); ?>
		</td>
	</tr>
	<tr>
		<td>
			<table style="width:350px; ">
				<tr>
					<td style="width:50px;">
						<img onClick="javascript:ChangeLayout(1);" id="layout_img_1" <?php echo $layout_imgstyle[1];//$layout1_imgstyle; ?> src="<?php echo $srcimg.'screen-1.gif';?>" alt="" />
					</td>
					<td style="width:50px;">
						<img onClick="javascript:ChangeLayout(2);" id="layout_img_2" <?php echo $layout_imgstyle[2];//$layout2_imgstyle; ?> src="<?php echo $srcimg.'screen-2.gif';?>" alt="" />
					</td>
					<td style="width:50px;">
						<img onClick="javascript:ChangeLayout(3);" id="layout_img_3" <?php echo $layout_imgstyle[3];//$layout3_imgstyle; ?> src="<?php echo $srcimg.'screen-3.gif';?>" alt="" />
					</td>
					<td style="width:50px;">
						<img onClick="javascript:ChangeLayout(4);" id="layout_img_4" <?php echo $layout_imgstyle[4];//$layout4_imgstyle; ?> src="<?php echo $srcimg.'screen-4.gif';?>" alt="" />
					</td>
					<td style="width:50px;">
						<img onClick="javascript:ChangeLayout(5);" id="layout_img_5" <?php echo $layout_imgstyle[5];//$layout5_imgstyle; ?> src="<?php echo $srcimg.'screen-5.gif';?>" alt="" />
					</td>	
					<td style="width:50px;">
						<img onClick="javascript:ChangeLayout(6);" id="layout_img_6" <?php echo $layout_imgstyle[6];//$layout6_imgstyle; ?> src="<?php echo $srcimg.'screen-6.gif';?>" alt="" />
					</td>																											
				</tr>	
			</table>
		</td>
	</tr>
	<input name="text_is_quiz" type="hidden" value="<?php if(isset($text_is_quiz)) {echo $text_is_quiz;} else {$text_is_quiz=0;}?>">
	<tr id="layout1" <?php echo $layout_styledisplay[1];//$layout1_styledisplay; ?>>
		<td>
			<table style="border-bottom:1px solid; border-left:1px solid; border-top:1px solid; border-right:1px solid">
				<tr>
					<td>
						<table align="center" style="width:200px; border:1px solid;">
							<tr bgcolor="#FFFFCC">
								<td style="text-align:center" id="menu_med_1">
									<div id="before_menu_med_1" <?php echo $style_before_menu_med_1;?>>
									<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=1" class="modal"><?php echo JText::_("GURU_SELECT_MEDIA"); ?></a>
									</div>
									<div id="after_menu_med_1" <?php echo $style_after_menu_med_1;?>>
										<table>
											<tr>
												<td width="50%" style="text-align:center">
													<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=1" class="modal"><?php echo JText::_("GURU_REPLACE_MEDIA"); ?></a>
												</td>
												<td width="50%" style="text-align:center">
													<a id="a_edit_media_1" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruMedia&tmpl=component&task=editsboxx&cid[]=<?php echo $the_media_id;?>&scr=<?php echo $program->id;?>"><?php echo JText::_("GURU_DAY_EDIT_MEDIA"); ?></a>
												</td>
											</tr>
										</table>
									</div>									
								</td>
							</tr>
						</table>	
					</td>
					<td>
						<table align="center" style="width:200px; border:1px solid;">
							<tr bgcolor="#FFFFCC">
								<td style="text-align:center">
									<div id="before_menu_txt_1" <?php echo $style_before_menu_txt_1;?>>
									<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addtext&no_html=1&cid[]=<?php echo $program->id;?>&txt=1" class="modal">Select text</a>
									</div>
									<div id="after_menu_txt_1" <?php echo $style_after_menu_txt_1;?>>
										<table>
											<tr>
												<td width="50%" style="text-align:center">
													<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addtext&no_html=1&cid[]=<?php echo $program->id;?>&txt=1" class="modal">Replace text</a>
												</td>
												<td width="50%" style="text-align:center">
													<?php if ($text_is_quiz == 0) {?>
													<a id="a_edit_text_1" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruMedia&tmpl=component&task=editsboxx&cid[]=<?php echo $the_text_id;?>&scr=<?php echo $program->id;?>"><?php echo JText::_("GURU_EDIT_TEXT"); ?></a>
													<?php } else {?>
													<a id="a_edit_text_1" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruQuiz&tmpl=component&task=editsbox&cid[]=<?php echo $the_quiz_id;?>&scr=<?php echo $program->id;?>"><?php echo JText::_("GURU_EDIT_TEXT"); ?></a>
													<?php } ?>
												</td>
											</tr>
										</table>
									</div>									
								</td>
							</tr>
						</table>	
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table>
							<tr>
								<td>
								<div id="media_1">
									<?php if ($layout_media1 == 0) {?>
									<img src="<?php echo $srcimg.'screen-media.gif';?>" alt="" />
									<?php }else{
									echo $layout_media1_content;
									 }?>
								</div>	
								</td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<table>
							<tr>
								<td>
								<div id="text_1">
									<?php if ($layout_text1 == 0) {?>
									<img src="<?php echo $srcimg.'screen-text.gif';?>" alt="" />
									<?php }else{
									echo $layout_text1_content;
									 }?>
								</div>										
								</td>
							</tr>
						</table>
					</td>					
				</tr>
			</table>
		</td>
	</tr>
	<tr id="layout2" <?php echo $layout_styledisplay[2];//$layout2_styledisplay; ?>>
		<td>
			<table style="border-bottom:1px solid; border-left:1px solid; border-top:1px solid; border-right:1px solid">
				<tr>
					<td>
						<table align="center" style="width:200px; border:1px solid;">
							<tr bgcolor="#FFFFCC">
								<td style="text-align:center"  id="menu_med_2">
									<div id="before_menu_med_2"  <?php echo $style_before_menu_med_2;?>>
									<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=2" class="modal">Select media1</a>
									</div>
									<div id="after_menu_med_2"  <?php echo $style_after_menu_med_2;?>>
										<table>
											<tr>
												<td width="50%" style="text-align:center">
													<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=2" class="modal">Replace media</a>
												</td>
												<td width="50%" style="text-align:center">
													<a id="a_edit_media_2" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruMedia&tmpl=component&task=editsboxx&cid[]=<?php echo $the_media_id;?>&scr=<?php echo $program->id;?>">Edit media</a>
												</td>
											</tr>
										</table>
									</div>										
								</td>
							</tr>
						</table>	
					</td>
					<td>
						<table align="center" style="width:200px; border:1px solid;">
							<tr bgcolor="#FFFFCC">
								<td style="text-align:center">
									<div id="before_menu_txt_2" <?php echo $style_before_menu_txt_2;?>>
									<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addtext&no_html=1&cid[]=<?php echo $program->id;?>&txt=2" class="modal">Select text</a>
									</div>
									<div id="after_menu_txt_2" <?php echo $style_after_menu_txt_2;?>>
										<table>
											<tr>
												<td width="50%" style="text-align:center">
													<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addtext&no_html=1&cid[]=<?php echo $program->id;?>&txt=2" class="modal">Replace text</a>
												</td>
												<td width="50%" style="text-align:center">
													<?php if ($text_is_quiz == 0) {?>
													<a id="a_edit_text_2" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruMedia&tmpl=component&task=editsboxx&cid[]=<?php echo $the_text_id;?>&scr=<?php echo $program->id;?>"><?php echo JText::_("GURU_EDIT_TEXT"); ?></a>
													<?php } else {?>
													<a id="a_edit_text_2" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruQuiz&tmpl=component&task=editsbox&cid[]=<?php echo $the_text_id;?>&scr=<?php echo $program->id;?>"><?php echo JText::_("GURU_EDIT_TEXT"); ?></a>
													<?php } ?>
												</td>
											</tr>
										</table>
									</div>			
								</td>
							</tr>
						</table>	
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table>
							<tr>
								<td>
								<div id="media_2">
									<?php if ($layout_media2 == 0) {?>
									<img src="<?php echo $srcimg.'screen-media.gif';?>" alt="" />
									<?php }else{
									echo $layout_media2_content;
									 }?>								
								</div>
								</td>
							</tr>
						</table>
					</td>
					<td valign="top" rowspan="3">
						<table>
							<tr>
								<td>
								<div id="text_2">
									<?php if ($layout_text2 == 0) {?>
									<img height="530px" width="440px" src="<?php echo $srcimg.'screen-text.gif';?>" alt="" />
									<?php }else{
									echo $layout_text2_content;
									 }?>		
								</div>	 						
								</td>
							</tr>
						</table>
					</td>					
				</tr>

				<tr>
					<td valign="top">
						<table align="center" style="width:200px; border:1px solid;">
							<tr bgcolor="#FFFFCC">
								<td style="text-align:center"  id="menu_med_3">
									<div id="before_menu_med_3"  <?php echo $style_before_menu_med_3;?>>
									<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=3" class="modal">Select media2</a>
									</div>
									<div id="after_menu_med_3"  <?php echo $style_after_menu_med_3;?>>
										<table>
											<tr>
												<td width="50%" style="text-align:center">
													<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=3" class="modal">Replace media</a>
												</td>
												<td width="50%" style="text-align:center">
													<a id="a_edit_media_3" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruMedia&tmpl=component&task=editsboxx&cid[]=<?php echo $the_media_id;?>&scr=<?php echo $program->id;?>">Edit media</a>
												</td>
											</tr>
										</table>
									</div>										
								</td>
							</tr>
						</table>	
					</td>
				</tr>					
				
				<tr>
					<td valign="top">
						<table>
							<tr>
								<td>
								<div id="media_3">
									<?php if ($layout_media3 == 0) {?>
									<img src="<?php echo $srcimg.'screen-media.gif';?>" alt="" />
									<?php }else{
									echo $layout_media3_content;
									 }?>								
								</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>				
			</table>

		</td>
	</tr>
	
	<tr id="layout3" <?php echo $layout_styledisplay[3];//$layout3_styledisplay; ?>>
		<td>
			<table style="border-bottom:1px solid; border-left:1px solid; border-top:1px solid; border-right:1px solid">
				<tr>
					<td>
						<table align="center" style="width:200px; border:1px solid;">
							<tr bgcolor="#FFFFCC">
								<td style="text-align:center" id="menu_med_4">
									<div id="before_menu_med_4"  <?php echo $style_before_menu_med_4;?>>
									<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=4" class="modal">Select media3</a>
									</div>
									<div id="after_menu_med_4"  <?php echo $style_after_menu_med_4;?>>
										<table>
											<tr>
												<td width="50%" style="text-align:center">
													<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=4" class="modal">Replace media</a>
												</td>
												<td width="50%" style="text-align:center">
													<a id="a_edit_media_4" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruMedia&tmpl=component&task=editsboxx&cid[]=<?php echo $the_media_id;?>&scr=<?php echo $program->id;?>">Edit media</a>
												</td>
											</tr>
										</table>
									</div>	
								</td>
							</tr>
						</table>	
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table>
							<tr>
								<td>
								<div id="media_4">
									<?php if ($layout_media4 == 0) {?>
									<img height="240" width="778" src="<?php echo $srcimg.'screen-media.gif';?>" alt="" />
									<?php }else{
									echo $layout_media4_content;
									 }?>								
								</div>								
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table align="center" style="width:200px; border:1px solid;">
							<tr bgcolor="#FFFFCC">
								<td style="text-align:center">
									<div id="before_menu_txt_3" <?php echo $style_before_menu_txt_3;?>>
									<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addtext&no_html=1&cid[]=<?php echo $program->id;?>&txt=3" class="modal">Select text</a>
									</div>
									<div id="after_menu_txt_3" <?php echo $style_after_menu_txt_3;?>>
										<table>
											<tr>
												<td width="50%" style="text-align:center">
													<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addtext&no_html=1&cid[]=<?php echo $program->id;?>&txt=3" class="modal">Replace text</a>
												</td>
												<td width="50%" style="text-align:center">
													<?php if ($text_is_quiz == 0) {?>
													<a id="a_edit_text_3" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruMedia&tmpl=component&task=editsboxx&cid[]=<?php echo $the_text_id;?>&scr=<?php echo $program->id;?>"><?php echo JText::_("GURU_EDIT_TEXT"); ?></a>
													<?php } else {?>
													<a id="a_edit_text_3" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruQuiz&tmpl=component&task=editsbox&cid[]=<?php echo $the_text_id;?>&scr=<?php echo $program->id;?>"><?php echo JText::_("GURU_EDIT_TEXT"); ?></a>
													<?php } ?>
												</td>
											</tr>
										</table>
									</div>	
								</td>
							</tr>
						</table>	
					</td>						
				</tr>
				<tr>
					<td valign="top">
						<table>
							<tr>
								<td>
								<div id="text_3">
									<?php if ($layout_text3 == 0) {?>
									<img height="240" width="778"  src="<?php echo $srcimg.'screen-text.gif';?>" alt="" />
									<?php }else{
									echo $layout_text3_content;
									 }?>		
								</div>	
								</td>
							</tr>
						</table>
					</td>					
				</tr>
			</table>
		</td>
	</tr>
	
	<tr id="layout4" <?php echo $layout_styledisplay[4];//$layout4_styledisplay; ?>>
		<td>
			<table style="border-bottom:1px solid; border-left:1px solid; border-top:1px solid; border-right:1px solid">
				<tr>
					<td>
						<table align="center" style="width:200px; border:1px solid;">
							<tr bgcolor="#FFFFCC">
								<td style="text-align:center" id="menu_med_5">
									<div id="before_menu_med_5"  <?php echo $style_before_menu_med_5;?>>
									<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=5" class="modal">Select media4</a>
									</div>
									<div id="after_menu_med_5" <?php echo $style_after_menu_med_5;?>>
										<table>
											<tr>
												<td width="50%" style="text-align:center">
													<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=5" class="modal">Replace media</a>
												</td>
												<td width="50%" style="text-align:center">
													<a id="a_edit_media_5" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruMedia&tmpl=component&task=editsboxx&cid[]=<?php echo $the_media_id;?>&scr=<?php echo $program->id;?>">Edit media</a>
												</td>
											</tr>
										</table>
									</div>										
								</td>
							</tr>
						</table>	
					</td>
					<td>
						<table align="center" style="width:200px; border:1px solid;">
							<tr bgcolor="#FFFFCC">
								<td style="text-align:center" id="menu_med_6">
									<div id="before_menu_med_6" <?php echo $style_before_menu_med_6;?>>
									<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=6" class="modal">Select media5</a>
									</div>
									<div id="after_menu_med_6" <?php echo $style_after_menu_med_6;?>>
										<table>
											<tr>
												<td width="50%" style="text-align:center">
													<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=6" class="modal">Replace media</a>
												</td>
												<td width="50%" style="text-align:center">
													<a id="a_edit_media_6" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruMedia&tmpl=component&task=editsboxx&cid[]=<?php echo $the_media_id;?>&scr=<?php echo $program->id;?>">Edit media</a>
												</td>
											</tr>
										</table>
									</div>	
								</td>
							</tr>
						</table>	
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table>
							<tr>
								<td>
									<div id="media_5">
									<?php if ($layout_media5 == 0) {?>
									<img height="240" width="380" src="<?php echo $srcimg.'screen-media.gif';?>" alt="" />
									<?php }else{
									echo $layout_media5_content;
									 }?>									
									</div>
								</td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<table>
							<tr>
								<td>
									<div id="media_6">
									<?php if ($layout_media6 == 0) {?>
									<img height="240" width="380" src="<?php echo $srcimg.'screen-media.gif';?>" alt="" />
									<?php }else{
									echo $layout_media6_content;
									 }?>									
									</div>
								</td>
							</tr>
						</table>
					</td>					
				</tr>
				<tr>
					<td valign="top" colspan="2">
						<table align="center" style="width:200px; border:1px solid;">
							<tr bgcolor="#FFFFCC">
								<td style="text-align:center">
									<div id="before_menu_txt_4" <?php echo $style_before_menu_txt_4;?>>
									<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addtext&no_html=1&cid[]=<?php echo $program->id;?>&txt=4" class="modal">Select text</a>
									</div>
									<div id="after_menu_txt_4" <?php echo $style_after_menu_txt_4;?>>
										<table>
											<tr>
												<td width="50%" style="text-align:center">
													<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addtext&no_html=1&cid[]=<?php echo $program->id;?>&txt=4" class="modal">Replace text</a>
												</td>
												<td width="50%" style="text-align:center">
													<?php if ($text_is_quiz == 0) {?>
													<a id="a_edit_text_4" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruMedia&tmpl=component&task=editsboxx&cid[]=<?php echo $the_text_id;?>&scr=<?php echo $program->id;?>"><?php echo JText::_("GURU_EDIT_TEXT"); ?></a>
													<?php } else {?>
													<a id="a_edit_text_4" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruQuiz&tmpl=component&task=editsbox&cid[]=<?php echo $the_text_id;?>&scr=<?php echo $program->id;?>"><?php echo JText::_("GURU_EDIT_TEXT"); ?></a>
													<?php } ?>
												</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>				
				<tr>
					<td valign="top" colspan="2">
						<table>
							<tr>
								<td>
								<div id="text_4">
									<?php if ($layout_text4 == 0) {?>
									<img height="240" width="775"  src="<?php echo $srcimg.'screen-text.gif';?>" alt="" />
									<?php }else{
									echo $layout_text4_content;
									 }?>		
								</div>	
								</td>
							</tr>
						</table>
					</td>
				</tr>				
			</table>
		</td>
	</tr>
	<tr id="layout5" <?php echo $layout_styledisplay[5];//$layout5_styledisplay; ?>>
		<td>
			<table style="border-bottom:1px solid; border-left:1px solid; border-top:1px solid; border-right:1px solid">
				<tr>
					<td>
						<table align="center" style="width:200px; border:1px solid;">
							<tr bgcolor="#FFFFCC">
								<td style="text-align:center">
									<div id="before_menu_txt_5" <?php echo $style_before_menu_txt_5;?>>
									<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addtext&no_html=1&cid[]=<?php echo $program->id;?>&txt=5" class="modal">Select text</a>
									</div>
									<div id="after_menu_txt_5" <?php echo $style_after_menu_txt_5;?>>
										<table>
											<tr>
												<td width="50%" style="text-align:center">
													<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addtext&no_html=1&cid[]=<?php echo $program->id;?>&txt=5" class="modal">Replace text</a>
												</td>
												<td width="50%" style="text-align:center">
													<?php if ($text_is_quiz == 0) {?>
													<a id="a_edit_text_5" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruMedia&tmpl=component&task=editsboxx&cid[]=<?php echo $the_text_id;?>&scr=<?php echo $program->id;?>"><?php echo JText::_("GURU_EDIT_TEXT"); ?></a>
													<?php } else {?>
													<a id="a_edit_text_5" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruQuiz&tmpl=component&task=editsbox&cid[]=<?php echo $the_text_id;?>&scr=<?php echo $program->id;?>"><?php echo JText::_("GURU_EDIT_TEXT"); ?></a>
													<?php } ?>
												</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</table>	
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table>
							<tr>
								<td>
								<div id="text_5">
									<?php if ($layout_text5 == 0) {?>
									<img height="359" width="778" src="<?php echo $srcimg.'screen-text.gif';?>" alt="" />
									<?php }else{
									echo $layout_text5_content;
									 }?>		
								</div>	
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr id="layout6" <?php echo $layout_styledisplay[6];//$layout6_styledisplay; ?>>
		<td>
			<table style="border-bottom:1px solid; border-left:1px solid; border-top:1px solid; border-right:1px solid">
				<tr>
					<td>
						<table align="center" style="width:200px; border:1px solid;">
							<tr bgcolor="#FFFFCC">
								<td style="text-align:center" id="menu_med_7">
									<div id="before_menu_med_7" <?php echo $style_before_menu_med_7;?>>
									<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=7" class="modal">Select media6</a>
									</div>
									<div id="after_menu_med_7" <?php echo $style_after_menu_med_7;?>>
										<table>
											<tr>
												<td width="50%" style="text-align:center">
													<a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruTasks&task=addmedia&no_html=1&cid[]=<?php echo $program->id;?>&med=7" class="modal">Replace media</a>
												</td>
												<td width="50%" style="text-align:center">
													<a id="a_edit_media_7" class="modal" rel="{handler: 'iframe', size: {x: 850, y: 500}}" href="index.php?option=com_guru&controller=guruMedia&tmpl=component&task=editsboxx&cid[]=<?php echo $the_media_id;?>&scr=<?php echo $program->id;?>">Edit media</a>
												</td>
											</tr>
										</table>
									</div>	
								</td>
							</tr>
						</table>	
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table>
							<tr>
								<td>
									<div id="media_7">
										<?php
                                        	if($layout_media7 == 0){
										?>
                                        		<img height="359" width="778" src="<?php echo $srcimg.'screen-media.gif';?>" alt="" />
                                        <?php
                                        	}
											else{
                                        		echo $layout_media7_content;
                                         	}
										?>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
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
		<input type="hidden" name="option" value="com_guru" />
		<input type="hidden" name="controller" value="guruTasks" />
		<input type="hidden" name="layout_db" id="layout_db" value="<?php echo $the_layout_is; ?>" />
		<input type="hidden" name="db_media_1" id="db_media_1" value="<?php echo $layout_media1; ?>" />
		<input type="hidden" name="db_media_2" id="db_media_2" value="<?php echo $layout_media2; ?>" />
		<input type="hidden" name="db_media_3" id="db_media_3" value="<?php echo $layout_media3; ?>" />
		<input type="hidden" name="db_media_4" id="db_media_4" value="<?php echo $layout_media4; ?>" />
		<input type="hidden" name="db_media_5" id="db_media_5" value="<?php echo $layout_media5; ?>" />
		<input type="hidden" name="db_media_6" id="db_media_6" value="<?php echo $layout_media6; ?>" />
		<input type="hidden" name="db_media_7" id="db_media_7" value="<?php echo $layout_media7; ?>" />
		<input type="hidden" name="db_text_1" id="db_text_1" value="<?php echo $layout_text1; ?>" />
		<input type="hidden" name="db_text_2" id="db_text_2" value="<?php echo $layout_text2; ?>" />
		<input type="hidden" name="db_text_3" id="db_text_3" value="<?php echo $layout_text3; ?>" />
		<input type="hidden" name="db_text_4" id="db_text_4" value="<?php echo $layout_text4; ?>" />
		<input type="hidden" name="db_text_5" id="db_text_5" value="<?php echo $layout_text5; ?>" />	
</form>