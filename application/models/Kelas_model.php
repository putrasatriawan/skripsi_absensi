<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelas_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllById($where = array())
    {
        $this->db->select("kelas.*")->from("kelas");
        $this->db->where($where);
        $this->db->where("kelas.is_deleted", 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function get_by_code($code)
    {
        $this->db->where('code', $code);
        $query = $this->db->get('kelas');
        if ($query->num_rows() > 0) {
            return $query->row();
        } 
        else {
            return false;
        }
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('kelas');
        return $query->row();
    }

    public function getOneBy($where = array())
    {
        $this->db->select("kelas.*")->from("kelas");
        $this->db->where("kelas.is_deleted", 0);
        $this->db->where($where);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }

    public function insert($data)
    {
        $this->db->insert('kelas', $data);
        return $this->db->insert_id();
    }

    public function update($data, $where)
    {
        $this->db->update('kelas', $data, $where);
        return $this->db->affected_rows();
    }

    public function delete($where)
    {
        $this->db->where($where);
        $this->db->delete('kelas');
        if ($this->db->affected_rows()) {
            return TRUE;
        }
        return FALSE;
    }

    public function insert_batch($data)
    {
        $this->db->insert_batch('kelas', $data);
        return $this->db->insert_id();
    }

    function getAllBy($limit, $start, $search, $col, $dir)
    {
        $this->db->select("kelas.*")->from("kelas");
        $this->db->limit($limit, $start)->order_by($col, $dir);
        if (!empty($search)) {
            foreach ($search as $key => $value) {
                $this->db->or_like($key, $value);
            }
        }
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } 
        else {
            return null;
        }
    }

    function getCountAllBy($limit, $start, $search, $order, $dir)
    {
        $this->db->select("kelas.*")->from("kelas");
        if (!empty($search)) {
            foreach ($search as $key => $value) {
                $this->db->or_like($key, $value);
            }
        }
        $result = $this->db->get();
        return $result->num_rows();
    }
    
    public function getCountAllById($where = array())
    {
        $this->db->select("COUNT(*) as total_rows")->from("kelas");
        $this->db->where($where);
        $this->db->where("kelas.is_deleted", 0);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->total_rows;
        }
        return 0;
    }

    public function getCodeById($kelas_id)
    {
        $this->db->select('code');
        $this->db->from('kelas');
        $this->db->where('id', $kelas_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->name;
        }
        return 0;
    }

    public function getNameById($kelas_id)
    {
        $this->db->select('name');
        $this->db->from('kelas');
        $this->db->where('id', $kelas_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->name;
        }
        return 0;
    }

    public function checkCodeAndName($code, $name)
    {
        $this->db->where('code', $code);
        $this->db->where('name', $name);
        $query = $this->db->get('kelas');
        
        return $query->row();
    }

    public function checkCodeAndNameExceptId($code, $name, $id)
    {
        $this->db->where('id !=', $id);
        $this->db->group_start();
        $this->db->where('code', $code);
        $this->db->where('name', $name);
        $this->db->group_end();
        $query = $this->db->get('kelas');
        
        return $query->row();
    }
}