<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends MY_Controller
{
    /** @var Order_service */
    protected $orderService;

    /** @var Payment_service */
    protected $paymentService;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->orderService = $this->loadService('Order_service');
        $this->paymentService = $this->loadService('Payment_service');
    }
    
    public function index()
    {
        $orders = $this->orderService->listOrdersForUser((int) $this->auth->id());

        $this->render_store('user/purchase/index', array(
            'title' => 'Purchase History',
            'orders' => $orders,
        ));
    }

    public function show(int $orderId)
    {
        $paymentState = $this->input->get('payment');
        $userId = (int) $this->auth->id();

        if ($paymentState === 'success' || $paymentState === 'cancelled') {
            $data = $this->orderService->getOrderWithItemsForUserOrFail((int) $orderId, $userId);

            if ($paymentState === 'success') {
                $result = $this->paymentService->syncPaidSessionForOrder($data['order'], $userId);

                $this->session->set_flashdata(
                    $result['success'] ? 'success' : 'error',
                    $result['message']
                );
            } else {
                $this->session->set_flashdata('error', 'Payment was cancelled.');
            }

            redirect('user/purchase/show/' . (int) $orderId);
        }

        $data = $this->orderService->getOrderWithItemsForUserOrFail((int) $orderId, $userId);

        if ($data['order']->status === 'pending') {
            $this->paymentService->syncPaidSessionForOrder($data['order'], $userId);
            $data = $this->orderService->getOrderWithItemsForUserOrFail((int) $orderId, $userId);
        }

        $this->render_store('user/purchase/show', array(
            'title' => 'Order ' . $data['order']->order_number,
            'order' => $data['order'],
            'items' => $data['items'],
        ));
    }
}
