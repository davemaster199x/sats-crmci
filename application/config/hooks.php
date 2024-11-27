<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
// Needed for API authentication
$hook['post_controller_constructor'][] = [
    'class' => 'ApiHooks',
    'function' => 'checkToken',
    'filename' => 'ApiHooks.php',
    'filepath' => 'hooks',
    'params' => [],
];

$hook['post_controller_constructor'][] = [
    'class' => 'TemplateHooks',
    'function' => 'preloadData',
    'filename' => 'TemplateHooks.php',
    'filepath' => 'hooks',
    'params' => [],
];

$hook['post_controller_constructor'][] = function() {
	// Remove our env settings from $_SERVER for security reasons
	// This must be done after initial config loading is done
//	foreach(array_keys($_ENV) as $array_key) {
//		unset($_SERVER[$array_key]);
//	}
};
$hook['pre_system'][] = [
    'class' => 'PageLoadHooks',
    'function' => 'pre_system',
    'filename' => 'PageLoadHooks.php',
    'filepath' => 'hooks',
    'params' => [],
];

$hook['pre_controller'][] = [
	'class' => 'PageLoadHooks',
	'function' => 'pre_controller',
	'filename' => 'PageLoadHooks.php',
	'filepath' => 'hooks',
	'params' => [],
];

$hook['post_controller_constructor'][] = [
	'class' => 'PageLoadHooks',
	'function' => 'post_controller_constructor',
	'filename' => 'PageLoadHooks.php',
	'filepath' => 'hooks',
	'params' => [],
];

$hook['post_controller'][] = [
	'class' => 'PageLoadHooks',
	'function' => 'post_controller',
	'filename' => 'PageLoadHooks.php',
	'filepath' => 'hooks',
	'params' => [],
];
/*
$hook['display_override'][] = [
	'class' => 'PageLoadHooks',
	'function' => 'display_override',
	'filename' => 'PageLoadHooks.php',
	'filepath' => 'hooks',
	'params' => [],
];

$hook['cache_override'][] = [
	'class' => 'PageLoadHooks',
	'function' => 'cache_override',
	'filename' => 'PageLoadHooks.php',
	'filepath' => 'hooks',
	'params' => [],
];
*/

$hook['post_system'][] = [
    'class' => 'PageLoadHooks',
    'function' => 'post_system',
    'filename' => 'PageLoadHooks.php',
    'filepath' => 'hooks',
    'params' => [],
];