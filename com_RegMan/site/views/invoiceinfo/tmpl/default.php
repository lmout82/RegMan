<?php
/**
 * @package     Congress management
 * @subpackage  com_regman
 *
 * @copyright   Copyright (C) 2015 lmout82
 * @license     GNU General Public License version 2 or later;
 */
 
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
?>
<form method="post" action="<?php echo JURI::current().'?tmpl=component&view=invoiceinfo&id='.$this->id; ?>" name="invoice_info">
	<fieldset><legend>Invoice information for <?php echo $this->first_name." ".$this->name; ?></legend>
		<table border="0">
			<tr>
				<td>Payment date (yyyy-mm-dd)</td>
				<td><input maxlength="10" title="" style="width:224px" value="<?php echo $this->payment_date; ?>" name="payment_date" type="text"></td>
			</tr>
			<tr>
				<td>Fee</td>
				<td>
					<select name="tarification" style="width:230px">
						<?php echo $this->tarification_options; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Discount on the total net price ($)&nbsp;&nbsp;&nbsp;-</td>
				<td><input maxlength="5" title="" style="width:224px" value="<?php echo $this->discount; ?>" name="discount" type="text"></td>
			</tr>
			<tr>
				<td>Additional information</td>
				<td><textarea maxlength="390" rows="5" cols="58"  name="add_info"><?php echo $this->add_info; ?></textarea></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="Update"></td>
			</tr>
		</table>
	</fieldset>
</form>
<p></p>
<p>If this information is correct, click <a href="<?php echo JURI::base().'components/com_regman/pdf/pdf.php?id='.$this->id; ?>">here</a> to get the invoice.</p>

