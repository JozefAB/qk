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
JHtml::_('bootstrap.framework');

$document = JFactory::getDocument();
$editor1  = JFactory::getEditor();

$document->addScriptDeclaration('
	document.onreadystatechange = function(){
		initPhoneTeacherTabs();
	}
');
$document->setMetaData( 'viewport', 'width=device-width, initial-scale=1.0' );
require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');	

$configs = $this->getConfigs();
$terms_cond_teacher = $configs["0"]["terms_cond_teacher"];
$terms_cond_teacher_content = $configs["0"]["terms_cond_teacher_content"];

?>
<style>
.alert-info { height:auto!important;}
.accordionItem.hideTabs div { display: none; }
 div.guru-content .btn_renew{
	height:28px;!important; 
	}
</style>
<script language="javascript" type="text/javascript">
var accordionItems = new Array();		
function initPhoneTeacherTabs() {
      // Grab the accordion items from the page
      var divs = document.getElementsByTagName( 'div' );
      for ( var i = 0; i < divs.length; i++ ) {
        if ( divs[i].className == 'accordionItem' ) accordionItems.push( divs[i] );
      }

      // Assign onclick events to the accordion item headings
      for ( var i = 0; i < accordionItems.length; i++ ) {
        var h3 = getFirstChildWithTagName( accordionItems[i], 'H3' );
        h3.onclick = toggleItem;
      }

      // Hide all accordion item bodies except the first
      for ( var i = 1; i < accordionItems.length; i++ ) {
        accordionItems[i].className = 'accordionItem hideTabs';
      }
    }

    function toggleItem() {
      var itemClass = this.parentNode.className;

      // Hide all items
      for ( var i = 0; i < accordionItems.length; i++ ) {
        accordionItems[i].className = 'accordionItem hideTabs';
      }

      // Show this item if it was previously hidden
      if ( itemClass == 'accordionItem hideTabs' ) {
        this.parentNode.className = 'accordionItem';
      }
    }

    function getFirstChildWithTagName( element, tagName ) {
      for ( var i = 0; i < element.childNodes.length; i++ ) {
        if ( element.childNodes[i].nodeName == tagName ) return element.childNodes[i];
      }
    }

		
function validateAuForm(field){
	var form = document.adminForm;
	var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");
	var x = new RegExp("[\<|\>|\"]","i");
	var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
	var valid_entry = true;
	
	// validate name --------------------------------------------------------------
	if(field == "name"){
		if(form.name.value == ""){
			document.getElementById("text_red_n").style.display="block";
			valid_entry = false;
		}
		else if(form.name.value != ""){
			document.getElementById("text_red_n").style.display="none";
		}
		
		if(x.exec(form.name.value)){
			document.getElementById("text_red_ni").style.display="block";
			valid_entry = false;
		}
	}
	
	// validate username --------------------------------------------------------------
	if(field == "username"){
		if(document.getElementById('guru_username').value  == ""){
			document.getElementById("text_red_ul").style.display="block";
			valid_entry = false;
		}
		
		if(document.getElementById('guru_username').value  != ""){
			document.getElementById("text_red_ul").style.display="none";
		}
		
		if(r.exec(form.username.value) || form.username.value.length <= 3){
			document.getElementById("text_red_uc").style.display="block";
			valid_entry = false;
		}
		else if(form.username.value.length > 3){
			document.getElementById("text_red_uc").style.display="none";
		}
	}
	
	
	// validate email --------------------------------------------------------------
	if(field == "email"){
		if(document.getElementById('email').value == ""){
			document.getElementById("text_rede").style.display="block";
			valid_entry = false;
		}
		
		if(document.getElementById('email').value != ""){
			document.getElementById("text_rede").style.display="none";
		}
		
		if(filter.test(form.email.value)){
			document.getElementById("text_redv").style.display="none";
		}
		else if(!filter.test(form.email.value)){
			document.getElementById("text_redv").style.display="block";
			valid_entry = false;
		}
	}
	
	// validate website --------------------------------------------------------------
	if(field == "website"){
		if(form.website.value.substring(0, 7)!="http://" && form.website.value.substring(0, 8)!="https://"){
			document.getElementById("text_red_wb").style.display="block";
			valid_entry = false;
		} 
		else if(form.website.value.substring(0, 7)!=""){
			document.getElementById("text_red_wb").style.display="none";
		}
	}
	
	// validate blog --------------------------------------------------------------
	if(field == "blog"){
		if(form.blog.value.substring(0, 7)!="http://" && form.blog.value.substring(0, 8)!="https://"){
			document.getElementById("text_red_blog").style.display="block";
			valid_entry = false;
		} 
		else if(form.blog.value.substring(0, 7)!=""){
			document.getElementById("text_red_blog").style.display="none";
		}
	}
	
	// validate facebook --------------------------------------------------------------
	if(field == "facebook"){
		if(form.facebook.value.substring(0, 7)!="http://" && form.facebook.value.substring(0, 8)!="https://"){
			document.getElementById("text_red_fb").style.display="block";
			valid_entry = false;
		}
		else if(form.facebook.value.substring(0, 7)!=""){
			document.getElementById("text_red_fb").style.display="none";
		}
	}
	
	// validate password --------------------------------------------------------------
	if(field == "password"){
		if(form.password.value == ""){
			document.getElementById("text_red_pass2").style.display="block";
			valid_entry = false;
		}
		else if(form.password.value != ""){
			document.getElementById("text_red_pass2").style.display="none";
		}
	}
	
	// validate password2 --------------------------------------------------------------
	if(field == "password2"){
		if(form.password2.value == ""){
			document.getElementById("text_red_pass4").style.display="block";
			valid_entry = false;
		}
		else if(form.password2.value != ""){
			document.getElementById("text_red_pass4").style.display="none";
		}
		
		if(form.password.value != form.password2.value){
			document.getElementById("text_red_pass3").style.display="block";
			valid_entry = false;
		}
		else if(form.password.value == form.password2.value){
			document.getElementById("text_red_pass3").style.display="none";
		}
	}
	
	valid_entry = checkAllFields();
}

function validateAuFormAjax(field){
	var valid_entry = true;
	username = document.getElementById("guru_username").value;
	if(username != ""){
		htmlvalue = "0";
		var req = new Request.HTML({
			async: false,
			method: 'get',
			url: '<?php echo JURI::root()."components/com_guru/js/ajax.php?task=checkExistingUserU";?>&username='+username+'&email='+email+'&id=<?php echo @$id; ?>',
			data: { 'do' : '1' },
			onComplete: function(response){
				document.getElementById("ajax_response_u").empty().adopt(response);
			}
		}).send();
		check_return_u = document.getElementById("ajax_response_u").innerHTML;
	}
	
	email = document.getElementById("email").value;
	if(email != ""){
		htmlvalue = "0";
		var req = new Request.HTML({
			async: false,
			method: 'get',
			url: '<?php echo JURI::root()."components/com_guru/js/ajax.php?task=checkExistingUserE";?>&username='+username+'&email='+email+'&id=<?php echo @$id; ?>',
			data: { 'do' : '1' },
			onComplete: function(response){
				document.getElementById("ajax_response_e").empty().adopt(response);
			}
		}).send();
		check_return_e = document.getElementById("ajax_response_e").innerHTML;
	}
	check_return_e = document.getElementById("ajax_response_e").innerHTML;
	
	if(check_return_e != 0){
		if(trimString(check_return_e) == '111'){// not validate email
			document.getElementById("text_red").style.display="block";
			document.getElementById("text_redv").style.display="none";
			valid_entry = false;
		}
		else if(trimString(check_return_e) == '222'){// validate email
			document.getElementById("text_red").style.display="none";
		}
	}
	else if(trimString(check_return_e) == ''){// empty input
		document.getElementById("text_red").style.display="none";
	}
	
	check_return_u = document.getElementById("ajax_response_u").innerHTML;
	if(check_return_u != 0){
		if(trimString(check_return_u) == '222'){// not validate username
			document.getElementById("text_red_u").style.display="block";
			valid_entry = false;
		}
		else if(trimString(check_return_u) == '333'){// validate username
			document.getElementById("text_red_u").style.display="none";
		}
	}
	else if(trimString(check_return_u) == ''){// empty input
		document.getElementById("text_red_u").style.display="none";
	}
}

function validateAuFormAjaxButton(){
	var form = document.adminForm;
	var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");
	var x = new RegExp("[\<|\>|\"]","i");
	var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
	var valid_entry = true;
	
	if(form.name.value == ""){
		alert("<?php echo JText::_("GURU_PROVIDE_USER_LOGIN_NAME"); ?>");
		valid_entry = false;
		return false;
	}
	
	if(document.getElementById('guru_username').value  == ""){
		alert("<?php echo JText::_("GURU_INSERT_TEACHER_USERNAME"); ?>");
		valid_entry = false;
		return false;
	}
	
	if(r.exec(form.username.value) || form.username.value.length <= 3){
		alert("<?php echo JText::_("GURU_INVALID_CHARACTERS_LOG_NAME"); ?>");
		valid_entry = false;
		return false;
	}
	
	if(document.getElementById('email').value == ""){
		alert("<?php echo JText::_("GURU_PROVIDE_EMAIL_ADDRESS"); ?>");
		valid_entry = false;
		return false;
	}
	
	if(!filter.test(form.email.value)){
		alert("<?php echo JText::_("GURU_PROVIDE_VALID_EMAIL"); ?>");
		valid_entry = false;
		return false;
	}
	
	if(form.website.value.substring(0, 7)!="http://" && form.website.value.substring(0, 8)!="https://"){
		alert("<?php echo JText::_("GURU_AU_WEBSITE"); ?>");
		valid_entry = false;
		return false;
	}
	
	if(form.blog.value.substring(0, 7)!="http://" && form.blog.value.substring(0, 8)!="https://"){
		alert("<?php echo JText::_("GURU_AU_BLOG"); ?>");
		valid_entry = false;
		return false;
	}
	
	if(form.facebook.value.substring(0, 7)!="http://" && form.facebook.value.substring(0, 8)!="https://"){
		alert("<?php echo JText::_("GURU_AU_FB"); ?>");
		valid_entry = false;
		return false;
	}
	
	if(form.password.value == ""){
		alert("<?php echo JText::_("GURU_INSERT_PASS"); ?>");
		valid_entry = false;
		return false;
	}
	
	if(form.password.value != form.password2.value){
		alert("<?php echo JText::_("DSCONFIRM_PASSWORD_MSG"); ?>");
		valid_entry = false;
		return false;
	}
	
	username = document.getElementById("guru_username").value;
	if(username != ""){
		htmlvalue = "0";
		var req = new Request.HTML({
			async: false,
			method: 'get',
			url: '<?php echo JURI::root()."components/com_guru/js/ajax.php?task=checkExistingUserU";?>&username='+username+'&email='+email+'&id=<?php echo @$id; ?>',
			data: { 'do' : '1' },
			onComplete: function(response){
				document.getElementById("ajax_response_u").empty().adopt(response);
			}
		}).send();
	}
	check_return_u = document.getElementById("ajax_response_u").innerHTML;
	
	email = document.getElementById("email").value;
	if(email != ""){
		htmlvalue = "0";
		var req = new Request.HTML({
			async: false,
			method: 'get',
			url: '<?php echo JURI::root()."components/com_guru/js/ajax.php?task=checkExistingUserE";?>&username='+username+'&email='+email+'&id=<?php echo @$id; ?>',
			data: { 'do' : '1' },
			onComplete: function(response){
				document.getElementById("ajax_response_e").empty().adopt(response);
			}
		}).send();
	}
	check_return_e = document.getElementById("ajax_response_e").innerHTML;
	
	if(check_return_e != 0){
		if(trimString(check_return_e) == '111'){// not validate email
			document.getElementById("text_red").style.display="block";
			document.getElementById("text_redv").style.display="none";
			alert("<?php echo JText::_("GURU_EMAIL_IN_USE"); ?>");
			valid_entry = false;
			return false;
		}
		else if(trimString(check_return_e) == '222'){// validate email
			document.getElementById("text_red").style.display="none";
		}
	}
	else if(trimString(check_return_e) == ''){// empty input
		document.getElementById("text_red").style.display="none";
	}
	
	check_return_u = document.getElementById("ajax_response_u").innerHTML;
	if(check_return_u != 0){
		if(trimString(check_return_u) == '222'){// not validate username
			document.getElementById("text_red_u").style.display="block";
			alert("<?php echo JText::_("GURU_USERNAME_IN_USE"); ?>");
			valid_entry = false;
			return false;
		}
		else if(trimString(check_return_u) == '333'){// validate username
			document.getElementById("text_red_u").style.display="none";
		}
	}
	else if(trimString(check_return_u) == ''){// empty input
		document.getElementById("text_red_u").style.display="none";
	}
	
	if(!validateTerms()){
		return false;
	}
	
	if(valid_entry){
		valid_entry = checkAllFields();
		if(valid_entry) {
			document.adminForm.task.value ='saveAuthor';
			document.adminForm.submit();
		}
	}
}

function checkAllFields(){
	var form = document.adminForm;
	var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");
	var x = new RegExp("[\<|\>|\"]","i");
	var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
	var valid_entry = true;
	
	// validate name --------------------------------------------------------------
	if(form.name.value == ""){
		return false;
	}
	
	if(x.exec(form.name.value)){
		return false;
	}
	
	// validate username --------------------------------------------------------------
	if(document.getElementById('guru_username').value  == ""){
		return false;
	}
	
	if(r.exec(form.username.value) || form.username.value.length < 3){
		return false;
	}
	
	
	// validate email --------------------------------------------------------------
	if(document.getElementById('email').value == ""){
		return false;
	}
	
	if(!filter.test(form.email.value)){
		return false;
	}
	
	// validate website --------------------------------------------------------------
	if(form.website.value.substring(0, 7)!="http://" && form.website.value.substring(0, 8)!="https://"){
		return false;
	}
	
	
	// validate blog --------------------------------------------------------------
	if(form.blog.value.substring(0, 7)!="http://" && form.blog.value.substring(0, 8)!="https://"){
		return false;
	}
	
	
	// validate facebook --------------------------------------------------------------
	if(form.facebook.value.substring(0, 7)!="http://" && form.facebook.value.substring(0, 8)!="https://"){
		return false;
	}
	
	
	// validate password --------------------------------------------------------------
	if(form.password.value != "" && form.password2.value != "" && form.password.value != form.password2.value){
		return false;
	}
	
	
	if(form.password.value == ""){
		return false;
	}
	
	
	// validate password2 --------------------------------------------------------------
	if(form.password.value != form.password2.value){
		return false;
	}
	
	return true;
}


function trimString(str){
str = str.toString();
var begin = 0;
var end = str.length - 1;
while (begin <= end && str.charCodeAt(begin) < 33) { ++begin; }
while (end > begin && str.charCodeAt(end) < 33) { --end; }
return str.substr(begin, end - begin + 1);
}

function deleteImage(){
	document.getElementById("view_imagelist23").src = "components/com_guru/images/blank.png";
	document.getElementById("images").value = "";
}
function setModertorg(){
	document.getElementById('forummoderator1').style.display = 'none';
	document.getElementById('forummoderator2').style.display = 'table-row';
}

function validateTerms(){
	<?php
		if($terms_cond_teacher == 1){
	?>
			terms_cond_teacher = document.getElementById("terms_cond_teacher");
			if(terms_cond_teacher.checked == false){
				alert("<?php echo JText::_("GURU_SELECT_TERMS_AND_COND"); ?>");
				return false;
			}
	<?php
		}
	?>
	return true;
}
	
</script>
<?php
	$Itemid = JRequest::getVar('Itemid', 0);
	$user = $this->user;
	$lists = $user->lists;
	
	$config = $this->config;

	$max_upload = (int)(ini_get('upload_max_filesize'));
	$max_post = (int)(ini_get('post_max_size'));
	$memory_limit = (int)(ini_get('memory_limit'));
	$upload_mb = min($max_upload, $max_post, $memory_limit);
	if($upload_mb == 0){
		$upload_mb = 10;
	}
	$upload_mb *= 1048576; //transform in bytes
	$doc = JFactory::getDocument();
	$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');
	
	$config_author = json_decode($config->authorpage);
	$author_t_prop = $config_author->author_image_size_type == "0" ? "width" : "heigth";

	$doc->addScriptDeclaration('
		jQuery.noConflict();
		jQuery(function(){
			function createUploader(){
				var uploader = new qq.FileUploader({
					element: document.getElementById(\'fileUploader\'),
					action: \''.JURI::root().'components/com_guru/helpers/fileuploader.php\',
					params:{
						folder:\'authors\',
						mediaType:\'image\',
						size: '.$config_author->author_image_size.',
						type: \''.$author_t_prop.'\'
					},
					onSubmit: function(id,fileName){
						jQuery(\'.qq-upload-list li\').css(\'display\',\'none\');
					},
					onComplete: function(id,fileName,responseJSON){
						//alert(\'id: \'+ id + \'; filename:\' + fileName);
						if(responseJSON.success == true){						
							jQuery(\'.qq-upload-success\').append(\'- <span style="color:#387C44;">Upload successful</span>\');
							if(responseJSON.locate) {
								jQuery(\'#view_imagelist23\').attr("src", \''.JURI::root().'\'+responseJSON.locate +"/"+ fileName+"?timestamp=" + new Date().getTime());
								jQuery(\'#images\').val("/"+responseJSON.locate +"/"+ fileName);
							}
						}
					},
					allowedExtensions: [\'jpg\', \'jpeg\', \'png\', \'gif\', \'JPG\', \'JPEG\', \'PNG\', \'GIF\'],
					sizeLimit: '.$upload_mb.',
					multiple: false,
					maxConnections: 1
				});           
			}
			createUploader();
		});
	');
	$doc->addScript('components/com_guru/js/fileuploader.js');
	$doc->addStyleSheet('components/com_guru/css/fileuploader.css');
	$doc->setTitle(trim(JText::_('GURU_AUTHOR'))." ".trim(JText::_('GURU_AUTHOR_PROFILE')));
	
	$db = JFactory::getDBO();

	$div_menu = $this->authorGuruMenuBar();
?>
<div class="g_row">
    <div class="g_cell span12">
        <div>
            <div>
                <div id="g_registrationformauthor" class="clearfix com-cont-wrap">
                    <?php
                        if($user->id > 0){
                            echo $div_menu; //MENU TOP OF AUTHORS
                        }
                    ?>
                    
                    <form class="form-horizontal" id="adminForm" method="post" name="adminForm" enctype="multipart/form-data" onsubmit="return validateTerms()">
  
                <?php
                    if($deviceType != "phone"){
                ?>
                    <div class="profile_page page_title">
                        <h2><?php echo JText::_('GURU_PROFILE_SETTINGS');?></h2>
                    </div>
                    <div id="g_registrationformauthorcontent" class="g_sect clearfix">
                        <div class="authorreg_row_guru clearfix ">
                            <div class="authorreg_row_guru g_cell span12">
                                 <ul class="nav nav-tabs" id="guru-tab-header">
                                        <li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('GURU_GENERAL'); ?></a></li>
                                        <li><a href="#photo" data-toggle="tab"><?php echo JText::_('GURU_PHOTO_TAB'); ?></a></li>
                                        <li><a href="#bio" data-toggle="tab"><?php echo JText::_('GURU_BIO'); ?></a></li>
                                 </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="general">
                                        <div class="control-group">
                                            <label class="control-label" for="name"><?php echo JText::_("GURU_AU_AUTHOR_NAME");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                            <div class="controls">
                                                <span>
                                                    <input onkeyup="validateAuForm('name');" type="text" id="name" name="name" value="<?php echo $user->name; ?>" />
                                                </span>    
                                            </div>
                                            <div class="controls">
                                            	<div class="pull-left alert alert-warning" style="display:none;" id="text_red_n"><?php echo JText::_("GURU_PROVIDE_NAME"); ?></div>
                                                <div class="pull-left alert alert-warning" style="display:none;" id="text_red_ni"><?php echo JText::_("GURU_INVALID_CHARACTERS_LOG_NAME"); ?></div>
                                            </div>  
                                        </div>
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="username"><?php echo JText::_("GURU_AU_AUTHOR_USERNAME");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                            <div class="controls">
                                                <span>
                                                    <input onchange="validateAuFormAjax('username');" type="text" id="guru_username" name="username" <?php if(isset($user->username) && (trim($user->username != ""))){echo 'disabled="disabled"';} ?> value="<?php if(isset($user->username)){echo $user->username;}?>" />
                                                </span>    
                                            </div>
                                            <div class="controls">
                                            	<div class="pull-left alert alert-warning" style="display:none;" id="text_red_u"><?php echo JText::_("GURU_USERNAME_IN_USE"); ?></div>
                                                <div class="pull-left alert alert-warning" style="display:none;" id="text_red_ul"><?php echo JText::_("GURU_PROVIDE_USER_LOGIN_NAME"); ?></div>
                                                <div class="pull-left alert alert-warning" style="display:none;" id="text_red_uc"><?php echo JText::_("GURU_INVALID_CHARACTERS_LOG_NAME"); ?></div>
                                                
                                            </div>    
                                        </div>
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="author_title"><?php echo JText::_("GURU_AU_AUTHOR_TITLE");?>:&nbsp;&nbsp;</label>
                                            <div class="controls">
                                                <span>
                                                    <input onkeyup="validateAuForm('author_title');" type="text" id="author_title" name="author_title" value="<?php echo $user->author_title; ?>" />
                                                </span>    
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="author_email"><?php echo JText::_("GURU_AU_AUTHOR_EMAIL");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                            <div class="controls">
                                                <span>
                                                    <input onchange="validateAuFormAjax('email');" type="text" id="email" name="email" <?php if(trim($user->email != "")){echo 'disabled="disabled"';} ?> value="<?php echo $user->email; ?>" />
                                                </span>    
                                            </div>
                                            <div class="controls">
                                            	<div class="pull-left alert alert-warning" style="display:none;" id="text_red"><?php echo JText::_("GURU_EMAIL_IN_USE"); ?></div>
                                                <div class="pull-left alert alert-warning" style="display:none;" id="text_rede"><?php echo JText::_("GURU_PROVIDE_EMAIL_ADDRESS"); ?></div>
                                                <div class="pull-left alert alert-warning" style="display:none;" id="text_redv"><?php echo JText::_("GURU_PROVIDE_VALID_EMAIL"); ?></div>
                                            </div>    
                                        </div>
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="author_website"><?php echo JText::_("GURU_AU_AUTHOR_WEBSITE");?>:&nbsp;&nbsp;</label>
                                            <div class="controls">
                                                <span>
                                                    <input onkeyup="validateAuForm('website');" type="text" class="pull-left" name="website" id="website" value="<?php echo $user->website; ?>" />
                                                    <?php echo $lists['show_website']; ?>
                                                </span>    
                                            </div>
                                            <div class="controls">
                                            	<div class="pull-left alert alert-warning" style="display:none;" id="text_red_wb"><?php echo JText::_("GURU_AU_WEBSITE"); ?></div>
                                            </div>
                                        </div>
                                        
                                         <div class="control-group ">
                                            <label class="control-label" for="author_blog"><?php echo JText::_("GURU_AU_AUTHOR_BLOG");?>:&nbsp;&nbsp;</label>
                                            <div class="controls">
                                                <span>
                                                    <input onkeyup="validateAuForm('blog');" type="text" class="pull-left" name="blog" id="blog" value="<?php echo $user->blog; ?>" />
                                                    <?php echo $lists['show_blog']; ?>
                                                </span>    
                                            </div>
                                            <div class="controls">
                                            	<div class="pull-left alert alert-warning" style="display:none;" id="text_red_blog"><?php echo JText::_("GURU_AU_BLOG"); ?></div>
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="author_facebook"><?php echo JText::_("GURU_AU_AUTHOR_FACEBOOK");?>:&nbsp;&nbsp;</label>
                                            <div class="controls">
                                                <span>
                                                    <input onkeyup="validateAuForm('facebook');" type="text" class="pull-left" name="facebook" id="facebook" value="<?php echo $user->facebook; ?>" />
                                                    <?php echo $lists['show_facebook']; ?>
                                                </span>    
                                            </div>
                                            <div class="controls">
                                            	<div class="pull-left alert alert-warning" style="display:none;" id="text_red_fb"><?php echo JText::_("GURU_AU_FB"); ?></div>
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label class="control-label" for="author_twitter"><?php echo JText::_("GURU_AU_AUTHOR_TWITTER");?>:&nbsp;&nbsp;</label>
                                            <div class="controls">
                                                <span>
                                                    <input onkeyup="validateAuForm('twitter');" type="text" class="pull-left" name="twitter" id="twitter" value="<?php echo $user->twitter; ?>" />
                                                    <?php echo $lists['show_twitter']; ?>
                                                </span>    
                                            </div>
                                        </div>
                                        
                                        <?php
                                        	if($user->id > 0){
                          				?>
                                        		<div class="control-group">
                                                    <div class="controls">
                                                        <input onclick="document.adminForm.task.value ='authorprofile'; document.adminForm.submit();" type="button" class="btn btn-success btn_renew" value="<?php echo JText::_("GURU_SAVE_PROFILE")?>" />
                                                    </div>
                                                </div>
                            			<?php
                                			}
										?>
                                        
                                         <?php if(intval($user->id) == 0){?>
                                                <div class="control-group clearfix">
                                                    <label class="control-label" for="password"><?php echo JText::_("GURU_AU_AUTHOR_PASSWORD");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                                    <div class="controls">
                                                        <span>
                                                            <input id="password" onchange="validateAuForm('password');" type="password" name="password" size="40" value="" autocomplete="off" class="inputbox" size="40" />
                                                        </span>    
                                                    </div>
                                                    <div class="controls">
                                                        <div class="pull-left alert alert-warning" style="display:none;" id="text_red_pass1"><?php echo JText::_("DSCONFIRM_PASSWORD_MSG"); ?></div>
                                                        <div class="pull-left alert alert-warning" style="display:none;" id="text_red_pass2"><?php echo JText::_("GURU_INSERT_PASS"); ?></div>
                                                    </div>
                                                </div>
                                                 <div class="control-group clearfix">
                                                    <label class="control-label" for="password2"><?php echo JText::_("GURU_AU_AUTHOR_VERIFY_PASSWORD");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                                    <div class="controls">
                                                        <span>
                                                            <input id="password2" onchange="validateAuForm('password2');" type="password" name="password2" size="40" value="" autocomplete="off" class="inputbox" size="40" />					
                                                        </span>    
                                                    </div>
                                                    <div class="controls">
                                                        <div class="pull-left alert alert-warning" style="display:none;" id="text_red_pass3"><?php echo JText::_("DSCONFIRM_PASSWORD_MSG"); ?></div>
                                                        <div class="pull-left alert alert-warning" style="display:none;" id="text_red_pass4"><?php echo JText::_("GURU_INSERT_PASS"); ?></div>
                                                    </div>
                                                </div>
                                                
                                                <?php
													if($terms_cond_teacher == 1){
												?>
														<div class="control-group g_row">
															<label class="control-label g_cell span3" for="firstname"></label>
															<div class="controls g_cell span5">
																<input type="checkbox" value="1" name="terms_cond_teacher" id="terms_cond_teacher" />
																<span class="lbl"></span>
																<a href="#" onclick='window.open("<?php echo JURI::root()."index.php?option=com_guru&controller=guruAuthor&task=terms&tmpl=component"; ?>", "", "width=500, height=400")'><?php echo JText::_("GURU_TERMS_AND_COND"); ?></a>
															</div>
														</div>
												<?php
													}
												?>
                                        <?php }?>
                                    </div>
                                    <div class="tab-pane" id="photo">
                                         <div class="control-group clearfix">
                                            <label class="g_cell span2 g_margin_top" for="author_group"><?php echo JText::_("GURU_UPLOAD_IMAGE");?>:</label>
                                            <div class="controls g_cell span5">
                                                <span>
                                                   <div id="fileUploader"></div>
                                                   <input type="hidden" name="images" id="images" value="<?php echo $user->images; ?>" />
                                                </span>    
                                            </div>
                                        </div>
                                         <?php
                                            if(isset($user->images) && $user->images!=""){ 	?>
                                                <div class="control-group clearfix">
                                                    <label class="g_cell span2" for="author_image"><?php echo JText::_("GURU_SEL_IMAGE");?>:</label>
                                                    <div class="controls g_cell span5">
                                                        <span>
                                                          <div id='authorImageSelected'>
                                                            <img id="view_imagelist23" name="view_imagelist" src='<?php echo JURI::root().$user->images;?>'/><br />
                                                            </div>
                                                            <br />
                                                            <input type="button" class="btn btn-warning tree-button" value="<?php echo JText::_('GURU_REMOVE'); ?>" onclick="return deleteImage();"/>
                                                            <input type="hidden" value="<?php echo $user->images; ?>" name="img_name" id="img_name" />
                                                        </span>    
                                                    </div>
                                                </div>
                                        <?php 
                                            } else {
                                                echo "<div id='authorImageSelected'><img id='view_imagelist23' name='view_imagelist' src='".JURI::root()."components/com_guru/images/blank.png'/></div>";
                                            }
                                        ?>
                                        
                                        <div class="control-group clearfix">
                                            <label class="g_cell span2" for="author_image"></label>
                                            <div class="controls g_cell span5">
                                                <?php
													if($user->id > 0){
												?>
														<input onclick="document.adminForm.task.value ='authorprofile'; document.adminForm.submit();" type="button" class="btn btn-success btn_renew" value="<?php echo JText::_("GURU_SAVE_PROFILE")?>" />
												<?php
													}
												?>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="tab-pane" id="bio">
                                         <?php
                                        ?>
                                        <div class="control-group">
                                            <div class="controls g_cell span12">
                                                <span>
                                                    <?php
														$doc = JFactory::getDocument();
														$doc->addScript(JURI::root().'components/com_guru/js/redactor.min.js');
														$doc->addStyleSheet(JURI::root().'components/com_guru/css/redactor.css');
														// parameters : areaname, content, hidden field, width, height, rows, cols'full_bio',
                                                    	//echo $editor1->display('full_bio', $user->full_bio, '100%', '100px', '20', '60');
													?>
                                                    <textarea id="full_bio" name="full_bio" class="useredactor" style="width:100%; height:400px;"><?php echo $user->full_bio; ?></textarea>
                                                    <?php
														if($user->id > 0){
													?>
															<input onclick="document.adminForm.task.value ='authorprofile'; document.adminForm.submit();" type="button" class="btn btn-success btn_renew" value="<?php echo JText::_("GURU_SAVE_PROFILE")?>" />
													<?php
														}
													?>
                                                    
                                                    <?php
														$upload_script = 'window.addEvent( "domready", function(){
																			jQuery(".useredactor").redactor({
																				 buttons: [\'bold\', \'italic\', \'underline\', \'link\', \'alignment\', \'unorderedlist\', \'orderedlist\']
																			});
																			jQuery(".redactor_useredactor").css("height","400px");
																		  });';
                                                    	$doc->addScriptDeclaration($upload_script);
													?>
                                                </span>    
                                            </div>
                                        </div>
                                    </div> 
                               </div> 
                            </div>        
                        </div>
                       </div>
                       <?php
                       }
                       else{
                       ?>
                       <!-- start mobile version -->
                        <div id="g_registrationformauthorcontent_mobile" class="g_mobile">
                            <div class="container-fluid">
                                <div id="accordion" class="accordion">
                                    <div class="accordionItem">
                                        <h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_GENERAL");?></h3>
                                        <div class="clearfix tab-body  g_accordion-group g_content_active">
                                           <div class="control-group clearfix">
                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_AU_AUTHOR_NAME");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                            <div class="controls g_cell span5">
                                                <span>
                                                    <input type="text" class="inputbox" size="30" id="name" name="name" value="<?php echo $user->name; ?>" />
                                                </span>    
                                            </div>
                                            </div>
                                            <div class="control-group clearfix">
                                                <label class="control-label g_cell span3" for="username"><?php echo JText::_("GURU_AU_AUTHOR_USERNAME");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                                <div class="controls g_cell span5">
                                                    <span>
                                                        <input type="text" class="inputbox" size="30" id="guru_username" name="username" <?php if(isset($user->username) && (trim($user->username != ""))){echo 'disabled="disabled"';} ?> value="<?php if(isset($user->username)){echo $user->username;}?>" />
                                                    </span>    
                                                </div>
                                            </div>
                                            <div class="control-group clearfix">
                                                <label class="control-label g_cell span3" for="author_title"><?php echo JText::_("GURU_AU_AUTHOR_TITLE");?>:&nbsp;&nbsp;</label>
                                                <div class="controls g_cell span5">
                                                    <span>
                                                        <input type="text" class="inputbox" size="30" id="author_title" name="author_title" value="<?php echo $user->author_title; ?>" />
                                                    </span>    
                                                </div>
                                            </div>
                                            <div class="control-group clearfix">
                                                <label class="control-label g_cell span3" for="author_email"><?php echo JText::_("GURU_AU_AUTHOR_EMAIL");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                                <div class="controls g_cell span5">
                                                    <span>
                                                        <input type="text" class="inputbox" size="30" id="email" name="email" <?php if(trim($user->email != "")){echo 'disabled="disabled"';} ?> value="<?php echo $user->email; ?>" />
                                                    </span>    
                                                </div>
                                            </div>
                                              <div class="control-group clearfix">
                                                <label class="control-label g_cell span3" for="author_website"><?php echo JText::_("GURU_AU_AUTHOR_WEBSITE");?>:&nbsp;&nbsp;</label>
                                                <div class="controls g_cell span5">
                                                    <span>
                                                        <input type="text" class="inputbox" size="30"  name="website" id="website" value="<?php echo $user->website; ?>" />
                                                    </span>    
                                                </div>
                                                 <div class="controls g_cell span4">
                                                    <span>
                                                         <?php echo $lists['show_website']; ?>
                                                    </span>    
                                                </div>
                                            </div>
                                             <div class="control-group clearfix">
                                                <label class="control-label g_cell span3" for="author_blog"><?php echo JText::_("GURU_AU_AUTHOR_BLOG");?>:&nbsp;&nbsp;</label>
                                                <div class="controls g_cell span5">
                                                    <span>
                                                        <input type="text" class="inputbox" size="30"  name="blog" id="blog" value="<?php echo $user->blog; ?>" />
                                                    </span>    
                                                </div>
                                                 <div class="controls g_cell span4">
                                                    <span>
                                                         <?php echo $lists['show_blog']; ?>
                                                    </span>    
                                                </div>
                                            </div>
                                            <div class="control-group clearfix">
                                                <label class="control-label g_cell span3" for="author_facebook"><?php echo JText::_("GURU_AU_AUTHOR_FACEBOOK");?>:&nbsp;&nbsp;</label>
                                                <div class="controls g_cell span5">
                                                    <span>
                                                        <input type="text" class="inputbox" size="30"  name="facebook" id="facebook" value="<?php echo $user->facebook; ?>" />
                                                    </span>    
                                                </div>
                                                 <div class="controls g_cell span4">
                                                    <span>
                                                         <?php echo $lists['show_facebook']; ?>
                                                    </span>    
                                                </div>
                                            </div>
                                            <div class="control-group clearfix">
                                                <label class="control-label g_cell span3" for="author_twitter"><?php echo JText::_("GURU_AU_AUTHOR_TWITTER");?>:&nbsp;&nbsp;</label>
                                                <div class="controls g_cell span5">
                                                    <span>
                                                        <input type="text" class="inputbox" size="30"  name="twitter" id="twitter" value="<?php echo $user->twitter; ?>" />
                                                    </span>    
                                                </div>
                                                 <div class="controls g_cell span4">
                                                    <span>
                                                         <?php echo $lists['show_twitter']; ?>
                                                    </span>    
                                                </div>
                                            </div>
                                             <?php if(intval($user->id) == 0){?>
                                                    <div class="control-group clearfix">
                                                        <label class="control-label" for="password"><?php echo JText::_("GURU_AU_AUTHOR_PASSWORD");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                                        <div class="controls">
                                                            <span>
                                                                <input id="password" type="password" name="password" size="40" value="" autocomplete="off" class="inputbox" size="40" />
                                                            </span>    
                                                        </div>
                                                    </div>
                                                     <div class="control-group clearfix">
                                                        <label class="control-label" for="password2"><?php echo JText::_("GURU_AU_AUTHOR_VERIFY_PASSWORD");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                                        <div class="controls">
                                                            <span>
                                                                <input id="password2" type="password" name="password2" size="40" value="" autocomplete="off" class="inputbox" size="40" />					
                                                            </span>    
                                                        </div>
                                                    </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="accordionItem">
                                        <h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_AUTHOR_PHOTO");?></h3>
                                        <div class="clearfix tab-body  g_accordion-group g_content_active">
                                            <div class="control-group clearfix">
                                            <label class="control-label g_cell span3" for="author_group"><?php echo JText::_("GURU_UPLOAD_IMAGE");?>:</label>
                                            <div class="controls g_cell span5">
                                                <span>
                                                   <div id="fileUploader"></div>
                                                   <input type="hidden" name="images" id="images" value="<?php echo $user->images; ?>" />
                                                </span>    
                                            </div>
                                            </div>
                                             <?php
                                                if(isset($user->images) && $user->images!=""){ 	?>
                                                    <div class="control-group clearfix">
                                                        <label class="control-label g_cell span3" for="author_image"><?php echo JText::_("GURU_SEL_IMAGE");?>:</label>
                                                        <div class="controls g_cell span5">
                                                            <span>
                                                              <div id='authorImageSelected'>
                                                                <img id="view_imagelist23" name="view_imagelist" src='<?php echo JURI::root().$user->images;?>'/><br />
                                                                </div>
                                                                <br />
                                                                <input type="button" class="btn btn-warning" value="<?php echo JText::_('GURU_REMOVE'); ?>" onclick="return deleteImage();"/>
                                                                <input type="hidden" value="<?php echo $user->images; ?>" name="img_name" id="img_name" />
                                                            </span>    
                                                        </div>
                                                    </div>
                                            <?php 
                                                } else {
                                                    echo "<div id='authorImageSelected'><img id='view_imagelist23' name='view_imagelist' src='".JURI::root()."components/com_guru/images/blank.png'/></div>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                        
                                    <div class="accordionItem">
                                        <h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_BIO");?></h3>
                                        <div class="clearfix tab-body  g_accordion-group g_content_active">
                                             <?php
                                            ?>
                                            <div class="control-group">
                                                <div class="controls g_cell span12">
                                                    <span>
                                                        <?php
                                                        // parameters : areaname, content, hidden field, width, height, rows, cols'full_bio',
                                                        //echo $editor1->display( 'full_bio',  $user->full_bio ,  '100%', '100px', '20', '60' ) ;
														?>
                                                        <textarea id="full_bio" name="full_bio" class="useredactor" style="width:100%;"><?php echo $user->full_bio; ?></textarea>
                                                    <?php
														$upload_script = 'window.addEvent( "domready", function(){
																			jQuery(".useredactor").redactor({
																				 buttons: [\'bold\', \'italic\', \'underline\', \'link\', \'alignment\', \'unorderedlist\', \'orderedlist\']
																			});
																			jQuery(".redactor_useredactor").css("height","400px");
																		  });';
                                                    	$doc->addScriptDeclaration($upload_script);
													?>
                                                    </span>    
                                                </div>
                                             </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- end mobile version -->
                       <?php }?>
                        <div id="ajax_response_u" style="display:none;"></div>  
                    	<div id="ajax_response_e" style="display:none;"></div>   
                        <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
                        <input type="hidden" name="option" value="com_guru" />
                        <input type="hidden" name="auid" value="<?php echo $user->userid; ?>" />
                         <input type="hidden" name="id" value="" />
                        <input type="hidden" name="task" value="saveAuthor" />
                        <input type="hidden" name="returnpage" value="<?php echo (JRequest::getVar("returnpage", "", 'request'));?>" />		
                        <input type="hidden" name="controller" value="guruLogin" />
                        <input type="hidden" name="guru_teacher" value="2">
                        
                        <?php
                        	if($user->id <= 0){
						?>
                        		<a href="#" class="cancel-link" onclick="history.go(-1);"><?php echo JText::_("GURU_CANCEL"); ?></a>
                        <?php
                        	}
						?>
                        
                        <!--Save BUTTON -->
                        <div class="g_inline_child clearfix">
                          <?php if($user->id <= 0){ ?>
                                    <input id="guru_create_account" type="button" onclick="validateAuFormAjaxButton('name');" class="btn btn-primary" value="<?php echo JText::_("GURU_CREATE_ACCOUNT")?>" />  
        
                          <?php 
                                }
                           ?>    
                        </div> 
                        <!-- -->
                        
                    </form> 
                </div>
            </div>
        </div>
    </div>
    </div>        