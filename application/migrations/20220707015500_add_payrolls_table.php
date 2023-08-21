<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_payrolls_table extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                "`category` int(11) NOT NULL",
                "`department` int(11) NOT NULL",
                "`start_date` date DEFAULT NULL",
                "`end_date` date DEFAULT NULL",
                "`pay_date` date DEFAULT NULL",
                "`remarks` text NOT NULL",
                "`account_id` int(11) DEFAULT NULL",
                "`expense_id` int(11) DEFAULT NULL",
                "`signed_by` int(11) DEFAULT NULL",
                "`status` enum('draft','cancelled','ongoing','completed') NOT NULL DEFAULT 'draft'",
                "`created_by` int(11) DEFAULT NULL",
                "`timestamp` timestamp NULL DEFAULT NULL",
                'verified_at' => array(
                    'type' => 'timestamp',
                    'default' => NULL,
                    'null' => true
                ),
                '`updated_at` timestamp default current_timestamp on update current_timestamp',
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => '0',
                ),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);

            $result = $this->dbforge->create_table('payrolls', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_payrolls_table error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('payrolls',TRUE); //DROP TABLE IF EXISTS table_name
        }
}