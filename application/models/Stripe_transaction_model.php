<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stripe_transaction_model extends CI_Model
{
    protected $table = 'stripe_transactions';

    public function create(array $data)
    {
        $this->db->insert($this->table, $data);
        return (int) $this->db->insert_id();
    }

    public function existsByEventId(string $eventId)
    {
        return $this->db
            ->where('stripe_event_id', $eventId)
            ->count_all_results($this->table) > 0;
    }

    public function getAllWithPayments()
    {
        return $this->db
            ->select('stripe_transactions.*, payments.order_id, payments.amount, payments.currency, payments.status AS payment_status')
            ->from($this->table)
            ->join('payments', 'payments.id = stripe_transactions.payment_id')
            ->order_by('stripe_transactions.id', 'desc')
            ->get()
            ->result();
    }
}