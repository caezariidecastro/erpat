<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_attendance_status extends CI_Migration { 
        
        public function up()
        {
                $status_column = array(
                        "`status` ENUM('incomplete','pending','approved', 'rejected', 'clockout') NOT NULL DEFAULT 'incomplete'",
                );
                $this->dbforge->modify_column('attendance', $status_column);  
        }

        public function down()
        {
                //None
        }
}