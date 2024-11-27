<?php

$data = $this->templatedatahandler->getData();
extract($data);
$user_account = $loggedInUser;
$staff_name = $user_account->FirstName;
$staff_last_name = $user_account->LastName;

if (( $this->system_model->isDateNotEmpty($job_row['j_date']) ) && $_GET['tr_date'] != "") {
    $day = date("l", strtotime($_GET['tr_date']));
} else {
    $day = ($this->system_model->isDateNotEmpty($job_row['j_date'])) ? date("l", strtotime($job_row['j_date'])) : 'NO DATE SELECTED';
}

if ($this->system_model->getAgencyPrivateFranchiseGroups($job_row['franchise_groups_id']) == true) {
    $agency_name_txt = "your agency";
    $landlord_txt = 'your landlord';
} else {
    $agency_name_txt = $job_row['agency_name'];
    $landlord_txt = 'your agency';
}

if ($job_row['ajt_id'] == 9) {
    $serv_text = 'Smoke Alarms/Window Blinds/Safety Switch';
} else if ($job_row['ajt_id'] == 8) { // SA SS
    $serv_text = 'Smoke Alarms/Safety Switch';
} else {
    $serv_text = 'Smoke Alarms';
}


 /**
*  TBB JOB TOP POPUP/SCRIPT TEXT SELECTION
*/
if( $job_row['j_status'] == "To Be Booked" ){

    echo "<div class='alert alert-success alert-fill alert-border-left alert-close alert-dismissible fade show' role='alert'>";

    $script_text="";
    if( $job_row['j_type']=="Yearly Maintenance" ){
        $script_text = "
        <p>Hi this is {$staff_name} from " . config_item('company_full_name') . " calling on behalf of <span class='junderline_colored'><u>{$agency_name_txt}</u></span> in regards to the rental property at <span class='junderline_colored'><u>{$job_row['p_address_2']}</u></span>.</p>
        <p>We have been instructed to service the {$serv_text} at your property. I have a technician available this <span class='junderline_colored'><u>{$day}</u></span> between <span class='junderline_colored'><u>TIME</u></span> and <span class='junderline_colored'><u>TIME</u></span><p/>
        <p>Would anybody be available to allow access?</p>
    ";
    }elseif($job_row['j_type']=="240v Rebook"){
        $script_text = "
        <p>Hi this is {$staff_name} from " . config_item('company_full_name') . ". <span class='junderline_colored'>{$agency_name_txt}</span> have instructed us to attend to your property at <span class='junderline_colored'>{$job_row['p_address_2']}</span> to replace the smoke alarms as they are <span class='junderline_colored'>due to expire</span>.</p>
        <p>I have a technician available this <span class='junderline_colored'>{$day}</span> between <span class='junderline_colored'>TIME</span> and <span class='junderline_colored'>TIME</span><p/>
        <p>Would anybody be available to allow access?</p>
        ";
    }elseif($job_row['j_type']=="Fix or Replace"){
        $script_text = "
        <p>Hello, this is {$staff_name} from " . config_item('company_full_name') . ". We've been informed by <span class='junderline_colored'>{$agency_name_txt}</span> that there are concerns regarding your Smoke Alarms at <span class='junderline_colored'>{$job_row['p_address_2']}</span>, and they've requested our urgent attendance.</p>
        <p> I have a technician available this <span class='junderline_colored'>{$day}</span> between <span class='junderline_colored'>TIME and TIME</span>. Is there anyone available to grant access during this time frame? </p>
        ";
    }elseif($job_row['j_type']=="Change of Tenancy"){
        $script_text = "
            <p>Hi this is {$staff_name} from " . config_item('company_full_name') . ". <span class='junderline_colored'>{$agency_name_txt}</span> have instructed us that you are a new tenant at <span class='junderline_colored'>{$job_row['p_address_2']}</span> and we are to service the {$serv_text} at this property.</p>
            <p>I have a technician available this <span class='junderline_colored'>{$day}</span> between <span class='junderline_colored'>TIME</span> and <span class='junderline_colored'>TIME</span><p/>
            <p>Would anybody be available to allow access?</p>
        ";
    }elseif($job_row['j_type']=="Lease Renewal"){
        $script_text = "
            <p>Hi this is {$staff_name} from " . config_item('company_full_name') . ". <span class='junderline_colored'>{$agency_name_txt}</span> have instructed us that you have signed a new lease and we are to attend to your property at <span class='junderline_colored'>{$job_row['p_address_2']}</span> to service the {$serv_text}.</p>
            <p>I have a technician available this <span class='junderline_colored'>{$day}</span> between <span class='junderline_colored'>TIME</span> and <span class='junderline_colored'>TIME</span><p/>
            <p>Would anybody be available to allow access?</p>
        ";
    }elseif($job_row['j_type']=="Once-off"){
        $script_text = "
            <p>Hi this is {$staff_name} from " . config_item('company_full_name') . " calling on behalf of <span class='junderline_colored'>{$agency_name_txt}</span> in regards to the rental property at <span class='junderline_colored'>{$job_row['p_address_2']}</span>.</p>
            <p>We have been instructed to service the {$serv_text} at your property. I have a technician available this <span class='junderline_colored'>{$day}</span> between <span class='junderline_colored'>TIME</span> and <span class='junderline_colored'>TIME</span><p/>
            <p>Would anybody be available to allow access?</p>
        ";
    }

    echo "<div class='script_textbox main_script_text'>";
    echo $script_text;
    echo "</div>";
?>
    <div>
        <div class="script_textbox" id='inbound_call_div' style='display:none;'>
            <p>We called on behalf of <span class='junderline_colored'><?php echo $agency_name_txt ?></span></p>
            <p>We have been instructed to service the Smoke Alarms at your property. I can see we have a technician available this <span class='junderline_colored'><?php echo $day; ?></span> between <span class='junderline_colored'>TIME</span> and <span class='junderline_colored'>TIME</span></p>
            <p>Would anybody be available to allow access?</p>
        </div>
        <div class="script_textbox" id='dif_bok_div' style='display:none;'>
            <p>I need to advise you that because we have attempted to book this job in multiple times and have not been able to gain access that I am obliged to notify <?php echo $agency_name_txt; ?></p>
        </div>
        <div class="script_textbox" id='voicemail_div' style='display:none;'>
            <p>Hi this is <?php echo $staff_name; ?> from <?=config_item('company_name_short');?> calling on behalf of <?php echo $agency_name_txt; ?>. Please return my call on <?php echo $ctn['tenant_number']; ?></p>
        </div>
        <div class="script_textbox" id='not_available_div' style='display:none;'>
            <p>What is the best time or day of the week that you are available so I can make a note for next time we are in the area?</p>
            <p>Thanks <span class='junderline_colored'>NAME</span> Just to confirm. I have here that your best time is <span class='junderline_colored'>TIME</span>. So we will call you again to make an appointment when we have that time available.</p>
            <p>Thanks and Have a great day</p>
        </div>
        <div class="script_textbox" id='key_access_div' style='display:none;'>
            <p>We do have the option of getting the keys from <?php echo $agency_name_txt ?>, and we can leave a card once we have completed the service to let you know we have attended.</p>
            <p>How does that sound?</p>
        </div>
        <div class="script_textbox" id='cat_div' style='display:none;'>
            <p> Hi this is <?php echo $staff_name; ?> from <?=config_item('company_name_short');?>. We've been calling you to see if you are available on <span class='junderline_colored'>TIME AND DAY</span>. We are in the area and it would be great if we can service your smoke alarms too. </p>
        </div>
        <div class="script_textbox" id='pt_div' style='display:none;'>
            <p>Hi this is <?php echo $staff_name; ?> from <?=config_item('company_name_short');?>. I know you requested for a <span class='junderline_colored'>PREFERRED DAY/TIME/EXACT DATE</span> to be scheduled. Since we have a technician in the area between <span class='junderline_colored'>TIME AND DAY</span> and it would be great to service your smoke alarms if anyone in your household is available for us. </p>
        </div>

        <!--<button type='button' class='btn btn-danger btn-sm btn-script' data-target="#booking_script" data-label="Hide Script">Hide Script</button>-->
        <button type='button' class='btn btn-primary btn-sm btn-script' data-target="#inbound_call_div" data-label="Inbound Call">Inbound Call</button>
        <button type='button' class='btn btn-danger btn-sm btn-script' data-target="#dif_bok_div" data-label="Difficult Booking">Difficult Booking</button>
        <button type='button' class='btn btn-primary btn-sm btn-script' data-target="#voicemail_div" data-label="Voicemail">Voicemail</button>
        <button type='button' class='btn btn-danger btn-sm btn-script' data-target="#not_available_div" data-label="Not Available">Not Available</button>
        <button type='button' class='btn btn-primary btn-sm btn-script' data-target="#key_access_div" data-label="Key Access">Key Access</button>
        <button type='button' class='btn btn-danger btn-sm btn-script' data-target="#cat_div" data-label="Called Already Today">Called Already Today</button>
        <button type='button' class='btn btn-primary btn-sm btn-script' data-target="#pt_div" data-label="Preferred Time">Preferred Time</button>

    </div>

<?php
    echo "</div>";
}

