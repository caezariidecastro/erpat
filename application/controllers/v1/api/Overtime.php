<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once('Users.php');

class Overtime extends Users {
    
    function __construct() {
        parent::__construct(false);
        $this->load->model("Overtime_model");
    }

    function logtime($userid) {
        $user_token = self::validate(getBearerToken());
        if(!isset($user_token->id)) {
            echo json_encode( array("success"=>false, "message"=>"Client token is invalid or tampered!" ) );
            exit();
        }//$user_token->id

        //TODO: Check if client token has permission.

        //Get the user object from the target id.
        $user_id = $authId = $this->input->post('self') ? $user_token->id : $userid;
        $user = $this->get($user_id, false, false, false);
        if($user['data']->status === 'inactive' || $user['data']->deleted === '1') {
            echo json_encode( array("success"=>false, "message"=>"User is currently inactive/deleted or dont have permission." ) );
            exit;
        }

        //Check if this user id is 60 seconds last timein.
        $checking = $this->check_if_now_allowed($user_id);
        if($checking['success'] == false) {
            echo json_encode( $checking );
            exit;
        }

        $user = $this->Users_model->get_details(array("id"=>$user_id,"deleted"=>0))->row();
        $user = array(
            "fname" => $user->first_name,
            "lname" => $user->last_name,
            "email" => $user->email,
            "avatar" => get_avatar($user->image),
            "status" => $user->status,
            "job" => $user->job_title
        );

        $is_currently_clocked_in = $this->Overtime_model->log_time($user_id, "System Generated", true);  
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
        $user_token = self::validate(getBearerToken());
        if(!isset($user_token->id)) {
            echo json_encode( array("success"=>false, "message"=>"Client token is invalid or tampered!" ) );
            exit();
        }//$user_token->id
        
        $options = array(
            "access_type" => "all", 
            "only_clocked_in_members" => true
        );
        $list_data = $this->Overtime_model->get_details($options)->result();

        foreach($list_data as $item) {
            $instance = $this->Users_model->get_details(array("id"=>$item->user_id,"deleted"=>0))->row();
            $item->fname = $instance->first_name;
            $item->lname = $instance->last_name;
            $item->avatar = get_avatar($instance->image);
            $item->start_time = convert_date_utc_to_local($item->start_time, "Y-m-d g:i A");
        }
        
        echo json_encode(array("success" => true, "data" => $list_data));
    }

    function my_clocked_in_list() {        
        $user_token = self::validate(getBearerToken());
        if(!isset($user_token->id)) {
            echo json_encode( array("success"=>false, "message"=>"Client token is invalid or tampered!" ) );
            exit();
        }//$user_token->id

        $options = array(
            "access_type" => "all", 
            "start_date" => sub_day_to_datetime(get_current_utc_time(), 30),
            "end_date" => add_day_to_datetime(get_current_utc_time(), 1),
            "user_id" => $user_token->id
        );
        $list_data = $this->Overtime_model->get_details($options)->result();

        $list_response = [];
        foreach($list_data as $item) {
            if(isset($item->end_time) && !empty(isset($item->end_time))) {
                $list_item = new stdClass();
                $list_item->duration = convert_seconds_to_time_format(abs(strtotime($item->end_time) - strtotime($item->start_time)), true);
                $list_item->start_time = convert_date_utc_to_local($item->start_time, "Y-m-d g:i A");
                $list_item->end_time = convert_date_utc_to_local($item->end_time, "Y-m-d g:i A");
                $list_response[] = $list_item;
            }
        }

        $can_clockin = count($list_data) > 0 ? (isset($list_data[0]->end_time) ? false:true): false;
        $status_data = array(
            "local_time" => get_my_local_time(),
            "clocked_in" => $can_clockin ? convert_date_utc_to_local($list_data[0]->start_time) : false //from database todo.
        );

        echo json_encode(array("success" => true, "data" => $list_response, "status"=>$status_data));
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
        $list_data = $this->Overtime_model->get_details($options)->result();

        //Let this pass as no attendance is found.
        if(count($list_data) == 0) {
            return array("success" => true);
            exit;
        }

        $timespan = 30;
        $end_date = get_current_utc_time();
        //Check if previously clocked in.
        if($list_data[0]->end_time == "") {
            $start_date = $list_data[0]->start_time;
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
            $start_date = $list_data[0]->end_time;
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