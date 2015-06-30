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
jimport ("joomla.aplication.component.model");


class guruAdminModelguruConfig extends JModelLegacy {
	var $_configs = null;
	var $_id = null;

	function __construct () {
		parent::__construct();
		$this->_id = 1;
	}

	function getConfigs() {
		if (empty ($this->_configs)) {
			$this->_configs = $this->getTable("guruConfig");
			$this->_configs->load($this->_id);
		}		
		
		//start currency drop-down
		$currencyOptions=array();
		$currencyOptions[]=JHTML::_("select.option","USD","U.S. Dollar");
		$currencyOptions[]=JHTML::_("select.option","AUD","Australian Dollar");
		$currencyOptions[]=JHTML::_("select.option","CAD","Canadian Dollar");
		$currencyOptions[]=JHTML::_("select.option","CHF","Swiss Franc");
		$currencyOptions[]=JHTML::_("select.option","CZK","Czech Koruna");		
		$currencyOptions[]=JHTML::_("select.option","DKK","Danish Krone");	
		$currencyOptions[]=JHTML::_("select.option","EUR","Euro");			
		$currencyOptions[]=JHTML::_("select.option","GBP","Pound Sterling");				
		$currencyOptions[]=JHTML::_("select.option","HKD","Hong Kong Dollar");			
		$currencyOptions[]=JHTML::_("select.option","HUF","Hungarian Forint");			
		$currencyOptions[]=JHTML::_("select.option","JPY","Japanese Yen");				
		$currencyOptions[]=JHTML::_("select.option","NOK","Norwegian Krone");				
		$currencyOptions[]=JHTML::_("select.option","NZD","New Zealand Dollar");	
		$currencyOptions[]=JHTML::_("select.option","PLN","Polish Zloty");		
		$currencyOptions[]=JHTML::_("select.option","SEK","Swedish Krona");			
		$currencyOptions[]=JHTML::_("select.option","SGD","Singapore Dollar");
		$currencyOptions[]=JHTML::_("select.option","BRL","Brazilian Real");	
		$currencyOptions[]=JHTML::_("select.option","MXN","Peso Mexicano");	
		$currencyOptions[]=JHTML::_("select.option","INR","Indian Rupee");	
		$currencyOptions[]=JHTML::_("select.option","ZAR","South African Rand");
		$currencyOptions[]=JHTML::_("select.option","IDR","Indonesian Rupiah");	
		$currencyOptions[]=JHTML::_("select.option","MYR","Malaysian Ringgit");	
		$currencyOptions[]=JHTML::_("select.option","XOF","African CFA Franc");	
		$currencyOptions[]=JHTML::_("select.option","BGN","Bulgarian lev");	
		$currencyOptions[]=JHTML::_("select.option","VND","Vietnamese Dong");	
		$currencyOptions[]=JHTML::_("select.option","CNY","Chinese Yuan");	
		$currencyOptions[]=JHTML::_("select.option","IR","Iranian Rial");
		
		$this->_configs->lists['currency']=JHTML::_("select.genericlist",$currencyOptions,"currency","size=1","value","text",$this->_configs->currency);		
		$emails = $this->getEmails();
		
		//start date format list
		$dateOptions[]=JHTML::_('select.option','m/d/Y H:i:s','mm/dd/yyyy hh:mm:ss');
		$dateOptions[]=JHTML::_('select.option','Y-m-d H:i:s','yyyy-mm-dd hh:mm:ss');
		$dateOptions[]=JHTML::_('select.option','d-m-Y','dd-mm-yyyy');
		$dateOptions[]=JHTML::_('select.option','m/d/Y','mm/dd/yyyy');	
		$dateOptions[]=JHTML::_('select.option','Y-m-d','yyyy-mm-dd');			
					
		$this->_configs->lists['date_format']  =  JHTML::_( 'select.genericlist', $dateOptions, 'datetype', 'class="inputbox" size="1"','value', 'text', $this->_configs->datetype);	
		$targetOptions[]=JHTML::_('select.option','0',JText::_('GURU_SAME_WINDOW'));
		$targetOptions[]=JHTML::_('select.option','1',JText::_('GURU_NEW_WINDOW'));
			
		$this->_configs->lists['target']  =  JHTML::_( 'select.genericlist', $targetOptions, 'open_target', 'class="inputbox" size="1"','value', 'text', $this->_configs->open_target);
		
		$lesson_values_string = $this->_configs->lesson_window_size;
		$lesson_values_array = explode("x", $lesson_values_string);
		$lesson_height = $lesson_values_array["0"];
		$lesson_width = $lesson_values_array["1"];
		$this->_configs->lists["lesson_window_size"] = '
		<div>
			<div style="float:left;">
				<input type="text" size="5" name="lesson_window_size_width" value="'.$lesson_width.'" />
			</div>
			<div style="float:left; margin-right:-2px;"> &nbsp; 
				x &nbsp;
			</div>
			<div style="float:left;">
				<input type="text" size="5" name="lesson_window_size_height" value="'.$lesson_height.'" />
			</div>
			<div style="float:left;"> &nbsp; 
				('.JText::_("GURU_WIDTH").' x '.JText::_("GURU_HEIGHT").')&nbsp;
			</div>
		</div>
		';
		
		$lesson_values_string = $this->_configs->lesson_window_size_back;
		$lesson_values_array = explode("x", $lesson_values_string);
		$lesson_height = $lesson_values_array["0"];
		$lesson_width = $lesson_values_array["1"];
		$back_size_type = $this->_configs->back_size_type;
		$checked_joomla = $back_size_type == "0" ? ' checked="checked" ' : "";
		$checked_user = $back_size_type == "1" ? ' checked="checked" ' : "";
		
		$this->_configs->lists["lesson_window_size_back"] = '<div ><input style="width:18px!important;" type="radio" name="back_size_type" value="0" '.$checked_joomla.'>'.JText::_("GURU_BACK_SIZE_TYPE").'</div>
																			<div style="float:left;">
																				<input style="width:18px!important;" type="radio" name="back_size_type" value="1" '.$checked_user.'>
																				<input type="text" size="5" name="lesson_window_size_back_width" value="'.$lesson_width.'" />
																			</div>
																			<div style="float:left; margin-right:-2px;">&nbsp; 
																				x &nbsp;
																			</div>
																			<div style="float:left;">
																				<input type="text" size="5" name="lesson_window_size_back_height" value="'.$lesson_height.'" />
																			</div>
																			<div style="float:left;"> &nbsp;
																				('.JText::_("GURU_WIDTH").' x '.JText::_("GURU_HEIGHT").') &nbsp;
																			</div>
																		</div>';
		
		$default_video_string = $this->_configs->default_video_size ;
		$default_video_array = explode("x", $default_video_string);
		$default_video_height = $default_video_array["0"];
		$default_video_width = $default_video_array["1"];
		$this->_configs->lists["lesson_default_video_size"] = '
		<div>
			<div style="float:left;">
				<input type="text" size="5" name="default_video_size_width" value="'.$default_video_width.'" />
			</div>
			<div style="float:left;">&nbsp; 
				x &nbsp;
			</div>
			<div style="float:left;">
				<input type="text" size="5" name="default_video_size_height" value="'.$default_video_height.'" />
			</div>
			<div style="float:left;"> &nbsp; 
				('.JText::_("GURU_WIDTH").' x '.JText::_("GURU_HEIGHT").') &nbsp;
			</div>
		</div>
		';
		
		$hour_format[]=JHTML::_('select.option', '12', JText::_("GURU_TWELVE"));
		$hour_format[]=JHTML::_('select.option', '24', JText::_("GURU_TWENTY_FOUR"));
		$this->_configs->lists['hour_format']  =  JHTML::_( 'select.genericlist', $hour_format, 'hour_format', 'class="inputbox" size="1"','value', 'text', $this->_configs->hour_format);
		
		return $this->_configs;
	}
	function getMultipleProfileJomSocial(){
		$db = JFactory::getDBO();
		$ask = "SELECT id, name FROM `#__community_profiles` where published=1";
		$db->setQuery( $ask );
		$result = $db->loadObjectList();
		return $result;
	}

