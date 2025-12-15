<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Customer_test extends RestController {
    
    function __construct(){
        parent::__construct();
        $this->load->model('Customer/Customer_model');
    }

    // Test GET
    function index_get(){
        $this->response([
            'Status' => true,
            'Message' => 'GET working!'
        ], RestController::HTTP_OK);
    }

    // Test POST dengan _post suffix
    function index_post(){
        $this->response([
            'Status' => true,
            'Message' => 'POST working!',
            'Data' => $this->post()
        ], RestController::HTTP_OK);
    }
}