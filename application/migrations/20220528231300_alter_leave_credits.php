<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_leave_credits extends CI_Migration { 
        
        public function up()
        {
                $edit_column = array(
                        'counts' => array(
                                'type' => 'DECIMAL',
                                'constraint' => '10,2',
                        ),
                );
                $this->dbforge->modify_column('leave_credits', $edit_column);  
        }

        public function down()
        {
                //None
        }
}