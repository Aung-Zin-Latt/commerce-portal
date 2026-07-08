<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_service
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('User_model');
        $this->CI->load->model('Product_model');
    }

    public function getSummary()
    {
        return array(
            'total_users' => $this->CI->User_model->countAll(),
            'active_users' => $this->CI->User_model->countActive(),
            'total_products' => $this->CI->Product_model->countAll(),
            'active_products' => $this->CI->Product_model->countActive(),
        );
    }
}