<?php
/**
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') or exit('No direct script access allowed');

// function uploadFiles($file_name, $dir,$prefix_file,$ext=''){   
//     $CI =& get_instance();
//     $return_file_name="";
//     $config['upload_path']          = $dir;
//     $config['allowed_types']        = '*'; 
//     if($ext != ''){
//         $ext = $ext;
//     }else{
//         $ext = ".jpg";
//     }
//     $config['file_name'] = $prefix_file."_".time().$ext;

//     $return = array();
//     $return['status'] = FALSE;
//     $return['message'] = "";
//     $CI->load->library('upload');
//     $CI->upload->initialize($config);
//     if ($CI->upload->do_upload($file_name)){   
//         $upload_data = $CI->upload->data(); 
//         $return_file_name = $config['file_name']; 
//         $return['status'] = TRUE;
//         $return['message'] = $return_file_name;
//     }else{
//         $return['status'] = FALSE;  
//         $return['message'] =  $CI->upload->display_errors();
//     }
//     return $return;
// }


function notification($from_id, $to_id, $ref_table, $ref_id, $is_read, $action_text, $description, $ref_function)
{
    $notif = array(
        'from_id' => $from_id,
        'to_id' => $to_id,
        'ref_table' => $ref_table,
        'description' => $description,
        'ref_function' => $ref_function,
        'ref_id' => $ref_id,
        'is_read' => $is_read,
        'action_text' => $action_text,
        'is_deleted' => 0
    );

    // You might want to return $notif or use it for further processing
    return $notif;
}

function uploadFile($file_name, $location_path, $prefix_file, $type)
{
    $CI = &get_instance();
    $return_file = "";
    $return_file_thumb = "";
    $list_allowed = "";
    $config['upload_path'] = $location_path;
    $old_name = $_FILES[$file_name]['name'];
    $ext = pathinfo($old_name, PATHINFO_EXTENSION);
    $config['file_name'] = $prefix_file . "_" . time() . '.' . $ext;
    $mime_type = [];
    $file_mime_type = mime_content_type($_FILES[$file_name]['tmp_name']);

    // type 1 = image, 2 = office, 3 = optional
    if ($type == 1) {
        $file_allowed = ['bmp', 'jpeg', 'jpg', 'png', 'svg'];
        $list_allowed = "bmp|jpg|jpeg|png|svg";
    } else if ($type == 2) {
        $file_allowed = ['pdf', 'csv'];
        $list_allowed = "pdf|csv";
    } else if ($type == 3) {
        $file_allowed = ['zip'];
        $list_allowed = "zip";
    } else {
        $file_allowed = [];
        $list_allowed = "*";
    }

    if (!empty($file_allowed)) {
        foreach ($file_allowed as $key => $allowed) {
            $temp_mime_type = [];
            $temp_mime_type = get_mimes()[$allowed];
            for ($i = 0; $i < count($temp_mime_type); $i++) {
                if (!in_array($temp_mime_type[$i], $mime_type, true)) {
                    $mime_type[] = $temp_mime_type[$i];
                }
            }
        }

        if (in_array($file_mime_type, $mime_type)) {
            $config['allowed_types'] = $list_allowed;
        }
    } else {
        $config['allowed_types'] = $list_allowed;
    }

    $return = array();
    $return['status'] = FALSE;
    $return['file'] = "";
    $return['file_thumb'] = "";

    $CI->load->library('upload');
    $CI->upload->initialize($config);
    if ($CI->upload->do_upload($file_name)) {
        $upload_data = $CI->upload->data();
        if ($type == 1) {
            $config_thumb['image_library'] = 'gd2';
            $config_thumb['source_image'] = $location_path . $config['file_name'];
            $config_thumb['new_image'] = $location_path;
            $config_thumb['maintain_ratio'] = TRUE;
            $config_thumb['create_thumb'] = TRUE;
            $config_thumb['thumb_marker'] = '_thumb';
            $config_thumb['width'] = 100;

            $CI->load->library('image_lib');
            $CI->image_lib->initialize($config_thumb);
            if ($CI->image_lib->resize() == true) {
                $return_file_thumb = $upload_data['raw_name'] . "_thumb" . $upload_data['file_ext'];
            }
        } else {
            $return_file_thumb = "";
        }

        $return_file = $upload_data['file_name'];
        $return['status'] = TRUE;
        $return['file'] = $return_file;
        $return['file_thumb'] = $return_file_thumb;
    } else {
        $return['status'] = FALSE;
        $return['file'] = "";
        $return['file_thumb'] = "";
    }
    return $return;
}

function uploadFileArray($file_name, $location_path, $prefix_file, $type)
{
    $CI = &get_instance();
    $return_file = "";
    $return_file_thumb = "";
    $list_allowed = "";
    $config['upload_path'] = $location_path;

    // type 1 = image, 2 = office, 3 = optional
    if ($type == 1) {
        $file_allowed = ['bmp', 'jpeg', 'jpg', 'png', 'svg'];
        $list_allowed = "bmp|jpg|jpeg|png|svg";
    } else if ($type == 2) {
        $file_allowed = ['pdf', 'csv'];
        $list_allowed = "pdf|csv";
    } else if ($type == 3) {
        $file_allowed = ['zip'];
        $list_allowed = "zip";
    } else {
        $file_allowed = [];
        $list_allowed = "*";
    }

    $return = [];

    $jumlah_berkas = count($_FILES[$file_name]['name']);
    for ($i = 0; $i < $jumlah_berkas; $i++) {
        if (!empty($_FILES[$file_name]['name'][$i])) {
            $old_name = $_FILES[$file_name]['name'][$i];
            $ext = pathinfo($old_name, PATHINFO_EXTENSION);
            $config['file_name'] = $prefix_file . "_" . abs(round(microtime(true) * 1000)) . '.' . $ext;
            $mime_type = [];
            $file_mime_type = mime_content_type($_FILES[$file_name]['tmp_name'][$i]);

            if (!empty($file_allowed)) {
                foreach ($file_allowed as $key => $allowed) {
                    $temp_mime_type = [];
                    $temp_mime_type = get_mimes()[$allowed];
                    for ($x = 0; $x < count($temp_mime_type); $x++) {
                        if (!in_array($temp_mime_type[$x], $mime_type, true)) {
                            $mime_type[] = $temp_mime_type[$x];
                        }
                    }
                }

                if (in_array($file_mime_type, $mime_type)) {
                    $config['allowed_types'] = $list_allowed;
                }
            } else {
                $config['allowed_types'] = $list_allowed;
            }

            $CI->load->library('upload');
            $CI->upload->initialize($config);

            $_FILES['file']['name'] = $_FILES[$file_name]['name'][$i];
            $_FILES['file']['type'] = $_FILES[$file_name]['type'][$i];
            $_FILES['file']['tmp_name'] = $_FILES[$file_name]['tmp_name'][$i];
            $_FILES['file']['error'] = $_FILES[$file_name]['error'][$i];
            $_FILES['file']['size'] = $_FILES[$file_name]['size'][$i];

            if ($CI->upload->do_upload('file')) {
                $upload_data = $CI->upload->data();
                if ($type == 1) {
                    $config_thumb['image_library'] = 'gd2';
                    $config_thumb['source_image'] = $location_path . $config['file_name'];
                    $config_thumb['new_image'] = $location_path;
                    $config_thumb['maintain_ratio'] = TRUE;
                    $config_thumb['create_thumb'] = TRUE;
                    $config_thumb['thumb_marker'] = '_thumb';
                    $config_thumb['width'] = 100;

                    $CI->load->library('image_lib');
                    $CI->image_lib->initialize($config_thumb);
                    if ($CI->image_lib->resize() == true) {
                        $return_file_thumb = $upload_data['raw_name'] . "_thumb" . $upload_data['file_ext'];
                    }
                } else {
                    $return_file_thumb = "";
                }

                $return[$i]["file"] = $upload_data['file_name'];
                $return[$i]["file_thumb"] = $return_file_thumb;
            } else {
                $return[$i]["file"] = "";
                $return[$i]["file_thumb"] = "";
            }
        } else {
            $return[$i]["file"] = "";
            $return[$i]["file_thumb"] = "";
        }
    }

    return $return;
}

function bulan($index)
{
    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    if (!isset($bulan[$index]))
        return FALSE;

    return $bulan[$index];
}

function bulan_romawi($index)
{
    $bulan = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII',
    ];

    if (!isset($bulan[$index]))
        return FALSE;

    return $bulan[$index];
}

function hari($index)
{
    $hari = [
        0 => "Minggu",
        1 => "Senin",
        2 => "Selasa",
        3 => "Rabu",
        4 => "Kamis",
        5 => "Jumat",
        6 => "Sabtu",
    ];

    if (!isset($hari[$index]))
        return FALSE;

    return $hari[$index];
}

function tgl_indo($str, $dengan_jam = FALSE)
{
    $date = date('Y-n-d', strtotime($str));
    $pecahkan = explode('-', $date);

    if ($dengan_jam === FALSE) {
        return $pecahkan[2] . ' ' . bulan($pecahkan[1]) . ' ' . $pecahkan[0];
    } else {
        $jam = date('H:i:s', strtotime($str));
        return $pecahkan[2] . ' ' . bulan($pecahkan[1]) . ' ' . $pecahkan[0] . ' ' . $jam;
    }
}

function abjad($index)
{
    $bulan = [
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
        5 => 'E',
        6 => 'F',
        7 => 'G',
        8 => 'H',
        9 => 'I',
        10 => 'J',
        11 => 'K',
        12 => 'L',
        13 => 'M',
        14 => 'N',
        15 => 'O',
        16 => 'P',
        17 => 'Q',
        18 => 'R',
        19 => 'S',
        20 => 'T',
        21 => 'U',
        22 => 'V',
        23 => 'W',
        24 => 'X',
        25 => 'Y',
        26 => 'Z',
        27 => 'AA',
        28 => 'AB',
        29 => 'AC',
        30 => 'AD',
        31 => 'AE',
        32 => 'AF',
        33 => 'AG',
        34 => 'AH',
        35 => 'AI',
        36 => 'AJ',
        37 => 'AK',
        38 => 'AL',
        39 => 'AM',
        40 => 'AN',
        41 => 'AO',
        42 => 'AP',
        43 => 'AQ',
        44 => 'AR',
        45 => 'AS',
        46 => 'AT',
        47 => 'AU',
        48 => 'AV',
        49 => 'AW',
        50 => 'AX',
        51 => 'AY',
        52 => 'AZ',
    ];
    if (!isset($bulan[$index]))
        return FALSE;

    return $bulan[$index];
}

function location_path($name_folder = "")
{
    $location_path = "./uploaded/" . $name_folder . "/";
    if (!is_dir("./uploaded/")) {
        mkdir("./uploaded/", 0777, TRUE);
    }
    if (!is_dir($location_path)) {
        mkdir($location_path, 0777, TRUE);
    }

    return $location_path;
}

function dd($value)
{
    echo "<pre>";
    print_r($value);
    die;
}