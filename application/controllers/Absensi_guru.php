<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';
class Absensi_guru extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('roles_model');
		$this->load->model('absensi_guru_model');
		$this->load->model('config_model');
	}

	public function index()
	{
		$this->load->helper('url');
		$id_user = $this->data['users']->id;
		$today = date('Y-m-d');

		$attendance = $this->absensi_guru_model->getAttendanceByDate($id_user, $today);

		if ($attendance) {
			$this->data['photo'] = $attendance->photo;
		} else {
			$this->data['photo'] = null;
		}

		$this->data['longitude'] = $this->config_model->get_setting('longitude');
    	$this->data['latitude'] = $this->config_model->get_setting('latitude');

		if ($this->data['is_can_read']) {
			$this->data['content'] = 'admin/absensi_guru/list_v';
		} else {
			$this->data['content'] = 'errors/html/restrict';
		}

		$this->load->view('admin/layouts/page', $this->data);
	}

	public function uploads()
	{

		// dd($_POST);
		// var_dump($_POST);die;
		$latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
		$longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;

		if (isset($_POST['photo'])) {
			$photoData = $_POST['photo'];

			$photoData = str_replace('data:image/png;base64,', '', $photoData);
			$photoData = str_replace(' ', '+', $photoData);
		} else {
			echo "Gambar tidak ditemukan!";
		}

		$id_user = $this->data['users']->id;
		$roles = $this->user_model->getRolesById(array("users_roles.user_id" => $id_user));
		// $id_role = $roles->role_id;

		$data = array(
			'id_user' => $id_user,
			'tanggal_absen' => date('Y-m-d H:i:s'),
			'photo' => $photoData,
			'id_role' => "1",
			'tanggal_insert' => date('Y-m-d H:i:s'),
			'is_deleted' => 0
		);

		$this->absensi_guru_model->insert($data);
        echo json_encode(array('status' => 'success'));
	}
}
