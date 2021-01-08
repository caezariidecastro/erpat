<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Units extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Units_model");
    }

    function index(){
        $this->template->rander("units/index");
    }

    function list_data(){
        $list_data = $this->Units_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            $data->title,
            $data->operator,
            $data->value,
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(get_uri("units/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_unit'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("units/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $unit_data = array(
            "title" => $this->input->post('title'),
            "operator" => $this->input->post('operator'),
            "value" => $this->input->post('value'),
        );

        if(!$id){
            $unit_data["created_on"] = date('Y-m-d H:i:s');
            $unit_data["created_by"] = $this->login_user->id;
        }

        $unit_id = $this->Units_model->save($unit_data, $id);
        if ($unit_id) {
            $options = array("id" => $unit_id);
            $unit_info = $this->Units_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $unit_info->id, "data" => $this->_make_row($unit_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Units_model->get_one($this->input->post('id'));

        $this->load->view('units/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Units_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
