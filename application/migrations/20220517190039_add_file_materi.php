<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_file_materi extends CI_Migration
{

    public function up()
    {
        $fields_notif = array(

            'file_materi' => array(
                'type' => 'VARCHAR(200)',
                'null' => TRUE,
            ),
        );
        $this->dbforge->add_column('elearning', $fields_notif);
    }

    public function down()
    {

    }
}