<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_terms_warranty_invoice extends CI_Migration { 
        
        public function up()
        {
                $this->db->query("ALTER TABLE `invoices` ADD `enable_terms` INT DEFAULT 0 AFTER `files`;");
                $this->db->query("ALTER TABLE `invoices` ADD `enable_warranty` INT DEFAULT 0 AFTER `enable_terms`;");
        }

        public function down()
        {
                $this->db->query("ALTER TABLE `invoices` DROP `enable_terms`;");
                $this->db->query("ALTER TABLE `invoices` DROP `enable_warranty`;");
        }
}