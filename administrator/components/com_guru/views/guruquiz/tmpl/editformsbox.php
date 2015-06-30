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

$doc =JFactory::getDocument();
$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');	
$doc->addScript('components/com_guru/js/jquery.noconflict.js');
$doc->addScript('components/com_guru/js/jquery.DOMWindow.js');
JHTML::_('behavior.modal');
JHtml::_('behavior.calendar');

$program = $this->program;
if($program->id == ""){
	$program_id = 0;
}
else{
	$program_id = $program->id;
}	
$lists = $program->lists;	
$mmediam = $this->mmediam;
$mainmedia = $this->mainmedia;
$configuration = guruAdminModelguruQuiz::getConfigs();

$temp_size = $configuration->lesson_window_size_back;
$temp_size_array = explode("x", $temp_size);
$width = $temp_size_array["1"]-20;
$height = $temp_size_array["0"]-20;	
$db = JFactory::getDBO();
$sql = "Select datetype FROM #__guru_config where id=1 ";
$db->setQuery($sql);
$format_date = $db->loadColumn();
$dateformat = $format_date[0];
$amount_quest = guruAdminModelguruQuiz::getAmountQuestions($program->id);

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
		
	function submitbutton (pressbutton){
		var form = document.adminForm;
		if (pressbutton=='save' || pressbutton=='apply') {
			if (form['name'].value == "") {
				alert( "<?php echo JText::_("JS_INSERT_CMPNAME");?>" );
			} 
			else if (form['author'].value == "- select -") {
				alert( "<?php echo JText::_("GURU_CS_PLSINSAUTHOR");?>" );				
			}
			else {
				submitform( pressbutton );
			}
		}
		else {
			submitform( pressbutton );
		}
	}
		
	//Joomla.submitbutton2 = function(pressbutton){
	function submitbutton2(pressbutton) {
		var form = document.adminForm;
		if (pressbutton=='savesbox') {
			if (form['name'].value == "") {
				alert( "<?php echo JText::_("GURU_TASKS_JS_NAME");?>" );
			} 
			else{
				//var screen_id = document.getElementById('screen').value;
				submitform(pressbutton);					
			}
		}
	}	
		
	function loadjscssfile(filename, filetype){
		if (filetype=="js"){ //if filename is a external JavaScript file
			var fileref=document.createElement('script');
			fileref.setAttribute("type","text/javascript");
			fileref.setAttribute("src", filename);
		}
		else if (filetype=="css"){ //if filename is an external CSS file
			var fileref=document.createElement("link");
			fileref.setAttribute("rel", "stylesheet");
			fileref.setAttribute("type", "text/css");
			fileref.setAttribute("href", filename);
		}
		if (typeof fileref!="undefined"){
			document.getElementsByTagName("head")[0].appendChild(fileref);
		}
	}
			
	function loadprototipe(){
		loadjscssfile("<?php echo JURI::base().'components/com_guru/views/gurutasks/tmpl/prototype-1.6.0.2.js' ?>","js");
	}

	function addmedia (idu, name, asoc_file, description) {
	//
		loadprototipe();
		var url = 'components/com_guru/views/gurutasks/tmpl/ajaxAddMedia.php?type=quiz&id='+idu;
		myAjax= new Ajax(url, {
			method: 'get',
			asynchronous: 'true',
			onSuccess: function(transport) {			
				to_be_replaced=parent.document.getElementById('media_15');
				to_be_replaced.innerHTML = '&nbsp;';
				
					to_be_replaced.innerHTML += transport;
					parent.document.getElementById("media_"+99).style.display="";
					parent.document.getElementById("description_med_99").innerHTML=''+name;		
				parent.document.getElementById('before_menu_med_'+replace_m).style.display = 'none';
				parent.document.getElementById('after_menu_med_'+replace_m).style.display = '';
				parent.document.getElementById('db_media_'+replace_m).value = idu;
			
				replace_edit_link = parent.document.getElementById('a_edit_media_'+replace_m);
				replace_edit_link.href = 'index.php?option=com_guru&controller=guruQuiz&tmpl=component&task=editsbox&cid[]='+ idu;
				var qwe='&nbsp;'+transport.responseText+'<br /><div style="text-align:center"><i>'+ name +'</i></div><div  style="text-align:center"><i>' + description + '</i></div>';
				//window.parent.test1(qwe);
				window.parent.test(replace_m, idu,qwe);
			},
			onCreate: function(){  			
			}
		});
		myAjax.request();	
		window.parent.setTimeout('document.getElementById("sbox-window").close()', 1);
		return true;
	}
	
	function publish(x){
		var req = new Request.HTML({
			async: false,
			method: 'get',
			url: 'components/com_guru/views/guruquiz/tmpl/ajax.php?id='+x+'&action=publish',
			data: { 'do' : '1' },
			onComplete: function(response){
				document.getElementById('publishing'+x).innerHTML='<a class="icon-ok" style="cursor: pointer; text-decoration: none;" onclick="javascript:unpublish(\''+x+'\');"></a>';
			}
		}).send();
	}
	
	function unpublish(x){
		var req = new Request.HTML({
			async: false,
			method: 'get',
			url: 'components/com_guru/views/guruquiz/tmpl/ajax.php?id='+x+'&action=unpublish',
			data: { 'do' : '1' },
			onComplete: function(response){
				document.getElementById('publishing'+x).innerHTML='<a class="icon-remove" style="cursor: pointer; text-decoration: none;" onclick="javascript:publish(\''+x+'\');"></a>';
			}
		}).send();
	}
	
