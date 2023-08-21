<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_expenses extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'due_date' => array(
                                'type' => 'date',
                                'after' => 'files'
                        ),
                        "`status` enum('draft','not_paid','cancelled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft' after due_date"
                );
                $this->dbforge->add_column('expenses', $fields);
        }

        public function down()
        {
                $this->dbforge->drop_column('expenses', 'due_date');
        }
}