<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'core/Admin_Controller.php';
class Profile extends Admin_Controller {
 	public function __construct()
	{
		parent::__construct();
		$this->load->model('profile_model');
	}  
	public function index()
	{ 

		if (!empty($_POST))
		{
			if(!empty($this->input->post('profil_pengguna')))
			{
				//print_r($_POST);
			  	// $upload_path = './uploaded/photo_profile/';
				// $prefix_file = $this->data['users']->id;
				// $upload_result = uploadFile('photo', $upload_path, $prefix_file, 1); // 1 indicates image type
				
				// Check if the upload was successful
				if ($upload_result['status']) {
					$data = array(
						'first_name' => $this->input->post('nama_lengkap'),
						'nik' => $this->input->post('nik'),
						'email' => $this->input->post('email'),
						'username' => $this->input->post('user_name'),
						'phone' => $this->input->post('phone'), 
						'address' => $this->input->post('address'),
						// 'photo' => $upload_result['file'], // Update the photo field with the uploaded file name
					); 
					$user_id = $this->input->post('id'); 

					$update = $this->profile_model->update($data, array("id" => $user_id));

					$this->session->set_flashdata('message', "Profile telah diperbaharui");
					redirect("profile", "refresh");
				} else {
					// Handle upload error
					$upload_error = $upload_result['message'];
					$this->session->set_flashdata('upload_error', $upload_error);
					redirect("profile", "refresh");
				}
			}
			else
			{
				$identity = $this->session->userdata('identity');

				$change = $this->ion_auth->change_password($identity, $this->input->post('old_password'), $this->input->post('new_password'));

				if ($change)
				{
					//if the password was successfully changed
					$this->session->set_flashdata('message', 'Password Lama diganti');
					redirect('profile', 'refresh');
				}
				else
				{
					$this->session->set_flashdata('message_error', 'Password lama salah');
					redirect('profile', 'refresh');
				}
			}
		} 
		else
		{
			$data = $this->data['users'];
			$this->data['id'] =   (!empty($data))?$data->id:"";
			$this->data['name'] =   (!empty($data))?$data->first_name:"";
			$this->data['user_name'] =   (!empty($data))?$data->username:"";
			$this->data['email'] =   (!empty($data))?$data->email:""; 
			$this->data['nik'] =   (!empty($data))?$data->nik:""; 
			$this->data['phone'] =   (!empty($data))?$data->phone:""; 
			$this->data['address'] =   (!empty($data))?$data->address:"";  
			$this->data['content'] = 'admin/profile/edit_v'; 
			$this->load->view('admin/layouts/page',$this->data);  

		}    
		
	}  
}