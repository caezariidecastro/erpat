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
            exit();
        }

        //Check if this user id is 5 min last timein.


        $is_currently_clocked_in = $this->Attendance_model->log_time($userid, "System Generated", true);  
        echo json_encode( array(
            "success"=>true, 
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
}