<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_users extends CI_Migration
{
    public function up()
    {
        $now = date('Y-m-d H:i:s');

        $adminRole = $this->db
            ->where('name', 'admin')
            ->get('roles')
            ->row();

        $customerRole = $this->db
            ->where('name', 'customer')
            ->get('roles')
            ->row();

        if (!$adminRole || !$customerRole) {
            show_error('Default roles are missing. Run the role seed migration before seeding users.');
        }

        $users = array(
            array(
                'role_id' => $adminRole->id,
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ),
            array(
                'role_id' => $customerRole->id,
                'name' => 'Customer User',
                'email' => 'customer@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ),
        );

        foreach ($users as $user) {
            $exists = $this->db
                ->where('email', $user['email'])
                ->count_all_results('users');

            if ($exists === 0) {
                $this->db->insert('users', $user);
            }
        }
    }

    public function down()
    {
        $this->db->where_in('email', array(
            'admin@example.com',
            'customer@example.com',
        ));
        $this->db->delete('users');
    }
}
