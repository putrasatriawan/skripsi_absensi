<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_elearning_tugas extends CI_Migration
{

    public function up()
    {
        $fields_notif = array(

            'is_tugas' => array(
                'type' => 'TINYINT',
                'null' => TRUE,
            ),
            'batas_submit' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        );
        $this->dbforge->add_column('elearning', $fields_notif);
    }

    public function down()
    {

    }
}