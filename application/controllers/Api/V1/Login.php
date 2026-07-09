<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * API login endpoint.
 *
 * Named Login (not Auth) to avoid colliding with application/libraries/Auth.php.
 * CI3 cannot load a library whose class name matches the current controller.
 */
class Login extends MY_Api_Controller
{
    public function index()
    {
        if (strtoupper($this->input->method(TRUE)) !== 'POST') {
            return json_error('Method not allowed.', 405);
        }

        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password', TRUE);

        // Accept JSON body as well as form-encoded POST.
        if (($email === NULL || $email === '') && ($password === NULL || $password === '')) {
            $raw = $this->input->raw_input_stream;
            $json = json_decode($raw, TRUE);

            if (is_array($json)) {
                $email = isset($json['email']) ? trim((string) $json['email']) : '';
                $password = isset($json['password']) ? (string) $json['password'] : '';
            }
        }

        $email = trim((string) $email);
        $password = (string) $password;

        if ($email === '' || $password === '') {
            return json_error('Email and password are required.', 422);
        }

        $authService = $this->loadService('Auth_service');
        $result = $authService->verifyCredentials($email, $password);

        if (empty($result['success'])) {
            return json_error($result['message'], 401);
        }

        $user = $result['user'];
        $tokenService = $this->loadService('Api_token_service');
        $issued = $tokenService->issueToken((int) $user->id, 'API Login');

        $this->load->model('User_model');
        $this->User_model->updateLastLogin((int) $user->id);

        return json_success(array(
            'token' => $issued['token'],
            'token_type' => 'Bearer',
            'expires_in' => $issued['expires_in'],
            'expires_at' => $issued['expires_at'],
            'user' => array(
                'id' => (int) $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role_name,
            ),
        ), 'Login successful.');
    }
}