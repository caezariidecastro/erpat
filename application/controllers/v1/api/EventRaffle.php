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
        $eventid = $this->input->post('eventid');

        $filter = array(
            "event_id" => $eventid,
            "status" => "active"
        );
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
}
