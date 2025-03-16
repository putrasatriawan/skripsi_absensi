<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';
class Absensi extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('roles_model');
		$this->load->model('absensi_model');
		$this->load->model('config_model');
	}

	public function index()
	{
		$this->load->helper('url');
		$id_user = $this->data['users']->id;
		$role_id = $this->data['users_groups']->id;
		$today = date('Y-m-d');
		$attendance = $this->absensi_model->getAttendanceByDate($id_user, $today);
		
		if ($attendance) {
			$this->data['photo'] = $attendance->photo;
		} else {
			$this->data['photo'] = null;
		}

		$this->data['user_master'] = $this->user_model->getAndMaster(["users.id" => $id_user]);

		$this->data['longitude'] = $this->config_model->get_setting('longitude');
    	$this->data['latitude'] = $this->config_model->get_setting('latitude');

    	$this->data['config_check'] = $this->config_model->get_config_chcek($role_id);

		
	

		if ($this->data['is_can_read']) {
			$this->data['content'] = 'admin/absensi/list_v';
		} else {
			$this->data['content'] = 'errors/html/restrict';
		}

		$this->load->view('admin/layouts/page', $this->data);
	}

	public function init_absensi()
	{
		$latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
		$longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;
		$distance = isset($_POST['distance']) ? $_POST['distance'] : null;
		$check_in_const = isset($_POST['check_in_const']) ? $_POST['check_in_const'] : null;
		$check_out_const = isset($_POST['check_out_const']) ? $_POST['check_out_const'] : null;
		$init_time = isset($_POST['init_time']) ? $_POST['init_time'] : null;
		$status = isset($_POST['status']) ? $_POST['status'] : null;

		if (isset($_POST['photo'])) {
			$photoData = $_POST['photo'];

			$photoData = str_replace('data:image/png;base64,', '', $photoData);
			$photoData = str_replace(' ', '+', $photoData);
		} else {
			echo "Gambar tidak ditemukan!";
		}

		$id_user = $this->data['users']->id;
		$role_id = $this->data['users_groups']->id;
		// $id_role = $roles->role_id;

		$data = array(
			'id_user' => $id_user,
			'tanggal_absen' => date('Y-m-d H:i:s'),
			'photo' => $photoData,
			'id_role' => $role_id,
			'distance' => $distance,
			'check_in_const' => $check_in_const,
			'check_out_const' => $check_out_const,
			'init_time' => $init_time,
			'status' => $status,
			'tanggal_insert' => date('Y-m-d H:i:s'),
			'is_deleted' => 0
		);

		$this->absensi_model->insert($data);
        echo json_encode(array('status' => 'success'));
	}
}
