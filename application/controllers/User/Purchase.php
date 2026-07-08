<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends MY_Controller
{
    public function index()
    {
        $this->render('user/placeholder', array(
            'title' => 'Purchase',
            'page_heading' => 'Purchase',
            'page_description' => 'User purchase page is ready for implementation.',
            'breadcrumbs' => array(
                'Home' => '',
                'Purchase' => NULL,
            ),
        ));
    }
}
