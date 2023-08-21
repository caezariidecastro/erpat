<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_services_add_unofficial extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'unofficial' => array(
                                'type' => 'TINYINT',
                                'constraint' => 1,
                                'after' => 'rate',
                                'default' => NULL,
                                'null' => true
                        ),
                );
                $this->dbforge->add_column('services', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('services', 'unofficial');
        }
}