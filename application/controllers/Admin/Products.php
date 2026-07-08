<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends MY_Controller
{
    /** @var Product_service */
    protected $productService;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->productService = $this->loadService('Product_service');
    }

    public function index()
    {
        $filters = array(
            'search' => $this->input->get('search', TRUE),
            'status' => $this->input->get('status', TRUE),
        );

        $page = (int) $this->input->get('page');
        $result = $this->productService->listProducts($filters, $page ?: 1, 10);

        $this->render('admin/products/index', array(
            'title' => 'Products',
            'page_heading' => 'Products',
            'products' => $result['products'],
            'filters' => $filters,
            'pagination' => $result,
            'breadcrumbs' => array(
                'Dashboard' => 'admin/dashboard',
                'Products' => NULL,
            ),
        ));
    }

    public function create()
    {
        $this->render('admin/products/form', array(
            'title' => 'Create Product',
            'page_heading' => 'Create Product',
            'form_action' => 'admin/products/store',
            'product' => NULL,
            'breadcrumbs' => array(
                'Dashboard' => 'admin/dashboard',
                'Products' => 'admin/products',
                'Create' => NULL,
            ),
        ));
    }

    public function store()
    {
        $result = $this->productService->createProduct(array(
            'sku' => $this->input->post('sku', TRUE),
            'name' => $this->input->post('name', TRUE),
            'description' => $this->input->post('description', TRUE),
            'price' => $this->input->post('price'),
            'status' => $this->input->post('status', TRUE),
        ));

        if (!$result['success']) {
            $this->session->set_flashdata('error', $result['message']);
            return redirect('admin/products/create');
        }

        $this->session->set_flashdata('success', $result['message']);
        redirect('admin/products');
    }

    public function edit($id)
    {
        $product = $this->productService->getProductOrFail($id);

        $this->render('admin/products/form', array(
            'title' => 'Edit Product',
            'page_heading' => 'Edit Product',
            'form_action' => 'admin/products/update/' . $product->id,
            'product' => $product,
            'breadcrumbs' => array(
                'Dashboard' => 'admin/dashboard',
                'Products' => 'admin/products',
                'Edit' => NULL,
            ),
        ));
    }

    public function update($id)
    {
        $result = $this->productService->updateProduct($id, array(
            'sku' => $this->input->post('sku', TRUE),
            'name' => $this->input->post('name', TRUE),
            'description' => $this->input->post('description', TRUE),
            'price' => $this->input->post('price'),
            'status' => $this->input->post('status', TRUE),
        ));

        if (!$result['success']) {
            $this->session->set_flashdata('error', $result['message']);
            return redirect('admin/products/edit/' . $id);
        }

        $this->session->set_flashdata('success', $result['message']);
        redirect('admin/products');
    }

    public function delete($id)
    {
        $result = $this->productService->deleteProduct($id);

        $this->session->set_flashdata(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );

        redirect('admin/products');
    }
}
