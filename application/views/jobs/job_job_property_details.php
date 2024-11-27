<?php
//Propert details related errors
/*$property_details_tab_err = [];
if (in_array($job_row['ajt_id'], $ic_serv)) {
    $property_details_tab_err[] = "*** ALL ALARMS ARE TO BE INTERCONNECTED ***";
}*/
?>
<div class="text-red" style="font-weight: bold;margin-bottom:10px;text-align:center;"><?php echo implode('<br/>', $property_details_tab_err) ?></div>

<div class="row row-flex">
    <div class="col-md-3">
        <section class="card card-blue-fill" style="min-height:235px;">
            <header class="card-header">Property Details</header>
            <div class="card-block">
                <div class="form-group form-flex">
                    <label class="form-flex-25 txt-bold">No. of Levels</label>
                    <div class="form-flex-2">
                        <a href="#" data-fancybox="" data-src="#prop_det_sec_fb"><?php echo ($job_row['survey_numlevels']!="") ? $job_row['survey_numlevels'] : 'No Data'; ?></a>
                    </div>
                </div>
                <div class="form-group form-flex">
                    <label class="form-flex-25 txt-bold">Ceiling Type</label>
                    <div class="form-flex-2">
                        <a href="#" data-fancybox="" data-src="#prop_det_sec_fb"><?php echo $job_row['survey_ceiling']; ?></a>
                    </div>
                </div>
                <div class="form-group form-flex">
                    <label class="form-flex-25 txt-bold">Ladder Required</label>
                    <div class="form-flex-2">
                        <a href="#" data-fancybox="" data-src="#prop_det_sec_fb"><?php echo ($job_row['survey_ladder']=='4FT') ? '3FT' : $job_row['survey_ladder'] ; ?></a>
                    </div>
                </div>
                <div class="form-group form-flex">
                    <label class="form-flex-25 txt-bold">No. Bedrooms</label>
                    <div class="form-flex-2">
                        <a href="#" data-fancybox="" data-src="#prop_det_sec_fb"><?php echo $job_row['ps_number_of_bedrooms']; ?></a>
                    </div>
                </div>

                <?php
                /**
                 * Show 'Alarms required to meet NEW legislation' only if state = QLD
                 */
                if( $job_row['p_state']=='QLD' ){
                    ?>
                    <div class="form-group form-flex">
                        <label class="form-flex-25 txt-bold">No. Alarms Required <small class="text-red">(QLD ONLY)</small></label>
                        <div class="form-flex-2">
                            <a href="#" data-fancybox="" data-src="#prop_det_sec_fb"><?php echo $job_row['qld_new_leg_alarm_num'] ?></a>
                        </div>
                    </div>
                <?php } ?>

                <?php
                /**
                 * Show this field only if IC
                 */
                if (in_array($job_row['ajt_id'], $ic_serv)) {
                    ?>
                    <div class="form-group form-flex">
                        <label class="form-flex-25 txt-bold">Alarms Must be Interconnected?</label>
                        <div class="form-flex-2">
                            <a href="#" data-fancybox="" data-src="#prop_det_sec_fb"><span class="txt-bold text-red">YES</span></a>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Property Details fancybox/lightbox -->
            <div style="display: none;width:500px;" id="prop_det_sec_fb">
                <section class="card card-blue-fill bold-label-children">
                    <header class="card-header">Property Details</header>
                    <div class="card-block">

                        <div class="form-group form-flex">
                            <label class="form-flex-10">Levels</label>
                            <div class="form-flex-2">
                                <input style="width: 70px;" type="number" name="survey_numlevels" class="form-control" value="<?php echo $job_row['survey_numlevels']; ?>" />
                            </div>
                        </div>

                        <div class="form-group form-flex">
                            <label class="form-flex-10">Ceiling Type</label>
                            <div class="form-flex-2">
                                <div class="left radio" style="margin-right:10px;">
                                    <input id="survey_ceiling1" class="form-control" name="survey_ceiling" type="radio" value="CON" <?php echo ($job_row['survey_ceiling'] == 'CON') ? 'checked="checked"' : ''; ?> />
                                    <label for="survey_ceiling1">CON</label>
                                </div>
                                <div class="left radio">
                                    <input id="survey_ceiling2" class="form-control" name="survey_ceiling" type="radio" value="GYP" <?php echo ($job_row['survey_ceiling'] == 'GYP') ? 'checked="checked"' : ''; ?> />
                                    <label for="survey_ceiling2">GYP</label>
                                </div>
                            </div>
                        </div>

                        <div class="clear"></div>

                        <div class="form-group form-flex">
                            <label class="form-flex-10">Ladder Required</label>
                            <div class="form-flex-2">
                                <div class="left radio" style="margin-right:10px;">
                                    <input id="survey_ladder1" type="radio" name="survey_ladder" <?php echo ($job_row['survey_ladder'] == '4FT') ? 'checked="checked"' : ''; ?> value="4FT" />
                                    <label for="survey_ladder1">3FT</label>
                                </div>
                                <div class="left radio" style="margin-right:10px;">
                                    <input id="survey_ladder2" type="radio" name="survey_ladder" <?php echo ($job_row['survey_ladder'] == '6FT') ? 'checked="checked"' : ''; ?> value="6FT" />
                                    <label for="survey_ladder2">6FT</label>
                                </div>
                                <div class="left radio">
                                    <input id="survey_ladder3" type="radio" name="survey_ladder" <?php echo ($job_row['survey_ladder'] == '8FT') ? 'checked="checked"' : ''; ?> value="8FT" />
                                    <label for="survey_ladder3">8FT</label>
                                </div>
                            </div>
                        </div>

                        <div class="clear"></div>

                        <div class="form-group form-flex">
                            <label class="form-flex-10">Bedrooms</label>
                            <div class="form-flex-2">
                                <input class="form-control" type="number" name="ps_number_of_bedrooms" style="width: 70px;" value="<?php echo ($job_row['ps_number_of_bedrooms'] == 0) ? '' : $job_row['ps_number_of_bedrooms']; ?>" />
                            </div>
                        </div>

                        <?php if( $job_row['p_state']=='QLD' ){ ?>
                            <div class="form-group form-flex">
                                <label class="form-flex-10">No. Alarms Required <small class="text-red">(QLD ONLY)</small></label>
                                <div class="form-flex-2">
                                    <input min="0" class="form-control" type="number" name="qld_new_leg_alarm_num" style="width: 70px;" value="<?php echo $job_row['qld_new_leg_alarm_num']; ?>" />
                                </div>
                            </div>
                        <?php } ?>

                        <div class="clear"></div>

                    </div>
                </section>
                <?php if( $job_editable==1 ){ ?>
                    <div class="form-group text-right">
                        <button class="btn btn_update_prop_details_row">Update</button>
                    </div>
                <?php } ?>
            </div>
        </section>
    </div>

    <?php
    /**
     * Show this box only if alarm is SS
     * 5 = Safety Switch, 3 = Safety Safety Switch View
     * See alarm_job_type table
     */
    if(in_array(5,$ajt_bundle_ids) OR in_array(3,$ajt_bundle_ids)){

        $view_only_text = (in_array(3,$ajt_bundle_ids)) ? "(View ONLY)" : "";
        ?>
        <div class="col-md-3">
            <section class="card card-orange-fill" style="min-height:235px;">
                <header class="card-header">Safety Switch Details <?php echo $view_only_text; ?></header>
                <div class="card-block">
                    <div class="form-group form-flex">
                        <label class="form-flex-25 txt-bold">Switchboard Location</label>
                        <div class="form-flex-2">
                            <a href="#" data-fancybox="" data-src="#ss_reason_fb"><?php echo $job_row['ss_location']; ?></a>
                        </div>
                    </div>
                    <div class="form-group form-flex">
                        <label class="form-flex-25 txt-bold">No. Switches</label>
                        <div class="form-flex-2">
                            <?php echo $job_row['ss_quantity']; ?>
                        </div>
                    </div>
                    <div class="form-group form-flex">
                        <label class="form-flex-25 txt-bold">Viewed?</label>
                        <div class="form-flex-2">
                            <?php
                            if($job_row['ts_safety_switch'] == '2'){
                                echo "Yes";
                            }elseif($job_row['ts_safety_switch'] == '1'){
                                echo "No";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group form-flex">
                        <label class="form-flex-25 txt-bold">Reason</label>
                        <div class="form-flex-2">
                            <?php
                            if( $job_row['ts_safety_switch']==1 ){

                                switch ($job_row['ts_safety_switch_reason']) {
                                    case 0:
                                        $ssp_reason = 'No Switch';
                                        break;
                                    case 1:
                                        $ssp_reason = 'Unable to Locate';
                                        break;
                                    case 2:
                                        $ssp_reason = 'Unable to Access';
                                        break;
                                    default:
                                        $ssp_reason = "No Data";
                                        break;
                                }
                                $ss_reason_txt =  $ssp_reason;

                            }else{
                                $ss_reason_txt =  "N/A";
                            }
                            echo $ss_reason_txt;
                            ?>
                        </div>
                    </div>
                    <div class="form-group form-flex">
                        <label class="form-flex-25 txt-bold">Image</label>
                        <div class="form-flex-2">
                            <?php
                            if( $job_row['ss_image']!="" ){
                                if ( file_exists("{$_SERVER['DOCUMENT_ROOT']}/uploads/switchboard_image/{$job_row['ss_image']}") ) {
                                    // tecsheet CI
                                    $ss_image_upload_folder = '/uploads/switchboard_image';
                                }else{ // old techsheet
                                    $ss_image_upload_folder = "{$this->config->item("crm_link")}/images/ss_image";
                                }
                                ?>

                            <a href="#" class="fancybox" data-fancybox="" data-src="#ss_image_fb">
                                    <span class="fa fa-camera text-blue"></span>
                            </a>
                            <span style="position: relative; bottom: 2px; left: 4px; margin-right: 9px; color: #00aeef;">Image Stored</span>

                            <div id="ss_image_fb" style="display:none;">
                                <section class="card card-blue-fill">
                                        <header class="card-header">Safety Switch Image</header>
                                        <div class="card-block">
                                            <a href="#" data-fancybox="" data-src="<?php echo $ss_image_upload_folder ?>/<?php echo $job_row['ss_image']; ?>">
                                                <img width="167" src="<?php echo $ss_image_upload_folder ?>/<?php echo $job_row['ss_image']; ?>">
                                            </a>
                                        </div>
                                </section>
                            </div>
                                <?php
                            }else{
                                echo "<span class='fa fa-camera text-grey'></span>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Safety Switch Details (View ONLY) Fancybox -->
                <div id="ss_reason_fb" style="display:none;">
                    <?php echo form_open_multipart('/jobs/vjd_update_prop_survey','id=ss_prop_survey_fb');?>

                    <section class="card card-blue-fill bold-label-children">
                        <header class="card-header">Safety Switch Details <?php echo $view_only_text; ?></header>
                        <div class="card-block">

                            <div class="form-group form-flex">
                                <label>Switchboard Location</label>
                                <div class="form-flex-2">
                                    <input type="text" class="form-control" name="ss_location" value="<?php echo $job_row['ss_location']; ?>" />
                                </div>
                            </div>

                            <div class="form-group form-flex">
                                <label>SS Qty</label>
                                <div class="form-flex-2">
                                    <input type="number" class="form-control" name="ss_quantity" value="<?php echo $job_row['ss_quantity']; ?>" />
                                </div>
                            </div>

                            <div class="form-group form-flex">
                                <label>Switchboard Viewed</label>
                                <div class="form-flex-2">
                                    <div class="left radio" style="margin-right:10px;">
                                        <input type="radio" onclick="" name="ts_safety_switch" class="safety_switch_toggle" id="safety_switch_yes" <?php echo ($job_row['ts_safety_switch'] == '2') ? 'checked' : ''; ?> value="2">
                                        <label for="safety_switch_yes">Yes</label>
                                    </div>
                                    <div class="left radio">
                                        <input type="radio" onclick="" name="ts_safety_switch" class="safety_switch_toggle radiobut-red"  id="safety_switch_no"  <?= ($job_row['ts_safety_switch'] == '1') ? 'checked' : ''; ?> value="1">
                                        <label for="safety_switch_no">No</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group form-flex">
                                <label>Reason</label>
                                <div class="form-flex-2">
                                    <?php  if( $job_row['ts_safety_switch']==1 ){ ?>
                                        <select name="ss_reason" id="ss_reason" class="form-control">
                                            <option value="">----</option>
                                            <option value="0" <?php echo ($job_row['ts_safety_switch_reason'] == 0 ? "selected" : ""); ?>>No Switch</option>
                                            <option value="1" <?php echo ($job_row['ts_safety_switch_reason'] == 1 ? "selected" : ""); ?>>Unable to Locate</option>
                                            <option value="2" <?php echo ($job_row['ts_safety_switch_reason'] == 2 ? "selected" : ""); ?>>Unable to Access</option>
                                        </select>
                                    <?php }else{
                                        echo "N/A";
                                    } ?>
                                </div>
                            </div>

                            <div class="form-group form-flex">
                                <label>Switch Board Image</label>
                                <div class="form-flex-2">
                                    <?php if( $job_row['ss_image']!="" ){  ?>
                                        <div><img src="<?php echo $ss_image_upload_folder ?>/<?php echo $job_row['ss_image']; ?>" style="width:95px;margin-bottom:7px;"></div>
                                    <?php } ?>
                                    <input type="file" capture="camera" accept="image/*" name="ss_image" id="ss_image" class="form-control" />
                                </div>
                            </div>

                        </div>
                    </section>

                    <div class="form-group text-right">
                        <?php if( $job_editable==1 ){ ?>
                            <input type="hidden" name="vjd_ss_survey_job_id" value="<?php echo $job_row['jid']; ?>">
                            <input type="hidden" name="vjd_ss_survey_board_current_ss_image" value="<?php echo $job_row['ss_image']; ?>">
                            <button type="button" id="btn_update_ss_survey" class="btn btn_update_ss_survey">Update</button>
                        <?php } ?>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </section>
        </div>
    <?php } ?>
    <div class="col-md-6">
        <section class="card card-red-fill" style="min-height:235px;">
            <header class="card-header">Pre Completion Details  &nbsp;<a href="/jobs/pre_completion"><span class="fa fa-external-link-square text-white"></span></a></header>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-flex">
                            <label class="txt-bold">Job Type</label>
                            <div class="form-flex-2">
                                <?php echo $job_row['j_type']; ?>
                            </div>
                        </div>
                        <div class="form-group form-flex">
                            <label class="txt-bold">Job Status</label>
                            <div class="form-flex-2">
                                <?php echo $job_row['j_status']; ?>
                            </div>
                        </div>

                        <!-- Job Price -->
                        <div class="form-group form-flex ajax_price_toogle_main_box">
                            <label class="txt-bold">Job Price  <a data-toggle='tooltip' title="Show/Hide Price Breakdown" class="toggle_job_price_breakdown" href="#"><span class="fa fa-arrow-down"></span></a></label>
                            <div class="form-flex-2">
                                <?php echo "$". number_format($this->system_model->getJobTotalAmount($job_row['jid']), 2); ?>
                            </div>
                        </div>
                        <!-- Load price details/breakdown via ajax request here -->
                        <div class="form-group ajax_load_price_detail"></div>
                        <!-- Job Price end -->

                        <?php
                        /**
                         * Show Repair Notes only if Job type = FR/Fix or Replace
                         */
                        if($job_row['j_type'] == "Fix or Replace"){
                            ?>
                            <div class="form-group form-flex">
                                <label class="txt-bold">Repair Notes</label>
                                <div class="form-flex-2">
                                    <?php echo $job_row['repair_notes'] ?>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                    <div class="col-md-6 pre-completion-details-button">
                        <div class="row">
                            <div class="col-md-6">
<!--                                <a href="javascript:" class="btn btn-sm btn-warning btn_move_to_booked text-left">Send Back to Tech</a>-->
                                <button data-toggle="modal" data-target="#btn_move_to_booked_modal" class="btn btn-sm btn-warning btn_move_to_booked text-left">Send Back to Tech</button>
                                <a href="javascript:" class="btn btn-sm btn-danger btn_create_rebook text-left">Rebook Job</a>
                                <a href="javascript:" class="btn btn-sm btn-danger btn_create_240v_rebook text-left">Rebook Job (240v)</a>
                                <?php
                                /**
                                 * Show this Update to Merged button only if Job status is currently not Merged
                                 * AND status is currently TBI or Pre Completion
                                 */
                                if($job_row['j_status']!="Merged Certificates" && ($job_row['j_status']=="To Be Invoiced" OR $job_row['j_status']=="Pre Completion")){
                                    ?>
                                    <a href="javascript:" class="btn btn-sm btn-success btn_update_to_merge">Update to Merged</a>
                                <?php } ?>
                            </div>
                            <div class="col-md-6">
                                <a target="_blank" href="/email/send/?job_id=<?php echo $job_row['jid'] ?>" class="btn btn-sm btn-primary">Email Templates</a>
                                <a target="_blank" href="/sms/send/?job_id=<?php echo $job_row['jid'] ?>" class="btn btn-sm btn-primary">SMS Templates</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Current Alarms -->
<?php if( Alarm_job_type_model::show_smoke_alarms($ajt_bundle_ids) ){ ?>
    <?php if( $num_alarms>0 ){ ?>
        <section class="card card-blue-fill">
            <header class="card-header">
                Current Alarms
                <?php
                if( $this->config->item('country') ==1){

                    if($job_row['p_state']=="QLD"){
                        if( $job_row['preferred_alarm_id'] > 0 && $job_row['qld_new_leg_alarm_num'] > 0 ){
                            echo "<small>Property should use {$job_row['pref_alarm_make']} alarms</small>";
                        }
                    }else{
                        echo "<small>Property should use ".$this->system_model->display_free_emerald_or_paid_brooks($job_row['a_id'])." alarms</small>";
                    }

                }else{
                    echo "<small>Property should use ".$this->system_model->display_orca_or_cavi_alarms($job_row['a_id'])." alarms</small>";
                }
                ?>
            </header>

            <div class="card-block">

                <table class="table table-hover main-table">
                    <thead>
                    <tr>
                        <th>Position</th>
                        <th>Power</th>
                        <th>Type</th>
                        <th>RFC</th>
                        <th>New?</th>
                        <th>Price</th>
                        <th>Reason</th>
                        <?php
                        // only if IC alarm
                        if (in_array($job_row['j_service'], $ic_serv)) {
                            echo "<th class='colorwhite'>Interconnected</th>";
                        }
                        ?>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Expiry</th>
                        <th>TS</th>
                        <th>dB</th>
                        <th>Images</th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($alarms as $alarms_row){
                        ?>
                        <tr>
                            <td>
                                <input type='hidden' name='alarm_id[]' value='<?php echo $alarms_row['alarm_id']; ?>'>
                                <input type='hidden' name='sa_serv_manipulated[]' class='sa_serv_manipulated' value='0'>

                            <a href="#" data-fancybox data-src="#current_alarms_fb_v2">
                                    <?php echo $alarms_row['ts_position']; ?>
                                </a>
                            </td>
                            <td>
                                <?php
                                echo $alarms_row['alarm_pwr'];
                                ?>
                            </td>
                            <td>
                                <?php echo $alarms_row['alarm_type']; ?>
                            </td>
                            <td>
                                <?php
                                if($alarms_row['ts_required_compliance'] == 0){
                                    echo "No";
                                }elseif($alarms_row['ts_required_compliance'] == 1){
                                    echo "Yes";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($alarms_row['new'] == 0){
                                    echo "No";
                                }elseif($alarms_row['new'] == 1){
                                    echo "<span class='txt-bold text-red'>YES</span>";
                                }
                                ?>
                            </td>
                            <td>
                            <?php  echo $alarms_row['alarm_price']; ?>
                            </td>
                            <td>
                                <?php echo $alarms_row['alarm_reason']; ?>
                            </td>
                            <?php  if (in_array($job_row['j_service'], $ic_serv)) { ?>
                                <td>
                                    <?php
                                    if( $alarms_row['ts_alarm_sounds_other'] == 0 ){
                                        echo "No";
                                    }elseif($alarms_row['ts_alarm_sounds_other'] == 1){
                                        echo "Yes";
                                    }
                                    ?>
                                </td>
                            <?php } ?>
                            <td>
                                <?php echo strtoupper($alarms_row['make']); ?>
                            </td>
                            <td>
                                <?php echo strtoupper($alarms_row['model']); ?>
                            </td>
                            <td>
                                <?php echo $alarms_row['expiry']; ?>

                            </td>
                            <td>
                                <?php echo $alarms_row['ts_expiry'] ?>
                            </td>
                            <td>
                                <?php echo $alarms_row['ts_db_rating']; ?>
                            </td>
                            <td>
                                <?php if($alarms_row['alarm_expire_image']!="" && $alarms_row['alarm_location_image']!=""){
                                    if ($can_add_photo) {
                                        // When user has permission to add and update alarm images
                                        $permission_text = "View and update alarm images";
                                    } else {
                                        // When user does not have permission to add and update alarm images
                                        $permission_text = "View alarm images";
                                    }
                                    ?>
                                    <a href="#" class="fancybox" data-fancybox="" data-src="#curr_alarm_images_fb_<?php echo $alarms_row['alarm_id']; ?>" onclick='show_alarm_photo(<?= $alarms_row["alarm_id"].",".$job_row["a_id"]; ?>)' title="<?=$permission_text?>"><span class="fa fa-camera"></span></a>
                                <?php }else{
                                    if ($can_add_photo) {
                                        // When user has permission to add and update alarm images
                                        $permission_text = "Add and update alarm images";
                                    } else {
                                        // When user does not have permission to add and update alarm images
                                        $permission_text = "No alarm images";
                                    }
                                    if ($can_add_photo) {
                                        echo '<a href="#" class="fancybox" data-fancybox="" data-src="#curr_alarm_images_fb_' . $alarms_row['alarm_id'] . '" onclick="show_alarm_photo(' . $alarms_row["alarm_id"] . ','.$job_row["a_id"].')" title="'.$permission_text.'"><span class="fa fa-camera text-grey"></span></a>';

                                    } else {
                                        echo "<span class='fa fa-camera text-grey' title='".$permission_text."'></span>";
                                    }
                                } ?>

                                <div id="curr_alarm_images_fb_<?php echo $alarms_row['alarm_id']; ?>" style="display: none; width:520px;">
                                    <form id="form-user-profile_pic" action="/jobs/upload_alarm_images/?alarm_id=<?php echo $alarms_row['alarm_id']; ?>&job_id=<?=$job_row['jid']?>" method="post" enctype="multipart/form-data">
                                        <div class="row" id="show_alarm_photo_div_<?php echo $alarms_row['alarm_id']; ?>">
                                        </div>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <?php
                                if( $job_editable==1 ){
                                    $alarm_id = $alarms_row['alarm_id'];
                                    echo "<button class='btn btn-danger btn-sm remove_alarm' data-alarmid='{$alarm_id}'>Remove</button>";
                                }else{
                                    echo "N/A";
                                }
                                ?>
                            </td>

                        </tr>

                <?php } ?>
            </tbody>
        </table>

        <!-- Current Alarms fancybox -->
        <div id="current_alarms_fb_v2" style="display: none; max-width:max-content">
        <?php echo form_open('/jobs/vjd_update_alarm_price','id=current_alarm_bulk_update_form') ?>
                            <section class="card card-blue-fill bold-label-children">
                                <header class="card-header">Current Alarms</header>
                <div class="card-block">

                    <table class="table table-hover main-table">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Power</th>
                                <th>Type</th>
                                <th>RFC</th>
                                <th>New?</th>
                                <th>Price</th>
                                <th>Reason</th>
                                <?php
                                    // only if IC alarm
                                    if (in_array($job_row['j_service'], $ic_serv)) {
                                        echo "<th class='colorwhite'>Interconnected</th>";
                                    }
                                ?>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Expiry</th>
                                <th>TS</th>
                                <th>dB</th>
                                <th>Images</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($alarms as $alarms_row){
                            ?>
                            <tr>
                                <td>
                                    <input type='hidden' class="current_alarm_id" name='alarm_id[]' value='<?php echo $alarms_row['alarm_id']; ?>'>
                                    <input class='form-control current_alarm_ts_position' type='text' name='ts_position[]' value='<?php echo $alarms_row['ts_position']; ?>'>
                                    <input class='orig_current_alarm_ts_position' type='hidden' value='<?php echo $alarms_row['ts_position']; ?>'>
                                </td>
                                <td>
                                     <select name='alarm_power_id[]' size=1 class='current_alarm_power_id form-control'>
                                                    <?php
                                                    foreach ($alarm_pwr->result_array() as $a_i => $a_data) {
                                                        echo "<option value='" . $a_data['alarm_pwr_id'] . "' " . ($alarms_row['alarm_power_id'] == $a_data['alarm_pwr_id'] ? 'selected' : '') . ">" . $a_data['alarm_pwr'] . "</option>\n";
                                                    }
                                                    ?>
                                                </select>
                                    <input type="hidden" class="orig_current_alarm_power_id" value="<?php echo $alarms_row['alarm_power_id']; ?>" >
                                </td>
                                <td>
                                    <select class="form-control current_alarm_type_id" type=text name='alarm_type_id[]'>
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    foreach ($alarm_type->result_array() as $a_i => $a_data) {
                                                        echo "<option value='" . $a_data['alarm_type_id'] . "' " . ($alarms_row['alarm_type_id'] == $a_data['alarm_type_id'] ? 'selected' : '') . ">" . $a_data['alarm_type'] . "</option>\n";
                                                    }
                                                    ?>
                                                </select>
                                    <input type="hidden" class="orig_current_alarm_type_id" value="<?php echo $alarms_row['alarm_type_id']; ?>" >
                                </td>
                                <td>
                                    <select class="form-control current_ts_required_compliance" type=text name='ts_required_compliance[]' style='width: 90px !important;'>
                                                    <option value='0' <?php echo ($alarms_row['ts_required_compliance'] == 0 ? 'selected' : '') ?>>No</option>
                                                    <option value='1' <?php echo ($alarms_row['ts_required_compliance'] == 1 ? 'selected' : '') ?>>Yes</option>
                                                </select>
                                    <input type="hidden" class="orig_current_ts_required_compliance" value="<?php echo $alarms_row['ts_required_compliance']; ?>" >
                                </td>
                                <td>
                                    <select class="form-control current_newinstall" type=text name='newinstall[]' style='width: 90px !important;'>
                                                    <option value='0' <?php echo ($alarms_row['new'] == 0 ? 'selected' : '') ?>>No</option>
                                                    <option value='1' <?php echo ($alarms_row['new'] == 1 ? 'selected' : '') ?>>Yes</option>
                                                </select>
                                    <input type="hidden" class="orig_current_newinstall" value="<?php echo $alarms_row['new']; ?>" >
                                </td>
                                <td>
                                    <div class="form-control-wrapper form-control-icon-left">
                                        <input name="alarm_price[]" type="number" class="form-control current_alarms_price" style="width:120px;" value="<?php echo $alarms_row['alarm_price']; ?>">
                                        <i class="fa fa-dollar"></i>
                                        </div>
                                    <input type="hidden" class="orig_current_alarms_price" value="<?php echo $alarms_row['alarm_price']; ?>" >
                                </td>
                                <td>
                                    <select class="form-control current_alarm_reason_id" type=text name='alarm_reason_id[]' style='width: 190px !important;'>
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    foreach ($alarm_reason->result_array() as $a_i => $a_reason_row) {
                                                        echo "<option value='" . $a_reason_row['alarm_reason_id'] . "' " . ($alarms_row['alarm_reason_id'] == $a_reason_row['alarm_reason_id'] ? 'selected' : '') . ">" . $a_reason_row['alarm_reason'] . "</option>\n";
                                                    }
                                                    ?>
                                                </select>
                                    <input type="hidden" class="orig_current_alarm_reason_id" value="<?php echo $alarms_row['alarm_reason_id']; ?>" >
                                </td>
                                <?php  if (in_array($job_row['j_service'], $ic_serv)) { ?>
                                <td>
                                    <select type=text name='ts_is_alarm_ic[]' size=1 class='current_ts_is_alarm_ic form-control'>
                                                        <option value=''>--- Select ---</option>
                                                        <option value='0' <?php echo ( ( is_numeric($alarms_row['ts_alarm_sounds_other']) && $alarms_row['ts_alarm_sounds_other'] == 0 ) ? 'selected' : '') ?>>No</option>
                                                        <option value='1' <?php echo ($alarms_row['ts_alarm_sounds_other'] == 1 ? 'selected' : '') ?>>Yes</option>
                                                    </select>
                                    <input type="hidden" class="orig_current_ts_is_alarm_ic" value="<?php echo $alarms_row['ts_alarm_sounds_other']; ?>" >
                                </td>
                                <?php } ?>
                                <td>
                                    <input  type=text name='make[]' value='<?php echo strtoupper($alarms_row['make']); ?>'  class='current_make form-control' style="text-transform:uppercase;">
                                    <input type="hidden" class="orig_current_make" value="<?php echo strtoupper($alarms_row['make']); ?>" >
                                </td>
                                <td>
                                    <input type=text name='model[]' value='<?php echo strtoupper($alarms_row['model']); ?>'  class='current_model form-control' style="text-transform:uppercase;">
                                    <input type="hidden" class="orig_current_model" value="<?php echo strtoupper($alarms_row['model']); ?>" >
                                </td>
                                <td>
                                    <input type='number' name='expiry[]' style='width: 90px;' value='<?php echo $alarms_row['expiry']; ?>' class='current_expiry form-control' />
                                    <input type="hidden" class="orig_current_expiry" value="<?php echo $alarms_row['expiry']; ?>" >
                                </td>
                                <td>
                                    <?php echo $alarms_row['ts_db_rating']; ?>
                                </td>
                                <td>
                                    <input type='number' class='current_ts_db_rating form-control' name='ts_db_rating[]' style='width: 90px !important;' value='<?php echo $alarms_row['ts_db_rating']; ?>' maxlength='3' />
                                    <input type="hidden" class="orig_current_ts_db_rating" value="<?php echo $alarms_row['ts_db_rating']; ?>" >
                                </td>
                                <td>
                                    <?php if($alarms_row['alarm_expire_image']!="" && $alarms_row['alarm_location_image']!=""){ ?>
                                    <a href="#" class="fancybox" data-fancybox="" data-src="#curr_alarm_images_fb_<?php echo $alarms_row['alarm_id']; ?>"><span class="fa fa-camera"></span></a>
                                    <?php }else{
                                        echo "<span class='fa fa-camera text-grey'></span>";
                                    } ?>

                                    <div id="curr_alarm_images_fb_<?php echo $alarms_row['alarm_id']; ?>" style="display: none;">
                                        <section class="card card-blue-fill">
                                            <header class="card-header">Pic of Expiry Date</header>
                                            <div class="card-block">
                                            <?php
                                            if($agency_pref_row['sel_pref_val'] == 0 && $agency_pref_row['sel_pref_val'] != ""){
                                                echo "<img src='{$not_included_image_placeholder}'>";
                                            }else{
                                                if($alarms_row['alarm_expire_image']!=""){
                                                        //echo "<a href='#' data-fancybox='' data-src='/images/alarm_images/{$alarms_row['alarm_expire_image']}'><img style='width:250px' src='/images/alarm_images/{$alarms_row['alarm_expire_image']}'></a>";
                                                        echo "<img style='width:250px' src='/images/alarm_images/{$alarms_row['alarm_expire_image']}'>";
                                                }else{
                                                        echo "<img style='width:250' src='{$no_image_placeholder}'>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        </section>

                                        <section class="card card-blue-fill">
                                            <header class="card-header">Pic of Alarm Location</header>
                                            <div class="card-block">
                                            <?php
                                            if($agency_pref_row['sel_pref_val'] == 0 && $agency_pref_row['sel_pref_val'] != ""){
                                                echo "<img src='{$not_included_image_placeholder}'>";
                                            }else{
                                                if($alarms_row['alarm_location_image']!=""){
                                                        //echo "<a href='#' data-fancybox='' data-src='/images/alarm_images/{$alarms_row['alarm_location_image']}'><img style='width:250' src='/images/alarm_images/{$alarms_row['alarm_location_image']}'></a>";
                                                        echo "<img style='width:250' src='/images/alarm_images/{$alarms_row['alarm_location_image']}'>";
                                                }else{
                                                        echo "<img style='width:250' src='{$no_image_placeholder}'>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        </section>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                                </div>
                            </section>

            <div class="text-right">
                <?php
                 if( $can_edit_alarms === TRUE ){
                    ?>
                <button type="button" class="btn btn_bulk_update_current_alarms">Update</button>
                                <?php } ?>
                            </div>
            <?php echo form_close(); ?>
                        </div>

            </div>
        </section>
    <?php } ?>
    <!-- Current Alarms end -->

    <!-- Removed/Discarded Alarms -->
    <?php if(count($disc_alarms)>0){ ?>
        <section class="card card-red-fill">
            <header class="card-header">Removed/Discarded Alarms</header>
            <div class="card-block">
                <table class="table table-hover main-table">
                    <thead>
                    <tr>
                        <th>Position</th>
                        <th>Power</th>
                        <th>Type</th>
                        <th>RFC</th>
                        <th>Reason</th>
                        <?php
                        // only if IC alarm
                        if (in_array($job_row['j_service'], $ic_serv)) {
                            echo "<th class='colorwhite'>Interconnected</th>";
                        }
                        ?>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Expiry</th>
                        <th>TS</th>
                        <th>dB</th>
                        <th>Images</th>
                        <th>Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  foreach($disc_alarms as $disc_alarms_row) {
                        ?>
                        <tr>
                            <td>
                                <input type='hidden' name='disc_alarm_id[]' value='<?php echo $disc_alarms_row['alarm_id']; ?>'>
                                <input type='hidden' name='disc_sa_serv_manipulated[]' class='sa_serv_manipulated' value='0'>
                                <a href="#" data-fancybox data-src="#discarded_alarms_fb_<?php echo $disc_alarms_row['alarm_id'] ?>"><?php echo $disc_alarms_row['ts_position']; ?></a>
                            </td>
                            <td>
                                <?php echo $disc_alarms_row['alarm_pwr']; ?>
                            </td>
                            <td>
                                <?php echo $disc_alarms_row['alarm_type']; ?>
                            </td>
                            <td>
                                <?php
                                if($disc_alarms_row['ts_required_compliance'] == 0){
                                    echo "No";
                                }else if($disc_alarms_row['ts_required_compliance'] == 1){
                                    echo "Yes";
                                }else{
                                    echo "";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $disc_alarms_row['discarded_reason_text'];
                                ?>
                            </td>
                            <?php  if (in_array($job_row['j_service'], $ic_serv)) { ?>
                                <td>
                                    <?php
                                    if(is_numeric($disc_alarms_row['ts_alarm_sounds_other']) && $disc_alarms_row['ts_alarm_sounds_other'] == 0){
                                        echo "No";
                                    }else if($disc_alarms_row['ts_alarm_sounds_other'] == 1){
                                        echo "Yes";
                                    }
                                    ?>
                                </td>
                            <?php } ?>
                            <td>
                                <?php echo strtoupper($disc_alarms_row['make']); ?>
                            </td>
                            <td>
                                <?php echo strtoupper($disc_alarms_row['model']); ?>
                            </td>
                            <td>
                                <?php echo $disc_alarms_row['expiry']; ?>
                            </td>
                            <td><?php echo $disc_alarms_row['ts_expiry'] ?></td>
                            <td>
                                <?php echo $disc_alarms_row['ts_db_rating']; ?>
                            </td>
                            <td>
                                <?php if($disc_alarms_row['disc_alarm_expiry_image']!="" && $disc_alarms_row['disc_alarm_location_image']!=""){
                                    if ($can_add_photo) {
                                        // When user has permission to add and update alarm images
                                        $permission_text = "View and update alarm images";
                                    } else {
                                        // When user does not have permission to add and update alarm images
                                        $permission_text = "View alarm images";
                                    }
                                    ?>
                                    <a href="#" class="fancybox" data-fancybox="" data-src="#curr_alarm_images_fb_<?php echo $disc_alarms_row['alarm_id']; ?>" onclick='show_alarm_photo(<?= $disc_alarms_row["alarm_id"].",".$job_row["a_id"]; ?>)' title="<?=$permission_text?>"><span class="fa fa-camera"></span></a>
                                <?php }else{
                                    if ($can_add_photo) {
                                        // When user has permission to add and update alarm images
                                        $permission_text = "Add and update alarm images";
                                    } else {
                                        // When user does not have permission to add and update alarm images
                                        $permission_text = "No alarm images";
                                    }
                                    if ($can_add_photo) {
                                        echo '<a href="#" class="fancybox" data-fancybox="" data-src="#curr_alarm_images_fb_'.$disc_alarms_row['alarm_id'].'" onclick="show_alarm_photo(' . $disc_alarms_row["alarm_id"] . ','.$job_row["a_id"].')" title="'.$permission_text.'"><span class="fa fa-camera text-grey"></span></a>';
                                    } else {
                                        echo "<span class='fa fa-camera text-grey' title='".$permission_text."'></span>";
                                    }
                                } ?>

                                <div id="curr_alarm_images_fb_<?php echo $disc_alarms_row['alarm_id']; ?>" style="display: none; width:520px;">
                                    <form id="form-user-profile_pic" action="/jobs/upload_alarm_images/?alarm_id=<?php echo $disc_alarms_row['alarm_id']; ?>&job_id=<?=$job_row['jid']?>" method="post" enctype="multipart/form-data">
                                        <div class="row" id="show_alarm_photo_div_<?php echo $disc_alarms_row['alarm_id']; ?>">
                                        </div>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <?php
                                if( $job_editable==1 ){
                                    echo "<button class='btn btn-danger btn-sm remove_alarm' data-alarmid='{$disc_alarms_row['alarm_id']}'>Remove</button>";
                                }else{
                                    echo "N/A";
                                }
                                ?>
                            </td>
                        </tr>

                        <!-- Discarded Alarms Fancybox -->
                        <div class="alarms_fb" id="discarded_alarms_fb_<?php echo $disc_alarms_row['alarm_id'] ?>" style="width:500px;display:none;">

                            <section class="card card-blue-fill">
                                <header class="card-header">Removed/Discarded Alarms Details</header>
                                <div class="card-block bold-label-children">

                                    <div class="form-group form-flex">
                                        <label>Position</label>
                                        <div class="form-flex-2">
                                            <input class='form-control' type='text' name='ts_position' value='<?php echo $disc_alarms_row['ts_position']; ?>' size=8 class='xsmall addinput'>
                                        </div>
                                    </div>

                                    <div class="form-group form-flex">
                                        <label>Power</label>
                                        <div class="form-flex-2">
                                            <select name='alarm_power_id' size=1 class='vjd-sel form-control' style='width: 100px !important;'>
                                                <?php
                                                foreach ($alarm_pwr->result_array() as $a_i => $a_data) {
                                                    echo "<option value='" . $a_data['alarm_pwr_id'] . "' " . ($disc_alarms_row['alarm_power_id'] == $a_data['alarm_pwr_id'] ? 'selected' : '') . ">" . $a_data['alarm_pwr'] . "</option>\n";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group form-flex">
                                        <label>Type</label>
                                        <div class="form-flex-2">
                                            <select class="form-control" type=text name='alarm_type_id' size=1 class='vjd-sel'>
                                                <option value="">&nbsp;</option>
                                                <?php
                                                foreach ($alarm_type->result_array() as $a_i => $a_data) {
                                                    echo "<option value='" . $a_data['alarm_type_id'] . "' " . ($disc_alarms_row['alarm_type_id'] == $a_data['alarm_type_id'] ? 'selected' : '') . ">" . $a_data['alarm_type'] . "</option>\n";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group form-flex">
                                        <label>RFC</label>
                                        <div class="form-flex-2">
                                            <select class="form-control" type=text name='ts_required_compliance' size=1 class='vjd-sel' style='width: 90px !important;'>
                                                <option value='0' <?php echo ($disc_alarms_row['ts_required_compliance'] == 0 ? 'selected' : '') ?>>No</option>
                                                <option value='1' <?php echo ($disc_alarms_row['ts_required_compliance'] == 1 ? 'selected' : '') ?>>Yes</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group form-flex">
                                        <label>Reason</label>
                                        <div class="form-flex-2">
                                            <select class="form-control" type=text name='alarm_reason_id' size=1 class='vjd-sel' style='width: 190px !important;'>
                                                <option value="">&nbsp;</option>
                                                <?php
                                                foreach ($alarm_discarded_reason->result_array() as $row) {
                                                    echo "<option value='" . $row['id'] . "' " . ($disc_alarms_row['ts_discarded_reason'] == $row['id'] ? 'selected' : '') . ">" . $row['reason'] . "</option>\n";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <?php
                                    if (in_array($job_row['j_service'], $ic_serv)) {
                                        ?>
                                        <div class="form-group form-flex">
                                            <label>Interconnected</label>
                                            <div class="form-flex-2">
                                                <select type=text name='ts_is_alarm_ic' size=1 class='vjd-sel form-control'>
                                                    <option value=''>--- Select ---</option>
                                                    <option value='0' <?php echo ( ( is_numeric($disc_alarms_row['ts_alarm_sounds_other']) && $disc_alarms_row['ts_alarm_sounds_other'] == 0 ) ? 'selected' : '') ?>>No</option>
                                                    <option value='1' <?php echo ($disc_alarms_row['ts_alarm_sounds_other'] == 1 ? 'selected' : '') ?>>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <div class="form-group form-flex">
                                        <label>Make</label>
                                        <div class="form-flex-2">
                                            <input  type=text name='make' value='<?php echo strtoupper($disc_alarms_row['make']); ?>' size=8 class='xsmall addinput exlarge form-control'>
                                        </div>
                                    </div>

                                    <div class="form-group form-flex">
                                        <label>Model</label>
                                        <div class="form-flex-2">
                                            <input type=text name='model' value='<?php echo strtoupper($disc_alarms_row['model']); ?>' size=8 class='xsmall addinput exlarge form-control'>
                                        </div>
                                    </div>

                                    <div class="form-group form-flex">
                                        <label>Expiry</label>
                                        <div class="form-flex-2">
                                            <input type='number' name='expiry' style='width: 88px;' value='<?php echo $disc_alarms_row['expiry']; ?>' size=8 class='xxsmall addinput form-control' />
                                        </div>
                                    </div>

                                    <div class="form-group form-flex">
                                        <label>dB</label>
                                        <div class="form-flex-2">
                                            <input type='number' class='xxsmall addinput vjd-inpt-rtn form-control' name='ts_db_rating' style='width: 88px !important;' value='<?php echo $disc_alarms_row['ts_db_rating']; ?>' maxlength='3' />
                                        </div>
                                    </div>

                                </div>
                            </section>

                            <div class="form-group text-right">
                            <?php if($can_edit_alarms === TRUE){ ?>
                                    <input type="hidden" class="vjd_alarm_id" value="<?php echo $disc_alarms_row['alarm_id']; ?>">
                                    <button type="button" class="btn btn_update_current_alarms_row">Update</button>
                                <?php } ?>
                            </div>

                        </div>
                        <!-- Discarded Alarms Fancybox end -->

                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php } ?>
    <!-- Removed/Discarded Alarms end -->
<?php } ?>


<!-- Corded Windows Details -->
<?php if( Alarm_job_type_model::show_corded_windows($ajt_bundle_ids) ){ ?>

    <section class="card card-green-fill">
        <header class="card-header">Window Details</header>
        <div class="card-block">
            <table class="table table-hover main-table">
                <thead>
                <tr>
                    <th>Location</th>
                    <th>Number of Windows</th>
                    <th>Remove</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach( $job_row['corded_window'] as $corded_window_row ){ ?>
                    <tr>
                        <td><a href="#" data-fancybox="" data-src="#window_detail_fb_<?php echo $corded_window_row['corded_window_id'] ?>"><?php echo $corded_window_row['location']; ?></a></td>
                        <td><?php echo $corded_window_row['num_of_windows']; ?></td>
                        <td><button data-corded_window_id="<?php echo $corded_window_row['corded_window_id'] ?>" href="#" class="btn-sm btn btn-danger del_cw">Remove</button></td>
                    </tr>

                    <div class="window_detail_fb" id="window_detail_fb_<?php echo $corded_window_row['corded_window_id'] ?>" style="display:none;">

                        <section class="card card-blue-fill">
                            <header class="card-header">Window Details</header>
                            <div class="card-block">
                                <div class="form-group">
                                    <label>Location</label>
                                    <input class="form-control" type="text" name="cw_location" value="<?php echo $corded_window_row['location'] ?>">
                                </div>

                                <div class="form-group">
                                    <label>Number of windows</label>
                                    <input class="form-control" type="number" name="cw_number_of_windows" value="<?php echo $corded_window_row['num_of_windows'] ?>">
                                </div>
                            </div>
                        </section>

                        <div class="form-group text-right">
                            <?php if( $job_editable==1 ){ ?>
                                <input type="hidden" class="vjd_corded_window_id" value="<?php echo $corded_window_row['corded_window_id']; ?>">
                                <button type="button" class="btn btn_update_corded_window">Update</button>
                            <?php } ?>
                        </div>

                    </div>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

<?php } ?>
<!-- Corded Windows Details end -->

<!-- Safety switch -->
<?php if( Alarm_job_type_model::show_safety_switches($ajt_bundle_ids) ): ?>
    <section class="card card-orange-fill">
        <header class="card-header">Safety Switch Details (Mechanical Test)</header>
        <div class="card-block">
            <table class="table table-hover main-table">
                <thead>
                <tr>
                    <th>New?</th>
                    <th>Reason</th>
                    <th>Pole</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Test</th>
                    <th>Discarded</th>
                    <th>Remove</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach( $job_row['safety_switch'] as $safty_switch_row ){ ?>
                    <tr>
                        <td>
                            <a href="#" data-fancybox="" data-src="#ss_details_fb_<?php echo $safty_switch_row['safety_switch_id'] ?>">
                                <?php
                                if($safty_switch_row['new'] == 1){
                                    echo "Yes";
                                }elseif($safty_switch_row['new'] == 0){
                                    echo "Existing";
                                }
                                ?>
                            </a>
                        </td>
                        <td>
                            <?php
                            $ss_res_id = $safty_switch_row['ss_res_id'];
                            if( $ss_res_id=="" OR $ss_res_id <= 0 ){
                                echo " ";
                            }else{
                                $ss_reason_q = $this->db->query("
                                    SELECT 
                                        `ss_res_id`,    
                                        `reason`                        
                                    FROM `safety_switch_reason`
                                    WHERE ss_res_id = {$ss_res_id}
                                    ");
                                $ss_reason_row = $ss_reason_q->row();
                                echo $ss_reason_row->reason;
                            }
                            ?>

                        </td>
                        <td>
                            <?php
                            if( $safty_switch_row['ss_stock_id'] == "" OR $safty_switch_row['ss_stock_id'] <= 0 ){
                                echo " ";
                            }else{
                                $ss_stock_q = $this->db->query("
                                SELECT 
                                    `ss_stock_id`,
                                    `pole`,
                                    `make`,
                                    `model`                            
                                FROM `safety_switch_stock`
                                WHERE `active` = 1 AND
                                ss_stock_id = {$safty_switch_row['ss_stock_id']}
                                ");
                                echo $ss_stock_q->row()->pole." Pole";
                            }

                            ?>
                        </td>
                        <td>
                            <?php echo $safty_switch_row['make'] ?>
                        </td>
                        <td>
                            <?php echo $safty_switch_row['model']; ?>
                        </td>
                        <td>
                            <?php echo $this->gherxlib->safety_switch_test_result($safty_switch_row['safety_switch_id']); ?>
                        </td>
                        <td> <?php echo ( $safty_switch_row['discarded'] == 1 )?'Yes':null; ?></td>
                        <td class="colorwhite" style="border-right: 1px solid #ccc;">
                            <?php
                            if( $safty_switch_row['discarded'] == 1 ){ // discarded ?>

                                <a href="#" class="btn btn-sm btn-danger del_ss" data-ssid="<?php echo $safty_switch_row['safety_switch_id'] ?>">Delete</a>

                                <?php
                            }else{ ?>

                                <a data-fancybox="" data-src="#ss_discarded_fb_<?= $safty_switch_row['safety_switch_id']; ?>" class="btn btn-sm btn-danger fancybox confirm_discard_ss_link" data-discard_ss_id="<?= $safty_switch_row['safety_switch_id']; ?>" href="#confirm_discard_ss_fb">Remove</a>

                                <div style="display: none;" id="ss_discarded_fb_<?= $safty_switch_row['safety_switch_id']; ?>" class="text-center ss_discarded_fb">
                                    <p>This will discard this safety switch. Please select reason for discarding.</p>

                                    <div class="form-group">
                                        <select class='form-control ss_discard_reason'>
                                            <option value=''>---</option>
                                            <?php
                                            // get safety switch reason
                                            $ss_reason_sql = $this->db->query("
                                            SELECT 
                                                `ss_res_id`,    
                                                `reason`                        
                                            FROM `safety_switch_reason`
                                            ");
                                            foreach( $ss_reason_sql->result() as $ss_reason_row ){ ?>
                                                <option value='<?php echo $ss_reason_row->ss_res_id; ?>'><?php echo $ss_reason_row->reason; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" class="discard_ss_id" value="<?= $safty_switch_row['safety_switch_id']; ?>"/>
                                        <button type="button" class="btn confirm_discard_yes_btn" >Yes</button>
                                        <button data-fancybox-close="" type="button" id="confirm_discard_no_btn" class="btn btn-danger" >Cancel</button>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>

                    <div style="display: none;width:520px;" class="ss_details_fb" id="ss_details_fb_<?php echo $safty_switch_row['safety_switch_id'] ?>">

                        <section class="card card-blue-fill bold-label-children">
                            <header class="card-header">Safety Switch Details</header>
                            <div class="card-block">

                                <div class="form-group form-flex">
                                    <label>New?</label>
                                    <div class="form-flex-2">
                                        <select name="ss_new_update" class="form-control">
                                            <option value="">---</option>
                                            <option value="1" <?php echo ( $safty_switch_row['new'] == 1 )?'selected':null; ?>>Yes</option>
                                            <option value="0" <?php echo ( $safty_switch_row['new'] == 0 && is_numeric($safty_switch_row['new']) )?'selected':null; ?>>Existing</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group form-flex">
                                    <label>Reason</label>
                                    <div class="form-flex-2">
                                        <select name="ss_reason_update" class="form-control ss_reason_update">
                                            <option value="">---</option>
                                            <?php
                                            // get safety switch reason
                                            $ss_reason_sql = $this->db->query("
                                            SELECT 
                                                `ss_res_id`,    
                                                `reason`                        
                                            FROM `safety_switch_reason`
                                            ");
                                            foreach( $ss_reason_sql->result() as $ss_reason_row  ){ ?>
                                                <option value='<?php echo $ss_reason_row->ss_res_id; ?>' <?php echo ( $ss_reason_row->ss_res_id == $safty_switch_row['ss_res_id'] )?'selected':null; ?>><?php echo $ss_reason_row->reason; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group form-flex">
                                    <label>Pole</label>
                                    <div class="form-flex-2">
                                        <select name="ss_pole_update" class="form-control">
                                            <option value="">---</option>
                                            <?php
                                            // get safety switch stocks
                                            $ss_stock_sql = $this->db->query("
                                            SELECT 
                                                `ss_stock_id`,
                                                `pole`,
                                                `make`,
                                                `model`                            
                                            FROM `safety_switch_stock`
                                            WHERE `active` = 1
                                            ");
                                            foreach( $ss_stock_sql->result() as $ss_stock_row ){ ?>
                                                <option
                                                        value="<?php echo $ss_stock_row->ss_stock_id; ?>"
                                                        data-ss_stock_make="<?php echo $ss_stock_row->make; ?>"
                                                        data-ss_stock_model="<?php echo $ss_stock_row->model; ?>"
                                                    <?php echo ( $ss_stock_row->ss_stock_id == $safty_switch_row['ss_stock_id'] )?'selected':null; ?>
                                                >
                                                    <?php echo $ss_stock_row->pole; ?> Pole
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group form-flex">
                                    <label>Make</label>
                                    <div class="form-flex-2">
                                        <input type="text" class="form-control" name="ss_make"  value="<?php echo $safty_switch_row['make'] ?>" />
                                    </div>
                                </div>

                                <div class="form-group form-flex">
                                    <label>Model</label>
                                    <div class="form-flex-2">
                                        <input type="text" class="form-control" name="ss_model"  value="<?php echo $safty_switch_row['model']; ?>" />
                                    </div>
                                </div>

                                <div class="form-group form-flex">
                                    <label>Test</label>
                                    <div class="form-flex-2">
                                        <select name="ss_test" class="form-control">
                                            <option value="">---</option>
                                            <option value="1" <?php echo ($safty_switch_row['test'] == 1) ? 'selected="selected"' : ''; ?>>Pass</option>
                                            <option value="0" <?php echo ($safty_switch_row['test'] == 0 && is_numeric($safty_switch_row['test'])) ? 'selected="selected"' : ''; ?>>Fail</option>
                                            <option value="2" <?php echo ($safty_switch_row['test'] == 2) ? 'selected="selected"' : ''; ?>>No Power</option>
                                            <option value="3" <?php echo ($safty_switch_row['test'] == 3) ? 'selected="selected"' : ''; ?>>Not Tested</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </section>

                        <div class="form-group text-right">
                            <?php if( $job_editable==1 ){ ?>
                                <input type="hidden" class="vjd_ss_id" value="<?php echo $safty_switch_row['safety_switch_id']; ?>">
                                <button type="button" class="btn btn_update_ss">Update</button>
                            <?php } ?>
                        </div>

                    </div>

                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
    <!-- Safety switch end -->
<?php endif; ?>

<!-- Water Meter -->
<?php if( Alarm_job_type_model::show_water_meters($ajt_bundle_ids) ){ ?>
    <section class="card card-blue-fill">
        <header class="card-header">Water Meter Details</header>
        <div class="card-block">

            <table class="table table-hover main-table">
                <thead>
                <tr>
                    <th>Location</th>
                    <th>Reading</th>
                    <th>Meter Image</th>
                    <th>Meter Reading Image</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if( !empty($job_row['water_meter']) ){
                    foreach( $job_row['water_meter'] as $water_meter_row ){
                        ?>
                        <tr>
                            <td><a href="#" data-fancybox="" data-src="#water_meter_row_fb_<?php echo $water_meter_row['water_meter_id'] ?>"><?php echo ($water_meter_row['location']!="") ? $water_meter_row['location'] : "No Data"  ?></a></td>
                            <td><?php echo $water_meter_row['reading']  ?></td>
                            <td>
                                <?php if( $water_meter_row['meter_image']!="" ){ ?>

                                    <a href="#" class="fancybox" data-fancybox="" data-src="#wm_img_fb_<?php echo $water_meter_row['water_meter_id'] ?>">
                                        <span class="fa fa-camera text-blue"></span>
                                    </a>
                                    <span style="position: relative; bottom: 2px; left: 4px; margin-right: 9px; color: #00aeef;">Image Stored</span>

                                    <div id="wm_img_fb_<?php echo $water_meter_row['water_meter_id'] ?>" style="display:none;">
                                        <section class="card card-blue-fill">
                                            <header class="card-header"><?php echo $water_meter_row['location']; ?> Meter Image</header>
                                            <div class="card-block text-center">
                                                <a href="" data-fancybox="" data-src="<?=Water_meter_model::image($water_meter_row['meter_image']);?>">
                                                    <img width="167" src="<?=Water_meter_model::image($water_meter_row['meter_image']);?>">
                                                </a>
                                            </div>
                                        </section>
                                    </div>
                                    
                                <?php }else{
                                    echo "<span class='fa fa-camera text-grey'></span>";
                                } ?>
                            </td>
                            <td>
                                <?php if( $water_meter_row['meter_reading_image']!="" ){ ?>

                                    <a href="#" class="fancybox" data-fancybox="" data-src="#wm_img_meter_reading_fb_<?=$water_meter_row['water_meter_id']?>">
                                        <span class="fa fa-camera text-blue"></span>
                                    </a>
                                    <span style="position: relative; bottom: 2px; left: 4px; margin-right: 9px; color: #00aeef;">Image Stored</span>
                                   
                                    <div id="wm_img_meter_reading_fb_<?php echo $water_meter_row['water_meter_id'] ?>" style="display:none;">
                                        <section class="card card-blue-fill">
                                            <header class="card-header"><?php echo $water_meter_row['location']; ?> Meter Reading Image</header>
                                            <div class="card-block text-center">
                                                <a href="" data-fancybox="" data-src="<?=Water_meter_model::image($water_meter_row['meter_reading_image']);?>">
                                                    <img width="167" src="<?=Water_meter_model::image($water_meter_row['meter_reading_image']);?>">
                                                </a>
                                            </div>
                                        </section>
                                    </div>

                                <?php }else{
                                    echo "<span class='fa fa-camera text-grey'></span>";
                                } ?>

                                <div class="water_meter_row_fb" id="water_meter_row_fb_<?php echo $water_meter_row['water_meter_id'] ?>" style="display:none;">

                                    <?php  echo form_open_multipart('/jobs/vjd_update_water_meter','id=vjd_update_water_meter');?>

                                    <section class="card card-blue-fill">
                                        <header class="card-header">Update Water Meter Detail</header>
                                        <div class="card-block">

                                            <div class="form-group">
                                                <label>Location</label>
                                                <input type="text" class="wm_location form-control" name="wm_location" value="<?php echo $water_meter_row['location']  ?>">
                                            </div>

                                            <div class="form-group">
                                                <label>Reading</label>
                                                <input type="number" class="form-control wm_reading" name="wm_reading" value="<?php echo $water_meter_row['reading'] ?>">
                                            </div>

                                            <div class="form-group">
                                                <label>Meter Image</label>
                                                <div>
                                                    <?php if( $water_meter_row['meter_image']!="" ){ ?>
                                                        <img src="<?=Water_meter_model::image($water_meter_row['meter_image']);?>" style="width:95px;">
                                                    <?php } ?>
                                                </div>
                                                <!--<input type="file" capture="camera" accept="image/*" name="water_meter_image" class="form-control">-->
                                            </div>

                                            <div class="form-group">
                                                <label>Meter Reading Image</label>
                                                <div>
                                                    <?php if( $water_meter_row['meter_reading_image']!="" ){ ?>
                                                        <img src="<?=Water_meter_model::image($water_meter_row['meter_reading_image']);?>" style="width:95px;">
                                                    <?php } ?>
                                                </div>
                                                <!--<input type="file" capture="camera" accept="image/*" name="meter_reading_image" class="form-control">-->
                                            </div>

                                        </div>
                                    </section>

                                    <div class="form-group text-right">
                                        <?php if( $job_editable==1 ){ ?>
                                            <input type="hidden" name="vjd_water_meter_id_job_id" value="<?php echo $job_row['jid']; ?>">
                                            <input type="hidden" name="vjd_water_meter_id" value="<?php echo $water_meter_row['water_meter_id']; ?>">
                                            <button type="button" id="btn_update_water_meter_row" class="btn btn_update_water_meter_row">Update</button>
                                        <?php } ?>
                                    </div>
                                    </form>
                                </div>

                            </td>

                        </tr>

                    <?php }

                }else{
                    echo '<tr><td colspan="100%">No Data</td></tr>';
                } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>
<!-- Water Meter end -->

<!-- WE -->
<?php if( Alarm_job_type_model::show_water_efficiency($ajt_bundle_ids) ){ ?>
    <section class="card card-blue-fill">
        <header class="card-header">Water Efficiency Details</header>
        <div class="card-block">
            <table class="table table-hover main-table">
                <thead>
                <tr>
                    <th>Device</th>
                    <th>Toilet Type / Is water flow less than 9L per minute?</th>
                    <th>Location</th>
                    <th>Note</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                <?php $x = 0; foreach( $job_row['we'] as $we_row ){ ?>
                    <tr>
                        <td><a href="#" data-fancybox="" data-src="#we_row_fb_<?php echo $we_row['water_efficiency_id'] ?>"><?php echo $we_row['wed_name']; ?></a></td>
                        <td>
                            <?php
                            if( $we_row['device'] == 2 ){ // toilet
                                $pass_yes = 'Dual';
                                $pass_no = 'Single';
                            }else{
                                $pass_yes = 'Yes';
                                $pass_no = 'No';
                            }

                            if( $we_row['pass'] == 1 ){
                                echo $pass_yes;
                            }elseif( $we_row['pass'] == 0 ){
                                echo $pass_no;
                            }else{
                                echo "No Data";
                            }
                            ?>

                        </td>
                        <td><?php echo $we_row['location'] ?></td>
                        <td><?php echo $we_row['note'] ?></td>
                        <td><a data-we_id="<?php echo $we_row['water_efficiency_id'] ?>" href="#" class="text-red we_del btn btn-danger btn-sm">Remove</a></td>
                    </tr>

                    <div class="we_row_fb_" id="we_row_fb_<?php echo $we_row['water_efficiency_id'] ?>" style="display:none;">

                        <section class="card card-blue-fill">
                            <header class="card-header">Update Water Efficiency</header>
                            <div class="card-block">

                                <div class="form-group form-flex">
                                    <label>Device</label>
                                    <div class="form-flex-2">
                                        <select name="we_device" class="form-control we_device">
                                            <option value="">---</option>
                                            <?php
                                            // get WE data
                                            $wed_sql = $this->db->query("
                                            SELECT
                                                `water_efficiency_device_id`,
                                                `name`
                                            FROM `water_efficiency_device`
                                            WHERE `active` = 1
                                            ");

                                            foreach( $wed_sql->result_array() as $wed_row ){ ?>
                                                <option value="<?php echo $wed_row['water_efficiency_device_id'] ?>" <?php echo ( $wed_row['water_efficiency_device_id'] == $we_row['device'] )?'selected':null; ?>><?php echo $wed_row['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group form-flex">
                                    <label>Toilet Type / Is water flow less than 9L per minute?</label>
                                    <div class="form-flex-2">
                                        <div class="radio left">
                                            <input id="<?php echo "radio_".$x; ?>" type="radio" class="we_pass we_pass_yes" name="we_pass[<?php echo $x; ?>]" value="1" <?php echo ( $we_row['pass'] == 1 )?'checked':null; ?> />
                                            <label for="<?php echo "radio_".$x; ?>" class="we_pass_lbl_yes"><?php echo $pass_yes; ?></label> &nbsp;&nbsp;
                                        </div>
                                        <div class="radio">
                                            <input id="<?php echo "radio_no".$x; ?>" type="radio" class="we_pass we_pass_no" name="we_pass[<?php echo $x; ?>]" value="0" <?php echo ( $we_row['pass'] == 0 && is_numeric($we_row['pass']) )?'checked':null; ?> />
                                            <label for="<?php echo "radio_no".$x; ?>" class="we_pass_lbl_no"><?php echo $pass_no; ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-flex">
                                    <label>Location</label>
                                    <div class="form-flex-2">
                                        <input type="text" class="form-control we_location" name="we_location" id="we_location" value="<?php echo $we_row['location']; ?>" />
                                    </div>
                                </div>

                                <div class="form-group form-flex">
                                    <label>Note</label>
                                    <div class="form-flex-2">
                                        <input type="text" class="form-control we_note" name="we_note" id="we_note" value="<?php echo $we_row['note']; ?>" />
                                    </div>
                                </div>

                            </div>
                        </section>

                        <div class="form-group text-right">
                            <?php if( $job_editable==1 ){ ?>
                                <input type="hidden" name="we_id" value="<?php echo $we_row['water_efficiency_id']; ?>" />
                                <button type="button" class="btn btn_update_water_efficiency_row">Update</button>
                            <?php } ?>
                        </div>
                    </div>

                    <?php $x++; }  ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>
<!-- WE end -->

<script type="text/javascript">
    function show_alarm_photo(alarm_id, agency_id){
        $('#load-screen').show(); //show loader
        jQuery.ajax({
            type: "POST",
            url: "/jobs/show_alarm_photo",
            data: {
                alarm_id: alarm_id,
                agency_id: agency_id
            }
        }).done(function (res) {
            jQuery('#show_alarm_photo_div_'+alarm_id).html(res);
            jQuery("#load-screen").hide();
        });
    }
    function remove_alarm_photo(alarm_id, agency_id, image_type){
        event.preventDefault();
        if (confirm('Are you sure?')) {
            $('#load-screen').show(); //show loader
            jQuery.ajax({
                type: "POST",
                url: "/jobs/remove_alarm_photo",
                dataType: 'json',
                data: {
                    alarm_id: alarm_id,
                    image_type: image_type
                }
            }).done(function (res) {
                jQuery("#load-screen").hide();

                if(res.status){
                    show_alarm_photo(alarm_id, agency_id);
                }else{
                    swal('Error','Internal error please contact admin','error');
                }
            });
        }
    }
    $(document).ready(function(){

        $('.btn_bulk_update_current_alarms').click(function(){
            var obj = $(this).parents('#current_alarm_bulk_update_form');
            var current_alarm_id = [];
            var current_alarm_position = [];
            var current_alarm_power_id = [];
            var current_alarm_type_id = [];
            var current_ts_required_compliance = [];
            var current_newinstall = [];
            var current_alarms_price = [];
            var current_alarm_reason_id = [];
            var current_ts_is_alarm_ic = [];
            var current_make = [];
            var current_model = [];
            var current_expiry = [];
            var current_ts_db_rating = [];

            var orig_current_alarm_ts_position = [];
            var orig_current_alarm_power_id = [];
            var orig_current_alarm_type_id = [];
            var orig_current_ts_required_compliance = [];
            var orig_current_newinstall = [];
            var orig_current_alarms_price = [];
            var orig_current_alarm_reason_id = [];
            var orig_current_ts_is_alarm_ic = [];
            var orig_current_make = [];
            var orig_current_model = [];
            var orig_current_expiry = [];
            var orig_current_ts_db_rating = [];

            obj.find('.current_alarm_id').each(function(){
                current_alarm_id.push($(this).val());
            })
            obj.find('.current_alarm_ts_position').each(function(){
                current_alarm_position.push($(this).val());
            })
            obj.find('.current_alarm_power_id').each(function(){
                current_alarm_power_id.push($(this).val());
            })
            obj.find('.current_alarm_type_id').each(function(){
                current_alarm_type_id.push($(this).val());
            })
            obj.find('.current_ts_required_compliance').each(function(){
                current_ts_required_compliance.push($(this).val());
            })
            obj.find('.current_newinstall').each(function(){
                current_newinstall.push($(this).val());
            })
            obj.find('.current_alarms_price').each(function(){
                current_alarms_price.push($(this).val());
            })
            obj.find('.current_alarm_reason_id').each(function(){
                current_alarm_reason_id.push($(this).val());
            })
            obj.find('.current_ts_is_alarm_ic').each(function(){
                current_ts_is_alarm_ic.push($(this).val());
            })
            obj.find('.current_make').each(function(){
                current_make.push($(this).val());
            })
            obj.find('.current_model').each(function(){
                current_model.push($(this).val());
            })
            obj.find('.current_expiry').each(function(){
                current_expiry.push($(this).val());
            })
            obj.find('.current_ts_db_rating').each(function(){
                current_ts_db_rating.push($(this).val());
            })

            // Orig values
            obj.find('.orig_current_alarm_ts_position').each(function(){
                orig_current_alarm_ts_position.push($(this).val());
            })
            obj.find('.orig_current_alarm_power_id').each(function(){
                orig_current_alarm_power_id.push($(this).val());
            })
            obj.find('.orig_current_alarm_type_id').each(function(){
                orig_current_alarm_type_id.push($(this).val());
            })
            obj.find('.orig_current_ts_required_compliance').each(function(){
                orig_current_ts_required_compliance.push($(this).val());
            })
            obj.find('.orig_current_newinstall').each(function(){
                orig_current_newinstall.push($(this).val());
            })
            obj.find('.orig_current_alarms_price').each(function(){
                orig_current_alarms_price.push($(this).val());
            })
            obj.find('.orig_current_alarm_reason_id').each(function(){
                orig_current_alarm_reason_id.push($(this).val());
            })
            obj.find('.orig_current_ts_is_alarm_ic').each(function(){
                orig_current_ts_is_alarm_ic.push($(this).val());
            })
            obj.find('.orig_current_make').each(function(){
                orig_current_make.push($(this).val());
            })
            obj.find('.orig_current_model').each(function(){
                orig_current_model.push($(this).val());
            })
            obj.find('.orig_current_expiry').each(function(){
                orig_current_expiry.push($(this).val());
            })
            obj.find('.orig_current_ts_db_rating').each(function(){
                orig_current_ts_db_rating.push($(this).val());
            })

            $('#load-screen').show(); //show loader
            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_vjd_bulk_update_alarm",
                dataType: 'json',
                data: {
                    job_id: <?php echo $job_row['jid'] ?? 0 ?>,
                    current_alarm_id: current_alarm_id,
                    current_alarm_position: current_alarm_position,
                    current_alarm_power_id: current_alarm_power_id,
                    current_alarm_type_id: current_alarm_type_id,
                    current_ts_required_compliance: current_ts_required_compliance,
                    current_newinstall: current_newinstall,
                    current_alarms_price: current_alarms_price,
                    current_alarm_reason_id: current_alarm_reason_id,
                    current_ts_is_alarm_ic: current_ts_is_alarm_ic,
                    current_make: current_make,
                    current_model: current_model,
                    current_expiry: current_expiry,
                    current_ts_db_rating: current_ts_db_rating,
                    orig_current_alarm_ts_position: orig_current_alarm_ts_position,
                    orig_current_alarm_power_id: orig_current_alarm_power_id,
                    orig_current_alarm_type_id: orig_current_alarm_type_id,
                    orig_current_ts_required_compliance: orig_current_ts_required_compliance,
                    orig_current_newinstall: orig_current_newinstall,
                    orig_current_alarms_price: orig_current_alarms_price,
                    orig_current_alarm_reason_id: orig_current_alarm_reason_id,
                    orig_current_ts_is_alarm_ic: orig_current_ts_is_alarm_ic,
                    orig_current_make: orig_current_make,
                    orig_current_model: orig_current_model,
                    orig_current_expiry: orig_current_expiry,
                    orig_current_ts_db_rating: orig_current_ts_db_rating
                }
            }).done(function (res) {
                jQuery("#load-screen").hide();

                if(res.status){
                    swal({
                        title:"Success!",
                        text: "Current Alarms successfully updated",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });
                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }else{
                    swal('Error','Internal error please contact admin','error');
                }
            });


        })


        //Delete Corded Windows
        $('.del_cw').click(function(){

            var cw_id = $(this).attr('data-corded_window_id');

            swal({
                title: "Warning!",
                text: "Are you sure you want to delete this Window?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                    $('#load-screen').show(); //show loader

                    jQuery.ajax({
                        type: "POST",
                        url: "/jobs/ajax_remove_window",
                        dataType: 'json',
                        data: {
                            job_id: <?php echo $job_row['jid'] ?? 0 ?>,
                            cw_id: cw_id
                        }

                    }).done(function( retval ) {
                        $('#load-screen').hide(); //hide loader

                        if(retval.status){

                            swal({
                                title:"Success!",
                                text: "Window Sucessfully Deleted.",
                                type: "success",
                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                timer: <?php echo $this->config->item('timer') ?>
                            });

                            var full_url = window.location.href;
                            setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                        }else{
                            swal('Error','Internal error please contact admin','error');
                        }
                        
                    });
                
                }

            });

        });

        //Update Corded Window
        $('.btn_update_corded_window').on('click',function(e){
            e.preventDefault();

            var node = $(this).parents('.window_detail_fb');

            var corded_window_id = node.find('.vjd_corded_window_id').val();
            var cw_location = node.find($("input[name='cw_location']")).val();
            var cw_number_of_windows = node.find($("input[name='cw_number_of_windows']")).val();

            swal({
                title: "Warning!",
                text: "Update Corded Window?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                    $('#load-screen').show(); //show loader

                    jQuery.ajax({
                        type: "POST",
                        url: "/jobs/ajax_vjd_update_cw",
                        dataType: 'json',
                        data: {
                            job_id: <?php echo $job_row['jid'] ?? 0 ?>,
                            corded_window_id: corded_window_id,
                            cw_location: cw_location,
                            cw_number_of_windows: cw_number_of_windows
                        }

                    }).done(function( retval ) {
                        $('#load-screen').hide(); //hide loader

                        if(retval.status){

                            swal({
                                title:"Success!",
                                text: "Corded Window successfully updated",
                                type: "success",
                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                timer: <?php echo $this->config->item('timer') ?>
                            });

                            var full_url = window.location.href;
                            setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                        }else{
                            swal('Error','Internal error please contact admin','error');
                        }
                        
                    });
                
                }

            });

        });

        $('.btn_update_prop_details_row').on('click',function(e){
            e.preventDefault();

            var survey_numlevels = $("input[name='survey_numlevels']").val();
            var survey_ceiling = $('input[name="survey_ceiling"]:checked').val();
            var survey_ladder = $('input[name="survey_ladder"]:checked').val();
            var ps_number_of_bedrooms = $("input[name='ps_number_of_bedrooms']").val();

            <?php if( $job_row['p_state']=='QLD' ){ ?>
            var qld_new_leg_alarm_num = $("input[name='qld_new_leg_alarm_num']").val();
            <?php } ?>

            //var ts_safety_switch = $('input[name="ts_safety_switch"]:checked').val();
            //var ss_location = $("input[name='ss_location']").val();
            //var ss_quantity = $("input[name='ss_quantity']").val();

            swal({
                title: "Warning!",
                text: "Update details?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                    $('#load-screen').show(); //show loader

                    jQuery.ajax({
                        type: "POST",
                        url: "/jobs/ajax_update_job_detail",
                        dataType: 'json',
                        data: {
                            job_id: <?=$job_row['jid'] ?? 0?>,
                            prop_id: <?=$job_row['prop_id'] ?? 0?>,
                            update_type: 'btn_update_prop_details_row',
                            survey_numlevels: survey_numlevels,
                            survey_ceiling: survey_ceiling,
                            survey_ladder: survey_ladder,
                            ps_number_of_bedrooms: ps_number_of_bedrooms,
                            qld_new_leg_alarm_num: qld_new_leg_alarm_num,
                            //ts_safety_switch: ts_safety_switch,
                            //ss_location: ss_location,
                            //ss_quantity: ss_quantity
                        }

                    }).done(function( retval ) {
                        $('#load-screen').hide(); //hide loader

                        if(retval.status){

                            swal({
                                title:"Success!",
                                text: "Property details successfully updated",
                                type: "success",
                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                timer: <?php echo $this->config->item('timer') ?>
                            });

                            var full_url = window.location.href;
                            setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                        }else{
                            swal('Error','Internal error please contact admin','error');
                        }
                        
                    });
                
                }

            });

        })

        $("#btn_update_ss_survey").click(function(e){
            
            var submitCount = 0;
            swal({
                title: "Warning!",
                text: "Update Switch Property Survey?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if(isConfirm){
                    if(submitCount==0){
                    submitCount++;
                    $('#ss_prop_survey_fb').submit();
                    return false;
                }else{
                    swal('','Form submission is in progress.','error');
                    return false;
                }
                }

            });

        });

        //Remove Alarm JS
        $('.remove_alarm').click(function(){

            var alarm_id = $(this).attr('data-alarmid');

            swal({
                title: "Warning!",
                text: "Are you sure you want to delete this alarm?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                    $('#load-screen').show(); //show loader

                    jQuery.ajax({
                        type: "POST",
                        url: "/jobs/ajax_remove_alarm",
                        dataType: 'json',
                        data: {
                            job_id: <?=$job_row['jid'] ?? 0?>,
                            alarm_id: alarm_id
                        }

                    }).done(function( retval ) {
                        $('#load-screen').hide(); //hide loader

                        if(retval.status){

                            swal({
                                title:"Success!",
                                text: "Alarm Sucessfully Deleted.",
                                type: "success",
                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                timer: <?php echo $this->config->item('timer') ?>
                            });

                            var full_url = window.location.href;
                            setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                        }else{
                            swal('Error','Internal error please contact admin','error');
                        }
                        
                    });
                
                }

            });

        });

        $('.btn_update_current_alarms_row').on('click',function(e){
            e.preventDefault();

            var node = $(this).parents('.alarms_fb');

            var alarm_id = node.find('.vjd_alarm_id').val();
            var ts_position = node.find($("input[name='ts_position']")).val();
            var alarm_power_id = node.find($("select[name='alarm_power_id']")).val();
            var alarm_type_id = node.find($("select[name='alarm_type_id']")).val();
            var rfc = node.find($("select[name='ts_required_compliance']")).val();
            var newinstall = node.find($("select[name='newinstall']")).val();
            var alarm_price = node.find($("input[name='alarm_price']")).val();
            var alarm_reason_id = node.find($("select[name='alarm_reason_id']")).val();
            var make = node.find($("input[name='make']")).val();
            var model = node.find($("input[name='model']")).val();
            var expiry = node.find($("input[name='expiry']")).val();
            var ts_db_rating = node.find($("input[name='ts_db_rating']")).val();
            var ts_is_alarm_ic = node.find($("select[name='ts_is_alarm_ic']")).val();

            $('#load-screen').show(); //show loader

            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_vjd_update_alarm",
                dataType: 'json',
                data: {
                    job_id: <?=$job_row['jid'] ?? 0?>,
                    alarm_id: alarm_id,
                    ts_position: ts_position,
                    alarm_power_id: alarm_power_id,
                    alarm_type_id: alarm_type_id,
                    rfc: rfc,
                    newinstall: newinstall,
                    alarm_price: alarm_price,
                    alarm_reason_id: alarm_reason_id,
                    make: make,
                    model: model,
                    expiry: expiry,
                    ts_db_rating: ts_db_rating,
                    ts_is_alarm_ic: ts_is_alarm_ic
                }

            }).done(function( retval ) {
                $('#load-screen').hide(); //hide loader

                if(retval.status){

                    swal({
                        title:"Success!",
                        text: "Current alarm successfully updated",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });

                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);

                }else{
                    swal('Error','Internal error please contact admin','error');
                }

            });

        });

        $('.btn_update_ss').on('click',function(e){
            e.preventDefault();

            var node = $(this).parents('.ss_details_fb');

            var ss_id = node.find('.vjd_ss_id').val();
            var reason_new = node.find($("select[name='ss_new_update']")).val();
            var ss_reason_update = node.find($("select[name='ss_reason_update']")).val();
            var ss_pole_update = node.find($("select[name='ss_pole_update']")).val();
            var ss_make = node.find($("input[name='ss_make']")).val();
            var ss_model = node.find($("input[name='ss_model']")).val();
            var ss_test = node.find($("select[name='ss_test']")).val();
           
            swal({
                title: "Warning!",
                text: "Update Switch Detail?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                    $('#load-screen').show(); //show loader

                    jQuery.ajax({
                        type: "POST",
                        url: "/jobs/ajax_vjd_update_ss",
                        dataType: 'json',
                        data: {
                            job_id: <?php echo $job_row['jid'] ?? 0 ?>,
                            ss_id: ss_id,
                            reason_new: reason_new,
                            ss_reason_update: ss_reason_update,
                            ss_pole_update: ss_pole_update,
                            ss_make: ss_make,
                            ss_model: ss_model,
                            ss_test: ss_test
                        }

                    }).done(function( retval ) {
                        $('#load-screen').hide(); //hide loader

                        if(retval.status){

                            swal({
                                title:"Success!",
                                text: "Safety Switch successfully updated",
                                type: "success",
                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                timer: <?php echo $this->config->item('timer') ?>
                            });

                            var full_url = window.location.href;
                            setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                        }else{
                            swal('Error','Internal error please contact admin','error');
                        }
                        
                    });
                
                }

            });

        });

        $('.del_ss').click(function(e){

            e.preventDefault();
            var ss_id = $(this).attr('data-ssid');
            var ss_reason = $(this).parents('tr:first').find('.ss_reason_update option:selected').text();

            swal({
                title: "Warning!",
                text: "Are you sure you want to delete Safety Switch?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                    $('#load-screen').show(); //show loader

                    jQuery.ajax({
                        type: "POST",
                        url: "/jobs/ajax_delete_ss",
                        dataType: 'json',
                        data: {
                            job_id: <?php echo $job_row['jid'] ?? 0 ?>,
                            ss_id: ss_id,
                            ss_reason: ss_reason
                        }

                    }).done(function( retval ) {
                        $('#load-screen').hide(); //hide loader

                        if(retval.status){

                            swal({
                                title:"Success!",
                                text: "Safety Switch Sucessfully Deleted.",
                                type: "success",
                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                timer: <?php echo $this->config->item('timer') ?>
                            });

                            var full_url = window.location.href;
                            setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                        }else{
                            swal('Error','Internal error please contact admin','error');
                        }
                        
                    });
                
                }

            });

        });

        $("#btn_update_water_meter_row").click(function(e){
            
            var submitCount = 0;
            swal({
                title: "Warning!",
                text: "Update Water Meter?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if(isConfirm){
                    if(submitCount==0){
                    submitCount++;
                    $('#vjd_update_water_meter').submit();
                    return false;
                }else{
                    swal('','Form submission is in progress.','error');
                    return false;
                }
                }

            });

        });

        jQuery(".we_device").change(function(){

            var node = jQuery(this);
            var parent = node.parents(".we_row_fb_");
            var we_device = node.val();

            if( we_device == 2 ){ // toilet

                parent.find(".we_pass_lbl_yes").html("Dual");
                parent.find(".we_pass_lbl_no").html("Single");

            }else if( we_device == 1 || we_device == 3 ){

                parent.find(".we_pass_lbl_yes").html("Yes");
                parent.find(".we_pass_lbl_no").html("No");

            }else{

                parent.find(".we_pass_lbl_yes").html("Pass");
                parent.find(".we_pass_lbl_no").html("Fail");

            }

        });

        $(".btn_update_water_efficiency_row").click(function(e){

            var node = $(this).parents('.we_row_fb_');

            var we_id = node.find($("input[name='we_id']")).val();
            var we_device = node.find($("select[name='we_device']")).val();
            var we_pass = node.find($(".we_pass:checked")).val();
            var we_location = node.find($("input[name='we_location']")).val();
            var we_note = node.find($("input[name='we_note']")).val();

            swal({
                title: "Warning!",
                text: "Update Water Efficiency?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                    $('#load-screen').show(); //show loader

                    jQuery.ajax({
                        type: "POST",
                        url: "/jobs/ajax_vjd_update_we",
                        dataType: 'json',
                        data: {
                            job_id: <?=$job_row['jid'] ?? 0?>,
                            we_id: we_id,
                            we_device: we_device,
                            we_pass: we_pass,
                            we_location: we_location,
                            we_note: we_note
                        }

                    }).done(function( retval ) {
                        $('#load-screen').hide(); //hide loader

                        if(retval.status){

                            swal({
                                title:"Success!",
                                text: "Water Efficiency successfully updated",
                                type: "success",
                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                timer: <?php echo $this->config->item('timer') ?>
                            });

                            var full_url = window.location.href;
                            setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                        }else{
                            swal('Error','Internal error please contact admin','error');
                        }
                        
                    });
                
                }

            });

        });

        jQuery(".we_del").click(function(e){
            
            e.preventDefault();
            var we_id = $(this).attr('data-we_id');

            swal({
                title: "Warning!",
                text: "Are you sure you want to delete?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                    $('#load-screen').show(); //show loader

                    jQuery.ajax({
                        type: "POST",
                        url: "/jobs/ajax_delete_water_efficiency",
                        dataType: 'json',
                        data: {
                            job_id: <?php echo $job_row['jid'] ?? 0 ?>,
                            we_id: we_id
                        }

                    }).done(function( retval ) {
                        $('#load-screen').hide(); //hide loader

                        if(retval.status){

                            swal({
                                title:"Success!",
                                text: "Water Efficiency Sucessfully Deleted.",
                                type: "success",
                                showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                timer: <?php echo $this->config->item('timer') ?>
                            });

                            var full_url = window.location.href;
                            setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                        }else{
                            swal('Error','Internal error please contact admin','error');
                        }
                        
                    });
                
                }

            });

        });


    })

</script>