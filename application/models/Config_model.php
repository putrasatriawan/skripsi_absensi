<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Config_model extends CI_Model
{

    public function get_setting($key)
    {
        $query = $this->db->get_where('config', array('key' => $key));
        if ($query->num_rows() > 0) {
            return $query->row()->value;
        } else {
            return null;
        }
    }
    public function get_config_chcek($id)
    {
        $query = $this->db->get_where('config_check', array('roles_id' => $id));
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function set_setting($key, $value)
    {
        $query = $this->db->get_where('config', array('key' => $key));

        if ($query->num_rows() > 0) {
            $this->db->where('key', $key);
            $this->db->update('config', array('value' => $value));
        } else {
            $this->db->insert('config', array('key' => $key, 'value' => $value));
        }
    }
    public function getAllById($where = array())
    {
        $this->db->select("config_check.*")->from("config_check");
        $this->db->where($where);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }
    public function getAllBy($limit, $start, $search, $col, $dir)
    {
        $this->db->select("roles.*, config_check.check_in as check_in, config_check.check_out as check_out, config_check.id as id_check")
            ->from("roles")
            ->join("config_check", "config_check.roles_id = roles.id", "left");

        $roles_default = array('1', '2');
        $this->db->where_not_in('roles.id', $roles_default);
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

    public function getCountAllBy($limit, $start, $search, $order, $dir)
    {
        $this->db->select("roles.*, config_check.check_in as check_in, config_check.check_out as check_out, config_check.id as id_check")
            ->from("roles")
            ->join("config_check", "config_check.roles_id = roles.id", "left");

        $roles_default = array('1', '2');
        $this->db->where_not_in('roles.id', $roles_default);
        if (!empty($search)) {
            foreach ($search as $key => $value) {
                $this->db->or_like($key, $value);
            }
        }

        $result = $this->db->get();
        return $result->num_rows();
    }
}
