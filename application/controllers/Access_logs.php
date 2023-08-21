<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Access_logs extends MY_Controller {

	function __construct() {
       	parent::__construct();
        $this->load->library('encryption');
		$this->load->model("Access_logs_model");
        $this->load->model("Access_devices_model");
        $this->load->helper('utility');
    }

    function get_device_select2_data() {
        $devices = $this->Access_devices_model->get_details(array())->result();
        $device_lists = array(array("id" => "", "text" => "- Select Device -"));
        foreach ($devices as $key => $value) {
            $device_lists[] = array("id" => $value->id, "text" => $value->device_name );
        }
        return $device_lists;
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Access_logs_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    function index(){
        $this->template->rander("access/index");
    }

    function view(){
        $view_data['device_dropdown'] = $this->get_device_select2_data();
        $this->load->view("access/logs/index", $view_data);
    }

    function list_data(){
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $device_id = $this->input->post('device_id');

        $options = array(
            "start_date" => $start_date, 
            "end_date" => $end_date, 
            "device_id" => $device_id, 
        );

        $list_data = $this->Access_logs_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {

        return array(
            $data->id,
            $data->device_name,
            get_team_member_profile_link($data->user_id, $data->full_name),
            nl2br($data->remarks?$data->remarks:""),
            convert_date_utc_to_local($data->timestamp)
        );
    }

    function log_action() {
        validate_submitted_data(array(
            "device_id" => "required",
            "user_id" => "required"
        ));

        $device_id = $this->input->post('device_id');
        $user_id = $this->input->post('user_id');
        $remarks = $this->input->post('remarks'); //entry or exit

        $data = array(
            "device_id" => $this->input->post('device_id'),
            "user_id" => $this->input->post('user_id'),
            "remarks" => $this->input->post('remarks'),
            "timestamp" => get_current_utc_time(),
        );
        $data = clean_data($data);

        $save_id = $this->Access_logs_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Access_logs_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
