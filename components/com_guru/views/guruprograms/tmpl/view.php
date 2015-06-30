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
$k = 0;
// --------------------------- unload ijseo_plugin
jimport( 'joomla.plugin.helper' );
class iJoomlaPlugin extends JPluginHelper{
	function unloadFromPlugin($type, $name){
		$plugins = JPluginHelper::getPlugin("content");
		$plugins = JPluginHelper::$plugins;
		
		if(isset($plugins) && count($plugins) > 0){
			foreach($plugins as $key=>$value){
				if($value->name == $name && $value->type == $type){
					unset($plugins[$key]);
					JPluginHelper::$plugins = $plugins;
					break;
				}
			}
		}
	}
}
$db = JFactory::getDBO();
$sql = "SELECT 	guru_ignore_ijseo from #__guru_config where id =1";
$db->setQuery($sql);
$db->query();
$res = $db->loadResult();

if($res == 0){
	$iJoomlaPlugin = new iJoomlaPlugin();
	$iJoomlaPlugin->unloadFromPlugin("content", "ijseo_plugin");
}
// --------------------------- unload ijseo_plugin


require_once(JPATH_BASE . "/components/com_guru/helpers/Mobile_Detect.php");

$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	
$document = JFactory::getDocument();
$guruHelper = new guruHelper ();

$db = JFactory::getDBO();
$sql = "SELECT guru_turnoffjq  FROM  `#__guru_config` WHERE id=1";
$db->setQuery($sql);
$db->query();
$guru_turnoffjq = $db->loadResult();

//if( $guru_turnoffjq != 0){ 
	$document->addScript('components/com_guru/js/1jqueryui.js');	
	$document->addScript('components/com_guru/js/jquery-1.9.1.min.js');	
//}




