<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_epass_override extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'override' => array(
                                'type' => 'TINYINT',
                                'constraint' => 1,
                                'default' => '0',
                                'after' => 'status'
                        )
                );
                $this->dbforge->add_column('event_pass', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('event_pass', 'override');
        }
}