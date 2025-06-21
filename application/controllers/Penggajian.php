<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;


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
		$this->load->model('absensi_model');
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
		$totalData = $this->config_waktu_master_model->getCountAllByPenggajian($limit, $start, $search, $order, $dir);

		if (!empty($this->input->post('search')['value'])) {
			$search_value = $this->input->post('search')['value'];
			$search = array(
				"config_waktu_master.kode" => $search_value
			);

			$totalFiltered = $this->config_waktu_master_model->getCountAllByPenggajian($limit, $start, $search, $order, $dir);
		} else {
			$totalFiltered = $totalData;
		}

		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$datas = $this->config_waktu_master_model->getAllByPenggajian($limit, $start, $search, $order, $dir);

		$new_data = array();
		if (!empty($datas)) {
			foreach ($datas as $key => $data) {
				$edit_url = "";
				$mapel_url = "";
				$delete_url = "";
				$delete_url_hard = "";


				if ($this->data['is_can_read'] && $data->is_deleted == 0) {
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
		//START GET CONFIG DETAIL
		$config_detail_where = [
			'config_waktu_detail.id_config_master' => $id_config_master,
			'config_waktu_detail.id_user' => $id
		];
		$config_detail = $this->config_waktu_detail_model->getAllById($config_detail_where);
		if ($config_detail == null || empty($config_detail)) {
			$this->data['why'] = 'config detail tidak ditemukan, hubungi admin';
			$this->data['content'] = 'admin/penggajian/detail_gaji_gagal_v';
			$this->load->view('admin/layouts/page', $this->data);
			return;
		}
		//END GET CONFIG DETAIL



		//START GET CONFIG MASTER
		$config_master_where = [
			'config_waktu_master.id' => $id_config_master
		];
		$config_master = $this->config_waktu_master_model->getAllById($config_master_where);
		if ($config_master == null || empty($config_master)) {
			$this->data['why'] = 'config master tidak ditemukan, hubungi admin';
			$this->data['content'] = 'admin/penggajian/detail_gaji_gagal_v';
			$this->load->view('admin/layouts/page', $this->data);
			return;
		}

		//END GET CONFIG MASTER
		$bulanTahun = $config_master[0]->bulan_tahun;

		//START GET ABSENSI
		$absensi_where = [
			'absensi.id_user' => $id,
			"absensi.tanggal_absen LIKE '{$bulanTahun}%'" => null,
			"absensi.is_deleted" => 0,
			'absensi.is_check_in' => 'check_in'
		];
		$absensi = $this->absensi_model->getAllByIdMapelDetail($absensi_where);

		if ($absensi == null || empty($absensi)) {
			$this->data['why'] = 'absensi tidak ditemukan, hubungi admin';
			$this->data['content'] = 'admin/penggajian/detail_gaji_gagal_v';
			$this->load->view('admin/layouts/page', $this->data);
			return;
		}
		//END GET ABSENSI
		if ($absensi == null) {
			$absensi = [];
		}


		$master_user = $this->user_model->getAllByIdWithMasterUser(['users.id' => $id]);
		if ($master_user == null || empty($master_user)) {
			$this->data['why'] = 'absensi tidak ditemukan, hubungi admin';
			$this->data['content'] = 'admin/penggajian/detail_gaji_gagal_v';
			$this->load->view('admin/layouts/page', $this->data);
			return;
		}

		$check_in_jadwal = new DateTime($master_user[0]->check_in);
		$type_pemotongan = $master_user[0]->type_pemotongan;
		$pemotongan_nominal = (int) $master_user[0]->pemotongan;



		// echo "<pre>";
		// 			print_r($absensi);
		// 			die;
		// 			foreach ($absensi as $value) {
		// 				echo "<pre>";
		// 				print_r($value);
		// 			}
		// 			die;

		$merged_data = [];
		foreach ($config_detail as $config) {
			// Extract month and year from 'bulan_tahun_config_master'
			$config_month = substr($config->bulan_tahun_config_master, 0, 7); // "2025-06"
			$config_day = $config->tanggal;

			foreach ($absensi as $absen) {
				// Extract month and year from 'tanggal_absen'
				$absen_month = substr($absen->tanggal_absen, 0, 7); // "2025-06"
				$absen_day = (int)date('d', strtotime($absen->tanggal_absen)); // Get day as integer

				// Check if the days and months match
				if ($config_day === $absen_day && $config_month === $absen_month) {
					// Merge the data
					$merged_data[] = (object) array_merge((array)$config, (array)$absen);
				}
			}
		}


		// echo "<pre>";
		// 			print_r($absen_day);
		// 			die;
		// 			foreach ($absen_day as $value) {
		// 				echo "<pre>";
		// 				print_r($value);
		// 			}
		// 			die;

		$merged_detail_absen = [];
		$total_durasi = 0;
		$total_durasi_hadir = 0;
		$total_pemotongan = 0;




		foreach ($config_detail as $detail) {
			$tanggal_detail = (int) $detail->tanggal;

			// Filter absensi berdasarkan tanggal dan id_mapel
			$absen_hari_ini = array_filter($absensi, function ($absen) use ($tanggal_detail, $detail) {
				return (int) date('d', strtotime($absen->tanggal_absen)) === $tanggal_detail
					&& isset($absen->id_mapel) && $absen->id_mapel == $detail->id_mapel;
			});

			// Jika ada absensi yang cocok, ambil data pertama atau lebih (dinamis)
			if (!empty($absen_hari_ini)) {
				$first_absen = reset($absen_hari_ini);
				$detail->id_absen = $first_absen->id;  // Assign ID absensi jika ada
			} else {
				$detail->id_absen = null;  // Jika tidak ada absensi
			}

			// Menyimpan absensi yang sesuai
			$detail->absen_list = array_values($absen_hari_ini);
			$merged_detail_absen[] = $detail;

			// Hitung durasi
			$dur = $this->parseDurasi($detail->durasi);  // Parse durasi
			$total_durasi += $dur;  // Update total durasi

			// Mengecek semua absensi untuk status hadir atau tidak
			if (!empty($detail->absen_list)) {
				foreach ($detail->absen_list as $absen) {
					// Jika status absensi 'hadir', tambahkan durasi
					if (strtolower($absen->status_mapel ?? '') === 'hadir') {
						$total_durasi_hadir += $dur;

						// Cek keterlambatan jika status absensi adalah terlambat
						if (strtolower($absen->status) === 'terlambat') {
							$init_time = new DateTime($absen->init_time);
							$diff = $check_in_jadwal->diff($init_time);
							$diff_menit = ($diff->h * 60) + $diff->i;

							if ($type_pemotongan === 'per_menit') {
								$total_pemotongan += $diff_menit * $pemotongan_nominal;
							} elseif ($type_pemotongan === 'per_jam') {
								$total_pemotongan += ceil($diff_menit / 60) * $pemotongan_nominal;
							}
						}
					}
				}
			}
		}

		$gaji_raw = isset($master_user[0]->gaji) && is_numeric($master_user[0]->gaji) ? (float) $master_user[0]->gaji : 0;
		$total_gaji = $gaji_raw * $total_durasi_hadir;
		$total_gaji_pemotongan = $total_gaji - $total_pemotongan;


		// Kirim ke view
		$this->data['total_gaji'] = $total_gaji;
		$this->data['total_gaji_pemotongan'] = $total_gaji_pemotongan;
		$this->data['total_pemotongan'] = $total_pemotongan;
		$this->data['id'] = $id;
		$this->data['id_config_master'] = $id_config_master;

		// var_dump( $this->data['total_gaji_pemotongan']);die;
		$this->data['total_durasi'] = $total_durasi;
		$this->data['total_durasi_hadir'] = $total_durasi_hadir;
		$this->data['merged_detail_absen'] = $merged_detail_absen;
		// echo "<pre>";
		// print_r($total_durasi_hadir);
		// die;
		// foreach ($total_durasi_hadir as $value) {
		// 	echo "<pre>";
		// 	print_r($value);
		// }
		// die;
		$this->data['master_user'] = $master_user;
		$this->data['content'] = 'admin/penggajian/detail_gaji_v';
		$this->load->view('admin/layouts/page', $this->data);
	}



	private function parseDurasi($durasiStr)
	{
		if (strpos($durasiStr, 'Menit') !== false) {
			preg_match('/(\d+) Jam(?: (\d+) Menit)?/', $durasiStr, $matches);
			$jam = isset($matches[1]) ? (int)$matches[1] : 0;
			$menit = isset($matches[2]) ? (int)$matches[2] : 0;
			return $jam + ($menit / 60);
		} else {
			return (float) $durasiStr;
		}
	}





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


				if ($this->data['is_can_read'] && $data->is_deleted == 0) {
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
	public function report($id, $id_config_master)
	{
		//START GET CONFIG DETAIL
		$config_detail_where = [
			'config_waktu_detail.id_config_master' => $id_config_master,
			'config_waktu_detail.id_user' => $id
		];
		$config_detail = $this->config_waktu_detail_model->getAllById($config_detail_where);
		if ($config_detail == null || empty($config_detail)) {
			$this->data['why'] = 'config detail tidak ditemukan, hubungi admin';
			$this->data['content'] = 'admin/penggajian/detail_gaji_gagal_v';
			$this->load->view('admin/layouts/page', $this->data);
			return;
		}
		//END GET CONFIG DETAIL



		//START GET CONFIG MASTER
		$config_master_where = [
			'config_waktu_master.id' => $id_config_master
		];
		$config_master = $this->config_waktu_master_model->getAllById($config_master_where);
		if ($config_master == null || empty($config_master)) {
			$this->data['why'] = 'config master tidak ditemukan, hubungi admin';
			$this->data['content'] = 'admin/penggajian/detail_gaji_gagal_v';
			$this->load->view('admin/layouts/page', $this->data);
			return;
		}

		//END GET CONFIG MASTER
		$bulanTahun = $config_master[0]->bulan_tahun;

		//START GET ABSENSI
		$absensi_where = [
			'absensi.id_user' => $id,
			"absensi.tanggal_absen LIKE '{$bulanTahun}%'" => null,
			"absensi.is_deleted" => 0,
			'absensi.is_check_in' => 'check_in'
		];
		$absensi = $this->absensi_model->getAllByIdMapelDetail($absensi_where);

		if ($absensi == null || empty($absensi)) {
			$this->data['why'] = 'absensi tidak ditemukan, hubungi admin';
			$this->data['content'] = 'admin/penggajian/detail_gaji_gagal_v';
			$this->load->view('admin/layouts/page', $this->data);
			return;
		}
		//END GET ABSENSI
		if ($absensi == null) {
			$absensi = [];
		}


		$master_user = $this->user_model->getAllByIdWithMasterUser(['users.id' => $id]);
		if ($master_user == null || empty($master_user)) {
			$this->data['why'] = 'absensi tidak ditemukan, hubungi admin';
			$this->data['content'] = 'admin/penggajian/detail_gaji_gagal_v';
			$this->load->view('admin/layouts/page', $this->data);
			return;
		}

		$check_in_jadwal = new DateTime($master_user[0]->check_in);
		$type_pemotongan = $master_user[0]->type_pemotongan;
		$pemotongan_nominal = (int) $master_user[0]->pemotongan;

		$merged_data = [];
		foreach ($config_detail as $config) {
			$config_month = substr($config->bulan_tahun_config_master, 0, 7);
			$config_day = $config->tanggal;

			foreach ($absensi as $absen) {
				$absen_month = substr($absen->tanggal_absen, 0, 7);
				$absen_day = (int)date('d', strtotime($absen->tanggal_absen));

				if ($config_day === $absen_day && $config_month === $absen_month) {
					$merged_data[] = (object) array_merge((array)$config, (array)$absen);
				}
			}
		}


		// echo "<pre>";
		// 			print_r($absen_day);
		// 			die;
		// 			foreach ($absen_day as $value) {
		// 				echo "<pre>";
		// 				print_r($value);
		// 			}
		// 			die;

		$merged_detail_absen = [];
		$total_durasi = 0;
		$total_durasi_hadir = 0;
		$total_pemotongan = 0;

		foreach ($config_detail as $detail) {
			$tanggal_detail = (int) $detail->tanggal;

			$absen_hari_ini = array_filter($absensi, function ($absen) use ($tanggal_detail, $detail) {
				return (int) date('d', strtotime($absen->tanggal_absen)) === $tanggal_detail
					&& isset($absen->id_mapel) && $absen->id_mapel == $detail->id_mapel;
			});

			if (!empty($absen_hari_ini)) {
				$first_absen = reset($absen_hari_ini);
				$detail->id_absen = $first_absen->id;
			} else {
				$detail->id_absen = null;
			}

			$detail->absen_list = array_values($absen_hari_ini);
			$merged_detail_absen[] = $detail;

			$dur = $this->parseDurasi($detail->durasi);
			$total_durasi += $dur;

			if (!empty($detail->absen_list)) {
				foreach ($detail->absen_list as $absen) {
					if (strtolower($absen->status_mapel ?? '') === 'hadir') {
						$total_durasi_hadir += $dur;
						if (strtolower($absen->status) === 'terlambat') {
							$init_time = new DateTime($absen->init_time);
							$diff = $check_in_jadwal->diff($init_time);
							$diff_menit = ($diff->h * 60) + $diff->i;

							if ($type_pemotongan === 'per_menit') {
								$total_pemotongan += $diff_menit * $pemotongan_nominal;
							} elseif ($type_pemotongan === 'per_jam') {
								$total_pemotongan += ceil($diff_menit / 60) * $pemotongan_nominal;
							}
						}
					}
				}
			}
		}

		$gaji_raw = isset($master_user[0]->gaji) && is_numeric($master_user[0]->gaji) ? (float) $master_user[0]->gaji : 0;
		$total_gaji = $gaji_raw * $total_durasi_hadir;
		$total_gaji_pemotongan = $total_gaji - $total_pemotongan;

		// Kirim ke view
		$this->data['total_gaji'] = $total_gaji;
		$this->data['total_gaji_pemotongan'] = $total_gaji_pemotongan;
		$this->data['total_pemotongan'] = $total_pemotongan;
		$this->data['id'] = $id;
		$this->data['id_config_master'] = $id_config_master;
		$this->data['total_durasi'] = $total_durasi;
		$this->data['total_durasi_hadir'] = $total_durasi_hadir;
		$this->data['merged_detail_absen'] = $merged_detail_absen;
		$this->data['master_user'] = $master_user;

		$html = $this->load->view('admin/penggajian/report_penggajian', $this->data, true);

		// Generate PDF dengan Dompdf
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Nama file
		$filename = 'Laporan_Gaji_' . $master_user[0]->name;
		$dompdf->stream($filename . ".pdf", array("Attachment" => 0));
	}
}
