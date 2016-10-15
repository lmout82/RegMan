<?php
/**
 * @package     Congress management
 * @subpackage  com_regman
 *
 * @copyright   Copyright (C) 2015 lmout82
 * @license     GNU General Public License version 2 or later;
 */
 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
 
class RegManController extends JController
{
	protected function redirectHome()
	{
		$app = JFactory::getApplication();
		$url = JRoute::_('index.php?option=com_regman');
		$app->redirect($url);
	}
	
	// return an array of ids from a multiple selection
	protected function getPostData($form_name = '')
	{
		$jinput = JFactory::getApplication()->input;
		$users  = $jinput->post->get($form_name, 0, 'array');
		
		foreach ($users as $index => $id)
			$users[$index] = @abs((int)$id); 
		
		return $users;
	}
	
	// $value: 1 -> paid, if not 0
	protected function setPaidStatus($users, $value)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$fields = array($db->quoteName('paid').'='.$db->escape($value));
		$list_id ='';
		foreach ($users as $index => $id)
			$list_id .= ($db->escape($id).','); 
			
		$list_id = substr($list_id, 0, -1); 
		$conditions = array($db->quoteName('id').' IN('.$list_id.')');
		
		$query->update($db->quoteName('#__users'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();
	}
	
	public function update()
	{
		// date and time of the last update of the page
		$date_committee = new DateTime('now', new DateTimeZone(COM_REGMAN_ORGANIZERS_TIMEZONE));

		$msg = "Last update: ".$date_committee->format('H:i')." (".COM_REGMAN_ORGANIZERS_TIMEZONE.")";
		JFactory::getApplication()->enqueueMessage($msg);
		
		$this->redirectHome();
	}
	
	// move selected users to the category of pre-registred users
	public function move_prereg()
	{
		$users = array();
		$users = $this->getPostData('paidreg');
		
		if(count($users)==0)
		{
			$this->update();
			return;
		}
				
		$this->setPaidStatus($users, 0);
		$this->update();
	}
	
	// move selected users to the category of paid users
	public function move_paid()
	{		
		$users = array();	
		$users = $this->getPostData('prereg');
		
		if(count($users)==0)
		{
			$this->update();
			return;
		}
				
		$this->setPaidStatus($users, 1);
		$this->update();
	}
}