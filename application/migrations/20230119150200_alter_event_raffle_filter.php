<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_event_raffle_filter extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        "`raffle_type` enum('countdown','spinner','wheel','mosaic') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'countdown' AFTER winners",
                        'crowd_type' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 180,
                                'null' => true,
                                'after' => 'raffle_type'
                        ),
                );
                $this->dbforge->add_column('event_raffle', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('event_raffle', 'raffle_type');
                $this->dbforge->drop_column('event_raffle', 'crowd_type');
        }
}