<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_users extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'sched_id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'default' => '0',
                                'null' => true,
                                'after' => 'vendor_id'
                        ),
                );
                $this->dbforge->add_column('users', $fields);
        }

        public function down()
        {
                //$this->dbforge->drop_column('invoices', 'consumer_id');
        }
}