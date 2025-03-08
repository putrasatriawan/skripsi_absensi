<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_foto_absensi extends CI_Migration
{

    public function up()
    {
        $fields_notif = array(

            'photo' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
        );
        $this->dbforge->add_column('absensi_sub', $fields_notif);
    }

    public function down()
    {

    }
}