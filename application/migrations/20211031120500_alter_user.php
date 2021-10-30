<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_user extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'uuid' => array(
                                'type' => 'varchar',
                                'constraint' => 36,
                                'after' => 'id'
                        ),
                );
                $this->dbforge->add_column('users', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('users', 'uuid');
        }
}