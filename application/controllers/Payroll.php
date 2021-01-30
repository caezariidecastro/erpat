<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payroll extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Payroll_model");
        $this->load->model("Accounts_model");
        $this->load->model("Expenses_model");
        $this->load->model("Account_transactions_model");
    }

    function index(){
        $this->template->rander("payroll/index");
    }

    function list_data(){
        $list_data = $this->Payroll_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $status = "<span class='label label-".($data->expense_id ? "success" : "danger")."'>".($data->expense_id ? "Paid" : "Not paid")."</span>";

        $pay = !$data->expense_id ? '<li role="presentation">'. js_anchor("<i class='fa fa-check'></i> " . lang('mark_as_paid'), array('title' => lang('update'), "class" => "", "data-action-url" => get_uri("payroll/pay/$data->id"), "data-action" => "update")) .'</li>' : "";
        $edit = '<li role="presentation">' . modal_anchor(get_uri("payroll/modal_form"), "<i class='fa fa-pencil'></i> " . lang('edit'), array("title" => lang('edit'), "data-post-view" => "details", "data-post-id" => $data->id)) . '</li>';
        $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times fa-fw'></i>" . lang('delete'), array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("payroll/delete"), "data-action" => "delete-confirmation")) . '</li>';

        $actions = '<span class="dropdown inline-block">
                        <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-cogs"></i>&nbsp;
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">' . $pay . $edit . $delete . '</ul>
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

        $payroll_data = array(
            "employee_id" => $this->input->post('employee_id'),
            "account_id" => $this->input->post('account_id'),
            "payment_method_id" => $this->input->post('payment_method_id'),
            "amount" => $this->input->post('amount'),
            "note" => $this->input->post('note'),
        );

        if(!$id){
            $payroll_data["created_on"] = date('Y-m-d H:i:s');
            $payroll_data["created_by"] = $this->login_user->id;
        }

        $payroll_id = $this->Payroll_model->save($payroll_data, $id);

        if ($payroll_id) {
            $options = array("id" => $payroll_id);
            $payroll_info = $this->Payroll_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $payroll_info->id, "data" => $this->_make_row($payroll_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $model_info = $this->Payroll_model->get_one($this->input->post('id'));

        $view_data['model_info'] = $model_info;
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
            $this->Account_transactions_model->delete_expense($payroll_info->expense_id);
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

            $payroll = $this->Payroll_model->get_one($payroll_id);

            $expense_data = array(
                "expense_date" => date('Y-m-d'),
                "account_id" => $payroll->account_id,
                "category_id" => 1,
                "amount" => $payroll->amount,
                "user_id" => $payroll->employee_id,
            );

            $expense_id = $this->Expenses_model->save($expense_data);
            $this->Account_transactions_model->add_expense($payroll->account_id, $payroll->amount, $expense_id);

            $payroll_data = array(
                'expense_id' => $expense_id
            );

            $save_id = $this->Payroll_model->save($payroll_data, $payroll_id);
            if ($save_id) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($payroll_id), "id" => $payroll_id, "message" => lang("record_saved")));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        }
    }
}
