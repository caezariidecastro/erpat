<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payroll extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Payroll_model");
        $this->load->model("Expenses_model");
        $this->load->model("Account_transactions_model");
        $this->load->model("Accounts_model");
        $this->load->model("Expense_categories_model");
        $this->load->model("Payment_methods_model");
    }

    protected function _get_users_select2_data() {
        $users = $this->Users_model->get_team_members_for_select2()->result();
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
        $view_data['user_select2'] = $this->_get_users_select2_data();
        $this->template->rander("payroll/index", $view_data);
    }

    function list_data(){
        $list_data = $this->Payroll_model->get_details(array(
            'start' => $this->input->post('start_date'),
            'end' => $this->input->post('end_date'),
            'employee_id' => $this->input->post('users_select2_filter'),
            'account_id' => $this->input->post('account_select2_filter'),
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function get_labeled_status($status){
        $labeled_status = "";

        if($status == "not paid"){
            $labeled_status = "<span class='label label-default'>".(ucwords($status))."</span>";
        }

        if($status == "paid"){
            $labeled_status = "<span class='label label-success'>".(ucwords($status))."</span>";
        }

        if($status == "cancelled"){
            $labeled_status = "<span class='label label-danger'>".(ucwords($status))."</span>";
        }

        return $labeled_status;
    }

    private function _make_row($data) {
        $status = $this->get_labeled_status($data->status);

        $edit = '<li role="presentation">' . modal_anchor(get_uri("fas/payroll/modal_form"), "<i class='fa fa-pencil'></i> " . lang('edit'), array("title" => lang('edit'), "data-post-view" => "details", "data-post-id" => $data->id)) . '</li>';
        $delete = "";
        $pay = "";
        $cancel = "";
        $pdf = "";

        if($data->status == "not paid"){
            $pay = '<li role="presentation">'. js_anchor("<i class='fa fa-check'></i> " . lang('mark_as_paid'), array('title' => lang('update'), "class" => "", "data-action-url" => get_uri("fas/payroll/pay/$data->id"), "data-action" => "update")) .'</li>';
            $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("fas/payroll/delete"), "data-action" => "delete-confirmation")) . '</li>';
        }

        if($data->status == "paid"){
            $pdf = "<li role='presentation'>" . anchor(get_uri("fas/payroll/pdf/".$data->id), "<i class='fa fa-file-pdf-o'></i> " . lang('view_pdf'), array("title" => lang('view_pdf'), "target" => "_blank")) . "</li>";
            $cancel = '<li role="presentation">'. js_anchor("<i class='fa fa-remove'></i> " . lang('mark_as_cancelled'), array('title' => lang('update'), "data-action-url" => get_uri("fas/payroll/cancel/$data->id"), "data-action" => "update"));
        }

        if($data->status == "cancelled"){
            $pay = '<li role="presentation">'. js_anchor("<i class='fa fa-check'></i> " . lang('mark_as_paid'), array('title' => lang('update'), "class" => "", "data-action-url" => get_uri("fas/payroll/pay/$data->id"), "data-action" => "update")) .'</li>';
        }

        $actions = '<span class="dropdown inline-block">
                        <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-cogs"></i>&nbsp;
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">' . $pay . $pdf . $edit . $cancel . $delete . '</ul>
                    </span>';

        return array(
            get_team_member_profile_link($data->employee_id, $data->employee_name, array("target" => "_blank")),
            $data->account_name,
            $data->payment_method,
            number_with_decimal($data->amount),
            $status,
            nl2br($data->note),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $actions
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $account_id = $this->input->post('account_id');
        $expense_id = $this->input->post('expense_id');
        $amount = $this->input->post('amount');
        $employee_id = $this->input->post('employee_id');

        $payroll_data = array(
            "employee_id" => $employee_id,
            "account_id" => $account_id,
            "payment_method_id" => $this->input->post('payment_method_id'),
            "note" => $this->input->post('note'),
        );

        if(!$id){
            $payroll_data["created_on"] = date('Y-m-d H:i:s');
            $payroll_data["created_by"] = $this->login_user->id;
        }

        $saved_id = $this->Payroll_model->save($payroll_data, $id);

        $payroll_data["expense_id"] = $this->save_expense($account_id, $amount, $employee_id, $expense_id);

        $payroll_id = $this->Payroll_model->save($payroll_data, $saved_id);
        if ($payroll_id) {
            $options = array("id" => $payroll_id);
            $payroll_info = $this->Payroll_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $payroll_info->id, "data" => $this->_make_row($payroll_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    private function save_expense($account_id, $amount, $user, $expense_id){
        $expense_category_info = $this->Expense_categories_model->get_details_by_title("Payroll")->row();

        $expense_data = array(
            "account_id" => $account_id,
            "category_id" => $expense_category_info->id,
            "amount" => $amount,
            "user_id" => $user,
        );

        if(!$expense_id){
            $expense_data["expense_date"] = date('Y-m-d');
        }

        $saved_id = $this->Expenses_model->save($expense_data, $expense_id);

        $this->save_expense_transaction($account_id, $amount, $expense_id, $saved_id);

        return $saved_id;
    }

    private function save_expense_transaction($account_id, $amount, $expense_id, $saved_id){
        $transaction_data = array(
            'account_id' => $account_id,
            'amount' => $amount,
            'reference' => $expense_id
        );

        if(!$expense_id){
            $this->Account_transactions_model->add_payroll($account_id, $amount, $saved_id); 
        }
        else{
            $this->Account_transactions_model->update_payroll($expense_id, $transaction_data); 
        }        
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $view_data['model_info'] = $id ? $this->Payroll_model->get_details(array("id" => $id))->row() : "";
        $view_data['user_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
        $view_data['payment_methods_dropdown'] = array("" => "-") + $this->Payment_methods_model->get_dropdown_list(array("title"), "id", array("available_on_payroll" => 1, "deleted" => 0));
        $view_data['account_dropdown'] = array("" => "-") + $this->Accounts_model->get_dropdown_list(array("name"), "id", array("deleted" => 0));

        $this->load->view('payroll/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        $options = array("id" => $id);
        $payroll_info = $this->Payroll_model->get_details($options)->row();

        if ($this->Payroll_model->delete($id)) {
            $this->Expenses_model->delete($payroll_info->expense_id);
            $this->Account_transactions_model->delete_payroll($payroll_info->expense_id);
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $payroll_info = $this->Payroll_model->get_details($options)->row();
        return $this->_make_row($payroll_info);
    }

    function pay($payroll_id = 0) {
        if ($payroll_id) {
            $payroll_data["status"] = "paid";

            $save_id = $this->Payroll_model->save($payroll_data, $payroll_id);

            $options = array("id" => $payroll_id);
            $payroll_info = $this->Payroll_model->get_details($options)->row();

            $transaction_data = array(
                'deleted' => '0'
            );

            $this->Account_transactions_model->update_payroll($payroll_info->expense_id, $transaction_data);
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

            $save_id = $this->Payroll_model->save($payroll_data, $payroll_id);

            $options = array("id" => $payroll_id);
            $payroll_info = $this->Payroll_model->get_details($options)->row();

            $transaction_data = array(
                'deleted' => '1'
            );

            $this->Account_transactions_model->update_payroll($payroll_info->expense_id, $transaction_data);
            if ($save_id) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($payroll_id), "id" => $payroll_id, "message" => lang("record_saved")));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        }
    }

    function pdf($id = 0) {
        if ($id) {
            $view_data["payroll_info"] = $this->Payroll_model->get_details(array("id" => $id))->row();
            prepare_payroll_pdf($view_data);
        } else {
            show_404();
        }
    }
}
