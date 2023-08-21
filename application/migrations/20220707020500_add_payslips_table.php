<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Add_payslips_table extends CI_Migration { 
        
        public function up()
        {
            $fields = array(
                'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                "`payroll` int(11) NOT NULL",
                "`user` int(11) NOT NULL",
                "`hourly_rate` decimal(10,2) NOT NULL",
                "`schedule` decimal(10,2) NOT NULL",
                "`worked` decimal(10,2) NOT NULL",
                "`absent` decimal(10,2) NOT NULL",
                "`lates` decimal(10,2) NOT NULL",
                "`overbreak` decimal(10,2) NOT NULL",
                "`undertime` decimal(10,2) NOT NULL",
                "`reg_nd` decimal(10,2) NOT NULL",
                "`rest_nd` decimal(10,2) NOT NULL",
                "`legal_nd` decimal(10,2) NOT NULL",
                "`spcl_nd` decimal(10,2) NOT NULL",
                "`reg_ot` decimal(10,2) NOT NULL",
                "`rest_ot` decimal(10,2) NOT NULL",
                "`legal_ot` decimal(10,2) NOT NULL",
                "`spcl_ot` decimal(10,2) NOT NULL",
                "`reg_ot_nd` decimal(10,2) NOT NULL",
                "`rest_ot_nd` decimal(10,2) NOT NULL",
                "`legal_ot_nd` decimal(10,2) NOT NULL",
                "`spcl_ot_nd` decimal(10,2) NOT NULL",
                "`sss` decimal(10,2) NOT NULL",
                "`pagibig` decimal(10,2) NOT NULL",
                "`phealth` decimal(10,2) NOT NULL",
                "`hmo` decimal(10,2) NOT NULL",
                "`com_loan` decimal(10,2) NOT NULL",
                "`sss_loan` decimal(10,2) NOT NULL",
                "`hdmf_loan` decimal(10,2) NOT NULL",
                "`deduct_adjust` decimal(10,2) NOT NULL",
                "`deduct_other` decimal(10,2) NOT NULL",
                "`allowance` decimal(10,2) NOT NULL",
                "`incentive` decimal(10,2) NOT NULL",
                "`bonus_month` decimal(10,2) NOT NULL",
                "`month13th` decimal(10,2) NOT NULL",
                "`pto` decimal(10,2) NOT NULL",
                "`add_adjust` decimal(10,2) NOT NULL",
                "`add_other` decimal(10,3) NOT NULL",
                "`signed_by` int(11) DEFAULT NULL",
                "`signed_at` timestamp NULL DEFAULT NULL",
                "`cancelled_by` int(11) DEFAULT NULL",
                "`cancelled_at` timestamp NULL DEFAULT NULL",
                "`remarks` text NOT NULL",
                "`created_by` int(11) DEFAULT NULL",
                "`timestamp` timestamp NULL DEFAULT NULL",
                '`updated_at` timestamp default current_timestamp on update current_timestamp',
                'deleted' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => '0',
                ),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);

            $result = $this->dbforge->create_table('payslips', TRUE); //IF NOT EXIST.

            if(!$result) {
                throw new Exception("Add_payslips_table error");
            }
            
        }

        public function down()
        {
            $this->dbforge->drop_table('payslips',TRUE); //DROP TABLE IF EXISTS table_name
        }
}