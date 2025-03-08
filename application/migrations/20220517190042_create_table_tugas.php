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
class Migration_create_table_tugas extends CI_Migration
{
	public function up()
	{
		$table = "tugas";
		$fields = array(
			'id' => [
				'type' => 'INT(11)',
				'auto_increment' => TRUE,
				'unsigned' => TRUE,
			],
			'id_elearning' => [
				'type' => 'INT(11)',
			],
			'id_siswa' => [
				'type' => 'INT(11)',
			],
			'deskripsi_tugas' => [
				'type' => 'TEXT',
			],
			'file_tugas' => array(
                'type' => 'VARCHAR(200)',
                'null' => TRUE,
            ),
			'tanggal_upload' => [
				'type' => 'DATETIME',
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
		$table = "tugas";
		if ($this->db->table_exists($table)) {
			$this->db->query(drop_foreign_key($table, 'api_key'));
			$this->dbforge->drop_table($table);
		}
	}

}