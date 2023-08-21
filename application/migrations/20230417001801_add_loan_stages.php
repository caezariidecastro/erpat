<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_loan_stages extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'loan_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),

                'stage_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 180,
                ),
                'serial_data' => array(
                    'type' => 'TEXT',
                    'null' => true
                ),
                'remarks' => array(
                    'type' => 'TEXT',
                    'null' => true
                ),
                'executed_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
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

            $result = $this->dbforge->create_table('loan_stages', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_loan_stages error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('loan_stages',TRUE); //DROP TABLE IF EXISTS table_name
        }
}