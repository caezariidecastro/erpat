<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_image_to_products extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        "`image` VARCHAR(180) NOT NULL AFTER `description`"
                );
                $this->dbforge->add_column('inventory_items', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('inventory_items', 'image');
        }
}