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
	public function detail_gaji($id, $id_config_master)
	{
		$master_user = $this->master_user_model->getAllById(array("master_user.users_id" => $id));
		$user = $this->config_waktu_master_model->getAllById(array("config_waktu_master.id" => $id_config_master));
		$where = array(
			'config_waktu_detail.id_config_master' => $id_config_master,
			'config_waktu_detail.id_user' => $id
		);
		$users = $this->config_waktu_detail_model->getAllById($where);

		echo "<pre>";
		print_r($users);
		die;
		foreach ($users as $value) {
			echo "<pre>";
			print_r($value);
		}
		die;
		$this->data['content'] = 'admin/penggajian/detail_gaji_v';
		$this->data['id'] = $id;
		$this->load->view('admin/layouts/page', $this->data);
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
