<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_group_to_eventpass extends CI_Migration { 
        
        public function up()
        {
                $group_name = array(
                        'group_name' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 50,
                                'null' => true,
                                'after' => 'seats'
                        ),
                );
                $this->dbforge->add_column('event_pass', $group_name);
        }

        public function down()
        {
                $this->dbforge->drop_column('group_name', 'event_pass');
        }
}