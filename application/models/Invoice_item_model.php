<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_item_model extends CI_Model
{
    protected $table = 'invoice_line_items';

    public function createMany(array $rows)
    {
        if (empty($rows)) {
            return FALSE;
        }
        return $this->db->insert_batch($this->table, $rows);
    }
    
    public function getByInvoiceId(int $invoiceId)
    {
        return $this->db
            ->where('invoice_id', $invoiceId)
            ->order_by('id', 'asc')
            ->get($this->table)
            ->result();
    }
}