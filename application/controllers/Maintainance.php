<?php defined('BASEPATH') OR exit('No direct script access allowed');
        
class Maintainance extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->library('Uuid');
    }

    function uuid()
    {
        $this->header_application_json();

        $total = 0;
        $where = array("uuid"=>null);
        $users = $this->Users_model->get_all_where($where)->result();

        foreach($users as $user) {
            $where = array("uuid"=>null, "id"=>$user->id);

            $data = array("uuid"=>$this->uuid->v4());
            $success = $this->Users_model->update_where($data, $where);

            if($success) {
                $total += 1;
            }
        }

        echo json_encode(
            array("message"=>"Found and generated " .$total. " uuids for users table.")
        );
    }
}
        
/* End of file  Maintainance.php */
                        