<?php
/**
* @package RSMembership!
* @copyright (C) 2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/
defined('_JEXEC') or die('Restricted access');

class RSMembershipExport 
{
	public static function buildCSV($type, $data, $fileHash = '', $customFields = null) {
		require_once JPATH_COMPONENT.'/helpers/helper.php';
		
		$rows 	 		= '';
		if ($fileHash == '') {
		// Build header
			switch($type) {
				case 'subscribers':
					$headers = array(
						JText::_('COM_RSMEMBERSHIP_SUBSCRIBER_ID'),
						JText::_('COM_RSMEMBERSHIP_ENABLED'),
						JText::_('COM_RSMEMBERSHIP_NAME'),
						JText::_('COM_RSMEMBERSHIP_USERNAME'),
						JText::_('COM_RSMEMBERSHIP_EMAIL')
					);
					if ($customFields) {
						foreach ($customFields as $id => $properties) {
							$headers[] = JText::_($properties->label);
						}
					}
				break;
				case 'subscriptions':
					$headers = array(
						JText::_('COM_RSMEMBERSHIP_MEMBERSHIP'),
						JText::_('COM_RSMEMBERSHIP_USERNAME'),
						JText::_('COM_RSMEMBERSHIP_EMAIL'),
						JText::_('COM_RSMEMBERSHIP_STATUS'),
						JText::_('COM_RSMEMBERSHIP_NOTIFIED'),
						JText::_('COM_RSMEMBERSHIP_START_DATE'),
						JText::_('COM_RSMEMBERSHIP_START_END'),
						JText::_('JPUBLISHED')
					);	
				break;
			}
			// Add header to rows
			$rows .= '"'.implode('","', $headers).'"'."\n";
		}
		// Add the data to rows
		foreach ($data as $i => $entry) {
			$row = (array) $entry;
			switch($type) {
				case 'subscribers':
					$row['block'] = $row['block'] ? JText::_('JNO') : JText::_('JYES');
				break;
				
				case 'subscriptions':
					unset($row['membership_id']);
					unset($row['id']);
					unset($row['user_id']);
					$row['membership_start'] = RSMembershipHelper::showDate($row['membership_start']);
					$row['membership_end']   = ($row['membership_end'] != '0000-00-00 00:00:00' ? RSMembershipHelper::showDate($row['membership_end']) : ' - ');
					$row['notified']  		 = ($row['notified'] != '0000-00-00 00:00:00' ? RSMembershipHelper::showDate($row['notified']) : ' - ');
					$row['status'] 			 = JText::_('COM_RSMEMBERSHIP_STATUS_'.$row['status']);
					$row['published'] 		 = $row['published'] ? JText::_('JYES') : JText::_('JNO');
				break;
			}
			
			$rows .= '"'.implode('","',$row).'"';
			$rows .="\n";
		}
		
		return $rows;
	}
	
	public static function writeCSV($type, $query, $totalItems, $from, $fileHash = '', $filename, $customFields = null) {
		$db				= JFactory::getDBO();
		$db->setQuery($query, $from, 10);
		$data = $db->loadObjectList();
		
		$fileContent = RSMembershipExport::buildCSV($type, $data, $fileHash, $customFields);
		
		// build the file hash if not already created
		if ($fileHash == '') {
			$now 		= JHtml::date('now','D, d M Y H:i:s');
			$date 		= JHtml::date('now','Y-m-d_H-i-s');
			$filename 	= $filename.'-'.$date.'.csv';
			$fileHash 	= md5($filename.$now);
		}
		
		// create or append the hashed file and put content
		$path = JPATH_ADMINISTRATOR.'/components/com_rsmembership/';
		$success = false;
		if ($fileContent != '') {
			$test = file_put_contents($path.'tmp/'.$fileHash, $fileContent, FILE_APPEND);
			if ($test) $success = true;
		}
		
		$response  =  new stdClass();
		if ($success) {
			$newFrom = ($from + 10);
			$checkRemaining = $totalItems - $newFrom;
			$response->success = $success;
			$response->newFrom = ($checkRemaining > 0 ? $newFrom : $totalItems );
			$response->fileHash = $fileHash;
		}
		
		$response->success = $success;
		return $response;
		
	}
	
	public static function getCSV($fileHash) {
		$file 		= JPATH_ADMINISTRATOR.'/components/com_rsmembership/tmp/'.$fileHash;
		$content 	= is_file($file) ? file_get_contents($file) : '';
		return $content;
	}
	
	public static function buildCSVHeaders($filename) {
		// disable caching
		$now = JHtml::date('now','D, d M Y H:i:s');
		$date = JHtml::date('now','Y-m-d_H-i-s');
		$filename = $filename.'-'.$date.'.csv';
		
		header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: ".$now." GMT");

		// force download  
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");

		// disposition / encoding on response body
		header("Content-Disposition: attachment;filename=".$filename);
		header("Content-Transfer-Encoding: binary");
	}

}