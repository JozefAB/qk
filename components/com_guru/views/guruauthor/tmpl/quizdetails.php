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
JHTML::_('behavior.tooltip');
$div_menu = $this->authorGuruMenuBar();

$doc = JFactory::getDocument();
$doc->addStyleSheet("components/com_guru/css/quiz.css");

$user_id = JRequest::getVar("userid", "0");
$user_name = $this->userName($user_id);
$user_email = $this->userEmail($user_id);
$quiz_id = JRequest::getVar("quiz", "0");
$pid = JRequest::getVar("pid", "0");
$db = JFactory::getDBO();
$sql = "select `id` from #__guru_quiz_taken where `user_id`=".intval($user_id)." and `pid`=".intval($pid)." and `quiz_id`=".intval($quiz_id)." order by `id` desc limit 0,1";
$db->setQuery($sql);
$db->query();
$id_row = $db->loadColumn();
$id_row = $id_row["0"];
?>

<style type="text/css">
	div.g_row{
		margin:0px !important;
	}
</style>

<div class="g_row">
	<div class="g_cell span12">
		<div>
			<div>
				<div id="g_myquizzesstats" class="clearfix com-cont-wrap">
					<?php echo $div_menu; ?>
                    <div id="g_mycoursesauthorcontent" class="g_sect clearfix">
                    	<?php
							$k = 0;
							$n = count($this->ads);
							$quiz_name = $this->getQuizName($quiz_id);
							$score = $this->getScoreQuiz($quiz_id, $user_id, $pid);
							$score = explode("|", $score);
						
							$how_many_right_answers = @$score["0"];
							$number_of_questions = @$score["1"];
							$score = 0;
							if(intval($number_of_questions) > 0){
								$score = intval(($how_many_right_answers/$number_of_questions)*100);
							}
							$ans_gived =  $this->getAnsGived($user_id, $quiz_id, $pid);
							$ans_right =  $this->getAnsRight($user_id, $quiz_id, $pid);
							$the_question = $this->getQuestionName($user_id, $pid, $quiz_id);
							$all_answers_array = $this->getAllAns($quiz_id, $user_id, $pid);
							$all_answers_text_array = $this->getAllAnsText($quiz_id, $user_id, $pid);
							$quiz_result_content = "";
							
							foreach($the_question as $i=>$question){
								if(!isset($all_answers_array[$i])){
									continue;
								}
								
								$answer_count = 0;
								$all_answers_array_result = explode("|||", @$all_answers_array[$i]); 
								$all_answers_text_array_result = explode("|||", @$all_answers_text_array[$i]); 
								$ans_right_result = explode("|||", @$ans_right[$i]->answers); 
								$ans_gived_result = explode(" ||", @$ans_gived[$i]->answers_gived);
								
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
									$quiz_result_content .= '<li class="question wrong g_quize_q">'. @$the_question[$i]->text.'</li>';                    
								}
								
								for($j=0; $j<count($all_answers_array_result); $j++){
									if($all_answers_array_result[$j] != "") {
										$all_keys_given_answers = array();
										foreach($ans_gived_result as $key_given=>$val_given){
											if(trim($val_given) != ""){
												$all_keys_given_answers[] = array_search($val_given, $all_answers_array_result);
											}
										}
										
										//--------------------------------------------
										$inArray = in_array($all_answers_array_result[$j], $ans_right_result);
										//-------------------------------------------- 
										
										$icon = "";
										if(in_array($j, $all_keys_given_answers)){
											$icon = '<i class="fa fa-hand-o-right"></i>&nbsp;&nbsp;';
										}
										
										if($inArray){
											$quiz_result_content .= '<li class="correct">'.$icon.$all_answers_text_array_result[$j].'</li>'; 
										}
										else{
											$quiz_result_content .= '<li class="incorrect">'.$icon.$all_answers_text_array_result[$j].'</li>'; 
										}
									}
								}
								$quiz_result_content .= '</ul>';
							}    
						?>
                        <h4>
							<?php
                                $grav_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($user_email)))."?d=mm&s=40";
                                echo '<img src="'.$grav_url.'" alt="'.$user_name.'" title="'.$user_name.'"/>&nbsp;';
                                echo $user_name;
                            ?>
                        </h4>
						<div id="editcell" class="guru_quiz_title">
							<div>
								<span class="guru_quiz_title"><?php echo $quiz_name ; ?></span>
								<span class="guru_quiz_title"><?php echo JText::_("GURU_QUIZ_RESULT"); ?>:</span>
								<span class="guru_quiz_score"><?php echo $user_name."'s"." ".JText::_("GURU_QUIZ_SCORE"); ?>: <?php echo $score. "%";?></span>
								<br/>   
								<div id="the_quiz">	
									<?php echo $quiz_result_content;?>
								</div>
							</div>
						</div>
                        
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>