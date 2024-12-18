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

	<style>
	.separator {
		margin: 0 5px;
	}
	.bold_it{
		font-weight: bold;
	}
	#nlm_btn{
		margin: 15px;	
		display: none;	
	}
	</style>
    
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

                        <div class="col-md-4">
							<label for="agency_select">Agency</label>
							<select id="agency_filter" name="agency_filter"  class="form-control field_g2">
                                <option value="">ALL</option>
                                <?php								
                                foreach( $agency_sql->result() as $agency_row ){ ?>
                                    <option value="<?php echo $agency_row->agency_id; ?>" <?php echo ( $agency_row->agency_id == $this->input->get_post('agency_filter') )?'selected="selected"':null; ?>>
                                        <?php echo $agency_row->agency_name; ?>
                                    </option>
								<?php								
								}								
                                ?>
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

	<section>
		<div class="body-typical-body">
			<div class="table-responsive">
            
				<table id="jtable" class="table table-hover main-table table-striped">

                    <thead>
						<tr>    
							<th>CRM Address</th>
							<th>PMe Address</th> 							
							<th>Deactivated Date</th>   
							<th></th>                             							                              						                           
						</tr>
					</thead>

					<tbody>
                    <?php  
                    if( $this->input->get_post('agency_filter') > 0 ){		
						if( count($crm_prop_arr) > 0 ){              
                            foreach( $crm_prop_arr as $index => $row ){ 
                                
                                $p_address = "{$row->p_street_num} {$row->p_street_name} {$row->p_suburb}  {$row->p_state}  {$row->p_postcode}";                        
								
								

			
								?>
                                <tr>
                                    <td>
                                        <a href='/properties/details/?id=<?php echo $row->property_id; ?>'>
                                            <?php echo $p_address; ?>
                                        </a>
									</td>    									
									<td><?php echo $row->pme_prop_address; ?></td>									
									<td><?php echo date('d/m/Y',strtotime($row->pme_archived_date)); ?></td> 
									<td>
										<div class="checkbox">
											<input class="crm_prop_id" type="checkbox" id="checkbox_<?php echo $index ?>" value="<?php echo $row->property_id; ?>" />
											<label for="checkbox_<?php echo $index ?>">&nbsp;</label>
										</div>
									</td>                       									                            
                                </tr>
								<?php   								
					
							}  	
						}else{ ?>
						<tr><td colspan="4">Empty</td></tr>
					<?php							
						}		               						
					}else{ ?>
						<tr><td colspan="4">Please select agency to filter</td></tr>
					<?php
					}					                                                    
					?>

					</tbody>

				</table>	
			
				<?php
				if( count($crm_prop_arr) > 0 ){ ?>
					<button type="button" id="nlm_btn" class="btn float-right">No Longer Managed</button>	
				<?php
				}
				?>							

            </div>


		</div>
	</section>

</div>


<!-- Fancybox START -->

<!-- ABOUT TEXT -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >

	<h4><?php echo $title; ?></h4>
	<p>lorem impsum</p>

</div>

<link rel="stylesheet" href="/inc/css/lib/datatables-net/datatables.min.css">
<link rel="stylesheet" href="/inc/css/separate/vendor/datatables-net.min.css">
<script src="/inc/js/lib/datatables-net/datatables.min.js"></script>
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css" rel="stylesheet" />
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js"></script>

<script>
jQuery(document).ready(function(){

	// datatable
    jQuery('#jtable').DataTable({
        
		'pageLength': 50,
		'lengthChange': true,
		"order": [[ 0, 'asc' ]],
		'columnDefs': [
			{
				'targets': [3],
				'orderable': false
			}
		],

	});

	// NLM button toggle
	jQuery(".crm_prop_id").click(function(){

		var prop_count = jQuery(".crm_prop_id:checked").length;	

		if( prop_count > 0 ){
			jQuery("#nlm_btn").show();
		}else{
			jQuery("#nlm_btn").hide();
		}

	});


	// NLM button toggle
	jQuery("#nlm_btn").click(function(){

		var crm_prop_id_arr = []; 
		jQuery(".crm_prop_id:checked").each(function(){

			var node = jQuery(this);
			var crm_prop_id = node.val();	
			crm_prop_id_arr.push(crm_prop_id);			

		});

		if( crm_prop_id_arr.length > 0 ){

			// confirm add PMe property on crm
			swal({
				title: "Are you sure?",
				text: "You are about to NLM selected properties. Are you sure you want to continue?",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-success",
				confirmButtonText: "Yes, NLM",
				cancelButtonText: "No, Cancel!",
				cancelButtonClass: "btn-danger",
				closeOnConfirm: true,
				showLoaderOnConfirm: true,
				closeOnCancel: true
			},
			function(isConfirm) {
				
				if (isConfirm) { // yes		
									
					// get crm list
					jQuery('#load-screen').show(); 
					jQuery.ajax({
						url: "/property_me/nlm_property",
						type: 'POST',
						data: { 
							'property_id_arr': crm_prop_id_arr
						},
						dataType: 'json'
					}).done(function( json ){
						
						jQuery('#load-screen').hide();

						var nlm_msg_txt = '';

						// NLM success
						if( json.nlm_success_prop_id_arr.length > 0 ){

							nlm_msg_txt += 'NLM Successful!\n\n';																							

						}

						// cannot NLM bec has active jobs
						if( json.cannot_nlm_prop_id_arr.length > 0 ){

							nlm_msg_txt += 'The following properties cannot be NLM bec it has active jobs: \n\n';
							jQuery.each(json.cannot_nlm_address_arr, function( index, value ) {

								nlm_msg_txt += value+'\n';
								
							});																									

						}	

						if( nlm_msg_txt != '' ){

							
							if( json.nlm_success_prop_id_arr.length > 0 ){ 

								// if it has properties that cannot be NLM, do not reload page
								if( json.cannot_nlm_prop_id_arr.length > 0 ){

									swal({
										title: "Success!",
										text: nlm_msg_txt,
										type: "success",
										confirmButtonClass: "btn-success"
									},
									function(isConfirm) {

										if (isConfirm) { // only reload on confirm							  											
											window.location="/property_me/properties_inactive_on_pme";					
										}

									});

								}else{

									swal({
										title: "Success!",
										text: nlm_msg_txt,
										type: "success",															
										confirmButtonClass: "btn-success",
										showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
										timer: <?php echo $this->config->item('timer') ?>
									});	

								}								
								
							}else{

								swal({
									title: "Info!",
									text: nlm_msg_txt,
									type: "info",
									confirmButtonClass: "btn-primary"
								});

							}														

						}
						
											

					});					

					
				}
				
			});

		}

	});


});
</script>

