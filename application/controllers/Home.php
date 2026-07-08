<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{
    /** @var Product_service */
    protected $productService;

    public function __construct()
    {
        parent::__construct();
        $this->productService = $this->loadService('Product_service');
    }

    public function index()
    {
        $this->load->library('auth');

        if (!$this->auth->check()) {
            redirect('login');
        }

        if ($this->auth->isAdmin()) {
            redirect('admin/dashboard');
        }

        if ($this->auth->isCustomer()) {
            $filters = array(
                'search' => $this->input->get('search', TRUE),
            );
            $page = (int) $this->input->get('page');
            $result = $this->productService->listActiveProducts($filters, $page ?: 1, 12);

            $this->render_store('user/products/index', array(
                'title' => 'Shop',
                'products' => $result['products'],
                'filters' => $filters,
                'pagination' => $result,
            ));
            return;
        }

        $this->session->set_flashdata('error', 'Your account role is not allowed.');
        redirect('login');
    }
}
