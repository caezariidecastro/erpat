<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_pages extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'visible_to_galyon' => array(
                                'type' => 'TINYINT',
                                'constraint' => 1,
                                'default' => '0',
                                'after' => 'visible_to_clients_only'
                        ),
                        'sys_required' => array(
                                'type' => 'TINYINT',
                                'constraint' => 1,
                                'default' => '0',
                                'after' => 'visible_to_galyon'
                        ),
                        'created_at datetime default current_timestamp after sys_required',
                        'updated_at datetime default current_timestamp on update current_timestamp after created_at',
                        'active' => array(
                                'type' => 'TINYINT',
                                'constraint' => 1,
                                'default' => '1',
                                'after' => 'updated_at'
                        ),
                );
                $this->dbforge->add_column('pages', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('pages', 'visible_to_galyon');
                $this->dbforge->drop_column('pages', 'sys_required');
                $this->dbforge->drop_column('pages', 'created_at');
                $this->dbforge->drop_column('pages', 'updated_at');
                $this->dbforge->drop_column('pages', 'active');
        }
}