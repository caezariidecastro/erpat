<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_sched_hours_payroll extends CI_Migration { 
        
        public function up()
        {
                $this->db->query("ALTER TABLE `payrolls` ADD `sched_hours` DECIMAL(10,2) NOT NULL AFTER `pay_date`;");
        }

        public function down()
        {
                $this->db->query("ALTER TABLE `payrolls` DROP `type`;");
        }
}