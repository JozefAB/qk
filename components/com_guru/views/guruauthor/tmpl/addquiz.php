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

$quiz = $this->quiz;
$n = count($quiz);
JHtml::_('behavior.framework');
?>
<style>
table.adminlist {
background-color:#E7E7E7;
border-spacing:1px;
color:#666666;
width:100%;
font-family:Arial,Helvetica,sans-serif;
font-size:11px;
}
</style>
<link rel="stylesheet" href="<?php echo JURI::base()."components/com_guru/css/modal.css";?>" type="text/css" />

<script type="text/javascript" src="<?php echo JURI::root().'media/system/js/mootools.js' ?>"></script>
<script type="text/javascript" src="<?php echo JURI::base().'components/com_guru/js/modal.js' ?>"></script>
<script type="text/javascript">
function loadjscssfile(filename, filetype){
	if (filetype=="js"){ //if filename is a external JavaScript file
  		var fileref=document.createElement('script')
  		fileref.setAttribute("type","text/javascript")
  		fileref.setAttribute("src", filename)
	 }
 	else if (filetype=="css"){ //if filename is an external CSS file
 		var fileref=document.createElement("link")
 		fileref.setAttribute("rel", "stylesheet")
  		fileref.setAttribute("type", "text/css")
  		fileref.setAttribute("href", filename)
 	}
 	if (typeof fileref!="undefined")
  		document.getElementsByTagName("head")[0].appendChild(fileref);
}

function loadprototipe(){
	loadjscssfile("<?php echo JURI::root().'components/com_guru/views/guruauthor/tmpl/prototype-1.6.0.2.js' ?>","js");
	//alert('testing');
}

function addmedia (idu, name, asoc_file, description) {
	loadprototipe();
	
	var url = '<?php echo JURI::root(); ?>components/com_guru/views/guruauthor/tmpl/ajaxAddMedia.php?id='+idu+'&type=quiz';
	new Ajax.Request(url, {
		method: 'get',
		asynchronous: 'false',
		onSuccess: function(transport) {
			replace_m = document.getElementById('to_replace').value;
			to_be_replaced = parent.document.getElementById('media_'+replace_m);
			to_be_replaced.innerHTML = '&nbsp;';
			
			if(replace_m == 99){
				if ((transport.responseText.match(/(.*?).pdf(.*?)/))&&(!transport.responseText.match(/(.*?).iframe(.*?)/))) {to_be_replaced.innerHTML += transport.responseText+'<p /><div  style="text-align:center"><i>' + description + '</i></div>'; } else {
					to_be_replaced.innerHTML += transport.responseText+'<br /><div style="text-align:center"><i>'+ name +'</i></div><br /><div  style="text-align:center"><i>' + description + '</i></div>';
				}
			} else {
				to_be_replaced.innerHTML += transport.responseText;
				parent.document.getElementById("media_"+99).style.display="";
				parent.document.getElementById("description_med_99").innerHTML=''+name;
				
				parent.document.getElementById('before_menu_med_'+replace_m).style.display = 'none';
				parent.document.getElementById('after_menu_med_'+replace_m).style.display = '';
				parent.document.getElementById('db_media_'+replace_m).value = idu;
			}			
		
			screen_id = document.getElementById('the_screen_id').value;
			replace_edit_link = parent.document.getElementById('a_edit_media_'+replace_m);
			replace_edit_link.href = 'index.php?option=com_guru&controller=guruAuthor&tmpl=component&task=editMedia&cid='+ idu;
			if ((transport.responseText.match(/(.*?).pdf(.*?)/))&&(!transport.responseText.match(/(.*?).iframe(.*?)/))) {
				var qwe='&nbsp;'+transport.responseText+'<p /><div  style="text-align:center"><i>' + description + '</i></div>';
			} else {
				var qwe='&nbsp;'+transport.responseText+'<br /><div style="text-align:center"><i>'+ name +'</i></div><div  style="text-align:center"><i>' + description + '</i></div>';
			}
			window.parent.test(replace_m, idu,qwe);
		},
		onCreate: function(){}
	 });
	 
	setTimeout('window.parent.document.getElementById("close").click()',1000);
	return true;
}

</script>

	<div style="float: left; font-weight:bold"><?php echo JText::_("GURU_CLICK_TO_QUIZ"); ?></div>
		<br /><br />
	<div>
