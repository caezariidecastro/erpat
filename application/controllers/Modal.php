<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modal extends MY_Controller
{
    function __construct() {
        parent::__construct();
    }

    function index() {
        redirect('forbidden');
    }

    function notify() {
        $view_data['system_message'] = $this->input->post('msg');
        $this->load->view("modal/notify", $view_data);
    }
}