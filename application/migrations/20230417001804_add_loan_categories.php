<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_loan_categories extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'parent_id' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 36,
                    'default' => '',
                    'null' => true
                ),
                
                'name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 150,
                    'default' => '',
                    'null' => false
                ),
                'description' => array(
                    'type' => 'TEXT',
                    'default' => '',
                    'null' => false
                ),

                'status' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => '1',
                    'null' => false
                ),
                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true
                ),

                'timestamp' => array(
                    'type' => 'timestamp',
                    'default' => NULL,
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

            $result = $this->dbforge->create_table('loan_categories', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_loan_categories error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('loan_categories',TRUE); //DROP TABLE IF EXISTS table_name
        }
}