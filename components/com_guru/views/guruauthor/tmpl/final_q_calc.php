<script language="javascript" type="text/javascript">
	function isFloat(nr){
		return parseFloat(nr.match(/^-?\d*(\.\d+)?$/)) > 0;
	}
	
	function savequiz (pressbutton){
		var form = document.adminForm;
		if (pressbutton == 'save_quizFE' || pressbutton == 'apply_quizFE') {
			if (form['name'].value == "") {
				alert( "<?php echo JText::_("GURU_TASKS_JS_NAME");?>" );
				return false;
			} 
			else{
				max_score_pass = document.getElementById("max_score_pass").value;
				limit_time_l = document.getElementById("limit_time_l").value;
				limit_time_f = document.getElementById("limit_time_f").value;
				
				if(!isFloat(max_score_pass) || max_score_pass <= 0){
					alert("<?php echo JText::_("GURU_INVALID_NUMBER_AVG"); ?>");
					return false;
				}
				else if(!isFloat(limit_time_l) || limit_time_l <= 0){
					alert("<?php echo JText::_("GURU_INVALID_NUMBER_AVG"); ?>");
					return false;
				}
				else if(!isFloat(limit_time_f) || limit_time_f <= 0){
					alert("<?php echo JText::_("GURU_INVALID_NUMBER_AVG"); ?>");
					return false;
				}
				
				if((parseInt(limit_time_l) < parseInt(limit_time_f)) || (parseInt(limit_time_l) == parseInt(limit_time_f))){
					alert("<?php echo JText::_("GURU_LIMIT2_GRATER_LIMIT1"); ?>");
					return false;
				}
				
				all_quizes_nr = document.getElementById("all_quizes_nr").value;
				if(all_quizes_nr == 0){
					if(confirm('<?php echo addslashes(JText::_("GURU_ADD_QUIZ_TO_FINAL")); ?>')){
						window.parent.location.href = 'index.php?option=com_guru&controller=guruAuthor&task=editQuizFE&v=0';
					}
					return false;
				}
				
				submitform( pressbutton );
			}
		}
		else{
			submitform(pressbutton);
		}
	}
	
	function makeVisible(tab){
		if(tab == 'tab1'){
			document.getElementById("li_general").className="active";
			document.getElementById("li_quizzes").className="";
			document.getElementById("general1").className="tab-pane active";
			document.getElementById("general1").style.display="block";
			
			document.getElementById("quizzesincl").className="";
			document.getElementById("quizzesincl").style.display="none";
		}
		else if(tab == 'tab2'){
			document.getElementById("li_quizzes").className="active";
			document.getElementById("li_general").className="";
			document.getElementById("general1").className="";
			document.getElementById("quizzesincl").className="tab-pane active";
			document.getElementById("general1").style.display="none";
			document.getElementById("quizzesincl").style.display="block";

		}
	}
</script>

<style type="text/css">
	.form-horizontal .controls{
		margin-left: 205px;
	}
	iframe {
		height: 100%!important;
	}
</style>

