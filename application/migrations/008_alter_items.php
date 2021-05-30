<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_items extends CI_Migration { 
        
        public function up()
        {
                $this->dbforge->rename_table('items', 'services');

                $edit_column = array(
                        'category' => array(
                                'name' => 'category_id',
                                'type' => 'varchar',
                                'constraint' => 36,
                                'after' => 'id'
                        ),
                        'created_on' => array(
                                'name' => 'created_at',
                                'type' => 'datetime',
                                'default' => 'current_timestamp',
                                'after' => 'created_by'
                        ),
                        'active' => array(
                                'after' => 'updated_at'
                        ),
                );
                $this->dbforge->modify_column('services', $edit_column);  

                $new_column = array(
                        'uuid' => array(
                                'type' => 'varchar',
                                'constraint' => 36,
                                'after' => 'id'
                        ),
                        'updated_at datetime default current_timestamp on update current_timestamp after created_at',
                );
                $this->dbforge->add_column('services', $new_column);
                $this->db->query('ALTER TABLE `services` ADD INDEX `services_uuid_index` (`uuid`);');

                $this->dbforge->drop_column('services', 'date_created');
        }

        public function down()
        {
                $this->dbforge->rename_table('services', 'items');

                $this->dbforge->drop_column('items', 'uuid');

                $edit_column = array(
                        'category_id' => array(
                                'name' => 'category',
                                'type' => 'int',
                                'constraint' => 11,
                        ),
                        'created_at' => array(
                                'name' => 'created_on',
                        ),
                );
                $this->dbforge->modify_column('items', $edit_column); 

                $this->dbforge->drop_column('items', 'updated_at'); 
        }
}