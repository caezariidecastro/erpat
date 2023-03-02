<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Drop_raw_materials extends CI_Migration { 
        
        public function up()
        {
                $this->dbforge->drop_table('materials', TRUE);
                $this->dbforge->drop_table('material_categories', TRUE);
                $this->dbforge->drop_table('material_inventory', TRUE);
                $this->dbforge->drop_table('material_inventory_stock_override', TRUE);
                $this->dbforge->drop_table('material_inventory_transfers', TRUE);
                $this->dbforge->drop_table('material_inventory_transfer_items', TRUE);
        }

        public function down()
        {
                //Do nothing.
        }
}