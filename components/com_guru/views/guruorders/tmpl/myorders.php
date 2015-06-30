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
	$document = JFactory::getDocument();
    $document->addStyleSheet("components/com_guru/css/guru_style.css");
	$document->setMetaData( 'viewport', 'width=device-width, initial-scale=1.0' );
	//$document->addStyleSheet("components/com_guru/css/tabs.css");
	$document->setTitle(trim(JText::_('GURU_MYORDERS_MYORDERS')));
	$guruModelguruOrder = new guruModelguruOrder();
	JHTML::_('behavior.tooltip');


	$k = 0;
	$myorders = $this->myorders;
	$Itemid = JRequest::getVar("Itemid", "0");
	$config = $this->getConfigSettings();
	$datetype = $this->datetype;
	
	$return_url = base64_encode("index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=".intval(@$itemid));
	
	if($config->gurujomsocialprofilestudent == 1){
		$link = "index.php?option=com_community&view=profile&task=edit&Itemid=".$Itemid;
	}
	else{
		$link = "index.php?option=com_guru&view=guruProfile&task=edit&Itemid=".$Itemid;
	}

	if(isset($myorders) && is_array($myorders) && count($myorders) > 0){
		$all_plans = $this->getPlans();
?>
 <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
var modalWindow = {
	parent:"body",
	windowId:null,
	content:null,
	width:null,
	height:null,
	close:function()
	{
		jQuery(".modal-window").remove();
		jQuery(".modal-overlay").remove();
	},
	open:function()
	{
		var modal = "";
		modal += "<div class=\"modal-overlay\"></div>";
		modal += "<div id=\"" + this.windowId + "\" class=\"modal-window modal g_modal \">";
		modal += this.content;
		modal += "</div>";	
		jQuery(this.parent).append(modal);


		jQuery(".modal-window").append("<a class=\"close-window\"></a>");
		jQuery(".close-window").click(function(){modalWindow.close();});
		jQuery(".modal-overlay").click(function(){modalWindow.close();});
	}
};




  var openMyModal = function(source)  
{  
	modalWindow.windowId = "myModal";  
	modalWindow.content = "<iframe id='g_order_pop' class='g_leesson_popup' src='" + source + "'></iframe>";  
	modalWindow.open();  
};  
</script>

<div class="g_row">
	<div class="g_cell span12">
		<div>
			<div>
                <form action="index.php" name="adminForm" method="post">
                   <!--  <div id="guru_menubar" class="clearfix g_toolbar guru_menubar">
                        <ul>
                            <li id="my_account"><a href="<?php //echo $link; ?>"><i class="icon-user"></i><?php //echo JText::_("GURU_MY_ACCOUNT"); ?></a></li>
                            <li id="my_courses"><a href="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php //echo $Itemid; ?>"><i class="icon-eye-open"></i><?php //echo JText::_("GURU_MYCOURSES"); ?></a></li>
                            <li id="my_orders"><a class="g_toolbar_active" href="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php //echo $Itemid; ?>"><i class="icon-cart"></i><?php //echo JText::_("GURU_MYORDERS_MYORDERS"); ?></a></li>
                            <li id="my_quizzes"><a href="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php //echo $Itemid; ?>"><i class="icon-question-sign"></i><?php //echo JText::_("GURU_QUIZZ_FINAL_EXAM"); ?></a></li>
                            <li id="my_certificates"><a href="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php //echo $Itemid; ?>"><i class="icon-star"></i><?php //echo JText::_("GURU_MYCERTIFICATES"); ?></a></li>
                            
                            <li class="g_hide_mobile logout-btn" id="g_logout">
                                <a href="index.php?option=com_users&task=user.logout&<?php //echo JSession::getFormToken(); ?>=1&Itemid=<?php //cho $Itemid; ?>&return=<?php //echo $return_url;?>">
                                    <i class="fa fa-sign-out"></i>
                                </a>
							</li>
                            
                        </ul>
                    </div>
                    <div id="guru_menubar_mobile" class="g_mobile guru_menubar g_select">
                        <select name="menuboostrap" class="g_select" id="menuboostrap" onchange="window.open(this.value, '_self');" >
                         <option value="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_MYCOURSES");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_QUIZZ_FINAL_EXAM");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_MYCERTIFICATES");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_MYORDERS_MYORDERS");?></option>
                          <option value="index.php?option=com_guru&view=guruBuy&Itemid=<?php echo $Itemid; ?>"><?php echo JText::_("GURU_CART");?></option>
                         <option value="index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken(); ?>=1&Itemid=<?php echo $Itemid; ?>"><?php echo JText::_("GURU_LOGIN_OUT");?></option>
                      </select>
                   </div> -->
                    
                     <!-- <div id="myorders" class="clearfix com-cont-wrap">
                        <div class="clearfix">
                            <div class="mycourses_page page_title g_cell span7">
                                <h2><?php //echo JText::_('GURU_MYORDERS_MYORDERS');?></h2>
                            </div> 
                            <div id="g_user_action" class="g_cell span5 g_hide_mobile">
                                <a class="btn btn-success" href="index.php?option=com_guru&view=guruBuy&Itemid=<?php echo $Itemid; ?>"><img src="components/com_guru/images/cart.gif" alt="<?php echo JText::_("GURU_MY_CART"); ?>"/><u><?php echo JText::_("GURU_CART"); ?></u></a>
                          	</div>
                        </div>   -->
                         
                        <!-- Start Search -->
                        <div class="clearfix">
                            <div class="g_cell span8">
                               <div class="input-group g_search">
                                  <input type="text" class="form-control inputbox" name="search"  id="search" value="<?php if(isset($_POST['search'])) echo $_POST['search'];?>" >
                                  <span class="input-group-btn g_hide_mobile">
                                    <button class="btn btn-primary" type="submit"><?php echo JText::_("GURU_SEARCH"); ?></button>
                                  </span>
                                </div><!-- /input-group -->
                            </div>
                        </div>
                        <!-- End Search -->
                        <div class="clerafix">
                            <div class="g_table_wrap g_hide_mobile">
                            	<table class="table table-striped">
                                    <tr class="g_table_header">
                                        <th class="g_cell_1"><?php echo JText::_("GURU_COURSES_DETAILS"); ?></th>
                                        <th class="g_cell_2"><?php echo JText::_("GURU_STATUS"); ?></th>
                                        <th class="g_cell_3"><?php echo JText::_("GURU_INVOICE"); ?></th>
                                    </tr>
                                        <?php
                                            foreach($myorders as $key=>$order){
                                                $class = "odd";
                                                if($k%2 != 0){
                                                    $class = "even";
                                                }
                                                $id = $order["id"];								
                                                $rec_link = JRoute::_("index.php?option=com_guru&view=guruOrders&task=showrec&orderid=".$id."&Itemid=".$Itemid);
                                        ?>
                                                
                                                        <?php
                                                            $courses = $order["courses"];
                                                            $courses = explode("|", $courses);
                                                            $date = $order["order_date"];
															
                                                            foreach($courses as $course){
                                                                $course_id_array = explode("-", $course);
                                                                $course_id = $course_id_array["0"];
                                                                $course_name = $guruModelguruOrder->getCourseName($course_id);
                                                                if($course_name != NULL){
                                                                    $alias = JFilterOutput::stringURLSafe($course_name);
                                                                    $course_link = JRoute::_("index.php?option=com_guru&view=guruPrograms&task=view&cid=".intval($course_id)."-".$alias."&Itemid=".$Itemid);
                                                                    $course_link = '<a href="'.$course_link.'">'.$course_name.'</a>';
                                                                    $plan = "";
                                                                    
                                                                    if(isset($course_id_array["1"]) && trim($course_id_array["2"]) != ""){
                                                                        if(isset($all_plans[trim(@$course_id_array["2"])]["term"]) && @$all_plans[trim(@$course_id["2"])]["term"] != "Unlimited"){
                                                                            $period = $all_plans[trim(@$course_id_array["2"])]["period"];
                                                                            if($all_plans[trim($course_id_array["2"])]["term"] <= 1 && (substr($period, -1) == "s")){
                                                                                $period = substr($period, 0, -1);
                                                                            }															
                                                                            if($all_plans[trim($course_id_array["2"])]["term"] == "0"){
                                                                                $plan = " - ".JText::_("GURU_UNLIMITED");
                                                                            }
                                                                            else{
                                                                                $plan = " - ".$all_plans[trim($course_id_array["2"])]["term"]." ".$period;
                                                                            }
                                                                        }
                                                                        else{
                                                                            $plan = " - ".JText::_("GURU_UNLIMITED");
                                                                        }
                                                                    }
                                                                }
																?>
                                                                <tr>
                                                                	<td class="guru_product_name g_cell_1"><?php echo $course_link.$plan ;?>	
                                                                <?php	
                                                                 $currency = $order["currency"];
                                                                $simbol = JText::_("GURU_CURRENCY_".$currency);
                                                                $payed = $order["amount"];
                                                                if(isset($order["amount_paid"]) && trim($order["amount_paid"]) != "" && trim($order["amount_paid"]) != "0"){
                                                                    $payed = $order["amount_paid"];
                                                                }
                                                                $edit_date = "";
                                                                if($config->hour_format == 24){
                                                                    //$edit_date = date('m-d-Y | H:i' , strtotime($date));
                                                                    
                                                                    $format = "m-d-Y";
                                                                    switch($datetype){
                                                                        case "d-m-Y H:i:s": $format = "d-m-Y H:i";
                                                                              break;
                                                                        case "d/m/Y H:i:s": $format = "d/m/Y H:i"; 
                                                                              break;
                                                                        case "m-d-Y H:i:s": $format = "m-d-Y H:i"; 
                                                                              break;
                                                                        case "m/d/Y H:i:s": $format = "m/d/Y H:i"; 
                                                                              break;
                                                                        case "Y-m-d H:i:s": $format = "Y-m-d H:i"; 
                                                                              break;
                                                                        case "Y/m/d H:i:s": $format = "Y/m/d H:i"; 
                                                                              break;
                                                                        case "d-m-Y": $format = "d-m-Y"; 
                                                                              break;
                                                                        case "d/m/Y": $format = "d/m/Y"; 
                                                                              break;
                                                                        case "m-d-Y": $format = "m-d-Y"; 
                                                                              break;
                                                                        case "m/d/Y": $format = "m/d/Y"; 
                                                                              break;
                                                                        case "Y-m-d": $format = "Y-m-d"; 
                                                                              break;
                                                                        case "Y/m/d": $format = "Y/m/d";		
                                                                              break;  	  	  	  	  	  	  	  	  	  
                                                                    }
                                                                    $edit_date = JHTML::_('date', strtotime($date), $format);												
                                                                }
                                                                elseif($config->hour_format == 12){
                                                                    //$edit_date = date('m-d-Y | h:i A' , strtotime($date));
                                                                    $format = " m-d-Y ";
                                                                    switch($datetype){
                                                                        case "d-m-Y H:i:s": $format = "d-m-Y h:i A";
                                                                              break;
                                                                        case "d/m/Y H:i:s": $format = "d/m/Y h:i A"; 
                                                                              break;
                                                                        case "m-d-Y H:i:s": $format = "m-d-Y h:i A"; 
                                                                              break;
                                                                        case "m/d/Y H:i:s": $format = "m/d/Y h:i A"; 
                                                                              break;
                                                                        case "Y-m-d H:i:s": $format = "Y-m-d h:i A"; 
                                                                              break;
                                                                        case "Y/m/d H:i:s": $format = "Y/m/d h:i A"; 
                                                                              break;
                                                                        case "d-m-Y": $format = "d-m-Y A"; 
                                                                              break;
                                                                        case "d/m/Y": $format = "d/m/Y A"; 
                                                                              break;
                                                                        case "m-d-Y": $format = "m-d-Y A"; 
                                                                              break;
                                                                        case "m/d/Y": $format = "m/d/Y A"; 
                                                                              break;
                                                                        case "Y-m-d": $format = "Y-m-d A"; 
                                                                              break;
                                                                        case "Y/m/d": $format = "Y/m/d A";	
                                                                              break;	  	  	  	  	  	  	  	  	  	  
                                                                    }
                                                                    $edit_date = JHTML::_('date', strtotime($date), $format);												
                                                                }	
                                                                ?>
                                                                 <br/><?php echo JText::_("GURU_PURCHASED_ON"); ?>:<?php echo $edit_date; ?>
                                                                 
                                                              </td>
                                                               <td class="g_cell_2">
                                                       			<?php 
																	if($order["status"] == 'Pending'){
																		echo '<span class="editlinktip hasTip" style="color:#0099FF; font-size:12px;" title="'.JText::_("GURU_ORDERS_STATUS_TOOLTIP").'">'.$order["status"].'</span>';
																	}
																	else{
																		echo $order["status"];
																	}
																?>
                                                               </td>
                                                                 
																 <td class="g_cell_3">
                                                        <a class="btn btn-warning" href="#" onclick="openMyModal('<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruOrders&task=showrec&orderid=<?php echo $id;?>&Itemid=<?php echo $Itemid; ?>&tmpl=component'); return false;"><?php echo JText::_("GURU_VIEW_ORDER");?></a>
                                                                </td>
                                                            </tr>
                                                            <?php	
                                                            }
                                                           
                                                        ?>											
                                                   
                                                   
                                        <?php	
                                                $k++;
                                            }
                                        ?>
                                        </table>
                                       
                                </div> 
                                 <!--- start mobile view-->
                                                    
                                        <div class="g_mobile g_table_wrap">
                                            <table class="table table-striped">
                                            	<tr class="g_table_header">
                                                    <td class="g_cell_1"><?php echo JText::_("GURU_DAYS_NAME"); ?></td>
                                                    <td class="g_cell_3"><?php echo JText::_("GURU_INVOICE"); ?></td>
                                                </tr>
                                                <?php
                                                 foreach($myorders as $key=>$order){
                                                    $class = "odd";
                                                    if($k%2 != 0){
                                                        $class = "even";
                                                    }
                                                    $id = $order["id"];								
                                                    $rec_link = JRoute::_("index.php?option=com_guru&view=guruOrders&task=showrec&orderid=".$id."&Itemid=".$Itemid);
                                                    $courses = $order["courses"];
                                                    $courses = explode("|", $courses);
                                                    $date = $order["order_date"];
                                                    
                                                    foreach($courses as $course){
                                                        $course_id_array = explode("-", $course);
                                                        $course_id = $course_id_array["0"];
                                                        $course_name = $guruModelguruOrder->getCourseName($course_id);
                                                        if($course_name != NULL){
                                                            $alias = JFilterOutput::stringURLSafe($course_name);
                                                            $course_link = JRoute::_("index.php?option=com_guru&view=guruPrograms&task=view&cid=".intval($course_id)."-".$alias."&Itemid=".$Itemid);
                                                            $course_link = '<a href="'.$course_link.'">'.$course_name.'</a>';
                                                            $plan = "";
                                                            
                                                            if(isset($course_id_array["1"]) && trim($course_id_array["2"]) != ""){
                                                                if(isset($all_plans[trim(@$course_id_array["2"])]["term"]) && @$all_plans[trim(@$course_id["2"])]["term"] != "Unlimited"){
                                                                    $period = $all_plans[trim(@$course_id_array["2"])]["period"];
                                                                    if($all_plans[trim($course_id_array["2"])]["term"] <= 1 && (substr($period, -1) == "s")){
                                                                        $period = substr($period, 0, -1);
                                                                    }															
                                                                    if($all_plans[trim($course_id_array["2"])]["term"] == "0"){
                                                                        $plan = " - ".JText::_("GURU_UNLIMITED");
                                                                    }
                                                                    else{
                                                                        $plan = " - ".$all_plans[trim($course_id_array["2"])]["term"]." ".$period;
                                                                    }
                                                                }
                                                                else{
                                                                    $plan = " - ".JText::_("GURU_UNLIMITED");
                                                                }
                                                            }
                                                        }
														?>
														
														  <tr>
                                                            <td>
                                                                <?php echo $course_link; ?><br/>
                                                                <?php 
                                                                $plan_boostrap = explode("-",$plan);
                                                                echo $plan_boostrap[1];
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-warning" href="#" onclick="openMyModal('<?php echo JURI::root(); ?>index.php?option=com_guru&view=guruOrders&task=showrec&orderid=<?php echo $id;?>&Itemid=<?php echo $Itemid; ?>&tmpl=component'); return false;"><?php echo JText::_("GURU_VIEW_ORDER");?></a>
                                                            </td>
                                                     </tr>
                                                     <?php             
                                                    }
                                                    $k++;
                                                  }
                                                    ?>
                                         </table>   
                                    
                                            <?php
                                                ?>
                                    
                                 </div>   
     <!--- end  mobile view-->        
                </div>
           </div> 
                                        						
                              	
                    <input type="hidden" name="option" value="com_guru" />
                    <input type="hidden" name="boxchecked" value="0" />
                    <input type="hidden" name="controller" value="guruOrders" />
                    <input type="hidden" name="view" value="guruOrders" />
                    <input type="hidden" name="task" value="myorders" />
                </form>
            </div>
           </div>
          </div>
        </div> 
        
        
        
