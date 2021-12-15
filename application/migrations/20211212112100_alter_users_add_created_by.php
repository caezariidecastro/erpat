<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_users_add_created_by extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'created_by' => array(
                                'type' => 'INT',
                                'default' => '0',
                                'null' => true,
                                'after' => 'created_at'
                        ),
                );
                $this->dbforge->add_column('users', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('users', 'created_by');
        }
}