<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Epass_Area extends MY_Controller {

	function __construct() {
       	parent::__construct();
        $this->load->library('encryption');
		$this->load->model("EPass_area_model");
        $this->load->model("EPass_seat_model");
        $this->load->model("Events_model");
        $this->load->helper('utility');
    }

    function get_event_select2_data() {
        $events = $this->Events_model->get_details(array(
            "start_date" => get_current_utc_time('Y-m-d'),
            "end_date" => add_period_to_date(get_current_utc_time('Y-m-d'), 365)
        ))->result();
        $event_lists = array(array("id" => "", "text" => "- Select Event -"));
        foreach ($events as $key => $value) {
            $event_lists[] = array("id" => $value->id, "text" => trim($value->title . " (" . $value->start_date . " to " . $value->start_date .")"));
        }
        return $event_lists;
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->EPass_area_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    function index(){
        $this->load->view("epass/area/index");
    }

    function list_data(){
        $list_data = $this->EPass_area_model->get_details()->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {

        $filter = array(
            'status' => 'assigned',
            'limits' => 1000000,
            'area' => $data->id,
        );
        $assigned = $this->EPass_seat_model->get_details($filter)->num_rows();

        return array(
            $data->id,
            modal_anchor(get_uri("events/view"), $data->event_name, array("class" => "edit", "title" => lang('event_name'), "data-post-id" => encode_id($data->event_id, "event_id"))),
            $data->area_name,
            $data->blocks,
            $data->seats,
            $data->seats-$assigned,
            $data->sort,
            nl2br($data->remarks?$data->remarks:""),
            convert_date_utc_to_local($data->update_at),
            modal_anchor(get_uri("Epass_Area/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('ticket_approval'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("Epass_Area/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        if($id) {
            $view_data['model_info'] = $this->EPass_area_model->get_details(array(
                "id" => $id
            ))->row();
        }

        $view_data['events_dropdown'] = $this->get_event_select2_data();
        $this->load->view('epass/area/modal_form', $view_data);
    }

    function save() {
        validate_submitted_data(array(
            "event_id" => "required",
            "area_name" => "required"
        ));

        $id = $this->input->post('id');
        $event_id = $this->input->post('event_id');
        $area_name = $this->input->post('area_name');
        $sort = $this->input->post('sort');
        $remarks = $this->input->post('remarks');

        $data = array(
            "event_id" => $this->input->post('event_id'),
            "area_name" => $this->input->post('area_name'),
            "sort" => $this->input->post('sort'),
            "remarks" => $this->input->post('remarks'),
        );
        $data = clean_data($data);

        $save_id = $this->EPass_area_model->save($data, $id);
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
        
        if ($this->EPass_area_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
