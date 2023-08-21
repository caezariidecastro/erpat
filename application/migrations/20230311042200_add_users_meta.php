<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_users_meta extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'user_id' => array(
                    'type' => 'INT',
                    'constraint' => 11
                ),
                'meta_key' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 180,
                ),
                'meta_val' => array(
                    'type' => 'TEXT',
                    'null' => true
                ),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);

            $result = $this->dbforge->create_table('users_meta', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_users_meta error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('users_meta',TRUE); //DROP TABLE IF EXISTS table_name
        }
}