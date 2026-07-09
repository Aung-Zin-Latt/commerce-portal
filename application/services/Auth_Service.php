<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_service
{
    /**
     * @var CI_Controller
     */
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->load->model('User_model');
        $this->CI->load->library('auth');
    }

    /**
     * Attempt to authenticate a user.
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public function attempt($email, $password)
    {
        $user = $this->CI->User_model->findByEmailWithTrashed($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Invalid email or password.',
            ];
        }

        if (!empty($user->deleted_at)) {
            return [
                'success' => false,
                'message' => 'Your account is no longer active.',
            ];
        }

        if ($user->status !== 'active') {
            return [
                'success' => false,
                'message' => 'Your account is inactive.',
            ];
        }

        if (!password_verify($password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Invalid email or password.',
            ];
        }

        $this->CI->User_model->updateLastLogin($user->id);

        $this->CI->auth->login($user);

        return [
            'success' => true,
            'user' => $user,
        ];
    }

    /**
     * Verify credentials without creating a web session.
     * Used by the API login endpoint.
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public function verifyCredentials($email, $password)
    {
        $user = $this->CI->User_model->findByEmailWithTrashed($email);

        if (!$user) {
            return array(
                'success' => FALSE,
                'message' => 'Invalid email or password.',
            );
        }

        if (!empty($user->deleted_at)) {
            return array(
                'success' => FALSE,
                'message' => 'Your account is no longer active.',
            );
        }

        if ($user->status !== 'active') {
            return array(
                'success' => FALSE,
                'message' => 'Your account is inactive.',
            );
        }

        if (!password_verify($password, $user->password)) {
            return array(
                'success' => FALSE,
                'message' => 'Invalid email or password.',
            );
        }

        return array(
            'success' => TRUE,
            'user' => $user,
        );
    }

    /**
     * Logout current user.
     */
    public function logout()
    {
        $this->CI->auth->logout();
    }

    /**
     * Hash password.
     *
     * @param string $password
     * @return string
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify password.
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}