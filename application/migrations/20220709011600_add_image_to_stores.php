<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_image_to_stores extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        "`image` VARCHAR(180) NOT NULL AFTER `description`"
                );
                $this->dbforge->add_column('stores', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('stores', 'image');
        }
}