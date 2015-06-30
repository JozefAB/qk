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
	$Itemid = JRequest::getVar("Itemid", "0");
	$document =JFactory::getDocument();
	$document->setTitle(JText::_("GURU_ALREADY_MEMBER"));
	$username = "";
	$email = "";
	$firstname = "";
	$lastname = "";
	$company = "";
		
	if(isset($_SESSION["username"])){
		$username = $_SESSION["username"];
	}
	if(isset($_SESSION["email"])){
		$email = $_SESSION["email"];
	}
	if(isset($_SESSION["firstname"])){
		$firstname = $_SESSION["firstname"];
	}
	if(isset($_SESSION["lastname"])){
		$lastname = $_SESSION["lastname"];
	}
	if(isset($_SESSION["company"])){
		$company = $_SESSION["company"];
	}
	$configs = $this->configs;
	
	$terms_cond_student = $configs["0"]["terms_cond_student"];
	$terms_cond_student_content = $configs["0"]["terms_cond_student_content"];
?>
<script language="javascript" type="text/javascript">
	function isEmail(string) {
		var str = string;
		return (str.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1);
	}
	
	function validateForm(field){ 
		var valid_entry = true;
		
		// validate firstname --------------------------------------------------------------
		if(field == "firstname"){
			if(document.adminForm.firstname.value==""){
				document.getElementById("text_red_f").style.display="block";
				valid_entry = false;
			}
			else if(document.adminForm.firstname.value!=""){
				document.getElementById("text_red_f").style.display="none";
			}
		}
		
		// validate lastname --------------------------------------------------------------
		if(field == "lastname"){
			if(document.adminForm.lastname.value==""){
				document.getElementById("text_red_l").style.display="block";
				valid_entry = false;
			}
			else if(document.adminForm.lastname.value!=""){
				document.getElementById("text_red_l").style.display="none";
			}
		}
		
		// validate email --------------------------------------------------------------
		if(field == "email"){
			if (document.adminForm.email.value==""){
				document.getElementById("text_red_reqr").style.display="block";
				valid_entry = false;
			}
			else if(document.adminForm.email.value!=""){
				document.getElementById("text_red_reqr").style.display="none";
			}
		}
		
		<?php
			if(JRequest::getInt('id', '0', 'request') == "0"){
		?>
				// validate username --------------------------------------------------------------
				if(field == "username"){
					if(document.getElementById('g_username').value  == ""){
						document.getElementById("text_red_requ").style.display="block";
						valid_entry = false;
					}
					else if(document.getElementById('g_username').value !=""){
						document.getElementById("text_red_requ").style.display="none";
					}
				}
				
				// validate password --------------------------------------------------------------
				if(field == "password"){
					if (document.adminForm.password.value==""){
						//document.getElementById("text_red_reqp").style.display="block";
						valid_entry = false;
					}
					else if(document.adminForm.password.value!=""){
						document.getElementById("text_red_reqp").style.display="none";
					}
				}
				
				// validate password_confirm --------------------------------------------------------------
				if(field == "password_confirm"){
					if (document.adminForm.password.value != document.adminForm.password_confirm.value) {
						//document.getElementById("text_red_reqp").style.display="block";
						document.getElementById("text_red_reqp2").style.display="block";
						valid_entry = false;
					}
					else if(document.adminForm.password.value == document.adminForm.password_confirm.value){
						document.getElementById("text_red_reqp").style.display="none";
						document.getElementById("text_red_reqp2").style.display="none";
					}
				}
		<?php 
			}
		?>
		
		// validate email --------------------------------------------------------------
		if(field == "email"){
			if (!isEmail(document.adminForm.email.value)){
				document.getElementById("text_red_req").style.display="block";
				valid_entry = false;
			}
			else if(isEmail(document.adminForm.email.value)){
				document.getElementById("text_red_req").style.display="none";
			}
		}
		
		
		
		username = document.getElementById("g_username").value;
		if(username != ""){
			htmlvalue = "0";
			var req = new Request.HTML({
				async: false,
				method: 'get',
				url: '<?php echo JURI::root()."components/com_guru/js/ajax.php?task=checkExistingUserU";?>&username='+username+'&email='+email+'&id=<?php echo $id; ?>',
				data: { 'do' : '1' },
				onComplete: function(response){
					document.getElementById("ajax_response_u").empty().adopt(response);
				}
			}).send();
			check_return_u = document.getElementById("ajax_response_u").innerHTML;
		}
		email = document.getElementById("email").value;
		if(email != ""){
			htmlvalue = "0";
			var req = new Request.HTML({
				async: false,
				method: 'get',
				url: '<?php echo JURI::root()."components/com_guru/js/ajax.php?task=checkExistingUserE";?>&username='+username+'&email='+email+'&id=<?php echo $id; ?>',
				data: { 'do' : '1' },
				onComplete: function(response){
					document.getElementById("ajax_response_e").empty().adopt(response);
				}
			}).send();
			check_return_e = document.getElementById("ajax_response_e").innerHTML;
		}
		check_return_e = document.getElementById("ajax_response_e").innerHTML;
		if(check_return_e != 0){
			if(trimString(check_return_e) == '111'){// not validate email
				document.getElementById("text_red").style.display="block";
				valid_entry = false;
			}
			else if(trimString(check_return_e) == '222'){// validate email
				document.getElementById("text_red").style.display="none";
			}
		}
		else if(trimString(check_return_e) == ''){// empty input
			document.getElementById("text_red").style.display="none";
		}
		check_return_u = document.getElementById("ajax_response_u").innerHTML;
		if(check_return_u != 0){
			if(trimString(check_return_u) == '222'){// not validate username
				document.getElementById("text_red_u").style.display="block";
				valid_entry = false;
			}
			else if(trimString(check_return_u) == '333'){// validate username
				document.getElementById("text_red_u").style.display="none";
			}
		}
		else if(trimString(check_return_u) == ''){// empty input
			document.getElementById("text_red_u").style.display="none";
			return false;
		}
		
		valid_entry = checkAllFields();
		
		document.adminForm.name.value = document.adminForm.firstname.value+" "+document.adminForm.lastname.value;
		return true;
	}
	
	function validateFormButton(){
		if(document.adminForm.firstname.value==""){
			alert("<?php echo JText::_("GURU_INSERT_FIRSTNAME"); ?>");
			return false;
		}
		
		if(document.adminForm.lastname.value==""){
			alert("<?php echo JText::_("GURU_INSERT_LASTNAME"); ?>");
			return false;
		}
		
		if(document.adminForm.email.value==""){
			alert("<?php echo JText::_("GURU_INSERT_EMAIL"); ?>");
			return false;
		}
		
		if (!isEmail(document.adminForm.email.value)){
			alert("<?php echo JText::_("GURU_PROVIDE_VALID_EMAIL"); ?>");
			return false;
		}
		
		<?php
			if(JRequest::getInt('id', '0', 'request') == "0"){
		?>
				if(document.getElementById('g_username').value==""){
					alert("<?php echo JText::_("GURU_INSERT_USERNAME"); ?>");
					return false;
				}
				
				if (document.adminForm.password.value==""){
					alert("<?php echo JText::_("GURU_INSERT_PASS"); ?>");
					return false;
				}
				
				if (document.adminForm.password.value != document.adminForm.password_confirm.value) {
					alert("<?php echo JText::_("GURU_MATCH_PASS"); ?>");
					return false;
				}
		<?php
			}
		?>
		
		username = document.getElementById("g_username").value;
		if(username != ""){
			htmlvalue = "0";
			var req = new Request.HTML({
				async: false,
				method: 'get',
				url: '<?php echo JURI::root()."components/com_guru/js/ajax.php?task=checkExistingUserU";?>&username='+username+'&email='+email+'&id=<?php echo $id; ?>',
				data: { 'do' : '1' },
				onComplete: function(response){
					document.getElementById("ajax_response_u").empty().adopt(response);
				}
			}).send();
		}
		check_return_u = document.getElementById("ajax_response_u").innerHTML;
		
		
		email = document.getElementById("email").value;
		if(email != ""){
			htmlvalue = "0";
			var req = new Request.HTML({
				async: false,
				method: 'get',
				url: '<?php echo JURI::root()."components/com_guru/js/ajax.php?task=checkExistingUserE";?>&username='+username+'&email='+email+'&id=<?php echo $id; ?>',
				data: { 'do' : '1' },
				onComplete: function(response){
					document.getElementById("ajax_response_e").empty().adopt(response);
				}
			}).send();
		}
		check_return_e = document.getElementById("ajax_response_e").innerHTML;
		
		if(check_return_e != 0){
			if(trimString(check_return_e) == '111'){// not validate email
				document.getElementById("text_red").style.display="block";
				alert("<?php echo JText::_("GURU_EMAIL_IN_USE"); ?>");
				valid_entry = false;
				return false;
			}
			else if(trimString(check_return_e) == '222'){// validate email
				document.getElementById("text_red").style.display="none";
			}
		}
		else if(trimString(check_return_e) == ''){// empty input
			document.getElementById("text_red").style.display="none";
		}
		
		check_return_u = document.getElementById("ajax_response_u").innerHTML;
		if(check_return_u != 0){
			if(trimString(check_return_u) == '222'){// not validate username
				document.getElementById("text_red_u").style.display="block";
				alert("<?php echo JText::_("GURU_USERNAME_IN_USE"); ?>");
				valid_entry = false;
				return false;
			}
			else if(trimString(check_return_u) == '333'){// validate username
				document.getElementById("text_red_u").style.display="none";
			}
		}
		else if(trimString(check_return_u) == ''){// empty input
			document.getElementById("text_red_u").style.display="none";
			return false;
		}
		
		if(!validateTerms()){
			return false;
		}
		
		document.adminForm.submit();
	}
	
	function checkAllFields(){
		// validate firstname --------------------------------------------------------------
		if(document.adminForm.firstname.value==""){
			return false;
		}
		
		// validate lastname --------------------------------------------------------------
		if(document.adminForm.lastname.value==""){
			return false;
		}
		
		// validate email --------------------------------------------------------------
		if(document.adminForm.email.value==""){
			return false;
		}
		
		<?php
			if(JRequest::getInt('id', '0', 'request') == "0"){
		?>
				// validate username --------------------------------------------------------------
				if(document.getElementById("g_username").value ==""){
					return false;
				}
				
				// validate password --------------------------------------------------------------
				if(document.adminForm.password.value==""){
					return false;
				}
				
				// validate password_confirm --------------------------------------------------------------
				if(document.adminForm.password.value != document.adminForm.password_confirm.value){
					return false;
				}
		<?php
			}
		?>
		
		// validate email --------------------------------------------------------------
		if(!isEmail(document.adminForm.email.value)){
			return false;
		}
		
		return true;
	}
	
	var request_processed = 0;        
	
	function trimString(str){
		str = str.toString();
		var begin = 0;
		var end = str.length - 1;
		while (begin <= end && str.charCodeAt(begin) < 33) { ++begin; }
		while (end > begin && str.charCodeAt(end) < 33) { --end; }
		return str.substr(begin, end - begin + 1);
	}
	
	function validateTerms(){
		<?php
			if($terms_cond_student == 1){
		?>
				terms_cond_student = document.getElementById("terms_cond_student");
				if(terms_cond_student.checked == false){
					alert("<?php echo JText::_("GURU_SELECT_TERMS_AND_COND"); ?>");
					return false;
				}
		<?php
			}
		?>
		return true;
	}
