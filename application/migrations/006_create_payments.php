<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_payments extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('payments')) {
            return;
        }

        // -----------------------------------------------------
        // Create Payments Table
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
            'payment_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => FALSE,
            ],
            'provider' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => FALSE,
            ],
            'provider_reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => TRUE,
            ],
            'currency' => [
                'type'       => 'CHAR',
                'constraint' => 3,
                'default'    => 'SGD',
                'null'       => FALSE,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => FALSE,
            ],
            'status' => [
                'type'    => 'ENUM("pending","paid","failed","refunded","partially_refunded")',
                'default' => 'pending',
                'null'    => FALSE,
            ],
            'paid_at' => [
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
        ]);

        // -----------------------------------------------------
        // Keys
        // -----------------------------------------------------
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('order_id');
        $this->dbforge->add_key('provider');
        $this->dbforge->add_key('provider_reference');
        $this->dbforge->add_key('status');
        $this->dbforge->add_key('paid_at');

        // -----------------------------------------------------
        // Create Table
        // -----------------------------------------------------
        $this->dbforge->create_table('payments', TRUE);

        // -----------------------------------------------------
        // Unique Constraint, Foreign Keys & Check Constraints
        // -----------------------------------------------------
        $this->db->query('
            ALTER TABLE `payments`
                ADD CONSTRAINT `uq_payments_reference` UNIQUE (`payment_reference`),
                ADD CONSTRAINT `uq_payments_provider_reference` UNIQUE (`provider`, `provider_reference`),
                ADD CONSTRAINT `fk_payments_order`
                    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                ADD CONSTRAINT `chk_payments_amount` CHECK (`amount` >= 0)
        ');
    }

    public function down()
    {
        $this->dbforge->drop_table('payments', TRUE);
    }
}
