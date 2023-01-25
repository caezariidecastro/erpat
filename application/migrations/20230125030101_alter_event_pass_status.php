<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_event_pass_status extends CI_Migration { 
        
        public function up()
        {
                $this->db->query("ALTER TABLE `event_pass` CHANGE `status` `status` ENUM('draft','approved','cancelled','sent') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft';");
        }

        public function down()
        {
                $this->db->query("ALTER TABLE `event_pass` CHANGE `status` `status` ENUM('draft','approved','cancelled') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft';");
        }
}