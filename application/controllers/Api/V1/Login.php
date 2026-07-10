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

        $request = $this->makeRequest('Api_login_request');
        $payload = $request->payloadFromRequest();

        if (!$request->setData($payload)->validate()) {
            return json_error('Validation failed.', 422, $request->errors());
        }

        $email = $request->input('email');
        $password = $request->input('password');

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