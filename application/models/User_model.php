<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    protected $table = 'users';

    public function findById(int $id)
    {
        return $this->db
            ->select('users.*, roles.name AS role_name')
            ->from($this->table)
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.id', $id)
            ->where('users.deleted_at IS NULL', NULL, FALSE)
            ->get()
            ->row();
    }

    public function findByEmail(string $email)
    {
        return $this->db
            ->select('users.*, roles.name AS role_name')
            ->from($this->table)
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.email', $email)
            ->where('users.deleted_at IS NULL', NULL, FALSE)
            ->get()
            ->row();
    }

    public function findByEmailWithTrashed(string $email)
    {
        return $this->db
            ->select('users.*, roles.name AS role_name')
            ->from($this->table)
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.email', $email)
            ->get()
            ->row();
    }

    public function emailExists(string $email, $excludeId = NULL)
    {
        $this->db->where('email', $email);
        $this->db->where('deleted_at IS NULL', NULL, FALSE);

        if ($excludeId !== NULL) {
            $this->db->where('id !=', (int) $excludeId);
        }

        return $this->db->count_all_results($this->table) > 0;
    }

    public function create(array $data)
    {
        $this->db->insert($this->table, $data);
        return (int) $this->db->insert_id();
    }

    public function update(int $id, array $data)
    {
        return $this->db
            ->where('id', $id)
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->update($this->table, $data);
    }

    public function softDelete(int $id)
    {
        return $this->db
            ->where('id', $id)
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->update($this->table, array(
                'deleted_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ));
    }

    public function restore(int $id)
    {
        return $this->db
            ->where('id', $id)
            ->where('deleted_at IS NOT NULL', NULL, FALSE)
            ->update($this->table, array(
                'deleted_at' => NULL,
                'updated_at' => date('Y-m-d H:i:s'),
            ));
    }

    public function findByIdWithTrashed(int $id)
    {
        return $this->db
            ->select('users.*, roles.name AS role_name')
            ->from($this->table)
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.id', $id)
            ->get()
            ->row();
    }

    public function updateLastLogin(int $id)
    {
        return $this->update($id, array(
            'last_login_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ));
    }

    public function getRoleByName(string $name)
    {
        return $this->db
            ->where('name', $name)
            ->get('roles')
            ->row();
    }

    public function getAllRoles()
    {
        return $this->db
            ->order_by('name', 'ASC')
            ->get('roles')
            ->result();
    }

    public function countActive()
    {
        return (int) $this->db
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->where('status', 'active')
            ->count_all_results($this->table);
    }

    public function countAll()
    {
        return (int) $this->db
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->count_all_results($this->table);
    }

    public function paginate(array $filters = array(), int $limit = 10, int $offset = 0)
    {
        $this->applyFilters($filters);

        return $this->db
            ->select('users.*, roles.name AS role_name')
            ->from($this->table)
            ->join('roles', 'roles.id = users.role_id')
            ->order_by('users.deleted_at IS NULL', 'DESC', FALSE)
            ->order_by('users.created_at', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->result();
    }

    public function countFiltered(array $filters = array())
    {
        $this->applyFilters($filters);

        return (int) $this->db
            ->from($this->table)
            ->join('roles', 'roles.id = users.role_id')
            ->count_all_results();
    }

    protected function applyFilters(array $filters)
    {
        $this->applyDeletedScope($filters);
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('users.name', $search);
            $this->db->or_like('users.email', $search);
            $this->db->group_end();
        }

        if (!empty($filters['role'])) {
            $this->db->where('roles.name', $filters['role']);
        }

        if (!empty($filters['status']) && $filters['status'] !== 'archived') {
            $this->db->where('users.status', $filters['status']);
        }
    }

    protected function applyDeletedScope(array $filters)
    {
        if (!empty($filters['status']) && $filters['status'] === 'archived') {
            $this->db->where('users.deleted_at IS NOT NULL', NULL, FALSE);
            return;
        }

        if (empty($filters['include_deleted'])) {
            $this->db->where('users.deleted_at IS NULL', NULL, FALSE);
        }
    }
}
