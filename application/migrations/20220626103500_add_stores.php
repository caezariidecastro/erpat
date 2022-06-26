<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_stores extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'uuid' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 36,
                    'null' => true
                ),
                'owner' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 36,
                    'null' => true
                ),
                'city_id' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 36,
                    'null' => true
                ),
                'category_id' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 36,
                    'null' => true
                ),
                'name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 120,
                    'default' => '',
                    'null' => false
                ),
                'description' => array(
                    'type' => 'TEXT',
                    'default' => '',
                    'null' => false
                ),
                'phone' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'default' => '',
                    'null' => false
                ),
                'email' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 180,
                    'default' => '',
                    'null' => false
                ),
                'vcode' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'default' => '',
                    'null' => false
                ),
                'cover' => array(
                    'type' => 'TEXT',
                    'default' => '',
                    'null' => false
                ),
                'images' => array(
                    'type' => 'TEXT',
                    'default' => '',
                    'null' => false
                ),
                'certificates' => array(
                    'type' => 'TEXT',
                    'default' => '',
                    'null' => false
                ),
                'open_time' => array(
                    'type' => 'TIME',
                    'default' => NULL,
                    'null' => true
                ),
                'close_time' => array(
                    'type' => 'TIME',
                    'default' => NULL,
                    'null' => true
                ),
                "`isClosed` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'",
                'commission' => array(
                    'type' => 'decimal(10,2)',
                    'default' => '0.00',
                    'null' => false,
                ),
                'lazada' => array(
                    'type' => 'TEXT',
                    'default' => '',
                    'null' => false
                ),
                'shopee' => array(
                    'type' => 'TEXT',
                    'default' => '',
                    'null' => false
                ),
                'fbpage' => array(
                    'type' => 'TEXT',
                    'default' => '',
                    'null' => false
                ),
                'igname' => array(
                    'type' => 'TEXT',
                    'default' => '',
                    'null' => false
                ),
                'pending_updates' => array(
                    'type' => 'TEXT',
                    'default' => '',
                    'null' => false
                ),
                'is_featured' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => '0',
                    'null' => false
                ),
                'status' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => '1',
                    'null' => false
                ),
                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true
                ),
                'timestamp' => array(
                    'type' => 'timestamp',
                    'default' => NULL,
                    'null' => true
                ),
                'verified_at' => array(
                    'type' => 'timestamp',
                    'default' => NULL,
                    'null' => true
                ),
                'updated_at timestamp default current_timestamp on update current_timestamp',
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => '0',
                ),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);

            $result = $this->dbforge->create_table('stores', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_stores error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('stores',TRUE); //DROP TABLE IF EXISTS table_name
        }
}