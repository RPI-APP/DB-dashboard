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
                            Edit User <small><?php echo $u->name . ' (' . $u->username . ')'; ?></small>
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
                
                <div class="col-lg-6">
		            <?php
						$this->load->helper('form');
						echo form_open('user/submitedit');
						echo form_hidden('id', $u->id);

						$class = ' class="form-control" ';
						echo form_label('Name:', 'name');
						echo form_input('name', $u->name, $class);
					?>

					<header>
						<h3>Permissions</h3>
					</header>

					<div class="checkbox"><label><?php echo form_checkbox('can_data_view', '1', $u->can_data_view); ?>View Data</label></div>
					<div class="checkbox"><label><?php echo form_checkbox('can_markers_manage', '1', $u->can_markers_manage); ?>Manage Markers</label></div>
					<div class="checkbox"><label><?php echo form_checkbox('can_instruments_add', '1', $u->can_instruments_add); ?>Add Instruments</label></div>
					<div class="checkbox"><label><?php echo form_checkbox('can_instruments_rem', '1', $u->can_instruments_rem); ?>Delete Instruments</label></div>
					<div class="checkbox"><label><?php echo form_checkbox('can_users_edit', '1', $u->can_users_edit); ?>Edit Users</label></div>
					
					<br />
					<?php
						echo form_submit('editsubmit', 'Submit edits', 'class="btn btn-default"');
					
						echo form_close();
					?>
				</div>

<?php
include "footer.php"

?>
