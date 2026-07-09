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
        $logs = $this->auditService->listAllLogs();

        $this->render('admin/audit_logs/index', array(
            'title' => 'Audit Logs',
            'logs' => $logs,
            'breadcrumbs' => array(
                'Home' => 'admin/dashboard',
                'Audit Logs' => NULL,
            ),
        ));
    }
}
