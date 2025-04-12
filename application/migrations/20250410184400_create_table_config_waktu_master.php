<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Migration_create_table_config_waktu
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_table_config_waktu_master extends CI_Migration
{
    public function up()
    {
        $table = "config_waktu_master";
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE,
                'unsigned' => TRUE,
            ],
            'kode' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'bulan_tahun' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'keterangan' => [
                'type' => 'TEXT',
            ],
            'is_deleted' => [
                'type' => 'INT',
                'null' => TRUE,
            ],
        ];
        $this->dbforge->add_field("created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($table, TRUE);
    }

    public function down()
    {
        $table = "config_waktu_master";
        if ($this->db->table_exists($table)) {
            $this->dbforge->drop_table($table, TRUE);
        }
    }
}
