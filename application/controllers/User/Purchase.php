<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends MY_Controller
{
    /** @var Order_service */
    protected $orderService;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('auth');
        $this->orderService = $this->loadService('Order_service');
    }
    
    public function index()
    {
        $orders = $this->orderService->listOrdersForUser((int) $this->auth->id());

        $this->render_store('user/purchase/index', array(
            'title' => 'Purchase History',
            'orders' => $orders,
        ));
    }

    public function show($orderId)
    {
        $data = $this->orderService->getOrderWithItemsForUserOrFail(
            (int)$orderId,
            (int) $this->auth->id()
        );

        $this->render_store('user/purchase/show', array(
            'title' => 'Order ' . $data['order']->order_number,
            'order' => $data['order'],
            'items' => $data['items'],
        ));
    }
}
