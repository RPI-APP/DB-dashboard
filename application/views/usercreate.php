<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include "header.php"
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Create User <small>Fill out the info</small>
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
                <div class="col-lg-6">
<?php
	$this->load->helper('form');
	
	$attributes = array('autocomplete' => 'off');
	echo form_open('user/submitcreate', $attributes);
	
	// I can't believe I have to do this dumb kludge to make autofill not happen
	//  on these
	echo form_input('foilautofill', '', 'style="display:none;"');
	echo form_password('foilautofill2', '', 'style="display:none;"');
	
	$class = ' class="form-control" ';
	echo form_label('Username:', 'username');
	echo form_input('username', '', 'autocomplete=off' . $class);
?>
<p class="help-block">Enter the new username</p>

<?php
	echo form_label('Password:', 'password');
	echo form_password('password', '', 'autocomplete=off' . $class);
?>
<p class="help-block">Enter the new password (the user can change it later)</p>
<?php
	echo form_label('Name:', 'name');
	echo form_input('name', '', $class);
?>
<p class="help-block">Enter his or her real name</p>

<header>
	<h3>Permissions</h3>
</header>

<div class="checkbox"><label><?php echo form_checkbox('can_data_view', '1', 1); ?>View Data</label></div>
<div class="checkbox"><label><?php echo form_checkbox('can_markers_manage', '1', 0); ?>Manage Markers</label></div>
<div class="checkbox"><label><?php echo form_checkbox('can_instruments_add', '1', 0); ?>Add Instruments</label></div>
<div class="checkbox"><label><?php echo form_checkbox('can_instruments_rem', '1', 0); ?>Remove Instruments</label></div>
<div class="checkbox"><label><?php echo form_checkbox('can_users_edit', '1', 0); ?>Manage Users</label></div>

<br />
<?php
	echo form_submit('mysubmit', 'Create', 'class="btn btn-default"');
	echo form_close();
	
	?>
	
	</div>

<?php
include "footer.php"

?>
