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
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }
    public function getAllByIdDashboard($where = [])
    {
        $this->db->select('*')
            ->from('absensi')
            ->where('is_deleted', 0);

        if (!empty($where)) {
            $this->db->where($where);
        }

        $this->db->order_by('tanggal_absen', 'DESC');

        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result() : [];
    }

    public function getAllByIdMapelDetail($where = array())
    {
        $this->db->select("
            absensi.*, 
            absensi_mapel.id_mapel, 
            absensi_mapel.status as status_mapel, 
            absensi_mapel.created_by as created_by_mapel,
            absensi_mapel.created_at as created_at_mapel
        ");
        $this->db->from("absensi");
        $this->db->join("absensi_mapel", "absensi.id = absensi_mapel.absen_id", "left");
        $this->db->where($where);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }

        return FALSE;
    }




    public function getOneBy($where = array())
    {
        $this->db->select("absensi.*")
            ->from("absensi")
            ->where("absensi.is_deleted", 0)
            ->where($where)
            ->order_by("absensi.id", "DESC")
            ->limit(1);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }

    public function getLastAbsenAndValidate($id_user)
    {
        // Ambil data terakhir TANPA filter is_check_in atau tanggal
        $sql = "
            SELECT * FROM absensi
            WHERE id_user = ?
              AND is_deleted = 0
            ORDER BY id DESC
            LIMIT 1
        ";

        $query = $this->db->query($sql, [$id_user]);
        $absen = $query->row();

        // Validasi di PHP: jika tidak check_out dan bukan hari ini
        if ($absen) {
            $is_check_out = $absen->is_check_in === 'check_out';
            $is_today = date('Y-m-d', strtotime($absen->tanggal_absen)) === date('Y-m-d');

            if (!$is_check_out && !$is_today) {
                return $absen; // hanya kalau lolos validasi
            }
        }

        return false; // tidak ada yang valid
    }


    public function getOneConfig($where = array())
    {
        $this->db->select("config_check.*")->from("config_check");
        $this->db->where($where);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }

    public function getUser($where = array())
    {
        $this->db->select("users.*")->from("users");
        $this->db->where('users.is_deleted', 0);
        $this->db->where($where);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }


    public function getConfigMaster($id_user)
    {
        $this->db->select('config_waktu_master.*, config_waktu_detail.*');
        $this->db->from('config_waktu_master');
        $this->db->join('config_waktu_detail', 'config_waktu_detail.id_config_master = config_waktu_master.id');
        $this->db->where('config_waktu_detail.id_user', $id_user);
        $this->db->where('config_waktu_master.is_deleted', 0);

        return $this->db->get()->result();
    }

    public function getMapelTerdekat($id_user)
    {
        $now = date('H:i:s');
        $hari_ini = date('l');

        $hari_indonesia = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $hari_db = $hari_indonesia[$hari_ini];

        // var_dump($id_user);die;
        $this->db->select('*')
            ->from('mapel_detail')
            ->where('id_user', $id_user)
            ->where('is_deleted', 0)
            ->where('hari', $hari_db)
            ->limit(1);

        return $this->db->get()->row();
    }
    public function getMapelTerdekatNotOne($id_user, $tanggal_absen)
    {
        $tanggal_real = formatTanggal($tanggal_absen); // contoh: "Senin, 26 Mei 2025"

        // Ambil hari dari $tanggal_absen, bukan dari waktu saat ini
        $hari_en = date('l', strtotime($tanggal_absen)); // hasil: Monday, Tuesday, etc

        // Mapping ke Bahasa Indonesia
        $hari_indonesia = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $hari_db = $hari_indonesia[$hari_en]; // hasil: "Senin", dst

        // Query berdasarkan hari dari tanggal absen yang dimasukkan
        return $this->db->select('*')
            ->from('mapel_detail')
            ->where('id_user', $id_user)
            ->where('is_deleted', 0)
            ->where('hari', $hari_db)
            ->get()
            ->result();
    }


    public function checkMapelDiKonfigurasi($id_user, $tanggal_absen, $id_mapel)
    {
        $bulan_tahun = date('Y-m', strtotime($tanggal_absen));
        $tanggal = ltrim(date('d', strtotime($tanggal_absen)), '0');

        $master = $this->db->get_where('config_waktu_master', ['bulan_tahun' => $bulan_tahun])->row();
        if (!$master) return false;

        $detail = $this->db->get_where('config_waktu_detail', [
            'id_user' => $id_user,
            'id_config_master' => $master->id,
            'id_mapel' => $id_mapel,
            'tanggal' => $tanggal,
        ])->row();

        return $detail ? true : false;
    }


    public function getStatusMapelByAbsenId($absen_id)
    {
        $absen =  $this->db->get_where('absensi_mapel', [
            'absen_id' => $absen_id
        ])->result();
        return $absen;
    }


    public function getById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('absensi');
        return $query->row();
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

    function getAllBy($limit, $start, $search, $col, $dir, $where)
    {
        $this->db->select("absensi.*, users.first_name as nama_user")->from("absensi");
        $this->db->where($where);
        $this->db->join("users", "users.id = absensi.id_user", "left");
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
    function getAllByGroup($limit, $start, $search, $col, $dir, $where)
    {
        $this->db->select("absensi.*, users.first_name as nama_user")->from("absensi");
        $this->db->where($where);
        $this->db->join("users", "users.id = absensi.id_user", "left");
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
    function getCountAllBy($limit, $start, $search, $order, $dir, $where)
    {
        $this->db->select("absensi.*")->from("absensi");
        $this->db->where($where);
        if (!empty($search)) {
            foreach ($search as $key => $value) {
                $this->db->or_like($key, $value);
            }
        }
        $result = $this->db->get();
        return $result->num_rows();
    }

    public function getCountAllById($where)
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

    public function getAttendanceByDate($id_user, $date)
    {
        $this->db->select('*');
        $this->db->from('absensi');
        $this->db->where('id_user', $id_user);
        $this->db->like('tanggal_absen', $date);
        $this->db->where('is_deleted', 0);
        $this->db->limit(2);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    public function insert_or_update_status_mapel($data)
    {
        $exists = $this->db->get_where('absensi_mapel', [
            'id_mapel' => $data['id_mapel'],
            'absen_id' => $data['absen_id']
        ])->row();

        if ($exists) {
            $this->db->where([
                'id_mapel' => $data['id_mapel'],
                'absen_id' => $data['absen_id']
            ]);
            return $this->db->update('absensi_mapel', [
                'status' => $data['status'],
                'updated_by' => $data['created_by'],
                'updated_at' => $data['created_at']
            ]);
        } else {
            return $this->db->insert('absensi_mapel', $data);
        }
    }
}
