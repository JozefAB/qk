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

JHTML::_('behavior.modal', 'a.modal');
JHTML::_('behavior.framework');
?>
<script>
function editgurucomment1(comid){
	var gurutext = document.getElementById('gurupostcomment'+comid).innerHTML;
	document.getElementById('gurupostcomment'+comid).style.display = "none";
	document.getElementById("message1"+comid).value = gurutext;
	document.getElementById('message1'+comid).style.display = "block";
	if(document.getElementById('delete'+comid)){
		document.getElementById('delete'+comid).style.display = "none";
	}
	if(document.getElementById('edit'+comid)){
		document.getElementById('edit'+comid).style.display = "none";
	}
	document.getElementById('save'+comid).style.display = "block";
}
</script>
<?php

jimport ('joomla.application.component.controller');
class guruControllerguruTasks extends guruController {
	var $model = null;

	
	function __construct () {

		parent::__construct();

		$this->registerTask ("add", "edit");
		$this->registerTask ("", "view");
		$this->registerTask ("unpublish", "publish");	
		$this->registerTask ("exercise","exerciseFile");
		$this->registerTask ("saveInDb", "saveInDb");
		$this->registerTask ("saveInDbQuiz", "saveInDbQuiz");
		$this->registerTask ("saveInDbaseHowMany", "saveInDbaseHowMany");
		$this->registerTask ("viewcertificate", "viewcertificate");
		$this->registerTask ("savecertificatepdf", "savecertificatepdf");
		$this->registerTask ("lessonmessage", "lessonmessage");
		$this->registerTask ("deletecom", "deletecom");
		$this->registerTask ("editgurucomment", "editgurucomment");
		$this->registerTask ("editformgurupost", "editformgurupost");
		$this->registerTask ("calculatecertificate", "calculatecertificate");
		$this->registerTask ("showCertificateFr", "showCertificateFr");
		
		$this->registerTask ("quizz_fe_submit", "get_quiz_result");
		$this->_model = $this->getModel("guruTask");
		
	}

	function listTasks() {
		$view = $this->getView("guruTasks", "html"); 
		$view->setModel($this->_model, true);
		$view->display();
	}
	
	function view () {
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("view");
		$view->setModel($this->_model, "getTask");	
		$view->show();
	}	
	
	function viewguest () {
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("viewguest");
		$view->setModel($this->_model, true);	
		$view->show();
	}		

	function edit () {
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);
	
		//$model =& $this->getModel("adagencyConfig");
		//$view->setModel($model);

