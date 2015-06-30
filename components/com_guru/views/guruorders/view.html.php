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

class guruViewguruOrders extends JViewLegacy {

	function display ($tpl =  null ) {
		$myorders = $this->get('MyOrders');
		$this->assign('myorders', $myorders);
		$configs = $this->get('ConfigSettings');
		$this->assign('configs', $configs);
		$datetype =  $this->get( 'DateType' );
		$this->assignRef('datetype', $datetype);
		parent::display($tpl);
	}
	
	function myCourses($tpl =  null){
		$configs = $this->get('ConfigSettings');
		$this->assign('configs', $configs);
		$my_courses = $this->get('MyCourses');
		$this->assign('my_courses', $my_courses);
		parent::display($tpl);
	}
	function mycertificates($tpl =  null){
		$configs = $this->get('ConfigSettings');
		$this->assign('configs', $configs);
		$my_courses = $this->get('MyCourses');
		$this->assign('my_courses', $my_courses);
		parent::display($tpl);
	}
	function myQuizandfexam($tpl =  null){
		$configs = $this->get('ConfigSettings');
		$this->assign('configs', $configs);
		//$my_quizzes = $this->get('MyQuizzes');
		//$this->assign('my_quizzes', $my_quizzes);
		parent::display($tpl);
	}
	function listquizstud($tpl =  null){
		/*$pagination =  $this->get( 'Pagination' );
		$this->assignRef('pagination', $pagination);*/
		$pid = JRequest::getVar('pid',"");
		$model = $this->getModel('guruorder');
		$list = $model->getlistQuizTakenStud($pid);
		$this->ads = $list;
		parent::display($tpl);
	}
	function show_quizz_res($tpl =  null){
		/*$pagination =  $this->get( 'Pagination' );
		$this->assignRef('pagination', $pagination);*/
		$list1 = $this->get('getlistQuizTakenStudF');
		$this->ads = $list1;
		parent::display($tpl);
	}	
	
	function orderDetails1($tpl =  null){
		$order = $this->get("OrderFromOrders");
		$this->assign("order", $order);
		$this->assign("show", false);
		parent::display($tpl);
	}
	function printcertificate($tpl =  null){
		parent::display($tpl);
	}
	function orderDetails2($tpl =  null){
		$order = $this->get("OrderFromOrders");
		$this->assign("order", $order);
		$this->assign("show", true);
		parent::display($tpl);
	}
	
	function getPlans(){
		$plans = $this->get("Plans");
		return $plans;
	}
	
	function countCourseOrders($id){
		$model = $this->getModel("guruorder");
		$number = $model->countCourseOrders($id);
		return $number;
	}
	
	function getConfigSettings(){
		$model = $this->getModel("guruorder");
		$configs = $model->getConfigSettings();
		return $configs;
	}
}

?>