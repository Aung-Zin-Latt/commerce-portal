<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StripeTransactions extends MY_Controller
{
    public function index()
    {
        $this->load->model('Stripe_transaction_model');

        $page = (int) $this->input->get('page');
        $meta = pagination_prepare(
            $this->Stripe_transaction_model->countAll(),
            $page ?: 1,
            10
        );
        $transactions = $this->Stripe_transaction_model->paginateWithPayments(
            $meta['per_page'],
            $meta['offset']
        );

        $this->render('admin/stripe_transactions/index', array(
            'title' => 'Stripe Transactions',
            'transactions' => $transactions,
            'pagination' => $meta,
            'breadcrumbs' => array(
                'Home' => 'admin/dashboard',
                'Stripe Transactions' => NULL,
            ),
        ));
    }
}
