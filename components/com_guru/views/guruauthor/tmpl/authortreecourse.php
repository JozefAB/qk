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
jimport( 'joomla.html.pagination' );
JHTML::_('behavior.tooltip');
JHTML::_('behavior.framework');
JHtml::_('behavior.calendar');


$k		= 0;
$ads 	= $this->ads;
$n 		= count ($ads);
$filter = $this->filters;
$pid = JRequest::getVar("pid", "0");
$config = $this->getConfigs();
$dimensions_backend = $config["0"]["lesson_window_size_back"];
$width = "715";
$height = "1150";
if(trim($dimensions_backend) != ""){
	$temp = explode("x", $dimensions_backend);
	$height = $temp["0"];
	$width = $temp["1"];
}

$document = JFactory::getDocument();
$document->setTitle(trim(JText::_('GURU_AUTHOR'))." ".trim(JText::_('GURU_AUTHOR_MY_COURSE'))." ".trim(JText::_('GURU_TREE')) );
include_once(JPATH_SITE.DS.'components'.DS.'com_guru'.DS.'views'.DS.'guruauthor'.DS.'tmpl'.DS.'course_modal.php');
$course_modal= guruAdminCourseModal();

$document->addScript('components/com_guru/js/guru_modal.js');

?>

<div id="zuzuzuzu"></div>
<div id="cucu" style="display:none"></div>

<script type="text/javascript">
	function dom_refresh() { 
			window.parent.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });
			window.parent.addEvent('domready', function() {
	
				SqueezeBox.initialize({});
	
				$$('a.modal').each(function(el) {
					el.addEvent('click', function(e) {
						new Event(e).stop();
						SqueezeBox.fromElement(el);
					});
				});
			});
	}
	
	function showContent(href){
		jQuery( '#myModal .modal-body iframe').attr('src', href);
	}
	
	
	function changeAction(value){
		if(value != "descending" && value != "ascending"){
			cids = document.getElementsByName("cid[]");
			selected = false;
			for(i=0; i<cids.length; i++){
				if(cids[i].checked){
					selected = true;
					break;
				}
			}
			
			if(!selected){
				alert("<?php echo JText::_("GURU_NO_LESSON_SELECTED"); ?>");
				return false;
			}
		}
		
		if(value != 0){
			msg = "";
			if(value == "delete"){
				msg = '<?php echo JText::_("GURU_SURE_DELETE_LESSONS"); ?>';
			}
			else if(value == "publish"){
				msg = '<?php echo JText::_("GURU_SURE_PUBLISH_LESSONS"); ?>';
			}
			else if(value == "unpublish"){
				msg = '<?php echo JText::_("GURU_SURE_UNPUBLISH_LESSONS"); ?>';
			}
			else if(value == "students"){
				msg = '<?php echo JText::_("GURU_SURE_STUDENTS_LESSONS"); ?>';
			}
			else if(value == "members"){
				msg = '<?php echo JText::_("GURU_SURE_MEMBERS_LESSONS"); ?>';
			}
			else if(value == "guests"){
				msg = '<?php echo JText::_("GURU_SURE_GUESTS_LESSONS"); ?>';
			}
			else if(value == "descending"){
				msg = '<?php echo JText::_("GURU_SURE_DESCENDING_LESSONS"); ?>';
			}
			else if(value == "ascending"){
				msg = '<?php echo JText::_("GURU_SURE_ASCENDING_LESSONS"); ?>';
			}
			
			if(confirm(msg)){
				document.getElementById("task").value = "action";
				document.adminForm.submit();
			}
			else{
				document.getElementById("task").value = "treeCourse";
			}
		}
	}
</script>
<style>
	.guru-content .btn-default, 
	.guru-content .btn {
		line-height:10px!important;
	}
</style>
<form action="index.php?option=com_guru&controller=guruAuthor&pid=<?php echo $pid; ?>" id="adminForm" name="adminForm" method="post">

<div class="g_row">
	<div class="g_cell span12">
		<div>
			<div>
                <div id="g_course_tree" class="clearfix com-cont-wrap">  
                	 
					<?php 
					$div_menu = $this->authorGuruMenuBar();
                	echo $div_menu;
					JHtmlBehavior::framework(); ?>
                    <div id="myModalVideo" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalVideoLabel" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="javascript:closeModal();">x</button>
                         </div>
                         <div class="modal-body">
                        </div>
                    </div>
                    <?php echo $course_modal; ?>
                    
