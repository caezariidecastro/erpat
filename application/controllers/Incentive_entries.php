<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Incentive_entries extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Incentive_entries_model");
        $this->load->model("Incentive_categories_model");
        $this->load->model("Accounts_model");
        $this->load->model("Expenses_model");
        $this->load->model("Account_transactions_model");
    }

    protected function _get_category_dropdown_data() {
        $Incentive_categories = $this->Incentive_categories_model->get_all()->result();
        $category_dropdown = array('' => '-');

        foreach ($Incentive_categories as $group) {
            $category_dropdown[$group->id] = $group->title;
        }
        return $category_dropdown;
    }

    function index(){
        $this->template->rander("incentive_entries/index");
    }

    function list_data(){
        $list_data = $this->Incentive_entries_model->get_details(array(
            'category' => $this->input->post('category_select2_filter'),
            'start' => $this->input->post('start_date'),
            'end' => $this->input->post('end_date'),
            'user' => $this->input->post('users_select2_filter'),
            'account_id' => $this->input->post('account_select2_filter'),
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            $data->category_name,
            $data->account_name,
            get_team_member_profile_link($data->created_by, $data->employee_name, array("target" => "_blank")),
            get_team_member_profile_link($data->created_by, $data->signed_by_name, array("target" => "_blank")),
            number_with_decimal($data->amount),
            nl2br($data->remarks),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            modal_anchor(get_uri("incentive_entries/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_entry'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_entry'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("incentive_entries/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $expense_id = $this->input->post('expense_id');
        $account_id = $this->input->post('account_id');
        $amount = $this->input->post('amount');
        $user = $this->input->post('user');

        $incentive_data = array(
            "user" => $user,
            "account_id" => $account_id,
            "remarks" => $this->input->post('remarks'),
            "signed_by" => $this->input->post('signed_by'),
            "category" => $this->input->post('category'),
        );

        if(!$id){
            $incentive_data["created_on"] = date('Y-m-d H:i:s');
            $incentive_data["created_by"] = $this->login_user->id;
        }

        $saved_id = $this->Incentive_entries_model->save($incentive_data, $id);

        $incentive_data["expense_id"] = $this->save_expense($account_id, $amount, $user, $expense_id);

        $incentive_id = $this->Incentive_entries_model->save($incentive_data, $saved_id);
        if ($incentive_id) {
            $options = array("id" => $incentive_id);
            $incentive_info = $this->Incentive_entries_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $incentive_info->id, "data" => $this->_make_row($incentive_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    private function save_expense($account_id, $amount, $user, $expense_id){
        $expense_data = array(
            "account_id" => $account_id,
            "category_id" => 4,
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
            $this->Account_transactions_model->add_incentive($account_id, $amount, $saved_id); 
        }
        else{
            $this->Account_transactions_model->update_incentive($expense_id, $transaction_data); 
        }        
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $view_data['model_info'] = $id ? $this->Incentive_entries_model->get_details(array("id" => $id))->row() : "";
        $view_data['category_dropdown'] = $this->_get_category_dropdown_data();
        $view_data['user_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));
        $view_data['account_dropdown'] = array("" => "-") + $this->Accounts_model->get_dropdown_list(array("name"), "id", array("deleted" => 0));

        $this->load->view('incentive_entries/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        $options = array("id" => $id);
        $incentive_info = $this->Incentive_entries_model->get_details($options)->row();

        if ($this->Incentive_entries_model->delete($id)) {
            $this->Expenses_model->delete($incentive_info->expense_id);
            $this->Account_transactions_model->delete_incentive($incentive_info->expense_id);
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
