<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function json_success($data = NULL, $message = 'OK', $status = 200)
{
    $CI =& get_instance();
    $CI->output
        ->set_status_header($status)
        ->set_content_type('application/json')
        ->set_output(json_encode(array(
            'success' => TRUE,
            'message' => $message,
            'data'    => $data,
        )));
}

function json_error($message = 'Error', $status = 400, $errors = NULL)
{
    $CI =& get_instance();
    $payload = array(
        'success' => FALSE,
        'message' => $message,
    );

    if ($errors !== NULL) {
        $payload['errors'] = $errors;
    }

    $CI->output
        ->set_status_header($status)
        ->set_content_type('application/json')
        ->set_output(json_encode($payload));
}