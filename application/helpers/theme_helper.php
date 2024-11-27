<?php

if (!function_exists('theme')) {
    function theme($param){
        $env_path = FCPATH.'.env';

        if (file_exists($env_path)) {
            return base_url('theme/'.$_ENV['THEME'].'/'.$param);
        }
        return base_url('theme/sats/'.$param);
    }
}

if (!function_exists('pdf_template')) {
    function pdf_template($param){
        $env_path = FCPATH.'.env';

        if (file_exists($env_path)) {
            return base_url('theme/pdf_templates/'.$_ENV['THEME'].'/'.$param);
        }
        return base_url('theme/pdf_templates/sats/'.$param);
    }
}



if (!function_exists('logo')) {
    function logo() {
        $env_path = FCPATH.'.env'; 

        if (file_exists($env_path)) {
            if($_ENV['THEME'] === 'sats'){
                return base_url('/images/logo_login.png');
            } else {
                return base_url('theme/sas/images/logo_login.svg');
            }
        }
        return base_url('/images/logo_login.png');
    }
}

if (!function_exists('get_app_email_from_header')) {
    function get_app_email_from_header() {
        $env_path = FCPATH .'.env';
        if (file_exists($env_path)) {
            return strtoupper($_ENV['theme'])." - " . strtoupper($_ENV['company_full_name']) ?? "";
        }
    }
}

/**
 * This will generate vCard filepath for tenants and they would be able to download the vCard
 */
if (!function_exists('get_vcard')) {
	function get_vcard() {
		$env_path = FCPATH . '.env';
		
		if (file_exists($env_path)) {
			if (config_item('theme') === 'sats') {
				log_message('info', 'Generate sats vCard filepath');
				return config_item('country') === 1 ? base_url('/theme/sats/vcard/contact_card_sats_au.vcf') : base_url('/theme/sats/vcard/contact_card_sats_nz.vcf');
			} else {
				log_message('info', 'Generate sas vCard filepath');
				return base_url('/theme/sas/vcard/contact_card_sas.vcf');
			}
		}
		return false;
	}
}
