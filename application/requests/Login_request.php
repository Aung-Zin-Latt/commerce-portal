<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_request extends Form_request
{
    public function rules()
    {
        return array(
            array('email', 'Email', 'trim|required|valid_email'),
            array('password', 'Password', 'required'),
        );
    }
}