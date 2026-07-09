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
    
}