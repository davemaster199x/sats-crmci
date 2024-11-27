<style>
#bot_func_btn_div,
#bot_func_highlight_row,
#bot_func_hide,
#bot_func_assign_dk,
#bot_func_en,
#bot_func_escalate,
#bot_func_change_tech,
#bot_func_mark_tech_sick,
#bot_func_remove_keys,
#bot_func_remove_suppliers,
.select_job_type_class,
.select_agency_jobs_class,
#add_key_div,
#add_supplier_div,
#region_filter_div,
#region_filter_div .state_div_chk,
#region_filter_div .region_div_chk,
#region_filter_div .sub_region_div_chk,
.EN_show_elem,
.a_address,
.time_of_day_hid,
#tech_run_functions option.show_for_jobs,
#tech_run_functions option.show_for_keys,
#tech_run_functions option.show_for_supplier,
#maximize_panel,
.time_of_day_save_icon,
#display_jt_div{
	display: none;
}

.time_of_day_save_icon{
	color: green;
	font-size: 19px;
}

/* region filters - start */
#region_filter_div{
	padding: 1px 10px 1px 6px;
	position: absolute;
	top: 60px;
	display: none;
	z-index: 10;
	min-width: 129px;
	width: -moz-max-content;
}
#region_filter_div .state_div_chk {
	margin: 4px 0;
}
#region_filter_div .region_div {
	margin: 13px 0 0 24px;
}
#region_filter_div .sub_region_div_chk {
	margin: 13px 0 0 26px;
}
#region_filter_div .rf_select{
	font-weight: bold;
}
/* region filters - end */
#select_table_section .chk_col{
	width: 35px;
}
#run_status_tbl button.btn{
	width: 100%;
}
#bot_func_btn_div{
	position: fixed;
	top: 50%;
	left: 50px;
    z-index: 999;
}
#bot_func_btn_div.minimise{
    position: fixed;
    bottom: 50px;
    top: unset;
    left: unset;
    right: 50px;
    opacity: 25%;
}
.details_icon{
	font-size: 24px;
}
.span_circle {
  height: 25px;
  width: 25px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
}
#notes_timestamp_div {
  color: #00D1E5;
}
.hiddenJobs{
	background-color: #add8e6 !important;
    border: 1px solid #006df0;
}
#minimize_panel,
#maximize_panel{
	cursor:pointer;
}
#display_multiselect {
	width: 180px;
	padding: 15px 1px 1px 7px;
}
.booked_icon{
	width: 20px;
}
.redCross{
	font-size: 30px;
}
.j_icons {
  margin-right: 0px;
}
.sub_region_tag {
  display: inline-block;
  padding: 0px 4px;
  border-radius: 8px;
  margin-top: 2px;
  margin-bottom: 2px;
  margin-right: 2px;
  background-color: #5dca73;
  color: white;
  font-size: 12pt;
}
#booking_notes_div{
	background-color: red !important;	
	border-bottom-color: red !important;
}
#booking_notes_section{
	border-color: red !important;
}
#booking_notes_div a{
	color: wheat;
}
.tech_run_tbl{
	border-left: solid 1px #d8e2e7 !important;
	border-right: solid 1px #d8e2e7 !important;
	border-bottom: solid 1px #d8e2e7 !important;	
}
.card-block{
	padding: 5px;
}
.card{
	margin-bottom: 15px;
}
.flatpickr {
  width: 100%;
}
.booking_goals_count_td{
	font-weight: bold;
}
.page_key_completed{
	background-color: #c2ffa7;
}
.page_key_completed{
	background-color: #c2ffa7;
}
.page_key_completed{
	background-color: #c2ffa7;
}
.page_key_utc{
	background-color: #fffca3;
}
.page_key_error{
	background-color: pink;
}
.page_key_tbs{
	background-color: #ffff00;
}
.page_key_hidden_jobs{
	background-color: #add8e6;
}
#page_key_card_block{
	padding: 12px 0;
}
#page_key_card_block span{
	margin: 0 5px;
	padding: 1px 6px;
}
.agency_no_key_access_allowed{
	font-size: 26px;
}


#tbl_maps .sorting_asc,
#tbl_maps .sorting_desc{
	color: #0082c6
}

/* update sort icon */  
table.dataTable thead th.sorting:after,
table.dataTable thead th.sorting_asc:after,
table.dataTable thead th.sorting_desc:after {
    font-family: FontAwesome !important;
}

/* default unsorted icon */
table.dataTable thead th.sorting:after {
    content: "\f0dc" !important;
}

/* ascending and descending sort */
table.dataTable thead th.sorting_asc:after,
table.dataTable thead th.sorting_desc:after{
	opacity: 1;
}

/* ascending sort */
table.dataTable thead th.sorting_asc:after {
    content: "\f0de" !important;	
}

/* descending sort */
table.dataTable thead th.sorting_desc:after {
    content: "\f0dd" !important;
}
.grey_row{
	background-color: #eeeeee;
}
#job_priority_ol{
	margin-left: 20px;
}
#job_priority_ol li{
	margin: 5px 0;
}

/*
table.dataTable th,
table.dataTable td {
    font-size: 12px;
}
*/

.dataTable tr:nth-child(even) {
    background-color: #fcfcfc;
}
#tbl_maps_parent_div{
	overflow-x: scroll;
	overflow-y: hidden;
}

/* time input  column */
.en_time {
    padding: 0 !important;

    min-width: 70px;
    font-size: 13px;
    text-align: center;
}
<?php
// for SAS only
if( $_ENV['THEME'] == 'sas' ){ ?>
    .btn.btn-primary-outline:hover, .btn.btn-primary-outline:focus:hover {
        background-color: #00607f !important;
    }
    .btn.btn-default-outline:hover, .btn.btn-primary-outline:hover, .btn.btn-secondary-outline:hover, .btn.btn-success-outline:hover, .btn.btn-info-outline:hover, .btn.btn-warning-outline:hover, .btn.btn-danger-outline:hover {
        color: #fff !important;
    }
    .btn.btn-primary-outline, .btn.btn-primary-outline:focus {
        color: #00607f !important;
        border-color: #00607f !important;
    }
    .btn.btn-default-outline, .btn.btn-primary-outline, .btn.btn-secondary-outline, .btn.btn-success-outline, .btn.btn-info-outline, .btn.btn-warning-outline, .btn.btn-danger-outline {
        background-color: #fff !important; 
    }
<?php
}
 ?>
