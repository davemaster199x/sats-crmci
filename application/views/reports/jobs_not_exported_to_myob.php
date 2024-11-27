<style>
    .col-mdd-3{
        max-width:15.5%;
    }
    .j_is_bold{
        font-weight:bold;
    }
    #year_filter{
        width: 86px;
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

// $export_links_params_arr = array(
//     'date_from_filter' => $this->input->get_post('date_from_filter'),
//     'date_to_filter' => $this->input->get_post('date_to_filter'),
//     'tech_filter' =>  $this->input->get_post('tech_filter'),
//     'reason_filter' =>  $this->input->get_post('reason_filter'),
//     'job_type_filter' =>  $this->input->get_post('job_type_filter'),
//     'date_filter' =>  $this->input->get_post('date')
// );
// $export_link_params = "/jobs/missed_jobs/?export=1&".http_build_query($export_links_params_arr);
?>

    <header class="box-typical-header">

        <div class="box-typical box-typical-padding">
            <?php
                $form_attr = array(
                    'id' => 'jform'
                );
                echo form_open($uri,$form_attr);
            ?>
            <div class="for-groups row">

            
                <div class="col-lg-10 col-md-12 columns">        
                    <div class="row">                        

                        <div class="col-md-2">
                            <label for="date">Date</label>
                            <select class="form-control" name="month_filter">
                                <?php foreach($months as $key => $row): ?>
                                <option value="<?= $key ?>" <?= $this->input->get_post('month_filter') == $key ? 'selected' : null?> ><?= $row ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-2">
							<label>Year</label>
                            <input type="number" name="year_filter" id="year_filter" class="form-control" value="<?php echo $year_filter; ?>" />							
						</div>
                        
                        <div class="col-md-1 columns">
                            <label class="col-sm-12 form-control-label">&nbsp;</label>
                            <input class="btn" type="submit" id="btn_search" name="btn_search" value="Search" />
                        </div>
                    </div>    
                </div>
                            
                <!--
                <div class="col-lg-2 columns">
                    <section class="proj-page-section float-right">
                        <div class="proj-page-attach">
                            <i class="fa fa-file-excel-o"></i>
                            <p class="name"><?php echo $title; ?></p>
                            <p>
                                <a href="<?php echo $export_link ?>">
                                    Export
                                </a>
                            </p>
                        </div>
                    </section>
                </div>  
                -->              
                                    
                </div>
            </form>
        </div>

    </header>


	<section>
		<div class="body-typical-body">
			<div class="table-responsive">
				<table class="table table-hover main-table">
					<thead>
						<tr>
                            <th>Job #</th>
                            <th>Job Service Type</th>
                            <th>Job Type</th>
                            <th>Job Status</th>   
                            <th>Job Date</th> 
                            <th>Job Price</th>                        
                            <th>
                                Property address
                                <!--<a 
                                    data-toggle="tooltip" 
                                    class="a_link <?php echo $sort ?>" 
                                    href="<?php echo "{$uri}/?sort_header=1&order_by=p.address_2&sort={$toggle_sort}&".http_build_query($header_link_params); ?>"
                                >
                                    <em class="fa fa-sort-<?php echo $sort; ?>"></em>
                                </a>-->
                            </th>      
                            <th>Agency
                                <!--<a 
                                    data-toggle="tooltip" 
                                    class="a_link <?php echo $sort ?>" 
                                    href="<?php echo "{$uri}/?sort_header=1&order_by=a.agency_name&sort={$toggle_sort}&".http_build_query($header_link_params); ?>"
                                >
                                    <em class="fa fa-sort-<?php echo $sort; ?>"></em>
                                </a>-->
                            </th>   
                            <!--<th></th>-->                                                                                  
						</tr>
					</thead>

					<tbody>
                        <?php                                              
                        if( $lists->num_rows() > 0 ){
                            foreach($lists->result() as $index => $row){
                            ?>
                                <tr>
                                    <td>
                                        <a target="_blank" href="<?php echo $this->config->item("crmci_link"); ?>/jobs/details/<?php echo $row->jid; ?>">
                                            <?php echo  $row->jid; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php
                                        // service
                                        $job_icons_params = array(
                                            'job_id' => $row->jid,
                                            'job_type' => $row->j_status
                                        );
                                        echo $this->system_model->display_job_icons($job_icons_params);
                                        ?>
                                    </td>
                                    <td><?php echo $row->job_type; ?></td>
                                    <td><?php echo $row->j_status; ?></td>      
                                    <td><?php echo ( $this->system_model->isDateNotEmpty($row->jdate) )?date("d/m/Y",strtotime($row->jdate)):null; ?></td>     
                                    <td><?php echo '$'.number_format($row->job_price,2) ?></td>                             
                                    <td>
                                        <a target="_blank" href="/properties/details/?id=<?php echo $row->property_id; ?>">
                                            <?php echo "{$row->p_address_1} {$row->p_address_2}, {$row->p_address_3}"; ?>
                                        </a>
                                    </td>
                                    <td>                         
                                        <a target="_blank" href="/agency/view_agency_details/<?php echo $row->agency_id; ?>"><?php echo $row->agency_name; ?></a> 
                                    </td>    
                                    <!--<td>
                                        <span class="checkbox">
                                            <input type="checkbox" id="chk_prop_id_<?php echo $index; ?>" class="chk_prop_id" />
                                            <label for="chk_prop_id_<?php echo $index; ?>"></label>
                                        </span>
                                    </td>-->                                                                                                   
                                </tr>
                            <?php
                            }
                        }else{
                            echo "<tr><td colspan='100%'>No Data</td></tr>";
                        }                                               
                        ?>
					</tbody>

				</table>

			</div>

            <nav id="pagi_links" aria-label="Page navigation example" style="text-align:center"><?php echo $pagination; ?></nav>
            <div id="pagi_count" class="pagi_count text-center"><?php echo $pagi_count; ?></div>

    </div>

<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >

<h4><?php echo $title; ?></h4>
<pre><code><?php echo $sql_query; ?></code></pre>
</div>
<!-- Fancybox END -->

<script>

jQuery(document).ready(function()
{
    jQuery("#zero_jobs_included").change(function(){

        var zero_jobs_included_dom = jQuery(this);
        var zero_jobs_included_lbl_dom = jQuery("#zero_jobs_included_lbl");

        if( zero_jobs_included_dom.prop("checked") == true ){
            zero_jobs_included_lbl_dom.text('$0 Jobs Included');
        }else{
            zero_jobs_included_lbl_dom.text('$0 Jobs Excluded');
        }

    });

});
</script>