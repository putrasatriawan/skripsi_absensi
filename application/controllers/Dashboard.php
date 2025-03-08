<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'core/Admin_Controller.php';
class Dashboard extends Admin_Controller {
 	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('absensi_model');
	
	}
	public function index()
	{
		$this->load->helper('url');

		$this->data['content'] = 'admin/dashboard';   
		$this->load->view('admin/layouts/page',$this->data); 
	} 	
	
	public function get_user()
	{
		$roles_data = $this->user_model->getUserRolesWithCount();
		echo json_encode($roles_data);
	}

	public function get_attendance()
	{
		$date = $this->input->get('date');
		$attendance_data = $this->absensi_model->getAttendanceDashboard($date);
		echo json_encode($attendance_data);
	}

}