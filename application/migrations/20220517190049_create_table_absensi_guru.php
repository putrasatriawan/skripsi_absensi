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
class Migration_create_table_absensi_guru extends CI_Migration
{
	public function up()
	{
		$table = "absensi_guru";
		$fields = array(
			'id' => [
				'type' => 'INT(11)',
				'auto_increment' => TRUE,
				'unsigned' => TRUE,
			],
			'id_user' => [
				'type' => 'INT(11)',
			],
			'tanggal_absen' => [
				'type' => 'DATETIME',
			],
			'photo' => array(
				'type' => 'LONGTEXT',
			),
			'id_role' => [
				'type' => 'INT(11)',
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
		$table = "absensi_guru";
		if ($this->db->table_exists($table)) {
			$this->db->query(drop_foreign_key($table, 'api_key'));
			$this->dbforge->drop_table($table);
		}
	}

}