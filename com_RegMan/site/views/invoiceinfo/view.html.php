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
 
class  RegManViewInvoiceInfo extends JView
{
	// get the last invoice of the user if it exists
	private function getUserInvoice($user_id)
	{
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName(array('tarification','discount','add_info','payment_date')))
			->from($db->quoteName('#__invoices'))
			->where($db->quoteName('user_id').' ='.$db->escape($user_id))
			->setLimit('1');
	
		$db->setQuery($query);
		$db->execute();
		return $db->loadAssoc();
	}
	
	// get the name, the first_name and the registration price of a user  
	private function getUserInfo($user_id)
	{
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName(array('name','first_name','tarification')))
			->from($db->quoteName('#__users'))
			->where($db->quoteName('id').' ='.$db->escape($user_id))
			->setLimit('1');
	
		$db->setQuery($query);
		$db->execute();
		return $db->loadAssoc();	
	}
	
	// show the calculation form for creating an invoice 
	private function showUserForm ($invoice)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
				
		$query
			->select($db->quoteName(array('code','name','amount_bef_eb','amount_aft_eb')))
			->from($db->quoteName('#__pricescale'))
			->setLimit('6');
			
		$db->setQuery($query);
		$db->execute();
		$avb_tarifications = $db->loadAssocList();
	
		$options = '';
		if(trim($invoice['tarification'])==false)
			$options .= '<option value="?" disabled="disabled" selected="selected">Please select</option>';
			
		foreach ($avb_tarifications as $index => $tarification)
			$options .= ('<option value="'.htmlentities($tarification['code'],ENT_COMPAT|ENT_HTML401,'UTF-8').'" '.($invoice['tarification']==$tarification['code']?'selected':'').'>'.htmlentities($tarification['name'],ENT_COMPAT|ENT_HTML401,'UTF-8').'</option>');
		
		$this->payment_date = htmlentities(date('Y-m-d', strtotime($invoice['payment_date'])),ENT_COMPAT|ENT_HTML401,'UTF-8'); 
		$this->tarification_options = $options;
		$this->discount  = htmlentities($invoice['discount'],ENT_COMPAT|ENT_HTML401,'UTF-8');
		$this->add_info  = htmlentities($invoice['add_info'],ENT_COMPAT|ENT_HTML401,'UTF-8');
	}
	
	// update the invoice in db
	private function saveinvoice($invoice)
	{		
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);	
		$fields = array(
			$db->quoteName('tarification').'='.$db->quote($invoice['tarification']),
			$db->quoteName('discount').'='.$db->quote($invoice['discount']),
			$db->quoteName('add_info').'='.$db->quote($invoice['add_info']),
			$db->quoteName('payment_date').'='.$db->quote($invoice['payment_date']));
		$conditions = array(
			$db->quoteName('user_id').'='.$db->quote($invoice['user_id']));
			
		$query->update($db->quoteName('#__invoices'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();
	}

	// add a new invoice in db
	private function newinvoice($invoice)
	{		
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$fields = array('user_id','tarification','discount','add_info','payment_date');
		$values = array($db->quote($invoice['user_id']),$db->quote($invoice['tarification']),$db->quote($invoice['discount']),$db->quote($invoice['add_info']),$db->quote($invoice['payment_date']));
		$query
			->insert($db->quoteName('#__invoices'))
			->columns($db->quoteName($fields))
			->values(implode(',',$values));	
		$db->setQuery($query);
		return $db->execute();
	}
	
	public function display($tpl = null) 
	{		
		// get the 'id' parameter from the URL
		$jinput = JFactory::getApplication()->input;
		$id = $jinput->get->get('id', 0, 'UINT');
		
		if($id != 0)
		{
			// get some user information
			$user = $this->getUserInfo($id);
			$this->id         = $id;
			$this->name       = $user['name'];
			$this->first_name = $user['first_name'];
			
			// first, look for the last user invoice
			$invoice = $this->getUserInvoice($id);
			
			if(count($invoice) == 0)
			{
				// no invoice found -> blank form
				$invoice_info = array();
				$invoice_info['payment_date'] = date("Y-m-d"); 
				$invoice_info['tarification'] = $user['tarification'];
				$invoice_info['user_id']  = $id;
				$invoice_info['discount'] = 0;
				$invoice_info['add_info'] = '';
				
				$this->showUserForm($invoice_info);
				$this->newinvoice($invoice_info);
			}
			else
			{
				// if data was sent from the form -> save it in the db
				// otherwise show a pre-filled form 
				$jinput = JFactory::getApplication()->input;
				$invoice_info = array();
				$invoice_info = $jinput->post->getArray(array('payment_date'=>'', 'tarification'=>'','discount'=>0,'add_info'=>''));
				
				if(isset($invoice_info['tarification']))
				{
					$invoice_info['user_id'] = $id;
					$this->saveinvoice($invoice_info);
					$this->showUserForm($invoice_info);
				}
				else
					$this->showUserForm($invoice);
			}
		}
		
		// display the view
		parent::display($tpl);
	}
}