<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Config_model extends CI_Model {

    public function get_setting($key)
    {
        $query = $this->db->get_where('config', array('key' => $key));
        if ($query->num_rows() > 0) {
            return $query->row()->value;
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
}
