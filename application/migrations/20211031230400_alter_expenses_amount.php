<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_expenses_amount extends CI_Migration { 
        
        public function up()
        {
                $modify2 = array(
                        'amount' => array(
                                'type' => 'decimal(10,2)',
                                'null' => false,
                        ),
                );
                $this->dbforge->modify_column('expenses', $modify2);
                
                $modify1 = array(
                        'amount' => array(
                                'type' => 'decimal(10,2)'
                        ),
                );
                $this->dbforge->modify_column('expenses_payments', $modify1);
        }

        public function down()
        {
                //Do nothing!
        }
}