$document->addScriptDeclaration('
	jQuery.noConflict();
	jQuery(function(){
		jQuery(".subcat").find("hr:last").css("display","block");
		jQuery(".show_sub").click(function(){
			jQuery(".subcat").slideDown().parent().css("border-bottom","2px solid #F7F7F7 !important");
		});
		jQuery(".close_sub").click(function(){
			jQuery(".subcat").slideUp().parent().css("border-bottom","none");
		});		
	});
');


$document->addStyleSheet("components/com_guru/css/guru-j30.css");

$program 			= $this->program;	
$config				= $this->getConfigSettings;
$pdays 				= $this->pdays;	
$author				= $this->author;
$courses			= $this->courses;
$programContent		= $this->programContent;
$exercise			= $this->exercise;
$requirements		= $this->requirements;
$number_of_days_per_program = count($pdays);

// how many points and how much time has a program
//$getsum_points_and_time = $this->getsum_points_and_time;

$my = JFactory::getUser();

$document	= JFactory::getDocument();
$document->setTitle($program->metatitle);
$document->setMetaData('keywords', $program->metakwd); 
$document->setMetaData('description', $program->metadesc); 

$db = JFactory::getDBO();
$sql = "SELECT chb_free_courses, step_access_courses, selected_course  FROM `#__guru_program` where id = ".intval($program->id);
$db->setQuery($sql);
$db->query();
$result= $db->loadAssocList();
$chb_free_courses = $result["0"]["chb_free_courses"];
$step_access_courses = $result["0"]["step_access_courses"];
$selected_course = $result["0"]["selected_course"];


$selected_course_final = explode('|', $selected_course);
if(implode(", ", $selected_course_final) != ''){
	foreach($selected_course_final as $key=>$value){
		if(trim($value) == ""){
			unset($selected_course_final[$key]);
		}
	}
	
	
	$sql = "select name, id from #__guru_program where id in (".implode(", ", $selected_course_final).")";
	
	$db->setQuery($sql);
	$db->query();
	$result = $db->loadAssocList();
	$all_title = array();
	$itemid = JRequest::getVar("Itemid", "0");
	if(isset($result) && count($result) > 0){
		foreach($result as $key=>$course){
			$all_title[] = $course["name"];
		}
	}
	$all_title = implode(", ", $all_title);
}
$sql = "select certificate_term from #__guru_program  where id = ".intval($program->id);
$db->setQuery($sql);
$db->query();
$certificate_term = $db->loadResult();

$sql = "select avg_certc from #__guru_program where id = ".intval($program->id);
$db->setQuery($sql);
$db->query();
$avg_cert = $db->loadResult();


$sql = "SELECT max_score FROM #__guru_quiz WHERE is_final= 1 LIMIT 1";
$db->setQuery($sql);
$result_maxs = $db->loadResult();


if($config->display_media == 1){
	$guruModelguruProgram = new guruModelguruProgram();
	$the_media = $guruModelguruProgram->find_intro_media($program->id);
	$no_plugin_for_code = 0;
	$aheight = 0; 
	$awidth = 0; 
	$vheight = 0; 
	$vwidth = 0;
	if(isset($the_media)){
		$the_media->code = stripslashes($the_media->code);
		if($the_media->type == 'video'){
			if($the_media->source == 'url' || $the_media->source == 'local'){
				if ($the_media->width == 0 || $the_media->height == 0){
					$vheight = 300; 
					$vwidth = 400;
				}
				else{
					$vheight=$the_media->height; $vwidth=$the_media->width;
				}		
			}
			elseif ($the_media->source=='code'){
				if ($the_media->width == 0 || $the_media->height == 0){
					$begin_tag = strpos($the_media->code, 'width="');
					if($begin_tag !== false){
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
							$vheight = 300; 
							$vwidth = 400;
						}	
					}
					else{
						$vheight = 300; 
						$vwidth = 400;
					}	
				}
				else{
					$replace_with = 'width="'.$the_media->width.'"';
					$the_media->code = preg_replace('#width="[0-9]+"#', $replace_with, $the_media->code);
					$replace_with = 'height="'.$the_media->height.'"';
					$the_media->code = preg_replace('#height="[0-9]+"#', $replace_with, $the_media->code);
					$vheight=$the_media->height; $vwidth=$the_media->width;						
				}
			}	
		}
		elseif($the_media->type == 'audio'){
			if ($the_media->source == 'url' || $the_media->source == 'local'){	
				if ($the_media->width == 0 || $the_media->height == 0){
					$aheight=20;
					$awidth=300;
				}
				else{
					$aheight=$the_media->height; 
					$awidth=$the_media->width;
				}
			}		
			elseif ($the_media->source=='code'){
				if ($the_media->width == 0 || $the_media->height == 0){
					$begin_tag = strpos($the_media->code, 'width="');
					if ($begin_tag !== false){
						$remaining_code = substr($the_media->code, $begin_tag+7, strlen($the_media->code));
						$end_tag = strpos($remaining_code, '"');
						$awidth = substr($remaining_code, 0, $end_tag);	
						$begin_tag = strpos($the_media->code, 'height="');
						if ($begin_tag !== false){
							$remaining_code = substr($the_media->code, $begin_tag+8, strlen($the_media->code));
							$end_tag = strpos($remaining_code, '"');
							$aheight = substr($remaining_code, 0, $end_tag);
							$no_plugin_for_code = 1;
						}
						else{
							$aheight = 20;
							$awidth = 300;
						}	
					}
					else{
						$aheight = 20;
						$awidth = 300;
					}							
				}
				else{					
					$replace_with = 'width="'.$the_media->width.'"';
					$the_media->code = preg_replace('#width="[0-9]+"#', $replace_with, $the_media->code);
					$replace_with = 'height="'.$the_media->height.'"';
					$the_media->code = preg_replace('#height="[0-9]+"#', $replace_with, $the_media->code);
					$aheight=$the_media->height; $awidth=$the_media->width;
				}
			}	
		}	
		if ($no_plugin_for_code == 0){
			$media = $guruHelper->create_media_using_plugin($the_media, $config, $aheight, $awidth, $vheight, $vwidth);
		}
		else{
			$media = $the_media->code;
		}
	}
	else{
		$media = '';
	}
}
elseif($config->display_media == 0){
	$media = '';
} 	

$public_data = $program->startpublish;
$int_date 	 = strtotime($public_data);
$data 		 = date($config->datetype,$int_date);

$path = JPATH_SITE.DS."components".DS."com_guru".DS."views".DS."guruprograms".DS."tmpl".DS."tabs.php";
require_once($path);

$course_config = json_decode($config->psgpage);
$course_style = json_decode($config->st_psgpage);

$wrap = $course_config->course_wrap_image; //0-yes, 1-no
$img_align = $course_config->course_image_alignment; //0-left, 1-right
$type = $course_config->course_image_size_type == "0" ? "w" : "h";

if(trim($program->image) != ""){
    $array = explode("/", $program->image);
    if(isset($array) && count($array) > 0){
        $program->imageN = $array[count($array)-1];
    }
}

