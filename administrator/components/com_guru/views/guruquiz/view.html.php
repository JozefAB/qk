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

class guruAdminViewguruQuiz extends JViewLegacy {

	function display ($tpl =  null ) {
		JToolBarHelper::title(JText::_('GURU_Q_QUIZ_MANAGER'), 'generic.png');		
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList('This will delete all the quiz results on the backend and front end','deletequizresult','Clear Results');
		//JToolBarHelper::addNew('editZ',JText::_('GURU_NEW_Q_BTN'));
		JToolBarHelper::addNew('duplicate',JText::_('GURU_DUPLICATE_Q_BTN'));
		JToolBarHelper::editList();
		JToolBarHelper::deleteList('Are you sure you want to delete quiz?');	
		
		$pagination = $this->get( 'Pagination' );
		$this->assignRef('pagination', $pagination);
		
		$ads = $this->get('listQuiz');
		$this->assignRef('ads', $ads);
		parent::display($tpl);

	}	

	function listquizstud($tpl =  null){
		$pagination = $this->get( 'Pagination' );
		$this->assignRef('pagination', $pagination);
		$pid = JRequest::getVar('pid',"");
		$model = $this->getModel('guruQuiz');
		$list = $model->getlistQuizTakenStud();
		$this->ads = $list;
		parent::display($tpl);
	}
	function listStudentsQuizTaken($tpl =  null){
		$pagination = $this->get( 'Pagination' );
		$this->assignRef('pagination', $pagination);
		$model = $this->getModel('guruQuiz');
		$list = $model->getlistStudentsQuizTaken();
		$this->ads = $list;
		parent::display($tpl);
	}		
	function show_quizz_res($tpl =  null){
		$pagination = $this->get( 'Pagination' );
		$this->assignRef('pagination', $pagination);
		$list1 = $this->get('listQuizTakenStud');
		$this->ads = $list1;
		parent::display($tpl);
	}	
	function addquestion ($tpl =  null ) { 
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM `#__guru_questions`");
		$medias = $db->loadObjectList();
		$this->assignRef('medias', $medias);
		parent::display($tpl);
	}
	function addquizzes ($tpl =  null ) { 
		$db = JFactory::getDBO();		
		$search_text = JRequest::getVar('search_text', "");
		$sql = "SELECT id, name FROM `#__guru_quiz`";
		if($search_text!=""){
			$sql = $sql." where name LIKE '%".$search_text."%' and `is_final` <> 1 " ;
		}
		else{
			$sql = $sql." where `is_final` <> 1" ;
		}
		$db->setQuery($sql);
		$list_quizzes=$db->loadAssocList();	
		$this->assignRef('list_quizzes',$list_quizzes);
		parent::display($tpl);
	}
	function settypeform($tpl = null){
		$id = JRequest::getVar("id", "0");
		if($id == "0"){
			JToolBarHelper::title(JText::_('GURU_Q_QUIZ_MANAGER'));
		}
		else{
			JToolBarHelper::title(JText::_('GURU_Q_QUIZ_MANAGER'));
		}
		parent::display($tpl);
	}	
	function editquestion ($tpl =  null ) { 
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM `#__guru_questions` WHERE id = ".$_GET['qid']." AND qid =".$_GET['cid'][0]);
		$medias = $db->loadObject();
		$this->assignRef('medias', $medias);
		parent::display($tpl);
	}	
	
	function editForm($tpl = null) { 
		$app = JFactory::getApplication('administrator');		
		$db = JFactory::getDBO();		
		$program = $this->get('quiz'); 
		$value_option = JRequest::getVar("v");
		if($program->is_final == 0 && $value_option == 0){
			JToolBarHelper::title(JText::_('GURU_QUIZ').":<small>[".$program->text."]</small>");
		}
		else{
			JToolBarHelper::title(JText::_('GURU_FINAL_EXAM_QUIZ1').":<small>[".$program->text."]</small>");
		}
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel('cancel', JText::_('GURU_CANCEL_Q_BTN'));		
		
		$media = $this->get('Media');
		$this->assign("media", $media);
		$this->assign("max_reo", $media->max_reo);
		$this->assign("min_reo", $media->min_reo);
	   	$this->assign("mmediam", $media->mmediam);
		$this->assign("mainmedia", $media->mainmedia);
		$this->assign("program", $program);
		$pagination = $this->get( 'Pagination' );
		$this->assignRef('pagination', $pagination);
		parent::display($tpl);
	}
	
	function addmedia ($tpl =  null ) { 
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM `#__guru_media`");
		$medias = $db->loadObjectList();
		$this->assignRef('medias', $medias);
		parent::display($tpl);
	}	
	
	function getNrStudentsQuiz(){
		$db = JFactory::getDBO();
		$sql = "SELECT DISTINCT (user_id) FROM `#__guru_quiz_taken`";
		$db->setQuery($sql);
		$db->query();
		$total = $db->loadAssocList();
		return intval(count($total));
	}

	function getTotalAvg(){
		$db = JFactory::getDBO();
		$sql = "SELECT sum(SUBSTRING_INDEX(`score_quiz` , '|', 1 ) / SUBSTRING_INDEX(`score_quiz` , '|', -1 )) as total FROM `#__guru_quiz_taken`";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		$total = $result["total"];
		return $total;
	}
}
?>