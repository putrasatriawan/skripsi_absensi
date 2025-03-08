<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_unit_kerja_created extends CI_Migration
{

    public function up()
    {
        $fields_notif = array(

            'created_by' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),
            'updated_by' => array(
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