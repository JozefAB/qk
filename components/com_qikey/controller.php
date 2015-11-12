<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
jimport('joomla.application.component.view');
 
/**
 * Hello World Component Controller
 */
class QikeyController extends JControllerLegacy
{

	function filterByDate(){
		$jinput = JFactory::getApplication()->input;
		$fromDate = $jinput->getVar('fromDate');
		$toDate = $jinput->getVar('toDate');

		if ($fromDate == null || $toDate == null){
			$view = $this->getView('credits','html','qikeyview');
			$view->display(null, null, "error");
		} else {
			$model = JModelList::getInstance('credits','QikeyModel');
			$completedLessons = $model->getCompletedLessons($fromDate, $toDate);	
			$view = $this->getView('credits','html','qikeyview');
			$view->display(null, $completedLessons, null);
		}
		
	}

}

// class QikeyController extends JControllerLegacy
// {

// }