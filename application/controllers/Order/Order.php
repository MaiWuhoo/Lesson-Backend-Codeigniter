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

Class Order extends RestController {
    function __construct()
    {
        parent::__construct();
        $this->load->model('Order/Order_model');
    }

    //GET ALL
    function all_get(){
        $order_list = $this ->Order_model ->order_list();

        $this -> response([
            'Status'=> true,
            'Response'=>'Order list found!',
            'Order'=>$order_list,
        ], RestController::HTTP_OK);
    }

    //GET BY ID
    function detail_get(){
        $orderNumber = $this ->get('orderNumber');

        if(!$orderNumber){
            $this->response([
                'Status'=>false,
                'Response' =>"Order Number is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $order = $this->Order_model->order_by_id($orderNumber);

        if($order){
            $this->response([
                'Status'=>true,
                'Response'=>"Order found!",
                'Order'=>$order
            ], RestController::HTTP_OK);
        } else{
            $this->response([
                'Status'=>false,
                'Response'=>"Order not found"
            ],RestController::HTTP_NOT_FOUND);
        }
    }

    //CREATE
    function index_post(){
        $data =[
            'orderDate' =>$this->post('orderDate'),
            'requiredDate' =>$this->post('requiredDate'),
            'shippedDate' =>$this->post('shippedDate'),
            'status' =>$this->post('status'),
            'comments' =>$this->post('comments'),
            'customerNumber' =>$this->post('customerNumber'),
        ];

        if(empty($data['orderDate'])){
            $this->response([
                'Status' => false,
                'Response' =>"Order Date are required",
                'orderNumber' => $insert_id
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $insert_id = $this->Order_model->order_create($data);

        if($insert_id){
            $this -> response([
                'Status'=>true,
                'Response' => "Order created successfully!",
                'orderNumber'=> $insert_id
            ], RestController::HTTP_CREATED);
        } else {
            $this ->response ([
                'Status' => false,
                'Response' =>"Failed to create order"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }
}