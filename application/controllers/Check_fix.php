<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Check_fix extends MY_Controller
{
    function __construct() {
        parent::__construct();
        $this->access_only_admin();
        $this->load->model("Settings_model");
        $this->load->model("Users_model");
    }

    public function index()
    {
        $utc_datetime = get_setting("last_check_fix") ? format_to_datetime(get_setting("last_check_fix")) : "None";
        $view_data['last_check'] = $utc_datetime;
        $this->template->rander("settings/check_fix", $view_data);
    }

    public function execute()
    {
        //Execute all fixing here.
        $user_uuid = $this->add_uuidto_users();

        $utc_datetime = get_current_utc_time();
        $this->Settings_model->save_setting("last_check_fix", $utc_datetime);
        echo json_encode(array(
            "success"=>true,
            "current"=>format_to_datetime($utc_datetime),
            "message"=>"We updated $user_uuid users UUID."
        ));
    }

    protected function add_uuidto_users() {
        $users = $this->Users_model->get_users_without_uuid();

        foreach($users as $user) {
            $uuid = $this->uuid->v4();
            $this->Users_model->set_user_uuid($user->id, $uuid);
        }

        return count($users);
    }
}