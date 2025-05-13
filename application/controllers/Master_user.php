<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Master_user extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		$this->load->model('master_user_model');
		$this->load->model('master_user_jadwal_model');
		$this->load->model('config_waktu_master_model');
		$this->load->model('user_model');
	}

	public function index()
	{

		$this->load->helper('url');
		if ($this->data['is_can_read']) {
			$this->data['content'] = 'admin/master_user/list_v';
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
			if ($this->master_user_model->insert($data)) {
				$response = array('status' => 'success', 'message' => 'guruan Berhasil Disimpan!');
				header('Content-Type: application/json');
				echo json_encode($response);
			} else {
				$response = array('status' => 'error', 'message' => 'guruan Gagal Disimpan!');
				header('Content-Type: application/json');
				echo json_encode($response);
			}
		} else {
			$this->data['content'] = 'admin/master_user/create_v';
			$this->load->view('admin/layouts/page', $this->data);
		}
	}

	public function edit($id, $users_id)
	{
		$this->form_validation->set_rules('name', "Name Harus Diisi", 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$gaji_input = $this->input->post('gaji');
			$pemotongan_input = $this->input->post('pemotongan');

			// Hapus semua karakter non-digit
			$gaji_bersih = preg_replace('/\D/', '', $gaji_input);
			$pemotongan_bersih = preg_replace('/\D/', '', $pemotongan_input);

			// Siapkan data untuk disimpan
			$data = array(
				'name' => $this->input->post('name'),
				'nip' => $this->input->post('nip'),
				'jenis_kelamin' => $this->input->post('jenis_kelamin'),
				'no_hp' => $this->input->post('no_hp'),
				'agama' => $this->input->post('agama'),
				'alamat' => $this->input->post('alamat'),
				'gaji' => $gaji_bersih,
				'tempat_lahir' => $this->input->post('tempat_lahir'),
				'tanggal_lahir' => $this->input->post('tanggal_lahir'),
				'type_pemotongan' => $this->input->post('type_pemotongan'),
				'pemotongan' => $pemotongan_bersih,
			);

			// echo "<pre>";
			// print_r($data);
			// die;
			// foreach ($data as $value) {
			// 	echo "<pre>";
			// 	print_r($value);
			// }
			// die;
			$update = $this->master_user_model->update($data, array("id" => $id));

			if ($update) {
				$data_user = array(
					'first_name' => $this->input->post('name'),
					'nik' => $this->input->post('nip'),
				);
				$this->user_model->update($data_user, array("id" => $users_id));
				echo json_encode(['status' => 'success', 'message' => 'Data Guru Berhasil Diubah!']);
				redirect("master_user", "refresh");
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Data Guru Gagal Diubah!']);
				redirect("master_user", "refresh");
			}
		} else {
			if (!empty($_POST)) {
				$this->session->set_flashdata('message_error', validation_errors());
				return redirect("master_user/edit/" . $id);
			} else {
				$master_user = $this->master_user_model->getAllById(array("master_user.id" => $id));

				if (!empty($master_user)) {
					$this->data['id'] = $master_user[0]->id;
					$this->data['users_id'] = $master_user[0]->users_id;
					$this->data['name'] = $master_user[0]->name;
					$this->data['nip'] = $master_user[0]->nip;
					$this->data['jenis_kelamin'] = $master_user[0]->jenis_kelamin;
					$this->data['no_hp'] = $master_user[0]->no_hp;
					$this->data['agama'] = $master_user[0]->agama;
					$this->data['alamat'] = $master_user[0]->alamat;
					$this->data['gaji'] = $master_user[0]->gaji;
					$this->data['tempat_lahir'] = $master_user[0]->tempat_lahir;
					$this->data['tanggal_lahir'] = $master_user[0]->tanggal_lahir;
				} else {
					$this->data['id'] = "";
					$this->data['users_id'] = "";
					$this->data['name'] = "";
					$this->data['nip'] = "";
					$this->data['jenis_kelamin'] = "";
					$this->data['no_hp'] = "";
					$this->data['agama'] = "";
					$this->data['alamat'] = "";
					$this->data['gaji'] = "";
					$this->data['tempat_lahir'] = "";
					$this->data['tanggal_lahir'] = "";
				}

				$this->data['content'] = 'admin/master_user/edit_v';
				$this->load->view('admin/layouts/page', $this->data);
			}
		}
	}

	public function getGuruById()
	{
		$id = $this->input->post('id');
		$master_user = $this->master_user_model->getAllById(array("master_user.id" => $id));

		if (!empty($master_user)) {
			$response = array(
				'id' => $master_user[0]->id,
				'nip' => $master_user[0]->nip,
				'name' => $master_user[0]->name,
				'jenis_kelamin' => $master_user[0]->jenis_kelamin,
				'no_hp' => $master_user[0]->no_hp,
				'agama' => $master_user[0]->agama,
				'alamat' => $master_user[0]->alamat,
				'gaji' => $master_user[0]->gaji,
				'tempat_lahir' => $master_user[0]->tempat_lahir,
				'tanggal_lahir' => $master_user[0]->tanggal_lahir,
				'type_pemotongan' => $master_user[0]->type_pemotongan,
				'pemotongan' => $master_user[0]->pemotongan,
			);
			echo json_encode($response);
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Guru not found'));
		}
	}

