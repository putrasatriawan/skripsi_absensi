<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Kelas extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		$this->load->model('kelas_model');
	}

	public function index()
	{
		$this->load->helper('url');

		if ($this->data['is_can_read']) {
			$this->data['content'] = 'admin/kelas/list_v';
		} 
		else {
			$this->data['content'] = 'errors/html/restrict';
		}
		$this->load->view('admin/layouts/page', $this->data);
	}

	public function create()
	{
		$this->form_validation->set_rules('code', "Code Prosedur Harus Diisi", 'trim|required');
		$this->form_validation->set_rules('name', "Nama Harus Diisi", 'trim|required');

		if ($this->form_validation->run() === TRUE) {

			$code = $this->input->post('code');
        	$name = $this->input->post('name');

			$existing_class = $this->kelas_model->checkCodeAndName($code, $name);

			if ($existing_class) {
				$response = array('status' => 'error', 'message' => 'Kode atau Nama Kelas sudah ada, silakan gunakan yang berbeda.');
				header('Content-Type: application/json');
				echo json_encode($response);
				return;
			}

			$data = array(
				'code' => $this->input->post('code'),
				'name' => $this->input->post('name'),
				'th_ajaran' => $this->input->post('th_ajaran'),
				'created_by' => $this->data['users']->id,
				'updated_by' => $this->data['users']->id,
				'is_deleted' => 0
			);

			if ($this->kelas_model->insert($data)) {
				$response = array('status' => 'success', 'message' => 'Kelas Berhasil Disimpan!');
				header('Content-Type: application/json');
				echo json_encode($response);
			} 
			else {
				$response = array('status' => 'error', 'message' => 'Kelas Gagal Disimpan!');
				header('Content-Type: application/json');
				echo json_encode($response);
			}
		} 
		else {
			$this->data['content'] = 'admin/kelas/create_v';
			$this->load->view('admin/layouts/page', $this->data);
		}
	}

	public function edit($id)
	{
		$this->form_validation->set_rules('code', "Code Prosedur Harus Diisi", 'trim|required');
		$this->form_validation->set_rules('name', "Name Harus Diisi", 'trim|required');
	
		if ($this->form_validation->run() === TRUE) {
	
			$code = $this->input->post('code');
			$name = $this->input->post('name');
	
			$existing_class = $this->kelas_model->checkCodeAndNameExceptId($code, $name, $id);
	
			if ($existing_class) {
				$response = array('status' => 'error', 'message' => 'Kode atau Nama Kelas sudah ada, silakan gunakan yang berbeda.');
				header('Content-Type: application/json');
				echo json_encode($response);
				return;
			}
	
			$data = array(
				'name' => $this->input->post('name'),
				'code' => $this->input->post('code'),
				'th_ajaran' => $this->input->post('th_ajaran'),
			);
	
			$update = $this->kelas_model->update($data, array("kelas.id" => $id));
			if ($update) {
				$response = array('status' => 'success', 'message' => 'Kelas Berhasil Diubah!');
				header('Content-Type: application/json');
				echo json_encode($response);
			} 
			else {
				$response = array('status' => 'error', 'message' => 'Kelas Gagal Diubah!');
				header('Content-Type: application/json');
				echo json_encode($response);
			}
		} 
		else {
			if (!empty($_POST)) {
				$id = $this->input->post('id');
				$this->session->set_flashdata('message_error', validation_errors());
				return redirect("kelas/edit/" . $id);
			} 
			else {
				$this->data['id'] = $this->uri->segment(3);
				$kelas = $this->kelas_model->getAllById(array("kelas.id" => $this->data['id']));
				$this->data['name'] = (!empty($kelas)) ? $kelas[0]->name : "";
				$this->data['code'] = (!empty($kelas)) ? $kelas[0]->code : "";
				$this->data['th_ajaran'] = (!empty($kelas)) ? $kelas[0]->th_ajaran : "";
	
				$this->data['content'] = 'admin/kelas/edit_v';
				$this->load->view('admin/layouts/page', $this->data);
			}
		}
	}

	public function dataList()
	{
		$columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'code',
			3 => 'th_ajaran',
			4 => '',
		);

		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];
		$search = array();
		$limit = 0;
		$start = 0;
		$totalData = $this->kelas_model->getCountAllBy($limit, $start, $search, $order, $dir);

		if (!empty($this->input->post('search')['value'])) {
			// $isSearchColumn = true;
			$search_value = $this->input->post('search')['value'];
			$search = array(
				"kelas.code" => $search_value,
				"kelas.name" => $search_value,
				"kelas.th_ajaran" => $search_value,
			);
			//    	 }
			// if($isSearchColumn){
			$totalFiltered = $this->kelas_model->getCountAllBy($limit, $start, $search, $order, $dir);
		} 
		else {
			$totalFiltered = $totalData;
		}

		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$datas = $this->kelas_model->getAllBy($limit, $start, $search, $order, $dir);

		$new_data = array();
		if (!empty($datas)) {
			foreach ($datas as $key => $data) {
				$edit_url = "";
				$delete_url = "";
				$delete_url_hard = "";

				if ($this->data['is_can_edit'] && $data->is_deleted == 0) {
					$edit_url = "<a href='" . base_url() . "kelas/edit/" . $data->id . "' class='btn btn-sm btn-info white'><i class='fas fa-edit'></i> Ubah</a>";
				}
				if ($this->data['is_can_read'] && $data->is_deleted == 0) {
					$detail_url = "<a href='" . base_url() . "kelas/detail/" . $data->id . "' class='btn btn-sm btn-warning white'><i class='fas fa-eye'></i> Detail</a>";
				}
				if ($this->data['is_can_delete']) {
					if ($data->is_deleted == 0) {
						$delete_url = "<a href='#' y
	        				url='" . base_url() . "kelas/destroy/" . $data->id . "/" . $data->is_deleted . "'
	        				class='btn btn-sm btn-danger white delete' ><i class='fas fa-times'></i> Non Aktifkan
	        				</a>";
					} 
					else {
						$delete_url = "<a href='#' 
	        				url='" . base_url() . "kelas/destroy/" . $data->id . "/" . $data->is_deleted . "'
	        					class='btn btn-sm btn-primary white delete' 
	        				 ><i class='fas fa-check'></i> Aktifkan
	        				</a>";
						$delete_url_hard = "<a href='#' 
	        				url='" . base_url() . "kelas/destroy_hard/" . $data->id . "/" . $data->is_deleted . "'
	        				class='btn btn-sm btn-danger white delete' 
	        				 ><i class='fas fa-trash'></i> Delete
	        				</a>";
					}
				}

				$nestedData['id'] = $start + $key + 1;
				$nestedData['code'] = $data->code;
				$nestedData['name'] = $data->name;
				$nestedData['th_ajaran'] = $data->th_ajaran;
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
			$this->load->model("kelas_model");
			$data = array(
				'is_deleted' => ($is_deleted == 1) ? 0 : 1
			);
			$update = $this->kelas_model->update($data, array("id" => $id));

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
			$this->load->model("kelas_model");
			$data = array(
				'is_deleted' => ($is_deleted == 1) ? 0 : 1
			);
			$update = $this->kelas_model->delete(array("id" => $id));

			$response_data['data'] = $data;
			$response_data['status'] = true;
		} 
		else {
			$response_data['msg'] = "ID Harus Diisi";
		}
		echo json_encode($response_data);
	}

	public function import_data()
	{
		$this->load->library('upload');

		$upload_path = FCPATH . './uploads/kelas_upload';

		if (!is_dir($upload_path)) {
			mkdir($upload_path, 0755, true);
		}

		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'xlsx';
		$config['file_name'] = 'imported_data_kelas' . time();
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('userfile')) {
			$error = array('status' => 'error', 'message' => $this->upload->display_errors('', ''));
			echo json_encode($error);
			return;
		} 
		else {
			$file = $this->upload->data();
			try {
				$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file['full_path']);
				$sheet = $spreadsheet->getActiveSheet();
				$data = $sheet->toArray(null, true, true, true);

				$insert_count = 0;
				foreach ($data as $key => $row) {
					if ($key < 6) continue;
					if (!empty($row['B']) && !empty($row['C']) && !empty($row['D'])) {
						$existing_data = $this->kelas_model->get_by_code($row['B']);

						if (!$existing_data) {
							$insert_data = array(
								'name' => $row['C'],
								'code' => $row['B'],
								'th_ajaran' => $row['D'],
								'created_by' => $this->data['users']->id,
								'is_deleted' => 0,
							);

							$this->kelas_model->insert($insert_data);
							$insert_count++;
						}
					}
				}

				unlink($file['full_path']);

				$response = array('status' => 'success', 'message' => 'Import data berhasil! Total data yang diimport: ' . $insert_count);
				echo json_encode($response);
				return;
			} 
			catch (Exception $e) {
				$error = array('status' => 'error', 'message' => 'Terjadi kesalahan saat mengimport data: ' . $e->getMessage());
				echo json_encode($error);
				return;
			}
		}
	}

	public function generate_excel()
	{
		$spreadsheet = new Spreadsheet();

	    $spreadsheet->getProperties()->setCreator("Your Name")
        ->setLastModifiedBy("Your Name")
        ->setTitle("Kelas Data")
        ->setSubject("Kelas Data")
        ->setDescription("Generated Kelas data.")
        ->setKeywords("kelas data excel")
        ->setCategory("Exported Data");


        $sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'Data Kelas')
			->setCellValue('A4', 'ID')
			->setCellValue('B4', 'Kode')
			->setCellValue('C4', 'Kelas')
			->setCellValue('D4', 'Tahun Ajaran');

		$sheet->mergeCells('A1:D2');
		$sheet->mergeCells('A4:A5');
		$sheet->mergeCells('B4:B5');
		$sheet->mergeCells('C4:C5');
		$sheet->mergeCells('D4:D5');
		
		$sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A4:D5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$sheet->getStyle('A1')->getFont()->setBold(true);
		$sheet->getStyle('A4:D4')->getFont()->setBold(true);
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A4:D4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		$kelasList = $this->kelas_model->getAllById();

		if (!empty($kelasList)) {
			$kelas = $kelasList[0];
	
			$sheet->setCellValue('A6', $kelas->id)
				  ->setCellValue('B6', $kelas->code)
				  ->setCellValue('C6', $kelas->name)
				  ->setCellValue('D6', $kelas->th_ajaran);
		}

		$sheet->setTitle('Kelas Data');

		$spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="kelas_data.xlsx"');
        header('Cache-Control: max-age=0');
		
        $writer  = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer ->save('php://output');
        exit;
    }
}
