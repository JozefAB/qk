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
jimport ('joomla.application.component.controller');

class guruAdminControllerguruCertificate extends guruAdminController {
	var $_model = null;
	
	function __construct(){
		parent::__construct();
		$this->registerTask ("", "display");
		$this->registerTask ("previewcertificate", "previewcertificate");
        $this->_model = $this->getModel("guruCertificate");
	}

    function display($cachable = false, $urlparams = Array()){
		$view = $this->getView("guruCertificate", "html");
        $view->setLayout('default');
		$view->setModel($this->_model, true);
		@$view->display();
    }
	function cancel(){
		 $msg = JText::_('GURU_MEDIACANCEL');	
		 $app = JFactory::getApplication('administrator');
		 $app->redirect('index.php?option=com_guru', $msg);
	}
    function save(){
        $app = JFactory::getApplication('administrator');
        if($this->_model->savedesigncert('s')){
            $msg = JText::_('GURU_MODIF_OK');
            $app->redirect('index.php?option=com_guru&controller=guruCertificate', $msg);
        } 
		else{
            $msg = JText::_('GURU_ERROR');
            $app->redirect('index.php?option=com_guru', $msg);
        }
        $this->display();
    }
    
    function apply(){
        $app = JFactory::getApplication('administrator');
        if($id = $this->_model->savedesigncert('a')){
            $msg = JText::_('GURU_MODIF_OK');
            $app->redirect('index.php?option=com_guru&controller=guruCertificate', $msg);
        }
		else{
            $msg = JText::_('GURU_ERROR');
            $app->redirect('index.php?option=com_guru&controller=guruCertificate', $msg);
        }
        $this->display();
    }
	function previewcertificate(){
		$db = JFactory::getDBO();
		$imagename = "SELECT * FROM #__guru_certificates WHERE id=1";
		$db->setQuery($imagename);
		$db->query();
		$imagename = $db->loadAssocList();
		
		if($imagename[0]["design_background"] !=""){
			$image_theme = explode("/", $imagename[0]["design_background"]);
			if(trim($image_theme[4]) =='thumbs'){
				$image_theme = $image_theme[5];
			}
			else{
				$image_theme = $image_theme[4];
			}
		}	
		else{
			$background_color= "background-color:"."#".$imagename[0]["design_background_color"];
		}	
		?>
		<div style="width:800px; font-family:<?php echo $imagename[0]["font_certificate"]; ?>; height:600px; <?php echo $background_color;?>; background-repeat:no-repeat; background-image:url(<?php echo JUri::root()."images/stories/guru/certificates/".$image_theme; ?>);">
		<table>
			<tr>
				<td style="padding-left:150px; padding-top:180px; font-size:36px; color:<?php echo "#".$imagename[0]["design_text_color"]; ?>"><?php echo JText::_("GURU_CERTIFICATE_OF_COMPLETION"); ?></td>
			</tr>
			<tr>
				<td  style="padding:40px; padding-left:70px; color:<?php echo "#".$imagename[0]["design_text_color"]; ?>">
				<?php echo $imagename[0]["templates1"]; ?>
		   </td>
			</tr>
		 </table>
		</div>
	<?php
	}
}

?>