	function getAdmins() {
		$db = JFactory::getDBO();
		$sql = "select u.`id`, u.`name` from #__users u, #__user_usergroup_map ugm where u.`id`=ugm.`user_id` and ugm.`group_id`='8'";
		$db->setQuery($sql);
		$db->query();
		$admins = $db->loadObjectList();
		return $admins;
	}
	function getEmails() {
		$db = JFactory::getDBO();
		$ask = "SELECT * FROM `#__guru_emails` ";
		$db->setQuery( $ask );
		$where = $db->loadObjectList();
		return $where;
	}
	function getJsMProfile(){
		$db = JFactory::getDBO();
		$sql = "select count(id) from #__community_profiles";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		return $count["0"];	
	}

	function store () {
		jimport('joomla.filesystem.folder');
		$item = $this->getTable('guruConfig');
		$tab = JRequest::getVar("tab", "0");
		
		if($tab == "3"){
			// we update the CSS FILE - begin
			$css=JPATH_SITE."/components/com_guru/css/trainer_style.css" ;
			@chmod($css,0777);
			$fp=@fopen($css,"w");
			fwrite($fp,stripslashes($_POST['css_file']));
			fclose($fp);
			// we update the CSS FILE - end
		}

		$imagepath = str_replace("/administrator","",JPATH_BASE);
		
		$imagesin=JRequest::getVar("imagesin","","post","string");
		if($imagesin!=''){
			JFolder::create($imagepath."/".$imagesin,"0755");
	    }
		
		$videoin=JRequest::getVar("videoin","","post","string");
		if($videoin!=''){
			JFolder::create($imagepath."/".$videoin,"0755");
	    }		
		
		$audioin=JRequest::getVar("audioin","","post","string");
		if($audioin!=''){
			JFolder::create($imagepath."/".$audioin,"0755");
	    }	
		
		$docsin=JRequest::getVar("docsin","","post","string");
		if($docsin!=''){
			JFolder::create($imagepath."/".$docsin,"0755");
	    }			
	
		$data = JRequest::get('post');			
		$ctgslayout = JRequest::getVar("ctgslayout", "");
		$ctgscols = JRequest::getVar("ctgscols", "");
		$ctgs_image_size = JRequest::getVar("ctgs_image_size", "");
		$ctgs_image_size_type = JRequest::getVar("ctgs_image_size_type", "");
		$ctgs_image_alignment = JRequest::getVar("ctgs_image_alignment", "");
		$ctgs_wrap_image = JRequest::getVar("ctgs_wrap_image", "");
		$ctgs_description_length = JRequest::getVar("ctgs_description_length", "");
		$ctgs_description_type = JRequest::getVar("ctgs_description_type", "");
		$ctgs_description_mode = JRequest::getVar("ctgs_description_mode", "");
		$ctgs_description_alignment = JRequest::getVar("ctgs_description_alignment", "");
		$ctgs_read_more = JRequest::getVar("ctgs_read_more", "");
		$ctgs_read_more_align = JRequest::getVar("ctgs_read_more_align", "");
		$ctgs_show_empty_catgs = JRequest::getVar("ctgs_show_empty_catgs", "");
		$ctgspage_array = array("ctgslayout" => $ctgslayout, "ctgscols" => $ctgscols, "ctgs_image_size" => $ctgs_image_size, "ctgs_image_size_type" => $ctgs_image_size_type, "ctgs_image_alignment" => $ctgs_image_alignment, "ctgs_wrap_image" => $ctgs_wrap_image, "ctgs_description_length" => $ctgs_description_length, "ctgs_description_type" =>$ctgs_description_type, "ctgs_description_mode" => $ctgs_description_mode, "ctgs_description_alignment"=> $ctgs_description_alignment, "ctgs_read_more" => $ctgs_read_more, "ctgs_read_more_align" => $ctgs_read_more_align, "ctgs_show_empty_catgs" => $ctgs_show_empty_catgs);
		$data["ctgspage"] = json_encode($ctgspage_array);
		
		$ctgs_page_title = JRequest::getVar("ctgs_page_title", "");
		$ctgs_categ_name = JRequest::getVar("ctgs_categ_name", "");
		$ctgs_image = JRequest::getVar("ctgs_image", "");
		$ctgs_description = JRequest::getVar("ctgs_description", "");
		$ctgs_st_read_more = JRequest::getVar("ctgs_st_read_more", "");
		$st_ctgspage_array = array("ctgs_page_title" => $ctgs_page_title, "ctgs_categ_name" => $ctgs_categ_name, "ctgs_image" => $ctgs_image, "ctgs_description" => $ctgs_description, "ctgs_st_read_more" => $ctgs_st_read_more);
		$data["st_ctgspage"] = json_encode($st_ctgspage_array);
		//-----------------------------------------------------
		$ctg_image_size = JRequest::getVar("ctg_image_size", "");
		$ctg_image_size_type = JRequest::getVar("ctg_image_size_type", "");
		$ctg_image_alignment = JRequest::getVar("ctg_image_alignment", "");
		$ctg_wrap_image = JRequest::getVar("ctg_wrap_image", "");
		$ctg_description_length = JRequest::getVar("ctg_description_length", "");
		$ctg_description_type = JRequest::getVar("ctg_description_type", "");
		$ctg_description_mode = JRequest::getVar("ctg_description_mode", "");
		$ctg_description_alignment = JRequest::getVar("ctg_description_alignment", "");
		$ctgpage_array = array("ctg_image_size" => $ctg_image_size, "ctg_image_size_type" => $ctg_image_size_type, "ctg_image_alignment" => $ctg_image_alignment, "ctg_wrap_image" => $ctg_wrap_image, "ctg_description_length" => $ctg_description_length, "ctg_description_mode" => $ctg_description_mode, "ctg_description_type" => $ctg_description_type, "ctg_description_alignment" => $ctg_description_alignment);
		$data["ctgpage"] = json_encode($ctgpage_array);
		
		$ctg_name = JRequest::getVar("ctg_name", "");
		$ctg_image = JRequest::getVar("ctg_image", "");
		$ctg_description = JRequest::getVar("ctg_description", "");
		$ctg_sub_title = JRequest::getVar("ctg_sub_title", "");
		$st_ctgpage_array = array("ctg_name" => $ctg_name, "ctg_image" => $ctg_image, "ctg_description" => $ctg_description, "ctg_sub_title" => $ctg_sub_title);
		$data["st_ctgpage"] = json_encode($st_ctgpage_array);
		//-----------------------------------------------------
		$courseslayout = JRequest::getVar("courseslayout", "");
		$coursescols = JRequest::getVar("coursescols", "");
		$courses_image_size = JRequest::getVar("courses_image_size", "");
		$courses_image_size_type = JRequest::getVar("courses_image_size_type", "");
		$courses_image_alignment = JRequest::getVar("courses_image_alignment", "");
		$courses_wrap_image = JRequest::getVar("courses_wrap_image", "");
		$courses_description_length = JRequest::getVar("courses_description_length", "");
		$courses_description_type = JRequest::getVar("courses_description_type", "");
		$courses_description_mode = JRequest::getVar("courses_description_mode", "");
		$courses_description_alignment = JRequest::getVar("courses_description_alignment", "");
		$courses_read_more = JRequest::getVar("courses_read_more", "");
		$courses_read_more_align = JRequest::getVar("courses_read_more_align", "");
		$psgspage_array = array("courseslayout" => $courseslayout, "coursescols" => $coursescols, "courses_image_size" => $courses_image_size, "courses_image_size_type" => $courses_image_size_type, "courses_image_alignment" => $courses_image_alignment, "courses_wrap_image" => $courses_wrap_image, "courses_description_length" => $courses_description_length, "courses_description_type" => $courses_description_type, "courses_description_mode" => $courses_description_mode, "courses_description_alignment" => $courses_description_alignment, "courses_read_more" => $courses_read_more, "courses_read_more_align" => $courses_read_more_align);
		$data["psgspage"] = json_encode($psgspage_array);
		
		$courses_page_title = JRequest::getVar("courses_page_title", "");
		$courses_name = JRequest::getVar("courses_name", "");
		$courses_image = JRequest::getVar("courses_image", "");
		$courses_description = JRequest::getVar("courses_description", "");
		$courses_st_read_more = JRequest::getVar("courses_st_read_more", "");
		$st_psgspage_array = array("courses_page_title" => $courses_page_title, "courses_name" => $courses_name, "courses_image" => $courses_image, "courses_description" => $courses_description, "courses_st_read_more" => $courses_st_read_more);
		$data["st_psgspage"] = json_encode($st_psgspage_array);
		//-----------------------------------------------------
		$course_image_size = JRequest::getVar("course_image_size", "");
		$course_image_size_type = JRequest::getVar("course_image_size_type", "");
		$course_image_alignment = JRequest::getVar("course_image_alignment", "");
		$course_wrap_image = JRequest::getVar("course_wrap_image", "");
		$show_course_image = JRequest::getVar("show_course_image", "");
		$show_course_studentamount = JRequest::getVar("show_course_studentamount", "");
		$course_author_name_show = JRequest::getVar("course_author_name_show", "");
		$course_released_date = JRequest::getVar("course_released_date", "");
		$course_level = JRequest::getVar("course_level", "");
		$course_price = JRequest::getVar("course_price", "");
		$course_price_type = JRequest::getVar("course_price_type", "");
		$course_table_contents = JRequest::getVar("course_table_contents", "");
		$course_description_show = JRequest::getVar("course_description_show", "");
		$course_tab_price = JRequest::getVar("course_tab_price", "");
		$course_author = JRequest::getVar("course_author", "");
		$course_requirements = JRequest::getVar("course_requirements", "");
		$course_buy_button = JRequest::getVar("course_buy_button", "");
		$course_buy_button_location = JRequest::getVar("course_buy_button_location", "");
		$show_all_cloase_all = JRequest::getVar("show_all_cloase_all", "");
		$psgpage_array = array("course_image_size" => $course_image_size, "course_image_size_type" => $course_image_size_type, "course_image_alignment" => $course_image_alignment, "course_wrap_image" => $course_wrap_image, "course_author_name_show" => $course_author_name_show, "course_released_date" => $course_released_date, "course_level" => $course_level, "course_price" => $course_price, "course_price_type" => $course_price_type, "course_table_contents" => $course_table_contents, "course_description_show" => $course_description_show, "course_tab_price" => $course_tab_price, "course_author" => $course_author, "course_requirements" => $course_requirements, "course_buy_button" => $course_buy_button, "course_buy_button_location" => $course_buy_button_location, "show_course_image" => $show_course_image, "show_course_studentamount" => $show_course_studentamount,"show_all_cloase_all" => $show_all_cloase_all);
		$data["psgpage"] = json_encode($psgpage_array);
		
		$course_name = JRequest::getVar("course_name", "");
		$course_image = JRequest::getVar("course_image", "");
		$course_top_field_name = JRequest::getVar("course_top_field_name", "");
		$course_top_field_value = JRequest::getVar("course_top_field_value", "");
		$course_tabs_module_name = JRequest::getVar("course_tabs_module_name", "");
		$course_tabs_step_name = JRequest::getVar("course_tabs_step_name", "");
		$course_description = JRequest::getVar("course_description", "");
		$course_price_field_name = JRequest::getVar("course_price_field_name", "");
		$course_price_field_value = JRequest::getVar("course_price_field_value", "");
		$course_author_name = JRequest::getVar("course_author_name", "");
		$course_author_bio = JRequest::getVar("course_author_bio", "");
		$course_author_image = JRequest::getVar("course_author_image", "");
		$course_req_field_name = JRequest::getVar("course_req_field_name", "");
		$course_req_field_value = JRequest::getVar("course_req_field_value", "");
		$course_other_button = JRequest::getVar("course_other_button", "");
		$course_other_background = JRequest::getVar("course_other_background", "");
		$st_psgpage_array = array("course_name" => $course_name, "course_image" => $course_image, "course_top_field_name" => $course_top_field_name, "course_top_field_value" => $course_top_field_value, "course_tabs_module_name" => $course_tabs_module_name, "course_tabs_step_name" => $course_tabs_step_name, "course_description" => $course_description, "course_price_field_name" => $course_price_field_name, "course_price_field_value" => $course_price_field_value, "course_author_name" => $course_author_name, "course_author_bio" => $course_author_bio, "course_author_image" => $course_author_image, "course_req_field_name" => $course_req_field_name, "course_req_field_value" => $course_req_field_value, "course_other_button" => $course_other_button, "course_other_background" => $course_other_background);
		$data["st_psgpage"] = json_encode($st_psgpage_array);
		//-----------------------------------------------------
		$authorslayout = JRequest::getVar("authorslayout", "");
		$authorscols = JRequest::getVar("authorscols", "");
		$authors_image_size = JRequest::getVar("authors_image_size", "");
		$authors_image_size_type = JRequest::getVar("authors_image_size_type", "");
		$authors_image_alignment = JRequest::getVar("authors_image_alignment", "");
		$authors_wrap_image = JRequest::getVar("authors_wrap_image", "");
		$authors_description_length = JRequest::getVar("authors_description_length", "");
		$authors_description_mode = JRequest::getVar("authors_description_mode", "");
		$authors_description_type = JRequest::getVar("authors_description_type", "");
		$authors_description_alignment = JRequest::getVar("authors_description_alignment", "");
		$authors_read_more = JRequest::getVar("authors_read_more", "");
		$authors_read_more_align = JRequest::getVar("authors_read_more_align", "");
		$authorspage_array = array("authorslayout" => $authorslayout, "authorscols" => $authorscols, "authors_image_size" => $authors_image_size, "authors_image_size_type" => $authors_image_size_type, "authors_image_alignment" => $authors_image_alignment, "authors_wrap_image" => $authors_wrap_image, "authors_description_length" => $authors_description_length, "authors_description_type" => $authors_description_type, "authors_description_mode" => $authors_description_mode, "authors_description_alignment" => $authors_description_alignment, "authors_read_more" => $authors_read_more, "authors_read_more_align" => $authors_read_more_align);
		$data["authorspage"] = json_encode($authorspage_array);
				
		$authors_page_title = JRequest::getVar("authors_page_title", "");
		$authors_name = JRequest::getVar("authors_name", "");
		$authors_image = JRequest::getVar("authors_image", "");
		$authors_description = JRequest::getVar("authors_description", "");
		$authors_st_read_more = JRequest::getVar("authors_st_read_more", "");
		$st_authorspage_array = array("authors_page_title" => $authors_page_title, "authors_name" => $authors_name, "authors_image" => $authors_image, "authors_description" => $authors_description, "authors_st_read_more" => $authors_st_read_more);
		$data["st_authorspage"] = json_encode($st_authorspage_array);
		//-----------------------------------------------------
		$author_image_size = JRequest::getVar("author_image_size", "");
		$author_image_size_type = JRequest::getVar("author_image_size_type", "");
		$author_image_alignment = JRequest::getVar("author_image_alignment", "");
		$author_wrap_image = JRequest::getVar("author_wrap_image", "");
		$author_description_length = JRequest::getVar("author_description_length", "");
		$author_description_type = JRequest::getVar("author_description_type", "");
		$author_description_alignment = JRequest::getVar("author_description_alignment", "");
		$authorpage_array = array("author_image_size" => $author_image_size, "author_image_size_type" => $author_image_size_type, "author_image_alignment" => $author_image_alignment, "author_wrap_image" => $author_wrap_image, "author_description_length" => $author_description_length, "author_description_type" => $author_description_type, "author_description_alignment" => $author_description_alignment);
		$data["authorpage"] = json_encode($authorpage_array);
		
		$author_name = JRequest::getVar("author_name", "");
		$author_image = JRequest::getVar("author_image", "");
		$author_description = JRequest::getVar("author_description", "");
		$author_st_read_more = JRequest::getVar("author_st_read_more", "");
		//add new columns for teacher page in admin(confis->tab8)
		$teacher_aprove = JRequest::getVar("teacher_aprove", "");
		$teacher_group = JRequest::getVar("teacher_group", "");
		$teacher_add_media = JRequest::getVar("teacher_add_media", "");
		$teacher_edit_media = JRequest::getVar("teacher_edit_media", ""); 
		$teacher_add_courses = JRequest::getVar("teacher_add_courses", "");
		$teacher_approve_courses = JRequest::getVar("teacher_approve_courses", ""); 
		$teacher_edit_courses = JRequest::getVar("teacher_edit_courses", "");
		$teacher_add_quizzesfe = JRequest::getVar("teacher_add_quizzesfe", "");
		$teacher_edit_quizzesfe = JRequest::getVar("teacher_edit_quizzesfe", "");
		$teacher_add_students =  JRequest::getVar("teacher_add_students", "");
		$teacher_edit_students = JRequest::getVar("teacher_edit_students", "");
		//----------end new columns-----------
		$st_authorpage_array = array("author_name" => $author_name, "author_image" => $author_image, "author_description" => $author_description, "author_st_read_more" => $author_st_read_more, "teacher_aprove"=>$teacher_aprove, "teacher_group"=>$teacher_group, "teacher_add_media"=>$teacher_add_media, "teacher_edit_media"=>$teacher_edit_media, "teacher_add_courses"=>$teacher_add_courses, "teacher_approve_courses"=>$teacher_approve_courses, "teacher_edit_courses"=>$teacher_edit_courses, "teacher_add_quizzesfe"=>$teacher_add_quizzesfe, "teacher_edit_quizzesfe"=>$teacher_edit_quizzesfe, "teacher_add_students"=>$teacher_add_students, "teacher_edit_students"=>$teacher_edit_students );
		$data["st_authorpage"] = json_encode($st_authorpage_array);
		//-----------------------------------------------------
		
		$data['st_donecolor'] = '#'.$data['st_donecolor'];
		$data['st_notdonecolor'] = '#'.$data['st_notdonecolor'];
		$data['st_txtcolor'] = '#'.$data['st_txtcolor'];
		
		$database =  JFactory::getDBO();				
		
		$for_save = array("id"=>"1", "option"=>"com_guru", "controller"=>"guruConfigs");
		
		
		
		//save only values from current tab
		if($tab == "0"){
			$for_save["currency"] = $data["currency"];
			$for_save["datetype"] = $data["datetype"];
			$for_save["hour_format"] = $data["hour_format"];
			$for_save["open_target"] = $data["open_target"];
			$for_save["lesson_window_size_back"] = intval($data["lesson_window_size_back_height"])."x".intval($data["lesson_window_size_back_width"]);
			$for_save["lesson_window_size"] = intval($data["lesson_window_size_height"])."x".intval($data["lesson_window_size_width"]);
			$for_save["default_video_size"] = intval($data["default_video_size_height"])."x".intval($data["default_video_size_width"]);
			$for_save["notification"] = $data["notification"];
			$for_save["show_bradcrumbs"] = $data["show_bradcrumbs"];
			$for_save["show_powerd"] = $data["show_powerd"];
			$for_save["guru_ignore_ijseo"] = $data["guru_ignore_ijseo"];
			$for_save["student_group"] = $data["student_group"];
			$for_save["currencypos"] = $data["currencypos"];
			$for_save["back_size_type"] = intval($data["back_size_type"]);
			$for_save["guru_turnoffjq"] = intval($data["guru_turnoffjq"]);
			$for_save["show_bootstrap"] = intval($data["show_bootstrap"]);
			$for_save["guru_turnoffbootstrap"] = intval($data["guru_turnoffbootstrap"]);
			$for_save["invoice_issued_by"] = JRequest::getVar('invoice_issued_by', '', 'post', 'string', JREQUEST_ALLOWRAW);
		}		
		elseif($tab == "1"){
			$for_save["imagesin"] = $data["imagesin"];
			$for_save["videoin"] = $data["videoin"];
			$for_save["audioin"] = $data["audioin"];
			$for_save["docsin"] = $data["docsin"];
			$for_save["filesin"] = $data["filesin"];
		}
		elseif($tab == "2"){
			$for_save["ctgspage"] = $data["ctgspage"];
			$for_save["ctgpage"] = $data["ctgpage"];
			$for_save["psgspage"] = $data["psgspage"];
			$for_save["psgpage"] = $data["psgpage"];
			$for_save["authorspage"] = $data["authorspage"];
			$for_save["authorpage"] = $data["authorpage"];
			$for_save["course_lesson_release"] = $data["course_lesson_release"];

		}
		elseif($tab == "3"){
			$for_save["st_ctgspage"] = $data["st_ctgspage"];
			$for_save["st_ctgpage"] = $data["st_ctgpage"];
			$for_save["st_psgspage"] = $data["st_psgspage"];
			$for_save["st_psgpage"] = $data["st_psgpage"];
			$for_save["st_authorspage"] = $data["st_authorspage"];
			$for_save["st_authorpage"] = $data["st_authorpage"];		
		}
		elseif($tab == "4"){
			if(($data["st_width"] <= 0) && ($data["st_width"] <= 0)){
				return false;										  
				
			}
			
			$for_save["progress_bar"] = $data["progress_bar"];
			$for_save["st_donecolor"] = $data["st_donecolor"];
			$for_save["st_notdonecolor"] = $data["st_notdonecolor"];
			$for_save["st_txtcolor"] = $data["st_txtcolor"];
			$for_save["st_width"] = $data["st_width"];
			$for_save["st_height"] = $data["st_height"];		
		}
		elseif($tab == "5"){
			$for_save["fromname"] = $data["fromname"];
			$for_save["fromemail"] = $data["fromemail"];
			$for_save["admin_email"] = implode(",", $data["cid"]);			
			
			$template_emails["approve_subject"] = JRequest::getVar('approve_subject', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["approve_body"] = JRequest::getVar('approve_body', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["unapprove_subject"] = JRequest::getVar('unapprove_subject', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["unapprove_body"] = JRequest::getVar('unapprove_body', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["ask_approve_subject"] = JRequest::getVar('ask_approve_subject', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["ask_approve_body"] = JRequest::getVar('ask_approve_body', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["ask_teacher_subject"] = JRequest::getVar('ask_teacher_subject', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["ask_teacher_body"] = JRequest::getVar('ask_teacher_body', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["new_teacher_subject"] = JRequest::getVar('new_teacher_subject', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["new_teacher_body"] = JRequest::getVar('new_teacher_body', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["approved_teacher_subject"] = JRequest::getVar('approved_teacher_subject', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["approved_teacher_body"] = JRequest::getVar('approved_teacher_body', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["pending_teacher_subject"] = JRequest::getVar('pending_teacher_subject', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["pending_teacher_body"] = JRequest::getVar('pending_teacher_body', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["approve_order_subject"] = JRequest::getVar('approve_order_subject', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["approve_order_body"] = JRequest::getVar('approve_order_body', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["pending_order_subject"] = JRequest::getVar('pending_order_subject', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$template_emails["pending_order_body"] = JRequest::getVar('pending_order_body', '', 'post', 'string', JREQUEST_ALLOWRAW);
			
			$for_save["template_emails"] = json_encode($template_emails);
		}
		elseif($tab == "6"){
			$item->content_selling = JRequest::getVar('content_selling',"","post","string",JREQUEST_ALLOWRAW);
		}	
		elseif($tab == "7"){
			$for_save["gurujomsocialregstudent"] = $data["gurujomsocialregstudent"];
			$for_save["gurujomsocialregteacher"] = $data["gurujomsocialregteacher"];
			$for_save["gurujomsocialprofilestudent"] = $data["gurujomsocialprofilestudent"];
			$for_save["gurujomsocialprofileteacher"] = $data["gurujomsocialprofileteacher"];
			$for_save["gurujomsocialregstudentmprof"] = $data["gurujomsocialregstudentmprof"];
			$for_save["gurujomsocialregteachermprof"] = $data["gurujomsocialregteachermprof"];
		}	
		elseif($tab == "8"){
			$for_save["st_authorpage"] = $data["st_authorpage"];
			$for_save["course_is_free_show"] = intval($data["course_is_free_show"]);

		}
		elseif($tab == "9"){
			$for_save["terms_cond_student"] = $data["terms_cond_student"];
			$for_save["terms_cond_teacher"] = $data["terms_cond_teacher"];
			$for_save["terms_cond_student_content"] = JRequest::getVar('terms_cond_student_content', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$for_save["terms_cond_teacher_content"] = JRequest::getVar('terms_cond_teacher_content', '', 'post', 'string', JREQUEST_ALLOWRAW);
		}	
			
		if (!$item->bind($for_save)){
			return JError::raiseError( 500, $database->getErrorMsg() );
			return false;

		} 
		if (!$item->check()) {
			return JError::raiseError( 500, $database->getErrorMsg() );
			return false;

		}
    
		if (!$item->store()) {
			return JError::raiseError( 500, $database->getErrorMsg() );
			return false;
		}		
		return true;
	}	
};
?>