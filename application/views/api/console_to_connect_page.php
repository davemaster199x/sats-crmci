
<link rel="stylesheet" href="/inc/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="/inc/css/separate/vendor/datatables-net.min.css">
<script src="/inc/js/lib/datatables-net/datatables.min.js"></script>
<style type="text/css">
.dataTables_paginate {
  display: inline-block;
}

.dataTables_paginate a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  transition: background-color .3s;
  border: 1px solid #ddd;
}

.dataTables_paginate a.active {
  background-color: #4CAF50;
  color: white;
  border: 1px solid #4CAF50;
}
#pmeProp {
  /*table-layout: fixed;*/
  width: 100% !important;
}
.dataTables_filter {
   display: none;
}

#pmeProp tr th:nth-child(1){
       width: 35%;
}
#pmeProp tr th:nth-child(3){
       width: 15%;
}
#pmeProp tr th:nth-child(4){
       width: 15%;
}
#pmeProp tr th:nth-child(7){
       width: 3%;
}
.fa-lg:hover{
  color: #07da07;
}

.company_logo {
    margin: 30px 0;
	height: 70px;
}

.pme_main_div {
    border: solid 2px #14cdeb;
    background: #f5f8fa;	
}

.pme_logo {
    margin-left: 9px;
}
.api_address_tenant_det_btn{
	cursor: pointer;
}
.pmeTenantTable{
	display:none;
}
</style>
<div class="box-typical box-typical-padding">

	<?php 
	// breadcrumbs template
	$bc_items = array(
		array(
			'title' => $title,
			'status' => 'active',
			'link' => "/property_me/property/{$this->uri->segment(3)}/{$this->uri->segment(4)}"
		)
	);
	$bc_data['bc_items'] = $bc_items;
	$this->load->view('templates/breadcrumbs', $bc_data);
	?>
	<section>
		<div class="body-typical-body">
				<div class="row">
				  <div class="col-sm-12">
				  <img src="/images/logo_login.png" class="company_logo sats_logo" />
				  	<?php 
				  	if (isset($crm_prop_row)) {
				  		$prop_full_add = "{$crm_prop_row->address_1} {$crm_prop_row->address_2} {$crm_prop_row->address_3} {$crm_prop_row->state} {$crm_prop_row->postcode}";
			  		 ?>
			  			<div class="row">
							<div class="col-lg-8">
					  			<div class="row">
									<div class="col-lg-12">
										<fieldset class="form-group">
											<label class="form-label semibold" for="add1">Address Text</label>
											<input type="hidden" id="add0" value="<?=$crm_prop_row->property_id?>">
											<div class="input-group">
												<input type="text" class="form-control crm_full_address" id="add1" placeholder="Address Text" value="<?=$prop_full_add?>">
											      <button class="btn btn-primary" id="addSearch" type="button" style="display: none;"><i class="fa fa-search"></i>
											      </button>
										    </div>
										</fieldset>
									</div>
								</div>
					  			<div class="row">
									<div class="col-lg-2">
										<fieldset class="form-group">
											<label class="form-label semibold" for="add2">No.</label>
											<input type="text" class="form-control" id="add2" placeholder="No." value="<?=$crm_prop_row->address_1?>">
										</fieldset>
									</div>
									<div class="col-lg-4">
										<fieldset class="form-group">
											<label class="form-label semibold" for="add3">Street</label>
											<input type="text" class="form-control" id="add3" placeholder="Street" value="<?=$crm_prop_row->address_2?>">
										</fieldset>
									</div>
									<div class="col-lg-2">
										<fieldset class="form-group">
											<label class="form-label semibold" for="add4">Suburb</label>
											<input type="text" class="form-control" id="add4" placeholder="Suburb" value="<?=$crm_prop_row->address_3?>">
										</fieldset>
									</div>
									<div class="col-lg-2">
										<fieldset class="form-group">
											<label class="form-label semibold" for="add5">
												<?php
												if ($this->config->item('country') == 1) {
													echo "State";
												}else {
													echo "Region";
												}
												?>
											</label>
											<input type="text" class="form-control" id="add5" placeholder="State" value="<?=$crm_prop_row->state?>">
										</fieldset>
									</div>
									<div class="col-lg-2">
										<fieldset class="form-group">
											<label class="form-label semibold" for="add6">Postcode</label>
											<input type="text" class="form-control" id="add6" placeholder="Postcode" value="<?=$crm_prop_row->postcode?>">
										</fieldset>
									</div>
									
								</div>
							</div>
							<div class="col-lg-4">
								<fieldset class="form-group">
									<label class="form-label semibold" for="add7">Property Notes</label>
									<textarea class="form-control" placeholder="Notes" style="height: 120px;"><?=$crm_prop_row->comments?></textarea>
								</fieldset>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<small class="text-muted">*This CRM property is not yet linked to any Console property, kindly link to a Console property below.</small>
							</div>
						</div>
			  		<?php
				  	} else { ?>
			  			<div class="row">
							<div class="col-lg-12">
								No property is associated with the property id <?=$this->uri->segment(3)?>
							</div>
						</div>
				  	<?php
				  	}
				  	?>

					<?php 
					if ( $console_prop_sql->num_rows() > 0 ) { 
					?>
					    <div id="pmeDetId" class="pme_main_div" style="display: <?=isset($crm_prop_row) ? "" : "none"?>">
						<img src="/images/third_party/console_default.png" class="company_logo pme_logo" />
						    <div class="container-fluid">
								<div class="row">
									<div class="col-sm-4">
										<label class="form-label semibold">Address Search:</label>
										<input type="text" class="form-control" id="datatable_pme_search" placeholder="Search Address" />
									</div>
									<div class="col-sm-4">
										<label class="form-label semibold">&nbsp;</label>
										<?php
                                            /*
											$t_params = array(
												'property_id' => $crm_prop_row->property_id,
												'property_source' => 1
											);
											if($this->api_model->if_notes_already_exist_in_pnv($t_params)==true){
												echo '<button class="btn" disabled>Pending Verification</button>';
											}else{
												echo '<butotn class="btn pnv_verify_button">Property Needs Verification</button>';
											}
                                            */
										?>
										
									</div>
								</div>
						    </div>
							<table id="pmeProp" class="display table table-striped table-bordered table-hover" >
								<thead>
									<tr>
										<th>Full Address</th>
                                        <th>Unit</th>
										<th>Number</th>
										<th>Street</th>
                                        <th>St. type</th>
										<th>Suburb</th>
										<th>
											<?php
											if ($this->config->item('country') == 1) {
												echo "State";
											}else {
												echo "Region";
											}
											?>
										</th>
										<th>Postal Code</th>
										<!--<th>Status</th>-->
										<th>Link to CRM</th>
									</tr>
								</thead>
								<tbody>
									<?php                                     
                                    foreach ( $console_prop_sql->result() as $console_prop_row ) { 

                                        // street
                                        if( $console_prop_row->unit_num != '' && $console_prop_row->street_num != '' ){
                                            $street_unit_num = "{$console_prop_row->unit_num}/{$console_prop_row->street_num}";
                                        }else if( $console_prop_row->unit_num != '' ){
                                            $street_unit_num = "{$console_prop_row->unit_num}";
                                        }else if( $console_prop_row->street_num != '' ){
                                            $street_unit_num = "{$console_prop_row->street_num}";
                                        }

                                        $street_full = "{$console_prop_row->street_name} {$console_prop_row->street_type}";  
                                    ?>
                                    <tr>
                                        <td class="api_address_tenant_det_btn console_full_address" data-tenant_fb_id="pmeTenantTable<?php echo $console_prop_row->cp_id; ?>"><?=str_replace(',','',$street_full)?></td>
                                        <td class="console_unit"><?php echo $console_prop_row->unit_num; ?></td>
                                        <td class="console_street_num" style="text-align: center;"><?=$console_prop_row->street_num?></td>
                                        <td class="console_street_name"><?=$console_prop_row->street_name?></td>
                                        <td class="console_street_type"><?=$console_prop_row->street_type?></td>
                                        <td class="console_suburb" style="text-align: center;"><?=$console_prop_row->suburb?></td>
                                        <td class="console_state"><?php echo $console_prop_row->state; ?></td>
                                        <td class="console_postcode" style="text-align: center;"><?=$console_prop_row->postcode?></td>
                                        <!--<td><?php echo ( $row->IsArchived == true )?'<span class="fa fa-close text-red" data-toggle="tooltip" title="Archived"></span>':'<span class="fa fa-check text-green"></span>'; ?></td>-->
                                        <td>
                                            <a class="btn_link" href="javascript:void(0)" data-toggle="tooltip" title="Link to this property" data-cp_id="<?=$console_prop_row->cp_id?>">
                                            <i class="fa fa-chain fa-lg"></i></a>	
                                           
											<div class="pmeTenantTable" id="pmeTenantTable<?php echo $console_prop_row->cp_id; ?>">

											<h4>Console Property Details</h4>

												<table class="table mb-3">
													<tbody>
														<tr>
															<th>Address</th>
															<td><?php echo $street_full; ?></td>
														</tr>
														<tr>
															<th>Unit</th>
															<td><?php echo $console_prop_row->unit_num; ?></td>
														</tr>
														<tr>
															<th>Number</th>
															<td><?php echo $console_prop_row->street_num; ?></td>
														</tr>
														<tr>
															<th>Street</th>
															<td><?php echo $console_prop_row->street_name; ?></td>
														</tr>
														<tr>
															<th>St. Type</th>
															<td><?php echo $console_prop_row->street_type; ?></td>
														</tr>
														<tr>
															<th>Suburb</th>
															<td><?php echo $console_prop_row->suburb; ?></td>
														</tr>
														<tr>
															<th>State</th>
															<td><?php echo $console_prop_row->state; ?></td>
														</tr>
														<tr>
															<th>Postal Code</th>
															<td><?php echo $console_prop_row->postcode; ?></td>
														</tr>														
													</tbody>
												</table>

												<h3 class="pme_headings">Tenant Details</h3>

												<table class="table">
													<thead>
														<tr>
															<th>First Name</th>
															<th>Last Name</th>
															<th>Phone</th>                                
															<th>Email</th>                             
														</tr>
													</thead>
													<tbody>
														<?php 
														if( $console_prop_row->console_prop_id > 0 ){

															// get console tenants                  
															$this->db->select('*');
															$this->db->from('console_property_tenants AS cpt');
															$this->db->join('console_properties AS cp', 'cpt.`console_prop_id` = cp.`console_prop_id`', 'inner');
															$this->db->where('cp.console_prop_id', $console_prop_row->console_prop_id);
															$this->db->where('cpt.active', 1);
															$this->db->where('cpt.is_landlord', 0);
															$console_tenant_sql = $this->db->get();	
						
															foreach ( $console_tenant_sql->result() as $console_row ) { 
															?>
																<tr>
																	<td>
																		<?php echo $console_row->first_name; ?>
																		<input type="hidden" class="console_tenant_fname" value="<?php echo $console_row->first_name; ?>" />
																	</td>
																	<td>
																		<?php echo $console_row->last_name; ?>
																		<input type="hidden" class="console_tenant_lname" value="<?php echo $console_row->last_name; ?>" />
																	</td>
																	<td>
																		<table clas="table">
																			<tr>
																				<th>Type</th>
																				<th>Number</th>
																				<th>Primary</th>												
																			</tr>
																			<?php
																			if( $console_row->contact_id > 0 ){

																				// get tenants phone                
																				$this->db->select('*');
																				$this->db->from('console_property_tenant_phones AS cpt_phones');
																				$this->db->join('console_property_tenants AS cpt', 'cpt_phones.contact_id = cpt.contact_id', 'inner');											
																				$this->db->where('cpt.contact_id', $console_row->contact_id);
																				$this->db->where('cpt_phones.active', 1);
																				$cpt_phone_sql = $this->db->get();												

																				foreach ( $cpt_phone_sql->result() as $cpt_phone_row ){ ?>
																					<tr>
																						<td><?php echo ucwords(strtolower($cpt_phone_row->type)); ?></td>
																						<td>
																							<?php echo $cpt_phone_row->number; ?>
																							<input type="hidden" class="console_tenant_phone_number" value="<?php echo $cpt_phone_row->number; ?>" />
																						</td>
																						<td>
																							<?php echo ( $cpt_phone_row->is_primary == 1 )?'<span class="text-success">Yes</span>':'<span class="text-danger">No</span>'; ?>
																						</td>									
																					</tr>
																				<?php
																				}

																			}											
																			?>											
																		</table>
																	</td>
																	<td>
																		<table clas="table">
																			<tr>
																				<th>Type</th>
																				<th>Email</th>
																				<th>Primary</th>															
																			</tr>
																			<?php
																			if( $console_row->contact_id > 0 ){

																				// get tenants email                
																				$this->db->select('*');
																				$this->db->from('console_property_tenant_emails AS cpt_emails');
																				$this->db->join('console_property_tenants AS cpt', 'cpt_emails.contact_id = cpt.contact_id', 'inner');											
																				$this->db->where('cpt.contact_id', $console_row->contact_id);
																				$this->db->where('cpt_emails.active', 1);
																				$cpt_emails_sql = $this->db->get();												

																				foreach ( $cpt_emails_sql->result() as $cpt_emails_row ){ ?>
																					<tr>
																						<td><?php echo ucwords(strtolower($cpt_emails_row->type)); ?></td>
																						<td>
																							<?php echo $cpt_emails_row->email; ?>															
																						</td>
																						<td>
																							<?php echo ( $cpt_emails_row->is_primary == 1 )?'<span class="text-success">Yes</span>':'<span class="text-danger">No</span>'; ?>
																						</td>																		
																					</tr>
																				<?php
																				}

																			}											
																			?>	
																		</table>
																	</td>    							                                                           
																</tr>
															<?php
															}

														}																										
														?>
													</tbody>
												</table>

											</div>

                                        </td>
                                    </tr>
                                    <?php
                                    }                                        
									?>
								</tbody>
							</table>
				    	</div>
					<?php
					} else { ?>
						<table id="pmeProp1" class="display table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Address</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Address</th>
								</tr>
							</tfoot>
							<tbody>
								<tr>
									<td>No PMe Data</td>
								</tr>
							</tbody>
						</table>
					<?php
					}
					?>
				  </div>
				</div>
		</div>
	</section>

