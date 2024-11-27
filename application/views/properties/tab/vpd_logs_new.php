<style>
    .log_list_box table td, .log_list_box table th{
        padding:11px 30px 10px 7px;
    }
    .preferences_list_box .radio{
        margin-bottom: 0px;
    }
    div.checkbox{margin: 0px;}
   
    .ob_check_icon{
        font-size:20px;
    }
    table.table_log_listing_old tbody tr:nth-child(even) {
        background-color: rgb(238, 238, 238);
    }
</style>

<div class="log_list_box">
    <section class="card card-blue-fill">
        <header class="card-header">Logs</header>
        <div class="card-block">
            <div class="row text-left">
                <div class="col-md-1 columns">
                    <label class="form-label" for="eventdate">Date</label>
                    <input style="width: 110px;" type="text" id="created_date" name="created_date" class="flatpickr_event_log flatpickr-input form-control agency_logs_input" value="<?php echo date("d/m/Y"); ?>">
                </div>
                <div class="col-md-2 columns">
                    <label class="form-label">Event</label>
                    <select name="title" id="title" class="form-control">
                        <?php foreach( $log_title_for_contact_type_dropdown->result_array() as $log_title_for_contact_type_dropdown_row ){ ?>
                            <option value="<?=$log_title_for_contact_type_dropdown_row['log_title_id']?>"><?=$log_title_for_contact_type_dropdown_row['title_name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4 columns">
                    <label class="form-label">Details</label>
                    <textarea name="details" id="details" lengthcut="true" class="form-control vpr-adev-txt comments" rows="1"></textarea>
                </div>
                <div class="col-md-1 columns">
                    <label class="form-label">Important</label>
                    <div class="checkbox" style="margin-top: 15px;">
                        <input type="checkbox" name="important1" id="important1" value="1">
                        <label for="important1">&nbsp;</label>
                    </div>
                </div>
                <div class="col-md-3 columns">
                    <div class="vad_cta_box form-group text-left">
                    <button class="btn btn_add_log_event" onclick="add_event_new_logs()">Add Event</button>
                    </div>
                </div>
            </div>
            <!-- New Logs -->
            <div class="log_listing_old text-left">          
                <table class="table table-hover main-table table_log_listing_old table-sm" id="sorttablenew">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Title</th>
                            <th>Who</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(count($property_new_logs)!=0){
                            foreach( $property_new_logs->result() as $p_log){ 
                        ?>
                                <tr style="<?= ($p_log->important == 1) ? 'background-color: #FFCCCB!important;':'';?>">
                                    <td><?php echo date('d/m/Y', strtotime($p_log->created_date)); ?></td>
                                    <td><?php echo date('H:i',strtotime($p_log->created_date)); ?></td>
                                    <td><?php echo $p_log->title_name; ?></td>
                                    <td>
                                        <?php
                                            if( $p_log->auto_process == 1 ){
                                                echo "Auto Processed";
                                            }else{
                                                if ($p_log->StaffID != '') { // sats staff
                                                    echo $p_log->FirstName." ".$p_log->LastName;
                                                } else { // agency portal users
                                                    echo $p_log->fname." ".$p_log->lname;
                                                }
                                            }
                                        ?>
                                    </td>
                                    <!-- <td><?php // echo $p_log->details; ?></td> -->
                                    <td>
                                        <?php
                                        $params = array(
                                            'log_details' => $p_log->details,
                                            'log_id' => $p_log->log_id
                                        );
                                        echo $this->properties_model->parseDynamicLink($params);
                                        ?>
                                    </td>
                                </tr>
                        <?php 
                            }
                        }else{
                            echo "<tr><td colspan='4'>No Data</td></tr>";
                        } 
                        ?>
                    </tbody>
                </table>
                    <nav id="pagi_links" aria-label="Page navigation example" style="text-align:center"><?php echo $pagination; ?></nav>
                    <div id="pagi_count" class="pagi_count text-center"><?php echo $pagi_count; ?></div>
                    <p>&nbsp;</p>
            </div>

            <!-- Old logs -->
            <div class="log_listing_old text-left">                 
                <table class="table table-hover main-table table_log_listing_old table-sm" id="sorttableold">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Staff Member</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(count($property_old_logs)!=0){
                            foreach( $property_old_logs->result() as $p_log){ 
                        ?>
                                <tr>
                                    <td><?php echo date('d/m/Y',strtotime($p_log->log_date)); ?></td>
                                    <td><?php echo date('H:i',strtotime($p_log->log_date)); ?></td>
                                    <td><?php echo $p_log->event_type; ?></td>
                                    <td><?php echo $p_log->FirstName." ".$p_log->LastName; ?></td>
                                    <td><?php echo $p_log->event_details; ?></td>
                                </tr>
                        <?php 
                            }
                        }else{
                            echo "<tr><td colspan='4'>No Data</td></tr>";
                        } 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $('#sorttablenew').DataTable({
        paging: false,    // Disable pagination
        info: false,      // Disable table information display
        searching: false, // Disable searching
        columnDefs: [
            { 
                type: 'datetime-moment', 
                targets: [0], 
                render: function(data, type, full, meta) {
                    if (type === 'sort' || type === 'type') {
                        return moment(data, 'DD/MM/YYYY').format('YYYY-MM-DD');
                    }
                    return data;
                }
            }
        ],
        order: [[0, 'desc']]
    });

    $('#sorttableold').DataTable({
        paging: false,    // Disable pagination
        info: false,      // Disable table information display
        searching: false, // Disable searching
        columnDefs: [
            { type: 'datetime-moment', targets: [0], render: function(data, type, full, meta) {
                if (type === 'sort' || type === 'type') {
                    return moment(data, 'DD/MM/YYYY').format('YYYY-MM-DD');
                }
                return data;
            } }
        ],
        order: [[0, 'desc']]
    });

    jQuery(document).ready(function(){
         //init datepicker
		jQuery('.flatpickr_event_log').flatpickr({
			dateFormat: "d/m/Y",
            maxDate: "today",
			locale: {
				firstDayOfWeek: 1
			}
		});
    });

    function add_event_new_logs(){
        created_date = $('#created_date').val();
        title = $('#title').val();
        details = $('#details').val();
        checkbox = document.getElementById("important1");
        var important = (checkbox && checkbox.checked) ? 1 : 0;
        $('#load-screen').show();
        jQuery.ajax({
            type: "POST",
            url: "/properties/ajax_update_property",
            dataType: 'json',
            data: {
                property_id: <?php echo $this->input->get_post('id'); ?>,
                created_date: created_date,
                title: title,
                details: details,
                important: important,
                property_update: 'add_event_new_logs'
                
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
                var full_url = "/properties/details/?id=<?php echo $this->input->get_post('id'); ?>&tab=5";
                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
            }
        }); 

    }
</script>