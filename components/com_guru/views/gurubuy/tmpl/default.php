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
$document->addScript(JURI::base()."components/com_guru/js/buy.js");
$document->addStyleSheet("components/com_guru/css/guru_style.css");
JHTML::_('behavior.modal');
require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");
$total = "";
$order_id = isset($_SESSION["order_id"]) ? intval($_SESSION["order_id"]) : "";
$promocode = "";

if(isset($_SESSION["promo_code"])){
	$promocode = $_SESSION["promo_code"];
}
$guruModelguruBuy = new guruModelguruBuy();
$configs = $guruModelguruBuy->getConfigs();
$currency = $configs["0"]["currency"];

$currencypos = $configs["0"]["currencypos"];
$character = "GURU_CURRENCY_".$currency;
$action = JRequest::getVar("action", "");

$all_product = array();
if($action == ""){
	if(isset($_SESSION["courses_from_cart"])){
		$all_product = $_SESSION["courses_from_cart"];
	}
}
else{
	$all_product = $_SESSION["renew_courses_from_cart"];
}


$user = JFactory::getUser();
$user_id = $user->id;
if($user_id != "0" && $action == ""){
	$all_product = $this->refreshCoursesFromCart($all_product);
}

$action2 = JRequest::getVar("action2", "");
if($action != "renew"){
	foreach($all_product as $key=>$value){
		$course_details = $guruModelguruBuy->getCourseDetails($value["course_id"]);
		if(is_array($course_details) && count($course_details) == 0){
			unset($_SESSION["courses_from_cart"][$value["course_id"]]);
		}
	}
	$all_product = $_SESSION["courses_from_cart"];
}
$document->setTitle(JText::_("GURU_MY_CART"));

