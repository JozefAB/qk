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
	$document->addStyleSheet("components/com_guru/css/trainer_style.css");
	$document->addStyleSheet("components/com_guru/css/guru-j30.css");
	require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");
	
	$document->setTitle(JText::_("GURU_PROGRAM_CATEGORIES"));
	$display = $this->display;
	$k = 0;
	$categ = $this->categ;	
	$subcateg = $this->subcateg;
	$programs = $this->programs;
	$k = count($programs);
	$n = count($subcateg);
	$config = $this->getConfigSettings;
	$config_category = json_decode($config->ctgpage);
	$config_category_style = json_decode($config->st_ctgpage);
	$type = $config_category->ctg_image_size_type == "0" ? "w" : "h";
	$category_layout = "";
	
	 function generateTreeCategoriesList($parent_id, $level){

        $db = JFactory::getDBO();

        $item_id = JRequest::getVar("Itemid", "0");



        $sql = "select c.id, c.name from #__guru_category c, #__guru_categoryrel cr where c.id=cr.child_id and cr.parent_id=".intval($parent_id)." and c.published=1";

        $db->setQuery($sql);

        $db->query();

        $childrens = $db->loadAssocList();



        $i = 1;

        if(isset($childrens) && is_array($childrens) && count($childrens) > 0){

            echo '<div id="categoryList"><ul>';

            $level = $level == 1 ? 2 : 1;

            foreach($childrens as $key=>$value){

                $cat_id = $value["id"] == "0" ? "-1" : $value["id"];

                $sql = "select count(*) from #__guru_program where catid=".($cat_id);

                $db->setQuery($sql);

                $db->query();

                $result = $db->loadColumn();

                if($result["0"] != "0"){

                    echo '<li class="guru_level'.$level.'">';

                    if(!isset($next_nr)){

                        $next_nr = "";

                    }

                    echo $next_nr." ".'<a href="index.php?option=com_guru&view=guruPcategs&task=view&cid='.$value["id"]."-".JFilterOutput::stringURLSafe(trim($value["name"])).'&Itemid='.intval($item_id).'">'.trim($value["name"])." (".$result.")".'</a>';

                    generateTreeCategoriesList($value["id"], $level);

                    echo '</li>';

                }

                $i++;

            }

            echo "</ul></div>";

        }

    }

	function countCoursesNumber($cat_id){

        $db = JFactory::getDBO();

        $sql = "select count(*) from #__guru_program where catid=".intval($cat_id)." and published=1 and `status`='1'";

        $db->setQuery($sql);

        $db->query();

        $single_result = $db->loadColumn();

        $result = 0;



        $sql = "select child_id from #__guru_categoryrel where parent_id=".intval($cat_id);

        $db->setQuery($sql);

        $db->query();

        $ids = $db->loadColumn();

        if(isset($ids) && count($ids) > 0){

            $sql = "select count(*) from #__guru_program where catid in (".implode(", ", $ids).") and published=1 and `status`='1'";

            $db->setQuery($sql);

            $db->query();

            $result = $db->loadColumn();

        }

        return (int)$single_result[0] + (int)$result[0];

    }



    function countSubcategsNumber($cat_id){

        $db = JFactory::getDBO();

        $sql = "select count(*) from #__guru_categoryrel c, #__guru_category ca where c.parent_id=".intval($cat_id)." and c.child_id = ca.id and published=1";

        $db->setQuery($sql);

        $db->query();

        $result = $db->loadColumn();

        return $result["0"];

    }

