<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_access_devices extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'api_key' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 36,
                    'null' => true
                ),
                'api_secret' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 120,
                    'null' => true
                ),
                'device_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 36,
                    'null' => true
                ),
                'passes' => array(
                    'type' => 'TEXT',
                    'null' => true
                ),
                'remarks' => array(
                    'type' => 'TEXT',
                    'null' => true
                ),
                'category_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'labels' => array(
                    'type' => 'text'
                ),
                'status' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => '1',
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

            $result = $this->dbforge->create_table('access_devices', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_access_devices error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('access_devices',TRUE); //DROP TABLE IF EXISTS table_name
        }
}