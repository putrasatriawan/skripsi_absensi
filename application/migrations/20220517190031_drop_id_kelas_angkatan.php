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
class Migration_drop_id_kelas_angkatan extends CI_Migration
{


	public function up()
    {
        $this->dbforge->drop_column('siswa', 'id_kelas');
        $this->dbforge->drop_column('siswa', 'angkatan');
    }
    public function down()
    {

    }
}
