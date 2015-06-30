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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

	$doc =JFactory::getDocument();
	$doc->addScript('components/com_guru/js/jquery-1.9.1.min.js');	
	$doc->addScript('components/com_guru/js/jquery.noconflict.js');
	$doc->addScript('components/com_guru/js/jquery.DOMWindow.js');
	
	$k = 0;
	$n = 0;
	$orders = $this->orders;
	$n = count($orders);
	$config = guruAdminModelguruOrder::getConfig();
	$dates=$this->dates;	
	$datetype = $this->datetype;
	$currencypos = $config->currencypos;
	
	$total = $this->getTotalSum();
?>
	<script language="javascript">
		function validateForm(){
			var sDate = new Date(document.getElementById('startdate').value);
			var eDate = new Date(document.getElementById('enddate').value);

			if(Date.parse(document.getElementById('startdate').value) == "Invalid Date") {
				alert("<?php echo JText::_("GURU_INVALID_DATE");?>");
				document.getElementById('startdate').value="";
				return false;
			}
			else if( Date.parse(document.getElementById('enddate').value) == "Invalid Date") {
				alert("<?php echo JText::_("GURU_INVALID_DATE");?>");
				document.getElementById('enddate').value="";
				return false;
			}
			else if(document.getElementById('startdate').value == '0000-00-00'){
				alert("<?php echo JText::_("GURU_INVALID_DATE");?>");
				document.getElementById('startdate').value="";
				return false;
			}
			else if(document.getElementById('enddate').value == '0000-00-00'){
				alert("<?php echo JText::_("GURU_INVALID_DATE");?>");
				document.getElementById('startdate').value="";
				return false;
			}
		  	else if(document.getElementById('startdate').value != '' && document.getElementById('enddate').value != '' && sDate> eDate)
			{
				alert("<?php echo JText::_("GURU_DATE_GRATER");?>");
				return false;
			}		
		}
	</script>	
<?php	
	if(isset($_POST['startdate'])){
		$startdate=$_POST['startdate'];
	}
	
	if(isset($startdate)){
		$_SESSION['startdate_guru']=$startdate;
	} 
	elseif(isset($_SESSION['startdate_guru'])){
		$startdate=$_SESSION['startdate_guru'];
	} 
	else{ 
		$startdate=NULL;
	}

	if(isset($_POST['enddate'])){
		$enddate=$_POST['enddate'];
	}
	
	if(isset($enddate)){
		$_SESSION['enddate_guru']=$enddate;
	} 
	elseif(isset($_SESSION['startdate_guru'])){
		$enddate=$_SESSION['enddate_guru'];
	} 
	else{ 
		$enddate=NULL;
	}
	
	if(isset($_SESSION['ord_payments'])){
		$ord_pay=$_SESSION['ord_payments'];
	}
	if(isset($_POST['ord_payments'])){
		$ord_pay=$_POST['ord_payments'];
		$_SESSION['ord_payments']=$ord_pay;
	}
	if(!isset($ord_pay)) {$ord_pay=NULL;}
	
$app = JFactory::getApplication('administrator');
$limistart = $app->getUserStateFromRequest('com_surveys.surveys'.'.list.start', 'limitstart');
$limit = $app->getUserStateFromRequest('com_surveys.surveys'.'.list.limit', 'limit');

$filter_status = JRequest::getVar("filter_status", "-");
$filter_payement = JRequest::getVar("filter_payement", "-");
?>	

