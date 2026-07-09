<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Public Routes (No Middleware)
|--------------------------------------------------------------------------
|
| Controllers and directories listed here can be accessed without
| authentication. Everything else is protected by Auth_middleware.
|
*/
$config['auth_public_controllers'] = array('home', 'test');

$config['auth_public_directories'] = array(
    'auth/',
    'cli/',
    'webhooks/',
);

/*
|--------------------------------------------------------------------------
| Route Middleware Rules
|--------------------------------------------------------------------------
|
| CI3 does not have Laravel-style middleware. This config is consumed by
| application/hooks/Auth_middleware.php before controller actions run.
|
| Keys are lowercase controller directories (admin/, user/).
|
*/
$config['auth_middleware'] = array(
    'admin/' => array(
        'type'             => 'session',
        'role'             => 'admin',
        'login_redirect'   => 'login',
        'denied_redirect'  => '',
        'login_message'    => 'Please log in first.',
        'denied_message'   => 'You do not have permission to access the admin panel.',
    ),
    'user/' => array(
        'type'             => 'session',
        'role'             => 'customer',
        'login_redirect'   => 'login',
        'denied_redirect'  => 'admin/dashboard',
        'login_message'    => 'Please log in first.',
        'denied_message'   => 'You do not have permission to access that page.',
    ),
);
