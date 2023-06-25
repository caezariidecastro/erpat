<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Leave_credits extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
        
        $this->load->model("Leave_credits_model");
        $this->load->model("Leave_types_model");

        $this->init_permission_checker("leave");
    }

    //load leave type list view
    function index() {
        $this->template->rander("leave_credits/index");
    }

    //load leave type add/edit form
    function modal_form() {
        $view_data['model_info'] = $this->Leave_credits_model->get_one($this->input->post('id'));
        $this->load->view('leave_credits/modal_form', $view_data);
    }

    //save leave type
    function save() {

        validate_submitted_data(array(
            "counts" => "numeric",
            "user_id" => "required",
            "leave_type_id" => "required",
            "counts" => "required",
        ));

        if(!$this->with_permission("leave_update")) {
            exit_response_with_message("not_permitted_updating_leave_credits");
        }

        $id = $this->input->post('id');
        $data = array(
            "user_id" => $this->input->post('user_id'),
            "leave_type_id" => $this->input->post('leave_type_id'),
            "counts" => $this->input->post('counts'),
            "action" => $this->input->post('action'),
            "remarks" => $this->input->post('remarks'),
        );

        if(!$id){
            $data["date_created"] = get_current_utc_time();
            $data["created_by"] = $this->login_user->id;
        }

        $save_id = $this->Leave_credits_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function convert() {
        validate_submitted_data(array(
            "counts" => "numeric",
            "user_id" => "required",
            "leave_type_id" => "required",
            "leave_type_to_id" => "required",
            "counts" => "required",
        ));

        if(!$this->with_permission("leave_update")) {
            exit_response_with_message("not_permitted_updating_leave_credits");
        }

        if($this->input->post('leave_type_id') === $this->input->post('leave_type_to_id')) {
            echo json_encode(array("success" => false, 'message' => lang('same_leave_type_origin_and_target')));
            exit;
        }

        $shared_data = array(
            "user_id" => $this->input->post('user_id'),
            "counts" => $this->input->post('counts'),
            "date_created" => get_current_utc_time(),
            "created_by" => $this->login_user->id,
            "remarks" => $this->input->post('remarks'),
        );

        $balance = $this->Leave_credits_model->get_balance(
            array(
                "user_id"=>$this->input->post('user_id'),
                "leave_type_id"=>$this->input->post('leave_type_id')
            )
        );

        if(intval($this->input->post('counts')) > $balance) {
            echo json_encode(array("success" => false, 'message' => lang('leave_type_credit_insufficient').$balance." credit(s)"));
            exit;
        }

        $origin_data = $shared_data;
        $origin_data['leave_type_id'] = $this->input->post('leave_type_id');
        $origin_data['action'] = 'credit';
        $origin_id = $this->Leave_credits_model->save($origin_data);

        $target_data = $shared_data;
        $target_data['leave_type_id'] = $this->input->post('leave_type_to_id');
        $target_data['action'] = 'debit';
        $target_id = $this->Leave_credits_model->save($target_data);

        if ($origin_id && $target_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //delete/undo a leve type
    function delete() {
        
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');
        if ($this->input->post('undo')) {
            if ($this->Leave_credits_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Leave_credits_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    //prepare leave types list data for datatable
    function list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');
        $action = $this->input->post('action');
        $department_id = $this->input->post('department_select2_filter');
        
        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "login_user_id" => $this->login_user->id,
            "access_type" => $this->access_type, 
            "allowed_members" => $this->allowed_members, 
            "user_id" => $user_id,
            "action" => $action,
            "department_id" => $department_id,
            "leave_type_id" => $this->input->post('leave_type_id'),
        );
        $list_data = $this->Leave_credits_model->get_details($options)->result();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data, $action=="balance"?true:false);
        }
        echo json_encode(array("data" => $result));
    }

    //get a row of leave types row
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Leave_credits_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //make a row of leave types row
    private function _make_row($data, $balance = false) {
        $label_column_text = $data->action == "debit" ? "ADDED" : "DEDUCTED";
        $label_column_color = $data->action == "debit" ? "#8bc408" : "#ff4747";
        $label_column_count = $data->action == "debit" ? $data->counts : ($data->counts*-1);

        if($balance) {
            $label_column_text = "BALANCE";
            $label_column_color = "#4f72ff";
            $label_column_count = $data->balance;
        }

        return array(
            get_team_member_profile_link($data->user_id, $data->fullname, array("target" => "_blank")),
            "<span class='mt0 label' style='background-color:".$label_column_color.";' title=" . lang("label") . ">" . strtoupper($label_column_text) . "</span> ",
            strval(convert_number_to_decimal($label_column_count)),
            $data->remarks ? $data->remarks : "-",
            $data->date_created ? $data->date_created : "-",
            get_team_member_profile_link($data->created_by, $data->creator, array("target" => "_blank")),
            //modal_anchor(get_uri("leave_credits/modal_form"), "<i class='fa fa-info'></i>", array("class" => "edit", "title" => lang('leave_credit_info'), "data-post-id" => $data->id)) .
            js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_leave_type'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("hrs/leave_credits/delete"), "data-action" => "delete"))
        );
    }

}

/* End of file Leave_Credits.php */
/* Location: ./application/controllers/Leave_Credits.php */