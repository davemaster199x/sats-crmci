<!-- CONTENT START HERE -->

<?php 
    $no_assigned_pm_txt = "<span style='font-style: italic;'>No Assigned Property Manager</span>"; // no assigned PM 
?>

<p>Dear <?php echo $agency_name; ?>,</p>

<!-- Upfront Auto Renew QLD clients with API -->
<?php if ($agency_auto_renew === 1 && $subscription_billing === 1 && $apis->active === 1): ?>

    <p>Please <a href='<?php echo $agency_portal_link; ?>'>log in</a>  to our Agency Portal and go to the 'Service Due' page to view properties that are now due for subscription renewal.</p>
    <p>Please note, because we have an active API connection with your Agency, our Team will adjust any updated tenant details required. </p>
    <p>If you no longer manage the property, then mark the checkbox and 'click' NO LO.03NGER MANAGE. These changes can be done in multiple ways.</p>
    <p style='color:red;'>Any properties that are still in 'Service Due' by the 15th of the month, will automatically be renewed to fulfill our obligations to your landlords.</p>

    <p>When you process the properties due for subscription renewal, and it has been identified that the property has not had a physical attendance completed for more than 12 months, we will generate a job to attend.</p>
    <p>To view our Step-by-Step video on how to process properties that are due for subscription renewal please click <a href='<?php echo $youtube_link ?>'>HERE</a>.</p>
    <p>If you need any help or have questions, please contact our office on <?php echo $agent_number; ?> and speak with one of our friendly staff members.</p>
<?php endif; ?>

<!-- Upfront Auto Renew QLD clients without API -->
<?php if ($agency_auto_renew === 1 && $subscription_billing === 1 && $apis->active === 0): ?>

    <p>Please <a href='<?php echo $agency_portal_link; ?>'>log in</a>  to our Agency Portal and go to the 'Service Due' page to view properties that are now due for subscription renewal.</p>
    <p>If the tenant details are correct and you still manage the property, then mark the check box and 'click' CREATE JOB.</p>
    <p>If you no longer manage the property, then mark the checkbox and 'click' NO LONGER MANAGE. These changes can be done in multiple ways.</p>
    <p style='color:red;'>Any properties that are still in 'Service Due' by the 15th of the month, will automatically be renewed to fulfill our obligations to your landlords.</p>

    <p>When you process the properties due for subscription renewal, and it has been identified that the property has not had a physical attendance completed for more than 12 months, we will generate a job to attend.</p>
    <p>To view our Step-by-Step video on how to process properties that are due for subscription renewal please click <a href='<?php echo $youtube_link ?>'>HERE</a>.</p>
    <p>If you need any help or have questions, please contact our office on <?php echo $agent_number; ?> and speak with one of our friendly staff members.</p>

<?php endif; ?>

