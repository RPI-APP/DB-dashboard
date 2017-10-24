<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include "header.php"
?>



    <iframe id="dash" src="<?php echo base_url(); ?>dashboard/index.php" style="width: 100%; border: none;"></iframe>

	<script>
	$(function() {
		var aboveHeight = 55;//$("#wrapper").outerHeight(true);
		$(window).resize(function() {
			$('#dash').height( $(window).height() - aboveHeight );
		}).resize();
	});
	</script>

    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="<?php echo base_url(); ?>js/plugins/morris/raphael.min.js"></script>
    <script src="<?php echo base_url(); ?>js/plugins/morris/morris.min.js"></script>
    <script src="<?php echo base_url(); ?>js/plugins/morris/morris-data.js"></script>

</body>

</html>
