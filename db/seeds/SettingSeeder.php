<?php


use Phinx\Seed\AbstractSeed;

class SettingSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
	public function run(): void
	{
		$skipped = [
			'__construct',
			'run',
		];

		$methods = array_diff(get_class_methods($this), get_class_methods(get_parent_class($this)));

		foreach ($methods as $method) {
			if (!in_array($method, $skipped)) {
				// reset indexes before insert
				$this->query("TRUNCATE TABLE " . $method);
				$sql = $this->$method();
				if(!empty($sql)){
					$this->query($sql);
				}
			}
		}
	}

    private function agency_api()
    {
        return "insert into agency_api (agency_api_id, api_name, img_name, active)
values  (1, 'PropertyMe', 'pme', 1),
        (2, 'Tapi', 'tapi', 1),
        (3, 'Property Tree', 'prop_tree', 1),
        (4, 'Palace', 'palace', 1),
        (5, 'Console Cloud', 'cc', 1),
        (6, 'Our Property', 'our_tradie', 1),
        (7, 'Maintenance Manager', 'mm', 1);";
    }

    private function agency_marker_id_definition(){
        return "insert into agency_marker_id_definition (id, marker_definition, yes, no)
values  (1, 'Hide ''Not 2022 Compliant'' for short term rentals? Test ', 'This agency will hide ''Not 2022 Compliant''. Test', 'This agency will show ''Not 2022 Compliant''. Test'),
(2, 'Sales Only', null, null)";

    }

    private function agency_onboarding(){
        return "insert into agency_onboarding (onboarding_id, name, active)
values  (1, 'Welcome Email sent', 1),
        (2, 'Portal Login Created and invite sent', 1),
        (3, 'All SAS departments notified of new Agency', 1),
        (4, 'BDM checklist received and attached to Agencies file in CRM', 1),
        (5, 'Email Thread Attached into Agency files in CRM', 1),
        (6, 'List completion notes email to Agency and CC BDM', 1),
        (7, 'After first service completed call to Agency (Refer to H/O Onboarding checklist) ', 1),
        (8, '4 week \"Happy Call\" made', 1),
        (9, '3 month survey monkey review sent', 1)";

    }

    private function agency_preference(){
        return "insert into agency_preference (id, pref_text, yes_txt, no_txt, sort_index, active)
values  (1, 'Attach invoices to emails?', 'Agency emails will include an attached PDF invoice and a link', 'Agency emails will only include a hyperlink to the invoice', 1, 0),
        (2, 'Send Combined Certificate and Invoice', 'Agency Receives a Combined Invoice and Certificate', 'Agency Receives a separate Invoice and Certificate', 2, 0),
        (3, 'Entry Notice issued by Email', 'Entry Notices by Email allowed', 'Entry Notices MUST be Posted', 3, 0),
        (4, 'Work Order Required For All Jobs?', 'Work order number required for all jobs', 'NO work order number required', 4, 0),
        (5, 'Individual Property Managers Receive Certificate & Invoice?', 'PMs get additional copy of Invoice and Certificate', 'NO additional Certificate and Invoice sent', 5, 0),
        (6, 'Auto Renew Yearly Maintenance Properties', 'Agency Allows Auto Renew', 'Agency DOESN''T allow Auto Renew', 6, 0),
        (7, 'Key Access Allowed?', 'Key access allowed', 'Key access NOT allowed', 7, 0),
        (8, 'Tenant Key Email Required?', 'Agency wants email from Tenant to approve keys', 'No email from Tenant required', 8, 0),
        (9, 'Allow Doorknocks?', 'Door Knocks allowed', 'NO Door Knocks allowed', 9, 0),
        (10, 'Allow Entry Notice?', 'Entry Notices are Allowed', 'NO Entry Notices are Allowed', 10, 0),
        (11, 'All New Jobs Emailed to Agency?', 'Agency Receives email for ALL new properties', 'Agency DOESN''T Receive email for ALL new properties', 11, 0),
        (12, 'Display BPAY on Invoices?', 'BPAY displayed on invoices', 'BPAY not displayed on invoices', 12, 0),
        (13, 'Subscription Billing?', 'Agency Allows up front billing', 'Agency Does not Allow up front billing', 13, 0),
        (14, 'Invoice PM''S Only?', 'Invoice issued only to individual PM not to agency accounts email', 'Agency Does not Allow Invoice PM''S Only', 14, 0),
        (15, 'Electrician Only', 'Electricians ONLY to attend', 'Both Techs and Electricians can attend', 15, 0),
        (16, 'Send copy of EN to Agency', 'Agency receives a copy of Entry Notices', 'Agency does not receive a copy of Entry Notices', 16, 0),
        (17, 'Individual Property Managers Receive EN?', 'EN sent to PM only', 'EN sent to Agency only', 17, 0),
        (18, 'Show accounts reports?', 'Accounts reports, including statements will be visible', 'No accounting information will be visible to the agency', 18, 0),
        (19, 'Exclude $0 invoices', 'This agency will only receive invoices with a positive balance.', 'This agency will receive all invoices.', 19, 0),
        (20, 'Send 48 hour key email', 'This agency will be notified both 24 and 48 hours in advance of any keys required.', 'This agency will only be notified 24 hours in advance of any keys required.', 20, 0),
        (21, 'Address Invoice to Agency?', 'The invoice will show ''ATTN: [agency_name]''', 'The invoice will show ''ATTN: [landlord_firstname landlord_lastname]''', 21, 0),
        (22, 'Paid alarms?', 'The agency will pay for all new alarms', 'The agency will only pay for alarms for IC Upgrade jobs or Short Term Rental properties', 21, 1),
        (23, 'Photos on Compliance Certificate?', 'Agency certificates and combined will show alarm photos', 'Agency has requested no photos on certificates and combined', 23, 1)";

    }

    private function agency_price_variation_reason(){
        return "insert into agency_price_variation_reason (id, reason, is_discount, active)
values  (1, 'Agency Deal', 1, 1),
        (2, 'Prior Arrangement', 1, 1),
        (3, 'Multi Property', 1, 1),
        (4, 'Agency Request', 0, 1),
        (5, 'Platform Cost', 0, 1),
        (6, 'Price Adjustment', 1, 1),
        (7, 'Price Adjustment', 0, 1),
        (8, 'Once-Off Visit', 0, 1)";

    }

    private function agency_priority_marker_definition(){
        return "insert into agency_priority_marker_definition (id, priority, abbreviation, priority_full_name)
values  (1, 0, 'NULL', 'Normal'),
        (2, 1, 'HT', 'High Touch'),
        (3, 2, 'VIP', 'Very Important Person'),
        (4, 3, 'HWC', 'Handle With Care')";

    }
    private function agency_site_maintenance_mode(){
        return "insert into agency_site_maintenance_mode (agency_site_maintenance_mode_id, mode)
values  (1, 0)";

    }


    private function agency_using(){
        return "insert into agency_using (agency_using_id, name, country_id)
values  (1, 'SAS', 1),
        (2, 'NSAS', 1),
        (3, 'SA Australia', 1),
        (4, 'Electrician', 1),
        (5, 'Handyman', 1),
        (6, 'Sydney SA', 1),
        (7, 'Other', 1),
        (8, 'No One', 1),
        (9, 'Detector Inspector', 1),
        (10, '1300 SA', 1),
        (11, 'Safety Watch', 1),
        (12, 'CCA', 1)";

    }

    private function alarm_discarded_reason(){
        return "insert into alarm_discarded_reason (id, reason, active)
values  (1, 'Alarm Faulty', 0),
        (2, 'Alarm Expired', 1),
        (3, 'Not RFC', 0),
        (4, 'Alarm Broken', 0),
        (5, 'Alarm Missing', 1),
        (6, 'No Expiry Date', 1),
        (7, 'Alarm Not Li', 1),
        (8, 'Not Photo-Electric', 1),
        (9, 'Won''t Stop Beeping', 1),
        (10, 'Low dB Reading', 1),
        (11, 'Match Expiry Dates', 1),
        (12, 'No Indicator Lights', 1),
        (13, 'Alarm Damaged', 1),
        (14, 'Upgrade to 240v', 1),
        (15, 'Upgrade to 240vLi', 1),
        (16, 'Alarm Clip Broken', 1),
        (17, 'Alarm Base Broken', 1),
        (18, 'Head Unit Broken', 1),
        (19, 'Not Interconnected', 1),
        (20, 'Match Brands', 1),
        (21, 'Duplicate Alarm Added', 1),
        (22, 'Additional Cover', 1)";

    }



    private function alarm_pwr(){
        return "insert into alarm_pwr (alarm_pwr_id, alarm_pwr, alarm_price_ex, alarm_price_inc, alarm_job_type_id, alarm_make, alarm_model, alarm_expiry, alarm_type, active, is_240v, battery_type, is_replaceable, alarm_pwr_source, is_li)
values  (1, '9v', 25.5, 27.82, 2, 'Brooks', 'EIB605C', 2031, 2, 1, 0, '9v', 1, '9v', 0),
        (2, '240v', 38.7, 42.22, 2, 'Brooks', 'EIB146RC', 2031, 18, 1, 1, '9v', 1, '240v', 0),
        (3, '9vLi', 53, 57.81, 2, 'Brooks', 'EIB605TYC', 2029, 2, 0, 0, '3vLi', 0, '3vLi', 1),
        (4, '240vLi', 84, 92.4, 2, 'Brooks', 'EiB166E', 2031, 2, 1, 1, '3vLi', 0, '240v', 1),
        (5, 'CO', 89.6, 0, 2, 'Ei', 'EI208W', 2026, 2, 1, 0, '3vLi', 0, '3vLi', 0),
        (6, 'Batteries', 0.2, 0, 2, null, null, 2028, 2, 1, 0, null, 0, null, 0),
        (7, '3vLi', 56.2, 61.3, 2, 'Brooks', 'EIB650IC', 2031, 2, 1, 0, '3vLi', 0, '3vLi', 1),
        (8, '9vLi RF', 120.6, 131.56, 2, 'Brooks', 'EIB605MTYRF', 2028, 2, 0, 0, '9vLi', 0, '9vLi', 1),
        (9, '240vLi RF', 172, 187.6, 2, 'Brooks', 'EIB166ERF', 2028, 19, 1, 1, '9vLi', 0, '240v', 1),
        (10, '240v RF', 99, 108, 2, 'Brooks', 'EIB168RC', 2031, 2, 1, 1, '9v', 1, '9v', 0),
        (11, '240vLi', 80.35, 87.65, 2, 'Brooks', 'EIPFSPTLH (OLD)', 2029, 2, 0, 1, '9vLi', 0, '240v', 1),
        (12, '3vLiRF', 120.6, 115.64, 2, 'Brooks', 'EIB650iRF', 2031, 19, 1, 0, '3vLi', 0, '3vLi', 1),
        (13, '6vLiRF(cav)', 80, 88, 2, 'Cavius', '2107-001', 2031, 19, 1, 0, '6vLi', 0, '6vLi', 1),
        (14, '240vRF(cav)', 90, 99, 2, 'Cavius', '2203-002', 2031, 19, 1, 1, '6vLi', 0, '240v', 1),
        (15, '3vLiFP', 48.13, 52.51, 2, 'Brooks', 'EIB650IWX', 2031, 2, 1, 0, '3vLi', 0, '3vLi', 0),
        (16, 'ABAX16', 41.13, 45.2, 2, 'Brooks', 'BAX16', 2031, 2, 1, 0, '3vLi', 0, '240v', 0),
        (17, '3vLiRF Heat', 161.04, 175.68, 2, 'Brooks', 'EiB603TYCRF', 2031, 4, 1, 0, '3vLi', 0, '3vLi', 0),
        (18, '9v(EP)', 7.6, 8.4, 2, 'Emerald Planet', 'EP-RANG-1', 2032, 2, 1, 0, '9v', 1, '9v', 0),
        (19, '3VLi(EP)', 15.4, 16.94, 2, 'Emerald Planet', 'EP-RANG-10', 2032, 19, 1, 0, '3vLi', 0, '3vLi', 0),
        (20, '3vLiRF(EP)', 33.4, 36.74, 2, 'Emerald Planet', 'EP-RANG-RF-10', 2032, 19, 1, 0, '3vLi', 0, '3vLi', 0),
        (21, '240v(EP)', 17.8, 19.58, 2, 'Emerald Planet', 'EP-VC-240-1', 2032, 2, 1, 0, '9v', 1, '240v', 0),
        (22, '240VRF(EP)', 29.5, 32.45, 2, 'Emerald Planet', 'EP-VC-RF-MOD', 2032, 2, 1, 0, '9v', 1, '240v', 0),
        (23, 'Test', 100, 110, 2, 'Test', 'T123456', 0, 2, 0, 0, '3v', 1, '240v', 0),
        (24, '240v (RED)', 16.5, 18.15, 2, 'Red', 'R240', 2032, 2, 1, 0, '9v', 1, '240v', 0),
        (25, '240vLi (RED)', 44, 48.4, 2, 'Red', 'R240RC', 2032, 2, 1, 0, '3vLi', 0, '240v', 1),
        (26, '3vLiRF (RED)', 30, 33, 2, 'Red', 'R10RF', 2032, 2, 1, 0, '3vLi', 0, '3vLi', 1),
        (27, '240vRF (RED)', 16.5, 18.15, 2, 'Red', 'RWB240v', 2032, 2, 1, 0, '9v', 1, '240v', 0),
        (28, '3vLi (RED)', 21, 23.1, 2, 'Red', 'R10', 2032, 2, 1, 0, '3vLi', 0, '3vLi', 1),
        (29, '9v (RED)', 9, 9.9, 2, 'Red', 'R9', 2032, 2, 1, 0, '9v', 1, '9v', 0),
        (30, 'Safety Switch', 60, 66, 2, 'Switch', '240v SW', 2032, 2, 1, 0, '9v', 0, '240v', 0),
        (31, 'Red Wall Controller', 25, 27.5, 2, 'Red', 'R-A-C', 0, 2, 1, 0, '3vLi', 0, '3vLi', 0)";

    }


    private function alarm_reason(){
        return "insert into alarm_reason (alarm_reason_id, alarm_reason, alarm_job_type_id, active)
values  (1, 'Alarm Missing', 2, 1),
        (2, 'Alarm Faulty', 2, 0),
        (3, 'Alarm Expired', 2, 1),
        (4, 'Insufficient Alarms', 2, 1),
        (5, 'Alarm Broken', 2, 0),
        (6, 'Discolourisation', 1, 1),
        (7, 'Burnt Pieces', 1, 1),
        (8, 'Burnt Smell', 1, 1),
        (9, 'Loose Components', 1, 1),
        (10, 'Twist, Kink or Cut Lead', 1, 1),
        (11, 'Exposed Conductors', 1, 1),
        (12, 'RFC', 2, 0),
        (13, 'Existing', 2, 0),
        (14, 'No Expiry Date', 2, 1),
        (16, 'Not Photo-Electric', 2, 1),
        (17, 'Won''t Stop Beeping', 2, 1),
        (18, 'Low dB Reading', 2, 1),
        (19, 'Match Expiry Dates', 2, 1),
        (20, 'No Indicator Lights', 2, 1),
        (21, 'Alarm Damaged', 2, 1),
        (22, 'Under Warranty', 2, 1),
        (23, 'Upgrade to 240v', 2, 1),
        (24, 'Upgrade to 240vLi', 2, 1),
        (25, 'Li Alarm Required', 2, 1),
        (26, 'Alarm Clip Broken', 2, 1),
        (27, 'Alarm Base Broken', 2, 1),
        (28, 'Head Unit Broken', 2, 1),
        (29, 'Incorrect Position', 2, 1),
        (30, 'Upgrade to Interconnected', 2, 1),
        (32, 'Match Brands', 2, 1),
        (33, 'Additional Cover', 2, 1),
        (34, 'Extra Alarm requested', 2, 1)";

    }

    private function alarm_type(){
        return "insert into alarm_type (alarm_type_id, alarm_type, alarm_job_type_id)
values  (1, 'Ionisation', 2),
        (2, 'Photo Electric', 2),
        (3, 'Other', 2),
        (4, 'Class 1', 1),
        (5, 'Class 2', 1),
        (6, 'Light', 4),
        (7, 'Power Circuit', 4),
        (8, 'Stove', 4),
        (9, 'Air Cond', 4),
        (10, 'Other', 4),
        (11, 'Roman', 6),
        (12, 'Holland', 6),
        (13, 'Venetian', 6),
        (14, 'Vertical', 6),
        (15, 'Lead', 1),
        (16, 'Curtain', 6),
        (17, 'Other', 6),
        (18, 'Carbon Monoxide', 2),
        (19, 'P/E Interconnected', 2),
        (20, 'Ion Interconnected', 2)";

    }

    private function blind_type_cw(){
        return "insert into blind_type_cw (blind_type_cw_id, name, active)
values  (1, 'None', 1),
        (2, 'Holland', 1),
        (3, 'Venetian', 1),
        (4, 'Vertical', 1),
        (5, 'Curtain', 1),
        (6, 'Roman', 1),
        (7, 'Other', 1)";

    }

    private function complaints_status(){
        return "insert into complaints_status (id, status, hex, index_sort, active)
values  (1, 'Pending', '#fa424a', 1, 1),
        (2, 'Declined', '#C0C0C0', 6, 1),
        (3, 'In Progress', '#fc6f03', 2, 1),
        (4, 'Completed', '#3c9e0e', 4, 1),
        (5, 'QA', '#fcd703', 3, 1),
        (6, 'More info required', '#34ebd5', 5, 1),
        (7, 'Unable to Replicate', '#b46fd1', 7, 1),
        (8, 'Approval Required', '#5b5b5b', 8, 1)";

    }

    private function complaints_topic(){
        return "insert into complaints_topic (comp_topic_id, comp_topic, active)
values  (1, 'Job related', 1),
        (2, 'Scheduling related', 1),
        (3, 'Customer service related', 1),
        (4, 'Technician related', 1),
        (5, 'BDM related', 1),
        (6, 'Pricing related', 1)";

    }

    private function countries(){
        return "insert into countries (country_id, country, tax, tax_percent, states, phone_prefix, iso, agent_number, tenant_number, email_signature, letterhead_footer, trading_name, outgoing_email, bank, bsb, abn, ac_name, ac_number, web, facebook, twitter, instagram, company_address, linkedin)
				values  
    			(1, 'Australia', 'GST', 10, 1, '+61', 'AU', '1300 852 301', '1300 852 301', 'cron_email_footer_au.png', 'cert_footer_au.png', 'Smoke Alarm Solutions Pty Ltd', 'info@smokealarmsolutions.com.au', 'BANK', '000-000', '97 604 793 688', 'Smoke Alarm Solutions Pty Ltd', '1111 1111', 'https://smokealarmsolutions.com.au/', 'https://www.facebook.com/SmokeAlarmSolutionsAU/', 'https://twitter.com/', 'https://instagram.com/', '63931 Yatala QLD 4207', 'https://www.linkedin.com/')
     			";

    }

    private function credit_reason(){
        return "insert into credit_reason (credit_reason_id, reason, active)
values  (1, 'In Good Faith', 1),
        (2, 'Duplicate Charge', 1),
        (3, 'Multiple Property Discount', 1),
        (4, 'Incorrectly charged', 1),
        (5, 'Agents Property', 1),
        (6, 'Write Off BAD DEBT', 1),
        (7, 'Transfer', 1),
        (8, 'Honour Supplier Dates', 1),
        (9, 'Upfront Bill NLM', 1)";

    }

    private function credit_request_adj_res(){
        return "insert into credit_request_adj_res (id, reason, active)
values  (1, 'Multiple Property Discount', 1),
        (2, 'Dispute with agent', 1),
        (3, 'Dispute with owner', 1),
        (4, 'Upfront bill - NLM', 1),
        (5, 'Duplicate YM Charge', 1),
        (6, 'SAS Incorrectly charged', 1),
        (7, 'FOC Alarms', 1),
        (8, 'Discounted YM per agreed rates', 1),
        (9, 'No Longer Manage', 1)";

    }

    private function crm_settings(){
        return "insert into crm_settings (crm_settings_id, auto_emails, cron_send_letters, cron_merged_cert, cron_merge_sms, country_id, agency_portal_vip_agencies, sms_credit, sms_credit_update_ts, statements_generic_note, statements_generic_note_ts, cron_pme_upload, cron_mark_unservice, disable_all_crons, agency_portal_mm)
values  (1, 0, 1, 1, 1, 1, '1448,3899,3962,1598,4644,3446', 110099, '2022-06-02 15:01:37', '<p style=\"text-align: left; \" align=\"center\">Note - this isn''t what you sent today, dumb dumb test dev test111<br></p><p align=\"center\">The team at SAS would like to wish all our customers a Merry Christmas and Happy New Year. </p>', '2020-10-13 17:35:22', 0, 1, 0, 0),
        (2, 0, 1, 1, 0, 2, '3899,1448,3962,1598,4644,3446', 0, null, null, null, null, null, 0, 0)";

    }


    private function crm_task_category(){
        return "insert into crm_task_category (id, category_name, active)
values  (1, 'CRM', 1),
        (2, 'Tech App', 1),
        (3, 'Agency Portal', 1),
        (4, 'Hardware', 1),
        (5, 'VOIP', 1)";

    }


    private function crm_task_status(){
        return "insert into crm_task_status (id, status, hex, index_sort, active)
values  (1, 'Pending', '#fa424a', 1, 1),
        (2, 'Declined', '#C0C0C0', 6, 1),
        (3, 'In Progress', '#fc6f03', 2, 1),
        (4, 'Completed', '#3c9e0e', 4, 1),
        (5, 'QA', '#fcd703', 3, 1),
        (6, 'More info required', '#34ebd5', 5, 1),
        (7, 'Unable to Replicate', '#b46fd1', 7, 1),
        (8, 'Approval Required', '#5b5b5b', 8, 1)";

    }



    private function crm_task_sub_category(){
        return "insert into crm_task_sub_category (id, sub_category_name, active)
values  (1, 'Bug', 1),
        (2, 'Suggestion', 0),
        (3, 'Change', 1),
        (4, 'Feature Wanted', 0)";

    }




    private function display_on(){
        return "insert into display_on (id, location, active)
values  (1, 'View Agency Details', 1),
        (2, 'CRM Property Pages', 1),
        (3, 'Agency Portal', 1),
        (4, 'VAD, VPD & Agency Portal', 1),
        (5, 'VPD & Agency Portal', 1),
        (6, 'Invoice', 1),
        (7, 'Invoice & Agency Portal', 1)";

    }



    private function email_templates_tag(){
        return "insert into email_templates_tag (email_templates_tag_id, tag_name, tag, active)
values  (1, 'Agency Name', '{agency_name}', 1),
        (2, 'Property Address', '{property_address}', 1),
        (3, 'Service Type', '{service_type}', 1),
        (4, 'Job Date', '{job_date}', 1),
        (5, 'Job Number', '{job_number}', 1),
        (6, 'Landlord', '{landlord}', 1),
        (7, 'Tenant Phone Number', '{tenant_phone_number}', 1),
        (8, 'Tenant 1', '{tenant_1}', 0),
        (9, 'Tenant 2', '{tenant_2}', 0),
        (10, 'Tenant 3', '{tenant_3}', 0),
        (11, 'Tenant 4', '{tenant_4}', 0),
        (12, 'Agency Phone Number', '{agency_phone_number}', 1),
        (13, 'User', '{user}', 1),
        (14, 'Tech Comments', '{tech_comments}', 1),
        (15, 'Agency Email', '{agency_email}', 1),
        (16, 'Accounts Email', '{agency_accounts_email}', 1),
        (17, 'Agency Address', '{agency_address}', 1),
        (18, 'Active Tenants', '{active_tenants}', 0),
        (19, 'PM Tenants', '{pm_tenants}', 0),
        (20, 'Landlord Email', '{landlord_email}', 1),
        (21, 'SAS Tenant Line', '{tenant_number}', 1),
        (22, 'SAS Agent Line', '{agent_number}', 1),
        (23, 'Agency Staff First Name', '{agency_staff_fname}', 1),
        (24, 'Agency Staff 2FA Code', '{agency_staff_2fa_code}', 1),
        (25, 'Agency Staff Device Used', '{agency_staff_device_used}', 1),
        (26, 'Agency Staff Browser Used', '{agency_staff_browser_used}', 1),
        (27, 'Agency Staff IP', '{agency_staff_ip}', 1),
        (28, 'Info SAS email', '{info_sats_email}', 1),
        (29, 'SAS Google Review', '{sats_google_review}', 1),
        (30, 'Not Complaint Notes', '{not_compliant_notes}', 1),
        (31, 'Property Managers Email', '{property_managers_email}', 1)";

    }

    private function email_templates_type(){
        return "insert into email_templates_type (email_templates_type_id, name, active)
values  (1, 'Sales', 1),
        (2, 'Jobs', 1),
        (3, 'Accounts', 1),
        (4, 'General', 1),
        (5, 'Operations', 1),
        (6, 'Customer Service', 1),
        (7, 'Scheduling', 1),
        (8, 'Escalates', 1),
        (9, 'Call Centre', 1),
        (10, 'Private Jobs', 1),
        (11, 'IC Upgrades', 1),
        (12, 'Data Entry', 1)";

    }


    private function expense_account(){
        return "insert into expense_account (expense_account_id, account_name, active, deleted)
values  (1, 'Postage', 1, 0),
        (2, 'Office Supplies', 1, 0),
        (3, 'Fuel/Oil', 1, 0),
        (4, 'Telephone', 1, 0),
        (5, 'Printing', 1, 0),
        (6, 'Tools/Supplies', 1, 0),
        (7, 'Flights', 1, 0),
        (8, 'Accommodation', 1, 0),
        (9, 'Meals', 1, 0),
        (10, 'Service of Fleet', 1, 0),
        (11, 'Entertainment', 1, 0),
        (12, 'Repairs & Maintenance', 1, 0),
        (13, 'Tolls', 1, 0),
        (14, 'Other', 1, 0),
        (15, 'Transport', 1, 0)";

    }





    private function franchise_groups(){
        return "insert into franchise_groups (franchise_groups_id, name, username, password, country_id)
values  (1, 'Independent', '', '', 1),
        (2, 'LJ Hooker', '', '', 1),
        (3, 'Raine & Horne', '', '', 1),
        (4, 'Elders', '', '', 1),
        (5, 'Remax', '', '', 1),
        (6, 'Laing & Simmons', '', '', 1),
        (7, 'Belle', '', '', 1),
        (8, 'Century 21', '', '', 1),
        (9, 'Dowling', '', '', 1),
        (10, 'Private', '', '', 1),
        (11, 'Coldwell Banker', '', '', 1),
        (12, 'First National', '', '', 1),
        (13, 'Harcourts', '', '', 1),
        (14, 'Defence Housing', '', '', 1),
        (15, 'McGrath', '', '', 1),
        (16, 'Professionals', '', '', 1),
        (17, 'Place', '', '', 1),
        (18, 'PRD Nationwide', '', '', 1),
        (19, 'Ray White', '', '', 1),
        (20, 'Richardson & Wrench', '', '', 1),
        (21, 'Starr Partners', '', '', 1),
        (22, 'Wiseberry', '', '', 1),
        (23, 'Rentals Express ', '', '', 1),
        (33, 'UK Agencies', '', '', 4),
        (34, 'LJ Hooker', '', '', 4),
        (36, 'One Agency', ' ', '', 1),
        (38, 'Stone Real Estate', '', '', 1),
        (39, 'Compass Housing ', '', '', 1),
        (40, 'Image', '', '', 1),
        (42, 'Harris', '', '', 1)";

    }


    private function home_content_block(){
        return "insert into home_content_block (content_block_id, content_name, category)
values  (1, 'To Be Booked', 1),
        (2, '240v Rebook', 1),
        (3, 'Fix and Replace', 1),
        (4, 'Electrician Only', 1),
        (5, 'NSW Overdue', 1),
        (6, 'DHA To be booked', 1),
        (7, 'Invalid Address', 1),
        (8, 'Multiple Jobs', 1),
        (9, 'Duplicate Visits', 1),
        (10, 'Coordinate Errors', 1),
        (11, 'Active Unsold Services', 1),
        (12, 'No Job Type', 1),
        (13, 'No Job Status', 1),
        (14, 'No Retest Date', 1),
        (15, 'Data Discrepancy', 1),
        (16, 'Unserviced Properties', 1),
        (17, 'Multiple Services', 1),
        (18, 'No Active Job', 1),
        (19, 'DHA completed (365 Days)', 1),
        (20, 'Total Jobs since June 2021', 1),
        (21, 'Agency Audits', 1),
        (22, 'New Agency Lists', 1),
        (23, 'Greetings', 2),
        (24, 'Booking Schedule', 2),
        (25, 'Recent tickets', 2),
        (26, 'Expense Statements', 2),
        (27, 'Cars', 2),
        (28, 'Booked', 2),
        (29, 'Leave Requests', 2),
        (30, '
Agency Noticeboard', 2),
        (31, 'Staff Dates', 2),
        (32, 'Local Times', 1),
        (33, 'DHA to be Invoiced', 1),
        (34, 'Platform Invoicing', 1),
        (35, 'Incoming SMS', 1),
        (36, 'Credit Request', 1),
        (37, 'Refund Request', 1),
        (38, 'To Be Invoiced', 1),
        (39, 'New Jobs', 1),
        (40, 'Office to call', 1),
        (41, 'To be Allocated', 1),
        (42, 'Missing Region', 1),
        (43, 'Duplicate Properties', 1),
        (44, 'Escalated Jobs', 1),
        (45, 'Action Required', 1),
        (46, 'Properties need Verification', 1),
        (47, 'Sales Upgrades To be Booked', 1),
        (48, 'Ready to be Mapped', 1),
        (49, 'Call over complete', 1),
        (50, 'Active Properties Without Jobs', 1),
        (51, 'Last Contact', 1),
        (52, 'Console Tenants', 1),
        (53, 'Sales SMS', 1),
        (54, 'Unlinked SMS', 1)";

    }



    private function home_content_block_class_access(){
        return "insert into home_content_block_class_access (id, user_class, content_block_id)
values  (4376, 10, 27),
        (4377, 10, 36),
        (4378, 10, 26),
        (4379, 10, 23),
        (4380, 10, 32),
        (4381, 10, 25),
        (4382, 10, 37),
        (4383, 10, 31),
        (4384, 10, 20),
        (5447, 3, 30),
        (5448, 3, 2),
        (5449, 3, 45),
        (5450, 3, 50),
        (5451, 3, 11),
        (5452, 3, 21),
        (5453, 3, 28),
        (5454, 3, 24),
        (5455, 3, 49),
        (5456, 3, 27),
        (5457, 3, 10),
        (5458, 3, 36),
        (5459, 3, 15),
        (5460, 3, 19),
        (5461, 3, 6),
        (5462, 3, 33),
        (5463, 3, 43),
        (5464, 3, 9),
        (5465, 3, 4),
        (5466, 3, 44),
        (5467, 3, 26),
        (5468, 3, 3),
        (5469, 3, 23),
        (5470, 3, 35),
        (5471, 3, 7),
        (5472, 3, 51),
        (5473, 3, 29),
        (5474, 3, 32),
        (5475, 3, 42),
        (5476, 3, 8),
        (5477, 3, 17),
        (5478, 3, 22),
        (5479, 3, 39),
        (5480, 3, 18),
        (5481, 3, 13),
        (5482, 3, 12),
        (5483, 3, 14),
        (5484, 3, 5),
        (5485, 3, 40),
        (5486, 3, 34),
        (5487, 3, 46),
        (5488, 3, 48),
        (5489, 3, 25),
        (5490, 3, 37),
        (5491, 3, 47),
        (5492, 3, 31),
        (5493, 3, 41),
        (5494, 3, 1),
        (5495, 3, 38),
        (5496, 3, 20),
        (5497, 3, 16),
        (5505, 5, 30),
        (5506, 5, 23),
        (5507, 5, 22),
        (5508, 5, 25),
        (5509, 5, 31),
        (5510, 5, 20),
        (5511, 7, 2),
        (5512, 7, 11),
        (5513, 7, 28),
        (5514, 7, 24),
        (5515, 7, 19),
        (5516, 7, 6),
        (5517, 7, 9),
        (5518, 7, 4),
        (5519, 7, 3),
        (5520, 7, 23),
        (5521, 7, 7),
        (5522, 7, 51),
        (5523, 7, 32),
        (5524, 7, 8),
        (5525, 7, 17),
        (5526, 7, 22),
        (5527, 7, 18),
        (5528, 7, 13),
        (5529, 7, 12),
        (5530, 7, 14),
        (5531, 7, 5),
        (5532, 7, 25),
        (5533, 7, 1),
        (5534, 7, 20),
        (5535, 7, 16),
        (5536, 8, 2),
        (5537, 8, 45),
        (5538, 8, 11),
        (5539, 8, 28),
        (5540, 8, 24),
        (5541, 8, 49),
        (5542, 8, 10),
        (5543, 8, 15),
        (5544, 8, 6),
        (5545, 8, 9),
        (5546, 8, 4),
        (5547, 8, 44),
        (5548, 8, 3),
        (5549, 8, 23),
        (5550, 8, 35),
        (5551, 8, 7),
        (5552, 8, 51),
        (5553, 8, 32),
        (5554, 8, 42),
        (5555, 8, 8),
        (5556, 8, 17),
        (5557, 8, 22),
        (5558, 8, 18),
        (5559, 8, 13),
        (5560, 8, 12),
        (5561, 8, 14),
        (5562, 8, 5),
        (5563, 8, 48),
        (5564, 8, 25),
        (5565, 8, 41),
        (5566, 8, 1),
        (5567, 8, 20),
        (5568, 8, 16),
        (5569, 9, 30),
        (5570, 9, 2),
        (5571, 9, 45),
        (5572, 9, 50),
        (5573, 9, 11),
        (5574, 9, 21),
        (5575, 9, 28),
        (5576, 9, 24),
        (5577, 9, 49),
        (5578, 9, 27),
        (5579, 9, 10),
        (5580, 9, 36),
        (5581, 9, 15),
        (5582, 9, 19),
        (5583, 9, 6),
        (5584, 9, 33),
        (5585, 9, 43),
        (5586, 9, 9),
        (5587, 9, 4),
        (5588, 9, 44),
        (5589, 9, 26),
        (5590, 9, 3),
        (5591, 9, 23),
        (5592, 9, 35),
        (5593, 9, 7),
        (5594, 9, 51),
        (5595, 9, 29),
        (5596, 9, 32),
        (5597, 9, 42),
        (5598, 9, 8),
        (5599, 9, 17),
        (5600, 9, 22),
        (5601, 9, 39),
        (5602, 9, 18),
        (5603, 9, 13),
        (5604, 9, 12),
        (5605, 9, 14),
        (5606, 9, 5),
        (5607, 9, 40),
        (5608, 9, 34),
        (5609, 9, 46),
        (5610, 9, 48),
        (5611, 9, 25),
        (5612, 9, 37),
        (5613, 9, 47),
        (5614, 9, 31),
        (5615, 9, 41),
        (5616, 9, 1),
        (5617, 9, 38),
        (5618, 9, 20),
        (5619, 9, 16),
        (5620, 11, 30),
        (5621, 11, 2),
        (5622, 11, 11),
        (5623, 11, 21),
        (5624, 11, 28),
        (5625, 11, 24),
        (5626, 11, 27),
        (5627, 11, 10),
        (5628, 11, 15),
        (5629, 11, 19),
        (5630, 11, 6),
        (5631, 11, 9),
        (5632, 11, 4),
        (5633, 11, 26),
        (5634, 11, 3),
        (5635, 11, 23),
        (5636, 11, 7),
        (5637, 11, 51),
        (5638, 11, 29),
        (5639, 11, 32),
        (5640, 11, 8),
        (5641, 11, 17),
        (5642, 11, 22),
        (5643, 11, 18),
        (5644, 11, 13),
        (5645, 11, 12),
        (5646, 11, 14),
        (5647, 11, 5),
        (5648, 11, 25),
        (5649, 11, 31),
        (5650, 11, 1),
        (5651, 11, 20),
        (5652, 11, 16),
        (5758, 2, 30),
        (5759, 2, 2),
        (5760, 2, 45),
        (5761, 2, 50),
        (5762, 2, 11),
        (5763, 2, 21),
        (5764, 2, 28),
        (5765, 2, 24),
        (5766, 2, 49),
        (5767, 2, 27),
        (5768, 2, 52),
        (5769, 2, 10),
        (5770, 2, 36),
        (5771, 2, 15),
        (5772, 2, 19),
        (5773, 2, 6),
        (5774, 2, 33),
        (5775, 2, 43),
        (5776, 2, 9),
        (5777, 2, 4),
        (5778, 2, 44),
        (5779, 2, 26),
        (5780, 2, 3),
        (5781, 2, 23),
        (5782, 2, 35),
        (5783, 2, 7),
        (5784, 2, 51),
        (5785, 2, 29),
        (5786, 2, 32),
        (5787, 2, 42),
        (5788, 2, 8),
        (5789, 2, 17),
        (5790, 2, 22),
        (5791, 2, 39),
        (5792, 2, 18),
        (5793, 2, 13),
        (5794, 2, 12),
        (5795, 2, 14),
        (5796, 2, 5),
        (5797, 2, 40),
        (5798, 2, 34),
        (5799, 2, 46),
        (5800, 2, 48),
        (5801, 2, 25),
        (5802, 2, 37),
        (5803, 2, 53),
        (5804, 2, 47),
        (5805, 2, 31),
        (5806, 2, 41),
        (5807, 2, 1),
        (5808, 2, 38),
        (5809, 2, 20),
        (5810, 2, 54),
        (5811, 2, 16)";

    }


    private function job_reason(){
        return "insert into job_reason (job_reason_id, name, log_message)
values  (1, 'No Show', 'Job Marked as No Show'),
        (2, '240v Rebook', 'Job Marked as 240v Rebook'),
        (3, 'Fire Panel', 'Alarms connected to Fire Panel'),
        (4, 'Alarm System', 'Alarms connected to House Alarm'),
        (5, 'Keys Don’t Work', 'Keys Provided Don’t Work'),
        (7, 'Fuse Box Locked', 'Fuse Box Locked'),
        (8, 'Unable to Locate Fuse Box', 'Unable to Locate Fuse Box'),
        (9, 'No Power To 240v', 'No Power To 240v'),
        (10, 'Refused Entry', 'Refused Entry'),
        (11, 'No Keys EVER', 'No Keys EVER'),
        (12, 'Unable to open lockbox', 'Unable to open lockbox'),
        (13, 'No Keys on Harcor', 'No Keys on Harcor'),
        (14, 'No Time to Complete', 'No Time to Complete'),
        (15, 'Tall Ladder Required', 'Tall Ladder Required'),
        (16, 'DK Nobody Home', 'DK Nobody Home'),
        (17, 'No Longer Managed by Agent', 'No Longer Managed by Agent'),
        (18, 'Property Vacant', 'Property Vacant'),
        (19, 'Interconnect Required', 'Interconnect Required'),
        (20, 'No Adults Present', 'No Adults Present'),
        (21, 'Not Vacant', 'Property Not Vacant'),
        (22, 'No Keys TODAY', 'No Keys TODAY'),
        (23, 'No Entry due to Dog', 'No Entry due to Dog'),
        (24, 'No Show (Late)', 'Job Marked as No Show (Late)'),
        (25, 'Staff Sick', 'Staff Sick'),
        (26, 'Tenant Cancelled', 'Tenant Cancelled'),
        (28, 'Out of Stock', 'Out of Stock'),
        (29, 'Dangerous situation', 'Dangerous situation'),
        (30, 'Stock', 'Stock'),
        (31, 'Car Issues', 'Car Issues'),
        (32, 'DK Refused Entry', 'Upon DK Tenant refused entry'),
        (33, 'DK Refused COVID', 'Upon DK Tenant refused due to COVID'),
        (34, 'Weather', 'Weather prevented job being done'),
        (35, 'Unable to Locate Safety Switch', '')";

    }


    private function job_type(){
        return "insert into job_type (job_type, description, abbrv)
values  ('240v Rebook', '240v Rebook', '240v'),
        ('Annual Visit', 'Annual Visit', 'Annual'),
        ('Change of Tenancy', 'Change of Tenancy Service', 'COT'),
        ('Fix or Replace', 'Fix or Replace Service', 'FR'),
        ('IC Upgrade', 'IC Upgrade', 'Upgrade'),
        ('Lease Renewal', 'Lease Renewal', 'LR'),
        ('Once-off', 'Once off Service', 'O/Off'),
        ('Yearly Maintenance', 'Yearly Maintenance Service', 'YM')";

    }


    private function job_type_change(){
        return "insert into job_type_change (id, description)
values  (1, 'Change from YM to 240v Rebook'),
        (2, 'Change from COT to YM')";

    }



    private function ladder_inspection(){
        return "insert into ladder_inspection (ladder_inspection_id, item, active, deleted, created_date)
values  (1, 'Is the Ladder free from any modifications, such as being painted, shortened', 1, 0, '2016-08-22 00:00:00'),
        (2, 'Are the stiles (uprights) free of damage or excessive wear (particularly at the head or foot of the ladder', 1, 0, '2016-08-22 00:00:00'),
        (3, 'Are the rungs (steps) clean and free of damage or excessive wear', 1, 0, '2016-08-22 00:00:00'),
        (4, 'Are the rungs and stiles secure and free from movement', 1, 0, '2016-08-22 00:00:00'),
        (5, 'Is the ladder free from distortion or warping that could affect its stability', 1, 0, '2016-08-22 00:00:00'),
        (6, 'Is the ladder free from damage such as cracks, corrosion degradation and dents', 1, 0, '2016-08-22 00:00:00'),
        (7, 'Are all the warnings and labels legible and in place', 1, 0, '2016-08-22 00:00:00'),
        (8, 'Has the ladder passed inspection and fit for use', 1, 0, '2016-08-22 00:00:00'),
        (9, 'Is the ladder tag and ladder ID prominently displayed on the ladder', 1, 0, '2016-08-22 00:00:00'),
        (10, 'Is the detail required on the laddertag panel complete', 1, 0, '2016-08-22 00:00:00')";

    }

    private function leave_types(){
        return "insert into leave_types (leave_type_id, leave_name, hidden, active)
values  (1, 'Annual Leave', 0, 1),
        (2, 'Sick Leave', 0, 1),
        (3, 'Carer''s Leave', 0, 1),
        (4, 'Compassionate', 0, 1),
        (5, 'Cancel Previous Leave', 0, 1),
        (6, 'Leave without pay', 1, 1);";

    }



    private function leaving_reason(){
        return "insert into leaving_reason (id, reason, display_on, active)
values  (1, 'Lost Management', 5, 1),
        (2, 'Owner Moving In', 5, 1),
        (3, 'Property Sold', 5, 1),
        (4, 'RE Moved to O/S', 2, 1),
        (5, 'Owner Moved to O/S', 2, 1),
        (6, 'Service Required Outside of SAS Scope', 4, 1),
        (7, 'Rent Roll Sold', 5, 1),
        (8, 'Moving to Other Provider', 4, 1),
        (9, 'Unhappy with SAS Service', 4, 1)";

    }



    private function lockout_kit_checklist(){
        return "insert into lockout_kit_checklist (lockout_kit_checklist_id, item, active, deleted, date_created)
values  (1, '2x Mini CB/ Residential Lockouts', 1, 0, '2016-12-14 16:07:22'),
        (2, '1x Industrial Lockout', 1, 0, '2016-12-14 16:07:22'),
        (3, '1x Pen', 1, 0, '2016-12-14 16:07:22'),
        (4, '1x Dielectric hasp', 1, 0, '2016-12-14 16:07:22'),
        (5, '2 x Non- Conductive Lock', 1, 0, '2016-12-14 16:07:22')";

    }



    private function log_titles(){
        return "insert into log_titles (log_title_id, title_name, active)
values  (1, 'New Job Created', 1),
        (2, 'New Property Added', 1),
        (3, 'Property Service Updated', 1),
        (4, 'Account Updated', 1),
        (5, 'Landlord Updated', 1),
        (6, 'Property No Longer Managed', 1),
        (7, 'Tenant Removed', 1),
        (8, 'Tenant Reactivated', 1),
        (9, 'Tenant Updated', 1),
        (10, 'New Tenant Added', 1),
        (11, 'Password', 1),
        (12, 'Job Pending', 1),
        (13, 'Account Deactivated', 1),
        (14, 'Agency Profile Update', 1),
        (15, 'Escalate Job', 1),
        (16, 'Account Restored', 1),
        (17, 'User Account Added', 1),
        (18, 'Property Manager Updated', 1),
        (19, 'PDF download', 1),
        (20, 'Job Cancelled', 1),
        (21, 'Report Downloaded', 1),
        (22, 'Report Displayed', 1),
        (23, 'Password Reset', 1),
        (24, 'Password Updated', 1),
        (25, 'Invitation Sent', 1),
        (26, 'Audit Properties', 1),
        (27, 'Merged Certificates', 1),
        (28, 'User Type Updated', 1),
        (29, 'No Tenant Letters Sent', 1),
        (30, 'Test Smoke Alarm SMS Sent', 1),
        (31, 'Test Smoke Alarm Letter Sent', 1),
        (32, 'Door Knock Booked', 1),
        (33, 'Rebook (DHA)', 1),
        (34, 'Rebook (240v)', 1),
        (35, 'Rebook', 1),
        (36, 'Credit Request', 1),
        (37, 'Sales Snapshot', 1),
        (38, 'Property Restored', 1),
        (39, 'Upgrade Quote', 1),
        (40, 'SMS sent', 1),
        (41, 'File Upload', 1),
        (42, 'Price Changed', 1),
        (43, 'Payment', 1),
        (44, 'Job assigned to technician', 1),
        (45, 'Statement', 1),
        (46, 'Agency Update', 1),
        (47, 'Agency Payments', 1),
        (48, 'Action Required Update', 1),
        (49, 'Move On Hold Jobs', 1),
        (50, 'Move Future Start Date Jobs', 1),
        (51, 'Service Due', 1),
        (52, 'API Combined Post', 1),
        (53, 'Welcome SMS', 1),
        (54, 'Welcome Email', 1),
        (55, 'No Tenant Letter Sent', 1),
        (56, 'Key access', 1),
        (57, 'Tenant Welcome Email', 1),
        (58, 'Agency Notification Email', 1),
        (59, 'Job type updated', 1),
        (60, 'Tenant Welcome SMS', 1),
        (61, 'Sync Alarms', 1),
        (62, 'Job Incomplete', 1),
        (63, 'Job Update', 1),
        (64, 'Keys Not Collected', 1),
        (65, 'Property Update', 1),
        (66, 'Merge Invoice Sent', 1),
        (67, 'Refund Request', 1),
        (68, 'Snooze Day', 1),
        (69, 'PMe API', 1),
        (70, 'Palace API', 1),
        (71, 'New Agency Added', 1),
        (72, 'Job Status Updated', 1),
        (73, 'New Alarm', 1),
        (74, 'Job Not Completed', 1),
        (75, 'Techsheet Completed', 1),
        (76, 'SMS Replies', 1),
        (77, 'SMS Template Updated', 1),
        (78, 'Email Sent', 1),
        (79, 'Maintenance Program', 1),
        (80, 'Sales Emails', 1),
        (81, 'Service Price Updated', 1),
        (82, 'Alarm Price Updated', 1),
        (83, 'Alarm approved/unapproved', 1),
        (84, 'Agency Changed', 1),
        (85, 'API Integration', 1),
        (86, 'Sales Report', 1),
        (87, 'Email Template Updated', 1),
        (88, 'Is Payable', 1),
        (89, 'OurProperty API', 1),
        (90, 'Console API', 1),
        (91, 'PropertyTree API', 1),
        (92, 'Property Moved', 1),
        (93, 'Phone Call', 1),
        (94, 'E-mail', 1),
        (95, 'Other', 1),
        (96, 'Work Order', 1),
        (97, 'Duplicate Property', 1),
        (98, 'Recreate Bundle Services', 1),
        (99, 'Job Moved', 1),
        (100, 'Job Deleted', 1),
        (101, 'Unavailable', 1),
        (102, 'Problematic', 1),
        (103, 'SMS Received', 1),
        (104, 'Payment Taken', 1),
        (105, 'Airtable', 1),
        (106, 'Job Restored', 1),
        (107, 'Safety Switch Update', 1),
        (108, 'Paid/Unpaid', 1),
        (109, 'Accounts Notes', 1)";

    }



    private function main_log_type(){
        return "insert into main_log_type (main_log_type_id, contact_type, is_show, active)
values  (1, 'Cold Call', 1, 1),
        (2, 'Cold Call In', 1, 1),
        (3, 'Conference', 1, 1),
        (4, 'E-mail', 1, 1),
        (5, 'Email - Accounts', 0, 1),
        (6, 'Follow Up', 1, 1),
        (7, 'Happy Call', 0, 1),
        (8, 'Mail-Out', 1, 1),
        (9, 'Meeting', 1, 1),
        (10, 'Other', 1, 1),
        (11, 'Other - Accounts', 0, 1),
        (12, 'Pack Sent', 1, 1),
        (13, 'Phone Call', 1, 1),
        (14, 'Phone Call - Accounts', 0, 1),
        (15, 'Pop In', 1, 1),
        (16, 'Complaint', 1, 1)";

    }



    private function maintenance(){
        return "insert into maintenance (maintenance_id, name, status)
values  (1, 'Our Property', 1),
        (2, 'Maintenance Manager', 1),
        (3, 'Other', 1),
        (4, 'PropertyMe', 0),
        (5, 'Tapi', 1),
        (6, 'Palace', 0),
        (7, 'Bricks and Agent', 1)";

    }



    private function permission_list(){
        return "insert into permission_list (id, description, active)
values  (1, 'Can edit VJD completed jobs', 1),
        (2, 'Can delete property', 1),
        (3, 'Can edit VJD price when job is Merged or Completed', 1),
        (4, 'Can edit VJD accounts tab', 1),
        (5, 'Prohibited from web CRM login', 1)";

    }
	private function log_title_usable_pages(){
		return "insert into log_title_usable_pages (id, log_titles_id, show_in)
values  (1, 40, 1),
(2, 93, 1),
(3, 94, 1),
(4, 95, 1),
(5, 96, 1),
(6, 97, 1),
(7, 101, 1),
(8, 102, 1),
(9, 103, 1),
(10, 104, 1),
(11, 105, 1);";

	}

	private function sms_api_type(){
		return "insert into sms_api_type (sms_api_type_id, type_name, created_date, active, category, body)
values  (1, 'No Answer', '2017-12-21 16:18:15', 1, 'No Answer', 'Hi, 

On behalf of {agency_name}, we, Smoke Alarm Solutions (SAS) are dedicated to completing the essential servicing at {p_address}.  We have been trying to reach you to schedule a convenient time for the testing, but have been unable to do so. To avoid any delays or potential hazards, we kindly ask you to contact us urgently at 1300 852 301 to arrange a suitable time for the testing or provide us with key access. 

Thank you, SAS Team'),
        (2, 'No Answer (Yes/No SMS Reply)', '2017-12-21 16:18:15', 1, 'No Answer', 'Hi, 

On behalf of {agency_name}, we, Smoke Alarm Solutions (SAS) are dedicated to completing the essential servicing at {p_address}. We have the following booking available {job_date}@ {time_of_day}. To secure this booking, please reply \"YES.\" If this is unsuitable, please share your preferred dates and times. 

Thank you, SAS Team 1300 852 301
'),
        (3, 'No Answer (Keys SMS Reply) ', '2017-12-21 16:18:15', 1, 'No Answer', 'Hi, 

On behalf of {agency_name}, we, Smoke Alarm Solutions (SAS) are dedicated to completing the essential servicing at {p_address}. We have the following booking available {job_date} @ {time_of_day}. To secure this booking, please reply \"YES.\" If you''re unable to be present during our servicing, SAS can collect keys from {agency_name}. To confirm this, please reply \"KEYS\". 

Thank you, SAS Team 1300 852 301'),
        (4, 'No Show', '2017-12-21 16:18:15', 1, 'Cancel', 'We attended your property today to check your smoke alarms as per our appointment and nobody was home. Please call SAS 1300 852 301 to reschedule an appointment. Please note, that your agency may be advised of the missed appointment. '),
        (5, 'Cancel (Tech Called Away) ', '2017-12-21 16:18:15', 1, 'Cancel', 'SAS apologise for any inconvenience caused, but unfortunately, we have to cancel our appointment for today as our technician has been called away unexpectedly. We understand how frustrating this can be and want to assure you that we''re doing everything possible to reschedule your service as soon as possible.

We will be in touch shortly to discuss a new appointment that suits you. Please don''t hesitate to contact us if you have any questions or concerns.

Kind regards, SAS Team 1300 852 301'),
        (6, 'Cancel (Sick Tech EN)', '2017-12-21 16:18:15', 1, 'Cancel', 'SAS regret to inform you that we are unable to attend your property today via Entry Notice as our technician is unwell. We understand that this may be inconvenient for you and we apologize for any inconvenience caused.
We will re-issue the Entry Notice at a later date, and we''ll be in touch with you to arrange a new appointment as soon as possible.
If you have any questions or concerns, please don''t hesitate to contact us. We appreciate your patience and understanding in this matter.

Kind regards, SAS Team 1300 852 301'),
        (7, 'Cancel (Sick Tech)', '2017-12-21 16:18:15', 1, 'Cancel', 'SAS are unable to attend your property for our appointment today due to the Technician being unwell. We will call you again to schedule a new appointment. Sorry for any inconvenience caused. 1300 852 301'),
        (8, 'Escalation', '2017-12-21 16:18:15', 1, 'Customer Service', 'Hi there,

SAS must carry out mandatory testing of the {serv_name} at {p_address}, and we have been unsuccessful in scheduling an appointment with you. We have now escalated this matter and noted it on file with {agency_name}.

Please call SAS urgently on {tenant_number} to arrange a suitable time for the testing or to provide us with key access. We appreciate your cooperation in this matter.

Kind regards, SAS Team 1300 852 301'),
        (9, 'Email Notice (Email EN)', '2017-12-21 16:18:15', 1, 'Entry Notice', 'Hi There, 

I hope this finds you well,
Smoke Alarm Solutions (SAS) have issued you an Entry Notice to test the {serv_name} at {p_address} on {job_date} and will collect the keys from {agency_name}. Email may appear in Spam/Junk folders. View this Entry Notice by clicking this link {en_link} .Please ensure all rooms inside the property are accessible by our Technician. As our technicians will be attending your property, we kindly ask that if you are sick or have come into contact with somebody who has or is being tested for COVID-19 or if you are in quarantine let our team know.  {tenant_number} please also, do not hesitate to contact us should you wish to discuss further.

We hope you have a lovely day ahead.'),
        (10, 'Entry Notice (SMS EN)', '2017-12-21 16:18:15', 1, 'Entry Notice', 'Smoke Alarm Solutions (SAS) have issued you an Entry Notice to test the {serv_name} at {p_address} on {job_date} and will collect the keys from {agency_name}. Click here to view {en_link} . Please ensure all rooms inside the property are accessible by our Technician. As our technicians will be attending your property, we ask that if you are sick or have come into contact with somebody who has or is being tested for COVID-19 or if you have travelled overseas within the last 14 days who is unwell to let our team know. {tenant_number}'),
        (11, 'Tech Running Late', '2017-12-21 16:18:15', 1, 'Operations', 'Your SAS technician has been held up and is running late. They will be there ASAP. We apologise for any inconvenience caused. 

Kind regards, SAS Team 1300 852 301'),
        (12, 'Unable to Complete (No Keys)', '2017-12-21 16:18:15', 1, 'Unable to Complete', 'This is a courtesy to advise  SAS were unable to complete your smoke alarm service today because {agency_name} did not have keys available. We will contact you shortly to reschedule. {tenant_number}'),
        (13, 'Unable to Complete (Keys Don''t Work)', '2017-12-21 16:18:15', 1, 'Unable to Complete', 'SAS were unable to complete your smoke alarm service today because the keys provided by {agency_name} didn''t work. We will contact you shortly to reschedule. 1300 852 301 '),
        (14, 'Unable to Complete (Unable to Access)', '2017-12-21 16:18:15', 1, 'Unable to Complete', 'Our technician attended your property today to check your smoke alarms as per our appointment however we were unable to gain access. Please call SAS {tenant_number} to reschedule. We have notified {agency_name} of this issue.'),
        (15, 'SMS Reply (Time-Slot FULL)', '2017-12-21 16:18:15', 1, 'Customer Service', 'Thank you for your reply. Unfortunately, this time slot is currently full. We apologise for any inconvenience this may have caused you. Please reply to this SMS and let us know your availability, including which day/s and time/s would work for you, and we will do our best to accommodate your request. 

Kind regards, SAS Team 1300 852 301'),
        (16, 'SMS Reply (Booking Confirmed NO KEYS)', '2017-12-21 16:18:15', 1, 'Customer Service', 'Thank you for scheduling an appointment with Smoke Alarm Solutions. This message is to confirm that our technician will be servicing your {serv_name} at {p_address} on {job_date} at {time_of_day}. Please ensure that someone is home to allow our technician access to each bedroom in order to assess the property for compliance and safety purposes. If you need to make any changes to the appointment or have any questions, please contact us.

Kind regards, SAS Team 1300 852 301'),
        (17, 'SMS (Custom)', '2017-12-21 16:18:15', 1, 'SMS', null),
        (18, 'SMS (Thank You)', '2017-12-21 16:27:25', 1, 'Z - Thank You SMS Template - DO NOT TOUCH', 'Hello, Thank you for allowing Smoke Alarm Solutions to service your property today. We strive to provide the best service we can. If you have a free moment we would love to hear your feedback about our service via a Google Review via this link: https://g.page/r/CX5UkUBiJs6TEB0/review or an SMS reply. Have a great day!'),
        (19, 'SMS (Reminder)', '2017-12-21 16:27:25', 1, 'Reminder for booked jobs', '{booked_with}, SAS will be testing the {serv_name} at {p_address} on {job_date} between {time_of_day}, Please ensure all rooms inside the property are accessible by our Technician. If this is no longer suitable, please let our team know by replying to this SMS or calling our friendly team. 

Kind regards, SAS Team 1300 852 301'),
        (20, 'SMS (No Longer Tenant)', '2018-01-03 12:23:10', 1, 'Customer Service', 'Thank you for letting us know that you no longer live at {p_address}. We appreciate you taking the time to inform us of this change.

We will contact {agency_name} to collect the details of the new tenant details.'),
        (21, 'Cancel (Service no longer required)', '2018-01-16 13:40:17', 1, 'Cancel', 'SAS would like to apologise as we will have to cancel our scheduled booking as {agency_name} has advised this service is not required at this stage. We apologise for any inconvenience caused. 

Kind regards, SAS Team 1300 852 301'),
        (22, 'SMS Reply (Booking Confirmed KEYS)', '2018-01-17 10:58:59', 1, 'Customer Service', 'Thank you for scheduling an appointment with Smoke Alarm Solutions (SAS). This message is to confirm that we will be collecting keys from {agency_name} on {job_date} to service the smoke alarms at {p_address}. Please ensure that access is available, as our technician will need to assess each bedroom in the property to ensure that everything is in compliance with safety regulations.

If you have any questions or concerns, please do not hesitate to contact us at {tenant_number}.'),
        (23, 'Unable to Complete (Dog)', '2018-02-12 10:18:06', 1, 'Unable to Complete', 'SAS were unable to complete your {serv_name} service today due to an unrestrained dog on the premises. We will contact you shortly to reschedule. 

Kind regards, SAS Team 1300 852 301'),
        (24, 'Send Letters', '2018-03-16 10:52:01', 1, null, 'SAS have been asked to test the {serv_name} at the property you occupy. Our staff will contact you shortly to make an appointment. Any questions {tenant_number}'),
        (25, 'Unable to Complete (No FOB or Security Tag)', '2018-04-25 11:43:20', 1, 'Unable to Complete', 'This is a courtesy to advise you that SAS were unable to complete your {serv_name} service today because {agency_name} was not able to supply the FOB or security key to gain entry to your property. We will contact you shortly to reschedule.

Kind regards, SAS Team 1300 852 301'),
        (26, 'Preferred (Time/Date) KEYS ALLOWED', '2018-05-03 15:28:20', 1, 'Preferred ', 'Hello,

SAS is responsible for {serv_name} testing on behalf of {agency_name} to ensure compliance and safety at {p_address}. We provide weekday appointments between 7 am and 3 pm, requiring only a minimum of 1 hour. Please share your preferred days and times, and we''ll make every effort to work with your schedule.

Alternatively, please let our team know if you wish for us to collect the keys from {agency_name} to complete this.

Kind regards, SAS Team 1300 852 301
'),
        (27, 'No Answer (Keys SMS Reply) (NZ)', '2018-07-10 13:22:22', 1, null, 'SAS are trying to contact you to book in [date] @ [time] to service the {serv_name} at {p_address} on behalf of {your_agency}. Please reply YES to confirm someone will be home for this appointment or reply KEYS and we will collect keys from {your_agency} {tenant_number}'),
        (28, 'No Answer (Yes/No SMS Reply) (NZ)', '2018-07-26 10:52:45', 1, null, 'SAS are trying to contact you to book in [date] @ [time] to service the {serv_name} at {p_address} on behalf of {your_agency}. Please reply YES to confirm someone will be home for this appointment {tenant_number}'),
        (29, 'Cancel (Tenant Request)', '2018-10-17 12:39:32', 1, 'Cancel', 'Hello, this is a courtesy SMS to advise your appointment has been cancelled as per your request today. Please note that we have notified {agency_name}. Thank you and have a lovely day. 

Kind regards, SAS Team 1300 852 301'),
        (30, 'Preferred Time Given - SAS response', '2019-04-10 13:57:28', 1, 'Preferred', 'Thank you for providing us with your preferred times for SAS to attend your property. We have taken note of your request and will do our best to meet it.

Please note that this is not a confirmed booking at this time. We will be in touch with you shortly to confirm a booking, or alternatively, you can contact us to discuss further.

Thank you, SAS Team 1300 852 301'),
        (31, 'Query To Access Bedrooms', '2019-08-09 15:09:24', 1, 'Customer Service', 'Thank you for your reply. We would like to inform you that {agency_name} and the Owner have requested we attend to survey the property to ensure that it has the correct number of alarms required. This is done in order to ensure that the property is compliant with safety regulations and to provide peace of mind for both the tenant and the property owner. If you have any further questions or concerns, please do not hesitate to contact us.

Kind regards, SAS Team 1300 852 301'),
        (32, 'Tech late No Show', '2019-11-12 10:41:15', 1, 'No Answer', 'SAS would like to apologise for our missed appointment today, Our Technician was held up at a property, however, he did attend your property, though nobody was home at the time of attendance. We apologise for any inconvenience. Someone from our office will call you again shortly to reschedule.

Kind regards, SAS Team 1300 852 301'),
        (33, 'Cancel (No Keys at Agency) ', '2020-03-17 16:41:18', 1, 'Cancel', 'SAS would like to apologise as we will have to cancel our scheduled booking as {agency_name} has advised they do not currently have any keys available for your property. We apologise for any inconvenience caused and will contact you shortly to reschedule. 

Kind regards, SAS Team 1300 852 301'),
        (34, 'SMS Reply (Booking Confirmed) COVID-19', '2020-03-20 16:30:14', 0, 'SMS Reply ', 'Thank you, This is to confirm the appointment made today for the {job_date} @ {time_of_day} to service the {serv_name} at {p_address}. Please ensure someone is home to allow access. As our technicians will be attending your property, we ask that if you are experiencing flu-like symptoms or have come into contact with somebody who has or is being tested for COVID-19 to let our team know.  SAS {tenant_number}'),
        (35, 'Cancel (Tenant Request) COVID-19', '2020-03-23 00:21:49', 0, 'Cancel', 'This is a confirmation to advise we have cancelled your service as per your request and placed your service on hold for  7 days. We will contact you after this time to reschedule. SAS {tenant_number}'),
        (36, 'Cancel (Agents request) COVID-19', '2020-03-27 15:33:17', 0, 'Cancel', 'Smoke Alarm Solutions have been in contact with your Agency and they have advised to cancel your service for now and place on hold for 7 days. We will contact you after this time to reschedule. SAS {tenant_number}'),
        (37, 'Unable to Complete (No adult present)', '2020-05-08 08:18:26', 1, 'Unable to Complete', 'Our technician attended your property today to check your smoke alarms as per our appointment however we were unable to gain access due to no adult being present. Please call SAS 1300 852 301 to reschedule. We have notified {agency_name} of this issue.'),
        (38, '240v Rebook', '2020-09-01 08:31:17', 1, 'Unable to Complete', 'Thank you for allowing SAS to attend your property today, However, a SAS Electrician will need to re attend to replace the hardwired alarm in the property. We will contact you shortly to arrange this service. SAS {tenant_number}'),
        (39, 'COVID Restrictions', '2021-01-08 10:55:48', 0, 'Cancel', 'Due to COVID-19 imposed restrictions in your area we have had to cancel the appointment booked for {job_date} and will reschedule we apologise for any inconvenience caused. SAS {tenant_number}'),
        (40, 'Booked preferred time', '2021-01-25 10:54:53', 1, 'Preferred', 'SAS will conduct {serv_name} testing on behalf of {agency_name}. We''ve secured an available slot on {job_date} at {time_of_day} based on your updated availability. If this doesn''t work, please share a new preferred time/date or reach us on 1300 852 301'),
        (41, 'Unable to Complete (Flooding)', '2021-03-22 09:48:40', 1, 'Unable to Complete', 'SAS would like to apologise, due to continuous rainfall and flooding our Technician is unable to make your scheduled appointment. We apologise for any inconvenience caused and we will contact you shortly to rebook a suitable time. Kind regards SAS {tenant_number}'),
        (42, 'COVID-19 Essential Service Reminder', '2021-03-29 15:27:05', 0, 'Temp reminder ', 'In light of Government response to recent COVID-19 reports; please be advised that SAS are an essential service and are permitted to continue servicing the smoke alarms in your property. This SMS is to confirm your booking for tomorrow, however, given the current COVID-19 circumstance, if you wish to not proceed, please call SAS ASAP so that we can reschedule. Kind regards SAS {tenant_number}'),
        (43, 'Cancel (Unforeseen Circumstances EN)', '2021-04-22 07:58:40', 1, 'Cancel', 'SAS are unable to attend your property today via Entry Notice, due to unforeseen circumstances, we will re-issue the Entry Notice at a later date. Sorry for any inconvenience caused. 

Kind regards, SAS Team 1300 852 301'),
        (44, 'Cancel (Unforeseen Circumstances)', '2021-04-22 07:59:44', 1, 'Cancel', 'SAS are unable to attend your property for our appointment today due to unforeseen circumstances. We will call you again to schedule a new appointment. Sorry for any inconvenience caused. 

Kind regards, SAS Team 1300 852 301'),
        (45, 'Cancel Cancellation SMS ', '2021-04-28 10:06:25', 0, 'Apology ', 'Good morning, SAS would like to apologize. We sent a notification to cancel our scheduled attendance tomorrow, this was done so incorrectly. Please note our Technician will still attend as per our agreed appointment. Kind regards SAS{tenant_number}'),
        (46, 'Cancel Cancellation ', '2021-06-28 18:09:03', 1, 'Apology', 'Hello,

We wanted to let you know that there was an error in the SMS message sent earlier today regarding your scheduled service. We apologize for any confusion or inconvenience this may have caused.

Please be assured that your scheduled service is still planned to go ahead as originally arranged. We value your business and are committed to providing you with high-quality service.

If you have any questions or concerns about your service, please don''t hesitate to reach out to our team at 1300 852 301. We are always here to help.

Best regards,
SAS Team'),
        (47, 'IC Upgrade Entry Notice', '2021-11-10 15:31:28', 1, 'Entry Notice', 'Hi There, 

Smoke Alarm Solutions (SAS) has scheduled a mandatory smoke alarm inspection for your property, {p_address} on {job_date} and will collect the keys from {agency_name}. You can access the Entry Notice directly by clicking this link: {en_link} . Please ensure that all bedrooms within the property are easily accessible, as we will be installing alarms in each of them. If you have any questions or require further information, please don''t hesitate to contact us. 

Kind regards, SAS Team 1300 852 301.'),
        (48, 'Sales upgrade', '2021-11-18 15:58:37', 0, 'Agency Sales SMS', 'No homes can be sold after January 1 without upgraded smoke alarms. SAS has the solution. Visit https://bit.ly/qldsellerscompliance or call 1300 852 301

'),
        (49, 'Sales upgrade Template test', '2021-11-23 11:19:22', 0, 'Agency Sales SMS', 'test'),
        (50, 'test', '2022-01-11 13:05:55', 0, 'test', 'test'),
        (51, 'Requesting Preferred Time ', '2022-07-18 12:45:23', 1, 'Preferred ', 'Hello,

SAS need to complete testing of the Smoke Alarms on behalf of {agency_name}. Appointments are available between 7am - 3pm weekdays, with a minimum 1 hour time-frame required. Please reply to this message and advise which day/s and time you are available. We will do our best to meet these. 

Thank you, Smoke Alarm Solutions (SAS). 13 51 22 99'),
        (52, 'Hush - Brooks', '2022-07-18 15:39:30', 1, 'Hush Alarm', 'Hello, 

Thank you for your call today. We are in the process of scheduling a technician to address the issue at your property. In the meantime, please follow these steps to temporarily silence the alarms:

1. Locate the alarm that is sounding and has a flashing red LED.
2. Press the test/hush button briefly to silence the alarms for 10 minutes. 

After 10 minutes, the alarms should return to their normal functionality. If the alarm continues to sound after this period, please repeat the above steps.

Rest assured, we will contact you as soon as we have a technician available to attend your property. If you have any questions or require further information, please don''t hesitate to contact us. 

Kind regards, SAS Team 1300 852 301.'),
        (53, 'Preferred (Time/Date) NO KEYS ALLOWED', '2022-08-16 14:26:29', 1, 'Preferred', 'Hello,

SAS need to complete testing of the {serv_name} on behalf of {agency_name}. Appointments are available between 7 am - 3 pm on weekdays, with a minimum 1-hour time-frame required. Please reply to this message and advise which day/s and time you are available. We will do our best to meet these. 

Thank you, SAS Team 1300 852 301'),
        (54, 'Allocate - Fix or Replace', '2022-08-22 16:34:47', 1, 'Operations', 'Hello,

SAS has been notified of the beeping alarm in your home. You can find instructions to silence it here: https://www.smokealarmsolutions.com.au/false-alarms/

Our team will contact you shortly to arrange a repair appointment for your alarms.

Kind regards, SAS Team 1300 852 301'),
        (55, 'Pre-Booking Reminder SMS', '2022-09-27 14:06:28', 1, 'Z - CRON TEMPLATE DO NOT TOUCH', 'Hi There. Your scheduled Smoke Alarm service with Smoke Alarm Solutions (SAS) is coming up for {p_address}. Our team will be in contact to arrange your scheduled service. If you no longer reside at {p_address} please advise our team via SMS. Thank you.'),
        (56, 'Customer Service-  Overdue Jobs', '2022-10-27 10:30:01', 1, 'Customer Service', 'Hi there!

We hope this message finds you well. SAS has been trying to get in touch with you to schedule a smoke alarm service for {p_address}, but we haven''t been able to secure a booking yet.

Please let us know your preferred times within the next fortnight, and we''ll get in touch with you to confirm a booking. If you won''t be available, kindly let us know if we can collect keys from your agency to complete the service.

We appreciate your prompt response and look forward to serving you soon!

Kind regards, SAS Team 1300 852 301'),
        (57, 'HUME BOOKING', '2022-11-10 13:51:36', 0, 'Operations', 'SAS are contacting you on behalf of  {agency_name} to complete {serv_name} at your property {p_address}. 
An appointment has been booked in for 11/11/2022 between 7-3pm. Please ensure someone is home to allow access. 

Smoke Alarm Solutions
{tenant_number}'),
        (58, 'Agency Staff User 2FA Code Request', '2022-11-24 16:45:58', 1, '', 'SAS: Your Security Code is {agency_staff_2fa_code}
'),
        (59, 'Google Review', '2022-12-21 16:41:53', 1, 'Customer Service', 'Hi There!

We wanted to take a moment to thank you for your recent feedback about our services. It truly means a lot to us that we were able to exceed your expectations and provide you with a positive experience.

Your feedback is invaluable to us and we appreciate your taking the time to share your thoughts. As a small gesture of our gratitude, we kindly ask if you could take a few moments to leave a quick review via the below link:

https://g.page/r/CX5UkUBiJs6TEB0/review

If you have any further questions or concerns, please don''t hesitate to contact us at 1300 852 301.

Warm regards,

Charlotte B
Customer Experience Manager
SAS
'),
        (60, 'Customer Service - Sales IC Preferred Time', '2023-02-22 16:44:41', 1, 'Preferred', 'Smoke Alarm Solutions (SAS) need to complete a smoke alarm upgrade at the property address {p_address}. Appointments are available between 7 am - 3 pm on weekdays with a minimum of 1-hour time-frame required. Please reply to this SMS and advise which day/s and time you are available. 

If you have any questions, please call on 1300 852 301 to discuss further. Thanks.'),
        (62, 'Customer Service - Sales IC Available Appointment', '2023-03-03 15:58:06', 1, 'Customer Service', 'Thank you for choosing SAS to complete your Smoke Alarm installation. At Smoke Alarm Solutions (SAS), we take your safety seriously. That''s why we offer professional installation of interconnected Smoke Alarms in your property. We have availability on {job_date} at {time_of_day} to install the {serv_name} at {p_address}.

Please let us know if this booking suits you{by replying with a \"YES\". If this date and time do not work for you, please let us know your preferred dates and times, and we will do our best to accommodate your schedule. 

Kind regards, SAS Team 1300 852 301'),
        (63, 'Customer Service - Sales IC Confirmed ', '2023-03-03 16:00:09', 1, 'Customer Service', 'Thank you for scheduling an appointment with us and choosing SAS. This message is to confirm that our technician will be installing your {serv_name} at {p_address} on {job_date} at {time_of_day}. Please ensure that someone is home to allow our technician access to each bedroom in order to assess the property for compliance and safety purposes. If you need to make any changes to the appointment or have any questions, please contact us.

Kind regards, SAS Team 1300 852 301'),
        (64, 'SMS Reply (Booking Confirmed Keys on Site)', '2023-03-07 14:56:04', 1, 'Customer Service', 'Thank you for scheduling an appointment with Smoke Alarm Solutions (SAS). This message is to confirm that we will be servicing the smoke alarms at {p_address}. Please ensure that access is available as previously arranged, as our technician will need to assess each bedroom in the property to ensure that everything is in compliance with safety regulations. If you have any questions or concerns, please do not hesitate to contact us.

Kind regards, SAS Team 1300 852 301'),
        (65, 'Private - No Answer offering Appointment ', '2023-03-09 10:07:06', 1, 'No Answer', 'Hello, At Smoke Alarm Solutions (SAS), we are committed to ensuring your safety by conducting mandatory testing. SAS need to complete testing of the {serv_name}. We have the following booking available: >DATE< between >TIME< to service the {serv_name} at {p_address}. Please reply \"YES\" if this booking suits you. If this does not suit you, please reply with your preferred dates/times. 

Kind regards, SAS Team 1300 852 301'),
        (66, 'Hush - Legrand', '2023-03-15 10:21:11', 1, 'Hush Alarm', 'Hello,

Thank you for your call today. We are in the process of scheduling a technician to address the issue at your property. In the meantime, please follow these steps to temporarily silence the alarms:

1. Locate the alarm that is sounding and has a flashing red LED.
2. Press the test/hush button briefly to silence the alarms for 10 minutes. 

After 10 minutes, the alarms should return to their normal functionality. If the alarm continues to sound after this period, please repeat the above steps.

Rest assured, we will contact you as soon as we have a technician available to attend your property. If you have any questions or require further information, please don''t hesitate to contact us. 

Kind regards, SAS Team 1300 852 301.'),
        (67, 'Hush - Emerald Planet ', '2023-03-15 10:22:55', 1, 'Hush Alarm', 'Hello,

Thank you for your call today. We are in the process of scheduling a technician to address the issue at your property. In the meantime, please follow these steps to temporarily silence the alarms:

1. Find the alarm with the flashing red light (the one beeping).
2. Quickly press the Hush/Test button 6 times within 5 seconds.
3. Wait 3-5 seconds for the red light to come on and start flashing again. Then, press the Hush/Test button 6 more times within 5 seconds. You''ll hear a final chirp to confirm the alarm is turned off.

After 10 minutes, the alarms should return to their normal functionality. If the alarm continues to sound after this period, please repeat the above steps.

Rest assured, we will contact you as soon as we have a technician available to attend your property. If you have any questions or require further information, please don''t hesitate to contact us. 

Kind regards, SAS Team 1300 852 301.'),
        (68, 'Hush - Red Alarms', '2023-03-15 10:29:44', 1, 'Hush Alarm', 'Hello, 

Thank you for your call today. We are in the process of scheduling a technician to address the issue at your property. In the meantime, please follow these steps to temporarily silence the alarms:

1. Locate the alarm that is sounding and has a flashing red LED.
2.  Press and hold the TEST/HUSH button for 3 seconds. 

After 10 minutes, the alarms should return to their normal functionality. If the alarm continues to sound after this period, please repeat the above steps.

Rest assured, we will contact you as soon as we have a technician available to attend your property. If you have any questions or require further information, please don''t hesitate to contact us. 

Kind regards, SAS Team 1300 852 301.'),
        (69, 'Hush - Clipsal', '2023-03-15 11:39:38', 1, 'Hush Alarm', 'Hello, 

Thank you for your call today, we are organising a technician to attend the property. However, in the mean time please follow these steps to hush the alarms. 

1. Locate the alarm that is sounding and has a flashing red LED.

2.  Press and hold the TEST/HUSH button for 20 seconds. 

After 10 minutes, these alarms should return to normal functionality. If after 10 minutes, the alarm sounds. Please repeat above steps.

If this continues to persist, please contact SAS for further instruction.

We will be in contact once we have an available technician to attend the property. 

 Thank you, Smoke Alarm Solutions (SAS). {tenant_number}'),
        (70, 'Hush - Quell', '2023-03-15 11:41:16', 1, 'Hush Alarm', 'Hello, 

Thank you for your call today. We are in the process of scheduling a technician to address the issue at your property. In the meantime, please follow these steps to temporarily silence the alarms:

1. Locate the alarm that is sounding and has a flashing red LED.
2.  Press and hold the TEST/HUSH button for 15 seconds. 

After 10 minutes, the alarms should return to their normal functionality. If the alarm continues to sound after this period, please repeat the above steps.

Rest assured, we will contact you as soon as we have a technician available to attend your property. If you have any questions or require further information, please don''t hesitate to contact us. 

Kind regards, SAS Team 1300 852 301.'),
        (71, 'Hush - Life Saver', '2023-03-15 11:45:23', 1, 'Hush Alarm', 'Hello, 

Thank you for your call today. We are in the process of scheduling a technician to address the issue at your property. In the meantime, please follow these steps to temporarily silence the alarms:

1. Locate the alarm that is sounding and has a flashing red LED.
2.  Press and hold the TEST/HUSH button for 3 seconds. 

After 10 minutes, the alarms should return to their normal functionality. If the alarm continues to sound after this period, please repeat the above steps.

Rest assured, we will contact you as soon as we have a technician available to attend your property. If you have any questions or require further information, please don''t hesitate to contact us. 

Kind regards, SAS Team 1300 852 301.'),
        (72, 'Hush - Cavius ', '2023-03-15 11:47:13', 1, 'Hush Alarm', 'Hello, 

Thank you for your call today. We are in the process of scheduling a technician to address the issue at your property. In the meantime, please follow these steps to temporarily silence the alarms:

1. Locate the alarm that is sounding and has a flashing red LED.
2.  Press the TEST/HUSH button.

After 10 minutes, the alarms should return to their normal functionality. If the alarm continues to sound after this period, please repeat the above steps.

Rest assured, we will contact you as soon as we have a technician available to attend your property. If you have any questions or require further information, please don''t hesitate to contact us. 

Kind regards, SAS Team 1300 852 301.'),
        (73, 'Automatic Preferred Time SMS - DO NOT USE', '2023-08-04 15:09:54', 1, 'Preferred', 'Hello,

Smoke Alarm Solutions (SAS) are required to test {serv_name} for {agency_name}. 

**Appointments are available on weekdays from 7 am to 3 pm, with a minimum of 1-hour needed.**

- Please ** SMS reply** to this message to advise your preferred date and time.

- We''ll try to accommodate your request, but please note this is NOT a confirmed booking.

- You''ll receive a confirmation SMS if your preferred schedule works.

If you would prefer to call, please contact us on {tenant_number}.

Thank you for your cooperation, we look forward to hearing from you.'),
        (74, 'Offering an appointment', '2023-08-08 08:52:17', 1, 'Customer Service', 'Hello, At Smoke Alarm Solutions (SAS), we are committed to ensuring your safety by conducting mandatory testing. SAS need to complete testing of the {serv_name}. We have the following booking available: >DATE< between >TIME< to service the {serv_name} at {p_address}. Please reply \"YES\" if this booking suits you. If this does not suit you, please reply with your preferred dates/times. 

Kind regards, SAS Team 1300 852 301'),
        (75, 'Tenant Request EN to be cancelled', '2023-09-29 11:20:37', 1, 'Call Centre', 'Good afternoon/morning,

Thank you for taking the time to chat with me today regarding the Entry Notice you received from Smoke Alarm Solutions (SAS) relating to the mandatory smoke alarm service required at the property {p_address}.

As discussed, we kindly ask that you contact your property manager to discuss your request, it''s important to note that we are unable to cancel or modify an Entry Notice without the approval of your agent.

If you have any additional questions or concerns, please feel free to reach out to us directly at 1300 852 301.

Thank you for your understanding and cooperation in this matter.

 '),
        (76, 'Invoice Payment Reminder', '2023-10-27 15:47:34', 1, 'Accounts', 'Hi, Reminder that payment for your recent Smoke Alarm service is due. An invoice has been emailed to your email address provided. Thank you, SAS team.');
        ";

	}

	private function email_templates(){
		return "insert into email_templates (email_templates_id, template_name, subject, temp_type, body, show_to_call_centre, active)
				values  
				(1, 'Operations - Fire Panel', '{property_address}', 5, 'Good morning/ afternoon, 

RE: {property_address}

Recently our Technician attended the above-mentioned property; they have advised that the property has a fire panel integrated through the premises. 
 
We do not touch these systems nor test the alarms, as we do not deem these to be a standalone unit, they should be tested by a third party (I assume STRATA?). 
 
The alarms may meet the Australian standards but we cannot verify that they are indeed compliant; therefore we cannot include these alarms as being compliant on our certificate of compliance. 

If you would like our team to reattend to install a stand-alone battery-operated unit please let us know.

Kind Regards

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au



 
', 0, 1),
        (2, 'Operations - QLD - No Safety Switch', '{property_address}', 5, 'Good morning/ afternoon, 
 
Recently Smoke Alarm Solutions attended the following rental property:

{property_address}
 
Our Technician has noted that there was no Safety Switch/s present at this property.
 
QLD Law dictates that safety switches are a mandatory requirement for rental properties.

A safety switch is a device that quickly switches off the electricity supply if an electrical fault is detected, to minimise the risk of electricity-related fires, electric shock, injury and death. For the safety of the occupants residing in this property all safety switches should be working. 

This information is for your use, and we strongly suggest you advise your client. SAS do not install Safety Switches; however we do test them when they are present.

If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au


', 0, 1),
        (3, 'Operations - ACT NSW SA - Failed Safety Switch', '{property_address}', 5, 'Good morning / afternoon, 

Recently SAS attended the following rental property:

Our Technician has noted that the safety switch failed at {property_address} on {job_date}. The safety switch was tested at the switch itself using the required test button and it has failed to trip/reset as it should. 

Our Technician was unable to ascertain why the fault has occurred. It is possible that the fault lies in the mechanical test switch itself and the actual safety switch may still work when a leakage of current occurs. The mechanical switches often fail due to their location, insects and dust build up etc.

Anytime we detect a failed safety switch we advise that an electrician attends to fault find to ensure that the safety switch and its test button are both in working order.
Please be rest assured there is still power to the property.

A safety switch is a device that quickly switches off the electricity supply if an electrical fault is detected, to minimise the risk of electricity-related fires, electric shock, injury and death. 

For the safety of the occupants residing in this property all safety switches should be working. 

This information is for your use, and I strongly suggest you discuss this situation with your client for further action. 
 
SAS do not install Safety Switches; however we do test them when they are present. 
 
If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au', 0, 1),
        (4, 'Operations - QLD - Failed Safety Switch', '{property_address}', 5, 'Good morning/afternoon, 
 
Our Technician has noted that the safety switch failed at {property_address} on {job_date}.

The safety switch was tested at the switch itself using the required test button and it has failed to trip/reset as it should. Our Technician was unable to ascertain why the fault has occurred.

It is possible that the fault lies in the mechanical test switch itself and the actual safety switch may still work when a leakage of current occurs. The mechanical switches often fail due to their location, insects and dust build up etc.

Anytime we detect a failed safety switch we forward on our findings for your records.

<b>Please be rest assured there is still power to the property.</b>
 
QLD Law dictates that Safety Switches are a mandatory requirement for rental properties.
Anytime we detect a failed safety switch we advise that an electrician attends to fault find to ensure that the safety switch and its test button are both in working order.

A safety switch is a device that quickly switches off the electricity supply if an electrical fault is detected, to minimise the risk of electricity-related fires,electric shock, injury and death. For the safety of the occupants residing in this property all safety switches should be working. 

This information is for your use, and we strongly suggest you advise your client. SAS do not install Safety Switches; however we do test them when they are present.

If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au', 0, 1),
        (5, 'Operations - Back to Base Alarm', '{property_address}', 5, 'Good morning/ afternoon, 

RE: {property_address}

Recently our technician attended the premises; they have noted that the property has a back to base alarm system integrated through the premises. 

We do not touch these systems nor test the alarms, as we do not deem theses to be a standalone unit they must be tested by the security company. Unfortunately we do not hold a security license.
 
The alarms may meet the Australian standards but we cannot verify that they are indeed compliant; therefore we cannot include theses alarms as being compliant on our certificate of compliance.

Can you please let me know if you wish for us to cancel the service or install stand alone battery operated alarms

I look forward to hearing from you. 
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au

', 0, 1),
        (6, 'sales test', 'sales test', 1, 'test test test 

{agency_name} {agency_phone_number}', 0, 0),
        (7, 't', 'test', 0, 'test', 0, 0),
        (8, 'Operations - 240V Alarm - No Power to Property', '{property_address}', 5, 'Good morning/ afternoon, 

Recently our Technician attended the following property: {property_address}.

This is a courtesy email to advise that when SAS attended the property, our Technician has advised that there is currently no power to the property. 

Because of this he has not been able to assess if there are any faults with the wiring/cables to the smoke alarm. 

Please be rest assured that even though this may be the case, we have ensured that the property is protected and the smoke alarms are operating via the back up battery.

If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au

', 0, 1),
        (9, 'Operations - Out of Scope', '{property_address}', 5, 'Good morning/ afternoon, 

Today our technician attended {property_address} to complete {service_type}
 
Our Technician has noted the following: {tech_comments}

Rectifying the situation is outside the realms of what our Electrical Technicians are employed to service at the property.

Rectifying the wiring situation will require a third party (your preferred electrician) to attend at your landlord’s expense. 

If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au

', 0, 1),
        (10, 'Accounts - ', '{property_address}', 2, '
', 0, 0),
        (11, '6. Escalate', 'Urgent Smoke Alarm Servicing at {property_address}', 9, 'Hi There,

I hope this email finds you well.

Smoke Alarm Solutions (SAS) must conduct a mandatory service of the {service_type} at {property_address}. However, we have been unable to contact you despite our numerous attempts to do so. We have escalated this matter with your agency, but it is imperative that we service the {service_type} as soon as possible.

Please call SAS urgently on {tenant_number} to arrange a mutually convenient time for the servicing of the smoke alarms or to provide us with key access to the property. Failing to comply with this request may result in a breach of the tenancy agreement.

Thank you for your immediate attention to this matter.

Kind regards, 

{user}

Smoke Alarm Solutions (SAS)

{tenant_number}', 1, 1),
        (12, '2. No Answer YES/NO Reply', 'Smoke Alarm Servicing Appointment for  {property_address}', 9, 'Hi There,

I hope this email finds you well. I am writing on behalf of {agency_name} and your landlord to inform you that Smoke Alarm Solutions (SAS) need to service the smoke alarms at your rental property located at {Property Address}.

In compliance with the legislation in Australia, it is mandatory to ensure that the smoke alarms are regularly tested and maintained for the safety of the tenants.

<strong> We currently have an available booking for the smoke alarm testing and maintenance at your property on >date< and >time<. Please reply to this email with \"YES\" to confirm the booking or call SAS on 1300 852 301 to arrange a different appointment time. </strong>

Please be informed that failing to comply with this request may result in a breach of the tenancy agreement.

Thank you for your cooperation in this matter.

Best regards,

{user}

Smoke Alarm Solutions (SAS)

1300 852 301', 1, 1),
        (13, '1. No Answer', ' Smoke Alarm Servicing Appointment for {property_address}', 9, 'Hi there,

I hope this message finds you well. I am writing on behalf of {agency_name} and your landlord to inform you that Smoke Alarm Solutions (SAS) need to service the smoke alarms at your rental property located at {property_address}.

In compliance with Australian legislation, regular maintenance and testing of smoke alarms are essential for ensuring the safety of tenants. We kindly request you to contact SAS at your earliest convenience to schedule a mutually agreeable appointment time. You may reach SAS at 1300 852 301.

Please note that failure to comply with this request may result in a breach of the tenancy agreement.

Thank you for your cooperation in this matter.

Best Regards,

{user}

<strong>Smoke Alarm Solutions (SAS)

1300 852 301</strong>', 1, 1),
        (14, '3. No Answer YES/NO Keys Reply', 'Smoke Alarm Servicing Appointment for {property_address}', 9, 'Hi There,

I hope this email finds you well. I am writing on behalf of {Your agency} and your landlord to inform you that Smoke Alarm Solutions (SAS) need to service the smoke alarms at your rental property located at {property_address}.

In compliance with the legislation in Australia, it is mandatory to ensure that the smoke alarms are regularly tested and maintained for the safety of the tenants.

<strong>We currently have an available booking for the smoke alarm testing and maintenance at your property on >date< and >time<. Please reply to this email with \"YES\" to confirm the booking or call SAS on 1300 852 301 to arrange a different appointment time. 

If it is more convenient for you, we can collect keys from your agency. If this suits you, please reply with \"KEYS\". </strong>

Please be informed that failing to comply with this request may result in a breach of the tenancy agreement.

Thank you for your cooperation in this matter.

Best Regards,

{user}

Smoke Alarm Solutions (SAS)

1300 852 301', 1, 1),
        (15, '4. Booking Confirmed', ' Confirmation of Smoke Alarm Servicing Appointment for  {property_address}', 9, 'Hi There,

I hope this email finds you well.

This email is to confirm your appointment scheduled today on {job_date} for >TIME< for Smoke Alarm Solutions (SAS) to service the {service_type} at {property_address}.

Please ensure someone is available to allow access to the property at the scheduled time.

If you need to reschedule this appointment, please call SAS on 1300 852 301 at your earliest convenience.

Thank you for your cooperation.

Best Regards,

{user}

Smoke Alarm Solutions (SAS)

{tenant_number}', 1, 1),
        (16, '5. Booking Confirmed Keys ', 'Confirmation of Smoke Alarm Servicing Appointment for {property_address}', 9, 'Hi There,

I hope this email finds you well.

This is to confirm your appointment scheduled today on {job_date} for Smoke Alarm Solutions (SAS) to service the smoke alarms at {property_address}.

We have made arrangements to collect the keys to gain access to the property. Rest assured that we will take good care of the keys.

If you have any concerns or questions, please do not hesitate to contact us at {tenant_number}.

Thank you for your cooperation.

Best Regards,

{user}

Smoke Alarm Solutions (SAS)

{tenant_number}', 1, 1),
        (17, 'test call centre', 'test call centre', 2, 'test call centre', 1, 0),
        (18, 'thalia test', '{property_address}', 2, 'bla bla bla {property_address}
{agency_accounts_email}
{agency_address}
{agency_email}
{agency_name}
{agency_phone_number}
{job_date}
{job_number}
{landlord}
{property_address}
{service_type}
{tech_comments}
{tenant_1}
{tenant_2}
{tenant_3}
{tenant_4}
{tenant_phone_number}
{user}
', 0, 0),
        (19, 'Operations - Courtesy Notification ', '{property_address}', 5, 'Good morning/afternoon, 

Today our technician attended {property_address} to complete the {service_type} service.
 
Our Technician has noted the following: {tech_comments}

It is important to note the alarm location is noted as required for minimum compliance, and there currently is no cause for alarm, however, this is a courtesy to let you know.

If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au', 0, 1),
        (20, 'test sales', 'left my card', 1, 'Hi {agency_name}

I was in your office 

thanks
{user}', 0, 0),
        (21, 'Operations - Unable to Locate Safety Switch', '{property_address}', 5, 'Good morning/ afternoon, 

Recently SAS attended the following rental property:

{property_address}.

Our technician has noted that they were unable to locate the safety switch at this address on attendance {job_date}.

A safety switch is a device that quickly switches off the electricity supply if an electrical fault is detected, to minimise the risk of electricity-related fires, electric shock, injury and death. 

If you are able to provide SAS with the location of the safety switch, SAS will note this on file for our next attendance.

 SAS do not install Safety Switches; however we do test them when they are present. 
 
If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au', 1, 1),
        (22, 'No Response Property', '{property_address}', 6, 'Good Morning/ Afternoon, 

I hope this email finds you well. Thank you for your time on the phone. 

As discussed, Smoke Alarm Solutions will change the above property status to “No Response”. This will mean that the property will not be active for servicing here with SAS until notified otherwise. 

This property is still available to view from your Agency portal by simply clicking “View Properties” > and then selecting the “Not Serviced by SAS” tab. 

The status of the property can be changed at any time on your Agency portal or by submission of work order to info@smokealarmsolutions.com.au 

If you have any additional questions, please feel free to contact our friendly Customer Service Team on 1300 852 301 or via reply to this email directly. 

I hope you have a lovely day. 

Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au
', 0, 1),
        (23, 'Customer Service - On Hold Until Tenant Found', '{property_address}', 2, 'Hi <strong>{agency_name}</strong>, 

I hope this email finds you well. Thank you for your time on the phone. 

As discussed, Smoke Alarm Solutions are seeking clarification on whether we are to service the above property or not. 

On the >DATE< it was requested that we place the property on hold in our system until further notice as >EXPLANATION< The property address has been incurring service days and is currently >JOB AGE< days out of compliance with the last service date recorded being >LAST SERVICE DATE<. 

We are requesting to change the property status to \"No Response\" as we currently resume liability. 

This will mean that the property will not be active for servicing here with SAS until notified otherwise. 

This property is still available to view from your Agency portal by simply clicking “View Properties” and then selecting the “Not Serviced by SAS” tab. 

The status of the property can be changed at any time on your Agency portal or by submission of work order to info@smokealarmsolutions.com.au 

If you have any additional questions, please feel free to contact our friendly Customer Service Team on 1300 852 301 or via reply to this email directly. 

Kind Regards,

{user}

<strong>Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au</strong> 
', 1, 1),
        (24, 'Private Service Due', '{property_address} ', 12, 'Hi There, 

This is a courtesy to advise the above property address has a {service_type} service due for renewal here at Smoke Alarm Solutions. SAS have had a price increase from the 1st August 2022, the servicing for Smoke Alarms is now $139 per annum and Smoke Alarms and Safety Switch is now $149 per annum.

Please advise if you wish for us to continue servicing the property or advise to deactivate the property and cease all communication with yourself.

Can we please request that if you would like to continue service, could you please provide updated tenant details for the property? 

Please be advised SAS will not attend, conduct and complete any services until we have verification from you in writing.

If you have any additional questions, please feel free to contact our friendly Customer Service Team on 1300 852 301 or via reply to this email directly. 

I hope you have a lovely day. 

Kind regards, 

SAS

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au', 0, 1),
        (25, 'Accounts - Agent No Longer Manage Template', 'Outstanding Invoice - {property_address}  {job_number}', 3, 'Thank you for marking this Property No Longer Managed in the Agency Portal, it’s great to see Agents using this tool.

We note that the last service on this property still has not been paid and remains outstanding on your account.  As this service was completed during your management and the invoice was issued to your agency, we ask that you follow up with the landlord for prompt payment to finalise the account.

We have attached a copy of your invoice for your convenience.

This invoice will remain outstanding on your agency account until the payment has been received.

If you would like to discuss further, please do not hesitate to contact our accounts department regarding any accounts related issues accounts@smokealarmsolutions.com.au or our friendly customer service team info@smokealarmsolutions.com.au 

Kind Regards

Accounts Department
Smoke Alarm Solutions
P 1300 416 667
E accounts@smokealarmsolutions.com.au', 0, 0),
        (26, 'Accounts - SAS No Longer Manage Template', 'Invoice Outstanding - {property_address} {job_number}', 3, 'Thank you for advising SAS that this Property is No Longer Managed by your agency.

We note that the last service on this property still has not been paid and remains outstanding on your account.  As this service was completed during your management and the invoice was issued to your agency, we ask that you follow up with the landlord for prompt payment to finalise the account.  

We have attached a copy of your invoice for your convenience.

This invoice will remain outstanding on your agency account until the payment has been received.

If you would like to discuss further, please do not hesitate to contact our accounts department regarding any accounts related issues accounts@smokealarmsolutions.com.au or our friendly customer service team info@smokealarmsolutions.com.au 

Kind Regards

Accounts Department
Smoke Alarm Solutions
P  1300 852 301
E  accounts@smokealarmsolutions.com.au', 1, 1),
        (27, 'Notification NLM after service completed ', '{property_address}', 6, 'Good morning/afternoon, 

Thank you for your email. After looking further into this for you, I can see this service has automatically renewed. 

An email was sent to the following address/es <EMAIL ADDRESS> on <FIRST RENEWAL NOTIFICATION DATE> and again on <SECOND RENEWAL NOTIFICATION DATE, to advise this service was due and to let our Team know via the Agency Portal, email or phone if the service was not to continue. 

Unfortunately, SAS did not hear from your Team and the service was renewed and completed.  Please be advised however, I have put through a request to our Accounts Department so they may determine whether this invoice can be adjusted as per your request. 

If you have any questions please do not hesitate to contact me. 

Kind regards, 

{user}

Smoke Alarm Solutions 
1300 852 301 ', 0, 1),
        (28, 'Sales - Cold call email to drop off info', 'Smoke Alarm Solutions - SAS', 1, 'Good <time of day>

My apologies for the cold call email, I know how busy you are!

We are as the name suggests, a smoke alarm compliance company specialising in rental properties, exclusively for the Real Estate Industry. 
I will be in <suburb> <what day>, would you mind if I dropped off some information regarding our service?

{user}
', 0, 1),
        (29, 'Sales - Team Meeting Follow up ', 'Team Meeting Follow up ', 1, 'Hey Team,

Thanks for another great meeting today. Here’s a quick recap of what we talked about, what we have to focus on in the coming weeks, and steps we need to take to accomplish our goals.

<INSERT RELEVANT INFO>

{user}', 0, 1),
        (30, 'Sales - Cold call follow up', 'Smoke Alarm Solutions - SAS', 1, 'Hi <Person>

Great meeting with you today and thank you for your time and having us come by the office!


<insert additional info discussed>


At any time if you have a question, query or suggestion, please feel free to call me or one of our team on 1300 852 301.

{user}







', 0, 1),
        (31, 'Operations - ACT NSW SA - No Safety Switch', '{property_address}', 5, 'Good morning/afternoon, 
 
Recently Smoke Alarm Solutions attended the following rental property:

{property_address}
 
Our Technician has noted that there was no Safety Switch/s present at this property.

A safety switch is a device that quickly switches off the electricity supply if an electrical fault is detected, to minimise the risk of electricity-related fires, electric shock, injury and death. 

For the safety of the occupants residing in this property all safety switches should be working. 

This information is for your use, and I strongly suggest you discuss this situation with your client for further action. 
 
SAS do not install Safety Switches; however we do test them when they are present. 
 
If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au', 0, 1),
        (32, 'No response to service due. Deactivate property.', '{property_address}', 12, 'Good Morning/Afternoon, 

This is a courtesy to advise the above property address has a {service_type} service due for renewal here at Smoke Alarm Solutions and has now been cancelled as we have not had a response from you. 

If you do not wish for SAS to cancel this service, please contact our friendly Customer Service Team on 1300 852 301 to reactivate this service.  

I hope you have a lovely day. 

Kind regards, 

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au', 0, 1),
        (33, 'Customer Service - Timeframe offer', '{property_address}', 2, 'SAS need to complete testing of the Smoke Alarms on behalf of {agency_name}. Appointments are available between 7am - 3pm weekdays, with a minimum 1 hour time-frame required. Please reply to this email and advise which day/s and time you are available. We will do our best to met these. Thank you, Smoke Alarm Solutions (SAS). 13 51 22 99', 1, 1),
        (34, 'Customer Service - Agency Service Due', 'Properties due for Servicing', 1, 'Good Morning/ Afternoon, 

Please find attached the csv file containing each property currently due for the service subscription renewal here at Smoke Alarm Solutions. 

Please advise which property address/es you wish for us to continue servicing  or advise which property address/es you would like for SAS to deactivate and cease future servicing for.

Please also be advised that we will not attend, conduct and complete any services until we have verification from you in writing.

If you have any additional questions, please feel free to contact our friendly Customer Service Team on 1300 852 301 or via reply to this email directly. 

I hope you have a lovely day. 

Kind regards, 

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au', 0, 0),
        (35, '7. Permission to collect keys (email AGENT and TENANT)', 'SAS Keys for {property_address}', 9, 'Hi <add tenant name>

We confirm our conversation granting us permission to collect keys from {agency_name} in order to service the smoke alarms at {property_address}

Would you kindly press ''REPLY ALL'' to this email with the word ''APPROVED''.

{agency_name} has also received a copy of this email

Thanks
{user}

Smoke Alarm Solutions (SAS) 
1300 852 301 https://smokealarmsolutions.com.au/', 1, 1),
        (36, 'Customer Service - Agency Service Due', 'Properties due for Servicing with Smoke Alarm Solutions ', 1, 'Hi There, 

This is a courtesy to advise the attached file contains property addresses with services due for renewal here at Smoke Alarm Solutions (SAS). 

Please confirm if each Owner would like SAS to continue servicing each respective property and supply current occupancy details, or simply advise to deactivate and cease all future servicing.

Please note: We are under your strict instruction to NOT attend these properties as part of our Annual Maintenance program and if we have not been granted permission to attend by <SERVICING MONTH> and complete works, SAS shall deem these properties non-compliant and accept no liability pertaining to their compliance.

If you have any additional questions, please feel free to contact our friendly Customer Service Team on 1300 852 301 or via a reply to this email directly. 

I hope you have a lovely day. 

Kind regards, 

Smoke Alarm Solutions (SAS)

1300 852 301

info@smokealarmsolutions.com.au', 0, 1),
        (37, 'Annual service created', 'Service Created', 2, 'This is a courtesy email to note SAS have create an annual service to attend the property at {property_address} as the service subscription is due.  SAS have not received instruction to attend the property at {property_address}, so to ensure compliance is kept up to date this inspection will be scheduled as soon as possible.

', 0, 1),
        (38, 'Accounts - Copy of invoice/certificate', 'Copy of invoice/certificate - {property_address}  {job_number}', 3, 'Hi,

As requested, please find attached a copy of your tax invoice for the above stated property.

If you have any questions at all, please do not hesitate to contact us.


Accounts Team
Smoke Alarm Solutions
P: 1300 852 301
E: accounts@smokealarmsolutions.com.au', 1, 1),
        (39, 'Operations - Could not access area in home', '{property_address}', 5, 'Good morning/afternoon, 

RE: {property_address}

Today our Technician attended the above-mentioned property and have advised they were unable to gain access to the <AREA/ROOM> as this part of the home was locked. 

Because of this, we are unable to accurately record if there are alarms in this portion on the home and note this on our Statement of Compliance. 

Please be rest assured however, the alarms recorded and tested by SAS today will supply sufficient compliance to the home as per the current standard. 

If you would like Smoke Alarm Solutions (SAS) to re-attend the property and record this information we are more than happy to, however, access will need to be provided. 

If you have any additional questions, please do not hesitate to phone our friendly Customer Service Team on 1300 852 301 or reply directly to this email. 

Kind Regards,

{user}
Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au', 0, 1),
        (40, 'Accounts - Overdue invoice', 'OVERDUE REMINDER - {property_address} {job_number}', 3, 'Hi,

Our records indicate the attached invoice remains unpaid.

Kindly arrange payment on your next payment run, alternatively, contact our accounts department to discuss the invoice if you have any questions regarding the charge. 

If you believe you have paid this invoice, please email proof of payment along with your remittance advice through to accounts@smokealarmsolutions.com.au

Kind Regards

Accounts Department
Smoke Alarm Solutions
P 1300 416 667
E accounts@smokealarmsolutions.com.au', 1, 1),
        (41, 'Accounts - Private Owner INVOICE', '{property_address} {job_number}', 3, 'Hi There,

Please find attached your tax invoice for the recent Smoke Alarm testing conducted at the above stated property.

Kindly remit your payment at your earliest convenience.  Once your payment has been received and receipted, your compliance certificate will be issued.

If you have any questions at all, please do not hesitate to contact our customer service team.

Accounts Department
Smoke Alarm Solutions
P  1300 416 667
E  accounts@smokealarmsolutions.com.au 
', 0, 1),
        (42, 'Accounts - Private Owner Compliance Certificate', '{property_address} {job_number}', 3, 'Hi,

Thank you for your recent payment for the above stated property.

Please find attached your paid tax invoice along with your compliance certificate.

If you have any questions at all, please do not hesitate to contact us.

Kind regards

SAS Accounts Team
Smoke Alarm Solutions Pty Ltd
P  1300 416 667
E  accounts@smokealarmsolutions.com.au', 1, 1),
        (43, 'Accounts - INV in CREDIT Refund advice', 'Invoice in CREDIT - {property_address} {job_number}', 3, 'Hi,

Our records indicate this invoice is now in credit.

Please reply to this email and advise SAS of the below options:

Option 1:
Please hold the credit and put toward the next annual service

Option 2:
Please refund the over payment.  <strong>*When choosing this option, please provide bank details.<strong>

If you disagree with the balance of this invoice, and your records indicate you have not overpaid, please contact us so we can further investigate.  At times, owners pay direct to SAS without agents being made aware.

Kind regards

SAS Accounts Team
Smoke Alarm Solutions Pty Ltd
P  1300 416 667
E  accounts@smokealarmsolutions.com.au', 1, 1),
        (44, 'Accounts - Courtesy Reminder', 'Courtesy Reminder - {property_address} {job_number}', 3, 'Hi,

A courtesy reminder that the attached tax invoice is now due for payment. 

Please disregard this notice if you have paid in the last 2 working days otherwise, kindly arrange payment on your next payment run, alternatively, contact our accounts department to discuss the invoice if you have any questions regarding the charge. 

If you believe you have paid this invoice, please email proof of payment along with your remittance advice through to accounts@smokealarmsolutions.com.au

Kind Regards
SAS Accounts Team
Smoke Alarm Solutions Pty Ltd
P 1300 416 667
E accounts@smokealarmsolutions.com.au', 1, 1),
        (45, 'Customer Service - Quote Request ', '{property_address}', 2, 'Hi, 

Thank you for your query. As requested, please find attached a copy of our quote to upgrade the above-mentioned property address. 

This is to ensure the property will comply with new legislative requirements. 

If you have any additional questions, please do not hesitate to reach out to our Team on 1300 852 301 or respond directly to this email. 

Kind regards, 
{user}
Smoke Alarm Solutions Pty Ltd 
1300 852 301', 0, 1),
        (46, 'Accounts - Refund on its way', 'A Refund is on its way - {property_address} {job_number}', 2, 'THIS IS AN AUTOMATED EMAIL PROVIDING INFORMATION ON YOUR UPCOMING REFUND.  YOUR REMITTANCE ADVICE WILL BE ISSUED WHEN FUNDS LEAVE SAS BANK ACCOUNT

We received your refund request for the attached property/tax invoice , and your refund has been approved, and on its way to your nominated bank account! Please allow up to 2-3 working days for the funds to hit your nominated bank account.

If the refund is for multiple items, you will receive one deposit into your bank account with a reference of \"REFUND REFER REMIT\" please refer to the refund remittance advice/s to reconcile your refund.

Total Deposit: $
Total refund for this property will show on the attached invoice under \"Amount Owing\"

If you have any questions at all, please do not hesitate to contact us.

Kind regards
SAS TEAM
Accounts Department
Ph: 1300 416 667
E: accounts@smokealarmsolutions.com.au
', 0, 0),
        (47, 'Operations - Property Upgraded Workmanship Issue', '{property_address}', 5, 'Good morning/afternoon, 

I hope this email finds you well. Our Technician attended today for {service_type} at the above-mentioned property address. 

Upon arrival, our Technician has noted the following - {tech_comments}

We believe that there has been an attempt to upgrade this property to meet 2022 Legislation. As there is an issue with one or more of the alarms, this will mean the property is no longer compliant. 

SAS has a dutiful obligation to advise you and would advise this information is passed on to the Landlord as the workmanship completed from the previous company should be covered under warranty.

Alternatively, if your landlord approves for this alarm to be relocated to bring the property to 2022 compliance standards, please respond to this email advising as such.  SAS will then create a job and return at no extra cost to the owner.  

Please note: If you approve for this work to be carried out by SAS you acknowledge that there could be damage to the ceiling from the previous alarm installed that will require a patch and paint that SAS will not be liable for. In the interim a temporary base plate will be installed over the previous alarm location until a handyman is assigned to rectify any damage at the landlords expense.
 
If you have any additional questions please do not hesitate to let our team know. 

Kind regards,

{user}

Smoke Alarm Solutions 
1300 852 301 ', 0, 1),
        (48, 'Customer Service - Agency Service Due NSW', 'Properties due for Servicing with Smoke Alarm Solutions ', 6, 'Hi There, 

This is a courtesy to advise the attached file contains property addresses with services due for renewal here at Smoke Alarm Solutions (SAS). 

Please confirm if each Owner would like SAS to continue servicing each respective property and supply current occupancy details, or simply advise to deactivate and cease all future servicing.

Please note that if we identify a property that requires service within the next 60 days, we will automatically generate a service to be booked. This is to ensure that any active property serviced by SAS meets new legislative requirements (which came into effect in March 2020). Prior to this, the active job would be placed ‘On Hold’ until the beginning of the following month. 

Please note: We are under your strict instruction to NOT attend these properties as part of our Annual Maintenance program and if we have not been granted permission to attend by <SERVICING MONTH> and complete works, SAS shall deem these properties non-compliant and accept no liability pertaining to their compliance.

If you have any additional questions, please feel free to contact our friendly Customer Service Team on 1300 852 301 or via a reply to this email directly. 

I hope you have a lovely day. 

Kind regards, 

SAS

Smoke Alarm Solutions

1300 852 301

info@smokealarmsolutions.com.au', 1, 1),
        (49, 'Accounts - Updated Tax Invoice', 'Updated Tax Invoice - {property_address}  {job_number}', 3, 'Hi,

We received an adjustment request and your adjustment has been completed.  Please find attached updated tax invoice showing the discount/credit applied. 

Please refer to the bottom right hand side for the balance owing on your tax invoice.

If you have any further questions, please do not hesitate to contact us.

Kind regards


Accounts Department
Smoke Alarm Solutions
P: 1300 416 667  E: accounts@smokealarmsolutions.com.au', 1, 1),
        (50, 'Accounts - CR Notification OPTION 1', 'Credit Hold - {property_address}  {job_number}', 2, 'Hi there,

Thank you for letting us know know that you would like to take up Option 1 for the credit balance of the attached tax invoice, to hold the credit balance to put toward the next billable service.

There is no further action that needs to be taken until this service occurs.  This credit balance on this tax invoice will remain and will show on future statements until it is allocated to the next billable service invoice.  Please ignore the credit balance when receiving your fortnightly statements.  It will disappear from your statement once it is allocated.

If you have any questions at all, please do not hesitate to contact us.

Kind regards


Accounts Department
Smoke Alarm Solutions Pty Ltd
P:  1300 416 667  E: accounts@smokealarmsolutions.com.au', 0, 0),
        (51, 'Operations - Unable to test safety switch, tenant requires power on', '{property_address}', 5, 'Good morning/afternoon, 

RE: {property_address}

Today our Technician attended the above-mentioned property and has advised they were unable to test the safety switch as the tenant was working from home and required power.

Because of this, we are unable to accurately record this portion on the home and note this on our Statement of Compliance. 

If you would like Smoke Alarm Solutions (SAS) to re-attend the property and record this information we are more than happy to, however, access will need to be provided. 

If you have any additional questions, please do not hesitate to phone our friendly Customer Service Team on 1300 852 301 or reply directly to this email. 

Kind Regards,

{user}
Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au
', 0, 1),
        (52, 'Operations - Keys dont work', '{property_address}', 5, 'Good morning/afternoon,

Today our Technician collected keys for {property_address} to complete {service_type}.

However when they attended the property the keys that were provided did not allow access. Our Technician has noted the following comments- (Insert doors tried & key number).

Smoke Alarm Solutions (SAS) are more than happy to reattend with keys, however, a working or the correct set will need to be provided 

If you have any additional questions, please do not hesitate to phone our friendly Customer Service Team on 1300 852 301 or reply directly to this email.', 1, 1),
        (53, 'Accounts - Outstanding Inv Deactivated Agent', 'Outstanding Invoice - {property_address}  {job_number}', 3, 'Hi,

We recently received notification that you have deactivated all future services with Smoke Alarm Solutions.  We are sorry to see you go!

We note the attached tax invoice is showing as outstanding prior to notification that you are leaving SAS.  We ask that you settle the attached outstanding tax invoice on your next disbursement.

If you have any questions in relation to the attached tax invoice, please do not hesitate to contact us.

Kind Regards

Accounts Department
Smoke Alarm Solutions
P:  1300 416 667  E: accounts@smokealarmsolutions.com.au', 1, 1),
        (54, 'Property Needs Verification ', 'Property service confirmation with Smoke Alarm Solutions', 12, 'Good morning/afternoon Team, 

We recently audited your servicing portfolio here at SAS, using our Palace/PropertyMe integration. 

As a result, there are a number of properties that require confirmation of service. Please find this attached. 

If there are any properties mentioned, which require deactivation, please let our team know at your earliest convenience. 

If you have any additional questions, please feel free to contact our friendly Customer Service Team on 1300 852 301 or via a reply to this email directly. 

I hope you have a lovely day. 

Kind regards, 
Smoke Alarm Solutions

1300 852 301

info@smokealarmsolutions.com.au', 0, 1),
        (55, 'Customer Service - Holiday Vacancy date request ', 'Outstanding Smoke Alarm Services for {agency_name} Properties', 6, 'Dear {agency_name} team,

We hope this email finds you well.

Our records indicate that there are a number of outstanding smoke alarm services for your Agency. For your convenience, we have attached a list of the properties in question. We kindly request that you provide us with the vacancy dates for these properties, to assist us in conducting the necessary smoke alarm servicing.

Alternatively, if any of these properties require deactivation, please let our team know at your earliest convenience.

If you have any additional questions or concerns, please do not hesitate to contact our friendly Customer Service Team on {agent_number}, or simply reply to this email.

Thank you for your attention to this matter. Have a great day.

Best Regards,

{user}

Smoke Alarm Solutions

{agent_number}', 0, 1),
        (56, 'Operations - Denovans EN Template', 'Entry Notice Required - <date> Smoke Alarm Solutions ', 5, 'Good morning/afternoon,

Please entry notices the attached properties for < day & date>.

The Technician attending will be <Technician name>.

Please confirm once you have issued the appropriate notices so as our team can ensure the services are scheduled accordingly. 

If you have any additional questions please do not hesitate to let our team know. 

Thank you and have a lovely day.

Kind regards, 

{user}

Smoke Alarm Solutions 
{agency_phone_number}
', 0, 0),
        (57, 'Operations - Exposed Ugly Ceiling/Blank Plate', '{property_address}{service_type}', 5, 'Good morning/afternoon, 

Today our technician attended the above-mentioned property address to complete the {service_type}.
 
Our Technician has noted that because we have had to relocate/remove and replace existing alarms, in addition to the sizing difference of our product, this has left the ceiling partially exposed. 

To cover the affected area, our Technician has used electrical base plates. 

It is not a common occurrence but from time to time it does happen. This is a result of the original alarm being left in place and not removed when the ceiling was painted or repainted. 

It is important to note the alarm location is noted as required for compliance, and there is currently is no cause for distress, this is a courtesy to let you know.

If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au

', 0, 1),
        (58, 'Operations - Exposed Ugly Ceiling/No Blank Plate', '{property_address}{service_type}', 5, 'Good morning/afternoon, 

Today our technician attended the above-mentioned property address to complete the {service_type}.
 
Our Technician has noted that because we have had to relocate/remove and replace existing alarms, in addition to the sizing difference of our product, this has left the ceiling partially exposed. 

It is not a common occurrence but from time to time it does happen. This is a result of the original alarm being left in place and not removed when the ceiling was painted or repainted. 

It is important to note the alarm location is noted as required for compliance, and there is currently is no cause for distress, this is a courtesy to let you know.

If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au

', 0, 0),
        (59, 'Operations - No show', '{property_address}', 5, 'Hi <TENANT>, 

We attended your property today to check your smoke alarms as per our appointment and nobody was home. 

Please call SAS 1300 852 301 to reschedule urgently as your service is outstanding and your property is not compliant. ', 1, 1),
        (60, 'Operations - NSW potential Upgrade', '{property_address}', 5, 'Good morning/afternoon,

We trust this email finds you well. 

Today we write to you with regards to - {property_address}. This property is currently noted as a “Short Term Rental” in your SAS portfolio. This property may be affected by the NSW Short Term Rentals Accommodation amendments.

WHAT DOES IT MEAN?
The existing alarms in the property may or may not be sufficient, the property may require additional and or different alarms to adhere to the new amendments.

WHAT DOES SAS DO?
SAS will assess the property and if it is required, install the relevant alarms (and if a heat alarm is required in the garage, we will also display the required signage)


If you do NOT wish for SAS to attend to this property or it is no longer a short-term rental, please let me know. To adhere to the amendments, SAS will visit the property and do what is required for the smoke alarms to comply, before 1st November 2021.

I have attached a brochure for your perusal with further information and pricing, any questions, please don’t hesitate to reach out to our team to discuss this further. 

Kind regards, 
{user}
Smoke Alarm Solutions Pty Ltd 
1300 852 301', 0, 1),
        (61, 'Operations - 240v alarm - No power to unit', '{property_address}', 5, 'Good morning, 

Recently our Technician attended the following property: {property_address}

This is a courtesy email to advise that when SAS attended the property, our Technician has advised that there is currently no mains power to the alarm unit located in the <LOCATION OF ALARM>. 

Rectifying the situation is outside the realms of what our Electrical Technicians are employed to service at the property, rectifying the wiring situation will require a third party (your preferred electrician) to attend at your landlord’s expense. 

Please be rest assured that even though this may be the case, we have ensured that the property is protected and the smoke alarms are operating via the backup battery.

If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au

', 0, 1),
        (62, 'Upfront Invoice Template', 'Changing the way we renew subscription services - Smoke Alarm Solutions', 1, 'Good morning/afternoon,

I hope this email finds you well.

The team at Smoke Alarm Solutions would like to advise of our recent change to the way we will be invoicing our subscription renewals.  

As of <strong>June 1st, 2021</strong>, we will be transitioning to an <strong>upfront invoicing</strong system. This will mean all properties that are due for their subscription renewal will have an invoice produced automatically on the first working day of the month. Additionally, SAS will only attend the property for service upon a work order request received from your office. 

Please note, although invoices will all be dated the 1st, all compliance certificates will still be issued on the date of visit to ensure accuracy.

Please be aware on the <strong>15th of each month</strong> your office will still receive our courtesy email notification to advise of all properties requiring renewal in the upcoming month. Providing your team with the opportunity to advise of any services that are to be removed before the subscription renewal.

Furthermore, please rest assured that we will still automatically create a service to be attended if we identify that the property has not had a service completed within the past 12 months to ensure all properties remain compliant as well as attend any work requested via a work order.

In implementing this change we hope to prevent any delays in your owner’s subscriptions being renewed and ensure that all properties remain actively serviced by SAS as well as prevent additional visits to properties within a 12-month time frame that may cause frustration and inconvenience to your tenants.

If you have any further questions or concerns, please feel free to contact SAS at any time.

Thank you for your ongoing support and understanding.

Kind regards, 

{user}

info@smokealarmsolutions.com.au
1300 852 301
', 0, 1),
        (63, ' Customer Service - Move to Upfront Billing', 'A change to the way we invoice - Smoke Alarm Solutions Pty Ltd', 1, 'Good morning/afternoon,

I hope this email finds you well.

The team at Smoke Alarm Solutions would like to advise of our recent change to the way we will be invoicing our subscription renewals.  

As of June 1st, 2021, we will be transitioning to an upfront invoicing system. This will mean all properties that are due for their subscription renewal will have an invoice produced automatically on the first working day of the month. Additionally, SAS will only attend the property for service upon a work order request received from your office. 

Please note, although invoices will all be dated the 1st, all compliance certificates will still be issued on the date of visit to ensure accuracy.

Please be aware on the 15th of each month your office will still receive our courtesy email notification to advise of all properties requiring renewal in the upcoming month. Providing your team with the opportunity to advise of any services that are to be removed before the subscription renewal.

Furthermore, please rest assured that we will still automatically create a service to be attended if we identify that the property has not had a service completed within the past 12 months to ensure all properties remain compliant as well as attend any work requested via a work order.

In implementing this change we hope to prevent any delays in your owner’s subscriptions being renewed and ensure that all properties remain actively serviced by SAS as well as prevent additional visits to properties within a 12-month time frame that may cause frustration and inconvenience to your tenants.

If you have any further questions or concerns, please feel free to contact SAS at any time.

Thank you for your ongoing support and understanding.

Kind regards, 

{user}

info@smokealarmsolutions.com.au
1300 852 301
', 0, 0),
        (64, 'Operations - Out of Scope IC Warranty', '{property_address} {job_date} ', 5, 'Good morning /afternoon, 

Today our technician attended {property_address} to complete {service_type}
 
Our Technician has noted the following: {tech_comments}

This property has been upgraded to meet 2022 Legislation; meaning all alarms are interconnected. As there is an issue with one or more alarms, this now interferes with the connectivity and ultimately will mean the property is no longer compliant. 

SAS has a dutiful obligation to advise you and would advise this information is passed on to the Landlord as the alarm may be covered under warranty with the previous installer.

Rectifying the situation is outside the realms of what our Electrical Technicians are employed to service at the property.

Rectifying the wiring situation will require a third party (your preferred electrician) to attend at your landlord’s expense. 

If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au

', 0, 1),
        (65, 'Dupe visit - Upfront INV', '{property_address}', 12, 'Hi There,

I hope this email finds you well, I am reaching out to you regarding {property_address}. We recently attended this property to service the smoke alarms, and it is now due for the annual subscription renewal.

Considering our recent attendance, we wanted to provide you with two options for the annual maintenance: we can attend the property for the yearly maintenance, or we can charge the annual subscription fee without attendance. Please let us know which option works best for you.

If you have any concerns or questions, please do not hesitate to contact us. We are always happy to help.

Kind regards,
{user}
SAS
{agent_number}
', 0, 1),
        (66, 'Operations - ACT alarm relocation recommendation', '{property_address}', 2, 'Good morning/afternoon, 

Today our technician attended the above-mentioned property to complete the {service_type} service.
 
As a result of our attendance, he has noted the following: {tech_comments}

Although the property is compliant, it is recommended the alarms are repositioned for optimum performance. As the alarm in question is hardwired, relocating the alarms are outside the realms of what our Electrical Technicians are employed to service at the property.

Rectifying the wiring situation will require a third party (your preferred electrician) to attend at your landlord’s expense. 

If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au

', 0, 0),
        (67, 'Operations - ACT, NSW, SA 240v alarm relocation recommendation', '{property_address}', 5, 'Good morning/afternoon, 

Today our technician attended the above-mentioned property to complete the {service_type} service.
 
As a result of our attendance, he has noted the following: {tech_comments}

Although the property is compliant, it is recommended the alarms are repositioned for optimum performance. As the alarm in question is hardwired, relocating the alarms are outside the realms of what our Electrical Technicians are employed to service at the property.

Adjusting the wiring situation will require a third party (your preferred electrician) to attend at your landlord’s expense. 

If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au

', 0, 0),
        (68, 'Action Required - Cancel', 'SAS| Help Needed {property_address}', 6, 'Hi There,

I hope this email finds you well,
SAS have made contact with you regarding this property which requires further permissions or instruction.. We are yet to receive a response.

Please be advised this job has now been cancelled and a new work order will be required should you wish for SAS to carry out further works.

Please do not hesitate to call us should you wish to discuss further.

Kind Regards,

SAS
Smoke Alarm Solutions.
1300 416 667', 0, 1),
        (69, 'Operations - DHA Back to Base Alarm ', 'Smoke Alarm Solutions - MITM-NUMBER', 5, 'Good afternoon, 

RE: {property_address}

Recently our technician attended the premises; they have noted that the property has a back to base alarm system integrated through the premises. 

We do not touch these systems nor test the alarms, as we do not deem theses to be a standalone unit they must be tested by the security company. Unfortunately we do not hold a security license.
 
The alarms may meet the Australian standards but we cannot verify that they are indeed compliant; therefore we cannot include theses alarms as being compliant on our certificate of compliance.

Can you please let me know if you wish for us to cancel the service or install stand alone battery operated alarms

I look forward to hearing from you. 
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au', 1, 1),
        (70, 'Operations - DHA Fire Panel Alarm', 'Smoke Alarm Solutions - MITM-NUMBER', 5, 'Good morning/ afternoon, 

<b><i>RE: {property_address}</b></i>

Recently our Technician attended the above-mentioned property; they have advised that the property has a fire panel integrated through the premises. 
 
We do not touch these systems nor test the alarms, as we do not deem these to be a standalone unit, they should be tested by a third party (I assume STRATA?). 
 
The alarms may meet the Australian standards but we cannot verify that they are indeed compliant; therefore we cannot include these alarms as being compliant on our certificate of compliance. 

Can you please let SAS know if you wish for the service to be cancelled, alternatively we are able to install a stand-alone battery-operated unit?

Kind Regards

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au
', 1, 1),
        (71, 'Operations - DHA Invoice Adjustment request', 'Smoke Alarm Solutions - MITM-NUMBER', 5, 'Good morning/afternoon, 

<b><i>RE: {property_address} </b></i>

We request an invoice adjustment based on the requirement for the installation of <NUMBER> new alarms.

Please adjust the contracted rate to - \$PRICE gst included.

Please find attached a copy of our invoice for your perusal.   

Kind regards, 

{user}

Smoke Alarm Solutions 
1300 852 301', 1, 1),
        (72, 'Maintenance Provider confirmation email', 'Smoke Alarm Solutions', 12, 'Good morning/afternoon,

I trust this email finds you well.

Our database currently has your agency using TAPI/MAINTENANCE MANAGER for SAS invoicing. 

Recently We have noticed that we have been unable to locate your agencies properties/jobs on TAPI/MAINTENANCE MANAGER, therefore being unable to upload invoices accordingly.

May I please confirm with you if your agency is still using this platform or if you would be happy for SAS to email the invoices directly to your agency moving forward. 

Please do not hesitate to contact our office if you have any further questions.

Kind regards, 

{user}

Smoke Alarm Solutions 
1300 852 301
', 0, 1),
        (73, 'SALES UPGRADES ', 'What you need to know about selling a home in QLD after January 1st 2022', 4, 'Hi There,

I hope this email finds you well,
Here''s the \"need to know\" changes to the QLD legislation for sales properties and how we can help you!

Legislation change, what does this mean for Home Owners?
• All houses leased or sold will need to comply with the new standards from 1 January 2022.

• All owner-occupied private dwellings will need to comply with the new standards by 1 January 2027.

• From 1 January 2017, all houses being built or significantly renovated will need to comply with the new Smoke Alarm legislation upon completion.

SAS have put together the best value option for QLD Vendors to ensure that they can quickly, easily, and economically upgrade the Smoke Alarms in properties to ensure that they comply with new legislation and that the transfer of property ownership can take place.

Our exceptional buying power in QLD means that we can supply and install quality alarms cost effectively.

SAS are dedicated to providing you the service you require, if you wish to engage our services, please respond YES along with your contact information and a representative will contact you shortly to discuss further. alternatively, please do not hesitate to call us on 1300 416 667.

Have a lovely day ahead,
{user}
SAS
{agency_phone_number}', 1, 1),
        (74, 'Operations - DHA service completion/cancelled job', 'Smoke Alarm Solutions - MITM-NUMBER', 5, 'Good morning/afternoon, 

<b><i>RE: {property_address} </b></i>

We request an invoice submission based on the service completion for the above-mentioned property address. 

Our Team have attended and completed the service within the allocated time period and are unable to submit our invoice for approval as this job is now cancelled via the DHA portal. Please find attached a copy of our invoice for your perusal.   

Kind regards, 

{user}

Smoke Alarm Solutions 
1300 852 301', 1, 1),
        (75, 'Operations - Patched Holes', '{property_address}', 2, 'Hi <strong>{agency_name}</strong>,

Please be advised that SAS have completed work at <strong>{property_address}</strong>.

We have patched screw holes to the best of our ability. The patched screw holes may need a touch of paint at your handyman/painter''s next visit to the property.

Please feel free to let us know if you have any questions.

Kind Regards,

{user}

<strong>Smoke Alarm Solutions
0508 836 268
info@sats.co.nz</strong>', 1, 1),
        (76, 'Operations - IC same brand recommendation', '{property_address}', 2, 'Good morning/afternoon, 

Today our technician attended the above-mentioned property to complete the {service_type} service.
 
As a result of our attendance, he has noted the following: {tech_comments}

Although the property is compliant, it is recommended by manufacturer that the alarms are same brand for optimum performance.

This information is for your use, however, we felt it imperative you are notified. SAS has a dutiful obligation to advise you and would advise this information is passed on to the Landlord as the alarm may be covered under warranty with the previous installer.

If I can be of any further assistance please don’t hesitate to contact me.
 
Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au', 0, 0),
        (77, 'Verify Tenant Details', 'SAS| Help Needed  {property_address}', 8, 'Hi There,

I hope this email finds you well,
Please provide tenant details for the above property at your earliest convenience in order for SAS to arrange a suitable time and day to service the Smoke alarms.

Kind regards,
SAS
{agent_number}', 0, 1),
        (78, 'Unresponsive/Old Jobs', 'SAS| Help Needed  {property_address}', 8, 'Hi There,

I hope this email finds you well.

Smoke Alarm Solutions (SAS) must conduct a mandatory service of the {service_type} at {property_address}. However, we have been unable to contact you despite our numerous attempts to do so. We have escalated this matter with your agency, but it is imperative that we service the {service_type} as soon as possible.

Please call SAS urgently on {tenant_number} to arrange a mutually convenient time for the servicing of the smoke alarms or to provide us with key access to the property. Failing to comply with this request may result in a breach of the tenancy agreement.

Thank you for your immediate attention to this matter.', 0, 1),
        (79, 'Agent needs to Verify', 'SAS| Help Needed {property_address}', 8, 'Hi There,

I hope this email finds you well,
The tenant residing at this property requests a call from your agency to confirm they are to book and appointment, they will not provide access until confirmed with you. Please call this tenant to confirm.

Kind Regards,
SAS
1300 416 667
', 1, 1),
        (80, 'Book with agent (tenant wants RE to attend with SAS)', 'SAS| Help Needed  {property_address}', 8, 'Hi There,

I hope this email finds you well,
The tenant at this property has granted SAS access with an agent from your agency ONLY. Please provide preferred times and dates you will be available to attend this property with our technician and I will endeavour to allocate this for you.

Kind Regards,
SAS
{agent_number}', 0, 1),
        (81, 'FR cancellation', 'SAS| Help Needed  {property_address}', 8, 'Hi There,

I hope this email finds you well,

SAS received notice of the smoke alarms beeping at this property and our attendance was required to rectify the situation.
We have made contact with this tenant who has advised the alarms are no longer beeping and the service is unrequired. Please confirm that this job can be cancelled or not. If the job is to be carried out, may I please ask you to contact the tenant to allow access to SAS.

Kind Regards,
SAS
{agent_number} 
', 0, 1),
        (82, 'Tenant Declines Access', 'SAS| Help Needed  {property_address}', 8, 'Hi There,

I hope this email finds you well,
The tenant residing at this property has declined SAS access to service the smoke alarms, may we ask that you contact the tenant to request they make a booking with us.

Kind Regards,
SAS
{agent_number}', 0, 1),
        (83, 'Other supplier', 'SAS| Help Needed  {property_address}', 8, 'Hi There,

I hope this email finds you well,
The tenant at this property has advised the property is looked after by another supplier, may i please confirm SAS servicing is to be cancelled.

Kind Regards,
SAS
{agent_number}
', 0, 1),
        (84, 'Tenant Advises they are vacating', 'SAS| Help Needed  {property_address}', 8, 'Hi There,

I hope this email finds you well,
The tenant on file for this property has advised they are vacating the property soon and would like the smoke alarms serviced after they vacate.

May I please request you provide tenant details of the new tenants moving in and/or vacancy dates.

Kind Regards,
SAS
{agent_number}', 0, 1),
        (85, 'Verify NLM', 'SAS| Help Needed  {property_address}', 8, 'Hi There,

I hope this email finds you well,
SAS have been advised you no longer manage this property. May we please confirm this?

Kind Regards,
SAS
{agent_number}', 0, 1),
        (86, 'Operations - Property No Longer Upgraded', '{property_address}', 5, 'Good morning/afternoon, 

I hope this email finds you well. Our Technician attended today for the {service_type} service at the above-mentioned property address. 

Upon arrival, our Technician has noted the following - {tech_comments}

Based on this acknowledgement, the property is no longer compliant and it will require an additional alarm in room.

SAS has a dutiful obligation to advise you and would advise this information is passed on to the Landlord as the alarm may be covered under warranty with the previous installer/as you may need to discuss replacements with the owner.

If the Landlord would like SAS to complete the works, we are more than happy to re-attend and replace all the alarms with the brand of Smoke Alarm we use. 

<b>The cost of these are $200 per Premium Brooks smoke alarm unit OR $100 per Economical Quality smoke alarm unit.</b>

If this option is chosen the alarms would additionally guarantee an extended warranty with SAS. Confirming that if there were any issues in the future, we will attend, fix or replace the alarm at no additional cost. 

If you have any additional questions please do not hesitate to let our team know. 

Kind regards,

{user}

Smoke Alarm Solutions 
1300 852 301 ', 0, 1),
        (87, 'EN cancellation ', 'SAS| Help Needed  {property_address}', 8, 'Hi There,

We hope this email finds you well,
This is a courtesy to advise that the tenants of the above property have cancelled the entry notice sent. SAS will await your further instruction on how to proceed with this booking. 

Have a lovely day ahead,

SAS
1300 416 667', 0, 1),
        (88, 'Operations - Sales Upgrade Voicemail Sent ', 'SERVICE COMPLETED - {property_address}', 5, 'Good morning/afternoon, 

Today our Team attended and completed the upgrade to the above-mentioned property address. 

We have attempted contact with you directly to process payment for this, and have been unsuccessful. As a result, we are unable to submit your statement of compliance for this service. 

We have attached a copy of the invoice for your records, please contact our Team and quote your invoice number to proceed with payment. 

If you have any additional questions, please do not hesitate to reach out to our Team on 1300 852 301. 

Kind regards, 

{user}

Smoke Alarm Solutions

', 0, 1),
        (89, 'Operations - Tech Sick', 'Smoke Alarm Testing Service attendance - {job_date}', 5, 'Good morning {agency_accounts_email}, 

We hope this email finds you well. 

Today we had scheduled our technician to attend the following property - {property_address}

Unfortunately, our technician is away sick today and we will be required to schedule these services for a later date. 

Please be rest assured we have notified the occupants of the need to reschedule, this is a courtesy to advise your office. 

If you have any additional questions, please do not hesitate to let our team know. 

Kind regards, 

{user}

Smoke Alarm Solutions 
1300 852 301


', 1, 1),
        (90, 'Operations - Brooks IC swap out', '{property_address}  {job_number}', 5, 'Hi Rob, 

This is a courtesy to advise you, for your records, that the above-mentioned property address required swapping of the Emerald Planet upgrade alarms, to the Brooks upgrade alarm stock. 

Kind regards, 

{user}', 0, 0),
        (91, 'Operations - Please prep extra keys', '{property_address} - additional request to prepare keys', 5, 'Good <time of day>, 

Our team have identified that the above-mentioned property is currently vacant, with the vacancy dates soon to expire. 

Based on this information, we request the keys are prepared for our Technician - <TECH> to attend <DATE>. 

If they are currently unavailable please let us know at your earliest convenience. If I can be of any further assistance please don’t hesitate to contact our team. 

Kind Regards,

{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au', 0, 1),
        (92, 'RE change notification', 'Update on Property Management for {property_address}', 6, 'Hi There,

I hope this email finds you well.

I am writing to inform you that we have received notification of a change in management for {property_address}, and we have updated our records accordingly. We would like to ensure that we have the correct information on file, so please let us know if you believe we have been advised incorrectly.

If you have any questions or concerns regarding the servicing of the smoke alarms at {property_address}, please do not hesitate to contact us.

Thank you for your attention to this matter.

Kind regards,

{User}
Smoke Alarm Solutions (SAS)', 0, 1),
        (93, 'Action Required - Tall Ladder SAS ', '{property_address} {job_number}', 5, 'Hi Rob, 

We have had to shift this job to Action Required as <Tech> has advised the property requires a taller ladder than any he has available. 

Please let Customer Service know when this has been provided to a team member, or a solution has been provided so they are able to place the service back into circulation. 

Kind regards, 

{user}', 0, 1),
        (94, 'Operations - DHA Request for target date extension', '{property_address} MITM - <NUMBER>', 5, 'Good morning/afternoon, 

<b><i>RE: {property_address} </b></i>

We request a target extension for the MITM number stated above. This is based on - <INSERT EXPLANATION>. 

If this is not feasible please let our Team know, and we will cancel this outstanding job. 

Kind regards, 

{user}

Smoke Alarm Solutions 
1300 852 301', 0, 1),
        (95, 'CS Fix Replace - Hush Brooks EIB3024 / 3016 / 3014', 'SAS | How to hush alarm ', 4, 'Good morning/afternoon,

Thank you for your call today, we are organising a technician to attend the property. However, in the mean time please follow these steps to hush the alarms. 

1. Locate the alarm that is sounding and has a flashing red LED. (Please note if these are interconnected, you will need to find the alarm that triggered the system to go off. This alarm can be located by a flashing red LED light which is flashing more frequently than other alarms in the property.)

2. Press the test/hush button briefly to silence the alarms for 10 minutes. 

After the 10 minutes, these alarms should return to normal functionality. If after 10 minutes, the alarm sounds again. Please repeat above steps.

If the beeping continues, please contact SAS for further instruction.

We will be in contact once we have an available technician to attend the property. 

https://www.smokealarmsolutions.com.au/false-alarms/

Thank you, Smoke Alarm Solutions (SAS). 13 51 22 99', 1, 0),
        (96, 'CS Fix Replace - Hush Emerald Planet', 'SAS | How to hush alarm ', 4, 'Good morning/afternoon,

Thank you for your call today, we are organising a technician to attend the property. However, in the mean time please follow these steps to hush the alarms. 

1. Locate the alarm that is sounding.

2. Press & release the silence button.

3. Your alarm will silence & enter a reduced sensitivity reset cycle for approximately
8 minutes. During the reduced sensitivity reset cycle, the red LED light will flash every 8 seconds

Your alarm will automatically return to full sensitivity on completion of the reset cycle
& the red LED light will revert to flashing once 40 seconds (normal operation).

After the 8 minutes, these alarms should return to normal functionality. If after 8 minutes, the alarm sounds again. Please repeat above steps.

If the beeping continues, please contact SAS for further instruction.

We will be in contact once we have an available technician to attend the property. 

https://www.smokealarmsolutions.com.au/false-alarms/

Thank you, Smoke Alarm Solutions (SAS). 13 51 22 99', 1, 0),
        (97, 'Accounts - INV error after bulk update', 'Invoice Error', 3, 'Dear Customer,

A recent error in our system caused some invoices to be sent out with a non existent payment listed.
The error has since been fixed, as well as the affected jobs, but you may have received an invoice with the incorrect due amount listed due to this payment.

Please ignore the payment, and pay the full invoice amount. 
If you''d like confirmation, or an updated invoice, please don''t hesitate to contact our friendly team who will assist you. 

Below is a list of recent properties with jobs that might have been affected, for your records.

[List]

Thank you for your understanding. 

Sincere Regards,

The SAS Team', 0, 0),
        (98, 'Booked Job (Meeting Agent Onsite)', '{property_address} booking confirmed ', 2, 'Hello,

This is to confirm the appointment made today for the {job_date} @ {time_of_day} to service the {serv_name} at {p_address}. We will be meeting a {agency_name} property manager onsite for access. 

Thank you for your assistance.

SAS
 {tenant_number}', 0, 1),
        (99, 'Clause 104RBB(2) applied', '{property_address}', 5, 'Good morning/afternoon,

Today we had an SAS electrician visit {property_address} to assess the smoke alarms. 

Our technician has noted the following- SAS can confidently mark this property compliant 2022 compliant based on the application of section 104RBB(2) of the Fire and Emergency Services Act 1990.

Our technician has deemed that the relocation of the smoke alarm in the LOCATION is impractical and its location is not impeding the function of this alarm.

Please see a copy of this clause below.

“Under section 104RBB(2) of the Fire and Emergency Services Act 1990, alternative compliance is acceptable if it is unreasonable or impractical for an owner of a domestic dwelling to relocate smoke alarms to positions required under a smoke alarm requirement provision. Furthermore, for this section to apply, the smoke alarm in question must meet all other requirements of the 2022 Queensland legislation and operate as intended, which is to provide occupants with adequate warning of fire.”

If you have any further questions please don’t hesitate to give the office a call.

{user}

{agent_number}
', 0, 1),
        (100, 'Preferred Time Given - SAS Response', 'SAS | Preferred Time for a booking at {property_address}', 2, 'Thank you for providing your preferred times for SAS to attend your property at {property_address}. We have taken note of this and will endeavour to meet your request. Please be advised this is NOT a confirmed booking as of yet, we will contact you to confirm a booking. alternatively, you can contact us on {tenant_number} to discuss further.', 1, 1),
        (101, 'Agency Staff User 2FA Code Request', 'Security Code', 4, 'Hi {agency_staff_fname},

You are receiving this email because you have enabled Two-factor Authentication for the SAS (Smoke Alarm Solutions) Agency Portal. 

You will need a security code to log in (this code is only valid for 5 minutes).

Your code is {agency_staff_2fa_code}

For security purposes, this request was received from a {agency_staff_device_used} device using {agency_staff_browser_used} and IP address {agency_staff_ip}

If you did not request this action please contact SAS on {agent_number}.

Thanks
The SAS Team', 0, 1),
        (102, 'Overdue Jobs', 'SAS | Overdue Smoke Alarm Compliance {property_address}', 6, 'Hello,

SAS have continuously tried contacting you to make a booking for {property_address} to service the smoke alarms and have not been able to secure a booking.

 Please respond with any preferred times you have within the next fortnight. Once we have these preferred times SAS will be in contact to confirm a booking. If you are not available for any bookings, please advise if SAS can collect keys from your agency to complete the service.

We look forward to hearing from you soon!

Smoke Alarm Solutions
{tenant_number}', 1, 1),
        (103, 'API NLM', 'SAS | Help Needed | {property_address}', 8, 'Hi There,

I hope this email finds you well,
SAS have been advised via your API connection that you no longer manage this property, we have cancelled SAS servicing this property moving forward. If this has been done in error, please contact us at your earliest convenience. 

Kind Regards,
SAS
{agent_number}', 1, 1),
        (104, ' API Verify vacancy details', 'SAS| Help Needed  {property_address} vacancy period', 8, 'Hi There,

I hope this email finds you well,
SAS have been advised that this property is vacant via your API connection. Can you please notify us of the vacancy period either by replying to this email or updating us in the SAS portal.

If you have any questions, please do not hesitate to contact us. 

Kind Regards,
SAS
{agent_number}', 0, 1),
        (105, '8. Booking Time Slot FULL', 'Smoke Alarm Servicing Appointment for {property_address}', 9, 'Hello there,

I hope this email finds you well. Thank you for your recent booking request. Unfortunately, the time-slot you requested is currently full. We apologize for any inconvenience this may have caused you.

We would like to offer our assistance in finding another suitable time for you. If you could please reply to this email and let us know your availability, including which day/s and time/s would work best for you, we will do our best to accommodate your request.

Alternatively, you can also contact us at {tenant_number} if you have any questions or concerns. Our team is always ready to help.

Once again, thank you for your interest in our services, and we hope to hear back from you soon.

Best regards,
{user}
Smoke Alarm Solutions (SAS)', 1, 1),
        (106, 'Accounts - Inv in CREDIT after adjustment to paid invoice.', 'Invoice in CREDIT - {property_address}  {job_number}', 3, 'Hi,

We received an adjustment request and your adjustment has been completed. Please find attached an updated tax invoice showing the adjustment applied. As the invoice has already been paid, it now has a credit balance.

Kindly reply to this email advising what you would like to be done with the credit. Alternatively, if your records differ and your payment may be allocated incorrectly, please contact us immediately so we can re-allocate correctly.

OPTION 1:
We do not want a refund, please hold the funds for the next annual service. 

OPTION 2:
Please refund this credit back to us <strong>(please reply with bank account details)</strong>.

If you have any questions at all, please do not hesitate to contact us.

Kind Regards

Accounts Department
Smoke Alarm Solutions
Ph: 1300 416 667
E: accounts@smokealarmsolutions.com.au', 1, 1),
        (107, 'Accounts - Updated Tax Invoice (Held Credit Applied)', 'Updated Tax Invoice - {property_address}  {job_number}', 3, 'Hi,

We previously held a credit for this property which has now been applied to this invoice. Attached is an updated tax invoice showing the credit applied.

<strong>Please remove the original invoice that was issued from your system, and replace it with this updated invoice</strong>

Please refer to the bottom right hand side for the balance owing on your tax invoice.

If you have any further questions, please do not hesitate to contact us.

Kind Regards

Accounts Department
Smoke Alarm Solutions
Ph: 1300 416 667
E: accounts@smokealarmsolutions.com.au', 1, 1),
        (108, 'Accounts - Private owner overdue invoice', 'OVERDUE REMINDER - {property_address} {job_number}', 3, 'Hi,

Our records indicate the attached invoice remains unpaid.

Kindly arrange payment at your earliest convenience.

If you believe you have paid this invoice, please email proof of payment along with your remittance advice through to accounts@smokealarmsolutions.com.au

Kind Regards

Accounts Department
Smoke Alarm Solutions
P 1300 416 667
E accounts@smokealarmsolutions.com.au', 1, 1),
        (109, 'Not Compliant ', 'Not Compliant - {property_address}', 5, 'Good morning/ afternoon,

RE: Not Complaint {property_address}

We attended the above mentioned property today and our Technician has deemed the property not compliant due to the following reasons; {not_compliant_notes}

Should you have any questions, please do not hesitate to contact us. 

Kind Regards
{user}

Smoke Alarm Solutions
1300 852 301
info@smokealarmsolutions.com.au', 0, 1),
        (110, 'CUSTOMER SERVICE - Requesting Preferred Time ', 'SAS | Preferred Time for a booking at {property_address}', 4, 'Hello,

SAS need to complete testing of the Smoke Alarms on behalf of {agency_name}. Appointments are available between 7am - 3pm weekdays, with a minimum 1 hour time-frame required. Please reply to this message and advise which day/s and time you are available. We will do our best to met these. 

Thank you, Smoke Alarm Solutions (SAS). {agent_number}', 1, 1),
        (111, 'Cancel due to no response', 'Cancellation of {property_address} services', 8, 'Hello,

I am writing to inform you that we have canceled the scheduled servicing for the property address mentioned above. This decision was made due to our unsuccessful attempts to contact your agency and obtain necessary tenant information.

If this cancellation was made in error or if there has been a misunderstanding, kindly reply to this email with the required tenant information. We will promptly reinstate the job and proceed accordingly.

Thank you for your understanding and cooperation in this matter. We appreciate your prompt attention to provide the necessary details to ensure a smooth continuation of our servicing.

If you have any questions or need further assistance, please do not hesitate to reach out to us.

Kind Regards, 
Smoke Alarm Solutions (SAS) 
{agent_number}', 0, 1),
        (112, 'API Tenant Details', 'SAS | Help Needed | {property_address}', 8, 'Hello,

The tenants at the above mentioned property have advised SAS that they no longer are the tenants at this property. New tenants details are not available via your API connection, can you please confirm if this property is vacant or update the tenant details in your API?

Kind Regards,
SAS
{agent_number}', 0, 1),
        (113, 'IC Upgrade Required', '{agency_address} Property Not Compliant', 10, 'Hello {agency_name},

I hope this email finds you well. Our technician visited {property_address} today for the servicing of the {service_type}. 

During the visit, it was observed that there had been an attempt to upgrade the property to comply with the 2022 Legislation. However, the technician noted the following issues:
[tech_comments]
{not_compliant_notes}

Currently, the alarms in the property do not interconnect with the brand of alarms SAS stocks, and there is a problem with one or more of the alarms. Therefore, we present the following options for you and the landlord to discuss and decide upon:

1. The original installer can revisit the property to rectify their previous work under warranty. As this should have been completed correctly in the first place, there will be no additional charge for the landlord.

2. SAS can return to the property and upgrade it with our branded alarms. If you are interested in exploring this option and would like to receive a quote, please let us know.

In the meantime, we have ensured the property''s safety by installing a temporary alarm in the event of a fire.

We eagerly await further discussion with you to resolve this matter promptly. Please don''t hesitate to reach out to us.

Kind Regards,
{user}
{agent_number}
Smoke Alarm Solutions', 1, 1),
        (114, 'IC Upgrade Completed', 'SAS Property Upgrade Complete | {property_address}', 10, 'Hello,

Thank you for choosing SAS to upgrade your Smoke Alarms at {property_address}. 

We have completed the upgrade at this property today for you, and the property is now compliant as per Queensland''s 2022 Smoke Alarm Legislation requirements. 

Please see your invoice/compliance certificate attached for your persual. 

Please feel free to reach out to SAS if you require any extra assistance!

Kind Regards, 
{user}
Smoke Alarm Solutions 
{agent_number}', 1, 1),
        (115, 'No Longer Servicing Area - Mackay', 'SAS | {property_address}', 6, 'Hello,

We hope this email finds you well,

SAS are just reaching out to you regarding your property at {property_address}. It is with regret that we must inform you of our decision to discontinue servicing the Mackay/Gladstone/Rockhampton region due to the unavailability of an electrician in the area.

We understand that this decision may cause inconvenience, and we sincerely apologise for any disruptions it may cause. Once again, we extend our apologies for any inconvenience this may cause, and we truly appreciate your understanding.

If you have any questions or need further information, please feel free to contact us.

Kind Regards,
{user} | SAS | {agent_number}', 0, 1),
        (116, 'Offering an appointment', 'SAS | Offer of appointment', 6, 'Hello, At Smoke Alarm Solutions (SAS), we are committed to ensuring your safety by conducting mandatory testing. SAS need to complete testing of the {serv_name}. We have the following booking available: >DATE< between >TIME< to service the {serv_name} at {p_address}. Please reply \"YES\" if this booking suits you. If this does not suit you, please reply with your preferred dates/times. Thank you, SAS Team {tenant_number}', 1, 1),
        (117, 'Failed Water Compliance - Flow Rate', 'SAS | Failed Water Compliance', 5, 'Hello,

I hope this email finds you well. Our technician visited the PROPERTY ADDRESS today for the servicing of the SERVICE TYPE.

During the visit, it was observed that the property''s water flow rate had failed due to ***INSERT WATER COMPLIANCE NOTES***.

You may be wondering what could be causing this issue and what the next steps are. It is important to know that the compliance report is true and accurate at the time that we perform the test and can vary in results at any other day we test. There are several possible reasons for low or failed water flow rate at a property, the most common ones are listed below:

•	Water pressure fluctuations: Changes in water pressure on the day can cause the flow rate to fluctuate. This can be caused by a variety of factors, including municipal water supply issues, a malfunctioning pressure regulator or problems with the homes plumbing system. 
•	Clogged pipes: If there is a blockage or build-up of debris in your pipes, this can cause the water flow to fluctuate as water struggles to pass through the narrowed area.
•	Corrosion or damage to pipes: Corrosion or damage to the pipes can cause weak spots or holes to form, which can affect the flow rate of water.
•	Faulty plumbing fixtures: If your plumbing fixtures are old or damaged, they may not be able to consistently deliver the same flow rate as newer, more efficient models.
•	Water usage: The flow rate of the water can be affected by the amount of water

Please note that SAS does not employ plumbers to test the water flow rate at properties. Our technicians can provide a water efficiency test, but they cannot fix the issue on site or investigate why the water flow rate is low/failed. If the property has failed the water flow rate at any tap, SAS recommend getting a plumber to check the property. The landlord is responsible for covering the cost of this service.

 It is the responsibility of your agency to communicate to the tenants at the property regarding the compliance in accordance to the regulations. SAS does not provide notifications directly to the tenants around any compliance. 





', 0, 0),
        (118, 'Failed Water Compliance - Flow Rate', 'SAS | Failed Water Compliance', 5, 'Hello,

I hope this email finds you well. Our technician visited the {property_address} today for the servicing of the {service_type}.

During the visit, it was observed that the property''s water flow rate had failed due to ***INSERT WATER COMPLIANCE NOTES***.

You may be wondering what could be causing this issue and what the next steps are. It is important to know that the compliance report is true and accurate at the time that we perform the test and can vary in results at any other day we test. There are several possible reasons for low or failed water flow rate at a property, the most common ones are listed below:

•    Water pressure fluctuations: Changes in water pressure on the day can cause the flow rate to fluctuate. This can be caused by a variety of factors, including municipal water supply issues, a malfunctioning pressure regulator or problems with the homes plumbing system. 
•    Clogged pipes: If there is a blockage or build-up of debris in your pipes, this can cause the water flow to fluctuate as water struggles to pass through the narrowed area.
•    Corrosion or damage to pipes: Corrosion or damage to the pipes can cause weak spots or holes to form, which can affect the flow rate of water.
•    Faulty plumbing fixtures: If your plumbing fixtures are old or damaged, they may not be able to consistently deliver the same flow rate as newer, more efficient models.
•    Water usage: The flow rate of the water can be affected by the amount of water

Please note that SAS does not employ plumbers to test the water flow rate at properties. Our technicians can provide a water efficiency test, but they cannot fix the issue on site or investigate why the water flow rate is low/failed. SAS recommend getting a plumber to check the property. The landlord is responsible for covering the cost of this service.

It is the responsibility of your agency to communicate to the tenants at the property regarding the compliance in accordance to the regulations. SAS does not provide notifications directly to the tenants around any compliance.

Kind Regards,
Smoke Alarm Solutions
{user}
{agent_number}', 0, 1),
        (119, 'Failed Water Compliance - Leaking Taps', 'SAS | Failed Water Compliance ', 5, 'Hello,

I hope this email finds you well. Our technician visited the {property_address} today for the servicing of the {service_type}.

During the visit, it was observed that the property has a leaking tap located ***INSERT LOCATION***

Our technicians can provide a water efficiency test and assess the property for leaking taps, but they cannot fix the issue on site or investigate why the tap is leaking. SAS recommend getting a plumber to check the property and rectify the leak. The landlord is responsible for covering the cost of this service.

 It is the responsibility of your agency to communicate to the tenants at the property regarding the compliance in accordance to the regulations. SAS does not provide notifications directly to the tenants around any compliance. If you would like to discuss this further, please feel free to email us or call us on {agent_number}.


Kind Regards,
Smoke Alarm Solutions
{user}
{agent_number}', 0, 1),
        (120, '0test', '0test', 2, '0test{agency_accounts_email}{agency_address}', 0, 1),
        (121, 'Tenant Request EN to be cancelled', 'Cancellation Request at {property_address}', 9, 'Good afternoon/morning *Tenant''s Name*,

I hope this email finds you well. Thank you for taking the time to chat with me today regarding the Entry Notice you received from Smoke Alarm Solutions (SAS) relating to the mandatory smoke alarm service required at the property {property_address}.

As discussed, we kindly ask that you contact your property manager to discuss your request, it''s important to note that we are unable to cancel or modify an Entry Notice without the approval of your agent.

If you have any additional questions or concerns, please feel free to reach out to us directly at 1300 852 301.

Thank you for your understanding and cooperation in this matter.

Kind Regards,
{user}
1300 852 301', 1, 1),
        (122, 'Tech marked property NLM', 'SAS | No Longer Managed Property {property_address}', 8, 'Hello, 

I hope this email finds you well,
SAS have been advised your reception team that this property is no longer managed by your agency. We have cancelled SAS servicing this property moving forward. If this has been done in error, please contact us at your earliest convenience. 

Kind Regards,
SAS
{agent_number}', 1, 1),
        (123, 'Unresponsive/Old Jobs  (1st attempt - send to tenant only)', 'SAS | Overdue Smoke Alarm Compliance {property_address}', 8, 'Hello, 

I hope this email finds you well. 

Smoke Alarm Solutions (SAS) must conduct a mandatory service of the Smoke Alarms at {property_address}. However, we have been unable to contact you despite our numerous attempts to do so. If SAS do not receive a response, we will need to contact your agency. It is imperative that we service the Smoke Alarms as soon as possible to ensure the property is safe and compliant for you. 

  
Please call SAS urgently on {tenant_number} to arrange a mutually convenient time for the servicing of the smoke alarms or please advise us if you are happy for us to collect keys from {agency_name}.

Thank you for your immediate attention to this matter.

Kind Regards,
SAS
{tenant_number}', 1, 1),
        (124, 'Unresponsive/Old Jobs  (2nd attempt - CC In agency)', 'SAS | Overdue Smoke Alarm Compliance {property_address}', 8, 'Hi There, 

I hope this email finds you well. 

Smoke Alarm Solutions (SAS) must conduct a mandatory service of the Smoke Alarms at {property_address}. However, we have been unable to contact you despite our numerous attempts to do so. We have escalated this matter with your agency, but it is imperative that we service the Smoke Alarms as soon as possible as mentioned to ensure the safety and compliance of the property. 

Please call SAS urgently on {tenant_number} to arrange a mutually convenient time for the servicing of the smoke alarms or please advise us if you are happy for us to collect keys from your {agency_name}.

Thank you for your immediate attention to this matter. 

Kind Regards,
SAS
{tenant_number}', 1, 1),
        (125, 'Unresponsive/ Old Jobs (3rd attempt - send to Agency only cc BDM)', 'SAS | Overdue Smoke Alarm Compliance {property_address}', 8, 'Hi There, 

I hope this email finds you well, 

SAS are trying to gain access of the property at {property_address} after numerous attempts of contacting the tenant and emailing your agency for assistance we have not been able to gain access to this property. This job is now overdue and SAS are very concerned for the compliance of this property. 

Can you please assist us with gaining access this property? We can attend via: 
- Notice of Entry and Key Collection 
- Meeting the property manager on site when they are doing their next routine inspection 

I look forward to hearing back from you promptly to resolve this. 

Kind Regards,
SAS
{agent_number}', 1, 1);";
	}

}
