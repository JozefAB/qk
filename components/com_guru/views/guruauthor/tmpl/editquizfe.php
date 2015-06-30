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
JHTML::_('behavior.modal');
JHTML::_('behavior.framework');

$program = $this->program;
$lists = $program->lists;
$max_reo = $this->max_reo;
$min_reo = $this->min_reo;
if($program->id == ""){
	$program->id = 0;
}
$div_menu = $this->authorGuruMenuBar();

$mmediam = $this->mmediam;
foreach($mmediam as $mmm){
	$vector[] = $mmm->id;
}

$value_optiono = JRequest::getVar("v", "0");
if($program->is_final == 0){
	$value_option = 0;
}
else{
	$value_option = 1;
}
if($program->id == 0){
	$value_option = $value_optiono;
}
$mainmedia = $this->mainmedia;
$configuration = $this->getConfigsObject();
$amount_quest = $this->getAmountQuestions($program->id);
$amount_quest_quizzes = $this->getAmountQuizzes($program->id);

$listDirn = "asc";
$listOrder = "ordering";
$saveOrderingUrl = 'index.php?option=com_guru&controller=guruAuthor&task=saveOrderQuestions&tmpl=component';
JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);

$db = JFactory::getDBO();
$request_final = "";
if($value_optiono == "1"){
	$request_final = "&v=1";
}
$doc = JFactory::getDocument();
$doc->setTitle(trim(JText::_('GURU_AUTHOR'))." ".trim(JText::_('GURU_AUTHOR_MY_QUIZZES')));

require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

?>
<script language="javascript" type="text/javascript">
	function savequiz(pressbutton){
		var form = document.adminForm;
		if (pressbutton == 'save_quizFE' || pressbutton == 'apply_quizFE'){
			if(form['name'].value == ""){
				alert( "<?php echo JText::_("GURU_TASKS_JS_NAME");?>" );
			}
			else{
				submitform( pressbutton );
			}
		}
		else {
			submitform( pressbutton );
		}
	}

	function publish(x){
		var req = new Request.HTML({
			async: false,
			method: 'get',
			url: 'components/com_guru/views/guruauthor/tmpl/ajax.php?id='+x+'&action=publish<?php echo $request_final; ?>',
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
			url: 'components/com_guru/views/guruauthor/tmpl/ajax.php?id='+x+'&action=unpublish<?php echo $request_final; ?>',
			data: { 'do' : '1' },
			onComplete: function(response){
				document.getElementById('publishing'+x).innerHTML='<a class="icon-remove" style="cursor: pointer; text-decoration: none;" onclick="javascript:publish(\''+x+'\');"></a>';
			}
		}).send();
	}
	
	function showContentQuestion(href){
			jQuery.ajax({
			  url: href,
			  success: function(response){
			   jQuery( '#myModal .modal-body').html(response);
			  }
			});
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
<script>
	function delete_temp_m(i){
		document.getElementById('trm'+i).style.display = 'none';
		document.getElementById('mediafiletodel').value =  document.getElementById('mediafiletodel').value+','+i;
	}
	function delete_q(i,id,t){
		var deleted = i;
		var url = 'components/com_guru/views/guruauthor/tmpl/ajax.php?id='+id+'&deleted='+deleted+'&f='+t;		
		var req = new Request.HTML({
			method: 'get',
			asynchronous: 'true',
			url: url,
			data: { 'do' : '1' },
			onSuccess: function() {
					document.getElementById('trque'+i).style.display = 'none';

					document.getElementById('deleteq').value =  document.getElementById('deleteq').value+','+i;
					var rows = new Array();
					var ok = true;
			},
			onCreate: function(){
			}			
		}).send();		
	}
	
	function delete_fq(i, id,t){
		var deleted = i;
		var url = 'components/com_guru/views/guruauthor/tmpl/ajax.php?id='+id+'&deleted='+deleted+'&f='+t;		
		var req = new Request.HTML({
			method: 'get',
			asynchronous: 'true',
			url: url,
			data: { 'do' : '1' },
			onSuccess: function() {
					all_quizes_nr = document.getElementById("all_quizes_nr").value;
					document.getElementById("all_quizes_nr").value = (all_quizes_nr - 1);
					
					document.getElementById('trfque'+i).style.display = 'none';
					document.getElementById('deleteq').value =  document.getElementById('deleteq').value+','+i;
					var rows = new Array();
					var ok = true;
			},
			onCreate: function(){
			}			
		}).send();		
	}
</script>

<?php
	//$deviceType = "phone";
	
	if($deviceType != "phone"){
		// computer
		if($value_option == 0){
			// regular quiz
			include_once(JPATH_SITE.DS."components".DS."com_guru".DS."views".DS."guruauthor".DS."tmpl".DS."reg_q_calc.php");
		}
		else{
			// final quiz
			include_once(JPATH_SITE.DS."components".DS."com_guru".DS."views".DS."guruauthor".DS."tmpl".DS."final_q_calc.php");
		}
	}
	else{
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('
			document.onreadystatechange = function(){
				initPhoneTeacherTabs();
			}
		');
		$document->setMetaData( 'viewport', 'width=device-width, initial-scale=1.0' );
		
		// phone
		if($value_option == 0){
			// regular quiz
			include_once(JPATH_SITE.DS."components".DS."com_guru".DS."views".DS."guruauthor".DS."tmpl".DS."reg_q_phone.php");
		}
		else{
			// final quiz
			include_once(JPATH_SITE.DS."components".DS."com_guru".DS."views".DS."guruauthor".DS."tmpl".DS."final_q_phone.php");
		}
	}
?>