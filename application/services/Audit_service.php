<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_service
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Audit_log_model');
    }

    // Write audit log entry
    // e.g $action = payment.completed, $entityType = payment, $entityId = 123, $userId = 456
    public function log(string $action, string $entityType, int $entityId, int $userId = NULL)
    {
        $now = date('Y-m-d H:i:s');

        return $this->CI->Audit_log_model->create(array(
            'user_id' => $userId !== NULL ? (int) $userId : NULL,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => (int) $entityId,
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => substr((string) $this->CI->input->user_agent(), 0, 1000),
            'created_at' => $now,
        ));
    }

    public function listAllLogs()
    {
        return $this->CI->Audit_log_model->getAllWithUser();
    }
}