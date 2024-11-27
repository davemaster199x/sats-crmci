
<style>
    .tab-content{
        border: solid 1px #d8e2e7;
        border-top:0px;
        padding: 30px 15px 0px 15px;
    }
    .card{
        text-align:left;
    }
    p.form-control-static{
        margin-bottom: 0px;
    }
    .statements_agency_comments_ts_span{
        color: #00d1e5;
        font-style: italic;
    }
    .card-red-fill{
        border: 1px solid #dc3545;
    }
    .card-red-fill header.card-header{
        background: #dc3545;a
        color: #fff;
    }
    .vad_cta_box .btn span{
        display: inline!important;
        margin-right: 10px!important;
        color:#fff!important;
    }
    .vad_cta_box{
        margin-top: 20px;
    }
    .vad_cta_box .btn{
        margin-bottom:10px;
    }
    .portal_user_table td,  .portal_user_table th{
        text-align: left;
    }
    .cta .btn span{
        display: inline-block!important;
        color:#fff!important;
    }
    .pagi_count{
        margin-bottom:10px;
    }
    .action_div{
        width: 70px;
    }
    .action_div a{
        display: inline-block;
    }
    .card-blue-fill .card-header{
        padding:8px 12px;
    }
    .tt_boxes{
        padding-right:30px;
    }
    .tt_boxes label{
        margin-bottom:5px;
        font-weight:700;
    }
    .text-capitalize{
        text-transform:capitalize;
    }
    .fancybox-content{
        min-width:500px;
    }
    .btn_del:hover .fa, .btn_delete:hover .glyphicon{
        color:#dc3545;
    }
    .clear_b{clear:both;}
    .th_div label{
        font-weight:700px;
        margin-bottom:5px;
    }
    .th_div span.fa,.th_div .font-icon{
        font-weight:700;
        color:#00a8ff!important;
    }
    .table-no-border{
        border:none;
    }
    .table-no-border td{
        border:none;
    }
    .table-no-border th{
        border:none;
        background:#fff!important;
    }
   /* .active_s{
        background: #fff!important;
    }
    .active_s a{
        border-bottom:0px!important;
    }*/
</style>

<div class="box-typical box-typical-padding">
<?php 
    // breadcrumbs template
    $bc_items = array(
        array(
            'title' => 'View Properties',
            'link' => "/properties/active_properties"
        ),
        array(
            'title' => $title,
            'status' => 'active',
            'link' => "/properties/details/?id={$_GET['id']}&tab={$_GET['tab']}"
        )
    );
    $bc_data['bc_items'] = $bc_items;
    $this->load->view('templates/breadcrumbs', $bc_data);
