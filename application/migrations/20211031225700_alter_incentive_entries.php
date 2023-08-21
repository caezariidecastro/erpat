<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_incentive_entries extends CI_Migration { 
        
        public function up()
        {
                $modify = array(
                        'amount' => array(
                                'type' => 'decimal(10,2)',
                                'null' => false,
                                'after' => 'id'
                        ),
                );
                $this->dbforge->modify_column('incentive_entries', $modify);
        }

        public function down()
        {
                $this->dbforge->drop_column('incentive_entries', 'amount');
        }
}