<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Migration_create_table_jadwal_mapel
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_table_jadwal_mapel extends CI_Migration
{
	public function up()
	{
		// Tabel jadwal_mapel
		$table = "jadwal_mapel";
		$fields = array(
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
				'constraint' => 20,
			],
			'created_at  timestamp DEFAULT CURRENT_TIMESTAMP',
			'updated_at' => [
				'type' => 'DATETIME',
				'null' => TRUE,
			]
		);
		
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table($table);
	}

	public function down()
	{
		$table = "jadwal_mapel";
		if ($this->db->table_exists($table)) {
			$this->dbforge->drop_table($table);
		}
	}
}
