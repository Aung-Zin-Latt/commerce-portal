<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StripeTransactions extends MY_Controller
{
    public function index()
    {
        $this->load->model('Stripe_transaction_model');
        $transactions = $this->Stripe_transaction_model->getAllWithPayments();

        $this->render('admin/stripe_transactions/index', array(
            'title' => 'Stripe Transactions',
            'transactions' => $transactions,
            'breadcrumbs' => array(
                'Home' => 'admin/dashboard',
                'Stripe Transactions' => NULL,
            ),
        ));
    }
}
