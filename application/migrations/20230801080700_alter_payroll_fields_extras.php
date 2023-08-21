<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_payroll_fields_extras extends CI_Migration { 
        
        public function up()
        {
                $this->db->query("ALTER TABLE `payrolls` CHANGE `department` `department` TEXT NULL DEFAULT NULL;");
                $this->db->query("ALTER TABLE `payrolls` ADD `deductions` TEXT NULL DEFAULT NULL AFTER `department`;");
                $this->db->query("ALTER TABLE `payrolls` ADD `earnings` TEXT NULL DEFAULT NULL AFTER `department`;");
        }

        public function down()
        {
                $this->dbforge->drop_column('payrolls', 'earnings');
                $this->dbforge->drop_column('payrolls', 'deductions');
        }
}