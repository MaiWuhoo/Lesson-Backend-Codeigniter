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

class Customer extends RestController {
    
    function __construct(){
        parent::__construct();
        $this->load->model('Customer/Customer_model');
    }

    // GET ALL
    function index_get(){
        $customer_list = $this->Customer_model->customer_list();
        
        $this->response([
            'Status' => true,
            'Response' => "Customer list found!",
            'Customer' => $customer_list,
        ], RestController::HTTP_OK);
    }

    // GET BY ID
    function detail_get(){
        $customerNumber = $this->get('customerNumber');
        
        if(!$customerNumber){
            $this->response([
                'Status' => false,
                'Response' => "Customer Number is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }
        
        $customer = $this->Customer_model->customer_by_id($customerNumber);
        
        if($customer){
            $this->response([
                'Status' => true,
                'Response' => "Customer found!",
                'Customer' => $customer
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'Status' => false,
                'Response' => "Customer not found"
            ], RestController::HTTP_NOT_FOUND);
        }
    }

    // CREATE
    function index_post(){
        $data = [
        
            'customerName' => $this->post('customerName'),
            'contactLastName' => $this->post('contactLastName'),
            'contactFirstName' => $this->post('contactFirstName'),
            'phone' => $this->post('phone'),
            'addressLine1' => $this->post('addressLine1'),
            'addressLine2' => $this->post('addressLine2'),
            'city' => $this->post('city'),
            'state' => $this->post('state'),
            'postalCode' => $this->post('postalCode'),
            'country' => $this->post('country'),
            'salesRepEmployeeNumber' => $this->post('salesRepEmployeeNumber'),
            'creditLimit' => $this->post('creditLimit')
        ];
        
         if(empty($data['customerName'])){
            $this->response([
                'Status' => false,
                'Response' => "Customer Name are required",
                'customerNumber' =>$insert_id
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }
        
        $insert_id = $this->Customer_model->customer_create($data);
        
        if($insert_id){
            $this->response([
                'Status' => true,
                'Response' => "Customer created successfully!",
                'CustomerNumber' => $insert_id
            ], RestController::HTTP_CREATED);
        } else {
            $this->response([
                'Status' => false,
                'Response' => "Failed to create customer"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }
}