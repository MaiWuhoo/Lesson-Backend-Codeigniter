<?php
defined ('BASEPATH') OR exit ('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST,PUT, DELETE,OPTIONS");
header("Access-Control-Allow-Headers:Content-Type");

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
            'officeCode' => $this->post('officeCode'),
            'city' => $this->post('city'),
            'phone' => $this->post('phone'),
            'addressLine1' => $this->post('addressLine1'),
            'addressLine2' => $this->post('addressLine2'),
            'state' => $this->post('state'),
            'country' => $this->post('country'),
            'postalCode' => $this->post('postalCode'),
            'territory' => $this->post('territory'),
            
        ];
        
        if(empty($data['officeCode']) || empty($data['city'])){
            $this->respomse([
                'Status' =>false,
                'Response' =>"Office Code and City are required"
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
                'Response' => "Failed to create customer"
            ], RestController::HTTP_INTERNAL_ERROR);
                }
        }

}