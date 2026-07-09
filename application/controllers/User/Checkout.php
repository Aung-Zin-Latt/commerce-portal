<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends MY_Controller
{
    /** @var Cart_service */
    protected $cartService;

    /** @var Order_service */
    protected $orderService;

    /** @var Payment_service */
    protected $paymentService;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->cartService = $this->loadService('Cart_service');
        $this->orderService = $this->loadService('Order_service');
        $this->paymentService = $this->loadService('Payment_service');
    }

    public function index()
    {
        $checkout = $this->cartService->getCheckoutSummary();

        if ($checkout['is_empty']) {
            $this->session->set_flashdata('error', 'Your cart is empty. Add items before checkout.');
            redirect('user/products');
        }

        $this->render_store('user/checkout/index', array(
            'title' => 'Checkout',
            'checkout' => $checkout,
        ));
    }

    public function place()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        $result = $this->orderService->createFromCart((int) $this->auth->id());
        if (!$result['success']) {
            $this->session->set_flashdata('error', $result['message']);
            redirect('user/checkout');
        }
        $this->session->set_flashdata('success', $result['message']);
        redirect('user/checkout/confirmation/' . (int) $result['order_id']);
    }
    public function confirmation($orderId)
    {
        $data = $this->orderService->getOrderWithItemsForUserOrFail(
            (int) $orderId,
            (int) $this->auth->id()
        );
        $this->render_store('user/checkout/confirmation', array(
            'title' => 'Order Confirmation',
            'order' => $data['order'],
            'items' => $data['items'],
        ));
    }

    // Stripe
    public function pay($orderId)
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        $data = $this->orderService->getOrderWithItemsForUserOrFail(
            (int) $orderId,
            (int) $this->auth->id()
        );
        $result = $this->paymentService->createCheckoutSession(
            $data['order'],
            $data['items'],
            (int) $this->auth->id(),
            (string) $this->session->userdata('user_email')
        );
        if (!$result['success']) {
            $this->session->set_flashdata('error', $result['message']);
            redirect('user/purchase/show/' . (int) $orderId);
        }
        
        redirect($result['redirect_url']);
    }
}