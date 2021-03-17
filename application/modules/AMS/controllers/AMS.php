<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AMS extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    function index(){
        $this->validate_user_sub_module_permission("module_ats");
    }
}