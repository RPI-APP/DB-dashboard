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
                            Instrument <small>Edit</small>
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
                <div class="col-lg-6">
					<?php
						$this->load->helper('form');
	
	
						echo form_open('instrument/submitcreate');
						
						$class = ' class="form-control" ';
						echo form_label('Instrument Name:', 'name');
						echo form_input('name', '', $class);
	
	
					?>

					<br />
					<?php
						echo form_submit('mysubmit', 'Create Instrument!', 'class="btn btn-default"');
						echo form_close();
					?>
				</div>
<?php
	include "footer.php"
?>