?>
    <div class="vad_box">
        
        <?php
        if($row['deleted'])
		{
			echo "<div id='permission_error' class='text-center alert alert-danger'>This Property is Deleted! If you want to Restore this property, please contact IT</div>";
		}
		elseif($row['agency_deleted'])
		{
			echo "<div id='permission_error' class='text-center alert alert-danger'>This property has been marked as no longer managed / deleted by the agency!</div>";
		}
		elseif($row['is_nlm'] == 1)
        {
			echo "<div id='permission_error' class='text-center alert alert-danger'>Property Marked No Longer Manager. Restore by Selecting Property Status</div>";
		}
        elseif( $row['status'] == 'deactivated' ){
			echo "<div id='permission_error' class='text-center alert alert-danger'>Agency is deactivated: You cannot create a new job while an Agency is deactivated.</div>";
		}

        // foreach( $api_token_sql->result_array() as $api_row){

		// 	if ( $api_row['api_id'] == 1 ){ // PMe

		// 		$connected_to_pme = true;

		// 		$agency_api_params = array(
		// 			'prop_id' =>  $row['api_prop_id'],
		// 			'agency_id' => $agency_id
		// 		);

		// 		$pme_prop_json = $this->api_model->get_property_pme($agency_api_params);
		// 		$pme_prop_json_dec = json_decode($pme_prop_json);

		// 		if( $pme_prop_json_dec->IsArchived == true ){
		// 			echo "<div id='permission_error' class='text-center alert alert-danger'>This Property is Deactivated in PropertyMe</div>";
		// 		}

		// 	}

		// 	if ( $api_row['api_id'] == 3 ){ // PropertyTree

		// 		// get tenants contact ID
		// 		$agency_api_params = array(
		// 			'property_id' => $property_id
		// 		);

		// 		$curl_ret_arr =  $this->api_model->get_property_tree_property($agency_api_params);

		// 		$raw_response = $curl_ret_arr['raw_response'];
		// 		$json_decoded_response = $curl_ret_arr['json_decoded_response'];
        //         $http_status_code = $curl_ret_arr['http_status_code'];

		// 		if( $http_status_code == 200 ){ // OK

		// 			$api_prop_obj = $json_decoded_response[0];

		// 			if( $api_prop_obj->archived == true || $api_prop_obj->deleted == true ){
		// 				echo "<div id='permission_error' class='text-center alert alert-danger'>This Property is Deactivated in PropertyTree</div>";
		// 			}

		// 		} else{ // error
        //             echo "<div id='permission_error' class='text-center alert alert-danger'>
        //                 API Request error, please notify IT via a ticket
        //                 <p>{$raw_response}</p>
        //             </div>";
		// 		}				

		// 	}

		// 	if ( $api_row['api_id'] == 4 ){ // Palace

		// 		$connected_to_palace = true;

		// 		$agency_api_params = array(
		// 			'prop_id' =>  $row['api_prop_id'],
		// 			'agency_id' => $agency_id
		// 		);

		// 		$palace_prop_json = $this->api_model->get_property_palace($agency_api_params);
		// 		$palace_prop_dec = json_decode($palace_prop_json);
		// 		//print_r($palace_prop_dec);


		// 		if( $palace_prop_dec->PropertyArchived == true ){
		// 			echo "<div id='permission_error' class='text-center alert alert-danger'>This Property is Deactivated in Palace</div>";
		// 		}


		// 	}

		// 	if ( $api_row['api_id'] == 6 ){ // Ourtradide

		// 		$connected_to_ourtradie = true;

		// 		$agency_api_params = array(
		// 			'prop_id' =>  $row['api_prop_id'],
		// 			'agency_id' => $agency_id
		// 		);

		// 		/*$ot_prop_json = $agency_api->get_property_ourtradie($agency_api_params);
		// 		$ot_prop_json_dec = json_decode($ot_prop_json);

		// 		if( $ot_prop_json_dec->IsArchived == true ){
		// 			echo "<div id='permission_error'>This Property is Deactivated in PropertyMe</div>";
		// 		}
		// 		*/


		// 	}

		// }
        //Disabled above api property archive warning/error message and replaced with vjd_vpd_apis_error_warning_message same function used in VJD 

        // copied this logic from vpd_details page
        $show_id_in_used = FALSE;
        if( $row['api_prop_id'] != '' ){

            $pt_join_sql = null;
            $pt_sql_filter = null;
            if( $row['api'] == 4 ){ // palace ONLY

                // join
                $pt_join_sql = "
                LEFT JOIN `agency` AS a ON  p.`agency_id` = a.`agency_id`
                LEFT JOIN `agency_api_tokens` AS aat ON a.`agency_id` = aat.`agency_id`
                ";

                // where filter
                $pt_sql_filter = "AND aat.`access_token` = '{$row['access_token']}'";

            }

            $isConnectedCheck_str = "
            SELECT
                p.`property_id`,
                p.`address_1`,
                p.`address_2`,
                p.`address_3`,
                p.`state`,
                p.`postcode`,
                p.`deleted`
            FROM `property` as p            
            LEFT JOIN `api_property_data` AS apd ON p.`property_id` = apd.`crm_prop_id`
            {$pt_join_sql}            
            WHERE apd.`api_prop_id` = '{$row['api_prop_id']}'
            AND p.`property_id` != {$property_id}
            {$pt_sql_filter}
            ORDER BY p.`address_2` ASC, p.`address_3` ASC, p.`address_1` ASC
            ";
            $connected_prop_sql = $this->db->query($isConnectedCheck_str);
            //echo $this->db->last_query();

            if( $connected_prop_sql->num_rows() ){
                $connected_prop_row = $connected_prop_sql->row_array();
                $connected_prop_full_add  = "{$connected_prop_row['address_1']} {$connected_prop_row['address_2']}, {$connected_prop_row['address_3']} {$connected_prop_row['state']}, {$connected_prop_row['postcode']}";

                $show_id_in_used = TRUE;
            }

        }
        ?>

        <div class="row">
            <div class="col text-center">
                <h3><a class="" data-auto-focus="false" data-fancybox data-src="#fancybox_address" href="javascript:;"><?=($prop_full_add != '') ? $prop_full_add:'No Data'; ?></a></h3>                
            </div>
        </div>

        <?php
        if( $show_id_in_used == true ){ ?>

            <div class='text-center alert alert-danger'>API property ID <?php echo $row['api_prop_id']; ?> already exists! click <a href="javascript:void(0);" data-fancybox data-src="#fancybox_id_in_used">HERE</a></div>
        
        <?php
        }
        ?>  
        
        <?php 
        /** New api tenants connection warning message
         *  Moved from tenants card to top part of page
         *  included API property archived check
         */
        $tenants_api_connection_check = $this->api_model->vjd_vpd_apis_error_warning_message($property_id);

        if(!empty($tenants_api_connection_check)):
            $api_status_color = ($tenants_api_connection_check['propertyIsConnected'] === true) ? 'warning' : 'danger';
        ?>
            <div class="row">
                <div class="col api_tenants_head_text text-center">
                    <div class="api_connection_warning_box alert alert-<?php echo $api_status_color ?> alert-iconss alert-close alert-dismissible fade show" role="alert">
                    <?php echo $tenants_api_connection_check['message']; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- pop up message -->
        <div id="fancybox_address" style="display:none; padding: 30px">
            <section class="card card-blue-fill">
                <header class="card-header">
                    <div class="row">
                        <div class="col-md-9" > <span >Address </span> </div>
                    </div> 
            </header>
                <div class="card-block">
                    
                    <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns" style="margin-bottom: 20px;">
                                <label class="form-label">Google Address Bar</label>
                                <input type='text' name='fullAdd' id='fullAdd' class='addinput vw-pro-dtl-tnt short-fld form-control' placeholder="Enter a location" value="<?=($prop_full_add != '') ? $prop_full_add:'No Address'; ?>" />
                            </div><br>
                            <div class="col-md-2 columns">
                                <div class="form-group">
                                    <label class="form-label">No.</label>
                                    <input type='text' name='address_1' id='address_1' value="<?php echo $row['address_1'] ?>" class='form-control vw-pro-dtl-tnt short-fld'>
                                    <input type='hidden' name='og_address_1' id='og_address_1' value="<?php echo $row['address_1'] ?>" class='form-control vw-pro-dtl-tnt short-fld'>
                                </div>
                            </div>
                            <div class="col-md-4 columns">
                                <div class="form-group">
                                    <label class="form-label">Street</label>
                                    <input type='text' name='address_2' id='address_2' value="<?php echo $row['address_2'] ?>" class='form-control vw-pro-dtl-tnt long-fld streetinput'>
                                    <input type='hidden' name='og_address_2' id='og_address_2' value="<?php echo $row['address_2'] ?>" class='form-control vw-pro-dtl-tnt long-fld streetinput'>
                                </div>
                            </div>
                            <div class="col-md-2 columns">
                                <div class="form-group">
                                    <label class="form-label">Suburb</label>
                                    <input type='text'  name='address_3' id='address_3' value="<?php echo $row['address_3'] ?>" class='form-control vw-pro-dtl-tnt big-fld'>
                                    <input type='hidden'  name='og_address_3' id='og_address_3' value="<?php echo $row['address_3'] ?>" class='form-control vw-pro-dtl-tnt big-fld'>
                                    <input type="hidden" id="locality" />
                                    <input type="hidden" id="sublocality_level_1" />
                                </div>
                            </div>
                            <div class="col-md-2 columns">
                                <div class="form-group">
                                    <?php if($this->config->item('country') == 1){ ?>
                                        <label class="form-label">State</label>
                                        <select class="form-control" id="state" name="state">
                                            <option value="">----</option>
                                            <?php
                                            foreach($getCountryState->result_array() as $state){ ?>
                                                <option value='<?php echo $state['state']; ?>' <?php echo ($state['state']==$row['state'])?'selected="selected"':''; ?>><?php echo $state['state']; ?></option>
                                            <?php	  
                                            }
                                            ?>
                                        </select>
                                    <?php }else{?>
                                        <label class="form-label">Region</label>
                                        <input class="form-control" type="text" name="state" id="state" value="<?php echo $row['state']; ?>">
                                    <?php } ?>
                                    <input class="form-control" type="hidden" name="og_state" id="og_state" value="<?php echo $row['state']; ?>">
                                </div>
                            </div>
                            <div class="col-md-2 columns">
                                <div class="form-group">
                                    <label class="form-label">Postcode</label>
                                    <input class="form-control" name='postcode' id='postcode' type="text" value="<?php echo $row['postcode']; ?>">
                                    <input class="form-control" name='og_postcode' id='og_postcode' type="hidden" value="<?php echo $row['postcode']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Latitutde</label>
                                    <input type='text' name='p_lat' id='p_lat' value="<?php echo $row['lat']; ?>" class='form-control' />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Longitude</label>
                                    <input type='text' name='p_lng' id='p_lng' value="<?php echo $row['lng']; ?>" class='form-control' />
                                </div>
                            </div>
                        </div>

                </div>
                    </div>                    
                </div>
            </section>

            <div class="text-right">
                <button class="btn btn-primmary" id="btn_update_property_address">Update</button>
            </div>
        </div>

        <section class="tabs-section">
            <div class="tabs-section-nav tabs-section-nav-icons">
                <div class="tbl">
                    <ul class="nav" role="tablist">
                        <li class="nav-item">
                            <a  class="nav-link <?php echo ($tab==1 || $tab=="")?'active':'not-active' ?>" href="/properties/details/?id=<?php echo $property_id ?>&tab=1">
                                <span class="nav-link-in">
                                    <i class="font-icon font-icon-build"></i>
                                    Property Details
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($tab==2)?'active':'not-active' ?>" href="/properties/details/?id=<?php echo $property_id ?>&tab=2">
                                <span class="nav-link-in">
                                    <i class="font-icon font-icon-cogwheel"></i>
                                    Services/Jobs
                                </span>
                            </a>
                        </li>	
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($tab==3)?'active':'not-active' ?>" href="/properties/details/?id=<?php echo $property_id ?>&tab=3">
                                <span class="nav-link-in">
                                    <i class="font-icon font-icon-users"></i>
                                    Tenants/Landlord
                                </span>
                            </a>
                        </li>	
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($tab==5)?'active':'not-active' ?>" href="/properties/details/?id=<?php echo $property_id ?>&tab=5">
                                <span class="nav-link-in">
                                    <i class="font-icon font-icon-list-square"></i>
                                    Logs
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($tab==4)?'active':'not-active' ?>" href="/properties/details/?id=<?php echo $property_id ?>&tab=4">
                                <span class="nav-link-in">
                                    <i class="font-icon font-icon-page"></i>
                                    Files
                                </span>
                            </a>
                        </li>	
                    </ul>
                </div>
            </div>

            <div class="tab-content">
                <?php 
                    ## Tab content/page switching > load relevant tab
                    ?>

                    
                    
                    <?php
                    if(!$tab || $tab==1){
                        $this->load->view('/properties/tab/vpd_details.php');
                    }elseif($tab==2){
                        $this->load->view('/properties/tab/vpd_services.php');
                    }
                    elseif($tab==3){
                        $this->load->view('/properties/tab/vpd_tenants_lanlord.php');
                    }
                    elseif($tab==5){
                        $this->load->view('/properties/tab/vpd_logs_new.php');
                    }elseif($tab==4){
                        $this->load->view('/properties/tab/vpd_files.php');
                    }
                ?>
            </div>
        </section>
    </div>
