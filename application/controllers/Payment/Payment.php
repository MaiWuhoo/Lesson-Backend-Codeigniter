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

Class Payment extends RestController {
    function __construct()
    {
        parent::__construct();
        $this->load->model('Payment/Payment_model');
    }

    //Get all
    function all_get(){
        $payment_list =$this ->Payment_model -> payment_list();

        $this -> response([
            'Status' => true,
            'Response' =>"Payment list found!",
            'Payment'=>$payment_list,
        ], RestController::HTTP_OK);
    }

    //get by cust number
    function customer_get(){
        $customerNumber =$this ->get('customerNumber');

        if(!$customerNumber){
            $this->response([
                'Status' => false,
                'Response' =>"Customer Number is required"
            ],RestController::HTTP_BAD_REQUEST);
            return;
        }

        $payment =$this->Payment_model->payment_by_customerNumber($customerNumber);

        if($payment){
            $this ->response([
                'Status' => true,
                'Response' =>"Payment Details found!",
                'Payment'=>$payment
            ],RestController::HTTP_OK);
        } else{
            $this-> response([
                'Status'=>false,
                'Response' =>"No details found"
            ], RestController::HTTP_NOT_FOUND);
        }
    }

    //Get by customer number and check number
    function detail_get(){
        $customerNumber = $this->get('customerNumber');
        $checkNumber = $this->get('checkNumber');

        if(!$customerNumber || !$checkNumber){
            $this->response([
                'Status' => false,
                'Response' =>"Customer Number and Check Number are required"
            ], RestController::HTTP_BAD_REQUEST);
            return;
        }

        $payment= $this ->Payment_model ->payment_by_id($customerNumber,$checkNumber);

        if($payment){
            $this -> response ([
                'Status' => true,
                'Response'=> "Payment Detail found!",
                'Payment'=>$payment
            ], RestController::HTTP_OK);
        } else{
            $this -> response([
                'Status' => false,
                'Response' => "Payment detail not found"
            ], RestController::HTTP_NOT_FOUND);
        }
    }

    //CREATE NEW PAYMENT
    function index_post(){
        $data = [
            'customerNumber' =>$this->post('customerNumber'),
            'checkNumber' => $this->post('checkNumber'),
            'paymentDate' => $this->post('paymentDate'),
            'amount'=>$this->post('amount')
        ];

        if(empty($data['customerNumber']) || empty($data['checkNumber'])){
            $this->response([
                'Status'=> false,
                'Response'=>"Customer Number and Check Number are required"
            ],RestController::HTTP_BAD_REQUEST);
            return;
        }

        $result = $this->Payment_model -> payment_create($data);

        if($result){
            $this -> response([
                'Status' =>true,
                'Response' =>"Payment created successfully!",
                'Data'=>$result
            ], RestController::HTTP_CREATED);
        } else {
            $this->response([
                'Status' => false,
                'Response' =>"Failed to create payment"
            ], RestController::HTTP_INTERNAL_ERROR);
        }
    }
    
}