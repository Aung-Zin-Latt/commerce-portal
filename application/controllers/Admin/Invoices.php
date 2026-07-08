<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends MY_Controller
{
    public function index()
    {
        $this->render('admin/placeholder', array(
            'title' => 'Invoices',
            'page_heading' => 'Invoices',
            'page_description' => 'Admin invoices page is ready for implementation.',
            'breadcrumbs' => array(
                'Home' => '',
                'Invoices' => NULL,
            ),
        ));
    }
}
