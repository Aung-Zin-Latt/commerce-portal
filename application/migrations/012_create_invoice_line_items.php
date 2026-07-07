<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_invoice_line_items extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('invoice_line_items')) {
            return;
        }

        // -----------------------------------------------------
        // Create Invoice Line Items Table
        // -----------------------------------------------------
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ],
            'invoice_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
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
        $this->dbforge->add_key('invoice_id');

        // -----------------------------------------------------
        // Create Table
        // -----------------------------------------------------
        $this->dbforge->create_table('invoice_line_items', TRUE);

        // -----------------------------------------------------
        // Foreign Keys & Check Constraints
        // -----------------------------------------------------
        $this->db->query('
            ALTER TABLE `invoice_line_items`
                ADD CONSTRAINT `fk_invoice_line_items_invoice`
                    FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`)
                    ON UPDATE CASCADE ON DELETE CASCADE,
                ADD CONSTRAINT `chk_invoice_line_items_quantity` CHECK (`quantity` > 0),
                ADD CONSTRAINT `chk_invoice_line_items_unit_price` CHECK (`unit_price` >= 0),
                ADD CONSTRAINT `chk_invoice_line_items_subtotal` CHECK (`subtotal` >= 0)
        ');
    }

    public function down()
    {
        $this->dbforge->drop_table('invoice_line_items', TRUE);
    }
}
