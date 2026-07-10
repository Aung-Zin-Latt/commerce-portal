<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_service
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Order_model');
        $this->CI->load->model('Order_item_model');
        $this->CI->load->model('Product_model');
    }

    public function listOrdersForUser(int $userId, int $page = 1, int $perPage = 10)
    {
        $meta = pagination_prepare(
            $this->CI->Order_model->countForUser($userId),
            $page,
            $perPage
        );

        return pagination_result(
            'orders',
            $this->CI->Order_model->paginateForUser($userId, $meta['per_page'], $meta['offset']),
            $meta
        );
    }

    public function getOrderForUserOrFail(int $orderId, int $userId)
    {
        $order = $this->CI->Order_model->findByIdForUser($orderId, $userId);

        if (!$order) {
            show_404();
        }

        return $order;
    }

    public function getOrderWithItemsForUserOrFail(int $orderId, int $userId)
    {
        $order = $this->getOrderForUserOrFail($orderId, $userId);
        $items = $this->CI->Order_item_model->getByOrderId($orderId);

        return array(
            'order' => $order,
            'items' => $items,
        );
    }

    public function createFromCart(int $userId)
    {
        if (!class_exists('Cart_service', false)) {
            require_once APPPATH . 'services/Cart_service.php';
        }

        $cartService = new Cart_service();
        $checkout = $cartService->getCheckoutSummary();

        if ($checkout['is_empty']) {
            return array(
                'success' => FALSE,
                'message' => 'Your cart is empty.',
            );
        }

        $lineItems = array();
        $subtotal = 0.0;

        foreach ($checkout['items'] as $item) {
            $productId = (int) $item['product_id'];
            $quantity = (int) $item['quantity'];

            $product = $this->CI->Product_model->findById($productId);

            if (!$product || $product->status !== 'active') {
                return array(
                    'success' => FALSE,
                    'message' => 'One or more products in your cart are no longer available.',
                );
            }

            $unitPrice = (float) $product->price;
            $lineSubtotal = round($unitPrice * $quantity, 2);
            $subtotal += $lineSubtotal;

            $lineItems[] = array(
                'product_id' => $productId,
                'product_name' => $product->name,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $lineSubtotal,
            );
        }

        $subtotal = round($subtotal, 2);
        $taxAmount = round($cartService->getTaxAmount(), 2);
        $totalAmount = round($subtotal + $taxAmount, 2);
        $now = date('Y-m-d H:i:s');

        $this->CI->db->trans_start();

        $orderId = $this->CI->Order_model->createOrder(array(
            'user_id' => $userId,
            'order_number' => $this->generateOrderNumber(),
            'status' => 'pending',
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'currency' => 'SGD',
            'created_at' => $now,
            'updated_at' => $now,
        ));

        $rows = array();

        foreach ($lineItems as $lineItem) {
            $rows[] = array(
                'order_id' => $orderId,
                'product_id' => $lineItem['product_id'],
                'product_name' => $lineItem['product_name'],
                'unit_price' => $lineItem['unit_price'],
                'quantity' => $lineItem['quantity'],
                'subtotal' => $lineItem['subtotal'],
                'created_at' => $now,
            );
        }

        $this->CI->Order_item_model->createMany($rows);
        $cartService->clear();

        // // Audit Log
        // if (!class_exists('Audit_service', FALSE)) {
        //     require_once APPPATH . 'services/Audit_service.php';
        // }
        // $auditService = new Audit_service();
        // $auditService->log('order.created', 'order', (int) $orderId, (int) $userId);

        $this->CI->db->trans_complete();

        if (!$this->CI->db->trans_status() || $orderId <= 0) {
            return array(
                'success' => FALSE,
                'message' => 'Unable to create your order. Please try again.',
            );
        }

        return array(
            'success' => TRUE,
            'order_id' => $orderId,
            'message' => 'Order placed successfully.',
        );
    }

    protected function generateOrderNumber()
    {
        $prefix = 'ORD-' . date('Y') . '-';

        $row = $this->CI->db
            ->select('order_number')
            ->like('order_number', $prefix, 'after')
            ->order_by('id', 'desc')
            ->limit(1)
            ->get('orders')
            ->row();

        $next = 1;

        if ($row && preg_match('/-(\d+)$/', $row->order_number, $matches)) {
            $next = (int) $matches[1] + 1;
        }

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    // Admin Order List read helper
    public function listAllOrders(int $page = 1, int $perPage = 10)
    {
        $meta = pagination_prepare(
            $this->CI->Order_model->countAll(),
            $page,
            $perPage
        );

        return pagination_result(
            'orders',
            $this->CI->Order_model->paginateWithCustomer($meta['per_page'], $meta['offset']),
            $meta
        );
    }
    public function getOrderWithItemsOrFail(int $orderId)
    {
        $order = $this->CI->Order_model->findById($orderId);

        if (!$order) {
            show_404();
        }

        $items = $this->CI->Order_item_model->getByOrderId($orderId);

        return array(
            'order' => $order,
            'items' => $items,
        );
    }
}