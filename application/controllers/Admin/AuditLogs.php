<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuditLogs extends MY_Controller
{
    /** @var Audit_service */
    protected $auditService;

    public function __construct()
    {
        parent::__construct();
        $this->auditService = $this->loadService('Audit_service');
    }

    public function index()
    {
        $page = (int) $this->input->get('page');
        $result = $this->auditService->listAllLogs($page ?: 1, 10);

        $this->render('admin/audit_logs/index', array(
            'title' => 'Audit Logs',
            'logs' => $result['logs'],
            'pagination' => $result,
            'breadcrumbs' => array(
                'Home' => 'admin/dashboard',
                'Audit Logs' => NULL,
            ),
        ));
    }
}