</style>
<div class="box-typical box-typical-padding">

	<?php 
	// breadcrumbs template
	$bc_items = array(
		array(
			'title' => $title,
			'status' => 'active',
			'link' => $uri."/?tr_id={$tr_id}"
		)
	);
	$bc_data['bc_items'] = $bc_items;
	$this->load->view('templates/breadcrumbs', $bc_data);
	?>


	<section class="tabs-section">
		
		<div class="tabs-section-nav tabs-section-nav-icons">
			<div class="tbl">
				<ul class="nav j_remember_tab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="setup_tab" href="#nav_setup" role="tab" data-toggle="tab">
							<span class="nav-link-in">
								<i class="fa fa-wrench text-red"></i>								
								Setup
							</span>
						</a>
					</li>

					<?php
					// show only if tech run exist
					if( $has_tech_run == true ){ ?>
						<li class="nav-item">
							<a class="nav-link" id="details_tab" href="#nav_details" role="tab" data-toggle="tab">
								<span class="nav-link-in">
									<i class="fa fa-info-circle text-orange"></i>								
									Details
								</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="functions_tab" href="#nave_functions" role="tab" data-toggle="tab">
								<span class="nav-link-in">
									<i class="fa fa-gears text-green"></i>							
									Functions
								</span>
							</a>
						</li>
					<?php
					}
					?>					
				</ul>
			</div>
		</div><!--.tabs-section-nav-->

		
		<div class="tab-content">

			<!-- SETUP CONTENT -->
			<div role="tabpanel" class="tab-pane fade active show" id="nav_setup">			        				

				<form id="jform" action="/tech_run/create_or_update" method="POST">

					<div class="row">

						<!-- 1st column -->
						<div class="col">
							<section class="card card-blue-fill">
								<header class="card-header">1. Run Details</header>
								<div class="card-block">
									<table class="table table-borderless colour_tbl">
										<tr>
											<th>Date</th>
											<td>
												<input 
													name="date" 
													id="date" 
													class="form-control flatpickr" 
													data-allow-input="true" type="text" 
													value="<?php echo ( $this->system_model->isDateNotEmpty($tech_run_row->date) )?date('d/m/Y',strtotime($tech_run_row->date)):null; ?>" 
												/>	
												<input type="hidden" id="orig_date" value="<?php echo ( $this->system_model->isDateNotEmpty($tech_run_row->date) )?date('d/m/Y',strtotime($tech_run_row->date)):null; ?>"> 
											</td>
										</tr>
										<tr>
											<th>Technician</th>
											<td>
												<select name="assigned_tech" id="assigned_tech" class="form-control">
													<option value="">---</option>
													<?php
													foreach( $tech_sql->result() as $tech_row ){ ?>

														<option value="<?php echo $tech_row->StaffID; ?>" <?php echo ( $tech_row->StaffID == $tech_run_row->assigned_tech )?'selected':null; ?>>
															<?php echo $this->system_model->formatStaffName($tech_row->FirstName,$tech_row->LastName).( ( $tech_row->is_electrician == 1 )?' [E]':null ); ?>
														</option>

													<?php
													}
													?>
												</select>
												<input type="hidden" id="orig_assigned_tech" value="<?=$tech_run_row->assigned_tech?>">
											</td>
										</tr>
										<tr>
											<th>Working Hours</th>
											<td><input type="text" class="form-control working_hours" id="working_hours" name="working_hours" value="<?php echo $tech_run_row->working_hours; ?>" /></td>
										</tr>
										<tr>
											<th>Booking Staff</th>
											<td>
												<select name="booking_staff" id="booking_staff" class="form-control">
													<option value="">---</option>
													<?php
													foreach( $booking_staff_sql->result() as $booking_staff_row ){ ?>
														<option value="<?php echo $booking_staff_row->StaffID ?>" <?php echo ( $booking_staff_row->StaffID == $cal_row->booking_staff )?'selected="selected"':'' ?>>
															<?php echo $this->system_model->formatStaffName($booking_staff_row->FirstName,$booking_staff_row->LastName); ?>
														</option>
													<?php
													}
													?>
												</select>
											</td>	
										</tr>
										<tr>
											<th>Display on Calendar</th>	
											<td>
												<input name="calendar" id="calendar" class="form-control calendar" type="text" value="<?php echo $cal_row->region; ?>" />								
												<input type="hidden" name="calendar_id" id="calendar_id" value="<?php echo $cal_row->calendar_id; ?>" />	
											</td>
										</tr>							
									</table>
								</div>
							</section>
						</div>

						<!-- 2nd column -->
						<div class="col">
							<section class="card card-blue-fill">
								<header class="card-header">2. Route</header>
								<div class="card-block">
									<table class="table table-borderless colour_tbl">
										<tr>
											<th>Starting Point</th>
											<td>
												<select name="start_point" id="start_point" class="form-control">
													<option value="">---</option>
													<?php									
													foreach( $acco_sql->result() as $acco_row ){ ?>
														<option 
															value="<?php echo $acco_row->accomodation_id; ?>" 
															<?php echo ( $acco_row->accomodation_id == $tech_run_row->start )?'selected':null; ?>
														>
															<?php echo $acco_row->name; ?>
														</option>
													<?php
													}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<th>Ending Point</th>
											<td>
												<select name="end_point" id="end_point" class="form-control">
													<option value="">---</option>
													<?php									
													foreach( $acco_sql->result() as $acco_row ){ ?>
														<option value="<?php echo $acco_row->accomodation_id; ?>" <?php echo ( $acco_row->accomodation_id == $tech_run_row->end )?'selected':null; ?>>
															<?php echo $acco_row->name; ?>
														</option>
													<?php
													}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<th>Accommodation</th>
											<td>
												<select name="accomodation" id="accomodation" class="form-control">
													<option value="">None</option>
													<option value="0" <?php echo ( is_numeric($cal_row->accomodation) && $cal_row->accomodation == 0 )?'selected':null; ?>>Required</option>
													<option value="2" <?php echo ( $cal_row->accomodation == 2 )?'selected':null; ?>>Pending</option>
													<option value="1" <?php echo ( $cal_row->accomodation == 1 )?'selected':null; ?>>Booked</option>
												</select>

												<div id="sel_acco" class="mt-3" style="display:<?php echo ( $cal_row->accomodation == 1 || $cal_row->accomodation == 2 )?'block':'none'; ?>;">
													<select name="accomodation_id" id="accomodation_id" class="form-control">
														<option value="">---</option>
														<?php									
														foreach( $acco_sql->result() as $acco_row ){ ?>
															<option value="<?php echo $acco_row->accomodation_id; ?>" <?php echo ( $acco_row->accomodation_id == $cal_row->accomodation_id )?'selected':null; ?>><?php echo $acco_row->name; ?></option>
														<?php
														}
														?>
													</select>
												</div>
											</td>
										</tr>
										<?php
										if( $has_tech_run == true ){ ?>
											<tr>	
												<th>Stops Required</th>	
												<td>
														<div class="row">
															<div class="col">
																<button type="button" class="btn btn-success float-left" id="add_key_btn">Add Keys</button>
																<button type="button" class="btn btn-success float-right" id="add_supplier_btn">Add Supplier</button>
															</div>
														</div>

														<div class="row">

															<div class="col" id="add_key_div">
													
																<!-- ADD KEY hidden div -->
																<select id="keys_agency" class="form-control mt-2">
																	<option value="">---</option>	
																	<?php
																	foreach( $sel_agency_jobs_sql->result() as $sel_agency_jobs_row ){ 												
																		if( $sel_agency_jobs_row->agency_id > 0 ){
																			
																			// COPIED FROM OLD STR
																			// display key address for agency that has it
																			$agency_add_sql = $this->db->query("
																			SELECT 
																				a.`agency_name`,
																				a.`agency_id`,
																				agen_add.`id` AS agen_add_id,
																				agen_add.`address_1` AS agen_add_street_num, 
																				agen_add.`address_2` AS agen_add_street_name, 
																				agen_add.`address_3` AS agen_add_suburb, 
																				agen_add.`state` AS agen_add_state, 
																				agen_add.`postcode` AS agen_add_postcode			
																			FROM `agency_addresses` AS agen_add
																			LEFT JOIN `agency` AS a ON agen_add.`agency_id` = a.`agency_id`
																			WHERE agen_add.`agency_id` = {$sel_agency_jobs_row->agency_id}
																			AND agen_add.`type` = 2
																			");
																			$key_add_num = 1;

																			$check_address_str= "SELECT `agency_addresses`.`id`, a.`agency_id`, a.`agency_name`, agency_addresses.`address_1` AS agen_add_street_num, agency_addresses.`address_2` AS agen_add_street_name, agency_addresses.`address_3` AS agen_add_suburb  FROM `agency_addresses` JOIN `property_keys` ON `agency_addresses`.`id`=`property_keys`.`agency_addresses_id` JOIN `agency` AS a ON agency_addresses.`agency_id` = a.`agency_id` JOIN `jobs` ON `property_keys`.`property_id` = `jobs`.`property_id` WHERE agency_addresses.`agency_id`={$sel_agency_jobs_row->agency_id} AND agency_addresses.`type`=2 AND jobs.`date`=CURDATE() AND jobs.`status`='Booked' GROUP BY agency_addresses.`id`";
																			$check_address_sql = $this->db->query($check_address_str);														

																			//Count Key Address
																			$count_address_str= "SELECT `agency_addresses`.`id` FROM `agency_addresses` JOIN `property_keys` ON `agency_addresses`.`id`=`property_keys`.`agency_addresses_id` JOIN `agency` AS a ON agency_addresses.`agency_id` = a.`agency_id` JOIN `jobs` ON `property_keys`.`property_id` = `jobs`.`property_id` WHERE agency_addresses.`agency_id`={$sel_agency_jobs_row->agency_id} AND agency_addresses.`type`=2 AND jobs.`date`=CURDATE() AND jobs.`status`='Booked' GROUP BY agency_addresses.`id`";
																			$count_address_sql = $this->db->query($count_address_str);
																			$count_address = $count_address_sql->num_rows();
																			$count_check_address = $check_address_sql->num_rows();

																			if( $check_address_sql->num_rows() > 0 && $count_address == $count_check_address ){

																				foreach( $check_address_sql->result() as $check_address_row ){
																					$agen_add_comb = "{$check_address_row->agen_add_street_num} {$check_address_row->agen_add_street_name}, {$check_address_row->agen_add_suburb}"; ?>
																						<option value="<?php echo $check_address_row->agency_id; ?>" data-agency_addresses_id="<?php echo $check_address_row->id; ?>"><?php echo "{$check_address_row->agency_name} Key #{$key_add_num} {$agen_add_comb}"; ?></option>
																					<?php
																					$key_add_num++;
																				}

																			}else{ ?>

																				<option value="<?php echo $sel_agency_jobs_row->agency_id; ?>"><?php echo $sel_agency_jobs_row->agency_name; ?></option>

																				<?php
																				// First National added list
																				if( $sel_agency_jobs_row->agency_id == $fn_agency_main ){

																					$fn_agency_sub_sql_str = "
																						SELECT `agency_id`, `agency_name`
																						FROM `agency`
																						WHERE `agency_id` IN({$fn_agency_sub_imp})
																					";
																					$fn_agency_sub_sql = $this->db->query($fn_agency_sub_sql_str);
																					foreach( $fn_agency_sub_sql->result() as $fn_agency_sub_row ){ ?>
																						<option value="<?php echo $fn_agency_sub_row->agency_id; ?>"><?php echo $fn_agency_sub_row->agency_name; ?></option>
																					<?php
																					}
																				}

																				// // Vision Real Estate added list
																				if( $sel_agency_jobs_row->agency_id == $vision_agency_main ){

																					$vision_agency_sub_sql_str = "
																						SELECT `agency_id`, `agency_name`
																						FROM `agency`
																						WHERE `agency_id` IN({$vision_agency_sub_imp})
																					";
																					$vision_agency_sub_sql = $this->db->query($vision_agency_sub_sql_str);
																					foreach( $vision_agency_sub_sql->result() as $vision_agency_sub_row ){ ?>
																						<option value="<?php echo $vision_agency_sub_row->agency_id; ?>"><?php echo $vision_agency_sub_row->agency_name; ?></option>
																					<?php
																					}
																				}

																				if( $agency_add_sql->num_rows() > 0 ){

																					$key_add_num = 1;
																					foreach( $agency_add_sql->result() as $agency_add_row ){
																					
																					// get agency address from `agency_addresses` table
																					$agency_add_str = "{$agency_add_row->agen_add_street_num} {$agency_add_row->agen_add_street_name}, {$agency_add_row->agen_add_suburb}"; 
																					?>
																						<option value="<?php echo $sel_agency_jobs_row->agency_id; ?>" data-agency_addresses_id="<?php echo $agency_add_row->agen_add_id; ?>"><?php echo "{$sel_agency_jobs_row->agency_name} Key #{$key_add_num} {$agency_add_str}"; ?></option>
																					<?php
																					$key_add_num++;
																					}

																				}

																			}																												

																		}
																	}
																	?>																									
																</select>	

																<button type="button" class="btn btn-primary mt-2 float-right" id="add_key_submit_btn">Submit</button>
														
															</div>

															<div class="col" id="add_supplier_div">

																<select name="supplier" id="supplier" class="form-control mt-2">
																	<option value="">---</option>
																	<?php													
																	foreach( $supp_sql->result() as $supp_row ){														
																	?>
																		<option value="<?php echo $supp_row->suppliers_id;  ?>"><?php echo $supp_row->company_name;  ?></option>
																	<?php														
																	}
																	?>
																</select>
																<button type="button" class="btn btn-primary mt-2 float-right" id="add_supplier_submit_btn">Submit</button>

															</div>

														</div>																																																	
														
													
												</td>
											</tr>	
										<?php
										}
										?>									
									</table>
								</div>
							</section>
						</div>

						<!-- column 3 -->
						<div class="col">

							<div class="row">
								<div class="col">
									<section class="card card-blue-fill">
										<header class="card-header">3. Regions</header>
										<div class="card-block">
											<div id="region_filter_parent_div">
												<input type="text" name="region_filter" id='region_filter' class="form-control region_filter" placeholder="Search for Sub Region" autocomplete="off" />

												<div id="sub_region_tag_div" class="mt-2">
													<?php
													if( $has_tech_run == true ){

														if( $tech_run_row->sub_regions != '' ){

															// get sub region
															$sub_regions_sql = $this->db->query("
															SELECT 
																`sub_region_id`,
																`subregion_name`
															FROM `sub_regions`
															WHERE `sub_region_id` IN({$tech_run_row->sub_regions})
															");									
															foreach( $sub_regions_sql->result() as $sub_region ){ ?>

																<button type="button" class="btn btn-success sub_region_tag">
																	<?php echo $sub_region->subregion_name; ?> 
																	<input type="hidden" name="sub_region_ms_tag[]" class="selected_sub_region_ms_tag" value="<?php echo $sub_region->sub_region_id; ?>">
																	<span class="fa fa-close"></span>
																</button>

															<?php
															}

														}										

													}									
													?>									
												</div>
												
												<div id="region_filter_div" class="box-typical region_filter_div">
												
													<div class="region_dp_header">	
														<?php										
														foreach( $dist_state_obj as $distinct_state_row ){ ?>
															<div class="checkbox state_div_chk">

																<input type="checkbox" id="chk_state_<?php echo $distinct_state_row->region_state; ?>" name="state_ms[]" class="state_ms" value="<?php echo $distinct_state_row->region_state; ?>">
																<label for="chk_state_<?php echo $distinct_state_row->region_state; ?>" class="rf_state_lbl"><?php echo $distinct_state_row->region_state; ?> (<?php echo $distinct_state_row->jcount; ?>)</label>

																<div class="region_div">
																	<?php
																	foreach( $distinct_state_row->region_arr_obj as $region_row ){ ?>
																		<div class="checkbox region_div_chk">
																			<input type="checkbox" id="chk_region_<?php echo $region_row->regions_id ?>" name="region_ms[]" class="region_ms" value="<?php echo $region_row->regions_id; ?>">													
																			<label for="chk_region_<?php echo $region_row->regions_id; ?>" class="rf_region_lbl"><?php echo $region_row->region_name; ?> (<?php echo $region_row->jcount; ?>)</label>

																			<div class="sub_region_div">
																				<?php
																				foreach( $region_row->sub_region_arr_obj as $sub_region_row ){ ?>
																					<div class="checkbox sub_region_div_chk">
																						<input type="checkbox" id="chk_sub_region_<?php echo $sub_region_row->sub_region_id; ?>" name="sub_region_ms[]" class="sub_region_ms" value="<?php echo $sub_region_row->sub_region_id; ?>">
																						<label for="chk_sub_region_<?php echo $sub_region_row->sub_region_id; ?>" class="rf_sub_region_lbl sub_region_ms_lbl"><?php echo $sub_region_row->subregion_name; ?> (<?php echo $sub_region_row->jcount; ?>)</label>
																					</div>
																				<?php
																				}
																				?>
																			</div>
																			
																		</div>
																	<?php
																	}
																	?>
																</div>

															</div>
														<?php
														}
														?>
													</div>					
													
												</div>								
											</div>
										</div>
									</section>
								</div>		
							</div>

							<?php
							if( $has_tech_run == true ){ ?>

								<div class="row">
									<div class="col">
										<section class="card card-blue-fill">
											<header class="card-header">4. Selected Job Types</header>
											<div class="card-block">
												<button type="button" id="display_jt_btn_view" data-orig_btn_txt="ALL (Click to Edit)" class="btn btn-success">ALL (Click to Edit)</button>
												<div id="display_jt_div" class="mt-2">
													<?php
													if( $has_tech_run == true ){ ?>

														<div id="display_multiselect" class="box-typical">
															<?php
															// get selected tech run job types
															$hide_job_types_arr  = [];		
															foreach( $hide_job_types_sql->result() as $hide_job_types_row ){
																$hide_job_types_arr[] = $hide_job_types_row->job_type;
															}
																		
															foreach( $distinct_job_type_sql->result() as $index => $job_type_row ){ 																											
																?>
																<div class="checkbox">
																	<input 
																		type="checkbox" 
																		class="jt_display_filter" id="job_type_<?php echo $index; ?>" 
																		value="<?php echo $job_type_row->job_type; ?>" 
																		<?php echo ( !in_array($job_type_row->job_type, $hide_job_types_arr) )?'checked':null; ?> 
																	/>
																	<label for="job_type_<?php echo $index; ?>"><?php echo $job_type_row->job_type; ?></label>													
																</div>	
																<?php									
															}
															?>								
														</div>
														
													<?php
													}
													?>	
												</div>
											</div>
										</section>
									</div>
								</div>

								<div class="row">
									<div class="col">
										<section class="card card-blue-fill">
											<header class="card-header">5. Job Priority</header>
											<div class="card-block">
												<div class="row">

													<div class="col">
													<?php
													// ACT, SA OR QLD state
													if( 
														in_array("ACT", $str_state_arr) || 
														in_array("SA", $str_state_arr) || 
														in_array("QLD", $str_state_arr)
													){ ?>												
														<?php
														foreach( $str_state_arr as $str_state ){ 
															if( $str_state != 'NSW' ){ ?>
																<label class="label label-info mt-1"><?php echo $str_state; ?></label>
															<?php
															}																					
														}
														?>
														<ol id="job_priority_ol">
															<li>Allocate</li>
															<li>Fix/Replace</li>
															<li>COT/LR</li>
															<li>240v Rebook</li>
															<li>YM/Annual/OnceOff</li>
														</ol>
													<?php
													}
													?>
													</div>
													
													<div class="col">
													<?php											
													// NSW state only
													if( in_array("NSW", $str_state_arr) ){ ?>
														<label class="label label-info">NSW</label>
														<ol id="job_priority_ol">
															<li>Allocate</li>
															<li>Fix/Replace</li>
															<li>Job Age > 15 Days</li>
															<li>COT/LR</li>
															<li>240v Rebook</li>	
															<li>YM/Annual/OnceOff</li>													
														</ol>
													<?php
													}
													?>	
													</div>

												</div>																					
											</div>
										</section>
									</div>
								</div>

							<?php
							}
							?>							

						</div>
					</div>

					<div class="row">
						<div class="col text-right">
						<?php
						if( $has_tech_run == true ){ ?>
							
							<button type="submit" class="btn btn-primary" id="update_tech_run">Update Tech Run</button>	
							<input type="hidden" id="tr_already_exist" value="0" />
						<?php
						}else{ ?>

							<input type="hidden" id="tr_already_exist" value="0" />
							<button type="submit" class="btn btn-success" id="create_tech_run">Create Tech Run</button>

						<?php
						}
						?>	
						</div>
					</div>

					<input type="hidden" name="tr_id" id="tr_id" value="<?php echo $tr_id; ?>" />

				</form>


			</div><!--.tab-pane-->
			
			<?php
			// show only if tech run exist
			if( $has_tech_run == true ){ ?>

				<!-- DETAILS CONTENT -->
				<div role="tabpanel" class="tab-pane fade" id="nav_details">
				
					<div class="row">
						<div class="col-6">

							<section class="card card-blue-fill">
								<header class="card-header">Booking Goals</header>
								<div class="card-block">
								
									<table class="table table-borderless colour_tbl">
										<tr>
											<th>Colour</th>
											<th>Time</th>
											<th>Jobs</th>
											<th>NO Keys</th>
											<th>Status</th>
										</tr>
										<?php
										foreach( $trr_color_sql->result() as $trr_color_row ){

										
										// get saved colour table
										$sql_colour_sql = $this->db->query("
										SELECT 
											`time`,
											`jobs_num`,
											`no_keys`,
											`booking_status`
										FROM `colour_table`
										WHERE `tech_run_id` = {$tr_id}
										AND `colour_id` = {$trr_color_row->tech_run_row_color_id}
										");
										
										$sql_colour_row = $sql_colour_sql->row();
										$ct_time = $sql_colour_row->time;
										$ct_jobs = $sql_colour_row->jobs_num;
										$ct_no_keys_chk = $sql_colour_row->no_keys;
										$ct_booking_status = $sql_colour_row->booking_status;										
										$isFullyBooked = 0;

										$status_dif_txt = '';
										if($ct_booking_status!=''){

											if($ct_booking_status=='FULL'){
												$status_dif_txt = "FULL";
												$isFullyBooked = 1;
											}else{
												$status_dif_txt = $ct_booking_status;
											}

										}
										?>
											<tr id="ct_row_id_<?php echo $trr_color_row->tech_run_row_color_id; ?>" class="ct_row">
												<td style="background-color:<?php echo $trr_color_row->hex; ?>">
													<input type="hidden" class="ct_trrc_id" value="<?php echo $trr_color_row->tech_run_row_color_id; ?>" />
													<input type="hidden" class="ct_booked_job" value="0" />
													<input type="hidden" class="ct_fully_booked" value="<?php echo $isFullyBooked; ?>" />
												</td>
												<td><input type="text" class="form-control ct_time" value="<?php echo $ct_time; ?>" /></td>
												<td><input type="text" class="form-control ct_jobs" value="<?php echo $ct_jobs; ?>" /></td>
												<td>											
													<span class="checkbox">
														<input type="checkbox" id="ct_no_keys_chk_<?php echo $trr_color_row->tech_run_row_color_id; ?>" class="ct_no_keys_chk" <?php echo ($ct_no_keys_chk==1)?'checked="checked"':''; ?>>
														<label for="ct_no_keys_chk_<?php echo $trr_color_row->tech_run_row_color_id; ?>" class="chk_lbl"></label>
													</span>													
													<span class="fa fa-close text-danger redCross" style="<?php echo ($ct_no_keys_chk==1)?'display:inline;':'display:none;'; ?>"></span>
												</td>
												<td>
													<input type="text" class="form-control ct_status" value="<?php echo $status_dif_txt; ?>" readonly />
												</td>
											</tr>
										<?php
										}
										?>
									</table>
									
									<table class="table table-borderless colour_tbl mt-2">
										<tr>
											<th>Booked</th>
											<th>Door Knocks</th>
											<th>Billables</th>		
										</tr>
										<tr>
											<td class="booking_goals_count_td"><?php echo $tot_jobs_count; ?></td>
											<td class="booking_goals_count_td"><?php echo $tot_dk_count; ?></td>
											<td class="booking_goals_count_td"><?php echo $tot_bill_count; ?></td>
										</tr>
									</table>

								</div>
							</section>								

							<section class="card card-blue-fill" id="booking_notes_section">
								<header class="card-header" id="booking_notes_div">Booking Notes</header>
								<div class="card-block">									

									<table class="table table-borderless" id="tech_run_notes">
										<tr>
											<th>
												<div class="float-left">Call Centre Instructions</div>
												<div class="float-right font-italic" id="notes_timestamp_div">
													<span id="updates_by"><?php echo $notes_updated_by; ?></span>
													<span id="updated_ts"><?php echo $notes_ts; ?></span>
												</div>
											</th>
											<th>Technician Notes (Technician Can View)</th>
										</tr>
										<tr>
											<td>
												<textarea class="form-control addtextarea" name="notes" id="notes"><?php echo $tech_run_row->notes; ?></textarea>
											</td>
											<td>
												<textarea class="form-control addtextarea" name="tech_notes" id="tech_notes"><?php echo $tech_run_row->tech_notes; ?></textarea>
											</td>					
										</tr>
									</table>

								</div>
							</section>

						</div>

						<div class="col-6">																																																

						
							<section class="card card-blue-fill">
								<header class="card-header">Sort</header>
								<div class="card-block">								

									<select name="sort" id="sort" class="form-control">
										<option value="">None</option>
										<option value="1">Colour</option>
										<option value="2">Street</option>
										<option value="3">Suburb</option>
									</select>

								</div>
							</section>
						

							<div class="row">

								<div class="col-7">
									<section class="card card-blue-fill" id="function_map_section">
										<header class="card-header">Map and Run Sheets</header>
										<div class="card-block" style="display:flex; justify-content: space-between;">
											<a target="_blank" href="/tech_run/map/?tr_id=<?php echo $tr_id; ?>">
												<button type="button" class="btn">Map</button>
											</a>
											<a target="_blank" href="/tech_run/run_sheet_admin/<?php echo $tr_id; ?>">
												<button type="button" class="btn btn-warning">Run Sheet</button>
											</a>
											<a target="_blank" href="/tech_run/run_sheet/<?php echo $tr_id; ?>">								
												<button type="button" class="btn btn-info">Run Sheet (Tech View)</button>
											</a>
										</div>
									</section>
								</div>

								<div class="col-5">
									<section class="card card-blue-fill">
										<header class="card-header">Working Hours</header>
										<div class="card-block">
											<input type="text" class="form-control working_hours" id="working_hours" name="working_hours" value="<?php echo $tech_run_row->working_hours; ?>" />
										</div>
									</section>	
								</div>

							</div>

							<section class="card card-blue-fill">
								<header class="card-header">Booking Regions</header>
								<div class="card-block">
									<table class="table table-borderless colour_tbl">
										<tr>
											<th>Regions you are booking:</th>
											<th></th>
											<th>Alternate Days: </th>		
										</tr>
										<?php

										if( $tech_run_row->sub_regions != '' ){

											// get sub region
											$sub_region_sql = $this->db->query("
											SELECT 
												r.`region_name`,

												sr.`sub_region_id`,
												sr.`subregion_name`																			
											FROM `sub_regions` AS sr
											LEFT JOIN `regions` AS r ON sr.`region_id` = r.`regions_id`
											WHERE sr.`sub_region_id` > 0
											AND sr.`sub_region_id` IN({$tech_run_row->sub_regions}) 
											AND sr.`active` = 1
											");

											if( $sub_region_sql->num_rows() > 0 ){
												?>

												<?php
												foreach( $sub_region_sql->result() as $sub_region_row ){

													// get all postcode that belong to a sub region
													$postcodes_imp = null;

													$postcode_sql = $this->db->query("
													SELECT pc.`postcode`
													FROM `postcode` AS pc
													LEFT JOIN `sub_regions` AS sr ON pc.`sub_region_id` = sr.`sub_region_id`
													LEFT JOIN `regions` AS r ON sr.`region_id` = r.`regions_id`
													WHERE pc.`id` > 0
													AND pc.`sub_region_id` = {$sub_region_row->sub_region_id}
													");

													$postcodes_arr = [];
													foreach ( $postcode_sql->result() as $postcode_row ) {
														$postcodes_arr[] = $postcode_row->postcode;
													}

													if( count($postcodes_arr) > 0 ){

														$postcodes_imp = implode(",", $postcodes_arr);

														// todo: called in a function
														// get tech run row count
														$tech_run_rows_sql = $this->db->query("
														SELECT COUNT(trr.`tech_run_rows_id`) AS trr_count														
														FROM `tech_run_rows` AS trr
														LEFT JOIN `tech_run` AS tr ON trr.`tech_run_id` =  tr.`tech_run_id`
														LEFT JOIN `jobs` AS j ON ( trr.`row_id` = j.`id` AND trr.`row_id_type` = 'job_id' )  
														LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id`
														LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
														LEFT JOIN `tech_run_row_color` AS trr_hc ON trr.`highlight_color` = trr_hc.`tech_run_row_color_id`
														WHERE tr.`tech_run_id` = {$tr_id}
														AND tr.`country_id` = {$this->config->item('country')}
														AND j.`del_job` = 0
														AND p.`deleted` = 0
														AND a.`status` = 'active'
														AND a.`deleted` = 0													
														AND a.`country_id` = {$this->config->item('country')}
														AND ( 
															p.`is_nlm` = 0 OR 
															p.`is_nlm` IS NULL 
														)
														AND p.`postcode` IN ( {$postcodes_imp} ) 
														AND (
															j.`status` = 'To Be Booked'	
															OR j.`status` = 'Booked' 
															OR j.`status` = 'DHA'
															OR j.`status` = 'Escalate'
															OR j.`status` = 'On Hold' 
															OR j.`status` = 'Allocate'
														)
														AND ( 
															j.`assigned_tech` = {$tech_run_row->assigned_tech} 
															OR j.`assigned_tech` = 0
															OR j.`assigned_tech` IS NULL 
														) 
														AND(
															j.`date` = '{$tech_run_row->date}'
															OR j.`date` IS NULL
															OR j.`date` = '0000-00-00'
															OR j.`date` = ''
														)														
														");
														
														$trr_count =  $tech_run_rows_sql->row()->trr_count; ?>

														<tr>
															<td><?php echo "{$sub_region_row->region_name}/{$sub_region_row->subregion_name}"; ?></td>
															<td>(<?php echo $trr_count; ?>)</td>
															<td>
																<?php

																// fetch all future STR
																$future_str_sql = $this->db->query("
																SELECT 
																	`tech_run_id`,
																	`sub_regions`,
																	`date`
																FROM  `tech_run`
																WHERE `sub_regions` LIKE '%{$sub_region_row->sub_region_id}%'
																AND `date` > '".date('Y-m-d')."'
																AND `date` != '{$tech_run_row->date}'
																AND `country_id` = {$this->config->item('country')}
																");
																$fcount = 0;

																foreach( $future_str_sql->result() as $future_str_row ){

																	$reg_arr = explode(",",$future_str_row->sub_regions);

																	if( in_array($sub_region_row->sub_region_id, $reg_arr) ){

																		echo ($fcount!=0)?', ':'';

																		?>
																			<a href="/tech_run/set/?tr_id=<?php echo $future_str_row->tech_run_id ?>">
																				<?php echo date('D d/m',strtotime($future_str_row->date)); ?>
																			</a>
																		<?php
																		$fcount++;

																	}else{
																		$no_set_date_flag = 1;
																	}

																}

																if( $fcount==0 ){
																	echo "No Days scheduled";
																}
																?>
															</td>
														</tr>

													<?php	
													}													
													
												}

											}

										}
										?>
									</table>
								</div>
							</section>		
							
							<section class="card card-blue-fill page_key_section">
								<header class="card-header">Page Key</header>
								<div class="card-block" id="page_key_card_block">
									<div class="row">
										<div class="col text-center">
											<span class="page_key_completed">Completed</span>
											<span class="page_key_utc">Unable to Complete</span>
											<span class="page_key_error">ERROR on Tech Sheet</span>
											<span class="page_key_tbs">To Be Sorted</span>
											<span class="page_key_hidden_jobs">Hidden Job</span>
										</div>										
									</div>
								</div>								
							</section>

							<section class="card card-blue-fill" id="other_function_section">
								<header class="card-header">Run Status</header>
								<div class="card-block">
		
									<table class="table table-borderless" id="run_status_tbl">
										<tr>
											<td><button type="button" data-tech_run-field="run_set" class="btn run_status <?php echo ( ( $tech_run_row->run_set == 1 )?'btn-success':'btn-primary-outline' ); ?>">Run Set</button></td>
											<td><button type="button" data-tech_run-field="run_coloured" class="btn run_status <?php echo ( ( $tech_run_row->run_coloured == 1 )?'btn-success':'btn-primary-outline' ); ?>">Run Coloured</button></td>
											<td><button type="button" data-tech_run-field="ready_to_book" class="btn run_status <?php echo ( ( $tech_run_row->ready_to_book == 1 )?'btn-success':'btn-primary-outline' ); ?>">Ready to Book</button></td>
										</tr>
										<tr>
											<td><button type="button" data-tech_run-field="first_call_over_done" class="btn run_status <?php echo ( ( $tech_run_row->first_call_over_done == 1 )?'btn-success':'btn-primary-outline' ); ?>">1st Call Over Done</button></td>
											<td><button type="button" data-tech_run-field="run_reviewed" class="btn run_status <?php echo ( ( $tech_run_row->run_reviewed == 1 )?'btn-success':'btn-primary-outline' ); ?>">Run Reviewed</button></td>
											<td><button type="button" data-tech_run-field="finished_booking" class="btn run_status <?php echo ( ( $tech_run_row->finished_booking == 1 )?'btn-success':'btn-primary-outline' ); ?>">2nd Call Over Done</button></td>
										</tr>
										<tr>
											<td><button type="button" data-tech_run-field="additional_call_over" class="btn run_status <?php echo ( ( $tech_run_row->additional_call_over == 1 )?'btn-success':'btn-primary-outline' ); ?>">Extra Call Over</button></td>
											<td><button type="button" data-tech_run-field="additional_call_over_done" class="btn run_status <?php echo ( ( $tech_run_row->additional_call_over_done == 1 )?'btn-success':'btn-primary-outline' ); ?>">Extra Call Over Done</button></td>
											<td><button type="button" data-tech_run-field="ready_to_map" class="btn run_status <?php echo ( ( $tech_run_row->ready_to_map == 1 )?'btn-success':'btn-primary-outline' ); ?>">Run Ready to Map</button></td>
										</tr>
										<tr>
											<td><button type="button" data-tech_run-field="run_complete" class="btn run_status <?php echo ( ( $tech_run_row->run_complete == 1 )?'btn-success':'btn-primary-outline' ); ?>">Run Mapped</button></td>
											<td><button type="button" data-tech_run-field="morning_call_over" class="btn run_status <?php echo ( ( $tech_run_row->morning_call_over == 1 )?'btn-success':'btn-primary-outline' ); ?>">Morning Call Over</button></td>
											<td><button type="button" data-tech_run-field="no_more_jobs" class="btn <?php echo ( $tech_run_row->run_complete == 1 )?'run_status':null; ?> <?php echo ( ( $tech_run_row->no_more_jobs == 1 )?'btn-success':'btn-primary-outline' ); ?>" <?php //echo ( $tech_run_row->run_complete == 1 )?null:'disabled'; ?>>FULL - No More Jobs</button></td>
										</tr>
									</table>
										
								</div>
							</section>
							
						</div>

					</div>
				
				</div><!--.tab-pane-->

				<!-- FUNCTION CONTENT -->
				<div role="tabpanel" class="tab-pane fade" id="nave_functions">
				
					<div class="row">

						<div class="col-6">		
													
						<section class="tabs-section">

							<div class="tabs-section-nav">
								<div class="tbl">
									<ul class="nav" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" href="#tabs-2-tab-1" role="tab" data-toggle="tab">
												<span class="nav-link-in">Run Logs</span>
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="#tabs-2-tab-2" role="tab" data-toggle="tab">
												<span class="nav-link-in">Booking Goal Logs</span>
											</a>
										</li>				
									</ul>
								</div>
							</div>

							<div class="tab-content">

								<div role="tabpanel" class="tab-pane fade in active show" id="tabs-2-tab-1">
									<table class="table table-borderless colour_tbl">
										<tr>
											<th>Description</th>
											<th>Who</th>
											<th>Date</th>		
										</tr>
										<?php										
										foreach( $tech_run_logs_sql->result() as $trl_row ){ ?>
											<tr>
												<td><?php echo $trl_row->description; ?></td>
												<td><?php echo $this->system_model->formatStaffName($trl_row->FirstName, $trl_row->LastName); ?></td>
												<td><?php echo date('d/m/Y H:i',strtotime($trl_row->created)); ?></td>
											</tr>
										<?php
										}
										?>						
									</table>
								</div>

								<div role="tabpanel" class="tab-pane fade" id="tabs-2-tab-2">
									<table class="table table-borderless colour_tbl">
										<tr>
											<th>Description</th>
											<th>Who</th>
											<th>Date</th>		
										</tr>
										<?php										
										foreach( $booking_goals_logs_sql->result() as $trl_row ){ ?>
											<tr>
												<td><?php echo $trl_row->description; ?></td>
												<td><?php echo $this->system_model->formatStaffName($trl_row->FirstName, $trl_row->LastName); ?></td>
												<td><?php echo date('d/m/Y H:i',strtotime($trl_row->created)); ?></td>
											</tr>
										<?php
										}
										?>						
									</table>
								</div>

							</div>

						</section>						

						</div>

						<div class="col-6">

							<section class="card card-blue-fill">
								<header class="card-header">Select</header>
								<div class="card-block">																		
																	
									<div class="mt-2" id="select_function_btn_div">

										<table class="table table-borderless" id="select_table_section">
											<tr>
												<td class="chk_col">
													<span class="checkbox">
														<input type="checkbox" id="sel_job_type_chk">
														<label for="sel_job_type_chk"></label>
													</span>
												</td>
												<td>Select Job Type</td>
												<td class="select_job_type_class">
													<select name="select_job_type" id="select_job_type" class="form-control">
														<option value="">---</option>
														<?php
														foreach( $job_type_sql->result() as $job_type_row ){ ?>
															<option value="<?php echo $job_type_row->job_type; ?>"><?php echo $job_type_row->job_type; ?></option>
														<?php
														}
														?>
													</select>
												</td>
												<td class="select_job_type_class"><button type="button" class="btn btn-primary" id="select_job_type_btn">Select Job Type</button></td>
											</tr>
											<tr>
												<td class="chk_col">
													<span class="checkbox">
														<input type="checkbox" id="sel_agency_job_chk">
														<label for="sel_agency_job_chk"></label>
													</span>
												</td>
												<td>Select Agency Jobs</td>
												<td class="select_agency_jobs_class">
													<select name="select_agency_jobs" id="select_agency_jobs" class="form-control">
														<option value="">---</option>	
														<?php
														foreach( $sel_agency_jobs_sql->result() as $sel_agency_jobs_row ){ ?>
															<option value="<?php echo $sel_agency_jobs_row->agency_id; ?>"><?php echo $sel_agency_jobs_row->agency_name; ?></option>
														<?php
														}
														?>																									
													</select>
												</td>
												<td class="select_agency_jobs_class">
													<button type="button" class="btn btn-primary" id="select_agency_jobs_btn">Select Agency Jobs</button>
												</td>
											</tr>
											<tr>
												<td class="chk_col">
													<span class="checkbox">
														<input type="checkbox" id="sel_first_visit_chk">
														<label for="sel_first_visit_chk"></label>
													</span>
												</td>
												<td>Select First Visit</td>
											</tr>
											<tr>
												<td class="chk_col">
													<span class="checkbox">
														<input type="checkbox" id="sel_esc_jobs_chk">
														<label for="sel_esc_jobs_chk"></label>
													</span>
												</td>
												<td>Select Escalate Jobs</td>
											</tr>
											<tr>
												<td class="chk_col">
													<span class="checkbox">
														<input type="checkbox" id="sel_no_tenant_det_chk">
														<label for="sel_no_tenant_det_chk"></label>
													</span>
												</td>
												<td>Select No Tenants</td>
											</tr>
											<tr>
												<td class="chk_col">
													<span class="checkbox">
														<input type="checkbox" id="select_holiday_rent_chk">
														<label for="select_holiday_rent_chk"></label>
													</span>
												</td>
												<td>Select Holiday Rental</td>
											</tr>
											<tr>
												<td class="chk_col">
													<span class="checkbox">
														<input type="checkbox" id="select_uncoloured_chk">
														<label for="select_uncoloured_chk"></label>
													</span>
												</td>
												<td>Select Uncoloured</td>
											</tr>
										</table>

										
									</div>																										

								</div>
							</section>
                        </div>

					</div>

				</div><!--.tab-pane-->

			<?php
			}
			?>
			

		</div><!--.tab-content-->

	</section><!--.tabs-section-->
	<?php if( $has_tech_run): ?>

    <section class="card card-blue-fill">
        <header class="card-header text-center">Tech Run List Options</header>
        <div class="card-block" style="display:flex; justify-content: space-between">

            <button type="button" class="btn btn-success mt-2 en_btn">Entry Notice</button>
            <button type="button" class="btn btn-danger mt-2 delete_btn">Delete</button>
            <button type="button" class="btn mt-2 refresh_btn">Refresh</button>
            <button
                    type="button"
                    class="btn hidden_jobs_toggle_btn <?php echo ( $tech_run_row->show_hidden == 1 )?'btn-warning':'btn-secondary'; ?> mt-2"
            >
				<?php echo ( $tech_run_row->show_hidden == 1 )?'Hide':'Show'; ?> <span class="hiddenRowsCount_span">0</span> Hidden Jobs
            </button>
            <button type='button' class='btn mt-2 btn_display_distance'>Display Distance to Agency</button>
            <button type="button" class="btn mt-2 filter_agency_btn">Filter Unassigned Jobs by Agency <?php echo ( $sel_agency_filter_count > 0 )?" ({$sel_agency_filter_count})":null; ?></button>

        </div>
    </section>
	<?php endif; ?>
	<?php
	// show only if tech run exist
	if( $has_tech_run == true ){
	?>

		<div id="tbl_maps_parent_div">
		<table id="tbl_maps" class="table table-hover tech_run_tbl" data-paging="false">
			<thead>
				<tr class="nodrop nodrag str_header_row">
                    <th class="chk_col" data-orderable="false">
						<span class="checkbox">
							<input type="checkbox" id="check-all" class="check-all">
							<label for="check-all" class="chk_lbl"></label>
						</span>
                    </th>
					<th>#</th>
                    <th class="EN_show_elem">Alarms Req.</th>
                    <th class="EN_show_elem">Time</th>
                    <th class="EN_show_elem">Keys/EN</th>
					<th></th>
					<th>Details</th>
					<th>Deadline</th>
					<th>Age</th>
					<th>End Date</th>
					<th>Vacant</th>
					<th>Notes</th>
					<th>Time</th>
					<th>Job Status</th>
					<th>Job Type</th>
					<th>Service</th>
					<th>DK</th>				
					<th>Address</th>
					<th>Region</th>
					<th>Agency</th>
					<th>Job Comments</th>
					<th>Property Comments</th>

					<th>Preferred Time</th>		
					<th class="DTA_elem">Distance to agency</th>

					<?php
					if( $tech_run_row->show_hidden == 1 ){ ?>
						<th class="hidden_elem">Hidden</th>
					<?php
					}
					?>
				</tr>
			</thead>							
			<tbody>
			<?php
			$ctr = 2;
			$hiddenRowsCount = 0;
			foreach( $tech_run_row_sql as $tech_run_row_data ){  // job

				$bgcolor = null; // clear background color every row

				if( $tech_run_row_data->row_id_type == 'job_id' ){

					$show_row = true;
					$hiddenText = null;
					$isUnavailable = 0;
					$isHidden = 0;
					$isPriority = 0;
					$is_no_en = false;

					// filters
					// if job type is 240v Rebook and status is to be booked and the tech is not electrician then hide it
					if( 
						( $tech_run_row_data->job_type == '240v Rebook' || $tech_run_row_data->is_eo == 1 ) && 
						$tech_run_row_data->j_status == 'To Be Booked' && $tech_run_row->is_tech_elec == 0 
					){
						$hiddenText .= '240v<br />';
						$show_row = false;
					}else{
						$show_row = true;
					}

					if( $tech_run_row_data->hidden == 1 ){
						$hiddenText .= 'User<br />';
					}

					if( $tech_run_row_data->unavailable == 1 && $tech_run_row_data->unavailable_date == $tech_run_row->date ){

						$isUnavailable = 1;
						$hiddenText .= 'Unavailable<br />';
						
					}
		
					$startDate = date('Y-m-d',strtotime($tech_run_row_data->start_date));
		
					if( $tech_run_row_data->job_type == 'Lease Renewal' && ( $tech_run_row_data->start_date != "" && $tech_run_row->date < $startDate ) ){
						$hiddenText .= 'LR<br />';
					}
		
					if( $tech_run_row_data->job_type == 'Change of Tenancy' && ( $tech_run_row_data->start_date != "" && $tech_run_row->date < $startDate  ) ){
						$hiddenText .= 'COT<br />';
					}
		
					if( $tech_run_row_data->j_status == 'DHA' && ( $tech_run_row_data->start_date != "" && $tech_run_row->date < $startDate ) ){
						$hiddenText .= 'DHA<br />';
					}
		
					if( $tech_run_row_data->j_status == 'On Hold' && ( $tech_run_row_data->start_date != "" && $tech_run_row->date < $startDate ) ){
						$hiddenText .= 'On Hold<br />';
					}
		
					if( $tech_run_row_data->j_status == 'On Hold' && $tech_run_row_data->allow_upfront_billing == 1 ){
						$hiddenText .= 'Up Front Billing<br />';
					}
		
					// this job is for electrician only
					if( $tech_run_row_data->electrician_only == 1 && $tech_run_row->is_tech_elec == 0 ){
						$hiddenText .= 'Electrician Only<br />';
					}

					if( $tech_run_row->show_hidden == 0 && $hiddenText != "" && $tech_run_row_data->j_status != 'Booked' ){
						$show_row = false;
					}else{
						$show_row = true;
					}
					
					if( $hiddenText != "" ){

						$hiddenRowsCount++;
						//$bgcolor = "#ADD8E6";
						$isHidden = 1;

					}
		
					if( $tech_run_row->show_hidden == 1 && ( $tech_run_row_data->hidden == 1 || $isUnavailable == 1 ) ){
						$hideChk = 0;
					}else if( $tech_run_row->show_hidden == 1 ){
						$hideChk = 1;
					}else{
						$hideChk = 0;
					}
		
		
					// if property and agency is NO to EN
					if( $tech_run_row_data->no_en == 1 || ( is_numeric($tech_run_row_data->allow_en) && $tech_run_row_data->allow_en == 0 ) ){
						$is_no_en = true;
					}
				
					// priority jobs
					$isPriority = false;
					if(
						$tech_run_row_data->job_type == "Change of Tenancy" ||
						$tech_run_row_data->job_type == "Lease Renewal" ||
						$tech_run_row_data->job_type == "Fix or Replace" ||
						$tech_run_row_data->job_type == "240v Rebook" ||
						$tech_run_row_data->is_eo == 1 ||
						$tech_run_row_data->j_status == 'DHA' ||
						$tech_run_row_data->urgent_job == 1
					){
						$isPriority = true;
					}

					$ecalate_reason_str = $tech_run_row_data->j_status;
					$isEscalateJob = 0;

					if( $tech_run_row_data->j_status == 'Escalate' ){

						// get Escalate Reasons
						$escalate_sql = $this->db->query("
							SELECT *
							FROM `selected_escalate_job_reasons` AS sejr
							LEFT JOIN `escalate_job_reasons` AS ejr ON sejr.`escalate_job_reasons_id` = ejr.`escalate_job_reasons_id`
							WHERE sejr.`job_id` = {$tech_run_row_data->jid}
						");

						$escalate_arr = [];
						foreach( $escalate_sql->result() as $escalate_row ){
							$escalate_arr[] = $escalate_row->reason_short;
						}

						$ecalate_reason = implode("<br />",$escalate_arr);
						$ecalate_reason_str =  "<b class='text-danger'>{$ecalate_reason}</b>";
						$isEscalateJob = 1;

					}

					$tr_class_arr = [];
					$tr_class_arr[] = ( $tech_run_row_data->hex != '' )?'hasColor':'NoColor'; // colour
					$tr_class_arr[] = ( $isHidden != '' )?'hidden_elem hiddenJobs':null; // hidden jobs
					$tr_class_arr[] = ( $tech_run_row_data->holiday_rental == 1 )?'jrow_holiday_rental':null; // holiday/short term rental
					$tr_class_arr[] = ( $isEscalateJob == 1 )?'jrow_escalate_jobs':null; // escalate						
					$tr_class_arr[] = ( $tech_run_row_data->j_status == 'Booked' )?'isBooked':null; // job status booked

					// first visit
					$tr_class_arr[] = empty($tech_run_row_data->completed_jobs) ? 'jrow_first_visit' : null;

                    // No tenants based off column on jobs table, not off property_tenants table ?????
					$tr_class_arr[] = ( empty($tech_run_row_data->completed_jobs) && $tech_run_row_data->property_vacant == 0 ) ? 'no_tenants' : null ;


					// add all class
					$tr_class_imp = implode(' ',$tr_class_arr);	


					// row highlight color
					$bgcolor = '';
					if( $tech_run_row_data->job_reason_id > 0 ){
						$bgcolor = "background-color:#fffca3 !important;";
					}									

					if( $tech_run_row_data->dnd_sorted == 0 ){
						$bgcolor = 'background-color:#ffff00 !important;';
					}

					if( $tech_run_row_data->ts_completed == 1 ){
						$bgcolor = "background-color:#c2ffa7 !important;";
					}

					
					if( $show_row == true ){						
					?>
					<tr 
						id="<?php echo $tech_run_row_data->tech_run_rows_id; ?>" 
						class="tech_run_row_tr <?php echo $tr_class_imp; ?>" 
						data-hlc_id="<?php echo $tech_run_row_data->highlight_color; ?>"
						style="<?php echo $bgcolor; ?>"
					>
                        <td class="chk_col">


							<?php

								// no tenant icon
								if( !$tech_run_row_data->has_tenant ){ ?>

                                    <img
                                            data-toggle="tooltip"
                                            title="No Tenants"
                                            class="no_tenant_icon EN_show_elem"
                                            data-prop_vacant="<?php echo $tech_run_row_data->property_vacant; ?>"
                                            data-start_date="<?php echo $tech_run_row_data->start_date; ?>"
                                            data-due_date="<?php echo $tech_run_row_data->due_date; ?>"
                                            style="cursor: pointer;"

                                            src="/images/tech_run/no_tenant.png"
                                    />

									<?php
								}

								// Has tenant but no contact info
								if( $tech_run_row_data->has_tenant && !$tech_run_row_data->has_tenant_contact_info ){ ?>
                                    <img class="invalid_en_icon EN_show_elem" data-toggle="tooltip" title="No tenant mobile and email, invalid for EN" style="cursor: pointer;" src="/images/tech_run/invalid_en.png" />
									<?php
								}

								// If no tenant or no contact info or no entry notices - then do not allow selection checkbox on this row
								$row_checkbox_class = ( !$tech_run_row_data->has_tenant || !$tech_run_row_data->has_tenant_contact_info || $is_no_en ) ? 'hide_chk_on_en' : '';
							?>

                            <input type="hidden" class="row_id_type" value="<?php echo $tech_run_row_data->row_id_type; ?>" />
                            <input type="hidden" class="job_id" value="<?php echo $tech_run_row_data->jid; ?>" />
                            <input type="hidden" class="job_status" value="<?php echo $tech_run_row_data->j_status; ?>" />
                            <input type="hidden" class="job_type" value="<?php echo $tech_run_row_data->job_type; ?>" />

                            <input type="hidden" class="property_id" value="<?php echo $tech_run_row_data->property_id; ?>" />
                            <input type="hidden" class="prop_address" value="<?php echo $prop_address; ?>" />
                            <input type="hidden" class="prop_no_dk" value="<?php echo $tech_run_row_data->no_dk; ?>" />

                            <input type="hidden" class="agency_id" value="<?php echo $tech_run_row_data->agency_id; ?>" />
                            <input type="hidden" class="agency_name" value="<?php echo $agency_name; ?>" />
                            <input type="hidden" class="agency_no_dk" value="<?php echo $tech_run_row_data->allow_dk; ?>" />
                            <input type="hidden" class="sort_order_num" value="<?php echo $tech_run_row_data->sort_order_num; ?>" />
                            <input type="hidden" class="trrc_id" value="<?php echo $tech_run_row_data->tech_run_row_color_id; ?>" />
                            <input type="hidden" class="row_type" value="job" />

                            <span class="<?php echo $row_checkbox_class; ?>">
								<input
                                        type="checkbox"
                                        id="trr_chk-<?php echo $tech_run_row_data->tech_run_rows_id; ?>"
                                        class="trr_chk"
                                        data-row-type="job"
                                        value="<?php echo $tech_run_row_data->tech_run_rows_id; ?>"
                                />
								<label for="trr_chk-<?php echo $tech_run_row_data->tech_run_rows_id; ?>"></label>
							</span>

                        </td>

                        <td><?php echo $ctr; ?></td>
                        <td class="EN_show_elem" style="text-align: center;"><?php echo $tech_run_row_data->qld_new_leg_alarm_num; ?></td>
                        <td class="EN_show_elem"><input type="text" class="form-control en_time" value="8.30 - 5"></td>
                        <td class="EN_show_elem"><?php echo ( $tech_run_row_data->key_allowed != 1 || $tech_run_row_data->no_keys == 1 || $tech_run_row_data->no_en == 1 )?'<span Class="fa fa-close text-danger agency_no_key_access_allowed"></span>':null; ?></td>

                        <td style="<?php echo ( $tech_run_row_data->tech_run_row_color_id > 0 )?"background-color:{$tech_run_row_data->hex};":null; ?>">
							<span class="d-none"><?php echo ( $tech_run_row_data->tech_run_row_color_id > 0 )?$tech_run_row_data->tech_run_row_color_id:99999999; ?></span>
						</td>
						<td>
							<?php
							$icons_arr = [];

							// green phone icon
							$had_a_phone_call_in_the_last_x_hours = false;


                            $current_time = date("Y-m-d H:i:s");
                            $job_log_time = date("Y-m-d H:i:s",strtotime("{$job_log_row->eventdate} {$job_log_row->eventtime}:00"));
                            $last_x_hours = date("Y-m-d H:i:s",strtotime("-3 hours")); // last 3 hours

							// get 'phone call' contact type from new logs
							$log_title_id = 93; // Phone Call
							$new_job_log_sql = $this->db->query("
							SELECT COUNT(`log_id`) AS log_count
							FROM `logs`
							WHERE `job_id` = {$tech_run_row_data->jid}
							AND `title` = {$log_title_id}
							AND `deleted` = 0 														
							AND `created_date` BETWEEN '{$last_x_hours}' AND '{$current_time}'
							");
							
							if( $new_job_log_sql->row()->log_count > 0 ){														
								$had_a_phone_call_in_the_last_x_hours = true;
							}

							// display green phone icon
							if( $tech_run_row_data->j_status == 'To Be Booked' && $had_a_phone_call_in_the_last_x_hours == true ){	

								$icons_arr[] =  (object) [
									'src' => '/images/tech_run/green_phone.png',
									'title' => 'Phone Call'
								];

							}

							// first visit
							if( $tech_run_row_data->completed_jobs == 0 ) { // first visit
								$icons_arr[] =  (object) [
									'src' => '/images/tech_run/first_icon2.png',
									'title' => 'First visit'
								];
							}
							
							// priority
							if( $isPriority == true ){
								$icons_arr[] =  (object) [
									'src' => '/images/tech_run/caution.png',
									'title' => 'Priority Jobs'
								];
							}

							// key acccess
							if( $tech_run_row_data->key_access_required == 1 && $tech_run_row_data->j_status == 'Booked' ){							
								$icons_arr[] =  (object) [
									'src' => '/images/tech_run/key_icon_green.png',
									'title' => 'Key Access Required'
								];
							}

							// No Tenants Icon
							if( !$tech_run_row_data->has_tenant ) { // no tenants
								$icons_arr[] =  (object) [
									'src' => '/images/tech_run/no_tenant.png',
									'title' => 'No Tenants'
								];
							}

							// age
							if(  $tech_run_row_data->age > 60  ){														
								$icons_arr[] =  (object) [
									'src' => '/images/tech_run/bomb.png',
									'title' => '60+ days old'
								];
							}

							// service garage
							if( $tech_run_row_data->p_state == 'NSW' && $tech_run_row_data->service_garage == 1 ){							
								$icons_arr[] =  (object) [
									'src' => '/images/serv_img/service_garage_icon.png',
									'title' => 'Service Garage'
								];
							}

							// hidden
							if( $isHidden == 1 ){														
								$icons_arr[] =  (object) [
									'src' => '/images/tech_run/hidden_job.png',
									'title' => '60+ days old'
								];
							}
							
							// display icons
							foreach( $icons_arr as $icon ){ ?>
								<img src="<?php echo $icon->src; ?>" class="details_icon mr-1 mb-1" title="<?php echo $icon->title; ?>" />
							<?php
							}
							?>
						</td>
						<td><?php echo ( $tech_run_row_data->deadline >= 0 )?$tech_run_row_data->deadline:"<span class='text-danger'>{$tech_run_row_data->deadline}</span>"; ?></td>
						<td><?php echo ( $tech_run_row_data->age > 30 )?"<span class='text-danger'>{$tech_run_row_data->age}</span>":$tech_run_row_data->age; ?></td>
						<td>
						<!-- used to sort date dont remove (start) -->
						<span class="d-none"><?php echo strtotime($tech_run_row_data->due_date); ?></span>
						<!-- used to sort date dont remove (end) -->
						<?php echo ( $this->system_model->isDateNotEmpty($tech_run_row_data->due_date) )?date('d/m/Y',strtotime($tech_run_row_data->due_date)):null; ?>
						</td>
						<td><?php echo ( $tech_run_row_data->property_vacant == 1 )?"<span class='text-danger font-weight-bold'>YES</span>":null; ?></td>
						<td><?php echo $tech_run_row_data->tech_notes; ?></td>
						<td class="time_of_day_td text-center">
							<?php
							if( $tech_run_row->run_complete == 1 ){ ?>

								<div class="time_of_day_div">
									<a href="javascript:void(0);" class="time_of_day_link"><?php echo $tech_run_row_data->time_of_day; ?></a>
									<input type="text" class="form-control mb-2 time_of_day_hid" value="<?php echo $tech_run_row_data->time_of_day; ?>" />
									<a href="javascript:void(0);"><span class="fa fa-save time_of_day_save_icon"></span></a>
								</div>

							<?php
							}else{
								echo $tech_run_row_data->time_of_day;
							}
							?>
						</td>
						<td>
							<?php 
							echo $ecalate_reason_str; 
							if( $tech_run_row_data->j_status == 'Booked' ){
							?>
								<img data-toggle="tooltip" title="Booked" class="booked_icon" src="/images/tech_run/check_icon2.png" />
							<?php
							}
							?>
						</td>
						<td>
							<a target="_blank" href="<?php echo $this->config->item('crmci_link'); ?>/jobs/details/<?php echo $tech_run_row_data->jid; ?>?tr_tech_id=<?php echo $tech_run_row->assigned_tech; ?>&tr_date=<?php echo $tech_run_row->date; ?>&tr_booked_by=<?php echo $this->session->staff_id; ?>">
								<?php 
								if( $tech_run_row_data->jt_abbrv == 'FR' ){
									echo 'FIX';
								}else{
									echo $tech_run_row_data->jt_abbrv;
								} 
								?>
							</a>
						</td>
						<td>
							<?php
							// display icons
							$job_icons_params = array(
								'job_id' => $tech_run_row_data->jid
							);
							echo $this->system_model->display_job_icons($job_icons_params);
							?>
						</td>
						<td><?php echo ( $tech_run_row_data->door_knock == 1 )?'YES':null; ?></td>								
						<td>
							<a target="_blank" href="/properties/details/?id=<?php echo $tech_run_row_data->property_id; ?>">
								<span class="p_address"><?php echo $prop_address = "{$tech_run_row_data->p_address_1} {$tech_run_row_data->p_address_2}, {$tech_run_row_data->p_address_3}"; ?></span>
							</a>
						</td>
						<td><?php echo $tech_run_row_data->subregion_name; ?></td>
						<td>
							<a target="_blank" href="/agency/view_agency_details/<?php echo $tech_run_row_data->agency_id; ?>"><?php echo $agency_name = $tech_run_row_data->agency_name; ?></a>
							<span class="a_address"><?php echo "{$tech_run_row_data->a_address_1} {$tech_run_row_data->a_address_2}, {$tech_run_row_data->a_address_3} {$tech_run_row_data->a_state} {$tech_run_row_data->a_postcode}"; ?></span>
						</td>
						<td><?php echo $tech_run_row_data->j_comments; ?></td>
						<td><?php echo $tech_run_row_data->p_comments; ?></td>

						<td><?php echo $tech_run_row_data->preferred_time; ?></td>
						<td class="DTA_elem distance_to_agency"></td>
                        <?php
						if( $tech_run_row->show_hidden == 1 ){ ?>
							<td class="hidden_elem"><?php echo $hiddenText; ?></td>
						<?php
						}
						?>						

					</tr>
				<?php
					$ctr++;
					}	
                }else if( $tech_run_row_data->row_id_type == 'keys_id' ){ // key

					if( $tech_run_row_data->trk_action == "Pick Up" && $tech_run_row_data->trk_completed == 1 ){
						$bgcolor = "#c2ffa7";
					}									
					?>

					<tr id="<?php echo $tech_run_row_data->tech_run_rows_id; ?>"  class="tech_run_row grey_row" style="background-color:<?php echo $bgcolor; ?>">
                        <td>
                            <input type="hidden" class="row_type" value="key" />
                            <input type="hidden" class="trk_id" value="<?php echo $tech_run_row_data->tech_run_keys_id; ?>" />

                            <span>
								<input
                                        type="checkbox"
                                        id="trr_chk-<?php echo $tech_run_row_data->tech_run_rows_id; ?>"
                                        class="trr_chk"
                                        value="<?php echo $tech_run_row_data->tech_run_rows_id; ?>"
                                        data-row-type="key"
                                />
								<label for="trr_chk-<?php echo $tech_run_row_data->tech_run_rows_id; ?>"></label>
							</span>
                        </td>
                        <td><?php echo $ctr; ?></td>
                        <td class="EN_show_elem">&nbsp;</td>
                        <td class="EN_show_elem">&nbsp;</td>
                        <td class="EN_show_elem">&nbsp;</td>

						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>
							<?php 
							if( $tech_run_row_data->trk_completed == 1 ){

								$kr_act = explode(" ",$tech_run_row_data->trk_action);
								$temp2 = ( $tech_run_row_data->trk_action == "Drop Off" )?'p':null;
								$temp = "{$kr_act[0]}{$temp2}ed";
								$action = "{$temp} {$kr_act[1]}";

							}else{
								$action = $tech_run_row_data->trk_action;
							}
							echo $action;
							?>
						</td>
						<td></td>
						<td></td>
						<td></td>
						<td><img src="/images/key_icon_green.png" /></td>
						<td></td>
						<td>
							<?php  
							if( $tech_run_row_data->agen_add_id > 0 ){ // key address

								echo "{$tech_run_row_data->agen_add_street_num} {$tech_run_row_data->agen_add_street_name}, {$tech_run_row_data->agen_add_suburb}"; 

							}else{ // default

								echo "{$tech_run_row_data->key_a_address_1} {$tech_run_row_data->key_a_address_2}, {$tech_run_row_data->key_a_address_3}"; 

							}	
							?>
						</td>
						<td></td>
						<td>
							<a target="_blank" href="/agency/view_agency_details/<?php echo $tech_run_row_data->key_a_agency_id; ?>">
								<?php echo $tech_run_row_data->key_a_agency_name;  ?>
							</a>
						</td>
						<td></td>
						<td></td>

						<td></td>							
						<td class="DTA_elem">&nbsp;</td>

						<?php
						if( $tech_run_row->show_hidden == 1 ){ ?>
							<td class="hidden_elem">&nbsp;</td>
						<?php
						}
						?>

					</tr>
					
				<?php
				$ctr++;
				}else if( $tech_run_row_data->row_id_type == 'supplier_id' ){ // supplier ?>

					<tr id="<?php echo $tech_run_row_data->tech_run_rows_id; ?>"  class="tech_run_row grey_row">
                        <td>
                            <input type="hidden" class="row_type" value="supplier" />
                            <input type="hidden" class="trs_id" value="<?php echo $tech_run_row_data->tech_run_suppliers_id; ?>" />

                            <span>
								<input type="checkbox"
                                       id="trr_chk-<?php echo $tech_run_row_data->tech_run_rows_id; ?>"
                                       class="trr_chk"
                                       value="<?php echo $tech_run_row_data->tech_run_rows_id; ?>"
                                       data-row-type="supplier"
                                />
								<label for="trr_chk-<?php echo $tech_run_row_data->tech_run_rows_id; ?>"></label>
							</span>
                        </td>
                        <td><?php echo $ctr; ?></td>
                        <td class="EN_show_elem">&nbsp;</td>
                        <td class="EN_show_elem">&nbsp;</td>
                        <td class="EN_show_elem">&nbsp;</td>

						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td>Supplier</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>																		
						<td><?php echo $tech_run_row_data->sup_address; ?></td>
						<td><?php echo $tech_run_row_data->company_name; ?></td>
						<td></td>
						<td></td>
						<td></td>						

						<td></td>						
						<td class="DTA_elem">&nbsp;</td>

						<?php
						if( $tech_run_row->show_hidden == 1 ){ ?>
							<td class="hidden_elem">&nbsp;</td>
						<?php
						}
						?>
					</tr>
					
				<?php
				$ctr++;
				}
			}
			?>
			</tbody>
		</table>
		</div>
		<input type="hidden" id="hiddenRowsCount" value="<?php echo $hiddenRowsCount; ?>" />

	<?php
	}
	?>
	

</div>

<section class="card card-blue-fill" id="bot_func_btn_div">
	
	<header class="card-header">

		<span id="panel_text">Select Action</span>

		<span class="fa fa-minus float-right" id="minimize_panel"></span>

		<span class="fa fa-square-o float-right ml-3" id="maximize_panel"></span>

	</header>

	<div class="card-block" id="bot_func_btn_inner_div">
		
		<select id="tech_run_functions" class="form-control">
			<option value="">---</option>
			<option value="hide">Hide/Unhide</option>
			<option class="show_for_jobs" value="dk">Door Knocks</option>		
			<option class="show_for_jobs" value="highlight">Assign/Remove Colour</option>
			<option class="show_for_jobs" value="escalate">Escalate</option>
			<option class="show_for_jobs" value="change_tech">Change Tech</option>
			<option class="show_for_jobs" value="mark_tech_sick">Mark Tech Sick</option>
			<option class="show_for_keys" value="keys">Keys</option>
			<option class="show_for_supplier" value="suppliers">Supplier</option>
			<option class="EN_show_elem" value="en">Entry Notice</option>
		</select>

		<div id="bot_func_hide" class="mt-2">
			<button type="button" class="btn" id="hide_btn">Hide</button>
		</div>

		<div id="bot_func_assign_dk" class="mt-2">
			<button type="button" class="btn" id="assign_dk_btn">Assign Door Knock</button>
		</div>

		<div id="bot_func_en" class="mt-2">
			<button type="button" class="btn" id="issue_en_btn">Issue Entry Notice</button>
		</div>

		<div id="bot_func_escalate" class="mt-2">
			<button type="button" class="btn" id="escalate_jobs_btn">Escalate Jobs</button>
		</div>
		
		<div id="bot_func_highlight_row">
			<div class="mt-4">
				<select class="form-control mb-2" id="row_highlight_color">
					<option value="">---</option>
					<?php
					foreach( $trr_color_sql->result() as $trr_color_row ){ ?>
						<option value="<?php echo $trr_color_row->tech_run_row_color_id; ?>"><?php echo $trr_color_row->color; ?></option>
					<?php
					}
					?>
				</select>
				<button type="button" id="btn_assign_color" class="btn">Assign Color</button>
			</div>

			<div class="mt-4">
				<button type="button" id="btn_remove_color" class="btn">Remove Color</button>
			</div>
		</div>	

		<div id="bot_func_change_tech">
			<div class="mt-4">
				<select class="form-control mb-2" id="change_tech_dp">
					<option value="">-- Select --</option>
					<?php
					foreach( $tech_sql->result() as $tech_row ){ ?>

						<option value="<?php echo $tech_row->StaffID; ?>" <?php echo ( $tech_row->StaffID == $tech_run_row->assigned_tech )?'selected':null; ?>>
							<?php echo $this->system_model->formatStaffName($tech_row->FirstName,$tech_row->LastName).( ( $tech_row->is_electrician == 1 )?' [E]':null ); ?>
						</option>

					<?php
					}
					?>
				</select>
				<button type="button" class="btn" id="change_tech_update_btn">Update Tech</button>
			</div>
		</div>

		<div id="bot_func_mark_tech_sick" class="mt-2">
			<button type="button" class="btn" id="mark_tech_sick">Mark Tech Sick</button>
		</div>

		<div id="bot_func_remove_keys" class="mt-2">
			<button type="button" class="btn" id="remove_keys_btn">Remove Keys</button>
		</div>

		<div id="bot_func_remove_suppliers" class="mt-2">
			<button type="button" class="btn" id="remove_suppliers_btn">Remove Suppliers</button>
		</div>	

	</div>
</section>

<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" ><pre><code><?php echo $page_query; ?></code></pre></div>

<div id="filter_agency_filter_fb" class="fancybox" style="display:none;max-width:500px;">

    <h3>Filter Unassigned Job by Agency</h3>

    <p>
        This filter works on unassigned (uncoloured) jobs only, allowing booking staff to focus on filling the tech run by booking jobs for the selected agencies only.
    </p>
    <div class="my-3">
        <?php
        if( $has_tech_run == true ){

            foreach( $sel_agency_jobs_sql->result() as $sel_agency_jobs_row ){ ?>
                <div class="checkbox">
                    <input
                        type="checkbox" id="agency_filter_chk<?php echo $sel_agency_jobs_row->agency_id; ?>"
                        class="agency_filter_chk"
                        value="<?php echo $sel_agency_jobs_row->agency_id; ?>"
                        <?php echo ( in_array($sel_agency_jobs_row->agency_id, $sel_agency_filter_exp) )?'checked':null; ?>
                    />
                    <label for="agency_filter_chk<?php echo $sel_agency_jobs_row->agency_id; ?>"><?php echo $sel_agency_jobs_row->agency_name; ?></label>
                </div>
            <?php
            }

        }
        ?>
    </div>

    <div class="text-center">
        <button type="button" class="btn" id="agency_filter_btn_save" style="width:100%;">Save</button>
    </div>
    <br>
    <p>
        Please note this will not save any outstanding changes, if you have made changes close this popup and save your changes first.
    </p>
    
</div>

<!-- Fancybox END -->
<script type="text/javascript" src="/inc/js/select_multiple_checkbox_via_shift_key.js"></script>
<script type="text/javascript">
function show_bottom_functions(){

	var ticked_count = jQuery(".trr_chk:checked").length;

	if( ticked_count > 0 ){

		// find ticked row count for jobs, key and supplier
		var jobs_row_ticked_count = jQuery('input.trr_chk[data-row-type="job"]:checked').length;
		var key_row_ticked_count = jQuery('input.trr_chk[data-row-type="key"]:checked').length;
		var supplier_row_ticked_count = jQuery('input.trr_chk[data-row-type="supplier"]:checked').length;

		// show jobs dropdown option
		if( jobs_row_ticked_count > 0 ){
			jQuery(".show_for_jobs").show();
		}else{
			jQuery(".show_for_jobs").hide();
		}

		// show key dropdown option
		if( key_row_ticked_count > 0 ){
			jQuery(".show_for_keys").show();
		}else{
			jQuery(".show_for_keys").hide();
		}

		// show supplier dropdown option
		if( supplier_row_ticked_count > 0 ){
			jQuery(".show_for_supplier").show();
		}else{
			jQuery(".show_for_supplier").hide();
		}

		jQuery("#bot_func_btn_div").show().removeClass('minimise');
        jQuery("#bot_func_btn_inner_div").show();

        jQuery("#minimize_panel").show();
        jQuery("#maximize_panel").hide();
	}else{

		// hide all dropdown options
		jQuery(".show_for_jobs").hide();

		jQuery("#bot_func_btn_div").hide();

	}

}

function update_tech_run_color_table(obj){

	var tr_id = '<?php echo $tr_id; ?>';

	var parent_tr = obj.parents("tr:first");

	var colour_id = parent_tr.find(".ct_trrc_id").val();
	var time = parent_tr.find(".ct_time").val();
	var jobs_num = parent_tr.find(".ct_jobs").val();
	var no_keys = parent_tr.find(".ct_no_keys_chk").prop("checked");
	var no_keys_fin = (no_keys==true)?1:0;
	var booked_jobs = parent_tr.find(".ct_booked_job").val();
	var status_dif = '';
	var isFullyBooked = 0;

	// invoke ajax
	jQuery("#load-screen").show();
	jQuery.ajax({
		type: "POST",
		url: "/tech_run/set_colour_table",
		data: {
			tr_id: tr_id,
			colour_id: colour_id,
			time: time,
			jobs_num: jobs_num,
			no_keys: no_keys_fin,
			booked_jobs: booked_jobs
		}
	}).done(function( ret ){
		
		jQuery("#load-screen").hide();		
		
	});

}

function tr_color_table_update_status(obj){

	var parent_tr = obj.parents("tr:first");

	var jobs_num = parent_tr.find(".ct_jobs").val();
	var booked_jobs = parent_tr.find(".ct_booked_job").val();
	var status_dif = '';
	var isFullyBooked = 0;

	var status_dif = jobs_num-booked_jobs;
	var booking_status = getCTstatusReturnData(status_dif);
	var status_txt = '';

	if(booking_status=='FULL'){
		status_txt = 'FULL';
		isFullyBooked = 1;
	}else{
		status_txt = '-'+status_dif;
	}

	parent_tr.find(".ct_status").val(status_txt);
	parent_tr.find(".ct_fully_booked").val(isFullyBooked);

	<?php
	if( $tech_run_row->run_complete != 1 && $tech_run_row->no_more_jobs != 1 ){ ?>
		hideFullyBookedJobs();
	<?php
	}
	?>

}

function tech_color_table_update_key(obj){

	var parent_tr = obj.parents("tr:first");
	var no_keys = parent_tr.find(".ct_no_keys_chk").prop("checked");

	if( no_keys == true ){
		parent_tr.find(".redCross").show();
	}else{
		parent_tr.find(".redCross").hide();
	}

}

function getCTstatusReturnData(status_dif){

	if( status_dif > 0 ){
		booking_status = '-'+status_dif;
	}else{
		booking_status = 'FULL';
	}
	return booking_status

}

// colour table: hide fully booked script
function hideFullyBookedJobs(){

	jQuery(".ct_fully_booked").each(function(){

		var ct_fully_booked_dom = jQuery(this);
		var parent_tr = ct_fully_booked_dom.parents("tr:first");

		var ct_trrc_id = parent_tr.find(".ct_trrc_id").val();
		var isFullyBooked = ct_fully_booked_dom.val();

		if( isFullyBooked == 1 ){
			jQuery('#tbl_maps tr[data-hlc_id="'+ct_trrc_id+'"]:not(".isBooked")').hide();
		}else{
			jQuery('#tbl_maps tr[data-hlc_id="'+ct_trrc_id+'"]:not(".isBooked")').show();
		}


	});

}

function countNumOfBookedJobsEachColor(){

	jQuery(".ct_booked_job").val(0); // clear them on load, bec shitty firefox autofills them on refresh

	jQuery(".isBooked").each(function(){

		var trrc_id = jQuery(this).find(".trrc_id").val();
		var booked_job = parseInt(jQuery("#ct_row_id_"+trrc_id).find(".ct_booked_job").val());
		var booked_tot = booked_job+1;
		jQuery("#ct_row_id_"+trrc_id).find(".ct_booked_job").val(booked_tot);

	});

}

function updateStatusColourTableBooked(){

	var tr_id = '<?php echo $tr_id; ?>';

	jQuery(".ct_jobs").each(function(){

		var ct_jobs_dom = jQuery(this);
		var parent_tr = ct_jobs_dom.parents("tr:first");

		var colour_id = parseInt(parent_tr.find(".ct_trrc_id").val());
		var time = parent_tr.find(".ct_time").val();
		var num_jobs = parseInt(parent_tr.find(".ct_jobs").val());
		var booked_job = parseInt(parent_tr.find(".ct_booked_job").val());
		var booking_status = '';
		var status_txt = '';
		var isFullyBooked = 0;



		if( time!='' ){

			// calculate status
			var status_dif = num_jobs-booked_job;
			var booking_status = getCTstatusReturnData(status_dif);
			var status_txt = '';

			if(booking_status=='FULL'){
				status_txt = 'FULL';
				isFullyBooked = 1;
			}else{
				status_txt = '-'+status_dif;
			}


			// ajax
			//jQuery("#load-screen").show();
			jQuery.ajax({
				type: "POST",
				url: "/tech_run/update_colour_table_status",
				data: {
					tr_id: tr_id,
					colour_id: colour_id,
					booking_status: booking_status
				}
			}).done(function( ret ){
				// function here
				//jQuery("#load-screen").hide();
			});


			parent_tr.find(".ct_status").val(status_txt);
			parent_tr.find(".ct_fully_booked").val(isFullyBooked);

			<?php
			if( $tech_run_row->run_complete != 1 && $tech_run_row->no_more_jobs != 1 ){ ?>
				hideFullyBookedJobs();
			<?php
			}
			?>

		}


	});

}


var selected_sub_region_arr = [];
function sub_region_tick(sub_region_ms_dom){

	var is_sub_region_ms_ticked = sub_region_ms_dom.prop("checked");		
	var sub_region_ms =  sub_region_ms_dom.val();
	var sub_region_div_chk_dom = sub_region_ms_dom.parents("div.sub_region_div_chk");
	var sub_region_ms_lbl = sub_region_div_chk_dom.find('.sub_region_ms_lbl').text();

	var sub_region_tag_html = ''+
	'<button type="button" class="btn btn-success sub_region_tag sub_region_tag_btn_'+sub_region_ms+'">'+sub_region_ms_lbl+
		'<input type="hidden" name="sub_region_ms_tag[]" value="'+sub_region_ms+'" />'+
		' <span class="fa fa-close"></span>'+
	'</button>'+
	'';

	if( is_sub_region_ms_ticked == true ){ // ticked

		// get currently selected sub regions
		var curr_sel_sub_regions = [];
		jQuery("#sub_region_tag_div .selected_sub_region_ms_tag").each(function(){

			var sub_region_id = jQuery(this).val();

			if( sub_region_id > 0 ){
				curr_sel_sub_regions.push(sub_region_id);
			}		
			
		});
	
		// do not re-add tag that already exist
		if( jQuery.inArray(sub_region_ms, curr_sel_sub_regions) === -1 && sub_region_ms > 0 ){

			if( jQuery.inArray( sub_region_ms, selected_sub_region_arr ) == -1 ){
				
				selected_sub_region_arr.push(sub_region_ms);
				jQuery("#sub_region_tag_div").append(sub_region_tag_html);
									
			}

		}

	}else{ // unticked

					
		var index = selected_sub_region_arr.indexOf(sub_region_ms);
		if (index !== -1) {

			// remove sub region ID from array
			selected_sub_region_arr.splice(index, 1);

			// remove tag
			jQuery(".sub_region_tag_btn_"+sub_region_ms).remove();

		}

	}

	console.log(selected_sub_region_arr);

}

// hidden rows ccount script
function get_hidden_jobs_count(){
	
	var hiddenRowsCount = jQuery("#hiddenRowsCount").val();
	jQuery(".hiddenRowsCount_span").html(hiddenRowsCount);

}

// COPIED FROM OLD STR
// get unique agency from STR page
function getUniqueAgenciesFromTheList(){

	// get unique agency from the list
	var agencies = new Array();
	var ex_agencies = new Array();

	jQuery("#tbl_maps .agency_id").each(function(){
	var agency_id = jQuery(this).val();
	if( jQuery.inArray( agency_id, agencies ) == -1 ){
		agencies.push(parseInt(agency_id));
	}
	});

	<?php
	// add FN agencies
	if( count($fn_agency_sub) > 0 ){
		foreach( $fn_agency_sub as $fn_sub_agency_id ){ ?>
			agencies.push(parseInt(<?php echo $fn_sub_agency_id; ?>));
		<?php
		}
	}
	?>

	<?php
	// add vision agencies
	if( count($vision_agency_sub) > 0 ){
		foreach( $vision_agency_sub as $vision_sub_agency_id ){ ?>
			agencies.push(parseInt(<?php echo $vision_sub_agency_id; ?>));
		<?php
		}
	}
	?>

	//console.log("agencies: "+agencies);
	//console.log("ex_agencies: "+ex_agencies);

	// remove agency not in the list
	jQuery("#keys_agency option").each(function(index){

		var opt = jQuery(this);
		var agency_id = parseInt(opt.val());
		if( index>0 && jQuery.inArray( agency_id, agencies ) == -1 ){
			opt.remove();
		}

	});


}


function hide_close_un_selected_items(){

	var region_filter_div = jQuery("#region_filter_div");
	region_filter_div.find(".sub_region_ms:not(:checked):visible").parents(".sub_region_div_chk").hide(); // hide unticked sub regions
	region_filter_div.find(".region_ms:not(:checked):visible").parents(".region_div_chk").hide(); // hide unticked regions

}

// distance
function calculateDistances(start,destination,row) {

	var service = new google.maps.DistanceMatrixService();
	service.getDistanceMatrix(
	{
		origins: [start],
		destinations: [destination],
		travelMode: google.maps.TravelMode.DRIVING,
		unitSystem: google.maps.UnitSystem.METRIC,
		avoidHighways: false,
	avoidTolls: false
	}, function(response, status){
		distance_callback(response,status,row)
	});

}

function distance_callback(response, status,row) {

	var jtext = "";

	if (status != google.maps.DistanceMatrixStatus.OK) {

		alert('Error was: ' + status);

	}else{

		var origins = response.originAddresses;
		var destinations = response.destinationAddresses;

		for (var i = 0; i < origins.length; i++) {
			var results = response.rows[i].elements;

			for (var j = 0; j < results.length; j++) {


				jtext = ' From: '+origins[i] + ' - To: ' + destinations[j]
				+ ' | Distance: ' + results[j].distance.text + ' | Duration: '
				+ results[j].duration.text + ' - Distance value : '+results[j].duration.value+'\n';
				//console.log(jtext);

				//row.find(".time").html(results[j].duration.text);
				row.find(".distance_to_agency").html(results[j].distance.text);

				/*
				tot_time += parseFloat(results[j].duration.text);
				tot_dis += parseFloat(results[j].distance.text);
				orig_dur += results[j].duration.value;
				
				var totalSec = orig_dur;
				var hours = parseInt( totalSec / 3600 ) % 24;
				var minutes = parseInt( totalSec / 60 ) % 60;
				var seconds = totalSec % 60;
				var time_str = "";
				if(hours==0){
					time_str = minutes+" mins";
				}else{
					time_str = hours+" hours "+minutes+" mins";
				}
				jQuery("#tot_time").html(time_str);
				//jQuery("#tot_time").html(tot_time+" mins");
				jQuery("#tot_dis").html(tot_dis.toFixed(1)+" km");
				*/

				address_index++;
			}
		}

	}

}

function set_sel_jt_button_dynamic_text(){

	var display_jt_btn_dom = jQuery("#display_jt_btn_view");

	if( jQuery(".jt_display_filter").length == jQuery(".jt_display_filter:checked").length ){
		btn_txt = "ALL (Click to Edit)";
	}else{
		btn_txt = "Various (Click to Edit)";
	}

	display_jt_btn_dom.text(btn_txt);
	display_jt_btn_dom.attr("data-orig_btn_txt",btn_txt);

}

function ajax_send_en(trr_id_arr, str_tech, str_tech_name, str_date, en_time_arr)
{
	swal({
		html: true,
		title: "Warning!",
		text: "This will issue Entry Notice on selected jobs, continue?",
		type: "warning",						
		showCancelButton: true,
		confirmButtonClass: "btn-success",
		confirmButtonText: "Yes, Continue",
		cancelButtonClass: "btn-danger",
		cancelButtonText: "No, Cancel!",
		closeOnConfirm: true,
		showLoaderOnConfirm: true,
		closeOnCancel: true
		},
		function(isConfirm2) {

		if (isConfirm2) {							  
			
			$('#load-screen').show(); 
			jQuery.ajax({
				type: "POST",
				url: "/tech_run/issue_en",
				data: {						
					'trr_id_arr': trr_id_arr,
					'str_tech': str_tech,
					'str_tech_name': str_tech_name,
					'str_date': str_date,
					'en_time_arr': en_time_arr
				}
			}).done(function( ret ){

				$('#load-screen').hide(); 
				swal({
					title: "Success!",
					text: "Entry Notice has been Issued!",
					type: "success",
					confirmButtonClass: "btn-success",
					showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
					timer: <?php echo $this->config->item('timer') ?>
				});
				location.reload();						

			});				

		}

	});	
}

jQuery(document).ready(function(){

	function check_if_tech_run_already_exist(date, assigned_tech){
		//This function will check if tech_run exist or not
		//Joe did this ajax request but I just moved it to function for easy to reuse
		jQuery("#load-screen").show();
		jQuery.ajax({
			type: "POST",
			url: "/tech_run/already_exist",
			data: {
				date: date,
				assigned_tech: assigned_tech
			}
		}).done(function( ret ){

			jQuery("#load-screen").hide();
			
			var tr_count = parseInt(ret);
			
			if( tr_count > 0 ){

				jQuery("#tr_already_exist").val(1);					
				swal('','This tech run already exist','error');

			}else{

				jQuery("#tr_already_exist").val(0);

			}

		});
	}
	
	<?php
	// new jobs found popup
	if( $new_jobs_count > 0){ ?>

		swal({
			title: "",
			text: "<?php echo $new_jobs_count ?> new jobs have been found",
			type: "warning",
			confirmButtonClass: "btn-success",
			showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
			timer: <?php echo $this->config->item('timer') ?>
		});

	<?php
	}
	// run on load
	if( $has_tech_run == true ){ ?>

		// get unique agency from STR page
		getUniqueAgenciesFromTheList();

		// hidden rows ccount script
		get_hidden_jobs_count();

		// count number of booked jobs
		countNumOfBookedJobsEachColor();

		// update colour table status
		updateStatusColourTableBooked();

		// set selected job type button text
		set_sel_jt_button_dynamic_text();

	<?php
	}	
	?>

	<?php
	// success message popup
	if( $this->session->flashdata('success') ==  true ){ ?>

		swal({
			title: "Success!",
			text: "Tech Run has been created successfully",
			type: "success",
			confirmButtonClass: "btn-success",
			showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
			timer: <?php echo $this->config->item('timer') ?>
		});

	<?php
	}	
	
	// deleted success
	if( $this->session->flashdata('delete_success') ==  true ){ ?>

		swal({
			title: "Success!",
			text: "Tech Run has been successfully deleted",
			type: "success",
			confirmButtonClass: "btn-success",
			showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
			timer: <?php echo $this->config->item('timer') ?>
		});

	<?php
	}		
	?>
	
	// select current tab
	if( localStorage.getItem('str_curren_tab') != '' ){
		jQuery("#"+localStorage.getItem('str_curren_tab') +"").click();
	}

	// datatable
	$('#tbl_maps').DataTable({
        columnDefs: [{ width: '1%', title:'', targets: 1 }],
        order: [[1, 'asc']]
		/*
		'pageLength': 50,
		'lengthChange': true,
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
		*/
		

		//"bPaginate": false // disable pagination		   

	});

	// accomodation hide/show toggle
	jQuery("#accomodation").change(function(){

		var opt = jQuery(this).val();
		if ( opt == 1 || opt == 2 ){
			jQuery("#sel_acco").show();
		}else{
			jQuery("#sel_acco").hide();
		}

	});

	// checkbox script
	// check all
	jQuery("#check-all").change(function(){

		var dom = jQuery(this);
		var is_ticked = dom.prop("checked");

		if( is_ticked == true ){
			jQuery(".trr_chk").prop("checked",true)
		}else{
			jQuery(".trr_chk").prop("checked",false)
		}

		show_bottom_functions();

	});

	// single checkbox
	jQuery("#tbl_maps").on('change','.trr_chk',function(){

		show_bottom_functions();

	});

	// bottom functions script
	jQuery("#tech_run_functions").change(function(){

		var opt = jQuery(this).val();
		
		jQuery("#bot_func_hide").hide();
		jQuery("#bot_func_assign_dk").hide();
		jQuery("#bot_func_en").hide();
		jQuery("#bot_func_highlight_row").hide();
		jQuery("#bot_func_escalate").hide();
		jQuery("#bot_func_change_tech").hide();
		jQuery("#bot_func_mark_tech_sick").hide();
		jQuery("#bot_func_remove_keys").hide();
		jQuery("#bot_func_remove_suppliers").hide();

		switch(opt){

			case 'hide':
				jQuery("#bot_func_hide").show();
			break; 

			case 'dk':
				jQuery("#bot_func_assign_dk").show();
			break;

			case 'highlight':
				jQuery("#bot_func_highlight_row").show();
			break; 

			case 'escalate':
				jQuery("#bot_func_escalate").show();
			break;

			case 'change_tech':
				jQuery("#bot_func_change_tech").show();
			break;

			case 'mark_tech_sick':
				jQuery("#bot_func_mark_tech_sick").show();
			break;	
			
			case 'keys':
				jQuery("#bot_func_remove_keys").show();
			break;

			case 'suppliers':
				jQuery("#bot_func_remove_suppliers").show();
			break;

			case 'en':
				jQuery("#bot_func_en").show();
			break;

		}

	});

	// minimize bottom process panel
	jQuery("#minimize_panel").click(function(){

		jQuery("#bot_func_btn_inner_div").hide();

		jQuery("#minimize_panel").hide();
		jQuery("#maximize_panel").show();		

		jQuery("#bot_func_btn_div").addClass('minimise');
		

	});

	// show bottom process panel
	jQuery("#maximize_panel").click(function(){

		show_bottom_functions();

		jQuery("#bot_func_btn_inner_div").show();
		
		jQuery("#minimize_panel").show();
		jQuery("#maximize_panel").hide();

        jQuery("#bot_func_btn_div").removeClass('minimise');
		

	});


	// select job type show/hide toggle
	jQuery("#sel_job_type_chk").change(function(){

		var is_ticked = jQuery(this).prop("checked");

		if( is_ticked == true ){
			jQuery(".select_job_type_class").show();
		}else{
			jQuery(".select_job_type_class").hide();

			// untick select job type
			jQuery("#select_job_type").val('');
			jQuery(".trr_chk:visible").prop("checked",false);
			show_bottom_functions();
			
		} 

	});

	// select job type show/hide toggle
	jQuery("#sel_agency_job_chk").change(function(){

		var is_ticked = jQuery(this).prop("checked");

		if( is_ticked == true ){
			jQuery(".select_agency_jobs_class").show();
		}else{
			jQuery(".select_agency_jobs_class").hide();

			// untick agency jobs
			jQuery("#select_agency_jobs").val('');
			jQuery(".trr_chk:visible").prop("checked",false);
			show_bottom_functions();

		} 

	});

	// add key show/hide toggle
	jQuery("#add_key_btn").click(function(){

		var add_key_btn_dom = jQuery(this);
		var orig_btn_txt = 'Add Keys';

		if( add_key_btn_dom.text() == orig_btn_txt ){

			add_key_btn_dom.text('Cancel Add Keys');
			add_key_btn_dom.removeClass('btn-primary');
			add_key_btn_dom.addClass('btn-danger');
			jQuery("#add_key_div").show();

		}else{

			add_key_btn_dom.text(orig_btn_txt);
			add_key_btn_dom.removeClass('btn-danger');
			add_key_btn_dom.addClass('btn-primary');
			jQuery("#add_key_div").hide();

		}		

	});

	// add supplier show/hide toggle
	jQuery("#add_supplier_btn").click(function(){

		var add_key_btn_dom = jQuery(this);
		var orig_btn_txt = 'Add Supplier';

		if( add_key_btn_dom.text() == orig_btn_txt ){

			add_key_btn_dom.text('Cancel Add Supplier');
			add_key_btn_dom.removeClass('btn-primary');
			add_key_btn_dom.addClass('btn-danger');
			jQuery("#add_supplier_div").show();

		}else{

			add_key_btn_dom.text(orig_btn_txt);
			add_key_btn_dom.removeClass('btn-danger');
			add_key_btn_dom.addClass('btn-primary');
			jQuery("#add_supplier_div").hide();

		}	

	});

	
	// region filter script
	jQuery("#region_filter_parent_div").on('keyup click',"#region_filter",function(){

		var region_filter = jQuery(this).val().trim().toLowerCase();

		if( region_filter != '' ){
						
			// default
			jQuery("#region_filter_div").show();
			hide_close_un_selected_items();
			
			jQuery(".sub_region_ms_lbl").each(function(){

				var sub_region_lbl_dom = jQuery(this);
				var sub_region = sub_region_lbl_dom.text().trim().toLowerCase();

				var sub_region_div_chk_dom = sub_region_lbl_dom.parents(".sub_region_div_chk:first");
				var region_div_chk_dom = sub_region_div_chk_dom.parents(".region_div_chk:first");
				var state_div_chk_dom = region_div_chk_dom.parents(".state_div_chk:first");
				
				var position = sub_region.search(region_filter);				
				
				if( position != -1 ){ // found
					
					state_div_chk_dom.show();
					region_div_chk_dom.show();
					sub_region_div_chk_dom.show();

				}

			});

		}else{

			jQuery("#region_filter_div").show();
			jQuery("#region_filter_div .state_div_chk").show();
			hide_close_un_selected_items();

		}			

	});

	// hide when clicking outside script
	jQuery(document).mouseup(function (e){

		var container = jQuery("#region_filter_div");
		if (!container.is(e.target) // if the target of the click isn't the container...
			&& container.has(e.target).length === 0) {
			container.hide();
		}

	});	

	jQuery(".state_ms").change(function(){

		var state_ms_dom = jQuery(this);
		var is_state_ms_ticked = state_ms_dom.prop("checked");	
		var state_div_chk_dom = state_ms_dom.parents(".state_div_chk:first");		

		if( is_state_ms_ticked == true ){
			state_div_chk_dom.find(".region_div_chk").show();
		}else{
			state_div_chk_dom.find(".region_div_chk").hide();
		}

	});
	
	jQuery("#region_filter_div").on("change",".region_ms",function(){

		var region_ms_dom = jQuery(this);
		var is_region_ms_ticked = region_ms_dom.prop("checked");	
		var region_div_chk_dom = region_ms_dom.parents(".region_div_chk:first");	

		if( is_region_ms_ticked == true ){ // ticked

			region_div_chk_dom.find(".sub_region_div .sub_region_ms").prop("checked",true);
			region_div_chk_dom.find(".sub_region_div .sub_region_ms").each(function(){

				var sub_region_ms_dom =  jQuery(this);	
				sub_region_tick(sub_region_ms_dom);					

			});

			region_div_chk_dom.find(".sub_region_div_chk").show();

		}else{ // untick

			region_div_chk_dom.find(".sub_region_div .sub_region_ms").prop("checked",false);
			region_div_chk_dom.find(".sub_region_div .sub_region_ms").each(function(){

				var sub_region_ms_dom =  jQuery(this);		
				var sub_region_ms =  sub_region_ms_dom.val();

				var index = selected_sub_region_arr.indexOf(sub_region_ms);
				if (index !== -1) {

					// remove sub region ID from array
					selected_sub_region_arr.splice(index, 1);

					// remove tag
					jQuery(".sub_region_tag_btn_"+sub_region_ms).remove();

				}

			});

			console.log(selected_sub_region_arr);

			region_div_chk_dom.find(".sub_region_div_chk").hide();			

		}

	});

	jQuery("#region_filter_div").on("click",".sub_region_ms",function(){

		var sub_region_ms_dom =  jQuery(this);	
		sub_region_tick(sub_region_ms_dom);

	});

	jQuery("#sub_region_tag_div").on("click",".sub_region_tag",function(){

		jQuery(this).remove();

	});

	// form validation
	jQuery("#jform").submit(function(){

		var date = jQuery("#date").val();
		var assigned_tech = jQuery("#assigned_tech").val();
		var start_point = jQuery("#start_point").val();
		var end_point = jQuery("#end_point").val();		
		var tr_already_exist = jQuery("#tr_already_exist").val();

		var error = '';

		if( date == ""){
			error += "Date is required\n";
		}

		if( assigned_tech == "" ){
			error += "Technician is required\n";
		}

		if( start_point == "" ){
			error += "Start point is required\n";
		}

		if( end_point == "" ){
			error += "End Point is required\n";
		}

		if( tr_already_exist == 1 ){
				error += "This tech run already exist\n";
		}	

		if( error != "" ){			
			swal('',error,'error');
			return false;
		}else{			
			return true;
		}

	});

	// auto-select accomodation and call agent script
	jQuery("#assigned_tech").change(function(){

		var assigned_tech = jQuery("#assigned_tech").val();

		if( assigned_tech > 0 ){

			jQuery('#load-screen').show(); 
			jQuery.ajax({
				type: "POST",
				url: "/tech_run/get_accomodation_and_booking_staff",
				dataType: 'json',
				data: {
					assigned_tech: assigned_tech
				}
			}).done(function( ret ){

				jQuery('#load-screen').hide(); 

				var accomodation = parseInt(ret.accomodation);
				var call_agent = parseInt(ret.call_agent);

				jQuery("#booking_staff").val(call_agent);
				jQuery("#start_point").val(accomodation);
				jQuery("#end_point").val(accomodation);
				jQuery("#working_hours").val(ret.working_hours);

			});

		}		

	});


		// tech run already exist check
		jQuery("#date, #assigned_tech").change(function(){

			var date = jQuery("#date").val();
			var assigned_tech = jQuery("#assigned_tech").val();
			var orig_date = jQuery("#orig_date").val();
			var orig_assigned_tech = jQuery("#orig_assigned_tech").val();

			if( date != "" && assigned_tech > 0 ){
			//date and assigned_tech is not empty

				<?php if($has_tech_run === false): ?>
				//tech run didn't exist > trigger validation
				check_if_tech_run_already_exist(date, assigned_tech);
				
				<?php else: ?>
				//tech run exist 

				//trigger validation only if has changes in date or assigned_tech
				if(date != orig_date || assigned_tech != orig_assigned_tech){
					check_if_tech_run_already_exist(date, assigned_tech);
				}else{
					//reset tr_already_exist flag
					jQuery("#tr_already_exist").val(0);
				}
	
				<?php endif; ?>

			}

		});

			

	// remove map routes
	jQuery(".delete_btn").click(function(){

		swal({
			title: "Warning!",
			text: "Are you sure you want to delete this tech run?",
			type: "warning",						
			showCancelButton: true,
			confirmButtonClass: "btn-success",
			confirmButtonText: "Yes, Continue",
			cancelButtonClass: "btn-danger",
			cancelButtonText: "No, Cancel!",
			closeOnConfirm: true,
			showLoaderOnConfirm: true,
			closeOnCancel: true
		},
		function(isConfirm) {

			if (isConfirm) {							  
				
				jQuery("#load-screen").show();
				window.location='/tech_run/delete/?tr_id=<?php echo $tr_id; ?>';			

			}

		});		

	});

	// tab remember selected script
	jQuery(".nav-link").click(function(){

		var nav_link_dom = jQuery(this);
		var tab_id = nav_link_dom.attr("id");
		localStorage.setItem('str_curren_tab', tab_id);

	});

	// select uncoloured
	jQuery("#select_uncoloured_chk").change(function(){

		var is_ticked = jQuery(this).prop("checked");

		if( is_ticked == true ){

			jQuery(".NoColor:visible").each(function(){
				jQuery(this).find(".trr_chk:visible").prop("checked",true);
			});

		}else{

			jQuery(".NoColor:visible").each(function(){
				jQuery(this).find(".trr_chk:visible").prop("checked",false);
			});

		}
		
		show_bottom_functions();

	});

	// select holiday/short term rental
	jQuery("#select_holiday_rent_chk").change(function(){

		var is_ticked = jQuery(this).prop("checked");

		if( is_ticked == true ){

			jQuery(".jrow_holiday_rental:visible").each(function(){
				jQuery(this).find(".trr_chk:visible").prop("checked",true);
			});

		}else{

			jQuery(".jrow_holiday_rental:visible").each(function(){
				jQuery(this).find(".trr_chk:visible").prop("checked",false);
			});

		}

		show_bottom_functions();

	});

	// select escalate job
	jQuery("#sel_esc_jobs_chk").change(function(){

		var is_ticked = jQuery(this).prop("checked");

		if( is_ticked == true ){

			jQuery(".jrow_escalate_jobs:visible").each(function(){
				jQuery(this).find(".trr_chk:visible").prop("checked",true);
			});

		}else{

			jQuery(".jrow_escalate_jobs:visible").each(function(){
				jQuery(this).find(".trr_chk:visible").prop("checked",false);
			});

		}

		show_bottom_functions();

	});


	// select no tenant job
	jQuery("#sel_no_tenant_det_chk").change(function(){

		var is_ticked = jQuery(this).prop("checked");

		if( is_ticked == true ){

			jQuery(".no_tenants:visible").each(function(){
				jQuery(this).find(".trr_chk:visible").prop("checked",true);
			});

		}else{

			jQuery(".no_tenants:visible").each(function(){
				jQuery(this).find(".trr_chk:visible").prop("checked",false);
			});

		}

		show_bottom_functions();

	});


	// select job type
	jQuery("#select_job_type_btn").click(function(){

		var select_job_type = jQuery("#select_job_type").val();

		// untick by default
		jQuery(".trr_chk:visible").prop("checked",false);

		jQuery("input.job_type").each(function(){

			var job_type_dom = jQuery(this);
			var job_type = job_type_dom.val();
			var parent_td = job_type_dom.parents("td.chk_col");

			if( job_type == select_job_type ){

				parent_td.find(".trr_chk:visible").prop("checked",true);

			}			

		});

		show_bottom_functions();

	});


	// select agency jobs
	jQuery("#select_agency_jobs_btn").click(function(){

		var select_agency_jobs = jQuery("#select_agency_jobs").val();

		// untick by default
		jQuery(".trr_chk:visible").prop("checked",false);

		jQuery("input.agency_id").each(function(){

			var agency_id_dom = jQuery(this);
			var agency_id = agency_id_dom.val();
			var parent_td = agency_id_dom.parents("td.chk_col");

			if( agency_id == select_agency_jobs ){

				parent_td.find(".trr_chk:visible").prop("checked",true);

			}			

		});

		show_bottom_functions();

	});


	// select first visit
	jQuery("#sel_first_visit_chk").change(function(){

		var is_ticked = jQuery(this).prop("checked");

		if( is_ticked == true ){

			jQuery(".jrow_first_visit:visible").each(function(){
				jQuery(this).find(".trr_chk:visible").prop("checked",true);
			});

		}else{

			jQuery(".jrow_first_visit:visible").each(function(){
				jQuery(this).find(".trr_chk:visible").prop("checked",false);
			});

		}

		show_bottom_functions();

	});


	// hide function
	jQuery("#hide_btn").click(function(){

		var tr_id = '<?php echo $tr_id; ?>';
		var trr_id_arr = [];
		var isBooked = false;

		jQuery(".trr_chk:checked:visible").each(function(){

			var trr_chk_dom = jQuery(this);
			var trr_id = trr_chk_dom.val();
			if( trr_id > 0 ){
				trr_id_arr.push(trr_id);
			}
			

			var jt = trr_chk_dom.parents("tr:first").find(".job_type").val();
			if( jt == "Booked" ){
				isBooked = true;
			}

		});

		if( isBooked == true ){

			swal('',"Booked jobs can't be hidden",'error');

		}else{

			if( trr_id_arr.length > 0 ){

				swal({
					html: true,
					title: "Warning!",
					text: "Are you sure you want to <b>Hide</b> all selected items?",
					type: "warning",						
					showCancelButton: true,
					confirmButtonClass: "btn-success",
					confirmButtonText: "Yes, Continue",
					cancelButtonClass: "btn-danger",
					cancelButtonText: "No, Cancel!",
					closeOnConfirm: true,
					showLoaderOnConfirm: true,
					closeOnCancel: true
				},
				function(isConfirm) {

					if (isConfirm) {							  
						
						$('#load-screen').show(); 
						jQuery.ajax({
							type: "POST",
							url: "/tech_run/hide_tech_run_rows",
							data: {
								tr_id: tr_id,
								trr_id_arr: trr_id_arr,
								operation: 'hide'
							}
						}).done(function( ret ){

							$('#load-screen').hide(); 
							location.reload();						

						});				

					}

				});	

			}			

		}

	});



	// Escalate jobs
	jQuery("#escalate_jobs_btn").click(function(){

		var job_id_arr = [];

		jQuery(".trr_chk:checked:visible").each(function(){

			var trr_chk_dom = jQuery(this);
			var parent_td = trr_chk_dom.parents("td.chk_col");
			var job_id = parent_td.find('.job_id').val();
			
			if( job_id > 0 ){
				job_id_arr.push(job_id);
			}
			

		});

		if( job_id_arr.length > 0 ){

			swal({
				html: true,
				title: "Warning!",
				text: "Are you sure you want to <b>Escalate</b> all selected items?",
				type: "warning",						
				showCancelButton: true,
				confirmButtonClass: "btn-success",
				confirmButtonText: "Yes, Continue",
				cancelButtonClass: "btn-danger",
				cancelButtonText: "No, Cancel!",
				closeOnConfirm: true,
				showLoaderOnConfirm: true,
				closeOnCancel: true
			},
			function(isConfirm) {

				if (isConfirm) {							  
					
					$('#load-screen').show(); 
					jQuery.ajax({
						type: "POST",
						url: "/tech_run/escalate_jobs",
						data: {
							job_id_arr: job_id_arr
						}
					}).done(function( ret ){

						$('#load-screen').hide(); 
						location.reload();						

					});				

				}

			});	

		}		

	});



	// assign color
	jQuery("#btn_assign_color").click(function(){

		var tr_id = '<?php echo $tr_id; ?>';
		var trr_id_arr = [];
		var trr_hl_color = jQuery("#row_highlight_color").val();
		var error = '';

		jQuery(".trr_chk:checked:visible").each(function(){

			var trr_chk_dom = jQuery(this);
			var trr_id = trr_chk_dom.val();
			if( trr_id > 0 ){
				trr_id_arr.push(trr_id);
			}

		});

		if( trr_hl_color == '' ){
			error += "Please select a colour\n";
		}

		if( error != '' ){

			swal('',error,'error');
			
		}else{

			if( trr_id_arr.length > 0 ){

				swal({
					html: true,
					title: "Warning!",
					text: "Are you sure you want to <b>Assign Color</b> all selected items?",
					type: "warning",						
					showCancelButton: true,
					confirmButtonClass: "btn-success",
					confirmButtonText: "Yes, Continue",
					cancelButtonClass: "btn-danger",
					cancelButtonText: "No, Cancel!",
					closeOnConfirm: true,
					showLoaderOnConfirm: true,
					closeOnCancel: true
				},
				function(isConfirm) {

					if (isConfirm) {							  
						
						$('#load-screen').show(); 
						jQuery.ajax({
							type: "POST",
							url: "/tech_run/highlight_row",
							data: {
								tr_id: tr_id,
								trr_id_arr: trr_id_arr,
								trr_hl_color: trr_hl_color
							}
						}).done(function( ret ){

							$('#load-screen').hide();
							location.reload();						

						});				

					}

				});	

			}

		}		

	});


	// remove color
	jQuery("#btn_remove_color").click(function(){

		var tr_id = '<?php echo $tr_id; ?>';
		var trr_id_arr = [];
		var trr_hl_color = jQuery("#row_highlight_color").val();
		var error = '';

		jQuery(".trr_chk:checked:visible").each(function(){

			var trr_chk_dom = jQuery(this);
			var trr_id = trr_chk_dom.val();
			if( trr_id > 0 ){
				trr_id_arr.push(trr_id);
			}

		});

		if( trr_id_arr.length > 0 ){

			swal({
				html: true,
				title: "Warning!",
				text: "Are you sure you want to <b>Remove Color</b> to all selected items?",
				type: "warning",						
				showCancelButton: true,
				confirmButtonClass: "btn-success",
				confirmButtonText: "Yes, Continue",
				cancelButtonClass: "btn-danger",
				cancelButtonText: "No, Cancel!",
				closeOnConfirm: true,
				showLoaderOnConfirm: true,
				closeOnCancel: true
			},
			function(isConfirm) {

				if (isConfirm) {							  
					
					$('#load-screen').show(); 
					jQuery.ajax({
						type: "POST",
						url: "/tech_run/remove_color",
						data: {
							tr_id: tr_id,
							trr_id_arr: trr_id_arr
						}
					}).done(function( ret ){

						$('#load-screen').hide();
						location.reload();						

					});				

				}

			});	

		}		

	});


	// Change Technician	
	jQuery("#change_tech_update_btn").click(function(){

		
		var tr_id = '<?php echo $tr_id; ?>';
		var trr_id_arr = [];		
		var error = '';
		var has_not_booked = false;

		var change_tech = jQuery("#change_tech_dp").val();
		
		// checkbox loop
		jQuery(".trr_chk:checked:visible").each(function(){

			var trr_chk_dom = jQuery(this);
			var trr_id = trr_chk_dom.val();

			var parent_td = trr_chk_dom.parents("td.chk_col");
			var parent_tr = trr_chk_dom.parents("tr.tech_run_row_tr:first");
			
			var row_id_type = parent_td.find('.row_id_type').val();
			var job_status = parent_td.find('.job_status').val();			
			
			if( row_id_type == 'job_id' && job_status == "Booked"  ){

				if( trr_id > 0 ){
					trr_id_arr.push(trr_id);
				}
				
			}else{

				has_not_booked = true;
				parent_tr.addClass('bg-warning');
				
			}

		});

		if( change_tech == '' ){
			error += "Please Pick Technician to update to.\n";
		}

		if( has_not_booked == true ){
			error += 'Row highlighted as yellow are jobs that are not "booked" so it cannot proceed with the change tech, please untick them or update job as "booked" refresh and then try again.\n';
		}

		if( error !='' ){
			swal('',error,'error');
		}else{

			if( trr_id_arr.length > 0 ){

				swal({
					html: true,
					title: "Warning!",
					text: "Are you sure you want to <b>Change Tech</b> to all selected items?",
					type: "warning",						
					showCancelButton: true,
					confirmButtonClass: "btn-success",
					confirmButtonText: "Yes, Continue",
					cancelButtonClass: "btn-danger",
					cancelButtonText: "No, Cancel!",
					closeOnConfirm: true,
					showLoaderOnConfirm: true,
					closeOnCancel: true
				},
				function(isConfirm) {

					if (isConfirm) {							  
						
						$('#load-screen').show(); 
						jQuery.ajax({
							type: "POST",
							url: "/tech_run/change_tech",
							data: {
								tr_id: tr_id,
								trr_id_arr: trr_id_arr,
								change_tech: change_tech
							}
						}).done(function( ret ){

							$('#load-screen').hide();
							location.reload();						

						});				

					}

				});	

			}

		}				

	});


	// assign DK
	jQuery("#assign_dk_btn").click(function(){

		var job_id_arr = [];
		var assigned_tech = '<?php echo $tech_run_row->assigned_tech; ?>';
		var date = '<?php echo $tech_run_row->date; ?>';
		var agency_no_dk_arr = [];
		var prop_no_dk_arr = [];

		jQuery(".trr_chk:checked:visible").each(function(){

			var trr_chk_dom = jQuery(this);
			var trr_id = trr_chk_dom.val();

			var parent_td = trr_chk_dom.parents("td.chk_col");
			var parent_tr = trr_chk_dom.parents("tr.tech_run_row_tr:first");

			var job_id = parent_td.find('.job_id').val();		
			var row_id_type = parent_td.find('.row_id_type').val();
			var prop_no_dk = parent_td.find('.prop_no_dk').val();
			var agency_no_dk = parent_td.find('.agency_no_dk').val();			
			var prop_address = parent_td.find('.prop_address').val();
			var agency_name = parent_td.find('.agency_name').val();
			var no_dk = false;
			
			
			if( row_id_type == 'job_id' && job_id > 0  ){					
				
				// property does not allow DK
				if( prop_no_dk == 1 ){

					prop_no_dk_arr.push(prop_address);
					no_dk = true;

				}
				
				// agency does not allow DK
				if( agency_no_dk == 0 ){ 
				
					agency_no_dk_arr.push(agency_name);
					no_dk = true;

				}

				
				if( no_dk == true ){
					
					parent_tr.addClass('bg-warning');

				}else{

					job_id_arr.push(job_id);

				}
				
				
			}								

		});

		var prop_no_dk_arr_unique = [...new Set(prop_no_dk_arr)];
		var agency_no_dk_arr_unique = [...new Set(agency_no_dk_arr)];

		if( prop_no_dk_arr_unique.length > 0 || agency_no_dk_arr_unique.length > 0 ){

			var error_txt = "Cannot proceed to process door knocks because these properties/agencies that are highlighted yellow does not allow it: \n";

			// property no DK
			if( prop_no_dk_arr_unique.length > 0 ){

				error_txt += "\nProperties: \n";
				prop_no_dk_arr_unique.forEach(function(prop){
					error_txt += prop+"\n";
				});

			}			
			
			// agency no DK
			if( prop_no_dk_arr_unique.length > 0 ){

				error_txt += "\nAgencies: \n";
				agency_no_dk_arr_unique.forEach(function(agency){				
					error_txt += agency+"\n";				
				});

			}
			
			swal('',error_txt,'error');

		}else{

			if(  job_id_arr.length > 0 ){

				swal({
					html: true,
					title: "Warning!",
					text: "Are you sure you want to <b>Door Knocks</b> all selected items?",
					type: "warning",						
					showCancelButton: true,
					confirmButtonClass: "btn-success",
					confirmButtonText: "Yes, Continue",
					cancelButtonClass: "btn-danger",
					cancelButtonText: "No, Cancel!",
					closeOnConfirm: true,
					showLoaderOnConfirm: true,
					closeOnCancel: true
				},
				function(isConfirm) {

					if (isConfirm) {							  
						
						$('#load-screen').show(); 
						jQuery.ajax({
							type: "POST",
							url: "/tech_run/assign_dk",
							data: {
								job_id_arr: job_id_arr,
								assigned_tech: assigned_tech,
								date: date
							}
						}).done(function( ret ){

							$('#load-screen').hide(); 
							location.reload();						

						});				

					}

				});	

			}

		}				

	});


	// Mark Tech Sick
	jQuery("#mark_tech_sick").click(function(){

		var job_id_arr = [];

		jQuery(".trr_chk:checked:visible").each(function(){

			var trr_chk_dom = jQuery(this);
			var parent_td = trr_chk_dom.parents("td.chk_col");
			var job_id = parent_td.find('.job_id').val();
			
			if( job_id > 0 ){
				job_id_arr.push(job_id);
			}			

		});

		if( job_id_arr.length > 0 ){

			swal({
				html: true,
				title: "Warning!",
				text: "Are you sure you want to <b>Mark Tech Sick</b> all selected items?",
				type: "warning",						
				showCancelButton: true,
				confirmButtonClass: "btn-success",
				confirmButtonText: "Yes, Continue",
				cancelButtonClass: "btn-danger",
				cancelButtonText: "No, Cancel!",
				closeOnConfirm: true,
				showLoaderOnConfirm: true,
				closeOnCancel: true
			},
			function(isConfirm) {

				if (isConfirm) {							  
					
					$('#load-screen').show(); 
					jQuery.ajax({
						type: "POST",
						url: "/tech_run/mark_tech_sick",
						data: {
							job_id_arr: job_id_arr
						}
					}).done(function( ret ){

						$('#load-screen').hide(); 
						location.reload();						

					});				

				}

			});	

		}		

	});

	// update colour table "time" column
	jQuery(".ct_time").change(function(){

		var obj = jQuery(this);
		update_tech_run_color_table(obj); // update tech run colour db data

	});

	// colour table
	jQuery(".ct_jobs").change(function(){

		var obj = jQuery(this);
		update_tech_run_color_table(obj); // update tech run colour db data
		tr_color_table_update_status(obj); // update status column display

	});

	// update colour table "no keys" column
	jQuery(".ct_no_keys_chk").change(function(){

		var obj = jQuery(this);
		tech_color_table_update_key(obj); // update no key icon display toggle
		update_tech_run_color_table(obj); // update tech run colour db data

	});	

	// update booking notes
	jQuery("#notes").change(function(){

		var tr_id = '<?php echo $tr_id; ?>';
		var notes = jQuery(this).val();
		var notes_ts_div = jQuery("#notes_timestamp_div");

		jQuery("#load-screen").show();
		jQuery.ajax({
			type: "POST",
			url: "/tech_run/update_notes",
			dataType: 'json',
			data: {
				tr_id: tr_id,
				notes: notes
			}
		}).done(function( ret ){

			jQuery("#load-screen").hide();
			notes_ts_div.find('#updates_by').html(ret.notes_updated_by);
			notes_ts_div.find('#updated_ts').html(ret.notes_updated_ts);

		});

	});


	// update tech notes
	jQuery("#tech_notes").change(function(){

		var tr_id = '<?php echo $tr_id; ?>';
		var tech_notes = jQuery(this).val();
		var notes_ts_div = jQuery("#notes_timestamp_div");

		jQuery("#load-screen").show();
		jQuery.ajax({
			type: "POST",
			url: "/tech_run/update_tech_notes",
			dataType: 'json',
			data: {
				tr_id: tr_id,
				tech_notes: tech_notes
			}
		}).done(function( ret ){

			jQuery("#load-screen").hide();
			notes_ts_div.find('#updates_by').html(ret.notes_updated_by);
			notes_ts_div.find('#updated_ts').html(ret.notes_updated_ts);

		});

	});


	// sort by suburb 
	jQuery("#sort").change(function(){

		var tr_id = '<?php echo $tr_id; ?>';
		var sort = jQuery(this).val();
		var sort_by_txt = '';

		if( sort == 1 ){
			sort_by_txt = 'Colour';
		}else if( sort == 2 ){
			sort_by_txt = 'Street';
		}else if( sort == 3 ){
			sort_by_txt = 'Suburb';
		}	

		if( sort > 0 && tr_id > 0 ){

			jQuery("#load-screen").show();
			jQuery.ajax({
				type: "POST",
				url: "/tech_run/sort",
				data: {
					tr_id: tr_id,
					sort_by: sort
				}
			}).done(function( ret ){
				
				jQuery("#load-screen").hide();
				location.reload();

			});	

		}			

	});

	
	// check calendar entry
	jQuery("#assigned_tech").change(function(){

		var assigned_tech = jQuery(this).val();
		var date = jQuery("#date").val();

		if( date!='' ){

			// invoke ajax
			jQuery("#load-screen").show();
			jQuery.ajax({
				type: "POST",
				url: "/tech_run/get_existing_calendar",
				dataType: 'json',
				data: {
					assigned_tech: assigned_tech,
					date: date
				}
			}).done(function( ret ){

				jQuery("#load-screen").hide();
				if( parseInt(ret.calendar_id) > 0 ){

					jQuery("#calendar_id").val(ret.calendar_id);
					jQuery("#calendar").val(ret.region);

				}

			});

		}


	});
	

	<?php
	// show only if tech run exist
	if( $has_tech_run == true ){ ?>

		// add key
		jQuery("#add_key_submit_btn").click(function(){

			var tr_id = '<?php echo $tr_id; ?>';
			var keys_agency = jQuery("#keys_agency").val();
			var agency_addresses_id_dp = jQuery("#keys_agency option:selected").attr("data-agency_addresses_id");
			var agency_addresses_id = ( agency_addresses_id_dp > 0 )?agency_addresses_id_dp:0;
			var error = "";

			if( keys_agency == "" ){
				error += "Agency is required\n";
			}

			if(error!=""){
				swal('',error,'error');
			}else{

				jQuery("#load-screen").show();
				jQuery.ajax({
					type: "POST",
					url: "/tech_run/add_key",
					data: {
						'tr_id': tr_id,
						'keys_agency': keys_agency,
						'assigned_tech': '<?php echo $tech_run_row->assigned_tech; ?>',
						'date': '<?php echo $tech_run_row->date; ?>',
						'agency_addresses_id': agency_addresses_id
					}
				}).done(function( ret ){

					jQuery("#load-screen").hide();
					location.reload();

				});

			}

		});

	<?php
	}
	?>	


	jQuery("#add_supplier_submit_btn").click(function(){

		var tr_id = '<?php echo $tr_id; ?>';
		var supplier = jQuery("#supplier").val();
		var error = "";

		if( supplier=="" ){
			error += "Supplier is required";
		}

		if(error!=""){
			swal('',error,'error');
		}else{

			jQuery("#load-screen").show();
			jQuery.ajax({
				type: "POST",
				url: "/tech_run/add_supplier",
				data: {
					tr_id: tr_id,
					supplier: supplier
				}
			}).done(function( ret ){
				
				jQuery("#load-screen").hide();
				location.reload();

			});

		}

	});


	jQuery(".run_status").click(function(){

		var tr_id = '<?php echo $tr_id; ?>';
		var assigned_tech = '<?php echo $tech_run_row->assigned_tech; ?>';
		var date = '<?php echo $tech_run_row->date; ?>';
		var booking_staff = jQuery("#booking_staff").val();

		var run_status_dom = jQuery(this);
		var tech_run_field = run_status_dom.attr("data-tech_run-field");
		var update_to = ( run_status_dom.hasClass('btn-success') == true )?0:1;
		var run_status_name = run_status_dom.text();

		var mark_text = ( update_to == 1 )?'mark':'unmark';


		swal({
				html: true,
				title: "Warning!",
				text: "This will "+mark_text+" run status as '"+run_status_name+"', proceed?",
				type: "warning",						
				showCancelButton: true,
				confirmButtonClass: "btn-success",
				confirmButtonText: "Yes, Continue",
				cancelButtonClass: "btn-danger",
				cancelButtonText: "No, Cancel!",
				closeOnConfirm: true,
				showLoaderOnConfirm: true,
				closeOnCancel: true
			},
			function(isConfirm) {

				if (isConfirm) {							  
					
					jQuery("#load-screen").show();
					jQuery.ajax({
						type: "POST",
						url: "/tech_run/status_update",
						data: {
							tr_id: tr_id,
							tech_run_field: tech_run_field,
							update_to: update_to,
							run_status_name: run_status_name,
							assigned_tech: assigned_tech,
							date: date,
							booking_staff: booking_staff
						}
					}).done(function( ret ){
						
						jQuery("#load-screen").hide();
						location.reload();

					});

				}

			});

		

	});


	// remove keys
	jQuery("#remove_keys_btn").click(function(){

		var tr_id = '<?php echo $tr_id; ?>';
		var trr_id_arr = [];
		var trk_id_arr = [];
		var has_not_key_ticked = false;

		jQuery(".trr_chk:checked:visible").each(function(){

			var trr_chk_dom = jQuery(this);
			var parent_tr = trr_chk_dom.parents("tr:first");

			var trr_id = trr_chk_dom.val();			
			var trk_id = parent_tr.find('.trk_id').val();
			var row_type = trr_chk_dom.attr("data-row-type");
			

			if( row_type == 'key' ){ // key
				
				if( trr_id > 0 ){
					trr_id_arr.push(trr_id);
				}	
				
				if( trk_id > 0 ){
					trk_id_arr.push(trk_id);
				}

			}else{ // not key

				has_not_key_ticked = true;	
				parent_tr.addClass('bg-warning');

			}
			

		});

		
		if( has_not_key_ticked == true ){
			swal('','Row higlighted yellow are not keys, please untick them','error');
		}else{

			if( trr_id_arr.length > 0 ){

				swal({
					html: true,
					title: "Warning!",
					text: "Are you sure you want to remove selected keys?",
					type: "warning",						
					showCancelButton: true,
					confirmButtonClass: "btn-success",
					confirmButtonText: "Yes, Continue",
					cancelButtonClass: "btn-danger",
					cancelButtonText: "No, Cancel!",
					closeOnConfirm: true,
					showLoaderOnConfirm: true,
					closeOnCancel: true
				},
				function(isConfirm) {

					if (isConfirm) {							  
						
						$('#load-screen').show(); 
						jQuery.ajax({
							type: "POST",
							url: "/tech_run/remove_keys",
							data: {
								tr_id: tr_id,
								trr_id_arr: trr_id_arr,
								trk_id_arr: trk_id_arr
							}
						}).done(function( ret ){

							$('#load-screen').hide(); 
							location.reload();						

						});				

					}

				});	

			}

		}			

	});


	// remove supplier
	jQuery("#remove_suppliers_btn").click(function(){

		var tr_id = '<?php echo $tr_id; ?>';
		var trr_id_arr = [];
		var trs_id_arr = [];
		var has_not_supplier_ticked = false;

		jQuery(".trr_chk:checked:visible").each(function(){

			var trr_chk_dom = jQuery(this);
			var parent_tr = trr_chk_dom.parents("tr:first");

			var trr_id = trr_chk_dom.val();			
			var trs_id = parent_tr.find('.trs_id').val();
			var row_type = trr_chk_dom.attr("data-row-type");
			

			if( row_type == 'supplier' ){ // supplier
				
				if( trr_id > 0 ){
					trr_id_arr.push(trr_id);
				}	
				
				if( trs_id > 0 ){
					trs_id_arr.push(trs_id);
				}

			}else{ // not key

				has_not_supplier_ticked = true;	
				parent_tr.addClass('bg-warning');

			}
			

		});

		
		if( has_not_supplier_ticked == true ){
			swal('','Row higlighted yellow are not supplier, please untick them','error');
		}else{

			if( trr_id_arr.length > 0 ){

				swal({
					html: true,
					title: "Warning!",
					text: "Are you sure you want to remove selected suppliers?",
					type: "warning",						
					showCancelButton: true,
					confirmButtonClass: "btn-success",
					confirmButtonText: "Yes, Continue",
					cancelButtonClass: "btn-danger",
					cancelButtonText: "No, Cancel!",
					closeOnConfirm: true,
					showLoaderOnConfirm: true,
					closeOnCancel: true
				},
				function(isConfirm) {

					if (isConfirm) {							  
						
						$('#load-screen').show(); 
						jQuery.ajax({
							type: "POST",
							url: "/tech_run/remove_supplier",
							data: {
								tr_id: tr_id,
								trr_id_arr: trr_id_arr,
								trs_id_arr: trs_id_arr
							}
						}).done(function( ret ){

							$('#load-screen').hide(); 
							location.reload();						

						});				

					}

				});	

			}

		}			

	});

	
	// invoke table DND
	jQuery("#tbl_maps").tableDnD({

		onDrop: function(table, row) {

			var job_id = jQuery.tableDnD.serialize({
				'serializeRegexp': null
			});

			//jQuery("#load-screen").show();
			jQuery.ajax({
				type: "GET",
				url: "/tech_run/ajax_sort_tech_run/?tr_id=<?php echo $this->input->get_post('tr_id'); ?>&"+job_id
			}).done(function( ret ){

				//jQuery("#load-screen").hide();

			});

		}

	});

	// hidden jobs toggle
	jQuery(".hidden_jobs_toggle_btn").click(function(){

		var tr_id = '<?php echo $tr_id; ?>';

		var btn_dom = jQuery(this);
		var show_hidden = ( btn_dom.hasClass("btn-secondary") == true )?1:0;

		swal({

			html: true,
			title: "Warning!",
			text: "This will show hidden jobs, continue?",
			type: "warning",						
			showCancelButton: true,
			confirmButtonClass: "btn-success",
			confirmButtonText: "Yes, Continue",
			cancelButtonClass: "btn-danger",
			cancelButtonText: "No, Cancel!",
			closeOnConfirm: true,
			showLoaderOnConfirm: true,
			closeOnCancel: true
		},
		function(isConfirm) {

			if (isConfirm) {							  
				
				$('#load-screen').show(); 
				jQuery.ajax({
					type: "POST",
					url: "/tech_run/hidden_jobs_toggle",
					data: {
						tr_id: tr_id,
						show_hidden: show_hidden
					}
				}).done(function( ret ){

					$('#load-screen').hide(); 
					location.reload();						

				});				

			}

		});	

	});



	// EN script
	jQuery(".en_btn").click(function(){

		var btn_dom = jQuery(this);
		var orig_btn_text = 'Entry Notice';

		if( btn_dom.text() == orig_btn_text ){

			// hide row
			jQuery(".EN_show_elem").show();			
			jQuery(".jrow_escalate_jobs").hide(); // hide escalate job on EN
			jQuery(".invalid_en_icon").show();

			// hide checkbox
			jQuery(".hide_chk_on_en").hide();

			jQuery("#tbl_maps .str_header_row th").addClass('bg-success');
			jQuery("#tbl_maps .str_header_row th").addClass('text-white');

			// update EN button text
			btn_dom.text('Cancel');			

		}else{

			// hide row
			jQuery(".EN_show_elem").hide();			
			jQuery(".jrow_escalate_jobs").show(); // redisplay the hidden escalate job upon exiting EN view
			jQuery(".invalid_en_icon").hide();

			// hide checkbox
			jQuery(".hide_chk_on_en").show();

			jQuery("#tbl_maps .str_header_row th").removeClass('bg-success');
			jQuery("#tbl_maps .str_header_row th").removeClass('text-white');

			// update EN button text
			btn_dom.text(orig_btn_text);

		}
		

	});

	<?php
	// show only if tech run exist
	if( $has_tech_run == true ){ ?> 

		// issue EN
		jQuery("#issue_en_btn").click(function(){

			var btn_dom = jQuery(this);
			
			var str_tech = '<?php echo $tech_run_row->assigned_tech; ?>';
			var str_tech_name = '<?php echo $this->system_model->formatStaffName($tech_run_row->tech_sa_fname,$tech_run_row->tech_sa_lname); ?>';
			var str_date = '<?php echo $tech_run_row->date; ?>';
			var trr_id_arr = [];
			var en_time_arr = [];
			var prpo_id_arr = [];

			jQuery(".trr_chk:checked:visible").each(function(){

				var trr_chk_dom = jQuery(this);
				var parent_tr = trr_chk_dom.parents("tr:first");

				var trr_id = trr_chk_dom.val();
				var en_time = parent_tr.find(".en_time").val();
				var prop_id = parent_tr.find(".property_id").val();

				if( trr_id > 0 ){

					trr_id_arr.push(trr_id);					
					en_time_arr.push(en_time);

					prpo_id_arr.push(prop_id);
					
				}

			});

			if( trr_id_arr.length > 0 ){

				$('#load-screen').show(); 
				jQuery.ajax({
					type: "POST",
					url: "/tech_run/ajax_set_tech_run_property_api_tenant_mismatch_check",
					data: {						
						'prop_id': prpo_id_arr
					}
				}).done(function( ret ){

					$('#load-screen').hide(); 

					if(ret != ""){
						swal({
							html: true,
							title: "Warning",
							text: ret,
							type: "warning",
							showCancelButton: true,
							confirmButtonClass: "btn-success",
							confirmButtonText: "Issue EN anyway",
							cancelButtonText: "Cancel!",
							closeOnConfirm: false,
							closeOnCancel: true,
							cancelButtonClass: "btn-danger"
						}, function(isConfirm){

							if(isConfirm){
								ajax_send_en(trr_id_arr, str_tech, str_tech_name, str_date, en_time_arr);
							}
						});	
					}else{
						//No mismatch warning message send EN 
						ajax_send_en(trr_id_arr, str_tech, str_tech_name, str_date, en_time_arr);
					}
					
				});	

			}

		});

	<?php	
	}
	?>	


	// hidden jobs toggle
	jQuery(".jt_display_filter").change(function(){

		var tr_id = '<?php echo $tr_id; ?>';

		var btn_dom = jQuery(this);
		var is_ticked = ( btn_dom.prop("checked") == true )?1:0;
		var job_type = btn_dom.val();

		$('#load-screen').show(); 
		jQuery.ajax({
			type: "POST",
			url: "/tech_run/job_type_toggle",
			data: {
				tr_id: tr_id,
				is_ticked: is_ticked,
				job_type: job_type
			}
		}).done(function( ret ){

			$('#load-screen').hide(); 
			location.reload();						

		});	

	});		

	// hidden jobs toggle
	jQuery(".working_hours").change(function(){

		var tr_id = '<?php echo $tr_id; ?>';

		var working_hours_dom = jQuery(this);
		var working_hours = working_hours_dom.val();

		$('#load-screen').show(); 
		jQuery.ajax({
			type: "POST",
			url: "/tech_run/update_working_hours",
			data: {
				tr_id: tr_id,
				working_hours: working_hours
			}
		}).done(function( ret ){

			$('#load-screen').hide(); 
			//location.reload();						

		});	

	});	

	// get distance
	jQuery(".btn_display_distance").click(function(){

		address_index = 1;
		tot_time = 0;
		tot_dis = 0;
		orig_dur = 0;

		jQuery(".p_address").each(function(index){

			var dom = jQuery(this);
			var row = dom.parents("tr:first");

			//var orig = dom.parents("tr:first").prev('tr').find('.address').html();
			var p_address = dom.text();
			//console.log('p_address :'+p_address);
			var a_address = row.find('.a_address').text();
			//console.log('a_address :'+a_address);
					
			setTimeout(function(){

				// dunno how to pass variables on callback functions
				calculateDistances(p_address,a_address,row);

			}, 1000);					

		});

	});

	// hidden jobs toggle
	jQuery(".time_of_day_save_icon").click(function(){

		var time_of_day_save_icon_dom = jQuery(this);
		var parent_tr = time_of_day_save_icon_dom.parents("tr.tech_run_row_tr:first");

		var job_id = parent_tr.find('.job_id').val();
		var time_of_day = parent_tr.find('.time_of_day_hid').val();

		jQuery('#load-screen').show(); 
		jQuery.ajax({
			type: "POST",
			url: "/tech_run/update_time_of_day",
			data: {
				job_id: job_id,
				time_of_day: time_of_day
			}
		}).done(function( ret ){

			jQuery('#load-screen').hide(); 
			//location.reload();
			
			// time of day label
			parent_tr.find(".time_of_day_link").text(time_of_day);
			parent_tr.find(".time_of_day_link").show();

			// time of day hidden field and save icon
			parent_tr.find(".time_of_day_hid").hide();
			parent_tr.find(".time_of_day_save_icon").hide();

		});	

	});	

	// time of day update
	jQuery(".time_of_day_link").click(function(){

		var time_of_day_link_dom = jQuery(this);
		var parent_td = time_of_day_link_dom.parents("td.time_of_day_td:first");

		time_of_day_link_dom.hide();

		// time of day hidden field and save icon
		parent_td.find(".time_of_day_hid").show();
		parent_td.find(".time_of_day_save_icon").show();

	});

	// display job types toggle
	jQuery("#display_jt_btn_view").click(function(){

		var btn_dom = jQuery(this);
		var orig_btn_txt = btn_dom.attr("data-orig_btn_txt");

		if( btn_dom.text() == orig_btn_txt  ){

			btn_dom.text('Cancel');
			btn_dom.removeClass('btn-primary');
			btn_dom.addClass('btn-danger');
			jQuery("#display_jt_div").show();

		}else{

			btn_dom.text(orig_btn_txt);
			btn_dom.removeClass('btn-danger');
			btn_dom.addClass('btn-primary');
			jQuery("#display_jt_div").hide();

		}		

	});

	// refresh
	jQuery(".refresh_btn").click(function(){
		location.reload();
	});

	jQuery(".filter_agency_btn").click(function(){

		// launch fancybox
		$.fancybox.open({
			src  : '#filter_agency_filter_fb'
		});

	});

	jQuery("#agency_filter_btn_save").click(function(){
        // launch fancybox
        $.fancybox.close({
            src  : '#filter_agency_filter_fb'
        });
        $('#load-screen').show();
        var tr_id = '<?php echo $tr_id; ?>';

		var agency_id_arr = [];
		jQuery(".agency_filter_chk:checked").each(function(){

			var obj =  jQuery(this);
			var agency_id = obj.val();

			if( agency_id > 0 ){
				agency_id_arr.push(agency_id);
			}
			
		});


		jQuery.ajax({
			type: "POST",
			url: "/tech_run/agency_filter_update",
			data: {
				'tr_id': tr_id,
				'agency_id_arr': agency_id_arr
			}
		}).done(function( ret ){
			// Reload the page,as its faster than submitting the data of the page again
			window.location.reload();
		});			

	});
	
	// select multiple checkbox via shift key
	var multi_sel_chk = jQuery('.trr_chk:visible'); // checkbox DOM
	select_multiple_checkbox_via_shift_key(multi_sel_chk);
	
});
</script>