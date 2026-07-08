<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends MY_Controller
{
    /** @var Cart_service */
    protected $cartService;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->cartService = $this->loadService('Cart_service');
    }

    public function index()
    {
        $cart = $this->cartService->getCartSummary();

        $this->render_store('user/cart/index', array(
            'title' => 'Cart',
            'cart' => $cart,
        ));
    }

    public function add()
    {
        $productId = (int) $this->input->post('product_id');
        $quantity = (int) $this->input->post('quantity');

        if ($productId <= 0) {
            $this->session->set_flashdata('error', 'Invalid product selected.');
            return redirect('');
        }

        $result = $this->cartService->addItem($productId, $quantity ?: 1);

        $this->session->set_flashdata(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );

        redirect('');
    }

    public function update()
    {
        $productId = (int) $this->input->post('product_id');
        $quantity = (int) $this->input->post('quantity');

        $result = $this->cartService->updateItem($productId, $quantity);

        $this->session->set_flashdata(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );

        redirect('user/cart');
    }

    public function increase($productId)
    {
        $this->cartService->increaseItem((int) $productId);
        redirect('user/cart');
    }

    public function decrease($productId)
    {
        $this->cartService->decreaseItem((int) $productId);
        redirect('user/cart');
    }

    public function remove($productId)
    {
        $result = $this->cartService->removeItem((int) $productId);

        $this->session->set_flashdata(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );

        redirect('user/cart');
    }
}