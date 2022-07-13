<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payrolls extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('payhp');
        $this->load->helper("biometric");

        $this->load->model("Payrolls_model");
        $this->load->model("Payslips_model");
        $this->load->model("Leave_credits_model");
        $this->load->model("Accounts_model");
        $this->load->model("Attendance_model");
        $this->load->model("Payment_methods_model");

        $this->load->model("Expenses_model");
        $this->load->model("Expense_categories_model");
    }

    protected function _get_team_select2_data() {
        $teams = $this->Team_model->get_details()->result();
        $team_select2 = array(array('id' => '', 'text'  => '- Departments -'));

        foreach($teams as $team){
            $team_select2[] = array('id' => $team->id, 'text'  => $team->title);
        }

        return $team_select2;
    }

    protected function _get_users_select2_data() {
        $users = $this->Users_model->get_team_members_for_select2(
            array("deleted" => 0, "user_type" => "staff")
        )->result();
        $user_select2 = array(array('id' => '', 'text'  => '- Users -'));

        foreach($users as $user){
            $user_select2[] = array('id' => $user->id, 'text'  => $user->user_name);
        }

        return $user_select2;
    }

    protected function _get_account_select2_data() {
        $Accounts = $this->Accounts_model->get_all()->result();
        $account_select2 = array(array('id' => '', 'text'  => '- Accounts -'));

        foreach ($Accounts as $account) {
            $account_select2[] = array('id' => $account->id, 'text' => $account->name) ;
        }
        return $account_select2;
    }

    function index(){
        $this->validate_user_module_permission("module_fas");
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

        $edit = "";
        $delete = "";
        $pay = "";
        $generate = "";
        $cancel = "";

        if($data->status == "draft"){
            $edit = '<li role="presentation">' . modal_anchor(get_uri("fas/payrolls/modal_form"), "<i class='fa fa-pencil'></i> " . lang('edit'), array("title" => lang('edit'), "data-post-view" => "details", "data-post-id" => $data->id)) . '</li>';
            $generate = '<li role="presentation">'. js_anchor("<i class='fa fa-wrench'></i> " . lang('mark_as_ongoing'), array('title' => lang('update'), "data-action-url" => get_uri("fas/payrolls/mark_as_ongoing/$data->id"), "data-action" => "update", "data-action" => "update", "data-reload-on-success" => "1"));
            $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("fas/payrolls/delete"), "data-action" => "delete-confirmation")) . '</li>';
        }

        if($data->status == "cancelled"){
            $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("fas/payrolls/delete"), "data-action" => "delete-confirmation")) . '</li>';
        }

        if($data->status == "ongoing") {
            $pay =  '<li role="presentation">' . modal_anchor(get_uri("payrolls/lock_payment/".$data->id), "<i class='fa fa-money'></i> " . lang('lock_payment'), array("class" => "btn btn-default", "title" => lang('payslip_preview'), "data-post-payroll_id" => $data->id, "data-action" => "update", "data-reload-on-success" => "1")) .'</li>';

            $cancel = '<li role="presentation">' . js_anchor("<i class='fa fa-exclamation fa-fw'></i>" . lang('cancel'), array("class" => "update", "data-action-url" => get_uri("fas/payrolls/mark_as_cancelled/".$data->id), "data-action" => "update", "data-reload-on-success" => "1")) . '</li>';
        }

        if( $data->status == "ongoing" || $data->status == "completed" ){
            $view = "<li role='presentation'>" . anchor(get_uri("fas/payrolls/view/".$data->id), "<i class='fa fa-file-pdf-o'></i> " . lang('view_payroll'), array("title" => lang('view_payroll'), "target" => "_blank")) . "</li>";
            $cancel = '<li role="presentation">' . js_anchor("<i class='fa fa-exclamation fa-fw'></i>" . lang('cancel'), array("class" => "update", "data-action-url" => get_uri("fas/payrolls/mark_as_cancelled/".$data->id), "data-action" => "update", "data-reload-on-success" => "1")) . '</li>';
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
            $data->department_name,
            $data->start_date,
            $data->end_date,
            $data->pay_date,
            $data->account_name,
            $data->total_payslip,
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
            $payroll_data["timestamp"] = get_current_utc_time();
            $payroll_data["signed_by"] = $this->login_user->id;
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

        $view_data['account_dropdown'] = array("" => "-") + $this->Accounts_model->get_dropdown_list(array("name"), "id", array("deleted" => 0));
        $view_data['department_dropdown'] = array("" => "-") + $this->Team_model->get_dropdown_list(array("title"), "id", array("deleted" => 0));
        $view_data['model_info'] = $id ? $this->Payrolls_model->get_details(array("id" => $id))->row() : "";
        $view_data['user_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));

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
        if ($payroll_id) {
            
            $payroll_info = $this->Payrolls_model->get_one( $payroll_id );
            $view_data["payroll_info"] = $payroll_info;
            $view_data["status"] = $this->get_labeled_status( $payroll_info->status );

            //$payroll_info->recurring = true;
            //$payroll_info->no_of_cycles_completed = 6;
            //$payroll_info->no_of_cycles = 12;
            $view_data["can_edit_payrolls"] = ($this->login_user->is_admin || $this->login_user->id == $payroll_info->signed_by)?true:false;

            $department = $this->Team_model->get_one($payroll_info->department);
            $view_data["department"] = $department->title;
            $view_data["start_date"] = $payroll_info->start_date;
            $view_data["end_date"] = $payroll_info->end_date;
            $view_data["pay_date"] = $payroll_info->pay_date;

            $view_data["payroll_info"] = $payroll_info;
            $view_data['user_select2'] = $this->_get_users_select2_data();
            $view_data['department_select2'] = $this->_get_team_select2_data();

            $this->template->rander('payrolls/view', $view_data);
        } else {
            show_404();
        }
    }

    function mark_as_ongoing( $payroll_id = 0 ) {

        //TODO: Generate Payslips
        //Get payroll instance
        $payroll_info = $this->Payrolls_model->get_details(array(
            "id" => $payroll_id
        ))->row();

        //Get all the users within a department id ng payroll 
        $department = $this->Team_model->get_details(array(
            "id" => $payroll_info->department
        ))->row();

        $users = []; //list of users.
        $lists = explode(",", $department->heads.",".$department->members);
        for ($i = 0; $i < count($lists); $i++) {
            if (isset($lists[$i]) && !in_array($lists[$i], $users)) {
                $users[] = $lists[$i];
            }
        }
        
        //Get all the summarized attendance from date focused on in.
        $payslips = [];
        //schedule hours for this date.
        //absent, late, overbreak, undertime
        foreach($users as $user_id) {

            $job_info = $this->Users_model->get_job_info($user_id);

            $attendance = $this->Attendance_model->get_details(array(
                "user_id" => $user_id,
                "start_date" => $payroll_info->start_date,
                "end_date" => $payroll_info->end_date,
                "access_type" => "all",
            ))->result();
            //$payslips[] = $attendance;

            $attd = (new BioMeet($this, array(
              "hours_per_day" => $job_info->hours_per_day
            ), true))->setAttendance($attendance)->calculate();

            $payslips[] = array(
                "payroll" => $payroll_id,
                "user" => $user_id,
                "hourly_rate" => $job_info->rate_per_hour,
                //"leave_credit" => $user_id, //hourly_rate

                "schedule" => $attd->getTotalSchedule(), //schedule
                "worked" => $attd->getTotalWork(), //work
                "absent" => $attd->getTotalAbsent(), //absent
                "lates" => $attd->getTotalLates(), //lates
                "overbreak" => $attd->getTotalOverbreak(), //overbreak
                "undertime" => $attd->getTotalUndertime(), //undertime

                //"sss" => 000, //absent
                //"pagibig" => 000, //lates
                //"overbreak" => 000, //overbreak
                //"undertime" => 000, //undertime

                //"com_loan" => 000, //com_loan
                //"sss_loan" => 000, //sss_loan
                //"hdmf_loan" => 000, //hdmf_loan
            );
        }

        //To create a payslip for that user in a list.
        foreach($payslips as $payslip) {
            $this->Payslips_model->save( $payslip );
        }

        $payroll_data["status"] = "ongoing";
        if ($this->Payrolls_model->save($payroll_data, $payroll_id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
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
            echo json_encode(array(
                "success" => true, 
                'message' => lang('record_saved'), 
            ));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function mark_as_cancelled( $id ) {

        $data = array(
            "status" => 'cancelled',
        );

        if ($this->Payrolls_model->save($data, $id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
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

    protected function prepare_payslip_pdf( $data ) {
        $this->load->library('pdf');
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetCellPadding(1);
        $this->pdf->setImageScale(2.0);
        $this->pdf->AddPage();
        $this->pdf->SetFontSize(9);

        if( $data ) {
            $html = $this->load->view("payrolls/preview", $data, true);
            $this->pdf->writeHTML($html, true, false, true, false, '');

            $fullname = get_array_value($data, "fullname");
            $file_name =  str_replace(" ", "-", $fullname).".pdf";

            $this->pdf->Output($file_name, "I");
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
        
        $list_data = $this->Payslips_model->get_details(array(
            'payroll_id' => $payroll_id,
            'user_id' => $this->input->post('user_select2_filter'),
            'department_id' => $this->input->post('department_select2_filter'),
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payslip_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function get_payslip_status($data){
        $labeled_status = "";

        if($data->cancelled_by){
            $labeled_status = "<span class='label label-danger'>CANCELLED</span>";
        } else if($data->signed_by && !$data->cancelled_by){
            $labeled_status = "<span class='label label-success'>APPROVED</span>";
        } else {
            $labeled_status = "<span class='label label-default'>DRAFT</span>";
        }

        return $labeled_status;
    }

    function _make_payslip_row( $payslip ) {

        $data = $this->Payslips_model->get_details(array(
            "id" => $payslip->id
        ))->row();

        $summary = $this->processPayHP( $data )->calculate();

        $view = ""; //todo
        $pdf = ""; //todo

        $cancel = "";
        $signe = "";
        $override = "";
        $delete = "";
        
        if($data->signed_by && !$data->cancelled_by) {
            $view = "<li role='presentation'>".modal_anchor(get_uri("payrolls/preview/".$data->id), "<i class='fa fa-eye'></i> " . lang('view_pdf'), array("class" => "btn btn-default", "title" => lang('lock_payment'), "data-post-payroll_id" => $data->id))."</i>";
            $pdf = "<li role='presentation'>" . anchor(get_uri("fas/payrolls/download_pdf/".$data->id), "<i class='fa fa-download'></i>  &nbsp" . lang('download_pdf'), array("title" => lang('view_pdf'), "target" => "_blank")) . "</li>";

            $cancel = '<li role="presentation">' . js_anchor("<i class='fa fa-exclamation fa-fw'></i>" . lang('cancel'), array("class" => "update", "data-action-url" => get_uri("fas/payrolls/cancel_payslip/".$data->id), "data-action" => "update", "data-reload-on-success" => "1")) . '</li>';
        } else if(!$data->signed_by && !$data->cancelled_by) {
            $signe = '<li role="presentation">' . js_anchor("<i class='fa fa-paw fa-fw'></i>" . lang('approve'), array("class" => "update", "data-action-url" => get_uri("fas/payrolls/approve_payslip/".$data->id), "data-action" => "update", "data-reload-on-success" => "1")) . '</li>';

            $override = "<li role='presentation'><a href='#' id='$data->id' name='override' class='override_btn role-row link'><span class='fa fa-check'></span>   &nbsp" . lang('override') . "</a></i>";
        }
        
        $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => "  &nbsp".lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("fas/payrolls/delete_payslip"), "data-action" => "delete-confirmation")) . '</li>';

        $actions = '<style> 
                    .dropdown-menu li a { width: 10px; padding: 3px 7px; } 
                    .dropdown-menu li { margin-bottom: 5px; } 
                    .dropdown-menu li:hover { background-color: #d9d9d9; } 
                    </style>
                    <span class="dropdown inline-block">
                        <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-cogs"></i>&nbsp;
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">' . $view . $pdf . $override . $signe . $cancel . $delete . '</ul>
                    </span>';

        return array(
            anchor(get_uri("payrolls/preview/" . $data->id), get_payslip_id($data->id, $data->payroll)), //payslip_id
            get_team_member_profile_link($data->user, $data->employee_name, array("target" => "_blank")), //user link

            $data->department_name, //department

            $data->sched_hour, //schedule_hour
            $data->work_hour, //work_hour
            $data->idle_hour, //absent,lates,under

            to_currency( $summary['basic_pay'] ), 
            to_currency( $summary['gross_pay'] ), 
            to_currency( $summary['net_pay'] ), 
            to_currency( $summary['tax_due'] ),  

            $this->get_payslip_status($data), //net_pay
            $actions
        );
    }

    protected function processPayHP( $data ) {
        return (new PayHP($data->hourly_rate, array(), 0))
            ->addEarnings('allowance', $data->allowance)
            ->addEarnings('incentive', $data->incentive)
            ->addEarnings('bonus', $data->bonus_month)
            ->addEarnings('13thmonth', $data->month13th)
            ->addEarnings('adjust', $data->add_adjust)
            ->addEarnings('other', $data->add_other)

            ->setHour('schedule', $data->schedule)
            ->setHour('absent', $data->absent)
            ->setHour('late', $data->lates)
            ->setHour('overbreak', $data->overbreak)
            ->setHour('undertime', $data->undertime)
            ->setHour('pto', $data->pto)

            ->setOvertime('allowance', $data->allowance)
            ->setOvertime('incentive', $data->incentive)
            ->setOvertime('bonus', $data->bonus_month)
            ->setOvertime('13thmonth', $data->month13th)
            ->setOvertime('adjust', $data->add_adjust)
            ->setOvertime('other', $data->add_other)

            ->setOvertime('regular', $data->reg_ot)
            ->setOvertime('restday', $data->rest_ot)
            ->setOvertime('legalhd', $data->legal_ot)
            ->setOvertime('specialhd', $data->spcl_ot)

            ->setNightdiff('regular', $data->reg_nd)
            ->setNightdiff('restday', $data->rest_nd)
            ->setNightdiff('legalhd', $data->legal_nd)
            ->setNightdiff('specialhd', $data->spcl_nd)

            ->setOtNd('regular', $data->reg_ot_nd)
            ->setOtNd('restday', $data->rest_ot_nd)
            ->setOtNd('legalhd', $data->legal_ot_nd)
            ->setOtNd('specialhd', $data->spcl_ot_nd)

            ->deductContribution('sss', $data->sss)
            ->deductContribution('pagibig', $data->pagibig)
            ->deductContribution('phealth', $data->phealth)
            ->deductContribution('hmo', $data->hmo)

            ->deductLoan('company', $data->com_loan)
            ->deductLoan('pagibig', $data->hdmf_loan)
            ->deductLoan('sss', $data->sss_loan)

            ->deductOther('adjust', $data->deduct_adjust)
            ->deductOther('other', $data->deduct_other)
            ;
    }

    function override( $payslip_id = 0 ) {

        $data = $this->Payslips_model->get_details(array(
            "id" => $payslip_id
        ))->row();

        $view_data["payslip_id"] = $payslip_id;
        $view_data["fullname"] = $data->employee_name;
        $view_data["job_title"] = $data->job_title;
        $view_data["department"] = $data->department_name;
        $view_data["salary"] = to_currency($data->salary_amount);

        $summary = $this->processPayHP( $data )->calculate();

        $view_data["biometrics"] = [
            array(
                "key" => "schedule",
                "value" => $data->schedule
            ),
            array(
                "key" => "paid_timeoff",
                "value" => $data->pto,
                "disabled" => true,
                "class" => "disabled",
            ),
            array(
                "key" => "absent",
                "value" => $data->absent
            ),
            array(
                "key" => "lates",
                "value" => $data->lates
            ),
            array(
                "key" => "overbreak",
                "value" => $data->overbreak
            ),
            array(
                "key" => "undertime",
                "value" => $data->undertime
            ),
        ];

        $view_data["night_diff"] = [
            array(
                "key" => "regular_nd",
                "value" => $data->reg_nd
            ),
            array(
                "key" => "restday_nd",
                "value" => $data->rest_nd
            ),
            array(
                "key" => "legal_hd_nd",
                "value" => $data->legal_nd
            ),
            array(
                "key" => "special_hd_nd",
                "value" => $data->spcl_nd
            )
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
                "key" => "legal_hd_ot",
                "value" => $data->legal_ot
            ),
            array(
                "key" => "special_hd_ot",
                "value" => $data->spcl_ot
            )
        ];

        $view_data["ot_nd"] = [
            array(
                "key" => "regular_ot_nd",
                "value" => $data->reg_ot_nd
            ),
            array(
                "key" => "restday_ot_nd",
                "value" => $data->rest_ot_nd
            ),
            array(
                "key" => "legal_hd_ot_nd",
                "value" => $data->legal_ot_nd
            ),
            array(
                "key" => "special_hd_ot_nd",
                "value" => $data->spcl_ot_nd
            )
        ];

        $view_data["earnings"] = [
            array(
                "key" => "monthly_salary",
                "value" => to_currency($data->salary_amount),
                "disabled" => true,
                "type" => "text",
                "class" => "disabled",
            ),
            array(
                "key" => "basic_pay",
                "value" => to_currency($summary['basic_pay']),
                "disabled" => true,
                "type" => "text",
                "class" => "disabled",
            ),
            array(
                "key" => "hours_paid",
                "value" => to_currency($summary['hours_paid']),
                "disabled" => true,
                "type" => "text",
                "class" => "disabled",
            ),
            array(
                "key" => "hourly_rate",
                "value" => convert_number_to_decimal($data->hourly_rate)
            ),
        ];

        $view_data["additionals"] = [
            array(
                "key" => "allowance",
                "value" => $data->allowance
            ),
            array(
                "key" => "incentive",
                "value" => $data->incentive
            ),
            array(
                "key" => "bonus",
                "value" => $data->bonus_month
            ),
            array(
                "key" => "13th_month",
                "value" => $data->month13th
            )
        ];

        $view_data["earn_other"] = [
            array(
                "key" => "earn_adjust",
                "value" => $data->add_adjust
            ),
            array(
                "key" => "earn_other",
                "value" => $data->add_other
            ),
        ];

        $view_data["non_tax_deducts"] = [
            array(
                "key" => "sss",
                "value" => $data->sss
            ),
            array(
                "key" => "pagibig",
                "value" => $data->pagibig
            ),
            array(
                "key" => "phealth",
                "value" => $data->phealth
            ),
            array(
                "key" => "hmo",
                "value" => $data->hmo
            )
        ];

        $view_data["non_tax_loans"] = [
            array(
                "key" => "com_loan",
                "value" => $data->com_loan
            ),
            array(
                "key" => "hdmf_loan",
                "value" => $data->hdmf_loan
            ),
            array(
                "key" => "sss_loan",
                "value" => $data->sss_loan
            )
        ];

        $view_data["non_tax_other"] = [
            array(
                "key" => "deduct_adjust",
                "value" => $data->deduct_adjust
            ),
            array(
                "key" => "deduct_other",
                "value" => $data->deduct_other
            )
        ];

        $view_data["summary_general"] = [
            array(
                "key" => "overtimePay",
                "value" => to_currency($summary['overtime_pay'])
            ),
            array(
                "key" => "nightdiffPay",
                "value" => to_currency($summary['nightdiff_pay'])
            ),
            array(
                "key" => "specialPay",
                "value" => to_currency($summary['special_pay'])
            ),
            array(
                "key" => "unwork_deductions",
                "value" => to_currency($summary['unwork_deduction'])
            ),
            array(
                "key" => "total_contributions",
                "value" => to_currency($summary['tota_contribution'])
            ),
            array(
                "key" => "total_loans",
                "value" => to_currency($summary['total_loan'])
            ),
        ];

        $view_data["summary_totals"] = [
            array(
                "key" => "net_taxable",
                "value" => to_currency($summary['net_taxable'])
            ),
            array(
                "key" => "compensation_tax",
                "value" => to_currency($summary['tax_due'])
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

        $this->load->view('payrolls/override', $view_data);
    }

    function override_calculate( $payslip_id = 0 ) {

        $action = $this->input->post('action');

        //TODO: Save here
        if($action === "save") {
            $payslip_data = array(
                "hourly_rate" => $this->input->post('hourly_rate'),
                "pto" => $this->input->post('pto'),

                "schedule" => $this->input->post('schedule'),
                "absent" => $this->input->post('absent'),
                "lates" => $this->input->post('lates'),
                "overbreak" => $this->input->post('overbreak'),
                "undertime" => $this->input->post('undertime'),

                "allowance" => $this->input->post('allowance'),
                "incentive" => $this->input->post('incentive'),
                "bonus_month" => $this->input->post('bonus'),
                "month13th" => $this->input->post('13th_month'),

                "add_adjust" => $this->input->post('earn_adjust'),
                "add_other" => $this->input->post('earn_other'),

                "sss" => $this->input->post('sss'),
                "pagibig" => $this->input->post('pagibig'),
                "phealth" => $this->input->post('phealth'),
                "hmo" => $this->input->post('hmo'),

                "com_loan" => $this->input->post('com_loan'),
                "sss_loan" => $this->input->post('sss_loan'),
                "hdmf_loan" => $this->input->post('hdmf_loan'),

                "deduct_adjust" => $this->input->post('deduct_adjust'),
                "deduct_other" => $this->input->post('deduct_other'),

                "reg_nd" => $this->input->post('regular_nd'),
                "rest_nd" => $this->input->post('restday_nd'),
                "legal_nd" => $this->input->post('legal_hd_nd'),
                "spcl_nd" => $this->input->post('special_hd_nd'),

                "reg_ot" => $this->input->post('regular_ot'),
                "rest_ot" => $this->input->post('restday_ot'),
                "legal_ot" => $this->input->post('legal_hd_ot'),
                "spcl_ot" => $this->input->post('special_hd_ot'),

                "reg_ot_nd" => $this->input->post('regular_ot_nd'),
                "rest_ot_nd" => $this->input->post('restday_ot_nd'),
                "legal_ot_nd" => $this->input->post('legal_hd_ot_nd'),
                "spcl_ot_nd" => $this->input->post('special_hd_ot_nd'),
            );
            $this->Payslips_model->save($payslip_data, $payslip_id);
        }
        
        echo json_encode(array("success"=>true));
    }

    function cancel_payslip( $id ) {

        $data = array(
            "cancelled_by" => $this->login_user->id,
            "cancelled_at" => get_current_utc_time(),
        );

        if ($this->Payslips_model->save($data, $id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function approve_payslip( $id ) {

        $data = array(
            "signed_by" => $this->login_user->id,
            "signed_at" => get_current_utc_time(),
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

    function preview( $payslip_id = 0 ) {
        $payslip = $this->Payslips_model->get_details(array(
            "id" => $payslip_id
        ))->row();

        $payroll = $this->Payrolls_model->get_details(array(
            "id" => $payslip->payroll
        ))->row();
        $payslip->pay_date = format_to_custom($payroll->pay_date, "F d, Y");
        $payslip->pay_period= format_to_custom($payroll->start_date, "F d-").format_to_custom($payroll->end_date, "d Y");

        $user = $this->Users_model->get_details(array(
            "id" => $payslip->user
        ))->row();
        $payslip->fullname = $user->first_name." ".$user->last_name;
        $payslip->job_title = $user->job_title;

        $leave_credit = $this->Leave_credits_model->get_balance(array(
            "user_id" => $payslip->user
        ));
        $payslip->leave_credit = $leave_credit['balance'];

        $team = $this->Team_model->get_teams($payslip->user)->row();//todo
        $payslip->department = $team?$team->title:"None";

        $job_info = $this->Users_model->get_job_info($payslip->user);
        $payslip->salary = $job_info->salary;

        $accountant = $this->Users_model->get_details(array(
            "id" => $payslip->accountant_id
        ))->row();
        $payslip->accountant_name = $accountant->first_name." ".$accountant->last_name;

        $view_data["payslip"] = $payslip;
        $view_data["summary"] = $this->processPayHP( $payslip )->calculate();

        $this->load->view('payrolls/preview', $view_data);
    }

    function payslip($id = 0) {
        if ($id) {
            //$view_data['user_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
            //$view_data['payment_methods_dropdown'] = array("" => "-") + $this->Payment_methods_model->get_dropdown_list(array("title"), "id", array("available_on_payroll" => 1, "deleted" => 0));
            //$view_data['account_dropdown'] = array("" => "-") + $this->Accounts_model->get_dropdown_list(array("name"), "id", array("deleted" => 0));

            $view_data["payroll_info"] = $this->Payrolls_model->get_details(array("id" => $id))->row();
            $this->prepare_payslip_pdf($view_data);
        } else {
            show_404();
        }
    }

    function download_pdf($id = 0, $mode = "download") {
        if ($id) {
            $payslip = $this->Payslips_model->get_details(array(
                "id" => $id
            ))->row();
    
            $payroll = $this->Payrolls_model->get_details(array(
                "id" => $payslip->payroll
            ))->row();
            $payslip->pay_date = format_to_custom($payroll->pay_date, "F d, Y");
            $payslip->pay_period= format_to_custom($payroll->start_date, "F d-").format_to_custom($payroll->end_date, "d Y");
    
            $user = $this->Users_model->get_details(array(
                "id" => $payslip->user
            ))->row();
            $payslip->fullname = $user->first_name." ".$user->last_name;
            $payslip->job_title = $user->job_title;
    
            $leave_credit = $this->Leave_credits_model->get_balance(array(
                "user_id" => $payslip->user
            ));
            $payslip->leave_credit = $leave_credit['balance'];
    
            $team = $this->Team_model->get_teams($payslip->user)->row();//todo
            $payslip->department = $team?$team->title:"None";
    
            $job_info = $this->Users_model->get_job_info($payslip->user);
            $payslip->salary = $job_info->salary;
    
            $accountant = $this->Users_model->get_details(array(
                "id" => $payslip->accountant_id
            ))->row();
            $payslip->accountant_name = $accountant->first_name." ".$accountant->last_name;
    
            $view_data["payslip"] = $payslip;
            $view_data["summary"] = $this->processPayHP( $payslip )->calculate();

            $this->prepare_payslip_pdf($view_data);
        } else {
            show_404();
        }
    }
}
