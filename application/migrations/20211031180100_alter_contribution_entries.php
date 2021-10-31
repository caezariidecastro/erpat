<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_contribution_entries extends CI_Migration { 
        
        public function up()
        {
                $fields = array(
                        'amount' => array(
                                'type' => 'decimal(10,2)',
                                'null' => false,
                                'after' => 'id'
                        ),
                        'remarks' => array(
                                'type' => 'text',
                                'null' => false,
                                'after' => 'status'
                        ),
                        'signed_by' => array(
                                'type' => 'int',
                                'null' => false,
                                'after' => 'remarks'
                        ),
                );
                $this->dbforge->add_column('contribution_entries', $fields);

                $modify = array(
                        "`status` ENUM('not paid','paid','cancelled') NOT NULL DEFAULT 'not paid'",
                );
                $this->dbforge->modify_column('contribution_entries', $modify);
                $this->db->query("UPDATE contribution_entries SET status = DEFAULT(status)");
        }

        public function down()
        {
                $this->dbforge->drop_column('contribution_entries', 'amount');
                $this->dbforge->drop_column('contribution_entries', 'remarks');
                $this->dbforge->drop_column('contribution_entries', 'signed_by');
        }
}