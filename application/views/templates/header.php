<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title; ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <meta name="description" content="Elephant is an admin template that helps you build modern Admin Applications, professionally fast! Built on top of Bootstrap, it includes a large collection of HTML, CSS and JS components that are simple to use and easy to customize.">
    <meta property="og:url" content="http://demo.madebytilde.com/elephant">
    <meta property="og:type" content="website">
    <meta property="og:title" content="The fastest way to build Modern Admin APPS for any platform, browser, or device.">
    <meta property="og:description" content="Elephant is an admin template that helps you build modern Admin Applications, professionally fast! Built on top of Bootstrap, it includes a large collection of HTML, CSS and JS components that are simple to use and easy to customize.">
    <meta property="og:image" content="http://demo.madebytilde.com/elephant.jpg">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@madebytilde">
    <meta name="twitter:creator" content="@madebytilde">
    <meta name="twitter:title" content="The fastest way to build Modern Admin APPS for any platform, browser, or device.">
    <meta name="twitter:description" content="Elephant is an admin template that helps you build modern Admin Applications, professionally fast! Built on top of Bootstrap, it includes a large collection of HTML, CSS and JS components that are simple to use and easy to customize.">
    <meta name="twitter:image" content="http://demo.madebytilde.com/elephant.jpg">
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="<?php echo base_url() ?>assets/admin/css/nucleo-icons.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>assets/admin/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="<?php echo base_url() ?>assets/admin/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="<?php echo base_url() ?>assets/admin/css/argon-dashboard.css" rel="stylesheet" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url() ?>assets/admin/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="<?php echo base_url() ?>assets/admin/img/logo.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?php echo base_url() ?>assets/admin/img/logo.png" sizes="16x16">
    <link rel="manifest" href="<?php echo base_url() ?>assets/admin/manifest.json">
    <link rel="mask-icon" href="<?php echo base_url() ?>assets/admin/safari-pinned-tab.svg" color="#0288d1">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500,700">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/vendor.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/elephant.min.css">
	  <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/application.min.css">
	<!-- CSS datatable-->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/demo.min.css">

  <!-- TAMBAHAN -->
  <script src="<?= base_url(); ?>assets/jquery/jquery.min.js"></script>
  <!-- SCRIPT CKEDITOR -->
  <script type="text/javascript" src="<?= base_url(); ?>assets/ckeditor/ckeditor.js"></script>
  </head>
  <body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-primary position-absolute w-100">
      <ul class="nav navbar-nav navbar-right">
        <ul class="sidenav navbar-nav  justify-content-end white" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <li class="nav-item d-flex align-items-right"> 
              <a href="javascript:;" class="nav-link font-weight-bold px-1 white dropdown-toggle  " data-bs-toggle="dropdown" id="navbarDropdownMenuLink2" aria-haspopup="false">
                <span class="d-sm-inline d-none "><img class="box" width="36" height="36" src="<?= base_url(); ?>assets/admin/img/marie.jpg"> Fajar H.</span>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                <li><a class="dropdown-item" href="<?= base_url(); ?>profile ">Profile</a></li>
                <li><a class="dropdown-item" href="<?= base_url(); ?>auth/logout">Sign out</a></li>
              </ul>
            </li>
        </ul>
      </ul>
    </div>
    