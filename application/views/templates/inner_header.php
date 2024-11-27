<?php
$data = $this->templatedatahandler->getData();
extract($data);
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <?php $this->load->view('templates/tracking'); ?>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo $title; ?></title>
    <link rel="preconnect" href="https://stats.pusher.com" />
    <link rel="icon" type="image/png" href="<?= theme('favicon.png') ?>" />

    <link rel="stylesheet" href="/inc/css/lib/bootstrap-4.6.2/bootstrap.min.css">

	<link rel="stylesheet" href="/inc/css/lib/lobipanel/lobipanel.min.css">
	<link rel="stylesheet" href="/inc/css/separate/vendor/lobipanel.min.css">
	<link rel="stylesheet" href="/inc/css/lib/jqueryui/jquery-ui.min.css">
	<link rel="stylesheet" href="/inc/css/separate/pages/widgets.min.css">

	<link rel="stylesheet" href="/inc/css/lib/bootstrap-sweetalert/sweetalert.css">
	<link rel="stylesheet" href="/inc/fancybox-3.4.2/dist/jquery.fancybox.min.css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.dataTables.min.css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css" />
    <link rel="stylesheet" href="/inc/flatpickr-4.6.13/flatpickr.min.css">
    <link rel="stylesheet" href="/inc/css/lib/font-awesome/font-awesome.min.css">

	<link rel="stylesheet" href="/inc/css/main.css">
	<link rel="stylesheet" href="/inc/css/custom.css">
	<link rel="stylesheet" href="/inc/loading-bar/loading-bar.css"/>
	<link rel="stylesheet" href="<?= theme('styles.css') ?>">

	<!-- JS START -->
	<script type="text/javascript" src="/inc/js/lib/jquery-3.5.1/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="/inc/js/lib/bootstrap-4.6.2/bootstrap.bundle.min.js"></script>
	<script type="text/javascript" src="/inc/js/plugins.js"></script>

	<script type="text/javascript" src="/inc/flatpickr-4.6.13/flatpickr.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/jqueryui/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/lobipanel/lobipanel.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/match-height/jquery.matchHeight.min.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript" src="/inc/js/lib/bootstrap-sweetalert/sweetalert.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/html5-form-validation/jquery.validation.min.js"></script>
	<script type="text/javascript" src="/inc/fancybox-3.4.2/dist/jquery.fancybox.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/bootstrap-select/bootstrap-select.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/select2/select2.full.min.js"></script>
	<script type="text/javascript" src="/inc/ion_sound/ion.sound.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/pusher/pusher.min.js"></script>
	<script type="text/javascript" src="/inc/js/lib/jquery-idle-master/jquery.idle.js"></script>
	<script type="text/javascript" src="/inc/js/custom.js"></script>
	<script type="text/javascript" src="/inc/js/my-custom.js"></script>
	<script type="text/javascript" src="/inc/js-cookie/js.cookie.js"></script>
	<script type="text/javascript" src="/inc/loading-bar/loading-bar.js"></script>
	<script type="text/javascript" src="/inc/js/jquery.tablednd_0_5.js"></script>
	<script type="text/javascript" src="/inc/js/jsignature/jSignature.min.js"></script>
	<script type="text/javascript" src="/inc/js/input_mask/dist/jquery.inputmask.js"></script>
	<script type="text/javascript" src="/inc/moment-2.30.1/moment.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/searchpanes/2.2.0/js/dataTables.searchPanes.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>

	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

    <link rel="stylesheet" href="/inc/css/custom-datatables-searchpanes.css"/>
    <script type="module" src="../../../inc/js/app/CustomDatatablesSearchPanes.js"></script>
    <script type="module" src="../../../inc/js/app/PageOptions.js"></script>
    <script type="module" src="../../../inc/js/app/ExportPagesFunctions.js"></script>
    <script type="module" src="../../../inc/js/app/DatatableConfigOptions.js"></script>

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
		float: left;
		margin-right: 19px;
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
	.flags img {
		width: 34px;
		margin-right: 5px;
	}
	.theme-picton-blue .site-header .header-alarm.active::after {
		border-color: red;
    	background-color: red;
	}
	.homepage_setting_box{
		float: left;
		margin-left: 6px;
		margin-top: 6px;
		position: relative;
	}
	.homepage_setting_box a{
		color:#fff;
	}

    #logviewer-wrapper {
        float: left;
        margin-right: 19px;
        color: #fff;
        padding: 7px;
        font-size: 15px;
        font-weight:600;
    }
    #logviewer-wrapper a {
        color:#FFF;
        text-decoration:none;
    }
    #logviewer-wrapper a:hover {
        color:#FFF;
        text-decoration:none;
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

