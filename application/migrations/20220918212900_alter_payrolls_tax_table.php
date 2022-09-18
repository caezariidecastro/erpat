<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_payrolls_tax_table extends CI_Migration { 
        
        public function up()
        {
                $this->db->query("ALTER TABLE `payrolls` ADD `tax_table` ENUM('daily','weekly','biweekly','monthly') NOT NULL AFTER `signed_by`;");
        }

        public function down()
        {
                $this->dbforge->drop_column('payrolls', 'tax_table');
        }
}