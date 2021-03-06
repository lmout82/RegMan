<?php
/**
 * @package     Congress management
 * @subpackage  com_regman
 *
 * @copyright   Copyright (C) 2015 lmout82
 * @license     GNU General Public License version 2 or later;
 */

/*
Note: this script may not be the best way to create an invoice from a template but I have no control over the LAMP server.
The idea is to load an invoice template in the form of a JPG image with the help of the GD library. Then the GD library is
used to add text at specific coordinates (X,Y). At last, a pure PHP class called “FPDF” incorporates the resulting image
into a PDF file (without using the PDFlib library which is not installed on my server).
In this way, the script must be running on most servers. 
*/

define('_JEXEC', 1);
require_once('../config.php'); 
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT'].COM_REGMAN_PDF_JOOMLA_NAME_PARENT_DIRECTORY);
require_once(JPATH_BASE.'/includes/defines.php');
require_once(JPATH_BASE.'/includes/framework.php');
require_once('fpdf.php');

$app = JFactory::getApplication('site');

/* ----------------------------- *\
   is the visitor an organizer ?
\* ----------------------------- */
$user     =& JFactory::getUser();
$user_id  = $user->id;
$user_groups = JUserHelper::getUserGroups($user_id);

if(!in_array(COM_REGMAN_ID_GROUP_ORGANIZERS, $user_groups))
  exit("You are not authorized to view this page.");

/* ---------------- *\
   initializations
\* ---------------- */
$im = @imagecreatefromjpeg('invoice-template.jpg');

if(!$im)
	exit('');

$color = imagecolorallocate($im, 0, 0, 0); 
putenv('GDFONTPATH='.realpath('.'));
$font_size = 40;

/* -------------- *\
   get user data 
\* -------------- */
// id from the URL
$jinput = JFactory::getApplication()->input;
$id = 0;
$id = $jinput->get->get('id', 0, 'UINT');
if($id == 0)
	exit('');

// read the bd
$db = JFactory::getDbo();
$query = $db->getQuery(true);
		
$query
	->select($db->quoteName(array('name','first_name','email','organization','lab_service','address1','address2','city','zip_postal_code','country','phone','tarification')))
	->from($db->quoteName('#__users'))
	->where($db->quoteName('id').' ='.$db->escape($id))
	->setLimit('1');
	
$db->setQuery($query);
$db->execute();
$user = $db->loadAssoc();

/* ----------------- *\
   get invoice info  
\* ----------------- */
$query = $db->getQuery(true);
		
$query
	->select($db->quoteName(array('tarification','discount','add_info','payment_date')))
	->from($db->quoteName('#__invoices'))
	->where($db->quoteName('user_id').' ='.$db->escape($id))
	->setLimit('1');
	
$db->setQuery($query);
$db->execute();
$inv_info = $db->loadAssoc();

/* -------------------- *\
   get the price scale
\* -------------------- */
$query = $db->getQuery(true);
		
$query
	->select($db->quoteName(array('code','name','amount_bef_eb','amount_aft_eb')))
	->from($db->quoteName('#__pricescale'))
	->setLimit('5');
	
$db->setQuery($query);
$db->execute();
$avb_tarifications = $db->loadAssocList();
		
/* ---------*\
   |bill to|
\* ---------*/
$bill_X = 210;
$bill_Y = 900;
$bill_Y_step = 56;

imagettftext ($im, $font_size, 0, $bill_X, $bill_Y, $color, "FreeSerif.ttf", $user['name'].' '.$user['first_name']);
$bill_Y += $bill_Y_step; 
imagettftext ($im, $font_size, 0, $bill_X, $bill_Y, $color, "FreeSerif.ttf", $user['organization']);
$bill_Y += $bill_Y_step;
imagettftext ($im, $font_size, 0, $bill_X, $bill_Y, $color, "FreeSerif.ttf", $user['lab_service']);
$bill_Y += $bill_Y_step;
imagettftext ($im, $font_size, 0, $bill_X, $bill_Y, $color, "FreeSerif.ttf", $user['address1']);
$bill_Y += $bill_Y_step;
if(trim($user['address2']) != '')
{
	imagettftext ($im, $font_size, 0, $bill_X, $bill_Y, $color, "FreeSerif.ttf", $user['address2']);
	$bill_Y += $bill_Y_step;
}
imagettftext ($im, $font_size, 0, $bill_X, $bill_Y, $color, "FreeSerif.ttf", $user['zip_postal_code']);
$bill_Y += $bill_Y_step;
imagettftext ($im, $font_size, 0, $bill_X, $bill_Y, $color, "FreeSerif.ttf", $user['city']);
$bill_Y += $bill_Y_step;
imagettftext ($im, $font_size, 0, $bill_X, $bill_Y, $color, "FreeSerif.ttf", $user['country']);
$bill_Y += $bill_Y_step;
imagettftext ($im, $font_size, 0, $bill_X, $bill_Y, $color, "FreeSerif.ttf", $user['email'].' - '.$user['phone']);

