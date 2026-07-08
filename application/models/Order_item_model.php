<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_item_model extends CI_Model
{
    protected $table = 'order_items';

    public function createMany(array $rows)
    {
        if (empty($rows)) {
            return FALSE;
        }

        return $this->db->insert_batch($this->table, $rows);
    }

    public function getByOrderId(int $orderId)
    {
        return $this->db
            ->where('order_id', $orderId)
            ->order_by('id', 'asc')
            ->get($this->table)
            ->result();
    }
}