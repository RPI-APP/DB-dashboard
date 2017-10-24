<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<link rel="shortcut icon" href="<?php echo base_url(); ?>images/xenon.gif" />
	
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Xenon Control Panel</title>
    
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?php echo base_url(); ?>css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url(); ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>jqwidgets/styles/jqx.base.css" />

    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>js/jquery.js"></script>
    
    <!-- add the jQWidgets framework -->
	<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxcore.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxbuttons.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxdata.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>jqwidgets/jqxscrollbar.js"></script>

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url(); ?>">Xenon Control Panel</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $name; ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                    	<li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> Profile TODO</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings TODO</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
					<?php
						$userdata = $this->session->userdata('logged_in');
					?>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/dashboard"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    
                    <?php
						if ($userdata['can_data_view']):
                    ?>
					<li>
                        <a href="<?php echo base_url(); ?>index.php/tables"><i class="fa fa-fw fa-table"></i> Export</a>
                    </li>
                    <?php
						endif;
						if ($userdata['can_data_view'] && FALSE):
					?>
                    <li>
                        <a href="#"><i class="fa fa-fw fa-bar-chart-o"></i> Charts TODO</a>
                    </li>
                    <?php
						endif;
						if ($userdata['can_markers_manage'] && FALSE):
					?>
                    <li>
                        <a href="#"><i class="fa fa-fw fa-clock-o"></i> Timestamps TODO</a>
                    </li>
                    <?php
						endif;
						if (true):
					?>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/instrument"><i class="fa fa-fw fa-eyedropper"></i> Instruments</a>
                    </li>
                    <?php
						endif;
						if ($userdata['can_users_edit']):
					?>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/user"><i class="fa fa-fw fa-group"></i> Users</a>
                    </li>
                    <?php
						endif;
					?>
                    <!--
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#users"><i class="fa fa-fw fa-wrench"></i> Users <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="users" class="collapse">
                            <li>
                                <a href="<?php echo base_url(); ?>index.php/user/create">Create User</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>index.php/user/edit">Edit Users</a>
                            </li>
                        </ul>
                    </li>
                    -->
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>








