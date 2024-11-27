<!DOCTYPE html>
<html>
<head lang="en">
    <?php $this->load->view('templates/tracking'); ?>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?= $title ?></title>

    <link rel="icon" type="image/png" href="<?= theme('favicon.png') ?>" />

    <link rel="stylesheet" href="/inc/css/lib/bootstrap-4.6.2/bootstrap.min.css">

	<link rel="stylesheet" href="/inc/css/separate/pages/login.min.css">
    <link rel="stylesheet" href="/inc/css/lib/font-awesome/font-awesome.min.css">

    <link rel="stylesheet" href="/inc/css/main.css">
	<link rel="stylesheet" href="/inc/css/lib/ladda-button/ladda-themeless.min.css">
	<link rel="stylesheet" href="/inc/css/lib/bootstrap-sweetalert/sweetalert.css">

	<link rel="stylesheet" href="<?= theme('styles.css') ?>">

	<!-- JS start -->
    <script src="/inc/js/lib/jquery-3.5.1/jquery-3.5.1.js"></script>
    <script src="/inc/js/lib/bootstrap-4.6.2/bootstrap.bundle.min.js"></script>
    <script src="/inc/js/plugins.js"></script>

	<script src="/inc/js/lib/ladda-button/spin.min.js"></script>
	<script src="/inc/js/lib/ladda-button/ladda.min.js"></script>

	<script src="/inc/js/lib/ladda-button/ladda-button-init.js"></script>
	<script src="/inc/js/lib/match-height/jquery.matchHeight.min.js"></script>
	<script src="/inc/js/lib/html5-form-validation/jquery.validation.min.js"></script>
	<script src="/inc/js/lib/bootstrap-sweetalert/sweetalert.min.js"></script>
	<script src="/inc/js/app.js"></script>
	 <!-- JS end -->

	<style>
	@media print {
		@page {
			size: auto;
		}
	}
	.sats_logo{
		width: 260px;
		margin-bottom: 10px;
	}
	.alert.alert-success.mx5 {
	    max-width: 320px;
	    margin: 15px auto;
	}
	</style>
</head>
<body>


    <div class="page-center">
        <div class="page-center-in">
            <div class="container-fluid">