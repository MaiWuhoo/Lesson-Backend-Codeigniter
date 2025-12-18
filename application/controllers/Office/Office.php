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

Class Office extends RestController {
    function __construct()
    {
        parent::__construct();
        $this->load->model('Office/Office_model');
    }


    //GET ALL

    function all_get (){
        $office_list =$this ->Office_model ->office_list();

        $this -> response([
            'Status' => true,
            'Response' => "Office list found!",
            "Office" => $office_list,
        ], RestController::HTTP_OK);
    }


    //GET BY ID
    function detail_get(){
        $officeCode = $this ->get('officeCode');

        if(!$officeCode){
            $this->response([
                'Status' =>false,
                'Response'=>"Office Code is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $office =$this->Office_model->office_by_id($officeCode);

        if($office){
            $this->response([
                'Status'=> true,
                'Response' =>"Office Found!",
                'Office' =>$office
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'Status' => false,
                'Response'=>"Office not found"
            ], RestController::HTTP_NOT_FOUND);
        }
    }

    //CREATE
    function index_post(){
        $data =[
            
            'city' => $this->post('city'),
            'phone' => $this->post('phone'),
            'addressLine1' => $this->post('addressLine1'),
            'addressLine2' => $this->post('addressLine2'),
            'state' => $this->post('state'),
            'country' => $this->post('country'),
            'postalCode' => $this->post('postalCode'),
            'territory' => $this->post('territory'),
            
        ];
        
        if(empty($data['city'])){
            $this->response([
                'Status' =>false,
                'Response' =>"City are required",
                'officeCode' =>$insert_id
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $insert_id = $this->Office_model->office_create($data);

        if($insert_id){
            $this->response([
                'Status' => true,
                'Response' =>"Office created successfully!",
                'OfficeCode' =>$insert_id
            ], RestController::HTTP_CREATED);
        } else {
            $this->response([
                'Status' => false,
                'Response' => "Failed to create Office"
            ], RestController::HTTP_INTERNAL_ERROR);
                }
    }

    //PUT (Full Update)
    function index_put(){
        $officeCode = $this -> put ('officeCode');

        if(!$officeCode){
            $this ->response([
                'Status' => false,
                'Response' => "Office Code is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $existing_office = $this->Office_model -> office_by_id($officeCode);
        if(!$existing_office){
            $this->response([
                'Status'=> false,
                'Response' => "Office not found"
            ], RestController::HTTP_NOT_FOUND);
            return;
        }

        if(!$this->put('city') || !$this->put('phone') || !$this -> put('addressLine1') ||
        !$this -> put ('addressLine2') || !$this -> put('state') || !$this -> put('country') ||
        !$this -> put('postalCode') || !$this ->put('territory')) {
            $this -> response([
            'Status' => false,
            'Response' => "All required fields must be provided for full update"
            ], RestController::HTTP_BAD_REQUEST); 
            return;
    }

    $data = [
        'city' => $this -> put('city'),
        'phone' => $this -> put ('phone'),
        'addressLine1' => $this -> put ('addressLine1'),
        'addressLine2' => $this -> put ('addressLine2'),
        'state' => $this -> put ('state'),
        'country' => $this -> put ('country'),
        'postalCode' => $this -> put ('postalCode'),
        'territory' => $this -> put ('territory')
    ];

    $update_result = $this -> Office_model -> office_update ($officeCode , $data);

    if($update_result){
            $this->response([
                'Status' => true,
                'Response' => "Office replaced successfully!",
                'OfficeCode' => $officeCode
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'Status' => false,
                'Response' => "Failed to update Office"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }

    //PATCH (Partial Update)
    function index_patch(){
        $officeCode = $this->patch('officeCode');

        if(!$officeCode){
            $this -> response ([
                'Status' => false,
                'Response' => "Office Code is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $existing_office = $this -> Office_model -> office_by_id($officeCode);
        if(!$existing_office){
            $this -> response([
                'Status' => false,
                'Response' => "Office not found"
            ], RestController::HTTP_NOT_FOUND);
            return;
        }

        $data = [] ;

        if($this ->patch('city')!== null) $data['city'] = $this -> put('city');
        if($this ->patch('phone')!== null) $data['phone'] = $this -> put ('phone');
        if($this ->patch('addressLine1')!== null) $data['addressLine1']= $this -> put ('addressLine1');
        if($this ->patch('addressLine2')!== null) $data['addressLine2'] =$this -> put ('addressLine2');
        if($this ->patch('state')!== null) $data['state'] = $this -> put ('state');
        if($this ->patch('country')!== null) $data['country'] = $this -> put ('country');
        if($this ->patch('postalCode')!== null) $data['postalCode'] = $this -> put ('postalCode');
        if($this ->patch('territory')!== null) $data['territory'] = $this -> put ('territory');
        
        if(empty($data)){
            $this -> response([
                'Status' => false,
                'Response' => "No data provided for update"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $update_result = $this->Office_model -> office_update($officeCode, $data);

        if($update_result){
            $this->response([
                'Status' => true,
                'Response' => "Office updated successfully!",
                'OfficeCode' => $officeCode
            ], RestController:: HTTP_OK);
        }else {
            $this -> response([
                'Status' => false,
                'Response' => "Failed to update customer or no changes made"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }

    //DELETE
    function detail_delete() {
        $officeCode = $this->input->request_headers()['officeCode'];

        if(!$officeCode){
            $this -> response([
                'Status' => false,
                'Response' => "Office Code is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $existing_office = $this->Office_model -> office_by_id($officeCode);
        if(!$existing_office){
            $this->response([
                'Status' => false,
                'Response' => "Office not found"
            ], RestController::HTTP_NOT_FOUND);
            return;
        }

        $delete_result = $this->Office_model -> office_delete($officeCode);

        if($delete_result){
            $this->response([
                'Status' => true,
                'Response' => "Office deleted successfully!",
                'OfficeCode' => $officeCode
            ], RestController::HTTP_OK);
        } else {
            $this -> response([
                'Status'=> false,
                'Response' => "Failed to delete office"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }

}