<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_order_items extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('order_items')) {
            return;
        }

        // -----------------------------------------------------
        // Create Order Items Table
        // -----------------------------------------------------
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ],
            'order_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => TRUE,
                'null'       => FALSE,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => TRUE,
                'null'       => FALSE,
            ],
            'product_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => FALSE,
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => FALSE,
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => TRUE,
                'null'       => FALSE,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => FALSE,
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
        $this->dbforge->add_key('order_id');
        $this->dbforge->add_key('product_id');

        // -----------------------------------------------------
        // Create Table
        // -----------------------------------------------------
        $this->dbforge->create_table('order_items', TRUE);

        // -----------------------------------------------------
        // Foreign Keys & Check Constraints
        // -----------------------------------------------------
        $this->db->query('
            ALTER TABLE `order_items`
                ADD CONSTRAINT `fk_order_items_order`
                    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
                    ON UPDATE CASCADE ON DELETE CASCADE,
                ADD CONSTRAINT `fk_order_items_product`
                    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                ADD CONSTRAINT `chk_order_items_quantity` CHECK (`quantity` > 0),
                ADD CONSTRAINT `chk_order_items_unit_price` CHECK (`unit_price` >= 0),
                ADD CONSTRAINT `chk_order_items_subtotal` CHECK (`subtotal` >= 0)
        ');
    }

    public function down()
    {
        $this->dbforge->drop_table('order_items', TRUE);
    }
}
