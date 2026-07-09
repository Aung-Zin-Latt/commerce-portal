<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipt_model extends CI_Model
{
    protected $table = 'receipts';

    public function findById($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->get($this->table)
            ->row();
    }

    /**
     * Find a receipt only when it belongs to the given user.
     *
     * @param int $id
     * @param int $userId
     * @return object|null
     */
    public function findByIdForUser($id, $userId)
    {
        return $this->db
            ->where('id', (int) $id)
            ->where('user_id', (int) $userId)
            ->get($this->table)
            ->row();
    }

    public function getAllForUser($userId)
    {
        return $this->db
            ->where('user_id', (int) $userId)
            ->order_by('issued_at', 'DESC')
            ->get($this->table)
            ->result();
    }

    // Receipts create
    public function create(array $data)
    {
        $this->db->insert($this->table, $data);

        return (int) $this->db->insert_id();
    }
    public function findByPaymentId(int $paymentId)
    {
        return $this->db
            ->where('payment_id', $paymentId)
            ->get($this->table)
            ->row();
    }
    public function findByOrderIdForUser(int $orderId, int $userId)
    {
        return $this->db
            ->where('order_id', $orderId)
            ->where('user_id', $userId)
            ->get($this->table)
            ->row();
    }
}
