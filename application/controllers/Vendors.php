<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendors extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Vendors_model");
    }

    function index(){
        $this->template->rander("vendors/index");
    }

    function list_data(){
        $list_data = $this->Vendors_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            $data->name,
            $data->contact,
            $data->email,
            nl2br($data->address),
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(get_uri("vendors/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_vendor'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("vendors/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $vendor_data = array(
            "name" => $this->input->post('name'),
            "contact" => $this->input->post('contact'),
            "address" => $this->input->post('address'),
            "email" => $this->input->post('email'),
        );

        if(!$id){
            $vendor_data["created_on"] = date('Y-m-d H:i:s');
            $vendor_data["created_by"] = $this->login_user->id;
        }

        $vendor_id = $this->Vendors_model->save($vendor_data, $id);

        if ($vendor_id) {
            $options = array("id" => $vendor_id);
            $vendor_info = $this->Vendors_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $vendor_info->id, "data" => $this->_make_row($vendor_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Vendors_model->get_one($this->input->post('id'));

        $this->load->view('vendors/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->Vendors_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
