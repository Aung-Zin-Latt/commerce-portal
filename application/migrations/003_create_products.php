<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_products extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('products')) {
            return;
        }

        // -----------------------------------------------------
        // Create Products Table
        // -----------------------------------------------------
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ],
            'sku' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => TRUE,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => FALSE,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => TRUE,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => FALSE,
            ],
            'status' => [
                'type'    => 'ENUM("active","inactive")',
                'default' => 'active',
                'null'    => FALSE,
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
        $this->dbforge->add_key(['status', 'deleted_at']);

        // -----------------------------------------------------
        // Create Table
        // -----------------------------------------------------
        $this->dbforge->create_table('products', TRUE);

        // -----------------------------------------------------
        // Unique Constraint & Check Constraints
        // -----------------------------------------------------
        $this->db->query('
            ALTER TABLE `products`
                ADD CONSTRAINT `uq_products_sku` UNIQUE (`sku`),
                ADD CONSTRAINT `chk_products_price` CHECK (`price` >= 0)
        ');
    }

    public function down()
    {
        $this->dbforge->drop_table('products', TRUE);
    }
}
