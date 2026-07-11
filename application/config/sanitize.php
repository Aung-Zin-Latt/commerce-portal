<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['sanitize_enabled'] = TRUE;

// Do not strip these fields (passwords may contain < > & etc.)
$config['sanitize_except_fields'] = array(
    'password',
    'password_confirm',
    'csrf_test_name',
);

// Do not touch these URI prefixes (Stripe signature needs raw body)
$config['sanitize_except_directories'] = array(
    'webhooks/',
);