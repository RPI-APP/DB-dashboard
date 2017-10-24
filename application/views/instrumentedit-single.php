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
                            Edit Instrument <small><?php echo $u->name; ?></small>
                        </h1>
                    </div>
                </div>
                
                <!-- /.row -->
                <div class="col-lg-6">
					<?php
						$confirm = ' onclick="return confirm(\'Are you sure you want to delete this instrument FOREVER?\');" ';
						if ($userdata['can_instruments_rem']):
					?>
					<a href="<?php echo base_url(); ?>index.php/instrument/deleteinstrument/<?php echo $u->id; ?>" <?php echo $confirm; ?>>Delete Forever </a>
					<br /><br />
					<?php
						endif;
					?>
					<?php
						$this->load->helper('form');
						echo form_open('instrument/submitedit');
	
						echo form_hidden('id', $u->id);
	
						$class = ' class="form-control" ';
						echo form_label('Name:', 'name');
						echo form_input('name', $u->name, $class);
					?>
					<br />
					<div class="checkbox"><label><?php echo form_checkbox('enabled', '1', $u->enabled); ?>Enabled</label></div>

					<br />
					<?php
						echo form_submit('mysubmit', 'Submit edits', 'class="btn btn-default"');
						echo form_close();
					?>
				</div>
<?php
	include "footer.php"
?>
