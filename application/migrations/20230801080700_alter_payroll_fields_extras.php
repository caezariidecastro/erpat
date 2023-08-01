<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_payroll_fields_extras extends CI_Migration { 
        
        public function up()
        {
                $this->db->query("ALTER TABLE `payrolls` CHANGE `department` `department` TEXT NULL DEFAULT NULL;");
        }

        public function down()
        {
               //No need to revert
        }
}