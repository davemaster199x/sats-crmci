<?php
// breakdown json

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

// tenant update or compliance cancelled webhook type
if( $event_type == 'PROPERTY_COMPLIANCE_CONTACT_UPDATED' || $webhooks_row->event_type == 'PROPERTY_COMPLIANCE_CANCELLED' ){                                  

    // property address
    $cp_sql = $this->db->query("
    SELECT 
        cp.`crm_prop_id`,
        cp.`unit_num`,
        cp.`street_num`,
        cp.`street_name`,
        cp.`street_type`,
        cp.`suburb`,
        cp.`postcode`,
        cp.`state`,

        cpoi.`key_number`,
        cpoi.`access_details`,
        cpoi.`property_type`,
        cpoi.`property_use`,
        cpoi.`service_type`,

        cpc.`compliance_notes`,
        cpc.`expiry_date`,
        cpc.`last_inspection`,
        cpc.`qld_2022_comp`
    FROM `console_properties` AS cp
    LEFT JOIN `console_property_other_info` AS cpoi ON cp.`console_prop_id` = cpoi.`console_prop_id`
    LEFT JOIN `console_property_compliance` AS cpc ON cp.`console_prop_id` = cpc.`console_prop_id`
    WHERE cp.`console_prop_id` = {$webhooks_row->console_prop_id}
    AND cp.`active` = 1
    ");
    $cp_row = $cp_sql->row();

    $st_unit = $cp_row->unit_num;
    $st_num = $cp_row->street_num;
    $st_name = $cp_row->street_name;
    $st_type = $cp_row->street_type;
    $suburb = $cp_row->suburb;
    $postcode = $cp_row->postCode;
    $state = $cp_row->state;

    // other info section
    // service type
    $service_type = null;
    $service_type_class = null;
    if( $cp_row->service_type == 'Smoke Alarms' ){ // smoke alarms

        $service_type = 'Smoke Alarms';
        $service_type_class = 'text-danger';

    }else if( $cp_row->service_type == 'Residual Current Devices' ){ // safety switch

        $service_type = 'Safety Switch';
        $service_type_class = 'text-warning';

    }else if( $cp_row->service_type == 'Blinds' ){ // corded window
        
        $service_type = 'Corded Windows';
        $service_type_class = 'text-success';

    }else if( $cp_row->service_type == 'Water Efficiency' ){ // water efficiency

        $service_type = 'Water Efficiency';
        $service_type_class = 'text-primary';

    }

    $compliance_notes = $cp_row->compliance_notes;
    $key_number = $cp_row->key_number;
    $access_details = $cp_row->access_details;
    $property_type = ucwords(strtolower($cp_row->property_type));
    $expiry_date_txt = ( $cp_row->expiry_date != '' )?date('d/m/Y',strtotime($cp_row->expiry_date)):null;
    $last_ins_date_txt = ( $cp_row->last_inspection != '' )?date('d/m/Y',strtotime($cp_row->last_inspection)):null;
    $qld_2022_comp = $prop_comp_obj->qld_2022_comp;    
    
    
}else{ // other webhook type

    // property address
    $st_unit = $address_obj->unitNumber;
    $st_num = $address_obj->streetNumber;
    $st_name = $address_obj->streetName;
    $st_type = $address_obj->streetType;
    $suburb = $address_obj->suburb;
    $postcode = $address_obj->postCode;
    $state = $address_obj->stateCode;

    // other info section
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

    $compliance_notes = $prop_comp_obj->notes;
    $key_number = $prop_obj->keyNumber;
    $access_details = $prop_obj->access_details;
    $property_type = ucwords(strtolower($prop_obj->property_type));
    $expiry_date_txt = ( $expiry_date != '' )?date('d/m/Y',strtotime($expiry_date)):null;
    $last_ins_date_txt = ( $last_ins_date != '' )?date('d/m/Y',strtotime($last_ins_date)):null;
    $qld_2022_comp = ( $prop_comp_obj->has2022LegislationCompliance == true )?1:0;

}

// tenant header
if( $event_type == 'PROPERTY_COMPLIANCE_CONTACT_UPDATED' ){

    // tenants
    $tenant_data_arr[] = $event_obj->contact;
    $tenant_header = 'Updated Tenant';
    
}else{

    // tenants
    $tenant_data_arr = $rel_res_obj->contacts;
    $tenant_header = 'Tenants';

}
?>
<table class="table cwd_tbl">

    <tr>
        <th>Address</th>    
        <th>Other Info</th>    
        <th>Tenancy Agreement</th>
        <th>Users</th>
    </tr>
    <tr>                                        
        <td class="align-top pr-3">
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
        <td class="align-top pr-3">
            <table class="table">
                <tr>    
                    <th>Web Hook Event Type ID</th>
                    <td><?php echo $event_id; ?></td> 
                </tr>
                <tr>    
                    <th>Service Type</th>
                    <td>
                        <span class="<?php echo $service_type_class; ?>"><?php echo $service_type; ?></span>
                    </td> 
                </tr>
                <tr>    
                    <th>Compliance Notes</th><td class="text-danger"><?php echo $compliance_notes; ?></td> 
                </tr>                                                                                                                  
                <tr>    
                    <th>Key Number</th><td><?php echo $key_number; ?></td> 
                </tr> 
                <tr>    
                    <th>Access Details</th><td class="text-danger"><?php echo $access_details; ?></td> 
                </tr>
                <tr>    
                    <th>Property Type</th><td><?php echo $property_type; ?></td> 
                </tr>                                                       
                <tr>    
                    <th>Expiry Date</th><td><?php echo $expiry_date_txt; ?></td> 
                </tr>
                <tr>    
                    <th>Last Inspection</th><td><?php echo $last_ins_date_txt; ?></td> 
                </tr>                                               
                <tr>    
                    <th>QLD 2022 Compliance</th><td><?php echo ( $qld_2022_comp == 1 )?'<span class="text-success">Yes</span>':'<span class="text-danger">No</span>'; ?></td> 
                </tr>                                                                                      
            </table>
        </td>
        <td class="align-top pr-3">
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
                        <th>Last Name</th><td><?php echo $users_obj->email; ?></td>
                    </tr>
                </table>
            <?php
            }
            ?>
        </td>                            
    </tr>

