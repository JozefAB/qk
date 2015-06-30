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
$doc =JFactory::getDocument();
$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');    
$doc->addScript('components/com_guru/js/jquery.noconflict.js');

$k = 0;
$n = count($this->ads);    
$quiz_id =  intval(JRequest::getVar("quiz_id", ""));
$user_id =  intval(JRequest::getVar("cid", ""));
$id =  intval(JRequest::getVar("id", ""));
$quiz_name = guruAdminModelguruQuiz::getQuizName($quiz_id);
$score = guruAdminModelguruQuiz::getScoreQuiz($quiz_id, $user_id,$id);
$score = explode("|", $score);

$how_many_right_answers = $score[0];
$number_of_questions = $score[1];
$score = intval(($how_many_right_answers/$number_of_questions)*100);
//$score = $how_many_right_answers.'/'.$number_of_questions;
$ans_gived =  guruAdminModelguruQuiz::getAnsGived($user_id,$id);
$ans_right =  guruAdminModelguruQuiz::getAnsRight($quiz_id);
$the_question =  guruAdminModelguruQuiz::getQuestionName($id,$quiz_id);
$all_answers_array = guruAdminModelguruQuiz::getAllAns($quiz_id,$id);
$all_answers_text_array = guruAdminModelguruQuiz::getAllAnsText($quiz_id,$id);

foreach($the_question as $i=>$question){
	if(!isset($all_answers_array[$i])){
		continue;
	}
	
	$answer_count = 0;
	$all_answers_array_result = explode("|||",$all_answers_array[$i]); 
	$all_answers_text_array_result = explode("|||",$all_answers_text_array[$i]); 
	$ans_right_result = explode("|||", $ans_right[$i]->answers); 
	$ans_gived_result = explode(" ||", $ans_gived[$i]->answers_gived);
	for($t=0; $t<count($ans_gived_result); $t++){
		if($ans_gived_result[$t] != ""){
			if(!in_array($ans_gived_result[$t], $ans_right_result)){
				$gasit = false;
				break;
			}
			else{
				$gasit = true;
				$answer_count++;
			}
		}
	}
	
                                           	
											
				@$quiz_result_content .= '<ul class="guru_list">';
					$empty_elements = array("");
					$ans_gived_result = array_diff($ans_gived_result,$empty_elements);
					if(count($ans_gived_result) == $answer_count){
						$quiz_result_content .= '<li class="question right">'. $the_question[$i]->text.'</li>';                                
					}
					else{    
						$quiz_result_content .= '<li class="question wrong g_quize_q">'. $the_question[$i]->text.'</li>';                    
					}
	
					for($j=0; $j<count($all_answers_array_result); $j++){
						if($all_answers_array_result[$j] != "") {
							//--------------------------------------------
							$inArray = in_array($all_answers_array_result[$j], $ans_right_result);
							//-------------------------------------------- 
							if($inArray){
								$quiz_result_content .= '<li class="correct">'.$all_answers_text_array_result[$j].'</li>'; 
							}
							else{
								$quiz_result_content .= '<li class="incorrect">'.$all_answers_text_array_result[$j].'</li>'; 
							}
						}
					}    
	
				$quiz_result_content .= '</ul>';  
			
}    
?>

	<script language="javascript" type="text/javascript">
    </script>
    <div id="editcell">
        <div>
            <span style="font-size:16px; padding-left:10px"><?php echo $quiz_name ; ?></span>
            <span class="guru_quiz_title"><?php echo JText::_("GURU_QUIZ_RESULT"); ?>:</span>
            <span class="guru_quiz_score"><?php echo JText::_("GURU_YOUR_SCORE"); ?>: <?php echo $score. "%";?></span>
            <br/>   
            
            
          
            <div id="the_quiz">	
                <?php echo $quiz_result_content;?>
            </div>
        </div>
    </div>