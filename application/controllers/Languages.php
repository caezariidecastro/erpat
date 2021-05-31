<?php 
        
defined('BASEPATH') OR exit('No direct script access allowed');
        
class Languages extends MY_Controller {

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        redirect('forbidden');       
    }

    function default()
    {
        $this->header_application_json();

        //Default Temporary
        $file = 'translation.json';
        $json = file_get_contents(APPPATH.'/language/english/'.$file);
        
        echo json_encode(
            array(
                "data" => json_decode($json)
            )
        );
    }
}
        
/* End of file  Languages.php */                         