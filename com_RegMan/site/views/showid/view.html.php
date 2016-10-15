<?php
/**
 * @package     Congress management
 * @subpackage  com_regman
 *
 * @copyright   Copyright (C) 2015 lmout82
 * @license     GNU General Public License version 2 or later;
 */
 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
 
class  RegManViewShowid extends JView
{
	public function display($tpl = null) 
	{		
		// get the 'id' parameter from the URL
		$jinput = JFactory::getApplication()->input;
		$id = $jinput->get->get('id', 0, 'UINT');
		
		// get user information from the db
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName(array('gender','name','first_name','email','organization','lab_service','address1','address2','city','zip_postal_code','country','phone','tarification','presentation','dietary_restrictions','appeared_in_photos','username')))
			->from($db->quoteName('#__users'))
			->where($db->quoteName('id').' ='.$db->escape($id))
			->setLimit('1');
	
		$db->setQuery($query);
		$db->execute();
		$user = $db->loadAssoc();
		
		// get price scale from the db
		$query = $db->getQuery(true);	
		$query
			->select($db->quoteName(array('code','name')))
			->from($db->quoteName('#__pricescale'))
			->setLimit('5');
			
		$db->setQuery($query);
		$db->execute();
		$price_scale = $db->loadAssocList();
		
		// assign data to the view
		$this->gender          = htmlentities($user['gender'],          ENT_COMPAT|ENT_HTML401, 'UTF-8');
		$this->name            = htmlentities($user['name'],            ENT_COMPAT|ENT_HTML401, 'UTF-8');
		$this->first_name      = htmlentities($user['first_name'],      ENT_COMPAT|ENT_HTML401, 'UTF-8');
		$this->email           = htmlentities($user['email'],           ENT_COMPAT|ENT_HTML401, 'UTF-8');
		$this->organization    = htmlentities($user['organization'],    ENT_COMPAT|ENT_HTML401, 'UTF-8');
		$this->lab_service     = htmlentities($user['lab_service'],     ENT_COMPAT|ENT_HTML401, 'UTF-8');
		$this->address1        = htmlentities($user['address1'],        ENT_COMPAT|ENT_HTML401, 'UTF-8');
		$this->address2        = htmlentities($user['address2'],        ENT_COMPAT|ENT_HTML401, 'UTF-8');
		$this->city            = htmlentities($user['city'],            ENT_COMPAT|ENT_HTML401, 'UTF-8');
		$this->zip_postal_code = htmlentities($user['zip_postal_code'], ENT_COMPAT|ENT_HTML401, 'UTF-8');
		$this->country         = htmlentities($user['country'],         ENT_COMPAT|ENT_HTML401, 'UTF-8');
		$this->phone           = htmlentities($user['phone'],           ENT_COMPAT|ENT_HTML401, 'UTF-8');
		
		foreach ($price_scale as $index => $price)
			if($user['tarification'] == $price['code'])
			{
				$this->tarification = htmlentities($price['name'],ENT_COMPAT|ENT_HTML401,'UTF-8');
				break;
			}
		
		$this->presentation         = htmlentities($user['presentation'],                    ENT_COMPAT|ENT_HTML401,'UTF-8');
		$this->dietary_restrictions = htmlentities($user['dietary_restrictions'],            ENT_COMPAT|ENT_HTML401,'UTF-8');
		$this->appeared_in_photos   = htmlentities(($user['appeared_in_photos']?"yes":"no"), ENT_COMPAT|ENT_HTML401,'UTF-8');
		
		$this->username = htmlentities($user['username'], ENT_COMPAT|ENT_HTML401, 'UTF-8');

		// display the view
		parent::display($tpl);
	}
}