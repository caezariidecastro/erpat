<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Epass_Seat extends MY_Controller {

	function __construct() {
       	parent::__construct();
        $this->load->library('encryption');
		$this->load->model("EPass_seat_model");
        $this->load->model("EPass_block_model");
        $this->load->model("EPass_area_model");
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

    function get_area_select2_data($event_id) {
        $events = $this->EPass_area_model->get_details(array(
            "event_id" => $event_id
        ))->result();

        $event_lists = array(array("id" => "", "text" => "- ".lang('select_area')." -"));
        foreach ($events as $key => $value) {
            $event_lists[] = array("id" => $value->id, "text" => $value->area_name . " (" . trim($value->event_name.")"));
        }
        return $event_lists;
    }

    function get_block_select2_data($area_id) {
        $events = $this->EPass_block_model->get_details(array(
            "area_id" => $area_id
        ))->result();

        $event_lists = array(array("id" => "", "text" => "- ".lang('select_block')." -"));
        foreach ($events as $key => $value) {
            $event_lists[] = array("id" => $value->id, "text" => $value->block_name ." in ". $value->area_name . " (" . trim($value->event_name.")"));
        }
        return $event_lists;
    }

    function get_seat_select2_data($block_id) {
        $seats_involved = $this->EPass_seat_model->get_seats_available(array(
            "event_id" => 3,
            "block_id" => $block_id,
        ))->result();

        $seats_dropdown = array(array("id" => "", "text" => "- ".lang('select_seats')." -"));
        foreach ($seats_involved as $item) {
            $seats_dropdown[] = array("id" => $item->id, "text" => $item->area_name.": ".$item->block_name." > ".$item->seat_name);
        }

        return $seats_dropdown;
    }

    private function get_labeled_status($status){
        $labeled_status = "";

        if($status == "vacant"){
            $labeled_status = "<span class='label label-success'>".(ucwords($status))."</span>";
        } else {
            $labeled_status = "<span class='label label-danger'>".(ucwords($status))."</span>";
        }

        return $labeled_status;
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->EPass_seat_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    function list_areas($event_id) {
        echo json_encode(array(
            "data" => $this->get_area_select2_data($event_id)   
        ));
    }

    function list_blocks($area_id) {
        echo json_encode(array(
            "data" => $this->get_block_select2_data($area_id)   
        ));
    }

    function list_available($block_id) {
        echo json_encode(array(
            "data" => $this->get_seat_select2_data($block_id)   
        ));
    }

    function index(){
        $this->load->view("epass/seat/index");
    }

    function list_data(){
        $search = $this->input->post('search');
        $status = $this->input->post('status');
        $limits = $this->input->post('limits');
        $area = $this->input->post('area');
        $data = array(
            'search' => $search,
            'status' => $status,
            'limits' => $limits?$limits:100,
            'area' => $area,
        );

        $list_data = $this->EPass_seat_model->get_details($data)->result();

        $lists = array();
        $result = array();
        foreach ($list_data as $data) {
            if(in_array($data->sort, $lists)) {
                $data->last_name = $data->last_name." - (DUPLICATE)";
            }
            $result[] = $this->_make_row($data);
            $lists[] = $data->sort;
        }
        echo json_encode(array("data" => $result));
    }

    private function getRowUser($epass_uuid, $first_name, $last_name) {
        $html = "";
        if($first_name) {
            $html = "<a href='#' onclick='alert(`e-Pass: ".$epass_uuid."`)'>";
            $html .= $first_name." ".$last_name;
            $html .= "</a>";
        }

        return $html;
    }

    private function _make_row($data) {

        return array(
            $data->id,
            modal_anchor(get_uri("events/view"), $data->event_name, array("class" => "edit", "title" => lang('event_name'), "data-post-id" => encode_id($data->event_id, "event_id"))),
            $data->area_name,
            $data->block_name,
            $data->seat_name,
            $this->get_labeled_status($data->first_name ? "assigned":"vacant"),
            $data->sort,
            nl2br($data->remarks?$data->remarks:"")."\n".$this->getRowUser($data->epass_uuid, $data->first_name, $data->last_name),
            convert_date_utc_to_local($data->update_at),
            modal_anchor(get_uri("Epass_Seat/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('ticket_approval'), "data-post-id" => $data->id))
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("Epass_Seat/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        if($id) {
            $view_data['model_info'] = $this->EPass_seat_model->get_details(array(
                "id" => $id
            ))->row();
        }

        $view_data['events_dropdown'] = $this->get_event_select2_data();
        $view_data['areas_dropdown'] = array(array("id" => "", "text" => "- Select Event First -"));
        $view_data['blocks_dropdown'] = array(array("id" => "", "text" => "- Select Areas First -"));
        $this->load->view('epass/seat/modal_form', $view_data);
    }

    function save() {
        validate_submitted_data(array(
            "block_id" => "required",
            "seat_name" => "required"
        ));

        $id = $this->input->post('id');
        $block_id = $this->input->post('block_id');
        $seat_name = $this->input->post('seat_name');
        $sort = $this->input->post('sort');
        $remarks = $this->input->post('remarks');

        $data = array(
            "block_id" => $this->input->post('block_id'),
            "seat_name" => $this->input->post('seat_name'),
            "sort" => $this->input->post('sort'),
            "remarks" => $this->input->post('remarks'),
        );
        $data = clean_data($data);

        $save_id = $this->EPass_seat_model->save($data, $id);
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
        
        if ($this->EPass_seat_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }
}
