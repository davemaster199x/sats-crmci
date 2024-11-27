<style>
    .col-mdd-3{
        max-width:15.5%;
    }
    
</style>

<div class="box-typical box-typical-padding">

	<?php 
// breadcrumbs template
$bc_items = array(
    array(
        'title' => 'Reports',
        'link' => "/reports"
    ),
    array(
        'title' => $title,
        'status' => 'active',
        'link' => $uri
    )
);
$bc_data['bc_items'] = $bc_items;
$this->load->view('templates/breadcrumbs', $bc_data);

?>

	<header class="box-typical-header mb-2">
        <div class="box-typical box-typical-padding">
            <?php
                $form_attr = array(
                    'id' => 'jform'
                );
                echo form_open($uri,$form_attr);
            ?>
            <div class="for-groupss row">
                <div class="col-lg-10 col-md-12 columns">
                    <div class="d-flex flex-wrap">

                        <div class="mr-2 my-2">
                            <label for="search">From</label>
                            <input type="text"  id="start_date" name="start_date" class="form-control flatpickr flatpickr-input" data-allow-input="true" value="<?php echo ( $this->input->get_post('start_date') != "" )?$this->input->get_post('start_date'):null; ?>" />
                            
                        </div>

                        <div class="mr-2  my-2">
                            <label for="search">To</label>
                            <input type="text"  id="end_date" name="end_date" class="form-control flatpickr flatpickr-input" data-allow-input="true" value="<?php echo ( $this->input->get_post('end_date') != "" )?$this->input->get_post('end_date'):null; ?>"  />
                        </div>

                        <div class="mr-2  my-2">
							<label>Agency</label>
							<select id="agency_filter" name="agency_filter" class="form-control">
								<option value="">---</option>
                                <?php                                                           
                                foreach( $distinct_agency->result() as $agency_row ){                                   
                                ?>
                                    <option value="<?php echo $agency_row->agency_id; ?>" <?php echo (  $agency_row->agency_id == $this->input->get_post('agency_filter') )?'selected':null; ?>>
                                        <?php echo $agency_row->agency_name; ?>
                                    </option>
                                <?php
                                }                                
                                ?>
							</select>							
						</div>

                        <div class="d-flex mr-4  my-2">
                            <div class="mr-2">
                                <label class="col-sm-12 form-control-label">&nbsp;</label>
                                <input id="apply_filter" class="btn" type="submit" name="btn_search" value="Search">
                            </div>
                            <div class="mr-2">
                                <label class="col-sm-12 form-control-label">&nbsp;</label>
                                <input id="reset_filter" class="btn btn-default" type="submit" name="btn_search" value="Reset">
                            </div>
                        </div>

                        <!-- compliant total -->
                        <div class="mr-2  my-2">
                            <label class="font-weight-bold">Compliant Total</label>
                            <div class='d-flex flex-row justify-content-start align-items-center h-100 text-lg-start'>
                                <div class="d-flex mr-2">
                                    <i class="fa fa-thumbs-up text-success"></i>
                                    <p class="font-weight-bold">YES <span class="badge badge-pill badge-success"><?= $compliant_total ?></span></p>	
                                </div>
                                <div class="d-flex ml-4">
                                    <i class="fa fa-thumbs-down text-danger mr-2"></i>
                                    <p class="font-weight-bold">NO <span class="badge badge-pill badge-danger"><?= $non_compliant_total ?></span></p>	
                                </div>
                                
                                <div class="ml-4">
                                    <p class="font-weight-bold">Percentage <span class="badge badge-pill badge-primary"><?= $percentage ?>%</span></p>	
                                </div>
                            </div>
													
						</div>
                    </div>
                </div>
                <div class="col-lg-2 columns">
                    <section class="proj-page-section float-right">
                        <div class="proj-page-attach">
                            <i class="fa fa-file-excel-o"></i>
                            <p class="name"><?php echo $title; ?></p>
                            <p>
								<a href="" id="exportReport">
									Export
								</a>
                            </p>
                        </div>
                    </section>
				</div>
            </div>
            <?php form_close(); ?>
        </div>
        
    </header>

	<section>
		<div class="body-typical-body w-100">
			<div class="table-responsive">
                <table id="complianceTable" class="table table-hover main-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>End Date</th>
                            <th>Created Date</th>
							<th>Age</th>
                            <th>Days Missed by</th>
                            <th>Property</th>
                            <th>Agency</th>  
                            <th>Job Type</th>     
                            <th>Compliant</th>       
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row): ?>
                            <?php $full_prop_address = "{$row->address_1} {$row->address_2}, {$row->address_3}" ?>
                            <tr>
                                <td>
                                    <?= $this->system_model->isDateNotEmpty($row->jdate) ? date("d/m/Y",strtotime($row->jdate)) : '' ?>
                                </td>
                                <td>
                                    <?= $this->system_model->isDateNotEmpty($row->due_date) ? date("d/m/Y",strtotime($row->due_date)) : '' ?>
                                </td>
                                <td>
                                    <?= $this->system_model->isDateNotEmpty($row->jcreated) ? date("d/m/Y",strtotime($row->jcreated)) : '' ?>
                                </td>
                                <td>
                                    <?= $this->gherxlib->getAge($row->jcreated, $row->jdate) ?>
                                </td>
                                <td>
                                    <?= $this->gherxlib->getDaysMissedBy($row->jdate,$row->due_date) ?>
                                </td>
                                <td>
                                    <a href="/properties/details/?id=<?= $row->property_id ?>">
                                            <?php echo $full_prop_address; ?>
                                        </a> 
                                </td>
                                <td>
                                    <a href="/agency/view_agency_details/<?= $row->agency_id ?>">
                                            <?= $row->agency_name ?>
                                    </a> 
                                </td>
                                <td>
                                    <a href="<?= $this->config->item('crmci_link') ?>/jobs/details/<?= $row->jid ?>">
                                        <?= $row->job_type ?>
                                    </a>   
                                </td>
                                <td>
                                    <?= $row->jdate <= $row->due_date ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
			</div>
        </div>
    </section>
</div>

<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >
<h4><?php echo $title; ?></h4>
<pre><code><?php echo $sql_query; ?></code></pre>
</div>
<!-- Fancybox END -->

<script type="text/javascript">

jQuery(document).ready(function(){

    var dataTable = $('#complianceTable').DataTable({
        dom: '<"top"lf<"clear">>rt<"bottom"ip<"clear">>',
        pageLength: 100,
        lengthMenu: [ 100, 250, 500 ],
        buttons: [
            'csvHtml5' 
        ],
        columnDefs: [
            { type: 'date-range', targets: [1] },
            { 
                "targets": [4,5,6,7], 
                "orderable": false 
            },
        ],
    });

     // Custom export button click event
     $('#exportReport').on('click', function(e) {
        e.preventDefault();

        dataTable.buttons('.buttons-csv').trigger();
    });



     
    // Reset the filter when the "Reset Filter" button is clicked
    $('#reset_filter').on('click', function() {
        // Reset input fields
        $('#start_date').val('');
        $('#end_date').val('');
        $('#agency_filter').val('');

            // Reload the page to reset filters
            location.reload();
    });

    $("a.inline_fancybox").fancybox({});

});

</script>