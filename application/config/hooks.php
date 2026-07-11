<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/

// Sanitize → Rate limit → Auth (order matters)
$hook['post_controller_constructor'][] = array(
    'class'    => 'Sanitize_input_middleware',
    'function' => 'run',
    'filename' => 'Sanitize_input_middleware.php',
    'filepath' => 'hooks',
);

// Throttle middleware runs first to limit API requests
$hook['post_controller_constructor'][] = array(
    'class'    => 'Rate_limit_middleware',
    'function' => 'run',
    'filename' => 'Rate_limit_middleware.php',
    'filepath' => 'hooks',
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'Auth_middleware',
    'function' => 'run',
    'filename' => 'Auth_middleware.php',
    'filepath' => 'hooks',
);