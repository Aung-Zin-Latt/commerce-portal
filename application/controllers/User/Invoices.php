<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends MY_Controller
{
    /** @var Invoice_service */
    protected $invoiceService;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->invoiceService = $this->loadService('Invoice_service');
    }
    
    public function index()
    {
        $page = (int) $this->input->get('page');
        $result = $this->invoiceService->listInvoicesForUser((int) $this->auth->id(), $page ?: 1, 10);

        $this->render_store('user/invoices/index', array(
            'title' => 'Invoices',
            'invoices' => $result['invoices'],
            'pagination' => $result,
        ));
    }

    /**
     * View a single invoice with ownership authorization.
     *
     * @param int $id
     */
    public function show($id)
    {
        $this->load->model('Invoice_model');
        $this->load->model('Invoice_item_model');

        if ($this->auth->isAdmin()) {
            $invoice = $this->Invoice_model->findById((int) $id);
            if (!$invoice) {
                $this->deny_resource_access();
                return;
            }
            $items = $this->Invoice_item_model->getByInvoiceId((int) $invoice->id);
        } else {
            $data = $this->invoiceService->getInvoiceWithItemsForUserOrFail(
                (int) $id,
                (int) $this->auth->id()
            );
            $invoice = $data['invoice'];
            $items = $data['items'];
        }

        $this->render_store('user/invoices/show', array(
            'title' => 'Invoice ' . $invoice->invoice_number,
            'invoice' => $invoice,
            'items' => $items,
        ));
    }
}
