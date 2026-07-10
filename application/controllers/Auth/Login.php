<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('auth');
        $this->load->helper('form');

        $this->load->model('User_model');

        $this->load->file(APPPATH . 'services/Auth_service.php', true);
        $this->authService = new Auth_service();
    }

    /**
     * Display login page.
     */
    public function index()
    {
        if ($this->auth->check()) {
            return $this->redirect_by_role();
        }

        $this->render_auth('auth/login', ['title' => 'Login']);
    }

    /**
     * Handle login request.
     */
    public function authenticate()
    {
        $request = $this->makeRequest('Login_request');

        if (!$request->validate()) {
            return $this->redirect_with_validation_errors('login');
        }

        $result = $this->authService->attempt(
            $this->input->post('email', TRUE),
            $this->input->post('password')
        );

        if (!$result['success']) {
            $this->session->set_flashdata('error', $result['message']);
            return redirect('login');
        }

        if ($this->auth->isAdmin()) {
            return redirect('admin/dashboard');
        }

        return redirect('');
    }

    /**
     * Logout.
     */
    public function logout()
    {
        $this->authService->logout();

        redirect('login');
    }
}
