<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Access_devices extends MY_Controller {

	function __construct() {
       	parent::__construct();
        $this->load->library('encryption');
		$this->load->model("Access_devices_model");
        $this->load->model("Access_device_categories_model");
        $this->load->helper('utility');
    }

    function get_device_categories_select2_data() {
        $devices = $this->Access_device_categories_model->get_details(array())->result();
        $device_lists = array(array("id" => "", "text" => "- Select Categories -"));
        foreach ($devices as $key => $value) {
            $device_lists[] = array("id" => $value->id, "text" => $value->title );
        }
        return $device_lists;
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Access_devices_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    function index(){
        $view_data['categories'] = $this->get_device_categories_select2_data();
        $this->load->view("access/devices/index", $view_data);
    }

    function list_data(){
        $list_data = $this->Access_devices_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $passess_count = $this->Users_model->get_actual_active($data->passes);
        $pass_count = "<span class='label label-light w100'><i class='fa fa-users'></i> " . $passess_count . "</span>";
        return array(
            $data->id,
            $data->device_name,
            $data->category_name,
            modal_anchor(get_uri("Access_devices/pass_list"), $pass_count, array("title" => lang('access_lists'), "data-post-passes" => $data->passes)),
            nl2br($data->remarks),
            // $data->api_key,
            // $data->api_secret,
            //$data->labels,
            $data->status?"Active":"Disabled",
            convert_date_utc_to_local($data->update_at),
            modal_anchor(get_uri("Access_devices/modal_form_credential"), "<i class='fa fa-key'></i>", array("class" => "edit", "title" => lang('show_secret'), "data-post-id" => $data->id))
            
            . modal_anchor(get_uri("Access_devices/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('add_device'), "data-post-id" => $data->id))
           
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("Access_devices/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function pass_list() {
        $view_data['team_members'] = $this->Users_model->get_team_members($this->input->post('passes'))->result();
        $this->load->view('access/devices/passes', $view_data);
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        if($id) {
            $view_data['model_info'] = $this->Access_devices_model->get_details(array(
                "id" => $id
            ))->row();
        }

        $team_members = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->result();
        $members_dropdown = array();

        foreach ($team_members as $team_member) {
            $fullname = $team_member->first_name . " " . $team_member->last_name;
            if(get_setting('name_format') == "lastfirst") {
                $fullname = $team_member->last_name.", ".$team_member->first_name;
            }
            $members_dropdown[] = array("id" => $team_member->id, "text" => $fullname);
        }

        $view_data['passes_dropdown'] = json_encode($members_dropdown);

        $view_data['category_dropdown'] = $this->get_device_categories_select2_data();
        $this->load->view('access/devices/modal_form', $view_data);
    }

    function modal_form_credential() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        if($id) {
            $view_data['model_info'] = $this->Access_devices_model->get_details(array(
                "id" => $id
            ))->row();
        }

        $this->load->view('access/devices/modal_form_credential', $view_data);
    }

    function generate_new_secret() {
        validate_submitted_data(array(
            "id" => "required",
        ));

        $id = $this->input->post('id');

        $data = array(
            "api_secret" => password_hash($this->uuid->v4(), PASSWORD_DEFAULT)
        );

        $save_id = $this->Access_devices_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true,'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function save() {
        validate_submitted_data(array(
            "device_name" => "required",
            "category_id" => "required"
        ));

        $id = $this->input->post('id');

        $device_name = $this->input->post('device_name');
        $passes = $this->input->post('passes');
        $remarks = $this->input->post('remarks');

        $api_key = $this->input->post('api_key');
        $api_secret = $this->input->post('api_secret');

        $category_id = $this->input->post('category_id');
        //$labels = $this->input->post('labels');
        //$status = $this->input->post('status');

        $data = array(
            "device_name" => $this->input->post('device_name'),
            "passes" => $this->input->post('passes'),
            "remarks" => $this->input->post('remarks'),

            "category_id" => $this->input->post('category_id'),
            //"labels" => $this->input->post('labels'),
            "status" => 1,
        );
        $data = clean_data($data);

        if(!$id) {
            $data["api_key"] = $this->uuid->v4();
            $data["api_secret"] = password_hash($this->uuid->v4(), PASSWORD_DEFAULT);
        }

        $save_id = $this->Access_devices_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        
        if ($this->Access_devices_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
