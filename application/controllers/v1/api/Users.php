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

    function verify() {
        $user_token = self::validate(getBearerToken());
        if(!isset($user_token->id)) {
            echo json_encode( array("success"=>false, "message"=>"Client token is invalid or tampered!" ) );
            exit();
        }

        echo json_encode( array("success"=>true, "data"=>$user_token ) );
    }

    function permissions() {
        $user_token = self::validate(getBearerToken());
        if(!isset($user_token->id)) {
            echo json_encode( array("success"=>false, "message"=>"Client token is invalid or tampered!" ) );
            exit();
        }//$user_token->id

        //Check if has permission
        $userData = self::getUserData($user_token->id);
        $userData->is_admin = $userData->is_admin?true:false;

        echo json_encode( array("success"=>true, "admin"=>$userData->is_admin, "data"=>$userData->permissions ) );
    }

    function listall() {
        $user_token = self::validate(getBearerToken());
        if(!isset($user_token->id)) {
            echo json_encode( array("success"=>false, "message"=>"Client token is invalid or tampered!" ) );
            exit();
        }

        $listall = $this->Users_model->get_details(array(
            "user_type" => "staff",
            "deleted" => 0
        ))->result();

        foreach($listall as $current) {
            $job = $this->Users_model->get_job_info($current->id);
            $current->contri_sss = $job->sss;
            $current->contri_tin = $job->tin;
            $current->contri_pagibig = $job->pag_ibig;
            $current->contri_phealth = $job->phil_health;

            $current->contact_name = $job->contact_name;
            $current->contact_address = $job->contact_address;
            $current->contact_phone = $job->contact_phone;

            $current->signiture = $job->signiture_url;
            $current->photo = get_avatar($current->image);
            $current->job_idnum = $job->job_idnum;
            $current->blood_type = $current->ssn;
        }

        echo json_encode( array("success"=>true, "data"=>$listall ) );
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

    private function getUserData($user_id) {
        $login_user = $this->Users_model->get_access_info($user_id);
        if ($login_user->permissions) {
            $permissions = unserialize($login_user->permissions);
            $login_user->permissions = is_array($permissions) ? $permissions : array();
        } else {
            $login_user->permissions = array();
        }
        return $login_user;
    }

    public static function validate($token) {
        if(empty($token )) {
            return false;
        }
        
        $decoded = JWT::decode($token, ENCRYPTION);
        if($decoded) {
            return $decoded;
        } else {
            return false;
        }
    }
}