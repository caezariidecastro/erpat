<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_product_item_column extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'brand' => array(
                                'type' => 'INT',
                                'null' => false,
                                'after' => 'category'
                        ),
                );
                $this->dbforge->add_column('inventory_items', $fields);
        }

        public function down()
        {
                
        }
}