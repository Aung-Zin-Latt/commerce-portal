<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_service
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Payment_model');
        $this->CI->load->model('Order_model');
        $this->CI->load->model('Stripe_transaction_model');
        $this->CI->config->load('stripe', TRUE);
    }

    protected function stripeConfig($key)
    {
        return $this->CI->config->item($key, 'stripe');
    }

    protected function bootstrapStripe()
    {
        $secretKey = $this->stripeConfig('stripe_secret_key');

        if ($secretKey === '') {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        \Stripe\Stripe::setApiKey($secretKey);
    }

    protected function generatePaymentReference()
    {
        $prefix = 'PAY-' . date('Y') . '-';

        $row = $this->CI->db
            ->select('payment_reference')
            ->like('payment_reference', $prefix, 'after')
            ->order_by('id', 'desc')
            ->limit(1)
            ->get('payments')
            ->row();

        $next = 1;

        if ($row && preg_match('/-(\d+)$/', $row->payment_reference, $matches)) {
            $next = (int) $matches[1] + 1;
        }

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function createCheckoutSession($order, array $items, int $userId, string $customerEmail)
    {
        if ($order->status !== 'pending') {
            return array(
                'success' => FALSE,
                'message' => 'This order is not payable.',
            );
        }

        if ($this->CI->Payment_model->findPaidByOrderId((int) $order->id)) {
            return array(
                'success' => FALSE,
                'message' => 'This order has already been paid.',
            );
        }

        try {
            $this->bootstrapStripe();
        } catch (RuntimeException $exception) {
            return array(
                'success' => FALSE,
                'message' => $exception->getMessage(),
            );
        }

        $now = date('Y-m-d H:i:s');
        $payment = $this->CI->Payment_model->findPendingByOrderId((int) $order->id);

        if ($payment) {
            $paymentId = (int) $payment->id;
        } else {
            $paymentId = $this->CI->Payment_model->create(array(
                'order_id' => (int) $order->id,
                'payment_reference' => $this->generatePaymentReference(),
                'provider' => 'stripe',
                'provider_reference' => NULL,
                'currency' => $order->currency,
                'amount' => $order->total_amount,
                'status' => 'pending',
                'paid_at' => NULL,
                'created_at' => $now,
                'updated_at' => $now,
            ));
        }

        $lineItems = array();

        foreach ($items as $item) {
            $lineItems[] = array(
                'price_data' => array(
                    'currency' => strtolower($order->currency),
                    'product_data' => array(
                        'name' => $item->product_name,
                    ),
                    'unit_amount' => (int) round(((float) $item->unit_price) * 100),
                ),
                'quantity' => (int) $item->quantity,
            );
        }

        if ((float) $order->tax_amount > 0) {
            $lineItems[] = array(
                'price_data' => array(
                    'currency' => strtolower($order->currency),
                    'product_data' => array(
                        'name' => 'Tax',
                    ),
                    'unit_amount' => (int) round(((float) $order->tax_amount) * 100),
                ),
                'quantity' => 1,
            );
        }

        // Payment success URL and cancel URL
        $successUrl = site_url('user/checkout/success/' . (int) $order->id);
        $cancelUrl = site_url('user/purchase/show/' . (int) $order->id) . '?payment=cancelled';

        try {
            $session = \Stripe\Checkout\Session::create(array(
                'mode' => 'payment',
                'customer_email' => $customerEmail,
                'line_items' => $lineItems,
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'metadata' => array(
                    'order_id' => (string) $order->id,
                    'payment_id' => (string) $paymentId,
                    'user_id' => (string) $userId,
                ),
            ));
        } catch (\Exception $exception) {
            return array(
                'success' => FALSE,
                'message' => 'Unable to start Stripe checkout. Please try again.',
            );
        }

        $this->CI->Payment_model->updateById($paymentId, array(
            'provider_reference' => $session->id,
            'updated_at' => date('Y-m-d H:i:s'),
        ));

        return array(
            'success' => TRUE,
            'redirect_url' => $session->url,
        );
    }

    public function handleWebhook($payload, $signatureHeader)
    {
        if ($payload === '' || $payload === FALSE) {
            return array(
                'success' => FALSE,
                'message' => 'Empty payload.',
            );
        }

        if ($signatureHeader === NULL || $signatureHeader === '') {
            return array(
                'success' => FALSE,
                'message' => 'Missing Stripe signature.',
            );
        }

        try {
            $this->bootstrapStripe();

            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signatureHeader,
                $this->stripeConfig('stripe_webhook_secret')
            );
        } catch (\UnexpectedValueException $exception) {
            return array(
                'success' => FALSE,
                'message' => 'Invalid payload.',
            );
        } catch (\Stripe\Exception\SignatureVerificationException $exception) {
            return array(
                'success' => FALSE,
                'message' => 'Invalid signature.',
            );
        }

        if ($this->CI->Stripe_transaction_model->existsByEventId($event->id)) {
            return array(
                'success' => TRUE,
                'message' => 'Event already processed.',
            );
        }

        try {
            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                $paymentId = isset($session->metadata->payment_id) ? (int) $session->metadata->payment_id : 0;

                if ($paymentId > 0) {
                    $this->markOrderPaidFromSession(
                        $session,
                        $paymentId,
                        $event->id,
                        $event->type,
                        $event->toJSON()
                    );
                }
            }
        } catch (\Exception $exception) {
            log_message('error', 'Stripe webhook error: ' . $exception->getMessage());

            return array(
                'success' => FALSE,
                'message' => 'Webhook processing failed.',
            );
        }

        return array(
            'success' => TRUE,
            'message' => 'Webhook received.',
        );
    }

    /**
     * Verify a pending order against Stripe after the customer returns from Checkout.
     * Useful when local webhooks are not forwarded (Stripe CLI not running).
     */
    public function syncPaidSessionForOrder($order, int $userId)
    {
        if ($order->status === 'paid') {
            return array(
                'success' => TRUE,
                'updated' => FALSE,
                'message' => 'Order already paid.',
            );
        }

        if ((int) $order->user_id !== $userId) {
            return array(
                'success' => FALSE,
                'updated' => FALSE,
                'message' => 'Payment verification failed.',
            );
        }

        $payment = $this->CI->Payment_model->findPendingByOrderId((int) $order->id);

        if (!$payment || empty($payment->provider_reference)) {
            return array(
                'success' => FALSE,
                'updated' => FALSE,
                'message' => 'No checkout session found for this order.',
            );
        }

        try {
            $this->bootstrapStripe();
            $session = \Stripe\Checkout\Session::retrieve($payment->provider_reference);
        } catch (\Exception $exception) {
            log_message('error', 'Stripe session sync error: ' . $exception->getMessage());

            return array(
                'success' => FALSE,
                'updated' => FALSE,
                'message' => 'Unable to verify payment with Stripe.',
            );
        }

        if (isset($session->metadata->user_id) && (int) $session->metadata->user_id !== $userId) {
            return array(
                'success' => FALSE,
                'updated' => FALSE,
                'message' => 'Payment verification failed.',
            );
        }

        if ($session->payment_status !== 'paid') {
            return array(
                'success' => FALSE,
                'updated' => FALSE,
                'message' => 'Payment has not been completed yet.',
            );
        }

        $syncEventId = 'sync_' . $session->id;

        if ($this->CI->Stripe_transaction_model->existsByEventId($syncEventId)) {
            return array(
                'success' => TRUE,
                'updated' => FALSE,
                'message' => 'Payment already confirmed.',
            );
        }

        $updated = $this->markOrderPaidFromSession(
            $session,
            (int) $payment->id,
            $syncEventId,
            'checkout.session.completed',
            $session->toJSON()
        );

        return array(
            'success' => $updated,
            'updated' => $updated,
            'message' => $updated ? 'Payment confirmed successfully.' : 'Unable to update order status.',
        );
    }

    protected function markOrderPaidFromSession($session, int $paymentId, string $stripeEventId, string $eventType, string $payloadJson)
    {
        $orderId = isset($session->metadata->order_id) ? (int) $session->metadata->order_id : 0;

        $payment = $this->CI->Payment_model->findById($paymentId);

        if (!$payment) {
            return FALSE;
        }

        if ($orderId <= 0) {
            $orderId = (int) $payment->order_id;
        }

        $order = $this->CI->db->where('id', $orderId)->get('orders')->row();

        if (!$order) {
            return FALSE;
        }

        if ($order->status === 'paid' && $payment->status === 'paid') {
            return TRUE;
        }

        if ($this->CI->Stripe_transaction_model->existsByEventId($stripeEventId)) {
            return TRUE;
        }

        $now = date('Y-m-d H:i:s');
        $paymentIntentId = $this->resolveStripePaymentIntentId($session->payment_intent);

        $this->CI->db->trans_start();

        $this->CI->Payment_model->updateById($paymentId, array(
            'status' => 'paid',
            'provider_reference' => $session->id,
            'paid_at' => $now,
            'updated_at' => $now,
        ));

        $this->CI->Order_model->updateById($orderId, array(
            'status' => 'paid',
            'updated_at' => $now,
        ));

        $this->CI->Stripe_transaction_model->create(array(
            'payment_id' => $paymentId,
            'stripe_event_id' => $stripeEventId,
            'stripe_payment_intent' => $paymentIntentId,
            'stripe_session_id' => $session->id,
            'event_type' => $eventType,
            'status' => isset($session->payment_status) ? $session->payment_status : 'paid',
            'payload' => $payloadJson,
            'created_at' => $now,
        ));

        // Invoice
        if (!class_exists('Invoice_service', FALSE)) {
            require_once APPPATH . 'services/Invoice_service.php';
        }
        $invoiceService = new Invoice_service();
        $invoiceResult = $invoiceService->createFromPayment($paymentId);
        if (!$invoiceResult['success']) {
            $this->CI->db->trans_rollback();
            return FALSE;
        }

        // Receipt
        if (!class_exists('Receipt_service', FALSE)) {
            require_once APPPATH . 'services/Receipt_service.php';
        }
        $receiptService = new Receipt_service();
        $receiptResult = $receiptService->createFromPayment($paymentId);
        if (!$receiptResult['success']) {
            $this->CI->db->trans_rollback();
            return FALSE;
        }

        // Audit Log
        if (!class_exists('Audit_service', FALSE)) {
            require_once APPPATH . 'services/Audit_service.php';
        }
        $auditService = new Audit_service();
        $auditService->log('payment.completed', 'payment', $paymentId, $order->user_id);

        $this->CI->db->trans_complete();

        $ok = (bool) $this->CI->db->trans_status();

        if ($ok) {
            $this->sendReceiptEmailAfterPayment($paymentId, $order);
        }

        return $ok;
    }

    // Receipt email sending
    protected function sendReceiptEmailAfterPayment(int $paymentId, $order)
    {
        $this->CI->load->model('Receipt_model');

        $receipt = $this->CI->Receipt_model->findByPaymentId($paymentId);
        $user = $this->CI->db
            ->where('id', (int) $order->user_id)
            ->get('users')
            ->row();

        if (!$receipt || !$user) {
            log_message('error', 'Receipt email skipped: missing receipt or user for payment #' . $paymentId);
            return;
        }

        if (!class_exists('Email_service', FALSE)) {
            require_once APPPATH . 'services/Email_service.php';
        }

        $emailService = new Email_service();
        $result = $emailService->sendReceiptEmail($receipt, $order, $user);

        if (!$result['success']) {
            log_message('error', 'Receipt email not sent for payment #' . $paymentId . ': ' . $result['message']);
        }
    }

    protected function resolveStripePaymentIntentId($paymentIntent)
    {
        if (is_string($paymentIntent)) {
            return $paymentIntent;
        }

        if (is_object($paymentIntent) && isset($paymentIntent->id)) {
            return $paymentIntent->id;
        }

        return NULL;
    }
}