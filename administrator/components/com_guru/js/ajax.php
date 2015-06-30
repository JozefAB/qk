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
define( '_JEXEC', 1 );
define('JPATH_BASE', substr(substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), "administra")),0,-1));


define( 'DS', DIRECTORY_SEPARATOR );
$parts = explode(DS, JPATH_BASE);
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
//require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'methods.php');
require_once ( JPATH_BASE .DS.'configuration.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'base'.DS.'adapter.php');
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'database'.DS.'database.php');
//require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'database'.DS.'database'.DS.'mysql.php');
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'filesystem'.DS.'folder.php');

$config = new JConfig();


//$database = new JoomlaDatabase($options);
$database = JFactory::getDBO();
$task = JRequest::getVar("task", "", "get", "string");

switch($task){
	case "getplans" : getPlainsByCourseIDSelectHTML(); 
						break;
	case "getcoursecost" : getCourseCost();
						break;
	case "setpromo" : setPromo();
						break;
	case "setrenew" : setRenew();
						break;	
	case "checkExistingUser" : {
		checkExistingUser();
		break;
	}													
}

function checkExistingUser(){
	global $database;
	$username = JRequest::getVar("username", "");
	$email = JRequest::getVar("email", "");
	$id = JRequest::getVar("id", "0");
	
	if(intval($id) == 0){// new user
		$sql = "select count(*) from #__users where `email`='".addslashes(trim($email))."'";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadResult();
		if($result > 0){
			echo "111";
			return true;
		}
		
		$sql = "select count(*) from #__users where `username`='".addslashes(trim($username))."'";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadResult();
		if($result > 0){
			echo "222";
			return true;
		}
		die();
	}
	elseif(intval($id) != 0){
		$sql = "select `username`, `email` from #__users where id=".intval($id);
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadAssocList();
		$old_username = $result["0"]["username"];
		$old_email = $result["0"]["email"];
		if($username != $old_username){
			$sql = "select count(*) from #__users where `username`='".addslashes(trim($username))."'";
			$database->setQuery($sql);
			$database->query();
			$result = $database->loadResult();
			if($result > 0){
				echo "222";
				return true;
			}
			die();
		}
		
		if($email != $old_email){
			$sql = "select count(*) from #__users where `email`='".addslashes(trim($email))."'";
			$database->setQuery($sql);
			$database->query();
			$result = $database->loadResult();
			if($result > 0){
				echo "111";
				return true;
			}
			die();
		}
	}
}


function setRenew(){
	$db = JFactory::getDBO();
	$gen_code = JRequest::getVar("gen_number");
	$course_id = JRequest::getVar("course_id");
	$option = JRequest::getVar("option");
	
	$sql = "select sb.name, pr.price, pr.default from #__guru_program_renewals pr, #__guru_subplan sb where sb.id=pr.plan_id and pr.product_id=".intval($course_id);
	$db->setQuery($sql);
	$db->query();
	$plans = $db->loadAssocList();

	$component_configs = getComponentConfigs();
	$currency = $component_configs["0"]["currency"];
	$character = utf8_encode(trim(getCharacterCurrency($currency)));

	if((!isset($plans) || count($plans) <= 0) || $option == "new"){
		$sql = "select sb.name, pr.price, pr.default from #__guru_program_plans pr, #__guru_subplan sb where sb.id=pr.plan_id and pr.product_id=".intval($course_id);
		$db->setQuery($sql);
		$db->query();
		$plans = $db->loadAssocList();
	}
	
	$hidden_value = "0.00";
	
	$select = '<select name="licences_select" id="licences_select'.$gen_code.'" class="inputbox" size="1" onchange="javascript:changeAmount(\''.$gen_code.'\');">';
	foreach($plans as $key=>$value){
		$selected = '';
		if($value["default"] == "1"){
			$selected = 'selected="selected"';
			$hidden_value = $value["price"];
		}
		$select .= '<option value="'.$value["price"].'" '.$selected.'>'.$value["name"]." - ".$character.$value["price"].'</option>';
	}
	$select .= "</select>";
	$select .= '<input type="hidden" id="hidden_licenses_'.$gen_code.'" name="hidden_licenses['.$gen_code.']" value="'.$hidden_value.'" />';
	echo $select;
}

