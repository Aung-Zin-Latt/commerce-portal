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

        $expectedRole = $rule['role'];
        $actualRole = $CI->auth->role();

        if ($actualRole !== $expectedRole) {
            $CI->session->set_flashdata('error', $rule['denied_message']);
            redirect($rule['denied_redirect']);
        }
    }
}
