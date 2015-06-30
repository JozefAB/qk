<?php

/**
# svg_helper - Main plugin file
# -------------------------------------------------------------------------------------------------------------------------------------
# author		Joker
# copyright	Copyright (C) 2012 www.exself.ru. All Rights Reserved.
# @license	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website	http://www.joker.exself.ru
# Support		info@exself.ru
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 

class plgSystemSvg_helper extends JPlugin {
 
	function onAfterRender()
	{
		$app =& JFactory::getApplication();

		if ($app->getName()!= 'site') {
			return true;
		}
 
		$buffer = JResponse::getBody();

		if (strripos($buffer, '_svg.png') !== false) {
		
			include('plugins/system/svg_helper/Modernizr/modernizr-server.php');

			if (isset($_COOKIE['svg-check']) && strripos($_COOKIE['svg-check'], 'svg:1') !== false) {
				$buffer = str_replace('_svg.png', '_png.svg', $buffer);
			}
		}

		/* Older versions compatibility start */

		$extension = $this->params->get('extension');

		if (strripos($buffer, $extension) !== false) {
		
			include('plugins/system/svg_helper/Modernizr/modernizr-server.php');

			if($this->params->get('enable_replacement')==1)
			{
				if (isset($_COOKIE['svg-check']) && strripos($_COOKIE['svg-check'], 'svg:1') !== false) {
					$buffer = str_replace($extension, 'svg', $buffer);
				} else {
					$buffer = str_replace($extension, 'png', $buffer);
				}
			}
		}
		
		/* Older versions compatibility end */

		if ($buffer != '') {
			JResponse::setBody($buffer);
		}
		return true;
	}
}