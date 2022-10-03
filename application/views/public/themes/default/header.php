<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Event App</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!--Favicons-->
    <link rel="icon" href="https://info2ideas.com/wp-content/uploads/2021/09/cropped-Favicon-32x32.png" sizes="32x32" />
    <link rel="icon" href="https://info2ideas.com/wp-content/uploads/2021/09/cropped-Favicon-192x192.png" sizes="192x192" />
    <link rel="apple-touch-icon" href="https://info2ideas.com/wp-content/uploads/2021/09/cropped-Favicon-180x180.png" />
    
    <!-- Bootstrap core CSS     -->
    <link href="<?php echo base_url(); ?>assets/admin/css/bootstrap.min.css" rel="stylesheet" />

    <!--  Light Bootstrap Dashboard core CSS    -->
    <link href="<?php echo base_url(); ?>assets/admin/css/theme-style.css" rel="stylesheet"/>
    
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="<?php echo base_url(); ?>assets/admin/css/style.css" rel="stylesheet" />
    <!-- Sweet Alert 2 plugin  -->
    <link href="<?php echo base_url(); ?>assets/admin/css/sweetalert2.min.css" rel="stylesheet" />

    <!--     Fonts and icons     -->
    <link href="<?php echo base_url(); ?>assets/admin/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- <link href='<?php echo base_url(); ?>assets/admin/css/Roboto.css' rel='stylesheet' type='text/css'> -->
    <link href="https://fonts.googleapis.com/css2?family=Varela+Round&amp;display=swap" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/admin/css/pe-icon-7-stroke.css" rel="stylesheet" />
    <?php if(isset($dttable_tab) == 'dt_table'): ?>
	    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/admin/css/buttons.dataTables.min.css">
    <?php endif; ?>
    
<?php echo $before_head;?>
</head>
<body>