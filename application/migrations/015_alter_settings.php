<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_alter_settings extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'created_at datetime default current_timestamp after type',
                        'updated_at datetime default current_timestamp on update current_timestamp after created_at',
                        'active' => array(
                                'type' => 'TINYINT',
                                'constraint' => 1,
                                'default' => '1',
                                'after' => 'updated_at'
                        ),
                );
                $this->dbforge->add_column('settings', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('settings', 'created_at');
                $this->dbforge->drop_column('settings', 'updated_at');
                $this->dbforge->drop_column('settings', 'active');
        }
}