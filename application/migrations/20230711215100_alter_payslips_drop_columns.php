<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Migration_Alter_payslips_drop_columns extends CI_Migration { 
        
        public function up()
        {
                $this->dbforge->drop_column('payslips', 'lates');
                $this->dbforge->drop_column('payslips', 'overbreak');
                $this->dbforge->drop_column('payslips', 'undertime');

                $this->dbforge->drop_column('payslips', 'rest_nd');
                $this->dbforge->drop_column('payslips', 'legal_nd');
                $this->dbforge->drop_column('payslips', 'spcl_nd');

                $this->dbforge->drop_column('payslips', 'legal_ot');
                $this->dbforge->drop_column('payslips', 'spcl_ot');

                $this->dbforge->drop_column('payslips', 'reg_ot_nd');
                $this->dbforge->drop_column('payslips', 'rest_ot_nd');
                $this->dbforge->drop_column('payslips', 'legal_ot_nd');
                $this->dbforge->drop_column('payslips', 'spcl_ot_nd');

                $this->dbforge->drop_column('payslips', 'sss');
                $this->dbforge->drop_column('payslips', 'pagibig');
                $this->dbforge->drop_column('payslips', 'phealth');
                $this->dbforge->drop_column('payslips', 'hmo');

                $this->dbforge->drop_column('payslips', 'com_loan');
                $this->dbforge->drop_column('payslips', 'sss_loan');
                $this->dbforge->drop_column('payslips', 'hdmf_loan');
                $this->dbforge->drop_column('payslips', 'deduct_adjust');
                $this->dbforge->drop_column('payslips', 'deduct_other');

                $this->dbforge->drop_column('payslips', 'allowance');
                $this->dbforge->drop_column('payslips', 'incentive');
                $this->dbforge->drop_column('payslips', 'bonus_month');
                $this->dbforge->drop_column('payslips', 'month13th');

                $this->dbforge->drop_column('payslips', 'add_adjust');
                $this->dbforge->drop_column('payslips', 'add_other');

                $this->dbforge->drop_column('payslips', 'cancelled_by');
                $this->dbforge->drop_column('payslips', 'cancelled_at');
                $this->dbforge->drop_column('payslips', 'timestamp');

                $fields = array(
                        "`bonus` decimal(10,2) NOT NULL DEFAULT 0 after absent",
                        "`leave_credit` decimal(10,2) NOT NULL DEFAULT 0 after bonus",
                        "`special_hd` decimal(10,2) NOT NULL DEFAULT 0 after leave_credit",
                        "`legal_hd` decimal(10,2) NOT NULL DEFAULT 0 after special_hd",
                        "`status` enum('draft','approved','rejected') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft' after signed_at"
                );
                $this->dbforge->add_column('payslips', $fields);

                $item_key = array(
                        "`item_key` varchar(180) NOT NULL after payslip_id",
                );

                $this->dbforge->add_column('payslips_earnings', $item_key);
                $this->dbforge->drop_column('payslips_earnings', 'status');
                $this->dbforge->drop_column('payslips_earnings', 'timestamp');
                $this->dbforge->drop_column('payslips_earnings', 'created_by');

                $this->dbforge->add_column('payslips_deductions', $item_key);
                $this->dbforge->drop_column('payslips_deductions', 'status');
                $this->dbforge->drop_column('payslips_deductions', 'timestamp');
                $this->dbforge->drop_column('payslips_deductions', 'created_by');
        }

        public function down()
        {
               //No need to revert
        }
}