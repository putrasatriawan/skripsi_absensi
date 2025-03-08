<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_is_role extends CI_Migration
{

    public function up()
    {
        $fields_notif = array(

            'is_role' => array(
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