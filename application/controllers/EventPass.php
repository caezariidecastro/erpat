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
            $labeled_status = "<span class='label label-primary'>".(ucwords($status))."</span>";
        } else if($status == "sent"){
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
        $this->template->rander("epass/index");
    }

    function view(){
        $view_data['is_admin'] = $this->login_user->is_admin;
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

        $actions = "";
        if($data->status == "draft" || $data->status == "approved" || $data->status == "cancelled") {
            $actions = modal_anchor(get_uri("EventPass/modal_form"), "<i class='fa fa-bolt'></i>", array("class" => "edit", "title" => lang('ticket_approval'), "data-post-id" => $data->id))
            .$assign_customer;
        }
        if($data->status == "draft" || $data->status == "cancelled") {
            $actions .= js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("EventPass/delete"), "data-action" => "delete-confirmation"));
        }

        $previews = "";
        if($data->status == "sent") {
            $seats = explode(",", $data->seat_assign);
            if (count($seats)) {
                foreach($seats as $index=>$seat) {
                    $previews = modal_anchor(get_uri("EventPass/modal_form_epass"), "<i class='fa fa-file-image-o'></i>", array("class" => "edit", "title" => lang('image_preview'), "data-post-file_url" => get_uri(get_setting('event_epass_ticket_path')."/".$data->uuid."-".$index.".jpg")));
                }
            }
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
            $previews,
            $this->get_labeled_status($data->status),
            convert_date_utc_to_local($data->timestamp),
            $actions
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

    function modal_form_epass() {
        $view_data['file_url'] = $this->input->post('file_url');

        $this->load->view('epass/modal_form_epass', $view_data);
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
        $this->access_only_admin();

        $epasses = $this->getAllUnassignedPass();
        $view_data['total'] = count($epasses);

        $reserve = 0;
        $epass = 0;
        foreach($epasses as $single) {
            $reserve += $single->seats+1;
            $epass += 1;
        }
        $view_data['seats'] = $reserve;
        $view_data['epass'] = $epass;

        $action_lists = array(
            array("id" => "", "text" => "- ".lang('select_action')." -"),
            array("id" => "clear", "text" => "Clear Seat Assignment"),
            array("id" => "allocate", "text" => "Start Seat Allocation")
        );
        $view_data['action_lists'] = $action_lists;
        
        $this->load->view('epass/modal_form_allocate', $view_data);
    }

    function modal_form_email_blast() {
        $this->access_only_admin();

        $epasses = $this->getEpassListApproved();

        $lists = array();
        $reserve = 0;
        foreach($epasses as $single) {
            array_push($lists, array(
                "id" => $single->id
            ));
            $reserve += $single->seats+1;
        }
        $view_data['lists'] = $lists;
        $view_data['seats'] = $reserve;

        $action_lists = array(
            array("id" => "", "text" => "- ".lang('select_action')." -"),
            array("id" => "render_all", "text" => "Render All"),
            array("id" => "email_blast", "text" => "Email Blast"),
            array("id" => "resend", "text" => "Bulk Resend")
        );
        $view_data['action_lists'] = $action_lists;
        
        $this->load->view('epass/modal_form_email_blast', $view_data);
    }

    function prepare_epass_instance() {
        $this->access_only_admin();

        $action = $this->input->post('action');

        if($action === "render_all") {
            $epasses = array();
            $sent = $this->getEpassListSent();
            foreach($sent as $item) {
                if(!isset($item->tickets)) {
                    $epasses[] = $item;
                }
            }
            $approved = $this->getEpassListApproved();
            foreach($approved as $item) {
                if(!isset($item->tickets)) {
                    $epasses[] = $item;
                }
            }
        } else if($action === "email_blast") {
            $epasses = $this->getEpassListApproved();
        } else if($action === "resend") {
            $epasses = $this->getEpassListSent();
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            exit;
        }

        echo json_encode(array("success" => true, "action" => $action, "data" => $epasses, 'message' => lang('record_saved')));
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
        $this->access_only_admin();

        $epasses = array();

        $action = $this->input->post('action');
        if($action == "clear") {
            //SET ALL APPROVED seat_assign NULLED.
            $this->EventPass_model->unassign_all_approved();
        } else {
            //GET ALL APPROVED epass for processing.
            $epasses = $this->getAllUnassignedPass();
        }

        //RETURN TOTAL SUMMARY OF ACTION.
        echo json_encode(array("success" => true, "action" => $action, "data" => $epasses, 'message' => lang('record_saved')));
    }

    function allocate_seats() {
        $this->access_only_admin();

        $id = $this->input->post('id');
        $uuid = $this->input->post('uuid');
        $seats = $this->input->post('seats');

        $group_name = $this->input->post('group_name');
        $group_name = $group_name=="reserved"?"franchisee":$group_name;

        $req_seats = (int)$seats+1;
        $seat_option = array(
            "event_id" => $this->input->post('event_id'),
            "group_name" => $group_name,
            "seat_requested" => $req_seats
        );
        $avail_seat = $this->EPass_seat_model->get_seats_vacant($seat_option);
        if(count($avail_seat) <= 0) {
            echo json_encode(array("success" => false, 'data' => 'ePass #'.$id." w/ seats of ".$req_seats, 'message' => lang('no_seats_available')));
            exit();
        }

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
            echo json_encode(array("success" => true, 'data' => "(".$group_name.') ePass #'.$id." w/ seats of ".$req_seats." to ".$avail_seat[0]->area_name."(".implode($seat_assigned, ",").")", 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'data' => $group_name.') ePass #'.$id." w/ seats of ".$req_seats, 'message' => lang('error_occurred')));
        }
    }

    function prepare_epass_render_or_email() {
        $this->access_only_admin();

        $id = $this->input->post('id');
        $action = $this->input->post('action');

        //Get the instance of the epass.
        $filter = array("id"=>$id);
        $instance = $this->EventPass_model->get_details($filter)->row();
        if(!$instance) {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            exit;
        }

        if($action == "render_all") {
            //Generate all the epass ticket image and return the url's.
            $this->load->library('ImageEditor');
            $tickets = array();
            $seats = explode(",", $instance->seat_assign);
            foreach($seats as $index=>$seat) {
                $seat_filter = array("id"=>$seat);
                if($cur_seat = $this->EPass_seat_model->get_details($seat_filter)->row()) {
                    $epass = array(
                        "uuid" => $instance->uuid."-".$index,
                        "fname" => $instance->full_name,
                        "area" => $cur_seat->area_name,
                        "seat" => $cur_seat->seat_name
                    );
                    $image_data = (new ImageEditor())->render($epass);
                    array_push($tickets, $image_data["path"]);
                }
            }
            $tickets_db = serialize($tickets);

            $update = array( "tickets" => $tickets_db );
            if( $this->EventPass_model->save($update, $id) ) {
                echo json_encode(array("success" => true, 'message' => lang('record_saved') ));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            }
            exit;
        } else if($action == "email_blast" || $action == "resend") {
            //Compose the email instance then send.
            $data = array(
                "reference_id" => $instance->uuid,
                "first_name" => $instance->first_name,
                "last_name" => $instance->last_name,
                "phone" => $instance->phone,
                "seats" => $instance->seats,
                "group" => strtoupper($instance->group_name),
                "remarks" => $instance->remarks,
                "attachments" => unserialize($instance->tickets),
            );
            $success = $this->sendEpassConfirm($data, $instance->user_email);
            if(!$success) {
                echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
                exit;
            }

             //Save new status to sent if success.
            $update = array( "status" => "sent" );
            if( $this->EventPass_model->save($update, $id) ) {
                echo json_encode(array("success" => true, 'message' => lang('record_saved') ));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
            }
            exit;
        }        

        echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
    }

    //for allocation only.
    private function getAllUnassignedPass() {
        $epasses = array();

        $reserved = $this->EventPass_model->get_all_approved('reserved');
        $franchisee = $this->EventPass_model->get_all_approved('franchisee');
        $distributor = $this->EventPass_model->get_all_approved('distributor');
        $seller = $this->EventPass_model->get_all_approved('seller');
        $viewer = $this->EventPass_model->get_all_approved('viewer');

        $sents = $this->EventPass_model->get_all_sent();
        foreach($sents as $sent) {
            if(!$sent->seat_assign) {
                if($sent->group_name == "reserved") {
                    $reserved[] = $sent;
                } else if($sent->group_name == "franchisee") {
                    $franchisee[] = $sent;
                } else if($sent->group_name == "distributor") {
                    $distributor[] = $sent;
                } else if($sent->group_name == "seller") {
                    $seller[] = $sent;
                } else if($sent->group_name == "viewer") {
                    $viewer[] = $sent;
                }   
            }
        }

        foreach($reserved as $reserv) {
            if(!$reserv->seat_assign) {
                $epasses[] = $reserv;
            }
        }
        foreach($franchisee as $fran) {
            if(!$fran->seat_assign) {
            $epasses[] = $fran;
            }
        }
        foreach($distributor as $dist) {
            if(!$dist->seat_assign) {
                $epasses[] = $dist; 
            }
        }
        foreach($seller as $sell) {
            if(!$sell->seat_assign) {
                $epasses[] = $sell;
            }
        }
        foreach($viewer as $view) {
            if(!$view->seat_assign) {
                $epasses[] = $view;  
            }
        }

        return $epasses;
    }

    private function getEpassListApproved() {
        $epasses = array();

        $reserved = $this->EventPass_model->get_all_approved('reserved');
        foreach($reserved as $reserve) {
            $epasses[] = $reserve;
        }
        $franchisee = $this->EventPass_model->get_all_approved('franchisee');
        foreach($franchisee as $fran) {
            $epasses[] = $fran;
        }
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

    private function getEpassListSent() {
        $epasses = array();

        $sents = $this->EventPass_model->get_all_sent();
        foreach($sents as $sent) {
            $epasses[] = $sent;
        }

        return $epasses;
    }

    private function sendEpassConfirm($data, $email){
        $email_template = $this->Email_templates_model->get_final_template("epass_confirm");

        $parser_data["REFERENCE_ID"] = $data['reference_id'];
        $parser_data["SIGNATURE"] = $email_template->signature;
        $parser_data["FIRST_NAME"] = $data['first_name'];
        $parser_data["LAST_NAME"] = $data['last_name'];
        $parser_data["PHONE_NUMBER"] = $data['phone'];
        $parser_data["TOTAL_SEATS"] = $data['seats'];
        $parser_data["GROUP_NAME"] = $data['group'];
        $parser_data["REMARKS"] = $data['remarks'];
        $parser_data["LOGO_URL"] = get_logo_url();
        $link = "https://events.brilliantskinessentials.ph/companion?refid=".$data['reference_id'];
        $parser_data["COMPANION_LINK"] = "<a href='$link' target='__blank'>$link</a>";

        $attachments = array();
        foreach($data['attachments'] as $urls) {
            array_push($attachments, array(
                "file_path" => $urls
            ));
        }

        $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
        return send_app_mail($email, $email_template->subject, $message, array(
            "attachments" => $attachments,
            "cc" => "admin@brilliantskinessentialsinc.com, brilliantaleck@gmail.com", 
        ));
    }
}
