
<style>
.total_box{
    margin-top: 18px;
}
.a_link.asc{
    top:3px;
}
.a_link.desc{
    top:-3px;
}
.fa-sort-up:before, .fa-sort-asc:before {
    content: "\f0de";
}
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

.css-checkbox div.checkbox{
    margin-left: 10px;
}

#datatable {width: 100% !important;}
#datatable_wrapper{overflow-x: hidden;}
#datatable td {padding: 0.5em 0.5em;}
.is_eo_bg_color{
    background-color: #e77e7e73;
}
</style>

<div class="box-typical box-typical-padding">

<?php
    /**
     * Breadcrumbs template
     */
    $this->load->view('templates/breadcrumbs', $bc_items);
?>

<section>
    <div class="body-typical-body">
        <div class="table-responsive">
            <table class="table table-hover main-table" id="datatable">
                <thead>
                    <tr>
                        <th style="width:120px;">End Date</th>
                        <th>Property Address</th>
                        <th>Agency</th>
                        <th>Region</th>
                        <th>Job Type</th>
                        <th>Preferred Time</th>
                        <th>Allow EN</th>
                        <th>Keys</th>
                        <th>EO</th>
                        <th class="check_all_td">
                            <div class="checkbox" style="margin:0;">
                                <input name="chk_all" type="checkbox" id="check-all">
                                <label for="check-all">&nbsp;</label>
                            </div>
                        </th>
                        <th>Property ID</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (isset($lists)) {
                    foreach (array_keys($lists['data']) as $index => $data): ?>
                        <tr class="body_tr jalign_left tbl_list_tr" style="background:<?= $lists['is_eo_bg_color'][$index]; ?>">
                            <td style="color:<?php echo $lists['end_date_color'][$index]; ?>" class="date"><?= $lists['end_date'][$index]; ?></td>
                            <td>
                                <span class="txt_lbl">
                                    <?= $lists['address'][$index]; ?>
                                </span>
                            </td>
                            <td><?= $lists['agency_name'][$index]; ?></td>
                            <td><?= $lists['regions'][$index]; ?></td>
                            <td td-data="<?=$lists["job_id"][$index]?>">
                                <?= $lists['job_type'][$index]; ?>
                            </td>
                            <td><?= $lists['preferred_time'][$index]; ?></td>
                            <td style="color:<?= $lists['bg_red'][$index] ?>"><?= $lists['allow_en'][$index]; ?></td>
                            <td style="color:<?= $lists['bg_red'][$index] ?>"><?= $lists['keys'][$index]; ?></td>
                            <td style="color:<?= $lists['bg_red'][$index] ?>"><?= $lists['is_eo'][$index]; ?></td>
                            <td>
                                <div class="checkbox">
                                    <input class="chk_job" name="chk_job[]" type="checkbox" id="check-<?= $lists["job_id"][$index] ?>" data-jobid="<?= $lists["job_id"][$index]; ?>" data-propid="<?= $lists['property_id'][$index] ?>" value="<?= $lists['job_id'][$index]; ?>">
                                    <label for="check-<?php echo $lists["job_id"][$index] ?>">&nbsp;</label>
                                </div>
                                <input type="hidden" class="job_type" value="<?= $lists[$index]['job_type']; ?>" />
                                <input type="hidden" class="is_eo" value="<?= $lists['is_eo'][$index]; ?>" />
                            </td>
                            <td style="color:<?= $lists['bg_red'][$index] ?>"><?= $lists['property_id'][$index]; ?></td>
                        </tr>
                    <?php endforeach;
                } ?>
                </tbody>
            </table>
            <div id="mbm_box" class="text-right">                                

                <div class="gbox_main" style="margin-right:50px;">
                    <div class="gbox">
                    <select id="maps_tech" class="form-control">
                        <option value="">Please select Tech</option>
                        <?php if (isset($lists)): ?>
                            <?php foreach($tech as $key => $row): ?>
                                <?php ?>
                                <option value="<?php echo $row['StaffID'] ?>" data-isElectrician="<?php echo $row['is_electrician']; ?>">
                                    <?php echo $tech['fullname'][$key]; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    </div>
                    <div class="gbox">
                        <input name="assign_date" class="flatpickr form-control flatpickr-input" data-allow-input="true" id="assign_date" type="text" placeholder="Date" >
                    </div>
                    <div class="gbox">
                        <button id="assign_btn" type="button" class="btn">Assign</button>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

