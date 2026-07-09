<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_token_model extends CI_Model
{
    protected $table = 'api_tokens';

    public function create(array $data)
    {
        $this->db->insert($this->table, $data);

        return (int) $this->db->insert_id();
    }

    /**
     * Find an active (non-revoked, non-expired) token by hash.
     *
     * @param string $tokenHash
     * @return object|null
     */
    public function findActiveByHash($tokenHash)
    {
        $now = date('Y-m-d H:i:s');

        return $this->db
            ->where('token_hash', $tokenHash)
            ->where('revoked_at IS NULL', NULL, FALSE)
            ->where('expires_at >', $now)
            ->get($this->table)
            ->row();
    }

    public function touchLastUsed($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, array(
                'last_used_at' => date('Y-m-d H:i:s'),
            ));
    }

    public function revokeById($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->where('revoked_at IS NULL', NULL, FALSE)
            ->update($this->table, array(
                'revoked_at' => date('Y-m-d H:i:s'),
            ));
    }
}