</table>

<h5 class="mt-3"><?php echo $tenant_header; ?>:</h5>
<table class="table mb-3">
    <tr>
        <th colspan="2">Name</th>
        <th>Phone</th>
        <th>Email</th>
    </tr>
<?php                          
foreach( $tenant_data_arr as $contacts_obj ){ 

    $contact_id = $contacts_obj->contactId;

    if( !in_array($contact_id,$landlords_arr) ){ // exclude landlords
    
    $person_det_obj = $contacts_obj->personDetail;
    $phones_arr_obj = $contacts_obj->phones;
    $emails_arr_obj = $contacts_obj->emails;
    ?>                                        
        <tr>                                        
            <td><?php echo $person_det_obj->firstName; ?></td>
            <td><?php echo $person_det_obj->lastName; ?></td>
            <td>
                <table clas="table">
                    <tr>
                        <th>Type</th>
                        <th>Number</th>
                        <th>Primary</th>                                                        
                    </tr>
                    <?php
                    foreach( $phones_arr_obj as $phones_obj ){ ?>
                        <tr>
                            <td><?php echo ucwords(strtolower($phones_obj->type)); ?></td>
                            <td><?php echo $phones_obj->phoneNumber; ?></td>
                            <td>
                                <?php echo ( $phones_obj->is_primary == 1 )?'<span class="text-success">Yes</span>':'<span class="text-danger">No</span>'; ?>
                            </td>                                                                
                        </tr>
                    <?php
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
                    foreach ( $emails_arr_obj as $emails_obj ){ ?>
                        <tr>
                            <td><?php echo ucwords(strtolower($emails_obj->type)); ?></td>
                            <td>
                                <?php echo $emails_obj->emailAddress; ?>															
                            </td>
                            <td>
                                <?php echo ( $emails_obj->is_primary == 1 )?'<span class="text-success">Yes</span>':'<span class="text-danger">No</span>'; ?>
                            </td>                                                                
                        </tr>
                    <?php
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

<div class="text-center">
    <input type="hidden" class="cwd_id" value="<?php echo $webhooks_row->cwd_id; ?>" />
    <button type="button" class="btn update_tenant_btn">Update Tenant</button>
</div>
