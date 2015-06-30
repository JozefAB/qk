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
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.html.pagination' );
JHTML::_('behavior.tooltip');
JHTML::_('behavior.framework');
JHtml::_('behavior.calendar');

$doc =JFactory::getDocument();
$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');	
require_once (JPATH_COMPONENT_ADMINISTRATOR.DS."models".DS."gurucertificate.php");

$program = $this->program;
if(isset($program->id) && $program->id != NULL ){
	$chb_free_courses = $this->selectedCoursesforFree();
	$step_access_courses = $this->getStepAccessCourses();
	$selected_course = $this->getSelectedCourse();
}
$lists = $program->lists;
$editorul  = JFactory::getEditor(); 

$certificates_details = guruAdminModelguruCertificate::getCertificatesDetails();
$mmediam = $this->mmediam;

if(isset($this->mmediam_preq)){
	foreach($this->mmediam_preq as $element){
		$vect[] = $element->id;
	}
}
$config = guruAdminModelguruProgram::getConfigs();
$config_courses = json_decode($config->psgspage);
$courses_t_prop = $config_courses->courses_image_size_type == "0" ? "width" : "heigth";

$list_authors = $this->listAuthors();

$dateformat = $this->gurudateformat;

$listDirn = "asc";
$listOrder = "ordering";
$saveOrderingUrl = 'index.php?option=com_guru&controller=guruPrograms&task=saveOrderExercices&tmpl=component';
JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');

$max_upload = (int)(ini_get('upload_max_filesize'));
$max_post = (int)(ini_get('post_max_size'));
$memory_limit = (int)(ini_get('memory_limit'));
$upload_mb = min($max_upload, $max_post, $memory_limit);
if($upload_mb == 0) {$upload_mb = 10;}
$upload_mb*=1048576; //transform in bytes
$doc =JFactory::getDocument();
$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');

$cid = JRequest::getVar("cid", array(), "get");
$id = intval(@$cid["0"]);