function setPromo(){
	$promo_code = JRequest::getVar("promocode", "");
	$value = JRequest::getVar("value");
	$value = floatval($value);
	$count_s = JRequest::getVar("count", "0");
	
	if($promo_code == "none" || $promo_code == ""){
		echo "-1";
	}
	else{
		global $database;		
		$sql = "select * from #__guru_promos where code='".addslashes(trim($promo_code))."'";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadAssocList();
		
		$sql1 = "SELECT courses_ids FROM #__guru_promos where code='".addslashes(trim($promo_code))."'";
		$database->setQuery($sql1);
		$database->query();
		$courses = $database->loadColumn();
		$courses_array = explode("||",$courses["0"]);
		
		for ($i =1; $i<$count_s; $i++){
			$s = JRequest::getVar("s".$i, "");
			$course_id_price = explode("-",$s);
			$course_id = $course_id_price[0];
			$price = $course_id_price[1];
			if(in_array($course_id,$courses_array)){
				$discount = $result["0"]["discount"];
				$type = $result["0"]["typediscount"];
				if($type == '0') {//use absolute values		
					$value = $price - (float)$discount;
				}
				else{//use percentage
					$value = ($discount / 100)*$price;
				}
			}
			
			
		}
		/*if(isset($result) && count($result) > 0){
			$discount = $result["0"]["discount"];
			$type = $result["0"]["typediscount"];
			if($type == "0"){
				$value = $discount;
			}
			else{
				$value = floatval($discount/100)*$value;
			}
		}*/
			
	}
	echo $value;
	return true;
}

function getCourseCost(){
	global $database;
	$course_id = JRequest::getVar("course_id");
	$sql = "SELECT pp.price FROM #__guru_program_plans pp, #__guru_subplan s WHERE pp.product_id = ".$course_id." and pp.plan_id=s.id and pp.default=1";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadResult();
	echo $result;
}

function getComponentConfigs(){
	global $database;
	$sql = "select * from #__guru_config";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadAssocList();
	return $result;
}

function getCharacterCurrency($currency){
	$character = "$";
	switch($currency){
		case "INR" : {
			$character = "INR";
			break;
		}
		case "MXN" : {
			$character = "$";
			break;
		}
		case "USD" : {
			$character = "$";
			break;
		}
		case "AUD" : {
			$character = "$";
			break;
		}
		case "CAD" : {
			$character = "$";
			break;
		}
		case "CHF" : {
			$character = "Fr";
			break;
		}
		case "CZK" : {
			$character = "Kc";
			break;
		}
		case "DKK" : {
			$character = "kr";
			break;
		}
		case "EUR" : {
			$character = "�";
			break;
		}
		case "GBP" : {
			$character = "�";
			break;
		}
		case "HKD" : {
			$character = "$";
			break;
		}
		case "HUF" : {
			$character = "Ft";
			break;
		}
		case "JPY" : {
			$character = "�";
			break;
		}
		case "NOK" : {
			$character = "kr";
			break;
		}
		case "HZD" : {
			$character = "$";
			break;
		}
		case "PLN" : {
			$character = "zl";
			break;
		}
		case "SEK" : {
			$character = "kr";
			break;
		}
		case "SGD" : {
			$character = "$";
			break;
		}
		case "BRL" : {
			$character = "R$";
			break;
		}
	}
	return $character;
}

function getPlainsByCourseIDSelectHTML(){
	global $database;
	$course_id = JRequest::getVar("course_id");
	$gen_number = JRequest::getVar("gen_id");
	$sql = "SELECT pp.plan_id, pp.price, pp.default, s.name FROM #__guru_program_plans pp, #__guru_subplan s WHERE pp.product_id = ".$course_id." and pp.plan_id=s.id order by s.ordering";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadAssocList();
	
	$hidden_value = "0.00";
	$currencyposition = "";
	
	$component_configs = getComponentConfigs();
	$currency = $component_configs["0"]["currency"];
	$currencypos = $component_configs["0"]["currencypos"];
	
	$character = utf8_encode(trim(getCharacterCurrency($currency)));
	if($currencypos == 0){
		$currencyposition1 = $character ;
	}
	else{
		$currencyposition2 = $character ;
	}

	$html = '<select onchange="javascript:changeAmount(\''.$gen_number.'\');" size="1" class="inputbox" id="licences_select'.$gen_number.'" name="licences_select">';
	if(isset($result) && is_array($result) && count($result) > 0){
		foreach($result as $key=>$value){
			$selected = "";
			if($value["default"] == "1"){
				$selected = 'selected="selected"';
				$hidden_value = $value["price"];
			}
			$html .= '<option value="'.$value["price"].'" '.$selected.' >'.$value["name"]." - ".@$currencyposition1." ".$value["price"].@$currencyposition2.'</option>';
		}
	}
	else{
		$html .= '<option value="none">none</option>';
	}
	$html .= '</select>';
	$html .= '<input type="hidden" id="hidden_licenses_'.$gen_number.'" name="hidden_licenses['.$gen_number.']" value="'.$hidden_value.'" />';
	echo $html;
}
?>	