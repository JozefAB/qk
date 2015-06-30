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

jimport ('joomla.application.component.controller');

class guruAdminControllerguruPrograms extends guruAdminController{
	var $model = null;
	
	function __construct(){
		parent::__construct();
		$this->registerTask ("add", "edit");
		$this->registerTask ("", "listPrograms");
		$this->registerTask ("show", "showStudents");
		$this->registerTask( 'export_button', 'exportFile' );
		$this->registerTask ("selectCourse", "listPrograms");		
		$this->registerTask ("unpublish", "publish");
		$this->registerTask ("delete", "del");
		$this->registerTask ("saveorder", "saveorder");	
		$this->registerTask ("orderup", "orderup");
		$this->registerTask ("orderdown", "orderdown");
		$this->registerTask ("orderupfile", "orderFile");
		$this->registerTask ("orderdownfile", "orderFile");
		$this->registerTask ("saveorderfile", "saveorderFile");
		$this->registerTask ("savenbquizzes", "savenbquizzes");
		$this->registerTask ("saveOrderAjax", "saveOrderAjax");
		$this->registerTask ("saveOrderExercices", "saveOrderExercices");
		$this->registerTask ("apply32", "apply");
		$this->registerTask ("approve", "approve");
		$this->registerTask ("pending", "pending");
		$this->_model = $this->getModel("guruProgram");
	}
	
	function orderFile(){
		$id = JRequest::getVar('id', "0", "post");
		$this->_model->orderFile();
		$link = "index.php?option=com_guru&controller=guruPrograms&task=edit&cid[]=".intval($id);
		$this->setRedirect($link, $msg);
	}
	
	function showStudents(){
		$view = $this->getView("guruPrograms", "html"); 
		$view->setLayout("studentsenrolled");
		$view->setModel($this->_model, true);
		$view->studentsenrolled();	
	}
	
	function saveorderFile(){
		$id = JRequest::getVar('id', "0", "post");
		$this->_model->saveorderFile();
		$link = "index.php?option=com_guru&controller=guruPrograms&task=edit&cid[]=".intval($id);
		$this->setRedirect($link, $msg);
	}
		
	function orderup(){
		if($this->_model->orderUp()){
			$msg = JText::_('GURU_FM_SAVE');
		}
		else{
			$msg = JText::_('GURU_FM_NOT_SAVE');
		}
		$link = "index.php?option=com_guru&controller=guruPrograms";
		$this->setRedirect($link, $msg);
	}
	
	function orderdown(){
		if($this->_model->orderDown()) {
			$msg = JText::_('GURU_FM_SAVE');
		}
		else{
			$msg = JText::_('GURU_FM_NOT_SAVE');
		}
		$link = "index.php?option=com_guru&controller=guruPrograms";
		$this->setRedirect($link, $msg);
	}

	function saveorder(){
		if($this->_model->saveOrder()){
			$msg = JText::_('GURU_FM_SAVE');
		}
		else{
			$msg = JText::_('GURU_FM_NOT_SAVE');
		}
		$link = "index.php?option=com_guru&controller=guruPrograms";
		$this->setRedirect($link, $msg);
	}
	
	function del(){
		$task2 = JRequest::getVar("task2", "", "get");
		$msg = "";
		$id = JRequest::getVar("id");
		$cids = JRequest::getVar('cid', "", "get");
		$cid = $cids[0];		

		if($task2 != NULL && $task2 != "" && $task2 == "edit"){			
			if(!$this->_model->delFileMedia($id, $cid)){
				$msg = JText::_('GURU_FM_CANTREMOVED');
			}
			else{
				$msg = JText::_('GURU_FM_REMOVED');
			}							
			$link = "index.php?option=com_guru&controller=guruPrograms&task=edit&cid[]=".$id;
			$this->setRedirect($link, $msg);
		}
		else{
			$tid = JRequest::getVar('tid','0','get','int');	
			$cid = JRequest::getVar('cid',array(),'get','array');	
			$cid = intval($cid[0]);
			if (!$this->_model->delmedia($tid,$cid)) {
				$msg = JText::_('GURU_CS_CANTREMOVED');
			}
			else{
				$msg = JText::_('GURU_CS_REMOVED');
			}
			$link = "index.php?option=com_guru&controller=guruPrograms&task=edit&cid[]=".$tid;
			$this->setRedirect($link, $msg);
		}
	}
	
	function listPrograms(){		
		$view = $this->getView("guruPrograms", "html"); 
		$view->setModel($this->_model, true);
		$view->display();
	}
	
