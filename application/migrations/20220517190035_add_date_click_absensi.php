<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_date_click_absensi extends CI_Migration
{

    public function up()
    {
        $fields_notif = array(

            'date_click_in' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),

        );
        $this->dbforge->add_column('absensi', $fields_notif);
    }
    public function down()
    {

    }

}