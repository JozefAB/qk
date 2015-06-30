<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
jimport('joomla.application.component.modellist');

/**
 * HTML View class for the Qikey Component
 */
/*class QikeyViewCredits extends JViewLegacy
{
	protected $profile;
	protected $completedLessons;
        public $filteredLessons;
        protected $currentDate;
        protected $pagination;

        // Overwriting JView display method
        function display($tpl = null, $filteredLessons = null, $errors = null) 
        {
                // Assign data to the view
                $this->msg = 'Credit Page';
                $this->currentDate = new JDate('now');
                $this->currentDate = $this->currentDate->format('Y-m-d', false, false);
                $model = JModelList::getInstance('Credits','QikeyModel');
                $this->profile = $model->getProfile();
                if ($filteredLessons == null){
                        $this->completedLessons = $model->getCompletedLessons();
                        if($errors !== null){
                                JFactory::getApplication()->enqueueMessage('Please Choose both a From and To date', 'notice');
                        }
                }
                else {

                        $this->completedLessons = $filteredLessons;
                }

                $this->pagination = $model->getPagination() ;
                //$this->assignRef( 'pagination', $pagination );

                // Display the view
                parent::display($tpl);
        }
}*/

class QikeyViewCredits extends JViewLegacy
{
    function display($tpl = null) 
    { 

        $items = $this->get('Data');
        $this->assignRef( 'items', $items );

        $model = JModelList::getInstance('Credits','QikeyModel');
        $filterForm = $model->getFilterForm('items');
        $this->assignRef('filterForm', $filterForm);

        $pagination = $this->get('Pagination') ;
        $this->assignRef( 'pagination', $pagination );

        parent::display($tpl);
    }
}