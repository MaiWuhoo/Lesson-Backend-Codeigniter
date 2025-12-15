<?php
defined ('BASEPATH') OR exit ('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST,PUT, DELETE,OPTIONS");
header("Access-Control-Allow-Headers:Content-Type");

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

Class Order extends RestController {
    function __construct()
    {
        parent::__construct();
        $this->load->model('Order/Order_model');
    }

    function all_get(){
        $order_list = $this ->Order_model ->order_list();

        $this -> response([
            'Status'=> true,
            'Response'=>'Order list found!',
            'Order'=>$order_list,
        ], RestController::HTTP_OK);
    }
}