		$view->editForm();
	}

	function addmedia () {
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("addmedia");
		$view->setModel($this->_model, true);
		$view->addmedia();

	}
	
	function addmainmedia () {
		JRequest::setVar ("hidemainmenu", 1); 
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("addmainmedia");
		$view->setModel($this->_model, true);
		$view->addmedia();

	}
	
	function save () {
		if ($this->_model->store() ) {
			$msg = JText::_('AD_CMP_SAVED');
		} else {
			$msg = JText::_('AD_CMP_NOT_SAVED');
		}
		$link = "index.php?option=com_guru&view=guruTasks";
		$this->setRedirect($link, $msg);

	}


	function remove () {
		if (!$this->_model->delete()) {
			$msg = JText::_('GURU_TASKS_DELFAILED');
		} else {
		 	$msg = JText::_('GURU_TASKS_DEL');
		}
		
		$link = "index.php?option=com_guru&view=guruTasks";
		$this->setRedirect($link, $msg);
		
	}
	
	function del () { 
		$tid = intval($_GET['tid']); 
		$cid = intval($_GET['cid'][0]);
		if (!$this->_model->delmedia($tid,$cid)) {
			$msg = JText::_('GURU_TASKS_DELFAILED');
		} else {
		 	$msg = JText::_('GURU_TASKS_DEL');
		}
		
		$link = "index.php?option=com_guru&view=guruTasks&task=edit&cid[]=".$tid;
		$this->setRedirect($link, $msg);
		
	}

	function cancel () {
	 	$msg = JText::_('AD_OP_CANCELED');
		$link = "index.php?option=com_guru&view=guruTasks";
		$this->setRedirect($link, $msg);
	}
	
	
	function approve () { 
		$res = $this->_model->publish();

		if (!$res) {
			$msg = JText::_('AD_CMP_UNERROR');
		} elseif ($res == -1) {
		 	$msg = JText::_('AD_CMP_UNNAP');
		} elseif ($res == 1) {
			$msg = JText::_('AD_CMP_APPV');
		} else {
                 	$msg = JText::_('AD_CMP_UNERROR');
		}
		
		$link = "index.php?option=com_guru&view=guruTasks";
		$this->setRedirect($link, $msg);


	}
		function unapprove () {
			$res = $this->_model->publish();
	
			if (!$res) {
				$msg = JText::_('AD_CMP_ERROR');
			} elseif ($res == -1) {
			 	$msg = JText::_('AD_CMP_UNNAP');
			} elseif ($res == 1) {
				$msg = JText::_('AD_CMP_APPV');
			} else {
	                 	$msg = JText::_('AD_CMP_ERROR');
			}
			
			$link = "index.php?option=com_guru&view=guruTasks";
		$this->setRedirect($link, $msg);
	
	
		}
		
		function publish () { 
			$res = $this->_model->publish();

			if (!$res) { 
				$msg = JText::_('AD_CMP_UNERROR');
			} elseif ($res == -1) {
			 	$msg = JText::_('AD_CMP_UNNAP');
			} elseif ($res == 1) {
				$msg = JText::_('AD_CMP_APPV');
			} else {
	                 	$msg = JText::_('AD_CMP_UNERROR');
			}
			
			$link = "index.php?option=com_guru&view=guruTasks";
		$this->setRedirect($link, $msg);

		}
		
		function unpublish () {
			$res = $this->_model->publish();
	
			if (!$res) {
				$msg = JText::_('AD_CMP_ERROR');
			} elseif ($res == -1) {
			 	$msg = JText::_('AD_CMP_UNNAP');
			} elseif ($res == 1) {
				$msg = JText::_('AD_CMP_APPV');
			} else {
	                 	$msg = JText::_('AD_CMP_ERROR');
			}
			
			$link = "index.php?option=com_guru&view=guruTasks";
		$this->setRedirect($link, $msg);
		}
		
		function pause () {
		if (!$this->_model->pause()) {
			$msg = JText::_('AD_CMP_CANTPAUSE');
		} else {
		 	$msg = JText::_('AD_CMP_PAUSED');
		}
		
		$link = "index.php?option=com_guru&view=guruTasks";
		$this->setRedirect($link, $msg);
		
		}
		
		function unpause () {
			if (!$this->_model->unpause()) {
				$msg = JText::_('AD_CMP_CANTUNPAUSE');
			} else {
			 	$msg = JText::_('AD_CMP_UNPAUSED');
			}
			
			$link = "index.php?option=com_guru&view=guruTasks";
		$this->setRedirect($link, $msg);
			
		}
		
	function savemedia () {
		$insertit = intval($_POST['idmedia']);
		$taskid = intval($_POST['idtask']);
		$mainmedia = intval($_POST['mainmedia']);
		$this->_model->addmedia($insertit, $taskid, $mainmedia);
	}
	
	function saveInDb(){
		$database = JFactory::getDBO();	
		$user = JFactory::getUser();
		$user_id = $user->id;
		$saved_quiz_id = JRequest::getVar("saved_quiz_id");
		$quiz_id =  JRequest::getVar("quiz_id");
		$ans_givedbyuser = JRequest::getVar("ans_gived");
		$qstion_id = JRequest::getVar("qstion_id");	
		$time_quiz_taken = JRequest::getVar("time_quiz_taken");	
		$questions_ids_list = JRequest::getVar("questions_ids_list");

		
		$sql = "SELECT is_final from #__guru_quiz where id=".$quiz_id;
		$database->setQuery($sql);
		$database->query();
		$isfinal=$database->loadColumn();
		$isfinal = $isfinal[0];
		
		
		$sql = "SELECT show_nb_quiz_select_up from #__guru_quiz where id=".$quiz_id;
		$database->setQuery($sql);
		$database->query();
		$show_nb_quiz_select_up=$database->loadResult();
		
		if($isfinal == 0){
			$sql = "SELECT id FROM #__guru_questions WHERE qid=".intval($quiz_id)." ORDER BY `reorder`";
		}
		else{
			$sql = "SELECT 	quizzes_ids FROM #__guru_quizzes_final WHERE qid=".$quiz_id;
			$database->setQuery($sql);
			$database->query();
			$result=$database->loadResult();	
			$result_qids = explode(",",trim($result,","));
			
			if(count($result_qids) == 0 || $result_qids["0"] == ""){
				$result_qids["0"] = 0;
			}
		
			$sql  = "SELECT id FROM #__guru_questions WHERE qid IN (".implode(",", $result_qids).") ORDER BY reorder";
		
		}
		$database->setQuery($sql);
		$database->query();
		$quiz_question_id= $database->loadObjectList();
		$qstion_id = $qstion_id - 1;
		
		$quiz_question_id = $quiz_question_id[$qstion_id]->id;	
		
		if($show_nb_quiz_select_up == 0){
			$quiz_question_id = explode(",", trim($questions_ids_list));
			$quiz_question_id  = $quiz_question_id [$qstion_id];
		}
		//$sql = 'DELETE FROM #__guru_quiz_question_taken WHERE `show_result_quiz_id`='.$saved_quiz_id.'';
		//$database->setQuery($sql);
		//$database->query();
		
		$sql = "INSERT INTO #__guru_quiz_question_taken (`user_id`, `show_result_quiz_id`, `answers_gived`,`question_id`, `question_order_no`) VALUES ('".$user_id."', '".$saved_quiz_id."', '".$ans_givedbyuser."', '".$quiz_question_id."', '".($qstion_id +1)."')";
		$database->setQuery($sql);
		$database->query();
		
		/*if($time_quiz_taken > 0 && $time_quiz_taken != "" && $time_quiz_taken < 11){
			$sql = "UPDATE #__guru_quiz_taken set `time_quiz_taken_per_user` = '".($time_quiz_taken-1)."' WHERE `quiz_id`=".intval($quiz_id)." AND `user_id`=".intval($user_id);
			$database->setQuery($sql);
			$database->query();
		}*/
	}
	function saveInDbQuiz(){
		$database = JFactory::getDBO();	
		$user = JFactory::getUser();
		$user_id = $user->id;
		$quiz_id = JRequest::getVar("quiz_id");
		$how_many_right_answers = JRequest::getVar("howmrans");
		$number_of_questions = JRequest::getVar("numbofquestions");
		$course_id = JRequest::getVar("course_id");	
		$score_quiz = $how_many_right_answers."|".$number_of_questions;
		$date = date('Y-m-d h:i:s');
		
		$sql1 = "SELECT time_quiz_taken FROM #__guru_quiz WHERE id=".$quiz_id;
		$database->setQuery($sql1);
		$resultt = $database->loadColumn();
		$resultt = $resultt[0];
		
		$sql2 = "SELECT count(user_id) FROM #__guru_quiz_taken WHERE user_id=".$user_id." and quiz_id=".$quiz_id;
		$database->setQuery($sql2);
		$resultu = $database->loadColumn();
		$iterator = 1;
		if($resultt < 11){
			if(intval($resultu["0"]) != 0){
				$iterator = intval($resultu["0"]) + 1;
			}
		}
		else{
			$iterator = 11;
		}
		
		$sql = 'INSERT INTO  #__guru_quiz_taken (`user_id`, `quiz_id`, `score_quiz`, `date_taken_quiz`, `pid`,`time_quiz_taken_per_user`) VALUES ('.$user_id.', '.$quiz_id.', "'.$score_quiz.'","'.$date.'", '.$course_id.', '.$iterator.')';
		$database->setQuery($sql);
		$database->query();
		
		$sql2 = "SELECT max(id) FROM #__guru_quiz_taken";
		$database->setQuery($sql2);
		$x = $database->loadColumn();
		$x = $x["0"];
		
		echo intval(trim($x));
		die();
	}
	function saveInDbaseHowMany(){
		$database = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id = $user->id;	
		$quiz_id = JRequest::getVar("quiz_id");
		$how_many_right_answers = JRequest::getVar("howmanyans");
		$number_of_questions = JRequest::getVar("numbofquestions");
		$saved_quiz_id = JRequest::getVar("saved_quiz_id");		
		$score_quiz = $how_many_right_answers."|".$number_of_questions;	
		$sql = 'UPDATE #__guru_quiz_taken set `score_quiz`= "'.$score_quiz.'" WHERE quiz_id='.$quiz_id.' and id='.$saved_quiz_id;
		$database->setQuery($sql);
		if($database->query()){
			echo "true";
		}
		else{
			echo "false";
		}
		die();
	}	
	function exerciseFile(){
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("exercise");
		$view->setModel($this->_model, "getExercise");	
		$view->showExercise();
	}
	function viewcertificate(){
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("certificatefront");
		$view->viewcertificate();
	}
	
	function savecertificatepdf(){
		$datac = JRequest::get('post',JREQUEST_ALLOWRAW);

		$db = JFactory::getDBO();
		$config = JFactory::getConfig();
		$user = JFactory::getUser();
		$user_id = $user->id;
		$sql = "SELECT `name` from #__guru_program WHERE `id` =".$datac['ci'];
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		
		
		
		$imagename = "SELECT * FROM #__guru_certificates WHERE id=1";
		$db->setQuery($imagename);
		$db->query();
		$imagename = $db->loadAssocList();
		
		$authorname = "SELECT name from #__users where id IN (SELECT author_id FROM #__guru_mycertificates WHERE user_id = ".intval($user_id)." AND course_id =".$datac['ci']." )";
		$db->setQuery($authorname);
		$db->query();
		$authorname = $db->loadResult();
		
		$date_completed = "SELECT datecertificate FROM #__guru_mycertificates WHERE user_id=".intval($user_id)." AND course_id=".intval($datac['ci']);
		$db->setQuery($date_completed);
		$db->query();
		$date_completed = $db->loadResult();
		
		$format = "SELECT datetype FROM #__guru_config WHERE id=1";
		$db->setQuery($format);
		$db->query();
		$format = $db->loadResult();
		$date_completed = date($format, strtotime($date_completed));
		
		$completiondate = $date_completed;
		$sitename = $config->get('sitename');
		$coursename = $result;
		$site_url = JURI::root();
		$certificateid = $datac['id']; 
		
		$coursemsg = "SELECT certificate_course_msg FROM #__guru_program WHERE id=".intval($datac['ci']);
		$db->setQuery($coursemsg);
		$db->query();
		$coursemsg = $db->loadResult();
		
		$scores_avg_quizzes =  guruModelguruTask::getAvgScoresQ($user_id,$course_id);

 		$avg_quizzes_cert = "SELECT avg_certc FROM #__guru_program WHERE id=".intval($datac['ci']);
		$db->setQuery($avg_quizzes_cert);
		$db->query();
		$avg_quizzes_cert = $db->loadResult();


		$sql = "SELECT id_final_exam FROM #__guru_program WHERE id=".intval($datac['ci']);
		$db->setQuery($sql);
		$result = $db->loadResult();

		$sql = "SELECT hasquiz from #__guru_program WHERE id=".intval($datac['ci']);
		$db->setQuery($sql);
		$resulthasq = $db->loadResult();

		$sql = "SELECT max_score FROM #__guru_quiz WHERE id=".intval($result);
		$db->setQuery($sql);
		$result_maxs = $db->loadResult();

		$sql = "SELECT id, score_quiz, time_quiz_taken_per_user  FROM #__guru_quiz_taken WHERE user_id=".intval($user_id)." and quiz_id=".intval($result)." and pid=".intval($datac['ci'])." ORDER BY id DESC LIMIT 0,1";
		$db->setQuery($sql);
		$result_q = $db->loadObject();

		$first= explode("|", @$result_q->score_quiz);

		@$res = intval(($first[0]/$first[1])*100);

		if($resulthasq == 0 && $scores_avg_quizzes == ""){
			$avg_certc1 = "N/A";
		}
		elseif($resulthasq != 0 && $scores_avg_quizzes == ""){
			$avg_certc1 = "N/A";
		}
		elseif($resulthasq != 0 && isset($scores_avg_quizzes)){
		if($scores_avg_quizzes >= intval($avg_quizzes_cert)){
			$avg_certc1 =  $scores_avg_quizzes.'%'; 
		}
		else{
			$avg_certc1 = $scores_avg_quizzes.'%';
		}
	}

	if($result !=0 && $res !="" ){
		if( $res >= $result_maxs){
			$avg_certc = $res.'%';
		}
		elseif($res < $result_maxs){
			$avg_certc = $res.'%';
		}
	}
	elseif(($result !=0 && $result !="")){
		$avg_certc = "N/A";
	}
	elseif($result ==0 || $result ==""){
		$avg_certc = "N/A";
	}
		
		
		$firstnamelastname = "SELECT firstname, lastname FROM #__guru_customer WHERE id=".intval($user_id);
		$db->setQuery($firstnamelastname);
		$db->query();
		$firstnamelastname = $db->loadAssocList();
		
		$certificate_url = JUri::base()."index.php?option=com_guru&view=guruOrders&task=printcertificate&opt=".$certificateid."&cn=".$coursename."&an=".$authorname."&cd=".$completiondate."&id=".$certificateid."&ci=".$datac['ci'];
		$certificate_url = str_replace(" ", "%20", $certificate_url);

		
		$imagename[0]["templates1"]  = str_replace("[SITENAME]", $sitename, $imagename[0]["templates1"]);
		$imagename[0]["templates1"]  = str_replace("[STUDENT_FIRST_NAME]", $firstnamelastname[0]["firstname"], $imagename[0]["templates1"]);
		$imagename[0]["templates1"]  = str_replace("[STUDENT_LAST_NAME]", $firstnamelastname[0]["lastname"], $imagename[0]["templates1"]);
		$imagename[0]["templates1"]  = str_replace("[SITEURL]", $site_url, $imagename[0]["templates1"]);
		$imagename[0]["templates1"]  = str_replace("[CERTIFICATE_ID]", $certificateid, $imagename[0]["templates1"]);
		$imagename[0]["templates1"]  = str_replace("[COMPLETION_DATE]", $completiondate, $imagename[0]["templates1"]);
		$imagename[0]["templates1"]  = str_replace("[COURSE_NAME]", $coursename, $imagename[0]["templates1"]);
		$imagename[0]["templates1"]  = str_replace("[AUTHOR_NAME]", $authorname, $imagename[0]["templates1"]);
		$imagename[0]["templates1"]  = str_replace("[CERT_URL]", $certificate_url, $imagename[0]["templates1"]);
		$imagename[0]["templates1"]  = str_replace("[COURSE_MSG]", $coursemsg, $imagename[0]["templates1"]);
		$imagename[0]["templates1"]  = str_replace("[COURSE_AVG_SCORE]", $avg_certc1, $imagename[0]["templates1"]);
        $imagename[0]["templates1"]  = str_replace("[COURSE_FINAL_SCORE]", $avg_certc, $imagename[0]["templates1"]);


		
		$datac["content"] = $imagename[0]["templates1"];

		while (ob_get_level())
		ob_end_clean();
		header("Content-Encoding: None", true);
		
		if(strlen($datac["color"]) == 3) {
		  $r = hexdec(substr($datac["color"],0,1).substr($datac["color"],0,1));
		  $g = hexdec(substr($datac["color"],1,1).substr($datac["color"],1,1));
		  $b = hexdec(substr($datac["color"],2,1).substr($datac["color"],2,1));
	   } else {
		  $r = hexdec(substr($datac["color"],0,2));
		  $g = hexdec(substr($datac["color"],2,2));
		  $b = hexdec(substr($datac["color"],4,2));
	   }
	   	
		$datac["bgcolor"] = explode(":",$datac["bgcolor"] );
		$datac["bgcolor"][1]=str_replace("#", "", $datac["bgcolor"][1]);
		
		if(strlen($datac["bgcolor"][1] ) == 3) {
		  $rg = hexdec(substr($datac["bgcolor"][1],0,1).substr($datac["bgcolor"][1],0,1));
		  $gg = hexdec(substr($datac["bgcolor"][1],1,1).substr($datac["bgcolor"][1],1,1));
		  $bg = hexdec(substr($datac["bgcolor"][1],2,1).substr($datac["bgcolor"][1],2,1));
	   } else {
		  $rg = hexdec(substr($datac["bgcolor"][1],0,2));
		  $gg = hexdec(substr($datac["bgcolor"][1],2,2));
		  $bg = hexdec(substr($datac["bgcolor"][1],4,2));
	   }
		
		if($imagename[0]["library_pdf"] == 0){
			require (JPATH_SITE.DS."components".DS."com_guru".DS."helpers".DS."fpdf.php");
			
			$pdf = new PDF('L', 'mm', 'A5');
	
			$pdf->SetFont($datac["font"],'',12);
			$pdf->SetTextColor($r,$g,$b);
			
			//set up a page
			$pdf->AddPage();
	
			if($datac["image"] !=""){
				$pdf->Image(JUri::base()."images/stories/guru/certificates/".$datac["image"],-4,-1,210, 150);
				//$pdf->Cell(0,75,JText::_("GURU_CERTIFICATE_OF_COMPLETION"),0,1,'C');
	
			}
			else{
				$pdf->SetFillColor($rg,$gg,$bg);
				//$pdf->Cell(0,115,JText::_("GURU_CERTIFICATE_OF_COMPLETION"),0,1,'C',true);
	
			}
			$pdf->Ln(20);
			$pdf->SetXY(100,50);
			$pdf->WriteHTML(iconv('UTF-8', 'ISO-8859-1', $imagename[0]["templates1"]),true);
			$pdf->Output('certificate'.$certificateid.'.pdf','D'); 
		}
		else{
			require (JPATH_SITE.DS."components".DS."com_guru".DS."helpers".DS."MPDF".DS."mpdf.php");
			$pdf = new mPDF('utf-8','A4-L');
			$imagename[0]["templates1"] = '<style> body { font-family:"'.strtolower ($datac["font"]).'" ; color: rgb('.$r.', '.$g.', '.$b.'); }</style>'.$imagename[0]["templates1"];
			
			
			//set up a page
			$pdf->AddPage('L');
	
			if($datac["image"] !=""){
				$pdf->Image(JPATH_BASE."/images/stories/guru/certificates/".$datac["image"],0,0,298, 210, 'jpg', '', true, false);
				//$pdf->Cell(0,75,JText::_("GURU_CERTIFICATE_OF_COMPLETION"),0,1,'C');
				
	
			}
			else{
				$pdf->SetFillColor($rg,$gg,$bg);
				//$pdf->Cell(0,115,JText::_("GURU_CERTIFICATE_OF_COMPLETION"),0,1,'C',true);
	
			}
			//$pdf->Ln(20);
			$pdf->SetXY(100,50);
			$pdf->SetDisplayMode('fullpage');  
			$pdf->WriteHTML($imagename[0]["templates1"]);
			$pdf->Output('certificate'.$certificateid.'.pdf','D'); 
			exit;
		}
	}
	
	function lessonmessage(){
		$datamessage['lessonid'] = JRequest::getVar("lessonid", "");
		$datamessage['message'] = urldecode(JRequest::getVar("message", ""));
		
		$db = JFactory::getDBO();
		$config = JFactory::getConfig();
		$user = JFactory::getUser();
		
		$sql = "SELECT max(thread) from #__kunena_messages";
		$db->setQuery($sql);
		$db->query();
		$threadid = $db->loadResult();
		
		$sql = "select allow_edit from #__guru_kunena_forum where id=1";
		$db->setQuery($sql);
		$db->query();
		$allow_edit = $db->loadResult();
		
		$sql = "select allow_delete  from #__guru_kunena_forum where id=1";
		$db->setQuery($sql);
		$db->query();
		$allow_delete = $db->loadResult();
		
		$sql = "SELECT count(id) FROM  #__guru_task_kunenacomment WHERE id_lesson=".intval($datamessage['lessonid']);
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadResult();

		
		if($count == 0){
			$sql = "INSERT INTO #__guru_task_kunenacomment (id_lesson, thread) VALUES (".$datamessage['lessonid'].", ".($threadid+1).")";
			$db->setQuery($sql);
			$db->query();
		}
		
		$sql = "SELECT name from #__guru_task WHERE id=".intval($datamessage['lessonid']);
		$db->setQuery($sql);
		$db->query();
		$name = $db->loadResult();
		
		$sql = "SELECT count(id) FROM  #__kunena_messages WHERE subject='".$name."'";
		$db->setQuery($sql);
		$db->query();
		$count2 = $db->loadResult();
		
		
		$jnow = JFactory::getDate();
		$currentdate = $jnow->toSQL();
		
		$sql = "SELECT id from #__kunena_categories WHERE name ='".$name."'";
		$db->setQuery($sql);
		$db->query();
		$idcat = $db->loadResult();

		
		$sql = "SELECT id from #__kunena_messages WHERE subject ='".$name."' and parent =0 ";
		$db->setQuery($sql);
		$db->query();
		$idparent = $db->loadResult();
		
		if($count2 == 0){
			$sql = "INSERT INTO #__kunena_messages (`parent`, `thread`, `catid`, `name`, `userid`, `email`, `subject`, `time`, `ip`, `topic_emoticon`, `locked`, `hold`, `ordering`, `hits`, `moved`, `modified_by`, `modified_time`, `modified_reason`) VALUES (0, 0, ".$idcat.", '".$user->username."', ".$user->id.", '', '".$name."',  '".strtotime($currentdate)."', '127.0.0.1', 0,0,0,0,0,0,'','','')";	
			$db->setQuery($sql);
			$db->query();
			
			
		}
		
		$sql = "SELECT id from #__kunena_messages WHERE subject ='".$name."'";
		$db->setQuery($sql);
		$db->query();
		$idparent = $db->loadResult();
		
		$sql = "UPDATE  #__kunena_messages set thread ='".$idparent."' where parent =0 and subject ='".$name."' ";
		$db->setQuery($sql);
		$db->query();
		
		
		$sql = "SELECT id from #__kunena_categories WHERE name ='".$name."'";
		$db->setQuery($sql);
		$db->query();
		$idcat = $db->loadResult();
		
		if($idcat == "" || $idcat == 0){
			$idcat = 1;
		}
		
		if($count2 >0){
			$sql = "INSERT INTO #__kunena_messages (`parent`, `thread`, `catid`, `name`, `userid`, `email`, `subject`, `time`, `ip`, `topic_emoticon`, `locked`, `hold`, `ordering`, `hits`, `moved`, `modified_by`, `modified_time`, `modified_reason`) VALUES (".$idparent.", 0, ".$idcat.", '".$user->username."', ".$user->id.", '', '".$name."',  '".strtotime($currentdate)."', '127.0.0.1', 0,0,0,0,0,0,'','','')";
			$db->setQuery($sql);
			$db->query();
			
			
			$sql = "SELECT thread from #__kunena_messages WHERE subject ='".$name."' and parent =0";
			$db->setQuery($sql);
			$db->query();
			$thread = $db->loadResult();
			
			$sql = "UPDATE  #__kunena_messages set thread ='".$thread."' where parent =".$idparent." and subject ='".$name."' ";
			$db->setQuery($sql);
			$db->query();
			
		}
		
		
		$sql = "SELECT id from #__kunena_messages WHERE subject ='".$name."' order by id desc limit 0,1 ";
		$db->setQuery($sql);
		$db->query();
		$idmessage = $db->loadColumn();
		$idmessage = $idmessage["0"];
		
		$sql = "INSERT INTO `#__kunena_messages_text` (`mesid`, `message`) VALUES (".$idmessage.",'".$datamessage['message']."' )";
		$db->setQuery($sql);
		$db->query();
		
		if(isset($idcat) &&  $idcat != 0){
			$sql = "SELECT count(id) FROM  #__kunena_topics WHERE subject='".$name."'";
			$db->setQuery($sql);
			$db->query();
			$count3 = $db->loadResult();
				if($count3 == 0){
					$sql = "INSERT INTO `#__kunena_topics` ( `category_id`, `subject`, `icon_id`, `locked`, `hold`, `ordering`, `posts`, `hits`, `attachments`, `poll_id`, `moved_id`, `first_post_id`, `first_post_time`, `first_post_userid`, `first_post_message`, `first_post_guest_name`, `last_post_id`, `last_post_time`, `last_post_userid`, `last_post_message`, `last_post_guest_name`, `params`) VALUES (".$idcat.", '".$name."', 0, 0,0,0,1,0,0,0,0,".$idmessage.",'".strtotime($currentdate)."', ".$user->id.", '".$datamessage['message']."', '".$user->username."', ".$idmessage.",'".strtotime($currentdate)."',".$user->id.",'".$datamessage['message']."','".$user->username."','')";
					$db->setQuery($sql);
					$db->query();
				
				}
				else{
					$sql = "SELECT posts from #__kunena_topics WHERE subject ='".$name."' order by id desc limit 0,1 ";
					$db->setQuery($sql);
					$db->query();
					$posts = $db->loadResult();
					
					$subquery = "SELECT  first_post_id from #__kunena_topics WHERE subject ='".$name."' order by id desc limit 0,1";
					$db->setQuery($subquery);
					$db->query();
					$subquery = $db->loadColumn();
					$subquery = implode(",",$subquery);
					
					$sql = "SELECT count(id) from #__kunena_categories WHERE id IN(".$subquery.")";
					$db->setQuery($sql);
					$db->query();
					$count_firstpost = $db->loadResult();
					
					if($count_firstpost == 0){
						$sql = "UPDATE `#__kunena_topics` set  `posts`=".($posts +1).", `first_post_id`=".$idmessage.",`last_post_id`=".$idmessage.", `last_post_time`='".strtotime($currentdate)."', `last_post_userid`=".$user->id.", `last_post_message`='".$datamessage['message']."', `last_post_guest_name`='".$user->username."' WHERE subject='".$name."'";
						$db->setQuery($sql);
						$db->query();
					}
					else{
						$sql = "UPDATE `#__kunena_topics` set  `posts`=".($posts +1).", `last_post_id`=".$idmessage.", `last_post_time`='".strtotime($currentdate)."', `last_post_userid`=".$user->id.", `last_post_message`='".$datamessage['message']."', `last_post_guest_name`='".$user->username."' WHERE subject='".$name."'";
						$db->setQuery($sql);
						$db->query();
					}
				
				}
			
			
		
		
		}
		
		$sql = "SELECT id FROM  #__kunena_topics WHERE subject='".$name."'";
		$db->setQuery($sql);
		$db->query();
		$idtopic = $db->loadResult();
		
		$sql = "SELECT count(*) from #__kunena_user_topics WHERE user_id =".$user->id." and  topic_id =".$idtopic;
		$db->setQuery($sql);
		$db->query();
		$counttopics = $db->loadResult();
		
		if($counttopics == 0){
			$sql = "INSERT INTO `#__kunena_user_topics` (`user_id`, `topic_id`, `category_id`, `posts`, `last_post_id`, `owner`, `favorite`, `subscribed`, `params`)  VALUES (".$user->id.", '".$idtopic."', ".$idcat.", 1,".$idmessage.",1,0,1,'')";
			$db->setQuery($sql);
			$db->query();
		}
		else{
			$sql = "UPDATE `#__kunena_user_topics` set  `posts`=".($posts +1).", `last_post_id`=".$idmessage." WHERE user_id ='".$user->id."' and  topic_id =".$idtopic;
			$db->setQuery($sql);
			$db->query();
		}
		
		$sql = "UPDATE  #__kunena_messages set thread ='".$idtopic."' where subject ='".$name."' ";
		$db->setQuery($sql);
		$db->query();

		$sql = "UPDATE  #__kunena_categories set last_topic_id ='".intval($idtopic)."' ,`last_post_id`=".intval($idmessage)."  where id =".$idcat." and name ='".$name."' ";
		$db->setQuery($sql);
		$db->query();
		
		
		
		
		
		$sql ="select id, name, userid from #__kunena_messages WHERE subject='".$name."' order by id desc";
		$db->setQuery($sql);
		$db->query();
		$resultid = $db->loadAssocList();
		
		$jnow = JFactory::getDate();
		$date_currentk = $jnow->toSQL();									
		$int_current_datek = strtotime($date_currentk);
		
		$sql ="select id from #__kunena_categories WHERE name='".$name."' order by id desc limit 0,1";
		$db->setQuery($sql);
		$db->query();
		$catkunena = $db->loadResult();
		
		$sql ="select id from #__kunena_topics WHERE subject='".$name."' order by id asc limit 0,1";
		$db->setQuery($sql);
		$db->query();

		$idmess = $db->loadResult();
		
			 for($i=0; $i < count($resultid); $i++){	
					$sql = "select message from #__kunena_messages_text WHERE mesid=".$resultid[$i]["id"];
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadResult();
					
					$sql = "select time from #__kunena_messages WHERE id=".$resultid[$i]["id"];
					$db->setQuery($sql);
					$db->query();
					$datestart = $db->loadResult();
					
					$timepast = guruModelguruTask::get_time_difference($datestart,$int_current_datek);
					
					if($timepast["days"] == 0){
						if($timepast["hours"] == 0){
							if($timepast["minutes"] == 0){
								$difference = $timepast["seconds_ago"]." ".JText::_("GURU_FEW_SECS_AGO");
							}
							else{
								$difference = $timepast["minutes"]." ".JText::_("GURU_REAL_MINUTES")." ".JText::_("GURU_AGO");
							}
						}
						else{
							$difference = $timepast["hours"]." ".JText::_("GURU_REAL_HOURS").", ".
										  $timepast["minutes"]." ".JText::_("GURU_REAL_MINUTES")." ".JText::_("GURU_AGO");
						}
					}
					else{
						$difference = $timepast["days"]." ".JText::_("GURU_REAL_DAYS").", ".
									  $difference = $timepast["hours"]." ".JText::_("GURU_REAL_HOURS").", ".
									  $timepast["minutes"]." ".JText::_("GURU_REAL_MINUTES")." ".JText::_("GURU_AGO");
					}
					if($user->id == $resultid[$i]["userid"]){
						if($allow_delete == 0){
							$concat = 1;
							$buttons = '<span style="display: block; float:left;"><a href="#" id="delete'.$resultid[$i]["id"].'" onclick="javascript:deletegurucomment('.$datamessage['lessonid'].', '.$resultid[$i]["userid"].', '.$resultid[$i]["id"].'); return false;">'.JText::_("GURU_DELETE").'</a></span>';
																 			
						}
						else{
							$concat = 0;
						}
						if($allow_edit == 0){
							if($concat == 0){
								$buttons = '<span style="float:right;display:block"><a href="#" id="edit'.$resultid[$i]["id"].'" onclick="javascript:editgurucomment1('.$resultid[$i]["id"].'); return false;">'.JText::_("GURU_EDIT").'</a></span>';
							}
							else{
								$buttons .= '<span style="float:right;display:block"><a href="#" id="edit'.$resultid[$i]["id"].'" onclick="javascript:editgurucomment1('.$resultid[$i]["id"].'); return false;">'.JText::_("GURU_EDIT").'</a></span>';
							}	
						}
					}
					else{
						$buttons = " ";
					}
					
						echo '<div class="guru-header">
							<span><img  src="'.JUri::base().'components/com_guru/images/guru_comment.gif'.'"</span>
							<span>'.JText::_ ( "GURU_POSTED" ).':'.$difference.'</span>
							<span style="float:right;"><a href='.JUri::base().'index.php?option=com_kunena&view=topic&catid='.$catkunena.'&id='.$idmess.'&Itemid=0#'.$resultid[$i]["id"].'>#'.$resultid[$i]["id"].'</a></span>
							<span>'. JText::_ ( "GURU_COMMENTED_BY" ) . ' ' . $resultid[$i]["name"] .'</span>
							</div>
							<div class="guru-reply-body clearfix">
								<div style="display:block;" class="guru-text" id="gurupostcomment'.$resultid[$i]["id"].'">'.$result.'</div>
								<textarea style="display:none;" style="width:100%" name="message1'.$resultid[$i]["id"].'" id="message1'.$resultid[$i]["id"].'" rows="2" cols="90"></textarea>
                                 <input  class="btn btn-success" style="display:none;" id="save'.$resultid[$i]["id"].'" name="save" type="button" onclick="javascript:savegurucomment('.$datamessage['lessonid'].','.$resultid[$i]["id"].');" value="'.JText::_('GURU_SAVE').'" />
								<div>'.$buttons.'</div>
						   </div>';
				}
		
		
	}
	
	function deletecom(){
		$db = JFactory::getDBO();
		$comid = JRequest::getVar("comid", "");
		$uid = JRequest::getVar("uid", "");
		$lid = JRequest::getVar("lessonid", "");
		$user = JFactory::getUser();
		
		$jnow = JFactory::getDate();
		$date_currentk = $jnow->toSQL();									
		$int_current_datek = strtotime($date_currentk);
		
		$sql = "SELECT name from #__guru_task WHERE id=".intval($lid);
		$db->setQuery($sql);
		$db->query();
		$name = $db->loadResult();
		
		$sql = "DELETE from #__kunena_messages WHERE id =".$comid." and userid=".$uid." ";
		$db->setQuery($sql);
		$db->query();
		
		
		
		$sql = "SELECT id FROM  #__kunena_topics WHERE subject='".$name."'";
		$db->setQuery($sql);
		$db->query();
		$idtopic = $db->loadResult();
		
		$sql = "SELECT id from #__kunena_messages WHERE subject ='".$name."' order by id desc limit 0,1 ";
		$db->setQuery($sql);
		$db->query();
		$idmessage = $db->loadResult();
		
		$sql = "SELECT id from #__kunena_categories WHERE name ='".$name."'";
		$db->setQuery($sql);
		$db->query();
		$idcat = $db->loadResult();

		$sql = "UPDATE  #__kunena_categories set last_topic_id ='".intval($idtopic)."' ,`last_post_id`=".intval($idmessage).", `last_post_time`='".$int_current_datek ."'  where id =".$idcat." and name ='".$name."' ";
		$db->setQuery($sql);
		$db->query();
		
		$sql = "UPDATE  #__kunena_topics set first_post_id ='".$idmessage."' where id =".$idtopic." ";
		$db->setQuery($sql);
		$db->query();
		
		$sql ="select id, name, userid from #__kunena_messages WHERE subject='".$name."' order by id desc";
		$db->setQuery($sql);
		$db->query();
		$resultid = $db->loadAssocList();
		
		$sql ="select id from #__kunena_categories WHERE name='".$name."' order by id desc limit 0,1";
		$db->setQuery($sql);
		$db->query();
		$catkunena = $db->loadResult();
		
		$sql ="select id from #__kunena_topics WHERE subject='".$name."' order by id asc limit 0,1";
		$db->setQuery($sql);
		$db->query();
		$idmess = $db->loadResult();
		
		 for($i=0; $i < count($resultid); $i++){	
					$sql = "select message from #__kunena_messages_text WHERE mesid=".$resultid[$i]["id"];
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadResult();
					
					$sql = "select time from #__kunena_messages WHERE id=".$resultid[$i]["id"];
					$db->setQuery($sql);
					$db->query();
					$datestart = $db->loadResult();
					
					$timepast = guruModelguruTask::get_time_difference($datestart,$int_current_datek);
					
					if($timepast["days"] == 0){
						if($timepast["hours"] == 0){
							if($timepast["minutes"] == 0){
								$difference = "a few seconds ago";
							}
							else{
								$difference = $timepast["minutes"]." ".JText::_("GURU_REAL_MINUTES")." ".JText::_("GURU_AGO");
							}
						}
						else{
							$difference = $timepast["hours"]." ".JText::_("GURU_REAL_HOURS").", ".
										  $timepast["minutes"]." ".JText::_("GURU_REAL_MINUTES")." ".JText::_("GURU_AGO");
						}
					}
					else{
						$difference = $timepast["days"]." ".JText::_("GURU_REAL_DAYS").", ".
									  $difference = $timepast["hours"]." ".JText::_("GURU_REAL_HOURS").", ".
									  $timepast["minutes"]." ".JText::_("GURU_REAL_MINUTES")." ".JText::_("GURU_AGO");
					}
					if($user->id == $resultid[$i]["userid"]){
						if($allow_delete == 0){
							$concat = 1;
							$buttonsd = '<span style="display:block; float:left;"><a href="#" id="delete'.$resultid[$i]["id"].'" onclick="javascript:deletegurucomment('.$lid.', '.$resultid[$i]["userid"].', '.$resultid[$i]["id"].'); return false;">'.JText::_("GURU_DELETE").'</a></span>';
																 			
						}
						else{
							$concat = 0;
						}
						if($allow_edit == 0){
							if($concat == 0){
								$buttonsd = '<span style="float:right;display:block"><a href="#" id="edit'.$resultid[$i]["id"].'" onclick="javascript:editgurucomment1('.$resultid[$i]["id"].'); return false;">'.JText::_("GURU_EDIT").'</a></span>';
							}
							else{
								$buttonsd .= '<span style="float:right;display:block"><a href="#" id="edit'.$resultid[$i]["id"].'" onclick="javascript:editgurucomment1('.$resultid[$i]["id"].'); return false;">'.JText::_("GURU_EDIT").'</a></span>';
							}
						}
					}
					else{
						$buttonsd = " ";
					}
					
					echo '<div class="guru-header">
							<span><img  src="'.JUri::base().'components/com_guru/images/guru_comment.gif'.'"</span>
							<span>'.JText::_ ( "GURU_POSTED" ).':'.$difference.'</span>
							<span style="float:right;"><a href='.JUri::base().'index.php?option=com_kunena&view=topic&catid='.$catkunena.'&id='.$idmess.'&Itemid=0#'.$resultid[$i]["id"].'>#'.$resultid[$i]["id"].'</a></span>
							<span>'. JText::_ ( "GURU_COMMENTED_BY" ) . ' ' . $resultid[$i]["name"] .'</span>
							</div>
							<div class="guru-reply-body clearfix">
								<div style="display:block;" class="guru-text" id="gurupostcomment'.$resultid[$i]["id"].'">'.$result.'</div>
								<textarea style="display:none;" style="width:100%" name="message1'.$resultid[$i]["id"].'" id="message1'.$resultid[$i]["id"].'" rows="2" cols="90"></textarea>
                                 <input class="btn btn-success" style="display:none;" id="save'.$resultid[$i]["id"].'" name="save" type="button" onclick="javascript:savegurucomment('.$lid.','.$resultid[$i]["id"].');" value="'.JText::_('GURU_SAVE').'" />
								<div>'.$buttonsd.'</div>
						   </div>';
				}
		
	
	}
	
	function editformgurupost(){
		$db = JFactory::getDBO();
		$comid = JRequest::getVar("comid", "");
		$user = JFactory::getUser();
		$message = urldecode(JRequest::getVar("message", ""));
		
		$sql = "UPDATE #__kunena_messages_text set message= '".$message."' WHERE mesid =".$comid."";
		$db->setQuery($sql);
		$db->query();
		
		$sql = "SELECT message from #__kunena_messages_text WHERE mesid =".$comid."";
		$db->setQuery($sql);
		$db->query();
		$message = $db->loadResult();
		
		echo $message;
	}
	
	function calculatecertificate(){
		$model = $this->getModel("guruTask");
		$course_id = JRequest::getVar("course_id", "0");
		$return = $model->isLastPassedQuiz($course_id);
		
		if($return === TRUE){
			echo "ok";
			die();
		}
		else{
			echo "not_ok";
			die();
		}
	}
	function showCertificateFr(){
		$db = JFactory::getDBO();	
		$user = JFactory::getUser();
		$user_id = $user->id;
		$pid =  JRequest::getVar("course_id");
		$lesson_id = JRequest::getVar("lesson_id");
		$scores_avg_quizzes =  guruModelguruTask::getAvgScoresQ($user_id,$pid );

		
		$sql = "SELECT `completed` from #__guru_viewed_lesson WHERE `user_id` =".intval($user_id)." and pid=".intval($pid);
		$db->setQuery($sql);
		$db->query();
		$completed_course = $db->loadResult();
		
		$sql = "SELECT certificate_term  FROM #__guru_program
				WHERE id =".intval($pid);
		$db->setQuery($sql);
		$db->query();
		$course_certificate_term = $db->loadResult();
		
		$sql = "select avg_certc from #__guru_program where id=".$pid;
		$db->setQuery($sql);
		$db->query();
		$avg_certif = $db->loadResult();
		
		$sql = "SELECT media_id FROM #__guru_mediarel WHERE type='scr_m' and type_id=".intval($lesson_id);
		$db->setQuery($sql);
		$result = $db->loadResult();
	   
		$sql = "SELECT max_score FROM #__guru_quiz WHERE id=".intval($result);
		$db->setQuery($sql);
		$result_maxs = $db->loadResult();

		$sql = "SELECT id, score_quiz, time_quiz_taken_per_user  FROM #__guru_quiz_taken WHERE user_id=".intval($user_id)." and quiz_id=".intval($result)." and pid=".$pid." ORDER BY id DESC LIMIT 0,1";
		$db->setQuery($sql);
		$result_q = $db->loadObject();
	   
		$first= explode("|", @$result_q->score_quiz);
	   
		@$res = intval(($first[0]/$first[1])*100);
		if($course_certificate_term == 2 && ($completed_course==true || $completed_course ==1) ){
			$this->InsertMyCertificateDetails2($pid);
			echo "yes";
		} 
		elseif($course_certificate_term == 3 && isset($result_maxs) && $res >= intval($result_maxs) ){
			$this->InsertMyCertificateDetails2($pid);
			echo "yes";
		}
		elseif($course_certificate_term == 4 && $scores_avg_quizzes >= intval($avg_certif)){
			$this->InsertMyCertificateDetails2($pid);
			echo "yes";
		}
		elseif($course_certificate_term == 5 && ($completed_course==true || $completed_course ==1) && isset($result_maxs) && $res >= intval($result_maxs)){
			$this->InsertMyCertificateDetails2($pid);
			echo "yes";
		}
		elseif($course_certificate_term == 6 && ($completed_course==true || $completed_course ==1) && isset($scores_avg_quizzes) && ($scores_avg_quizzes >= intval($avg_certif))){
			$this->InsertMyCertificateDetails2($pid);
			echo "yes";
		}
		else{
			echo "no";	
		}
		die();
	}
	function InsertMyCertificateDetails2($pid){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$jnow = JFactory::getDate();
		$id = $user->id;
		$sql = "SELECT count(id) from #__guru_mycertificates WHERE `user_id` =".intval($id)." and course_id=".intval($pid);
		$db->setQuery($sql);
		$db->query();
		$count_cert = $db->loadResult();
		
		$current_date_cert = $jnow->toSQL();

			$author_id = "SELECT `author` from #__guru_program WHERE `id` =".intval($pid);
			$db->setQuery($author_id);
			$db->query();
			$resultauth = $db->loadResult();

			$sql = 'insert into  #__guru_mycertificates (`course_id`, `author_id`, `user_id`, `emailcert`, `datecertificate` ) values ("'.intval($pid).'", "'.intval($resultauth).'", "'.intval($id).'", 0, "'.$current_date_cert.'")';
			$db->setQuery($sql);
			$db->query();	
	
	}
	function neededinfo(){
		$db = JFactory::getDBO();
		$quiz_id =  JRequest::getVar("quiz_id");
		$question_id =  JRequest::getVar("question_id");
		
		$q  = "SELECT * FROM #__guru_quiz WHERE id = ".$quiz_id." and published=1 ";
		$db->setQuery($q);
		$result_quiz = $db->loadObject();
		
		$sql = "SELECT max_score, pbl_max_score, limit_time, show_limit_time, time_quiz_taken, show_nb_quiz_taken, nb_quiz_select_up, show_nb_quiz_select_up  FROM #__guru_quiz where id=".$result_quiz->id;
		$db->setQuery($sql);
		$result_settings_quiz = $db->loadObject();
		
		$order_by = "";
		
		if(isset($result_settings_quiz->nb_quiz_select_up) && $result_settings_quiz->nb_quiz_select_up !=0 && $result_settings_quiz->show_nb_quiz_select_up ==0){
			$order_by = "";
		}
		else{
			$order_by = " ORDER BY reorder";
		}
		
		if($result_quiz->is_final == 1){
			$sql = "SELECT 	quizzes_ids FROM #__guru_quizzes_final WHERE qid=".intval($quiz_id);
			$db->setQuery($sql);
			$db->query();
			$result=$db->loadColumn();	
			$result_qids = explode(",",trim($result[0],","));
			
			if($result_qids["0"] == ""){
				$result_qids["0"] = 0;
			}
			
			if(isset($result_qids) && count($result_qids) > 0){
				foreach($result_qids as $key=>$value){
					$quiz_id = intval($value);
					$sql = "select `published` from #__guru_quiz where `id`=".intval($quiz_id);
					$db->setQuery($sql);
					$db->query();
					$published = $db->loadColumn();
					$published = @$published["0"];
					if(intval($published) == 0){
						unset($result_qids[$key]);
					}
				}
			}
			
			if(count($result_qids) == 0 || $result_qids["0"] == ""){
				$result_qids["0"] = 0;
			}
			
			$query  = "SELECT * FROM #__guru_questions WHERE qid IN (".implode(",", $result_qids).") and published=1".$order_by;
			$db->setQuery($query);
			$quiz_questions = $db->loadObjectList();
			
			foreach($quiz_questions as $one_question ){
				if($one_question->id == intval($question_id)){
					echo trim($one_question->answers);
					die();	
				}
			}
		}
		else{
			$query  = "SELECT * FROM #__guru_questions WHERE qid = ".intval($quiz_id)." and `id`=".intval($question_id);
			$db->setQuery($query);
			$question_details = $db->loadAssocList();
			echo trim($question_details["0"]["answers"]);
			die();
		}	
		
		
	}
	
	function get_quiz_result(){
		$view = $this->getView("guruTasks", "html");
		$view->setLayout("view");
		$view->setModel($this->_model, "getTask");		
		$view->quiz_fe_result_calculation();	
	}
	
		
};

?>