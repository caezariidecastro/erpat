<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_todo_updated_at extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'updated_at timestamp default current_timestamp on update current_timestamp',
                );
                $this->dbforge->add_column('to_do', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('to_do', 'updated_at');
        }
}