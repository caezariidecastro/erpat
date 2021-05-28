<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_schedule extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'title' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'default' => '',
                    'null' => true
                ),
                'desc' => array(
                    'type' => 'TEXT',
                    'default' => '',
                    'null' => true
                ),
                'mon' => array(
                    'type' => 'TEXT',
                    'default' => NULL,
                    'null' => true
                ),
                'tue' => array(
                    'type' => 'TEXT',
                    'default' => NULL,
                    'null' => true
                ),
                'wed' => array(
                    'type' => 'TEXT',
                    'default' => NULL,
                    'null' => true
                ),
                'thu' => array(
                    'type' => 'TEXT',
                    'default' => NULL,
                    'null' => true
                ),
                'fri' => array(
                    'type' => 'TEXT',
                    'default' => NULL,
                    'null' => true
                ),
                'sat' => array(
                    'type' => 'TEXT',
                    'default' => NULL,
                    'null' => true
                ),
                'sun' => array(
                    'type' => 'TEXT',
                    'default' => NULL,
                    'null' => true
                ),
                'created_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '0',
                    'null' => true
                ),
                'date_created' => array(
                    'type' => 'DATETIME',
                    'default' => NULL,
                    'null' => true
                ),
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => '0',
                ),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);

            $result = $this->dbforge->create_table('schedule', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Hello");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('schedule',TRUE); //DROP TABLE IF EXISTS table_name
        }
}