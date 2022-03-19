<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_users_emergency_contacts extends CI_Migration { 
        
        public function up()
        {
                $contact_name = array(
                        'contact_name' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 200,
                                'default' => '',
                                'after' => 'date_of_hire'
                        ),
                );
                $this->dbforge->add_column('team_member_job_info', $contact_name);

                $contact_address = array(
                        'contact_address' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 200,
                                'default' => '',
                                'after' => 'contact_name'
                        ),
                );
                $this->dbforge->add_column('team_member_job_info', $contact_address);

                $contact_phone = array(
                        'contact_phone' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 200,
                                'default' => '',
                                'after' => 'contact_address'
                        ),
                );
                $this->dbforge->add_column('team_member_job_info', $contact_phone);

                $job_idnum = array(
                        'job_idnum' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 50,
                                'default' => '',
                                'after' => 'user_id'
                        ),
                );
                $this->dbforge->add_column('team_member_job_info', $job_idnum);

                $signiture_url = array(
                        'signiture_url' => array(
                                'type' => 'TEXT',
                                'default' => '',
                                'after' => 'contact_phone'
                        ),
                );
                $this->dbforge->add_column('team_member_job_info', $signiture_url);
        }

        public function down()
        {
                $this->dbforge->drop_column('team_member_job_info', 'contact_name');
                $this->dbforge->drop_column('team_member_job_info', 'contact_address');
                $this->dbforge->drop_column('team_member_job_info', 'contact_phone');
                $this->dbforge->drop_column('team_member_job_info', 'job_idnum');
                $this->dbforge->drop_column('team_member_job_info', 'signiture_url');
        }
}