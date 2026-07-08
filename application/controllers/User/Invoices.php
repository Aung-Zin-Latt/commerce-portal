<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends MY_Controller
{
    public function index()
    {
        $this->load->library('auth');
        $this->load->model('Invoice_model');

        $invoices = $this->Invoice_model->getAllForUser((int) $this->auth->id());

        $this->render_store('user/store/placeholder', array(
            'title' => 'Invoices',
            'page_heading' => 'Invoices',
            'page_description' => empty($invoices)
                ? 'No invoices yet. Completed purchases will appear here.'
                : 'You have ' . count($invoices) . ' invoice(s).',
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
        $this->load->library('auth');

        if ($this->auth->isAdmin()) {
            $invoice = $this->Invoice_model->findById((int) $id);
        } else {
            $invoice = $this->Invoice_model->findByIdForUser((int) $id, (int) $this->auth->id());
        }

        if (!$invoice) {
            $this->deny_resource_access();
            return;
        }

        $this->render_store('user/store/placeholder', array(
            'title' => 'Invoice ' . $invoice->invoice_number,
            'page_heading' => 'Invoice ' . html_escape($invoice->invoice_number),
            'page_description' => 'Total: ' . html_escape($invoice->currency) . ' ' . html_escape($invoice->total_amount),
        ));
    }
}
