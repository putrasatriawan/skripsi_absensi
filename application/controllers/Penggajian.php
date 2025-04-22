<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Penggajian extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		$this->load->library('upload');
		$this->load->model('master_user_model');
		$this->load->model('master_user_jadwal_model');
		$this->load->model('config_waktu_detail_model');
		$this->load->model('config_waktu_master_model');
		$this->load->model('user_model');
	}

	public function index()
	{
		$this->load->helper('url');

		if ($this->data['is_can_read']) {
			$this->data['content'] = 'admin/penggajian/list_v';
		} else {
			$this->data['content'] = 'errors/html/restrict';
		}
		$this->load->view('admin/layouts/page', $this->data);
	}

	public function dataList()
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
			// $search = array(
			// 	"master_user.name" => $search_value,
			// 	"master_user.nip" => $search_value,
			// 	"master_user.jenis_kelamin" => $search_value
			// );

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
				$mapel_url = "";
				$delete_url = "";
				$delete_url_hard = "";


				if ($this->data['is_can_edit'] && $data->is_deleted == 0) {
					$mapel_url = "<a href='" . base_url() . "penggajian/detail/" . $data->id . "' class='btn btn-sm white btn-warning'><i class='fas fa-eye'></i> Detail</a>";
				}

				$nestedData['id'] = $start + $key + 1;
				$nestedData['kode'] = $data->kode;
				$nestedData['bulan_tahun'] = $data->bulan_tahun;
				$nestedData['keterangan'] = $data->keterangan;
				$nestedData['action'] = $edit_url . " " . $mapel_url . " " . $delete_url_hard;
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

	public function detail($id)
	{
		$this->data['content'] = 'admin/penggajian/detail_v';
		$this->data['id'] = $id;
		$this->load->view('admin/layouts/page', $this->data);
	}
	private function hitungDurasiJam($jam_mulai, $jam_selesai)
	{
		$mulai = new DateTime($jam_mulai);
		$selesai = new DateTime($jam_selesai);
		$interval = $mulai->diff($selesai);
		return $interval->h + ($interval->i / 60);
	}

	private function hitungPemotongan($type, $nominal, $jam_mulai, $check_in_const)
	{
		if (empty($check_in_const)) return 0;

		$mulai = new DateTime($jam_mulai);
		$checkin = new DateTime($check_in_const);

		if ($checkin <= $mulai) return 0;

		$diff = $mulai->diff($checkin);
		$total_menit = ($diff->h * 60) + $diff->i;

		if ($type === 'per_jam') {
			return ceil($total_menit / 60) * $nominal;
		} elseif ($type === 'per_menit') {
			return $total_menit * ($nominal / 60);
		}

		return 0;
	}

	public function detail_gaji($id, $id_config_master)
	{
		$where = [
			'config_waktu_detail.id_config_master' => $id_config_master,
			'config_waktu_detail.id_user' => $id
		];
		$users = $this->config_waktu_detail_model->getAllById($where);

		if (empty($users)) {
			$this->data['summary'] = [];
			$this->load->view('admin/layouts/page', $this->data);
			return;
		}

		$grouped_users = [];
		foreach ($users as $item) {
			$tanggal = $item->tanggal;
			if (!isset($grouped_users[$tanggal])) {
				$grouped_users[$tanggal] = [];
			}

			$item->durasi_jam = $this->hitungDurasiJam($item->jam_mulai, $item->jam_selesai);
			$grouped_users[$tanggal][] = $item;
		}

		$user = $users[0];
		$total_jam_valid = 0;
		$total_gaji = 0;
		$total_pemotongan = 0;
		$keterangan_pemotongan_per_hari = [];

		foreach ($grouped_users as $tanggal => $items) {
			$hadir = false;
			$check_in_tercepat = null;
			$jam_mulai_terawal = null;
			$durasi_terpanjang = 0;

			foreach ($items as $item) {
				if (!empty($item->tanggal_absen)) {
					$hadir = true;
					if (!$check_in_tercepat || strtotime($item->check_in_const) < strtotime($check_in_tercepat)) {
						$check_in_tercepat = $item->check_in_const;
					}
					if (!$jam_mulai_terawal || strtotime($item->jam_mulai) < strtotime($jam_mulai_terawal)) {
						$jam_mulai_terawal = $item->jam_mulai;
					}

					if ($item->durasi_jam > $durasi_terpanjang) {
						$durasi_terpanjang = $item->durasi_jam;
					}
				}
			}

			if ($hadir) {
				$total_jam_valid += $durasi_terpanjang;
				$total_gaji += $durasi_terpanjang * $user->gaji_master_user;
				$potongan = $this->hitungPemotongan(
					$user->type_pemotongan_master_user,
					$user->pemotongan_master_user,
					$jam_mulai_terawal,
					$check_in_tercepat
				);
				$total_pemotongan += $potongan;
				$keterangan_pemotongan_per_hari[$tanggal] = $potongan;
			} else {
				$keterangan_pemotongan_per_hari[$tanggal] = 0;
			}
		}

		$gaji_akhir = $total_gaji - $total_pemotongan;

		$this->data['summary'] = [
			'total_jam_valid' => $total_jam_valid,
			'total_gaji' => $total_gaji,
			'total_pemotongan' => $total_pemotongan,
			'gaji_akhir' => $gaji_akhir,
			'user' => $user,
			'grouped_users' => $grouped_users,
			'keterangan_pemotongan_per_hari' => $keterangan_pemotongan_per_hari
		];
		$this->data['content'] = 'admin/penggajian/detail_gaji_v';
		$this->load->view('admin/layouts/page', $this->data);
	}




	// echo "<pre>";
	// print_r($this->data['users_list']);
	// die;
	// foreach ($this->data['users_list']  as $value) {
	// 	echo "<pre>";
	// 	print_r($value);
	// }
	// die;
	public function dataListDetail($id)
	{
		$columns = array(
			0 => 'id',
			1 => '',
			2 => 'name_user',
			3 => '',
			4 => ''
		);

		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];
		$search = array();
		$limit = 0;
		$start = 0;
		$totalData = $this->config_waktu_detail_model->getCountAllBy($limit, $start, $search, $order, $dir, $id);

		if (!empty($this->input->post('search')['value'])) {
			$search_value = $this->input->post('search')['value'];


			$totalFiltered = $this->config_waktu_detail_model->getCountAllBy($limit, $start, $search, $order, $dir, $id);
		} else {
			$totalFiltered = $totalData;
		}

		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$datas = $this->config_waktu_detail_model->getAllBy($limit, $start, $search, $order, $dir, $id);

		// echo "<pre>";
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
					$mapel_url = "<a href='" . base_url() . "penggajian/detail_gaji/" . $data->id_user . "/" . $data->id_config_master . "' class='btn btn-sm white btn-warning'><i class='fas fa-eye'></i> Detail</a>";
				}

				$nestedData['id'] = $start + $key + 1;
				$nestedData[''] = '';
				$nestedData['name_user'] = $data->name_user;
				$nestedData[''] = '';
				$nestedData['action'] = $edit_url . " " . $mapel_url . " " . $delete_url_hard;
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
}