<?php
	if(isset($ads[0]->id)){
		$ProgramName = $this->getProgramName($ads[0]->id);
	}
	else{
		$progr = $this->getProgram($pid);
		$ProgramName = $progr['name'];
	}
?>

                    <div class="row-fluid">
                    	<div class="span12">
                            <span style="margin-top: -10px; color: black; font-family:Arial,Helvetica,sans-serif; font-size:25px;">
                                <b><?php echo $ProgramName; ?></b>
                            </span>
                            <div class="clearfix"></div>
                            <input type="button" class="btn btn-success tree-button" onclick="history.back();" value="<?php echo JText::_("GURU_BACK_ARROW"); ?>" />
                            <input type="button" class="btn btn-warning tree-button" onclick="javascript:openMyModal(900, 425, '<?php echo JURI::root();?>index.php?option=com_guru&controller=guruAuthor&component=com_guru&task=newmodule&tmpl=component&pid=<?php echo $pid; ?>'); return false;" href="#" value="<?php echo JText::_("GURU_ADD_NEW_MODULE"); ?>" />
                            
                            <div class="action-group">
								<?php echo JText::_("GURU_ACTION_TO_MORE_LESSONS"); ?>
                                <br/>
                                <select name="action" onchange="javasctip:changeAction(this.value);">
                                    <option value="0"><?php echo JText::_("GURU_SELECT_OPTION"); ?></option>
                                    <optgroup label="<?php echo JText::_("GURU_ACTIONS"); ?>">
                                        <option value="delete"><?php echo JText::_("GURU_DELETE_OPTION"); ?></option>
                                        <option value="publish"><?php echo JText::_("GURU_PUBLISH_OPTION"); ?></option>
                                        <option value="unpublish"><?php echo JText::_("GURU_UNPUBLISH_OPTION"); ?></option>
                                    </optgroup>
                                    <optgroup label="<?php echo JText::_("GURU_ACCESS"); ?>">
                                        <option value="students"><?php echo JText::_("GURU_STUDENTS_ACCESS_OPTION"); ?></option>
                                        <option value="members"><?php echo JText::_("GURU_MEMBERS_ACCESS_OPTION"); ?></option>
                                        <option value="guests"><?php echo JText::_("GURU_GUESTS_ACCESS_OPTION"); ?></option>
                                    </optgroup>
                                    <optgroup label="<?php echo JText::_("GURU_ORDERING"); ?>">
                                        <option value="descending"><?php echo JText::_("GURU_DESCENDING_OPTION"); ?></option>
                                        <option value="ascending"><?php echo JText::_("GURU_ASCENDING_OPTION"); ?></option>
                                    </optgroup>
                                </select>
                            </div>
						</div>
					</div>
                    
                    <br/>
                    
