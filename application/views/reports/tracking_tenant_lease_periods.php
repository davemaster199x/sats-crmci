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

$export_links_params_arr = array(
    'date_from_filter' => $this->input->get_post('date_from_filter'),
    'date_to_filter' => $this->input->get_post('date_to_filter'),
    'tech_filter' =>  $this->input->get_post('tech_filter'),
    'reason_filter' =>  $this->input->get_post('reason_filter'),
    'job_type_filter' =>  $this->input->get_post('job_type_filter'),
    'date_filter' =>  $this->input->get_post('date')
);
$export_link_params = "/jobs/missed_jobs/?export=1&".http_build_query($export_links_params_arr);
?>

	<header class="box-typical-header">

        <div class="box-typical box-typical-padding">
            <?php
        $form_attr = array(
            'id' => 'jform'
        );
        echo form_open($uri,$form_attr);
        ?>
            <div class="for-groupss row">

            
                <div class="col-lg-10 col-md-12 columns">
           
                    <div class="row">

                        <div class="col-md-3">
							<label>Agency</label>
							<select id="agency_filter" name="agency_filter" class="form-control">
								<option value="">---</option>
                                <?php                                                           
                                foreach( $agency_filter_sql->result() as $agency_row ){                                   
                                ?>
                                    <option 
                                        value="<?php echo $agency_row->agency_id; ?>" 
                                        <?php echo (  $agency_row->agency_id == $this->input->get_post('agency_filter') )?'selected':null; ?>
                                    >
                                        <?php echo $agency_row->agency_name; ?>
                                    </option>
                                <?php
                                }                                
                                ?>
							</select>							
						</div>

                        <div class="col-md-3">
							<label>Search</label>
							<select id="date_search" name="date_search" class="form-control">
								<option value="">---</option>
                                <option value="1" <?php echo ( $this->input->get_post('date_search') == 1 )?'selected':null; ?>>Tenancy Start</option>
                                <option value="2" <?php echo ( $this->input->get_post('date_search') == 2 )?'selected':null; ?>>Tenancy End</option>
                                <option value="3" <?php echo ( $this->input->get_post('date_search') == 3 )?'selected':null; ?>>Agreement Start</option>
                                <option value="4" <?php echo ( $this->input->get_post('date_search') == 4 )?'selected':null; ?>>Agreement End</option>                     
							</select>							
						</div>

                        <div class="col-md-3">
							<label>Month</label>
							<select name="month_filter" id="month_filter" class="form-control">
								<option value="">---</option>     
                                <?php
                                for( $m = 1; $m <= 12; $m++ ) {
                                $month = date('F', mktime(0,0,0,$m, 1, date('Y'))); 
                                ?>
                                    <option value="<?php echo $m; ?>" <?php echo ( $m == $this->input->get_post('date_search') )?'selected':null; ?>><?php echo $month; ?></option>  
                                <?php
                                }
                                ?>                                     
							</select>							
						</div>

                        <div class="col-md-3">
							<label>Year</label>
                            <input type="number" name="year_filter" id="year_filter" class="form-control" value="<?php echo $this->input->get_post('year_filter') ?>" />							
						</div>

                        <div class="col-md-1 columns">
                            <label class="col-sm-12 form-control-label">&nbsp;</label>
                            <input class="btn" type="submit" name="btn_search" value="Search" />
                        </div>
                        
                    </div>
              

                </div>
                              
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
                            <th>
                                Property address
                                <a 
                                    data-toggle="tooltip" 
                                    class="a_link <?php echo $sort ?>" 
                                    href="<?php echo "{$uri}/?sort_header=1&order_by=p.address_2&sort={$toggle_sort}&".http_build_query($header_link_params); ?>"
                                >
                                    <em class="fa fa-sort-<?php echo $sort; ?>"></em>
                                </a>
                            </th>      
                            <th>Agency
                                <a 
                                    data-toggle="tooltip" 
                                    class="a_link <?php echo $sort ?>" 
                                    href="<?php echo "{$uri}/?sort_header=1&order_by=a.agency_name&sort={$toggle_sort}&".http_build_query($header_link_params); ?>"
                                >
                                    <em class="fa fa-sort-<?php echo $sort; ?>"></em>
                                </a>
                            </th>                                                         
                            <th>
                                Tenancy Start
                                <a 
                                    data-toggle="tooltip" 
                                    class="a_link <?php echo $sort ?>" 
                                    href="<?php echo "{$uri}/?sort_header=1&order_by=atd.start&sort={$toggle_sort}&".http_build_query($header_link_params); ?>"
                                >
                                    <em class="fa fa-sort-<?php echo $sort; ?>"></em>
                                </a>
                            </th>
                            <th>
                                Tenancy End
                                <a 
                                    data-toggle="tooltip" 
                                    class="a_link <?php echo $sort ?>" 
                                    href="<?php echo "{$uri}/?sort_header=1&order_by=atd.end&sort={$toggle_sort}&".http_build_query($header_link_params); ?>"
                                >
                                    <em class="fa fa-sort-<?php echo $sort; ?>"></em>
                                </a>
                            </th>
                            <th>
                                Agreement Start
                                <a 
                                    data-toggle="tooltip" 
                                    class="a_link <?php echo $sort ?>" 
                                    href="<?php echo "{$uri}/?sort_header=1&order_by=atd.agreement_start&sort={$toggle_sort}&".http_build_query($header_link_params); ?>"
                                >
                                    <em class="fa fa-sort-<?php echo $sort; ?>"></em>
                                </a>
                            </th>
                            <th>
                                Agreement End
                                <a 
                                    data-toggle="tooltip" 
                                    class="a_link <?php echo $sort ?>" 
                                    href="<?php echo "{$uri}/?sort_header=1&order_by=atd.agreement_end&sort={$toggle_sort}&".http_build_query($header_link_params); ?>"
                                >
                                    <em class="fa fa-sort-<?php echo $sort; ?>"></em>
                                </a>
                            </th>
						</tr>
					</thead>

					<tbody>
                        <?php                                              
                        if( $lists->num_rows() > 0 ){
                            foreach($lists->result() as $row){
                            ?>
                                <tr>    
                                    <td>
                                        <a href="/properties/details/?id=<?php echo $row->property_id; ?>">
                                            <?php echo "{$row->p_address_1} {$row->p_address_2}, {$row->p_address_3}"; ?>
                                        </a>
                                    </td>
                                    </td>
                                    <td>                         
                                        <a href="/agency/view_agency_details/<?php echo $row->agency_id; ?>"><?php echo $row->agency_name; ?></a> 
                                    </td>                                        
                                    <td><?php echo $row->atd_start_dmy; ?></td>
                                    <td><?php echo $row->atd_end_dmy; ?></td>
                                    <td><?php echo $row->atd_agree_start_dmy; ?></td>
                                    <td><?php echo $row->atd_agree_end_dmy; ?></td>                                                             
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