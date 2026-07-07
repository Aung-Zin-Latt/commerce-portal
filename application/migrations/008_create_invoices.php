<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_invoices extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('invoices')) {
            return;
        }

        // -----------------------------------------------------
        // Create Invoices Table
        // -----------------------------------------------------
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE,
            ],
            'payment_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => TRUE,
                'null'       => FALSE,
            ],
            'order_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => TRUE,
                'null'       => FALSE,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => TRUE,
                'null'       => FALSE,
            ],
            'invoice_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => FALSE,
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
            'issued_at' => [
                'type' => 'DATETIME',
                'null' => FALSE,
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
        $this->dbforge->add_key('payment_id');
        $this->dbforge->add_key('order_id');
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('issued_at');

        // -----------------------------------------------------
        // Create Table
        // -----------------------------------------------------
        $this->dbforge->create_table('invoices', TRUE);

        // -----------------------------------------------------
        // Unique Constraints, Foreign Keys & Check Constraints
        // -----------------------------------------------------
        $this->db->query('
            ALTER TABLE `invoices`
                ADD CONSTRAINT `uq_invoices_number` UNIQUE (`invoice_number`),
                ADD CONSTRAINT `uq_invoices_payment` UNIQUE (`payment_id`),
                ADD CONSTRAINT `fk_invoices_payment`
                    FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                ADD CONSTRAINT `fk_invoices_order`
                    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                ADD CONSTRAINT `fk_invoices_user`
                    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                ADD CONSTRAINT `chk_invoices_subtotal` CHECK (`subtotal` >= 0),
                ADD CONSTRAINT `chk_invoices_tax_amount` CHECK (`tax_amount` >= 0),
                ADD CONSTRAINT `chk_invoices_total_amount` CHECK (`total_amount` >= 0)
        ');
    }

    public function down()
    {
        $this->dbforge->drop_table('invoices', TRUE);
    }
}
