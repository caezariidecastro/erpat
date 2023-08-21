<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_access_to_users extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'access_erpat' => array(
                                'type' => 'INT',
                                'constraint' => 1,
                                'default' => '0',
                                'after' => 'disable_login'
                        ),
                        'access_syntry' => array(
                                'type' => 'INT',
                                'constraint' => 1,
                                'default' => '0',
                                'after' => 'access_erpat'
                        ),
                );
                $this->dbforge->add_column('users', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('users', 'access_erpat');
                $this->dbforge->drop_column('users', 'access_syntry');
        }
}