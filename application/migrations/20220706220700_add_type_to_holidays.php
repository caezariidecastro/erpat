<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_type_to_holidays extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        "`type` enum('unofficial','regular','special') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unofficial' after date_to"
                );
                $this->dbforge->add_column('holidays', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('holidays', 'type');
        }
}