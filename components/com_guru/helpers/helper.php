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
class guruHelper {
	
	function createBreacrumbs(){
		$bradcrumbs = "";
		$db = JFactory::getDBO();
		$sql = "select `show_bradcrumbs` from #__guru_config";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		$show_bradcrumbs = $result;
		$Home_link = "index.php?option=com_guru";

		if($show_bradcrumbs == "1"){
			$controller = JRequest::getVar("controller", "");
			$task = JRequest::getVar("task", "");
			$position = JRequest::getVar("position", "");
			if(trim($controller) == ""){
				$controller = JRequest::getVar("view", "");
			}

			if($controller == "guruPcategs" && trim($position) == ""){
				$cid = JRequest::getVar("cid", "0");

				$catid = $cid;
				
				$itemid1 = JRequest::getVar("Itemid", "0");
				$sql = "select `name` from #__guru_category where id=".intval($cid);
				$db->setQuery($sql);
				$db->query();
				$categ_name = $db->loadResult();
				
				$bradcrumbs .= '<div id="g_breadcrumb">';
				$bradcrumbs .= 		'<span class="breadcrumbs pathway">';
				$bradcrumbs .= 			'<a class="pathway g_breadcrumb_link" href="'.JRoute::_($Home_link).'">Home</a>&nbsp;&nbsp;';
				
				
				//start - check if this is subcategory
				$sql = "select `parent_id` from #__guru_categoryrel where child_id=".intval($cid);
				$db->setQuery($sql);
				$db->query();
				$parent_id = $db->loadResult();
				$array_bradcrumbs = array();
				while($parent_id != ""){
					if(intval($parent_id) != "0"){
						$sql = "select `name` from #__guru_category where id=".intval($parent_id);
						$db->setQuery($sql);
						$db->query();
						$parent_cat_name = $db->loadResult();
						$array_bradcrumbs[] = '<a class="pathway g_breadcrumb_link" href="'.JRoute::_("index.php?option=com_guru&view=guruPcategs&task=view&cid=".$parent_id."&Itemid=".$itemid).'">'.$parent_cat_name.'</a>&nbsp;&nbsp;';
					}
					$sql = "select `parent_id` from #__guru_categoryrel where child_id=".intval($parent_id);
					$db->setQuery($sql);
					$db->query();
					$parent_id = $db->loadResult();
				}
				if(isset($array_bradcrumbs) && count($array_bradcrumbs) > 0){
					for($i=count($array_bradcrumbs)-1; $i>=0; $i--){
						$bradcrumbs .= $array_bradcrumbs[$i];
					}
				}
				//stop - check if this is subcategory
				
				if($task == ""){
					$bradcrumbs .= 		$categ_name;
				}
				else{
					$pid = JRequest::getVar("cid", "0");
					$sql = "select `name` from #__guru_program where id=".intval($pid);
					$db->setQuery($sql);
					$db->query();
					$product_name = $db->loadResult();
					$bradcrumbs .= 		'<a class="pathway g_breadcrumb_link " href="'.JRoute::_("index.php?option=com_guru&view=guruPcategs&task=view&cid=".$catid."&Itemid=".@$itemid).'">'.$categ_name.'</a>&nbsp;&nbsp;';
				}	
				$bradcrumbs .= 		'</span>';
				$bradcrumbs .= '</div>';
			}

			if($controller == "guruprograms" || $controller == "guruPrograms" && trim($position) == ""){
				$cid = JRequest::getVar("cid", "0");
				
				$sql = "select `catid` from #__guru_program where id=".intval($cid);
				$db->setQuery($sql);
				$db->query();
				$catid = $db->loadResult();
				
				$itemid1 = JRequest::getVar("Itemid", "0");
				$sql = "select `name` from #__guru_category where id=".intval($catid);
				$db->setQuery($sql);
				$db->query();
				$categ_name = $db->loadResult();
				
				$bradcrumbs .= '<div id="g_breadcrumb">';
				$bradcrumbs .= 		'<span class="breadcrumbs pathway">';
				$bradcrumbs .= 			'<a class="pathway g_breadcrumb_link" href="'.JRoute::_($Home_link).'">Home</a>&nbsp;&nbsp;';
				
				//start - check if this is subcategory
				$sql = "select `parent_id` from #__guru_categoryrel where child_id=".intval($catid);
				$db->setQuery($sql);
				$db->query();
				$parent_id = $db->loadResult();
				$array_bradcrumbs = array();
				while($parent_id != ""){
					if(intval($parent_id) != "0"){
						$sql = "select `name` from #__guru_category where id=".intval($parent_id);
						$db->setQuery($sql);
						$db->query();
						$parent_cat_name = $db->loadResult();
						$array_bradcrumbs[] = '<a class="pathway g_breadcrumb_link" href="'.JRoute::_("index.php?option=com_guru&view=guruPcategs&task=view&cid=".$parent_id."&Itemid=".$itemid).'">'.$parent_cat_name.'</a>&nbsp;&nbsp;';
					}
					$sql = "select `parent_id` from #__guru_categoryrel where child_id=".intval($parent_id);
					$db->setQuery($sql);
					$db->query();
					$parent_id = $db->loadResult();
				}
				if(isset($array_bradcrumbs) && count($array_bradcrumbs) > 0){
					for($i=count($array_bradcrumbs)-1; $i>=0; $i--){
						$bradcrumbs .= $array_bradcrumbs[$i];
					}
				}
				//stop - check if this is subcategory
				
				if($task == ""){
					$bradcrumbs .= 		$categ_name;
				}
				else{
					$pid = JRequest::getVar("cid", "0");
					$sql = "select `name` from #__guru_program where id=".intval($pid);
					$db->setQuery($sql);
					$db->query();
					$product_name = $db->loadResult();
					
					$bradcrumbs .= 		'<a class="pathway g_breadcrumb_link" href="'.JRoute::_("index.php?option=com_guru&view=guruPcategs&task=view&cid=".$catid."&Itemid=".@$itemid).'">'.$categ_name.'</a>&nbsp;&nbsp;';
					$bradcrumbs .= 		$product_name;
				}	
				$bradcrumbs .= 		'</span>';
				$bradcrumbs .= '</div>';
			}
		}
		echo $bradcrumbs;
	}


