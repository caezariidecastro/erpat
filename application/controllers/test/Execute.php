<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Execute extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        redirect('forbidden');
    }
}

/* End of file Execute.php */
/* Location: ./application/controllers/test/Execute.php */