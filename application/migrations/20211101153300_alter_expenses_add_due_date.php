<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_expenses_add_due_date extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'due_date' => array(
                                'type' => 'date',
                                'null' => false,
                                'after' => 'expense_date'
                        ),
                );
                $this->dbforge->add_column('expenses', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('expenses', 'due_date');
        }
}