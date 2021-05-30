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
                        ),
                        'address_id' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 36,
                        ),

                        'owners' => array( //serialized array of uuid
                                'type' => 'text',
                                'default' => '',
                                'null' => true
                        ),
                        
                        'title' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                        ),
                        'description' => array(
                                'type' => 'text',
                                'default' => '',
                                'null' => true
                        ),

                        'phone' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                        ),
                        'email' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                        ),

                        'open_time' => array(
                                'type' => 'time',
                                'null' => true
                        ),
                        'close_time' => array(
                                'type' => 'time',
                                'null' => true
                        ),

                        'avatar' => array(
                                'type' => 'text',
                                'default' => '',
                                'null' => true
                        ),
                        'banners' => array(
                                'type' => 'text',
                                'default' => '',
                                'null' => true
                        ),
                        'documents' => array(
                                'type' => 'text',
                                'default' => '',
                                'null' => true
                        ),

                        'ratings' => array(
                                'type' => 'longtext',
                                'default' => '',
                                'null' => true
                        ),
                        'verified_at' => array(
                                'type' => 'datetime',
                                'default' => null,
                                'null' => true
                        ),
                        'created_by' => array(
                            'type' => 'INT',
                            'constraint' => 11,
                            'default' => '0',
                            'null' => true
                        ),
                        'created_at datetime default current_timestamp',
                        'updated_at datetime default current_timestamp on update current_timestamp',
                        'active' => array(
                                'type' => 'TINYINT',
                                'constraint' => 1,
                                'default' => '1',
                        ),
                        'deleted' => array(
                            'type' => 'TINYINT',
                            'constraint' => 1,
                            'default' => '0',
                        ),
                    );
                    $this->dbforge->add_field($fields);
                    $this->dbforge->add_key('id', TRUE);
                    $this->dbforge->add_key('uuid');

                    $result = $this->dbforge->create_table('stores', TRUE); //IF NOT EXIST.
        }

        public function down()
        {
                $this->dbforge->drop_table('stores',TRUE); //DROP TABLE IF EXISTS table_name
        }
}