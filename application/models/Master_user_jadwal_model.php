<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Master_user_jadwal_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function getAllById($where = array())
	{
		$this->db->select("mapel_detail
        .*")->from("mapel_detail
        ");
		$this->db->where("mapel_detail.is_deleted", 0);
		$this->db->where($where);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return FALSE;
	}
	public function insert($data)
	{
		$this->db->insert('mapel_detail
        ', $data);
		return $this->db->insert_id();
	}
	public function update($data, $where)
	{
		$this->db->update('mapel_detail
        ', $data, $where);
		return $this->db->affected_rows();
	}
	public function delete($where)
	{

		$this->db->where($where);
		$this->db->delete('mapel_detail
        ');
		if ($this->db->affected_rows()) {
			return TRUE;
		}
		return FALSE;
	}
	public function insert_batch($data_insert)
	{
		$this->db->insert_batch('mapel_detail
        ', $data_insert);
		return $this->db->insert_id();
	}
	public function update_batch($data_update)
	{
		$this->db->update_batch('mapel_detail
        ', $data_update, 'id');
		return $this->db->insert_id();
	}
	function getAllBy($limit, $start, $search, $col, $dir)
	{
		$this->db->select("mapel_detail
        .*")->from("mapel_detail
        ");

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
		$this->db->select("mapel_detail
        .*")->from("mapel_detail
        ");
		if (!empty($search)) {
			foreach ($search as $key => $value) {
				$this->db->or_like($key, $value);
			}
		}
		$result = $this->db->get();
		return $result->num_rows();
	}
	public function deleteByid($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('mapel_detail');
		return $this->db->affected_rows() > 0;
	}
}
