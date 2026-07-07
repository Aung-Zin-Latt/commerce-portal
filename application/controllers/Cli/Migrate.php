<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // -----------------------------------------------------
        // Allow Migration Only From CLI
        // -----------------------------------------------------
        if (!$this->input->is_cli_request())
        {
            show_404();
        }

        $this->load->library('migration');
    }

    public function index()
    {
        echo PHP_EOL;
        echo "==========================================" . PHP_EOL;
        echo " Running Database Migrations" . PHP_EOL;
        echo "==========================================" . PHP_EOL;

        if ($this->migration->latest() === FALSE)
        {
            echo PHP_EOL;
            echo "[ERROR] " . $this->migration->error_string() . PHP_EOL;
            exit(1);
        }

        echo PHP_EOL;
        echo "[SUCCESS] Database migrated successfully." . PHP_EOL;
        echo PHP_EOL;
    }
}