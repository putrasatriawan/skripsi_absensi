<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Guru extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		$this->load->model('guru_model');
		$this->load->model('user_model');
	}

	public function index()
	{

		$this->load->helper('url');
		if ($this->data['is_can_read']) {
			$this->data['content'] = 'admin/guru/list_v';
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
			if ($this->guru_model->insert($data)) {
				$response = array('status' => 'success', 'message' => 'guruan Berhasil Disimpan!');
				header('Content-Type: application/json');
				echo json_encode($response);
			} else {
				$response = array('status' => 'error', 'message' => 'guruan Gagal Disimpan!');
				header('Content-Type: application/json');
				echo json_encode($response);
			}
		} else {
			$this->data['content'] = 'admin/guru/create_v';
			$this->load->view('admin/layouts/page', $this->data);
		}
	}

	public function edit($id)
	{
		$this->form_validation->set_rules('name', "Name Harus Diisi", 'trim|required');

		if ($this->form_validation->run() === TRUE) {
			$data = array(
				'name' => $this->input->post('name'),
				'nip' => $this->input->post('nip'),
				'jenis_kelamin' => $this->input->post('jenis_kelamin'),
				'no_hp' => $this->input->post('no_hp'),
				'agama' => $this->input->post('agama'),
				'alamat' => $this->input->post('alamat'),
				'gaji' => $this->input->post('gaji'),
				'tempat_lahir' => $this->input->post('tempat_lahir'),
				'tanggal_lahir' => $this->input->post('tanggal_lahir'),
			);

				// echo "<pre>";
				// print_r($id);
				// die;
				// foreach ($id as $value) {
				// 	echo "<pre>";
				// 	print_r($value);
				// }
				// die;
			$update = $this->guru_model->update($data, array("id" => $id));

			if ($update) {
				echo json_encode(['status' => 'success', 'message' => 'Data Guru Berhasil Diubah!']);
				redirect("guru", "refresh");
			} 
			else {
				echo json_encode(['status' => 'error', 'message' => 'Data Guru Gagal Diubah!']);
				redirect("guru", "refresh");
			}
		} 
		else {
			if (!empty($_POST)) {
				$this->session->set_flashdata('message_error', validation_errors());
				return redirect("guru/edit/" . $id);
			} else {
				$guru = $this->guru_model->getAllById(array("guru.id" => $id));

				if (!empty($guru)) {
					$this->data['id'] = $guru[0]->id;
					$this->data['users_id'] = $guru[0]->users_id;
					$this->data['name'] = $guru[0]->name;
					$this->data['nip'] = $guru[0]->nip;
					$this->data['jenis_kelamin'] = $guru[0]->jenis_kelamin;
					$this->data['no_hp'] = $guru[0]->no_hp;
					$this->data['agama'] = $guru[0]->agama;
					$this->data['alamat'] = $guru[0]->alamat;
					$this->data['gaji'] = $guru[0]->gaji;
					$this->data['tempat_lahir'] = $guru[0]->tempat_lahir;
					$this->data['tanggal_lahir'] = $guru[0]->tanggal_lahir;
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

				$this->data['content'] = 'admin/guru/edit_v';
				$this->load->view('admin/layouts/page', $this->data);
			}
		}
	}

	public function getGuruById()
	{
		$id = $this->input->post('id');
		$guru = $this->guru_model->getAllById(array("guru.id" => $id));
		
		if (!empty($guru)) {
			$response = array(
				'id' => $guru[0]->id,
				'nip' => $guru[0]->nip,
				'name' => $guru[0]->name,
				'jenis_kelamin' => $guru[0]->jenis_kelamin,
				'no_hp' => $guru[0]->no_hp,
				'agama' => $guru[0]->agama,
				'alamat' => $guru[0]->alamat,
				'gaji' => $guru[0]->gaji,	
				'tempat_lahir' => $guru[0]->tempat_lahir,
				'tanggal_lahir' => $guru[0]->tanggal_lahir,
			);
			echo json_encode($response);
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Guru not found'));
		}
	}

	public function getAdjacentRecords()
	{
		$id = $this->input->post('id');

		$prevRecord = $this->guru_model->getPreviousRecord($id);
		$nextRecord = $this->guru_model->getNextRecord($id);

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
		$totalData = $this->guru_model->getCountAllBy($limit, $start, $search, $order, $dir);

		if (!empty($this->input->post('search')['value'])) {
			$search_value = $this->input->post('search')['value'];
			$search = array(
				"guru.name" => $search_value,
				"guru.nip" => $search_value,
				"guru.jenis_kelamin" => $search_value
			);

           	$totalFiltered = $this->guru_model->getCountAllBy($limit,$start,$search,$order,$dir); 
        }
		else{
        	$totalFiltered = $totalData;
        } 
       
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
     	$datas = $this->guru_model->getAllBy($limit,$start,$search,$order,$dir);
     	
        $new_data = array();
        if(!empty($datas))
        {
            foreach ($datas as $key=>$data)
            {  
            	$edit_url = "";
     			$delete_url = "";
     			$delete_url_hard = "";
     		
            	if($this->data['is_can_edit'] && $data->is_deleted == 0){
					$edit_url = "<button class='btn btn-sm btn-info white edit-button' data-id='".$data->id."'><i class='fas fa-edit'></i> Ubah</button>";
            	}  
            	if($this->data['is_can_delete']){
	            	if($data->is_deleted == 0){
	        			$delete_url = "<a href='#' 
	        				url='".base_url()."guru/destroy/".$data->id."/".$data->is_deleted."'
	        				class='btn btn-sm btn-danger white delete' >Non Aktifkan
	        				</a>";
					} else {
						$delete_url = "<a href='#' 
	        				url='" . base_url() . "guru/destroy/" . $data->id . "/" . $data->is_deleted . "'
	        				class='btn btn-sm btn-primary white delete' 
	        				 >Aktifkan
	        				</a>";
						$delete_url_hard = "<a href='#' 
	        				url='" . base_url() . "guru/destroy_hard/" . $data->id . "/" . $data->is_deleted . "'
	        				class='btn btn-sm btn-danger white delete' 
	        				 >Delete
	        				</a>";
					}
				}

				$nestedData['id'] = $start + $key + 1;
				$nestedData['name'] = $data->name;
				$nestedData['nip'] = substr(strip_tags($data->nip), 0, 50);
				$nestedData['jenis_kelamin'] = $data->jenis_kelamin;
				$nestedData['action'] = $edit_url . " " . $delete_url . " " . $delete_url_hard;
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

	public function destroy()
	{
		$response_data = array();
		$response_data['status'] = false;
		$response_data['msg'] = "";
		$response_data['data'] = array();

		$id = $this->uri->segment(3);
		$is_deleted = $this->uri->segment(4);
		if (!empty($id)) {
			$this->load->model("guru_model");
			$data = array(
				'is_deleted' => ($is_deleted == 1) ? 0 : 1
			);
			$update = $this->guru_model->update($data, array("id" => $id));

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
			$this->load->model("guru_model");
			$data = array(
				'is_deleted' => ($is_deleted == 1) ? 0 : 1
			);
			$update = $this->guru_model->delete(array("id" => $id));

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
						$existing_data = $this->guru_model->get_by_code($row['B']);

						if (!$existing_data) {
							$insert_data = array(
								'name' => $row['C'],
								'code' => $row['B'],
								'description' => $row['D'],
								'created_by' => $this->data['users']->id,
								'is_deleted' => 0,
							);

							$this->guru_model->insert($insert_data);
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
