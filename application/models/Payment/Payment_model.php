<?php

class Payment_model extends CI_Model {
    private $default_db;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->default_db = $this->load->database('default',TRUE);
    }

    //GET ALL
    public function payment_list(){
        $this->default_db->select('*');
        $query = $this->default_db->get('payments');
        return $query->result();
    }

    //GET BY CUSTOMER NUMBER
    public function payment_by_customerNumber($customerNumber){
        $this->default_db->select('*');
        $this->default_db->where('customerNumber',$customerNumber);
        $query = $this->default_db->get('payments');
        return $query->result();
    }

    //GET BY CUSTOMER NUMBER AND CHECK NUMBER
    public function payment_by_id($customerNumber,$checkNumber){
        $this->default_db->select('*');
        $this->default_db->where('customerNumber',$customerNumber);
        $this->default_db->where('checkNumber',$checkNumber);
        $query = $this->default_db->get('payments');
        return $query->row();
    }

    //create
    public function payment_create($data){
        $this->default_db->insert('payments',$data);
        
        if($this->default_db->affected_rows() > 0 ){
            return [
                'customerNumber' => $data['customerNumber'],
                'checkNumber'=> $data['checkNumber']
            ];
        }

        return false;
    }
}