<?php
defined ('BASEPATH') OR exit ('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST,PUT, DELETE,OPTIONS");
header("Access-Control-Allow-Headers:Content-Type");

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

Class Product extends RestController {
    function __construct()
    {
        parent::__construct();
<<<<<<< HEAD
        $this->load->model('Product/Product_model');
    }

    function all_get(){
        $product_list =$this ->Product_model ->product_list();

        $this -> response([
            'Status' => true,
            'Response'=>"Product list found!",
            'Product'=> $product_list,
        ], RestController::HTTP_OK);
    }
}
=======
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

// Class Product extends RestController {
//     function __construct()
//     {
//         parent::__construct();
//         $this->load->model('Product/Product_model');
//     }

//     function all_get(){
//         $product_list = $this ->Product_model -> product_list();

//         $this -> response([
//             'Status' => true,
//             'Response'=>"Product list found!",
//             'Product'=>$product_list,
//         ], RestController::HTTP_OK);
//     }
// }
>>>>>>> 568315573f3c0d29993bec7f606a46627ff57982
