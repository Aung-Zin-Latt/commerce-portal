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
*/
$route['login'] = 'Auth/Login';
$route['login/authenticate'] = 'Auth/Login/authenticate';
$route['logout'] = 'Auth/Login/logout';
$route['register'] = 'Auth/Register';
$route['register/store'] = 'Auth/Register/store';

/*
|--------------------------------------------------------------------------
| Test Routes
|--------------------------------------------------------------------------
*/
$route['test'] = 'test/bootstrap';

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
$route['admin'] = 'Admin/Dashboard';
$route['admin/dashboard'] = 'Admin/Dashboard';

$route['admin/users'] = 'Admin/Users/index';
$route['admin/users/create'] = 'Admin/Users/create';
$route['admin/users/store'] = 'Admin/Users/store';
$route['admin/users/edit/(:num)'] = 'Admin/Users/edit/$1';
$route['admin/users/update/(:num)'] = 'Admin/Users/update/$1';
$route['admin/users/delete/(:num)'] = 'Admin/Users/delete/$1';
$route['admin/users/restore/(:num)'] = 'Admin/Users/restore/$1';

$route['admin/products'] = 'Admin/Products/index';
$route['admin/products/create'] = 'Admin/Products/create';
$route['admin/products/store'] = 'Admin/Products/store';
$route['admin/products/edit/(:num)'] = 'Admin/Products/edit/$1';
$route['admin/products/update/(:num)'] = 'Admin/Products/update/$1';
$route['admin/products/delete/(:num)'] = 'Admin/Products/delete/$1';

/*
|--------------------------------------------------------------------------
| Reserved Routes
|--------------------------------------------------------------------------
*/
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