</script>
<div class="g_row" id="g_registrationform">
	<div class="g_cell span12">
		<div>
			<div>
                <div class="profile_row_guru span12 clearfix">
                	<div class="profile_page page_title">
                    	<h2><?php echo JText::_('GURU_PROFILE_SETTINGS');?></h2>
                	</div> 
                    <form class="form-horizontal"  method="post" name="adminForm" id="adminForm" onsubmit="return validateTerms()">
                         <div class="control-group g_row">
                                <label class="control-label g_cell span3" for="firstname"><?php echo JText::_("GURU_FIRS_NAME");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                <div class="controls g_cell span5">
                                	<span>
                                   		<input onchange="validateForm('firstname');" type="text" class="inputbox" size="30" id="firstname" name="firstname" value="<?php echo $firstname; ?>" />
                                    </span>    
                                </div>
                                <div class="pull-left alert alert-warning" style="display:none;" id="text_red_f"><?php echo JText::_("DSALL_REQUIRED_FIELDS"); ?></div>
                          </div>
                          <div class="control-group g_row">
                                <label class="control-label g_cell span3" for="lastname"><?php echo JText::_("GURU_LAST_NAME");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                <div class="controls g_cell span5">
                                	<span>
                                   		<input onchange="validateForm('lastname');" type="text" class="inputbox" size="30" id="lastname" name="lastname" value="<?php echo $lastname; ?>"/>
                                    </span>    
                                </div>
                                <div class="pull-left alert alert-warning" style="display:none;" id="text_red_l"><?php echo JText::_("DSALL_REQUIRED_FIELDS"); ?></div>
                          </div>
                          <div class="control-group g_row">
                                <label class="control-label g_cell span3" for="company"><?php echo JText::_("GURU_COMPANY");?>: <span>&nbsp;&nbsp;&nbsp;</span></label>
                                <div class="controls g_cell span5">
                                	<span>
                                		<input  onchange="validateForm('company');" type="text" class="inputbox" size="30" id="company" name="company" value="<?php echo $company; ?>"/>
                                    </span>    
                                </div>
                          </div>
                         <div class="control-group g_row">
                                <label class=" control-label g_cell span3" for="email"><?php echo JText::_("GURU_EMAIL");?>:<span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                <div class="controls g_cell span5">
                                	<span class="g_input">
                                  		<input onchange="validateForm('email');" type="text" <?php if (isset($cust) && isset($cust->id)){?> disabled <?php }?> class="inputbox" size="30" id="email" name="email" value="<?php echo $email; ?>"/>
                                    </span>
                                </div>
                                <span class="g_level_remark" style="font-size:0.8em"><?php echo JText::_('DSEMAILNOTE'); ?></span>
                          </div>
                          <div class="control-group g_row">
                            <div class="controls g_cell pull-left">
                                <div class="pull-left alert alert-warning" style="display:none;" id="text_red"><?php echo JText::_("GURU_EMAIL_IN_USE"); ?></div>
                                <div class="pull-left alert alert-warning" style="display:none;" id="text_red_req"><?php echo JText::_("DSINVALID_EMAIL"); ?></div>
                                <div class="pull-left alert alert-warning" style="display:none;" id="text_red_reqr"><?php echo JText::_("DSALL_REQUIRED_FIELDS"); ?></div>
                            </div>	
                          </div>  	
                          <div class="profile_page page_title">
                            <h2><?php echo JText::_('GURU_LOGIN_INFORMATIONS');?></h2>
                          </div>  
                         <div class="control-group g_row">
                                <label class="control-label g_cell span3" for="firstname"><?php echo JText::_("GURU_PROFILE_USERNAME");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                <div class="controls g_cell span5">
                                	<span>
                                   		<input onchange="validateForm('username');" type="text" <?php if (isset($cust) && isset($cust->id)){?> disabled <?php } ?> class="inputbox" size="30" id="g_username" name="username" value="<?php echo $username; ?>" />
                                     
                                </div>
                                <div class="controls g_cell pull-left">
                                	<div class="pull-left alert alert-warning" style="display:none;" id="text_red_u"><?php echo JText::_("GURU_USERNAME_IN_USE"); ?></div>
                                    <div class="pull-left alert alert-warning" style="display:none;" id="text_red_requ"><?php echo JText::_("DSALL_REQUIRED_FIELDS"); ?></div>
                                </div>    
                          </div>
                          <?php  if (!isset($cust) && !isset($cust->id)){?>	
                                     <div class="control-group g_row">
                                        <label class="control-label g_cell span3" for="lastname"><?php echo JText::_("GURU_PROFILE_REG_PSW");?>: <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                        <div class="controls g_cell span5">
                                        	<span>
                                           		<input onchange="validateForm('password');" type="password" class="inputbox" size="30" id="password" name="password" />
                                            </span>    
                                        </div>
                                        <div class="controls g_cell pull-left">
                                        	<div class="pull-left alert alert-warning" style="display:none;" id="text_red_reqp"><?php echo JText::_("DSCONFIRM_PASSWORD_MSG"); ?></div>
                                        </div>
                                    </div>
                                    <div class="control-group g_row">
                                        <label class="control-label g_cell span3" for="company"><?php echo JText::_("GURU_PROFILE_REG_PSW2");?> <span class="guru_error">*</span>&nbsp;&nbsp;</label>
                                        <div class="controls g_cell span5">
                                        	<span>
                                           		<input onchange="validateForm('password_confirm');" type="password" class="inputbox" size="30" id="password_confirm" name="password_confirm"/>
                                           	</span>
                                        </div>
                                        <div class="controls g_cell pull-left">
                                        	<div class="pull-left alert alert-warning" style="display:none;" id="text_red_reqp2"><?php echo JText::_("DSCONFIRM_PASSWORD_MSG"); ?></div>
                                        </div>    
                                    </div>		
                            <?php } ?>
                            
                            <?php
                            	if($terms_cond_student == 1){
							?>
                                    <div class="control-group g_row">
                                    	<label class="control-label g_cell span3" for="firstname"></label>
                                    	<div class="controls g_cell span5">
											<input type="checkbox" value="1" name="terms_cond_student" id="terms_cond_student" />
											<span class="lbl"></span>
                                            <a href="#" onclick='window.open("<?php echo JURI::root()."index.php?option=com_guru&controller=guruLogin&task=terms&tmpl=component"; ?>", "", "width=500, height=400")'><?php echo JText::_("GURU_TERMS_AND_COND"); ?></a>
                                    	</div>
                                    </div>
                            <?php
								}
							?>
                            
                            <div class="g_row">
                            	<div class="g_cell span3 g_offset"></div>
                                <div class="g_cell span9">
									<input type="button" onclick="history.go(-1);" class="btn btn-danger" value="<?php echo JText::_("GURU_CANCEL")?>" />
                                      <?php 
						   				if($configs["0"]["gurujomsocialregstudent"]== 0){
									  ?>
                        			 		<input id="guru_create_account" type="button" class="btn btn-primary" onclick="javascript:validateFormButton();" value="<?php echo JText::_("GURU_CREATE_ACCOUNT")?>" />  
                                      <?php
									  	}
										else{
										?>
                                        	<input type="submit" class="btn btn-primary" value="<?php echo JText::_("GURU_NEXT")?>" />
                                        <?php
										}
									   ?>    
                                </div>
                          </div>
                    <?php
					$x = intval(JRequest::getVar("cid", "",'request'));
                    if( $x == 0){
						$course_id = intval(JRequest::getVar("course_id", "",'request'));
					}
					else{
						$course_id = intval(JRequest::getVar("cid", "",'request'));
					}
					?>   
                    <div id="ajax_response_u" style="display:none;"></div>  
                    <div id="ajax_response_e" style="display:none;"></div>    
                    <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
                    <input type="hidden" name="images" value="" />                
                    <input type="hidden" name="option" value="com_guru" />
                    <input type="hidden" name="id" value="" />
                    <input type="hidden" name="task" value="saveCustomer" />
                    <input type="hidden" name="returnpage" value="<?php echo (JRequest::getVar("returnpage", "", 'request'));?>" />		
                    <input type="hidden" name="controller" value="guruLogin" />
                    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />  
                    <input type="hidden" name="registered_user" value="1" />  
                    <input type="hidden" name="guru_teacher" value="1">  
                    <input type="hidden" name="studentpage" value="studentpage">    
                    </form>
                </div>
			</div>
		</div>
	</div>
</div>                  