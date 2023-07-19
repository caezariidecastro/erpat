<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_loans_add_payroll_binding extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        "`payroll_binding` enum('none','daily','weekly','bi-weekly','monthly') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'none' after created_by"
                );
                $this->dbforge->add_column('loans', $fields);
        }

        public function down()
        {
               //No need to revert
        }
}