$db = JFactory::getDBO();
$sql = "select courses_ids from #__guru_promos where code="."'".$promocode."'";
$db->setQuery($sql);
$db->query();
$courses_ids_list = $db->loadColumn();
$courses_ids_list2 = implode(",",$courses_ids_list);
$courses_ids_list3 = explode("||",$courses_ids_list2);
$counter = 0;
if(trim($action2) != ""){
	$order_id = JRequest::getVar("order_id", "0");
	$db = JFactory::getDBO();
	$sql = "select form from #__guru_order where id=".intval($order_id);
	$db->setQuery($sql);
	$db->query();
	$form = $db->loadResult();
	echo $form;	
}
elseif(isset($all_product) && count($all_product) > 0){
?>
<div class="g_row">
	<div class="g_cell span12">
		<div>
			<div>
            	<form action="index.php" id="adminForm" name="adminForm" method="post">
                	<div id="my_cart" class="clearfix com-cont-wrap">
                        <div class="clearfix g_hide_mobile">
                            <div class="mycart_page page_title">
                                <h2><?php echo JText::_('GURU_MY_CART');?></h2>
                            </div>
                            <div id="guru_cart" class="g_table_wrap">
                                <table id="g_table_cart" class="table">
                                    <tr class="g_table_header tr1">
                                        <td class="g_cell_1"><?php echo JText::_("GURU_COURSE_NAME"); ?></td>
                                        <td class="g_cell_2"><?php echo JText::_("GURU_MYORDERS_AMOUNT"); ?></td>
                                        <td class="g_cell_3"><?php echo JText::_("GURU_REMOVE"); ?></td>
                                        <td class="g_cell_4"><?php echo JText::_("GURU_TOTAL"); ?></td>
                                    </tr> <!--end table row 1-->   
                                    <?php	
                                        if(isset($all_product) && is_array($all_product) && count($all_product) > 0){
                                            $j = 1;
                                            $all_ids = array();
                                            foreach($all_product as $key=>$value){
                                                $all_ids[] = $key;
                                            }
                                            $all_ids = implode(",", $all_ids);
											$price = 0;
                                            $total_price = 0;


                                            foreach($all_product as $key=>$value){
                                                $course_details = $guruModelguruBuy->getCourseDetails($value["course_id"]);
                                                $course_plans = $guruModelguruBuy->getCoursePlans($value["course_id"], $value["plan"]);

												if(isset($course_details["0"]["name"]) || $course_details["0"]["name"] !=""){
                                    ?>
                                                <tr id="row_<?php echo intval($value["course_id"]); ?>" class="tr2">
                                                    <td class="g_cell_1">
                                                        <ul>
                                                            <?php
                                                            if(isset($course_details["0"]["name"])){
                                                                echo '<li class="guru_product_name clearfix">'.$course_details["0"]["name"].'</li>';
                                                            }
                                                            ?>
                                                            <li class="guru_details"><strong><?php echo JText::_("GURU_SELECT_PLAN"); ?>: 
                                                             <?php

                                                                echo '<select class="selectpicker" onchange="update_cart('.$value["course_id"].', this.value, \''.$all_ids.'\', \''.trim(JText::_($character)).'\')" size="1" class="inputbox" id="plan_id'.$value["course_id"].'" name="plan_id['.$value["course_id"].']">';	
                                                                if(isset($course_plans) && count($course_plans) > 0){
                                                                    $find = FALSE;
																	foreach($course_plans as $key_plam=>$value_plan){
                                                                        $selected = "";												
                                                                        if($value_plan["default"] == "1" && $value["value"] == "" && $value["plan"] == "buy" && !$find){
                                                                            $price = $value_plan["price"];
                                                                            $total_price = $price;
                                                                            $total += $total_price;
                                                                            $selected = ' selected="selected "';
																			$find = TRUE;
                                                                        }
                                                                        elseif($value_plan["default"] == "1" && $value["value"] == "" && $value["plan"] == "renew" && !$find){
                                                                            $price = $value_plan["price"];
                                                                            $total_price = $price;
                                                                            $total += $total_price;
                                                                            $selected = ' selected="selected "';
																			$find = TRUE;
                                                                        }
                                                                        elseif($value_plan["price"] == $value["value"] && !$find){
                                                                            $price = $value_plan["price"];
                                                                            $total_price = $price;
                                                                            $total += $total_price;
                                                                            $selected = ' selected="selected "';
																			$find = TRUE;
                                                                        }
                                                                        if($currencypos == 0){
                                                                            echo '<option value="'.$value_plan["price"].'" '.$selected.' >'.$value_plan["name"].' - '.JText::_($character).' '.$value_plan["price"].'</option>';
                                                                        }

                                                                        else{
                                                                            echo '<option value="'.$value_plan["price"].'" '.$selected.' >'.$value_plan["name"].' - '.$value_plan["price"].' '.JText::_($character).'</option>';
                                                                        }
                                                                    }
                                                                }	
                                                                echo '</select>';
                                                             ?>
                                                            </strong></li>
                                                        </ul>
                                                    </td>
                                                    <td class="g_cell_2">
                                                        <span class="guru_cart_amount" id="cart_item_price<?php echo $value["course_id"]; ?>" >
                                                            <?php 
                                                            if($currencypos == 0){
                                                                echo JText::_($character)." ".$price; 
                                                            }
                                                            else{
                                                                echo $price." ".JText::_($character); 
                                                            }
                                                            ?>
                                                        </span>
                                                    </td>					
                                                    <td class="g_cell_3">
                                                        <?php
                                                            $action_for_request = "buy";
                                                            if(trim($action) == "renew"){
                                                                $action_for_request = "renew";
                                                            }
                                                        ?>
                                                        <input type="image" src="<?php echo JURI::root()."components/com_guru/images/icons/icon_trash.png"; ?>" name="remove" onclick="javascript:removeCourse(<?php echo intval($value["course_id"]); ?>, '<?php echo $all_ids; ?>', '<?php echo $action_for_request; ?>', '<?php echo addslashes(JText::_("GURU_CART_IS_EMPTY")); ?>', '<?php echo JRoute::_("index.php?option=com_guru&view=gurupcategs"); ?>', '<?php echo addslashes(JText::_("GURU_CLICK_HERE_TO_PURCHASE")); ?>', '<?php echo trim(JText::_($character)); ?>');" />

                                                    </td>
                                                    <td class="g_cell_4">
                                                        <ul>
                                                            <li class="guru_cart_amount">
                                                                <span id="cart_item_total<?php echo $value["course_id"]; ?>">
                                                                    <?php 
                                                                    if($currencypos == 0){
                                                                        echo JText::_($character)." ".$total_price;
                                                                    }
                                                                    else{
                                                                        echo $total_price." ".JText::_($character);
                                                                    }
                                                                     ?>
                                                                </span>
                                                            </li>
                                                            <?php 
															//$promo_discount_percourse = $this->getPromoDiscountCourse($total_price);
															if(in_array($value["course_id"],$courses_ids_list3 )){
																$counter +=1;
															 ?>
                                                                 <li class="guru_cart_amount_discount">
                                                                    <span id="guru_cart_amount_discount<?php echo $value["course_id"]; ?>">
                                                                        <?php 
																			echo JText::_("GURU_DISCOUNT").": ";
                                                            				$promo_discount_percourse = $this->getPromoDiscountCourse($total_price); 
																			if($currencypos == 0){
																				echo JText::_($character)." ".$promo_discount_percourse;
																			}
																			else{
																				echo $promo_discount_percourse." ".JText::_($character);
																			}
																			//var_dump($promo_discount_percourse);
                                                                         ?>
                                                                    </span>
                                                                </li>
                                                                 <li class="guru_cart_amount_discount">
                                                                    <span id="guru_cart_amount_discount<?php echo $value["course_id"]; ?>">
                                                                        <?php 
																			echo JText::_("GURU_TOTAL").": ";
																			$total_final = $this->setPromoTest($total_price, $counter);
																			if($currencypos == 0){
																				echo JText::_($character)." ".$total_final;
																			}
																			else{
																				echo $total_final." ".JText::_($character);
																			}
                                                                         ?>
                                                                    </span>
                                                                </li>
                                                          <?php }
														  		else{
																	$total_final = $total_price;
																	$promo_discount_percourse = 0;
																}
														  ?>  
                                                        </ul>	
                                                    </td>												
                                                </tr><!--end table row 2-->					
                                    <?php
												}
												
											$total_finish += $total_final;
											$totall_discount += $promo_discount_percourse;
                                            $j = $j == 1 ? 2 : 1;
                                            }
                                        }
                                    ?>

                                    <tr class="tr3">
                                        <td class="g_cell_1 g_promo_code_box">
                                            <ul>

                                               <span class="guru_details"><?php echo JText::_("GURU_BUY_PROMO"); ?>:</span>

                                                <input type="text" class="guru_textbox g_std_input" value="<?php echo $promocode; ?>" name="promocode" />

                                                <input type="submit" class="btn btn-primary g_no_margine_top" value="<?php echo JText::_("GURU_RECALCULATE"); ?>" name="Submit"  onclick="document.adminForm.task.value='updatecart'" />

                                            </ul>

                                        </td>

                                        <td class="g_cell_2">

                                             <span class="guru_alt"></span>

                                        </td>

                                        

                                        <td class="g_cell_3">

                                            <ul>

                                                <?php

                                               if($counter >0){

                                                ?>

                                                <li class="guru_cart_total"><?php echo JText::_("GURU_DISCOUNT"); ?>:</li>

                                                <?php

                                                }

                                                ?>

                                                <li class="guru_cart_total"><?php echo JText::_("GURU_TOTAL"); ?>:</li>

                                            </ul>

                                        </td>

                                        <td class="g_cell_4">

                                            <ul>

                                                <?php
												
                                                if($counter >0){
													?>
	
													<li class="guru_cart_amount">
														<?php 
															if($currencypos == 0){
																echo JText::_($character)." ".$totall_discount;
															}
															else{
																echo $totall_discount." ".JText::_($character);
															}
															$_SESSION["discount_value"] = $totall_discount;
														?>
													</li>
												<?php
                                                }

                                                ?>

                                                <li class="guru_cart_amount" id="max_total">

                                                <?php
													if($counter >0){
														if($currencypos == 0){
															echo JText::_($character)." ".$total_finish;
														}
														else{
															echo $total_finish." ".JText::_($character);
														}
													}
													else{	
														if(trim($total) != ""){
															if(!isset($_SESSION["max_total"])){
																if($currencypos == 0){
																	echo JText::_($character)." ".$total;
																}
																else{
																	echo $total." ".JText::_($character);
																}	
	
															}
	
															elseif($_SESSION["max_total"] != $total){
	
																$_SESSION["max_total"] = $total;
	
																if($currencypos == 0){
	
																	echo JText::_($character)." ".$total;
	
																}
	
																else{
	
																	echo $total." ".JText::_($character);
	
																}	
	
															}
	
															else{
	
																if($currencypos == 0){
	
																	echo JText::_($character)." ".$_SESSION["max_total"];
	
																}
	
																else{
	
																	echo $_SESSION["max_total"]." ".JText::_($character);
	
																}	
	
															}
	
														}
													}

                                                ?>

                                                </li>

                                            </ul>

                                        </td>

                                    </tr><!--end table row 3-->

                                    

                                                

                                    <tr class="tr4">

                                        <td> 

                                            <input type="button" class="btn btn-primary g_newline2" onclick="window.location='<?php echo JRoute::_("index.php?option=com_guru&view=gurupcategs"); ?>';" value="&lt;&lt; <?php echo JText::_("GURU_CONTINUE_SHOPPING"); ?>" name="continue"/>

                                        </td>

                                        <td></td>

                                        <td></td>

                                        <td id="g_myCart_payment">    

											<?php 
											echo $this->getPlugins(); ?>

                                            <input type="submit" class="btn btn-warning" value="<?php echo JText::_("GURU_CHECKOUT"); ?> &gt;&gt;" name="checkout"/>                                                    

                                        </td>

                                                        

                                    </tr><!--end tr4-->		

                                </table>

                              </div>

                              </div>

                                <input type="hidden" name="option" value="com_guru" />
                                <input type="hidden" name="controller" value="guruBuy" />
                                <input type="hidden" name="task" value="checkout" />
                                <input type="hidden" name="view" value="test" />
                                <input type="hidden" name="order_id" id="order_id" value="<?php echo intval($order_id); ?>"/>

                                <input type="hidden" value="<?php echo $action; ?>" id="action" name="action" />

                             </form>   
                             <?php  $detect = new Mobile_Detect;
							$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
							
							if($deviceType != "computer"){?>
                                    <!-- start mobile version -->
						<form action="index.php" id="adminForm2" name="adminForm2" method="post">
                              <div class="g_mobile">

                              	<div class="mycart_page page_title">

                                    <h2><?php echo JText::_('GURU_MY_CART');?></h2>

                                </div>

                                <?php			

                                    if(isset($all_product) && is_array($all_product) && count($all_product) > 0){

                                        $j = 1;

                                        $all_ids = array();

                                        foreach($all_product as $key=>$value){

                                            $all_ids[] = $key;

                                        }

                                        $all_ids = implode(",", $all_ids);

                                        ?>

                                        <div id="divuniq">

                                        

                                          <?php

											foreach($all_product as $key=>$value){

												$class = "even";

												if($k%2 != 0){

													$class = "odd";

												}

												$price1 = 0;

												$total_price1 = 0;

												$course_details = $guruModelguruBuy->getCourseDetails($value["course_id"]);

												$course_plans = $guruModelguruBuy->getCoursePlans($value["course_id"], $value["plan"]);						

									?>

											

											

												<div id="row1_<?php echo intval($value["course_id"]); ?>" class="g_cart_item">

                                                	<div>

														<ul class="clearfix <?php echo $class; ?> " >

															<?php

															if(isset($course_details["0"]["name"])){

																echo '<li class="guru_product_name clearfix"><div><div class="pull-left g_cart_item_title">'.$course_details["0"]["name"]." ".'</div><div class="pull-right" ><button onclick="javascript:removeCourseB('.intval($value["course_id"]).', \''.$all_ids.'\', \''.$action_for_request.'\', \''.addslashes(JText::_("GURU_CART_IS_EMPTY")).'\', \''.JRoute::_("index.php?option=com_guru&view=gurupcategs").'\', \''.addslashes(JText::_("GURU_CLICK_HERE_TO_PURCHASE")).'\', \''.trim(JText::_($character)).'\');"  class="btn btn-primary" type="button"><i class="icon-trash"></i></button></div></div></li>';

															}

															?>

                                                            <li>

															 <?php

																echo '<select onchange="update_cartb('.$value["course_id"].', this.value, \''.$all_ids.'\', \''.trim(JText::_($character)).'\')" size="1" class="inputbox" id="plan_id'.$value["course_id"].'" name="plan_id['.$value["course_id"].']">';																																		

																if(isset($course_plans) && count($course_plans) > 0){

																	foreach($course_plans as $key_plam=>$value_plan){

																		$selected = "";												

																		if($value_plan["default"] == "1" && $value["value"] == "" && $value["plan"] == "buy"){

																			$price1 = $value_plan["price"];

																			$total_price1 = $price1;

																			$total1 += $total_price1;

																			$selected = ' selected="selected "';

																		}

																		elseif($value_plan["default"] == "1" && $value["value"] == "" && $value["plan"] == "renew"){

																			$price1 = $value_plan["price"];

																			$total_price1 = $price1;

																			$total1 += $total_price1;

																			$selected = ' selected="selected "';

																		}

																		elseif($value_plan["price"] == $value["value"]){

																			$price1 = $value_plan["price"];

																			$total_price1 = $price1;

																			$total1 += $total_price1;

																			$selected = ' selected="selected "';

																		}

																		if($currencypos == 0){

																			echo '<option value="'.$value_plan["price"].'" '.$selected.' >'.$value_plan["name"].' - '.JText::_($character).' '.$value_plan["price"].'</option>';

																		}

																		else{

																			echo '<option value="'.$value_plan["price"].'" '.$selected.' >'.$value_plan["name"].' - '.$value_plan["price"].' '.JText::_($character).'</option>';

																		}

																		

																	}

																}	

																echo '</select>';

															 ?>

                                                             </li>

														</ul>

                                                        </div>

														<div style="display:none;" id="cart_item_totalb<?php echo $value["course_id"]; ?>">

															<?php 

															if($currencypos == 0){

																echo JText::_($character)." ".$total_price1;

															}

															else{

																echo $total_price1." ".JText::_($character);

															}

															 ?>

															 

														</div>

													 </div>

									<?php	
											$total_final = $this->setPromoTest($total_price1, $counter);
											$promo_discount_percourse1 = $this->getPromoDiscountCourse($total_price1);
											$totall_discount1 += $promo_discount_percourse1;
											$total_finishp += $total_final;
											$j = $j == 1 ? 2 : 1;

											$k++;

											}
                                    }
									$_SESSION["discount_value"] = $totall_discount1;

                                ?>
                                  <div>
                                 <table id="g_cource-price">

                                   <tr>

                                     <td style="font-weight:normal" class="guru_cart_totalb"><?php echo JText::_("GURU_SUB_TOTAL"); ?>:</td>

                                     <td class="guru_cart_amount" id="max_totalb1" style="font-weight:normal">

                                     <?php

                                        

                                                if(trim($total) != ""){

                                                    if(!isset($_SESSION["max_totalb1"])){

                                                        if($currencypos == 0){

                                                            echo JText::_($character)." ".$total1;

                                                        }

                                                        else{

                                                            echo $total1." ".JText::_($character);

                                                        }	

                                                    }

                                                    elseif($_SESSION["max_totalb1"] != $total1){

                                                        $_SESSION["max_totalb1"] = $total1;

                                                        if($currencypos == 0){

                                                            echo JText::_($character)." ".$total1;

                                                        }

                                                        else{

                                                            echo $total1." ".JText::_($character);

                                                        }	

                                                    }

                                                    else{

                                                        if($currencypos == 0){

                                                            echo JText::_($character)." ".$_SESSION["max_totalb1"];

                                                        }

                                                        else{

                                                            echo $_SESSION["max_totalb1"]." ".JText::_($character);

                                                        }	

                                                    }

                                                }

                                     ?>
                                     </td>
                                     </tr>
                                     <tr>
                                     <?php if(isset($_SESSION["discount_value"]) && trim($_SESSION["discount_value"]) != ""){$cls = "";} else{$cls = 'g_price-no-val';}?>
                                     <td style="font-weight:normal" class="guru_cart_totalb <?php echo $cls;?>"><?php if(isset($_SESSION["discount_value"]) && trim($_SESSION["discount_value"]) != ""){ echo JText::_("GURU_DISCOUNT").":"; }?></td>
                                     <td class="guru_cart_amount"><?php if(isset($_SESSION["discount_value"]) && trim($_SESSION["discount_value"]) != ""){ echo trim($_SESSION["discount_value"]); }?></td>

                                    </tr>
                                    <tr>	
                                            <td class="guru_cart_totalb"><?php echo JText::_("GURU_TOTAL"); ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td class="guru_cart_amount" id="max_totalb" style="color:#336600;">

                                            <?php

                                                if($counter>0){
													if($currencypos == 0){

                                                            echo JText::_($character)." ".$total_finishp;

                                                        }

                                                        else{

                                                            echo $total_finishp." ".JText::_($character);

                                                        }	
												}
												else{
	
													if(trim($total) != ""){
	
														if(!isset($_SESSION["max_totalb"])){
	
															if($currencypos == 0){
	
																echo JText::_($character)." ".$total;
	
															}
	
															else{
	
																echo $total." ".JText::_($character);
	
															}	
	
														}
	
														elseif($_SESSION["max_totalb"] != $total){
	
															$_SESSION["max_totalb"] = $total;
	
															if($currencypos == 0){
	
																echo JText::_($character)." ".$total;
	
															}
	
															else{
	
																echo $total." ".JText::_($character);
	
															}	
	
														}
	
														else{
	
															if($currencypos == 0){
	
																echo JText::_($character)." ".$_SESSION["max_totalb"];
	
															}
	
															else{
	
																echo $_SESSION["max_totalb"]." ".JText::_($character);
	
															}	
	
														}
	
													}
												}

                                            ?>

                                            </td>

                                        </tr>

                                 </table>

                                  <div class="pagination-centered">

                                        <span class="guru_details"><?php echo JText::_("GURU_BUY_PROMO_B"); ?>:</span>

                                        <div class="input-append" id="g_promo_code">

                                            <div style="width:100%">

                                                <input class="g_std_input" name="promocode" class="span2" value="<?php echo $promocode; ?>" id="appendedInputButton" type="text">

                                                <input type="submit" class="btn btn-primary g_no_margine_top" value="<?php echo JText::_("GURU_RECALCULATE"); ?>" name="Submit"  onclick="document.adminForm2.task.value='updatecart'" />

                                            </div>

                                        </div>

                                    </div>

                                 </div>

                                 <div> 

                                        <div class="pagination-right">

                                            <?php echo $this->getPluginsB(); ?>

                                        </div>

                                        <div  class="pagination-right" style="padding-right:5px;">
                                            <input type="submit" class="btn btn-warning" value="<?php echo JText::_("GURU_CHECKOUT"); ?> &gt;&gt;" name="checkout"/>
                                        </div>

                                    </div>
                              </div>
                              <!-- end mobile version-->
                       </div>  
                       </div>
                        <input type="hidden" name="option" value="com_guru" />
                        <input type="hidden" name="controller" value="guruBuy" />
                        <input type="hidden" name="task" value="checkout" />
                        <input type="hidden" name="view" value="test" />
                        <input type="hidden" name="order_id" id="order_id" value="<?php echo intval($order_id); ?>"/>
                        <input type="hidden" value="<?php echo $action; ?>" id="action" name="action" />
                    </form>  
                    <?php
                    }
					?>                        
            </div>
        </div>
    </div>
</div>      
<?php
}
else{
	echo JText::_("GURU_CART_IS_EMPTY").", ".'<a href="'.JRoute::_('index.php?option=com_guru&view=gurupcategs').'">'.JText::_("GURU_CLICK_HERE_TO_PURCHASE").'</a>';

}
?>	