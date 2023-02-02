<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_epass_tickets extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'tickets' => array(
                                'type' => 'TEXT',
                                'null' => true,
                                'after' => 'seat_assign'
                        )
                );
                $this->dbforge->add_column('event_pass', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('event_pass', 'tickets');
        }
}