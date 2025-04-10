<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Migration_create_table_master_user
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_table_master_user extends CI_Migration
{
    public function up()
    {
        $table = "master_user";
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE,
                'unsigned' => TRUE,
            ],
            'users_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'nip' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'jenis_kelamin' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'no_hp' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'agama' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'agama' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'alamat' => [
                'type' => 'TEXT',
            ],
            'gaji' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tempat_lahir' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tanggal_lahir' => [
                'type' => 'DATE',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'updated_by' => [
                'type' => 'INT',
                'null' => TRUE,
            ],
            'created_by' => [
                'type' => 'INT',
                'null' => TRUE,
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
        $table = "master_user";
        if ($this->db->table_exists($table)) {
            $this->dbforge->drop_table($table, TRUE);
        }
    }
}
