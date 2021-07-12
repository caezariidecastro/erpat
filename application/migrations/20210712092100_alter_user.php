<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_user extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'resigned' => array(
                                'type' => 'INT',
                                'constraint' => 1,
                                'default' => '0',
                                'null' => true,
                                'after' => 'license_image'
                        ),
                        'terminated' => array(
                                'type' => 'INT',
                                'constraint' => 1,
                                'default' => '0',
                                'null' => true,
                                'after' => 'resigned'
                        ),
                );
                $this->dbforge->add_column('users', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('users', 'resigned');
                $this->dbforge->drop_column('users', 'terminated');
        }
}