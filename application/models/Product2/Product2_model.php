<?php

class Product2_model extends CI_Model{

    private $default_db;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->default_db = $this->load->database('default', TRUE);
    }

    public function product2_list(){
        $this->default_db->select('*');
        $query = $this->default_db->get('products');
        return $query->result();
    }
}