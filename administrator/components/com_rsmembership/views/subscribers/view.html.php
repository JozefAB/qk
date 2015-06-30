<?php
/**
* @package RSMembership!
* @copyright (C) 2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSMembershipViewSubscribers extends JViewLegacy
{
	public function display($tpl = null)
	{
		require_once JPATH_COMPONENT.'/helpers/rsmembership.php';
		
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state	 	= $this->get('State');
		$this->customFields = RSMembership::getCustomFields(array('showinsubscribers'=>1));
		$this->totalItems = $this->get('TotalItems');

		$this->addToolbar();

		$this->filterbar = $this->get('FilterBar');
		$this->sidebar 	 = $this->get('SideBar');
		
		$document = JFactory::getDocument();	
		$document->addScript('components/com_rsmembership/assets/js/export.js');
		
		$document->addScriptDeclaration("function rsmem_get_lang(id) {
			switch (id)
			{
				default: return id;
				case 'COM_RSMEMBERSHIP_EXPORT_NO_DATA': return '".JText::_('COM_RSMEMBERSHIP_EXPORT_NO_DATA', true)."'; break;	
			}
		}");
		
		parent::display($tpl);
	}

	protected function addToolbar() 
	{		
		JToolBarHelper::title(JText::_('COM_RSMEMBERSHIP_SUBSCRIBERS'),'subscribers');

		// add Menu in sidebar
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		RSMembershipToolbarHelper::addToolbar('subscribers');

		// Check Joomla! version
		$jversion 	= new JVersion();
		$is30 		= $jversion->isCompatible('3.0');
		$export		= $is30 ? 'download' : 'export';
		
		JToolBarHelper::editList('subscriber.edit');
		
		JToolBarHelper::spacer();
		JToolBarHelper::custom('subscribers.exportcsv', $export.'.png', $export.'_f2.png', 'COM_RSMEMBERSHIP_EXPORT', false);
	}
}