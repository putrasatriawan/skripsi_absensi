<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';

class Report extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Pdf');
        $this->load->model('user_model');
        $this->load->model('kelas_model');
        $this->load->model('kelompok_kelas_model');
        $this->load->model('pengampu_model');
        $this->load->model('siswa_model');
        $this->load->model('ion_auth_model');
        $this->load->model('roles_model');
        $this->load->model('mapel_model');
        $this->load->model('absensi_model');
    }

    public function index()
    {
        $this->load->helper('url');

        if ($this->data['is_can_read']) {
            if ($this->data['is_superadmin']) {
                $this->data['roles'] = $this->roles_model->getAllById();
                $this->data['kelas'] = $this->kelas_model->getAllById();
                $this->data['pengampu'] = $this->pengampu_model->getAllById();
                $this->data['nama_siswa'] = $this->siswa_model->getAllById();
                $this->data['kode_kelas'] = $this->kelompok_kelas_model->getAllById();
                $this->data['mapel'] = $this->mapel_model->getAllById();
            }

            $this->data['content'] = 'admin/report/list_v';
        } else {
            $this->data['content'] = 'errors/html/restrict';
        }

        $this->load->view('admin/layouts/page', $this->data);
    }

    public function generate_pdf()
    {
        $pengampu = $this->input->post('pengampu');
        $kode_kelas = $this->input->post('kode_kelas');
        $tanggal_start = $this->input->post('tanggal_start');
        $tanggal_end = $this->input->post('tanggal_end');
        $mapel = $this->input->post('mapel');
        $status = $this->input->post('status');

        $this->data['pengampu'] = $this->pengampu_model->getOneBy(array("pengampu.id" => $pengampu));
        $this->data['kode_kelas'] = $this->kelompok_kelas_model->getOneBy(array("kelompok_kelas.kode_kelas" => $kode_kelas));
        $this->data['mapel'] = $this->mapel_model->getOneBy(array("mapel.id" => $mapel));
        $this->data['tanggal_start'] = $tanggal_start;
        $this->data['tanggal_end'] = $tanggal_end;
        $this->data['attendance_data']  = $this->absensi_model->getFilteredAbsensi($pengampu, $kode_kelas, $tanggal_start, $tanggal_end, $mapel, $status);
        $this->data['title'] = 'Attendance Report';

        $customPaper = array(0, 0, 800, 935.433);
        $this->pdf->set_paper($customPaper);
        $this->pdf->set_option('isRemoteEnabled', TRUE);
        $this->pdf->set_base_path("/");

        $html = $this->load->view('admin/report/report', $this->data, true);

        // dd($html);
        $this->pdf->load_html($html);
        $this->pdf->render();

        $this->pdf->stream('Report.pdf', array("Attachment" => FALSE));
    }

    public function getFilteredData() {
        $pengampu = $this->input->post('pengampu');
        $kode_kelas = $this->input->post('kode_kelas');
        $tanggal_start = $this->input->post('tanggal_start');
        $tanggal_end = $this->input->post('tanggal_end');
        $mapel = $this->input->post('mapel');
        $status = $this->input->post('status');
    
        $this->load->model('Absensi_model');
        $data = $this->Absensi_model->getFilteredAbsensi($pengampu, $kode_kelas, $tanggal_start, $tanggal_end, $mapel, $status);
    
        $end_data = [];
        foreach ($data as $record) {
            $end_data[$record['pengampu_name']][] = $record;
        }

        echo json_encode(['grouped_data' => $end_data]);
    }
    
}
