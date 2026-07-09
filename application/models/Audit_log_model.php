<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_log_model extends CI_Model
{
    protected $table = 'audit_logs';

    public function create(array $data)
    {
        $this->db->insert($this->table, $data);

        return (int) $this->db->insert_id();
    }

    // Helper for admin UI / debugging later
    public function getByEntity(string $entityType, int $entityId)
    {
        return $this->db
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->order_by('created_at', 'desc')
            ->get($this->table)
            ->result();
    }
}