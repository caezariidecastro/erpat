<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_payslips_sents extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'payslip_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true
                ),

                'serialized' => array(
                    'type' => 'TEXT',
                    'default' => '',
                    'null' => false
                ),
                'remarks' => array(
                    'type' => 'TEXT',
                    'default' => '',
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

            $result = $this->dbforge->create_table('payslips_sents', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_payslips_sents error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('payslips_sents',TRUE); //DROP TABLE IF EXISTS table_name
        }
}