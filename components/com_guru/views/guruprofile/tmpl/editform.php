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
$user = JFactory::getUser();

$user_id = "";
$user_username = "";
$user_email = "";
$firstname = "";
$lastname = "";
$company = "";
$returnpage = JRequest::getVar("returnpage", "");
$Itemid = JRequest::getVar("Itemid", "0");

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_guru/css/guru_style.css");


$document->setTitle(trim(JText::_('GURU_MY_ACCOUNT')));

if(isset($user)){
	$user_id = $user->id;
	$user_username = $user->username;
	$user_email = $user->email;
	$customer_profile = $this->getCustomerProfile();
	if(isset($customer_profile) && count($customer_profile) > 0){
		$firstname = $customer_profile["0"]["firstname"];
		$lastname = $customer_profile["0"]["lastname"];
		$company = $customer_profile["0"]["company"];
	}
	else{
		$name = $user->name;
		$temp = explode(" ", $name);
		if(count($temp) == 1){
			$firstname = $name;
		}
		else{
			$firstname = $temp["0"];
			unset($temp["0"]);
			$lastname = implode(" ", $temp);
		}
	}
}
$return_url = base64_encode("index.php?option=com_guru&view=guruProfile&task=edit&Itemid=".intval(@$itemid));

?>

<script language="javascript" type="text/javascript">
	function validateForm(){
		var first_name = document.adminForm.firstname.value;
		var last_name = document.adminForm.lastname.value;
		if(first_name == ""){
			alert("Firs Name is mandatory!");
			return false;
		}
		else if(last_name == ""){
			alert("Last Name is mandatory!");
			return false;
		}		
        if(document.adminForm.password.value != document.adminForm.password_confirm.value){
			alert("<?php echo JText::_("DSCONFIRM_PASSWORD_MSG");?>");
            return false;
        }   				
		return true;
	}
