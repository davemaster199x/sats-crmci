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
    .prop_details{
        display:none;
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

                        <div class="col-md-5">
							<label for="agency_select">Office (Agency)</label>
							<select id="office_id_filter" name="office_id_filter"  class="form-control">
                                <option value="">ALL</option>
                                <?php 
                                foreach( $agency_filter->result() as $agency_row ) { ?>
                                    <option value="<?php echo $agency_row->office_id; ?>" <?php echo (  $agency_row->office_id == $this->input->get_post('office_id_filter') )?'selected':null;  ?>><?php echo $agency_row->agency_name; ?></option>
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
  
                <table class="table main-table">
                    <tr>
                        <th>Event Type</th>
                        <th>Address</th>    
                        <th>Other Info</th>    
                        <th>Tenancy Agreement</th>
                        <th>Created By</th> <!-- renamed from "Users" to "Created By" by ness -->                  
                        <th>Date</th>
                        <th>CRM Linked</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if( $webhooks_data_sql->num_rows() > 0 ){
                        foreach( $webhooks_data_sql->result() as $webhooks_row ){ 
                        
                            $json_dec = json_decode($webhooks_row->json);     

                            $event_obj = $json_dec->event;
                            $rel_res_obj = $event_obj->relatedResources;
                            $prop_comp_obj = $rel_res_obj->propertyCompliance;
                            $manage_agree_obj = $rel_res_obj->managementAgreement;
                            $landlords_obj_arr = $manage_agree_obj->landlords;
                            $ten_agree_arr_obj = $rel_res_obj->tenantAgreements;
                            $prop_obj = $rel_res_obj->property;      
                            $portfolio_obj = $rel_res_obj->portfolio;
                            $users_arr_obj = $rel_res_obj->users;
                            $address_obj = $prop_obj->address;

                            $event_id = $json_dec->eventId;
                            $office_id = $json_dec->officeId;
                            $event_type = $json_dec->eventType;
                            $console_prop_id = $prop_obj->propertyId;  

                            $prop_comp_proc_obj = $event_obj->propertyComplianceProcess;
                            $prop_comp_proc_id = $prop_comp_proc_obj->propertyComplianceProcessId;  
                            
                            $last_updated_date_time = date('Y-m-d H:i:s',strtotime($event_obj->lastUpdatedDateTime));   
                            
                            // get landlords
                            $landlords_arr = [];
                            foreach( $landlords_obj_arr as $landlords_obj ){
                                $landlords_arr[] = $landlords_obj->contactId;
                            }

                            // property compliance
                            $expiry_date = ( $prop_comp_obj->expiryDate != '' )?date('Y-m-d',strtotime($prop_comp_obj->expiryDate)):null;
                            $last_ins_date = ( $prop_comp_obj->lastInspectionDate != '' )?date('Y-m-d',strtotime($prop_comp_obj->lastInspectionDate)):null;
                            
                        ?>

                        <tr>                                        
                            <td>
                                <span class="<?php echo ( $webhooks_row->event_type == 'PROPERTY_COMPLIANCE_CANCELLED' )?'text-danger':null; ?>">
                                    <?php 
                                    // event type
                                    $even_type_txt = null;
                                    if( $webhooks_row->event_type == 'PROPERTY_COMPLIANCE_REQUESTED' ){
                                        $even_type_txt = 'Property to be serviced by '.$this->config->item('company_name_short'); 
                                    }else{
                                        $even_type_txt = str_replace('_',' ',ucwords(strtolower($webhooks_row->event_type))); 
                                    }   
                                    echo $even_type_txt;                                 
                                    ?>
                                </span>
                            </td>
                            <td class="align-top">
                                <?php
                                $st_unit = null;
                                $st_num = null;
                                $st_name = null;
                                $st_type = null;
                                $suburb = null;
                                $postcode = null;
                                $state = null;

                                if( $webhooks_row->event_type == 'PROPERTY_COMPLIANCE_CANCELLED' ){ // cancelled webhook                                    

                                    // get inactive property
                                    $inactive_cp_sql = $this->db->query("
                                    SELECT 
                                        `crm_prop_id`,
                                        `unit_num`,
                                        `street_num`,
                                        `street_name`,
                                        `street_type`,
                                        `suburb`,
                                        `postcode`,
                                        `state`
                                    FROM `console_properties`
                                    WHERE `console_prop_id` = {$webhooks_row->console_prop_id}
                                    AND `active` = 0
                                    ORDER BY `id` DESC
                                    LIMIT 1
                                    ");
                                    $inactive_cp_row = $inactive_cp_sql->row();

                                    $st_unit = $inactive_cp_row->unit_num;
                                    $st_num = $inactive_cp_row->street_num;
                                    $st_name = $inactive_cp_row->street_name;
                                    $st_type = $inactive_cp_row->street_type;
                                    $suburb = $inactive_cp_row->suburb;
                                    $postcode = $inactive_cp_row->postCode;
                                    $state = $inactive_cp_row->state;
                                    
                                }else{ // default

                                    $st_unit = $address_obj->unitNumber;
                                    $st_num = $address_obj->streetNumber;
                                    $st_name = $address_obj->streetName;
                                    $st_type = $address_obj->streetType;
                                    $suburb = $address_obj->suburb;
                                    $postcode = $address_obj->postCode;
                                    $state = $address_obj->stateCode;

                                }

                                $p_address_search = "{$st_num} {$st_name} {$suburb}";
                                ?>
                                <table class="table">
                                    <tr>    
                                        <th>Unit Number</th><td><?php echo $st_unit; ?></td> 
                                    </tr>
                                    <tr>
                                        <th>Street Number</th><td><?php echo $st_num; ?></td>	  
                                    </tr>
                                    <tr> 
                                        <th>Street Name</th><td><?php echo $st_name; ?></td> 
                                    </tr>
                                    <tr>
                                        <th>Street Type</th> <td><?php echo $st_type; ?></td>
                                    </tr>
                                    <tr> 
                                        <th>Suburb</th><td><?php echo $suburb; ?></td> 
                                    </tr>
                                    <tr>
                                        <th>Postcode</th><td><?php echo $postcode; ?></td> 
                                    </tr>
                                    <tr>
                                        <th>State</th><td><?php echo $state; ?></td>                               						                           
                                    </tr>
                                </table>
                            </td>
                            <td class="align-top">
                                <table class="table">
                                    <tr>
                                        <th>Service Type</th>
                                        <td>
                                            <?php 
                                            // service type
                                            $service_type = null;
                                            $service_type_class = null;
                                            if( $prop_comp_obj->type == 'SMOKE_ALARMS' ){ // smoke alarms

                                                $service_type = 'Smoke Alarms';
                                                $service_type_class = 'text-danger';

                                            }else if( $prop_comp_obj->type == 'RESIDUAL_CURRENT_DEVICES' ){ // safety switch

                                                $service_type = 'Safety Switch';
                                                $service_type_class = 'text-warning';

                                            }else if( $prop_comp_obj->type == 'BLINDS' ){ // corded window
                                                
                                                $service_type = 'Corded Windows';
                                                $service_type_class = 'text-success';

                                            }else if( $prop_comp_obj->type == 'WATER_EFFICIENCY' ){ // water efficiency

                                                $service_type = 'Water Efficiency';
                                                $service_type_class = 'text-primary';

                                            }
                                            ?>
                                            <span class="<?php echo $service_type_class; ?>"><?php echo $service_type; ?></span>
                                        </td>
                                    </tr>
                                    <tr>    
                                        <th>Compliance Notes</th><td><?php echo $prop_comp_obj->notes; ?></td> 
                                    </tr>                                                                                                                  
                                    <tr>    
                                        <th>Key Number</th><td><?php echo $prop_obj->keyNumber; ?></td> 
                                    </tr> 
                                    <tr>    
                                        <th>Access Details</th><td><?php echo $prop_obj->access_details; ?></td> 
                                    </tr>
                                    <tr>    
                                        <th>Property Type</th><td><?php echo ucwords(strtolower($prop_obj->property_type)); ?></td> 
                                    </tr>                                                       
                                    <tr>    
                                        <th>Expiry Date</th><td><?php echo ( $expiry_date != '' )?date('d/m/Y',strtotime($expiry_date)):null; ?></td> 
                                    </tr>
                                    <tr>    
                                        <th>Last Inspection</th><td><?php echo ( $last_ins_date != '' )?date('d/m/Y',strtotime($last_ins_date)):null; ?></td> 
                                    </tr>                                               
                                    <tr>    
                                        <th>QLD 2022 Compliance</th><td><?php echo ( $prop_comp_obj->has2022LegislationCompliance == true )?'<span class="text-success">Yes</span>':'<span class="text-danger">No</span>'; ?></td> 
                                    </tr>                                                                                      
                                </table>
                            </td>
                            <td class="align-top">
                                <?php
                                // console tenants agreement
                                foreach( $ten_agree_arr_obj as $ten_agree_obj ){ 
                                    
                                    $lease_obj = $ten_agree_obj->lease;                                                                        
                                    ?>

                                    <table class="table mb-3">
                                        <tr>
                                            <th>Lease Name</th><td><?php echo $ten_agree_obj->leaseName; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Inaugural Date</th>
                                            <td><?php echo ( $lease_obj->inauguralDate !='' )?date('d/m/Y',strtotime($lease_obj->inauguralDate)):null; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Start Date</th>
                                            <td><?php echo ( $lease_obj->startDate !='' )?date('d/m/Y',strtotime($lease_obj->startDate)):null; ?></td>
                                        </tr>  
                                        <tr>
                                            <th>End Date</th>
                                            <td><?php echo ( $lease_obj->endDate != '' )?date('d/m/Y',strtotime($lease_obj->endDate)):null; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Vacating Date</th>
                                            <td><?php echo ( $lease_obj->vacating_date !=''  )?date('d/m/Y',strtotime($lease_obj->vacating_date)):null; ?></td>
                                        </tr>                                                                                                                      
                                    </table>

                                <?php
                                }
                                ?>                                
                            </td>
                            <td class="align-top">
                                <?php
                                foreach( $users_arr_obj as $users_obj ){ ?>
                                    <table class="table">
                                        <tr>
                                            <th>First Name</th><td><?php echo $users_obj->firstName; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Last Name</th><td><?php echo $users_obj->lastName; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Email</th><td><?php echo $users_obj->email; ?></td>
                                        </tr>
                                    </table>
                                <?php
                                }
                                ?>
                            </td>                            
                            <td><?php echo date("d/m/Y H:i",strtotime($webhooks_row->cwd_date)) ?></td>
                            <td>
                                <?php
                                if( $webhooks_row->crm_prop_id != '' ){ ?>
                                    <a 
                                        href="/properties/details/?id=<?php echo $webhooks_row->crm_prop_id; ?>" 
                                        target="_blank"
                                    >
                                        <button type="button" class="btn btn-primary">View</button>
                                    </a>
                                <?php
                                }else{ ?>
                                    <a 
                                        href="/console/bulk_connect/?agency_id=<?php echo $webhooks_row->agency_id; ?>&p_address_search=<?php echo $p_address_search; ?>" 
                                        target="_blank"
                                    >
                                        <button type="button" class="btn btn-primary">Connect</button>
                                    </a>
                                <?php
                                }
                                ?> 
                            </td>
                            <td>
                                <input type="hidden" class="cwd_id" value="<?php echo $webhooks_row->cwd_id; ?>">
                                <button type="button" class="btn hide_btn">Hide</button>
                            </td>
                        </tr>
                        <?php                
                        }
                    }else{ ?>
                        <tr>
                            <td colspan="100%">Empty</td>
                        </tr>
                    <?php
                    }
                    ?>
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
<pre><code><?php echo $page_query; ?></code></pre>

</div>
<script>
jQuery(document).ready(function(){

    jQuery(".hide_btn").click(function(){

        var dom = jQuery(this);
        var parent_td = dom.parents("td:first");
        var cwd_id = parent_td.find(".cwd_id").val();

        if( cwd_id > 0 ){

            swal({
                title: "Warning!",
                text: "This will hide this tenant info, Do you want to continue?",
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
                        url: "/console/deactivate_webhook_data",
                        data: { 	
                            cwd_id: cwd_id
                        }
                    }).done(function( ret ){
                                            
                        $('#load-screen').hide();	
                        location.reload();

                    });							

                }

            });				            

        }        

    });
    
});
</script>

