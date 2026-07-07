<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_receipts extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('receipts')) {
            return;
        }

        // -----------------------------------------------------
        // Create Receipts Table
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
            'receipt_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => FALSE,
            ],
            'amount' => [
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
        $this->dbforge->create_table('receipts', TRUE);

        // -----------------------------------------------------
        // Unique Constraints, Foreign Keys & Check Constraints
        // -----------------------------------------------------
        $this->db->query('
            ALTER TABLE `receipts`
                ADD CONSTRAINT `uq_receipts_number` UNIQUE (`receipt_number`),
                ADD CONSTRAINT `uq_receipts_payment` UNIQUE (`payment_id`),
                ADD CONSTRAINT `fk_receipts_payment`
                    FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                ADD CONSTRAINT `fk_receipts_order`
                    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                ADD CONSTRAINT `fk_receipts_user`
                    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                ADD CONSTRAINT `chk_receipts_amount` CHECK (`amount` >= 0)
        ');
    }

    public function down()
    {
        $this->dbforge->drop_table('receipts', TRUE);
    }
}
