<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_type_estimate extends CI_Migration { 
        
        public function up()
        {
                $this->db->query("ALTER TABLE `estimates` DROP `type`;");
        }

        public function down()
        {
                $this->db->query("ALTER TABLE `estimates` ADD `type` ENUM('service','product') NOT NULL AFTER `project_id`;");
        }
}