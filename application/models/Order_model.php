<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model
{
    protected $table = 'orders';

    public function getAllOrdersForUser(int $userId)
    {
        return $this->db
            ->where('user_id', $userId)
            ->order_by('created_at', 'desc')
            ->get($this->table)
            ->result();
    }

    public function countForUser(int $userId)
    {
        return (int) $this->db
            ->where('user_id', (int) $userId)
            ->count_all_results($this->table);
    }

    public function paginateForUser(int $userId, $limit = 10, $offset = 0)
    {
        return $this->db
            ->where('user_id', (int) $userId)
            ->order_by('created_at', 'desc')
            ->limit((int) $limit, (int) $offset)
            ->get($this->table)
            ->result();
    }

    public function findByIdForUser(int $orderId, int $userId)
    {
        return $this->db
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->get($this->table)
            ->row();
    }

    public function createOrder(array $orderData)
    {
        $this->db->insert($this->table, $orderData);

        return (int) $this->db->insert_id();
    }

    // Stripe
    public function updateById(int $id, array $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    // Admin Order List
    public function findById(int $orderId)
    {
        return $this->db
            ->where('id', $orderId)
            ->get($this->table)
            ->row();
    }
    public function getAllOrdersWithCustomer()
    {
        return $this->db
            ->select('orders.*, users.name AS customer_name, users.email AS customer_email')
            ->select('(SELECT provider FROM payments WHERE payments.order_id = orders.id ORDER BY id DESC LIMIT 1) AS payment_provider', false)
            ->from($this->table)
            ->join('users', 'users.id = orders.user_id')
            ->order_by('orders.created_at', 'DESC')
            ->get()
            ->result();
    }

    public function paginateWithCustomer($limit = 10, $offset = 0)
    {
        return $this->db
            ->select('orders.*, users.name AS customer_name, users.email AS customer_email')
            ->select('(SELECT provider FROM payments WHERE payments.order_id = orders.id ORDER BY id DESC LIMIT 1) AS payment_provider', false)
            ->from($this->table)
            ->join('users', 'users.id = orders.user_id')
            ->order_by('orders.created_at', 'DESC')
            ->limit((int) $limit, (int) $offset)
            ->get()
            ->result();
    }

    // Order count/sum helpers
    public function countAll()
    {
        return (int) $this->db->count_all($this->table);
    }
    public function countByStatus(string $status)
    {
        return (int) $this->db
            ->where('status', $status)
            ->count_all_results($this->table);
    }
    public function sumPaidRevenue()
    {
        $row = $this->db
            ->select_sum('total_amount', 'revenue')
            ->where('status', 'paid')
            ->get($this->table)
            ->row();

        return $row && $row->revenue !== NULL ? (float) $row->revenue : 0.0;
    }
    public function getRecentWithCustomer(int $limit = 5)
    {
        return $this->db
            ->select('orders.*, users.name AS customer_name, users.email AS customer_email')
            ->from($this->table)
            ->join('users', 'users.id = orders.user_id')
            ->order_by('orders.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }
    
}