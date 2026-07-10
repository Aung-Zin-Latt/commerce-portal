<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipt_service
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Receipt_model');
        $this->CI->load->model('Payment_model');
    }

    // Create receipt from a paid payment.
    // Safe to call multiple times (idempotent per payment_id).
    public function createFromPayment(int $paymentId)
    {
        $existingReceipt = $this->CI->Receipt_model->findByPaymentId($paymentId);

        if ($existingReceipt) {
            return array(
                'success' => TRUE,
                'receipt_id' => (int) $existingReceipt->id,
                'message' => 'Receipt already created for this payment.',
            );
        }

        $payment = $this->CI->Payment_model->findById($paymentId);

        if (!$payment || $payment->status !== 'paid') {
            return array(
                'success' => FALSE,
                'message' => 'Payment not found or not paid.',
            );
        }

        $order = $this->CI->db
            ->where('id', $payment->order_id)
            ->get('orders')
            ->row();

        if (!$order) {
            return array(
                'success' => FALSE,
                'message' => 'Order not found.',
            );
        }

        $now = date('Y-m-d H:i:s');
        $receiptId = $this->CI->Receipt_model->create(array(
            'payment_id' => (int) $payment->id,
            'order_id' => (int) $order->id,
            'user_id' => (int) $order->user_id,
            'receipt_number' => $this->generateReceiptNumber(),
            'amount' => $order->total_amount,
            'currency' => $order->currency,
            'issued_at' => $now,
            'created_at' => $now,
        ));

        return array(
            'success' => TRUE,
            'receipt_id' => (int) $receiptId,
            'message' => 'Receipt created successfully.',
        );
    }

    protected function generateReceiptNumber()
    {
        $prefix = 'RCP-' . date('Y') . '-';
        $row = $this->CI->db
            ->select('receipt_number')
            ->like('receipt_number', $prefix, 'after')
            ->order_by('id', 'desc')
            ->limit(1)
            ->get('receipts')
            ->row();

        $next = 1;
        if ($row && preg_match('/-(\d+)$/', $row->receipt_number, $matches)) {
            $next = (int) $matches[1] + 1;
        }

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function listReceiptsForUser(int $userId, int $page = 1, int $perPage = 10)
    {
        $meta = pagination_prepare(
            $this->CI->Receipt_model->countForUser($userId),
            $page,
            $perPage
        );

        return pagination_result(
            'receipts',
            $this->CI->Receipt_model->paginateForUser($userId, $meta['per_page'], $meta['offset']),
            $meta
        );
    }
    public function getReceiptForUserOrFail(int $receiptId, int $userId)
    {
        $receipt = $this->CI->Receipt_model->findByIdForUser($receiptId, $userId);
        if (!$receipt) {
            show_404();
        }
        return $receipt;
    }

    // Admin Receipt List read helper
    public function listAllReceipts(int $page = 1, int $perPage = 10)
    {
        $meta = pagination_prepare(
            $this->CI->Receipt_model->countAll(),
            $page,
            $perPage
        );

        return pagination_result(
            'receipts',
            $this->CI->Receipt_model->paginateWithCustomer($meta['per_page'], $meta['offset']),
            $meta
        );
    }
    public function getReceiptOrFail(int $receiptId)
    {
        $receipt = $this->CI->Receipt_model->findById($receiptId);

        if (!$receipt) {
            show_404();
        }

        return $receipt;
    }
}