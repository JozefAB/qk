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

JHtml::_('behavior.framework');
$medias = $this->medias;
$n = count($medias);
$db = JFactory::getDBO();

$sql = "select count(*) from #__guru_media";
$db->setQuery($sql);
$db->query();
$count = $db->loadresult();
$action = JRequest::getVar("action", "");

$doc = JFactory::getDocument();
$doc->addStyleSheet('media/jui/css/bootstrap.min.css');
$doc->addStyleSheet('media/jui/css/bootstrap-extended.css');

if(!isset($count) || $count == 0){
	echo "<b>".JText::_("GURU_NO_MEDIA")."</b>";
	return;
}
$v = 1;
$z = 1;
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
<link rel="StyleSheet" href="<?php echo JURI::root(); ?>media/jui/css/bootstrap.min.css" type="text/css"/>
<link rel="StyleSheet" href="<?php echo JURI::root(); ?>media/jui/css/bootstrap-responsive.min.css" type="text/css"/>
<link rel="StyleSheet" href="components/com_guru/css/guru-j30.css" type="text/css"/>


<script type="text/javascript" src="<?php echo JURI::root(); ?>media/jui/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo JURI::root(); ?>media/jui/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo JURI::root(); ?>media/system/js/mootools-core.js"></script>
<script type="text/javascript" src="<?php echo JURI::root(); ?>media/system/js/core.js"></script>
<script type="text/javascript" src="<?php echo JURI::root(); ?>media/system/js/mootools-more.js"></script>

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
  document.getElementsByTagName("head")[0].appendChild(fileref)
}
</script>

<!--<script type="text/javascript" src="<?php echo JURI::base().'components/com_guru/views/guruauthor/tmpl/prototype-1.6.0.2.js' ?>"></script>-->


<script>

function loadprototipe(){
	loadjscssfile("<?php echo JURI::base().'components/com_guru/views/guruauthor/tmpl/prototype-1.6.0.2.js' ?>","js");
}
function addmedia (idu, name, asoc_file, description, isquiz, the_real_quiz_id) {
    loadprototipe();
	var url = '<?php echo JURI::root(); ?>components/com_guru/views/guruauthor/tmpl/ajaxAddText.php?id='+idu;

	if(the_real_quiz_id == "new_module"){	
		replace_m = document.getElementById('to_replace').value;
		to_be_replaced = parent.document.getElementById('media_'+replace_m);
		to_be_replaced.innerHTML = name;
		parent.document.getElementById('db_media_'+replace_m).value = idu;
		parent.document.getElementById('before_menu_med_'+replace_m).style.display = 'none';
		parent.document.getElementById('after_menu_med_'+replace_m).style.display = '';
		
		replace_m = document.getElementById('to_replace').value;	
		to_be_replaced = parent.document.getElementById('text_'+replace_m);
		to_be_replaced.innerHTML = "";
		window.parent.document.getElementById('close').click();

		return true;
	}
	<?php
		if($v == 1){
	?>
		var req = new Request.HTML({
			method: 'get',
			asynchronous: 'false',
			url: url,
			data: { 'do' : '1' },	
			onSuccess: function(tree, elements, html){
				replace_m = document.getElementById('to_replace').value;
				to_be_replaced = parent.document.getElementById('text_'+replace_m);
				to_be_replaced.innerHTML = '&nbsp;';
				to_be_replaced.innerHTML += html;
		
				parent.document.getElementById('before_menu_txt_'+replace_m).style.display = 'none';
				parent.document.getElementById('after_menu_txt_'+replace_m).style.display = '';
				parent.document.getElementById('db_text_'+replace_m).value = idu;
				
				screen_id = document.getElementById('the_screen_id').value;
				replace_edit_link = parent.document.getElementById('a_edit_text_'+replace_m);
				if(isquiz == '0')
					replace_edit_link.href = '<?php echo JURI::root(); ?>index.php?option=com_guru&controller=guruAuthor&tmpl=component&task=editsboxx&cid='+ idu +'&scr=' + screen_id;		
				else	
					replace_edit_link.href = '<?php echo JURI::root(); ?>index.php?option=com_guru&controller=guruAuthor&tmpl=component&task=editQuiz&cid='+ the_real_quiz_id +'&scr=' + screen_id;		
					
				var qwe='<div style="text-align:center"><i>'+ name +'</i></div><br /><br /><div  style="text-align:center"><i>' + description + '</i></div>&nbsp;'+html+'<br /><br />';
				window.parent.txtest(replace_m, idu,qwe);
			},
			onCreate: function(){
			}
		}).send();
	<?php  }
		if($z == 1){?>
		//window.parent.close_modal();
	<?php  }?>
	setTimeout('window.parent.document.getElementById("close").click()',1000);

	return true;
}
</script>

