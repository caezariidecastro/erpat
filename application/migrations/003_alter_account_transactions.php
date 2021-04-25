<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_account_transactions extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'reference' => array(
                                'name' => 'reference',
                                'type' => 'INT',
                                'default' => NULL
                        ),
                );
                $this->dbforge->modify_column('account_transactions', $fields);
        }

        public function down()
        {
                //$this->dbforge->drop_column('account_transaction', 'reference');
        }
}