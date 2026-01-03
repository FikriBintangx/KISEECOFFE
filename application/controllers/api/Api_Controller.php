<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Allow CORS untuk akses dari mobile/cross-origin
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-API-KEY");
    }

    protected function response($data, $status = 200)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($status)
            ->set_output(json_encode($data));
    }

    protected function json_input()
    {
        // Untuk membaca JSON body dari request mobile app
        $input = json_decode(file_get_contents('php://input'), true);
        return $input ? $input : [];
    }
}
