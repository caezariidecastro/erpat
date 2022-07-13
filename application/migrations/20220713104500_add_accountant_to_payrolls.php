<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_accountant_to_payrolls extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'accountant_id' => array(
                                'type' => 'INT',
                                'null' => false,
                                'after' => 'signed_by'
                        ),
                );
                $this->dbforge->add_column('payrolls', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('payrolls', 'accountant_id');
        }
}