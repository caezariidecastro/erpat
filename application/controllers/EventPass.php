<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class EventPass extends MY_Controller {

	function __construct() {
       	parent::__construct();
        $this->load->library('encryption');
		$this->load->model("EventPass_model");
        $this->load->model("Users_model");
        $this->load->model("Email_templates_model");
        $this->load->helper('utility');
    }

    function get_customer_select2_data() {
        $customers = $this->Users_model->get_details(array("user_type" => "customer"))->result();
        $consumer_list = array(array("id" => "", "text" => "-"));
        foreach ($customers as $key => $value) {
            $consumer_list[] = array("id" => $value->id, "text" => trim($value->first_name . " " . $value->last_name));
        }
        echo json_encode($consumer_list);
    }

    private function get_labeled_status($status){
        $labeled_status = "";

        if($status == "draft"){
            $labeled_status = "<span class='label label-default'>".(ucwords($status))."</span>";
        } else if($status == "approved"){
            $labeled_status = "<span class='label label-success'>".(ucwords($status))."</span>";
        } else if($status == "cancelled"){
            $labeled_status = "<span class='label label-danger'>".(ucwords($status))."</span>";
        }

        return $labeled_status;
    }

    function index(){
        //$this->validate_user_module_permission("module_ams");
        $this->template->rander("epass/index");
    }

    function view(){
        //$this->validate_user_module_permission("module_ams");
        $view_data['test'] = "";
        $this->load->view("epass/view", $view_data);
    }

    function list_data(){
        $list_data = $this->EventPass_model->get_details(array(), true)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {

        return array(
            $data->id,
            strtoupper($data->uuid),
            modal_anchor(get_uri("events/view"), $data->event_name, array("class" => "edit", "title" => lang('event_name'), "data-post-id" => encode_id($data->event_id, "event_id"))),
            $data->full_name,
            strtoupper($data->group_name),
            $data->vcode,
            nl2br($data->remarks),
            $data->seats,
            $data->assign,
            $this->get_labeled_status($data->status),
            convert_date_utc_to_local($data->timestamp),
            modal_anchor(get_uri("EventPass/modal_form"), "<i class='fa fa-bolt'></i>", array("class" => "edit", "title" => lang('ticket_approval'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("EventPass/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        $view_data['model_info'] = $this->EventPass_model->get_details(array(
            "id" => $id
        ));

        $this->load->view('epass/modal_form', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->EventPass_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function approve() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        $id = $this->input->post('id');
        
        if ($this->EventPass_model->approve($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_approved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_approved')));
        }
    }

    function cancel() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        $id = $this->input->post('id');
        
        if ($this->EventPass_model->cancel($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_cancelled')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_cancelled')));
        }
    }
}
