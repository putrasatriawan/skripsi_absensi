<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Mapel_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getAllById($where = array())
    {
        $this->db->select("mapel.*")->from("mapel");
        $this->db->where($where);
        $this->db->where("mapel.is_deleted", 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    public function getOneBy($where = array())
    {
        $this->db->select("mapel.*")->from("mapel");
        $this->db->where("mapel.is_deleted", 0);
        $this->db->where($where);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }
    public function get_by_code($code)
    {
        $this->db->where('code', $code);
        $query = $this->db->get('mapel');

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('mapel');  // Assuming the table name is 'mapel'
        return $query->row(); // Returns a single row (object)
    }

    public function insert($data)
    {

        $this->db->insert('mapel', $data);
        return $this->db->insert_id();
    }
    public function update($data, $where)
    {
        $this->db->update('mapel', $data, $where);
        return $this->db->affected_rows();
    }
    public function delete($where)
    {
        $this->db->where($where);
        $this->db->delete('mapel');
        if ($this->db->affected_rows()) {
            return TRUE;
        }
        return FALSE;
    }
    public function insert_batch($data)
    {
        $this->db->insert_batch('mapel', $data);
        return $this->db->insert_id();
    }
    function getAllBy($limit, $start, $search, $col, $dir)
    {
        $this->db->select("mapel.*")->from("mapel");

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
        $this->db->select("mapel.*")->from("mapel");
        if (!empty($search)) {
            foreach ($search as $key => $value) {
                $this->db->or_like($key, $value);
            }
        }
        $result = $this->db->get();
        return $result->num_rows();
    }
    public function getRekamanByUnit($tables)
    {
        $result = array();

        foreach ($tables as $table) {

            $this->db->select('uk.name, COUNT(m.id) AS count')
                ->from('mapel uk')
                ->join($table . ' m', 'uk.id = m.mapel', 'left')
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
                ->from('mapel uk')
                ->join($table . ' m', 'uk.id = m.mapel', 'left')
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
        $this->db->select("COUNT(*) as total_rows")->from("mapel");
        $this->db->where($where);
        $this->db->where("mapel.is_deleted", 0);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->total_rows;
        }

        return 0; // Mengembalikan 0 jika tidak ada baris yang ditemukan.
    }

    public function getByCode($code)
    {
        $this->db->where('code', $code);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->total_rows;
        }
        return 0;
    }
}
