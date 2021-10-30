<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_expenses_payments extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'account_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'payment_method_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'expense_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'amount' => array(
                    'type' => 'DOUBLE'
                ),
                'payment_date' => array(
                    'type' => 'DATE'
                ),
                '	note' => array(
                    'type' => 'TEXT'
                ),
                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'created_at datetime default current_timestamp',
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => '0',
                ),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);

            $result = $this->dbforge->create_table('expenses_payments', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Hello");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('expenses_payments',TRUE); //DROP TABLE IF EXISTS table_name
        }
}