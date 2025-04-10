<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Migration_create_table_mapel_detail
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_table_mapel_detail extends CI_Migration
{
    public function up()
    {
        $table = "mapel_detail";
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE,
                'unsigned' => TRUE,
            ],
            'jadwal_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'id_user' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'hari' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'nama_mapel' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'jam_mulai' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'jam_selesai' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'durasi' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'is_deleted' => [
                'type' => 'INT',
            ],
        ];
        $this->dbforge->add_field("created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($table, TRUE);
    }

    public function down()
    {
        $table = "mapel_detail";
        if ($this->db->table_exists($table)) {
            $this->dbforge->drop_table($table, TRUE);
        }
    }
}
