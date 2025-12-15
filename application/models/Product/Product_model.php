<?php

class Product_model extends CI_Model{

    private $default_db;
    function __construct(){
<<<<<<< HEAD
        parent:;__construct();
        $this->load->database();
        $this->default_db = $this->load->database('default',TRUE);
=======
        parent::__construct();
        $this->load->database();
        $this->default_db = $this->load->database('default', TRUE);
>>>>>>> 568315573f3c0d29993bec7f606a46627ff57982
    }

    public function product_list(){
        $this->default_db->select('*');
        $query = $this->default_db->get('products');
        return $query->result();
    }
}