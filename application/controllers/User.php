<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class User extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('kelas_model');
		$this->load->model('guru_model');
		$this->load->model('ion_auth_model');
		$this->load->model("roles_model");
	}

	public function index()
	{
		$this->load->helper('url');

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login'); 
		}

		$this->data['users'] = $this->ion_auth->user()->row();

		if ($this->data['is_can_read']) {
			if ($this->data['is_superadmin']) {
				$this->data['roles'] = $this->roles_model->getAllById();
				$this->data['kelas'] = $this->kelas_model->getAllById();
			}
			$this->data['content'] = 'admin/user/list_v';
		} 
		else {
			$this->data['content'] = 'errors/html/restrict';
		}
		$this->load->view('admin/layouts/page', $this->data);
	}

	public function create()
	{
		$this->form_validation->set_rules('email', "Email", 'trim|required');
		$this->form_validation->set_rules('name', "Nama", 'trim|required');
		$this->form_validation->set_rules('password', "Password", 'trim|required');
		$this->form_validation->set_rules('role_id', "Role", 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$file_name = '';

			$config['upload_path'] = './uploads/photo_profile/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = 100240;
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('photo')) {
				$this->session->set_flashdata('message_error', $this->upload->display_errors());
				return redirect('user/create');
			} 
			else {
				$fileData = $this->upload->data();
				$file_name = $fileData['file_name'];
			}

			$data = array(
				'nik' => $this->input->post('nik'),
				'first_name' => $this->input->post('name'),
				'address' => $this->input->post('address'),
				'active' => 1,
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'photo' =>  $file_name,
				'is_deleted' => 0
			);

			$nik = $this->input->post('nik');
			$role_id = $this->input->post('role_id');
			$password = $this->input->post('password');
			$email = $this->input->post('email');

			$user_id = $this->ion_auth->register($nik, $password, $email, $data, $role_id);

			if ($user_id) {
				$user_role_data = array(
					'user_id' => $user_id,
					'role_id' => $role_id
				);
				$this->user_model->insert_users_roles($user_role_data);

				if ($role_id == 3) {
					$parsing_guru = array(
						'name' => $this->input->post('name'),
						'users_id' => $user_id,
						'nip' => $this->input->post('nik'),
					);
					$this->guru_model->insert($parsing_guru);
				} 
				elseif ($role_id == 4) { // Role ID 4 is for 'Siswa'
					$parsing_siswa = array(
						'nama' => $this->input->post('name'),
						'id_users' => $user_id,
						'nis' => $this->input->post('nik'),
						'kode_kelas_id' => $this->input->post('kelas_id'),
						'jk' => $this->input->post('jk'),
						'alamat' => $this->input->post('address'),
						'ttl' => $this->input->post('ttl'),
						'no_hp' => $this->input->post('no_hp'),
					);
					$this->siswa_model->insert($parsing_siswa);
				}

				$response = array('status' => 'success', 'message' => 'User Berhasil Disimpan!');
				header('Content-Type: application/json');
				echo json_encode($response);
			} 
			else {
				$response = array('status' => 'error', 'message' => 'User Gagal Disimpan!');
				header('Content-Type: application/json');
				echo json_encode($response);
			}
		} 
		else {
			$this->data['roles'] = $this->roles_model->getAllById();
			$this->data['content'] = 'admin/user/create_v';
			$this->load->view('admin/layouts/page', $this->data);
		}
	}


	public function edit($id)
	{
	
		// $this->load->helper('dev');
		$this->form_validation->set_rules('email', "Email", 'trim|required');
		$this->form_validation->set_rules('name', "Nama", 'trim|required');
		$this->form_validation->set_rules('role_id', "Role", 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$data = array(
				'first_name' => $this->input->post('name'),
				'address' => $this->input->post('address'),
				'nik' => $this->input->post('nik'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
			);

			$role_id= $this->input->post('role_id');
			$roles_id = array(
				'role_id' => $this->input->post('role_id'),
			);

			$user_id = $id;
			if ($role_id == 3) {
				$parsing_guru = array(
					
					'name' => $this->input->post('name'),
					'name' => $this->input->post('name'),
					'users_id' => $user_id,
					'nip' => $this->input->post('nik'),
				);
				$update = $this->guru_model->update($parsing_guru, array("users_id" => $id));
			} 

				// echo "<pre>";
				// print_r($parsing_guru);
				// die;
				// foreach ($parsing_guru as $value) {
				// 	echo "<pre>";
				// 	print_r($value);
				// }
				// die;

			if (!empty($_FILES['photo']['name'])) {
				$config['upload_path'] = './uploads/photo_profile/';
				$config['allowed_types'] = 'jpg|jpeg|png|gif';
				$config['max_size'] = 2048;
				$config['file_name'] = time() . '_' . $_FILES['photo']['name'];

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('photo')) {
					$upload_data = $this->upload->data();
					$data['photo'] = $upload_data['file_name'];

					$old_photo = $this->user_model->getPhotoById($user_id);
					if (!empty($old_photo)) {
						unlink('./uploads/photo_profile/' . $old_photo); 
					}
				} 
				else {
					$this->session->set_flashdata('message_error', $this->upload->display_errors());
					return redirect("user/edit/" . $id);
				}
			}

			$this->user_model->update_user_roles($roles_id, array("users_roles.user_id" => $user_id));
			$update = $this->ion_auth->update($user_id, $data);

			if ($update) {
				$response = array('status' => 'success', 'message' => 'User Berhasil Diubah!');
				header('Content-Type: application/json');
				echo json_encode($response);
			} 
			else {
				$response = array('status' => 'error', 'message' => 'User Gagal Diubah!');
				header('Content-Type: application/json');
				echo json_encode($response);
			}
		} 
		else {
			if (!empty($_POST)) {
				$id = $this->input->post('id');
				$this->session->set_flashdata('message_error', validation_errors());
				return redirect("user/edit/" . $id);
			} 
			else {
				$this->data['id'] = $id;
				$data = $this->user_model->getOneBy(array("users.id" => $this->data['id']));

				if (empty($data)) {
					$this->session->set_flashdata('message_error', 'User not found');
					return redirect('user');
				}

				$this->data['photo'] = (!empty($data)) ? $data->photo : "";

				$this->data['roles'] = $this->roles_model->getAllById();
				$this->data['kelas'] = $this->kelas_model->getAllById();

				$this->data['first_name'] = $data->first_name;
				$this->data['last_name'] = $data->last_name;
				$this->data['username'] = $data->username;
				$this->data['address'] = $data->address;
				$this->data['email'] = $data->email;
				$this->data['nik'] = $data->nik;
				$this->data['phone'] = $data->phone;
				$this->data['role_id'] = $data->role_id;

				$this->data['photo'] = (!empty($data->photo)) ? $data->photo : "";

				$this->data['content'] = 'admin/user/edit_v';
				$this->load->view('admin/layouts/page', $this->data);
			}
		}
	}

	public function dataList()
	{
		$columns = array(
			0 => 'id',
			1 => 'role_name',
			2 => 'users.first_name',
			3 => 'users.nik',
			4 => 'action'
		);

		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];
		$search = array();
		$where = array("roles.id >" => "1");
		$limit = 0;
		$start = 0;
		$totalData = $this->user_model->getCountAllBy($limit, $start, $search, $order, $dir, $where);

		$searchColumn = $this->input->post('columns');
		$isSearchColumn = false;

		if (!empty($searchColumn[1]['search']['value'])) {
			$value = $searchColumn[1]['search']['value'];
			$isSearchColumn = true;
			$where['roles.name'] = $value;
		}

		if (!empty($this->input->post('search')['value'])) {
			$isSearchColumn = true;
			$search_value = $this->input->post('search')['value'];
			$search = array(
				"users.first_name" => $search_value,
				"users.nik" => $search_value
			);
		}
		if ($isSearchColumn) {
			$totalFiltered = $this->user_model->getCountAllBy($limit, $start, $search, $order, $dir, $where);
		} 
		else {
			$totalFiltered = $totalData;
		}
		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$datas = $this->user_model->getAllBy($limit, $start, $search, $order, $dir, $where);

		$new_data = array();
		if (!empty($datas)) {
			foreach ($datas as $key => $data) {

				$edit_url = "";
				$delete_url = "";
				$delete_url_hard = "";
				$reset_p_url = "";

				if ($this->data['is_can_edit'] && $data->is_deleted == 0) {
					$edit_url = "<a href='" . base_url() . "user/edit/" . $data->id . "' class='btn btn-sm white btn-info'><i class='fas fa-edit'></i> Ubah</a>";
					$reset_p_url = "<a href='#' class='btn btn-sm btn-primary reset_password' data-id='" . $data->id . "'  url='" . base_url() . "user/reset_password/" . $data->id . "/1'><i class='fas fa-pencil-alt me-2'></i> Reset Password</a>";
				}
				if ($this->data['is_can_delete']) {
					if ($data->is_deleted == 0) {
						$delete_url = "<a href='#' 
	        				url='" . base_url() . "user/destroy/" . $data->id . "/" . $data->is_deleted . "'
	        				class='btn btn-sm white btn-danger delete' ><i class='fas fa-times'></i> NonAktifkan
	        				</a>";
					} 
					else {
						$delete_url = "<a href='#' 
	        				url='" . base_url() . "user/destroy/" . $data->id . "/" . $data->is_deleted . "'
	        				class='btn btn-sm btn-primary white delete' 
	        				 ><i class='fas fa-check'></i> Aktifkan
	        				</a>";
						$delete_url_hard = "<a href='#' 
	        				url='" . base_url() . "user/destroy_hard/" . $data->id . "/" . $data->is_deleted . "'
	        				class='btn btn-sm btn-danger white delete' 
	        				 ><i class='fas fa-trash'></i> Delete 
	        				</a>";
					}
				}

				$nestedData['id'] = $start + $key + 1;
				$nestedData['role_name'] = $data->role_name;
				$nestedData['name'] = $data->first_name . ' ' . $data->last_name;
				$nestedData['nik'] = $data->nik;
				$nestedData['action'] = $edit_url . " " . $delete_url . " " . $delete_url_hard . " " . $reset_p_url;
				$new_data[] = $nestedData;
			}
		}

		$json_data = array(
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data" => $new_data
		);
		echo json_encode($json_data);
		$this->data['photo'] = (!empty($data->photo)) ? $data->photo : "";
	}

	public function destroy()
	{
		$response_data = array();
		$response_data['status'] = false;
		$response_data['msg'] = "";
		$response_data['data'] = array();

		$id = $this->uri->segment(3);
		$is_deleted = $this->uri->segment(4);
		if (!empty($id)) {
			$this->load->model("user_model");
			$data = array(
				'is_deleted' => ($is_deleted == 1) ? 0 : 1
			);
			$update = $this->user_model->update($data, array("id" => $id));

			$response_data['data'] = $data;
			$response_data['status'] = true;
		} 
		else {
			$response_data['msg'] = "ID Harus Diisi";
		}
		echo json_encode($response_data);
	}

	public function destroy_hard()
	{
		$response_data = array();
		$response_data['status'] = false;
		$response_data['msg'] = "";
		$response_data['data'] = array();

		$id = $this->uri->segment(3);
		$is_deleted = $this->uri->segment(4);
		if (!empty($id)) {
			$this->load->model("roles_model");
			$data = array(
				'is_deleted' => ($is_deleted == 1) ? 0 : 1
			);
			$update = $this->user_model->delete(array("id" => $id));

			$response_data['data'] = $data;
			$response_data['status'] = true;
		} 
		else {
			$response_data['msg'] = "ID Harus Diisi";
		}
		echo json_encode($response_data);
	}

	public function reset_password($id, $status)
	{
		$response_data = array();
		$response_data['status'] = false;
		$response_data['msg'] = "";
		$response_data['data'] = array();

		if (!empty($id)) {
			$where = ['users.id' => $id];
			$password = $this->generate_password();
			$data = array(
				'password' => $password['hash_password'],
			);
			$update = $this->user_model->update($data, $where);
			$response_data['data'] = $data;
			$response_data['msg'] = "Sukses Menonaktifkan Data";
			$response_data['status'] = true;
		} 
		else {
			$response_data['msg'] = "ID Kosong";
		}
		echo json_encode($response_data);
	}

	public function generate_password()
	{
		$password = 0;
		$this->load->library('Bcrypt');

		$password = "AkhlakPTSI01";
		$hash_password = $this->bcrypt->hash($password);
		$data = array(
			"password" => $password,
			"hash_password" => $hash_password,
		);
		return $data;
	}

	public function get_template_url()
	{
		$role = $this->input->post('role');

		if ($role == 3) {
			$templateUrl = base_url('user/generate_excel_guru');
		} 
		elseif ($role == 4) {
			$templateUrl = base_url('user/generate_excel_siswa');
		} 
		else {
			$templateUrl = '';
		}
		echo json_encode(['templateUrl' => $templateUrl]);
	}

	public function import_data()
	{
		$this->load->library('upload');
		$this->load->library('Bcrypt');
		$role = $this->input->post('role_id');

		$upload_path_siswa = FCPATH . './uploads/siswa_upload';
		$upload_path_guru = FCPATH . './uploads/guru_upload';

		if (!is_dir($upload_path_siswa)) {
			mkdir($upload_path_siswa, 0755, true);
		}

		if (!is_dir($upload_path_guru)) {
			mkdir($upload_path_guru, 0755, true);
		}

		$config_guru = [
			'upload_path'   => $upload_path_guru,
			'allowed_types' => 'xlsx',
			'file_name'     => 'imported-data-guru' . time(),
		];

		$config_siswa = [
			'upload_path'   => $upload_path_siswa,
			'allowed_types' => 'xlsx',
			'file_name'     => 'imported-data-siswa' . time(),
		];

		$insert_count = 0;
		$error_occurred = false;

		if ($role == 3) {
			$this->upload->initialize($config_guru);

			if (!$this->upload->do_upload('userfile')) {
				echo json_encode(['status' => 'error', 'message' => $this->upload->display_errors('', '')]);
				return;
			}

			$file = $this->upload->data();
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file['full_path']);
			$data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

			foreach ($data as $key => $row) {
				if ($key < 6) continue;
				if (!empty($row['B']) && !empty($row['C']) && !empty($row['E']) && !empty($row['G']) && !empty($row['H']) && !empty($row['I']) && !empty($row['J']) && !empty($row['K']) && !empty($row['L'])) {
					$existing_data = $this->user_model->get_by_nik($row['B']);
					$password_hash = $this->bcrypt->hash($row['E']);
					if (!$existing_data) {
						$insert_user_data_guru = [
							'nik' => $row['B'],
							'username' => $row['D'],
							'password' => $password_hash,
							'first_name' => $row['C'],
							'address' => $row['I'],
							'phone' => $row['G'],
							'is_deleted' => 0,
						];

						$user_id_guru = $this->user_model->insert($insert_user_data_guru);
						if ($user_id_guru) {
							$this->user_model->insert_users_roles(['user_id' => $user_id_guru, 'role_id' => 3]);

							$insert_guru_data = [
								'users_id' => $user_id_guru,
								'nip' => $row['B'],
								'name' => $row['C'],
								'jenis_kelamin' => $row['F'],
								'no_hp' => $row['G'],
								'agama' => $row['H'],
								'alamat' => $row['I'],
								'gaji' => $row['J'],
								'tempat_lahir' => $row['K'],
								'tanggal_lahir' => $row['L'],
							];
							$this->guru_model->insert($insert_guru_data);
							$insert_count++;
						}
					}
				} 
				else {
					$error_occurred = true;
					echo json_encode(['status' => 'error', 'message' => 'Guru tidak diperbolehkan mengimpor data siswa.']);
					unlink($file['full_path']);
					return;
				}
			}
			unlink($file['full_path']);
		} 
		elseif ($role == 4) {
			$this->upload->initialize($config_siswa);
			if (!$this->upload->do_upload('userfile')) {
				echo json_encode(['status' => 'error', 'message' => $this->upload->display_errors('', '')]);
				return;
			}

			$file = $this->upload->data();
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file['full_path']);
			$data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

			foreach ($data as $key => $row) {
				if ($key < 6 || empty($row['B'])) continue;
				if (!empty($row['B']) && !empty($row['C']) && !empty($row['E']) && !empty($row['G']) && !empty($row['H'])) {
					$existing_data = $this->user_model->get_by_nik($row['B']);
					$password_hash = $this->bcrypt->hash($row['E']);
					if (!$existing_data) {
						$insert_user_data_siswa = [
							'nik' => $row['B'],
							'username' => $row['D'],
							'password' => $password_hash,
							'first_name' => $row['C'],
							'address' => $row['I'],
							'phone' => $row['J'],
							'is_deleted' => 0,
						];

						$user_id_siswa = $this->user_model->insert($insert_user_data_siswa);
						if ($user_id_siswa) {
							$this->user_model->insert_users_roles(['user_id' => $user_id_siswa, 'role_id' => 4]);
							$checkKelas = $this->kelompok_kelas_model->check_class($row['F']);

							$insert_siswa_data = [
								'id_users' => $user_id_siswa,
								'nis' => $row['B'],
								'nama' => $row['C'],
								'kode_kelas_id' => $checkKelas,
								'jk' => $row['G'],
								'ttl' => $row['H'],
								'alamat' => $row['I'],
								'no_hp' => $row['J'],
							];

							$this->siswa_model->insert($insert_siswa_data);
							$insert_count++;
						}
					}
				} 
				else {
					$error_occurred = true;
					echo json_encode(['status' => 'error', 'message' => 'Siswa tidak diperbolehkan mengimpor data guru.']);
					unlink($file['full_path']);
					return;
				}
			}
			unlink($file['full_path']);
		}

		if (!$error_occurred && $insert_count > 0) {
			echo json_encode(['status' => 'success', 'message' => 'Import data berhasil! Total data yang diimpor: ' . $insert_count]);
		} 
		else {
			if (!$error_occurred) {
				echo json_encode(['status' => 'error', 'message' => 'Siswa tidak diperbolehkan mengimpor data guru.']);
			}
		}
	}

	public function generate_excel_siswa()
	{
		$spreadsheet = new Spreadsheet();

		$spreadsheet->getProperties()->setCreator("Your Name")
			->setLastModifiedBy("Your Name")
			->setTitle("Data Siswa")
			->setSubject("Data Siswa")
			->setDescription("Generated Siswa data.")
			->setKeywords("siswa data excel")
			->setCategory("Exported Data");


		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'Data Siswa')
			->setCellValue('A4', 'No')
			->setCellValue('B4', 'NIS')
			->setCellValue('C4', 'Nama')
			->setCellValue('D4', 'Username')
			->setCellValue('E4', 'Password')
			->setCellValue('F4', 'Kode Kelas')
			->setCellValue('G4', 'Jenis Kelamin')
			->setCellValue('H4', 'Tempat Tanggal Lahir')
			->setCellValue('I4', 'Alamat')
			->setCellValue('J4', 'No HP');

		$sheet->mergeCells('A1:C2');
		$sheet->mergeCells('A4:A5');
		$sheet->mergeCells('B4:B5');
		$sheet->mergeCells('C4:C5');
		$sheet->mergeCells('D4:D5');
		$sheet->mergeCells('E4:E5');
		$sheet->mergeCells('F4:F5');
		$sheet->mergeCells('G4:G5');
		$sheet->mergeCells('H4:H5');
		$sheet->mergeCells('I4:I5');
		$sheet->mergeCells('J4:J5');

		$sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A4:J5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1')->getFont()->setBold(true);
		$sheet->getStyle('A4:J4')->getFont()->setBold(true);
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A4:J4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		$kelompokKelasList = $this->kelompok_kelas_model->getAllById();

		$sheet->setCellValue('A6', "1")
			->setCellValue('B6', "13153113")
			->setCellValue('C6', "Orizz")
			->setCellValue('D6', "Orizzz")
			->setCellValue('E6', "Admin123")
			->setCellValue('F6', "10-A-IPA-2025")
			->setCellValue('G6', "Laki-laki")
			->setCellValue('H6', "Toronto, 14 Februari 2003")
			->setCellValue('I6', "Pasir Impun")
			->setCellValue('J6', "081234567890");

		if (!empty($kelompokKelasList)) {
			$kodeKelasValues = array_map(function ($item) {
				return $item->kode_kelas;
			}, $kelompokKelasList);

			$kodeKelasRange = 'L1:L' . (count($kodeKelasValues) + 1);

			foreach ($kodeKelasValues as $index => $value) {
				$sheet->setCellValue('L' . ($index + 1), $value);
			}

			$validation = $sheet->getCell('F6')->getDataValidation();
			$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
				->setFormula1('=L1:L' . count($kodeKelasValues))
				->setAllowBlank(true)
				->setShowDropDown(true)
				->setShowErrorMessage(true);
		}

		$jenisKelaminValues = ['Laki-laki', 'Perempuan'];

		$jenisKelaminRange = 'M1:M' . (count($jenisKelaminValues) + 1);

		foreach ($jenisKelaminValues as $index => $value) {
			$sheet->setCellValue('M' . ($index + 1), $value);
		}

		$validation = $sheet->getCell('G6')->getDataValidation();
		$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
			->setFormula1('=M1:M' . count($jenisKelaminValues))
			->setAllowBlank(true)
			->setShowDropDown(true)
			->setShowErrorMessage(true);

		$sheet->getColumnDimension('A')->setWidth(10);
		$sheet->getColumnDimension('B')->setWidth(15);
		$sheet->getColumnDimension('C')->setWidth(25);
		$sheet->getColumnDimension('D')->setWidth(20);
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->getColumnDimension('F')->setWidth(30);
		$sheet->getColumnDimension('G')->setWidth(30);
		$sheet->getColumnDimension('H')->setWidth(15);
		$sheet->getColumnDimension('I')->setWidth(15);
		$sheet->getColumnDimension('J')->setWidth(15);

		$sheet->setTitle('Data Siswa');

		$spreadsheet->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="siswa_data.xlsx"');
		header('Cache-Control: max-age=0');

		$writer  = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	public function generate_excel_guru()
	{
		$spreadsheet = new Spreadsheet();

		$spreadsheet->getProperties()->setCreator("Your Name")
			->setLastModifiedBy("Your Name")
			->setTitle("Data Guru")
			->setSubject("Data Guru")
			->setDescription("Generated Guru data.")
			->setKeywords("guru data excel")
			->setCategory("Exported Data");


		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'Data Guru')
			->setCellValue('A4', 'No')
			->setCellValue('B4', 'NIP')
			->setCellValue('C4', 'Nama')
			->setCellValue('D4', 'Username')
			->setCellValue('E4', 'Password')
			->setCellValue('F4', 'Jenis Kelamin')
			->setCellValue('G4', 'No HP')
			->setCellValue('H4', 'Agama')
			->setCellValue('I4', 'Alamat')
			->setCellValue('J4', 'Gaji')
			->setCellValue('K4', 'Tempat Lahir')
			->setCellValue('L4', 'Tanggal Lahir');

		$sheet->mergeCells('A1:C2');
		$sheet->mergeCells('A4:A5');
		$sheet->mergeCells('B4:B5');
		$sheet->mergeCells('C4:C5');
		$sheet->mergeCells('D4:D5');
		$sheet->mergeCells('E4:E5');
		$sheet->mergeCells('F4:F5');
		$sheet->mergeCells('G4:G5');
		$sheet->mergeCells('H4:H5');
		$sheet->mergeCells('I4:I5');
		$sheet->mergeCells('J4:J5');
		$sheet->mergeCells('K4:K5');
		$sheet->mergeCells('L4:L5');

		$sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A4:L5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1')->getFont()->setBold(true);
		$sheet->getStyle('A4:L4')->getFont()->setBold(true);
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A4:L4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		$kelompokKelasList = $this->kelompok_kelas_model->getAllById();

		$sheet->setCellValue('A6', "1")
			->setCellValue('B6', "12153443")
			->setCellValue('C6', "Asep")
			->setCellValue('D6', "AsepSiKasep")
			->setCellValue('E6', "password")
			->setCellValue('F6', "Laki-laki")
			->setCellValue('G6', "081234567890")
			->setCellValue('H6', "Islam")
			->setCellValue('I6', "Legok Badak")
			->setCellValue('J6', "8000000")
			->setCellValue('K6', "Sumedang")
			->setCellValue('L6', "300503");

		$jenisKelaminValues = ['Laki-laki', 'Perempuan'];

		$jenisKelaminRange = 'O1:O' . (count($jenisKelaminValues) + 1);

		foreach ($jenisKelaminValues as $index => $value) {
			$sheet->setCellValue('O' . ($index + 1), $value);
		}

		$validation = $sheet->getCell('F6')->getDataValidation();
		$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
			->setFormula1('=O1:O' . count($jenisKelaminValues))
			->setAllowBlank(true)
			->setShowDropDown(true)
			->setShowErrorMessage(true);

		$sheet->getColumnDimension('A')->setWidth(10);
		$sheet->getColumnDimension('B')->setWidth(15);
		$sheet->getColumnDimension('C')->setWidth(25);
		$sheet->getColumnDimension('D')->setWidth(20);
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->getColumnDimension('F')->setWidth(15);
		$sheet->getColumnDimension('G')->setWidth(30);
		$sheet->getColumnDimension('H')->setWidth(20);
		$sheet->getColumnDimension('I')->setWidth(15);
		$sheet->getColumnDimension('J')->setWidth(15);
		$sheet->getColumnDimension('K')->setWidth(15);
		$sheet->getColumnDimension('L')->setWidth(15);

		$sheet->setTitle('Data Guru');

		$spreadsheet->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="guru_data.xlsx"');
		header('Cache-Control: max-age=0');

		$writer  = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	public function save_photo_profile()
	{
		$this->load->library('upload');
		$user_id = $this->input->post('insert_id'); // Ensure you have the user ID

		if (!is_dir("./uploads/")) {
			mkdir("./uploads/");
		}

		$location_path = "./uploads/photo_profile/";
		if (!is_dir($location_path)) {
			mkdir($location_path);
		}

		$config['upload_path'] = $location_path;
		$config['allowed_types'] = 'jpg|jpeg|png|gif';
		$config['file_name'] = time();

		$this->upload->initialize($config);

		if ($this->upload->do_upload('photo')) {
			$upload_data = $this->upload->data();
			$data_foto = [
				'photo' => $upload_data['file_name'],
			];

			$update = $this->user_model->update($data_foto, ['users.id' => $user_id]);
			if ($update) {
				$response_data['status'] = true;
				$response_data['message'] = "Berhasil Menyimpan User";
			} 
			else {
				$response_data['status'] = false;
				$response_data['message'] = "Berhasil Menyimpan User, Tapi Gagal Menyimpan Foto";
			}
		} 
		else {
			$response_data['status'] = false;
			$response_data['message'] = $this->upload->display_errors();
		}
		echo json_encode($response_data);
	}

	public function update_photo_profile()
	{
		$this->load->library('upload');
		$karyawan_id = $this->input->post('id');
		$data_foto = array();
		if (!is_dir("./uploads/")) {
			mkdir("./uploads/");
		}

		$location_path = "./uploads/photo_profile/";
		if (!is_dir($location_path)) {
			mkdir($location_path);
		}

		$data_foto = $this->user_model->getOneBy(['users.id' => $karyawan_id]);
		if ($data_foto) {
			$photo = $data_foto->photo;
			if ($photo) {
				if (file_exists("./uploads/photo_profile/" . $photo)) {
					unlink("./uploads/photo_profile/" . $photo);
				}
			}
		}

		$_FILES['file']['name'] = $_FILES['attachment']['name'][0];
		$_FILES['file']['type'] = $_FILES['attachment']['type'][0];
		$_FILES['file']['tmp_name'] = $_FILES['attachment']['tmp_name'][0];
		$_FILES['file']['error'] = $_FILES['attachment']['error'][0];
		$_FILES['file']['size'] = $_FILES['attachment']['size'][0];
		$config['upload_path'] = $location_path;
		$config['allowed_types'] = '*';
		$config['file_name'] = time();

		$this->upload->initialize($config);
		if ($this->upload->do_upload('file')) {
			$upload_data = $this->upload->data();
			$data_foto = [
				'photo' => $this->upload->data('file_name'),
			];
		}

		$update = $this->user_model->update($data_foto, ['users.id' => $karyawan_id]);
		if ($update) {
			$response_data['status'] = true;
			$response_data['message'] = "Berhasil Menyimpan User";
		} 
		else {
			$response_data['status'] = false;
			$response_data['message'] = "Berhasil Menyimpan User, Tapi Gagal Menyimpan Foto";
		}
		echo json_encode($response_data);
	}

	public function removeImages()
	{
		$response_data = array();
		$id = $this->input->post('id');
		$update = 0;
		$data_karyawan = $this->user_model->getOneBy(['users.id' => $id]);

		if ($data_karyawan) {
			$photo = $data_karyawan->photo;
			if ($photo) {
				if ($photo) {
					if (file_exists("./uploads/photo_profile/" . $photo)) {
						unlink("./uploads/photo_profile/" . $photo);
					}

					$data_update = array(
						'photo' => null
					);
					$update = $this->user_model->update($data_update, ['users.id' => $id]);
				}
			}
		}

		if ($update) {
			$response_data['status'] = true;
			$response_data['message'] = 'Berhasil Menghapus Foto';
		} 
		else {
			$response_data['status'] = false;
			$response_data['message'] = 'Gagal Menghapus Foto';
		}
		echo json_encode($response_data);
	}

	public function profile()
	{
		// Pastikan pengguna sedang login
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}

		// Dapatkan data pengguna yang sedang login
		$this->data['users'] = $this->ion_auth->user()->row();

		// Load tampilan profile dengan data pengguna
		$this->data['content'] = 'user/profile';
		$this->load->view('layouts/page', $this->data);
	}
}
