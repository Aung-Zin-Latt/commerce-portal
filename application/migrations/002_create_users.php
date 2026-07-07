<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_users extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('users')) {
            return;
        }

        // -----------------------------------------------------
        // Create Users Table
        // -----------------------------------------------------
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ],
            'role_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => TRUE,
                'null'       => FALSE,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => FALSE,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => FALSE,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => FALSE,
            ],
            'status' => [
                'type'    => 'ENUM("active","inactive")',
                'default' => 'active',
                'null'    => FALSE,
            ],
            'last_login_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => FALSE,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => FALSE,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
        ]);

        // -----------------------------------------------------
        // Keys
        // -----------------------------------------------------
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('role_id');
        $this->dbforge->add_key(['status', 'deleted_at']);

        // -----------------------------------------------------
        // Create Table
        // -----------------------------------------------------
        $this->dbforge->create_table('users', TRUE);

        // -----------------------------------------------------
        // Unique Constraint & Foreign Keys
        // -----------------------------------------------------
        $this->db->query('
            ALTER TABLE `users`
                ADD CONSTRAINT `uq_users_email` UNIQUE (`email`),
                ADD CONSTRAINT `fk_users_role`
                    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
                    ON UPDATE CASCADE ON DELETE RESTRICT
        ');
    }

    public function down()
    {
        $this->dbforge->drop_table('users', TRUE);
    }
}
