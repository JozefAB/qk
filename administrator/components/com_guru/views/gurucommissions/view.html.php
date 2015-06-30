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

class guruAdminViewguruCommissions extends JViewLegacy {

	function display ($tpl =  null ) { 
		
		JToolBarHelper::title(JText::_('GURU_COMMISSIONS'), 'generic.png');
		JToolBarHelper::addNew();
		JToolBarHelper::custom('default','star','star','Default',false);
		JToolBarHelper::deleteList();		
		$programs = $this->get('listCommissions');
		$this->assignRef('programs', $programs);
		parent::display($tpl);
	}
	
	function editform($tpl = null){
		$id = JRequest::getVar("id", "0");
		$cid = JRequest::getVar("cid", "0");
		if($id == "0" && $cid == "0" ){
			JToolBarHelper::title(JText::_('GURU_COMMISSION_PLAN').":<small>[".trim(JText::_("GURU_NEW"))."]</small>");
		}
		else{
			JToolBarHelper::title(JText::_('GURU_COMMISSION_PLAN').":<small>[".trim(JText::_("GURU_EDIT"))."]</small>");
		}
		
		JToolBarHelper::save('save');
		JToolBarHelper::apply('apply');
		JToolBarHelper::cancel();	

		$commissions = $this->get('CommissionDetails');
		$this->assignRef("commissions",$commissions);
		
		$config = $this->get('Config');
		$this->assignRef("config",$config);
		
		parent::display($tpl);
	}
	function historycommission ($tpl = null){
		$id = JRequest::getVar("id", "0");
		$cid = JRequest::getVar("cid", "0");
		
		$commissions_paid = $this->get('PendingCommissionsPaid');
		$this->assignRef("commissions_paid",$commissions_paid);
		
		$config = $this->get('Config');
		$this->assignRef("config",$config);
		
		$pagination = $this->get( 'Pagination' );
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}
	function pendingcommission($tpl = null){
		$id = JRequest::getVar("id", "0");
		$cid = JRequest::getVar("cid", "0");
		
		JToolBarHelper::custom('make_paid_top','publish','publish',JText::_('GURU_MAKE_PAID'),false);

		$commissions_to_pay = $this->get('PendingCommissionsToBePaid');
		$this->assignRef("commissions_to_pay",$commissions_to_pay);
		
		$pagination1 = $this->get( 'Pagination' );
		$this->assignRef('pagination1', $pagination1);
		
		
		$teacher_name = $this->get('TeachersNames');
		$this->assignRef("teacher_name",$teacher_name);
		
		$config = $this->get('Config');
		$this->assignRef("config",$config);
		
		parent::display($tpl);
	}
	
	function paidcommission($tpl = null){
		$id = JRequest::getVar("id", "0");
		$cid = JRequest::getVar("cid", "0");		

		$commissions_paid_teacher = $this->get('PendingCommissionsPaidTotal');
		$this->assignRef("commissions_paid_teacher",$commissions_paid_teacher);
		
		$config = $this->get('Config');
		$this->assignRef("config",$config);
		
		$pagination = $this->get( 'Pagination' );
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	}
	function getAllTeachers(){
		$teacher = $this->get("AllTeachers");
		return $teacher;
	}
	function getAllCourses(){
		$course = $this->get("AllCourses");
		return $course;
	}
	function getAllPromos(){
		$promo = $this->get("AllPromos");
		return $promo;
	}
	function getAllCommissions(){
		$allcommission = $this->get("AllCommissions");
		return $allcommission;
	}
	function view_details ($tpl = null){
		$id = JRequest::getVar("id", "0");
		$cid = JRequest::getVar("cid", "0");
		$model =$this->getModel("guruCommissions");
		$this->assignRef("model",$model);

		$teachers = $this->get('TeacherDetails');
		$this->assignRef("teachers",$teachers);
		
		$config = $this->get('Config');
		$this->assignRef("config",$config);
		
		parent::display($tpl);
	}
	
	function getDetailsPagination(){
		$pagination = $this->get('Pagination');
		return $pagination;
	}
}

?>