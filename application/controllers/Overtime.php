<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Overtime extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->with_module("module_overtime", true);

        //this module is accessible only to team members 
        $this->access_only_team_members();

        //we can set ip restiction to access this module. validate user access
        $this->check_allowed_ip();

        //initialize managerial permission
        $this->init_permission_checker("overtime");

        $this->load->model("Overtime_model");
    }

    //check ip restriction for none admin users
    private function check_allowed_ip() {
        if (!$this->login_user->is_admin) {
            $ip = get_real_ip();
            $allowed_ips = $this->Settings_model->get_setting("allowed_ip_addresses");
            if ($allowed_ips) {
                $allowed_ip_array = array_map('trim', preg_split('/\R/', $allowed_ips));
                if (!in_array($ip, $allowed_ip_array)) {
                    redirect("forbidden");
                }
            }
        }
    }

    //only admin or assigend members can access/manage other member's overtime
    protected function access_only_allowed_members($user_id = 0) {
        if ($this->access_type !== "all") {
            if ($user_id === $this->login_user->id || !array_search($user_id, $this->allowed_members)) {
                redirect("forbidden");
            }
        }
    }

    //show overtime list view
    function index() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        $this->template->rander("overtime/index", $view_data);
    }

    //show add/edit overtime modal
    function modal_form() {
        $user_id = 0;

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;
        $view_data['model_info'] = $this->Overtime_model->get_one($this->input->post('id'));
        if ($view_data['model_info']->id) {
            $user_id = $view_data['model_info']->user_id;

            $this->access_only_allowed_members($user_id, true);
        }

        if ($user_id) {
            //edit mode. show user's info
            $view_data['team_members_info'] = $this->Users_model->get_one($user_id);
        } else {
            //new add mode. show users dropdown
            //don't show none allowed members in dropdown
            if ($this->access_type === "all") {
                $where = array("user_type" => "staff");
            } else {
                if (!count($this->allowed_members)) {
                    redirect("forbidden");
                }
                $where = array("user_type" => "staff", "id !=" => $this->login_user->id, "where_in" => array("id" => $this->allowed_members));
            }

            $view_data['team_members_dropdown'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", $where);
        }

        $this->load->view('overtime/modal_form', $view_data);
    }

    //show overtime note modal
    function note_modal_form($user_id = 0) {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $view_data["clock_out"] = $this->input->post("clock_out"); //trigger clockout after submit?
        $view_data["user_id"] = $user_id;

        $view_data['model_info'] = $this->Overtime_model->get_one($this->input->post('id'));
        $this->load->view('overtime/note_modal_form', $view_data);
    }

    //add/edit overtime record
    function save() {
        $id = $this->input->post('id');

        validate_submitted_data(array(
            "id" => "numeric",
            "in_date" => "required",
            "out_date" => "required",
            "in_time" => "required",
            "out_time" => "required"
        ));

        //convert to 24hrs time format
        $start_time = $this->input->post('in_time');
        $end_time = $this->input->post('out_time');

        if (get_setting("time_format") != "24_hours") {
            $start_time = convert_time_to_24hours_format($start_time);
            $end_time = convert_time_to_24hours_format($end_time);
        }

        //join date with time
        $in_date_time = $this->input->post('in_date') . " " . $start_time;
        $out_date_time = $this->input->post('out_date') . " " . $end_time;

        //add time offset
        $in_date_time = convert_date_local_to_utc($in_date_time);
        $out_date_time = convert_date_local_to_utc($out_date_time);

        $override = $this->input->post('note_override');

        $data = array(
            "start_time" => $in_date_time,
            "end_time" => $out_date_time,
            "notes" => $override.": ".$this->input->post('note')
        );

        //save user_id only on insert and it will not be editable
        if ($id) {
            $info = $this->Overtime_model->get_one($id);
            $user_id = $info->user_id;
        } else {
            $user_id = $this->input->post('user_id');
            $data["user_id"] = $user_id;
        }

        $this->access_only_allowed_members($user_id);


        $save_id = $this->Overtime_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'isUpdate' => $id ? true : false, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //edit overtime note
    function save_note() {

        //TODO: Only approver can change note.

        $id = $this->input->post('id');

        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $data = array(
            "notes" => $this->input->post('note')
        );


        $save_id = $this->Overtime_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'isUpdate' => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //clock in / clock out
    function log_time($user_id = 0, $tag = "System Generated") {
        $note = $this->input->post('note');

        if ($user_id && $user_id != $this->login_user->id) {
            //check if the login user has permission to clock in/out this user
            $this->access_only_allowed_members($user_id);
        }

        $this->Overtime_model->log_time($user_id ? $user_id : $this->login_user->id, $tag.": ".$note);

        if ($user_id) {
            echo json_encode(array("success" => true, "data" => $this->_clock_in_out_row_data($user_id), 'id' => $user_id, 'message' => lang('record_saved'), "isUpdate" => true));
        } else if ($this->input->post("clock_out")) {
            echo json_encode(array("success" => true, "clock_widget" => clock_widget(true)));
        } else {
            clock_widget();
        }
    }

    //delete/undo overtime record
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->access_type !== "all") {
            $info = $this->Overtime_model->get_one($id);
            $this->access_only_allowed_members($info->user_id);
        }

        if ($this->input->post('undo')) {
            if ($this->Overtime_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Overtime_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* get all overtime of a given duration */

    function list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');

        $options = array(
            "start_date" => $start_date, 
            "end_date" => $end_date, 
            "login_user_id" => $this->login_user->id, 
            "user_id" => $user_id, 
            "access_type" => $this->access_type, 
            "allowed_members" => $this->allowed_members
        );
        $list_data = $this->Overtime_model->get_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //load overtime overtime info view
    function overtime_info() {
        $view_data['user_id'] = $this->login_user->id;
        $view_data['show_clock_in_out'] = true;

        if ($this->input->is_ajax_request()) {
            $this->load->view("team_members/overtime_info", $view_data);
        } else {
            $view_data['page_type'] = "full";
            $this->template->rander("team_members/overtime_info", $view_data);
        }
    }

    //get a row of attendnace list
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Overtime_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //prepare a row of overtime list
    private function _make_row($data) {
        $image_url = get_avatar($data->created_by_avatar);
        $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->created_by_user";
        $end_time = $data->end_time;
        if (!is_date_exists($end_time)) {
            $end_time = "";
        }

        $to_time = strtotime($data->end_time);
        if (!$end_time) {
            $to_time = strtotime($data->start_time);
        }
        $from_time = strtotime($data->start_time);

        $option_links = modal_anchor(get_uri("hrs/overtime/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_overtime'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_overtime'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("hrs/overtime/delete"), "data-action" => "delete"));

        if ($this->access_type != "all") {
            //don't show options links for none admin user's own records
            if ($data->user_id === $this->login_user->id) {
                $option_links = "";
            }
        }

        //if the rich text editor is enabled, don't show the note as title
        $note_title = $data->note;
        if (get_setting('enable_rich_text_editor')) {
            $note_title = "";
        }

        $note_link = modal_anchor(get_uri("hrs/overtime/note_modal_form"), "<i class='fa fa-comment-o p10'></i>", array("class" => "edit text-muted", "title" => lang("note"), "data-post-id" => $data->id));
        if ($data->note) {
            $note_link = modal_anchor(get_uri("hrs/overtime/note_modal_form"), "<i class='fa fa-comment p10'></i>", array("class" => "edit text-muted", "title" => $note_title, "data-modal-title" => lang("note"), "data-post-id" => $data->id));
        }

        $duration_type = $this->input->post('duration_type');
        if($duration_type == 'decimal') {
            $duration  = convert_seconds_to_hour_decimal(abs($to_time - $from_time));
        } else {
            $duration  = convert_seconds_to_time_format(abs($to_time - $from_time));
        }

        return array(
            get_team_member_profile_link($data->user_id, $user),
            $data->start_time,
            format_to_date($data->start_time),
            format_to_time($data->start_time),
            $end_time ? $end_time : 0,
            $end_time ? format_to_date($end_time) : "-",
            $end_time ? format_to_time($end_time) : "-",
            convert_seconds_to_time_format(abs($to_time - $from_time)),
            convert_seconds_to_hour_decimal(abs($to_time - $from_time)),
            $option_links
        );
    }

    //load the custom date view of overtime list 
    function custom() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        $this->load->view("overtime/custom_list", $view_data);
    }

    //load the clocked in members list view of overtime list 
    function members_clocked_in() {
        $this->load->view("overtime/members_clocked_in");
    }

    private function _get_members_dropdown_list_for_filter() {
        //prepare the dropdown list of members
        //don't show none allowed members in dropdown
        $where = $this->_get_members_query_options();

        $members = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", $where);

        $members_dropdown = array(array("id" => "", "text" => "- " . lang("user") . " -"));
        foreach ($members as $id => $name) {
            $members_dropdown[] = array("id" => $id, "text" => $name);
        }

        return $members_dropdown;
    }

    //get members query options
    private function _get_members_query_options($type = "") {
        if ($this->access_type === "all") {
            $where = array("user_type" => "staff");
        } else {
            if (!count($this->allowed_members) && $type != "data") {
                $where = array("user_type" => "nothing"); //don't show any users in dropdown
            } else {
                //add login user in dropdown list
                $allowed_members = $this->allowed_members;
                $allowed_members[] = $this->login_user->id;

                $where = array("user_type" => "staff", "where_in" => ($type == "data") ? $allowed_members : array("id" => $allowed_members));
            }
        }

        return $where;
    }

    //load the custom date view of overtime list 
    function summary() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        $this->load->view("overtime/summary_list", $view_data);
    }

    /* get all overtime of a given duration */

    function summary_list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');

        $options = array("start_date" => $start_date, "end_date" => $end_date, "login_user_id" => $this->login_user->id, "user_id" => $user_id, "access_type" => $this->access_type, "allowed_members" => $this->allowed_members);
        $list_data = $this->Overtime_model->get_summary_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $image_url = get_avatar($data->created_by_avatar);
            $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->created_by_user";

            $duration = convert_seconds_to_time_format(abs($data->total_duration));

            $result[] = array(
                get_team_member_profile_link($data->user_id, $user),
                $duration,
                to_decimal_format(convert_time_string_to_decimal($duration))
            );
        }

        echo json_encode(array("data" => $result));
    }

    //load the overtime summary details tab
    function export() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        $this->load->view("overtime/export_list", $view_data);
    }

    /* get data the overtime summary details tab */

    function export_list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');

        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "login_user_id" => $this->login_user->id,
            "user_id" => $user_id,
            "access_type" => $this->access_type,
            "allowed_members" => $this->allowed_members,
            "summary_details" => true
        );

        $list_data = $this->Overtime_model->get_summary_details($options)->result();

        //group the list by users

        $result = array();
        $last_key = 0;
        $last_user = "";
        $last_total_duration = 0;
        $last_created_by = "";
        $has_data = false;

        foreach ($list_data as $data) {
            $image_url = get_avatar($data->created_by_avatar);
            $user = "<span class='avatar avatar-xs mr10'><img src='$image_url'></span> $data->created_by_user";

            $duration = convert_seconds_to_time_format(abs($data->total_duration));

            //found a new user, add new row for the total
            if ($last_user != $data->user_id) {
                $last_user = $data->user_id;

                $result[] = array(
                    $data->created_by_user,
                    get_team_member_profile_link($data->user_id, $user),
                    "",
                    "",
                    ""
                );

                $result[$last_key][0] = $last_created_by;
                $result[$last_key][3] = "<b>" . convert_seconds_to_time_format($last_total_duration) . "</b>";
                $result[$last_key][4] = "<b>" . to_decimal_format(convert_time_string_to_decimal(convert_seconds_to_time_format($last_total_duration))) . "</b>";

                $last_total_duration = 0;
                $last_key = count($result) - 1;
            }

            $last_total_duration += abs($data->total_duration);
            $last_created_by = $data->created_by_user;
            $has_data = true;

            $duration = convert_seconds_to_time_format(abs($data->total_duration));

            $result[] = array(
                $data->created_by_user,
                "",
                format_to_date($data->start_date, false),
                $duration,
                to_decimal_format(convert_time_string_to_decimal($duration))
            );
        }

        if ($has_data) {
            $result[$last_key][0] = $data->created_by_user;
            $result[$last_key][3] = "<b>" . convert_seconds_to_time_format($last_total_duration) . "</b>";
            $result[$last_key][4] = "<b>" . to_decimal_format(convert_time_string_to_decimal(convert_seconds_to_time_format($last_total_duration))) . "</b>";
        }

        echo json_encode(array("data" => $result));
    }

    /* get clocked in members list */

    function clocked_in_members_list_data() {

        $options = array("login_user_id" => $this->login_user->id, "access_type" => $this->access_type, "allowed_members" => $this->allowed_members, "only_clocked_in_members" => true);
        $list_data = $this->Overtime_model->get_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //load the clock in / out tab view of overtime list 
    function clock_in_out() {
        $this->load->view("overtime/clock_in_out");
    }

    /* get data the overtime clock In / Out tab */

    function clock_in_out_list_data() {
        $options = $this->_get_members_query_options("data");
        $list_data = $this->Overtime_model->get_clock_in_out_details_of_all_users($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_clock_in_out_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    private function _clock_in_out_row_data($user_id) {
        $options = array("id" => $user_id);
        $data = $this->Overtime_model->get_clock_in_out_details_of_all_users($options)->row();
        return $this->_make_clock_in_out_row($data);
    }

    private function _make_clock_in_out_row($data) {
        if (isset($data->overtime_id)) {
            $start_time = format_to_time($data->start_time);
            $in_datetime = format_to_datetime($data->start_time);
            $status = "<div class='mb15' title='$in_datetime'>" . lang('clock_started_at') . " : $start_time</div>";
            $view_data = modal_anchor(get_uri("hrs/overtime/note_modal_form/$data->id"), "<i class='fa fa-sign-out'></i> " . lang('clock_out'), array("class" => "btn btn-default", "title" => lang('clock_out'), "id" => "timecard-clock-out", "data-post-id" => $data->overtime_id, "data-post-clock_out" => 1, "data-post-id" => $data->id));
        } else {
            $status = "<div class='mb15'>" . lang('not_clocked_id_yet') . "</div>";
            $view_data = js_anchor("<i class='fa fa-sign-in'></i> " . lang('clock_in'), array('title' => lang('clock_in'), "class" => "btn btn-default", "data-action-url" => get_uri("hrs/overtime/log_time/$data->id"), "data-action" => "update", "data-inline-loader" => "1", "data-post-id" => $data->id));
        }

        $image_url = get_avatar($data->image);
        $user_avatar = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt='...'></span>";

        return array(
            get_team_member_profile_link($data->id, $user_avatar . $data->member_name),
            $status,
            $view_data
        );
    }

}

/* End of file overtime.php */
/* Location: ./application/controllers/overtime.php */