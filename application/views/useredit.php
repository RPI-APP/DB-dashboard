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
                            Users <small>Edit, add, or remove</small>
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
                
                <script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxlistbox.js"></script>
				<script type="text/javascript">
					$(document).ready(function () {
						$("#userselect").jqxListBox({ width: 250, height: 400 });
					});
				</script>
	
				<?php
					$this->load->helper('form');
					echo form_open('user/editselect');
	
					$options = $usernamePass;

					echo form_dropdown('userselect', $options, null, 'id="userselect"');
				?>
				<br />
				<?php
					echo form_submit('editsubmit', 'Edit User', 'class="btn btn-default"');
					echo form_submit('addsubmit', '+', 'class="btn btn-default"');
					$confirm = ' onclick="return confirm(\'Are you sure you want to delete this user?\');" ';
					echo form_submit('delsubmit', '-', 'class="btn btn-default"' . $confirm);
					echo form_close();
				?>

<?php
include "footer.php"

?>
