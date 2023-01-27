<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class EventPass extends MY_Controller {

	function __construct() {
       	parent::__construct();
        $this->load->library('encryption');
		$this->load->model("EventPass_model");
        $this->load->model("Events_model");
        $this->load->model("EPass_seat_model");
        $this->load->model("EPass_block_model");
        $this->load->model("EPass_area_model");
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

    private function _get_users_dropdown_list_for_filter() {
        $where = array("status" => "active");

        $members = $this->Users_model->get_dropdown_list(array("first_name", "last_name", "user_type", "email"), "id", $where);

        $users_dropdown = array(array("id" => "", "text" => "- " . lang("select_user") . " -"));
        foreach ($members as $id => $name) {
            $users_dropdown[] = array("id" => $id, "text" => $name);
        }

        return $users_dropdown;
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

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->EventPass_model->get_details($options)->row();
        return $this->_make_row($data);
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
        $status = $this->input->post('status');
        $type = $this->input->post('type');
        $groups = $this->input->post('groups');
        $limits = $this->input->post('limits');
        $search = $this->input->post('search');

        $filter = array(
            'search' => $search,
            "status" => $status?$status:"draft",
            "type" => $type,
            "groups" => $groups,
            "limits" => $limits?$limits:100
        );

        $list_data = $this->EventPass_model->get_details($filter)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        
        $assign_customer = modal_anchor(get_uri("EventPass/modal_form_add_user"), "<i class='fa fa-user'></i>", array("class" => "edit", "title" => lang('epass_user_assignment'), "data-post-id" => $data->id));
        if($data->user_id) {
            $assign_customer = "";
        }

        return array(
            $data->id,
            strtoupper($data->uuid),
            modal_anchor(get_uri("events/view"), $data->event_name, array("class" => "edit", "title" => lang('event_name'), "data-post-id" => encode_id($data->event_id, "event_id"))),
            $data->full_name,
            strtoupper($data->group_name),
            $data->vcode,
            nl2br($data->remarks?$data->remarks.($data->guest?"\n\nGuest: ".$data->guest:""):""),
            $data->seats,
            nl2br($data->assign),
            $this->get_labeled_status($data->status),
            convert_date_utc_to_local($data->timestamp),
            modal_anchor(get_uri("EventPass/modal_form"), "<i class='fa fa-bolt'></i>", array("class" => "edit", "title" => lang('ticket_approval'), "data-post-id" => $data->id))
            .$assign_customer
            . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("EventPass/delete"), "data-action" => "delete-confirmation"))
        );
    }

    function modal_form() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        $view_data['is_admin'] = $this->login_user->is_admin;
        $view_data['model_info'] = $this->EventPass_model->get_details(array(
            "id" => $id
        ))->row();

        $this->load->view('epass/modal_form', $view_data);
    }

    function modal_form_add_user() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        $view_data['model_info'] = $this->EventPass_model->get_details(array(
            "id" => $id
        ))->row();

        $view_data['users_dropdown'] = $this->_get_users_dropdown_list_for_filter();
        $this->load->view('epass/modal_form_add_user', $view_data);
    }

    function save_assigned_user() {
        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $id = $this->input->post('id');

        $data = array();
        $data['user_id'] = $this->input->post('user_assigned');

        if ($this->EventPass_model->save($data, $id)) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($id), 'id' => $id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function modal_form_add() {
        $view_data['events_dropdown'] = $this->get_event_select2_data();
        $view_data['areas_dropdown'] = array(array("id" => "", "text" => "- Select Event First -"));
        $view_data['blocks_dropdown'] = array(array("id" => "", "text" => "- Select Areas First -"));
        $view_data['seats_dropdown'] = array(array("id" => "", "text" => "- Select Block First -"));
        
        $view_data['group_name_dropdown'] = array(
            array("id" => "", "text" => "- Select Group -"),
            array("id" => "viewer", "text" => "User / Viewer"),
            array("id" => "seller", "text" => "Seller"),
            array("id" => "distributor", "text" => "Distributor"),
            array("id" => "franchiee", "text" => "Franchisee")
        );

        $this->load->view('epass/modal_form_add', $view_data);
    }

    function modal_form_allocate() {
        $epasses = $this->getEpassList();
        $view_data['total'] = count($epasses);

        $reserve = 0;
        $epass = 0;
        foreach($epasses as $single) {
            $reserve += $single->seats+1;
            $epass += 1;
        }
        $view_data['seats'] = $reserve;
        $view_data['epass'] = $epass;
        
        $this->load->view('epass/modal_form_allocate', $view_data);
    }

    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        
        $id = $this->input->post('id');
        $data['seat_assign'] = "";
        
        if ($this->EventPass_model->save($data, $id) && $this->EventPass_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function update() {
        validate_submitted_data(array(
            "id" => "required|numeric",
            "status" => "required"
        ));

        $id = $this->input->post('id');
        $status = $this->input->post('status');

        $data = array();
        if($status == "approved" || $status == "cancelled") {
            $data['status'] = $this->input->post('status');

            if($status == "approved") {
                $epass_instance = $this->EventPass_model->get_details(array(
                    "id" => $id
                ))->row();

                if(!$epass_instance->seat_assign) {
                    $seat_option = array(
                        "event_id" => $epass_instance->event_id,
                        "group_name" => $epass_instance->group_name,
                        "seat_requested" => $epass_instance->seats + 1
                    );
                    $avail_seat = $this->EPass_seat_model->get_seats_available($seat_option)->result();
    
                    $seat_assigned = array();
                    foreach($avail_seat as $item) {
                        $seat_assigned[] = $item->id;
                    }
    
                    $data['seat_assign'] = implode(",", $seat_assigned);
                }
            } else {
				$data['seat_assign'] = "";
			}
        }

        if ($this->EventPass_model->save($data, $id)) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($id), 'id' => $id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function reserve() {
        $event_id = $this->input->post('event_id');
        $assigned = $this->input->post('seat_assigned');
        $remarks = $this->input->post('remarks');
        $remarks = "Remarks: ".$remarks."\n\nAddress: ".$address;

        if(empty($event_id) || empty($assigned)) {
            echo json_encode(array("success"=>false, "message"=>"Please complete all required fields."));
            exit;
        }

        $seats = explode(",", $assigned);
        $epass_data = array(
            "uuid" => $this->uuid->v4(),
            "event_id" => $event_id,
            "user_id" => 0,
            "seats" => count($seats),
            "group_name" => "reserved",
            "seat_assign" => $assigned,
            "remarks" => $remarks,
            "timestamp" => get_current_utc_time()
        );
        
        if ($id = $this->EventPass_model->save($epass_data)) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($id), 'id' => $id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function clear_allocation() {
        $summary = array();

        //SET ALL APPROVED seat_assign NULLED.
        $this->EventPass_model->unassign_all_approved();

        //GET ALL APPROVED epass for processing.
        $epasses = $this->getEpassList();

        //RETURN TOTAL SUMMARY OF ACTION.
        echo json_encode(array("success" => true, "data" => $epasses, 'message' => lang('record_saved')));
    }

    function allocate_seats() {
        $id = $this->input->post('id');
        $uuid = $this->input->post('uuid');
        $seats = $this->input->post('seats');

        $seat_option = array(
            "event_id" => $this->input->post('event_id'),
            "group_name" => $this->input->post('group_name'),
            "seat_requested" => (int)$seats+1
        );
        $avail_seat = $this->EPass_seat_model->get_seats_available($seat_option)->result();

        $seat_names = array();
        $seat_assigned = array();
        foreach($avail_seat as $item) {
            $seat_assigned[] = $item->id;
            $seat_names[] = $item->seat_name;
        }

        $data = array(
            "seat_assign" => implode(",", $seat_assigned)
        );
        
        if( $this->EventPass_model->save($data, $id) ) {
            echo json_encode(array("success" => true, 'message' => $uuid." seats are ".implode(",", $seat_names)));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function generate_ticket() {

        if( $this->login_user->is_admin ) {
            $this->load->library('ImageEditor');

            $epass = array(
                "uuid" => "1541885d-5b9a-4d4c-8367-d83f71d61031",
                "fname" => "Juan Dela Cruz - Madrigal",
                "area" => "Gen. Admin",
                "seat" => "Seat #1234"
            );
            $image_data = (new ImageEditor())->render($epass);

            //echo json_encode(array("success" => true, "data" => $this->_row_data($id), 'id' => $id, 'message' => lang('record_saved')));
        }

        echo json_encode(array("success" => false));
    }

    private function getEpassList() {
        $epasses = array();

        $distributor = $this->EventPass_model->get_all_approved('distributor');
        foreach($distributor as $dist) {
            $epasses[] = $dist;
        }
        $seller = $this->EventPass_model->get_all_approved('seller');
        foreach($seller as $sell) {
            $epasses[] = $sell;
        }
        $viewer = $this->EventPass_model->get_all_approved('viewer');
        foreach($viewer as $view) {
            $epasses[] = $view;
        }

        return $epasses;
    }

}
