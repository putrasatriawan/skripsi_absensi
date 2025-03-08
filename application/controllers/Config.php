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
            echo json_encode(array('status' => 'success', 'message' => 'Settings updated successfully.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid input for longitude or latitude.'));
        }
    }
}
