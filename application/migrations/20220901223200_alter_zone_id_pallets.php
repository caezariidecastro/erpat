<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_zone_id_pallets extends CI_Migration { 
        
        public function up()
        {
                $this->db->query("ALTER TABLE `pallets` ADD `zone_id` INT DEFAULT NULL AFTER `position_id`;");
        }

        public function down()
        {
                $this->db->query("ALTER TABLE `pallets` DROP `zone_id`;");
        }
}