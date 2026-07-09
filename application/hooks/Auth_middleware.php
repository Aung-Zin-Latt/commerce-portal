<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CI3 middleware hook.
 *
 * CodeIgniter 3 has no built-in middleware stack. Hooks are the framework's
 * equivalent: this class runs on every request before the controller action.
 */
class Auth_middleware
{
    public function run()
    {
        $CI =& get_instance();
        $CI->config->load('auth', TRUE);

        $directory = strtolower((string) $CI->router->directory);
        $controller = strtolower((string) $CI->router->class);

        if ($this->isPublicRoute($directory, $controller)) {
            return;
        }

        $rules = $CI->config->item('auth_middleware', 'auth');

        if (!is_array($rules)) {
            return;
        }

        foreach ($rules as $prefix => $rule) {
            if (strpos($directory, $prefix) !== 0) {
                continue;
            }

            if ($rule['type'] === 'session') {
                $this->enforceSessionRole($CI, $rule);
                return;
            }

            if ($rule['type'] === 'bearer') {
                $this->enforceBearerToken($CI, $rule);
                return;
            }
        }
    }

    protected function isPublicRoute($directory, $controller)
    {
        $CI =& get_instance();

        $publicControllers = $CI->config->item('auth_public_controllers', 'auth');
        $publicDirectories = $CI->config->item('auth_public_directories', 'auth');

        if (is_array($publicControllers) && in_array($controller, $publicControllers, TRUE)) {
            return TRUE;
        }

        if (!is_array($publicDirectories)) {
            return FALSE;
        }

        foreach ($publicDirectories as $publicDirectory) {
            if (strpos($directory, strtolower($publicDirectory)) === 0) {
                return TRUE;
            }
        }

        return FALSE;
    }

    protected function enforceSessionRole($CI, array $rule)
    {
        $CI->load->library('auth');

        if (!$CI->auth->check()) {
            $CI->session->set_flashdata('error', $rule['login_message']);
            redirect($rule['login_redirect']);
        }

        $CI->load->model('User_model');
        $user = $CI->User_model->findById((int) $CI->auth->id());

        if (!$user || $user->status !== 'active') {
            $CI->auth->logout();
            $CI->session->set_flashdata('error', 'Your account is no longer active.');
            redirect($rule['login_redirect']);
        }

        $expectedRole = $rule['role'];
        $actualRole = $CI->auth->role();

        if ($actualRole !== $expectedRole) {
            $CI->session->set_flashdata('error', $rule['denied_message']);
            redirect($rule['denied_redirect']);
        }
    }

    protected function enforceBearerToken($CI, array $rule)
    {
        $CI->load->helper('api');
        $CI->load->library('auth');

        $controller = strtolower((string) $CI->router->class);
        $method = strtolower((string) $CI->router->method);
        $routeKey = $controller . '/' . $method;

        $except = isset($rule['except']) && is_array($rule['except']) ? $rule['except'] : array();

        if (in_array($routeKey, $except, TRUE)) {
            return;
        }

        $header = $this->getAuthorizationHeader($CI);
        $token = $this->extractBearerToken($header);

        if ($token === NULL) {
            json_error('Unauthorized.', 401);
            $CI->output->_display();
            exit;
        }

        if (!class_exists('Api_token_service', FALSE)) {
            require_once APPPATH . 'services/Api_token_service.php';
        }

        $tokenService = new Api_token_service();
        $user = $tokenService->authenticateBearer($token);

        if (!$user) {
            json_error('Unauthorized.', 401);
            $CI->output->_display();
            exit;
        }

        $CI->auth->setApiUser($user);
    }

    protected function getAuthorizationHeader($CI)
    {
        $header = $CI->input->get_request_header('Authorization', TRUE);

        if (!empty($header)) {
            return $header;
        }

        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return $_SERVER['HTTP_AUTHORIZATION'];
        }

        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            return $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        return '';
    }

    protected function extractBearerToken($header)
    {
        if (!is_string($header) || $header === '') {
            return NULL;
        }

        if (preg_match('/^Bearer\s+(\S+)$/i', trim($header), $matches)) {
            return $matches[1];
        }

        return NULL;
    }
}