</div>



<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >

	<h4><?php echo $title; ?></h4>
	<p>
    <pre>
        <code>
            <?=$last_query;?>
        </code>
    </pre>
	</p>

</div>

<div id="fancybox_id_in_used" style="display:none; padding: 30px">
        <section class="card card-blue-fill">
            <header class="card-header">
                <div class="row">
                    <div class="col-md-9" > <span >This ID already in use:</span> </div>
                </div> 
        </header>
            <div class="card-block">
                
                <div id="ajax_address_div">
                    <div class="default_address">
                        <div class="row">
                            <div class="col-md-12 columns">
                                <div class="form-group">
                                    <a href="/properties/details/?id=<?php echo $connected_prop_row['property_id'] ?>"><?php echo $connected_prop_full_add; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </section>
    </div>
<!-- Fancybox END -->

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $this->config->item('gmap_api_key'); ?>&signed_in=true&libraries=places&callback=initialize" async defer></script>
<script type="text/javascript">
    function fetch_date(){

        $('#load-screen').show();
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $property_id; ?>,
                property_update: 'fetch_date'
            }
        }).done(function( response ) {
            $('#load-screen').hide();
            if( Object.keys(response).length ){
                // Set the subscription date to the response date which is the last visit of a completed ym job
                jQuery("#subscription_date").val(response.date);

                // Update the source as well based on the tech who performed the last job
                if( response.assigned_tech == 1 || response.assigned_tech == 2 ){ // if Other Supplier(1) OR Upfront Bill(2)
                    jQuery("#subscription_source").css('border','1px solid red');
                } else {
                    jQuery("#subscription_source").val(8); // select "SATS"
                }

            } else {
                alert("No data found. Please enter subscription start date manually");
            }
        });
    }

    // google map autocomplete
    var placeSearch, autocomplete;

    // google address prefill
    var componentForm2 = {
    route: {
        'type': 'long_name',
        'field': 'address_2'
    },
    locality: {
        'type': 'long_name',
        'field': 'locality'
    },
    sublocality_level_1: {
        'type': 'long_name',
        'field': 'sublocality_level_1'
    },
    administrative_area_level_1: {
        'type': 'short_name',
        'field': 'state'
    },
    postal_code: {
        'type': 'short_name',
        'field': 'postcode'
    }
    };

    function initAutocomplete() {

        // Create the autocomplete object, restricting the search to geographical
        // location types.
            var options = {
                types: ['geocode'],
                componentRestrictions: {
                    country: '<?php echo ($this->config->item('country') == 1) ? 'au': 'nz'; ?>'
                }
            };

        autocomplete = new google.maps.places.Autocomplete(
            (document.getElementById('fullAdd')),
            options
            );

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);

    }

    // [START region_fillform]
    function fillInAddress() {

        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();


        // test
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm2[addressType]) {

                var val = place.address_components[i][componentForm2[addressType].type];
                document.getElementById(componentForm2[addressType].field).value = val;

            }

        }
        // street name
        var ac = jQuery("#fullAdd").val();
        var ac2 = ac.split(" ");
        var street_number = ac2[0];
        jQuery("#address_1").val(street_number);

        // get suburb from locality or sublocality
        var sublocality_level_1 = jQuery("#sublocality_level_1").val();
        var locality = jQuery("#locality").val();

        var suburb = ( sublocality_level_1 != '' )?sublocality_level_1:locality;
        jQuery("#address_3").val(suburb);

        // get suburb from google object 'vicinity'
        if( jQuery("#address_3").val() == '' ){
            jQuery("#address_3").val(place.vicinity);
        }

        // get coordinates
        jQuery("#p_lat").val(place.geometry.location.lat());
        jQuery("#p_lng").val(place.geometry.location.lng());

        console.log(place);

    }

    var geocoder;
    var map;
    var address = "<?php echo $prop_full_add; ?>";

    function initMap() {

            geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(-25.363, 131.044);
            var myOptions = {
            zoom: 15,
            center: latlng,
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
            if (geocoder) {
            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                    map.setCenter(results[0].geometry.location);

                    var infowindow = new google.maps.InfoWindow({
                    content: '<b>' + address + '</b>',
                    size: new google.maps.Size(150, 50)
                    });

                    var marker = new google.maps.Marker({
                    position: results[0].geometry.location,
                    map: map,
                    title: address
                    });
                    google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map, marker);
                    });

                } else {
                    console.log("No results found");
                }
                } else {
                console.log("Geocode was not successful for the following reason: " + status);
                }
            });
            }

    }

    function initialize() {
        <?php if(isset($_GET['map'])){ ?>
            initMap();
        <?php } ?>
        initAutocomplete();
    }

    // this somehow causes error on js, and some functions will not work. commenting it doesn't break the google map and autocomplete API
    //google.maps.event.addDomListener(window, 'load', initMap); 

    jQuery(document).ready(function(){

         //success/error message sweel alert pop  start
        <?php 
        if( $this->session->flashdata('update_agency_success') &&  $this->session->flashdata('update_agency_success') == 1 ){ 
    
        ?>
        var msg = "<?php echo $this->session->flashdata('update_agency_success_msg') ?>";
            swal({
                html: true,
                title: "Success!",
                text: msg,
                type: "success",
                confirmButtonClass: "btn-success"
            });
        <?php 
        }
        ?>

        <?php 
            if( $this->session->flashdata('update_not_free_error') &&  $this->session->flashdata('update_not_free_error') == 1 ){ 
        
            ?>
            var msg = "<?php echo $this->session->flashdata('update_not_free_msg') ?>";
                swal({
                    html: true,
                    title: "Error!",
                    text: msg,
                    type: "error",
                    confirmButtonClass: "btn-error"
                });
            <?php 
            }
        ?>

        // field edited script, to know what field is edited to be included in the logs, WIP try and get all fields
        /*jQuery(".form-control").change(function(){
            var fields_edited = jQuery("#fields_edited").val();
            var field = jQuery(this).attr("title");
            if(fields_edited.search(field)==-1){
                console.log('already exist');
                var comb = fields_edited+","+field;
                jQuery("#fields_edited").val(comb);
            }
        });*/

        //update property address
        $('#btn_update_property_address').on('click',function(){            

            var address_1 = $('#address_1').val();
            var address_2 = $('#address_2').val();
            var address_3 = $('#address_3').val();
            var state = $('#state').val();
            var postcode = $('#postcode').val();

            var og_address_1 = $('#og_address_1').val();
            var og_address_2 = $('#og_address_2').val();
            var og_address_3 = $('#og_address_3').val();
            var og_state = $('#og_state').val();
            var og_postcode = $('#og_postcode').val();

            // coordinates
            var p_lat = $('#p_lat').val();
            var p_lng = $('#p_lng').val();

            var err = "";

            if(address_1 == "" || address_2 == "" || address_3 == "" || state == "" || postcode == ""){
                err+="Complete address is required";
            }

            if(err!=""){
                swal('',err,'error');
                return false;
            }

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/properties/ajax_update_property",
                dataType: 'json',
                data: {
                    property_id: <?php echo $property_id; ?>,
                    address_1: address_1,
                    address_2: address_2,
                    address_3: address_3,
                    state: state,
                    postcode: postcode,
                    og_address_1:og_address_1,
                    og_address_2:og_address_2,
                    og_address_3:og_address_3,
                    og_state:og_state,
                    og_postcode:og_postcode,
                    p_lat: p_lat,
                    p_lng: p_lng,
                    property_update: 'update_address'
                    
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
                    var full_url = "/properties/details/?id=<?php echo $property_id; ?>&tab=1";
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            }); 

        });

        //ajax request for api property archived check
        $('#ajax_check_api_property_status').click(function(){
            
            var el = $(this);
            var prop_id = <?php echo $property_id ?? 0 ?>;

            $('#load-screen').show();
            jQuery.ajax({
                type: "POST",
                url: "/jobs/apiPropertyIsArchived",
                dataType: 'json',
                data: {
                    prop_id: prop_id
                }

            }).done(function(response) {
                //hide loader
                $('#load-screen').hide();
                
                if(!jQuery.isEmptyObject(response)){
                    $('#api_prop_status_response_box').html(response.message);

                    //changed warning box from orange to green when property is active
                    if(response.isActive == true){
                        el.parents('.api_connection_warning_box').removeClass('alert-warning').addClass('alert-success');
                    }
                }

            });

        })


    })
</script>