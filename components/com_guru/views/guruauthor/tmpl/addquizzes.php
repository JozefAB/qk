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
$doc->addScript('components/com_guru/js/open_modal.js');
$doc->addStyleSheet('media/jui/css/bootstrap.min.css');
$doc->addStyleSheet('media/jui/css/bootstrap-extended.css');

$list_quizzes = $this->list_quizzes;
$n = count($list_quizzes);


?>

<script type="text/javascript" language="javascript">
	function savequizzes() {
		var chks = document.getElementsByName('cb[]');
		var hasChecked = false;
		for (var i = 0; i < chks.length; i++){
			if (chks[i].checked){
				old_value = document.getElementById('quizzes_ids').value;
				new_value = old_value+","+chks[i].value;
				document.getElementById('quizzes_ids').value = new_value;
				hasChecked = true;
			}
		}
		if (hasChecked == false){
			alert("Please select at least one quiz.");
			return false;
		}
			document.adminForm.submit();
	}
	
</script>


<h2 class="g_modal_title"><?php echo JText::_("GURU_ADD_QUIZZES_TO_FINAL_EXAM"). " ".@$quiz_name ; ?></h2>
		

<form class="g_modal_search" name="form1" action="index.php?option=com_guru&controller=guruAuthor&task=addquizzes&no_html=1&cid=<?php echo $_GET['cid'];?>&tmpl=<?php echo JRequest::getVar("tmpl", ""); ?>" method="post">
<div class="filter-search btn-group pull-left clearfix">
	<div class="input-group g_search">
        <input class="form-control inputbox" type="text" name="search_text"  value="<?php echo JRequest::getVar("search_text", "");?>" />
        <span class="input-group-btn">
            <button type="submit" name="submit_search"  class="btn btn-primary"><?php echo JText::_('GURU_SEARCHTXT');?></button>
        </span>
    </div>
</div>        
</form>
<form method="post" name="adminForm" id="adminForm" action="index.php">
    <div id="editcell" class="clearfix">
        <table class="table table-striped">
                <th></th>
                <th><?php echo JText::_("GURU_ID");?></th>
                <th><?php echo JText::_("VIEWPLUGTITLE");?></th>
        <?php
        $k = 0;
        for($i = 0; $i < count($list_quizzes); $i++){
            $id =  $list_quizzes[$i]["id"];
        ?>
            <tr class="row<?php echo $k;?>">
                <td><input type="checkbox" name="cb[]"  id="cb[]" value= "<?php echo $id;?>"><span class="lbl"></span></td>	
                <td><?php echo $list_quizzes[$i]["id"];?></td>
                <td><?php echo $list_quizzes[$i]["name"];?></td>
            </tr> 
        <?php
            $k = 1 - $k;
        }
        ?> 
        </table>
    </div>  
    <div>
        <input type="button" class="btn btn-success" onclick="savequizzes();" value="<?php echo JText::_("GURU_SAVE_PROGRAM_BTN"); ?>"> 
    </div> 
	<input type="hidden" value="com_guru" name="option"/>
	<input type="hidden" value="savequizzes" name="task"/>
	<input type="hidden" value="<?php echo intval($_GET['cid']);?>" name="quizid"/>
    <input id="quizzes_ids" name="quizzes_ids" type="hidden" value="" />
	<input type="hidden" value="guruAuthor" name="controller"/>
    <input type="hidden" value="<?php echo JRequest::getVar("tmpl", ""); ?>" name="tmpl"/>
</form>