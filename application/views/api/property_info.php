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
    .webhook_details_td{
        border: 6px solid #46c35f !important;
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
				<div class="col-md-12 columns">
					<div class="row">	

                        <div class="col-md-3">
							<label for="agency_select">Agency</label>
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

                        <div class="col-md-3 columns">
							<label class="col-sm-12 form-control-label">Address</label>
                            <input type="text" name="address_filter" class="form-control" value="<?php echo $this->input->get_post('address_filter'); ?>" />
						</div>    

                        <div class="col-md-3">
							<label for="agency_select">Display</label>
							<select id="display" name="display"  class="form-control">
                                <option value="-1">ALL</option>
                                <option value="1" <?php echo ( $display == 1 )?'selected':null; ?>>Unprocessed</option>
                                <option value="2" <?php echo ( $display == 2 )?'selected':null; ?>>Processed</option>
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

                    <thead>
                        <tr>    
                            <th>Address in Console</th>  
                            <th>Agency</th>  
                            <th>CRM Linked</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Action</th>                                   						                           
                        </tr>
                    </thead>

                        <?php
                        foreach( $console_prop_sql->result() as $console_prop_row ){ 

                      
                            // get 2nd to the last webhook, bec the latest web hook gets applied to the current
                            $whd_sql = $this->db->query("
                            SELECT *
                            FROM `console_webhooks_data` 
                            WHERE `console_prop_id` = '{$console_prop_row->console_prop_id}'     
                            ORDER BY `last_updated_date_time` DESC 
                            LIMIT 1,1 
                            ");
                            
                            if( $whd_sql->num_rows() > 0 ){

                                $whd_row = $whd_sql->row(); 

                                if( $whd_row->json != '' ){

                                    $json_dec = json_decode($whd_row->json);

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

                                    // format to Y-m-d
                                    // property compliance
                                    $console_expiry_date_ymd = ( $prop_comp_obj->expiryDate != '' )?date('Y-m-d',strtotime($prop_comp_obj->expiryDate)):null;
                                    $console_last_ins_date_ymd = ( $prop_comp_obj->lastInspectionDate != '' )?date('Y-m-d',strtotime($prop_comp_obj->lastInspectionDate)):null;
                                    
                                    $console_inaugural_date_ymd = ( $lease_obj->inauguralDate != '' )?date('Y-m-d',strtotime($lease_obj->inauguralDate)):null;
                                    $console_start_date_ymd = ( $lease_obj->startDate != '' )?date('Y-m-d',strtotime($lease_obj->startDate)):null;
                                    $console_end_date_ymd = ( $lease_obj->endDate != '' )?date('Y-m-d',strtotime($lease_obj->endDate)):null;
                                    $console_vacating_date_ymd = ( $lease_obj->vacating_date != '' )?date('Y-m-d',strtotime($lease_obj->vacating_date)):null;

                                } 

                            }                           
                    
                            $crm_is_qld_2022_compliance = ( $console_prop_row->qld_2022_comp == 1 )?true:false;
                            $console_is_qld_2022_compliance = ( $prop_comp_obj->has2022LegislationCompliance == true )?true:false;

                            $p_address_search = "{$console_prop_row->unit_num} {$console_prop_row->street_name} {$console_prop_row->suburb}";

                            $display_row = false;                            
                            if( $console_prop_row->crm_prop_id != '' ){ // connected

                                // for connected properties, only show the one where it's agency is from its connected CRM property
                                if( $console_prop_row->p_agency_id == $console_prop_row->cak_agency_id ){
                                    $display_row = true;
                                }

                            }else{ // not connected
                                $display_row = true;
                            }

                            if( $display_row == true ){
                                ?>
                                
                                <tbody class="prop_tbody">

                                    <tr class="<?php //echo ( $console_prop_row->cp_hidden == 1 )?'bg-danger':null; ?>">
                                        <td><?php echo $console_prop_row->full_address; ?></td>
                                        <td>
                                            <a href="/agency/view_agency_details/<?php echo $console_prop_row->agency_id; ?>" target="_blank"><?php echo $console_prop_row->agency_name; ?></a>
                                        </td>
                                        <td>
                                            <?php
                                            if( $console_prop_row->crm_prop_id != '' ){ ?>
                                                <a href="/properties/details/?id=<?php echo $console_prop_row->crm_prop_id; ?>" target="_blank">
                                                    <button type="button" class="btn btn-primary">View in CRM</button>
                                                </a>
                                            <?php
                                            }else{ ?>
                                                <a 
                                                    href="/console/bulk_connect/?agency_id=<?php echo $console_prop_row->agency_id; ?>&p_address_search=<?php echo $p_address_search; ?>" 
                                                    target="_blank"
                                                >
                                                    <button type="button" class="btn btn-primary-outline">Connect</button>
                                                </a>
                                            <?php
                                            }
                                            ?>                                        
                                        </td>   
                                        <td><?php echo ( $console_prop_row->cp_hidden == 1 )?'<span class="text-danger">Hidden</span>':'<span class="text-success">Active</span>'; ?></td>                                 
                                        <td>
                                        <?php
                                        // get console property                
                                        $this->db->select('
                                        cwd.`id` AS cwd_id,
                                        cwd.`event_type`,
                                        cwd.`json`,
                                        cwd.`date` AS cwd_date,
                                        cwd.`actioned_by` AS cwd_actioned_by,
                                        cwd.`actioned_ts` AS cwd_actioned_ts,

                                        sa.`StaffID`,
                                        sa.`FirstName` AS sa_fname,
                                        sa.`LastName` AS sa_lname
                                        ');
                                        $this->db->from('console_webhooks_data AS cwd');  
                                        $this->db->join('staff_accounts AS sa', 'cwd.`actioned_by` = sa.`StaffID`', 'left');
                                        $this->db->where('cwd.`active`', 1);      
                                        if( $this->input->get_post('office_id_filter') > 0 ){            
                                            $this->db->where('cwd.office_id', $this->input->get_post('office_id_filter'));
                                        }
                                        $this->db->where('cwd.`console_prop_id`', $console_prop_row->console_prop_id);               
                                        $this->db->order_by('cwd.`date DESC, cwd.`id DESC');
                                        $webhooks_data_sql = $this->db->get(); 
                                        $webhooks_single_row = $webhooks_data_sql->row();

                                        $find_arr = ['property_compliance', '_'];
                                        $replace_arr   = ['COMPLIANCE', ' '];
                                        echo str_ireplace($find_arr, $replace_arr, $webhooks_single_row->event_type);
                                        ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary show_details_btn">View Webhooks</button>
                                            <button type="button" class="btn btn-success hide_btn">Mark as Processed</button>
                                            <input type="hidden" class="console_prop_id" value="<?php echo $console_prop_row->console_prop_id ?>" />
                                        </td>										                            
                                    </tr>

                                    <tr class="prop_details">
                                        <td colspan="100%" class="webhook_details_td">
                                                    
                                            <table class="table main-table">

                                                <tr>
                                                    <th>Address</th>   
                                                    <th>Other Info</th>    
                                                    <th>Tenancy Agreement</th>
                                                    <th>Users</th>                                 
                                                </tr>
                                                <tr>

                                                    <td class="align-top">
                                                        <table class="table">

                                                            <tr>    
                                                                <th>Unit Number</th>
                                                                <td class="<?php echo ( $address_obj->unitNumber != '' && $console_prop_row->unit_num != $address_obj->unitNumber )?'bg-warning':null; ?>">
                                                                    <?php echo $console_prop_row->unit_num; ?>
                                                                </td> 
                                                            </tr>
                                                            <tr>
                                                                <th>Street Number</th>
                                                                <td class="<?php echo ( $address_obj->streetNumber != '' && $console_prop_row->street_num != $address_obj->streetNumber )?'bg-warning':null; ?>">
                                                                    <?php echo $console_prop_row->street_num; ?>
                                                                </td>	  
                                                            </tr>
                                                            <tr> 
                                                                <th>Street Name</th>
                                                                <td class="<?php echo ( $address_obj->streetName != '' && $console_prop_row->street_name != $address_obj->streetName )?'bg-warning':null; ?>">
                                                                    <?php echo $console_prop_row->street_name; ?>
                                                                </td> 
                                                            </tr>
                                                            <tr>
                                                                <th>Street Type</th> 
                                                                <td class="<?php echo ( $address_obj->streetType != '' && $console_prop_row->street_type != $address_obj->streetType )?'bg-warning':null; ?>">
                                                                    <?php echo $console_prop_row->street_type; ?>
                                                                </td>
                                                            </tr>
                                                            <tr> 
                                                                <th>Suburb</th>
                                                                <td class="<?php echo ( $address_obj->suburb != '' && $console_prop_row->suburb != $address_obj->suburb )?'bg-warning':null; ?>">
                                                                    <?php echo $console_prop_row->suburb; ?>
                                                                </td> 
                                                            </tr>
                                                            <tr>
                                                                <th>Postcode</th>
                                                                <td class="<?php echo ( $address_obj->postCode != '' && $console_prop_row->postcode != $address_obj->postCode )?'bg-warning':null; ?>">
                                                                    <?php echo $console_prop_row->postcode; ?>
                                                                </td> 
                                                            </tr>
                                                            <tr>
                                                                <th>State</th>
                                                                <td class="<?php echo ( $address_obj->stateCode != '' && $console_prop_row->state != $address_obj->stateCode )?'bg-warning':null; ?>">
                                                                    <?php echo $console_prop_row->state; ?>
                                                                </td>                               						                           
                                                            </tr>

                                                        </table>
                                                    </td>

                                                    <td class="align-top">                                 
                                                        <table class="table">
                                                                                                                                                                            
                                                            <tr>    
                                                                <th>Key Number</th>
                                                                <td class="<?php echo ( $prop_obj->keyNumber != '' && $console_prop_row->key_number != $prop_obj->keyNumber )?'bg-warning':null; ?>"><?php echo $console_prop_row->key_number; ?></td> 
                                                            </tr> 
                                                            <tr>    
                                                                <th>Access Details</th>
                                                                <td class="<?php echo ( $prop_obj->access_details != '' && $console_prop_row->access_details != $prop_obj->access_details )?'bg-warning':null; ?> text-danger"><?php echo $console_prop_row->access_details; ?></td> 
                                                            </tr>
                                                            <tr>    
                                                                <th>Property Type</th>
                                                                <td class="<?php echo ( $prop_obj->property_type != '' && $console_prop_row->property_type != $prop_obj->property_type )?'bg-warning':null; ?>"><?php echo ucwords(strtolower($console_prop_row->property_type)); ?></td> 
                                                            </tr> 
                                                            <tr>    
                                                                <th>Property Use</th>
                                                                <td class="<?php echo ( $prop_obj->propertyUse != '' && strtolower($console_prop_row->property_use) != strtolower($prop_obj->propertyUse) )?'bg-warning':null; ?>"><?php echo ucwords(strtolower($console_prop_row->property_use)); ?></td> 
                                                            </tr>    
                                                            <tr>
                                                                <th>Last Actioned</th>
                                                                <td><?php echo ( $this->system_model->isDateNotEmpty($console_prop_row->actioned_ts) )?date("d/m/Y H:i",strtotime($console_prop_row->actioned_ts)):''; ?></td>
                                                            </tr>                              

                                                        </table>
                                                    </td>

                                                    <td class="align-top">
                                                        <?php

                                                        if( $console_prop_row->console_prop_id > 0 ){

                                                            // get console tenant agreements                
                                                            $this->db->select('*');
                                                            $this->db->from('console_tenant_agreements'); 
                                                            $this->db->where('console_prop_id', $console_prop_row->console_prop_id);
                                                            $this->db->where('active', 1);
                                                            $cta_sql = $this->db->get();

                                                            foreach( $cta_sql->result() as $cta_row ){                                                                                                                     
                                                            ?>
                                                                <table class="table">
                                                                    <tr>
                                                                        <th>Lease Name</th>
                                                                        <td class="<?php echo ( $ten_agree_obj->leaseName != '' && $cta_row->lease_name != $ten_agree_obj->leaseName )?'bg-warning':null; ?>"><?php echo $cta_row->lease_name; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Inaugural Date</th>
                                                                        <td class="<?php echo ( $lease_obj->inauguralDate != '' && $cta_row->inaugural_date != $console_inaugural_date_ymd )?'bg-warning':null; ?>"><?php echo ( $cta_row->inaugural_date !='' )?date('d/m/Y',strtotime($cta_row->inaugural_date)):null; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Start Date</th>
                                                                        <td class="<?php echo ( $lease_obj->startDate != '' && $cta_row->start_date != $console_start_date_ymd )?'bg-warning':null; ?>"><?php echo ( $cta_row->start_date !='' )?date('d/m/Y',strtotime($cta_row->start_date)):null; ?></td>
                                                                    </tr>  
                                                                    <tr>
                                                                        <th>End Date</th>
                                                                        <td class="<?php echo ( $lease_obj->endDate != '' && $cta_row->end_date != $console_end_date_ymd )?'bg-warning':null; ?>"><?php echo ( $cta_row->end_date != '' )?date('d/m/Y',strtotime($cta_row->end_date)):null; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Vacating Date</th>
                                                                        <td class="<?php echo ( $lease_obj->vacating_date != '' && $cta_row->vacating_date != $console_vacating_date_ymd )?'bg-warning':null; ?>"><?php echo ( $cta_row->vacating_date !=''  )?date('d/m/Y',strtotime($cta_row->vacating_date)):null; ?></td>
                                                                    </tr>                                                                                                                      
                                                                </table>
                                                            <?php
                                                            }
                                                            
                                                        }                                                   
                                                        ?>
                                                    </td> 

                                                    <td class="align-top">                                 
                                                        <?php
                                                        if( $console_prop_row->console_prop_id ){

                                                            // get console tenant agreements                
                                                            $this->db->select('*');
                                                            $this->db->from('console_users'); 
                                                            $this->db->where('console_prop_id', $console_prop_row->console_prop_id);
                                                            $this->db->where('active', 1);
                                                            $cu_sql = $this->db->get();

                                                            foreach( $cu_sql->result() as $cu_row ){                                                             
                                                            ?>
                                                                <table class="table">
                                                                    <tr>
                                                                        <th>First Name</th><td><?php echo $cu_row->first_name; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Last Name</th><td><?php echo $cu_row->last_name; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Last Name</th><td><?php echo $cu_row->email; ?></td>
                                                                    </tr>
                                                                </table>
                                                            <?php
                                                            }
                                                            
                                                        }                                                    
                                                        ?>
                                                    </td>

                                                </tr>

                                            </table>

                                            <table class="table main-table">

                                                <tr>
                                                    <th colspan="100%">Compliance</th>
                                                </tr>

                                                <tr>    
                                                    <th>Service Type</th>
                                                    <th>QLD 2022 Compliance</th>
                                                    <th>Last Inspected</th>
                                                    <th>Expiry Date</th>
                                                    <th>Notes</th>
                                                </tr>
                                                
                                                <?php
                                                $prop_comp_sql = $this->db->query("
                                                SELECT 
                                                    `service_type`,
                                                    `compliance_notes`,
                                                    `expiry_date`,
                                                    `last_inspection`,
                                                    `qld_2022_comp`
                                                FROM `console_property_compliance`
                                                WHERE `console_prop_id` = {$console_prop_row->console_prop_id}
                                                AND `active` = 1
                                                ");
                                                foreach( $prop_comp_sql->result() as $prop_comp_row ){ ?>
                                                    <tr>
                                                        <td><?php echo $prop_comp_row->service_type; ?></td>
                                                        <td><?php echo ( $prop_comp_row->qld_2022_comp == 1 )?'<span class="text-success">Yes</span>':'<span class="text-danger">No</span>'; ?></td>
                                                        <td><?php echo ( $prop_comp_row->last_inspection != '' )?date('d/m/Y',strtotime($prop_comp_row->last_inspection)):null; ?></td>
                                                        <td><?php echo ( $prop_comp_row->expiry_date != '' )?date('d/m/Y',strtotime($prop_comp_row->expiry_date)):null; ?></td>
                                                        <td><?php echo $prop_comp_row->compliance_notes; ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>

                                            </table>

                                            <table class="table main-table mb-5">

                                                <tr>
                                                    <th colspan="100%">Tenants</th>
                                                </tr>

                                                <tr>    
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                </tr>  
                                                
                                                <?php
                                                if( $console_prop_row->console_prop_id > 0 ){

                                                    // get console tenants                  
                                                    $this->db->select('*');
                                                    $this->db->from('console_property_tenants AS cpt');
                                                    $this->db->join('`console_properties` AS cp', '( cpt.`console_prop_id` = cp.`console_prop_id` AND cp.`active` = 1 )', 'inner');
                                                    $this->db->where('cp.console_prop_id', $console_prop_row->console_prop_id);
                                                    $this->db->where('cpt.active', 1);
                                                    $this->db->where('cpt.is_landlord', 0);
                                                    $console_tenant_sql = $this->db->get();
                                                    
                                                    foreach( $console_tenant_sql->result() as $console_tenant_row ){ ?>

                                                        <tr class="<?php echo ( $console_tenant_row->new_tenants_ts != '' )?'bg-success':null;  ?>">
                                                            <td class="<?php echo ( $console_tenant_row->first_name_updated_ts != '' )?'bg-warning':null;  ?>"><?php echo $console_tenant_row->first_name; ?></td>
                                                            <td class="<?php echo ( $console_tenant_row->last_name_updated_ts != '' )?'bg-warning':null;  ?>"><?php echo $console_tenant_row->last_name; ?></td>
                                                            <td>
                                                                <table clas="table">
                                                                    <tr>
                                                                        <th>Type</th>
                                                                        <th>Number</th>
                                                                        <th>Primary</th>                                                        
                                                                    </tr>
                                                                    <?php
                                                                    if( $console_tenant_row->contact_id > 0 ){

                                                                        // get tenants phone                
                                                                        $this->db->select('*');
                                                                        $this->db->from('console_property_tenant_phones AS cpt_phones');
                                                                        $this->db->join('console_property_tenants AS cpt', 'cpt_phones.contact_id = cpt.contact_id', 'inner');											
                                                                        $this->db->where('cpt.contact_id', $console_tenant_row->contact_id);
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
                                                                                    <?php echo ( $cpt_phone_row->is_primary == 1 )?'Yes':'No'; ?>
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
                                                                    if( $console_tenant_row->contact_id > 0 ){

                                                                        // get tenants email                
                                                                        $this->db->select('*');
                                                                        $this->db->from('console_property_tenant_emails AS cpt_emails');
                                                                        $this->db->join('console_property_tenants AS cpt', 'cpt_emails.contact_id = cpt.contact_id', 'inner');											
                                                                        $this->db->where('cpt.contact_id', $console_tenant_row->contact_id);
                                                                        $this->db->where('cpt_emails.active', 1);
                                                                        $cpt_emails_sql = $this->db->get();												

                                                                        foreach ( $cpt_emails_sql->result() as $cpt_emails_row ){ ?>
                                                                            <tr>
                                                                                <td><?php echo ucwords(strtolower($cpt_emails_row->type)); ?></td>
                                                                                <td>
                                                                                    <?php echo $cpt_emails_row->email; ?>															
                                                                                </td>
                                                                                <td>
                                                                                    <?php echo ( $cpt_emails_row->is_primary == 1 )?'Yes':'No'; ?>
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

                                            </table>  

                                            <?php
                                            if( $console_prop_row->console_prop_id > 0 ){
 
                                                if( $webhooks_data_sql->num_rows() > 0 ){
                                                ?>
                                                    <h5>Webhooks: </h5>
                                                    <table class="table mb-3">
                                                        <tr>
                                                            <th>Event Type</th>
                                                            <th>Content</th>
                                                            <th>Date Recieved</th>      
                                                            <th>Actioned By</th>  
                                                            <th>Actioned Date</th>                                        
                                                        </tr>
                                                        <?php                                            
                                                        foreach( $webhooks_data_sql->result() as $webhooks_row ){ ?>
                                                            <tr>
                                                                <td>
                                                                    <?php 
                                                                    $find_arr = ['property_compliance', '_'];
                                                                    $replace_arr   = ['COMPLIANCE', ' '];
                                                                    echo str_ireplace($find_arr, $replace_arr, $webhooks_row->event_type);
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" class="cwd_id" value=<?php echo $webhooks_row->cwd_id ?> />
                                                                    <button type="button" class="btn <?php echo ( $this->system_model->isDateNotEmpty($webhooks_row->cwd_actioned_ts) )?'btn-success':''; ?> view_webhook_data_btn">View</button>                                                                
                                                                </td>
                                                                <td><?php echo ( $this->system_model->isDateNotEmpty($webhooks_row->cwd_date) )?date("d/m/Y H:i",strtotime($webhooks_row->cwd_date)):''; ?></td>                   
                                                                <td><?php echo $this->system_model->formatStaffName($webhooks_row->sa_fname,$webhooks_row->sa_lname); ?></td>
                                                                <td><?php echo ( $this->system_model->isDateNotEmpty($webhooks_row->cwd_actioned_ts) )?date("d/m/Y H:i",strtotime($webhooks_row->cwd_actioned_ts)):''; ?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>                                            
                                                    </table>
                                                <?php
                                                }

                                            }
                                            ?>

                                        </td>
                                    </tr>

                                </tbody>

                                <?php
                            }
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
	<p>This page shows all properties that are not upgraded to the NEW QLD legislation</p>
<pre><code><?php echo $page_query; ?></code></pre>

</div>

<div id="display_webhook_data_breakdown_fb" class="fancybox" style="display:none;" >test</div>
<script>
jQuery(document).ready(function(){

    jQuery(".show_details_btn").click(function(){

        var dom = jQuery(this);
        var orig_txt = 'View Webhooks';

        if( dom.text() == orig_txt ){ // view webhooks

            dom.parents(".prop_tbody").find(".prop_details").show();
            dom.text('Close Webhooks');

        }else{ // close webhooks

            dom.parents(".prop_tbody").find(".prop_details").hide();
            dom.text(orig_txt);

        }        

    });

    // load webhook data breakdown
    jQuery(".view_webhook_data_btn").click(function(){

        var dom = jQuery(this);
        var parent_td = dom.parents("td:first");
        var cwd_id = parent_td.find(".cwd_id").val();

        if( cwd_id > 0 ){

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/console/display_webhook_data_breakdown",
                data: { 	
                    cwd_id: cwd_id
                }
            }).done(function( ret ){
                    
                $('#load-screen').hide();
                jQuery("#display_webhook_data_breakdown_fb").html(ret)
                jQuery.fancybox.open({
                    src  : '#display_webhook_data_breakdown_fb'
                })	

            });

        }        	

    });

    jQuery(".hide_btn").click(function(){

        var dom = jQuery(this);
        var parent_td = dom.parents("td:first");
        var console_prop_id = parent_td.find(".console_prop_id").val();

        if( console_prop_id > 0 ){

            swal({
                title: "",
                text: "Are you sure you have processed all webhooks for this property? This will hide from the list, proceed?",
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
                        url: "/console/hide_console_property",
                        data: { 	
                            console_prop_id: console_prop_id
                        }
                    }).done(function( ret ){
                            
                        $('#load-screen').hide();
                        location.reload();
                    
                    });
                }

            });	            

        }        	

    });    

    jQuery('#display_webhook_data_breakdown_fb').on('click','.update_tenant_btn',function(){

        var dom = jQuery(this);
        var parent_td = dom.parents("#display_webhook_data_breakdown_fb:first");
        var cwd_id = parent_td.find(".cwd_id").val();

        if( cwd_id > 0 ){

            swal({
                title: "",
                text: "This will apply the webhook data to the current property info. Would you like to continue?",
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
                        url: "/console/apply_console_webhook_data",
                        data: { 	
                            cwd_id: cwd_id
                        }
                    }).done(function( ret ){
                            
                        $('#load-screen').hide();
                        swal({
                            title: "Success!",
                            text: "Webhook data applied to property info successfully!",
                            type: "success",
                            confirmButtonClass: "btn-success",
                            showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                            timer: <?php echo $this->config->item('timer') ?>
                        });
                        setTimeout(function(){ window.location='/console/unprocessed_webhooks'; }, <?php echo $this->config->item('timer') ?>);	                        
                    
                    });
                }

            });	            

        }        	

    }); 
    
});
</script>

