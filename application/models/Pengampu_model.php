<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengampu_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllById($where = array())
    {
        $this->db->select("pengampu.*, users.first_name as guru, mapel.name as mapel, kelompok_kelas.kode_kelas as kode_kelas")->from("pengampu");
        $this->db->join('users', 'pengampu.id_user = users.id', 'left');
        $this->db->join('mapel', 'pengampu.id_mapel = mapel.id', 'left');
        $this->db->join('kelompok_kelas', 'pengampu.id_kelas = kelompok_kelas.id', 'left');
        $this->db->where($where);
        $this->db->where("pengampu.is_deleted", 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function getOneBy($where = array())
    {
        $this->db->select("pengampu.*, users.first_name as guru, mapel.name as mapel, kelompok_kelas.kode_kelas as kode_kelas")->from("pengampu");
        $this->db->join('users', 'pengampu.id_user = users.id', 'left');
        $this->db->join('mapel', 'pengampu.id_mapel = mapel.id', 'left');
        $this->db->join('kelompok_kelas', 'pengampu.id_kelas = kelompok_kelas.id', 'left');
        $this->db->where("pengampu.is_deleted", 0);
        $this->db->where($where);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }

    public function insert($data)
    {
        $this->db->insert('pengampu', $data);
        return $this->db->insert_id();
    }

    public function update($data, $where)
    {
        $this->db->update('pengampu', $data, $where);
        return $this->db->affected_rows();
    }

    public function delete($where)
    {
        $this->db->where($where);
        $this->db->delete('pengampu');
        if ($this->db->affected_rows()) {
            return TRUE;
        }
        return FALSE;
    }

    public function insert_batch($data)
    {
        $this->db->insert_batch('pengampu', $data);
        return $this->db->insert_id();
    }

    function getAllBy($limit, $start, $search, $col, $dir, $where = array())
    {
        $this->db->select("pengampu.*, users.first_name as guru, mapel.name as mapel, kelompok_kelas.kode_kelas as kode_kelas, ruang.name as ruang,
        kelompok_kelas.id as kelompok_kelas_id, mapel.id as mapel_id, users.id as user_id, pengampu_sub.hari as hari, pengampu_sub.jam as jam")->from("pengampu");
        $this->db->join('pengampu_sub', 'pengampu.id = pengampu_sub.id_pengampu', 'left');
        $this->db->join('users', 'pengampu.id_user = users.id', 'left');
        $this->db->join('mapel', 'pengampu_sub.id_mapel = mapel.id', 'left');
        $this->db->join('kelompok_kelas', 'pengampu_sub.id_kelas = kelompok_kelas.id', 'left');
        $this->db->join('ruang', 'pengampu_sub.ruang_id = ruang.id', 'left');

        $this->db->limit($limit, $start)->order_by($col, $dir);
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($search)) {
            $this->db->group_start();
            foreach ($search as $column => $value) {
                $this->db->or_like($column, $value);
            }
            $this->db->group_end();
        }
        
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return null;
        }
    }

    public function getByUserId($id)
    {
        $this->db->where('id_user', $id);
        $query = $this->db->get('pengampu'); 
        return $query->row();
    }

    function getCountAllBy($limit, $start, $search, $order, $dir)
    {
        $this->db->select("pengampu.*, users.first_name as guru, mapel.name as mapel, kelompok_kelas.kode_kelas as kode_kelas, ruang.name as ruang,
        kelompok_kelas.id as kelompok_kelas_id, mapel.id as mapel_id, users.id as user_id, pengampu_sub.hari as hari, pengampu_sub.jam as jam")->from("pengampu");
        $this->db->join('pengampu_sub', 'pengampu.id = pengampu_sub.id_pengampu', 'left');
        $this->db->join('users', 'pengampu.id_user = users.id', 'left');
        $this->db->join('mapel', 'pengampu_sub.id_mapel = mapel.id', 'left');
        $this->db->join('kelompok_kelas', 'pengampu_sub.id_kelas = kelompok_kelas.id', 'left');
        $this->db->join('ruang', 'pengampu_sub.ruang_id = ruang.id', 'left');
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
        $this->db->select("COUNT(*) as total_rows")->from("pengampu");
        $this->db->where($where);
        $this->db->where("pengampu.is_deleted", 0);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->total_rows;
        }
        return 0; 
    }

    public function getPengampuByMapel($id_mapel)
    {
        $this->db->select('pengampu.*, users.id as user_id, users.first_name as guru')->from('pengampu');
        $this->db->join('users', 'pengampu.id_user = users.id', 'left');
        $this->db->join('pengampu_sub', 'pengampu.id = pengampu_sub.id_pengampu', 'left');
        $this->db->where('pengampu_sub.id_mapel', $id_mapel);
        $this->db->where('pengampu_sub.is_deleted', 0);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return [];
    }
}