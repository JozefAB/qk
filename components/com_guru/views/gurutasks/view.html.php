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

class guruViewguruTasks extends JViewLegacy {

	function display ($tpl =  null ) { 
		$module = $this->get('getTask');
		$this->assignRef('module', $module);
		parent::display($tpl);
	}
	
	function show ($tpl =  null ) {
		$task = $this->get('Task');
		$this->assignRef('task', $task);
		if($task!=false){
			$programname = $this->get('programname');
			$this->assignRef('programname', $programname);	
			
			$mediaForTask = $this->get('MediaForTask');
			$this->assignRef('mediaForTask', $mediaForTask);
	
			$day = $this->get('day');
			$this->assignRef('day', $day);	
		}
		parent::display($tpl);
	}	
	
	function showExercise($tpl = null){
		parent::display($tpl);
	}
	function viewcertificate($tpl = null){
		parent::display($tpl);
	}	
	function getSteps($author_id){
		$model = $this->getModel();
		$result = $model->getSteps($author_id);
	}
	//REMOVED BY JOSEPH 08/04/2015
	//function saveLesson($step_id,$pid){
		//$model = $this->getModel();
		//$result = $model->saveLessonViewed($step_id,$pid);
	//}
	//END

	//ADDED BY JOSEPH 08/04/2015
	function saveLesson($step_id,$pid, $credit_val = 0){
		$model = $this->getModel();
		$result = $model->saveLessonViewed($step_id,$pid, $credit_val);
	}
	//END
	function InsertMyCertificateDetails1($pid){
		$model = $this->getModel();
		$result = $model->InsertMyCertificateDetails($pid);
	}
	function emailCertificate1($pid){
		$model = $this->getModel();
		$result = $model->emailCertificate($pid);
	}
	
	function isLastPassedQuiz($course_id){
		$model = $this->getModel();
		$result = $model->isLastPassedQuiz($course_id);
		return $result;
	}
	
	function eliminateBlankAnswers($answers){
		$temp_array = array();
		if(isset($answers) && count($answers) > 0){
			foreach($answers as $key=>$value){
				if(trim($value) != ""){
					$temp_array[] = $value;
				}
			}
		}
		return $temp_array;
	}
	function quiz_fe_result_calculation(){
		$task = $this->get('Task');
		$this->assignRef('task', $task);
		if($task!=false){
			$programname = $this->get('programname');
			$this->assignRef('programname', $programname);	
			
			$mediaForTask = $this->get('MediaForTask');
			$this->assignRef('mediaForTask', $mediaForTask);
	
			$day = $this->get('day');
			$this->assignRef('day', $day);	
		}
		parent::display($tpl);
	
	}
	function getQuizCalculation($quiz_id, $course_id, $nb_of_questions){
		$model = $this->getModel();
		$quiz_content = $model->getResultQuizzes($quiz_id, $course_id, $nb_of_questions);
		return $quiz_content;
	}
	function generatePassed_Failed_quizzes($quiz_id, $course_id, $nb_of_questions, $pass){
		$model = $this->getModel();
		$quiz_content = $model->generatePassed_Failed_quizzes($quiz_id, $course_id, $nb_of_questions, $pass);
		return $quiz_content;
	}
}

?>