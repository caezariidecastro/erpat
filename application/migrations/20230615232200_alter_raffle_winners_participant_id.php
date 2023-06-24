<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_raffle_winners_participant_id extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'participant_id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'after' => 'raffle_id'
                        ),
                );
                $this->dbforge->add_column('event_raffle_winners', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('event_raffle_winners', 'participant_id');
        }
}