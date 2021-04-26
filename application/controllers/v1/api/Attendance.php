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
        //TODO: Check if this user has credential to clockin/clockout a user.
        $user = $validation['data']['token'];

        $is_currently_clocked_in = $this->Attendance_model->log_time($userid, "Note sample!", true);  
        echo json_encode( array(
            "success"=>true, 
            "message"=>"Successfully clocked in.",
            "stamp"=>get_my_local_time(),
            "clocked"=>$is_currently_clocked_in
        ));      
    }
}