<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Default Routes
|--------------------------------------------------------------------------
|
| The application entry point is the authentication module.
| Users are redirected here when visiting the root URL.
|
*/
$route['default_controller'] = 'Auth/Login';

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
|
| Friendly authentication URLs.
|
*/
$route['login']  = 'Auth/Login';
$route['logout'] = 'Auth/Login/logout';

/*
|--------------------------------------------------------------------------
| Reserved Routes
|--------------------------------------------------------------------------
*/
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;