/* --------- *\
   |INVOICE|
\* --------- */
$invoice_X = 2000;
$invoice_Y = 400;
$invoice_Y_step = 56;

imagettftext ($im, $font_size, 0, $invoice_X, $invoice_Y, $color, "FreeSerif.ttf", date('M j Y'));
$invoice_Y += $invoice_Y_step; 
imagettftext ($im, $font_size, 0, $invoice_X, $invoice_Y, $color, "FreeSerif.ttf", '16'.date('ymd', strtotime($inv_info['payment_date'])).$id);
$invoice_Y += $invoice_Y_step;
imagettftext ($im, $font_size, 0, $invoice_X, $invoice_Y, $color, "FreeSerif.ttf", $id);

/* -------------------- *\
   calculating the fee
\* -------------------- */
$payment_date = strtotime($inv_info['payment_date']);
$registration_fee_txt   = '';
$registration_fee_price = 0;

foreach ($avb_tarifications as $index => $tarification)
{
	if($tarification['code'] == $inv_info['tarification'])
	{
		$registration_fee_txt = $tarification['name']; 
		if($payment_date > strtotime(COM_REGMAN_EARLY_BIRD)) 
			$registration_fee_price = $tarification['amount_aft_eb'];
		else
			$registration_fee_price = $tarification['amount_bef_eb'];
		
		break;
	}
}

/* -------------*\
   |Description|
\* -------------*/
$desc_X   = 177;
$amount_X = 2000;
$desc_Y   = 1500;
$desc_Y_step = 56;
imagettftext ($im, $font_size, 0, $desc_X, $desc_Y, $color, "FreeSerif.ttf", 'Registration fee: '.$registration_fee_txt);
imagettftext ($im, $font_size, 0, $amount_X, $desc_Y, $color, "FreeSerif.ttf", $registration_fee_price);
$desc_Y += $desc_Y_step; 
imagettftext ($im, $font_size, 0, $desc_X, $desc_Y, $color, "FreeSerif.ttf", 'Discount');
imagettftext ($im, $font_size, 0, $amount_X, $desc_Y, $color, "FreeSerif.ttf", $inv_info['discount']);

/* ---------------- *\
   |Other comments|
\* ----------------*/
$add_info_X      = 173;
$add_info_Y      = 2010;
$add_info_Y_step = 56;
$add_info_array  = array();

$add_info_array = preg_split('/\n|\r\n?/', trim($inv_info['add_info']));
if(count($add_info_array) == 0)
	imagettftext ($im, $font_size, 0, $add_info_X, $add_info_Y, $color, "FreeSerif.ttf", '(none)');
else
{
	foreach ($add_info_array as $index => $line)
	{
		imagettftext ($im, $font_size, 0, $add_info_X, $add_info_Y, $color, "FreeSerif.ttf", $line);
		$add_info_Y += $add_info_Y_step;
	}
} 

/* ------- *\
   totals
\* ------- */
$subtotal = $registration_fee_price-$inv_info['discount'];  // net price
$tax_rate = COM_REGMAN_VAT;
$tax_due  = $subtotal*$tax_rate/100;
$total    = $subtotal+$tax_due;

$totals_X = 2000;
$totals_Y = 1830;
$totals_Y_step = 56;
imagettftext ($im, $font_size, 0, $totals_X, $totals_Y, $color, "FreeSerif.ttf", round($subtotal, 2));
$totals_Y += $totals_Y_step; 
imagettftext ($im, $font_size, 0, $totals_X, $totals_Y, $color, "FreeSerif.ttf", round($tax_rate, 2).'%');
$totals_Y += $totals_Y_step; 
imagettftext ($im, $font_size, 0, $totals_X, $totals_Y, $color, "FreeSerif.ttf", round($tax_due, 2));
$totals_Y += $totals_Y_step+10; 
imagettftext ($im, $font_size, 0, $totals_X, $totals_Y, $color, "FreeSerif.ttf", round($total, 2));

/* ------------------------------ *\
   Save the image of the invoice
   Free some resources 
   Export the image in PDF
\* ------------------------------ */
$image_invoice_dir  = JPATH_BASE.'/images'.COM_REGMAN_PDF_INVOICES_FOLDER;
$image_invoice_name = 'invoice_'.$id;
$image_invoice_full_path = $image_invoice_dir.'/'.$image_invoice_name.'.jpg';
 
imagejpeg ($im, $image_invoice_full_path, 100);
imagedestroy ($im);

$pdf = new FPDF();
$pdf->AddPage("P","A4");
$pdf->Image($image_invoice_full_path,0,0,-300,-300,'JPEG');
$pdf->Output('invoice.pdf','D');
?>