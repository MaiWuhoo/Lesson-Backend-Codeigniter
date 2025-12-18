<?php

class ProductLine_model extends CI_Model{

    private $default_db;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->default_db = $this->load->database('default', TRUE);
    }

    //GET ALL
    public function productLine_list(){
        $this->default_db->select('*');
        $query = $this->default_db->get('productlines');
        return $query->result();
    }

    //GET BY ID
    public function productLine_by_id($id){
        $this->default_db->select('*');
        $this->default_db->where('productLine',$id);
        $query = $this->default_db->get('productlines');
        return $query->row();
    }

    //CREATE
    public function productLine_create($data){
        $this->default_db->insert('productlines',$data);

        if($this->default_db->affected_rows() > 0){
            return $data['productLine'];
        }
    }

    public function productLine_update($productLine,$data){
        $this->default_db->where('productLine', $productLine);
        $this->default_db->update('productlines', $data);
        return $this->default_db->affected_rows() > 0;
    }

    public function productLine_delete($productLine){
    $this->default_db->where('productLine', $productLine);
    $this->default_db->delete('productlines');
    
    return $this->default_db->affected_rows() > 0;
}
}