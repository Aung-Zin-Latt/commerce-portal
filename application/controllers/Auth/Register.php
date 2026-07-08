<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends MY_Controller
{
    protected $authService;

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

    public function index()
    {
        if ($this->auth->check()) {
            return $this->redirectByRole();
        }

        $this->render_auth('auth/register', array(
            'title' => 'Register',
        ));
    }

    public function store()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[150]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() === FALSE) {
            return $this->redirect_with_validation_errors('register');
        }

        $role = $this->User_model->getRoleByName('customer');

        if (!$role) {
            $this->session->set_flashdata('error', 'Customer role is missing. Please run the role migration/seed first.');
            return redirect('register');
        }

        $now = date('Y-m-d H:i:s');
        $userId = $this->User_model->create(array(
            'role_id' => $role->id,
            'name' => $this->input->post('name', TRUE),
            'email' => $this->input->post('email', TRUE),
            'password' => $this->authService->hashPassword($this->input->post('password')),
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ));

        $user = $this->User_model->findById($userId);
        $this->auth->login($user);

        return redirect('user/products');
    }

    protected function redirectByRole()
    {
        if ($this->auth->isAdmin()) {
            return redirect('admin/dashboard');
        }

        return redirect('user/products');
    }
}
