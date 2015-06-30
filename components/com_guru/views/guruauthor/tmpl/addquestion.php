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

$doc = JFactory::getDocument();
$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');	
$doc->addScript('components/com_guru/js/jquery.noconflict.js');
$doc->addScript('components/com_guru/js/jquery.DOMWindow.js');


$ins_id = $this->id_for_last_question();
$ins_id=$ins_id+1;

$configuration = $this->getConfigsObject();

$temp_size = $configuration->lesson_window_size_back;
$temp_size_array = explode("x", $temp_size);
$width = $temp_size_array["1"]-20;
$height = $temp_size_array["0"]-20;
?>

<script type="text/javascript" language="javascript">
	
	function addquestion (qid, idu) {
		var completed = 0;
		var qqqqid=<?php echo intval($_GET['cid']); ?>;
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
			mycellfour.innerHTML='<a style="color:#666666 !important;" class="modal" rel="{handler: \'iframe\', size: {x: 770, y: 400}, iframeOptions: {id: \'g_teacher_addquestionss\'}}" href="<?php echo JURI::root(); ?>index.php?option=com_guru&controller=guruAuthor&task=editquestion&is_from_modal=1&tmpl=component&cid[]='+qqqqid+'&qid='+idu+'">Edit</a>';
			mycellfive.innerHTML='Published';
			document.adminForm.submit();
			setTimeout('window.parent.SqueezeBox.close()',1000);
			window.parent.document.getElementById("close-window").click();
			return true;
		}
		
		
	}
</script>

<style type="text/css">
	.g_table{
		margin-bottom: 0px !important;
		margin-top: 0px !important;
	}
</style>

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
    
    <input type="button" class="btn btn-success pull-right" onclick="addquestion(<?php echo $_GET['cid'].','.$ins_id;?>)" value="<?php echo JText::_("GURU_SAVE_PROGRAM_BTN"); ?>">
	<div class="clearfix"></div>
    
    <div id="g_quize_q" class="g_table_wrap">
        <div class="g_table clearfix" id="g_media_list_table">  
        	<div class="g_table_row">
                <div class="g_cell span6 g_table_cell g_th pull-left">
                    <div>
                        <div>
                            <?php echo JText::_('GURU_QUESTION'); ?>
                        </div>
                    </div>
                </div>
                <div class="g_cell span6 g_table_cell g_th pull-left">
                    <div>
                        <div>
                            <?php echo JText::_('GURU_ANSWER'); ?>
                            &nbsp;(<?php echo JText::_('GURU_CHECK_ANSWER'); ?>)
                        </div>
                    </div>
                </div>
			</div>
            <div class="clearfix"></div>
            <div class="g_table_row">
                <div class="g_cell span6 g_table_cell pull-left">
                    <div>
                        <div>
                            <textarea rows="7" cols="40" name="text" class="span12" id="text"></textarea>
                        </div>
                    </div>
                </div>
                <div class="g_cell span6 g_table_cell pull-left">
                    <div>
                        <div>
                            <div class="g_table_wrap">
        						<div class="g_table clearfix" id="g_media_list_table">
                                <?php
                                	for($i=1; $i<=10; $i++){
                                ?>
                                		<div class="g_table_row g_no_bg">
                                            <div class="g_cell span1 g_table_cell">
                                                <div>
                                                    <div>
                                                        <?php echo $i; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="g_cell span11 g_table_cell">
                                                <div>
                                                    <div>
                                                        <input type="text" name="a<?php echo $i; ?>" id="a<?php echo $i; ?>" value="" size="32" class="pull-left answer">&nbsp;
                                                        <input type="checkbox" name="<?php echo $i; ?>a" id="<?php echo $i; ?>a" >
                                                        <span class="lbl"></span>
                                                    </div>
                                                </div>
                                            </div>
										</div>
								<?php
                                    }
								?>
                                </div>
							</div>
                        </div>
                    </div>
                </div>
			</div>
            
		</div>
	</div>
    
	<input type="hidden" value="com_guru" name="option"/>
	<input type="hidden" value="savequestion" name="task"/>
	<input type="hidden" value="<?php echo intval($_GET['cid']);?>" name="quizid"/>
	<input type="hidden" value="guruAuthor" name="controller"/>
    <input type="hidden" value="<?php echo $_GET['is_from_modal'] ?>" name="is_from_modal">
</form>