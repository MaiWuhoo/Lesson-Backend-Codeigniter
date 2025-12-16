<?php

class Order_model extends CI_Model {
    private $default_db;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->default_db = $this->load->database('default' , TRUE);
    }

    //GET ALL
    public function order_list(){
        $this->default_db->select('*');
        $query = $this->default_db->get('orders');
        return $query->result();
    }

    //GET BY ID
    public function order_by_id($id){
        $this->default_db->select('*');
        $this->default_db->where('orderNumber',$id);
        $query = $this->default_db->get('orders');
        return $query->row();
    }

    //CREATE
    public function order_create($data){
        $this ->default_db->insert('orders', $data);

        if($this->default_db->affected_rows() >0){
            return $this->default_db->insert_id();
        }

        return false;
    }
}