<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';
class Profile extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('profile_model');
	}
	public function index()
	{
		if (!empty($_POST)) {
			// Pengecekan apakah profil atau password yang diperbaharui
			if (!empty($this->input->post('profil_pengguna'))) {
				// Upload photo
				$upload_path = './uploads/photo_profile/';
				$prefix_file = $this->data['users']->id;
				$upload_result = $this->upload_photo($upload_path, $prefix_file);
				// Data untuk update profil pengguna
				$data = array(
					'first_name' => $this->input->post('nama_lengkap'),
					'email' => $this->input->post('email'),
					'photo' => $upload_result['file'] ?? $this->data['users']->photo
				);

				$user_id = $this->input->post('id');
				$update = $this->profile_model->update($data, array("id" => $user_id));

				$this->session->set_flashdata('message', "Profile telah diperbaharui");
				redirect("profile", "refresh");
			} else {
				// Password change process
				$identity = $this->session->userdata('identity');
				$change = $this->ion_auth->change_password($identity, $this->input->post('old_password'), $this->input->post('new_password'));

				if ($change) {
					$this->session->set_flashdata('message', 'Password Lama diganti');
					redirect('profile', 'refresh');
				} else {
					$this->session->set_flashdata('message_error', 'Password lama salah');
					redirect('profile', 'refresh');
				}
			}
		} else {
			// Get user data to populate profile form
			$data = $this->data['users'];
			$this->data['id'] = $data->id ?? "";
			$this->data['name'] = $data->first_name ?? "";
			$this->data['email'] = $data->email ?? "";
			$this->data['content'] = 'admin/profile/edit_v';
			$this->load->view('admin/layouts/page', $this->data);
		}
	}

	// Function to handle file upload
	private function upload_photo($upload_path, $prefix_file)
	{
		$this->load->library('upload');

		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['file_name'] = $prefix_file;

		$this->upload->initialize($config);

		if ($this->upload->do_upload('photo')) {
			$upload_data = $this->upload->data();
			return [
				'status' => true,
				'file' => $upload_data['file_name']
			];
		} else {
			return [
				'status' => false,
				'message' => $this->upload->display_errors()
			];
		}
	}
}
