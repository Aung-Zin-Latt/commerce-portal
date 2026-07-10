<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'requests/Form_request.php';

class Store_product_request extends Form_request
{
    /** @var int|null */
    protected $excludeId = NULL;

    /** @var array */
    protected $customErrors = array();

    public function excludeProductId($id)
    {
        $this->excludeId = $id ? (int) $id : NULL;
        return $this;
    }

    public function rules()
    {
        return array(
            array('name', 'Name', 'trim|required|max_length[255]'),
            array('price', 'Price', 'trim|required|numeric|greater_than_equal_to[0]'),
            array('status', 'Status', 'trim|required|in_list[active,inactive]'),
            array('description', 'Description', 'trim'),
            array('sku', 'SKU', 'trim|max_length[100]'),
        );
    }

    public function validate()
    {
        $this->customErrors = array();

        if (!parent::validate()) {
            return FALSE;
        }

        $sku = trim((string) $this->input('sku', ''));
        if ($sku === '') {
            return TRUE;
        }

        $this->CI->load->model('Product_model');
        if ($this->CI->Product_model->skuExists($sku, $this->excludeId)) {
            $this->customErrors['sku'] = 'The SKU field must contain a unique value.';
            return FALSE;
        }

        return TRUE;
    }

    public function errors()
    {
        return array_merge(parent::errors(), $this->customErrors);
    }
}