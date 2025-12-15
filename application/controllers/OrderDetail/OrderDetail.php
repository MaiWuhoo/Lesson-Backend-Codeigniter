<?php
defined ('BASEPATH') OR exit ('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST,PUT, DELETE,OPTIONS");
header("Access-Control-Allow-Headers:Content-Type");

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

Class OrderDetail extends RestController{
    function __construct()
    {
        parent::__construct();
        $this->load->model('OrderDetail/OrderDetail_model');
    }

    function all_get(){
        $orderDetail_list =$this->OrderDetail_model ->orderDetail_list();

        $this -> response([
            'Status' => true,
            'Response'=> "Order Detail list found!",
            'OrderDetail'=> $orderDetail_list,
        ], RestController::HTTP_OK);
    }

}