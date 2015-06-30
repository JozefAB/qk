<?php
/*------------------------------------------------------------------------
# com_guru
# ------------------------------------------------------------------------
# author    iJoomla
# copyright Copyright (C) 2013 ijoomla.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.ijoomla.com
# Technical Support:  Forum - http://www.ijoomla.com/forum/index/
-------------------------------------------------------------------------*/

define( '_JEXEC', 1 );
define('JPATH_BASE', substr(substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), "components")),0,-1));

define( 'DS', DIRECTORY_SEPARATOR );
$parts = explode(DS, JPATH_BASE);
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once ( JPATH_BASE .DS.'configuration.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'database'.DS.'database.php');

$config = new JConfig();
$database =  JFactory::getDBO();

$q = "SELECT * FROM #__guru_config WHERE id = '1'";
$database->setQuery($q);
$configs = $database->loadObjectList();
		
$q = "SELECT * FROM #__guru_media WHERE id = ".intval($_GET['id']);
$database->setQuery($q);
$result = $database->loadObjectList();
	
$the_media = $result["0"];


if($the_media->type == 'text'){
	$media = $the_media->code;
	if(strpos($media, "src=") !== FALSE){
		$the_base_link = explode('components/', $_SERVER['HTTP_REFERER']);
		$the_base_link = $the_base_link[0];
		$media = str_replace('src="', 'src="'.$the_base_link, $media);
	}
}
if($the_media->type == 'docs'){	
	$the_base_link = explode('components/', $_SERVER['HTTP_REFERER']);
	$the_base_link = $the_base_link[0];				
	
	$media = 'The selected element is a text file that can\'t have a preview';
	//$media = JText::_("GURU_TASKS");
	
	if($the_media->source == 'local' && (substr($the_media->local,(strlen($the_media->local)-3),3) == 'txt' || substr($the_media->local,(strlen($the_media->local)-3),3) == 'pdf') && $the_media->width == 0)
	$media='<div class="contentpane">
					<iframe id="blockrandom"
						name="iframe"
						src="'.$the_base_link.'/'.$configs->docsin.'/'.$the_media->local.'"
						width="100%"
						height="600"
						scrolling="auto"
						align="top"
						frameborder="2"
						class="wrapper">
						This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
					</div>';
					
	if($the_media->source == 'local' && $the_media->width == 1)
	$media = '<a href="'.$the_base_link.'/'.$configs->docsin.'/'.$the_media->local.'" target="_blank">'.$the_media->name.'</a>';

	if($the_media->source == 'url'  && $the_media->width == 0)
	$media='<div class="contentpane">
					<iframe id="blockrandom"
						name="iframe"
						src="'.$the_media->url.'"
						width="100%"
						height="600"
						scrolling="auto"
						align="top"
						frameborder="2"
						class="wrapper">
						This option will not work correctly. Unfortunately, your browser does not support inline frames.</iframe>
					</div>';		
					
	if($the_media->source == 'url'  && $the_media->width == 1)
	$media='<a href="'.$the_media->url.'" target="_blank">'.$the_media->name.'</a>';								
}	
if($the_media->type == 'quiz'){
	//$media = 'The quiz is being generated';
	$media = '';
	
	$q  = "SELECT * FROM ".$dbprefix."guru_quiz WHERE id = ".$the_media->source;
	$result_quiz=mysql_fetch_object(mysql_query($q));		
	
	$media = $media. '<strong>'.$result_quiz->name.'</strong><br /><br />';
	$media = $media. $result_quiz->description.'<br /><br />';
	
	$q  = "SELECT * FROM ".$dbprefix."guru_questions WHERE qid = ".$the_media->source;
	$quiz_questions=mysql_query($q);	
	
	while( $one_question = mysql_fetch_array($quiz_questions) )
		{
			$media = $media.'<div align="left">'.$one_question['text'].'<div>';
			
			$media = $media.'<div align="left" style="padding-left:30px;">';
			if($one_question['a1']!='')
				$media = $media.'<input type="radio" name="'.$one_question['id'].'">'.$one_question['a1'].'</input><br />';
			if($one_question['a2']!='')
				$media = $media.'<input type="radio" name="'.$one_question['id'].'">'.$one_question['a2'].'</input><br />';
			if($one_question['a3']!='')
				$media = $media.'<input type="radio" name="'.$one_question['id'].'">'.$one_question['a3'].'</input><br />';
			if($one_question['a4']!='')
				$media = $media.'<input type="radio" name="'.$one_question['id'].'">'.$one_question['a4'].'</input><br />';
			if($one_question['a5']!='')
				$media = $media.'<input type="radio" name="'.$one_question['id'].'">'.$one_question['a5'].'</input><br />';
			if($one_question['a6']!='')
				$media = $media.'<input type="radio" name="'.$one_question['id'].'">'.$one_question['a6'].'</input><br />';
			if($one_question['a7']!='')
				$media = $media.'<input type="radio" name="'.$one_question['id'].'">'.$one_question['a7'].'</input><br />';
			if($one_question['a8']!='')
				$media = $media.'<input type="radio" name="'.$one_question['id'].'">'.$one_question['a8'].'</input><br />';
			if($one_question['a9']!='')
				$media = $media.'<input type="radio" name="'.$one_question['id'].'">'.$one_question['a9'].'</input><br />';		
			if($one_question['a10']!='')
				$media = $media.'<input type="radio" name="'.$one_question['id'].'">'.$one_question['a10'].'</input><br />';		
			$media = $media.'</div>';																																										
		}		
		
	$media = $media.'<br /><div align="left" style="padding-left:30px;"><input type="submit" value="Submit" disabled="disabled" /></div><input name="text_is_quiz" type="hidden" value="1">';	
}	
$media = str_replace('’','\'',$media);
$media = str_replace("“","\"",$media);
$media = str_replace("”","\"",$media);
echo stripslashes($media); 
die();
?>