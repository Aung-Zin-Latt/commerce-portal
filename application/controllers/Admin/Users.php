<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller
{
    /** @var User_service */
    protected $userService;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('auth');
        $this->load->file(APPPATH . 'services/User_service.php', TRUE);
        $this->userService = new User_service();
    }
    public function index()
    {
        $filters = array(
            'search' => $this->input->get('search', TRUE),
            'role' => $this->input->get('role', TRUE),
            'status' => $this->input->get('status', TRUE),
            'include_deleted' => $this->input->get('include_deleted') ? '1' : '',
        );
        $page = (int) $this->input->get('page');
        $result = $this->userService->listUsers($filters, $page ?: 1, 10);
        $this->render('admin/users/index', array(
            'title' => 'Users',
            'page_heading' => 'Users',
            'users' => $result['users'],
            'filters' => $filters,
            'pagination' => $result,
            'breadcrumbs' => array(
                'Dashboard' => 'admin/dashboard',
                'Users' => NULL,
            ),
        ));
    }
    public function create()
    {
        $this->render('admin/users/form', array(
            'title' => 'Create User',
            'page_heading' => 'Create User',
            'form_action' => 'admin/users/store',
            'user' => NULL,
            'roles' => $this->userService->getRoles(),
            'breadcrumbs' => array(
                'Dashboard' => 'admin/dashboard',
                'Users' => 'admin/users',
                'Create' => NULL,
            ),
        ));
    }
    public function store()
    {
        $result = $this->userService->createUser(array(
            'name' => $this->input->post('name', TRUE),
            'email' => $this->input->post('email', TRUE),
            'password' => $this->input->post('password'),
            'role' => $this->input->post('role', TRUE),
            'status' => $this->input->post('status', TRUE),
        ));
        if (!$result['success']) {
            $this->session->set_flashdata('error', $result['message']);
            return redirect('admin/users/create');
        }
        $this->session->set_flashdata('success', $result['message']);
        redirect('admin/users');
    }
    public function edit($id)
    {
        $user = $this->userService->getUserOrFail($id);
        $this->render('admin/users/form', array(
            'title' => 'Edit User',
            'page_heading' => 'Edit User',
            'form_action' => 'admin/users/update/' . $user->id,
            'user' => $user,
            'roles' => $this->userService->getRoles(),
            'breadcrumbs' => array(
                'Dashboard' => 'admin/dashboard',
                'Users' => 'admin/users',
                'Edit' => NULL,
            ),
        ));
    }
    public function update($id)
    {
        $result = $this->userService->updateUser($id, array(
            'name' => $this->input->post('name', TRUE),
            'email' => $this->input->post('email', TRUE),
            'password' => $this->input->post('password'),
            'role' => $this->input->post('role', TRUE),
            'status' => $this->input->post('status', TRUE),
        ));
        if (!$result['success']) {
            $this->session->set_flashdata('error', $result['message']);
            return redirect('admin/users/edit/' . $id);
        }
        $this->session->set_flashdata('success', $result['message']);
        redirect('admin/users');
    }
    public function delete($id)
    {
        $result = $this->userService->deleteUser($id);
        $this->session->set_flashdata(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
        redirect('admin/users');
    }

    public function restore($id)
    {
        $result = $this->userService->restoreUser($id);

        $this->session->set_flashdata(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );

        $redirectUrl = 'admin/users';

        if ($this->input->get('include_deleted')) {
            $redirectUrl .= '?include_deleted=1';
        }

        redirect($redirectUrl);
    }
}
