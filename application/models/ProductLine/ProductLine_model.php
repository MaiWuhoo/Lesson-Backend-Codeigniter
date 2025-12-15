<?php

class ProductLine_model extends CI_Model{

    private $default_db;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->default_db = $this->load->database('default', TRUE);
    }

    public function productLine_list(){
        $this->default_db->select('*');
        $query = $this->default_db->get('productlines');
        return $query->result();
    }
}