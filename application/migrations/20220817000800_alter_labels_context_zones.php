<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_labels_context_zones extends CI_Migration { 
        
        public function up()
        {
                $this->db->query("ALTER TABLE `labels` CHANGE `context` `context` ENUM('event','invoice','note','project','task','ticket','to_do','asset_entry','zones') DEFAULT NULL;");
        }

        public function down()
        {
                $this->dbforge->drop_column('labels', 'rfid_num');
        }
}