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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport ("joomla.application.component.view");

class guruAdminViewguruMedia extends JViewLegacy {

	function display ($tpl =  null ) { 
		
		JToolBarHelper::title(JText::_('GURU_MEDIAMAN'), 'generic.png');
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		//JToolBarHelper::addNew();
		JToolBarHelper::custom('duplicate','copy','copy', JText::_("GURU_DUPLICATE_MEDIA_BTN"), true);
		JToolBarHelper::editList();
		JToolBarHelper::deleteList(JText::_('GURU_MEDIA_REM_MESSAGE'));
			
		$filters = $this->get('filters');
		$this->assignRef('filters', $filters);
		
		$files = $this->get('listFiles');
		$this->assignRef('files', $files);
		
		$pagination = $this->get( 'Pagination' );
		$this->assignRef('pagination', $pagination);	
		parent::display($tpl);

	}
	
	function editForm($tpl = null) { 
		$app = JFactory::getApplication('administrator');
		$db = JFactory::getDBO();
		
		$data = JRequest::get('post');
		$this->assign("data", $data);
		
		$media = $this->get('file');
		$this->assign("media", $media);
		
		$config = $this->get('config');
		$this->assign('config',$config);
		
		JToolBarHelper::title(JText::_('GURU_MEDIA_').":<small>[".$media->text."]</small>");
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel ('cancel', JText::_('GURU_CANCEL_MEDIA_BTN'));
		
		parent::display($tpl);
	}
	
	function mass($tpl = null) { 
		JToolBarHelper::title(JText::_('GURU_MEDIA_MASS_TITLE'));
		JToolBarHelper::save("save_mass");
		JToolBarHelper::cancel('cancel', JText::_('GURU_CANCEL_MEDIA_BTN'));
		
		parent::display($tpl);
	}
	
	function preview ($tpl =  null ) { 
		$media =$this->get('mainMedia');
		$this->assignRef('media',$media);
		
		$config = $this->get('config');
		$this->assign('config',$config);
		
		parent::display($tpl);
	}
	
	function parentCategory($categ_id){
		if($categ_id == NULL){
			if(isset($_SESSION["category_id"])){
				$categ_id = $_SESSION["category_id"];
			}
		}
		$model = $this->getModel();
		$categ_db = $model->getAllRows(0, 0);
				
		$return = '';
		if(is_array($categ_db) && count($categ_db) == 0){
			$return .= '<select name="category_id">';
			$return .= 		'<option value="0">'.JText::_("GURU_GENERAL").'</option>';		
			$return .= '</select>';
		}
		else{
			$return .= '<select name="category_id">';
			foreach($categ_db as $key=>$val){
				$val = (object)$val;
				$id = $val->id;
				$name = $val->name;
				$line = "";
				for($i=0; $i<$val->level; $i++){
					$line .= "&#151;";
				}
				$selected = "";
				if($categ_id == $id){
					$selected = 'selected="selected"';
				}
				$return .= '<option value="'.$id.'" '.$selected.'>'.$line."(".$val->level.") ".$name.'</option>';
			}
			$return .= '</select>';
		}
		return $return;
	}
	function displayArticleguru($id, $guruartname){
		
		$value = "";
		$document = JFactory::getDocument();
		$html = "";	
		$article = JTable::getInstance('content');															
		if($id != "0"){
			$value = $id;
		}	
		else{
			$value = NULL;
		}
		if($value){
			$article->load($value);
		}							
		else{
			if($id != 0){				
				$article->title = $guruartname;
			}
			else{ 
				$article->title = '';
			}	
		}

		$name = 'article';
		$link = 'index.php?option=com_content&amp;task=element&amp;tmpl=component&amp;layout=modal&function=SelectArticleg";//function=iJoomlaSelectArticle';
		JHTML::_('behavior.modal', 'a.modal');
		
		$html = "\n".'<div class="fltlft"><div id="updateTextAfterDelete"><input type="text" size="75" id="'.$name.'_name" value="'.htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('GURU_MEDIATYPEARTICLE2').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">'.JText::_('GURU_SELECT_CHANGE').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="'.$name.'id" name="'.$name.'id" value="'.(int)$value.'" />';
		
		echo $html;
	}
	
	function getCourses(){
		$db = JFactory::getDBO();
		$sql = "select `id`, `name` from #__guru_program order by `name` ASC";
		$db->setQuery($sql);
		$db->query();
		$courses = $db->loadAssocList();
		return $courses;
	}
	
	function getTeachers(){
		$db = JFactory::getDBO();
		$sql = "select u.`id`, u.`name` from #__users u, #__guru_authors g where g.`userid`=u.`id` and g.`enabled`='1' order by u.`name` ASC";
		$db->setQuery($sql);
		$db->query();
		$courses = $db->loadAssocList();
		return $courses;
	}
	
	function getCourseName($id){
		$db = JFactory::getDBO();
		$sql = "select `name` from #__guru_program where `id`=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$name = $db->loadColumn();
		$name = @$name["0"];
		return $name;
	}
	
	function getCategoryName($id){
		$db = JFactory::getDBO();
		$sql = "select `name` from #__guru_media_categories where `id`=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$name = $db->loadColumn();
		$name = @$name["0"];
		return $name;
	}
}

?>