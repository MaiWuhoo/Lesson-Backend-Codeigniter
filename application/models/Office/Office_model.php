<?php

class Office_model extends CI_Model{

    private $default_db;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->default_db = $this->load->database('default', TRUE);
    }

    //GET ALL
    public function office_list(){
        $this->default_db->select('*');
        $query = $this->default_db->get('offices');
        return $query->result();
    }
    //GET BY ID
    public function office_by_id($id){
        $this->default_db->select('*');
        $this->default_db->where('officeCode', $id);
        $query = $this->default_db->get('offices');
        return $query ->row();
    }

    //CREATE
    public function office_create($data){
        $this->default_db->insert('offices',$data);

        if($this->default_db->affected_rows() > 0){
            return  $this->default_db->insert_id(); 
        }

        return false;
    }
}