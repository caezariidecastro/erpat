<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_event_raffles extends CI_Migration { 
        
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
                'event_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'title' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 200,
                ),
                'description' => array(
                    'type' => 'TEXT',
                    'null' => true
                ),
                'winners' => array(
                    'type' => 'INT',
                    'constraint' => 7,
                ),
                'labels' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 1,
                ),
                'remarks' => array(
                    'type' => 'TEXT',
                    'null' => true
                ),
                'creator' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true
                ),
                "`ranking` enum('asc','desc') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'asc'", //only if winners is more than 1
                'draw_date' => array( //if this draw autimatic.
                    'type' => 'datetime',
                    'default' => NULL,
                    'null' => true
                ),
                "`status` enum('draft','active','cancelled','completed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft'",
                'updated_at timestamp default current_timestamp on update current_timestamp',
                'timestamp' => array(
                    'type' => 'datetime',
                    'default' => NULL,
                    'null' => true
                ),
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => '0',
                ),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);

            $result = $this->dbforge->create_table('event_raffle', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_event_raffle error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('event_raffle',TRUE); //DROP TABLE IF EXISTS table_name
        }
}