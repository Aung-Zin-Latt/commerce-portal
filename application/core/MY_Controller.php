<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function render($content, $data = array())
    {
        $data['content'] = $content;
        $this->load->view('layouts/app_layout', $data);
    }

    protected function render_store($content, $data = array())
    {
        $cartService = $this->loadService('Cart_service');

        $data['cart_item_count'] = $cartService->getItemCount();
        $data['content'] = $content;
        $this->load->view('layouts/store_layout', $data);
    }

    /**
     * Load a service class once per request.
     *
     * CI's load->file() always re-includes the file, which causes
     * "Cannot declare class ... already in use" fatals when multiple
     * services depend on each other.
     *
     * @param string $service
     * @return object
     */
    protected function loadService($service)
    {
        if (!class_exists($service, false)) {
            require_once APPPATH . 'services/' . $service . '.php';
        }

        return new $service();
    }

    protected function render_auth($content, $data = array())
    {
        $data['content'] = $content;
        $this->load->view('layouts/auth_layout', $data);
    }

    /**
     * Ensure the current user is logged in.
     * Route-level auth is normally handled by Auth_middleware.
     */
    protected function require_auth()
    {
        $this->load->library('auth');

        if (!$this->auth->check()) {
            $this->session->set_flashdata('error', 'Please log in first.');
            redirect('login');
        }
    }

    /**
     * Ensure the current user is an admin.
     * Prefer central middleware rules in application/config/auth.php.
     */
    protected function require_admin()
    {
        $this->require_auth();

        if (!$this->auth->isAdmin()) {
            $this->session->set_flashdata('error', 'You do not have permission to access the admin panel.');
            redirect('');
        }
    }

    /**
     * Ensure the current user is a customer.
     * Prefer central middleware rules in application/config/auth.php.
     */
    protected function require_customer()
    {
        $this->require_auth();

        if (!$this->auth->isCustomer()) {
            $this->session->set_flashdata('error', 'You do not have permission to access that page.');
            redirect('admin/dashboard');
        }
    }

    /**
     * Ownership authorization.
     *
     * Use this after loading a resource to ensure it belongs to the
     * currently authenticated user. Admins may access any resource.
     *
     * @param int $resourceUserId
     * @return void
     */
    protected function require_owner($resourceUserId)
    {
        $this->load->library('auth');

        if ($this->auth->isAdmin()) {
            return;
        }

        if ((int) $resourceUserId !== (int) $this->auth->id()) {
            $this->deny_resource_access();
        }
    }

    /**
     * Return 404 for unauthorized or missing resources.
     *
     * We intentionally do not reveal whether a record exists when the
     * current user does not own it.
     */
    protected function deny_resource_access()
    {
        show_404();
    }

    protected function redirect_by_role()
    {
        $this->load->library('auth');

        if (!$this->auth->check()) {
            redirect('login');
        }

        if ($this->auth->isAdmin()) {
            redirect('admin/dashboard');
        }

        if ($this->auth->isCustomer()) {
            redirect('');
        }

        $this->session->set_flashdata('error', 'Your account role is not allowed.');
        redirect('login');
    }

    protected function redirect_with_validation_errors($redirectUrl)
    {
        $this->session->set_flashdata('field_errors', $this->form_validation->error_array());
        $this->session->set_flashdata('old_input', $this->input->post(NULL, TRUE) ?: array());

        return redirect($redirectUrl);
    }

    // Custom form request (Laravel-style wrapper around CI Form Validation)
    protected function makeRequest($class)
    {
        if (!class_exists('Form_request', FALSE)) {
            require_once APPPATH . 'requests/Form_request.php';
        }

        $file = APPPATH . 'requests/' . $class . '.php';

        if (!class_exists($class, FALSE)) {
            require_once $file;
        }

        return new $class();
    }
}

/**
 * Base controller for JSON API endpoints.
 *
 * Defined in this file because CI3 only auto-loads MY_Controller.php
 * before parsing application controllers.
 */
class MY_Api_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('api');
        $this->load->library('auth');
    }

    /**
     * Load a service class once per request.
     *
     * @param string $service
     * @return object
     */
    protected function loadService($service)
    {
        if (!class_exists($service, FALSE)) {
            require_once APPPATH . 'services/' . $service . '.php';
        }

        return new $service();
    }

    /**
     * Load a Form Request class under application/requests/.
     *
     * @param string $class
     * @return object
     */
    protected function makeRequest($class)
    {
        if (!class_exists('Form_request', FALSE)) {
            require_once APPPATH . 'requests/Form_request.php';
        }

        $file = APPPATH . 'requests/' . $class . '.php';

        if (!class_exists($class, FALSE)) {
            require_once $file;
        }

        return new $class();
    }
}
