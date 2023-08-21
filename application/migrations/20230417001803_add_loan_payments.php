<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_loan_payments extends CI_Migration { 
        
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

                'preferred_date' => array(
                    'type' => 'datetime',
                    'null' => false
                ),
                'date_paid' => array(
                    'type' => 'datetime',
                    'null' => false
                ),
                "`late_interest` decimal(10,2) NOT NULL",
                "`amount` decimal(10,2) NOT NULL",
                'serial_data' => array(
                    'type' => 'TEXT',
                    'null' => true
                ),
                'remarks' => array(
                    'type' => 'TEXT',
                    'null' => true
                ),

                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
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

            $result = $this->dbforge->create_table('loan_payments', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_loan_payments error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('loan_payments',TRUE); //DROP TABLE IF EXISTS table_name
        }
}