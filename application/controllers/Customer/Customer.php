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

    // PUT (Full Update)
    function index_put(){
        $customerNumber = $this->put('customerNumber');
        
        // Validate customerNumber
        if(!$customerNumber){
            $this->response([
                'Status' => false,
                'Response' => "Customer Number is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }
        
        // Check if customer exists
        $existing_customer = $this->Customer_model->customer_by_id($customerNumber);
        if(!$existing_customer){
            $this->response([
                'Status' => false,
                'Response' => "Customer not found"
            ], RestController::HTTP_NOT_FOUND);
            return;
        }
        
        // Validate required fields for full update
        if(!$this->put('customerName') || !$this->put('contactLastName') || 
        !$this->put('contactFirstName') || !$this->put('phone') || 
        !$this->put('addressLine1') || !$this->put('city') || !$this->put('country')){
            $this->response([
                'Status' => false,
                'Response' => "All required fields must be provided for full update (customerName, contactLastName, contactFirstName, phone, addressLine1, city, country)"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }
        
        // Set ALL fields (replace entire resource)
        $data = [
            'customerName' => $this->put('customerName'),
            'contactLastName' => $this->put('contactLastName'),
            'contactFirstName' => $this->put('contactFirstName'),
            'phone' => $this->put('phone'),
            'addressLine1' => $this->put('addressLine1'),
            'addressLine2' => $this->put('addressLine2'), // boleh NULL
            'city' => $this->put('city'),
            'state' => $this->put('state'), // boleh NULL
            'postalCode' => $this->put('postalCode'), // boleh NULL
            'country' => $this->put('country'),
            'salesRepEmployeeNumber' => $this->put('salesRepEmployeeNumber'), // boleh NULL
            'creditLimit' => $this->put('creditLimit') // boleh NULL
        ];
        
        // Update customer
        $update_result = $this->Customer_model->customer_update($customerNumber, $data);
        
        if($update_result){
            $this->response([
                'Status' => true,
                'Response' => "Customer replaced successfully!",
                'CustomerNumber' => $customerNumber
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'Status' => false,
                'Response' => "Failed to update customer"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }

    //PATCH (Partial Update)
    function index_patch(){
        $customerNumber = $this->patch('customerNumber');
        
        // Validate customerNumber
        if(!$customerNumber){
            $this->response([
                'Status' => false,
                'Response' => "Customer Number is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }
        
        // Check if customer exists
        $existing_customer = $this->Customer_model->customer_by_id($customerNumber);
        if(!$existing_customer){
            $this->response([
                'Status' => false,
                'Response' => "Customer not found"
            ], RestController::HTTP_NOT_FOUND);
            return;
        }
        
        // Prepare data for partial update - only fields that are provided
        $data = [];
        
        if($this->patch('customerName') !== null) $data['customerName'] = $this->patch('customerName');
        if($this->patch('contactLastName') !== null) $data['contactLastName'] = $this->patch('contactLastName');
        if($this->patch('contactFirstName') !== null) $data['contactFirstName'] = $this->patch('contactFirstName');
        if($this->patch('phone') !== null) $data['phone'] = $this->patch('phone');
        if($this->patch('addressLine1') !== null) $data['addressLine1'] = $this->patch('addressLine1');
        if($this->patch('addressLine2') !== null) $data['addressLine2'] = $this->patch('addressLine2');
        if($this->patch('city') !== null) $data['city'] = $this->patch('city');
        if($this->patch('state') !== null) $data['state'] = $this->patch('state');
        if($this->patch('postalCode') !== null) $data['postalCode'] = $this->patch('postalCode');
        if($this->patch('country') !== null) $data['country'] = $this->patch('country');
        if($this->patch('salesRepEmployeeNumber') !== null) $data['salesRepEmployeeNumber'] = $this->patch('salesRepEmployeeNumber');
        if($this->patch('creditLimit') !== null) $data['creditLimit'] = $this->patch('creditLimit');
        
        // Check if there's data to update
        if(empty($data)){
            $this->response([
                'Status' => false,
                'Response' => "No data provided for update"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }
        
        // Update customer
        $update_result = $this->Customer_model->customer_update($customerNumber, $data);
        
        if($update_result){
            $this->response([
                'Status' => true,
                'Response' => "Customer updated successfully!",
                'CustomerNumber' => $customerNumber
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'Status' => false,
                'Response' => "Failed to update customer or no changes made"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }

        // DELETE - Remove employee
    function detail_delete(){
        // Guna $this->get() untuk query params
        //$customerNumber = $this->get('customer_number');
        $customerNumber = $this->input->request_headers()['customerNumber'];
        
        // Validate customerNumber
        if(!$customerNumber){
            $this->response([
                'Status' => false,
                'Response' => "Customer Number is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }
        
        // Check if customer exists
        $existing_customer = $this->Customer_model->customer_by_id($customerNumber);
        if(!$existing_customer){
            $this->response([
                'Status' => false,
                'Response' => "Customer not found"
            ], RestController::HTTP_NOT_FOUND);
            return;
        }
        
        // Delete customer
        $delete_result = $this->Customer_model->customer_delete($customerNumber);
        
        if($delete_result){
            $this->response([
                'Status' => true,
                'Response' => "Customer deleted successfully!",
                'CustomerNumber' => $customerNumber
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'Status' => false,
                'Response' => "Failed to delete customer"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }
}