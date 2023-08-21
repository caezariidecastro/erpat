<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_attachments extends CI_Migration { 
        
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
                    'constraint' => 180,
                ),
                'file_type' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ),
                'file_size' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'description' => array(
                    'type' => 'TEXT',
                ),

                'uploaded_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                '`updated_at` timestamp default current_timestamp on update current_timestamp',
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => '0',
                ),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);

            $result = $this->dbforge->create_table('attachments', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_attachments error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('attachments',TRUE); //DROP TABLE IF EXISTS table_name
        }
}