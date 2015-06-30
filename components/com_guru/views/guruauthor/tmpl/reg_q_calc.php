<script language="javascript" type="text/javascript">

	function isFloat(nr){
		return parseFloat(nr.match(/^-?\d*(\.\d+)?$/)) > 0;
	}
	
	function save_quizFE(pressbutton){
		var form = document.adminForm;
		if (pressbutton == 'save_quizFE' || pressbutton == 'apply_quizFE') {
			if (form['name'].value == "") {
				alert( "<?php echo JText::_("GURU_TASKS_JS_NAME");?>" );
			} 
			else {
				//---------------------------------
				max_score_pass = document.getElementById("max_score_pass").value;
				limit_time_l = document.getElementById("limit_time_l").value;
				limit_time_f = document.getElementById("limit_time_f").value;
				nb_quiz_select_up = document.getElementById("nb_quiz_select_up").value;
				
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
				else if(nb_quiz_select_up == 'text1'){
					alert("<?php echo JText::_("GURU_ADD_QUESTION_ALERT"); ?>");
					return false;
				}
				
				if((parseInt(limit_time_l) < parseInt(limit_time_f)) || (parseInt(limit_time_l) == parseInt(limit_time_f))){
					alert("<?php echo JText::_("GURU_LIMIT2_GRATER_LIMIT1"); ?>");
					return false;
				}
				//---------------------------------
				submitform( pressbutton );
			}
		}
		else {
			submitform( pressbutton );
		}
	}
	function changeSort(){
		var changeSort="";
		changeSort = document.getElementById("sortquestion").value;
		if(changeSort == 'ASC'){
			document.getElementById("sortquestion").value = "DESC";
		}
		else{
			document.getElementById("sortquestion").value = "ASC";
		}
		document.adminForm.task.value = 'editQuizFE';
		document.adminForm.submit();
	}
	
	function makeVisible(tab){
		if(tab == 'tab1'){
			document.getElementById("li_general").className="active";
			document.getElementById("li_quizzes").className="";
			document.getElementById("general").className="tab-pane active";
			document.getElementById("general").style.display="block";
			
			document.getElementById("question").className="";
			document.getElementById("question").style.display="none";
		}
		else if(tab == 'tab2'){
			document.getElementById("li_quizzes").className="active";
			document.getElementById("li_general").className="";
			document.getElementById("general").className="";
			document.getElementById("question").className="tab-pane active";
			document.getElementById("general").style.display="none";
			document.getElementById("question").style.display="block";

		}
	}
</script>

<style type="text/css">
	.form-horizontal .controls{
		margin-left: 205px;
	}
	#sbox-content.sbox-content-iframe {
		overflow: hidden!important;
	}
	.guru-content .btn-danger,
	.guru-content .btn.btn-danger {
		margin:0px;
	}
</style>
<?php
		$dateformat = $this->gurudateformat;
?>
<div id="myModal" class="modal hide">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    </div>
    <div class="modal-body">
    </div>
