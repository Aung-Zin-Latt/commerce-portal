<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends MY_Controller
{
    public function index()
    {
        $this->render('admin/placeholder', array(
            'title' => 'Products',
            'page_heading' => 'Products',
            'page_description' => 'Admin products page is ready for implementation.',
            'breadcrumbs' => array(
                'Home' => '',
                'Products' => NULL,
            ),
        ));
    }
}
