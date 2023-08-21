<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_alter_inventory_items_remove_delivery extends CI_Migration { 
        
        public function up()
        {
                $this->dbforge->drop_column('inventory_items', 'delivery_reference_no');
        }

        public function down()
        {
                //Do nothing.
        }
}