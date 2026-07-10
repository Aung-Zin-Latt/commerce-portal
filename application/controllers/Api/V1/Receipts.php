<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipts extends MY_Api_Controller
{
    /** @var Receipt_service */
    protected $receiptService;

    public function __construct()
    {
        parent::__construct();
        $this->receiptService = $this->loadService('Receipt_service');
    }

    public function index()
    {
        $page = (int) $this->input->get('page');
        $perPage = (int) $this->input->get('per_page');
        $result = $this->receiptService->listReceiptsForUser(
            (int) $this->auth->id(),
            $page ?: 1,
            $perPage ?: 10
        );

        $list = array();
        foreach ($result['receipts'] as $row) {
            $list[] = $this->formatReceipt($row);
        }

        return json_success(array(
            'items' => $list,
            'page' => $result['page'],
            'per_page' => $result['per_page'],
            'total' => $result['total'],
            'total_pages' => $result['total_pages'],
        ));
    }

    public function show($id)
    {
        $this->load->model('Receipt_model');

        $receipt = $this->Receipt_model->findByIdForUser((int) $id, (int) $this->auth->id());

        if (!$receipt) {
            return json_error('Resource not found.', 404);
        }

        return json_success($this->formatReceipt($receipt));
    }

    protected function formatReceipt($receipt)
    {
        return array(
            'id' => (int) $receipt->id,
            'receipt_number' => $receipt->receipt_number,
            'order_id' => (int) $receipt->order_id,
            'payment_id' => (int) $receipt->payment_id,
            'amount' => (float) $receipt->amount,
            'currency' => $receipt->currency,
            'issued_at' => $receipt->issued_at,
        );
    }
}
