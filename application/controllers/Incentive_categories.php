<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Incentive_categories extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Incentive_categories_model");
    }

    function index(){
        $this->template->rander("incentive_categories/index");
    }

    function list_data(){
        $list_data = $this->Incentive_categories_model->get_details()->result();
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
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(get_uri("incentive_categories/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_category'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_incentive'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("incentive_categories/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $incentive_data = array(
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description'),
        );

        if(!$id){
            $incentive_data["created_on"] = date('Y-m-d H:i:s');
            $incentive_data["created_by"] = $this->login_user->id;
        }

        $incentive_id = $this->Incentive_categories_model->save($incentive_data, $id);
        if ($incentive_id) {
            $options = array("id" => $incentive_id);
            $incentive_info = $this->Incentive_categories_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $incentive_info->id, "data" => $this->_make_row($incentive_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Incentive_categories_model->get_one($this->input->post('id'));

        $this->load->view('incentive_categories/modal_form', $view_data);
    }
}
