<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StripeTransactions extends MY_Controller
{
    public function index()
    {
        $this->render('admin/placeholder', array(
            'title' => 'Stripe Transactions',
            'page_heading' => 'Stripe Transactions',
            'page_description' => 'Admin Stripe transactions page is ready for implementation.',
            'breadcrumbs' => array(
                'Home' => '',
                'Stripe Transactions' => NULL,
            ),
        ));
    }
}
