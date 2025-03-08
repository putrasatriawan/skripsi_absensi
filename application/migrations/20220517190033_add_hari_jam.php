<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_hari_jam extends CI_Migration
{

    public function up()
    {
        $fields_notif = array(

            'hari' => array(
                'type' => 'VARCHAR(8)',
                'null' => TRUE,
            ),
            'jam' => array(
                'type' => 'VARCHAR(16)',
                'null' => TRUE,
            ),
            'ruang_id' => array(
                'type' => 'INT(8)',
                'null' => TRUE,
            ),

        );
        $this->dbforge->add_column('pengampu_sub', $fields_notif);
    }
    public function down()
    {

    }

}