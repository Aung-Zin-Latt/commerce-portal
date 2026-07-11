<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'requests/Form_request.php';

class Api_login_request extends Form_request
{
    public function rules()
    {
        return array(
            array('email', 'Email', 'trim|required|valid_email'),
            array('password', 'Password', 'required'),
        );
    }

    /**
     * Build payload from form POST or JSON body.
     *
     * @return array
     */
    public function payloadFromRequest()
    {
        $email = $this->CI->input->post('email', TRUE);
        $password = $this->CI->input->post('password'); // no XSS clean — passwords may contain < >

        if (($email === NULL || $email === '') && ($password === NULL || $password === '')) {
            $raw = $this->CI->input->raw_input_stream;
            $json = json_decode($raw, TRUE);

            if (is_array($json)) {
                $email = isset($json['email']) ? trim((string) $json['email']) : '';
                $password = isset($json['password']) ? (string) $json['password'] : '';
            }
        }

        return array(
            'email' => strip_tags(trim((string) $email)),
            'password' => (string) $password, // never strip_tags / XSS-clean passwords
        );
    }
}