/*************  ✨ Windsurf Command ⭐  *************/
/**
 * Fetches the adjacent records for a given user ID.
 *
 * This function retrieves the previous and next records based on the current user ID
 * submitted via POST request. It queries the database for the closest previous and next
 * records in terms of IDs and returns their IDs, if available, in JSON format.
 *
 * @return void Outputs a JSON response with the previous and next record IDs.
 */

/*******  4a71ed38-0048-42d7-9ca1-de92acb9d1a6  *******/	public function getAdjacentRecords()
	{
		$id = $this->input->post('id');

		$prevRecord = $this->master_user_model->getPreviousRecord($id);
		$nextRecord = $this->master_user_model->getNextRecord($id);

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
			1 => 'name',
			2 => 'nip',
			3 => 'jenis_kelamin',
			4 => ''
		);

		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];
		$search = array();
		$limit = 0;
		$start = 0;
		$totalData = $this->master_user_model->getCountAllBy($limit, $start, $search, $order, $dir);

		if (!empty($this->input->post('search')['value'])) {
			$search_value = $this->input->post('search')['value'];
			$search = array(
				"master_user.name" => $search_value,
				"master_user.nip" => $search_value,
				"master_user.jenis_kelamin" => $search_value
			);

			$totalFiltered = $this->master_user_model->getCountAllBy($limit, $start, $search, $order, $dir);
		} else {
			$totalFiltered = $totalData;
		}

		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$datas = $this->master_user_model->getAllBy($limit, $start, $search, $order, $dir);

		// 	echo "<pre>";
		// print_r($datas);
		// die;
		// foreach ($datas as $value) {
		// 	echo "<pre>";
		// 	print_r($value);
		// }
		// die;
		$new_data = array();
		if (!empty($datas)) {
			foreach ($datas as $key => $data) {
				$edit_url = "";
				$mapel_url = "";
				$delete_url = "";
				$delete_url_hard = "";

				if ($this->data['is_can_edit'] && $data->is_deleted == 0) {
					$edit_url = "<button class='btn btn-sm btn-info white edit-button' data-id='" . $data->id . "'  data-users-id='" . $data->users_id . "'><i class='fas fa-edit'></i> Ubah</button>";
				}

				if ($this->data['is_can_edit'] && $data->is_deleted == 0) {
					$mapel_url = "<a href='" . base_url() . "master_user/mapel/" . $data->users_id . "' class='btn btn-sm white btn-warning'><i class='fas fa-edit'></i> Mapel</a>";
				}

				$nestedData['id'] = $start + $key + 1;
				$nestedData['name'] = $data->name;
				$nestedData['nip'] = substr(strip_tags($data->nip), 0, 50);
				$nestedData['jenis_kelamin'] = $data->jenis_kelamin;
				$nestedData['action'] = $edit_url . " " . $mapel_url . " " .  $delete_url . " ". $delete_url_hard;
				$new_data[] = $nestedData;
			}
		}

		$json_data = array(
			"draw"            => intval($this->input->post('draw')),
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $new_data
		);
		echo json_encode($json_data);
	}
	public function mapel($id)
	{

		$this->data['id'] = $id;
		// $data = $this->user_model->getOneBy(array("users.id" => $this->data['id']));

		// if (empty($data)) {
		// 	$this->session->set_flashdata('message_error', 'User not found');
		// 	return redirect('user');
		// }

		// $this->data['photo'] = (!empty($data)) ? $data->photo : "";

		// $this->data['roles'] = $this->roles_model->getAllById();
		// $this->data['kelas'] = $this->kelas_model->getAllById();

		// $this->data['first_name'] = $data->first_name;
		// $this->data['last_name'] = $data->last_name;
		// $this->data['username'] = $data->username;
		// $this->data['address'] = $data->address;
		// $this->data['email'] = $data->email;
		// $this->data['nik'] = $data->nik;
		// $this->data['phone'] = $data->phone;
		// $this->data['role_id'] = $data->role_id;

		// $this->data['photo'] = (!empty($data->photo)) ? $data->photo : "";

		$this->data['content'] = 'admin/master_user/mapel_v';
		$this->load->view('admin/layouts/page', $this->data);
	}

	public function waktu($id)
	{
		$this->data['id_config_master'] = $id;

		$config = $this->db->get_where('config_waktu_master', ['id' => $id])->row();

		if ($config) {
			$bulan_tahun = $config->bulan_tahun; // format: YYYY-MM
			$year = date('Y', strtotime($bulan_tahun));
			$month = date('m', strtotime($bulan_tahun));
			$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

			$tanggal_list = [];
			for ($day = 1; $day <= $daysInMonth; $day++) {
				$tanggal = sprintf('%s-%02d', $bulan_tahun, $day);
				$hari = $this->getHariIndo(date('N', strtotime($tanggal)));

				$tanggal_list[] = [
					'tanggal' => $tanggal,
					'hari' => $hari
				];
			}

			$this->data['tanggal_list'] = $tanggal_list;
		} else {
			$this->data['tanggal_list'] = [];
		}

		// Load available pengampu
		$mapel_raw = $this->master_user_model->get_mapel_detail_with_user();

		// echo "<pre>";
		// print_r($mapel_raw);
		// die;
		// foreach ($mapel_raw as $value) {
		// 	echo "<pre>";
		// 	print_r($value);
		// }
		// die;
		$mapel_by_hari = [];
		foreach ($mapel_raw as $row) {
			$mapel_by_hari[$row->hari][] = [
				'name' => $row->name,
				'id_user' => $row->id_user,
				'id_mapel' => $row->id_mapel,
				'nama_mapel' => $row->nama_mapel,
			];
		}

		// Get saved configurations
		$saved_pengampu = $this->db
			->where('id_config_master', $id)
			->get('config_waktu_detail')
			->result();

		// Store selected as 'id_user|id_mapel'
		$selected = [];
		foreach ($saved_pengampu as $s) {
			$key =  $s->id_user . '|' . $s->id_mapel;
			$selected[$s->tanggal][] = $key;
		}



	
		$this->data['selected_pengampu'] = $selected;
		$this->data['mapel_by_hari'] = $mapel_by_hari;
		$this->data['content'] = 'admin/master_user/waktu_v';
		$this->load->view('admin/layouts/page', $this->data);
	}

	public function save_config_detail()
{
    $json = file_get_contents('php://input');
    $decoded = json_decode($json, true);
		// echo "<pre>";
		// print_r($decoded);
		// die;
		// foreach ($decoded as $value) {
		// 	echo "<pre>";
		// 	print_r($value);
		// }
		// die;

		$this->master_user_model->delete_existing_config([
			'id_config_master' => $decoded['id_config_master'],
		]);
    if (!empty($decoded['data'])) {
      

        // Insert semua data baru
        foreach ($decoded['data'] as $row) {
            $item = [
                'hari' => $row['hari'],
                'tanggal' => $row['tanggal'],
                'id_user' => $row['id_user'],
                'id_mapel' => $row['id_mapel'] ?? null,
                'id_config_master' => $row['id_config_master'],
                'is_deleted' => 0
            ];
            $this->master_user_model->insert_config($item);
        }

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'empty']);
    }
}


	private function getHariIndo($dayNumber)
	{
		$hari = [
			1 => 'Senin',
			2 => 'Selasa',
			3 => 'Rabu',
			4 => 'Kamis',
			5 => 'Jumat',
			6 => 'Sabtu',
			7 => 'Minggu'
		];
		return $hari[$dayNumber];
	}

	public function get_mapel()
	{
		$data = $this->master_user_jadwal_model->getAllById();
		echo json_encode($data);
	}
	// public function save_jadwal($id)
	// {
	// 	$this->load->model('master_user_model');

	// 	$id_user = $id;
	// 	$hariList = $this->input->post('hari_detail');
	// 	$namaMapelList = $this->input->post('nama_mapel');
	// 	$jamMulaiList = $this->input->post('jam_mulai');
	// 	$jamSelesaiList = $this->input->post('jam_selesai');
	// 	$durasiList = $this->input->post('durasi');

	// 	// echo "<pre>";
	// 	// print_r($_POST);
	// 	// die;
	// 	// foreach ($_POST as $value) {
	// 	// 	echo "<pre>";
	// 	// 	print_r($value);
	// 	// }
	// 	// die;
	// 	if (!empty($namaMapelList)) {
	// 		foreach ($namaMapelList as $day => $mapelNameList) {

	// 			$dataJadwal = [
	// 				'id_user' => $id_user,
	// 				'hari' => $day,
	// 			];

	// 			$dataMapel = [];

	// 			foreach ($mapelNameList as $key => $nama_mapel) {
	// 				$hari = isset($hariList[$day][$key]) ? $hariList[$day][$key] : null;

	// 				$dataMapel[] = [
	// 					'id_user' => $id_user,
	// 					'hari' => $hari,
	// 					'nama_mapel' => $nama_mapel,
	// 					'jam_mulai' => $jamMulaiList[$day][$key],
	// 					'jam_selesai' => $jamSelesaiList[$day][$key],
	// 					'durasi' => $durasiList[$day][$key],
	// 				];
	// 			}


	// 			$this->master_user_model->save_jadwal($dataJadwal, $dataMapel);
	// 		}
	// 	}

	// 	echo json_encode(['success' => true, 'message' => 'Jadwal berhasil disimpan!']);
	// }

	public function save_jadwal()
	{
		$id_user =  $this->input->post('id_user')[0];
		$data_second = array();
		if (!empty($this->input->post('hari'))) {
			foreach ($this->input->post('hari') as $key => $value) {
				if (isset($this->input->post('id_jadwal')[$key])) {
					$ids = $this->input->post('id_jadwal')[$key];
				} else {
					$ids = null;
				}

				$data_second[] = array(
					'id' => $ids,
					'id_user' => $id_user,
					'hari' => $this->input->post('hari')[$key],
					'id_user' => $id_user,
					'nama_mapel' => $this->input->post('nama_mapel')[$key],
					'jam_mulai' => $this->input->post('jam_mulai')[$key],
					'jam_selesai' => $this->input->post('jam_selesai')[$key],
					'durasi' => $this->input->post('durasi')[$key],
					'is_deleted' => 0,
				);
			}
		}

		$data_update = array();
		$data_insert = array();
		foreach ($data_second as $row) {
			if ($row['id']) {
				$data_update[] = $row;
			} else {
				$data_insert[] = $row;
			}
		}

		if (!empty($data_update)) {
			$update_second = $this->master_user_jadwal_model->update_batch($data_update, 'id');
		}

		if (!empty($data_insert)) {
			$insert_second = $this->master_user_jadwal_model->insert_batch($data_insert);
		}
		$this->session->set_flashdata('message', "Data Mapel Berhasil disimpan!");
		redirect("master_user");
	}
	public function get_jadwal($id)
	{
		$data = $this->db->where('id_user', $id)->get('jadwal_mapel')->row();
		// var_dump($data);
		$mapel_details = $this->db->where('id_user', $id)->get('mapel_detail')->result();
		echo json_encode(['data' => $data, 'mapel_details' => $mapel_details]);
	}
	public function delete_jadwal()
	{
		$id = $this->input->post('id_delete');
		$this->master_user_jadwal_model->deleteByid($id);
		$response = array('status' => 'success');
		echo json_encode($response);
	}

	public function import_data()
	{
		$this->load->library('upload');

		$upload_path = FCPATH . './uploads/guru_upload';

		if (!is_dir($upload_path)) {
			mkdir($upload_path, 0755, true);
		}

		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'xlsx';
		$config['file_name'] = 'imported_data_guru' . time();
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
						$existing_data = $this->master_user_model->get_by_code($row['B']);

						if (!$existing_data) {
							$insert_data = array(
								'name' => $row['C'],
								'code' => $row['B'],
								'description' => $row['D'],
								'created_by' => $this->data['users']->id,
								'is_deleted' => 0,
							);

							$this->master_user_model->insert($insert_data);
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

	//KONFIG WAKTU
	public function config_waktu()
	{
		$this->data['content'] = 'admin/master_user/config_waktu_v';
		$this->load->view('admin/layouts/page', $this->data);
	}
	public function dataListWaktu()
	{
		$columns = array(
			0 => 'id',
			1 => 'kode',
			2 => 'tahun',
			3 => 'bulan',
			4 => ''
		);

		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];
		$search = array();
		$limit = 0;
		$start = 0;
		$totalData = $this->config_waktu_master_model->getCountAllBy($limit, $start, $search, $order, $dir);

		if (!empty($this->input->post('search')['value'])) {
			$search_value = $this->input->post('search')['value'];
			$search = array(
				"config_waktu_master.kode" => $search_value,
			);

			$totalFiltered = $this->config_waktu_master_model->getCountAllBy($limit, $start, $search, $order, $dir);
		} else {
			$totalFiltered = $totalData;
		}

		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$datas = $this->config_waktu_master_model->getAllBy($limit, $start, $search, $order, $dir);

		// 	echo "<pre>";
		// print_r($datas);
		// die;
		// foreach ($datas as $value) {
		// 	echo "<pre>";
		// 	print_r($value);
		// }
		// die;
		$new_data = array();
		if (!empty($datas)) {
			foreach ($datas as $key => $data) {
				$edit_url = "";
				$config_url = "";
				$delete_url = "";
				$delete_url_hard = "";


				if ($this->data['is_can_edit'] && $data->is_deleted == 0) {
					$config_url = "<a href='" . base_url() . "master_user/waktu/" . $data->id . "' class='btn btn-sm white btn-warning'><i class='fas fa-edit'></i> Config</a>";
				}
				if ($this->data['is_can_delete']) {
					if ($data->is_deleted == 0) {
						$delete_url = "<a href='#' 
	        				url='" . base_url() . "master_user/destroy_waktu/" . $data->id . "/" . $data->is_deleted . "'
	        				class='btn btn-sm white btn-danger delete-config'><i class='fas fa-times'></i> NonAktifkan
	        				</a>";
					} else {
						$delete_url = "<a href='#' 
	        				url='" . base_url() . "master_user/destroy_waktu/" . $data->id . "/" . $data->is_deleted . "'
	        				class='btn btn-sm btn-primary white delete-config' 
	        				 ><i class='fas fa-check'></i> Aktifkan
	        				</a>";
						$delete_url_hard = "<a href='#' 
	        				url='" . base_url() . "master_user/destroy_hard_waktu/" . $data->id . "/" . $data->is_deleted . "'
	        				class='btn btn-sm btn-danger white delete-config' 
	        				 ><i class='fas fa-trash'></i> Delete 
	        				</a>";
					}
				}


				$nestedData['id'] = $start + $key + 1;
				$nestedData['kode'] = $data->kode;
				$nestedData['bulan_tahun'] = $data->bulan_tahun;
				$nestedData['keterangan'] = $data->keterangan;
				$nestedData['action'] =  $config_url . " " . $delete_url . " " . $delete_url_hard ;
				$new_data[] = $nestedData;
			}
		}

		$json_data = array(
			"draw"            => intval($this->input->post('draw')),
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $new_data
		);
		echo json_encode($json_data);
	}
	public function destroy_waktu()
	{
		$response_data = array();
		$response_data['status'] = false;
		$response_data['msg'] = "";
		$response_data['data'] = array();

		$id = $this->uri->segment(3);
		$is_deleted = $this->uri->segment(4);
		if (!empty($id)) {
			$data = array(
				'is_deleted' => ($is_deleted == 1) ? 0 : 1
			);
			$update = $this->config_waktu_master_model->update($data, array("id" => $id));

			$response_data['data'] = $data;
			$response_data['status'] = true;
		} else {
			$response_data['msg'] = "ID Harus Diisi";
		}

		echo json_encode($response_data);
	}
	public function destroy_hard_waktu()
	{
		$response_data = array();
		$response_data['status'] = false;
		$response_data['msg'] = "";
		$response_data['data'] = array();

		$id = $this->uri->segment(3);
		$is_deleted = $this->uri->segment(4);
		if (!empty($id)) {
			$this->load->model("master_user_model");
			$data = array(
				'is_deleted' => ($is_deleted == 1) ? 0 : 1
			);
			$update = $this->config_waktu_master_model->delete(array("id" => $id));

			$response_data['data'] = $data;
			$response_data['status'] = true;
		} else {
			$response_data['msg'] = "ID Harus Diisi";
		}

		echo json_encode($response_data);
	}
	public function save_config_waktu()
	{
		// Validasi input
		$this->form_validation->set_rules('bulan', 'Bulan dan Tahun', 'required');
		$this->form_validation->set_rules('kode', 'Kode Konfigurasi', 'required');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'required');

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('message', validation_errors());
			redirect('master_user');
		}

		$bulan      = $this->input->post('bulan');
		$kode       = $this->input->post('kode');
		$keterangan = $this->input->post('keterangan');

		$parts = explode('/', $bulan);
		$formatted_bulan = $parts[1] . '-' . $parts[0];
		// Siapkan data untuk disimpan
		$data = [
			'bulan_tahun' => $formatted_bulan,
			'kode'        => $kode,
			'keterangan'  => $keterangan,
			'created_at'  => date('Y-m-d H:i:s')
		];

		// Load model dan simpan data
		$insert_id = $this->config_waktu_master_model->insert($data);

		if ($insert_id) {
			$this->session->set_flashdata('message', 'Konfigurasi berhasil disimpan.');
		} else {
			$this->session->set_flashdata('message', 'Gagal menyimpan konfigurasi.');
		}

		redirect('master_user');
	}


	public function get_config_waktu()
	{
		$data = $this->config_waktu_master_model->getAllById();
		echo json_encode($data);
	}
}
