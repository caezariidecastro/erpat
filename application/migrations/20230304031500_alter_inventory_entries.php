<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_inventory_entries extends CI_Migration { 
        
        public function up()
        {
                $this->db->query("ALTER TABLE `inventory` ADD `kind` ENUM('finished_goods','raw_materials','work_in_process') NOT NULL AFTER `vendor`;");
        }

        public function down()
        {
                $this->dbforge->drop_column('inventory', 'kind');
        }
}