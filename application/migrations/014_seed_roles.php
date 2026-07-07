<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_roles extends CI_Migration
{
    public function up()
    {
        // -----------------------------------------------------
        // Seed Default Roles
        // -----------------------------------------------------
        $now = date('Y-m-d H:i:s');

        $roles = [
            [
                'name'        => 'admin',
                'description' => 'Administrator with full access to the portal',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'customer',
                'description' => 'Standard customer who can purchase products',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        foreach ($roles as $role) {
            $exists = $this->db
                ->where('name', $role['name'])
                ->count_all_results('roles');

            if ($exists === 0) {
                $this->db->insert('roles', $role);
            }
        }
    }

    public function down()
    {
        // -----------------------------------------------------
        // Remove Seeded Roles
        // -----------------------------------------------------
        $this->db->where_in('name', ['admin', 'customer']);
        $this->db->delete('roles');
    }
}
