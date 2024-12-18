
<style>
#btn_recheck_unserviced{
    margin-top:12px;
}
</style>
<div class="box-typical box-typical-padding">

<?php 
// breadcrumbs template
$bc_items = array(
    array(
        'title' => $title,
        'status' => 'active',
        'link' => "/daily/unserviced"
    )
);
$bc_data['bc_items'] = $bc_items;
$this->load->view('templates/breadcrumbs', $bc_data);
?>

<header class="box-typical-header">

<div class="box-typical box-typical-padding">
    <form action="/daily/unserviced" method="post">
        <div class="for-groupss row">
        
            <div class="col-lg-2 col-md-4 columns">
                <div><button class="btn" type="button" id="btn_recheck_unserviced">Recheck Unserviced</button></div>
            </div>
            <div class="col-lg-2 col-md-2 columns">
                <label for="agency_select">Agency</label>
                <select id="agency_filter" name="agency_filter"  class="form-control field_g2">
                    <option value="">ALL</option>	
                </select>	
            </div>
            <div class="col-lg-2 col-md-2 columns">
                <label for="agency_select">Job Type</label>
                <select id="job_type_filter" name="job_type_filter"  class="form-control field_g2">
                    <option value="">All</option>
                </select>	
            </div>
            <div class="col-lg-2 col-md-2 columns">
                <label class="col-sm-12 form-control-label">&nbsp;</label>
                <input type="submit" name="search_submit" value="Search" class="btn">
            </div>
       
        <!-- DL ICONS START -->
        <?php 
        $date = ($this->input->get_post('date')!="")?date('Y-m-d',$this->input->get_post('date')):NULL;
        ?>
        <div class="col-lg-4 col-md-12 columns">
            <section class="proj-page-section float-right">
                <div class="proj-page-attach">
                    <i class="fa fa-file-excel-o"></i>
                    <p class="name"><?php echo $title; ?></p>
                    <p>
                        <a href="/daily/unserviced?export=1" target="blank">
                            Export
                        </a>
                    </p>
                </div>
            </section>
        </div>
        <!-- DL ICONS END -->
        </form>             
        </div>

        <div class="col-md-12">
            <a href="/daily/unserviced<?php echo ($this->input->get('show_all') == "1") ? "" : "?show_all=1" ?>" >
                <button class="btn btn-inline" type="button" >
                    <?php echo ($this->input->get('show_all') == 1) ? 'Hide Acknowledged' : 'Show All' ?>
                </button>
            </a>
        </div>
    </div>

</header>

