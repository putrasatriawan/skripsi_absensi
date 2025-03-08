<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Absensi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getAllById($where = array())
    {
        $this->db->select("absensi.*")->from("absensi");
        $this->db->where($where);
        $this->db->where("absensi.is_deleted", 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function getOneBy($where = array())
    {
        $this->db->select("absensi.*")->from("absensi");
        $this->db->where("absensi.is_deleted", 0);
        $this->db->where($where);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }
    public function insert($data)
    {
        $this->db->insert('absensi', $data);
        return $this->db->insert_id();
    }
    public function update($data, $where)
    {
        $this->db->update('absensi', $data, $where);
        return $this->db->affected_rows();
    }
    public function insert_absen($data)
    {
        $this->db->insert('absensi_sub', $data);
        return $this->db->insert_id();
    }
    public function update_absen($data, $where)
    {
        $this->db->update('absensi_sub', $data, $where);
        return $this->db->affected_rows();
    }
    public function delete($where)
    {
        $this->db->where($where);
        $this->db->delete('absensi');
        if ($this->db->affected_rows()) {
            return TRUE;
        }
        return FALSE;
    }
    public function insert_batch($data)
    {
        $this->db->insert_batch('absensi', $data);
        return $this->db->insert_id();
    }
    function getAllBy($limit, $start, $search, $col, $dir)
    {
        $this->db->select("absensi.*")->from("absensi");

        $this->db->limit($limit, $start)->order_by($col, $dir);
        if (!empty($search)) {
            foreach ($search as $key => $value) {
                $this->db->or_like($key, $value);
            }
        }
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result->result();
        } else {
            return null;
        }
    }
    
    function getCountAllBy($limit, $start, $search, $order, $dir)
    {
        $this->db->select("absensi.*")->from("absensi");
        if (!empty($search)) {
            foreach ($search as $key => $value) {
                $this->db->or_like($key, $value);
            }
        }
        $result = $this->db->get();
        return $result->num_rows();
    }

    public function getAbsensiBySiswa($id_siswa, $id_pengampu)
    {
        $this->db->select('absensi_sub.status')->from('absensi_sub');
        $this->db->where('absensi_sub.id_siswa', $id_siswa);
        $this->db->where('absensi_sub.id_pengampu', $id_pengampu);
        $this->db->where('absensi_sub.is_deleted', 0);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }

    public function check_existing_record($id_guru, $id_kelas, $id_mapel, $current_date) {
        $this->db->where('id_guru', $id_guru);
        $this->db->where('id_kelas', $id_kelas);
        $this->db->where('id_mapel', $id_mapel);
        $this->db->where('DATE(date_click_in)', $current_date);
        return $this->db->get('absensi')->row();
    }

    public function getRecord($id_siswa, $id_mapel, $kode_kelas, $id_pengampu, $date_click_status)
    {
        $this->db->where('id_siswa', $id_siswa);
        $this->db->where('id_mapel', $id_mapel);
        $this->db->where('kode_kelas', $kode_kelas);
        $this->db->where('id_pengampu', $id_pengampu);
        $this->db->where('DATE(date_click_status) =', $date_click_status);
        $query = $this->db->get('absensi_sub');

        return $query->row();
    }

    public function getAttendanceStatus($id_siswa, $date_click_status, $id_mapel, $kode_kelas_id, $id_pengampu)
    {
        $this->db->where('id_siswa', $id_siswa);
        $this->db->where('date_click_status', $date_click_status);
        $this->db->where('id_mapel', $id_mapel);
        $this->db->where('kode_kelas', $kode_kelas_id);
        $this->db->where('id_pengampu', $id_pengampu);
        $query = $this->db->get('absensi_sub');
        return $query->row();
    }

    public function getAttendanceDashboard($date = null)
    {
        $this->db->select('kelompok_kelas.kode_kelas, 
                           SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as Hadir, 
                           SUM(CASE WHEN status = "alpa" THEN 1 ELSE 0 END) as Alpa,
                           SUM(CASE WHEN status = "sakit" THEN 1 ELSE 0 END) as Sakit');
        $this->db->from('absensi_sub');
        $this->db->join('kelompok_kelas', 'kelompok_kelas.id = absensi_sub.kode_kelas');
        $this->db->group_by('kelompok_kelas.kode_kelas');
        
        if (!empty($date)) {
            $this->db->where('DATE(absensi_sub.date_click_status)', $date); // Filter by date
        }
        
        $this->db->group_by('kelompok_kelas.kode_kelas');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getFilteredAbsensi($pengampu, $kode_kelas, $tanggal_start, $tanggal_end, $mapel, $status) {
        $this->db->select("absensi_sub.*, kelompok_kelas.kode_kelas as kode_kelas_kode, mapel.name as mapel_name, siswa.nama as nama_siswa, users.first_name as pengampu_name")->from("absensi_sub");
        $this->db->join('pengampu', 'pengampu.id = absensi_sub.id_pengampu', 'left');
        $this->db->join('users', 'users.id = pengampu.id_user', 'left');
        $this->db->join('kelompok_kelas', 'kelompok_kelas.id = absensi_sub.kode_kelas', 'left');
        $this->db->join('siswa', 'siswa.id = absensi_sub.id_siswa', 'left');
        $this->db->join('mapel', 'mapel.id = absensi_sub.id_mapel', 'left');
        
        if (!empty($pengampu)) {
            $this->db->where('absensi_sub.id_pengampu', $pengampu);
        }
        if (!empty($kode_kelas)) {
            $this->db->where('absensi_sub.kode_kelas', $kode_kelas);
        }
        if (!empty($tanggal_start) && !empty($tanggal_end)) {
            $this->db->where('absensi_sub.date_click_status >=', $tanggal_start);
            $this->db->where('absensi_sub.date_click_status <=', $tanggal_end);
        } elseif (!empty($tanggal_start)) {
            $this->db->where('absensi_sub.date_click_status >=', $tanggal_start);
        } elseif (!empty($tanggal_end)) {
            $this->db->where('absensi_sub.date_click_status <=', $tanggal_end);
        }
        if (!empty($mapel)) {
            $this->db->where('absensi_sub.id_mapel', $mapel);
        }
        if (!empty($status)) {
            $this->db->where('absensi_sub.status', $status);
        }
    
        $query = $this->db->get();
        // $grouped_data = [];
        // foreach ($query->result() as $row) {
        //     $grouped_data[$row->pengampu_name][] = $row;
        // }

        // return $grouped_data;
        return $query->result_array();
    }    

    public function update_date_click_out($id_mapel, $id_pengampu, $kode_kelas, $date_click_status)
    {
        $this->db->where('id_mapel', $id_mapel);
        $this->db->where('id_pengampu', $id_pengampu);
        $this->db->where('kode_kelas', $kode_kelas);
        $this->db->where('DATE(date_click_status)', $date_click_status);
        $this->db->update('absensi_sub', array('date_click_out' => date('Y-m-d H:i:s')));
        return $this->db->affected_rows();
    }

    public function getRekamanByUnit($tables)
    {
        $result = array();

        foreach ($tables as $table) {

            $this->db->select('uk.name, COUNT(m.id) AS count')
                ->from('absensi uk')
                ->join($table . ' m', 'uk.id = m.absensi', 'left')
                ->group_by('uk.name');
            $data = $this->db->get()->result();


            $total = 0;
            foreach ($data as $item) {
                $total += $item->count;
            }


            $result[$table] = array(
                'data' => $data,
                'total' => $total,
            );
        }

        return $result;
    }
    public function getAllByUnit($tables)
    {
        $result = array();

        foreach ($tables as $table) {
            $this->db->select('uk.name, COUNT(m.id) AS count')
                ->from('absensi uk')
                ->join($table . ' m', 'uk.id = m.absensi', 'left')
                ->group_by('uk.name');
            $data = $this->db->get()->result();
            $total = 0;
            foreach ($data as $item) {
                $total += $item->count;
            }
            $result[$table] = array(
                'data' => $data,
                'total' => $total,
            );
        }

        return $result;
    }
    public function getCustomAliasAll($tables)
    {
        $aliases = array();

        foreach ($tables as $table) {
            if ($table === "data_manual") {
                $aliases[$table] = "Data Manual";
            } elseif ($table === "data_prosedur") {
                $aliases[$table] = "Data Prosedur";
            } elseif ($table === "data_intruksi_kerja") {
                $aliases[$table] = "Data Instruksi Kerja";
            } elseif ($table === "data_form") {
                $aliases[$table] = "Data Formulir";
            } elseif ($table === "dokumen_referensi") {
                $aliases[$table] = "Dokumen Referensi";
            } elseif ($table === "pmr0101") {
                $aliases[$table] = "FP-MR01-01";
            } elseif ($table === "pmr0102") {
                $aliases[$table] = "FP-MR01-02";
            } elseif ($table === "pmr0103") {
                $aliases[$table] = "FP-MR01-03";
            } elseif ($table === "pmr0104") {
                $aliases[$table] = "FP-MR01-04";
            } elseif ($table === "pmr0201") {
                $aliases[$table] = "FP-MR02-01";
            } elseif ($table === "pmr0202") {
                $aliases[$table] = "FP-MR02-02";
            } else {
                $aliases[$table] = $table;
            }
        }

        return $aliases;
    }
    public function getCustomAliasRekaman($tables)
    {
        $aliases = array();

        foreach ($tables as $table) {
            // Logika untuk mendapatkan alias kustom berdasarkan nama tabel
            if ($table === "pmr0101") {
                $aliases[$table] = "FP-MR01-01";
            } elseif ($table === "pmr0102") {
                $aliases[$table] = "FP-MR01-02";
            } elseif ($table === "pmr0103") {
                $aliases[$table] = "FP-MR01-03";
            } elseif ($table === "pmr0104") {
                $aliases[$table] = "FP-MR01-04";
            } elseif ($table === "pmr0201") {
                $aliases[$table] = "FP-MR02-01";
            } elseif ($table === "pmr0202") {
                $aliases[$table] = "FP-MR02-02";
            }
            // Tambahkan logika lain jika diperlukan
            // Jika tidak ada alias kustom, gunakan nama tabel aslinya
            else {
                $aliases[$table] = $table;
            }
        }

        return $aliases;
    }
    public function getCountAllById($where = array())
    {
        $this->db->select("COUNT(*) as total_rows")->from("absensi");
        $this->db->where($where);
        $this->db->where("absensi.is_deleted", 0);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->total_rows;
        }

        return 0;
    }

    public function cekAbsensiFoto($id_siswa)
    {
        $this->db->select('id');
        $this->db->from('absensi_sub');
        $this->db->where('id_siswa', $id_siswa);
        $this->db->where('date_click_status IS NOT NULL');
        $query = $this->db->get();

        return $query->num_rows() > 0;
    }
}
