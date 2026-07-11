<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| CI3 Rate limits
|--------------------------------------------------------------------------
|
| Keys = lowercase "{directory}{class}/{method}" from the router.
| max    = allowed hits in the window
| window = seconds
|
*/
$config['rate_limit_enabled'] = TRUE;

// Must be named rate_limits — Rate_limit_middleware reads this key.
$config['rate_limits'] = array(
    // Web login submit → Auth/Login::authenticate
    'auth/login/authenticate' => array(
        'max' => 5,
        'window' => 60,
    ),
    // API login → Api/V1/Login::index (directory is api/v1/)
    'api/v1/login/index' => array(
        'max' => 10,
        'window' => 60,
    ),
);