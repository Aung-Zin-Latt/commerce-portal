<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends MY_Controller
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
        redirect('');
    }

    public function show($id)
    {
        $product = $this->productService->getActiveProductOrFail((int) $id);

        $this->render_store('user/products/show', array(
            'title' => $product->name,
            'product' => $product,
        ));
    }
}