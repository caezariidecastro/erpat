<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_epass_guest extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'guest' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 36,
                                'null' => true,
                                'after' => 'user_id'
                        )
                );
                $this->dbforge->add_column('event_pass', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('event_pass', 'guest');
        }
}