<!-- Upfront No Auto Renew QLD clients with API -->
<?php if ($agency_auto_renew === 0 && $subscription_billing === 1 && $apis->active === 1): ?>

    <p>This is a courtesy to advise the below properties are due for subscription renewals here at <?=config_item('company_full_name');?> (<?=config_item('company_name_short');?>)</p>
    <p>Please note, because we have an active API connection with your Agency, our Team will adjust any updated tenant details required.</p>
    <p>We ask that you advise which properties are to continue <?=config_item('company_name_short');?> services and which are to be deactivated within our database (if required).</p>

    <br /><br />
    <table style="width:100%; border: 1px solid #efefef;">
        <tr>
            <td style="background-color: #404041; color: #ffffff; padding: 5px;"><b>Month Due</b></td>
            <td style="background-color: #404041; color: #ffffff; padding: 5px;"><b>Address</b></td>
            <td style="background-color: #404041; color: #ffffff; padding: 5px;"><b>Property Manager</b></td>
        </tr>

        <?php // get pending jobs

            $rowcount = 0;
            foreach( $pending_sql->result() as $pending_row )
            {
                // property address
                $paddress = "{$pending_row->p_address_1} {$pending_row->p_address_2}, {$pending_row->p_address_3}";
                // get property managers
                if( $pending_row->agency_user_account_id > 0 ){
                    $pm_name = "{$pending_row->pm_fname} {$pending_row->pm_lname}";
                }else{
                    $pm_name = $no_assigned_pm_txt;
                }
        ?>

        <tr style="background-color:<?php echo ($rowcount % 2 == 0 ? null : "#efefef") ?>">
            <td style="padding: 5px;"><?php echo date('F',strtotime($pending_row->start_date)); ?></td>
            <td style="padding: 5px;"><?php echo $paddress; ?></td>
            <td style="padding: 5px;"><?php echo $pm_name; ?></td>
        </tr>
        <?php
                $rowcount++;
            }
        ?>
    </table>
    <br>

    <p>
        Please note: We are under your strict instruction to NOT renew these properties as part of our Annual Maintenance program. 
        If we are not granted permission to attend and complete works, <?=config_item('company_name_short');?> shall deem these properties non-compliant and accept no liability pertaining to their compliance.
    </p>
    <p>When you process the properties due for subscription renewal, and it has been identified that the property has not had a physical attendance completed for more than 12 months, we will generate a job to attend.</p>
    <p>Should you have any additional questions, please feel free to contact our friendly Customer Service Team on 1300 41 66 67 or please respond to this email directly.</p>

    <br /><br />
    
<?php endif; ?>

<!-- Upfront No Auto Renew QLD clients without API -->
<?php if ($agency_auto_renew === 0 && $subscription_billing === 1): ?>

    <p>This is a courtesy to advise the below properties are due for subscription renewals here at <?=config_item('company_full_name');?> (<?=config_item('company_name_short');?>)</p>
    <p>We ask that you advise which properties are to continue <?=config_item('company_name_short');?> services and which are to be deactivated within our database (if required).</p>

    <br /><br />
    <table style="width:100%; border: 1px solid #efefef;">
        <tr>
            <td style="background-color: #404041; color: #ffffff; padding: 5px;"><b>Month Due</b></td>
            <td style="background-color: #404041; color: #ffffff; padding: 5px;"><b>Address</b></td>
            <td style="background-color: #404041; color: #ffffff; padding: 5px;"><b>Property Manager</b></td>
        </tr>

        <?php // get pending jobs

            $rowcount = 0;
            foreach( $pending_sql->result() as $pending_row )
            {
                // property address
                $paddress = "{$pending_row->p_address_1} {$pending_row->p_address_2}, {$pending_row->p_address_3}";
                // get property managers
                if( $pending_row->agency_user_account_id > 0 ){
                    $pm_name = "{$pending_row->pm_fname} {$pending_row->pm_lname}";
                }else{
                    $pm_name = $no_assigned_pm_txt;
                }
        ?>

        <tr style="background-color:<?php echo ($rowcount % 2 == 0 ? null : "#efefef") ?>">
            <td style="padding: 5px;"><?php echo date('F',strtotime($pending_row->start_date)); ?></td>
            <td style="padding: 5px;"><?php echo $paddress; ?></td>
            <td style="padding: 5px;"><?php echo $pm_name; ?></td>
        </tr>
        <?php
                $rowcount++;
            }
        ?>
    </table>
    <br>

    <p>
        Please note: We are under your strict instruction to NOT renew these properties as part of our Annual Maintenance program. 
        If we are not granted permission to attend and complete works, <?=config_item('company_name_short');?> shall deem these properties non-compliant and accept no liability pertaining to their compliance.
    </p>
    <p>When you process the properties due for subscription renewal, and it has been identified that the property has not had a physical attendance completed for more than 12 months, we will generate a job to attend.</p>
    <p>Should you have any additional questions, please feel free to contact our friendly Customer Service Team on 1300 41 66 67 or please respond to this email directly.</p>

    <br /><br />

<?php else: ?>



<?php endif; ?>


<p>
    Kind Regards<br />
	<?=config_item('company_full_name');?>.
</p>	
<!-- CONTENT END HERE -->