<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!doctype html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> 	<html lang="en"> <!--<![endif]-->
<head>
	<link rel="shortcut icon" href="<?php echo base_url(); ?>images/xenon.gif" />
	
	<!-- General Metas -->
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	<!-- Force Latest IE rendering engine -->
	<title>Login Form</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
	
	<!-- Stylesheets -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/base.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/skeleton.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/layout.css">
	
</head>
<body>

<?php if(!empty($errors = validation_errors(' ', ' ')) ) : ?>
	<div class="notice">
		<a href="" class="close">close</a>
		<p class="warn"><?php echo $errors; ?></p>
	</div>
<?php endif; ?>


	<!-- Primary Page Layout -->

	<div class="container">
		
		<div class="form-bg">
			<?php echo form_open('login'); ?>
				<h2>Login</h2>
				<p><input type="text" id="username" name="username" placeholder="Username"></p>
				<p><input type="password" id="password" name="password" placeholder="Password"></p>
				<button type="submit"></button>
			</form>
		</div>

	<?php
		//<p class="forgot">Forgot your password? That's unfortunate.</p>
	?>

	</div><!-- container -->

	<!-- JS  -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.js"></script>
	<script>window.jQuery || document.write("<script src='js/jquery-1.5.1.min.js'>\x3C/script>")</script>
	<script src="<?php echo base_url(); ?>js/app.js"></script>
	
<!-- End Document -->
</body>
</html>
