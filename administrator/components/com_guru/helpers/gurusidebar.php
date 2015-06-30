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

jimport('joomla.application.component.modellist');
jimport('joomla.utilities.date');

$controller_req = JRequest::getVar("controller", "");
$task = JRequest::getVar("task", "");
$tab_req = JRequest::getVar("tab", "");
$display_settings = "none";
$display_managers = "none";
$display_courses = "none";
$display_media = "none";
$display_quizzes = "none";
$display_finances = "none";
$display_subscription = "none";
$display_icon1 = "none";
$display_icon2 = "none";
$display_icon3 = "none";
$db = JFactory::getDBO();
$sql = " SELECT count(a.id) FROM #__guru_authors a, #__users u WHERE a.enabled=2 and a.userid=u.id";
$db->setQuery($sql);
$db->query();
$pending_authors = $db->loadColumn();
$pending_authors = $pending_authors[0];
												
$li_settings = "";
$li_managers = "";
$li_courses = "";
$li_media = "";
$li_quizzes = "";
$li_finances = "";
$li_subscription = "";

if(($controller_req == "guruConfigs" && $tab_req != 4 && $tab_req != 1) || $controller_req == "guruLanguages"){
	$display_settings = "block";
	$li_settings = 'class="open"';
}
elseif($controller_req == "guruAuthor" || $controller_req == "guruCustomers"){
	$display_managers = "block";
	$li_managers = 'class="open"';
}
elseif($controller_req == "guruPrograms" || $controller_req == "guruPcategs" || ($controller_req == "guruConfigs" && $tab_req == 4) || $controller_req == "guruKunenaForum"){
	$display_courses = "block";
	$li_courses = 'class="open"';
}
elseif($controller_req == "guruMedia" || $controller_req == "guruMediacategs" || ($controller_req == "guruConfigs" && $tab_req == 1)){
	$display_media = "block";
	$li_edia = 'class="open"';
}
elseif($controller_req == "guruQuiz" || $controller_req == "guruQuizCountdown"){
	$display_quizzes = "block";
	$li_quizzes = 'class="open"';
}
elseif($controller_req == "guruOrders" || $controller_req == "guruPromos" || $controller_req == "guruPlugins"){
	$display_finances = "block";
	$li_finances = 'class="open"';
}
elseif($controller_req == "guruSubplan" || $controller_req == "guruSubremind"){
	$display_subscription = "block";
	$li_subscription = 'class="open"';
}
elseif($controller_req == "guruCommissions" && $task == 'list'){
	$display_settings = "block";
}
elseif($controller_req == "guruCommissions" && $task != 'list'){
	$display_finances = "block";
	if($task == 'pending'){
		$display_icon1 ="inline-block";
	}
	elseif($task == 'paid'){
		$display_icon2 ="inline-block";
	}
	elseif($task == 'history'){
		$display_icon3 ="inline-block";
	}
}

