<?php

class Employee_model extends CI_Model {

    private $default_db;
    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->default_db = $this->load->database('default' , TRUE);
    }

    public function employee_list (){
        $this->default_db->select('*');
        $query = $this->default_db->get('employees');
        return $query->result();
    }


    //GET BY ID
    public function employee_by_id($id){
        $this->default_db->select('*');
        $this->default_db->where('employeeNumber',$id);
        $query = $this->default_db->get('employees');
        return $query->row();
    }

    //Create
    public function employee_create($data){
        $this->default_db->insert('employees',$data);

        //return employeeNumber yang baru create
        if($this->default_db->affected_rows() >0){
            return $this->default_db->insert_id(); 
        }

        return false;
    }

    //UPDATE (PUT & PATCH)
    public function employee_update($employeeNumber , $data){
        $this->default_db->where('employeeNumber',$employeeNumber);
        $this->default_db->update('employees' ,$data);

        return $this->default_db->affected_rows() > 0;
    }

    public function employee_delete($employeeNumber){
    $this->default_db->where('employeeNumber', $employeeNumber);
    $this->default_db->delete('employees');
    
    return $this->default_db->affected_rows() > 0;
}

}