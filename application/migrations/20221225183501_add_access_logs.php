<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_access_logs extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'device_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'user_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'remarks' => array(
                    'type' => 'TEXT',
                    'null' => true
                ),
                'timestamp' => array(
                    'type' => 'datetime',
                    'null' => false
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

            $result = $this->dbforge->create_table('access_logs', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_access_logs error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('access_logs',TRUE); //DROP TABLE IF EXISTS table_name
        }
}