<?php

class Payrolls_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = $this->db->dbprefix('payrolls');
        parent::__construct($this->table);
        $this->load->model('Email_templates_model');
    }

    function get_details($options = array()) {
        $where = "";
        $id = get_array_value($options, "id");
        $category = get_array_value($options, "category");
        $start = get_array_value($options, "start");
        $end = get_array_value($options, "end");
        $account_id = get_array_value($options, "account_id");
        $department_id = get_array_value($options, "department_id");

        if ($id) {
            $where .= " AND {$this->table}.id=$id";
        }

        if($category){
            $where .= " AND {$this->table}.category = $category";
        }

        if($start){
            $where .= " AND {$this->table}.pay_date BETWEEN '$start' AND '$end'";
        }

        if($account_id){
            $where .= " AND {$this->table}.account_id = $account_id";
        }

        if($department_id){
            $where .= " AND {$this->table}.department = $department_id";
        }

        $sql = "SELECT {$this->table}.*, 
        TRIM(CONCAT(creator.first_name, ' ', creator.last_name)) AS creator_name, 
        TRIM(CONCAT(signee.first_name, ' ', signee.last_name)) AS signee_name, 
        TRIM(CONCAT(accountant.first_name, ' ', accountant.last_name)) AS accountant_name, 
        department.title AS department_name, 
        (SELECT count(id) FROM payslips WHERE payslips.payroll = {$this->table}.id AND deleted=0) AS total_payslip,
        acct.name AS account_name 
        FROM {$this->table}
        LEFT JOIN accounts acct ON acct.id = {$this->table}.account_id
        LEFT JOIN team department ON department.id = {$this->table}.department
        LEFT JOIN users creator ON creator.id = {$this->table}.created_by
        LEFT JOIN users accountant ON accountant.id = {$this->table}.accountant_id
        LEFT JOIN users signee ON signee.id = {$this->table}.signed_by
        WHERE {$this->table}.deleted=0 $where";
        return $this->db->query($sql);
    }

    function get_contribution_lists() {
        $users = $this->db->dbprefix('users');

        $sql = "SELECT {$users}.id, {$users}.first_name, {$users}.last_name, 

        WHERE {$users}.deleted=0 AND {$users}.status='active'";
        return $this->db->query($sql)->result();
    }

    function restore_payslip_email() {
        $content = '<div style="background-color: #eeeeef; padding: 50px 0; ">    <div style="max-width:640px; margin:0 auto; ">  <div style="color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;"><h1>SYSTEM GENERATED</h1> </div> <div style="padding: 20px; background-color: rgb(255, 255, 255);">            <p style=""><span style="font-size: 14px; line-height: 20px;"><br></span></p><p style=""><span style="font-size: 14px; line-height: 20px;">Hello {FIRST_NAME} {LAST_NAME},</span></p><p style=""><span style="font-size: 14px; line-height: 20px;"><br></span></p><p style="text-align: justify; "><font face="Arial">&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 14px;">We hope this email finds you well. As part of our commitment to transparency and efficiency, we are pleased to provide you with your payslip for</span><span style="font-size: 14px;">&nbsp;{PAY_PERIOD}.&nbsp;</span></font><span style="font-family: Arial; font-size: 14px;">You will find a detailed breakdown of your earnings and deductions for the specified period to the PDF attached in this email.&nbsp;</span><span style="font-family: Arial; font-size: 14px;">Hope you find everything in order.</span></p><p style="text-align: justify; "><font face="Arial">&nbsp;&nbsp;&nbsp;Thank you for your dedication and hard work. We value your contribution to the company and look forward to your continued success.</font></p><p style="text-align: justify; "><font face="Arial">&nbsp;&nbsp;&nbsp;&nbsp;{REMARKS}</font></p><p style="text-align: justify;"><br></p><p><font color="#555555" face="Arial, Helvetica, sans-serif"><span style="font-size: 14px;">Regards,</span></font></p><p><font color="#555555"><span style="font-size: 14px;">HR / Accounting</span><br><span style="font-size: 14px;">ABC Company Inc.<br></span></font><a href="https://abc.company" target="_blank">https://abc.company</a><font color="#555555"><span style="font-size: 14px;"><br></span></font></p><p><br></p>            <p style="text-align: center; color: rgb(85, 85, 85); font-size: 14px;">{SIGNATURE}</p>        </div>    </div></div>';

        $template_name = 'payslips';
        $email_subject = 'ERPat - Generated Payslip';

        //Try to get the id and just update.
        $template = $this->Email_templates_model->get_one_where(array(
            "template_name" => $template_name 
        ));

        //If the id is null, create new one.
        if(!$template->id) {
            return $this->Email_templates_model->new_template($template_name, $email_subject, $content);
        }
    }
}
