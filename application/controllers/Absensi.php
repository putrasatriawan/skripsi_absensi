<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'core/Admin_Controller.php';
class Absensi extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('roles_model');
        $this->load->model('absensi_model');
        $this->load->model('config_model');
    }
    public function index()
    {
        $this->load->helper('url');
        $id_user = $this->data['users']->id;
        $role_id = $this->data['users_groups']->id;
        $today = date('Y-m-d');

        // var_dump($today);die; 
        $show_view = false;
        $label_check = '';

        $attendances = $this->absensi_model->getAttendanceByDate($id_user, $today);

        $has_check_in = false;
        $has_check_out = false;
        // echo "<pre>";
        // print_r($attendances);
        // die;
        // foreach ($attendances as $value) {
        //     echo "<pre>";
        //     print_r($value);
        // }
        // die;
        if ($attendances) {
            foreach ($attendances as $attendance) {
                if ($attendance->is_check_in == 'check_in') {
                    $has_check_in = true;
                }

                if ($attendance->is_check_in == 'check_out') {
                    $has_check_out = true;
                }
            }

            if ($has_check_in && !$has_check_out) {
                $show_view = true;
                $label_check = 'Check In, Jangan Lupa Check Out!';
                if ($this->data['is_can_read']) {
                    $this->data['content'] = 'admin/absensi/done_absen_v';
                } else {
                    $this->data['content'] = 'errors/html/restrict';
                }

                $this->load->view('admin/layouts/page', $this->data);
                return;
            }

            if ($has_check_in && $has_check_out) {
                $show_view = true;
                $label_check = 'Check Out, Sampai Bertemu Kembali!';
                $label_check = 'Check In, Jangan Lupa Check Out!';
                if ($this->data['is_can_read']) {
                    $this->data['content'] = 'admin/absensi/done_absen_v';
                } else {
                    $this->data['content'] = 'errors/html/restrict';
                }

                $this->load->view('admin/layouts/page', $this->data);
                return;
            }

            $last_attendance = end($attendances);

            $this->data['photo'] = $last_attendance->photo ?? null;
            $this->data['show_view'] = $show_view;
            $this->data['init_time'] = $last_attendance->init_time ?? null;
            $this->data['status_work'] = $last_attendance->status_work ?? null;
            $this->data['status'] = $last_attendance->status ?? null;
            $this->data['label_check'] = $label_check;
        } else {
            $this->data['photo'] = null;
            $this->data['show_view'] = false;
            $this->data['label_check'] = 'Belum Ada Data Absensi';
        }
        $this->data['user_master'] = $this->user_model->getAndMaster(["users.id" => $id_user]);
        $this->data['longitude'] = $this->config_model->get_setting('longitude');
        $this->data['latitude'] = $this->config_model->get_setting('latitude');
        $this->data['config_check'] = $this->config_model->get_config_chcek($role_id);


        if ($this->data['is_can_read']) {
            $this->data['content'] = 'admin/absensi/list_v';
        } else {
            $this->data['content'] = 'errors/html/restrict';
        }

        $this->load->view('admin/layouts/page', $this->data);
    }
    public function init_absensi()
    {
        $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
        $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;
        $distance = isset($_POST['distance']) ? $_POST['distance'] : null;
        $check_in_const = isset($_POST['check_in_const']) ? $_POST['check_in_const'] : null;
        $check_out_const = isset($_POST['check_out_const']) ? $_POST['check_out_const'] : null;
        $init_time = isset($_POST['init_time']) ? $_POST['init_time'] : null;
        $status_kerja = isset($_POST['status']) ? $_POST['status'] : null;

        if (isset($_POST['photo'])) {
            $photoData = $_POST['photo'];

            // Proses decoding Base64
            $photoData = str_replace('data:image/png;base64,', '', $photoData);
            $photoData = str_replace(' ', '+', $photoData);
            $decodedPhoto = base64_decode($photoData);

            if ($decodedPhoto === false) {
                echo json_encode(array('status' => 'error', 'message' => 'Gambar tidak valid!'));
                return;
            }

            // Membuat folder sesuai tanggal dan ID user
            $id_user = $this->data['users']->id;
            $today = date('Ymd');
            $folderPath = FCPATH . "assets/images/{$today}{$id_user}/";

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            // Nama file unik
            $fileName = "absen_" . time() . ".png";
            $filePath = $folderPath . $fileName;

            // Simpan gambar ke folder
            if (file_put_contents($filePath, $decodedPhoto) === false) {
                echo json_encode(array('status' => 'error', 'message' => 'Gagal menyimpan gambar!'));
                return;
            }

            // Menyimpan path gambar relatif yang akan disimpan di database
            $savedPhotoPath = "assets/images/{$today}{$id_user}/" . $fileName;
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Gambar tidak ditemukan!'));
            return;
        }

        $role_id = $this->data['users_groups']->id;

        // Konversi waktu menjadi timestamp
        $initTimestamp = strtotime($init_time);
        $checkInTimestamp = strtotime($check_in_const);
        $checkOutTimestamp = strtotime($check_out_const);

        // Logika Status dan Check-In
        if ($initTimestamp <= $checkInTimestamp) {
            $status = 'Tepat Waktu';
            $is_check_in = 'check_in';
        } elseif ($initTimestamp > $checkInTimestamp && $initTimestamp < $checkOutTimestamp) {
            $status = 'Terlambat';
            $is_check_in = 'check_in';
        } elseif ($initTimestamp >= $checkOutTimestamp) {
            $status = 'Pulang';
            $is_check_in = 'check_out';
        } else {
            $status = 'Tidak Diketahui';
            $is_check_in = 'unknown';
        }

        $data = array(
            'id_user' => $id_user,
            'tanggal_absen' => date('Y-m-d H:i:s'),
            'photo' => $photoData,
            'id_role' => $role_id,
            'distance' => $distance,
            'check_in_const' => $check_in_const,
            'check_out_const' => $check_out_const,
            'init_time' => $init_time,
            'status_work' => $status_kerja,
            'status' => $status,
            'tanggal_insert' => date('Y-m-d H:i:s'),
            'is_check_in' => $is_check_in,
            'is_deleted' => 0
        );

        $this->absensi_model->insert($data);
        echo json_encode(array('status' => 'success'));
    }
}
