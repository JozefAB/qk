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
jimport ("joomla.aplication.component.model");


class guruAdminModelguruCertificate extends JModelLegacy {
	var $_packages;
	var $_package;
	var $_tid = null;
	var $_total = 0;
	var $_pagination = null;

	
	function savedesigncert($t) {
		$post_value = JRequest::get('post',JREQUEST_ALLOWRAW);
		$db = JFactory::getDBO();
		
		if(!file_exists(JPATH_SITE.DS.'components'.DS.'com_guru'.DS.'helpers'.DS.'MPDF') && $post_value["library_pdf"] == 1){
			$app = JFactory::getApplication('administrator');
			$msg = JText::_('GURU_NO_MPDF_MSG'); 
			if($t == 'a'){
				$app->redirect( 'index.php?option=com_guru&controller=guruCertificate', $msg, "error");
			}
			elseif($t == 's'){
				$app->redirect( 'index.php?option=com_guru', $msg, "error");
			}
		}
		
		$sql = "UPDATE #__guru_certificates set `general_settings`='".$post_value["certificate_sett"]."', `design_background`= '".$post_value["image"]."',	`design_background_color` ='". $post_value["st_donecolor1"]."', `design_text_color`='".$post_value["st_donecolor2"]."', `avg_cert`='".$post_value["avg_cert"]."',`templates1`='".$post_value["certificate"]."', `templates2`='".$post_value["certificate_page"]."', `templates3`='".$post_value["email_template"]."', `templates4`='".$post_value["email_mycertificate"]."', `subjectt3`='".$post_value["subjectt3"]."', `subjectt4`='".$post_value["subjectt4"]."', `font_certificate`='".$post_value["font"]."' , `library_pdf` = '".$post_value["library_pdf"]."' ";
		$db->setQuery($sql);
		$db->query();
		return true;
	}	

   public static function getCertificatesDetails(){
  		$db = JFactory::getDBO();
		$sql = "SELECT * from #__guru_certificates where id='1'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadObject();
		return $result;
   }
};
?>