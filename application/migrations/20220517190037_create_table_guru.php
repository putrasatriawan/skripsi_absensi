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
class Migration_create_table_guru extends CI_Migration
{


	public function up()
	{
		$table = "guru";
		$fields = array(
			'id' => [
				'type' => 'INT(11)',
				'auto_increment' => TRUE,
				'unsigned' => TRUE,
			],
			'users_id' => [
				'type' => 'INT(11)',
			],
			'nip' => [
				'type' => 'VARCHAR(225)',
			],
			'name' => [
				'type' => 'VARCHAR(225)',
			],
			'jenis_kelamin' => [
				'type' => 'VARCHAR(225)',
			],
			'no_hp' => [
				'type' => 'VARCHAR(225)',
			],
			'agama' => [
				'type' => 'VARCHAR(225)',
			],
			'alamat' => [
				'type' => 'VARCHAR(255)',
			],
			'gaji' => [
				'type' => 'DECIMAL',
				'constraint' => '10,2',
			],
			'tempat_lahir' => [
				'type' => 'VARCHAR(255)',
			],
			'tanggal_lahir' => [
				'type' => 'DATE',
			],
			'created_at  timestamp DEFAULT CURRENT_TIMESTAMP',
			'created_by' => array(
				'type' => 'INT',
				'constraint' => 11,
				'NULL' => TRUE,
			),
			'updated_by' => array(
				'type' => 'INT',
				'constraint' => 11,
				'NULL' => TRUE,
			),
			'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
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
		$table = "guru";
		if ($this->db->table_exists($table)) {
			$this->db->query(drop_foreign_key($table, 'api_key'));
			$this->dbforge->drop_table($table);
		}
	}
}