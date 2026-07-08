<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_service
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('User_model');
        $this->CI->load->file(APPPATH . 'services/Auth_service.php', TRUE);
        $this->authService = new Auth_service();
    }

    public function listUsers(array $filters = array(), int $page = 1, int $perPage = 10)
    {
        $page = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $offset = ($page - 1) * $perPage;
        $total = $this->CI->User_model->countFiltered($filters);

        return array(
            'users' => $this->CI->User_model->paginate($filters, $perPage, $offset),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int) ceil($total / $perPage),
        );
    }

    public function getUserOrFail(int $id)
    {
        $user = $this->CI->User_model->findById($id);

        if (!$user) {
            show_404();
        }

        return $user;
    }

    public function getRoles()
    {
        return $this->CI->User_model->getAllRoles();
    }

    public function createUser(array $input)
    {
        $validation = $this->validateUserInput($input);

        if (!$validation['success']) {
            return $validation;
        }

        $role = $this->CI->User_model->getRoleByName($input['role']);

        if (!$role) {
            return array('success' => FALSE, 'message' => 'Invalid role selected.');
        }

        $now = date('Y-m-d H:i:s');

        $userId = $this->CI->User_model->create(array(
            'role_id' => $role->id,
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $this->authService->hashPassword($input['password']),
            'status' => $input['status'],
            'created_at' => $now,
            'updated_at' => $now,
        ));

        return array(
            'success' => TRUE,
            'user_id' => $userId,
            'message' => 'User created successfully.',
        );
    }

    public function updateUser(int $id, array $input)
    {
        $user = $this->getUserOrFail($id);
        $validation = $this->validateUserInput($input, (int) $user->id, FALSE);

        if (!$validation['success']) {
            return $validation;
        }

        $role = $this->CI->User_model->getRoleByName($input['role']);

        if (!$role) {
            return array('success' => FALSE, 'message' => 'Invalid role selected.');
        }

        $data = array(
            'role_id' => $role->id,
            'name' => $input['name'],
            'email' => $input['email'],
            'status' => $input['status'],
            'updated_at' => date('Y-m-d H:i:s'),
        );

        if (!empty($input['password'])) {
            $data['password'] = $this->authService->hashPassword($input['password']);
        }

        $this->CI->User_model->update($user->id, $data);

        return array(
            'success' => TRUE,
            'message' => 'User updated successfully.',
        );
    }

    public function deleteUser(int $id)
    {
        $user = $this->getUserOrFail($id);

        if ((int) $this->CI->auth->id() === (int) $user->id) {
            return array(
                'success' => FALSE,
                'message' => 'You cannot delete your own account.',
            );
        }

        $this->CI->User_model->softDelete($user->id);

        return array(
            'success' => TRUE,
            'message' => 'User archived successfully.',
        );
    }

    public function restoreUser(int $id)
    {
        $user = $this->CI->User_model->findByIdWithTrashed($id);

        if (!$user || empty($user->deleted_at)) {
            return array(
                'success' => FALSE,
                'message' => 'User is not archived or does not exist.',
            );
        }

        $this->CI->User_model->restore($user->id);

        return array(
            'success' => TRUE,
            'message' => 'User restored successfully.',
        );
    }

    protected function validateUserInput(array $input, ?int $excludeId = NULL, bool $passwordRequired = TRUE)
    {
        if (empty($input['name']) || strlen($input['name']) < 2) {
            return array('success' => FALSE, 'message' => 'Name is required.');
        }

        if (empty($input['email']) || !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            return array('success' => FALSE, 'message' => 'A valid email is required.');
        }

        if ($this->CI->User_model->emailExists($input['email'], $excludeId)) {
            return array('success' => FALSE, 'message' => 'Email is already in use.');
        }

        if ($passwordRequired && empty($input['password'])) {
            return array('success' => FALSE, 'message' => 'Password is required.');
        }

        if (!empty($input['password']) && strlen($input['password']) < 8) {
            return array('success' => FALSE, 'message' => 'Password must be at least 8 characters.');
        }

        if (!in_array($input['status'], array('active', 'inactive'), TRUE)) {
            return array('success' => FALSE, 'message' => 'Invalid status selected.');
        }

        if (!in_array($input['role'], array('admin', 'customer'), TRUE)) {
            return array('success' => FALSE, 'message' => 'Invalid role selected.');
        }

        return array('success' => TRUE);
    }
}