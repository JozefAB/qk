<?php
/**
* @package RSMembership!
* @copyright (C) 2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSMembershipModelConfiguration extends JModelAdmin
{
	public function __construct() {
		parent::__construct();
	}

	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_rsmembership.configuration', 'configuration', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	protected function loadFormData() {
		$data = (array) RSMembershipHelper::getConfig();

		return $data;
	}
	
	public function getSideBar() {
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		
		return RSMembershipToolbarHelper::render();
	}
	
	public function getRSFieldset() {
		require_once JPATH_COMPONENT.'/helpers/adapters/fieldset.php';
		
		$fieldset = new RSFieldset();
		return $fieldset;
	}
	
	public function getRSTabs() {
		require_once JPATH_COMPONENT.'/helpers/adapters/tabs.php';
		
		$tabs = new RSTabs('com-rsmembership-configuration');
		return $tabs;
	}
	
	public function save($data) {
		$db 	= JFactory::getDBO();
		$query 	= $db->getQuery(true);
		$config = RSMembershipConfig::getInstance();
		
		// parse rules
		if (isset($data['rules'])) {
			$rules	= new JAccessRules($data['rules']);
			$asset	= JTable::getInstance('asset');
			
			if (!$asset->loadByName($this->option)) {
				$root	= JTable::getInstance('asset');
				$root->loadByName('root.1');
				$asset->name = $this->option;
				$asset->title = $this->option;
				$asset->setLocation($root->id, 'last-child');
			}
			$asset->rules = (string) $rules;
			
			if (!$asset->check() || !$asset->store()) {
				$this->setError($asset->getError());
				return false;
			}
		}
		
		if (!isset($data['captcha_enabled_for']) && isset($data['captcha_enabled']) && $data['captcha_enabled'] > 0) 
			$data['captcha_enabled_for'] = array();

		foreach ($data as $prop => $val) 
			$config->set($prop, $val);

		RSMembershipHelper::readConfig(true);

		return true;
	}

	public function idevCheckConnection()
	{
		$db 	= JFactory::getDBO();
		$query	= $db->getQuery(true);

		$idev_url = JFactory::getApplication()->input->get('idev_url', '', 'string');
		if (!empty($idev_url))
		{
			if (strlen($idev_url) > 5)
			{
				$idev_url = rtrim($idev_url, '/');
				$idev_url.= '/';
			}

			$config = RSMembershipConfig::getInstance();
			$config->set('idev_url', $idev_url);
		}

		$result = RSMembership::updateIdev(array('idev_saleamt' => 1.00, 'idev_ordernum' => 'test', 'ip_address' => '127.0.0.1'));

		if (!$result['success'])
		{
			JError::raiseWarning(500, JText::sprintf('COM_RSMEMBERSHIP_IDEV_COULD_NOT_CONNECT', $result['url'], !empty($result['error']) ? $result['error'] : JText::_('COM_RSMEMBERSHIP_UNKNOWN'), $result['code']));
			return false;
		}
		
		return true;
	}
}