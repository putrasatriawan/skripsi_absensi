<?php
defined('BASEPATH') or exit('No direct script access allowed');


class config_waktu_master_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllById($where = array())
	{
		$this->db->select("config_waktu_master.*")->from("config_waktu_master");
		$this->db->where("config_waktu_master.is_deleted", 0);
		$this->db->where($where);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return FALSE;
	}
	public function getAllIdSuperadmin($where = array())
	{
		$this->db->select("config_waktu_master.*")->from("config_waktu_master");
		$this->db->where("config_waktu_master.is_deleted", 0);
		$this->db->where($where);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return FALSE;
	}
	public function insert($data)
	{
		$this->db->insert('config_waktu_master', $data);
		return $this->db->insert_id();
	}

	public function update($data, $where)
	{
		$this->db->update('config_waktu_master', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete($where)
	{
		$this->db->where($where);
		$this->db->delete('config_waktu_master');
		if ($this->db->affected_rows()) {
			return TRUE;
		}
		return FALSE;
	}

	function getAllBy($limit, $start, $search, $col, $dir)
	{
		$this->db->select("config_waktu_master.*")->from("config_waktu_master");

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

	public function getById($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('config_waktu_master');  // Assuming the table name is 'config_waktu_master'
		return $query->row(); // Returns a single row (object)
	}

	public function getCountAllBy($limit, $start, $search, $order, $dir)
	{
		$this->db->select("config_waktu_master.*")->from("config_waktu_master");
		if (!empty($search)) {
			foreach ($search as $key => $value) {
				$this->db->or_like($key, $value);
			}
		}
		$result = $this->db->get();
		return $result->num_rows();
	}


}
