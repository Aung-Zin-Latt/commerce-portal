<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_api_tokens extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('api_tokens')) {
            return;
        }

        // -----------------------------------------------------
        // Create API Tokens Table
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
                'null'       => FALSE,
            ],
            'token_hash' => [
                'type'       => 'CHAR',
                'constraint' => 64,
                'null'       => FALSE,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => TRUE,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => FALSE,
            ],
            'revoked_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'last_used_at' => [
                'type' => 'DATETIME',
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
        $this->dbforge->add_key('expires_at');

        // -----------------------------------------------------
        // Create Table
        // -----------------------------------------------------
        $this->dbforge->create_table('api_tokens', TRUE);

        // -----------------------------------------------------
        // Unique Constraint & Foreign Keys
        // -----------------------------------------------------
        $this->db->query('
            ALTER TABLE `api_tokens`
                ADD CONSTRAINT `uq_api_tokens_hash` UNIQUE (`token_hash`),
                ADD CONSTRAINT `fk_api_tokens_user`
                    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
                    ON UPDATE CASCADE ON DELETE CASCADE
        ');
    }

    public function down()
    {
        $this->dbforge->drop_table('api_tokens', TRUE);
    }
}
