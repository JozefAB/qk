<?php
/**
* @package RSMembership!
* @copyright (C) 2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/
defined('_JEXEC') or die('Restricted access');

class RSMembershipControllerSubscribers extends JControllerAdmin
{
	protected $text_prefix = 'COM_RSMEMBERSHIP';

	public function __construct($config = array()) 
	{
		parent::__construct($config);
		$this->registerTask('exportcsv',   'exportcsv');
	}

	public function getModel($name = 'Subscriber', $prefix = 'RSMembershipModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	public function writecsv() {
		$model = $this->getModel('Subscribers');
		$input = JFactory::getApplication()->input;
		
		$from = (int) $input->get('start');
		$fileHash = $input->get('filehash');
		
		$response = $model->writeCSV($from, $fileHash);
		
		$success 	= $response->success;
		$this->showResponse($success, $response);
	}
	
	protected function showResponse($success, $data=null) {
		$app 		= JFactory::getApplication();
		$document 	= JFactory::getDocument();
		
		// set JSON encoding
		$document->setMimeEncoding('application/json');
		
		// compute the response
		$response = new stdClass();
		$response->success = $success;
		if ($data) {
			$response->response = $data;
		}
		
		// show the response
		echo json_encode($response);
		
		// close
		$app->close();
	}
	
	public function exportcsv() {
		require_once JPATH_COMPONENT.'/helpers/export.php';
		
		$model = $this->getModel('Subscribers');
		
		$filename = JText::_('COM_RSMEMBERSHIP_SUBSCRIBERS');
		RSMembershipExport::buildCSVHeaders($filename);
		
		$input = JFactory::getApplication()->input;
		$fileHash = $input->get('filehash');
		
		echo RSMembershipExport::getCSV($fileHash);
		
		JFactory::getApplication()->close();
	}
	
}