function ignoreHtml($html, $maxLength=100){
		mb_internal_encoding("UTF-8");
		$printedLength = 0;
		$position = 0;
		$tags = array();
		$newContent = '';
	
		$html = $content = preg_replace("/<img[^>]+\>/i", "", $html);
	
		while ($printedLength < $maxLength && preg_match('{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}', $html, $match, PREG_OFFSET_CAPTURE, $position))
		{
			list($tag, $tagPosition) = $match[0];
			// Print text leading up to the tag.
			$str = mb_strcut($html, $position, $tagPosition - $position);
			if ($printedLength + mb_strlen($str) > $maxLength){
				$newstr = mb_strcut($str, 0, $maxLength - $printedLength);
				$newstr = preg_replace('~\s+\S+$~', '', $newstr);  
				$newContent .= $newstr;
				$printedLength = $maxLength;
				break;
			}
			$newContent .= $str;
			$printedLength += mb_strlen($str);
			if ($tag[0] == '&') {
				// Handle the entity.
				$newContent .= $tag;
				$printedLength++;
			} else {
				// Handle the tag.
				$tagName = $match[1][0];
				if ($tag[1] == '/') {
				  // This is a closing tag.
				  $openingTag = array_pop($tags);
				  assert($openingTag == $tagName); // check that tags are properly nested.
				  $newContent .= $tag;
				} else if ($tag[mb_strlen($tag) - 2] == '/'){
			  // Self-closing tag.
				$newContent .= $tag;
			} else {
			  // Opening tag.
			  $newContent .= $tag;
			  $tags[] = $tagName;
			}
		  }
	
		  // Continue after the tag.
		  $position = $tagPosition + mb_strlen($tag);
		}
	
		// Print any remaining text.
		if ($printedLength < $maxLength && $position < mb_strlen($html))
		  {
			$newstr = mb_strcut($html, $position, $maxLength - $printedLength);
			$newstr = preg_replace('~\s+\S+$~', '', $newstr);
			$newContent .= $newstr;
		  }
	
		// Close any open tags.
		while (!empty($tags))
		  {
			$newContent .= sprintf('</%s>', array_pop($tags));
		  }
	
		return $newContent."...";
	}
	
	function cutBio($full_bio, $description_length, $description_type, $description_mode){
        $original_text = $full_bio;
		$full_bio = strip_tags($full_bio);
		
		if($description_mode == 0){
			// Text
			$original_text = strip_tags($original_text);
		}
		
		if($description_length == "" || strlen($full_bio) <= $description_length){
			return $original_text;
		}
		else{
			if($description_type == "0"){
				$return = ignoreHtml($original_text, $description_length);
				return $return;
			}
			elseif($description_type == "1"){
				$return = "";
				
				$full_bio = str_replace("\r\n", " ", $full_bio);
				$full_bio = str_replace("\r", " ", $full_bio);
				$full_bio = str_replace("\n", " ", $full_bio);
				$full_bio = str_replace("  ", " ", $full_bio);
				
				$words = explode(" ", $full_bio);
				$words = array_slice($words, 0, $description_length);
				$return = implode(" ", $words);
				
				$new_length = strlen($return);
				$return = ignoreHtml($original_text, $new_length + ($description_length - 1));
				return $return;
			}
		}
    }



	
