<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_item_categories extends CI_Migration { 
        
        public function up()
        {
                $this->dbforge->rename_table('item_categories', 'services_categories');

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
                $this->dbforge->modify_column('services_categories', $edit_column);  

                $new_column = array(
                        'uuid' => array(
                                'type' => 'varchar',
                                'constraint' => 36,
                                'after' => 'id'
                        ),
                        'updated_at datetime default current_timestamp on update current_timestamp after created_at',
                );
                $this->dbforge->add_column('services_categories', $new_column);
                $this->db->query('ALTER TABLE `services_categories` ADD INDEX `services_category_uuid_index` (`uuid`);');

                $this->dbforge->drop_column('services_categories', 'date_created');
        }

        public function down()
        {
                $this->dbforge->rename_table('services_categories', 'item_categories');

                $this->dbforge->drop_column('item_categories', 'uuid');

                $edit_column = array(
                        'created_at' => array(
                                'name' => 'created_on',
                        ),
                );
                $this->dbforge->modify_column('item_categories', $edit_column); 

                $this->dbforge->drop_column('item_categories', 'updated_at'); 
        }
}