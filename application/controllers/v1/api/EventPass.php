<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class EventPass extends CI_Controller {

	function __construct() {
       	parent::__construct();
        $this->load->library('encryption');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization, Basic");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
		
		$this->load->model("EventPass_model");
        $this->load->model("Users_model");
        $this->load->model("Email_templates_model");
    }

    function get_customer_select2_data() {
        $customers = $this->Users_model->get_details(array("user_type" => "customer"))->result();
        $consumer_list = array(array("id" => "", "text" => "-"));
        foreach ($customers as $key => $value) {
            $consumer_list[] = array("id" => $value->id, "text" => trim($value->first_name . " " . $value->last_name));
        }
        echo json_encode($consumer_list);
    }

    private function email($first_name, $last_name, $email, $phone, $seats, $remarks){
        $email_template = $this->Email_templates_model->get_final_template("event_pass");

        $parser_data["SIGNATURE"] = $email_template->signature;
        $parser_data["FIRST_NAME"] = $first_name;
        $parser_data["LAST_NAME"] = $last_name;
        $parser_data["PHONE_NUMBER"] = $phone;
        $parser_data["TOTAL_SEATS"] = $seats;
        $parser_data["REMARKS"] = $remarks;
        $parser_data["LOGO_URL"] = get_logo_url();

        $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);
        send_app_mail($email, $email_template->subject, $message);
    }
	
    public function reserve() {
		
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $phone = $this->input->post('phone');
        $email = $this->input->post('email');
        $seats = $this->input->post('seat');
        $vcode = $this->input->post('vcode');
        $remarks = $this->input->post('remarks');

        if(empty($first_name) || empty($last_name) || empty($phone) || empty($email) || empty($seats)) {
            echo json_encode(array("success"=>false, "message"=>"Please complete all required fieldss."));
            exit;
        }

        if((int)$seats > 5) {
            echo json_encode(array("success"=>false, "message"=>"Maximum of 5 seats only."));
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            echo json_encode(array("success"=>false, "message"=>"Email address is invalid."));
            exit;
        }

        //Structure user data
        $user_data = array(
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "phone" => $phone,
            "user_type" => 'customer',
        );

        
        if ($this->Users_model->is_email_exists($email) === false) {
            $user_data["uuid"] = $this->uuid->v4();
            $user_data["disable_login"] = 1;
            $user_data["password"] = password_hash($password, PASSWORD_DEFAULT);
            $user_data["created_at"] = get_current_utc_time();

            $this->Users_model->save($user_data);
        }

        //TODO: Get the event id.
        $event_id = 0;

        //Check if customer email is registered.
        $cur_user = $this->Users_model->get_details(array("search" => $email))->row();
        if($cur_user) {
            $epass_data = array(
                "uuid" => $this->uuid->v4(),
                "event_id" => $event_id,
                "user_id" => $cur_user->id,
                "seats" => $seats,
                "remarks" => $remarks,
                "timestamp" => get_current_utc_time()
            );

            //Check if there is already reserve a seat.
            $current_pass = $this->EventPass_model->get_details(array(
                "user_id" => $cur_user->id,
                "event_id" => $event_id
            ))->row();
            //save if not found
            if(!$current_pass) {
                $this->EventPass_model->save($epass_data);
                $this->email($first_name, $last_name, $email, $phone, $seats, $remarks);
            }

            //get again for processing
            $latest_pass = $this->EventPass_model->get_details(array(
                "user_id" => $cur_user->id,
                "event_id" => $event_id
            ))->row();

            //TODO: Send email about ticket reservation.

            echo json_encode(array("success" => true, 'data' => array(
                "uuid" => $latest_pass->uuid,
                "existing" => $current_pass?true:false,
                "pass" => $latest_pass,
                "qrbase64" => "test"
            )));
        } else {
            echo json_encode(array("success" => false, 'message' => 'Contact technical support.'));
        }
    }
}
