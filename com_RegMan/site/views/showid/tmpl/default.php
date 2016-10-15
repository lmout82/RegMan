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
<h1>Registrant information</h1>
<p></p><p></p>
<form>
	<fieldset><legend>Personal information</legend>
		<table border="0">
			<tr>
				<td>Gender</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->gender; ?>" name="gender" type="text" readonly></td>
			</tr>
			<tr>
				<td>First name</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->first_name; ?>" name="first_name" type="text" readonly></td>
			</tr>
			<tr>
				<td>Family name</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->name; ?>" name="name" type="text" readonly></td>
			</tr>
			<tr>
				<td>Email Address</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->email; ?>" name="email" type="text" readonly></td>
			</tr>
			<tr>
				<td>Univ./Organization</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->organization; ?>" name="organization" type="text" readonly></td>
			</tr>
			<tr>
				<td>Lab./Service</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->lab_service; ?>" name="lab_service" type="text" readonly></td>
			</tr>
			<tr>
				<td>Address</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->address1; ?>" name="address1" type="text" readonly></td>
			</tr>
			<tr>
				<td>Address (cont'd)</td>
				<td><input maxlength="150" size="30" class="" title="" value="<?php echo $this->address2; ?>" name="address2" type="text" readonly></td>
			</tr>
			<tr>
				<td>City</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->city; ?>" name="city" type="text" readonly></td>
			</tr>
			<tr>
				<td>ZIP/Postal code</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->zip_postal_code; ?>" name="zip_postal_code" type="text" readonly></td>
			</tr>
			<tr>
				<td>Country</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->country; ?>" name="country" type="text" readonly></td>
			</tr>
			<tr>
				<td>Phone</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->phone; ?>" name="phone" type="text" readonly></td>
			</tr>
		</table>
	</fieldset>
	<br/>
	<fieldset><legend>Registration questions</legend>
		<table border="0">
			<tr>
				<td>Fee</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->tarification; ?>" name="tarification" type="text" readonly></td>
			</tr>
			<tr>
				<td>I wish</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->presentation; ?>" name="presentation" type="text" readonly></td>
			</tr>
			<tr>
				<td>Dietary Restrictions</td>
				<td><textarea cols="50" rows="3" class="" title="" name="dietary_restrictions" readonly><?php echo $this->dietary_restrictions; ?></textarea></td>
			</tr>
			<tr>
				<td>Permission to publish photos in which I appeared</td>
				<td><input name="appeared_in_photos" id="appeared_in_photos_0" title="" value="<?php echo $this->appeared_in_photos; ?>" readonly></td>
			</tr>
		</table>
	</fieldset>
	<br/>
	<fieldset><legend>Account information</legend>
		<table border="0">
			<tr>
				<td>Username</td>
				<td><input maxlength="150" size="30" title="" value="<?php echo $this->username; ?>" name="username" type="text" readonly></td>
			</tr>
		</table>
	</fieldset>	
</form>