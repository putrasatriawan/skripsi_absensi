<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Migration_create_table_config_check
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_table_config_check extends CI_Migration
{
    public function up()
    {
        $table = "config_check";
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE,
                'unsigned' => TRUE,
            ],
            'roles_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'check_in' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'check_out' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($table, TRUE);
    }

    public function down()
    {
        $table = "config_check";
        if ($this->db->table_exists($table)) {
            $this->dbforge->drop_table($table, TRUE);
        }
    }
}
