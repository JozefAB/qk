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


class guruControllerguruOrders extends guruController {
	var $_model = null;
	
	function __construct () {
		parent::__construct();
		$this->registerTask("mycourses", "myCourses");
		$this->registerTask ("listQuizStud", "listQuizStud");
		$this->registerTask ("show_quizz_res", "show_quizz_res");
		$this->registerTask("view", "viewOrderDetails1");
		$this->registerTask("showrec", "viewOrderDetails2");
		$this->registerTask("renew", "renewtest");
		$this->registerTask("printcertificate", "printcertificate");
		$this->registerTask("sendemailcertificate", "sendemailcertificate");
		$this->_model = $this->getModel("guruOrder");	
	}
	
	function listQuizStud(){
		$view = $this->getView("guruOrders", "html");
		$view->setLayout("listquizstud");
		$view->setModel($this->_model, true);
		$view->listQuizStud();
	}
	function show_quizz_res(){
		$view = $this->getView("guruOrders", "html");
		$view->setLayout("show_quizz_res");
		$view->setModel($this->_model, true);
		$view->show_quizz_res();
	}

	function myorders(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$user = JFactory::getUser();
		$user_id = $user->id;
		// Check Login		
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=myorders"."&Itemid=".$Itemid, false));
			return true;
		}
		
		$model = $this->getModel("guruOrder");
        $res = $model->checkCustomerProfile($user_id);

        if($res === FALSE){
            $this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruProfile&task=edit&returnpage=myorders&Itemid=".$Itemid, false));
        }
		
