<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_kode_kelas_id extends CI_Migration
{

    public function up()
    {
        $fields_notif = array(

            'kode_kelas_id' => array(
                'type' => 'INT(8)',
                'null' => TRUE,
            ),

        );
        $this->dbforge->add_column('siswa', $fields_notif);
    }
    public function down()
    {

    }

}