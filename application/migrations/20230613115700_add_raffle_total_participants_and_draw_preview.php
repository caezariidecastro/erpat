<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_raffle_total_participants_and_draw_preview extends CI_Migration { 
        
        public function up()
        {
           $fields = array(
                'total_participants' => array(
                    'type' => 'INT',
                    'constraint' => 7,
                    'after' => 'winners'
                ),
                "`draw_preview` enum('event_draw','instant_show','via_email') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'event_draw' after total_participants"
            );
            $this->dbforge->add_column('event_raffle', $fields);
        }

        public function down()
        {
            $this->dbforge->drop_column('event_raffle', 'total_participants');
            $this->dbforge->drop_column('event_raffle', 'draw_preview');
        }
}