?>
<div id="sidebar" class="sidebar">
    <ul class="nav nav-list">
     <li <?php if($controller_req == ""){ echo 'class="active"';} ?>>
        <a href="index.php?option=com_guru">
            <i class="icon-home"></i>
            <span class="menu-text"> <?php echo JText::_("GURU_DASHBOARD"); ?></span>
            
        </a>
     </li><!--end home-->
     
     <li <?php echo $li_settings; ?>>
        <a class="dropdown-toggle" href="#">
            <i class="icon-wrench"></i>
            <span class="menu-text">  <?php echo JText::_("GURU_TREESETTINGS"); ?> </span>
            <b class="arrow js-icon-angle-down"></b>
        </a>
        <ul class="submenu" style="display:<?php echo $display_settings; ?>;">
            <li <?php if($controller_req == "guruConfigs" && $tab_req == 0){ echo 'class="active"';} ?> >
                <a href="index.php?option=com_guru&controller=guruConfigs&tab=0">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_GENERAL"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruConfigs" && $tab_req == 2){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruConfigs&tab=2">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_LAYOUT"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruConfigs" && $tab_req == 5){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruConfigs&tab=5">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TREEEMAILS"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruConfigs" && $tab_req == 6){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruConfigs&tab=6">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_PROMOTION_BOX"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruLanguages"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruLanguages&task=edit">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TREELANGUAGES"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruConfigs" && $tab_req == 8){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruConfigs&tab=8">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TEACHERS"); ?>
                </a>
            </li>
             <li <?php if($controller_req == "guruCommissions" && $task == 'list'){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruCommissions&task=list">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_COMMISSION_PLAN"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruConfigs" && $tab_req == 9){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruConfigs&tab=9">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_REGISTRTION"); ?>
                </a>
            </li>
        </ul>
     </li><!--end settings-->
     <li <?php echo $li_managers; ?>>
         <a class="dropdown-toggle" href="#">
            <i class="icon-user"></i>
             <span class="menu-text">  <?php echo JText::_("GURU_TREEMANAGERS"); ?> </span>
             <b class="arrow js-icon-angle-down"></b>
    
        </a>
        <ul class="submenu" style="display:<?php echo $display_managers; ?>;">
            <li <?php if($controller_req == "guruAuthor"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruAuthor&task=list">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TREEAUTHOR"); ?>
                    <?php 
					if(intval($pending_authors) > 0){?>
                    	<span class="badge badge-important"><?php echo $pending_authors;?></span>
                    <?php 
					}?>
                </a>
                
            </li>
            <li <?php if($controller_req == "guruCustomers"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruCustomers">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TREECUSTOMERS"); ?>
                </a>
            </li>
        </ul>
     </li><!--end managers-->
     <li <?php echo $li_courses; ?>>
       <a class="dropdown-toggle" href="#">
            <i class="icon-eye-open"></i>
             <span class="menu-text">  <?php echo JText::_("GURU_TREECOURSE"); ?> </span>
             <b class="arrow js-icon-angle-down"></b>
    
        </a>
        <ul class="submenu" style="display:<?php echo $display_courses; ?>;">
            <li <?php if($controller_req == "guruPrograms"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruPrograms">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TREECOURSE"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruPcategs"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruPcategs">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TREECOURSECAT"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruConfigs" && $tab_req == 4 ){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruConfigs&tab=4">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_PROGRESS_BAR"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruKunenaForum"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruKunenaForum">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_KUNENA_FORUM1"); ?>
                </a>
            </li>
        </ul>
    </li><!--end courses-->
    <li <?php echo $li_media; ?>>
        <a class="dropdown-toggle" href="#">
            <i class="icon-picture"></i>
             <span class="menu-text">  <?php echo JText::_("GURU_TASK_MEDIA"); ?> </span>
             <b class="arrow js-icon-angle-down"></b>
    
        </a>
        <ul class="submenu" style="display:<?php echo $display_media; ?>;">
            <li <?php if($controller_req == "guruMedia" && $task == "edit"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruMedia&task=edit&cid[]=0">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TREE_NEW_MEDIA"); ?>
                </a>
            </li>
			<li <?php if($controller_req == "guruMedia" && $task == "mass"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruMedia&task=mass">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TREE_NEW_MEDIA_MASS"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruMedia" && $task != "edit" && $task != "mass"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruMedia">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TREEMEDIA"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruMediacategs"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruMediacategs">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TREEMEDIACAT"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruConfigs" && $tab_req == 1){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruConfigs&tab=1">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_MEDIA"); ?>
                </a>
            </li>
        </ul>
    </li><!--end media-->
    <li <?php echo $li_quizzes; ?>>
        <a class="dropdown-toggle" href="#">
            <i class="icon-question-sign"></i>
            <span class="menu-text">  <?php echo JText::_("GURU_TREEQUIZ"); ?> </span>
           <b class="arrow js-icon-angle-down"></b>
    
        </a>
        <ul class="submenu" style="display:<?php echo $display_quizzes; ?>;">
            <li <?php if($controller_req == "guruQuiz"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruQuiz">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TREEQUIZ"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruQuizCountdown"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruQuizCountdown">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_QUIZ_COUNTD"); ?>
                </a>
            </li>
        </ul>
    </li><!--end quiz-->
    <li>
        <a href="index.php?option=com_guru&controller=guruCertificate">
            <i class="icon-bookmark"></i>
            <span class="menu-text"><?php echo JText::_("GURU_CERTIFICATE"); ?> </span>
        </a>
    </li><!--end certificate-->
    <li <?php echo $li_finances; ?>>
        <a class="dropdown-toggle" href="#">
            <i class="icon-cart"></i>
            <span class="menu-text">  <?php echo JText::_("GURU_FINANCES"); ?> </span>
            <b class="arrow js-icon-angle-down"></b>
    
        </a>
        <ul class="submenu" style="display:<?php echo $display_finances; ?>;">
            <li <?php if($controller_req == "guruOrders"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruOrders">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TREEORDERS"); ?>
                </a>
            </li>
            <!-- start Orders Commissions -->
            <li>
                <a class="dropdown-toggle" href="#">
                	<i class="js-icon-double-angle-right"></i>
                    <span class="menu-text">  <?php echo JText::_("GURU_COMMISSIONS"); ?> </span>
                    <b class="arrow js-icon-angle-down"></b>
                </a>
                 <ul class="submenu" style="display:<?php echo $display_finances; ?>;">
                     <li <?php if($controller_req == "guruCommissions" && $task == 'pending'){ echo 'class="active"';} ?>>
                        <a href="index.php?option=com_guru&controller=guruCommissions&task=pending" onclick="document.getElementById('iconar').style.display='inline-block';">
                            <i id="iconar" class="js-icon-double-angle-right" style="display:<?php echo $display_icon1; ?>;"></i>
                            <?php echo JText::_("GURU_AU_PENDING"); ?>
                        </a>
                    </li>
                     <li <?php if($controller_req == "guruCommissions" && $task == 'paid'){ echo 'class="active"';} ?>>
                        <a href="index.php?option=com_guru&controller=guruCommissions&task=paid" onclick="document.getElementById('iconar2').style.display='inline-block';">
                            <i id="iconar2" class="js-icon-double-angle-right" style="display:<?php echo $display_icon2; ?>;"></i>
                            <?php echo JText::_("GURU_O_PAID"); ?>
                        </a>
                    </li>
                     <li <?php if($controller_req == "guruCommissions" && $task == 'history'){ echo 'class="active"';} ?>>
                        <a href="index.php?option=com_guru&controller=guruCommissions&task=history" onclick="document.getElementById('iconar3').style.display='inline-block';">
                            <i id="iconar3" class="js-icon-double-angle-right" style="display:<?php echo $display_icon3; ?>;"></i>
                            <?php echo JText::_("GURU_COMMISSIONS_HISTORY"); ?>
                        </a>
                    </li>
                </ul>    
            </li>
            <!--end Orders Commissions -->
            <li <?php if($controller_req == "guruPromos"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruPromos">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_TREEPROMOS"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruPlugins"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruPlugins">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_PAYMENT_PLUGINS"); ?>
                </a>
            </li>
        </ul>
     </li><!--end finances-->
     <li <?php echo $li_subscription; ?>>
        <a class="dropdown-toggle" href="#">
            <i class="icon-archive"></i>
            <span class="menu-text">  <?php echo JText::_("GURU_SUBSCRIPTIONS"); ?> </span>
            <b class="arrow js-icon-angle-down"></b>
        </a>
        <ul class="submenu" style="display:<?php echo $display_subscription; ?>;">
            <li <?php if($controller_req == "guruSubplan"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruSubplan">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_SUBS_PLANS"); ?>
                </a>
            </li>
            <li <?php if($controller_req == "guruSubremind"){ echo 'class="active"';} ?>>
                <a href="index.php?option=com_guru&controller=guruSubremind">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("GURU_EMAIL_REMIND"); ?>
                </a>
            </li>
        </ul>
    </li><!--end subscriptions-->
    <li>
        <a class="dropdown-toggle" href="#">
            <i class="icon-question-sign"></i>
            <span class="menu-text">  <?php echo JText::_("GURU_HELP"); ?> </span>
            <b class="arrow js-icon-angle-down"></b>
        </a>
        <ul class="submenu">
            <li class="">
                <a target="_blank" href="http://www.ijoomla.com/redirect/general/support.htm">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("VIEWDSADMINSUPPORT"); ?>
                </a>
            </li>
            <li class="">
                <a target="_blank" href="http://www.ijoomla.com/redirect/guru/manual.htm">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("VIEWDSADMINMANUAL"); ?>
                </a>
            </li>
            <li class="">
                <a target="_blank" href="http://www.ijoomla.com/redirect/guru/forum.htm">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("VIEWDSADMINFORUM"); ?>
                </a>
            </li>
            <li class="">
                <a target="_blank" href="http://www.ijoomla.com/redirect/general/templates.htm">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("VIEWTREETEMPLATES"); ?>
                </a>
            </li>
            <li class="">
                <a target="_blank" href="http://www.ijoomla.com">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("VIEWDSADMINSITE"); ?>
                </a>
            </li>
            <li class="">
                <a target="_blank" href="http://www.ijoomla.com/redirect/general/latestversion.htm">
                    <i class="js-icon-double-angle-right"></i>
                    <?php echo JText::_("VIEWTREELV"); ?>
                </a>
            </li>
            <!--<li class="">
                <a href="http://www.ijoomla.com/redirect/guru/rate.htm">
                    <i class="js-icon-double-angle-right"></i>
                    <?php //echo JText::_("GURU_RATE_US"); ?>
                </a>
            </li>-->
        </ul>
    </li><!--end help-->
    <li <?php if($controller_req == "guruAbout"){ echo 'class="active"';} ?>>
        <a href="index.php?option=com_guru&controller=guruAbout">
            <i class="icon-star"></i>
            <span class="menu-text"><?php echo JText::_("GURU_TREEABOUT"); ?> </span>
        </a>
    </li><!--end about-->
 </ul>
 	<div id="sidebar-collapse" class="sidebar-collapse">
    	<i class="js-icon-double-angle-left"></i>
	</div><!--end collapse button div -->
</div><!-- end the guru menu-->
<?php
?>