// start the category and sub-category  generation function	
	 function generateCategsCellsB($config_categs, $style_categs, $course, $config){
	 	$guruHelper = new guruHelper();

        $item_id = JRequest::getVar("Itemid", "0");

        $type = $config_categs->ctgs_image_size_type == "0" ? "w" : "h";

        $return = "";

        $layout = $config_categs->ctgslayout;

        $wrap = $config_categs->ctgs_wrap_image; //0-yes, 1-no

        $img_align = $config_categs->ctgs_image_alignment; //0-left, 1-right

        $read_more = $config_categs->ctgs_read_more; //0-yes 1-no

        $read_align = $config_categs->ctgs_read_more_align == "0" ? "left" : "right";

        $description_align = $config_categs->ctgs_description_alignment == "0" ? "left" : "right";

        $edit_read_more = $config_categs->ctgs_read_more;

        $courses_number = countCoursesNumber($course->id);

        $sub_categs_number = countSubcategsNumber($course->id);

        $show_empty_categs = $config_categs->ctgs_show_empty_catgs;

        $show = true;

        $rt = "";

        $detect = new Mobile_Detect;

        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');



         if(isset($course->alias) && $course->alias != ""){

            $alias = trim($course->alias);

        }

        else{

            $alias =  JFilterOutput::stringURLSafe($course->name);

        }

        //$alias = isset($course->alias) == "" ? trim($course->alias) : JFilterOutput::stringURLSafe($course->name);



        if($show_empty_categs == "0"){

            $show = true;

        }

        elseif($show_empty_categs == "1"){

            if(intval($sub_categs_number) > 0 || intval($courses_number) > 0){

                $show = true;

            }

            else{

                $show = false;

            }

        }



        $edit_sum = "";

        $edit_sum_array = array();

        if($sub_categs_number > 0){

            if($sub_categs_number == 1){

                $edit_sum_array[] = $sub_categs_number." ".JText::_("GURU_NUMBER_CATEGORY");

            }

            else{

                $edit_sum_array[] = $sub_categs_number." ".JText::_("GURU_NUMBER_CATEGORIES");

            }

        }

        if($courses_number > 0){

            if($courses_number == 1){

                $edit_sum_array[] = $courses_number." ".JText::_("GURU_NUMBER_COURSE");

            }

            else{

                $edit_sum_array[] = $courses_number." ".JText::_("GURU_NUMBER_COURSES");

            }

        }

        $edit_sum = "";

        if(count($edit_sum_array) > 0){

            $edit_sum = " (".implode(" / ", $edit_sum_array).") ";

        }



        if($deviceType =="phone"){

            $nameandnumb = $course->name."<br/>".$edit_sum;

            $style_m = "padding-left:20px;";

        }

        else{

            $nameandnumb = $course->name.$edit_sum;

            $style_d = "";

        }



        if($show === true){

            if($layout == "1"){//mini profile

                if(trim($course->image) == ""){

                    $course->image = "components/com_guru/images/thumbs/no_image.gif";

                    $course->imageName = "no_image.gif";

                   $guruHelper->createThumb($course->imageName, "components".DS."com_guru".DS."images", $config_categs->ctgs_image_size, $type);

                }

                else{

                    $guruHelper->createThumb($course->imageName, $config->imagesin."/categories", $config_categs->ctgs_image_size, $type);

                }

                $image = "";

                if(trim($course->image) != ""){

                    $image = '<img alt="Category Image" src="'.JURI::root().$course->image.'" />';

                    $image_left = '<a class="thumbnail pull-left" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.$image.'</a>';
					$image_right = '<a class="thumbnail pull-right" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.$image.'</a>';

                }

                $description = cutBio($course->description, $config_categs->ctgs_description_length, $config_categs->ctgs_description_type, $config_categs->ctgs_description_mode);

                if($wrap == "1"){//no wrap
					$class_display = "display:table-cell;";

                    if($img_align == "0"){// left

                        $return .= "<div class='image_guru'>";

                        if(trim($image) != ""){

							$return .= 	$image_left;
                        }

                        $return .= 			'<div class="'.$style_categs->ctgs_categ_name.'">

														<a style="'.$style_d.'" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id.'-'.$alias.'&Itemid='.$item_id).'">'.$nameandnumb.'</a>

													</div>';

                        if($read_more == "0"&& $edit_read_more == "0"){

                            $rt ='<div class="readon"><a class="btn btn-primary" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.JText::_("GURU_READ_MORE").'</a></div>';

                        }

                        elseif($read_more == "1" && $edit_read_more == "0"){

                            $rt ='<div class="readon"><a class="btn btn-primary" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.JText::_("GURU_READ_MORE").'</a></div>';

                        }

                        $return .= 			'<div class="'.$style_categs->ctgs_description.'" style="text-align:'.$description_align.'; '.$style_d.' '.$class_display.'"><p>'.$description.'</p>'.$rt.'</div>';

                        $return .= "</div>";

                    }

                    elseif($img_align == "1"){// right

                        $return .= "<div class='image_guru'>";

                        if(trim($image) != ""){

							$return .=	$image_right;
                        }

                        $return .= 			'<div class="'.$style_categs->ctgs_categ_name.'">

														<a style="'.$style_d.'" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id.'-'.$alias.'&Itemid='.$item_id).'">'.$nameandnumb.'</a>

													</div>';

                        if($read_more == "0"&& $edit_read_more == "0"){

                            $rt ='<div class="readon"><a class="btn btn-primary" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.JText::_("GURU_READ_MORE").'</a></div>';

                        }

                        elseif($read_more == "1" && $edit_read_more == "0"){

                            $rt ='<div class="readon"><a class="btn btn-primary" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.JText::_("GURU_READ_MORE").'</a></div>';

                        }

                        $return .= 			'<div class="'.$style_categs->ctgs_description.'" style="text-align:'.$description_align.'; '.$style_d.' '.$class_display.'"><p>'.$description.'</p>'.$rt.'</div>';

                        $return .= "</div>";
                    }

                }

                elseif($wrap == "0"){//wrap
					 if($img_align == "0"){// left
 						$return .= "<div class='image_guru'>";

                        if(trim($image) != ""){

							$return .= 	$image_left;
                        }

                        $return .= 			'<div class="'.$style_categs->ctgs_categ_name.'">

														<a style="'.$style_d.'" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id.'-'.$alias.'&Itemid='.$item_id).'">'.$nameandnumb.'</a>

													</div>';

                        if($read_more == "0"&& $edit_read_more == "0"){

                            $rt ='<div style="text-align:'.$read_align.'" class="readon">'.'<a class="btn btn-primary" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.JText::_("GURU_READ_MORE").'</a></div>';

                        }

                        elseif($read_more == "1" && $edit_read_more == "0"){

                            $rt ='<div style="text-align:'.$read_align.'" class="readon">'.'<a class="btn btn-primary" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.JText::_("GURU_READ_MORE").'</a></div>';

                        }

                        $return .= 			'<div class="'.$style_categs->ctgs_description.'" style="text-align:'.$description_align.'; '.$style_d.'"><p>'.$description.'</p>'.$rt.'</div>';

                        $return .= "</div>";
					}

                    elseif($img_align == "1"){// right

                        $return .= "<div class='image_guru'>";

                        if(trim($image) != ""){

							$return .= 	$image_right;
                        }

                        $return .= 			'<div class="'.$style_categs->ctgs_categ_name.'">

														<a style="'.$style_d.'" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id.'-'.$alias.'&Itemid='.$item_id).'">'.$nameandnumb.'</a>

													</div>';

                        if($read_more == "0"&& $edit_read_more == "0"){

                            $rt ='<div class="readon"><a class="btn btn-primary" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.JText::_("GURU_READ_MORE").'</a></div>';

                        }

                        elseif($read_more == "1" && $edit_read_more == "0"){

                            $rt ='<div class="readon"><a class="btn btn-primary" href="'.JRoute::_('index.php?option=com_guru&view=guruPcategs&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.JText::_("GURU_READ_MORE").'</a></div>';

                        }

                        $return .= 			'<div class="'.$style_categs->ctgs_description.'" style="text-align:'.$description_align.'; '.$style_d.'"><p>'.$description.'</p>'.$rt.'</div>';

                        $return .= "</div>";
                    }

                }


            }//if mini profile

        }//if show
        return $return;

    }


