<?php

/*------------------------------------------------------------------------
# com_guru
# ------------------------------------------------------------------------
# author    iJoomla
# copyright Copyright (C) 2013 ijoomla.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.ijoomla.com
# Technical Support:  Forum - http://www.ijoomla.com.com/forum/index/
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgSystemGuruCron extends JPlugin{
    function plgSystemGuruCron(&$subject, $config)  {
        parent::__construct($subject, $config);
    }

    function onAfterRender(){
		$app =& JFactory::getApplication();
		if($app->isSite()){
			require_once(JPATH_ROOT.DS.'components'.DS.'com_guru'.DS.'helpers'.DS.'cronjobs.php');
			guru_cronjobs();
		}
    }
}
?>