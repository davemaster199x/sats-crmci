<style>
    a[data-fancybox]:hover .font-icon {
        color: red;
    }
    .tbl-last-col {
        display: flex;
        align-items: center;
        /* justify-content: space-evenly; */
    }
</style>

<div class="tenants_landlord_box">

    <div class="tenants_landlord">
                            
        <!-- Member -->
        <section class="card card-blue-fill">
            <header class="card-header">Tenant Details</header>
                <div class="card-block" style="padding: 5px;">
                    <div class="col-md-12 columns">
                        <!-- tenants tab -->
                        <section class="tabs-section loader_wrapper_pos_rel tenant_section" style="margin-bottom:0px;">
                            <div class="loader_block_v2" style="display: none;"> <div id="div_loader"></div></div>
                            <div class="tenants_ajax_box"></div>
                        </section>
                        <!-- tenants tab end -->
                    </div>
                </div>
        </section>
        <!-- End Member -->

        <!-- Landlord -->
        <section class="card card-blue-fill">
            <header class="card-header">Landlord Details</header>
                <div class="card-block" style="padding: 5px;">
                    <div class="col-md-12 columns"> 
                        <table class="table vpd_table table-sm tbl_active table-no-border">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Mobile</th>
                                    <th>Landline</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php echo $row['landlord_firstname']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['landlord_lastname']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['landlord_mob']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['landlord_ph']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['landlord_email']; ?>
                                    </td>
                                    <td>
                                        <a href="#" data-auto-focus="false" data-fancybox data-src="#fancybox_show_landlord" style="display: inline-block; cursor: pointer;"><span class="font-icon font-icon-pencil"></span></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
        </section>
        <!-- End Landlord -->

        <?php 
            $newAPI_ID = $row['api_prop_id'];

            // get pme property
            $end_points = "https://app.propertyme.com/api/v1/lots/".$newAPI_ID."/detail";
            $api_id = 1; // PMe

            // get access token
            $pme_params = array(
                'agency_id' => $agency_id,
                'api_id' => $api_id
            );
            $access_token = $this->pme_model->getAccessToken($pme_params);

            $pme_params = array(
                'access_token' => $access_token,
                'end_points' => $end_points
            );

            $pme_prop_json = $this->pme_model->call_end_points_v2($pme_params);
            $pme_prop_json_enc = json_decode($pme_prop_json);
            $ownerId = $pme_prop_json_enc->Ownership->ContactId;

            // get pme owner
            $agency_api_params = array(
                'contact_id' =>  $ownerId,
                'agency_id' => $agency_id
            );
            $contact_json = $this->properties_model->get_contact($agency_api_params);
            $contact_json_enc = json_decode($contact_json);

            $pme_landlord_arr = [];
            foreach( $contact_json_enc->ContactPersons as $pme_tenant ){
                $pme_landlord_arr[] = array(
                    'fname' => trim($pme_tenant->FirstName),
                    'lname' => trim($pme_tenant->LastName),
                    'mobile' => str_replace(' ', '', trim($pme_tenant->CellPhone)),
                    'landline' => str_replace(' ', '', trim($pme_tenant->HomePhone)),
                    'email' => trim($pme_tenant->Email)
                );
            }
        
        ?>

        <!-- PropertyMe Landlord -->
        <section class="card card-blue-fill" style="display: <?=empty($pme_landlord_arr) ? "none" : ""; ?>">
            <header class="card-header">PropertyMe Landlord</header>
                <div class="card-block" style="padding: 5px;">
                    <div class="col-md-12 columns"> 
                        <table class="table vpd_table table-sm tbl_active table-no-border">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Mobile</th>
                                    <th>Landline</th>
                                    <th>Email</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $countLandlord = 0;
                                foreach ($pme_landlord_arr as $landlord) {
                            ?>
                                <tr>
                                    <td><?php echo $landlord['fname'] ?></td>
                                    <td><?php echo $landlord['lname'] ?></td>
                                    <td><?php echo $landlord['mobile'] ?></td>
                                    <td><?php echo $landlord['landline'] ?></td>
                                    <td><?php echo $landlord['email'] ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger" onclick="copyToCRM(this)" data-auto-focus="false" data-fancybox data-src="#fancybox_show_landlord">Copy to CRM</button>
                                    </td>
                                </tr>
                            <?php
                                    $countLandlord++;
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </section>
        <!-- End PropertyMe Landlord -->

        <?php
            $agency_api_params = array(
                'prop_id' => $row['api_prop_id'],
                'agency_id' => $agency_id
            );

            $owner_json = $this->properties_model->get_palace_landlord($agency_api_params);
            $owner_json_enc = $owner_json;

            $palace_landlord_arr = [];
            foreach( $owner_json_enc as $palace_owner ){
                $palace_landlord_arr[] = array(
                    'fname' => trim($palace_owner->OwnerFirstName),
                    'lname' => trim($palace_owner->OwnerLastName),
                    'mobile' => str_replace(' ', '', trim($palace_owner->OwnerMobile)),
                    'landline' => str_replace(' ', '', trim($palace_owner->OwnerPhoneHome)),
                    'email' => trim($palace_owner->OwnerEmail1)
                );
            }
        ?>
        <!-- Palace Landlord -->
        <section class="card card-blue-fill" style="display: <?=empty($palace_landlord_arr) ? "none" : ""; ?>">
            <header class="card-header">Palace Landlord</header>
                <div class="card-block" style="padding: 5px;">
                    <div class="col-md-12 columns"> 
                        <table class="table vpd_table table-sm tbl_active table-no-border">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Mobile</th>
                                    <th>Landline</th>
                                    <th>Email</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $countLandlord = 0;
                                foreach ($palace_landlord_arr as $landlord) {
                            ?>
                                <tr>
                                    <td><?php echo $landlord['fname'] ?></td>
                                    <td><?php echo $landlord['lname'] ?></td>
                                    <td><?php echo $landlord['mobile'] ?></td>
                                    <td><?php echo $landlord['landline'] ?></td>
                                    <td><?php echo $landlord['email'] ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger" onclick="copyToCRM(this)" data-auto-focus="false" data-fancybox data-src="#fancybox_show_landlord">Copy to CRM</button>
                                    </td>
                                </tr>
                            <?php
                                    $countLandlord++;
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </section>
        <!-- End Palace Landlord -->

        <?php   
        if( $console_prop_sql->num_rows() > 0 ){

            $console_prop_row = $console_prop_sql->row();

            // get console landlords                  
            $this->db->select('*');
            $this->db->from('console_property_tenants AS cpt');
            $this->db->join('`console_properties` AS cp', '( cpt.`console_prop_id` = cp.`console_prop_id` AND cp.`active` = 1 )', 'inner');
            $this->db->where('cp.console_prop_id', $console_prop_row->console_prop_id);
            $this->db->where('cpt.active', 1);
            $this->db->where('cpt.is_landlord', 1);
            $console_tenant_sql = $this->db->get();

            if( $console_tenant_sql->num_rows() > 0 ){
            ?>

                <!-- Console Landlord -->       
                <section class="card card-blue-fill">
                    <header class="card-header">Console Landlord</header>
                        <div class="card-block" style="padding: 5px;">
                            <div class="col-md-12 columns"> 
                            
                                <table class="table main-table">

                                    <tr>    
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>  

                                    <?php
                                    foreach( $console_tenant_sql->result() as $console_tenant_row ){ ?>

                                        <tr>
                                            <td class="console_landlord_fname_td"><?php echo $console_tenant_row->first_name; ?></td>
                                            <td class="console_landlord_lname_td"><?php echo $console_tenant_row->last_name; ?></td>
                                            <td>
                                                <table clas="table">
                                                    <tr>
                                                        <th>Type</th>
                                                        <th>Number</th>
                                                        <th>Primary</th> 
                                                        <th>Select As</th>                                                       
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
                                                                <td class="console_landlord_number"><?php echo $cpt_phone_row->number; ?></td>
                                                                <td><?php echo ( $cpt_phone_row->is_primary == 1 )?'Yes':'No'; ?></td>  
                                                                <td>
                                                                    <select class="form-control console_ll_select_phone_type">
                                                                        <option value="">---</option>
                                                                        <option value="1">Mobile</option>
                                                                        <option value="2">Landline</option>
                                                                    </select>
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
                                                        <th>Select</th>                                                      
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
                                                                <td class="console_landlord_email"><?php echo $cpt_emails_row->email; ?></td>
                                                                <td><?php echo ( $cpt_emails_row->is_primary == 1 )?'Yes':'No'; ?></td> 
                                                                <td>
                                                                    <input type="radio" name="console_landlord_email_radio" class="console_ll_select_email" value="<?php echo $cpt_emails_row->email; ?>" />
                                                                </td>                                                               
                                                            </tr>
                                                        <?php
                                                        }

                                                    }											
                                                    ?>	
                                                </table>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" id="console_landlord_copy_to_crm" class="btn btn-danger" data-auto-focus="false" data-fancybox data-src="#fancybox_show_landlord">Copy to CRM</button>
                                            </td>
                                        </tr>

                                    <?php
                                    }                              
                                    ?>                                    

                                </table>

                            </div>
                        </div>
                </section>
                <!-- End Console Landlord -->

            <?php 
            }

        }              
        ?>
    </div>

