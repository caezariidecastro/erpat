<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once('Users.php');

class Advisories extends Users {
    
    function __construct() {
        parent::__construct(false);
    }

    function listdata() {
        $user_token = self::validate(getBearerToken());
        if(!isset($user_token->id)) {
            echo json_encode( array("success"=>false, "message"=>"Client token is invalid or tampered!", "stamp"=>get_my_local_time() ) );
            exit();
        }//$user_token->id
        
        $options = array();
        //TODO: Implement this role advisy base.
        // if ($this->access_type !== "all") {
        //     if ($this->login_user->user_type === "staff") {
        //         $options["share_with"] = "all_members";
        //     } else if ($this->login_user->user_type === "client") {
        //         $options["share_with"] = "all_clients";
        //     } else {
        //         $options["share_with"] = "none";
        //     }
        // }

        $result = $this->Announcements_model->get_details($options)->result();
        $list_data = array();
        foreach ($result as $data) {
            $list_data[] = array(
                "id" => $data->id,
                "title" => $data->title,
                "description" => $data->description,
                "start_date" => $data->start_date,
                "end_date" => $data->end_date,
                "read" => in_array($user_token->id, explode(",", $data->read_by)),
            );
        }
        echo json_encode(array("success" => true, "data" => $list_data, "stamp"=>get_my_local_time()));
    }

    function read() {
        $user_token = self::validate(getBearerToken());
        if(!isset($user_token->id)) {
            echo json_encode( array("success"=>false, "message"=>"Client token is invalid or tampered!" ) );
            exit();
        }//$user_token->id
        
        $id = $this->input->post('id');
        if ($id) {
            //show only the allowed announcement
            $options = array("id" => $id);

            $options = $this->_prepare_access_options($options);

            $announcement = $this->Announcements_model->get_details($options)->row();
            
            if($announcement) {
                $data = array(
                    "id" => $announcement->id,
                    "title" => $announcement->title,
                    "description" => $announcement->description,
                    "start_date" => $announcement->start_date,
                    "end_date" => $announcement->end_date,
                    "read" => in_array($user_token->id, explode(",", $announcement->read_by)),
                );
                $this->Announcements_model->mark_as_read($id, $this->login_user->id);
                echo json_encode( array("success"=>true, "data"=>$data ) );
                exit();
            }
        }
            
        echo json_encode( array("success"=>false, "message"=>"Something went wrong!" ) );
    }
}