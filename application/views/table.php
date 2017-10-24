<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include "header.php"
?>
<!-- List of all things -->
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxpanel.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxtree.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxcheckbox.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxdatetimeinput.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxcalendar.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxtooltip.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/globalization/globalize.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxnumberinput.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxmenu.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxlistbox.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxdropdownlist.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxgrid.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxgrid.pager.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxgrid.selection.js"></script>	
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxgrid.columnsresize.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxdata.export.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxgrid.export.js"></script> 

<script type="text/javascript">
	$(document).ready(function () {
		// Create jqxNumberInput
		$("#numericInput").jqxNumberInput({ width: '200px', height: '25px', inputMode: 'simple', enableMouseWheel: false, spinButtons: true, value: <?php echo $interval; ?>, min: 10, decimalDigits: 0, textAlign: 'left' });
	});
</script>

<script type="text/javascript">
	$(document).ready(function () {                
		// Create a jqxDateTimeInput
		
		$("#time1").jqxDateTimeInput({ width: '200px', height: '25px',  formatString: 'dd-MMM-yyyy HH:mm:ss' });
		$('#time1').jqxDateTimeInput('setDate', new Date(<?php echo $epoch; ?>));
		
		$("#time2").jqxDateTimeInput({ width: '200px', height: '25px',  formatString: 'dd-MMM-yyyy HH:mm:ss' });
		$('#time2').jqxDateTimeInput('setDate', new Date(<?php echo $end; ?>));
	});
</script>

<script type="text/javascript">
	$(document).ready(function () {
		// create jqxTree
		var source = [
			<?php
				$first1 = true;
				foreach ($instruments as $key=>$inst)
				{
					if (!$first1)
						echo ",";
					$first1 = false;
					
					// We append an 'i' in front of the ID for this one to mean "ignore" because we don't care if all fields in
					//  an instrument are selected, we process all of those fields individually.
					echo '{ label: ' . json_encode($inst->name) . ', value: "i' . $inst->id . '", expanded: false, items: [';

					$first2 = true;
					foreach ($fields[$key] as $f)
					{
						if (!$first2)
							echo ",";
						$first2 = false;
						
						$selected = "";
						if ($dataFields != null)
						{
							foreach ($dataFields as $df)
							{
								if ($f->id == $df->id)
								{
									$selected = ",checked: 'true'";
									break;
								}
							}
						}
						
						echo '{ label: ' . json_encode($f->name) . ', value: "' . $f->id . '" ' . $selected . ' }';
					}

					echo "]}";
				}
			?>
		];
		$('#jqxTree').jqxTree({ source: source, height: '450px', hasThreeStates: true, checkboxes: true, width: '300px', submitCheckedItems: true});
		$('#jqxTree').css('visibility', 'visible');
	});
</script>


<?php
	$showDataTable = ($dataFields != null);
	if ($showDataTable):
?>
<script type="text/javascript">
	$(document).ready(function () {
		// prepare the data
	
		var source =
		{
			 datatype: "json",
			 datafields: [
				{ name: '00000000-0000-0000-0000-000000000000' },
				<?php
					$lastField = array_pop($dataFields);
					$lastFieldName = array_pop($dataFieldsNames);
					foreach ($dataFields as $field)
						echo "{ name: '" . $field->guid . "' },";
					echo "{ name: '" . $lastField->guid . "' },";
				?>
			],
			url: '<?php echo base_url(); ?>index.php/tables/mysqlData?<?php echo $query; ?>',
			root: 'Rows',
			cache: false,
			beforeprocessing: function(data)
			{		
				source.totalrecords = data[0].TotalRows;
			}
		};		
		
		var dataadapter = new $.jqx.dataAdapter(source);
		// initialize jqxGrid
		$("#jqxgrid").jqxGrid(
		{
			width: '100%',
			source: dataadapter,
			
			autoheight: true,
			pageable: true,
			virtualmode: true,
			pagesize: 10,
			columnsresize: true,
			selectionmode: 'none',
			rendergridrows: function()
			{
				  return dataadapter.records;     
			},
			columns: [
				{ text: 'Time (ms)', datafield: '00000000-0000-0000-0000-000000000000', width: 200 },
				<?php
					foreach ($dataFields as $key=>$field)
						echo "{ text: " . json_encode($dataFieldsNames[$key]) . ", datafield: '" . $field->guid . "', width: 200 },";
					echo "{ text: " . json_encode($lastFieldName) . ", datafield: '" . $lastField->guid . "', width: 200 }";
				?>
			  ]
		});
	});
</script>
<?php
	endif;
?>
        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Export Data
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
                
                <?php
					if ($showDataTable) :
				?>
						<div id="jqxgrid"></div>
						<br />
				<?php
					endif;
				?>
                
                <?php
					$this->load->helper('form');
					echo form_open('tables/submit', array("id" => "myform"));
				?>
                
                <div id='jqxTree' name="tree" style='visibility: hidden;'></div>
				
				<br />
				
				Interval (ms):
				<div style='margin-top: 3px;' id='numericInput' name='interval'></div>
				<br />
				Start: <div id='time1' name="time1"></div>
				<br />
				End: <div id='time2' name="time2"></div>

				<br />
				
				<?php
					echo form_submit('prevsubmit', 'Preview', 'class="btn btn-default"');
					echo form_submit('exportsubmit', 'Export', 'class="btn btn-default"');
					echo form_close();
				?>
				<br />
                
<?php
include "footer.php"




?>
