<?php
class Tech_run_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
        $this->load->helper('email_helper');
    }

    // old function: assignTechRunPinColors
    public function assign_pin_colours($params)
    {

        $trr_hl_color = ($params['trr_hl_color'] != -1) ? $params['trr_hl_color'] : 'NULL'; // color

        foreach ($params['trr_id_arr'] as $trr_id) {

            if ($params['tr_id'] > 0 && $trr_id > 0) {

                // update
                $this->db->query("
                UPDATE `tech_run_rows`
                SET `highlight_color` = {$trr_hl_color}
                WHERE `tech_run_id` = {$params['tr_id']}
                AND `tech_run_rows_id` = {$trr_id}
                ");
            }
        }
    }

    // old function: techRunUpdateStartEndPoint
    function update_start_and_end($params)
    {

        if ($params['start'] != "" || $params['end'] != "") {

            $country_id = $this->config->item('country');

            // get country data
            $country_params = array(
                'sel_query' => 'c.`country`',
                'country_id' => $country_id
            );
            $country_sql = $this->system_model->get_countries($country_params);
            $country_row = $country_sql->row();
            $country_name = $country_row->country;

            // check lat/lng
            // start point
            if ($params['start'] > 0) {

                // start
                // get accomodation address
                $acc_sql = $this->db->query("
                    SELECT `address`
                    FROM `accomodation`
                    WHERE `accomodation_id` = {$params['start']}
                    AND `lat` IS NULL
                    AND `lng` IS NULL
                ");

                if ($acc_sql->num_rows() > 0) {

                    $acc_row = $acc_sql->result();

                    // get geocode
                    $coor = $this->system_model->getGoogleMapCoordinates("{$acc_row->address}, {$country_name}");

                    // update agency lat/lng
                    $this->db->query("
                        UPDATE `accomodation`
                        SET
                            `lat` = '{$coor['lat']}',
                            `lng` = '{$coor['lng']}'
                        WHERE `accomodation_id` = {$params['start']}
                    ");
                }
            }

            // end point
            if ($params['end'] > 0) {

                // end
                // get accomodation address
                $acc_sql = $this->db->query("
                    SELECT `address`
                    FROM `accomodation`
                    WHERE `accomodation_id` = {$params['end']}
                    AND `lat` IS NULL
                    AND `lng` IS NULL
                ");

                if ($acc_sql->num_rows() > 0) {

                    $acc_row = $acc_sql->result();

                    // get geocode
                    $coor = $this->system_model->getGoogleMapCoordinates("{$acc_row->address}, {$country_name}");

                    // update agency lat/lng
                    $this->db->query("
                        UPDATE `accomodation`
                        SET
                            `lat` = '{$coor['lat']}',
                            `lng` = '{$coor['lng']}'
                        WHERE `accomodation_id` = {$params['end']}
                    ");
                }
            }

            // update start and end point
            if ($params['tr_id'] > 0 && $params['start'] > 0 && $params['end'] > 0) {

                $this->db->query("
                UPDATE `tech_run`
                SET `start` = {$params['start']},
                    `end` = {$params['end']}
                WHERE `tech_run_id` = {$params['tr_id']}
                ");
            }
        }
    }


    // old function: techRunAddAgencyKeys
    function add_agency_keys($params)
    {

        $this->load->model('tech_model');

        // data
        $tr_id = $params['tr_id'];
        $keys_agency = $params['keys_agency'];
        $agency_addresses_id = $params['agency_addresses_id'];
        $tech_id = $params['tech_id'];
        $date = $params['date'];
        $country_id = $this->config->item('country');

        // get country data
        $country_params = array(
            'sel_query' => 'c.`country`',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();
        $country_name = $country_row->country;

        //get tech run rows
        $tr_sel = "COUNT(trr.`tech_run_rows_id`) AS trr_count";
        $tr_params = array(
            'sel_query' => $tr_sel
        );
        $trr_sql = $this->tech_model->getTechRunRows($tr_id, $country_id, $tr_params);
        $count = $trr_sql->row()->trr_count;

        $i = ($count) + 2;

        // type of keys
        $keys_array = array(
            'Pick Up',
            'Drop Off'
        );

        // insert both pick up and drop off keys
        foreach ($keys_array as $key_type) {

            // check agency lat/lng
            $agen_sql = $this->db->query("
                SELECT
                    `address_1`,
                    `address_2`,
                    `address_3`,
                    `state`,
                    `postcode`
                FROM `agency`
                WHERE `agency_id` = {$keys_agency}
                AND `lat` IS NULL
                AND `lng` IS NULL
            ");

            if ($agen_sql->num_rows() > 0) {

                $agen_row = $agen_sql->result();

                // get geocode
                $coor = $this->system_model->getGoogleMapCoordinates("{$agen_row->address_1} {$agen_row->address_2} {$agen_row->address_3} {$agen_row->state} {$agen_row->postcode}, {$country_name}");

                // update agency lat/lng
                $this->db->query("
                    UPDATE `agency`
                    SET
                        `lat` = '{$coor['lat']}',
                        `lng` = '{$coor['lng']}'
                    WHERE `agency_id` = {$keys_agency}
                ");
            }

            // insert keys
            $this->db->query("
                INSERT INTO
                `tech_run_keys`(
                    `assigned_tech`,
                    `date`,
                    `action`,
                    `agency_id`,
                    `sort_order`,
                    `agency_addresses_id`
                )
                VALUES(
                    {$tech_id},
                    '{$date}',
                    '{$key_type}',
                    '{$keys_agency}',
                    {$i},
                    '{$agency_addresses_id}'
                )
            ");
            $key_id = $this->db->insert_id();

            //  insert tech run rows
            $this->db->query("
                INSERT INTO
                `tech_run_rows` (
                    `tech_run_id`,
                    `row_id_type`,
                    `row_id`,
                    `sort_order_num`,
                    `created_date`,
                    `status`
                )
                VALUES (
                    {$tr_id},
                    'keys_id',
                    {$key_id},
                    {$i},
                    '" . date('Y-m-d H:i:s') . "',
                    1
                )
            ");

            $i++;
        }
    }

    // get property keys of agency
    public function get_agency_key_per_job($params)
    {

        // update property key
        $sql = "
            SELECT *
            FROM `agency_keys`
            WHERE `job_id` = {$params['job_id']}
            AND `tech_id` = {$params['tech_id']}
            AND `date` = '{$params['date']}'
            AND `agency_id` = {$params['agency_id']}
        ";

        if ($params['display_query'] == 1) {
            echo $sql;
        }
        return $this->db->query($sql);
    }


    // mark job as not completed
    public function mark_job_not_completed($params)
    {

        $job_id = $params['job_id'];
        $tech_id = $params['tech_id'];

        $job_reason = $params['job_reason'];
        $reason_comment = $params['reason_comment'];

        $country_id = $this->config->item('country');

        if ($job_id > 0) {

            // get jobs data
            $job_sql = $this->db->query("
            SELECT
                j.`assigned_tech`,
                j.`status`,
                j.`door_knock`,
                j.job_reason_id, 
                j.job_reason_comment,

                p.`property_id`,
                p.`address_1` AS p_address_1,
                p.`address_2` AS p_address_2,
                p.`address_3` AS p_address_3,
                p.`state` AS p_state,
                p.`postcode` AS p_postcode
            FROM `jobs` AS j
            LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id`
            WHERE `id` = {$job_id}
            ");
            $job_row = $job_sql->row();

            $property_id = $job_row->property_id;
            $job_status = $job_row->status;
            $p_address = "{$job_row->p_address_1} {$job_row->p_address_2} {$job_row->p_address_3}";

            // get job reason
            $jr_sql = $this->db->query("
                SELECT *
                FROM `job_reason`
                WHERE `job_reason_id` = {$job_reason}
            ");
            $jr_row = $jr_sql->row();
            $reason_name = $jr_row->name;

            // update job
            $job_update_data = array(
                "status"                => "Pre Completion",
                "job_reason_id"         => $job_reason > 0 ? $job_reason : 'NULL',
                "job_reason_comment"    => $reason_comment,
                "completed_timestamp"   => date("Y-m-d H:i:s")
            );
            $this->db->set($job_update_data)->where("id", $job_id)->update("jobs");



            // insert job log
            $log_title = 62; // Job Incomplete
            $log_details = (isset($params["is_from_app"]) ? "<b>APP</b>: " : "") . "This job was marked incompleted due to: {$reason_name}, {$reason_comment}";
            $log_params = array(
                'title' => $log_title,
                'details' => $log_details,
                'display_in_vjd' => 1,
                'created_by_staff' => $tech_id,
                'job_id' => $job_id
            );
            $this->system_model->insert_log($log_params);

            $log_title = 63; // Job Update
            $log_details = (isset($params["is_from_app"]) ? "<b>APP</b>: " : "") . "Job status updated from <b>{$job_status}</b> to <b>Pre Completion</b>";
            $log_params = array(
                'title' => $log_title,
                'details' => $log_details,
                'display_in_vjd' => 1,
                'created_by_staff' => $tech_id,
                'job_id' => $job_id
            );
            $this->system_model->insert_log($log_params);



            $this->db->insert('jobs_not_completed', [
                'job_id'            => $job_id,
                'reason_id'         => $job_reason,
                'reason_comment'    => $reason_comment,
                'tech_id'           => $tech_id,
                'date_created'      => date("Y-m-d H:i:s"),
                'door_knock'        => $job_row->door_knock,
                "image"             => $params["utc_image"] ?? ''
            ]);


            // Refused Entry
            if ($job_reason == 10) {

                $return_as_string =  true;
                $email_body = null;

                // mail
                $view_data['p_address'] = $p_address;
                $view_data['property_id'] = $property_id;

                // content
                $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/refused_entry_email', $view_data, $return_as_string);
                $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

                // subject
                $subject = "Refused Entry";

                // get country data
                $country_params = array(
                    'sel_query' => 'c.agent_number, c.outgoing_email',
                    'country_id' => $country_id
                );
                $country_sql = $this->system_model->get_countries($country_params);
                $country_row = $country_sql->row();

                $to_email = make_email('noshow');

                // email settings
                $this->email->to($to_email);
                $this->email->cc(make_email('reports'));
//                $this->email->bcc(make_email('bcc'));

                $this->email->subject($subject);
                $this->email->message($email_body);

                // send email
                $this->email->send();
            }
        }
    }

    public function get_tech_run_keys_list($params)
    {

        $tech_id = $params['tech_id'];
        $date = $params['date'];
        $agency_id = $params['agency_id'];

        $key_action = $params['key_action'];
        $key_action_no_space = str_replace(' ', '-', strtolower($key_action));

        $country_id = $this->config->item('country');
        $row_count = 0;

?>
        <table class="table main-table keys_table table-bordered">
            <thead>
                <tr>
                    <th class="paddress_th">Address</th>
                    <th class="key_num_th">Key Number</th>
                    <th><?php echo ($key_action == 'Pick Up') ? 'Approved By?' : 'Picked Up'; ?></th>
                    <th style="width: 7%">Verify</th>
                    <th class="keys_picked_up_th"><?php echo ($key_action == 'Pick Up') ? 'Keys Picked Up?' : 'Keys Returned?'; ?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                $job_sql = $this->db->query("
                SELECT
                    j.`id` AS jid,
                    j.`service` AS j_service,
                    j.`key_access_details`,
                    j.`ts_completed`,
                    j.`status` AS jstatus,
                    j.`door_knock`,
                    j.`due_date`,
                    j.`property_vacant`,

                    p.`property_id`,
                    p.`address_1` AS p_address_1,
                    p.`address_2` AS p_address_2,
                    p.`address_3` AS p_address_3,
                    p.`state` AS p_state,
                    p.`postcode` AS p_postcode,
                    p.`key_number`,
                    p.`lat` AS p_lat,
                    p.`lng` AS p_lng,

                    a.`agency_id`,
                    a.`agency_name`,
                    a.`address_1` AS a_address_1,
                    a.`address_2` AS a_address_2,
                    a.`address_3` AS a_address_3,
                    a.`state` AS a_state,
                    a.`postcode` AS a_postcode,
                    a.`phone` AS a_phone,
                    a.`allow_dk`
                FROM jobs AS j
                LEFT JOIN  `property` AS p ON j.`property_id` = p.`property_id`
                LEFT JOIN  `agency` AS a ON p.`agency_id` = a.`agency_id`
                LEFT JOIN `staff_accounts` AS sa ON j.`assigned_tech` = sa.`StaffID`
                WHERE p.`deleted` =0
                AND a.`status` = 'active'
                AND j.`del_job` = 0
                AND a.`country_id` = {$country_id}
                AND j.`key_access_required` = 1
                AND j.`assigned_tech` ={$tech_id}
                AND j.`date` = '{$date}'
                AND a.`agency_id` = {$agency_id}
            ");
                $job_id_arr = [];
                if ($job_sql->num_rows() > 0) {
                    foreach ($job_sql->result() as $index => $job_row) {

                        $bg_color = null;

                        $job_id = $job_row->jid;
                        $p_address = "{$job_row->p_address_1} {$job_row->p_address_2}, {$job_row->p_address_3}";
                        $door_knock = $job_row->door_knock;

                        $agen_key_params = array(
                            'job_id' => $job_id,
                            'tech_id' => $tech_id,
                            'date' => $date,
                            'agency_id' => $agency_id,
                            'display_query' => 0
                        );
                        $agency_key_sql = $this->tech_run_model->get_agency_key_per_job($agen_key_params);
                        if ($agency_key_sql->num_rows()) {

                            $agency_key_row = $agency_key_sql->row();

                            $agency_keys_id = $agency_key_row->agency_keys_id;
                            $is_keys_picked_up = $agency_key_row->is_keys_picked_up;
                            $attend_property = $agency_key_row->attend_property;
                            $job_reason = $agency_key_row->job_reason;
                            $reason_comment = $agency_key_row->reason_comment;
                            $ak_created_date = $agency_key_row->created_date;
                            $is_keys_returned = $agency_key_row->is_keys_returned;
                            $not_returned_notes = $agency_key_row->not_returned_notes;
                        } else {

                            $is_keys_picked_up = null;
                            $job_reason = null;
                            $reason_comment = null;
                            $is_keys_returned = null;
                            $not_returned_notes = null;
                        }


                        if ($job_row->ts_completed == 1) {
                            $bg_color = '#c2ffa7';
                        }

                        // check for not complete reason
                        $jnc_sql = $this->db->query("
                        SELECT COUNT(`jobs_not_completed_id`) AS jnc_count
                        FROM `jobs_not_completed`
                        WHERE `job_id` = {$job_row->jid}
                        AND DATE(`date_created`) = '{$date}'
                    ");
                        $jnc_count = $jnc_sql->row()->jnc_count;

                        if ($jnc_count > 0) {
                            $bg_color = 'orange';
                        }


                ?>
                        <tr class="body_tr jalign_left prop_row" style="background-color:<?php echo $bg_color; ?>">
                            <td class="prop_address">
                                <?php //echo "{$p_address} ({$job_row->jstatus})"; 
                                ?>
                                <?php echo $p_address; ?>
                            </td>
                            <td class="prop_key_num">
                                <input type="text" class="form-control key_number" value="<?php echo $job_row->key_number; ?>" placeholder="Insert Key Number" />
                                <input type="hidden" class="job_id" value="<?php echo $job_row->jid; ?>" />
                            </td>
                            <td>
                                <?php
                                if ($key_action == 'Pick Up') {
                                    echo $job_row->key_access_details;
                                } else {
                                    echo $this->system_model->isDateNotEmpty($ak_created_date) ? date('H:i', strtotime($ak_created_date)) : null;
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (

                                    ($this->system_model->isDateNotEmpty($job_row->due_date) == false ||
                                        ($this->system_model->isDateNotEmpty($job_row->due_date) && $job_row->due_date < date('Y-m-d'))
                                    ) &&
                                    $job_row->property_vacant == 1

                                ) {
                                ?>
                                    <span class="text-danger">Verify vacant</span>
                                <?php
                                }
                                ?>
                            </td>
                            <td class="is_keys_picked_up_td">

                                <?php
                                if ($key_action == 'Pick Up') { ?>

                                    <div class="radio float-left mr-2">
                                        <input type="radio" name="is_keys_picked_up<?php echo $row_count . "-" . $key_action_no_space; ?>" id="is_keys_picked_up_yes<?php echo $row_count . "-" . $key_action_no_space; ?>" class="is_keys_picked_up is_keys_picked_up_yes inline-block" value="1" <?php echo ($is_keys_picked_up == 1) ? 'checked' : null; ?> />
                                        <label class="inline-block" for="is_keys_picked_up_yes<?php echo $row_count . "-" . $key_action_no_space; ?>">Yes</label>
                                    </div>

                                    <div class="radio float-left mr-2">
                                        <input type="radio" name="is_keys_picked_up<?php echo $row_count . "-" . $key_action_no_space; ?>" id="is_keys_picked_up_no<?php echo $row_count . "-" . $key_action_no_space; ?>" class="is_keys_picked_up is_keys_picked_up_no inline-block" value="0" <?php echo ($is_keys_picked_up == 0 && is_numeric($is_keys_picked_up)) ? 'checked' : null; ?> />
                                        <label class="inline-block" for="is_keys_picked_up_no<?php echo $row_count . "-" . $key_action_no_space; ?>">No</label>
                                    </div>

                                    <div class="radio float-left">
                                        <input type="radio" name="is_keys_picked_up<?php echo $row_count . "-" . $key_action_no_space; ?>" id="is_keys_picked_up_other_office<?php echo $row_count . "-" . $key_action_no_space; ?>" class="is_keys_picked_up is_keys_picked_up_other_office inline-block" value="2" <?php echo ($is_keys_picked_up == 2) ? 'checked' : null; ?> />
                                        <label class="inline-block" for="is_keys_picked_up_other_office<?php echo $row_count . "-" . $key_action_no_space; ?>">Other Office</label>
                                    </div>


                                    <div class="job_reason_div" style="display:<?php echo ($is_keys_picked_up == 0 && is_numeric($is_keys_picked_up)) ? 'block' : 'none'; ?>;">


                                        <select id="attend_property" class="form-control attend_property">
                                            <option value="" disabled selected hidden>Attend Property?</option>
                                            <option value="1" <?php echo ($attend_property == 1) ? 'selected' : null; ?>>Yes</option>
                                            <option value="0" <?php echo (is_numeric($attend_property) && $attend_property == 0) ? 'selected' : null; ?>>No</option>
                                        </select>

                                        <div class="not_completed_div" style="display:<?php echo ($attend_property == 0 && is_numeric($attend_property)) ? 'block' : 'none'; ?>;">
                                            <?php
                                            // get not completed reason
                                            if ($door_knock != 1) {  // Do not show NTTC on non-DK jobs            

                                                $ncr_sql_str = "
                                                SELECT `job_reason_id`, `name`
                                                FROM `job_reason`
                                                WHERE `job_reason_id` != 14
                                                ORDER BY `name`
                                            ";
                                            } else { // show ALL   

                                                $ncr_sql_str = "
                                                SELECT `job_reason_id`, `name`
                                                FROM `job_reason`
                                                ORDER BY `name`
                                            ";
                                            }
                                            // job not completed reason
                                            $jr_sql = $this->db->query($ncr_sql_str);
                                            ?>
                                            <select id="job_reason" class="form-control job_reason">
                                                <option value="">----</option>
                                                <?php
                                                foreach ($jr_sql->result() as $jr) {
                                                ?>
                                                    <option value="<?php echo $jr->job_reason_id; ?>" <?php echo ($jr->job_reason_id == $job_reason) ? 'selected' : null ?>><?php echo $jr->name; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>

                                            <!-- comment -->
                                            <div><input type="text" name="reason_comment" class="form-control reason_comment" placeholder="Comment" value="<?php echo ($reason_comment != '') ? $reason_comment : null ?>" /></div>
                                        </div>

                                    </div>

                                    <?php
                                } else { // drop off 

                                    if ($is_keys_picked_up == true) {
                                    ?>

                                        <div class="radio float-left mr-2">
                                            <input type="radio" name="is_keys_returned<?php echo $row_count . "-" . $key_action_no_space; ?>" id="is_keys_returned_yes<?php echo $row_count . "-" . $key_action_no_space; ?>" class="is_keys_returned is_keys_returned_yes inline-block" value="1" <?php echo ($is_keys_returned == 1) ? 'checked' : null; ?> />
                                            <label class="inline-block" for="is_keys_returned_yes<?php echo $row_count . "-" . $key_action_no_space; ?>">Yes</label>
                                        </div>

                                        <div class="radio float-left">
                                            <input type="radio" name="is_keys_returned<?php echo $row_count . "-" . $key_action_no_space; ?>" id="is_keys_returned_no<?php echo $row_count . "-" . $key_action_no_space; ?>" class="is_keys_returned is_keys_returned_no inline-block" value="0" <?php echo ($is_keys_returned == 0 && is_numeric($is_keys_returned)) ? 'checked' : null; ?> />
                                            <label class="inline-block" for="is_keys_returned_no<?php echo $row_count . "-" . $key_action_no_space; ?>">Other</label>
                                        </div>


                                        <div class="keys_not_returned_div" style="display:<?php echo ($is_keys_returned == 0 && is_numeric($is_keys_returned)) ? 'block' : 'none'; ?>;">

                                            <!-- key not returned note -->
                                            <div><input type="text" name="not_returned_notes" class="form-control not_returned_notes" placeholder="Comment" value="<?php echo ($not_returned_notes != '') ? $not_returned_notes : null ?>" /></div>

                                        </div>

                                    <?php
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>

                                    <input type="hidden" class="is_keys_picked_up" value="<?php echo $is_keys_picked_up; ?>" />

                                <?php
                                }
                                ?>

                                <input type="hidden" class="agency_keys_id" value="<?php echo $agency_keys_id; ?>" />
                            </td>
                        </tr>
                    <?php

                        $job_id_arr[] = $job_id;

                        $row_count++;
                    }
                } else { ?>
                    <tr>
                        <td colspan='4'>No Data</td>
                    </tr>
                <?php
                }
                ?>


                <?php
                // get rebooked jobs
                $exclude_jobs_above = null;
                if (count($job_id_arr) > 0) {

                    $job_id_imp = implode(",", $job_id_arr);
                    $exclude_jobs_above = "AND ak.job_id NOT IN({$job_id_imp})";
                }

                $job_sql_str = "
                SELECT
                    ak.`is_keys_picked_up`,
                    ak.`attend_property`,
                    ak.`job_reason` AS ak_job_reason,
                    ak.`reason_comment` AS ak_reason_comment,
                    ak.`created_date` AS ak_created_date,

                    j.`id` AS jid,
                    j.`service` AS j_service,
                    j.`key_access_details`,
                    j.`ts_completed`,
                    j.`status` AS jstatus,
                    j.`door_knock`,
                    j.`due_date`,
                    j.`property_vacant`,

                    p.`property_id`,
                    p.`address_1` AS p_address_1,
                    p.`address_2` AS p_address_2,
                    p.`address_3` AS p_address_3,
                    p.`state` AS p_state,
                    p.`postcode` AS p_postcode,
                    p.`key_number`,
                    p.`lat` AS p_lat,
                    p.`lng` AS p_lng,

                    a.`agency_id`,
                    a.`agency_name`,
                    a.`address_1` AS a_address_1,
                    a.`address_2` AS a_address_2,
                    a.`address_3` AS a_address_3,
                    a.`state` AS a_state,
                    a.`postcode` AS a_postcode,
                    a.`phone` AS a_phone,
                    a.`allow_dk`
                FROM `agency_keys` AS ak
                LEFT JOIN jobs AS j ON ak.`job_id` = j.`id`
                LEFT JOIN  `property` AS p ON j.`property_id` = p.`property_id`
                LEFT JOIN  `agency` AS a ON p.`agency_id` = a.`agency_id`
                LEFT JOIN `staff_accounts` AS sa ON j.`assigned_tech` = sa.`StaffID`
                WHERE ak.`tech_id` ={$tech_id}
                AND ak.`date` = '{$date}'
                AND ak.`agency_id` = {$agency_id}
                {$exclude_jobs_above}
            ";
                $job_sql = $this->db->query($job_sql_str);

                if ($job_sql->num_rows() > 0) {
                    foreach ($job_sql->result() as $index => $job_row) {

                        $bg_color = null;

                        $job_id = $job_row->jid;
                        $p_address = "{$job_row->p_address_1} {$job_row->p_address_2}, {$job_row->p_address_3}";
                        $door_knock = $job_row->door_knock;

                        $agency_keys_id = $job_row->agency_keys_id;
                        $is_keys_picked_up = $job_row->is_keys_picked_up;
                        $attend_property = $job_row->attend_property;
                        $job_reason = $job_row->ak_job_reason;
                        $reason_comment = $job_row->ak_reason_comment;
                        $ak_created_date = $job_row->ak_created_date;
                        $is_keys_returned = $job_row->is_keys_returned;
                        $not_returned_notes = $job_row->not_returned_notes;


                        if ($job_row->ts_completed == 1) {
                            $bg_color = '#c2ffa7';
                        }

                        // check for not complete reason
                        $jnc_sql = $this->db->query("
                        SELECT COUNT(`jobs_not_completed_id`) AS jnc_count
                        FROM `jobs_not_completed`
                        WHERE `job_id` = {$job_row->jid}
                        AND DATE(`date_created`) = '{$date}'
                    ");
                        $jnc_count = $jnc_sql->row()->jnc_count;

                        if ($jnc_count > 0) {
                            $bg_color = 'orange';
                        }


                ?>
                        <tr class="body_tr jalign_left prop_row" style="background-color:<?php echo $bg_color; ?>">
                            <td class="prop_address">
                                <?php //echo "{$p_address} ({$job_row->jstatus})"; 
                                ?>
                                <?php echo $p_address; ?>
                            </td>
                            <td class="prop_key_num">
                                <input type="text" class="form-control key_number" value="<?php echo $job_row->key_number; ?>" />
                                <input type="hidden" class="job_id" value="<?php echo $job_row->jid; ?>" />
                            </td>
                            <td>
                                <?php
                                if ($key_action == 'Pick Up') {
                                    echo $job_row->key_access_details;
                                } else {
                                    echo $this->system_model->isDateNotEmpty($ak_created_date) ? date('H:i', strtotime($ak_created_date)) : null;
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (

                                    ($this->system_model->isDateNotEmpty($job_row->due_date) == false ||
                                        ($this->system_model->isDateNotEmpty($job_row->due_date) && $job_row->due_date < date('Y-m-d'))
                                    ) &&
                                    $job_row->property_vacant == 1

                                ) {
                                ?>
                                    <span class="text-danger">Verify vacant</span>
                                <?php
                                }
                                ?>
                            </td>
                            <td class="is_keys_picked_up_td">

                                <?php
                                if ($key_action == 'Pick Up') { ?>

                                    <div class="radio float-left mr-2">
                                        <input type="radio" name="is_keys_picked_up<?php echo $row_count . "-" . $key_action_no_space; ?>" id="is_keys_picked_up_yes<?php echo $row_count . "-" . $key_action_no_space; ?>" class="is_keys_picked_up is_keys_picked_up_yes inline-block" value="1" <?php echo ($is_keys_picked_up == 1) ? 'checked' : null; ?> />
                                        <label class="inline-block" for="is_keys_picked_up_yes<?php echo $row_count . "-" . $key_action_no_space; ?>">Yes</label>
                                    </div>

                                    <div class="radio float-left mr-2">
                                        <input type="radio" name="is_keys_picked_up<?php echo $row_count . "-" . $key_action_no_space; ?>" id="is_keys_picked_up_no<?php echo $row_count . "-" . $key_action_no_space; ?>" class="is_keys_picked_up is_keys_picked_up_no inline-block" value="0" <?php echo ($is_keys_picked_up == 0 && is_numeric($is_keys_picked_up)) ? 'checked' : null; ?> />
                                        <label class="inline-block" for="is_keys_picked_up_no<?php echo $row_count . "-" . $key_action_no_space; ?>">No</label>
                                    </div>

                                    <div class="radio float-left">
                                        <input type="radio" name="is_keys_picked_up<?php echo $row_count . "-" . $key_action_no_space; ?>" id="is_keys_picked_up_other_office<?php echo $row_count . "-" . $key_action_no_space; ?>" class="is_keys_picked_up is_keys_picked_up_other_office inline-block" value="2" <?php echo ($is_keys_picked_up == 2) ? 'checked' : null; ?> />
                                        <label class="inline-block" for="is_keys_picked_up_other_office<?php echo $row_count . "-" . $key_action_no_space; ?>">Other Office</label>
                                    </div>


                                    <div class="job_reason_div" style="display:<?php echo ($is_keys_picked_up == 0 && is_numeric($is_keys_picked_up)) ? 'block' : 'none'; ?>;">


                                        <select id="attend_property" class="form-control attend_property">
                                            <option value="" disabled selected hidden>Attend Property?</option>
                                            <option value="1" <?php echo ($attend_property == 1) ? 'selected' : null; ?>>Yes</option>
                                            <option value="0" <?php echo (is_numeric($attend_property) && $attend_property == 0) ? 'selected' : null; ?>>No</option>
                                        </select>

                                        <div class="not_completed_div" style="display:<?php echo ($attend_property == 0 && is_numeric($attend_property)) ? 'block' : 'none'; ?>;">
                                            <?php
                                            // get not completed reason
                                            if ($door_knock != 1) {  // Do not show NTTC on non-DK jobs            

                                                $ncr_sql_str = "
                                                SELECT `job_reason_id`, `name`
                                                FROM `job_reason`
                                                WHERE `job_reason_id` != 14
                                                ORDER BY `name`
                                            ";
                                            } else { // show ALL   

                                                $ncr_sql_str = "
                                                SELECT `job_reason_id`, `name`
                                                FROM `job_reason`
                                                ORDER BY `name`
                                            ";
                                            }
                                            // job not completed reason
                                            $jr_sql = $this->db->query($ncr_sql_str);
                                            ?>
                                            <select id="job_reason" class="form-control job_reason">
                                                <option value="">----</option>
                                                <?php
                                                foreach ($jr_sql->result() as $jr) {
                                                ?>
                                                    <option value="<?php echo $jr->job_reason_id; ?>" <?php echo ($jr->job_reason_id == $job_reason) ? 'selected' : null ?>><?php echo $jr->name; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>

                                            <!-- comment -->
                                            <div><input type="text" name="reason_comment" class="form-control reason_comment" placeholder="Comment" value="<?php echo ($reason_comment != '') ? $reason_comment : null ?>" /></div>
                                        </div>

                                    </div>

                                    <?php
                                } else { // drop off 

                                    if ($is_keys_picked_up == true) {

                                    ?>

                                        <div class="radio float-left mr-2">
                                            <input type="radio" name="is_keys_returned<?php echo $row_count . "-" . $key_action_no_space; ?>" id="is_keys_returned_yes<?php echo $row_count . "-" . $key_action_no_space; ?>" class="is_keys_returned is_keys_returned_yes inline-block" value="1" <?php echo ($is_keys_returned == 1) ? 'checked' : null; ?> />
                                            <label class="inline-block" for="is_keys_returned_yes<?php echo $row_count . "-" . $key_action_no_space; ?>">Yes</label>
                                        </div>

                                        <div class="radio float-left">
                                            <input type="radio" name="is_keys_returned<?php echo $row_count . "-" . $key_action_no_space; ?>" id="is_keys_returned_no<?php echo $row_count . "-" . $key_action_no_space; ?>" class="is_keys_returned is_keys_returned_no inline-block" value="0" <?php echo ($is_keys_returned == 0 && is_numeric($is_keys_returned)) ? 'checked' : null; ?> />
                                            <label class="inline-block" for="is_keys_returned_no<?php echo $row_count . "-" . $key_action_no_space; ?>">Other</label>
                                        </div>


                                        <div class="keys_not_returned_div" style="display:<?php echo ($is_keys_returned == 0 && is_numeric($is_keys_returned)) ? 'block' : 'none'; ?>;">

                                            <!-- key not returned note -->
                                            <div><input type="text" name="not_returned_notes" class="form-control not_returned_notes" placeholder="Comment" value="<?php echo ($not_returned_notes != '') ? $not_returned_notes : null ?>" /></div>

                                        </div>

                                    <?php
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>

                                    <input type="hidden" class="is_keys_picked_up" value="<?php echo $is_keys_picked_up; ?>" />

                                <?php
                                }
                                ?>

                                <input type="hidden" class="agency_keys_id" value="<?php echo $agency_keys_id; ?>" />
                            </td>
                        </tr>
                <?php
                        $row_count++;
                    }
                }
                ?>
            </tbody>
        </table>

<?php
    }

    public function getJobsNotCompleted($jobIds, $date = null)
    {

        $jobIdsString = implode(',', $jobIds);

        $dateFilter = "";
        if ($date != null) {
            $dateFilter = "
                AND DATE(`date_created`) = '{$date}'
            ";
        }

        $sql = "
            SELECT `job_id`, COUNT(`jobs_not_completed_id`) AS jnc_count
            FROM `jobs_not_completed`
            WHERE `job_id` IN ({$jobIdsString})
            {$dateFilter}
            GROUP BY `job_id`
        ";

        $jobsNotCompletedResult = $this->db->query($sql);

        return $jobsNotCompletedResult->result();
    }

    public function getTechRunKeyList($params)
    {
        $techId = $params['tech_id'];
        $date = $params['date'];
        $agencyId = $params['agency_id'];

        $keyAction = $params['key_action'];
        $keyActionNoSpace = str_replace(' ', '-', strtolower($keyAction));

        $countryId = $this->config->item('country');
        $rowCount = 0;

        $jobsResult = $this->db->query("
            SELECT
                j.`id` AS jid,
                j.`service` AS j_service,
                j.`key_access_details`,
                j.`ts_completed`,
                j.`status` AS jstatus,
                j.`door_knock`,
                j.`due_date`,
                j.`property_vacant`,

                p.`property_id`,
                p.`address_1` AS p_address_1,
                p.`address_2` AS p_address_2,
                p.`address_3` AS p_address_3,
                p.`state` AS p_state,
                p.`postcode` AS p_postcode,
                p.`key_number`,
                p.`lat` AS p_lat,
                p.`lng` AS p_lng,

                a.`agency_id`,
                a.`agency_name`,
                a.`address_1` AS a_address_1,
                a.`address_2` AS a_address_2,
                a.`address_3` AS a_address_3,
                a.`state` AS a_state,
                a.`postcode` AS a_postcode,
                a.`phone` AS a_phone,
                a.`allow_dk`
            FROM jobs AS j
            LEFT JOIN  `property` AS p ON j.`property_id` = p.`property_id`
            LEFT JOIN  `agency` AS a ON p.`agency_id` = a.`agency_id`
            LEFT JOIN `staff_accounts` AS sa ON j.`assigned_tech` = sa.`StaffID`
            WHERE p.`deleted` =0
            AND a.`status` = 'active'
            AND j.`del_job` = 0
            AND a.`country_id` = {$countryId}
            AND j.`key_access_required` = 1
            AND j.`assigned_tech` = {$techId}
            AND j.`date` = '{$date}'
            AND a.`agency_id` = {$agencyId}
        ");


        $jobsAssoc = [];

        if ($jobsResult->num_rows() > 0) {

            $jobs = $jobsResult->result_array();
            for ($index = 0; $index < count($jobs); $index++) {
                $bgColor = null;

                $jobId = $jobs[$index]['jid'];

                $jobs[$index]['agency_key'] = [
                    'agency_keys_id' => null,
                    'is_keys_picked_up' => null,
                    'attend_property' => null,
                    'job_reason' => null,
                    'reason_comment' => null,
                    'created_date' => null,
                    'is_keys_returned' => null,
                    'not_returned_notes' => null,
                ];
                $jobs[$index]['jnc_count'] = 0;

                $jobsAssoc[$jobId] = &$jobs[$index];
            }

            $jobIds = array_keys($jobsAssoc);
            $jobIdsString = implode(',', $jobIds);

            $sql = "
                SELECT *
                FROM `agency_keys`
                WHERE
                `job_id` IN ({$jobIdsString})
                AND `tech_id` = {$params['tech_id']}
                AND `date` = '{$params['date']}'
                AND `agency_id` = {$params['agency_id']}
                GROUP BY `job_id`
            ";

            $agencyKeysResult = $this->db->query($sql);

            foreach ($agencyKeysResult->result() as $agencyKey) {
                $jobsAssoc[$agencyKey->job_id]['agency_key'] = [
                    'agency_keys_id' => $agencyKey->agency_keys_id,
                    'is_keys_picked_up' => $agencyKey->is_keys_picked_up,
                    'attend_property' => $agencyKey->attend_property,
                    'job_reason' => $agencyKey->job_reason,
                    'reason_comment' => $agencyKey->reason_comment,
                    'created_date' => $agencyKey->created_date,
                    'is_keys_returned' => $agencyKey->is_keys_returned,
                    'not_returned_notes' => $agencyKey->not_returned_notes,
                ];
            }

            $jobsNotCompleted = $this->getJobsNotCompleted($jobIds, $date);

            foreach ($jobsNotCompleted as $notCompletedJob) {
                $jobsAssoc[$notCompletedJob->job_id]['jnc_count'] = $notCompletedJob->jnc_count;
            }
        }

        if (!empty($jobsAssoc)) {
            $jobIdsString = implode(',', array_keys($jobsAssoc));

            $excludedJobsClause = "AND ak.job_id NOT IN ({$jobIdsString})";
        } else {
            $excludedJobsClause = "";
        }

        $otherJobsResult = $this->db->query("
            SELECT
                ak.`is_keys_picked_up`,
                ak.`attend_property`,
                ak.`job_reason` AS ak_job_reason,
                ak.`reason_comment` AS ak_reason_comment,
                ak.`created_date` AS ak_created_date,

                j.`id` AS jid,
                j.`service` AS j_service,
                j.`key_access_details`,
                j.`ts_completed`,
                j.`status` AS jstatus,
                j.`door_knock`,
                j.`due_date`,
                j.`property_vacant`,

                p.`property_id`,
                p.`address_1` AS p_address_1,
                p.`address_2` AS p_address_2,
                p.`address_3` AS p_address_3,
                p.`state` AS p_state,
                p.`postcode` AS p_postcode,
                p.`key_number`,
                p.`lat` AS p_lat,
                p.`lng` AS p_lng,

                a.`agency_id`,
                a.`agency_name`,
                a.`address_1` AS a_address_1,
                a.`address_2` AS a_address_2,
                a.`address_3` AS a_address_3,
                a.`state` AS a_state,
                a.`postcode` AS a_postcode,
                a.`phone` AS a_phone,
                a.`allow_dk`
            FROM `agency_keys` AS ak
            LEFT JOIN jobs AS j ON ak.`job_id` = j.`id`
            LEFT JOIN  `property` AS p ON j.`property_id` = p.`property_id`
            LEFT JOIN  `agency` AS a ON p.`agency_id` = a.`agency_id`
            LEFT JOIN `staff_accounts` AS sa ON j.`assigned_tech` = sa.`StaffID`
            WHERE ak.`tech_id` ={$techId}
            AND ak.`date` = '{$date}'
            AND ak.`agency_id` = {$agencyId}
            {$excludedJobsClause}
        ");

        $otherJobsAssoc = [];
        $jobIds = [];
        if ($otherJobsResult->num_rows() > 0) {
            $otherJobs = $otherJobsResult->result_array();
            for ($index = 0; $index < count($otherJobs); $index++) {

                $jobIds[] = $jobId = $otherJobs[$index]['jid'];
                $otherJobs[$index]['agency_key'] = [
                    'agency_keys_id' => null,
                    'is_keys_picked_up' => null,
                    'attend_property' => null,
                    'job_reason' => null,
                    'reason_comment' => null,
                    'created_date' => null,
                    'is_keys_returned' => null,
                    'not_returned_notes' => null,
                ];
                $otherJobs[$index]['jnc_count'] = 0;

                $otherJobsAssoc[$jobId] = &$otherJobs[$index];
            }


            $jobIdsString = implode(',', $jobIds);

            $sql = "
                SELECT *
                FROM `agency_keys`
                WHERE
                `job_id` IN ({$jobIdsString})
                AND `tech_id` = {$params['tech_id']}
                AND `date` = '{$params['date']}'
                AND `agency_id` = {$params['agency_id']}
                GROUP BY `job_id`
            ";

            $agencyKeysResult = $this->db->query($sql);

            foreach ($agencyKeysResult->result() as $agencyKey) {
                $otherJobsAssoc[$agencyKey->job_id]['agency_key'] = [
                    'agency_keys_id' => $agencyKey->agency_keys_id,
                    'is_keys_picked_up' => $agencyKey->is_keys_picked_up,
                    'attend_property' => $agencyKey->attend_property,
                    'job_reason' => $agencyKey->job_reason,
                    'reason_comment' => $agencyKey->reason_comment,
                    'created_date' => $agencyKey->created_date,
                    'is_keys_returned' => $agencyKey->is_keys_returned,
                    'not_returned_notes' => $agencyKey->not_returned_notes,
                ];
            }


            $jobsNotCompleted = $this->getJobsNotCompleted($jobIds, $date);

            foreach ($jobsNotCompleted as $notCompletedJob) {
                $otherJobsAssoc[$notCompletedJob->job_id]['jnc_count'] = $notCompletedJob->jnc_count;
            }
        }

        return [
            'jobs' => array_values($jobsAssoc),
            'other_jobs' => array_values($otherJobsAssoc),
        ];
    }


    public function issue_en($tr_params)
    {

        $this->load->model('properties_model');
        $this->load->model('sms_model');
        $this->load->model('jobs_model');
        $this->load->model('/inc/pdf_template');

        // parameter data
        $trr_id_arr = $tr_params->trr_id_arr;
        $str_tech = $tr_params->str_tech;
        $str_tech_name = $tr_params->str_tech_name;
        $str_date = $tr_params->str_date;
        $en_time_arr = $tr_params->en_time_arr;

        $today_full = date('Y-m-d H:i:s');

        $logged_user = $this->session->staff_id;
        $booked_with = 'Agent';

        $en_date = $str_date;
        $country_id = $this->config->item('country');

        // get country data
        $country_params = array(
            'sel_query' => 'c.agent_number, c.outgoing_email',
            'country_id' => $country_id
        );
        $country_sql = $this->system_model->get_countries($country_params);
        $country_row = $country_sql->row();

        $view_data['agency_portal_link'] = $this->config->item('agency_link');
        $view_data['outgoing_email'] = $country_row->outgoing_email;
        $view_data['agent_number'] = $country_row->agent_number;


        foreach ($trr_id_arr as $index => $trr_id) {

            $combined_logs_arr = []; // clear

            // clear          
            $email_body = null;
            $sms_sent = false;

            $en_time = $en_time_arr[$index];

            // get jobs data
            $trr_sql = $this->db->query("
            SELECT 
                j.`id` AS jid,
                j.`service` AS jservice, 
                j.`job_type`,
                j.`date` AS jdate, 
                
                p.`property_id`,
                p.`address_1` AS p_address_1, 
                p.`address_2` AS p_address_2, 
                p.`address_3` AS p_address_3, 
                p.`state` AS p_state, 
                p.`postcode` AS p_postcode, 
                p.pm_id_new,
                
                a.`agency_id`,
                a.`agency_name`,
                a.`agency_emails`,
                a.`en_to_pm`,
                a.`send_en_to_agency`
            FROM `tech_run_rows` AS trr
            LEFT JOIN `tech_run` AS tr ON trr.`tech_run_id` =  tr.`tech_run_id`
            LEFT JOIN `jobs` AS j ON ( trr.`row_id` = j.`id` AND trr.`row_id_type` = 'job_id' )  
            LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id` 
            LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id` 
            WHERE trr.`tech_run_rows_id` = {$trr_id}
            ");

            $trr_row = $trr_sql->row();

            // query data
            $job_id = $trr_row->jid;
            $property_id = $trr_row->property_id;
            $agency_id = $trr_row->agency_id;
            $p_address = "{$trr_row->p_address_1} {$trr_row->p_address_2} {$trr_row->p_address_3}, {$trr_row->p_state} {$trr_row->p_postcode}";
            $agency_name = $trr_row->agency_name;
            $agency_emails = $trr_row->agency_emails;
            $pm_id_new = $trr_row->pm_id_new;
            $en_to_pm = $trr_row->en_to_pm;
            $send_en_to_agency = $trr_row->send_en_to_agency;

            // get tenants 
            $sel_query = "
                pt.`property_tenant_id`,
                pt.`tenant_firstname`,
                pt.`tenant_lastname`,
                pt.`tenant_mobile`,
                pt.`tenant_email`
            ";
            $params = array(
                'sel_query' => $sel_query,
                'property_id' => $property_id,
                'pt_active' => 1,
                'display_query' => 0
            );
            $pt_sql = $this->properties_model->get_property_tenants($params);

            // clear tenants
            $tenant_mobile_arr = [];
            $tenant_names_arr = [];
            $tenant_email_arr = [];

            foreach ($pt_sql->result() as $pt_row) {

                // tenant names
                if ($pt_row->tenant_firstname != '') {

                    $tenant_names_arr[] = $pt_row->tenant_firstname;
                }

                // mobile
                if ($pt_row->tenant_mobile != '') {

                    $tenant_mobile_arr[] = $this->sms_model->formatToInternationNumber($pt_row->tenant_mobile); // format number  

                }

                // email
                if ($pt_row->tenant_email != '') {

                    if (filter_var(trim($pt_row->tenant_email), FILTER_VALIDATE_EMAIL)) { // validate email
                        $tenant_email_arr[] = $pt_row->tenant_email;
                    }
                }
            }

            // get PM
            $pm_email = null;
            if ($pm_id_new > 0) {

                $pm_sql = $this->db->query("
                    SELECT `email`
                    FROM `agency_user_accounts`
                    WHERE `agency_user_account_id` = {$pm_id_new}    
                    AND `agency_id` = {$agency_id}    
                ");
                if ($pm_sql->num_rows() > 0) {

                    // sanitize email            
                    $pm_row = $pm_sql->row();
                    if (filter_var(trim($pm_row->email), FILTER_VALIDATE_EMAIL)) {
                        $pm_email = $pm_row->email;
                    }
                }
            }

            // agency email
            $agency_emails_arr = []; // clear
            $agency_emails_imp = null;

            $agency_emails_imp = explode("\n", trim($agency_emails));
            foreach ($agency_emails_imp as $agency_email) {
                if (filter_var(trim($agency_email), FILTER_VALIDATE_EMAIL)) {
                    $agency_emails_arr[] = $agency_email;
                }
            }

            $en_bcc_emails = []; // clear
            if ($en_to_pm == 1) { // send to PM - YEs

                // PM exist, only send to PM
                if ($pm_email != '') {

                    $en_bcc_emails[] = $pm_email;
                } else { // PM doesnt exist, send to agency

                    if (count($agency_emails_arr) > 0) {
                        $en_bcc_emails = $agency_emails_arr;
                    }
                }
            } else { // send to PM - NO

                if ($send_en_to_agency == 1) {
                    if (count($agency_emails_arr) > 0) {
                        $en_bcc_emails = $agency_emails_arr;
                    }
                }
            }

            // if "TO" email is empty move all BCC email to it, bec this email system will error if TO: is empty
            if (count($tenant_email_arr) == 0) {

                $tenant_email_arr = $en_bcc_emails; // TO email

                $en_bcc_emails = []; // clear other BCC email bec, it was already on "TO" email
                $en_bcc_emails[] = make_email('cc');
            } else {
                $en_bcc_emails[] = make_email('cc');
            }

            $proceed_en_operation = true; // defaul to run EN
            if ($country_id == 2 && count($tenant_email_arr) == 0) { // on NZ dont run EN if no tenant emails
                $proceed_en_operation = false;
            }

            if ($proceed_en_operation == true) {

                // update job, this update needs to happen before sending those EN pdf's
                $update_data = array(
                    'assigned_tech' => $str_tech,
                    'date' => $en_date,
                    'time_of_day' => $en_time,
                    'job_entry_notice' => 1,
                    'key_access_required' => 1,
                    'key_access_details' => 'Entry Notice',
                    'tech_notes' => 'EN - Keys',
                    'booked_by' => $logged_user,
                    'booked_with' => $booked_with,
                    'en_date_issued' => $today_full
                );
                $this->db->where('id', $job_id);
                $this->db->update('jobs', $update_data);

                // get job details after the update
                $sel_query = " 
                j.`id` AS jid,
                j.`job_type`,
                j.`date` AS jdate 
                ";
                $job_params = array(
                    'sel_query' => $sel_query,

                    'p_deleted' => 0,
                    'a_status' => 'active',
                    'del_job' => 0,
                    'country_id' => $country_id,
                    'job_id' => $job_id,

                    'join_table' => array('job_type', 'alarm_job_type'),
                );
                $job_sql = $this->jobs_model->get_jobs($job_params);
                $job_row = $job_sql->row();

                $job_date_dmy = date('d/m/Y', strtotime($job_row->jdate));
                $job_type = $job_row->job_type;

                $this->email->clear(TRUE);
                $this->email->to($tenant_email_arr);
                if (count($en_bcc_emails) > 0) {
                    $this->email->bcc($en_bcc_emails);
                }
                $this->email->subject("Entry Notice - {$p_address}");

                // append tenant names
                $tenants_str = null;
                $num_tenants = count($tenant_names_arr);

                for ($z = 0; $z < $num_tenants; $z++) {

                    if ($z == 0) {
                        $tenants_txt_sep = "";
                    } else if ($z == ($num_tenants - 1)) {
                        $tenants_txt_sep = " and ";
                    } else {
                        $tenants_txt_sep = ", ";
                    }
                    $tenants_str .= "{$tenants_txt_sep}{$tenant_names_arr[$z]}";
                }

                // EN email content
                $html_content  = "<p>Dear {$tenants_str},</p><br />
                <p>
                    Please find the attached entry notice for {$p_address} on {$job_date_dmy}. 
                    We will collect the keys from {$agency_name} to complete the service. Please contact us with any enquiries you may have.
                </p>
                <p>
                    <strong>Property Address</strong><br />
                    {$p_address}
                </p>
                <p>
                    Kind Regards,<br />
                    ". config_item('company_name_short') . " Team
                </p>";

                $return_as_string =  true;

                $view_data['paddress'] = $p_address;
                $view_data['agency_name'] = $agency_name;

                // content
                $email_body = ''; // clear
                $email_body .= $this->load->view('emails/template/email_header', $view_data, $return_as_string);
                $email_body .= nl2br($html_content);
                $email_body .= $this->load->view('emails/template/email_footer', $view_data, $return_as_string);

                $this->email->message($email_body);

                // attach EN pdf
                $pdf_name = 'en_pdf_' . rand() . date('YmdHis') . '.pdf';

                $en_pdf_params = array(
                    'job_id' => $job_id,
                    'output' => 'S'
                );
                $en_pdf = $this->pdf_template->entry_notice_switch($en_pdf_params);
                $this->email->attach($en_pdf, 'attachment',  $pdf_name, 'application/pdf');

                $email_sent = false;
                if ($this->email->send()) { // send email

                    $email_sent = true;

                    if (count($tenant_names_arr) > 0) {

                        // insert log
                        $combined_logs_arr[] = "Entry Notice emailed to <strong>Tenants</strong>";
                    }

                    if ($send_en_to_agency == 1) {

                        // insert log
                        $combined_logs_arr[] = "Entry Notice emailed to <strong>{$agency_name}</strong>";
                    }

                    // update job
                    $update_data = array(
                        'entry_notice_emailed' => $today_full
                    );
                    $this->db->where('id', $job_id);
                    $this->db->update('jobs', $update_data);
                }

                // SMS
                if ($job_type == "IC Upgrade" && $country_id == 1) {
                    $sms_type = 47; // Entry Notice (SMS EN) IC UPgrade
                } else {
                    $sms_type = 10; // Entry Notice (SMS EN)
                }

                // get template content                      
                $sel_query = "sms_api_type_id, body";
                $params = array(
                    'sel_query' => $sel_query,
                    'active' => 1,
                    'sms_api_type_id' => $sms_type,
                    'display_query' => 0
                );
                $sql = $this->sms_model->getSmsTemplates($params);
                $row = $sql->row();
                $unparsed_template = $row->body;

                // parse tags
                $sms_params = array(
                    'job_id' => $job_id,
                    'unparsed_template' => $unparsed_template
                );

                $parsed_template_body = $this->sms_model->parseTags($sms_params);

                $sms_sent = false;
                foreach ($tenant_mobile_arr as $tenant_mobile) {

                    // send SMS
                    $sms_params = array(
                        'sms_msg' => $parsed_template_body,
                        'mobile' => $tenant_mobile
                    );
                    $sms_json = $this->sms_model->sendSMS($sms_params);

                    // save SMS data on database
                    $sms_params = array(
                        'sms_json' => $sms_json,
                        'job_id' => $job_id,
                        'message' => $parsed_template_body,
                        'mobile' => $tenant_mobile,
                        'sent_by' => $logged_user,
                        'sms_type' => $sms_type,
                    );
                    $this->sms_model->captureSmsData($sms_params);
                    $sms_sent = true;
                }

                if (count($tenant_mobile_arr) > 0 && $sms_sent == true) {

                    $tenant_name_imp = implode(', ', $tenant_names_arr);

                    // insert log
                    $indiv_logs_str = "Reminder SMS to {$tenant_name_imp} <strong>{$parsed_template_body}</strong>";
                    $log_params = array(
                        'title' => 63, // Job Update
                        'details' => $indiv_logs_str,
                        'display_in_vjd' => 1,
                        'created_by_staff' => $logged_user,
                        'job_id' => $job_id
                    );
                    $this->system_model->insert_log($log_params);

                    // update job
                    $update_data = array(
                        'sms_sent' => $today_full,
                    );
                    $this->db->where('id', $job_id);
                    $this->db->update('jobs', $update_data);
                }

                // insert EN Date Issued either email or SMS sent
                if ($email_sent == true || $sms_sent == true) {

                    // update job as booked
                    $update_data = array(
                        'status' => 'Booked'
                    );
                    $this->db->where('id', $job_id);
                    $this->db->update('jobs', $update_data);

                    // insert log
                    $combined_logs_arr[] = "EN Booked via Key Access with <strong>{$booked_with}</strong> for <strong>" . ($this->system_model->isDateNotEmpty($en_date) ? date("d/m/Y", strtotime($en_date)) : null) . "</strong> @ <strong>{$en_time}</strong>. Technician <strong>{$str_tech_name}</strong>";
                } else {

                    // reset job updates if neither SMS or email EN is sent
                    $update_data = array(
                        'assigned_tech' => null,
                        'date' => null,
                        'time_of_day' => null,
                        'job_entry_notice' => 0,
                        'key_access_required' => 0,
                        'key_access_details' => null,
                        'tech_notes' => null,
                        'booked_by' => null,
                        'booked_with' => null,
                        'en_date_issued' => null
                    );
                    $this->db->where('id', $job_id);
                    $this->db->update('jobs', $update_data);
                }

                if (count($combined_logs_arr) > 0) {

                    // combined logs separator
                    $tenant_name_imp = implode('; ', $combined_logs_arr);

                    $log_params = array(
                        'title' => 63, // Job Update
                        'details' => $tenant_name_imp,
                        'display_in_vjd' => 1,
                        'created_by_staff' => $logged_user,
                        'job_id' => $job_id
                    );
                    $this->system_model->insert_log($log_params);
                }
            }
        }
    }

    public function get_agency_alarm($agency_id)
    {
        if (empty($agency_id)) {
            return false;
        }

        $this->db->select("agency_alarms.agency_id, alarm_pwr.alarm_pwr_id, alarm_pwr.alarm_pwr, alarm_pwr.is_li, alarm_pwr.is_240v, alarm_pwr.alarm_make, alarm_pwr.alarm_model");
        $this->db->from('agency_alarms');
        $this->db->join('alarm_pwr', 'agency_alarms.alarm_pwr_id = alarm_pwr.alarm_pwr_id', 'left');

        if (!empty($agency_id)) {
            if (is_array($agency_id)) {
                $this->db->where_in("agency_alarms.agency_id", $agency_id);
            } else {
                $this->db->where("agency_alarms.agency_id", $agency_id);
            }
        }

        $this->db->order_by("agency_alarms.agency_id, alarm_pwr.alarm_pwr");

        $result  = $this->db->get();

        if ($result->num_rows() > 0) {
            return $result->result();
        }

        return false;
    }


    public function get_job_details($jobId, $job_image_data = [])
    {
        $this->load->model('jobs_model');
        $this->load->model('tech_model');
        $this->load->model('figure_model');
        $this->load->model('water_meter_model');
        $this->load->model('Certification_types_model');

        $icServices = $this->figure_model->getICService(); // ic service ids
        $job_data = [];
        $jobs = $this->jobs_model->get_jobs([
            'sel_query' => "
                j.`id` AS jid,
                j.`status` AS j_status,
                j.`service` AS j_service,
                j.`created` AS j_created,
                j.`date` AS j_date,
                j.`comments` AS j_comments,
                j.`job_price`,
                j.`job_type`,
                j.`assigned_tech`,
                j.`invoice_amount`,
                j.`work_order`,
                j.`completed_timestamp`,
                j.`ts_signoffdate`,
                j.`swms_heights`,
                j.`swms_uv_protection`,
                j.`swms_asbestos`,
                j.`swms_powertools`,
                j.`swms_animals`,
                j.`swms_live_circuit`,
                j.`swms_covid_19`,
                j.`tech_comments`,
                j.`repair_notes`,
                j.`job_reason_id`,
                j.`job_reason_comment`,
                j.`survey_numlevels`,
                j.`survey_ladder`,
                j.`survey_ceiling`,
                j.`ps_number_of_bedrooms`,
                j.`ss_location`,
                j.`ss_quantity`,
                j.`ts_safety_switch`,
                j.`ts_safety_switch_reason`,
                j.`survey_numalarms`,
                j.`ts_batteriesinstalled`,
                j.`ts_items_tested`,
                j.`ss_items_tested`,
                j.`cw_items_tested`,
                j.`we_items_tested`,
                j.`ts_alarmsinstalled`,
                j.`survey_alarmspositioned`,
                j.`survey_minstandard`,
                j.`entry_gained_via`,
                j.`property_leaks`,
                j.`leak_notes`,
                j.`ss_image`,
                j.`ts_techconfirm`,
                j.`prop_comp_with_state_leg`,
                j.`booked_with`,
                j.`job_entry_notice`,
                j.`key_access_required`,
                j.`en_date_issued`,
                j.`key_access_details`,
                j.`entry_gained_other_text`,
                j.`door_knock`,
                j.`time_of_day`,
                j.`job_priority`,
                j.`is_eo`,

                p.`property_id`,
                p.`address_1` AS p_street_num,
                p.`address_2` AS p_street_name,
                p.`address_3` AS p_suburb,
                p.`state` AS p_state,
                p.`postcode` AS p_postcode,
                p.`comments` AS p_comments,
                p.`created` AS p_created,
                p.`key_number`,
                p.`alarm_code`,
                p.`prop_upgraded_to_ic_sa`,
                p.`qld_new_leg_alarm_num`,
                p.`preferred_alarm_id`,
                p.`holiday_rental`,
                p.`service_garage`,

                pl.`code` AS lb_code,

                nsw_pc.`short_term_rental_compliant`,
                nsw_pc.`req_num_alarms`,
                nsw_pc.`req_heat_alarm`,

                al_p.`alarm_make` AS pref_alarm_make,

                a.`agency_id`,
                a.`agency_name` AS agency_name,
                a.`phone` AS a_phone,
                a.`address_1` AS a_street_num,
                a.`address_2` AS a_street_name,
                a.`address_3` AS a_suburb,
                a.`state` AS a_state,
                a.`postcode` AS a_postcode,
                a.`trust_account_software`,
                a.`tas_connected`,
                a.`agency_specific_notes`,

                ajt.`id` AS service_type_id,
                ajt.`type` AS service_type,
                ajt.`bundle` AS is_bundle_serv,

                t.`StaffID` AS tech_id,
                t.`FirstName` AS tech_fname,
                t.`LastName` AS tech_lname
            ",
            'job_id' => $jobId,
            'country_id' => $this->config->item("country"),
            'join_table' => array('job_type', 'alarm_job_type', 'tech', 'preferred_alarm'),
            'custom_joins_arr' => array(
                array(
                    'join_table' => 'nsw_property_compliance as nsw_pc',
                    'join_on' => 'p.property_id = nsw_pc.property_id',
                    'join_type' => 'left'
                ),
                array(
                    'join_table' => 'property_lockbox as pl',
                    'join_on' => 'p.property_id = pl.property_id',
                    'join_type' => 'left'
                )
            ),
            'display_query' => 0
        ])->result_array();


        $alarm_data = $this->get_job_alarms($jobId, $job_image_data);
        $job_safety_switch = $this->get_job_safety_switch($jobId);
        $job_corded_window = $this->get_job_corded_window($jobId);
        $job_water_efficiency = $this->get_job_water_efficiency($jobId);



        foreach ($jobs as $obj_job) {
            $this_job_id = $obj_job["jid"];

            if ($obj_job["ss_image"] != null && $obj_job["ss_image"] != '') {
                // dynamic switch of ss image
                if (file_exists("{$_SERVER['DOCUMENT_ROOT']}/uploads/switchboard_image/{$obj_job["ss_image"]}")) {
                    // tecsheet CI
                    $ss_image_upload_folder = "{$this->config->item("crmci_link")}/uploads/switchboard_image/";
                    $obj_job["ss_image"] = "{$ss_image_upload_folder}{$obj_job["ss_image"]}";

                } elseif (file_exists("{$_SERVER['DOCUMENT_ROOT']}/images/ss_image/{$obj_job["ss_image"]}")) { // old techsheet
                    $ss_image_upload_folder = "{$this->config->item("crm_link")}/images/ss_image/";
                    $obj_job["ss_image"] = "{$ss_image_upload_folder}{$obj_job["ss_image"]}";

                }else{
                    $obj_job["ss_image"] = "";
                }
            }

            if ($obj_job["is_bundle_serv"] == 1) { // job can have multiple services
                $services = $this->db->select("bundle_services_id, alarm_job_type_id")
                    ->from("bundle_services AS bs")
                    ->join("alarm_job_type AS ajt", "ajt.id = bs.alarm_job_type_id", "left")
                    ->where("job_id", $this_job_id)
                    ->get()->result_array();

                $alarmJobTypeIds = array_map(function ($ajt) {
                    return $ajt["alarm_job_type_id"];
                }, $services);

                
                $hasSafetySwitch = in_array(5, $alarmJobTypeIds);
                $hasCordedWindow = in_array(6, $alarmJobTypeIds);
                $hasWaterEffeciency = in_array(15, $alarmJobTypeIds);
                $is_view_only_service = in_array(3, $alarmJobTypeIds);
                $has_water_meter = in_array(7, $alarmJobTypeIds);

	            $hasSmokeAlarm = Alarm_job_type_model::show_smoke_alarms($alarmJobTypeIds);

                array_walk($services, function ($ajt) use ($this_job_id) {
                    $this->jobs_model->runSync([
                        "job_id" => $this_job_id,
                        "jserv" => $ajt["alarm_job_type_id"],
                        "bundle_serv_id" => $ajt["bundle_services_id"],
                    ]);
                });
            } else { // single service job
                $hasSmokeAlarm = Alarm_job_type_model::show_smoke_alarms($obj_job["j_service"]);
                $hasSafetySwitch = $obj_job["j_service"] == 5;
                $hasCordedWindow = $obj_job["j_service"] == 6;
                $hasWaterEffeciency = $obj_job["j_service"] == 15;
                $is_view_only_service = $obj_job["j_service"] == 3;
                $has_water_meter = $obj_job["j_service"] == 7;

                $alarmJobTypeIds = [$obj_job["j_service"]];

                $this->jobs_model->runSync([
                    "job_id" => $this_job_id,
                    "jserv" => $obj_job["j_service"],
                ]);
            }

            // active tenants
            $propertyTenants = $this->properties_model->get_property_tenants([
                'sel_query' => "pt.`property_tenant_id`, pt.`tenant_firstname`, pt.`tenant_lastname`, pt.`tenant_email`, pt.`tenant_mobile`, pt.`tenant_landline`",
                'property_id' => $obj_job["property_id"],
                'pt_active' => 1,
                'display_query' => 0,
            ])->result_array();


            // check if first visit and
            $firstVisit = $this->tech_model->check_prop_first_visit($obj_job["property_id"]);

            $certification_types =  $this->Certification_types_model
                ->as_array()
                ->fields("certification_types.*")
                ->select("IF(c.id > 0, IF(c.status = 'cancelled',0,1),'') as `option`")
                ->join('certifications as c', 'c.certification_id = certification_types.id AND c.job_id = '.$this_job_id, 'LEFT')
                ->where(['active' => 1])->get_all();

            $water_meter = $this->water_meter_model->as_array()->where([['job_id', $this_job_id]])->order_by('water_meter_id', 'DESC')->get();


            $job_data[$this_job_id] = [
                "job"                   => $obj_job,
                "is_ic_service"         => in_array($obj_job["j_service"], $icServices),
                "has_smoke_alarm"       => $hasSmokeAlarm,
                "has_safety_switch"     => $hasSafetySwitch,
                "has_corded_window"     => $hasCordedWindow,
                "has_water_efficiency"  => $hasWaterEffeciency,
                "is_view_only_service"  => $is_view_only_service,
                "has_water_meter"       => $has_water_meter,
                "property_tenants"      => $propertyTenants,
                "first_visit"           => $firstVisit,
                "alarm_job_type_ids"    => $alarmJobTypeIds,
                "existing_alarms"       => $alarm_data[$this_job_id]["existing_alarm"] ?? [],
                "new_alarms"            => $alarm_data[$this_job_id]["new_alarm"] ?? [],
                "expired_alarms"        => $alarm_data[$this_job_id]["expired_alarm"] ?? [],
                "safety_switches"       => $job_safety_switch[$this_job_id] ?? [],
                "corded_windows"        => $job_corded_window[$this_job_id] ?? [],
                "water_efficiency_details" => $job_water_efficiency[$this_job_id] ?? [],
                "switchboard_image"     => "",
                "water_meter"          => $water_meter ?  $water_meter :  [],
                "certification_types" => $certification_types ?  $certification_types :  []
            ];

            if (
                isset($job_image_data[$this_job_id]["switchboard_image"]) &&
                !empty($job_image_data[$this_job_id]["switchboard_image"])
            ) {
                $job_data[$this_job_id]["switchboard_image"] = $job_image_data[$this_job_id]["switchboard_image"];
            }
        }

        return $job_data;
    }

    public function get_job_alarms($job_ids, $job_image_data = [])
    {
        $alarm_job_data = [];

        $existing_alarms = $this->db->select("
            al.`alarm_id`,
            al.`job_id`,
            al.`alarm_power_id`,
            al.`alarm_type_id`,
            al.`alarm_reason_id`,
            al.`expiry`,
            al.`make`,
            al.`model`,
            al.`new`,
            al.`ts_added`,
            al.`ts_alarm_sounds_other`,
            al.`ts_cleaned`,
            al.`ts_db_rating`,
            al.`ts_discarded`,
            al.`ts_discarded_reason`,
            al.`ts_expiry`,
            al.`ts_fixing`,
            al.`ts_meetsas1851`,
            al.`ts_newbattery`,
            al.`ts_position`,
            al.`rec_batt_exp`,
            al.`ts_required_compliance`,
            al.`ts_testbutton`,
            al.`ts_visualind`,

            al_pwr.`alarm_pwr_id`,
            al_pwr.`alarm_pwr`,
            al_pwr.`alarm_make`,

            al_type.`alarm_type_id`,
            al_type.`alarm_type`,
            al_img.`location_image_filename`,
            al_img.`expiry_image_filename`
        ")->from("alarm AS al")
            ->join("alarm_pwr AS al_pwr", "al.alarm_power_id = al_pwr.alarm_pwr_id", "left")
            ->join("alarm_type AS al_type", "al.alarm_type_id = al_type.alarm_type_id", "left")
            ->join("alarm_images AS al_img", "al.alarm_id = al_img.alarm_id", "left")
            ->where_in("al.job_id", $job_ids)
            ->where("al.new !=", 1)
            ->group_by('al.`alarm_id`')
            ->order_by("al.alarm_id", "ASC")
            ->get()->result_array();

        foreach ($existing_alarms as $alarm) {

            if (!isset($alarm_job_data[$alarm["job_id"]]["existing_alarm"])) {
                $alarm_job_data[$alarm["job_id"]]["existing_alarm"] = array();
            }

            if (
                isset($job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_expiry"]) &&
                !empty($job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_expiry"])
            ) {
                $alarm["image_expiry"] = $job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_expiry"];
            }

            if (
                isset($job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_location"]) &&
                !empty($job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_location"])
            ) {
                $alarm["image_location"] = $job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_location"];
            }

            $alarm_job_data[$alarm["job_id"]]["existing_alarm"][] = $alarm;
        }

        $new_alarms = $this->db->select("
            al.`alarm_id`,
            al.`job_id`,
            al.`alarm_power_id`,
            al.`alarm_type_id`,
            al.`alarm_reason_id`,
            al.`expiry`,
            al.`make`,
            al.`model`,
            al.`new`,
            al.`ts_added`,
            al.`ts_alarm_sounds_other`,
            al.`ts_cleaned`,
            al.`ts_db_rating`,
            al.`ts_discarded`,
            al.`ts_discarded_reason`,
            al.`ts_expiry`,
            al.`ts_fixing`,
            al.`ts_meetsas1851`,
            al.`ts_newbattery`,
            al.`ts_position`,
            al.`rec_batt_exp`,
            al.`ts_required_compliance`,
            al.`ts_testbutton`,
            al.`ts_visualind`,

            al_pwr.`alarm_pwr_id`,
            al_pwr.`alarm_pwr`,
            al_pwr.`alarm_make`,
            al_pwr.`alarm_model`,

            al_type.`alarm_type_id`,
            al_type.`alarm_type`,
            al_img.`location_image_filename`,
            al_img.`expiry_image_filename`
        ")->from("alarm AS al")
            ->join("alarm_pwr AS al_pwr", "al.alarm_power_id = al_pwr.alarm_pwr_id", "left")
            ->join("alarm_type AS al_type", "al.alarm_type_id = al_type.alarm_type_id", "left")
            ->join("alarm_images AS al_img", "al.alarm_id = al_img.alarm_id", "left")
            ->where_in("al.job_id", $job_ids)
            ->where("al.new", 1)
            ->group_by('al.`alarm_id`')
            ->order_by("al.alarm_id", "ASC")
            ->get()->result_array();

        foreach ($new_alarms as $alarm) {

            if (!isset($alarm_job_data[$alarm["job_id"]]["new_alarm"])) {
                $alarm_job_data[$alarm["job_id"]]["new_alarm"] = array();
            }

            if (
                isset($job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_expiry"]) &&
                !empty($job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_expiry"])
            ) {
                $alarm["image_expiry"] = $job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_expiry"];
            }

            if (
                isset($job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_location"]) &&
                !empty($job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_location"])
            ) {
                $alarm["image_location"] = $job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_location"];
            }

            $alarm_job_data[$alarm["job_id"]]["new_alarm"][] = $alarm;
        }

        $expired_alarms = $this->db->select("
            al.`alarm_id`,
            al.`job_id`,
            al.`alarm_power_id`,
            al.`alarm_type_id`,
            al.`alarm_reason_id`,
            al.`expiry`,
            al.`make`,
            al.`model`,
            al.`new`,
            al.`ts_added`,
            al.`ts_alarm_sounds_other`,
            al.`ts_cleaned`,
            al.`ts_db_rating`,
            al.`ts_discarded`,
            al.`ts_discarded_reason`,
            al.`ts_expiry`,
            al.`ts_fixing`,
            al.`ts_meetsas1851`,
            al.`ts_newbattery`,
            al.`ts_position`,
            al.`rec_batt_exp`,
            al.`ts_required_compliance`,
            al.`ts_testbutton`,
            al.`ts_visualind`,

            al_pwr.`alarm_pwr_id`,
            al_pwr.`alarm_pwr`,
            al_pwr.`alarm_make`,

            al_type.`alarm_type_id`,
            al_type.`alarm_type`
        ")
            ->from("alarm AS al")
            ->join("alarm_pwr AS al_pwr", "al.alarm_power_id = al_pwr.alarm_pwr_id", "left")
            ->join("alarm_type AS al_type", "al.alarm_type_id = al_type.alarm_type_id", "left")
            ->where("al.alarm_power_id !=", 6)
            ->where_in("al.job_id", $job_ids)
            ->where("al.expiry <=", date("Y"))
            ->group_by('al.`alarm_id`')
            ->order_by("al.alarm_id", "ASC")
            ->get()->result_array();

        foreach ($expired_alarms as $alarm) {

            if (!isset($alarm_job_data[$alarm["job_id"]]["expired_alarm"])) {
                $alarm_job_data[$alarm["job_id"]]["expired_alarm"] = array();
            }

            if (
                isset($job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_expiry"]) &&
                !empty($job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_expiry"])
            ) {
                $alarm["image_expiry"] = $job_image_data[$alarm["job_id"]][$alarm["alarm_id"]]["image_expiry"];
            }

            if (
                isset($job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_location"]) &&
                !empty($job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_location"])
            ) {
                $alarm["image_location"] = $job_image_data[$alarm["job_id"]]["alarms"][$alarm["alarm_id"]]["image_location"];
            }

            $alarm_job_data[$alarm["job_id"]]["expired_alarm"][] = $alarm;
        }

        return $alarm_job_data;
    }

    public function get_job_safety_switch($job_ids)
    {
        $result = $this->db->select("safety_switch_id, make, model, test, new, ss_stock_id, ss_res_id, discarded, job_id")
            ->from("safety_switch")
            ->where_in("job_id", $job_ids)
            ->order_by("make", "ASC")
            ->get()->result_array();

        $data = [];

        foreach ($result as $row) {

            if (!isset($data[$row["job_id"]])) {
                $data[$row["job_id"]] = [];
            }

            $data[$row["job_id"]][] = $row;
        }
        return $data;
    }

    public function get_job_corded_window($job_ids)
    {
        $result = $this->db->select("corded_window_id, location, num_of_windows, job_id")
            ->from("corded_window")
            ->where_in("job_id", $job_ids)
            ->order_by("location", "ASC")
            ->get()->result_array();

        $data = [];

        foreach ($result as $row) {

            if (!isset($data[$row["job_id"]])) {
                $data[$row["job_id"]] = [];
            }

            $data[$row["job_id"]][] = $row;
        }
        return $data;
    }

    public function get_job_water_efficiency($job_ids)
    {
        $result = $this->db->select("water_efficiency_id, device, pass, location, note, job_id")
            ->from("water_efficiency")
            ->where_in("job_id", $job_ids)
            ->where("active", 1)
            ->get()->result_array();

        $data = [];

        foreach ($result as $row) {

            if (!isset($data[$row["job_id"]])) {
                $data[$row["job_id"]] = [];
            }

            $data[$row["job_id"]][] = $row;
        }
        return $data;
    }
}
