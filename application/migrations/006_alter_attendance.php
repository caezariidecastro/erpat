<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_attendance extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'sched_id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'default' => '0',
                                'null' => true,
                                'after' => 'user_id'
                        ),
                );
                $this->dbforge->add_column('attendance', $fields);
        }

        public function down()
        {
                //$this->dbforge->drop_column('invoices', 'consumer_id');
        }
}