<body class="with-side-menu theme-picton-blue <?php echo ( $loggedInUser->ClassID == 6 )?'sidebar-hidden':null; ?>">

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
// Only load pusher on production sites
if(ENVIRONMENT == 'production'){
    $this->load->view('notifications/pusher_imp.php');
}

// get logged user staff class
$logged_user_class_id = $loggedInUser->ClassID;

if ($logged_user_class_id == 6) { // tech
    $sats_logo_redirect = "/home/index";
} else {
    $sats_logo_redirect = '/home';
}
?>

	<header class="site-header">
	    <div class="container-fluid">
	        <a href="<?php echo $sats_logo_redirect; ?>" class="site-logo">
				<img src="<?= theme('images/logo.png') ?>" alt="logo" >
			</a>
			<?php
			$staff_class = $loggedInUser->ClassID;

			if($staff_class!=6){ //display menu button/toggle if not tech
			?>
			<button id="show-hide-sidebar-toggle" class="show-hide-sidebar">
	            <span>toggle menu</span>
			</button>
			<?php } ?>

	        <button class="hamburger hamburger--htla">
	            <span>toggle menu</span>
	        </button>
	        <div class="site-header-content">
	            <div class="site-header-content-in">


	                <div class="site-header-shown">
                        <?php if(in_array($loggedInUser->ClassID, [2,11])): ?>
                        <div id="logviewer-wrapper" >
                            <a href="/logs/" target="_blank"><span class="fa fa-bug"></span> LogViewer</a>
                        </div>
                        <?php endif; ?>
						<div class="top_profile_name" >
							<span class="font-icon font-icon-user logged_user_span"></span> <span><?php echo "{$loggedInUser->FirstName} {$loggedInUser->LastName}"; ?></span>
						</div>

						<div class="dropdown dropdown-notification notif">
	                        <a href="javascript:void(0);" class="header-alarm">
	                            <i class="fa fa-search" id="search_icon_fb"></i>
	                        </a>
	                    </div>

						<?php
						// get general notification
						$jparams = array(
							'notf_type' => 1,
							'notify_to' => $this->session->staff_id,
							'read' => 0,
							'return_count' => 1
						);
						$unread_notif_count = $this->system_model->getOverallNotification($jparams);
						?>
	                    <div class="dropdown dropdown-notification notif gen_notif_main_div">
	                        <a href="#"
	                           class="header-alarm general-notif <?php echo ( $unread_notif_count > 0 )?'dropdown-toggle active':null; ?>"
	                           id="dd-notification"
	                           data-toggle="dropdown"
	                           aria-haspopup="true"
	                           aria-expanded="false">
	                            <i class="font-icon-alarm"></i>
	                        </a>
	                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-notif" aria-labelledby="dd-notification">
	                            <div class="dropdown-menu-notif-header">
	                                Notifications
    								<span class="label label-pill label-danger" id="notif_count"></span>
	                            </div>
	                            <div class="dropdown-menu-notif-list" id="notication_div">
                            		<!-- general notification appended here -->
									<div class="main_notf_div">
										<?php
										$jparams = array(
											'notf_type' => 1,
											'notify_to' => $this->session->staff_id,
											'sort_list' => array(
												array(
													'order_by' => 'n.`date_created`',
													'sort' => 'DESC'
												)
											),
											'paginate' => array(
												'offset' => 0,
												'limit' => 15
											)
										);
										$n_sql = $this->system_model->getOverallNotification($jparams);
										$n_num = count($n_sql);

										if( $n_num >0 ){
										?>
										<div class="notification_box" data-notf_type="<?php echo $notf_data['notf_type']; ?>">
											<ul>
												<?php
												foreach ($n_sql as $n) {
													$sms_notf_msg = $n['notification_message'];
													  if ($n['read']==0 ) {
												?>
							                        <div class="dropdown-menu-notif-item new_notification" data-id="<?=$n['notifications_id']?>">
							                            <div class="photo">
							                                <img src="/images/avatar-2-64.png" alt="">
							                            </div>
							                            <?php
							                            //$changeRedi = str_replace('href="', 'href="'.$this->config->item("crm_link").'/', $sms_notf_msg);

														if (strpos($sms_notf_msg, '.php') !== false) { // old crm
															$changeRedi = str_replace('href="', 'href="'.$this->config->item("crm_link").'/', $sms_notf_msg);
														}else{ // CI
															$changeRedi = str_replace('href="', 'href="'.$this->config->item("crmci_link").'/', $sms_notf_msg);
														}

														echo $changeRedi;
							                            ?>
							                        </div>
												<?php } else { ?>
							                        <div class="dropdown-menu-notif-item"  style="background-color: #f2f2f2;" data-id="<?=$n['notifications_id']?>">
							                            <div class="photo">
							                                <img src="/images/avatar-2-64.png" alt="">
							                            </div>
							                            <?php
							                            //$changeRedi = str_replace('href="', 'href="'.$this->config->item("crm_link").'/', $sms_notf_msg);

														if (strpos($sms_notf_msg, '.php') !== false) { // old crm
															$changeRedi = str_replace('href="', 'href="'.$this->config->item("crm_link").'/', $sms_notf_msg);
														}else{ // CI
															$changeRedi = str_replace('href="', 'href="'.$this->config->item("crmci_link").'/', $sms_notf_msg);
														}

														echo $changeRedi;
							                            ?>
							                        </div>
												<?php } ?>
												<?php
												}
												?>
											</ul>
										</div>
										<?php
										} else { ?>
											<!-- NOTIFICATION BOX -->
											<div class="notification_box" data-notf_type="<?php echo $notf_data['notf_type']; ?>">
												<ul>
								                    <div class="dropdown-menu-notif-item"  style="background-color: #f2f2f2;" data-id="<?=$n['notifications_id']?>">
								                       <center>No Notifications</center>
								                    </div>
												</ul>
											</div>
										<?php
										}
										?>
									</div>
	                            </div>
	                            <div class="dropdown-menu-notif-more">
	                                <!-- <a href="#">See more</a> -->
	                            </div>
	                        </div>
	                    </div>

						<?php
						// get SMS notification
						$jparams = array(
							'notf_type' => 2,
							'notify_to' => $this->session->staff_id,
							'read' => 0,
							'return_count' => 1
						);
						$unread_notif_count = $this->system_model->getOverallNotification($jparams);
						?>
	                    <div class="dropdown dropdown-notification notif sms_notif_main_div">
	                        <a href="#"
	                           class="header-alarm sms-notif <?php echo ( $unread_notif_count > 0 )?'dropdown-toggle active':null; ?>"
	                           id="dd-messages"
	                           data-toggle="dropdown"
	                           aria-haspopup="true"
	                           aria-expanded="false">
	                            <i class="font-icon-comments"></i>
	                        </a>
	                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-notif" aria-labelledby="dd-messages">
	                            <div class="dropdown-menu-notif-header">
	                                SMS Notifications
    								<span class="label label-pill label-danger" id="notif_sms_count"></span>
	                            </div>
	                            <div class="dropdown-menu-notif-list" id="notication_sms_div">
                            		<!-- general notification appended here -->
									<div class="main_notf_div">
										<?php
										$jparams = array(
											'notf_type' => 2,
											'notify_to' => $this->session->staff_id,
											'sort_list' => array(
												array(
													'order_by' => 'n.`date_created`',
													'sort' => 'DESC'
												)
											),
											'paginate' => array(
												'offset' => 0,
												'limit' => 15
											)
										);
										$n_sql = $this->system_model->getOverallNotification($jparams);
										$n_num = count($n_sql);

										if( $n_num >0 ){
										?>
										<div class="notification_box" data-notf_type="<?php echo $notf_data['notf_type']; ?>">
											<ul>
												<?php
												foreach ($n_sql as $n) {
													$sms_notf_msg = $n['notification_message'];
													  if ($n['read']==0 ) {
												?>
							                        <div class="dropdown-menu-notif-item new_notification" data-id="<?=$n['notifications_id']?>">
							                            <div class="photo">
							                                <img src="/images/avatar-2-64.png" alt="">
							                            </div>
							                            <?php
							                            //$changeRedi = str_replace('href="', 'href="'.$this->config->item("crm_link").'/', $sms_notf_msg);

														if (strpos($sms_notf_msg, '.php') !== false) { // old crm
															$changeRedi = str_replace('href="', 'href="'.$this->config->item("crm_link").'/', $sms_notf_msg);
														}else{ // CI
															$changeRedi = str_replace('href="', 'href="'.$this->config->item("crmci_link").'/', $sms_notf_msg);
														}

														echo $changeRedi;
							                            ?>
							                        </div>
												<?php } else { ?>
							                        <div class="dropdown-menu-notif-item"  style="background-color: #f2f2f2;" data-id="<?=$n['notifications_id']?>">
							                            <div class="photo">
							                                <img src="/images/avatar-2-64.png" alt="">
							                            </div>
							                            <?php
							                            //$changeRedi = str_replace('href="', 'href="'.$this->config->item("crm_link").'/', $sms_notf_msg);

														if (strpos($sms_notf_msg, '.php') !== false) { // old crm
															$changeRedi = str_replace('href="', 'href="'.$this->config->item("crm_link").'/', $sms_notf_msg);
														}else{ // CI
															$changeRedi = str_replace('href="', 'href="'.$this->config->item("crmci_link").'/', $sms_notf_msg);
														}

														echo $changeRedi;
							                            ?>
							                        </div>
												<?php } ?>
												<?php
												}
												?>
											</ul>
										</div>
										<?php
										} else { ?>
											<!-- NOTIFICATION BOX -->
											<div class="notification_box" data-notf_type="<?php echo $notf_data['notf_type']; ?>">
												<ul>
								                    <div class="dropdown-menu-notif-item"  style="background-color: #f2f2f2;" data-id="<?=$n['notifications_id']?>">
								                       <center>No Notifications</center>
								                    </div>
												</ul>
											</div>
										<?php
										}
										?>
									</div>
	                            </div>
	                            <div class="dropdown-menu-notif-more">
	                                <!-- <a href="#">See more</a> -->
	                            </div>
	                        </div>
	                    </div>
						
						<div class="homepage_setting_box"><a href="/home/homepage_settings"><span class="fa fa-gear"></span></a></div>
	                </div>
	                <div class="mobile-menu-right-overlay"></div>
	                <div class="site-header-collapsed jtopheader_left">
						<div class="flags">
							<?php
							$loggedInCountryAccess = $loggedInCountryAccess;
							$countryAccessWithDefaultValues = [
								0 => [
									"country_id" => 1,
									"default" => $this->config->item("country") == 1,
									"status" => $this->config->item("country") == 1,
								],
								1 => [
									"country_id" => 2,
									"default" => $this->config->item("country") == 2,
									"status" => $this->config->item("country") == 2,
								],
							];
							$countryAccessToUse = [];
							$countryIds = array_column($loggedInCountryAccess, "country_id");
							foreach($countryAccessWithDefaultValues as &$ca1) {
								$key = array_search($ca1["country_id"], $countryIds);
								if ($key != false) {
									$countryAccessToUse[] = $loggedInCountryAccess[$key];
								}
								else {
									$countryAccessToUse[] = $ca1;
								}
							}
							?>

							<?php if($this->config->item('theme') != 'sas') : ?>
                                <?php
                                switch($this->config->item('country')){
                                    case 1:
                                        $tooltip = 'Switch to NZ';
                                        $image = 'nz';
                                        $ccTLD = '.co.nz';
                                        break;
                                    case 2:
                                        $tooltip = 'Switch to AUS';
                                        $image = 'au';
                                        $ccTLD = '.com.au';
                                        break;
                                }
                                // take current url and replace the end with the opposite
                                $search = ['.com.au/', '.co.nz/'];
                                $replace = $ccTLD;
                                $link = str_replace($search, $replace, base_url());
                                ?>
								<!-- AU -->
								<a href="<?=$link;?>" target="_blank">
									<img src="/images/flags/<?=$image;?>.png" data-toggle="tooltip" title="<?=$tooltip;?>" />
								</a>
							<?php endif; ?>
						</div>
	                </div><!--.site-header-collapsed-->
	            </div><!--site-header-content-in-->
	        </div><!--.site-header-content-->
	    </div><!--.container-fluid-->
	</header><!--.site-header-->


<!-- MAIN LEFT MENU START HERE  -->
<?php $this->load->view('templates/main_menu'); ?>
<!-- MAIN LEFT MENU END HERE -->

	<div class="page-content">
	    <div class="container-fluid">
