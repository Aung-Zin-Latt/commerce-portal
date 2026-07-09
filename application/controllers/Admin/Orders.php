<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MY_Controller
{
    /** @var Order_service */
    protected $orderService;

    public function __construct()
    {
        parent::__construct();
        $this->orderService = $this->loadService('Order_service');
    }

    public function index()
    {
        $orders = $this->orderService->listAllOrders();

        $this->render('admin/orders/index', array(
            'title' => 'Orders',
            'orders' => $orders,
            'breadcrumbs' => array(
                'Home' => 'admin/dashboard',
                'Orders' => NULL,
            ),
        ));
    }

    public function show($id)
    {
        $data = $this->orderService->getOrderWithItemsOrFail((int) $id);

        $this->load->model('User_model');
        $this->load->model('Invoice_model');
        $this->load->model('Receipt_model');

        $customer = $this->User_model->findById((int) $data['order']->user_id);
        $invoice = $this->Invoice_model->findByOrderIdForUser(
            (int) $data['order']->id,
            (int) $data['order']->user_id
        );
        $receipt = $this->Receipt_model->findByOrderIdForUser(
            (int) $data['order']->id,
            (int) $data['order']->user_id
        );

        $this->render('admin/orders/show', array(
            'title' => 'Order ' . $data['order']->order_number,
            'order' => $data['order'],
            'items' => $data['items'],
            'customer' => $customer,
            'invoice' => $invoice,
            'receipt' => $receipt,
            'breadcrumbs' => array(
                'Home' => 'admin/dashboard',
                'Orders' => 'admin/orders',
                $data['order']->order_number => NULL,
            ),
        ));
    }
}