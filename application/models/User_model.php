<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    protected $table = 'users';

    public function findById($id)
    {
        return $this->db
            ->select('users.*, roles.name AS role_name')
            ->from($this->table)
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.id', $id)
            ->get()
            ->row();
    }

    public function findByEmail($email)
    {
        return $this->db
            ->select('users.*, roles.name AS role_name')
            ->from($this->table)
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.email', $email)
            ->get()
            ->row();
    }

    public function create(array $data)
    {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function update($id, array $data)
    {
        return $this->db
            ->where('id', $id)
            ->update($this->table, $data);
    }

    public function softDelete($id)
    {
        return $this->update($id, array(
            'deleted_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ));
    }

    public function updateLastLogin($id)
    {
        return $this->update($id, array(
            'last_login_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ));
    }

    public function getRoleByName($name)
    {
        return $this->db
            ->where('name', $name)
            ->get('roles')
            ->row();
    }
}
