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

        $show_view = false;
        $label_check = '';

        $attendances = $this->absensi_model->getAttendanceByDate($id_user, $today);
        $config = $this->absensi_model->getOneConfig(array('config_check.roles_id' => $role_id));
        $config_mapel = $this->absensi_model->getMapelTerdekat($id_user);


        if (empty($config)) {
            $this->data['content'] = 'admin/absensi/error_absen_v';
            $this->load->view('admin/layouts/page', $this->data);
            return;
        }
        if (empty($config_mapel)) {
            $this->data['content'] = 'admin/absensi/not_jadwal_absen_v';
            $this->load->view('admin/layouts/page', $this->data);
            return;
        }



        $this->data['photo'] = null;
        $this->data['show_view'] = false;
        $this->data['label_check'] = 'Belum Ada Data Absensi';

        $id_user = $this->data['users']->id;
        $role_id = $this->data['users_groups']->id;
        $this->data['user_master'] = $this->user_model->getAndMaster(["users.id" => $id_user]);
        $this->data['longitude'] = $this->config_model->get_setting('longitude');
        $this->data['latitude'] = $this->config_model->get_setting('latitude');
        $this->data['config_check'] = $this->config_model->get_config_chcek($role_id);

        // echo "<pre>";
        // print_r($this->data['config_check']);
        // die;
        // foreach ($this->data['config_check']  as $value) {
        //     echo "<pre>";
        //     print_r($value);
        // }
        // die;

        if ($this->data['is_can_read']) {
            $this->data['content'] = 'admin/absensi/list_v';
        } else {
            $this->data['content'] = 'errors/html/restrict';
        }

        $this->load->view('admin/layouts/page', $this->data);
    }
    public function init_absensi()
    {
        $role_id = $this->data['users_groups']->id;
        // $id = $this->data['user']->id;
        $config = $this->absensi_model->getOneConfig(array('config_check.roles_id' => $role_id));
        $distance = isset($_POST['distance']) ? $_POST['distance'] : null;
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



        // if ($check_done_absen) {
        //     echo json_encode([
        //         'status' => 'error',
        //         'message' => 'Anda Sudah Check In Hari ini ' . $check_done_absen->tanggal_insert . '. Tidak dapat melakukan absensi lagi'
        //     ]);
        //     return;
        // }

        $role_id = $this->data['users_groups']->id;

        // Konversi waktu menjadi timestamp
        $initTimestamp = strtotime($init_time);
        $checkInTimestamp = strtotime($config->check_in);
        $checkOutTimestamp = strtotime($config->check_out);


        $check_done_absen = $this->absensi_model->getOneBy([
            'absensi.id_user' => $id_user,
            'absensi.tanggal_absen' => date('Y-m-d'),
            'absensi.is_check_in' => 'check_in',
        ]);

        $check_not_check_out_done_absen = $this->absensi_model->getLastByUserNotCheckIn($id_user);
        if (!empty($check_not_check_out_done_absen)) {
            $status = 'Pulang';
            $is_check_in = 'check_out';
            $data = array(
                'id_user' => $id_user,
                'tanggal_absen' => $check_not_check_out_done_absen->tanggal_absen,
                'photo' => $photoData,
                'id_role' => $role_id,
                'distance' => $distance,
                // ' => '',
                // 'ch' => '',
                'init_time' => $init_time,
                'status_work' => $status_kerja,
                'status' => $status,
                'tanggal_insert' => date('Y-m-d H:i:s'),
                'is_check_in' => $is_check_in,
                'is_deleted' => 0
            );

            // echo "<pre>";
            // print_r($data);
            // die;
            // foreach ($data  as $value) {
            //     echo "<pre>";
            //     print_r($value);
            // }
            // die;

            $this->absensi_model->insert($data);

            echo json_encode(array(
                'status' => 'success',
                'message' => 'Check-out hari sebelumya telah dilakukan, silahkan check_in ulang'
            ));
            return;
        }
        $check_absen = $this->absensi_model->getOneBy([
            'absensi.id_user' => $id_user,
            'absensi.tanggal_absen' => date('Y-m-d')
        ]);
        $can_check_out = $this->canCheckOut($check_absen, $config);
        // var_dump($can_check_out);
        // die;
        if ($check_done_absen) {
            if (!$can_check_out) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Anda Sudah Check In Hari ini ' . $check_done_absen->tanggal_insert . '. Tidak dapat melakukan absensi lagi'
                ]);
                return;
            }
        }

        // var_dump($check_absen);
        // die;

        if ($initTimestamp <= $checkInTimestamp) {
            $status = 'Tepat Waktu';
            $is_check_in = 'check_in';
        } elseif ($initTimestamp > $checkInTimestamp && $initTimestamp < $checkOutTimestamp) {
            $status = 'Terlambat';
            $is_check_in = 'check_in';
        } elseif ($initTimestamp >= $checkOutTimestamp && $check_absen) {
            $status = 'Pulang';
            $is_check_in = 'check_out';
        } elseif (!$check_absen) {
            $status = 'Terlambat';
            $is_check_in = 'check_in';
        } else {
            $status = 'Tidak Diketahui';
            $is_check_in = 'unknown';
        }



        //Jika sudah check-out, tidak boleh check-in lagi
        if ($check_absen && $check_absen->is_check_in === 'check_out') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Anda sudah melakukan check-out hari ini. Tidak dapat absen kembali.'
            ]);
            return;
        }
        // Jika statusnya izin atau sakit, tidak boleh check-in ataupun check-out
        if ($check_absen && in_array(strtolower($check_absen->status), ['izin', 'sakit'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Status absensi Anda hari ini adalah ' . ucfirst($check_absen->status) . '. Tidak dapat melakukan absensi.'
            ]);
            return;
        }

        // Jika belum ada data absen atau belum check-in → boleh check-in
        if (!$check_absen || $check_absen->is_check_in == null || $check_absen->is_check_in == '') {
            $data = array(
                'id_user' => $id_user,
                'tanggal_absen' => date('Y-m-d'),
                'photo' => $photoData,
                'id_role' => $role_id,
                'distance' => $distance,
                'init_time' => $init_time,
                'status_work' => $status_kerja,
                'status' => $status,
                'tanggal_insert' => date('Y-m-d H:i:s'),
                'is_check_in' => $is_check_in,
                'is_deleted' => 0
            );

            $this->absensi_model->insert($data);
            echo json_encode([
                'status' => 'success',
                'message' => 'Sukses melakukan check-in.'
            ]);
            return;
        }

        // Jika sudah check-in dan ingin check-out → validasi jam
        $status_checkout = $this->canCheckOut($check_absen, $config);
        if (!$status_checkout) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Belum waktunya check-out atau Anda sudah melakukan check-out.'
            ]);
            return;
        }

        $data = array(
            'id_user' => $id_user,
            'tanggal_absen' => date('Y-m-d'),
            'photo' => $photoData,
            'id_role' => $role_id,
            'distance' => $distance,
            // ' => '',
            // 'ch' => '',
            'init_time' => $init_time,
            'status_work' => $status_kerja,
            'status' => $status,
            'tanggal_insert' => date('Y-m-d H:i:s'),
            'is_check_in' => $is_check_in,
            'is_deleted' => 0
        );

        $this->absensi_model->insert($data);
        echo json_encode(array(
            'status' => 'success',
            'message' => 'Check-out berhasil dilakukan.'
        ));
        return;
    }
    private function canCheckOut($check_absen, $config)
    {
        if (!$check_absen || !$config) {
            return false;
        }

        if (!isset($check_absen->is_check_in) || $check_absen->is_check_in !== 'check_in') {
            return false;
        }

        $jam_check_out_config = $config->check_out; // format: "HH:MM"
        $jam_sekarang = date('H:i');

        if (strtotime($jam_sekarang) >= strtotime($jam_check_out_config)) {
            return true;
        }

        return false;
    }
}
