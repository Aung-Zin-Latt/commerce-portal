<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_service
{
    protected $CI;
    protected $sessionKey = 'cart';

    public function __construct()
    {
        $this->CI = &get_instance();
        if (!class_exists('Product_service', false)) {
            require_once APPPATH . 'services/Product_service.php';
        }

        $this->productService = new Product_service();
    }

    public function getItems()
    {
        $items = $this->CI->session->userdata($this->sessionKey);

        return is_array($items) ? $items : array();
    }

    public function getItemCount()
    {
        $count = 0;

        foreach ($this->getItems() as $item) {
            $count += (int) $item['quantity'];
        }

        return $count;
    }

    public function getSubtotal()
    {
        $subtotal = 0.0;
        foreach ($this->getItems() as $item) {
            $subtotal += (float) $item['price'] * (int) $item['quantity'];
        }
        return round($subtotal, 2);
    }

    public function getCartSummary()
    {
        $items = $this->getItems();
        return array(
            'items' => $items,
            'item_count' => $this->getItemCount(),
            'subtotal' => $this->getSubtotal(),
            'is_empty' => empty($items),
        );
    }

    public function addItem(int $productId, int $quantity = 1)
    {
        $quantity = max(1, $quantity);
        $product = $this->productService->getActiveProductOrFail($productId);
        $items = $this->getItems();
        $productId = (int) $product->id;
        if (isset($items[$productId])) {
            $items[$productId]['quantity'] += $quantity;
        } else {
            $items[$productId] = array(
                'product_id' => $productId,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $quantity,
            );
        }
        $this->saveItems($items);
        return array(
            'success' => TRUE,
            'message' => 'Product added to cart.',
        );
    }

    public function updateItem(int $productId, int $quantity)
    {
        $items = $this->getItems();
        $productId = (int) $productId;
        if (!isset($items[$productId])) {
            return array(
                'success' => FALSE,
                'message' => 'Product is not in your cart.',
            );
        }
        if ($quantity <= 0) {
            return $this->removeItem($productId);
        }
        $this->productService->getActiveProductOrFail($productId);
        $items[$productId]['quantity'] = $quantity;
        $this->saveItems($items);
        return array(
            'success' => TRUE,
            'message' => 'Cart updated.',
        );
    }

    public function increaseItem(int $productId)
    {
        $items = $this->getItems();
        $productId = (int) $productId;

        if (!isset($items[$productId])) {
            return array(
                'success' => FALSE,
                'message' => 'Product is not in your cart.',
            );
        }

        return $this->updateItem($productId, (int) $items[$productId]['quantity'] + 1);
    }

    public function decreaseItem(int $productId)
    {
        $items = $this->getItems();
        $productId = (int) $productId;

        if (!isset($items[$productId])) {
            return array(
                'success' => FALSE,
                'message' => 'Product is not in your cart.',
            );
        }

        return $this->updateItem($productId, (int) $items[$productId]['quantity'] - 1);
    }

    public function removeItem(int $productId)
    {
        $items = $this->getItems();
        $productId = (int) $productId;
        if (!isset($items[$productId])) {
            return array(
                'success' => FALSE,
                'message' => 'Product is not in your cart.',
            );
        }
        unset($items[$productId]);
        $this->saveItems($items);
        return array(
            'success' => TRUE,
            'message' => 'Product removed from cart.',
        );
    }

    public function clear()
    {
        $this->CI->session->unset_userdata($this->sessionKey);
    }

    protected function saveItems(array $items)
    {
        $this->CI->session->set_userdata($this->sessionKey, $items);
    }

    public function getTaxAmount()
    {
        // no tax yet. Change to e.g. round($this->getSubtotal() * 0.10, 2) later.
        return 0.0;
    }

    public function getTotal()
    {
        return round($this->getSubtotal() + $this->getTaxAmount(), 2);
    }

    public function getCheckoutSummary()
    {
        $summary = $this->getCartSummary();
        $summary['tax'] = $this->getTaxAmount();
        $summary['total'] = $this->getTotal();

        return $summary;
    }
}