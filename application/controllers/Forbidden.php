<?php

class Forbidden extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $view_data["heading"] = "Forbidden Access";
        $view_data["message"] = "Entrance to this page is restricted. You do not have permission to view it.";
        if ($this->input->is_ajax_request()) {
            $view_data["no_css"] = true;
        }
        $this->load->view("errors/html/error_general", $view_data);
    }

}
