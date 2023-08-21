<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Drop_payroll_table extends CI_Migration { 
        
        public function up()
        {
                $this->dbforge->drop_table('payroll', TRUE);
        }

        public function down()
        {
                //Do nothing
        }
}