<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_bank_to_team_job_info extends CI_Migration { 
        
        public function up()
        {
                $bank_name = array(
                        'bank_name' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 100,
                                'default' => '',
                                'after' => 'phil_health'
                        ),
                );
                $this->dbforge->add_column('team_member_job_info', $bank_name);

                $bank_account = array(
                        'bank_account' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 50,
                                'default' => '',
                                'after' => 'bank_name'
                        ),
                );
                $this->dbforge->add_column('team_member_job_info', $bank_account);

                $bank_number = array(
                        'bank_number' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 50,
                                'default' => '',
                                'after' => 'bank_account'
                        ),
                );
                $this->dbforge->add_column('team_member_job_info', $bank_number);
        }

        public function down()
        {
                $this->dbforge->drop_column('team_member_job_info', 'bank_name');
                $this->dbforge->drop_column('team_member_job_info', 'bank_account');
                $this->dbforge->drop_column('team_member_job_info', 'bank_number');
        }
}