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

    public function getAllWithUser()
    {
        return $this->db
            ->select('audit_logs.*, users.name AS user_name, users.email AS user_email')
            ->from($this->table)
            ->join('users', 'users.id = audit_logs.user_id', 'left')
            ->order_by('audit_logs.created_at', 'DESC')
            ->get()
            ->result();
    }

    public function countAll()
    {
        return (int) $this->db->count_all($this->table);
    }

    public function paginateWithUser($limit = 10, $offset = 0)
    {
        return $this->db
            ->select('audit_logs.*, users.name AS user_name, users.email AS user_email')
            ->from($this->table)
            ->join('users', 'users.id = audit_logs.user_id', 'left')
            ->order_by('audit_logs.created_at', 'DESC')
            ->limit((int) $limit, (int) $offset)
            ->get()
            ->result();
    }

    // Audit recent with user
    public function getRecentWithUser(int $limit = 5)
    {
        return $this->db
            ->select('audit_logs.*, users.name AS user_name, users.email AS user_email')
            ->from($this->table)
            ->join('users', 'users.id = audit_logs.user_id', 'left')
            ->order_by('audit_logs.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }
}