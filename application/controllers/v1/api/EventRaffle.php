<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class EventRaffle extends CI_Controller {

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
        $this->load->model("Raffle_draw_model");

        $this->load->helper('utility');
    }

    public function list_raffle() {
        
        $filter = array(
            "status" => "active"
        );

        $eventid = $this->input->post('eventid');
        if( $eventid ) {
            $filter["event_id"] = $eventid;
        }

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        if( $start_date && $end_date ) {
            $filter["start"] = $start_date;
            $filter["end"] = $end_date;
        }

        $raffles = $this->Raffle_draw_model->get_details($filter)->result();
        echo json_encode( array("data"=>$raffles) );
    }

    public function load_raffle() {
        $id = $this->input->post('id');

        $filter_raffle = array(
            "id" => $id
        );
        $raffle = $this->Raffle_draw_model->get_details($filter_raffle)->row();

        $filter_winner = array(
            "raffle_id" => $id
        );
        $winners = $this->Raffle_draw_model->get_winners($filter_winner)->result();

        $filter_participant = array(
            "raffle_id" => $id
        );
        $participants = $this->Raffle_draw_model->get_participants($filter_participant)->row();

        echo json_encode( array(
            "raffle"=>$raffle,
            "winners"=>$winners,
            "total_participants"=>count($participants)
        ) );
    }

    public function play_raffle() {
        $id = $this->input->post('id');

        $filter_raffle = array(
            "id" => $id
        );
        $raffle = $this->Raffle_draw_model->get_details($filter_raffle)->row();

        $allwinner = $this->Raffle_draw_model->get_winners(array(
            "raffle_id" => $id
        ))->result();

        if( count($allwinner) >= $raffle->winners ) {
            echo json_encode(array("success" => false, 'message' => lang('winners_completed')));
            exit;
        }

        $filter = array( 
            "raffle_id" => $id, 
            "winners" => 1 
        );
        $winner = $this->Raffle_draw_model->pick_winners($filter)->row();

        if(count($winner) == 0) {
            echo json_encode(array("success" => false, 'message' => lang('no_participants')));
            exit;
        }

        //Save winners here.
        $saving = array(
            "uuid" => $this->uuid->v4(),
            "raffle_id" => $id,
            "user_id" => $winner->user_id,
            "remarks" => "App Draw"
        );
        $this->Raffle_draw_model->save_winner($saving);
        
        echo json_encode(array("success" => true, "winner"=>$winner ));
    }

    public function validate_entry() {
        $entry_id = $this->input->post('entry_id');

        $entry_object = $this->Raffle_draw_model->get_participants(array(
            "uuid" => $entry_id,
        ))->row();

        if(!$entry_object) {
            echo json_encode(array("success" => false, 'message' => "QR Code not valid!"));
            exit;
        }

        if($entry_object->user_id) {
            echo json_encode(array("success" => false, 'message' => "QR Code already used!"));
            exit;
        }

        echo json_encode(array("success" => true, "id" => $entry_object->id, "uuid" => $entry_object->uuid ));
    }

    public function submit_entry() {
        $id = $this->input->post('id');
        $entry_id = $this->input->post('entry_id');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $email_address = $this->input->post('email_address');
        if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array("success"=>false, "message"=>"Email address is invalid."));
            exit;
        }

        $phone_number = $this->input->post('phone_number'); //optional
        $remarks = $this->input->post('remarks'); //optional

        if(empty($id) || empty($entry_id) || empty($first_name) || empty($last_name) || empty($email_address) ) {
            echo json_encode(array("success"=>false, "message"=>"Please complete all required fields."));
            exit;
        }

        //Check if qrcode is valid or already assigned.
        $entry_object = $this->Raffle_draw_model->get_participants(array(
            "id" => $id,
            "uuid" => $entry_id,
        ))->row();

        if(!$entry_object) {
            echo json_encode(array("success" => false, 'message' => "QR Code not valid!"));
            exit;
        }

        if($entry_object->user_id) {
            echo json_encode(array("success" => false, 'message' => "QR Code already used!"));
            exit;
        }

        //Check if the user exist else create and get the id. use email to get id.
        $cur_user = $this->Users_model->is_email_exists($email_address);
        if (!$cur_user) {
            $user_data = array(
                "uuid" => $this->uuid->v4(),
                "first_name" => $first_name,
                "last_name" => $last_name,
                "email" => $email_address,
                "phone" => $phone_number,
                "user_type" => 'customer',
                "disable_login" => 1,
                "password" => password_hash($this->uuid->v4(), PASSWORD_DEFAULT),
                "created_at" => get_current_utc_time(),
            );
            $user_id = $this->Users_model->save($user_data);
            $cur_user = $this->Users_model->get_one($user_id);
        }

        // Set the user for this participant
        $success = $this->Raffle_draw_model->set_participant($id, $cur_user->id, $remarks);
        if(!$success) {
            echo json_encode(array("success" => false, 'message' => "User unable to claim the QR Code."));
            exit;
        }

        $raffle_object = $this->Raffle_draw_model->get_details(array(
            "status" => "active",
            "id" => $entry_object->raffle_id,
        ))->row();
        
        $this->email('raffle_subscription',  $raffle_object->id,  $raffle_object->title, $id, $first_name, $last_name, $phone_number, $email_address, $remarks);

        echo json_encode(array("success" => true, 'message' => "Congratulation! see your prize."));
    }

    public function list_winners() {
        $filter_winner = array(
            "order_by" => true
        );

        $winners = $this->Raffle_draw_model
            ->get_winners($filter_winner)
            ->result();

        echo json_encode( array(
            "data" => $winners
        ) );
    } 

    public function subscribe_now() {
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $email_address = $this->input->post('email_address');
        if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array("success"=>false, "message"=>"Email address is invalid."));
            exit;
        }
        $phone_number = $this->input->post('phone_number'); //optional

        if( empty($first_name) || empty($last_name) || empty($email_address) || empty($phone_number) ) {
            echo json_encode(array("success"=>false, "message"=>"Please complete all required fields."));
            exit;
        }

        //Check if the user exist else create and get the id. use email to get id.
        $cur_user = $this->Users_model->is_email_exists($email_address);
        if ($cur_user) {
            echo json_encode(array("success"=>true, "message"=>"The email address is already regaistered, you're all set!"));
            exit;
        }

        $participant_id = $this->uuid->v4();
        $user_data = array(
            "uuid" => $participant_id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email_address,
            "phone" => $phone_number,
            "user_type" => 'customer',
            "disable_login" => 1,
            "password" => password_hash($this->uuid->v4(), PASSWORD_DEFAULT),
            "created_at" => get_current_utc_time(),
        );
        $user_id = $this->Users_model->save($user_data);

        if(!$user_id) {
            echo json_encode(array("success"=>true, "message"=>"Something went wrong during the subcription creation."));
            exit;
        }

        $this->email('raffle_subscription', false, false, $participant_id, $first_name, $last_name, $phone_number, $email_address, $remarks);

        echo json_encode(array("success" => true, 'message' => "Visit our website for daily updates on the winners of our raffle and stay informed."));
    }

    public function validate_raffle() {
        $raffle_id = $this->input->post('raffle_id');

        $raffle_object = $this->Raffle_draw_model->get_details(array(
            "status" => "active",
            "uuid" => strtolower($raffle_id),
        ))->row();

        if(!$raffle_object) {
            echo json_encode(array("success" => false, 'message' => "Raffle code not valid!"));
            exit;
        }

        echo json_encode(array("success" => true, "data" => $raffle_object ));
    }

    public function join_raffle() {
        $id = $this->input->post('id');
        $raffle_id = $this->input->post('raffle_id');

        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $email_address = $this->input->post('email_address');
        if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array("success"=>false, "message"=>"Email address is invalid."));
            exit;
        }

        $phone_number = $this->input->post('phone_number'); //optional
        $remarks = $this->input->post('remarks'); //optional

        if(empty($id) || empty($raffle_id) || empty($first_name) || empty($last_name) || empty($email_address) ) {
            echo json_encode(array("success"=>false, "message"=>"Please complete all required fields."));
            exit;
        }
    
        //Check if the raffle id and uid is existing else error.
        $raffle_object = $this->Raffle_draw_model->get_details(array(
            "status" => "active",
            "id" => $id,
            "uuid" => $raffle_id,
        ))->row();

        if(!$raffle_object) {
            echo json_encode(array("success" => false, 'message' => "Raffle code not valid!"));
            exit;
        }

        if($raffle_object->current_participants >= $raffle_object->total_participants ) {
            echo json_encode(array("success" => false, 'message' => "Participants for this raffle is already full!"));
            exit;
        }

        //Check if the user exist else create and get the id. use email to get id.
        $cur_user = $this->Users_model->is_email_exists($email_address);
        if (!$cur_user) {
            $user_data = array(
                "uuid" => $this->uuid->v4(),
                "first_name" => $first_name,
                "last_name" => $last_name,
                "email" => $email_address,
                "phone" => $phone_number,
                "user_type" => 'customer',
                "disable_login" => 1,
                "password" => password_hash($this->uuid->v4(), PASSWORD_DEFAULT),
                "created_at" => get_current_utc_time(),
            );
            $user_id = $this->Users_model->save($user_data);
            $cur_user = $this->Users_model->get_one($user_id);
        }

        //Check if this user has a participant else error.
        $entry_object = $this->Raffle_draw_model->get_participants(array(
            "raffle_id" => $raffle_object->id,
            "user_id" => $cur_user->id,
        ))->row();

        if($entry_object) {
            echo json_encode(array("success" => true, 'message' => "The user is already a participant of this raffle."));
            exit;
        }

        // Join this user as participants.
        $participant_data = array(
            "uuid" => $this->uuid->v4(),
            "raffle_id" => $raffle_object->id,
            "user_id" => $cur_user->id,
            "remarks" => $remarks
        );
        $participant_id = $this->Raffle_draw_model->join_raffle($participant_data);
        if(!$participant_id) {
            echo json_encode(array("success" => false, 'data' => $participant_data, 'message' => "Something went wrong while joining the raffle."));
            exit;
        }

        //Get prize set.

        if($raffle_object->draw_preview === "via_email") {
            $this->email('raffle_join', $raffle_object->uuid, $raffle_object->title, $participant_id, $first_name, $last_name, $phone_number, $email_address, $remarks);
        } else if($raffle_object->draw_preview === "instant_show") {
            echo json_encode(array("success" => true, 'message' => "Congratulation! you are now a participant of this raffle.", "msg_content"=>"", "msg_url"=>""));
            exit;
        }

        echo json_encode(array("success" => true, 'message' => "Congratulation! you are now a participant of this raffle."));
    }

    private function email($template_id, $raffle_id, $raffle_title, $reference_id, $first_name, $last_name, $email, $phone, $remarks){
        $email_template = $this->Email_templates_model->get_final_template($template_id);

        if($raffle_id && $raffle_title) {
            $parser_data["RAFFLE_ID"] = $raffle_id;
            $parser_data["RAFFLE_TITLE"] = $raffle_title;
        }

        $parser_data["REFERENCE_ID"] = $reference_id;
        $parser_data["SIGNATURE"] = $email_template->signature;

        $parser_data["FIRST_NAME"] = $first_name;
        $parser_data["LAST_NAME"] = $last_name;
        $parser_data["PHONE_NUMBER"] = $phone;
        $parser_data["REMARKS"] = $remarks;

        $message = $this->parser->parse_string($email_template->message, $parser_data, TRUE);

        send_app_mail($email, $email_template->subject, $message, array(
            "bcc" => get_setting('site_admin_email')
        ));
    }
}
