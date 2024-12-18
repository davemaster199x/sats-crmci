<!DOCTYPE html>
<html>
<head lang="en">
    <?php $this->load->view('templates/tracking'); ?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo $title; ?></title>

    <link rel="icon" type="image/png" href="<?= theme('favicon.png') ?>" />

    <link rel="stylesheet" href="/inc/css/lib/bootstrap-4.6.2/bootstrap.min.css">

	<link rel="stylesheet" href="/inc/css/lib/lobipanel/lobipanel.min.css">
	<link rel="stylesheet" href="/inc/css/separate/vendor/lobipanel.min.css">
	<link rel="stylesheet" href="/inc/css/lib/jqueryui/jquery-ui.min.css">
	<link rel="stylesheet" href="/inc/css/separate/pages/widgets.min.css">
	<!--
	<link rel="stylesheet" href="/inc/css/lib/flatpickr/flatpickr.min.css">
	<link rel="stylesheet" href="/inc/css/separate/vendor/flatpickr.min.css">
	-->
	<link rel="stylesheet" href="/inc/css/lib/bootstrap-sweetalert/sweetalert.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.4.2/jquery.fancybox.min.css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.dataTables.min.css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css" />

    <link rel="stylesheet" href="/inc/css/lib/font-awesome/font-awesome.min.css">
	<link rel="stylesheet" href="/inc/css/main.css">
	<link rel="stylesheet" href="/inc/css/custom.css">
	<link rel="stylesheet" href="/inc/loading-bar/loading-bar.css"/>

	<link rel="stylesheet" href="<?= theme('styles.css') ?>">

    <!-- JS start -->
    <script src="/inc/js/lib/jquery-3.5.1/jquery-3.5.1.js"></script>
    <script src="/inc/js/lib/bootstrap-4.6.2/bootstrap.bundle.min.js"></script>
    <script src="/inc/js/plugins.js"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script type="text/javascript" src="/inc/js/lib/jqueryui/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/lobipanel/lobipanel.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/match-height/jquery.matchHeight.min.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script src="/inc/js/lib/bootstrap-sweetalert/sweetalert.min.js"></script>
	<script src="/inc/js/lib/html5-form-validation/jquery.validation.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.4.2/jquery.fancybox.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/bootstrap-select/bootstrap-select.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/select2/select2.full.min.js"></script>
	<script type="text/javascript" src="/inc/ion_sound/ion.sound.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/pusher/pusher.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/jquery-idle-master/jquery.idle.js"></script>
	<script type="text/javascript" src="/inc/js/custom.js"></script>
	<script type="text/javascript" src="/inc/js-cookie/js.cookie.js"></script>
	<script type="text/javascript" src="/inc/loading-bar/loading-bar.js"></script>
	<script type="text/javascript" src="/inc/js/jquery.tablednd_0_5.js"></script>
	<script type="text/javascript" src="/inc/js/jsignature/jSignature.min.js"></script>
	<script type="text/javascript" src="/inc/js/input_mask/dist/jquery.inputmask.js"></script>
	<script type="text/javascript" src="https://momentjs.com/downloads/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/searchpanes/2.2.0/js/dataTables.searchPanes.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>

    <link rel="stylesheet" href="/inc/css/custom-datatables-searchpanes.css"/>
    <script type="module" src="/inc/js/CustomDatatablesSearchPanes.js"></script>
    <script type="module" src="/inc/js/PageOptions.js"></script>
    <script type="module" src="/inc/js/ExportPagesFunctions.js"></script>
    <script type="module" src="/inc/js/DatatableConfigOptions.js"></script>

	<style type="text/css">
	@media print {
		@page {
			size: auto;
		}
	}
	#load-screen {
		width: 100%;
		height: 100%;
		background: url("/images/preloader2.gif") no-repeat center center #fff;
		position: fixed;
		opacity: 0.7;
		display:none;
		margin-top: -25px;
	}
	/* region filters */
	#region_dp_div{
		padding: 1px 10px 1px 6px;
		position: absolute;
		top: 60px;
		display: none;
		z-index: 10;
		min-width: 129px;
		width: -moz-max-content;
	}
	#region_dp_div .state_div {
		margin: 4px 0;
	}
	#region_dp_div .region_div {
		margin: 13px 0 0 24px;
	}
	#region_dp_div .sub_region_div_chk {
		margin: 13px 0 0 26px;
	}
	#region_dp_div .rf_select{
		font-weight: bold;
	}
	.form-control:disabled, .form-control[readonly] {
		background-color: #fafafa;
        cursor: not-allowed;
		opacity: 1;
	}
	.jtopheader_left .dropdown-toggle::after {
		display: none;
	}
	.about_page_icon{
		margin-right: 5px;
	}
	.fancybox-content {
		max-width: 60%;
	}
	.fb_trigger{
		display: none;
	}
	#about_page_fb ul li span{
		padding: 0 2px;
	}
	#search_fb .row{
		margin-bottom: 10px;
	}
	#search_icon_fb{
		position: relative;
		right: 9px;
		bottom: 1px;
	}
	.top_profile_name{
		position: relative;
		right: 12px;
		color: #fff;
		padding: 7px;
		font-size: 15px;
		font-weight:600;
	}
	.logged_user_span{
		margin-right: 3px;
	}
	.site-header .site-header-collapsed{
		margin-right: -500px!important;
	}

	/* z index heirarchy */
	.site-header{
		z-index: 100 !important;
	}
	.fancybox-container{
		z-index: 200 !important;
	}
	#load-screen{
		z-index: 300 !important;
	}

	.pdf_header_div {
		display: inline-block;
		max-width: 100%;
		vertical-align: top;
		position: relative;
		padding: 0 30px 0 60px;
		padding-right: 30px;
		height: 54px;
		margin: 0 0 20px 0;
		font-size: .9375rem;
		line-height: 18px;
	}
	.pdf_header_div .name{
		text-align: left;
	}

	.ldBar{
		width: 15% !important;
		height: 15% !important;
		z-index: 200;
	}
	.ldBar_center {
		margin: 0;
		position: absolute;
		top: 500px;
		left: 50%;
		transform: translate(-50%, -50%);
	}
	.load-screen-bg {
		width: 100%;
		height: 100%;
		position: fixed;
		opacity: 0.5;
		margin-top: -25px;
		z-index: 100;
		background-color: #ECEFF4;
	}
	.loading-bar-div{
		display: none;
	}
    .user_full_name{
        padding: 7px;
        float: right;
        margin-right: 50%;
        color: #fff;
		font-size: 15px;
		font-weight:600;
    }
	.site-header .site-logo img {
		height: auto;
		position: relative;
		bottom: 15px;
		left: 24px;
		top: unset;
	}

	/* tech specific CSS */
	.btn {
		padding: 3px;
		border-radius: .25rem;
	}
	.tds_tbl button.btn{
		width: 100px !important;
	}
	body.sidebar-hidden .page-content{
		padding-left: unset;
	}
	.container-fluid{
		padding: 0;
	}
	.page-content{
		padding: 79px 0 0 0;
	}

	</style>

	<?php $this->load->view('templates/header_js.php'); ?>

