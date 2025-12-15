<?php
defined ('BASEPATH') OR exit ('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST,PUT, DELETE,OPTIONS");
header("Access-Control-Allow-Headers:Content-Type");

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

Class Product2 extends RestController {
    function __construct()
    {
        parent::__construct();
        $this->load->model('Product2/Product2_model');
    }

    function all_get(){
        $product2_list = $this ->Product2_model ->product2_list();

        $this -> response([
            'Status' => true,
            'Response' =>"Product list found!",
            'Product2' =>$product2_list,
        ], RestController::HTTP_OK);
    }
}