<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accounts extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Accounts_model");
        $this->load->model("Account_transactions_model");
        $this->load->model("Expenses_model");
        $this->load->model("Invoice_payments_model");
    }

    function index(){
        $this->template->rander("accounts/index");
    }

    function list_data(){
        $list_data = $this->Accounts_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            $data->name,
            $data->number,
            nl2br($data->remarks),
            number_with_decimal($data->initial_balance),
            number_with_decimal($data->current_balance),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(get_uri("accounts/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_account'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("accounts/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $amount = $this->input->post('initial_balance');

        $account_data = array(
            "name" => $this->input->post('account_name'),
            "number" => $this->input->post('account_number'),
            "initial_balance" => $amount,
            "remarks" => $this->input->post('remarks'),
        );

        if(!$id){
            $account_data["created_on"] = date('Y-m-d H:i:s');
            $account_data["created_by"] = $this->login_user->id;
        }

        $account_id = $this->Accounts_model->save($account_data, $id);

        if($id){
            $data = array(
                'account_id' => $account_id,
                'amount' => $amount,
                'reference' => $id
            );

            $this->Account_transactions_model->update_initial_balance($id, $data);
        }
        else{
            $amount = $amount ? $amount : 0;
            $this->Account_transactions_model->add_initial_balance($account_id, $amount, $account_id);
        }

        if ($account_id) {
            $options = array("id" => $account_id);
            $account_info = $this->Accounts_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $account_info->id, "data" => $this->_make_row($account_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Accounts_model->get_one($this->input->post('id'));

        $this->load->view('accounts/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if($this->Expenses_model->is_account_has_expense($id) || $this->Invoice_payments_model->is_account_has_payment($id)){
            echo json_encode(array("success" => false, 'message' => lang('account_has_payment_expense_delete_error')));
        }
        else{
            if ($this->Accounts_model->delete($id)) {
                $this->Account_transactions_model->update_initial_balance($id, array('amount' => 0));
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }
}
