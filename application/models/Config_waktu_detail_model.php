<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Config_waktu_detail_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function getAllBy($limit, $start, $search, $col, $dir, $id)
	{
		$this->db->select("config_waktu_detail.*, users.first_name as name_user")
			->from("config_waktu_detail")
			->join("users", "users.id = config_waktu_detail.id_user", "left")
			->where("config_waktu_detail.id_config_master", $id)
			->group_by("config_waktu_detail.id_user") // Tambahan group by
			->limit($limit, $start)
			->order_by($col, $dir);

		if (!empty($search)) {
			foreach ($search as $key => $value) {
				$this->db->or_like($key, $value);
			}
		}

		$result = $this->db->get();
		return $result->num_rows() > 0 ? $result->result() : null;
	}



	public function getById($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('config_waktu_detail');  // Assuming the table name is 'config_waktu_detail'
		return $query->row(); // Returns a single row (object)
	}

	function getCountAllBy($limit, $start, $search, $order, $dir, $id)
	{
		$this->db->from("config_waktu_detail")
			->join("users", "users.id = config_waktu_detail.id_user", "left")
			->where("config_waktu_detail.id_config_master", $id)
			->group_by("config_waktu_detail.id_user");
		if (!empty($search)) {
			foreach ($search as $key => $value) {
				$this->db->or_like($key, $value);
			}
		}
		$result = $this->db->get();
		return $result->num_rows();
	}
}