	function getDate($date){
		$db = JFactory::getDBO();
		$sql = "select datetype from #__guru_config where id=1";
		$db->setQuery($sql);
		$db->query();
		$format = $db->loadResult();
		
		$result = date($format, strtotime($date));
		return $result;
	}

	function publishAndExpiryHelper(&$img, &$alt, &$times, &$status, $timestart, $timeend, $published, $configs) {
		$now = time();
		$nullDate = 0;

		if ( $now <= $timestart && $promo->publishing == "1" ) {
	                $img = "tick.png";
        	        $alt = JText::_('HELPERPUBLISHED');
	        } else if ( ( $now <= $timeend || $timeend == $nullDate ) && $published == "1" ) {
        	        $img = "tick.png";
                	$alt = JText::_('HELPERPUBLISHED');
	        } else if ( $now > $timeend && $published == "1" && $timeend != $nullDate) {
        	        $img = "publish_r.png";
                	$alt = JText::_('HELPEREXPIRED');
	        } elseif ( $published == "0" ) {
        	        $img = "publish_x.png";
                	$alt = JText::_('HELPERUNPUBLICHED');
	        }       
  	        $times = '';
          	if (isset( $timestart)) {
          		if ( $timestart == $nullDate) {
                		$times .= "<tr><td>".(JText::_("HELPERALWAWSPUB"))."</td></tr>";
	                } else {
        		        $times .= "<tr><td>".(JText::_("HELPERSTARTAT"))." ".date($configs->time_format, $timestart)."</td></tr>";
	                }
        	}
	        if ( isset( $timeend ) ) {
        	        if ( $timeend == $nullDate) {
                		$times .= "<tr><td>".(JText::_("HELPERNEVEREXP"))."</td></tr>";
	                } else {
        		        $times .= "<tr><td>".(JText::_("HELPEXPAT"))." ".date($configs->time_format, $timeend)."</td></tr>";
	                }
        	}


                $status = '';
		if (!isset ($promo->codelimit)) {
			$promo->codelimit = 0;
		}
		if (!isset ($promo->used)) {
			$promo->used = 0;
		}

		$remain = $promo->codelimit - $promo->used;
		if (($timeend > $now || $timeend == $nullDate )&& ($remain > 0 || $promo->codelimit == 0)) {
			$status = JText::_("HELPERACTIVE");
		} else if ($timeend != $nullDate && $timeend < $now && ($remain < 1 && $promo->codelimit > 0)) {
			$status = "<span style='color:red'>".(JText::_("HELPEREXPIRE"))." (".(JText::_("Date"))." ,".(JText::_("Amount")).")</span>";
		} else if ($remain < 1 && $promo->codelimit > 0) {
			$status = "<span style='color:red'>".(JText::_("HELPEREXPIRE"))." (".(JText::_("Amount")).")</span>";
		} else if ($timeend < $now && $timeend != $nullDate){
			$status = "<span style='color:red'>".(JText::_("HELPEREXPIRE"))." (".(JText::_("Date")).")</span>";
		} else {
			$status = "<span style='color:red'>".(JText::_("HELPERPROMOERROR"))."</span>";
		}

	}

function createThumb($images, $folder, $new_size, $type, $folder_thumbs="thumbs"){
	$mosConfig_absolute_path = JPATH_ROOT;
	$mosConfig_live_site = JURI :: base();
	$folder=str_replace("/",DS,$folder);
	
	if(intval($new_size)>0){
		if(file_exists(JPATH_SITE.DS.$folder.DS.$images)){			
			$old_size = @getimagesize(JPATH_SITE.DS.$folder.DS.$images);
			$width_old = $old_size[0];
			$height_old = $old_size[1];
			if(intval($width_old)==0 || intval($height_old)==0){
				return "";
			}
			
			if($type=='w'){
				//get the correct height
				if($width_old < $new_size){
					$width_new=$width_old;
				}	
			 	else{
					$width_new=$new_size;
				}
 			 	$height_new =intval($width_new*$height_old/$width_old);
			}
			else{
				if($height_old<$new_size){
					$height_new=$height_old;
				}	
				else{
					$height_new=$new_size;
				}	
				$width_new =intval($height_new*$width_old/$height_old); 	
				
			}
		
			if(!is_dir(JPATH_SITE.DS.$folder.DS.$folder_thumbs)){
				mkdir(JPATH_SITE.DS.$folder.DS.$folder_thumbs, 0777);
			}
			
			$images = trim($images);
			//get dir name and file name
			$get_path = explode('/',$images);
			$nr = (count($get_path) - 1);
			//get photo name
			//last value from get_path array
			$photo_name = $get_path[$nr];
			
			unset($get_path[$nr]);
			//get dir name
			$path = implode("/",$get_path);
			//@chmod($mosConfig_absolute_path.'/images/stories'.$path.,0777);					
			//see if thumbnails is created and it have same size return his name
			if(file_exists(JPATH_SITE.DS.$folder.DS.$folder_thumbs.DS.$photo_name)){
				$img_size_thumb = @getimagesize(JPATH_SITE.DS.$folder.$folder_thumbs.DS.$photo_name);
				$width_thumb = $img_size_thumb[0];
				$height_thumb = $img_size_thumb[1];
				
				if($width_thumb == intval($width_new) || $height_thumb == intval($height_new)){
					return true;
				}
			}
		 
			$name_array = explode('.',$photo_name);
			$extension = $name_array[count($name_array)-1];
			$extension = strtolower ($extension);

			
			switch($extension){
				case "jpg":				
					$gdimg = @imagecreatefromjpeg(JPATH_SITE.DS.$folder.DS.$images);
					break;
				case "jpeg":				
					$gdimg = @imagecreatefromjpeg(JPATH_SITE.DS.$folder.DS.$images);
					break;
				case "gif": 
					$gdimg = @imagecreatefromgif(JPATH_SITE.DS.$folder.DS.$images);
					break;
				case "png":
					$gdimg = @imagecreatefrompng(JPATH_SITE.DS.$folder.DS.$images);
					break;
			}
			
			if($extension == "png"){
				$image_p = @imagecreatetruecolor($width_new, $height_new);
				@imagealphablending($image_p, false);
				@imagesavealpha($image_p, true);
				$source = @imagecreatefrompng(JPATH_SITE.DS.$folder.DS.$images);
				@imagealphablending($source, true);
				@imagecopyresampled($image_p, $source, 0, 0, 0, 0, $width_new, $height_new, $width_old, $height_old);
			}
			elseif($extension != 'gif'){
				$image_p = @imagecreatetruecolor($width_new, $height_new);
				$trans = @imagecolorallocate($image_p, 0,0,0);
				@imagecolortransparent($image_p, $trans);
				@imagecopyresampled($image_p, $gdimg, 0, 0, 0, 0, $width_new, $height_new, $width_old, $height_old);
			}
			else{ 	
				$image_p = @imagecreate($width_new, $height_new);
				$trans = @imagecolorallocate($image_p,0,0,0);
				@imagecolortransparent($image_p,$trans);
				@imagecopyresized($image_p, $gdimg, 0, 0, 0, 0, $width_new, $height_new, $width_old, $height_old);				
			}
		
			if($extension == "jpg" || $extension == "JPG"){
				$upload_th = @imagejpeg($image_p, JPATH_ROOT.DS.$folder.DS."thumbs".DS.$photo_name, 100);
			}
			if($extension == "jpeg" || $extension == "JPEG"){
				$upload_th = @imagejpeg($image_p, JPATH_ROOT.DS.$folder.DS."thumbs".DS.$photo_name, 100);			
			}
			if($extension == "gif" || $extension == "GIF"){
				$upload_th = @imagegif($image_p, JPATH_ROOT.DS.$folder.DS."thumbs".DS.$photo_name, 100); 
			}	
			if($extension == "png" || $extension == "PNG"){
				$upload_th = @imagepng($image_p, JPATH_ROOT.DS.$folder.DS."thumbs".DS.$photo_name);
			}
			
			if($upload_th){
				return true;
			}	
			else{
				return false;
			}	
		}
	}	
}    
	
	
	function create_media_using_plugin($main_media, $configs, $aheight, $awidth, $vheight, $vwidth){	
		//require_once(JPATH_SITE.DS.'plugins'.DS.'content'.DS.'jw_allvideos.php');
		$auto_play = "";
		$tag_end = "";
		
		if($main_media->auto_play == "1"){
			$auto_play = "&autoplay=1";
		}
		
		if($main_media->type=='video'){			
			if($main_media->source=='code'){
				$media = $main_media->code;				
			}
			if($main_media->source=='url'){
			if(substr($_SERVER['SERVER_PROTOCOL'], 0, 5) == "https" || substr($_SERVER['SERVER_PROTOCOL'], 0, 5) == "HTTPS"){
					$main_media->url = str_replace("http","https",$main_media->url);
			}
					//$main_media->url .= $auto_play;
					
					//$position_watch = strpos($main_media->url, 'www.youtube.com/watch');
					if (strpos($main_media->url, 'www.youtube.com/watch')!==false)
					{ // youtube link - begin
						$link_array = explode('=',$main_media->url);
						$link_ = $link_array[1].$auto_play; 	
						$media = '{youtube}'.$link_.'{/youtube}';
					} // youtube link - end
					elseif (strpos($main_media->url, 'www.123video.nl')!==false)
					{ // 123video.nl link - begin
						$link_array = explode('=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{123video}'.$link_.'{/123video}';			
					} // 123video.nl link - end
					elseif (strpos($main_media->url, 'www.aniboom.com')!==false)
					{ // aniboom.com link - begin
						$begin_tag = strpos($main_media->url, 'video');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '/');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{aniboom}'.$link_.'{/aniboom}';	
					} // aniboom.com link - end
					elseif (strpos($main_media->url, 'www.badjojo.com')!==false)
					{ // badjojo.com [adult] link - begin
						$link_array = explode('=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{badjojo}'.$link_.'{/badjojo}';
						echo $media;			
					} // badjojo.com [adult] link - end
					elseif (strpos($main_media->url, 'www.brightcove.tv')!==false)
					{ // brightcove.tv link - begin
						$begin_tag = strpos($main_media->url, 'title=');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '&');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{brightcove}'.$link_.'{/brightcove}';	
					} // brightcove.tv link - end
					elseif (strpos($main_media->url, 'www.collegehumor.com')!==false)
					{ // collegehumor.com link - begin
						$link_array = explode(':',$main_media->url);
						$link_ = $link_array[2]; 	
						$media = '{collegehumor}'.$link_.'{/collegehumor}';
					} // collegehumor.com link - end
					elseif (strpos($main_media->url, 'current.com')!==false)
					{ // current.com link - begin
						$begin_tag = strpos($main_media->url, 'items/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '_');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{current}'.$link_.'{/current}';	
					} // current.com link - end
					elseif (strpos($main_media->url, 'dailymotion.com')!==false)
					{ // dailymotion.com link - begin
						$begin_tag = strpos($main_media->url, 'video/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '_');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{dailymotion}'.$link_.'{/dailymotion}';	
					} // dailymotion.com link - end
					elseif (strpos($main_media->url, 'espn')!==false)
					{ // video.espn.com link - begin
						$begin_tag = strpos($main_media->url, 'videoId=');
						$remaining_link = substr($main_media->url, $begin_tag + 8, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '&');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{espn}'.$link_.'{/espn}';	
					} // video.espn.com link - end
					elseif (strpos($main_media->url, 'eyespot.com')!==false)
					{ // eyespot.com link - begin
						$link_array = explode('r=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{eyespot}'.$link_.'{/eyespot}';
					} // eyespot.com link - end
					elseif (strpos($main_media->url, 'flurl.com')!==false)
					{ // flurl.com link - begin
						$begin_tag = strpos($main_media->url, 'video/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '_');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{flurl}'.$link_.'{/flurl}';	
					} // flurl.com link - end
					elseif (strpos($main_media->url, 'funnyordie.com')!==false)
					{ // funnyordie.com link - begin
						$link_array = explode('videos/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{funnyordie}'.$link_.'{/funnyordie}';
					} // funnyordie.com link - end
					elseif (strpos($main_media->url, 'gametrailers.com')!==false)
					{ // gametrailers.com link - begin
						$begin_tag = strpos($main_media->url, 'player/');
						$remaining_link = substr($main_media->url, $begin_tag + 7, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '.');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{gametrailers}'.$link_.'{/gametrailers}';	
					} // gametrailers.com link - end
					elseif (strpos($main_media->url, 'godtube.com')!==false)
					{ // godtube.com link - begin
						$link_array = explode('viewkey=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{godtube}'.$link_.'{/godtube}';
					} // godtube.com link - end
					elseif (strpos($main_media->url, 'gofish.com')!==false)
					{ // gofish.com link - begin
						$link_array = explode('gfid=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{gofish}'.$link_.'{/gofish}';
					} // gofish.com link - end
					elseif (strpos($main_media->url, 'google.com')!==false)
					{ // Google Video link - begin
						$link_array = explode('docid=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{google}'.$link_.'{/google}';
					} // Google Video link - end
					elseif (strpos($main_media->url, 'guba.com')!==false)
					{ // guba.com link - begin
						$link_array = explode('watch/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{guba}'.$link_.'{/guba}';
					} // guba.com link - end
					elseif (strpos($main_media->url, 'hook.tv')!==false)
					{ // hook.tv link - begin
						$link_array = explode('key=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{hook}'.$link_.'{/hook}';
					} // hook.tv link - end
					elseif (strpos($main_media->url, 'jumpcut.com')!==false)
					{ // jumpcut.com link - begin
						$link_array = explode('id=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{jumpcut}'.$link_.'{/jumpcut}';
					} // jumpcut.com link - end
					elseif (strpos($main_media->url, 'kewego.com')!==false)
					{ // kewego.com link - begin
						$begin_tag = strpos($main_media->url, 'video/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '.');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{kewego}'.$link_.'{/kewego}';	
					} // kewego.com link - end
					elseif (strpos($main_media->url, 'krazyshow.com')!==false)
					{ // krazyshow.com [adult] link - begin
						$link_array = explode('cid=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{krazyshow}'.$link_.'{/krazyshow}';
					} // krazyshow.com [adult] link - end
					elseif (strpos($main_media->url, 'ku6.com')!==false)
					{ // ku6.com link - begin
						$begin_tag = strpos($main_media->url, 'show/');
						$remaining_link = substr($main_media->url, $begin_tag + 5, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '.');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{ku6}'.$link_.'{/ku6}';	
					} // ku6.com link - end
					elseif (strpos($main_media->url, 'liveleak.com')!==false)
					{ // liveleak.com link - begin
						$link_array = explode('i=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{liveleak}'.$link_.'{/liveleak}';
					} // liveleak.com link - end
					elseif (strpos($main_media->url, 'metacafe.com')!==false)
					{ // metacafe.com link - begin
						$begin_tag = strpos($main_media->url, 'watch/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{metacafe}'.$link_.'{/metacafe}';	
					} // metacafe.com link - end
					elseif (strpos($main_media->url, 'mofile.com')!==false)
					{ // mofile.com link - begin
						$begin_tag = strpos($main_media->url, 'com/');
						$remaining_link = substr($main_media->url, $begin_tag + 4, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '/');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{mofile}'.$link_.'{/mofile}';	
					} // mofile.com link - end
					elseif (strpos($main_media->url, 'myspace.com')!==false)
					{ // myspace.com link - begin
						$link_array = explode('VideoID=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{myspace}'.$link_.'{/myspace}';
					} // myspace.com link - end
					elseif (strpos($main_media->url, 'myvideo.de')!==false)
					{ // myvideo.de link - begin
						$begin_tag = strpos($main_media->url, 'watch/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '/');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{myvideo}'.$link_.'{/myvideo}';	
					} // myvideo.de link - end
					elseif (strpos($main_media->url, 'redtube.com')!==false)
					{ // redtube.com [adult] link - begin
						$link_array = explode('/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{redtube}'.$link_.'{/redtube}';
					} // redtube.com [adult] - end
					elseif (strpos($main_media->url, 'revver.com')!==false)
					{ // revver.com link - begin
						$begin_tag = strpos($main_media->url, 'video/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '/');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{revver}'.$link_.'{/revver}';	
					} // revver.com link - end
					elseif (strpos($main_media->url, 'sapo.pt')!==false)
					{ // sapo.pt link - begin
						$link_array = explode('pt/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{sapo}'.$link_.'{/sapo}';
					} // sapo.pt - end
					elseif (strpos($main_media->url, 'sevenload.com')!==false)
					{ // sevenload.com link - begin
						$begin_tag = strpos($main_media->url, 'videos/');
						$remaining_link = substr($main_media->url, $begin_tag + 7, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '-');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{sevenload}'.$link_.'{/sevenload}';	
					} // sevenload.com link - end
					elseif (strpos($main_media->url, 'sohu.com')!==false)
					{ // sohu.com link - begin
						$link_array = explode('/',$main_media->url);
						$link_ = $link_array[count($link_array)-1]; 	
						$media = '{sohu}'.$link_.'{/sohu}';
					} // sohu.com - end
					elseif (strpos($main_media->url, 'southparkstudios.com')!==false)
					{ // southparkstudios.com link - begin
						$begin_tag = strpos($main_media->url, 'clips/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '/');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{southpark}'.$link_.'{/southpark}';	
					} // southparkstudios.com link - end
					elseif (strpos($main_media->url, 'spike.com')!==false)
					{ // spike.com link - begin
						$link_array = explode('video/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{spike}'.$link_.'{/spike}';
					} // spike.com - end
					elseif (strpos($main_media->url, 'stickam.com')!==false)
					{ // stickam.com link - begin
						$link_array = explode('mId=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{stickam}'.$link_.'{/stickam}';
					} // stickam.com - end
					elseif (strpos($main_media->url, 'stupidvideos.com')!==false)
					{ // stupidvideos.com link - begin
						$link_array = explode('#',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{stupidvideos}'.$link_.'{/stupidvideos}';
					} // stupidvideos.com - end
					elseif (strpos($main_media->url, 'tudou.com')!==false)
					{ // tudou.com link - begin
						$begin_tag = strpos($main_media->url, 'view/');
						$remaining_link = substr($main_media->url, $begin_tag + 5, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '/');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{tudou}'.$link_.'{/tudou}';	
					} // tudou.com link - end
					elseif (strpos($main_media->url, 'ustream.tv')!==false)
					{ // ustream.tv link - begin
						$link_array = explode('recorded/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{ustream}'.$link_.'{/ustream}';
					} // ustream.tv - end
					elseif (strpos($main_media->url, 'veoh.com')!==false)
					{ // veoh.com link - begin
						$link_array = explode('videos/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{veoh}'.$link_.'{/veoh}';
					} // veoh.com - end
					elseif (strpos($main_media->url, 'videotube.de')!==false)
					{ // videotube.de link - begin
						$link_array = explode('watch/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{videotube}'.$link_.'{/videotube}';
					} // videotube.de - end
					elseif (strpos($main_media->url, 'vidiac.com')!==false)
					{ // vidiac.com link - begin
						$begin_tag = strpos($main_media->url, 'video/');
						$remaining_link = substr($main_media->url, $begin_tag + 6, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '.');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{vidiac}'.$link_.'{/vidiac}';	
					} // vidiac.com link - end
					elseif (strpos($main_media->url, 'vimeo.com')!==false)
					{ // vimeo.com link - begin
						$link_array = explode('.com/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{vimeo}'.$link_.'{/vimeo}';
					} // vimeo.com - end
					elseif (strpos($main_media->url, 'yahoo.com')!==false)
					{ // video.yahoo.com link - begin
						$link_array = explode('watch/',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{yahoo}'.$link_.'{/yahoo}';			
					} // video.yahoo.com - end
					elseif (strpos($main_media->url, 'youare.tv')!==false)
					{ // youare.tv link - begin
						$link_array = explode('id=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{youare}'.$link_.'{/youare}';			
					} // youare.tv - end
					elseif (strpos($main_media->url, 'youku.com')!==false)
					{ // youku.com link - begin
						$begin_tag = strpos($main_media->url, 'v_show/');
						$remaining_link = substr($main_media->url, $begin_tag + 7, strlen($main_media->url));
						$end_tag = strpos($remaining_link, '.');
						if($end_tag===false) $end_tag = strlen($remaining_link);
						$link_ = substr($remaining_link, 0, $end_tag);	
						$media = '{youku}'.$link_.'{/youku}';	
					} // youku.com link - end
					elseif (strpos($main_media->url, 'youmaker.com')!==false)
					{ // youmaker.com  link - begin
						$link_array = explode('id=',$main_media->url);
						$link_ = $link_array[1]; 	
						$media = '{youmaker}'.$link_.'{/youmaker}';			
					} // youmaker.com  - end
					else
					{
						//----------- not special link - begin
						$extension_array=explode('.',$main_media->url);
						$extension = $extension_array[count($extension_array)-1];
					
						if(strtolower($extension)=='flv' || strtolower($extension)=='swf' || strtolower($extension)=='mov' || strtolower($extension)=='wmv' || strtolower($extension)=='mp4' || strtolower($extension)=='divx')
							{
								$tag_begin = '{'.strtolower($extension).'remote}';
								$tag_end = '{/'.strtolower($extension).'remote}';
							}	
						if(!isset($tag_begin)) {$tag_begin=NULL;}
						if(!isset($tag_end)) {$tag_end=NULL;}
						$media = $tag_begin.$main_media->url.$auto_play.$tag_end;
						//----------- not special link - begin										
					}
					
					$media = guruHelper::jwAllVideos( $media, $aheight, $awidth, $vheight, $vwidth, 0);
				}
				
				//$media = '<a target="_blank" href="'.$main_media->url.'">'.$main_media->name.'</a>';	
			if($main_media->source=='local')
				{	
					if($main_media->auto_play == "1"){
						$autoplay = 'true';
					}
					else {
						$autoplay = 'false';
					}			
					$extension_array=explode('.',$main_media->local);
					$extension = $extension_array[count($extension_array)-1];
					//echo $extension;
					if(strtolower($extension)=='flv' || strtolower($extension)=='swf' || strtolower($extension)=='mov' || strtolower($extension)=='wmv' || strtolower($extension)=='mp4' || strtolower($extension)=='divx')
						{
							$tag_begin = '{'.strtolower($extension).'remote}';
							$tag_end = '{/'.strtolower($extension).'remote}';
						}	
					if(!isset($tag_begin)) {$tag_begin=NULL;}
					if(!isset($tag_end)) {$tag_end=NULL;}				
					$media = $tag_begin.str_replace("/administrator","",JURI::base()).$configs->videoin.'/'.$main_media->local.$tag_end;
					$guru_media_autoplay = "";
					$media = guruHelper::jwAllVideos( $media, $aheight, $awidth, $vheight, $vwidth, $autoplay);
				}
		}	
		
		if($main_media->type=='audio')
		{				
			if($main_media->auto_play == "1"){	
				$guru_media_autoplay = TRUE;
			}
			else{
				$guru_media_autoplay = FALSE;	
			}
			
			if($main_media->source=='code'){
				$media = $main_media->code;
			}
			
			if($main_media->source=='url'){
				$extension_array=explode('.',$main_media->url);
				$extension = $extension_array[count($extension_array)-1];
				if(strtolower($extension)=='mp3' || strtolower($extension)=='wma' || strtolower($extension)=='m4a'){
					$tag_begin = '{'.strtolower($extension).'remote}';
					$tag_end = '{/'.strtolower($extension).'remote}';
				}
				$media = @$tag_begin.$main_media->url.$tag_end;
				$media = guruHelper::jwAllVideos( $media, $aheight, $awidth, $vheight, $vwidth, $guru_media_autoplay);
			}
			
			if($main_media->source=='local'){
				$extension_array=explode('.',$main_media->local);
				$extension = $extension_array[count($extension_array)-1];
				
				if(strtolower($extension)=='mp3' || strtolower($extension)=='wma'){
					$tag_begin = '{'.strtolower($extension).'remote}';
					$tag_end = '{/'.strtolower($extension).'remote}';
				}
				$media = $tag_begin.str_replace("/administrator","",JURI::base()).$configs->audioin.'/'.$main_media->local.$tag_end;					
				$media = guruHelper::jwAllVideos( $media, $aheight, $awidth, $vheight, $vwidth, $guru_media_autoplay);
			}
		}
	if($main_media->type=='url')
		{
			$media = '<a target="_blank" href="'.$main_media->url.'">'.$main_media->name.'</a>';	
		}		
	if($main_media->type=='docs')
		{
			if($main_media->source=='url')
				$media = '<a target="_blank" href="'.$main_media->url.'">'.$main_media->name.'</a>';	
			if($main_media->source=='local')
				$media = '<a target="_blank" href="'.str_replace("/administrator","",JURI::base()).'/'.$configs->docsin.'/'.$main_media->local.'">'.$main_media->name.'</a>';
		}				
		if(isset($media)){return $media;} else {return NULL;}
	}	
	
	public static function jwAllVideos( &$row, $parawidth=300, $paraheight=20, $parvwidth=400, $parvheight=300, $auto_play){
		$app = JFactory::getApplication('administrator');
		$plg_name					= "jw_allvideos";
		$plg_tag					= "";
		$plg_copyrights_start		= "\n\n<!-- JoomlaWorks \"AllVideos\" Plugin (v4.5.0) starts here -->\n";
		$plg_copyrights_end			= "\n<!-- JoomlaWorks \"AllVideos\" Plugin (v4.5.0) ends here -->\n\n";
		$mosConfig_live_site = JURI::root();
		$document  = JFactory::getDocument();
		
    	if(substr($mosConfig_live_site, -1)=="/"){
			$mosConfig_live_site = substr($mosConfig_live_site, 0, -1);
		}
		jimport('joomla.filesystem.file');

		if(JPluginHelper::isEnabled('content',$plg_name)==false) return;
		
		include(JPATH_SITE.DS."plugins".DS."content".DS."jw_allvideos".DS."jw_allvideos".DS."includes".DS."sources.php");

		$grabTags = str_replace("(","",str_replace(")","",implode(array_keys($tagReplace),"|")));
		
		if(preg_match("#{(".$grabTags.")}#s", $row)==false){
			return;
		}
		
		$av_template			= 'getault';
		$av_compressjs			= 0;
		// video
		$vfolder 				= 'images/stories/videos';
		$vwidth 				= $parvwidth;
		$vheight 				= $parvheight;
		// audio
		$afolder 				= '';
		$awidth 				= $parawidth;
		$aheight 				= $paraheight;
		// global
		$autoplay 				= $auto_play;
		$transparency 			= 'transparent';
		$background 			= '#FFFFFF';
		// FLV playback
		$av_flvcontroller 		= 'bottom';	
	
		if($av_flvcontroller == "over"){
			$av_flvcontroller = "&controlbar=over";
		} else {
			$av_flvcontroller = "";
		}

		// Variable cleanups for K2
		if(JRequest::getCmd('format')=='raw'){
			$this->plg_copyrights_start = '';
			$this->plg_copyrights_end = '';
		}

		// ----------------------------------- Render the output -----------------------------------

		// Append head includes only when the document is in HTML mode
		if(JRequest::getCmd('format')=='html' || JRequest::getCmd('format')==''|| JRequest::getCmd('format')=='raw'){
			// CSS
			//$avCSS = $AllVideosHelper->getTemplatePath($this->plg_name,'css/template.css',$playerTemplate);
			@$avCSS = $avCSS->http;
			$document->addStyleSheet(@$avCSS);

			// JS
			
		  JHtml::_('behavior.framework');
		
			if(0){
	
				$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/jw_allvideos.js.php');
			} 
			else{
				$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/behaviour.js');
				$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/mediaplayer/jwplayer.min.js');
				$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/wmvplayer/silverlight.js');
				$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/wmvplayer/wmvplayer.js');
				$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/quicktimeplayer/AC_QuickTime.js');
			}
		}		

		// START ALLVIDEOS LOOP	
		
		$document  = JFactory::getDocument();
		$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/jw_allvideos.js.php');
		$document->addScript($mosConfig_live_site.'/plugins/content/jw_allvideos/jw_allvideos/includes/js/behaviour.js');

		foreach ($tagReplace as $plg_tag => $value) {

			// expression to search for
			$regex = "#{".$plg_tag."}.*?{/".$plg_tag."}#s";
			// process tags

			if(preg_match_all($regex, $row, $matches, PREG_PATTERN_ORDER)) {
				// start the replace loop
				foreach ($matches[0] as $key => $match) {

					$tagcontent 		= preg_replace("/{.+?}/", "", $match);
					$tagparams 			= explode('|',$tagcontent);
					$tagsource 			= trim(strip_tags($tagparams[0]));

					// Prepare the HTML
					$output = new JObject;

					// Width/height/source folder split per media type

					if(in_array($plg_tag, array(
						'mp3',
						'mp3remote',
						'aac',
						'aacremote',
						'm4a',
						'm4aremote',
						'ogg',
						'oggremote',
						'wma',
						'wmaremote',
						'soundcloud'
					))){
						$final_awidth 	= (@$tagparams[1]) ? $tagparams[1] : $awidth;
						$final_aheight 	= (@$tagparams[2]) ? $tagparams[2] : $aheight;

						$output->playerWidth = $final_awidth;
						$output->playerHeight = $final_aheight;
						$output->folder = $afolder;


						if($plg_tag=='soundcloud'){
							if(strpos($tagsource,'/sets/')!==false){
								$output->mediaTypeClass = ' avSoundCloudSet';
							} else {
								$output->mediaTypeClass = ' avSoundCloudSong';
							}
							$output->mediaType = '';
						} else {
							$output->mediaTypeClass = ' avAudio';
							$output->mediaType = 'audio';
						}

						if(in_array($plg_tag, array('mp3','aac','m4a','ogg','wma'))){
							$output->source = "$siteUrl/$afolder/$tagsource.$plg_tag";
						} elseif(in_array($plg_tag, array('mp3remote','aacremote','m4aremote','oggremote','wmaremote'))){
							$output->source = $tagsource;

						} else {
							$output->source = '';
						}
					} else {
						$final_vwidth 	= (@$tagparams[1]) ? $tagparams[1] : $vwidth;
						$final_vheight 	= (@$tagparams[2]) ? $tagparams[2] : $vheight;

						$output->playerWidth = $final_vwidth;
						$output->playerHeight = $final_vheight;
						$output->folder = $vfolder;
						$output->mediaType = 'video';
						$output->mediaTypeClass = ' avVideo';
					}

					// Autoplay
					$final_autoplay = (@$tagparams[3]) ? $tagparams[3] : $autoplay;
					$final_autoplay	= ($final_autoplay) ? 'true' : 'false';

					// Special treatment for specific video providers
					if($plg_tag=="dailymotion"){
						$tagsource = preg_replace("~(http|https):(.+?)dailymotion.com\/video\/~s","",$tagsource);
						$tagsourceDailymotion = explode('_',$tagsource);
						$tagsource = $tagsourceDailymotion[0];
						if($final_autoplay=='true'){
							if(strpos($tagsource,'?')!==false){
								$tagsource = $tagsource.'&amp;autoPlay=1';
							} else {
								$tagsource = $tagsource.'?autoPlay=1';
							}
						}
					}

					if($plg_tag=="ku6"){
						$tagsource = str_replace('.html','',$tagsource);
					}

					if($plg_tag=="metacafe" && substr($tagsource,-1,1)=='/'){
						$tagsource = substr($tagsource,0,-1);
					}

					if($plg_tag=="tnaondemand"){
						$tagsource = parse_url($tagsource);
						$tagsource = explode('&',$tagsource['query']);
						$tagsource = str_replace('vidid=','',$tagsource[0]);
					}

					if($plg_tag=="twitvid"){
						$tagsource = preg_replace("~(http|https):(.+?)twitvid.com\/~s","",$tagsource);
						if($final_autoplay=='true'){
							$tagsource = $tagsource.'&amp;autoplay=1';
						}
					}

					if($plg_tag=="vidiac"){
						$tagsourceVidiac = explode(';',$tagsource);
						$tagsource = $tagsourceVidiac[0];
					}

					if($plg_tag=="vimeo"){
				
						$tagsource = preg_replace("~(http|https):(.+?)vimeo.com\/~s","",$tagsource);
							
						if(strpos($tagsource,'?')!==false){
							$tagsource = $tagsource.'&amp;portrait=0&amp;autoplay='.$auto_play.'';
						} else {
							$tagsource = $tagsource.'?portrait=0&amp;autoplay='.$auto_play.'';
						}
						if($final_autoplay=='true'){
							$tagsource = $tagsource.'&amp;autoplay='.$auto_play.'';
						}
					}

					if($plg_tag=="yahoo"){
						$tagsourceYahoo = explode('-',str_replace('.html','',$tagsource));
						$tagsourceYahoo = array_reverse($tagsourceYahoo);
						$tagsource = $tagsourceYahoo[0];
					}

					if($plg_tag=="yfrog"){
						$tagsource = preg_replace("~(http|https):(.+?)yfrog.com\/~s","",$tagsource);
					}

					if($plg_tag=="youmaker"){
						$tagsourceYoumaker = explode('-',str_replace('.html','',$tagsource));
						$tagsource = $tagsourceYoumaker[1];
					}

					if($plg_tag=="youku"){
						$tagsource = str_replace('.html','',$tagsource);
						$tagsource = substr($tagsource,3);
					}

					if($plg_tag=="youtube"){
						$tagsource = preg_replace("~(http|https):(.+?)youtube.com\/watch\?v=~s","",$tagsource);
						$tagsourceYoutube = explode('&',$tagsource);
						$tagsource = $tagsourceYoutube[0];

						if(strpos($tagsource,'?')!==false){
							$tagsource = $tagsource.'&amp;rel=0&amp;fs=1&amp;wmode=transparent';
						} else {
							$tagsource = $tagsource.'?rel=0&amp;fs=1&amp;wmode=transparent';
						}
						if($final_autoplay=='true'){
							$tagsource = $tagsource.'&amp;autoplay=1';
						}
					}

					
				// Set a unique ID
				$output->playerID = 'AVPlayerID_'.substr(md5($tagsource),1,8).'_'.rand();

				if(@$guru_media_autoplay != ""){
				// is audio	
					$final_autoplay = (@$tagparams[3]) ? $tagparams[3] : @$guru_media_autoplay;
				}
				else{
				// is video
					$final_autoplay = (@$tagparams[3]) ? $tagparams[3] : $auto_play;
				}	
				// replacements
				$findAVparams = array(
					"{SITEURL}",
					"{SOURCE}",
					"{SOURCEID}",
					"{FOLDER}",
					"{WIDTH}",
					"{HEIGHT}",		
					"{AUTOPLAY}",
					"{TRANSPARENCY}",
					"{PLAYER_BACKGROUND}",
					"{PLAYER_ABACKGROUND}",
					"{CONTROLBAR}"
				);
				
				// special treatment
				if($plg_tag=="yahoo"){
					$tagsourceyahoo = explode('/',$tagsource);
					$tagsource = 'id='.$tagsourceyahoo[1].'&amp;vid='.$tagsourceyahoo[0];
				}
				if($plg_tag=="youku"){
					$tagsource = substr($tagsource,3);
				}				
				
				// replacement elements
					if(in_array($plg_tag, array("mp3", "mp3remote", "m4a", "m4aremote", "wma", "wmaremote"))){
						$replaceAVparams = array(
							JURI::root(),
							$tagsource,
							substr(md5($tagsource),1,8),
							$afolder,
							$awidth,
							$aheight,
							$final_autoplay,
							$transparency,
							$background,
							$background,
							@$controlBarLocation		
						);
						
						$output->playerWidth = $awidth;
						$output->playerHeight = $aheight;
						
					}
					else{
						require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");
						$detect = new Mobile_Detect;
						$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

						if($deviceType == 'phone'){
							$final_vwidth = "100%";
							$final_vheight = "";
						}
						
						$replaceAVparams = array(
							JURI::root(),
							$tagsource,
							substr(md5($tagsource),1,8),
							$vfolder,
							$final_vwidth,
							$final_vheight,
							$final_autoplay,
							$transparency,
							$background,
							$background,
							@$controlBarLocation	
						);
					}


					//$plg_html = JFilterOutput::ampReplace($wrapstart.str_replace($findAVparams, $replaceAVparams, $tagReplace[$plg_tag]).$wrapend);
					$plg_html = str_replace($findAVparams, $replaceAVparams, $tagReplace[$plg_tag]);				
					
					// Do the replace
					$row = preg_replace("#{".$plg_tag."}".preg_quote($tagcontent)."{/".$plg_tag."}#s", $plg_html , $row);
					
					$pluginLivePath = JURI::root(true).'/plugins/content/jw_allvideos';
					$row = str_replace("{PLUGIN_PATH}", $pluginLivePath, $row);
					
				} // end foreach
			} // end if
		} // END ALLVIDEOS LOOP
		
		$extension_array = explode(".", $tagsource);
		$extension = $extension_array[count($extension_array) - 1];
		
		if($extension == "mp4"){
			$auto_play_object = "";
			
			if($final_autoplay == "false"){
				$auto_play_object = 'autoplay="false"';
			}
			else{
				$auto_play_object = 'autoplay="true"';
			}
			
			$row = '<object height="'.$final_vheight.'" width="'.$final_vwidth.'" data="'.$tagsource.'" '.$auto_play_object.'></object>';
		}
		return $row;
	} // END FUNCTION
	
	function transformPagination($pages){
		if(strpos(" ".$pages, '<ul>') !== FALSE){
			$pages = str_replace("<ul>", '<ul class="uk-pagination">', $pages);
		}
		$pages = str_replace('<ul class="pagination-list">', '<ul class="uk-pagination">', $pages);

		preg_match_all('/<a(.*)>(.*)<\/a>/msU', $pages, $matches);
		
		if(isset($matches) && count($matches) > 0){
			foreach($matches["0"] as $key=>$link){
				if(strpos($link, "limitstart=") !== FALSE){
					preg_match_all('/limitstart=(.*)"/msU', $link, $limit);
					if(isset($limit["1"]["0"])){
						$limitstart = intval($limit["1"]["0"]);
						$url_text = $matches["2"][$key];
						$url_text = preg_replace("/title=(.*)>/msU", "", $url_text);
						
						$new_link = '<a onclick="document.adminForm.limitstart.value='.intval($limitstart).'; Joomla.submitform();return false;" href="#">'.trim($url_text).'</a>';
						$pages = str_replace($link, $new_link, $pages);
					}
				}
				elseif(strpos($link, "start=") !== FALSE){
					preg_match_all('/start=(.*)"/msU', $link, $limit);
					if(isset($limit["1"]["0"])){
						$limitstart = intval($limit["1"]["0"]);
						$url_text = $matches["2"][$key];
						$url_text = preg_replace("/title=(.*)>/msU", "", $url_text);
						
						$new_link = '<a onclick="document.adminForm.limitstart.value='.intval($limitstart).'; Joomla.submitform();return false;" href="#">'.trim($url_text).'</a>';
						$pages = str_replace($link, $new_link, $pages);
					}
				}
			}
			$pages .= '<input type="hidden" value="0" name="limitstart">';
		}
		$pages = '<div class="pagination pagination-centered">'.$pages.'</div>';

		return $pages;
	}
};
?>