<form action="index.php?option=com_guru&controller=guruOrders" id="adminForm" method="post" name="adminForm" onsubmit=" return validateForm();">
	 <div class="row-fluid">
     	<div class="span6">
        	<input type="text" value="<?php if(isset($_SESSION['search_order'])) {
                                                            echo $_SESSION['search_order'];
                                                        }
                                                 ?>" name="search"/>&nbsp;&nbsp;
			<input style="margin-left:10px;" class="btn btn-primary" type="submit" value="<?php echo JText::_("GURU_SEARCHTXT"); ?>" name="submit_search"/>
            <div class="clearfix" style="padding:3px;"></div>
            <select name="filter_status" class="inputbox" onchange="document.adminForm.submit();">
                <option value="-"  <?php if ($filter_status == "-"){ echo ' selected="selected" '; }?>><?php  echo JText::_("GURU_STATUS"); ?></option>
                <option value="Pending"  <?php if ($filter_status == "Pending"){ echo ' selected="selected" '; }?>><?php  echo JText::_("GURU_AU_PENDING"); ?></option>
                <option value="Paid"  <?php if ($filter_status == "Paid"){ echo ' selected="selected" '; }?>><?php echo JText::_("GURU_O_PAID"); ?></option>
            </select>
            
            <select name="filter_payement" class="inputbox" onchange="document.adminForm.submit();">
                <option value="-"  <?php if ($filter_payement == "-"){ echo ' selected="selected" '; }?>><?php  echo JText::_("GURU_ORDPAYMENTMETHOD"); ?></option>
                <option value="payauthorize"  <?php if ($filter_payement == "payauthorize"){ echo ' selected="selected" '; }?>><?php  echo JText::_("GURU_PAYAUTHORIZE"); ?></option>
                <option value="paypaypal"  <?php if ($filter_payement == "paypaypal"){ echo ' selected="selected" '; }?>><?php echo JText::_("GURU_PAYPAL"); ?></option>
            </select>
        </div>
        
        <div class="span6">
        	<?php
				echo JHTML::_("calendar", $startdate, 'startdate', 'startdate'); 
			?>
            <?php
				echo JHTML::_("calendar", $enddate, 'enddate', 'enddate'); 
			?>
            
            <input style="margin-top:-10px;" class="btn btn-primary" type="submit" value="<?php echo JText::_("GURU_VIEWSTATGO"); ?>" name="submit_go"/>
            
            <div class="clearfix" style="padding:3px;"></div>
            
            <div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" style="border-width:0px !important;"><?php echo JText::_("GURU_EXPORT"); ?>
                    <span class="caret" style="margin-left: 0; margin-top: 10px;"></span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#" onclick="document.getElementById('message_lib').style.display='none'; document.adminForm.export.value='csv'; document.adminForm.submit();">CSV</a>
                    </li>
                    <?php
                    	if(file_exists(JPATH_SITE.DS."components".DS."com_guru".DS."helpers".DS."MPDF".DS."mpdf.php")){
					?>
                            <li>
                                <a href="#" onclick="document.adminForm.export.value='pdf'; document.adminForm.submit();">PDF</a>
                            </li>
                    <?php
                    	}
						else{
					?>
                    		<li>
                                <a href="#" onclick="document.getElementById('message_lib').style.display='block';">PDF</a>
                            </li>
                    <?php
						}
					?>
                </ul>
            </div>
            
            <?php
            	$filter_teacher = JRequest::getVar("filter_teacher", "0");
				$teachers = $this->getAllTeachers();
			?>
            <select name="filter_teacher" class="inputbox" onchange="document.adminForm.submit();">
                <option value="0" <?php if($filter_teacher == "0"){ echo 'selected="selected"'; }?>><?php  echo JText::_("GURU_SELECT_TEACHER"); ?></option>
                <?php
                	if(isset($teachers) && count($teachers) > 0){
						foreach($teachers as $key=>$teacher){
				?>
                			<option value="<?php echo $teacher["id"]; ?>" <?php if($teacher["id"] == $filter_teacher){echo 'selected="selected"';} ?> ><?php echo $teacher["name"]; ?></option>
                <?php
						}
					}
				?>
            </select>
            <span class="order-sum-value pull-right"><?php echo $total; ?></span>
            <span class="order-sum-label pull-right"><?php echo JText::_("GURU_TOTAL"); ?>:&nbsp;</span>
        </div>
     </div>
     
    <span id="message_lib" class="alert" style="display:none; margin-top: 5px;">
        <a href='http://www.ijoomla.com/redirect/guru/mpdf.htm' target="_blank">
        	<?php echo "1. ".JText::_("GURU_DOWNLOAD_MPDF1"); ?>
        </a>
        <br />
        <?php echo "2. ".JText::_("GURU_DOWNLOAD_MPDF2"); ?>
        <br />
        <?php echo "3. ".JText::_("GURU_DOWNLOAD_MPDF3"); ?>
    </span>

