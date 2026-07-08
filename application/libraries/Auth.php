<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function login($user)
    {
        $this->CI->session->set_userdata(array(
            'user_id' => (int) $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'role_name' => $user->role_name,
            'logged_in' => TRUE,
        ));
    }

    public function logout()
    {
        $this->CI->session->unset_userdata(array(
            'user_id',
            'user_name',
            'user_email',
            'role_name',
            'logged_in',
        ));
        $this->CI->session->sess_regenerate(TRUE);
    }

    public function check()
    {
        return (bool) $this->CI->session->userdata('logged_in');
    }

    public function id()
    {
        return $this->CI->session->userdata('user_id');
    }

    public function role()
    {
        return $this->CI->session->userdata('role_name');
    }

    public function isAdmin()
    {
        return $this->role() === 'admin';
    }

    public function isCustomer()
    {
        return $this->role() === 'customer';
    }
}
