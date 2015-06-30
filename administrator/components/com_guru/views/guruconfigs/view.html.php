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

class guruAdminViewguruConfigs extends JViewLegacy {
	function display ($tpl =  null ) {
		$db = JFactory::getDBO(); 

		JToolBarHelper::title(JText::_('GURU_SETTINGS'));
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel ('cancel', 'Cancel');
		
		$configs = $this->get('Configs');
		$this->assignRef('configs', $configs);  
		/*if (JComponentHelper::isEnabled( 'com_community', true)){
			$profile_disabled = $this->get('JsMProfile');
			$this->assignRef('profile_disabled', $profile_disabled);
			
			$jomsocialmultiple = $this->get('MultipleProfileJomSocial');
			$this->assignRef('jomsocialmultiple', $jomsocialmultiple);   
		}*/
		$emails = $this->get('Emails');
		$this->assignRef('emails', $emails);   
		
		$superadmins = $this->get('Admins');
		$this->assignRef('superadmins', $superadmins);	
		

		parent::display($tpl);
	}
}

?>