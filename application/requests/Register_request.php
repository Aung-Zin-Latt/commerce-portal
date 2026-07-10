<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'requests/Form_request.php';

class Register_request extends Form_request
{
    public function rules()
    {
        return array(
            array('name', 'Name', 'trim|required|min_length[2]|max_length[150]'),
            array('email', 'Email', 'trim|required|valid_email|is_unique[users.email]'),
            array('password', 'Password', 'required|min_length[8]'),
            array('password_confirm', 'Confirm Password', 'required|matches[password]'),
        );
    }
}