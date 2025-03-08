<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Absensi_guru_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getAllById($where = array())
    {
        $this->db->select("absensi_guru.*")->from("absensi_guru");
        $this->db->where($where);
        $this->db->where("absensi_guru.is_deleted", 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function getOneBy($where = array())
    {
        $this->db->select("absensi_guru.*")->from("absensi_guru");
        $this->db->where("absensi_guru.is_deleted", 0);
        $this->db->where($where);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('absensi_guru'); 
        return $query->row(); 
    }

    public function insert($data)
    {

        $this->db->insert('absensi_guru', $data);
        return $this->db->insert_id();
    }

    public function update($data, $where)
    {
        $this->db->update('absensi_guru', $data, $where);
        return $this->db->affected_rows();
    }

    public function delete($where)
    {
        $this->db->where($where);
        $this->db->delete('absensi_guru');
        if ($this->db->affected_rows()) {
            return TRUE;
        }
        return FALSE;
    }

    public function insert_batch($data)
    {
        $this->db->insert_batch('absensi_guru', $data);
        return $this->db->insert_id();
    }

    function getAllBy($limit, $start, $search, $col, $dir)
    {
        $this->db->select("absensi_guru.*")->from("absensi_guru");

        $this->db->limit($limit, $start)->order_by($col, $dir);
        if (!empty($search)) {
            foreach ($search as $key => $value) {
                $this->db->or_like($key, $value);
            }
        }
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return null;
        }
    }
    function getCountAllBy($limit, $start, $search, $order, $dir)
    {
        $this->db->select("absensi_guru.*")->from("absensi_guru");
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
        $this->db->select("COUNT(*) as total_rows")->from("absensi_guru");
        $this->db->where($where);
        $this->db->where("absensi_guru.is_deleted", 0);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->total_rows;
        }

        return 0;
    }

    public function getAttendanceByDate($id_user, $date)
    {
        $this->db->select('*');
        $this->db->from('absensi_guru');
        $this->db->where('id_user', $id_user);
        $this->db->like('tanggal_absen', $date);
        $this->db->where('is_deleted', 0);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

}
