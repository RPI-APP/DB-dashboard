<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include "header.php"
?>

<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxnumberinput.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		// Create jqxNumberInput
		$("#numericInput").jqxNumberInput({ width: '200px', height: '25px', inputMode: 'simple', enableMouseWheel: false, spinButtons: true, value: 10000, min: 10, decimalDigits: 0, textAlign: 'left' });
	});
</script>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Create Field <small><?php echo $instrumentName; ?></small>
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
                
				<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxscrollbar.js"></script>
				<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxbuttons.js"></script>
				<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxlistbox.js"></script>
				<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxdropdownlist.js"></script>
				<script type="text/javascript">
					$(document).ready(function () {
						$("#datatype").jqxDropDownList({autoDropDownHeight: true, width: 150, height: 25 });
					});
				</script>
                
                <div class="col-lg-6">
                
		           	<?php
						$this->load->helper('form');
	
						echo form_open('instrument/submitcreatefield');
						
						echo form_hidden('instrumentId', $instrumentId);
		
						$class = ' class="form-control" ';
						echo form_label('Name:', 'name');
						echo form_input('name', '', $class);
					?>
					<br />
					Description:<br />
					<textarea id="description" name="description" rows="4" cols="70"></textarea>
					<br /><br />
					Data type: 
					<?php
						$types = array();
						$types[0] = "Integer";
						$types[1] = "Double";
						$types[2] = "String";
	
						echo form_dropdown('datatype', $types, "1", 'id="datatype"');
					?>
					<br />
					Timeout (ms):
					<div style='margin-top: 3px;' id='numericInput' name='timeout'></div>
					<br />
					<?php
						echo form_submit('mysubmit', 'Create', 'class="btn btn-default"');
						echo form_close();
					?>
				</div>

<?php
include "footer.php"

?>
