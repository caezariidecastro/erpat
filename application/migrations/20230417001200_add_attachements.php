<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_attachements extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                
                'file_name' => array(
                    'type' => 'VARCHAR',
                    'null' => false
                ),
                'file_type' => array(
                    'type' => 'VARCHAR',
                    'null' => false
                ),
                'file_size' => array(
                    'constraint' => 11, //bytes
                    'null' => false
                ),
                'description' => array(
                    'type' => 'TEXT',
                    'null' => true
                ),

                'uploaded_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => false
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

            $result = $this->dbforge->create_table('attachements', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_attachements error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('attachements',TRUE); //DROP TABLE IF EXISTS table_name
        }
}