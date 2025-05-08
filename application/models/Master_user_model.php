<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Master_user_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllById($where = array())
	{
		$this->db->select("master_user.*")->from("master_user");
		$this->db->where("master_user.is_deleted", 0);
		$this->db->where($where);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return FALSE;
	}
	public function getAllIdSuperadmin($where = array())
	{
		$this->db->select("master_user.*")->from("master_user");
		$this->db->where("master_user.is_deleted", 0);
		$this->db->where($where);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return FALSE;
	}
	public function get_mapel_detail_with_user()
	{
		$this->db->select("mapel_detail.*, master_user.*, mapel_detail.id as id_mapel")->from("mapel_detail");
		$this->db->join("master_user", "mapel_detail.id_user = master_user.users_id");
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return FALSE;
	}
	public function insert($data)
	{
		$this->db->insert('master_user', $data);
		return $this->db->insert_id();
	}

	public function update($data, $where)
	{
		$this->db->update('master_user', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete($where)
	{
		$this->db->where($where);
		$this->db->delete('master_user');
		if ($this->db->affected_rows()) {
			return TRUE;
		}
		return FALSE;
	}

	function getAllBy($limit, $start, $search, $col, $dir)
	{
		$this->db->select("master_user.*")->from("master_user");

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
		$query = $this->db->get('master_user');  // Assuming the table name is 'master_user'
		return $query->row(); // Returns a single row (object)
	}

	public function getCountAllBy($limit, $start, $search, $order, $dir)
	{
		$this->db->select("master_user.*")->from("master_user");
		if (!empty($search)) {
			foreach ($search as $key => $value) {
				$this->db->or_like($key, $value);
			}
		}
		$result = $this->db->get();
		return $result->num_rows();
	}

	public function getPreviousRecord($currentId)
	{
		$this->db->where('id <', $currentId);
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get('master_user', 1);
		return $query->result();
	}

	public function getNextRecord($currentId)
	{
		$this->db->where('id >', $currentId);
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get('master_user', 1);
		return $query->result();
	}
	public function save_jadwal($dataJadwal, $dataMapel)
	{
		// Cek apakah sudah ada jadwal dengan id_user dan hari yang sama
		$this->db->where('id_user', $dataJadwal['id_user']);
		$this->db->where('hari', $dataJadwal['hari']);
		$cek = $this->db->get('jadwal_mapel');

		if ($cek->num_rows() > 0) {
			// Jika sudah ada, jangan insert lagi
			return false;
		}

		// Jika belum ada, insert data
		$this->db->insert('jadwal_mapel', $dataJadwal);
		$jadwal_id = $this->db->insert_id();

		foreach ($dataMapel as &$mapel) {
			$mapel['jadwal_id'] = $jadwal_id;
		}

		$this->db->insert_batch('mapel_detail', $dataMapel);

		return true;
	}

	public function update_jadwal($id_user, $dataJadwal, $dataMapel)
	{
		$this->db->where('id_user', $id_user);
		$this->db->delete('jadwal_mapel');

		$this->db->insert('jadwal_mapel', $dataJadwal);
		$jadwal_id = $this->db->insert_id();

		foreach ($dataMapel as &$mapel) {
			$mapel['jadwal_id'] = $jadwal_id;
		}
		$this->db->insert_batch('mapel_detail', $dataMapel);

		return true;
	}

	public function delete_existing_config($where)
	{
		// You can use delete or soft delete
		$this->db->where($where);
		$this->db->delete('config_waktu_detail');
	}

	public function insert_config($data)
	{
		$this->db->insert('config_waktu_detail', $data);
	}

	public function get_existing_configs($id_config_master)
	{
		$this->db->where('id_config_master', $id_config_master);
		$this->db->where('is_deleted', 0);
		return $this->db->get('config_waktu_detail')->result();
	}
}
