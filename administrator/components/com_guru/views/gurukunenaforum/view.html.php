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

class guruAdminViewguruKunenaForum extends JViewLegacy {
    
    function display ($tpl =  null ) { 
        JToolBarHelper::title(JText::_('GURU_KUNENAF_MANAGER'), 'generic.png');	
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
        
		$kunenaforum_details = $this->get('KunenaforumDetails');
		$this->assignRef('kunenaforum_details', $kunenaforum_details);
		
        $pagination = $this->get( 'Pagination' );
        $this->assignRef('pagination', $pagination);
        parent::display($tpl);
    }
}

?>