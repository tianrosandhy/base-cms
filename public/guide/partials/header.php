<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">
  <head>
  	<title>CMS Maxsol Documentation</title>
  	<link rel="stylesheet" href="../admin_theme/vendor/font-awesome/css/font-awesome.min.css">
  	<link rel="stylesheet" href="../admin_theme/vendor/base/vendor.bundle.base.css">
  	<link rel="stylesheet" href="../admin_theme/css/style.min.css">
    <link rel="stylesheet" href="../admin_theme/vendor/prism/prism.css">
    <link rel="stylesheet" href="assets/additional.css">
  </head>
  <body>

    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
          <div class="navbar-brand-wrapper d-flex justify-content-center">
            <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
              <a class="navbar-brand brand-logo" href="index.php">
              	<img src="../admin_theme/img/logo.png" alt="Logo" style="height:20px;">
              </a>
              <a class="navbar-brand brand-logo-mini" href="{{ admin_url('/') }}">
                <img src="../admin_theme/img/logo.png" alt="Logo" style="height:7px;">
              </a>
            </div>  
          </div>
          <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="fa fa-bars"></span>
            </button>
          </div>
        </nav>
        <div class="container-fluid page-body-wrapper">
          <?php require('partials/sidebar.php') ?>

            <div class="main-panel">
              <div class="content-wrapper">
                <div class="row">
                  <div class="col-lg-8 col-md-10 ajax-load-content">