<div class="g_row">
	<div class="g_cell span12">
    	<div>
			<div>
            	<div id="g_myquizzesfeaddedit" class="clearfix com-cont-wrap">
					<?php 	echo $div_menu; //MENU TOP OF AUTHORS?>
                        <div class="g_inline_child g_margin_bottom">
                                <input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE"); ?>" onclick="javascript:savequiz('apply_quizFE');" />
                                <input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE_AND_CLOSE"); ?>" onclick="javascript:savequiz('save_quizFE');" />
                                <input type="button" class="btn" value="<?php echo JText::_("GURU_CLOSE"); ?>" onclick="document.location.href='<?php echo JRoute::_("index.php?option=com_guru&view=guruauthor&task=authorquizzes&layout=authorquizzes"); ?>';" />
                        </div>
                	 <div id="g_teacher_fe">   
                        <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-horizontal">
                            <div class="final_exam_teacher_page page_title">
                                <h2> 
                                    <?php
                                        if($program->id < 1){
                                            echo JText::_('GURU_NEW_FE_CREATION');
                                        }
                                        else{
                                            echo JText::_('GURU_EDIT_EXISTING_FE');
                                        }
                                    ?>
                               </h2>
                            </div>
                            
                            <?php
                            	$general_active = "active";
								$quizz_active = "";
								$style_tab2 = "style='display:none'";
								$style_tab1 = "style='display:block'";
								if(isset($_SESSION["added_quiz"]) && $_SESSION["added_quiz"] == 1){
									$_SESSION["added_quiz"] = 0;
									$general_active = "";
									$quizz_active = "active";
									$style_tab1 = "style='display:none'";
									$style_tab2 = "style='display:block'";
								}
							?>
                            
                           <ul class="nav nav-tabs">
                                <li class="<?php echo $general_active; ?>" id="li_general" onclick="makeVisible('tab1'); return false;"><a href="#general1" data-toggle="tab"><?php echo JText::_('GURU_GENERAL');?></a></li>
                                <li  class="<?php echo $quizz_active; ?>" id="li_quizzes" onclick="makeVisible('tab2'); return false;"><a href="#quizzesincl" data-toggle="tab"><?php echo JText::_('GURU_QUIZZES_INCLUDED');?></a></li>
                            </ul>
								
                           <div class="tab-content">
                            <div <?php echo $style_tab1; ?> class="tab-pane <?php echo $general_active; ?>" id="general1">
                                <div class="control-group clearfix g_row_inner">
                                    <div class="g_cell">
                                        <div>
                                            <label class="control-label" for="name"><?php echo JText::_("GURU_NAME");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                        </div>
                                    </div>       
                                    <div class="controls">
                                        <input type="text" id="name" name="name" value="<?php echo $program->name; ?>" />
                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_QUIZ_NAME"); ?>" >
                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                        </span>  
                                    </div>
                                </div>
                                
                                <div class="control-group clearfix g_row_inner">
                                    <div class="g_cell">
                                        <div>
                                            <label class="control-label" for="name"><?php echo JText::_("GURU_PRODDESC");?>:</label>
                                        </div>
                                    </div>        
                                    <div class="controls">
                                        <textarea name="description" id="description" cols="40" rows="8"><?php echo stripslashes($program->description); ?></textarea>
                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_QUIZ_PRODDESC"); ?>" >
                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                        </span> 
                                    </div>
                                </div>
                                
                                 <div class="control-group clearfix g_row_inner">
                                    <div class="g_cell">
                                        <div>
                                            <label class="control-label" for="name"><?php echo JText::_("GURU_MINIMUM_SCORE_FINAL_QUIZ");?>:</label>
                                        </div>
                                    </div>        
                                    <div class="controls">
                                        <div>
                                            <div>
                                                <?php if (isset($program->max_score)){
                                                        $program->max_score = $program->max_score;
                                                      }
                                                      else{
                                                        $program->max_score = 70;
                                                      }
                                                
                                                
                                                
                                                ?>
                                                <input class="input-mini pull-left" type="text" id="max_score_pass" name="max_score_pass" size="6" value="<?php echo $program->max_score;?>" style="float:left !important;" />
                                                <span class="pull-left" style="padding:0px 5px; line-height:30px;">%</span>
                                                <select id="show_max_score_pass" name="show_max_score_pass"  class="input-small" >
                                                    <option value="0" <?php if($program->pbl_max_score == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                                    <option value="1" <?php if($program->pbl_max_score == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                                                </select>
                                                <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_MAX_SCORE_TOOLTIP'); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div> 
                                        </div>           
                                    </div>
                                </div>
                                
                                <div class="control-group clearfix g_row_inner">
                                    <div class="g_cell">
                                        <div>
                                            <label class="control-label" for="name"><?php echo JText::_("GURU_QUIZ_CAN_BE_TAKEN");?>:</label>
                                        </div>
                                    </div>        
                                    <div class="controls">
                                        <div>
                                            <div>
                                               <select id="nb_quiz_taken" name="nb_quiz_taken" class="pull-left input-mini" >
                                                    <option value="1" <?php if($program->time_quiz_taken == "1"){echo 'selected="selected"'; }?> >1</option>
                                                    <option value="2" <?php if($program->time_quiz_taken == "2"){echo 'selected="selected"'; }?> >2</option>
                                                    <option value="3" <?php if($program->time_quiz_taken == "3"){echo 'selected="selected"'; }?> >3</option>
                                                    <option value="4" <?php if($program->time_quiz_taken == "4"){echo 'selected="selected"'; }?> >4</option>
                                                    <option value="5" <?php if($program->time_quiz_taken == "5"){echo 'selected="selected"'; }?> >5</option>
                                                    <option value="6" <?php if($program->time_quiz_taken == "6"){echo 'selected="selected"'; }?> >6</option>
                                                    <option value="7" <?php if($program->time_quiz_taken == "7"){echo 'selected="selected"'; }?> >7</option>
                                                    <option value="8" <?php if($program->time_quiz_taken == "8"){echo 'selected="selected"'; }?> >8</option>
                                                    <option value="9" <?php if($program->time_quiz_taken == "9"){echo 'selected="selected"'; }?> >9</option>
                                                    <option value="10"<?php if($program->time_quiz_taken == "10"){echo 'selected="selected"'; }?> >10</option>
                                                    <option value="11" <?php if($program->time_quiz_taken == "11"){echo 'selected="selected"'; }?>><?php echo JText::_("GURU_UNLIMPROMO");?></option>
                                                </select>
                                                <div class="pull-left" style="padding:0px 5px; line-height:30px;">
                                                    <?php echo JText::_("GURU_TIMES_T"); ?>
                                                </div>
                                                <select id="show_nb_quiz_taken" name="show_nb_quiz_taken" class="input-small" >
                                                    <option value="0" <?php if($program->show_nb_quiz_taken == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                                    <option value="1" <?php if($program->show_nb_quiz_taken == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                                                </select>
                                                <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_TIMES_TAKEN_TOOLTIP'); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>        
                                    </div>
                                </div>
                                
                                <div class="control-group clearfix g_row_inner">
                                    <div class="g_cell">
                                        <div>
                                            <label class="control-label" for="name"><?php echo JText::_("GURU_SELECT_UP_TO");?>:</label>
                                        </div>
                                    </div>        
                                    <div class="controls">
                                        <div>
                                            <div>
                                                <select id="nb_quiz_select_up" name="nb_quiz_select_up" class="input-mini pull-left" >
                                                    <?php
                                                    if (isset($program->nb_quiz_select_up)){
                                                        $program->nb_quiz_select_up = $program->nb_quiz_select_up;
                                                    }
                                                    else{
                                                        $program->nb_quiz_select_up = 10;
                                                    }
                                                    
                                                        for($i=1; $i<=100; $i++){?>
                                                            <option value="<?php echo $i;?>" <?php if($program->nb_quiz_select_up == $i){echo 'selected="selected"'; }?> ><?php echo $i;?></option>
                                                        <?php 
                                                        }
                                                        ?>
                                                </select>
                                                <div class="pull-left" style="padding:0px 5px; line-height:30px;">
                                                    <?php echo JText::_("GURU_QUESTION_RANDOM"); ?>
                                                </div>
                                                <select id="show_nb_quiz_select_up" name="show_nb_quiz_select_up" class="input-small" >
                                                    <option value="0" <?php if($program->show_nb_quiz_select_up == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                                    <option value="1" <?php if($program->show_nb_quiz_select_up == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                                                </select>
                                                <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_QUESTIONS_NUMBER_TOOLTIP'); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                           </div>
                                       </div>         
                                    </div>
                                </div>
                                
                                <div class="control-group clearfix g_row_inner">
                                    <div class="g_cell">
                                        <div>
                                            <label class="control-label" for="name"><?php echo JText::_("GURU_QUESTIONS_PER_PAGE");?></label>
                                        </div>  
                                    </div>      
                                    <div class="controls">
                                        <div>
                                            <div>
                                                <?php
                                                    $questions_per_page = $program->questions_per_page;
                                                ?>
                                                <select name="questions_per_page" class="input-mini">
                                                    <option value="5" <?php if($questions_per_page == "5"){echo 'selected="selected"';} ?> >5</option>
                                                    <option value="10" <?php if($questions_per_page == "10"){echo 'selected="selected"';} ?> >10</option>
                                                    <option value="15" <?php if($questions_per_page == "15"){echo 'selected="selected"';} ?> >15</option>
                                                    <option value="20" <?php if($questions_per_page == "20"){echo 'selected="selected"';} ?> >20</option>
                                                    <option value="25" <?php if($questions_per_page == "25"){echo 'selected="selected"';} ?> >25</option>
                                                    <option value="30" <?php if($questions_per_page == "30"){echo 'selected="selected"';} ?> >30</option>
                                                    <option value="50" <?php if($questions_per_page == "50"){echo 'selected="selected"';} ?> >50</option>
                                                    <option value="100" <?php if($questions_per_page == "100"){echo 'selected="selected"';} ?> >100</option>
                                                    <option value="0" <?php if($questions_per_page == "0"){echo 'selected="selected"';} ?> >All</option>
                                                </select>
                                                &nbsp;
                                                <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_QUESTIONS_PER_PAGE_TOOLTIP'); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                           </div>
                                       </div>         
                                  </div>
                                </div>
                                
                                <div class="alert alert-info"><?php echo JText::_("GURU_TIMER"); ?></div>
                                
                                <div class="control-group clearfix g_row_inner">
                                    <div class="g_cell">
                                        <div>
                                            <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_EXAM_LIMIT_TIME");?>:</label>
                                        </div>
                                    </div>        
                                    <div class="controls">
                                        <div>
                                            <div>
                                                <?php if (isset($program->limit_time)){
                                                            $program->limit_time = $program->limit_time;
                                                          }
                                                          else{
                                                            $program->limit_time = 3;
                                                          }
                                                    
                                                    
                                                    
                                                ?>
                                                <input class="input-mini pull-left" type="text" id="limit_time_l" name="limit_time_l" size="5" maxlength="255" value="<?php echo $program->limit_time; ?>" style="float:left !important;" />
                                                <span class="pull-left" style="padding:0px 5px; line-height:30px;">
                                                    <?php echo JText::_('GURU_PROGRAM_DETAILS_MINUTES'); ?>
                                                </span>
                                                <select id="show_limit_time" name="show_limit_time" class="input-small" >
                                                    <option value="0" <?php if($program->show_limit_time == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                                    <option value="1" <?php if($program->show_limit_time == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                                                </select>
                                                <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_QUIZ_LIMIT_TIME_TOOLTIP'); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                           </div>
                                        </div>                                                         
                                    </div>
                                </div>
                                
                                <div class="control-group clearfix g_row_inner">
                                    <div class="g_cell">
                                        <div>
                                            <label class="control-label" for="name"><?php echo JText::_("GURU_SHOW_COUNTDOWN");?>:</label>
                                        </div>
                                    </div>        
                                    <div class="controls">
                                        <select id="show_countdown" name="show_countdown" class="input-small" >
                                            <option value="0" <?php if($program->show_countdown == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                            <option value="1" <?php if($program->show_countdown == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                                        </select>
                                        <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_SHOW_COUNTDOWN_TOOLTIP'); ?>" >
                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="control-group clearfix g_row_inner">
                                    <div class="g_cell">
                                        <div>
                                            <label class="control-label g_cell span2" for="name"><?php echo JText::_("GURU_FINISH_ALERT");?>:</label>
                                        </div>
                                    </div>        
                                    <div class="controls">
                                        <?php
                                            if (isset($program->limit_time_f)){
                                                $program->limit_time_f = $program->limit_time_f;
                                              }
                                              else{
                                                $program->limit_time_f = 1;
                                              }
                                        ?>
                                        <input class="input-mini pull-left" type="text" id="limit_time_f" name="limit_time_f" size="5" maxlength="255" value="<?php echo $program->limit_time_f; ?>" />
                                        <span class="pull-left" style="padding:0px 5px; line-height:30px;">
                                            <?php echo JText::_('GURU_MINUTES_BEFORE_IS_UP'); ?>
                                        </span>
                                        <select id="show_finish_alert" name="show_finish_alert" class="input-small" >
                                            <option value="0" <?php if($program->show_finish_alert == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                            <option value="1" <?php if($program->show_finish_alert == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                                        </select>
                                        <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_FINISH_ALERT_TOOLTIP'); ?>" >
                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                        </span>
                                  </div>
                               </div>
                               
                            </div>
                            <div <?php echo $style_tab2 ;?> class="tab-pane <?php echo $quizz_active; ?>" id="quizzesincl">
                              <table class="table">
                                <tr>
                                    <td>
                                        <div>
                                            <div style="float:left;">
                                                <a rel="{handler: 'iframe', size: {x: 800, y: 700}, iframeOptions: {id: 'g_teacher_addquizzes'}}" href="<?php echo JURI::root();?>index.php?option=com_guru&controller=guruAuthor&task=addquizzes&tmpl=component&cid=<?php echo $program->id;?>" class="modal"><span title="Parameters" class="icon-32-config"></span><?php echo JText::_("GURU_ADD_QUIZZES"); ?></a>&nbsp;
                                            </div>
                                            <div style="float:left;">
                                                <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_QUIZZES"); ?>" >
                                                    <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                </span>
                                            </div>
                                        </div>
                                        <br/><br/>
                                        <?php						
                                            $db =JFactory::getDBO();
                                            $e = JRequest::getVar("e", "0");
                                            $cid = JRequest::getVar("cid", "0");
                                            
                                            $sql = "SELECT 	quizzes_ids FROM #__guru_quizzes_final WHERE qid=".intval($cid);
                    
                                            $db->setQuery($sql);
                                            $db->query();
                                            $result = $db->loadAssocList();
                                            
                                            $listofids = array();
                                            foreach($result as $value){
                                                //$result_ids = explode(",",trim($value['quizzes_ids']));
                                                $listofids = array_merge($listofids, (array)$value["quizzes_ids"]);
                                            }
                                            $listofids = implode(",", array_unique($listofids));
                                            $listofids = str_replace(",,", ",", $listofids);
                                            $listofids = "0".$listofids;
                                            
                                            
                                            
                                                $sql = "SELECT id, name, published FROM `#__guru_quiz` WHERE id IN (".$listofids.")";
                                                $db->setQuery($sql);
                                                $db->query();
                                                $result_name=$db->loadAssocList();	
                                            
                                             ?>
                                        <table cellspacing="1" cellpadding="5" border="0" bgcolor="#cccccc" width="100%">                  
                                            <tbody id="rowquestion" <?php if(!isset($result_name)) { echo 'style="display: none;"';} ?>>
                                                <tr>
                                                    <td width="42%">
                                                        <strong><?php echo JText::_('Quizzes');?></strong>
                                                    </td>
                                                    <td width="17%">
                                                        <strong><?php echo JText::_('Remove');?></strong>
                                                    </td>
                                                    <td width="12%">
                                                       <!-- <strong><?php //echo JText::_('Edit');?></strong>-->
                                                    </td>
                                                    <td width="14%">
                                                        <strong><?php echo JText::_('GURU_PUBLISHED');?></strong>
                                                    </td>
                                                </tr>                                   
                                             <?php 
                                            if(isset($_POST['deleteq'])){
                                                    $hide_q2del = $_POST['deleteq'];
                                            }
                                            else{
                                                $hide_q2del = ',';
                                            }
                                            $hide_q2del = explode(',', $hide_q2del);
                                             
                                             for ($i = 0; $i < count($result_name); $i++){ 
                                                $link2_remove = '<font color="#FF0000"><span onClick="delete_fq('.$result_name[$i]["id"].','.intval(@$_GET['cid']).', 1)">Remove</span></font>';
                                                $sql = "SELECT 	published FROM #__guru_quizzes_final WHERE qid=".$result_name[$i]["id"];
                                                $db->setQuery($sql);
                                                $db->query();
                                                $published=$db->loadColumn();	
                                             ?>
                                             
                                              <tr id="trfque<?php echo $result_name[$i]["id"]; ?>" <?php if(in_array($result_name[$i]["id"],$hide_q2del)) { ?> style="display:none" <?php } ?>>
                                                    <td width="42%">
                                                        <strong><?php echo $result_name[$i]["name"];?></strong>
                                                    </td>
                                                     <td width="17%">
                                                        <?php echo $link2_remove;  ?>
                                                    </td>
                                                    <td width="12%">
                                                        <?php 	if(isset($published["0"]) && $published["0"] == 1){ 
                                                            echo "<input type='hidden' id='publ".$result_name[$i]["id"]."' name='publish_q[".$result_name[$i]["id"]."]' value='1' />";
                                                        } 
                                                        else{ 
                                                            echo "<input type='hidden' id='publ".$result_name[$i]["id"]."' name='publish_q[".$result_name[$i]["id"]."]' value='0' />";
                                                        }?>
                                                    </td>
                                                    <td width="14%" id="publishing<?php echo $result_name[$i]["id"];?>">
                                                        <?php 
                                                            if($result_name[$i]["published"] == 1) {
                                                                echo '<a class="icon-ok" style="cursor: pointer; text-decoration: none;" onclick="javascript:unpublish(\''.$result_name[$i]["id"].'\');"></a>';
                                                            }
                                                            else{
                                                                echo '<a class="icon-remove" style="cursor: pointer; text-decoration: none;" onclick="javascript:publish(\''.$result_name[$i]["id"].'\');"></a>';
                                                            }
                                                        ?>
                                                    </td>
                                               </tr>      
                                               <?php } ?>     
                                                
                                             
                                                <input type="hidden" value="<?php if (isset($_POST['newquizq'])) echo $_POST['newquizq'];?>" id="newquizq" name="newquizq" >
                                                <input type="hidden" value="<?php if (isset($_POST['deleteq'])) echo $_POST['deleteq'];?>" id="deleteq" name="deleteq" >
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                    
                            </table>
                            </div>	
                        </div>    
                        <input type="hidden" name="id" value="<?php echo $program->id; ?>" />
                        <input type="hidden" name="task" value="" />
                        <input type="hidden" name="valueop" value="<?php echo $value_option; ?>"/>
                        <input type="hidden" name="option" value="com_guru" />
                        <input type="hidden" name="image" value="<?php if(isset($_POST['image'])){echo $_POST['image'];}else{echo $program->image;}?>" />
                        <input type="hidden" name="controller" value="guruAuthor" />
                        <input type="hidden" name="time_format" id="time_format" value="<?php echo $format; ?>" />
                        <input type="hidden" name="all_quizes_nr" id="all_quizes_nr" value="<?php echo intval(count($result_name)); ?>" />
                    </form>
                   </div> 
                </div>
            </div>
        </div>    
    </div>
</div> 