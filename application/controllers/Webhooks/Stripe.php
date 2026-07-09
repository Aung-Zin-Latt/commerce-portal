<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stripe extends CI_Controller
{
    public function index()
    {
        $payload = file_get_contents('php://input');
        $signature = $this->input->get_request_header('Stripe-Signature', TRUE);

        if (!class_exists('Payment_service', FALSE)) {
            require_once APPPATH . 'services/Payment_service.php';
        }

        $paymentService = new Payment_service();
        $result = $paymentService->handleWebhook($payload, $signature);

        $statusCode = $result['success'] ? 200 : 400;

        $this->output
            ->set_status_header($statusCode)
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }
}