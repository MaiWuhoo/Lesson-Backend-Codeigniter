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

class OrderDetail extends RestController {
    
    function __construct(){
        parent::__construct();
        $this->load->model('OrderDetail/OrderDetail_model');
    }

    // 1. GET ALL ORDER DETAILS
    function index_get(){
        $orderDetail_list = $this->OrderDetail_model->orderDetail_list();
        
        $this->response([
            'Status' => true,
            'Response' => "Order details list found!",
            'OrderDetails' => $orderDetail_list,
        ], RestController::HTTP_OK);
    }

    // 2. GET BY ORDER NUMBER (all products in that order)
    function order_get(){
        $orderNumber = $this->get('orderNumber');
        
        if(!$orderNumber){
            $this->response([
                'Status' => false,
                'Response' => "Order Number is required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }
        
        $orderDetails = $this->OrderDetail_model->orderDetail_by_orderNumber($orderNumber);
        
        if($orderDetails){
            $this->response([
                'Status' => true,
                'Response' => "Order details found!",
                'OrderDetails' => $orderDetails
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'Status' => false,
                'Response' => "No details found for this order"
            ], RestController::HTTP_NOT_FOUND);
        }
    }

    // 3. GET BY ORDER NUMBER AND PRODUCT CODE 
    function detail_get(){
        $orderNumber = $this->get('orderNumber');
        $productCode = $this->get('productCode');
        
        if(!$orderNumber || !$productCode){
            $this->response([
                'Status' => false,
                'Response' => "Order Number and Product Code are required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }
        
        $orderDetail = $this->OrderDetail_model->orderDetail_by_id($orderNumber, $productCode);
        
        if($orderDetail){
            $this->response([
                'Status' => true,
                'Response' => "Order detail found!",
                'OrderDetail' => $orderDetail
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'Status' => false,
                'Response' => "Order detail not found"
            ], RestController::HTTP_NOT_FOUND);
        }
    }

    // 4. CREATE NEW ORDER DETAIL
    function index_post(){
        $data = [
            'orderNumber' => $this->post('orderNumber'),
            'productCode' => $this->post('productCode'),
            'quantityOrdered' => $this->post('quantityOrdered'),
            'priceEach' => $this->post('priceEach'),
            'orderLineNumber' => $this->post('orderLineNumber')
        ];
        
        // Validation
        if(empty($data['orderNumber']) || empty($data['productCode'])){
            $this->response([
                'Status' => false,
                'Response' => "Order Number and Product Code are required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }
        
        $result = $this->OrderDetail_model->orderDetail_create($data);
        
        if($result){
            $this->response([
                'Status' => true,
                'Response' => "Order detail created successfully!",
                'Data' => $result
            ], RestController::HTTP_CREATED);
        } else {
            $this->response([
                'Status' => false,
                'Response' => "Failed to create order detail"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }
}