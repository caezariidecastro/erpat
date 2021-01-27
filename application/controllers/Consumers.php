<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Consumers extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Consumers_model");
    }

    function index(){
        $this->template->rander("consumers/index");
    }

    function list_data(){
        $list_data = $this->Consumers_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        return array(
            $data->first_name,
            $data->last_name,
            $data->email,
            $data->contact,
            $data->street,
            $data->city,
            $data->state,
            $data->zip,
            $data->country,
            $data->created_on,
            get_team_member_profile_link($data->created_by, $data->full_name, array("target" => "_blank")),
            modal_anchor(get_uri("consumers/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_consumer'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("consumers/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function save() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->input->post('id');

        $consumer_data = array(
            "first_name" => $this->input->post('first_name'),
            "last_name" => $this->input->post('last_name'),
            "email" => $this->input->post('email'),
            "first_name" => $this->input->post('first_name'),
            "contact" => $this->input->post('contact'),
            "street" => $this->input->post('street'),
            "city" => $this->input->post('city'),
            "state" => $this->input->post('state'),
            "zip" => $this->input->post('zip'),
            "country" => $this->input->post('country')
        );

        if(!$id){
            $consumer_data["created_on"] = date('Y-m-d H:i:s');
            $consumer_data["created_by"] = $this->login_user->id;
        }

        $consumer_id = $this->Consumers_model->save($consumer_data, $id);
        if ($consumer_id) {
            $options = array("id" => $consumer_id);
            $consumer_info = $this->Consumers_model->get_details($options)->row();
            echo json_encode(array("success" => true, "id" => $consumer_info->id, "data" => $this->_make_row($consumer_info), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Consumers_model->get_one($this->input->post('id'));

        $this->load->view('consumers/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Consumers_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function get_consumer() {
        $consumer_info = $this->Consumers_model->get_details(array("id" => $this->input->post('id')))->row();
        if ($consumer_info) {
            echo json_encode(array("success" => true, "consumer_info" => $consumer_info));
        } else {
            echo json_encode(array("success" => false));
        }
    }
}