<?php
/**
* @package RSMembership!
* @copyright (C) 2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHTML::_('behavior.tooltip');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=file.edit&cid='.$this->item->path); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal" enctype="multipart/form-data">

<?php
$this->fields 	= $this->form->getFieldset('main');

$this->field->startFieldset(JText::_($this->fieldsets['main']->label), 'adminform form');

foreach ($this->fields as $field) {
	if ($field->name == 'jform[term_id]' && !$this->is_file) 	
		continue;
	else {
		if (strtolower($field->type) == 'editor') echo '<div class="clr"></div>';
		
		$this->field->showField( $field->hidden ? '' : $field->label, $field->input);
	}
}
$this->field->endFieldset();
?>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="task" value="" />
<input type="hidden" name="folder" value="<?php echo dirname($this->item->path); ?>" />
<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>