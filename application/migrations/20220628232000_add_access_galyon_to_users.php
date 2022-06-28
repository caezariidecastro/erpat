<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_access_galyon_to_users extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'access_madage' => array(
                                'type' => 'INT',
                                'constraint' => 1,
                                'default' => '0',
                                'after' => 'access_syntry'
                        ),
                        'access_galyon' => array(
                                'type' => 'INT',
                                'constraint' => 1,
                                'default' => '1',
                                'after' => 'access_madage'
                        ),
                );
                $this->dbforge->add_column('users', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('users', 'access_madage');
                $this->dbforge->drop_column('users', 'access_galyon');
        }
}