</div>	
<div class="g_row">
	<div class="g_cell span12">
		<div>
			<div>
                <div id="g_myquizzesaddedit" class="clearfix com-cont-wrap">
					<?php 	echo $div_menu; //MENU TOP OF AUTHORS?>
                    <div class="g_inline_child g_margin_bottom">
                            <input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE"); ?>" onclick="javascript:save_quizFE('apply_quizFE');" />
                            <input type="button" class="btn btn-success" value="<?php echo JText::_("GURU_SAVE_AND_CLOSE"); ?>" onclick="javascript:save_quizFE('save_quizFE');" />
                            <input type="button" class="btn btn-inverse" value="<?php echo JText::_("GURU_CLOSE"); ?>" onclick="document.location.href='<?php echo JRoute::_("index.php?option=com_guru&view=guruauthor&task=authorquizzes&layout=authorquizzes"); ?>';" />
                    </div>
                    <div id="g_teacher_quiz">   
                            <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-horizontal">
                                <div class="quiz_teacher_page page_title">
                                    <h2> 
                                        <?php
                                            if($program->id < 1){
                                                echo JText::_('GURU_NEW_Q_CREATION');
                                            }
                                            else{
                                                echo JText::_('GURU_EDIT_EXISTING_QUIZ');
                                            }
                                        ?>
                                   </h2>
                                </div>
                                <?php
									$sortquestion = JRequest::getVar("sortquestion", "");
									$active_pagination = JRequest::getVar("active_pagination", "0");
									$style_tab2 = "style='display:none'";
									$style_tab1 = "style='display:block'";
									
									if($sortquestion !=""){
										@$class1 = "";
										@$class2 = "class='active'";
										@$class3 = "";
										
										@$class11 = "";
										@$class21 = " active";
										@$class31 = "";
									}
									elseif($active_pagination == "1"){
										@$class1 = "";
										@$class2 = "class='active'";
										@$class3 = "";
										
										@$class11 = "";
										@$class21 = " active";
										@$class31 = "";
									}
									else{
										@$class1 = "class='active'";
										@$class2 = "";
										
										@$class11 = " active";
										@$class21 = "";
										
										if(isset($_SESSION["added_questions_tab"]) && $_SESSION["added_questions_tab"] == 1){
											$_SESSION["added_questions_tab"] = 0;
											@$class1 = "";
											@$class2 = "class='active'";
											@$class11 = "";
											@$class21 = " active";
											$style_tab1 = "style='display:none'";
											$style_tab2 = "style='display:block'";
										}
									}
									
								?>
                                    
                                    <ul class="nav nav-tabs">
                                        <li <?php echo @$class1; ?> id="li_general" onclick="makeVisible('tab1'); return false;"><a href="#general" data-toggle="tab"><?php echo JText::_('GURU_GENERAL');?></a></li>
                                        <li <?php echo @$class2; ?> id="li_quizzes" onclick="makeVisible('tab2'); return false;"><a href="#question" data-toggle="tab"><?php echo JText::_('GURU_QUESTIONS');?></a></li>
                                     </ul>
                                     
                                     <div class="tab-content">
                                        <div  <?php echo $style_tab1; ?> class="tab-pane <?php echo @$class11; ?>" id="general">
                                            
                                            <div class="control-group clearfix g_row_inner">
                                                <div class="g_cell">
                                                    <div>
                                                        <label class="control-label" for="name"><?php echo JText::_("GURU_NAME");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                                    </div>
                                                </div>
                                                <div class="controls">
                                                    <div>
                                                        <div>
                                                            <input type="text" id="name" name="name" value="<?php echo $program->name; ?>" />
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_QUIZ_NAME"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div> 
                                                    </div>     
                                                </div>
                                            </div>
                                            
                                            <div class="control-group clearfix g_row_inner">
                                                <div class="g_cell">
                                                    <div>
                                                        <label class="control-label" for="name"><?php echo JText::_("GURU_PRODDESC");?>:</label>
                                                    </div>
                                                </div>        
                                                <div class="controls">
                                                    <div>
                                                        <div>
                                                            <textarea name="description" id="description" cols="40" rows="8"><?php echo stripslashes($program->description); ?></textarea>
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_QUIZ_PRODDESC"); ?>" >
                                                                <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                    </div>        
                                                </div>
                                            </div>
                                            
                                             <div class="control-group clearfix g_row_inner">
                                                <div class="g_cell">
                                                    <div>
                                                        <label class="control-label" for="author_blog"><?php echo JText::_("GURU_MINIMUM_SCORE_QUIZ");?>:</label>
                                                    </div>
                                                </div>        
                                                <div class="controls">
                                                    <div>
                                                        <div>
                                                            <span>
                                                               <?php if (isset($program->max_score)){
                                                                    $program->max_score = $program->max_score;
                                                                  }
                                                                  else{
                                                                    $program->max_score = 70;
                                                                  }
                                                            ?>
                                                                <input type="text" id="max_score_pass" name="max_score_pass" value="<?php echo $program->max_score;?>" class="input-mini pull-left" />&nbsp;
                                                                <select id="show_max_score_pass" name="show_max_score_pass"  class="input-small" >
                                                                    <option value="0" <?php if($program->pbl_max_score == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                                                    <option value="1" <?php if($program->pbl_max_score == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                                                                </select>
                                                                <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_MAX_SCORE_TOOLTIP'); ?>" >
                                                                	<img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            	</span>
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
                                                           <select id="nb_quiz_taken" name="nb_quiz_taken" class="input-mini pull-left" >
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
                                                            <span class="pull-left" style="line-height: 30px; padding: 0 5px;">
																<?php echo JText::_("GURU_TIMES_T"); ?>
															</span>
                                                            
                                                            <select id="show_nb_quiz_taken" name="show_nb_quiz_taken" class="input-small" >
                                                                <option value="0" <?php if($program->show_nb_quiz_taken == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                                                <option value="1" <?php if($program->show_nb_quiz_taken == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                                                            </select>&nbsp;
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
                                                        <label class="control-label" for="name"><?php echo JText::_("GURU_SELECT_UP_TO");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                                    </div>  
                                                </div>      
                                                <div class="controls">
                                                    <div>
                                                        <div>
                                                            <select id="nb_quiz_select_up" name="nb_quiz_select_up" class="input-mini pull-left" >
																<?php
                                                                if(isset($amount_quest) && $amount_quest !=0 ){
                                                                    for($i=$amount_quest; $i>=1; $i--){?>
                                                                        <option value="<?php echo $i;?>" <?php if($program->nb_quiz_select_up == $i){echo 'selected="selected"'; }?> ><?php echo $i;?></option>
                                                                    <?php 
                                                                    }
                                                                }
                                                                else{
                                                                ?>
                                                                    <option value="text1"><?php echo "Please add questions first";?></option>
                                                                
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                            <span class="pull-left" style="padding:0px 5px; line-height:30px;">
																<?php echo JText::_('GURU_QUESTION_RANDOM'); ?>
															</span>
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
                                                        <label class="control-label" for="name"><?php echo JText::_("GURU_QUIZ_LIMIT_TIME");?>:</label>
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
                                                    <div>
                                                        <div>
                                                            <select id="show_countdown" name="show_countdown" class="input-small pull-left" >
                                                                <option value="0" <?php if($program->show_countdown == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                                                <option value="1" <?php if($program->show_countdown == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                                                            </select>
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_SHOW_COUNTDOWN_TOOLTIP'); ?>" >
																<img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                            </span>
                                                        </div>
                                                    </div>        
                                                </div>
                                            </div>
                                            
                                            <div class="control-group clearfix g_row_inner">
                                                <div class="g_cell">
                                                    <div>
                                                        <label class="control-label" for="name"><?php echo JText::_("GURU_FINISH_ALERT");?>:</label>
                                                    </div>
                                                </div>       
                                                <div class="controls">
                                                    <div>
                                                        <div>
                                                        <?php if (isset($program->limit_time_f)){
                                                                $program->limit_time_f = $program->limit_time_f;
                                                              }
                                                              else{
                                                                $program->limit_time_f = 1;
                                                              }
                                                        
                                                        
                                                        
                                                        ?>
                                                            <input class="input-mini pull-left" type="text" id="limit_time_f" name="limit_time_f" size="5" maxlength="255" value="<?php echo $program->limit_time_f; ?>" style="float:left !important;" />
                                                            <span class="pull-left" style="padding:0px 5px; line-height:30px;">
                                                            	<?php echo JText::_('GURU_MINUTES_BEFORE_IS_UP'); ?>
															</span>
                                                            <select id="show_finish_alert" name="show_finish_alert" class="pull-left input-small" >
                                                                <option value="0" <?php if($program->show_finish_alert == "0"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_SHOW"); ?></option>
                                                                <option value="1" <?php if($program->show_finish_alert == "1"){echo 'selected="selected"'; } ?>><?php echo JText::_("GURU_HIDE"); ?></option>
                                                        	</select>
                                                            <span class="editlinktip hasTip" title="<?php echo JText::_('GURU_FINISH_ALERT_TOOLTIP'); ?>" >
                                                        		<img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                			</span>
                                                       </div>
                                                   </div> 
                                              </div>
                                           </div>
                                           
                                        </div>
                                        
                                  <div  <?php echo $style_tab2; ?> class="tab-pane <?php echo @$class21; ?>" id="question">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <div>
                                                    <div style="float:left;">
                                                        <a rel="{handler: 'iframe', size: {x: 800, y: 700}, iframeOptions: {id: 'g_teacher_addquestion'}}" href="<?php echo JURI::root(); ?>index.php?option=com_guru&controller=guruAuthor&task=addquestion&tmpl=component&no_html=1&cid=<?php echo $program->id;?>&is_from_modal=1" class="modal"><span title="Parameters" class="icon-32-config"></span><?php echo JText::_("GURU_ADD_QUESTION"); ?></a>&nbsp;
                                                    </div>
                                                    <div style="float:left;">
                                                        <span class="editlinktip hasTip" title="<?php echo JText::_("GURU_TIP_QUIZ_ADD_QUESTION"); ?>" >
                                                            <img border="0" src="components/com_guru/images/icons/tooltip.png">
                                                        </span>
                                                    </div>
                                                </div>
                                                <br/><br/>
                                                <table id="articleList" class="table" cellspacing="1" cellpadding="5" border="0" bgcolor="#cccccc" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="1%">
                                                                <a href="#" onclick="changeSort();"><i class="icon-menu-2"></i></a>
                                                                <?php
                                                                	$sortquestion = JRequest::getVar("sortquestion", "");
																?>
                                                                <input type="hidden" name="sortquestion" id="sortquestion" value="<?php echo $sortquestion; ?>" />
                                                            </th>
                                                            <th width="1%"></th>
                                                            <th width="42%">
                                                                <strong><?php echo JText::_('GURU_QUESTIONS');?></strong>
                                                            </th>
                                                            <th width="12%">
                                                                <strong><?php echo JText::_('GURU_EDIT');?></strong>
                                                            </th>
                                                             <th width="17%">
                                                                <strong><?php echo JText::_('GURU_REMOVE');?></strong>
                                                            </th>
                                                            <th width="14%">
                                                                <strong><?php echo JText::_('GURU_PUBLISHED');?></strong>
                                                            </th>
                                                        </tr> 
                                                    <thead>    
                                                    <tbody id="rowquestion">                
                                                    <?php 
                                                    if(isset($_POST['deleteq'])){
                                                        $hide_q2del = $_POST['deleteq'];
                                                    }
                                                    else{
                                                        $hide_q2del = ',';
                                                    }
                                                    $hide_q2del = explode(',', $hide_q2del);
                                                    $i = 0;
													
													$questions_per_page = $program->questions_per_page;
													if(intval($questions_per_page) == 0){
														$questions_per_page = 5;
													}
													
                                                    foreach ($mmediam as $mmedial) { 
                                                        $link2_remove = '<font class="btn btn-danger"><span onClick="delete_q('.$mmedial->id.','.$program->id.',0)">'.JText::_('GURU_REMOVE').'</span></font>';
														
														if($i % $questions_per_page == 0 && $i != 0){
															echo '<tr><td colspan="6" class="quiz-limit-page">'.JText::_("GURU_PAGE").' '.($i / $questions_per_page).'<hr style="border-top: 2px solid red;"></td></tr>';
														}
													?>
                                                        
                                                        <tr class="row<?php echo $i%2;?>" id="trque<?php echo $mmedial->id; ?>" <?php if(in_array($mmedial->id,$hide_q2del)) { ?> style="display:none" <?php } ?>>
                                                            <td>
                                                                <span class="sortable-handler active" style="cursor: move;">
                                                                    <i class="icon-menu"></i>
                                                                </span>
                                                                <input type="text" class="width-20 text-area-order " value="<?php echo $mmedial->reorder; ?>" size="5" name="order[]" style="display:none;">
                                                            </td> 
                                                            <td width="1%" style="text-align:center; visibility:hidden;">>
															 	<?php
                                                                	$checked = JHTML::_('grid.id', $i, $mmedial->id); echo $checked;
																?>
															</td>
                                                            <td id="tdq<?php echo $mmedial->id?>" width="42%">
                                                                <a rel="{handler: 'iframe', size: {x: 800, y: 700}, iframeOptions: {id: 'g_teacher_editquestion'}}"  href="<?php echo JURI::root(); ?>index.php?option=com_guru&controller=guruAuthor&task=editquestion&is_from_modal=1&tmpl=component&no_html=1&cid=<?php echo $program->id.'&qid='.$mmedial->id;?>"  class="modal question-title"><?php if (strlen ($mmedial->text) >55){echo substr(str_replace("\'","&acute;" ,$mmedial->text),0,55).'...';}else{echo str_replace("\'","&acute;" ,$mmedial->text);}?></a>
                                                            </td>
                                                            <td width="12%">
                                                                <a class=" modal btn btn-primary" rel="{handler: 'iframe', size: {x: 800, y: 700}, iframeOptions: {id: 'g_teacher_editquestions'}}"  href="<?php echo JURI::root(); ?>index.php?option=com_guru&controller=guruAuthor&task=editquestion&is_from_modal=1&tmpl=component&no_html=1&cid=<?php echo $program->id.'&qid='.$mmedial->id;?>"><?php echo JText::_('GURU_EDIT');?></a>
                                                                    <?php 	if($mmedial->published==1){ 
                                                                                echo "<input type='hidden' id='publ".$mmedial->id."' name='publish_q[".$mmedial->id."]' value='1' />";
                                                                            } 
                                                                            else{ 
                                                                                echo "<input type='hidden' id='publ".$mmedial->id."' name='publish_q[".$mmedial->id."]' value='0' />";
                                                                            }?>
                                                            </td>
                                                            <td width="17%">
                                                                <?php echo $link2_remove; ?>
                                                            </td>
                                                            <td width="14%" id="publishing<?php echo $mmedial->id;?>">
                                                                <?php 
                                                                    if($mmedial->published == 1) {
                                                                        echo '<a class="icon-ok" style="cursor: pointer; text-decoration: none;" onclick="javascript:unpublish(\''.$mmedial->id.'\');"></a>';
                                                                    }
                                                                    else{
                                                                        echo '<a class="icon-remove" style="cursor: pointer; text-decoration: none;" onclick="javascript:publish(\''.$mmedial->id.'\');"></a>';
                                                                    }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    $i++;
                                                    }//end foreach?>
                                                        <input type="hidden" value="<?php if (isset($_POST['newquizq'])) echo $_POST['newquizq'];?>" id="newquizq" name="newquizq" >
                                                        <input type="hidden" value="<?php if (isset($_POST['deleteq'])) echo $_POST['deleteq'];?>" id="deleteq" name="deleteq" >
                                                        
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                            
                                    </table>
                                    <?php
										$limit = $this->pagination->getLimitBox();
										$limit = str_replace('onchange="', 'onchange="document.adminForm.active_pagination.value=1; ', $limit);
										echo $limit;
										$media = $this->media;
										$app = JFactory::getApplication('site');
										$limit = $app->getUserStateFromRequest( 'limit', 'limit', $app->getCfg('list_limit'), 'int');
										$limitstart = JRequest::getVar("limitstart", "0");
										$total = @$media->total;
										
										if($total <= $limit){
											// no pagination
										}
										else{
											$nr_pages = 0;
											if(intval($limit) > 0){
												$nr_pages = ceil($total / $limit);
											}
											
											echo '<div class="pagination pagination-centered"><ul class="uk-pagination">';
											for($i=1; $i<=$nr_pages; $i++){
												$current_page = ($limitstart / $limit) + 1;
												echo '<li>';
												if($current_page == $i){
													echo '<span class="pagenav">'.$i.'</span>';
												}
												else{
													echo '<a href="#" onclick="document.adminForm.limitstart.value='.(($i-1) * $limit).'; document.adminForm.active_pagination.value=1; document.adminForm.submit(); return false;">'.$i.'</a>';
												}
												echo '</li>';
											}
											echo '</ul></div>';
										}
										
										echo '<input type="hidden" name="limitstart" value="'.$limitstart.'" />';
										echo '<input type="hidden" name="active_pagination" value="0" />';
									?>
                                </div>
                                
                                <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
                                <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
                                <?php echo JHtml::_('form.token'); ?>	
                                <input type="hidden" name="id" value="<?php echo $program->id; ?>" />
                                <input type="hidden" name="task" value="editQuizFE" />
                                <input type="hidden" name="valueop" value="<?php echo $value_option; ?>"/>
                                <input type="hidden" name="option" value="com_guru" />
                                <input type="hidden" name="image" value="<?php if(isset($_POST['image'])){echo $_POST['image'];}else{echo $program->image;}?>" />
                                <input type="hidden" name="controller" value="guruAuthor" />
                                <input type="hidden" name="time_format" id="time_format" value="<?php echo $format; ?>" />
                                <?php
									$cid = JRequest::getVar("cid", "0");
									$v = JRequest::getVar("v", "");
									$e = JRequest::getVar("e", "");
								?>
                                <input type="hidden" name="cid" id="cid" value="<?php echo $cid; ?>" />
                                <input type="hidden" name="v" id="v" value="<?php echo $v; ?>" />
                                <input type="hidden" name="e" id="e" value="<?php echo $e; ?>" />
                            </form>
                         </div>   
                    </div>
              </div>
          </div>
       </div>
  </div> 
  </div>