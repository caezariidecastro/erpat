<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_expense extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'client_id' => array(
                                'name' => 'client_id',
                                'type' => 'INT',
                                'default' => NULL
                        ),
                );
                $this->dbforge->modify_column('expenses', $fields);
        }

        public function down()
        {
                //$this->dbforge->drop_column('expenses', 'client_id');
        }
}