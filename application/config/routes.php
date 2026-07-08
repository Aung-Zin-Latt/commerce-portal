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
$route['default_controller'] = 'Home';

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
|
| Friendly authentication URLs.
|
*/
$route['login'] = 'Auth/Login';
$route['login/authenticate'] = 'Auth/Login/authenticate';
$route['logout'] = 'Auth/Login/logout';
$route['register'] = 'Auth/Register';
$route['register/store'] = 'Auth/Register/store';

$route['test'] = 'test/bootstrap';

/*
|--------------------------------------------------------------------------
| Reserved Routes
|--------------------------------------------------------------------------
*/
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
