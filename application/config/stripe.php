<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['stripe_public_key'] = getenv('STRIPE_PUBLIC_KEY') ?: '';
$config['stripe_secret_key'] = getenv('STRIPE_SECRET_KEY') ?: '';
$config['stripe_webhook_secret'] = getenv('STRIPE_WEBHOOK_SECRET') ?: '';
$config['stripe_currency'] = 'sgd';