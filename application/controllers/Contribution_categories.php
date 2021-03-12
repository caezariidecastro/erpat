<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contribution_categories extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Contribution_categories_model");
        $this->load->model("Accounts_model");
    }

    protected function _get_category_select2_data() {
        $Contribution_categories = $this->Contribution_categories_model->get_all()->result();
        $category_select2 = array(array('id' => '', 'text'  => '- Categories -'));

        foreach ($Contribution_categories as $group) {
            $category_select2[] = array('id' => $group->id, 'text' => $group->title) ;
        }
        return $category_select2;
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
        $this->validate_user_sub_module_permission("module_fas");
        $view_data['category_select2'] = $this->_get_category_select2_data();
        $view_data['account_select2'] = $this->_get_account_select2_data();
        $view_data['user_select2'] = $this->_get_users_select2_data();
        $this->template->rander("contribution_categories/index", $view_data);
    }

    function list_data(){
        $list_data = $this->Contribution_categories_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            $data->title,
            nl2br($data->description),
            $data->mode_of_payment,
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(get_uri("contribution_categories/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_category'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_contribution'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("contribution_categories/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $contribution_data = array(
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
            "mode_of_payment" => $this->input->post('mode_of_payment'),
        );

        if(!$id){
            $contribution_data["created_on"] = date('Y-m-d H:i:s');
            $contribution_data["created_by"] = $this->login_user->id;
        }

        $contribution_id = $this->Contribution_categories_model->save($contribution_data, $id);
        if ($contribution_id) {
            $options = array("id" => $contribution_id);
            $contribution_info = $this->Contribution_categories_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $contribution_info->id, "data" => $this->_make_row($contribution_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Contribution_categories_model->get_one($this->input->post('id'));

        $this->load->view('contribution_categories/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Contribution_categories_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
