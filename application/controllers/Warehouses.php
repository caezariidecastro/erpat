<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Warehouses extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Warehouse_model");
    }

    function index(){
        $this->validate_user_sub_module_permission("module_lds");
        $this->template->rander("warehouse/index");
    }

    function list_data(){
        $list_data = $this->Warehouse_model->get_details(array(
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
            $data->name,
            $data->address,
            $data->zip_code,
            $data->email,
            get_team_member_profile_link($data->head, $data->head_name, array("target" => "_blank")),
            get_team_member_profile_link($data->created_by, $data->creator_name, array("target" => "_blank")),
            $data->created_on,
            modal_anchor(get_uri("lds/warehouses/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_warehouse'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("lds/warehouses/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $data = array(
            "name" => $this->input->post('name'),
            "address" => $this->input->post('address'),
            "zip_code" => $this->input->post('zip_code'),
            "email" => $this->input->post('email'),
            "head" => $this->input->post('head')
        );

        if(!$id){
            $data["created_on"] = date('Y-m-d H:i:s');
            $data["created_by"] = $this->login_user->id;
        }
        
        $saved_id = $this->Warehouse_model->save($data, $id);
        if ($saved_id) {
            $options = array("id" => $saved_id);
            $model_info = $this->Warehouse_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $model_info->id, "data" => $this->_make_row($model_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Warehouse_model->get_one($this->input->post('id'));
        $view_data['user_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("deleted" => 0, "user_type" => "staff"));

        $this->load->view('warehouse/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Warehouse_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
