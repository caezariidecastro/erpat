<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_seat_assign_to_eventpass extends CI_Migration { 
        
        public function up()
        {
                $seat_assign = array(
                        'seat_assign' => array(
                                'type' => 'TEXT',
                                'null' => true,
                                'after' => 'seats'
                        ),
                        "`status` enum('draft','approved','cancelled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft' after remarks"
                );
                $this->dbforge->add_column('event_pass', $seat_assign);
        }

        public function down()
        {
                $this->dbforge->drop_column('seat_assign', 'event_pass');
                $this->dbforge->drop_column('status', 'event_pass');
        }
}