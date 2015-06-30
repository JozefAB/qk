<?php
// NO DIRECT ACCESS
defined('_JEXEC') or die('Restricted Access');

// IMPORT LIBRARY DEPENDENCIES
jimport('joomla.event.plugin');

class plgSystemFilterJavascript extends JPlugin
{
	public function plgSystemFilterJavascript(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}
	
	public function onBeforeCompileHead()
	{
		$app		=& JFactory::getApplication();
		$document	=& JFactory::getDocument();
		if($app->isSite() && $document->getType() == "html"){
			$this->data			= $document->getHeadData();
			$clean_framework	= $this->filterFrameworks($this->params->get('framework'));
			$clean_scripts		= $this->filterScripts();
			$clean_declarations	= $this->filterDeclarations($this->params->get('framework'));
			$clean = array();
			foreach($clean_framework as $key){
				$clean[$key] = $this->data['scripts'][$key];
			}
			foreach($clean_scripts as $key){
				$clean[$key] = $this->data['scripts'][$key];
			}
			$this->data['scripts'] = $clean;
			$this->data['script']['text/javascript'] = $clean_declarations;
			$document->setHeadData($this->data);
		}
		return true;
	}
	
	private function getPatterns($type)
	{
		$prototype = array(
			'/prototype([\d\w\.\-]+)?\.js/',
			'/scriptaculous([\d\w\.\-]+)?\.js/',
			'/effects([\d\w\.\-]+)?\.js/',
			'/dragdrop([\d\w\.\-]+)?\.js/'
		);
		
		$mootools = array(
			'/mootools([\d\w\.\-]+)?\.js/'
		);
		
		$jquery = array(
			'/jquery([\d\w\.\-]+)?\.js/'
		);
		switch($type){
		case "withkeys":
			return array(
				'prototype'=>$prototype,
				'mootools'=>$mootools,
				'jquery'=>$jquery
			);
		case "nokeys":
			return array_merge($prototype, $mootools, $jquery);
		case "declarations":
			$pattern = array(
				'prototype'	=> '/document\.observe/',
				'mootools'	=> '/window\.addEvent/',
				'jquery'	=> '/\$\(window\)\.load/'
			);
			return $pattern;
		default:
			return array();
		}
	}
	
	private function filterFrameworks($framework)
	{
	
		$patterns = $this->getPatterns('withkeys');
		$clean = array();
		foreach($this->data['scripts'] as $key=>$value){
			$matches = array();
			foreach($patterns[$framework] as $pattern){
				if(preg_match($pattern, $key, $matches)){
					array_push($clean, $key);
					break 1;
				}
			}
		}
		return $clean;
	}
	
	private function filterScripts()
	{
		$patterns = $this->getPatterns('nokeys');
		$clean = array();
		foreach($this->data['scripts'] as $key=>$value){
			$match = false;
			foreach($patterns as $pattern){
				if(preg_match($pattern, $key)){
					$match = true;
					break 1;
				}
			}
			if($match){
				continue;
			}
			if((int)$this->params->get('system') == 0){
				if(!preg_match('/media\/system\/js/', $key)){
					array_push($clean, $key);
				}
			}
		}
		usort($clean, function ($a, $b){
			if($a == $b){
				return 0;
			}
			$priorities = array('http', 'template', 'component', 'module', 'plugin');
			$a_score = array();
			$b_score = array();
			foreach($priorities as $term){
				array_push($a_score, strpos($a, $term));
				array_push($b_score, strpos($b, $term));
			}
			$a_key = array_keys(array_filter($a_score, function($var){
				return ($var === false) ? false : true;
			}));
			$b_key = array_keys(array_filter($b_score, function($var){
				return ($var === false) ? false : true;
			}));
			if($a_key[0] < $b_key[0]){
				return -1;
			}
			if($a_key[0] > $b_key[0]){
				return 1;
			}
			return strcmp($a, $b);
		});
		return $clean;
	}
	
	private function filterDeclarations($framework)
	{
		$clean = array();
		$patterns = $this->getPatterns('declarations');
		$declarations = explode(chr(13), $this->data['script']['text/javascript']);
		// LOOP THROUGH EACH SCRIPT DECLARATION
		foreach($declarations as $script){
			$flag = true;
			foreach($patterns as $key => $value){
				if($key != $framework){
					// FILTER OUT SCRIPTS FROM FOREIGN FRAMEWORKS
					if(preg_match($value, $script)){
						$flag = false;
					}
				}
			}
			if($flag){
				array_push($clean, $script);
			}
		}
		// RETURN THE CLEAN SCRIPTS
		return implode(chr(13), $clean);
	}
}