</script>
<style>
	#rowquestion {
		background-color:#ffffff;
	}
	#rowquestion tr{
		background-color:#ffffff;
	}
	#rowquestion td{
		background-color:#ffffff;
	}
</style>
<script>
	function delete_temp_m(i){
		document.getElementById('trm'+i).style.display = 'none';
		document.getElementById('mediafiletodel').value =  document.getElementById('mediafiletodel').value+','+i;
	}
	function delete_q(i){
		document.getElementById('trque'+i).style.display = 'none';
		document.getElementById('deleteq').value =  document.getElementById('deleteq').value+','+i;
	}
</script>

<div style="float:right">
	<div id="toolbar" class="btn-toolbar pull-right no-margin">
        <div id="toolbar-apply" class="btn-wrapper">
            <button class="btn btn-success" onclick="javascript:submitbutton2('savesbox');">
                <span class="icon-apply icon-white"></span>
                Save
            </button>
        </div>
   </div>
</div>

<form method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
 	<input type="hidden" name="page_width" value="0" />
	<input type="hidden" name="page_height" value="0" />
 	<script type="text/javascript">
		<?php 
			if($configuration->back_size_type == "1"){ 
				echo 'document.adminForm.page_width.value="'.$width.'";';
				echo 'document.adminForm.page_height.value="'.$height.'";';
			}
		
		?>
	</script>
    
     <ul id="gurutabs" data-tabs="tabs" class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab"><?php echo JText::_("GURU_GENERAL");?></a></li>
        <li><a href="#tab2" data-toggle="tab"><?php echo JText::_("GURU_QUESTIONS");?></a></li>
        <li><a href="#tab3" data-toggle="tab"><?php echo JText::_("GURU_PUBLISHING");?></a></li>
   </ul>
   <div class="tab-content" style="border-top:none!important;">
   <div class="tab-pane active" id="tab1">
		<div class="well"><?php if ($program_id<1) echo JText::_('GURU_NEWQUIZ'); else echo JText::_('GURU_EDITQUIZ');?></div>
        	<table class="adminform">
                <tr>
                    <td width="15%">
                        <?php echo JText::_('GURU_NAME'); ?>:<font color="#ff0000">*</font>
                    </td>
                    <td>
                        <input class="inputbox" type="text" id="name" name="name" size="40" maxlength="255" value="<?php echo $program->name; ?>" />
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_QUIZ_NAME"); ?>" >
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
                    <td>
                        <?php echo JText::_('GURU_PRODDESC');?>:
                    </td>
                    <td>
                        <?php //echo $editorul->display( 'description', ''.stripslashes($program->description),'100%', '300px', '20', '60' );?>
                        <textarea name="description" id="description" cols="40" rows="8"><?php echo stripslashes($program->description); ?></textarea>
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_QUIZ_PRODDESC"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                </tr>	
                <tr>
                    <td width="15%">
                        <?php echo JText::_('GURU_MINIMUM_SCORE_QUIZ'); ?>:
                    </td>
                    <td>
                    <?php if (isset($program->max_score)){
                            $program->max_score = $program->max_score;
                          }
                          else{
                            $program->max_score = 70;
                          }
                    
                    
                    
                    ?>
                        <input class="input-mini" type="text" id="max_score_pass" name="max_score_pass" value="<?php echo $program->max_score; ?>" style="float:left !important;" />&nbsp;
                        <span style="float:left !important; padding-top:4px; padding-left:19px;">% &nbsp;</span>
                   
                            <select id="show_max_score_pass" name="show_max_score_pass"  style="float:left !important;" class="input-small" >
                                <option value="0" <?php if($program->pbl_max_score == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                <option value="1" <?php if($program->pbl_max_score == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                            </select>
                            <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_MAX_SCORE_TOOLTIP'); ?>" >
                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                            </span>
                      </td>
                </tr>
                <tr>
                    <td width="15%">
                        <?php echo JText::_('GURU_QUIZ_CAN_BE_TAKEN'); ?>:
                    </td>
                    <td>
                        <select id="nb_quiz_taken" name="nb_quiz_taken" style="float:left !important;" class="input-small" >
                                <option value="1" <?php if($program->time_quiz_taken == "1"){echo 'selected="selected"'; }?> >1</option>
                                <option value="2" <?php if($program->time_quiz_taken == "2"){echo 'selected="selected"'; }?> >2</option>
                                <option value="3" <?php if($program->time_quiz_taken == "3"){echo 'selected="selected"'; }?> >3</option>
                                <option value="4" <?php if($program->time_quiz_taken == "4"){echo 'selected="selected"'; }?> >4</option>
                                <option value="5" <?php if($program->time_quiz_taken == "5"){echo 'selected="selected"'; }?> >5</option>
                                <option value="6" <?php if($program->time_quiz_taken == "6"){echo 'selected="selected"'; }?> >6</option>
                                <option value="7" <?php if($program->time_quiz_taken == "7"){echo 'selected="selected"'; }?> >7</option>
                                <option value="8" <?php if($program->time_quiz_taken == "8"){echo 'selected="selected"'; }?> >8</option>
                                <option value="9" <?php if($program->time_quiz_taken == "9"){echo 'selected="selected"'; }?> >9</option>
                                <option value="10"<?php if($program->time_quiz_taken == "10"){echo 'selected="selected"'; }?> >10</option>
                                <option value="11" <?php if($program->time_quiz_taken == "11"){echo 'selected="selected"'; }?>><?php echo JText::_("GURU_UNLIMPROMO");?></option>
                        </select>
                        
                        <span style="float:left !important;padding-top:4px;padding-left:2px;">&nbsp;<?php echo JText::_("GURU_TIMES_T"); ?>&nbsp;</span>
                        <select id="show_nb_quiz_taken" name="show_nb_quiz_taken" style="float:left !important;" class="input-small" >
                                <option value="0" <?php if($program->show_nb_quiz_taken == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                <option value="1" <?php if($program->show_nb_quiz_taken == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                        </select>&nbsp;
                        <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_TIMES_TAKEN_TOOLTIP'); ?>" >
                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                            </span>
                    </td>
                </tr>
                <tr>
                    <td width="15%">
                        <?php echo JText::_('GURU_SELECT_UP_TO'); ?>:
                    </td>
                    <td style="padding-top:5px" nowrap="nowrap">
                        <select id="nb_quiz_select_up" name="nb_quiz_select_up" >
                                <?php
                                if(isset($amount_quest) && $amount_quest !=0 ){
                                    for($i=$amount_quest; $i>=1; $i--){?>
                                        <option value="<?php echo $i;?>" <?php if($program->nb_quiz_select_up == $i){echo 'selected="selected"'; }?> ><?php echo $i;?></option>
                                    <?php 
                                    }
                                }
                                else{
                                ?>
                                    <option value="text1"><?php echo "Please add questions first";?></option>
                                
                                <?php
                                }
                                ?>
                        </select>
                       <span style="float:left !important;">&nbsp;<?php echo JText::_('GURU_QUESTION_RANDOM'); ?>&nbsp;</span>
                       <select id="show_nb_quiz_select_up" name="show_nb_quiz_select_up" style="float:left !important;" class="input-small" >
                                <option value="0" <?php if($program->show_nb_quiz_select_up == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                <option value="1" <?php if($program->show_nb_quiz_select_up == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                        </select>&nbsp;
                       <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_QUESTIONS_NUMBER_TOOLTIP'); ?>" >
                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                            </span>
                    </td>
                </tr>
                <tr>
                    <td width="15%">
                        <?php echo JText::_('GURU_QUESTIONS_PER_PAGE'); ?>:
                    </td>
                    <td style="padding-top:5px">
                        <?php
                            $questions_per_page = $program->questions_per_page;
                        ?>
                        <select name="questions_per_page">
                            <option value="5" <?php if($questions_per_page == "5"){echo 'selected="selected"';} ?> >5</option>
                            <option value="10" <?php if($questions_per_page == "10"){echo 'selected="selected"';} ?> >10</option>
                            <option value="15" <?php if($questions_per_page == "15"){echo 'selected="selected"';} ?> >15</option>
                            <option value="20" <?php if($questions_per_page == "20"){echo 'selected="selected"';} ?> >20</option>
                            <option value="25" <?php if($questions_per_page == "25"){echo 'selected="selected"';} ?> >25</option>
                            <option value="30" <?php if($questions_per_page == "30"){echo 'selected="selected"';} ?> >30</option>
                            <option value="50" <?php if($questions_per_page == "50"){echo 'selected="selected"';} ?> >50</option>
                            <option value="100" <?php if($questions_per_page == "100"){echo 'selected="selected"';} ?> >100</option>
                            <option value="0" <?php if($questions_per_page == "0"){echo 'selected="selected"';} ?> >All</option>
                        </select>
                        &nbsp;
                        <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_QUESTIONS_PER_PAGE_TOOLTIP'); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                </tr>
                <tr>
                <td style=" font-weight:bold; font-size:25px" >Timer:</td>
                </tr>
                <tr>
                    <td width="15%">
                        <?php echo JText::_('GURU_QUIZ_LIMIT_TIME'); ?>:
                    </td>
                    <td style="padding-top:5px">
                     <?php if (isset($program->limit_time)){
                            $program->limit_time = $program->limit_time;
                          }
                          else{
                            $program->limit_time = 3;
                          }
                    
                    
                    
                    ?>
                        <input class="input-mini" type="text" id="limit_time_l" name="limit_time_l" maxlength="255" value="<?php echo $program->limit_time; ?>" style="float:left !important;" />
                        <span style="float:left !important; padding-top:4px; padding-left:2px;">&nbsp;<?php echo JText::_('GURU_PROGRAM_DETAILS_MINUTES'); ?>&nbsp;</span>
                       <select id="show_limit_time" name="show_limit_time" style="float:left !important;" class="input-small" >
                                <option value="0" <?php if($program->show_limit_time == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                <option value="1" <?php if($program->show_limit_time == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                        </select>&nbsp;
                        <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_QUIZ_LIMIT_TIME_TOOLTIP'); ?>" >
                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="15%">
                        <?php echo JText::_('GURU_SHOW_COUNTDOWN'); ?>:
                    </td>
                    <td style="padding-top:9px">
                        <select id="show_countdown" name="show_countdown" style="float:left !important;" class="input-small" >
                                <option value="0" <?php if($program->show_countdown == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                <option value="1" <?php if($program->show_countdown == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                        </select>&nbsp;
                        <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_SHOW_COUNTDOWN_TOOLTIP'); ?>" >
                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="15%">
                        <?php echo JText::_('GURU_FINISH_ALERT'); ?>:
                    </td>
                    <td style="padding-top:5px">
                     <?php if (isset($program->limit_time_f)){
                            $program->limit_time_f = $program->limit_time_f;
                          }
                          else{
                            $program->limit_time_f = 1;
                          }
                    
                    
                    
                    ?>
                        <input class="input-mini" type="text" id="limit_time_f" name="limit_time_f" maxlength="255" value="<?php echo $program->limit_time_f; ?>" style="float:left !important;" />
                        <span style="float:left !important; padding-top:4px; padding-left:2px;">&nbsp;<?php echo JText::_('GURU_MINUTES_BEFORE_IS_UP'); ?>&nbsp;</span>
                        
                        <select id="show_finish_alert" name="show_finish_alert" style="float:left !important;" class="input-small" >
                                <option value="0" <?php if($program->show_finish_alert == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                <option value="1" <?php if($program->show_finish_alert == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                        </select>&nbsp;
                       <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_FINISH_ALERT_TOOLTIP'); ?>" >
                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                       </span>
                    </td>
                </tr>
            </table>
		</div>
    <div class="tab-pane" id="tab2">
		<div class="well"><?php if ($program_id<1) echo JText::_('GURU_NEWQUIZ'); else echo JText::_('GURU_EDITQUIZ');?></div>
            <a rel="{handler: 'iframe', size: {x: 550, y: 300}}" href="index.php?option=com_guru&controller=guruQuiz&task=addquestion&tmpl=component&no_html=1&cid[]=<?php echo $program_id;?>&is_from_modal=1" class="modal" style="color:#666666 !important; text-decoration:underline !important;">
                <?php echo JText::_('GURU_ADD_QUESTION'); ?>
            </a>
            <br/><br/>
            <table id="articleList" class="table" >
                <tbody id="rowquestion">
                    <tr>
                        <td><?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?></td>
                        <td></td>
                        <td><strong><?php echo JText::_('Question');?></strong></td>
                        <td><strong><?php echo JText::_('Remove');?></strong></td>
                        <td><strong><?php echo JText::_('Edit');?></strong></td>
                        <td><strong><?php echo JText::_('GURU_PUBLISHED');?></strong></td>
                    </tr>
                    <?php 
                    if(isset($_POST['deleteq'])) $hide_q2del = $_POST['deleteq']; else  $hide_q2del = ',';
                    $hide_q2del = explode(',', $hide_q2del);
                    foreach ($mmediam as $mmedial) { 
                        $link2_remove = '<font color="#FF0000"><span onClick="delete_q('.$mmedial->id.')">Remove</span></font>';?>
                        <tr id="trque<?php echo $mmedial->id; ?>" <?php if(in_array($mmedial->id,$hide_q2del)) { ?> style="display:none" <?php } ?>>
                            <td>
                            <span class="sortable-handler active" style="cursor: move;">
                                <i class="icon-menu"></i>
                            </span>
                             <input  type="text" class="input-mini" value="<?php echo $mmedial->order; ?>" name="order[]" style="display:none;">
                            </td>  
                            <td  width="5%" style="visibility:hidden;"><?php $checked = JHTML::_('grid.id', $i, $mmedial->media_id); echo $checked;?></td>
                            <td id="tdq<?php echo $mmedial->id?>" width="39%">
                                <a rel="{handler: 'iframe', size: {x: 560, y: 350}}"  href="index.php?option=com_guru&controller=guruQuiz&task=editquestion&tmpl=component&no_html=1&cid[]=<?php echo $program_id.'&qid='.$mmedial->id;?>"  class="modal">
                                    <?php if (strlen ($mmedial->text) >55) echo substr($mmedial->text,0,55).'...'; else echo $mmedial->text; ?>
                                </a>
                            </td>
                            <td><?php echo $link2_remove; ?></td>
                            <td>
                                <a rel="{handler: 'iframe', size: {x: 560, y: 350}}"  href="index.php?option=com_guru&controller=guruQuiz&task=editquestion&tmpl=component&no_html=1&cid[]=<?php echo $program_id.'&qid='.$mmedial->id;?>"  class="modal">Edit</a>
                            </td>
                            <td id="publishing<?php echo $mmedial->id; ?>">
                            	<?php 
									if($mmedial->published == 1) {
										echo '<a class="icon-ok" style="cursor: pointer; text-decoration: none;" onclick="javascript:unpublish(\''.$mmedial->id.'\');"></a>';
									}
									else{
										echo '<a class="icon-remove" style="cursor: pointer; text-decoration: none;" onclick="javascript:publish(\''.$mmedial->id.'\');"></a>';
									}
								?>
                            </td>
                        </tr>
            <?php   }?>
                    <input type="hidden" value="<?php if (isset($_POST['newquizq'])) echo $_POST['newquizq'];?>" id="newquizq" name="newquizq" >
                    <input type="hidden" value="<?php if (isset($_POST['deleteq'])) echo $_POST['deleteq'];?>" id="deleteq" name="deleteq" >
                 </tbody>
            </table>
	</div>
     <div class="tab-pane" id="tab3">
		<div class="well"><?php if ($program_id<1) echo JText::_('GURU_NEWDAY'); else echo JText::_('GURU_EDITDAY');?></div>
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
					if ($program->id<1){
							$start_publish =  date("".$dateformat."", time());
						}
						else{
							$start_publish =  date("".$dateformat."", strtotime($program->startpublish));
						}
                		echo JHTML::_('calendar', $start_publish, 'startpublish', 'startpublish', $format, array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
                </td>
			</tr>
            <tr>
                <td valign="top" align="right">
                	<?php echo JText::_('GURU_PRODLEPUB'); ?>
                </td>
                <td>
                	<?php 
					if(substr($program->endpublish,0,4) =='0000' || $program->endpublish == JText::_('GURU_NEVER')|| $program->id<1) $program->endpublish = JText::_('GURU_NEVER'); else $program->endpublish = date("".$dateformat."", strtotime($program->endpublish));  
                	echo JHTML::_('calendar', $program->endpublish, 'endpublish', 'endpublish', $format, array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
                </td>
			</tr>
        </table>
	</div>
    </div>
    <input type="hidden" name="id" value="<?php echo $program_id; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="option" value="com_guru" />
    <input type="hidden" name="image" value="<?php if(isset($_POST['image'])) echo $_POST['image']; else echo $program->image;?>" />
    <input type="hidden" name="controller" value="guruQuiz" />
    <input type="hidden" name="is_from_modal" value="1">
    <a id="close_gb" style="display:none;">#</a>
</form>
