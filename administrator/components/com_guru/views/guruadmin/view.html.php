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

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport ("joomla.application.component.view");

class guruAdminViewguruAdmin extends JViewLegacy {

	function display ($tpl = null){
		JToolBarHelper::title('&nbsp;', 'generic.png');
		$this->status_message = $this->get("CoponentStatus");
		parent::display($tpl);
	}
	function showNotification(){
		$this->notification = $this->get("NotificationStatus");
		return $this->notification;
	}
	
	function getOrders(){
		$db = JFactory::getDBO();
		$sql = "SELECT count(*) as orders FROM `#__guru_order` o, #__users u, #__guru_customer c where c.id=u.id and u.id=o.userid";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result;
	}
	function getRevenue(){
		$db = JFactory::getDBO();
		
		$sql = "select o.*, u.username, c.firstname, c.lastname from #__guru_order o, #__users u, #__guru_customer c where c.id=u.id and u.id=o.userid order by o.order_date desc ";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		
		$sum = array();
		if(isset($result) && count($result) > 0){
			foreach($result as $key=>$value){
				if(isset($value["courses"]) && trim($value["courses"]) != ""){
					$list = explode("|", $value["courses"]);
					
					if(isset($list) && count($list) > 0){
						foreach($list as $nr=>$element){
							$temp = explode("-", $element);
							$course_id = intval($temp["0"]);
							if(isset($courses) && count($courses) > 0){// filter about teachers
								if(in_array($course_id, $courses)){ // if this course is from our filter
									if($value["amount_paid"] == -1){
										@$sum[$value["currency"]] += $temp["1"];
									}
									else{
										@$sum[$value["currency"]] += $value["amount_paid"];
									}
								}
							}
							else{// not filter about teachers
								if($value["amount_paid"] == -1){
									@$sum[$value["currency"]] += $temp["1"];
								}
								else{
									@$sum[$value["currency"]] += $value["amount_paid"];
								}
							}
						}
					}
				}
			}
		}
		
		return $sum;
	}
	function getCourses(){
		$db = JFactory::getDBO();
		$sql = "SELECT count(id) as courses FROM `#__guru_program`";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result;
	}
	function getTeachers(){
		$db = JFactory::getDBO();
		$sql = "SELECT count(*) as teachers FROM `#__guru_authors` a, #__users u where a.`userid`=u.`id` and a.`enabled`=1 and u.`block`=0 and u.`activation`=''";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result;
	} 
	function getStudents(){
		$db = JFactory::getDBO();
		$sql = "SELECT count(id) as stud FROM `#__guru_customer`";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result;
	} 
	function getRecentOrders(){
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM `#__guru_order` where status='Paid' order by `order_date` desc limit 0,3";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	function getGivenCertificate(){
		$return = 0;
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM `#__guru_mycertificates` order by `id` desc limit 0,3";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	function getAwardedCertificates(){
		$return = 0;
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM `#__guru_mycertificates`";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		
		if(isset($result) && count($result) > 0){
			$temp = array();
			foreach($result as $key=>$value){
				$unit = $value["course_id"]."-".$value["author_id"]."-".$value["user_id"];
				$temp[$unit] = 0;
			}
			$return = count($temp);
		}
		
		return $return;
	}
	
	function bestSellingCourse(){
		$db = JFactory::getDBO();
		$sql = "SELECT c.`course_id` as idc, count(distinct(c.`userid`)) as frequency FROM `#__guru_buy_courses` c, #__guru_order o, #__users u where o.`id`=c.`order_id` and u.`id`=c.`userid` and o.`userid`=c.`userid` GROUP BY idc order by frequency desc limit 0,3 ";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		
		return $result;
	}
	
	function getAvgCourses(){
		$db = JFactory::getDBO();
		
		$sql = "select distinct(`userid`) from #__guru_buy_courses WHERE userid IN (SELECT id FROM `#__guru_customer` )";
		$db->setQuery($sql);
		$db->query();
		$results = $db->loadColumn();
		if(isset($results) && count($results) > 0){
			$results = count($results);
		}
		else{
			$results = 0;
		}
		
		$sql = "select distinct(`course_id`) from #__guru_buy_courses";
		$db->setQuery($sql);
		$db->query();
		$resultc = $db->loadColumn();
		if(isset($resultc) && count($resultc) > 0){
			$resultc = count($resultc);
		}
		else{
			$resultc = 0;
		}
		
		if(isset($results) && isset($resultc)){
			@$result = @$results / @$resultc;
		}
		return $result;
	}
	function getCurrencyPos(){
		$db = JFactory::getDBO();
		$sql = "SELECT currencypos FROM `#__guru_config` Where id= 1";
		$db->setQuery($sql);
		$db->query();
		$results = $db->loadColumn();
		return $results[0];
	}
	
	function getCurrency(){
		$db = JFactory::getDBO();
		$sql = "SELECT currency FROM `#__guru_config` Where id= 1";
		$db->setQuery($sql);
		$db->query();
		$results = $db->loadColumn();
		return $results[0];
	}
}

?>