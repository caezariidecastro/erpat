<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payrolls extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->with_module("payroll", "redirect");
        //$this->with_permission("payroll", "redirect");

        $this->load->helper('payhp');
        $this->load->helper("biometric");
        $this->load->helper('utility');

        $this->load->library('Amount_In_Words');

        $this->load->model("Payrolls_model");
        $this->load->model("Payslips_model");
        $this->load->model("Payslip_earnings_model");
        $this->load->model("Payslip_deductions_model");
        $this->load->model("Payslip_sents_model");
        $this->load->model("Leave_credits_model");
        $this->load->model("Accounts_model");
        $this->load->model("Attendance_model");
        $this->load->model("Payment_methods_model");
        $this->load->model("Leave_credits_model");

        $this->load->model("Loans_model");
        $this->load->model("Loan_categories_model");
        $this->load->model("Loan_payments_model");

        $this->load->model("Expenses_model");
        $this->load->model("Expense_categories_model");
    } 

    function index(){
        $view_data['account_select2'] = $this->_get_account_select2_data();
        $view_data['department_select2'] = $this->_get_team_select2_data();
        $this->template->rander("payrolls/index", $view_data);
    }

    function list_data(){
        $list_data = $this->Payrolls_model->get_details(array(
            'start' => $this->input->post('start_date'),
            'end' => $this->input->post('end_date'),
            'account_id' => $this->input->post('account_select2_filter'),
            'department_id' => $this->input->post('department_select2_filter'),
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function get_labeled_status($status){
        $labeled_status = "";

        if($status == "draft"){
            $labeled_status = "<span class='label label-default'>".(ucwords($status))."</span>";
        }

        if($status == "cancelled"){
            $labeled_status = "<span class='label label-danger'>".(ucwords($status))."</span>";
        }

        if($status == "ongoing"){
            $labeled_status = "<span class='label label-warning'>".(ucwords($status))."</span>";
        }

        if($status == "completed"){
            $labeled_status = "<span class='label label-success'>".(ucwords($status))."</span>";
        }

        return $labeled_status;
    }

    private function _make_row($data) {

        if($data->status == "draft"){
            $edit = '<li role="presentation">' . modal_anchor(get_uri("fas/payrolls/modal_form"), "<i class='fa fa-pencil'></i> " . lang('edit'), array("title" => lang('edit'), "data-post-view" => "details", "data-post-id" => $data->id)) . '</li>';
            
            $generate = '<li role="presentation">'. js_anchor("<i class='fa fa-wrench'></i> " . lang('mark_as_ongoing'), array('title' => lang('update'), "data-action-url" => get_uri("fas/payrolls/mark_as_ongoing/$data->id"), "data-action" => "update")). '</li>';
        }

        if($data->status == "draft" || $data->status == "cancelled"){
            $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("fas/payrolls/delete"), "data-action" => "delete-confirmation")) . '</li>';
        }

        if($data->status == "ongoing" || $data->status == "completed") {
            $view = "<li role='presentation'>" . anchor(get_uri("fas/payrolls/view/".$data->id), "<i class='fa fa-file-pdf-o'></i> " . lang('view_payroll'), array("title" => lang('view_payroll'), "target" => "_blank")) . "</li>";

            $cancel = '<li role="presentation">' . js_anchor("<i class='fa fa-exclamation fa-fw'></i> " . lang('cancel'), array('title' => lang('update'), "data-action-url" => get_uri("fas/payrolls/mark_as_cancelled/$data->id"), "data-action" => "update", "data-reload-on-success" => "1")). '</li>';

            if( $data->status == "ongoing" ) {
                $pay =  '<li role="presentation">' . modal_anchor(get_uri("payrolls/lock_payment/".$data->id), "<i class='fa fa-money'></i> " . lang('lock_payment'), array("class" => "btn btn-default", "title" => lang('payslip_preview'), "data-post-payroll_id" => $data->id, "data-action" => "update", "data-reload-on-success" => "1")) .'</li>';
            }
        }

        if( $this->login_user->is_admin || $data->signed_by == $this->login_user->id) {
            $actions = '<span class="dropdown inline-block">
                <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                    <i class="fa fa-cogs"></i>&nbsp;
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">' . $view . $edit . $generate . $pay . $cancel . $delete . '</ul>
            </span>';
        }

        return array(
            $data->id,
            get_payroll_id($data->id),
            $data->team_list,
            $data->start_date,
            $data->end_date,
            $data->pay_date,
            $data->sched_hours,
            $data->account_name,
            $data->total_payslip,
            strtoupper($data->tax_table),
            $this->get_labeled_status($data->status),
            $data->remarks,
            get_team_member_profile_link($data->signed_by, $data->signee_name, array("target" => "_blank")),
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $data->timestamp,
            $actions
        );
    }

    function save() {
        $id = $this->input->post('id');

        $payroll_data = array(
            "remarks" => $this->input->post('remarks'),
            "signed_by" => $this->input->post('user_id'),
        );

        if(!$id){
            $payroll_data["category"] = $this->input->post('category_id') ? $this->input->post('category_id'):0;
            $payroll_data["account_id"] = $this->input->post('account_id');
            $payroll_data["department"] = $this->input->post('department_id');
            $payroll_data["start_date"] = $this->input->post('start_date');
            $payroll_data["end_date"] = $this->input->post('end_date');
            $payroll_data["pay_date"] = $this->input->post('pay_date');
            $payroll_data["sched_hours"] = $this->input->post('sched_hours');
            $payroll_data["timestamp"] = get_current_utc_time();
            $payroll_data["signed_by"] = $this->login_user->id;
            $payroll_data["tax_table"] = $this->input->post('tax_table');
            $payroll_data["earnings"] = $this->input->post('earnings_included');
            $payroll_data["deductions"] = $this->input->post('deductions_included');
            $payroll_data["accountant_id"] = $this->input->post('accountant_id');
            $payroll_data["created_by"] = $this->login_user->id;
        }

        //$payroll_data["expense_id"] = $this->save_expense($account_id, $amount, $employee_id, $expense_id);`
        $payroll_id = $this->Payrolls_model->save($payroll_data, $id);
        if ($payroll_id) {
            $options = array("id" => $payroll_id);
            $payroll_info = $this->Payrolls_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $payroll_info->id, "data" => $this->_make_row($payroll_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $view_data['earning_dropdown'] = json_encode( array(
            array("id"=>"allowance", "text"=>"Allowance: Tax Table"),
            array("id"=>"allowance_monthly", "text"=>"Allowance: Monthly"),
            array("id"=>"incentive", "text"=>"Incentive: Tax Table"),
            array("id"=>"incentive_monthly", "text"=>"Incentive: Monthly"),
            array("id"=>"bonus", "text"=>"Bonus: Tax Table"),
            array("id"=>"bonus_monthly", "text"=>"Bonus: Monthly")
        ) );
        
        $deduction_dropdown = array(
            array("id"=>"sss_contri", "text"=>"SSS Contribution: Tax Table"),
            array("id"=>"sss_contri_monthly", "text"=>"SSS Contribution: Monthly"),
            array("id"=>"pagibig_contri", "text"=>"HDMF Contribution: Tax Table"),
            array("id"=>"pagibig_contri_monthly", "text"=>"HDMF Contribution: Monthly"),
            array("id"=>"phealth_contri", "text"=>"Phealth Contribution: Tax Table"),
            array("id"=>"phealth_contri_monthly", "text"=>"Phealth Contribution: Monthly"),
            array("id"=>"hmo_contri", "text"=>"HMO Contribution: Tax Table"),
            array("id"=>"hmo_contri_monthly", "text"=>"HMO Contribution: Monthly"),
            array("id"=>"others", "text"=>"Other Deduction: Tax Table"),
            array("id"=>"others_monthly", "text"=>"Other Deduction: Monthly"),
        );
        $loans = $this->Loan_categories_model->get_details(array())->result();
        foreach($loans as $loan) {
            $deduction_dropdown[] = array("id"=>"loan:".$loan->id, "text"=>$loan->name);
        }
        $view_data['deduction_dropdown'] = json_encode( $deduction_dropdown );

        $view_data['account_dropdown'] = array("" => "-") + $this->Accounts_model->get_dropdown_list(array("name"), "id", array("deleted" => 0));
        $view_data['department_dropdown'] = json_encode($this->_get_team_select2_data());
        $view_data['model_info'] = $id ? $this->Payrolls_model->get_details(array("id" => $id))->row() : "";
        $view_data['user_dropdown'] = array("" => "- ".lang("accountant")." -") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"), "- ".lang("accountant")." -");

        if($id) {
            $view_data['model_info']->start_date = $view_data['model_info']->start_date;
            $view_data['model_info']->end_date = $view_data['model_info']->end_date;
            $view_data['model_info']->pay_date = $view_data['model_info']->pay_date;
        }

        $this->load->view('payrolls/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        $options = array("id" => $id);
        $payroll_info = $this->Payrolls_model->get_details($options)->row();

        if ($this->Payrolls_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $payroll_info = $this->Payrolls_model->get_details($options)->row();
        return $this->_make_row($payroll_info);
    }

    function pay($payroll_id = 0) {
        if ($payroll_id) {
            $payroll_data["status"] = "completed";

            $save_id = $this->Payrolls_model->save($payroll_data, $payroll_id);

            $options = array("id" => $payroll_id);
            $payroll_info = $this->Payrolls_model->get_details($options)->row();

            if ($save_id) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($payroll_info), "id" => $payroll_id, "message" => lang("record_saved")));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        }
    }

    function generate($payroll_id = 0) {
        if ($payroll_id) {
            $payroll_data["status"] = "ongoing";

            $save_id = $this->Payrolls_model->save($payroll_data, $payroll_id);

            $options = array("id" => $payroll_id);
            $payroll_info = $this->Payrolls_model->get_details($options)->row();

            if ($save_id) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($payroll_id), "id" => $payroll_id, "message" => lang("record_saved")));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        }
    }

    function cancel($payroll_id = 0) {
        if ($payroll_id) {
            $payroll_data["status"] = "cancelled";

            $save_id = $this->Payrolls_model->save($payroll_data, $payroll_id);

            $options = array("id" => $payroll_id);
            $payroll_info = $this->Payrolls_model->get_details($options)->row();

            if ($save_id) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($payroll_info), "id" => $payroll_id, "message" => lang("record_saved")));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        }
    }

    function pdf($id = 0) {
        if ($id) {
            $view_data["payroll_info"] = $this->Payrolls_model->get_details(array("id" => $id))->row();
            prepare_payroll_pdf($view_data);
        } else {
            show_404();
        }
    }

    function view($payroll_id = 0) {

        if ($payroll_id && $payroll_info = $this->Payrolls_model->get_details( array("id"=>$payroll_id) )->row()) {            
            $view_data["payroll_info"] = $payroll_info;
            $view_data["status"] = $this->get_labeled_status( $payroll_info->status );
            $view_data["can_edit_payrolls"] = ($this->login_user->is_admin || $this->login_user->id == $payroll_info->signed_by)?true:false;

            $view_data["department"] = $payroll_info->team_list;

            $this->template->rander('payrolls/view', $view_data);
        } else {
            show_404();
        }
    }

    function earnings() {
        $view_data['department_select2'] = $this->_get_team_select2_data();
        $view_data['category_select2'] = [
            array('id' => 'daily', 'text'  => '- Daily -'),
            array('id' => 'weekly', 'text'  => '- Weekly -'),
            array('id' => 'biweekly', 'text'  => '- Biweekly -'),
            array('id' => 'monthly', 'text'  => '- Monthly -'),
            array('id' => 'quarterly', 'text'  => '- Quartery -'),
            array('id' => 'annually', 'text'  => '- Annually -')        
        ];
        $this->load->view('payrolls/earnings', $view_data);
    }

    function earning_lists() {
        $lists = array();

        $options = array(
            "status" => 'active',
            'department_id' => $this->input->post('department_id'),
            "user_type" => 'staff',
            "where_in" => $this->get_allowed_users_only("staff", true)
        );
        $actives = $this->Users_model->get_details($options)->result();

        $filter = $this->input->post('category_select2_filter');

        foreach($actives as $user) {
            $user_id = $user->id;

            $full_name = $user->first_name . " " . $user->last_name . " ";
            if(get_setting('name_format') == "lastfirst") {
                $full_name = $user->last_name . ", " . $user->first_name;
            }

            $user_row = [
                $user->id,
                get_team_member_profile_link($user->id, $full_name),
            ];
    
            $total = 0;
            foreach(array(
                "allowances", "incentives", "bonuses"
            ) as $item) {
                $meta_key = "user_".$filter."_".$item."_".$user->id."_earnings";
                $meta_val = $this->Settings_model->get_setting($meta_key, "user");
    
                $user_row[] = cell_input(
                    $item.'_'.$user->id, 
                    convert_number_to_decimal($meta_val), 'number', 
                    'cell-class cell-class-'.$user->id, true
                );
            }

            $user_row[] = js_anchor("<i class='fa fa-pencil fa-fw'></i>", 
                    array('id' => "cell-edit-".$user->id, "data-filter" => $filter, 'name' => $user->id, 'title' => lang('edit'), 
                    "class" => "cell-style cell-edit")).
                js_anchor("<i class='fa fa-save fa-fw'></i>", 
                    array('id' => "cell-save-".$user->id, "data-filter" => $filter, 'name' => $user->id, 'title' => lang('save'), 
                    "class" => "cell-style cell-save hide"));

            $lists[] = $user_row;
        }
        echo json_encode(array("data"=>$lists));
    }

    function update_deductions() {
        $this->with_permission("staff_update", "no_permission");

        validate_submitted_data(array(
            "payslip_id" => "required",
            "item_key" => "required",
            "amount" => "required"
        ));
        
        $payslip_id = $this->input->post('payslip_id');
        $item_key = $this->input->post('item_key');
        $amount = $this->input->post('amount');

        set_payslip_item($payslip_id, $item_key, array("amount" => $amount), "deductions");
        echo json_encode(array("success" => true, 'message' => lang('record_saved')));       
    }

    function save_earning() {
        $this->with_permission("staff_update", "no_permission");
        
        $user_id = $this->input->post('user_id');
        $filter = $this->input->post('filter');

        $total = 0;
        foreach(array(
            "allowances", "incentives", "bonuses"
        ) as $item) {
            $meta_key = "user_".$filter."_".$item."_".$user_id."_earnings";
            $meta_val = $this->input->post( $item );

            if($saved = $this->Settings_model->save_setting($meta_key, $meta_val, "user")) {
                $total += 1;
            }
        }

        echo json_encode(array("success" => true, 'message' => lang('record_saved')." Total: ".$total));       
    }

    function contributions() {
        $view_data['department_select2'] = $this->_get_team_select2_data();
        $view_data['category_select2'] = [
            //array('id' => 'daily', 'text'  => '- Daily -'),
            array('id' => 'weekly', 'text'  => '- Weekly -'),
            array('id' => 'biweekly', 'text'  => '- Biweekly -'),
            array('id' => 'monthly', 'text'  => '- Monthly -')
        ];
        $this->load->view('payrolls/contributions', $view_data);
    }

    function contribution_lists() {
        $lists = array();
        
        $options = array(
            "status" => 'active',
            'department_id' => $this->input->post('department_id'),
            "user_type" => 'staff',
            "where_in" => $this->get_allowed_users_only("staff", true)
        );
        $actives = $this->Users_model->get_details($options)->result();

        $filter = $this->input->post('category_select2_filter');

        foreach($actives as $user) {
            $full_name = $user->first_name . " " . $user->last_name . " ";
            if(get_setting('name_format') == "lastfirst") {
                $full_name = $user->last_name . ", " . $user->first_name;
            }

            $user_id = $user->id;
            $filter = $filter?$filter:"weekly";

            $user_row = [
                $user->id,
                get_team_member_profile_link($user->id, $full_name),
            ];
    
            $total = 0;
            foreach(array(
                "sss_contri", "pagibig_contri", "philhealth_contri", "hmo_contri", "others"
            ) as $item) {
                $meta_key = "user_".$filter."_".$item."_".$user->id."_deductions";
                $meta_val = $this->Settings_model->get_setting($meta_key, "user");
    
                $user_row[] = cell_input(
                    $item.'_'.$user->id, 
                    convert_number_to_decimal($meta_val), 'number', 
                    'cell-class cell-class-'.$user->id, true
                );
            }

            $user_row[] = js_anchor("<i class='fa fa-pencil fa-fw'></i>", 
                    array('id' => "cell-edit-".$user->id, "data-filter" => $filter, 'name' => $user->id, 'title' => lang('edit'), 
                    "class" => "cell-style cell-edit")).
                js_anchor("<i class='fa fa-save fa-fw'></i>", 
                    array('id' => "cell-save-".$user->id, "data-filter" => $filter, 'name' => $user->id, 'title' => lang('save'), 
                    "class" => "cell-style cell-save hide"));

            $lists[] = $user_row;
        }
        echo json_encode(array("data"=>$lists));
    }

    function save_contribution() {
        $this->with_permission("staff_update", "no_permission");
        
        $user_id = $this->input->post('user_id');
        $filter = $this->input->post('filter');

        $total = 0;
        foreach(array(
            "sss_contri", "pagibig_contri", "philhealth_contri", "hmo_contri", "others"
        ) as $item) {
            $meta_key = "user_".$filter."_".$item."_".$user_id."_deductions";
            $meta_val = $this->input->post( $item );

            if($saved = $this->Settings_model->save_setting($meta_key, $meta_val, "user")) {
                $total += 1;
            }
        }

        echo json_encode(array("success" => true, 'message' => lang('record_saved')." Total: ".$total));       
    }

    function contribution_modal_form() {
        $view_data['user_id'] = $this->input->post('user_id'); //Todo
        
        $this->load->view('payrolls/contribution_modal_form', $view_data);
    }

    function save_auto_contribution() {
        $this->with_permission('payroll_auto_contribution', "no_permission");

        $success = 0; $failed = 0;

        $actives = $this->Users_model->get_all_active();
        foreach($actives as $user) {
            
            $user_id = $user->id;
            $hourly_rate = get_hourly_rate($user_id, false);
            $monthly_salary = get_monthly_from_hourly($hourly_rate, false);

            //Check monthly else pass.
            $total = 0;
            foreach(array(
                "weekly", "biweekly", "monthly"
            ) as $filter) {
                if($filter == "weekly") {
                    $devided = 4;
                } else if($filter == "biweekly") {
                    $devided = 2;
                } else { //monthly
                    $devided = 1;
                } 

                foreach(array(
                    "sss_contri", "pagibig_contri", "philhealth_contri"
                ) as $item) {
                    $meta_key = "user_".$filter."_".$item."_".$user_id."_deductions";
                    
                    if($item == "sss_contri") {
                        $this->Settings_model->save_setting($meta_key, 
                            get_sss_contribution($monthly_salary, false)/$devided, "user");
                    } else if($item == "pagibig_contri") {
                        $this->Settings_model->save_setting($meta_key, 
                            get_pagibig_contribution($monthly_salary, false)/$devided, "user");
                    } else if($item == "philhealth_contri") {
                        $this->Settings_model->save_setting($meta_key, 
                            get_phealth_contribution($monthly_salary, false)/$devided, "user");
                    }
    
                    $total += 1;
                }
            }
        }

        echo json_encode(array("success" => true, 'message' => lang('record_saved').". Total: $total"));
    }

    private function get_paid_holidays($user_id, $start_date, $end_date, $type = "regular") { //special
        $this->load->model("Holidays_model");

        $options = array(
            "start" => $start_date,
            "end" => $end_date,
        );

        $query = $this->Holidays_model->get_details($options);

        $list = array();

        if($query->num_rows() > 0) {
            foreach($query->result() as $item) {
                $list[] = $item;
            }
        }

        return $list;
    }

    private function get_paid_leave($user_id, $start_date, $end_date) {
        $this->load->model("Leave_applications_model");

        $options = array(
            "access_type" => "all",
            "applicant_id" => $user_id,
            "status" => "approved",
            "leave_type_paid_filter" => true,
            "start_date" => $start_date,
            "end_date" => $end_date,
        );
        $result = $this->Leave_applications_model->get_list($options)->result();

        $total_days = 0;
        foreach($result as $item) {
            $total_days += $item->total_days;
        }

        return convert_number_to_decimal($total_days);
    }

    protected function generate_payslip($payroll_info, $user_id = 0) {
        $attendance = $this->Attendance_model->get_details(array(
            "user_id" => $user_id,
            "start_date" => $payroll_info->start_date,
            "end_date" => $payroll_info->end_date,
            "status" => "approved",
            "access_type" => "all",
        ))->result();
        
        $attd = (new BioMeet($this, array(), true))
            ->setSchedHour($payroll_info->sched_hours)
            ->setAttendance($attendance)
            ->setHoliday( $this->get_paid_holidays( $user_id, $payroll_info->start_date, $payroll_info->end_date ) )
            ->calculate();
        
        $job_info = $this->Users_model->get_job_info($user_id);

        $payslip = $this->Payslips_model->get_one_where(array(
            'user' => $user_id,
            'payroll' => $payroll_info->id,
        ));
        
        if( !isset($payslip->id) ) {
            return false;
        }
        $payslip_id = $payslip->id;

        // EARNINGS START
        $earnings = array(); //get the value from earning table
        if( contain_str($payroll_info->earnings, "allowance") ) {
            $earnings[] = array( "payslip_id" => $payslip_id, "item_key" => "allowances", "title"=>"Allowance", 
                "amount" => get_user_option($payslip->user, "allowances", "earnings", $payroll_info->tax_table), "remarks" => false );
        }
        if( contain_str($payroll_info->earnings, "incentive") ) {
            $earnings[] = array( "payslip_id" => $payslip_id, "item_key" => "incentives", "title"=>"Incentive", 
                "amount" => get_user_option($payslip->user, "incentives", "earnings", $payroll_info->tax_table), "remarks" => false );
        }
        if( contain_str($payroll_info->earnings, "bonus") ) {
            $earnings[] = array( "payslip_id" => $payslip_id, "item_key" => "bonuses", "title"=>"Bonus", 
                "amount" => get_user_option($payslip->user, "others", "earnings", $payroll_info->tax_table), "remarks" => false );
        }
        if( contain_str($payroll_info->earnings, "allowance_monthly") ) {
            $earnings[] = array( "payslip_id" => $payslip_id, "item_key" => "allowances", "title"=>"Allowance", 
                "amount" => get_user_option($payslip->user, "allowances", "earnings", "monthly"), "remarks" => false );
        }
        if( contain_str($payroll_info->earnings, "incentive_monthly") ) {
            $earnings[] = array( "payslip_id" => $payslip_id, "item_key" => "incentives", "title"=>"Incentive", 
                "amount" => get_user_option($payslip->user, "incentives", "earnings", "monthly"), "remarks" => false );
        }
        if( contain_str($payroll_info->earnings, "bonus_monthly") ) {
            $earnings[] = array( "payslip_id" => $payslip_id, "item_key" => "bonuses", "title"=>"Bonus", 
                "amount" => get_user_option($payslip->user, "others", "earnings", "monthly"), "remarks" => false );
        }

        $earn_adjust_title = get_user_option($payslip->user, "adjust", "earnings", "title", true);
        $earn_adjust_amount = get_user_option($payslip->user, "adjust", "earnings", $payroll_info->tax_table);
        if($earn_adjust_title && $earn_adjust_amount) {
            $earnings[] = array( "payslip_id" => $payslip_id, "item_key" => "adjust", "title" => $earn_adjust_title, "amount" => $earn_adjust_amount, "remarks" => "taxable=true" );
        }

        $earn_other_title = get_user_option($payslip->user, "other", "earnings", "title", true);
        $earn_other_amount = get_user_option($payslip->user, "other", "earnings", $payroll_info->tax_table);
        if($earn_other_title && $earn_other_amount) {
            $earnings[] = array( "payslip_id" => $payslip_id, "item_key" => "other", "title" => $earn_other_title, "amount" => $earn_other_amount, "remarks" => false );
        }

        foreach($earnings as $earn) {
            set_payslip_item($payslip_id, $earn['item_key'], $earn, "earnings");
        }
        // EARNINGS END

        // DEDUCTIONS START
        $deductions = array();

        if( contain_str($payroll_info->deductions, "sss_contri") ) {
            $deductions[] = array( "payslip_id" => $payslip_id, "item_key" => "sss_contri", "title"=>"SSS Contribution", 
            "amount" => get_user_option($payslip->user, "sss_contri", "deductions", $payroll_info->tax_table), "remarks" => "tax_excess=true" );
        }
        if( contain_str($payroll_info->deductions, "phealth_contri") ) {
            $deductions[] = array( "payslip_id" => $payslip_id, "item_key" => "phealth_contri", "title"=>"Philhealth Contribution", 
            "amount" => get_user_option($payslip->user, "philhealth_contri", "deductions", $payroll_info->tax_table), "remarks" => "tax_excess=true" );
        }
        if( contain_str($payroll_info->deductions, "pagibig_contri") ) {
            $deductions[] = array( "payslip_id" => $payslip_id, "item_key" => "pagibig_contri", "title"=>"Pagibig Contribution", 
            "amount" => get_user_option($payslip->user, "pagibig_contri", "deductions", $payroll_info->tax_table), "remarks" => "tax_excess=true" );
        }
        if( contain_str($payroll_info->deductions, "hmo_contri") ) {
            $deductions[] = array( "payslip_id" => $payslip_id, "item_key" => "hmo", "title"=>"HMO Contribution", 
            "amount" => get_user_option($payslip->user, "hmo_contri", "deductions", $payroll_info->tax_table), "remarks" => false );
        }
        if( contain_str($payroll_info->deductions, "others") ) {
            $deductions[] = array( "payslip_id" => $payslip_id, "item_key" => "others", "title"=>"Others", 
            "amount" => get_user_option($payslip->user, "others", "deductions", $payroll_info->tax_table), "remarks" => false );
        }

        if( contain_str($payroll_info->deductions, "sss_contri_monthly") ) {
            $deductions[] = array( "payslip_id" => $payslip_id, "item_key" => "sss_contri", "title"=>"SSS Contribution", 
            "amount" => get_user_option($payslip->user, "sss_contri", "deductions", "monthly"), "remarks" => "tax_excess=true" );
        }
        if( contain_str($payroll_info->deductions, "phealth_contri_monthly") ) {
            $deductions[] = array( "payslip_id" => $payslip_id, "item_key" => "phealth_contri", "title"=>"Philhealth Contribution", 
            "amount" => get_user_option($payslip->user, "philhealth_contri", "deductions", "monthly"), "remarks" => "tax_excess=true" );
        }
        if( contain_str($payroll_info->deductions, "pagibig_contri_monthly") ) {
            $deductions[] = array( "payslip_id" => $payslip_id, "item_key" => "pagibig_contri", "title"=>"Pagibig Contribution", 
            "amount" => get_user_option($payslip->user, "pagibig_contri", "deductions", "monthly"), "remarks" => "tax_excess=true" );
        }
        if( contain_str($payroll_info->deductions, "hmo_contri_monthly") ) {
            $deductions[] = array( "payslip_id" => $payslip_id, "item_key" => "hmo", "title"=>"HMO Contribution", 
            "amount" => get_user_option($payslip->user, "hmo_contri", "deductions", "monthly"), "remarks" => false );
        }
        if( contain_str($payroll_info->deductions, "others_monthly") ) {
            $deductions[] = array( "payslip_id" => $payslip_id, "item_key" => "others", "title"=>"Others", 
            "amount" => get_user_option($payslip->user, "others", "deductions", "monthly"), "remarks" => false );
        }

        $loans = $this->Loans_model->get_loans($payslip->user, $payroll_info->tax_table)->result();
        foreach($loans as $item) {
            if( contain_str($payroll_info->deductions, "loan:".$item->cat_id) ) {
                $deductions[] = array( "payslip_id" => $payslip_id, "item_key" => "custom_loan_".$item->id, "title"=>$item->category_name, 
                    "amount" => $item->min_payment, "remarks" => "loan_term=( ".(intval($item->months_paid)+1)."/".$item->months_topay." )" );
            }
        }

        $deduct_adjust_title = get_user_option($payslip->user, "adjust", "deductions", "title", true);
        $deduct_adjust_amount = get_user_option($payslip->user, "adjust", "deductions", $payroll_info->tax_table);
        if($deduct_adjust_title && $deduct_adjust_amount) {
            $deductions[] = array( "payslip_id" => $payslip_id, "item_key" => "adjust", "title" => $deduct_adjust_title, "amount" => $deduct_adjust_amount, "remarks" => false );
        }

        $deduct_other_title = get_user_option($payslip->user, "other", "deductions", "title", true);
        $deduct_other_amount = get_user_option($payslip->user, "other", "deductions", $payroll_info->tax_table);
        if($deduct_other_title && $deduct_other_amount) {
            $deductions[] = array( "payslip_id" => $payslip_id, "item_key" => "other", "title" => $deduct_other_title, "amount" => $deduct_other_amount, "remarks" => false );
        }

        foreach($deductions as $deduct) {
            set_payslip_item($payslip_id, $deduct['item_key'], $deduct, "deductions");
        }
        // DEDUCTIONS END

        return array(
            "id" => $payslip_id,
            "payroll" => $payroll_info->id,
            "user" => $user_id,
            "hourly_rate" => $job_info->rate_per_hour,
            "leave_credit" => $payslip->leave_credit, 

            "schedule" => $payroll_info->sched_hours,//$attd->getTotalSchedule(), //schedule
            "worked" => $attd->getTotalWork(), //work
            "absent" => $attd->getTotalAbsent(), //absent
            "bonus" => $attd->getTotalBonus(), //bonus
            "pto" => $this->get_paid_leave($user_id, $payroll_info->start_date, $payroll_info->end_date), //pto

            "reg_ot" => $attd->getTotalRegularOvertime(), //Regular OT
            "rest_ot" => $attd->getTotalRestdayOvertime(), //Restday OT
            "reg_nd" => $attd->getTotalNightDiff(), //Nightpay

            "special_hd" => $attd->getTotalSpecialHD(), //overtime
            "legal_hd" => $attd->getTotalLegalHD(), //overtime
        );
    }

    function recalculate( $payroll_id = 0, $silent = false ) {

        //Get payroll instance
        $payroll_info = $this->Payrolls_model->get_details(array(
            "id" => $payroll_id
        ))->row();

        $payslips = $this->Payslips_model->get_details(array(
            "payroll_id" => $payroll_id,
            "status" => "draft"
        ))->result(); //ONGOING
            
        foreach($payslips as $current) {
            $payslip = $this->generate_payslip($payroll_info, $current->user);
            $this->Payslips_model->update_where( $payslip, array("id"=>$current->id) );
        }

        if(!$silent) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        }
    }

    function mark_as_ongoing( $payroll_id = 0 ) {
        //Get payroll instance
        $payroll_info = $this->Payrolls_model->get_details(array(
            "id" => $payroll_id
        ))->row();

        $users = [];
        $teams = explode(",", $payroll_info->department);

        foreach($teams as $team) {
            //Get all the users within a dept id ng payroll 
            $dept = $this->Team_model->get_details(array(
                "id" => $team
            ))->row();

            //get all users unique within the dept heads or members.
            $users = array_merge($users, get_team_all_unique($dept->heads, $dept->members) );
        }
        
        //schedule hours for this date.
        //absent, late, overbreak, undertime
        foreach($users as $user_id) {

            if(!$this->Users_model->is_user_active($user_id)) {
                continue;
            }

            $attendance = $this->Attendance_model->get_details(array(
                "user_id" => $user_id,
                "start_date" => $payroll_info->start_date,
                "end_date" => $payroll_info->end_date,
                "status" => "approved",
                "access_type" => "all",
            ))->result();
            
            $attd = (new BioMeet($this, array(), true))
                ->setSchedHour($payroll_info->sched_hours)
                ->setAttendance($attendance)
                ->setHoliday( $this->get_paid_holidays( $user_id, $payroll_info->start_date, $payroll_info->end_date ) )
                ->calculate();
            
            $job_info = $this->Users_model->get_job_info($user_id);

            $leave_credit_balance = $this->Leave_credits_model->get_balance(array(
                "user_id" => $user_id
            ));
    
            $new_payslip = array(
                "payroll" => $payroll_info->id,
                "user" => $user_id,
                "hourly_rate" => $job_info->rate_per_hour,
                "leave_credit" => $leave_credit_balance,
                    
                "schedule" => $payroll_info->sched_hours,//$attd->getTotalSchedule(), //schedule
                "worked" => $attd->getTotalWork(), //work
                "absent" => $attd->getTotalAbsent(), //absent
                "bonus" => $attd->getTotalBonus(), //bonus
                "pto" => $this->get_paid_leave($user_id, $payroll_info->start_date, $payroll_info->end_date), //pto
    
                "reg_ot" => $attd->getTotalRegularOvertime(), //Regular OT
                "rest_ot" => $attd->getTotalRestdayOvertime(), //Restday OT
                "reg_nd" => $attd->getTotalNightDiff(), //Nightpay
    
                "special_hd" => $attd->getTotalSpecialHD(), //overtime
                "legal_hd" => $attd->getTotalLegalHD(), //overtime
            );
            
            //To create a payslip for that user in a list.
            $payslip_id = $this->Payslips_model->save( $new_payslip );
        }
        $this->recalculate($payroll_info->id, true);

        $payroll_data["status"] = "ongoing";
        if ($this->Payrolls_model->save($payroll_data, $payroll_id)) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($payroll_id), "id" => $payroll_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function mark_as_paid( $payroll_id = 0 ) {

        //TODO: Add a new payment for this payroll.
        $date = $this->input->post('expense_payment_date');
        $expense_data = array(
            "expense_date" => convert_date_local_to_utc($date),
            "due_date" => convert_date_local_to_utc($date),
            "title" => get_payroll_id($payroll_id),
            "description" => $this->input->post('expense_payment_note'),
            "account_id" => $this->input->post('expense_account_id'), //done
            "category_id" => $this->input->post('expense_payment_category_id'),
            "amount" => unformat_currency( $this->input->post('expense_payment_amount') ),
            "user_id" => $this->input->post('expense_user_id'), //done
            //"client_id" => 0,
            //"project_id" => 0,
            
            //"tax_id" => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
            //"tax_id2" => $this->input->post('tax_id2') ? $this->input->post('tax_id2') : 0,
            "recurring" => 0,
            "repeat_every" => 0,
            "repeat_type" => NULL,
            "no_of_cycles" => 0,
        );
        $expense_id = $this->Expenses_model->save($expense_data);

        $payroll_data["expense_id"] = $expense_id;
        $payroll_data["status"] = "completed";
        if ($this->Payrolls_model->save($payroll_data, $payroll_id)) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($id), "id" => $id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function mark_as_cancelled( $payroll_id ) {
        $payroll_data["status"] = "cancelled";
        if ($this->Payrolls_model->save($payroll_data, $payroll_id)) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($payroll_id), "id" => $payroll_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function lock_payment( $payroll_id = 0 ) {

        $view_data['payment_categories_dropdown'] = array("" => "-") + $this->Expense_categories_model->get_dropdown_list(array("title"), "id", array("deleted" => 0));
        $view_data['payment_methods_dropdown'] = array("" => "-") + $this->Payment_methods_model->get_dropdown_list(array("title"), "id", array("deleted" => 0));
        $view_data['account_dropdown'] = array("" => "-") + $this->Accounts_model->get_dropdown_list(array("name"), "id", array("deleted" => 0));
        $view_data['model_info'] = $payroll_id ? $this->Payrolls_model->get_details(array("id" => $payroll_id))->row() : "";
        $view_data['cur_user_id'] = $this->login_user->id;

        $this->load->view('payrolls/payment_modal_form', $view_data);
    }

    protected function prepare_payslip_pdf( $data, $mode) { //download, send_email, view, html
        $this->load->library('pdf');
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetCellPadding(0.7);
        $this->pdf->setImageScale(2.0);
        $this->pdf->AddPage();
        $this->pdf->SetFontSize(9);

        if( $data ) {
            $html = $this->load->view("payrolls/preview_modal_form", $data, true);
            $this->pdf->writeHTML($html, true, false, true, false, '');

            $fullname = $data['payslip']->employee_name;
            $file_name =  str_replace(" ", "-", $fullname).".pdf";

            if ($mode === "download") {
                $this->pdf->Output($file_name, "D");
            } else if ($mode === "send_email") {
                $file_name = $this->uuid->v4()."-".$file_name;
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $file_name;
                $this->pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $this->pdf->Output($file_name, "I");
            } else if ($mode === "html") {
                return $html;
            }
        }
    }

    function payment_list( $payroll_id = 0 ) {
        $payroll_info = new stdClass();
        $payroll_info->id = 1;

        $view_data["payroll_info"] = $payroll_info;

        $this->load->view('payrolls/payments', $view_data);
    }

    function payment_list_data($id = 0) {
        $list_data = [1,2,3,4,5];
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    function _make_payment_row( $data ) {
        $data = new stdClass();
        $data->id = 1;
        $data->employee_id = 1;
        $data->employee_name = "Juan De Cruz";

        $data->job_title = "Team Lead";
        $data->department = "Sales Department";

        $data->basic_pay = 10123.56;
        $data->total_earn = 9000.54;
        $data->total_deduct = 1200.45;
        $data->net_pay = 8456.45;

        return array(
            $data->id, //payslip_id
            get_team_member_profile_link($data->employee_id, $data->employee_name, array("target" => "_blank")), //user link

            $data->job_title, //job_title
            $data->department, //department

            $data->basic_pay, //basic_pay
            $data->total_earn, //total_earn
            $data->total_deduct, //total_deduct
            $data->net_pay //net_pay
        );
    }

    function payslip_list_data($payroll_id = 0) {

        //Get payroll instance
        $payroll = $this->Payrolls_model->get_details(array(
            "id" => $payroll_id
        ))->row();
        
        $list_data = $this->Payslips_model->get_details(array(
            'payroll_id' => $payroll_id,
            'status' => $this->input->post('status')
        ))->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payslip_row($data, $payroll);
        }
        echo json_encode(array("data" => $result));
    }

    private function get_payslip_status($data){
        $labeled_status = "";

        if($data->status == "rejected"){
            $labeled_status = "<span class='label label-danger'>CANCELLED</span>";
        } else if($data->status == "approved"){
            $labeled_status = "<span class='label label-success'>APPROVED</span>";
        } else {
            $labeled_status = "<span class='label label-default'>DRAFT</span>";
        }

        return $labeled_status;
    }

    function _make_payslip_row( $payslip, $payroll ) {

        $data = $payslip;

        $summary = $this->processPayHP( $data, $payroll->tax_table )->calculate();

        $preview = modal_anchor(get_uri("payrolls/preview/".$data->id), get_payslip_id($data->id, $data->payroll), array( "title" => lang('preview_payslip'), "data-post-payroll_id" => $data->id));
        
        $view = '<li role="presentation">' . modal_anchor(get_uri("fas/payrolls/preview_modal_form/".$data->id), "<i class='fa fa-eye'></i> " . lang('view_pdf'), array("title" => lang('view_pdf'), "data-post-view" => "details")) . '</li>';
        
        if($data->status == "draft") {

            $check = '<li role="presentation">' . modal_anchor(get_uri("fas/payrolls/attendance_modal_form"), "<i class='fa fa-calendar'></i> " . lang('check_logs'), array("title" => lang('check_logs').": ".$data->employee_name, "data-post-user_id" => $data->user, "data-post-start_date" => "$payroll->start_date", "data-post-end_date" => "$payroll->end_date" )) . '</li>';
            
            $override = '<li role="presentation">' . modal_anchor(get_uri("fas/payrolls/override_modal_form/".$data->id), "<i class='fa fa-check'></i> " . lang('override'), array("title" => lang('override'), "data-post-view" => "details")) . '</li>';

            $signe = '<li role="presentation">' . js_anchor("<i class='fa fa-paw fa-fw'></i>" . lang('approve'), array("class" => "update", "style" => "border-radius: 0; width: -webkit-fill-available; border: none; text-align: left;", "data-action-url" => get_uri("fas/payrolls/approve_payslip/".$data->id), "data-action" => "update", "data-reload-on-success" => "1")) . '</li>';
            
        } else if($data->status == "approved") {

            $send = '<li role="presentation">' . modal_anchor(get_uri("fas/payrolls/send_payslip_modal_form"), "<i class='fa fa-envelope'></i> " . lang('send_email'), array("title" => lang('send_email'), "data-post-id" => $data->id )) . '</li>';

            $pdf = "<li role='presentation'>" . anchor(get_uri("fas/payrolls/download_pdf/".$data->id), "<i class='fa fa-download'></i>  &nbsp" . lang('download_pdf'), array("style" => "border-radius: 0; width: -webkit-fill-available; border: none; text-align: left;", "title" => lang('view_pdf'), "target" => "_blank")) . "</li>";
            
            $cancel = '<li role="presentation">' . js_anchor("<i class='fa fa-exclamation fa-fw'></i>" . lang('cancel'), array("style" => "border-radius: 0; width: -webkit-fill-available; border: none; text-align: left;", "class" => "update", "data-action-url" => get_uri("fas/payrolls/cancel_payslip/".$data->id), "data-action" => "update", "data-reload-on-success" => "1")) . '</li>';

        } 
        
        $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => "  &nbsp".lang('delete'), "class" => "delete", "style"=>"border-radius: 0; width: -webkit-fill-available; border: none; text-align: left;", "data-id" => $data->id, "data-action-url" => get_uri("fas/payrolls/delete_payslip"), "data-action" => "delete-confirmation")) . '</li>';
        
        $actions = '<span class="dropdown inline-block" style="position: relative; right: 0; margin-top: 0;">
                        <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-cogs"></i>&nbsp;
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">' . $override . $send . $check . $view . $pdf . $signe . $cancel . $delete . '</ul>
                    </span>';

        return array(
            $preview,//anchor(get_uri("payrolls/preview/" . $data->id), ), //payslip_id
            get_team_member_profile_link($data->user, $data->employee_name, array("target" => "_blank")), //user link

            to_currency( $summary['basic_pay'] ),
            $data->worked,   
            to_currency( $summary['overtime_pay'] ),    
            to_currency( $summary['total_deductions'] ), 
            to_currency( $summary['gross_pay'] ),   
            to_currency( $summary['tax_due'] ),  
            "<strong ".($summary['net_pay']<=0?"style='color: red;'":"").">".to_currency( $summary['net_pay'] )."</strong>", 

            $this->get_payslip_status($data),
            intval($data->sents),
            $payroll->status=="ongoing"?$actions:""
        );
    }

    protected function processPayHP( $data, $tax_table ) {

        $data->earnings = $this->Payslip_earnings_model->get_details(array(
            "payslip_id" => $data->id,
        ))->result();

        $data->deductions = $this->Payslip_deductions_model->get_details(array(
            "payslip_id" => $data->id
        ))->result();

        $basicpay_calculation = get_setting('basic_pay_calculation', 'hourly_based');
        $instance = (new PayHP()) 
            ->setCalculationMode($basicpay_calculation)
            ->setMonthlySalary( get_monthly_salary($data->user, false) )
            ->setHourlyRate($data->hourly_rate)
            ->setTaxTable("term", $tax_table)
            ->setTaxTable($tax_table, get_compensation_tax($tax_table))

            ->setHour('schedule', $data->schedule)
            ->setHour('worked', $data->worked)
            ->setHour('absent', $data->absent)
            ->setHour('bonus', $data->bonus)
            ->setHour('pto', $data->pto)

            ->setOvertime('regular', $data->reg_ot)
            ->setOvertime('restday', $data->rest_ot)

            ->setNightdiff($data->reg_nd)

            ->setHoliday('special', $data->special_hd)
            ->setHoliday('legal', $data->legal_hd);

        if(is_array($data->earnings)) {
            foreach($data->earnings as $earn) {
                $instance = $instance->addEarnings($earn->title, $earn->amount, $earn->remarks=="taxable=true"?true:false);
            }
        }
        
        if( is_array($data->deductions) ) {
            foreach($data->deductions as $deduct) {
                if( contain_str($deduct->remarks, "loan_term=") ) {
                    $remarks = str_replace("loan_term=", "", $deduct->remarks);
                }
                $instance = $instance->addDeductions($deduct->title, $deduct->amount, $deduct->remarks=="tax_excess=true"?true:false, $remarks);
            }
        }

        $instance->leave_credit = $data->leave_credit;
        return $instance;
    }

    function override_modal_form( $payslip_id = 0 ) {

        $data = $this->Payslips_model->get_details(array(
            "id" => $payslip_id
        ))->row();

        $payroll = $this->Payrolls_model->get_details(array(
            "id" => $data->payroll
        ))->row();

        $view_data["payslip_id"] = $payslip_id;
        $view_data["fullname"] = $data->employee_name;
        $view_data["job_title"] = $data->job_title;
        $view_data["department"] = $data->department_name;
        $view_data["salary"] = to_currency( get_monthly_salary($data->user, false) );

        $summary = $this->processPayHP( $data, $payroll->tax_table )->calculate();

        $view_data["biometrics"] = [
            array(
                "key" => "schedule",
                "value" => $data->schedule
            ),
            array(
                "key" => "worked",
                "value" => $data->worked,
            ),
            array(
                "key" => "absent",
                "value" => $data->absent
            ),
            array(
                "key" => "bonus",
                "value" => $data->bonus
            ),
        ];

        $view_data["holiday"] = [
            array(
                "key" => "special_hd",
                "value" => $data->special_hd
            ),
            array(
                "key" => "legal_hd",
                "value" => $data->legal_hd
            ),
            array(
                "key" => "pto",
                "value" => $data->pto
            ),
            array(
                "key" => "leave_credit",
                "value" => convert_number_to_decimal($data->leave_credit),
                "type" => "number",
            ),
        ];

        $view_data["overtime"] = [
            array(
                "key" => "regular_ot",
                "value" => $data->reg_ot
            ),
            array(
                "key" => "restday_ot",
                "value" => $data->rest_ot
            ),
            array(
                "key" => "regular_nd",
                "value" => $data->reg_nd
            ),
        ];

        $view_data["earnings"] = [
            array(
                "key" => "basic_pay",
                "value" => to_currency($summary['basic_pay']),
                "disabled" => true,
                "type" => "text",
                "class" => "disabled",
            ),
            array(
                "key" => "hourly_rate",
                "value" => convert_number_to_decimal($data->hourly_rate),
                "disabled" => true,
                "type" => "text",
                "class" => "disabled",
            ),
            array(
                "key" => "bonusPay",
                "value" => to_currency($summary['bonus_pay']),
                "disabled" => true,
                "type" => "text",
                "class" => "disabled",
            ),
            
        ];

        $view_data["additionals"] = [
            array(
                "key" => "allowances",
                "value" => get_payslip_item($payslip_id, 'allowances', "earnings")->amount
            ),
            array(
                "key" => "incentives",
                "value" => get_payslip_item($payslip_id, 'incentives', "earnings")->amount
            ),
            array(
                "key" => "bonuses",
                "value" => get_payslip_item($payslip_id, 'bonuses', "earnings")->amount
            )
        ];

        $earn_adjust = get_payslip_item($payslip_id, 'earn_adjust', "earnings");
        $earn_other = get_payslip_item($payslip_id, 'earn_other', "earnings");
        $view_data["earn_other"] = [
            array(
                "key" => "earn_adjust_name",
                "value" => $earn_adjust->title,
                "type" => "text"
            ),
            array(
                "key" => "earn_adjust",
                "value" => $earn_adjust->amount,
                "type" => "number"
            ),
            array(
                "key" => "earn_other_name",
                "value" => $earn_other->title,
                "type" => "text"
            ),
            array(
                "key" => "earn_other",
                "value" => $earn_other->amount,
                "type" => "number"
            )
        ];

        $view_data["non_tax_deducts"] = [
            array(
                "key" => "sss",
                "value" => get_payslip_item($payslip_id, 'sss_contri', "deductions")->amount
            ),
            array(
                "key" => "pagibig",
                "value" => get_payslip_item($payslip_id, 'pagibig_contri', "deductions")->amount
            ),
            array(
                "key" => "phealth",
                "value" => get_payslip_item($payslip_id, 'phealth_contri', "deductions")->amount
            ),
            array(
                "key" => "hmo",
                "value" => get_payslip_item($payslip_id, 'hmo', "deductions")->amount
            )
        ];

        $view_data["non_tax_loans"] = [];
        $loans = $this->Payslip_deductions_model->get_details(array(
            "payslip_id" => $payslip_id,
            "item_key_like" => "custom_loan_"
        ))->result();
        foreach($loans as $item) {
            $view_data["non_tax_loans"][] = array(
                "key" => $item->item_key,
                "title" => $item->title,
                "value" => $item->amount
            );
        }

        $deduct_adjust = get_payslip_item($payslip_id, 'deduct_adjust', "deductions");
        $deduct_other = get_payslip_item($payslip_id, 'deduct_other', "deductions");
        $view_data["non_tax_other"] = [
            array(
                "key" => "deduct_adjust_name",
                "value" => $deduct_adjust->title,
                "type" => "text"
            ),
            array(
                "key" => "deduct_adjust",
                "value" => $deduct_adjust->amount,
                "type" => "number"
            ),
            array(
                "key" => "deduct_other_name",
                "value" => $deduct_other->title,
                "type" => "text"
            ),
            array(
                "key" => "deduct_other",
                "value" => $deduct_other->amount,
                "type" => "number"
            )
        ];

        $view_data["summary_additionals"] = [
            array(
                "key" => "overtimePay",
                "value" => to_currency($summary['overtime_pay'])
            ),
            array(
                "key" => "holidayPay",
                "value" => to_currency($summary['holiday_pay'])
            ),
            array(
                "key" => "nightdiffPay",
                "value" => to_currency($summary['nightdiff_pay'])
            )
        ];

        $view_data["summary_deductions"] = [
            array(
                "key" => "unwork_deductions",
                "value" => to_currency($summary['unwork_deduction'])
            ),
            array(
                "key" => "pto_pay",
                "value" => to_currency($summary['pto_pay']),
                "disabled" => true,
                "type" => "text",
                "class" => "disabled",
            ),
            array(
                "key" => "taxDue",
                "value" => to_currency($summary['tax_due'])
            ),
        ];

        $view_data["summary_totals"] = [
            array(
                "key" => "net_taxable",
                "value" => to_currency($summary['net_taxable'])
            ),
            array(
                "key" => "gross_pay",
                "value" => to_currency($summary['gross_pay'])
            ),
            array(
                "key" => "net_pay",
                "value" => to_currency($summary['net_pay'])
            )
        ];

        $this->load->view('payrolls/override_modal_form', $view_data);
    }

    function override_calculate( $payslip_id = 0 ) {

        $action = $this->input->post('action');

        //TODO: Save here and calculate
        if($action === "overwrite") {
            $payslip_data = array(
                "pto" => $this->input->post('pto'),

                "schedule" => $this->input->post('schedule'),
                "worked" => $this->input->post('worked'),
                "absent" => $this->input->post('absent'),
                "bonus" => $this->input->post('bonus'),

                "leave_credit" => $this->input->post('leave_credit'),

                "reg_nd" => $this->input->post('regular_nd'),
                "reg_ot" => $this->input->post('regular_ot'),
                "rest_ot" => $this->input->post('restday_ot'),

                "special_hd" => $this->input->post('special_hd'),
                "legal_hd" => $this->input->post('legal_hd'),
                "pto" => $this->input->post('pto'),
            );

            if( $success = $this->Payslips_model->save($payslip_data, $payslip_id) ) {
                set_payslip_item($payslip_id, "allowances", array(
                    "title" => "Allowance",
                    "amount" => $this->input->post('allowances')
                ), "earnings", "");

                set_payslip_item($payslip_id, "incentives", array(
                    "title" => "Incentive",
                    "amount" => $this->input->post('incentives')
                ), "earnings", "");

                set_payslip_item($payslip_id, "bonuses", array(
                    "title" => "Bonus",
                    "amount" => $this->input->post('bonuses')
                ), "earnings", "");

                set_payslip_item($payslip_id, "earn_adjust", array(
                    "title" => $this->input->post('earn_adjust_name'),
                    "amount" => $this->input->post('earn_adjust')
                ), "earnings", "");

                set_payslip_item($payslip_id, "earn_other", array(
                    "title" => $this->input->post('earn_other_name'),
                    "amount" => $this->input->post('earn_other')
                ), "earnings", "");


                set_payslip_item($payslip_id, "sss_contri", array(
                    "title" => "SSS Contribution",
                    "amount" => $this->input->post('sss')
                ), "deductions", "");

                set_payslip_item($payslip_id, "phealth_contri", array(
                    "title" => "Philhealth Contribution",
                    "amount" => $this->input->post('phealth')
                ), "deductions", "");

                set_payslip_item($payslip_id, "pagibig_contri", array(
                    "title" => "Pagibig Contribution",
                    "amount" => $this->input->post('pagibig')
                ), "deductions", "");

                set_payslip_item($payslip_id, "hmo", array(
                    "title" => "HMO Contribution",
                    "amount" => $this->input->post('hmo')
                ), "deductions", "");

                set_payslip_item($payslip_id, "other_loan", array(
                    "title" => "Other Loan",
                    "amount" => $this->input->post('other_loan')
                ), "deductions", "");

                set_payslip_item($payslip_id, "deduct_adjust", array(
                    "title" => $this->input->post('deduct_adjust_name'),
                    "amount" => $this->input->post('deduct_adjust')
                ), "deductions", "");

                set_payslip_item($payslip_id, "deduct_other", array(
                    "title" => $this->input->post('deduct_other_name'),
                    "amount" => $this->input->post('deduct_other')
                ), "deductions", "");
            };
        } else if($action === "recalculate") {
            $payslip_detail = $this->Payslips_model->get_details(array(
                "id" => $payslip_id,
                "status" => "draft"
            ))->row(); 

            //Get payroll instance
            $payroll_info = $this->Payrolls_model->get_details(array(
                "id" => $payslip_detail->payroll
            ))->row();

            $payslip = $this->generate_payslip($payroll_info, $payslip_detail->user);
            $this->Payslips_model->update_where( $payslip, array("id"=>$payslip_detail->id) );
        }

        $data = $this->Payslips_model->get_details(array(
            "id" => $payslip_id
        ))->row();

        $payroll = $this->Payrolls_model->get_details(array(
            "id" => $data->payroll
        ))->row();

        $summary = $this->processPayHP( $data, $payroll->tax_table )->calculate();

        $view_data = array(
            "schedule" => $data->schedule,
            "worked" => $data->worked,
            "absent" => $data->absent,
            "bonus" => $data->bonus,

            "reg_ot" => $data->reg_ot,
            "rest_ot" => $data->rest_ot,
            "pto" => $data->pto,
            "leave" => $data->leave_credit,

            "reg_hd" => $data->legal_hd,
            "spc_hd" => $data->special_hd,
            "night" => $data->reg_nd,

            "basic_pay" => $data->basic_pay,
            "hourly_rate" => $data->hourly_rate,

            //TODO: Get the value
            // "allowances" => $data->not_set,
            // "incentives" => $data->not_set,
            // "bonuses" => $data->not_set,

            // "sss" => $data->not_set,
            // "pagibig" => $data->not_set,
            // "phealth" => $data->not_set,
            // "hmo" => $data->not_set,

            "overtime_pay" => to_currency($summary['overtime_pay']),
            "holiday_pay" => to_currency($summary['holiday_pay']),
            "nightdiff_pay" => to_currency($summary['nightdiff_pay']),

            "unwork_deductions" => to_currency($summary['unwork_deduction']),
            "pto_pay" => to_currency($summary['pto_pay']),
            "tax_due" => to_currency($summary['tax_due']),

            "net_taxable" => to_currency($summary['net_taxable']),
            "gross_pay" => to_currency($summary['gross_pay']),
            "net_pay" => to_currency($summary['net_pay']),

            "bonus_pay" => to_currency($summary['bonus_pay']),
        );

        echo json_encode(array("success"=>true, "data" => $view_data, "message"=>lang('record_saved')));
    }

    function cancel_payslip( $id ) {

        $data = array(
            "signed_by" => $this->login_user->id,
            "signed_at" => get_current_utc_time(),
            "status" => "rejected"
        );

        if ($this->Payslips_model->save($data, $id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function approve_payslip( $id ) {

        //TODO: Get loan deductions attached to this payslip and add payment.
        $payslip = $this->Payslips_model->get_details(array(
            "id" => $id
        ))->row(); //ONGOING

        //Get payroll instance
        $payroll_info = $this->Payrolls_model->get_details(array(
            "id" => $payslip->payroll
        ))->row();
        
        $loans = $this->Loans_model->get_loans($payslip->user, $payroll_info->tax_table)->result();
        foreach($loans as $item) {
            log_message("error", "loan:".$item->cat_id);
            if( contain_str($payroll_info->deductions, "loan:".$item->cat_id) ) {
                $data = array(
                    "loan_id" => $item->id,
                    "date_paid" => convert_date_local_to_utc($payroll_info->pay_date),
                    "amount" => $item->min_payment,
                    "late_interest" => 0,
                    "remarks" => lang("payslip_generated_payment").get_payroll_id($payroll_info->id),
                    "created_by" => $this->login_user->id
                );
                $last_id = $this->Loan_payments_model->save($data);
            }
        }

        $data = array(
            "signed_by" => $this->login_user->id,
            "signed_at" => get_current_utc_time(),
            "status" => "approved"
        );
        if ($this->Payslips_model->save($data, $id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete_payslip() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Payslips_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function preview_modal_form( $payslip_id = 0 ) {
        $payslip = $this->Payslips_model->get_details(array(
            "id" => $payslip_id
        ))->row();

        $payroll = $this->Payrolls_model->get_details(array(
            "id" => $payslip->payroll
        ))->row();

        $payslip->pay_date = convert_date_format($payroll->pay_date, "F d, Y");
        $payslip->pay_period= convert_date_format($payroll->start_date, "F d-").convert_date_format($payroll->end_date, "d Y");

        $user = $this->Users_model->get_details(array(
            "id" => $payslip->user
        ))->row();
        $payslip->fullname = $user->first_name." ".$user->last_name;
        $payslip->job_title = $user->job_title;

        $team = $this->Team_model->get_teams($payslip->user)->row();//todo
        $payslip->department = $team?$team->title:"None";

        $job_info = $this->Users_model->get_job_info($payslip->user);
        $payslip->salary = $job_info->salary;
        $payslip->bank_name = $job_info->bank_name;
        $payslip->bank_account = $job_info->bank_account;
        $payslip->bank_number = $job_info->bank_number;

        $accountant = $this->Users_model->get_baseinfo($payroll->accountant_id);
        $payslip->accountant_name = $accountant->first_name." ".$accountant->last_name;
        $payslip->accountant_title = $accountant->job_title;

        $view_data["payslip"] = $payslip;
        $view_data["summary"] = $this->processPayHP( $payslip, $payroll->tax_table )->calculate();

        $payslip->amount_in_words = (new Amount_In_Words())->convertNumber( $view_data['summary']['net_pay'] );

        $this->load->view('payrolls/preview_modal_form', $view_data);
    }

    function download_pdf($id = 0) {
        if ($id) {
            $payslip = $this->Payslips_model->get_details(array(
                "id" => $id
            ))->row();
    
            $payroll = $this->Payrolls_model->get_details(array(
                "id" => $payslip->payroll
            ))->row();
    
            $payslip->pay_date = convert_date_format($payroll->pay_date, "F d, Y");
            $payslip->pay_period= convert_date_format($payroll->start_date, "F d-").convert_date_format($payroll->end_date, "d Y");
    
            $user = $this->Users_model->get_details(array(
                "id" => $payslip->user
            ))->row();
            $payslip->fullname = $user->first_name." ".$user->last_name;
            $payslip->job_title = $user->job_title;
    
            $team = $this->Team_model->get_teams($payslip->user)->row();//todo
            $payslip->department = $team?$team->title:"None";
    
            $job_info = $this->Users_model->get_job_info($payslip->user);
            $payslip->salary = $job_info->salary;
            $payslip->bank_name = $job_info->bank_name;
            $payslip->bank_account = $job_info->bank_account;
            $payslip->bank_number = $job_info->bank_number;

            $accountant = $this->Users_model->get_baseinfo($payroll->accountant_id);
            $payslip->accountant_name = $accountant->first_name." ".$accountant->last_name;
            $payslip->accountant_title = $accountant->job_title;

            $view_data["payslip"] = $payslip;
            $view_data["summary"] = $this->processPayHP( $payslip, $payroll->tax_table )->calculate();
    
            $payslip->amount_in_words = (new Amount_In_Words())->convertNumber( $view_data['summary']['net_pay'] );

            $this->prepare_payslip_pdf($view_data, "view");
        } else {
            show_404();
        }
    }

    function attendance_modal_form() {
        $user_id = $this->input->post("user_id");
        if(!$user_id) {
            show_404();
        }

        $view_data['user_id'] = $user_id;
        $view_data['start_date'] = $this->input->post("start_date");
        $view_data['end_date'] = $this->input->post("end_date");
        $this->load->view('payrolls/attendance_modal_form', $view_data);
    }

    function download_approved_payslip($payroll_id = 0) {

        $this->load->library('pdf');
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetCellPadding(0.7);
        $this->pdf->setImageScale(2.0);
        $this->pdf->SetFontSize(9);

        $payroll = $this->Payrolls_model->get_details(array(
            "id" => $payroll_id
        ))->row();

        $list_data = $this->Payslips_model->get_details(array(
            'payroll_id' => $payroll_id,
            //'status' => "approved"
        ))->result();

        foreach($list_data as $payslip) {
            if($payslip->status != "approved")
                continue;

            $this->pdf->AddPage();

            $payslip->pay_date = convert_date_format($payroll->pay_date, "F d, Y");
            $payslip->pay_period= convert_date_format($payroll->start_date, "F d-").convert_date_format($payroll->end_date, "d Y");
    
            $user = $this->Users_model->get_details(array(
                "id" => $payslip->user
            ))->row();
            $payslip->fullname = $user->first_name." ".$user->last_name;
            $payslip->job_title = $user->job_title;
    
            $team = $this->Team_model->get_teams($payslip->user)->row();//todo
            $payslip->department = $team?$team->title:"None";
    
            $job_info = $this->Users_model->get_job_info($payslip->user);
            $payslip->salary = $job_info->salary;
            $payslip->bank_name = $job_info->bank_name;
            $payslip->bank_account = $job_info->bank_account;
            $payslip->bank_number = $job_info->bank_number;
    
            $accountant = $this->Users_model->get_baseinfo($payroll->accountant_id);
            $payslip->accountant_name = $accountant->first_name." ".$accountant->last_name;
            $payslip->accountant_title = $accountant->job_title;
    
            $view_data["payslip"] = $payslip;
            $view_data["summary"] = $this->processPayHP( $payslip, $payroll->tax_table )->calculate();
    
            $payslip->amount_in_words = (new Amount_In_Words())->convertNumber( $view_data['summary']['net_pay'] );

            $html = $this->load->view("payrolls/preview_modal_form", $view_data, true);
            $this->pdf->writeHTML($html, true, false, true, false, '');
        }

        $file_name =  $accountant->first_name.$accountant->last_name.".pdf";
        $this->pdf->Output($file_name, "I");
    }

    function send_payslip(){
        validate_submitted_data(array(
            "payslip_id" => "numeric"
        ));

        $email_template = $this->Email_templates_model->get_final_template("payslips");

        $payslip_id = $this->input->post('payslip_id');
        $remarks = $this->input->post('remarks');

        $payslip = $this->Payslips_model->get_details(array(
            "id" => $payslip_id
        ))->row();

        $payroll = $this->Payrolls_model->get_details(array(
            "id" => $payslip->payroll
        ))->row();

        $payslip->pay_date = convert_date_format($payroll->pay_date, "F d, Y");
        $payslip->pay_period= convert_date_format($payroll->start_date, "F d-").convert_date_format($payroll->end_date, "d Y");

        $user = $this->Users_model->get_details(array(
            "id" => $payslip->user
        ))->row();
        $payslip->fullname = $user->first_name." ".$user->last_name;
        $payslip->job_title = $user->job_title;

        $team = $this->Team_model->get_teams($payslip->user)->row();//todo
        $payslip->department = $team?$team->title:"None";

        $job_info = $this->Users_model->get_job_info($payslip->user);
        $payslip->salary = $job_info->salary;
        $payslip->bank_name = $job_info->bank_name;
        $payslip->bank_account = $job_info->bank_account;
        $payslip->bank_number = $job_info->bank_number;

        $accountant = $this->Users_model->get_baseinfo($payroll->accountant_id);
        $payslip->accountant_name = $accountant->first_name." ".$accountant->last_name;
        $payslip->accountant_title = $accountant->job_title;

        $view_data["payslip"] = $payslip;
        $view_data["summary"] = $this->processPayHP( $payslip, $payroll->tax_table )->calculate();

        $payslip->amount_in_words = (new Amount_In_Words())->convertNumber( $view_data['summary']['net_pay'] );

        $payslip_url = $this->prepare_payslip_pdf($view_data, "send_email");

        //add uploaded files
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "payslip");
        $attachments = prepare_attachment_of_files(get_setting("timeline_file_path"), $files_data);
        array_unshift($attachments, array("file_path" => $payslip_url));

        $parser_data["PAYSLIP_ID"] =  $payslip->id;
        $parser_data["PAY_PERIOD"] = convert_date_format($payslip->start_date, 'F j-')
            .convert_date_format($payslip->end_date, 'j, Y'); 

        $parser_data["FIRST_NAME"] = $payslip->first_name;
        $parser_data["LAST_NAME"] = $payslip->last_name;
        
        $parser_data["REMARKS"] = $remarks?"Remarks: ".$remarks:"";
        $parser_data["SIGNATURE"] = $email_template->signature;

        $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
        $email_options = array(
            "attachments" => $attachments
        );

        if( filter_var( $payslip->officer_email, FILTER_VALIDATE_EMAIL) ) {
            $email_options['cc'] = $payslip->officer_email;
        }

        if( filter_var( get_setting("payroll_reply_to", FILTER_VALIDATE_EMAIL)) ) {
            $email_options['reply_to'] = get_setting("payroll_reply_to"); //TODO
        }

        if ( $success = send_app_mail($payslip->employee_email, $email_template->subject, $message, $email_options) ) {
            save_payslip_mail($payslip->id, array_merge($payslip, $email_options), $remarks);
            echo json_encode(array("success" => true, 'message' => lang('email_sent').$payslip->employee_email, "parser_data"=>$parser_data, "email_options"=>$email_options));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }

        //delete attachments
        if ($files_data) {
            $files = unserialize($files_data);
            foreach ($files as $file) {
                delete_app_files($target_path, array($file));
            }
        }
    }

    function send_payslip_modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $payslip_id = $this->input->post('id');

        $model_info = $this->Payslip_sents_model->get_details(array(
            "payslip_id" => $payslip_id
        ))->result();

        $sents_objects = array();
        foreach($model_info as $item) {
            $item->object = unserialize($item->serialized);
            $sents_objects[] = $item;
        }

        $view_data['payslip_id'] = $payslip_id;
        $view_data['sents'] = $sents_objects;

        $this->load->view('payrolls/send_payslip_modal_form', $view_data);
    }
}
