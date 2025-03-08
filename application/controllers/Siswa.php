<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Siswa extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		$this->load->model('guru_model');
		$this->load->model('siswa_model');
		$this->load->model('user_model');
		$this->load->model('kelompok_kelas_model');
	}

	public function index()
	{

		$this->load->helper('url');
		if ($this->data['is_can_read']) {
			$this->data['content'] = 'admin/siswa/list_v';
		} else {
			$this->data['content'] = 'errors/html/restrict';
		}

		$this->load->view('admin/layouts/page', $this->data);
	}

	public function create()
	{
		$this->form_validation->set_rules('code', "Code Prosedur Harus Diisi", 'trim|required');
		$this->form_validation->set_rules('name', "Nama Harus Diisi", 'trim|required');
		if ($this->form_validation->run() === TRUE) {
			$data = array(
				'code' => $this->input->post('code'),
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
				'created_by' => $this->data['users']->id,
				'updated_by' => $this->data['users']->id,
				'is_deleted' => 0
			);
			if ($this->siswa_model->insert($data)) {
				$response = array('status' => 'success', 'message' => 'siswaan Berhasil Disimpan!');
				header('Content-Type: application/json');
				echo json_encode($response);
			} else {
				$response = array('status' => 'error', 'message' => 'siswaan Gagal Disimpan!');
				header('Content-Type: application/json');
				echo json_encode($response);
			}
		} else {
			$this->data['content'] = 'admin/siswa/create_v';
			$this->load->view('admin/layouts/page', $this->data);
		}
	}

	public function edit()
	{
		$this->form_validation->set_rules('nama', "Name Harus Diisi", 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$data = array(
				'nis' => $this->input->post('nip'),
				'nama' => $this->input->post('name'),
				'jk' => $this->input->post('jk'),
				'ttl' => $this->input->post('ttl'),
				'alamat' => $this->input->post('alamat'),
				'no_hp' => $this->input->post('no_hp'),
				'kode_kelas_id' => $this->input->post('kode_kelas_id'),
			);

			$id = $this->input->post('id');
			$update = $this->guru_model->update($data, array('id' => $id));

			if ($update) {
				$response = array('status' => 'success', 'message' => 'Siswa Berhasil Diubah!');
			} else {
				$response = array('status' => 'error', 'message' => 'Siswa Gagal Diubah!');
			}
		} else {
			$response = array('status' => 'error', 'message' => validation_errors());
		}
	
		header('Content-Type: application/json');
		echo json_encode($response);
	}

	public function getSiswaById()
	{
		$id = $this->input->post('id');
		$siswa = $this->siswa_model->getAllById(array("siswa.id" => $id));
		
		if (!empty($siswa)) {
			$kode_kelas_options = $this->kelompok_kelas_model->getAllForDropdown();
			$response = array(
				'id' => $siswa[0]->id,
				'nis' => $siswa[0]->nis,
				'nama' => $siswa[0]->nama,
				'jk' => $siswa[0]->jk,
				'ttl' => $siswa[0]->ttl,	
				'alamat' => $siswa[0]->alamat,
				'no_hp' => $siswa[0]->no_hp,
				'kode_kelas_id' => $siswa[0]->kode_kelas_id,
				'kode_kelas_options' => $kode_kelas_options
			);
			echo json_encode($response);
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Siswa not found'));
		}
	}

	public function getAdjacentRecords()
	{
		$id = $this->input->post('id');

		$prevRecord = $this->siswa_model->getPreviousRecord($id);
		$nextRecord = $this->siswa_model->getNextRecord($id);

		$response = array(
			'prevId' => !empty($prevRecord) ? $prevRecord[0]->id : null,
			'nextId' => !empty($nextRecord) ? $nextRecord[0]->id : null
		);

		header('Content-Type: application/json');
		echo json_encode($response);
	}



	public function dataList()
	{
		$columns = array(
			0 => 'id',
			1 => 'nama', // Mengambil data 'name' dari tabel 'users'
			2 => 'nis', // Mengambil data 'nik' dari tabel 'users'
			3 => 'jk', // Mengambil data 'jenis_kelamin' dari tabel 'guru'
			4 => 'kode_kelas_id', // Menambahkan kolom 'kode_kelas_id'?
			5 => '', // Kolom untuk aksi (edit, delete)
		);


		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];
		$search = array();
		$limit = 0;
		$start = 0;
		$totalData = $this->siswa_model->getCountAllBy($limit, $start, $search, $order, $dir);
		$where= array();

		if (!empty($this->input->post('search')['value'])) {
			$search_value = $this->input->post('search')['value'];
			$search = array(
				"siswa.nama" => $search_value,
				"siswa.nis" => $search_value,
				"siswa.jk" => $search_value,
			);
			$totalFiltered = $this->siswa_model->getCountAllBy($limit,$start,$search,$order,$dir, $where); 
		}else{
        	$totalFiltered = $totalData;
        } 

		$limit = $this->input->post('length');
        $start = $this->input->post('start');
		$datas = $this->siswa_model->getAllBy($limit, $start, $search, $order, $dir);
		$new_data = array();

		if (!empty($datas)) {
			foreach ($datas as $key => $data) {
				$edit_url = "";
				$delete_url = "";
				$delete_url_hard = "";

				if ($this->data['is_can_edit'] && isset($data->is_deleted) && $data->is_deleted == 0) {
					$edit_url = "<button class='btn btn-sm btn-info white edit-button' data-id='".$data->id."'><i class='fas fa-edit'></i> Ubah</button>";
				}
				if ($this->data['is_can_read'] && isset($data->is_deleted) && $data->is_deleted == 0) {
					$detail_url = "<a href='" . base_url() . "siswa/detail/" . $data->id . "' class='btn btn-sm btn-warning white'><i class='fas fa-eye'></i> Detail</a>";
				}
				if ($this->data['is_can_delete']) {
					if (isset($data->is_deleted)) {
						if ($data->is_deleted == 0) {
							$delete_url = "<a href='#'
                        url='" . base_url() . "user/destroy/" . $data->id . "/" . $data->is_deleted . "'
                        class='btn btn-sm btn-danger white delete'><i class='fas fa-times'></i> Non Aktifkan
                        </a>";
						} else {
							$delete_url = "<a href='#'
                        url='" . base_url() . "user/destroy/" . $data->id . "/" . $data->is_deleted . "'
                        class='btn btn-sm btn-primary white delete'><i class='fas fa-check'></i> Aktifkan
                        </a>";
							$delete_url_hard = "<a href='#'
                        url='" . base_url() . "user/destroy_hard/" . $data->id . "/" . $data->is_deleted . "'
                        class='btn btn-sm btn-danger white delete'><i class='fas fa-trash'></i> Delete
                        </a>";
						}
					}
				}

				$nestedData['id'] = $start + $key + 1;
				$nestedData['nama'] = isset($data->nama) ? $data->nama : '';
				$nestedData['nis'] = isset($data->nis) ? $data->nis : '';
				$nestedData['jk'] = isset($data->jk) ? $data->jk : '';
				$nestedData['action'] = $edit_url . " " . $delete_url . " " . $delete_url_hard;
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
			$this->load->model("siswa_model");
			$data = array(
				'is_deleted' => ($is_deleted == 1) ? 0 : 1
			);
			$update = $this->siswa_model->update($data, array("id" => $id));

			$response_data['data'] = $data;
			$response_data['status'] = true;
		} else {
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
			$this->load->model("siswa_model");
			$data = array(
				'is_deleted' => ($is_deleted == 1) ? 0 : 1
			);
			$update = $this->siswa_model->delete(array("id" => $id));

			$response_data['data'] = $data;
			$response_data['status'] = true;
		} else {
			$response_data['msg'] = "ID Harus Diisi";
		}

		echo json_encode($response_data);
	}

	public function import_data()
	{
		$this->load->library('upload');

		$upload_path = FCPATH . './uploads/siswa_upload';

		if (!is_dir($upload_path)) {
			mkdir($upload_path, 0755, true);
		}

		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'xlsx';
		$config['file_name'] = 'imported_data_siswa' . time();
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('userfile')) {
			$error = array('status' => 'error', 'message' => $this->upload->display_errors('', ''));
			echo json_encode($error);
			return;
		} else {
			$file = $this->upload->data();
			try {
				$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file['full_path']);
				$sheet = $spreadsheet->getActiveSheet();
				$data = $sheet->toArray(null, true, true, true);

				$insert_count = 0;
				foreach ($data as $key => $row) {
					if ($key < 6) continue;
					if (!empty($row['B']) && !empty($row['C']) && !empty($row['D'])) {
						$existing_data = $this->siswa_model->get_by_code($row['B']);

						if (!$existing_data) {
							$insert_data = array(
								'name' => $row['C'],
								'code' => $row['B'],
								'description' => $row['D'],
								'created_by' => $this->data['users']->id,
								'is_deleted' => 0,
							);

							$this->siswa_model->insert($insert_data);
							$insert_count++;
						}
					}
				}

				unlink($file['full_path']);

				$response = array('status' => 'success', 'message' => 'Import data berhasil! Total data yang diimport: ' . $insert_count);
				echo json_encode($response);
				return;
			} catch (Exception $e) {
				$error = array('status' => 'error', 'message' => 'Terjadi kesalahan saat mengimport data: ' . $e->getMessage());
				echo json_encode($error);
				return;
			}
		}
	}
}
