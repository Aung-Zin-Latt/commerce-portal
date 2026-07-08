<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipts extends MY_Controller
{
    public function index()
    {
        $this->render('admin/placeholder', array(
            'title' => 'Receipts',
            'page_heading' => 'Receipts',
            'page_description' => 'Admin receipts page is ready for implementation.',
            'breadcrumbs' => array(
                'Home' => '',
                'Receipts' => NULL,
            ),
        ));
    }
}
