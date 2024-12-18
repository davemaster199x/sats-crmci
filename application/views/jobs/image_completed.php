<style>
	.col-mdd-3{
		max-width:12.5%;
		padding-left:10px;
		padding-right:10px;
	}
	.col-mdd-2{
		max-width:9.6%;
		padding-left:10px;
		padding-right:10px;
		position: relative;
		width: 100%;
		min-height: 1px;
	}
	.a_link.asc{
    top:3px;
	}
	.a_link.desc{
			top:-3px;
	}
	.fa-sort-up:before, .fa-sort-asc:before {
			content: "\f0de";
	}
</style>
<div class="box-typical box-typical-padding">

	<?php
	// breadcrumbs template
	$bc_items = array(
		array(
			'title' => 'Reports',
			'link' => "/reports"
		),
		array(
			'title' => $title,
			'status' => 'active',
			'link' => $uri
		)
	);
	$bc_data['bc_items'] = $bc_items;
	$this->load->view('templates/breadcrumbs', $bc_data);
	?>

	<header class="box-typical-header">

		<div class="box-typical box-typical-padding">
			<?php
		$form_attr = array(
			'id' => 'jform'
		);
		echo form_open('/jobs/image_completed',$form_attr);
		?>
			<div class="for-groupss row">
				<div class="col-lg-12 col-md-12 columns">
					<div class="row">

						<div class="col-mdd-2">
							<label>Job Type</label>
							<select id="job_type_filter" name="job_type_filter" class="form-control">
								<option value="">ALL</option>
							</select>
							<div class="mini_loader"></div>
						</div>

						<div class="col-mdd-2">
							<label>Service</label>
							<select id="service_filter" name="service_filter" class="form-control">
								<option value="">ALL</option>
							</select>
							<div class="mini_loader"></div>
						</div>
						
						<div class="col-mdd-2">
							<label for="state"><?php echo $this->gherxlib->getDynamicState($this->config->item('country')); ?></label>
							<select id="state_filter" name="state_filter" class="form-control ">
								<option value="">ALL</option>
							</select>
							<div class="mini_loader"></div>
						</div>

						<!-- State or Region -->
						<div class="col-mdd-2">
						
							<div class="fl-left region_filter_main_div">
								<label>	
								<?php 
									$defaultCountry = $this->config->item('country');
									echo $this->customlib->getDynamicRegionViaCountry($defaultCountry); 
								?>:
								</label>
								<input type="text" name="region_filter_state" id='region_filter_state' class="form-control region_filter_state" placeholder="ALL" readonly="readonly" />
								
								<div id="region_dp_div" class="box-typical region_dp_div">
								
									<div class="region_dp_header">										
									</div>
									
									<div class="region_dp_body">								
									</div>
									
								</div>	
								
							</div>
					
						</div>
						<!--
						<div class="col-mdd-2">
							<label for="date_select">Date</label>
							<input name="date_filter" class="flatpickr form-control flatpickr-input" data-allow-input="true" id="flatpickr" type="text" placeholder="ALL" value="<?php echo $this->input->get_post('date_filter'); ?>">
						</div>
						-->
						<div class="col-mdd-3">
							<label for="date_select">Date</label>
							<?php 
								if(!empty($_GET['date_filter_from']) && !empty($_GET['date_filter_to'])){
									//$tmp_date_from = $_GET['date_filter_from'];2021-12-01
									$tmp_date_from = $_GET['date_filter_from'];
									$tmp_date_to   = $_GET['date_filter_to'];
									$date_from = date('d-m-Y', strtotime($tmp_date_from));
									$date_to = date('d-m-Y', strtotime($tmp_date_to));
								}
								else{
									$date_from = $this->input->get_post('date_filter_from');
									$date_to   = $this->input->get_post('date_filter_to');
								}
							?>
							<input style="width: 100% !important" name="date_filter_from" class="flatpickr form-control flatpickr-input" data-allow-input="true" id="flatpickr" type="text" placeholder="FROM" value="<?php echo $date_from; ?>" autocomplete="off">
						</div>

						<div class="col-mdd-3">
						<label for="date_select">&nbsp;</label>
							<input style="width: 100% !important" name="date_filter_to" class="flatpickr form-control flatpickr-input" data-allow-input="true" id="flatpickr" type="text" placeholder="TO" value="<?php echo $date_to; ?>" autocomplete="off">
						</div>

						<div class="col-mdd-2">
							<label for="search">Tech</label>
							<select id="tech_filter" name="tech_filter" class="form-control">
								<option value="">ALL</option>
								<?php 
									foreach($tech_filter->result_array() as $row){
										if(!empty($row['StaffID'])){
											$sel = ($this->input->get_post('tech_filter') == $row['StaffID']) ? 'selected="true"' :NULL;
								?>
											<option <?php echo $sel; ?> value="<?php echo $row['StaffID'] ?>"><?php echo $row['FirstName']." ".$row['LastName'] ?></option>
								<?php
										}
									}
								?>
							</select>
						</div>

						<div class="col-mdd-2">
							<label for="search">Phrase</label>
							<input type="text" name="search_filter" class="form-control" placeholder="ALL" value="<?php echo $this->input->get_post('search_filter'); ?>" />
						</div>

						<div class="col-md-1 columns">
							<label class="col-sm-12 form-control-label">&nbsp;</label>
							<button type="submit" class="btn btn-inline">Search</button>
						</div>
						
					</div>

				</div>
			</div>
			</form>
		</div>
	</header>

	<section>
		<div class="body-typical-body">
			<div class="table-responsive">
				<table class="table table-hover main-table">
					<thead>
						<tr>
							<th>
								Date
								<a data-toggle="tooltip" class="a_link <?php echo $sort ?>" href="<?php echo "/jobs/image_completed?sort_header=1&order_by=created_date&sort={$toggle_sort}&".http_build_query($header_link_params); ?>">
										<em class="fa fa-sort-<?php echo $sort; ?>"></em>
								</a>
							</th>
							<th>Job Type</th>
							<th>Age</th>
							<th>Service</th>
							<th>Price</th>
							<th>Address</th>
							<th><?php echo $this->gherxlib->getDynamicState($this->config->item('country')); ?></th>
							<th>Agency</th>
							<th>Job#</th>
							<th>
								Compliant
								<a data-toggle="tooltip" class="a_link <?php echo $sort ?>" href="<?php echo "/jobs/image_completed?sort_header=1&order_by=compliant&sort={$toggle_sort}&".http_build_query($header_link_params); ?>">
										<em class="fa fa-sort-<?php echo $sort; ?>"></em>
								</a>
							</th>
							<th>Status</th>
							<th>In Airtable</th>
						</tr>
					</thead>

					<tbody>
						<?php foreach($lists->result_array() as $list_item): 	
						
						// $row_color = '';
						// if alarms 240v or 240vli are expired
						// if( $this->system_model->findExpired240vAlarm($list_item['jid']) == true ){	
						// 	$row_color = "redRowBg";			
						// }
						
						// urgent jobs							
						// if($list_item['urgent_job']==1){
						// 	$row_color = "greenRowBg";
						// }
						
						// jobs not completed
						// if($list_item['job_reason_id']>0){
						// 	$row_color = "yellowRowBg";
						// }

						?>
						<tr class="tbl_list_tr <?php // echo $row_color; ?>">
							<td><?php echo ($this->system_model->isDateNotEmpty($list_item['j_date']))?date('d/m/Y', strtotime($list_item['j_date'])):''; ?></td>
							<td><?php echo $this->gherxlib->getJobTypeAbbrv($list_item['j_type']); ?></td>
							<td><?php echo $this->gherxlib->getAge($list_item['j_created']);  ?></td>
							<td>
								<?=Alarm_job_type_model::icons($list_item['j_service']);?>
							</td>
							<td>$<?php echo number_format($this->system_model->price_ex_gst($list_item['j_price']),2); ?></td>
							<td>
								<?php 
									$prop_address = $list_item['p_address_1']." ".$list_item['p_address_2'].", ".$list_item['p_address_3'];
									echo $this->gherxlib->crmLink('vpd',$list_item['prop_id'],$prop_address);
								?>
							</td>
							<td><?php echo $list_item['p_state']; ?></td>
							<td class="<?php echo ( $list_item['priority'] > 0 )?'j_bold':null; ?>">
								<a href="/agency/view_agency_details/<?php echo $list_item['a_id']; ?>">
									<?php echo $list_item['agency_name']." ".( ( $list_item['priority'] > 0 )?' ('.$list_item['abbreviation'].')':null ); ?>
								</a>
							</td>
              				<td><?php echo $this->gherxlib->crmLink('vjd',$list_item['jid'],$list_item['jid']); ?></td>
							<td><?php echo ( $list_item['prop_comp_with_state_leg'] == 1 && $list_item['prop_upgraded_to_ic_sa'] == 1 )?"<span class='text-success'>Yes</span>":"<span class='text-danger'>No</span>"; ?></td>
							<td><?php echo $list_item['j_status']; ?></td>
							<td>
									<div class="checkbox">
											<input type="checkbox" class="in_airtable" id="in_airtable_<?php echo $list_item['jid']; ?>" value="<?php echo $list_item['jid']; ?>" />
											<label for="in_airtable_<?php echo $list_item['jid']; ?>"></label>
									</div>
							</td>
						</tr>
						<?php endforeach ?>
					</tbody>

				</table>
			</div>

			<nav aria-label="Page navigation example" style="text-align:center">
				<?php echo $pagination; ?>
			</nav>

			<div class="pagi_count text-center"><?php echo $pagi_count; ?></div>

		</div>
	</section>

