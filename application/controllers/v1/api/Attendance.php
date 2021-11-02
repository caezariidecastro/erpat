<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once('Users.php');

class Attendance extends Users {
    
    function __construct() {
        parent::__construct(false);
    }

    function check() {
        //Check if currently in.
    }

    function logtime($userid) {
        $secret_key  = $this->input->get_request_header('Basic');

        $validation = self::validate($secret_key);
        if($validation['success'] !== true) {
            echo json_encode( array("success"=>false, "message"=>$validation['message'] ) );
            exit();
        }
        
        $user = $this->get($userid, false, false, false);
        $authId = $this->input->post('id');

        if($user['data']->status === 'inactive' || $user['data']->deleted === '1' || !user_has_permission($authId, 'attendance')) {
            echo json_encode( array("success"=>false, "message"=>"User is currently inactive/deleted or auth user dont have permission." ) );
            exit;
        }

        //Check if this user id is 60 seconds last timein.
        $checking = $this->check_if_now_allowed($userid);
        if($checking['success'] == false) {
            echo json_encode( $checking );
            exit;
        }

        $user = $this->Users_model->get_details(array("id"=>$userid,"deleted"=>0))->row();
        $user = array(
            "fname" => $user->first_name,
            "lname" => $user->last_name,
            "email" => $user->email,
            "avatar" => get_avatar($user->image),
            "status" => $user->status,
            "job" => $user->job_title
        );

        $is_currently_clocked_in = $this->Attendance_model->log_time($userid, "System Generated", true);  
        echo json_encode( array(
            "success"=>true, 
            "data"=>$user,
            "message"=>"Successfully clocked ".($is_currently_clocked_in ? "in." : "out."),
            "stamp"=>get_my_local_time(),
            "clocked"=>$is_currently_clocked_in,
        ));      
    }

    /* get clocked in members list */
    function clocked_in_list() {
        $options = array(
            "access_type" => "all", 
            "only_clocked_in_members" => true
        );
        $list_data = $this->Attendance_model->get_details($options)->result();

        foreach($list_data as $item) {
            $instance = $this->Users_model->get_details(array("id"=>$item->user_id,"deleted"=>0))->row();
            $item->fname = $instance->first_name;
            $item->lname = $instance->last_name;
            $item->avatar = get_avatar($instance->image);
        }
        echo json_encode(array("success" => true, "data" => $list_data));
    }

    protected function get_time_diff($start_date, $end_date) {
        $start_time  = strtotime($start_date);
        $end_time = strtotime($end_date);
        return $end_time - $start_time;
    }

    protected function check_if_now_allowed($userid) {
        $options = array(
            "user_id" => $userid,
            "access_type" => "all", 
            //"only_clocked_in_members" => true
        );
        $list_data = $this->Attendance_model->get_details($options)->result();

        //Let this pass as no attendance is found.
        if(count($list_data) == 0) {
            return array("success" => true);
            exit;
        }

        $timespan = 30;
        $end_date = get_current_utc_time();
        //Check if previously clocked in.
        if($list_data[0]->out_time == "") {
            $start_date = $list_data[0]->in_time;
            $countdown = $timespan - $this->get_time_diff($start_date, $end_date);

            if($countdown > 0) { 
                return array(
                    "success" => false,
                    "message" => "You're not allowed to clockout, you need to wait for ".$countdown." seconds since your last clocked in."
                );
                exit;
            }
            //return json_encode(array("message" => "clocked in: ".$mindiff ));
        } else { //clocked out
            $start_date = $list_data[0]->out_time;
            $countdown = $timespan - $this->get_time_diff($start_date, $end_date);

            if($countdown > 0) { 
                return array(
                    "success" => false,
                    "message" => "You're not allowed to clockin, you need to wait for ".$countdown." seconds since your last clocked out."
                );
                exit;
            }
            //echo json_encode(array("message" => "clocked out"));
        }
        
        return array("success" => true);
    }
}