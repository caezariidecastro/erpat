<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_description_products extends CI_Migration { 
        
        public function up()
        {
                $description = array(
                        'description' => array(
                                'type' => 'TEXT',
                                'default' => '',
                                'after' => 'name'
                        ),
                );
                $this->dbforge->add_column('inventory_items', $description);
        }

        public function down()
        {
                $this->dbforge->drop_column('inventory_items', 'description');
        }
}