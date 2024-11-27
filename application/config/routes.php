<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

// properties
$route['properties/properties_with_multiple_services'] = 'properties/get_properties_with_multiple_services';

$route['log_rotation'] = 'logrotation';

/**
 * Handle group routes for PDF Combined
 */
$route['pdf/combined/(:any)'] = 'pdf/view_combined/$1';
$route['pdf/combined/(:any)/(:any)'] = 'pdf/view_combined/$1/$2';

/**
 * Handle group routes with multiple parameter for PDF Certificates
 */
$route['pdf/certificates/(:any)'] = 'pdf/view_certificate/$1';
$route['pdf/certificates/(:any)/(:any)'] = 'pdf/view_certificate/$1/$2';
$route['pdf/certificates/(:any)/(:any)/(:any)'] = 'pdf/view_certificate/$1/$2/$3';

/**
 * Handle group routes with multiple parameter for PDF Invoices
 */
$route['pdf/invoices/(:any)'] = 'pdf/view_invoice/$1';
$route['pdf/invoices/(:any)/(:any)'] = 'pdf/view_invoice/$1/$2';
$route['pdf/invoices/(:any)/(:any)/(:any)'] = 'pdf/view_invoice/$1/$2/$3';

/**
 * Handle group routes with multiple parameter for PDF Quotes
 */
$route['pdf/quotes/(:any)'] = 'pdf/view_quote/$1';
$route['pdf/quotes/(:any)/(:any)'] = 'pdf/view_quote/$1/$2';
$route['pdf/quotes/(:any)/(:any)/(:any)'] = 'pdf/view_quote/$1/$2/$3';
$route['pdf/quotes/(:any)/(:any)/(:any)/(:any)'] = 'pdf/view_quote/$1/$2/$3/$4';

/**
 * Handle group routes with multiple parameter for PDF Safe Work Method Statement
 */
$route['pdf/swms/(:any)'] = 'pdf/safe_work_method_statement/$1';
$route['pdf/swms/(:any)/(:any)'] = 'pdf/safe_work_method_statement/$1/$2';


$route['pdf/entry_notice/(:any)'] = 'pdf/entry_notice/$1';