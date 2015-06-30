<?php

defined('_JEXEC') or die('Access Denied');
jimport('joomla.application.component.modellist');

/*class QikeyModelCredits extends JModelList {
        
        protected $jomId;
        protected $profile;
        protected $completedLessons = null;
        protected $totalCompletedLessons = null;
        protected $lessonDetails;
        protected $perPage;
        protected $limitStart;
        protected $_pagination = null;

        function __construct() { 
            
            parent::__construct(); 

            $application = JFactory::getApplication() ; 

            $config = JFactory::getConfig() ; 

            $limitstart = JRequest::getInt( 'limitstart', 0 );
            $limit = $application->getUserStateFromRequest( 'global.list.limit', 
                'limit', $config->get('config.list_limit'), 'int' );        

            $this->setState('limitstart', $limitstart); 
            $this->setState('limit', 2); 
        }
    
        function getProfile(){
            $user = JFactory::getUser();
            $this->jomId = $user->id;
            $db = JFactory::getDbo();
            $query = "SELECT * FROM #__guru_customer WHERE id =" . $this->jomId ."";
            $total_rows = $this->getTotal($query);
            $db->setQuery($query);
            $this->profile = $db->loadAssoc();
            return $this->profile;
            //$query=parent::getListQuery();
            //$query->select('*');
            //$query->from('#__guru_customer');
            //$query->where('id=' . $this->jomId .'');
            //return $query;
            //$res = "working";
            //return $res;
        }

        function totalCompletedLessons() { 
            return $this->totalCompletedLessons; 
        } 

        function getCompletedLessons(){
            if (empty($this->completedLessons) && empty($this->totalCompletedLessons)){
                $user = JFactory::getUser();
                $this->jomId = $user->id;
                $db = JFactory::getDbo();
                $query = "SELECT * 
                            FROM #__qikey_completed_lesson 
                            WHERE user_id =" . $this->jomId;
                $db->setQuery($query);
                $this->completedLessons = $db->loadAssocList();
                //return $this->completedLessons;
                $counter = 0;
                foreach($this->completedLessons as $key => $value){
                    $this->lessonDetails[$counter]['course_id'] = $value['course_id'];
                    $this->lessonDetails[$counter]['course_name'] = $this->getCourseName($value['course_id']);
                    $this->lessonDetails[$counter]['module_id'] = $value['module_id'];
                    $this->lessonDetails[$counter]['module_name'] = $this->getModuleName($value['course_id'],$value['module_id']);
                    $this->lessonDetails[$counter]['lesson_id'] = $value['lesson_id'];
                    $this->lessonDetails[$counter]['lesson_name'] = $this->getLessonName($value['lesson_id']);
                    $this->lessonDetails[$counter]['completed_date'] = $value['completed_date'];
                    $counter ++;
                };
                $this->totalCompletedLessons = count($this->completedLessons);
                
                $limitstart = $this->getState('limitstart');
                $limit = $this->getState('limit');

                return array_slice( $this->lessonDetails, $limitstart, $limit );
                }
        }

        function loadCompletedLessons($fromDate = "1900-01-01 00:00:00", $toDate = "2100-12-31 23:59:59"){
            if (empty($this->completedLessons) && empty($this->totalCompletedLessons)){
                $user = JFactory::getUser();
                $this->jomId = $user->id;
                $db = JFactory::getDbo();
                $query = "SELECT * 
                            FROM #__qikey_completed_lesson 
                            WHERE user_id =". $this->jomId ." AND completed_date BETWEEN '" . $fromDate . "' AND '" . $toDate . " 23:59:59'";
                $db->setQuery($query);
                $this->completedLessons = $db->loadAssocList();
                //return $this->completedLessons;
                $counter = 0;
                foreach($this->completedLessons as $key => $value){
                    $this->lessonDetails[$counter]['course_id'] = $value['course_id'];
                    $this->lessonDetails[$counter]['course_name'] = $this->getCourseName($value['course_id']);
                    $this->lessonDetails[$counter]['module_id'] = $value['module_id'];
                    $this->lessonDetails[$counter]['module_name'] = $this->getModuleName($value['course_id'],$value['module_id']);
                    $this->lessonDetails[$counter]['lesson_id'] = $value['lesson_id'];
                    $this->lessonDetails[$counter]['lesson_name'] = $this->getLessonName($value['lesson_id']);
                    $this->lessonDetails[$counter]['completed_date'] = $value['completed_date'];
                    $counter ++;
                };
                $this->totalCompletedLessons = count($this->completedLessons);
                return $this->completedLessons;
            }
        }

        function getCompletedLessons($fromDate = "1900-01-01 00:00:00", $toDate = "2100-12-31 23:59:59"){
            $this->loadCompletedLessons($fromDate, $toDate) ; 
    
            $limitstart = $this->getState('limitstart');
            $limit = $this->getState('limit');

            return array_slice( $this->completedLessons, $limitstart, $limit );   
        }

        function getPagination() { 
            $this->loadCompletedLessons();

            if (empty($this->_pagination)) 
            { 
                jimport('joomla.html.pagination'); 

                $limitstart = $this->getState('limitstart');
                $limit = $this->getState('limit');
                $total = $this->getTotal();

                $this->_pagination = new JPagination( $total, $limitstart, $limit ); 
            } 

            return $this->_pagination; 
        }

        function getCourseName($pid){
            $db = JFactory::getDbo();
            $query = "SELECT name  
                        FROM #__guru_program
                        WHERE id =" . $pid;
            $db->setQuery($query);
            $result = $db->loadResult();
            return $result;
        }

        function getModuleName($pid,$mid){
            $pid = intval($pid);
            $mid = intval($mid);
            $db = JFactory::getDbo();
            $query = "SELECT title  
                        FROM #__guru_days
                        WHERE id =". $mid ." AND pid =' ". $pid. "'";
            $db->setQuery($query);
            $result = $db->loadResult();
            return $result;
        }

        function getLessonName($lid){
            $lid = intval($lid);
            $db = JFactory::getDbo();
            $query = "SELECT name  
                        FROM #__guru_task
                        WHERE id =". $lid;
            $db->setQuery($query);
            $result = $db->loadResult();
            return $result;
        }
}*/


