<?php
/**
 * @package     Congress management
 * @subpackage  com_regman
 *
 * @copyright   Copyright (C) 2015 lmout82
 * @license     GNU General Public License version 2 or later;
 */
 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.helper');
require_once('config.php');

// is the user a member of the organizer group ?
$user     =& JFactory::getUser();
$user_id  = $user->id;
$user_groups = JUserHelper::getUserGroups($user_id);

if(!in_array(COM_REGMAN_ID_GROUP_ORGANIZERS, $user_groups))
  return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));

// import joomla controller library
jimport('joomla.application.component.controller');

$controller = JController::getInstance('RegMan');
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
 
// redirect if set by the controller
$controller->redirect();