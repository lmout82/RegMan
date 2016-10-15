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

class RegManViewRegMan extends JView
{	
	public function display($tpl = null) 
	{
		// add a specific CSS
		JHtml::stylesheet(JURI::base().'components/com_regman/css/default.css',array(),true);
		
		// set the breadcrumb
		$mainframe  = &JFactory::getApplication();
		$pathway    = &$mainframe->getPathway();
		$breadcrumb = $pathway->setPathway(array());
		$pathway->addItem(JText::_('RegMan'),'');
		
		// get the users from pre-registration user group
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName(array('id','name','first_name','organization','country','paid','prereg_date','tarification')))
			->from($db->quoteName('#__user_usergroup_map'))
			->join('INNER', $db->quoteName('#__users').' ON ('.$db->quoteName('user_id').' = '.$db->quoteName('id').')')
			->where($db->quoteName('group_id').'='.COM_REGMAN_ID_GROUP_PREREG)
			->order($db->quoteName('name'));
		 
		$db->setQuery($query);
		$db->execute();

		$results = $db->loadAssocList();
		
		// sort the users between pre-reg and reg.
		$nb_paid   = 0;
		$nb_prereg = 0;
		$rows_paid   = "";
		$rows_prereg = "";
		$tr_under_paid   = true;
		$tr_under_prereg = true;
		
		foreach($results as $row => $oneresult)
		{
			$id = @abs((int)$oneresult['id']);
			
			$prereg_date = strtotime($oneresult['prereg_date']);
			$prereg_date_txt = "";
			if($prereg_date == FALSE || $prereg_date == strtotime('0000-00-00 00:00:00'))
				$prereg_date_txt = '?';
			else
				$prereg_date_txt = date("m/d/y", $prereg_date);
			
			if($oneresult['paid'] == true)
			{
				$rows_paid .= '<tr '.($tr_under_paid==true?'class="odd"':'').'>';
				$rows_paid .= '<td><input type="checkbox" name="paidreg[]" value="'.$id.'" /></td><td>'.$id.'</td><td><a  href="index.php?component=regman&tmpl=component&view=showid&id='.$id.'" class="modal" rel="{handler: \'iframe\', size: {x: 700, y: 600}}">'.htmlentities($oneresult['name'],ENT_COMPAT|ENT_HTML401,'UTF-8').'</a></td><td>'.htmlentities($oneresult['first_name'],ENT_COMPAT|ENT_HTML401,'UTF-8').'</td><td>'.htmlentities($oneresult['organization'],ENT_COMPAT|ENT_HTML401,'UTF-8').'</td><td>'.htmlentities($oneresult['country'],ENT_COMPAT|ENT_HTML401,'UTF-8').'</td><td align="center">'.htmlentities($prereg_date_txt,ENT_COMPAT|ENT_HTML401,'UTF-8').'</td><td align="center"><a  href="index.php?component=regman&tmpl=component&view=invoiceinfo&id='.$id.'" class="modal" rel="{handler: \'iframe\', size: {x: 800, y: 350}}">+</a></td>';
				$rows_paid .= '</tr>';
				$tr_under_paid = ($tr_under_paid XOR true);
				$nb_paid++;
			}
			else
			{
				$rows_prereg .= '<tr '.($tr_under_prereg==true?'class="odd"':'').'>';
				$rows_prereg .= '<td><input type="checkbox" name="prereg[]" value="'.$id.'" /></td><td>'.$id.'</td><td><a  href="index.php?component=regman&tmpl=component&view=showid&id='.$id.'" class="modal" rel="{handler: \'iframe\', size: {x: 700, y: 600}}">'.htmlentities($oneresult['name'],ENT_COMPAT|ENT_HTML401,'UTF-8').'</a></td><td>'.htmlentities($oneresult['first_name'],ENT_COMPAT|ENT_HTML401,'UTF-8').'</td><td>'.htmlentities($oneresult['organization'],ENT_COMPAT|ENT_HTML401,'UTF-8').'</td><td>'.htmlentities($oneresult['country'],ENT_COMPAT|ENT_HTML401,'UTF-8').'</td><td>'.htmlentities($prereg_date_txt,ENT_COMPAT|ENT_HTML401,'UTF-8').'</td>';
				$rows_prereg .= '</tr>';
				$tr_under_prereg = ($tr_under_prereg XOR true);	
				$nb_prereg++;
			}
		}
				
		// assign data to the view
		$this->nb_paidreg  = htmlentities($nb_paid,ENT_COMPAT|ENT_HTML401,'UTF-8');
		$this->nb_prereg   = htmlentities($nb_prereg,ENT_COMPAT|ENT_HTML401,'UTF-8');
		$this->nb_totalreg = htmlentities($nb_prereg+$nb_paid,ENT_COMPAT|ENT_HTML401,'UTF-8');
				
		$this->paidreg = $rows_paid;
		$this->prereg  = $rows_prereg;
 
		// display the view
		parent::display($tpl);
	}
}