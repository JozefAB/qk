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
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('bootstrap.framework');
jimport('joomla.html.html.grid');
	$k = 0;
	$n = count ($this->categs);
	$categs = $this->categs;
	
	$user = JFactory::getUser();
	$listDirn = "asc";
	$listOrder = "ordering";
	$saveOrderingUrl = 'index.php?option=com_guru&controller=guruPcategs&task=saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
?>
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
		submitform ( pressbutton );
}
</script>
<form action="index.php" id="adminForm" name="adminForm" method="post">
<table class="table table-striped table-bordered adminlist" id="articleList">
<thead>
	<tr>
		<th>
			<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
		</th>
        <th width="5"><input type="checkbox" onclick="Joomla.checkAll(this)" name="toggle" value="" /><span class="lbl"></span></th>
	    <th><?php echo JText::_('GURU_ID');?></th>
		<th><?php echo JText::_('GURU_CATEGORY');?></th>
		<th><?php echo JText::_('GURU_CSCAT_SUBCATEGORIES');?></th>
		<th><?php echo JText::_('GURU_CSCAT_COURSES');?></th>
		<th><?php echo JText::_('GURU_PUBLISHED');?></th>
	</tr>
</thead>
<tbody>
<?php  $unu = $this->categlist();	?>
</tbody>
<tfoot>
	<tr>
        <td colspan="10">
            <div class="pagination pagination-toolbar">
                <?php echo $this->pagination->getListFooter(); ?>
            </div>
            <div class="btn-group pull-left">
                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                <?php echo $this->pagination->getLimitBox(); ?>
           </div>
        </td>
    </tr>
</tfoot>
</table>
</div>
<input type="hidden" name="option" value="com_guru" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="guruPcategs" />
<input type="hidden" name="old_limit" value="<?php echo JRequest::getVar("limitstart"); ?>" />
</form>
