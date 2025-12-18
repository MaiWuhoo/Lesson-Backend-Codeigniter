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

    //PUT (Full Update)
    function index_put(){
        $orderNumber = $this->put('orderNumber');

        if(!$orderNumber){
            $this->response([
                'Status' => false,
                'Response' => "Order Number is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        if(!$this -> put('orderDate') || !$this->put('requiredDate') || 
        !$this -> put('shippedDate') || !$this -> put('status') ||
        !$this -> put ('comments') || !$this -> put ('customerNumber')){
            $this -> response([
                'Status'=>false,
                'Response' =>"All required fields must be probided for full update"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $data = [
            'orderDate' => $this -> put('orderDate'),
            'requiredDate' => $this -> put('requiredDate'),
            'shippedDate'=> $this -> put('shippedDate'),
            'status' => $this -> put('status'),
            'comments'=>$this -> put('comments'),
            'customerNumber' => $this -> put ('customerNumber')
        ];

        $update_result = $this->Order_model -> order_update($orderNumber, $data);

        if($update_result){
            $this -> response([
                'Status' => true,
                'Response' => "Order replaced successfully!",
                'OrderNumber' => $orderNumber
            ], RestController::HTTP_OK);
        } else{
            $this -> response([
                'Status' => false,
                'Response' => "Failed to update order"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }

    //PATCH (Partial Update)
    function index_patch (){
        $orderNumber = $this -> patch ('orderNumber');

        if(!$orderNumber){
            $this->response([
                'Status' => false,
                'Response' => "Order Number is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $data = [];

        if($this -> patch('orderDate') !== null) $data ['orderDate'] = $this -> patch('orderDate');
        if($this -> patch ('requiredDate') !== null) $date['requiredDate'] = $this -> patch ('requiredDate');
        if($this -> patch ('shippedDate') !== null) $data ['shippedDate'] = $this -> patch ('shippedDate');
        if($this -> patch ('status') !== null) $data['status'] = $this -> patch ('status');
        if($this -> patch ('comments') !== null) $data ['comments'] = $this -> patch('comments');
        if($this -> patch ('customerNumber') !== null) $data ['customerNumber'] = $this -> patch ('customerNumber');

        if(empty($data)){
            $this -> response([
                'Status' =>false,
                'Response' => "No data provided for update"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $update_result = $this ->Order_model -> order_update($orderNumber, $data);

        if($update_result){
            $this -> response([
                'Status'=>false,
                'Response' => "Failed to update customer or no changes made"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }

    //DELETE
    function detail_delete(){
        $orderNumber = $this -> input->request_headers()['orderNumber'];

        if(!$orderNumber){
            $this -> response([
                'Status'=>false,
                'Response' => "Order Number is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $existing_order = $this -> Order_model -> order_by_id($orderNumber);
        if(!$existing_order){
            $this-> response([
                'Status' => false,
                'Response' => "Order not found"
            ], RestController::HTTP_NOT_FOUND);
            return;
        }

        $delete_result = $this->Order_model->order_delete($orderNumber);

        if($delete_result){
            $this -> response([
                'Status' => true,
                'Response' => "Order deleted successfully!",
                'OrderNumber' => $orderNumber
            ], RestController::HTTP_OK);
        } else {
            $this -> response([
                'Status' => false,
                'Response' => "Failed to delete order"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }
}