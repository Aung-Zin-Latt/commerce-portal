<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipts extends MY_Controller
{
    public function index()
    {
        $this->load->library('auth');
        $this->load->model('Receipt_model');

        $receipts = $this->Receipt_model->getAllForUser((int) $this->auth->id());

        $this->render('user/placeholder', array(
            'title' => 'Receipts',
            'page_heading' => 'Receipts',
            'page_description' => empty($receipts)
                ? 'No receipts yet. Completed purchases will appear here.'
                : 'You have ' . count($receipts) . ' receipt(s). Open one with /user/receipts/show/{id}.',
            'breadcrumbs' => array(
                'Home' => '',
                'Receipts' => NULL,
            ),
        ));
    }

    /**
     * View a single receipt with ownership authorization.
     *
     * @param int $id
     */
    public function show($id)
    {
        $this->load->model('Receipt_model');
        $this->load->library('auth');

        if ($this->auth->isAdmin()) {
            $receipt = $this->Receipt_model->findById((int) $id);
        } else {
            $receipt = $this->Receipt_model->findByIdForUser((int) $id, (int) $this->auth->id());
        }

        if (!$receipt) {
            $this->deny_resource_access();
            return;
        }

        $this->render('user/placeholder', array(
            'title' => 'Receipt ' . $receipt->receipt_number,
            'page_heading' => 'Receipt ' . html_escape($receipt->receipt_number),
            'page_description' => 'Amount: ' . html_escape($receipt->currency) . ' ' . html_escape($receipt->amount),
            'breadcrumbs' => array(
                'Home' => 'user/receipts',
                'Receipt Details' => NULL,
            ),
        ));
    }
}
