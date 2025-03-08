<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User_model extends CI_Model
{


	public function __construct()
	{
		parent::__construct();
	}
	public function getAllById($where = array())
	{
		$this->db->select("users.*, roles.id as role_id, roles.name as role_name, unit_kerja.name as unit_kerja_name")->from("users");
		$this->db->join("users_roles", "users.id = users_roles.user_id", 'LEFT');
		$this->db->join('unit_kerja', 'users.unit_kerja = unit_kerja.id', 'left');
		$this->db->join("roles", "roles.id = users_roles.role_id", 'LEFT');
		$this->db->where("users.is_deleted", 0);
		$this->db->where("roles.is_deleted", 0);

		$roles_default = array('1');
		// $this->db->where_not_in('roles.id', $roles_default);
		$this->db->where($where);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return FALSE;
	}
	public function insert($data)
	{
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}
	public function insert_users_roles($data)
	{
		$this->db->insert('users_roles', $data);
		return $this->db->insert_id();
	}

	public function update($data, $where)
	{
		$this->db->update('users', $data, $where);
		return $this->db->affected_rows();
	}

	public function update_user_roles($data, $where)
	{
		$this->db->update('users_roles', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete($where)
	{
		$this->db->where($where);
		$this->db->delete('users');
		if ($this->db->affected_rows()) {
			return TRUE;
		}
		return FALSE;
	}

	public function getPhotoById($user_id)
	{
		$this->db->select('photo');
		$this->db->from('users');
		$this->db->where('id', $user_id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row()->photo;
		}

		return null;
	}


	function getOneBy($where = array())
	{
		$this->db->select("users.*, roles.id as role_id, roles.name as role_name, unit_kerja.name as unit_kerja_name")->from("users");
		$this->db->join("users_roles", "users.id = users_roles.user_id", 'LEFT');
		$this->db->join('unit_kerja', 'users.unit_kerja = unit_kerja.id', 'left');
		$this->db->join("roles", "roles.id = users_roles.role_id", 'LEFT');

		$roles_default = array('1', '2');
		// $this->db->where_not_in('roles.id', $roles_default);

		$this->db->where("users.is_deleted", 0);
		$this->db->where("roles.is_deleted", 0);
		$this->db->where($where);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return FALSE;
	}

	function getRolesById($where = array())
	{
		$this->db->select("users_roles.*, roles.id as role_id, roles.name as role_name")->from("users_roles");
		$this->db->join("users", "users_roles.user_id = users.id", 'LEFT');
		$this->db->join("roles", "roles.id = users_roles.role_id", 'LEFT');

		$this->db->where("users.is_deleted", 0);
		$this->db->where("roles.is_deleted", 0);
		$this->db->where($where);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return FALSE;
	}

	function getOneUserBy($where = array())
	{
		$this->db->select("users.*, roles.id as role_id, roles.name as role_name, unit_kerja.name as unit_kerja_name")->from("users");
		$this->db->join("users_roles", "users.id = users_roles.user_id", 'LEFT');
		// $this->db->join('unit_kerja', 'users.unit_kerja = unit_kerja.id', 'left');
		$this->db->join("roles", "roles.id = users_roles.role_id", 'LEFT');
		$this->db->where('roles.id', 2);

		// $this->db->where("users.is_deleted",0);
		// $this->db->where("roles.is_deleted",0); 
		$this->db->where($where);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		}
		return FALSE;
	}

	public function getAllBy($limit, $start, $search, $col, $dir, $where = [])
	{
		$this->db->select("users.id, users.first_name, users.last_name, users.nik, users.username, users.email, users.phone, users.is_deleted, roles.name as role_name
						   ")->from("users");
		$this->db->join("users_roles", "users.id = users_roles.user_id", 'LEFT');
		$this->db->join("roles", "roles.id = users_roles.role_id", 'LEFT');
		$this->db->limit($limit, $start)->order_by($col, $dir);

		$roles_default = array('1');
		$this->db->where_not_in('roles.id', $roles_default);
		$this->db->where("roles.is_deleted", 0);
		$this->db->where($where);
		if (!empty($search)) {
			$this->db->group_start();
			foreach ($search as $key => $value) {
				$this->db->or_like($key, $value);
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

	public function getCountAllBy($limit, $start, $search, $order, $dir, $where = [])
	{
		$this->db->select("users.id, users.first_name, users.last_name, users.nik, users.username, users.email, users.phone, users.is_deleted, roles.name as role_name
						   ")->from("users");
		$this->db->join("users_roles", "users.id = users_roles.user_id", 'LEFT');
		$this->db->join("roles", "roles.id = users_roles.role_id", 'LEFT');

		$roles_default = array('1');
		$this->db->where_not_in('roles.id', $roles_default);
		$this->db->where("roles.is_deleted", 0);
		$this->db->where($where);
		if (!empty($search)) {
			$this->db->group_start();
			foreach ($search as $key => $value) {
				$this->db->or_like($key, $value);
			}
			$this->db->group_end();
		}

		$result = $this->db->get();

		return $result->num_rows();
	}

	// check data guru & siswa berdasarkan nik
	public function get_by_nik($nik)
	{
		$this->db->select('id');
		$this->db->where('nik', $nik);
		$query = $this->db->get('users');

		if ($query->num_rows() > 0) {
			$result = $query->row();
			return $result->id;
		}
		return 0;
	}

	public function getUserRolesWithCount()
	{
		$this->db->select('roles.name, COUNT(users_roles.role_id) as count');
		$this->db->from('users_roles');
		$this->db->join('roles', 'roles.id = users_roles.role_id');
		$this->db->where('roles.id !=', 1);
		$this->db->group_by('roles.name');

		$query = $this->db->get();
		return $query->result_array();
	}
}
