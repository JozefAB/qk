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

jimport ("joomla.application.component.view");
require_once(JPATH_SITE.DS."administrator".DS."components".DS."com_guru".DS."helpers".DS."helper.php");

class guruAdminViewguruCustomers extends JViewLegacy {

	function display ($tpl =  null ) {
		JToolBarHelper::title(JText::_('GURU_MANAGEC'), 'generic.png');
		//JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::deleteList(JText::_('GURU_DELETE_STUDENT'));	
		
		$customers = $this->get('Items');
		$pagination = $this->get( 'Pagination' );
		
		$this->assignRef('customers', $customers);
		$this->assignRef('pagination', $pagination);
		
		$filters= $this->get('Filters');
		$this->assignRef('filters', $filters);
				
		parent::display($tpl);

	}
	
	function editForm($tpl = null){
		$db = JFactory::getDBO();
		$customer = $this->get('customer');		
		$isNew = ($customer->id < 1);
		$text = $isNew?JText::_('New'):JText::_('Edit');
		JToolBarHelper::title(JText::_('Student').":<small>[".$text."]</small>");
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel ('cancel', 'Close');	
		parent::display($tpl);
	}
	
	function addForm($tpl = null){
		$text = JText::_('GURU_NEW');
		JToolBarHelper::title(JText::_('Student').":<small>[".$text."]</small>");
		JToolBarHelper::custom('next','forward.png','forward_f2.png', JText::_("GURU_NEXT"), false);
		JToolBarHelper::cancel();
		parent::display($tpl);
	}
	
	function getCustomerDetails($id){
		$model = $this->getModel();
		$result = $model->getCustomerDetails($id);
		return $result;
	}
	function getStudentCourses($id){
		$model = $this->getModel();
		$result = $model->getStudentCourses($id);
		return $result;
	}	
}

?>