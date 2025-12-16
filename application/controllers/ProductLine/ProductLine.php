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
}