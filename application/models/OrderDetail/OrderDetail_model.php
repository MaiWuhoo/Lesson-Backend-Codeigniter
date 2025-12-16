<?php

class OrderDetail_model extends CI_Model{
    private $default_db;
    
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->default_db = $this->load->database('default', TRUE);
    }

    // GET ALL
    public function orderDetail_list(){
        $this->default_db->select('*');
        $query = $this->default_db->get('orderdetails');
        return $query->result();
    }

    // GET BY ORDER NUMBER (all details for that order)
    public function orderDetail_by_orderNumber($orderNumber){
        $this->default_db->select('*');
        $this->default_db->where('orderNumber', $orderNumber);
        $query = $this->default_db->get('orderdetails');
        return $query->result(); // Multiple rows
    }
    
    // GET SPECIFIC DETAIL (by orderNumber AND productCode)
    public function orderDetail_by_id($orderNumber, $productCode){
        $this->default_db->select('*');
        $this->default_db->where('orderNumber', $orderNumber);
        $this->default_db->where('productCode', $productCode);
        $query = $this->default_db->get('orderdetails');
        return $query->row(); // Single row
    }

    // CREATE
    public function orderDetail_create($data){
        $this->default_db->insert('orderdetails', $data);
        
        if($this->default_db->affected_rows() > 0){
            return [
                'orderNumber' => $data['orderNumber'],
                'productCode' => $data['productCode']
            ];
        }
        
        return false;
    }
}