</head>

<div id="load-screen"></div>

<!-- loading bar START -->
<div class="loading-bar-div">
<div class="load-screen-bg"></div>
<div class="ldBar label-center jlbar ldBar_center" data-preset="circle"></div>
</div>
<!-- loading bar END -->

<body class="with-side-menu theme-picton-blue <?php echo ( $this->system_model->getStaffClassID() == 6 )?'sidebar-hidden':null; ?>">

<div id="preloader">
	<!-- <div id="status">&nbsp;</div> -->
	<div id="circularG">
		<div id="circularG_1" class="circularG"></div>
		<div id="circularG_2" class="circularG"></div>
		<div id="circularG_3" class="circularG"></div>
		<div id="circularG_4" class="circularG"></div>
		<div id="circularG_5" class="circularG"></div>
		<div id="circularG_6" class="circularG"></div>
		<div id="circularG_7" class="circularG"></div>
		<div id="circularG_8" class="circularG"></div>
	</div>
</div>

<?php
 $this->load->view('notifications/pusher_imp.php');

 // get logged user staff class
 $logged_user_class_id = $this->system_model->getStaffClassID();

 if( $logged_user_class_id == 6 ){ // tech
	$sats_logo_redirect = "/home/index";
 }else{
	$sats_logo_redirect = '/jobs';
 }
?>

	<header class="site-header">
	    <div class="container-fluid">
	        <a href="<?php echo $sats_logo_redirect; ?>" class="site-logo">
				<img src="<?= theme('/images/tech_logo.png') ?>" alt="<?=config_item('company_name_short');?>" class="tech_logo">
			</a>

	        <div class="site-header-content">
	            <div class="site-header-content-in">

                <div class="site-header-shown">
                    <a href="/sys/logout">
                        <div class="top_profile_name" >
                            <i class="fa fa-sign-out"></i>
                            <span>Logout</span>
                        </div>
                    </a>
                </div>



                    <?php
                    // get logged user
                    $params = array(
                        'sel_query' => '
                            sa.`StaffID`,
                            sa.`FirstName`,
                            sa.`LastName`
                        ',
                        'staff_id' => $this->session->staff_id,
                        'active' => 1,
                        'deleted' => 0,
                        'display_query' => 0
                    );

                    // get user details
                    $user_account_sql = $this->staff_accounts_model->get_staff_accounts($params);
                    $user_account = $user_account_sql->row();
                    ?>
                    <div class="user_full_name"><?php echo "{$user_account->FirstName} {$user_account->LastName}"; ?></div>








	            </div><!--site-header-content-in-->
	        </div><!--.site-header-content-->
	    </div><!--.container-fluid-->
	</header><!--.site-header-->






<!-- MAIN LEFT MENU START HERE  -->
<?php $this->load->view('templates/main_menu'); ?>
<!-- MAIN LEFT MENU END HERE -->





	<div class="page-content">
	    <div class="container-fluid">