</div>

<!-- Fancybox Start -->
<a href="javascript:" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>
<div id="about_page_fb" class="fancybox" style="display:none;" >

<div class="container">
    <h4 class="text-align">This page lists all QLD jobs that are past their due date.</h4>
    <div class="row">
        <div class="col-sm-12">
            <p>- Any record that past its end date, colour the <strong>End Date</strong> column RED</p>
            <p class="is_eo_bg_color">- Any record in the <strong>Electrician Only</strong> table row will be highlighted in RED.</p>
            <p>- Job Type data will be redirected to Job details</p>
        </div>
    </div>
</div>

</div>
<!-- Fancybox END -->

<script type="text/javascript">

jQuery(document).ready(function()
{

    //Use show() function to force open filters on page however by default we hide them --->>> custom-datatables-searchpanes.js
    jQuery(".dtsp-panesContainer").show();

    $('#check-all').on('change',function(){
        var obj = $(this);
        var isChecked = obj.is(':checked');
        var divbutton = $('#mbm_box');
        if(isChecked){
            divbutton.show();
            $('.chk_job').prop('checked',true);
            $("tr.tbl_list_tr").addClass("yello_mark");
        }else{
            divbutton.hide();
            $('.chk_job').prop('checked',false);
            $("tr.tbl_list_tr").removeClass("yello_mark");
        }
    });

    $('.chk_job').on('change',function(){
        var obj = $(this);
        var isLength = $('.chk_job:checked').length;
        var divbutton = $('#mbm_box');
        if(obj.is(':checked')){
            divbutton.show();
            obj.parents('.tbl_list_tr').addClass('yello_mark');
        }else{
            obj.parents('.tbl_list_tr').removeClass('yello_mark');
            if(isLength<=0){
                divbutton.hide();
            }
        }
    })

    jQuery("#assign_btn").on('click',function()
    {
        var job_id = new Array();
        var tech_id = jQuery("#maps_tech").val();
        var is_tech_electrician = jQuery("#maps_tech option:selected").attr("data-isElectrician");
        var date = jQuery("#assign_date").val();
        var checkLength = $('.chk_job:checked').length;
        var for_elec_only = false;

        var error = "";

        //push job_id array
        jQuery(".chk_job:checked").each(function(){

            var job_chk_dom = jQuery(this);
            var parents_tr = job_chk_dom.parents("tr:first");
            var job_type = parents_tr.find(".job_type").val();
            var is_eo = parents_tr.find(".is_eo").val();

            // 240v Rebook Jobs or Electrician Only(EO)
            if( job_type == '240v Rebook' || is_eo == 1 ){
                for_elec_only = true;
            }

            job_id.push(jQuery(this).val());

        });

        //validations
        if(checkLength == 0){
            error += "Please select/tick Job\n";
        }
        if(tech_id==""){
            error += "Tech must not be empty\n";
        }
        if(date==""){
            error += "Date must not be empty\n";
        }

        // 240v Rebook or Electrician Only(EO) check
        if( tech_id > 0 && is_tech_electrician != 1 && for_elec_only == true ){
            error += "Cannot assign 240v Rebook or Electrician Only(EO) job to non Electrician\n";
        }

        if( error != "" ){

            swal('',error,'error');
            return false;

        }else{

            if( job_id.length > 0 ){

                $('#load-screen').show(); //show loader
                jQuery.ajax({
                    type: "POST",
                    url: "/jobs/ajax_move_to_maps",
                    data: {
                        job_id: job_id,
                        tech_id: tech_id,
                        date: date,
                        page_type: "overdue_qld_jobs"
                    }
                }).done(function( ret ){
                    $('#load-screen').hide(); //hide loader
                    swal({
                        title:"Success!",
                        text: "Assigned success",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: false,
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>

                    });
                    setTimeout(function(){ window.location='/daily/overdue_qld_jobs'; }, <?php echo $this->config->item('timer') ?>);

                });

            }

        }

	});
})

</script>