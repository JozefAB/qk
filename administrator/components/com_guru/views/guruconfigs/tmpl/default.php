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
	$tab = JRequest::getVar("tab", "0");
	$configs = $this->configs;
	$lists = $configs->lists;
	$editor  = JFactory::getEditor();
	$admins = $this->superadmins;
		
	$doc =JFactory::getDocument();
	$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');	
	$doc->addScript('components/com_guru/js/jquery.noconflict.js');
	$doc->addScript('components/com_guru/js/jquery.DOMWindow.js');
	$doc->addScript('components/com_guru/js/freecourse.js');
	
	$template_emails = json_decode($configs->template_emails);
	
	require_once JPATH_ADMINISTRATOR.'/components/com_users/helpers/users.php';
?>

<script language="JavaScript" type="text/javascript" src="<?php echo JURI::base(); ?>components/com_guru/js/colorpicker.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo JURI::base(); ?>components/com_guru/js/freecourse.js"></script>
<script language="javascript" type="text/javascript">        
	
	function validateColor(color){
		if(color == ''){
			return true
		}
		if(/^[0-9A-F]{6}$/i.test(color)){
			return true;
		}
		return false;
	}
	
	function makeVisibleProfileT(value){
		if(value == 1){
			document.getElementById('gurujomsocialregteachermprof').style.display = "block";	
		}
		else{
			document.getElementById('gurujomsocialregteachermprof').style.display = "none";
		}
	
	}
	
	function makeVisibleProfileS(value){
		if(value == 1){
			document.getElementById('gurujomsocialregstudentmprof').style.display = "block";	
		}
		else{
			document.getElementById('gurujomsocialregstudentmprof').style.display = "none";
		}
	}
	
	function IsValidNumeric(sText){
		var ValidChars = "0123456789.";
		var IsNumber=true;
		var Char;
		for (i = 0; i < sText.length && IsNumber == true; i++) { 
			Char = sText.charAt(i); 
			if (ValidChars.indexOf(Char) == -1)  { IsNumber = false; }
		}
		return IsNumber;
	}
	
	function emailTemplate(value){
		if(value == 1){
			document.getElementById("course-approve-email").style.display = "block";
			document.getElementById("course-unapprove-email").style.display = "none";
			document.getElementById("for-course-email").style.display = "none";
			document.getElementById("approved-teacher").style.display = "none";
			document.getElementById("pending-teacher").style.display = "none";
			document.getElementById("for-teacher-approve").style.display = "none";
			document.getElementById("for-teacher-registered").style.display = "none";
			document.getElementById("approved-order").style.display = "none";
			document.getElementById("pending-order").style.display = "none";
		}
		else if(value == 2){
			document.getElementById("course-approve-email").style.display = "none";
			document.getElementById("course-unapprove-email").style.display = "block";
			document.getElementById("for-course-email").style.display = "none";
			document.getElementById("approved-teacher").style.display = "none";
			document.getElementById("pending-teacher").style.display = "none";
			document.getElementById("for-teacher-approve").style.display = "none";
			document.getElementById("for-teacher-registered").style.display = "none";
			document.getElementById("approved-order").style.display = "none";
			document.getElementById("pending-order").style.display = "none";
		}
		else if(value == 3){
			document.getElementById("course-approve-email").style.display = "none";
			document.getElementById("course-unapprove-email").style.display = "none";
			document.getElementById("for-course-email").style.display = "block";
			document.getElementById("approved-teacher").style.display = "none";
			document.getElementById("pending-teacher").style.display = "none";
			document.getElementById("for-teacher-approve").style.display = "none";
			document.getElementById("for-teacher-registered").style.display = "none";
			document.getElementById("approved-order").style.display = "none";
			document.getElementById("pending-order").style.display = "none";
		}
		else if(value == 4){
			document.getElementById("course-approve-email").style.display = "none";
			document.getElementById("course-unapprove-email").style.display = "none";
			document.getElementById("for-course-email").style.display = "none";
			document.getElementById("approved-teacher").style.display = "block";
			document.getElementById("pending-teacher").style.display = "none";
			document.getElementById("for-teacher-approve").style.display = "none";
			document.getElementById("for-teacher-registered").style.display = "none";
			document.getElementById("approved-order").style.display = "none";
			document.getElementById("pending-order").style.display = "none";
		}
		else if(value == 5){
			document.getElementById("course-approve-email").style.display = "none";
			document.getElementById("course-unapprove-email").style.display = "none";
			document.getElementById("for-course-email").style.display = "none";
			document.getElementById("approved-teacher").style.display = "none";
			document.getElementById("pending-teacher").style.display = "block";
			document.getElementById("for-teacher-approve").style.display = "none";
			document.getElementById("for-teacher-registered").style.display = "none";
			document.getElementById("approved-order").style.display = "none";
			document.getElementById("pending-order").style.display = "none";
		}
		else if(value == 6){
			document.getElementById("course-approve-email").style.display = "none";
			document.getElementById("course-unapprove-email").style.display = "none";
			document.getElementById("for-course-email").style.display = "none";
			document.getElementById("approved-teacher").style.display = "none";
			document.getElementById("pending-teacher").style.display = "none";
			document.getElementById("for-teacher-approve").style.display = "block";
			document.getElementById("for-teacher-registered").style.display = "none";
			document.getElementById("approved-order").style.display = "none";
			document.getElementById("pending-order").style.display = "none";
		}
		else if(value == 7){
			document.getElementById("course-approve-email").style.display = "none";
			document.getElementById("course-unapprove-email").style.display = "none";
			document.getElementById("for-course-email").style.display = "none";
			document.getElementById("approved-teacher").style.display = "none";
			document.getElementById("pending-teacher").style.display = "none";
			document.getElementById("for-teacher-approve").style.display = "none";
			document.getElementById("for-teacher-registered").style.display = "block";
			document.getElementById("approved-order").style.display = "none";
			document.getElementById("pending-order").style.display = "none";
		}
		else if(value == 8){
			document.getElementById("course-approve-email").style.display = "none";
			document.getElementById("course-unapprove-email").style.display = "none";
			document.getElementById("for-course-email").style.display = "none";
			document.getElementById("approved-teacher").style.display = "none";
			document.getElementById("pending-teacher").style.display = "none";
			document.getElementById("for-teacher-approve").style.display = "none";
			document.getElementById("for-teacher-registered").style.display = "none";
			document.getElementById("approved-order").style.display = "block";
			document.getElementById("pending-order").style.display = "none";
		}
		else if(value == 10){
			document.getElementById("course-approve-email").style.display = "none";
			document.getElementById("course-unapprove-email").style.display = "none";
			document.getElementById("for-course-email").style.display = "none";
			document.getElementById("approved-teacher").style.display = "none";
			document.getElementById("pending-teacher").style.display = "none";
			document.getElementById("for-teacher-approve").style.display = "none";
			document.getElementById("for-teacher-registered").style.display = "none";
			document.getElementById("approved-order").style.display = "none";
			document.getElementById("pending-order").style.display = "block";
		}
		else{
			document.getElementById("course-approve-email").style.display = "none";
			document.getElementById("course-unapprove-email").style.display = "none";
			document.getElementById("for-course-email").style.display = "none";
			document.getElementById("approved-teacher").style.display = "none";
			document.getElementById("pending-teacher").style.display = "none";
			document.getElementById("for-teacher-approve").style.display = "none";
			document.getElementById("for-teacher-registered").style.display = "none";
			document.getElementById("approved-order").style.display = "none";
			document.getElementById("pending-order").style.display = "none";
		}
	}
	
	Joomla.submitbutton = function(pressbutton){
			var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i

		<?php
			$validation_script = "";
			if($tab == "0"){
				$validation_script = 'if(document.adminForm.default_video_size_width.value != "" && !IsValidNumeric(document.adminForm.default_video_size_width.value)){
									  		alert("'.JText::_("GURU_DEFAULT_VIDEO_SIZE_NUMBER").'");
											return false;
									  }
									  else if(document.adminForm.default_video_size_width.value == 0){
									  		alert("'.JText::_("GURU_DEFAULT_VIDEO_SIZE_NUMBER").'");
											return false;
									  }
									  else if(document.adminForm.default_video_size_height.value != "" && !IsValidNumeric(document.adminForm.default_video_size_height.value)){
									  		alert("'.JText::_("GURU_DEFAULT_VIDEO_SIZE_NUMBER").'");
											return false;
									  }
									  else if(document.adminForm.default_video_size_height.value == 0){
									  		alert("'.JText::_("GURU_DEFAULT_VIDEO_SIZE_NUMBER").'");
											return false;
									  }';
				echo $validation_script;
			}
			if($tab == "1"){
				$validation_script = 'if(document.adminForm.imagesin.value==""){
									  		alert("'.JText::_("GURU_IMAGE_PATH_MANDATORY").'");
											return false;
									  }
									  else if(document.adminForm.videoin.value==""){
									  		alert("'.JText::_("GURU_VIDEO_PATH_MANDATORY").'");
											return false;
									  }
									  else if(document.adminForm.audioin.value==""){
									  		alert("'.JText::_("GURU_AUDIO_PATH_MANDATORY").'");
											return false;
									  }
									  else if(document.adminForm.docsin.value==""){
									  		alert("'.JText::_("GURU_DOC_PATH_MANDATORY").'");
											return false;
									  }
									  else if(document.adminForm.filesin.value==""){
									  		alert("'.JText::_("GURU_FILE_PATH_MANDATORY").'");
											return false;
									  }
									  else if(document.adminForm.filesin1.value==""){
									  		alert("'.JText::_("GURU_CERT_PATH_MANDATORY").'");
											return false;
									  }';
				echo $validation_script;
			}
			if($tab == "4"){
				
				$validation_script = 'if(!validateColor(document.getElementById("pick_rdonecolorfield").value)){
									  		alert("'.JText::_("GURU_ALERT_INVALID_COLOR").'");
											return false;
									  }
									  else if(!validateColor(document.getElementById("pick_pnotdonecolorfield").value)){
									  		alert("'.JText::_("GURU_ALERT_INVALID_COLOR").'");
											return false;
									  }
									  else if(!validateColor(document.getElementById("pick_stxtcolorfield").value)){
									  		alert("'.JText::_("GURU_ALERT_INVALID_COLOR").'");
											return false;
									  }';
				echo $validation_script;
			}
			if($tab == "5"){
				
				$validation_script = 'if(document.adminForm.fromname.value==""){
									  		alert("'.JText::_("GURU_PROVIDE_NAME").'");
											return false;
									  }
									  else if(document.adminForm.fromemail.value==""){
									  		alert("'.JText::_("GURU_PROVIDE_EMAIL_ADDRESS").'");
											return false;
									  }
									  else if (!filter.test(document.adminForm.fromemail.value)){
											alert ("'.JText::_("GURU_PROVIDE_VALID_EMAIL").'");
											return false;
									  }';
			  echo $validation_script;
			}
		?>
		submitform( pressbutton );
	}
