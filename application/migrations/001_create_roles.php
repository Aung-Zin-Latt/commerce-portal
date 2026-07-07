<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_roles extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('roles')) {
            return;
        }

        // -----------------------------------------------------
        // Create Roles Table
        // -----------------------------------------------------
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => FALSE,
            ],
            'description' => [
                'type' => 'TEXT',
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
        ]);

        // -----------------------------------------------------
        // Keys
        // -----------------------------------------------------
        $this->dbforge->add_key('id', TRUE);

        // -----------------------------------------------------
        // Create Table
        // -----------------------------------------------------
        $this->dbforge->create_table('roles', TRUE);

        // -----------------------------------------------------
        // Unique Constraint
        // -----------------------------------------------------
        $this->db->query('
            ALTER TABLE `roles`
                ADD CONSTRAINT `uq_roles_name` UNIQUE (`name`)
        ');
    }

    public function down()
    {
        $this->dbforge->drop_table('roles', TRUE);
    }
}