	function upload(){
		JRequest::setVar ("hidemainmenu", 1);
		$is_sbox= JRequest::getVar("is_sbox","0","post","int");
		$view = $this->getView("guruPrograms", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);
		$view->uploadimage();
		$progid = $this->_model->store();
		
		if($is_sbox == '1'){
			$tmpl = '&tmpl=component';
		}	
		else{
			$tmpl = '';
		}
		$link = "index.php?option=com_guru&controller=guruPrograms&task=edit".$tmpl."&cid[]=".$progid;
		$this->setRedirect($link, $msg);
	}

	function edit(){
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruPrograms", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);
        
		$model = $this->getModel("guruSubplan");
		$view->setModel($model);

		$model = $this->getModel("guruSubremind");
		$view->setModel($model);
		$view->editForm();
	}
	
	function editsbox(){
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruPrograms", "html");
		$view->setLayout("editFormsbox");
		$view->setModel($this->_model, true);
		$view->editForm();
	}


	function save(){
		if($this->_model->store()){
			$msg = JText::_('GURU_CS_SAVE');
			$notice = '';
		}
		else{
			$msg = JText::_('GURU_CS_NOTSAVE');
			if($_SESSION["empltyprice"] ==1){
				$msg = $msg." ".JText::_('GURU_NO_EMPTY_PRICE');	
			}
			$notice = 'warning';
			unset($_SESSION["empltyprice"]);
		}
		$link = "index.php?option=com_guru&controller=guruPrograms";
		$this->setRedirect($link, $msg,$notice);
	}
	
	function apply(){
		$id = $this->_model->store();
		if(intval($id) > 0){
			$msg = JText::_('GURU_CS_APPLY_DONE');
			$notice = '';
		}
		else{
			$msg = JText::_('GURU_CS_APPLY_FAILED');
			if($_SESSION["empltyprice"] ==1){
				$msg = $msg." ".JText::_('GURU_NO_EMPTY_PRICE');	
			}
			$notice = 'warning';
			unset($_SESSION["empltyprice"]);
		}
		$link = "index.php?option=com_guru&controller=guruPrograms&task=edit&cid[]=".$id;
		$this->setRedirect($link, $msg, $notice);
	}
	
	function addmedia(){
	 	JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruPrograms", "html"); 
		$view->setLayout("addmedia");
		$view->setModel($this->_model, true);
		$view->addmedia();
	}

	function addcourse(){
	 	JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruPrograms", "html"); 
		$view->setLayout("addcourse");
		$view->setModel($this->_model, true);
		$view->addcourse();
	}

	function remove(){
		$notice = "";
		$return = $this->_model->delete();
		
		if($return === TRUE){
			$msg = JText::_('GURU_CS_REMOVED');
		}
		elseif($return === FALSE){
			$msg = JText::_('GURU_CS_CANTREMOVED');
			$notice = "error";
		} 
		else{
			$msg = JText::_('GURU_CAN_NOT_DELETE_COURSE');
			$notice = "error";
		}
		
		$link = "index.php?option=com_guru&controller=guruPrograms";
		$this->setRedirect($link, $msg, $notice);
	}

	function cancel(){
	 	$msg = JText::_('GURU_CS_OPCANC');
		$link = "index.php?option=com_guru&controller=guruPrograms";
		$this->setRedirect($link, $msg);
	}
		
	function publish(){
		$task2 = JRequest::getVar("task2");
		$res = "";
		$cid = JRequest::getVar("id");
			
		if($task2 != NULL && $task2 != "" && $task2 == "edit"){
			$res = $this->_model->publishEdit();
			if(!$res){ 
				$msg = JText::_('GURU_CS_ACTION_ERROR');
			}
			elseif($res == -1){
				$msg = JText::_('GURU_FM_UNPUBLISHED');
			}
			elseif($res == 1){
				$msg = JText::_('GURU_FM_PUBLISHED');
			}
			else{
				$msg = JText::_('GURU_CS_ACTION_ERROR');
			}				
			$link = "index.php?option=com_guru&controller=guruPrograms&task=edit&cid[]=".$cid;
			$this->setRedirect($link, $msg);
		}
		else{
			$res = $this->_model->publish();
			if(!$res){ 
				$msg = JText::_('GURU_CS_ACTION_ERROR');
			}
			elseif($res == -1){
				$msg = JText::_('GURU_CS_UNPUBLISHED');
			}
			elseif($res == 1){
				$msg = JText::_('GURU_CS_PUBLISHED');
			}
			else{
				$msg = JText::_('GURU_CS_ACTION_ERROR');
			}	
			$link = "index.php?option=com_guru&controller=guruPrograms";
			$this->setRedirect($link, $msg);
		}		
	}
		
