<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_job_info_rfid extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'rfid_num' => array(
                                'type' => 'varchar',
                                'constraint' => 24,
                                'after' => 'job_idnum'
                        ),
                );
                $this->dbforge->add_column('team_member_job_info', $fields);
                $this->db->query('ALTER TABLE `team_member_job_info` ADD UNIQUE INDEX (`rfid_num`)');
        }

        public function down()
        {
                $this->dbforge->drop_column('team_member_job_info', 'rfid_num');
        }
}