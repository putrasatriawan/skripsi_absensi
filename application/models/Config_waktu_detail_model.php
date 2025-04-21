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

	function getAllById($where)
	{
		$this->db->select("config_waktu_detail.*, users.first_name as name_user, master_user.nip as nip_master_user, master_user.jenis_kelamin as jenis_kelamin_master_user, master_user.no_hp as no_hp_master_user, master_user.agama as agama_master_user, master_user.alamat as alamat_master_user, master_user.gaji as gaji_master_user, master_user.tempat_lahir as tempat_lahir_master_user, master_user.tanggal_lahir as tanggal_lahir_master_user, master_user.pemotongan as pemotongan_master_user,  master_user.type_pemotongan as type_pemotongan_master_user")
			->from("config_waktu_detail")
			->join("users", "users.id = config_waktu_detail.id_user", "left")
			->join("master_user", "master_user.users_id = config_waktu_detail.id_user", "left")
			->where($where);

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
