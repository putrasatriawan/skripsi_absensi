<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_tanggal_akhir_tayang extends CI_Migration
{

    public function up()
    {
        $fields_notif = array(

            'tanggal_akhir_tayang' => array(
                'type' => 'DATE',
            ),
        );
        $this->dbforge->add_column('elearning', $fields_notif);
    }

    public function down()
    {

    }
}