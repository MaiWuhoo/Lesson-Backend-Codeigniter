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

    //GET  BY ID
    public function product2_by_id($id){
        $this->default_db->select('*');
        $this->default_db->where('productCode', $id);
        $query = $this -> default_db -> get('products');
        return $query ->row();
    }

    //CREATE

public function product2_create($data){
    $this->default_db->insert('products', $data);
    
    if($this->default_db->affected_rows() > 0){
        return $data['productCode'];
    }
}
}