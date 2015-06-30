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

$doc = JFactory::getDocument();

$doc->addScript('components/com_guru/js/guru_modal.js');

$doc->addScriptDeclaration('
	var accordionItems = new Array();		
	function initPhoneTeacherTabs() {
		// Grab the accordion items from the page
		var divs = document.getElementsByTagName( \'div\' );
		
		for ( var i = 0; i < divs.length; i++ ) {
			if ( divs[i].className == \'accordionItem\' ) accordionItems.push( divs[i] );
		}
		
		// Assign onclick events to the accordion item headings
		for ( var i = 0; i < accordionItems.length; i++ ) {
			var h3 = getFirstChildWithTagName( accordionItems[i], \'H3\' );
			h3.onclick = toggleItem;
		}
		
		// Hide all accordion item bodies except the first
		for ( var i = 1; i < accordionItems.length; i++ ) {
			accordionItems[i].className = \'accordionItem hideTabs\';
		}
    }
	
	function toggleItem() {
      var itemClass = this.parentNode.className;

      // Hide all items
      for ( var i = 0; i < accordionItems.length; i++ ) {
        accordionItems[i].className = \'accordionItem hideTabs\';
      }

      // Show this item if it was previously hidden
      if ( itemClass == \'accordionItem hideTabs\' ) {
        this.parentNode.className = \'accordionItem\';
      }
    }

    function getFirstChildWithTagName( element, tagName ) {
      for ( var i = 0; i < element.childNodes.length; i++ ) {
        if ( element.childNodes[i].nodeName == tagName ) return element.childNodes[i];
      }
    }
	
	jQuery(document).ready(function($) {
		initPhoneTeacherTabs();
	});
	
	/*document.onreadystatechange = function(){
		initPhoneTeacherTabs();
	}*/
	function showContent(href){
		first = true;
		jQuery( \'#myModal .modal-body iframe\').attr(\'src\', href);
	}
	function closeModal(){
		jQuery(\'#myModal .modal-body iframe\').attr(\'src\', \'\');
	}
');
$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');	
require_once (JPATH_COMPONENT_ADMINISTRATOR.DS."models".DS."gurucertificate.php");
$program = $this->program;

require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

if(isset($program->id) && $program->id != NULL ){
	$chb_free_courses = $this->selectedCoursesforFree();
	$step_access_courses = $this->getStepAccessCourses();
	$selected_course = $this->getSelectedCourse();
}
$lists = $program->lists;
$editorul  =  JFactory::getEditor(); 

$mmediam = $this->mmediam;

if(isset($this->mmediam_preq)){
	foreach($this->mmediam_preq as $element){
		$vect[] = $element->id;
	}
}

$config = $this->config;
$config_courses = json_decode($config->psgspage);
$courses_t_prop = $config_courses->courses_image_size_type == "0" ? "width" : "heigth";

$list_authors = $this->listAuthors();
$dateformat = $this->gurudateformat;

$listDirn = "asc";
$listOrder = "ordering";
$saveOrderingUrl = 'index.php?option=com_guru&controller=guruAuthor&task=saveOrderExercices&tmpl=component';
JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');

$max_upload = (int)(ini_get('upload_max_filesize'));
$max_post = (int)(ini_get('post_max_size'));
$memory_limit = (int)(ini_get('memory_limit'));
$upload_mb = min($max_upload, $max_post, $memory_limit);
if($upload_mb == 0) {$upload_mb = 10;}
$upload_mb*=1048576; //transform in bytes
$doc = JFactory::getDocument();
$div_menu = $this->authorGuruMenuBar();

$cid = JRequest::getVar("cid", array(), "get");
$id = intval(@$cid["0"]);
$doc->addScriptDeclaration('
	//jQuery.noConflict();
	jQuery(function(){
		function createUploader(){            
			var uploader = new qq.FileUploader({
				element: document.getElementById(\'fileUploader\'),
				action: \''.JURI::root().'components/com_guru/helpers/fileuploader.php\',
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
							jQuery(\'#view_imagelist23\').attr("src", "'.JURI::root().'"+responseJSON.locate +"/"+ fileName+"?timestamp=" + new Date().getTime());
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
	//jQuery(document).ready(function($) {
//		document.getElementById("free_courses").style.display = '.(@$step_access_courses == 0 ? '"block"' : '"none"').';
//	});
//	
');

$doc->addScriptDeclaration('
	//jQuery.noConflict();
	jQuery(function(){
		function createUploader1(){            
			var uploader = new qq.FileUploader({
				element: document.getElementById(\'fileUploader1\'),
				action: \''.JURI::root().'components/com_guru/helpers/fileuploader.php\',
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
							jQuery(\'#view_imagelist24\').attr("src", "'.JURI::root().'"+responseJSON.locate +"/"+ fileName+"?timestamp=" + new Date().getTime());
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
	//jQuery(document).ready(function($) {
//		document.getElementById("free_courses").style.display = '.(@$step_access_courses == 0 ? '"block"' : '"none"').';
//	});
//	
');
$doc->addScript('components/com_guru/js/fileuploader.js');
$doc->addStyleSheet('components/com_guru/css/fileuploader.css');
$doc->setTitle(trim(JText::_('GURU_AUTHOR'))." ".trim(JText::_('GURU_AUTHOR_MY_COURSE')));

$doc->addScript(JURI::root().'components/com_guru/js/redactor.min.js');
$doc->addStyleSheet(JURI::root().'components/com_guru/css/redactor.css');

?>
<style type="text/css">
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
	
	.accordionItem.hideTabs div{
		display: none;
	}
</style>

<script language="javascript" type="text/javascript">
	function selectrealtime(val){
		var div = document.getElementById("free_courses");
		div.style.display = val == 0 ? "block" : "none";	
	}
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
	
	function guruShowLessons(value){
		if(value == 1){
			document.getElementById('lessons_release_td').style.display = "block";
			document.getElementById('lessons_show_td').style.display = "block";
		}
		else{
			document.getElementById('lessons_release_td').style.display = "none";
			document.getElementById('lessons_show_td').style.display = "none";
		}
	}
	
	function ChangeTermCourse(nb, id){
		chb_free_courses = false;
		if(eval(document.getElementById("chb_free_courses"))){
			chb_free_courses = document.getElementById("chb_free_courses").checked;
		}
		
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
			document.getElementById('coursecertifiactemsg').style.display = 'block';
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
		
	function saveCourse(pressbutton){
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
			id = document.getElementById("id").value;
			continue_save = true;
			
			var req = new Request.HTML({
				async: false,
				method: 'get',
				url: 'components/com_guru/views/guruprograms/tmpl/ajax.php?action=check_values&name='+name+'&id='+id,
				data: { 'do' : '1' },
				onComplete: function(response){
					if(response[0].textContent == "exist"){
						alert("<?php echo JText::_("GURU_ALERT_EXISTING_NAME_ALIAS"); ?>");
						continue_save = false;
						return false;
					}
				}
			}).send();
			
			if(!continue_save){
				return false;
			}
			
			<?php if($config->course_is_free_show == 1){?>
				chb_free_courses = document.getElementById("chb_free_courses").checked;
			<?php }
				else{
			?>	
				chb_free_courses = false;
			<?php
				}
			?>
			
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
			 else if (chb_free_courses == true){
			 	if (form['step_access_courses'].value == 0 && form['selected_course[]'].value == 0 )
				{
					alert("<?php echo JText::_("GURU_SELECTED_COURSE_ALERT");?>");		
				}
				else {submitform( pressbutton );}			 	
			 }
			 else { 
					if(pressbutton=='apply') {submitform('apply');} else {submitform( pressbutton );}
				}
		}
		else{  
			if(pressbutton=='apply') {
				submitform('apply');
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
<div id="myModal" class="modal g_modal hide" style="position: fixed !important;">
    <div class="modal-header">
        <button id="g_modal_close_button" type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:closeModal();">&times;</button>
    </div>
    <div class="modal-body">
    	<iframe id="g_add_course"></iframe>
    </div>
</div>

<div class="g_row">
	<div class="g_cell span12">
		<div>
			<div>
                <div id="g_authoraddcourse" class="clearfix com-cont-wrap">
                	<?php 	echo $div_menu; //MENU TOP OF AUTHORS?>
                    <?php
						if($deviceType != "phone"){
							// computer or tablet
					?>
                            <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
                                <div class="page_title">
                        			<h2>
										<?php if ($program->id<1) echo JText::_('GURU_NEWCOURSE'); else echo JText::_('GURU_EDITCOURSE');?>
                                    </h2>    
                                </div>
                                
                                <div class="g_inline_child clearfix">
                                        <input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE"); ?>" onclick="javascript:saveCourse('apply');" />
                                        <input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE_AND_CLOSE"); ?>" onclick="javascript:saveCourse('save');" />
                                        <input type="button" class="btn btn-inverse" value="<?php echo JText::_("GURU_CLOSE"); ?>" onclick="document.location.href='<?php echo JRoute::_("index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses"); ?>';" />
                                </div>
                                
                				<div class="clearfix" style="padding-bottom:10px;"></div>
                                
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('GURU_GENERAL');?></a></li>
                                    <li><a href="#prog_description" data-toggle="tab"><?php echo JText::_('GURU_PRODDESC');?></a></li>
                                     <li><a href="#prog_image" data-toggle="tab"><?php echo JText::_('GURU_IMAGE_COVER');?></a></li>
                                    <li><a href="#prog_exerc" data-toggle="tab"><?php echo JText::_('GURU_EXERCISE_FILES');?></a></li>
                                    <!--REMOVED BY JOSEPH BUCK-->
                                    <!--<li><a href="#prog_pricing" data-toggle="tab"><?php echo JText::_('GURU_PRICING_PLANS');?></a></li>-->
                                    <!--END-->
                                    <li><a href="#prog_day_req" data-toggle="tab"><?php echo JText::_('GURU_DAY_REQ');?></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="general">
                                        <div class="control-group clearfix">
                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_PRODNAME");?>: <font color="#ff0000">*</font></label>
                                            <div class="controls g_cell span9">
                                                <span>
                                                    <input class="inputbox" type="text" name="name" size="40" maxlength="255" value="<?php echo $program->name; ?>" />
                                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODNAME"); ?>" >
                                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                    </span>
                                                </span>    
                                            </div>
                                        </div>
                                        
                                        <div class="control-group clearfix">
                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_CATEGPARENT");?>:</label>
                                            <div class="controls g_cell span5">
                                                <span>
                                                     <?php $lists['treecateg']= $this->list_all(0, "catid", $program->catid, $program->catid); ?>
                                                </span>    
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_CATEGPARENT"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control-group clearfix">
                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_LEVEL");?>:</label>
                                            <div class="controls g_cell span5">
                                                <span>
                                                     <?php echo $lists['level']; ?>
                                                </span>    
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_LEVEL"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="g_date_style">
                                            <div class="control-group clearfix">
                                                <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_PRODLPBS");?>:</label>
                                                <div class="controls g_cell span5">
                                                     <?php echo $lists['published']; ?>
                                                     <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODLPBS"); ?>" >
                                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                    </span>
                                                </div>
                                            </div>  
                                            <div class="control-group clearfix">
                                                <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_PRODLSPUB");?>:</label>
                                                <div class="controls g_cell span5">
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
                                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODLSPUB"); ?>">
                                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                    </span>
                                                </div>
                                            </div> 
                                            <div class="control-group clearfix">
                                                <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_PRODLEPUB");?>:</label>
                                                <div class="controls g_cell span5">
                                                    <?php
                                                        if(substr($program->endpublish, 0, 4) == '0000' || $program->id < 1){
                                                            $end_publish = JText::_('GURU_NEVER');
                                                        }
                                                        else{
                                                            $end_publish = date("".$dateformat."", strtotime($program->endpublish));
                                                        }
                                                        $calendar = JHTML::_('calendar', $end_publish, 'endpublish', 'endpublish', $format, array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19'));
														if(substr($program->endpublish, 0, 4) == '0000' || $program->id < 1){
															$calendar = str_replace('value=""', 'value="'.$end_publish.'"', $calendar);;
														}
														echo $calendar;
                                                    ?>
                                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODLEPUB"); ?>" >
                                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                    </span>
                                                </div>
                                            </div> 
                                        </div>                
                                        <div class="control-group clearfix">
                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_SKIP_MODULE_PAGE");?>:</label>
                                            <div class="controls g_cell span5">
                                                <span>
                                                      <?php
                                                    $skip_module = $program->skip_module;
                                                    if(!isset($skip_module) || $skip_module == ""){
                                                        $skip_module = 0;
                                                    }
                                                    ?>	
                                                    <input type="hidden" name="skip_module" value="0">
                                                    <?php
                                                        $checked = '';
                                                        if($skip_module == 1){
                                                            $checked = 'checked="checked"';
                                                        }
                                                    ?>
                                                    <input type="checkbox" <?php echo $checked; ?> value="1" class="ace-switch ace-switch-5" name="skip_module">
                                                    <span class="lbl"></span>
                                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_SKIP"); ?>" >
                                                        <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                    </span>
                                                </span>    
                                            </div>
                                        </div>
                                        <div class="control-group clearfix">
                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_COURSE_TYPE");?>:</label>
                                            <div class="controls g_cell span5">
                                                <span>
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
                                                </span>    
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_SEQ"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control-group clearfix" id="lessons_release_td"  <?php if ($program->course_type ==1){$style="style=display:table-row;"; echo $style;} else{$style="style=display:none;"; echo $style;} ?>>
                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_LESSON_RELEASE");?>:</label>
                                            <div class="controls g_cell span5">
                                                <span>
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
                                                </span>    
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_LESSON_RELEASE"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="control-group clearfix" id="lessons_show_td"  <?php if ($program->course_type ==1){$style="style=display:table-row;"; echo $style;} else{$style="style=display:none;"; echo $style;} ?>>
                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_SHOW_UNRELEASED_LESSONS");?>:</label>
                                            <div class="controls g_cell span5">
                                                <span>
                                                    <select id="lessons_show" name="lessons_show">
                                                        <option value="1" <?php if($program->lessons_show == "1"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_GRAYED_TEXT");?></option>
                                                        <option value="2" <?php if($program->lessons_show == "2"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_SHOULD_NOT_SHOW"); ?></option>
                                                    </select>
                                                </span>    
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_SHOW_UNRELEASED_LESSONS"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>
                                         <div class="control-group clearfix">
                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_FINAL_EXAM");?>:</label>
                                            <div class="controls g_cell span5">
                                                <span>
                                                     <select id="final_quizzes" name="final_quizzes">
                                                        <option value="0" <?php if($program->id_final_exam == "0"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_NO_FINAL_EXAM");?></option>
                                                        <?php
                                                        $cid = "0";
                                                        $db = JFactory::getDBO();
														$user = JFactory::getUser();
														
                                                        $sql = "SELECT `id` as cid , `name` as name FROM #__guru_quiz WHERE `is_final` = 1 and `author`=".intval($user->id)." ORDER by name asc";		
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
                                                </span>    
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_FINAL_EXAM"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>
                                         <div class="control-group clearfix">
                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_CERTIFICATE_TERM");?>:</label>
                                            <div class="controls g_cell span5">
                                                <span>
                                                      <select id="certificate_setts" name="certificate_setts" onchange="javascript:ChangeTermCourse(this.value,'<?php echo $program->id;?>' )">
                                                        <option value="1" <?php if($program->certificate_term  == "1"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_NO_CERTIFICATE"); ?></option>
                                                        <option value="2" <?php if($program->certificate_term  == "2"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_COMPLETE_ALL_LESSONS");?></option>
                                                        <option value="3" <?php if($program->certificate_term  == "3"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_PASS_FINAL_EXAM"); ?></option>
                                                        <option value="4" <?php if($program->certificate_term  == "4"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_PASS_THE_QUIZZES_IN_AVG"); ?></option>
                                                        <option value="5" <?php if($program->certificate_term  == "5"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_CERT_TERM_FALFE"); ?></option>
                                                        <option value="6" <?php if($program->certificate_term  == "6"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_CERT_TERM_FALPQAVG"); ?></option>
                                                    </select>
                                                </span>    
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_CERTIFICATE_TERM"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                            <div class="controls g_cell span3" id="avg_certificate" style=" <?php if($program->certificate_term  == "4" || $program->certificate_term  == "6" ){echo 'display:table-row;';}else{echo 'display:none; ';}?>">
                                                <input onchange="javascript:checkNegativeNb(this.value);" size="5px;" type="text" name="avg_cert" id="avg_cert" value="<?php if($program->id ==""){echo '70';} else{echo $program->avg_certc;} ?>" class="input-mini" /> %
                                            </div>
                                        </div>
                                        <div class="control-group clearfix" id="coursecertifiactemsg" style=" <?php if($program->certificate_term  != "1" && $program->certificate_term  != "0"){echo 'display:block;';}else{echo 'display:none;';}?>">
                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_CERTIFICATE_COURSE_MSG");?>:</label>
                                            <div class="controls g_cell span5">
                                                <span>
                                                    <textarea style="float-left" id="coursemessage" name="coursemessage" cols="50" rows="4" maxlength="7000"><?php echo stripslashes($program->certificate_course_msg); ?></textarea>
                                                </span>    
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_CERTIFICATE_COURSE_MSG1"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane" id="prog_description">
                                         <div class="control-group clearfix">
                                            <label class="control-label g_cell span2" for="name"><h2><?php echo JText::_("GURU_PRODDESC");?>:</h2></label>
                                            <div class="controls g_cell span9">
                                               <div onmouseover="document.getElementById('editor_clicked').value = 'description';">
                                                    <?php 
                                                        //echo $editorul->display( 'description', ''.stripslashes($program->description),'100%', '220px', '20', '50' );
                                                    ?>
                                                    <textarea id="description" name="description" class="useredactor" style="width:100%; height:100px;"><?php echo $program->description; ?></textarea>
                                                </div>      
                                            </div>
                                            <div class="controls g_cell span1">
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODDESC"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane" id="prog_image">
                                        <h5 class="sub-title"><?php echo JText::_('GURU_IMAGE_COVER2');?></h5>
                                        <div class="alert alert-info" >
                                            <?php echo JText::_('GURU_IMAGE_COVER_USAGE');?>
                                        </div>
                                        
                                        <div class="control-group clearfix">
                                            <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_IMAGE_COVER2");?>:</label>
                                                <div class="controls g_cell span5">
                                                    <div style="float:left;">
                                                        <div id="fileUploader"></div>
                                                    </div> 
                                                    &nbsp;
                                                    <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_IMAGE"); ?>" >
                                                    	<img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                	</span>
                                                </div>
                                            <div class="controls g_cell span4">
                                                <div style="float:left; padding-left:10px;">
                                                    <?php echo JText::_('GURU_RECOMMENDED_SIZE');?>
                                                </div>
                                            </div>
                                            <input type="hidden" name="image" id="image" value="<?php echo $program->image; ?>" />&nbsp; 
                                        </div>
                                        <div class="control-group clearfix">
                                            <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_PRODCIMG_COVER");?>:</label>
                                            <div class="controls g_cell span10">
                                                <?php
                                                    if(isset($program->image) && $program->image!=""){ 
                                                    
                                                        ?>
                                                        <img id="view_imagelist23" name="view_imagelist" src='<?php echo JURI::root();?><?php echo $program->image;?>'/>
                                                        <br />
                                                        <input style="margin-top:10px;" type="button" class="btn btn-danger" value="<?php echo JText::_('GURU_REMOVE'); ?>" onclick="deleteImage('<?php echo $program->id; ?>');" id="deletebtn" />
                                                        <input type="hidden" value="<?php echo $program->image; ?>" name="img_name" id="img_name" />		
                                                    <?php 
                                                    } 
                                                    else {
                                                        echo "<img id='view_imagelist23' name='view_imagelist' src='".JURI::base()."components/com_guru/images/blank.png'/>";
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        
                                        <h5 class="sub-title"><?php echo JText::_('GURU_IMAGE_AVATAR');?></h5>
                                        <div class="alert alert-info" >
                                            <?php echo JText::_('GURU_IMAGE_AVATAR_USAGE');?>
                                        </div>
                                        <div class="control-group clearfix">
                                            <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_IMAGE_AVATAR");?>:</label>
                                                <div class="controls g_cell span5">
                                                 <div style="float:left;">
                                                    <div id="fileUploader1"></div>
                                                </div>
                                                &nbsp;
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_IMAGE"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                                </div>
                                            <input type="hidden" name="image_avatar" id="image_avatar" value="<?php echo $program->image_avatar; ?>" />&nbsp; 
                                        </div>
                                        <div class="control-group clearfix">
                                            <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_PRODCIMG_AVATAR");?>:</label>
                                            <div class="controls g_cell span10">
                                                <?php
                                                    if(isset($program->image_avatar) && $program->image_avatar!=""){ 
                                                        ?>
                                                            <img id="view_imagelist24" name="view_imagelist1" src='<?php echo JURI::root();?><?php echo $program->image_avatar;?>'/>
                                                        <br />
                                                        <input style="margin-top:10px;" type="button" class="btn btn-danger" value="<?php echo JText::_('GURU_REMOVE'); ?>" onclick="deleteImageA('<?php echo $program->id; ?>');" id="deletebtn2" />
                                                        <input type="hidden" value="<?php echo $program->image_avatar; ?>" name="image_avatar2" id="image_avatar2" />		
                                                    <?php 
                                                    } 
                                                    else {
                                                        echo "<img id='view_imagelist24' name='view_imagelist' src='".JURI::base()."components/com_guru/images/blank.png'/>";
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane" id="prog_exerc">
                                    
                                        <div style="float:left;">
                                            <input type="button" class="btn btn-primary tree-button g_margin_bottom" onclick="javascript:openMyModal(800, 500, '<?php echo JURI::root();?>index.php?option=com_guru&view=guruauthor&task=addexercise&tmpl=component&cid=<?php echo $program->id;?>'); return false;" value="<?php echo JText::_("GURU_EXERCISE_FILE"); ?>" />
                                        </div>
                                        <div style="float:left;">
                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_EXERCISE_FILE"); ?>" >
                                                &nbsp;<img border="0" src="components/com_guru/images/icons/tooltip.png">
                                            </span>
                                        </div>
                                        <div class="clearfix">
                                            <div class="g_table_wrap">
                                                <table id="articleList" class="table table-striped">
                                                    <tr class="g_table_header">
                                                        <th class="g_cell_1 center">#</th>
                                                        <th class="g_cell_2 center"><?php echo JText::_('GURU_FILE_MEDIA_NAME'); ?></th>
                                                        <th class="g_cell_3 center"><?php echo JText::_('GURU_PUBLISHED'); ?></th>
                                                        <th class="g_cell_4 center"><?php echo JText::_("GURU_GUEST_ACCESS"); ?></th>
                                                        <th class="g_cell_5 center"><?php echo JText::_("GURU_REMOVE"); ?></th>
                                                    </tr>
                                                   <tbody id="rowsmedia"> 
                                                    <?php 
                                                   $oldids = "";
                                                    $display_none = '';
                                                    if($program->id != NULL){
                                                        $existing_ids = $this->existing_ids($program->id);
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
                                                    <tr class="guru_row" id="tr<?php echo intval($mmedial->media_id); ?>">
                                                        <td>
                                                            <span class="sortable-handler active" style="cursor: move;">
                                                                <i class="icon-menu"></i>
                                                            </span>
                                                            <input type="text" class="width-20 text-area-order " value="<?php echo $mmedial->order; ?>" size="5" name="order[]" style="display:none;">
                                                        </td>  
                                                        <td style="text-align:center; visibility:hidden;">
															<?php $checked = JHTML::_('grid.id', $i, $mmedial->media_id); echo $checked;?>
														</td>
                                                        <td class="g_cell_1"><?php echo $i+1; ?></td>
                                                        <td class="g_cell_2">
                                                             <?php
                                                                switch($mmedial->type){
                                                                    case "video":{						
                                                                                 $img_path = JURI::root()."components/com_guru/images/video.gif";
                                                                                 echo '<img src="'.$img_path.'" alt="video type"/>';									
                                                                                }
                                                                        break;
                                                                    case "docs":{						
                                                                                 $img_path = JURI::root()."components/com_guru/images/doc.gif";
                                                                                 echo '<img src="'.$img_path.'" alt="video type"/>';									
                                                                                }
                                                                        break;
                                                                    case "url":{						
                                                                                 $img_path = JURI::root()."components/com_guru/images/url.gif";
                                                                                 echo '<img src="'.$img_path.'" alt="video type"/>';									
                                                                                }
                                                                        break;
                                                                    case "image":{						
                                                                                 $img_path = JURI::root()."components/com_guru/images/image.jpg";
                                                                                 echo '<img src="'.$img_path.'" alt="video type"/>';									
                                                                                }
                                                                        break;
                                                                    case "audio":{						
                                                                                 $img_path = JURI::root()."components/com_guru/images/audio.gif";
                                                                                 echo '<img src="'.$img_path.'" alt="audio type"/>';									
                                                                                }
                                                                        break;											
                                                                    case "quiz":{						
                                                                                 $img_path = JURI::root()."components/com_guru/images/quiz.gif";
                                                                                 echo '<img src="'.$img_path.'" alt="quiz type"/>';									
                                                                                } 
                                                                        break;
                                                                    case "text":{						
                                                                                 $img_path = JURI::root()."components/com_guru/images/doc.gif";
                                                                                 echo '<img src="'.$img_path.'" alt="doc type"/>';									
                                                                                } 
                                                                        break;
                                                                    case "file":{						
                                                                                 $img_path = JURI::root()."components/com_guru/images/file.gif";
                                                                                 echo '<img src="'.$img_path.'" alt="doc type"/>';									
                                                                                } 
                                                                        break;
                                                                }
                                                            ?>	
                                                             <a class="a_guru" href="index.php?option=com_guru&controller=guruAuthor&task=editMedia&cid=<?php echo $mmedial->media_id;?>" target="_blank"><?php echo $mmedial->name;?></a> 	  
                                                        </td>
                                                        <td class="g_cell_3">
             
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
                                                        <td class="g_cell_4">
                                                            <select name="<?php echo "access".$mmedial->media_id ?>">
                                                                <option value="0" <?php if($mmedial->access == 0) echo "selected"; ?> ><?php echo JText::_("GURU_COU_STUDENTS"); ?></option>
                                                                <option value="1" <?php if($mmedial->access == 1) echo "selected"; ?> ><?php echo JText::_("GURU_REG_MEMBERS"); ?></option>
                                                                <option value="2" <?php if($mmedial->access == 2) echo "selected"; ?> ><?php echo JText::_("GURU_REG_GUESTS"); ?></option>
                                                            </select>          
                                                        </td>
                                                        <td class="g_cell_5"><?php 
                                                                $img_path = "components/com_guru/images/delete2.gif";
                                                                echo '<img onclick="deleteMedia(\''.$mmedial->media_id.'\');" src="'.$img_path.'" alt="delete"/>';
                                                            ?>
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
                                                                <a class="a_guru" href="index.php?option=com_guru&controller=guruAuthor&task=editMedia&cid=<?php echo $mmedial->media_id;?>" target="_blank"><?php echo $more_media_files_val->name;?></a>
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
                                           </div>
                                        </div>          
                                    </div>
                                    
                                    <div class="tab-pane" id="prog_pricing">
                                    <?php if($config->course_is_free_show == 1){?>
                                      <div class="clearfix">
                                           <div class="pull-left">
                                            	<input onclick="javascript:checkIfCertificateIsSet();" type="checkbox" id= "chb_free_courses" name="chb_free_courses" <?php if(@$chb_free_courses == "1") echo 'checked="checked"'; else { }?> />
                                                <span class="lbl"></span>
											</div>
                                            <div class="pull-left" style=" float:left; padding-left:10px;"> <?php echo trim(JText::_('GURU_FREE_COURSES')); ?></div>
                                            <div class="pull-left">
                                                &nbsp;
                                                <select id="step_access_courses" name="step_access_courses" onchange="javascript:selectrealtime(this.value); checkIfCertificateIsSet();" class="input-medium">
                                                    <option value="0" <?php if(@$step_access_courses == 0) echo "selected"; ?> ><?php echo JText::_("GURU_COU_STUDENTS"); ?></option>
                                                    <option value="1" <?php if(@$step_access_courses == 1) echo "selected"; ?> ><?php echo JText::_("GURU_REG_MEMBERS"); ?></option>
                                                    <option value="2" <?php if(@$step_access_courses == 2) echo "selected"; ?> ><?php echo JText::_("GURU_REG_GUESTS"); ?></option>
                                                </select>
                                             </div>
                                             <div class="pull-left span5 g_of_select" id="free_courses"> 
                                                <span style="float: left; padding-right: 10px; padding-left: 10px;">
                                                    <?php echo trim(JText::_('GURU_OF'));?>
                                                </span> 
                                                <?php $this->getCourseListForStudents(); ?>                       
                                            </div>
                                        </div> 
        						 <?php } ?>
                                        <div style="float:left;">
                                            <h2><?php echo trim(JText::_('GURU_SUBSCRIPTIONS')) . ' ' . trim(strtolower(JText::_('GURU_SUBS_PLANS'))); ?>&nbsp;</h2>
                                        </div>
                                        <div style="float:left;">
                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_SUBSCRIPTIONS"); ?>" >
                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                            </span>
                                        </div>
                                        <div class="g_table_wrap">
                                            <div class="g_table clearfix"> 
                                                <div class="">
                                                    <?php echo $this->plans; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="float:left;" class="g_margin_top g_margin_bottom">
                                            <h2><?php echo JText::_('GURU_RENW_PLANS'); ?>&nbsp;</h2>
                                        </div>
                                        <div style="float:left;">
                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_RENW_PLANS"); ?>" >
                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                            </span>
                                        </div> 
                                        <div class="g_table_wrap">
                                            <div class="g_table clearfix"> 
                                                <div class="">
                                                    <?php echo $this->renewals; ?>
                                                </div>
                                            </div>
                                        </div>  
                                       <!-- <div style="float:left;">
                                            <?php //echo JText::_('GURU_EMAIL_PLANS'); ?>&nbsp;
                                        </div>
                                        <div style="float:left;">
                                            <span class="editlinktip hasTip" title="<?php //echo JText::_("GURU_TIP_EMAIL_PLANS"); ?>" >
                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                            </span>
                                       </div>
                                        <div class="g_table_wrap">
                                            <div class="g_table clearfix"> 
                                                <div class="">
                                                    <?php// echo $this->emails; ?>
                                                </div>
                                            </div>
                                        </div>   -->  
                                     </div>          
                                    <div class="tab-pane" id="prog_day_req">
                                        <div class="control-group clearfix">
                                             <?php
                                            $mmediam_preq=$this->mmediam_preq;
                                            $table_display = "table";
                                            
                                            if($mmediam_preq == NULL || (is_array($mmediam_preq) && count($mmediam_preq) <= 0)){
                                                $table_display = "none";
                                            }
                                            ?>
                                                <div id="table_courses_id" class="g_table_wrap" style="display:<?php echo $table_display; ?>;">
                                                    <div class="g_table clearfix">  
                                                         <?php
                                                            $old_preq_ids='';
                                                            if($program->id !="" || $program->id != NULL){
                                                                $preq_existing_ids = $this->preq_existing_ids($program->id);	
                                                            }											
                                                        ?>	
                                                        <div id="rowspreq">	
                                                            <div class="g_table_row">
                                                                <div class="g_cell span1 g_table_cell g_th">
                                                                    <div>
                                                                        <div>
                                                                            <?php echo JText::_("GURU_ID"); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="g_cell span1 g_table_cell g_th">
                                                                    <div>
                                                                        <div>
                                                                            <?php echo JText::_("GURU_NAME"); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="g_cell span1 g_table_cell g_th">
                                                                    <div>
                                                                        <div>
                                                                            <?php echo JText::_("GURU_REMOVE"); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <?php
                                                                    $table_rows = "";
                                                                    if($mmediam_preq!=NULL){
                                                                        foreach($mmediam_preq as $element){
                                                                            $table_rows .= "<div id='tr_".$element->media_id."' class='g_table_row'>";
                                                                            $table_rows .= 		"<div class='g_cell span1 g_table_cell'>
                                                                                                    <div>
                                                                                                        <div>".$element->media_id."</div>
                                                                                                        </div>
                                                                                                    </div>";
                                                                            $table_rows .= 		'<div class="g_cell span1 g_table_cell">
                                                                                                    <div>
                                                                                                        <div><a class="a_guru" target="_blank" href="index.php?option=com_guru&controller=guruTasks&task=edit&cid='.$element->media_id.'">'.$element->name."</a></div>
                                                                                                        </div>
                                                                                                    </div>";
                                                                            $table_rows .= 		'<div class="g_cell span1 g_table_cell">
                                                                                                    <div>
                                                                                                        <div>
                                                                                                            <img onclick="deleteCourse(\''.$element->media_id.'\');" alt="delete" src="'.JURI::root()."components/com_guru/images/delete2.gif".'" /></div>
                                                                                                        </div>
                                                                                                    </div>';
                                                                            $table_rows .= "</div>";
                                                                        }
                                                                        echo $table_rows;
                                                                    }
                                                                ?>
                                                         </div>   
                                                      </div>
                                                   </div>				  
                                               </div> 
                                       </div> 
                                       <div class="control-group clearfix">
                                            <h2><?php echo JText::_("GURU_DAY_OPREQ");?>:</h2>
                                            <div class="controls g_cell span9">
                                                <div onmouseover="document.getElementById('editor_clicked').value = 'pre_req';">
                                                    <?php //echo $editorul->display( 'pre_req', ''.stripslashes($program->pre_req),'100%', '220px', '20', '50' ); ?>
                                                    <textarea id="full_bio" name="pre_req" class="useredactor" style="width:100%; height:100px;"><?php echo $program->pre_req; ?></textarea>
                                                </div> 
                                            </div>
                                            <div class="controls g_cell span1">
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DAY_OPREQ"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>           
                                        <div class="control-group clearfix">
                                            <h2><?php echo JText::_("GURU_DAY_PREQBK");?>:</h2>
                                            <div class="controls g_cell span9">
                                                <div onmouseover="document.getElementById('editor_clicked').value = 'pre_req_books';">
                                                    <?php //echo $editorul->display( 'pre_req_books', ''.stripslashes($program->pre_req_books),'100%', '220px', '20', '50' ); ?>
                                                    <textarea id="pre_req_books" name="pre_req_books" class="useredactor" style="width:100%; height:100px;"><?php echo $program->pre_req_books; ?></textarea>
                                                </div> 
                                            </div>
                                            <div class="controls g_cell span1">
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DAY_PREQBK"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>       
                                        <div class="control-group clearfix">
                                            <h2><?php echo JText::_("GURU_DAY_OREQ");?>:</h2>
                                            <div class="controls g_cell span9">
                                                <div onmouseover="document.getElementById('editor_clicked').value = 'reqmts';">
                                                    <?php //echo $editorul->display( 'reqmts', ''.stripslashes($program->reqmts),'100%', '220px', '20', '50' ); ?>
                                                    <textarea id="reqmts" name="reqmts" class="useredactor" style="width:100%; height:100px;"><?php echo $program->reqmts; ?></textarea>
                                                </div>  
                                            </div>
                                            <div class="controls g_cell span1">
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DAY_OREQ"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <input type="hidden" name="id" id="id" value="<?php echo $program->id; ?>" />
                                <input type="hidden" name="task" value="" />
                                <input type="hidden" name="option" value="com_guru" />
                                <input type="hidden" name="controller" value="guruAuthor" />
                                <input type="hidden" name="view" value="guruauthor" />
                                <input type="hidden" name="boxchecked" value="" />
                                <input type="hidden" name="task2" value="edit" />
                                <input type="hidden" name="media_number" value="<?php echo @$n; ?>" id="media_number"/>	
                                <input type="hidden" id="editor_clicked" name="editor_clicked" value="" />
                                <input type="hidden" name="g_page" value="<?php if(intval($program->id) == 0){ echo "courseadd";} else{ echo "courseedit";} ?>">
                                
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
					<?php
                    	}
						elseif($deviceType == "phone"){
							// if device is phone
					?>
                    		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
                                 <div class="page_title">
                        			<h2>
										<?php if ($program->id<1) echo JText::_('GURU_NEWCOURSE'); else echo JText::_('GURU_EDITCOURSE');?>
                                    </h2>    
                                </div>
                               
								<div id="g_registrationformauthorcontent_mobile" class="g_mobile">
                                    <div class="container-fluid">
                                        <div id="accordion" class="accordion">
                                            
                                            <div class="accordionItem">
                                                <h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_GENERAL");?></h3>
                                                <div class="clearfix tab-body  g_accordion-group g_content_active">
                                                    <div class="control-group clearfix">
                                                        <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_PRODNAME");?>: <font color="#ff0000">*</font></label>
                                                        <div class="controls g_cell span9">
                                                            <span>
                                                                <input class="inputbox" type="text" name="name" size="40" maxlength="255" value="<?php echo $program->name; ?>" />
                                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODNAME"); ?>" >
                                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                                </span>
                                                            </span>    
                                                        </div>
                                                    </div>
                                                                                                        
                                                    <div class="control-group">
                                                        <label class="control-label" for="name"><?php echo JText::_("GURU_CATEGPARENT");?>:</label>
                                                        <div class="controls">
                                                            <?php $lists['treecateg'] = $this->list_all(0, "catid", $program->catid, $program->catid); ?>
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_CATEGPARENT"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="control-group">
                                                        <label class="control-label" for="name"><?php echo JText::_("GURU_LEVEL");?>:</label>
                                                        <div class="controls">
                                                            <?php echo $lists['level']; ?>
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_LEVEL"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="g_date_style">
                                                        <div class="control-group clearfix">
                                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_PRODLPBS");?>:</label>
                                                            <div class="controls g_cell span5">
                                                                 <?php echo $lists['published']; ?>
                                                                 <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODLPBS"); ?>" >
                                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                                </span>
                                                            </div>
                                                        </div>  
                                                        <div class="control-group clearfix">
                                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_PRODLSPUB");?>:</label>
                                                            <div class="controls g_cell span5">
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
                                                                echo JHTML::_('calendar', $start_publish, 'startpublish', 'startpublish', $format, array('class'=>'input-small')); ?>
                                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODLSPUB"); ?>" >
                                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                                </span>
                                                            </div>
                                                            
                                                        </div> 
                                                        <div class="control-group clearfix">
                                                            <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_PRODLEPUB");?>:</label>
                                                            <div class="controls g_cell span5">
                                                                <?php
                                                                    if(substr($program->endpublish, 0, 4) == '0000' || $program->id < 1){
                                                                        $end_publish = JText::_('GURU_NEVER');
                                                                    }
                                                                    else{
                                                                        $end_publish = date("".$dateformat."", strtotime($program->endpublish));
                                                                    }
                                                                    echo JHTML::_('calendar', $end_publish, 'endpublish', 'endpublish', $format, array('class'=>'input-small'));
                                                                ?>
                                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODLEPUB"); ?>" >
                                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                                </span>
                                                            </div>
                                                        </div> 
                                                    </div>                
                                                    <div class="control-group clearfix">
                                                        <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_SKIP_MODULE_PAGE");?>:</label>
                                                        <div class="controls g_cell span5">
                                                            <span>
                                                                  <?php
                                                                $skip_module = $program->skip_module;
                                                                if(!isset($skip_module) || $skip_module == ""){
                                                                    $skip_module = 0;
                                                                }
                                                                ?>	
                                                                <input type="hidden" name="skip_module" value="0">
                                                                <?php
                                                                    $checked = '';
                                                                    if($skip_module == 1){
                                                                        $checked = 'checked="checked"';
                                                                    }
                                                                ?>
                                                                <input type="checkbox" <?php echo $checked; ?> value="1" class="ace-switch ace-switch-5" name="skip_module">
                                                                <span class="lbl"></span> 
                                                            </span>    
                                                        </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <label class="control-label" for="name"><?php echo JText::_("GURU_COURSE_TYPE");?>:</label>
                                                        <div class="controls">
															<?php
                                                                $disabled = "";
                                                                if(intval($id) != 0){
                                                                    $disabled = 'disabled="disabled"';
                                                                    echo '<input type="hidden" name="course_type" value="'.$program->course_type.'" />';
                                                                }
                                                            ?>
                                                            <select id="course_type" class="input-medium" <?php echo $disabled; ?> name="course_type" onchange="javascript:guruShowLessons(value)">
                                                                <option value="0" <?php if($program->course_type == "0"){echo 'selected="selected"';} ?>><?php echo  JText::_("GURU_NON_SEQ"); ?></option>
                                                                <option value="1" <?php if($program->course_type == "1"){echo 'selected="selected"';} ?>><?php echo  JText::_("GURU_SEQ");  ?></option>
                                                            </select>
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_SEQ"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="control-group clearfix" id="lessons_release_td" <?php if ($program->course_type ==1){$style="style=display:block;"; echo $style;} else{$style="style=display:none;"; echo $style;} ?>>
                                                        <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_LESSON_RELEASE");?>:</label>
                                                        <div class="controls g_cell span5">
                                                            <?php
																$disabled = "";
																if(intval($id) != 0){
																	$disabled = 'disabled="disabled"';
																	echo '<input type="hidden" name="lesson_release" value="'.$program->lesson_release.'" />';
																}
															?>
														
														   <select id="lesson_release" class="input-medium" <?php echo $disabled; ?> name="lesson_release" onchange="javascript:guruPopupChangeOption()">
															<option value="0" <?php if($program->lesson_release == "0"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_ALL_AT_ONCE"); ?></option>
															<option value="1" <?php if($program->lesson_release == "1"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_ONE_PER_DAY");?></option>
															<option value="2" <?php if($program->lesson_release == "2"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_ONE_PER_W"); ?></option>
															<option value="3" <?php if($program->lesson_release == "3"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_ONE_PER_M"); ?></option>
														   </select>
                                                           <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_LESSON_RELEASE"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="control-group clearfix" id="lessons_show_td"  <?php if ($program->course_type ==1){$style="style=display:block;"; echo $style;} else{$style="style=display:none;"; echo $style;} ?>>
                                                        <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_SHOW_UNRELEASED_LESSONS");?>:</label>
                                                        <div class="controls g_cell span5">
                                                            <select id="lessons_show" class="input-medium" name="lessons_show">
                                                                <option value="1" <?php if($program->lessons_show == "1"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_GRAYED_TEXT");?></option>
                                                                <option value="2" <?php if($program->lessons_show == "2"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_SHOULD_NOT_SHOW"); ?></option>
                                                            </select>
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_SHOW_UNRELEASED_LESSONS"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                     <div class="control-group clearfix">
                                                        <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_FINAL_EXAM");?>:</label>
                                                        <div class="controls g_cell span5">
                                                             <select id="final_quizzes" class="input-medium" name="final_quizzes">
                                                                <option value="0" <?php if($program->id_final_exam == "0"){echo 'selected="selected"';} ?>><?php echo JText::_("GURU_NO_FINAL_EXAM");?></option>
                                                                <?php
                                                                $cid = "0";
                                                                $db = JFactory::getDBO();
																$user = JFactory::getUser();
																$sql = "SELECT `id` as cid, `name` as name FROM #__guru_quiz WHERE `is_final` = 1 and `author`=".intval($user->id)." ORDER by name asc";
																
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
                                                        </div>
                                                    </div>
                                                     <div class="control-group clearfix">
                                                        <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_CERTIFICATE_TERM");?>:</label>
                                                        <div class="controls g_cell span5">
                                                            <select id="certificate_setts" class="input-medium" name="certificate_setts" onchange="javascript:ChangeTermCourse(this.value,'<?php echo $program->id;?>' )">
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
                                                        </div>
                                                        
                                                        <div class="controls g_cell span3" id="avg_certificate" style=" <?php if($program->certificate_term  == "4" || $program->certificate_term  == "6" ){echo 'display:table-row;';}else{echo 'display:none; ';}?>">
                                                            <input onchange="javascript:checkNegativeNb(this.value);" size="5px;" type="text" name="avg_cert" id="avg_cert" value="<?php if($program->id ==""){echo '70';} else{echo $program->avg_certc;} ?>" class="input-mini" /> %
                                                        </div>
                                                    </div>
                                                    <div class="control-group clearfix" id="coursecertifiactemsg" style=" <?php if($program->certificate_term  != "1" && $program->certificate_term  != "0"){echo 'display:block;';}else{echo 'display:none;';}?>">
                                                        <label class="control-label g_cell span3" for="name"><?php echo JText::_("GURU_CERTIFICATE_COURSE_MSG");?>:</label>
                                                        <div class="controls g_cell span5">
                                                            <span>
                                                                <textarea style="float-left" id="coursemessage" name="coursemessage" cols="50" rows="4" maxlength="7000"><?php echo stripslashes($program->certificate_course_msg); ?></textarea>
                                                            </span>    
                                                        </div>
                                                        <div class="controls g_cell span2" style="float-left">
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_CERTIFICATE_COURSE_MSG1"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="accordionItem">
                                                <h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_PRODDESC");?></h3>
                                                <div class="clearfix tab-body  g_accordion-group g_content_active">
                                                    <div class="control-group clearfix">
                                                        <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_PRODDESC");?>:</label>
                                                        <div class="controls g_cell span9">
                                                           <div onmouseover="document.getElementById('editor_clicked').value = 'description';">
                                                                <?php 
                                                                    //echo $editorul->display( 'description', ''.stripslashes($program->description),'100%', '220px', '20', '50' ); ?>
                                                                    <textarea id="description" name="description" class="useredactor" style="width:100%; height:100px;"><?php echo $program->description; ?></textarea>
                                                            </div>      
                                                        </div>
                                                        <div class="controls g_cell span1">
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_PRODDESC"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="accordionItem">
                                                <h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_IMAGE_COVER");?></h3>
                                                <div class="clearfix tab-body  g_accordion-group g_content_active">
                                                    <div class="well">
                                                        <h5><?php echo JText::_('GURU_IMAGE_COVER2');?></h5>
                                                    </div>
                                                    <div class="alert alert-info" >
                                                        <?php echo JText::_('GURU_IMAGE_COVER_USAGE');?>
                                                    </div>
                                                    
                                                    <div class="control-group clearfix">
                                                        <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_IMAGE_COVER2");?>:</label>
                                                            <div class="controls g_cell span5">
                                                                <div style="float:left;">
                                                                    <div id="fileUploader"></div>
                                                                </div> 
                                                            </div>
                                                        <div class="controls g_cell span1">
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_IMAGE"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                        <div class="controls g_cell span4">
                                                            <div style="float:left; padding-left:10px;">
                                                                <?php echo JText::_('GURU_RECOMMENDED_SIZE');?>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="image" id="image" value="<?php echo $program->image; ?>" />&nbsp; 
                                                    </div>
                                                    <div class="control-group clearfix">
                                                        <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_PRODCIMG_COVER");?>:</label>
                                                        <div class="controls g_cell span10">
                                                            <?php
                                                                if(isset($program->image) && $program->image!=""){ 
                                                                
                                                                    ?>
                                                                    <img id="view_imagelist23" name="view_imagelist" src='<?php echo JURI::root();?><?php echo $program->image;?>'/>
                                                                    <br />
                                                                    <input style="margin-top:10px;" type="button" class="btn btn-danger" value="<?php echo JText::_('GURU_REMOVE'); ?>" onclick="deleteImage('<?php echo $program->id; ?>');" id="deletebtn" />
                                                                    <input type="hidden" value="<?php echo $program->image; ?>" name="img_name" id="img_name" />		
                                                                <?php 
                                                                } 
                                                                else {
                                                                    echo "<img id='view_imagelist23' name='view_imagelist' src='".JURI::base()."components/com_guru/images/blank.png'/>";
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="well">
                                                        <h5><?php echo JText::_('GURU_IMAGE_AVATAR');?></h5>
                                                    </div>
                                                    <div class="alert alert-info" >
                                                        <?php echo JText::_('GURU_IMAGE_AVATAR_USAGE');?>
                                                    </div>
                                                    <div class="control-group clearfix">
                                                        <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_IMAGE_AVATAR");?>:</label>
                                                            <div class="controls g_cell span5">
                                                             <div style="float:left;">
                                                                <div id="fileUploader1"></div>
                                                            </div>
                                                            </div>
                                                        <div class="controls g_cell span1">
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_IMAGE"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                        <input type="hidden" name="image_avatar" id="image_avatar" value="<?php echo $program->image_avatar; ?>" />&nbsp; 
                                                    </div>
                                                    <div class="control-group clearfix">
                                                        <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_PRODCIMG_AVATAR");?>:</label>
                                                        <div class="controls g_cell span10">
                                                            <?php
                                                                if(isset($program->image_avatar) && $program->image_avatar!=""){ 
                                                                    ?>
                                                                        <img id="view_imagelist24" name="view_imagelist1" src='<?php echo JURI::root();?><?php echo $program->image_avatar;?>'/>
                                                                    <br />
                                                                    <input style="margin-top:10px;" type="button" class="btn btn-danger" value="<?php echo JText::_('GURU_REMOVE'); ?>" onclick="deleteImageA('<?php echo $program->id; ?>');" id="deletebtn2" />
                                                                    <input type="hidden" value="<?php echo $program->image_avatar; ?>" name="image_avatar2" id="image_avatar2" />		
                                                                <?php 
                                                                } 
                                                                else {
                                                                    echo "<img id='view_imagelist24' name='view_imagelist' src='".JURI::base()."components/com_guru/images/blank.png'/>";
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="accordionItem">
                                                <h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_EXERCISE_FILES");?></h3>
                                                <div class="clearfix tab-body  g_accordion-group g_content_active">
                                                    <div style="float:left;">
                                                    	<a data-toggle="modal" data-target="#myModal" onclick="showContent('<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruauthor&task=addexercise&tmpl=component&cid=<?php echo $program->id;?>')" href="#">
                                                      <?php echo JText::_("GURU_EXERCISE_FILE"); ?>
                                                      </a>&nbsp;
                                                    </div>
                                                    <div style="float:left;">
                                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_EXERCISE_FILE"); ?>" >
                                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                        </span>
                                                    </div>
                                                    <div class="clearfix">
                                                        <div class="g_table_wrap">
                                                            <table id="articleList" class="table table-striped">
                                                                <tr class="g_table_header">
                                                                    <th width="1%"></th>
                                                                    <th width="1%"></th>
                                                                    <th class="g_cell_1 center">#</th>
                                                                    <th class="g_cell_2"><?php echo JText::_('GURU_FILE_MEDIA_NAME'); ?></th>
                                                                    <th class="g_cell_3"><?php echo JText::_('GURU_PUBLISHED'); ?></th>
                                                                    <th class="g_cell_4"><?php echo JText::_("GURU_GUEST_ACCESS"); ?></th>
                                                                    <th class="g_cell_5"><?php echo JText::_("GURU_REMOVE"); ?></th>
                                                                </tr>
                                                               <tbody id="rowsmedia"> 
                                                                <?php 
                                                               $oldids = "";
                                                                $display_none = '';
                                                                if($program->id != NULL){
                                                                    $existing_ids = $this->existing_ids($program->id);
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
                                                               <tr class="guru_row" id="tr<?php echo intval($mmedial->media_id); ?>">
                                                                	<td>
                                                                        <span class="sortable-handler active" style="cursor: move;">
                                                                            <i class="icon-menu"></i>
                                                                        </span>
                                                                        <input type="text" class="width-20 text-area-order " value="<?php echo $mmedial->order; ?>" size="5" name="order[]" style="display:none;">
                                                                    </td>  
                                                                    <td style="visibility:hidden;">
                                                                        <?php $checked = JHTML::_('grid.id', $i, $mmedial->media_id); echo $checked;?>
                                                                    </td>
                                                                    <td class="g_cell_1"><?php echo $i+1; ?></td>
                                                                    <td class="g_cell_2">
                                                                         <?php
                                                                            switch($mmedial->type){
                                                                                case "video":{						
                                                                                             $img_path = JURI::root()."components/com_guru/images/video.gif";
                                                                                             echo '<img src="'.$img_path.'" alt="video type"/>';									
                                                                                            }
                                                                                    break;
                                                                                case "docs":{						
                                                                                             $img_path = JURI::root()."components/com_guru/images/doc.gif";
                                                                                             echo '<img src="'.$img_path.'" alt="video type"/>';									
                                                                                            }
                                                                                    break;
                                                                                case "url":{						
                                                                                             $img_path = JURI::root()."components/com_guru/images/url.gif";
                                                                                             echo '<img src="'.$img_path.'" alt="video type"/>';									
                                                                                            }
                                                                                    break;
                                                                                case "image":{						
                                                                                             $img_path = JURI::root()."components/com_guru/images/image.jpg";
                                                                                             echo '<img src="'.$img_path.'" alt="video type"/>';									
                                                                                            }
                                                                                    break;
                                                                                case "audio":{						
                                                                                             $img_path = JURI::root()."components/com_guru/images/audio.gif";
                                                                                             echo '<img src="'.$img_path.'" alt="audio type"/>';									
                                                                                            }
                                                                                    break;											
                                                                                case "quiz":{						
                                                                                             $img_path = JURI::root()."components/com_guru/images/quiz.gif";
                                                                                             echo '<img src="'.$img_path.'" alt="quiz type"/>';									
                                                                                            } 
                                                                                    break;
                                                                                case "text":{						
                                                                                             $img_path = JURI::root()."components/com_guru/images/doc.gif";
                                                                                             echo '<img src="'.$img_path.'" alt="doc type"/>';									
                                                                                            } 
                                                                                    break;
                                                                                case "file":{						
                                                                                             $img_path = JURI::root()."components/com_guru/images/file.gif";
                                                                                             echo '<img src="'.$img_path.'" alt="doc type"/>';									
                                                                                            } 
                                                                                    break;
                                                                            }
                                                                        ?>	
                                                                         <a class="a_guru" href="index.php?option=com_guru&controller=guruAuthor&task=editMedia&cid=<?php echo $mmedial->media_id;?>" target="_blank"><?php echo $mmedial->name;?></a> 	  
                                                                    </td>
                                                                    <td class="g_cell_3">
                         
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
                                                                    <td class="g_cell_4">
                                                                        <select name="<?php echo "access".$mmedial->media_id ?>">
                                                                            <option value="0" <?php if($mmedial->access == 0) echo "selected"; ?> ><?php echo JText::_("GURU_COU_STUDENTS"); ?></option>
                                                                            <option value="1" <?php if($mmedial->access == 1) echo "selected"; ?> ><?php echo JText::_("GURU_REG_MEMBERS"); ?></option>
                                                                            <option value="2" <?php if($mmedial->access == 2) echo "selected"; ?> ><?php echo JText::_("GURU_REG_GUESTS"); ?></option>
                                                                        </select>          
                                                                    </td>
                                                                    <td class="g_cell_5"><?php 
                                                                            $img_path = "components/com_guru/images/delete2.gif";
                                                                            echo '<img onclick="deleteMedia(\''.$mmedial->media_id.'\');" src="'.$img_path.'" alt="delete"/>';
                                                                        ?>
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
                                                                            <a class="a_guru" href="index.php?option=com_guru&controller=guruAuthor&task=editMedia&cid=<?php echo $mmedial->media_id;?>" target="_blank"><?php echo $more_media_files_val->name;?></a>
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
                                                       </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="accordionItem">
                                                <h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_PRICING_PLANS");?></h3>
                                                <div class="clearfix tab-body  g_accordion-group g_content_active">
                                                	<?php if($config->course_is_free_show == 1){?>
                                                            <div class="clearfix">
                                                                <div class="pull-left"><input onclick="javascript:checkIfCertificateIsSet();" type="checkbox" id= "chb_free_courses" name="chb_free_courses" <?php if(@$chb_free_courses == "1") echo 'checked="checked"'; else { }?>> <span class="lbl"></span></div>
                                                                <div class="pull-left" style=" float:left; padding:0.2em"> <?php echo trim(JText::_('GURU_FREE_COURSES')); ?></div>
                                                                <div class="pull-left">
                                                                    <select id="step_access_courses" name="step_access_courses" onchange="javascript:selectrealtime(this.value); checkIfCertificateIsSet();" class="input-medium">
                                                                        <option value="0" <?php if(@$step_access_courses == 0) echo "selected"; ?> ><?php echo JText::_("GURU_COU_STUDENTS"); ?></option>
                                                                        <option value="1" <?php if(@$step_access_courses == 1) echo "selected"; ?> ><?php echo JText::_("GURU_REG_MEMBERS"); ?></option>
                                                                        <option value="2" <?php if(@$step_access_courses == 2) echo "selected"; ?> ><?php echo JText::_("GURU_REG_GUESTS"); ?></option>
                                                                    </select>
                                                                 </div>
                                                                 <div class="pull-left span5 g_of_select" id="free_courses"> 
                                                                    <span style="float: left; padding-right: 10px;">
                                                                        <?php echo trim(JText::_('GURU_OF'));?>
                                                                    </span> 
                                                                    <?php $this->getCourseListForStudents(); ?>                       
                                                                </div>
                                                            </div>
                                                  <?php }?>  
                    
                                                    <div style="float:left;">
                                                        <?php echo trim(JText::_('GURU_SUBSCRIPTIONS')) . ' ' . trim(strtolower(JText::_('GURU_SUBS_PLANS'))); ?>&nbsp;
                                                    </div>
                                                    <div style="float:left;">
                                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_SUBSCRIPTIONS"); ?>" >
                                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                        </span>
                                                    </div>
                                                    <div class="g_table_wrap">
                                                        <div class="g_table clearfix"> 
                                                            <div class="">
                                                                <?php echo $this->plans; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div style="float:left;">
                                                        <?php echo JText::_('GURU_RENW_PLANS'); ?>&nbsp;
                                                    </div>
                                                    <div style="float:left;">
                                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_RENW_PLANS"); ?>" >
                                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                        </span>
                                                    </div> 
                                                    <div class="g_table_wrap">
                                                        <div class="g_table clearfix"> 
                                                            <div class="">
                                                                <?php echo $this->renewals; ?>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                    <!--<div style="float:left;">
                                                        <?php //echo JText::_('GURU_EMAIL_PLANS'); ?>&nbsp;
                                                    </div>
                                                    <div style="float:left;">
                                                        <span class="editlinktip hasTip" title="<?php //echo JText::_("GURU_TIP_EMAIL_PLANS"); ?>" >
                                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                        </span>
                                                   </div>
                                                    <div class="g_table_wrap">
                                                        <div class="g_table clearfix"> 
                                                            <div class="">
                                                                <?php //echo $this->emails; ?>
                                                            </div>
                                                        </div>
                                                    </div>-->
                                                </div>
                                            </div>
                                            
                                            <div class="accordionItem">
                                                <h3 class="g_accordion-group ui-corner-all g_title_active"><?php echo JText::_("GURU_DAY_REQ");?></h3>
                                                <div class="clearfix tab-body  g_accordion-group g_content_active">
                                                    <div class="control-group clearfix">
														 <?php
                                                        $mmediam_preq=$this->mmediam_preq;
                                                        $table_display = "table";
                                                        
                                                        if($mmediam_preq == NULL || (is_array($mmediam_preq) && count($mmediam_preq) <= 0)){
                                                            $table_display = "none";
                                                        }
                                                        ?>
                                                            <div id="table_courses_id" class="g_table_wrap" style="display:<?php echo $table_display; ?>;">
                                                                <div class="g_table clearfix">  
                                                                     <?php
                                                                        $old_preq_ids='';
                                                                        if($program->id !="" || $program->id != NULL){
                                                                            $preq_existing_ids = $this->preq_existing_ids($program->id);	
                                                                        }											
                                                                    ?>	
                                                                    <div id="rowspreq">	
                                                                        <div class="g_table_row">
                                                                            <div class="g_cell span1 g_table_cell g_th">
                                                                                <div>
                                                                                    <div>
                                                                                        <?php echo JText::_("GURU_ID"); ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="g_cell span1 g_table_cell g_th">
                                                                                <div>
                                                                                    <div>
                                                                                        <?php echo JText::_("GURU_NAME"); ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="g_cell span1 g_table_cell g_th">
                                                                                <div>
                                                                                    <div>
                                                                                        <?php echo JText::_("GURU_REMOVE"); ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <?php
                                                                                $table_rows = "";
                                                                                if($mmediam_preq!=NULL){
                                                                                    foreach($mmediam_preq as $element){
                                                                                        $table_rows .= "<div id='tr_".$element->media_id."' class='g_table_row'>";
                                                                                        $table_rows .= 		"<div class='g_cell span1 g_table_cell'>
                                                                                                                <div>
                                                                                                                    <div>".$element->media_id."</div>
                                                                                                                    </div>
                                                                                                                </div>";
                                                                                        $table_rows .= 		'<div class="g_cell span1 g_table_cell">
                                                                                                                <div>
                                                                                                                    <div><a class="a_guru" target="_blank" href="index.php?option=com_guru&controller=guruTasks&task=edit&cid='.$element->media_id.'">'.$element->name."</a></div>
                                                                                                                    </div>
                                                                                                                </div>";
                                                                                        $table_rows .= 		'<div class="g_cell span1 g_table_cell">
                                                                                                                <div>
                                                                                                                    <div>
                                                                                                                        <img onclick="deleteCourse(\''.$element->media_id.'\');" alt="delete" src="'.JURI::root()."components/com_guru/images/delete2.gif".'" /></div>
                                                                                                                    </div>
                                                                                                                </div>';
                                                                                        $table_rows .= "</div>";
                                                                                    }
                                                                                    echo $table_rows;
                                                                                }
                                                                            ?>
                                                                     </div>   
                                                                  </div>
                                                               </div>				  
                                                           </div> 
                                                   </div> 
                                                   <div class="control-group clearfix">
                                                        <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_DAY_OPREQ");?>:</label>
                                                        <div class="controls g_cell span9">
                                                            <div onmouseover="document.getElementById('editor_clicked').value = 'pre_req';">
                                                                <?php //echo $editorul->display( 'pre_req', ''.stripslashes($program->pre_req),'100%', '220px', '20', '50' ); ?>
                                                                <textarea id="pre_req" name="pre_req" class="useredactor" style="width:100%; height:100px;"><?php echo $program->pre_req; ?></textarea>
                                                            </div> 
                                                        </div>
                                                        <div class="controls g_cell span1">
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DAY_OPREQ"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                    </div>           
                                                    <div class="control-group clearfix">
                                                        <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_DAY_PREQBK");?>:</label>
                                                        <div class="controls g_cell span9">
                                                            <div onmouseover="document.getElementById('editor_clicked').value = 'pre_req_books';">
                                                                <?php //echo $editorul->display( 'pre_req_books', ''.stripslashes($program->pre_req_books),'100%', '220px', '20', '50' ); ?>
                                                                <textarea id="pre_req_books" name="pre_req_books" class="useredactor" style="width:100%; height:100px;"><?php echo $program->pre_req_books; ?></textarea>
                                                            </div> 
                                                        </div>
                                                        <div class="controls g_cell span1">
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DAY_PREQBK"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                    </div>       
                                                    <div class="control-group clearfix">
                                                        <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_DAY_OREQ");?>:</label>
                                                        <div class="controls g_cell span9">
                                                            <div onmouseover="document.getElementById('editor_clicked').value = 'reqmts';">
                                                                <?php //echo $editorul->display( 'reqmts', ''.stripslashes($program->reqmts),'100%', '220px', '20', '50' ); ?>
                                                                <textarea id="reqmts" name="reqmts" class="useredactor" style="width:100%; height:100px;"><?php echo $program->reqmts; ?></textarea>
                                                            </div>  
                                                        </div>
                                                        <div class="controls g_cell span1">
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_DAY_OREQ"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <input type="hidden" name="id" id="id" value="<?php echo $program->id; ?>" />
                                <input type="hidden" name="task" value="" />
                                <input type="hidden" name="option" value="com_guru" />
                                <input type="hidden" name="controller" value="guruAuthor" />
                                <input type="hidden" name="view" value="guruauthor" />
                                <input type="hidden" name="boxchecked" value="" />
                                <input type="hidden" name="task2" value="edit" />
                                <input type="hidden" name="media_number" value="<?php echo @$n; ?>" id="media_number"/>	
                                <input type="hidden" id="editor_clicked" name="editor_clicked" value="" />
                                <input type="hidden" name="g_page" value="<?php if(intval($program->id) == 0){ echo "courseadd";} else{ echo "courseedit";} ?>">
                      	</form>          
                    <?php
						}
						
						$upload_script = 'window.addEvent( "domready", function(){
											jQuery(".useredactor").redactor({
												 buttons: [\'bold\', \'italic\', \'underline\', \'link\', \'alignment\', \'unorderedlist\', \'orderedlist\']
											});
											jQuery(".redactor_useredactor").css("height","400px");
										  });';
						$doc->addScriptDeclaration($upload_script);
					?>
				</div>
                
                <div class="g_inline_child clearfix">
                        <input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE"); ?>" onclick="javascript:saveCourse('apply');" />
                        <input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE_AND_CLOSE"); ?>" onclick="javascript:saveCourse('save');" />
                        <input type="button" class="btn btn-inverse" value="<?php echo JText::_("GURU_CLOSE"); ?>" onclick="document.location.href='<?php echo JRoute::_("index.php?option=com_guru&view=guruauthor&task=authormycourses&layout=authormycourses"); ?>';" />
                </div>
                
			</div>
		</div> 
	</div>  
</div>                  