<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_cities extends CI_Migration { 
        
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
                        'operator_id' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 36,
                        ),
                        'title' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                                'default' => '',
                                'null' => true
                        ),
                        'latitude' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 100,
                                'default' => '',
                                'null' => true
                        ),
                        'longitude' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 100,
                                'default' => '',
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

                    $result = $this->dbforge->create_table('cities', TRUE); //IF NOT EXIST.
        }

        public function down()
        {
                $this->dbforge->drop_table('cities',TRUE); //DROP TABLE IF EXISTS table_name
        }
}