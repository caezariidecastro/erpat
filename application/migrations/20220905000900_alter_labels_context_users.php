<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_labels_context_users extends CI_Migration { 
        
        public function up()
        {
                $this->db->query("ALTER TABLE `labels` CHANGE `context` `context` ENUM('event','invoice','note','project','task','ticket','to_do','asset_entry','zones','racks','bays','levels','positions','pallets','users') DEFAULT NULL;");
                $this->db->query("ALTER TABLE `users` ADD `labels` TEXT NOT NULL AFTER `last_online`;");
        }

        public function down()
        {
                $this->db->query("ALTER TABLE `labels` CHANGE `context` `context` ENUM('event','invoice','note','project','task','ticket','to_do','asset_entry','zones','racks','bays','levels','positions','pallets') DEFAULT NULL;");
                $this->db->query("ALTER TABLE `users` DROP `labels`;");
        }
}