<?php
$document->addScript(JURI::base()."components/com_guru/views/guruauthor/tmpl/js/ajax.js");
$document->addScript(JURI::base()."components/com_guru/views/guruauthor/tmpl/js/context-menu.js");
$document->addScript(JURI::base()."components/com_guru/views/guruauthor/tmpl/js/drag-drop-folder-tree.js");
$document->addStyleSheet(JURI::base()."components/com_guru/views/guruauthor/tmpl/css/drag-drop-folder-tree.css");
$document->addStyleSheet(JURI::base()."components/com_guru/views/guruauthor/tmpl/css/context-menu.css");
 ?>	
	<style type="text/css">
	/* CSS for the demo */
	img{
		border:0px;
		
	}
	.modal-color-myModal1 .modal-backdrop {
      background-color: #f00!important;
	}
	</style>
	<script type="text/javascript">
	//--------------------------------
	// Save functions
	//--------------------------------
		
	jQuery('.modal[data-color]').on('show hidden', function(e) {
	  jQuery('body')
		.toggleClass('modal-color-' + jQuery(this).data('color'));
	});
	var ajaxObjects = new Array();

	function refreshTree()
	{
		var save_string = treeObj.getNodeOrders();
		treeObj.initTree(2, 1);
	}	

	function saveTheNewTree()
	{
			// hack LMS - using AJAX to save the new order
			var ajaxObjects = new Array();
			
			saveString = JSTreeObj.getNodeOrders();
			var ajaxIndex = ajaxObjects.length;
			ajaxObjects[ajaxIndex] = new sack();
			
			var url = '<?php echo JURI::root();?>components/com_guru/views/guruauthor/tmpl/saveOrder.php?saveString=' + saveString;
			ajaxObjects[ajaxIndex].requestFile = url;	// Specifying which file to get
			
			ajaxObjects[ajaxIndex].onCompletion = function() 
					{ 
						
					} ;	// Specify function that will be executed after file has been found
			ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function				
	}
	
	function addNewScreen(day_id, screen_id)
	{
			var ajaxObjects = new Array();
			
			var ajaxIndex = ajaxObjects.length;
			ajaxObjects[ajaxIndex] = new sack();
			
			var url = '<?php echo JURI::root();?>components/com_guru/views/guruauthor/tmpl/addScreen.php?day_id=' + day_id + '&screen_id=' + screen_id;
			ajaxObjects[ajaxIndex].requestFile = url;	// Specifying which file to get
			
			ajaxObjects[ajaxIndex].onCompletion = function() 
					{ 
					} ;	
			ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function				
	}	

	function saveMyTree(day_title, day_ordering, newdayid)
	{
		
			saveString = treeObj.getNodeOrders();
			var ajaxIndex = ajaxObjects.length;
			ajaxObjects[ajaxIndex] = new sack();
			
			var url = '<?php echo JURI::base()."components/com_guru/views/guruauthor/tmpl/saveNodes.php?saveString=";?>' + saveString;
			ajaxObjects[ajaxIndex].requestFile = url;	// Specifying which file to get
			
			//ajaxObjects[ajaxIndex].onCompletion = function() { saveComplete(ajaxIndex); } ;	// Specify function that will be executed after file has been found
			ajaxObjects[ajaxIndex].onCompletion = function() 
					{ 
						treeObj._____addElement(day_title, day_ordering, newdayid) ; 
						saveTheNewTree();
						refreshTree();
						dom_refresh();
				
						/*treeObj.initTree(); */
					} ;	// Specify function that will be executed after file has been found
			ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function			
	}
	

	function GetNodesOrder()
	{
		var save_string = treeObj.getNodeOrders();
		//alert(save_string);
	}		
	
	function deleteFinalExam(course_id){
		var ajaxObjects = new Array();
		var ajaxIndex = ajaxObjects.length;
		
		ajaxObjects[ajaxIndex] = new sack();
		if(confirm ('<?php echo JText::_('GURU_SURE_DELETE_FINAL_EXAM'); ?>')){
			var url = '<?php echo JURI::root();?>components/com_guru/views/guruauthor/tmpl/deleteFinalQuiz.php?course_id='+ course_id;
			ajaxObjects[ajaxIndex].requestFile = url;	// Specifying which file to get
			
			//ajaxObjects[ajaxIndex].onCompletion = function() { saveComplete(ajaxIndex); } ;	// Specify function that will be executed after file has been found
			ajaxObjects[ajaxIndex].onCompletion = function() 
					{ 
						document.getElementById("final-exam").innerHTML = "";
						
					} ;	// Specify function that will be executed after file has been found
			ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function		
		}
		else{
		}
	}
	
	function deleteScreen(group_id, screen_id, node_id)
	{
			// hack LMS - using AJAX to save the new order
			var ajaxObjects = new Array();
			
			//saveString = JSTreeObj.getNodeOrders();
			var ajaxIndex = ajaxObjects.length;
			ajaxObjects[ajaxIndex] = new sack();
			if(confirm ('<?php echo JText::_('GURU_SURE_DELETE_LESSON'); ?>')){
				var url = '<?php echo JURI::root();?>components/com_guru/views/guruauthor/tmpl/deleteScreen.php?group='+ group_id +'&screen=' + screen_id;
				ajaxObjects[ajaxIndex].requestFile = url;	// Specifying which file to get
				
				//ajaxObjects[ajaxIndex].onCompletion = function() { saveComplete(ajaxIndex); } ;	// Specify function that will be executed after file has been found
				ajaxObjects[ajaxIndex].onCompletion = function() 
						{ 
							//treeObj._____addElement(day_title, day_ordering, newdayid) ; 
							//treeObj.initTree(); 
							node_screen = document.getElementById('node' + node_id);
							treeObj.__deleteOfScreen(node_screen) ; 	
							
							
						} ;	// Specify function that will be executed after file has been found
				ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function		
			}
			else{
			}		
	
	}	
	
	function deleteGroup(group_id, node_id)
	{
			// hack LMS - using AJAX to save the new order
			var ajaxObjects = new Array();
			
			//saveString = JSTreeObj.getNodeOrders();
			var ajaxIndex = ajaxObjects.length;
			ajaxObjects[ajaxIndex] = new sack();
			if(confirm ('<?php echo JText::_('GURU_SURE_DELETE_GROUP'); ?>')){
				var url = '<?php echo JURI::root();?>components/com_guru/views/guruauthor/tmpl/deleteGroup.php?group='+ group_id ;
				ajaxObjects[ajaxIndex].requestFile = url;	// Specifying which file to get
				
				//ajaxObjects[ajaxIndex].onCompletion = function() { saveComplete(ajaxIndex); } ;	// Specify function that will be executed after file has been found
				ajaxObjects[ajaxIndex].onCompletion = function() 
						{ 
							//treeObj._____addElement(day_title, day_ordering, newdayid) ; 
							//treeObj.initTree(); 
							node_screen = document.getElementById('node' + node_id);
							treeObj.__deleteOfScreen(node_screen) ; 
							document.getElementById('guru_message_module').style.display = "block";
						} ;	// Specify function that will be executed after file has been found
				ajaxObjects[ajaxIndex].runAJAX();		// Execute AJAX function	
		   }
		   else{
		   }
	}		

	// Call this function if you want to save it by a form.
	function saveMyTree_byForm()
	{
		document.myForm.elements['saveString'].value = treeObj.getNodeOrders();
		document.myForm.submit();		
	}
	function guruCheckBoxex(id_module, lesson_poz){
		for(i=1; i<(lesson_poz+1); i++){
			if(!document.getElementById('check_all_lessons'+id_module).checked){
				document.getElementById("lesson_"+id_module+"_"+i).checked = false;
			}
			else{
				document.getElementById("lesson_"+id_module+"_"+i).checked = true;
			}
		}
		
	}
	</script>
				<?php    
                $style="none";  
                if(isset($_SESSION["saved_new"]) && $_SESSION["saved_new"] == 1){
                    $style="block";
                    unset($_SESSION["saved_new"]);
                } 
                
                ?>

                <div style="display:none;" id="guru_message_module">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <div class="alert alert-success">
                        <h4 class="alert-heading">Message</h4>
                        <p><?php echo JText::_("GURU_DAY_REMSUCC"); ?></p>
                    </div>
                </div>
                <div style="display:<?php echo $style;?>;" id="guru_message_savemodule">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <div class="alert alert-success">
                        <h4 class="alert-heading">Message</h4>
                        <p><?php echo JText::_("GURU_DAY_SAVE"); ?></p>
                    </div>
                </div>
                
                
                    <div class="tree tree-selectable" id="tree1">
                        <ul id="dhtmlgoodies_tree2" class="tree-folder-header dhtmlgoodies_tree">
                            <li style="list-style-type:none;" id="node0" noDrag="true" noSiblings="true" noDelete="true" noRename="true">
                                <ul>
                                    <?php 
                                    $rank = 1;
                    
                                    for ($i = 0; $i < $n; $i++) {
                                        $ad =$ads[$i];
                                        $id = $ad->id;					
                                        $link = "index.php?option=com_guru&controller=guruAuthor&tmpl=component&task=edit&node=".$rank."&cid=".$id;
                                        $day_title = $ad->title;
                                        $idTasksForDay = $this->getIDTasksForDay($id);
                                        ?>
                    
                                    <li  style="background:url(components/com_guru/images/join_dot.png) repeat-y" isLeaf="false" LeafId="<?php echo $id; ?>" id="node<?php echo $rank; ?>">
                                        <img border="0" src="<?php echo JURI::base()."components/com_guru/images/module-tn.gif"; ?>" alt="alt" />
                                       <input type="checkbox" name="check_all_lessons" id="check_all_lessons<?php echo $id ;?>" onclick="guruCheckBoxex(<?php echo $id; ?>,<?php echo intval(count($idTasksForDay));?>);" value="<?php echo intval($aTask->id); ?>" />
                                        <a onclick="javascript:openMyModal(900, 425, '<?php echo JURI::root();?><?php echo $link;?>'); return false;" href="#">
											<?php echo $day_title; ?>
                                		</a>&nbsp;
                                        <img border="0" onClick="javascript:deleteGroup(<?php echo $id.','.$rank; ?>)" src="<?php echo JURI::base()."components/com_guru/images/delete.gif"; ?>" />&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?php
                                            if($config["0"]["back_size_type"] == "1"){
                                                $link_addlesson = JURI::root().'index.php?option=com_guru&controller=guruAuthor&task=editsbox&tmpl=component&day='.$id.'&progrid='.$pid.'&cid=0';
                                        ?>
                                        		<a onclick="javascript:openMyModal((window.screen.availWidth - 120), (window.screen.availHeight - 180), '<?php echo $link_addlesson;?>'); return false;" href="#">
													<font color="#0B55C4"><?php echo JText::_('GURU_DAY_ADD_SCREEN2'); ?></font>
                                				</a>
                                        <?php
                                            }
                                            else{
                                                $link_addlesson = JURI::root().'index.php?option=com_guru&controller=guruAuthor&task=editsbox&tmpl=component&day='.$id.'&progrid='.$pid.'&cid=';
                                        ?>
                                        		<a onclick="javascript:openMyModal((window.screen.availWidth - 120), (window.screen.availHeight - 180), '<?php echo $link_addlesson;?>'); return false;" href="#">
													<font color="#0B55C4"><?php echo JText::_('GURU_DAY_ADD_SCREEN2'); ?></font>
                                				</a>
                                        <?php
                                            }
                                        ?>
                            <?php	
                                
                                    $nr=0;
                    
                                    if(count($idTasksForDay)) {
                                    ?>
                                    <ul>
                                    <?php
										$new_order = 1;
										$lesson_poz = 1;
                                        foreach($idTasksForDay as $aIDTask) {
                                            if($aIDTask>0){
                                                $rank++;
                                                $nr++;
                                                $aTask = $this->getTask($aIDTask);
                                                $layout_nr=$this->select_layout($aIDTask);
                                                
                                                $task_link = "index.php?option=com_guru&controller=guruAuthor&tmpl=component&task=editsbox&cid=".$aTask->id;
                                                $TaskType = $this->getTaskType($aTask->id);
                                                
                                                
                                                $image_assoc = '';
                                            
                                                if(isset($TaskType) && $TaskType == 'audio') $image_assoc = 'audio.gif';
                                                if(isset($TaskType) && $TaskType == 'video') $image_assoc = 'video.gif';
                                                if(isset($TaskType) && $TaskType == 'docs') $image_assoc = 'doc.gif';
                                                if(isset($TaskType) && $TaskType == 'url') $image_assoc = 'url.gif';
                                                if(isset($TaskType) && $TaskType == 'quiz') $image_assoc = 'quiz.gif';
                                                if(isset($TaskType) && $TaskType == 'text') $image_assoc = 'text.gif';	
                                                
                                                if($aTask->final_lesson != 0){
                                                    $new_rank=$rank+10;
                                                }
                                                else{
                                                    $new_rank=$rank;
                                                }
                                                ?>
                                                <li isLeaf="true" LeafId="<?php echo $aTask->id; ?>" id="node<?php echo $new_rank; ?>">
                                                <?php if($nr == count($idTasksForDay)){?>
                                                        <img src="components/com_guru/images/joinbottom.png" alt="" border="0" />
                                                <?php }
                                                      else{?>
                                                        <img alt="" src="components/com_guru/images/join.png" border="0" />
                                                <?php }
                                                        if(isset($layout_nr) && ($layout_nr<13) && ($layout_nr>0)){
                                                        echo '<img border="0" src="'.JURI::base().'components/com_guru/images/screen-'.$layout_nr.'-tn.gif" alt="layout" />';
                                                    } 
                                                    
                    
                                                    if($image_assoc!=''){
                                                ?>
                                                        <img border="0" src="<?php echo JURI::base()."components/com_guru/images/".$image_assoc; ?>" />
                                                <?php 
                                                    }
												?>
                                                <input type="checkbox" name="cid[]" id="lesson_<?php echo $id."_".$lesson_poz; ?>" value="<?php echo intval($aTask->id); ?>" />
                                                <?php
													$opacity = "1";
													if($aTask->published == 0){
														$opacity = "0.5";
													}
												
                                                    if($config["0"]["back_size_type"] == "1"){ 
                                                ?>
                                                        <script type="text/javascript">
                                                            width = window.screen.availWidth - 120;
															height = window.screen.availHeight - 180;
															document.write('<a onclick="javascript:openMyModal('+width+', '+height+', \'<?php echo JURI::root();?><?php echo $task_link; ?>&progrid=<?php echo intval($_REQUEST['pid']);?>&module=<?php echo $id; ?>\'); return false;" href="#" id="nodeATag<?php echo $new_rank; ?>" style="opacity:<?php echo $opacity; ?>;"><?php echo addslashes($aTask->name); ?></a>');
                                                        </script>
                    
                                                <?php
                                                    }
                                                    else
                                                    {
                                                ?>
                                                        <script type="text/javascript">
															width = window.screen.availWidth - 120;
															height = window.screen.availHeight - 180;
                                                            document.write('<a onclick="javascript:openMyModal('+width+', '+height+', \'<?php echo JURI::root();?><?php echo $task_link; ?>&progrid=<?php echo intval($_REQUEST['pid']);?>&module=<?php echo $id; ?>\'); return false;" href="#" id="nodeATag<?php echo $new_rank; ?>" style="opacity:<?php echo $opacity; ?>;"><?php echo addslashes($aTask->name); ?></a>');								
                                                        </script>
                    
                                                <?php	
                                                    }
													$lesson_poz ++;
                                                ?>	
                                                        &nbsp;<img border="0" onClick="javascript:deleteScreen(<?php echo $id.','.intval($aTask->id).','.$new_rank; ?>)" src="<?php echo JURI::base()."components/com_guru/images/delete.gif"; ?>" /></li>
                                                <?php
                                            } 
                                        }
                                        ?>						
                                        </ul>
                                    
                                <?php }
                    
                                    $rank++;
                                    }
                                    ?>
                                </ul>
                            </li>
                        </ul>	
                    </div>
                </div> 
			</div> 
		</div> 
	</div> 
