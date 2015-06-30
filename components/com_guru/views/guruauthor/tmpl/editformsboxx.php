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

defined ('_JEXEC') or die ("Go away.");
	$mediaval1	= JRequest::getVar("med","","get","string");
	$mediaval2	= JRequest::getVar("txt","","get","string");
	$scr		= JRequest::getVar("scr","0","get","int");
	$txt		= JRequest::getVar("txt","0","get","int");
	$cid = JRequest::getVar("cid", "0");

	$data 	= $this->data;
	$_row	= $this->media;
	$lists 	= $_row->lists;

	$nullDate = 0;
	$livesite = JURI::base();	
	$configuration = $this->getConfigsObject();	
	$editorul  = JFactory::getEditor();
	
	$UPLOAD_MAX_SIZE = @ini_get('upload_max_filesize');
	$max_post 		= (int)(ini_get('post_max_size'));
	$memory_limit 	= (int)(ini_get('memory_limit'));
	$UPLOAD_MAX_SIZE = min($UPLOAD_MAX_SIZE, $max_post, $memory_limit);
	if($UPLOAD_MAX_SIZE == 0) {$UPLOAD_MAX_SIZE = 10;}
	
	$maxUpload = "<font color='#FF0000'>";
	$maxUpload .= JText::_('GURU_MEDIA_MAX_UPL_V_1')." ";
	$maxUpload .= $UPLOAD_MAX_SIZE.'M ';
	$maxUpload .= JText::_('GURU_MEDIA_MAX_UPL_V_2');
	$maxUpload .= "</font>";

	$doc = JFactory::getDocument();
	$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');
	$doc->addStyleSheet('media/jui/css/bootstrap-extended.css');
	
	$doc->addStyleSheet('media/jui/css/bootstrap.min.css');
	
	include(JPATH_SITE.DS.DS.'components'.DS.'com_guru'.DS.'helpers'.DS.'createUploader.php');
	JHtml::_('behavior.framework');	
	
