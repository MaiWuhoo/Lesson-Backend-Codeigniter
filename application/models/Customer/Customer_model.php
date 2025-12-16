<?php

class Customer_model extends CI_Model{
    
    private $default_db;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->default_db = $this->load->database('default', TRUE);
    }

    // GET ALL 
    public function customer_list(){
        $this->default_db->select('*');
        $query = $this->default_db->get('customers');
        return $query->result();
    }

    // GET BY ID
    public function customer_by_id($id){
        $this->default_db->select('*');
        $this->default_db->where('customerNumber', $id);
        $query = $this->default_db->get('customers');
        return $query->row(); 
    }

    // CREATE - UPDATED
    public function customer_create($data){
        $this->default_db->insert('customers', $data);
        
        // Return auto-generated customerNumber
        if($this->default_db->affected_rows() > 0){
            return $this->default_db->insert_id(); 
        }
        
        return false;
    }
}