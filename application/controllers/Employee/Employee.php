<?php
defined ('BASEPATH') OR exit ('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST,PUT, DELETE,OPTIONS");
header("Access-Control-Allow-Headers:Content-Type");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

Class Employee extends RestController {
    function __construct()
    {
        parent::__construct();
        $this->load->model('Employee/Employee_model');
    }


    //GET ALL LIST

    function all_get (){
        $employee_list =$this ->Employee_model ->employee_list();

        $this -> response ([
            'Status' => true,
            'Response' => "Employee list found!",
            'Employee' => $employee_list,
        ], RestController::HTTP_OK);
    }


    //GET BY ID
    function detail_get(){
        $employeeNumber = $this ->get('employeeNumber');

        if(!$employeeNumber){
            $this -> response([
                'Status'=>false,
                'Response' =>"Employee Number is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }
        
        $employee = $this->Employee_model->employee_by_id($employeeNumber);

        if($employee){
            $this->response([
                'Status' => true,
                'Response' =>"Employee Found!",
                'Customer' =>$employee
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'Status' =>false,
                'Response'=>"Employee Not Found"
            ], RestController::Http_NOT_FOUND);
        }
    }

    //POST NEW EMPLOYEE
    function index_post(){
        $data = [
            
            'lastName' =>$this->post('lastName'),
            'firstName' =>$this->post('firstName'),
            'extension' =>$this->post('extension'),
            'email' =>$this->post('email'),
            'officeCode' =>$this->post('officeCode'),
            'reportsTo' =>$this->post('reportsTo'),
            'jobTitle' =>$this->post('jobTitle'),
        ];

        if (empty($data['lastName'])){
            $this->response([
                'Status'=>false,
                'Response'=>"Last Name are required",
                'employeeNumber' =>$insert_id
            ],RestController::HTTP_BAD_REQUEST);
            return;
        }

        $insert_id = $this->Employee_model->employee_create($data);

        if($insert_id){
            $this->response([
                'Status'=>true,
                'Response'=> "Employee created successfully!",
                'EmployeeNumber'=>$insert_id
            ], RestController::HTTP_CREATED);
        } else{
            $this->response([
                'Status'=>false,
                'Response'=>"Failed to create employee"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }

    //PUT (Full Update)
    function index_put(){
        $employeeNumber = $this->put('employeeNumber');

        if(!$employeeNumber){
            $this->response([
                'Status'=>false,
                'Response' =>"Employee Number is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $existing_employee = $this->Employee_model -> employee_by_id($employeeNumber);
        if(!$existing_employee){
            $this->response([
                'Status' => false,
                "Response" =>"Employee not found"
            ], RestController::HTTP_NOT_FOUND);
            return;
        }

        if(!$this->put('lastName') || 
        !$this-> put('firstName') || !$this->put('extension') || 
        !$this-> put('email') || !$this->put('officeCode') ||
        !$this->put('reportsTo') || !$this->put('jobTitle')){
            $this -> response ([
                'Status' => false,
                'Response' => "All required fields must be provided for full update"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $data = [
            
            'lastName'=> $this->put('lastName'),
            'firstName' => $this -> put('firstName'),
            'extension' => $this->put('extension'),
            'email' => $this->put('email'),
            'officeCode' =>$this->put('officeCode'),
            'reportsTo'=>$this->put('reportsTo'),
            'jobTitle'=>$this->put('jobTitle')
        ];

        $update_result = $this->Employee_model->employee_update($employeeNumber, $data);

        if($update_result){
            $this->response([
                'Status'=>true,
                'Response' => "Employee replaced successfully!",
                'EmployeeNumber' => $employeeNumber
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'Status' => false,
                'Response' =>"Failed to update employee"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }

    //PATCH (Partial Update)
    function index_patch(){
        $employeeNumber = $this->patch('employeeNumber');

        if(!$employeeNumber){
            $this -> response([
                'Status' => false,
                'Response' => "Employee Number is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $existing_employee = $this->Employee_model ->employee_by_id($employeeNumber);
        if(!$existing_employee){
            $this -> response([
                'Status' => false,
                'Response' => "Employee not found"
            ], RestController :: HTTP_NOT_FOUND);
            return;  
        }
        
        $data =[];

        if($this->patch('lastName') !== null) $data['lastName']= $this->patch('lastName');
        if($this->patch('firstName') !== null) $data['firstName']= $this->patch('firstName');
        if($this->patch('extension') !== null) $data['extension']= $this->patch('extension');
        if($this->patch('email') !== null) $data['email']= $this->patch('email');
        if($this->patch('officeCode') !== null) $data['officeCode']= $this->patch('officeCode');
        if($this->patch('reportsTo') !== null) $data['reportsTo']= $this->patch('reportsTo');
        if($this->patch('jobTitle') !== null) $data['jobTitle']= $this->patch('jobTitle');

        if(empty($data)){
            $this -> response([
                'Status' => false,
                'Response' => "No data provided for update"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $update_result = $this->Employee_model->employee_update($employeeNumber,$data);

       if($update_result){
            $this->response([
                'Status' => true,
                'Response' => "Employee updated successfully!",
                'EmployeeNumber' => $employeeNumber
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'Status' => false,
                'Response' => "Failed to update employee or no changes made"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }

    // DELETE - Remove employee
function index_delete(){
    $employeeNumber = $this->input->request_headers()['employeeNumber'];
    
    // Validate employeeNumber
    if(!$employeeNumber){
        $this->response([
            'Status' => false,
            'Response' => "Employee Number is required"
        ], RestController::HTTP_BAD_REQUEST);
        return;
    }
    
    // Check if employee exists
    $existing_employee = $this->Employee_model->employee_by_id($employeeNumber);
    if(!$existing_employee){
        $this->response([
            'Status' => false,
            'Response' => "Employee not found"
        ], RestController::HTTP_NOT_FOUND);
        return;
    }
    
    // Delete employee
    $delete_result = $this->Employee_model->employee_delete($employeeNumber);
    
    if($delete_result){
        $this->response([
            'Status' => true,
            'Response' => "Employee deleted successfully!",
            'EmployeeNumber' => $employeeNumber
        ], RestController::HTTP_OK);
    } else {
        $this->response([
            'Status' => false,
            'Response' => "Failed to delete employee"
        ], RestController::HTTP_INTERNAL_ERROR);
    }
}
}