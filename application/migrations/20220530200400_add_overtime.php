<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_overtime extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'user_id' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'default' => '',
                    'null' => true
                ),
                'start_time' => array(
                    'type' => 'DATETIME',
                    'default' => NULL,
                    'null' => true
                ),
                'end_time' => array(
                    'type' => 'DATETIME',
                    'default' => NULL,
                    'null' => true
                ),
                'notes' => array(
                    'type' => 'TEXT',
                    'default' => '',
                    'null' => true
                ),
                'approved_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true
                ),
                'updated_at timestamp default current_timestamp on update current_timestamp',
                'date_created' => array(
                    'type' => 'DATETIME',
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

            $result = $this->dbforge->create_table('overtime', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_overtime error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('overtime',TRUE); //DROP TABLE IF EXISTS table_name
        }
}