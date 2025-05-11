<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Migration_create_table_absen_mapel
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_table_absen_mapel extends CI_Migration
{
    public function up()
    {
        $table = "absensi_mapel";
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE,
                'unsigned' => TRUE,
            ],
            'absen_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'id_mapel' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => TRUE,
            ],
            'created_by' => [
                'type' => 'INT',
                'null' => TRUE,
            ],
            'updated_by' => [
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
        $table = "absensi_mapel";
        if ($this->db->table_exists($table)) {
            $this->dbforge->drop_table($table, TRUE);
        }
    }
}
