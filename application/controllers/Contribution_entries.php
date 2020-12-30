<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contribution_entries extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Contribution_entries_model");
        $this->load->model("Contribution_categories_model");
    }

    protected function _get_category_dropdown_data() {
        $Contribution_categories = $this->Contribution_categories_model->get_all()->result();
        $category_dropdown = array('' => '-');

        foreach ($Contribution_categories as $group) {
            $category_dropdown[$group->id] = $group->title;
        }
        return $category_dropdown;
    }

    function index(){
        $this->template->rander("contribution_entries/index");
    }

    function list_data(){
        $list_data = $this->Contribution_entries_model->get_details(array(
            'start' => $this->input->post('start_date'),
            'end' => $this->input->post('end_date'),
            'category' => $this->input->post('category_select2_filter'),
            'employee' => $this->input->post('users_select2_filter'),
        ))->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            get_team_member_profile_link($data->created_by, $data->employee_name, array("target" => "_blank")),
            $data->category_name,
            number_with_decimal($data->amount),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            modal_anchor(get_uri("contribution_entries/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_category'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_category'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("contribution_entries/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $contribution_data = array(
            "employee" => $this->input->post('employee'),
            "amount" => $this->input->post('amount'),
            "category" => $this->input->post('category'),
        );

        if(!$id){
            $contribution_data["created_on"] = date('Y-m-d H:i:s');
            $contribution_data["created_by"] = $this->login_user->id;
        }

        $contribution_id = $this->Contribution_entries_model->save($contribution_data, $id);
        if ($contribution_id) {
            $options = array("id" => $contribution_id);
            $contribution_info = $this->Contribution_entries_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $contribution_info->id, "data" => $this->_make_row($contribution_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Contribution_entries_model->get_one($this->input->post('id'));
        $view_data['category_dropdown'] = $this->_get_category_dropdown_data();
        $view_data['user_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));

        $this->load->view('contribution_entries/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Contribution_entries_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
