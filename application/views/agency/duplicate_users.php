<div class="box-typical box-typical-padding">

	<?php 
	// breadcrumbs template
    $bc_items = array(
        array(
            'title' => $title,
            'status' => 'active',
            'link' =>  $uri
        )
    );
	$bc_data['bc_items'] = $bc_items;
	$this->load->view('templates/breadcrumbs', $bc_data);
	?>

	<?php 
	if( validation_errors() ){ ?>
		<div class="alert alert-danger">
		<?php echo validation_errors(); ?>
		</div>
	<?php
	}	
	?>

    <!--
	<header class="box-typical-header">

		<div class="box-typical box-typical-padding">
			<?php
		$form_attr = array(
			'id' => 'jform'
		);
		echo form_open($uri,$form_attr);
		?>
			<div class="for-groupss row">
				<div class="col-md-8 columns">
					<div class="row">


						<div class="col-mdd-3">
							<label>Agency</label>
							<select name="page_display" class="form-control">
                                <option value="">--- Select ---</option>																					
							</select>
						</div>													

						<div class="col-md-1 columns">
							<label class="col-sm-12 form-control-label">&nbsp;</label>
							<input type="submit" name="search_submit" value="Search" class="btn">
						</div>
						
					</div>

				</div>
			</div>
			</form>
		</div>
	</header>
    -->

	<section>
		<div class="body-typical-body">
			<div class="table-responsive">
            
				<table class="table table-hover main-table duplicate_users_tbl">

					<thead>
						<tr>
							<th>User ID</th>                  
                            <th>User</th>							
                            <th>User Type</th>
                            <th>Username/Email</th>
                            <th>Agency</th>
                            <th>Active</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<tbody>
					<?php
					$i = 1;
					$chk_count = 1;
					foreach( $list->result() as $row ){ 
						$user = "{$row->fname} {$row->lname}";
						$user_id = $row->agency_user_account_id;
						?>
						<tr>
							<td>
                                #<?php echo $user_id; ?>
                            </td>
							<td>
								<?php echo $user; ?>
                            </td>							
                            <td>
                                <?php echo $row->user_type_name; ?>
                            </td>
                            <td>
                                <?php echo $row->email; ?>
                            </td>
                            <td>
								<a href="/agency/view_agency_details/<?php echo $row->agency_id; ?>">
                                    <?php echo $row->agency_name; ?>
                                </a>
							</td>	
                            <td>
                                <?php echo ( $row->aua_active == 1 )?'<span style="color:green">Yes</span>':'<span style="color:red">No</span>'; ?>
                            </td>						
                            <td class="text-center action_td">
								<?php
								// find attached properties
								$attached_prop_sql = $this->db->query("
									SELECT 
										p.`property_id`,
										p.`address_1` ,
										p.`address_2`,
										p.`address_3`,
										p.`state`,
										p.`postcode`,

										a.`agency_id`,
										a.`agency_name`
									FROM `property` AS p
									LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
									WHERE p.`pm_id_new` = {$this->db->escape($user_id)}
									AND p.`deleted` = 0
								");
		
								if( $attached_prop_sql->num_rows() > 0 ){ ?>

									<button type="button" class="btn btn-info move_properties_btn">Move Properties</button>									

									<!-- MOVE PROPERTIES -->
									<a href="javascript:void(0);" class="fb_trigger move_prop_fb" data-fancybox data-src="#move_prop_fb_<?php echo $user_id; ?>">Trigger the fancybox</a>							
									<div id="move_prop_fb_<?php echo $user_id; ?>" class="fancybox move_prop_div" style="display:none;" >

										<h4>Move Attached Properties</h4>
										
										<?php
										$form_attr = array(
											'class' => 'jform_move_properties'
										);
										echo form_open('/agency/duplicate_users',$form_attr);
										?>

											<div class="row">
												<label class="col-sm-5 form-control-label">Attached From: </label>
												<div class="col-sm-7">
													<?php echo $user; ?>
												</div>
											</div>
										
											<div class="attached_prop_div">
												<h5>Attached Properties: </h5>
												<ul>	
													<table class="table">	
													<tr>
														<th>
															<span class="checkbox">
																<input type="checkbox" id="check-all-<?php echo $chk_count ?>" class="check-all" />
																<label for="check-all-<?php echo $chk_count ?>" class="chk_lbl"></label>
															</span>
														</th>
														<th>Property Address</th>
														<th>Agency</th>
													</tr>											
													<?php
													foreach( $attached_prop_sql->result() as $attached_prop ){ ?>
														<tr>
															<td>
																<span class="checkbox">
																	<input type="checkbox" name="move_property[]" id="check-<?php echo $chk_count ?>-<?php echo $attached_prop->property_id ?>" class="prop_id_chk" value="<?php echo $attached_prop->property_id; ?>" />
																	<label for="check-<?php echo $chk_count ?>-<?php echo $attached_prop->property_id ?>" class="chk_lbl"></label>
																</span>
															</td>
															<td>
																<a href="/properties/details/?id=<?php echo $attached_prop->property_id; ?>">
																	<?php echo "{$attached_prop->address_1} {$attached_prop->address_2} {$attached_prop->address_3} {$attached_prop->state} {$attached_prop->postcode}"; ?>
																</a>
															</td>
															<td>
																<a href="/agency/view_agency_details/<?php echo $attached_prop->agency_id; ?>">
																	<?php echo $attached_prop->agency_name; ?>
																</a>
															</td>
														</tr>	
													<?php
													$chk_count++;
													}
													?>
													</table>
												</ul>
											</div>

											<h5>Move To: </h5>
											<div class="row">
												<label class="col-sm-5 form-control-label">Agency: </label>
												<div class="col-sm-7">
													<p class="form-control-static">
														<select class="form-control agency" data-validation="[NOTEMPTY]">
															<option value="">SELECT</option>								
															<?php
															foreach( $agency_sql->result() as $agency ){ ?>
																<option value="<?php echo $agency->agency_id ?>"><?php echo addslashes($agency->agency_name); ?></option>
															<?php
															} 						
															?>													
														</select>
													</p>
												</div>
											</div>

											<div class="move_prop_process_div">
												<div class="row move_prop_to_div">
													<label class="col-sm-5 form-control-label">User</label>
													<div class="col-sm-7">
														<p class="form-control-static">
															<select name="move_to_user" class="form-control move_to_user" data-validation="[NOTEMPTY]">
																<option value="">SELECT</option>																																				
															</select>
														</p>
													</div>
												</div>

												<input type="hidden" class="exclude_id" value="<?php echo $user_id; ?>" />
												<button type="button" class="btn btn-info move_prop_process_btn">Move</button>	
											</div>

										<?php
										echo form_close();
										?>

									</div>
								<?php
								}else{ 

									if( $row->aua_active == 1 ){  // active
										$toggle_status_btn = 'Deactivate';
										$toggle_status_btn_class = 'btn-danger';										
									}else{ //inactive
										$toggle_status_btn = 'Activate';
										$toggle_status_btn_class = null;
									}

									?>
										<button type="button" class="btn <?php echo $toggle_status_btn_class; ?> toggle_user_status" data-aua_active="<?php echo $row->aua_active ?>"><?php echo $toggle_status_btn; ?></button>
									<?php
									
								?>
									
								<?php
								}
								?>
								<input type="hidden" class="aua_id" value="<?php echo $user_id; ?>" />								                                                          
                            </td>
						</tr>
					<?php
                    $i++;
					}
					?>
					</tbody>

				</table>		

			</div>

			<nav id="pagi_links" aria-label="Page navigation example" style="text-align:center"><?php echo $pagination; ?></nav>
			<div id="pagi_count" class="pagi_count text-center"><?php echo $pagi_count; ?></div>
			

		</div>
	</section>

</div>


<!-- Fancybox START -->

<!-- ABOUT TEXT -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >

	<h4><?php echo $title; ?></h4>
	<p>
    Lorem Ipsum
	</p>

</div>




<style>
.move_prop_to_div,
.move_prop_process_btn{
	display:none;
}
.duplicate_users_tbl .btn{
    width: 145px;
}
.fancybox-content {
    width: auto;
}
.attached_prop_div{
	margin: 25px 0;
}
</style>

<!-- Fancybox END -->
<script>
function show_hide_move_btn(container){

	var num_ticked = container.find('.prop_id_chk:checked').length;
	var move_to_user = container.find('.move_to_user').val();

	if( move_to_user != '' && num_ticked > 0 ){
		container.find(".move_prop_process_btn").show();
	}else{
		container.find(".move_prop_process_btn").hide();
	}
}

jQuery(document).ready(function(){


	//success/error message sweel alert pop  start
    <?php 
    if( $this->session->flashdata('move_to_user') &&  $this->session->flashdata('move_to_user') == 1 ){ ?>
        swal({
            title: "Success!",
            text: "Properties Successfuly Moved",
            type: "success",
            confirmButtonClass: "btn-success"
        });
    <?php 
    }
    ?>

	//success/error message sweel alert pop  start
    <?php 
    if( is_numeric($this->input->get_post('active')) ){ 
		$status_txt = ( $this->input->get_post('active') == 1 )?'Deactivated':'Activated';
	?>	

		swal({
            title: "Success!",
            text: "User <?php echo $status_txt; ?>",
            type: "success",
            confirmButtonClass: "btn-success"
        });	
        
    <?php 
    }
    ?>


	// activate or deactivate user
	jQuery(".toggle_user_status").click(function(){

		var aua_id = jQuery(this).parents("td.action_td:first").find(".aua_id").val();
		var active = jQuery(this).attr("data-aua_active");
		var status_txt = ( active == 1 )?'Deactivate':'Activate';

		// confirm move user
		swal({
			title: "Warning!",
			text: "Are you sure you want to "+status_txt+" User?",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Cancel!",
			confirmButtonClass: "btn-warning",
			confirmButtonText: "Yes",                       
			closeOnConfirm: true
		},
		function(isConfirm) {
			
			if (isConfirm) { // yes				

				jQuery("#load-screen").show();
				jQuery.ajax({
					type: "POST",
					url: "/agency/toggle_user_status",
					data: { 
						aua_id: aua_id,
						active: active
					}
				}).done(function( ret ){
					jQuery("#load-screen").hide();
					window.location='/agency/duplicate_users/?active='+active;
				});
			}
			
		});

	});


	// move user
	jQuery(".move_prop_process_btn").click(function(){

		var obj = jQuery(this);
		var container = obj.parents(".move_prop_div:first");

		// confirm move user
		swal({
			title: "Warning!",
			text: "Are you sure you want to move properties to this user?",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Cancel!",
			confirmButtonClass: "btn-warning",
			confirmButtonText: "Yes",                       
			closeOnConfirm: true
		},
		function(isConfirm) {
			
			if (isConfirm) { // yes				

				container.find('.jform_move_properties').submit();
								
			}
			
		});


	});


	// check all
	jQuery(".check-all").change(function(){

		var obj = jQuery(this);
		var container = obj.parents(".move_prop_div:first");

		if( jQuery(this).prop("checked") == true ){
			container.find(".prop_id_chk").prop("checked",true);
		}else{
			container.find(".prop_id_chk").prop("checked",false);
		}

		show_hide_move_btn(container);

	});

	// individual checkbox
	jQuery(".prop_id_chk").change(function(){

		var obj = jQuery(this);
		var container = obj.parents(".move_prop_div:first");

		show_hide_move_btn(container);

	});

	// agency script
	jQuery(".agency").change(function(){

		var obj = jQuery(this);
		var container = obj.parents(".move_prop_div:first");
		
		var agency_id = obj.val();
		var exclude_id = container.find(".exclude_id").val();

		if( agency_id != '' ){

			jQuery("#load-screen").show();
			jQuery.ajax({
				type: "POST",
				url: "/agency/get_users",
				data: { 
					agency_id: agency_id,
					display_user_id: 1,
					exclude_id: exclude_id
				}
			}).done(function( ret ){	
				jQuery("#load-screen").hide();
				container.find(".move_to_user").html(ret);
				container.find(".move_prop_to_div").css('display','flex');
			});
			
			container.find(".move_prop_process_div").show();

		}else{

			container.find(".move_prop_process_div").hide();

		}

	});

	// user script
	jQuery(".move_prop_process_div").on('change','.move_to_user',function(){

		var obj = jQuery(this);
		var user_id = obj.val()
		var container = obj.parents(".move_prop_div:first");

		show_hide_move_btn(container);

	});

	// fancybox trigger
    // add connection
    jQuery(".move_properties_btn").click(function(){
        jQuery(this).parents("td:first").find(".move_prop_fb").click();
    });

});
</script>