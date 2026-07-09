<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model
{
    protected $table = 'payments';

    public function create(array $data)
    {
        $this->db->insert($this->table, $data);
        return (int) $this->db->insert_id();
    }

    public function findById(int $id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function findPendingByOrderId(int $orderId)
    {
        return $this->db
            ->where('order_id', $orderId)
            ->where('status', 'pending')
            ->order_by('id', 'desc')
            ->limit(1)
            ->get($this->table)
            ->row();
    }

    public function findPaidByOrderId(int $orderId)
    {
        return $this->db
            ->where('order_id', $orderId)
            ->where('status', 'paid')
            ->limit(1)
            ->get($this->table)
            ->row();
    }

    public function updateById(int $id, array $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }
}