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
require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'helper.php');

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_guru/css/guru_style.css");
$order = $this->order["0"];
$promocodeid = $order["promocodeid"];
$discount_details = array();
$currency = $order["currency"];
$character = "GURU_CURRENCY_".$currency;
$guruModelguruOrder = new guruModelguruOrder();
$db = JFactory::getDBO();
$sql = "select invoice_issued_by from #__guru_config where id=1";
$db->setQuery($sql);
$db->query();
$invoice_issued_by = $db->loadResult();
?>

<style>
	@media print {
    * { display: block; !important }
}
@media (min-width: 900px) {
	.g_hide_mobile {
		display: block !important;
	}
}
</style>
<script>
function printDiv(contentpane) {
     var printContents = document.getElementById("contentpane").innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
<?php
if($promocodeid != "0"){
	$courses_list_promo = $guruModelguruOrder->getCoursesPromo($promocodeid);
}

$courses_array = explode("|",$courses_list_promo["0"]);
$courses_array = array_values(array_filter($courses_array));

if(empty($order)){
	$Itemid = JRequest::getVar("Itemid", "0");
	$app = JFactory::getApplication('site');
    $app->redirect(JRoute::_("index.php?option=com_guru&view=guruorders&layout=myorders&"."&Itemid=".$Itemid, false));
}
else{
	$db = JFactory::getDBO();
	$sql = "SELECT currencypos  from #__guru_config where id =1";
	$db->setQuery($sql);
	$db->query();
	$currencypos = $db->loadResult();
	if($currencypos == 0){
		$discount = JText::_($character)." 0.00";
	}
	else{
		$discount = "0.00 ".JText::_($character);
	}
	
	
	if($promocodeid != "0"){
		$discount_details = $guruModelguruOrder->getDiscountDetails($promocodeid);
		if($discount_details["0"]["typediscount"] == "0"){
			if($currencypos == 0){
				$discount = JText::_($character)." ".$discount_details["0"]["discount"];
			}
			else{
				$discount = $discount_details["0"]["discount"]." ".JText::_($character);
			}
		}
		else{
			$discount = $discount_details["0"]["discount"]."%";
		}
	}
	
	
	?>
	<div class="clearfix">
		<div class="g_cell span12 g_hide_mobile_o">
			<div>
				<div>
					<?php
					if($this->show === TRUE){
	?>
		<button type="button" onclick="javascript:printDiv('contentpane'); return false;" class="g_print btn btn-primary"> <img src="<?php echo JUri::base()."components/com_guru/images/print.png"; ?>" alt="Print" /><?php echo JText::_("GURU_PRINT"); ?></button>
	<?php
	}?>
					<form method="post" name="adminForm" action="index.php">
						<div id="contentpane">
					
										 <div class="g_table_wrap">
											<table cellspacing="0" cellpadding="3" bordercolor="#cccccc" border="1" width="100%" style="border-collapse: collapse;" class="adminlist">
												<caption class="componentheading"><?php echo JText::_("GURU_INVOICE"); ?> #<?php echo $order["id"] ?>: <?php echo $order["status"]; ?></caption>
											</table>
										<span align="left"><b><?php echo JText::_("GURU_MYORDERS_ORDER_DATE"); ?>
											<?php 
												$helper = new guruHelper();
												echo $helper->getDate($order["order_date"]);
											?></b></span>
										<br /><br />
										<?php
											if($this->show === TRUE){
												$customer = $guruModelguruOrder->getCustomerDetails($order["userid"]);
										?>
                                        	<div class="span8">
                                                <span class="pull-left" style="font-weight:bold;"><?php echo JText::_("GURU_BILLED_TO").":"; ?></span>
                                                <br />
                                                <table class="guru_customer_details table span7 g_margin_bottom">
                                                    <tr>
                                                        <td><?php echo JText::_("GURU_FIRS_NAME"); ?>:</td>
                                                        <td style="padding-left:20px;"><?php echo $customer["0"]["firstname"]; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo JText::_("GURU_LAST_NAME"); ?>:</td>
                                                        <td style="padding-left:20px;"><?php echo $customer["0"]["lastname"]; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo JText::_("GURU_COMPANY"); ?>:</td>
                                                        <td style="padding-left:20px;"><?php echo $customer["0"]["company"]; ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="span2">
                                                <span  style="font-weight:bold;"><?php echo JText::_("GURU_ISSUED_BY").":"; ?></span>
    
                                                <table class="g_margin_bottom">
                                                    <tr>
                                                        <td>
                                                            <?php 
                                                            $ivoces_details = nl2br($invoice_issued_by);
                                                            $ivoces_details = stripslashes($ivoces_details);
    
                                                            echo $ivoces_details ;?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
										<?php
										}
										?>
										
										<table class="guru_order_table table">
												<tr>
													<th><?php echo JText::_("GURU_COURSE_NAME"); ?></th>
													<th><?php echo JText::_("GURU_QUANTITY"); ?></th>		
													<th><?php echo JText::_("GURU_PROGRAM_DETAILS_PRICE"); ?></th>
													<th><?php echo JText::_("GURU_DISCOUNTPROMO"); ?></th>	
													<th><?php echo JText::_("GURU_TOTAL"); ?></th>	
												</tr>
										<?php
											$ids = "0";
											$id_price = array();
											$id = array();
											
											if(trim($order["courses"]) != ""){
												$temp1 = explode("|", trim($order["courses"]));
												
												if(is_array($temp1) && count($temp1) > 0){
													foreach($temp1 as $key=>$value){
														$temp2 = explode("-", $value);					
														$id[] = trim($temp2["0"]);
														$id_price[trim($temp2["0"])]["price"] = trim($temp2["1"]);						
														//$id_price[trim($temp2["0"])]["quantity"] = trim($temp2["2"]);
													}
												}
											}
											
											$courses = "";
											if(isset($id) && count($id) > 0){
												$courses = $guruModelguruOrder->getCourses(implode(",", $id));
											}	
											if(isset($courses) && is_array($courses) && count($courses) > 0){
												$i = 0;
												$k = 1;
												foreach($courses as $key=>$value){
													$price = $id_price[$value["id"]]["price"];
													$total_courses_price += (float)$price;
										?>			
												<tr class="<?php echo "row".$i; ?> ">
													<td><?php echo $value["name"]; ?></td>
                                                    <td>1</td>
													<td><?php
													if($currencypos == 0){
														echo JText::_($character)." ".$id_price[$value["id"]]["price"];
													}
													else{
														echo $id_price[$value["id"]]["price"]." ".JText::_($character);
													}
													  ?></td>
													<td>
														<?php
                                                            if(in_array($value["id"], $courses_array)){
                                                                $promo_discount_percourse = $guruModelguruOrder->getPromoDiscountCourses($price, $promocodeid);
                                                                
                                                                if($currencypos == 0){
                                                                    echo JText::_($character)." ".$promo_discount_percourse." (".$discount.")";
                                                                }
                                                                else{
                                                                    echo $promo_discount_percourse." ".JText::_($character)." (".$discount.")";
                                                                } 
                                                                
                                                            }
                                                            else{
                                                                if($currencypos == 0){
                                                                    echo JText::_($character)." "."0 (0"."%".")"; 
                                                                }
                                                                else{
                                                                    echo "0 (0"."%".")"." ".JText::_($character); 
                                                                } 
                                                            }
                                                        ?>
                                                    </td>
													<td>
													 <?php
																if($promocodeid != "0"){
																	if(in_array($value["id"], $courses_array)){
																		$total_new = $guruModelguruOrder->getPromoDiscountCourse($price,$promocodeid);
																		$promo_discount_percourse = $guruModelguruOrder->getPromoDiscountCourses($price, $promocodeid );
																		$totall_discount += $promo_discount_percourse;
																	}
																	else{
																		$total_new = $price;
																	}	
																}
																else{
																	$total_new = $price;
																	if($discount_details["0"]["typediscount"] == "0"){
																		$totall_discount = JText::_($character)." "."0";
																	}
																	else{
																		$totall_discount = "0"."%";
																	}
																}
																	
																if($currencypos == 0){
																	echo JText::_($character)." ".round(((float)$total_new), 2); 
																}
																else{
																	echo round(((float)$total_new), 2)." ".JText::_($character); 
																}
																$total_final += $total_new;
															
															 ?>
													 </td>
												</tr>			
												<?php
														$i = 1-$i;
														$k++;
													}//foreach
												}//if
												?>
                                                <table align="right">
                                                    <tr>
                                                        <td  style="font-weight: bold; text-align:right;"><?php echo JText::_("GURU_TOTAL"); ?></td>
                                                        <td><?php
                                                                if($currencypos == 0){
																	echo JText::_($character)." ".$total_courses_price;  
																}
																else{
																	echo $total_courses_price." ".JText::_($character); 
																}
                                                         ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold; text-align:right;"><?php echo JText::_("GURU_DISCOUNTPROMO"); ?></td>
                                                        <td>
														<?php 
															if($currencypos == 0){
																echo JText::_($character)." ".$totall_discount; 
															}
															else{
																echo $totall_discount." ".JText::_($character); 
															}
														?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold; text-align:right;"><?php echo JText::_("GURU_FINAL_TOTAL"); ?></td>
                                                        <td>
															<?php
                                                            if($currencypos == 0){
                                                                echo JText::_($character)." ".$total_final;
                                                            }
                                                            else{
                                                                echo $total_final." ".JText::_($character);
                                                            }
                                                                                
                                                            
                                                            ?>
                                                        </td>
                                                    </tr>
                                              </table>  
										</table>	
										</div>
										
						<input type="hidden" value="com_guru" name="option" />
						<input type="hidden" value="" name="task" />
						<input type="hidden" value="0" name="boxchecked" />
						<input type="hidden" value="guruOrders" name="controller" />
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<div class="g_cell span12 g_mobile_o">
			<div>
				<div>
				<?php
					if($this->show === TRUE){
	?>
		<button type="button" onclick="javascript:printDiv('contentpane'); return false;" class="g_print btn btn-primary"> <img src="<?php echo JUri::base()."components/com_guru/images/print.png"; ?>" alt="Print" /><?php echo JText::_("GURU_PRINT"); ?></button>
	<?php
	}?>
					<form method="post" name="adminForm" action="index.php">
						 <div class="g_table_wrap">
							<table>
								<caption class="componentheading"><?php echo JText::_("GURU_INVOICE")."#"; ?><?php echo $order["id"] ?>: <?php echo $order["status"]; ?></caption>
							</table>
                            <span style="font-weight:bold;"><?php echo JText::_("GURU_ISSUED_BY").":"; ?></span>
							<br />
                            <table  class="g_margin_bottom">
                                <tr>
                                    <td>
                                        <?php 
                                        $ivoces_details = nl2br($invoice_issued_by);
                                        $ivoces_details = stripslashes($ivoces_details);
    
                                        echo $ivoces_details ;?>
                                    </td>
                                </tr>
                            </table>
							<span align="left"><b><?php echo JText::_("GURU_MYORDERS_ORDER_DATE"); ?>
							<?php 
								$helper = new guruHelper();
								echo $helper->getDate($order["order_date"]);
							?></b></span>
						<br /><br />
						<?php
							if($this->show === TRUE){
								$customer = $guruModelguruOrder->getCustomerDetails($order["userid"]);
						?>
							<span style="font-weight:bold;"><?php echo JText::_("GURU_BILLED_TO").":"; ?></span>
							<br />
							<table class="guru_customer_details table">
								<tr>
									<td><?php echo JText::_("GURU_FIRS_NAME"); ?>:</td>
									<td style="padding-left:20px;"><?php echo $customer["0"]["firstname"]; ?></td>
								</tr>
								<tr>
									<td><?php echo JText::_("GURU_LAST_NAME"); ?>:</td>
									<td style="padding-left:20px;"><?php echo $customer["0"]["lastname"]; ?></td>
								</tr>
								<tr>
									<td><?php echo JText::_("GURU_COMPANY"); ?>:</td>
									<td style="padding-left:20px;"><?php echo $customer["0"]["company"]; ?></td>
								</tr>
							</table>
						<?php
						}
						?>
						
						<table class="table">
							<tr>
								<td class="g_cell_1"><b><?php echo JText::_("GURU_COURSE_NAME"); ?></b></td>
								<?php
							$ids = "0";
							$id_price = array();
							$id = array();
							
							if(trim($order["courses"]) != ""){
								$temp1 = explode("|", trim($order["courses"]));
								
								if(is_array($temp1) && count($temp1) > 0){
									foreach($temp1 as $key=>$value){
										$temp2 = explode("-", $value);					
										$id[] = trim($temp2["0"]);
										$id_price[trim($temp2["0"])]["price"] = trim($temp2["1"]);						
										//$id_price[trim($temp2["0"])]["quantity"] = trim($temp2["2"]);
									}
								}
							}
							
							$courses = "";
							if(isset($id) && count($id) > 0){
								$courses = $guruModelguruOrder->getCourses(implode(",", $id));
							}	
							if(isset($courses) && is_array($courses) && count($courses) > 0){
								foreach($courses as $key=>$value){
									$price_p = $id_price[$value["id"]]["price"];
									$total_courses_price_p += (float)$price_p;
									
									if($promocodeid != "0"){
										if(in_array($value["id"], $courses_array)){
											$total_new_p = $guruModelguruOrder->getPromoDiscountCourse($price_p,$promocodeid);
											$promo_discount_percourse_p = $guruModelguruOrder->getPromoDiscountCourses($price_p, $promocodeid );
											$totall_discount_p += $promo_discount_percourse_p;
										}
										else{
											$total_new_p = $price_p;
										}	
									}
									else{
										$total_new_p = $price_p;
										if($discount_details["0"]["typediscount"] == "0"){
											$totall_discount_p = JText::_($character)." "."0";
										}
										else{
											$totall_discount_p = "0"."%";
										}
									}
										
									
									$total_final_p += $total_new_p;
															
								?>
											
									<td><?php echo $value["name"]; ?></td>
								<?php
									}//foreach
								}//if
								?>
							   </tr> 
							   <tr>
									<td class="g_cell_2"><b><?php echo JText::_("GURU_PROGRAM_DETAILS_PRICE"); ?></b></td>
								<?php    
								if(isset($courses) && is_array($courses) && count($courses) > 0){
									foreach($courses as $key=>$value){?>
											
									<td>
									<?php
										if($currencypos == 0){
											echo JText::_($character)." ".$id_price[$value["id"]]["price"];
										}
										else{
											echo $id_price[$value["id"]]["price"]." ".JText::_($character);
										}
										  ?>
									</td>
								<?php
									}//foreach
								}//if
								?>
							   </tr>
							   <tr>
                                    <td><b><?php echo JText::_("GURU_TOTAL"); ?></b></td>	
                                    <td>
                                    <?php
										if($currencypos == 0){
												echo JText::_($character)." ".$total_courses_price_p;  
											}
											else{
												echo $total_courses_price_p." ".JText::_($character); 
											}
										?>
                                    </td>
							   </tr>
							   <tr>
								<td><b><?php echo JText::_("GURU_DISCOUNTPROMO"); ?></b></td>	
								 <td>
								 	<?php 
										if($currencypos == 0){
											echo JText::_($character)." ".$totall_discount_p; 
										}
										else{
											echo $totall_discount_p." ".JText::_($character); 
										}
									?>
                               </td>
							   </tr>
							   <tr>
								<td><b><?php echo JText::_("GURU_FINAL_TOTAL"); ?></b></td>	
								 <td>
									<?php 
									if(is_array($discount_details) && count($discount_details) > 0){ 
										if($currencypos == 0){
											echo JText::_($character)." ".$total_final_p ;
										}
										else{
											echo $total_final_p ." ".JText::_($character);
										}						
									}
									?>
								</td>
							   </tr>
								
						</table>	
					  </div>
						<input type="hidden" value="com_guru" name="option" />
						<input type="hidden" value="" name="task" />
						<input type="hidden" value="0" name="boxchecked" />
						<input type="hidden" value="guruOrders" name="controller" />
					</form>
				</div>
			</div>
		</div>
	 </div>
 <?php }?> 