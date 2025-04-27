<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Migration_create_table_absensi
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_table_absensi extends CI_Migration
{
    public function up()
    {
        $table = "absensi";
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE,
                'unsigned' => TRUE,
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'tanggal_absen' => [
                'type' => 'DATE',
            ],
            'photo' => [
                'type' => 'LONGTEXT',
            ],
            'id_role' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'tanggal_insert' => [
                'type' => 'TIMESTAMP',
                'null' => TRUE,
            ],
            'is_deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'check_in_const' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'check_out_const' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'distance' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'status_work' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'init_time' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'is_check_in' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($table, TRUE);
    }

    public function down()
    {
        $table = "absensi";
        if ($this->db->table_exists($table)) {
            $this->dbforge->drop_table($table, TRUE);
        }
    }
}
