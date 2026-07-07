<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_orders extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('orders')) {
            return;
        }

        // -----------------------------------------------------
        // Create Orders Table
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
            'order_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => FALSE,
            ],
            'status' => [
                'type'    => 'ENUM("pending","paid","failed","cancelled","refunded")',
                'default' => 'pending',
                'null'    => FALSE,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => FALSE,
            ],
            'tax_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => '0.00',
                'null'       => FALSE,
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => FALSE,
            ],
            'currency' => [
                'type'       => 'CHAR',
                'constraint' => 3,
                'default'    => 'SGD',
                'null'       => FALSE,
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
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('status');
        $this->dbforge->add_key('created_at');

        // -----------------------------------------------------
        // Create Table
        // -----------------------------------------------------
        $this->dbforge->create_table('orders', TRUE);

        // -----------------------------------------------------
        // Unique Constraint, Foreign Keys & Check Constraints
        // -----------------------------------------------------
        $this->db->query('
            ALTER TABLE `orders`
                ADD CONSTRAINT `uq_orders_number` UNIQUE (`order_number`),
                ADD CONSTRAINT `fk_orders_user`
                    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                ADD CONSTRAINT `chk_orders_subtotal` CHECK (`subtotal` >= 0),
                ADD CONSTRAINT `chk_orders_tax_amount` CHECK (`tax_amount` >= 0),
                ADD CONSTRAINT `chk_orders_total_amount` CHECK (`total_amount` >= 0)
        ');
    }

    public function down()
    {
        $this->dbforge->drop_table('orders', TRUE);
    }
}
