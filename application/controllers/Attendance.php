<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attendance extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->with_module("module_attendance", true);

        //this module is accessible only to team members 
        $this->access_only_team_members();

        //we can set ip restiction to access this module. validate user access
        $this->check_allowed_ip();

        //initialize managerial permission
        $this->init_permission_checker("attendance");

        $this->load->model("Attendance_model");
        $this->load->model("Schedule_model");
        $this->load->model("Users_model");

        $this->load->helper("biometric");
    }

    protected function _get_team_select2_data() {
        $teams = $this->Team_model->get_details()->result();
        $team_select2 = array(array('id' => '', 'text'  => '- Departments -'));

        foreach($teams as $team){
            $team_select2[] = array('id' => $team->id, 'text'  => $team->title);
        }

        return $team_select2;
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

    //only admin or assigend members can access/manage other member's attendance
    protected function access_only_allowed_members($user_id = 0) {
        if ($this->access_type !== "all") {
            if ($user_id === $this->login_user->id || !array_search($user_id, $this->allowed_members)) {
                redirect("forbidden");
            }
        }
    }

    //show attendance list view
    function index() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        $view_data['department_select2'] = $this->_get_team_select2_data();
        $this->template->rander("attendance/index", $view_data);
    }

    //show add/edit attendance modal
    function modal_form() {
        $user_id = 0;

        validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;
        $model_info = $this->Attendance_model->get_one($this->input->post('id'));
        $model_info->break_time = serialized_breaktime($model_info->break_time, '', false, false);

        $view_data['model_info'] = $model_info;
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
        $view_data['sched_dropdown'] = $this->_get_schedule_dropdown();

        $this->load->view('attendance/modal_form', $view_data);
    }

    //prepare the dropdown list of schedule
    private function _get_schedule_dropdown() {
        $sched_dropdown = array(
            "" => '-',
        );

        $schedule = $this->Schedule_model->get_details()->result();
        foreach ($schedule as $item) {
            $sched_dropdown[$item->id] = $item->title;
        }
        return $sched_dropdown;
    }


    //show attendance note modal
    function note_modal_form($user_id = 0) {
        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $view_data["clock_out"] = $this->input->post("clock_out"); //trigger clockout after submit?
        $view_data["user_id"] = $user_id;

        $view_data['model_info'] = $this->Attendance_model->get_one($this->input->post('id'));
        $this->load->view('attendance/note_modal_form', $view_data);
    }

    //add/edit attendance record
    function save() {
        $id = $this->input->post('id');
        $sched_id = $this->input->post('sched_id');

        validate_submitted_data(array(
            "id" => "numeric",
            //"sched_id" => "required",
            "in_date" => "required",
            //"out_date" => "required",
            "in_time" => "required",
            //"out_time" => "required"
        ));

        //convert to 24hrs time format
        $in_time = $this->input->post('in_time');

        $first_start = $this->input->post('first_start');
        $first_start_time = $this->input->post('first_start_time');
        
        $first_end = $this->input->post('first_end');
        $first_end_time = $this->input->post('first_end_time');

        $lunch_start = $this->input->post('lunch_start');
        $lunch_start_time = $this->input->post('lunch_start_time');
        
        $lunch_end = $this->input->post('lunch_end');
        $lunch_end_time = $this->input->post('lunch_end_time');

        $second_start = $this->input->post('second_start');
        $second_start_time = $this->input->post('second_start_time');
        
        $second_end = $this->input->post('second_end');
        $second_end_time = $this->input->post('second_end_time');

        $out_time = $this->input->post('out_time');

        if (get_setting("time_format") != "24_hours") {
            $in_time = convert_time_to_24hours_format($in_time);
            $first_start_time = convert_time_to_24hours_format($first_start_time);
            $first_end_time = convert_time_to_24hours_format($first_end_time);
            $lunch_start_time = convert_time_to_24hours_format($lunch_start_time);
            $lunch_end_time = convert_time_to_24hours_format($lunch_end_time);
            $second_start_time = convert_time_to_24hours_format($second_start_time);
            $second_end_time = convert_time_to_24hours_format($second_end_time);
            $out_time = convert_time_to_24hours_format($out_time);
        }

        //join date with time
        $in_date_time = $this->input->post('in_date') . " " . $in_time;
        $first_start_time = $this->input->post('first_start') . " " . $first_start_time;
        $first_end_time = $this->input->post('first_end') . " " . $first_end_time;
        $lunch_start_time = $this->input->post('lunch_start') . " " . $lunch_start_time;
        $lunch_end_time = $this->input->post('lunch_end') . " " . $lunch_end_time;
        $second_start_time = $this->input->post('second_start') . " " . $second_start_time;
        $second_end_time = $this->input->post('second_end') . " " . $second_end_time;
        $out_date_time = $this->input->post('out_date') . " " . $out_time;

        //add time offset
        $in_date_time = convert_date_local_to_utc($in_date_time);
        $first_start_time = convert_date_local_to_utc($first_start_time);
        $first_end_time = convert_date_local_to_utc($first_end_time);
        $lunch_start_time = convert_date_local_to_utc($lunch_start_time);
        $lunch_end_time = convert_date_local_to_utc($lunch_end_time);
        $second_start_time = convert_date_local_to_utc($second_start_time);
        $second_end_time = convert_date_local_to_utc($second_end_time);
        $out_date_time = convert_date_local_to_utc($out_date_time);

        $break_time = [];
        if( empty($_POST['first_start']) || empty($_POST['first_start_time']) ) {
            $break_time[] = null;
        } else {
            $break_time[] = $first_start_time;
        }
        if( empty($_POST['first_end']) || empty($_POST['first_end_time']) ) {
            $break_time[] = null;
        } else {
            $break_time[] = $first_end_time;
        }
        if( empty($_POST['lunch_start']) || empty($_POST['lunch_start_time']) ) {
            $break_time[] = null;
        } else {
            $break_time[] = $lunch_start_time;
        }
        if( empty($_POST['lunch_end']) || empty($_POST['lunch_end_time']) ) {
            $break_time[] = null;
        } else {
            $break_time[] = $lunch_end_time;
        }
        if( empty($_POST['second_start']) || empty($_POST['second_start_time']) ) {
            $break_time[] = null;
        } else {
            $break_time[] = $second_start_time;
        }
        if( empty($_POST['second_end']) || empty($_POST['second_end_time']) ) {
            $break_time[] = null;
        } else {
            $break_time[] = $second_end_time;
        }

        if( empty($_POST['out_date']) || empty($_POST['out_time']) ) {
            $out_date_time = null;
        }

        $all_null = true;
        for( $i=0; $i<count($break_time); $i++) {
            if($break_time[$i] !== null) {
                $all_null = false;
            }
        }

        $data = array(
            "in_time" => $in_date_time,
            "out_time" => $out_date_time,
            "break_time" => $all_null?NULL:serialize($break_time),
            "note" => $this->input->post('note')
        );

        //save user_id only on insert and it will not be editable
        if ($id) {
            $info = $this->Attendance_model->get_one($id);
            $user_id = $info->user_id;
        } else {
            $data["status"] = "pending";
            $user_id = $this->input->post('user_id');
            $data["user_id"] = $user_id;
        }

        if ($sched_id) {
            $data['sched_id'] = $sched_id;
        } else {
            $data['sched_id'] = 0;
        }

        $this->access_only_allowed_members($user_id);

        $save_id = $this->Attendance_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'isUpdate' => $id ? true : false, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //edit attendance note
    function save_note() {
        $id = $this->input->post('id');

        validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $data = array(
            "note" => $this->input->post('note')
        );


        $save_id = $this->Attendance_model->save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'isUpdate' => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //clock in / clock out
    function log_time($user_id = 0) {
        $note = $this->input->post('note');

        if ($user_id && $user_id != $this->login_user->id) {
            //check if the login user has permission to clock in/out this user
            $this->access_only_allowed_members($user_id);
        }

        $this->Attendance_model->log_time($user_id ? $user_id : $this->login_user->id, $note);

        if ($user_id) {
            echo json_encode(array("success" => true, "data" => $this->_clock_in_out_row_data($user_id), 'id' => $user_id, 'message' => lang('record_saved'), "isUpdate" => true));
        } else if ($this->input->post("clock_out")) {
            echo json_encode(array("success" => true, "clock_widget" => clock_widget(true)));
        } else {
            clock_widget();
        }
    }

    //delete/undo attendance record
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        if ($this->access_type !== "all") {
            $info = $this->Attendance_model->get_one($id);
            $this->access_only_allowed_members($info->user_id);
        }

        if ($this->input->post('undo')) {
            if ($this->Attendance_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        } else {
            if ($this->Attendance_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
            }
        }
    }

    /* get all attendance of a given duration */

    function list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');
        $department_id = $this->input->post('department_id');

        $options = array(
            "start_date" => $start_date, 
            "end_date" => $end_date, 
            "login_user_id" => $this->login_user->id, 
            "user_id" => $user_id, 
            "department_id" => $department_id,
            "access_type" => $this->access_type, 
            "allowed_members" => $this->allowed_members,
            "active_only" => true, 
        );
        $list_data = $this->Attendance_model->get_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //load attendance attendance info view
    function attendance_info() {
        $view_data['user_id'] = $this->login_user->id;
        $view_data['show_clock_in_out'] = true;

        if ($this->input->is_ajax_request()) {
            $this->load->view("team_members/attendance_info", $view_data);
        } else {
            $view_data['page_type'] = "full";
            $this->template->rander("team_members/attendance_info", $view_data);
        }
    }

    //get a row of attendnace list
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Attendance_model->get_details($options)->row();
        return $this->_make_row($data);
    }

    //prepare a row of attendance list
    private function _make_row($data) {
        $image_url = get_avatar($data->created_by_avatar);
        $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->created_by_user";
        $option_links = modal_anchor(get_uri("hrs/attendance/modal_form"), "<i class='fa fa-pencil'></i>", array("class" => "edit", "title" => lang('edit_attendance'), "data-post-id" => $data->id))
                . js_anchor("<i class='fa fa-times fa-fw'></i>", array('title' => lang('delete_attendance'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("hrs/attendance/delete"), "data-action" => "delete"));

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

        $note_link = modal_anchor(get_uri("hrs/attendance/note_modal_form"), "<i class='fa fa-comment-o p10'></i>", array("class" => "edit text-muted", "title" => lang("note"), "data-post-id" => $data->id));
        if ($data->note) {
            $note_link = modal_anchor(get_uri("hrs/attendance/note_modal_form"), "<i class='fa fa-comment p10'></i>", array("class" => "edit text-muted", "title" => $note_title, "data-modal-title" => lang("note"), "data-post-id" => $data->id));
        }

        $sched_link = modal_anchor(get_uri("modal/notify"), "<i class='fa fa-circle-o p10'></i>", array("class" => "edit text-muted", "title" => lang("sys_msg"), "data-post-msg" => lang('no_sched') ));
        if ($data->sched_id) {
            $sched_link = modal_anchor(get_uri("hrs/schedule/modal_form/display"), "<i class='fa fa-clock-o p10'></i>", array("class" => "edit text-muted", "title" => lang("schedule"), "data-modal-title" => lang("schedule"), "data-post-id" => $data->sched_id));
        }
        $info_link = $note_link.$sched_link;

        //Get job info for computation of total hours.
        $attd = (new BioMeet($this, array(), true))
            ->addAttendance($data)
            ->calculate();

        //Get the break time.
        $btime = serialized_breaktime($data->break_time, '-');

        return array(
            get_team_member_profile_link($data->user_id, $user),
            $data->team_list,
            $data->in_time,
            format_to_date($data->in_time),
            format_to_time($data->in_time),
            $btime[0],$btime[1],$btime[2],$btime[3],$btime[4],$btime[5],
            $data->out_time ? $data->out_time : 0,
            $data->out_time ? format_to_date( $data->out_time ) : "-",
            $data->out_time ? format_to_time( $data->out_time ) : "-",
            $attd->getTotalDuration(),
            strval($attd->getTotalWork()), 
            strval($attd->getTotalOvertime()), 
            strval($attd->getTotalBonuspay()), 
            strval($attd->getTotalNightpay()), 
            strval($attd->getTotalLates()), 
            strval($attd->getTotalOverbreak()), 
            strval($attd->getTotalUndertime()),
            $info_link,
            $option_links
        );
    }

    //load the custom date view of attendance list 
    function custom() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        $view_data['department_select2'] = $this->_get_team_select2_data();
        $this->load->view("attendance/custom_list", $view_data);
    }

    //load the clocked in members list view of attendance list 
    function members_clocked_in() {
        $this->load->view("attendance/members_clocked_in");
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
            $where = array("status" => "active", "user_type" => "staff");
        } else {
            if (!count($this->allowed_members) && $type != "data") {
                $where = array("status" => "active", "user_type" => "nothing"); //don't show any users in dropdown
            } else {
                //add login user in dropdown list
                $allowed_members = $this->allowed_members;
                $allowed_members[] = $this->login_user->id;

                $where = array("status" => "active", "user_type" => "staff", "where_in" => ($type == "data") ? $allowed_members : array("id" => $allowed_members));
            }
        }

        return $where;
    }

    //load the custom date view of attendance list 
    function summary() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        $view_data['department_select2'] = $this->_get_team_select2_data();
        $this->load->view("attendance/summary_list", $view_data);
    }

    /* get all attendance of a given duration */

    function summary_list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');
        $department_id = $this->input->post('department_id');

        $options = array(
            "start_date" => $start_date, 
            "end_date" => $end_date, 
            "login_user_id" => $this->login_user->id, 
            "user_id" => $user_id, 
            "department_id" => $department_id,
            "access_type" => $this->access_type, 
            "allowed_members" => $this->allowed_members
        );
        $list_data = $this->Attendance_model->get_details($options)->result();

        $result = array();
        $list_temp = array();

        foreach ($list_data as $data) {
            if( is_date_exists($data->out_time) ) {

                $attd = (new BioMeet($this, array(), true))
                    ->addAttendance($data)
                    ->calculate();

                $image_url = get_avatar($data->created_by_avatar);
                $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->created_by_user";

                if( isset($list_temp[$data->user_id]) ) {
                    $list_temp[$data->user_id][2] += (double)$attd->getTotalDuration();
                    $list_temp[$data->user_id][3] += (double)$attd->getTotalWork();
                    $list_temp[$data->user_id][4] += (double)$attd->getTotalOvertime();
                    $list_temp[$data->user_id][5] += (double)$attd->getTotalBonuspay();
                    $list_temp[$data->user_id][6] += (double)$attd->getTotalNightpay();
                    $list_temp[$data->user_id][7] += (double)$attd->getTotalLates();
                    $list_temp[$data->user_id][8] += (double)$attd->getTotalOverbreak();
                    $list_temp[$data->user_id][9] += (double)$attd->getTotalUndertime();
                } else {
                    $list_temp[$data->user_id] = array();
                    $list_temp[$data->user_id][0] = get_team_member_profile_link($data->user_id, $user);
                    $list_temp[$data->user_id][1] = $data->team_list;
                    $list_temp[$data->user_id][2] = (double)$attd->getTotalDuration();
                    $list_temp[$data->user_id][3] = (double)$attd->getTotalWork();
                    $list_temp[$data->user_id][4] = (double)$attd->getTotalOvertime();
                    $list_temp[$data->user_id][5] = (double)$attd->getTotalBonuspay();
                    $list_temp[$data->user_id][6] = (double)$attd->getTotalNightpay();
                    $list_temp[$data->user_id][7] = (double)$attd->getTotalLates();
                    $list_temp[$data->user_id][8] = (double)$attd->getTotalOverbreak();
                    $list_temp[$data->user_id][9] = (double)$attd->getTotalUndertime();
                }
            }
        }

        foreach ($list_temp as $key => $item) {
            $row = array();
            $row[0] = $key;
            $row[1] = $item[0];
            $row[2] = $item[1];
            $row[3] = convert_seconds_to_time_format( $item[2] * 3600 ); //to hours
            $row[4] = strval($item[3]);
            $row[5] = strval($item[4]);
            $row[6] = strval($item[5]);
            $row[7] = strval($item[6]);
            $row[8] = strval($item[7]);
            $row[9] = strval($item[8]);
            $row[10] = strval($item[9]);
            $result[] = $row;
        }

        echo json_encode(array("data" => $result));
    }

    //load the attendance summary details tab
    function export() {
        $view_data['team_members_dropdown'] = json_encode($this->_get_members_dropdown_list_for_filter());
        $view_data['department_select2'] = $this->_get_team_select2_data();
        $this->load->view("attendance/export_list", $view_data);
    }

    /* get data the attendance summary details tab */

    function export_list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');
        $department_id = $this->input->post('department_id');

        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "login_user_id" => $this->login_user->id,
            "user_id" => $user_id,
            "department_id" => $department_id,
            "access_type" => $this->access_type,
            "allowed_members" => $this->allowed_members,
        );

        $list_data = $this->Attendance_model->get_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            if( is_date_exists($data->out_time) ) {

                $attd = (new BioMeet($this, array(), true))
                    ->addAttendance($data)
                    ->calculate();

                $image_url = get_avatar($data->created_by_avatar);
                $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->created_by_user";

                $result[] = array(
                    $data->user_id.".".$data->id,
                    get_team_member_profile_link($data->user_id, $user),
                    $data->team_list,
                    format_to_date($data->in_time),
                    $attd->getTotalDuration(),
                    strval($attd->getTotalWork()), 
                    strval($attd->getTotalOvertime()), 
                    strval($attd->getTotalBonuspay()), 
                    strval($attd->getTotalNightpay()), 
                    strval($attd->getTotalLates()), 
                    strval($attd->getTotalOverbreak()), 
                    strval($attd->getTotalUndertime()),
                );
            }
        }

        echo json_encode(array("data" => $result));
    }

    /* get clocked in members list */

    function clocked_in_members_list_data() {

        $options = array("login_user_id" => $this->login_user->id, "access_type" => $this->access_type, "allowed_members" => $this->allowed_members, "only_clocked_in_members" => true);
        $list_data = $this->Attendance_model->get_details($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //load the clock in / out tab view of attendance list 
    function clock_in_out() {
        $this->load->view("attendance/clock_in_out");
    }

    /* get data the attendance clock In / Out tab */

    function clock_in_out_list_data() {
        $options = $this->_get_members_query_options("data");
        $list_data = $this->Attendance_model->get_clock_in_out_details_of_all_users($options)->result();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_clock_in_out_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    private function _clock_in_out_row_data($user_id) {
        $options = array("id" => $user_id);
        $data = $this->Attendance_model->get_clock_in_out_details_of_all_users($options)->row();
        return $this->_make_clock_in_out_row($data);
    }

    private function _make_clock_in_out_row($data) {
        if (isset($data->attendance_id)) {
            $in_time = format_to_time($data->in_time);
            $in_datetime = format_to_datetime($data->in_time);
            $status = "<div class='mb15' title='$in_datetime'>" . lang('clock_started_at') . " : $in_time</div>";
            $view_data = modal_anchor(get_uri("hrs/attendance/note_modal_form/$data->id"), "<i class='fa fa-sign-out'></i> " . lang('clock_out'), array("class" => "btn btn-default", "title" => lang('clock_out'), "id" => "timecard-clock-out", "data-post-id" => $data->attendance_id, "data-post-clock_out" => 1, "data-post-id" => $data->id));
        } else {
            $status = "<div class='mb15'>" . lang('not_clocked_id_yet') . "</div>";
            $view_data = js_anchor("<i class='fa fa-sign-in'></i> " . lang('clock_in'), array('title' => lang('clock_in'), "class" => "btn btn-default", "data-action-url" => get_uri("hrs/attendance/log_time/$data->id"), "data-action" => "update", "data-inline-loader" => "1", "data-post-id" => $data->id));
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

/* End of file attendance.php */
/* Location: ./application/controllers/attendance.php */