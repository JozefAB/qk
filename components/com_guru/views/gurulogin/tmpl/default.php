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
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_guru/css/guru_style.css");
$document->setMetaData( 'viewport', 'width=device-width, initial-scale=1.0' );
require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');	
$document->setTitle(JText::_("GURU_ALREADY_MEMBER"));
$returnpageoR = JRequest::getVar("returnpage", "");
if($returnpageoR == 'authorprofile' || $returnpageoR == "authormymedia" || $returnpageoR == "authormymediacategories" || $returnpageoR == "mystudents" || $returnpageoR == "authormycourses"){
	$returnpageo = 'authorprofile';
}
else{
	$returnpageo = $returnpageoR;
}

?>
<div class="login_page page_title">
	<h1><?php echo JText::_("GURU_ALREADY_MEMBER");?></h1>
</div>

<div class="login_row_guru  g_row clearfix">
	<div class="login_cell_guru g_cell span6">
    	<div>
        	<div class="no-padding">
        		<div>
                	<div id="g_login_title" class="g_login_title">
                    	<h2> <?php echo JText::_("GURU_HAVE_ACCOUNT"); ?></h2>
                   	</div>
                        <form name="login" method="post" class="clearfix">
                            <div class="lo_cont_wraper">
                                <div class="control-group">
                                    <label class="pull-left control-label g_cell span4" for="username"><?php echo JText::_("GURU_PROFILE_USERNAME");?>: <span class="guru_error">*</span></label>
                                    <div class="controls g_cell span8">
                                        <input type="text" class="inputbox" size="15" id="username" name="username" placeholder="Username" />
                                    </div>
                                </div>
                                
                                <div class="control-group">
                                    <label class="pull-left control-label g_cell span4" for="passwd"><?php echo JText::_("GURU_PROFILE_PSW");?>: <span class="guru_error">*</span></label>
                                    <div class="controls g_cell span8">
                                    <input type="password" class="inputbox" size="15" id="passwd" name="passwd" placeholder="Password" />
                                    </div>
                                </div>
                                <div class="control-group">
                                	<div class="g_cell span4 g_offset"></div>
                                    <div class="controls g_cell span8">
                                        <label class="checkbox span8">
                                            <input type="checkbox" name="rememeber" value="1" /> <?php echo JText::_("GURU_PROFILE_REMEMBER_ME");?>	
                                        </label>
                                    </div>
                                    <div class="g_cell span4 g_offset"></div>
                                    <div class="g_cell span8">
                                        <input type="submit" class="btn btn-primary" name="submit" value="<?php echo JText::_("GURU_LOGIN_AND_CONTINUE"); ?>" />
                                    </div> 
                                </div>
                              </div>  
                       
                        <input type="hidden" name="option" value="com_guru" />
                        <input type="hidden" name="controller" value="guruLogin" />
                        <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
                        <input type="hidden" name="task" value="log_in_user" />
                        <input type="hidden" name="returnpage" value="<?php echo JRequest::getVar("returnpage", ""); ?>" />	
                        <input type="hidden" name="cid" value="<?php echo JRequest::getVar("cid", "0"); ?>" />	
                        </form>
               </div>
            </div>
        </div>  
     </div>  

	 <div class="login_cell_guru g_cell span6">
     	<div>
        	<div class="no-padding">
        		<div>
                	<div id="g_registration_title" class="g_login_title">
                    	<h2><?php echo JText::_("GURU_CREATE_NEW_ACCOUNT"); ?></h2>
                    </div>
                     <?php if($returnpageo != "authorprofile"){ ?>
                             <form name="register" method="post" class="clearfix">
                                <input type="hidden" name="option" value="com_guru" />
                                <input type="hidden" name="controller" value="guruLogin" />
                                <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar("Itemid", "0"); ?>" />
                                <input type="hidden" name="task" value="register" />
                                <input type="hidden" name="returnpage" value="<?php echo JRequest::getVar("returnpage", ""); ?>" />	
                                <input type="hidden" name="cid" value="<?php echo JRequest::getVar("cid", "0"); ?>" />  
                                  <!--start case of student rgistration -->
                                <div class="lo_cont_wraper">
                                    <span>
                                        <?php echo JText::_("GURU_REGISTRATION_EASY_STUDENT"); ?>
                                    </span>
                                </div>   
                                <div>
                                    <input type="submit" class="btn btn-primary" value="<?php echo JText::_("GURU_REGISTER_AS_STUDENT");?>" />
                                </div>
                               <!-- end of student registration -->
                         </form>
                     <?php }
					 	   elseif($returnpageo == "authorprofile"){?>
					 	   	<form name="register" method="post" class="clearfix">
								<input type="hidden" name="option" value="com_guru" />
								<input type="hidden" name="controller" value="guruAuthor" />
								<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar("Itemid", "0"); ?>" />
								<input type="hidden" name="task" value="authorregister" />
								<input type="hidden" name="returnpage" value="<?php echo JRequest::getVar("returnpage", ""); ?>" />	
								<input type="hidden" name="cid" value="<?php echo JRequest::getVar("cid", "0"); ?>" />  
							   <!-- start case of teacher registration -->
								<div class="lo_cont_wraper">
									<span>
										<?php echo JText::_("GURU_REGISTRATION_EASY_TEACHER"); ?>
									</span>
								</div>   
								<div>
									<input type="submit" class="btn btn-primary" value="<?php echo JText::_("GURU_REGISTER_AS_TEACHER");?>" />
								</div>
							   <!-- end of teacher registration --> 
						</form>
                        <?php
						   }
					 ?>
                   
                </div>
             </div>
          </div>         
     </div>   
</div>
<script>
	window.onload = equalHeight('login_cell_guru');
</script>