</script>
 <div id="myModal" class="modal hide">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    </div>
    <div class="modal-body">
    </div>
</div>
<style>
	select {
  	 width: 180px!important;
	}

</style>
<form action="index.php" class="form-horizontal" method="post" name="adminForm" id="adminForm">
<div class="">
	<?php
		if($tab == "0"){
	?>
   
    <div class="container-fluid">
          <a data-toggle="modal" data-target="#myModal" class="pull-right guru_video" onclick="showContentVideo('index.php?option=com_guru&controller=guruAbout&task=vimeo&id=29999457&tmpl=component')" href="#">
                    <img src="<?php echo JURI::base(); ?>components/com_guru/images/icon_video.gif" class="video_img" />
                <?php echo JText::_("GURU_GENERAL_VIDEO"); ?>                  
          </a>
	</div>	
	<div class="clearfix"></div>
    <div class="well well-minimized">
		<?php echo JText::_("GURU_GENERAL_SETTINGS_DESCRIPTION"); ?>
	</div>
    <div class="widget-header widget-header-flat"><h5><?php echo  JText::_('GURU_GENERAL')." ".JText::_('GURU_SETTINGS');?></h5></div>
    <div class="widget-body">
    	<div class="widget-main">	
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_CURRENCY');?>:</label>
                                <div class="controls">
                                    <?php echo $lists['currency']; ?>
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_CURRENCY"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_CURRENCY_POSITION');?>:		
                                  </label>
                                  <div class="controls">
                                    <select id="currencypos" name="currencypos">
                                        <option value="0" <?php if($configs->currencypos == "0"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_POS_BEFORE"); ?></option>
                                        <option value="1" <?php if($configs->currencypos == "1"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_POS_AFTER"); ?></option>
                                    </select>
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_CURRENCY_POSITION"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_DATETIME');?>:		
                                  </label>
                                  <div class="controls">
                                    <?php echo $lists['date_format']; ?>
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DATE_TIME"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                 </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_HOUR_FORMAT');?>:		
                                  </label>
                                  <div class="controls">
                                    <?php echo $lists['hour_format']; ?>
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DATE_TIME"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                 </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_OPEN_STEP');?>:		
                                  </label>
                                  <div class="controls">
                                    <?php echo $lists['target']; ?>
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_OPEN_STEP"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                 </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_INVOICEISSUED_BY');?>	
                                  </label>
                                    <div class="controls">
                                        <textarea name="invoice_issued_by"><?php echo $configs->invoice_issued_by; ?></textarea>
                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_INVOICEISSUED_BY"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                        </span>
                                    </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>
			<div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_NOTIFICATION');?>:		
									</label>
									<div class="controls">
										<input type="hidden" name="notification" value="1">
										<?php
											$checked = '';
											if($configs->notification == 0){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="notification">
										<span class="lbl"></span>
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_NOTIFICATION"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>                   			 
									</div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>		
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_SHOW_BREAD_CRUMBS');?>:		
									</label>
									<div class="controls">
										<input type="hidden" name="show_bradcrumbs" value="0">
										<?php
											$checked = '';
											if($configs->show_bradcrumbs == 1){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="1" class="ace-switch ace-switch-5" name="show_bradcrumbs">
										<span class="lbl"></span>
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_GURU_SHOW_BREAD_CRUMBS"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>                  			 
									</div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>		
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_SHOW_POWERD_BY');?>:		
                                  </label>
                                  <div class="controls">
										<input type="hidden" name="show_powerd" value="0">
										<?php
											$checked = '';
											if($configs->show_powerd == 1){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="1" class="ace-switch ace-switch-5" name="show_powerd">
										<span class="lbl"></span>
										
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_GURU_SHOW_POWERD"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>                			 
                                 </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_IGNORE_IJOOMLASEO');?>:		
                                  </label>
                                  <div class="controls">
										<input type="hidden" name="guru_ignore_ijseo" value="1">
										<?php
											$checked = '';
											if($configs->guru_ignore_ijseo == 0){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="guru_ignore_ijseo">
										<span class="lbl"></span>
									
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_IGNORE_IJOOMLASEO"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>               			 
                                 </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
           <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_TURN_OFF_JQUERY');?>:		
                                  </label>
                                  <div class="controls">
										<input type="hidden" name="guru_turnoffjq" value="1">
										<?php
											$checked = '';
											if($configs->guru_turnoffjq == 0){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="guru_turnoffjq">
										<span class="lbl"></span>
									
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TURN_OFF_JQUERY1"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>               			 
                                 </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="bootstrap_show"><?php echo JText::_('GURU_TURN_OFF_BOOTSTRAP');?>:		
                                  </label>
                                  <div class="controls">
										<input type="hidden" name="guru_turnoffbootstrap" value="0">
										<?php
											$checked = '';
											if($configs->guru_turnoffbootstrap == 1){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="1" class="ace-switch ace-switch-5" name="guru_turnoffbootstrap">
										<span class="lbl"></span>
									
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TURN_OFF_BOOTSTRAP1"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>               			 
                                 </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_STUDENTS_DEFAULT_GROUP');?>:		
                                  </label>
                                  <div class="controls">
                                    <?php 
                                    $db = JFactory::getDBO();
                                    $user = JFactory::getUser();
                                    $user_id = $user->id;
                                    
                                    $sql_u = "select group_id from #__user_usergroup_map where user_id=".intval($user_id);
                                    $db->setQuery($sql_u);
                                    $res_user_current = $db->loadResult();
                                    $listgroup = UsersHelper::getGroups();
                        
                                    ?>
                                        <select id="student_group" name="student_group"  class="inputbox" size="10">
                                        <?php
                                        if($res_user_current == 8){
                                         echo JHtml::_('select.options', $listgroup, 'value', 'text', $configs->student_group);
                                        }
                                        else{
                                         echo str_replace("- Super Users", "", JHtml::_('select.options', $listgroup, 'value', 'text', $configs->student_group));
                                        }
                                         ?>
                                        </select>
                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_STUDENTS_DEFAULT_GROUP2"); ?>" >
                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                        </span>              			 
                                 </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
             <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span8">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_DEFAULT_VIDEO_SIZE');?>:		
                                  </label>
                                  <div class="controls">
                                    <?php echo $lists['lesson_default_video_size']; ?>
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DEFAULT_VIDEO_SIZE"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>              			 
                                 </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
        </div>
      </div>  
    <?php
	}
	elseif($tab == "1"){
    ?>

     <div class="container-fluid">
      <a data-toggle="modal" data-target="#myModal" class="pull-right guru_video" onclick="showContentVideo('index.php?option=com_guru&controller=guruAbout&task=vimeo&id=29999510&tmpl=component')" href="#">
                <img src="<?php echo JURI::base(); ?>components/com_guru/images/icon_video.gif" class="video_img" />
            <?php echo JText::_("GURU_MEDIA_VIDEO"); ?>                  
      </a>
	</div>	
    <div class="clearfix"></div>
    <div class="well well-minimized">
		<?php echo JText::_("GURU_MEDIA_SETTINGS_DESCRIPTION"); ?>
	</div>
   <div class="widget-header widget-header-flat"><h5><?php echo  JText::_('GURU_FILESTORAGE');?></h5></div>
    <div class="widget-body">
    	<div class="widget-main">	
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_IMGIN');?>&nbsp;<span style="color:#FF0000">*</span></label>
                                <div class="controls">
                                    <input type="text" size="32" name="imagesin" value="<?php echo $configs->imagesin;?>" />
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_IMGIN"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>
             <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_VIDEOIN');?>&nbsp;<span style="color:#FF0000">*</span></label>
                                <div class="controls">
                                    <input type="text" size="32" name="videoin" value="<?php echo $configs->videoin;?>" />
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_VIDEOIN"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                    </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_AUDIOIN');?>&nbsp;<span style="color:#FF0000">*</span></label>
                                <div class="controls">
                                    <input type="text" size="32" name="audioin" value="<?php echo $configs->audioin;?>" />
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_AUDIOIN"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                    </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_DOCSIN');?>&nbsp;<span style="color:#FF0000">*</span></label>
                                <div class="controls">
                                    <input type="text" size="32" name="docsin" value="<?php echo $configs->docsin;?>" />
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DOCSIN"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                    </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_FILESIN');?>&nbsp;<span style="color:#FF0000">*</span></label>
                                <div class="controls">
                                   <input type="text" size="32" name="filesin" value="<?php echo $configs->filesin;?>" />
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_FILESIN"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                    </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_BACKGROUND_STORAGE');?>&nbsp;<span style="color:#FF0000">*</span></label>
                                <div class="controls">
                                   <input type="text" size="32" name="filesin1" value="<?php echo $configs->imagesin."/certificates";?>" />
                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_BACKGROUND_STORAGE"); ?>" >
                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                    </span>
                                    </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>			
         </div>
       </div>     
    <?php 
	}
	elseif($tab == "2"){
	?>
	 <div class="container-fluid">
          <a data-toggle="modal" data-target="#myModal" class="pull-right guru_video" onclick="showContentVideo('index.php?option=com_guru&controller=guruAbout&task=vimeo&id=29999504&tmpl=component')" href="#">
                    <img src="<?php echo JURI::base(); ?>components/com_guru/images/icon_video.gif" class="video_img" />
                <?php echo JText::_("GURU_LAYOUT_VIDEO"); ?>                  
          </a>
	</div>	
	<div class="clearfix"></div>
    <div class="well well-minimized">
		<?php echo JText::_("GURU_LAYOUT_SETTINGS_DESCRIPTION"); ?>
	</div>
	<div class="widget-header widget-header-flat"><h5><?php echo  JText::_('GURU_LAYOUT_TITLE');?></h5></div>
    	<div class="widget-body">
    		<div class="widget-main">
	
			<?php
                require_once(JPATH_SITE.DS."administrator".DS."components".DS."com_guru".DS."views".DS."guruconfigs".DS."tmpl".DS."layout.php"); 
            ?>
            </div>
         </div>   
        <?php
	}				
	elseif($tab == "4"){
	
	?>
    <script language="javascript" type="text/javascript">        
	
	function validateColor(color){
		if(color == ''){
			return true
		}
		if(/^[0-9A-F]{6}$/i.test(color)){
			return true;
		}
		return false;
	}
	
	Joomla.submitbutton = function(pressbutton){
		<?php
			$validation_script = "";
			if($tab == "4"){
				
				$validation_script = 'if(!validateColor(document.getElementById("pick_rdonecolorfield").value)){
									  		alert("'.JText::_("GURU_ALERT_INVALID_COLOR").'");
											return false;
									  }
									  else if(!validateColor(document.getElementById("pick_pnotdonecolorfield").value)){
									  		alert("'.JText::_("GURU_ALERT_INVALID_COLOR").'");
											return false;
									  }
									  else if(!validateColor(document.getElementById("pick_stxtcolorfield").value)){
									  		alert("'.JText::_("GURU_ALERT_INVALID_COLOR").'");
											return false;
									  }';
				echo $validation_script;
			}
		?>
		submitform( pressbutton );
	}
</script>
    <style>
    	input[type="text"]{
			width:135px;!important
		}
    
    </style>
     <div class="container-fluid">
          <a data-toggle="modal" data-target="#myModal" class="pull-right guru_video" onclick="showContentVideo('index.php?option=com_guru&controller=guruAbout&task=vimeo&id=29999534&tmpl=component')" href="#">
                    <img src="<?php echo JURI::base(); ?>components/com_guru/images/icon_video.gif" class="video_img" />
                <?php echo JText::_("GURU_PROGRESS_VIDEO"); ?>                  
          </a>
	</div>	
	<div class="clearfix"></div>
    <div class="well well-minimized">
		<?php echo JText::_("GURU_PROGRESS_SETTINGS_DESCRIPTION"); ?>
	</div>
    <div class="widget-header widget-header-flat"><h5><?php echo  JText::_('GURU_PROGRESS_BAR');?></h5></div>
   <div class="widget-body">
   	 <div style="padding-right: 375px;" class="pagination-right"><?php echo JText::_("GURU_PREVIEW"); ?></div>
   	<br/>
    	<div class="widget-main">	
         <div class="row-fluid">
            <div class="span12"> 
                <div class="row-fluid">
                    <div class="span12">
                        <div class="row-fluid">
                            <div class="control-group">
                                <label class="control-label" for="curency"><?php echo JText::_('GURU_SHOW_PROGRS_BAR');?></label>
                            <div class="controls">
                                <input type="hidden" name="progress_bar" value="1">
								<?php
                                    $checked = '';
                                    if($configs->progress_bar == "0"){
                                        $checked = 'checked="checked"';
                                    }
                                ?>
                                <input type="checkbox" <?php echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="progress_bar">
                                <span class="lbl"></span>                             
                                
                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_SHOW_PROGRS_BAR"); ?>" >
                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                </span>
                                <div class="pull-right span5">
                                      <div id="progress" class="progress" style="width:<?php echo $configs->st_width; ?>px !important; height:<?php echo $configs->st_height; ?>px !important;">
                                        <div id="success" class="bar" style="width: 45%; background-color:<?php echo $configs->st_donecolor; ?>!important; background-image: none !important;"></div>
                                        <div id="warning" class="bar " style="width: 30%; background-color:<?php echo $configs->st_txtcolor; ?> !important; background-image: none !important;"></div>
                                        <div id="danger"  class="bar " style="width: 15%; background-color:<?php echo $configs->st_notdonecolor; ?>!important; background-image: none !important; "></div>
                                      </div>
                                    </div>  	
                                </div>
                            </div>
                        </div>
                    </div>							
                </div>
            </div>						
        </div>	
        <div class="row-fluid">
            <div class="span12"> 
                <div class="row-fluid">
                    <div class="span6">
                        <div class="row-fluid">
                            <div class="control-group">
                                <label class="control-label" for="curency"><?php echo JText::_('GURU_DONECOLOR');?></label>
                            <div class="controls">
                       			<div>
                                        <div style="float:left;">
                                            <input type="text" size="7" name="st_donecolor" ID="pick_rdonecolorfield" value="<?php echo substr($configs->st_donecolor, 1, strlen($configs->st_donecolor));?>" onchange="changeBcolor(); relateColor('pick_rdonecolor', this.value);" size="6" maxlength="6" onkeyup="if (this.value.length == 6) {relateColor('pick_rdonecolor', this.value); changeBcolor();}"/>
                                            &nbsp;
                                            <a href="javascript:pickColor('pick_rdonecolor');" onClick="document.getElementById('show_hide_box').style.display='none';" id="pick_rdonecolor" style="border: 1px solid #000000; font-family:Verdana; font-size:10px; text-decoration: none;">
                                            &nbsp;&nbsp;&nbsp;
                                            </a>
                                            <SCRIPT LANGUAGE="javascript">relateColor('pick_rdonecolor', getObj('pick_rdonecolorfield').value);</script>
                                            &nbsp;&nbsp;&nbsp;
                                        </div>
                                        <div style="float:left;">
                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DONECOLOR"); ?>" >
                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                            </span>
                                        </div>                    
                                    </div>
                                </div>
                            </div>										
                        </div>
                    </div>							
                </div>
            </div>						
        </div>	
        <div class="row-fluid">
            <div class="span12"> 
                <div class="row-fluid">
                    <div class="span6">
                        <div class="row-fluid">
                            <div class="control-group">
                                <label class="control-label" for="curency"><?php echo JText::_('GURU_NOTDONECOL');?></label>
                            <div class="controls">
                       			<div>
                                    <div style="float:left;">
                                        <input type="text" size="7" name="st_notdonecolor" ID="pick_pnotdonecolorfield" value="<?php echo substr($configs->st_notdonecolor, 1, strlen($configs->st_notdonecolor));?>" onchange="changeBcolor(); relateColor('pick_pnotdonecolor', this.value);" size="6" maxlength="6" onkeyup="if (this.value.length == 6) {relateColor('pick_pnotdonecolor', this.value); changeBcolor();}"  />
                                        &nbsp;
                                        <a href="javascript:pickColor('pick_pnotdonecolor');" onClick="document.getElementById('show_hide_box').style.display='none';" id="pick_pnotdonecolor" style="border: 1px solid #000000; font-family:Verdana; font-size:10px; text-decoration: none;">
                                        &nbsp;&nbsp;&nbsp;
                                        </a>
                                        <SCRIPT LANGUAGE="javascript">relateColor('pick_pnotdonecolor', getObj('pick_pnotdonecolorfield').value);</script>
                                        <span id='show_hide_box'></span>
                                        &nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div style="float:left;">
                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_NOTDONECOL"); ?>" >
                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                        </span>	
                                    </div>                    
                                </div>
                                </div>
                            </div>										
                        </div>
                    </div>							
                </div>
            </div>						
        </div>	
        <div class="row-fluid">
            <div class="span12"> 
                <div class="row-fluid">
                    <div class="span6">
                        <div class="row-fluid">
                            <div class="control-group">
                                <label class="control-label" for="curency"><?php echo JText::_('GURU_TEXTCOL');?></label>
                            <div class="controls">
                       			<div>
                                    <div style="float:left;">
                                        <input type="text" size="7" name="st_txtcolor" ID="pick_stxtcolorfield" value="<?php echo substr($configs->st_txtcolor, 1, strlen($configs->st_txtcolor));?>" onchange="changeBcolor(); relateColor('pick_stxtcolor', this.value);" size="6" maxlength="6" onkeyup="if (this.value.length == 6) {relateColor('pick_stxtcolor', this.value); changeBcolor();}"  />
                                        &nbsp;
                                        <a href="javascript:pickColor('pick_stxtcolor');" onClick="document.getElementById('show_hide_box').style.display='none';" id="pick_stxtcolor" style="border: 1px solid #000000; font-family:Verdana; font-size:10px; text-decoration: none;">
                                        &nbsp;&nbsp;&nbsp;
                                        </a>
                                        <SCRIPT LANGUAGE="javascript">relateColor('pick_stxtcolor', getObj('pick_stxtcolorfield').value);</script>
                                        <span id='show_hide_box'></span>
                                        &nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div style="float:left;">
                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_TEXTCOL"); ?>" >
                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                        </span>	
                                    </div>                    
                                </div>
                                </div>
                            </div>										
                        </div>
                    </div>							
                </div>
            </div>						
        </div>	
        <div class="row-fluid">
            <div class="span12"> 
                <div class="row-fluid">
                    <div class="span6">
                        <div class="row-fluid">
                            <div class="control-group">
                                <label class="control-label" for="curency"><?php echo JText::_('GURU_WIDTH');?></label>
                            <div class="controls">
                                 <div style="float:left;">
                                        <input  onchange="guruChangeWidth(this.value);" type="text" size="4" name="st_width" value="<?php echo $configs->st_width; ?>" />
                                    </div>
                                    <div style="float:left;">
                                     &nbsp;&nbsp;&nbsp;px &nbsp;
                                    </div>
                                    <div style="float:left;">
                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_BAR_WIDTH"); ?>" >
                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                        </span>
                                    </div>
                                </div>
                                </div>
                            </div>										
                        </div>
                    </div>							
                </div>
            </div>						
        <div class="row-fluid">
            <div class="span12"> 
                <div class="row-fluid">
                    <div class="span6">
                        <div class="row-fluid">
                            <div class="control-group">
                                <label class="control-label" for="curency"><?php echo JText::_('GURU_HEIGHT');?></label>
                            <div class="controls">
                                <div>
                                    <div style="float:left;">
                                        <input onchange="guruChangeHeight(this.value);" type="text" size="4" name="st_height" value="<?php echo $configs->st_height; ?>" />
                                    </div>
                                    <div style="float:left;">
                                     &nbsp;&nbsp;&nbsp;px &nbsp;
                                    </div>
                                    <div style="float:left;">
                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_BAR_HEIGHT"); ?>" >
                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                        </span>
                                    </div>
                                </div>
                                </div>
                            </div>										
                        </div>
                    </div>							
                </div>
            </div>	
  		</div>		
</div> 
	<?php
	}
	elseif($tab == "5"){
    ?>
	<div class="container-fluid">
          <a data-toggle="modal" data-target="#myModal" class="pull-right guru_video" onclick="showContentVideo('index.php?option=com_guru&controller=guruAbout&task=vimeo&id=29999432&tmpl=component')" href="#">
                    <img src="<?php echo JURI::base(); ?>components/com_guru/images/icon_video.gif" class="video_img" />
                <?php echo JText::_("GURU_EMAIL_VIDEO"); ?>                  
          </a>
	</div>	
	<div class="clearfix"></div>
    <div class="well well-minimized">
		<?php echo JText::_("GURU_EMAIL_SETTINGS_DESCRIPTION"); ?>
	</div>
    <div class="widget-header widget-header-flat"><h5><?php echo  JText::_('GURU_AUTHOR_EMAIL');?></h5></div>
	
	<div class="widget-body">
    	<div class="widget-main">
        	<div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_FROMNAME');?></label>
                                    <div class="controls">
                                     <div style="float:left;">
                                         <input type="text" size="32" name="fromname" value="<?php echo $configs->fromname; ?>" />
                                        </div>
                                        <div style="float:left;">
                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_FROMNAME"); ?>" >
                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                            </span>
                                        </div>
                                    </div>
                                    </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
            </div>	
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_FROMEMAIL');?></label>
                                <div class="controls">
                                     <div style="float:left;">
                                        <input type="text" size="32" name="fromemail" value="<?php echo $configs->fromemail; ?>" />
                                        </div>
                                        <div style="float:left;">
                                             <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_FROMEMAIL"); ?>" >
                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                            </span>
                                        </div>
                                    </div>
                                    </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
          	 <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="curency"><?php echo JText::_('GURU_EMAIL_SUPER_ADMIN');?></label>
                                <div class="controls">
                                     <div style="float:left;">
                                     	<table class="table table-bordered">
                                            <tbody>
                                            <?php 
                                            for($i=0; $i<count($admins); $i++){
                                                $id = $admins[$i]->id;
                                                $admins_array = explode(",",$configs->admin_email);
                                            ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" <?php if(isset($configs->admin_email)&&(in_array($id,$admins_array))) { echo 'checked="checked"'; } ?> name="cid[]" value="<?php echo $id;?>" />
                                                    <span class="lbl"></span>
                                                </td>	
                                                 <td>
                                                    <?php echo $admins[$i]->name;?>
                                                </td>		
                                            </tr>
                                            <?php 
                                                }
                                            ?>
             
                                            </tbody>
                                    </table>
                                     
                                        </div>
                                        <div style="float:left;">
                                             <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_EMAIL_SUPER_ADMIN1"); ?>" >
                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                            </span>
                                        </div>
                                    </div>
                                    </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>			
			<div class="row-fluid">
				<div class="control-group">
                	<label class="control-label" for="curency"><?php echo JText::_('GURU_SELECT_EMAIL_TEMPLATE');?></label>
                    <div class="controls">
                    	<select onchange="javascript:emailTemplate(this.value);" name="email_template" id="email_template">
                            <option>- Select -</option>
                            <optgroup label="<?php echo JText::_("GURU_TO_TEACHER"); ?>:">
                                <option id="1" value="1"><?php echo JText::_("GURU_COURSE_APPROVED"); ?></option>
                                <option id="2" value="2"><?php echo JText::_("GURU_COURSE_UNAPPROVED"); ?></option>
                                <option id="4" value="4"><?php echo JText::_("GURU_TEACHER_APPROVED"); ?></option>
                                <option id="5" value="5"><?php echo JText::_("GURU_TEACHER_PENDING"); ?></option>
                            </optgroup>
                            <optgroup label="<?php echo JText::_("GURU_TO_ADMIN"); ?>:">
                                <option id="3" value="3"><?php echo JText::_("GURU_FOR_COURSE_APPROVED"); ?></option>
                                <option id="6" value="6"><?php echo JText::_("GURU_FOR_TEACHER_APPROVED"); ?></option>
                                <option id="7" value="7"><?php echo JText::_("GURU_FOR_TEACHER_REGISTERED"); ?></option>
                                <option id="10" value="10"><?php echo JText::_("GURU_PENDING_ORDERS"); ?></option>
                            </optgroup>
                            <optgroup label="<?php echo JText::_("GURU_TO_STUDENT"); ?>:">
                                <option id="8" value="8"><?php echo JText::_("GURU_FOR_COURSE_APPROVED_ORDER"); ?></option>
                            </optgroup>
                        </select>
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_SELECT_EMAIL_TEMPLATE"); ?>" >
							<img border="0" src="components/com_guru/images/icons/tooltip.png">
						</span>
                    </div>
				</div>
			</div>
            
            <div id="course-approve-email" style="display:none;">
            	<div class="pull-left">
                	<?php echo JText::_("GURU_USE_EMAIL_VARIABLES"); ?>
                </div>
                <div class="clearfix"></div>
                <div class="pull-left">[AUTHOR_NAME] = <?php echo JText::_("GURU_TEACH_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[COURSE_NAME] = <?php echo JText::_("GURU_COURSE_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[SITE_NAME] = <?php echo JText::_("GURU_SITE_NAME"); ?></div>
                <div class="clearfix"></div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_SUBJECT');?></label>
                        <div class="controls">
                            <input type="text" class="input-large" name="approve_subject" value="<?php echo $template_emails->approve_subject; ?>" />
                        </div>
                    </div>
                </div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_BODY');?></label>
                        <div class="controls">
							<?php
								$editor = JFactory::getEditor();
								echo $editor->display('approve_body', $template_emails->approve_body, '100%', '350', '75', '20', false);
							?>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div id="course-unapprove-email" style="display:none;">
            	<div class="pull-left">
                	<?php echo JText::_("GURU_USE_EMAIL_VARIABLES"); ?>
                </div>
                <div class="clearfix"></div>
                <div class="pull-left">[AUTHOR_NAME] = <?php echo JText::_("GURU_TEACH_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[COURSE_NAME] = <?php echo JText::_("GURU_COURSE_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[SITE_NAME] = <?php echo JText::_("GURU_SITE_NAME"); ?></div>
                <div class="clearfix"></div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_SUBJECT');?></label>
                        <div class="controls">
                            <input type="text" class="input-large" name="unapprove_subject" value="<?php echo $template_emails->unapprove_subject; ?>" />
                        </div>
                    </div>
                </div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_BODY');?></label>
                        <div class="controls">
							<?php
								$editor = JFactory::getEditor();
								echo $editor->display('unapprove_body', $template_emails->unapprove_body, '100%', '350', '75', '20', false);
							?>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div id="for-course-email" style="display:none;">
            	<div class="pull-left">
                	<?php echo JText::_("GURU_USE_EMAIL_VARIABLES"); ?>
                </div>
                <div class="clearfix"></div>
                <div class="pull-left">[AUTHOR_NAME] = <?php echo JText::_("GURU_TEACH_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[COURSE_NAME] = <?php echo JText::_("GURU_COURSE_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[COURSE_APPROVE_URL] = <?php echo JText::_("GURU_APPROVE_URL"); ?></div>
                <div class="clearfix"></div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_SUBJECT');?></label>
                        <div class="controls">
                            <input type="text" class="input-large" name="ask_approve_subject" value="<?php echo $template_emails->ask_approve_subject; ?>" />
                        </div>
                    </div>
                </div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_BODY');?></label>
                        <div class="controls">
							<?php
								$editor = JFactory::getEditor();
								echo $editor->display('ask_approve_body', $template_emails->ask_approve_body, '100%', '350', '75', '20', false);
							?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="for-teacher-approve" style="display:none;">
            	<div class="pull-left">
                	<?php echo JText::_("GURU_USE_EMAIL_VARIABLES"); ?>
                </div>
                <div class="clearfix"></div>
                <div class="pull-left">[AUTHOR_NAME] = <?php echo JText::_("GURU_TEACH_NAME"); ?></div>
                <div class="clearfix"></div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_SUBJECT');?></label>
                        <div class="controls">
                            <input type="text" class="input-large" name="ask_teacher_subject" value="<?php echo $template_emails->ask_teacher_subject; ?>" />
                        </div>
                    </div>
                </div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_BODY');?></label>
                        <div class="controls">
							<?php
								$editor = JFactory::getEditor();
								echo $editor->display('ask_teacher_body', $template_emails->ask_teacher_body, '100%', '350', '75', '20', false);
							?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="for-teacher-registered" style="display:none;">
            	<div class="pull-left">
                	<?php echo JText::_("GURU_USE_EMAIL_VARIABLES"); ?>
                </div>
                <div class="clearfix"></div>
                <div class="pull-left">[AUTHOR_NAME] = <?php echo JText::_("GURU_TEACH_NAME"); ?></div>
                <div class="clearfix"></div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_SUBJECT');?></label>
                        <div class="controls">
                            <input type="text" class="input-large" name="new_teacher_subject" value="<?php echo $template_emails->new_teacher_subject; ?>" />
                        </div>
                    </div>
                </div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_BODY');?></label>
                        <div class="controls">
							<?php
								$editor = JFactory::getEditor();
								echo $editor->display('new_teacher_body', $template_emails->new_teacher_body, '100%', '350', '75', '20', false);
							?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="approved-teacher" style="display:none;">
            	<div class="pull-left">
                	<?php echo JText::_("GURU_USE_EMAIL_VARIABLES"); ?>
                </div>
                <div class="clearfix"></div>
                <div class="pull-left">[AUTHOR_NAME] = <?php echo JText::_("GURU_TEACH_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[SITE_NAME] = <?php echo JText::_("GURU_SITE_NAME"); ?></div>
                <div class="clearfix"></div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_SUBJECT');?></label>
                        <div class="controls">
                            <input type="text" class="input-large" name="approved_teacher_subject" value="<?php echo $template_emails->approved_teacher_subject; ?>" />
                        </div>
                    </div>
                </div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_BODY');?></label>
                        <div class="controls">
							<?php
								$editor = JFactory::getEditor();
								echo $editor->display('approved_teacher_body', $template_emails->approved_teacher_body, '100%', '350', '75', '20', false);
							?>
                        </div>
                    </div>
                </div>
            </div>
            
             <div id="approved-order" style="display:none;">
            	<div class="pull-left">
                	<?php echo JText::_("GURU_USE_EMAIL_VARIABLES"); ?>
                </div>
                <div class="clearfix"></div>
                <div class="pull-left">[COURSE_NAME] = <?php echo JText::_("GURU_COURSE_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[SITE_NAME] = <?php echo JText::_("GURU_SITE_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[STUDENT_FIRST_NAME] = <?php echo JText::_("GURU_STUDENT_FIRST_NAME"); ?></div>
                <div class="clearfix"></div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_SUBJECT');?></label>
                        <div class="controls">
                            <input type="text" class="input-large" name="approve_order_subject" value="<?php echo $template_emails->approve_order_subject; ?>" />
                        </div>
                    </div>
                </div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_BODY');?></label>
                        <div class="controls">
							<?php
								$editor = JFactory::getEditor();
								echo $editor->display('approve_order_body', $template_emails->approve_order_body, '100%', '350', '75', '20', false);
							?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="pending-order" style="display:none;">
                <div class="pull-left">
                    <?php echo JText::_("GURU_USE_EMAIL_VARIABLES"); ?>
                </div>
                <div class="clearfix"></div>
                <div class="pull-left">[COURSE_NAME] = <?php echo JText::_("GURU_COURSE_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[STUDENT_FIRST_NAME] = <?php echo JText::_("GURU_STUDENT_FIRST_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[STUDENT_LAST_NAME] = <?php echo JText::_("GURU_STUDENT_LAST_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[ORDER_NUMBER] = <?php echo JText::_("GURU_ORDER_NUMBER"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[ORDER_LIST_URL] = <?php echo JText::_("GURU_ORDER_LIST_URL"); ?></div>
                <div class="clearfix"></div>
               
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_SUBJECT');?></label>
                        <div class="controls">
                            <input type="text" class="input-large" name="pending_order_subject" value="<?php echo $template_emails->pending_order_subject; ?>" />
                        </div>
                    </div>
                </div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_BODY');?></label>
                        <div class="controls">
                            <?php
                                $editor = JFactory::getEditor();
                                echo $editor->display('pending_order_body', $template_emails->pending_order_body, '100%', '350', '75', '20', false);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="pending-teacher" style="display:none;">
            	<div class="pull-left">
                	<?php echo JText::_("GURU_USE_EMAIL_VARIABLES"); ?>
                </div>
                <div class="clearfix"></div>
                <div class="pull-left">[AUTHOR_NAME] = <?php echo JText::_("GURU_TEACH_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[SITE_NAME] = <?php echo JText::_("GURU_SITE_NAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[USERNAME] = <?php echo JText::_("GURU_USERNAME"); ?></div>
                <div class="clearfix"></div>
                <div class="pull-left">[PASSWORD] = <?php echo JText::_("GURU_PASSWORD"); ?></div>
                <div class="clearfix"></div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_SUBJECT');?></label>
                        <div class="controls">
                            <input type="text" class="input-large" name="pending_teacher_subject" value="<?php echo $template_emails->pending_teacher_subject; ?>" />
                        </div>
                    </div>
                </div>
                
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label" for="curency"><?php echo JText::_('GURU_BODY');?></label>
                        <div class="controls">
							<?php
								$editor = JFactory::getEditor();
								echo $editor->display('pending_teacher_body', $template_emails->pending_teacher_body, '100%', '350', '75', '20', false);
							?>
                        </div>
                    </div>
                </div>
            </div>
            
		</div>
	</div>
    
    <?php
	}
	elseif($tab == "6"){       
	?>
    <div id="g_promotion_box">

        <div class="container-fluid">
              <a data-toggle="modal" data-target="#myModal" class="pull-right guru_video"  onclick="showContentVideo('index.php?option=com_guru&controller=guruAbout&task=vimeo&id=29999551&tmpl=component')" href="#">
                        <img src="<?php echo JURI::base(); ?>components/com_guru/images/icon_video.gif" class="video_img" />
                    <?php echo JText::_("GURU_PROMOTION_VIDEO"); ?>                  
              </a>
        </div>	
        <div class="clearfix"></div>
        <div class="well well-minimized">
            <?php echo JText::_("GURU_PROMOTION_SETTINGS_DESCRIPTION"); ?>
        </div>
        
            <?php
                echo $editor->display( 'content_selling', ''.stripslashes($configs->content_selling),'100%', '300px', '20', '60' );	
            ?>
           
            <?php	
		?>
   </div>
   
          <?php
   }
   elseif($tab == "8"){
  		$config_author = json_decode($configs->st_authorpage);
	 ?> 
    <div class="widget-header widget-header-flat"><h5><?php echo  JText::_('GURU_GENERAL')." ".JText::_('GURU_SETTINGS');?></h5></div>
    <div class="widget-body">
        <div class="widget-main">
        	<div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="teacher_aprove"><?php echo JText::_('GURU_AUTHOR_APROVE');?>:		
									</label>
									<div class="controls">
										<input type="hidden" name="teacher_aprove" value="1">
										<?php
											$checked = '';
											if($config_author->teacher_aprove == 0){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="teacher_aprove">
										<span class="lbl"></span>
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_GURU_AUTHOR_APROVE"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>                   			 
									</div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
             <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="teacher_group"><?php echo JText::_('GURU_TEACHER_DEFAULT_GROUP');?>:		
                                  </label>
                                  <div class="controls">
                                    <?php 
                                    $db = JFactory::getDBO();
                                    $user = JFactory::getUser();
                                    $user_id = $user->id;
                                    
                                    $sql_u = "select group_id from #__user_usergroup_map where user_id=".intval($user_id);
                                    $db->setQuery($sql_u);
                                    $res_user_current = $db->loadResult();
                                    $listgroup = UsersHelper::getGroups();
                        
                                    ?>
                                        <select id="teacher_group" name="teacher_group"  class="inputbox" size="10">
                                        <?php
                                        if($res_user_current == 8){
                                         echo JHtml::_('select.options', $listgroup, 'value', 'text', $config_author->teacher_group);
                                        }
                                        else{
                                         echo str_replace("- Super Users", "", JHtml::_('select.options', $listgroup, 'value', 'text', $config_author->teacher_group));
                                        }
                                         ?>
                                        </select>
                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TEACHER_DEFAULT_GROUP2"); ?>" >
                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                        </span>              			 
                                 </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
        </div>
    </div> 
    <br/>
    <div class="widget-header widget-header-flat"><h5><?php echo  JText::_('GURU_ALLOW_TEACHERS_TO');?></h5></div>
    <div class="widget-body">
        <div class="widget-main">	
        	 <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="course_is_free_show"><?php echo JText::_('GURU_ALLOW_TECHERS_FREE');?>		
                                  </label>
                                  <div class="controls">
                                        <input type="hidden" name="course_is_free_show" value="0">
                                        <?php
                                            $checked = '';
                                            if($configs->course_is_free_show == 1){
                                                $checked = 'checked="checked"';
                                            }
                                        ?>
                                        <input type="checkbox" <?php echo $checked; ?> value="1" class="ace-switch ace-switch-5" name="course_is_free_show">
                                        <span class="lbl"></span>
                                    
                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_SHOW_HIDE_COURSEISFREE1"); ?>" >
                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                        </span>               			 
                                 </div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>		
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="teacher_add_media"><?php echo JText::_('GURU_TEACHER_ADD_MEDIA');?>:		
									</label>
									<div class="controls">
										<input type="hidden" name="teacher_add_media" value="1">
										<?php
											$checked = '';
											if($config_author->teacher_add_media == 0){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="teacher_add_media">
										<span class="lbl"></span>
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TEACHER_ADD_MEDIA2"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>                   			 
									</div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>	
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="teacher_edit_media"><?php echo JText::_('GURU_TEACHER_EDIT_OWN_MEDIA');?>:		
									</label>
									<div class="controls">
										<input type="hidden" name="teacher_edit_media" value="1">
										<?php
											$checked = '';
											if($config_author->teacher_edit_media == 0){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="teacher_edit_media">
										<span class="lbl"></span>
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TEACHER_EDIT_OWN_MEDIA2"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>                   			 
									</div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>
             <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="teacher_add_courses"><?php echo JText::_('GURU_TEACHER_ADD_COURSE');?>:		
									</label>
									<div class="controls">
										<input type="hidden" name="teacher_add_courses" value="1">
										<?php
											$checked = '';
											if($config_author->teacher_add_courses == 0){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="teacher_add_courses">
										<span class="lbl"></span>
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TEACHER_ADD_COURSE2"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>                   			 
									</div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>
            
            <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="teacher_add_courses"><?php echo JText::_('GURU_TEACHER_AUTO_APPROVE_COURSE');?>:		
									</label>
									<div class="controls">
										<input type="hidden" name="teacher_approve_courses" value="1">
										<?php
											$checked = '';
											if($config_author->teacher_approve_courses == 0){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="teacher_approve_courses">
										<span class="lbl"></span>
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TEACHER_AUTO_APPROVE_COURSE2"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>                   			 
									</div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>
            
             <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="teacher_edit_courses"><?php echo JText::_('GURU_TEACHER_EDIT_OWN_COURSE');?>:		
									</label>
									<div class="controls">
										<input type="hidden" name="teacher_edit_courses" value="1">
										<?php
											$checked = '';
											if($config_author->teacher_edit_courses == 0){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="teacher_edit_courses">
										<span class="lbl"></span>
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TEACHER_EDIT_OWN_COURSE2"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>                   			 
									</div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>
             <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="teacher_add_quizzesfe"><?php echo JText::_('GURU_TEACHER_ADD_QUIZZES_FE');?>:		
									</label>
									<div class="controls">
										<input type="hidden" name="teacher_add_quizzesfe" value="1">
										<?php
											$checked = '';
											if($config_author->teacher_add_quizzesfe == 0){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="teacher_add_quizzesfe">
										<span class="lbl"></span>
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TEACHER_ADD_QUIZZES_FE2"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>                   			 
									</div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>
             <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="teacher_edit_quizzesfe"><?php echo JText::_('GURU_TEACHER_EDIT_QWN_QUIZZES_FE');?>:		
									</label>
									<div class="controls">
										<input type="hidden" name="teacher_edit_quizzesfe" value="1">
										<?php
											$checked = '';
											if($config_author->teacher_edit_quizzesfe == 0){
												$checked = 'checked="checked"';
											}
										?>
										<input type="checkbox" <?php echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="teacher_edit_quizzesfe">
										<span class="lbl"></span>
										<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TEACHER_EDIT_QWN_QUIZZES_FE2"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>                   			 
									</div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>
           <!-- <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="teacher_add_students"><?php //echo JText::_('GURU_TEACHER_ADD_STUDENTS');?>:		
									</label>
									<div class="controls">
										<input type="hidden" name="teacher_add_students" value="1">
										<?php
											//$checked = '';
//											if($config_author->teacher_add_students == 0){
//												$checked = 'checked="checked"';
//											}
										?>
										<input type="checkbox" <?php //echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="teacher_add_students">
										<span class="lbl"></span>
										<span class="editlinktip hasTip" title="<?php //echo JText::_("GURU_TEACHER_ADD_STUDENTS2"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>                   			 
									</div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>						
            </div>-->
    <!-- /*       <div class="row-fluid">
                <div class="span12"> 
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="row-fluid">
                                <div class="control-group">
                                    <label class="control-label" for="teacher_edit_students"><?php //echo JText::_('GURU_TEACHER_EDIT_OWN_STUDENTS');?>:		
									</label>
									<div class="controls">
										<input type="hidden" name="teacher_edit_students" value="1">
										<?php
											/*$checked = '';
											if($config_author->teacher_edit_students == 0){
												$checked = 'checked="checked"';
											}*/
										?>
										<input type="checkbox" <?php //echo $checked; ?> value="0" class="ace-switch ace-switch-5" name="teacher_edit_students">
										<span class="lbl"></span>
										<span class="editlinktip hasTip" title="<?php //echo JText::_("GURU_TEACHER_EDIT_OWN_STUDENTS2"); ?>" >
											<img border="0" src="components/com_guru/images/icons/tooltip.png">
										</span>                   			 
									</div>
                                </div>										
                            </div>
                        </div>							
                    </div>
                </div>*/-->						
            </div>
        </div>
    </div> 
     <?php
    }
	elseif($tab == "9"){
		$terms_cond_student = $configs->terms_cond_student;
		$terms_cond_teacher = $configs->terms_cond_teacher;
		$terms_cond_student_content = $configs->terms_cond_student_content;
		$terms_cond_teacher_content = $configs->terms_cond_teacher_content;
	?>
    	<ul class="nav nav-tabs">
            <li class="active"><a href="#teachers" data-toggle="tab"><?php echo JText::_('GURU_TEACHERS');?></a></li>
            <li><a href="#students" data-toggle="tab"><?php echo JText::_('GURU_COU_STUDENTS');?></a></li>
        </ul>
        
        <div class="tab-content">
			<div class="tab-pane active" id="teachers">
            	<div class="control-group">
                    <label class="control-label">
                        <?php echo JText::_('GURU_ENABLE_TERMS');?>
                    </label>
                    <div class="controls">
                        <fieldset class="radio btn-group" id="single_video_p_show_length">
							<?php
                                $no_checked = "";
                                $yes_cheched = "";
                                
                                if($terms_cond_teacher == "0"){
                                    $no_checked = 'checked="checked"';
                                }
                                else{
                                    $yes_cheched = 'checked="checked"';
                                }
                            ?>
                            <input type="hidden" name="terms_cond_teacher" value="0">
                            <input type="checkbox" <?php echo $yes_cheched; ?> value="1" name="terms_cond_teacher" class="ace-switch ace-switch-5">
                            <span class="lbl"></span>
                        </fieldset>
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_ENABLE_TERMS_TEACHER_TOOLTIP"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">
                        <?php echo JText::_('GURU_TERMS_AND_COND');?>
                    </label>
                    <div class="controls">
                        <?php
							$editor = JFactory::getEditor();
							echo $editor->display('terms_cond_teacher_content', $terms_cond_teacher_content, '100%', '350', '75', '20', false);
						?>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane" id="students">
            	<div class="control-group">
                    <label class="control-label">
                        <?php echo JText::_('GURU_ENABLE_TERMS');?>
                    </label>
                    <div class="controls">
                        <fieldset class="radio btn-group" id="single_video_p_show_length">
							<?php
                                $no_checked = "";
                                $yes_cheched = "";
                                
                                if($terms_cond_student == "0"){
                                    $no_checked = 'checked="checked"';
                                }
                                else{
                                    $yes_cheched = 'checked="checked"';
                                }
                            ?>
                            <input type="hidden" name="terms_cond_student" value="0">
                            <input type="checkbox" <?php echo $yes_cheched; ?> value="1" name="terms_cond_student" class="ace-switch ace-switch-5">
                            <span class="lbl"></span>
                        </fieldset>
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_ENABLE_TERMS_STUDENT_TOOLTIP"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label">
                        <?php echo JText::_('GURU_TERMS_AND_COND');?>
                    </label>
                    <div class="controls">
                        <?php
							$editor = JFactory::getEditor();
							echo $editor->display('terms_cond_student_content', $terms_cond_student_content, '100%', '350', '75', '20', false);
						?>
                    </div>
                </div>
            </div>
		</div>
    <?php
	}
 	?>
 
    <input type="hidden" name="id" value="1" />
	<input type="hidden" name="option" value="com_guru" />
	<input type="hidden" name="task" value="save" />	
	<input type="hidden" name="controller" value="guruConfigs" />
    <input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="tab" value="<?php echo intval($tab); ?>" />
</form>
<script language="javascript">
	var first = false;
	function showContentVideo(href){
	first = true;
	jQuery.ajax({
      url: href,
      success: function(response){
       jQuery( '#myModal .modal-body').html(response);
      }
    });
}

	jQuery('#myModal').on('hide', function () {
	 jQuery('div.modal-body').html('');
	});
	
	jQuery('#myModal').on('hide', function () {
	 jQuery('div.modal-body').html('');
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