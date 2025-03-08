<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Roles_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct(); 
    }  

    public function getOneBy($where = array()){
        $this->db->select("roles.*")->from("roles"); 
       //superadmin
        $roles_default = array('1');
        $this->db->where_not_in('roles.id', $roles_default); 
        $this->db->where($where);  
        $this->db->where("roles.is_deleted",0);  

        $query = $this->db->get();
        if ($query->num_rows() >0){  
            return $query->row(); 
        } 
        return FALSE;
    }

    public function getAllById($where = array()){
        $this->db->select("roles.*")->from("roles");  
        //superadmin, agent, pandu
        $roles_default = array('1', '2');
        $this->db->where_not_in('roles.id', $roles_default);

        $this->db->where($where);  
        $this->db->where("roles.is_deleted",0);  

        $query = $this->db->get();
        if ($query->num_rows() >0){  
            return $query->result(); 
        } 
        return FALSE;
    }
    public function insert($data){
        $this->db->insert("roles", $data);
        return $this->db->insert_id();
    }

    public function update($data,$where){
        $this->db->update("roles", $data, $where);
        return $this->db->affected_rows();
    }

    public function delete($where){
        $this->db->where($where);
        $this->db->delete("roles"); 
        if($this->db->affected_rows()){
            return TRUE;
        }
        return FALSE;
    }

    function getAllBy($limit,$start,$search,$col,$dir)
    {
        $this->db->select("roles.*")->from("roles");   
        $this->db->limit($limit,$start)->order_by($col,$dir);
        if(!empty($search)){
            foreach($search as $key => $value){
                $this->db->or_like($key,$value);    
            }   
        }
        //superadmin, agent, pandu
        $roles_default = array('1');
        $this->db->where_not_in('roles.id', $roles_default);
  
        $result = $this->db->get();
        if($result->num_rows()>0)
        {
            return $result->result();  
        }
        else
        {
            return null;
        }
    }

     function getAllByRoles($limit,$start,$search,$col,$dir)
    {
        $this->db->select("roles.*")->from("roles"); 
        $this->db->where("roles.is_deleted",0);    
        $this->db->limit($limit,$start)->order_by($col,$dir);
        if(!empty($search)){
            foreach($search as $key => $value){
                $this->db->or_like($key,$value);    
            }   
        }
        //superadmin, agent, pandu
        $roles_default = array('1');
        $this->db->where_not_in('roles.id', $roles_default);
  
        $result = $this->db->get();
        if($result->num_rows()>0)
        {
            return $result->result();  
        }
        else
        {
            return null;
        }
    }

    function getCountAllBy($limit,$start,$search,$order,$dir)
    { 
        $this->db->select("roles.*")->from("roles");  

        if(!empty($search)){
            foreach($search as $key => $value){
                $this->db->or_like($key,$value);    
            }   
        }

        //superadmin, agent, pandu
        $roles_default = array('1');
        $this->db->where_not_in('roles.id', $roles_default);
 
        $result = $this->db->get();
    
        return $result->num_rows();
    } 

    function getCountAllByRoles($limit,$start,$search,$order,$dir)
    { 
        $this->db->select("roles.*")->from("roles");  
         $this->db->where("roles.is_deleted",0);   
        if(!empty($search)){
            foreach($search as $key => $value){
                $this->db->or_like($key,$value);    
            }   
        }

        //superadmin, agent, pandu
        $roles_default = array('1');
        $this->db->where_not_in('roles.id', $roles_default);
 
        $result = $this->db->get();
    
        return $result->num_rows();
    }
    
    public function checkNameAndDescription($name, $description)
    {
        $this->db->where('name', $name);
        $this->db->where('description', $description);
        $query = $this->db->get('roles');
        
        return $query->row();
    }

    public function checkNameAndDescriptionForUpdate($name, $description, $id)
    {
        $this->db->where('id !=', $id);
        $this->db->group_start();
        $this->db->where('name', $name);
        $this->db->where('description', $description);
        $this->db->group_end();
        $query = $this->db->get('roles');
        
        return $query->row();
    }
}