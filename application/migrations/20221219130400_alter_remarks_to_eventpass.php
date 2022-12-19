<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_remarks_to_eventpass extends CI_Migration { 
        
        public function up()
        {
                $this->db->query('ALTER TABLE `event_pass` CHANGE `remarks` `remarks` TEXT NULL DEFAULT NULL;');
        }

        public function down()
        {
                //None
        }
}