</div>

<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >

	<h4>Image - Completed Jobs</h4>
	<p>This page shows jobs that are currently booked in the system</p>
	<p>Price are exclusive of GST.</p>
<pre><code><?php echo $page_query; ?></code></pre>

</div>
<!-- Fancybox END -->

<script type="text/javascript">


	// agency
	function run_ajax_agency_filter(){

	var json_data = <?php echo $agency_filter_json; ?>;
	var searched_val = '<?php echo $this->input->get_post('agency_filter'); ?>';

	jQuery('#agency_filter').next('.mini_loader').show();
	jQuery.ajax({
		type: "POST",
			url: "/sys/header_filters",
			data: { 
				rf_class: 'jobs',
				header_filter_type: 'agency',
				json_data: json_data,
				searched_val: searched_val
			}
		}).done(function( ret ){	
			jQuery('#agency_filter').next('.mini_loader').hide();
			$('#agency_filter').append(ret);
		});
				
	}

	// job type	
	function run_ajax_job_filter(){

		var json_data = <?php echo $job_type_filter_json; ?>;
		var searched_val = '<?php echo $this->input->get_post('job_type_filter'); ?>';

		jQuery('#job_type_filter').next('.mini_loader').show();
		jQuery.ajax({
			type: "POST",
				url: "/sys/header_filters",
				data: { 
					rf_class: 'jobs',
					header_filter_type: 'job_type',
					json_data: json_data,
					searched_val: searched_val
				}
			}).done(function( ret ){	
				jQuery('#job_type_filter').next('.mini_loader').hide();
				jQuery('#job_type_filter').append(ret);
			});
					
	}

	// service
	function run_ajax_service_filter(){

	var json_data = <?php echo $service_filter_json; ?>;
	var searched_val = '<?php echo $this->input->get_post('service_filter'); ?>';

	jQuery('#service_filter').next('.mini_loader').show();
	jQuery.ajax({
		type: "POST",
			url: "/sys/header_filters",
			data: { 
				rf_class: 'jobs',
				header_filter_type: 'service',
				json_data: json_data,
				searched_val: searched_val
			}
		}).done(function( ret ){	
			jQuery('#service_filter').next('.mini_loader').hide();
			$('#service_filter').append(ret);
		});
				
	}

	// state
	function run_ajax_state_filter(){

	var json_data = <?php echo $state_filter_json; ?>;
	var searched_val = '<?php echo $this->input->get_post('state_filter'); ?>';

	jQuery('#state_filter').next('.mini_loader').show();
	jQuery.ajax({
		type: "POST",
			url: "/sys/header_filters",
			data: { 
				rf_class: 'jobs',
				header_filter_type: 'state',
				json_data: json_data,
				searched_val: searched_val
			}
		}).done(function( ret ){	
			jQuery('#state_filter').next('.mini_loader').hide();
			$('#state_filter').append(ret);
		});
				
	}

