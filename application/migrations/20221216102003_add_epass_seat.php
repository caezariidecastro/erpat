<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_epass_seat extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'block_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'seat_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ),
                'sort' => array(
                    'type' => 'INT',
                    'constraint' => 2,
                ),
                'remarks' => array(
                    'type' => 'INT',
                    'constraint' => 11,
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

            $result = $this->dbforge->create_table('epass_seat', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_epass_seat error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('epass_seat',TRUE); //DROP TABLE IF EXISTS table_name
        }
}