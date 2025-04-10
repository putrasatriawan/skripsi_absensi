<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Migration_create_table_role_schedule
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_table_jadwal_mapel extends CI_Migration
{
    public function up()
    {
        $table = "jadwal_mapel";
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
            'hari' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'check_out' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
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
        $table = "role_schedule";
        if ($this->db->table_exists($table)) {
            $this->dbforge->drop_table($table, TRUE);
        }
    }
}
