<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->library('encryption');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization,Basic");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
    }

    function signin() {
        $email = $this->input->post('email');
        $pword = $this->input->post('pword');

        $user_id = $this->Users_model->authenticate($email, $pword, true);
        if (!$user_id && !is_integer($user_id)) {
            echo json_encode(array("success"=>false, "message"=>"Email or Password is incorrect!"));
            exit();
        }

        $info = $this->Users_model->get_baseinfo($user_id);
        $now = strtotime(get_current_utc_time());
        $span = TOKEN_EXPIRY; //12 hrs default
        $expiry = (int)$now + (int)$span;

        $user = array(
            "id" => $user_id,
            "uuid" => $info->uuid,
            "avatar" => get_avatar($user_id, true),
            "fullname" => $info->first_name ." ".$info->last_name,
            "email" => $email,
            "expired" => $expiry
        );
        $token = JWT::encode($user, ENCRYPTION);

        echo json_encode(array("success"=>true, "data"=> $token));
    }

    //Temporary get the user data.
    function get($user_id, $encoded = 'true', $isEcho = true, $generalOnly = true) {
        $instance = $this->Users_model->get_details(array("id"=>$user_id,"deleted"=>0))->row();
        $user_data = array(
            "fname" => $instance->first_name,
            "lname" => $instance->last_name,
            "email" => $instance->email,
            "avatar" => get_avatar($instance->image),
            "status" => $instance->status,
            "job" => $instance->job_title
        );

        if($instance == null) {
            return get_encode_where( array("success"=>false,"message"=>"User not found!"), $encoded, $isEcho );
        } else {
            $reqData = $generalOnly ? $user_data : $instance;
            return get_encode_where( array("success"=>true,"data"=>$reqData), $encoded, $isEcho );
        }
    }

    public static function validate($token) {
        if(empty($token )) {
            return array("success"=>false, "message"=> "You're token is invalid!");
        }
        
        $decoded = JWT::decode($token, ENCRYPTION);
        if($decoded) {
            return array("success"=>true, "data"=> array("token"=>(array)$decoded));
        } else {
            return array("success"=>false, "message"=> "You're token is tampered!");
        }
    }
}