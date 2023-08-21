<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attendance extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->with_module("attendance", "redirect");
        $this->with_permission("attendance", "redirect");
        $this->access_only_team_members();
        
        $this->load->model("Attendance_model");
        $this->load->model("Schedule_model");
        $this->load->model("Users_model");

        $this->load->helper("biometric");

        $this->init_permission_checker("attendance");
    }

    protected function _get_team_select2_data() {
        $teams = $this->Team_model->get_details()->result();
        $team_select2 = array(array('id' => '', 'text'  => '- Departments -'));

        foreach($teams as $team){
            $team_select2[] = array('id' => $team->id, 'text'  => $team->title);
        }

        return $team_select2;
    }

    

    //show attendance list view
    function index() {
        $view_data['team_members_dropdown'] = json_encode($this->get_users_select2_dropdown());
        $view_data['department_select2'] = $this->_get_team_select2_data();
        $view_data['allowed_create'] = $this->with_permission("attendance_create");
        $this->template->rander("attendance/index", $view_data);
    }

    //show add/edit attendance modal
    function modal_form() {
        $user_id = 0;

        validate_submitted_data(array(
            "id" => "numeric"
        ));
        $view_data['status'] = $this->input->post('status');

        $view_data['time_format_24_hours'] = get_setting("time_format") == "24_hours" ? true : false;
        $model_info = $this->Attendance_model->get_one($this->input->post('id'));
        $model_info->break_time = serialized_breaktime($model_info->break_time, '', false, false);

        $view_data['model_info'] = $model_info;
        if ($view_data['model_info']->id) {
            $user_id = $view_data['model_info']->user_id;
        }

        if ($user_id) {
            $this->with_permission("attendance_update", "no_permission");

            //edit mode. show user's info
            $view_data['team_members_info'] = $this->Users_model->get_one($user_id);
        } else {
            $this->with_permission("attendance_create", "no_permission");

            //new add mode. show users dropdown
            //don't show none allowed members in dropdown
            $view_data['team_members_dropdown'] = $this->get_users_select2_filter("select_user", "attendance");
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

        $view_data['can_update'] = $this->with_permission("attendance_update");
        $view_data['model_info'] = $this->Attendance_model->get_one($this->input->post('id'));
        $this->load->view('attendance/note_modal_form', $view_data);
    }

    //add/edit attendance record
    function save() {
        validate_submitted_data(array(
            "id" => "numeric",
            //"sched_id" => "required",
            "in_date" => "required",
            //"out_date" => "required",
            "in_time" => "required",
            //"out_time" => "required"
            "log_type" => "required",
        ));

        $id = $this->input->post('id');
        $log_type = $this->input->post('log_type');
        $sched_id = $this->input->post('sched_id');
        $status = $this->input->post('status');

        if( $id ) {
            $this->with_permission("attendance_update", "no_permission");
        } else {
            $this->with_permission("attendance_create", "no_permission");
        }

        $cur_sched = $this->Schedule_model->get_details(array(
            "id" => $sched_id,
        ))->row();

        if($log_type === "schedule" && (empty($sched_id) || !isset($cur_sched->id)) ) { 
            echo json_encode(array("success" => false, 'message' => lang('no_schedule')));
            exit;
        }

        //convert to 24hrs time format
        $in_time = $this->input->post('in_time');
        $out_time = $this->input->post('out_time');
        if (get_setting("time_format") != "24_hours") {
            $in_time = convert_time_to_24hours_format($in_time);
            $out_time = convert_time_to_24hours_format($out_time);
        }
        $in_date_time = $this->input->post('in_date') . " " . $in_time;
        $out_date_time = $this->input->post('out_date') . " " . $out_time;
        $in_date_time = convert_date_local_to_utc($in_date_time);
        $out_date_time = convert_date_local_to_utc($out_date_time);
        if( empty($_POST['out_date']) || empty($_POST['out_time']) ) {
            $out_date_time = null;
        }

        $break_time = [];
        foreach([1,2,3,4,5,6,7,8] as $index) {
            $date = $this->input->post($index.'-date');
            $time = $this->input->post($index.'-time');

            if (get_setting("time_format") != "24_hours") {
                $time = convert_time_to_24hours_format($time);
            }

            $date_time = $date . " " . $time;
            $utc_datetime = convert_date_local_to_utc($date_time);

            if( empty($date) || empty($time) ) {
                $break_time[] = null;
            } else {
                $break_time[] = $utc_datetime;
            }
        }

        $data = array(
            "in_time" => $in_date_time,
            "log_type" => $log_type,
            "out_time" => $out_date_time,
            "break_time" => serialize($break_time),
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

        if( !isset($out_date_time) ) {
            $data['status'] = "incomplete";
        } else if($status) { //override
            $data['status'] = $status;
        }

        if ($sched_id) {
            $data['sched_id'] = $sched_id;
        } else {
            $data['sched_id'] = 0;
        }
        
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

        if ($user_id && $user_id !== $this->login_user->id) {
            $this->with_permission("attendance_update", "no_permission");
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

    function log_breaktime($user_id = 0) {

        if ($user_id && $user_id != $this->login_user->id) {
            //check if the login user has permission to clock in/out this user
        }

        $success = $this->Attendance_model->log_break($user_id ? $user_id : $this->login_user->id);

        if ($user_id) {
            echo json_encode(array("success" => true, "data" => $this->_clock_in_out_row_data($user_id), 'id' => $user_id, 'message' => lang('record_saved'), "isUpdate" => true));
        } else if ($this->input->post("clock_out")) {
            echo json_encode(array("success" => true, "clock_widget" => clock_widget(true)));
        } else {
            if(!$success) {
                $break_error = lang("all_breaktime_consumed");
            }
            clock_widget(false, $break_error);
        }
    }

    //delete/undo attendance record
    function delete() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));
        $id = $this->input->post('id');

        $this->with_permission("attendance_delete", "no_permission");

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
                echo json_encode(array("success" => false, 'message' => lang('not_permitted')));
            }
        }
    }

    /* get all attendance of a given duration */

    function list_data() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->input->post('user_id');
        $department_id = $this->input->post('department_id');
        $log_type = $this->input->post('log_type');
        $status = $this->input->post('status');

        $options = array(
            "start_date" => $start_date, 
            "end_date" => $end_date, 
            "login_user_id" => $this->login_user->id, 
            "user_id" => $user_id, 
            "department_id" => $department_id,
            "log_type" => $log_type,
            "status" => $status,
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

    private function _prepare_log_info($data) {
        $image_url = get_avatar($data->applicant_avatar);
        $data->applicant_meta = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span>" . $data->applicant_name;

        if ($data->status === "pending") {
            $status_class = "label-warning";
        } else if ($data->status === "approved") {
            $status_class = "label-success";
        } else if ($data->status === "rejected") {
            $status_class = "label-danger";
        } else if ($data->status === "clockout") {
            $status_class = "label-info";
        } else if ($data->status === "incomplete") {
            $status_class = "label-default";
        } else {
            $status_class = "label-default";
        }
        $data->status_meta = "<span class='label $status_class'>" . lang($data->status) . "</span>";

        if (isset($data->in_time)) {
            $data->start_date_meta = convert_date_utc_to_local($data->in_time, "h:i a - Y-m-d");
            $data->end_date_meta = convert_date_utc_to_local($data->out_time, "h:i a - Y-m-d");
        }

        $duration = $data->out_time?strtotime($data->out_time)-strtotime($data->in_time):0;
        $data->duration_meta = convert_seconds_to_time_format($duration);

        $data->log_type_meta = $data->log_type==="schedule"?"Scheduled":"Overtime";

        $options = array("id" => $data->id);
        $data_info = $this->Attendance_model->get_details($options)->row();

        //Get job info for computation of total hours.
        $attd = (new BioMeet($this, array(), true))
            ->addAttendance($data_info)
            ->calculate();

        $data->worked_meta = $attd->getTotalWork();
        $data->overtime_meta = $attd->getTotalOvertime();
        $data->night_meta = $attd->getTotalNightDiff();
        $data->lates_meta = $attd->getTotalLates();
        $data->overbreak_meta = $attd->getTotalOverbreak();
        $data->undertime_meta = $attd->getTotalUndertime();
        
        $data->checked_date = convert_date_utc_to_local($data->checked_at, "d/m/Y");
        return $data;
    }

    function log_details() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $user_id = $this->input->post('id');
        $info = $this->Attendance_model->get_details_info($user_id);
        if (!$info) {
            show_404();
        }

        $view_data['can_approve'] = $this->with_permission("attendance_update");
        $view_data['model_info'] = $this->_prepare_log_info($info);
        $this->load->view("attendance/log_details", $view_data);
    }

    function update_status() {
        validate_submitted_data(array(
            "id" => "required|numeric",
            "status" => "required"
        ));
        $this->with_permission("attendance_update", "no_permission");

        $log_id = $this->input->post('id');
        $status = $this->input->post('status');
        $now = get_current_utc_time();

        $log_data = array(
            "checked_by" => $this->login_user->id,
            "checked_at" => $now,
            "status" => $status
        );

        $save_id = $this->Attendance_model->save($log_data, $log_id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    //prepare a row of attendance list
    private function _make_row($data) {
        $image_url = get_avatar($data->created_by_avatar);
        $user = "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span> $data->created_by_user";

        //if the rich text editor is enabled, don't show the note as title
        $note_title = $data->note;
        if (get_setting('enable_rich_text_editor')) {
            $note_title = "";
        }

        //Get job info for computation of total hours.
        $attd = (new BioMeet($this, array(), true))
            ->addAttendance($data)
            ->calculate();

        if ($data->status === "pending") {
            $status_class = "label-warning";
        } else if ($data->status === "approved") {
            $status_class = "label-success";
        } else if ($data->status === "rejected") {
            $status_class = "label-danger";
        } else if ($data->status === "clockout") {
            $status_class = "label-info";
        } else if ($data->status === "incomplete") {
            $status_class = "label-default";
        } else {
            $status_class = "label-default";
        }
        $data->status_meta = "<span class='label $status_class'>" . lang($data->status) . "</span>";

        $sched_meta = '<li role="presentation" style="list-style: none;">' . modal_anchor(get_uri("hrs/schedule/modal_form/display"), $data->schedule_name, array("class" => "", "title" => "", "data-modal-title" => lang("schedule_detail"), "data-toggle" => "tooltip", "data-placement" => "top", "title"=>$data->schedule_info, "data-post-id" => $data->sched_id)) . '</li>';
        
        if($data->log_type == "schedule" && !$data->sched_id) {
            $sched_meta = "Invalid";
        }

        if($data->log_type == "overtime") {
            if($data->sched_id) {
                $sched_meta = "Regular OT (Override)";
            } else {
                $sched_meta = "Restday OT (Override)";
            }
        }

        $response = array(
            get_team_member_profile_link($data->user_id, $user),
            $data->team_list,
            $sched_meta,
            $data->in_time,
            format_to_date($data->in_time),
            format_to_time($data->in_time)
        );
        
        $response = array_merge($response, array(
            $data->out_time ? $data->out_time : 0,
            $data->out_time ? format_to_date( $data->out_time ) : "-",
            $data->out_time ? format_to_time( $data->out_time ) : "-",
            $attd->getTotalDuration(),
        ));

        $response = array_merge($response, array(
            strval($attd->getTotalWork()), 
            strval($attd->getTotalRegularOvertime()), 
            strval($attd->getTotalRestdayOvertime()), 
        ));

        $response = array_merge($response, array(
            strval($attd->getTotalBonus())
        ));

        $response = array_merge($response, array(
            strval($attd->getTotalNightDiff()), 
            strval($attd->getTotalLates()), 
            strval($attd->getTotalOverbreak()), 
            strval($attd->getTotalUndertime())
        ));

        if( $this->with_permission("attendance_update") ) {
            $info = '<li role="presentation">' . modal_anchor(get_uri("hrs/attendance/log_details"), "<i class='fa fa-".($data->status==="pending"?"bolt":"info")." p10'></i>".lang(($data->status==="pending"?"approval":"detail")), array("class" => "", "title" => lang(($data->status==="pending"?"approval":"detail")), "data-post-id" => $data->id)) . '</li>';

            //TODO: Have this on settings.
            //if($data->status==="incomplete" || $data->status==="pending" || $data->status==="clockout") {
                $edit = '<li role="presentation">' . modal_anchor(get_uri("hrs/attendance/modal_form"), "<i class='fa fa-pencil p10'></i> ".lang("edit_attendance"), array("class" => "", "title" => lang('edit_attendance'), "data-post-id" => $data->id, "data-post-status" => "pending" )) . '</li>';
            //}
        }

        $note = '<li role="presentation">' . modal_anchor(get_uri("hrs/attendance/note_modal_form"), "<i class='fa fa-comment-o p10'></i> ".lang("note"), array("class" => "", "title" => lang("note"), "data-post-id" => $data->id));
        if ($data->note) {
            $note = '<li role="presentation">' . modal_anchor(get_uri("hrs/attendance/note_modal_form"), "<i class='fa fa-comment p10'></i> ".lang("note"), array("class" => "", "title" => $note_title, "data-modal-title" => lang("note"), "data-post-id" => $data->id)) . '</li>';
        }

        if( $this->with_permission("attendance_delete") ) {
            $delete = '<li role="presentation">' . js_anchor("<i class='fa fa-times p10'></i> ".lang('delete'), array('title' => lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("hrs/attendance/delete"), "data-action" => "delete")) . '</li>';
        }

        if ($this->access_type != "all") {
            //don't show options links for none admin user's own records
            if ($data->user_id === $this->login_user->id) {
                $option_links = "";
            }
        }

        if(!$this->login_user->is_admin && get_date_difference_in_days($data->in_time, get_my_local_time("Y-m-d")) >= get_setting("days_locked_attendance", 7)) {
            $info = "";
            $note = "";
            $edit = "";
            $delete = "";
            $locked = '<li role="presentation" style="padding: 5px; text-align: center;"><i class="fa fa-lock" aria-hidden="true"></i> <label>' . lang("attendance_locked") . '</label></li>';
        }

        $option_links = '<span class="dropdown inline-block">
                        <button class="btn btn-default dropdown-toggle  mt0 mb0" type="button" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-cogs"></i>&nbsp;
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">' . $locked . $info . $note . $edit . $delete . '</ul>
                    </span>';
            
        $response = array_merge($response, array(
            $data->status_meta,
            $option_links
        ));

        return $response;
    }

    //load the custom date view of attendance list 
    function custom() {
        $view_data['team_members_dropdown'] = json_encode($this->get_users_select2_dropdown());
        $view_data['department_select2'] = $this->_get_team_select2_data();
        $this->load->view("attendance/custom_list", $view_data);
    }

    //load the clocked in members list view of attendance list 
    function members_clocked_in() {
        $this->load->view("attendance/members_clocked_in");
    }

    //load the custom date view of attendance list 
    function summary() {
        $view_data['team_members_dropdown'] = json_encode($this->get_users_select2_dropdown());
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
                    $list_temp[$data->user_id][4] += (double)$attd->getTotalRegularOvertime();
                    $list_temp[$data->user_id][5] += (double)$attd->getTotalRestdayOvertime();
                    $list_temp[$data->user_id][6] += (double)$attd->getTotalBonus();
                    $list_temp[$data->user_id][7] += (double)$attd->getTotalNightDiff();
                    $list_temp[$data->user_id][8] += (double)$attd->getTotalLates();
                    $list_temp[$data->user_id][9] += (double)$attd->getTotalOverbreak();
                    $list_temp[$data->user_id][10] += (double)$attd->getTotalUndertime();
                } else {
                    $list_temp[$data->user_id] = array();
                    $list_temp[$data->user_id][0] = get_team_member_profile_link($data->user_id, $user);
                    $list_temp[$data->user_id][1] = $data->team_list;
                    $list_temp[$data->user_id][2] = (double)$attd->getTotalDuration();
                    $list_temp[$data->user_id][3] = (double)$attd->getTotalWork();
                    $list_temp[$data->user_id][4] += (double)$attd->getTotalRegularOvertime();
                    $list_temp[$data->user_id][5] += (double)$attd->getTotalRestdayOvertime();
                    $list_temp[$data->user_id][6] = (double)$attd->getTotalBonus();
                    $list_temp[$data->user_id][7] = (double)$attd->getTotalNightDiff();
                    $list_temp[$data->user_id][8] = (double)$attd->getTotalLates();
                    $list_temp[$data->user_id][9] = (double)$attd->getTotalOverbreak();
                    $list_temp[$data->user_id][10] = (double)$attd->getTotalUndertime();
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
            $row[11] = strval($item[10]);
            $result[] = $row;
        }

        echo json_encode(array("data" => $result));
    }

    //load the attendance summary details tab
    function export() {
        $view_data['team_members_dropdown'] = json_encode($this->get_users_select2_dropdown());
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
                    strval($attd->getTotalBonus()), 
                    strval($attd->getTotalNightDiff()), 
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
        $this->with_permission("attendance_create", "no_permission");
        $this->load->view("attendance/clock_in_out");
    }

    /* get data the attendance clock In / Out tab */

    function clock_in_out_list_data() {
        $allowed_members = $this->get_allowed_users_only("attendance");
        $allowed_members[] = $this->login_user->id;
        $where = array("status" => "active", "user_type" => "staff", "where_in" => $allowed_members);
        
        $list_data = $this->Attendance_model->get_clock_in_out_details_of_all_users($where)->result();

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
            $logtype = $data->log_type==="schedule"?"Scheduled":"Overtime";
            $status = "<div class='mb15' title='$in_datetime'>" . lang('clock_started_at') . " : $in_time ($logtype)</div>";
            
            if( $this->with_permission("attendance_update") ) {
                $view_data = modal_anchor(get_uri("hrs/attendance/note_modal_form/$data->id"), "<i class='fa fa-sign-out'></i> " . lang('clock_out'), array("class" => "btn btn-default", "title" => lang('clock_out'), "id" => "timecard-clock-out", "data-post-id" => $data->attendance_id, "data-post-clock_out" => 1, "data-post-id" => $data->id));
            }
        } else {
            $status = "<div class='mb15'>" . lang('not_clocked_id_yet') . "</div>";
            
            if( $this->with_permission("attendance_create") ) {
                $view_data = js_anchor("<i class='fa fa-sign-in'></i> " . lang('clock_in'), array('title' => lang('clock_in'), "class" => "btn btn-default", "data-action-url" => get_uri("hrs/attendance/log_time/$data->id"), "data-action" => "update", "data-inline-loader" => "1", "data-post-id" => $data->id));
            }
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