</script>
<div class="g_row">
	<div class="g_cell span12">
		<div>
			<div>
                <form onsubmit="return validateForm();" id="adminForm" name="adminForm" method="post" action="index.php">
                <!--CHANGED BY JOSEPH 31/03/2015-->
                	<!--<div id="guru_menubar" class="clearfix g_toolbar guru_menubar">
                		<ul>
                            <li id="my_account"><a class="g_toolbar_active" href="index.php?option=com_guru&view=guruProfile&task=edit&Itemid=<?php //echo $Itemid; ?>"><i class="icon-user"></i><?php //echo JText::_("GURU_MY_ACCOUNT"); ?></a></li>
                            <li id="my_courses"><a href="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php //echo $Itemid; ?>"><i class="icon-eye-open"></i><?php //echo JText::_("GURU_MYCOURSES"); ?></a></li>
                            <li id="my_orders"><a href="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php //echo $Itemid; ?>"><i class="icon-cart"></i><?php //echo JText::_("GURU_MYORDERS_MYORDERS"); ?></a></li>
                            <li id="my_quizzes"><a href="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php //echo $Itemid; ?>"><i class="icon-question-sign"></i><?php //echo JText::_("GURU_QUIZZ_FINAL_EXAM"); ?></a></li>
                            <li id="my_certificates"><a href="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php //echo $Itemid; ?>"><i class="icon-star"></i><?php //echo JText::_("GURU_MYCERTIFICATES"); ?></a></li>
                            <li class="g_hide_mobile logout-btn" id="g_logout">
                                <a href="index.php?option=com_users&task=user.logout&<?php //echo JSession::getFormToken(); ?>=1&Itemid=<?php //echo $Itemid; ?>&return=<?php //echo $return_url;?>">
                                    <i class="fa fa-sign-out"></i>
                                </a>
							</li>
                        </ul>
                	</div>
                	<div id="guru_menubar_mobile" class="g_mobile guru_menubar g_select">
                        <select name="menuboostrap" class="g_select" id="menuboostrap" onchange="window.open(this.value, '_self');" >
                         <option value="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_MYCOURSES");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_MYORDERS_MYORDERS");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_QUIZZ_FINAL_EXAM");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php //echo $Itemid; ?>" <?php //echo 'selected="selected"'; ?>><?php //echo JText::_("GURU_MYCERTIFICATES");?></option>
                          <option value="index.php?option=com_guru&view=guruBuy&Itemid=<?php //echo $Itemid; ?>"><?php //echo JText::_("GURU_CART");?></option>
                         <option value="index.php?option=com_users&task=user.logout&<?php //echo JSession::getFormToken(); ?>=1&Itemid=<?php //echo $Itemid; ?>"><?php //echo JText::_("GURU_LOGIN_OUT");?></option>
                      </select>
                  </div>-->
                <!--END-->
                
                	<div id="myaccount" class="clearfix com-cont-wrap">
                    	<div class="clearfix">
                            <div class="mycourses_page page_title g_cell span7">
                                <h2><?php echo JText::_('GURU_PROFILE');?></h2>
                            </div> 
                        <!--REMOVED BY JOSEPH 31/03/2015-->
                            <!--<div id="g_user_action" class="g_cell span5 g_hide_mobile">
                                <a class="btn btn-success" href="index.php?option=com_guru&view=guruBuy&Itemid=<?php //echo $Itemid; ?>"><img src="components/com_guru/images/cart.gif" alt="<?php echo JText::_("GURU_MY_CART"); ?>"/><u><?php echo JText::_("GURU_CART"); ?></u></a>                               
                          </div>-->
                        <!--END-->
                       </div>  
                		<div class="control-group clearfix">
                			<label class="control-label g_cell span3" for="firstname"><?php echo JText::_("GURU_FIRS_NAME");?>: <span class="guru_error">*</span></label>
                			<div class="controls g_cell span5">
                            	<span>
                					<input type="text" class="inputbox" size="30" id="firstname" name="firstname" value="<?php echo $firstname; ?>" />
                                </span>
                			</div>
                		</div>
                		<div class="control-group clearfix">
                			<label class="control-label g_cell span3" for="lastname"><?php echo JText::_("GURU_LAST_NAME");?>: <span class="guru_error">*</span></label>
                			<div class="controls g_cell span5">
                            	<span>
                					<input type="text" class="inputbox" size="30" id="lastname" name="lastname" value="<?php echo $lastname; ?>"/>
                                </span>    
                			</div>
                		</div>
                    	<div class="control-group clearfix">
                			<label class="control-label g_cell span3" for="company"><?php echo JText::_("GURU_COMPANY");?>: <span>&nbsp;&nbsp;&nbsp;</span></label>
                			<div class="controls g_cell span5">
                            	<span>
                					<input type="text" class="inputbox" size="30" id="company" name="company" value="<?php echo $company; ?>"/>
                                </span>    
                			</div>
                		</div>
                
                <!-- Login section-->
                <div class="myaccount_page page_title">
                	<h2><?php echo JText::_('GURU_LOGIN_INFORMATIONS');?></h2>
                </div>  
                <div class="control-group clearfix">
                	<label class="control-label g_cell span3" for="firstname"><?php echo JText::_("GURU_PROFILE_USERNAME");?>: <span style="color:#FF0000">*</span></label>
                	<div class="controls g_cell span5">
                    	<span>
                			<input type="text" class="inputbox" size="30" id="username" disabled="disabled" name="username"  value="<?php echo $user_username; ?>" />
                        </span>    
                	</div>
                </div>
                <div class="control-group clearfix">
                	<label class="control-label g_cell span3" for="email"><?php echo JText::_("GURU_EMAIL");?>:<span style="color:#FF0000">*</span></label>
               		<div class="controls g_cell span5">
                    	<span>
                			<input type="text" class="inputbox" size="30" id="email" name="email" disabled="disabled" value="<?php echo $user_email; ?>"/>
                        </span>
                	</div>
                    <span style="font-size: 0.8em;" class="g_level_remark">
                        <?php
                        if($user->id == "0"){
                            echo JText::_("DSEMAILNOTE"); 
                        }
                        ?>
                        </span>
                </div>   
                <?php
                if($returnpage != "checkout"){
                ?>
                <div class="control-group clearfix">
                	<label class="control-label g_cell span3" for="password"><?php echo JText::_("GURU_PROFILE_REG_PSW");?>:</label>
                	<div class="controls g_cell span5">
                    	<span>
                			<input type="password" class="inputbox" size="30" id="password" name="password" />
                        </span>
                	</div>
                </div>
                <div class="control-group clearfix">
                	<label class="control-label g_cell span3" for="password_confirm"><?php echo JText::_("GURU_PROFILE_REG_PSW2");?>&nbsp;&nbsp;</label>
                	<div class="controls g_cell span5">
                    	<span>
                			<input type="password" class="inputbox" size="30" id="password_confirm" name="password_confirm"/>
                        </span>    
                	</div>
                </div>	
                <?php
                }
                else{
                ?>
                <input type="hidden" name="password" value=""/>
                <input type="hidden" name="password_confirm" value="" />
                <?php		
                }
                ?>
                 <style>
					  div.guru-content .btn_renew{
						height:20px;!important; 
					  }
				</style>
                <div class="clearfix">
                	<input type="submit" value="<?php echo JText::_("GURU_CONTINUE"); ?>" class="btn btn-primary btn_renew">
                </div>
             </div>
                <input type="hidden" value="138" name="Itemid" />
                <input type="hidden" value="com_guru" name="option" />
                <input type="hidden" value="<?php echo $user_id; ?>" name="id" />
                <input type="hidden" value="saveCustomer" name="task" />
                <input type="hidden" value="<?php echo $returnpage; ?>" name="returnpage" />
                <input type="hidden" value="guruProfile" name="controller" />
                <input type="hidden" value="<?php echo $user_username; ?>" name="username" />
                <input type="hidden" value="<?php echo $user_email; ?>" name="email" />
                </form>
			</div>
		</div> 
	</div>
</div>