if(trim($program->image) == "" || $program->image == NULL ){
	$program->image = "components/com_guru/images/thumbs/no_image.gif";
	$guruHelper->createThumb($program->image, "components/com_guru/images", $course_config->course_image_size, $type);
}
else{
	$guruHelper->createThumb($program->imageN, $config->imagesin."/courses", $course_config->course_image_size, $type);
	$program->image = str_replace("thumbs/", "", $program->image);
}

$img_size = getimagesize(JPATH_SITE.DS.$program->image);
$height = "auto";
if(isset($img_size) && isset($img_size["1"])){
	$height = $img_size["1"];
}
$db = JFactory::getDBO();
$sql = "SELECT currency, currencypos, course_lesson_release  from #__guru_config where id =1";
$db->setQuery($sql);
$db->query();
$res = $db->loadAssoc();
/*print_r($program->id);
die();*/
$amount_students = guruModelguruProgram::getStudentAmount($program->id);
$class_cover ='guru-cover-details';
?>

<div id="coursedetail" class="clearfix com-cont-wrap g_sect">
	<div class="g_row">
		<div class="g_cell span12 clearfix">
			<div>
				<div>
					<?php
                        $style = "";
                        $show_course_image = isset($course_config->show_course_image) ? $course_config->show_course_image : "0";
                        if(trim($program->image != "") && $show_course_image == 0){
                            $style = 'style="background-image:url(\''.JURI::root().$program->image.'\');"';
                        }
						else{
							if($course_config->show_course_studentamount == "1" && $course_config->course_author_name_show == "1" && $course_config->course_released_date == "1" && $course_config->course_level == "1" && $course_config->course_price == "1" && $res["course_lesson_release"] == '1' && $certificate_term == 0){
								$style = 'style="height:50px!important;"';
								$class_cover ='';
							}
							else{
								$style = 'style="height:180px!important;"';
								$class_cover ='guru-cover-details';
							}
						}
                    ?>
					
					<div class="guru-cover-image span12" <?php echo $style; ?> >
						<div class="<?php echo $course_style->course_name; ?> page_title">
							<h2><?php echo $program->name; ?></h2>
						</div>
                            <div class="coursedetail_row_guru guru-content clearfix">
                                <?php
                                $details = '<div class="'.$class_cover.' '.$class_wrap.'">';
                                if($course_config->show_course_studentamount == "0"){
                                    $details .= '<b>'.JText::_("GURU_AMOUNT_STUDENTS").': </b>'.$amount_students.'<br />';
                                }
                                if($course_config->course_author_name_show == "0"){
                                    if(isset($author->name)){
                                        $details .= '<b>'.JText::_("GURU_AUTHOR").': </b>'.$author->name.'<br />';
                                    }
                                }
                                        
                                if($course_config->course_released_date == "0"){
                                    $details .= '<b>'.JText::_("GURU_RELEASED").': </b>'.$data.'<br />';
                                }
                                
                                if($course_config->course_level == "0"){
                                    $details .= '<b>'.JText::_("GURU_LEVEL").': </b>'.$program->level."<br />";
                                }
                                if($course_config->course_price == "0"){
                                    $curent_currency = "GURU_CURRENCY_".$res["currency"];
                                    if($course_config->course_price_type == 0 ){
                                        $prices = guruModelguruProgram::getOnlyPricesR($program->id);
                                    }
                                    else{
                                        $prices = guruModelguruProgram::getOnlyPrices($program->id);
                                    }
                                    if(isset($chb_free_courses) && $chb_free_courses == 1){
                                            if($step_access_courses == 2){
                                                $text = JText::_("GURU_FREE_GUEST_PRICE");
                                            }
                                            elseif($step_access_courses == 1){
                                                $text = JText::_("GURU_FREE_FOR_MEMEBERS_PRICE");
                                            }
                                            elseif($step_access_courses == 0 && $selected_course == -1){
                                                $text = JText::_("GURU_FREE_FOR_STUDENTS_AC_PRICE");
                                            }
                                            elseif($step_access_courses == 0 && $selected_course > -1){
                                                $text = JText::_("GURU_FREE_FOR_STUDENTS_SC_PRICE")." ".$all_title;
                                            }
                                        $details .= '<b>'.JText::_("GURU_BUY_PRICE").': </b>'. $text.'<br/>';
                                
                                    }
                                    else{
                                        if(isset($prices)){
                                            if($res["currencypos"] == '0'){
                                                $details .= '<b>'.JText::_("GURU_BUY_PRICE").': </b>'.JText::_($curent_currency)." ".$prices."<br/>";
                                            }
                                            else{
                                                $details .= '<b>'.JText::_("GURU_BUY_PRICE").': </b>'.$prices." ".JText::_($curent_currency)."<br/>";
                                            }
                                            
                                        }
                                        
                                    }
                            
                                }
                                
                                $res_progr =guruModelguruProgram::getLessonReleaseType($program->id);
                                    
                                if($res["course_lesson_release"] == '0'){
                                    if($res_progr["course_type"] == 1 && $res_progr["lesson_release"] == 0){
                                        $details .= '<br/><b>'.JText::_("GURU_RELEASED_DATE").': </b>'.JText::_("GURU_ALL_AT_ONCE");
                                    }
                                    elseif($res_progr["course_type"] == 1 && $res_progr["lesson_release"] == 1){
                                        $details .= '<br/><b>'.JText::_("GURU_RELEASED_DATE").': </b>'.JText::_("GURU_ONE_PER_DAY");
                                    }
                                    elseif($res_progr["course_type"] == 1 && $res_progr["lesson_release"] == 2){
                                        $details .= '<br/><b>'.JText::_("GURU_RELEASED_DATE").': </b>'.JText::_("GURU_ONE_PER_W");
                                    }
                                    elseif($res_progr["course_type"] == 1 && $res_progr["lesson_release"] == 3){
                                        $details .= '<br/><b>'.JText::_("GURU_RELEASED_DATE").': </b>'.JText::_("GURU_ONE_PER_M");
                                    }
                                }
                                if(isset($certificate_term) && $certificate_term != 0){
                                    if($certificate_term == 1){
                                        $details .= '<br/><b>'.JText::_("GURU_CERTIFICATE_COL").': </b>'.JText::_("GURU_NO_CERT_GIVEN");
                                    }
                                    elseif($certificate_term == 2){
                                        $details .= '<br/><b>'.JText::_("GURU_CERTIFICATE_COL").': </b>'.JText::_("GURU_MUST_COLMP_ALL_LESS");
                                    }
                                    elseif($certificate_term == 3){
                                        $details .= '<br/><b>'.JText::_("GURU_CERTIFICATE_COL").': </b>'.JText::_("GURU_MUST_PASS_FE")." ".$result_maxs."%";
                                    }
                                    elseif($certificate_term == 4){
                                        $details .= '<br/><b>'.JText::_("GURU_CERTIFICATE_COL").': </b>'.JText::_("GURU_MUST_PASS_QAVG")." ".$avg_cert."%";
                                    }
                                    elseif($certificate_term == 5){
                                        $details .= '<br/><b>'.JText::_("GURU_CERTIFICATE_COL").': </b>'.JText::_("GURU_CERT_TERM_FALFE");
                                    }
                                    elseif($certificate_term == 6){
                                        $details .= '<br/><b>'.JText::_("GURU_CERTIFICATE_COL").': </b>'.JText::_("GURU_CERT_TERM_FALPQAVG")." ".$avg_cert."%";
                                    }
                                }
                                $details .= '</div>';
                                    
                                echo '<div  class="coursedetail_cell_guru g_cell span12 teacher_info">'.
                                        $details.'
                                      </div>';
                                 ?>	
                            </div>
					</div>
					<?php 
                    if (isset($_SESSION["joomlamessage"])) {
                    ?>
                        <div class="joomlamessage" id="joomlamessage">
                            <?php echo $_SESSION["joomlamessage"]; ?>
                        </div>
                    <?php 
                    }
                    unset($_SESSION["joomlamessage"]);
                    ?>
					
					<div class="guru-content clearfix">
                        <div class="coursedetail_cell_guru g_cell span12">
                            <div>
                                <div>
                                    <div>
                                         <form name="adminForm" id="adminForm" >
                                            <?php
                                            ob_start();	///the result given by components/com_guru/views/guruprograms/tmpl/tabs.php
                                            createTabs($program, $author, $programContent, $exercise, $requirements, $courses, $config, $course_config);
                                            $tabsContent = ob_get_contents();
                                            ob_end_clean();
                                            echo $tabsContent;
                                            
                                            $course_id = JRequest::getVar("cid", "0");
                                            ?>
                                            <input type="hidden" name="course_id" value="<?php echo intval($course_id); ?>" />
                                            <input type="hidden" name="option" value="com_guru" />
                                            <input type="hidden" name="controller" value="guruPrograms" />
                                            <input type="hidden" name="task" value="" />
                                        </form>
                                     </div>
                                </div>
                           </div>
                        </div>            
                    </div>
					
				</div>
			</div>
		</div>
	</div>
</div>