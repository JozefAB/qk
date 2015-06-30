<?php
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
jimport( 'joomla.filesystem.folder' );

class com_guruInstallerScript{

	function install(){
	}
	
	function update($parent){
		$this->install();
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent){
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//echo '<p>' . JText::_('COM_ALTACOACH_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent){
		/*$app = JFactory::getApplication("admin");
		$app->redirect(JURI::root()."administrator/index.php?option=com_guru&installer=1");*/
		
		echo '<script language="javascript" type="text/javascript">
					setTimeout(function(){window.location.href = "'.JURI::root()."administrator/index.php?option=com_guru&installer=1".'";}, 3000);
			  </script>';
		return true;
	}
}
?>