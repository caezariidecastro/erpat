<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_invoice extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'consumer_id' => array(
                                'name' => 'consumer_id',
                                'type' => 'INT',
                                'default' => NULL
                        ),
                );
                $this->dbforge->modify_column('invoices', $fields);
        }

        public function down()
        {
                //$this->dbforge->drop_column('invoices', 'consumer_id');
        }
}