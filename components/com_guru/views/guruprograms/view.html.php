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

jimport ("joomla.application.component.view");

class guruViewguruPrograms extends JViewLegacy {

	function display ($tpl =  null ){
		$programs = $this->get('listPrograms');
		$this->assignRef('programs', $programs);
		parent::display($tpl);
	}
	
	function show ($tpl =  null ) {
		$model = $this->getModel();
		$model->changeCompleted();
	
		$db = JFactory::getDBO();		
		$program = $this->get('Program');
		$this->assignRef('program', $program);	

		$programContent = $this->get('programContent');
		$this->assignRef('programContent', $programContent);	

		$exercise =  $this->get('exercise');
		$this->assignRef('exercise', $exercise);	

		$requirements=  $this->get('ReqCourses');
		$this->assignRef('requirements',$requirements);

		// we get also the DAYS for this program
		$pdays = $this->get('pdays'); 
		$this->assign('pdays', $pdays);
		
		// we extract the points, time for each program
		//$getsum_points_and_time = $this->get('sum_points_and_time');
		//$this->assignRef('getsum_points_and_time', $getsum_points_and_time);	

		//we extract also the CONFIG SETTINGS for curency
		$getConfigSettings = $this->get('ConfigSettings');
		$this->assignRef('getConfigSettings', $getConfigSettings);

		$author = $this->get('Author');
		$this->assignRef('author',$author);

		$courses=  $this->get('authorCourses');
		$this->assignRef('courses', $courses);
		
		parent::display($tpl);
	}

	function details ($tpl =  null ) {
		$db = JFactory::getDBO();		
		$program = $this->get('Program');
		$this->assignRef('program', $program);	
		$pdays = $this->get('pdays'); 
		$this->assign('pdays', $pdays);	
		// we extract the points, time for each program
		//$getsum_points_and_time = $this->get('sum_points_and_time');
		//$this->assignRef('getsum_points_and_time', $getsum_points_and_time);	
		//we extract also the CONFIG SETTINGS for curency
		$getConfigSettings = $this->get('ConfigSettings');
		$this->assignRef('getConfigSettings', $getConfigSettings);		
		parent::display($tpl);
	}
	
	function showmyprograms ($tpl =  null ) {
		$db = JFactory::getDBO();		
		$program = $this->get('myprograms');
		$this->assignRef('program', $program);	
		$getConfigSettings = $this->get('ConfigSettings');
		$this->assignRef('getConfigSettings', $getConfigSettings);			
		parent::display($tpl);
	}
	
	function listCourses($tmpl = null){
		$db = JFactory::getDBO();		
		$program = $this->get('Program');
		$this->assignRef('program', $program);	
		
		$programContent = $this->get('programContent');
		$this->assignRef('programContent', $programContent);	
		
		parent::display($tpl);
	}
}

?>