class QikeyModelCredits extends JModelList{
    function __construct() 
    { 
       parent::__construct(); 

        $application = JFactory::getApplication() ; 

        $config = JFactory::getConfig() ; 

        $limitstart = JRequest::getInt( 'limitstart', 0 );
        $limit = $application->getUserStateFromRequest( 'global.list.limit', 
            'limit', $config->get('config.list_limit'), 'int' );        

        $this->setState('limitstart', $limitstart); 
        $this->setState('limit', $limit); 
    }

    function _loadData() 
    { 
        if (empty($this->_data) && empty($this->_total)) 
        { 
            $user = JFactory::getUser();
            $this->jomId = $user->id;
            $jinput = JFactory::getApplication()->input;
            $fromDate = $jinput->getVar('fromDate');
            $toDate = $jinput->getVar('toDate');
            if ($fromDate == null || $toDate == null){
                $query = "SELECT #__guru_task.name AS lesson, #__guru_task.duration, #__guru_task.credit, #__guru_task.difficultylevel, #__guru_task.id, #__guru_task.alias, #__guru_program.name AS activity, #__guru_program.description, #__guru_program.catid, #__guru_category.name AS category
                        FROM #__qikey_completed_lesson
                        LEFT JOIN #__guru_task
                        ON #__qikey_completed_lesson.lesson_id=#__guru_task.id
                        LEFT JOIN #__guru_program
                        ON #__qikey_completed_lesson.course_id=#__guru_program.id
                        LEFT JOIN #__guru_category
                        ON #__guru_program.catid=#__guru_category.id
                        WHERE #__qikey_completed_lesson.user_id = $this->jomId
                        ORDER BY #__qikey_completed_lesson.completed_date DESC";
            } else {
                $query = "SELECT * 
                            FROM #__qikey_completed_lesson 
                            WHERE user_id =". $this->jomId ." AND completed_date BETWEEN '" . $fromDate . "' AND '" . $toDate . " 23:59:59'";
            }
            
            $this->_db->setQuery($query); 

            $this->_data = $this->_db->loadObjectList(); 
            $this->_total = count( $this->_data ) ; 

        } 

        return $this->_data ; 
    } 

    function getData() 
    { 
        $this->_loadData() ; 
        
        $limitstart = $this->getState('limitstart');
        $limit = $this->getState('limit');

           return array_slice( $this->_data, $limitstart, $limit ); 
    }

    function totalItems() 
    { 
        return $this->_total; 
    } 

    function getPagination() 
    { 
        $this->_loadData() ;

        if (empty($this->_pagination)) 
        { 
            jimport('joomla.html.pagination'); 

            $limitstart = $this->getState('limitstart');
            $limit = $this->getState('limit');
            $total = $this->totalItems();

            $this->_pagination = new JPagination( $total, $limitstart, $limit ); 
        } 

        return $this->_pagination; 
    }

    function getProfile() 
    {
        
    }

    function getTotalCredits($user_id)
    {

    } 

}