/**
*  BOOKED JOB TOP POPUP/SCRIPT TEXT SELECTION
*/
if( $job_row['j_status'] == "Booked" ){

    echo "<div class='alert alert-success alert-fill alert-border-left alert-close alert-dismissible fade show' role='alert'>";

    if ($job_row['key_access_required'] == 1) {
        if ($job_row['key_email_req'] == 1) {
            $script_text = "Thanks <span class='junderline_colored'>{$job_row['booked_with']}</span>. Just to confirm, we have you booked in for <span class='junderline_colored'>" . date('l', strtotime($job_row['j_date'])) . " " . date('d/m/Y', strtotime($job_row['j_date'])) . "</span> and we will collect the keys from {$agency_name_txt} and our technician will leave a card to let you know the job has been done. {$agency_name_txt} requires you to confirm this booking so I am going to email you a template that you will need to reply to. Is that ok? Great, I am sending that to you now. Thanks and have a great day";
        } else {
            $script_text = "Thanks <span class='junderline_colored'>{$job_row['booked_with']}</span>. Just to confirm, we have you booked in for <span class='junderline_colored'>" . date('l', strtotime($job_row['j_date'])) . " " . date('d/m/Y', strtotime($job_row['j_date'])) . "</span> and we will collect the keys from {$agency_name_txt} and our technician will leave a card to let you know the job has been done. Thanks and have a great day";
        }
    } else {
        $script_text = "Thanks <span class='junderline_colored'>{$job_row['booked_with']}</span>. Just to confirm, we have you booked in for <span class='junderline_colored'>" . date('l', strtotime($job_row['j_date'])) . " " . date('d/m/Y', strtotime($job_row['j_date'])) . "</span> at <span class='junderline_colored'>" . $job_row['time_of_day'] . "</span>. We will send you an SMS the day before to remind you of the appointment. Thanks and have a great day";
    }

    echo "<div class='script_textbox main_script_text'";
    echo $script_text;
    echo "</div>";
?>
    
    <div>
        <div class="script_textbox" id="cancel_voicemail_script" style="display: none;">
            Hi This is <?php echo $staff_name; ?> from smoke alarm testing services, unfortunately we are unable to complete your service today as our technician <span class='junderline_colored'>REASON</span>. I'm sorry but we will have to call you again to make a new appointment. Have a nice day
        </div>
        <div class="script_textbox" id="cancel_with_caller_script" style="display: none;">
            Hi This is <?php echo $staff_name; ?> from smoke alarm testing services, unfortunately we are unable to complete your service today as our technician <span class='junderline_colored'>REASON</span>. I'm sorry but we will have to call you again to make a new appointment.
        </div>
        <div class="script_textbox" id="rebook_script" style="display: none;">
            Hi This is <?php echo $staff_name; ?> from smoke alarm testing services, unfortunately we are unable to complete your service today as our technician <span class='junderline_colored'>REASON</span>. Are you available <span class='junderline_colored'><?php echo $day; ?></span> <span class='junderline_colored'>TIME</span> for us    
        </div>

        <button type='button' class='btn btn-danger btn-sm btn-script' data-target="#cancel_voicemail_script" data-label="Cancel Voicemail">Cancel Voicemail</button>
        <button type='button' class='btn btn-primary btn-sm btn-script' data-target="#cancel_with_caller_script" data-label="Cancel with Caller">Cancel with Caller</button>
        <button type='button' class='btn btn-danger btn-sm btn-script' data-target="#rebook_script" data-label="Rebook">Rebook</button>
    </div>
    
<?php
    echo "</div>";

}
?>