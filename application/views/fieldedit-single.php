<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include "header.php"
?>

<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxnumberinput.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		// Create jqxNumberInput
		$("#numericInput").jqxNumberInput({ width: '200px', height: '25px', inputMode: 'simple', enableMouseWheel: false, spinButtons: true, value: <?php echo $field->timeout; ?>, min: 10, decimalDigits: 0, textAlign: 'left' });
	});
</script>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Edit Field <small><?php echo $field->name; ?></small>
                        </h1>
                    </div>
                </div>
                
                <script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxlistbox.js"></script>
				<script type="text/javascript">
					$(document).ready(function () {
						$("#instrumentselect").jqxListBox({ width: 250, height: 400 });
					});
				</script>
				
                <!-- /.row -->
                <div class="col-lg-6">
					<?php
						$confirm = ' onclick="return confirm(\'Are you sure you want to delete this field FOREVER?\');" ';
						if ($userdata['can_instruments_rem']):
					?>
					<a href="<?php echo base_url(); ?>index.php/instrument/deletefield/<?php echo $field->id; ?>" <?php echo $confirm; ?>>Delete Forever </a>
					<br /><br />
					<?php
						endif;
					?>
					<?php
						$this->load->helper('form');
						echo form_open('instrument/submiteditfield');
	
						echo form_hidden('id', $field->id);
	
						$class = ' class="form-control" ';
						echo form_label('Name:', 'name');
						echo form_input('name', $field->name, $class);
					?>
					<br />
					Description:<br />
					<textarea id="description" name="description" rows="4" cols="70"><?php echo htmlentities($field->description); ?></textarea>
					<br />
					Instrument:
					<?php
						$options = $instrumentNames;

						echo form_dropdown('instrumentselect', $options, $field->instrument, 'id="instrumentselect"');
					?>
					<br />
					Timeout (ms):
					<div style='margin-top: 3px;' id='numericInput' name='timeout'></div>
					<br />
					<div class="checkbox"><label><?php echo form_checkbox('enabled', '1', $field->enabled); ?>Enabled</label></div>

					<br />
					<?php
						echo form_submit('mysubmit', 'Submit edits', 'class="btn btn-default"');
						echo form_close();
					?>
				</div>
<?php
	include "footer.php"
?>
