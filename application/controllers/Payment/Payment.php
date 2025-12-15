<?php
defined ('BASEPATH') OR exit ('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST,PUT, DELETE,OPTIONS");
header("Access-Control-Allow-Headers:Content-Type");

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

Class Payment extends RestController {
    function __construct()
    {
        parent::__construct();
        $this->load->model('Payment/Payment_model');
    }

    function all_get(){
        $payment_list =$this ->Payment_model -> payment_list();

        $this -> response([
            'Status' => true,
            'Response' =>"Payment list found!",
            'Payment'=>$payment_list,
        ], RestController::HTTP_OK);
    }
}