</div>

    <div id="fancybox_show_landlord" style="display:none; min-width: 600px; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >Landlord Details</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                            <table class="table table-bordered vpd_table table-sm tbl_active">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Mobile</th>
                                    <th>Landline</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" name="landlord_firstname" id="landlord_firstname" class="form-control" value="<?php echo $row['landlord_firstname']; ?>" placeholder="First Name">
                                    </td>
                                    <td>
                                        <input type="text" name="landlord_lastname" id="landlord_lastname" class="form-control" value="<?php echo $row['landlord_lastname']; ?>" placeholder="Last Name">
                                    </td>
                                    <td>
                                        <input type="text" name="landlord_mob" id="landlord_mob" class="form-control" value="<?php echo $row['landlord_mob']; ?>" placeholder="Mobile">
                                    </td>
                                    <td>
                                        <input type="text" name="landlord_ph" id="landlord_ph" class="form-control" value="<?php echo $row['landlord_ph']; ?>" placeholder="Landline">
                                    </td>
                                    <td>
                                        <input type="email" name="landlord_email" id="landlord_email" class="form-control" value="<?php echo $row['landlord_email']; ?>" placeholder="Email">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
        <div class="text-right">
            <button class="btn btn-primmary" onclick="update_landlord()">Save</button>
        </div>
    </div>

