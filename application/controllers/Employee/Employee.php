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

}