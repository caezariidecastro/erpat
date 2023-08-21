<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_event_raffle_winners extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'uuid' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 36,
                    'null' => true
                ),
                'raffle_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'user_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'labels' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 1,
                ),
                'remarks' => array(
                    'type' => 'TEXT',
                    'null' => true
                ),
                'updated_at timestamp default current_timestamp on update current_timestamp',
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => '0',
                ),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);

            $result = $this->dbforge->create_table('event_raffle_winners', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_event_raffle_winners error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('event_raffle_winners',TRUE); //DROP TABLE IF EXISTS table_name
        }
}