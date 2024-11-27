<style>
.service_type_icon_tab_link{
    cursor: pointer;
}
.unable_to_complete_ts_tab{
    display: none;
}
</style>

 <!-- service type TABS -->
 <section class="tabs-section">
		
    <div class="tabs-section-nav tabs-section-nav-icons">
        <div id="service_type_tab" class="tbl <?php echo ( count($service_types_arr) == 1 )?'d-none':null; ?>">
            <ul class="nav j_remember_tab2" role="tablist">
                <?php if( Alarm_job_type_model::show_smoke_alarms($ajt_bundle_ids) ): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#tab_sa" role="tab" data-toggle="tab" data-tab_service_type_id="<?php echo $service_type; ?>">
                        <span class="nav-link-in">
                            <?=Alarm_job_type_model::icons($service_type);?>
                            Smoke Alarms
                        </span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if( Alarm_job_type_model::show_safety_switches($ajt_bundle_ids) ): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#tab_ss" role="tab" data-toggle="tab" data-tab_service_type_id="<?php echo $service_type; ?>">
                        <span class="nav-link-in">
                            <?=Alarm_job_type_model::icons($service_type);?>
                            Safety Switch
                        </span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if( Alarm_job_type_model::show_safety_switches_view_only($ajt_bundle_ids) ): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#tab_ss_vo" role="tab" data-toggle="tab" data-tab_service_type_id="<?php echo $service_type; ?>">
                        <span class="nav-link-in">
                            <?=Alarm_job_type_model::icons($service_type);?>
                            Safety Switch (VIEW ONLY)
                        </span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if( Alarm_job_type_model::show_corded_windows($ajt_bundle_ids) ): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#tab_cw" role="tab" data-toggle="tab" data-tab_service_type_id="<?php echo $service_type; ?>">
                        <span class="nav-link-in">
                            <?=Alarm_job_type_model::icons($service_type);?>
                            Corded Window
                        </span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if( Alarm_job_type_model::show_water_efficiency($ajt_bundle_ids) ): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#tab_we" role="tab" data-toggle="tab" data-tab_service_type_id="<?php echo $service_type; ?>">
                        <span class="nav-link-in">
                            <?=Alarm_job_type_model::icons($service_type);?>
                            Water Efficiency
                        </span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    
    <div class="tab-content">
        <?php if( Alarm_job_type_model::show_smoke_alarms($ajt_bundle_ids) ): ?>
        <div role="tabpanel" class="tab-pane fade" id="tab_sa">
            <?php $this->load->view('jobs/tech_sheet_sa'); ?>
        </div>
        <?php endif; ?>

        <?php if( Alarm_job_type_model::show_safety_switches($ajt_bundle_ids) ): ?>
        <div role="tabpanel" class="tab-pane fade" id="tab_ss">
            <?php $this->load->view('jobs/tech_sheet_ss'); ?>
        </div>
        <?php endif; ?>

        <?php if( Alarm_job_type_model::show_safety_switches_view_only($ajt_bundle_ids) ): ?>
        <div role="tabpanel" class="tab-pane fade" id="tab_ss_vo">
            <?php $this->load->view('jobs/tech_sheet_ss_vo'); ?>
        </div>
        <?php endif; ?>

        <?php if( Alarm_job_type_model::show_corded_windows($ajt_bundle_ids) ): ?>
        <div role="tabpanel" class="tab-pane fade" id="tab_cw">
            <?php $this->load->view('jobs/tech_sheet_cw'); ?>
        </div>
        <?php endif; ?>

        <?php if( Alarm_job_type_model::show_water_efficiency($ajt_bundle_ids) ): ?>
        <div role="tabpanel" class="tab-pane fade" id="tab_we">
            <?php $this->load->view('jobs/tech_sheet_we'); ?>
        </div>
	    <?php endif; ?>
    </div>
</section>





<div class="row">

    <div class="col-md-4 text-left">
        <button type="button" class="btn btn-success techsheet_tab_prev">Previous</button>	 
        <button type="button" id="unable_to_complete_btn" class="btn btn-danger unable_to_complete_btn unable_to_complete_ts_tab">Unable to complete Job</button>                   	
    </div>

    <div class="col-md-4 text-center pt-2">

        <div class="col-md">
        <?php
        foreach( $service_types_arr as $service_type ){ ?>

            <?=Alarm_job_type_model::icons($service_type);?>	  

        <?php    
        }
        ?>
        </div>

    </div>

    <div class="col-md-4 text-right">
        <button type="button" class="btn techsheet_tab_next">Next</button>                  	
    </div>

</div> 

<script>
jQuery(document).ready(function(){

    // next tab
    jQuery('.service_type_icon_tab_link').click(function(){
       
        var node = jQuery(this);

        var service_type_id = node.attr("data-tab_service_type_id");
        //console.log('service_type_id: '+service_type_id);

        if( service_type_id > 0 ){

            var link_node =  jQuery("#service_type_tab .nav-link[data-tab_service_type_id='"+service_type_id+"']");        
            remember_service_tab(link_node);
            link_node.tab('show');

        }        

    });

})
</script>