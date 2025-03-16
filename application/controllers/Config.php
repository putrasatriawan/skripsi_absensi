<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';

class Config extends Admin_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('config_model');
    }

    public function index()
    {
		$this->load->helper('url');
        $data['longitude'] = $this->config_model->get_setting('longitude');
        $data['latitude'] = $this->config_model->get_setting('latitude');

		if ($this->data['is_can_read']) {
			$this->data['content'] = 'admin/config/locate_v';
		} else {
			$this->data['content'] = 'errors/html/restrict';
		}

		$this->data['longitude'] = $data['longitude'];
    	$this->data['latitude'] = $data['latitude'];

		$this->load->view('admin/layouts/page', $this->data);
    }

    public function save()
    {
        $longitude = $this->input->post('longitude');
        $latitude = $this->input->post('latitude');

        if (is_numeric($longitude) && is_numeric($latitude)) {
            $this->config_model->set_setting('longitude', $longitude);
            $this->config_model->set_setting('latitude', $latitude);
            echo json_encode(array('status' => 'success', 'message' => 'Data berhasil diupdate!'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Data gagala diupdate!'));
        }
    }
    public function dataList()
	{

		$columns = array(
			0 => 'id',
			1 => 'name',
			2 => 'config_check.check_in',
			3 => 'config_check.check_out',
			4 => '',
		);


		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];
		$search = array();
		$limit = 0;
		$start = 0;
		$totalData = $this->config_model->getCountAllBy($limit, $start, $search, $order, $dir);

        
		if (!empty($this->input->post('search')['value'])) {
			// $isSearchColumn = true;
			$search_value = $this->input->post('search')['value'];
			$search = array(
                "name" => $search_value,
			);
			//    	 }
			// if($isSearchColumn){
                $totalFiltered = $this->config_model->getCountAllBy($limit, $start, $search, $order, $dir);
            } else {
			$totalFiltered = $totalData;
		}
        
		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$datas = $this->config_model->getAllBy($limit, $start, $search, $order, $dir);
        // var_dump($datas);die;

		$new_data = array();
		if (!empty($datas)) {

			foreach ($datas as $key => $data) {
				$edit_url = "";
				$delete_url = "";
				$delete_url_hard = "";

                if($this->data['is_can_edit'] && $data->is_deleted == 0){
					$edit_url = "<button class='btn btn-sm btn-info white edit-button' data-id='".$data->id."' ><i class='fas fa-edit'></i> Ubah</button>";
            	}  


				$nestedData['id'] = $start + $key + 1;
				$nestedData['name'] = $data->name;
				$nestedData['check_in'] = $data->check_in;
				$nestedData['check_out'] = $data->check_out;
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
    public function getRoleById()
	{
		$id = $this->input->post('id');
		$config_check = $this->config_model->getAllById(array("config_check.id" => $id));
		
		if (!empty($config_check)) {
			$response = array(
				'id_check' => $config_check[0]->id,
				'roles_id' => $id,
				'check_in' => $config_check[0]->check_in,
				'check_out' => $config_check[0]->check_out,
			);
			echo json_encode($response);
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Guru not found'));
		}
	}

    public function save_check()
    {
        $id_check = $this->input->post('id_check');
        $roles_id = $this->input->post('roles_id');
        $check_in = $this->input->post('check_in');
        $check_out = $this->input->post('check_out');
    
        $data = [
            'roles_id' => $roles_id,
            'check_in' => $check_in,
            'check_out' => $check_out,
        ];
    
        if (!empty($id_check)) {
            // Jika ID tersedia, coba update data
            $this->db->where('id', $id_check);
            $update = $this->db->update('config_check', $data);
    
            if ($update) {
                $this->session->set_flashdata('message', 'Data berhasil diupdate!');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate data!');
            }
        } else {
            // Jika ID kosong, lakukan insert
            $insert = $this->db->insert('config_check', $data);
    
            if ($insert) {
                $this->session->set_flashdata('message', 'Data berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data!');
            }
        }
    
        redirect('config');
    }
    

}
