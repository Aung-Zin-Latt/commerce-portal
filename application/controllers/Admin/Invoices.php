<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends MY_Controller
{
    /** @var Invoice_service */
    protected $invoiceService;

    public function __construct()
    {
        parent::__construct();
        $this->invoiceService = $this->loadService('Invoice_service');
    }

    public function index()
    {
        $page = (int) $this->input->get('page');
        $result = $this->invoiceService->listAllInvoices($page ?: 1, 10);

        $this->render('admin/invoices/index', array(
            'title' => 'Invoices',
            'invoices' => $result['invoices'],
            'pagination' => $result,
            'breadcrumbs' => array(
                'Home' => 'admin/dashboard',
                'Invoices' => NULL,
            ),
        ));
    }

    public function show($id)
    {
        $data = $this->invoiceService->getInvoiceWithItemsOrFail((int) $id);

        $this->load->model('User_model');
        $customer = $this->User_model->findById((int) $data['invoice']->user_id);

        $order = $this->db
            ->select('id, order_number')
            ->where('id', (int) $data['invoice']->order_id)
            ->get('orders')
            ->row();

        $this->render('admin/invoices/show', array(
            'title' => 'Invoice ' . $data['invoice']->invoice_number,
            'invoice' => $data['invoice'],
            'items' => $data['items'],
            'customer' => $customer,
            'order' => $order,
            'breadcrumbs' => array(
                'Home' => 'admin/dashboard',
                'Invoices' => 'admin/invoices',
                $data['invoice']->invoice_number => NULL,
            ),
        ));
    }
}
