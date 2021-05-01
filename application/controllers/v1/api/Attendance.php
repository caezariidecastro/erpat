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

        $is_currently_clocked_in = $this->Attendance_model->log_time($userid, "System Generated", true);  
        echo json_encode( array(
            "success"=>true, 
            "message"=>"Successfully clocked in.",
            "stamp"=>get_my_local_time(),
            "clocked"=>$is_currently_clocked_in,
        ));      
    }
}