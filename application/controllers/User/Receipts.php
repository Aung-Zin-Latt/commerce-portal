<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipts extends MY_Controller
{
    /** Receipt_service */
    protected $receiptService;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->receiptService = $this->loadService('Receipt_service');
    }

    public function index()
    {
        $page = (int) $this->input->get('page');
        $result = $this->receiptService->listReceiptsForUser((int) $this->auth->id(), $page ?: 1, 10);

        $this->render_store('user/receipts/index', array(
            'title' => 'Receipts',
            'receipts' => $result['receipts'],
            'pagination' => $result,
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

        if ($this->auth->isAdmin()) {
            $receipt = $this->Receipt_model->findById((int) $id);
            if (!$receipt) {
                $this->deny_resource_access();
                return;
            }
        } else {
            $receipt = $this->receiptService->getReceiptForUserOrFail(
                (int) $id,
                (int) $this->auth->id()
            );
        }

        $this->render_store('user/receipts/show', array(
            'title' => 'Receipt ' . $receipt->receipt_number,
            'receipt' => $receipt,
        ));
    }
}
