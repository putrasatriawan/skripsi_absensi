<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_rfid_uid extends CI_Migration
{

    public function up()
    {
        $fields_notif = array(

            'rfid_uid' => array(
                'type' => 'VARCHAR(255)',
                'null' => TRUE,
            ),
        );
        $this->dbforge->add_column('siswa', $fields_notif);
    }

    public function down()
    {

    }
}