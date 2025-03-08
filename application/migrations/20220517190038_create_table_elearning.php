<?php
/**
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Migration_create_table_api_limits
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_table_elearning extends CI_Migration
{
	public function up()
	{
		$table = "elearning";
		$fields = array(
			'id' => [
				'type' => 'INT(11)',
				'auto_increment' => TRUE,
				'unsigned' => TRUE,
			],
			'id_pengampu' => [
				'type' => 'INT(11)',
			],
			'id_mapel' => [
				'type' => 'INT(11)',
			],
			'id_kelas' => [
				'type' => 'INT(11)',
			],
			'nama_materi' => [
				'type' => 'VARCHAR(225)',
				'type' => 'TEXT',
			],
			'deskripsi_materi' => [
				'type' => 'TEXT',
			],
			'tanggal_tayang' => [
				'type' => 'DATE',
			],
			'tanggal_insert  timestamp DEFAULT CURRENT_TIMESTAMP',
			'is_deleted' => [
				'type' => 'TINYINT(1)',
				'default' => 0,
			],
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table($table);
	}

	public function down()
	{
		$table = "elearning";
		if ($this->db->table_exists($table)) {
			$this->db->query(drop_foreign_key($table, 'api_key'));
			$this->dbforge->drop_table($table);
		}
	}

}