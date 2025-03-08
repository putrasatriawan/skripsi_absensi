<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_unit_kerja_users extends CI_Migration
{

    public function up()
    {
        $fields_notif = array(

            'unit_kerja' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),


        );
        $this->dbforge->add_column('users', $fields_notif);
    }
    public function down()
    {

    }

}