<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_token_service
{
    protected $CI;

    /** Default token lifetime in days. */
    const DEFAULT_TTL_DAYS = 30;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Api_token_model');
        $this->CI->load->model('User_model');
    }

    /**
     * Issue a new bearer token for a user.
     * Returns the raw token once; only the SHA-256 hash is stored.
     *
     * @param int $userId
     * @param string|null $name
     * @param int $days
     * @return array{token:string,expires_at:string,expires_in:int}
     */
    public function issueToken($userId, $name = NULL, $days = self::DEFAULT_TTL_DAYS)
    {
        $rawToken = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+' . (int) $days . ' days'));
        $now = date('Y-m-d H:i:s');

        $this->CI->Api_token_model->create(array(
            'user_id' => (int) $userId,
            'token_hash' => $this->hashToken($rawToken),
            'name' => $name,
            'expires_at' => $expiresAt,
            'revoked_at' => NULL,
            'last_used_at' => NULL,
            'created_at' => $now,
        ));

        return array(
            'token' => $rawToken,
            'expires_at' => $expiresAt,
            'expires_in' => (int) $days * 86400,
        );
    }

    /**
     * Validate a raw bearer token and return the active user, or FALSE.
     *
     * @param string $rawToken
     * @return object|false
     */
    public function authenticateBearer($rawToken)
    {
        $rawToken = trim((string) $rawToken);

        if ($rawToken === '') {
            return FALSE;
        }

        $token = $this->CI->Api_token_model->findActiveByHash($this->hashToken($rawToken));

        if (!$token) {
            return FALSE;
        }

        $user = $this->CI->User_model->findById((int) $token->user_id);

        if (!$user || $user->status !== 'active') {
            return FALSE;
        }

        $this->CI->Api_token_model->touchLastUsed((int) $token->id);

        return $user;
    }

    /**
     * @param string $rawToken
     * @return string
     */
    public function hashToken($rawToken)
    {
        return hash('sha256', $rawToken);
    }
}