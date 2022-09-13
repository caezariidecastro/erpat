<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_labels_context_services extends CI_Migration { 
        
        public function up()
        {
                $this->db->query("ALTER TABLE `labels` CHANGE `context` `context` ENUM('event','invoice','note','project','task','ticket','to_do','asset_entry','zones','racks','bays','levels','positions','pallets','users','services') DEFAULT NULL;");
                $this->db->query("ALTER TABLE `services` ADD `labels` TEXT NOT NULL AFTER `rate`;");
        }

        public function down()
        {
                $this->db->query("ALTER TABLE `labels` CHANGE `context` `context` ENUM('event','invoice','note','project','task','ticket','to_do','asset_entry','zones','racks','bays','levels','positions','pallets','users') DEFAULT NULL;");
                $this->db->query("ALTER TABLE `services` DROP `labels`;");
        }
}