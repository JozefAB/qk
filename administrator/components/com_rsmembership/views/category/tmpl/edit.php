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
<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=category.edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
<?php
$this->fields 	= $this->form->getFieldset('main');

$this->field->startFieldset(JText::_($this->fieldsets['main']->label), 'adminform form');

foreach ($this->fields as $field) {
	if (strtolower($field->type) == 'editor') echo '<div class="clr"></div>';
	$this->field->showField( $field->hidden ? '' : $field->label, $field->input);
}
$this->field->endFieldset();
?>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="task" value="" />
</form>

<?php JHTML::_('behavior.keepalive'); //keep session alive while editing ?>