<div id="myModal" class="modal hide">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    </div>
    <div class="modal-body">
    </div>
</div>
<div class="container-fluid">
      <a data-toggle="modal" data-target="#myModal" onclick="showContentVideo('index.php?option=com_guru&controller=guruAbout&task=vimeo&id=27181273&tmpl=component')" class="pull-right guru_video" href="#">
                <img src="<?php echo JURI::base(); ?>components/com_guru/images/icon_video.gif" class="video_img" />
            <?php echo JText::_("GURU_ORDERS_VIDEO"); ?>                  
      </a>
</div>	
<div class="clearfix"></div>
<div class="well well-minimized">
    <?php echo JText::_("GURU_ORDERS_SETTINGS_DESCRIPTION"); ?>
</div>

	<div id="editcell" >
		<table class="table table-striped  table-bordered  adminlist">
			<thead>
				<tr>
					<th width="20">
						#
					</th>
					<th width="5">
						<input type="checkbox" onclick="Joomla.checkAll(this)" name="toggle" value="" />
                        <span class="lbl"></span>
					</th>
					<th width="20">
						<?php echo JText::_('GURU_ID');?>
					</th>
					<th>
						<?php echo JText::_('GURU_ORDDATE'); ?>
					</th>				
					<th>
						<?php echo JText::_('GURU_PRICE');?>
					</th>
					<th>
						<?php echo JText::_('GURU_USERNAME');?>
					</th>
					<th>
						<?php echo JText::_('GURU_CUSTOMER_HEAD');?>
					</th>					
					<th>
						<?php echo JText::_('GURU_STATUS');?>
					</th>
                    <th>
						<?php echo JText::_('GURU_COURSE')."(s)";?>
					</th>
					<th>
						<?php echo JText::_('GURU_ORDPAYMENTMETHOD');?>
					</th>									
				</tr>
			</thead>

			<tbody>
			<?php
				$j = $limistart+1;
				for ($i = 0; $i < $n; $i++):
					$order = $this->orders[$i];
					$order =(array)$orders[$i];						
					$id = $order["id"];
					$checked = JHTML::_('grid.id', $i, $id);
					$customerlink = JRoute::_("index.php?option=com_guru&controller=guruCustomers&task=edit&cid[]=".$order["userid"]);
			?>
				<tr class="row<?php echo $k;?>"> 
					<td align="center">
						<?php echo $j++; ?>
					</td>
					<td>
						<?php echo $checked;?>
                        <span class="lbl"></span>
					</td>		
				
					<td align="center">
						<a class="a_guru" href="index.php?option=com_guru&controller=guruOrders&task=show&cid=<?php echo $id;?>"><?php echo $id;?></a>
					</td>		
				
					<td align="center">
						<?php 
							if($config->hour_format == 12){
							$format = " Y-m-d h:i:s A ";
								switch($datetype){
									case "d-m-Y H:i:s": $format = "d-m-Y h:i:s A";
									      break;
									case "d/m/Y H:i:s": $format = "d/m/Y h:i:s A"; 
										  break;
									case "m-d-Y H:i:s": $format = "m-d-Y h:i:s A"; 
										  break;
									case "m/d/Y H:i:s": $format = "m/d/Y h:i:s A"; 
										  break;
									case "Y-m-d H:i:s": $format = "Y-m-d h:i:s A"; 
										  break;
									case "Y/m/d H:i:s": $format = "Y/m/d h:i:s A"; 
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
								$date_int = strtotime($order["order_date"]);
								//$date_string = date("Y-m-d h:i:s A", $date_int);
								$date_string = JHTML::_('date', $date_int, $format );
								echo $date_string;
							}
							else{
								$date_int = strtotime($order["order_date"]);
								//$date_string = date("Y-m-d H:i:s", $date_int);
								$format = "Y-m-d H:M:S";
								switch($datetype){
									case "d-m-Y H:i:s": $format = "d-m-Y H:i:s";
									      break;
									case "d/m/Y H:i:s": $format = "d/m/Y H:i:s"; 
										  break;
									case "m-d-Y H:i:s": $format = "m-d-Y H:i:s"; 
										  break;
									case "m/d/Y H:i:s": $format = "m/d/Y H:i:s"; 
										  break;
									case "Y-m-d H:i:s": $format = "Y-m-d H:i:s"; 
										  break;
									case "Y/m/d H:i:s": $format = "Y/m/d H:i:s"; 
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
								$date_string = JHTML::_('date', $date_int, $format);
								echo $date_string;
							}
						?>
					</td>	
				
					<td align="center">
						<?php 
							$character = "GURU_CURRENCY_".$order["currency"];
							if(isset($order["amount_paid"]) && trim($order["amount_paid"]) != "" && trim($order["amount_paid"]) != "-1"){
								
								if($currencypos == 0){
									echo JText::_($character)." ".$order["amount_paid"];
								}
								else{
									echo $order["amount_paid"]." ".JText::_($character);
								}
							}
							else{
								if($currencypos == 0){
									echo JText::_($character)." ".$order["amount"];
								}
								else{
									echo $order["amount"]." ".JText::_($character);
								}
								
							}
						?>
					</td>
								
					<td align="center"> 
                    	<?php $userlink = JRoute::_("index.php?option=com_users&task=user.edit&id=".$order["userid"]);?>
						<a class="a_guru" href="<?php echo $userlink; ?>"><?php echo $order["username"]; ?></a>
					</td>		
			
					<td align="center">
						<a class="a_guru" href="<?php echo $customerlink; ?>"><?php echo $order["firstname"]." ".$order["lastname"]; ?></a>
					</td>
					
					<td align="center">
						<a class="a_guru" href="index.php?option=com_guru&amp;controller=guruOrders&amp;task=cycleStatus&amp;cid[]=<?php echo $id; ?>"><?php echo $order["status"]; ?></a>
					</td>		
					<td align="center">
						<?php 
						$idsc = array();
						if(trim($order["courses"]) != ""){
							$temp1 = explode("|", trim($order["courses"]));
							if(is_array($temp1) && count($temp1) > 0){
								foreach($temp1 as $key=>$value){
									$temp2 = explode("-", $value);
									$idsc[] = trim($temp2["0"]);
									$course_id_plan[$temp2["0"]] = $temp2["2"];
									$id_price[trim($temp2["0"])]["price"] = trim($temp2["1"]);
								}
							}
						}
						
						$courses = "";
						if(isset($idsc) && count($idsc) > 0){
							$idsc = array_diff($idsc, array(''));
							$courses = guruAdminModelguruOrder::getCourses(implode(",", $idsc));
						}		
						if(isset($courses) && is_array($courses) && count($courses) > 0){
							foreach($courses as $key=>$value){
								echo '<a href="index.php?option=com_guru&controller=guruDays&pid='.$value["id"].'">'.$value["name"].'</a>'."<br/>";
							}
						}
						?>
					</td>
					<td align="center">
						<?php 
							echo $order["processor"];
						?>
					</td>
				</tr>
				
				
				<?php 
					$k = 1 - $k;
					endfor;
				?>
				</tbody>
                <tfoot>
                    <tr>
                        <td colspan="10">
                        	<div class="btn-group pull-left hidden-phone">
                                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                                <?php echo $this->pagination->getLimitBox(); ?>
                            </div>
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
            </tfoot>
	</table>
</div>

<input type="hidden" name="option" value="com_guru" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="guruOrders" />
<input type="hidden" name="old_limit" value="<?php echo JRequest::getVar("limitstart"); ?>" />
<input type="hidden" name="export" value="" />
</form>
<script language="javascript">
	var first = false;
	function showContentVideo(href){
	first = true;
	jQuery.ajax({
      url: href,
      success: function(response){
       jQuery( '#myModal .modal-body').html(response);
      }
    });
}

jQuery('#myModal').on('hide', function () {
 jQuery('div.modal-body').html('');
});
jQuery('body').click(function () {
	if(!first){
		jQuery('#myModal .modal-body iframe').attr('src', '');
	}
	else{
		first = false;
	}
});
</script>