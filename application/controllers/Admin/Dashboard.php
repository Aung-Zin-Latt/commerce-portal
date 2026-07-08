<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    /** @var Dashboard_service */
    protected $dashboardService;

    public function __construct()
    {
        parent::__construct();
        $this->load->file(APPPATH . 'services/Dashboard_service.php', TRUE);
        $this->dashboardService = new Dashboard_service();
    }

    public function index()
    {
        $summary = $this->dashboardService->getSummary();

        $this->render('admin/dashboard/index', array(
            'title' => 'Dashboard',
            'summary' => $summary,
            'breadcrumbs' => array(
                'Dashboard' => NULL,
            ),
        ));
    }
}