<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_service
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Product_model');
    }

    public function listProducts(array $filters = array(), int $page = 1, int $perPage = 10)
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;
        $total = $this->CI->Product_model->countFiltered($filters);

        return array(
            'products' => $this->CI->Product_model->paginate($filters, $perPage, $offset),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int) ceil($total / $perPage),
        );
    }

    public function listActiveProducts(array $filters = array(), int $page = 1, int $perPage = 12)
    {
        $filters['status'] = 'active';

        return $this->listProducts($filters, $page, $perPage);
    }

    public function getActiveProductOrFail(int $id)
    {
        $product = $this->getProductOrFail($id);

        if ($product->status !== 'active') {
            show_404();
        }

        return $product;
    }

    public function getProductOrFail(int $id)
    {
        $product = $this->CI->Product_model->findById($id);

        if (!$product) {
            show_404();
        }

        return $product;
    }

    public function createProduct(array $input)
    {
        $now = date('Y-m-d H:i:s');

        $productId = $this->CI->Product_model->create(array(
            'sku' => $input['sku'] !== '' ? $input['sku'] : NULL,
            'name' => $input['name'],
            'description' => $input['description'],
            'price' => $input['price'],
            'status' => $input['status'],
            'created_at' => $now,
            'updated_at' => $now,
        ));

        return array(
            'success' => TRUE,
            'product_id' => $productId,
            'message' => 'Product created successfully.',
        );
    }

    public function updateProduct(int $id, array $input)
    {
        $product = $this->getProductOrFail($id);

        $this->CI->Product_model->update($product->id, array(
            'sku' => $input['sku'] !== '' ? $input['sku'] : NULL,
            'name' => $input['name'],
            'description' => $input['description'],
            'price' => $input['price'],
            'status' => $input['status'],
            'updated_at' => date('Y-m-d H:i:s'),
        ));

        return array(
            'success' => TRUE,
            'message' => 'Product updated successfully.',
        );
    }

    public function deleteProduct(int $id)
    {
        $product = $this->getProductOrFail($id);
        $this->CI->Product_model->softDelete($product->id);

        return array(
            'success' => TRUE,
            'message' => 'Product deleted successfully.',
        );
    }
}