<section>
    <div class="body-typical-body">
        <div class="table-responsive">
            <table class="table table-hover main-table table-striped" id="sortTable">
                <thead>
                    <tr>
                        <th>Address</th>
                        <th>Agency Name</th>
                        <th>Job Type</th>
                        <th>Job Status</th>
                        <th>Last completed YM</th>
                        <th>Last Completed Job Date</th>
                        <th><b>Hide</b></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        foreach($lists->result_array() as $u){
                    ?>
                            <tr class="body_tr jalign_left" <?php echo $bg_color; ?>>
								<td>
									<span class="txt_lbl">
                                        <?php echo $this->gherxlib->crmLink('vpd',$u['property_id'],"{$u['p_address1']} {$u['p_address2']}, {$u['p_address3']} {$u['p_state']} {$u['p_postcode']}"); ?>
									</span>
                                </td>
                                <td><?php echo $u['agency_name']; ?></td>
                                <td> <?php echo $this->gherxlib->crmlink('vjd', $u['j_id'], $u['j_type']); ?> </td>
                                <td><?php echo $u['j_status'] ?></td> 
								<td >
									<span class="txt_lbl">
										<?php echo ($this->system_model->isDateNotEmpty($this->daily_model->getGetLastJob($u['property_id'])))?date("d/m/Y",strtotime($this->daily_model->getGetLastJob($u['property_id']))):NULL; ?>
                                    </span>
                                </td>
                                <td data-jobid="<?php echo $u['j_id'] ?>">
                                    <?php 
                                        $last_completed_job = $this->daily_model->get_last_job_date($u['property_id']);
                                        echo ( $this->system_model->isDateNotEmpty($last_completed_job) ) ? $this->system_model->formatDate($last_completed_job,'d/m/Y') : NULL;
                                    ?>
                                </td>
                                <td><input type="checkbox" class="is_acknowledge" name="tick_box" data-property-id="<?php echo $u['property_id']; ?>" value="<?php echo $u['hidden']; ?>" <?php echo ($u['hidden'] == 1) ? "checked"  :  ""; ?> /></td>
                        </tr>
                    <?php
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


<style>
.main-table {
    border-left: 1px solid #dee2e6;
    border-right: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 20px;
}

.col-mdd-3 {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 15.2%;
    flex: 0 0 15.2%;
    max-width: 15.2%;

    position: relative;
    width: 100%;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}
</style>

<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >

<h4><?php echo $title; ?></h4>
<p>This page lists all active properties without active service job.
</p>

<pre><code><?php echo $sql_query; ?></code></pre>
<p>&nbsp;
</p>
Process:
<ol style="margin-left:15px;">
    <li>get all properties that will be excluded like properties with booked jobs etc.</li>
    <li> get all properties filter/excluded above prop</li>
    <li>mark unserviced >  is_unserviced = 1</li>
    <li>display on unserviced page</li>
</ol>


</div>
<!-- Fancybox END -->

<script type="text/javascript">

jQuery(document).ready(function(){

    run_ajax_job_filter();
    run_ajax_agency_filter();

    $('#btn_recheck_unserviced').click(function(){
        
        // confirm move user
		swal({
			title: "Warning!",
			text: "Recheck Unserviced?",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Cancel!",
			confirmButtonClass: "btn-success",
			confirmButtonText: "Yes",                       
			closeOnConfirm: true
		},
		function(isConfirm) {
			
			if (isConfirm) { // yes				

				jQuery("#load-screen").show();
				jQuery.ajax({
					type: "POST",
                    dataType: 'json',
					url: "/daily/ajax_recheck_unserviced",
					data: { 
						unserviced: 1
					}
				}).done(function( ret ){
					if(ret.status){
                        $('#load-screen').hide();
                        swal({
                            title: "Success!",
                            text: ret.status_msg,
                            type: "success",
                            confirmButtonClass: "btn-success",
                            showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                            timer: <?php echo $this->config->item('timer') ?>
                        });
                        setTimeout(function(){ window.location='/daily/unserviced'; }, <?php echo $this->config->item('timer') ?>);	
                    }else{
                        $('#load-screen').hide();
                        swal('',ret.status_msg,'error');
                    }
				});
			}
			
		});

    })

    $('#sortTable').DataTable({

    "ordering": true,
    columnDefs: [{
        orderable: false,
        targets: "no-sort"
    }],
    "paging": false,
    "info": false,
    "searching": false


    });

    // job type	
	function run_ajax_job_filter(){

    var json_data = <?php echo $job_type_filter_json; ?>;
    var searched_val = '<?php echo $this->input->get_post('job_type_filter'); ?>';

    jQuery('#job_type_filter').next('.mini_loader').show();
    jQuery.ajax({
        type: "POST",
            url: "/sys/header_filters",
            data: { 
                rf_class: 'jobs',
                header_filter_type: 'job_type',
                json_data: json_data,
                searched_val: searched_val
            }
        }).done(function( ret ){	
            jQuery('#job_type_filter').next('.mini_loader').hide();
            jQuery('#job_type_filter').append(ret);
        });
                
    }

    // agency
    function run_ajax_agency_filter(){

    var json_data = <?php echo $agency_filter_json; ?>;
    var searched_val = '<?php echo $this->input->get_post('agency_filter'); ?>';

    jQuery('#agency_filter').next('.mini_loader').show();
    jQuery.ajax({
        type: "POST",
            url: "/sys/header_filters",
            data: { 
                rf_class: 'property',
                header_filter_type: 'agency',
                json_data: json_data,
                searched_val: searched_val
            }
        }).done(function( ret ){	
            jQuery('#agency_filter').next('.mini_loader').hide();
            $('#agency_filter').append(ret);
        });
                
    }

    jQuery(".is_acknowledge").on('click', function() {
        var property_id = jQuery(this).attr('data-property-id');
        console.log(property_id);
        
        var acknowledge_val = ( jQuery(this).prop("checked" ) == true ) ? 1 : 0;
        jQuery(this).val(acknowledge_val);

        jQuery('#load-screen').show();

        jQuery.ajax({
            type: "POST",
            url: "/daily/unserviced_is_acknowledge",
            dataType: 'json',
            data: {
                property_id:property_id,
                acknowledge: acknowledge_val,
                hidden_from_pages: '1' // 1 means unserviced page in the hidden_pages_from
            }
        }).done(function(response) {                
            if (response) {
                setInterval(() => {
                    $('#load-screen').hide();
                }, 1000);     
            }
            location.reload();
        });
    });

})

</script>