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
                            Fields <small><?php echo $instrumentName; ?></small>
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-4">
				        <script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxlistbox.js"></script>
				        <script type="text/javascript">
							function SelectAll(id)
							{
								document.getElementById(id).focus();
								document.getElementById(id).select();
							}
						</script>
						<script type="text/javascript">
							var EmnTL_fieldselectsource = [
									<?php
										$first = true;
										foreach ($allFieldNames as $f)
										{
											if (!$first)
												echo ",";
											else
												$first = false;
						
											echo '{';
											echo 'label: ' . json_encode($f['name']) . ', ';
											echo 'value: ' . $f['id'] . ', ';
											echo 'description: ' . json_encode($f['description']) . ', ';
											echo 'uuid: ' . json_encode($f['uuid']) . ' ';
											echo '}';
										}
									?>
								]
							$(document).ready(function () {
								$("#fieldselect").jqxListBox({ source: EmnTL_fieldselectsource, width: 300, height: 400 });
						
								$("#fieldselect").on('select', function (event) {
								    if (event.args) {
								        var item = event.args.item;
								        if (item) {
								        	var index = -1;
								        	for(var i = 0; i < EmnTL_fieldselectsource.length; i++)
								        	{
								        		if (EmnTL_fieldselectsource[i].value == item.value)
								        		{
								        			index = i;
								        			break;
								        		}
								        	}
								            $("#uuid_area").val(EmnTL_fieldselectsource[index].uuid);
								            $("#desc_area").val(EmnTL_fieldselectsource[index].description);
								        }
								    }
								});
							});
						</script>
	
						<?php
							$this->load->helper('form');
							echo form_open('instrument/fieldselect');
					
							echo form_hidden('instrumentId', $instrumentId);
	
							//$options = $allFieldNames;
							//echo form_dropdown('fieldselect', $options, null, 'id="fieldselect"');
						?>
						<div id='fieldselect' name='fieldselect'></div>
						<br />
						
						<?php
							if ($userdata['can_instruments_add'])
							{
								echo form_submit('editsubmit', 'Settings', 'class="btn btn-default"');
								echo form_submit('addsubmit', '+', 'class="btn btn-default"');
							}
							//$confirm = ' onclick="return confirm(\'Are you sure you want to delete this field?\');" ';
							//echo form_submit('delsubmit', '-', 'class="btn btn-default"' . $confirm);
							echo form_close();
						?>
					</div>
					<div class="col-lg-4">
						<div>Description:<br /><textarea readonly name="desc_area" id="desc_area" rows="4" cols="70"></textarea></div>
						<div>UUID:<br /><textarea readonly name="uuid_area" id="uuid_area" onClick="SelectAll('uuid_area');" rows="2" cols="70"></textarea></div>
					</div>
				</div>

<?php
include "footer.php"

?>
