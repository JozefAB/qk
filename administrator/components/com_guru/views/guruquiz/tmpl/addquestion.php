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


$ins_id = guruAdminModelguruQuiz::id_for_last_question();
$ins_id=$ins_id+1;

$configuration = guruAdminModelguruQuiz::getConfigs();

$temp_size = $configuration->lesson_window_size_back;
$temp_size_array = explode("x", $temp_size);
$width = $temp_size_array["1"]-20;
$height = $temp_size_array["0"]-20;
?>

<script type="text/javascript" language="javascript">
	
	function addquestion (qid, idu) {
		var completed = 0;
		var qqqqid=<?php echo intval($_GET['cid'][0]); ?>;
		for(i=1; i<=10; i++){
				if(document.getElementById('a'+i).value!='')
					completed = i;
		}
		var existing_answer = 0;	
		for(i=1; i<=completed; i++){
			if(document.getElementById(i+'a').checked == true){ 
				existing_answer = 1;
			}
		}
		if (document.adminForm.text.value=='') {
			alert("Please enter the question text.");
			return false;
		} 
		else if (document.adminForm.a1.value=='') {
			alert("You must have at least one answer for your question");
			return false;
		} 
		else if (existing_answer == 0) {
			alert("Please check at least one answer as the correct answer");	
			return false;
		} 
		else {
			var myrow = parent.document.createElement('TR');
			myrow.id = 'trque'+idu;
			parent.document.getElementById('newquizq').value = parent.document.getElementById('newquizq').value+','+idu;
			parent.document.getElementById('rowquestion').appendChild(myrow);
			var mycell0 = top.document.createElement('TD');
			myrow.appendChild(mycell0);
			var mycellsix = top.document.createElement('TD');
			myrow.appendChild(mycellsix);
			var mycell = top.document.createElement('TD');
			myrow.appendChild(mycell);
			var mycellthree = top.document.createElement('TD');
			myrow.appendChild(mycellthree);
			var mycellfour = top.document.createElement('TD');
			myrow.appendChild(mycellfour);
			var mycellfive = top.document.createElement('TD');
			myrow.appendChild(mycellfive);
			var span ='<span class="sortable-handler active" style="cursor: move;"><i class="icon-menu"></i></span>';
			
			var cb = '<input style="visibility:hidden" type="checkbox" onclick="isChecked(this.checked);" value="'+idu+'" name="cid[]" id="cb'+(i)+'">';
			var value = "cb"+i;
			
			mycell0.innerHTML = span;
			mycellsix.innerHTML = cb;
			mycell.innerHTML=document.adminForm.text.value;
			mycellthree.innerHTML='<font color="#FF0000"><span onClick="delete_q('+idu+')">Remove</span></font>';
			mycellfour.innerHTML='<a style="color:#666666 !important;" class="modal" rel="{handler: \'iframe\', size: {x: 770, y: 400}}" href="index.php?option=com_guru&controller=guruQuiz&task=editquestion&tmpl=component&cid[]='+qqqqid+'&qid='+idu+'">Edit</a>';
			mycellfive.innerHTML='Published';
			document.adminForm.submit();
			setTimeout('window.parent.SqueezeBox.close()',1000);
			return true;
		}
		
		
	}
</script>

<form method="post" name="adminForm" id="adminForm" action="index.php">
	<input type="hidden" name="page_width" value="0" />
	<input type="hidden" name="page_height" value="0" />
 	<script type="text/javascript">
		<?php 
			if($configuration->back_size_type == "1"){ 
				echo 'document.adminForm.page_width.value="'.$width.'";';
				echo 'document.adminForm.page_height.value="'.$height.'";';
			}
			else{
		?>
				document.adminForm.page_width.value = window.innerWidth-100;				
				document.adminForm.page_height.value = window.innerHeight-20;				
		<?php
			}
		?>
	</script>
    <table id="g_quize_q" width="100%">
		<tr>
			<td>
				<strong><?php echo JText::_('GURU_QUESTION'); ?></strong>
			</td>
			<td>
				<strong><?php echo JText::_('GURU_ANSWER'); ?></strong>
				&nbsp;(<?php echo JText::_('GURU_CHECK_ANSWER'); ?>)
			</td>
		</tr>
		<tr>
			<td valign="top" align="left" style="border-bottom:1px solid #cccccc;">
				<textarea rows="7" cols="40" name="text" id="text"></textarea>
				<!--<input type="image" src="<?php //echo JURI::base()."components/com_guru/images/save.png"?>" onclick="addquestion(<?php //echo $_GET['cid'][0].','.$ins_id;?>)">-->
				<input type="button" class="btn" onclick="addquestion(<?php echo $_GET['cid'][0].','.$ins_id;?>)" value="<?php echo JText::_("GURU_SAVE_PROGRAM_BTN"); ?>">
			</td>
			<td valign="top" style="border-left:1px solid #cccccc;border-bottom:1px solid #cccccc;">
				<table width="100%">
					<tr>
						<td>1</td>
						<td>
							<input type="text" name="a1" id="a1" value="" size="32">&nbsp;<input type="checkbox" name="1a" id="1a">
                            <span class="lbl"></span>
						</td>
					</tr>
					<tr>
						<td>2</td>
						<td>
							<input type="text" name="a2" id="a2" value="" size="32">&nbsp;<input type="checkbox" name="2a" id="2a">
                            <span class="lbl"></span>
						</td>
					</tr>
					<tr>
						<td>3</td>
						<td>
							<input type="text" name="a3" id="a3" value="" size="32">&nbsp;<input type="checkbox" name="3a" id="3a">
                            <span class="lbl"></span>
						</td>
					</tr>
					<tr>
						<td>4</td>
						<td>
							<input type="text" name="a4" id="a4" value="" size="32">&nbsp;<input type="checkbox" name="4a" id="4a">
                            <span class="lbl"></span>
						</td>
					</tr>
					<tr>
						<td>5</td>
						<td><input type="text" name="a5" id="a5" value="" size="32">&nbsp;<input type="checkbox" name="5a" id="5a">
                        <span class="lbl"></span>
						</td>
					</tr>
					<tr>
						<td>6</td>
						<td>
							<input type="text" name="a6" id="a6" value="" size="32">&nbsp;<input type="checkbox" name="6a" id="6a">
                            <span class="lbl"></span>
						</td>
					</tr>
					<tr>
						<td>7</td>
						<td>
							<input type="text" name="a7" id="a7" value="" size="32">&nbsp;<input type="checkbox" name="7a" id="7a">
                            <span class="lbl"></span>
						</td>
					</tr>
					<tr>
						<td>8</td>
						<td>
							<input type="text" name="a8" id="a8" value="" size="32">&nbsp;<input type="checkbox" name="8a" id="8a">
                            <span class="lbl"></span>
						</td>
					</tr>
					<tr>
						<td>9</td>
						<td>
							<input type="text" name="a9" id="a9" value="" size="32">&nbsp;<input type="checkbox" name="9a" id="9a">
                            <span class="lbl"></span>
						</td>
					</tr>
					<tr>
						<td>10</td>
						<td>
							<input type="text" name="a10" id="a10" value="" size="32">&nbsp;<input type="checkbox" name="10a" id="10a">
                            <span class="lbl"></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<input type="hidden" value="com_guru" name="option"/>
	<input type="hidden" value="savequestion" name="task"/>
	<input type="hidden" value="<?php echo intval($_GET['cid'][0]);?>" name="quizid"/>
	<input type="hidden" value="guruQuiz" name="controller"/>
    <input type="hidden" value="<?php echo $_GET['is_from_modal'] ?>" name="is_from_modal">
</form>