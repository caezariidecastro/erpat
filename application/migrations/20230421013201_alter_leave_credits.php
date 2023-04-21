<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_leave_credits extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'leave_type_id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'after' => 'leave_id'
                        ),
                );
                $this->dbforge->add_column('leave_credits', $fields);
        }

        public function down()
        {
                
        }
}