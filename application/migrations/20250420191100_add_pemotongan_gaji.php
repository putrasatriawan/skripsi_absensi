<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_pemotongan_gaji extends CI_Migration
{

    public function up()
    {
        $pemotongan = array(

            'pemotongan' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ),
            'type_pemotongan' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ),


        );
        $this->dbforge->add_column('master_user', $pemotongan);
    }
    public function down() {}
}
