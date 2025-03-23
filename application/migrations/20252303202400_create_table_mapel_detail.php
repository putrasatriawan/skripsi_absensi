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
		// Tabel mapel_detail
		$table = "mapel_detail";
		$fields = array(
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
				'type' => 'INT',
				'constraint' => 11,
			],
			'nama_mapel' => [
				'type' => 'VARCHAR',
				'constraint' => 100,
			],
			'jam_mulai' => [
				'type' => 'TIME',
			],
			'jam_selesai' => [
				'type' => 'TIME',
			],
			'durasi' => [
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
		$table = "mapel_detail";
		if ($this->db->table_exists($table)) {
			$this->dbforge->drop_table($table);
		}
	}
}
