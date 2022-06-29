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
                        'created_by' => array(
                                'type' => 'INT',
                                'null' => false,
                                'after' => 'created_on'
                        ),
                );
                $this->dbforge->add_column('inventory_items', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('inventory_items', 'brand');
                $this->dbforge->drop_column('inventory_items', 'created_by');
        }
}