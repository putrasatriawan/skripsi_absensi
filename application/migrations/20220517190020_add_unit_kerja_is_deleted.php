<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_unit_kerja_is_deleted extends CI_Migration
{

    public function up()
    {
        $fields_notif = array(

            'is_deleted' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),


        );
        $this->dbforge->add_column('unit_kerja', $fields_notif);
    }
    public function down()
    {

    }

}