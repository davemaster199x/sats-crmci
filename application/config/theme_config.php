<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Theme management set here
$config['theme'] = $_ENV['THEME'] ?? 'sats';

$config['company_name_short'] = $_ENV['company_name_short'] ?? strtoupper($_ENV['THEME']) ?? 'SATS';
$config['COMPANY_FULL_NAME'] = $_ENV['COMPANY_FULL_NAME'] ?? $_ENV['COMPANY_FULL_NAME'] ?? 'Smoke Alarm Testing Services';

$config['theme_email_from'] = $_ENV['THEME_EMAIL_FROM'] ?? $config['company_name_short'] . ' Team';

//document reports pdf template
// TODO - this is not how to do config settings
$config['COMPLIANCE_LETTER_HEAD'] = ($config['theme'] == 'sats' ? FCPATH.'theme/pdf_templates/sats/AU_Water_Cert_header_only.pdf' : FCPATH.'theme/pdf_templates/sas/ComplianceReportLetterhead.pdf');