// start the category and sub-category  generation function	



// start course in the category (Courses in this category:)generation function

function generateCoursesCellsB($config_courses, $style_courses, $course, $config){
		$guruHelper = new guruHelper();
        $detect = new Mobile_Detect;

        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

        $type = $config_courses->courses_image_size_type == "0" ? "w" : "h";

        $return = "";

        $layout = $config_courses->courseslayout;

        $wrap = $config_courses->courses_wrap_image; //0-yes, 1-no

        $img_align = $config_courses->courses_image_alignment; //0-left, 1-right

        $read_more = $config_courses->courses_read_more; //0-yes 1-no

        $read_align = $config_courses->courses_read_more_align == "0" ? "left" : "right";

        $description_align = $config_courses->courses_description_alignment == "0" ? "left" : "right";

        $edit_read_more = $config_courses->courses_read_more;

        $alias = trim($course->alias) == "" ? JFilterOutput::stringURLSafe($course->name) : trim($course->alias);

        $item_id = JRequest::getVar("Itemid", "0");

        $rt = "";

        $style_d = "";



        if($layout == "1"){//mini profile
            $image_name = explode("/", $course->image_avatar);
            $image_name = $image_name[count($image_name)-1];


            if(trim($course->image_avatar) == ""){
                $course->image_avatar = "components/com_guru/images/thumbs/no_image.gif";
                $guruHelper->createThumb($image_name, "components/com_guru/images", $config_courses->courses_image_size, $type);
            }
            else{
                $guruHelper->createThumb($image_name, $config->imagesin."/courses", $config_courses->courses_image_size, $type);
            }

            $image_avatar = "";

            if(trim($course->image_avatar) != ""){
                $image = '<img  src="'.JURI::root().$course->image_avatar.'" />';
                $image_left = '<a class="thumbnail pull-left"  href="'.JRoute::_('index.php?option=com_guru&view=guruPrograms&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.$image.'</a>';
			    $image_right = '<a class="thumbnail pull-right"  href="'.JRoute::_('index.php?option=com_guru&view=guruPrograms&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.$image.'</a>';
            }

            $description = cutBio($course->description, $config_courses->courses_description_length, $config_courses->courses_description_type, $config_courses->courses_description_mode);
            $return .= '<div>';

            if($wrap == "1"){//no wrap
				$class_display = "display:table-cell;";
                if($img_align == "0"){// left
                    $return .= "<div>";
                    if(trim($image) != ""){
							$return .= '<div class="image_guru">'.$image_left.'</div>';
                    }

                    $return .= 			'<div  class="'.$style_courses->courses_name.'">
											<a href="'.JRoute::_('index.php?option=com_guru&view=guruPrograms&task=view&cid='.$course->id."-".$alias."&Itemid=".intval($item_id)).'">'.$course->name.'</a>
										</div>';

                   if($read_more == "0" && $edit_read_more == "0"){
                        $rt ='<div class="readon"><a class="btn btn-primary" style="float:'.$read_align.'" href="'.JRoute::_('index.php?option=com_guru&view=guruPrograms&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.JText::_("GURU_READ_MORE").'</a></div>';
                    }
                    $return .= 			'<div class="'.$style_courses->courses_description.' " style="text-align:'.$description_align.' '.$style_d.'; '.$class_display.'"><p>'.$description.'</p>'.$rt.'</div>';
                    $return .= "</div>";
                }

                elseif($img_align == "1"){// right
                    $return .= "<div>";
					if(trim($image) != ""){
							$return .= "<div class='image_guru'>".$image_right."</div>";
                    }
                    $return .= 			'<div class=" '.$style_courses->courses_name.'">
												<a href="'.JRoute::_('index.php?option=com_guru&view=guruPrograms&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.$course->name.'</a>
											</div>';
                    if($read_more == "0" && $edit_read_more == "0"){
                        $rt ='<div class="readon"><a class="btn btn-primary" style="float:'.$read_align.'" href="'.JRoute::_('index.php?option=com_guru&view=guruPrograms&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.JText::_("GURU_READ_MORE").'</a></div>';
                    }
                    $return .= 			'<div class="'.$style_courses->courses_description.' " style="text-align:'.$description_align.'; '.$class_display.'"><p>'.$description.'</p>'.$rt.'</div>';
                    $return .= "</div>";
                }
            }
            elseif($wrap == "0"){//wrap
                if($img_align == "0"){// left
                    $return .= 		'<div>';
					if(trim($image) != ""){
							$return .= '<div class="image_guru">'.$image_left.'</div>';
                    }
                    $return .= 			'<div class="'.$style_courses->courses_name.'">
												<a href="'.JRoute::_('index.php?option=com_guru&view=guruPrograms&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.$course->name.'</a>
											</div>';
                    if($read_more == "0" && $edit_read_more == "0"){
                        $rt ='<div class="readon"><a class="btn btn-primary" style="float:'.$read_align.'" href="'.JRoute::_('index.php?option=com_guru&view=guruPrograms&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.JText::_("GURU_READ_MORE").'</a></div>';
                    }
                    $return .= 			'<div class="'.$style_courses->courses_description.'" style="text-align:'.$description_align.';"><p>'.$description.'</p>'.$rt.'</div>';
                    $return .= "</div>";
                }
                elseif($img_align == "1"){// right
                    $return .= "<div>";
                    if(trim($image) != ""){
							$return .= "<div class='image_guru'>".$image_right."</div>";
                    }
                    $return .= 			'<div class="'.$style_courses->courses_name.'">
												<a href="'.JRoute::_('index.php?option=com_guru&view=guruPrograms&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.$course->name.'</a>
											</div>';
					if($read_more == "0" && $edit_read_more == "0"){
                        $rt ='<div class="readon"><a class="btn btn-primary" style="float:'.$read_align.'" href="'.JRoute::_('index.php?option=com_guru&view=guruPrograms&task=view&cid='.$course->id."-".$alias."&Itemid=".$item_id).'">'.JText::_("GURU_READ_MORE").'</a></div>';
                    }
                    $return .= 			'<div class=" '.$style_courses->courses_description.'" style="text-align:'.$description_align.';"><p>'.$description.'</p>'.$rt.'</div>';
                    $return .= "</div>";
                }
            }
            $return .= '</div>';

        }//if mini profile
        return $return;

    }


// end course in the category generation function
	$guruHelper = new guruHelper();	
	$detect = new Mobile_Detect;
    $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	if($categ->published == 1){
		$var_inc = 0;
		if(trim($categ->image) == ""){
			$categ->image = "components/com_guru/images/thumbs/no_image.gif";
			$categ->imageName = "no_image.gif";
			$guruHelper->createThumb($categ->imageName, "components/com_guru/images", $config_category->ctg_image_size, $type);
		}
		else{
			$guruHelper->createThumb($categ->imageName, $config->imagesin."/categories", $config_category->ctg_image_size, $type);
		}
	
		if(trim($categ->image) != ""){
			$categ_image = '<img border="0" alt="" src="'.JURI::root().$categ->image.'" />';
		}	
		else{ 
			$categ_image = "";
		}
		$guruModelguruPcateg = new guruModelguruPcateg();
		$no_programs = $guruModelguruPcateg->getnoprograms($categ->id);	
		$return_value = $this->categlist($categ->id);	
		
		$desc_align = $config_category->ctg_description_alignment == "0" ? "left" : "right";
		$desc = $this->cutDescription($categ->description, $config_category->ctg_description_length, $config_category->ctg_description_type, $config_category->ctg_description_mode);
			
			?>
            <div id="categorydetail" class="clearfix com-cont-wrap">
            	<!--<div class="<?php //echo $config_category_style->ctg_name ;?> page_title"><h2><?php //echo $categ->name;?></h2></div>--><!--CATEGORY NAME-->

			<?php
 
				$category_layout .= 
				'<div id="g_cath_detail_top" class="cat_level_wrap cat_level_'.$var_inc.' g_sect clearfix">';
					$category_layout .= 
					   '<div class="cont_detail_guru g_row">
							<div class="cat_cell_guru g_cell span12">
								<!--<div>
									<div>
										<div>-->';
											// if($config_category->ctg_wrap_image == "0"){//wrap image
											// 	if($config_category->ctg_image_alignment == "0"){//image align left			
											// 		if($categ_image != ""){
											// 			$category_layout .= '<div class="thumbnail image_guru pull-left">'.$categ_image.'</div>'; //main category image
											// 		}
											// 		$category_layout .= 	'<div style="text-align:'.$desc_align.';">'.$desc.'</div>';												
											// 	}
											// 	else{//image alignment right
											// 		 if($categ_image != ""){
											// 			$category_layout .= '<div class="thumbnail image_guru pull-right">'.$categ_image.'</div>'; //main category image															
											// 		}
											// 		$category_layout .= 	'<div style="text-align:'.$desc_align.';">'.$desc.'</div>';
											// 	}	
											// }
											// else{
											// 	$class_display = "display:table-cell;";
											// 	if($config_category->ctg_image_alignment == "0"){//image align left
											// 		if($categ_image != ""){
											// 			$category_layout .= '<div class="thumbnail image_guru pull-left">'.$categ_image.'</div>';//main category image
											// 		}
											// 		$category_layout .= 	'<div  style="text-align:'.$desc_align.'; '.$class_display .'">'.$desc.'</div>';												
											// 	}
											// 	else{//image alignment right
											// 		 if($categ_image != ""){
											// 			$category_layout .= '<div class="thumbnail image_guru pull-right">'.$categ_image.'</div>'; //main category image															
											// 		}
											// 		$category_layout .= 	'<div style="text-align:'.$desc_align.'; '.$class_display .'">'.$desc.'</div>';
												   
											// 	}	
												
											// }
					$category_layout .= '</div>
								<!--</div>
							</div>
						</div>-->
					</div>
					';
		
		
			
				// start sub categories------------------------------------
					
					if(isset($subcateg) && count($subcateg) > 0){
						$categs_config = json_decode($config->ctgspage);
						$cols = $categs_config->ctgscols;
						$style_categs = json_decode($config->st_ctgspage);
						$layout = $categs_config->ctgslayout;
						
						if($deviceType =="phone"){
							$style = 'style="width:'.(float)(100/1).'%"';
						}
						else{
							$style = 'style="width:'.(float)(100/$cols).'%"';
							if(count($subcateg) == 1){
								$span = "12";
							}
							else{
								$span=12/$cols;
							}
						}
				
						if($layout == "0"){
							//generateTreeCategoriesList(0, 2);
						}
						else{
							$categs_array = array();	
							for($i=0; $i<count($subcateg); $i++){
								$added_element = generateCategsCellsB($categs_config, $style_categs, $subcateg[$i], $config);
								if(trim($added_element) != ""){
									$categs_array[] = $added_element;
								}	
							}
							$i = 0;
							$var_inc ++;
							$category_layout .= '<div class="cat_level_wrap cat_level_'.$var_inc.'" >
														<div class="g_sect">';

							/*if(count($categs_array) == "1"){
								$cols = 1;
								$category_layout .= '<div class="'.$config_category_style->ctg_name.' page_title"><h3>'.JText::_("GURU_PROGRAM_SUBCATEGORIES").':</h3></div>';
							}
							elseif(count($categs_array) != "0"){
								$category_layout .= '<div class="'.$config_category_style->ctg_name.' page_title"><h3>'.JText::_("GURU_PROGRAM_SUBCATEGORIES").':</h3></div>';
							}*/
							
							while(isset($categs_array[$i])){
								$row = "";
								$row .= '<div class="cont_detail_guru g_row span12" >';
		
								$j = 0;
								while($j < $cols){
									if(!isset($categs_array[$i])){
										$j = $cols;
										$i++;
									}
									elseif(isset($categs_array[$i]) && trim($categs_array[$i]) != ""){	
										$row .= '<div class="course_cell_guru g_cell span'.$span.' ">
												 	<div><div>'.$categs_array[$i]."</div></div>
												 </div>";
										$j++;
									}
									$i++;
								   
								}
								$row .= '</div>';
					
								$category_layout .= $row;
							}
							$category_layout .='</div>
													</div>';		
						}
				}				
				// end sub categories------------------------------------
				
				
				$courses_config = json_decode($config->psgspage);
				$style_courses = json_decode($config->st_psgspage);
				$layout = $courses_config->courseslayout;
				$cols = $courses_config->coursescols;	
				
				if($deviceType =="phone"){
					$style = 'style="width:'.(float)(100/1).'%"';
				}
				else{
					$style = 'style="width:'.(float)(100/$cols).'%"';
					if(count(@$courses_array) == 1){
						$span = "12";
					}
					else{
						$span=12/$cols;
					}
				}
				
				if($layout == "0"){
					if(count($programs) > 0){
						$category_layout .= "<div><ul>";
						foreach($programs as $order){
							if(isset($order->name)){
								$category_layout .= '<li><a href="index.php?option=com_guru&view=guruPrograms&task=view&cid='.$order->id.'">'.$order->name.'</a></li>';
							} 
						}
						$category_layout .= "</ul></div>";
					}
				}
				else{
					$courses_array = array();	
					$generate = new GenerateDisplay();
					for($i=0; $i<count($programs); $i++){	
						$courses_array[] = generateCoursesCellsB($courses_config, $style_courses, $programs[$i], $config);	
					}
					$i = 0;
					$category_layout .= '<div class="cat_list_wrap g_sect clearfix">';
					if(count($courses_array) > 0){
						$category_layout .= '<div class="'.$config_category_style->ctg_name.' section_title"><h4>'.JText::_("GURU_COURSES_IN_CATEGORY").':</h4></div> ';
					}
					
					$category_layout .= '<div class="g_row">';
					while(isset($courses_array[$i])){
						$row = "";
						for($j=0; $j<$cols; $j++){
							if(count($courses_array) == 1){				
								if(isset($courses_array[$i])){
									$row .= '<div class="course_cell_guru g_cell span'.$span.'"><div>'.$courses_array[$i++]."</div></div>";
								}
							}
							else{
								if(isset($courses_array[$i])){
										$row .= '<div class="course_cell_guru g_cell span'.$span.' "><div>'.$courses_array[$i++]."</div></div>";
								}
							}
						}
						$category_layout .= $row;
					}	
					$category_layout .= '</div>
										</div>';
				}
				$category_layout .= '</div>';
			
				echo $category_layout;
			?>
    </div>
    

    <?php	
	}
	else{
		echo JText::_("GURU_NO_CATH");//display the message from GURU_NO_CATH when the category is unpublised 
	}	
?>
<script>
	window.onload = equalHeight('course_cell_guru');
</script>