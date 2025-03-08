<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_description_menu extends CI_Migration
{

    public function up()
    {
        $fields_notif = array(

            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),


        );
        $this->dbforge->add_column('menu', $fields_notif);
    }
    public function down()
    {

    }

}