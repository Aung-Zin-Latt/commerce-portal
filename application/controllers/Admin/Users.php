<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller
{
    public function index()
    {
        $this->render('admin/placeholder', array(
            'title' => 'Users',
            'page_heading' => 'Users',
            'page_description' => 'Admin users page is ready for implementation.',
            'breadcrumbs' => array(
                'Home' => '',
                'Users' => NULL,
            ),
        ));
    }
}