<script type="text/javascript">
    function copyToCRM(buttonElement) {
        // Find the closest <tr> element to the clicked button
        var closestTr = buttonElement.closest('tr');

        // Add your logic here to work with the <tr> element
        // For example, you can access the data in the <td> elements within the <tr> like this:
        var fname = closestTr.querySelector('td:nth-child(1)').textContent;
        var lname = closestTr.querySelector('td:nth-child(2)').textContent;
        var mobile = closestTr.querySelector('td:nth-child(3)').textContent;
        var landline = closestTr.querySelector('td:nth-child(4)').textContent;
        var email = closestTr.querySelector('td:nth-child(5)').textContent;

        $('#landlord_firstname').val(fname);
        $('#landlord_lastname').val(lname);
        $('#landlord_mob').val(mobile);
        $('#landlord_ph').val(landline);
        $('#landlord_email').val(email);

        // Now you can use the extracted data as needed, such as sending it to your CRM
        console.log("First Name:", fname);
        console.log("Last Name:", lname);
        console.log("Mobile:", mobile);
        console.log("Landline:", landline);
        console.log("Email:", email);
    }

    $(document).ready(function(){

         //load tenants ajax box (via ajax)
         $('.loader_block_v2').show();
         $('.tenants_ajax_box').load('/jobs/tenants_ajax',{prop_id:<?php echo $row['property_id'] ?>}, function(response, status, xhr){
            $('.loader_block_v2').hide();
            $('[data-toggle="tooltip"]').tooltip(); //init tooltip
            phone_mobile_mask(); //init phone/mobile mask
            //mobile_validation(); //init mobile validation
            //phone_validation(); //init phone validation
            //add_validate_tenant(); //init new tenant validation
        });

        jQuery("#console_landlord_copy_to_crm").click(function(){

            // clear
            jQuery('#landlord_firstname').val('');
            jQuery('#landlord_lastname').val('');
            jQuery('#landlord_mob').val('');
            jQuery('#landlord_ph').val('');
            jQuery('#landlord_email').val('');

            var copy_btn_dom = jQuery(this);
            var parent_tr = copy_btn_dom.parents("tr:first");

            var fname = parent_tr.find(".console_landlord_fname_td").text();
            var lname = parent_tr.find(".console_landlord_lname_td").text();
            
            var select_email_dom = parent_tr.find(".console_ll_select_email:checked");
            var select_email_parent = select_email_dom.parents("tr:first");
            var email = select_email_parent.find(".console_landlord_email").text();
            
            jQuery('#landlord_firstname').val(fname);
            jQuery('#landlord_lastname').val(lname);

            jQuery(".console_ll_select_phone_type").each(function(){

                var phone_select_dom = jQuery(this);
                var parent_tr2 = phone_select_dom.parents("tr:first");                
                var number = parent_tr2.find(".console_landlord_number").text();
                var phone_select_as = phone_select_dom.val();

                if( phone_select_as == 1 ){ // mobile
                    jQuery('#landlord_mob').val(number);
                }else if( phone_select_as == 2 ){ // landline
                    jQuery('#landlord_ph').val(number);
                }

            });            

            jQuery('#landlord_email').val(email);

        });

    }); //document ready end

    function delete_landlord(){
        $('#landlord_firstname').val('');
        $('#landlord_lastname').val('');
        $('#landlord_mob').val('');
        $('#landlord_ph').val('');
        $('#landlord_email').val('');  
        update_landlord()
    }

    function update_landlord(){

    var landlord_firstname = $('#landlord_firstname').val();
    var landlord_lastname = $('#landlord_lastname').val();
    var landlord_mob = $('#landlord_mob').val();
    var landlord_ph = $('#landlord_ph').val();
    var landlord_email = $('#landlord_email').val();
    $('#load-screen').show();
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $property_id; ?>,
                landlord_firstname: landlord_firstname,
                landlord_lastname: landlord_lastname,
                landlord_mob: landlord_mob,
                landlord_ph: landlord_ph,
                landlord_email: landlord_email,
                property_update: 'update_landlord'
                
            }
        }).done(function( ret ) {	
            $('#load-screen').hide();
            if(ret.status){
                $('#load-screen').hide();
                swal({
                    title:"Success!",
                    text: "Update Successful",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "OK",
                    closeOnConfirm: false,  
                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                    timer: <?php echo $this->config->item('timer') ?>
                });
                var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=3";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        }); 
    }
</script>