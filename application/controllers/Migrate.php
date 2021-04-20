<?php

class Migrate extends CI_Controller
{
    public function index()
    {
        $this->load->library('migration');

        if ($this->migration->current() === FALSE) {
            echo json_encode( array( "success" => true, "message" => $this->migration->error_string() ) );
        } else {
            echo json_encode( array( "success" => true, "message" => "Database migration successfull." ) );
        }
    }
}