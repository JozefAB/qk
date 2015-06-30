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
JHtml::_('behavior.framework');
	$k = 0;
	$n = count($this->plugins);	
	$lang = JFactory::getLanguage();
    $lang->load('plg_gurupayment_offline', JPATH_ADMINISTRATOR);
?>
<script language="javascript" type="text/javascript" >
	function checkPluginFile () {
		var file = document.getElementById("pluginfile");
		if (file.value.length < 1) {
			alert ('<?php echo JText::_("VIEWPLUGNOPLUGFORUPL");?>');
			return false;
		}
	}
	function publish(publ, id){
		document.adminForm.id.value = id;
		document.adminForm.task.value = publ;
		submitform();
	}
</script>
<div class="gurutab-content">
<div id="myModal" class="modal hide">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    </div>
    <div class="modal-body">
    </div>
</div>
<div class="container-fluid">
      <a data-toggle="modal" data-target="#myModal" onclick="showContentVideo('index.php?option=com_guru&controller=guruAbout&task=vimeo&id=29999524&tmpl=component')" class="pull-right guru_video" href="#">
                <img src="<?php echo JURI::base(); ?>components/com_guru/images/icon_video.gif" class="video_img" />
            <?php echo JText::_("GURU_PLUGINS_VIDEO"); ?>                  
      </a>
</div>	
<div class="clearfix"></div>
<div class="well well-minimized">
    <?php echo JText::_("GURU_PLUGINS_SETTINGS_DESCRIPTION"); ?>
</div>
<form action="index.php" name="adminForm" method="post">
	<div id="editcell">
		<table class="table table-striped table-bordered adminlist">
			<thead>
				<tr>
					<th width="5">
						<input type="checkbox" onclick="Joomla.checkAll(this);" name="toggle" value="" />
                        <span class="lbl"></span>
					</th>
					<th width="20">
						<?php echo JText::_('VIEWPLUGID');?>
					</th>
					<th>
						<?php echo JText::_('VIEWPLUGTITLE');?>
					</th>
					<th>
						<?php echo JText::_("Plugin Type");?>	
					</th>
					<th>
						<?php echo JText::_("VIEWPLUGPUBLISH");?>	
					</th>
			
				</tr>
			</thead>			
			<tbody>
			<?php 
                for($i = 0; $i < $n; $i++){
                    $plugin = $this->plugins[$i];	
					$plugin["name"] = JText::_($plugin["name"]);		
	
                    //echo'<pre>'; print_r($plugin); die();
                    if(empty($plugin)){
                        continue;
                    }	
                    $id = $plugin["extension_id"];
					$enabled = $plugin["enabled"];
                    $checked = JHTML::_('grid.id', $i, $id);
                    //$link = "index.php?option=com_plugins&view=plugin&layout=edit&extension_id=".$id;
					if($plugin["name"] == "Payment Processor [PayPal]"){
						$link = "index.php?option=com_plugins&view=plugins&filter_search=Payment Processor";
				   }
				   else{
				   		$link = "index.php?option=com_plugins&view=plugins&filter_search=".$plugin["element"];
				   }
					
					//$link = "http://localhost/guru_73/administrator/index.php?option=com_plugins&view=plugin&layout=edit&extension_id=10004";
                    //$published = JHTML::_('grid.published', $plugin, $i);
            ?>
                    <tr class="row<?php echo $k;?>"> 
                        <td>
							<?php echo $checked;?>
                            <span class="lbl"></span>
                        </td>		
                        <td><?php echo $i+1;?></td>		
                        <td><?php echo '<a class="a_guru" href="'.$link.'">'.$plugin["name"].'</a>'; ?></td>		
                        <td><?php echo $plugin["element"]; ?></td>
                        <td align="center">
                        	<?php //echo $published;
							if($enabled == 0){ ?>
								<a href="#" onclick="javascript:publish('publish', <?php echo $id ?>);"><img src="components/com_guru/images/publish_x.png" /></a>
							<?php }
							else{ ?>
								<a href="#" onclick="javascript:publish('unpublish', <?php echo $id ?>);"><img src="components/com_guru/images/tick.png" /></a>
							<?php }?>
                        </td>
                    </tr>
            <?php 
                    $k = 1 - $k;
                }
            ?>
			</tbody>
		</table>
	</div>
</div>
	<input type="hidden" name="option" value="com_guru" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="guruPlugins" />
    <input type="hidden" name="id" value="0" />
</form>
<script language="javascript">
	var first = false;
	function showContentVideo(href){
	first = true;
	jQuery.ajax({
      url: href,
      success: function(response){
       jQuery( '#myModal .modal-body').html(response);
      }
    });
}

	jQuery('#myModal').on('hide', function () {
	 jQuery('div.modal-body').html('');
	});
	jQuery('body').click(function () {
		if(!first){
			jQuery('#myModal .modal-body iframe').attr('src', '');
		}
		else{
			first = false;
		}
	});
</script>