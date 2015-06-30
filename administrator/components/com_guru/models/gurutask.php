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


class guruAdminModelguruTask extends JModelLegacy {
	var $_attributes;
	var $_attribute;
	var $_id = null;
	var $_total = 0;
	var $_pagination = null;

	function __construct () {
		parent::__construct();
		$cids = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int)$cids[0]);
		global $app, $option;
		
		$app = JFactory::getApplication('administrator');
		$limit = $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$limitstart = $app->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );

		if(JRequest::getVar("limitstart") == JRequest::getVar("old_limit")){
			JRequest::setVar("limitstart", "0");		
			$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
			$limitstart = $app->getUserStateFromRequest($option.'limitstart', 'limitstart', 0, 'int');
		}

		$this->setState('limit', $limit); // Set the limit variable for query later on
		$this->setState('limitstart', $limitstart);
	}

	function setId($id) {
		$this->_id = $id;
		$this->_attribute = null;
	}
	
	public static function getMediaCategoriesName(){
		$db = JFactory::getDBO();
		$sql = "select id, name from #__guru_media_categories";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	
	public static function getMediaType($id){
		$db = JFactory::getDBO();
		$sql = "select type from #__guru_media where id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}	
	
	public static function getAllMediaCategs(){
		$db = JFactory::getDBO();
		$sql = "select id, name from #__guru_media_categories";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	public static function jump_save(){
		$data = JRequest::get('post');
		$db = JFactory::getDBO();
		$pieces=explode("|",$data['selstep']);
		$module_id = $data["jump_mod_id"];
		
		$type_selected = JRequest::getVar("type_selected", "");
		
		if(isset($data['editid'])&&($data['editid']!=0)) {
			$sql="UPDATE `#__guru_jump` SET `text` = '".trim(addslashes($data['jumptext']))."',`jump_step`='".$pieces["0"]."', `module_id1`=".intval($module_id).", `type_selected`='".trim($type_selected)."' WHERE `id` = ".$data['editid']." LIMIT 1 ;";
			$db->setQuery($sql);
			$db->query();
			$ret[]=$data['editid'];
		} 
		else{
			$sql="INSERT INTO `#__guru_jump` (`button` ,`text` ,`jump_step`, `module_id1`, `type_selected`)
				VALUES ('".$pieces[1]."', '".trim(addslashes($data['jumptext']))."', '".$pieces[0]."', ".intval($module_id).", '".trim($type_selected)."');";
			$db->setQuery($sql);
			$db->query();
			if(!isset($last_id)||($last_id==0)){
				$sql="SELECT id FROM `#__guru_jump` ORDER BY id DESC LIMIT 1";
				$db->setQuery($sql);
				$last_id=$db->loadResult($sql);
			}
			$ret[]=$last_id;
		}
		$ret[]=$pieces[1];
		$ret[]=$data['jumptext'];
		
		return $ret;
	}
	
	public static function saveorder_q(){
		$db = JFactory::getDBO();
		foreach($_POST['order_q'] as $key=>$value){
			$sql="UPDATE `#__guru_questions` SET `reorder` = '".$value."' WHERE `id` ='".$key."' LIMIT 1 ;";
			$db->setQuery($sql);
			$db->query();
		}
	}
	
	function getPagination(){
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))	{
			jimport('joomla.html.pagination');
			if (!$this->_total) $this->getlistQuiz();
			$this->_pagination = new JPagination( $this->_total, $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}
	
	function getlistQuiz(){
		$db = JFactory::getDBO();
		$app = JFactory::getApplication('administrator');
		
		$limitstart=$this->getState('limitstart');
		$limit=$this->getState('limit');
		if($limit!=0){
			$limit_cond=" LIMIT ".$limitstart.",".$limit." ";
		}

		$search_text = JRequest::getVar('search_quiz', "");
		$and = "";
		if($search_text!=""){
			$and ="AND name like '%".$search_text."%' ";
		}
		
		$sql = "SELECT count(*) FROM  `#__guru_quiz` WHERE is_final<>1 ".$and." ORDER BY `ordering`";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		$result = @$result["0"];
		$this->_total = intval($result);
		
		$sql = "SELECT * FROM `#__guru_quiz` WHERE is_final<>1 ".$and." ORDER BY `ordering` ".$limit_cond;
		$db->setQuery($sql);
		$db->query();
		
		return $db->loadObjectList();
	}
	
	function getlistaddmedia(){
		$db = JFactory::getDBO();
		$type = JRequest::getVar("type","","get","string");

		$task = JRequest::getVar("task","","get","string");
 		$condition=array();
		
		$sql = "SELECT m.*, mc.name as categ_name FROM `#__guru_media` m LEFT OUTER JOIN `#__guru_media_categories` mc on mc.id=m.category_id where 1=1 ";
		if($type!=""){
			$sql .="AND m.type='".$type."' ";
		}

		if($task != 'addtext'){
			$search_text = JRequest::getVar('search_text', "null");
			if($search_text == "null"){
				//$_SESSION['search_text_tskmed'] = JRequest::getVar('search_text');
				if(isset($_SESSION["search_value"])){
					$search_text = $_SESSION["search_value"];
				}
			}
			
			if($search_text != "null" && $search_text != ""){
				$sql = $sql." AND m.name LIKE '%".addslashes(JRequest::getVar('search_text'))."%' ";
				$_SESSION["search_value"] = $search_text;
			}

			if(isset($_POST['filter_type'])){
				if($_POST['filter_type']!='' && $_POST['filter_type'] != NULL) {
					$sql.= " AND m.type='".$_POST['filter_type']."'";
				}
				elseif($_POST['filter_type'] == NULL){
					unset($_SESSION['filter_type_tskmed']);
				}
			}
			
			if(isset($_POST['filter_status'])&&($_POST['filter_status']!='')){
				if($_POST['filter_status']=='1') {
					$sql.= " AND m.published=1 ";
				} elseif($_POST['filter_status']=='2') {
					$sql.= " AND m.published=0 ";
				}
			} elseif(isset($_POST['filter2'])&&($_POST['filter2']!='')&&($_POST['filter2']!=0)){
				if($_POST['filter2']=='1') {
					$sql.= " AND m.published=1 ";
				} elseif($_POST['filter2']=='2') {
					$sql.= " AND m.published=0 ";
				}		
			} elseif (isset($_SESSION['filter_status_tskmed'])&&($_SESSION['filter_status_tskmed']!='')){
				if($_SESSION['filter_status_tskmed']=='1') {
					$sql.= " AND m.published=1 ";
				} elseif($_SESSION['filter_status_tskmed']=='2') {
					$sql.= " AND m.published=0 ";
				}
			}
			if(isset($_POST['filter_status'])) {
				$_SESSION['filter_status_tskmed']=$_POST['filter_status'];
			} elseif(isset($_POST['filter2'])){
				$_SESSION['filter_status_tskmed']=$_POST['filter2'];
			}
		}
		
		$media_category = JRequest::getVar("filter_media", "");
		if($media_category == ""){
			$media_category = @$_SESSION["filter_media"];
		}
		elseif($media_category == "-1"){
			$_SESSION["filter_media"] = $media_category;
		}
		
		if($media_category != "" && $media_category != "-1"){
			$sql.= " AND m.category_id=".intval($media_category);
		}
		
		
		$search_text = JRequest::getVar('search_text', "null");
		if($search_text == "null"){
			if(isset($_SESSION["search_value"])){
				$search_text = $_SESSION["search_value"];
			}
		}
		elseif($search_text == ""){
			$_SESSION["search_value"] = "";
		}
		
		if($search_text != "null" && $search_text != ""){
			$sql = $sql." AND m.name LIKE '%".$search_text."%' " ;
			$_SESSION["search_value"] = $search_text;
		}
		
			
		if($task=='addmedia' && $type!="quiz" && $type!="text"){
			$sql.=" AND m.type <> 'text' AND m.type <> 'quiz' ";
		}
		elseif($task=='addmedia' && $type=="quiz"){
			$sql.=" AND m.type='quiz' ";
		}
		else{
			$sql.=" AND m.type='text' ";
		}
		
		$sql.= " order by m.id desc ";
		
		
		$limit_cond=NULL;
	
		$limitstart=$this->getState('limitstart');
		$limit=$this->getState('limit');
		
		if($limit!=0){
			$limit_cond=" LIMIT ".$limitstart.",".$limit." ";
		}
		$db->setQuery($sql.$limit_cond);
		$medias = $db->loadObjectList();
		$this->_total = $this->_getListCount($sql);
       
		if(($this->_total>1)&&(count($medias)==0)){
			$limit_cond=NULL;
			if($limit!=0){
				$limit_cond=" LIMIT 0,".$limit." ";
			}	
			$db->setQuery($sql.$limit_cond);
			$medias=$db->loadObjectList();
		}
		return $medias;
	}

	function getTask() {
		if (empty ($this->_attribute)) { 
			$this->_attribute =$this->getTable("guruTasks");
			$this->_attribute->load($this->_id);
		}
			$data = JRequest::get('post');
			
			if (!$this->_attribute->bind($data)){
				$this->setError($item->getErrorMsg());
				return false;
	
			}
	
			if (!$this->_attribute->check()) {
				$this->setError($item->getErrorMsg());
				return false;
	
			}
		return $this->_attribute;

	}
	
	public static function select_media ($pid, $media_no, $layout=0){
		// m_type = scr_m for media
		$db = JFactory::getDBO();
		if($pid != ""){
			$sql = "SELECT `media_id` FROM `#__guru_mediarel` WHERE `type_id` = ".intval($pid)." AND `type`='scr_m' AND `mainmedia`='".intval($media_no)."' AND `layout` = ".$layout;
			$db->setQuery($sql);
			$db->query();
			$media_id = $db->loadColumn();
		}
		return @$media_id[0];
	}	
	
	public static function select_text ($pid, $text_no = NULL, $layout=0){
		$db = JFactory::getDBO();
		if(isset($text_no)) { $cond=" AND text_no = '".intval($text_no)."'";} else { $cond=NULL;}
		if($pid !=""){
			$db->setQuery("SELECT media_id,mainmedia FROM `#__guru_mediarel` WHERE type_id = ".$pid." AND type='scr_t' ".$cond." AND layout=".$layout);
		}
		$db->query();
		$media_obj = $db->loadObject();
		if(isset($media_obj))
			@$media_id = $media_obj->media_id.'$$$$$'.$media_obj->mainmedia;
		else
			$media_id = 0;
		return $media_id;
	}		
	
	public static function parse_media ($id, $layout_id){		
		$db = JFactory::getDBO();
		$helperclass = new guruAdminHelper();
		$sql = "SELECT * FROM #__guru_config LIMIT 1";
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}	
		$configs = $db->loadObject();
		
		if(!isset($media)){
			$media = "";
		}
		
		$default_size = $configs->default_video_size;
		$default_width = "";
		$default_height = "";
		if(trim($default_size) != ""){
			$default_size = explode("x", $default_size);
			$default_width = $default_size["1"];
			$default_height = $default_size["0"];
		}
		if($layout_id!=15){
			$sql = "SELECT * FROM #__guru_media
						WHERE id = ".$id;
			$db->setQuery($sql);
			$db->query();
			$the_media = $db->loadObject();
			$the_media->code=stripslashes($the_media->code);
		}else{
			$sql = "SELECT * FROM #__guru_quiz
						WHERE id = ".$id; 
			$db->setQuery($sql);
			$db->query();
			$the_media = $db->loadObject();
			$the_media->type="quiz";
			$the_media->code="";
		}
	
		$no_plugin_for_code = 0;
		$aheight=0; $awidth=0; $vheight=0; $vwidth=0;
		if($the_media->type=='video'){
			if($the_media->source == 'url' || $the_media->source == 'local'){
				if(($the_media->width == 0 || $the_media->height == 0) && $the_media->option_video_size == 1){
					$vheight=300; 
					$vwidth=400;
				}
				elseif(($the_media->width != 0 && $the_media->height != 0) && $the_media->option_video_size == 1){
					$vheight = $the_media->height; 
					$vwidth = $the_media->width;
				}
				elseif($the_media->option_video_size == 0){
					$vheight = $default_height; 
					$vwidth = $default_width;
				}		
			}
			elseif($the_media->source=='code'){				
				if(($the_media->width == 0 || $the_media->height == 0) && $the_media->option_video_size == 1){
					$begin_tag = strpos($the_media->code, 'width="');
					if($begin_tag!==false){
						$remaining_code = substr($the_media->code, $begin_tag+7, strlen($the_media->code));
						$end_tag = strpos($remaining_code, '"');
						$vwidth = substr($remaining_code, 0, $end_tag);
						$begin_tag = strpos($the_media->code, 'height="');
						if($begin_tag !== false){
							$remaining_code = substr($the_media->code, $begin_tag+8, strlen($the_media->code));
							$end_tag = strpos($remaining_code, '"');
							$vheight = substr($remaining_code, 0, $end_tag);
							$no_plugin_for_code = 1;
						}
						else{
							$vheight=300;
							$vwidth=400;
						}
					}	
					else{
						$vheight=300;
						$vwidth=400;
					}
				}
				elseif(($the_media->width != 0 || $the_media->height != 0) && $the_media->option_video_size == 1){
					$replace_with = 'width="'.$the_media->width.'"';
					$the_media->code = preg_replace('#width="[0-9]+"#', $replace_with, $the_media->code);
					$replace_with = 'height="'.$the_media->height.'"';
					$the_media->code = preg_replace('#height="[0-9]+"#', $replace_with, $the_media->code);
					$replace_with = 'name="width" value="'.$the_media->width.'"';
					$the_media->code = preg_replace('#name="width" value="[0-9]+"#', $replace_with, $the_media->code);
					$replace_with = 'name="height" value="'.$the_media->height.'"';
					$the_media->code = preg_replace('#name="height" value="[0-9]+"#', $replace_with, $the_media->code);	
					$vheight=$the_media->height; $vwidth=$the_media->width;	
				}
				elseif($the_media->option_video_size == 0){
					$replace_with = 'width="'.$default_width.'"';
					$the_media->code = preg_replace('#width="[0-9]+"#', $replace_with, $the_media->code);
					$replace_with = 'height="'.$default_height.'"';
					$the_media->code = preg_replace('#height="[0-9]+"#', $replace_with, $the_media->code);
					
					$replace_with = 'name="width" value="'.$default_width.'"';
					$the_media->code = preg_replace('#value="[0-9]+" name="width"#', $replace_with, $the_media->code);
					$replace_with = 'name="height" value="'.$default_height.'"';
					$the_media->code = preg_replace('#value="[0-9]+" name="height"#', $replace_with, $the_media->code);
					
					$replace_with = 'name="width" value="'.$default_width.'"';
					$the_media->code = preg_replace('/name="width" value="[0-9]+"/', $replace_with, $the_media->code);
					$replace_with = 'name="height" value="'.$default_height.'"';
					$the_media->code = preg_replace('/name="height" value="[0-9]+"/', $replace_with, $the_media->code);
					
					$vheight = $default_height;
					$vwidth = $default_width;
				}
			}	
		}		
		elseif($the_media->type=='audio')
				{
					if ($the_media->source=='url' || $the_media->source=='local')
						{	
							if ($the_media->width == 0 || $the_media->height == 0) 
								{
									$aheight=20; $awidth=300;
								}
							else
								{
									$aheight=$the_media->height; $awidth=$the_media->width;
								}
						}		
					elseif ($the_media->source=='code')
						{
							if ($the_media->width == 0 || $the_media->height == 0) 
								{
									$begin_tag = strpos($the_media->code, 'width="');
									if ($begin_tag!==false)
										{
											$remaining_code = substr($the_media->code, $begin_tag+7, strlen($the_media->code));
											$end_tag = strpos($remaining_code, '"');
											$awidth = substr($remaining_code, 0, $end_tag);
											
											$begin_tag = strpos($the_media->code, 'height="');
											if ($begin_tag!==false)
												{
													$remaining_code = substr($the_media->code, $begin_tag+8, strlen($the_media->code));
													$end_tag = strpos($remaining_code, '"');
													$aheight = substr($remaining_code, 0, $end_tag);
													$no_plugin_for_code = 1;
												}
											else
												{$aheight=20; $awidth=300;}	
										}	
									else
										{$aheight=20; $awidth=300;}							
								}
							else	
								{					
									$replace_with = 'width="'.$the_media->width.'"';
									$the_media->code = preg_replace('#width="[0-9]+"#', $replace_with, $the_media->code);
									$replace_with = 'height="'.$the_media->height.'"';
									$the_media->code = preg_replace('#height="[0-9]+"#', $replace_with, $the_media->code);
									$aheight=$the_media->height; $awidth=$the_media->width;
								}
						}	
				}	
		
		$parts=explode(".", @$the_media->local);
		$extension=strtolower($parts[count($parts)-1]);
		
		if($the_media->type=='video' || $the_media->type=='audio'){
			if($the_media->type=='video' && $extension=="avi"){
				$media = '<object width="'.$vwidth.'" height="'.$vheight.'" type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" id="MediaPlayer1">
<param value="'.JURI::root().$configs->videoin."/".$the_media->local.'" name="fileName">
<param value="true" name="animationatStart">
<param value="true" name="transparentatStart">
<param value="true" name="autoStart">
<param value="true" name="showControls">
<param value="10" name="Volume">
<param value="false" name="autoplay">
<embed width="'.$vwidth.'" height="'.$vheight.'" type="video/x-msvideo" src="'.JURI::root().$configs->videoin."/".$the_media->local.'" name="plugin">
</object>';
				/*$media = '<object id="MediaPlayer1" CLASSID="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" type="application/x-oleobject" width="'.$vwidth.'" height="'.$vheight.'">
<param name="fileName" value="'.JURI::root().$configs->videoin."/".$the_media->local.'">
<param name="animationatStart" value="true">
<param name="transparentatStart" value="true">
<param name="autoStart" value="true">
<param name="showControls" value="true">
<param name="Volume" value="10">
<embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" src="'.JURI::root().$configs->videoin."/".$the_media->local.'" name="MediaPlayer1" width="'.$vwidth.'" height="'.$vheight.'" autostart="1" showcontrols="1" volume="10">
</object>';*/
			}
			elseif($no_plugin_for_code == 0){
				if($the_media->type == "video" && $the_media->source == "url"){
					require_once(JPATH_ROOT .'/components/com_guru/helpers/videos/helper.php');
					$parsedVideoLink = parse_url($the_media->url);
					preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $parsedVideoLink['host'], $matches);
					$domain	= $matches['domain'];
					
					if (!empty($domain)){
						$provider		= explode('.', $domain);
						$providerName	= JString::strtolower($provider[0]);
						$libraryPath	= JPATH_ROOT .'/components/com_guru/helpers/videos' .'/'. $providerName . '.php';
						require_once($libraryPath);
						$className		= 'PTableVideo' . JString::ucfirst($providerName);
						$videoObj		= new $className();
						$videoObj->init($the_media->url);
						$video_id		= $videoObj->getId();
						$videoPlayer	= $videoObj->getViewHTML($video_id, $vwidth, $vheight);
						$media = $videoPlayer;
					}
				}
				else{
					$media = $helperclass->create_media_using_plugin($the_media, $configs, $awidth, $aheight, $vwidth, $vheight);
				}
			}
		}

		if($the_media->type=='docs'){	
			$the_base_link = explode('administrator/', $_SERVER['HTTP_REFERER']);
			$the_base_link = $the_base_link[0];				
			
			$media = JText::_('GURU_NO_PREVIEW');
			//$media = JText::_("GURU_TASKS");
			
			if($the_media->source == 'local' && (substr($the_media->local,(strlen($the_media->local)-3),3) == 'txt' || substr($the_media->local,(strlen($the_media->local)-3),3) == 'pdf') && $the_media->width > 1) {
				$media='<div class="contentpane">
							<iframe id="blockrandom"
								name="iframe"
								src="'.$the_base_link.'/'.$configs->docsin.'/'.$the_media->local.'"
								width="'.$the_media->width.'"
								height="'.$the_media->height.'"
								scrolling="auto"
								align="top"
								frameborder="2"
								class="wrapper">
								This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
							</div>';
				return stripslashes($media.'<div  style="text-align:center"><i>'.$the_media->instructions.'</i></div>');
			}
			elseif($the_media->source == 'url' && (substr($the_media->url,(strlen($the_media->url)-3),3) == 'txt' || substr($the_media->url,(strlen($the_media->url)-3),3) == 'pdf') && $the_media->width > 1) {
				$media='<div class="contentpane">
							<iframe id="blockrandom"
								name="iframe"
								src="'.$the_media->url.'"
								width="'.$the_media->width.'"
								height="'.$the_media->height.'"
								scrolling="auto"
								align="top"
								frameborder="2"
								class="wrapper">
								This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
							</div>';
				return stripslashes($media.'<div  style="text-align:center"><i>'.$the_media->instructions.'</i></div>');
			}
							
			if($the_media->source == 'local' && $the_media->width == 1){
				$media='<br /><a href="'.$the_base_link.$configs->docsin.'/'.$the_media->local.'" target="_blank">'.$the_media->name.'</a>';
				return stripslashes($media.'<div  style="text-align:center"><i>'.$the_media->instructions.'</i></div>');
			}
			if($the_media->source == 'url'  && $the_media->width == 0)
			$media='<div class="contentpane">
							<iframe id="blockrandom"
								name="iframe"
								src="'.$the_media->url.'"
								width="100%"
								height="600"
								scrolling="auto"
								align="top"
								frameborder="2"
								class="wrapper">
								This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
							</div>';		
							
			if($the_media->source == 'url'  && $the_media->width == 1)
			$media='<a href="'.$the_media->url.'" target="_blank">'.$the_media->name.'</a>';			
		}
	
		if($the_media->type=='url'){
			$src = $the_media->url;
			$media = '<a href="'.$src.'" target="_blank">'.$src.'</a>';
		}
		if($the_media->type=='Article'){
			$media = self::getArticleById($the_media->code);
		}
		
		if($the_media->type=='image'){
			$img_size = @getimagesize(JPATH_SITE.DS.$configs->imagesin.DS.'media'.DS.'thumbs'.$the_media->local);
			//echo "~~~~".var_dump($img_size)."~~~~";
			$img_width = $img_size[0];
			$img_height = $img_size[1];
			if($img_width>0 && $img_height>0){ 
				$thumb_width=0;$thumb_height=0;
				if($the_media->width > 0){
					$thumb_width = $the_media->width;
					$thumb_height = $img_height / ($img_width/$the_media->width);
				}
				elseif($the_media->height > 0){
					$thumb_height = $the_media->height;
					$thumb_width = $img_width / ($img_height/$the_media->height);		
				}
				else{
					$thumb_height = 200;
					$thumb_width = $img_width / ($img_height/200);									
				}
				$media = '<img width="'.$thumb_width.'" height="'.$thumb_height.'" src="'.JURI::root().DS.$configs->imagesin.'/media/thumbs'.$the_media->local.'" />';	
				}
				if(!isset($media)) { $media=NULL;}
			}

		if($the_media->type=='quiz'){
			$document = JFactory::getDocument();
    		$document->addStyleSheet(JURI::root()."components/com_guru/css/quiz.css");
			$media = '';
				
			$q  = "SELECT * FROM #__guru_quiz WHERE id = ".$the_media->id;
			$db->setQuery( $q );
			$result_quiz = $db->loadObject();				
			
			$media .= '<span class="guru_quiz_title">'.$result_quiz->name.'</span>';
			$media .= '<span class="guru_quiz_description">'.$result_quiz->description.'</span>';
			if($result_quiz->is_final == 1){
				$sql = "SELECT 	quizzes_ids FROM #__guru_quizzes_final WHERE qid=".$the_media->id;
				$db->setQuery($sql);
				$db->query();
				$result=$db->loadResult();	
				$result_qids = explode(",",trim($result,","));
				
				if(count($result_qids) || $result_qids["0"] = ""){
					$result_qids["0"] = 0;
				}
				
				$q  = "SELECT * FROM #__guru_questions WHERE qid IN (".implode(",", $result_qids).") and published=1";
				
			}
			else{
				$q  = "SELECT * FROM #__guru_questions WHERE qid = ".$the_media->id." and published=1";
			}		
				
				
				
				$db->setQuery( $q );
				$quiz_questions = $db->loadObjectList();			
				
				$question_number = 1;
				foreach( $quiz_questions as $one_question )
					{
						$question_answers_number = 0;
						
						$media .= '<div id="the_quiz">';
						$media .= '<ul class="guru_list">';
						$media .= 	'<li class="question">'.$question_number.'. '.$one_question->text.'</li>';
						
						if($one_question->a1!=''){
							$media .= '<li class="answer"><input onclick="javascrit:setQuestionValue('.$question_number.', \''.trim(str_replace("'", "&acute;", $one_question->a1)).'\')" type="checkbox" value="1a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a1.'</li>';
							$question_answers_number++;
						}	
						if($one_question->a2!=''){
							$media .= '<li class="answer"><input onclick="javascrit:setQuestionValue('.$question_number.', \''.trim(str_replace("'", "&acute;", $one_question->a2)).'\')" type="checkbox" value="2a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a2.'</li>';
							$question_answers_number++;
						}	
						if($one_question->a3!=''){
							$media .= '<li class="answer"><input onclick="javascrit:setQuestionValue('.$question_number.', \''.trim(str_replace("'", "&acute;", $one_question->a3)).'\')" type="checkbox" value="3a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a3.'</li>';
							$question_answers_number++;
						}	
						if($one_question->a4!=''){
							$media .= '<li class="answer"><input onclick="javascrit:setQuestionValue('.$question_number.', \''.trim(str_replace("'", "&acute;", $one_question->a4)).'\')" type="checkbox" value="4a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a4.'</li>';
							$question_answers_number++;
						}	
						if($one_question->a5!=''){
							$media .= '<li class="answer"><input onclick="javascrit:setQuestionValue('.$question_number.', \''.trim(str_replace("'", "&acute;", $one_question->a5)).'\')" type="checkbox" value="5a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a5.'</li>';
							$question_answers_number++;
						}	
						if($one_question->a6!=''){
							$media .= '<li class="answer"><input onclick="javascrit:setQuestionValue('.$question_number.', \''.trim(str_replace("'", "&acute;", $one_question->a6)).'\')" type="checkbox" value="6a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a6.'</li>';
							$question_answers_number++;
						}	
						if($one_question->a7!=''){
							$media .= '<li class="answer"><input onclick="javascrit:setQuestionValue('.$question_number.', \''.trim(str_replace("'", "&acute;", $one_question->a7)).'\')" type="checkbox" value="7a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a7.'</li>';
							$question_answers_number++;
						}	
						if($one_question->a8!=''){
							$question_answers_number++;
							$media .= '<li class="answer"><input onclick="javascrit:setQuestionValue('.$question_number.', \''.trim(str_replace("'", "&acute;", $one_question->a8)).'\')" type="checkbox" value="8a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a8.'</li>';
						}
						if($one_question->a9!=''){
							$question_answers_number++;
							$media .= '<li class="answer"><input onclick="javascrit:setQuestionValue('.$question_number.', \''.trim(str_replace("'", "&acute;", $one_question->a9)).'\')" type="checkbox" value="9a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a9.'</li>';
						}
						if($one_question->a10!=''){
							$question_answers_number++;
							$media .= '<li class="answer"><input onclick="javascrit:setQuestionValue('.$question_number.', \''.trim(str_replace("'", "&acute;", $one_question->a10)).'\')" type="checkbox" value="10a'.$question_number.'" name="q'.$question_number.'">&nbsp;'.$one_question->a10.'</li>';
						}	
						$media = $media.'</ul>';
						$media = $media.'</div>';
						
						$the_first_answer = explode(',', $one_question->answers);
						$the_first_answer = $the_first_answer[0];
						$the_first_answer = str_replace('a', '', $the_first_answer);
						
						if(intval($the_first_answer) == 1)
							$the_right_answer = $one_question->a1;
						if(intval($the_first_answer) == 2)
							$the_right_answer = $one_question->a2;
						if(intval($the_first_answer) == 3)
							$the_right_answer = $one_question->a3;
						if(intval($the_first_answer) == 4)
							$the_right_answer = $one_question->a4;
						if(intval($the_first_answer) == 5)
							$the_right_answer = $one_question->a5;
						if(intval($the_first_answer) == 6)
							$the_right_answer = $one_question->a6;
						if(intval($the_first_answer) == 7)
							$the_right_answer = $one_question->a7;
						if(intval($the_first_answer) == 8)
							$the_right_answer = $one_question->a8;
						if(intval($the_first_answer) == 9)
							$the_right_answer = $one_question->a9;
						if(intval($the_first_answer) == 10)
							$the_right_answer = $one_question->a10;																		
						
						$all_answers = array();
								
				if(trim($one_question->a1) != ""){
					$all_answers[] = trim(str_replace("'", "&acute;", $one_question->a1));
				}
				if(trim($one_question->a2) != ""){
					$all_answers[] = trim(str_replace("'", "&acute;", $one_question->a2));
				}
				if(trim($one_question->a3) != ""){
					$all_answers[] = trim(str_replace("'", "&acute;", $one_question->a3));
				}
				if(trim($one_question->a4) != ""){
					$all_answers[] = trim(str_replace("'", "&acute;", $one_question->a4));
				}			
				if(trim($one_question->a5) != ""){
					$all_answers[] = trim(str_replace("'", "&acute;", $one_question->a5));
				}				
				if(trim($one_question->a6) != ""){
					$all_answers[] = trim(str_replace("'", "&acute;", $one_question->a6));
				}
				if(trim($one_question->a7) != ""){
					$all_answers[] = trim(str_replace("'", "&acute;", $one_question->a7));
				}				
				if(trim($one_question->a8) != ""){
					$all_answers[] = trim(str_replace("'", "&acute;", $one_question->a8));
				}				
				if(trim($one_question->a9) != ""){
					$all_answers[] = trim(str_replace("'", "&acute;", $one_question->a9));
				}				
				if(trim($one_question->a10) != ""){
					$all_answers[] = trim(str_replace("'", "&acute;", $one_question->a10));
				}
				
				$all_answers = implode(",", $all_answers);
																																											
						$media = $media.'<input type="hidden" value="'.$all_answers.'" name="all_answers'.$question_number.'" id="all_answers'.$question_number.'" />';
						$media = $media.'<input type="hidden" value="" name="question_answergived'.$question_number.'" id="question_answergived'.$question_number.'" />';
						$media = $media.'<input type="hidden" value="'.$the_right_answer.'" name="question_answerright'.$question_number.'" id="question_answerright'.$question_number.'" />';
						$media = $media.'<input type="hidden" value="'.str_replace("'","&acute;" ,$one_question->text).'" name="the_question'.$question_number.'" id="the_question'.$question_number.'" />';
						
						$question_number++;																																								
					}
												
				
				$media = $media.'<input type="hidden" value="'.($question_number-1).'" name="question_number" id="question_number" />';
				$media = $media.'<input type="hidden" value="'.$result_quiz->name.'" id="quize_name" name="quize_name"/>';				
				
				$media = $media.'<br /><div align="left" style="padding-left:30px;"><input type="submit"  class = "btn" value="'.JText::_("GURU_SUBMIT").'" onclick="get_quiz_result()" /></div>';	
			
				$media = str_replace('type="submit"','type="button"',$media);
				$media = str_replace("type='submit'","type='button'",$media);
		}
		
		if($the_media->type == "file"){			
			$media = '<a target="_blank" href="'.JURI::ROOT().$configs->filesin.'/'.$the_media->local.'">'.$the_media->name.'</a><br/><br/>'.$the_media->instructions;
		}
		
		return stripslashes($media);
	}	
	
	function parse_audio ($id){
		$db = JFactory::getDBO();
		$helperclass =  new guruAdminHelper();
		$sql = "SELECT * FROM #__guru_config LIMIT 1";
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}	
		$configs = $db->loadObject();		
	
			$sql = "SELECT * FROM #__guru_media
					WHERE id = ".$id; 
			$db->setQuery($sql);
			$the_media = $db->loadObject();
			$the_media->code=stripslashes($the_media->code);
			
			$no_plugin_for_code = 0;
			$aheight=0; $awidth=0; $vheight=0; $vwidth=0;
			if($the_media->type=='audio')
				{
					if ($the_media->source=='url' || $the_media->source=='local')
						{	
							if ($the_media->width == 0 || $the_media->height == 0) 
								{
									$aheight=20; $awidth=300;
								}
							else
								{
									$aheight=$the_media->height; $awidth=$the_media->width;
								}
						}		
					elseif ($the_media->source=='code')
						{
							if ($the_media->width == 0 || $the_media->height == 0) 
								{
									$begin_tag = strpos($the_media->code, 'width="');
									if ($begin_tag!==false)
										{
											$remaining_code = substr($the_media->code, $begin_tag+7, strlen($the_media->code));
											$end_tag = strpos($remaining_code, '"');
											$awidth = substr($remaining_code, 0, $end_tag);
											
											$begin_tag = strpos($the_media->code, 'height="');
											if ($begin_tag!==false)
												{
													$remaining_code = substr($the_media->code, $begin_tag+8, strlen($the_media->code));
													$end_tag = strpos($remaining_code, '"');
													$aheight = substr($remaining_code, 0, $end_tag);
													$no_plugin_for_code = 1;
												}
											else
												{$aheight=20; $awidth=300;}	
										}	
									else
										{$aheight=20; $awidth=300;}							
								}
							else	
								{					
									$replace_with = 'width="'.$the_media->width.'"';
									$the_media->code = preg_replace('#width="[0-9]+"#', $replace_with, $the_media->code);
									$replace_with = 'height="'.$the_media->height.'"';
									$the_media->code = preg_replace('#height="[0-9]+"#', $replace_with, $the_media->code);
									$aheight=$the_media->height; $awidth=$the_media->width;
								}
						}	
				}	
		
		$awidth="200";$aheight="20";
		if($the_media->type=='audio'){
			if(!isset($layout_id)){
				$layout_id = "";
			}
			if ($no_plugin_for_code == 0){
				$media = $helperclass->create_media_using_plugin($the_media, $configs, $awidth, $aheight, $vwidth, $vheight,$layout_id);	
			}
			else{
				$media = $the_media->code;
			}
		}

		if(!isset($media)) { $media=NULL;}
		
		return stripslashes($media);
	}	
	
	public static function parse_txt ($id){
		$db = JFactory::getDBO();
		
		$q = "SELECT * FROM #__guru_config WHERE id = '1' ";
		$db->setQuery( $q );
		$configs = $db->loadObject();
				
		$q  = "SELECT * FROM #__guru_media WHERE id = ".$id;
		$db->setQuery( $q );
		$result = $db->loadObject();
		$the_media = $result;
		
		if($the_media->type=='text')
			{
				$media = $the_media->code;
				if(strpos($media, 'src="') !== FALSE){
					$the_base_link = explode('administrator/', $_SERVER['HTTP_REFERER']);
					$the_base_link = $the_base_link[0];
					$media = str_replace('src="', 'src="'.$the_base_link, $media);
				}
			}
		if($the_media->type=='docs')
			{
			
				$the_base_link = explode('administrator/', $_SERVER['HTTP_REFERER']);
				$the_base_link = $the_base_link[0];				
				
				$media = JText::_('GURU_NO_PREVIEW');
				if($the_media->source == 'local' && (substr($the_media->local,(strlen($the_media->local)-3),3) == 'txt' || substr($the_media->local,(strlen($the_media->local)-3),3) == 'pdf') && $the_media->width == 0){
					$media='<div class="contentpane">
									<iframe id="blockrandom"
										name="iframe"
										src="'.$the_base_link.'/'.$configs->docsin.'/'.$the_media->local.'"
										width="100%"
										height="600"
										scrolling="auto"
										align="top"
										frameborder="2"
										class="wrapper">
										This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
									</div>';
				}
				elseif($the_media->source == 'url' && (substr($the_media->url,(strlen($the_media->url)-3),3) == 'txt' || substr($the_media->url,(strlen($the_media->url)-3),3) == 'pdf') && $the_media->width == 0){
					$media='<div class="contentpane">
									<iframe id="blockrandom"
										name="iframe"
										src="'.$the_media->url.'"
										width="100%"
										height="600"
										scrolling="auto"
										align="top"
										frameborder="2"
										class="wrapper">
										This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
									</div>';
				}
								
				if($the_media->source == 'local' && $the_media->width == 1)
				$media='<a href="'.$the_base_link.'/'.$configs->docsin.'/'.$the_media->local.'" target="_blank">'.$the_media->name.'</a>';
		
				if($the_media->source == 'url'  && $the_media->width == 0)
				$media='<div class="contentpane">
								<iframe id="blockrandom"
									name="iframe"
									src="'.$the_media->url.'"
									width="100%"
									height="600"
									scrolling="auto"
									align="top"
									frameborder="2"
									class="wrapper">
									This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
								</div>';		
								
		if($the_media->source == 'url'  && $the_media->width == 1)
				$media='<a href="'.$the_media->url.'" target="_blank">'.$the_media->name.'</a>';								
			}	
		if($the_media->type=='quiz'){
				$media = '';
				
				$q  = "SELECT * FROM #__guru_quiz WHERE id = ".$the_media->source;
				$db->setQuery( $q );
				$result_quiz = $db->loadObject();				
				
				$media = $media. '<strong>'.$result_quiz->name.'</strong><br /><br />';
				$media = $media. $result_quiz->description.'<br /><br />';
				
				$q  = "SELECT * FROM #__guru_questions WHERE qid = ".$the_media->source." and published=1";
				$db->setQuery( $q );
				$quiz_questions = $db->loadObjectList();			
				
				foreach( $quiz_questions as $one_question )
					{
						$media = $media.'<div align="left">'.$one_question->text.'<div>';
						
						$media = $media.'<div align="left" style="padding-left:30px;">';
						if($one_question->a1!='')
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a1.'</input><br />';
						if($one_question->a2!='')
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a2.'</input><br />';
						if($one_question->a3!='')
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a3.'</input><br />';
						if($one_question->a4!='')
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a4.'</input><br />';
						if($one_question->a5!='')
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a5.'</input><br />';
						if($one_question->a6!='')
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a6.'</input><br />';
						if($one_question->a7!='')
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a7.'</input><br />';
						if($one_question->a8!='')
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a8.'</input><br />';
						if($one_question->a9!='')
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a9.'</input><br />';		
						if($one_question->a10!='')
							$media = $media.'<input type="radio" name="'.$one_question->id.'">'.$one_question->a10.'</input><br />';		
						$media = $media.'</div>';																																										
					}		
					
				$media = $media.'<br /><div align="left" style="padding-left:30px;"><input type="submit" value="'.JText::_("GURU_SUBMIT").'" disabled="disabled" /></div>';	
			}	
		if(!isset($media)) {$media=NULL;}
		$media = $media.'<div  style="text-align:center"><i>' .$the_media->instructions. '</i></div>';
		
		return stripslashes($media);	
	}	

	function store(){
		$item = $this->getTable('guruTasks');
		$data = JRequest::get('post');
		$database = JFactory::getDBO();
		$return_array = array();
		
		$course_id = JRequest::getVar("day", "0");
		$module_id = JRequest::getVar("my_menu_id", "0");
		$change_order = false;
		$last_lesson_id = 0;
		
		$sql = "select `id` from #__guru_days where `pid`=".intval($course_id)." order by `ordering` desc";
		$database->setQuery($sql);
		$database->query();
		$ids = $database->loadColumn();
		$last_module_id = $ids["0"];

		if($last_module_id == $module_id){
			$sql = "select `id_final_exam` from #__guru_program where `id`=".intval($course_id);
			$database->setQuery($sql);
			$database->query();
			$id_final_exam = $database->loadColumn();
			$id_final_exam = @$id_final_exam["0"];
			
			if(intval($id_final_exam) > 0){
				$change_order = true;
				$sql = "select mr.`media_id` from #__guru_mediarel mr, #__guru_task t where mr.`type`='dtask' and mr.`type_id`=".intval($module_id)." and mr.`media_id`=t.`id` order by t.`ordering` desc limit 0,1";
				$database->setQuery($sql);
				$database->query();
				$lesson_id = $database->loadColumn();
				$last_lesson_id = @$lesson_id["0"];
			}
		}
		
		if($data['alias']==''){
			$data['alias'] = JFilterOutput::stringURLSafe($data['name']);
		} 
		else {
			$data['alias'] = JFilterOutput::stringURLSafe($data['alias']);
		}
		$data['startpublish'] = date('Y-m-d H:i:s', strtotime($data['startpublish']));
		
		if($data['endpublish'] != 'Never' && $data['endpublish'] != ''){
			$data['endpublish'] = date('Y-m-d H:i:s', strtotime($data['endpublish']));
		}
		//ADDED BY JOSEPH 05/04/2015
		$data['duration'] = JRequest::getVar("duration","","post","float",JREQUEST_ALLOWRAW);
		$data['credit'] = $data['duration'] * 4;
		//END

		$db = JFactory::getDBO();
		
		$id = JRequest::getVar("id", "");
		
		if($id == "" || $id == "0"){
			//start set the order. this step must to be the last one
			$query="select max(ordering) as ordering from #__guru_task";
			$database->setQuery($query);
			$database->query();
			$result=$database->loadObject();
			$data['ordering']=intval($result->ordering)+1;
			//end set the order. this step must to be the last one
		}
		
		if (!$item->bind($data)){
			JError::raiseWarning( 500, $db->getErrorMsg() );
			return false;
		}
		if (!$item->check()) {
			JError::raiseWarning( 500, $db->getErrorMsg() );
			return false;
		}		
		if (!$item->store()) {
			JError::raiseWarning( 500, $db->getErrorMsg() );
			return false;
		}	
		
		$return_array["id"] = $item->id;
		if($data['id'] == "" || $data['id'] == 0){
			$new_lesson = "yes";
		}
		else{
			$new_lesson = "no";
		}
		
		$db->setQuery("SELECT forumboardcourse,forumboardlesson FROM `#__guru_kunena_forum` WHERE id=1 ");
		$db->query();	
		$ressult = $db->loadAssocList();

		if($ressult[0]["forumboardlesson"] ==1){
			$new_lesson = "no";
		}
		if(isset($_SESSION["lesson_removed"]) && $_SESSION["lesson_removed"] =="yes"){
			$new_lesson = "yes";	
		}
		if(!isset($data['id']) || $data['id'] == 0)
			{
				$db->setQuery("SELECT max(id) FROM `#__guru_task` ");
				$db->query();	
				$data['id'] = $db->loadResult();		
			}	
		
		// scr_l = the layout for the screen
		$db->setQuery("DELETE FROM `#__guru_mediarel` WHERE type_id='".$data['id']."' AND type='scr_l' ");
		$db->query();		
		
		$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`) VALUES ('scr_l','".$data['id']."','".$data['layout_db']."','0')");
		$db->query();	
		
		// scr_m = the file type for the screen - media
		// mainmedia = 1 for the first media
		// mainmedia = 2 for the second media
		$db->setQuery("DELETE FROM `#__guru_mediarel` WHERE type_id='".$data['id']."' AND type='scr_m' ");
		$db->query();	
		
		// scr_t = 	the file type for the screen - text	
		// mainmedia = 1 for normal text
		// mainmedia = 2 for quiz	
		$db->setQuery("DELETE FROM `#__guru_mediarel` WHERE type_id='".$data['id']."' AND type='scr_t' ");
		$db->query();		
	
		if(isset($data['day']) && intval($data['day'])>0){		
		$queri="INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`text_no`) VALUES ('dtask','".intval($data['my_menu_id'])."','".$data['id']."','0','0')";	
			$db->setQuery($queri);
			$db->query();
		}	
		
		if(1==1)
			{
				if(intval($data['db_media_1'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_1'])."','1',1)");
						$db->query();					
					}		
					
				$mainmedia = 1;
				if(intval($data['text_is_quiz']) == 1)
					$mainmedia = 2;
				if(intval($data['db_text_1'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_t','".$data['id']."','".intval($data['db_text_1'])."','".$mainmedia."',1)");
						$db->query();					
					}							
			}
			
		if(1==1)
			{
				if(intval($data['db_media_2'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_2'])."','1',2)");
						$db->query();					
					}	
				if(intval($data['db_media_3'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_3'])."','2',2)");
						$db->query();					
					}	
					
				$mainmedia = 1;
				if(intval($data['text_is_quiz']) == 1)
					$mainmedia = 2;
				if(intval($data['db_text_2'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_t','".$data['id']."','".intval($data['db_text_2'])."','".$mainmedia."',2)");
						$db->query();					
					}														
			}
			
		if(1==1)
			{
				if(intval($data['db_media_4'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_4'])."','1',3)");
						$db->query();					
					}	
					
				$mainmedia = 1;
				if(intval($data['text_is_quiz']) == 1)
					$mainmedia = 2;
				if(intval($data['db_text_3'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_t','".$data['id']."','".intval($data['db_text_3'])."','".$mainmedia."',3)");
						$db->query();					
					}								
			}	
			
		if(1==1)
			{
				if(intval($data['db_media_5'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_5'])."','1',4)");
						$db->query();					
					}	
				if(intval($data['db_media_6'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_6'])."','2',4)");
						$db->query();					
					}
					
				$mainmedia = 1;
				if(intval($data['text_is_quiz']) == 1)
					$mainmedia = 2;
				if(intval($data['db_text_4'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_t','".$data['id']."','".intval($data['db_text_4'])."','".$mainmedia."',4)");
						$db->query();					
					}														
			}	
			
			
		if(1==1)
			{
				$mainmedia = 1;
				if(intval($data['text_is_quiz']) == 1)
					$mainmedia = 2;
				if(intval($data['db_text_5'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_t','".$data['id']."','".intval($data['db_text_5'])."','".$mainmedia."',5)");
						$db->query();					
					}				
			}				
			
			
		if(1==1)
			{
				if(intval($data['db_media_7'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_7'])."','1',6)");
						$db->query();					
					}			
			}		
		
		
		if(1==1)
			{
				if(intval($data['db_media_8'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_8'])."','1',7)");
						$db->query();					
				}			
								
				$mainmedia = 1;
				if(intval($data['text_is_quiz']) == 1)
					$mainmedia = 2;
				if(intval($data['db_text_6'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_t','".$data['id']."','".intval($data['db_text_6'])."','".$mainmedia."',7)");
						$db->query();					
					}
					
				
			}	
		
		
		if(1==1)
			{
				if(intval($data['db_media_9'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_9'])."','1',8)");
						$db->query();					
					}	
				if(intval($data['db_media_10'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_10'])."','2',8)");
						$db->query();					
					}	
					
				$mainmedia = 1;
				if(intval($data['text_is_quiz']) == 1)
					$mainmedia = 2;
				if(intval($data['db_text_7'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_t','".$data['id']."','".intval($data['db_text_7'])."','".$mainmedia."',8)");
						$db->query();					
					}														
			}	
		
		
		if(1==1)
			{
				if(intval($data['db_media_11'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_11'])."','1',9)");
						$db->query();					
					}	
					
				$mainmedia = 1;
				if(intval($data['text_is_quiz']) == 1)
					$mainmedia = 2;
				if(intval($data['db_text_8'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_t','".$data['id']."','".intval($data['db_text_8'])."','".$mainmedia."',9)");
						$db->query();					
					}								
			}	
		
		
		if(1==1)
			{
				if(intval($data['db_media_12'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_12'])."','1',10)");
						$db->query();					
					}	
				if(intval($data['db_media_13'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_13'])."','2',10)");
						$db->query();					
					}
					
				$mainmedia = 1;
				if(intval($data['text_is_quiz']) == 1)
					$mainmedia = 2;
				if(intval($data['db_text_9'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_t','".$data['id']."','".intval($data['db_text_9'])."','".$mainmedia."',10)");
						$db->query();					
					}														
			}	
				
		if(1==1)
			{
				if(intval($data['db_media_14'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_14'])."','1',11)");
						$db->query();					
					}	
					
				$mainmedia = 1;
				if(intval($data['text_is_quiz']) == 1)
					$mainmedia = 2;
				if(intval($data['db_text_10'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`text_no`,`layout`) VALUES ('scr_t','".$data['id']."','".intval($data['db_text_10'])."','".$mainmedia."', 1, 11)");
						$db->query();					
					}	
				
				if(intval($data['db_text_11'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`text_no`,`layout`) VALUES ('scr_t','".$data['id']."','".intval($data['db_text_11'])."','".$mainmedia."', 2,11)");
						$db->query();					
					}		
												
			}	
			
		if(1==1)
			{
				if(intval($data['db_media_15'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_15'])."','1',12)");
						$db->query();
						
						$listoflessons = "select distinct(media_id) from `#__guru_mediarel` where type='dtask' and type_id IN (select id from `#__guru_days` where pid=".intval($data['day']).")";
						$db->setquery($listoflessons);
						$db->query();
						$listoflessons = $db->loadColumn();

						
						$listoflessons = implode("," ,$listoflessons);
						if($listoflessons == ""){
							$listoflessons = "0";
						}
						
						$count_quiz = "select count(media_id) from `#__guru_mediarel` where type='scr_l' and media_id='12' and type_id IN (".$listoflessons.")";
						$db->setquery($count_quiz);
						$db->query();
						$count_quiz = $db->loadColumn();
						$count_quiz = $count_quiz["0"];
						
						$sql="UPDATE #__guru_program set hasquiz = ".intval($count_quiz)." WHERE id =".intval($data['day']);
						$db->setQuery($sql);
						$db->query($sql);	
					}			
			}		
		
		// jump buttons - Start //
		
		// delete existing buttons before inserting the new ones
		$sql="DELETE FROM #__guru_mediarel WHERE type='jump' AND type_id='".$data['id']."'";
		$db->setQuery($sql);
		$db->query($sql);
		
		// insert the 4 buttons
		if(intval($_POST['jumpbutton1'])!=0){
			$sql1="INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`text_no`) VALUES ('jump','".$data['id']."','".intval($_POST['jumpbutton1'])."','0','0')";
			$db->setQuery($sql1);
			$db->query($sql1);
		}
		if(intval($_POST['jumpbutton2'])!=0){	
			$sql="INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`text_no`) VALUES ('jump','".$data['id']."','".intval($_POST['jumpbutton2'])."','0','0')";
			$db->setQuery($sql);
			$db->query($sql);
		}
		
		if(intval($_POST['jumpbutton3'])!=0){
			$sql="INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`text_no`) VALUES ('jump','".$data['id']."','".intval($_POST['jumpbutton3'])."','0','0')";
			$db->setQuery($sql);
			$db->query($sql);
		}

		if(intval($_POST['jumpbutton4'])!=0){
			$sql="INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`text_no`) VALUES ('jump','".$data['id']."','".intval($_POST['jumpbutton4'])."','0','0')";
			$db->setQuery($sql);
			$db->query($sql);
		}
		
		if(!isset($data['db_media_99'])) {$data['db_media_99']=0;}
		if(intval($data['db_media_99'])>0)
					{
						$db->setQuery("INSERT INTO `#__guru_mediarel` (`type`,`type_id`,`media_id`,`mainmedia`,`layout`) VALUES ('scr_m','".$data['id']."','".intval($data['db_media_99'])."','1',99)");
						$db->query();					
		}	
		
		// jump buttons - End //
		
		//start- kunenea forum integration//
		$sql = "select count(*) from #__extensions where element='com_kunena' and name='com_kunena'";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadResult();
		if($count >0){
		if(JComponentHelper::isEnabled( 'com_kunena', true) ){
			$sql = "select forumboardlesson from #__guru_kunena_forum where id=1";
			$db->setQuery($sql);
			$db->query();
			$forumboardlesson = $db->loadResult();
			if($data['kunenabuttonactive'] == 'on'){
				$forumboardlesson = 1;
			}
			if($forumboardlesson != 0 ){
				if($new_lesson == "no"){
					$sql="UPDATE `#__guru_task` SET forum_kunena_generatedt = '1' WHERE id=".intval($data['id']);
					$db->setQuery($sql);
					$db->query($sql);
					
					$nameofmainforum = JText::_('GURU_TREECOURSE');
					
					$sql = "SELECT name FROM #__kunena_categories WHERE parent_id='0' and name='".addslashes($nameofmainforum)."'";
					$db->setQuery($sql);
					$db->query($sql);
					$result = $db->loadResult();
		
					if(count($result) == 0){
						$sql = "INSERT INTO #__kunena_categories ( `parent_id`, `name`, `alias`, `icon_id`, `locked`, `accesstype`, `access`, `pub_access`, `pub_recurse`, `admin_access`, `admin_recurse`, `ordering`, `published`, `channels`, `checked_out`, `checked_out_time`, `review`, `allow_anonymous`, `post_anonymous`, `hits`, `description`, `headerdesc`, `class_sfx`, `allow_polls`, `topic_ordering`, `numTopics`, `numPosts`, `last_topic_id`, `last_post_id`, `last_post_time`, `params`) VALUES ( 0, '".$nameofmainforum."', 'course', 0, 0, 'joomla.level', 1, 1, 1, 8, 1, 2, 1, NULL, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, '', '', '', 0, 'lastpost', 0, 0, 0, 0, 0, '{}')";
						$db->setQuery($sql);
						$db->query($sql);
					}
				
					$sql = "SELECT name from #__guru_program where id =".intval($data['day']);
					$db->setQuery($sql);
					$db->query($sql);	
					$coursename = $db->loadResult();
					
					$sql = "SELECT alias from #__guru_program where id =".intval($data['day']);
					$db->setQuery($sql);
					$db->query($sql);	
					$aliascourse = $db->loadResult();
					
					
					
					$sql = "SELECT id FROM #__kunena_categories WHERE parent_id='0' and name='".addslashes($nameofmainforum)."'";
					$db->setQuery($sql);
					$db->query();
					$idmainforum= $db->loadResult();
					
					$sql = "SELECT name FROM #__kunena_categories WHERE parent_id='".$idmainforum."' and name='".addslashes($coursename)."'";
					$db->setQuery($sql);
					$db->query($sql);
					$result1 = $db->loadResult();
					
					if(count($result1) == 0){
						$sql = "INSERT INTO #__kunena_categories ( `parent_id`, `name`, `alias`, `icon_id`, `locked`, `accesstype`, `access`, `pub_access`, `pub_recurse`, `admin_access`, `admin_recurse`, `ordering`, `published`, `channels`, `checked_out`, `checked_out_time`, `review`, `allow_anonymous`, `post_anonymous`, `hits`, `description`, `headerdesc`, `class_sfx`, `allow_polls`, `topic_ordering`, `numTopics`, `numPosts`, `last_topic_id`, `last_post_id`, `last_post_time`, `params`) VALUES ( '".$idmainforum."', '".addslashes($coursename)."', '".$aliascourse."', 0, 0, 'joomla.group', 1, 1, 1, 8, 1, 2, 1, NULL, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, '', '', '', 0, 'lastpost', 0, 0, 0, 0, 0, '{}')";
						$db->setQuery($sql);
						$db->query($sql);
						
				  }
	
					$sql = "SELECT title from #__guru_days where pid =".intval($data['day'])." and id IN (SELECT type_id FROM #__guru_mediarel WHERE media_id=".$data['id'].")";
					$db->setQuery($sql);
					$db->query($sql);	
					$modulename = $db->loadResult();
	
					$sql = "SELECT alias from #__guru_days where pid =".intval($data['day']);
					$db->setQuery($sql);
					$db->query($sql);	
					$aliasmodule = $db->loadResult();
					
					$sql = "SELECT id FROM #__kunena_categories WHERE alias ='".$aliascourse."'";
					$db->setQuery($sql);
					$db->query();
					$idmaincourse = $db->loadResult();
					
					
					$sql = "SELECT name FROM #__kunena_categories WHERE parent_id='".$idmaincourse."' and name='".addslashes($modulename)."'";
					$db->setQuery($sql);
					$db->query($sql);
					$resultmodule = $db->loadResult();
					
					
					
					if(count($resultmodule) == 0){
						$sql = "INSERT INTO #__kunena_categories ( `parent_id`, `name`, `alias`, `icon_id`, `locked`, `accesstype`, `access`, `pub_access`, `pub_recurse`, `admin_access`, `admin_recurse`, `ordering`, `published`, `channels`, `checked_out`, `checked_out_time`, `review`, `allow_anonymous`, `post_anonymous`, `hits`, `description`, `headerdesc`, `class_sfx`, `allow_polls`, `topic_ordering`, `numTopics`, `numPosts`, `last_topic_id`, `last_post_id`, `last_post_time`, `params`) VALUES ( '".$idmaincourse."', '".addslashes($modulename)."', '".$aliasmodule."', 0, 0, 'joomla.group', 1, 1, 1, 8, 1, 2, 1, NULL, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, '', '', '', 0, 'lastpost', 0, 0, 0, 0, 0, '{}')";
						$db->setQuery($sql);
						$db->query($sql);					
				  }
					
					
					$sql = "SELECT id FROM #__kunena_categories WHERE  name='".addslashes($coursename)."'";
					$db->setQuery($sql);
					$db->query($sql);
					$resultid = $db->loadResult();
					$count_c = count($resultid);
					
					$sql = "SELECT id FROM #__kunena_categories WHERE name='".addslashes($modulename)."'";
					$db->setQuery($sql);
					$db->query($sql);
					$resultidmodule = $db->loadResult();
					$count_m = count($resultidmodule);
					
					if($count_c == 0){
						$sql = "INSERT INTO #__kunena_aliases (`alias`, `type`, `item`, `state`) VALUES (  '".$aliascourse."', 'catid', ".$resultid.", 0)";
						$db->setQuery($sql);
						$db->query($sql);
					}
					
					if($count_m == 0){
						$sql = "INSERT INTO #__kunena_aliases (`alias`, `type`, `item`, `state`) VALUES (  '".$aliasmodule."', 'catid', ".$resultidmodule.", 0)";
						$db->setQuery($sql);
						$db->query($sql);
					}
					
					$sql = "INSERT INTO #__guru_kunena_courseslinkage (`idcourse`, `coursename`, `catidkunena`) VALUES (  '".$data['day']."', '".addslashes($coursename)."', '".$resultid."')";
					$db->setQuery($sql);
					$db->query($sql);
					
							  
					
					$sql = "SELECT alias from #__guru_task where id =".intval($data['id']);
					$db->setQuery($sql);
					$db->query($sql);	
					$aliaslesson = $db->loadResult();
					
					$sql = "SELECT name FROM #__kunena_categories WHERE parent_id='".$resultid."' and alias='".$aliaslesson."'";
					$db->setQuery($sql);
					$db->query($sql);
					$result2 = $db->loadResult();
					
					if(count($result2) == 0){
						$sql = "INSERT INTO #__kunena_categories ( `parent_id`, `name`, `alias`, `icon_id`, `locked`, `accesstype`, `access`, `pub_access`, `pub_recurse`, `admin_access`, `admin_recurse`, `ordering`, `published`, `channels`, `checked_out`, `checked_out_time`, `review`, `allow_anonymous`, `post_anonymous`, `hits`, `description`, `headerdesc`, `class_sfx`, `allow_polls`, `topic_ordering`, `numTopics`, `numPosts`, `last_topic_id`, `last_post_id`, `last_post_time`, `params`) VALUES ( ".$resultidmodule.", '".addslashes($data['name'])."', '".$aliaslesson."', 0, 0, 'joomla.group', 1, 1, 1, 8, 1, 2, 1, NULL, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, '', '', '', 0, 'lastpost', 0, 0, 0, 0, 0, '{}')";
						$db->setQuery($sql);
						$db->query($sql);
						
				  }
				  
				
				  $sql = "SELECT id FROM #__kunena_categories WHERE  alias='".$aliaslesson."'";
				  $db->setQuery($sql);
				  $db->query($sql);
				  $resultidlesson = $db->loadResult();
				  
				if(count($result2) == 0 && $result2 !=NULL ){
				  $sql = "INSERT INTO #__kunena_aliases (`alias`, `type`, `item`, `state`) VALUES ('".$aliaslesson."', 'catid', '".$resultidlesson."', 0)";
				  $db->setQuery($sql);
				  $db->query($sql);
				}
				  $sql = "INSERT INTO #__guru_kunena_lessonslinkage (`idlesson`, `lessonname`, `catidkunena`) VALUES (  '".$data['id']."', '".$data['name']."', '".$resultidlesson."')";
				  $db->setQuery($sql);
				  $db->query($sql);
				
				 $sql = "SELECT catidkunena  FROM `#__guru_kunena_lessonslinkage` where idlesson=".$data['id']." order by id desc limit 0,1";
				 $db->setQuery($sql);
				 $db->query();
				 $catidkunena = $db->loadResult(); 
				 
				 
				 $sql = "UPDATE #__kunena_categories set name='".$data['name']."' WHERE id=".intval($catidkunena);
				 $db->setQuery($sql);
				 $db->query();
				  
				//end- kunenea forum integration//
			  }
			}
		  }	
		}
		unset($_SESSION["lesson_removed"]);
		$return_array["return"] = true;
		
		if($change_order){
			$id_new_lesson = $return_array["id"];
			$sql = "select `ordering` from #__guru_task where `id`=".intval($id_new_lesson);
			$db->setQuery($sql);
			$db->query();
			$new_lesson_ordering = $db->loadColumn();
			$new_lesson_ordering = @$new_lesson_ordering["0"];
			
			$sql = "select `ordering` from #__guru_task where `id`=".intval($last_lesson_id);
			$db->setQuery($sql);
			$db->query();
			$last_lesson_ordering = $db->loadColumn();
			$last_lesson_ordering = @$last_lesson_ordering["0"];
			
			$sql = "update #__guru_task set `ordering` = ".intval($new_lesson_ordering)." where `id`=".intval($last_lesson_id);
			$db->setQuery($sql);
			$db->query();
			
			$sql = "update #__guru_task set `ordering` = ".intval($last_lesson_ordering)." where `id`=".intval($id_new_lesson);
			$db->setQuery($sql);
			$db->query();
		}

		return $return_array;
	}
	
	function getJumps(){
		$stepid=$_GET['cid'][0];
		$db = JFactory::getDBO();
		$sql="SELECT j . *
		FROM #__guru_jump AS j, #__guru_mediarel AS m
		WHERE j.id = m.media_id
		AND m.type = 'jump'
		AND m.type_id =".intval($stepid)."
		ORDER BY j.button ASC
		LIMIT 10";
		$db->setQuery($sql);
		return $db->loadObjectList();
	}
	
	function getCurrentJump(){
		$db = JFactory::getDBO();
		if(isset($_GET['id'])){
			$id=intval($_GET['id']);
		} else { return NULL;}
		$sql="SELECT * FROM #__guru_jump WHERE id = ".$id;
		$db->setQuery($sql);
		return $db->loadObject();		
	}
	
	function last_task(){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT max(id) FROM `#__guru_task` ");
		$db->query();	
		$last_task = $db->loadResult();	
		return $last_task;
	}
	
	function more_media_files ($ids){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT *, id as media_id FROM `#__guru_media` WHERE id in (".$ids.") GROUP BY media_id");
		$db->query();
		$more_media_files = $db->loadObjectList();
		$this->assign("more_media_files", $more_media_files);
		return true;
	}
	
	function existing_ids ($ids){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT media_id FROM `#__guru_mediarel` WHERE type_id = ".$ids." AND type='task' AND mainmedia = '0' ");
		$db->query();
		$existing_ids = $db->loadObjectList();
		//$this->assign("existing_ids", $existing_ids);
		return $existing_ids;
	}	
	
	function existing_mmid ($id){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT *, id as media_id FROM `#__guru_media` WHERE id in (".$id.") GROUP BY media_id");
		$db->query();
		$existing_ids = $db->loadObjectList();
		return $existing_ids;
	}		

	function existing_mqid ($id){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT *, id as media_id FROM `#__guru_quiz` WHERE id in (".$id.") GROUP BY media_id");
		$db->query();
		$existing_ids = $db->loadObjectList();
		return $existing_ids;
	}	
		
	public static function getMediaName ($id) {
		$db = JFactory::getDBO();
		$sql = "SELECT name FROM #__guru_media WHERE id=".intval($id)." LIMIT 1";
		$db->setQuery($sql);
		$db->query();
		$existing_ids = $db->loadResult();
		return $existing_ids;
	}

	function delete(){
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$database = JFactory::getDBO();
		
		// we retain the TASKS STATUS from PROGRAMSTATUS table - START
		$sql = "SELECT id, tasks FROM #__guru_programstatus";
		$database->setQuery($sql);
		if (!$database->query()){
			echo $database->stderr();
			return;
		}
		$tasks_ids = $database->loadObjectList();
		
		foreach($tasks_ids as $tasks_id){
			$new = '';
			$day_array = explode(';', $tasks_id->tasks);

			foreach($day_array as $day_tasks) {
				$tasks_array = explode ('-',$day_tasks);
				
				// removing a certain VALUE from an array - start
				foreach($tasks_array as $key => $value) {
					$task_number_array = explode(',',$value);
					
					if(in_array($task_number_array[0],$cids)) {
						unset($tasks_array[$key]);
					}
				}
				$new_array = array_values($tasks_array);	
				$new_array = implode('-',$new_array);
				
				//if(isset($new_array[0]))
				$new = $new.$new_array.';';
			}
			$new = substr($new, 0, strlen($new)-1);
			// $new has the task STATUS 
			$sql = "update #__guru_programstatus set tasks='".$new."' where id =".$tasks_id->id;
			$database->setQuery($sql);
			$database->query();	
		}
		// we retain the TASKS STATUS from PROGRAMSTATUS table - STOP
	
		$item = $this->getTable('guruTasks');
		
		$sql = "SELECT imagesin FROM #__guru_config WHERE id ='1' ";
		$database->setQuery($sql);
		if (!$database->query()) {
			echo $database->stderr();
			return;
		}
		$imagesin = $database->loadResult();
		
		foreach ($cids as $cid) {
			
			$sql = "SELECT image FROM #__guru_task WHERE id =".$cid;
			$database->setQuery($sql);
			if (!$database->query()) {
				echo $database->stderr();
				return;
			}
			$image = $database->loadResult();	
			
			if (!$item->delete($cid)) {
				$this->setError($item->getErrorMsg());
				return false;

			}
			// we delete the relations with MAIN MEDIA, SUPPORTING MEDIA
			$query = "DELETE FROM #__guru_mediarel WHERE type = 'task' AND type_id = '".$cid."'";
			$database->setQuery( $query );
			$database->query();
			
			// we delete the relations with QUIZES
			$query = "DELETE FROM #__guru_mediarel WHERE type = 'tquiz' AND type_id = '".$cid."'";
			$database->setQuery( $query );
			$database->query();

			// we delete the relations with DAYS
			$query = "DELETE FROM #__guru_mediarel WHERE type = 'dtask' AND media_id = '".$cid."'";
			$database->setQuery( $query );
			$database->query();			

			$targetPath = JPATH_SITE.'/'.$imagesin.'/';		
			unlink($targetPath.$image);			
		}
		return true;
	}

	function addmedia ($toinsert, $taskid, $mainmedia) {
		$db = JFactory::getDBO();
		$sql = "INSERT INTO `#__guru_mediarel` ( `id` , `type` , `type_id` , `media_id` , `mainmedia` ) VALUES ('', 'task', '".$taskid."' , '".$toinsert."', '".$mainmedia."');";
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
	return true;
	}
	
	function delmedia($tid,$cid,$main) {
		$db = JFactory::getDBO();
		
		$sql = "delete from #__guru_mediarel where type='task' and type_id=".$tid." and media_id=".$cid." and mainmedia=".$main;
		
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}
		return 1;
	}

	function getlistDays () {
		if(!isset($_GET['progrid'])) { return false;}
		$sql = "SELECT * FROM #__guru_days WHERE pid =".intval($_GET['progrid'])." ORDER BY ordering ASC ";
		$result = $this->_getList($sql);
		return $result;
	}
	
	public static function getTask2($taskid){
			$database = JFactory::getDBO();
			$sql = " SELECT * FROM #__guru_task WHERE id = ".$taskid; 
			$database->setQuery($sql);
			$result = $database->loadObject();
			return $result;	
	}	
	
	public static function select_layout ($pid){
		$db = JFactory::getDBO();
		$db->setQuery("SELECT media_id FROM `#__guru_mediarel` WHERE type_id = ".$pid." AND type='scr_l' ");
		$db->query();
		$layout_id = $db->loadResult();
		return $layout_id;
	}	

	public static function getIDTasksForDay($dayid){
			$database = JFactory::getDBO();
			$sql = "SELECT media_id FROM #__guru_mediarel WHERE type='dtask' AND type_id = ".$dayid." ORDER BY `id` ASC ";
			$database->setQuery($sql);
			$result = $database->loadColumn();
			return $result;	
	}
	
	function getArticleById($id) {
			$db = JFactory::getDBO();
			$sql = "SELECT jc.introtext, jc.fulltext FROM #__content jc WHERE id = ".$id;
			$db->setQuery($sql);
			$row = $db->loadAssoc();
			$fullArticle = $row['introtext'].$row['fulltext'];
			if(!strlen(trim($fullArticle))) $fullArticle = "Article is empty ";
			return $fullArticle; 
	}
	
	public static function getConfigs() {
		$db = JFactory::getDBO();
		
		$sql = "SELECT * FROM #__guru_config LIMIT 1";
		
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}	
		$result = $db->loadObject();	
		return $result;
	}		
	
	function checkbox_construct( $rowNum, $recId, $name='cid' )
	{
		$db = JFactory::getDBO();
		
		$sql = "SELECT id FROM #__guru_days WHERE pid in (SELECT programid FROM #__guru_order GROUP BY programid)";
		
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
			return false;
		}	
		$result = $db->loadObjectList();	
		
		$sql = "SELECT influence FROM #__guru_config WHERE id = 1";
		$db->setQuery($sql);
		if (!$db->query()) {
			echo $db->stderr();
			return;
			}
		$influence = $db->loadResult(); // we have selected the INFLUENCE 
		
		$sql = "SELECT locked FROM #__guru_days WHERE id in (SELECT type_id FROM #__guru_mediarel WHERE media_id = '".$recId."' AND type = 'dtask') ";
		$db->setQuery($sql);
		if (!$db->query()) {
			echo $db->stderr();
			return;
			}
		$locked = $db->loadColumn(); // we have selected the LOCKED property for a day 				

		$days_in_sold_programs = array();
		foreach ($result as $day_id)
			{
				array_push( $days_in_sold_programs, $day_id->id );
			}
		
		if(($influence==1 && in_array('1', $locked)) || $influence==0)
			{
				$not = 'not';
				$disabled = 'disabled="disabled"';	
			}	
		else 
			{
				$disabled = '';
				$not = '';
			}	
		
		return '<input type="checkbox" id="'.$not.'cb'.$rowNum.'" '.$disabled.'" name="'.$name.'[]" value="'.$recId.'" onclick="isChecked(this.checked);" />$$$$$'.$disabled;
	}	
	
	public static function get_asoc_file_for_media($media_id)	{
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__guru_media WHERE id=".$media_id;
		$db->setQuery($sql);
		if (!$db->query()) {
			echo $db->stderr();
			return;
		}
		$the_media = $db->loadObject();			
		if(!empty($the_media )){
			if($the_media->source == 'local' || $the_media->type == 'image')
				$asoc_file = $the_media->local;
			else
				$asoc_file = '-';
		}
		else $asoc_file = '-';
		
		return 	$asoc_file;			
	}	
	
	function real_quiz_id($media_id)	{
		$db = JFactory::getDBO();
		$sql = "SELECT source FROM #__guru_media WHERE id=".$media_id;
		$db->setQuery($sql);
		if (!$db->query()) {
			echo $db->stderr();
			return;
		}
		$the_media = $db->loadResult();		
		
		return 	$the_media;			
	}	

	function parse_quiz ($id){

		$db = JFactory::getDBO();
		
		$q = "SELECT * FROM #__guru_config WHERE id = '1' ";
		$db->setQuery( $q );
		$configs = $db->loadObject();
				
		$q  = "SELECT * FROM #__guru_media WHERE id = ".$id;
		$db->setQuery( $q );
		$result = $db->loadObject();	
	
		$the_media = $result;
		
		if($the_media->type=='text')
			{
				$media = $the_media->code;
			}
		if($the_media->type=='docs')
			{
			
				$the_base_link = explode('administrator/', $_SERVER['HTTP_REFERER']);
				$the_base_link = $the_base_link[0];				
				
				$media = JText::_('GURU_NO_PREVIEW');
				
				if($the_media->source == 'local' && (substr($the_media->local,(strlen($the_media->local)-3),3) == 'txt' || substr($the_media->local,(strlen($the_media->local)-3),3) == 'pdf') && $the_media->width == 0){
					$media='<div class="contentpane">
									<iframe id="blockrandom"
										name="iframe"
										src="'.$the_base_link.'/'.$configs->docsin.'/'.$the_media->local.'"
										width="100%"
										height="600"
										scrolling="auto"
										align="top"
										frameborder="2"
										class="wrapper">
										This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
									</div>';
				}
				elseif($the_media->source == 'url' && (substr($the_media->url,(strlen($the_media->url)-3),3) == 'txt' || substr($the_media->url,(strlen($the_media->url)-3),3) == 'pdf') && $the_media->width == 0){
					$media='<div class="contentpane">
									<iframe id="blockrandom"
										name="iframe"
										src="'.$the_media->url.'"
										width="100%"
										height="600"
										scrolling="auto"
										align="top"
										frameborder="2"
										class="wrapper">
										This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
									</div>';
				}
				
								
				if($the_media->source == 'local' && $the_media->width == 1)
				$media='<a href="'.$the_base_link.'/'.$configs->docsin.'/'.$the_media->local.'" target="_blank">'.$the_media->name.'</a>';
		
				if($the_media->source == 'url'  && $the_media->width == 0)
				$media='<div class="contentpane">
								<iframe id="blockrandom"
									name="iframe"
									src="'.$the_media->url.'"
									width="100%"
									height="600"
									scrolling="auto"
									align="top"
									frameborder="2"
									class="wrapper">
									This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
								</div>';		
								
				if($the_media->source == 'url'  && $the_media->width == 1)
				$media='<a href="'.$the_media->url.'" target="_blank">'.$the_media->name.'</a>';								
			}	
		if($the_media->type=='quiz'){
				$media = '';
				
				$q  = "SELECT * FROM #__guru_quiz WHERE id = ".$the_media->source;
				$db->setQuery( $q );
				$result_quiz = $db->loadObject();				
				
				$media = $media. '<strong>'.$result_quiz->name.'</strong><br /><br />';
				$media = $media. $result_quiz->description.'<br /><br />';
				
				$q  = "SELECT * FROM #__guru_questions WHERE qid = ".$the_media->source." and and published=1";
				$db->setQuery( $q );
				$quiz_questions = $db->loadObjectList();			
				
				$media = $media.'<div id="the_quiz">';
				
				$question_number = 1;
				foreach( $quiz_questions as $one_question )
					{
						$question_answers_number = 0;

						$media = $media.'<div align="left">'.$one_question->text.'<div>';
						
						$media = $media.'<div align="left" style="padding-left:30px;">';
						if($one_question->a1!='')
							{
								$media = $media.'<input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a1).'" \' type="radio" value="1a'.$question_number.'" name="q'.$question_number.'">'.$one_question->a1.'</input><br />';
								$question_answers_number++;
							}	
						if($one_question->a2!='')
							{
								$media = $media.'<input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a2).'" \' type="radio" value="2a'.$question_number.'" name="q'.$question_number.'">'.$one_question->a2.'</input><br />';
								$question_answers_number++;
							}	
						if($one_question->a3!='')
							{
								$media = $media.'<input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a3).'" \' type="radio" value="3a'.$question_number.'" name="q'.$question_number.'">'.$one_question->a3.'</input><br />';
								$question_answers_number++;
							}	
						if($one_question->a4!='')
							{
								$media = $media.'<input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a4).'" type="radio" value="4a'.$question_number.'" name="q'.$question_number.'">'.$one_question->a4.'</input><br />';
								$question_answers_number++;
							}	
						if($one_question->a5!='')
							{
								$media = $media.'<input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a5).'" type="radio" value="5a'.$question_number.'" name="q'.$question_number.'">'.$one_question->a5.'</input><br />';
								$question_answers_number++;
							}	
						if($one_question->a6!='')
							{
								$media = $media.'<input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a6).'" type="radio" value="6a'.$question_number.'" name="q'.$question_number.'">'.$one_question->a6.'</input><br />';
								$question_answers_number++;
							}	
						if($one_question->a7!='')
							{
								$media = $media.'<input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a7).'" type="radio" value="7a'.$question_number.'" name="q'.$question_number.'">'.$one_question->a7.'</input><br />';
								$question_answers_number++;
							}	
						if($one_question->a8!='')
							{
								$question_answers_number++;
								$media = $media.'<input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a8).'" type="radio" value="8a'.$question_number.'" name="q'.$question_number.'">'.$one_question->a8.'</input><br />';
							}
						if($one_question->a9!='')
							{
								$question_answers_number++;
								$media = $media.'<input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a9).'" type="radio" value="9a'.$question_number.'" name="q'.$question_number.'">'.$one_question->a9.'</input><br />';		
							}
						if($one_question->a10!='')
							{
								$question_answers_number++;
								$media = $media.'<input onclick=\'document.getElementById("question_answergived'.$question_number.'").value="'.str_replace("'","$$$$$" ,$one_question->a10).'" type="radio" value="10a'.$question_number.'" name="'.$question_number.'">'.$one_question->a10.'</input><br />';		
							}	
						$media = $media.'</div>';		
						
						$the_first_answer = explode(',', $one_question->answers);
						$the_first_answer = $the_first_answer[0];
						$the_first_answer = str_replace('a', '', $the_first_answer);
						
						if(intval($the_first_answer) == 1)
							$the_right_answer = $one_question->a1;
						if(intval($the_first_answer) == 2)
							$the_right_answer = $one_question->a2;
						if(intval($the_first_answer) == 3)
							$the_right_answer = $one_question->a3;
						if(intval($the_first_answer) == 4)
							$the_right_answer = $one_question->a4;
						if(intval($the_first_answer) == 5)
							$the_right_answer = $one_question->a5;
						if(intval($the_first_answer) == 6)
							$the_right_answer = $one_question->a6;
						if(intval($the_first_answer) == 7)
							$the_right_answer = $one_question->a7;
						if(intval($the_first_answer) == 8)
							$the_right_answer = $one_question->a8;
						if(intval($the_first_answer) == 9)
							$the_right_answer = $one_question->a9;
						if(intval($the_first_answer) == 10)
							$the_right_answer = $one_question->a10;																		
																																											
						
						$media = $media.'<input type="hidden" value="" name="question_answergived'.$question_number.'" id="question_answergived'.$question_number.'" />';
						$media = $media.'<input type="hidden" value="'.str_replace("'","$$$$$" ,$the_right_answer).'" name="question_answerright'.$question_number.'" id="question_answerright'.$question_number.'" />';
						$media = $media.'<input type="hidden" value="'.str_replace("'","&acute;" ,$one_question->text).'" name="the_question'.$question_number.'" id="the_question'.$question_number.'" />';
						
						$question_number++;																																								
					}		
				
				$media = $media.'<input type="hidden" value="'.($question_number-1).'" name="question_number" id="question_number" />';
				$media = $media.'<input type="hidden" value="'.$result_quiz->name.'" id="quize_name" name="quize_name"/>';
				
				
				$media = $media.'<br /><div align="left" style="padding-left:30px;"><input type="submit" value="'.JText::_("GURU_SUBMIT").'" onclick="get_quiz_result()" /></div>';	
			
				$media = $media.'</div>';
			}	
		
		return $media;	
		}	
};
?>