<?php		
	}
	else{
	?>
<div class="g_row">
	<div class="g_cell span12">
		<div>
			<div>
                <form action="index.php" name="adminForm" method="post">
                    <div id="guru_menubar" class="g_toolbar guru_menubar">
                        <ul>
                            <li id="my_account"><a href="<?php echo $link; ?>"><i class="icon-user"></i><?php echo JText::_("GURU_MY_ACCOUNT"); ?></a></li>
                            <li id="my_courses"><a href="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php echo $Itemid; ?>"><i class="icon-eye-open"></i><?php echo JText::_("GURU_MYCOURSES"); ?></a></li>
                            <li id="my_orders"><a class="g_toolbar_active" href="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php echo $Itemid; ?>"><i class="icon-cart"></i><?php echo JText::_("GURU_MYORDERS_MYORDERS"); ?></a></li>
                            <li id="my_quizzes"><a href="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php echo $Itemid; ?>"><i class="icon-question-sign"></i><?php echo JText::_("GURU_QUIZZ_FINAL_EXAM"); ?></a></li>
                            <li id="my_certificates"><a href="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php echo $Itemid; ?>"><i class="icon-star"></i><?php echo JText::_("GURU_MYCERTIFICATES"); ?></a></li>
                             <li class="g_hide_mobile logout-btn" id="g_logout">
                                    <a href="index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken(); ?>=1&Itemid=<?php echo $Itemid; ?>&return=<?php echo $return_url;?>">
                                        <i class="fa fa-sign-out"></i>
                                    </a>
                                </li>
                        </ul>
                    </div>
                    <div id="guru_menubar_mobile" class="g_mobile guru_menubar g_select">
                        <select name="menuboostrap" class="g_select" id="menuboostrap" onchange="window.open(this.value, '_self');" >
                         <option value="index.php?option=com_guru&view=guruorders&layout=mycourses&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_MYCOURSES");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=myquizandfexam&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_QUIZZ_FINAL_EXAM");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=mycertificates&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_MYCERTIFICATES");?></option>
                         <option value="index.php?option=com_guru&view=guruorders&layout=myorders&Itemid=<?php echo $Itemid; ?>" <?php echo 'selected="selected"'; ?>><?php echo JText::_("GURU_MYORDERS_MYORDERS");?></option>
                          <option value="index.php?option=com_guru&view=guruBuy&Itemid=<?php echo $Itemid; ?>"><?php echo JText::_("GURU_CART");?></option>
                         <option value="index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken(); ?>=1&Itemid=<?php echo $Itemid; ?>"><?php echo JText::_("GURU_LOGIN_OUT");?></option>
                      </select>
                   </div>
                    
                     <div id="myorders" class="clearfix com-cont-wrap">
                        <div class="clearfix">
                            <div class="mycourses_page page_title g_cell span7">
                                <h2><?php echo JText::_('GURU_MYORDERS_MYORDERS');?></h2>
                            </div> 
                            <div id="g_user_action" class="g_cell span5 g_hide_mobile">
                                <a class="btn btn-success" href="index.php?option=com_guru&view=guruBuy&Itemid=<?php echo $Itemid; ?>"><img src="components/com_guru/images/cart.gif" alt="<?php echo JText::_("GURU_MY_CART"); ?>"/><u><?php echo JText::_("GURU_CART"); ?></u></a>
                          	</div>
                        </div>  
                         
                        <!-- Start Search -->
                        <div class="clearfix">
                            <div class="g_cell span8">
                               <div class="input-group g_search">
                                  <input type="text" class="form-control inputbox" name="search"  id="search" value="<?php if(isset($_POST['search'])) echo $_POST['search'];?>" >
                                  <span class="input-group-btn g_hide_mobile">
                                    <button class="btn btn-primary" type="submit"><?php echo JText::_("GURU_SEARCH"); ?></button>
                                  </span>
                                </div><!-- /input-group -->
                            </div>
                        </div>
                        <!-- End Search -->
    <?php
?>

                <?php echo JText::_("GURU_NO_ORDERS"); ?>, <a href="<?php echo JRoute::_("index.php?option=com_guru&view=gurupcategs&Itemid=".intval($Itemid)); ?>"><?php echo JText::_("GURU_ORDER_COURSE"); ?></a>
                </div>
                <input type="hidden" name="option" value="com_guru" />
                    <input type="hidden" name="boxchecked" value="0" />
                    <input type="hidden" name="controller" value="guruOrders" />
                    <input type="hidden" name="view" value="guruOrders" />
                    <input type="hidden" name="task" value="myorders" />
                </form>
            </div> 
         </div>
        </div>   
     </div>        
<?php
	}
?> 