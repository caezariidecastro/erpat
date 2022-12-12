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
        $this->load->model("Expense_categories_model");
        $this->load->model("EventPass_model");
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
        $user_uuid = $this->add_uuid_to_users();
        $secured = $this->secure_expense_defaults();
        $emails = $this->generate_new_email_templates();

        $utc_datetime = get_current_utc_time();
        $this->Settings_model->save_setting("last_check_fix", $utc_datetime);
        echo json_encode(array(
            "success"=>true,
            "current"=>format_to_datetime($utc_datetime),
            "data" => array(
                "user_uuid" => $user_uuid,
                "expense_default" => $secured,
                "email_templates" =>$emails
            ),
            "message"=>"Added $user_uuid users UUID, secured $secured expense categories, and $emails email templates updated."
        ));
    }

    protected function add_uuid_to_users() {
        $users = $this->Users_model->get_users_without_uuid();

        foreach($users as $user) {
            $uuid = $this->uuid->v4();
            $this->Users_model->set_user_uuid($user->id, $uuid);
        }

        return count($users);
    }

    protected function secure_expense_defaults() {
        $expense_categories = $this->Expense_categories_model->get_default_expense_categories();

        foreach($expense_categories as $expense_category) {
            $this->Expense_categories_model->secure_default($expense_category->id);
        }

        return count($expense_categories);
    }

    protected function generate_new_email_templates() {    
        $total = 0;    
        if($this->EventPass_model->save_email()) {
            $total += 1;
        }
        return $total;
    }
}