jQuery(document).ready(function() { // Document ready start

	// run headler filter ajax
	run_ajax_job_filter();
	run_ajax_service_filter();
	run_ajax_state_filter();
	run_ajax_agency_filter();


	// region filter selection, cant trigger without the timeout, dunno why :( 
		<?php
		if( !empty($this->input->get_post('sub_region_ms')) ){ ?>
			setTimeout(function(){ 
				jQuery("#region_filter_state").click();
			}, 500);		
		<?php
		}
		?>

	//REGION FILTER AJAX
	jQuery('.region_filter_main_div').on('click','.region_filter_state',function(){
			
			var obj  = jQuery(this);
			var state_chk = obj.prop("checked");
			var region_filter_json = <?php echo $region_filter_json; ?>;
			var state_ms_json = <?php echo $state_ms_json; ?>;
			
			jQuery("#load-screen").show();
			
			jQuery.ajax({
				type: "POST",
				url: "/sys/getRegionFilterState",
				data: { 
					rf_class: 'jobs',
					region_filter_json: region_filter_json
				}
			}).done(function( ret ){
				
				jQuery("#load-screen").hide();
				jQuery(".region_dp_header").html(ret);
				
				// searched
				var state_ms_json_num = state_ms_json.length;
				if( state_ms_json_num > 0 ){				
					for( var i=0; i < state_ms_json_num; i++ ){
						jQuery("#region_dp_div .state_ms[value='"+state_ms_json[i]+"']").click();
					}
				}
				
				
			});
					
		});
		
		// state click
		jQuery('.region_dp_div').on('click','.state_ms',function(){
			
			var obj  = jQuery(this);
			var state = obj.val();
			var state_chk = obj.prop("checked");
			var region_filter_json = <?php echo $region_filter_json; ?>;
			var region_ms_json = <?php echo $region_ms_json; ?>;
			
			if(state_chk==true){
				
				obj.parents(".state_div:first").find(".rf_state_lbl").addClass("rf_select");
				jQuery("#load-screen").show();
				
				jQuery.ajax({
					type: "POST",
					url: "/sys/getMainRegion",
					data: { 
						state: state,
						rf_class: 'jobs',
						region_filter_json: region_filter_json
					}
				}).done(function( ret ){
					
					jQuery("#load-screen").hide();
					obj.parents(".state_div:first").find(".region_div").html(ret);

					// searched
					var region_ms_json_num = region_ms_json.length;
					if( region_ms_json_num > 0 ){				
						for( var i=0; i < region_ms_json_num; i++ ){
							obj.parents(".state_div:first").find(".region_ms[value='"+region_ms_json[i]+"']").click();
						}
					}
					
				});
				
			}else{
				obj.parents(".state_div:first").find(".rf_state_lbl").removeClass("rf_select");
				obj.parents(".state_div:first").find(".region_div").html('');			
			}	
					
		});
		
		
		// region click
		jQuery('.region_dp_div').on('click','.region_ms',function(){
			
			var obj  = jQuery(this);
			var region_id = obj.val();
			var state_chk = obj.prop("checked");
			var region_filter_json = <?php echo $region_filter_json; ?>;
			var sub_region_ms_json = <?php echo $sub_region_ms_json; ?>;
			
			if(state_chk==true){
				
				obj.parents(".region_div_chk:first").find(".rf_region_lbl").addClass("rf_select");
				jQuery("#load-screen").show();
				
				jQuery.ajax({
					type: "POST",
					url: "/sys/getSubRegion",
					data: { 
						region_id: region_id,
						rf_class: 'jobs',
						region_filter_json: region_filter_json
					}
				}).done(function( ret ){
					
					jQuery("#load-screen").hide();
					obj.parents(".region_div_chk:first").find(".sub_region_div").html(ret);

					// searched
					var sub_region_ms_json_num = sub_region_ms_json.length;
					if( sub_region_ms_json_num > 0 ){				
						for( var i=0; i < sub_region_ms_json_num; i++ ){
							obj.parents(".region_div_chk:first").find(".sub_region_ms[value='"+sub_region_ms_json[i]+"']").click();
						}
					}
					
				});
				
				
			}else{
				obj.parents(".region_div_chk:first").find(".rf_region_lbl").removeClass("rf_select");
				obj.parents(".region_div_chk:first").find(".sub_region_div").html('');
			}	
					
		});
		
		// sub region 
		jQuery('.region_dp_div').on('click','.sub_region_ms',function(){
			
			var obj  = jQuery(this);
			var region_id = obj.val();
			var state_chk = obj.prop("checked");
			
			if(state_chk==true){			
				obj.parents(".sub_region_div_chk:first").find(".rf_sub_region_lbl").addClass("rf_select");			
			}else{
				obj.parents(".sub_region_div_chk:first").find(".rf_sub_region_lbl").removeClass("rf_select");
			}	
					
		});


        jQuery(".in_airtable").change(function(){

            var dom = jQuery(this);
            var job_id = dom.val();
            var is_ticked = dom.prop("checked");

            if( is_ticked == true && job_id > 0 ){

                $('#load-screen').show(); //show loader
                jQuery.ajax({
                    type: "POST",
                    url: "/jobs/ajax_save_in_airtable",
                    data: { 
                        job_id: job_id,
						ticked_from: 'completed'
                    }
                }).done(function( ret ){

                    
                    $('#load-screen').hide(); //hide loader
                    /*
                    swal({
                        title:"Success!",
                        text: "Assigned success",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>

                    });
                    setTimeout(function(){ window.location='/jobs/ageing_jobs_90'; }, <?php echo $this->config->item('timer') ?>);
                    */
                    
                        
                });

            }
            	

        });


}) // Document ready end
</script>