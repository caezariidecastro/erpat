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

    public static function default_lang() {
        //Default Temporary
        $file = 'translation.json';
        $json = file_get_contents(APPPATH.'/language/english/'.$file);
        return json_decode($json);
    }

    function default()
    {
        $this->header_application_json();
        
        echo json_encode(
            array(
                "data" => self::default_lang()
            )
        );
    }
}
        
/* End of file  Languages.php */                         