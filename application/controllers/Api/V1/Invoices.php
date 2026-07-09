<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends MY_Api_Controller
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
        $rows = $this->invoiceService->listInvoicesForUser((int) $this->auth->id());
        $list = array();

        foreach ($rows as $row) {
            $list[] = $this->formatInvoice($row);
        }

        return json_success($list);
    }

    public function show($id)
    {
        $this->load->model('Invoice_model');
        $this->load->model('Invoice_item_model');

        $invoice = $this->Invoice_model->findByIdForUser((int) $id, (int) $this->auth->id());

        if (!$invoice) {
            return json_error('Resource not found.', 404);
        }

        $items = $this->Invoice_item_model->getByInvoiceId((int) $invoice->id);
        $lineItems = array();

        foreach ($items as $item) {
            $lineItems[] = array(
                'id' => (int) $item->id,
                'product_name' => $item->product_name,
                'unit_price' => (float) $item->unit_price,
                'quantity' => (int) $item->quantity,
                'subtotal' => (float) $item->subtotal,
            );
        }

        $payload = $this->formatInvoice($invoice);
        $payload['items'] = $lineItems;

        return json_success($payload);
    }

    protected function formatInvoice($invoice)
    {
        return array(
            'id' => (int) $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'order_id' => (int) $invoice->order_id,
            'payment_id' => (int) $invoice->payment_id,
            'subtotal' => (float) $invoice->subtotal,
            'tax_amount' => (float) $invoice->tax_amount,
            'total_amount' => (float) $invoice->total_amount,
            'currency' => $invoice->currency,
            'issued_at' => $invoice->issued_at,
        );
    }
}