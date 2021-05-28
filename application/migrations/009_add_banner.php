<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_banner extends CI_Migration { 
        
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
                        'title' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                                'default' => '',
                                'null' => true
                        ),
                        'description' => array(
                                'type' => 'TEXT',
                                'default' => '',
                                'null' => true
                        ),
                        'image' => array(
                                'type' => 'TEXT',
                                'default' => '',
                                'null' => true
                        ),
                        'type' => array(
                                'type' => 'TINYINT',
                                'constraint' => 1,
                                'default' => '0',
                        ),
                        'position' => array(
                                'type' => 'TINYINT',
                                'constraint' => 1,
                                'default' => '0',
                        ),
                        'group' => array(
                                'type' => 'TINYINT',
                                'constraint' => 1,
                                'default' => '0',
                        ),
                        'created_by' => array(
                            'type' => 'INT',
                            'constraint' => 11,
                            'default' => '0',
                            'null' => true
                        ),
                        'created_at datetime default current_timestamp',
                        'updated_at datetime default current_timestamp on update current_timestamp',
                        'deleted' => array(
                            'type' => 'TINYINT',
                            'constraint' => 1,
                            'default' => '0',
                        ),
                    );
                    $this->dbforge->add_field($fields);
                    $this->dbforge->add_key('id', TRUE);
                    $this->dbforge->add_key('uuid');

                    $result = $this->dbforge->create_table('banner', TRUE); //IF NOT EXIST.
        }

        public function down()
        {
                $this->dbforge->drop_table('banner',TRUE); //DROP TABLE IF EXISTS table_name
        }
}