		$view = $this->getView("guruOrders", "html");
		$view->setLayout("myorders");
		$view->setModel($this->_model, true);
		$view->display();
	}
	
	function mycertificates(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$user = JFactory::getUser();
		$user_id = $user->id;
		// Check Login		
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=mycertificates"."&Itemid=".$Itemid, false));
			return true;
		}
		
		$model = $this->getModel("guruOrder");
        $res = $model->checkCustomerProfile($user_id);

        if($res === FALSE){
            $this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruProfile&task=edit&returnpage=mycertificate&Itemid=".$Itemid, false));
        }
		
		$view = $this->getView("guruOrders", "html");
		$view->setLayout("mycertificates");
		$view->setModel($this->_model, true);
		//$view->display();
		$view->mycertificates();
	}
	
	function myquizandfexam(){
		$Itemid = JRequest::getVar("Itemid", "0");
		$user = JFactory::getUser();
		$user_id = $user->id;
		// Check Login		
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=myquizandfexam"."&Itemid=".$Itemid, false));
			return true;
		}
		
		$model = $this->getModel("guruOrder");
        $res = $model->checkCustomerProfile($user_id);

        if($res === FALSE){
            $this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruProfile&task=edit&returnpage=myquizandfexam&Itemid=".$Itemid, false));
        }
		
		$view = $this->getView("guruOrders", "html");
		$view->setLayout("myquizandfexam");
		$view->setModel($this->_model, true);
		//$view->display();
		$view->myQuizandfexam();
	
	}
	
	function myCourses(){
		$Itemid = JRequest::getVar("Itemid", "0");

		$user = JFactory::getUser();
		$user_id = $user->id;
		// Check Login		
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=mycourses"."&Itemid=".$Itemid, false));
			return true;
		}
		
		$view = $this->getView("guruOrders", "html");
		$view->setLayout("mycourses");
		$view->setModel($this->_model, true);
		$model = $this->getModel("guruOrder");
		$view->setModel($model);
		$view->myCourses();
	}
	function printcertificate(){
		$view = $this->getView("guruOrders", "html");
		$view->setLayout("printcertificate");
		$view->printcertificate();
	}
	
	function viewOrderDetails1(){
		$view = $this->getView("guruOrders", "html");
		$view->setLayout("orderdetails");
		$view->setModel($this->_model, true);
		$model = $this->getModel("guruOrder");
		$view->setModel($model);
		$view->orderDetails1();
	}
	
	function viewOrderDetails2(){
		$user = JFactory::getUser();
		$user_id = $user->id;
		$Itemid = JRequest::getVar("Itemid", "0");
		// Check Login		
		if($user_id == "0"){		
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruLogin&returnpage=myorders"."&Itemid=".$Itemid, false));
			return true;
		}
		else{
			$view = $this->getView("guruOrders", "html");
			$view->setLayout("orderdetails");
			$view->setModel($this->_model, true);
			$model = $this->getModel("guruOrder");
			$view->setModel($model);
			$view->orderDetails2();
		}
	}

	function order(){
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("guruOrders", "html");
		$view->setLayout("order");
		$view->setModel($this->_model, true);
		$view->order();
	}
	
	function checkout(){
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__guru_plugins";
		$db->setQuery($sql);
		$payment_plugins = $db->loadResult();
		$my = JFactory::getUser();		
		$filename = JPATH_BASE.'/components/com_guru/models/guruplugin.php';			
		require_once ($filename);
		$res = guruModelguruPlugin::performCheckout($my->id);
		if($res < 0){
			$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruPrograms" ));
		}	
	}	
	
	function renew(){
		$order_id = JRequest::getVar("order_id", "");
		$course_id = JRequest::getVar("course_id", "");
		$item_id = JRequest::getVar("Itemid", "0");
		
		$db = JFactory::getDBO();
		$sql = "SELECT `price` FROM `#__guru_program_renewals` WHERE `product_id` = ".intval($course_id)." and `default` = 1";		
		$db->setQuery($sql);
		$db->query();
		$price = $db->loadResult();
		if(!isset($price) && $price == NULL){
			$price = "0";
		}
		
		$db = JFactory::getDBO();
		$sql = "SELECT `name` FROM `#__guru_program` WHERE `id` = ".intval($course_id);		
		$db->setQuery($sql);
		$db->query();
		$name = $db->loadResult();
		
		if(isset($_SESSION["renew_courses_from_cart"])){
			$temp_array = array("course_id"=>$course_id, "value"=>$price, "name"=>$name, "plan"=>"renew");
			$new_value = $_SESSION["renew_courses_from_cart"];
			$new_value[$course_id] = $temp_array;
			$_SESSION["renew_courses_from_cart"] = $new_value;
		}
		else{
			$temp_array = array("course_id"=>$course_id, "value"=>$price, "name"=>$name, "plan"=>"renew");
			$new_value = array();
			$new_value[$course_id] = $temp_array;
			$_SESSION["renew_courses_from_cart"] = $new_value;
		}
		
		$this->setRedirect(JRoute::_("index.php?option=com_guru&view=guruBuy&action=renew&Itemid=".$item_id, false));//put order id if renew to same order
	}
	
	function savepdfcertificate(){
		$datac = JRequest::get('post',JREQUEST_ALLOWRAW);
		include(JPATH_SITE.DS.'components'.DS.'com_guru'.DS.'models'.DS.'gurutask.php');
		$background_color = "";

		$op = JRequest::getVar("op", "");

		if($op == 9){
			$db = JFactory::getDBO();
			$user = JFactory::getUser();
			$config = JFactory::getConfig();
			$imagename = "SELECT * FROM #__guru_certificates WHERE id=1";
			$db->setQuery($imagename);
			$db->query();
			$imagename = $db->loadAssocList();
			
			
			if($imagename[0]["design_background"] !=""){
				$image_theme = explode("/", $imagename[0]["design_background"]);
				if(trim($image_theme[4]) =='thumbs'){
					$image_theme = $image_theme[5];
				}
				else{
					$image_theme = $image_theme[4];
				}
			}	
			else{
				$background_color= "background-color:"."#".$imagename[0]["design_background_color"];
			}	
			
			$site_url = JURI::root();
			$coursename = JRequest::getVar('cn', '', 'get');
			$authorname = JRequest::getVar('an', '', 'get');
			$certificateid = JRequest::getVar('id', '', 'get');
			$completiondate = JRequest::getVar('cd', '', 'get');
			$course_id = JRequest::getVar('ci', '', 'get');			
			$sitename = $config->get('sitename');
			$user_id = $user->id;	
			
			$scores_avg_quizzes =  guruModelguruTask::getAvgScoresQ($user_id,$course_id);

			$avg_quizzes_cert = "SELECT avg_certc FROM #__guru_program WHERE id=".intval($course_id );
			$db->setQuery($avg_quizzes_cert);
			$db->query();
			$avg_quizzes_cert = $db->loadResult();


			$sql = "SELECT id_final_exam FROM #__guru_program WHERE id=".intval($course_id );
			$db->setQuery($sql);
			$result = $db->loadResult();
	
			$sql = "SELECT hasquiz from #__guru_program WHERE id=".intval($course_id );
			$db->setQuery($sql);
			$resulthasq = $db->loadResult();
	
			$sql = "SELECT max_score FROM #__guru_quiz WHERE id=".intval($result);
			$db->setQuery($sql);
			$result_maxs = $db->loadResult();
	
			$sql = "SELECT id, score_quiz, time_quiz_taken_per_user  FROM #__guru_quiz_taken WHERE user_id=".intval($user_id)." and quiz_id=".intval($result)." and pid=".intval($course_id )." ORDER BY id DESC LIMIT 0,1";
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
			
			$coursemsg = "SELECT certificate_course_msg FROM #__guru_program WHERE id=".intval($course_id);
			$db->setQuery($coursemsg);
			$db->query();
			$coursemsg = $db->loadResult();
			$certificate_url = JUri::base()."index.php?option=com_guru&view=guruOrders&task=printcertificate&opt=".$certificateid."&cn=".$coursename."&an=".$authorname."&cd=".$completiondate."&id=".$certificateid;
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

			
			
			while (ob_get_level())
			ob_end_clean();
			header("Content-Encoding: None", true);
			
			if(strlen($imagename[0]["design_text_color"]) == 3) {
			  $r = hexdec(substr($imagename[0]["design_text_color"],0,1).substr($imagename[0]["design_text_color"],0,1));
			  $g = hexdec(substr($imagename[0]["design_text_color"],1,1).substr($imagename[0]["design_text_color"],1,1));
			  $b = hexdec(substr($imagename[0]["design_text_color"],2,1).substr($imagename[0]["design_text_color"],2,1));
		   } else {
			  $r = hexdec(substr($imagename[0]["design_text_color"],0,2));
			  $g = hexdec(substr($imagename[0]["design_text_color"],2,2));
			  $b = hexdec(substr($imagename[0]["design_text_color"],4,2));
			}
			$background_color = explode(":",$background_color );
			@$background_color[1]=str_replace("#", "", $background_color[1]);
			
			if(strlen($background_color[1] ) == 3) {
			  $rg = hexdec(substr($background_color[1],0,1).substr($background_color[1],0,1));
			  $gg = hexdec(substr($background_color[1],1,1).substr($background_color,1,1));
			  $bg = hexdec(substr($background_color[1],2,1).substr($background_color[1],2,1));
		   } else {
			  $rg = hexdec(substr($background_color[1],0,2));
			  $gg = hexdec(substr($background_color[1],2,2));
			  $bg = hexdec(substr($background_color[1],4,2));
		   }
			
			if($imagename[0]["library_pdf"] == 0){
				require (JPATH_SITE.DS."components".DS."com_guru".DS."helpers".DS."fpdf.php");
				
				$pdf = new PDF('L', 'mm', 'A5');
		
				$pdf->SetFont($imagename[0]["font_certificate"],'',12);
				$pdf->SetTextColor($r,$g,$b);
				
				//set up a page
				$pdf->AddPage();
		
				if($image_theme !=""){
					$pdf->Image(JUri::base()."images/stories/guru/certificates/".$image_theme,-4,-1,210, 150);
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
				$imagename[0]["templates1"] = '<style> body { font-family:"'.strtolower ($imagename[0]["font_certificate"]).'" ; color: rgb('.$r.', '.$g.', '.$b.'); }</style>'.$imagename[0]["templates1"];
				
				
				//set up a page
				$pdf->AddPage('L');
		
				if($image_theme !=""){
					$pdf->Image(JPATH_BASE."/images/stories/guru/certificates/".$image_theme,0,0,298, 210, 'jpg', '', true, false);
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
		else{
			$db = JFactory::getDBO();
			$user = JFactory::getUser();
			$config = JFactory::getConfig();
			$imagename = "SELECT * FROM #__guru_certificates WHERE id=1";
			$db->setQuery($imagename);
			$db->query();
			$imagename = $db->loadAssocList();
			
			
			if($imagename[0]["design_background"] !=""){
				$image_theme = explode("/", $imagename[0]["design_background"]);
				if(trim($image_theme[4]) =='thumbs'){
					$image_theme = $image_theme[5];
				}
				else{
					$image_theme = $image_theme[4];
				}
			}	
			else{
				$background_color= "background-color:"."#".$imagename[0]["design_background_color"];
			}	
			
			$site_url = JURI::root();
			$coursename = $datac['cn'];
			$authorname = $datac['an'];
			$certificateid = $datac['id'];
			$completiondate = $datac['cd'];
			$course_id = $datac['ci'];;

			$sitename = $config->get('config.sitename');


			$user_id = $user->id;
			
			$scores_avg_quizzes =  guruModelguruTask::getAvgScoresQ($user_id,$course_id);

			$avg_quizzes_cert = "SELECT avg_certc FROM #__guru_program WHERE id=".intval($course_id );
			$db->setQuery($avg_quizzes_cert);
			$db->query();
			$avg_quizzes_cert = $db->loadResult();


			$sql = "SELECT id_final_exam FROM #__guru_program WHERE id=".intval($course_id );
			$db->setQuery($sql);
			$result = $db->loadResult();
	
			$sql = "SELECT hasquiz from #__guru_program WHERE id=".intval($course_id );
			$db->setQuery($sql);
			$resulthasq = $db->loadResult();
	
			$sql = "SELECT max_score FROM #__guru_quiz WHERE id=".intval($result);
			$db->setQuery($sql);
			$result_maxs = $db->loadResult();
	
			$sql = "SELECT id, score_quiz, time_quiz_taken_per_user  FROM #__guru_quiz_taken WHERE user_id=".intval($user_id)." and quiz_id=".intval($result)." and pid=".intval($course_id )." ORDER BY id DESC LIMIT 0,1";
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
			
			$coursemsg = "SELECT certificate_course_msg FROM #__guru_program WHERE id=".intval($course_id);
			$db->setQuery($coursemsg);
			$db->query();
			$coursemsg = $db->loadResult();
						

			$certificate_url = JUri::base()."index.php?option=com_guru&view=guruOrders&task=printcertificate&opt=".$certificateid."&cn=".$coursename."&an=".$authorname."&cd=".$completiondate."&id=".$certificateid;
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
			
			while (ob_get_level())
			ob_end_clean();
			header("Content-Encoding: None", true);
			
			if(strlen($imagename[0]["design_text_color"]) == 3) {
			  $r = hexdec(substr($imagename[0]["design_text_color"],0,1).substr($imagename[0]["design_text_color"],0,1));
			  $g = hexdec(substr($imagename[0]["design_text_color"],1,1).substr($imagename[0]["design_text_color"],1,1));
			  $b = hexdec(substr($imagename[0]["design_text_color"],2,1).substr($imagename[0]["design_text_color"],2,1));
		   } else {
			  $r = hexdec(substr($imagename[0]["design_text_color"],0,2));
			  $g = hexdec(substr($imagename[0]["design_text_color"],2,2));
			  $b = hexdec(substr($imagename[0]["design_text_color"],4,2));
			}
			$background_color = explode(":",$background_color );
			$background_color[1]=str_replace("#", "", $background_color[1]);
			
			if(strlen($background_color[1] ) == 3) {
			  $rg = hexdec(substr($background_color[1],0,1).substr($background_color[1],0,1));
			  $gg = hexdec(substr($background_color[1],1,1).substr($background_color,1,1));
			  $bg = hexdec(substr($background_color[1],2,1).substr($background_color[1],2,1));
		   } else {
			  $rg = hexdec(substr($background_color[1],0,2));
			  $gg = hexdec(substr($background_color[1],2,2));
			  $bg = hexdec(substr($background_color[1],4,2));
		   }
			
			if($imagename[0]["library_pdf"] == 0){
				require (JPATH_SITE.DS."components".DS."com_guru".DS."helpers".DS."fpdf.php");
				
				$pdf = new PDF('L', 'mm', 'A5');
		
				$pdf->SetFont($imagename[0]["font_certificate"],'',12);
				$pdf->SetTextColor($r,$g,$b);
				
				//set up a page
				$pdf->AddPage();
		
				if($image_theme !=""){
					$pdf->Image(JUri::base()."images/stories/guru/certificates/".$image_theme,-4,-1,210, 150);
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
				$imagename[0]["templates1"] = '<style> body { font-family:"'.strtolower ($imagename[0]["font_certificate"]).'" ; color: rgb('.$r.', '.$g.', '.$b.'); }</style>'.$imagename[0]["templates1"];
				
				
				//set up a page
				$pdf->AddPage('L');
		
				if($image_theme !=""){
					$pdf->Image(JPATH_BASE."/images/stories/guru/certificates/".$image_theme,0,0,298, 210, 'jpg', '', true, false);
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
	}
	
function sendemailcertificate(){
	$datace = JRequest::get('post',JREQUEST_ALLOWRAW);
	$user = JFactory::getUser();
	include(JPATH_SITE.DS.'components'.DS.'com_guru'.DS.'models'.DS.'gurubuy.php');

	
	$config = JFactory::getConfig();
	$from = $config->mailfrom;
	$fromname = $config->fromname;
	$guru_configs = guruModelguruBuy::getConfigs();
	$db = JFactory::getDBO();
	
	$imagename = "SELECT subjectt4 FROM #__guru_certificates WHERE id=1";
	$db->setQuery($imagename);
	$db->query();
	$imagename = $db->loadResult();

	
	if(isset($guru_configs["0"]["fromname"]) && trim($guru_configs["0"]["fromname"]) != ""){
		$fromname = trim($guru_configs["0"]["fromname"]);
	}
	if(isset($guru_configs["0"]["fromemail"]) && trim($guru_configs["0"]["fromemail"]) != ""){
		$from = trim($guru_configs["0"]["fromemail"]);
	}
	$imagename = "SELECT * FROM #__guru_certificates WHERE id=1";
	$db->setQuery($imagename);
	$db->query();
	$imagename = $db->loadAssocList();
	
	$site_url = JURI::root();
	$coursename = $datace['cn'];
	$authorname = $datace['an'];
	$certificateid = $datace['id'];
	$completiondate = $datace['cd'];
	$course_id =  $datace['ci'];

	$sitename = $config->get('sitename');
	
	$user_id = $user->id;			
	$firstnamelastname = "SELECT firstname, lastname FROM #__guru_customer WHERE id=".intval($user_id);
	$db->setQuery($firstnamelastname);
	$db->query();
	$firstnamelastname = $db->loadAssocList();
	
	$coursemsg = "SELECT certificate_course_msg FROM #__guru_program WHERE id=".intval($course_id);
	$db->setQuery($coursemsg);
	$db->query();
	$coursemsg = $db->loadResult();
	
	$avg_certc = "SELECT avg_certc FROM #__guru_program WHERE id=".intval($course_id);
	$db->setQuery($avg_certc);
	$db->query();
	$avg_certc = $db->loadResult()."%";
	
	$certificate_url = JUri::base()."index.php?option=com_guru&view=guruOrders&task=printcertificate&opt=".$certificateid."&cn=".$coursename."&an=".$authorname."&cd=".$completiondate."&id=".$certificateid;
	$certificate_url = str_replace(" ", "%20", $certificate_url);

	
	$imagename[0]["templates4"]  = str_replace("[SITENAME]", $sitename, $imagename[0]["templates4"]);
	$imagename[0]["templates4"]  = str_replace("[STUDENT_FIRST_NAME]", $firstnamelastname[0]["firstname"], $imagename[0]["templates4"]);
	$imagename[0]["templates4"]  = str_replace("[STUDENT_LAST_NAME]", $firstnamelastname[0]["lastname"], $imagename[0]["templates4"]);
	$imagename[0]["templates4"]  = str_replace("[CERT_URL]", $certificate_url, $imagename[0]["templates4"]);
	$imagename[0]["templates4"]  = str_replace("[CERT_MESSAGE]", $datace["personalmessage"], $imagename[0]["templates4"]);
	$imagename[0]["templates4"]  = str_replace("[SITEURL]", $site_url, $imagename[0]["templates4"]);
	$imagename[0]["templates4"]  = str_replace("[CERTIFICATE_ID]", $certificateid, $imagename[0]["templates4"]);
	$imagename[0]["templates4"]  = str_replace("[COMPLETION_DATE]", $completiondate, $imagename[0]["templates4"]);
	$imagename[0]["templates4"]  = str_replace("[COURSE_NAME]", $coursename, $imagename[0]["templates4"]);
	$imagename[0]["templates4"]  = str_replace("[AUTHOR_NAME]", $authorname, $imagename[0]["templates4"]);
	$imagename[0]["templates4"]  = str_replace("[COURSE_MSG]", $coursemsg, $imagename[0]["templates4"]);
	$imagename[0]["templates4"]  = str_replace("[COURSE_AVG_SCORE]", $avg_certc, $imagename[0]["templates4"]);
    $imagename[0]["templates4"]  = str_replace("[COURSE_FINAL_SCORE]", $avg_certc, $imagename[0]["templates4"]);



	
	$email_body	= $imagename[0]["templates4"];
	
	$recipient = $datace["emails"];
	$recipient = explode(",", $recipient); 
	$mode = true;
	
	$imagename = "SELECT subjectt4 FROM #__guru_certificates WHERE id=1";
	$db->setQuery($imagename);
	$db->query();
	$imagename = $db->loadResult();

	$imagename  = str_replace("[SITENAME]", $sitename, $imagename);
	$imagename   = str_replace("[STUDENT_FIRST_NAME]", $datace['studentfn'], $imagename);
	$imagename  = str_replace("[STUDENT_LAST_NAME]", $datace['studentln'], $imagename);
	$imagename  = str_replace("[SITEURL]", $site_url, $imagename);
	$imagename  = str_replace("[CERTIFICATE_ID]", $certificateid, $imagename);
	$imagename  = str_replace("[COMPLETION_DATE]", $completiondate, $imagename);
	$imagename  = str_replace("[COURSE_NAME]", $coursename, $imagename);
	$imagename  = str_replace("[AUTHOR_NAME]", $authorname, $imagename);
	$imagename = str_replace("[CERT_MESSAGE]", str_replace("'", "&acute;",$datace["personalmessage"]), $imagename);

	
					
	$subject_procesed = $imagename;
	$body_procesed = $email_body;


	if(is_array($recipient) && count($recipient) > 0){
		foreach($recipient as $key => $recipient){
			JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject_procesed, $body_procesed, $mode);
		}
	}
	
	echo '
	<script language="javascript" type="text/javascript">
		window.close();
	</script>';
}


};

?>