</div> 
	<script type="text/javascript">	
		treeObj = new JSDragDropTree();
		treeObj.setTreeId('dhtmlgoodies_tree2');
		treeObj.setMaximumDepth(6);
		treeObj.setMessageMaximumDepthReached('<?php echo JText::_('GURU_DAY_ILLEGAL_JS');?>'); // If you want to show a message when maximum depth is reached, i.e. on drop.
		treeObj.initTree(1, 0);
	</script>

<script language="javascript">
	var first = false;
	function showContentVideo(href){
		first = true;
		jQuery.ajax({
		  url: href,
		  success: function(response){
		   jQuery( '#myModalVideo .modal-body').html(response);
		  }
		});
	}

	jQuery('#myModalVideo').on('hide', function () {
	 jQuery('#myModalVideo .modal-body').html('');
	});
	
	jQuery('#myModal').on('hide', function () {
		jQuery('#myModal .modal-body iframe').attr('src', '');
	});
	
	function closeModal(){
		jQuery('#myModalVideo .modal-body iframe').attr('src', '');
	}
	
	jQuery('body').click(function () {
		if(!first){
			jQuery('#myModalVideo .modal-body iframe').attr('src', '');
		}
		else{
			first = false;
		}
	});

</script>

	<input type="hidden" name="option" value="com_guru" />
    <input type="hidden" name="task" id="task" value="treeCourse" />
    <input type="hidden" name="boxchecked" id="boxchecked" value="0" />
    <input type="hidden" name="boxchecked_id" id="boxchecked_id" value="" />
    <input type="hidden" name="controller" value="guruAuthor" />	
</form>