$doc->addScriptDeclaration('
	jQuery.noConflict();
	jQuery(function(){
		function createUploader(){            
			var uploader = new qq.FileUploader({
				element: document.getElementById(\'fileUploader\'),
				action: \'components/com_guru/helpers/fileuploader.php\',
				params:{
					folder:\'courses\',
					mediaType:\'image\',
					size: '.$config_courses->courses_image_size.',
					type: \''.$courses_t_prop.'\'
				},
				onSubmit: function(id,fileName){
					jQuery(\'.qq-upload-list li\').css(\'display\',\'none\');
				},
				onComplete: function(id,fileName,responseJSON){
					if(responseJSON.success == true){
						jQuery(\'.qq-upload-success\').append(\'- <span style="color:#387C44;">Upload successful</span>\');
						if(responseJSON.locate) {
							//jQuery(\'#view_imagelist23\').attr("src", "../"+responseJSON.locate +"/"+ fileName);
							jQuery(\'#view_imagelist23\').attr("src", "../"+responseJSON.locate +"/"+ fileName+"?timestamp=" + new Date().getTime());
							jQuery(\'#image\').val(responseJSON.locate +"/"+ fileName);
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
	jQuery(document).ready(function($) {
		document.getElementById("free_courses").style.display = '.(@$step_access_courses == 0 ? '"block"' : '"none"').';
	});
	
');

$doc->addScriptDeclaration('
	jQuery.noConflict();
	jQuery(function(){
		function createUploader1(){            
			var uploader = new qq.FileUploader({
				element: document.getElementById(\'fileUploader1\'),
				action: \'components/com_guru/helpers/fileuploader.php\',
				params:{
					folder:\'courses\',
					mediaType:\'image\',
					size: '.$config_courses->courses_image_size.',
					type: \''.$courses_t_prop.'\'
				},
				onSubmit: function(id,fileName){
					jQuery(\'.qq-upload-list li\').css(\'display\',\'none\');
				},
				onComplete: function(id,fileName,responseJSON){
					if(responseJSON.success == true){
						jQuery(\'.qq-upload-success\').append(\'- <span style="color:#387C44;">Upload successful</span>\');
						if(responseJSON.locate) {
							jQuery(\'#view_imagelist24\').attr("src", "../"+responseJSON.locate +"/"+ fileName+"?timestamp=" + new Date().getTime());
							jQuery(\'#image_avatar\').val(responseJSON.locate +"/"+ fileName);
						}
					}
				},
				allowedExtensions: [\'jpg\', \'jpeg\', \'png\', \'gif\', \'JPG\', \'JPEG\', \'PNG\', \'GIF\'],
				sizeLimit: '.$upload_mb.',
				multiple: false,
				maxConnections: 1
			});           
		}
		createUploader1();
	});
	jQuery(document).ready(function($) {
		document.getElementById("free_courses").style.display = '.(@$step_access_courses == 0 ? '"block"' : '"none"').';
	});
	
');
$doc->addScript('components/com_guru/js/fileuploader.js');
$doc->addScript('components/com_guru/js/freecourse.js');
$doc->addStyleSheet('components/com_guru/css/fileuploader.css');
?>
<style>
	#rowsmedia {
		background-color:#eeeeee;
	}
	#rowsmedia tr{
		background-color:white;
	}
	#rowsmainmedia {
		background-color:#eeeeee;
	}
	#rowsmainmedia tr{
		background-color:#eeeeee;
	}
</style>
<script language="JavaScript" type="text/javascript" src="<?php echo JURI::base(); ?>components/com_guru/js/freecourse.js"></script>
<script language="javascript" type="text/javascript">
	function checkIfCertificateIsSet(){
		chb_free_courses = document.getElementById("chb_free_courses").checked;
		if(chb_free_courses == true){
			if(document.getElementById("step_access_courses").value == 2){// free for guest
				if(document.getElementById("certificate_setts").value != 1){ // not selected "no certificate"
					document.getElementById("certificate_setts").value = 1;
					alert("<?php echo JText::_("COM_GURU_NO_CERTIFICATE_2"); ?>");
					return true;
				}
			}
		}
	}
	
	function ChangeTermCourse(nb, id){
		chb_free_courses = document.getElementById("chb_free_courses").checked;
		if(chb_free_courses == true){
			if(document.getElementById("step_access_courses").value == 2){// free for guest
				if(nb != 1){ // select certificate
					document.getElementById("certificate_setts").value = 1;
					alert("<?php echo JText::_("COM_GURU_NO_CERTIFICATE_1"); ?>");
					return false;
				}
			}
		}
		
		if(nb == 4 || nb == 6 ){
			document.getElementById('avg_certificate').style.display = 'table-row';
			
			jQuery.ajax({
  				 url: 'index.php?option=com_guru&controller=guruPrograms&task=savenbquizzes&id='+id,
   			});
		}
		else{
			document.getElementById('avg_certificate').style.display = 'none';
	
		}
		if(nb == 1){
			document.getElementById('coursecertifiactemsg').style.display = 'none';
		}
		else{
			document.getElementById('coursecertifiactemsg').style.display = 'table-row';
		}
	}
		
	function IsNumeric(sText){
		var ValidChars = "0123456789.";
		var IsNumber=true;
		var Char;
		for (i = 0; i < sText.length && IsNumber == true; i++) { 
			Char = sText.charAt(i); 
			if (ValidChars.indexOf(Char) == -1)  { 
				IsNumber = false;
			}
		}
		return IsNumber;
	}
		
	function isFloat(nr){
		return parseFloat(nr.match(/^-?\d*(\.\d+)?$/)) > 0;
	}
		
	Joomla.submitbutton = function(pressbutton){
	//function submitbutton(pressbutton) {
		var form = document.adminForm;
		var prisfinalq = document.getElementById('final_quizzes').value;
		var hasquiz = <?php if(isset($program->hasquiz)){echo $program->hasquiz;} else{ echo 0;}?>;
		var certificateterm = document.getElementById('certificate_setts').value;
		var avgval = document.getElementById('avg_cert').value;
		
		var sDate = document.getElementById('startpublish').value;
		sDate = sDate.split(" ");
		sDate = sDate[0];
		
		var eDate = document.getElementById('endpublish').value;
		eDate = eDate.split(" ");
		eDate = eDate[0];
		
		sDate = new Date(sDate+"");
		eDate = new Date(eDate+"");
		
		sDate = sDate.getTime();
		eDate = eDate.getTime();
		
		if (pressbutton == 'save' || pressbutton == 'apply') {
			name = form["name"].value;
			alias = form["alias"].value;

			var req = new Request.HTML({
				async: false,
				method: 'get',
				url: 'components/com_guru/views/guruprograms/tmpl/ajax.php?action=check_values&name='+name+'&alias='+alias+'&id='+<?php echo $id; ?>,
				data: { 'do' : '1' },
				onComplete: function(response){
					if(response[0].textContent == "exist"){
						alert("<?php echo JText::_("GURU_ALERT_EXISTING_NAME_ALIAS"); ?>");
						return false;
					}
				}
			}).send();
			
			chb_free_courses = document.getElementById("chb_free_courses").checked;
			if(chb_free_courses == false){
				 // start if check if price is correct
				 k=0;
				 subscription_added = false;
				 while(eval(document.getElementById("subscription_price_"+k))){
				 	if(document.getElementById("subscriptions_"+k).checked == true){
						subscription_added = true;
						subscription_price = document.getElementById("subscription_price_"+k).value;
						if(subscription_price != ""){
							if(!isFloat(subscription_price) || subscription_price <= 0){
								alert("<?php echo JText::_("GURU_ALERT_INVALID_PRICE"); ?>");
								return false;
							}
						}
						else{
							alert("<?php echo JText::_("GURU_ALERT_INVALID_PRICE"); ?>");
							return false;
						}
					}
					k++;
				 }
				 
				 if(!subscription_added){
				 	alert("<?php echo JText::_("GURU_ALERT_ADD_PRICE"); ?>");
					return false;
				 }
				 
				 // chekc if default plan is selected
				subscriptions = document.getElementsByName("subscription_default");
				checked = false;
				for(i=0; i<subscriptions.length; i++){
					if(subscriptions[i].checked){
						checked = true;
						break;
					}
				}
				
				if(!checked){
					alert("<?php echo JText::_("GURU_ALERT_ADD_DEFAULT_PLAN"); ?>");
					return false;
				}
				 
				 k = 0;
				 while(eval(document.getElementById("renewal_price_"+k))){
				 	if(document.getElementById("renewals_"+k).checked == true){
						renewal_price = document.getElementById("renewal_price_"+k).value;
						if(renewal_price != ""){
							if(!isFloat(renewal_price) || renewal_price <= 0){
								alert("<?php echo JText::_("GURU_ALERT_INVALID_PRICE"); ?>");
								return false;
							}
						}
						else{
							alert("<?php echo JText::_("GURU_ALERT_INVALID_PRICE"); ?>");
							return false;
						}
					}
					k++;
				 }
				 // stop if check if price is correct
			 }
			 if((certificateterm == 3 || certificateterm == 5) && prisfinalq == 0 && 0 != <?php echo intval($program->id); ?>){
				alert("<?php echo JText::_("GURU_ALERT_CERT_FEX");?>");
				return false;
			 }
			 else if((certificateterm == 4 || certificateterm == 6) && hasquiz == 0 && 0 != <?php echo intval($program->id); ?>){
			 	alert("<?php echo JText::_("GURU_ALERT_CERT_QAVG");?>");
				return false;
			 }
			 else if((isNaN(avgval) || parseInt(avgval) < 0) && pressbutton != 'cancel' ){
			 	alert("<?php echo JText::_("GURU_INVALID_NUMBER_AVG");?>");
				return false;
			 }
			 else if (form['name'].value == "") {
					alert( "<?php echo JText::_("GURU_CS_PLSINSNAME");?>" );
			 }
			 else if (form['author'].value == "- select -") {
					alert( "<?php echo JText::_("GURU_CS_PLSINSAUTHOR");?>" );				
			 }
			 else if (form['catid'].value==0) {
				alert("<?php echo JText::_("GURU_PR_PLSINSCATEG");?>");				 
			 }
			else if(document.getElementById('startpublish').value == ''  ){
				alert("<?php echo JText::_("GURU_INVALID_DATE");?>");
				document.getElementById('startdate').value="";
				return false;
			}
			else if(document.getElementById('startpublish').value != '' && document.getElementById('endpublish').value != '' && sDate > eDate)
			{
				alert("<?php echo JText::_("GURU_DATE_GRATER");?>");
				return false;
			}	
			 else if (document.getElementById('chb_free_courses').checked == true){
			 	if (form['step_access_courses'].value == 0 && form['selected_course[]'].value == 0 )
				{
					alert("<?php echo JText::_("GURU_SELECTED_COURSE_ALERT");?>");		
				}
				else {submitform( pressbutton );}			 	
			 }
			 else { 
					if(pressbutton=='apply') {submitform('apply32');} else {submitform( pressbutton );}
				}
		}
		else{  
			if(pressbutton=='apply') {
				submitform('apply32');
			} 
			else {
				submitform( pressbutton );
			}
		}
	}

	function typevar( variable ){
		return( typeof( variable ) );
	}

	function delete_temp(i){
		document.getElementById('tr'+i).style.display = 'none';
		document.getElementById('mediafiletodel').value =  document.getElementById('mediafiletodel').value+','+i;
	}

	function delete_temp_preq(i){
		var liveadded = document.getElementById('liveadded').value;
		var temp = new Array();
		var ok = true;
		temp = liveadded.split('|');
		document.getElementById('tr'+i).style.display = 'none';
		document.getElementById('preqfiletodel').value =  document.getElementById('preqfiletodel').value+','+i;
		for(var j=0;j<=temp.length-1;j++){
			if (document.getElementById('tr'+temp[j]).style.display != 'none') {
				ok = false;
			}
		}
	<?php 
		if(isset($vect)){
			echo "var exist = new Array();";
			$count=0;
			foreach($vect as $element){
				echo "exist[".$count."]=".$element.";";
				$count++;
			}
		} 
		else {
			echo "var exist=0;";
		}
	 ?> 	
		if(typevar(exist)!='number'){
			for(var j=0;j<=exist.length-1;j++){
				if (document.getElementById('2tr'+exist[j]).style.display != 'none') {
					ok = false;
				}
			}
		}
		if(ok == true) {
			document.getElementById('rowspreq').style.display = 'none';
		}
	}

	function delete_temp2_preq(i){
		var liveadded = document.getElementById('liveadded').value;
		var temp = new Array();
		var ok = true;
		temp = liveadded.split('|'); 
		temp['test']='b';
		document.getElementById('2tr'+i).style.display = 'none';
		document.getElementById('preqfiletodel').value =  document.getElementById('preqfiletodel').value+','+i;
		if(temp.length>1){
			for(var j=0;j<=temp.length-1;j++){
				if (document.getElementById('tr'+temp[j]).style.display != 'none') {
					ok = false;
				}
			}
		}
		<?php 
		if(isset($vect)){
			echo "var exist = new Array();";
			$count=0;
			foreach($vect as $element){
				echo "exist[".$count."]=".$element.";";
				$count++;
			}
		} 
		else {
			echo "var exist=0;";
		}
		?>
		if(typevar(exist)!='number'){
			for(var j=0;j<=exist.length-1;j++){
				if (document.getElementById('2tr'+exist[j]).style.display != 'none') {
					ok = false;
				}
			}
		}
		if(ok == true) {
			document.getElementById('rowspreq').style.display = 'none';
		}
	}

	function deleteMedia(id){
		var mediafiles;
		document.getElementById('tr'+id).style.display="none";
		mediafiles=document.getElementById('mediafiles').value;
		array=new Array();
		array=mediafiles.split(",");
		for(i=0;i<array.length;i++){
   			if(array[i]==id){
     			array.splice(i,1);
			}
		}
		document.getElementById('mediafiles').value=array.toString();
	}
	
	function deleteCourse(id){
		var mediafiles;
		document.getElementById('tr_'+id).style.display="none";
		mediafiles=document.getElementById('preqfiles').value;
		array=new Array();
		array=mediafiles.split(",");
		for(i=0;i<array.length;i++){
   			if(array[i]==id){
     			array.splice(i,1);
			}
		}
		document.getElementById('preqfiles').value=array.toString();
	}
	
	function deleteImage(id){
		var url = 'components/com_guru/views/guruprograms/tmpl/ajax.php?id='+id+"&avatar=0";		
		var req = new Request.HTML({
			method: 'get',
			asynchronous: 'true',
			url: url,
			data: { 'do' : '1' },
			onSuccess: function() {
					document.getElementById('view_imagelist23').src="<?php echo JURI::base(); ?>components/com_guru/images/blank.png";
					document.getElementById('deletebtn').style.display="none";
					document.getElementById('img_name').value="";
					document.getElementById('image').value="";
			},
			onCreate: function(){
			}			
		}).send();		
		return true;	
	}
	function deleteImageA(id){
		var url = 'components/com_guru/views/guruprograms/tmpl/ajax.php?id='+id+"&avatar=1";		
		var req = new Request.HTML({
			method: 'get',
			asynchronous: 'true',
			url: url,
			data: { 'do' : '1' },
			onSuccess: function() {
					document.getElementById('view_imagelist24').src="<?php echo JURI::base(); ?>components/com_guru/images/blank.png";
					document.getElementById('deletebtn2').style.display="none";
					document.getElementById('image_avatar2').value="";
					document.getElementById('image_avatar').value="";
			},
			onCreate: function(){
			}			
		}).send();		
		return true;	
	}
    
    function checkPlans(id) {
        var $checked = jQuery('#' + id + ' .plain:checkbox:checked');
        var $total = jQuery('#' + id + ' .plain:checkbox');
		
        if ($checked.length < $total.length) {
            //console.log('Not all boxes are checked');
            $total.each(function(){
                jQuery(this).prop('checked', true);
            });
        } 
		else {
            //console.log('All boxes are checked');
            $total.each(function(){
                jQuery(this).prop('checked', false);
            });
        }
    }
	function publishUn(i){
		var url = 'components/com_guru/js/ajaxExercices.php?id='+i;
		var myAjax = new Request.HTML({
			method: 'get',
			asynchronous: 'true',
			url: url,
			data: { 'do' : '1' },
			onSuccess: function(data) {
				if(data[0].textContent == 'publish'){
					element_id = "g_publish"+i;
					document.getElementById(element_id).className = "icon-ok";
				}
				else if(data[0].textContent == 'unpublish'){
					element_id = "g_publish"+i;
					document.getElementById(element_id).className = "icon-remove";
				}
			},
			onCreate: function(){
			}			
		}).send();	
		return true;	
	}
	function checkNegativeNb(nb){
		if(parseInt(nb)<0 || isNaN(nb)){
			alert("<?php echo JText::_("GURU_INVALID_NUMBER_AVG");?>");
			return false;
		}
		else{
			return  true;
		}
	}	
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<div class="well"><?php if ($program->id<1) echo JText::_('GURU_NEWCOURSE'); else echo JText::_('GURU_EDITCOURSE');?></div>
          <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('GURU_GENERAL');?></a></li>
                <li><a href="#prog_description" data-toggle="tab"><?php echo JText::_('GURU_PRODDESC');?></a></li>
                 <li><a href="#prog_image" data-toggle="tab"><?php echo JText::_('GURU_IMAGE_COVER');?></a></li>
                <li><a href="#prog_exerc" data-toggle="tab"><?php echo JText::_('GURU_EXERCISE_FILES');?></a></li>
                <li><a href="#prog_pricing" data-toggle="tab"><?php echo JText::_('GURU_PRICING_PLANS');?></a></li>
                <li><a href="#prog_publishing" data-toggle="tab"><?php echo JText::_('GURU_PUBLISHING');?></a></li>
                <li><a href="#prog_metatags" data-toggle="tab"><?php echo JText::_('GURU_METATAGS');?></a></li>
                <li><a href="#prog_day_req" data-toggle="tab"><?php echo JText::_('GURU_DAY_REQ');?></a></li>
         </ul>
         <div class="tab-content">
         <div class="tab-pane active" id="general">
            <table class="adminform">
                <tr>
                    <td >
                        <?php echo JText::_('GURU_PRODNAME'); ?>:<font color="#ff0000">*</font></td>
                    <td>
                        <input class="inputbox" type="text" name="name" size="40" maxlength="255" value="<?php echo $program->name; ?>" />
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODNAME"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                </tr>
                <tr>
                    <td >
                        <?php echo JText::_('GURU_ALIAS'); ?>:</td>
                    <td>
                        <input class="inputbox" type="text" name="alias" size="40" maxlength="255" value="<?php echo $program->alias; ?>" />
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_ALIAS"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                </tr>
                <!--ADDED BY JOSEPH 05/04/2015
                <tr>
                    <td >
                        <?php //echo "Duration (hours)"; ?>:</td>
                    <td>
                        <input class="inputbox" type="number" step="0.5" min="0" name="duration" size="40" maxlength="255" value="<?php //echo $program->alias; ?>" />
                    </td>
                </tr>
                END-->
                <tr>
                    <td >
                        <?php echo JText::_('GURU_CATEGPARENT'); ?>:</td>
                    <td>
                        <?php $lists['treecateg']=$this->list_all(0, "catid", $program->catid, $program->catid); ?>
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_CATEGPARENT"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                </tr>
                <tr>
                    <td >
                        <?php echo JText::_('GURU_AUTHOR'); ?>:<font color="#ff0000">*</font></td>
                    <td>
                        <?php echo $lists['author']; ?>
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_AUTHOR"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                </tr>
                <tr>
                    <td >
                        <?php echo JText::_('GURU_LEVEL'); ?>:</td>
                    <td> 
                        <?php echo $lists['level']; ?>
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_LEVEL"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                </tr>
                <tr>
                    <td ><?php echo JText::_('GURU_SKIP_MODULE_PAGE');?>:</td>
                    <?php
                    $skip_module = $program->skip_module;
                    if(!isset($skip_module) || $skip_module == ""){
                        $skip_module = 0;
                    }
                    ?>	
                    <td>            	
                        <input type="hidden" name="skip_module" value="0">
						<?php
							$checked = '';
							if($skip_module == 1){
								$checked = 'checked="checked"';
							}
						?>
						<input type="checkbox" <?php echo $checked; ?> value="1" class="ace-switch ace-switch-5" name="skip_module">
						<span class="lbl"></span>                             
                   </td>
                </tr>
                 <tr>
                    <td>
                        <?php echo JText::_("GURU_COURSE_TYPE");?>:</td>
                    <td>
						<?php
							$disabled = "";
							if(intval($id) != 0){
								$disabled = 'disabled="disabled"';
								echo '<input type="hidden" name="course_type" value="'.$program->course_type.'" />';
							}
						?>
						<select id="course_type" <?php echo $disabled; ?> name="course_type" onchange="javascript:guruShowLessons(value)">
							<option value="0" <?php if($program->course_type == "0"){echo 'selected="selected"';} ?>><?php echo  JText::_("GURU_NON_SEQ"); ?></option>
							<option value="1" <?php if($program->course_type == "1"){echo 'selected="selected"';} ?>><?php echo  JText::_("GURU_SEQ");  ?></option>
						</select>
						<span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_SEQ"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                </tr>
                 <tr id="lessons_release_td"  <?php if ($program->course_type ==1){$style="style=display:table-row;"; echo $style;} else{$style="style=display:none;"; echo $style;} ?>>

                    <td >
                        <?php echo JText::_("GURU_LESSON_RELEASE"); ?>:</td>
                    <td>
						<?php
							$disabled = "";
							if(intval($id) != 0){
								$disabled = 'disabled="disabled"';
								echo '<input type="hidden" name="lesson_release" value="'.$program->lesson_release.'" />';
							}
						?>
					
                       <select id="lesson_release" <?php echo $disabled; ?> name="lesson_release" onchange="javascript:guruPopupChangeOption()">
                        <option value="0" <?php if($program->lesson_release == "0"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_ALL_AT_ONCE"); ?></option>
                        <option value="1" <?php if($program->lesson_release == "1"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_ONE_PER_DAY");?></option>
                        <option value="2" <?php if($program->lesson_release == "2"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_ONE_PER_W"); ?></option>
                        <option value="3" <?php if($program->lesson_release == "3"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_ONE_PER_M"); ?></option>
                    </select>
                     <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_LESSON_RELEASE"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                </tr>	
                <tr id="lessons_show_td" <?php if ($program->course_type ==1){$style="style=display:table-row;"; echo $style;} else{$style="style=display:none;"; echo $style;} ?>>
                    <td >
                        <?php echo JText::_("GURU_SHOW_UNRELEASED_LESSONS"); ?>:</td>
                    <td>
                        <select id="lessons_show" name="lessons_show">
                            <option value="1" <?php if($program->lessons_show == "1"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_GRAYED_TEXT");?></option>
                            <option value="2" <?php if($program->lessons_show == "2"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_SHOULD_NOT_SHOW"); ?></option>
                        </select>
                         <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_SHOW_UNRELEASED_LESSONS"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                </tr>	
                 <tr>
                    <td >
                        <?php echo JText::_('GURU_FINAL_EXAM'); ?></td>
                    <td>
                        <select id="final_quizzes" name="final_quizzes">
                            <option value="0" <?php if($program->id_final_exam == "0"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_NO_FINAL_EXAM");?></option>
                            <?php
                            $cid = "0";
                            $db = JFactory::getDBO(); 
                            $sql = "SELECT id as cid , name as name FROM #__guru_quiz WHERE is_final = 1 ORDER by name asc";		
                            $db->setQuery($sql);
                            $db->query();
                            $result = $db->loadAssocList();
    
                            if(is_array($result) && count($result) > 0){		
                                foreach($result as $key => $values){						
                                    $quizid = $values["cid"];
                                    $quizname = $values["name"];			
                                    
                                    ?>			
                            <option value="<?php echo $quizid;?>"<?php if($program->id_final_exam == $quizid ){echo 'selected="selected"';} ?>><?php echo $quizname; ?></option>
                            <?php
                                }
                            }			
                            ?>
                        </select>
                         <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_FINAL_EXAM"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                </tr>
                <tr>
                <td><?php echo JText::_('GURU_CERTIFICATE_TERM');?> </td>
                    <td>
                       <select id="certificate_setts" name="certificate_setts" onchange="javascript:ChangeTermCourse(this.value,'<?php echo $program->id;?>' )">
                        <option value="1" <?php if($program->certificate_term  == "1"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_NO_CERTIFICATE"); ?></option>
                        <option value="2" <?php if($program->certificate_term  == "2"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_COMPLETE_ALL_LESSONS");?></option>
                        <option value="3" <?php if($program->certificate_term  == "3"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_PASS_FINAL_EXAM"); ?></option>
                        <option value="4" <?php if($program->certificate_term  == "4"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_PASS_THE_QUIZZES_IN_AVG"); ?></option>
                        <option value="5" <?php if($program->certificate_term  == "5"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_CERT_TERM_FALFE"); ?></option>
                        <option value="6" <?php if($program->certificate_term  == "6"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_CERT_TERM_FALPQAVG"); ?></option>
                    </select>
                     <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_CERTIFICATE_TERM"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </td>
                    <td  id="avg_certificate" style=" <?php if($program->certificate_term  == "4" || $program->certificate_term  == "6" ){echo 'display:table-row;';}else{echo 'display:none; ';}?>">&nbsp;&nbsp;&nbsp;
                        <input onchange="javascript:checkNegativeNb(this.value);" size="5px;" type="text" name="avg_cert" id="avg_cert" value="<?php if($program->id ==""){echo '70';} else{echo $program->avg_certc;} ?>" /> &nbsp;%
                    </td>
            </tr>
            <tr id="coursecertifiactemsg" style=" <?php if($program->certificate_term  != "1" && $program->certificate_term  != "0"){echo 'display:table-row;';}else{echo 'display:none;';}?>" >
                <td><?php echo JText::_('GURU_CERTIFICATE_COURSE_MSG');?></td>
                <td><textarea id="coursemessage" name="coursemessage" cols="50" rows="4" maxlength="7000"><?php echo stripslashes($program->certificate_course_msg); ?></textarea> 
                 <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_CERTIFICATE_COURSE_MSG1"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                </td>
            </tr>
            </table>
	</div>
     <div class="tab-pane" id="prog_description">
        <table class="adminform">
             <tr>
                <td>
                	<?php echo JText::_('GURU_PRODDESC');?>: 
                </td>
                <td>
                    <table>
                        <tr>
                            <td>
                            	<div onmouseover="document.getElementById('editor_clicked').value = 'description';">
									<?php 
                                        echo $editorul->display( 'description', ''.stripslashes($program->description),'100%', '220px', '20', '50' );
                                    ?>
                                </div>    
                            </td>
                            <td valign="top">
                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODDESC"); ?>" >
                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                </span>
                            </td>
                        </tr>
                    </table>		
                </td>
            </tr>			
        </table>
    </div>
     <div class="tab-pane" id="prog_image">
		<table class="adminform">
        	<div class="widget-header widget-header-flat">
            	<h5><?php echo JText::_('GURU_IMAGE_COVER2');?></h5>
            </div>
            <div class="well ijmargin_top" >
            	<?php echo JText::_('GURU_IMAGE_COVER_USAGE');?>
			</div>
			<tr>
				<td width="100" align="left">
					<?php echo JText::_('GURU_IMAGE_COVER2');?>:
				</td>
				<td>
                	<div style="float:left;">
            			<div id="fileUploader"></div>
                    </div>
                    <div style="float:left; padding-left:10px;">
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_IMAGE"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </div>
                    <div style="float:left; padding-left:10px;">
                       <?php echo JText::_('GURU_RECOMMENDED_SIZE');?>
                    </div>
            		<input type="hidden" name="image" id="image" value="<?php echo $program->image; ?>" />&nbsp; 
        		</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('GURU_PRODCIMG_COVER');?> 
				</td>
				<td>
					<?php
            		if(isset($program->image) && $program->image!=""){ 
					
						?>
						<img id="view_imagelist23" name="view_imagelist" src='../<?php echo $program->image;?>'/>
						<br />
						<input style="margin-top:10px;" type="button" class="btn" value="<?php echo JText::_('GURU_REMOVE'); ?>" onclick="deleteImage('<?php echo $program->id; ?>');" id="deletebtn" />
						<input type="hidden" value="<?php echo $program->image; ?>" name="img_name" id="img_name" />		
					<?php 
					} 
					else {
						echo "<img id='view_imagelist23' name='view_imagelist' src='".JURI::base()."components/com_guru/images/blank.png'/>";
					}
					?>
				</td>
			</tr>		
		</table>
        <table class="adminform">
        	<div class="widget-header widget-header-flat">
            	<h5><?php echo JText::_('GURU_IMAGE_AVATAR');?></h5>
            </div>
            <div class="well ijmargin_top" >
            	<?php echo JText::_('GURU_IMAGE_AVATAR_USAGE');?>
			</div>
			<tr>
				<td width="100" align="left">
					<?php echo JText::_('GURU_IMAGE_AVATAR');?>:
				</td>
				<td>
                	<div style="float:left;">
            			<div id="fileUploader1"></div>
                    </div>
                    <div style="float:left; padding-left:10px;">
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_IMAGE"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </div>
            		<input type="hidden" name="image_avatar" id="image_avatar" value="<?php echo $program->image_avatar; ?>" />&nbsp; 
        		</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('GURU_PRODCIMG_AVATAR');?> 
				</td>
				<td>
					<?php
            		if(isset($program->image_avatar) && $program->image_avatar!=""){ 
						?>
							<img id="view_imagelist24" name="view_imagelist1" src='../<?php echo $program->image_avatar;?>'/>
						<br />
						<input style="margin-top:10px;" type="button" class="btn" value="<?php echo JText::_('GURU_REMOVE'); ?>" onclick="deleteImageA('<?php echo $program->id; ?>');" id="deletebtn2" />
						<input type="hidden" value="<?php echo $program->image_avatar; ?>" name="image_avatar2" id="image_avatar2" />		
					<?php 
					} 
					else {
						echo "<img id='view_imagelist24' name='view_imagelist' src='".JURI::base()."components/com_guru/images/blank.png'/>";
					}
					?>
				</td>
			</tr>		
		</table>
	</div>
    <div class="tab-pane" id="prog_exerc">
		<table class="table">
			<tr>				
				<td>
                    <div style="float:left;">
                        <a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruPrograms&task=addmedia&tmpl=component&cid[]=<?php echo $program->id;?>" class="modal"><?php echo JText::_("GURU_EXERCISE_FILE"); ?></a>&nbsp;
                    </div>
                    <div style="float:left;">
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_EXERCISE_FILE"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </div>
                 </td>
			</tr>
            <tr>
				<td>
					<table id="articleList" class="table" width="100%">
                    	<thead>
                    		<tr>
								<th></th>
                                <th></th>
            					<th style="text-align:center; width:5%;">#</th>
								<th></th>
            					<th width="30%" style="text-align:left;">
									<strong><?php echo JText::_("GURU_FILE_MEDIA_NAME"); ?></strong>
								</th>
            					<th width="9%" style="text-align:center;">
									<strong><?php echo JText::_("GURU_PUBLISHED"); ?></strong>
								</th>
            					
            					<th width="12%" style="text-align:center;">
									<strong><?php echo JText::_("GURU_GUEST_ACCESS"); ?></strong>
								</th>
            					<th width="14%" style="text-align:center;">
									<strong><?php echo JText::_("GURU_REMOVE"); ?></strong>
								</th>
          					</tr>
                         </thead>   
         				<tbody id="rowsmedia">
							<?php 
                            $oldids = "";
                            $display_none = '';
							if($program->id != NULL){
                            	$existing_ids = guruAdminModelguruProgram::existing_ids($program->id);
                            }
							
                            $tt = array();
							if(is_array(@$existing_ids) && count(@$existing_ids) > 0){
								foreach($existing_ids as $ex_idz){
									$tt[] = $ex_idz->media_id;
								}		
                            }
                            $more_media_files=new stdClass();
                            
                            $i=0;
                            $pageNav = new JPagination( count($mmediam), 0, count($mmediam));
                            $n = count($mmediam);
                                    
                            foreach ($mmediam as $mmedial) {	
                            ?>
                            <tr class="row<?php echo $i%2; ?>" id="tr<?php echo $mmedial->media_id;?>" <?php echo $display_none; ?>>
                            	<td>
                                    <span class="sortable-handler active" style="cursor: move;">
                                        <i class="icon-menu"></i>
                                    </span>
                                    <input type="text" class="width-20 text-area-order " value="<?php echo $mmedial->order; ?>" size="5" name="order[]" style="display:none;">
                                </td>  
                                <td  width="5%" style="text-align:center; visibility:hidden;"><?php $checked = JHTML::_('grid.id', $i, $mmedial->media_id); echo $checked;?></td>

                                <td width="5%" style="text-align:center;"><?php echo $i+1; ?></td>
                                <td width="5%" style="text-align:center;">
                                    <?php
                                        switch($mmedial->type){
                                            case "video":{						
                                                         $img_path = JURI::root()."administrator/components/com_guru/images/video.gif";
                                                         echo '<img src="'.$img_path.'" alt="video type"/>';									
                                                        }
                                                break;
                                            case "docs":{						
                                                         $img_path = JURI::root()."administrator/components/com_guru/images/doc.gif";
                                                         echo '<img src="'.$img_path.'" alt="video type"/>';									
                                                        }
                                                break;
                                            case "url":{						
                                                         $img_path = JURI::root()."administrator/components/com_guru/images/url.gif";
                                                         echo '<img src="'.$img_path.'" alt="video type"/>';									
                                                        }
                                                break;
                                            case "image":{						
                                                         $img_path = JURI::root()."administrator/components/com_guru/images/image.jpg";
                                                         echo '<img src="'.$img_path.'" alt="video type"/>';									
                                                        }
                                                break;
                                            case "audio":{						
                                                         $img_path = JURI::root()."administrator/components/com_guru/images/audio.gif";
                                                         echo '<img src="'.$img_path.'" alt="audio type"/>';									
                                                        }
                                                break;											
                                            case "quiz":{						
                                                         $img_path = JURI::root()."administrator/components/com_guru/images/quiz.gif";
                                                         echo '<img src="'.$img_path.'" alt="quiz type"/>';									
                                                        } 
                                                break;
                                            case "text":{						
                                                         $img_path = JURI::root()."administrator/components/com_guru/images/doc.gif";
                                                         echo '<img src="'.$img_path.'" alt="doc type"/>';									
                                                        } 
                                                break;
                                            case "file":{						
                                                         $img_path = JURI::root()."administrator/components/com_guru/images/file.gif";
                                                         echo '<img src="'.$img_path.'" alt="doc type"/>';									
                                                        } 
                                                break;
                                        }
                                    ?>		  
                                </td>
                                <td width="30%">
                                    <a class="a_guru" href="index.php?option=com_guru&controller=guruMedia&task=edit&cid[]=<?php echo $mmedial->media_id;?>" target="_blank"><?php echo $mmedial->name;?></a>
                                </td>		 
                                <td width="9%" style="text-align:center;">
                                <?php
                                	if($mmedial->published ==1){
									?>
                                    	 <a id="g_publish<?php echo $mmedial->media_id;?>" style="cursor: pointer;" class="icon-ok" title="Publish Item" onclick="javascript:publishUn(<?php echo $mmedial->media_id;?>)"></a>
                                <?php
                                    }
                                    else{?>
										 <a id="g_publish<?php echo $mmedial->media_id;?>" style="cursor: pointer;" class="icon-remove" title="Unpublish Item" onclick="javascript:publishUn(<?php echo $mmedial->media_id;?>)"></a>
                                     <?php
                                    }
                                  ?>      
                                </td>
                                <td width="12%" style="text-align:center;">
                                    <select name="<?php echo "access".$mmedial->media_id ?>">
                                        <option value="0" <?php if($mmedial->access == 0) echo "selected"; ?> ><?php echo JText::_("GURU_COU_STUDENTS"); ?></option>
                                        <option value="1" <?php if($mmedial->access == 1) echo "selected"; ?> ><?php echo JText::_("GURU_REG_MEMBERS"); ?></option>
                                        <option value="2" <?php if($mmedial->access == 2) echo "selected"; ?> ><?php echo JText::_("GURU_REG_GUESTS"); ?></option>
                                    </select>          
                                </td>
                                <td width="14%" style="text-align:center;"><?php 
                                    //$img_path = JURI::root().DS."administrator".DS."components".DS."com_guru".DS."images".DS."delete2.gif";
									$img_path = "components/com_guru/images/delete2.gif";
                                    echo '<img onclick="deleteMedia(\''.$mmedial->media_id.'\');" src="'.$img_path.'" alt="delete"/>';?>
                                </td>
                            </tr>
							<?php $i++; 
                            $oldids = $oldids.$mmedial->media_id.',';
                            }
                            if(count($more_media_files)>0)
                            	foreach($more_media_files as $more_media_files_val) {
                                	$display_none = '';
                                	if(!in_array($more_media_files_val->media_id,$tt)) { // if start
                            		?>
                            <tr id="1tr<?php echo $more_media_files_val->media_id;?>" <?php echo $display_none; ?>>
                                <td width="8%"></td>
                                <td width="39%">
                                    <a class="a_guru" href="index.php?option=com_guru&controller=guruMedia&task=edit&cid[]=<?php echo $mmedial->media_id;?>" target="_blank"><?php echo $more_media_files_val->name;?></a>
                                </td>
                                <td width="9%">
                                    <?php echo $more_media_files_val->type;?>
                                </td>
                                <td width="18%">--</td>
                                <td width="12%">
                                    <?php echo $link2_remove; ?>
                                </td>
                                <td width="14%">
                                    <?php echo $more_media_files_val->published;?>
                                </td>
                            </tr>		
							<?php $oldids = $oldids.$more_media_files_val->media_id.',';
							} // if end
							} // foreach end ?>	
						</tbody>
					</table>
      				<input type="hidden" value="<?php echo $oldids;?>" name="mediafiles" id="mediafiles">
                    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
                    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
                    <?php echo JHtml::_('form.token'); ?>
				</td>
			</tr>
		</table> 
      </div>
      <div class="tab-pane" id="prog_pricing">
        <div class="clearfix">
            <div class="pull-left"><input onclick="javascript:checkIfCertificateIsSet();" type="checkbox" id= "chb_free_courses" name="chb_free_courses" <?php if(@$chb_free_courses == "1") echo 'checked="checked"'; else { }?>> <span class="lbl"></span></div>
            <div class="pull-left" style=" float:left; padding:0.2em"> <?php echo trim(JText::_('GURU_FREE_COURSES')); ?></div>
            <div class="pull-left">
                <select id="step_access_courses" name="step_access_courses" onchange="javascript:selectrealtime(this.value); checkIfCertificateIsSet();">
                    <option value="0" <?php if(@$step_access_courses == 0) echo "selected"; ?> ><?php echo JText::_("GURU_COU_STUDENTS"); ?></option>
                    <option value="1" <?php if(@$step_access_courses == 1) echo "selected"; ?> ><?php echo JText::_("GURU_REG_MEMBERS"); ?></option>
                    <option value="2" <?php if(@$step_access_courses == 2) echo "selected"; ?> ><?php echo JText::_("GURU_REG_GUESTS"); ?></option>
                </select>
             </div>
             <div class="pull-left" id="free_courses"> 
                <span style="float: left; padding:5px 10px 0 10px;">
                    <?php echo trim(JText::_('GURU_OF'));?>
                </span> 
                <?php $this->getCourseListForStudents(); ?>                       
            </div>
       </div>     
    
 
    
<?php
    $styleheader = " style='font-size:1.2em;font-weight:bold;padding:0.5em;' ";
    $stylerow = " style='padding:0.5em;' ";
    ?>
    <table class="table">
        <tr>
            <td <?php echo $styleheader; ?>>
                <div style="float:left;">
                    <?php echo trim(JText::_('GURU_SUBSCRIPTIONS')) . ' ' . trim(strtolower(JText::_('GURU_SUBS_PLANS'))); ?>&nbsp;
                </div>
                <div style="float:left;">
                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_SUBSCRIPTIONS"); ?>" >
                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $this->plans; ?>
            </td>
        </tr>
        <tr>
            <td <?php echo $styleheader; ?>>
                <div style="float:left;">
                    <?php echo JText::_('GURU_RENW_PLANS'); ?>&nbsp;
                </div>
                <div style="float:left;">
                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_RENW_PLANS"); ?>" >
                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                    </span>
                </div>                
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $this->renewals; ?>
            </td>
        </tr>
        <tr>
            <td <?php echo $styleheader; ?>>
                <div style="float:left;">
                    <?php echo JText::_('GURU_EMAIL_PLANS'); ?>&nbsp;
                </div>
                <div style="float:left;">
                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_EMAIL_PLANS"); ?>" >
                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                    </span>
               </div>   
           </td>
        </tr>
        <tr>
            <td>
                <?php echo $this->emails; ?>
            </td>
        </tr>
    </table> 
    </div>
    <div class="tab-pane" id="prog_publishing">       
 
     <table>
        <tr>
            <td >
                <?php echo JText::_('GURU_PRODLPBS'); ?>
            </td>
            <td width="50%">
                <?php echo $lists['published']; ?>&nbsp;
                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODLPBS"); ?>" >
					<img border="0" src="components/com_guru/images/icons/tooltip.png">
				</span>
            </td>
         </tr>
         <tr>
            <td valign="top" align="right">
                <?php echo JText::_('GURU_PRODLSPUB'); ?>
            </td>
            <td>
                <?php 
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
				$jnow 	= JFactory::getDate();
				$now 	= $jnow->toSQL();
				
				if ($program->id<1) $start_publish =  date("".$dateformat."", strtotime($now)); else $start_publish = date("".$dateformat."", strtotime($program->startpublish)) ;
                echo JHTML::_('calendar', $start_publish, 'startpublish', 'startpublish', $format, array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODLSPUB"); ?>" >
                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                </span>
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">
                <?php echo JText::_('GURU_PRODLEPUB');?>
            </td>
            <td>
                <?php
					if(substr($program->endpublish, 0, 4) == '0000' || $program->id < 1){
						$end_publish = JText::_('GURU_NEVER');
					}
					else{
						$end_publish = date("".$dateformat."", strtotime($program->endpublish));
					}
					echo JHTML::_('calendar', $end_publish, 'endpublish', 'endpublish', $format, array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19'));
				?>
                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODLEPUB"); ?>" >
                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                </span>
            </td>
        </tr>
    </table>
	</div>
    <div class="tab-pane" id="prog_metatags">
        <table class="adminform">
            <tr>
                <td >
                    <?php echo JText::_('GURU_TITLE'); ?>:</td>
                <td>
                    <input class="inputbox" type="text" name="metatitle" size="40" maxlength="255" value="<?php echo $program->metatitle; ?>" />
                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_TITLE"); ?>" >
                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo JText::_('GURU_KWDS');?>:
                </td>
                <td>
                    <textarea cols="40" name="metakwd" class="inputbox"><?php echo $program->metakwd; ?></textarea>
                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_KWDS"); ?>" >
                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                    </span>
                    <br>
                </td>
            </tr>	
            <tr>
                <td>
                    <?php echo JText::_('GURU_DSCS');?>:
                </td>
                <td>
                    <textarea cols="40" name="metadesc" class="inputbox"><?php echo $program->metadesc; ?></textarea>
                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DSCS"); ?>" >
                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                    </span>
                    <br>
                </td>
            </tr>	
        </table>
    </div>
    <div class="tab-pane" id="prog_day_req">
        <table class="adminform">
            <tr>
                <td  valign="middle">
                    <?php echo JText::_('GURU_PRERQSC'); ?>:
                </td>
                <td>
                    <div style="float:left;">
                        <a rel="{handler: 'iframe', size: {x: 700, y: 450}}" href="index.php?option=com_guru&controller=guruPrograms&task=addcourse&tmpl=component&cid[]=<?php echo $program->id;?>" class="modal"><span title="Parameters" class="icon-32-config"></span><?php echo JText::_("GURU_ADD_COURS_BUTTON"); ?></a>
                    </div>
                    <div style="float:left;">
                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRERQSC"); ?>" >
                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                        </span>
                    </div>
                <br /><br />
                
                <?php
                $mmediam_preq=$this->mmediam_preq;
                $table_display = "table";
                
                if($mmediam_preq == NULL || (is_array($mmediam_preq) && count($mmediam_preq) <= 0)){
                    $table_display = "none";
                }
                ?>
                    <table id="table_courses_id" cellspacing="1" cellpadding="5" border="0" bgcolor="#cccccc" width="100%" style="display:<?php echo $table_display; ?>;">
                         <?php
                            $old_preq_ids='';
							if($program->id !="" || $program->id != NULL){
                            	$preq_existing_ids = guruAdminModelguruProgram::preq_existing_ids($program->id);	
							}											
                        ?>	
                        <tbody id="rowspreq">			 
                            <tr>
                                <td width="8%"><strong><?php echo JText::_("GURU_ID"); ?></strong></td>
                                <td width="56%"><strong><?php echo JText::_("GURU_NAME"); ?></strong></td>
                                <td width="12%"><strong><?php echo JText::_("GURU_REMOVE"); ?></strong></td>
                            </tr>
                        <?php
                            $table_rows = "";
                            if($mmediam_preq!=NULL){
                                foreach($mmediam_preq as $element){
                                    $table_rows .= "<tr id='tr_".$element->media_id."'>";
                                    $table_rows .= 		"<td>".$element->media_id."</td>";
                                    $table_rows .= 		'<td><a class="a_guru" target="_blank" href="index.php?option=com_guru&controller=guruTasks&task=edit&cid[]='.$element->media_id.'">'.$element->name."</a></td>";
                                    $table_rows .= 		'<td><img onclick="deleteCourse(\''.$element->media_id.'\');" alt="delete" src="'.JURI::root()."administrator/components/com_guru/images/delete2.gif".'" /></td>';
                                    $table_rows .= "</tr>";
                                }
                                echo $table_rows;
                            }
                            
                        ?>
                        </tbody>				  
                    </table> 
                </td>
            </tr>		
            <tr>
                <td >
                    <?php echo JText::_('GURU_DAY_OPREQ'); ?>:</td>
                <td>
                	<div onmouseover="document.getElementById('editor_clicked').value = 'pre_req';">
                    	<?php echo $editorul->display( 'pre_req', ''.stripslashes($program->pre_req),'100%', '220px', '20', '50' );?>
                    </div>    
                </td>
                <td>
                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DAY_OPREQ"); ?>" >
                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo JText::_('GURU_DAY_PREQBK');?>:
                </td>
                <td>
                	<div onmouseover="document.getElementById('editor_clicked').value = 'pre_req_books';">
                    	<?php echo $editorul->display( 'pre_req_books', ''.stripslashes($program->pre_req_books),'100%', '220px', '20', '50' );?>
                    </div>    
                    <br>
                </td>
                <td>
                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DAY_PREQBK"); ?>" >
                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                    </span>
                </td>
            </tr>	
            <tr>
                <td>
                    <?php echo JText::_('GURU_DAY_OREQ');?>:
                </td>
                <td>
                	<div onmouseover="document.getElementById('editor_clicked').value = 'reqmts';">
                    	<?php echo $editorul->display( 'reqmts', ''.stripslashes($program->reqmts),'100%', '220px', '20', '50' );?>
                    </div>    
                    <br>
                    <input type="hidden" value="" name="preqfiles" id="preqfiles">			
                </td>
                <td>
                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DAY_OREQ"); ?>" >
                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                    </span>
                </td>
            </tr>	
        </table>
	</div>
   </div>
   <input type="hidden" name="id" value="<?php echo $program->id; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="option" value="com_guru" />
    <input type="hidden" name="controller" value="guruPrograms" />
    <input type="hidden" name="boxchecked" value="" />
    <input type="hidden" name="task2" value="edit" />
    <input type="hidden" name="media_number" value="<?php echo @$n; ?>" id="media_number"/>	
    <input type="hidden" id="editor_clicked" name="editor_clicked" value="" />
    
    <script type="text/javascript" language="javascript">
		function jSelectArticle(id, title, catid, object, link, lang){
			editor_clicked = document.getElementById("editor_clicked").value;
			
			var hreflang = '';
			if (lang !== '')
			{
				var hreflang = ' hreflang = "' + lang + '"';
			}
			var tag = '<a' + hreflang + ' href="' + link + '">' + title + '</a>';
			jInsertEditorText(tag, editor_clicked);
			SqueezeBox.close();
		}
	</script>	
</form>