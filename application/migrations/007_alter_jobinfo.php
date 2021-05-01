<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_jobinfo extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'sched_id' => array(
                                'type' => 'INT',
                                'constraint' => 11,
                                'default' => '0',
                                'null' => true,
                                'after' => 'user_id'
                        ),
                );
                $this->dbforge->add_column('team_member_job_info', $fields);

                $this->dbforge->drop_column('users', 'sched_id');
        }

        public function down()
        {
                $this->dbforge->drop_column('invoices', 'consumer_id');
        }
}