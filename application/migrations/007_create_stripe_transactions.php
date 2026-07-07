<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_stripe_transactions extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('stripe_transactions')) {
            return;
        }

        // -----------------------------------------------------
        // Create Stripe Transactions Table
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
            'stripe_event_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => FALSE,
            ],
            'stripe_payment_intent' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => TRUE,
            ],
            'stripe_session_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => TRUE,
            ],
            'event_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => FALSE,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => FALSE,
            ],
            'payload' => [
                'type' => 'LONGTEXT',
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
        $this->dbforge->add_key('payment_id');
        $this->dbforge->add_key('stripe_event_id');
        $this->dbforge->add_key('stripe_payment_intent');
        $this->dbforge->add_key('stripe_session_id');
        $this->dbforge->add_key('event_type');

        // -----------------------------------------------------
        // Create Table
        // -----------------------------------------------------
        $this->dbforge->create_table('stripe_transactions', TRUE);

        // -----------------------------------------------------
        // Unique Constraint & Foreign Keys
        // -----------------------------------------------------
        $this->db->query('
            ALTER TABLE `stripe_transactions`
                ADD CONSTRAINT `uq_stripe_event_id` UNIQUE (`stripe_event_id`),
                ADD CONSTRAINT `fk_stripe_transactions_payment`
                    FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`)
                    ON UPDATE CASCADE ON DELETE RESTRICT
        ');
    }

    public function down()
    {
        $this->dbforge->drop_table('stripe_transactions', TRUE);
    }
}
