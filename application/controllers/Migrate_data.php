<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migrate_data extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->insert_menu();
		$this->insert_function();
		$this->insert_menu_function();
		// $this->insert_users();
		// $this->insert_users_roles();
		// $this->insert_roles();
		// $this->insert_menu_function_master_data();
		$this->delete_data_absen();
		redirect("/");
	}

	function insert_menu()
	{
		$table = 'menu';
		$this->db->truncate($table);

		$data = array(
			array('id' => 1, 'module_id' => 1, 'name' => 'root', 'url' => '#', 'parent_id' => 0, 'icon' => "", 'sequence' => 0, 'description' => 'Root Aplikasi', "show_at" => 0),
			array('id' => 2, 'module_id' => 1, 'name' => 'Dashboard', 'url' => 'dashboard', 'parent_id' => 1, 'icon' => "pe-7s-graph", 'sequence' => 1, 'description' => '', "show_at" => 0),
			array('id' => 3, 'module_id' => 1, 'name' => 'Sistem Akses', 'url' => '#', 'parent_id' => 1, 'icon' => "pe-7s-settings", 'sequence' => 2, 'description' => '', "show_at" => 0),
			array('id' => 4, 'module_id' => 1, 'name' => 'Jabatan', 'url' => 'role', 'parent_id' => 3, 'icon' => "", 'sequence' => 1, 'description' => 'Jabatan', "show_at" => 0),
			array('id' => 5, 'module_id' => 1, 'name' => 'Hak Akses', 'url' => 'privileges', 'parent_id' => 3, 'icon' => "", 'sequence' => 2, 'description' => '', "show_at" => 0),
			array('id' => 6, 'module_id' => 1, 'name' => 'User', 'url' => 'user', 'parent_id' => 1, 'icon' => "pe-7s-users", 'sequence' => 3, 'description' => '', "show_at" => 0),
			array('id' => 7, 'module_id' => 1, 'name' => 'Penggajian', 'url' => 'penggajian', 'parent_id' => 1, 'icon' => "pe-7s-print", 'sequence' => 9, 'description' => '', "show_at" => 0),
			array('id' => 8, 'module_id' => 1, 'name' => 'Absensi', 'url' => 'absensi', 'parent_id' => 1, 'icon' => "pe-7s-portfolio", 'sequence' => 12, 'description' => '', "show_at" => 0),
			array('id' => 9, 'module_id' => 1, 'name' => 'Config Posisi', 'url' => 'config', 'parent_id' => 1, 'icon' => "pe-7s-portfolio", 'sequence' => 13, 'description' => '', "show_at" => 0),
			array('id' => 10, 'module_id' => 1, 'name' => 'Data Absen', 'url' => 'data_absen', 'parent_id' => 1, 'icon' => "pe-7s-portfolio", 'sequence' => 14, 'description' => '', "show_at" => 0),
			array('id' => 11, 'module_id' => 1, 'name' => 'Master Pengguna', 'url' => 'master_user', 'parent_id' => 1, 'icon' => "pe-7s-server", 'sequence' => 7, 'description' => 'Data Guru', "show_at" => 0),

		);
		$this->db->insert_batch($table, $data);
	}

	function insert_function()
	{
		$table = 'function';
		$this->db->truncate($table);

		$data = array(
			array('name' => 'Create', 'description' => 'Can Create'), //1
			array('name' => 'Read', 'description' => 'Can Read'), //2
			array('name' => 'Update', 'description' => 'Can Update'), //3
			array('name' => 'Delete', 'description' => 'Can Delete'), //4
			array('name' => 'Active', 'description' => 'Can Active'), //5
			array('name' => 'Access', 'description' => 'Can Access'), //6
			array('name' => 'Download', 'description' => 'Can Download'), //7
			array('name' => 'Absen Mapel', 'description' => 'Can Absen Mapel'), //8
			array('name' => 'Update Master Pengguna', 'description' => 'Can Update Master Pengguna'), //9
			array('name' => 'Update Mapel', 'description' => 'Can Update Mapel'), //10
			array('name' => 'Create Configuration Periode', 'description' => 'Can Create Configuration Periode'), //11
			array('name' => 'Update Configuration Periode', 'description' => 'Can Update Configuration Periode'), //12
			array('name' => 'Delete Configuration Periode', 'description' => 'Can Delete Configuration Periode'), //13

		);
		$this->db->insert_batch($table, $data);
	}

	function insert_menu_function()
	{
		$table = 'menu_function';
		$this->db->truncate($table);

		$menus = [
			"1" => [2],
			//parent menu
			"2" => [2],
			"3" => [2],

			//Akses Sistem
			"4" => [1, 2, 3, 4],
			"5" => [1, 2, 3, 4],
			"4" => [1, 2, 3, 4],
			"5" => [2, 3],
			"6" => [1, 2, 3, 4],
			"7" => [2],
			"8" => [2],
			"9" => [2, 3],
			"10" => [1, 2, 3, 4, 8],
			"11" => [2, 9, 10, 11, 12, 13],
		];

		$data = [];
		foreach ($menus as $key => $value) {
			for ($i = 0; $i < count($value); $i++) {
				$data[] = [
					"menu_id" => $key,
					"function_id" => $value[$i],
				];
			}
		}

		$this->db->insert_batch($table, $data);
	}

	function insert_users()
	{
		$table = 'users';
		$this->db->truncate($table);

		$data = array(
			array('ip_address' => '127.0.0.1', 'username' => 'administratos', 'password' => '$2y$08$LE4H5hSpdxI5Lnfgt/CjzufLr9x33ZvDTOUA46Q4ZwbKCNQTa6/va', 'salt' => '', 'email' => 'admin@admin.com', 'activation_code' => '', 'forgotten_password_code' => NULL, 'nik' => '11111', 'created_on' => '1268889823', 'last_login' => '1268889823', 'active' => '1', 'first_name' => 'super admin', 'last_name' => '', 'phone' => '0', 'nik' => '99'),
		);
		$this->db->insert_batch($table, $data);
	}

	function insert_users_roles()
	{
		$table = 'users_roles';
		$this->db->truncate($table);

		$data = array(
			array('id' => 1, 'user_id' => '1', 'role_id' => '1'),
			array('id' => 2, 'user_id' => '2', 'role_id' => '1'),
		);
		$this->db->insert_batch($table, $data);
	}

	function insert_roles()
	{
		$table = 'roles';
		$this->db->truncate($table);

		$data = array(
			array('id' => 1, 'name' => 'superadmin', 'description' => 'superadmin'),
			array('id' => 2, 'name' => 'admin', 'description' => 'admin'),
			array('id' => 3, 'name' => 'Guru', 'description' => 'Tenaga Pengajar'),
		);
		$this->db->insert_batch($table, $data);
	}


	function insert_menu_function_master_data()
	{
		$table = 'menu_function';

		$menus = [

			"11" => [1, 2, 3, 4, 5],
		];

		$data = [];
		foreach ($menus as $key => $value) {
			for ($i = 0; $i < count($value); $i++) {
				$data[] = [
					"menu_id" => $key,
					"function_id" => $value[$i],
				];
			}
		}
		$this->db->insert_batch($table, $data);
	}
	function delete_data_absen()
	{
		$table_absen = 'absensi';
		$this->db->truncate($table_absen);
		$table_mapel = 'absensi_mapel';
		$this->db->truncate($table_mapel);
	}
}
