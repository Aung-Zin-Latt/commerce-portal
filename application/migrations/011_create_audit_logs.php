<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_audit_logs extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('audit_logs')) {
            return;
        }

        // -----------------------------------------------------
        // Create Audit Logs Table
        // -----------------------------------------------------
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => TRUE,
                'null'       => TRUE,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => FALSE,
            ],
            'entity_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => FALSE,
            ],
            'entity_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => TRUE,
                'null'       => FALSE,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => TRUE,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => FALSE,
            ],
        ]);

        // -----------------------------------------------------
        // Keys
        // -----------------------------------------------------
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key(['entity_type', 'entity_id']);
        $this->dbforge->add_key('created_at');

        // -----------------------------------------------------
        // Create Table
        // -----------------------------------------------------
        $this->dbforge->create_table('audit_logs', TRUE);

        // -----------------------------------------------------
        // Foreign Keys
        // -----------------------------------------------------
        $this->db->query('
            ALTER TABLE `audit_logs`
                ADD CONSTRAINT `fk_audit_logs_user`
                    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
                    ON UPDATE CASCADE ON DELETE SET NULL
        ');
    }

    public function down()
    {
        $this->dbforge->drop_table('audit_logs', TRUE);
    }
}