	function unpublish(){
		$res = $this->_model->publish();
		if(!$res){
			$msg = JText::_('GURU_CS_ACTION_ERROR');
		} 
		elseif ($res == -1) {
			$msg = JText::_('GURU_CS_UNPUBLISHED');
		} 
		elseif ($res == 1) {
			$msg = JText::_('GURU_CS_PUBLISHED');
		} 
		else{
			$msg = JText::_('GURU_CS_ACTION_ERROR');
		}
		$link = "index.php?option=com_guru&controller=guruPrograms";
		$this->setRedirect($link, $msg);
	}

	function duplicate () { 
		$res = $this->_model->duplicate();
		if ($res == 1) {
			$msg = JText::_('GURU_CS_DUPLICATE_SUCC');
		}
		else{
			$msg = JText::_('GURU_CS_DUPLICATE_ERR');
		}
		$link = "index.php?option=com_guru&controller=guruPrograms";
		$this->setRedirect($link, $msg);
	}
	
	function exportFile(){
		$this->_model->exportFile();			
	}	
	function savenbquizzes(){
		$id= JRequest::getVar("id");
		$db = JFactory::getDBO();
		
		$sql="Select id_final_exam  from #__guru_program where id='".$id."' ";
		$db->setQuery($sql);
		$db->query();
		$resultidfe = $db->loadColumn();
		$resultidfe = $resultidfe["0"];
		
		$sql = "SELECT id from `#__guru_days` where pid=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$resulids = $db->loadColumn();
		
				
		$sql = "SELECT distinct media_id from `#__guru_mediarel` where type_id IN(".implode(",",$resulids).") and type='dtask'";
		$db->setQuery($sql);
		$db->query();
		$resultmids = $db->loadColumn();

		
		$sql = "SELECT distinct media_id from `#__guru_mediarel` where type_id IN(".implode(",",$resultmids).") and type='scr_m' and layout=12 ";
		$db->setQuery($sql);
		$db->query();
		$resultqids = $db->loadColumn();




		$result=array_diff($resultqids, array($resultidfe));
		$result = count($result);

		$sql="Select updated from #__guru_program where id='".$id."' ";
		$db->setQuery($sql);
		$db->query();
		$result1 = $db->loadColumn();
		$result1 = $result1["0"];
		
		$sql="Select hasquiz from #__guru_program where id='".$id."' ";
		$db->setQuery($sql);
		$db->query();
		$result2 = $db->loadColumn();
		$result2 = $result2["0"];

		if($result != 0){
			if($result1 == 0){
				$sql="update #__guru_program set hasquiz =".$result." where id='".$id."'";
				$db->setQuery($sql);
				$db->query();
				
				$query = "update #__guru_program set updated='1' where id='".intval($id)."'";
				$db->setQuery($query);
				$db->query();
			}
		}
	}
	
	public function saveOrderAjax(){
		// Get the arrays from the Request
		
		$originalOrder = explode(',', $this->input->getString('original_order_values'));

		$model = $this->getModel("guruProgram");
		// Save the ordering
		$return = $model->saveOrder();
		if ($return){
			echo "1";
		}
		// Close the application
		JFactory::getApplication()->close();
	}
	
	public function saveOrderExercices(){
		// Get the arrays from the Request
		$originalOrder = explode(',', $this->input->getString('original_order_values'));

		$model = $this->getModel("guruProgram");
		// Save the ordering
		$return = $model->saveorderFile();
		if ($return){
			echo "1";
		}
		// Close the application
		JFactory::getApplication()->close();
	}
	
	function approve(){
		$res = $this->_model->approve();
		if($res === TRUE){
			$msg = JText::_('GURU_CS_APPROVE_SUCC');
		}
		else{
			$msg = JText::_('GURU_CS_APPROVE_ERR');
		}
		$link = "index.php?option=com_guru&controller=guruPrograms";
		$this->setRedirect($link, $msg);
	}
	
	function pending(){
		$res = $this->_model->pending();
		if($res === TRUE){
			$msg = JText::_('GURU_CS_PENDING_SUCC');
		}
		else{
			$msg = JText::_('GURU_CS_PENDING_ERR');
		}
		$link = "index.php?option=com_guru&controller=guruPrograms";
		$this->setRedirect($link, $msg);
	}
};

?>