</div>

<script type="text/javascript">

function search_pme_datatable(address){

	jQuery('#pmeProp').DataTable().search( address, false, true ).draw();

}
	 
jQuery(document).ready(function() {
	

	var table = $('#pmeProp').DataTable({
		"ordering": false,
		"lengthChange": false
	});

	var crm_full_address = clearStreetName(jQuery(".crm_full_address").val()).trim();
	jQuery("#datatable_pme_search").val(crm_full_address);
	search_pme_datatable(crm_full_address);

	jQuery("#datatable_pme_search").keyup(function(){

		var address = jQuery(this).val();
		search_pme_datatable(address);
	});



	$(document).on('click', '.btn_link', function() {

		var cp_id = $(this).attr('data-cp_id');
		var crmId = $("#add0").val();

		swal({
			title: "Are you sure?",
			text: "This will link this Console property to the CRM property above.",
			type: "warning",
			showCancelButton: true,			
			confirmButtonClass: "btn-success",
			confirmButtonText: "Yes, link it!",
			cancelButtonClass: "btn-danger",
			cancelButtonText: "No, Cancel!",
			closeOnConfirm: false,
			closeOnCancel: true,
			showLoaderOnConfirm: true
		},
		function(isConfirm) {
			if (isConfirm) {
			$('#load-screen').show(); 
			$.ajax({
				url: "/console/ajax_function_link_property",
				type: 'POST',
				data: { 
					'cp_id': cp_id,
					'crmId': crmId
				}
			}).done(function( ret ){
				ret = JSON.parse(ret);
				$('#load-screen').hide(); 
				if (ret.updateStat === true) {
					swal({
						title: "Success!",
						text: "The properties are now linked.",
						type: "success",
						confirmButtonClass: "btn-success",
						showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                		timer: <?php echo $this->config->item('timer') ?>
					});

                    setTimeout(function(){ location.href='/console/connection_details/<?php echo $property_id; ?>'; }, <?php echo $this->config->item('timer') ?>);	
				}else {
					swal({
						title: "Error!",
						text: "Something went wrong, contact dev.",
						type: "error",
						confirmButtonClass: "btn-danger"
					});
				}
			})
			}
		});

	})

	$('.pnv_verify_button').on('click',function(){
		
		var pnv_id = "";
		var property_source = 1; // crm
		var property_id = <?php echo $crm_prop_row->property_id ?>;
		var property_address = $('.crm_full_address').val();
		var agency_id =  <?php echo $crm_prop_row->agency_id ?>;	
		var note = "Property Needs Verification";

		jQuery('#load-screen').show(); 
		jQuery.ajax({
			url: "/property_me/bulk_connect_save_note",
			type: 'POST',
			data: { 
				'pnv_id': pnv_id,
				'property_source': property_source,
				'property_id': property_id,
				'property_address': property_address,
				'agency_id': agency_id,
				'note': note
			}
		}).done(function( ret ){
			
			jQuery('#load-screen').hide(); 	
			swal({
				title:"Success!",
				text: "Submit success",
				type: "success",
				showCancelButton: false,
				confirmButtonText: "OK",
				closeOnConfirm: false,
				showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
				timer: <?php echo $this->config->item('timer') ?>

			});
			setTimeout(function(){location.reload(); }, <?php echo $this->config->item('timer') ?>);				

		});	

	})

	jQuery(document).on('click','.console_full_address',function(){
	
		var console_full_address_dom = jQuery(this);
		var tenant_fb_id = console_full_address_dom.attr("data-tenant_fb_id");
		
		$.fancybox.open({
			src  : '#'+tenant_fb_id
		});


	})

});
</script>