?>

	<script type="text/javascript" src="<?php echo JURI::base(); ?>components/com_guru/views/gurumedia/tmpl/js.js"></script>	
	<script language="javascript" type="text/javascript">
		function changefolder() {								
			submitbutton('changes');
		}
		
		//Joomla.submitbutton2 = function(pressbutton){
		function submitbutton2(pressbutton) {
			var form = document.adminForm;
			<?php //echo $editorul->save( 'text' );?>
			if(pressbutton=='savesbox'){
				if(form['name'].value == ""){
					alert( "<?php echo JText::_("GURU_MEDIA_JS_NAME_ERR");?>" );
				} 
				else if(form['type'].value == 0){
					alert( "<?php echo JText::_("GURU_MEDIA_JS_TYPE_ERR");?>" );
				}
				else if(form['type'].value == 'image' && form['is_image'].value == 0){
					alert( "<?php echo JText::_("GURU_MEDIA_JS_IMAGE_ERR");?>" );
				}
				else if (form['type'].value == 'text' && document.getElementById('text').value == '' ){
					alert( "<?php echo JText::_("GURU_MEDIA_JS_TEXT_ERR");?>" );
				}
				else if(form['type'].value == 'video'){
					if(document.getElementById("source_code_v").checked == true){
						if(form['code_v'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else if(document.getElementById("source_url_v").checked == true){
						if(form['url_v'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else if(document.getElementById("source_local_v2").checked == true){
						if(form['localfile'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else{
						alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
						return false;
					}
					submitform( pressbutton );
				}
				else if(form['type'].value == 'audio'){
					if(document.getElementById("source_code_a").checked == true){
						if(form['code_a'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else if(document.getElementById("source_url_a").checked == true){
						if(form['url_a'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else if(document.getElementById("source_local_a2").checked == true){
						if(form['localfile_a'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else{
						alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
						return false;
					}
					submitform( pressbutton );
				}
				else if(form['type'].value == 'docs'){
					if(document.getElementById("source_url_d").checked == true){
						if(form['url_d'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else if(document.getElementById("source_local_d2").checked == true){
						if(form['localfile_d'].value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else{
						alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
						return false;
					}
					submitform( pressbutton );
				}
				else if(form['type'].value == 'file'){
					if(document.getElementById("source_url_f").checked == true){
						if(document.getElementById("url_f").value == ""){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else if(document.getElementById("source_local_f2").checked == true){
						if(document.getElementById("localfile_f").value == "" || document.getElementById("localfile_f").value == "root"){
							alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
							return false;
						}
					}
					else{
						alert("<?php echo JText::_("GURU_SELECT_FILE"); ?>");
						return false;
					}
					submitform( pressbutton );
				}
				else{
					submitform( pressbutton );					
				}
			}
			else{	
				submitform( pressbutton );
			}	
		}
				
		function store_sessions(scr,ldb,dt1,dt2,dt3,dt4,dt5,dt6,dm1,dm2,dm3,dm4,dm5,dm6,dm7) {
			var url = 'components/com_guru/views/gurutasks/tmpl/store_sessions.php?ldb='+ldb+'&scr='+scr+'&dt='+dt1+','+dt2+','+dt3+','+dt4+','+dt5+','+dt6+'&dm='+dm1+','+dm2+','+dm3+','+dm4+','+dm5+','+dm6+','+dm7;
			new Ajax.Request(url, {
			  method: 'get',
			  asynchronous: 'true',
			  onSuccess: function(transport) {
			  },
			  onCreate: function(){
			  }
			});
			return true;
		}	
		
		function SelectArticleg(id, title, object){ 
			document.getElementById('articleid').value = id;
			document.getElementById('article_name').value = title;	
			document.getElementById('sbox-btn-close').click();
		}	
		
	</script>
    
    <style type="text/css">
		.redactor_box {
			width:60%;
		}
	</style>
    
<?php 
	$document = JFactory::getDocument();
	$document->addStyleSheet("components/com_guru/css/ytb.css"); 

	$action = JRequest::getVar("action", "");
?>	
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-horizontal">
	<h4><?php if(isset($_row->id)) {echo  JText::_('GURU_DAY_EDIT_MEDIA');} else{echo  JText::_('GURU_DAY_NEW_MEDIA');} ?></h4>
	
    <div class="control-group" id="auto_play" style="display:<?php echo $auto_play_display; ?>">
        <label class="control-label" for="author_title">
        </label> 
        <div class="controls pull-right">
            <input class="btn btn-success" border="0" type="button" name="savesbox" id="savesbox" value="<?php echo JText::_("GURU_SAVE_AND_CLOSE"); ?>" onClick="javascript:submitbutton2('savesbox');" />
        </div>
    </div>
    
    <?php 
		if($cid > 0){
	?>
			<input type="hidden" name="type" value="text" />
	<?php 
		}
		else{ 
			if($action == "addtext"){
				$_row->type='text';
				echo '<input type="hidden" name="type" value="text" />';
			}
			else{
				if($_row->type != 'text'){
	?>	
                    <div class="control-group" id="auto_play" style="display:<?php echo @$auto_play_display; ?>">
                        <label class="control-label" for="author_title">
                            <?php echo JText::_('GURU_TYPE'); ?>:<font color="#ff0000">*</font>
                        </label> 
                        <div class="controls">
                            <?php
                                echo $lists['type']; 
                            ?>
                        </div>
                    </div>
	<?php	
				}
    		}
		}
		?>
    
    <div class="control-group" id="auto_play" style="display:<?php echo $auto_play_display; ?>">
        <label class="control-label" for="author_title">
        	<?php echo JText::_('Name');?>:<font color="#ff0000">*</font>
        </label> 
        <div class="controls">
            <input class="formField" type="text" name="name" size="60" value="<?php echo str_replace('"', "&quot;", $_row->name); ?>">
        </div>
    </div>

    <div class="control-group" id="auto_play" style="display:<?php echo $auto_play_display; ?>">
        <label class="control-label" for="author_title">
        	<?php echo JText::_('GURU_INSTR');?>:
        </label> 
        <div class="controls">
            <textarea class="formField" type="text" name="instructions" rows="2" cols="60" ><?php echo stripslashes($_row->instructions); ?></textarea>
        </div>
    </div>
    
    <div class="control-group" id="auto_play" style="display:<?php echo $auto_play_display; ?>">
        <label class="control-label" for="author_title">
        	<?php echo JText::_('GURU_SHOW_INSTRUCTION');?>:
        </label> 
        <div class="controls">
            <select name="show_instruction" id="show_instruction">
                <option value="0" <?php if($_row->show_instruction == "0"){echo 'selected="selected"'; } ?> ><?php echo JText::_("GURU_SHOW_ABOVE"); ?></option>
                <option value="1" <?php if($_row->show_instruction == "1"){echo 'selected="selected"'; } ?> ><?php echo JText::_("GURU_SHOW_BELOW"); ?></option>
                <option value="2" <?php if($_row->show_instruction == "2"){echo 'selected="selected"'; } ?> ><?php echo JText::_("GURU_DONT_SHOW"); ?></option>
            </select>
        </div>
    </div>
    
    <div class="control-group" id="auto_play" style="display:<?php echo $auto_play_display; ?>">
        <label class="control-label" for="author_title">
        	<?php echo JText::_('GURU_MEDIATYPETEXT');?>:<font color="#ff0000">*</font>
        </label> 
        <div class="controls">
            <?php
				$doc = JFactory::getDocument();
				$doc->addScript(JURI::root().'components/com_guru/js/redactor.min.js');
				$doc->addStyleSheet(JURI::root().'components/com_guru/css/redactor.css');			
			?>
            <textarea id="text" name="text" class="useredactor" style="width:70%; height:100px;"><?php echo $_row->code; ?></textarea>
            <?php
				$upload_script = 'window.addEvent( "domready", function(){
									jQuery(".useredactor").redactor({
										 buttons: [\'bold\', \'italic\', \'underline\', \'link\', \'alignment\', \'unorderedlist\', \'orderedlist\']
									});
									jQuery(".redactor_useredactor").css("height","300px").css("width", "100%");
								  });';
				$doc->addScriptDeclaration($upload_script);
			?>
        </div>
    </div>
    
    <div class="control-group" id="auto_play" style="display:<?php echo $auto_play_display; ?>">
        <label class="control-label" for="author_title">
        </label> 
        <div class="controls pull-right">
            <input class="btn btn-success" border="0" type="button" name="savesbox" id="savesbox" value="<?php echo JText::_("GURU_SAVE_AND_CLOSE"); ?>" onClick="javascript:submitbutton2('savesbox');" />
        </div>
    </div>

<?php
	$action = JRequest::getVar("action", "addmedia");
?>
	<input type="hidden" name="action" value="<?php echo $action; ?>" />
	<input type="hidden" name="option" value="com_guru" />
	<input type="hidden" name="task" value="edit" />
	<input type="hidden" name="id" value="<?php echo $_row->id;?>" />
	<input type="hidden" name="mediatext" value="<?php 
	
		if($mediaval1!=""){
			echo "med";
		}
		elseif($mediaval2!=""){
			echo "txt";
		}
	?>" id="mediatext" />
	<input type="hidden" name="mediatextvalue" value="<?php 
		if($mediaval1!=""){
			echo $mediaval1;
		}
		elseif($mediaval2!=""){
			echo $mediaval2;
		}
	?>" id="mediatextvalue" />
	<input type="hidden" name="controller" value="guruAuthor" />
	<input type="hidden" name="screen" id="screen"  value="<?php echo $scr; ?>" />
    <?php
    	$user = JFactory::getUser();
	?>
    <input type="hidden" name="author" value="<?php echo intval($user->id); ?>" />

	<script type="text/javascript">
		var currentURL = window.location;
		document.write('<input type="hidden" name="redirect_to" value="'+currentURL.href+'" />');
	</script>
</form>