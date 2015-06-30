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

	$pid = JRequest::getVar("pid", "");
	$doc = JFactory::getDocument();
	$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');	
	$doc->addScript('components/com_guru/js/jquery.noconflict.js');
	$doc->addScript('components/com_guru/js/jquery.DOMWindow.js');
	$doc->addScript('components/com_guru/js/guru_modal.js');
	$doc->addStyleSheet('media/jui/css/bootstrap.min.css');

?>
<style>
	div.modal1 {
		left: 10% !important;
		width: 770px !important;
		top:-1%!important;
		position: fixed;
    	z-index: 9999;
	}

.modal-backdrop, .modal-backdrop.fade.in {
    opacity: 0.4 !important;
}


</style>
<script language="javascript" type="text/javascript">
	
	
	function validateForm(){
		value = document.adminForm.title.value;
		if(value == ""){
			alert("<?php echo JText::_("GURU_TASKS_JS_NAME"); ?>");
			return false;
		}
		else{
			return true;
		}
	}
	
	function deleteMedia(div_id, hidden_id){
		document.getElementById(div_id).innerHTML = "";
		document.getElementById(hidden_id).value="";
		document.getElementById("after_menu_med_1").style.display = "none";
	}
	

			
</script>
<link rel="StyleSheet" href="components/com_guru/css/guru-j30.css" type="text/css"/>

<script type="text/javascript" src="<?php echo JURI::root(); ?>plugins/content/jw_allvideos/includes/js/wmvplayer/silverlight.js"></script>
<script type="text/javascript" src="<?php echo JURI::root(); ?>plugins/content/jw_allvideos/includes/js/wmvplayer/wmvplayer.js"></script>
<script type="text/javascript" src="<?php echo JURI::root(); ?>plugins/content/jw_allvideos/includes/js/quicktimeplayer/AC_QuickTime.js"></script>	


 
<form method="post" name="adminForm" id="adminForm" onsubmit="return validateForm();" class="form-vertical g_margin_left">

	<h4><?php if ((isset($program->id)) && ($program->id < 1)) echo JText::_('GURU_NEWDAY'); else echo JText::_('GURU_NEWDAY');?></h4>
    <div style="text-align:left;" class="control-group clearfix">
        <label class="control-label g_cell span6" for="name"><?php echo JText::_("GURU_DAYNAME");?>: <font color="#ff0000">*</font></label>
        <div class="controls g_cell span6">
            <span>
				<input class="input-module" type="text" name="title" id="title" value="" size="50" />
            </span>    
        </div>
    </div>
   					
    <div style="text-align:left;" id="menu_med_1">
        <div id="before_menu_med_1" style="display:none"></div>							
        <div>
            <a data-toggle="modal" data-target="#myModal1" onClick = "javascript:openMyModal(700, 300, '<?php echo JURI::root(); ?>index.php?option=com_guru&controller=guruAuthor&task=addmedia&tmpl=component&cid=0&med=1&action=new_module'); return false;" href="#"><?php echo JText::_("GURU_MEDIA_SEARCH"); ?></a>				
        or
            <a data-toggle="modal" data-target="#myModal1" onClick = "javascript:openMyModal(700, 300, '<?php echo JURI::root(); ?>index.php?option=com_guru&controller=guruAuthor&task=addtext&tmpl=component&cid=0&txt=1&action=new_module'); return false;" href="#"><?php echo JText::_("GURU_TEXT_SEARCH"); ?></a>
        </div>
        <div id="media_1" style="float:left; margin-top:5px;">
        </div>
        <div id="text_1" style="float:left; margin-top:5px;">
        </div>
        <div id="after_menu_med_1" style="display:none; float:left; margin:0px; padding:0px 0px 0px 5px; width:20px;"><img src="<?php echo JURI::root()."components/com_guru/images/delete.gif" ?>" title="Remove this media!" alt="Remove" onclick="javascript:deleteMedia('media_1', 'db_media_1');"></img></div>
    </div>
     <div style="text-align:left;" class="control-group clearfix">
        <label class="control-label g_cell span6" for="name"><?php echo JText::_("GURU_ACCESS");?>:</label>
        <div class="controls g_cell span6">
            <span>
				<select name="access">
                    <option value="0"><?php echo JText::_("GURU_COU_STUDENTS"); ?></option>
                    <option value="1"><?php echo JText::_("GURU_REG_MEMBERS"); ?></option>
                    <option value="2"><?php echo JText::_("GURU_REG_GUESTS"); ?></option> 
                </select>
            </span>    
        </div>
    </div>
    <div> 
        <a id="close_gb" style="display:none;">#</a>
        <input class="btn btn-success" type="submit" name="submit" value="<?php echo JText::_("GURU_SAVE"); ?>" />
    </div>
	<input type="hidden" name="pid" value="<?php echo $pid; ?>" />
	<input type="hidden" name="task" value="save_new_module" />
	<input type="hidden" name="option" value="com_guru" />
	<input type="hidden" name="controller" value="guruAuthor" />
	<input type="hidden" name="db_media_1" id="db_media_1" value="" />
</form>
