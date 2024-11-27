<div class="text-left"><header class="box-typical-header">

		<div class="box-typical box-typical-padding">
			<?php
		$form_attr = array(
			'id' => 'jform'
		);
		echo form_open("/agency/view_agency_details/{$agency_id}/$tab?prop_type={$prop_type}",$form_attr);


		$export_links_params_arr = array(
			'search_filter' => $this->input->post('search_filter'),
			'prop_status' => $this->input->post('prop_status'),
			'service_type' => $this->input->post('service_type'),
			'pm_id' => $this->input->post('pm_id'),
			'prop_type' => $prop_type
		);
		$export_link_params = "/agency/view_agency_details/{$agency_id}/{$tab}?export=1&".http_build_query($export_links_params_arr);
		?>
			<div class="for-groupss row">
				<div class="col-md-9 columns">
					<div class="row">					
						<div class="col-md-2">
							<label for="phrase_select">Phrase</label>
							<input type="text" name="search_filter" class="form-control" placeholder="ALL" value="<?php echo $this->input->get_post('search_filter'); ?>" />
                        </div>
                        
                        <div class="col-md-2">
							<label for="agency_select">Property Status</label>
							<select id="prop_status" name="prop_status"  class="form-control field_g2">
                                <option value="-1">ALL</option>
							    <option value="0" <?php echo($this->input->get_post('prop_status')!=""&&$this->input->get_post('prop_status')==0)?'selected="selected"':''; ?>>Active</option>
							    <option value="1" <?php echo($this->input->get_post('prop_status')!=""&&$this->input->get_post('prop_status')==1)?'selected="selected"':''; ?>>Inactive</option>
							</select>
						</div>

						  <div class="col-md-3">
							<label for="service_type">Service Type</label>
							<select id="service_type" name="service_type"  class="form-control field_g2">
                                <option value="">ALL</option>
								  <?php foreach($agency_services as $agency_services_row) { ?>
									<option <?php echo ($this->input->get_post('service_type')==$agency_services_row['ajt_id']) ? "selected='true'" : null; ?> value="<?php echo $agency_services_row['ajt_id'] ?>"><?php echo $agency_services_row['type'] ?></option>
								  <?php } ?>
							</select>
						</div>

						<div class="col-md-3">
							<label for="service_type">Property Manager</label>
							<select id="pm_id" name="pm_id"  class="form-control field_g2">
                                <option value="">ALL</option>
                                <option  <?php echo ($this->input->get_post('pm_id')=='0') ? "selected='true'" : null; ?> value="0">No PM assigned</option>
								  <?php foreach($pm_list->result_array() as $pm_row) { 
									  if($pm_row['pm_fname']!="" && $pm_row['pm_id_new']!=""){
									?>
									<option <?php echo ($this->input->get_post('pm_id')==$pm_row['pm_id_new']) ? "selected='true'" : null; ?> value="<?php echo $pm_row['pm_id_new'] ?>"><?php echo "{$pm_row['pm_fname']} {$pm_row['pm_lname']}" ?></option>
								  <?php }} ?>
							</select>
						</div>

						<div class="col-md-1 columns">
							<label class="col-sm-12 form-control-label">&nbsp;</label>
							<button type="submit" class="btn btn-inline">Search</button>
						</div>
					</div>
                </div>
                
                <div class="col-md-3 columns">
                    <section class="proj-page-section float-right">
                        <div class="proj-page-attach">
                            <i class="fa fa-file-excel-o"></i>
                            <p class="name">Properties</p>
                            <p>
								<a href="<?php echo $export_link_params; ?>" target="blank">
									Export
								</a>
                            </p>
                        </div>
					</section>
				</div>
			</div>
			</form>
		</div>
    </header>
    
    <section>
		<div class="body-typical-body">

			<section class="tabs-section">

            	<div class="tabs-section-nav tabs-section-nav-icons">
					<div class="tbl">
						<ul class="nav prop_nav" role="tablist">
							<li class="nav-item">
								<a class="nav-link <?php echo ($prop_type==1 || !$prop_type)?'active':'not-active' ?>"  href="/agency/view_agency_details/<?php echo $agency_id.'/'.$tab ?>?prop_type=1">
									<span class="nav-link-in">
										<i class="fa fa-calendar-check-o"></i>
											Annual Service
									</span>
								</a>
							</li>	
							<li class="nav-item">
								<a class="nav-link <?php echo ($prop_type==3)?'active':'not-active' ?>"  href="/agency/view_agency_details/<?php echo $agency_id.'/'.$tab ?>?prop_type=3">
									<span class="nav-link-in">
										<i class="fa fa-calendar-times-o"></i>
											Once-off Service
									</span>
								</a>
							</li>
							<li class="nav-item red">
								<a  class="nav-link <?php echo ($prop_type==2)?'active':'not-active' ?>"   href="/agency/view_agency_details/<?php echo $agency_id.'/'.$tab ?>?prop_type=2">
									<span class="nav-link-in">
										<span class="fa fa-hourglass-end"></span>
										Not Serviced by <span class="uppercase"><?= $this->config->item('theme') ?></span>
									</span>
								</a>
							</li>	
						</ul>
					</div>
				</div>

				<div class="tab-content">
					<?php 
						if($prop_type==1){ ##Load Sats page/tab

							$this->load->view('/agency/tab/vad_properties_tab/vad_prop_sats.php');

						}elseif($prop_type==2){ ##Load Non Sats page/tab

							$this->load->view('/agency/tab/vad_properties_tab/vad_prop_nonsats.php');

						}elseif($prop_type==3){ ##Load Onceoff

							$this->load->view('/agency/tab/vad_properties_tab/vad_prop_onceoff.php');

						}
					?>

					<div id="change_prop_and_job_service_fb" class="fancybox" style="display:none;">

							<section class="card card-blue-fill">
								<header class="card-header">Change Property and Job Service Type</header>
								<div class="card-block">

									<table class="table table-borderless">	
									
										<tr>
											<th>Current Service</th>
											<td>
												<span id="from_service_type_name"></span>
												<img id="service_type_icon" />
												<span id="from_service_type_price_var_breakdown"></span>		
												<input type="hidden" id="from_service_dynamic_price_total" />
											</td>
										</tr>
											
										<tr>
											<th>New Service</th>
											<td>
												<select id="to_service_type" class="form-control">
													<option value="">---</option>
													<?php
													$agency_serv_sql = $this->db->query("
													SELECT 
														ageny_serv.`agency_services_id`,
														ajt.`id` AS ajt_id,
														ajt.`type` AS service_type
													FROM `agency_services` AS ageny_serv
													LEFT JOIN `alarm_job_type` AS ajt ON ageny_serv.`service_id` = ajt.`id`
													WHERE ageny_serv.`agency_id` = {$agency_id}
													");

													foreach( $agency_serv_sql->result() as $agency_row ){ 
														
														// service type
														$new_service_type = $agency_row->service_type; 

														// service price, from variation
														$price_var_params = array(
															'service_type' => $agency_row->ajt_id,
															'agency_id' => $agency_id
														);
														$price_var_arr = $this->system_model->get_agency_price_variation($price_var_params);
														$price_breakdown_text = $price_var_arr['price_breakdown_text'];
														$dynamic_price_total = number_format($price_var_arr['dynamic_price_total'],2);
														?>
														<option 
															value="<?php echo $agency_row->ajt_id; ?>"
															data-service_type="<?php echo $new_service_type; ?>"
															data-price_breakdown_text="<?php echo $price_breakdown_text; ?>"
															data-dynamic_price_total="<?php echo $dynamic_price_total; ?>"
														>
															<?php  echo "{$new_service_type} {$price_breakdown_text}"; ?>
														</option>
													<?php
													}
													?>
												</select>
											</td>
										</tr>

										<tr>
											<th>Update Last YM Completed Job Service</th>
											<td>
												<div class="checkbox">
													<input type="checkbox" name="update_last_ym_comp_job_serv_chk" id="update_last_ym_comp_job_serv_chk" value="1" checked />
													<label for="update_last_ym_comp_job_serv_chk">&nbsp;</label>
												</div>
											</td>
										</tr>

										<!--
										<tr>
											<th>Update Last YM Completed Job Price</th>
											<td>
												<div class="checkbox">
													<input type="checkbox" name="update_last_ym_comp_job_price_chk" id="update_last_ym_comp_job_price_chk" value="1" checked />
													<label for="update_last_ym_comp_job_price_chk">&nbsp;</label>
												</div>
											</td>
										</tr>
										-->

										<tr>
											<th>Update Non-Completed Job Service</th>
											<td>
												<div class="checkbox">
													<input type="checkbox" name="update_any_non_comp_job_serv_chk" id="update_any_non_comp_job_serv_chk" value="1" checked />
													<label for="update_any_non_comp_job_serv_chk">&nbsp;</label>
												</div>
											</td>
										</tr>

										<tr>
											<th>Update Non-Completed Job Price</th>
											<td>
												<div class="checkbox">
													<input type="checkbox" name="update_any_non_comp_job_price_chk" id="update_any_non_comp_job_price_chk" value="1" checked />
													<label for="update_any_non_comp_job_price_chk">&nbsp;</label>
												</div>
											</td>
										</tr>

									</table>

									<div class="text-right mt-3">
										<div class="float-left font-italic">All price variations will be removed EXCEPT Multi-Property Discount</div>	
										<input type="hidden" id="from_service_type" name="from_service_type" />									
										<button type="button" id="update_property_and_job_service_type_process_btn" class="btn float-right">Update</button>														
									</div>

								</div>
							</section>

							


						
						
					</div>

				</div>
               
        	</section>

			

		</div>
    </section>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function(){

			//check all toggle tweak
			$('#check-all').on('change',function(){
				var obj = $(this);
				var isChecked = obj.is(':checked');
				var divbutton = $('#mbm_box');
				if(isChecked){
					divbutton.show();
					$('.prop_chk').prop('checked',true);
				}else{
					divbutton.hide();
					$('.prop_chk').prop('checked',false);
				}
			})

			//check sing checkbox toggle tweak
			$('.prop_chk').on('change',function(){
				var obj = $(this);
				var isLength = $('.prop_chk:checked').length;
				var divbutton = $('#mbm_box');
				if(isLength>0){
					divbutton.show();
				}else{
					divbutton.hide();
				}
			})


			/**Change Agency */
			$('#btn_change_agency').on('click', function(){

				var agency = $('#sel_agency').val();
				var pm = $('#pm_v2').val();

				var props = [];
				jQuery(".prop_chk:checked").each(function(){
					props.push(jQuery(this).val());	
				});

				var error = "";
				var submitCount = 0;

				if(agency==""){
					error+="Agency must not be empty\n";
				}

				if(error!=""){
					swal('',error,'error');
					return false;
				}

				// invoke ajax
				jQuery("#load-screen").show();
				jQuery.ajax({
					type: "POST",
					url: "/agency/ajax_update_property_agency",
					dataType: 'json',
					data: { 
						current_agency: <?php echo $agency_id; ?>,
						new_agency: agency,
						props: props,
						pm: pm
					}
				}).done(function( ret ){
					if(ret.status){
						jQuery("#load-screen").hide();
						swal({
							title:"Success!",
							text: "Changed Agency Successful",
							type: "success",
							showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
							timer: <?php echo $this->config->item('timer') ?>
						});
						setTimeout(function(){ location.reload(); }, <?php echo $this->config->item('timer') ?>);	
					}
				});	

			})


			$('#btn_assign_pm').on('click', function(){

				var pm = $('#sel_pm').val();
				var props = [];
				jQuery(".prop_chk:checked").each(function(){
					props.push(jQuery(this).val());	
				});
				
				var error = "";

				if(pm==""){
					error+="Property Manager must not be empty\n";
				}

				if(error!=""){
					swal('',error,'error');
					return false;
				}

				jQuery("#load-screen").show();
				jQuery.ajax({
					type: "POST",
					url: "/agency/assign_pm",
					dataType: 'json',
					data: { 
						agency_id: <?php echo $agency_id; ?>,
						props: props,
						pm: pm
					}
				}).done(function( ret ){
					if(ret.status){
						jQuery("#load-screen").hide();
						swal({
							title:"Success!",
							text: "Assign PM Successful",
							type: "success",
							showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
							timer: <?php echo $this->config->item('timer') ?>
						});
						setTimeout(function(){ location.reload(); }, <?php echo $this->config->item('timer') ?>);	
					}
				});	

			})

			$('.btn_assign').click(function(e){
				e.preventDefault();
				
				var type = $(this).attr('data-val');
				
				if(type=='change_pm'){
					$('.gbox_main_change_pm').show();
					$('.gbox_main_change_agency').hide();
				}else if(type=='change_agency'){
					$('.gbox_main_change_agency').show();
					$('.gbox_main_change_pm').hide();
				}

			})

			$('#sel_agency').change(function(){

				var agency_id = $(this).val();

				if( $(this).val()!="" ){

					//request ajax > get agency PM
					jQuery("#load-screen").show();
					jQuery.ajax({
						type: "POST",
						url: "/property_ajax/property_mod/get_property_manager_by_agency_id",
						data: {
							agency_id: agency_id
						}
					}).done(function( ret ){
						jQuery("#load-screen").hide();
						$('#pm_v2').html(''); //clear pm dropdwon first
						$('#pm_v2').append(ret);
						//$('#pm_v2 option:first').text('No PM assigned');

						//show PM dropdown
						$('.pm_box_v2').show();
					});	

				}else{
					$('.pm_box_v2').hide();
				}
				
			})

			// show lightbox
			jQuery("#change_prop_and_job_service_btn").click(function(){

				var prop_serv_id_arr = [];
				var prop_serv_id_arr_obj = [];

				jQuery(".prop_chk:checked").each(function(){

					var prop_chk_dom = jQuery(this);
					var parent_tr = prop_chk_dom.parents("tr:first");

					var prop_service_type = parent_tr.find(".prop_service_type");		

					prop_service_type.each(function(){

						var prop_service_type_dom = jQuery(this);
						var parent_td = prop_service_type_dom.parents("td:first");

						var prop_service_type_val = prop_service_type_dom.val();
						var prop_service_type_name = parent_td.find('.prop_service_name').val();	
						var price_variation_breakdown = parent_td.find('.price_variation_breakdown').val();	
						var dynamic_price_total = parent_td.find('.dynamic_price_total').val();
						var service_type_icon = parent_td.find('.service_type_icon img').attr('src');

						console.log("prop_service_type: "+prop_service_type_val);
						console.log("prop_service_type_name: "+prop_service_type_name);
						console.log("price_variation_breakdown: "+price_variation_breakdown);
						console.log("dynamic_price_total: "+dynamic_price_total);
						console.log("service_type_icon: "+service_type_icon);

						// store service type
						if( prop_service_type_val > 0 ){

							if( jQuery.inArray(prop_service_type_val,prop_serv_id_arr) === -1 ){

								var service_type_obj = {
									'service_type_id': prop_service_type_val,
									'service_type_name': prop_service_type_name,
									'price_variation_breakdown': price_variation_breakdown,
									'dynamic_price_total': dynamic_price_total,
									'service_type_icon': service_type_icon
								};
								prop_serv_id_arr_obj.push(service_type_obj);

								prop_serv_id_arr.push(prop_service_type_val);								

							}

						}	

					});																			

				});
				
				
				if( prop_serv_id_arr_obj.length == 1 ){

					// "FROM" field data
					jQuery("#from_service_type_name").text(prop_serv_id_arr_obj[0].service_type_name);
					jQuery("#service_type_icon").attr('src',prop_serv_id_arr_obj[0].service_type_icon);
					jQuery("#from_service_type_price_var_breakdown").text(prop_serv_id_arr_obj[0].price_variation_breakdown);
					jQuery("#from_service_dynamic_price_total").val(prop_serv_id_arr_obj[0].dynamic_price_total);
					jQuery("#from_service_type").val(prop_serv_id_arr_obj[0].service_type_id);

					// hide FROM service type
					jQuery("#to_service_type option[value|='"+prop_serv_id_arr_obj[0].service_type_id+"']").hide();

					// launch fancybox
					jQuery.fancybox.open({
						src  : '#change_prop_and_job_service_fb'
					});

				}else{
						
					swal('','Can only process one service type','error');	

				}						

			});


			// update process
			jQuery("#update_property_and_job_service_type_process_btn").click(function(){
				
				var agency_id = '<?php echo $agency_id ?>';
				var prop_id_arr = [];

				var change_prop_and_job_service_fb = jQuery("#change_prop_and_job_service_fb");
				var error = '';

				jQuery(".prop_chk:checked").each(function(){

					var prop_chk_dom = jQuery(this);
					var parent_tr = prop_chk_dom.parents("tr:first");

					var prop_chk = parent_tr.find(".prop_chk").val();										

					// store property ID
					if( prop_chk > 0 ){

						prop_id_arr.push(prop_chk);

					}									

				});

				if( prop_id_arr.length > 0 ){	

					var from_service_type = change_prop_and_job_service_fb.find('#from_service_type').val();
					var to_service_type_dom = change_prop_and_job_service_fb.find('#to_service_type')  // service type DOM object
					var to_service_type = to_service_type_dom.val(); // service type value
					var to_service_type_name = to_service_type_dom.find(":selected").attr("data-service_type"); // service type name
					var to_dynamic_price_total = to_service_type_dom.find(":selected").attr("data-dynamic_price_total"); // service type name
					var update_last_ym_comp_job_serv_chk = ( change_prop_and_job_service_fb.find('#update_last_ym_comp_job_serv_chk').prop("checked") == true )?1:0;
					var update_last_ym_comp_job_price_chk = ( change_prop_and_job_service_fb.find('#update_last_ym_comp_job_price_chk').prop("checked") == true )?1:0;
					var update_any_non_comp_job_serv_chk = ( change_prop_and_job_service_fb.find('#update_any_non_comp_job_serv_chk').prop("checked") == true )?1:0;
					var update_any_non_comp_job_price_chk = ( change_prop_and_job_service_fb.find('#update_any_non_comp_job_price_chk').prop("checked") == true )?1:0;
					var from_service_dynamic_price_total = change_prop_and_job_service_fb.find('#from_service_dynamic_price_total').val();
					var from_service_type_name = change_prop_and_job_service_fb.find("#from_service_type_name").text();

					if( to_service_type == '' ){
						error += 'New service is required\n';
					}

					if( error != '' ){ // error
						swal('',error,'error');
					}else{

						// last YM completed 
						var last_ym_txt = '';
						if( update_last_ym_comp_job_serv_chk == 1 && update_last_ym_comp_job_price_chk == 1 ){
							var last_ym_txt = ' and Last YM Service and Price will update';
						}else if( update_last_ym_comp_job_serv_chk == 1 ){
							var last_ym_txt = ' and Last YM Service will update';
						}else if( update_last_ym_comp_job_price_chk == 1 ){
							var last_ym_txt = ' and Last YM Price will update';
						} 

						// non-completed
						var last_non_comp_txt = '';
						if( update_any_non_comp_job_serv_chk == 1 && update_any_non_comp_job_price_chk == 1 ){
							var last_non_comp_txt = ' and non-completed Service and Price will update';
						}else if( update_any_non_comp_job_serv_chk == 1 ){
							var last_non_comp_txt = ' and non-completed Service will update';
						}else if( update_any_non_comp_job_price_chk == 1 ){
							var last_non_comp_txt = ' and non-completed Price will update';
						}						

						var confirm_txt = 'This will update service from '+from_service_type_name+' and $'+from_service_dynamic_price_total+' to '+
						to_service_type_name+' and $'+to_dynamic_price_total+last_ym_txt+last_non_comp_txt+'\n\n'+
						'Are you sure you want to continue?'; 						

						swal({
							title: "",
							text: confirm_txt,
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
								
								jQuery('#load-screen').show();
								jQuery.ajax({
									type: "POST",
									url: "/agency/update_property_and_job_service_type_process",
									data: { 				
										'agency_id': agency_id,	
										'prop_id_arr': prop_id_arr,
										'from_service_type': from_service_type,
										'to_service_type': to_service_type,
										'update_last_ym_comp_job_serv_chk': update_last_ym_comp_job_serv_chk,
										'update_last_ym_comp_job_price_chk': update_last_ym_comp_job_price_chk,
										'update_any_non_comp_job_serv_chk': update_any_non_comp_job_serv_chk,
										'update_any_non_comp_job_price_chk': update_any_non_comp_job_price_chk
									},
									dataType: 'json'
								}).done(function( ret ){
										
									jQuery('#load-screen').hide();
									
									if( ret.length > 0 ){

										// swal html markup
										dup_html = '<div class="skipped_merge_precomp_jobs_div">Properties that could be processed have been processed.<br />'+ 
										'Some properties were skipped due to having merge/precomp jobs in the system <br /><br />'+
										'<ul>';

										for( var i=0; i<ret.length; i++ ){
											dup_html += ''+
											'<li>'+
												'<a href="/properties/details/?id='+ret[i].property_id+'" target="_blank">'+
													ret[i].prop_address+										
												'</a>'+
											'</li>';
										}
										dup_html +='</ul></div>';	
										
										// swal
										swal(
											{
												html: true,
												title: "Success!",
												text: dup_html,
												type: "success",
												confirmButtonClass: "btn-primary",
												customClass: 'swal-dup_prop'						
											},
											function(isConfirm) {
												if (isConfirm) {							  												
													$.fancybox.close();		
													location.reload();			
												}
											}
										);

									}else{
		
										swal({
											title: "Success!",
											text: "Update Successful!",
											type: "success",
											confirmButtonClass: "btn-success",
											showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
											timer: <?php echo $this->config->item('timer') ?>
										});
										setTimeout(function(){ location.reload(); }, <?php echo $this->config->item('timer') ?>);	

									}									

								});					

							}

						});								

					}					
					
				}
				
			});
			

		});
	</script>