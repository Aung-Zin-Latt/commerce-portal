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
        $this->CI->load->model('Order_model');
        $this->CI->load->model('Invoice_model');
        $this->CI->load->model('Receipt_model');
        $this->CI->load->model('Audit_log_model');
    }

    public function getSummary()
    {
        return array(
            'total_users' => $this->CI->User_model->countAll(),
            'active_users' => $this->CI->User_model->countActive(),
            'total_products' => $this->CI->Product_model->countAll(),
            'active_products' => $this->CI->Product_model->countActive(),

            'total_orders' => $this->CI->Order_model->countAll(),
            'paid_orders' => $this->CI->Order_model->countByStatus('paid'),
            'pending_orders' => $this->CI->Order_model->countByStatus('pending'),
            'revenue' => $this->CI->Order_model->sumPaidRevenue(),

            'total_invoices' => $this->CI->Invoice_model->countAll(),
            'total_receipts' => $this->CI->Receipt_model->countAll(),

            'recent_orders' => $this->CI->Order_model->getRecentWithCustomer(5),
            'recent_audit_logs' => $this->CI->Audit_log_model->getRecentWithUser(5),
        );
    }
}
