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

//global $mainframe;
$app = JFactory::getApplication();
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
//check for access
$my =  JFactory::getUser();

$database =  JFactory :: getDBO();
$meniu=0;
$task = JRequest::getVar('task', "");
$control = JRequest::getVar('controller', "");
$view = JRequest::getVar('view', "");
$export = JRequest::getVar('export', "");

require_once (JPATH_COMPONENT.DS.'controller.php');
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );
$controller = JRequest::getVar('controller', "");
$guruHelperclass = new guruHelper();
$guruHelperclass->createBreacrumbs();

$menuParams = new JRegistry;
$app = JFactory::getApplication("site");
$menu = $app->getMenu()->getActive();
@$menuParams->loadString($menu->params);
$show_page_heading = $menuParams->get("show_page_heading");
$page_heading = $menuParams->get("page_heading");

ini_set('display_errors', 0);
if($show_page_heading == 1){
	if($page_heading == ""){
		$page_heading = $menuParams->get("page_title");
	}
?>
<header class="page-header">
	<h1 class="page-title">
	<?php echo trim($page_heading); ?>
	</h1>
</header>
<?php
}

if($controller == "guruProfile" || $controller == "guruBuy"){
	JRequest::setVar("view", "");
	JRequest::setVar("layout", "");
	JRequest::setVar("cid", "");
}

if($controller){
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if(file_exists($path)){
		require_once($path);
	}
}
else{
	switch($view){
		case "guruauthor":
			$controller = 'guruAuthor';
			break;
		case "guruprograms":
			$controller = 'guruPrograms';
			break;
		case "guruPrograms":
			$controller = 'guruPrograms';
			break;
		case "guruorders":
			$controller = 'guruOrders';
			break;
		case "guruOrders":
			$controller = 'guruOrders';
			break;	
		case "gurutasks":
			$controller = 'guruTasks';
			break;
		case "guruTasks":
			$controller = 'guruTasks';
			break;
		case "guruLogin":
			$controller = 'guruLogin';
			break;
		case "guruBuy":
			$controller = 'guruBuy';
			break;
		case "guruProfile":
			$controller = 'guruProfile';
			break;
		case "guruCustomers":
			$controller = 'guruCustomers';
			break;
		case "guruEditplans":
			$controller = 'guruEditplans';
			break;	
		default:
			$controller = 'guruPcategs';
			break;
	}
 	
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';

	if(file_exists($path)){
		require_once($path);
	}
}
JHtml::_('behavior.framework',true);

$task = JRequest::getVar("task", "");

if($task != "saveInDbQuiz" && $task != "showCertificateFr" && $task != "ajax_add_video" && $task != "savesbox" && $task != "lessonmessage" && $task != "editgurucomment" && $task != "editformgurupost" && $export != "csv"){
?>
<div class="guru-content" id="guru-component">
<?php
}
?>

	<?php
    $db = JFactory::getDBO();
    $sql = "SELECT * FROM #__guru_config WHERE id = '1' ";
    $db->setQuery($sql);
    if(!$db->query()){
        echo $db->stderr();
        return;
    }
    $configs = $db->loadObject();
    $document	= JFactory::getDocument();
	
	$view = JRequest::getVar("view", "");
	$layout = JRequest::getVar("layout", "");
    $cid = JRequest::getVar("cid", "");
    
	if($view == "guruauthor" && $layout == "view" && $controller == "guruAuthor" && intval($cid) == "0"){
		$Itemid = JRequest::getVar("Itemid", "0");
		$redirect = JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=authorprofile&Itemid=".$Itemid, false);
		$app = JFactory::getApplication();
		$app->redirect($redirect);
	}
	
    $classname = "guruController".$controller;
    $ajax_req = JRequest::getVar("no_html", 0, "request");
    $controller = new $classname();
    $layout = JRequest::getWord('layout');

    if($layout && $task !="renew"){
        $controller->execute($layout);
    }
    else{
        $task = JRequest::getWord('task');
        $controller->execute($task);
    }
    
    $controller->redirect();
    
    $view = JRequest::getVar("view", "");
    $controller = JRequest::getVar("controller", "");
    
    if(trim($view) == ""){
        $view = $controller; 
    }
    
    if($view == 'gurutasks'){
        // do nothing
    }
    elseif($view == "guruPcategs" || $view == "gurupcategs" || $view == "gurubuy" || $view == "guruPrograms"){
            $db = JFactory::getDBO();
            $sql = "select `show_powerd` from #__guru_config";
            $db->setQuery($sql);
            $db->query();
            $result = $db->loadColumn();
			$result = $result["0"];
            if($result == 1){
				if($task != "savesbox" && $task !="saveLesson"){
            ?>
                <div class=" pagination-centered">
                    <span class="power_by">Powered by: Guru: </span>
                    <a target="_blank" href="http://guru.ijoomla.com/" class="power_link" title="joomla lms">Joomla LMS</a>
                </div>
            <?php
				}
            }
            else{
            
            }
    }

if($task != "saveInDbQuiz" && $task != "savesbox" && $task !="saveLesson" && $task != "lessonmessage" && $task != "editgurucomment" && $task != "editformgurupost" && $export != "csv"){	
    ?>
</div>
<div class="clearfix"></div>
<?php
}
?>