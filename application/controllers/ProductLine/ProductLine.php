<?php
defined ('BASEPATH') OR exit ('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST,PUT, DELETE,OPTIONS");
header("Access-Control-Allow-Headers:Content-Type");

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

Class ProductLine extends RestController {
    function __construct()
    {
        parent::__construct();
        $this->load->model('ProductLine/ProductLine_model');
    }

    function all_get(){
        $productLine_list = $this ->ProductLine_model -> productLine_list();

        $this -> response([
            'Status' => true,
            'Response'=>"ProductLine list found!",
            'ProductLine'=>$productLine_list,
        ], RestController::HTTP_OK);
    }
}