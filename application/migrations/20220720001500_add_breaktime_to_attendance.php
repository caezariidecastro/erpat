<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_breaktime_to_attendance extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        "`break_time` TEXT NULL DEFAULT NULL AFTER `out_time`"
                );
                $this->dbforge->add_column('attendance', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('attendance', 'break_time');
        }
}