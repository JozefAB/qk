<?php

/*------------------------------------------------------------------------
# com_guru
# ------------------------------------------------------------------------
# author    iJoomla
# copyright Copyright (C) 2013 ijoomla.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.ijoomla.com
# Technical Support:  Forum - http://www.ijoomla.com.com/forum/index/
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

require_once (JPATH_COMPONENT.DS.'controller.php');
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );
$controller = JRequest::getWord('controller');
if($controller){
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if(file_exists($path)){
		require_once($path);
	} 
	else{
	 	$controller = '';
	}
}
$ajax_req = JRequest::getVar("no_html", 0, "request");
$tmpl = JRequest::getVar("tmpl", "");
$squeeze = JRequest::getVar("sbox", 0, "request");
$task = JRequest::getVar("task", "");
$p = JRequest::getVar("p", "0");

$export = JRequest::getVar("export", "");
$export1 = JRequest::getVar("export1", "");

if($export != "" || $export1 != "" ){
	$classname = "guruAdminController".$controller;
    $ajax_req = JRequest::getVar("no_html", 0, "request");
	$tmpl = JRequest::getVar("tmpl", "");
    $squeeze = JRequest::getVar("sbox", 0, "request");
    
    $controller = new $classname();
	$controller->execute ($task);
	$controller->redirect();
}
elseif(!$ajax_req && $tmpl !="component" && $task != "savesbox"&& $task != "save2" && $task != "export_button" && $task != "export" && $task != "savequizzes" && $task !="savequestionedit" && $task != "savequestion" && $task != "list_of_modules"){
?>
<div id="js-cpanel">
	<?php
    $classname = "guruAdminController".$controller;
    $ajax_req = JRequest::getVar("no_html", 0, "request");
    $tmpl = JRequest::getVar("tmpl", "");
    $squeeze = JRequest::getVar("sbox", 0, "request");
    $task = JRequest::getVar("task", "");
    
    $controller = new $classname();
    $task = JRequest::getWord('task');

    ?>
	<div id="admin_content_wrapper">
		<?php
        if (!$ajax_req && $tmpl !="component" && $task != "savesbox"&& $task != "save2" && $task != "export_button" && $task != "export" && $task != "savequizzes" && $task !="savequestionedit" && $task != "savequestion"){
        require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'gurusidebar.php');
        }
        ?>
        <!--end sidebar-->
        <div class="main-content">
        	
           
        	<div class="page-content">
            <div class="page-header clearfix no-padding">
            	<?php 

					$pageTitle = ""; 
					$image_guru_top = "";
					$tab = JRequest::getVar("tab", "");
					$e = JRequest::getVar("e", "");
					
					switch ($classname) {
						case "guruAdminController":
							$pageTitle = JText::_('GURU_DASHBOARD');	
							$image_guru_top = '<a href="http://www.ijoomla.com" target="_blank"><img src="components/com_guru/images/ijoomla-logo.png"></a>';
							break;
						case "guruAdminControllerguruConfigs":
							$pageTitle = JText::_('GURU_SETTINGS');
							if($tab == 0){
								$pageTitle .=" ".">"." ".JText::_('GURU_GENERAL');
							}
							if($tab == 2){
								$pageTitle .=" ".">"." ".JText::_('GURU_LAYOUT');
							}
							if($tab == 5){
								$pageTitle .=" ".">"." ".JText::_('GURU_EMAIL');
							}
							if($tab == 6){
								$pageTitle .=" ".">"." ".JText::_('GURU_CONTENT');
							}
							if($tab == 1){
								$pageTitle = JText::_('GURU_MEDIA');
							}
							if($tab == 4){
								$pageTitle = JText::_('GURU_PROGRESS_BAR');
							}
							break;
						case "guruAdminControllerguruLanguages":
							$pageTitle = JText::_('GURU_SETTINGS')." ".">"." ".JText::_('GURU_TREELANGUAGES');
							break;
						case "guruAdminControllerguruAuthor":
							if($task == "list"){
								$pageTitle = JText::_('GURU_TREEAUTHOR');
							}
							if($task == "edit" || $task == "next"){
								$pageTitle = JText::_('GURU_AU_AUTHOR_DETAILS');
							}
							break;
						case "guruAdminControllerguruCustomers":
							if($task == ""){
								$pageTitle = JText::_('GURU_TREECUSTOMERS');
							}
							if($task == "edit"){
								$pageTitle = JText::_('GURU_STUDENT_DETAILS');
							}
							break;
						case "guruAdminControllerguruPrograms":
							if($task != "selectCourse" && $task != "edit"  ){
								$pageTitle = JText::_('GURU_COURSEMAN');
							}
							if($task == "edit"){
								$pageTitle = JText::_('GURU_EDITCOURSE');
							}
							break;
						case "guruAdminControllerguruPcategs":
							if($task == "add"){
								$pageTitle = JText::_('GURU_NEWCATEGORY');
							}
							if($task == "edit"){
								$pageTitle = JText::_('GURU_EDITCATEGORY');
							}
							if($task == ""){
								$pageTitle = JText::_('GURU_CSCAT_MANAGER');
							}
							break;
						case "guruAdminControllerguruKunenaForum":
							$pageTitle = JText::_('GURU_KUNENA_FORUM1');
							break;	
						case "guruAdminControllerguruMedia":
							if($task != "mass"){
								$pageTitle = JText::_('GURU_TREEMEDIA');
							}
							elseif($task == "mass"){
								$pageTitle = JText::_('GURU_MEDIA_MASS_TITLE');
							}
							break;	
						case "guruAdminControllerguruMediacategs":
							$pageTitle = JText::_('GURU_MEDIACATEGS');
							break;	
						case "guruAdminControllerguruQuiz":
							if($task == ""){
								$pageTitle = JText::_('GURU_Q_QUIZ_MANAGER');
							}
							if($task == "editZ"){
								$pageTitle = JText::_('GURU_NEWQUIZ');
							}
							if($task == "edit" && $e == 1){
								$pageTitle = JText::_('GURU_EDITQUIZ');
							}
							break;	
						case "guruAdminControllerguruQuizCountdown":
							$pageTitle = JText::_('GURU_Q_QUIZ_MANAGER');
							break;
						case "guruAdminControllerguruCertificate":
							$pageTitle = JText::_('GURU_CERTIFICATES_MANAGER');
							break;	
						case "guruAdminControllerguruOrders":
							if($task == ""){
								$pageTitle = JText::_('GURU_ORDER_MANAGER_TITLE');
							}
							
							break;	
						case "guruAdminControllerguruPromos":
							if($task == ""){
								$pageTitle = JText::_('GURU_PROMO_CODES_MANAGER');
							}
							if($task == "edit"){
								if(JRequest::getVar("cid", "") > 0){
									$pageTitle = JText::_('GURU_EDITPROMO');
								}
								else{
									$pageTitle = JText::_('GURU_ADDPROMO');
								}
							}
							break;	
						case "guruAdminControllerguruPlugins":
							$pageTitle = JText::_('GURU_PLUGINS_MANAGER');
							break;	
						case "guruAdminControllerguruSubplan":
							if($task == ""){
								$pageTitle = JText::_('GURU_PLANS_MANAGER');
							}
							if($task == "edit"){
								$pageTitle = JText::_('GURU_EDIT_SUBPL');
							}
							break;
						case "guruAdminControllerguruSubremind":
							if($task == ""){
								$pageTitle = JText::_('GURU_REMINDS_MANAGER');
							}
							if($task == "edit"){
								$pageTitle = JText::_('GURU_EDIT')." ".JText::_('GURU_EMAIL_REMIND');
							}
							break;	
						case "guruAdminControllerguruAbout":
							$pageTitle = JText::_('GURU_ABOUT_TITLE');
							break;
						case "guruAdminControllerguruDays":
							$pageTitle = JText::_('GURU_EDITCOURSE');
							break;
						case "guruAdminControllerguruCommissions":
							if($task == "edit"){
								if(JRequest::getVar("cid", "") > 0){
									$pageTitle = JText::_('GURU_COMMISSION_PLAN')." ".">"." ".JText::_('GURU_EDIT');
								}
								else{
									$pageTitle = JText::_('GURU_COMMISSION_PLAN')." ".">"." ".JText::_('GURU_NEW');
								}
							}
							if($task == "add"){
								$pageTitle = JText::_('GURU_COMMISSION_PLAN')." ".">"." ".JText::_('GURU_NEW');
							}
							if($task == "list"){
								$pageTitle = JText::_('GURU_COMMISSION_PLAN');
							}
							if($task == "history"){
								$pageTitle = JText::_('GURU_COMMISSIONS')." ".">"." ".JText::_('GURU_PAID_SUMMARY');
							}
							if($task == "pending"){
								$pageTitle = JText::_('GURU_COMMISSIONS')." ".">"." ".JText::_('GURU_AU_PENDING');
							}
							if($task == "paid"){
								$pageTitle = JText::_('GURU_COMMISSIONS')." ".">"." ".JText::_('GURU_PAID_SUMMARY_P');
							}
							break;			
							 
						default:
						   ;
					}
?>
            	<h2 class="pull-left"><?php echo $pageTitle; ?></h2>
                <div class="pull-right"><?php echo $image_guru_top; ?></div>
			</div>
			<?php
            $controller->execute ($task);
            $controller->redirect();
            ?>
            </div>
            
            <!-- -------------------------------------------------------------- -->
            <?php
				if($classname == "guruAdminControllerguruAuthor" && $task == "list"){
			?>
                    <div id="new-options" style="position:relative;">
                        <input type="button" id="new-options-button" onclick="editOptions();" class="btn btn-success g_toggle_button" value="<?php echo JText::_('GURU_NEW_TEACHER').'&nbsp; &#9660;'; ?>">
                        <div id="button-options" class="button-options" style="display:none;">
                            <ul style="font-size: 14px;">
                                <li>
                                    <a href="index.php?option=com_guru&controller=guruAuthor&task=edit&author_type=0">
										<?php echo JText::_("GURU_NEW_TECHER_DROP"); ?>
                                    </a>
								</li>
                                <li>
                                    <a href="#" onclick="Joomla.submitbutton('new');">
										<?php echo JText::_("GURU_EXISTING"); ?>
                                    </a>
								</li>
                            </ul>
                        </div>
                    </div>
			<?php
            	}
				elseif($classname == "guruAdminControllerguruCustomers" && $task == ""){
			?>
            		<div id="new-options"  style="position:relative;">
                        <input type="button" id="new-options-button" onclick="editOptions();" class="btn btn-success g_toggle_button" value="<?php echo JText::_('GURU_NEW_STUDENT').'&nbsp; &#9660;'; ?>">
                        <div id="button-options" class="button-options" style="display:none;">
                            <ul style="font-size: 14px;">
                                <li>
                                    <a href="index.php?option=com_guru&controller=guruCustomers&task=edit">
										<?php echo JText::_("GURU_NEW_TECHER_DROP"); ?>
                                    </a>
								</li>
                                <li>
                                    <a href="#" onclick="Joomla.submitbutton('add');">
										<?php echo JText::_("GURU_EXISTING"); ?>
                                    </a>
								</li>
                            </ul>
                        </div>
                    </div>
            <?php
				}
				elseif($classname == "guruAdminControllerguruQuiz" && $task == ""){
			?>
            		<div id="new-options"  style="position:relative;">
                        <input type="button" id="new-options-button" onclick="editOptions();" class="btn btn-success g_toggle_button" value="<?php echo JText::_('GURU_NEWQUIZ').'&nbsp; &#9660;'; ?>">
                        <div id="button-options" class="button-options" style="display:none;">
                            <ul style="font-size: 14px;">
                                <li>
                                    <a href="index.php?option=com_guru&controller=guruQuiz&task=edit&v=0">
										<?php echo JText::_("GURU_REGULAR_QUIZ"); ?>
                                    </a>
								</li>
                                <li>
                                    <a href="index.php?option=com_guru&controller=guruQuiz&task=edit&v=1">
										<?php echo JText::_("GURU_FINAL_EXAM_QUIZ"); ?>
                                    </a>
								</li>
                            </ul>
                        </div>
                    </div>
            <?php
				}
				elseif($classname == "guruAdminControllerguruMedia" && $task == ""){
			?>
            		<div id="new-options"  style="position:relative;">
                        <input type="button" id="new-options-button" onclick="editOptions();" class="btn btn-success g_toggle_button" value="<?php echo JText::_('GURU_TREE_NEW_MEDIA').'&nbsp; &#9660;'; ?>">
                        <div id="button-options" class="button-options" style="display:none;">
                            <ul style="font-size: 14px;">
                                <li>
                                    <a href="index.php?option=com_guru&controller=guruMedia&task=edit&type=video">
										<?php echo JText::_("GURU_VIDEO"); ?>
                                    </a>
								</li>
                                <li>
                                    <a href="index.php?option=com_guru&controller=guruMedia&task=edit&type=audio">
										<?php echo JText::_("GURU_AUDIO"); ?>
                                    </a>
								</li>
                                <li>
                                    <a href="index.php?option=com_guru&controller=guruMedia&task=edit&type=docs">
										<?php echo JText::_("GURU_DOCS"); ?>
                                    </a>
								</li>
                                <li>
                                    <a href="index.php?option=com_guru&controller=guruMedia&task=edit&type=url">
										<?php echo JText::_("GURU_URL"); ?>
                                    </a>
								</li>
                                <li>
                                    <a href="index.php?option=com_guru&controller=guruMedia&task=edit&type=Article">
										<?php echo JText::_("GURU_ARTICLE"); ?>
                                    </a>
								</li>
                                <li>
                                    <a href="index.php?option=com_guru&controller=guruMedia&task=edit&type=image">
										<?php echo JText::_("GURU_IMAGE"); ?>
                                    </a>
								</li>
                                <li>
                                    <a href="index.php?option=com_guru&controller=guruMedia&task=edit&type=text">
										<?php echo JText::_("GURU_TEXT"); ?>
                                    </a>
								</li>
                                <li>
                                    <a href="index.php?option=com_guru&controller=guruMedia&task=edit&type=file">
										<?php echo JText::_("GURU_FILE_LOWER"); ?>
                                    </a>
								</li>
                            </ul>
                        </div>
                    </div>
            <?php
				}
				elseif($classname == "guruAdminControllerguruCommissions" && $task == "history"){
			?>
            		<div id="new-options"  style="position:relative;">
                        <input type="button" id="new-options-button" onclick="editOptions();" class="btn btn-success g_toggle_button" value="<?php echo JText::_('GURU_EXPORT').'&nbsp; &#9660;'; ?>">
                        <div id="button-options" class="button-options" style="display:none;">
                            <ul style="font-size: 14px;">
                                <li>
                                    <a href="#" onclick="document.getElementById('export').value='csv'; document.adminForm.submit();">
										<?php echo JText::_("GURU_CSV"); ?>
                                    </a>
								</li>
                                 <?php
								/*if(file_exists(JPATH_SITE.DS."components".DS."com_guru".DS."helpers".DS."MPDF".DS."mpdf.php")){
								?>
                                    <li>
                                         <a href="#" onclick="document.getElementById('export').value='pdf'; document.adminForm.submit();">
                                            <?php echo JText::_("GURU_PDF_EXPORT"); ?>
                                        </a>
                                    </li>
                                <?php
								}
								else{
								?>
                                    <li>
                                        <a href="#" onclick="document.getElementById('message_lib').style.display='block';"><?php echo JText::_("GURU_PDF_EXPORT"); ?></a>
                                    </li>
								<?php
								}*/
							?>    
                            </ul>
                        </div>
                    </div>
            <?php
				}
				elseif($classname == "guruAdminControllerguruCommissions" && $task == "pending"){
			?>
            		<div id="new-options"  style="position:relative;">
                        <input type="button" id="new-options-button" onclick="editOptions();" class="btn btn-success g_toggle_button" value="<?php echo JText::_('GURU_EXPORT').'&nbsp; &#9660;'; ?>">
                        <div id="button-options" class="button-options" style="display:none;">
                            <ul style="font-size: 14px;">
                                 <li>
                                    <a href="#" onclick="document.getElementById('export').value='csv'; document.adminForm.submit();">
										<?php echo JText::_("GURU_CSV"); ?>
                                    </a>
								</li>
                                 <?php
								/*if(file_exists(JPATH_SITE.DS."components".DS."com_guru".DS."helpers".DS."MPDF".DS."mpdf.php")){
								?>
                                    <li>
                                         <a href="#" onclick="document.getElementById('export').value='pdf'; document.adminForm.submit();">
                                            <?php echo JText::_("GURU_PDF_EXPORT"); ?>
                                        </a>
                                    </li>
                                <?php
								}
								else{
								?>
                                    <li>
                                        <a href="#" onclick="document.getElementById('message_lib').style.display='block';"><?php echo JText::_("GURU_PDF_EXPORT"); ?></a>
                                    </li>
								<?php
								}*/
							?>    
                            </ul>
                        </div>
                    </div>
            <?php
				}
            	elseif($classname == "guruAdminControllerguruCommissions" && $task == "paid"){
			?>
            		<div id="new-options"  style="position:relative;">
                        <input type="button" id="new-options-button" onclick="editOptions();" class="btn btn-success g_toggle_button" value="<?php echo JText::_('GURU_EXPORT').'&nbsp; &#9660;'; ?>">
                        <div id="button-options" class="button-options" style="display:none;">
                            <ul style="font-size: 14px;">
                                <li>
                                    <a href="#" onclick="document.getElementById('export').value='csv'; document.adminForm.submit();">
										<?php echo JText::_("GURU_CSV"); ?>
                                    </a>
								</li>
                                 <?php
								/*if(file_exists(JPATH_SITE.DS."components".DS."com_guru".DS."helpers".DS."MPDF".DS."mpdf.php")){
								?>
                                    <li>
                                         <a href="#" onclick="document.getElementById('export').value='pdf'; document.adminForm.submit();">
                                            <?php echo JText::_("GURU_PDF_EXPORT"); ?>
                                        </a>
                                    </li>
                                <?php
								}
								else{
								?>
                                    <li>
                                        <a href="#" onclick="document.getElementById('message_lib').style.display='block';"><?php echo JText::_("GURU_PDF_EXPORT"); ?></a>
                                    </li>
								<?php
								}*/
							?>    
                            </ul>
                        </div>
                    </div>
            <?php
				}
			?>
            <!-- -------------------------------------------------------------- -->
            
			<script>
				function editOptions(){
					display = document.getElementById("button-options").style.display;
					
					if(display == "none"){
						document.getElementById("button-options").style.display = "";
						document.getElementById("new-options").value = "<?php echo JText::_('GURU_NEW'); ?> \u2227";
					}
					else{
						document.getElementById("button-options").style.display = "none";
						document.getElementById("new-options").value = "<?php echo JText::_('GURU_NEW'); ?> \u2228";
					}
				}
			
				// move the Joomla button toolbar to the layout
				jQuery("#toolbar").addClass("pull-right no-margin").prependTo(".page-header");
				
				jQuery("#new-options").addClass("pull-left no-margin").prependTo("#toolbar");
            </script>
    	</div> <!--end main content-->
    </div>
</div><!-- end js-cpanel -->
<?php 
}
else{
	echo '<div id="js-cpanel">';
	$classname = "guruAdminController".$controller;
    $ajax_req = JRequest::getVar("no_html", 0, "request");
	$tmpl = JRequest::getVar("tmpl", "");
    $squeeze = JRequest::getVar("sbox", 0, "request");
    
    $controller = new $classname();
	$controller->execute ($task);
	$controller->redirect();
	echo '</div>';
}?>