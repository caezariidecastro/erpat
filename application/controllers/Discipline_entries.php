<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Discipline_entries extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Discipline_entries_model");
        $this->load->model("Discipline_categories_model");
    }

    protected function _get_category_dropdown_data() {
        $Discipline_categories = $this->Discipline_categories_model->get_all()->result();
        $category_dropdown = array('' => '-');

        foreach ($Discipline_categories as $group) {
            $category_dropdown[$group->id] = $group->title;
        }
        return $category_dropdown;
    }

    function index(){
        $this->template->rander("discipline_entries/index");
    }

    function list_data(){
        $list_data = $this->Discipline_entries_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            $data->date_occurred,
            $data->category_name,
            get_team_member_profile_link($data->created_by, $data->employee_name, array("target" => "_blank")),
            get_team_member_profile_link($data->created_by, $data->witness_name, array("target" => "_blank")),
            $data->description,
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            modal_anchor(get_uri("discipline_entries/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_entry'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_entry'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("discipline_entries/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $discipline_data = array(
            "date_occurred" => $this->input->post('date_occurred'),
            "employee" => $this->input->post('employee'),
            "description" => $this->input->post('description'),
            "witness" => $this->input->post('witness'),
            "category" => $this->input->post('category'),
        );

        if(!$id){
            $discipline_data["created_on"] = date('Y-m-d H:i:s');
            $discipline_data["created_by"] = $this->login_user->id;
        }

        $discipline_id = $this->Discipline_entries_model->save($discipline_data, $id);
        if ($discipline_id) {
            $options = array("id" => $discipline_id);
            $discipline_info = $this->Discipline_entries_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $discipline_info->id, "data" => $this->_make_row($discipline_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Discipline_entries_model->get_one($this->input->post('id'));
        $view_data['category_dropdown'] = $this->_get_category_dropdown_data();
        $view_data['user_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));

        $this->load->view('discipline_entries/modal_form', $view_data);
    }
}
