<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_status_contribution_entries extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'status' => array(
                                'type' => 'INT',
                                'constraint' => 1,
                                'default' => '1',
                                'after' => 'created_by'
                        ),
                );
                $this->dbforge->add_column('contribution_entries', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('contribution_entries', 'status');
        }
}