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

Class ProductLine extends RestController {
    function __construct()
    {
        parent::__construct();
        $this->load->model('ProductLine/ProductLine_model');
    }

    //GET ALL
    function all_get(){
        $productLine_list = $this ->ProductLine_model -> productLine_list();

        $this -> response([
            'Status' => true,
            'Response'=>"ProductLine list found!",
            'ProductLine'=>$productLine_list,
        ], RestController::HTTP_OK);
    }

    //GET BY ID
    function detail_get(){
        $productLine = $this ->get('productLine');

        if(!$productLine){
            $this -> response([
                'Status' => false,
                'Resoponse' =>"ProductLine is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $productLine = $this->ProductLine_model->productLine_by_id($productLine);

        if($productLine){
            $this->response([
                'Status' => true,
                'Response' =>"ProductLine found!",
                'ProductLine' => $productLine
            ], RestController::HTTP_OK);
        } else{
            $this->response([
                'Status' => false,
                'Response' => "ProductLine not found"
            ], RestController::HTTP_NOT_FOUND);
        }
    }

    //CREATE
    function index_post(){
        $data=[
            'productLine' =>$this->post('productLine'),
            'textDescription' =>$this->post('textDescription'),
            'htmlDescription' => $this->post('htmlDescription'),
            'image' => $this->post('image')
        ];

        if(empty($data['productLine'])){
            $this->response([
                'Status' => false,
                'Response' => "Product Line are required",
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $insert_id = $this->ProductLine_model->productLine_create($data);

        if($insert_id){
            $this ->response([
                'Status'=> true,
                'Response' => "ProductLine created successfully!",
                'ProductLine' => $insert_id
            ],RestController::HTTP_CREATED);
        } else{
            $this -> response([
                'Status' => false,
                'Response' =>"Failed to create productLine"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }

    //PUT
    function index_put(){
        $productLine = $this -> put('productLine');

        if(!$productLine){
            $this->response([
                'Status' => false,
                'Response' => "Product Line is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $existing_productLine = $this->ProductLine_model -> productLine_by_id($productLine);
        if(!$existing_productLine){
            $this->response([
                'Status'=>false,
                'Response' => "Product Line not found"
            ], RestController::HTTP_NOT_FPUND);
            return;
        }

        if(!$this->put('textDescription') || !$this->put('htmlDescription') ||
        !$this->put('image')){
            $this-> response([
                'Status' => false,
                'Response' => "All required fields must be provided for full update"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $data =[
            'textDescription' => $this -> put('textDescription'),
            'htmlDescription' => $this-> put('htmlDescription'),
            'image'=>$this->put('image')
        ];

        $update_result = $this->ProductLine_model->productLine_update($productLine,$data);

        if($update_result){
            $this->response([
                'Status' => true,
                'Response' => "Customer replaced successfully!",
                'ProductLine'=>$productLine
            ], RestController::HTTP_OK);
        }else{
            $this->response([
                'Status' => false,
                'Response' => "Failed to update customer"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }

    //PATCH
    function index_patch(){
        $productLine = $this -> patch('productLine');

        if(!$productLine){
            $this->response([
                'Status'=>false,
                'Response' => "Product Line is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $existing_productLine = $this ->ProductLine_model -> productline_by_id($productLine);
        if(!$existing_productLine){
            $this->response([
                'Status'=>false,
                'Response' => "Product Line not found"
            ], RestController::HTTP_NOT_FOUND);
            return;
        }

        $data = [];

        if($this->patch('textDescription') !== null) $data ['textDescription'] = $this->patch('textDescription');
        if($this->patch('htmlDescription') !== null) $data ['htmlDescription'] = $this->patch('htmlDescription');
        if($this->patch('image') !== null) $data ['image'] = $this->patch('image');

        if(empty($data)){
            $this -> response([
                'Status'=>false,
                'Response' => "No data provided for update"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $update_result = $this->ProductLine_model->productline_update($productLine, $data);


        if($update_result){
            $this->response([
                'Status'=> true,
                'Response'=>"Product Line updated successfully!",
                'ProductLine'=>$productLine
            ], RestController::HTTP_OK);
        }else{
            $this -> response([
                'Status' => false,
                'Response' => "Failed to update productLine or no changes made"
            ],RestController::HTTP_INTERNAL_ERROR);
        }
    }

    //DELETE
    function detail_delete(){
        $productLine = $this->input->request_headers()['productLine'];

        if(!$productLine){
            $this -> response([
                'Status'=>false,
                'Response'=>"Product Line is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $existing_productLine = $this->ProductLine_model -> productline_by_id($productLine);
        if(!$existing_productLine){
            $this->response([
                'Status' => false,
                'Response' => "Product Line not found"
            ], RestController::HTTP_NOT_FOUND);
            return;
        }

        $delete_result = $this->ProductLine_model ->productline_delete($productLine);
        
        if($delete_result){
            $this->response([
                'Status' => true,
                'Response' => "Product Line deleted successfully!",
                'ProductLine' => $productLine
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'Status' => false,
                'Response' => "Failed to delete Product Line"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }
}