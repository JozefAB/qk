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

$plans = $this->plans;
$guruModelguruEditplans = new guruModelguruEditplans();
$config = $guruModelguruEditplans->getConfigs();
$currency = $config["0"]["currency"];
$character = JTExt::_("GURU_CURRENCY_".$currency); 
$course_id = intval(JRequest::getVar("course_id", "0"));
$action = JRequest::getVar("action", "");
$my = JFactory::getUser();
$user_id = $my->id;
$db = JFactory::getDBO();

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_guru/css/guru_style.css");

jimport('joomla.language.helper');
$lang_value = JLanguageHelper::detectLanguage();		
$lang = new JLanguage();
$lang->load('com_guru',JPATH_BASE,$lang_value);

if($action == ""){
?>
<div id="g_content" class="">
	<div class="g_cell span12">
		<div>
        	<div>
                <form action="index.php" name="adminForm" method="post">
                    <div id="g_prices_modal_text"  class="g_prices_modal_text">
                        <b>
                        <?php 
                            echo $config["0"]["content_selling"];
                        ?>
                        </b>
                    </div>
                            
                    <table id="g_prices_modal" class="g_prices_modal">
                        <?php
                            foreach($plans as $key=>$value){ 
                        ?>
                            <tr>
                                <td>
                                    <input type="radio" name="course_plans" value="<?php echo $value["price"] ?>" <?php if($value["default"] == "1"){echo 'checked="checked"';} ?>/> 
                                </td>
                                <td style="font-family: Georgia;">
                                    <?php echo $value["name"]; ?>
                                </td>
                                <td>
                                    <?php 
                                        echo $character." ".$value["price"];
                                    ?>
                                </td>
                            </tr>    
                        <?php
                            }
                        ?>
                    </table>
                        
                    <input type="hidden" name="option" value="com_guru" />
                    <input type="hidden" name="controller" value="guruEditplans" />
                    <input type="hidden" name="task" value="buy" />
                    <input type="hidden" name="course_id" value="<?php echo intval($course_id); ?>" />
                    <input type="submit" name="continue" class="btn btn-warning" value="<?php echo JText::_("GURU_CONTINUE_ARROW"); ?>" />
                </form>
<?php
}
elseif($action == "renew"){ // for renew
	$sql = "select expired_date from #__guru_buy_courses where userid=".intval($user_id)." and course_id=".intval($course_id);
	$db->setQuery($sql);
	$db->query();
	$expired_date_string = $db->loadResult();
	$expired_date_int = strtotime($expired_date_string);
	$jnow = JFactory::getDate();
	$current_date_string = $jnow->toSQL();
	$current_date_int = strtotime($current_date_string);
	
	$sql = "select pp.price, pp.default, s.name from #__guru_program_renewals pp, #__guru_subplan s where s.id = pp.plan_id and pp.product_id=".intval($course_id)." order by s.ordering asc";
	$db->setQuery($sql);
	$db->query();
	$plans = $db->loadAssocList();
	
	if(count($plans) == 0){ // no plans for renew
		$plans = $this->plans; // from buy plans
	}
?>
	
				<?php
				$difference_int = get_time_difference($current_date_int, $expired_date_int);
				if($difference_int){ //not expired
?>
				<form action="index.php" name="adminForm" method="post">
                	<div id="g_prices_modal_text"  class="g_prices_modal_text">
                    	<b>
                        <?php 
                            echo $config["0"]["content_selling"];
                        ?>
                        </b>
                    </div>
                    
                    
				<table id="g_prices_modal" class="g_prices_modal">
					<?php
						foreach($plans as $key=>$value){
					?>
						<tr>
							<td>
								<input type="radio" name="course_plans" value="<?php echo $value["price"] ?>" <?php if($value["default"] == "1"){echo 'checked="checked"';} ?>/> 
							</td>
							<td style="font-family: Georgia;">
								<?php echo $value["name"]; ?>
							</td>
							<td>
								<?php
									echo $character." ".$value["price"];
								?>
							</td>
						</tr>    
					<?php
						}
					?>
				</table>
				
				<input type="hidden" name="option" value="com_guru" />
				<input type="hidden" name="controller" value="guruEditplans" />
				<input type="hidden" name="task" value="renew" />
				<input type="hidden" name="course_id" value="<?php echo intval($course_id); ?>" />
				<table>
					<tr>
						<td>
<?php
					echo JText::_("GURU_STILL_HAVE")." ".$difference_int["days"]." ".JText::_("GURU_AVAILABLE_COURSE")." ".'<a href="#" onclick="document.adminForm.submit();">'.JText::_("GURU_YES").'</a>'." ".JText::_("GURU_ADD_TIME")." ".'<a href="#" onclick="document.adminForm.task.value=\'course\'; document.adminForm.submit();">'.JText::_("GURU_NO").'</a>'." ".JText::_("GURU_GO_TO_COURSE_PAGE");
?>
						</td>
					</tr>
				</table>
				</form>
<?php					
				}
				else{//expired, buy now
?>
				<form action="index.php" name="adminForm" method="post">
                     <div id="g_prices_modal_text"  class="g_prices_modal_text">
                        <b>
                        <?php 
                            echo $config["0"]["content_selling"];
                        ?>
                        </b>
                    </div>
					<table  id="g_prices_modal" class="g_prices_modal">
						<?php
							foreach($plans as $key=>$value){
						?>
							<tr>
								<td>
									<input type="radio" name="course_plans" value="<?php echo $value["price"] ?>" <?php if($value["default"] == "1"){echo 'checked="checked"';} ?>/> 
								</td>
								<td style="font-family: Georgia;">
									<?php echo $value["name"]; ?>
								</td>
								<td>
									<?php
										echo $character." ".$value["price"];
									?>
								</td>
							</tr>    
						<?php
							}
						?>
					</table>
					
					<input type="hidden" name="option" value="com_guru" />
					<input type="hidden" name="controller" value="guruEditplans" />
					<input type="hidden" name="task" value="renew" />
					<input type="hidden" name="course_id" value="<?php echo intval($course_id); ?>" />
				
					<input type="submit" class="btn btn-warning" name="continue" value="<?php echo JText::_("GURU_CONTINUE_ARROW"); ?>" />
				</form>
					
<?php					
			}
}

function get_time_difference($start, $end){
    $uts['start'] = $start;
    $uts['end'] = $end;
    if( $uts['start'] !== -1 && $uts['end'] !== -1){
        if($uts['end'] >= $uts['start']){
            $diff = $uts['end'] - $uts['start'];
            if($days=intval((floor($diff/86400)))){
                $diff = $diff % 86400;
			}
				
            if($hours=intval((floor($diff/3600)))){
                $diff = $diff % 3600;
			}	
            
			if($minutes=intval((floor($diff/60)))){
                $diff = $diff % 60;
			}	
            $diff = intval($diff);
            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff));
        }
		else{
			return false;
		}
    }
    return false;
}

?>
			</div>
		</div>
	</div>
</div>