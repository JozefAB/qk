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
$doc =JFactory::getDocument();
$doc->addScript(JURI::root().'/components/com_guru/js/sorttable.js');
$details = $this->details;
$total_paid = $this->total_paid;
$config = $this->config;
$datetype  = $config->datetype;
$currencypos = $config->currencypos;
$character = "GURU_CURRENCY_".$config->currency;
$guruModelguruAuthor = new guruModelguruAuthor();
$pagination = $this->getDetailsPagination();

//-----------------------------------------------------------------------------------
$tot = "";
if(isset($total_paid) && count($total_paid) > 0){
	$temp = array();
	foreach($total_paid as $key=>$value){
		if(isset($temp[$value["currency"]])){
			$temp[$value["currency"]] += $value["amount_paid_author"];
		}
		else{
			$temp[$value["currency"]] = $value["amount_paid_author"];
		}
	}
	
	if($currencypos == 0){
		foreach($temp as $currency=>$value){
			$temp[$currency] = JText::_("GURU_CURRENCY_".$currency)." ".number_format($value, 2);
		}
		$tot = implode("&nbsp;&nbsp;&nbsp;", $temp);
	}
	else{
		foreach($temp as $currency=>$value){
			$temp[$currency] = number_format($value, 2)." ".JText::_("GURU_CURRENCY_".$currency);
		}
		$tot = implode("&nbsp;&nbsp;&nbsp;", $temp);
	}
}
//-----------------------------------------------------------------------------------

?>
<div>
	 <div class="btn-group pull-right">
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" style="border-width:0px !important;"><?php echo JText::_("GURU_EXPORT"); ?>
            <span class="caret" style="margin-left: 0; margin-top: 10px;"></span>
        </button>
        <ul class="dropdown-menu">
            <li>
            	<div onclick="document.adminForm.export.value='csv'; document.adminForm.submit();" style="width: 100%; padding: 2px 10px; cursor: pointer;"><?php echo JText::_("GURU_CSV");?></div>
            </li>
        </ul>
    </div>
    <h2><?php echo JText::_('GURU_COMMISSIONS_RECEIVED')." ".">"." ".JText::_('GURU_VIEW_DETAILS'); ?></h2>
    <div class="g_margin_bottom"><?php echo JText::_('GURU_COMMISSIONS_RECEIVED').":"." ".'<b>'.$tot.'</b>'; ?></div>
</div>
<form id="adminForm" name="adminForm" method="post">
	  <table style="width: 40%;" cellpadding="5" cellspacing="5" bgcolor="#FFFFFF">
        <tr>
        	<td>
            	<?php  echo JText::_("GURU_FILTER_BY"); ?>
            </td>
        	<td>
				<?php
                    $filter_course = JRequest::getVar("filter_course", "0");
                    $course = $guruModelguruAuthor->getAllCourses();
                ?>
                <select name="filter_course" class="inputbox" onchange="document.getElementById('export').value=''; document.adminForm.submit();">
                    <option value="0" <?php if($filter_course == "0"){ echo 'selected="selected"'; }?>><?php  echo JText::_("GURU_SELECT_COURSE"); ?></option>
                    <?php
                        if(isset($course) && count($course) > 0){
                            foreach($course as $key=>$courses){
                    ?>
                                <option value="<?php echo $courses["id"]; ?>" <?php if($courses["id"] == $filter_course){echo 'selected="selected"';} ?> ><?php echo $courses["name"]; ?></option>
                    <?php
                            }
                        }
                    ?>
                </select>
            </td>
        <tr>
    </table> 

    <table class="sortable table table-striped adminlist table-bordered">
        <thead>
            <tr>
                <th>
                #
                </th>
                <th>
                    <?php echo JText::_('GURU_ID');?><i class="icon-menu-2"></i>
                </th>
                <th>
                    <?php echo JText::_('GURU_MYORDERS_ORDER_DATE');?><i class="icon-menu-2"></i>
                </th>
                <th>
					<?php echo JText::_('GURU_COURSE_NAME');?><i class="icon-menu-2"></i>
                </th>
                <th class="sorttable_numeric">
                    <?php echo JText::_('VIEWORDERSAMOUNTPAID'); ?><i class="icon-menu-2"></i>
                </th> 
                <th>
                    <?php echo JText::_('GURU_VIEW_DETAILS');?>
                </th> 
            </tr>
        </thead>
        
        <tbody>
        <?php
        $inc = 1;
		
		foreach($details as $key=>$value){
			$temp_key = explode("-", $key);
			$course_name =  $guruModelguruAuthor->getCourseName1($value["course_id"]);
			$character = "GURU_CURRENCY_".$temp_key["3"];
        ?>
            <tr> 
                <td>
                    <?php echo $inc; ?>
                </td>
                <td>
                    <?php echo $value["id"];?>
                </td>	
                <td>
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
                    $date_int = strtotime($value["data"]);
                    $date_string = JHTML::_('date', $date_int, $format );
                    }
                    else{
                    $date_int = strtotime($value["data"]);
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
                    }	
                
                ?>
                <?php echo $date_string;?>
                </td>
                <td>
					<?php echo $course_name;?>
                </td>
                <td>
                <?php
                if($currencypos == 0){
                    echo JText::_($character)." ".number_format($value["amount_paid_author"],2);
                }
                else{
                    echo number_format($value["amount_paid_author"],2)." ".JText::_($character);
                }
                ?>
                </td>
                <td>
                    <a class="btn btn-primary" href ="index.php?option=com_guru&controller=guruAuthor&task=details_paid&tmpl=component&course_id=<?php echo $value["course_id"];?>&cid[]=<?php echo $value["author_id"];?>&block=<?php echo $value["id"];?>&date=<?php echo $value["data"];?>"><?php echo JText::_("GURU_DETAILS"); ?></a>
                </td> 
            </tr>
            <?php			
            $inc ++;
        }
        
    ?>
     <tfoot>
        <tr>
            <td colspan="10">
                <div class="btn-group pull-left">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                    <?php
                    	$select_limit = $pagination->getLimitBox();
						$select_limit = str_replace('this.form.submit()', "document.getElementById('export').value=''; Joomla.submitform()", $select_limit);
						echo $select_limit;
					?>
                </div>
                <?php
                	$pages = $pagination->getListFooter();
					include_once(JPATH_SITE.DS."components".DS."com_guru".DS."helpers".DS."helper.php");
					$helper = new guruHelper();
					$pages = str_replace('name="limitstart"', 'name="temp_limitstart"', $pages);
					$pages = $helper->transformPagination($pages);
					$pages = str_replace('Joomla.submitform()', "document.getElementById('export').value=''; Joomla.submitform()", $pages);
					echo $pages;
				?>
                
            </td>
        </tr>
    </tfoot>
</table> 
	<input type="hidden" name="task" value="<?php echo JRequest::getVar("task", "authorcommissions_paid");?>" />
    <input type="hidden" name="option" value="com_guru" />
    <input type="hidden" name="controller" value="guruAuthor" />
    <input type="hidden" name="view" value="guruauthor" />
    <input type="hidden" name="boxchecked" value="" />
    <input type="hidden" id="export" name="export" value="" />
    <input type="hidden" name="old_limit" value="<?php echo intval($pagination->get("limit")); ?>" />

</form>
