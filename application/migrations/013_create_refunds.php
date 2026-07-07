<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_refunds extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('refunds')) {
            return;
        }

        // -----------------------------------------------------
        // Create Refunds Table
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
            'stripe_refund_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => TRUE,
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
            'status' => [
                'type'    => 'ENUM("pending","succeeded","failed","cancelled")',
                'default' => 'pending',
                'null'    => FALSE,
            ],
            'reason' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => TRUE,
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
        $this->dbforge->add_key('status');

        // -----------------------------------------------------
        // Create Table
        // -----------------------------------------------------
        $this->dbforge->create_table('refunds', TRUE);

        // -----------------------------------------------------
        // Unique Constraint, Foreign Keys & Check Constraints
        // -----------------------------------------------------
        $this->db->query('
            ALTER TABLE `refunds`
                ADD CONSTRAINT `uq_refunds_stripe_refund_id` UNIQUE (`stripe_refund_id`),
                ADD CONSTRAINT `fk_refunds_payment`
                    FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`)
                    ON UPDATE CASCADE ON DELETE RESTRICT,
                ADD CONSTRAINT `chk_refunds_amount` CHECK (`amount` > 0)
        ');
    }

    public function down()
    {
        $this->dbforge->drop_table('refunds', TRUE);
    }
}
