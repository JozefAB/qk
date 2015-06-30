<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

/**
 * Script file of the jQuery Easy plugin
 */
class plgsystemjqueryeasyInstallerScript
{		
	/**
	 * Called before an install/update/uninstall method
	 *
	 * @return  boolean  True on success
	 */
	public function preflight($type, $parent) {
		echo '<br />';
	}
	
	/**
	 * Called after an install/update/uninstall method
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($type, $parent) 
	{	
		if ($type != 'uninstall') {
			
			echo '<dl>';
			echo '    <dt>Change log</dt>';
			echo '    <dd>ADDED: jQuery UI v1.11</dd>';
			echo '    <dd>ADDED: any library with \'latest\' in the path after \'jquery\' is correctly removed (ex: \'jquery-latest.js\')</dd>';
			echo '    <dd>MODIFIED: more libraries/no conflict scripts are correctly removed (ex: \'jqueryui.js\' or \'jquerynoconflict.js\' are now taken into account)</dd>';
			echo '</dl>';
		}
		
		return true;
	}
	
	/**
	 * Called on installation
	 *
	 * @return  boolean  True on success
	 */
	public function install($parent) {}
	
	/**
	 * Called on update
	 *
	 * @return  boolean  True on success
	 */
	public function update($parent) {}
	
	/**
	 * Called on uninstallation
	 */
	public function uninstall($parent) {}
	
}
?>
