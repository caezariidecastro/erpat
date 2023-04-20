<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_leave_types extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'required_credits' => array(
                                'type' => 'TINYINT',
                                'constraint' => 1,
                                'default' => '0',
                                'after' => 'title'
                        ),
                        'paid' => array(
                                'type' => 'TINYINT',
                                'constraint' => 1,
                                'default' => '0',
                                'after' => 'required_credits'
                        ),
                );
                $this->dbforge->add_column('leave_types', $fields);
        }

        public function down()
        {
                
        }
}