<form name="adminForm2" action="index.php?option=com_guru&controller=guruAuthor&task=addquiz&med=<?php echo $_GET['med'];?>&tmpl=component&cid=<?php echo $_GET['cid'];?><?php if(isset($_GET['quiz'])){echo "&quiz=".$_GET['quiz'];}?><?php if(isset($_REQUEST['type'])){echo "&type=".$_REQUEST['type'];}?>" method="post">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="g_top_filters">
				<input type="text" name="search_quiz" value="<?php 
					if(isset($_POST['search_quiz'])&&($_POST['search_quiz']!='')){
						echo $_POST['search_quiz'];
					} 
					elseif(isset($_SESSION['search_quiz_tskmed'])&&($_SESSION['search_quiz_tskmed']!='')) {
						echo $_SESSION['search_quiz_tskmed'];
					}?>" />
				<input type="hidden" name="type" value="<?php if(isset($_GET['type'])) echo $_GET['type']; elseif (isset($_POST['type'])) echo $_POST['type'];?>" />
				<input class="btn btn-primary" type="submit" name="submit_search" value="<?php echo JText::_("GURU_SEARCHTXT"); ?>" />
                <input type="hidden" name="type" value="<?php echo $_REQUEST['type']; ?>">
			</td>
			<td>
			</td>
			<td>
			</td>
		</tr>
	</table>		
</form>
</div>
<br />

<div>
<div id="editcell">
	<table class="table table-striped adminlist">
		<thead>
			<tr>
				<th width="20"><?php echo JText::_("GURU_ID"); ?></th>
				<th><?php echo JText::_("GURU_QUIZ"); ?></th>
				<th><?php echo JText::_("GURU_QUESTIONS"); ?></th>
				<th><?php echo JText::_("GURU_PUBLISH"); ?></th>
			</tr>
		</thead>
		<tbody>
<?php 
		$pid = intval($_GET['cid']);
		if ($n>0) { 
			for ($i = 0; $i < $n; $i++){
				$file =$quiz[$i];
				if(isset($_GET['med'])){	
					$media_to_replace = $_GET['med'];
					$_SESSION['addmed_tskmed_to_rep']=$_GET['med'];
				} elseif(isset($_SESSION['addmed_tskmed_to_rep'])){
					$media_to_replace = $_SESSION['addmed_tskmed_to_rep'];
				} else {
					$media_to_replace = NULL;
			}

			$id = $file->id;
			$checked = JHTML::_('grid.id', $i, $id);
			$asoc_file = "-";
			$link = "addmedia('".$id."', '".addslashes($file->name)."', '".addslashes($asoc_file)."', '' ); return false;";
			$published = $file->published; 
		?>
			<tr class="camp0"> 
	   			<td><?php echo $file->id;?></td>		
	    		<td><a onmouseover="loadprototipe()" onclick="<?php echo $link;?>" href="#"><?php echo $file->name;?></a></td>	
				<td>
                	<?php
						$howManyQuestions = $this->QuestionNo($id);
						echo $howManyQuestions;
					?>
                </td>	
				<td><?php if($published==1) { echo '<img src="components/com_guru/images/tick.png" alt="Published" />';} 
					else { echo '<img src="images/publish_x.png" alt="Unpublished" />';}
					?>
				</td>		
			</tr>
		<?php 
		} // endif for MEDIA check
 } ?>

	<form name="adminForm" id="adminForm" action="index.php?option=com_guru&controller=guruAuthor&task=addquiz&med=<?php echo $_GET['med'];?>&tmpl=component&cid=<?php echo $_GET['cid'];?>" method="post">
		<table>
        	<tr>
        		<td colspan="6">
                    <div class="pagination pagination-toolbar">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </div>
                    <div class="btn-group pull-left">
                        <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                        <?php echo $this->pagination->getLimitBox(); ?>
                   </div>
                </td>
			</tr>
		</table>
		<input type="hidden" name="filter2" id="filter2" value="<?php if(isset($filter2)) {echo $filter2;} else {echo '3';}?>" />
        <input type="hidden" name="old_limit" value="<?php echo JRequest::getVar("limitstart"); ?>" />
	</form>
	</tbody>
</table>
</div>

</div>
<input type="hidden" id="to_replace" value="<?php 
	echo $media_to_replace; 
?>">
<input type="hidden" id="the_screen_id" value="<?php 
	echo $pid; 
?>">