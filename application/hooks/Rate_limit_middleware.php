<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simple IP rate limit for sensitive routes (login / API login).
 * Runs on post_controller_constructor, same lifecycle as Auth_middleware.
 */
class Rate_limit_middleware
{
    public function run()
    {
        $CI =& get_instance();
        $CI->config->load('rate_limit', TRUE);

        if (!$CI->config->item('rate_limit_enabled', 'rate_limit')) {
            return;
        }

        $limits = $CI->config->item('rate_limits', 'rate_limit');
        if (!is_array($limits) || empty($limits)) {
            return;
        }

        $directory = strtolower((string) $CI->router->directory);
        $controller = strtolower((string) $CI->router->class);
        $method = strtolower((string) $CI->router->method);
        $routeKey = $directory . $controller . '/' . $method;

        if (!isset($limits[$routeKey])) {
            return;
        }

        // Only throttle state-changing login attempts
        if (strtoupper($CI->input->method(TRUE)) !== 'POST') {
            return;
        }

        $rule = $limits[$routeKey];
        $max = isset($rule['max']) ? (int) $rule['max'] : 5;
        $window = isset($rule['window']) ? (int) $rule['window'] : 60;

        if (!class_exists('Rate_limit_service', FALSE)) {
            require_once APPPATH . 'services/Rate_limit_service.php';
        }

        $service = new Rate_limit_service();
        $ip = (string) $CI->input->ip_address();
        $result = $service->attempt($routeKey, $ip, $max, $window);

        if (!empty($result['allowed'])) {
            return;
        }

        $retryAfter = (int) $result['retry_after'];

        // API → JSON 429
        if (strpos($directory, 'api/') === 0) {
            $CI->load->helper('api');
            $CI->output
                ->set_header('Retry-After: ' . $retryAfter)
                ->set_status_header(429)
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'success' => FALSE,
                    'message' => 'Too many attempts. Please try again in ' . $retryAfter . ' seconds.',
                )));
            // Stop the controller from running
            echo $CI->output->get_output();
            exit;
        }
        
        // Web login → flash + redirect
        $CI->load->library('session');
        $CI->session->set_flashdata(
            'error',
            'Too many login attempts. Please wait ' . $retryAfter . ' seconds and try again.'
        );
        redirect('login');
    }
}