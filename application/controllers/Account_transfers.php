<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account_transfers extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Account_transfers_model");
        $this->load->model("Accounts_model");
        $this->load->model("Account_transactions_model");
    }

    protected function _get_accounts_dropdown_data() {
        $accounts = $this->Accounts_model->get_all()->result();
        $accounts_dropdown = array('' => '-');

        foreach ($accounts as $group) {
            $accounts_dropdown[$group->id] = $group->name;
        }
        return $accounts_dropdown;
    }

    function index(){
        $this->template->rander("account_transfers/index");
    }

    function list_data(){
        $list_data = $this->Account_transfers_model->get_details(array(
            'start' => $this->input->post('start_date'),
            'end' => $this->input->post('end_date'),
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            $data->date,
            $data->transferee_name,
            $data->recipient_name,
            number_with_decimal($data->amount),
            nl2br($data->note),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            modal_anchor(get_uri("account_transfers/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_account_transfer'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("account_transfers/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');
        $account_from = $this->input->post('account_from');
        $account_to = $this->input->post('account_to');
        $amount = $this->input->post('amount');

        $data = array(
            "date" => $this->input->post('date'),
            "account_from" => $account_from,
            "account_to" => $account_to,
            "amount" => $amount,
            "note" => $this->input->post('note')
        );

        if(!$id){
            $data["created_on"] = date('Y-m-d H:i:s');
            $data["created_by"] = $this->login_user->id;
        }

        $saved_id = $this->Account_transfers_model->save($data, $id);

        if($id){
            $data = array(
                'account_id' => $account_from,
                'amount' => $amount,
                'reference' => $id,
            );

            $this->Account_transactions_model->update_transfer(2, $id, $data);

            $data = array(
                'account_id' => $account_to,
                'amount' => $amount,
                'reference' => $id,
            );

            $this->Account_transactions_model->update_transfer(1, $id, $data);
        }
        else{
            $this->Account_transactions_model->add_transfer($account_from, $amount, $saved_id, 2);
            $this->Account_transactions_model->add_transfer($account_to, $amount, $saved_id, 1);
        }

        if ($saved_id) {
            $options = array("id" => $saved_id);
            $model_info = $this->Account_transfers_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $model_info->id, "data" => $this->_make_row($model_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Account_transfers_model->get_one($this->input->post('id'));
        $view_data['accounts_dropdown'] = $this->_get_accounts_dropdown_data();

        $this->load->view('account_transfers/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Account_transfers_model->delete($id)) {
            $this->Account_transactions_model->delete_transfer($id);
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
