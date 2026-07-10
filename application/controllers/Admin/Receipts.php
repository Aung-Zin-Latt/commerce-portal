<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipts extends MY_Controller
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
        $result = $this->receiptService->listAllReceipts($page ?: 1, 10);
        $this->render('admin/receipts/index', array(
            'title' => 'Receipts',
            'receipts' => $result['receipts'],
            'pagination' => $result,
            'breadcrumbs' => array(
                'Home' => 'admin/dashboard',
                'Receipts' => NULL,
            ),
        ));
    }
    
    public function show($id)
    {
        $receipt = $this->receiptService->getReceiptOrFail((int) $id);
        $this->load->model('User_model');
        $customer = $this->User_model->findById((int) $receipt->user_id);
        $order = $this->db
            ->select('id, order_number')
            ->where('id', (int) $receipt->order_id)
            ->get('orders')
            ->row();
        $this->render('admin/receipts/show', array(
            'title' => 'Receipt ' . $receipt->receipt_number,
            'receipt' => $receipt,
            'customer' => $customer,
            'order' => $order,
            'breadcrumbs' => array(
                'Home' => 'admin/dashboard',
                'Receipts' => 'admin/receipts',
                $receipt->receipt_number => NULL,
            ),
        ));
    }
}
