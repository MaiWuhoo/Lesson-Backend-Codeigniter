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

Class Product2 extends RestController {
    function __construct()
    {
        parent::__construct();
        $this->load->model('Product2/Product2_model');
    }

    //GET ALL
    function all_get(){
        $product2_list = $this ->Product2_model ->product2_list();

        $this -> response([
            'Status' => true,
            'Response' =>"Product list found!",
            'Product2' =>$product2_list,
        ], RestController::HTTP_OK);
    }

    //GET BY ID
    function detail_get(){
        $productCode = $this -> get('productCode');

        if(!$productCode){
            $this -> response([
                'Status' => false,
                'Response' => "Product Code is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $product2 = $this->Product2_model->product2_by_id($productCode);

        if($product2){
            $this->response([
                'Status' => true,
                'Response' => "Product Found!",
                'Product2' =>$product2
            ], RestController::HTTP_OK);
        } else {
            $this -> response([
                'Status'=>false,
                'Response'=>"Product not found"
            ], RestController::HTTP_NOT_FOUND);
        }
    }

    //CREATE
    function index_post(){
        $data =[
            'productCode'=> $this->post('productCode'),
            'productName'=>$this->post('productName'),
            'productLine' => $this->post('productLine'),
            'productScale' => $this -> post('productScale'),
            'productVendor' => $this->post('productVendor'),
            'productDescription' => $this->post('productDescription'),
            'quantityInStock' => $this->post('quantityInStock'),
            'buyPrice'=>$this->post('buyPrice'),
            'MSRP' => $this -> post('MSRP')
        ];

        if(empty($data['productCode'])){
            $this->response([
                'Status'=>false,
                'Response' =>"Product Code are required",
            ],RestController::HTTP_BAD_REQUEST);
            return;
        }

        $insert_id = $this->Product2_model->Product2_create($data);

        if($insert_id){
            $this -> response([
                'Status'=>true,
                'Response' => "Product created successfully!",
                'Product2'=>$insert_id
            ],RestController::HTTP_CREATED);
        }else{
            $this -> response([
                'Status'=>false,
                'Response'=>"Failed to create product"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }
}