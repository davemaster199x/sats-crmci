<style>
    .jalign_left{
        text-align:left;
    }
    .txt_hid, .btn_update{
        display:none;
    }
</style>

<div class="box-typical box-typical-padding">

    <?php
// breadcrumbs template
    $bc_items = array(
        array(
            'title' => 'Daily',
            'link' => "/daily/"
        ),
        array(
            'title' => $title,
            'status' => 'active',
            'link' => "/daily/view_no_active_job_properties"
        )
    );
    $bc_data['bc_items'] = $bc_items;
    $this->load->view('templates/breadcrumbs', $bc_data);
    ?>

    <section>
        <div class="box-typical box-typical-padding">
            <div class="col-md-12">
                <a href="/daily/view_no_active_job_properties<?php echo ($this->input->get('show_all') == "1") ? "" : "?show_all=1" ?>" >
                    <button class="btn btn-inline" type="button" >
                        <?php echo ($this->input->get('show_all') == 1) ? 'Hide Acknowledged' : 'Show All' ?>
                    </button>
                </a>
            </div>
        </div>
    </section>


    <section>
        <div class="body-typical-body">
            <div class="table-responsive">
                <table class="table table-hover main-table">
                    <thead>
                        <tr>
                            <th width="100"><b>Property ID</b></th>
                            <th><b>Address</b></th>
                            <th width="70"><b>Service</b></th>
                            <th><b>Agency</b></th>
                            <th width="100"><b>Created</b></th>
                            <th class="check_all_td">
								<div class="checkbox" style="margin:0;">
									<input name="chk_all" type="checkbox" id="check-all" <?= $this->input->get('show_all') == "1" ? 'disabled' : '' ?>>
									<label for="check-all">&nbsp;<b>Select All</b></label>                                    
								</div>
							</th>
                        </tr>
                    </thead>

                    <tbody>                


                        <?Php
                        foreach ($properties as $row) {
                            ?>
                            <tr class="body_tr jalign_left" 
                                data-property-id="<?php echo $row['property_id']; ?>" 
                                data-agency-id="<?php echo $row['agency_id']; ?>" 
                            >
                                <td>
                                    <span><?Php echo $this->gherxlib->crmLink('vpd', $row['property_id'], $row['property_id']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        <?Php echo $this->gherxlib->crmLink('vpd', $row['property_id'], "{$row['address_1']} {$row['address_2']}, {$row['address_3']} {$row['state']}"); ?>
                                    </span>
                                </td>
                                <td>							
								    <?php
                                    // display icons
                                    $job_icons_params = array(
                                        'service_type' => $row['j_service'],
                                        'job_type' => $row['j_type'],
                                        'sevice_type_name' => $row['ajt_type']
                                    );
                                    echo $this->system_model->display_job_icons($job_icons_params);
                                    ?>
                                </td>
                                <td>
                                    <span class="txt_lbl">
                                        <?Php echo $this->gherxlib->crmLink('vad', $row['agency_id'], "{$row['agency_name']}",'',$row['priority']); ?>
                                    </span>

                                </td>
                                <td> <?Php echo (($row['created'] != "") ? date('d/m/Y', strtotime($row['created'])) : ''); ?></td>

                                <td>
                                    <input type="checkbox" 
                                        class="is_acknowledge" 
                                        name="selected_records[]" 
                                        value="<?php echo $row['hidden']; ?>" 
                                        <?php echo ($row['hidden'] == 1) ? "checked"  :  ""; ?> 
                                    />
                                </td>
                            </tr>

                            <?php
                            $i++;
                        }
                        ?>
                    </tbody>

                </table>
                <button id="snooze-button" class="btn btn-inline float-right m-0" type="button" style="display: none;margin-left:10px!important;">Snooze for 30 days</button>
                <button id="save-button" class="btn btn-inline float-right m-0" type="button" style="display: none;">Hide</button>
            </div>
            <nav id="pagi_links" aria-label="Page navigation example" style="text-align:center"><?php echo $pagination; ?></nav>
            <div id="pagi_count" class="pagi_count text-center"><?php echo $pagi_count; ?></div>
        </div>
    </section>

</div>

<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >

<h4><?php echo $title; ?></h4>
<p>This page catches properties without an active job, or without a recently completed YM.</p>
<p>User will be able to click the tick box in Action column to hide job properties.</p>
<p>User will be able to filter `Show All` and `Hide Acknowledged`.</p>
<pre><code><?php echo $sql_query; ?></code></pre>

</div>
<!-- Fancybox END -->


<script type="text/javascript">

    $(document).ready(function (){

        const urlParams = new URLSearchParams(window.location.search);
        const showParam = urlParams.get('show_all');

        if (showParam === null){
            // Add a click event handler to the "Select All" checkbox
            $('#check-all').click(function () {
                // Check or uncheck all checkboxes based on the "Select All" checkbox state
                $('.is_acknowledge').prop('checked', this.checked);
                toggleSaveButtonVisibility();
            });

            // Add a click event handler to the individual checkboxes
            $('.is_acknowledge').click(function () {
                toggleSaveButtonVisibility();
            });

            
        } else{
          
            $(".is_acknowledge").on('click', function() {
         
                var property_id = $(this).closest('tr').data('property-id');

                var acknowledge_val = ( jQuery(this).prop("checked" ) == true ) ? 1 : 0;
                jQuery(this).val(acknowledge_val);

                let url = '<?php echo site_url(); ?>ajax/daily_ajax/ajax_is_acknowledge_update';
                let data = {
                    property_id,
                    acknowledge: acknowledge_val
                };

                jQuery('#load-screen').show();

                ajax(url, data).done(function(results){
                    if (results.success){
                        swal({
                            title: "Success!",
                            text: results.message,
                            type: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }); 
                
                        setInterval(() => {
                            $('#load-screen').hide();
                        }, 1000);    
                    
                    }else{
                        console.log(results.message);
                    }
                    location.reload();
                })
            
            });
        }

        // Function to toggle the visibility of the "Save" button
        function toggleSaveButtonVisibility() {
            var anyCheckboxChecked = $('.is_acknowledge:checked').length > 0;
            $('#save-button').toggle(anyCheckboxChecked);
            $('#snooze-button').toggle(anyCheckboxChecked);
        }

        // Add a click event handler to the "Save" button
         $('#save-button').click(function () {
             
             // Get the selected checkboxes and send data to the server
             var selectedRecords = $('.is_acknowledge:checked').map(function () {
                 return 1;
                }).get();
                
            // Get the corresponding data-id and data-agency values
            var propertyIds = $('.is_acknowledge:checked').map(function () {
                return $(this).closest('tr').data('property-id');
            }).get();
            
            let url = '<?php echo site_url(); ?>ajax/daily_ajax/ajax_is_acknowledge_multiple_update';
            let data = {
                selectedRecords,
                property_id : propertyIds,
            };
            
            jQuery('#load-screen').show();
            ajax(url, data).done(function(results){
                if (results.success){
                    swal({
                        title: "Success!",
                        text: results.message,
                        type: "success",
                        showConfirmButton: false,
                        timer: 1500
                    }); 
               
                    setInterval(() => {
                        $('#load-screen').hide();
                    }, 1000);    
                 
                }else{
                    console.log(results.message);
                }
                 location.reload();
            })
        });

        //Add event handler for Snooze button
        $('#snooze-button').click(function () {
            //Selected/Ticked Property
            var selectedProp = $('.is_acknowledge:checked').map(function(){
                return $(this).closest('tr').data('property-id');
            }).get();

            //Snooze confirmation
            swal({
                title: "Warning!",
                text: "Snooze for 30 days?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {
                $('#load-screen').show();
                //Ajax request
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo site_url() ?>/ajax/daily_ajax/ajaxSnoozeProperty",
                    dataType: 'json',
                    data: {
                        prop_id: selectedProp
                    }
                }).done(function(response) {
                    $('#load-screen').hide();
                    if(response.status){
                        swal({
                            title:"Success!",
                            text: "Properties successfully snooze for 30 days",
                            type: "success",
                            showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                            timer: <?php echo $this->config->item('timer') ?>
                        });

                        var full_url = window.location.href;
                        setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                    }else{
                      
                    }
                })
            })
        })

    })

</script>
