<?php
/**
* @package RSMembership!
* @copyright (C) 2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSMembershipControllerConfiguration extends JControllerLegacy
{
	public function __construct() {
		parent::__construct();
		
		$user = JFactory::getUser();
		if (!$user->authorise('core.admin', 'com_rsmembership')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->redirect(JRoute::_('index.php?option=com_rsmembership', false));
		}
		
		$this->registerTask('apply', 'save');
	}

	/**
	 * Logic to save configuration
	*/
	public function save() 
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$data  = JFactory::getApplication()->input->get('jform', array(), 'array');
		$model = $this->getModel('configuration');
		$form  = $model->getForm();

		// Validate the posted data.
		$return = $model->validate($form, $data);

		// Check for validation errors.
		if ($return === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=configuration', false));
			return false;
		}

		$data = $return;

		if (!$model->save($data)) 
			$this->setMessage($model->getError(), 'error');
		else
			$this->setMessage(JText::_('COM_RSMEMBERSHIP_CONFIGURATION_OK'));


		$task = $this->getTask();
		if ($task == 'apply') {
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=configuration', false));
		} elseif ($task == 'save') {
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership', false));
		}
	}

	function idevCheckConnection()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Get the model
		$model = $this->getModel('Configuration', 'RSMembershipModel');
		
		// Save
		$result = $model->idevCheckConnection();
		
		$tabposition = JFactory::getApplication()->input->get('tabposition', 0, 'int');

		$link = 'index.php?option=com_rsmembership&view=configuration&tabposition='.$tabposition;
		
		$msg = '';
		if ($result)
			$msg = JText::_('COM_RSMEMBERSHIP_IDEV_CONNECT_SUCCESS');
		
		// Redirect
		$this->setRedirect($link, $msg);
	}
	
	function patchmodule() 
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		jimport('joomla.filesystem.file');
		$module = RSMembershipPatchesHelper::getPatchFile('module');
		$buffer = JFile::read($module);

		if ( strpos($buffer, 'RSMembershipPatchesHelper') !== false ) 
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('COM_RSMEMBERSHIP_PATCH_APPLIED'));

		if (!is_writable($module))
		{
			JError::raiseWarning(500, JText::_('COM_RSMEMBERSHIP_PATCH_NOT_WRITABLE'));
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
		}

		$replace = "\$db->setQuery(\$query);";
		// add the new patch 
		$with =  "\n"."\t\t"."if (file_exists(JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/patches.php')) {".
				 "\n"."\t\t\t"."include_once JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/patches.php';".
				 "\n"."\t\t\t"."\$rsm_where = RSMembershipPatchesHelper::getModulesWhere();".
				 "\n"."\t\t\t"."if (\$rsm_where) \$query->where(\$rsm_where);".
				 "\n"."\t\t"."}".
				 "\n"."\n"."\t\t".$replace;

		$buffer = str_replace($replace, $with, $buffer);

		if (JFile::write($module, $buffer)) 
			return $this->setRedirect( 'index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('COM_RSMEMBERSHIP_PATCH_SUCCESS') );

		JError::raiseWarning( 500, JText::_('COM_RSMEMBERSHIP_PATCH_NOT_WRITABLE') );
		$this->setRedirect( 'index.php?option=com_rsmembership&view=configuration&tabposition=1' );
	}

	function unpatchmodule()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		jimport('joomla.filesystem.file');
		$module = RSMembershipPatchesHelper::getPatchFile('module');
		$buffer = JFile::read($module);

		if ( strpos($buffer, 'RSMembershipPatchesHelper') === false && strpos($buffer, 'RSMembershipHelper') === false ) 
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('COM_RSMEMBERSHIP_PATCH_NOT_APPLIED'));

		if (!is_writable($module)) 
		{
			JError::raiseWarning(500, JText::_('COM_RSMEMBERSHIP_PATCH_NOT_WRITABLE'));
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
		}

		// delete the old patch just in case the update couldn't do it 
		if ( strpos($buffer, 'RSMembershipHelper') !== false )
		{
			$with 	 = "\$query->where('m.published = 1');";
			$replace = $with."\n"."\t\t"."if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php')) {".
							 "\n"."\t\t\t"."include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php');".
							 "\n"."\t\t\t"."\$rsm_where = RSMembershipHelper::getModulesWhere();".
							 "\n"."\t\t\t"."if (\$rsm_where) \$query->where(\$rsm_where);".
							 "\n"."\t\t"."}".
							 "\n";

			$buffer  = str_replace($replace, $with, $buffer);
		}
		$with 	   = "\$db->setQuery(\$query);";
		$replace   = "\n"."\t\t"."if (file_exists(JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/patches.php')) {".
					 "\n"."\t\t\t"."include_once JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/patches.php';".
					 "\n"."\t\t\t"."\$rsm_where = RSMembershipPatchesHelper::getModulesWhere();".
					 "\n"."\t\t\t"."if (\$rsm_where) \$query->where(\$rsm_where);".
					 "\n"."\t\t"."}".
					 "\n"."\n"."\t\t".$with;

		$buffer = str_replace($replace, $with, $buffer);

		if (JFile::write($module, $buffer))
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('COM_RSMEMBERSHIP_PATCH_REMOVED_SUCCESS'));

		JError::raiseWarning(500, JText::_('COM_RSMEMBERSHIP_PATCH_NOT_WRITABLE'));
		$this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
	}

	function patchmenu()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		jimport('joomla.filesystem.file');
		$menu 	= RSMembershipPatchesHelper::getPatchFile('menu');
		$buffer = JFile::read($menu);

		if (strpos($buffer, 'RSMembershipPatchesHelper') !== false)
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('COM_RSMEMBERSHIP_PATCH_APPLIED'));

		if (!is_writable($menu))
		{
			JError::raiseWarning(500, JText::_('COM_RSMEMBERSHIP_PATCH_NOT_WRITABLE'));
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
		}

		$replace = "\$menu->getItems('menutype', \$params->get('menutype'));";
		$with 	 = $replace."\n\n"."\t\t\t"."if (file_exists(JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/patches.php')) {".
						 "\n"."\t\t\t\t"."include_once JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/patches.php';".
						 "\n"."\t\t\t\t"."RSMembershipPatchesHelper::checkMenuShared(\$items);".
						 "\n"."\t\t\t"."}".
						 "\n";

		$buffer = str_replace($replace, $with, $buffer);

		if (JFile::write($menu, $buffer))
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('COM_RSMEMBERSHIP_PATCH_SUCCESS'));

		JError::raiseWarning(500, JText::_('COM_RSMEMBERSHIP_PATCH_NOT_WRITABLE'));
		$this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
	}

	function unpatchmenu()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		jimport('joomla.filesystem.file');
		$menu 	= RSMembershipPatchesHelper::getPatchFile('menu');
		$buffer = JFile::read($menu);

		if ( strpos($buffer, 'RSMembershipPatchesHelper') === false && strpos($buffer, 'RSMembershipHelper') === false ) 
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1', JText::_('COM_RSMEMBERSHIP_PATCH_NOT_APPLIED'));

		if (!is_writable($menu)) 
		{
			JError::raiseWarning(500, JText::_('COM_RSMEMBERSHIP_PATCH_NOT_WRITABLE'));
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
		}

		// delete the old patch just in case the update couldn't do it 
		if ( strpos($buffer, 'RSMembershipHelper') !== false ) 
		{
			$with 	 = "\$items 		= \$menu->getItems('menutype', \$params->get('menutype'));";
			$replace = $with."\n"."\t\t"."if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php')) {".
						 "\n"."\t\t\t"."include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'rsmembership.php');".
						 "\n"."\t\t\t"."RSMembershipHelper::checkMenuShared(\$items);".
						 "\n"."\t\t"."}".
						 "\n";
			$buffer = str_replace($replace, $with, $buffer);
		}

		$with 	 = "\$menu->getItems('menutype', \$params->get('menutype'));";
		$replace = $with."\n\n"."\t\t\t"."if (file_exists(JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/patches.php')) {".
							 "\n"."\t\t\t\t"."include_once JPATH_ADMINISTRATOR.'/components/com_rsmembership/helpers/patches.php';".
							 "\n"."\t\t\t\t"."RSMembershipPatchesHelper::checkMenuShared(\$items);".
							 "\n"."\t\t\t"."}".
							 "\n";

		$buffer = str_replace($replace, $with, $buffer);

		if (JFile::write($menu, $buffer))
			return $this->setRedirect('index.php?option=com_rsmembership&view=configuration', JText::_('COM_RSMEMBERSHIP_PATCH_REMOVED_SUCCESS'));

		JError::raiseWarning(500, JText::_('COM_RSMEMBERSHIP_PATCH_NOT_WRITABLE'));
		$this->setRedirect('index.php?option=com_rsmembership&view=configuration&tabposition=1');
	}
}