<div style="float: left; font-weight:bold"><?php echo JText::_("GURU_CLICK_TO_TEXT"); ?></div>
<br /><br />
<div>
<form name="form1" action="index.php?option=com_guru&controller=guruAuthor&task=addtext&txt=<?php echo $_GET['txt'];?>&tmpl=component&cid=<?php echo $_GET['cid'];?>" method="post">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="left" class="g_top_filters">
			<div class="filter-search btn-group pull-left">
				<div class="input-group g_search">
			
				<?php
                    $search_value = JRequest::getVar("search_text", "");
                    if((isset($_SESSION["search_value"])) && ($search_value == "")){
                        $search_value = $_SESSION["search_value"];
                    }
                ?>
                <input type="text" name="search_text" value="<?php echo $search_value; ?>" class="form-control inputbox" />
                <span class="input-group-btn">
                    <button type="submit" name="submit_search" class="btn btn-primary"><?php echo JText::_("GURU_SEARCHTXT"); ?></button>
                </span>
				<input type="hidden" name="type" value="<?php if(isset($_GET['type'])) echo $_GET['type']; elseif (isset($_POST['type'])) echo $_POST['type'];?>" />
				</div>
			</div>
		</td>
		<td>
			<?php
				//echo JText::_('GURU_TREEMEDIACAT'),":"."&nbsp;";
				$all_media_categ = $this->getAllMediaCategs();
				$filter_media = JRequest::getVar("filter_media", "");
				
				if(isset($_SESSION["filter_media"])){
					if($filter_media != "" || $filter_media == "-1"){
						$_SESSION["filter_media"] = $filter_media;
					}
					else{
						$filter_media = $_SESSION["filter_media"];
					}
				}
			?>
			<!--<select name="filter_media"  onchange="document.form1.submit()">
			<option value="-1">- <?php echo JText::_("GURU_ALL_CATEGORIES"); ?> -</option>
			<?php 
				if(isset($all_media_categ) && count($all_media_categ) > 0){
					foreach($all_media_categ as $key=>$value){
						$selected = "";
						if($value["id"] == $filter_media){
							$selected = 'selected="selected"';
						}
						echo '<option value="'.$value["id"].'" '.$selected.'>'.$value["name"].'</option>';
					}
				}
			?>
			</select>-->
		</td>
	</tr>
</table>	
    <input type="hidden" name="controller" value="guruAuthor" />
    <input type="hidden" name="task" value="addtext" />	
    <input type="hidden" name="filter2" id="filter2" value="<?php if(isset($filter2)) {echo $filter2;} else {echo '3';}?>" />
    <input type="hidden" name="cid" value="<?php echo $pid; ?>">
    <input type="hidden" name="action" value="<?php echo $action; ?>">
</form>
</div>
<br />

<div>
<div id="editcell">
<table class="table table-striped adminlist">
<thead>
	<tr>
		<th width="20"><?php echo JText::_("GURU_ID"); ?></th>
		<th><?php echo JText::_("GURU_NAME"); ?></th>
<!--		<th><?php echo JText::_("GURU_TREEMEDIACAT"); ?></th>
-->		<th><?php echo JTExt::_("GURU_PUBLISHED"); ?></th>
	</tr>
</thead>

<tbody>
<?php 
	$pid = intval($_REQUEST['cid']);
	if($n>0){ 
	for ($i = 0; $i < $n; $i++):
	$file = $this->medias[$i];	
	$media_to_replace = $_GET['txt'];

	$id = $file->id;
	$checked = JHTML::_('grid.id', $i, $id);
	$asoc_file = $this->get_asoc_file_for_media($id);
	
	if($file->type=='quiz')
		{
			$the_quiz_id = $this->real_quiz_id($file->id);
		}		
	
	if($file->type=='quiz'){
		$link = "addmedia('".$id."', '".addslashes($file->name)."', '".addslashes($asoc_file)."', '".$file->instructions."' , '1', '".$the_quiz_id."' ); return false;";
	}	
	else{
		if($action == "new_module"){
			$link = "addmedia('".$id."', '".addslashes($file->name)."', '".addslashes($asoc_file)."', '".$file->instructions."' , '0', 'new_module' ); return false;";
		}
		else{
			$link = "addmedia('".$id."', '".addslashes($file->name)."', '".addslashes($asoc_file)."', '".$file->instructions."' , '0', '0' ); return false;";
		}	
	}	
	$published = JHTML::_('grid.published', $file, $i ); 
	
	if($file->type=='text')
	{
	?>
	<tr class="camp0"> 
	   <td><?php echo $file->id;?></td>		
	    <td><a onclick="<?php echo $link;?>" href="#"><?php echo $file->name;?></a></td>		
		<!--<td>
	     	  <?php echo $file->categ_name; ?>
		</td>-->		
		<td><?php echo $published;?></td>		
	</tr>
<?php 
	} // endif for MEDIA check
	endfor;
 } ?>

<form name="adminForm" action="index.php?option=com_guru&controller=guruAuthor&task=addtext&txt=<?php echo $_GET['txt'];?>&tmpl=component&cid=<?php echo $_GET['cid'];?>" method="post">

	<input type="hidden" name="filter2" id="filter2" value="<?php if(isset($filter2)) {echo $filter2;} else {echo '3';}?>" />
    
    <input type="hidden" name="controller" value="guruAuthor" />
	<input type="hidden" name="task" value="addtext" />
    <input type="hidden" name="cid" value="<?php echo $pid; ?>">
    <input type="hidden" name="action" value="<?php echo $action; ?>">
</form> 
 
</tbody>
</table>

</div>

</div>
<input type="hidden" id="to_replace" value="<?php echo $media_to_replace; ?>">
<input type="hidden" id="the_screen_id" value="<?php echo $pid; ?>">