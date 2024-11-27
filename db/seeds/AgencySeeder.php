<?php


use Phinx\Seed\AbstractSeed;

class AgencySeeder extends AbstractSeed
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


    private function agency(){
        return "insert into agency (agency_id, agency_name, address_1, address_2, address_3, phone, state, postcode, tot_properties, letter1, letter2, letter3, noletter, mailing_as_above, mailing_address, login_id, password, status, account_emails, accounts_name, accounts_phone, send_emails, agency_emails, p_tmp, p_converted, custom_alarm_pricing, send_combined_invoice, agency_region_id, salesrep, send_entry_notice, alt_agency_id, tmh_imported, contact_first_name, contact_last_name, contact_phone, contact_email, pass_timestamp, require_work_order, allow_indiv_pm, tot_prop_timestamp, franchise_groups_id, agency_using_id, legal_name, country_id, comment, auto_renew, key_allowed, key_email_req, agency_hours, lat, lng, postcode_region_id, phone_call_req, abn, allow_dk, website, allow_en, agency_specific_notes, team_meeting, new_job_email_to_agent, save_notes, escalate_notes, escalate_notes_ts, tenant_details_contact_name, tenant_details_contact_phone, display_bpay, agency_special_deal, trust_account_software, allow_indiv_pm_email_cc, allow_upfront_billing, joined_sats, invoice_pm_only, pt_completed, pt_no_statement_needed, pt_sent_to_va, tas_connected, propertyme_agency_id, send_statement_email_ts, electrician_only, initial_setup_done, esclate_notes_last_updated_by, subscription_notes, subscription_notes_update_ts, subscription_notes_update_by, multi_owner_discount, deactivated_ts, deactivated_reason, active_prop_with_sats, send_en_to_agency, en_to_pm, load_api, statements_agency_comments, statements_agency_comments_ts, pme_supplier_id, accounts_reports, palace_supplier_id, ourtradie_supplier_id, palace_agent_id, palace_diary_id, api_billable, no_bulk_match, exclude_free_invoices, send_48_hr_key, cc_landlord, add_inv_to_agen, high_touch, deleted, deleted_timestamp, display_discount_on_invoice, display_surcharge_on_invoice)
values  (NULL, 'Agency 1', '1', 'Union Circuit', 'Yatala', '', 'QLD', '4207', 0, 0, 0, 0, 0, 1, null, null, null, 'active', 'admin@sats.com.au', '', '', 0, 'admin@sats.com.au', null, 1, 0, 1, 0, 1, 1, null, null, 'Agency', 'Contact', '', 'admin@sats.com.au', '2023-10-02 17:37:43', 0, 1, '2023-10-02 17:37:43', 7, 0, '', 1, '', 1, 1, 0, '', '-27.7473785', '153.2256541', 191, null, '', 1, '', -1, '', null, 0, null, null, null, null, null, 0, '', null, 0, 1, '2023-10-02', 0, null, null, null, 0, null, null, 0, 0, null, null, null, null, null, null, null, null, 1, 0, 1, null, null, '', 0, '', '', '', null, 1, 0, 0, 0, 0, 0, 0, 0, null, 1, 0),
        (NULL, 'Private QLD', '', '', '', '', 'QLD', '4000', 0, 0, 0, 0, 0, 1, null, 'private_qld', '', 'active', 'accounts@smokealarmsolutions.com.au', '', '', 0, 'accounts@smokealarmsolutions.com.au', null, 1, 0, 0, 0, 2195, 0, null, null, '', '', '', '', '2017-02-15 16:35:43', 0, 1, '2017-02-15 16:35:43', 10, 0, '', 1, '', 0, 0, 0, '', '-34.8956922', '138.6413941', 70, 1, '', 0, '', 0, '', '', 0, 1, '', '2023-10-26 14:19:58', '', '', 1, '', 0, 0, 0, null, 0, null, null, null, 0, null, null, 0, 0, 2565, null, null, null, null, null, null, null, 1, 0, 1, '', '0000-00-00 00:00:00', '', 0, 0, null, null, null, 1, 0, 0, 0, 0, 0, 0, 0, null, 1, 0),
        (NULL, 'Private NSW', '', '', '', '', 'NSW', '2000', 0, 0, 0, 0, 0, 1, null, 'private_nsw', '', 'active', 'accounts@smokealarmsolutions.com.au', '', '', 0, 'accounts@smokealarmsolutions.com.au', null, 1, 0, 0, 0, 2195, 0, null, null, '', '', '', '', '2017-02-15 16:35:43', 0, 1, '2017-02-15 16:35:43', 10, 0, '', 1, '', 0, 0, 0, '', '-34.8956922', '138.6413941', 70, 1, '', 0, '', 0, '', '', 0, 1, '', '2023-10-26 14:19:58', '', '', 1, '', 0, 0, 0, null, 0, null, null, null, 0, null, null, 0, 0, 2565, null, null, null, null, null, null, null, 1, 0, 1, '', '0000-00-00 00:00:00', '', 0, 0, null, null, null, 1, 0, 0, 0, 0, 0, 0, 0, null, 1, 0),
        (NULL, 'Private VIC', '', '', '', '', 'VIC', '3000', 0, 0, 0, 0, 0, 1, null, 'private_vic', '', 'active', 'accounts@smokealarmsolutions.com.au', '', '', 0, 'accounts@smokealarmsolutions.com.au', null, 1, 0, 0, 0, 2195, 0, null, null, '', '', '', '', '2017-02-15 16:35:43', 0, 1, '2017-02-15 16:35:43', 10, 0, '', 1, '', 0, 0, 0, '', '-34.8956922', '138.6413941', 70, 1, '', 0, '', 0, '', '', 0, 1, '', '2023-10-26 14:19:58', '', '', 1, '', 0, 0, 0, null, 0, null, null, null, 0, null, null, 0, 0, 2565, null, null, null, null, null, null, null, 1, 0, 1, '', '0000-00-00 00:00:00', '', 0, 0, null, null, null, 1, 0, 0, 0, 0, 0, 0, 0, null, 1, 0),
        (NULL, 'Private TAS', '', '', '', '', 'TAS', '7000', 0, 0, 0, 0, 0, 1, null, 'private_tas', '', 'active', 'accounts@smokealarmsolutions.com.au', '', '', 0, 'accounts@smokealarmsolutions.com.au', null, 1, 0, 0, 0, 2195, 0, null, null, '', '', '', '', '2017-02-15 16:35:43', 0, 1, '2017-02-15 16:35:43', 10, 0, '', 1, '', 0, 0, 0, '', '-34.8956922', '138.6413941', 70, 1, '', 0, '', 0, '', '', 0, 1, '', '2023-10-26 14:19:58', '', '', 1, '', 0, 0, 0, null, 0, null, null, null, 0, null, null, 0, 0, 2565, null, null, null, null, null, null, null, 1, 0, 1, '', '0000-00-00 00:00:00', '', 0, 0, null, null, null, 1, 0, 0, 0, 0, 0, 0, 0, null, 1, 0),
        (NULL, 'Private SA', '', '', '', '', 'SA', '5000', 0, 0, 0, 0, 0, 1, null, 'private_sa', '', 'active', 'accounts@smokealarmsolutions.com.au', '', '', 0, 'accounts@smokealarmsolutions.com.au', null, 1, 0, 0, 0, 2195, 0, null, null, '', '', '', '', '2017-02-15 16:35:43', 0, 1, '2017-02-15 16:35:43', 10, 0, '', 1, '', 0, 0, 0, '', '-34.8956922', '138.6413941', 70, 1, '', 0, '', 0, '', '', 0, 1, '', '2023-10-26 14:19:58', '', '', 1, '', 0, 0, 0, null, 0, null, null, null, 0, null, null, 0, 0, 2565, null, null, null, null, null, null, null, 1, 0, 1, '', '0000-00-00 00:00:00', '', 0, 0, null, null, null, 1, 0, 0, 0, 0, 0, 0, 0, null, 1, 0),
        (NULL, 'Private WA', '', '', '', '', 'WA', '6000', 0, 0, 0, 0, 0, 1, null, 'private_wa', '', 'active', 'accounts@smokealarmsolutions.com.au', '', '', 0, 'accounts@smokealarmsolutions.com.au', null, 1, 0, 0, 0, 2195, 0, null, null, '', '', '', '', '2017-02-15 16:35:43', 0, 1, '2017-02-15 16:35:43', 10, 0, '', 1, '', 0, 0, 0, '', '-34.8956922', '138.6413941', 70, 1, '', 0, '', 0, '', '', 0, 1, '', '2023-10-26 14:19:58', '', '', 1, '', 0, 0, 0, null, 0, null, null, null, 0, null, null, 0, 0, 2565, null, null, null, null, null, null, null, 1, 0, 1, '', '0000-00-00 00:00:00', '', 0, 0, null, null, null, 1, 0, 0, 0, 0, 0, 0, 0, null, 1, 0),
        (NULL, 'Private NT', '', '', '', '', 'NT', '0800', 0, 0, 0, 0, 0, 1, null, 'private_nt', '', 'active', 'accounts@smokealarmsolutions.com.au', '', '', 0, 'accounts@smokealarmsolutions.com.au', null, 1, 0, 0, 0, 2195, 0, null, null, '', '', '', '', '2017-02-15 16:35:43', 0, 1, '2017-02-15 16:35:43', 10, 0, '', 1, '', 0, 0, 0, '', '-34.8956922', '138.6413941', 70, 1, '', 0, '', 0, '', '', 0, 1, '', '2023-10-26 14:19:58', '', '', 1, '', 0, 0, 0, null, 0, null, null, null, 0, null, null, 0, 0, 2565, null, null, null, null, null, null, null, 1, 0, 1, '', '0000-00-00 00:00:00', '', 0, 0, null, null, null, 1, 0, 0, 0, 0, 0, 0, 0, null, 1, 0),
        (NULL, 'Private ACT', '', '', '', '', 'ACT', '2600', 0, 0, 0, 0, 0, 1, null, 'private_act', '', 'active', 'accounts@smokealarmsolutions.com.au', '', '', 0, 'accounts@smokealarmsolutions.com.au', null, 1, 0, 0, 0, 2195, 0, null, null, '', '', '', '', '2017-02-15 16:35:43', 0, 1, '2017-02-15 16:35:43', 10, 0, '', 1, '', 0, 0, 0, '', '-34.8956922', '138.6413941', 70, 1, '', 0, '', 0, '', '', 0, 1, '', '2023-10-26 14:19:58', '', '', 1, '', 0, 0, 0, null, 0, null, null, null, 0, null, null, 0, 0, 2565, null, null, null, null, null, null, null, 1, 0, 1, '', '0000-00-00 00:00:00', '', 0, 0, null, null, null, 1, 0, 0, 0, 0, 0, 0, 0, null, 1, 0);
        
        ";
    }
	
	private function agency_user_accounts()
	{
		return "INSERT INTO agency_user_accounts (email, fname, lname, password, agency_id, user_type, phone, job_title, photo, reset_password_code, reset_password_code_ts, password_changed_ts, hide_welcome_msg, active, date_created, alt_agencies) 
VALUES ('portaluser@sats.com.au', 'Test', 'User', '$2y$10$1QRwhEEq4dzILfhSQE88TeKPLqRQOWA/nPf1u0Jhh9IP3xHl9shsK', 1, 1, '', '', null, null, null, '2023-10-09 15:27:22', 1, 1, '2023-08-03 10:42:46', null);
";

	}

    private function agency_alarms(){
        return "insert into agency_alarms (agency_alarm_id, agency_id, alarm_pwr_id, price)
values  (1, 1, 10, 100),
        (2, 1, 22, 100);";

    }

    private function agency_maintenance(){
        return "insert into agency_maintenance (agency_maintenance_id, agency_id, maintenance_id, price, surcharge, display_surcharge, surcharge_msg, updated_date, status)
values  (1, 1, 0, 0, null, null, '', null, 1);";

    }
    private function agency_services(){
        return "insert into agency_services (agency_services_id, agency_id, service_id, price)
values  (1, 1, 12, 0);";

    }

    private function agency_user_account_types(){
        return "insert into agency_user_account_types (agency_user_account_type_id, user_type_name, sort_index, active)
values  (1, 'Admin', 2, 1),
        (2, 'Property Manager', 1, 1),
        (3, 'Sales', 3, 1);";

    }

}
