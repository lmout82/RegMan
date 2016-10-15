<?php
/**
 * @package     Congress management
 * @subpackage  com_regman
 *
 * @copyright   Copyright (C) 2015 lmout82
 * @license     GNU General Public License version 2 or later;
 */
 
defined('_JEXEC') or die('Restricted access');

define('COM_REGMAN_ORGANIZERS_TIMEZONE', 'Asia/Tokyo');
define('COM_REGMAN_ID_GROUP_ORGANIZERS', 16);
define('COM_REGMAN_ID_GROUP_PREREG',     15);   // pre-registration user group id 

define('COM_REGMAN_EARLY_BIRD', '04/21/2016');  // american format (m/d/y)
define('COM_REGMAN_VAT', 20);                   // VAT (%) for the invoice

// if you have installed joomla in a subdir ('/joomla25')
define('COM_REGMAN_PDF_JOOMLA_NAME_PARENT_DIRECTORY', '/myevent');
// the path relative to 'images', where the invoices are saved ('/invoices')
define('COM_REGMAN_PDF_INVOICES_FOLDER', '/2016/invoices');
 ?>