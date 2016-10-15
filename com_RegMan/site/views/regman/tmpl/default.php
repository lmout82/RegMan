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
<h1>Registration management</h1>
<p></p>
<p></p>
<p>All dates are provided in the american format (mm/dd/yyyy) unless otherwise specified.</p>
<p>Moving a person from the "pre-registrations" to "paid registrations" category allows you to edit the invoice.</p>
<p></p><br/>
<p>Click <input id="reload" type="button" value="here" onclick="window.location.href='index.php?option=com_regman&task=update'" /> to update data.</p>
<p></p><p></p>
<h2>Statistics</h2>
<p></p>
<center>
	<table id="hor-zebra">
		<thead>
			<tr>
				<th scope="col">&nbsp;</th>
				<th scope="col">Number</th>
			</tr>
		</thead>
		<tbody>
			<tr class="odd">
				<td>pre-registrations</td>
				<td><?php echo $this->nb_prereg; ?></td>
			</tr>
			<tr>
				<td>paid registrations</td>
				<td><?php echo $this->nb_paidreg; ?></td>
			</tr>
			<tr class="odd">
				<td>total</td>
				<td><?php echo $this->nb_totalreg; ?></td>
			</tr>
		</tbody>
	</table>
</center>
<p></p>
<h2>Details</h2>
<h3>Pre-registrations</h3>
The "+" button allows you to move a selection of registrants from "pre-registrated" to "paid" category.<br/> 
<table id="hor-zebra">
	<thead>
		<tr>
			<th scope="col">&nbsp;</th>
			<th scope="col">ID</th>
			<th scope="col">Family Name</th>
			<th scope="col">First name</th>
			<th scope="col">Organization</th>
			<th scope="col">Country</th>
			<th scope="col">Pre-reg.<br/>Date</th>
		</tr>
	</thead>
	<form method="post" action="index.php?option=com_regman&task=move_paid">
		<tbody>
			<?php echo $this->prereg; ?>
		</tbody>
		<input type="submit" value="+" />
	</form>
</table>
<h3>Paid registrations</h3>
The "-" button allows you to move a selection of registrants from "paid" to "pre-registrated" category.<br/> 
<table id="hor-zebra">
	<thead>
		<tr>
			<th scope="col">&nbsp;</th>
			<th scope="col">ID</th>
			<th scope="col">Family Name</th>
			<th scope="col">First name</th>
			<th scope="col">Organization</th>
			<th scope="col">Country</th>
			<th scope="col">Pre-reg.<br/>Date</th>
			<th scope="col">Invoice</th>
		</tr>
	</thead>
	<form method="post" action="index.php?option=com_regman&task=move_prereg">
		<tbody>
			<?php echo $this->paidreg; ?>
		</tbody>
		<input type="submit" value="-" />
	</form>
</table>