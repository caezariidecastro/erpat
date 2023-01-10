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

    function log_access($api_key) {

        $user_id = $this->input->get('user_id');
        $remarks = $this->input->get('remarks'); //entry or exit
        $api_secret = $this->input->get('api_secret');

        if( empty($api_key) || empty($user_id) || empty($api_secret) ) {
            echo json_encode(array("success" => false, 'message' => lang('something_went_wrong')." Error: Request incomplete."));
            return;
        }

        //Load all the required modules.
        $this->load->model('Access_logs_model');
        $this->load->model('Access_devices_model');

        //Check if the api key and secret if valid.
        $device_option = array(
            'api_key' => strtolower($api_key),
        );
        $device = $this->Access_devices_model->get_details($device_option)->row();

        if(!isset($device->id) && $device->api_secret != $api_secret) {
            echo json_encode(array("success" => false, 'message' => lang('something_went_wrong')." Error: Device unauthorized."));
            return;
        }

        //Validate the user if has access to this device.
        $pass_list = explode(",", $device->passes);
        if(!in_array($user_id, $pass_list) ) {
            echo json_encode(array("success" => false, 'message' => lang('something_went_wrong')." Error: User dont have access."));
            return;
        }

        $data = array(
            "device_id" => $device->id,
            "user_id" => $user_id,
            "remarks" => $remarks,
            "timestamp" => get_current_utc_time(),
        );
        $data = clean_data($data);

        $save_id = $this->Access_logs_model->save($data);
        if ($save_id) {
            echo json_encode(array("success" => true, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('something_went_wrong')." Error: System failure."));
            
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
            "search" => $this->input->post('search'),
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

    function changepass() {
        $user_token = self::validate(getBearerToken());
        if(!isset($user_token->id)) {
            echo json_encode( array("success"=>false, "message"=>"Client token is invalid or tampered!" ) );
            exit();
        }

        $oldpass = $this->input->post('oldpass');
        $newpass = $this->input->post('newpass');
        $confirmpass = $this->input->post('confirmpass');

        if(empty($oldpass) || empty($newpass) || empty($confirmpass)) {
            echo json_encode( array("success"=>false, "message"=>"Incomplete inputs submitted!" ) );
            exit();
        }

        if($newpass !== $confirmpass) {
            echo json_encode( array("success"=>false, "message"=>"New password and confirm password is not the same." ) );
            exit();
        }

        if($oldpass === $newpass) {
            echo json_encode( array("success"=>false, "message"=>"The old and new password is the same." ) );
            exit();
        }

        $success = $this->Users_model->changepass($user_token->id, $oldpass, $newpass);
        if($success) {
            echo json_encode( array("success"=>true, "message"=>"You've successfully changed your password!") );
        } else {
            echo json_encode( array("success"=>true, "message"=>"Old password provided is incorrect!") );
        }
    }

    function get_user_info() {
        $user_token = self::validate(getBearerToken());
        if(!isset($user_token->id)) {
            echo json_encode( array("success"=>false, "message"=>"Client token is invalid or tampered!" ) );
            exit();
        }

        $user_info = $this->getUserData($user_token->id);
        echo json_encode(array("success"=>true, "data"=> $user_info));
    }

    function count_notification() {
        $user_token = self::validate(getBearerToken());
        if(!isset($user_token->id)) {
            echo json_encode( array("success"=>false, "message"=>"Client token is invalid or tampered!" ) );
            exit();
        }

        $this->load->model('Notifications_model');
        $count = $this->Notifications_model->count_notifications($user_token->id);

        echo json_encode( array("success"=>true, "data"=>$count) );
    }

    private function getUserData($user_id) {
        $login_user = $this->Users_model->get_access_info($user_id);
        if ($login_user->permissions) {
            $permissions = unserialize($login_user->permissions);
            $login_user->permissions = is_array($permissions) ? $permissions : array();
        } else {
            $login_user->permissions = array();
        }
        $login_user->image = get_avatar($login_user->image);
        unset($login_user->client_id);
        unset($login_user->is_primary_contact);
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