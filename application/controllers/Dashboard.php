<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';
class Dashboard extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('absensi_model');
	}
	public function index()
	{
		$id = $this->data['users']->id;
		$this->load->helper('url');
		if ($this->data['users']->id = 1) {
			$users = $this->user_model->getAllByIdWithOutSuperAdmin(array('users.is_deleted' => 0));
		} else {
			$users = $this->user_model->getAllByIdWithOutSuperAdmin(
				array(
					'users.is_deleted' => 0,
					'users.id' => 0
				)
			);
		}
		$grouped_absensi = $this->groupByRoleName($users);
		$get_absen_this_month = $this->absensi_model->getAllByIdDashboard([
			'absensi.is_check_in' => 'check_in',
			'absensi.tanggal_absen >=' => date('Y-m-01'),
			'absensi.tanggal_absen <=' => date('Y-m-t')
		]);

		$total_guru = 0;

		foreach ($grouped_absensi as $role => $user_list) {
			if (stripos($role, 'Guru') !== false) {
				$total_guru += count($user_list);
			}
		}

		$this->data['get_absen_this_month'] = count($get_absen_this_month);
		$this->data['total_guru'] = $total_guru;
		$this->data['grouped_absensi'] = $grouped_absensi;
		$this->data['content'] = 'admin/dashboard';
		$this->load->view('admin/layouts/page', $this->data);
	}
	function groupByRoleName($absensi_data)
	{
		$result = [];

		if (!empty($absensi_data)) {
			foreach ($absensi_data as $row) {
				$role = $row->role_name ?? 'Tanpa Role';
				if (!isset($result[$role])) {
					$result[$role] = [];
				}
				$result[$role][] = $row;
			}
		} else {
			$result = [];
		}
		return $result;
	}


	public function get_user()
	{
		$roles_data = $this->user_model->getUserRolesWithCount();
		echo json_encode($roles_data);
	}
	public function get_absen()
	{
		$id = $this->data['users']->id;
		if ($this->data['users']->id == 1) {
			$get_absen = $this->absensi_model->getAllByIdDashboard([
				'absensi.is_check_in' => 'check_in',
				'absensi.tanggal_absen >=' => date('Y-m-d', strtotime('-7 days'))
			]);
		} else {
			$get_absen = $this->absensi_model->getAllByIdDashboard([
				'absensi.is_check_in' => 'check_in',
				'absensi.id_user' => $id,
				'absensi.tanggal_absen >=' => date('Y-m-d', strtotime('-7 days'))
			]);
		}
		echo json_encode($get_absen);
	}

	public function get_attendance()
	{
		$date = $this->input->get('date');
		$attendance_data = $this->absensi_model->getAttendanceDashboard($date);
		echo json_encode($attendance_data);
	}
}
