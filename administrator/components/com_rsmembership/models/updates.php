<?php
/**
* @package RSMembership!
* @copyright (C) 2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSMembershipModelUpdates extends JModelLegacy
{
	public function getHash() {
		$version = new RSMembershipVersion();
		return md5(RSMembershipHelper::getConfig('global_register_code').$version->key);
	}

	public function getJoomlaVersion() {
		$jversion = new JVersion();
		return $jversion->getShortVersion();
	}

	public function getSideBar() {
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';

		return RSMembershipToolbarHelper::render();
	}
}