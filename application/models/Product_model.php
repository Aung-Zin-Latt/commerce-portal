<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{
    protected $table = 'products';

    public function findById(int $id)
    {
        return $this->db
            ->where('id', $id)
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->get($this->table)
            ->row();
    }

    public function skuExists(?string $sku, ?int $excludeId = NULL)
    {
        if ($sku === NULL || $sku === '') {
            return FALSE;
        }

        $this->db->where('sku', $sku);
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
        return $this->update($id, array(
            'deleted_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ));
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
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->order_by('created_at', 'DESC')
            ->limit($limit, $offset)
            ->get($this->table)
            ->result();
    }

    public function countFiltered(array $filters = array())
    {
        $this->applyFilters($filters);

        return (int) $this->db
            ->where('deleted_at IS NULL', NULL, FALSE)
            ->count_all_results($this->table);
    }

    protected function applyFilters(array $filters)
    {
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('sku', $search);
            $this->db->group_end();
        }

        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }
    }
}
