<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_loans extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'category_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true
                ),

                'borrower_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => false
                ),
                'cosigner_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true
                ),
                'remarks' => array(
                    'type' => 'TEXT',
                    'null' => true
                ),

                "`principal_amount` decimal(10,2) NOT NULL",
                "`interest_rate` decimal(10,2) NOT NULL", //annual
                "`min_payment` decimal(10,2) NOT NULL", //ammortization
                'months_topay' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => false
                ),
                'days_before_due' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 7
                ),
                "`penalty_rate` decimal(10,2) NOT NULL", //monthly

                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'start_payment' => array(
                    'type' => 'datetime',
                    'null' => true
                ),
                'date_applied' => array(
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

            $result = $this->dbforge->create_table('loans', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_loans error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('loans',TRUE); //DROP TABLE IF EXISTS table_name
        }
}