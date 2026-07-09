<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_service
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Invoice_model');
        $this->CI->load->model('Invoice_item_model');
        $this->CI->load->model('Payment_model');
        $this->CI->load->model('Order_model');
        $this->CI->load->model('Order_item_model');
    }

    // Create invoice + line items from a paid payment.
    // Safe to call multiple times (idempotent per payment_id).
    public function createFromPayment(int $paymentId)
    {
        $existingPayment = $this->CI->Invoice_model->findByPaymentId($paymentId);

        if ($existingPayment) {
            return array(
                'success' => TRUE,
                'invoice_id' => (int) $existingPayment->id,
                'message' => 'Invoice already created for this payment.',
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

        $orderItems = $this->CI->Order_item_model->getByOrderId((int) $order->id);

        if (empty($orderItems)) {
            return array(
                'success' => FALSE,
                'message' => 'Order has no items.',
            );
        }

        $now = date('Y-m-d H:i:s');

        $invoiceId = $this->CI->Invoice_model->create(array(
            'payment_id' => (int) $payment->id,
            'order_id' => (int) $order->id,
            'user_id' => (int) $order->user_id,
            'invoice_number' => $this->generateInvoiceNumber(),
            'subtotal' => $order->subtotal,
            'tax_amount' => $order->tax_amount,
            'total_amount' => $order->total_amount,
            'currency' => $order->currency,
            'issued_at' => $now,
            'created_at' => $now,
        ));

        $rows = array();

        foreach ($orderItems as $item) {
            $rows[] = array(
                'invoice_id' => $invoiceId,
                'product_name' => $item->product_name,
                'unit_price' => $item->unit_price,
                'quantity' => (int) $item->quantity,
                'subtotal' => $item->subtotal,
                'created_at' => $now,
            );
        }

        $this->CI->Invoice_item_model->createMany($rows);

        return array(
            'success' => TRUE,
            'invoice_id' => (int) $invoiceId,
            'message' => 'Invoice created successfully.',
        );
    }

    protected function generateInvoiceNumber()
    {
        $prefix = 'INV-' . date('Y') . '-';
        $row = $this->CI->db
            ->select('invoice_number')
            ->like('invoice_number', $prefix, 'after')
            ->order_by('id', 'desc')
            ->limit(1)
            ->get('invoices')
            ->row();
        $next = 1;
        if ($row && preg_match('/-(\d+)$/', $row->invoice_number, $matches)) {
            $next = (int) $matches[1] + 1;
        }
        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    // Invoice list for a user
    public function listInvoicesForUser(int $userId)
    {
        return $this->CI->Invoice_model->getAllForUser($userId);
    }
    public function getInvoiceForUserOrFail(int $invoiceId, int $userId)
    {
        $invoice = $this->CI->Invoice_model->findByIdForUser($invoiceId, $userId);
        if (!$invoice) {
            show_404();
        }

        return $invoice;
    }
    public function getInvoiceWithItemsForUserOrFail(int $invoiceId, int $userId)
    {
        $invoice = $this->getInvoiceForUserOrFail($invoiceId, $userId);
        $items = $this->CI->Invoice_item_model->getByInvoiceId($invoiceId);
        return array(
            'invoice' => $invoice,
            'items' => $items,
        );
    }


}