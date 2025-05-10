<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
	
class Data_absen extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		$this->load->model('absensi_model');
		$this->load->model('user_model');
	}

	public function index()
	{
		$this->load->helper('url');
		if ($this->data['is_can_read']) {
			$this->data['content'] = 'admin/data_absen/list_v';
		} else {
			$this->data['content'] = 'errors/html/restrict';
		}

		$this->data['user'] = $this->user_model->getAllById(array('users.is_deleted' => 0));		
		$this->load->view('admin/layouts/page', $this->data);
	}

	public function dataList()
	{
		$columns = array(
			0 => 'id',
			1 => 'tanggal_absen',
			2 => 'nama_users',
			3 => 'check_in',
			4 => 'check_out',
			5 => 'status_kerja',
			6 => 'status',
			7 => 'foto',
			8 => '',
		);
		$where = [
			'absensi.id_users' => $this->data['users']->id, 
		];
		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];
		$search = array();
		$limit = 0;
		$start = 0;
		$totalData = $this->absensi_model->getCountAllBy($limit, $start, $search, $order, $dir, $where);
		if (!empty($this->input->post('search')['value'])) {
			// $isSearchColumn = true;
			$search_value = $this->input->post('search')['value'];
			// $search = array(
			// 	"kelompok_kelas.kode_kelas" => $search_value,
			// );
			//    	 }
			// if($isSearchColumn){
			$totalFiltered = $this->absensi_model->getCountAllBy($limit, $start, $search, $order, $dir, $where);
		} else {
			$totalFiltered = $totalData;
		}

		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$datas = $this->absensi_model->getAllBy($limit, $start, $search, $order, $dir);
	
		$mapel = array();
		$new_data = array();
		if (!empty($datas)) {

			foreach ($datas as $key => $data) {

				$mapel = $this->absensi_model->getMapelTerdekatNotOne($data->id_user);
				
				$edit_url = "";
				$absen_mapel_url = "";

				if ($this->data['is_can_edit'] && $data->is_deleted == 0) {
					$edit_url = "<button class='btn btn-sm btn-info white edit-button' 
									data-id='{$data->id}' 
									data-nama_user='{$data->nama_user}' 
									data-init_time='{$data->init_time}' 
									data-status_work='{$data->status_work}' 
									data-is_check_in='{$data->is_check_in}' 
									data-status='{$data->status}' 
									data-photo='{$data->photo}'>
									<i class='fas fa-edit'></i> Ubah
								</button>";
				}
				
				
				if ($this->data['is_can_read'] && $data->is_deleted == 0) {
					$detail_url = "<button class='btn btn-sm btn-warning white detail-button' 
									data-id='{$data->id}' 
									data-nama_user='{$data->nama_user}' 
									data-init_time='{$data->init_time}' 
									data-status_work='{$data->status_work}' 
									data-is_check_in='{$data->is_check_in}' 
									data-status='{$data->status}' 
									data-photo='{$data->photo}'>
									<i class='fas fa-eye'></i> Detail
								</button>";
				}
				
				if ($this->data['is_can_read'] && $data->is_deleted == 0 && $data->is_check_in != 'check_out') {
					$absen_mapel_url = "<button class='btn btn-sm btn-warning white absen-mapel-button' 
									data-id='{$data->id}' 
									data-nama_user='{$data->nama_user}' 
									data-init_time='{$data->init_time}' 
									data-status_work='{$data->status_work}' 
									data-is_check_in='{$data->is_check_in}' 
									data-status='{$data->status}' 
									data-photo='{$data->photo}'>
									<i class='fas fa-eye'></i> Absen Mapel
								</button>";
				}
				

				if (!empty($data->photo)) {
					$photo = '<img src="data:image/jpeg;base64,' . $data->photo . '" alt="Foto" width="150" height="auto">';
				} else {
					$photo = '<img src="' . base_url('assets/images/default.png') . '" alt="Foto" width="200" height="200">';
				}
				
				$status = $data->status;
				$statusButton = '';

				switch ($status) {
					case 'Tepat Waktu':
						$statusButton = '<button class="mb-2 mr-2 btn-pill btn btn-success">Tepat Waktu</button>';
						break;
					case 'Terlambat':
						$statusButton = '<button class="mb-2 mr-2 btn-pill btn btn-danger">Terlambat</button>';
						break;
					case 'Pulang':
						$statusButton = '<button class="mb-2 mr-2 btn-pill btn btn-secondary">Pulang</button>';
						break;
					default:
						$statusButton = '<button class="mb-2 mr-2 btn-pill btn btn-dark">Tidak Diketahui</button>';
						break;
				}

				$nestedData['id'] = $start + $key + 1;
				$nestedData['tanggal_absen'] = formatTanggal($data->tanggal_absen);
				$nestedData['users_name'] = $data->nama_user;
				
				if ($data->is_check_in == 'check_in') {
					$nestedData['check_in'] = $data->init_time;
					$nestedData['check_out'] = '-'; 
				} else {
					$nestedData['check_in'] = '-'; 
					$nestedData['check_out'] = $data->init_time;
				}
				
				$nestedData['jarak'] = $data->distance;
				$nestedData['status_kerja'] = $data->status_work;
				$nestedData['status'] = $statusButton;
				$nestedData['foto'] = $photo;
				$nestedData['action'] = $edit_url . " " . $detail_url . " " . $absen_mapel_url ;
				$new_data[] = $nestedData;
				
			}
		}

	echo "<pre>";
				print_r($mapel);
				die;
				foreach ($mapel as $value) {
					echo "<pre>";
					print_r($value);
				}
				die;
		
		$json_data = array(
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data" => $new_data
		);

		echo json_encode($json_data);
	}

	public function update()
	{
		$id = $this->input->post('id');
		$init_time = $this->input->post('init_time');
		$status_work = $this->input->post('status_work');
		$status = $this->input->post('status');
		$is_check_in = $this->input->post('is_check_in');

		$data = array(
			'init_time' => $init_time . ':00',
			'status_work' => $status_work,
			'status' => $status,
		);

		$where = array('id' => $id); // Kondisi where untuk update data

		$this->absensi_model->update($data, $where); // Format update($data, $where) sesuai dengan yang kamu gunakan
		$this->session->set_flashdata('message', 'Data berhasil diubah.');
		redirect('data_absen');
	}

	public function create()
	{
		$nama_user = $this->input->post('nama_user');
		$tgl_absen = $this->input->post('tgl_absen');
		$init_time = $this->input->post('init_time');
		$status_work = $this->input->post('status_work');
		$status = $this->input->post('status');

		if (!empty($nama_user)) {
			list($id_user, $id_role) = explode('|', $nama_user);
		} else {
			$id_user = null;
			$id_role = null;
		}

		$data = array(
			'id_user' => $id_user,
			'id_role' => $id_role,
			'tanggal_absen' => $tgl_absen,
			'init_time' => $init_time . ':00',
			'tanggal_insert' => date('Y-m-d H:i:s'),
			'status_work' => $status_work,
			'status' => $status,
			'is_deleted' => 0
		);


		$this->absensi_model->insert($data);
		$this->session->set_flashdata('message', 'Data berhasil ditambahkan.');
		redirect('data_absen');
	}
	
	// public function destroy()
	// {
	// 	$response_data = array();
	// 	$response_data['status'] = false;
	// 	$response_data['msg'] = "";
	// 	$response_data['data'] = array();

	// 	$id = $this->uri->segment(3);
	// 	$is_deleted = $this->uri->segment(4);
	// 	if (!empty($id)) {
	// 		$this->load->model("kelompok_kelas_model");
	// 		$data = array(
	// 			'is_deleted' => ($is_deleted == 1) ? 0 : 1
	// 		);
	// 		$update = $this->kelompok_kelas_model->update($data, array("id" => $id));

	// 		$response_data['data'] = $data;
	// 		$response_data['status'] = true;
	// 	} else {
	// 		$response_data['msg'] = "ID Harus Diisi";
	// 	}

	// 	echo json_encode($response_data);
	// }

	// public function destroy_hard()
	// {
	// 	$response_data = array();
	// 	$response_data['status'] = false;
	// 	$response_data['msg'] = "";
	// 	$response_data['data'] = array();

	// 	$id = $this->uri->segment(3);
	// 	$is_deleted = $this->uri->segment(4);
	// 	if (!empty($id)) {
	// 		$this->load->model("kelompok_kelas_model");
	// 		$data = array(
	// 			'is_deleted' => ($is_deleted == 1) ? 0 : 1
	// 		);
	// 		$update = $this->kelompok_kelas_model->delete(array("id" => $id));

	// 		$response_data['data'] = $data;
	// 		$response_data['status'] = true;
	// 	} else {
	// 		$response_data['msg'] = "ID Harus Diisi";
	// 	}

	// 	echo json_encode($response_data);
	// }

	// public function import_data()
	// {
	// 	$this->load->library('upload');

	// 	$upload_path = FCPATH . './uploads/kelompok_kelas_upload';

	// 	if (!is_dir($upload_path)) {
	// 		mkdir($upload_path, 0755, true);
	// 	}

	// 	$config['upload_path'] = $upload_path;
	// 	$config['allowed_types'] = 'xlsx';
	// 	$config['file_name'] = 'imported_data_kelompok_kelas' . time();
	// 	$this->upload->initialize($config);

	// 	if (!$this->upload->do_upload('userfile')) {
	// 		$error = array('status' => 'error', 'message' => $this->upload->display_errors('', ''));
	// 		echo json_encode($error);
	// 		return;
	// 	} else {
	// 		$file = $this->upload->data();
	// 		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file['full_path']);
	// 		$sheet = $spreadsheet->getActiveSheet();
	// 		$data = $sheet->toArray(null, true, true, true);

	// 		$insert_count = 0;
	// 		foreach ($data as $key => $row) {
	// 			if ($key < 6) continue;
	// 			if (!empty($row['B']) && !empty($row['C']) && !empty($row['D'])) {
	// 				$existing_data = $this->kelompok_kelas_model->get_by_code($row['B']);

	// 				if (!$existing_data) {
	// 					$insert_data = array(
	// 						'name' => $row['C'],
	// 						'code' => $row['B'],
	// 						'th_ajaran' => $row['D'],
	// 						'created_by' => $this->data['users']->id,
	// 						'is_deleted' => 0,
	// 					);

	// 					$this->kelompok_kelas_model->insert($insert_data);
	// 					$insert_count++;
	// 				}
	// 			}
	// 		}

	// 		unlink($file['full_path']);

	// 		$response = array('status' => 'success', 'message' => 'Import data berhasil! Total data yang diimport: ' . $insert_count);
	// 		echo json_encode($response);
	// 		return;
	// 	}
	// }
}
