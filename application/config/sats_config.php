<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// This should be changed to country code AU/NZ
$config['app_url'] = parse_url($_ENV['APP_URL'], PHP_URL_HOST);
$config['domain'] = parse_url($_ENV['APP_URL'], PHP_URL_HOST);
$config['country'] = (int) $_ENV['COMPANY_COUNTRY_ID'] ?? 1;
if($config['country'] == 2){
	$config['cc'] = 'nz';
} else {
	$config['cc'] = 'au';
}

$config['company_planner_url'] = $_ENV['COMPANY_PLANNER_URL'] ?? '';
$config['customer_service'] = $_ENV['CUSTOMER_SERVICE'];

// set timezone
if ($config['country'] == 2) {
    date_default_timezone_set('Pacific/Auckland');
    $config['country_code'] = '+64';
} else {
    date_default_timezone_set('Australia/Brisbane');
    $config['country_code'] = '+61';
}

$config['user_photo'] = '/uploads/user_accounts/photo';
$config['photo_empty'] = 'blank/avatar-2-64.png';
$config['pagi_per_page'] = 50; // pagination per page

// set this if agency CI is now using the agency.sats.com domain
$agency_domain_used = 0;

// CRM link - determine the second-level domain name as our base domain for email addresses etc
// Updated to accommodate our migration back to the crm domain
// this splits it into 3 parts: subsubdomain | subdomain | domain
$pattern = '/((?:crm|crmci)\.)([a-z]+\.)?((?:sats|smokealarmsolutions).*)/m';
preg_match($pattern, $config['domain'], $matches);
//var_dump($pattern, $config['domain'], $matches);
if(!empty($matches)){
    $config['env_subdomain'] = $matches[2] ?? '';
    $config['base_domain'] = $matches[3];

    $config['crmci_link']  = rtrim($config['base_url'], '/');
    $config['crm_link']    = 'https://crm.' . $config['env_subdomain'] . $config['base_domain'];
    $config['agency_link'] = 'https://agency.' . $config['env_subdomain'] . $config['base_domain'];

} else {
    $error = 'CONFIG ERROR - base domain unknown';
    log_message('error', $error);
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo $error;
    exit(3); // EXIT_CONFIG
}


$config['sats_new_tenant'] = 1;


// Company Info/Details
$config['company_full_name'] = $_ENV['COMPANY_FULL_NAME'] ?? 'Smoke Alarm Testing Services';



// Pusher Details - Real time notification updates
$config['PUSHER_APP_ID'] = $_ENV['PUSHER_APP_ID'];
$config['PUSHER_KEY'] = $_ENV['PUSHER_KEY'];
$config['PUSHER_SECRET'] = $_ENV['PUSHER_SECRET'];
$config['PUSHER_CLUSTER'] = $_ENV['PUSHER_CLUSTER'];

// Property Me
$config['PME_CLIENT_ID'] = $_ENV['PME_CLIENT_ID'];
$config['PME_CLIENT_SECRET'] = $_ENV['PME_CLIENT_SECRET'];
$config['PME_URL_CALLBACK'] = $_ENV['APP_URL'] . "property_me/callback"; // No need to switch
$config['PME_CLIENT_Scope'] = "contact:read%20property:read%20property:write%20activity:read%20communication:read%20transaction:read%20transaction:write%20offline_access";
$config['PME_ACCESS_TOKEN_URL'] = "https://login.propertyme.com/connect/token";
$config['PME_AUTHORIZE_URL'] = "https://login.propertyme.com/connect/authorize";

// Blink API
$config['blink_email'] = $_ENV['BLINK_EMAIL'];
$config['blink_pass'] = $_ENV['BLINK_PASS'];
$config['blink_refresh_token'] = $_ENV['BLINK_REFRESH_TOKEN'];
$config['blink_domain_id'] = $_ENV['BLINK_DOMAIN_ID'];

// Wholesale SMS
$config['ws_sms_reply_url'] = $_ENV['APP_URL'] . 'sms/wholesalesms_reply';
$config['ws_sms_dlvr_url'] = $_ENV['APP_URL'] . 'sms/wholesalesms_delivery';
$config['ws_sms_api_key'] = $_ENV['WHOLESALE_SMS_API_KEY'];
$config['ws_sms_api_secret'] = $_ENV['WHOLESALE_SMS_API_SECRET'];

// YABBR
$config['yabbr_switch'] = 1;
$config['yabbr_sms_api_key'] = $_ENV['YABBR_API_KEY'];
$config['yabbr_virtual_number'] = $_ENV['YABBR_VIRTUAL_NUMBER'];

// Palace API
$config['palace_api_base_liquid'] = 'https://api.getpalace.com'; // liquid system (new)
$config['palace_api_base_legacy'] = 'https://serviceapia.realbaselive.com'; // legacy system (old)

// Google
$config['gmap_api_key'] = $_ENV['GOOGLE_MAPS_API_KEY'];
$config['google_tag'] = $_ENV['GOOGLE_TAG'] ?? '';
//swal
$config['showConfirmButton'] = 'false';
$config['timer'] = 2000; //2sec

// accounts date filter
if( $config['country'] == 1 ){ // AU
    $config['accounts_financial_year'] = '2020-06-01';
}else if( $config['country'] == 2 ){ // NZ
    $config['accounts_financial_year'] = '2019-12-01';
}



############################################################################################################
# User Access
############################################################################################################
## VAD users allowed to Unlink COnnected API Properties button
$config['allowed_people_to_pme_unlink'] = [1];

## VAD API ALLOWED TO EDIT/UPDATE > CHECKBOX
$config['user_can_edit_api'] = [1];

##Allowed to edit/assign homepage content block per user class
$config['allow_to_edit_user_class_block'] = [1];

############################################################################################################
# Products / Services
############################################################################################################
## GHERX: VAD PRICING AND ADD AGENCY page > alarms allowed to add with 0 price
if($config['country']==1){ #AU allowed zero price
    $config['alarm_allowed_zero_price'] = [1,2,4,7,18,19,20,21]; ##Allow 0 price for 240V,9V,240vLi,9v(EP),240v(EP)
}else{ #NZ allowed zero price
    $config['alarm_allowed_zero_price'] = [2,7,11,12]; ##Allow 0 price for 240V,3Vli,3vLi(Orc)
}
// default 240v RF alarm price
$config['fallback_price_for_ic_alarms_without_a_price_set'] = $_ENV['COMPANY_DEFAULT_QUOTE_AMOUNT_FOR_QLD_UPGRADES'] ?? 200;

// sales commission version switch
$config['sales_commission_ver'] = 'new';

##renewal_start_offset
$config['renewal_start_offset_default'] = 15;	##15 days
$config['renewal_start_offset_nsw'] = 30;	##30 days

##Allowed to edit/assign homepage content block per user class
if($config['country']==1){
	$config['allow_to_edit_user_class_block'] = array(2025,2070,2428,2287,11); //Daniel, DevTest, Charlote B, Ben, Vanessah
}else{
	$config['allow_to_edit_user_class_block'] = array(2025,2070,2289,2231,11); //Daniel, DevTest, Charlote B, Ben, Vanessah
}

## HashIds Salt
$config['hash_salt'] = $_ENV['HASH_SALT'];